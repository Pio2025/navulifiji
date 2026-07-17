<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSubjectModulePermissions extends Migration
{
    private array $permCodes = [
        '_subject_listing',
        '_add_subject',
        '_edit_subject',
        '_remove_subject',
    ];

    private array $adminRoles = [
        'Super Admin',
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

        // Insert module if missing
        $mod = $db->table('modules')->where('module_name', 'Subject')->get()->getRowArray();
        if (!$mod) {
            $db->table('modules')->insert([
                'module_name' => 'Subject',
                'module_icon' => '<i class="ki-duotone ki-book fs-2"><span class="path1"></span><span class="path2"></span></i>',
                'module_svg'  => '',
            ]);
        }
        $moduleId = $db->table('modules')->where('module_name', 'Subject')->get()->getRowArray()['module_id'];

        // Insert permissions if missing
        $existing = $db->table('permission')
            ->whereIn('perm_code', $this->permCodes)
            ->countAllResults();

        if ($existing === 0) {
            $db->table('permission')->insertBatch([
                ['module_id_fk' => $moduleId, 'perm_name' => 'Subject Listing',  'perm_desc' => 'View the global subject catalogue',       'perm_controller' => 'subject',          'perm_code' => '_subject_listing', 'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Add Subject',      'perm_desc' => 'Add a new subject to the catalogue',       'perm_controller' => 'subject/add',      'perm_code' => '_add_subject',     'show_in_nav' => 1, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Edit Subject',     'perm_desc' => 'Edit an existing subject',                 'perm_controller' => 'subject/edit/',    'perm_code' => '_edit_subject',    'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
                ['module_id_fk' => $moduleId, 'perm_name' => 'Delete Subject',   'perm_desc' => 'Remove a subject from the catalogue',      'perm_controller' => 'subject/remove',   'perm_code' => '_remove_subject',  'show_in_nav' => 0, 'perm_status' => 'Active', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // Grant to admin roles
        $escapedCodes  = implode(',', array_map(fn($c) => $db->escape($c), $this->permCodes));
        $escapedRoles  = implode(',', array_map(fn($r) => $db->escape($r), $this->adminRoles));

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
        $db->table('modules')->where('module_name', 'Subject')->delete();
    }
}
