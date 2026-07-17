<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetableTemplateSlotModel extends Model
{
    protected $table      = 'timetable_template_slot';
    protected $primaryKey = 'slot_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'template_id_fk', 'slot_order', 'slot_type', 'label', 'start_time', 'end_time', 'is_teaching',
    ];

    public function getByTemplate(int $templateId): array
    {
        return $this->where('template_id_fk', $templateId)
            ->orderBy('slot_order', 'ASC')
            ->findAll();
    }
}
