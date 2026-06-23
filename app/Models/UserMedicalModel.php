<?php
namespace App\Models;
use CodeIgniter\Model;

class UserMedicalModel extends Model
{
    protected $table      = 'user_medical';
    protected $primaryKey = 'medical_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id_fk',
        'blood_type',
        'medical_condition',
        'allergies',
        'medications',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'doctor_name',
        'doctor_phone',
        'doctor_address',
        'notes',
        'medical_date',
        'medical_time',
        'medical_status',
    ];

    public function getByUser(int $userId): array
    {
        return $this->where('user_id_fk', $userId)
                    ->orderBy('medical_date', 'DESC')
                    ->findAll();
    }

    public function getWithFiles(int $medicalId): ?array
    {
        $record = $this->find($medicalId);
        if (!$record) return null;

        $db = \Config\Database::connect();
        $record['files'] = $db->table('user_medical_files')
            ->where('medical_id_fk', $medicalId)
            ->get()->getResultArray();

        return $record;
    }
}