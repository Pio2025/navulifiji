<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetableTemplateModel extends Model
{
    protected $table      = 'timetable_template';
    protected $primaryKey = 'template_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'sch_cat_id_fk', 'sch_id_fk', 'template_name', 'is_default', 'created_at', 'updated_at',
    ];

    public function ensureTables(): void
    {
        try {
            $this->db->query('SELECT 1 FROM timetable_template LIMIT 1');
        } catch (\Throwable $e) {
            // tables not yet migrated
        }
    }

    public function getAll(): array
    {
        return $this->select('timetable_template.*, sch_category.sch_cat_name')
            ->join('sch_category', 'sch_category.sch_cat_id = timetable_template.sch_cat_id_fk', 'left')
            ->orderBy('template_id', 'ASC')
            ->findAll();
    }

    /**
     * Resolve the best default template for a school category + optional school override.
     * Priority: school-specific → category-wide → universal (sch_cat_id_fk = 0).
     */
    public function getDefaultForCategory(int $schCatId, int $schId = 0): ?array
    {
        if ($schId > 0) {
            $row = $this->where('sch_id_fk', $schId)->where('sch_cat_id_fk', $schCatId)->where('is_default', 1)->first();
            if ($row) return $row;
        }
        $row = $this->where('sch_id_fk', 0)->where('sch_cat_id_fk', $schCatId)->where('is_default', 1)->first();
        if ($row) return $row;
        return $this->where('sch_id_fk', 0)->where('sch_cat_id_fk', 0)->where('is_default', 1)->first();
    }
}
