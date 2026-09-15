<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImportExportPermissions extends Migration
{
    /**
     * module_name => [ [code, name, desc, controller], ... ]
     *
     * module_name values are confirmed against the live `modules` table
     * (php spark verify:modules, run 2026-09-11) — every module referenced
     * here already exists, so down() must never delete a `modules` row.
     * Notably: Role/Permission live under "RBAC", Reference lives under
     * "User", School Category/School Subscription live under "School",
     * Subject Category lives under "Curriculum", and Exam's real module
     * name is "External Exam".
     */
    private array $permsByModule = [
        'Admission' => [
            ['_export_admission', 'Export Admissions', 'Bulk export the admission list', 'admission/export'],
        ],
        'Enrolment' => [
            ['_export_enrolment', 'Export Enrolments', 'Bulk export the enrolment list', 'enrolment/export'],
        ],
        'Classroom' => [
            ['_export_classroom', 'Export Classrooms', 'Bulk export the classroom list', 'classroom/export'],
        ],
        'Conduct' => [
            ['_export_conduct', 'Export Conduct Records', 'Bulk export the conduct incident list', 'conduct/export'],
        ],
        'Doc Manager' => [
            ['_export_doc_manager', 'Export Documents', 'Bulk export the document list', 'doc_manager/export'],
        ],
        'Event' => [
            ['_export_event', 'Export Events', 'Bulk export the event list', 'event/export'],
        ],
        'External Exam' => [
            ['_export_exam', 'Export Exams', 'Bulk export the exam list', 'exam/export'],
        ],
        'Curriculum' => [
            ['_export_subject_category', 'Export Subject Categories', 'Bulk export the subject category list', 'subject-category/export'],
        ],
        'Task' => [
            ['_export_task', 'Export Tasks', 'Bulk export the task list', 'task/export'],
        ],
        'Timetable' => [
            ['_export_timetable', 'Export Timetables', 'Bulk export the timetable list', 'timetable/export'],
        ],
        'Transportation' => [
            ['_export_transportation', 'Export Transportation', 'Bulk export the transportation list', 'transport/export'],
        ],
        'Library' => [
            ['_export_library', 'Export Library', 'Bulk export the library catalogue/loans list', 'library/export'],
        ],
        'Hostel' => [
            ['_export_hostel', 'Export Hostel', 'Bulk export the hostel list', 'hostel/export'],
        ],
        'Gate Management' => [
            ['_export_gate', 'Export Gate Records', 'Bulk export gate pass/access records', 'gate/export'],
        ],
        'Subject' => [
            ['_export_subject', 'Export Subjects', 'Bulk export the global subject catalogue', 'subject/export'],
        ],
        'School' => [
            ['_export_school', 'Export Schools', 'Bulk export the school list', 'school/export'],
            ['_export_school_category', 'Export School Categories', 'Bulk export the school category list', 'school/category/export'],
            ['_export_school_subscription', 'Export School Subscriptions', 'Bulk export the school subscription list', 'school/subscription/export'],
        ],
        'RBAC' => [
            ['_export_role', 'Export Roles', 'Bulk export the role list', 'role/export'],
            ['_export_permission', 'Export Permissions', 'Bulk export the permission list', 'permission/export'],
        ],
        'User' => [
            ['_export_reference', 'Export References', 'Bulk export the reference request list', 'reference/export'],
            ['_export_user', 'Export Users', 'Bulk export the user list', 'user/export'],
            ['_import_user', 'Import Users', 'Bulk create users from an uploaded CSV file', 'user/import'],
        ],
    ];

    private array $adminRoles = [
        'Super Admin',
        'Principal',
        'Assistant Principal',
        'Vice Principal',
        'Head Master',
        'Assistant Head Master',
    ];

    private function allCodes(): array
    {
        $codes = [];
        foreach ($this->permsByModule as $perms) {
            foreach ($perms as $p) {
                $codes[] = $p[0];
            }
        }
        return $codes;
    }

    public function up(): void
    {
        $db  = $this->db;
        $now = date('Y-m-d H:i:s');

        foreach ($this->permsByModule as $moduleName => $perms) {
            $mod = $db->table('modules')->where('module_name', $moduleName)->get()->getRowArray();
            if (!$mod) {
                // Every module_name above was confirmed to already exist against
                // the live database; if this fires, the schema has drifted and
                // needs investigation rather than a silent auto-create.
                throw new \RuntimeException("Expected module '{$moduleName}' not found — aborting migration.");
            }
            $moduleId = $mod['module_id'];

            $codesForModule = array_column($perms, 0);
            $existing = $db->table('permission')
                ->whereIn('perm_code', $codesForModule)
                ->countAllResults();

            if ($existing === 0) {
                $insert = [];
                foreach ($perms as [$code, $name, $desc, $controller]) {
                    $insert[] = [
                        'module_id_fk'    => $moduleId,
                        'perm_name'       => $name,
                        'perm_desc'       => $desc,
                        'perm_controller' => $controller,
                        'perm_code'       => $code,
                        'show_in_nav'     => 0,
                        'perm_status'     => 'Active',
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
                $db->table('permission')->insertBatch($insert);
            }
        }

        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $this->allCodes()));
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
    }

    public function down(): void
    {
        $db = $this->db;

        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $this->allCodes()));

        $db->query("
            DELETE rp FROM role_permission rp
            JOIN permission p ON p.perm_id = rp.perm_id_fk
            WHERE p.perm_code IN ({$escapedCodes})
        ");

        $db->table('permission')->whereIn('perm_code', $this->allCodes())->delete();

        // Every module referenced by this migration already existed before it
        // ran (confirmed against live data) — never delete `modules` rows here.
    }
}
