<?php
namespace App\Models;
use CodeIgniter\Model;

class GateVisitorModel extends Model
{
    protected $table      = 'gate_visitor';
    protected $primaryKey = 'visitor_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'pass_number',
        'visitor_name',
        'visitor_phone',
        'purpose',
        'meet_user_id_fk',
        'meet_person_name',
        'photo',
        'id_proof',
        'items_carried',
        'check_in_at',
        'check_out_at',
        'status',
        'recorded_by_user_id_fk',
        'checked_out_by_user_id_fk',
        'created_at',
        'updated_at',
    ];

    private const JOIN_SELECT = 'gate_visitor.*,
        rec.fname AS recorded_by_fname, rec.lname AS recorded_by_lname,
        meet.fname AS meet_fname, meet.lname AS meet_lname';

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `gate_visitor` (
            `visitor_id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`                INT UNSIGNED NOT NULL,
            `pass_number`              VARCHAR(30) NOT NULL,
            `visitor_name`             VARCHAR(150) NOT NULL,
            `visitor_phone`            VARCHAR(20) DEFAULT NULL,
            `purpose`                  VARCHAR(150) DEFAULT NULL,
            `meet_user_id_fk`          INT UNSIGNED DEFAULT NULL,
            `meet_person_name`         VARCHAR(150) DEFAULT NULL,
            `photo`                    VARCHAR(255) DEFAULT NULL,
            `id_proof`                 VARCHAR(100) DEFAULT NULL,
            `items_carried`            VARCHAR(255) DEFAULT NULL,
            `check_in_at`              DATETIME NOT NULL,
            `check_out_at`             DATETIME DEFAULT NULL,
            `status`                   VARCHAR(20) NOT NULL DEFAULT 'Inside',
            `recorded_by_user_id_fk`   INT UNSIGNED NOT NULL,
            `checked_out_by_user_id_fk` INT UNSIGNED DEFAULT NULL,
            `created_at`               DATETIME DEFAULT NULL,
            `updated_at`               DATETIME DEFAULT NULL,
            PRIMARY KEY (`visitor_id`),
            KEY `sch_status` (`sch_id_fk`, `status`),
            KEY `check_in_at` (`check_in_at`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getForSchool(int $schId, ?string $status = null, int $limit = 50, int $offset = 0): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('gate_visitor')
            ->select(self::JOIN_SELECT)
            ->join('users rec', 'rec.user_id = gate_visitor.recorded_by_user_id_fk', 'left')
            ->join('users meet', 'meet.user_id = gate_visitor.meet_user_id_fk', 'left')
            ->where('gate_visitor.sch_id_fk', $schId);

        if ($status) {
            $builder->where('gate_visitor.status', $status);
        }

        return $builder->orderBy('gate_visitor.check_in_at', 'DESC')
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }

    public function getDetail(int $visitorId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('gate_visitor')
            ->select(self::JOIN_SELECT)
            ->join('users rec', 'rec.user_id = gate_visitor.recorded_by_user_id_fk', 'left')
            ->join('users meet', 'meet.user_id = gate_visitor.meet_user_id_fk', 'left')
            ->where('gate_visitor.visitor_id', $visitorId)
            ->get()->getRowArray();

        return $row ?: null;
    }

    public function checkOut(int $visitorId, int $byUserId): void
    {
        $this->update($visitorId, [
            'status'                    => 'Checked Out',
            'check_out_at'              => date('Y-m-d H:i:s'),
            'checked_out_by_user_id_fk' => $byUserId,
            'updated_at'                => date('Y-m-d H:i:s'),
        ]);
    }

    public function countInsideForSchool(int $schId): int
    {
        return $this->where('sch_id_fk', $schId)->where('status', 'Inside')->countAllResults();
    }

    /** Visitor check-ins per day for the last N days (for the reports chart). */
    public function dailyCounts(int $schId, int $days): array
    {
        $from = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
        $rows = $this->select("DATE(check_in_at) AS day, COUNT(*) AS total")
            ->where('sch_id_fk', $schId)
            ->where('check_in_at >=', $from)
            ->groupBy('DATE(check_in_at)')
            ->orderBy('day', 'ASC')
            ->findAll();

        return $rows;
    }
}
