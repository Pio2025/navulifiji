<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductIncidentModel extends Model
{
    protected $table      = 'conduct_incidents';
    protected $primaryKey = 'incident_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'student_id',
        'staff_id',
        'type_id_fk',
        'points_awarded',
        'incident_description',
        'incident_date',
        'location',
        'is_resolved',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `conduct_incidents` (
            `incident_id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `student_id`           INT UNSIGNED NOT NULL,
            `staff_id`             INT UNSIGNED NOT NULL,
            `type_id_fk`           INT UNSIGNED DEFAULT NULL,
            `points_awarded`       INT          NOT NULL DEFAULT 0,
            `incident_description` TEXT,
            `incident_date`        TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
            `location`             VARCHAR(100) DEFAULT NULL,
            `is_resolved`          TINYINT(1)   NOT NULL DEFAULT 0,
            PRIMARY KEY (`incident_id`),
            KEY `student_id` (`student_id`),
            KEY `type_id_fk` (`type_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Incidents for a school (0 = all schools), joined with student, staff and type details.
     * Filters: student_id (admission_id), category, severity_level, is_positive, is_resolved, date_from, date_to.
     */
    public function getBySchool(int $schId, array $filters = []): array
    {
        $db = \Config\Database::connect();

        $sql    = "
            SELECT ci.incident_id, ci.student_id, ci.staff_id, ci.type_id_fk,
                   ci.points_awarded, ci.incident_description, ci.incident_date,
                   ci.location, ci.is_resolved,
                   adm.admission_id, adm.sch_id_fk,
                   stu.fname AS student_fname, stu.lname AS student_lname, stu.profile_photo AS student_photo,
                   staff.fname AS staff_fname, staff.lname AS staff_lname,
                   ct.type_name, ct.category, ct.is_positive, ct.severity_level
            FROM conduct_incidents ci
            INNER JOIN admission adm ON adm.admission_id = ci.student_id
            INNER JOIN users      stu ON stu.user_id      = adm.user_id_fk
            LEFT JOIN  users      staff ON staff.user_id  = ci.staff_id
            LEFT JOIN  conduct_types ct ON ct.type_id      = ci.type_id_fk
            WHERE 1 = 1
        ";
        $params = [];

        if ($schId > 0) {
            $sql      .= " AND adm.sch_id_fk = ?";
            $params[] = $schId;
        }
        if (!empty($filters['student_id'])) {
            $sql      .= " AND ci.student_id = ?";
            $params[] = (int) $filters['student_id'];
        }
        if (!empty($filters['category'])) {
            $sql      .= " AND ct.category = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['severity_level'])) {
            $sql      .= " AND ct.severity_level = ?";
            $params[] = $filters['severity_level'];
        }
        if (isset($filters['is_positive']) && $filters['is_positive'] !== '') {
            $sql      .= " AND ct.is_positive = ?";
            $params[] = (int) $filters['is_positive'];
        }
        if (isset($filters['is_resolved']) && $filters['is_resolved'] !== '') {
            $sql      .= " AND ci.is_resolved = ?";
            $params[] = (int) $filters['is_resolved'];
        }
        if (!empty($filters['date_from'])) {
            $sql      .= " AND DATE(ci.incident_date) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql      .= " AND DATE(ci.incident_date) <= ?";
            $params[] = $filters['date_to'];
        }

        $sql .= " ORDER BY ci.incident_date DESC";

        return $db->query($sql, $params)->getResultArray();
    }

    /**
     * Active students (admission_id + name) for the student picker, scoped to a school (null = all schools).
     */
    public function getActiveStudentsBySchool(?int $schId = null): array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('admission')
            ->select('
                admission.admission_id, admission.sch_id_fk,
                users.fname, users.lname, users.oname,
                school.sch_name
            ')
            ->join('users',         'users.user_id             = admission.user_id_fk',   'inner')
            ->join('user_role',     'user_role.user_id_fk      = users.user_id',          'inner')
            ->join('role',          'role.role_id              = user_role.role_id_fk',   'inner')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',    'inner')
            ->join('school',        'school.sch_id             = admission.sch_id_fk',    'left')
            ->where('admission.admission_status', 'Active')
            ->where('user_role.user_role_status', 'Active')
            ->where('role_category.role_cat_id', 4); // ── Student only ──

        if ($schId !== null) {
            $builder->where('admission.sch_id_fk', $schId);
        }

        return $builder->orderBy('users.fname', 'ASC')->get()->getResultArray();
    }

    public function getDetail(int $incidentId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->query(
            "SELECT ci.incident_id, ci.student_id, ci.staff_id, ci.type_id_fk,
                    ci.points_awarded, ci.incident_description, ci.incident_date,
                    ci.location, ci.is_resolved,
                    adm.admission_id, adm.sch_id_fk, adm.user_id_fk AS student_user_id,
                    stu.fname AS student_fname, stu.lname AS student_lname, stu.profile_photo AS student_photo,
                    staff.fname AS staff_fname, staff.lname AS staff_lname,
                    ct.type_name, ct.category, ct.is_positive, ct.default_points, ct.severity_level
             FROM conduct_incidents ci
             INNER JOIN admission adm ON adm.admission_id = ci.student_id
             INNER JOIN users      stu ON stu.user_id      = adm.user_id_fk
             LEFT JOIN  users      staff ON staff.user_id  = ci.staff_id
             LEFT JOIN  conduct_types ct ON ct.type_id      = ci.type_id_fk
             WHERE ci.incident_id = ?",
            [$incidentId]
        )->getRowArray();

        return $row ?: null;
    }

    public function getByStudent(int $admissionId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT ci.incident_id, ci.student_id, ci.staff_id, ci.type_id_fk,
                    ci.points_awarded, ci.incident_description, ci.incident_date,
                    ci.location, ci.is_resolved,
                    staff.fname AS staff_fname, staff.lname AS staff_lname,
                    ct.type_name, ct.category, ct.is_positive, ct.severity_level
             FROM conduct_incidents ci
             LEFT JOIN users         staff ON staff.user_id = ci.staff_id
             LEFT JOIN conduct_types ct    ON ct.type_id     = ci.type_id_fk
             WHERE ci.student_id = ?
             ORDER BY ci.incident_date DESC",
            [$admissionId]
        )->getResultArray();
    }

    /**
     * Per-student points totals for a school (0 = all schools), optionally filtered by year.
     */
    public function getPointsSummaryBySchool(int $schId, ?int $year = null): array
    {
        $db = \Config\Database::connect();

        $sql    = "
            SELECT adm.admission_id, adm.sch_id_fk,
                   stu.fname AS student_fname, stu.lname AS student_lname, stu.profile_photo AS student_photo,
                   COALESCE(SUM(ci.points_awarded), 0) AS total_points,
                   SUM(CASE WHEN ct.is_positive = 1 THEN ci.points_awarded ELSE 0 END) AS positive_points,
                   SUM(CASE WHEN ct.is_positive = 0 THEN ci.points_awarded ELSE 0 END) AS negative_points,
                   COUNT(ci.incident_id) AS incident_count
            FROM admission adm
            INNER JOIN users stu ON stu.user_id = adm.user_id_fk
            INNER JOIN conduct_incidents ci ON ci.student_id = adm.admission_id
            LEFT JOIN conduct_types ct ON ct.type_id = ci.type_id_fk
            WHERE adm.admission_status = 'Active'
        ";
        $params = [];

        if ($schId > 0) {
            $sql      .= " AND adm.sch_id_fk = ?";
            $params[] = $schId;
        }
        if ($year) {
            $sql      .= " AND YEAR(ci.incident_date) = ?";
            $params[] = $year;
        }

        $sql .= " GROUP BY adm.admission_id, adm.sch_id_fk, stu.fname, stu.lname, stu.profile_photo
                  ORDER BY total_points ASC";

        return $db->query($sql, $params)->getResultArray();
    }
}
