<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductIncidentFileModel extends Model
{
    protected $table      = 'conduct_incident_file';
    protected $primaryKey = 'conduct_file_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'incident_id_fk',
        'file_src',
        'file_type',
    ];

    public function getByIncident(int $incidentId): array
    {
        return $this->where('incident_id_fk', $incidentId)->findAll();
    }
}
