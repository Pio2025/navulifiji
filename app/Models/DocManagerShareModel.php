<?php
namespace App\Models;
use CodeIgniter\Model;

class DocManagerShareModel extends Model
{
    protected $table      = 'doc_manager_share';
    protected $primaryKey = 'share_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'source_type',
        'source_file_id',
        'owner_user_id_fk',
        'shared_by_user_id_fk',
        'share_type',
        'shared_with_user_id_fk',
        'token',
        'can_download',
        'expires_at',
        'revoked_at',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `doc_manager_share` (
            `share_id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `source_type`             VARCHAR(30) NOT NULL,
            `source_file_id`          INT UNSIGNED NOT NULL,
            `owner_user_id_fk`        INT UNSIGNED NOT NULL,
            `shared_by_user_id_fk`    INT UNSIGNED NOT NULL,
            `share_type`              ENUM('user','link') NOT NULL,
            `shared_with_user_id_fk`  INT UNSIGNED DEFAULT NULL,
            `token`                   VARCHAR(64) DEFAULT NULL,
            `can_download`            TINYINT(1) NOT NULL DEFAULT 1,
            `expires_at`              DATETIME DEFAULT NULL,
            `revoked_at`              DATETIME DEFAULT NULL,
            `created_at`              DATETIME DEFAULT NULL,
            `updated_at`              DATETIME DEFAULT NULL,
            PRIMARY KEY (`share_id`),
            UNIQUE KEY `token` (`token`),
            KEY `source` (`source_type`, `source_file_id`),
            KEY `owner_user_id_fk` (`owner_user_id_fk`),
            KEY `shared_with_user_id_fk` (`shared_with_user_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Active (not revoked, not expired) shares for one source file, newest first.
     */
    public function getActiveForSource(string $sourceType, int $sourceFileId): array
    {
        $db  = \Config\Database::connect();
        $sql = "SELECT s.*, u.fname AS shared_with_fname, u.lname AS shared_with_lname
                FROM doc_manager_share s
                LEFT JOIN users u ON u.user_id = s.shared_with_user_id_fk
                WHERE s.source_type = ? AND s.source_file_id = ?
                  AND s.revoked_at IS NULL
                  AND (s.expires_at IS NULL OR s.expires_at > NOW())
                ORDER BY s.share_id DESC";
        return $db->query($sql, [$sourceType, $sourceFileId])->getResultArray();
    }

    public function findActiveByToken(string $token): ?array
    {
        $db  = \Config\Database::connect();
        $sql = "SELECT * FROM doc_manager_share
                WHERE token = ? AND share_type = 'link' AND revoked_at IS NULL
                  AND (expires_at IS NULL OR expires_at > NOW())";
        return $db->query($sql, [$token])->getRowArray() ?: null;
    }

    /**
     * Documents shared directly with the given user (in-app shares), newest first.
     */
    public function getSharedWithUser(int $userId): array
    {
        $db  = \Config\Database::connect();
        $sql = "SELECT s.*, u.fname AS owner_fname, u.lname AS owner_lname
                FROM doc_manager_share s
                INNER JOIN users u ON u.user_id = s.owner_user_id_fk
                WHERE s.share_type = 'user' AND s.shared_with_user_id_fk = ?
                  AND s.revoked_at IS NULL
                  AND (s.expires_at IS NULL OR s.expires_at > NOW())
                ORDER BY s.share_id DESC";
        return $db->query($sql, [$userId])->getResultArray();
    }

    public function revoke(int $shareId): void
    {
        $this->update($shareId, ['revoked_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function generateToken(): string
    {
        do {
            $token = bin2hex(random_bytes(24));
        } while ($this->where('token', $token)->first());

        return $token;
    }
}
