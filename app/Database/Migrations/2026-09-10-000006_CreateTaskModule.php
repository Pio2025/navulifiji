<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Task management module: tasks assignable to any staff member (not just
 * admins), with priority, due date, status workflow, a checklist of
 * subtasks, and a comment thread — school-scoped. Everyone in a
 * non-Student/non-Parent role gets a "My Tasks" view (tasks they created or
 * are assigned) via `_task_access`, granted broadly by role_cat_id_fk rather
 * than an explicit role-name list since every staff-tier role should
 * participate. Admin-tier roles additionally get `_task_manage_all` for an
 * "All Tasks" oversight view across the whole school.
 */
class CreateTaskModule extends Migration
{
    private string $accessCode = '_task_access';

    private string $adminCode = '_task_manage_all';

    private array $adminRoles = [
        'Super Admin',
        'School Admin',
        'Principal',
        'Assistant Principal',
        'Vice Principal',
        'Head Master',
        'Assistant Head Master',
    ];

    public function up(): void
    {
        $db  = $this->db;
        $now = date('Y-m-d H:i:s');

        // ── task ────────────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `task` (
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
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── task_checklist_item ─────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `task_checklist_item` (
                `checklist_item_id`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `task_id_fk`         INT UNSIGNED NOT NULL,
                `item_text`          VARCHAR(255) NOT NULL,
                `is_done`            TINYINT(1) NOT NULL DEFAULT 0,
                `sort_order`         INT UNSIGNED NOT NULL DEFAULT 0,
                `created_at`         DATETIME DEFAULT NULL,
                `updated_at`         DATETIME DEFAULT NULL,
                PRIMARY KEY (`checklist_item_id`),
                KEY `task_id_fk` (`task_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── task_comment ────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `task_comment` (
                `comment_id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `task_id_fk`         INT UNSIGNED NOT NULL,
                `user_id_fk`         INT UNSIGNED NOT NULL,
                `comment_text`       TEXT NOT NULL,
                `created_at`         DATETIME DEFAULT NULL,
                `updated_at`         DATETIME DEFAULT NULL,
                PRIMARY KEY (`comment_id`),
                KEY `task_id_fk` (`task_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── modules ─────────────────────────────────────────────────────
        $mod = $db->table('modules')->where('module_name', 'Task')->get()->getRowArray();
        if (!$mod) {
            $db->table('modules')->insert([
                'module_name' => 'Task',
                'module_icon' => '<i class="ki-duotone ki-check-square fs-2"><span class="path1"></span><span class="path2"></span></i>',
                'module_svg'  => '',
            ]);
        }
        $moduleId = $db->table('modules')->where('module_name', 'Task')->get()->getRowArray()['module_id'];

        // ── permission ──────────────────────────────────────────────────
        $allCodes = [$this->accessCode, $this->adminCode];
        $existing = $db->table('permission')->whereIn('perm_code', $allCodes)->countAllResults();

        if ($existing === 0) {
            $db->table('permission')->insertBatch([
                ['module_id_fk' => $moduleId, 'perm_name' => 'Tasks',     'perm_desc' => 'Create tasks, assign them to staff, and manage tasks I created or am assigned.', 'perm_controller' => 'task',     'perm_code' => $this->accessCode, 'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'All Tasks', 'perm_desc' => 'View and manage every task in the school.',                                    'perm_controller' => 'task/all', 'perm_code' => $this->adminCode,  'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ── grant _task_access broadly to every staff role (all role
        //    categories except Student=4 and Parent=6) ────────────────────
        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM   permission p
            CROSS  JOIN role r
            WHERE  p.perm_code = " . $db->escape($this->accessCode) . "
              AND  r.role_cat_id_fk NOT IN (4, 6)
              AND  NOT EXISTS (
                       SELECT 1 FROM role_permission x
                       WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
                   )
        ");

        // ── grant _task_manage_all to admin-tier roles only ───────────────
        $escapedRoles = implode(',', array_map(fn($r) => $db->escape($r), $this->adminRoles));
        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM   permission p
            CROSS  JOIN role r
            WHERE  p.perm_code = " . $db->escape($this->adminCode) . "
              AND  r.role_name IN ({$escapedRoles})
              AND  NOT EXISTS (
                       SELECT 1 FROM role_permission x
                       WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
                   )
        ");
    }

    public function down(): void
    {
        $db = $this->db;

        $allCodes     = [$this->accessCode, $this->adminCode];
        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $allCodes));

        $db->query("DELETE rp FROM role_permission rp JOIN permission p ON p.perm_id = rp.perm_id_fk WHERE p.perm_code IN ({$escapedCodes})");
        $db->table('permission')->whereIn('perm_code', $allCodes)->delete();
        $db->table('modules')->where('module_name', 'Task')->delete();

        foreach (['task_comment', 'task_checklist_item', 'task'] as $table) {
            $db->query("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
