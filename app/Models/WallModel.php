<?php

namespace App\Models;

use CodeIgniter\Model;

class WallModel extends Model
{
    protected $table      = 'wall_post';
    protected $primaryKey = 'wall_post_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'sch_id_fk', 'user_id_fk', 'content', 'post_status', 'created_at', 'updated_at',
    ];

    // ─── table bootstrap ────────────────────────────────────────────────────

    public function ensureTables(): void
    {
        $db  = \Config\Database::connect();
        $dbf = \Config\Database::forge();

        // wall_post
        if (!$db->tableExists('wall_post')) {
            $dbf->addField([
                'wall_post_id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'sch_id_fk'   => ['type' => 'INT', 'unsigned' => true],
                'user_id_fk'  => ['type' => 'INT', 'unsigned' => true],
                'content'     => ['type' => 'TEXT', 'null' => true],
                'post_status' => ['type' => 'ENUM', 'constraint' => ['Active','Deleted'], 'default' => 'Active'],
                'created_at'  => ['type' => 'DATETIME'],
                'updated_at'  => ['type' => 'DATETIME'],
            ]);
            $dbf->addPrimaryKey('wall_post_id');
            $dbf->addKey('sch_id_fk');
            $dbf->addKey('user_id_fk');
            $dbf->createTable('wall_post', true, ['ENGINE' => 'MyISAM']);
        }

        // wall_media — photos, video_url, file attachments on posts
        if (!$db->tableExists('wall_media')) {
            $dbf->addField([
                'wall_media_id'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'wall_post_id_fk' => ['type' => 'INT', 'unsigned' => true],
                'media_type'      => ['type' => 'ENUM', 'constraint' => ['image','video_url','file'], 'default' => 'image'],
                'file_src'        => ['type' => 'VARCHAR', 'constraint' => 500], // stored filename or raw URL
                'file_name'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // original name
                'created_at'      => ['type' => 'DATETIME'],
            ]);
            $dbf->addPrimaryKey('wall_media_id');
            $dbf->addKey('wall_post_id_fk');
            $dbf->createTable('wall_media', true, ['ENGINE' => 'MyISAM']);
        }

        // wall_comment — comments and nested replies (parent_comment_id NULL = direct comment)
        if (!$db->tableExists('wall_comment')) {
            $dbf->addField([
                'wall_comment_id'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'wall_post_id_fk'   => ['type' => 'INT', 'unsigned' => true],
                'parent_comment_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'default' => null],
                'user_id_fk'        => ['type' => 'INT', 'unsigned' => true],
                'content'           => ['type' => 'TEXT'],
                'comment_status'    => ['type' => 'ENUM', 'constraint' => ['Active','Deleted'], 'default' => 'Active'],
                'created_at'        => ['type' => 'DATETIME'],
            ]);
            $dbf->addPrimaryKey('wall_comment_id');
            $dbf->addKey('wall_post_id_fk');
            $dbf->addKey('parent_comment_id');
            $dbf->createTable('wall_comment', true, ['ENGINE' => 'MyISAM']);
        }

        // wall_reaction — emoji reactions on posts or comments; one reaction per user per target
        if (!$db->tableExists('wall_reaction')) {
            $dbf->addField([
                'wall_reaction_id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'target_type'      => ['type' => 'ENUM', 'constraint' => ['post','comment'], 'default' => 'post'],
                'target_id'        => ['type' => 'INT', 'unsigned' => true],
                'user_id_fk'       => ['type' => 'INT', 'unsigned' => true],
                'emoji'            => ['type' => 'VARCHAR', 'constraint' => 10],
                'created_at'       => ['type' => 'DATETIME'],
            ]);
            $dbf->addPrimaryKey('wall_reaction_id');
            $dbf->addUniqueKey(['target_type', 'target_id', 'user_id_fk'], 'uq_wall_reaction');
            $dbf->addKey(['target_type', 'target_id']);
            $dbf->createTable('wall_reaction', true, ['ENGINE' => 'MyISAM']);
        }
    }

    // ─── posts ───────────────────────────────────────────────────────────────

    public function getPosts(int $schId, int $offset = 0, int $limit = 10): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT wp.*,
                   u.fname, u.lname, u.profile_photo AS photo,
                   (SELECT COUNT(*) FROM wall_comment wc WHERE wc.wall_post_id_fk = wp.wall_post_id AND wc.comment_status = 'Active') AS comment_count,
                   (SELECT COUNT(*) FROM wall_reaction wr WHERE wr.target_type = 'post' AND wr.target_id = wp.wall_post_id) AS reaction_count
            FROM wall_post wp
            INNER JOIN users u ON u.user_id = wp.user_id_fk
            WHERE wp.sch_id_fk = ? AND wp.post_status = 'Active'
            ORDER BY wp.created_at DESC
            LIMIT ? OFFSET ?
        ", [$schId, $limit, $offset])->getResultArray();
    }

    public function getPost(int $postId): ?array
    {
        $db = \Config\Database::connect();
        $row = $db->query("
            SELECT wp.*, u.fname, u.lname, u.profile_photo AS photo
            FROM wall_post wp
            INNER JOIN users u ON u.user_id = wp.user_id_fk
            WHERE wp.wall_post_id = ? AND wp.post_status = 'Active'
        ", [$postId])->getRowArray();
        return $row ?: null;
    }

    public function createPost(int $schId, int $userId, string $content): int
    {
        $db = \Config\Database::connect();
        $db->table('wall_post')->insert([
            'sch_id_fk'   => $schId,
            'user_id_fk'  => $userId,
            'content'     => $content,
            'post_status' => 'Active',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        return (int) $db->insertID();
    }

    public function updatePost(int $postId, string $content): void
    {
        \Config\Database::connect()->table('wall_post')->where('wall_post_id', $postId)->update([
            'content'    => $content,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function deletePost(int $postId): void
    {
        $db = \Config\Database::connect();
        $db->table('wall_post')->where('wall_post_id', $postId)->update(['post_status' => 'Deleted', 'updated_at' => date('Y-m-d H:i:s')]);
    }

    // ─── media ───────────────────────────────────────────────────────────────

    public function addMedia(int $postId, string $type, string $src, ?string $name = null): int
    {
        $db = \Config\Database::connect();
        $db->table('wall_media')->insert([
            'wall_post_id_fk' => $postId,
            'media_type'      => $type,
            'file_src'        => $src,
            'file_name'       => $name,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);
        return (int) $db->insertID();
    }

    public function getMediaForPosts(array $postIds): array
    {
        if (empty($postIds)) return [];
        $db = \Config\Database::connect();
        $in = implode(',', array_map('intval', $postIds));
        return $db->query("SELECT * FROM wall_media WHERE wall_post_id_fk IN ($in) ORDER BY wall_media_id ASC")->getResultArray();
    }

    public function getMedia(int $mediaId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('wall_media')->where('wall_media_id', $mediaId)->get()->getRowArray();
        return $row ?: null;
    }

    public function deleteMedia(int $mediaId): void
    {
        \Config\Database::connect()->table('wall_media')->where('wall_media_id', $mediaId)->delete();
    }

    // ─── comments ────────────────────────────────────────────────────────────

    /**
     * Returns all active comments for a post, ordered oldest-first.
     * Includes user info. Caller assembles thread structure.
     */
    public function getComments(int $postId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT wc.*, u.fname, u.lname, u.profile_photo AS photo,
                   (SELECT COUNT(*) FROM wall_reaction wr WHERE wr.target_type = 'comment' AND wr.target_id = wc.wall_comment_id) AS reaction_count
            FROM wall_comment wc
            INNER JOIN users u ON u.user_id = wc.user_id_fk
            WHERE wc.wall_post_id_fk = ? AND wc.comment_status = 'Active'
            ORDER BY wc.created_at ASC
        ", [$postId])->getResultArray();
    }

    public function getComment(int $commentId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('wall_comment')->where('wall_comment_id', $commentId)->get()->getRowArray();
        return $row ?: null;
    }

    public function addComment(int $postId, int $userId, string $content, ?int $parentId = null): int
    {
        $db = \Config\Database::connect();
        $db->table('wall_comment')->insert([
            'wall_post_id_fk'   => $postId,
            'parent_comment_id' => $parentId,
            'user_id_fk'        => $userId,
            'content'           => $content,
            'comment_status'    => 'Active',
            'created_at'        => date('Y-m-d H:i:s'),
        ]);
        return (int) $db->insertID();
    }

    public function deleteComment(int $commentId): void
    {
        $db = \Config\Database::connect();
        $db->table('wall_comment')->where('wall_comment_id', $commentId)->update(['comment_status' => 'Deleted']);
        // soft-delete all replies too
        $db->table('wall_comment')->where('parent_comment_id', $commentId)->update(['comment_status' => 'Deleted']);
    }

    // ─── reactions ───────────────────────────────────────────────────────────

    /**
     * Toggle a reaction. Returns ['action'=>'added'|'changed'|'removed', 'emoji'=>...].
     */
    public function toggleReaction(string $targetType, int $targetId, int $userId, string $emoji): array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('wall_reaction')
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->where('user_id_fk', $userId)
            ->get()->getRowArray();

        if (!$row) {
            $db->table('wall_reaction')->insert([
                'target_type' => $targetType,
                'target_id'   => $targetId,
                'user_id_fk'  => $userId,
                'emoji'       => $emoji,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
            return ['action' => 'added', 'emoji' => $emoji];
        }

        if ($row['emoji'] === $emoji) {
            $db->table('wall_reaction')->where('wall_reaction_id', $row['wall_reaction_id'])->delete();
            return ['action' => 'removed', 'emoji' => $emoji];
        }

        $db->table('wall_reaction')->where('wall_reaction_id', $row['wall_reaction_id'])->update(['emoji' => $emoji]);
        return ['action' => 'changed', 'emoji' => $emoji];
    }

    /**
     * Returns reaction summary: [emoji => count, ...] and my_emoji for the requesting user.
     */
    public function getReactionSummary(string $targetType, int $targetId, int $myUserId): array
    {
        $db = \Config\Database::connect();
        $rows = $db->query("
            SELECT emoji, COUNT(*) AS cnt,
                   SUM(CASE WHEN user_id_fk = ? THEN 1 ELSE 0 END) AS is_mine
            FROM wall_reaction
            WHERE target_type = ? AND target_id = ?
            GROUP BY emoji
            ORDER BY cnt DESC
        ", [$myUserId, $targetType, $targetId])->getResultArray();

        $summary  = [];
        $myEmoji  = null;
        foreach ($rows as $r) {
            $summary[$r['emoji']] = (int) $r['cnt'];
            if ($r['is_mine']) $myEmoji = $r['emoji'];
        }
        return ['summary' => $summary, 'my_emoji' => $myEmoji];
    }

    /**
     * Returns every reaction on a target with the reacting user's name and photo.
     * Grouped result: [emoji => [{name, photo}, ...], ...]
     */
    public function getReactionDetail(string $targetType, int $targetId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT wr.emoji, u.fname, u.lname, u.profile_photo AS photo
            FROM wall_reaction wr
            INNER JOIN users u ON u.user_id = wr.user_id_fk
            WHERE wr.target_type = ? AND wr.target_id = ?
            ORDER BY wr.emoji, wr.created_at DESC
        ", [$targetType, $targetId])->getResultArray();
        return $rows;
    }

    /**
     * Get reactions for multiple targets in one query.
     * Returns [target_id => ['summary' => [...], 'my_emoji' => ...], ...]
     */
    public function getReactionSummaryBulk(string $targetType, array $targetIds, int $myUserId): array
    {
        if (empty($targetIds)) return [];
        $db = \Config\Database::connect();
        $in = implode(',', array_map('intval', $targetIds));
        $rows = $db->query("
            SELECT target_id, emoji, COUNT(*) AS cnt,
                   SUM(CASE WHEN user_id_fk = ? THEN 1 ELSE 0 END) AS is_mine
            FROM wall_reaction
            WHERE target_type = ? AND target_id IN ($in)
            GROUP BY target_id, emoji
        ", [$myUserId, $targetType])->getResultArray();

        $out = [];
        foreach ($rows as $r) {
            $tid = (int) $r['target_id'];
            if (!isset($out[$tid])) $out[$tid] = ['summary' => [], 'my_emoji' => null];
            $out[$tid]['summary'][$r['emoji']] = (int) $r['cnt'];
            if ($r['is_mine']) $out[$tid]['my_emoji'] = $r['emoji'];
        }
        return $out;
    }
}
