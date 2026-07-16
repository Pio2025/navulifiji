<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserLogTypeAndStatus extends Migration
{
    public function up()
    {
        // Add log_type column if not present
        $hasType = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'user_log'
              AND COLUMN_NAME  = 'log_type'
        ")->getRow()->cnt;

        if (!$hasType) {
            $this->db->query("
                ALTER TABLE user_log
                ADD COLUMN log_type ENUM('Activity','Alert') NOT NULL DEFAULT 'Activity'
                AFTER log_theme
            ");
        }

        // Add log_status column if not present
        $hasStatus = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'user_log'
              AND COLUMN_NAME  = 'log_status'
        ")->getRow()->cnt;

        if (!$hasStatus) {
            $this->db->query("
                ALTER TABLE user_log
                ADD COLUMN log_status ENUM('Unread','Read') NOT NULL DEFAULT 'Unread'
                AFTER log_type
            ");
        }

        // Mark all existing rows as Read — they are historical, not genuinely "new"
        $this->db->query("UPDATE user_log SET log_status = 'Read' WHERE log_status = 'Unread'");
    }

    public function down()
    {
        $hasType = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'user_log'
              AND COLUMN_NAME  = 'log_type'
        ")->getRow()->cnt;

        if ($hasType) {
            $this->db->query("ALTER TABLE user_log DROP COLUMN log_type");
        }

        $hasStatus = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'user_log'
              AND COLUMN_NAME  = 'log_status'
        ")->getRow()->cnt;

        if ($hasStatus) {
            $this->db->query("ALTER TABLE user_log DROP COLUMN log_status");
        }
    }
}
