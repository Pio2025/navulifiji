<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Transportation module: digitises the Ministry of Education Transport
 * Assistance Application Form (Appendix A) — the annual allocation the
 * Ministry grants to eligible students, funded through to Vodafone's
 * e-ticketing card. One application per student per academic year; most
 * student/school fields are auto-filled from admission+enrolment, only the
 * card number, household income table and transport trip legs are new data
 * captured here before a mimic PDF is generated for physical
 * principal/head signature and submission to the Ministry.
 *
 * Tables use IF NOT EXISTS (mirrors the Conduct module's migration) and
 * ensureTables() is also called from the models for parity with that
 * convention.
 */
class CreateTransportationModule extends Migration
{
    private array $permCodes = [
        '_add_transport',
        '_transport_listing',
        '_edit_transport',
        '_remove_transport',
        '_transport_detail',
        '_generate_transport_form',
        '_my_transport',
        '_my_child_transport',
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

        // ── transport_allocation ─────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `transport_allocation` (
                `allocation_id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `student_id`                 INT UNSIGNED NOT NULL COMMENT 'admission.admission_id',
                `academic_year`               INT UNSIGNED NOT NULL,
                `e_transport_card_number`    VARCHAR(50)  DEFAULT NULL,
                `receiving_social_welfare`   TINYINT(1)   NOT NULL DEFAULT 0,
                `social_welfare_number`      VARCHAR(50)  DEFAULT NULL,
                `to_school_final_destination` VARCHAR(150) DEFAULT NULL,
                `to_school_total_fare`       DECIMAL(8,2) DEFAULT NULL,
                `to_home_final_destination`  VARCHAR(150) DEFAULT NULL,
                `to_home_total_fare`         DECIMAL(8,2) DEFAULT NULL,
                `application_status`         ENUM('Draft','Submitted','Vetted','Approved','Rejected') NOT NULL DEFAULT 'Draft',
                `vetted_by_name`             VARCHAR(150) DEFAULT NULL,
                `vetted_date`                DATE         DEFAULT NULL,
                `principal_signed_date`      DATE         DEFAULT NULL,
                `femis_entry_by`             VARCHAR(150) DEFAULT NULL,
                `femis_entry_date`           DATE         DEFAULT NULL,
                `submitted_by`               INT UNSIGNED DEFAULT NULL COMMENT 'users.user_id',
                `created_at`                 DATETIME     DEFAULT NULL,
                `updated_at`                 DATETIME     DEFAULT NULL,
                PRIMARY KEY (`allocation_id`),
                UNIQUE KEY `uniq_student_year` (`student_id`, `academic_year`),
                KEY `academic_year` (`academic_year`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── transport_allocation_household_member ───────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `transport_allocation_household_member` (
                `household_member_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `allocation_id_fk`     INT UNSIGNED NOT NULL,
                `member_name`          VARCHAR(150) NOT NULL,
                `dob`                  DATE         DEFAULT NULL,
                `phone`                VARCHAR(30)  DEFAULT NULL,
                `relationship`         VARCHAR(50)  DEFAULT NULL,
                `occupation`           VARCHAR(150) DEFAULT NULL,
                `annual_income`        DECIMAL(10,2) DEFAULT NULL,
                `tin_number`           VARCHAR(50)  DEFAULT NULL,
                `evidence_attached`    TINYINT(1)   NOT NULL DEFAULT 0,
                PRIMARY KEY (`household_member_id`),
                KEY `allocation_id_fk` (`allocation_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── transport_allocation_trip ────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `transport_allocation_trip` (
                `trip_id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `allocation_id_fk` INT UNSIGNED NOT NULL,
                `direction`        ENUM('To School','To Home') NOT NULL,
                `trip_order`       TINYINT UNSIGNED NOT NULL DEFAULT 1,
                `boarding_point`   VARCHAR(150) DEFAULT NULL,
                `fare`             DECIMAL(8,2) DEFAULT NULL,
                `mode`             ENUM('Bus','Boat','Carrier','Minibus') DEFAULT NULL,
                PRIMARY KEY (`trip_id`),
                KEY `allocation_id_fk` (`allocation_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── modules ───────────────────────────────────────────────────────
        $mod = $db->table('modules')->where('module_name', 'Transportation')->get()->getRowArray();
        if (!$mod) {
            $db->table('modules')->insert([
                'module_name' => 'Transportation',
                'module_icon' => '<i class="ki-duotone ki-bus fs-2"><span class="path1"></span><span class="path2"></span></i>',
                'module_svg'  => '',
            ]);
        }
        $moduleId = $db->table('modules')->where('module_name', 'Transportation')->get()->getRowArray()['module_id'];

        // ── permission ────────────────────────────────────────────────────
        $existing = $db->table('permission')
            ->whereIn('perm_code', $this->permCodes)
            ->countAllResults();

        if ($existing === 0) {
            $db->table('permission')->insertBatch([
                ['module_id_fk' => $moduleId, 'perm_name' => 'Add Transport Allocation',        'perm_desc' => 'Create a new transport assistance allocation for a student',    'perm_controller' => 'transportation/add',         'perm_code' => '_add_transport',            'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Transport Allocation Listing',     'perm_desc' => 'View the school transport allocation listing',                  'perm_controller' => 'transportation',             'perm_code' => '_transport_listing',        'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Edit Transport Allocation',        'perm_desc' => 'Edit an existing transport allocation',                         'perm_controller' => 'transportation/edit/',       'perm_code' => '_edit_transport',           'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Delete Transport Allocation',      'perm_desc' => 'Remove a transport allocation',                                 'perm_controller' => 'transportation/remove',      'perm_code' => '_remove_transport',         'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'View Transport Allocation Detail', 'perm_desc' => 'View one transport allocation in detail',                       'perm_controller' => 'transportation/detail/',     'perm_code' => '_transport_detail',         'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Generate Transport Assistance Form', 'perm_desc' => 'Generate the printable Ministry Transport Assistance Application Form', 'perm_controller' => 'transportation/form/',     'perm_code' => '_generate_transport_form',  'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'My Transport Allocation',          'perm_desc' => 'View and submit my own transport assistance application',       'perm_controller' => 'transportation/my',          'perm_code' => '_my_transport',             'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'My Child Transport Allocation',    'perm_desc' => 'View and submit a linked child\'s transport assistance application', 'perm_controller' => 'transportation/my',       'perm_code' => '_my_child_transport',       'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ── grant admin roles the management permissions ────────────────────
        $adminCodes   = ['_add_transport', '_transport_listing', '_edit_transport', '_remove_transport', '_transport_detail', '_generate_transport_form'];
        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $adminCodes));
        $escapedRoles = implode(',', array_map(fn($r) => $db->escape($r), $this->adminRoles));

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

        // ── grant Student/Parent roles the self-service permissions (nav visibility) ──
        // The student is the actual applicant on the Ministry form (parent/teacher
        // may fill in on their behalf), so Student needs "My Transport Allocation";
        // Parent needs "My Child Transport Allocation" to reach the same form for a
        // linked child.
        $selfGrants = [
            '_my_transport'       => 'Student',
            '_my_child_transport' => 'Parent',
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

        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $this->permCodes));

        $db->query("
            DELETE rp FROM role_permission rp
            JOIN permission p ON p.perm_id = rp.perm_id_fk
            WHERE p.perm_code IN ({$escapedCodes})
        ");

        $db->table('permission')->whereIn('perm_code', $this->permCodes)->delete();
        $db->table('modules')->where('module_name', 'Transportation')->delete();

        foreach ([
            'transport_allocation_trip',
            'transport_allocation_household_member',
            'transport_allocation',
        ] as $table) {
            $db->query("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
