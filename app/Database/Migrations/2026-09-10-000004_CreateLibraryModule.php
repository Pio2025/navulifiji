<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Library management module: book catalog (with categories), issue/return
 * workflow against school members (students AND staff, both live in
 * `admission` — see AdmissionModel::getAllAdmissionsWithDetails()), and
 * overdue fine tracking. Self-service "My Library" lets Students see their
 * own borrowed books/fines and Parents see their confirmed linked
 * children's, mirroring the Transportation module's pattern. A dedicated
 * "Librarian" role is created (if missing) and granted the management
 * permissions alongside the existing admin roles.
 */
class CreateLibraryModule extends Migration
{
    private array $managementCodes = [
        '_library_category_manage',
        '_add_book',
        '_library_listing',
        '_edit_book',
        '_remove_book',
        '_book_detail',
        '_issue_book',
        '_return_book',
    ];

    private array $selfServiceCodes = [
        '_my_library',
        '_my_child_library',
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

        // ── library_category ────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `library_category` (
                `category_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `sch_id_fk`     INT UNSIGNED NOT NULL,
                `category_name` VARCHAR(100) NOT NULL,
                `description`   VARCHAR(255) DEFAULT NULL,
                `created_at`    DATETIME DEFAULT NULL,
                `updated_at`    DATETIME DEFAULT NULL,
                PRIMARY KEY (`category_id`),
                UNIQUE KEY `uniq_sch_category` (`sch_id_fk`, `category_name`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── library_book ─────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `library_book` (
                `book_id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `sch_id_fk`       INT UNSIGNED NOT NULL,
                `category_id_fk`  INT UNSIGNED DEFAULT NULL,
                `title`           VARCHAR(200) NOT NULL,
                `author`          VARCHAR(150) DEFAULT NULL,
                `isbn`            VARCHAR(50)  DEFAULT NULL,
                `publisher`       VARCHAR(150) DEFAULT NULL,
                `edition`         VARCHAR(50)  DEFAULT NULL,
                `shelf_location`  VARCHAR(100) DEFAULT NULL,
                `total_copies`    INT UNSIGNED NOT NULL DEFAULT 1,
                `available_copies` INT UNSIGNED NOT NULL DEFAULT 1,
                `description`     TEXT DEFAULT NULL,
                `created_at`      DATETIME DEFAULT NULL,
                `updated_at`      DATETIME DEFAULT NULL,
                PRIMARY KEY (`book_id`),
                KEY `sch_id_fk` (`sch_id_fk`),
                KEY `category_id_fk` (`category_id_fk`),
                KEY `isbn` (`isbn`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── library_book_issue ───────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `library_book_issue` (
                `issue_id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `book_id_fk`                INT UNSIGNED NOT NULL,
                `borrower_admission_id_fk`  INT UNSIGNED NOT NULL,
                `sch_id_fk`                 INT UNSIGNED NOT NULL,
                `issue_date`                DATE NOT NULL,
                `due_date`                  DATE NOT NULL,
                `return_date`               DATE DEFAULT NULL,
                `fine_amount`               DECIMAL(8,2) NOT NULL DEFAULT 0.00,
                `fine_paid`                 TINYINT(1) NOT NULL DEFAULT 0,
                `status`                    ENUM('Issued','Returned','Lost') NOT NULL DEFAULT 'Issued',
                `remarks`                   VARCHAR(255) DEFAULT NULL,
                `issued_by`                 INT UNSIGNED DEFAULT NULL,
                `returned_by`               INT UNSIGNED DEFAULT NULL,
                `created_at`                DATETIME DEFAULT NULL,
                `updated_at`                DATETIME DEFAULT NULL,
                PRIMARY KEY (`issue_id`),
                KEY `book_id_fk` (`book_id_fk`),
                KEY `borrower_admission_id_fk` (`borrower_admission_id_fk`),
                KEY `status` (`status`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── Librarian role (plain DB row, same as any admin-created role) ──
        $librarian = $db->table('role')->where('role_name', 'Librarian')->get()->getRowArray();
        if (!$librarian) {
            $db->table('role')->insert([
                'role_cat_id_fk' => 5, // Support Staff — matches School Counselor's tier
                'role_name'      => 'Librarian',
                'role_desc'      => 'The Librarian manages the school\'s book catalog and oversees the borrowing process, including issuing, returning, and tracking overdue fines to maintain an organised and accountable library service.',
                'role_rank'      => 7,
                'created_at'     => date('Y-m-d'),
                'updated_at'     => date('Y-m-d'),
            ]);
        }

        // ── modules ─────────────────────────────────────────────────────
        $mod = $db->table('modules')->where('module_name', 'Library')->get()->getRowArray();
        if (!$mod) {
            $db->table('modules')->insert([
                'module_name' => 'Library',
                'module_icon' => '<i class="ki-duotone ki-book fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>',
                'module_svg'  => '',
            ]);
        }
        $moduleId = $db->table('modules')->where('module_name', 'Library')->get()->getRowArray()['module_id'];

        // ── permission ──────────────────────────────────────────────────
        $allCodes = array_merge($this->managementCodes, $this->selfServiceCodes);
        $existing = $db->table('permission')->whereIn('perm_code', $allCodes)->countAllResults();

        if ($existing === 0) {
            $db->table('permission')->insertBatch([
                ['module_id_fk' => $moduleId, 'perm_name' => 'Book Categories',   'perm_desc' => 'Manage library book categories.',              'perm_controller' => 'library/category',    'perm_code' => '_library_category_manage', 'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Add Book',          'perm_desc' => 'Add a new book to the library catalog.',        'perm_controller' => 'library/add',         'perm_code' => '_add_book',                'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Book Catalog',      'perm_desc' => 'View the library book catalog.',                'perm_controller' => 'library',             'perm_code' => '_library_listing',         'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Edit Book',         'perm_desc' => 'Edit a library book\'s catalog details.',       'perm_controller' => 'library/edit',        'perm_code' => '_edit_book',               'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Remove Book',       'perm_desc' => 'Remove a book from the library catalog.',       'perm_controller' => 'library/remove',      'perm_code' => '_remove_book',             'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Book Detail',       'perm_desc' => 'View a single book\'s detail and issue history.', 'perm_controller' => 'library/detail',    'perm_code' => '_book_detail',             'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Issue / Return',    'perm_desc' => 'Issue books to borrowers and process returns.', 'perm_controller' => 'library/issue',       'perm_code' => '_issue_book',              'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Return Book',       'perm_desc' => 'Mark an issued book as returned.',              'perm_controller' => 'library/issue',       'perm_code' => '_return_book',             'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'My Library',       'perm_desc' => 'View my own borrowed books and fines.',          'perm_controller' => 'library/my',          'perm_code' => '_my_library',              'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => "Children's Library", 'perm_desc' => "View my children's borrowed books and fines.", 'perm_controller' => 'library/my',        'perm_code' => '_my_child_library',        'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // ── grant admin roles + Librarian the management permissions ───────
        $managementRoles = array_merge($this->adminRoles, ['Librarian']);
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
            '_my_library'       => 'Student',
            '_my_child_library' => 'Parent',
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
        $db->table('modules')->where('module_name', 'Library')->delete();
        $db->table('role')->where('role_name', 'Librarian')->delete();

        foreach (['library_book_issue', 'library_book', 'library_category'] as $table) {
            $db->query("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
