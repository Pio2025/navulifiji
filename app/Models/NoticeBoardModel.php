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

    /** Mark all expired records as Expired (can be called on a cron or each page load). */
    public function expireOld(): void
    {
        $this->db->query(
            "DELETE FROM notice_board WHERE expires_at IS NOT NULL AND expires_at <= NOW()"
        );
    }
}
