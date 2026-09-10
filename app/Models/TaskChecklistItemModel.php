<?php
namespace App\Models;
use CodeIgniter\Model;

class TaskChecklistItemModel extends Model
{
    protected $table      = 'task_checklist_item';
    protected $primaryKey = 'checklist_item_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'task_id_fk',
        'item_text',
        'is_done',
        'sort_order',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `task_checklist_item` (
            `checklist_item_id`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `task_id_fk`         INT UNSIGNED NOT NULL,
            `item_text`          VARCHAR(255) NOT NULL,
            `is_done`            TINYINT(1) NOT NULL DEFAULT 0,
            `sort_order`         INT UNSIGNED NOT NULL DEFAULT 0,
            `created_at`         DATETIME DEFAULT NULL,
            `updated_at`         DATETIME DEFAULT NULL,
            PRIMARY KEY (`checklist_item_id`),
            KEY `task_id_fk` (`task_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByTask(int $taskId): array
    {
        return $this->where('task_id_fk', $taskId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('checklist_item_id', 'ASC')
            ->findAll();
    }

    public function nextSortOrder(int $taskId): int
    {
        $row = $this->selectMax('sort_order')->where('task_id_fk', $taskId)->first();
        return ((int) ($row['sort_order'] ?? 0)) + 1;
    }
}
