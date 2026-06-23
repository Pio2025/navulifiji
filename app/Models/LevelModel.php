<?php
namespace App\Models;

use CodeIgniter\Model;

class LevelModel extends Model
{
    protected $table = 'level';
    protected $primaryKey = 'level_id';
    protected $allowedFields = ['level_id','sch_cat_id_fk','level_name'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    
    
}