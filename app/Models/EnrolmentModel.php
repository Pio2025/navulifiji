<?php
namespace App\Models;
use CodeIgniter\Model;

class EnrolmentModel extends Model
{
    protected $table      = 'enrolment';
    protected $primaryKey = 'enrol_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'admission_id_fk',
        'stream_id_fk',
        'enrol_date',
        'enrol_time',
        'enrol_term',
        'enrol_year',
        'enrol_note',
        'enrol_status',
    ];

    public function addEnrolment(array $data): int|false
    {
        if ($this->insert($data)) {
            return $this->getInsertID();
        }
        return false;
    }

    /**
     * Get all enrolments with full details
     */
    public function getAllWithDetails(?int $schId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('enrolment')
            ->select('
                enrolment.*,
                admission.admission_id,
                admission.admission_status,
                admission.admission_date,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.username,
                users.email,
                users.gender,
                users.profile_photo,
                school.sch_id,
                school.sch_name,
                school.sch_logo,
                stream.stream_name,
                level.level_name,
                sch_level.sch_level_id,
                role.role_name,
                role_category.role_cat_name
            ')
            ->join('admission',    'admission.admission_id    = enrolment.admission_id_fk',  'left')
            ->join('users',        'users.user_id             = admission.user_id_fk',        'left')
            ->join('user_role',    'user_role.user_id_fk      = users.user_id',               'left')
            ->join('role',         'role.role_id              = user_role.role_id_fk',        'left')
            ->join('role_category','role_category.role_cat_id = role.role_cat_id_fk',         'left')
            ->join('school',       'school.sch_id             = admission.sch_id_fk',         'left')
            ->join('stream',       'stream.stream_id          = enrolment.stream_id_fk',      'left')
            ->join('sch_level',    'sch_level.sch_level_id    = stream.sch_level_id_fk',      'left')
            ->join('level',        'level.level_id            = sch_level.level_id_fk',       'left')
            ->where('user_role.user_role_status', 'Active')
            ->orderBy('enrolment.enrol_id', 'DESC');

        if ($schId) {
            $builder->where('admission.sch_id_fk', $schId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get single enrolment with full details
     */
    public function getDetail(int $enrolId): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('enrolment')
            ->select('
                enrolment.*,
                admission.admission_id,
                admission.admission_status,
                admission.admission_date,
                admission.admission_note,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.email,
                users.gender,
                users.dob,
                users.phone,
                users.address,
                users.profile_photo,
                school.sch_id,
                school.sch_name,
                school.sch_address,
                school.sch_logo,
                school.sch_phone,
                school.sch_email,
                stream.stream_id,
                stream.stream_name,
                level.level_name,
                sch_level.sch_level_id,
                role.role_name,
                role_category.role_cat_name,
                role_category.role_cat_id
            ')
            ->join('admission',    'admission.admission_id    = enrolment.admission_id_fk',  'left')
            ->join('users',        'users.user_id             = admission.user_id_fk',        'left')
            ->join('user_role',    'user_role.user_id_fk      = users.user_id',               'left')
            ->join('role',         'role.role_id              = user_role.role_id_fk',        'left')
            ->join('role_category','role_category.role_cat_id = role.role_cat_id_fk',         'left')
            ->join('school',       'school.sch_id             = admission.sch_id_fk',         'left')
            ->join('stream',       'stream.stream_id          = enrolment.stream_id_fk',      'left')
            ->join('sch_level',    'sch_level.sch_level_id    = stream.sch_level_id_fk',      'left')
            ->join('level',        'level.level_id            = sch_level.level_id_fk',       'left')
            ->where('enrolment.enrol_id', $enrolId)
            ->where('user_role.user_role_status', 'Active')
            ->get()->getRowArray();
    }

    /**
     * Get active admissions not yet enrolled
     * If schId is null — all schools, else filter by school
     */
    public function getEligibleAdmissions(?int $schId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('admission')
            ->select('
                admission.*,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.email,
                school.sch_name,
                role.role_name,
                role_category.role_cat_name,
                role_category.role_cat_id
            ')
            ->join('users',         'users.user_id             = admission.user_id_fk',   'inner')
            ->join('user_role',     'user_role.user_id_fk      = users.user_id',          'inner')
            ->join('role',          'role.role_id              = user_role.role_id_fk',   'inner')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',   'inner')
            ->join('school',        'school.sch_id             = admission.sch_id_fk',    'left')
            ->where('admission.admission_status', 'Active')
            ->where('user_role.user_role_status', 'Active')
            ->where('role_category.role_cat_id', 4) // ── Student only ──
            ->whereNotIn('admission.admission_id', function($subQuery) {
                $subQuery->select('admission_id_fk')
                         ->from('enrolment')
                         ->where('enrol_status', 'Active');
            })
            ->orderBy('users.fname', 'ASC');
    
        if ($schId) {
            $builder->where('admission.sch_id_fk', $schId);
        }
    
        return $builder->get()->getResultArray();
    }

    /**
     * Get student subjects for an enrolment, grouped by core / optional / other
     */
    public function getStudentSubjects(int $enrolId): array
    {
        $db = \Config\Database::connect();

        $enrolment = $db->table('enrolment')->select('stream_id_fk')
            ->where('enrol_id', $enrolId)->get()->getRowArray();
        $streamId = (int)($enrolment['stream_id_fk'] ?? 0);

        $rows = $db->query("
            SELECT
                ss.stud_sub_id,
                ss.sch_sub_id_fk,
                ss.stud_sub_status,
                sub.subject_name,
                CASE
                    WHEN scs.stream_core_sub_id IS NOT NULL THEN 'core'
                    WHEN sos.stream_opt_sub_id  IS NOT NULL THEN 'optional'
                    ELSE 'other'
                END AS subject_type,
                sos.option_num
            FROM student_subject ss
            LEFT JOIN sch_subject schsub ON schsub.sch_sub_id = ss.sch_sub_id_fk
            LEFT JOIN subject sub        ON sub.subject_id    = schsub.subject_id_fk
            LEFT JOIN stream_core_subject scs
                   ON scs.sch_sub_id_fk = ss.sch_sub_id_fk AND scs.stream_id_fk = ?
            LEFT JOIN stream_optional_subject sos
                   ON sos.sch_sub_id_fk = ss.sch_sub_id_fk AND sos.stream_id_fk = ?
            WHERE ss.enrol_id_fk = ?
            ORDER BY subject_type ASC, sub.subject_name ASC
        ", [$streamId, $streamId, $enrolId])->getResultArray();

        $grouped = ['core' => [], 'optional' => [], 'other' => []];
        foreach ($rows as $row) {
            $grouped[$row['subject_type'] ?? 'other'][] = $row;
        }
        return $grouped;
    }

    /**
     * Get stream subjects not yet assigned to the enrolment
     */
    public function getAvailableSubjects(int $enrolId): array
    {
        $db = \Config\Database::connect();

        $enrolment = $db->table('enrolment')->select('stream_id_fk')
            ->where('enrol_id', $enrolId)->get()->getRowArray();
        $streamId = (int)($enrolment['stream_id_fk'] ?? 0);

        $assignedIds = array_column(
            $db->table('student_subject')->select('sch_sub_id_fk')
               ->where('enrol_id_fk', $enrolId)->get()->getResultArray(),
            'sch_sub_id_fk'
        );

        $coreQ = $db->table('stream_core_subject scs')
            ->select('schsub.sch_sub_id, sub.subject_name')
            ->join('sch_subject schsub', 'schsub.sch_sub_id = scs.sch_sub_id_fk', 'inner')
            ->join('subject sub',        'sub.subject_id    = schsub.subject_id_fk', 'inner')
            ->where('scs.stream_id_fk', $streamId)
            ->where('schsub.sch_sub_status', 'Active')
            ->orderBy('sub.subject_name', 'ASC');
        if (!empty($assignedIds)) {
            $coreQ->whereNotIn('schsub.sch_sub_id', $assignedIds);
        }

        $optQ = $db->table('stream_optional_subject sos')
            ->select('schsub.sch_sub_id, sub.subject_name, sos.option_num')
            ->join('sch_subject schsub', 'schsub.sch_sub_id = sos.sch_sub_id_fk', 'inner')
            ->join('subject sub',        'sub.subject_id    = schsub.subject_id_fk', 'inner')
            ->where('sos.stream_id_fk', $streamId)
            ->where('schsub.sch_sub_status', 'Active')
            ->orderBy('sos.option_num', 'ASC')
            ->orderBy('sub.subject_name', 'ASC');
        if (!empty($assignedIds)) {
            $optQ->whereNotIn('schsub.sch_sub_id', $assignedIds);
        }

        return [
            'core'     => $coreQ->get()->getResultArray(),
            'optional' => $optQ->get()->getResultArray(),
        ];
    }

    /**
     * Get core and optional subjects for a stream
     */
    public function getSubjectsByStream(int $streamId): array
    {
        $db = \Config\Database::connect();

        $core = $db->table('stream_core_subject scs')
            ->select('ss.sch_sub_id, sub.subject_name')
            ->join('sch_subject ss',  'ss.sch_sub_id    = scs.sch_sub_id_fk', 'inner')
            ->join('subject sub',     'sub.subject_id   = ss.subject_id_fk',  'inner')
            ->where('scs.stream_id_fk', $streamId)
            ->where('ss.sch_sub_status', 'Active')
            ->orderBy('sub.subject_name', 'ASC')
            ->get()->getResultArray();

        $optional = $db->table('stream_optional_subject sos')
            ->select('ss.sch_sub_id, sub.subject_name, sos.option_num')
            ->join('sch_subject ss', 'ss.sch_sub_id  = sos.sch_sub_id_fk', 'inner')
            ->join('subject sub',   'sub.subject_id  = ss.subject_id_fk',  'inner')
            ->where('sos.stream_id_fk', $streamId)
            ->where('ss.sch_sub_status', 'Active')
            ->orderBy('sos.option_num', 'ASC')
            ->orderBy('sub.subject_name', 'ASC')
            ->get()->getResultArray();

        return ['core' => $core, 'optional' => $optional];
    }

    /**
     * Get streams for a school (or all if null)
     */
    public function getStreams(?int $schId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('stream')
            ->select('
                stream.*,
                sch_level.sch_level_id,
                level.level_name,
                school.sch_name
            ')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk',  'left')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',   'left')
            ->join('school',    'school.sch_id          = sch_level.sch_id_fk',      'left')
            ->orderBy('level.level_name', 'ASC')
            ->orderBy('stream.stream_name', 'ASC');

        if ($schId) {
            $builder->where('sch_level.sch_id_fk', $schId);
        }

        return $builder->get()->getResultArray();
    }
}