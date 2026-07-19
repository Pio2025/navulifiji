<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventWallReadsTables extends Migration
{
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS event_reads (
                er_id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id    INT UNSIGNED NOT NULL,
                event_id   INT UNSIGNED NOT NULL,
                read_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_user_event (user_id, event_id),
                KEY idx_event_id (event_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS wall_reads (
                wr_id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id        INT UNSIGNED NOT NULL,
                wall_post_id   INT UNSIGNED NOT NULL,
                read_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_user_wall_post (user_id, wall_post_id),
                KEY idx_wall_post_id (wall_post_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP TABLE IF EXISTS event_reads');
        $this->db->query('DROP TABLE IF EXISTS wall_reads');
    }
}
