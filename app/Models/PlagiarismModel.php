<?php

namespace App\Models;

use CodeIgniter\Model;

class PlagiarismModel extends Model
{
    protected $table      = 'assignment_plagiarism';
    protected $primaryKey = 'plagiarism_id';

    public function getBySubmissionId(int $submissionId): ?array
    {
        return $this->db->table($this->table)
            ->where('submission_id_fk', $submissionId)
            ->orderBy('plagiarism_id', 'DESC')
            ->limit(1)
            ->get()->getRowArray();
    }

    public function getByScanId(string $scanId): ?array
    {
        return $this->db->table($this->table)
            ->where('scan_id', $scanId)
            ->limit(1)
            ->get()->getRowArray();
    }

    public function createPending(int $submissionId, string $scanId): int
    {
        $this->db->table($this->table)->insert([
            'submission_id_fk' => $submissionId,
            'scan_id'          => $scanId,
            'status'           => 'pending',
            'created_at'       => date('Y-m-d H:i:s'),
        ]);
        return (int) $this->db->insertID();
    }

    public function updateByScanId(string $scanId, array $data): void
    {
        $this->db->table($this->table)->where('scan_id', $scanId)->update($data);
    }

    public function deleteBySubmissionId(int $submissionId): void
    {
        $this->db->table($this->table)->where('submission_id_fk', $submissionId)->delete();
    }
}
