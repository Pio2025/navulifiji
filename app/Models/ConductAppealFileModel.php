<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductAppealFileModel extends Model
{
    protected $table      = 'conduct_appeal_files';
    protected $primaryKey = 'appeal_file_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'appeal_id',
        'file_src',
        'file_type',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `conduct_appeal_files` (
            `appeal_file_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `appeal_id`      INT UNSIGNED DEFAULT NULL,
            `file_src`       VARCHAR(255) DEFAULT NULL,
            `file_type`      VARCHAR(100) DEFAULT NULL,
            PRIMARY KEY (`appeal_file_id`),
            KEY `appeal_id` (`appeal_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByAppeal(int $appealId): array
    {
        return $this->where('appeal_id', $appealId)->findAll();
    }
}
