<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductAppealModel extends Model
{
    protected $table      = 'conduct_appeals';
    protected $primaryKey = 'appeal_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'incident_id',
        'student_id',
        'appeal_reason',
        'appeal_status',
        'submitted_date',
        'reviewed_by',
        'reviewed_date',
        'review_notes',
        'points_restored',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `conduct_appeals` (
            `appeal_id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `incident_id`    INT UNSIGNED DEFAULT NULL,
            `student_id`     INT UNSIGNED DEFAULT NULL,
            `appeal_reason`  TEXT,
            `appeal_status`  VARCHAR(20)  NOT NULL DEFAULT 'Pending',
            `submitted_date` TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
            `reviewed_by`    INT UNSIGNED DEFAULT NULL,
            `reviewed_date`  DATETIME     DEFAULT NULL,
            `review_notes`   TEXT,
            `points_restored` INT         NOT NULL DEFAULT 0,
            PRIMARY KEY (`appeal_id`),
            KEY `incident_id` (`incident_id`),
            KEY `student_id` (`student_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    public function getByIncident(int $incidentId): ?array
    {
        $row = $this->where('incident_id', $incidentId)
                    ->orderBy('appeal_id', 'DESC')
                    ->first();

        return $row ?: null;
    }

    /**
     * Appeals for a school (0 = all schools), joined with incident, student and type details.
     * Filters: appeal_status.
     */
    public function getBySchool(int $schId, array $filters = []): array
    {
        $db = \Config\Database::connect();

        $sql    = "
            SELECT ca.appeal_id, ca.incident_id, ca.student_id, ca.appeal_reason,
                   ca.appeal_status, ca.submitted_date, ca.reviewed_by, ca.reviewed_date,
                   ca.review_notes, ca.points_restored,
                   ci.points_awarded, ci.incident_date,
                   adm.admission_id, adm.sch_id_fk,
                   stu.fname AS student_fname, stu.lname AS student_lname, stu.profile_photo AS student_photo,
                   reviewer.fname AS reviewer_fname, reviewer.lname AS reviewer_lname,
                   ct.type_name, ct.category, ct.is_positive, ct.severity_level
            FROM conduct_appeals ca
            INNER JOIN conduct_incidents ci ON ci.incident_id = ca.incident_id
            INNER JOIN admission adm ON adm.admission_id = ci.student_id
            INNER JOIN users      stu ON stu.user_id      = adm.user_id_fk
            LEFT JOIN  users      reviewer ON reviewer.user_id = ca.reviewed_by
            LEFT JOIN  conduct_types ct ON ct.type_id = ci.type_id_fk
            WHERE 1 = 1
        ";
        $params = [];

        if ($schId > 0) {
            $sql      .= " AND adm.sch_id_fk = ?";
            $params[] = $schId;
        }
        if (!empty($filters['appeal_status'])) {
            $sql      .= " AND ca.appeal_status = ?";
            $params[] = $filters['appeal_status'];
        }

        $sql .= " ORDER BY ca.submitted_date DESC";

        return $db->query($sql, $params)->getResultArray();
    }

    public function getByStudent(int $admissionId): array
    {
        return $this->where('student_id', $admissionId)
                    ->orderBy('submitted_date', 'DESC')
                    ->findAll();
    }
}
