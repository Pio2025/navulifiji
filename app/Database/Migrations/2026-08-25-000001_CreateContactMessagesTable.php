<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContactMessagesTable extends Migration
{
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS contact_message (
                id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name          VARCHAR(150) NOT NULL,
                email         VARCHAR(255) NOT NULL,
                phone         VARCHAR(50) NULL,
                school_name   VARCHAR(255) NULL,
                subject       VARCHAR(255) NULL,
                message       TEXT NOT NULL,
                ip_address    VARCHAR(45) NULL,
                user_agent    VARCHAR(255) NULL,
                status        VARCHAR(20) NOT NULL DEFAULT 'new',
                date          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                KEY idx_status (status)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP TABLE IF EXISTS contact_message');
    }
}
