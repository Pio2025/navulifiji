<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiscussionPostingStatusToUsers extends Migration
{
    public function up()
    {
        $hasColumn = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'users'
              AND COLUMN_NAME  = 'discussion_posting_status'
        ")->getRow()->cnt;

        if (!$hasColumn) {
            $this->db->query("
                ALTER TABLE users
                ADD COLUMN discussion_posting_status VARCHAR(20) NOT NULL DEFAULT 'Active'
            ");
        }
    }

    public function down()
    {
        $hasColumn = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'users'
              AND COLUMN_NAME  = 'discussion_posting_status'
        ")->getRow()->cnt;

        if ($hasColumn) {
            $this->db->query("ALTER TABLE users DROP COLUMN discussion_posting_status");
        }
    }
}
