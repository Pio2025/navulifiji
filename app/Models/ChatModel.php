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
        $existing = $this->findDirectConversationId($userId1, $userId2);
        if ($existing !== null) {
            return $existing;
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

    /** Returns the existing direct conversation ID between two users, or null if none exists yet. */
    public function findDirectConversationId(int $userId1, int $userId2): ?int
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

        return $row ? (int) $row->conversation_id : null;
    }

    /** True if the user currently has an active admission (i.e. is affiliated to a school). */
    public function hasActiveAdmission(int $userId): bool
    {
        return (bool) $this->db->query(
            "SELECT admission_id FROM admission WHERE user_id_fk = ? AND admission_status = 'Active' LIMIT 1",
            [$userId]
        )->getRow();
    }

    public function getConversations(int $userId): array
    {
        $rows = $this->db->query("
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
                lm.sender_id       AS last_message_sender_id,
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

        foreach ($rows as &$row) {
            $row['conversation_id']      = (int) $row['conversation_id'];
            $row['other_user_id']        = $row['other_user_id'] !== null ? (int) $row['other_user_id'] : null;
            $row['unread_count']         = (int) $row['unread_count'];
            $row['last_message_sender_id'] = $row['last_message_sender_id'] !== null ? (int) $row['last_message_sender_id'] : null;
        }
        unset($row);

        return $rows;
    }

    // ------------------------------------------------------------------ Messages

    public function getMessages(int $conversationId, int $userId, int $page = 1, int $perPage = 30): array
    {
        $offset = ($page - 1) * $perPage;

        $rows = $this->db->query("
            SELECT
                m.id, m.conversation_id, m.sender_id, m.message_type, m.content, m.created_at,
                m.reply_to_message_id, m.is_pinned, m.pinned_at, m.pinned_by, m.is_forwarded,
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
            $row['id']              = (int) $row['id'];
            $row['conversation_id'] = (int) $row['conversation_id'];
            $row['sender_id']       = (int) $row['sender_id'];
            $row['is_pinned']       = (bool) $row['is_pinned'];
            $row['is_forwarded']    = (bool) $row['is_forwarded'];
            $row['pinned_by']       = $row['pinned_by'] !== null ? (int) $row['pinned_by'] : null;
            if ((int) $row['deleted_for_everyone']) {
                $row['message_type'] = 'deleted';
                $row['content']      = null;
                $row['files']        = [];
            } else {
                $row['files'] = ($row['message_type'] !== 'text')
                    ? $this->getMessageFiles($row['id'])
                    : [];
            }
        }
        unset($row);

        $this->attachReactions($rows, $userId);
        $this->attachReplyPreviews($rows);

        return array_reverse($rows);
    }

    public function saveMessage(
        int $conversationId,
        int $senderId,
        string $type,
        ?string $content,
        ?int $replyToMessageId = null,
        bool $isForwarded = false
    ): int {
        $this->db->query(
            "INSERT INTO chat_messages (conversation_id, sender_id, message_type, content, reply_to_message_id, is_forwarded)
             VALUES (?, ?, ?, ?, ?, ?)",
            [$conversationId, $senderId, $type, $content, $replyToMessageId, $isForwarded ? 1 : 0]
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

    public function getMessage(int $messageId, ?int $viewerId = null): ?array
    {
        $row = $this->db->query("
            SELECT
                m.id,
                m.conversation_id,
                m.sender_id,
                m.message_type,
                m.content,
                m.created_at,
                m.reply_to_message_id,
                m.is_pinned,
                m.pinned_at,
                m.pinned_by,
                m.is_forwarded,
                u.fname,
                u.lname,
                u.profile_photo
            FROM chat_messages m
            INNER JOIN users u ON u.user_id = m.sender_id
            WHERE m.id = ?
        ", [$messageId])->getRow();

        if (!$row) return null;

        $result                    = (array) $row;
        $result['id']              = (int) $result['id'];
        $result['conversation_id'] = (int) $result['conversation_id'];
        $result['sender_id']       = (int) $result['sender_id'];
        $result['is_pinned']       = (bool) $result['is_pinned'];
        $result['is_forwarded']    = (bool) $result['is_forwarded'];
        $result['pinned_by']       = $result['pinned_by'] !== null ? (int) $result['pinned_by'] : null;
        $result['files']           = $this->getMessageFiles($messageId);
        $result['reactions']       = $this->getReactionSummary($messageId, $viewerId ?? (int) $row->sender_id);

        $rows = [$result];
        $this->attachReplyPreviews($rows);

        return $rows[0];
    }

    /** Returns all messages newer than $afterId — used for fallback polling. */
    public function getMessagesAfter(int $conversationId, int $userId, int $afterId): array
    {
        $rows = $this->db->query("
            SELECT m.id, m.conversation_id, m.sender_id, m.message_type, m.content, m.created_at,
                   m.reply_to_message_id, m.is_pinned, m.pinned_at, m.pinned_by, m.is_forwarded,
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
            $row['id']              = (int) $row['id'];
            $row['conversation_id'] = (int) $row['conversation_id'];
            $row['sender_id']       = (int) $row['sender_id'];
            $row['is_pinned']       = (bool) $row['is_pinned'];
            $row['is_forwarded']    = (bool) $row['is_forwarded'];
            $row['pinned_by']       = $row['pinned_by'] !== null ? (int) $row['pinned_by'] : null;
            if ((int) $row['deleted_for_everyone']) {
                $row['message_type'] = 'deleted';
                $row['content']      = null;
                $row['files']        = [];
            } else {
                $row['files'] = ($row['message_type'] !== 'text')
                    ? $this->getMessageFiles($row['id'])
                    : [];
            }
        }
        unset($row);

        $this->attachReactions($rows, $userId);
        $this->attachReplyPreviews($rows);

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

    /** Soft-deletes every message in a conversation for the requesting user only (bulk "remove for me"). */
    public function clearConversationForUser(int $conversationId, int $userId): void
    {
        $this->db->query(
            "INSERT IGNORE INTO chat_message_deletions (message_id, user_id, deleted_at)
             SELECT m.id, ?, NOW() FROM chat_messages m WHERE m.conversation_id = ?",
            [$userId, $conversationId]
        );
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

    // ------------------------------------------------------------------ Reactions

    /** Set (or replace) the requesting user's single reaction on a message. */
    public function setReaction(int $messageId, int $userId, string $emoji): array
    {
        $this->db->query(
            "INSERT INTO chat_message_reactions (message_id, user_id, emoji, created_at) VALUES (?, ?, ?, NOW())
             ON DUPLICATE KEY UPDATE emoji = VALUES(emoji), created_at = NOW()",
            [$messageId, $userId, $emoji]
        );
        return $this->getReactionSummary($messageId, $userId);
    }

    /** Remove the requesting user's reaction from a message. */
    public function removeReaction(int $messageId, int $userId): array
    {
        $this->db->query(
            "DELETE FROM chat_message_reactions WHERE message_id = ? AND user_id = ?",
            [$messageId, $userId]
        );
        return $this->getReactionSummary($messageId, $userId);
    }

    /** Returns the requesting user's current reaction emoji on a message, or null. */
    public function getUserReaction(int $messageId, int $userId): ?string
    {
        $row = $this->db->query(
            "SELECT emoji FROM chat_message_reactions WHERE message_id = ? AND user_id = ?",
            [$messageId, $userId]
        )->getRow();
        return $row ? $row->emoji : null;
    }

    /** Grouped reaction summary for a single message: [{emoji, count, mine}]. */
    public function getReactionSummary(int $messageId, int $viewerId): array
    {
        $rows = $this->db->query(
            "SELECT emoji, COUNT(*) AS cnt, SUM(user_id = ?) AS mine
             FROM   chat_message_reactions
             WHERE  message_id = ?
             GROUP  BY emoji",
            [$viewerId, $messageId]
        )->getResultArray();

        return array_map(fn($r) => [
            'emoji' => $r['emoji'],
            'count' => (int) $r['cnt'],
            'mine'  => (bool) $r['mine'],
        ], $rows);
    }

    /** Batch-attaches a `reactions` array to each row in $rows (avoids N+1 queries). */
    private function attachReactions(array &$rows, int $viewerId): void
    {
        if (empty($rows)) return;

        $ids = array_map(fn($r) => (int) $r['id'], $rows);
        $in  = implode(',', array_fill(0, count($ids), '?'));

        $all = $this->db->query(
            "SELECT message_id, emoji, COUNT(*) AS cnt, SUM(user_id = ?) AS mine
             FROM   chat_message_reactions
             WHERE  message_id IN ($in)
             GROUP  BY message_id, emoji",
            array_merge([$viewerId], $ids)
        )->getResultArray();

        $byMessage = [];
        foreach ($all as $r) {
            $byMessage[(int) $r['message_id']][] = [
                'emoji' => $r['emoji'],
                'count' => (int) $r['cnt'],
                'mine'  => (bool) $r['mine'],
            ];
        }

        foreach ($rows as &$row) {
            $row['reactions'] = $byMessage[(int) $row['id']] ?? [];
        }
        unset($row);
    }

    // ------------------------------------------------------------------ Reply previews

    /** Batch-attaches a `reply_to` preview (or null) to each row in $rows (avoids N+1 queries). */
    private function attachReplyPreviews(array &$rows): void
    {
        if (empty($rows)) return;

        $replyIds = array_values(array_unique(array_filter(
            array_map(fn($r) => $r['reply_to_message_id'] ?? null, $rows)
        )));

        if (empty($replyIds)) {
            foreach ($rows as &$row) { $row['reply_to'] = null; }
            unset($row);
            return;
        }

        $in = implode(',', array_fill(0, count($replyIds), '?'));
        $parents = $this->db->query("
            SELECT m.id, m.sender_id, m.message_type, m.content, m.deleted_at, u.fname, u.lname
            FROM   chat_messages m
            INNER JOIN users u ON u.user_id = m.sender_id
            WHERE  m.id IN ($in)
        ", array_map('intval', $replyIds))->getResultArray();

        $byId = [];
        foreach ($parents as $p) {
            $deleted = $p['deleted_at'] !== null;
            $byId[(int) $p['id']] = [
                'id'           => (int) $p['id'],
                'sender_id'    => (int) $p['sender_id'],
                'sender_name'  => trim($p['fname'] . ' ' . $p['lname']),
                'message_type' => $deleted ? 'deleted' : $p['message_type'],
                'snippet'      => $deleted ? null : $this->contentSnippet($p['message_type'], $p['content']),
            ];
        }

        foreach ($rows as &$row) {
            $rid = $row['reply_to_message_id'] ?? null;
            $row['reply_to'] = ($rid && isset($byId[(int) $rid])) ? $byId[(int) $rid] : null;
        }
        unset($row);
    }

    private function contentSnippet(string $type, ?string $content): string
    {
        return match ($type) {
            'text'  => mb_strimwidth((string) $content, 0, 120, '…'),
            'image' => '📷 Photo',
            'file'  => '📎 File',
            'call'  => '📞 Call',
            default => '',
        };
    }

    /** Auto-create the reactions table if it doesn't exist yet. */
    public function ensureReactionsTable(): void
    {
        $db = \Config\Database::connect();
        if ($db->tableExists('chat_message_reactions')) return;
        $forge = \Config\Database::forge();
        $forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'message_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'emoji'      => ['type' => 'VARCHAR', 'constraint' => 16],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $forge->addPrimaryKey('id');
        $forge->addUniqueKey(['message_id', 'user_id']);
        $forge->addKey('message_id');
        $forge->createTable('chat_message_reactions', true);
    }

    // ------------------------------------------------------------------ Block

    public function blockUser(int $blockerId, int $blockedId): void
    {
        $this->db->query(
            "INSERT IGNORE INTO chat_user_blocks (blocker_id, blocked_id, created_at) VALUES (?, ?, NOW())",
            [$blockerId, $blockedId]
        );
    }

    public function unblockUser(int $blockerId, int $blockedId): void
    {
        $this->db->query(
            "DELETE FROM chat_user_blocks WHERE blocker_id = ? AND blocked_id = ?",
            [$blockerId, $blockedId]
        );
    }

    /** True if either user has blocked the other (mutual silence while blocked). */
    public function isBlockedBetween(int $a, int $b): bool
    {
        return (bool) $this->db->query(
            "SELECT id FROM chat_user_blocks
             WHERE (blocker_id = ? AND blocked_id = ?) OR (blocker_id = ? AND blocked_id = ?)
             LIMIT 1",
            [$a, $b, $b, $a]
        )->getRow();
    }

    /** True only if $myId is the one who placed the block (controls Block vs Unblock label). */
    public function blockedByMe(int $myId, int $otherId): bool
    {
        return (bool) $this->db->query(
            "SELECT id FROM chat_user_blocks WHERE blocker_id = ? AND blocked_id = ? LIMIT 1",
            [$myId, $otherId]
        )->getRow();
    }

    /** Auto-create the blocks table if it doesn't exist yet. */
    public function ensureBlocksTable(): void
    {
        $db = \Config\Database::connect();
        if ($db->tableExists('chat_user_blocks')) return;
        $forge = \Config\Database::forge();
        $forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'blocker_id' => ['type' => 'INT', 'unsigned' => true],
            'blocked_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $forge->addPrimaryKey('id');
        $forge->addUniqueKey(['blocker_id', 'blocked_id']);
        $forge->addKey('blocked_id');
        $forge->createTable('chat_user_blocks', true);
    }

    /** Auto-adds the reply/pin/forward columns to chat_messages if they don't exist yet. */
    public function ensureReplyPinColumns(): void
    {
        $db = \Config\Database::connect();
        if ($db->fieldExists('reply_to_message_id', 'chat_messages')) return;
        $forge = \Config\Database::forge();
        $forge->addColumn('chat_messages', [
            'reply_to_message_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'content'],
            'is_pinned'           => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false, 'default' => 0, 'after' => 'reply_to_message_id'],
            'pinned_at'           => ['type' => 'DATETIME', 'null' => true, 'after' => 'is_pinned'],
            'pinned_by'           => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'pinned_at'],
            'is_forwarded'        => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false, 'default' => 0, 'after' => 'pinned_by'],
        ]);
    }

    // ------------------------------------------------------------------ Pin

    /** Toggles the pinned state of a message. Returns ['pinned' => bool]. */
    public function togglePin(int $messageId, int $userId): array
    {
        $msg = $this->db->table('chat_messages')->where('id', $messageId)->get()->getRowArray();
        if (!$msg) return ['pinned' => false];

        $nowPinned = !((bool) $msg['is_pinned']);
        if ($nowPinned) {
            $this->db->query(
                "UPDATE chat_messages SET is_pinned = 1, pinned_at = NOW(), pinned_by = ? WHERE id = ?",
                [$userId, $messageId]
            );
        } else {
            $this->db->query(
                "UPDATE chat_messages SET is_pinned = 0, pinned_at = NULL, pinned_by = NULL WHERE id = ?",
                [$messageId]
            );
        }

        return ['pinned' => $nowPinned];
    }

    /** All currently-pinned, non-deleted messages in a conversation, newest pin first. */
    public function getPinnedMessages(int $conversationId, int $viewerId): array
    {
        $rows = $this->db->query("
            SELECT
                m.id, m.conversation_id, m.sender_id, m.message_type, m.content, m.created_at,
                m.reply_to_message_id, m.is_pinned, m.pinned_at, m.pinned_by, m.is_forwarded,
                u.fname, u.lname, u.profile_photo
            FROM   chat_messages m
            INNER JOIN users u ON u.user_id = m.sender_id
            WHERE  m.conversation_id = ? AND m.is_pinned = 1 AND m.deleted_at IS NULL
            ORDER  BY m.pinned_at DESC
        ", [$conversationId])->getResultArray();

        foreach ($rows as &$row) {
            $row['id']              = (int) $row['id'];
            $row['conversation_id'] = (int) $row['conversation_id'];
            $row['sender_id']       = (int) $row['sender_id'];
            $row['is_pinned']       = true;
            $row['is_forwarded']    = (bool) $row['is_forwarded'];
            $row['pinned_by']       = $row['pinned_by'] !== null ? (int) $row['pinned_by'] : null;
            $row['files']           = ($row['message_type'] !== 'text') ? $this->getMessageFiles($row['id']) : [];
        }
        unset($row);

        $this->attachReactions($rows, $viewerId);
        $this->attachReplyPreviews($rows);

        return $rows;
    }

    // ------------------------------------------------------------------ Forward

    /** Copies a message (and its files, if any) into another conversation as a new message. */
    public function forwardMessage(int $messageId, int $targetConversationId, int $senderId): ?int
    {
        $msg = $this->db->table('chat_messages')->where('id', $messageId)->get()->getRowArray();
        if (!$msg || $msg['deleted_at'] !== null) return null;

        $newId = $this->saveMessage($targetConversationId, $senderId, $msg['message_type'], $msg['content'], null, true);

        if ($msg['message_type'] !== 'text') {
            foreach ($this->getMessageFiles($messageId) as $f) {
                $this->saveMessageFile($newId, [
                    'original_name' => $f['original_name'],
                    'stored_name'   => basename($f['file_path']),
                    'file_path'     => $f['file_path'],
                    'file_type'     => $f['file_type'],
                    'file_size'     => $f['file_size'],
                ]);
            }
        }

        return $newId;
    }

    /** Returns the other participant's user_id in a direct conversation, or null. */
    public function getOtherParticipant(int $conversationId, int $myId): ?int
    {
        $row = $this->db->query(
            "SELECT user_id FROM chat_participants WHERE conversation_id = ? AND user_id != ? LIMIT 1",
            [$conversationId, $myId]
        )->getRow();
        return $row ? (int) $row->user_id : null;
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

    /**
     * Returns unread message counts keyed by sender user_id.
     * e.g. [ 5 => 3, 12 => 1 ] means 3 unread from user 5, 1 from user 12.
     */
    public function getUnreadCountsPerUser(int $userId): array
    {
        $rows = $this->db->query("
            SELECT m.sender_id, COUNT(*) AS cnt
            FROM   chat_messages m
            INNER JOIN chat_participants cp
                   ON  cp.conversation_id = m.conversation_id
                  AND  cp.user_id = ?
            WHERE  m.sender_id  != ?
              AND  m.deleted_at IS NULL
              AND  (cp.last_read_at IS NULL OR m.created_at > cp.last_read_at)
            GROUP BY m.sender_id
        ", [$userId, $userId])->getResultArray();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['sender_id']] = (int) $row['cnt'];
        }
        return $result;
    }

    // ------------------------------------------------------------------ Private helpers

    private function getMessageFiles(int $messageId): array
    {
        $rows = $this->db->query(
            "SELECT id, original_name, file_path, file_type, file_size
             FROM   chat_message_files
             WHERE  message_id = ?",
            [$messageId]
        )->getResultArray();

        foreach ($rows as &$row) {
            $row['id']        = (int) $row['id'];
            $row['file_size'] = (int) $row['file_size'];
        }
        unset($row);

        return $rows;
    }
}
