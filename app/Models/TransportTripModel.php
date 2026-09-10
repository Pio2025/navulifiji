<?php
namespace App\Models;
use CodeIgniter\Model;

class TransportTripModel extends Model
{
    protected $table      = 'transport_allocation_trip';
    protected $primaryKey = 'trip_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'allocation_id_fk',
        'direction',
        'trip_order',
        'boarding_point',
        'fare',
        'mode',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `transport_allocation_trip` (
            `trip_id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `allocation_id_fk` INT UNSIGNED NOT NULL,
            `direction`        ENUM('To School','To Home') NOT NULL,
            `trip_order`       TINYINT UNSIGNED NOT NULL DEFAULT 1,
            `boarding_point`   VARCHAR(150) DEFAULT NULL,
            `fare`             DECIMAL(8,2) DEFAULT NULL,
            `mode`             ENUM('Bus','Boat','Carrier','Minibus') DEFAULT NULL,
            PRIMARY KEY (`trip_id`),
            KEY `allocation_id_fk` (`allocation_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByAllocation(int $allocationId): array
    {
        return $this->where('allocation_id_fk', $allocationId)
            ->orderBy('direction', 'ASC')
            ->orderBy('trip_order', 'ASC')
            ->findAll();
    }

    public function replaceForAllocation(int $allocationId, array $trips): void
    {
        $this->where('allocation_id_fk', $allocationId)->delete();

        foreach ($trips as $trip) {
            if (empty($trip['boarding_point'])) {
                continue;
            }
            $trip['allocation_id_fk'] = $allocationId;
            $this->insert($trip);
        }
    }
}
