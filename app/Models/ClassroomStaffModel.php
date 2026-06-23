<?php
namespace App\Models;
use CodeIgniter\Model;

class ClassroomStaffModel extends Model
{
    protected $table      = 'classroom_role';
    protected $primaryKey = 'cs_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'class_id_fk',
        'user_id_fk',
        'cs_role',
        'cs_status',
        'cs_assigned_at',
        'cs_assigned_by',
    ];

    public function getByClassroom(int $classId): array
    {
        $db = \Config\Database::connect();
        return $db->table('classroom_role')
            ->select('classroom_role.*, users.fname, users.lname, users.oname, users.profile_photo')
            ->join('users', 'users.user_id = classroom_role.user_id_fk', 'left')
            ->where('classroom_role.class_id_fk', $classId)
            ->orderBy('classroom_role.cs_role', 'ASC')
            ->orderBy('classroom_role.cs_assigned_at', 'DESC')
            ->get()->getResultArray();
    }

    public function getActiveByRole(int $classId, string $role): ?array
    {
        $db = \Config\Database::connect();
        $row = $db->table('classroom_role')
            ->select('classroom_role.*, users.fname, users.lname, users.oname, users.profile_photo')
            ->join('users', 'users.user_id = classroom_role.user_id_fk', 'left')
            ->where('classroom_role.class_id_fk', $classId)
            ->where('classroom_role.cs_role', $role)
            ->where('classroom_role.cs_status', 'Active')
            ->orderBy('classroom_role.cs_assigned_at', 'DESC')
            ->limit(1)
            ->get()->getRowArray();
        return $row ?: null;
    }

    public function deactivateByRole(int $classId, string $role): void
    {
        $db = \Config\Database::connect();
        $db->table('classroom_role')
           ->where('class_id_fk', $classId)
           ->where('cs_role', $role)
           ->where('cs_status', 'Active')
           ->update(['cs_status' => 'Inactive']);
    }
}
