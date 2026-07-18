<?php
namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table         = 'subject';
    protected $primaryKey    = 'subject_id';
    protected $allowedFields = ['sub_cat_id_fk', 'level_id_fk', 'subject_name', 'sub_image', 'is_examinable'];
    protected $useTimestamps = false;
    protected $returnType    = 'array';

    public function getSubject($subject_id)
    {
        return $this->find($subject_id);
    }

    public function getAllSubject()
    {
        return $this->orderBy('subject_id', 'ASC')->findAll();
    }

    public function getAllWithLevel(): array
    {
        return $this->db->table('subject s')
            ->select('s.subject_id, s.subject_name, s.sub_image, s.is_examinable, s.sub_cat_id_fk, l.level_id, l.level_name, sc.sub_cat_name')
            ->join('level l', 'l.level_id = s.level_id_fk', 'left')
            ->join('subject_category sc', 'sc.sub_cat_id = s.sub_cat_id_fk', 'left')
            ->orderBy('l.level_id', 'ASC')
            ->orderBy('s.subject_name', 'ASC')
            ->get()->getResultArray();
    }

    public function addSubject($data)
    {
        return $this->insert($data);
    }

    public function updateSubject($subject_id, $data)
    {
        return $this->update($subject_id, $data);
    }

    public function deleteSubject($subject_id)
    {
        return $this->delete($subject_id);
    }

    public function getSubjectByLevel($id)
    {
        return $this->where('level_id_fk', $id)->findAll();
    }
}
