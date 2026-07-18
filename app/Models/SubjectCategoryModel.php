<?php
namespace App\Models;

use CodeIgniter\Model;

class SubjectCategoryModel extends Model
{
    protected $table         = 'subject_category';
    protected $primaryKey    = 'sub_cat_id';
    protected $allowedFields = ['sub_cat_name', 'created_date', 'updated_date', 'sub_cat_status'];
    protected $useTimestamps = false;
    protected $returnType    = 'array';

    public function getAllActive(): array
    {
        return $this->where('sub_cat_status', 1)
            ->orderBy('sub_cat_name', 'ASC')
            ->findAll();
    }
}
