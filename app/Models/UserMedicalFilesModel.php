<?php
namespace App\Models;
use CodeIgniter\Model;

class UserMedicalFilesModel extends Model
{
    protected $table      = 'user_medical_files';
    protected $primaryKey = 'file_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'medical_id_fk',
        'file_name',
        'file_original_name',
        'file_type',
        'file_size',
        'file_date',
        'file_time',
    ];

    public function getByMedical(int $medicalId): array
    {
        return $this->where('medical_id_fk', $medicalId)->findAll();
    }

    public function deleteWithFile(int $fileId): bool
    {
        $file = $this->find($fileId);
        if (!$file) return false;

        $path = FCPATH . 'uploads/medical/' . $file['file_name'];
        if (file_exists($path)) {
            unlink($path);
        }

        $this->delete($fileId);
        return true;
    }
}