<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentExamTables extends Migration
{
    public function up()
    {
        // student_exam — enrols a student (via enrolment) into an exam for a given year/term
        $this->forge->addField([
            'student_exam_id' => ['type' => 'INT', 'auto_increment' => true],
            'exam_id_fk'      => ['type' => 'INT', 'null' => false],
            'enrol_id_fk'     => ['type' => 'INT', 'null' => false],
            'exam_year'       => ['type' => 'INT', 'null' => false],
            'exam_term'       => ['type' => 'INT', 'null' => false, 'default' => 1],
            'student_exam_status' => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => false, 'default' => 'Active'],
            'created_by_fk'   => ['type' => 'INT', 'null' => true],
            'created_date'    => ['type' => 'DATE', 'null' => true],
            'created_time'    => ['type' => 'INT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('student_exam_id');
        $this->forge->addUniqueKey(['exam_id_fk', 'enrol_id_fk', 'exam_year', 'exam_term'], 'uniq_student_exam');
        $this->forge->addKey('exam_id_fk');
        $this->forge->addKey('enrol_id_fk');
        $this->forge->createTable('student_exam', true);

        // student_exam_mark — per-subject mark for a student_exam record
        $this->forge->addField([
            'mark_id'              => ['type' => 'INT', 'auto_increment' => true],
            'student_exam_id_fk'   => ['type' => 'INT', 'null' => false],
            'stud_sub_id_fk'       => ['type' => 'INT', 'null' => false],
            'mark'                 => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'grade'                => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'teacher_id_fk'        => ['type' => 'INT', 'null' => true],
            'mark_status'          => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => false, 'default' => 'Draft'],
            'created_date'         => ['type' => 'DATE', 'null' => true],
            'created_time'         => ['type' => 'INT', 'null' => true],
            'updated_time'         => ['type' => 'INT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('mark_id');
        $this->forge->addUniqueKey(['student_exam_id_fk', 'stud_sub_id_fk'], 'uniq_mark');
        $this->forge->addKey('student_exam_id_fk');
        $this->forge->addKey('stud_sub_id_fk');
        $this->forge->createTable('student_exam_mark', true);
    }

    public function down()
    {
        $this->forge->dropTable('student_exam_mark', true);
        $this->forge->dropTable('student_exam', true);
    }
}
