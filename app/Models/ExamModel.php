<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table      = 'exam';
    protected $primaryKey = 'exam_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'exam_name',
        'level_id_fk',
        'exam_status',
    ];

    public function getAllWithLevel(): array
    {
        return $this->select('exam.*, level.level_name')
                    ->join('level', 'level.level_id = exam.level_id_fk', 'left')
                    ->orderBy('exam.exam_id', 'DESC')
                    ->findAll();
    }

    public function getDetail(int $examId): ?array
    {
        return $this->select('exam.*, level.level_name')
                    ->join('level', 'level.level_id = exam.level_id_fk', 'left')
                    ->where('exam.exam_id', $examId)
                    ->first();
    }

    public function getByLevel(int $levelId): array
    {
        return $this->select('exam.*, level.level_name')
                    ->join('level', 'level.level_id = exam.level_id_fk', 'left')
                    ->where('exam.level_id_fk', $levelId)
                    ->where('exam.exam_status', 'Active')
                    ->orderBy('exam.exam_name', 'ASC')
                    ->findAll();
    }
}
