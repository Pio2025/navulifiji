<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Poll module: quick single-choice polls staff can run to gather opinions
 * from staff, students, or parents, optionally targeted at specific role
 * groups, with live results as votes come in.
 *
 * `_poll_access` (view/vote) is granted to literally every role — anyone
 * may be polled. `_poll_create` (create a poll) is granted broadly to every
 * non-Student/non-Parent role_cat, mirroring Task's `_task_access` grant,
 * since poll creation is a staff activity. `_poll_manage_all` is admin-tier
 * only, for closing/deleting any poll school-wide (not just one's own).
 */
class CreatePollModule extends Migration
{
    private string $accessCode = '_poll_access';

    private string $createCode = '_poll_create';

    private string $adminCode = '_poll_manage_all';

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

        // ── poll ────────────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `poll` (
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
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── poll_option ─────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `poll_option` (
                `option_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `poll_id_fk`  INT UNSIGNED NOT NULL,
                `option_text` VARCHAR(255) NOT NULL,
                `sort_order`  INT UNSIGNED NOT NULL DEFAULT 0,
                PRIMARY KEY (`option_id`),
                KEY `poll_id_fk` (`poll_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── poll_audience (role_cat targeting; no rows = everyone) ──────
        $db->query("
            CREATE TABLE IF NOT EXISTS `poll_audience` (
                `audience_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `poll_id_fk`      INT UNSIGNED NOT NULL,
                `role_cat_id_fk`  INT UNSIGNED NOT NULL,
                PRIMARY KEY (`audience_id`),
                KEY `poll_id_fk` (`poll_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── poll_vote ───────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `poll_vote` (
                `vote_id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `poll_id_fk`   INT UNSIGNED NOT NULL,
                `option_id_fk` INT UNSIGNED NOT NULL,
                `user_id_fk`   INT UNSIGNED NOT NULL,
                `created_at`   DATETIME DEFAULT NULL,
                PRIMARY KEY (`vote_id`),
                UNIQUE KEY `poll_user` (`poll_id_fk`, `user_id_fk`),
                KEY `option_id_fk` (`option_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── modules ─────────────────────────────────────────────────────
        $mod = $db->table('modules')->where('module_name', 'Poll')->get()->getRowArray();
        if (!$mod) {
            $db->table('modules')->insert([
                'module_name' => 'Poll',
                'module_icon' => '<i class="ki-duotone ki-chart-simple fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>',
                'module_svg'  => '',
            ]);
        }
        $moduleId = $db->table('modules')->where('module_name', 'Poll')->get()->getRowArray()['module_id'];

        // ── permission ──────────────────────────────────────────────────
        $allCodes = [$this->accessCode, $this->createCode, $this->adminCode];
        $existing = $db->table('permission')->whereIn('perm_code', $allCodes)->countAllResults();

        if ($existing === 0) {
            $db->table('permission')->insertBatch([
                ['module_id_fk' => $moduleId, 'perm_name' => 'Poll',            'perm_desc' => 'View polls targeted at me and cast my vote.',            'perm_controller' => 'poll',      'perm_code' => $this->accessCode, 'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Create Poll',     'perm_desc' => 'Create polls and target them at specific groups.',        'perm_controller' => 'poll',      'perm_code' => $this->createCode, 'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Manage All Polls', 'perm_desc' => 'Close or delete any poll in the school, not just my own.', 'perm_controller' => 'poll',      'perm_code' => $this->adminCode,  'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ── grant _poll_access to literally every role ─────────────────
        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM   permission p
            CROSS  JOIN role r
            WHERE  p.perm_code = " . $db->escape($this->accessCode) . "
              AND  NOT EXISTS (
                       SELECT 1 FROM role_permission x
                       WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
                   )
        ");

        // ── grant _poll_create to every staff-tier role (all role
        //    categories except Student=4 and Parent=6) ────────────────────
        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM   permission p
            CROSS  JOIN role r
            WHERE  p.perm_code = " . $db->escape($this->createCode) . "
              AND  r.role_cat_id_fk NOT IN (4, 6)
              AND  NOT EXISTS (
                       SELECT 1 FROM role_permission x
                       WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
                   )
        ");

        // ── grant _poll_manage_all to admin-tier roles only ────────────
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

        $allCodes     = [$this->accessCode, $this->createCode, $this->adminCode];
        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $allCodes));

        $db->query("DELETE rp FROM role_permission rp JOIN permission p ON p.perm_id = rp.perm_id_fk WHERE p.perm_code IN ({$escapedCodes})");
        $db->table('permission')->whereIn('perm_code', $allCodes)->delete();
        $db->table('modules')->where('module_name', 'Poll')->delete();

        foreach (['poll_vote', 'poll_audience', 'poll_option', 'poll'] as $table) {
            $db->query("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
