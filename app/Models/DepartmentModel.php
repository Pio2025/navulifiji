<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'dept_id';
    protected $allowedFields = ['dept_name'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    /**
     * Get Department by dept_id
     */
    public function getDepartment($dept_id)
    {
        return $this->find($dept_id);
    }


    /**
     * Get all Departments
     */
    public function getAllDepartment()
    {
        return $this->orderBy('dept_id', 'ASC')
                   ->findAll();
    }

    /**
     * Add new Department
     */
    public function addDepartment($data)
    {
        return $this->insert($data);
    }

    /**
     * Update Department
     */
    public function updateDepartment($dept_id, $data)
    {
        return $this->update($dept_id, $data);
    }

    /**
     * Delete Department
     */
    public function deleteDepartment($dept_id)
    {
        return $this->delete($dept_id);
    }

}