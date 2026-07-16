<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GrantConductAppealPermissions extends Migration
{
    // Roles that should be able to review conduct appeals
    private array $targetRoles = [
        'Principal',
        'Assistant Principal',
        'Vice Principal',
        'Head Master',
        'Assistant Head Master',
        'Councilor',
    ];

    // Permissions required to review/process conduct appeals
    private array $targetPerms = [
        '_conduct_listing',
        '_conduct_detail',
        '_conduct_report',
        '_process_conduct_appeal',
    ];

    public function up(): void
    {
        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $roleNames = implode(',', array_map(fn($n) => $db->escape($n), $this->targetRoles));
        $permCodes = implode(',', array_map(fn($c) => $db->escape($c), $this->targetPerms));

        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM permission p
            CROSS JOIN role r
            WHERE p.perm_code IN ({$permCodes})
              AND r.role_name  IN ({$roleNames})
              AND NOT EXISTS (
                  SELECT 1 FROM role_permission x
                  WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
              )
        ");
    }

    public function down(): void
    {
        $db = \Config\Database::connect();

        $roleNames = implode(',', array_map(fn($n) => $db->escape($n), $this->targetRoles));
        $permCodes = implode(',', array_map(fn($c) => $db->escape($c), $this->targetPerms));

        $db->query("
            DELETE rp FROM role_permission rp
            JOIN permission p ON p.perm_id = rp.perm_id_fk
            JOIN role r       ON r.role_id  = rp.role_id_fk
            WHERE p.perm_code IN ({$permCodes})
              AND r.role_name  IN ({$roleNames})
        ");
    }
}
