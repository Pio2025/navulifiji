<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolDepartmentModel extends Model
{
    protected $table = 'sch_department';
    protected $primaryKey = 'sch_dept_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['sch_id_fk', 'dept_id_fk', 'dept_head', 'dept_status'];

    protected $useTimestamps = false;
    
    /**
     * Get School by dept_id
     */
    public function getSchoolDepartment($id)
    {
        return $this->find($id);
    }

    /**
     * Get all Schools
     */
    public function getAllSchoolDepartment()
    {
        return $this->orderBy('sch_dept_id', 'ASC')
                   ->findAll();
    }
    
    public function getAllSchoolDepartmentBySchool($schID)
    {
        return $this->select('sch_department.*, department.*')
           ->join('department', 'department.dept_id = sch_department.dept_id_fk')
           ->where('sch_department.sch_id_fk', $schID)
           //->orderBy('level.level_name', 'ASC')
           ->orderBy('department.dept_id', 'ASC')
           ->findAll();
    }
    
    /**
     * Get Full School Department Details with Joins
     * Joins with department
     */
    public function findFullSchoolDepartment($sch_id = null)
    {
        // Select specific fields including user info
        $this->select('
            sch_department.*,
            department.dept_name,
            department.dept_code,
            department.dept_theme,
            department.dept_desc,
            users.user_id,
            users.fname,
            users.lname,
            users.email
        ');
        
        // Join with department table
        $this->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left');
        
        // Join with user table for department head
        $this->join('users', 'users.user_id = sch_department.dept_head', 'left');
        
        // If specific school ID is provided
        if ($sch_id !== null) {
            $this->where('sch_department.sch_id_fk', $sch_id);
        }
        
        // Order by department name
        $this->orderBy('department.dept_id', 'ASC');
        
        return $this->findAll();
    }
    
    // OR create a separate method for getting count:
    public function countSchoolDepartments($sch_id)
    {
        return $this->where('sch_id_fk', $sch_id)->countAllResults();
    }
    
    /**
     * Add new School
     */
    public function addSchoolDepartment($data)
    {
        return $this->insert($data);
    }
}
