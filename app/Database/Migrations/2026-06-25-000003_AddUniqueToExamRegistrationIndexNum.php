<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueToExamRegistrationIndexNum extends Migration
{
    public function up(): void
    {
        // Add UNIQUE constraint so the DB itself rejects duplicate index numbers,
        // providing a concurrency-safe final guard beyond the application-level check.
        $this->db->query('
            ALTER TABLE exam_registration
                ADD UNIQUE KEY uk_stud_index_num (stud_index_num)
        ');
    }

    public function down(): void
    {
        $this->db->query('
            ALTER TABLE exam_registration
                DROP INDEX uk_stud_index_num
        ');
    }
}
