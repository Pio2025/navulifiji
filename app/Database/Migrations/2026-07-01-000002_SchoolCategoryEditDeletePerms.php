<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SchoolCategoryEditDeletePerms extends Migration
{
    public function up()
    {
        $existing = $this->db->table('permission')
            ->whereIn('perm_code', ['_edit_school_category', '_remove_school_category'])
            ->countAllResults();

        if ($existing === 0) {
            $now = date('Y-m-d');
            $this->db->table('permission')->insertBatch([
                [
                    'module_id_fk'    => 4,
                    'perm_name'       => 'Edit School Category',
                    'perm_desc'       => 'Allows user to edit a school category and its configuration',
                    'perm_controller' => 'school/category/edit/',
                    'perm_code'       => '_edit_school_category',
                    'show_in_nav'     => 0,
                    'perm_status'     => 'Active',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'module_id_fk'    => 4,
                    'perm_name'       => 'Remove School Category',
                    'perm_desc'       => 'Allows user to delete a school category (restricted if schools reference it)',
                    'perm_controller' => 'school/category/remove',
                    'perm_code'       => '_remove_school_category',
                    'show_in_nav'     => 0,
                    'perm_status'     => 'Active',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
            ]);

            // Fetch the new perm_ids
            $editPerm = $this->db->table('permission')
                ->where('perm_code', '_edit_school_category')
                ->get()->getRowArray();
            $removePerm = $this->db->table('permission')
                ->where('perm_code', '_remove_school_category')
                ->get()->getRowArray();

            if ($editPerm && $removePerm) {
                $grantRoles = [1, 16]; // Super Admin, Admin
                $inserts = [];
                foreach ($grantRoles as $roleId) {
                    $inserts[] = ['role_id_fk' => $roleId, 'perm_id_fk' => $editPerm['perm_id']];
                    $inserts[] = ['role_id_fk' => $roleId, 'perm_id_fk' => $removePerm['perm_id']];
                }
                $this->db->table('role_permission')->insertBatch($inserts);
            }
        }
    }

    public function down()
    {
        $perms = $this->db->table('permission')
            ->whereIn('perm_code', ['_edit_school_category', '_remove_school_category'])
            ->get()->getResultArray();

        foreach ($perms as $p) {
            $this->db->table('role_permission')->where('perm_id_fk', $p['perm_id'])->delete();
        }

        $this->db->table('permission')
            ->whereIn('perm_code', ['_edit_school_category', '_remove_school_category'])
            ->delete();
    }
}
