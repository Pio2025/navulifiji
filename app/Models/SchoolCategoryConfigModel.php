<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolCategoryConfigModel extends Model
{
    protected $table         = 'school_category_config';
    protected $primaryKey    = 'sch_cat_con_id';
    protected $allowedFields = [
        'sch_cat_id_fk',
        'label_for_term',
    ];
    protected $useTimestamps = false;
    protected $returnType    = 'array';

    public function getByCategoryId(int $catId): ?array
    {
        return $this->where('sch_cat_id_fk', $catId)->first();
    }
}
