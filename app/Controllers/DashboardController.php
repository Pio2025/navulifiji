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
        $additionalData = [];

        if ($roleCatID === 3) {
            $view = 'app/dashboard/teacher';
            $additionalData = $this->getTeacherDashboardStats();
        } else {
            $view = 'app/dashboard/index';
        }

        $data = $this->loadCommonData($view, $additionalData);
        return view('app/layouts/main', $data);
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
