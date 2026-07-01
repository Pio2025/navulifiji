<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductActionModel extends Model
{
    protected $table      = 'conduct_actions';
    protected $primaryKey = 'action_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'incident_id',
        'action_type',
        'action_date',
        'duration_hours',
        'is_completed',
        'notes',
    ];

    public function getByIncident(int $incidentId): array
    {
        return $this->where('incident_id', $incidentId)
                    ->orderBy('action_date', 'DESC')
                    ->findAll();
    }

    public function markCompleted(int $actionId): bool
    {
        return $this->update($actionId, ['is_completed' => 1]);
    }
}
