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

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `conduct_actions` (
            `action_id`      INT UNSIGNED   NOT NULL AUTO_INCREMENT,
            `incident_id`    INT UNSIGNED   DEFAULT NULL,
            `action_type`    VARCHAR(50)    DEFAULT NULL,
            `action_date`    DATE           DEFAULT NULL,
            `duration_hours` DECIMAL(5,2)   DEFAULT NULL,
            `is_completed`   TINYINT(1)     NOT NULL DEFAULT 0,
            `notes`          TEXT,
            PRIMARY KEY (`action_id`),
            KEY `incident_id` (`incident_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

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
