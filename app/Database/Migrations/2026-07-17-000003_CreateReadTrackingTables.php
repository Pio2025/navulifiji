<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReadTrackingTables extends Migration
{
    public function up(): void
    {
        // Track which users have read which announcements
        $this->db->query("
            CREATE TABLE IF NOT EXISTS announcement_reads (
                ar_id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id        INT UNSIGNED NOT NULL,
                announcement_id INT UNSIGNED NOT NULL,
                read_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_user_ann (user_id, announcement_id),
                KEY idx_ann_id (announcement_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // Track which users have read which notices
        $this->db->query("
            CREATE TABLE IF NOT EXISTS notice_reads (
                nr_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id  INT UNSIGNED NOT NULL,
                notice_id INT UNSIGNED NOT NULL,
                read_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_user_notice (user_id, notice_id),
                KEY idx_notice_id (notice_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP TABLE IF EXISTS announcement_reads');
        $this->db->query('DROP TABLE IF EXISTS notice_reads');
    }
}
