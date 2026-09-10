<?php
namespace App\Models;
use CodeIgniter\Model;

class PollModel extends Model
{
    protected $table      = 'poll';
    protected $primaryKey = 'poll_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'created_by_user_id_fk',
        'question',
        'description',
        'status',
        'closes_at',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `poll` (
            `poll_id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`             INT UNSIGNED NOT NULL,
            `created_by_user_id_fk` INT UNSIGNED NOT NULL,
            `question`              VARCHAR(500) NOT NULL,
            `description`           TEXT DEFAULT NULL,
            `status`                ENUM('Active','Closed') NOT NULL DEFAULT 'Active',
            `closes_at`             DATETIME DEFAULT NULL,
            `created_at`            DATETIME DEFAULT NULL,
            `updated_at`            DATETIME DEFAULT NULL,
            PRIMARY KEY (`poll_id`),
            KEY `sch_id_fk` (`sch_id_fk`),
            KEY `created_by_user_id_fk` (`created_by_user_id_fk`),
            KEY `status` (`status`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    private const JOIN_SELECT = "
        p.*,
        cu.fname AS creator_fname, cu.lname AS creator_lname,
        (SELECT COUNT(*) FROM poll_option o WHERE o.poll_id_fk = p.poll_id) AS option_count,
        (SELECT COUNT(*) FROM poll_vote v WHERE v.poll_id_fk = p.poll_id) AS vote_count
    ";

    private const JOIN_SQL = "
        FROM poll p
        INNER JOIN users cu ON cu.user_id = p.created_by_user_id_fk
    ";

    /**
     * Polls visible to a viewer: same school (or every school for Super
     * Admin when $schId is null), and either untargeted (no poll_audience
     * rows = everyone), targeted at the viewer's role_cat, or created by
     * the viewer themself (a creator can always see/manage their own poll).
     */
    public function getVisibleForUser(?int $schId, int $userId, int $roleCatId): array
    {
        $db     = \Config\Database::connect();
        $sql    = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . ' WHERE 1=1';
        $params = [];

        if ($schId !== null) {
            $sql      .= ' AND p.sch_id_fk = ?';
            $params[] = $schId;
        }

        $sql .= '
            AND (
                p.created_by_user_id_fk = ?
                OR NOT EXISTS (SELECT 1 FROM poll_audience pa WHERE pa.poll_id_fk = p.poll_id)
                OR EXISTS (SELECT 1 FROM poll_audience pa WHERE pa.poll_id_fk = p.poll_id AND pa.role_cat_id_fk = ?)
            )
        ';
        $params[] = $userId;
        $params[] = $roleCatId;

        $sql .= " ORDER BY (p.status = 'Closed') ASC, p.poll_id DESC";

        return $db->query($sql, $params)->getResultArray();
    }

    public function getDetail(int $pollId): ?array
    {
        $db  = \Config\Database::connect();
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . ' WHERE p.poll_id = ?';
        return $db->query($sql, [$pollId])->getRowArray() ?: null;
    }

    /**
     * True once a poll can no longer be voted on: manually closed, or its
     * optional closes_at deadline has passed.
     */
    public function isClosed(array $poll): bool
    {
        if ($poll['status'] === 'Closed') {
            return true;
        }
        return !empty($poll['closes_at']) && strtotime($poll['closes_at']) < time();
    }

    public function isVisibleToRoleCat(int $pollId, int $roleCatId): bool
    {
        $db  = \Config\Database::connect();
        $row = $db->table('poll_audience')->where('poll_id_fk', $pollId)->countAllResults();
        if ($row === 0) {
            return true; // untargeted = everyone
        }
        return (bool) $db->table('poll_audience')
            ->where('poll_id_fk', $pollId)
            ->where('role_cat_id_fk', $roleCatId)
            ->countAllResults();
    }

    public function close(int $pollId): void
    {
        $this->update($pollId, ['status' => 'Closed', 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /** Deletes a poll and all its child rows (options, audience, votes). */
    public function deleteCascade(int $pollId): void
    {
        $db = \Config\Database::connect();
        $db->table('poll_vote')->where('poll_id_fk', $pollId)->delete();
        $db->table('poll_option')->where('poll_id_fk', $pollId)->delete();
        $db->table('poll_audience')->where('poll_id_fk', $pollId)->delete();
        $this->delete($pollId);
    }
}
