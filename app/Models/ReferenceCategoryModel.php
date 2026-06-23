<?php
namespace App\Models;
use CodeIgniter\Model;

class ReferenceCategoryModel extends Model
{
    protected $table      = 'reference_category';
    protected $primaryKey = 'ref_cat_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['ref_cat_name'];
}