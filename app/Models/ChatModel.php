<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // ------------------------------------------------------------------ Conversations

    public function getOrCreateDirectConversation(int $userId1, int $userId2): int
    {
        $row = $this->db->query("
            SELECT cp1.conversation_id
            FROM chat_participants cp1
            INNER JOIN chat_participants cp2
                   ON cp2.conversation_id = cp1.conversation_id
                  AND cp2.user_id = ?
            INNER JOIN chat_conversations cc
                   ON cc.id = cp1.conversation_id
                  AND cc.type = 'direct'
            WHERE cp1.user_id = ?
            LIMIT 1
        ", [$userId2, $userId1])->getRow();

        if ($row) {
            return (int) $row->conversation_id;
        }

        $this->db->query(
            "INSERT INTO chat_conversations (type, created_by) VALUES ('direct', ?)",
            [$userId1]
        );
        $conversationId = $this->db->insertID();

        $this->db->query(
            "INSERT INTO chat_participants (conversation_id, user_id) VALUES (?, ?), (?, ?)",
            [$conversationId, $userId1, $conversationId, $userId2]
        );

        return $conversationId;
    }

    public function getConversations(int $userId): array
    {
        return $this->db->query("
            SELECT
                cc.id              AS conversation_id,
                cc.type,
                cc.name            AS group_name,
                u.user_id          AS other_user_id,
                u.fname,
                u.lname,
                u.profile_photo,
                u.online_status,
                lm.content         AS last_message,
                lm.message_type    AS last_message_type,
                lm.created_at      AS last_message_at,
                (
                    SELECT COUNT(*)
                    FROM   chat_messages m2
                    WHERE  m2.conversation_id = cc.id
                      AND  m2.sender_id != ?
                      AND  m2.deleted_at IS NULL
                      AND  (cp.last_read_at IS NULL OR m2.created_at > cp.last_read_at)
                ) AS unread_count
            FROM chat_conversations cc
            INNER JOIN chat_participants cp
                   ON  cp.conversation_id = cc.id
                  AND  cp.user_id = ?
            LEFT JOIN chat_participants cp2
                   ON  cp2.conversation_id = cc.id
                  AND  cp2.user_id != ?
                  AND  cc.type = 'direct'
            LEFT JOIN users u ON u.user_id = cp2.user_id
            LEFT JOIN chat_messages lm
                   ON  lm.id = (
                           SELECT id FROM chat_messages
                           WHERE  conversation_id = cc.id
                             AND  deleted_at IS NULL
                           ORDER  BY created_at DESC
                           LIMIT  1
                       )
            ORDER BY COALESCE(lm.created_at, cc.created_at) DESC
        ", [$userId, $userId, $userId])->getResultArray();
    }

    // ------------------------------------------------------------------ Messages

    public function getMessages(int $conversationId, int $userId, int $page = 1, int $perPage = 30): array
    {
        $offset = ($page - 1) * $perPage;

        $rows = $this->db->query("
            SELECT
                m.id, m.conversation_id, m.sender_id, m.message_type, m.content, m.created_at,
                CASE WHEN m.deleted_at IS NOT NULL THEN 1 ELSE 0 END AS deleted_for_everyone,
                u.fname, u.lname, u.profile_photo
            FROM   chat_messages m
            INNER JOIN users u ON u.user_id = m.sender_id
            LEFT  JOIN chat_message_deletions cmd
                   ON  cmd.message_id = m.id AND cmd.user_id = ?
            WHERE  m.conversation_id = ?
              AND  cmd.id IS NULL
            ORDER  BY m.created_at DESC
            LIMIT  ? OFFSET ?
        ", [$userId, $conversationId, $perPage, $offset])->getResultArray();

        foreach ($rows as &$row) {
            if ((int) $row['deleted_for_everyone']) {
                $row['message_type'] = 'deleted';
                $row['content']      = null;
                $row['files']        = [];
            } else {
                $row['files'] = ($row['message_type'] !== 'text')
                    ? $this->getMessageFiles((int) $row['id'])
                    : [];
            }
        }

        return array_reverse($rows);
    }

    public function saveMessage(int $conversationId, int $senderId, string $type, ?string $content): int
    {
        $this->db->query(
            "INSERT INTO chat_messages (conversation_id, sender_id, message_type, content) VALUES (?, ?, ?, ?)",
            [$conversationId, $senderId, $type, $content]
        );
        $messageId = $this->db->insertID();

        $this->db->query(
            "UPDATE chat_conversations SET updated_at = NOW() WHERE id = ?",
            [$conversationId]
        );

        return $messageId;
    }

    public function saveMessageFile(int $messageId, array $file): void
    {
        $this->db->query(
            "INSERT INTO chat_message_files (message_id, original_name, stored_name, file_path, file_type, file_size)
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $messageId,
                $file['original_name'],
                $file['stored_name'],
                $file['file_path'],
                $file['file_type'],
                $file['file_size'],
            ]
        );
    }

    public function getMessage(int $messageId): ?array
    {
        $row = $this->db->query("
            SELECT
                m.id,
                m.conversation_id,
                m.sender_id,
                m.message_type,
                m.content,
                m.created_at,
                u.fname,
                u.lname,
                u.profile_photo
            FROM chat_messages m
            INNER JOIN users u ON u.user_id = m.sender_id
            WHERE m.id = ?
        ", [$messageId])->getRow();

        if (!$row) return null;

        $result          = (array) $row;
        $result['files'] = $this->getMessageFiles($messageId);

        return $result;
    }

    /** Returns all messages newer than $afterId — used for fallback polling. */
    public function getMessagesAfter(int $conversationId, int $userId, int $afterId): array
    {
        $rows = $this->db->query("
            SELECT m.id, m.conversation_id, m.sender_id, m.message_type, m.content, m.created_at,
                   CASE WHEN m.deleted_at IS NOT NULL THEN 1 ELSE 0 END AS deleted_for_everyone,
                   u.fname, u.lname, u.profile_photo
            FROM   chat_messages m
            INNER JOIN users u ON u.user_id = m.sender_id
            LEFT  JOIN chat_message_deletions cmd
                   ON  cmd.message_id = m.id AND cmd.user_id = ?
            WHERE  m.conversation_id = ?
              AND  m.id > ?
              AND  cmd.id IS NULL
            ORDER  BY m.created_at ASC
            LIMIT  50
        ", [$userId, $conversationId, $afterId])->getResultArray();

        foreach ($rows as &$row) {
            if ((int) $row['deleted_for_everyone']) {
                $row['message_type'] = 'deleted';
                $row['content']      = null;
                $row['files']        = [];
            } else {
                $row['files'] = ($row['message_type'] !== 'text')
                    ? $this->getMessageFiles((int) $row['id'])
                    : [];
            }
        }

        return $rows;
    }

    // ------------------------------------------------------------------ Message deletion

    /** Soft-delete a message for the current user only. */
    public function deleteForMe(int $messageId, int $userId): bool
    {
        $msg = $this->db->table('chat_messages')->where('id', $messageId)->get()->getRowArray();
        if (!$msg) return false;
        $this->db->query(
            "INSERT IGNORE INTO chat_message_deletions (message_id, user_id, deleted_at) VALUES (?, ?, NOW())",
            [$messageId, $userId]
        );
        return true;
    }

    /** Delete a message for everyone — only the original sender may do this. */
    public function deleteForEveryone(int $messageId, int $senderId): bool
    {
        $msg = $this->db->table('chat_messages')
            ->where('id', $messageId)->where('sender_id', $senderId)
            ->get()->getRowArray();
        if (!$msg) return false;
        $this->db->query("UPDATE chat_messages SET deleted_at = NOW() WHERE id = ?", [$messageId]);
        return true;
    }

    /** Auto-create the deletions table if it doesn't exist yet. */
    public function ensureDeletionTable(): void
    {
        $db = \Config\Database::connect();
        if ($db->tableExists('chat_message_deletions')) return;
        $forge = \Config\Database::forge();
        $forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'message_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'deleted_at' => ['type' => 'DATETIME'],
        ]);
        $forge->addPrimaryKey('id');
        $forge->addUniqueKey(['message_id', 'user_id']);
        $forge->addKey('message_id');
        $forge->createTable('chat_message_deletions', true);
    }

    // ------------------------------------------------------------------ Participants / read

    public function isParticipant(int $conversationId, int $userId): bool
    {
        return (bool) $this->db->query(
            "SELECT id FROM chat_participants WHERE conversation_id = ? AND user_id = ? LIMIT 1",
            [$conversationId, $userId]
        )->getRow();
    }

    public function markRead(int $conversationId, int $userId): void
    {
        $this->db->query(
            "UPDATE chat_participants SET last_read_at = NOW() WHERE conversation_id = ? AND user_id = ?",
            [$conversationId, $userId]
        );
    }

    // ------------------------------------------------------------------ Unread count

    public function getTotalUnreadCount(int $userId): int
    {
        $row = $this->db->query("
            SELECT COUNT(*) AS cnt
            FROM   chat_messages m
            INNER JOIN chat_participants cp
                   ON  cp.conversation_id = m.conversation_id
                  AND  cp.user_id = ?
            WHERE  m.sender_id  != ?
              AND  m.deleted_at IS NULL
              AND  (cp.last_read_at IS NULL OR m.created_at > cp.last_read_at)
        ", [$userId, $userId])->getRow();

        return $row ? (int) $row->cnt : 0;
    }

    // ------------------------------------------------------------------ Private helpers

    private function getMessageFiles(int $messageId): array
    {
        return $this->db->query(
            "SELECT id, original_name, file_path, file_type, file_size
             FROM   chat_message_files
             WHERE  message_id = ?",
            [$messageId]
        )->getResultArray();
    }
}
