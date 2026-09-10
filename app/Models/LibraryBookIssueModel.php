<?php
namespace App\Models;
use CodeIgniter\Model;

class LibraryBookIssueModel extends Model
{
    protected $table      = 'library_book_issue';
    protected $primaryKey = 'issue_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public const LOAN_DAYS     = 14;
    public const FINE_PER_DAY  = 1.00; // FJD

    protected $allowedFields = [
        'book_id_fk',
        'borrower_admission_id_fk',
        'sch_id_fk',
        'issue_date',
        'due_date',
        'return_date',
        'fine_amount',
        'fine_paid',
        'status',
        'remarks',
        'issued_by',
        'returned_by',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `library_book_issue` (
            `issue_id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `book_id_fk`                INT UNSIGNED NOT NULL,
            `borrower_admission_id_fk`  INT UNSIGNED NOT NULL,
            `sch_id_fk`                 INT UNSIGNED NOT NULL,
            `issue_date`                DATE NOT NULL,
            `due_date`                  DATE NOT NULL,
            `return_date`               DATE DEFAULT NULL,
            `fine_amount`               DECIMAL(8,2) NOT NULL DEFAULT 0.00,
            `fine_paid`                 TINYINT(1) NOT NULL DEFAULT 0,
            `status`                    ENUM('Issued','Returned','Lost') NOT NULL DEFAULT 'Issued',
            `remarks`                   VARCHAR(255) DEFAULT NULL,
            `issued_by`                 INT UNSIGNED DEFAULT NULL,
            `returned_by`               INT UNSIGNED DEFAULT NULL,
            `created_at`                DATETIME DEFAULT NULL,
            `updated_at`                DATETIME DEFAULT NULL,
            PRIMARY KEY (`issue_id`),
            KEY `book_id_fk` (`book_id_fk`),
            KEY `borrower_admission_id_fk` (`borrower_admission_id_fk`),
            KEY `status` (`status`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    private const JOIN_SELECT = "
        lbi.*,
        lb.title, lb.author, lb.isbn,
        adm.sch_id_fk AS borrower_sch_id,
        bu.fname AS borrower_fname, bu.lname AS borrower_lname, bu.profile_photo AS borrower_photo,
        role_category.role_cat_name AS borrower_role_cat
    ";

    private const JOIN_SQL = "
        FROM library_book_issue lbi
        INNER JOIN library_book lb ON lb.book_id = lbi.book_id_fk
        INNER JOIN admission adm   ON adm.admission_id = lbi.borrower_admission_id_fk
        INNER JOIN users bu        ON bu.user_id = adm.user_id_fk
        LEFT JOIN user_role     ON user_role.user_id_fk = bu.user_id
        LEFT JOIN role          ON role.role_id = user_role.role_id_fk
        LEFT JOIN role_category ON role_category.role_cat_id = role.role_cat_id_fk
    ";

    /**
     * Currently issued (not yet returned/lost) books for a school (0 = all).
     */
    public function getIssuedBySchool(int $schId): array
    {
        $db     = \Config\Database::connect();
        $sql    = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . " WHERE lbi.status = 'Issued'";
        $params = [];
        if ($schId > 0) {
            $sql      .= ' AND lbi.sch_id_fk = ?';
            $params[] = $schId;
        }
        $sql .= ' ORDER BY lbi.due_date ASC';
        return $db->query($sql, $params)->getResultArray();
    }

    /**
     * Full issue history for a school (0 = all), most recent first.
     */
    public function getHistoryBySchool(int $schId, int $limit = 200): array
    {
        $db       = \Config\Database::connect();
        $sql      = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . ' WHERE 1 = 1';
        $params   = [];
        if ($schId > 0) {
            $sql      .= ' AND lbi.sch_id_fk = ?';
            $params[] = $schId;
        }
        $sql      .= ' ORDER BY lbi.issue_date DESC LIMIT ?';
        $params[] = $limit;
        return $db->query($sql, $params)->getResultArray();
    }

    public function getHistoryByBook(int $bookId): array
    {
        $db  = \Config\Database::connect();
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . ' WHERE lbi.book_id_fk = ? ORDER BY lbi.issue_date DESC';
        return $db->query($sql, [$bookId])->getResultArray();
    }

    /**
     * All issue rows (any status) for the given borrower admission_ids —
     * used by the self-service "My Library" page for a student and, for a
     * parent, one or more linked children.
     */
    public function getByBorrowers(array $admissionIds): array
    {
        if (empty($admissionIds)) {
            return [];
        }
        $db = \Config\Database::connect();
        $in = implode(',', array_fill(0, count($admissionIds), '?'));
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL
            . " WHERE lbi.borrower_admission_id_fk IN ($in) ORDER BY lbi.status = 'Issued' DESC, lbi.due_date ASC";
        return $db->query($sql, $admissionIds)->getResultArray();
    }

    public function getActiveCountForBorrower(int $admissionId): int
    {
        return $this->where('borrower_admission_id_fk', $admissionId)
            ->where('status', 'Issued')
            ->countAllResults();
    }

    public function getDetail(int $issueId): ?array
    {
        $db  = \Config\Database::connect();
        $sql = 'SELECT ' . self::JOIN_SELECT . self::JOIN_SQL . ' WHERE lbi.issue_id = ?';
        return $db->query($sql, [$issueId])->getRowArray() ?: null;
    }

    /**
     * Overdue-fine calculation, usable both for a not-yet-returned book
     * (estimated fine as of today) and a book being returned right now.
     */
    public static function calculateFine(string $dueDate, ?string $asOfDate = null): float
    {
        $due   = new \DateTimeImmutable($dueDate);
        $asOf  = new \DateTimeImmutable($asOfDate ?: date('Y-m-d'));
        if ($asOf <= $due) {
            return 0.00;
        }
        $daysLate = $due->diff($asOf)->days;
        return round($daysLate * self::FINE_PER_DAY, 2);
    }
}
