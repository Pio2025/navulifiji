<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferenceRequestModel extends Model
{
    protected $table         = 'reference_requests';
    protected $primaryKey    = 'request_id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id_fk', 'ref_cat_id', 'ref_type_name',
        'request_note', 'request_status',
        'reviewed_by', 'review_note',
    ];

    public function getByUser(int $userId): array
    {
        $db = \Config\Database::connect();
        return $db->table('reference_requests rr')
            ->select('rr.*, u.fname, u.lname, u.oname, rev.fname AS rev_fname, rev.lname AS rev_lname')
            ->join('users u',   'u.user_id = rr.user_id_fk', 'left')
            ->join('users rev', 'rev.user_id = rr.reviewed_by', 'left')
            ->where('rr.user_id_fk', $userId)
            ->orderBy('rr.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function getAll(int $schId = 0): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('reference_requests rr')
            ->select('rr.*, u.fname, u.lname, u.oname, u.profile_photo, rev.fname AS rev_fname, rev.lname AS rev_lname, a.sch_id_fk')
            ->join('users u',      'u.user_id = rr.user_id_fk', 'left')
            ->join('admission a',  'a.user_id_fk = rr.user_id_fk AND a.admission_status = \'Active\'', 'left')
            ->join('users rev',    'rev.user_id = rr.reviewed_by', 'left')
            ->orderBy('rr.created_at', 'DESC');

        if ($schId > 0) {
            $builder->where('a.sch_id_fk', $schId);
        }

        return $builder->get()->getResultArray();
    }

    public function updateStatus(int $requestId, string $status, int $reviewedBy, string $reviewNote = ''): void
    {
        $this->update($requestId, [
            'request_status' => $status,
            'reviewed_by'    => $reviewedBy,
            'review_note'    => $reviewNote,
        ]);
    }
}
