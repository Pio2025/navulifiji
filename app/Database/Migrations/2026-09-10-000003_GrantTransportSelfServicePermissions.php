<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Follow-up to CreateTransportationModule: grants the self-service nav
 * permissions to Student ("My Transport Allocation" — the student is the
 * actual applicant on the Ministry form) and Parent ("My Child Transport
 * Allocation" — for a linked child), so the module is reachable from the
 * nav without a manual admin step.
 */
class GrantTransportSelfServicePermissions extends Migration
{
    private array $selfGrants = [
        '_my_transport'       => 'Student',
        '_my_child_transport' => 'Parent',
    ];

    public function up(): void
    {
        $db  = $this->db;
        $now = date('Y-m-d H:i:s');

        foreach ($this->selfGrants as $code => $roleName) {
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

        foreach ($this->selfGrants as $code => $roleName) {
            $escCode = $db->escape($code);
            $escRole = $db->escape($roleName);
            $db->query("
                DELETE rp FROM role_permission rp
                JOIN permission p ON p.perm_id = rp.perm_id_fk
                JOIN role r       ON r.role_id  = rp.role_id_fk
                WHERE p.perm_code = {$escCode} AND r.role_name = {$escRole}
            ");
        }
    }
}
