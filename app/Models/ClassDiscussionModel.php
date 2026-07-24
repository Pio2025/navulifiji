<?php
namespace App\Models;
use CodeIgniter\Model;

class ClassDiscussionModel extends Model
{
    protected $table      = 'class_discussion';
    protected $primaryKey = 'cd_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'class_id_fk', 'author', 'message', 'created_at', 'post_status',
    ];

    public function ensureTables(): void
    {
        $db    = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('class_discussion')) {
            $forge->addField([
                'cd_id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'class_id_fk' => ['type' => 'INT', 'null' => false],
                'author'      => ['type' => 'INT', 'null' => false],
                'message'     => ['type' => 'LONGTEXT', 'null' => true],
                'created_at'  => ['type' => 'DATETIME', 'null' => true],
                'post_status' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            ]);
            $forge->addPrimaryKey('cd_id');
            $forge->createTable('class_discussion', true);
        }

        if (!$db->tableExists('class_discussion_photo')) {
            $forge->addField([
                'photo_id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'cd_id_fk'    => ['type' => 'INT', 'null' => false],
                'photo_path'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
                'photo_order' => ['type' => 'INT', 'default' => 0],
            ]);
            $forge->addPrimaryKey('photo_id');
            $forge->createTable('class_discussion_photo', true);
        }

        if (!$db->tableExists('class_discussion_like')) {
            $forge->addField([
                'like_id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'cd_id_fk'   => ['type' => 'INT', 'null' => false],
                'user_id_fk' => ['type' => 'INT', 'null' => false],
                'like_type'  => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false],
            ]);
            $forge->addPrimaryKey('like_id');
            $forge->addUniqueKey(['cd_id_fk', 'user_id_fk']);
            $forge->createTable('class_discussion_like', true);
        }

        if (!$db->tableExists('class_discussion_comment')) {
            $forge->addField([
                'cdc_id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'cd_id_fk'       => ['type' => 'INT', 'null' => false],
                'author'         => ['type' => 'INT', 'null' => false],
                'comment'        => ['type' => 'LONGTEXT', 'null' => false],
                'created_at'     => ['type' => 'DATETIME', 'null' => true],
                'comment_status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
            ]);
            $forge->addPrimaryKey('cdc_id');
            $forge->createTable('class_discussion_comment', true);
        }

        if (!$db->tableExists('class_discussion_comment_like')) {
            $forge->addField([
                'clike_id'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'cdc_id_fk'  => ['type' => 'INT', 'null' => false],
                'user_id_fk' => ['type' => 'INT', 'null' => false],
                'like_type'  => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false, 'default' => 'like'],
            ]);
            $forge->addPrimaryKey('clike_id');
            $forge->addUniqueKey(['cdc_id_fk', 'user_id_fk']);
            $forge->createTable('class_discussion_comment_like', true);
        }

        if (!$db->tableExists('class_discussion_comment_reply')) {
            $forge->addField([
                'cdcr_id'            => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'cdc_id_fk'          => ['type' => 'INT', 'null' => false],
                'parent_reply_id_fk' => ['type' => 'INT', 'null' => true],
                'author'             => ['type' => 'INT', 'null' => false],
                'reply'              => ['type' => 'LONGTEXT', 'null' => false],
                'created_at'         => ['type' => 'DATETIME', 'null' => true],
                'reply_status'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'Active'],
            ]);
            $forge->addPrimaryKey('cdcr_id');
            $forge->createTable('class_discussion_comment_reply', true);
        } elseif (!$db->fieldExists('parent_reply_id_fk', 'class_discussion_comment_reply')) {
            $db->query("ALTER TABLE class_discussion_comment_reply ADD COLUMN parent_reply_id_fk INT NULL AFTER cdc_id_fk");
        }

        if (!$db->tableExists('class_discussion_comment_reply_like')) {
            $forge->addField([
                'rlike_id'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'cdcr_id_fk' => ['type' => 'INT', 'null' => false],
                'user_id_fk' => ['type' => 'INT', 'null' => false],
                'like_type'  => ['type' => 'ENUM', 'constraint' => ['like', 'dislike'], 'null' => false, 'default' => 'like'],
            ]);
            $forge->addPrimaryKey('rlike_id');
            $forge->addUniqueKey(['cdcr_id_fk', 'user_id_fk']);
            $forge->createTable('class_discussion_comment_reply_like', true);
        }
    }

    public function getPosts(int $classId, int $userId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                cd.cd_id,
                cd.message,
                cd.created_at,
                cd.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT rc_p.role_cat_id FROM user_role ur_p
                 INNER JOIN role ro_p ON ro_p.role_id = ur_p.role_id_fk
                 INNER JOIN role_category rc_p ON rc_p.role_cat_id = ro_p.role_cat_id_fk
                 WHERE ur_p.user_id_fk = cd.author AND ur_p.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_id,
                (SELECT rc_p.role_cat_name FROM user_role ur_p
                 INNER JOIN role ro_p ON ro_p.role_id = ur_p.role_id_fk
                 INNER JOIN role_category rc_p ON rc_p.role_cat_id = ro_p.role_cat_id_fk
                 WHERE ur_p.user_id_fk = cd.author AND ur_p.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_name,
                (SELECT COUNT(*) FROM class_discussion_comment cdc
                 WHERE cdc.cd_id_fk = cd.cd_id AND cdc.comment_status = 'Active') AS comment_count,
                (SELECT COUNT(*) FROM class_discussion_like cdl
                 WHERE cdl.cd_id_fk = cd.cd_id AND cdl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM class_discussion_like cdl
                 WHERE cdl.cd_id_fk = cd.cd_id AND cdl.like_type = 'dislike') AS dislike_count,
                (SELECT cdl2.like_type FROM class_discussion_like cdl2
                 WHERE cdl2.cd_id_fk = cd.cd_id AND cdl2.user_id_fk = ?
                 LIMIT 1) AS user_reaction
            FROM class_discussion cd
            INNER JOIN users u ON u.user_id = cd.author
            WHERE cd.class_id_fk = ? AND cd.post_status = 1
            ORDER BY cd.created_at DESC
        ", [$userId, $classId])->getResultArray();

        foreach ($rows as &$row) {
            $row['photos']   = $this->getPhotos((int) $row['cd_id']);
            $row['comments'] = $this->getComments((int) $row['cd_id'], $userId);
        }
        return $rows;
    }

    public function getPhotos(int $cdId): array
    {
        return \Config\Database::connect()
            ->table('class_discussion_photo')
            ->where('cd_id_fk', $cdId)
            ->orderBy('photo_order', 'ASC')
            ->get()->getResultArray();
    }

    public function getComments(int $cdId, int $userId = 0): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                cdc.cdc_id,
                cdc.comment,
                cdc.created_at,
                cdc.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT rc_c.role_cat_id FROM user_role ur_c
                 INNER JOIN role ro_c ON ro_c.role_id = ur_c.role_id_fk
                 INNER JOIN role_category rc_c ON rc_c.role_cat_id = ro_c.role_cat_id_fk
                 WHERE ur_c.user_id_fk = cdc.author AND ur_c.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_id,
                (SELECT rc_c.role_cat_name FROM user_role ur_c
                 INNER JOIN role ro_c ON ro_c.role_id = ur_c.role_id_fk
                 INNER JOIN role_category rc_c ON rc_c.role_cat_id = ro_c.role_cat_id_fk
                 WHERE ur_c.user_id_fk = cdc.author AND ur_c.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_name,
                (SELECT COUNT(*) FROM class_discussion_comment_like cl
                 WHERE cl.cdc_id_fk = cdc.cdc_id AND cl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM class_discussion_comment_like cl
                 WHERE cl.cdc_id_fk = cdc.cdc_id AND cl.like_type = 'dislike') AS dislike_count,
                (SELECT cl2.like_type FROM class_discussion_comment_like cl2
                 WHERE cl2.cdc_id_fk = cdc.cdc_id AND cl2.user_id_fk = ? LIMIT 1) AS user_reaction
            FROM class_discussion_comment cdc
            INNER JOIN users u ON u.user_id = cdc.author
            WHERE cdc.cd_id_fk = ? AND cdc.comment_status = 'Active'
            ORDER BY cdc.created_at ASC
        ", [$userId, $cdId])->getResultArray();

        foreach ($rows as &$row) {
            $row['replies'] = $this->getReplies((int) $row['cdc_id'], $userId);
        }
        return $rows;
    }

    /**
     * Returns replies for a comment as a nested tree: top-level replies (direct
     * replies to the comment) each carry a 'replies' array of replies-to-that-reply,
     * recursively, so the mobile app can render an unlimited-depth reply thread.
     */
    public function getReplies(int $cdcId, int $userId = 0): array
    {
        $rows = \Config\Database::connect()->query("
            SELECT
                r.cdcr_id,
                r.parent_reply_id_fk,
                r.reply,
                r.created_at,
                r.author AS author_id,
                CONCAT(u.fname, ' ', u.lname) AS author_name,
                u.profile_photo AS author_photo,
                (SELECT rc_r.role_cat_id FROM user_role ur_r
                 INNER JOIN role ro_r ON ro_r.role_id = ur_r.role_id_fk
                 INNER JOIN role_category rc_r ON rc_r.role_cat_id = ro_r.role_cat_id_fk
                 WHERE ur_r.user_id_fk = r.author AND ur_r.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_id,
                (SELECT rc_r.role_cat_name FROM user_role ur_r
                 INNER JOIN role ro_r ON ro_r.role_id = ur_r.role_id_fk
                 INNER JOIN role_category rc_r ON rc_r.role_cat_id = ro_r.role_cat_id_fk
                 WHERE ur_r.user_id_fk = r.author AND ur_r.user_role_status = 'Active'
                 LIMIT 1) AS author_role_cat_name,
                (SELECT COUNT(*) FROM class_discussion_comment_reply_like rl
                 WHERE rl.cdcr_id_fk = r.cdcr_id AND rl.like_type = 'like') AS like_count,
                (SELECT COUNT(*) FROM class_discussion_comment_reply_like rl
                 WHERE rl.cdcr_id_fk = r.cdcr_id AND rl.like_type = 'dislike') AS dislike_count,
                (SELECT rl2.like_type FROM class_discussion_comment_reply_like rl2
                 WHERE rl2.cdcr_id_fk = r.cdcr_id AND rl2.user_id_fk = ? LIMIT 1) AS user_reaction
            FROM class_discussion_comment_reply r
            INNER JOIN users u ON u.user_id = r.author
            WHERE r.cdc_id_fk = ? AND r.reply_status = 'Active'
            ORDER BY r.created_at ASC
        ", [$userId, $cdcId])->getResultArray();

        $byId = [];
        foreach ($rows as &$row) {
            $row['parent_reply_id_fk'] = $row['parent_reply_id_fk'] !== null ? (int) $row['parent_reply_id_fk'] : null;
            $row['replies']            = [];
            $byId[$row['cdcr_id']]      = &$row;
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

    public function togglePostLike(int $cdId, int $userId, string $type): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('class_discussion_like')
            ->where('cd_id_fk', $cdId)->where('user_id_fk', $userId)->get()->getRowArray();

        if (!$existing) {
            $db->table('class_discussion_like')->insert(['cd_id_fk' => $cdId, 'user_id_fk' => $userId, 'like_type' => $type]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('class_discussion_like')->where('cd_id_fk', $cdId)->where('user_id_fk', $userId)->delete();
            $reaction = null;
        } else {
            $db->table('class_discussion_like')->where('cd_id_fk', $cdId)->where('user_id_fk', $userId)->update(['like_type' => $type]);
            $reaction = $type;
        }

        return [
            'reaction' => $reaction,
            'likes'    => (int) $db->table('class_discussion_like')->where('cd_id_fk', $cdId)->where('like_type', 'like')->countAllResults(),
            'dislikes' => (int) $db->table('class_discussion_like')->where('cd_id_fk', $cdId)->where('like_type', 'dislike')->countAllResults(),
        ];
    }

    public function toggleCommentLike(int $cdcId, int $userId, string $type): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('class_discussion_comment_like')
            ->where('cdc_id_fk', $cdcId)->where('user_id_fk', $userId)->get()->getRowArray();

        if (!$existing) {
            $db->table('class_discussion_comment_like')->insert(['cdc_id_fk' => $cdcId, 'user_id_fk' => $userId, 'like_type' => $type]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('class_discussion_comment_like')->where('cdc_id_fk', $cdcId)->where('user_id_fk', $userId)->delete();
            $reaction = null;
        } else {
            $db->table('class_discussion_comment_like')->where('cdc_id_fk', $cdcId)->where('user_id_fk', $userId)->update(['like_type' => $type]);
            $reaction = $type;
        }

        return [
            'reaction' => $reaction,
            'likes'    => (int) $db->table('class_discussion_comment_like')->where('cdc_id_fk', $cdcId)->where('like_type', 'like')->countAllResults(),
            'dislikes' => (int) $db->table('class_discussion_comment_like')->where('cdc_id_fk', $cdcId)->where('like_type', 'dislike')->countAllResults(),
        ];
    }

    public function getPostReactions(int $cdId): array
    {
        return \Config\Database::connect()->query("
            SELECT cdl.like_type, CONCAT(u.fname,' ',u.lname) AS name, u.profile_photo AS photo
            FROM class_discussion_like cdl
            INNER JOIN users u ON u.user_id = cdl.user_id_fk
            WHERE cdl.cd_id_fk = ?
            ORDER BY cdl.like_type ASC, u.fname ASC
        ", [$cdId])->getResultArray();
    }

    public function getCommentReactions(int $cdcId): array
    {
        return \Config\Database::connect()->query("
            SELECT cl.like_type, CONCAT(u.fname,' ',u.lname) AS name, u.profile_photo AS photo
            FROM class_discussion_comment_like cl
            INNER JOIN users u ON u.user_id = cl.user_id_fk
            WHERE cl.cdc_id_fk = ?
            ORDER BY cl.like_type ASC, u.fname ASC
        ", [$cdcId])->getResultArray();
    }

    public function getReplyReactions(int $cdcrId): array
    {
        return \Config\Database::connect()->query("
            SELECT rl.like_type, CONCAT(u.fname,' ',u.lname) AS name, u.profile_photo AS photo
            FROM class_discussion_comment_reply_like rl
            INNER JOIN users u ON u.user_id = rl.user_id_fk
            WHERE rl.cdcr_id_fk = ?
            ORDER BY rl.like_type ASC, u.fname ASC
        ", [$cdcrId])->getResultArray();
    }

    public function toggleReplyLike(int $cdcrId, int $userId, string $type): array
    {
        $db       = \Config\Database::connect();
        $existing = $db->table('class_discussion_comment_reply_like')
            ->where('cdcr_id_fk', $cdcrId)->where('user_id_fk', $userId)->get()->getRowArray();

        if (!$existing) {
            $db->table('class_discussion_comment_reply_like')->insert(['cdcr_id_fk' => $cdcrId, 'user_id_fk' => $userId, 'like_type' => $type]);
            $reaction = $type;
        } elseif ($existing['like_type'] === $type) {
            $db->table('class_discussion_comment_reply_like')->where('cdcr_id_fk', $cdcrId)->where('user_id_fk', $userId)->delete();
            $reaction = null;
        } else {
            $db->table('class_discussion_comment_reply_like')->where('cdcr_id_fk', $cdcrId)->where('user_id_fk', $userId)->update(['like_type' => $type]);
            $reaction = $type;
        }

        return [
            'reaction' => $reaction,
            'likes'    => (int) $db->table('class_discussion_comment_reply_like')->where('cdcr_id_fk', $cdcrId)->where('like_type', 'like')->countAllResults(),
            'dislikes' => (int) $db->table('class_discussion_comment_reply_like')->where('cdcr_id_fk', $cdcrId)->where('like_type', 'dislike')->countAllResults(),
        ];
    }
}
