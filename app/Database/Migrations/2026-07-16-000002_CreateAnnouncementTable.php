<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnnouncementTable extends Migration
{
    public function up(): void
    {
        if (!in_array('school_announcement', $this->db->listTables())) {
            $this->forge->addField([
                'announcement_id'   => ['type' => 'INT', 'auto_increment' => true],
                'sch_id_fk'         => ['type' => 'INT'],
                'posted_by'         => ['type' => 'INT'],
                'title'             => ['type' => 'VARCHAR', 'constraint' => 255],
                'content'           => ['type' => 'TEXT'],
                'priority'          => ['type' => 'ENUM', 'constraint' => ['Info', 'Important', 'Critical'], 'default' => 'Info'],
                'attachment'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'attachment_type'   => ['type' => 'VARCHAR', 'constraint' => 20,  'null' => true],
                'attachment_name'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'expires_at'        => ['type' => 'DATETIME', 'null' => true],
                'announcement_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
                'created_at'        => ['type' => 'DATETIME', 'null' => true],
                'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('announcement_id');
            $this->forge->addKey('sch_id_fk');
            $this->forge->addKey('posted_by');
            $this->forge->createTable('school_announcement', true, ['ENGINE' => 'MyISAM']);
        }

        $perms = [
            ['code' => '_post_announcement',   'name' => 'Post Announcement',   'desc' => 'Create an official school-wide announcement', 'url' => 'dashboard/announcement/store'],
            ['code' => '_manage_announcement', 'name' => 'Manage Announcements','desc' => 'Edit or delete any school announcement',        'url' => 'dashboard/announcement/'],
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
        foreach (['_post_announcement', '_manage_announcement'] as $code) {
            $this->db->table('permission')->where('perm_code', $code)->delete();
        }
        $this->forge->dropTable('school_announcement', true);
    }
}
