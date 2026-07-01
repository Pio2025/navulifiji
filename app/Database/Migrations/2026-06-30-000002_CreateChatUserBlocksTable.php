<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChatUserBlocksTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('chat_user_blocks')) return;

        $this->db->query("
            CREATE TABLE `chat_user_blocks` (
                `id`         INT(11)  NOT NULL AUTO_INCREMENT,
                `blocker_id` INT(11)  NOT NULL,
                `blocked_id` INT(11)  NOT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_blocker_blocked` (`blocker_id`, `blocked_id`),
                KEY `idx_blocked_id` (`blocked_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        $this->forge->dropTable('chat_user_blocks', true);
    }
}
