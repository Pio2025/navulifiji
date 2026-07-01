<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSubTypeToExamSubject extends Migration
{
    public function up(): void
    {
        $fields = $this->db->getFieldNames('exam_subject');
        if (in_array('sub_type', $fields)) {
            return;
        }

        $this->forge->addColumn('exam_subject', [
            'sub_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Core', 'Optional'],
                'default'    => 'Core',
                'after'      => 'sch_sub_id_fk',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('exam_subject', 'sub_type');
    }
}
