<?php
namespace App\Models;
use CodeIgniter\Model;

class GatePassModel extends Model
{
    protected $table      = 'gate_pass';
    protected $primaryKey = 'pass_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'pass_number',
        'for_user_id_fk',
        'requested_by_user_id_fk',
        'pass_type',
        'reason',
        'requested_time',
        'status',
        'decided_by_user_id_fk',
        'decided_at',
        'decision_remarks',
        'used_at',
        'used_by_user_id_fk',
        'created_at',
        'updated_at',
    ];

    private const JOIN_SELECT = "gate_pass.*,
        f.fname AS for_fname, f.lname AS for_lname,
        req.fname AS requested_by_fname, req.lname AS requested_by_lname,
        dec.fname AS decided_by_fname, dec.lname AS decided_by_lname";

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `gate_pass` (
            `pass_id`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`                 INT UNSIGNED NOT NULL,
            `pass_number`                VARCHAR(30) NOT NULL,
            `for_user_id_fk`             INT UNSIGNED NOT NULL,
            `requested_by_user_id_fk`    INT UNSIGNED NOT NULL,
            `pass_type`                  VARCHAR(30) NOT NULL DEFAULT 'Other',
            `reason`                     VARCHAR(255) NOT NULL,
            `requested_time`             DATETIME NOT NULL,
            `status`                     VARCHAR(20) NOT NULL DEFAULT 'Pending',
            `decided_by_user_id_fk`      INT UNSIGNED DEFAULT NULL,
            `decided_at`                 DATETIME DEFAULT NULL,
            `decision_remarks`           VARCHAR(255) DEFAULT NULL,
            `used_at`                    DATETIME DEFAULT NULL,
            `used_by_user_id_fk`         INT UNSIGNED DEFAULT NULL,
            `created_at`                 DATETIME DEFAULT NULL,
            `updated_at`                 DATETIME DEFAULT NULL,
            PRIMARY KEY (`pass_id`),
            UNIQUE KEY `pass_number` (`pass_number`),
            KEY `sch_status` (`sch_id_fk`, `status`),
            KEY `for_user_id_fk` (`for_user_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getForSchool(int $schId, ?string $status = null, int $limit = 50, int $offset = 0): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('gate_pass')
            ->select(self::JOIN_SELECT)
            ->join('users f', 'f.user_id = gate_pass.for_user_id_fk', 'left')
            ->join('users req', 'req.user_id = gate_pass.requested_by_user_id_fk', 'left')
            ->join('users dec', 'dec.user_id = gate_pass.decided_by_user_id_fk', 'left')
            ->where('gate_pass.sch_id_fk', $schId);

        if ($status) {
            $builder->where('gate_pass.status', $status);
        }

        return $builder->orderBy('gate_pass.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }

    public function getForUser(int $userId, int $limit = 50, int $offset = 0): array
    {
        $db = \Config\Database::connect();
        return $db->table('gate_pass')
            ->select(self::JOIN_SELECT)
            ->join('users f', 'f.user_id = gate_pass.for_user_id_fk', 'left')
            ->join('users req', 'req.user_id = gate_pass.requested_by_user_id_fk', 'left')
            ->join('users dec', 'dec.user_id = gate_pass.decided_by_user_id_fk', 'left')
            ->where('gate_pass.for_user_id_fk', $userId)
            ->orderBy('gate_pass.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()->getResultArray();
    }

    public function getDetail(int $passId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('gate_pass')
            ->select(self::JOIN_SELECT)
            ->join('users f', 'f.user_id = gate_pass.for_user_id_fk', 'left')
            ->join('users req', 'req.user_id = gate_pass.requested_by_user_id_fk', 'left')
            ->join('users dec', 'dec.user_id = gate_pass.decided_by_user_id_fk', 'left')
            ->where('gate_pass.pass_id', $passId)
            ->get()->getRowArray();

        return $row ?: null;
    }

    public function decide(int $passId, string $status, int $byUserId, ?string $remarks): void
    {
        $this->update($passId, [
            'status'            => $status,
            'decided_by_user_id_fk' => $byUserId,
            'decided_at'        => date('Y-m-d H:i:s'),
            'decision_remarks'  => $remarks,
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);
    }

    public function markUsed(int $passId, int $byUserId): void
    {
        $this->update($passId, [
            'status'             => 'Used',
            'used_at'            => date('Y-m-d H:i:s'),
            'used_by_user_id_fk' => $byUserId,
            'updated_at'         => date('Y-m-d H:i:s'),
        ]);
    }

    public function cancel(int $passId): void
    {
        $this->update($passId, ['status' => 'Cancelled', 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function countPendingForSchool(int $schId): int
    {
        return $this->where('sch_id_fk', $schId)->where('status', 'Pending')->countAllResults();
    }

    /** Pass counts per day, by status, for the last N days (for the reports chart). */
    public function dailyCounts(int $schId, int $days): array
    {
        $from = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
        return $this->select("DATE(created_at) AS day, status, COUNT(*) AS total")
            ->where('sch_id_fk', $schId)
            ->where('created_at >=', $from)
            ->groupBy('DATE(created_at), status')
            ->orderBy('day', 'ASC')
            ->findAll();
    }

    /** Breakdown by pass_type for the last N days (for the reports pie/bar chart). */
    public function typeCounts(int $schId, int $days): array
    {
        $from = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
        return $this->select("pass_type, COUNT(*) AS total")
            ->where('sch_id_fk', $schId)
            ->where('created_at >=', $from)
            ->groupBy('pass_type')
            ->findAll();
    }
}
