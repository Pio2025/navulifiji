<?php
namespace App\Models;
use CodeIgniter\Model;

class LibraryBookModel extends Model
{
    protected $table      = 'library_book';
    protected $primaryKey = 'book_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'category_id_fk',
        'title',
        'author',
        'isbn',
        'publisher',
        'edition',
        'shelf_location',
        'total_copies',
        'available_copies',
        'description',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `library_book` (
            `book_id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`       INT UNSIGNED NOT NULL,
            `category_id_fk`  INT UNSIGNED DEFAULT NULL,
            `title`           VARCHAR(200) NOT NULL,
            `author`          VARCHAR(150) DEFAULT NULL,
            `isbn`            VARCHAR(50)  DEFAULT NULL,
            `publisher`       VARCHAR(150) DEFAULT NULL,
            `edition`         VARCHAR(50)  DEFAULT NULL,
            `shelf_location`  VARCHAR(100) DEFAULT NULL,
            `total_copies`    INT UNSIGNED NOT NULL DEFAULT 1,
            `available_copies` INT UNSIGNED NOT NULL DEFAULT 1,
            `description`     TEXT DEFAULT NULL,
            `created_at`      DATETIME DEFAULT NULL,
            `updated_at`      DATETIME DEFAULT NULL,
            PRIMARY KEY (`book_id`),
            KEY `sch_id_fk` (`sch_id_fk`),
            KEY `category_id_fk` (`category_id_fk`),
            KEY `isbn` (`isbn`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Catalog for a school (0 = all schools), optionally filtered by
     * category and/or a free-text search across title/author/isbn.
     */
    public function getBySchool(int $schId, ?int $categoryId = null, ?string $search = null): array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('library_book')
            ->select('library_book.*, library_category.category_name')
            ->join('library_category', 'library_category.category_id = library_book.category_id_fk', 'left');

        if ($schId > 0) {
            $builder->where('library_book.sch_id_fk', $schId);
        }
        if ($categoryId) {
            $builder->where('library_book.category_id_fk', $categoryId);
        }
        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('library_book.title', $search)
                ->orLike('library_book.author', $search)
                ->orLike('library_book.isbn', $search)
                ->groupEnd();
        }

        return $builder->orderBy('library_book.title', 'ASC')->get()->getResultArray();
    }

    public function getDetail(int $bookId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('library_book')
            ->select('library_book.*, library_category.category_name')
            ->join('library_category', 'library_category.category_id = library_book.category_id_fk', 'left')
            ->where('library_book.book_id', $bookId)
            ->get()->getRowArray();
        return $row ?: null;
    }

    /**
     * Books with at least one available copy (for the issue-book picker).
     */
    public function getAvailableBySchool(int $schId): array
    {
        return $this->where('sch_id_fk', $schId)
            ->where('available_copies >', 0)
            ->orderBy('title', 'ASC')
            ->findAll();
    }

    public function decrementAvailable(int $bookId): void
    {
        $db = \Config\Database::connect();
        $db->query('UPDATE library_book SET available_copies = available_copies - 1 WHERE book_id = ? AND available_copies > 0', [$bookId]);
    }

    public function incrementAvailable(int $bookId): void
    {
        $db = \Config\Database::connect();
        $db->query('UPDATE library_book SET available_copies = LEAST(available_copies + 1, total_copies) WHERE book_id = ?', [$bookId]);
    }

    public function isbnExists(int $schId, string $isbn, ?int $excludeId = null): bool
    {
        $builder = $this->where('sch_id_fk', $schId)->where('isbn', $isbn);
        if ($excludeId) {
            $builder->where('book_id !=', $excludeId);
        }
        return (bool) $builder->first();
    }
}
