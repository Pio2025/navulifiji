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
