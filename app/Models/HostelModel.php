<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelModel extends Model
{
    protected $table      = 'hostel';
    protected $primaryKey = 'hostel_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'hostel_name',
        'hostel_type',
        'address',
        'description',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `hostel` (
            `hostel_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`   INT UNSIGNED NOT NULL,
            `hostel_name` VARCHAR(150) NOT NULL,
            `hostel_type` ENUM('Boys','Girls','Mixed') NOT NULL DEFAULT 'Mixed',
            `address`     VARCHAR(255) DEFAULT NULL,
            `description` VARCHAR(255) DEFAULT NULL,
            `created_at`  DATETIME DEFAULT NULL,
            `updated_at`  DATETIME DEFAULT NULL,
            PRIMARY KEY (`hostel_id`),
            UNIQUE KEY `uniq_sch_hostel` (`sch_id_fk`, `hostel_name`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Hostels for a school (0 = all schools) with room-count / capacity /
     * occupied totals, for the catalog listing page. Correlated subqueries
     * (rather than joins) avoid row fan-out from the room/allocation join.
     */
    public function getBySchoolWithOccupancy(int $schId): array
    {
        $db  = \Config\Database::connect();
        $sql = "
            SELECT h.*,
                (SELECT COUNT(*) FROM hostel_room r WHERE r.hostel_id_fk = h.hostel_id) AS room_count,
                (SELECT COALESCE(SUM(r.capacity), 0) FROM hostel_room r WHERE r.hostel_id_fk = h.hostel_id) AS total_capacity,
                (SELECT COUNT(*) FROM hostel_allocation a
                    INNER JOIN hostel_room r2 ON r2.room_id = a.room_id_fk
                    WHERE r2.hostel_id_fk = h.hostel_id AND a.status = 'Active') AS occupied_count
            FROM hostel h
        ";
        $params = [];
        if ($schId > 0) {
            $sql      .= ' WHERE h.sch_id_fk = ?';
            $params[] = $schId;
        }
        $sql .= ' ORDER BY h.hostel_name ASC';

        return $db->query($sql, $params)->getResultArray();
    }

    public function getDetail(int $hostelId): ?array
    {
        return $this->find($hostelId);
    }

    public function nameExists(int $schId, string $name, ?int $excludeId = null): bool
    {
        $builder = $this->where('sch_id_fk', $schId)->where('hostel_name', $name);
        if ($excludeId) {
            $builder->where('hostel_id !=', $excludeId);
        }
        return (bool) $builder->first();
    }

    /**
     * Whether any room still belongs to this hostel (blocks delete).
     */
    public function isInUse(int $hostelId): bool
    {
        $db  = \Config\Database::connect();
        $row = $db->table('hostel_room')->where('hostel_id_fk', $hostelId)->get(1)->getRowArray();
        return (bool) $row;
    }
}
