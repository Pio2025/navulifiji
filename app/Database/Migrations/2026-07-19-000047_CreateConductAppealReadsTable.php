<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConductAppealReadsTable extends Migration
{
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS conduct_appeal_reads (
                car_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id    INT UNSIGNED NOT NULL,
                appeal_id  INT UNSIGNED NOT NULL,
                read_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_user_appeal (user_id, appeal_id),
                KEY idx_appeal_id (appeal_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP TABLE IF EXISTS conduct_appeal_reads');
    }
}
