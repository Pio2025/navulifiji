<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelLeaveModel extends Model
{
    protected $table      = 'hostel_leave';
    protected $primaryKey = 'leave_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'allocation_id_fk',
        'boarder_admission_id_fk',
        'sch_id_fk',
        'from_date',
        'to_date',
        'reason',
        'status',
        'decided_by',
        'remarks',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `hostel_leave` (
            `leave_id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `allocation_id_fk`         INT UNSIGNED NOT NULL,
            `boarder_admission_id_fk`  INT UNSIGNED NOT NULL,
            `sch_id_fk`                INT UNSIGNED NOT NULL,
            `from_date`                DATE NOT NULL,
            `to_date`                  DATE NOT NULL,
            `reason`                   VARCHAR(255) DEFAULT NULL,
            `status`                   ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
            `decided_by`               INT UNSIGNED DEFAULT NULL,
            `remarks`                  VARCHAR(255) DEFAULT NULL,
            `created_at`               DATETIME DEFAULT NULL,
            `updated_at`               DATETIME DEFAULT NULL,
            PRIMARY KEY (`leave_id`),
            KEY `allocation_id_fk` (`allocation_id_fk`),
            KEY `boarder_admission_id_fk` (`boarder_admission_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByAllocation(int $allocationId): array
    {
        return $this->where('allocation_id_fk', $allocationId)
            ->orderBy('from_date', 'DESC')
            ->findAll();
    }

    public function getByBorrowers(array $admissionIds): array
    {
        if (empty($admissionIds)) {
            return [];
        }
        return $this->whereIn('boarder_admission_id_fk', $admissionIds)
            ->orderBy('from_date', 'DESC')
            ->findAll();
    }
}
