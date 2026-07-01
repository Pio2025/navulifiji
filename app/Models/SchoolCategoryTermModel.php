<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolCategoryTermModel extends Model
{
    protected $table         = 'sch_cat_term_entry';
    protected $primaryKey    = 'sch_cat_term_id';
    protected $allowedFields = [
        'sch_cat_con_id_fk',
        'term_num',
        'num_of_week',
        'term_start_day',
        'term_start_month',
        'term_end_day',
        'term_end_month',
    ];
    protected $useTimestamps = false;
    protected $returnType    = 'array';

    public function getByConfigId(int $configId): array
    {
        return $this->where('sch_cat_con_id_fk', $configId)
                    ->orderBy('term_num', 'ASC')
                    ->findAll();
    }
}
