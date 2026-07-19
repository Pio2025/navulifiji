<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table      = 'school_announcement';
    protected $primaryKey = 'announcement_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'sch_id_fk', 'posted_by', 'title', 'content', 'priority',
        'attachment', 'attachment_type', 'attachment_name',
        'expires_at', 'announcement_status', 'created_at', 'updated_at',
    ];

    // ── create + broadcast ───────────────────────────────────────────────────

    public function insert($data = null, bool $returnID = true)
    {
        $result = parent::insert($data, $returnID);

        $row    = is_array($data) ? $data : (array) $data;
        $schId  = (int) ($row['sch_id_fk'] ?? 0);
        $itemId = $returnID ? (int) $result : (int) $this->getInsertID();

        \App\Libraries\RealtimeNotifier::notify(
            \App\Libraries\RealtimeNotifier::recipientsForSchool($schId, 'All'),
            'announcement',
            ['action' => 'new', 'itemId' => $itemId]
        );

        return $result;
    }

    public function getActiveForSchool(int $schId): array
    {
        $now = date('Y-m-d H:i:s');
        return $this->db->table('school_announcement sa')
            ->select("sa.*, u.fname, u.lname, u.profile_photo,
                (SELECT rc.role_cat_name
                 FROM user_role ur
                 INNER JOIN role ro ON ro.role_id = ur.role_id_fk
                 INNER JOIN role_category rc ON rc.role_cat_id = ro.role_cat_id_fk
                 WHERE ur.user_id_fk = sa.posted_by AND ur.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_name")
            ->join('users u', 'u.user_id = sa.posted_by', 'left')
            ->where('sa.sch_id_fk', $schId)
            ->where('sa.announcement_status', 'Active')
            ->where('(sa.expires_at IS NULL OR sa.expires_at > "' . $now . '")')
            ->orderBy('sa.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function getAllForSchool(int $schId): array
    {
        return $this->db->table('school_announcement sa')
            ->select('sa.*, u.fname, u.lname, u.profile_photo')
            ->join('users u', 'u.user_id = sa.posted_by', 'left')
            ->where('sa.sch_id_fk', $schId)
            ->orderBy('sa.created_at', 'DESC')
            ->get()->getResultArray();
    }

    /**
     * Mark all active announcements for a school as read by this user.
     * Called when the user visits the announcements page.
     */
    public function markAllReadForUser(int $userId, int $schId): void
    {
        if ($userId <= 0 || $schId <= 0) return;
        $now = date('Y-m-d H:i:s');
        try {
            $this->db->query("
                INSERT IGNORE INTO announcement_reads (user_id, announcement_id, read_at)
                SELECT ?, announcement_id, NOW()
                FROM school_announcement
                WHERE sch_id_fk = ?
                  AND announcement_status = 'Active'
                  AND (expires_at IS NULL OR expires_at > ?)
            ", [$userId, $schId, $now]);
        } catch (\Throwable $e) {
            // announcement_reads table may not exist yet — migrate to enable read tracking
        }
    }

    /**
     * Count active announcements not yet read by this user for a given school.
     */
    public function getUnreadCountForUser(int $userId, int $schId): int
    {
        if ($userId <= 0 || $schId <= 0) return 0;
        $now = date('Y-m-d H:i:s');
        try {
            $row = $this->db->query("
                SELECT COUNT(*) AS cnt
                FROM school_announcement sa
                LEFT JOIN announcement_reads ar
                    ON ar.announcement_id = sa.announcement_id AND ar.user_id = ?
                WHERE sa.sch_id_fk = ?
                  AND sa.announcement_status = 'Active'
                  AND (sa.expires_at IS NULL OR sa.expires_at > ?)
                  AND ar.ar_id IS NULL
            ", [$userId, $schId, $now])->getRowArray();
            return (int) ($row['cnt'] ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public function expireOld(): void
    {
        $expired = $this->db->table('school_announcement')
            ->select('announcement_id, attachment')
            ->where('announcement_status', 'Active')
            ->where('expires_at IS NOT NULL')
            ->where('expires_at <=', date('Y-m-d H:i:s'))
            ->get()->getResultArray();

        if (empty($expired)) return;

        foreach ($expired as $row) {
            if (!empty($row['attachment'])) {
                $path = FCPATH . 'uploads/announcements/' . $row['attachment'];
                if (file_exists($path)) unlink($path);
            }
        }

        $ids = array_column($expired, 'announcement_id');
        $this->db->table('school_announcement')->whereIn('announcement_id', $ids)->delete();
    }
}
