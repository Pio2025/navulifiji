<?php
namespace App\Models;
use CodeIgniter\Model;

class LibraryCategoryModel extends Model
{
    protected $table      = 'library_category';
    protected $primaryKey = 'category_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'category_name',
        'description',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `library_category` (
            `category_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`     INT UNSIGNED NOT NULL,
            `category_name` VARCHAR(100) NOT NULL,
            `description`   VARCHAR(255) DEFAULT NULL,
            `created_at`    DATETIME DEFAULT NULL,
            `updated_at`    DATETIME DEFAULT NULL,
            PRIMARY KEY (`category_id`),
            UNIQUE KEY `uniq_sch_category` (`sch_id_fk`, `category_name`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Categories for a school (0 = all schools, for super admin).
     */
    public function getBySchool(int $schId): array
    {
        $builder = $this->orderBy('category_name', 'ASC');
        if ($schId > 0) {
            $builder->where('sch_id_fk', $schId);
        }
        return $builder->findAll();
    }

    public function nameExists(int $schId, string $name, ?int $excludeId = null): bool
    {
        $builder = $this->where('sch_id_fk', $schId)->where('category_name', $name);
        if ($excludeId) {
            $builder->where('category_id !=', $excludeId);
        }
        return (bool) $builder->first();
    }

    /**
     * Whether any book still references this category (blocks delete).
     */
    public function isInUse(int $categoryId): bool
    {
        $db  = \Config\Database::connect();
        $row = $db->table('library_book')->where('category_id_fk', $categoryId)->get(1)->getRowArray();
        return (bool) $row;
    }
}
