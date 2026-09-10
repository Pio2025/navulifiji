<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Hostel management module: hostels (boarding houses) containing rooms with
 * a fixed bed capacity, a room-level allocation workflow for boarders (both
 * students and staff live in `admission` — see
 * AdmissionModel::getAllAdmissionsWithDetails()), boarding fee tracking per
 * allocation, and a visitor/leave log against each stay. Self-service "My
 * Hostel" lets Students see their own room/fee/visitor/leave and Parents see
 * their confirmed linked children's, mirroring the Library/Transportation
 * self-service pattern. A dedicated "Warden" role is created (if missing)
 * and granted the management permissions alongside the existing admin roles.
 */
class CreateHostelModule extends Migration
{
    private array $managementCodes = [
        '_hostel_manage',
        '_hostel_detail',
        '_hostel_room_manage',
        '_hostel_allocate',
        '_hostel_allocation_detail',
        '_hostel_visitor_log',
        '_hostel_leave_manage',
    ];

    private array $selfServiceCodes = [
        '_my_hostel',
        '_my_child_hostel',
    ];

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

        // ── hostel ──────────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `hostel` (
                `hostel_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `sch_id_fk`   INT UNSIGNED NOT NULL,
                `hostel_name` VARCHAR(150) NOT NULL,
                `hostel_type` ENUM('Boys','Girls','Mixed') NOT NULL DEFAULT 'Mixed',
                `address`     VARCHAR(255) DEFAULT NULL,
                `description` VARCHAR(255) DEFAULT NULL,
                `created_at`  DATETIME DEFAULT NULL,
                `updated_at`  DATETIME DEFAULT NULL,
                PRIMARY KEY (`hostel_id`),
                UNIQUE KEY `uniq_sch_hostel` (`sch_id_fk`, `hostel_name`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── hostel_room ─────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `hostel_room` (
                `room_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `hostel_id_fk` INT UNSIGNED NOT NULL,
                `sch_id_fk`   INT UNSIGNED NOT NULL,
                `room_number` VARCHAR(20) NOT NULL,
                `capacity`    INT UNSIGNED NOT NULL DEFAULT 1,
                `description` VARCHAR(255) DEFAULT NULL,
                `created_at`  DATETIME DEFAULT NULL,
                `updated_at`  DATETIME DEFAULT NULL,
                PRIMARY KEY (`room_id`),
                UNIQUE KEY `uniq_hostel_room` (`hostel_id_fk`, `room_number`),
                KEY `sch_id_fk` (`sch_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── hostel_allocation ───────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `hostel_allocation` (
                `allocation_id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `room_id_fk`                INT UNSIGNED NOT NULL,
                `hostel_id_fk`              INT UNSIGNED NOT NULL,
                `boarder_admission_id_fk`   INT UNSIGNED NOT NULL,
                `sch_id_fk`                 INT UNSIGNED NOT NULL,
                `check_in_date`             DATE NOT NULL,
                `check_out_date`            DATE DEFAULT NULL,
                `status`                    ENUM('Active','Vacated') NOT NULL DEFAULT 'Active',
                `fee_amount`                DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `fee_paid`                  TINYINT(1) NOT NULL DEFAULT 0,
                `remarks`                   VARCHAR(255) DEFAULT NULL,
                `allocated_by`              INT UNSIGNED DEFAULT NULL,
                `vacated_by`                INT UNSIGNED DEFAULT NULL,
                `created_at`                DATETIME DEFAULT NULL,
                `updated_at`                DATETIME DEFAULT NULL,
                PRIMARY KEY (`allocation_id`),
                KEY `room_id_fk` (`room_id_fk`),
                KEY `boarder_admission_id_fk` (`boarder_admission_id_fk`),
                KEY `status` (`status`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── hostel_visitor_log ──────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `hostel_visitor_log` (
                `visitor_log_id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `allocation_id_fk`         INT UNSIGNED NOT NULL,
                `boarder_admission_id_fk`  INT UNSIGNED NOT NULL,
                `sch_id_fk`                INT UNSIGNED NOT NULL,
                `visitor_name`             VARCHAR(150) NOT NULL,
                `relationship`             VARCHAR(100) DEFAULT NULL,
                `visit_date`               DATE NOT NULL,
                `time_in`                  TIME DEFAULT NULL,
                `time_out`                 TIME DEFAULT NULL,
                `purpose`                  VARCHAR(255) DEFAULT NULL,
                `recorded_by`              INT UNSIGNED DEFAULT NULL,
                `created_at`               DATETIME DEFAULT NULL,
                `updated_at`               DATETIME DEFAULT NULL,
                PRIMARY KEY (`visitor_log_id`),
                KEY `allocation_id_fk` (`allocation_id_fk`),
                KEY `boarder_admission_id_fk` (`boarder_admission_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── hostel_leave ────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `hostel_leave` (
                `leave_id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `allocation_id_fk`         INT UNSIGNED NOT NULL,
                `boarder_admission_id_fk`  INT UNSIGNED NOT NULL,
                `sch_id_fk`                INT UNSIGNED NOT NULL,
                `from_date`                DATE NOT NULL,
                `to_date`                  DATE NOT NULL,
                `reason`                   VARCHAR(255) DEFAULT NULL,
                `status`                   ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
                `decided_by`               INT UNSIGNED DEFAULT NULL,
                `remarks`                  VARCHAR(255) DEFAULT NULL,
                `created_at`               DATETIME DEFAULT NULL,
                `updated_at`               DATETIME DEFAULT NULL,
                PRIMARY KEY (`leave_id`),
                KEY `allocation_id_fk` (`allocation_id_fk`),
                KEY `boarder_admission_id_fk` (`boarder_admission_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── Warden role (plain DB row, same as any admin-created role) ────
        $warden = $db->table('role')->where('role_name', 'Warden')->get()->getRowArray();
        if (!$warden) {
            $db->table('role')->insert([
                'role_cat_id_fk' => 5, // Support Staff — matches Librarian's tier
                'role_name'      => 'Warden',
                'role_desc'      => 'The Warden manages the school\'s boarding houses, allocating students to rooms, tracking boarding fees, and logging visitors and leave requests to keep hostel life organised and accountable.',
                'role_rank'      => 8,
                'created_at'     => date('Y-m-d'),
                'updated_at'     => date('Y-m-d'),
            ]);
        }

        // ── modules ─────────────────────────────────────────────────────
        $mod = $db->table('modules')->where('module_name', 'Hostel')->get()->getRowArray();
        if (!$mod) {
            $db->table('modules')->insert([
                'module_name' => 'Hostel',
                'module_icon' => '<i class="ki-duotone ki-home-3 fs-2"><span class="path1"></span><span class="path2"></span></i>',
                'module_svg'  => '',
            ]);
        }
        $moduleId = $db->table('modules')->where('module_name', 'Hostel')->get()->getRowArray()['module_id'];

        // ── permission ──────────────────────────────────────────────────
        $allCodes = array_merge($this->managementCodes, $this->selfServiceCodes);
        $existing = $db->table('permission')->whereIn('perm_code', $allCodes)->countAllResults();

        if ($existing === 0) {
            $db->table('permission')->insertBatch([
                ['module_id_fk' => $moduleId, 'perm_name' => 'Hostels',              'perm_desc' => 'Manage boarding houses (hostels).',                    'perm_controller' => 'hostel',                    'perm_code' => '_hostel_manage',            'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Hostel Detail',        'perm_desc' => 'View a hostel\'s rooms and occupancy.',                'perm_controller' => 'hostel/detail',             'perm_code' => '_hostel_detail',             'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Room Management',      'perm_desc' => 'Add, edit and remove rooms within a hostel.',          'perm_controller' => 'hostel/room',               'perm_code' => '_hostel_room_manage',        'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Room Allocation',      'perm_desc' => 'Allocate and vacate boarders in hostel rooms.',        'perm_controller' => 'hostel/allocation',         'perm_code' => '_hostel_allocate',           'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Allocation Detail',    'perm_desc' => 'View a boarder\'s stay, fee, visitor and leave history.', 'perm_controller' => 'hostel/allocation/detail', 'perm_code' => '_hostel_allocation_detail',  'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Visitor Log',         'perm_desc' => 'Record visitor entries for boarders.',                  'perm_controller' => 'hostel/allocation/visitor', 'perm_code' => '_hostel_visitor_log',        'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Leave Management',     'perm_desc' => 'Record and approve/reject boarder leave requests.',    'perm_controller' => 'hostel/allocation/leave',   'perm_code' => '_hostel_leave_manage',       'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'My Hostel',           'perm_desc' => 'View my own hostel room, fees, visitors and leave.',    'perm_controller' => 'hostel/my',                 'perm_code' => '_my_hostel',                 'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => "Child's Hostel",      'perm_desc' => "View my children's hostel room, fees, visitors and leave.", 'perm_controller' => 'hostel/my',            'perm_code' => '_my_child_hostel',           'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ── grant admin roles + Warden the management permissions ─────────
        $managementRoles = array_merge($this->adminRoles, ['Warden']);
        $escapedCodes    = implode(',', array_map(fn($c) => $db->escape($c), $this->managementCodes));
        $escapedRoles    = implode(',', array_map(fn($r) => $db->escape($r), $managementRoles));

        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM   permission p
            CROSS  JOIN role r
            WHERE  p.perm_code IN ({$escapedCodes})
              AND  r.role_name  IN ({$escapedRoles})
              AND  NOT EXISTS (
                       SELECT 1 FROM role_permission x
                       WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
                   )
        ");

        // ── grant Student/Parent the self-service permissions ─────────────
        $selfGrants = [
            '_my_hostel'       => 'Student',
            '_my_child_hostel' => 'Parent',
        ];
        foreach ($selfGrants as $code => $roleName) {
            $escCode = $db->escape($code);
            $escRole = $db->escape($roleName);
            $db->query("
                INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
                SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
                FROM   permission p
                CROSS  JOIN role r
                WHERE  p.perm_code = {$escCode}
                  AND  r.role_name = {$escRole}
                  AND  NOT EXISTS (
                           SELECT 1 FROM role_permission x
                           WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
                       )
            ");
        }
    }

    public function down(): void
    {
        $db = $this->db;

        $allCodes     = array_merge($this->managementCodes, $this->selfServiceCodes);
        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $allCodes));

        $db->query("DELETE rp FROM role_permission rp JOIN permission p ON p.perm_id = rp.perm_id_fk WHERE p.perm_code IN ({$escapedCodes})");
        $db->table('permission')->whereIn('perm_code', $allCodes)->delete();
        $db->table('modules')->where('module_name', 'Hostel')->delete();
        $db->table('role')->where('role_name', 'Warden')->delete();

        foreach (['hostel_leave', 'hostel_visitor_log', 'hostel_allocation', 'hostel_room', 'hostel'] as $table) {
            $db->query("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
