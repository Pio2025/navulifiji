<?php
namespace App\Models;
use CodeIgniter\Model;

class GateEntryLogModel extends Model
{
    protected $table      = 'gate_entry_log';
    protected $primaryKey = 'entry_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'user_id_fk',
        'role_cat_id_fk',
        'direction',
        'event_at',
        'remarks',
        'recorded_by_user_id_fk',
        'created_at',
    ];

    private const JOIN_SELECT = "gate_entry_log.*,
        u.fname, u.lname, u.profile_photo,
        rec.fname AS recorded_by_fname, rec.lname AS recorded_by_lname";

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `gate_entry_log` (
            `entry_id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`              INT UNSIGNED NOT NULL,
            `user_id_fk`             INT UNSIGNED NOT NULL,
            `role_cat_id_fk`         INT UNSIGNED NOT NULL,
            `direction`              VARCHAR(10) NOT NULL,
            `event_at`               DATETIME NOT NULL,
            `remarks`                VARCHAR(255) DEFAULT NULL,
            `recorded_by_user_id_fk` INT UNSIGNED NOT NULL,
            `created_at`             DATETIME DEFAULT NULL,
            PRIMARY KEY (`entry_id`),
            KEY `sch_user` (`sch_id_fk`, `user_id_fk`),
            KEY `event_at` (`event_at`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getForSchool(int $schId, int $limit = 50, int $offset = 0): array
    {
        $db = \Config\Database::connect();
        return $db->table('gate_entry_log')
            ->select(self::JOIN_SELECT)
            ->join('users u', 'u.user_id = gate_entry_log.user_id_fk', 'left')
            ->join('users rec', 'rec.user_id = gate_entry_log.recorded_by_user_id_fk', 'left')
            ->where('gate_entry_log.sch_id_fk', $schId)
            ->orderBy('gate_entry_log.event_at', 'DESC')
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }

    /** Last logged direction for a user today, so the UI can offer the opposite action. */
    public function lastDirectionToday(int $schId, int $userId): ?string
    {
        $row = $this->where('sch_id_fk', $schId)
            ->where('user_id_fk', $userId)
            ->where('event_at >=', date('Y-m-d 00:00:00'))
            ->orderBy('event_at', 'DESC')
            ->first();

        return $row['direction'] ?? null;
    }

    /** In/Out counts per day for the last N days, split by direction (for the reports chart). */
    public function dailyCounts(int $schId, int $days): array
    {
        $from = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
        return $this->select("DATE(event_at) AS day, direction, COUNT(*) AS total")
            ->where('sch_id_fk', $schId)
            ->where('event_at >=', $from)
            ->groupBy('DATE(event_at), direction')
            ->orderBy('day', 'ASC')
            ->findAll();
    }
}
