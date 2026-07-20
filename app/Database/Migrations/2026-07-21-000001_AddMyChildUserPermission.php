<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMyChildUserPermission extends Migration
{
    private string $permCode = '_my_child';

    private array $grantRoles = [
        'Parent',
    ];

    public function up(): void
    {
        $db  = $this->db;
        $now = date('Y-m-d H:i:s');

        // The 'User' module already exists in every environment; only the
        // permission row itself may be missing.
        $mod = $db->table('modules')->where('module_name', 'User')->get()->getRowArray();
        if (!$mod) {
            $db->table('modules')->insert([
                'module_name' => 'User',
                'module_icon' => '<i class="ki-duotone ki-user fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
                'module_svg'  => '',
            ]);
        }
        $moduleId = $db->table('modules')->where('module_name', 'User')->get()->getRowArray()['module_id'];

        $existing = $db->table('permission')->where('perm_code', $this->permCode)->countAllResults();

        if ($existing === 0) {
            $db->table('permission')->insert([
                'module_id_fk'    => $moduleId,
                'perm_name'       => 'My Child',
                'perm_desc'       => 'View the list of children linked to my account',
                'perm_controller' => 'user/child/my',
                'perm_code'       => $this->permCode,
                'show_in_nav'     => 1,
                'perm_status'     => 'Active',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        $escapedRoles = implode(',', array_map(fn($r) => $db->escape($r), $this->grantRoles));

        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM   permission p
            CROSS  JOIN role r
            WHERE  p.perm_code = '{$this->permCode}'
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

        $db->query("
            DELETE rp FROM role_permission rp
            JOIN permission p ON p.perm_id = rp.perm_id_fk
            WHERE p.perm_code = '{$this->permCode}'
        ");

        $db->table('permission')->where('perm_code', $this->permCode)->delete();
    }
}
