<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelVisitorLogModel extends Model
{
    protected $table      = 'hostel_visitor_log';
    protected $primaryKey = 'visitor_log_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'allocation_id_fk',
        'boarder_admission_id_fk',
        'sch_id_fk',
        'visitor_name',
        'relationship',
        'visit_date',
        'time_in',
        'time_out',
        'purpose',
        'recorded_by',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `hostel_visitor_log` (
            `visitor_log_id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `allocation_id_fk`         INT UNSIGNED NOT NULL,
            `boarder_admission_id_fk`  INT UNSIGNED NOT NULL,
            `sch_id_fk`                INT UNSIGNED NOT NULL,
            `visitor_name`             VARCHAR(150) NOT NULL,
            `relationship`             VARCHAR(100) DEFAULT NULL,
            `visit_date`               DATE NOT NULL,
            `time_in`                  TIME DEFAULT NULL,
            `time_out`                 TIME DEFAULT NULL,
            `purpose`                  VARCHAR(255) DEFAULT NULL,
            `recorded_by`              INT UNSIGNED DEFAULT NULL,
            `created_at`               DATETIME DEFAULT NULL,
            `updated_at`               DATETIME DEFAULT NULL,
            PRIMARY KEY (`visitor_log_id`),
            KEY `allocation_id_fk` (`allocation_id_fk`),
            KEY `boarder_admission_id_fk` (`boarder_admission_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByAllocation(int $allocationId): array
    {
        return $this->where('allocation_id_fk', $allocationId)
            ->orderBy('visit_date', 'DESC')
            ->orderBy('visitor_log_id', 'DESC')
            ->findAll();
    }

    public function getByBorrowers(array $admissionIds, int $limit = 100): array
    {
        if (empty($admissionIds)) {
            return [];
        }
        return $this->whereIn('boarder_admission_id_fk', $admissionIds)
            ->orderBy('visit_date', 'DESC')
            ->orderBy('visitor_log_id', 'DESC')
            ->findAll($limit);
    }
}
