<?php
namespace App\Models;
use CodeIgniter\Model;

class HostelAllocationModel extends Model
{
    protected $table      = 'hostel_allocation';
    protected $primaryKey = 'allocation_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'room_id_fk',
        'hostel_id_fk',
        'boarder_admission_id_fk',
        'sch_id_fk',
        'check_in_date',
        'check_out_date',
        'status',
        'fee_amount',
        'fee_paid',
        'remarks',
        'allocated_by',
        'vacated_by',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `hostel_allocation` (
            `allocation_id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `room_id_fk`                INT UNSIGNED NOT NULL,
            `hostel_id_fk`              INT UNSIGNED NOT NULL,
            `boarder_admission_id_fk`   INT UNSIGNED NOT NULL,
            `sch_id_fk`                 INT UNSIGNED NOT NULL,
            `check_in_date`             DATE NOT NULL,
            `check_out_date`            DATE DEFAULT NULL,
            `status`                    ENUM('Active','Vacated') NOT NULL DEFAULT 'Active',
            `fee_amount`                DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `fee_paid`                  TINYINT(1) NOT NULL DEFAULT 0,
            `remarks`                   VARCHAR(255) DEFAULT NULL,
            `allocated_by`              INT UNSIGNED DEFAULT NULL,
            `vacated_by`                INT UNSIGNED DEFAULT NULL,
            `created_at`                DATETIME DEFAULT NULL,
            `updated_at`                DATETIME DEFAULT NULL,
            PRIMARY KEY (`allocation_id`),
            KEY `room_id_fk` (`room_id_fk`),
            KEY `boarder_admission_id_fk` (`boarder_admission_id_fk`),
            KEY `status` (`status`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    private const JOIN_SELECT = "
        ha.*,
        r.room_number, h.hostel_name, h.hostel_type,
        adm.sch_id_fk AS borrower_sch_id,
        bu.fname AS borrower_fname, bu.lname AS borrower_lname, bu.profile_photo AS borrower_photo,
        role_category.role_cat_name AS borrower_role_cat
    ";

    private const JOIN_SQL = "
        FROM hostel_allocation ha
        INNER JOIN hostel_room r   ON r.room_id = ha.room_id_fk
        INNER JOIN hostel h        ON h.hostel_id = ha.hostel_id_fk
        INNER JOIN admission adm   ON adm.admission_id = ha.boarder_admission_id_fk
        INNER JOIN users bu        ON bu.user_id = adm.user_id_fk
        LEFT JOIN user_role     ON user_role.user_id_fk = bu.user_id
        LEFT JOIN role          ON role.role_id = user_role.role_id_fk
        LEFT JOIN role_category ON role_category.role_cat_id = role.role_cat_id_fk
    ";

    /**
     * Currently active allocations for a school (0 = all).
     */
    public function getActiveBySchool(int $schId): array
    {
        $db     = \Config\Database::connect();
        $sql    = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . " WHERE ha.status = 'Active'";
        $params = [];
        if ($schId > 0) {
            $sql      .= ' AND ha.sch_id_fk = ?';
            $params[] = $schId;
        }
        $sql .= ' ORDER BY h.hostel_name ASC, r.room_number ASC';
        return $db->query($sql, $params)->getResultArray();
    }

    /**
     * All allocation rows (any status) for the given borrower admission_ids —
     * used by the self-service "My Hostel" page for a student and, for a
     * parent, one or more linked children.
     */
    public function getByBorrowers(array $admissionIds): array
    {
        if (empty($admissionIds)) {
            return [];
        }
        $db = \Config\Database::connect();
        $in = implode(',', array_fill(0, count($admissionIds), '?'));
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL
            . " WHERE ha.boarder_admission_id_fk IN ($in) ORDER BY ha.status = 'Active' DESC, ha.check_in_date DESC";
        return $db->query($sql, $admissionIds)->getResultArray();
    }

    public function getActiveByBoarder(int $admissionId): ?array
    {
        $db  = \Config\Database::connect();
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL
            . " WHERE ha.boarder_admission_id_fk = ? AND ha.status = 'Active'";
        return $db->query($sql, [$admissionId])->getRowArray() ?: null;
    }

    public function getDetail(int $allocationId): ?array
    {
        $db  = \Config\Database::connect();
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . ' WHERE ha.allocation_id = ?';
        return $db->query($sql, [$allocationId])->getRowArray() ?: null;
    }

    public function getActiveCountForRoom(int $roomId): int
    {
        return $this->where('room_id_fk', $roomId)
            ->where('status', 'Active')
            ->countAllResults();
    }
}
