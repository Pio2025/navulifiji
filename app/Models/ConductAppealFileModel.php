<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductAppealFileModel extends Model
{
    protected $table      = 'conduct_appeal_files';
    protected $primaryKey = 'appeal_file_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'appeal_id',
        'file_src',
        'file_type',
    ];

    public function getByAppeal(int $appealId): array
    {
        return $this->where('appeal_id', $appealId)->findAll();
    }
}
