<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticeBoardModel extends Model
{
    protected $table      = 'notice_board';
    protected $primaryKey = 'notice_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'sch_id_fk', 'posted_by', 'title', 'content',
        'priority', 'audience', 'is_pinned', 'expires_at',
        'notice_status', 'created_at', 'updated_at',
    ];

    /**
     * Active notices for a school, scoped by audience, newest pinned first.
     */
    public function getActiveForSchool(int $schId, string $audience = 'All'): array
    {
        $now = date('Y-m-d H:i:s');

        $builder = $this->db->table('notice_board nb')
            ->select('nb.*, u.fname, u.lname, u.profile_photo')
            ->join('users u', 'u.user_id = nb.posted_by', 'left')
            ->where('nb.sch_id_fk', $schId)
            ->where('nb.notice_status', 'Active')
            ->where('(nb.expires_at IS NULL OR nb.expires_at > "' . $now . '")')
            ->orderBy('nb.is_pinned', 'DESC')
            ->orderBy('nb.created_at', 'DESC');

        if ($audience !== 'All') {
            $builder->groupStart()
                ->where('nb.audience', 'All')
                ->orWhere('nb.audience', $audience)
            ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * All notices for a school (including expired), for admin/management view.
     */
    public function getAllForSchool(int $schId): array
    {
        return $this->db->table('notice_board nb')
            ->select('nb.*, u.fname, u.lname, u.profile_photo')
            ->join('users u', 'u.user_id = nb.posted_by', 'left')
            ->where('nb.sch_id_fk', $schId)
            ->orderBy('nb.is_pinned', 'DESC')
            ->orderBy('nb.created_at', 'DESC')
            ->get()->getResultArray();
    }

    /**
     * Notices posted by a specific user.
     */
    public function getByUser(int $userId, int $schId): array
    {
        return $this->db->table('notice_board nb')
            ->select('nb.*, u.fname, u.lname, u.profile_photo')
            ->join('users u', 'u.user_id = nb.posted_by', 'left')
            ->where('nb.posted_by', $userId)
            ->where('nb.sch_id_fk', $schId)
            ->orderBy('nb.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function findWithAuthor(int $noticeId): ?array
    {
        $row = $this->db->table('notice_board nb')
            ->select('nb.*, u.fname, u.lname, u.profile_photo')
            ->join('users u', 'u.user_id = nb.posted_by', 'left')
            ->where('nb.notice_id', $noticeId)
            ->get()->getRowArray();
        return $row ?: null;
    }

    /**
     * Mark all active notices visible to this user's audience as read.
     * Called when the user visits the notice board.
     */
    public function markAllReadForUser(int $userId, int $schId, string $audience = 'All'): void
    {
        if ($userId <= 0 || $schId <= 0) return;
        $now = date('Y-m-d H:i:s');

        $audienceClause = $audience === 'All'
            ? ''
            : "AND (nb.audience = 'All' OR nb.audience = " . $this->db->escape($audience) . ")";

        try {
            $this->db->query("
                INSERT IGNORE INTO notice_reads (user_id, notice_id, read_at)
                SELECT ?, nb.notice_id, NOW()
                FROM notice_board nb
                WHERE nb.sch_id_fk = ?
                  AND nb.notice_status = 'Active'
                  AND (nb.expires_at IS NULL OR nb.expires_at > ?)
                  {$audienceClause}
            ", [$userId, $schId, $now]);
        } catch (\Throwable $e) {
            // notice_reads table may not exist yet — migrate to enable read tracking
        }
    }

    /**
     * Count active notices not yet read by this user for a given school and audience.
     */
    public function getUnreadCountForUser(int $userId, int $schId, string $audience = 'All'): int
    {
        if ($userId <= 0 || $schId <= 0) return 0;
        $now = date('Y-m-d H:i:s');

        $audienceClause = $audience === 'All'
            ? ''
            : "AND (nb.audience = 'All' OR nb.audience = " . $this->db->escape($audience) . ")";

        try {
            $row = $this->db->query("
                SELECT COUNT(*) AS cnt
                FROM notice_board nb
                LEFT JOIN notice_reads nr
                    ON nr.notice_id = nb.notice_id AND nr.user_id = ?
                WHERE nb.sch_id_fk = ?
                  AND nb.notice_status = 'Active'
                  AND (nb.expires_at IS NULL OR nb.expires_at > ?)
                  {$audienceClause}
                  AND nr.nr_id IS NULL
            ", [$userId, $schId, $now])->getRowArray();
            return (int) ($row['cnt'] ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** Mark all expired records as Expired (can be called on a cron or each page load). */
    public function expireOld(): void
    {
        $this->db->query(
            "DELETE FROM notice_board WHERE expires_at IS NOT NULL AND expires_at <= NOW()"
        );
    }
}
