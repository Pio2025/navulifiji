<?php
namespace App\Models;
use CodeIgniter\Model;

class DocManagerFileModel extends Model
{
    protected $table      = 'doc_manager_file';
    protected $primaryKey = 'doc_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'user_id_fk',
        'file_name',
        'original_name',
        'description',
        'uploaded_by_user_id_fk',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `doc_manager_file` (
            `doc_id`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`               INT UNSIGNED NOT NULL,
            `user_id_fk`              INT UNSIGNED NOT NULL,
            `file_name`               VARCHAR(255) NOT NULL,
            `original_name`           VARCHAR(255) NOT NULL,
            `description`             VARCHAR(255) DEFAULT NULL,
            `uploaded_by_user_id_fk`  INT UNSIGNED NOT NULL,
            `created_at`              DATETIME DEFAULT NULL,
            `updated_at`              DATETIME DEFAULT NULL,
            PRIMARY KEY (`doc_id`),
            KEY `sch_id_fk` (`sch_id_fk`),
            KEY `user_id_fk` (`user_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getForUser(int $userId): array
    {
        return $this->where('user_id_fk', $userId)->orderBy('doc_id', 'DESC')->findAll();
    }
}
