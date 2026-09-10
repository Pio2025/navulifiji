<?php
namespace App\Models;
use CodeIgniter\Model;

class TaskCommentModel extends Model
{
    protected $table      = 'task_comment';
    protected $primaryKey = 'comment_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'task_id_fk',
        'user_id_fk',
        'comment_text',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `task_comment` (
            `comment_id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `task_id_fk`         INT UNSIGNED NOT NULL,
            `user_id_fk`         INT UNSIGNED NOT NULL,
            `comment_text`       TEXT NOT NULL,
            `created_at`         DATETIME DEFAULT NULL,
            `updated_at`         DATETIME DEFAULT NULL,
            PRIMARY KEY (`comment_id`),
            KEY `task_id_fk` (`task_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByTask(int $taskId): array
    {
        $db  = \Config\Database::connect();
        $sql = "
            SELECT tc.*, u.fname, u.lname, u.profile_photo
            FROM task_comment tc
            INNER JOIN users u ON u.user_id = tc.user_id_fk
            WHERE tc.task_id_fk = ?
            ORDER BY tc.comment_id ASC
        ";
        return $db->query($sql, [$taskId])->getResultArray();
    }
}
