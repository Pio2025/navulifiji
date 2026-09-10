<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelRoomModel extends Model
{
    protected $table      = 'hostel_room';
    protected $primaryKey = 'room_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'hostel_id_fk',
        'sch_id_fk',
        'room_number',
        'capacity',
        'description',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `hostel_room` (
            `room_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `hostel_id_fk` INT UNSIGNED NOT NULL,
            `sch_id_fk`   INT UNSIGNED NOT NULL,
            `room_number` VARCHAR(20) NOT NULL,
            `capacity`    INT UNSIGNED NOT NULL DEFAULT 1,
            `description` VARCHAR(255) DEFAULT NULL,
            `created_at`  DATETIME DEFAULT NULL,
            `updated_at`  DATETIME DEFAULT NULL,
            PRIMARY KEY (`room_id`),
            UNIQUE KEY `uniq_hostel_room` (`hostel_id_fk`, `room_number`),
            KEY `sch_id_fk` (`sch_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Rooms within a hostel, with a live occupied-count (Active allocations),
     * for the hostel detail page.
     */
    public function getByHostel(int $hostelId): array
    {
        $db  = \Config\Database::connect();
        $sql = "
            SELECT r.*,
                (SELECT COUNT(*) FROM hostel_allocation a WHERE a.room_id_fk = r.room_id AND a.status = 'Active') AS occupied_count
            FROM hostel_room r
            WHERE r.hostel_id_fk = ?
            ORDER BY r.room_number ASC
        ";
        return $db->query($sql, [$hostelId])->getResultArray();
    }

    public function getDetail(int $roomId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('hostel_room r')
            ->select('r.*, h.hostel_name, h.sch_id_fk AS hostel_sch_id')
            ->join('hostel h', 'h.hostel_id = r.hostel_id_fk', 'left')
            ->where('r.room_id', $roomId)
            ->get()->getRowArray();
        return $row ?: null;
    }

    public function numberExists(int $hostelId, string $roomNumber, ?int $excludeId = null): bool
    {
        $builder = $this->where('hostel_id_fk', $hostelId)->where('room_number', $roomNumber);
        if ($excludeId) {
            $builder->where('room_id !=', $excludeId);
        }
        return (bool) $builder->first();
    }

    public function getOccupiedCount(int $roomId): int
    {
        $db  = \Config\Database::connect();
        $row = $db->table('hostel_allocation')
            ->where('room_id_fk', $roomId)
            ->where('status', 'Active')
            ->countAllResults();
        return (int) $row;
    }

    /**
     * Rooms with at least one free bed (for the allocate-boarder picker).
     */
    public function getAvailableBySchool(int $schId): array
    {
        $db  = \Config\Database::connect();
        $sql = "
            SELECT r.*, h.hostel_name,
                (SELECT COUNT(*) FROM hostel_allocation a WHERE a.room_id_fk = r.room_id AND a.status = 'Active') AS occupied_count
            FROM hostel_room r
            INNER JOIN hostel h ON h.hostel_id = r.hostel_id_fk
            WHERE r.sch_id_fk = ?
            HAVING occupied_count < r.capacity
            ORDER BY h.hostel_name ASC, r.room_number ASC
        ";
        return $db->query($sql, [$schId])->getResultArray();
    }

    /**
     * Whether this room has ever been referenced by an allocation (blocks delete).
     */
    public function isInUse(int $roomId): bool
    {
        $db  = \Config\Database::connect();
        $row = $db->table('hostel_allocation')->where('room_id_fk', $roomId)->get(1)->getRowArray();
        return (bool) $row;
    }
}
