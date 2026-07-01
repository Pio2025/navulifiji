<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterLessonAssignmentFileTypeToVarchar extends Migration
{
    public function up(): void
    {
        $this->db->query("ALTER TABLE `lesson_assignment_file` MODIFY `file_type` VARCHAR(20) NOT NULL DEFAULT ''");
    }

    public function down(): void
    {
        $this->db->query("ALTER TABLE `lesson_assignment_file` MODIFY `file_type` INT NOT NULL DEFAULT 0");
    }
}
