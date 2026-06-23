<?php
namespace App\Models;
use CodeIgniter\Model;

class StudentAttendanceModel extends Model
{
    protected $table      = 'student_attendance';
    protected $primaryKey = 'stud_att_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'enrol_id_fk',
        'stream_id_fk',
        'admission_id_fk',
        'attendance_date',
        'attendance_note',
        'attendance_type',
        'attendance_status',
        'subject_id_fk',
    ];

    // ── Auth helpers ─────────────────────────────────────────────────────────

    public function getTeacherActiveAdmission(int $userId, int $schId = 0): ?array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('admission')
            ->where('user_id_fk', $userId)
            ->where('admission_status', 'Active');

        if ($schId > 0) {
            $builder->where('sch_id_fk', $schId);
        }

        return $builder->get()->getRowArray();
    }

    // ── Stream helpers ────────────────────────────────────────────────────────

    public function getStreamsBySchool(int $schId): array
    {
        $db = \Config\Database::connect();
        return $db->table('stream')
            ->select('stream.stream_id, stream.stream_name, level.level_name')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk', 'inner')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',  'left')
            ->where('sch_level.sch_id_fk', $schId)
            ->orderBy('level.level_name', 'ASC')
            ->orderBy('stream.stream_name', 'ASC')
            ->get()->getResultArray();
    }

    public function getStreamById(int $streamId): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('stream')
            ->select('stream.stream_id, stream.stream_name, level.level_name, sch_level.sch_id_fk')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk', 'left')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',  'left')
            ->where('stream.stream_id', $streamId)
            ->get()->getRowArray();
    }

    // ── Subject helpers ───────────────────────────────────────────────────────

    public function getSubjectsByStream(int $streamId): array
    {
        $db  = \Config\Database::connect();
        $sql = "
            SELECT ss.sch_sub_id, sub.subject_name, 'Core' AS subject_type
            FROM stream_core_subject scs
            JOIN sch_subject ss  ON ss.sch_sub_id    = scs.sch_sub_id_fk
            JOIN subject    sub  ON sub.subject_id   = ss.subject_id_fk
            WHERE scs.stream_id_fk = ?

            UNION

            SELECT ss.sch_sub_id, sub.subject_name, 'Optional' AS subject_type
            FROM stream_optional_subject sos
            JOIN sch_subject ss  ON ss.sch_sub_id    = sos.sch_sub_id_fk
            JOIN subject    sub  ON sub.subject_id   = ss.subject_id_fk
            WHERE sos.stream_id_fk = ?

            ORDER BY subject_type DESC, subject_name ASC
        ";
        return $db->query($sql, [$streamId, $streamId])->getResultArray();
    }

    public function getSubjectById(int $schSubId): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('sch_subject')
            ->select('sch_subject.sch_sub_id, subject.subject_name')
            ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk', 'left')
            ->where('sch_subject.sch_sub_id', $schSubId)
            ->get()->getRowArray();
    }

    // ── Enrolment helpers ─────────────────────────────────────────────────────

    public function getEnrolledStudents(int $streamId, int $year): array
    {
        $db = \Config\Database::connect();
        return $db->table('enrolment')
            ->select('
                enrolment.enrol_id,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.profile_photo
            ')
            ->join('admission',    'admission.admission_id    = enrolment.admission_id_fk', 'inner')
            ->join('users',        'users.user_id             = admission.user_id_fk',       'inner')
            ->join('user_role',    'user_role.user_id_fk      = users.user_id',              'inner')
            ->join('role',         'role.role_id              = user_role.role_id_fk',       'inner')
            ->join('role_category','role_category.role_cat_id = role.role_cat_id_fk',        'inner')
            ->where('enrolment.stream_id_fk', $streamId)
            ->where('enrolment.enrol_year',   $year)
            ->where('enrolment.enrol_status', 'Active')
            ->where('role_category.role_cat_id', 4)
            ->where('user_role.user_role_status', 'Active')
            ->orderBy('users.lname', 'ASC')
            ->orderBy('users.fname', 'ASC')
            ->get()->getResultArray();
    }

    // ── Add ───────────────────────────────────────────────────────────────────

    public function addAttendance(array $data): int|false
    {
        if ($this->insert($data)) {
            return (int) $this->getInsertID();
        }
        return false;
    }

    // ── Duplicate check ───────────────────────────────────────────────────────

    public function checkExists(int $streamId, string $date, string $type = 'Daily', ?int $subjectId = null): int
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('student_attendance')
            ->where('stream_id_fk',    $streamId)
            ->where('attendance_date', $date)
            ->where('attendance_type', $type);

        if ($subjectId !== null) {
            $builder->where('subject_id_fk', $subjectId);
        }

        return (int) $builder->countAllResults();
    }

    // ── Calendar events ───────────────────────────────────────────────────────

    public function getAttendanceDatesForStream(int $streamId, string $type = 'Daily', ?int $subjectId = null): array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('student_attendance')
            ->select("
                attendance_date,
                COUNT(*) AS student_count,
                SUM(CASE WHEN attendance_status = 'Present' THEN 1 ELSE 0 END) AS present_count,
                SUM(CASE WHEN attendance_status != 'Present' THEN 1 ELSE 0 END) AS absent_count
            ")
            ->where('stream_id_fk',    $streamId)
            ->where('attendance_type', $type);

        if ($subjectId !== null) {
            $builder->where('subject_id_fk', $subjectId);
        }

        return $builder
            ->groupBy('attendance_date')
            ->orderBy('attendance_date', 'ASC')
            ->get()->getResultArray();
    }

    // ── Detail for a specific stream + date ───────────────────────────────────

    public function getAttendanceForStreamDate(int $streamId, string $date, string $type = 'Daily', ?int $subjectId = null): array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('student_attendance')
            ->select('
                student_attendance.*,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.profile_photo
            ')
            ->join('enrolment', 'enrolment.enrol_id     = student_attendance.enrol_id_fk', 'left')
            ->join('admission', 'admission.admission_id = enrolment.admission_id_fk',      'left')
            ->join('users',     'users.user_id          = admission.user_id_fk',            'left')
            ->where('student_attendance.stream_id_fk',    $streamId)
            ->where('student_attendance.attendance_date', $date)
            ->where('student_attendance.attendance_type', $type);

        if ($subjectId !== null) {
            $builder->where('student_attendance.subject_id_fk', $subjectId);
        }

        return $builder
            ->orderBy('users.lname', 'ASC')
            ->orderBy('users.fname', 'ASC')
            ->get()->getResultArray();
    }

    // ── Update / Delete ───────────────────────────────────────────────────────

    public function updateAttendance(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function deleteAttendance(int $id): bool
    {
        return (bool) $this->delete($id);
    }

    public function getIdsByStreamDate(int $streamId, string $date, string $type = 'Daily', ?int $subjectId = null): array
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('student_attendance')
            ->select('stud_att_id')
            ->where('stream_id_fk',    $streamId)
            ->where('attendance_date', $date)
            ->where('attendance_type', $type);

        if ($subjectId !== null) {
            $builder->where('subject_id_fk', $subjectId);
        }

        $rows = $builder->get()->getResultArray();
        return array_column($rows, 'stud_att_id');
    }

    public function deleteAllForStreamDate(int $streamId, string $date, string $type = 'Daily', ?int $subjectId = null): void
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('student_attendance')
            ->where('stream_id_fk',    $streamId)
            ->where('attendance_date', $date)
            ->where('attendance_type', $type);

        if ($subjectId !== null) {
            $builder->where('subject_id_fk', $subjectId);
        }

        $builder->delete();
    }

    // ── Legacy ───────────────────────────────────────────────────────────────

    public function getAttendanceForDate(int $streamId, string $date): array
    {
        return $this->getAttendanceForStreamDate($streamId, $date);
    }
}
