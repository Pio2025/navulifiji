<?php
namespace App\Models;
use CodeIgniter\Model;

class LessonDiscussionModel extends Model
{
    protected $table      = 'lesson_discussion';
    protected $primaryKey = 'lesson_discussion_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'lesson_id_fk', 'author', 'message',
        'created_at', 'updated_at', 'created_time', 'message_status',
    ];

    public function ensureTables(): void
    {
        $db  = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if ($db->tableExists('lesson_discussion') && !$db->fieldExists('edited_at', 'lesson_discussion')) {
            $db->query("ALTER TABLE lesson_discussion ADD COLUMN edited_at DATETIME NULL");
        }

        if (!$db->tableExists('lesson_discussion_like')) {
            $forge->addField([
                'like_id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'discussion_id_fk' => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'user_id_fk'       => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'like_type'        => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false],
            ]);
            $forge->addPrimaryKey('like_id');
            $forge->addUniqueKey(['discussion_id_fk', 'user_id_fk']);
            $forge->createTable('lesson_discussion_like', true);
        }

        if (!$db->tableExists('lesson_discussion_comment')) {
            $forge->addField([
                'comment_id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'discussion_id_fk' => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'author'           => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'comment'          => ['type' => 'LONGTEXT', 'null' => false],
                'created_at'       => ['type' => 'DATETIME', 'null' => true],
                'comment_status'   => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
                'edited_at'        => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addPrimaryKey('comment_id');
            $forge->createTable('lesson_discussion_comment', true);
        } elseif (!$db->fieldExists('edited_at', 'lesson_discussion_comment')) {
            $db->query("ALTER TABLE lesson_discussion_comment ADD COLUMN edited_at DATETIME NULL");
        }

        if (!$db->tableExists('lesson_discussion_comment_like')) {
            $forge->addField([
                'clike_id'      => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'comment_id_fk' => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'user_id_fk'    => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'like_type'     => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false, 'default' => 'like'],
            ]);
            $forge->addPrimaryKey('clike_id');
            $forge->addUniqueKey(['comment_id_fk', 'user_id_fk']);
            $forge->createTable('lesson_discussion_comment_like', true);
        } elseif (!$db->fieldExists('like_type', 'lesson_discussion_comment_like')) {
            $db->query("ALTER TABLE lesson_discussion_comment_like ADD COLUMN like_type ENUM('like','dislike') NOT NULL DEFAULT 'like'");
        }

        if (!$db->tableExists('lesson_discussion_photo')) {
            $forge->addField([
                'photo_id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'ld_id_fk'    => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'photo_path'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
                'photo_order' => ['type' => 'INT', 'default' => 0],
            ]);
            $forge->addPrimaryKey('photo_id');
            $forge->createTable('lesson_discussion_photo', true);
        }

        if (!$db->tableExists('lesson_discussion_comment_reply')) {
            $forge->addField([
                'reply_id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'comment_id_fk'      => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'parent_reply_id_fk' => ['type' => 'INT', 'unsigned' => false, 'null' => true],
                'author'             => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'reply'              => ['type' => 'LONGTEXT', 'null' => false],
                'created_at'         => ['type' => 'DATETIME', 'null' => true],
                'reply_status'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
                'edited_at'          => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addPrimaryKey('reply_id');
            $forge->createTable('lesson_discussion_comment_reply', true);
        } else {
            if (!$db->fieldExists('parent_reply_id_fk', 'lesson_discussion_comment_reply')) {
                $db->query("ALTER TABLE lesson_discussion_comment_reply ADD COLUMN parent_reply_id_fk INT NULL AFTER comment_id_fk");
            }
            if (!$db->fieldExists('edited_at', 'lesson_discussion_comment_reply')) {
                $db->query("ALTER TABLE lesson_discussion_comment_reply ADD COLUMN edited_at DATETIME NULL");
            }
        }

        if (!$db->tableExists('lesson_discussion_comment_reply_like')) {
            $forge->addField([
                'rlike_id'     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'reply_id_fk'  => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'user_id_fk'   => ['type' => 'INT', 'unsigned' => false, 'null' => false],
                'like_type'    => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false, 'default' => 'like'],
            ]);
            $forge->addPrimaryKey('rlike_id');
            $forge->addUniqueKey(['reply_id_fk', 'user_id_fk']);
            $forge->createTable('lesson_discussion_comment_reply_like', true);
        }
    }

    public function getDiscussions(int $lessonId, int $userId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                ld.lesson_discussion_id,
                ld.message,
                ld.created_at,
                ld.message_status,
                ld.edited_at,
                ld.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT rc_p.role_cat_name FROM user_role ur_p
                 INNER JOIN role ro_p ON ro_p.role_id = ur_p.role_id_fk
                 INNER JOIN role_category rc_p ON rc_p.role_cat_id = ro_p.role_cat_id_fk
                 WHERE ur_p.user_id_fk = ld.author AND ur_p.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_name,
                (SELECT COUNT(*) FROM lesson_discussion_comment ldc
                 WHERE ldc.discussion_id_fk = ld.lesson_discussion_id AND ldc.comment_status = 'Active') AS comment_count,
                (SELECT COUNT(*) FROM lesson_discussion_like ldl
                 WHERE ldl.discussion_id_fk = ld.lesson_discussion_id AND ldl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM lesson_discussion_like ldl
                 WHERE ldl.discussion_id_fk = ld.lesson_discussion_id AND ldl.like_type = 'dislike') AS dislike_count,
                (SELECT ldl2.like_type FROM lesson_discussion_like ldl2
                 WHERE ldl2.discussion_id_fk = ld.lesson_discussion_id AND ldl2.user_id_fk = ?
                 LIMIT 1) AS user_reaction
            FROM lesson_discussion ld
            INNER JOIN users u ON u.user_id = ld.author
            WHERE ld.lesson_id_fk = ? AND ld.message_status IN (1, 2)
            ORDER BY ld.created_at DESC
        ", [$userId, $lessonId])->getResultArray();

        foreach ($rows as &$row) {
            $row['lesson_discussion_id'] = (int) $row['lesson_discussion_id'];
            $row['author_id']            = (int) $row['author_id'];
            $row['comment_count']        = (int) $row['comment_count'];
            $row['like_count']           = (int) $row['like_count'];
            $row['dislike_count']        = (int) $row['dislike_count'];
            $row['photos']   = (int) $row['message_status'] === 2 ? [] : $this->getPhotos($row['lesson_discussion_id']);
            $row['comments'] = $this->getComments($row['lesson_discussion_id'], $userId);
        }
        return $rows;
    }

    public function getPhotos(int $discussionId): array
    {
        return \Config\Database::connect()
            ->table('lesson_discussion_photo')
            ->where('ld_id_fk', $discussionId)
            ->orderBy('photo_order', 'ASC')
            ->get()->getResultArray();
    }

    public function getComments(int $discussionId, int $userId = 0): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                ldc.comment_id,
                ldc.comment,
                ldc.created_at,
                ldc.comment_status,
                ldc.edited_at,
                ldc.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT rc_c.role_cat_name FROM user_role ur_c
                 INNER JOIN role ro_c ON ro_c.role_id = ur_c.role_id_fk
                 INNER JOIN role_category rc_c ON rc_c.role_cat_id = ro_c.role_cat_id_fk
                 WHERE ur_c.user_id_fk = ldc.author AND ur_c.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_name,
                (SELECT COUNT(*) FROM lesson_discussion_comment_like cl
                 WHERE cl.comment_id_fk = ldc.comment_id AND cl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM lesson_discussion_comment_like cl
                 WHERE cl.comment_id_fk = ldc.comment_id AND cl.like_type = 'dislike') AS dislike_count,
                (SELECT cl2.like_type FROM lesson_discussion_comment_like cl2
                 WHERE cl2.comment_id_fk = ldc.comment_id AND cl2.user_id_fk = ? LIMIT 1) AS user_reaction
            FROM lesson_discussion_comment ldc
            INNER JOIN users u ON u.user_id = ldc.author
            WHERE ldc.discussion_id_fk = ? AND ldc.comment_status IN ('Active', 'Removed')
            ORDER BY ldc.created_at ASC
        ", [$userId, $discussionId])->getResultArray();

        foreach ($rows as &$row) {
            $row['comment_id']     = (int) $row['comment_id'];
            $row['author_id']      = (int) $row['author_id'];
            $row['like_count']     = (int) $row['like_count'];
            $row['dislike_count']  = (int) $row['dislike_count'];
            $row['replies'] = $this->getReplies($row['comment_id'], $userId);
        }
        return $rows;
    }

    /**
     * Returns replies for a comment as a nested tree: top-level replies (direct
     * replies to the comment) each carry a 'replies' array of replies-to-that-reply,
     * recursively, so the mobile app can render an unlimited-depth reply thread.
     */
    public function getReplies(int $commentId, int $userId = 0): array
    {
        $rows = \Config\Database::connect()->query("
            SELECT
                r.reply_id,
                r.parent_reply_id_fk,
                r.reply,
                r.created_at,
                r.reply_status,
                r.edited_at,
                r.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT rc_r.role_cat_name FROM user_role ur_r
                 INNER JOIN role ro_r ON ro_r.role_id = ur_r.role_id_fk
                 INNER JOIN role_category rc_r ON rc_r.role_cat_id = ro_r.role_cat_id_fk
                 WHERE ur_r.user_id_fk = r.author AND ur_r.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_name,
                (SELECT COUNT(*) FROM lesson_discussion_comment_reply_like rl
                 WHERE rl.reply_id_fk = r.reply_id AND rl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM lesson_discussion_comment_reply_like rl
                 WHERE rl.reply_id_fk = r.reply_id AND rl.like_type = 'dislike') AS dislike_count,
                (SELECT rl2.like_type FROM lesson_discussion_comment_reply_like rl2
                 WHERE rl2.reply_id_fk = r.reply_id AND rl2.user_id_fk = ? LIMIT 1) AS user_reaction
            FROM lesson_discussion_comment_reply r
            INNER JOIN users u ON u.user_id = r.author
            WHERE r.comment_id_fk = ? AND r.reply_status IN ('Active', 'Removed')
            ORDER BY r.created_at ASC
        ", [$userId, $commentId])->getResultArray();

        $byId = [];
        foreach ($rows as &$row) {
            $row['reply_id']           = (int) $row['reply_id'];
            $row['parent_reply_id_fk'] = $row['parent_reply_id_fk'] !== null ? (int) $row['parent_reply_id_fk'] : null;
            $row['author_id']          = (int) $row['author_id'];
            $row['like_count']         = (int) $row['like_count'];
            $row['dislike_count']      = (int) $row['dislike_count'];
            $row['replies']            = [];
            $byId[$row['reply_id']]    = &$row;
        }
        unset($row);

        $tree = [];
        foreach ($byId as &$row) {
            $parentId = $row['parent_reply_id_fk'];
            if ($parentId !== null && isset($byId[$parentId])) {
                $byId[$parentId]['replies'][] = &$row;
            } else {
                $tree[] = &$row;
            }
        }
        unset($row);

        return $tree;
    }

    public function toggleReplyLike(int $replyId, int $userId, string $type = 'like'): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('lesson_discussion_comment_reply_like')
            ->where('reply_id_fk', $replyId)->where('user_id_fk', $userId)->get()->getRowArray();

        if (!$existing) {
            $db->table('lesson_discussion_comment_reply_like')->insert([
                'reply_id_fk' => $replyId,
                'user_id_fk'  => $userId,
                'like_type'   => $type,
            ]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('lesson_discussion_comment_reply_like')
                ->where('reply_id_fk', $replyId)->where('user_id_fk', $userId)->delete();
            $reaction = null;
        } else {
            $db->table('lesson_discussion_comment_reply_like')
                ->where('reply_id_fk', $replyId)->where('user_id_fk', $userId)->update(['like_type' => $type]);
            $reaction = $type;
        }

        $likes    = (int) $db->table('lesson_discussion_comment_reply_like')
            ->where('reply_id_fk', $replyId)->where('like_type', 'like')->countAllResults();
        $dislikes = (int) $db->table('lesson_discussion_comment_reply_like')
            ->where('reply_id_fk', $replyId)->where('like_type', 'dislike')->countAllResults();

        return ['reaction' => $reaction, 'likes' => $likes, 'dislikes' => $dislikes];
    }

    public function getReplyReactions(int $replyId): array
    {
        return \Config\Database::connect()->query("
            SELECT rl.like_type, CONCAT(u.fname, ' ', u.lname) AS name, u.profile_photo AS photo
            FROM lesson_discussion_comment_reply_like rl
            INNER JOIN users u ON u.user_id = rl.user_id_fk
            WHERE rl.reply_id_fk = ?
            ORDER BY rl.like_type ASC, u.fname ASC
        ", [$replyId])->getResultArray();
    }

    public function toggleCommentLike(int $commentId, int $userId, string $type = 'like'): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('lesson_discussion_comment_like')
            ->where('comment_id_fk', $commentId)->where('user_id_fk', $userId)
            ->get()->getRowArray();

        if (!$existing) {
            $db->table('lesson_discussion_comment_like')->insert([
                'comment_id_fk' => $commentId,
                'user_id_fk'    => $userId,
                'like_type'     => $type,
            ]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('lesson_discussion_comment_like')
                ->where('comment_id_fk', $commentId)->where('user_id_fk', $userId)->delete();
            $reaction = null;
        } else {
            $db->table('lesson_discussion_comment_like')
                ->where('comment_id_fk', $commentId)->where('user_id_fk', $userId)
                ->update(['like_type' => $type]);
            $reaction = $type;
        }

        $likes    = (int) $db->table('lesson_discussion_comment_like')
            ->where('comment_id_fk', $commentId)->where('like_type', 'like')->countAllResults();
        $dislikes = (int) $db->table('lesson_discussion_comment_like')
            ->where('comment_id_fk', $commentId)->where('like_type', 'dislike')->countAllResults();

        return ['reaction' => $reaction, 'likes' => $likes, 'dislikes' => $dislikes];
    }

    public function getDiscussionReactions(int $discussionId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT ldl.like_type, CONCAT(u.fname, ' ', u.lname) AS name, u.profile_photo AS photo
            FROM lesson_discussion_like ldl
            INNER JOIN users u ON u.user_id = ldl.user_id_fk
            WHERE ldl.discussion_id_fk = ?
            ORDER BY ldl.like_type ASC, u.fname ASC
        ", [$discussionId])->getResultArray();
    }

    public function getCommentReactions(int $commentId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT cl.like_type, CONCAT(u.fname, ' ', u.lname) AS name, u.profile_photo AS photo
            FROM lesson_discussion_comment_like cl
            INNER JOIN users u ON u.user_id = cl.user_id_fk
            WHERE cl.comment_id_fk = ?
            ORDER BY cl.like_type ASC, u.fname ASC
        ", [$commentId])->getResultArray();
    }

    public function toggleLike(int $discussionId, int $userId, string $type): array
    {
        $db      = \Config\Database::connect();
        $existing = $db->table('lesson_discussion_like')
            ->where('discussion_id_fk', $discussionId)
            ->where('user_id_fk', $userId)
            ->get()->getRowArray();

        if (!$existing) {
            $db->table('lesson_discussion_like')->insert([
                'discussion_id_fk' => $discussionId,
                'user_id_fk'       => $userId,
                'like_type'        => $type,
            ]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('lesson_discussion_like')
                ->where('discussion_id_fk', $discussionId)
                ->where('user_id_fk', $userId)
                ->delete();
            $reaction = null;
        } else {
            $db->table('lesson_discussion_like')
                ->where('discussion_id_fk', $discussionId)
                ->where('user_id_fk', $userId)
                ->update(['like_type' => $type]);
            $reaction = $type;
        }

        $likes    = (int) $db->table('lesson_discussion_like')
            ->where('discussion_id_fk', $discussionId)->where('like_type', 'like')->countAllResults();
        $dislikes = (int) $db->table('lesson_discussion_like')
            ->where('discussion_id_fk', $discussionId)->where('like_type', 'dislike')->countAllResults();

        return ['reaction' => $reaction, 'likes' => $likes, 'dislikes' => $dislikes];
    }

    public function getPostAuthor(int $discussionId): ?int
    {
        $row = \Config\Database::connect()->table('lesson_discussion')->select('author')->where('lesson_discussion_id', $discussionId)->get()->getRowArray();
        return $row ? (int) $row['author'] : null;
    }

    public function getCommentAuthor(int $commentId): ?int
    {
        $row = \Config\Database::connect()->table('lesson_discussion_comment')->select('author')->where('comment_id', $commentId)->get()->getRowArray();
        return $row ? (int) $row['author'] : null;
    }

    public function getReplyAuthor(int $replyId): ?int
    {
        $row = \Config\Database::connect()->table('lesson_discussion_comment_reply')->select('author')->where('reply_id', $replyId)->get()->getRowArray();
        return $row ? (int) $row['author'] : null;
    }

    public function editPost(int $discussionId, string $message): void
    {
        \Config\Database::connect()->table('lesson_discussion')->where('lesson_discussion_id', $discussionId)
            ->update(['message' => $message, 'edited_at' => date('Y-m-d H:i:s')]);
    }

    public function selfDeletePost(int $discussionId): void
    {
        \Config\Database::connect()->table('lesson_discussion')->where('lesson_discussion_id', $discussionId)->update(['message_status' => 0]);
    }

    public function moderateRemovePost(int $discussionId): void
    {
        \Config\Database::connect()->table('lesson_discussion')->where('lesson_discussion_id', $discussionId)->update(['message_status' => 2]);
    }

    public function editComment(int $commentId, string $comment): void
    {
        \Config\Database::connect()->table('lesson_discussion_comment')->where('comment_id', $commentId)
            ->update(['comment' => $comment, 'edited_at' => date('Y-m-d H:i:s')]);
    }

    public function selfDeleteComment(int $commentId): void
    {
        \Config\Database::connect()->table('lesson_discussion_comment')->where('comment_id', $commentId)->update(['comment_status' => 'Deleted']);
    }

    public function moderateRemoveComment(int $commentId): void
    {
        \Config\Database::connect()->table('lesson_discussion_comment')->where('comment_id', $commentId)->update(['comment_status' => 'Removed']);
    }

    public function editReply(int $replyId, string $reply): void
    {
        \Config\Database::connect()->table('lesson_discussion_comment_reply')->where('reply_id', $replyId)
            ->update(['reply' => $reply, 'edited_at' => date('Y-m-d H:i:s')]);
    }

    public function selfDeleteReply(int $replyId): void
    {
        \Config\Database::connect()->table('lesson_discussion_comment_reply')->where('reply_id', $replyId)->update(['reply_status' => 'Deleted']);
    }

    public function moderateRemoveReply(int $replyId): void
    {
        \Config\Database::connect()->table('lesson_discussion_comment_reply')->where('reply_id', $replyId)->update(['reply_status' => 'Removed']);
    }
}
