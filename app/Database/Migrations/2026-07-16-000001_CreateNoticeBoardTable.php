<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNoticeBoardTable extends Migration
{
    public function up(): void
    {
        if (!in_array('notice_board', $this->db->listTables())) {
            $this->forge->addField([
                'notice_id'     => ['type' => 'INT', 'auto_increment' => true],
                'sch_id_fk'     => ['type' => 'INT'],
                'posted_by'     => ['type' => 'INT'],
                'title'         => ['type' => 'VARCHAR', 'constraint' => 255],
                'content'       => ['type' => 'TEXT'],
                'priority'      => ['type' => 'ENUM', 'constraint' => ['Normal', 'Important', 'Urgent'], 'default' => 'Normal'],
                'audience'      => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => 'All'],
                'is_pinned'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'expires_at'    => ['type' => 'DATETIME', 'null' => true],
                'notice_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
                'created_at'    => ['type' => 'DATETIME', 'null' => true],
                'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('notice_id');
            $this->forge->addKey('sch_id_fk');
            $this->forge->addKey('posted_by');
            $this->forge->createTable('notice_board', true, ['ENGINE' => 'MyISAM']);
        }

        // Add permissions (skip if already seeded from SQL dump)
        $perms = [
            ['code' => '_post_notice',    'name' => 'Post Notice',       'desc' => 'Create a new notice on the school notice board', 'url' => 'dashboard/notice/store'],
            ['code' => '_edit_notice',    'name' => 'Edit Notice',        'desc' => 'Edit an existing notice',                        'url' => 'dashboard/notice/edit/'],
            ['code' => '_remove_notice',  'name' => 'Remove Notice',      'desc' => 'Delete a notice from the board',                  'url' => 'dashboard/notice/delete'],
            ['code' => '_pin_notice',     'name' => 'Pin Notice',         'desc' => 'Pin or unpin a notice on the board',              'url' => 'dashboard/notice/pin'],
        ];
        foreach ($perms as $p) {
            $exists = $this->db->table('permission')->where('perm_code', $p['code'])->countAllResults();
            if ($exists === 0) {
                $this->db->table('permission')->insert([
                    'module_id_fk'    => 1,
                    'perm_name'       => $p['name'],
                    'perm_desc'       => $p['desc'],
                    'perm_controller' => $p['url'],
                    'perm_code'       => $p['code'],
                    'show_in_nav'     => 0,
                    'perm_status'     => 'Active',
                ]);
            }
        }
    }

    public function down(): void
    {
        foreach (['_post_notice', '_edit_notice', '_remove_notice', '_pin_notice'] as $code) {
            $this->db->table('permission')->where('perm_code', $code)->delete();
        }
        $this->forge->dropTable('notice_board', true);
    }
}
