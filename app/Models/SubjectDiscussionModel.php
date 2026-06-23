<?php
namespace App\Models;
use CodeIgniter\Model;

class SubjectDiscussionModel extends Model
{
    protected $table      = 'subject_discussion';
    protected $primaryKey = 'sd_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'class_sub_id_fk', 'author', 'message', 'created_at', 'post_status',
    ];

    public function ensureTables(): void
    {
        $db    = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('subject_discussion')) {
            $forge->addField([
                'sd_id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'class_sub_id_fk' => ['type' => 'INT', 'null' => false],
                'author'          => ['type' => 'INT', 'null' => false],
                'message'         => ['type' => 'LONGTEXT', 'null' => true],
                'created_at'      => ['type' => 'DATETIME', 'null' => true],
                'post_status'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            ]);
            $forge->addPrimaryKey('sd_id');
            $forge->createTable('subject_discussion', true);
        }

        if (!$db->tableExists('subject_discussion_photo')) {
            $forge->addField([
                'photo_id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'sd_id_fk'    => ['type' => 'INT', 'null' => false],
                'photo_path'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
                'photo_order' => ['type' => 'INT', 'default' => 0],
            ]);
            $forge->addPrimaryKey('photo_id');
            $forge->createTable('subject_discussion_photo', true);
        }

        if (!$db->tableExists('subject_discussion_like')) {
            $forge->addField([
                'like_id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'sd_id_fk'   => ['type' => 'INT', 'null' => false],
                'user_id_fk' => ['type' => 'INT', 'null' => false],
                'like_type'  => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false],
            ]);
            $forge->addPrimaryKey('like_id');
            $forge->addUniqueKey(['sd_id_fk', 'user_id_fk']);
            $forge->createTable('subject_discussion_like', true);
        }

        if (!$db->tableExists('subject_discussion_comment')) {
            $forge->addField([
                'sdc_id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'sd_id_fk'       => ['type' => 'INT', 'null' => false],
                'author'         => ['type' => 'INT', 'null' => false],
                'comment'        => ['type' => 'LONGTEXT', 'null' => false],
                'created_at'     => ['type' => 'DATETIME', 'null' => true],
                'comment_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
            ]);
            $forge->addPrimaryKey('sdc_id');
            $forge->createTable('subject_discussion_comment', true);
        }

        if (!$db->tableExists('subject_discussion_comment_like')) {
            $forge->addField([
                'clike_id'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'sdc_id_fk'  => ['type' => 'INT', 'null' => false],
                'user_id_fk' => ['type' => 'INT', 'null' => false],
                'like_type'  => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false, 'default' => 'like'],
            ]);
            $forge->addPrimaryKey('clike_id');
            $forge->addUniqueKey(['sdc_id_fk', 'user_id_fk']);
            $forge->createTable('subject_discussion_comment_like', true);
        }

        if (!$db->tableExists('subject_discussion_comment_reply')) {
            $forge->addField([
                'sdcr_id'      => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'sdc_id_fk'    => ['type' => 'INT', 'null' => false],
                'author'       => ['type' => 'INT', 'null' => false],
                'reply'        => ['type' => 'LONGTEXT', 'null' => false],
                'created_at'   => ['type' => 'DATETIME', 'null' => true],
                'reply_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
            ]);
            $forge->addPrimaryKey('sdcr_id');
            $forge->createTable('subject_discussion_comment_reply', true);
        }

        if (!$db->tableExists('subject_discussion_comment_reply_like')) {
            $forge->addField([
                'rlike_id'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'sdcr_id_fk' => ['type' => 'INT', 'null' => false],
                'user_id_fk' => ['type' => 'INT', 'null' => false],
                'like_type'  => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false, 'default' => 'like'],
            ]);
            $forge->addPrimaryKey('rlike_id');
            $forge->addUniqueKey(['sdcr_id_fk', 'user_id_fk']);
            $forge->createTable('subject_discussion_comment_reply_like', true);
        }
    }

    public function getPosts(int $classSubId, int $userId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                sd.sd_id,
                sd.message,
                sd.created_at,
                sd.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT COUNT(*) FROM subject_discussion_comment sdc
                 WHERE sdc.sd_id_fk = sd.sd_id AND sdc.comment_status = 'Active') AS comment_count,
                (SELECT COUNT(*) FROM subject_discussion_like sdl
                 WHERE sdl.sd_id_fk = sd.sd_id AND sdl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM subject_discussion_like sdl
                 WHERE sdl.sd_id_fk = sd.sd_id AND sdl.like_type = 'dislike') AS dislike_count,
                (SELECT sdl2.like_type FROM subject_discussion_like sdl2
                 WHERE sdl2.sd_id_fk = sd.sd_id AND sdl2.user_id_fk = ?
                 LIMIT 1) AS user_reaction
            FROM subject_discussion sd
            INNER JOIN users u ON u.user_id = sd.author
            WHERE sd.class_sub_id_fk = ? AND sd.post_status = 1
            ORDER BY sd.created_at DESC
        ", [$userId, $classSubId])->getResultArray();

        foreach ($rows as &$row) {
            $row['photos']   = $this->getPhotos((int) $row['sd_id']);
            $row['comments'] = $this->getComments((int) $row['sd_id'], $userId);
        }
        return $rows;
    }

    public function getPhotos(int $sdId): array
    {
        return \Config\Database::connect()
            ->table('subject_discussion_photo')
            ->where('sd_id_fk', $sdId)
            ->orderBy('photo_order', 'ASC')
            ->get()->getResultArray();
    }

    public function getComments(int $sdId, int $userId = 0): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                sdc.sdc_id,
                sdc.comment,
                sdc.created_at,
                sdc.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT COUNT(*) FROM subject_discussion_comment_like cl
                 WHERE cl.sdc_id_fk = sdc.sdc_id AND cl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM subject_discussion_comment_like cl
                 WHERE cl.sdc_id_fk = sdc.sdc_id AND cl.like_type = 'dislike') AS dislike_count,
                (SELECT cl2.like_type FROM subject_discussion_comment_like cl2
                 WHERE cl2.sdc_id_fk = sdc.sdc_id AND cl2.user_id_fk = ? LIMIT 1) AS user_reaction
            FROM subject_discussion_comment sdc
            INNER JOIN users u ON u.user_id = sdc.author
            WHERE sdc.sd_id_fk = ? AND sdc.comment_status = 'Active'
            ORDER BY sdc.created_at ASC
        ", [$userId, $sdId])->getResultArray();

        foreach ($rows as &$row) {
            $row['replies'] = $this->getReplies((int) $row['sdc_id'], $userId);
        }
        return $rows;
    }

    public function getReplies(int $sdcId, int $userId = 0): array
    {
        return \Config\Database::connect()->query("
            SELECT
                r.sdcr_id,
                r.reply,
                r.created_at,
                r.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT COUNT(*) FROM subject_discussion_comment_reply_like rl
                 WHERE rl.sdcr_id_fk = r.sdcr_id AND rl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM subject_discussion_comment_reply_like rl
                 WHERE rl.sdcr_id_fk = r.sdcr_id AND rl.like_type = 'dislike') AS dislike_count,
                (SELECT rl2.like_type FROM subject_discussion_comment_reply_like rl2
                 WHERE rl2.sdcr_id_fk = r.sdcr_id AND rl2.user_id_fk = ? LIMIT 1) AS user_reaction
            FROM subject_discussion_comment_reply r
            INNER JOIN users u ON u.user_id = r.author
            WHERE r.sdc_id_fk = ? AND r.reply_status = 'Active'
            ORDER BY r.created_at ASC
        ", [$userId, $sdcId])->getResultArray();
    }

    public function togglePostLike(int $sdId, int $userId, string $type): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('subject_discussion_like')
            ->where('sd_id_fk', $sdId)->where('user_id_fk', $userId)->get()->getRowArray();

        if (!$existing) {
            $db->table('subject_discussion_like')->insert(['sd_id_fk' => $sdId, 'user_id_fk' => $userId, 'like_type' => $type]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('subject_discussion_like')->where('sd_id_fk', $sdId)->where('user_id_fk', $userId)->delete();
            $reaction = null;
        } else {
            $db->table('subject_discussion_like')->where('sd_id_fk', $sdId)->where('user_id_fk', $userId)->update(['like_type' => $type]);
            $reaction = $type;
        }

        return [
            'reaction' => $reaction,
            'likes'    => (int) $db->table('subject_discussion_like')->where('sd_id_fk', $sdId)->where('like_type', 'like')->countAllResults(),
            'dislikes' => (int) $db->table('subject_discussion_like')->where('sd_id_fk', $sdId)->where('like_type', 'dislike')->countAllResults(),
        ];
    }

    public function toggleCommentLike(int $sdcId, int $userId, string $type): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('subject_discussion_comment_like')
            ->where('sdc_id_fk', $sdcId)->where('user_id_fk', $userId)->get()->getRowArray();

        if (!$existing) {
            $db->table('subject_discussion_comment_like')->insert(['sdc_id_fk' => $sdcId, 'user_id_fk' => $userId, 'like_type' => $type]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('subject_discussion_comment_like')->where('sdc_id_fk', $sdcId)->where('user_id_fk', $userId)->delete();
            $reaction = null;
        } else {
            $db->table('subject_discussion_comment_like')->where('sdc_id_fk', $sdcId)->where('user_id_fk', $userId)->update(['like_type' => $type]);
            $reaction = $type;
        }

        return [
            'reaction' => $reaction,
            'likes'    => (int) $db->table('subject_discussion_comment_like')->where('sdc_id_fk', $sdcId)->where('like_type', 'like')->countAllResults(),
            'dislikes' => (int) $db->table('subject_discussion_comment_like')->where('sdc_id_fk', $sdcId)->where('like_type', 'dislike')->countAllResults(),
        ];
    }

    public function getPostReactions(int $sdId): array
    {
        return \Config\Database::connect()->query("
            SELECT sdl.like_type, CONCAT(u.fname,' ',u.lname) AS name, u.profile_photo AS photo
            FROM subject_discussion_like sdl
            INNER JOIN users u ON u.user_id = sdl.user_id_fk
            WHERE sdl.sd_id_fk = ?
            ORDER BY sdl.like_type ASC, u.fname ASC
        ", [$sdId])->getResultArray();
    }

    public function getCommentReactions(int $sdcId): array
    {
        return \Config\Database::connect()->query("
            SELECT cl.like_type, CONCAT(u.fname,' ',u.lname) AS name, u.profile_photo AS photo
            FROM subject_discussion_comment_like cl
            INNER JOIN users u ON u.user_id = cl.user_id_fk
            WHERE cl.sdc_id_fk = ?
            ORDER BY cl.like_type ASC, u.fname ASC
        ", [$sdcId])->getResultArray();
    }

    public function getReplyReactions(int $sdcrId): array
    {
        return \Config\Database::connect()->query("
            SELECT rl.like_type, CONCAT(u.fname,' ',u.lname) AS name, u.profile_photo AS photo
            FROM subject_discussion_comment_reply_like rl
            INNER JOIN users u ON u.user_id = rl.user_id_fk
            WHERE rl.sdcr_id_fk = ?
            ORDER BY rl.like_type ASC, u.fname ASC
        ", [$sdcrId])->getResultArray();
    }

    public function toggleReplyLike(int $sdcrId, int $userId, string $type): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('subject_discussion_comment_reply_like')
            ->where('sdcr_id_fk', $sdcrId)->where('user_id_fk', $userId)->get()->getRowArray();

        if (!$existing) {
            $db->table('subject_discussion_comment_reply_like')->insert(['sdcr_id_fk' => $sdcrId, 'user_id_fk' => $userId, 'like_type' => $type]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('subject_discussion_comment_reply_like')->where('sdcr_id_fk', $sdcrId)->where('user_id_fk', $userId)->delete();
            $reaction = null;
        } else {
            $db->table('subject_discussion_comment_reply_like')->where('sdcr_id_fk', $sdcrId)->where('user_id_fk', $userId)->update(['like_type' => $type]);
            $reaction = $type;
        }

        return [
            'reaction' => $reaction,
            'likes'    => (int) $db->table('subject_discussion_comment_reply_like')->where('sdcr_id_fk', $sdcrId)->where('like_type', 'like')->countAllResults(),
            'dislikes' => (int) $db->table('subject_discussion_comment_reply_like')->where('sdcr_id_fk', $sdcrId)->where('like_type', 'dislike')->countAllResults(),
        ];
    }
}
