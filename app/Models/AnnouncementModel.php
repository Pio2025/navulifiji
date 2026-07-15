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

    public function getActiveForSchool(int $schId): array
    {
        $now = date('Y-m-d H:i:s');
        return $this->db->table('school_announcement sa')
            ->select('sa.*, u.fname, u.lname, u.profile_photo')
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

    public function expireOld(): void
    {
        $this->db->query(
            "UPDATE school_announcement SET announcement_status = 'Expired'
             WHERE announcement_status = 'Active' AND expires_at IS NOT NULL AND expires_at <= NOW()"
        );
    }
}
