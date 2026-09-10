<?php
namespace App\Models;
use CodeIgniter\Model;

class PollVoteModel extends Model
{
    protected $table      = 'poll_vote';
    protected $primaryKey = 'vote_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'poll_id_fk',
        'option_id_fk',
        'user_id_fk',
        'created_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `poll_vote` (
            `vote_id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `poll_id_fk`   INT UNSIGNED NOT NULL,
            `option_id_fk` INT UNSIGNED NOT NULL,
            `user_id_fk`   INT UNSIGNED NOT NULL,
            `created_at`   DATETIME DEFAULT NULL,
            PRIMARY KEY (`vote_id`),
            UNIQUE KEY `poll_user` (`poll_id_fk`, `user_id_fk`),
            KEY `option_id_fk` (`option_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getUserVoteOptionId(int $pollId, int $userId): ?int
    {
        $row = $this->where('poll_id_fk', $pollId)->where('user_id_fk', $userId)->first();
        return $row ? (int) $row['option_id_fk'] : null;
    }

    /** Single-choice vote: replaces any prior vote by this user on this poll. */
    public function castVote(int $pollId, int $optionId, int $userId): void
    {
        $this->where('poll_id_fk', $pollId)->where('user_id_fk', $userId)->delete();
        $this->insert([
            'poll_id_fk'   => $pollId,
            'option_id_fk' => $optionId,
            'user_id_fk'   => $userId,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    public function countForPoll(int $pollId): int
    {
        return $this->where('poll_id_fk', $pollId)->countAllResults();
    }
}
