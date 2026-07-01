<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExamSubjectTable extends Migration
{
    public function up(): void
    {
        if (in_array('exam_subject', $this->db->listTables())) {
            return;
        }

        $this->forge->addField([
            'exam_sub_id'    => ['type' => 'INT',  'auto_increment' => true],
            'exam_reg_id_fk' => ['type' => 'INT',  'unsigned' => false, 'null' => false],
            'sch_sub_id_fk'  => ['type' => 'INT',  'unsigned' => false, 'null' => false],
            'sub_type'       => [
                'type'       => 'ENUM',
                'constraint' => ['Core', 'Optional'],
                'default'    => 'Core',
            ],
        ]);
        $this->forge->addPrimaryKey('exam_sub_id');
        $this->forge->addKey(['exam_reg_id_fk', 'sch_sub_id_fk']);
        $this->forge->createTable('exam_subject', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('exam_subject', true);
    }
}
