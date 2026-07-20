<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    protected $validation;
    protected $session;
    protected $email;
    protected $helpers = ['form', 'url']; // Add form helper
    
    
    public function __construct()
    {
        helper('form,url'); // Load URL helper if you use base_url() in views
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();
    }
    
    
    public function index(){
        $view = '';
        $this->session->set('prevUrl',$this->session->get('url'));
        $this->session->set('url','dashboard');

        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('View Dashboard', 'Dashboard', 'Dashboard');

        $accessCheck = $this->require_access('_view_dashboard');
        if ($accessCheck !== true) {
            $view = 'app/auth/access_control';
            $data = $this->loadCommonData($view);
            return view('app/layouts/main', $data);
        }

        $roleCatID = (int) $this->session->get('roleCatID');
        $roleID    = (int) $this->session->get('roleID');
        $userId    = (int) $this->session->get('userID');
        $additionalData = [];

        // Any account outside Teacher/Student/Parent (System Admin, School Admin,
        // Support Staff, or any other role category) that is also flagged as a
        // parent with linked children gets a combined "own dashboard + child tabs"
        // view instead of full substitution, mirroring the Teacher-parent pattern.
        $ownView = null;
        $ownData = [];
        $parentView = null;

        if ($roleID === 1 || $roleCatID === 1) {
            $ownView    = 'app/dashboard/super_admin';
            $ownData    = $this->getSuperAdminStats();
            $parentView = 'app/dashboard/super_admin_parent';
        } elseif ($roleCatID === 2 || $roleCatID === 7) {
            $ownView    = 'app/dashboard/school_admin';
            $ownData    = $this->getSchoolAdminStats();
            $parentView = 'app/dashboard/school_admin_parent';
        } elseif ($roleCatID === 3) {
            $children = $this->hasParentFlag($userId) ? $this->parentStudentModel->getChildrenOf($userId) : [];

            if (!empty($children)) {
                $view = 'app/dashboard/teacher_parent';
                $additionalData = array_merge($this->getTeacherDashboardStats(), $this->getParentDashboardStats());
            } else {
                $view = 'app/dashboard/teacher';
                $additionalData = $this->getTeacherDashboardStats();
            }
        } elseif ($roleCatID === 4) {
            $view = 'app/dashboard/student';
            $additionalData = $this->getStudentDashboardStats();
        } elseif ($roleCatID === 6) {
            $view = 'app/dashboard/parent';
            $additionalData = $this->getParentDashboardStats();
        } else {
            $ownView    = 'app/dashboard/index';
            $parentView = 'app/dashboard/generic_parent';
        }

        if ($ownView !== null) {
            $children = $this->hasParentFlag($userId) ? $this->parentStudentModel->getChildrenOf($userId) : [];

            if (!empty($children)) {
                $view = $parentView;
                $additionalData = array_merge($ownData, $this->getParentDashboardStats());
            } else {
                $view = $ownView;
                $additionalData = $ownData;
            }
        }

        $data = $this->loadCommonData($view, $additionalData);
        return view('app/layouts/main', $data);
    }

    private function getSuperAdminStats(): array
    {
        $db = \Config\Database::connect();

        $totalSchools  = (int) $db->query("SELECT COUNT(*) c FROM school")->getRow()->c;
        $activeSchools = (int) $db->query("SELECT COUNT(*) c FROM school WHERE sch_status='Active'")->getRow()->c;
        $totalUsers    = (int) $db->query("SELECT COUNT(*) c FROM users")->getRow()->c;
        $totalStudents = (int) $db->query("SELECT COUNT(*) c FROM admission WHERE admission_status='Completed'")->getRow()->c;
        $totalTeachers = (int) $db->query("
            SELECT COUNT(DISTINCT ur.user_id_fk) c
            FROM user_role ur
            JOIN role r ON r.role_id = ur.role_id_fk
            WHERE r.role_cat_id_fk = 3 AND ur.user_role_status = 'Active'
        ")->getRow()->c;

        $schoolsList = $db->query("
            SELECT sch_id, sch_name, sch_status, sch_created_at,
                   (SELECT COUNT(*) FROM admission a WHERE a.sch_id_fk = school.sch_id AND a.admission_status = 'Completed') AS student_count
            FROM school
            ORDER BY sch_created_at DESC
        ")->getResultArray();

        $recentActivity = $db->query("
            SELECT ul.log_title, ul.log_desc, ul.log_date, ul.log_time,
                   u.fname, u.lname
            FROM user_log ul
            LEFT JOIN users u ON u.user_id = ul.user_id_fk
            ORDER BY ul.log_time DESC
            LIMIT 8
        ")->getResultArray();

        $usersByRole = $db->query("
            SELECT rc.role_cat_name, COUNT(DISTINCT ur.user_id_fk) AS cnt
            FROM user_role ur
            JOIN role r ON r.role_id = ur.role_id_fk
            JOIN role_category rc ON rc.role_cat_id = r.role_cat_id_fk
            WHERE ur.user_role_status = 'Active'
            GROUP BY rc.role_cat_id, rc.role_cat_name
            ORDER BY cnt DESC
        ")->getResultArray();

        $newUsersThisMonth = (int) $db->query("
            SELECT COUNT(*) c FROM users
            WHERE MONTH(created_date) = MONTH(CURDATE()) AND YEAR(created_date) = YEAR(CURDATE())
        ")->getRow()->c;

        return [
            'sa_total_schools'       => $totalSchools,
            'sa_active_schools'      => $activeSchools,
            'sa_total_users'         => $totalUsers,
            'sa_total_students'      => $totalStudents,
            'sa_total_teachers'      => $totalTeachers,
            'sa_new_users_month'     => $newUsersThisMonth,
            'sa_schools_list'        => $schoolsList,
            'sa_recent_activity'     => $recentActivity,
            'sa_users_by_role'       => $usersByRole,
        ];
    }

    private function getSchoolAdminStats(): array
    {
        $db    = \Config\Database::connect();
        $schId = (int) $this->session->get('schID');

        $totalStudents = (int) $db->query("
            SELECT COUNT(DISTINCT cs.user_id_fk) c
            FROM classroom_student cs
            JOIN classroom c   ON c.class_id       = cs.class_id_fk
            JOIN stream s      ON s.stream_id       = c.stream_id_fk
            JOIN sch_level sl  ON sl.sch_level_id  = s.sch_level_id_fk
            WHERE sl.sch_id_fk = ? AND cs.class_stud_status = 'Active'
        ", [$schId])->getRow()->c;

        $totalTeachers = (int) $db->query("
            SELECT COUNT(DISTINCT cst.user_id_fk) c
            FROM classroom_subject_teacher cst
            JOIN classroom_subject cs2 ON cs2.class_sub_id    = cst.class_sub_id_fk
            JOIN classroom c           ON c.class_id          = cs2.class_id_fk
            JOIN stream s              ON s.stream_id         = c.stream_id_fk
            JOIN sch_level sl          ON sl.sch_level_id     = s.sch_level_id_fk
            WHERE sl.sch_id_fk = ? AND cst.class_sub_teacher_status = 'Active'
        ", [$schId])->getRow()->c;

        $totalClassrooms = (int) $db->query("
            SELECT COUNT(*) c
            FROM classroom c
            JOIN stream s     ON s.stream_id      = c.stream_id_fk
            JOIN sch_level sl ON sl.sch_level_id  = s.sch_level_id_fk
            WHERE sl.sch_id_fk = ? AND c.class_status = 'Active'
        ", [$schId])->getRow()->c;

        $activeAnnouncements = (int) $db->query("
            SELECT COUNT(*) c FROM school_announcement
            WHERE sch_id_fk = ? AND announcement_status = 'Active'
        ", [$schId])->getRow()->c;

        $activeNotices = (int) $db->query("
            SELECT COUNT(*) c FROM notice_board
            WHERE sch_id_fk = ? AND notice_status = 'Active'
              AND (expires_at IS NULL OR expires_at > NOW())
        ", [$schId])->getRow()->c;

        // Attendance rate — last 30 days
        $attendanceRow = $db->query("
            SELECT
              SUM(CASE WHEN sa.attendance_status = 'Present' THEN 1 ELSE 0 END) AS present_cnt,
              COUNT(*) AS total_cnt
            FROM student_attendance sa
            JOIN stream s     ON s.stream_id      = sa.stream_id_fk
            JOIN sch_level sl ON sl.sch_level_id  = s.sch_level_id_fk
            WHERE sl.sch_id_fk = ?
              AND sa.attendance_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ", [$schId])->getRow();
        $attendancePct = ($attendanceRow && $attendanceRow->total_cnt > 0)
            ? round(($attendanceRow->present_cnt / $attendanceRow->total_cnt) * 100, 1)
            : 0;

        // Classrooms with student counts
        $classroomsList = $db->query("
            SELECT c.class_id, c.class_name, c.class_year, c.class_status,
                   COUNT(DISTINCT cs2.user_id_fk) AS student_count
            FROM classroom c
            JOIN stream s     ON s.stream_id      = c.stream_id_fk
            JOIN sch_level sl ON sl.sch_level_id  = s.sch_level_id_fk
            LEFT JOIN classroom_student cs2 ON cs2.class_id_fk = c.class_id
                                           AND cs2.class_stud_status = 'Active'
            WHERE sl.sch_id_fk = ?
            GROUP BY c.class_id, c.class_name, c.class_year, c.class_status
            ORDER BY c.class_name
        ", [$schId])->getResultArray();

        // Term report statuses
        $termReports = $db->query("
            SELECT trs.trs_id, trs.term, trs.status, c.class_name
            FROM term_report_status trs
            JOIN classroom c   ON c.class_id       = trs.class_id_fk
            JOIN stream s      ON s.stream_id       = c.stream_id_fk
            JOIN sch_level sl  ON sl.sch_level_id  = s.sch_level_id_fk
            WHERE sl.sch_id_fk = ?
            ORDER BY c.class_name, trs.term
        ", [$schId])->getResultArray();

        // Recent conduct incidents
        $conductIncidents = (int) $db->query("
            SELECT COUNT(*) c
            FROM conduct_incidents ci
            JOIN admission a ON a.admission_id = ci.student_id
            WHERE a.sch_id_fk = ?
              AND ci.incident_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ", [$schId])->getRow()->c;

        // Recent 5 announcements for this school
        $recentAnnouncements = $db->query("
            SELECT title, priority, created_at
            FROM school_announcement
            WHERE sch_id_fk = ? AND announcement_status = 'Active'
            ORDER BY created_at DESC LIMIT 5
        ", [$schId])->getResultArray();

        return [
            'ad_total_students'       => $totalStudents,
            'ad_total_teachers'       => $totalTeachers,
            'ad_total_classrooms'     => $totalClassrooms,
            'ad_active_announcements' => $activeAnnouncements,
            'ad_active_notices'       => $activeNotices,
            'ad_attendance_pct'       => $attendancePct,
            'ad_classrooms_list'      => $classroomsList,
            'ad_term_reports'         => $termReports,
            'ad_conduct_incidents'    => $conductIncidents,
            'ad_recent_announcements' => $recentAnnouncements,
        ];
    }

    private function getTeacherDashboardStats(): array
    {
        $db     = \Config\Database::connect();
        $userId = (int) $this->session->get('userID');

        // Teacher's active subject assignments → classrooms
        $subjectRows = $db->query("
            SELECT DISTINCT cst.class_sub_id_fk AS class_sub_id,
                   cs.class_id_fk,
                   c.class_name,
                   c.stream_id_fk,
                   sub.subject_name
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject cs  ON cs.class_sub_id  = cst.class_sub_id_fk
            INNER JOIN classroom c           ON c.class_id       = cs.class_id_fk
            INNER JOIN sch_subject ss        ON ss.sch_sub_id    = cs.sub_id_fk
            INNER JOIN subject sub           ON sub.subject_id   = ss.subject_id_fk
            WHERE cst.user_id_fk = ? AND cst.class_sub_teacher_status = 'Active'
        ", [$userId])->getResultArray();

        $classSubIds = array_unique(array_column($subjectRows, 'class_sub_id'));
        $classIds    = array_unique(array_column($subjectRows, 'class_id_fk'));
        $streamIds   = array_unique(array_column($subjectRows, 'stream_id_fk'));

        // KPI: distinct active classrooms
        $activeClassrooms = count($classIds);
        $subjectsTaught   = count($classSubIds);

        // KPI: students across teacher's classrooms
        $totalStudents = 0;
        if (!empty($classIds)) {
            $inClass = implode(',', array_map('intval', $classIds));
            $totalStudents = (int) $db->query("
                SELECT COUNT(DISTINCT user_id_fk) AS cnt
                FROM classroom_student
                WHERE class_id_fk IN ($inClass) AND class_stud_status = 'Active'
            ")->getRow()->cnt;
        }

        // KPI: lessons and assignments
        $lessonsPublished  = 0;
        $assignmentsActive = 0;
        $marksEntered      = 0;
        if (!empty($classSubIds)) {
            $inSub = implode(',', array_map('intval', $classSubIds));
            $lessonsPublished = (int) $db->query("
                SELECT COUNT(*) AS cnt FROM classroom_lesson
                WHERE class_sub_id_fk IN ($inSub) AND lesson_status = 'Published'
            ")->getRow()->cnt;
            $assignmentsActive = (int) $db->query("
                SELECT COUNT(*) AS cnt FROM lesson_assignment
                WHERE class_sub_id_fk IN ($inSub) AND assignment_status = 'Published'
            ")->getRow()->cnt;
        }
        $marksEntered = (int) $db->query("
            SELECT COUNT(*) AS cnt FROM term_exam_mark WHERE entered_by = ?
        ", [$userId])->getRow()->cnt;

        // Chart: mark grade distribution
        $markBands = ['below50' => 0, 'f50' => 0, 'f60' => 0, 'f70' => 0, 'f80' => 0, 'f90' => 0, 'absent' => 0];
        if (!empty($classSubIds)) {
            $inSub = implode(',', array_map('intval', $classSubIds));
            $row = $db->query("
                SELECT
                  SUM(CASE WHEN is_absent = 1 THEN 1 ELSE 0 END) AS absent,
                  SUM(CASE WHEN is_absent = 0 AND mark IS NOT NULL AND (mark/total_mark)*100 < 50  THEN 1 ELSE 0 END) AS below50,
                  SUM(CASE WHEN is_absent = 0 AND mark IS NOT NULL AND (mark/total_mark)*100 >= 50 AND (mark/total_mark)*100 < 60 THEN 1 ELSE 0 END) AS f50,
                  SUM(CASE WHEN is_absent = 0 AND mark IS NOT NULL AND (mark/total_mark)*100 >= 60 AND (mark/total_mark)*100 < 70 THEN 1 ELSE 0 END) AS f60,
                  SUM(CASE WHEN is_absent = 0 AND mark IS NOT NULL AND (mark/total_mark)*100 >= 70 AND (mark/total_mark)*100 < 80 THEN 1 ELSE 0 END) AS f70,
                  SUM(CASE WHEN is_absent = 0 AND mark IS NOT NULL AND (mark/total_mark)*100 >= 80 AND (mark/total_mark)*100 < 90 THEN 1 ELSE 0 END) AS f80,
                  SUM(CASE WHEN is_absent = 0 AND mark IS NOT NULL AND (mark/total_mark)*100 >= 90 THEN 1 ELSE 0 END) AS f90
                FROM term_exam_mark
                WHERE class_sub_id_fk IN ($inSub)
            ")->getRow();
            if ($row) {
                $markBands = [
                    'absent'  => (int)($row->absent  ?? 0),
                    'below50' => (int)($row->below50 ?? 0),
                    'f50'     => (int)($row->f50     ?? 0),
                    'f60'     => (int)($row->f60     ?? 0),
                    'f70'     => (int)($row->f70     ?? 0),
                    'f80'     => (int)($row->f80     ?? 0),
                    'f90'     => (int)($row->f90     ?? 0),
                ];
            }
        }

        // Chart: assignment submission rate (last 6 published assignments)
        $assignmentSubStats = [];
        if (!empty($classSubIds)) {
            $inSub = implode(',', array_map('intval', $classSubIds));
            $rows = $db->query("
                SELECT la.assignment_id, la.assignment_name, la.class_id_fk,
                  (SELECT COUNT(*) FROM classroom_student cs2
                   WHERE cs2.class_id_fk = la.class_id_fk AND cs2.class_stud_status = 'Active') AS enrolled,
                  COUNT(sub.submission_id) AS submitted
                FROM lesson_assignment la
                LEFT JOIN assignment_submission sub ON sub.assignment_id_fk = la.assignment_id
                WHERE la.class_sub_id_fk IN ($inSub) AND la.assignment_status = 'Published'
                GROUP BY la.assignment_id, la.assignment_name, la.class_id_fk
                ORDER BY la.created_at DESC
                LIMIT 6
            ")->getResultArray();
            foreach ($rows as $r) {
                $assignmentSubStats[] = [
                    'name'      => $r['assignment_name'],
                    'enrolled'  => (int)$r['enrolled'],
                    'submitted' => (int)$r['submitted'],
                ];
            }
        }

        // Chart: attendance rate by classroom
        $attendanceStats = [];
        if (!empty($streamIds)) {
            $inStream = implode(',', array_map('intval', $streamIds));
            $rows = $db->query("
                SELECT c.class_id, c.class_name,
                  SUM(CASE WHEN sa.attendance_status = 'Present' THEN 1 ELSE 0 END) AS present_count,
                  COUNT(*) AS total_count
                FROM student_attendance sa
                INNER JOIN classroom c ON c.stream_id_fk = sa.stream_id_fk
                WHERE sa.stream_id_fk IN ($inStream) AND sa.attendance_type = 'Daily'
                GROUP BY c.class_id, c.class_name
            ")->getResultArray();
            foreach ($rows as $r) {
                $attendanceStats[] = [
                    'class_name'    => $r['class_name'],
                    'present_count' => (int)$r['present_count'],
                    'total_count'   => (int)$r['total_count'],
                    'pct'           => $r['total_count'] > 0
                                        ? round(($r['present_count'] / $r['total_count']) * 100, 1)
                                        : 0,
                ];
            }
        }

        // Recent lessons (last 5)
        $recentLessons = [];
        if (!empty($classSubIds)) {
            $inSub = implode(',', array_map('intval', $classSubIds));
            $recentLessons = $db->query("
                SELECT cl.lesson_title, cl.lesson_term, cl.lesson_week, cl.created_at, cl.lesson_status,
                       sub.subject_name, c.class_name
                FROM classroom_lesson cl
                INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
                INNER JOIN classroom c          ON c.class_id      = cs.class_id_fk
                INNER JOIN sch_subject ss       ON ss.sch_sub_id   = cs.sub_id_fk
                INNER JOIN subject sub          ON sub.subject_id  = ss.subject_id_fk
                WHERE cl.class_sub_id_fk IN ($inSub)
                ORDER BY cl.created_at DESC
                LIMIT 5
            ")->getResultArray();
        }

        return [
            'ts_active_classrooms'   => $activeClassrooms,
            'ts_subjects_taught'     => $subjectsTaught,
            'ts_total_students'      => $totalStudents,
            'ts_lessons_published'   => $lessonsPublished,
            'ts_assignments_active'  => $assignmentsActive,
            'ts_marks_entered'       => $marksEntered,
            'ts_mark_bands'          => $markBands,
            'ts_assignment_sub'      => $assignmentSubStats,
            'ts_attendance'          => $attendanceStats,
            'ts_recent_lessons'      => $recentLessons,
        ];
    }
    private function getStudentDashboardStats(): array
    {
        $db     = \Config\Database::connect();
        $userId = (int) $this->session->get('userID');

        // ── 1. Admission + enrolment (most recent active) ──────────────────────
        $row = $db->query("
            SELECT a.admission_id, a.sch_id_fk, a.admission_status,
                   s.sch_name, s.sch_logo,
                   e.enrol_id, e.stream_id_fk, e.enrol_year, e.enrol_term, e.enrol_status,
                   st.stream_id, st.stream_name,
                   l.level_name
            FROM admission a
            LEFT JOIN school    s  ON s.sch_id           = a.sch_id_fk
            LEFT JOIN enrolment e  ON e.admission_id_fk  = a.admission_id
                                   AND e.enrol_status    = 'Active'
            LEFT JOIN stream    st ON st.stream_id        = e.stream_id_fk
            LEFT JOIN sch_level sl ON sl.sch_level_id     = st.sch_level_id_fk
            LEFT JOIN level     l  ON l.level_id          = sl.level_id_fk
            WHERE a.user_id_fk = ?
            ORDER BY a.admission_id DESC, e.enrol_id DESC
            LIMIT 1
        ", [$userId])->getRowArray();

        if (!$row) return ['st_no_data' => true];

        $admissionId = (int) $row['admission_id'];
        $streamId    = (int) ($row['stream_id'] ?? 0);
        $enrolTerm   = max(1, min(3, (int) ($row['enrol_term'] ?? 1)));
        $schId       = (int) $row['sch_id_fk'];

        // ── 2. Classroom ────────────────────────────────────────────────────────
        $classroom = $db->query("
            SELECT cs.class_id_fk, c.class_name, c.class_year
            FROM classroom_student cs
            JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cs.user_id_fk = ? AND cs.class_stud_status = 'Active'
            ORDER BY cs.class_stud_id DESC LIMIT 1
        ", [$userId])->getRowArray();

        $classId = $classroom ? (int) $classroom['class_id_fk'] : 0;

        // ── 3. Term marks (all 3 terms + current term stats) ────────────────────
        $allTermMarks = [];
        $classStats   = [];

        if ($classId) {
            for ($t = 1; $t <= 3; $t++) {
                $allTermMarks[$t] = $this->termExamModel->getStudentReport($classId, $userId, $t);
            }
            $classStats = $this->termExamModel->getClassStats($classId, $enrolTerm, $userId);
        }

        $currentMarks = $allTermMarks[$enrolTerm] ?? [];
        $overallPct   = $currentMarks['overall_pct'] ?? null;
        $classRank    = $classStats['position'] ?? null;
        $classSize    = $classStats['enrolled'] ?? null;

        // ── 4. Attendance ────────────────────────────────────────────────────────
        $attendancePct     = null;
        $attendanceData    = ['present' => 0, 'absent' => 0, 'total' => 0];
        $attendanceMonthly = [];
        $subjectAttendance = [];

        if ($streamId && $admissionId) {
            $daily = $db->query("
                SELECT attendance_status, COUNT(*) AS cnt
                FROM student_attendance
                WHERE admission_id_fk = ? AND stream_id_fk = ? AND attendance_type = 'Daily'
                GROUP BY attendance_status
            ", [$admissionId, $streamId])->getResultArray();

            $present = 0;
            $absent  = 0;
            foreach ($daily as $d) {
                if ($d['attendance_status'] === 'Present') $present = (int) $d['cnt'];
                else $absent = (int) $d['cnt'];
            }
            $total          = $present + $absent;
            $attendancePct  = $total > 0 ? round(($present / $total) * 100, 1) : null;
            $attendanceData = compact('present', 'absent', 'total');

            $monthly = $db->query("
                SELECT DATE_FORMAT(attendance_date,'%Y-%m')      AS mk,
                       DATE_FORMAT(attendance_date,'%b %Y')      AS label,
                       SUM(attendance_status = 'Present')        AS present,
                       COUNT(*)                                  AS total
                FROM student_attendance
                WHERE admission_id_fk = ? AND stream_id_fk = ? AND attendance_type = 'Daily'
                GROUP BY mk, label
                ORDER BY mk DESC LIMIT 6
            ", [$admissionId, $streamId])->getResultArray();
            $attendanceMonthly = array_reverse($monthly);

            $subjectAttendance = $db->query("
                SELECT COALESCE(sub.subject_name,'—') AS subject_name,
                       SUM(sa.attendance_status = 'Present')     AS present,
                       COUNT(*)                                  AS total
                FROM student_attendance sa
                LEFT JOIN sch_subject ss ON ss.sch_sub_id  = sa.subject_id_fk
                LEFT JOIN subject sub    ON sub.subject_id = ss.subject_id_fk
                WHERE sa.admission_id_fk = ? AND sa.stream_id_fk = ? AND sa.attendance_type = 'Subject'
                GROUP BY sa.subject_id_fk, sub.subject_name
                ORDER BY total DESC
            ", [$admissionId, $streamId])->getResultArray();
        }

        // ── 5. Conduct ──────────────────────────────────────────────────────────
        $incidents = [];
        try {
            $incidents = $db->query("
                SELECT ci.incident_id, ci.points_awarded, ci.incident_description,
                       ci.incident_date, ci.location, ci.is_resolved,
                       ct.type_name, ct.is_positive, ct.severity_level, ct.category,
                       CONCAT(u.fname,' ',u.lname) AS staff_name
                FROM conduct_incidents ci
                LEFT JOIN conduct_types ct ON ct.type_id  = ci.type_id_fk
                LEFT JOIN users u          ON u.user_id   = ci.staff_id
                WHERE ci.student_id = ?
                ORDER BY ci.incident_date DESC
            ", [$admissionId])->getResultArray();
        } catch (\Throwable $e) {
            // conduct tables not yet created — dashboard still loads
            log_message('error', 'Student dashboard conduct query failed: ' . $e->getMessage());
        }

        $conductPositive = $conductNegative = $conductResolved = 0;
        foreach ($incidents as $inc) {
            if ($inc['is_positive']) $conductPositive += (int) $inc['points_awarded'];
            else $conductNegative += (int) $inc['points_awarded'];
            if ($inc['is_resolved']) $conductResolved++;
        }

        // ── 6. Announcements ────────────────────────────────────────────────────
        $announcements = $db->query("
            SELECT title, content, priority, created_at
            FROM school_announcement
            WHERE sch_id_fk = ? AND announcement_status = 'Active'
            ORDER BY created_at DESC LIMIT 5
        ", [$schId])->getResultArray();

        return [
            'st_row'                => $row,
            'st_classroom'          => $classroom,
            'st_current_marks'      => $currentMarks,
            'st_all_term_marks'     => $allTermMarks,
            'st_overall_pct'        => $overallPct,
            'st_class_rank'         => $classRank,
            'st_class_size'         => $classSize,
            'st_attendance_pct'     => $attendancePct,
            'st_attendance_data'    => $attendanceData,
            'st_attendance_monthly' => $attendanceMonthly,
            'st_subject_attendance' => $subjectAttendance,
            'st_conduct_incidents'  => array_slice($incidents, 0, 5),
            'st_conduct_positive'   => $conductPositive,
            'st_conduct_negative'   => $conductNegative,
            'st_conduct_resolved'   => $conductResolved,
            'st_total_incidents'    => count($incidents),
            'st_announcements'      => $announcements,
        ];
    }

    private function getParentDashboardStats(): array
    {
        $db     = \Config\Database::connect();
        $userId = (int) $this->session->get('userID');

        $children = $this->parentStudentModel->getChildrenOf($userId);
        if (empty($children)) {
            return ['pr_no_children' => true, 'pr_children' => [], 'pr_announcements' => []];
        }

        $childStats = [];
        $schoolIds  = [];

        foreach ($children as $child) {
            $childUserId = (int) $child['user_id'];
            $childData   = [
                'user_id'      => $childUserId,
                'fname'        => $child['fname'] ?? '',
                'lname'        => $child['lname'] ?? '',
                'relationship' => $child['relationship'] ?? '',
                'photo'        => $child['profile_photo'] ?? null,
            ];

            // Admission + enrolment
            $row = $db->query("
                SELECT a.admission_id, a.sch_id_fk, a.admission_status,
                       s.sch_name, s.sch_logo,
                       e.enrol_id, e.stream_id_fk, e.enrol_year, e.enrol_term, e.enrol_status,
                       st.stream_id, st.stream_name,
                       l.level_name
                FROM admission a
                LEFT JOIN school    s  ON s.sch_id           = a.sch_id_fk
                LEFT JOIN enrolment e  ON e.admission_id_fk  = a.admission_id
                                       AND e.enrol_status    = 'Active'
                LEFT JOIN stream    st ON st.stream_id        = e.stream_id_fk
                LEFT JOIN sch_level sl ON sl.sch_level_id     = st.sch_level_id_fk
                LEFT JOIN level     l  ON l.level_id          = sl.level_id_fk
                WHERE a.user_id_fk = ?
                ORDER BY a.admission_id DESC, e.enrol_id DESC
                LIMIT 1
            ", [$childUserId])->getRowArray();

            if (!$row) {
                $childData['no_data'] = true;
                $childStats[] = $childData;
                continue;
            }

            $admissionId = (int) $row['admission_id'];
            $streamId    = (int) ($row['stream_id'] ?? 0);
            $enrolTerm   = max(1, min(3, (int) ($row['enrol_term'] ?? 1)));
            $schId       = (int) $row['sch_id_fk'];
            if ($schId) $schoolIds[$schId] = true;

            // Classroom
            $classroom = $db->query("
                SELECT cs.class_id_fk, c.class_name, c.class_year
                FROM classroom_student cs
                JOIN classroom c ON c.class_id = cs.class_id_fk
                WHERE cs.user_id_fk = ? AND cs.class_stud_status = 'Active'
                ORDER BY cs.class_stud_id DESC LIMIT 1
            ", [$childUserId])->getRowArray();

            $classId = $classroom ? (int) $classroom['class_id_fk'] : 0;

            // Term marks
            $allTermMarks = [];
            $classStatsR  = [];
            if ($classId) {
                for ($t = 1; $t <= 3; $t++) {
                    $allTermMarks[$t] = $this->termExamModel->getStudentReport($classId, $childUserId, $t);
                }
                $classStatsR = $this->termExamModel->getClassStats($classId, $enrolTerm, $childUserId);
            }

            $currentMarks = $allTermMarks[$enrolTerm] ?? [];
            $overallPct   = $currentMarks['overall_pct'] ?? null;
            $classRank    = $classStatsR['position'] ?? null;
            $classSize    = $classStatsR['enrolled'] ?? null;

            // Attendance
            $attendancePct     = null;
            $attendanceData    = ['present' => 0, 'absent' => 0, 'total' => 0];
            $attendanceMonthly = [];
            $subjectAttendance = [];

            if ($streamId && $admissionId) {
                $daily = $db->query("
                    SELECT attendance_status, COUNT(*) AS cnt
                    FROM student_attendance
                    WHERE admission_id_fk = ? AND stream_id_fk = ? AND attendance_type = 'Daily'
                    GROUP BY attendance_status
                ", [$admissionId, $streamId])->getResultArray();

                $present = 0; $absent = 0;
                foreach ($daily as $d) {
                    if ($d['attendance_status'] === 'Present') $present = (int) $d['cnt'];
                    else $absent = (int) $d['cnt'];
                }
                $total = $present + $absent;
                $attendancePct  = $total > 0 ? round(($present / $total) * 100, 1) : null;
                $attendanceData = compact('present', 'absent', 'total');

                $monthly = $db->query("
                    SELECT DATE_FORMAT(attendance_date,'%Y-%m') AS mk,
                           DATE_FORMAT(attendance_date,'%b %Y') AS label,
                           SUM(attendance_status = 'Present')   AS present,
                           COUNT(*)                             AS total
                    FROM student_attendance
                    WHERE admission_id_fk = ? AND stream_id_fk = ? AND attendance_type = 'Daily'
                    GROUP BY mk, label
                    ORDER BY mk DESC LIMIT 6
                ", [$admissionId, $streamId])->getResultArray();
                $attendanceMonthly = array_reverse($monthly);

                $subjectAttendance = $db->query("
                    SELECT COALESCE(sub.subject_name,'—') AS subject_name,
                           SUM(sa.attendance_status = 'Present') AS present,
                           COUNT(*)                              AS total
                    FROM student_attendance sa
                    LEFT JOIN sch_subject ss ON ss.sch_sub_id  = sa.subject_id_fk
                    LEFT JOIN subject sub    ON sub.subject_id = ss.subject_id_fk
                    WHERE sa.admission_id_fk = ? AND sa.stream_id_fk = ? AND sa.attendance_type = 'Subject'
                    GROUP BY sa.subject_id_fk, sub.subject_name
                    ORDER BY total DESC
                ", [$admissionId, $streamId])->getResultArray();
            }

            // Conduct
            $incidents = [];
            try {
                $incidents = $db->query("
                    SELECT ci.incident_id, ci.points_awarded, ci.incident_description,
                           ci.incident_date, ci.location, ci.is_resolved,
                           ct.type_name, ct.is_positive, ct.severity_level, ct.category,
                           CONCAT(u.fname,' ',u.lname) AS staff_name
                    FROM conduct_incidents ci
                    LEFT JOIN conduct_types ct ON ct.type_id = ci.type_id_fk
                    LEFT JOIN users u          ON u.user_id  = ci.staff_id
                    WHERE ci.student_id = ?
                    ORDER BY ci.incident_date DESC
                ", [$admissionId])->getResultArray();
            } catch (\Throwable $e) {
                log_message('error', 'Parent dashboard conduct query: ' . $e->getMessage());
            }

            $conductPositive = $conductNegative = $conductResolved = 0;
            foreach ($incidents as $inc) {
                if ($inc['is_positive']) $conductPositive += (int) $inc['points_awarded'];
                else $conductNegative += (int) $inc['points_awarded'];
                if ($inc['is_resolved']) $conductResolved++;
            }

            $childData = array_merge($childData, [
                'row'                => $row,
                'classroom'          => $classroom,
                'current_marks'      => $currentMarks,
                'all_term_marks'     => $allTermMarks,
                'overall_pct'        => $overallPct,
                'class_rank'         => $classRank,
                'class_size'         => $classSize,
                'attendance_pct'     => $attendancePct,
                'attendance_data'    => $attendanceData,
                'attendance_monthly' => $attendanceMonthly,
                'subject_attendance' => $subjectAttendance,
                'conduct_incidents'  => array_slice($incidents, 0, 5),
                'conduct_positive'   => $conductPositive,
                'conduct_negative'   => $conductNegative,
                'conduct_resolved'   => $conductResolved,
                'total_incidents'    => count($incidents),
            ]);

            $childStats[] = $childData;
        }

        // Announcements from all enrolled schools
        $announcements = [];
        if (!empty($schoolIds)) {
            $schIdList = implode(',', array_map('intval', array_keys($schoolIds)));
            $announcements = $db->query("
                SELECT sa.title, sa.content, sa.priority, sa.created_at, sa.sch_id_fk,
                       s.sch_name
                FROM school_announcement sa
                LEFT JOIN school s ON s.sch_id = sa.sch_id_fk
                WHERE sa.sch_id_fk IN ($schIdList) AND sa.announcement_status = 'Active'
                ORDER BY sa.created_at DESC LIMIT 10
            ")->getResultArray();
        }

        return [
            'pr_children'      => $childStats,
            'pr_announcements' => $announcements,
        ];
    }

    // ─── Unread counts for nav badges ─────────────────────────────────────────

    public function unreadCounts(): \CodeIgniter\HTTP\ResponseInterface
    {
        $zeroPayload = ['notices' => 0, 'announcements' => 0, 'conduct_appeals' => 0, 'events' => 0, 'wall' => 0, 'messages' => 0];

        if (!$this->isLoggedIn()) {
            return $this->response->setJSON($zeroPayload);
        }

        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');
        $db      = \Config\Database::connect();

        // Instant-message unread count is global to the user, independent of school scope.
        $messageCount = (new \App\Models\ChatModel())->getTotalUnreadCount($userId);

        $userRow  = (new \App\Models\UserModel())->find($userId);
        $isParent = $roleCat === 6
            || ($roleCat !== 3 && (int) (($userRow)['is_a_parent'] ?? 0) === 1);

        $audience = match ($roleCat) {
            3 => 'Teachers',
            4 => 'Students',
            6 => 'Parents',
            default => 'All',
        };

        if ($isParent) {
            $schools = $db->query("
                SELECT DISTINCT a.sch_id_fk
                FROM parent_student ps
                INNER JOIN admission a ON a.user_id_fk = ps.student_user_id_fk
                WHERE ps.parent_user_id_fk = ? AND a.admission_status = 'Active'
            ", [$userId])->getResultArray();

            $schIds = array_map('intval', array_column($schools, 'sch_id_fk'));

            if (empty($schIds)) {
                return $this->response->setJSON(array_merge($zeroPayload, ['messages' => $messageCount]));
            }

            $inList = implode(',', $schIds);
            $now    = date('Y-m-d H:i:s');

            try {
                $noticeCount = (int) $db->query("
                    SELECT COUNT(*) AS cnt
                    FROM notice_board nb
                    LEFT JOIN notice_reads nr ON nr.notice_id = nb.notice_id AND nr.user_id = ?
                    WHERE nb.sch_id_fk IN ({$inList})
                      AND nb.notice_status = 'Active'
                      AND (nb.expires_at IS NULL OR nb.expires_at > ?)
                      AND (nb.audience = 'All' OR nb.audience = 'Parents')
                      AND nr.nr_id IS NULL
                ", [$userId, $now])->getRow()->cnt;
            } catch (\Throwable $e) {
                $noticeCount = 0;
            }

            try {
                $annCount = (int) $db->query("
                    SELECT COUNT(*) AS cnt
                    FROM school_announcement sa
                    LEFT JOIN announcement_reads ar ON ar.announcement_id = sa.announcement_id AND ar.user_id = ?
                    WHERE sa.sch_id_fk IN ({$inList})
                      AND sa.announcement_status = 'Active'
                      AND (sa.expires_at IS NULL OR sa.expires_at > ?)
                      AND ar.ar_id IS NULL
                ", [$userId, $now])->getRow()->cnt;
            } catch (\Throwable $e) {
                $annCount = 0;
            }

            try {
                $eventCount = (int) $db->query("
                    SELECT COUNT(*) AS cnt
                    FROM school_event se
                    LEFT JOIN event_reads er ON er.event_id = se.event_id AND er.user_id = ?
                    WHERE se.sch_id_fk IN ({$inList})
                      AND er.er_id IS NULL
                ", [$userId])->getRow()->cnt;
            } catch (\Throwable $e) {
                $eventCount = 0;
            }

            try {
                $wallCount = (int) $db->query("
                    SELECT COUNT(*) AS cnt
                    FROM wall_post wp
                    LEFT JOIN wall_reads wr ON wr.wall_post_id = wp.wall_post_id AND wr.user_id = ?
                    WHERE wp.sch_id_fk IN ({$inList})
                      AND wp.post_status = 'Active'
                      AND wr.wr_id IS NULL
                ", [$userId])->getRow()->cnt;
            } catch (\Throwable $e) {
                $wallCount = 0;
            }

            $conductAppealCount = 0;

        } else {
            $schId = (int) $this->session->get('schID');
            if ($schId <= 0) {
                return $this->response->setJSON(array_merge($zeroPayload, ['messages' => $messageCount]));
            }

            $annModel    = new \App\Models\AnnouncementModel();
            $noticeModel = new \App\Models\NoticeBoardModel();
            $eventModel  = new \App\Models\EventModel();
            $wallModel   = new \App\Models\WallModel();

            $isSuperAdmin = (int) $this->session->get('roleID') === 1;

            $noticeCount = $noticeModel->getUnreadCountForUser($userId, $schId, $audience);
            $annCount    = $annModel->getUnreadCountForUser($userId, $schId);
            $eventCount  = $eventModel->getUnreadCountForUser($userId, $isSuperAdmin ? 0 : $schId);
            $wallCount   = $wallModel->getUnreadCountForUser($userId, $schId);

            $conductAppealCount = 0;
            if ($isSuperAdmin || $this->grant_access('_process_conduct_appeal')) {
                $conductAppealCount = (new \App\Models\ConductAppealModel())
                    ->getUnreadPendingCount($userId, $schId, $isSuperAdmin);
            }
        }

        return $this->response->setJSON([
            'notices'         => $noticeCount,
            'announcements'   => $annCount,
            'conduct_appeals' => $conductAppealCount,
            'events'          => $eventCount,
            'wall'            => $wallCount,
            'messages'        => $messageCount,
        ]);
    }

    // ─── Generic optimistic mark-read endpoint (badge store) ──────────────────

    public function markRead(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $domain  = (string) $this->request->getPost('domain');
        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');
        $db      = \Config\Database::connect();

        $userRow  = (new \App\Models\UserModel())->find($userId);
        $isParent = $roleCat === 6
            || ($roleCat !== 3 && (int) (($userRow)['is_a_parent'] ?? 0) === 1);

        $audience = match ($roleCat) {
            3 => 'Teachers',
            4 => 'Students',
            6 => 'Parents',
            default => 'All',
        };

        $schIds = [];
        if ($isParent) {
            $schools = $db->query("
                SELECT DISTINCT a.sch_id_fk
                FROM parent_student ps
                INNER JOIN admission a ON a.user_id_fk = ps.student_user_id_fk
                WHERE ps.parent_user_id_fk = ? AND a.admission_status = 'Active'
            ", [$userId])->getResultArray();
            $schIds = array_map('intval', array_column($schools, 'sch_id_fk'));
        } else {
            $schId = (int) $this->session->get('schID');
            if ($schId > 0) $schIds = [$schId];
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        switch ($domain) {
            case 'notice':
                $model = new \App\Models\NoticeBoardModel();
                foreach ($schIds as $schId) $model->markAllReadForUser($userId, $schId, $audience);
                break;

            case 'announcement':
                $model = new \App\Models\AnnouncementModel();
                foreach ($schIds as $schId) $model->markAllReadForUser($userId, $schId);
                break;

            case 'event':
                $model = new \App\Models\EventModel();
                if ($isSuperAdmin) {
                    $model->markAllReadForUser($userId, 0);
                } else {
                    foreach ($schIds as $schId) $model->markAllReadForUser($userId, $schId);
                }
                break;

            case 'wall':
                $model = new \App\Models\WallModel();
                foreach ($schIds as $schId) $model->markAllReadForUser($userId, $schId);
                break;

            case 'conduct_appeal':
                (new \App\Models\ConductAppealModel())
                    ->markPendingRead($userId, $schIds[0] ?? 0, $isSuperAdmin);
                break;

            case 'activity_alert':
                (new \App\Models\UserLogModel())->markAllRead($userId);
                break;

            default:
                return $this->response->setJSON(['success' => false, 'message' => 'Unknown domain.']);
        }

        return $this->response->setJSON(['success' => true]);
    }
}
