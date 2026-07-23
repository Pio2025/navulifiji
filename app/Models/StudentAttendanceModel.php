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

    // ── Student self-view ─────────────────────────────────────────────────────

    public function getStudentDailyAttendance(int $userId, int $streamId): array
    {
        $db  = \Config\Database::connect();
        $adm = $db->table('admission')
            ->select('admission_id')
            ->where('user_id_fk', $userId)
            ->where('admission_status', 'Active')
            ->get()->getRowArray();
        if (!$adm) {
            $adm = $db->table('admission')
                ->select('admission_id')
                ->where('user_id_fk', $userId)
                ->get()->getRowArray();
        }
        if (!$adm) return [];

        return $db->table('student_attendance')
            ->where('admission_id_fk', $adm['admission_id'])
            ->where('stream_id_fk', $streamId)
            ->where('attendance_type', 'Daily')
            ->orderBy('attendance_date', 'ASC')
            ->get()->getResultArray();
    }

    public function getStudentSubjectAttendance(int $userId, int $streamId, ?string $fromDate = null, ?string $toDate = null): array
    {
        $db  = \Config\Database::connect();
        $adm = $db->table('admission')
            ->select('admission_id')
            ->where('user_id_fk', $userId)
            ->where('admission_status', 'Active')
            ->get()->getRowArray();
        if (!$adm) {
            $adm = $db->table('admission')
                ->select('admission_id')
                ->where('user_id_fk', $userId)
                ->get()->getRowArray();
        }
        if (!$adm) return [];

        $params = [$adm['admission_id'], $streamId];
        $dateFilter = '';
        if ($fromDate && $toDate) {
            $dateFilter = ' AND sa.attendance_date BETWEEN ? AND ?';
            $params[] = $fromDate;
            $params[] = $toDate;
        }

        $sql = "
            SELECT sa.stud_att_id, sa.attendance_date, sa.attendance_status, sa.attendance_note,
                   COALESCE(sub.subject_name, '—') AS subject_name
            FROM student_attendance sa
            LEFT JOIN sch_subject ss ON ss.sch_sub_id = sa.subject_id_fk
            LEFT JOIN subject sub    ON sub.subject_id = ss.subject_id_fk
            WHERE sa.admission_id_fk = ? AND sa.stream_id_fk = ? AND sa.attendance_type = 'Subject'{$dateFilter}
            ORDER BY sa.attendance_date ASC, sub.subject_name ASC
        ";
        return $db->query($sql, $params)->getResultArray();
    }

    // ── Term grid helpers ─────────────────────────────────────────────────────

    public function getStudentsInStream(int $streamId): array
    {
        $db = \Config\Database::connect();
        return $db->table('enrolment e')
            ->select('MIN(e.enrol_id) AS enrol_id, e.admission_id_fk, u.fname, u.lname, u.oname, u.profile_photo')
            ->join('admission a',     'a.admission_id    = e.admission_id_fk', 'inner')
            ->join('users u',         'u.user_id         = a.user_id_fk',      'inner')
            ->join('user_role ur',    'ur.user_id_fk     = u.user_id',         'inner')
            ->join('role r',          'r.role_id         = ur.role_id_fk',     'inner')
            ->join('role_category rc','rc.role_cat_id    = r.role_cat_id_fk',  'inner')
            ->where('e.stream_id_fk', $streamId)
            ->where('e.enrol_status', 'Active')
            ->where('rc.role_cat_id', 4)
            ->where('ur.user_role_status', 'Active')
            ->groupBy('e.admission_id_fk, u.fname, u.lname, u.oname, u.profile_photo')
            ->orderBy('u.lname', 'ASC')
            ->orderBy('u.fname', 'ASC')
            ->get()->getResultArray();
    }

    public function getTermAttendance(int $streamId, array $dates): array
    {
        if (empty($dates)) {
            return [];
        }
        $db   = \Config\Database::connect();
        $rows = $db->table('student_attendance')
            ->select('enrol_id_fk, attendance_date, attendance_status')
            ->where('stream_id_fk', $streamId)
            ->where('attendance_type', 'Daily')
            ->whereIn('attendance_date', $dates)
            ->get()->getResultArray();

        $indexed = [];
        foreach ($rows as $row) {
            $indexed[(int)$row['enrol_id_fk']][$row['attendance_date']] = $row['attendance_status'];
        }
        return $indexed;
    }
}
