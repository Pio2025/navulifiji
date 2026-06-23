<?php
namespace App\Models;

use CodeIgniter\Model;

class SchoolLevelModel extends Model
{
    protected $table = 'sch_level';
    protected $primaryKey = 'sch_level_id';
    protected $allowedFields = ['sch_level_id','sch_id_fk','level_id_fk'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Get School Level by sch_level_id
     */
    public function getSchoolLevel($sch_level_id)
    {
        return $this->find($sch_level_id);
    }

    /**
     * Get School Level by sch_id_fk
     */
    public function getSchoolLevelBySchID($sch_id_fk)
    {
        return $this->where('sch_id_fk', $sch_id_fk)
                    ->findAll();
    }   

    /**
     * Get all School Level
     */
    public function getAllSchoolLevel()
    {
        //return $this->orderBy('sch_level_id', 'ASC')->findAll();
         return $this->select('sch_level.*, level.*')
               ->join('level', 'level.level_id = sch_level.level_id_fk')
               ->orderBy('sch_level.sch_level_id', 'ASC')
               ->findAll();
    }
    
    /**
     * Get Full School Level Details with Joins
     * Joins with level
     */
    public function findFullSchoolLevel($sch_id = null)
    {
        // Select specific fields including user info
        $this->select('
            sch_level.*,
            level.*
        ');
        
        // Join with level table
        $this->join('level', 'level.level_id = sch_level.level_id_fk', 'left');
        
        // If specific school ID is provided
        if ($sch_id !== null) {
            $this->where('sch_level.sch_id_fk', $sch_id);
        }
        
        // Order by department name
        $this->orderBy('level.level_id', 'ASC');
        
        return $this->findAll();
    }


    /**
     * Add new School Level
     */
    public function addSchoolLevel($data)
    {
        return $this->insert($data);
    }

    /**
     * Update School Level
     */
    public function updateSchoolLevel($sch_level_id, $data)
    {
        return $this->update($sch_level_id, $data);
    }

    /**
     * Delete School Level
     */
    public function deleteSchoolLevel($sch_level_id)
    {
        return $this->delete($sch_level_id);
    }

    
}