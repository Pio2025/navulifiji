<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentExamModel extends Model
{
    protected $table      = 'student_exam';
    protected $primaryKey = 'student_exam_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'exam_id_fk',
        'enrol_id_fk',
        'exam_year',
        'exam_term',
        'student_exam_status',
        'created_by_fk',
        'created_date',
        'created_time',
    ];

    /**
     * All student_exam rows for a given exam with full student info.
     */
    public function getStudentsInExam(int $examId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT se.*,
                    e.exam_name, e.level_id_fk,
                    enr.enrol_year, enr.enrol_term, enr.stream_id_fk,
                    str.stream_name,
                    adm.admission_id, adm.user_id_fk,
                    u.fname, u.lname, u.oname, u.profile_photo, u.gender
             FROM student_exam se
             INNER JOIN exam       e   ON e.exam_id       = se.exam_id_fk
             INNER JOIN enrolment  enr ON enr.enrol_id    = se.enrol_id_fk
             LEFT  JOIN stream     str ON str.stream_id   = enr.stream_id_fk
             INNER JOIN admission  adm ON adm.admission_id = enr.admission_id_fk
             INNER JOIN users      u   ON u.user_id       = adm.user_id_fk
             WHERE se.exam_id_fk = ?
             ORDER BY u.fname, u.lname",
            [$examId]
        )->getResultArray();
    }

    /**
     * All active schools with count of students already enrolled in this exam.
     */
    public function getSchoolSummaryForExam(int $examId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT sch.sch_id, sch.sch_name, sch.sch_status,
                    COUNT(DISTINCT se.student_exam_id) AS enrolled_count
             FROM school sch
             LEFT JOIN admission  adm ON adm.sch_id_fk      = sch.sch_id
             LEFT JOIN enrolment  enr ON enr.admission_id_fk = adm.admission_id
                                      AND enr.enrol_status   = 'Active'
             LEFT JOIN student_exam se ON se.enrol_id_fk     = enr.enrol_id
                                       AND se.exam_id_fk     = ?
             WHERE sch.sch_status = 'Active'
             GROUP BY sch.sch_id, sch.sch_name, sch.sch_status
             ORDER BY sch.sch_name ASC",
            [$examId]
        )->getResultArray();
    }

    /**
     * Students already in this exam for a specific school,
     * filtered to only those whose enrolment level matches the exam level.
     */
    public function getStudentsInExamBySchool(int $examId, int $schId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT se.*,
                    enr.enrol_year, enr.enrol_term,
                    str.stream_name,
                    sl.level_id_fk,
                    adm.admission_id, adm.user_id_fk,
                    u.fname, u.lname, u.oname, u.profile_photo, u.gender
             FROM student_exam se
             INNER JOIN enrolment  enr ON enr.enrol_id       = se.enrol_id_fk
             INNER JOIN stream     str ON str.stream_id       = enr.stream_id_fk
             INNER JOIN sch_level  sl  ON sl.sch_level_id    = str.sch_level_id_fk
             INNER JOIN exam       ex  ON ex.exam_id         = se.exam_id_fk
                                      AND ex.level_id_fk     = sl.level_id_fk
             INNER JOIN admission  adm ON adm.admission_id   = enr.admission_id_fk
             INNER JOIN users      u   ON u.user_id          = adm.user_id_fk
             WHERE se.exam_id_fk = ?
               AND adm.sch_id_fk = ?
             ORDER BY u.fname, u.lname",
            [$examId, $schId]
        )->getResultArray();
    }

    /**
     * Enrolments at a school for a given year, whose level matches the exam, not yet added to the exam.
     * Only Active enrolments qualify, and "already added" is checked per admission (not per enrol_id),
     * since one admission can have several enrolment rows for the same year.
     */
    public function getEligibleEnrolmentsBySchool(int $examId, int $schId, int $year): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT enr.enrol_id, enr.enrol_year, enr.enrol_term, enr.enrol_status,
                    str.stream_name,
                    adm.admission_id, adm.user_id_fk,
                    u.fname, u.lname, u.oname, u.profile_photo
             FROM enrolment  enr
             INNER JOIN stream    str ON str.stream_id       = enr.stream_id_fk
             INNER JOIN sch_level sl  ON sl.sch_level_id    = str.sch_level_id_fk
             INNER JOIN exam      ex  ON ex.exam_id         = ?
                                      AND ex.level_id_fk    = sl.level_id_fk
             INNER JOIN admission adm ON adm.admission_id   = enr.admission_id_fk
             INNER JOIN users     u   ON u.user_id          = adm.user_id_fk
             WHERE enr.enrol_year   = ?
               AND enr.enrol_status = 'Active'
               AND adm.sch_id_fk   = ?
               AND adm.admission_id NOT IN (
                   SELECT a2.admission_id
                   FROM student_exam se2
                   INNER JOIN enrolment e2 ON e2.enrol_id = se2.enrol_id_fk
                   INNER JOIN admission a2 ON a2.admission_id = e2.admission_id_fk
                   WHERE se2.exam_id_fk = ? AND se2.exam_year = ?
               )
             ORDER BY u.fname, u.lname",
            [$examId, $year, $schId, $examId, $year]
        )->getResultArray();
    }

    /**
     * Count of Active enrolments at a school, for a given year, whose level matches the exam level.
     * Used to distinguish "no matching enrolments" from "all already enrolled".
     */
    public function countEnrolmentsAtLevel(int $schId, int $levelId, int $year): int
    {
        $db = \Config\Database::connect();
        $row = $db->query(
            "SELECT COUNT(*) AS total
             FROM enrolment  enr
             INNER JOIN stream    str ON str.stream_id      = enr.stream_id_fk
             INNER JOIN sch_level sl  ON sl.sch_level_id   = str.sch_level_id_fk
             INNER JOIN admission adm ON adm.admission_id  = enr.admission_id_fk
             WHERE enr.enrol_year  = ?
               AND enr.enrol_status = 'Active'
               AND adm.sch_id_fk   = ?
               AND sl.level_id_fk  = ?",
            [$year, $schId, $levelId]
        )->getRowArray();

        return (int) ($row['total'] ?? 0);
    }

    /**
     * All exams a student has been entered into (by enrol_id).
     */
    public function getForEnrolment(int $enrolId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT se.*,
                    e.exam_name,
                    l.level_name
             FROM student_exam se
             INNER JOIN exam   e ON e.exam_id   = se.exam_id_fk
             LEFT  JOIN level  l ON l.level_id  = e.level_id_fk
             WHERE se.enrol_id_fk = ?
             ORDER BY se.exam_year DESC, se.exam_term DESC, e.exam_name ASC",
            [$enrolId]
        )->getResultArray();
    }

    /**
     * Enrolments eligible to be added to an exam (same level, not already added, Active enrolment).
     */
    public function getEligibleEnrolments(int $examId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT enr.enrol_id, enr.enrol_year, enr.enrol_term,
                    str.stream_name,
                    adm.admission_id, adm.user_id_fk,
                    u.fname, u.lname, u.oname, u.profile_photo
             FROM enrolment enr
             LEFT  JOIN stream    str ON str.stream_id     = enr.stream_id_fk
             INNER JOIN admission adm ON adm.admission_id  = enr.admission_id_fk
             INNER JOIN users     u   ON u.user_id         = adm.user_id_fk
             WHERE enr.enrol_status = 'Active'
               AND enr.enrol_id NOT IN (
                   SELECT enrol_id_fk FROM student_exam WHERE exam_id_fk = ?
               )
             ORDER BY u.fname, u.lname",
            [$examId]
        )->getResultArray();
    }

    // ── Marks helpers ───────────────────────────────────────────────────────────

    /**
     * Get all marks for a student_exam record with subject names.
     */
    public function getMarks(int $studentExamId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT sem.*,
                    s.subject_name,
                    u.fname AS teacher_fname, u.lname AS teacher_lname
             FROM student_exam_mark sem
             LEFT JOIN student_subject ss  ON ss.stud_sub_id  = sem.stud_sub_id_fk
             LEFT JOIN sch_subject     sch ON sch.sch_sub_id  = ss.sch_sub_id_fk
             LEFT JOIN subject         s   ON s.subject_id    = sch.subject_id_fk
             LEFT JOIN users           u   ON u.user_id       = sem.teacher_id_fk
             WHERE sem.student_exam_id_fk = ?
             ORDER BY s.subject_name ASC",
            [$studentExamId]
        )->getResultArray();
    }

    /**
     * Full detail for a single student_exam row (joins exam, enrolment, student, school).
     */
    public function getStudentExamDetail(int $studentExamId): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->query(
            "SELECT se.*,
                    e.exam_name, e.level_id_fk,
                    l.level_name,
                    enr.enrol_year, enr.enrol_term, enr.stream_id_fk,
                    str.stream_name,
                    adm.admission_id, adm.sch_id_fk,
                    sch.sch_name,
                    u.fname, u.lname, u.oname, u.profile_photo, u.gender
             FROM student_exam se
             INNER JOIN exam       e   ON e.exam_id        = se.exam_id_fk
             LEFT  JOIN level      l   ON l.level_id        = e.level_id_fk
             INNER JOIN enrolment  enr ON enr.enrol_id      = se.enrol_id_fk
             LEFT  JOIN stream     str ON str.stream_id      = enr.stream_id_fk
             INNER JOIN admission  adm ON adm.admission_id   = enr.admission_id_fk
             LEFT  JOIN school     sch ON sch.sch_id         = adm.sch_id_fk
             INNER JOIN users      u   ON u.user_id          = adm.user_id_fk
             WHERE se.student_exam_id = ?",
            [$studentExamId]
        )->getRowArray();
        return $row ?: null;
    }

    /**
     * All subjects for a student (via enrolment) with their existing marks for a student_exam.
     */
    public function getStudentSubjectsWithMarks(int $studentExamId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT ss.stud_sub_id,
                    sub.subject_name,
                    sem.mark_id,
                    sem.mark,
                    sem.grade,
                    sem.mark_status,
                    sem.teacher_id_fk,
                    CONCAT(u.fname, ' ', u.lname) AS teacher_name
             FROM student_exam      se
             INNER JOIN student_subject ss  ON ss.enrol_id_fk   = se.enrol_id_fk
             INNER JOIN sch_subject     scs ON scs.sch_sub_id    = ss.sch_sub_id_fk
             INNER JOIN subject         sub ON sub.subject_id     = scs.subject_id_fk
             LEFT  JOIN student_exam_mark sem ON sem.student_exam_id_fk = se.student_exam_id
                                             AND sem.stud_sub_id_fk     = ss.stud_sub_id
             LEFT  JOIN users            u   ON u.user_id = sem.teacher_id_fk
             WHERE se.student_exam_id = ?
             ORDER BY sub.subject_name ASC",
            [$studentExamId]
        )->getResultArray();
    }

    /**
     * All students in an exam at a school, with their examinable subject marks.
     * Sources subjects from student_subject (is_examinable=1) and marks from exam_mark.
     * Returns a flat array, each entry with a 'subjects' sub-array.
     */
    public function getStudentsWithMarksBySchool(int $examId, int $schId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                se.student_exam_id,
                adm.admission_id,
                u.fname, u.lname,
                er.exam_reg_id,
                ss.stud_sub_id,
                sub.subject_name,
                CASE
                    WHEN scs.stream_core_sub_id IS NOT NULL THEN 'Core'
                    WHEN sos.stream_opt_sub_id  IS NOT NULL THEN 'Optional'
                    ELSE 'Core'
                END AS sub_type,
                COALESCE(em.exam_mark, 0) AS exam_mark
            FROM student_exam se
            INNER JOIN enrolment  enr ON enr.enrol_id       = se.enrol_id_fk
            INNER JOIN admission  adm ON adm.admission_id   = enr.admission_id_fk
            INNER JOIN users      u   ON u.user_id          = adm.user_id_fk
            LEFT  JOIN exam_registration er
                    ON er.exam_id_fk     = se.exam_id_fk
                   AND er.admission_id_fk = adm.admission_id
            LEFT  JOIN student_subject ss
                    ON ss.admission_id_fk = adm.admission_id
                   AND ss.stud_sub_status  = 'Active'
            LEFT  JOIN sch_subject schsub ON schsub.sch_sub_id = ss.sch_sub_id_fk
            LEFT  JOIN subject     sub    ON sub.subject_id    = schsub.subject_id_fk
                                         AND sub.is_examinable  = 1
            LEFT  JOIN classroom   cl     ON cl.class_id       = ss.class_id_fk
            LEFT  JOIN stream_core_subject scs
                    ON scs.sch_sub_id_fk = ss.sch_sub_id_fk
                   AND scs.stream_id_fk  = cl.stream_id_fk
            LEFT  JOIN stream_optional_subject sos
                    ON sos.sch_sub_id_fk = ss.sch_sub_id_fk
                   AND sos.stream_id_fk  = cl.stream_id_fk
            LEFT  JOIN exam_mark em
                    ON em.stud_sub_id_fk = ss.stud_sub_id
                   AND em.exam_reg_id_fk = er.exam_reg_id
            WHERE se.exam_id_fk = ? AND adm.sch_id_fk = ?
              AND se.student_exam_status = 'Active'
            ORDER BY se.student_exam_id, sub.subject_name ASC
        ", [$examId, $schId])->getResultArray();

        $students = [];
        foreach ($rows as $row) {
            $sid = $row['student_exam_id'];
            if (!isset($students[$sid])) {
                $students[$sid] = [
                    'student_exam_id' => $sid,
                    'admission_id'    => $row['admission_id'],
                    'fname'           => $row['fname'],
                    'lname'           => $row['lname'],
                    'subjects'        => [],
                ];
            }
            // Only add if a real examinable subject row was joined
            if (!empty($row['stud_sub_id']) && !empty($row['subject_name'])) {
                $students[$sid]['subjects'][] = [
                    'sub_type'     => $row['sub_type'],
                    'exam_mark'    => (int) $row['exam_mark'],
                    'subject_name' => $row['subject_name'],
                ];
            }
        }
        return array_values($students);
    }

    /**
     * Same as getStudentsWithMarksBySchool but filtered to a specific exam year.
     */
    public function getStudentsWithMarksBySchoolByYear(int $examId, int $schId, int $year): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                se.student_exam_id,
                adm.admission_id,
                u.fname, u.lname,
                er.exam_reg_id,
                ss.stud_sub_id,
                sub.subject_name,
                CASE
                    WHEN scs.stream_core_sub_id IS NOT NULL THEN 'Core'
                    WHEN sos.stream_opt_sub_id  IS NOT NULL THEN 'Optional'
                    ELSE 'Core'
                END AS sub_type,
                COALESCE(em.exam_mark, 0) AS exam_mark
            FROM student_exam se
            INNER JOIN enrolment  enr ON enr.enrol_id       = se.enrol_id_fk
            INNER JOIN admission  adm ON adm.admission_id   = enr.admission_id_fk
            INNER JOIN users      u   ON u.user_id          = adm.user_id_fk
            LEFT  JOIN exam_registration er
                    ON er.exam_id_fk     = se.exam_id_fk
                   AND er.admission_id_fk = adm.admission_id
                   AND er.exam_year       = se.exam_year
            LEFT  JOIN student_subject ss
                    ON ss.admission_id_fk = adm.admission_id
                   AND ss.stud_sub_status  = 'Active'
            LEFT  JOIN sch_subject schsub ON schsub.sch_sub_id = ss.sch_sub_id_fk
            LEFT  JOIN subject     sub    ON sub.subject_id    = schsub.subject_id_fk
                                         AND sub.is_examinable  = 1
            LEFT  JOIN classroom   cl     ON cl.class_id       = ss.class_id_fk
            LEFT  JOIN stream_core_subject scs
                    ON scs.sch_sub_id_fk = ss.sch_sub_id_fk
                   AND scs.stream_id_fk  = cl.stream_id_fk
            LEFT  JOIN stream_optional_subject sos
                    ON sos.sch_sub_id_fk = ss.sch_sub_id_fk
                   AND sos.stream_id_fk  = cl.stream_id_fk
            LEFT  JOIN exam_mark em
                    ON em.stud_sub_id_fk = ss.stud_sub_id
                   AND em.exam_reg_id_fk = er.exam_reg_id
            WHERE se.exam_id_fk = ? AND adm.sch_id_fk = ? AND se.exam_year = ?
              AND se.student_exam_status = 'Active'
            ORDER BY se.student_exam_id, sub.subject_name ASC
        ", [$examId, $schId, $year])->getResultArray();

        $students = [];
        foreach ($rows as $row) {
            $sid = $row['student_exam_id'];
            if (!isset($students[$sid])) {
                $students[$sid] = [
                    'student_exam_id' => $sid,
                    'admission_id'    => $row['admission_id'],
                    'fname'           => $row['fname'],
                    'lname'           => $row['lname'],
                    'subjects'        => [],
                ];
            }
            if (!empty($row['stud_sub_id']) && !empty($row['subject_name'])) {
                $students[$sid]['subjects'][] = [
                    'sub_type'     => $row['sub_type'],
                    'exam_mark'    => (int) $row['exam_mark'],
                    'subject_name' => $row['subject_name'],
                ];
            }
        }
        return array_values($students);
    }

    /**
     * Upsert a mark (insert or update on duplicate key).
     */
    public function saveMark(int $studentExamId, int $studSubId, ?float $mark, ?string $grade, int $teacherId, string $status = 'Submitted'): bool
    {
        $db  = \Config\Database::connect();
        $now = time();
        $existing = $db->table('student_exam_mark')
                       ->where('student_exam_id_fk', $studentExamId)
                       ->where('stud_sub_id_fk', $studSubId)
                       ->get()->getRowArray();

        if ($existing) {
            return $db->table('student_exam_mark')
                      ->where('mark_id', $existing['mark_id'])
                      ->update([
                          'mark'          => $mark,
                          'grade'         => $grade,
                          'teacher_id_fk' => $teacherId,
                          'mark_status'   => $status,
                          'updated_time'  => $now,
                      ]);
        }

        return (bool) $db->table('student_exam_mark')->insert([
            'student_exam_id_fk' => $studentExamId,
            'stud_sub_id_fk'     => $studSubId,
            'mark'               => $mark,
            'grade'              => $grade,
            'teacher_id_fk'      => $teacherId,
            'mark_status'        => $status,
            'created_date'       => date('Y-m-d'),
            'created_time'       => $now,
        ]);
    }
}
