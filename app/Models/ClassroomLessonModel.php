<?php
namespace App\Models;
use CodeIgniter\Model;

class ClassroomLessonModel extends Model
{
    protected $table      = 'classroom_lesson';
    protected $primaryKey = 'lesson_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'class_sub_id_fk', 'lesson_title', 'lesson_desc',
        'lesson_term', 'lesson_week', 'lesson_day', 'lesson_year',
        'lesson_order', 'lesson_duration',
        'lesson_status', 'created_by', 'created_at', 'updated_at',
    ];

    /**
     * Lessons for a specific classroom subject (classSubId).
     */
    public function getLessonsForSubject(int $classSubId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT
                cl.lesson_id, cl.lesson_title, cl.lesson_desc,
                cl.lesson_term, cl.lesson_week, cl.lesson_day, cl.lesson_year,
                cl.lesson_order, cl.lesson_duration, cl.lesson_status, cl.created_at,
                sub.subject_name,
                (SELECT COUNT(*) FROM lesson_file   lf WHERE lf.lesson_id_fk = cl.lesson_id) AS file_count,
                (SELECT COUNT(*) FROM lesson_video  lv WHERE lv.lesson_id_fk = cl.lesson_id) AS video_count,
                (SELECT COUNT(*) FROM lesson_link   ll WHERE ll.lesson_id_fk = cl.lesson_id) AS link_count
            FROM classroom_lesson cl
            INNER JOIN classroom_subject cs   ON cs.class_sub_id  = cl.class_sub_id_fk
            INNER JOIN sch_subject schsub     ON schsub.sch_sub_id = cs.sub_id_fk
            INNER JOIN subject sub            ON sub.subject_id    = schsub.subject_id_fk
            WHERE cl.class_sub_id_fk = ?
            ORDER BY cl.lesson_term ASC, COALESCE(cl.lesson_week, 99) ASC,
                     COALESCE(cl.lesson_day, 9) ASC, cl.lesson_order ASC
        ", [$classSubId])->getResultArray();
    }

    /**
     * All subjects a teacher is assigned to across given classroom IDs (one query, grouped).
     */
    public function getTeacherSubjectsByClassrooms(array $classIds, int $userId): array
    {
        if (empty($classIds)) return [];
        $db          = \Config\Database::connect();
        $placeholders = implode(',', array_fill(0, count($classIds), '?'));
        $rows = $db->query("
            SELECT cs.class_sub_id, cs.class_id_fk, sub.subject_name
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject cs   ON cs.class_sub_id  = cst.class_sub_id_fk
            INNER JOIN sch_subject schsub     ON schsub.sch_sub_id = cs.sub_id_fk
            INNER JOIN subject sub            ON sub.subject_id    = schsub.subject_id_fk
            WHERE cs.class_id_fk IN ($placeholders)
              AND cst.user_id_fk = ? AND cst.class_sub_teacher_status = 'Active'
            ORDER BY sub.subject_name ASC
        ", [...$classIds, $userId])->getResultArray();

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['class_id_fk']][] = $row;
        }
        return $grouped;
    }

    /**
     * Single lesson with full details + steps.
     */
    public function getLessonWithSteps(int $lessonId): ?array
    {
        $db = \Config\Database::connect();

        $lesson = $db->query("
            SELECT
                cl.*, cs.class_id_fk, sub.subject_name,
                level.level_name, school.sch_name
            FROM classroom_lesson cl
            INNER JOIN classroom_subject cs ON cs.class_sub_id  = cl.class_sub_id_fk
            INNER JOIN sch_subject schsub   ON schsub.sch_sub_id = cs.sub_id_fk
            INNER JOIN subject sub          ON sub.subject_id    = schsub.subject_id_fk
            INNER JOIN classroom c          ON c.class_id        = cs.class_id_fk
            INNER JOIN stream s             ON s.stream_id       = c.stream_id_fk
            INNER JOIN sch_level sl         ON sl.sch_level_id   = s.sch_level_id_fk
            INNER JOIN level                ON level.level_id    = sl.level_id_fk
            INNER JOIN school               ON school.sch_id     = sl.sch_id_fk
            WHERE cl.lesson_id = ?
        ", [$lessonId])->getRowArray();

        if (!$lesson) return null;

        $lesson['steps'] = [];

        $lesson['files'] = $db->table('lesson_file')
            ->where('lesson_id_fk', $lessonId)->get()->getResultArray();

        $lesson['videos'] = $db->table('lesson_video')
            ->where('lesson_id_fk', $lessonId)->orderBy('video_order', 'ASC')->get()->getResultArray();

        $lesson['links'] = $db->table('lesson_link')
            ->where('lesson_id_fk', $lessonId)->orderBy('link_order', 'ASC')->get()->getResultArray();

        return $lesson;
    }

    /**
     * Subjects a teacher is assigned to in a classroom (for "Add Lesson" dropdown).
     */
    public function getTeacherSubjectsInClassroom(int $classId, int $userId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT cs.class_sub_id, sub.subject_name
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject cs   ON cs.class_sub_id  = cst.class_sub_id_fk
            INNER JOIN sch_subject schsub     ON schsub.sch_sub_id = cs.sub_id_fk
            INNER JOIN subject sub            ON sub.subject_id    = schsub.subject_id_fk
            WHERE cs.class_id_fk = ? AND cst.user_id_fk = ? AND cst.class_sub_teacher_status = 'Active'
            ORDER BY sub.subject_name ASC
        ", [$classId, $userId])->getResultArray();
    }

    /**
     * Dashboard stats for a classroom subject (classSubId).
     */
    public function getDashboardStats(int $classSubId): array
    {
        $db = \Config\Database::connect();

        $cs = $db->table('classroom_subject')->select('class_id_fk')
            ->where('class_sub_id', $classSubId)->get()->getRowArray();
        $classId = (int)($cs['class_id_fk'] ?? 0);

        $students = (int) $db->table('classroom_student')
            ->where('class_id_fk', $classId)->where('class_stud_status', 'Active')
            ->countAllResults();

        $lessonRow = $db->query("
            SELECT COUNT(*) AS cnt,
                   COALESCE(SUM((SELECT COUNT(*) FROM lesson_file lf WHERE lf.lesson_id_fk = cl.lesson_id AND lf.file_type IN ('pdf','doc','docx','ppt','pptx','xls','xlsx','txt','zip'))),0) AS file_count,
                   COALESCE(SUM((SELECT COUNT(*) FROM lesson_file lf WHERE lf.lesson_id_fk = cl.lesson_id AND lf.file_type IN ('mp4','avi','mov','mkv','webm','youtube','vimeo'))),0)          AS video_count,
                   COALESCE(SUM((SELECT COUNT(*) FROM lesson_link ll WHERE ll.lesson_id_fk = cl.lesson_id)),0) AS link_count
            FROM classroom_lesson cl
            WHERE cl.class_sub_id_fk = ? AND cl.lesson_status = 'Published'
        ", [$classSubId])->getRowArray();

        $assignments = (int) $db->table('lesson_assignment')
            ->where('class_sub_id_fk', $classSubId)->where('assignment_status', 'Published')
            ->countAllResults();

        // ── Assessment & attempt analytics ────────────────────────────────
        $lessonIds = array_column(
            $db->query("SELECT lesson_id FROM classroom_lesson WHERE class_sub_id_fk = ? AND lesson_status = 'Published'", [$classSubId])->getResultArray(),
            'lesson_id'
        );

        $assessmentList = [];
        $allScores      = [];
        $scoreDist      = [0, 0, 0, 0, 0];
        $totalAttempts  = 0;
        $quizIds = $ddIds = $labelIds = [];

        if ($lessonIds) {
            $ph = implode(',', array_fill(0, count($lessonIds), '?'));

            $quizzes = $db->query("
                SELECT lesson_quizze_id, quizze_name, assessment_type
                FROM lesson_quizze
                WHERE lesson_id_fk IN ($ph) AND quizze_status = 'Published'
                ORDER BY lesson_id_fk, lesson_quizze_id
            ", $lessonIds)->getResultArray();

            foreach ($quizzes as $q) {
                $qId   = (int) $q['lesson_quizze_id'];
                $aType = $q['assessment_type'] ?? 'quiz';
                if ($aType === 'drag_drop')  { $ddIds[]    = $qId; $tbl = 'lesson_dragdrop_attempt'; $sc = "status='submitted'"; }
                elseif ($aType === 'labelling') { $labelIds[] = $qId; $tbl = 'lesson_label_attempt';  $sc = "status='submitted'"; }
                else                         { $quizIds[]  = $qId; $tbl = 'lesson_quizze_attempt'; $sc = "status IN ('submitted','timed_out')"; }

                $row = $db->query("SELECT COUNT(*) AS cnt, ROUND(AVG(score),1) AS avg_sc FROM $tbl WHERE quizze_id_fk=? AND $sc", [$qId])->getRowArray();
                $cnt  = (int) $row['cnt'];
                $avg  = $row['avg_sc'] !== null ? (float) $row['avg_sc'] : null;
                $totalAttempts += $cnt;
                if ($avg !== null) $allScores[] = $avg;

                foreach ($db->query("SELECT score FROM $tbl WHERE quizze_id_fk=? AND $sc", [$qId])->getResultArray() as $s) {
                    $sc2 = (float) $s['score'];
                    if ($sc2 < 20) $scoreDist[0]++;
                    elseif ($sc2 < 40) $scoreDist[1]++;
                    elseif ($sc2 < 60) $scoreDist[2]++;
                    elseif ($sc2 < 80) $scoreDist[3]++;
                    else               $scoreDist[4]++;
                }

                $assessmentList[] = [
                    'name'          => $q['quizze_name'],
                    'type'          => $aType,
                    'attempt_count' => $cnt,
                    'avg_score'     => $avg,
                    'participation' => $students > 0 ? min(100, round(($cnt / $students) * 100)) : 0,
                ];
            }
        }

        $overallAvg = !empty($allScores) ? round(array_sum($allScores) / count($allScores), 1) : null;

        // Students with no attempts → need attention
        $needAttention = 0;
        if ($students > 0) {
            $enrolledIds = array_column(
                $db->query("SELECT user_id_fk FROM classroom_student WHERE class_id_fk=? AND class_stud_status='Active'", [$classId])->getResultArray(),
                'user_id_fk'
            );
            $withAttempts = [];
            if ($quizIds) {
                $ph2 = implode(',', array_fill(0, count($quizIds), '?'));
                foreach ($db->query("SELECT DISTINCT user_id_fk FROM lesson_quizze_attempt WHERE quizze_id_fk IN ($ph2) AND status IN ('submitted','timed_out')", $quizIds)->getResultArray() as $r) {
                    $withAttempts[] = $r['user_id_fk'];
                }
            }
            if ($ddIds) {
                $ph2 = implode(',', array_fill(0, count($ddIds), '?'));
                foreach ($db->query("SELECT DISTINCT user_id_fk FROM lesson_dragdrop_attempt WHERE quizze_id_fk IN ($ph2) AND status='submitted'", $ddIds)->getResultArray() as $r) {
                    $withAttempts[] = $r['user_id_fk'];
                }
            }
            if ($labelIds) {
                $ph2 = implode(',', array_fill(0, count($labelIds), '?'));
                foreach ($db->query("SELECT DISTINCT user_id_fk FROM lesson_label_attempt WHERE quizze_id_fk IN ($ph2) AND status='submitted'", $labelIds)->getResultArray() as $r) {
                    $withAttempts[] = $r['user_id_fk'];
                }
            }
            $needAttention = count(array_diff($enrolledIds, array_unique($withAttempts)));
        }

        // Lessons by term
        $lessonByTerm = [1 => 0, 2 => 0, 3 => 0];
        foreach ($db->query("SELECT lesson_term, COUNT(*) AS cnt FROM classroom_lesson WHERE class_sub_id_fk=? AND lesson_status='Published' GROUP BY lesson_term", [$classSubId])->getResultArray() as $r) {
            $t = (int) $r['lesson_term'];
            if ($t >= 1 && $t <= 3) $lessonByTerm[$t] = (int) $r['cnt'];
        }

        // Top 5 students by avg score
        $topStudents = [];
        $allAttemptIds = array_merge($quizIds, $ddIds, $labelIds);
        if ($allAttemptIds) {
            $parts = [];
            if ($quizIds) {
                $ph2 = implode(',', array_fill(0, count($quizIds), '?'));
                $parts[] = ["SELECT CONCAT(u.fname,' ',u.lname) AS sname, a.score FROM lesson_quizze_attempt a INNER JOIN users u ON u.user_id=a.user_id_fk WHERE a.quizze_id_fk IN ($ph2) AND a.status IN ('submitted','timed_out')", $quizIds];
            }
            if ($ddIds) {
                $ph2 = implode(',', array_fill(0, count($ddIds), '?'));
                $parts[] = ["SELECT CONCAT(u.fname,' ',u.lname), a.score FROM lesson_dragdrop_attempt a INNER JOIN users u ON u.user_id=a.user_id_fk WHERE a.quizze_id_fk IN ($ph2) AND a.status='submitted'", $ddIds];
            }
            if ($labelIds) {
                $ph2 = implode(',', array_fill(0, count($labelIds), '?'));
                $parts[] = ["SELECT CONCAT(u.fname,' ',u.lname), a.score FROM lesson_label_attempt a INNER JOIN users u ON u.user_id=a.user_id_fk WHERE a.quizze_id_fk IN ($ph2) AND a.status='submitted'", $labelIds];
            }
            if ($parts) {
                $union  = implode(' UNION ALL ', array_column($parts, 0));
                $params = array_merge(...array_column($parts, 1));
                $topStudents = $db->query("SELECT sname AS student_name, ROUND(AVG(score),1) AS avg_sc, COUNT(*) AS attempts FROM ($union) x GROUP BY sname ORDER BY avg_sc DESC LIMIT 5", $params)->getResultArray();
            }
        }

        // Recent 8 attempts across all types
        $recentAttempts = [];
        if ($allAttemptIds) {
            $parts = [];
            if ($quizIds) {
                $ph2 = implode(',', array_fill(0, count($quizIds), '?'));
                $parts[] = ["SELECT CONCAT(u.fname,' ',u.lname) AS sname, lq.quizze_name AS aname, a.score, a.submitted_at, 'Quiz' AS atype FROM lesson_quizze_attempt a INNER JOIN lesson_quizze lq ON lq.lesson_quizze_id=a.quizze_id_fk INNER JOIN users u ON u.user_id=a.user_id_fk WHERE a.quizze_id_fk IN ($ph2) AND a.status IN ('submitted','timed_out')", $quizIds];
            }
            if ($ddIds) {
                $ph2 = implode(',', array_fill(0, count($ddIds), '?'));
                $parts[] = ["SELECT CONCAT(u.fname,' ',u.lname), lq.quizze_name, a.score, a.submitted_at, 'Drag & Drop' FROM lesson_dragdrop_attempt a INNER JOIN lesson_quizze lq ON lq.lesson_quizze_id=a.quizze_id_fk INNER JOIN users u ON u.user_id=a.user_id_fk WHERE a.quizze_id_fk IN ($ph2) AND a.status='submitted'", $ddIds];
            }
            if ($labelIds) {
                $ph2 = implode(',', array_fill(0, count($labelIds), '?'));
                $parts[] = ["SELECT CONCAT(u.fname,' ',u.lname), lq.quizze_name, a.score, a.submitted_at, 'Labelling' FROM lesson_label_attempt a INNER JOIN lesson_quizze lq ON lq.lesson_quizze_id=a.quizze_id_fk INNER JOIN users u ON u.user_id=a.user_id_fk WHERE a.quizze_id_fk IN ($ph2) AND a.status='submitted'", $labelIds];
            }
            if ($parts) {
                $union  = implode(' UNION ALL ', array_column($parts, 0));
                $params = array_merge(...array_column($parts, 1));
                $rows   = $db->query("SELECT * FROM ($union) x ORDER BY submitted_at DESC LIMIT 8", $params)->getResultArray();
                foreach ($rows as &$r) {
                    $r['submitted_at'] = $r['submitted_at'] ? date('M j, g:ia', strtotime($r['submitted_at'])) : '—';
                }
                $recentAttempts = $rows;
            }
        }

        return [
            'students'        => $students,
            'lessons'         => (int)($lessonRow['cnt']        ?? 0),
            'assignments'     => $assignments,
            'files'           => (int)($lessonRow['file_count'] ?? 0),
            'videos'          => (int)($lessonRow['video_count']?? 0),
            'links'           => (int)($lessonRow['link_count'] ?? 0),
            'assessments'     => count($assessmentList),
            'total_attempts'  => $totalAttempts,
            'avg_score'       => $overallAvg,
            'need_attention'  => $needAttention,
            'score_dist'      => $scoreDist,
            'assessment_list' => $assessmentList,
            'lesson_by_term'  => $lessonByTerm,
            'recent_attempts' => $recentAttempts,
            'top_students'    => $topStudents,
        ];
    }

    /**
     * Number of Published lessons in this subject the student has not yet opened.
     */
    public function getUnreadLessonCount(int $classSubId, int $userId): int
    {
        if ($userId <= 0) return 0;
        $db = \Config\Database::connect();
        try {
            $row = $db->query("
                SELECT COUNT(*) AS cnt
                FROM classroom_lesson cl
                LEFT JOIN lesson_reads lr ON lr.lesson_id = cl.lesson_id AND lr.user_id = ?
                WHERE cl.class_sub_id_fk = ? AND cl.lesson_status = 'Published' AND lr.lr_id IS NULL
            ", [$userId, $classSubId])->getRowArray();
            return (int) ($row['cnt'] ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Mark a single lesson as read/opened by this student.
     */
    public function markLessonRead(int $lessonId, int $userId): void
    {
        if ($userId <= 0 || $lessonId <= 0) return;
        $db = \Config\Database::connect();
        try {
            $db->query("
                INSERT IGNORE INTO lesson_reads (user_id, lesson_id, read_at)
                VALUES (?, ?, NOW())
            ", [$userId, $lessonId]);
        } catch (\Throwable $e) { /* table may not exist yet */ }
    }

    public function getStudentAssessmentStats(int $classSubId, int $userId): array
    {
        $db = \Config\Database::connect();

        // ── Assignment scores (student vs class average) ───────────────
        $assignments = $db->query("
            SELECT
                la.assignment_id,
                la.assignment_name,
                la.assignment_total_score,
                sas.assignment_mark                                             AS student_mark,
                CASE WHEN la.assignment_total_score > 0 AND sas.assignment_mark IS NOT NULL
                     THEN ROUND((sas.assignment_mark / la.assignment_total_score) * 100, 1)
                     ELSE NULL END                                              AS student_pct,
                (SELECT ROUND(AVG(sas2.assignment_mark / la.assignment_total_score * 100), 1)
                 FROM student_assignment_score sas2
                 WHERE sas2.assignment_id_fk = la.assignment_id
                   AND la.assignment_total_score > 0)                          AS class_avg_pct,
                (SELECT COUNT(*) FROM student_assignment_score sas3
                 WHERE sas3.assignment_id_fk = la.assignment_id)               AS graded_count
            FROM lesson_assignment la
            LEFT JOIN student_assignment_score sas
                   ON sas.assignment_id_fk = la.assignment_id
                  AND sas.user_id_fk = ?
            WHERE la.class_sub_id_fk = ? AND la.assignment_status = 'Published'
            ORDER BY la.created_at ASC
        ", [$userId, $classSubId])->getResultArray();

        // ── Quiz scores (student vs class average) ────────────────────
        $quizzes = $db->query("
            SELECT
                lq.lesson_quizze_id,
                lq.quizze_name,
                lqa.score                                                       AS student_score,
                lqa.correct_answers,
                lqa.total_questions,
                lqa.status                                                      AS attempt_status,
                (SELECT ROUND(AVG(lqa2.score), 1)
                 FROM lesson_quizze_attempt lqa2
                 WHERE lqa2.quizze_id_fk = lq.lesson_quizze_id
                   AND lqa2.status IN ('submitted','timed_out'))                AS class_avg_score
            FROM lesson_quizze lq
            INNER JOIN classroom_lesson cl
                    ON cl.lesson_id = lq.lesson_id_fk
                   AND cl.class_sub_id_fk = ?
                   AND cl.lesson_status = 'Published'
            LEFT JOIN lesson_quizze_attempt lqa
                   ON lqa.quizze_id_fk = lq.lesson_quizze_id
                  AND lqa.user_id_fk = ?
                  AND lqa.status IN ('submitted','timed_out')
            WHERE lq.quizze_status = 'Published'
            ORDER BY cl.lesson_order ASC, lq.lesson_quizze_id ASC
        ", [$classSubId, $userId])->getResultArray();

        // ── Overall averages for summary ring ─────────────────────────
        $gradedAssignments = array_filter($assignments, fn($a) => $a['student_pct'] !== null);
        $attemptedQuizzes  = array_filter($quizzes,     fn($q) => $q['student_score'] !== null);

        $avgAssignment = count($gradedAssignments)
            ? round(array_sum(array_column($gradedAssignments, 'student_pct')) / count($gradedAssignments), 1)
            : null;
        $avgQuiz = count($attemptedQuizzes)
            ? round(array_sum(array_column($attemptedQuizzes, 'student_score')) / count($attemptedQuizzes), 1)
            : null;

        $allScores = array_merge(
            array_column($gradedAssignments, 'student_pct'),
            array_column($attemptedQuizzes,  'student_score')
        );
        $overallAvg = count($allScores)
            ? round(array_sum($allScores) / count($allScores), 1)
            : null;

        return [
            'assignments'  => $assignments,
            'quizzes'      => $quizzes,
            'avg_assignment' => $avgAssignment,
            'avg_quiz'       => $avgQuiz,
            'overall_avg'    => $overallAvg,
        ];
    }
}
