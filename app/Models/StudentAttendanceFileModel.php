<?php
namespace App\Models;
use CodeIgniter\Model;

class StudentAttendanceFileModel extends Model
{
    protected $table      = 'student_attendance_file';
    protected $primaryKey = 'stud_att_file_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'stud_att_id_fk',
        'stud_att_file_src',
        'stud_att_file_type',
    ];

    public function addFile(array $data): int|false
    {
        if ($this->insert($data)) {
            return (int) $this->getInsertID();
        }
        return false;
    }

    public function getFilesByAttendance(int $attId): array
    {
        return $this->where('stud_att_id_fk', $attId)->findAll();
    }

    public function getFilesByAttendanceIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        return $this->whereIn('stud_att_id_fk', $ids)->findAll();
    }

    public function getFile(int $fileId): ?array
    {
        return $this->find($fileId);
    }

    public function deleteFile(int $fileId): ?array
    {
        $file = $this->find($fileId);
        if ($file) {
            $this->delete($fileId);
            return $file;
        }
        return null;
    }

    public function deleteAllForAttendanceIds(array $ids): void
    {
        if (empty($ids)) {
            return;
        }
        $this->whereIn('stud_att_id_fk', $ids)->delete();
    }
}
