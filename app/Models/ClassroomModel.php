<?php
namespace App\Models;
use CodeIgniter\Model;

class ClassroomModel extends Model
{
    protected $table      = 'classroom';
    protected $primaryKey = 'class_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'stream_id_fk',
        'class_name',
        'class_year',
        'class_created_at',
        'class_updated_at',
        'class_created_by',
        'class_updated_by',
        'class_status',
    ];

    /**
     * All classrooms with stream / level / school details
     */
    public function getAllWithDetails(?int $schId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classroom')
            ->select('
                classroom.*,
                stream.stream_name,
                level.level_name,
                school.sch_id,
                school.sch_name,
                school.sch_logo,
                creator.fname  AS creator_fname,
                creator.lname  AS creator_lname,
                updater.fname  AS updater_fname,
                updater.lname  AS updater_lname
            ')
            ->join('stream',    'stream.stream_id       = classroom.stream_id_fk',    'left')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk',   'left')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',    'left')
            ->join('school',    'school.sch_id          = sch_level.sch_id_fk',      'left')
            ->join('users AS creator', 'creator.user_id = classroom.class_created_by','left')
            ->join('users AS updater', 'updater.user_id = classroom.class_updated_by','left')
            ->orderBy('classroom.class_year', 'DESC')
            ->orderBy('classroom.class_name', 'ASC');

        if ($schId) {
            $builder->where('sch_level.sch_id_fk', $schId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Single classroom with full details
     */
    public function getDetail(int $classId): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('classroom')
            ->select('
                classroom.*,
                stream.stream_name,
                stream.stream_id,
                level.level_name,
                level.level_id,
                school.sch_id,
                school.sch_name,
                school.sch_logo,
                school.sch_address,
                school.sch_email,
                school.sch_phone,
                creator.fname  AS creator_fname,
                creator.lname  AS creator_lname,
                updater.fname  AS updater_fname,
                updater.lname  AS updater_lname
            ')
            ->join('stream',    'stream.stream_id       = classroom.stream_id_fk',    'left')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk',   'left')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',    'left')
            ->join('school',    'school.sch_id          = sch_level.sch_id_fk',      'left')
            ->join('users AS creator', 'creator.user_id = classroom.class_created_by','left')
            ->join('users AS updater', 'updater.user_id = classroom.class_updated_by','left')
            ->where('classroom.class_id', $classId)
            ->get()->getRowArray();
    }

    /**
     * Check class_name uniqueness (optionally excluding one record for updates)
     */
    public function isClassNameUnique(string $className, ?int $excludeId = null): bool
    {
        $builder = $this->where('class_name', $className);
        if ($excludeId) {
            $builder->where('class_id !=', $excludeId);
        }
        return $builder->countAllResults() === 0;
    }

    /**
     * Get staff (non-student) users for a school — used in the teacher assignment dropdown
     */
    public function getStaffUsers(int $schId): array
    {
        $db = \Config\Database::connect();
        return $db->table('admission')
            ->distinct()
            ->select('users.user_id, users.fname, users.lname, users.oname, role.role_name')
            ->join('users',         'users.user_id             = admission.user_id_fk',  'inner')
            ->join('user_role',     'user_role.user_id_fk      = users.user_id',         'inner')
            ->join('role',          'role.role_id              = user_role.role_id_fk',  'inner')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',  'inner')
            ->where('admission.sch_id_fk', $schId)
            ->where('admission.admission_status', 'Active')
            ->where('role_category.role_cat_id !=', 4)
            ->orderBy('users.fname', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Get student users for a school — used in the captain assignment dropdown
     */
    public function getStudentUsers(int $schId): array
    {
        $db = \Config\Database::connect();
        return $db->table('admission')
            ->distinct()
            ->select('users.user_id, users.fname, users.lname, users.oname')
            ->join('users',         'users.user_id             = admission.user_id_fk',  'inner')
            ->join('user_role',     'user_role.user_id_fk      = users.user_id',         'inner')
            ->join('role',          'role.role_id              = user_role.role_id_fk',  'inner')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',  'inner')
            ->where('admission.sch_id_fk', $schId)
            ->where('admission.admission_status', 'Active')
            ->where('user_role.user_role_status', 'Active')
            ->where('role_category.role_cat_id', 4)
            ->orderBy('users.fname', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Get students enrolled in a stream for a given year
     */
    public function getEnrolledStudents(int $streamId, int $year): array
    {
        $db = \Config\Database::connect();
        return $db->table('enrolment')
            ->select('
                enrolment.enrol_id,
                enrolment.enrol_date,
                enrolment.enrol_term,
                enrolment.enrol_year,
                enrolment.enrol_status,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.gender,
                users.profile_photo
            ')
            ->join('admission', 'admission.admission_id = enrolment.admission_id_fk', 'inner')
            ->join('users',     'users.user_id          = admission.user_id_fk',      'inner')
            ->where('enrolment.stream_id_fk', $streamId)
            ->where('enrolment.enrol_year',   $year)
            ->orderBy('users.fname', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Classroom subjects with teacher info, classified core / optional
     */
    public function getClassroomSubjectData(int $classId): array
    {
        $db        = \Config\Database::connect();
        $classroom = $db->table('classroom')->select('stream_id_fk')
                        ->where('class_id', $classId)->get()->getRowArray();
        $streamId  = (int)($classroom['stream_id_fk'] ?? 0);

        $rows = $db->query("
            SELECT
                cs.class_sub_id,
                cs.sub_id_fk AS sch_sub_id,
                sub.subject_name,
                CASE WHEN scs.stream_core_sub_id IS NOT NULL THEN 'core'
                     WHEN sos.stream_opt_sub_id  IS NOT NULL THEN 'optional'
                     ELSE 'other' END AS subject_type,
                sos.option_num,
                t.user_id        AS teacher_id,
                t.teacher_name,
                t.teacher_photo
            FROM classroom_subject cs
            INNER JOIN sch_subject schsub ON schsub.sch_sub_id = cs.sub_id_fk
            INNER JOIN subject sub        ON sub.subject_id    = schsub.subject_id_fk
            LEFT JOIN stream_core_subject scs
                   ON scs.sch_sub_id_fk = cs.sub_id_fk AND scs.stream_id_fk = ?
            LEFT JOIN stream_optional_subject sos
                   ON sos.sch_sub_id_fk = cs.sub_id_fk AND sos.stream_id_fk = ?
            LEFT JOIN (
                SELECT cst.class_sub_id_fk,
                       u.user_id,
                       CONCAT(u.fname, ' ', u.lname) AS teacher_name,
                       u.profile_photo AS teacher_photo
                FROM classroom_subject_teacher cst
                INNER JOIN users u ON u.user_id = cst.user_id_fk
                WHERE cst.class_sub_teacher_status = 'Active'
            ) t ON t.class_sub_id_fk = cs.class_sub_id
            WHERE cs.class_id_fk = ?
            ORDER BY subject_type ASC, sos.option_num ASC, sub.subject_name ASC
        ", [$streamId, $streamId, $classId])->getResultArray();

        $core = []; $optional = [];
        foreach ($rows as $row) {
            if ($row['subject_type'] === 'optional') {
                $optional[(int)($row['option_num'] ?? 0)][] = $row;
            } else {
                $core[] = $row;
            }
        }
        return ['core' => $core, 'optional' => $optional];
    }

    /**
     * Stream subjects not yet added to this classroom
     */
    public function getAvailableSubjectsForClassroom(int $classId): array
    {
        $db        = \Config\Database::connect();
        $classroom = $db->table('classroom')->select('stream_id_fk')
                        ->where('class_id', $classId)->get()->getRowArray();
        $streamId  = (int)($classroom['stream_id_fk'] ?? 0);

        $addedIds = array_column(
            $db->table('classroom_subject')->select('sub_id_fk')
               ->where('class_id_fk', $classId)->get()->getResultArray(),
            'sub_id_fk'
        );

        $coreQ = $db->table('stream_core_subject scs')
            ->select('schsub.sch_sub_id, sub.subject_name')
            ->join('sch_subject schsub', 'schsub.sch_sub_id = scs.sch_sub_id_fk', 'inner')
            ->join('subject sub',        'sub.subject_id    = schsub.subject_id_fk', 'inner')
            ->where('scs.stream_id_fk', $streamId)
            ->where('schsub.sch_sub_status', 'Active')
            ->orderBy('sub.subject_name', 'ASC');
        if (!empty($addedIds)) $coreQ->whereNotIn('schsub.sch_sub_id', $addedIds);

        $optQ = $db->table('stream_optional_subject sos')
            ->select('schsub.sch_sub_id, sub.subject_name, sos.option_num')
            ->join('sch_subject schsub', 'schsub.sch_sub_id = sos.sch_sub_id_fk', 'inner')
            ->join('subject sub',        'sub.subject_id    = schsub.subject_id_fk', 'inner')
            ->where('sos.stream_id_fk', $streamId)
            ->where('schsub.sch_sub_status', 'Active')
            ->orderBy('sos.option_num', 'ASC')
            ->orderBy('sub.subject_name', 'ASC');
        if (!empty($addedIds)) $optQ->whereNotIn('schsub.sch_sub_id', $addedIds);

        return [
            'core'     => $coreQ->get()->getResultArray(),
            'optional' => $optQ->get()->getResultArray(),
        ];
    }

    /**
     * Students already admitted to this classroom
     */
    public function getClassroomStudents(int $classId): array
    {
        $db        = \Config\Database::connect();
        $classroom = $db->table('classroom')->select('stream_id_fk, class_year')
                        ->where('class_id', $classId)->get()->getRowArray();
        $streamId  = (int)($classroom['stream_id_fk'] ?? 0);
        $year      = (int)($classroom['class_year']   ?? 0);

        return $db->query("
            SELECT
                cs.class_stud_id,
                cs.class_stud_status,
                cs.admitted_at,
                cs.admitted_by,
                u.user_id,
                u.fname, u.lname, u.oname, u.gender, u.profile_photo,
                e.enrol_id, e.enrol_term, e.enrol_date, e.enrol_year, e.enrol_status
            FROM classroom_student cs
            INNER JOIN users u ON u.user_id = cs.user_id_fk
            LEFT JOIN admission a
                   ON a.user_id_fk = u.user_id AND a.admission_status = 'Active'
            LEFT JOIN enrolment e
                   ON e.admission_id_fk = a.admission_id
                  AND e.stream_id_fk = ?
                  AND e.enrol_year   = ?
            WHERE cs.class_id_fk = ?
            ORDER BY u.fname ASC
        ", [$streamId, $year, $classId])->getResultArray();
    }

    /**
     * Students enrolled in the stream/year but not yet admitted to this classroom
     */
    public function getEligibleStudents(int $classId, int $streamId, int $year): array
    {
        $db = \Config\Database::connect();

        $admittedUserIds = array_column(
            $db->table('classroom_student')->select('user_id_fk')
               ->where('class_id_fk', $classId)->get()->getResultArray(),
            'user_id_fk'
        );

        $builder = $db->table('enrolment')
            ->select('
                enrolment.enrol_id,
                enrolment.enrol_term,
                enrolment.enrol_date,
                enrolment.enrol_status,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.gender,
                users.profile_photo
            ')
            ->join('admission', 'admission.admission_id = enrolment.admission_id_fk', 'inner')
            ->join('users',     'users.user_id          = admission.user_id_fk',      'inner')
            ->where('enrolment.stream_id_fk', $streamId)
            ->where('enrolment.enrol_status', 'Active')
            ->orderBy('users.fname', 'ASC');

        if (!empty($admittedUserIds)) {
            $builder->whereNotIn('users.user_id', $admittedUserIds);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get streams for a school (for add/edit dropdowns)
     */
    public function getStreamsForSchool(?int $schId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('stream')
            ->select('
                stream.stream_id,
                stream.stream_name,
                level.level_name,
                school.sch_name
            ')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk', 'left')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',  'left')
            ->join('school',    'school.sch_id          = sch_level.sch_id_fk',    'left')
            ->orderBy('level.level_name', 'ASC')
            ->orderBy('stream.stream_name', 'ASC');

        if ($schId) {
            $builder->where('sch_level.sch_id_fk', $schId);
        }

        return $builder->get()->getResultArray();
    }
}