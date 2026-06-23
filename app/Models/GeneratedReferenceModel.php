<?php
namespace App\Models;
use CodeIgniter\Model;

class GeneratedReferenceModel extends Model
{
    protected $table      = 'generated_reference';
    protected $primaryKey = 'gen_ref_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'ref_cat_id_fk',
        'user_id_fk',
        'gen_ref_by',
        'gen_ref_file_name',
        'gen_ref_date',
        'gen_ref_time',
        'gen_ref_status',
    ];

    public function getCurrentReference($userId, $refCatId)
    {
        return $this->where('user_id_fk', $userId)
                    ->where('ref_cat_id_fk', $refCatId)
                    ->where('gen_ref_status', 'Current')
                    ->first();
    }
    
    public function getModernReference(int $userId, int $refCatId): ?array
    {
        return $this->where('user_id_fk', $userId)
                    ->where('ref_cat_id_fk', $refCatId)
                    ->where('gen_ref_status', 'Current') // ← was 'Modern'
                    ->first();
    }

    public function markOutdated($userId, $refCatId)
    {
        return $this->where('user_id_fk', $userId)
                    ->where('ref_cat_id_fk', $refCatId)
                    ->where('gen_ref_status', 'Current')
                    ->set(['gen_ref_status' => 'Outdated'])
                    ->update();
    }

    public function getUserReferences($userId)
    {
        return $this->select('generated_reference.*, reference_category.ref_cat_name, u1.fname as student_fname, u1.lname as student_lname, u2.fname as gen_by_fname, u2.lname as gen_by_lname')
                    ->join('reference_category', 'reference_category.ref_cat_id = generated_reference.ref_cat_id_fk')
                    ->join('users as u1', 'u1.user_id = generated_reference.user_id_fk')
                    ->join('users as u2', 'u2.user_id = generated_reference.gen_ref_by')
                    ->where('generated_reference.user_id_fk', $userId)
                    ->orderBy('generated_reference.gen_ref_date', 'DESC')
                    ->findAll();
    }
}