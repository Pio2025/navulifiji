<?php
namespace App\Models;
use CodeIgniter\Model;

class ParentStudentModel extends Model
{
    protected $table      = 'parent_student';
    protected $primaryKey = 'parent_student_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'parent_user_id_fk',
        'student_user_id_fk',
        'relationship',
        'created_by',
        'created_at',
    ];

    public function getChildrenOf(int $parentId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT ps.parent_student_id, ps.relationship,
                    u.user_id, u.fname, u.lname, u.profile_photo
             FROM parent_student ps
             INNER JOIN users u ON u.user_id = ps.student_user_id_fk
             WHERE ps.parent_user_id_fk = ?
             ORDER BY u.fname, u.lname",
            [$parentId]
        )->getResultArray();
    }

    public function getParentsOf(int $studentId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT ps.parent_student_id, ps.relationship,
                    u.user_id, u.fname, u.lname, u.profile_photo
             FROM parent_student ps
             INNER JOIN users u ON u.user_id = ps.parent_user_id_fk
             WHERE ps.student_user_id_fk = ?
             ORDER BY u.fname, u.lname",
            [$studentId]
        )->getResultArray();
    }
}
