<?php
namespace App\Models;

use CodeIgniter\Model;

class SchoolCategoryModel extends Model
{
    protected $table = 'sch_category';
    protected $primaryKey = 'sch_cat_id';
    protected $allowedFields = ['sch_cat_id', 'sch_cat_initial', 'sch_cat_name'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Get School by sch_cat_id
     */
    public function getSchoolCategory($sch_cat_id)
    {
        return $this->find($sch_cat_id);
    }

    /**
     * Get all Schools
     */
    public function getAllSchoolCategory()
    {
        return $this->orderBy('sch_cat_id', 'ASC')
                   ->findAll();
    }


    /**
     * Add new School
     */
    public function addSchoolCategory($data)
    {
        return $this->insert($data);
    }

    /**
     * Update School
     */
    public function updateSchoolCategory($sch_cat_id, $data)
    {
        return $this->update($sch_cat_id, $data);
    }

    /**
     * Delete School
     */
    public function deleteSchoolCategory($sch_cat_id)
    {
        return $this->delete($sch_cat_id);
    }

    
}