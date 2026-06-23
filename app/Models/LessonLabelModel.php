<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonLabelModel extends Model
{
    protected $table      = 'lesson_label_question';
    protected $primaryKey = 'label_question_id';
    protected $returnType = 'array';

    protected $allowedFields = ['quizze_id_fk', 'question_text', 'bg_image', 'question_order'];

    // ── Data getters ──────────────────────────────────────────────────────────

    /** Returns questions[] with markers[] for the assessment. */
    public function getAssessmentData(int $quizzeId): array
    {
        $db        = \Config\Database::connect();
        $questions = $db->table('lesson_label_question')
            ->where('quizze_id_fk', $quizzeId)
            ->orderBy('question_order', 'ASC')
            ->orderBy('label_question_id', 'ASC')
            ->get()->getResultArray();

        foreach ($questions as &$q) {
            $q['markers'] = $db->table('lesson_label_marker')
                ->where('label_question_id_fk', $q['label_question_id'])
                ->orderBy('marker_order', 'ASC')
                ->orderBy('marker_id', 'ASC')
                ->get()->getResultArray();
        }
        unset($q);

        return $questions;
    }

    public function getTotalMarkerCount(int $quizzeId): int
    {
        $db          = \Config\Database::connect();
        $questionIds = array_column(
            $db->table('lesson_label_question')
               ->select('label_question_id')
               ->where('quizze_id_fk', $quizzeId)
               ->get()->getResultArray(),
            'label_question_id'
        );
        if (empty($questionIds)) return 0;
        return (int) $db->table('lesson_label_marker')
            ->whereIn('label_question_id_fk', $questionIds)
            ->countAllResults();
    }

    public function getAttemptCounts(int $quizzeId): int
    {
        return (int) \Config\Database::connect()
            ->table('lesson_label_attempt')
            ->where('quizze_id_fk', $quizzeId)
            ->where('status', 'submitted')
            ->countAllResults();
    }

    // ── Student-facing methods ────────────────────────────────────────────────

    public function getStudentAttempt(int $quizzeId, int $userId): ?array
    {
        $row = \Config\Database::connect()
            ->table('lesson_label_attempt')
            ->where('quizze_id_fk', $quizzeId)
            ->where('user_id_fk', $userId)
            ->get()->getRowArray();
        return $row ?: null;
    }

    public function getStudentAttemptsForLesson(int $lessonId, int $userId): array
    {
        $rows = \Config\Database::connect()
            ->table('lesson_label_attempt')
            ->where('lesson_id_fk', $lessonId)
            ->where('user_id_fk', $userId)
            ->get()->getResultArray();
        $keyed = [];
        foreach ($rows as $row) {
            $keyed[(int) $row['quizze_id_fk']] = $row;
        }
        return $keyed;
    }

    public function startAttempt(int $quizzeId, int $lessonId, int $userId): int
    {
        $db = \Config\Database::connect();
        $db->table('lesson_label_attempt')->insert([
            'quizze_id_fk' => $quizzeId,
            'lesson_id_fk' => $lessonId,
            'user_id_fk'   => $userId,
            'started_at'   => date('Y-m-d H:i:s'),
            'status'       => 'in_progress',
        ]);
        return (int) $db->insertID();
    }

    /**
     * Score and persist a label submission.
     * $answers = [ marker_id => student_label_text, ... ]
     * Scoring: case-insensitive trimmed exact match.
     */
    public function submitAttempt(int $attemptId, array $answers, string $status = 'submitted'): array
    {
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_label_attempt')
            ->where('attempt_id', $attemptId)->get()->getRowArray();
        if (!$attempt) return ['success' => false, 'message' => 'Attempt not found.'];

        $quizzeId = (int) $attempt['quizze_id_fk'];

        // Get all markers for this assessment
        $questionIds = array_column(
            $db->table('lesson_label_question')
               ->select('label_question_id')
               ->where('quizze_id_fk', $quizzeId)
               ->get()->getResultArray(),
            'label_question_id'
        );

        $allMarkers = empty($questionIds) ? [] :
            $db->table('lesson_label_marker')
               ->whereIn('label_question_id_fk', $questionIds)
               ->get()->getResultArray();

        $totalMarkers = count($allMarkers);
        $correct      = 0;

        $db->table('lesson_label_attempt_answer')->where('attempt_id_fk', $attemptId)->delete();

        foreach ($allMarkers as $marker) {
            $markerId     = (int) $marker['marker_id'];
            $studentLabel = trim($answers[$markerId] ?? ($answers[(string) $markerId] ?? ''));
            $correctLabel = trim($marker['correct_label']);
            $isCorrect    = (strtolower($studentLabel) === strtolower($correctLabel) && $studentLabel !== '') ? 1 : 0;
            if ($isCorrect) $correct++;
            $db->table('lesson_label_attempt_answer')->insert([
                'attempt_id_fk' => $attemptId,
                'marker_id_fk'  => $markerId,
                'student_label' => $studentLabel,
                'is_correct'    => $isCorrect,
            ]);
        }

        $score = $totalMarkers > 0 ? round(($correct / $totalMarkers) * 100, 2) : 0;
        $db->table('lesson_label_attempt')->where('attempt_id', $attemptId)->update([
            'submitted_at'   => date('Y-m-d H:i:s'),
            'status'         => $status,
            'score'          => $score,
            'total_markers'  => $totalMarkers,
            'correct_markers'=> $correct,
        ]);

        return [
            'success'         => true,
            'score'           => $score,
            'correct_markers' => $correct,
            'total_markers'   => $totalMarkers,
            'status'          => $status,
        ];
    }

    /** Returns [markerId => student_label] for an in-progress attempt (for resumption). */
    public function getSavedAnswers(int $attemptId): array
    {
        $rows = \Config\Database::connect()
            ->table('lesson_label_attempt_answer')
            ->where('attempt_id_fk', $attemptId)
            ->get()->getResultArray();
        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['marker_id_fk']] = $row['student_label'] ?? '';
        }
        return $map;
    }

    /** Load attempt enriched with per-question/marker data for score/transcript views. */
    public function getAttemptWithAnswers(int $attemptId): ?array
    {
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_label_attempt')
            ->where('attempt_id', $attemptId)->get()->getRowArray();
        if (!$attempt) return null;

        $quizzeId  = (int) $attempt['quizze_id_fk'];
        $questions = $db->table('lesson_label_question')
            ->where('quizze_id_fk', $quizzeId)
            ->orderBy('question_order','ASC')->orderBy('label_question_id','ASC')
            ->get()->getResultArray();

        // Build answer map: marker_id => { student_label, is_correct }
        $answerRows = $db->table('lesson_label_attempt_answer')
            ->where('attempt_id_fk', $attemptId)->get()->getResultArray();
        $answerMap  = [];
        foreach ($answerRows as $a) {
            $answerMap[(int) $a['marker_id_fk']] = $a;
        }

        foreach ($questions as &$q) {
            $markers = $db->table('lesson_label_marker')
                ->where('label_question_id_fk', $q['label_question_id'])
                ->orderBy('marker_order','ASC')->orderBy('marker_id','ASC')
                ->get()->getResultArray();

            foreach ($markers as &$m) {
                $mid                  = (int) $m['marker_id'];
                $ans                  = $answerMap[$mid] ?? null;
                $m['student_label']   = $ans['student_label'] ?? '';
                $m['is_correct']      = (bool) ($ans['is_correct'] ?? 0);
                $m['is_answered']     = $ans !== null && $ans['student_label'] !== '';
            }
            unset($m);
            $q['markers'] = $markers;
        }
        unset($q);

        $attempt['questions'] = $questions;
        return $attempt;
    }

    // ── Cascade delete ────────────────────────────────────────────────────────

    public function deleteAll(int $quizzeId): void
    {
        $db = \Config\Database::connect();

        // Attempt answers → attempts
        $attemptIds = array_column(
            $db->table('lesson_label_attempt')
               ->select('attempt_id')->where('quizze_id_fk', $quizzeId)
               ->get()->getResultArray(),
            'attempt_id'
        );
        if ($attemptIds) {
            $db->table('lesson_label_attempt_answer')
               ->whereIn('attempt_id_fk', $attemptIds)->delete();
        }
        $db->table('lesson_label_attempt')->where('quizze_id_fk', $quizzeId)->delete();

        // Markers → questions
        $questions = $db->table('lesson_label_question')
            ->where('quizze_id_fk', $quizzeId)->get()->getResultArray();

        foreach ($questions as $q) {
            $db->table('lesson_label_marker')
               ->where('label_question_id_fk', $q['label_question_id'])->delete();
            if (!empty($q['bg_image'])) {
                $path = FCPATH . 'uploads/label_images/' . $q['bg_image'];
                if (file_exists($path)) unlink($path);
            }
        }
        $db->table('lesson_label_question')->where('quizze_id_fk', $quizzeId)->delete();
    }

    // ── Table creation ────────────────────────────────────────────────────────

    public function ensureTables(): void
    {
        $db    = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('lesson_label_question')) {
            $forge->addField([
                'label_question_id' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
                'quizze_id_fk'      => ['type'=>'INT','unsigned'=>true],
                'question_text'     => ['type'=>'VARCHAR','constraint'=>500,'default'=>''],
                'bg_image'          => ['type'=>'VARCHAR','constraint'=>260,'null'=>true,'default'=>null],
                'question_order'    => ['type'=>'INT','default'=>0],
            ]);
            $forge->addPrimaryKey('label_question_id');
            $forge->addKey('quizze_id_fk');
            $forge->createTable('lesson_label_question', true);
        }

        if (!$db->tableExists('lesson_label_marker')) {
            $forge->addField([
                'marker_id'               => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
                'label_question_id_fk'    => ['type'=>'INT','unsigned'=>true],
                'marker_x'                => ['type'=>'DECIMAL','constraint'=>'5,2'],
                'marker_y'                => ['type'=>'DECIMAL','constraint'=>'5,2'],
                'correct_label'           => ['type'=>'VARCHAR','constraint'=>300],
                'marker_order'            => ['type'=>'INT','default'=>0],
            ]);
            $forge->addPrimaryKey('marker_id');
            $forge->addKey('label_question_id_fk');
            $forge->createTable('lesson_label_marker', true);
        }

        if (!$db->tableExists('lesson_label_attempt')) {
            $forge->addField([
                'attempt_id'      => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
                'quizze_id_fk'    => ['type'=>'INT','unsigned'=>true],
                'lesson_id_fk'    => ['type'=>'INT','unsigned'=>true],
                'user_id_fk'      => ['type'=>'INT','unsigned'=>true],
                'started_at'      => ['type'=>'DATETIME'],
                'submitted_at'    => ['type'=>'DATETIME','null'=>true,'default'=>null],
                'status'          => ['type'=>'VARCHAR','constraint'=>60,'default'=>'in_progress'],
                'score'           => ['type'=>'DECIMAL','constraint'=>'5,2','null'=>true,'default'=>null],
                'total_markers'   => ['type'=>'INT','null'=>true,'default'=>null],
                'correct_markers' => ['type'=>'INT','null'=>true,'default'=>null],
            ]);
            $forge->addPrimaryKey('attempt_id');
            $forge->addKey(['quizze_id_fk','user_id_fk']);
            $forge->createTable('lesson_label_attempt', true);
        }

        if (!$db->tableExists('lesson_label_attempt_answer')) {
            $forge->addField([
                'id'             => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
                'attempt_id_fk'  => ['type'=>'INT','unsigned'=>true],
                'marker_id_fk'   => ['type'=>'INT','unsigned'=>true],
                'student_label'  => ['type'=>'VARCHAR','constraint'=>300,'default'=>''],
                'is_correct'     => ['type'=>'TINYINT','constraint'=>1,'default'=>0],
            ]);
            $forge->addPrimaryKey('id');
            $forge->addKey('attempt_id_fk');
            $forge->createTable('lesson_label_attempt_answer', true);
        }
    }
}
