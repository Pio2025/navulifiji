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
        $additionalData = [];

        if ($roleID === 1 || $roleCatID === 1) {
            $view = 'app/dashboard/super_admin';
            $additionalData = $this->getSuperAdminStats();
        } elseif ($roleCatID === 2 || $roleCatID === 7) {
            $view = 'app/dashboard/school_admin';
            $additionalData = $this->getSchoolAdminStats();
        } elseif ($roleCatID === 3) {
            $view = 'app/dashboard/teacher';
            $additionalData = $this->getTeacherDashboardStats();
        } else {
            $view = 'app/dashboard/index';
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
    
    
	
}
