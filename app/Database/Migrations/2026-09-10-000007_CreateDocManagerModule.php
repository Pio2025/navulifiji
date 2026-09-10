<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Doc Manager module: a personal-document aggregator that pulls a user's
 * files from across the system (personal uploads, generated references,
 * conduct incident/appeal files, attendance files, medical files, and
 * assignment submissions) into one place, grouped by file type, with
 * view/download/print and both in-app and public-link sharing.
 *
 * Only two tables are new here — `doc_manager_file` (personal uploads) and
 * `doc_manager_share` (both share types, keyed generically by
 * source_type + source_file_id so it can point at a row in ANY of the six
 * source file tables). The cross-table aggregation itself is done at query
 * time by App\Libraries\DocManagerAggregator, not by a table.
 *
 * `_doc_manager_access` is granted to literally every role (no role_cat
 * exclusion) since every user — student, parent, or staff — has documents
 * of their own to view. `_doc_manager_manage_others` is admin-tier only,
 * for the cross-user lookup/oversight view.
 */
class CreateDocManagerModule extends Migration
{
    private string $accessCode = '_doc_manager_access';

    private string $adminCode = '_doc_manager_manage_others';

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

        // ── doc_manager_file (personal uploads) ────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `doc_manager_file` (
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
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── doc_manager_share (in-app share + public link) ─────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `doc_manager_share` (
                `share_id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `source_type`             VARCHAR(30) NOT NULL,
                `source_file_id`          INT UNSIGNED NOT NULL,
                `owner_user_id_fk`        INT UNSIGNED NOT NULL,
                `shared_by_user_id_fk`    INT UNSIGNED NOT NULL,
                `share_type`              ENUM('user','link') NOT NULL,
                `shared_with_user_id_fk`  INT UNSIGNED DEFAULT NULL,
                `token`                   VARCHAR(64) DEFAULT NULL,
                `can_download`            TINYINT(1) NOT NULL DEFAULT 1,
                `expires_at`              DATETIME DEFAULT NULL,
                `revoked_at`              DATETIME DEFAULT NULL,
                `created_at`              DATETIME DEFAULT NULL,
                `updated_at`              DATETIME DEFAULT NULL,
                PRIMARY KEY (`share_id`),
                UNIQUE KEY `token` (`token`),
                KEY `source` (`source_type`, `source_file_id`),
                KEY `owner_user_id_fk` (`owner_user_id_fk`),
                KEY `shared_with_user_id_fk` (`shared_with_user_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── modules ─────────────────────────────────────────────────────
        $mod = $db->table('modules')->where('module_name', 'Doc Manager')->get()->getRowArray();
        if (!$mod) {
            $db->table('modules')->insert([
                'module_name' => 'Doc Manager',
                'module_icon' => '<i class="ki-duotone ki-file fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
                'module_svg'  => '',
            ]);
        }
        $moduleId = $db->table('modules')->where('module_name', 'Doc Manager')->get()->getRowArray()['module_id'];

        // ── permission ──────────────────────────────────────────────────
        $allCodes = [$this->accessCode, $this->adminCode];
        $existing = $db->table('permission')->whereIn('perm_code', $allCodes)->countAllResults();

        if ($existing === 0) {
            $db->table('permission')->insertBatch([
                ['module_id_fk' => $moduleId, 'perm_name' => 'Doc Manager',        'perm_desc' => 'View, upload, download, print, and share my documents from across the system in one place.', 'perm_controller' => 'doc-manager',        'perm_code' => $this->accessCode, 'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Doc Manager Lookup', 'perm_desc' => 'Look up and view any user\'s documents for oversight purposes.',                                   'perm_controller' => 'doc-manager/lookup', 'perm_code' => $this->adminCode,  'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ── grant _doc_manager_access to literally every role ──────────────
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

        // ── grant _doc_manager_manage_others to admin-tier roles only ──────
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
        $db->table('modules')->where('module_name', 'Doc Manager')->delete();

        foreach (['doc_manager_share', 'doc_manager_file'] as $table) {
            $db->query("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
