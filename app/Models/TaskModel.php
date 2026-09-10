<?php
namespace App\Models;
use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table      = 'task';
    protected $primaryKey = 'task_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'assigned_to_user_id_fk',
        'created_by_user_id_fk',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `task` (
            `task_id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`             INT UNSIGNED NOT NULL,
            `title`                 VARCHAR(200) NOT NULL,
            `description`           TEXT DEFAULT NULL,
            `priority`              ENUM('Low','Medium','High','Urgent') NOT NULL DEFAULT 'Medium',
            `status`                ENUM('To Do','In Progress','Done','Cancelled') NOT NULL DEFAULT 'To Do',
            `due_date`              DATE DEFAULT NULL,
            `assigned_to_user_id_fk` INT UNSIGNED NOT NULL,
            `created_by_user_id_fk` INT UNSIGNED NOT NULL,
            `created_at`            DATETIME DEFAULT NULL,
            `updated_at`            DATETIME DEFAULT NULL,
            PRIMARY KEY (`task_id`),
            KEY `sch_id_fk` (`sch_id_fk`),
            KEY `assigned_to_user_id_fk` (`assigned_to_user_id_fk`),
            KEY `created_by_user_id_fk` (`created_by_user_id_fk`),
            KEY `status` (`status`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    private const JOIN_SELECT = "
        t.*,
        au.fname AS assignee_fname, au.lname AS assignee_lname, au.profile_photo AS assignee_photo,
        cu.fname AS creator_fname, cu.lname AS creator_lname,
        (SELECT COUNT(*) FROM task_checklist_item ci WHERE ci.task_id_fk = t.task_id) AS checklist_total,
        (SELECT COUNT(*) FROM task_checklist_item ci WHERE ci.task_id_fk = t.task_id AND ci.is_done = 1) AS checklist_done,
        (SELECT COUNT(*) FROM task_comment tc WHERE tc.task_id_fk = t.task_id) AS comment_count
    ";

    private const JOIN_SQL = "
        FROM task t
        INNER JOIN users au ON au.user_id = t.assigned_to_user_id_fk
        INNER JOIN users cu ON cu.user_id = t.created_by_user_id_fk
    ";

    /**
     * All tasks for a school (0 = all schools) — the "All Tasks" oversight
     * view. Optional status/priority filters.
     */
    public function getAllForSchool(int $schId, ?string $status = null, ?string $priority = null): array
    {
        $db     = \Config\Database::connect();
        $sql    = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . ' WHERE 1=1';
        $params = [];
        if ($schId > 0) {
            $sql      .= ' AND t.sch_id_fk = ?';
            $params[] = $schId;
        }
        if ($status) {
            $sql      .= ' AND t.status = ?';
            $params[] = $status;
        }
        if ($priority) {
            $sql      .= ' AND t.priority = ?';
            $params[] = $priority;
        }
        $sql .= " ORDER BY (t.status = 'Done') ASC, (t.status = 'Cancelled') ASC, t.due_date IS NULL, t.due_date ASC, t.task_id DESC";
        return $db->query($sql, $params)->getResultArray();
    }

    /**
     * Tasks assigned to or created by the given user (the "My Tasks" view).
     */
    public function getForUser(int $userId): array
    {
        $db  = \Config\Database::connect();
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL
            . " WHERE (t.assigned_to_user_id_fk = ? OR t.created_by_user_id_fk = ?)"
            . " ORDER BY (t.status = 'Done') ASC, (t.status = 'Cancelled') ASC, t.due_date IS NULL, t.due_date ASC, t.task_id DESC";
        return $db->query($sql, [$userId, $userId])->getResultArray();
    }

    public function getDetail(int $taskId): ?array
    {
        $db  = \Config\Database::connect();
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . ' WHERE t.task_id = ?';
        return $db->query($sql, [$taskId])->getRowArray() ?: null;
    }

    /**
     * Whether the given user may view/act on this task: its assignee, its
     * creator, or an admin with `_task_manage_all`.
     */
    public function isParticipant(array $task, int $userId): bool
    {
        return (int) $task['assigned_to_user_id_fk'] === $userId
            || (int) $task['created_by_user_id_fk'] === $userId;
    }
}
