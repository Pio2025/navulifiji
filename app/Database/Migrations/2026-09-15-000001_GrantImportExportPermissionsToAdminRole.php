<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * The 'Admin' role (role_id 16, "Navuli Fiji Administrator") is a platform-level
 * role like Super Admin — not tied to any one school's subscription. BaseController::
 * canImportExport() now exempts it from the plan-rank check, but it was missing
 * from AddImportExportPermissions' $adminRoles grant list, so it still lacked the
 * permission codes themselves. This grants the same Import/Export perm_codes that
 * migration granted, to 'Admin' as well.
 */
class GrantImportExportPermissionsToAdminRole extends Migration
{
    private array $codes = [
        '_export_admission',
        '_export_enrolment',
        '_export_classroom',
        '_export_conduct',
        '_export_doc_manager',
        '_export_event',
        '_export_exam',
        '_export_subject_category',
        '_export_task',
        '_export_timetable',
        '_export_transportation',
        '_export_library',
        '_export_hostel',
        '_export_gate',
        '_export_subject',
        '_export_school',
        '_export_school_category',
        '_export_school_subscription',
        '_export_role',
        '_export_permission',
        '_export_reference',
        '_export_user',
        '_import_user',
    ];

    public function up(): void
    {
        $db  = $this->db;
        $now = date('Y-m-d H:i:s');

        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $this->codes));

        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM   permission p
            CROSS  JOIN role r
            WHERE  p.perm_code IN ({$escapedCodes})
              AND  r.role_name = 'Admin'
              AND  NOT EXISTS (
                       SELECT 1 FROM role_permission x
                       WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
                   )
        ");
    }

    public function down(): void
    {
        $db = $this->db;

        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $this->codes));

        $db->query("
            DELETE rp FROM role_permission rp
            JOIN permission p ON p.perm_id = rp.perm_id_fk
            JOIN role r ON r.role_id = rp.role_id_fk
            WHERE p.perm_code IN ({$escapedCodes})
              AND r.role_name = 'Admin'
        ");
    }
}
