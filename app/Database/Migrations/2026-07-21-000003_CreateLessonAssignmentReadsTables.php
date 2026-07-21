<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLessonAssignmentReadsTables extends Migration
{
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS lesson_reads (
                lr_id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id    INT UNSIGNED NOT NULL,
                lesson_id  INT UNSIGNED NOT NULL,
                read_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_user_lesson (user_id, lesson_id),
                KEY idx_lesson_id (lesson_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS assignment_reads (
                asr_id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id        INT UNSIGNED NOT NULL,
                assignment_id  INT UNSIGNED NOT NULL,
                read_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_user_assignment (user_id, assignment_id),
                KEY idx_assignment_id (assignment_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP TABLE IF EXISTS lesson_reads');
        $this->db->query('DROP TABLE IF EXISTS assignment_reads');
    }
}
