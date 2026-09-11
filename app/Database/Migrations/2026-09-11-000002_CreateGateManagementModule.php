<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGateManagementModule extends Migration
{
    private string $accessCode      = '_gate_access';
    private string $markCode        = '_gate_mark';
    private string $passApproveCode = '_gate_pass_approve';
    private string $reportsCode     = '_gate_reports';
    private string $adminCode       = '_gate_manage_all';

    private array $adminRoles = [
        'Super Admin',
        'School Admin',
        'Principal',
        'Assistant Principal',
        'Vice Principal',
        'Head Master',
        'Assistant Head Master',
    ];

    public function up()
    {
        $db = \Config\Database::connect();

        $db->query("CREATE TABLE IF NOT EXISTS `gate_setting` (
            `gate_setting_id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`                 INT UNSIGNED NOT NULL,
            `pass_prefix`               VARCHAR(10) NOT NULL DEFAULT 'GP-',
            `next_pass_seq`             INT UNSIGNED NOT NULL DEFAULT 1,
            `notify_on_visitor_checkin` TINYINT(1) NOT NULL DEFAULT 1,
            `notify_on_pass_request`    TINYINT(1) NOT NULL DEFAULT 1,
            `notify_on_pass_decision`   TINYINT(1) NOT NULL DEFAULT 1,
            `created_at`                DATETIME DEFAULT NULL,
            `updated_at`                DATETIME DEFAULT NULL,
            PRIMARY KEY (`gate_setting_id`),
            UNIQUE KEY `sch_id_fk` (`sch_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");

        $db->query("CREATE TABLE IF NOT EXISTS `gate_form_option` (
            `option_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`     INT UNSIGNED NOT NULL,
            `option_type`   VARCHAR(30) NOT NULL,
            `option_label`  VARCHAR(100) NOT NULL,
            `sort_order`    INT NOT NULL DEFAULT 0,
            `option_status` VARCHAR(20) NOT NULL DEFAULT 'Active',
            `created_at`    DATETIME DEFAULT NULL,
            PRIMARY KEY (`option_id`),
            KEY `sch_type` (`sch_id_fk`, `option_type`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");

        $db->query("CREATE TABLE IF NOT EXISTS `gate_visitor` (
            `visitor_id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`                INT UNSIGNED NOT NULL,
            `pass_number`              VARCHAR(30) NOT NULL,
            `visitor_name`             VARCHAR(150) NOT NULL,
            `visitor_phone`            VARCHAR(20) DEFAULT NULL,
            `purpose`                  VARCHAR(150) DEFAULT NULL,
            `meet_user_id_fk`          INT UNSIGNED DEFAULT NULL,
            `meet_person_name`         VARCHAR(150) DEFAULT NULL,
            `photo`                    VARCHAR(255) DEFAULT NULL,
            `id_proof`                 VARCHAR(100) DEFAULT NULL,
            `items_carried`            VARCHAR(255) DEFAULT NULL,
            `check_in_at`              DATETIME NOT NULL,
            `check_out_at`             DATETIME DEFAULT NULL,
            `status`                   VARCHAR(20) NOT NULL DEFAULT 'Inside',
            `recorded_by_user_id_fk`   INT UNSIGNED NOT NULL,
            `checked_out_by_user_id_fk` INT UNSIGNED DEFAULT NULL,
            `created_at`               DATETIME DEFAULT NULL,
            `updated_at`               DATETIME DEFAULT NULL,
            PRIMARY KEY (`visitor_id`),
            KEY `sch_status` (`sch_id_fk`, `status`),
            KEY `check_in_at` (`check_in_at`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");

        $db->query("CREATE TABLE IF NOT EXISTS `gate_entry_log` (
            `entry_id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`              INT UNSIGNED NOT NULL,
            `user_id_fk`             INT UNSIGNED NOT NULL,
            `role_cat_id_fk`         INT UNSIGNED NOT NULL,
            `direction`              VARCHAR(10) NOT NULL,
            `event_at`               DATETIME NOT NULL,
            `remarks`                VARCHAR(255) DEFAULT NULL,
            `recorded_by_user_id_fk` INT UNSIGNED NOT NULL,
            `created_at`             DATETIME DEFAULT NULL,
            PRIMARY KEY (`entry_id`),
            KEY `sch_user` (`sch_id_fk`, `user_id_fk`),
            KEY `event_at` (`event_at`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");

        $db->query("CREATE TABLE IF NOT EXISTS `gate_pass` (
            `pass_id`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`                 INT UNSIGNED NOT NULL,
            `pass_number`                VARCHAR(30) NOT NULL,
            `for_user_id_fk`             INT UNSIGNED NOT NULL,
            `requested_by_user_id_fk`    INT UNSIGNED NOT NULL,
            `pass_type`                  VARCHAR(30) NOT NULL DEFAULT 'Other',
            `reason`                     VARCHAR(255) NOT NULL,
            `requested_time`             DATETIME NOT NULL,
            `status`                     VARCHAR(20) NOT NULL DEFAULT 'Pending',
            `decided_by_user_id_fk`      INT UNSIGNED DEFAULT NULL,
            `decided_at`                 DATETIME DEFAULT NULL,
            `decision_remarks`           VARCHAR(255) DEFAULT NULL,
            `used_at`                    DATETIME DEFAULT NULL,
            `used_by_user_id_fk`         INT UNSIGNED DEFAULT NULL,
            `created_at`                 DATETIME DEFAULT NULL,
            `updated_at`                 DATETIME DEFAULT NULL,
            PRIMARY KEY (`pass_id`),
            UNIQUE KEY `pass_number` (`pass_number`),
            KEY `sch_status` (`sch_id_fk`, `status`),
            KEY `for_user_id_fk` (`for_user_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");

        // ── module ───────────────────────────────────────────────────────
        $existingModule = $db->table('modules')->where('module_name', 'Gate Management')->get()->getRowArray();
        if (!$existingModule) {
            $db->table('modules')->insert([
                'module_name' => 'Gate Management',
                'module_icon' => '<i class="ki-duotone ki-shield-tick fs-2"><span class="path1"></span><span class="path2"></span></i>',
                'module_svg'  => null,
            ]);
        }
        $moduleId = (int) ($existingModule['module_id'] ?? $db->insertID());

        // ── permissions ──────────────────────────────────────────────────
        $now = date('Y-m-d H:i:s');
        $permissions = [
            [
                'module_id_fk'     => $moduleId,
                'perm_name'        => 'Gate Access',
                'perm_desc'        => 'View gate activity relevant to the user (visitors, own entries, own gate passes) and request a gate pass.',
                'perm_controller'  => 'gate',
                'perm_code'        => $this->accessCode,
                'show_in_nav'      => 1,
                'perm_status'      => 'Active',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'module_id_fk'     => $moduleId,
                'perm_name'        => 'Mark Gate Entries',
                'perm_desc'        => 'Record visitor check-in/check-out and staff/student gate entry/exit at the gate desk.',
                'perm_controller'  => 'gate',
                'perm_code'        => $this->markCode,
                'show_in_nav'      => 0,
                'perm_status'      => 'Active',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'module_id_fk'     => $moduleId,
                'perm_name'        => 'Approve Gate Passes',
                'perm_desc'        => 'Approve or reject gate pass requests.',
                'perm_controller'  => 'gate',
                'perm_code'        => $this->passApproveCode,
                'show_in_nav'      => 0,
                'perm_status'      => 'Active',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'module_id_fk'     => $moduleId,
                'perm_name'        => 'Gate Reports',
                'perm_desc'        => 'Privileged access to gate management graph and tabular reports.',
                'perm_controller'  => 'gate',
                'perm_code'        => $this->reportsCode,
                'show_in_nav'      => 0,
                'perm_status'      => 'Active',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'module_id_fk'     => $moduleId,
                'perm_name'        => 'Manage Gate Settings',
                'perm_desc'        => 'Configure gate management form settings, notifications, pass number prefix, and manage/delete all records.',
                'perm_controller'  => 'gate',
                'perm_code'        => $this->adminCode,
                'show_in_nav'      => 0,
                'perm_status'      => 'Active',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ];

        foreach ($permissions as $perm) {
            $exists = $db->table('permission')->where('perm_code', $perm['perm_code'])->get()->getRowArray();
            if (!$exists) {
                $db->table('permission')->insert($perm);
            }
        }

        // ── grants ───────────────────────────────────────────────────────

        // _gate_access -> every role
        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, ?, ?
            FROM permission p
            CROSS JOIN role r
            WHERE p.perm_code = ?
            AND NOT EXISTS (
                SELECT 1 FROM role_permission rp
                WHERE rp.perm_id_fk = p.perm_id AND rp.role_id_fk = r.role_id
            )
        ", [$now, $now, $this->accessCode]);

        // _gate_mark -> staff-only (exclude Student=4, Parent=6)
        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, ?, ?
            FROM permission p
            CROSS JOIN role r
            WHERE p.perm_code = ?
            AND r.role_cat_id_fk NOT IN (4, 6)
            AND NOT EXISTS (
                SELECT 1 FROM role_permission rp
                WHERE rp.perm_id_fk = p.perm_id AND rp.role_id_fk = r.role_id
            )
        ", [$now, $now, $this->markCode]);

        // _gate_pass_approve, _gate_reports, _gate_manage_all -> admin-tier roles
        foreach ([$this->passApproveCode, $this->reportsCode, $this->adminCode] as $code) {
            $placeholders = implode(',', array_fill(0, count($this->adminRoles), '?'));
            $db->query("
                INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
                SELECT p.perm_id, r.role_id, ?, ?
                FROM permission p
                CROSS JOIN role r
                WHERE p.perm_code = ?
                AND r.role_name IN ($placeholders)
                AND NOT EXISTS (
                    SELECT 1 FROM role_permission rp
                    WHERE rp.perm_id_fk = p.perm_id AND rp.role_id_fk = r.role_id
                )
            ", array_merge([$now, $now, $code], $this->adminRoles));
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $codes = [$this->accessCode, $this->markCode, $this->passApproveCode, $this->reportsCode, $this->adminCode];
        $placeholders = implode(',', array_fill(0, count($codes), '?'));

        $db->query("
            DELETE rp FROM role_permission rp
            INNER JOIN permission p ON p.perm_id = rp.perm_id_fk
            WHERE p.perm_code IN ($placeholders)
        ", $codes);

        $db->query("DELETE FROM permission WHERE perm_code IN ($placeholders)", $codes);
        $db->query("DELETE FROM modules WHERE module_name = 'Gate Management'");

        $db->query('DROP TABLE IF EXISTS `gate_pass`');
        $db->query('DROP TABLE IF EXISTS `gate_entry_log`');
        $db->query('DROP TABLE IF EXISTS `gate_visitor`');
        $db->query('DROP TABLE IF EXISTS `gate_form_option`');
        $db->query('DROP TABLE IF EXISTS `gate_setting`');
    }
}
