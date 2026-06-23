<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChatTables extends Migration
{
    private array $tables = [
        'chat_conversations',
        'chat_participants',
        'chat_messages',
        'chat_message_files',
        'chat_message_deletions',
    ];

    public function up(): void
    {
        // ── chat_conversations ────────────────────────────────────────────────
        if (! $this->db->tableExists('chat_conversations')) {
            $this->db->query("
                CREATE TABLE `chat_conversations` (
                    `id`         INT(11)                NOT NULL AUTO_INCREMENT,
                    `type`       ENUM('direct','group') NOT NULL DEFAULT 'direct',
                    `name`       VARCHAR(255)           DEFAULT NULL,
                    `created_by` INT(11)                NOT NULL,
                    `created_at` DATETIME               NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` DATETIME               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `idx_created_by` (`created_by`),
                    KEY `idx_updated_at` (`updated_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        // ── chat_participants ─────────────────────────────────────────────────
        if (! $this->db->tableExists('chat_participants')) {
            $this->db->query("
                CREATE TABLE `chat_participants` (
                    `id`              INT(11)  NOT NULL AUTO_INCREMENT,
                    `conversation_id` INT(11)  NOT NULL,
                    `user_id`         INT(11)  NOT NULL,
                    `joined_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `last_read_at`    DATETIME DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `unique_participant` (`conversation_id`, `user_id`),
                    KEY `idx_user_id` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        // ── chat_messages ─────────────────────────────────────────────────────
        // NOTE: 'call' type is included for call-log messages saved by ChatController::callEvent()
        if (! $this->db->tableExists('chat_messages')) {
            $this->db->query("
                CREATE TABLE `chat_messages` (
                    `id`              INT(11)                            NOT NULL AUTO_INCREMENT,
                    `conversation_id` INT(11)                            NOT NULL,
                    `sender_id`       INT(11)                            NOT NULL,
                    `message_type`    ENUM('text','image','file','call') NOT NULL DEFAULT 'text',
                    `content`         TEXT                               DEFAULT NULL,
                    `deleted_at`      DATETIME                           DEFAULT NULL,
                    `created_at`      DATETIME                           NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `idx_conversation_created` (`conversation_id`, `created_at`),
                    KEY `idx_sender_id` (`sender_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        // ── chat_message_files ────────────────────────────────────────────────
        if (! $this->db->tableExists('chat_message_files')) {
            $this->db->query("
                CREATE TABLE `chat_message_files` (
                    `id`            INT(11)      NOT NULL AUTO_INCREMENT,
                    `message_id`    INT(11)      NOT NULL,
                    `original_name` VARCHAR(255) NOT NULL,
                    `stored_name`   VARCHAR(255) NOT NULL,
                    `file_path`     VARCHAR(500) NOT NULL,
                    `file_type`     VARCHAR(100) NOT NULL,
                    `file_size`     INT(11)      NOT NULL DEFAULT 0,
                    `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `idx_message_id` (`message_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        // ── chat_message_deletions ────────────────────────────────────────────
        // Tracks per-user soft-deletes ("remove for me" feature)
        if (! $this->db->tableExists('chat_message_deletions')) {
            $this->db->query("
                CREATE TABLE `chat_message_deletions` (
                    `id`         INT(11)  NOT NULL AUTO_INCREMENT,
                    `message_id` INT(11)  NOT NULL,
                    `user_id`    INT(11)  NOT NULL,
                    `deleted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `uq_msg_user` (`message_id`, `user_id`),
                    KEY `idx_message_id` (`message_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->tables) as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
