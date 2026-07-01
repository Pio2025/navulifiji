<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductTypeModel extends Model
{
    protected $table      = 'conduct_types';
    protected $primaryKey = 'type_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'type_name',
        'category',
        'is_positive',
        'default_points',
        'severity_level',
    ];

    public function getAll(): array
    {
        return $this->orderBy('category', 'ASC')->orderBy('type_name', 'ASC')->findAll();
    }

    public function getGroupedByCategory(): array
    {
        $types   = $this->getAll();
        $grouped = [];

        foreach ($types as $type) {
            $grouped[$type['category']][] = $type;
        }

        return $grouped;
    }
}
