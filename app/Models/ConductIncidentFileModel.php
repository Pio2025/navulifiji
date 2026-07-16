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

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `conduct_incident_file` (
            `conduct_file_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `incident_id_fk`  INT UNSIGNED DEFAULT NULL,
            `file_src`        VARCHAR(260) DEFAULT NULL,
            `file_type`       VARCHAR(100) DEFAULT NULL,
            PRIMARY KEY (`conduct_file_id`),
            KEY `incident_id_fk` (`incident_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByIncident(int $incidentId): array
    {
        return $this->where('incident_id_fk', $incidentId)->findAll();
    }
}
