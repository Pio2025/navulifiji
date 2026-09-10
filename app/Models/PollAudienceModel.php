<?php
namespace App\Models;
use CodeIgniter\Model;

class PollAudienceModel extends Model
{
    protected $table      = 'poll_audience';
    protected $primaryKey = 'audience_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'poll_id_fk',
        'role_cat_id_fk',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `poll_audience` (
            `audience_id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `poll_id_fk`     INT UNSIGNED NOT NULL,
            `role_cat_id_fk` INT UNSIGNED NOT NULL,
            PRIMARY KEY (`audience_id`),
            KEY `poll_id_fk` (`poll_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getRoleCatsForPoll(int $pollId): array
    {
        return array_column(
            $this->where('poll_id_fk', $pollId)->findAll(),
            'role_cat_id_fk'
        );
    }
}
