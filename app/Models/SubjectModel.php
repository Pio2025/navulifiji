<?php
namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subject';
    protected $primaryKey = 'subject_id';
    protected $allowedFields = ['subject_id','level_id_fk','subject_name'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    /**
     * Get Subject by subject_id
     */
    public function getSubject($subject_id)
    {
        return $this->find($subject_id);
    }

    /**
     * Get all Subjects
     */
    public function getAllSubject()
    {
        return $this->orderBy('subject_id', 'ASC')
                   ->findAll();
    }

    /**
     * Add new Subject
     */
    public function addSubject($data)
    {
        return $this->insert($data);
    }

    /**
     * Update Subject
     */
    public function updateSubject($subject_id, $data)
    {
        return $this->update($subject_id, $data);
    }

    /**
     * Delete Subject
     */
    public function deleteSubject($subject_id)
    {
        return $this->delete($subject_id);
    }

    /**
     * Get all subjects for a given level id
     */
    public function getSubjectByLevel($id)
    {
        // Use the model's built-in query builder
        return $this->where('level_id_fk', $id)->findAll();
        /*return $this->select('sch_subject.*, subject.*')
                ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk','left')
                ->where('subject.level_id_fk', $id)
                ->findAll();*/
    }
}