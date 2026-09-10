<?php
namespace App\Models;
use CodeIgniter\Model;

class TransportAllocationModel extends Model
{
    protected $table      = 'transport_allocation';
    protected $primaryKey = 'allocation_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'student_id',
        'academic_year',
        'e_transport_card_number',
        'receiving_social_welfare',
        'social_welfare_number',
        'to_school_final_destination',
        'to_school_total_fare',
        'to_home_final_destination',
        'to_home_total_fare',
        'application_status',
        'vetted_by_name',
        'vetted_date',
        'principal_signed_date',
        'femis_entry_by',
        'femis_entry_date',
        'submitted_by',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `transport_allocation` (
            `allocation_id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `student_id`                 INT UNSIGNED NOT NULL,
            `academic_year`               INT UNSIGNED NOT NULL,
            `e_transport_card_number`    VARCHAR(50)  DEFAULT NULL,
            `receiving_social_welfare`   TINYINT(1)   NOT NULL DEFAULT 0,
            `social_welfare_number`      VARCHAR(50)  DEFAULT NULL,
            `to_school_final_destination` VARCHAR(150) DEFAULT NULL,
            `to_school_total_fare`       DECIMAL(8,2) DEFAULT NULL,
            `to_home_final_destination`  VARCHAR(150) DEFAULT NULL,
            `to_home_total_fare`         DECIMAL(8,2) DEFAULT NULL,
            `application_status`         ENUM('Draft','Submitted','Vetted','Approved','Rejected') NOT NULL DEFAULT 'Draft',
            `vetted_by_name`             VARCHAR(150) DEFAULT NULL,
            `vetted_date`                DATE         DEFAULT NULL,
            `principal_signed_date`      DATE         DEFAULT NULL,
            `femis_entry_by`             VARCHAR(150) DEFAULT NULL,
            `femis_entry_date`           DATE         DEFAULT NULL,
            `submitted_by`               INT UNSIGNED DEFAULT NULL,
            `created_at`                 DATETIME     DEFAULT NULL,
            `updated_at`                 DATETIME     DEFAULT NULL,
            PRIMARY KEY (`allocation_id`),
            UNIQUE KEY `uniq_student_year` (`student_id`, `academic_year`),
            KEY `academic_year` (`academic_year`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /**
     * Allocations for a school (0 = all schools), optionally filtered by academic year.
     */
    public function getBySchool(int $schId, ?int $year = null): array
    {
        $db = \Config\Database::connect();

        $sql = "
            SELECT ta.*,
                   adm.admission_id, adm.sch_id_fk,
                   stu.fname AS student_fname, stu.lname AS student_lname, stu.profile_photo AS student_photo,
                   school.sch_name
            FROM transport_allocation ta
            INNER JOIN admission adm ON adm.admission_id = ta.student_id
            INNER JOIN users     stu ON stu.user_id      = adm.user_id_fk
            LEFT JOIN  school    school ON school.sch_id = adm.sch_id_fk
            WHERE 1 = 1
        ";
        $params = [];

        if ($schId > 0) {
            $sql      .= " AND adm.sch_id_fk = ?";
            $params[] = $schId;
        }
        if ($year) {
            $sql      .= " AND ta.academic_year = ?";
            $params[] = $year;
        }

        $sql .= " ORDER BY ta.academic_year DESC, stu.fname ASC";

        return $db->query($sql, $params)->getResultArray();
    }

    public function getDetail(int $allocationId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->query(
            "SELECT ta.*,
                    adm.admission_id, adm.sch_id_fk, adm.user_id_fk AS student_user_id,
                    stu.fname AS student_fname, stu.lname AS student_lname, stu.dob, stu.address,
                    stu.phone, stu.email, stu.profile_photo AS student_photo,
                    school.sch_name, school.sch_address, school.sch_logo,
                    stream.stream_name, level.level_name
             FROM transport_allocation ta
             INNER JOIN admission adm  ON adm.admission_id      = ta.student_id
             INNER JOIN users     stu  ON stu.user_id           = adm.user_id_fk
             LEFT JOIN  school    school ON school.sch_id       = adm.sch_id_fk
             LEFT JOIN  enrolment enr  ON enr.admission_id_fk   = adm.admission_id
             LEFT JOIN  stream    stream ON stream.stream_id    = enr.stream_id_fk
             LEFT JOIN  sch_level sch_level ON sch_level.sch_level_id = stream.sch_level_id_fk
             LEFT JOIN  level     level ON level.level_id       = sch_level.level_id_fk
             WHERE ta.allocation_id = ?
             ORDER BY enr.enrol_id DESC
             LIMIT 1",
            [$allocationId]
        )->getRowArray();

        return $row ?: null;
    }

    public function getByStudent(int $admissionId): array
    {
        return $this->where('student_id', $admissionId)
            ->orderBy('academic_year', 'DESC')
            ->findAll();
    }

    public function getByStudentAndYear(int $admissionId, int $year): ?array
    {
        return $this->where('student_id', $admissionId)
            ->where('academic_year', $year)
            ->first();
    }

    /**
     * Active students for a school (or all schools) for the allocation picker.
     */
    public function getActiveStudentsBySchool(?int $schId = null): array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('admission')
            ->select('
                admission.admission_id, admission.sch_id_fk,
                users.fname, users.lname, users.oname, users.dob, users.address, users.phone,
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
}
