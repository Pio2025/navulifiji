<?php
namespace App\Models;
use CodeIgniter\Model;

class LessonQuizzeAttemptModel extends Model
{
    protected $table      = 'lesson_quizze_attempt';
    protected $primaryKey = 'attempt_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'quizze_id_fk', 'lesson_id_fk', 'user_id_fk',
        'started_at', 'submitted_at', 'status', 'time_remaining',
        'score', 'total_questions', 'correct_answers',
    ];

    public function getStudentAttempt(int $quizzeId, int $userId): ?array
    {
        return $this->where('quizze_id_fk', $quizzeId)
                    ->where('user_id_fk', $userId)
                    ->first();
    }

    /** Returns attempts keyed by quizze_id_fk for a given lesson + student. */
    public function getStudentAttemptsForLesson(int $lessonId, int $userId): array
    {
        $rows = $this->where('lesson_id_fk', $lessonId)
                     ->where('user_id_fk', $userId)
                     ->findAll();
        $keyed = [];
        foreach ($rows as $row) {
            $keyed[(int) $row['quizze_id_fk']] = $row;
        }
        return $keyed;
    }

    public function startAttempt(int $quizzeId, int $lessonId, int $userId): int
    {
        $this->insert([
            'quizze_id_fk' => $quizzeId,
            'lesson_id_fk' => $lessonId,
            'user_id_fk'   => $userId,
            'started_at'   => date('Y-m-d H:i:s'),
            'status'       => 'in_progress',
        ]);
        return (int) $this->db->insertID();
    }

    public function submitAttempt(int $attemptId, array $responses, string $status = 'submitted'): array
    {
        $db      = \Config\Database::connect();
        $attempt = $this->find($attemptId);
        if (!$attempt) {
            return ['success' => false, 'message' => 'Attempt not found.'];
        }

        $correct = 0;

        foreach ($responses as $questionId => $answerId) {
            $questionId = (int) $questionId;
            $answerId   = (int) $answerId;
            if (!$questionId || !$answerId) continue;

            $isCorrect = (int) $db->table('lesson_quizze_answer')
                ->where('lesson_quizze_answer_id', $answerId)
                ->where('quizze_quest_id_fk', $questionId)
                ->where('is_correct_answer', 1)
                ->countAllResults();

            $existing = $db->table('lesson_quizze_response')
                ->where('attempt_id_fk', $attemptId)
                ->where('question_id_fk', $questionId)
                ->get()->getRowArray();

            if ($existing) {
                $db->table('lesson_quizze_response')
                    ->where('response_id', $existing['response_id'])
                    ->update(['answer_id_fk' => $answerId, 'is_correct' => $isCorrect]);
            } else {
                $db->table('lesson_quizze_response')->insert([
                    'attempt_id_fk'  => $attemptId,
                    'question_id_fk' => $questionId,
                    'answer_id_fk'   => $answerId,
                    'is_correct'     => $isCorrect,
                ]);
            }

            if ($isCorrect) $correct++;
        }

        // Total = active questions in this quiz
        $totalQuestions = (int) $db->table('lesson_quizze_question')
            ->where('lesson_quizze_id_fk', $attempt['quizze_id_fk'])
            ->where('status', 'Active')
            ->countAllResults();

        $score = $totalQuestions > 0 ? round(($correct / $totalQuestions) * 100, 2) : 0;

        $this->update($attemptId, [
            'submitted_at'    => date('Y-m-d H:i:s'),
            'status'          => $status,
            'score'           => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correct,
        ]);

        return [
            'success'         => true,
            'score'           => $score,
            'correct_answers' => $correct,
            'total_questions' => $totalQuestions,
            'status'          => $status,
        ];
    }

    /** Returns [questionId => answerId] for an in-progress attempt (for resumption). */
    public function getSavedResponses(int $attemptId): array
    {
        $rows = \Config\Database::connect()
            ->table('lesson_quizze_response')
            ->where('attempt_id_fk', $attemptId)
            ->get()->getResultArray();
        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['question_id_fk']] = (int) $row['answer_id_fk'];
        }
        return $map;
    }

    public function getAttemptWithResponses(int $attemptId): ?array
    {
        $db      = \Config\Database::connect();
        $attempt = $this->find($attemptId);
        if (!$attempt) return null;

        $attempt['responses'] = $db->query("
            SELECT
                r.response_id, r.question_id_fk, r.answer_id_fk, r.is_correct,
                q.question,
                sa.answer AS selected_answer,
                ca.answer AS correct_answer_text
            FROM lesson_quizze_response r
            INNER JOIN lesson_quizze_question q  ON q.quizze_quest_id        = r.question_id_fk
            INNER JOIN lesson_quizze_answer   sa ON sa.lesson_quizze_answer_id = r.answer_id_fk
            LEFT  JOIN lesson_quizze_answer   ca ON ca.quizze_quest_id_fk    = r.question_id_fk
                                                 AND ca.is_correct_answer     = 1
            WHERE r.attempt_id_fk = ?
            ORDER BY q.quizze_quest_id ASC
        ", [$attemptId])->getResultArray();

        return $attempt;
    }
}
