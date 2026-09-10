<?php
namespace App\Models;
use CodeIgniter\Model;

class TransportHouseholdMemberModel extends Model
{
    protected $table      = 'transport_allocation_household_member';
    protected $primaryKey = 'household_member_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'allocation_id_fk',
        'member_name',
        'dob',
        'phone',
        'relationship',
        'occupation',
        'annual_income',
        'tin_number',
        'evidence_attached',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `transport_allocation_household_member` (
            `household_member_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `allocation_id_fk`     INT UNSIGNED NOT NULL,
            `member_name`          VARCHAR(150) NOT NULL,
            `dob`                  DATE         DEFAULT NULL,
            `phone`                VARCHAR(30)  DEFAULT NULL,
            `relationship`         VARCHAR(50)  DEFAULT NULL,
            `occupation`           VARCHAR(150) DEFAULT NULL,
            `annual_income`        DECIMAL(10,2) DEFAULT NULL,
            `tin_number`           VARCHAR(50)  DEFAULT NULL,
            `evidence_attached`    TINYINT(1)   NOT NULL DEFAULT 0,
            PRIMARY KEY (`household_member_id`),
            KEY `allocation_id_fk` (`allocation_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByAllocation(int $allocationId): array
    {
        return $this->where('allocation_id_fk', $allocationId)
            ->orderBy('household_member_id', 'ASC')
            ->findAll();
    }

    public function replaceForAllocation(int $allocationId, array $members): void
    {
        $this->where('allocation_id_fk', $allocationId)->delete();

        foreach ($members as $member) {
            if (empty($member['member_name'])) {
                continue;
            }
            $member['allocation_id_fk'] = $allocationId;
            $this->insert($member);
        }
    }
}
