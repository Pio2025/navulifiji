<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConductAppealFilesTable extends Migration
{
    public function up(): void
    {
        if (!in_array('conduct_appeal_files', $this->db->listTables())) {
            $this->forge->addField([
                'appeal_file_id' => ['type' => 'INT', 'auto_increment' => true],
                'appeal_id'      => ['type' => 'INT', 'null' => true],
                'file_src'       => ['type' => 'VARCHAR', 'constraint' => 255],
                'file_type'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            ]);
            $this->forge->addPrimaryKey('appeal_file_id');
            $this->forge->addKey('appeal_id');
            $this->forge->createTable('conduct_appeal_files', true, ['ENGINE' => 'MyISAM']);
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('conduct_appeal_files', true);
    }
}
