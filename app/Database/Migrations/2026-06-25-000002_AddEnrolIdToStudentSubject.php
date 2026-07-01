<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEnrolIdToStudentSubject extends Migration
{
    public function up(): void
    {
        // Skip if the column was already added manually.
        if (in_array('enrol_id_fk', $this->db->getFieldNames('student_subject'))) {
            return;
        }

        // Add enrol_id_fk so student subjects can be linked to an enrolment record.
        // Keeps legacy class_id_fk / user_id_fk columns intact for backward compatibility.
        $this->db->query('
            ALTER TABLE student_subject
                ADD COLUMN enrol_id_fk INT NOT NULL DEFAULT 0 AFTER stud_sub_id,
                ADD INDEX  idx_student_subject_enrol (enrol_id_fk)
        ');
    }

    public function down(): void
    {
        $this->db->query('
            ALTER TABLE student_subject
                DROP INDEX  idx_student_subject_enrol,
                DROP COLUMN enrol_id_fk
        ');
    }
}
