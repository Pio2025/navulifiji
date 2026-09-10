<?php
namespace App\Models;
use CodeIgniter\Model;

class PollOptionModel extends Model
{
    protected $table      = 'poll_option';
    protected $primaryKey = 'option_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'poll_id_fk',
        'option_text',
        'sort_order',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `poll_option` (
            `option_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `poll_id_fk`  INT UNSIGNED NOT NULL,
            `option_text` VARCHAR(255) NOT NULL,
            `sort_order`  INT UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (`option_id`),
            KEY `poll_id_fk` (`poll_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /** Options for a poll with live vote counts, in display order. */
    public function getWithCounts(int $pollId): array
    {
        $db  = \Config\Database::connect();
        $sql = "
            SELECT o.*,
                (SELECT COUNT(*) FROM poll_vote v WHERE v.option_id_fk = o.option_id) AS vote_count
            FROM poll_option o
            WHERE o.poll_id_fk = ?
            ORDER BY o.sort_order ASC, o.option_id ASC
        ";
        return $db->query($sql, [$pollId])->getResultArray();
    }
}
