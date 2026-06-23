<?php
namespace App\Models;

use CodeIgniter\Model;

class SchoolSubjectModel extends Model
{
    protected $table = 'sch_subject';
    protected $primaryKey = 'sch_subject_id';
    protected $allowedFields = ['sch_sub_id','sch_id_fk','subject_id_fk','sch_dept_id_fk','sch_sub_status'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Get School subject by sch_subject_id
     */
    public function getSchoolSubject($sch_subject_id)
    {
        return $this->find($sch_subject_id);
    }
    
    public function getSchoolSubjectBySubject($sch_subject_id)
    {
        return $this->find($sch_subject_id);
    }

      

    /**
     * Get all School subject
     */
    public function getAllSchoolsubject()
    {
        //return $this->orderBy('sch_subject_id', 'ASC')->findAll();
         return $this->select('sch_subject.*, subject.*')
               ->join('subject', 'subject.subject_id = sch_subject.sub_id_fk')
               ->orderBy('sch_subject.sch_sub_id', 'ASC')
               ->findAll();
    }
    
    /**
     * Get Full School subject Details with Joins
     * Joins with subject
     */
    public function findFullSchoolsubject($sch_id = null)
    {
        // Select specific fields including user info
        $this->select('
            sch_subject.*,
            subject.subject_name
        ');
        
        // Join with subject table
        $this->join('subject', 'subject.subject_id = sch_subject.sub_id_fk', 'left');
        
        // If specific school ID is provided
        if ($sch_id !== null) {
            $this->where('sch_subject.sch_id_fk', $sch_id);
        }
        
        // Order by department name
        $this->orderBy('subject.subject_id', 'ASC');
        
        return $this->findAll();
    }
    
    public function getAllStreamsBySchool($schID)
    {
        return $this->select('sch_subject.*, subject.*')
           ->join('sch_subject', 'sch_subject.sch_level_id = stream.sch_level_id_fk')
           ->join('level', 'level.level_id = sch_level.level_id_fk')
           ->join('school', 'school.sch_id = sch_level.sch_id_fk')
           ->where('sch_level.sch_id_fk', $schID)
           //->orderBy('level.level_name', 'ASC')
           ->orderBy('stream.stream_id', 'ASC')
           ->findAll();
    }
    
    public function findSchoolSubjectByLevel ($id)
    {
        // Select specific fields including user info
        $this->select('
            sch_subject.*,
            subject.*
        ');
        
        // Join with subject table
        $this->join('subject', 'subject.subject_id = sch_subject.subject_id_fk', 'left');
        
        // If specific school ID is provided
        if ($level_id !== null) {
            $this->where('sch_subject.sch_id_fk', $id);
        }
        
        // Order by department name
        $this->orderBy('subject.subject_id', 'ASC');
        
        return $this->findAll();
    }
    
    public function getSubjectByLevel($id)
    {
        return $this->select('sch_subject.*, subject.*')
                ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk','left')
                ->where('subject.level_id_fk', $id)
                ->findAll();
    }


    /**
     * Add new School subject
     */
    public function addSchoolSubject($data)
    {
        return $this->insert($data);
    }

    /**
     * Update School subject
     */
    public function updateSchoolSubject($sch_subject_id, $data)
    {
        return $this->update($sch_subject_id, $data);
    }

    /**
     * Delete School subject
     */
    public function deleteSchoolSubject($sch_subject_id)
    {
        return $this->delete($sch_subject_id);
    }

    
}