<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLessonAssignmentFileTable extends Migration
{
    public function up(): void
    {
        if (!in_array('lesson_assignment_file', $this->db->listTables())) {
            $this->forge->addField([
                'assign_file_id'   => ['type' => 'INT', 'auto_increment' => true],
                'assignment_id_fk' => ['type' => 'INT'],
                'file_src'         => ['type' => 'VARCHAR', 'constraint' => 260],
                'file_type'        => ['type' => 'INT'],
            ]);
            $this->forge->addPrimaryKey('assign_file_id');
            $this->forge->addKey('assignment_id_fk');
            $this->forge->createTable('lesson_assignment_file', true, ['ENGINE' => 'MyISAM']);
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('lesson_assignment_file', true);
    }
}
