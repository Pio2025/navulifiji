<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiscussionModerationPermissions extends Migration
{
    private const MODULE_ID = 13;

    private array $moderationCodes = [
        '_edit_class_discussion',
        '_report_class_discussion',
        '_delete_class_discussion',
        '_edit_lesson_discussion',
        '_report_lesson_discussion',
        '_delete_lesson_discussion',
    ];

    private array $moderatorRoles = [
        'Super Admin',
        'School Admin',
        'Principal',
        'Head Master',
        'Assistant Head Master',
        'Vice Principal',
        'Assistant Principal',
    ];

    public function up(): void
    {
        $db  = $this->db;
        $now = date('Y-m-d H:i:s');

        $rows = [
            ['perm_name' => 'Post Class Discussion',   'perm_desc' => 'Post a new message on a classroom discussion',                    'perm_controller' => 'classroom/discussion/post',            'perm_code' => '_post_class_discussion',    'show_in_nav' => 0],
            ['perm_name' => 'View Class Discussion',    'perm_desc' => 'View a classroom discussion feed',                                'perm_controller' => 'classroom/discussion',                  'perm_code' => '_view_class_discussion',    'show_in_nav' => 1],
            ['perm_name' => 'Edit Class Discussion',    'perm_desc' => 'Edit another user\'s classroom discussion post, comment, or reply', 'perm_controller' => 'classroom/discussion/edit/',            'perm_code' => '_edit_class_discussion',    'show_in_nav' => 0],
            ['perm_name' => 'Report Class Discussion',  'perm_desc' => 'Report and review reports on classroom discussion content',        'perm_controller' => 'classroom/discussion/report/',          'perm_code' => '_report_class_discussion',  'show_in_nav' => 0],
            ['perm_name' => 'Delete Class Discussion',  'perm_desc' => 'Delete another user\'s classroom discussion post, comment, or reply', 'perm_controller' => 'classroom/discussion/delete/',        'perm_code' => '_delete_class_discussion',  'show_in_nav' => 0],
            ['perm_name' => 'Post Lesson Discussion',   'perm_desc' => 'Post a new message on a lesson discussion',                       'perm_controller' => 'classroom/lesson/discussion/post',      'perm_code' => '_post_lesson_discussion',   'show_in_nav' => 0],
            ['perm_name' => 'View Lesson Discussion',   'perm_desc' => 'View a lesson discussion feed',                                   'perm_controller' => 'classroom/lesson/discussion',           'perm_code' => '_view_lesson_discussion',   'show_in_nav' => 1],
            ['perm_name' => 'Edit Lesson Discussion',   'perm_desc' => 'Edit another user\'s lesson discussion post, comment, or reply',   'perm_controller' => 'classroom/lesson/discussion/edit/',     'perm_code' => '_edit_lesson_discussion',   'show_in_nav' => 0],
            ['perm_name' => 'Report Lesson Discussion', 'perm_desc' => 'Report and review reports on lesson discussion content',          'perm_controller' => 'classroom/lesson/discussion/report/',   'perm_code' => '_report_lesson_discussion', 'show_in_nav' => 0],
            ['perm_name' => 'Delete Lesson Discussion', 'perm_desc' => 'Delete another user\'s lesson discussion post, comment, or reply', 'perm_controller' => 'classroom/lesson/discussion/delete/',   'perm_code' => '_delete_lesson_discussion', 'show_in_nav' => 0],
        ];

        foreach ($rows as $row) {
            $exists = $db->table('permission')->where('perm_code', $row['perm_code'])->countAllResults();
            if ($exists === 0) {
                $db->table('permission')->insert([
                    'module_id_fk'    => self::MODULE_ID,
                    'perm_name'       => $row['perm_name'],
                    'perm_desc'       => $row['perm_desc'],
                    'perm_controller' => $row['perm_controller'],
                    'perm_code'       => $row['perm_code'],
                    'show_in_nav'     => $row['show_in_nav'],
                    'perm_status'     => 'Active',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
            }
        }

        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $this->moderationCodes));
        $escapedRoles = implode(',', array_map(fn($r) => $db->escape($r), $this->moderatorRoles));

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

        $allCodes = [
            '_post_class_discussion', '_view_class_discussion', '_edit_class_discussion',
            '_report_class_discussion', '_delete_class_discussion',
            '_post_lesson_discussion', '_view_lesson_discussion', '_edit_lesson_discussion',
            '_report_lesson_discussion', '_delete_lesson_discussion',
        ];
        $escapedCodes = implode(',', array_map(fn($c) => $db->escape($c), $allCodes));

        $db->query("
            DELETE rp FROM role_permission rp
            JOIN permission p ON p.perm_id = rp.perm_id_fk
            WHERE p.perm_code IN ({$escapedCodes})
        ");

        $db->table('permission')->whereIn('perm_code', $allCodes)->delete();
    }
}
