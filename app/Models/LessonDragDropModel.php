<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonDragDropModel extends Model
{
    protected $table      = 'lesson_dragdrop_item';
    protected $primaryKey = 'item_id';
    protected $returnType = 'array';

    protected $allowedFields = ['quizze_id_fk', 'item_text', 'item_image', 'item_order'];

    // ── Public data getters ───────────────────────────────────────────────

    public function getAssessmentData(int $quizzeId): array
    {
        return [
            'items'   => $this->getItems($quizzeId),
            'zones'   => $this->getZones($quizzeId),
            'answers' => $this->getAnswerMap($quizzeId),
        ];
    }

    public function getItems(int $quizzeId): array
    {
        return \Config\Database::connect()
            ->table('lesson_dragdrop_item')
            ->where('quizze_id_fk', $quizzeId)
            ->orderBy('item_order', 'ASC')
            ->orderBy('item_id', 'ASC')
            ->get()->getResultArray();
    }

    public function getZones(int $quizzeId): array
    {
        return \Config\Database::connect()
            ->table('lesson_dragdrop_zone')
            ->where('quizze_id_fk', $quizzeId)
            ->orderBy('zone_order', 'ASC')
            ->orderBy('zone_id', 'ASC')
            ->get()->getResultArray();
    }

    /** Returns [item_id => zone_id] keyed map for easy lookup. */
    public function getAnswerMap(int $quizzeId): array
    {
        $rows = \Config\Database::connect()
            ->table('lesson_dragdrop_answer')
            ->where('quizze_id_fk', $quizzeId)
            ->get()->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['item_id_fk']] = (int) $row['zone_id_fk'];
        }
        return $map;
    }

    public function getAttemptCounts(int $quizzeId): int
    {
        return (int) \Config\Database::connect()
            ->table('lesson_dragdrop_attempt')
            ->where('quizze_id_fk', $quizzeId)
            ->whereIn('status', ['submitted'])
            ->countAllResults();
    }

    // ── Student-facing methods ─────────────────────────────────────────────────

    public function getStudentAttempt(int $quizzeId, int $userId): ?array
    {
        $row = \Config\Database::connect()
            ->table('lesson_dragdrop_attempt')
            ->where('quizze_id_fk', $quizzeId)
            ->where('user_id_fk', $userId)
            ->get()->getRowArray();
        return $row ?: null;
    }

    /** Returns attempts keyed by quizze_id_fk for a given lesson + student. */
    public function getStudentAttemptsForLesson(int $lessonId, int $userId): array
    {
        $rows = \Config\Database::connect()
            ->table('lesson_dragdrop_attempt')
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
        $db->table('lesson_dragdrop_attempt')->insert([
            'quizze_id_fk' => $quizzeId,
            'lesson_id_fk' => $lessonId,
            'user_id_fk'   => $userId,
            'started_at'   => date('Y-m-d H:i:s'),
            'status'       => 'in_progress',
        ]);
        return (int) $db->insertID();
    }

    /**
     * Score and persist a student's drag-drop submission.
     * $itemPlacements = [ item_id => zone_id | null, ... ]
     */
    public function submitAttempt(int $attemptId, array $itemPlacements, string $status = 'submitted'): array
    {
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_dragdrop_attempt')
            ->where('attempt_id', $attemptId)->get()->getRowArray();
        if (!$attempt) return ['success' => false, 'message' => 'Attempt not found.'];

        $quizzeId  = (int) $attempt['quizze_id_fk'];
        $answerMap = $this->getAnswerMap($quizzeId);

        // Iterate ALL items so unplaced items are recorded as incorrect
        $allItems   = $db->table('lesson_dragdrop_item')
            ->where('quizze_id_fk', $quizzeId)->get()->getResultArray();
        $totalItems = count($allItems);
        $correct    = 0;

        $db->table('lesson_dragdrop_attempt_item')->where('attempt_id_fk', $attemptId)->delete();

        foreach ($allItems as $item) {
            $itemId    = (int) $item['item_id'];
            $raw       = $itemPlacements[$itemId] ?? ($itemPlacements[(string) $itemId] ?? null);
            $zoneId    = ($raw && (int) $raw > 0) ? (int) $raw : null;
            $isCorrect = ($zoneId !== null && isset($answerMap[$itemId]) && $answerMap[$itemId] === $zoneId) ? 1 : 0;
            if ($isCorrect) $correct++;
            $db->table('lesson_dragdrop_attempt_item')->insert([
                'attempt_id_fk' => $attemptId,
                'item_id_fk'    => $itemId,
                'zone_id_fk'    => $zoneId,
                'is_correct'    => $isCorrect,
            ]);
        }

        $score = $totalItems > 0 ? round(($correct / $totalItems) * 100, 2) : 0;
        $db->table('lesson_dragdrop_attempt')->where('attempt_id', $attemptId)->update([
            'submitted_at'  => date('Y-m-d H:i:s'),
            'status'        => $status,
            'score'         => $score,
            'total_items'   => $totalItems,
            'correct_items' => $correct,
        ]);

        return [
            'success'       => true,
            'score'         => $score,
            'correct_items' => $correct,
            'total_items'   => $totalItems,
            'status'        => $status,
        ];
    }

    /** Returns [itemId => zoneId|null] for an in-progress attempt (for resumption). */
    public function getSavedPlacements(int $attemptId): array
    {
        $rows = \Config\Database::connect()
            ->table('lesson_dragdrop_attempt_item')
            ->where('attempt_id_fk', $attemptId)
            ->get()->getResultArray();
        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['item_id_fk']] = $row['zone_id_fk'] ? (int) $row['zone_id_fk'] : null;
        }
        return $map;
    }

    /** Load attempt + enriched per-item data for score/transcript views. */
    public function getAttemptWithItems(int $attemptId): ?array
    {
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_dragdrop_attempt')
            ->where('attempt_id', $attemptId)->get()->getRowArray();
        if (!$attempt) return null;

        $quizzeId = (int) $attempt['quizze_id_fk'];

        $items = $db->table('lesson_dragdrop_item')
            ->where('quizze_id_fk', $quizzeId)
            ->orderBy('item_order', 'ASC')->orderBy('item_id', 'ASC')
            ->get()->getResultArray();

        $zones = $db->table('lesson_dragdrop_zone')
            ->where('quizze_id_fk', $quizzeId)
            ->orderBy('zone_order', 'ASC')->orderBy('zone_id', 'ASC')
            ->get()->getResultArray();

        $answerMap = $this->getAnswerMap($quizzeId);
        $zoneById  = array_column($zones, null, 'zone_id');

        $attemptItems = $db->table('lesson_dragdrop_attempt_item')
            ->where('attempt_id_fk', $attemptId)->get()->getResultArray();
        $studentMap = [];
        foreach ($attemptItems as $ai) {
            $studentMap[(int) $ai['item_id_fk']] = $ai['zone_id_fk'] ? (int) $ai['zone_id_fk'] : null;
        }

        foreach ($items as &$item) {
            $itemId                     = (int) $item['item_id'];
            $corrZone                   = $answerMap[$itemId] ?? null;
            $stuZone                    = $studentMap[$itemId] ?? null;
            $item['correct_zone_id']    = $corrZone;
            $item['correct_zone_label'] = $corrZone ? ($zoneById[$corrZone]['zone_label'] ?? '—') : '—';
            $item['student_zone_id']    = $stuZone;
            $item['student_zone_label'] = $stuZone ? ($zoneById[$stuZone]['zone_label'] ?? 'Not placed') : 'Not placed';
            $item['is_correct']         = ($corrZone !== null && $stuZone !== null && $corrZone === $stuZone);
            $item['is_placed']          = $stuZone !== null;
        }
        unset($item);

        $attempt['items'] = $items;
        $attempt['zones'] = $zones;
        return $attempt;
    }

    // ── Cascade delete ────────────────────────────────────────────────────

    public function deleteAll(int $quizzeId): void
    {
        $db = \Config\Database::connect();

        $attemptIds = array_column(
            $db->table('lesson_dragdrop_attempt')
               ->select('attempt_id')
               ->where('quizze_id_fk', $quizzeId)
               ->get()->getResultArray(),
            'attempt_id'
        );
        if ($attemptIds) {
            $db->table('lesson_dragdrop_attempt_item')
               ->whereIn('attempt_id_fk', $attemptIds)->delete();
        }
        $db->table('lesson_dragdrop_attempt')->where('quizze_id_fk', $quizzeId)->delete();
        $db->table('lesson_dragdrop_answer')->where('quizze_id_fk', $quizzeId)->delete();

        $items = $db->table('lesson_dragdrop_item')
            ->where('quizze_id_fk', $quizzeId)->get()->getResultArray();
        foreach ($items as $item) {
            if (!empty($item['item_image'])) {
                $path = FCPATH . 'uploads/dragdrop_files/' . $item['item_image'];
                if (file_exists($path)) unlink($path);
            }
        }
        $db->table('lesson_dragdrop_item')->where('quizze_id_fk', $quizzeId)->delete();
        $db->table('lesson_dragdrop_zone')->where('quizze_id_fk', $quizzeId)->delete();
    }

    // ── Table creation ────────────────────────────────────────────────────

    public function ensureTables(): void
    {
        $db    = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // Add assessment_type to lesson_quizze if missing
        if ($db->tableExists('lesson_quizze') && !$db->fieldExists('assessment_type', 'lesson_quizze')) {
            $forge->addColumn('lesson_quizze', [
                'assessment_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 60,
                    'default'    => 'quiz',
                    'after'      => 'lesson_id_fk',
                ],
            ]);
        }

        if (!$db->tableExists('lesson_dragdrop_item')) {
            $forge->addField([
                'item_id'      => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'quizze_id_fk' => ['type' => 'INT', 'unsigned' => true],
                'item_text'    => ['type' => 'VARCHAR', 'constraint' => 500],
                'item_image'   => ['type' => 'VARCHAR', 'constraint' => 260, 'null' => true, 'default' => null],
                'item_order'   => ['type' => 'INT', 'default' => 0],
            ]);
            $forge->addPrimaryKey('item_id');
            $forge->addKey('quizze_id_fk');
            $forge->createTable('lesson_dragdrop_item', true);
        }

        if (!$db->tableExists('lesson_dragdrop_zone')) {
            $forge->addField([
                'zone_id'      => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'quizze_id_fk' => ['type' => 'INT', 'unsigned' => true],
                'zone_label'   => ['type' => 'VARCHAR', 'constraint' => 500],
                'zone_order'   => ['type' => 'INT', 'default' => 0],
            ]);
            $forge->addPrimaryKey('zone_id');
            $forge->addKey('quizze_id_fk');
            $forge->createTable('lesson_dragdrop_zone', true);
        }

        if (!$db->tableExists('lesson_dragdrop_answer')) {
            $forge->addField([
                'answer_id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'quizze_id_fk' => ['type' => 'INT', 'unsigned' => true],
                'item_id_fk'   => ['type' => 'INT', 'unsigned' => true],
                'zone_id_fk'   => ['type' => 'INT', 'unsigned' => true],
            ]);
            $forge->addPrimaryKey('answer_id');
            $forge->addUniqueKey('item_id_fk');
            $forge->addKey('quizze_id_fk');
            $forge->createTable('lesson_dragdrop_answer', true);
        }

        if (!$db->tableExists('lesson_dragdrop_attempt')) {
            $forge->addField([
                'attempt_id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'quizze_id_fk'  => ['type' => 'INT', 'unsigned' => true],
                'lesson_id_fk'  => ['type' => 'INT', 'unsigned' => true],
                'user_id_fk'    => ['type' => 'INT', 'unsigned' => true],
                'started_at'    => ['type' => 'DATETIME'],
                'submitted_at'  => ['type' => 'DATETIME', 'null' => true, 'default' => null],
                'status'        => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => 'in_progress'],
                'score'         => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'default' => null],
                'total_items'   => ['type' => 'INT', 'null' => true, 'default' => null],
                'correct_items' => ['type' => 'INT', 'null' => true, 'default' => null],
            ]);
            $forge->addPrimaryKey('attempt_id');
            $forge->addKey(['quizze_id_fk', 'user_id_fk']);
            $forge->createTable('lesson_dragdrop_attempt', true);
        }

        if (!$db->tableExists('lesson_dragdrop_attempt_item')) {
            $forge->addField([
                'id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'attempt_id_fk' => ['type' => 'INT', 'unsigned' => true],
                'item_id_fk'    => ['type' => 'INT', 'unsigned' => true],
                'zone_id_fk'    => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'default' => null],
                'is_correct'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            ]);
            $forge->addPrimaryKey('id');
            $forge->addKey('attempt_id_fk');
            $forge->createTable('lesson_dragdrop_attempt_item', true);
        }
    }
}
