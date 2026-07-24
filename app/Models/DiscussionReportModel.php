<?php
namespace App\Models;
use CodeIgniter\Model;

/**
 * Generic report/moderation store shared by both Classroom Discussion and
 * Lesson Discussion, across posts, comments, and nested replies. Content is
 * identified by a (content_type, content_id) pair rather than a dedicated
 * table per content type, since the moderation lifecycle (report -> vote ->
 * review -> decide) is identical across all six content types.
 */
class DiscussionReportModel extends Model
{
    public const TYPE_CLASS_POST     = 'class_post';
    public const TYPE_CLASS_COMMENT  = 'class_comment';
    public const TYPE_CLASS_REPLY    = 'class_reply';
    public const TYPE_LESSON_POST    = 'lesson_post';
    public const TYPE_LESSON_COMMENT = 'lesson_comment';
    public const TYPE_LESSON_REPLY   = 'lesson_reply';

    public function ensureTables(): void
    {
        $db    = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('discussion_report')) {
            $forge->addField([
                'report_id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'content_type'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
                'content_id'        => ['type' => 'INT', 'null' => false],
                'content_author_id' => ['type' => 'INT', 'null' => false],
                'reporter_id_fk'    => ['type' => 'INT', 'null' => false],
                'reason'            => ['type' => 'TEXT', 'null' => false],
                'report_status'     => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Pending'],
                'resolved_by_fk'    => ['type' => 'INT', 'null' => true],
                'resolved_at'       => ['type' => 'DATETIME', 'null' => true],
                'created_at'        => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addPrimaryKey('report_id');
            $forge->addUniqueKey(['content_type', 'content_id', 'reporter_id_fk']);
            $forge->createTable('discussion_report', true);
        }

        if (!$db->tableExists('discussion_report_vote')) {
            $forge->addField([
                'vote_id'      => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'report_id_fk' => ['type' => 'INT', 'null' => false],
                'user_id_fk'   => ['type' => 'INT', 'null' => false],
                'vote_type'    => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => false],
            ]);
            $forge->addPrimaryKey('vote_id');
            $forge->addUniqueKey(['report_id_fk', 'user_id_fk']);
            $forge->createTable('discussion_report_vote', true);
        }
    }

    public static function contentTypes(): array
    {
        return [
            self::TYPE_CLASS_POST, self::TYPE_CLASS_COMMENT, self::TYPE_CLASS_REPLY,
            self::TYPE_LESSON_POST, self::TYPE_LESSON_COMMENT, self::TYPE_LESSON_REPLY,
        ];
    }

    /** Submits a report, or returns the reporter's existing report for this content unchanged. */
    public function submitReport(string $contentType, int $contentId, int $contentAuthorId, int $reporterId, string $reason): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('discussion_report')
            ->where('content_type', $contentType)->where('content_id', $contentId)
            ->where('reporter_id_fk', $reporterId)->get()->getRowArray();
        if ($existing) {
            return $existing;
        }

        $db->table('discussion_report')->insert([
            'content_type'      => $contentType,
            'content_id'        => $contentId,
            'content_author_id' => $contentAuthorId,
            'reporter_id_fk'    => $reporterId,
            'reason'            => $reason,
            'report_status'     => 'Pending',
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        return $db->table('discussion_report')
            ->where('content_type', $contentType)->where('content_id', $contentId)
            ->where('reporter_id_fk', $reporterId)->get()->getRowArray();
    }

    public function pendingSummaryForContent(string $contentType, int $contentId): array
    {
        $count = (int) \Config\Database::connect()->table('discussion_report')
            ->where('content_type', $contentType)->where('content_id', $contentId)
            ->where('report_status', 'Pending')->countAllResults();
        return ['is_reported' => $count > 0, 'report_count' => $count];
    }

    public function getReportsForContent(string $contentType, int $contentId, int $userId): array
    {
        $rows = \Config\Database::connect()->query("
            SELECT
                dr.report_id, dr.reason, dr.report_status, dr.created_at,
                dr.reporter_id_fk,
                CONCAT(u.fname, ' ', u.lname) AS reporter_name,
                u.profile_photo AS reporter_photo,
                (SELECT COUNT(*) FROM discussion_report_vote v WHERE v.report_id_fk = dr.report_id AND v.vote_type = 'support') AS support_count,
                (SELECT COUNT(*) FROM discussion_report_vote v WHERE v.report_id_fk = dr.report_id AND v.vote_type = 'oppose') AS oppose_count,
                (SELECT v2.vote_type FROM discussion_report_vote v2 WHERE v2.report_id_fk = dr.report_id AND v2.user_id_fk = ? LIMIT 1) AS my_vote
            FROM discussion_report dr
            INNER JOIN users u ON u.user_id = dr.reporter_id_fk
            WHERE dr.content_type = ? AND dr.content_id = ?
            ORDER BY dr.created_at ASC
        ", [$userId, $contentType, $contentId])->getResultArray();

        foreach ($rows as &$row) {
            $row['report_id']     = (int) $row['report_id'];
            $row['support_count'] = (int) $row['support_count'];
            $row['oppose_count']  = (int) $row['oppose_count'];
        }
        return $rows;
    }

    public function vote(int $reportId, int $userId, string $type): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('discussion_report_vote')
            ->where('report_id_fk', $reportId)->where('user_id_fk', $userId)->get()->getRowArray();

        if (!$existing) {
            $db->table('discussion_report_vote')->insert(['report_id_fk' => $reportId, 'user_id_fk' => $userId, 'vote_type' => $type]);
            $myVote = $type;
        } elseif ($existing['vote_type'] === $type) {
            $db->table('discussion_report_vote')->where('report_id_fk', $reportId)->where('user_id_fk', $userId)->delete();
            $myVote = null;
        } else {
            $db->table('discussion_report_vote')->where('report_id_fk', $reportId)->where('user_id_fk', $userId)->update(['vote_type' => $type]);
            $myVote = $type;
        }

        return [
            'my_vote'       => $myVote,
            'support_count' => (int) $db->table('discussion_report_vote')->where('report_id_fk', $reportId)->where('vote_type', 'support')->countAllResults(),
            'oppose_count'  => (int) $db->table('discussion_report_vote')->where('report_id_fk', $reportId)->where('vote_type', 'oppose')->countAllResults(),
        ];
    }

    public function getReport(int $reportId): ?array
    {
        return \Config\Database::connect()->table('discussion_report')->where('report_id', $reportId)->get()->getRowArray() ?: null;
    }

    /**
     * Resolves the whole flag on this report's content: every still-Pending report
     * against the same (content_type, content_id) is resolved together, since the
     * flag shown to viewers is an aggregate of all reports on that content.
     */
    public function decide(int $reportId, int $moderatorId, string $decision): ?array
    {
        $report = $this->getReport($reportId);
        if (!$report) {
            return null;
        }

        \Config\Database::connect()->table('discussion_report')
            ->where('content_type', $report['content_type'])
            ->where('content_id', $report['content_id'])
            ->where('report_status', 'Pending')
            ->update([
                'report_status'  => $decision === 'delete' ? 'Actioned' : 'Dismissed',
                'resolved_by_fk' => $moderatorId,
                'resolved_at'    => date('Y-m-d H:i:s'),
            ]);

        return $report;
    }

    /** Total-reported and total-actioned counts for a content author, across all discussion types. */
    public function reportHistoryForUser(int $userId): array
    {
        $db = \Config\Database::connect();
        return [
            'total_reported' => (int) $db->table('discussion_report')->where('content_author_id', $userId)->countAllResults(),
            'total_actioned' => (int) $db->table('discussion_report')->where('content_author_id', $userId)->where('report_status', 'Actioned')->countAllResults(),
        ];
    }

    /** Detail shown when tapping a "Deleted post/comment/reply" placeholder: the resolved reports plus the author's history. */
    public function getRemovalDetail(string $contentType, int $contentId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT dr.reason, dr.created_at, dr.resolved_at, dr.content_author_id,
                   CONCAT(u.fname, ' ', u.lname) AS reporter_name,
                   CONCAT(m.fname, ' ', m.lname) AS resolved_by_name
            FROM discussion_report dr
            INNER JOIN users u ON u.user_id = dr.reporter_id_fk
            LEFT JOIN users m ON m.user_id = dr.resolved_by_fk
            WHERE dr.content_type = ? AND dr.content_id = ? AND dr.report_status = 'Actioned'
            ORDER BY dr.created_at ASC
        ", [$contentType, $contentId])->getResultArray();

        $authorId = $rows[0]['content_author_id'] ?? null;
        return [
            'reports'      => $rows,
            'resolved_at'  => $rows[0]['resolved_at'] ?? null,
            'resolved_by'  => $rows[0]['resolved_by_name'] ?? null,
            'author_history' => $authorId !== null ? $this->reportHistoryForUser((int) $authorId) : ['total_reported' => 0, 'total_actioned' => 0],
        ];
    }

    /** Pending reports across both discussion types, scoped to a single classroom. */
    public function queuePendingForClassroom(int $classId): array
    {
        $rows = \Config\Database::connect()->query("
            SELECT report_id, content_type, content_id, content_author_id, reason, created_at, reporter_id_fk FROM discussion_report
            WHERE report_status = 'Pending' AND content_type = 'class_post' AND content_id IN (
                SELECT cd_id FROM class_discussion WHERE class_id_fk = ?
            )
            UNION ALL
            SELECT report_id, content_type, content_id, content_author_id, reason, created_at, reporter_id_fk FROM discussion_report
            WHERE report_status = 'Pending' AND content_type = 'class_comment' AND content_id IN (
                SELECT cdc.cdc_id FROM class_discussion_comment cdc
                INNER JOIN class_discussion cd ON cd.cd_id = cdc.cd_id_fk WHERE cd.class_id_fk = ?
            )
            UNION ALL
            SELECT report_id, content_type, content_id, content_author_id, reason, created_at, reporter_id_fk FROM discussion_report
            WHERE report_status = 'Pending' AND content_type = 'class_reply' AND content_id IN (
                SELECT r.cdcr_id FROM class_discussion_comment_reply r
                INNER JOIN class_discussion_comment cdc ON cdc.cdc_id = r.cdc_id_fk
                INNER JOIN class_discussion cd ON cd.cd_id = cdc.cd_id_fk WHERE cd.class_id_fk = ?
            )
            UNION ALL
            SELECT report_id, content_type, content_id, content_author_id, reason, created_at, reporter_id_fk FROM discussion_report
            WHERE report_status = 'Pending' AND content_type = 'lesson_post' AND content_id IN (
                SELECT ld.lesson_discussion_id FROM lesson_discussion ld
                INNER JOIN classroom_lesson cl ON cl.lesson_id = ld.lesson_id_fk
                INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk WHERE cs.class_id_fk = ?
            )
            UNION ALL
            SELECT report_id, content_type, content_id, content_author_id, reason, created_at, reporter_id_fk FROM discussion_report
            WHERE report_status = 'Pending' AND content_type = 'lesson_comment' AND content_id IN (
                SELECT ldc.comment_id FROM lesson_discussion_comment ldc
                INNER JOIN lesson_discussion ld ON ld.lesson_discussion_id = ldc.discussion_id_fk
                INNER JOIN classroom_lesson cl ON cl.lesson_id = ld.lesson_id_fk
                INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk WHERE cs.class_id_fk = ?
            )
            UNION ALL
            SELECT report_id, content_type, content_id, content_author_id, reason, created_at, reporter_id_fk FROM discussion_report
            WHERE report_status = 'Pending' AND content_type = 'lesson_reply' AND content_id IN (
                SELECT r.reply_id FROM lesson_discussion_comment_reply r
                INNER JOIN lesson_discussion_comment ldc ON ldc.comment_id = r.comment_id_fk
                INNER JOIN lesson_discussion ld ON ld.lesson_discussion_id = ldc.discussion_id_fk
                INNER JOIN classroom_lesson cl ON cl.lesson_id = ld.lesson_id_fk
                INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk WHERE cs.class_id_fk = ?
            )
            ORDER BY created_at ASC
        ", [$classId, $classId, $classId, $classId, $classId, $classId])->getResultArray();

        $db = \Config\Database::connect();
        foreach ($rows as &$row) {
            $row['report_id']         = (int) $row['report_id'];
            $row['content_id']        = (int) $row['content_id'];
            $row['content_author_id'] = (int) $row['content_author_id'];

            $reporter = $db->table('users')->select('fname, lname, profile_photo')->where('user_id', $row['reporter_id_fk'])->get()->getRowArray();
            $row['reporter_name']  = $reporter ? trim($reporter['fname'] . ' ' . $reporter['lname']) : null;
            $row['reporter_photo'] = $reporter['profile_photo'] ?? null;

            $author = $db->table('users')->select('fname, lname')->where('user_id', $row['content_author_id'])->get()->getRowArray();
            $row['content_author_name'] = $author ? trim($author['fname'] . ' ' . $author['lname']) : null;
        }
        return $rows;
    }
}
