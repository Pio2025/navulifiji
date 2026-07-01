<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChatMessageReactionsTable extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('chat_message_reactions')) return;

        $this->db->query("
            CREATE TABLE `chat_message_reactions` (
                `id`         INT(11)     NOT NULL AUTO_INCREMENT,
                `message_id` INT(11)     NOT NULL,
                `user_id`    INT(11)     NOT NULL,
                `emoji`      VARCHAR(16) NOT NULL,
                `created_at` DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_msg_user` (`message_id`, `user_id`),
                KEY `idx_message_id` (`message_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        $this->forge->dropTable('chat_message_reactions', true);
    }
}
