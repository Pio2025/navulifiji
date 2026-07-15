<?php
namespace App\Controllers;

class ClassroomController extends BaseController
{
    const VALID_ROLES = [
        'Class Teacher',
        'Assistant Class Teacher',
        'Class Captain',
        'Assistant Class Captain',
    ];

    // ================================================================
    // INDEX
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Classrooms', 'Classroom', 'All Classrooms');

        $accessCheck = $this->require_access('_classroom_listing');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $data['classrooms']   = $this->classroomModel->getAllWithDetails($schId);
        $data['canAdd']       = ($this->require_access('_add_classroom')    === true || $isSuperAdmin);
        $data['canEdit']      = ($this->require_access('_edit_classroom')   === true || $isSuperAdmin);
        $data['canDelete']    = ($this->require_access('_remove_classroom') === true || $isSuperAdmin);
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['_view']        = 'app/classroom/index';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // DETAIL
    // ================================================================

    public function detail(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $classroom = $this->classroomModel->getDetail($classId);
        if (!$classroom) {
            return redirect()->to('classroom')->with('error', 'Classroom not found.');
        }

        $this->setPageData('Classroom Detail', 'Classroom', 'Detail');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = (int) ($classroom['sch_id'] ?? 0);

        $data['classroom']    = $classroom;
        $data['canEdit']      = ($this->require_access('_edit_classroom')   === true || $isSuperAdmin);
        $data['canDelete']    = ($this->require_access('_remove_classroom') === true || $isSuperAdmin);
        $data['isSuperAdmin'] = $isSuperAdmin;

        $data['staff'] = [
            'Class Teacher'           => $this->classroomStaffModel->getActiveByRole($classId, 'Class Teacher'),
            'Assistant Class Teacher' => $this->classroomStaffModel->getActiveByRole($classId, 'Assistant Class Teacher'),
            'Class Captain'           => $this->classroomStaffModel->getActiveByRole($classId, 'Class Captain'),
            'Assistant Class Captain' => $this->classroomStaffModel->getActiveByRole($classId, 'Assistant Class Captain'),
        ];

        $sessionUserId  = (int) $this->session->get('userID');
        $classTeacher   = $data['staff']['Class Teacher'];
        $isClassTeacher = $classTeacher && (int)($classTeacher['user_id_fk'] ?? 0) === $sessionUserId;
        $data['canManageStudents'] = $data['canEdit'] || $isClassTeacher;

        $data['staffUsers']       = $schId ? $this->classroomModel->getStaffUsers($schId)   : [];
        $data['studentUsers']     = $schId ? $this->classroomModel->getStudentUsers($schId) : [];
        $data['classroomStudents'] = $this->classroomModel->getClassroomStudents($classId);

        $subjects = $this->classroomModel->getClassroomSubjectData($classId);
        $data['classroomSubjects'] = $subjects;

        $data['_view'] = 'app/classroom/detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ADD
    // ================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Add Classroom', 'Classroom', 'Add');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $data['streams']      = $this->classroomModel->getStreamsForSchool($schId);
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['currentYear']  = (int) date('Y');
        $data['_view']        = 'app/classroom/add';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STORE
    // ================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $streamId  = (int) $this->request->getPost('stream_id_fk');
            $className = trim($this->request->getPost('class_name') ?? '');
            $classYear = (int) $this->request->getPost('class_year');
            $status    = $this->request->getPost('class_status') ?? 'Active';

            if (!$streamId || !$className || !$classYear) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Stream, class name and year are required.'
                ]);
            }

            if (!$this->classroomModel->isClassNameUnique($className)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'A classroom named "' . $className . '" already exists. Please use a unique name.'
                ]);
            }

            $sessionUserId = (int) $this->session->get('userID');
            $now           = date('Y-m-d H:i:s');

            $classId = $this->classroomModel->insert([
                'stream_id_fk'     => $streamId,
                'class_name'       => $className,
                'class_year'       => $classYear,
                'class_created_at' => $now,
                'class_updated_at' => $now,
                'class_created_by' => $sessionUserId,
                'class_updated_by' => $sessionUserId,
                'class_status'     => $status,
            ]);

            if (!$classId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create classroom.'
                ]);
            }

            $this->userLogModel->insert([
                'user_id_fk'  => $sessionUserId,
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Classroom Created',
                'log_desc'    => 'Classroom "' . $className . '" created for year ' . $classYear,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-element-7"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'success',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Classroom created successfully.',
                'redirect' => base_url('classroom/detail/' . $classId),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::store] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // EDIT
    // ================================================================

    public function edit(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $classroom = $this->classroomModel->getDetail($classId);
        if (!$classroom) {
            return redirect()->to('classroom')->with('error', 'Classroom not found.');
        }

        $this->setPageData('Edit Classroom', 'Classroom', 'Edit');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $data['classroom']    = $classroom;
        $data['streams']      = $this->classroomModel->getStreamsForSchool($schId);
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['currentYear']  = (int) date('Y');
        $data['_view']        = 'app/classroom/edit';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // UPDATE
    // ================================================================

    public function update(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $classroom = $this->classroomModel->find($classId);
            if (!$classroom) {
                return $this->response->setJSON(['success' => false, 'message' => 'Classroom not found.']);
            }

            $streamId  = (int) $this->request->getPost('stream_id_fk');
            $className = trim($this->request->getPost('class_name') ?? '');
            $classYear = (int) $this->request->getPost('class_year');
            $status    = $this->request->getPost('class_status') ?? 'Active';

            if (!$streamId || !$className || !$classYear) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Stream, class name and year are required.'
                ]);
            }

            if (!$this->classroomModel->isClassNameUnique($className, $classId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'A classroom named "' . $className . '" already exists. Please use a unique name.'
                ]);
            }

            $sessionUserId = (int) $this->session->get('userID');

            $oldStatus = $classroom['class_status'] ?? '';

            $this->classroomModel->update($classId, [
                'stream_id_fk'     => $streamId,
                'class_name'       => $className,
                'class_year'       => $classYear,
                'class_updated_at' => date('Y-m-d H:i:s'),
                'class_updated_by' => $sessionUserId,
                'class_status'     => $status,
            ]);

            // Cascade status change to all related tables
            if ($status !== $oldStatus) {
                $db = \Config\Database::connect();

                $db->table('classroom_student')
                   ->where('class_id_fk', $classId)
                   ->update(['class_stud_status' => $status]);

                $classSubIds = array_column(
                    $db->table('classroom_subject')->select('class_sub_id')
                       ->where('class_id_fk', $classId)->get()->getResultArray(),
                    'class_sub_id'
                );
                if (!empty($classSubIds)) {
                    $db->table('classroom_subject_teacher')
                       ->whereIn('class_sub_id_fk', $classSubIds)
                       ->update(['class_sub_teacher_status' => $status]);
                }

                $db->table('classroom_role')
                   ->where('class_id_fk', $classId)
                   ->update(['cs_status' => $status]);
            }

            $this->userLogModel->insert([
                'user_id_fk'  => $sessionUserId,
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Classroom Updated',
                'log_desc'    => 'Classroom "' . $className . '" updated (ID: ' . $classId . ')',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-pencil"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'warning',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Classroom updated successfully.',
                'redirect' => base_url('classroom/detail/' . $classId),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::update] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DELETE
    // ================================================================

    public function delete(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $classroom = $this->classroomModel->find($classId);
            if (!$classroom) {
                return $this->response->setJSON(['success' => false, 'message' => 'Classroom not found.']);
            }

            $db = \Config\Database::connect();

            // Block deletion if staff are assigned
            $staffCount = $db->table('classroom_role')
                ->where('class_id_fk', $classId)
                ->where('cs_status', 'Active')
                ->countAllResults();

            if ($staffCount > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete this classroom — it has ' . $staffCount . ' staff member(s) assigned. Please remove all staff first.',
                ]);
            }

            // Block deletion if students are enrolled
            $studentCount = $db->table('enrolment')
                ->where('stream_id_fk', $classroom['stream_id_fk'])
                ->where('enrol_year',   $classroom['class_year'])
                ->countAllResults();

            if ($studentCount > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete this classroom — it has ' . $studentCount . ' enrolled student(s). Please remove all students first.',
                ]);
            }

            $this->classroomModel->delete($classId);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Classroom Deleted',
                'log_desc'    => 'Classroom "' . $classroom['class_name'] . '" deleted (ID: ' . $classId . ')',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'danger',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Classroom deleted successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::delete] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // MY CLASSROOM — role-based personal view
    // ================================================================

    public function myClassroom()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('My Classroom', 'Classroom', 'My Classroom');

        $userId    = (int) $this->session->get('userID');
        $roleCatId = (int) $this->session->get('roleCatID');

        if ($roleCatId === 3) {
            $sessionPhoto = $this->session->get('photo');
            $years        = $this->getTeacherClassroomYears($userId);
            $defaultYear  = $this->getTeacherDefaultYear($userId);
            $classrooms   = $defaultYear ? $this->getTeacherClassroomsForYear($userId, $defaultYear) : [];
            $data['classrooms']      = $classrooms;
            $data['years']           = $years;
            $data['defaultYear']     = $defaultYear;
            $data['sessionFname']    = $this->session->get('fname') ?? '';
            $data['sessionPhotoUrl'] = $sessionPhoto ? base_url('uploads/profilePhoto/' . $sessionPhoto) : null;
            $data['sessionUserId']   = $userId;
            $data['userId']          = $userId;
            $data['_view']           = 'app/classroom/teacher/my';

        } elseif ($roleCatId === 4) {
            $sessionPhoto = $this->session->get('photo');
            $years        = $this->getStudentClassroomYears($userId);
            $defaultYear  = $this->getStudentDefaultYear($userId);
            $classrooms   = $defaultYear ? $this->getStudentClassroomsForYear($userId, $defaultYear) : [];
            $data['classrooms']      = $classrooms;
            $data['years']           = $years;
            $data['defaultYear']     = $defaultYear;
            $data['sessionFname']    = $this->session->get('fname') ?? '';
            $data['sessionPhotoUrl'] = $sessionPhoto ? base_url('uploads/profilePhoto/' . $sessionPhoto) : null;
            $data['sessionUserId']   = $userId;
            $data['_view']           = 'app/classroom/student/my';

        } elseif ($roleCatId === 6) {
            $data['classrooms'] = $this->getChildrensClassrooms($userId);
            $data['_view']      = 'app/classroom/parent/my';

        } else {
            $user = $this->userModel->find($userId);
            if ($user && (int) ($user['is_a_parent'] ?? 0) === 1) {
                $data['classrooms'] = $this->getChildrensClassrooms($userId);
                $data['_view']      = 'app/classroom/parent/my';
            } else {
                // Check if this user is assigned as a Class Teacher even without roleCatId=3
                $years       = $this->getTeacherClassroomYears($userId);
                $defaultYear = $this->getTeacherDefaultYear($userId);
                if ($defaultYear) {
                    $sessionPhoto = $this->session->get('photo');
                    $data['classrooms']      = $this->getTeacherClassroomsForYear($userId, $defaultYear);
                    $data['years']           = $years;
                    $data['defaultYear']     = $defaultYear;
                    $data['sessionFname']    = $this->session->get('fname') ?? '';
                    $data['sessionPhotoUrl'] = $sessionPhoto ? base_url('uploads/profilePhoto/' . $sessionPhoto) : null;
                    $data['sessionUserId']   = $userId;
                    $data['userId']          = $userId;
                    $data['_view']           = 'app/classroom/teacher/my';
                } else {
                    $data['_view'] = 'app/classroom/my';
                    $data['mode']  = 'none';
                }
            }
        }

        // ── Principal: inject pending exam reports regardless of primary role ──
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $roleName     = strtolower(trim($this->session->get('roleName') ?? ''));
        $isPrincipal  = $isSuperAdmin
                     || $roleName === 'principal'
                     || ($this->require_access('_exam_publish') === true);
        if ($isPrincipal) {
            $db = \Config\Database::connect();
            $data['pendingExamReports'] = $db->query("
                SELECT trs.trs_id, trs.class_id_fk AS class_id, trs.term, trs.ct_submitted_at,
                       c.class_name, c.class_year, s.stream_name, sch.sch_name,
                       (SELECT COUNT(*) FROM classroom_student cs2
                        WHERE cs2.class_id_fk = c.class_id AND cs2.class_stud_status = 'Active') AS student_count,
                       (SELECT COUNT(*) FROM term_report_ct_comment ctc
                        WHERE ctc.class_id_fk = c.class_id AND ctc.term = trs.term) AS ct_comments,
                       (SELECT COUNT(*) FROM term_report_principal_comment prc
                        WHERE prc.class_id_fk = c.class_id AND prc.term = trs.term) AS prc_comments,
                       (SELECT CONCAT(u.fname,' ',u.lname) FROM classroom_role cr
                        INNER JOIN users u ON u.user_id = cr.user_id_fk
                        WHERE cr.class_id_fk = c.class_id AND cr.cs_role = 'Class Teacher'
                        AND cr.cs_status = 'Active' LIMIT 1) AS class_teacher
                FROM term_report_status trs
                INNER JOIN classroom c ON c.class_id = trs.class_id_fk
                INNER JOIN stream s ON s.stream_id = c.stream_id_fk
                INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
                INNER JOIN school sch ON sch.sch_id = sl.sch_id_fk
                WHERE trs.status = 'ct_submitted'
                ORDER BY trs.ct_submitted_at DESC
            ")->getResultArray();

            // Non-teacher principals without a specific view get a dedicated one
            if (!isset($data['_view']) || $data['_view'] === 'app/classroom/my') {
                $data['_view'] = 'app/classroom/principal/my';
            }
        }
        $data['isPrincipal'] = $isPrincipal;

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // GET STREAMS FOR A SCHOOL (AJAX — used by Add User enrollment)
    // ================================================================

    public function mySubject(int $schSubId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Subject Detail', 'Classroom', 'My Subject');

        $db  = \Config\Database::connect();
        $sub = $db->table('sch_subject')
            ->select('sch_subject.*, subject.subject_name, subject.sub_image, level.level_name, department.dept_name')
            ->join('subject',        'subject.subject_id         = sch_subject.subject_id_fk',  'left')
            ->join('level',          'level.level_id             = subject.level_id_fk',         'left')
            ->join('sch_department', 'sch_department.sch_dept_id = sch_subject.sch_dept_id_fk', 'left')
            ->join('department',     'department.dept_id         = sch_department.dept_id_fk',  'left')
            ->where('sch_subject.sch_sub_id', $schSubId)
            ->get()->getRowArray();

        if (!$sub) {
            return redirect()->to('classroom/my')->with('error', 'Subject not found.');
        }

        $data['subject'] = $sub;
        $data['_view']   = 'app/classroom/my_subject';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ELIGIBLE STUDENTS — AJAX GET classroom/students/eligible/{classId}
    // ================================================================

    public function eligibleStudents(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $classroom = $this->classroomModel->getDetail($classId);
        if (!$classroom) {
            return $this->response->setJSON(['success' => false, 'message' => 'Classroom not found.'])->setStatusCode(404);
        }

        $students = $this->classroomModel->getEligibleStudents(
            $classId,
            (int) $classroom['stream_id_fk'],
            (int) $classroom['class_year']
        );

        return $this->response->setJSON(['success' => true, 'students' => $students]);
    }

    // ================================================================
    // ADMIT STUDENTS — AJAX POST classroom/students/admit/{classId}
    // ================================================================

    public function admitStudents(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $userIds = $this->request->getPost('user_ids') ?? [];

            if (empty($userIds)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No students selected.']);
            }

            $db    = \Config\Database::connect();
            $count = 0;

            foreach ($userIds as $userId) {
                $userId = (int) $userId;
                if (!$userId) continue;

                $exists = $db->table('classroom_student')
                    ->where('class_id_fk', $classId)
                    ->where('user_id_fk',  $userId)
                    ->get()->getRowArray();
                if ($exists) continue;

                $db->table('classroom_student')->insert([
                    'class_id_fk'       => $classId,
                    'user_id_fk'        => $userId,
                    'class_stud_status' => 'Active',
                    'admitted_at'       => date('Y-m-d'),
                    'admitted_by'       => (int) $this->session->get('userID'),
                ]);
                $count++;
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $count . ' student' . ($count !== 1 ? 's' : '') . ' admitted successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::admitStudents] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // REMOVE STUDENT FROM CLASSROOM — AJAX POST classroom/students/remove/{classStudId}
    // ================================================================

    public function removeStudent(int $classStudId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $db     = \Config\Database::connect();
            $record = $db->table('classroom_student')
                ->where('class_stud_id', $classStudId)
                ->get()->getRowArray();

            if (!$record) {
                return $this->response->setJSON(['success' => false, 'message' => 'Student record not found.']);
            }

            $classId      = (int) $record['class_id_fk'];
            $isSuperAdmin = (int) $this->session->get('roleID') === 1;
            $canEdit      = $this->require_access('_edit_classroom') === true || $isSuperAdmin;
            $userId       = (int) $this->session->get('userID');

            if (!$canEdit) {
                $isClassTeacher = (bool) $db->table('classroom_role')
                    ->where('class_id_fk', $classId)
                    ->where('user_id_fk',  $userId)
                    ->where('cs_role',     'Class Teacher')
                    ->where('cs_status',   'Active')
                    ->get()->getRowArray();

                if (!$isClassTeacher) {
                    return $this->response->setJSON(['success' => false, 'message' => 'You do not have permission to remove students.']);
                }
            }

            $db->table('classroom_student')->where('class_stud_id', $classStudId)->delete();

            return $this->response->setJSON(['success' => true, 'message' => 'Student removed from classroom.']);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::removeStudent] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function getStreams(int $schId)
    {
        $streams = $this->classroomModel->getStreamsForSchool($schId);
        return $this->response->setJSON([
            'success' => true,
            'streams' => $streams,
        ]);
    }

    // ================================================================
    // ASSIGN STAFF / CAPTAIN
    // ================================================================

    public function assignStaff(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $classroom = $this->classroomModel->find($classId);
            if (!$classroom) {
                return $this->response->setJSON(['success' => false, 'message' => 'Classroom not found.']);
            }

            $userId = (int) $this->request->getPost('user_id_fk');
            $role   = trim($this->request->getPost('cs_role') ?? '');

            if (!$userId || !in_array($role, self::VALID_ROLES)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid data provided.']);
            }

            $sessionUserId = (int) $this->session->get('userID');

            $this->classroomStaffModel->deactivateByRole($classId, $role);

            $this->classroomStaffModel->insert([
                'class_id_fk'    => $classId,
                'user_id_fk'     => $userId,
                'cs_role'        => $role,
                'cs_status'      => 'Active',
                'cs_assigned_at' => date('Y-m-d H:i:s'),
                'cs_assigned_by' => $sessionUserId,
            ]);

            $user = $this->userModel->find($userId);
            $name = trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''));

            $this->userLogModel->insert([
                'user_id_fk'  => $sessionUserId,
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Staff Assigned',
                'log_desc'    => '"' . $name . '" assigned as ' . $role . ' for classroom ID ' . $classId,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-people"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'primary',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => $name . ' has been assigned as ' . $role . '.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::assignStaff] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // ASSIGN SUBJECT TEACHER
    // ================================================================

    // ================================================================
    // AVAILABLE SUBJECTS FOR CLASSROOM — AJAX GET
    // ================================================================

    public function availableSubjectsForClassroom(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }
        $data = $this->classroomModel->getAvailableSubjectsForClassroom($classId);
        return $this->response->setJSON(['success' => true, 'core' => $data['core'], 'optional' => $data['optional']]);
    }

    // ================================================================
    // ADD CLASSROOM SUBJECTS — AJAX POST classroom/subjects/add/{classId}
    // ================================================================

    public function addClassroomSubjects(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        try {
            $schSubIds = $this->request->getPost('sch_sub_ids') ?? [];
            if (empty($schSubIds)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No subjects selected.']);
            }
            $db    = \Config\Database::connect();
            $count = 0;
            foreach ($schSubIds as $schSubId) {
                $schSubId = (int) $schSubId;
                if (!$schSubId) continue;
                $exists = $db->table('classroom_subject')
                    ->where('class_id_fk', $classId)->where('sub_id_fk', $schSubId)
                    ->get()->getRowArray();
                if ($exists) continue;
                $db->table('classroom_subject')->insert(['class_id_fk' => $classId, 'sub_id_fk' => $schSubId]);
                $count++;
            }
            return $this->response->setJSON([
                'success' => true,
                'message' => $count . ' subject' . ($count !== 1 ? 's' : '') . ' added.',
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::addClassroomSubjects] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // ASSIGN CLASSROOM SUBJECT TEACHER — AJAX POST
    // ================================================================

    public function assignClassroomSubjectTeacher()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        try {
            $classSubId = (int) $this->request->getPost('class_sub_id');
            $userId     = (int) $this->request->getPost('user_id');
            if (!$classSubId || !$userId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid data.']);
            }
            $db = \Config\Database::connect();
            // Deactivate existing active teacher for this class_sub
            $db->table('classroom_subject_teacher')
               ->where('class_sub_id_fk', $classSubId)
               ->where('class_sub_teacher_status', 'Active')
               ->update(['class_sub_teacher_status' => 'Inactive']);
            // Insert new
            $db->table('classroom_subject_teacher')->insert([
                'class_sub_id_fk'         => $classSubId,
                'user_id_fk'              => $userId,
                'class_sub_teacher_status'=> 'Active',
            ]);
            $user = $this->userModel->find($userId);
            $name = trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''));
            return $this->response->setJSON(['success' => true, 'message' => $name . ' assigned as subject teacher.']);
        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::assignClassroomSubjectTeacher] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function assignSubjectTeacher()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $classId  = (int) $this->request->getPost('class_id');
            $schSubId = (int) $this->request->getPost('sch_sub_id');
            $userId   = (int) $this->request->getPost('user_id');

            if (!$classId || !$schSubId || !$userId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid data provided.']);
            }

            $classroom = $this->classroomModel->getDetail($classId);
            if (!$classroom) {
                return $this->response->setJSON(['success' => false, 'message' => 'Classroom not found.']);
            }

            $schId = (int) ($classroom['sch_id'] ?? 0);
            $db    = \Config\Database::connect();

            $admission = $db->query(
                "SELECT admission_id FROM admission WHERE user_id_fk = ? AND sch_id_fk = ? AND admission_status = 'Active' ORDER BY admission_date DESC LIMIT 1",
                [$userId, $schId]
            )->getRow();

            if (!$admission) {
                return $this->response->setJSON(['success' => false, 'message' => 'Selected teacher has no active admission at this school.']);
            }

            // Replace existing teacher for this subject+school (one teacher per subject)
            $db->query(
                "DELETE ats FROM admission_teaching_subject ats
                 INNER JOIN admission a ON a.admission_id = ats.admission_id_fk AND a.sch_id_fk = ?
                 WHERE ats.sch_sub_id_fk = ?",
                [$schId, $schSubId]
            );

            $db->table('admission_teaching_subject')->insert([
                'admission_id_fk' => $admission->admission_id,
                'sch_sub_id_fk'   => $schSubId,
                'created_date'    => date('Y-m-d'),
                'created_time'    => time(),
            ]);

            $user = $this->userModel->find($userId);
            $name = trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''));

            $this->userLogModel->insert([
                'user_id_fk'  => (int) $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Subject Teacher Assigned',
                'log_desc'    => '"' . $name . '" assigned to teach "' . ($classroom['stream_name'] ?? '') . '" subject ID ' . $schSubId,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-book"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'success',
            ]);

            return $this->response->setJSON(['success' => true, 'message' => $name . ' assigned as subject teacher.']);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::assignSubjectTeacher] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // STREAM SUBJECTS WITH TEACHER ASSIGNMENT (private helper)
    // ================================================================

    private function getStreamSubjectsData(int $streamId, int $schId): array
    {
        if (!$streamId || !$schId) {
            return ['core' => [], 'optional' => []];
        }

        $db = \Config\Database::connect();

        // Subquery: one teacher per subject at this school
        $teacherJoin = "
            LEFT JOIN (
                SELECT ats.sch_sub_id_fk,
                       u.user_id   AS teacher_id,
                       CONCAT(u.fname, ' ', u.lname) AS teacher_name,
                       u.profile_photo AS teacher_photo
                FROM admission_teaching_subject ats
                INNER JOIN admission a ON a.admission_id = ats.admission_id_fk
                    AND a.sch_id_fk = ? AND a.admission_status = 'Active'
                INNER JOIN users u ON u.user_id = a.user_id_fk AND u.user_status = 'Active'
                GROUP BY ats.sch_sub_id_fk
            ) t ON t.sch_sub_id_fk = ss.sch_sub_id
        ";

        $core = $db->query("
            SELECT scs.stream_core_sub_id, ss.sch_sub_id, s.subject_name,
                   t.teacher_id, t.teacher_name, t.teacher_photo
            FROM stream_core_subject scs
            INNER JOIN sch_subject ss ON ss.sch_sub_id = scs.sch_sub_id_fk
            INNER JOIN subject s ON s.subject_id = ss.subject_id_fk
            $teacherJoin
            WHERE scs.stream_id_fk = ?
            ORDER BY s.subject_name
        ", [$schId, $streamId])->getResultArray();

        $optRows = $db->query("
            SELECT sos.stream_opt_sub_id, sos.option_num, ss.sch_sub_id, s.subject_name,
                   t.teacher_id, t.teacher_name, t.teacher_photo
            FROM stream_optional_subject sos
            INNER JOIN sch_subject ss ON ss.sch_sub_id = sos.sch_sub_id_fk
            INNER JOIN subject s ON s.subject_id = ss.subject_id_fk
            $teacherJoin
            WHERE sos.stream_id_fk = ?
            ORDER BY sos.option_num, s.subject_name
        ", [$schId, $streamId])->getResultArray();

        $optional = [];
        foreach ($optRows as $row) {
            $optional[(int) $row['option_num']][] = $row;
        }

        return ['core' => $core, 'optional' => $optional];
    }

    // ================================================================
    // PRIVATE: enrolled classrooms for a student
    // ================================================================

    // ================================================================
    // TEACHER ASSIGNMENTS — POST classroom/teacher/{schSubId}/assignment/store|update|delete
    // ================================================================

    private const ASGN_ALLOWED_EXTS = ['pdf','jpg','jpeg','png','gif','webp','xls','xlsx','ppt','pptx','doc','docx','txt','zip','tar'];


    /**
     * Returns ['assignment_id' => [['assign_file_id' => ?, 'file_src' => ..], ...]], combining
     * the new lesson_assignment_file rows with any legacy single-file assignment_file value.
     */
    private function getAssignmentFilesGrouped(\CodeIgniter\Database\BaseConnection $db, array $assignmentIds, array $legacyByAssignment = []): array
    {
        $grouped = [];
        if (!empty($assignmentIds)) {
            foreach ($db->table('lesson_assignment_file')->whereIn('assignment_id_fk', $assignmentIds)->get()->getResultArray() as $f) {
                $grouped[$f['assignment_id_fk']][] = $f;
            }
        }
        foreach ($legacyByAssignment as $assignmentId => $legacyFile) {
            if (!$legacyFile) continue;
            if (!isset($grouped[$assignmentId])) $grouped[$assignmentId] = [];
            array_unshift($grouped[$assignmentId], [
                'assign_file_id'   => null,
                'assignment_id_fk' => $assignmentId,
                'file_src'         => $legacyFile,
                'file_type'        => null,
            ]);
        }
        return $grouped;
    }

    private function resolveTeacherClassSub(int $schSubId, int $userId, bool $anyStatus = false): ?array
    {
        $db = \Config\Database::connect();
        $statusClause = $anyStatus ? '' : "AND cst.class_sub_teacher_status = 'Active'";
        return $db->query("
            SELECT cs.class_sub_id, cs.class_id_fk AS class_id
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject cs ON cs.class_sub_id = cst.class_sub_id_fk
            WHERE cs.sub_id_fk = ? AND cst.user_id_fk = ? {$statusClause}
            LIMIT 1
        ", [$schSubId, $userId])->getRowArray() ?: null;
    }

    public function teacherAssignmentStore(int $schSubId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthenticated']);
        $userId = (int) $this->session->get('userID');
        $assign = $this->resolveTeacherClassSub($schSubId, $userId, true);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $db          = \Config\Database::connect();
        $classStatus = $db->table('classroom')->select('class_status')->where('class_id', $assign['class_id'])->get()->getRowArray();
        if (!$classStatus || $classStatus['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot add assignment — this classroom is no longer active.']);
        }

        $name    = trim($this->request->getPost('assignment_name') ?? '');
        $dueDate = trim($this->request->getPost('assignment_due_date') ?? '');
        if (!$name) return $this->response->setJSON(['success' => false, 'message' => 'Assignment name is required.']);

        $files = $this->request->getFileMultiple('assignment_files');
        foreach ($files ?? [] as $file) {
            if ($file && $file->isValid() && !$file->hasMoved() && !in_array(strtolower($file->getClientExtension()), self::ASGN_ALLOWED_EXTS)) {
                return $this->response->setJSON(['success' => false, 'message' => 'File type not allowed. Accepted: PDF, images, Word, Excel, PowerPoint, TXT, ZIP, TAR.']);
            }
        }

        $totalScore = (float) ($this->request->getPost('assignment_total_score') ?? 100);
        if ($totalScore <= 0) $totalScore = 100;

        $db->table('lesson_assignment')->insert([
            'class_sub_id_fk'        => $assign['class_sub_id'],
            'class_id_fk'            => $assign['class_id'],
            'assignment_name'        => $name,
            'assignment_due_date'    => $dueDate ?: null,
            'assignment_total_score' => $totalScore,
            'assignment_status'      => 'Draft',
            'created_at'             => date('Y-m-d H:i:s'),
            'created_by'             => $userId,
        ]);
        $assignmentId = $db->insertID();

        foreach ($files ?? [] as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) continue;
            $ext      = strtolower($file->getClientExtension());
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/assignments/', $fileName);
            $db->table('lesson_assignment_file')->insert([
                'assignment_id_fk' => $assignmentId,
                'file_src'         => $fileName,
                'file_type'        => strtolower($ext),
            ]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Assignment created.']);
    }

    public function teacherAssignmentUpdate(int $schSubId, int $assignmentId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthenticated']);
        $userId = (int) $this->session->get('userID');
        $assign = $this->resolveTeacherClassSub($schSubId, $userId, true);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $db          = \Config\Database::connect();
        $classStatus = $db->table('classroom')->select('class_status')->where('class_id', $assign['class_id'])->get()->getRowArray();
        if (!$classStatus || $classStatus['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot edit assignment — this classroom is no longer active.']);
        }

        $row = $db->table('lesson_assignment')
                  ->where('assignment_id', $assignmentId)
                  ->where('class_sub_id_fk', $assign['class_sub_id'])
                  ->get()->getRowArray();
        if (!$row) return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found.']);
        if ($row['assignment_status'] === 'Published') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot edit a published assignment.']);
        }

        $name    = trim($this->request->getPost('assignment_name') ?? '');
        $dueDate = trim($this->request->getPost('assignment_due_date') ?? '');
        $status  = $this->request->getPost('assignment_status') ?? 'Draft';
        if (!$name) return $this->response->setJSON(['success' => false, 'message' => 'Assignment name is required.']);

        $editTotal = (float) ($this->request->getPost('assignment_total_score') ?? $row['assignment_total_score'] ?? 100);
        if ($editTotal <= 0) $editTotal = 100;

        $update = [
            'assignment_name'        => $name,
            'assignment_due_date'    => $dueDate ?: null,
            'assignment_total_score' => $editTotal,
            'assignment_status'      => in_array($status, ['Draft', 'Published', 'Archived']) ? $status : 'Draft',
            'updated_at'             => date('Y-m-d H:i:s'),
            'updated_by'             => $userId,
        ];

        // Additional files (appended to the existing file set)
        $files = $this->request->getFileMultiple('assignment_files');
        foreach ($files ?? [] as $file) {
            if ($file && $file->isValid() && !$file->hasMoved() && !in_array(strtolower($file->getClientExtension()), self::ASGN_ALLOWED_EXTS)) {
                return $this->response->setJSON(['success' => false, 'message' => 'File type not allowed. Accepted: PDF, images, Word, Excel, PowerPoint, TXT, ZIP, TAR.']);
            }
        }

        $db->table('lesson_assignment')->where('assignment_id', $assignmentId)->update($update);

        foreach ($files ?? [] as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) continue;
            $ext      = strtolower($file->getClientExtension());
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/assignments/', $fileName);
            $db->table('lesson_assignment_file')->insert([
                'assignment_id_fk' => $assignmentId,
                'file_src'         => $fileName,
                'file_type'        => strtolower($ext),
            ]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Assignment updated.']);
    }

    public function teacherAssignmentFileDelete(int $schSubId, int $assignmentId, int $fileId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthenticated']);
        $userId = (int) $this->session->get('userID');
        $assign = $this->resolveTeacherClassSub($schSubId, $userId, true);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $db  = \Config\Database::connect();
        $row = $db->table('lesson_assignment')
                  ->where('assignment_id', $assignmentId)
                  ->where('class_sub_id_fk', $assign['class_sub_id'])
                  ->get()->getRowArray();
        if (!$row) return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found.']);
        if ($row['assignment_status'] === 'Published') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot edit a published assignment.']);
        }

        $fileRow = $db->table('lesson_assignment_file')
                      ->where('assign_file_id', $fileId)
                      ->where('assignment_id_fk', $assignmentId)
                      ->get()->getRowArray();
        if (!$fileRow) return $this->response->setJSON(['success' => false, 'message' => 'File not found.']);

        if ($fileRow['file_src'] && file_exists(FCPATH . 'uploads/assignments/' . $fileRow['file_src'])) {
            unlink(FCPATH . 'uploads/assignments/' . $fileRow['file_src']);
        }
        $db->table('lesson_assignment_file')->where('assign_file_id', $fileId)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'File removed.']);
    }

    public function teacherAssignmentDelete(int $schSubId, int $assignmentId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthenticated']);
        $userId = (int) $this->session->get('userID');
        $assign = $this->resolveTeacherClassSub($schSubId, $userId, true);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $db          = \Config\Database::connect();
        $classStatus = $db->table('classroom')->select('class_status')->where('class_id', $assign['class_id'])->get()->getRowArray();
        if (!$classStatus || $classStatus['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot delete assignment — this classroom is no longer active.']);
        }

        $row = $db->table('lesson_assignment')
                  ->where('assignment_id', $assignmentId)
                  ->where('class_sub_id_fk', $assign['class_sub_id'])
                  ->get()->getRowArray();
        if (!$row) return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found.']);
        if ($row['assignment_status'] === 'Published') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot delete a published assignment.']);
        }

        if ($row['assignment_file'] && file_exists(FCPATH . 'uploads/assignments/' . $row['assignment_file'])) {
            unlink(FCPATH . 'uploads/assignments/' . $row['assignment_file']);
        }
        foreach ($db->table('lesson_assignment_file')->where('assignment_id_fk', $assignmentId)->get()->getResultArray() as $fileRow) {
            if ($fileRow['file_src'] && file_exists(FCPATH . 'uploads/assignments/' . $fileRow['file_src'])) {
                unlink(FCPATH . 'uploads/assignments/' . $fileRow['file_src']);
            }
        }
        $db->table('lesson_assignment_file')->where('assignment_id_fk', $assignmentId)->delete();
        $db->table('lesson_assignment')->where('assignment_id', $assignmentId)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Assignment deleted.']);
    }

    // ================================================================
    // STUDENT ASSIGNMENT ASSESSMENT
    // ================================================================

    public function studentAssignmentAssessment(int $classSubId, int $assignmentId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $userId = (int) $this->session->get('userID');
        if ((int) $this->session->get('roleCatID') !== 4) return redirect()->to('classroom/my');

        $cs = $this->resolveStudentClassSub($classSubId, $userId);
        if (!$cs) return redirect()->to('classroom/my')->with('error', 'Access denied.');

        $db = \Config\Database::connect();
        $assignment = $db->query("
            SELECT a.*, CONCAT(u.fname,' ',u.lname) AS creator_name
            FROM lesson_assignment a
            LEFT JOIN users u ON u.user_id = a.created_by
            WHERE a.assignment_id = ? AND a.class_sub_id_fk = ? AND a.assignment_status = 'Published'
            LIMIT 1
        ", [$assignmentId, $classSubId])->getRowArray();

        if (!$assignment) return redirect()->to('classroom/student/' . $classSubId . '/assignments')->with('error', 'Assignment not found.');

        $submission = $db->table('assignment_submission')
            ->where('assignment_id_fk', $assignmentId)->where('user_id_fk', $userId)
            ->get()->getRowArray();

        $score = $submission ? $db->table('student_assignment_score')
            ->where('assignment_id_fk', $assignmentId)->where('user_id_fk', $userId)
            ->get()->getRowArray() : null;

        $classStats = $db->query("
            SELECT ROUND(AVG(assignment_mark),2) AS avg_mark,
                   MAX(assignment_mark) AS high_mark,
                   MIN(assignment_mark) AS low_mark,
                   COUNT(*) AS graded_count
            FROM student_assignment_score WHERE assignment_id_fk = ?
        ", [$assignmentId])->getRowArray();

        $this->setPageData('Assessment', 'Classroom', 'Assignment');
        $data['assignment']  = $assignment;
        $data['submission']  = $submission;
        $data['score']       = $score;
        $data['classStats']  = $classStats;
        $data['classSubId']  = $classSubId;
        $data['_view']       = 'app/classroom/student/assignment_assess';
        return view('app/layouts/main', $data);
    }

    // ================================================================
    // TEACHER ASSIGNMENT MARK + ANALYSIS
    // ================================================================

    public function teacherAssignmentMark(int $schSubId, int $assignmentId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $userId = (int) $this->session->get('userID');
        if ((int) $this->session->get('roleCatID') !== 3) return redirect()->to('classroom/my');

        $assign = $this->resolveTeacherClassSub($schSubId, $userId);
        if (!$assign) return redirect()->to('classroom/my')->with('error', 'Access denied.');

        $db = \Config\Database::connect();
        $assignment = $db->query("
            SELECT a.*, CONCAT(u.fname,' ',u.lname) AS creator_name
            FROM lesson_assignment a LEFT JOIN users u ON u.user_id = a.created_by
            WHERE a.assignment_id = ? AND a.class_sub_id_fk = ? LIMIT 1
        ", [$assignmentId, $assign['class_sub_id']])->getRowArray();

        if (!$assignment) return redirect()->to('classroom/teacher/' . $schSubId . '/assignments')->with('error', 'Assignment not found.');

        $assignment['files'] = $this->getAssignmentFilesGrouped(
            $db, [$assignmentId], [$assignmentId => $assignment['assignment_file']]
        )[$assignmentId] ?? [];

        $submissions = $db->query("
            SELECT asub.*, CONCAT(u.fname,' ',u.lname) AS student_name, u.profile_photo, u.user_id AS student_user_id,
                   sas.score_id, sas.assignment_mark, sas.feedback AS score_feedback, sas.graded_at,
                   aplg.status AS plagiarism_status, aplg.score AS plagiarism_score
            FROM assignment_submission asub
            INNER JOIN users u ON u.user_id = asub.user_id_fk
            LEFT JOIN student_assignment_score sas ON sas.assignment_id_fk = asub.assignment_id_fk AND sas.user_id_fk = asub.user_id_fk
            LEFT JOIN assignment_plagiarism aplg ON aplg.submission_id_fk = asub.submission_id
            WHERE asub.assignment_id_fk = ?
            ORDER BY asub.submitted_at ASC
        ", [$assignmentId])->getResultArray();

        $this->setPageData('Mark Assignment', 'Classroom', 'Assignment');
        $data['assignment']  = $assignment;
        $data['submissions'] = $submissions;
        $data['schSubId']    = $schSubId;
        $data['_view']       = 'app/classroom/teacher/assignment_mark';
        return view('app/layouts/main', $data);
    }

    public function teacherAssignmentMarkSave(int $schSubId, int $assignmentId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthenticated']);
        $userId = (int) $this->session->get('userID');
        if ((int) $this->session->get('roleCatID') !== 3) return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);

        $assign = $this->resolveTeacherClassSub($schSubId, $userId);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $db = \Config\Database::connect();
        $assignment = $db->table('lesson_assignment')
            ->where('assignment_id', $assignmentId)->where('class_sub_id_fk', $assign['class_sub_id'])
            ->get()->getRowArray();
        if (!$assignment) return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found.']);

        $submissionId = (int) $this->request->getPost('submission_id');
        $studentId    = (int) $this->request->getPost('user_id');
        $markVal      = $this->request->getPost('assignment_mark');
        $feedback     = trim($this->request->getPost('feedback') ?? '');

        if ($markVal === null || $markVal === '') return $this->response->setJSON(['success' => false, 'message' => 'Mark is required.']);

        $mark       = (float) $markVal;
        $totalScore = (float) ($assignment['assignment_total_score'] ?? 100);
        if ($mark < 0 || $mark > $totalScore) {
            return $this->response->setJSON(['success' => false, 'message' => "Mark must be between 0 and {$totalScore}."]);
        }

        // Only allow marking after the due date has passed
        if (!empty($assignment['assignment_due_date']) && strtotime($assignment['assignment_due_date']) > time()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Marking is only allowed after the assignment due date has passed.']);
        }

        $submission = $db->table('assignment_submission')
            ->where('submission_id', $submissionId)->where('assignment_id_fk', $assignmentId)
            ->where('user_id_fk', $studentId)->get()->getRowArray();
        if (!$submission) return $this->response->setJSON(['success' => false, 'message' => 'Submission not found.']);

        $existing = $db->table('student_assignment_score')
            ->where('assignment_id_fk', $assignmentId)->where('user_id_fk', $studentId)
            ->get()->getRowArray();

        $now = date('Y-m-d H:i:s');
        $row = ['assignment_mark' => $mark, 'feedback' => $feedback ?: null, 'graded_by' => $userId, 'graded_at' => $now, 'updated_at' => $now];

        if ($existing) {
            $db->table('student_assignment_score')->where('score_id', $existing['score_id'])->update($row);
        } else {
            $db->table('student_assignment_score')->insert(array_merge($row, [
                'assignment_id_fk' => $assignmentId,
                'submission_id_fk' => $submissionId,
                'user_id_fk'       => $studentId,
            ]));
        }

        $db->table('assignment_submission')->where('submission_id', $submissionId)->update(['submission_status' => 'Graded', 'updated_at' => $now]);

        $pct = $totalScore > 0 ? round(($mark / $totalScore) * 100, 1) : 0;
        return $this->response->setJSON(['success' => true, 'mark' => $mark, 'total' => $totalScore, 'pct' => $pct, 'feedback' => $feedback]);
    }

    public function teacherAssignmentAnalysis(int $schSubId, int $assignmentId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $userId = (int) $this->session->get('userID');
        if ((int) $this->session->get('roleCatID') !== 3) return redirect()->to('classroom/my');

        $assign = $this->resolveTeacherClassSub($schSubId, $userId);
        if (!$assign) return redirect()->to('classroom/my')->with('error', 'Access denied.');

        $db = \Config\Database::connect();
        $assignment = $db->query("
            SELECT a.*, CONCAT(u.fname,' ',u.lname) AS creator_name
            FROM lesson_assignment a LEFT JOIN users u ON u.user_id = a.created_by
            WHERE a.assignment_id = ? AND a.class_sub_id_fk = ? LIMIT 1
        ", [$assignmentId, $assign['class_sub_id']])->getRowArray();

        if (!$assignment) return redirect()->to('classroom/teacher/' . $schSubId . '/assignments')->with('error', 'Assignment not found.');

        $totalScore    = (float) ($assignment['assignment_total_score'] ?? 100);
        $enrolledCount = (int) $db->query("SELECT COUNT(*) AS c FROM classroom_student WHERE class_id_fk=? AND class_stud_status='Active'", [$assign['class_id']])->getRowArray()['c'];

        $subStats = $db->query("
            SELECT COUNT(*) AS total_submitted,
                   SUM(submission_status='Graded') AS total_graded,
                   SUM(submission_status='Late') AS total_late
            FROM assignment_submission WHERE assignment_id_fk=?
        ", [$assignmentId])->getRowArray();

        $scoreStats = $db->query("
            SELECT ROUND(AVG(assignment_mark),2) AS avg_mark,
                   MAX(assignment_mark) AS high_mark,
                   MIN(assignment_mark) AS low_mark,
                   COUNT(*) AS graded_count
            FROM student_assignment_score WHERE assignment_id_fk=?
        ", [$assignmentId])->getRowArray();

        $scoreDist = [0,0,0,0,0];
        foreach ($db->query("SELECT assignment_mark FROM student_assignment_score WHERE assignment_id_fk=?", [$assignmentId])->getResultArray() as $r) {
            $pct = $totalScore > 0 ? ($r['assignment_mark'] / $totalScore) * 100 : 0;
            $scoreDist[min((int)floor($pct / 20), 4)]++;
        }

        $students = $db->query("
            SELECT CONCAT(u.fname,' ',u.lname) AS student_name, u.profile_photo,
                   asub.submitted_at, asub.submission_status, asub.submission_file, asub.submission_file_type,
                   sas.assignment_mark, sas.feedback AS score_feedback, sas.graded_at
            FROM classroom_student cs
            INNER JOIN users u ON u.user_id = cs.user_id_fk
            LEFT JOIN assignment_submission asub ON asub.user_id_fk=cs.user_id_fk AND asub.assignment_id_fk=?
            LEFT JOIN student_assignment_score sas ON sas.user_id_fk=cs.user_id_fk AND sas.assignment_id_fk=?
            WHERE cs.class_id_fk=? AND cs.class_stud_status='Active'
            ORDER BY u.lname, u.fname
        ", [$assignmentId, $assignmentId, $assign['class_id']])->getResultArray();

        $this->setPageData('Assignment Analysis', 'Classroom', 'Analysis');
        $data['assignment']    = $assignment;
        $data['enrolledCount'] = $enrolledCount;
        $data['subStats']      = $subStats;
        $data['scoreStats']    = $scoreStats;
        $data['scoreDist']     = $scoreDist;
        $data['students']      = $students;
        $data['schSubId']      = $schSubId;
        $data['totalScore']    = $totalScore;
        $data['_view']         = 'app/classroom/teacher/assignment_analysis';
        return view('app/layouts/main', $data);
    }

    // ================================================================
    // TEACHER CLASSROOM LMS — GET classroom/teacher/{classId}/{section}
    // ================================================================

    public function teacherClassroom(int $schSubId, string $section = 'dashboard')
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $roleCatId = (int) $this->session->get('roleCatID');
        if ($roleCatId !== 3) {
            return redirect()->to('classroom/my');
        }

        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        // Resolve the teacher's classroom_subject assignment for this school subject (any status)
        $assignment = $db->query("
            SELECT cs.class_sub_id, cs.class_id_fk AS class_id
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject cs ON cs.class_sub_id = cst.class_sub_id_fk
            WHERE cs.sub_id_fk = ? AND cst.user_id_fk = ?
            ORDER BY cs.class_id_fk DESC
            LIMIT 1
        ", [$schSubId, $userId])->getRowArray();

        if (!$assignment) {
            return redirect()->to('classroom/my')->with('error', 'You do not have access to this subject.');
        }

        $classId    = (int) $assignment['class_id'];
        $classSubId = (int) $assignment['class_sub_id'];

        // Load subject details
        $subjectData = $db->query("
            SELECT ss.sch_sub_id, sub.subject_name, sub.sub_image, l.level_name, d.dept_name
            FROM sch_subject ss
            INNER JOIN subject sub   ON sub.subject_id  = ss.subject_id_fk
            LEFT  JOIN level l       ON l.level_id      = sub.level_id_fk
            LEFT  JOIN sch_department sd ON sd.sch_dept_id = ss.sch_dept_id_fk
            LEFT  JOIN department d  ON d.dept_id       = sd.dept_id_fk
            WHERE ss.sch_sub_id = ?
        ", [$schSubId])->getRowArray();

        // Load classroom card data — no status filter for past classroom access
        $classroomCard = $db->query("
            SELECT
                c.class_id, c.class_name, c.class_year, c.class_status,
                s.stream_name, l.level_name AS class_level_name, sch.sch_name,
                (SELECT u1.user_id                    FROM classroom_role r1 INNER JOIN users u1 ON u1.user_id=r1.user_id_fk WHERE r1.class_id_fk=c.class_id AND r1.cs_role='Class Teacher'  LIMIT 1) AS class_teacher_id,
                (SELECT CONCAT(u1.fname,' ',u1.lname) FROM classroom_role r1 INNER JOIN users u1 ON u1.user_id=r1.user_id_fk WHERE r1.class_id_fk=c.class_id AND r1.cs_role='Class Teacher'  LIMIT 1) AS class_teacher,
                (SELECT u1.profile_photo              FROM classroom_role r1 INNER JOIN users u1 ON u1.user_id=r1.user_id_fk WHERE r1.class_id_fk=c.class_id AND r1.cs_role='Class Teacher'  LIMIT 1) AS class_teacher_photo,
                (SELECT u2.user_id                    FROM classroom_role r2 INNER JOIN users u2 ON u2.user_id=r2.user_id_fk WHERE r2.class_id_fk=c.class_id AND r2.cs_role='Class Captain' LIMIT 1) AS class_captain_id,
                (SELECT CONCAT(u2.fname,' ',u2.lname) FROM classroom_role r2 INNER JOIN users u2 ON u2.user_id=r2.user_id_fk WHERE r2.class_id_fk=c.class_id AND r2.cs_role='Class Captain' LIMIT 1) AS class_captain,
                (SELECT u2.profile_photo              FROM classroom_role r2 INNER JOIN users u2 ON u2.user_id=r2.user_id_fk WHERE r2.class_id_fk=c.class_id AND r2.cs_role='Class Captain' LIMIT 1) AS class_captain_photo,
                (SELECT COUNT(*) FROM classroom_student cs2 WHERE cs2.class_id_fk=c.class_id) AS student_count
            FROM classroom c
            INNER JOIN stream s     ON s.stream_id      = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id  = s.sch_level_id_fk
            INNER JOIN level l      ON l.level_id       = sl.level_id_fk
            INNER JOIN school sch   ON sch.sch_id       = sl.sch_id_fk
            WHERE c.class_id = ?
        ", [$classId])->getRowArray();

        $section = strtolower($section);

        $this->setPageData($subjectData['subject_name'] ?? 'Subject', 'Classroom', ucfirst($section));

        $data['subjectData']        = $subjectData;
        $data['classroomCard']      = $classroomCard;
        $data['section']            = $section;
        $data['schSubId']           = $schSubId;
        $data['classSubId']         = $classSubId;
        $data['classId']            = $classId;
        $data['classroomIsActive']  = ($classroomCard['class_status'] ?? '') === 'Active';

        if ($section === 'lessons') {
            $data['lessons']         = $this->classroomLessonModel->getLessonsForSubject($classSubId);
            $data['teacherSubjects'] = $this->classroomLessonModel->getTeacherSubjectsInClassroom($classId, $userId);

            // Year for the schedule view; default to the classroom's own year
            $lessonYear = (int) ($this->request->getGet('year') ?: ($classroomCard['class_year'] ?? date('Y')));

            // Load school_config for the selected year; fall back to latest row
            $schoolConfig = $db->query(
                "SELECT * FROM school_config WHERE sch_year = ? LIMIT 1", [$lessonYear]
            )->getRowArray();
            if (!$schoolConfig) {
                $schoolConfig = $db->query(
                    "SELECT * FROM school_config ORDER BY sch_config_id DESC LIMIT 1"
                )->getRowArray();
            }

            // Build week/day calendar per term
            $termSchedule = [];
            $activeTerm   = 1;
            if ($schoolConfig) {
                $today = new \DateTime('today');
                for ($t = 1; $t <= 3; $t++) {
                    $sk = "term_{$t}_start_date";
                    $ek = "term_{$t}_end_date";
                    if (empty($schoolConfig[$sk]) || empty($schoolConfig[$ek])) continue;

                    $termStart = new \DateTime($schoolConfig[$sk]);
                    $termEnd   = new \DateTime($schoolConfig[$ek]);

                    if ($today >= $termStart && $today <= $termEnd) $activeTerm = $t;

                    // Snap back to Monday of the term-start week
                    $dow = (int) $termStart->format('N'); // 1=Mon…7=Sun
                    if ($dow > 1) $termStart->modify('-' . ($dow - 1) . ' days');

                    $weeks  = [];
                    $wNum   = 1;
                    $cursor = clone $termStart;
                    while ($cursor <= $termEnd) {
                        $wEnd = clone $cursor;
                        $wEnd->modify('+4 days');
                        $days = [];
                        for ($d = 1; $d <= 5; $d++) {
                            $dd = clone $cursor;
                            $dd->modify('+' . ($d - 1) . ' days');
                            $days[$d] = $dd->format('Y-m-d');
                        }
                        $weeks[$wNum] = [
                            'week_num'        => $wNum,
                            'start_date'      => $cursor->format('Y-m-d'),
                            'end_date'        => $wEnd->format('Y-m-d'),
                            'days'            => $days,
                            'is_current_week' => ($today->format('Y-m-d') >= $cursor->format('Y-m-d')
                                              && $today->format('Y-m-d') <= $wEnd->format('Y-m-d')),
                        ];
                        $cursor->modify('+7 days');
                        $wNum++;
                    }
                    $termSchedule[$t] = $weeks;
                }
            }

            // Determine current week number within active term for modal pre-fill
            $currentWeekNum = 1;
            $currentDayNum  = min(5, max(1, (int) date('N'))); // 1=Mon…5=Fri (clamp Sat/Sun to Fri)
            $todayStr       = date('Y-m-d');
            foreach ($termSchedule[$activeTerm] ?? [] as $wn => $wk) {
                if ($todayStr >= $wk['start_date'] && $todayStr <= $wk['end_date']) {
                    $currentWeekNum = $wn;
                    break;
                }
            }

            // All years available in school_config for the year selector
            $availableYears = array_column(
                $db->query("SELECT DISTINCT sch_year FROM school_config ORDER BY sch_year DESC")->getResultArray(),
                'sch_year'
            );

            // Build a flat set of every Mon-Fri school date from the generated calendar.
            // Holidays are then matched against this set so only dates that actually
            // appear as day cards are considered — no silent misses.
            $allSchoolDates = [];
            foreach ($termSchedule as $_termNum => $_weeks) {
                foreach ($_weeks as $_week) {
                    foreach ($_week['days'] as $_date) {
                        $allSchoolDates[$_date] = true;
                    }
                }
            }

            // Map each holiday to its display date:
            //   • observed_date in DB  → use that (manual override)
            //   • Saturday             → Friday before
            //   • Sunday              → Monday after
            //   • Weekday             → actual date
            // Only keep the holiday if the display date is an actual school day.
            $holidays = [];
            foreach ($db->query(
                "SELECT holiday_name, holiday_date, observed_date
                 FROM public_holiday WHERE holiday_year = ?
                 ORDER BY holiday_date",
                [$lessonYear]
            )->getResultArray() as $h) {
                $actualDate   = $h['holiday_date'];
                $observedDate = $h['observed_date'];
                $dow          = (int) date('N', strtotime($actualDate));

                if ($observedDate) {
                    $displayDate = $observedDate;
                    $isObserved  = true;
                } elseif ($dow === 6) {
                    $displayDate = date('Y-m-d', strtotime($actualDate . ' -1 day'));
                    $isObserved  = true;
                } elseif ($dow === 7) {
                    $displayDate = date('Y-m-d', strtotime($actualDate . ' +1 day'));
                    $isObserved  = true;
                } else {
                    $displayDate = $actualDate;
                    $isObserved  = false;
                }

                if (isset($allSchoolDates[$displayDate])) {
                    $holidays[$displayDate] = [
                        'name'        => $h['holiday_name'],
                        'is_observed' => $isObserved,
                        'actual_date' => $actualDate,
                    ];
                }
            }

            $data['termSchedule']   = $termSchedule;
            $data['activeTerm']     = $activeTerm;
            $data['lessonYear']     = $lessonYear;
            $data['availableYears'] = $availableYears;
            $data['currentWeekNum'] = $currentWeekNum;
            $data['currentDayNum']  = $currentDayNum;
            $data['holidays']       = $holidays;
        } elseif ($section === 'dashboard') {
            $data['dashStats'] = $this->classroomLessonModel->getDashboardStats($classSubId);
        } elseif ($section === 'assignments') {
            $data['assignments'] = $db->query("
                SELECT a.*, CONCAT(u.fname, ' ', u.lname) AS creator_name
                FROM lesson_assignment a
                LEFT JOIN users u ON u.user_id = a.created_by
                WHERE a.class_sub_id_fk = ?
                ORDER BY a.created_at DESC
            ", [$classSubId])->getResultArray();
            if (!empty($data['assignments'])) {
                $filesGrouped = $this->getAssignmentFilesGrouped(
                    $db,
                    array_column($data['assignments'], 'assignment_id'),
                    array_column($data['assignments'], 'assignment_file', 'assignment_id')
                );
                foreach ($data['assignments'] as &$asgnRow) {
                    $asgnRow['files'] = $filesGrouped[$asgnRow['assignment_id']] ?? [];
                }
                unset($asgnRow);
            }
        } elseif ($section === 'exams') {
            $data['examStudents'] = $db->query("
                SELECT u.user_id, u.fname, u.lname, u.profile_photo
                FROM classroom_student cs INNER JOIN users u ON u.user_id = cs.user_id_fk
                WHERE cs.class_id_fk = ?
                ORDER BY u.lname, u.fname
            ", [$classId])->getResultArray();
            $data['examMarks'] = [];
            $data['reportStatuses'] = [];
            for ($t = 1; $t <= 3; $t++) {
                $rows = $this->termExamModel->getMarksForSubjectTerm($classSubId, $t);
                $data['examMarks'][$t]       = array_column($rows, null, 'student_id_fk');
                $data['reportStatuses'][$t]  = $this->termExamModel->getReportStatus($classId, $t);
            }
            $data['isClassTeacher'] = (bool) $db->table('classroom_role')
                ->where('class_id_fk', $classId)->where('user_id_fk', $userId)
                ->where('cs_role', 'Class Teacher')
                ->get()->getRowArray();
        } elseif ($section === 'discussions') {
            $data['discussions'] = $this->classDiscussionModel->getPosts($classId, $userId);
            $sessionPhoto = $this->session->get('photo');
            $data['sessionFname']    = $this->session->get('fname') ?? 'Teacher';
            $data['sessionPhotoUrl'] = $sessionPhoto ? base_url('uploads/profilePhoto/' . $sessionPhoto) : null;
            $data['sessionUserId']   = $userId;
        } elseif ($section === 'feedback') {
            $avgs = $this->subjectFeedbackModel->getSubjectAverages($classSubId);
            $feedbacks = $this->subjectFeedbackModel->getClassFeedbacks($classSubId, true);

            // Enrolled count
            $enrolled = (int) $db->query(
                "SELECT COUNT(*) AS c FROM classroom_student WHERE class_id_fk=?",
                [$classId])->getRowArray()['c'];

            // Overall rating distribution (1–5)
            $dist = [1=>0, 2=>0, 3=>0, 4=>0, 5=>0];
            foreach ($feedbacks as $f) {
                $r = (int)$f['overall_rating'];
                if ($r >= 1 && $r <= 5) $dist[$r]++;
            }

            $data['feedbackAverages'] = $avgs;
            $data['feedbackList']     = $feedbacks;
            $data['feedbackDist']     = $dist;
            $data['enrolledCount']    = $enrolled;
        }

        // Students enrolled in this classroom (always loaded for left panel)
        $data['students'] = $db->query("
            SELECT u.user_id, u.fname, u.lname, u.profile_photo
            FROM classroom_student cs
            INNER JOIN users u ON u.user_id = cs.user_id_fk
            WHERE cs.class_id_fk = ?
            ORDER BY u.lname ASC, u.fname ASC
        ", [$classId])->getResultArray();

        $data['_view'] = 'app/classroom/teacher/classroom';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STUDENT ASSIGNMENT SUBMISSION
    // ================================================================

    private function resolveStudentClassSub(int $classSubId, int $userId): ?array
    {
        $db = \Config\Database::connect();
        $cs = $db->query("
            SELECT cs.class_sub_id, cs.class_id_fk AS class_id
            FROM classroom_subject cs
            WHERE cs.class_sub_id = ?
            LIMIT 1
        ", [$classSubId])->getRowArray();
        if (!$cs) return null;
        $enrolled = $db->table('classroom_student')
            ->where('class_id_fk', $cs['class_id'])
            ->where('user_id_fk', $userId)
            ->where('class_stud_status', 'Active')
            ->get()->getRowArray();
        return $enrolled ? $cs : null;
    }

    public function studentAssignmentSubmit(int $classSubId, int $assignmentId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $userId = (int) $this->session->get('userID');
        if ((int) $this->session->get('roleCatID') !== 4) return redirect()->to('classroom/my');

        $cs = $this->resolveStudentClassSub($classSubId, $userId);
        if (!$cs) return redirect()->to('classroom/my')->with('error', 'Access denied.');

        $db = \Config\Database::connect();
        $assignment = $db->query("
            SELECT a.*, CONCAT(u.fname,' ',u.lname) AS creator_name
            FROM lesson_assignment a
            LEFT JOIN users u ON u.user_id = a.created_by
            WHERE a.assignment_id = ? AND a.class_sub_id_fk = ? AND a.assignment_status = 'Published'
            LIMIT 1
        ", [$assignmentId, $classSubId])->getRowArray();

        if (!$assignment) return redirect()->to('classroom/student/' . $classSubId . '/assignments')->with('error', 'Assignment not found.');

        $assignment['files'] = $this->getAssignmentFilesGrouped(
            $db, [$assignmentId], [$assignmentId => $assignment['assignment_file']]
        )[$assignmentId] ?? [];

        $submission = $db->table('assignment_submission')
            ->where('assignment_id_fk', $assignmentId)
            ->where('user_id_fk', $userId)
            ->get()->getRowArray();

        $score = $submission ? $db->table('student_assignment_score')
            ->where('assignment_id_fk', $assignmentId)
            ->where('user_id_fk', $userId)
            ->get()->getRowArray() : null;

        $subjectData = $db->query("
            SELECT sub.subject_name
            FROM classroom_subject cs
            INNER JOIN sch_subject ss ON ss.sch_sub_id = cs.sub_id_fk
            INNER JOIN subject sub ON sub.subject_id = ss.subject_id_fk
            WHERE cs.class_sub_id = ?
        ", [$classSubId])->getRowArray();

        $plagiarism = null;
        if ($submission) {
            $plagiarism = $db->table('assignment_plagiarism')
                ->where('submission_id_fk', $submission['submission_id'])
                ->orderBy('plagiarism_id', 'DESC')
                ->limit(1)
                ->get()->getRowArray();
        }

        $this->setPageData('Submit Assignment', 'Classroom', 'Assignment');

        $data['assignment']   = $assignment;
        $data['submission']   = $submission;
        $data['score']        = $score;
        $data['plagiarism']   = $plagiarism;
        $data['classSubId']   = $classSubId;
        $data['subjectData']  = $subjectData;
        $data['_view']        = 'app/classroom/student/assignment_submit';
        return view('app/layouts/main', $data);
    }

    public function studentAssignmentSubmitStore(int $classSubId, int $assignmentId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthenticated']);
        $userId = (int) $this->session->get('userID');
        if ((int) $this->session->get('roleCatID') !== 4) return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);

        $cs = $this->resolveStudentClassSub($classSubId, $userId);
        if (!$cs) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $db = \Config\Database::connect();

        $classStatus = $db->query("
            SELECT c.class_status FROM classroom_subject cs
            INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cs.class_sub_id = ?
        ", [$classSubId])->getRow();
        if (!$classStatus || $classStatus->class_status !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active. Submissions are not allowed.']);
        }

        $assignment = $db->table('lesson_assignment')
            ->where('assignment_id', $assignmentId)
            ->where('class_sub_id_fk', $classSubId)
            ->where('assignment_status', 'Published')
            ->get()->getRowArray();
        if (!$assignment) return $this->response->setJSON(['success' => false, 'message' => 'Assignment not found.']);

        $file = $this->request->getFile('submission_file');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select a file to upload.']);
        }

        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['pdf', 'zip', 'rar'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Only PDF, ZIP, or RAR files are allowed.']);
        }

        $isDue  = !empty($assignment['assignment_due_date']) && strtotime($assignment['assignment_due_date']) < time();
        $status = $isDue ? 'Late' : 'Submitted';

        // Check existing submission
        $existing = $db->table('assignment_submission')
            ->where('assignment_id_fk', $assignmentId)
            ->where('user_id_fk', $userId)
            ->get()->getRowArray();

        // Block resubmission if already graded
        if ($existing && $existing['submission_status'] === 'Graded') {
            return $this->response->setJSON(['success' => false, 'message' => 'Your submission has been graded and cannot be replaced.']);
        }

        // Block resubmission after due date (initial late submission still allowed)
        if ($existing && $isDue) {
            return $this->response->setJSON(['success' => false, 'message' => 'The due date has passed. Resubmission is no longer allowed.']);
        }

        // Delete old file on resubmission
        if ($existing && $existing['submission_file']) {
            $oldPath = FCPATH . 'uploads/assignment_submissions/' . $existing['submission_file'];
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $fileName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/assignment_submissions/', $fileName);

        $row = [
            'assignment_id_fk'    => $assignmentId,
            'user_id_fk'          => $userId,
            'submission_file'     => $fileName,
            'submission_file_type'=> $ext,
            'submission_status'   => $status,
            'submitted_at'        => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $db->table('assignment_submission')->where('submission_id', $existing['submission_id'])->update($row);
            $submissionId = (int) $existing['submission_id'];
        } else {
            $db->table('assignment_submission')->insert($row);
            $submissionId = (int) $db->insertID();
        }

        // ── Copyleaks plagiarism check ────────────────────────────────────────
        $scanId = \App\Libraries\CopyleaksService::generateScanId($submissionId);

        // Replace any prior plagiarism record for this submission (re-submit case)
        $db->table('assignment_plagiarism')->where('submission_id_fk', $submissionId)->delete();
        $db->table('assignment_plagiarism')->insert([
            'submission_id_fk' => $submissionId,
            'scan_id'          => $scanId,
            'status'           => 'pending',
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        try {
            $copyleaks   = new \App\Libraries\CopyleaksService();
            $token       = $copyleaks->getToken();
            $filePath    = FCPATH . 'uploads/assignment_submissions/' . $fileName;
            $webhookBase = base_url('copyleaks/webhook');
            $copyleaks->submitFile($token, $scanId, $filePath, $webhookBase);
            $copyleaks->startScan($token, $scanId);

            // Try polling for immediate result (works for sandbox; falls back to
            // webhook delivery in production). Timeout after 45 s.
            $result = $copyleaks->waitForResult($token, $scanId, 45);
            if ($result) {
                $parsed = \App\Libraries\CopyleaksService::parseResult($result);
                $db->table('assignment_plagiarism')->where('scan_id', $scanId)->update(array_merge(
                    $parsed,
                    ['submitted_at' => date('Y-m-d H:i:s'), 'webhook_raw' => json_encode($result)]
                ));
            } else {
                $db->table('assignment_plagiarism')->where('scan_id', $scanId)->update([
                    'status'       => 'scanning',
                    'submitted_at' => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', '[Copyleaks] scan_id=' . $scanId . ' — ' . $e->getMessage());
            $db->table('assignment_plagiarism')->where('scan_id', $scanId)->update([
                'status'        => 'error',
                'error_message' => substr($e->getMessage(), 0, 900),
            ]);
        }
        // ─────────────────────────────────────────────────────────────────────

        return $this->response->setJSON(['success' => true, 'status' => $status, 'file' => $fileName, 'ext' => $ext]);
    }

    // ================================================================
    // STUDENT FEEDBACK — POST classroom/student/{classSubId}/feedback/store
    // ================================================================

    public function studentFeedbackStore(int $classSubId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthenticated']);
        $userId = (int) $this->session->get('userID');
        if ((int) $this->session->get('roleCatID') !== 4) return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);

        $cs = $this->resolveStudentClassSub($classSubId, $userId);
        if (!$cs) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $overall    = (int) $this->request->getPost('overall_rating');
        $teaching   = (int) $this->request->getPost('teaching_rating');
        $content    = (int) $this->request->getPost('content_rating');
        $engagement = (int) $this->request->getPost('engagement_rating');
        $comment    = trim($this->request->getPost('comment') ?? '');
        $anonymous  = (int) ($this->request->getPost('is_anonymous') ?? 0);

        if ($overall < 1 || $overall > 5) {
            return $this->response->setJSON(['success' => false, 'message' => 'Overall rating is required (1–5 stars).']);
        }
        $teaching   = max(0, min(5, $teaching));
        $content    = max(0, min(5, $content));
        $engagement = max(0, min(5, $engagement));

        $db      = \Config\Database::connect();
        $teacher = $db->query("
            SELECT cst.user_id_fk AS teacher_id, cs.sub_id_fk AS sch_sub_id
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject cs ON cs.class_sub_id = cst.class_sub_id_fk
            WHERE cst.class_sub_id_fk = ? AND cst.class_sub_teacher_status = 'Active'
            LIMIT 1
        ", [$classSubId])->getRowArray();

        $existing = $this->subjectFeedbackModel->getStudentFeedback($classSubId, $userId);

        $row = [
            'class_sub_id_fk'   => $classSubId,
            'class_id_fk'       => $cs['class_id'],
            'student_id_fk'     => $userId,
            'teacher_id_fk'     => $teacher['teacher_id'] ?? null,
            'sch_sub_id_fk'     => $teacher['sch_sub_id'] ?? null,
            'overall_rating'    => $overall,
            'teaching_rating'   => $teaching,
            'content_rating'    => $content,
            'engagement_rating' => $engagement,
            'comment'           => $comment ?: null,
            'is_anonymous'      => $anonymous ? 1 : 0,
        ];

        $isUpdate = $this->subjectFeedbackModel->upsert($row, $existing['feedback_id'] ?? null);
        $msg      = $existing ? 'Feedback updated. Thank you!' : 'Thank you for your feedback!';

        return $this->response->setJSON(['success' => (bool) $isUpdate, 'message' => $msg, 'is_update' => (bool) $existing]);
    }

    // ================================================================
    // STUDENT CLASSROOM LMS — GET classroom/student/{classSubId}/{section}
    // ================================================================

    public function studentClassroom(int $classSubId, string $section = 'dashboard')
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userId    = (int) $this->session->get('userID');
        $roleCatId = (int) $this->session->get('roleCatID');

        if ($roleCatId !== 4) {
            return redirect()->to('classroom/my');
        }

        $db = \Config\Database::connect();

        // Resolve the classroom_subject and verify student is enrolled
        $classroomSubject = $db->query("
            SELECT cs.class_sub_id, cs.class_id_fk AS class_id, cs.sub_id_fk AS sch_sub_id,
                   sub.subject_name, sub.sub_image,
                   l.level_name, d.dept_name
            FROM classroom_subject cs
            INNER JOIN sch_subject ss  ON ss.sch_sub_id  = cs.sub_id_fk
            INNER JOIN subject sub     ON sub.subject_id = ss.subject_id_fk
            LEFT  JOIN level l         ON l.level_id     = sub.level_id_fk
            LEFT  JOIN sch_department sd ON sd.sch_dept_id = ss.sch_dept_id_fk
            LEFT  JOIN department d    ON d.dept_id      = sd.dept_id_fk
            WHERE cs.class_sub_id = ?
        ", [$classSubId])->getRowArray();

        if (!$classroomSubject) {
            return redirect()->to('classroom/my')->with('error', 'Subject not found.');
        }

        $classId = (int) $classroomSubject['class_id'];

        // Verify this student is enrolled in the classroom (any status)
        $enrolled = $db->table('classroom_student')
            ->where('class_id_fk', $classId)
            ->where('user_id_fk',  $userId)
            ->get()->getRowArray();

        if (!$enrolled) {
            return redirect()->to('classroom/my')->with('error', 'You are not enrolled in this classroom.');
        }

        // Classroom card (teacher, captain, student count) — no status filter for past classroom access
        $classroomCard = $db->query("
            SELECT
                c.class_id, c.class_name, c.class_year, c.class_status,
                s.stream_name, l.level_name AS class_level_name, sch.sch_name,
                (SELECT u1.user_id                    FROM classroom_role r1 INNER JOIN users u1 ON u1.user_id=r1.user_id_fk WHERE r1.class_id_fk=c.class_id AND r1.cs_role='Class Teacher'  LIMIT 1) AS class_teacher_id,
                (SELECT CONCAT(u1.fname,' ',u1.lname) FROM classroom_role r1 INNER JOIN users u1 ON u1.user_id=r1.user_id_fk WHERE r1.class_id_fk=c.class_id AND r1.cs_role='Class Teacher'  LIMIT 1) AS class_teacher,
                (SELECT u1.profile_photo              FROM classroom_role r1 INNER JOIN users u1 ON u1.user_id=r1.user_id_fk WHERE r1.class_id_fk=c.class_id AND r1.cs_role='Class Teacher'  LIMIT 1) AS class_teacher_photo,
                (SELECT u2.user_id                    FROM classroom_role r2 INNER JOIN users u2 ON u2.user_id=r2.user_id_fk WHERE r2.class_id_fk=c.class_id AND r2.cs_role='Class Captain' LIMIT 1) AS class_captain_id,
                (SELECT CONCAT(u2.fname,' ',u2.lname) FROM classroom_role r2 INNER JOIN users u2 ON u2.user_id=r2.user_id_fk WHERE r2.class_id_fk=c.class_id AND r2.cs_role='Class Captain' LIMIT 1) AS class_captain,
                (SELECT u2.profile_photo              FROM classroom_role r2 INNER JOIN users u2 ON u2.user_id=r2.user_id_fk WHERE r2.class_id_fk=c.class_id AND r2.cs_role='Class Captain' LIMIT 1) AS class_captain_photo,
                (SELECT COUNT(*) FROM classroom_student cs2 WHERE cs2.class_id_fk=c.class_id) AS student_count
            FROM classroom c
            INNER JOIN stream s     ON s.stream_id     = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
            INNER JOIN level l      ON l.level_id      = sl.level_id_fk
            INNER JOIN school sch   ON sch.sch_id      = sl.sch_id_fk
            WHERE c.class_id = ?
        ", [$classId])->getRowArray();

        $section = strtolower($section);
        $this->setPageData($classroomSubject['subject_name'] ?? 'Subject', 'Classroom', ucfirst($section));

        $data['subjectData']   = $classroomSubject;
        $data['classroomCard'] = $classroomCard;
        $data['section']       = $section;
        $data['classSubId']    = $classSubId;
        $data['classId']       = $classId;

        if ($section === 'lessons' || $section === 'dashboard') {
            $data['lessons']   = $this->classroomLessonModel->getLessonsForSubject($classSubId);
            $data['dashStats'] = $this->classroomLessonModel->getDashboardStats($classSubId);
            if ($section === 'dashboard') {
                $data['assessmentStats'] = $this->classroomLessonModel->getStudentAssessmentStats($classSubId, $userId);
            }

            if ($section === 'lessons') {
                $lessonYear = (int) ($this->request->getGet('year') ?: date('Y'));

                $schoolConfig = $db->query(
                    "SELECT * FROM school_config WHERE sch_year = ? LIMIT 1", [$lessonYear]
                )->getRowArray();
                if (!$schoolConfig) {
                    $schoolConfig = $db->query(
                        "SELECT * FROM school_config ORDER BY sch_config_id DESC LIMIT 1"
                    )->getRowArray();
                }

                $termSchedule = [];
                $activeTerm   = 1;
                if ($schoolConfig) {
                    $today = new \DateTime('today');
                    for ($t = 1; $t <= 3; $t++) {
                        $sk = "term_{$t}_start_date";
                        $ek = "term_{$t}_end_date";
                        if (empty($schoolConfig[$sk]) || empty($schoolConfig[$ek])) continue;
                        $termStart = new \DateTime($schoolConfig[$sk]);
                        $termEnd   = new \DateTime($schoolConfig[$ek]);
                        if ($today >= $termStart && $today <= $termEnd) $activeTerm = $t;
                        $dow = (int) $termStart->format('N');
                        if ($dow > 1) $termStart->modify('-' . ($dow - 1) . ' days');
                        $weeks = []; $wNum = 1; $cursor = clone $termStart;
                        while ($cursor <= $termEnd) {
                            $wEnd = clone $cursor; $wEnd->modify('+4 days');
                            $days = [];
                            for ($d = 1; $d <= 5; $d++) {
                                $dd = clone $cursor; $dd->modify('+' . ($d - 1) . ' days');
                                $days[$d] = $dd->format('Y-m-d');
                            }
                            $weeks[$wNum] = [
                                'week_num'        => $wNum,
                                'start_date'      => $cursor->format('Y-m-d'),
                                'end_date'        => $wEnd->format('Y-m-d'),
                                'days'            => $days,
                                'is_current_week' => ($today->format('Y-m-d') >= $cursor->format('Y-m-d')
                                                  && $today->format('Y-m-d') <= $wEnd->format('Y-m-d')),
                            ];
                            $cursor->modify('+7 days'); $wNum++;
                        }
                        $termSchedule[$t] = $weeks;
                    }
                }

                $todayStr       = date('Y-m-d');
                $currentWeekNum = 1;
                foreach ($termSchedule[$activeTerm] ?? [] as $wn => $wk) {
                    if ($todayStr >= $wk['start_date'] && $todayStr <= $wk['end_date']) {
                        $currentWeekNum = $wn; break;
                    }
                }

                $availableYears = array_column(
                    $db->query("SELECT DISTINCT sch_year FROM school_config ORDER BY sch_year DESC")->getResultArray(),
                    'sch_year'
                );

                $allSchoolDates = [];
                foreach ($termSchedule as $_tw) {
                    foreach ($_tw as $_wk) {
                        foreach ($_wk['days'] as $_dt) { $allSchoolDates[$_dt] = true; }
                    }
                }

                $holidays = [];
                foreach ($db->query(
                    "SELECT holiday_name, holiday_date, observed_date FROM public_holiday WHERE holiday_year = ? ORDER BY holiday_date",
                    [$lessonYear]
                )->getResultArray() as $h) {
                    $actualDate = $h['holiday_date']; $observedDate = $h['observed_date'];
                    $dow = (int) date('N', strtotime($actualDate));
                    if ($observedDate)        { $displayDate = $observedDate; $isObs = true; }
                    elseif ($dow === 6)       { $displayDate = date('Y-m-d', strtotime($actualDate . ' -1 day')); $isObs = true; }
                    elseif ($dow === 7)       { $displayDate = date('Y-m-d', strtotime($actualDate . ' +1 day')); $isObs = true; }
                    else                     { $displayDate = $actualDate; $isObs = false; }
                    if (isset($allSchoolDates[$displayDate])) {
                        $holidays[$displayDate] = ['name' => $h['holiday_name'], 'is_observed' => $isObs, 'actual_date' => $actualDate];
                    }
                }

                $data['termSchedule']   = $termSchedule;
                $data['activeTerm']     = $activeTerm;
                $data['lessonYear']     = $lessonYear;
                $data['availableYears'] = $availableYears;
                $data['currentWeekNum'] = $currentWeekNum;
                $data['holidays']       = $holidays;
            }
        } elseif ($section === 'assignments') {
            $data['assignments'] = $db->query("
                SELECT a.*, CONCAT(u.fname, ' ', u.lname) AS creator_name
                FROM lesson_assignment a
                LEFT JOIN users u ON u.user_id = a.created_by
                WHERE a.class_sub_id_fk = ? AND a.assignment_status = 'Published'
                ORDER BY a.assignment_due_date ASC
            ", [$classSubId])->getResultArray();
        } elseif ($section === 'exam') {
            $data['studentExamReports'] = [];
            $data['reportStatuses']     = [];
            for ($t = 1; $t <= 3; $t++) {
                $data['reportStatuses'][$t]     = $this->termExamModel->getReportStatus($classId, $t);
                $data['studentExamReports'][$t] = $this->termExamModel->getStudentReport($classId, $userId, $t);
            }
        } elseif ($section === 'discussions') {
            $data['discussions'] = $this->classDiscussionModel->getPosts($classId, $userId);
            $sessionPhoto = $this->session->get('photo');
            $data['sessionFname']    = $this->session->get('fname') ?? 'Student';
            $data['sessionPhotoUrl'] = $sessionPhoto ? base_url('uploads/profilePhoto/' . $sessionPhoto) : null;
            $data['sessionUserId']   = $userId;
        } elseif ($section === 'feedback') {
            $data['existingFeedback'] = $this->subjectFeedbackModel->getStudentFeedback($classSubId, $userId);
            $data['subjectTeacher']   = $db->query("
                SELECT u.user_id, CONCAT(u.fname,' ',u.lname) AS teacher_name, u.profile_photo
                FROM classroom_subject_teacher cst
                INNER JOIN users u ON u.user_id = cst.user_id_fk
                WHERE cst.class_sub_id_fk = ?
                LIMIT 1
            ", [$classSubId])->getRowArray();
        }

        // Classmates: all students in the same classroom, excluding current user
        $data['classmates'] = $db->query("
            SELECT u.user_id, u.fname, u.lname, u.profile_photo
            FROM classroom_student cs
            INNER JOIN users u ON u.user_id = cs.user_id_fk
            WHERE cs.class_id_fk = ? AND u.user_id != ?
            ORDER BY u.lname ASC, u.fname ASC
        ", [$classId, $userId])->getResultArray();

        $data['_view'] = 'app/classroom/student/classroom';
        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STUDENT LESSON DETAIL — GET classroom/student/{classSubId}/lesson/{lessonId}
    // ================================================================

    public function studentLessonDetail(int $classSubId, int $lessonId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $roleCatId = (int) $this->session->get('roleCatID');
        if ($roleCatId !== 4) return redirect()->to('classroom/my');

        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        // Resolve classroom_subject and verify student is enrolled
        $classroomSubject = $db->query("
            SELECT cs.class_sub_id, cs.class_id_fk AS class_id
            FROM classroom_subject cs WHERE cs.class_sub_id = ?
        ", [$classSubId])->getRowArray();

        if (!$classroomSubject) {
            return redirect()->to('classroom/my')->with('error', 'Subject not found.');
        }

        $classId  = (int) $classroomSubject['class_id'];
        $enrolled = $db->table('classroom_student')
            ->where('class_id_fk', $classId)->where('user_id_fk', $userId)
            ->get()->getRowArray();

        if (!$enrolled) {
            return redirect()->to('classroom/my')->with('error', 'You are not enrolled in this classroom.');
        }

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        if (!$lesson || $lesson['lesson_status'] !== 'Published') {
            return redirect()->to("classroom/student/{$classSubId}/lessons")->with('error', 'Lesson not found or not available.');
        }

        $this->setPageData($lesson['lesson_title'], 'Classroom', 'Lesson');

        $sessionPhoto               = $this->session->get('photo');
        $data['lesson']             = $lesson;
        $data['classSubId']         = $classSubId;
        $data['sessionFname']       = $this->session->get('fname') ?? 'you';
        $data['sessionPhotoUrl']    = $sessionPhoto ? base_url('uploads/profilePhoto/' . $sessionPhoto) : null;
        $data['sessionUserId']      = $userId;
        $data['discussions']        = $this->lessonDiscussionModel->getDiscussions($lessonId, $userId);
        $data['quizzes']            = array_values(array_filter(
            $this->lessonQuizzeModel->getQuizzesWithQuestionsForLesson($lessonId),
            fn($q) => $q['quizze_status'] === 'Published'
        ));
        $data['quizAttempts']       = $this->lessonQuizzeAttemptModel->getStudentAttemptsForLesson($lessonId, $userId);
        $data['ddAttempts']         = $this->lessonDragDropModel->getStudentAttemptsForLesson($lessonId, $userId);
        $data['labelAttempts']      = $this->lessonLabelModel->getStudentAttemptsForLesson($lessonId, $userId);
        $data['_view']              = 'app/classroom/student/lesson_detail';

        return view('app/layouts/main', $data);
    }



    // ================================================================
    // LABELLING — Teacher builder
    // GET classroom/teacher/{schSubId}/lesson/{lessonId}/label/{quizzeId}
    // ================================================================
    public function teacherLabelDetail(int $schSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $db   = \Config\Database::connect();
        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizzeId)
            ->where('lesson_id_fk', $lessonId)
            ->where('assessment_type', 'labelling')
            ->get()->getRowArray();

        if (!$quiz) {
            return redirect()->to("classroom/teacher/{$schSubId}/lesson/{$lessonId}")
                ->with('error', 'Assessment not found.');
        }

        $lesson    = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        $questions = $this->lessonLabelModel->getAssessmentData($quizzeId);

        $this->setPageData($quiz['quizze_name'], 'Classroom', 'Labelling Builder');

        $data['quiz']       = $quiz;
        $data['lesson']     = $lesson;
        $data['schSubId']   = $schSubId;
        $data['questions']  = $questions;
        $data['_view']      = 'app/classroom/teacher/label_detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // LABEL ANALYSIS — GET classroom/teacher/{schSubId}/lesson/{lessonId}/label/{quizzeId}/analysis
    // ================================================================
    public function teacherLabelAnalysis(int $schSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $db   = \Config\Database::connect();
        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizzeId)
            ->where('lesson_id_fk', $lessonId)
            ->where('assessment_type', 'labelling')
            ->get()->getRowArray();

        if (!$quiz) {
            return redirect()->to("classroom/teacher/{$schSubId}/lesson/{$lessonId}")
                ->with('error', 'Assessment not found.');
        }

        $attempts = $db->table('lesson_label_attempt')
            ->where('quizze_id_fk', $quizzeId)
            ->where('status', 'submitted')
            ->get()->getResultArray();

        $totalAttempts = count($attempts);
        $scores        = array_map(fn($a) => (float) $a['score'], $attempts);
        $avgScore      = $totalAttempts > 0 ? round(array_sum($scores) / $totalAttempts, 2) : 0;
        $highScore     = $totalAttempts > 0 ? (float) max($scores) : 0;
        $lowScore      = $totalAttempts > 0 ? (float) min($scores) : 0;

        $distribution = [0, 0, 0, 0, 0];
        foreach ($scores as $s) {
            if      ($s < 20) $distribution[0]++;
            elseif  ($s < 40) $distribution[1]++;
            elseif  ($s < 60) $distribution[2]++;
            elseif  ($s < 80) $distribution[3]++;
            else               $distribution[4]++;
        }

        $questions    = $this->lessonLabelModel->getAssessmentData($quizzeId);
        $markerStats  = [];
        $totalMarkers = 0;

        foreach ($questions as $qi => $q) {
            foreach ($q['markers'] as $mi => $marker) {
                $markerId = (int) $marker['marker_id'];
                $totalMarkers++;
                $row = $db->query("
                    SELECT COUNT(*) AS total, SUM(aa.is_correct) AS correct
                    FROM lesson_label_attempt_answer aa
                    INNER JOIN lesson_label_attempt a ON a.attempt_id = aa.attempt_id_fk
                    WHERE aa.marker_id_fk = ? AND a.status = 'submitted'
                ", [$markerId])->getRowArray();

                $total   = (int) ($row['total']   ?? 0);
                $correct = (int) ($row['correct'] ?? 0);

                $markerStats[] = [
                    'question_text' => $q['question_text'],
                    'question_num'  => $qi + 1,
                    'marker_num'    => $mi + 1,
                    'correct_label' => $marker['correct_label'],
                    'total'         => $total,
                    'correct'       => $correct,
                    'incorrect'     => $total - $correct,
                    'pct'           => $total > 0 ? round(($correct / $total) * 100, 1) : 0,
                ];
            }
        }

        $classRow = $db->query("
            SELECT cs.class_id_fk
            FROM classroom_subject cs
            INNER JOIN classroom_lesson cl ON cl.class_sub_id_fk = cs.class_sub_id
            WHERE cl.lesson_id = ? LIMIT 1
        ", [$lessonId])->getRowArray();

        $enrolledCount = $classRow
            ? (int) $db->table('classroom_student')
                ->where('class_id_fk', $classRow['class_id_fk'])
                ->where('class_stud_status', 'Active')
                ->countAllResults()
            : 0;

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        $this->setPageData('Labelling Analysis', 'Classroom', 'Analysis');

        $data['quiz']          = $quiz;
        $data['lesson']        = $lesson;
        $data['schSubId']      = $schSubId;
        $data['lessonId']      = $lessonId;
        $data['quizzeId']      = $quizzeId;
        $data['totalAttempts'] = $totalAttempts;
        $data['enrolledCount'] = $enrolledCount;
        $data['avgScore']      = $avgScore;
        $data['highScore']     = $highScore;
        $data['lowScore']      = $lowScore;
        $data['distribution']  = $distribution;
        $data['markerStats']   = $markerStats;
        $data['totalMarkers']  = $totalMarkers;
        $data['backUrl']       = base_url("classroom/teacher/{$schSubId}/lesson/{$lessonId}");
        $data['_view']         = 'app/classroom/teacher/label_analysis';

        return view('app/layouts/main', $data);
    }

    // GET classroom/teacher/{schSubId}/lesson/{lessonId}/label/{quizzeId}/attempts  (AJAX)
    public function teacherLabelAttempts(int $schSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db       = \Config\Database::connect();
        $attempts = $db->query("
            SELECT a.attempt_id, a.status, a.score, a.correct_markers, a.total_markers,
                   a.submitted_at, CONCAT(u.fname, ' ', u.lname) AS student_name
            FROM lesson_label_attempt a
            INNER JOIN users u ON u.user_id = a.user_id_fk
            WHERE a.quizze_id_fk = ? AND a.status = 'submitted'
            ORDER BY a.score DESC
        ", [$quizzeId])->getResultArray();

        foreach ($attempts as &$a) {
            $a['submitted_at'] = $a['submitted_at'] ? date('M j, Y g:i A', strtotime($a['submitted_at'])) : null;
        }

        return $this->response->setJSON(['success' => true, 'attempts' => $attempts]);
    }

    // POST classroom/lesson/{lessonId}/label/{quizzeId}/question/store
    public function storeLabelQuestion(int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Unauthorized']);
        }

        $text  = trim($this->request->getPost('question_text') ?? '');
        $db    = \Config\Database::connect();
        $count = (int) $db->table('lesson_label_question')->where('quizze_id_fk', $quizzeId)->countAllResults();

        $imageName = null;
        $file      = $this->request->getFile('bg_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext     = strtolower($file->getClientExtension());
            if (in_array($ext, $allowed)) {
                $dir = FCPATH . 'uploads/label_images/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $imageName = 'lbl_' . $quizzeId . '_q_' . time() . '_' . random_int(100,999) . '.' . $ext;
                $file->move($dir, $imageName);
            }
        }

        $db->table('lesson_label_question')->insert([
            'quizze_id_fk'   => $quizzeId,
            'question_text'  => $text,
            'bg_image'       => $imageName,
            'question_order' => $count + 1,
        ]);

        return $this->response->setJSON([
            'success'  => true,
            'question' => [
                'label_question_id' => $db->insertID(),
                'question_text'     => $text,
                'bg_image'          => $imageName,
                'question_order'    => $count + 1,
                'markers'           => [],
            ],
        ]);
    }

    // POST classroom/lesson/{lessonId}/label/{quizzeId}/question/{questionId}/update
    public function updateLabelQuestion(int $lessonId, int $quizzeId, int $questionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Unauthorized']);
        }

        $db  = \Config\Database::connect();
        $row = $db->table('lesson_label_question')
            ->where('label_question_id', $questionId)
            ->where('quizze_id_fk', $quizzeId)
            ->get()->getRowArray();
        if (!$row) return $this->response->setJSON(['success'=>false,'message'=>'Not found.']);

        $text   = trim($this->request->getPost('question_text') ?? $row['question_text']);
        $update = ['question_text' => $text];

        $file = $this->request->getFile('bg_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext     = strtolower($file->getClientExtension());
            if (in_array($ext, $allowed)) {
                if (!empty($row['bg_image'])) {
                    $old = FCPATH . 'uploads/label_images/' . $row['bg_image'];
                    if (file_exists($old)) unlink($old);
                }
                $dir = FCPATH . 'uploads/label_images/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $imageName       = 'lbl_' . $quizzeId . '_q_' . time() . '_' . random_int(100,999) . '.' . $ext;
                $file->move($dir, $imageName);
                $update['bg_image'] = $imageName;
            }
        }

        $db->table('lesson_label_question')->where('label_question_id', $questionId)->update($update);

        return $this->response->setJSON([
            'success'  => true,
            'question' => array_merge($row, $update),
        ]);
    }

    // POST classroom/lesson/{lessonId}/label/{quizzeId}/question/{questionId}/delete
    public function deleteLabelQuestion(int $lessonId, int $quizzeId, int $questionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Unauthorized']);
        }

        $db  = \Config\Database::connect();
        $row = $db->table('lesson_label_question')
            ->where('label_question_id', $questionId)
            ->where('quizze_id_fk', $quizzeId)
            ->get()->getRowArray();
        if (!$row) return $this->response->setJSON(['success'=>false,'message'=>'Not found.']);

        if (!empty($row['bg_image'])) {
            $path = FCPATH . 'uploads/label_images/' . $row['bg_image'];
            if (file_exists($path)) unlink($path);
        }
        $db->table('lesson_label_marker')->where('label_question_id_fk', $questionId)->delete();
        $db->table('lesson_label_question')->where('label_question_id', $questionId)->delete();

        return $this->response->setJSON(['success'=>true]);
    }

    // POST classroom/lesson/{lessonId}/label/{quizzeId}/question/{questionId}/marker/store
    public function storeLabelMarker(int $lessonId, int $quizzeId, int $questionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Unauthorized']);
        }

        $label = trim($this->request->getPost('correct_label') ?? '');
        $x     = (float) ($this->request->getPost('marker_x') ?? 0);
        $y     = (float) ($this->request->getPost('marker_y') ?? 0);

        if (!$label) return $this->response->setJSON(['success'=>false,'message'=>'Label is required.']);

        $db    = \Config\Database::connect();
        $count = (int) $db->table('lesson_label_marker')
            ->where('label_question_id_fk', $questionId)->countAllResults();

        $db->table('lesson_label_marker')->insert([
            'label_question_id_fk' => $questionId,
            'marker_x'             => round(min(max($x, 0), 100), 2),
            'marker_y'             => round(min(max($y, 0), 100), 2),
            'correct_label'        => $label,
            'marker_order'         => $count + 1,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'marker'  => [
                'marker_id'    => $db->insertID(),
                'marker_x'     => round(min(max($x, 0), 100), 2),
                'marker_y'     => round(min(max($y, 0), 100), 2),
                'correct_label'=> $label,
                'marker_order' => $count + 1,
            ],
        ]);
    }

    // POST classroom/lesson/{lessonId}/label/{quizzeId}/question/{questionId}/marker/{markerId}/update
    public function updateLabelMarker(int $lessonId, int $quizzeId, int $questionId, int $markerId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Unauthorized']);
        }

        $label = trim($this->request->getPost('correct_label') ?? '');
        if (!$label) return $this->response->setJSON(['success'=>false,'message'=>'Label is required.']);

        $db  = \Config\Database::connect();
        $row = $db->table('lesson_label_marker')
            ->where('marker_id', $markerId)
            ->where('label_question_id_fk', $questionId)
            ->get()->getRowArray();
        if (!$row) return $this->response->setJSON(['success'=>false,'message'=>'Marker not found.']);

        $update = ['correct_label' => $label];
        if ($this->request->getPost('marker_x') !== null) {
            $update['marker_x'] = round(min(max((float) $this->request->getPost('marker_x'), 0), 100), 2);
            $update['marker_y'] = round(min(max((float) $this->request->getPost('marker_y'), 0), 100), 2);
        }

        $db->table('lesson_label_marker')->where('marker_id', $markerId)->update($update);

        return $this->response->setJSON([
            'success' => true,
            'marker'  => array_merge($row, $update),
        ]);
    }

    // POST classroom/lesson/{lessonId}/label/{quizzeId}/question/{questionId}/marker/{markerId}/delete
    public function deleteLabelMarker(int $lessonId, int $quizzeId, int $questionId, int $markerId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $db->table('lesson_label_marker')
            ->where('marker_id', $markerId)
            ->where('label_question_id_fk', $questionId)
            ->delete();

        return $this->response->setJSON(['success'=>true]);
    }

    // ================================================================
    // LABELLING — Student take / submit / score / transcript
    // ================================================================

    public function takeLabel(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        if ((int) $this->session->get('roleCatID') !== 4) return redirect()->to('classroom/my');

        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        $cs = $db->query("
            SELECT cs.class_id_fk AS class_id, c.class_status
            FROM classroom_subject cs INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cs.class_sub_id = ?
        ", [$classSubId])->getRowArray();
        if (!$cs) return redirect()->to('classroom/my')->with('error', 'Subject not found.');

        if ($cs['class_status'] !== 'Active') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'This classroom is no longer active. Assessments cannot be taken.');
        }

        $enrolled = $db->table('classroom_student')
            ->where('class_id_fk', (int)$cs['class_id'])->where('user_id_fk', $userId)
            ->where('class_stud_status','Active')->get()->getRowArray();
        if (!$enrolled) return redirect()->to('classroom/my')->with('error','Not enrolled.');

        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizzeId)->where('lesson_id_fk', $lessonId)
            ->where('assessment_type','labelling')->where('quizze_status','Published')
            ->get()->getRowArray();
        if (!$quiz) {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error','Assessment not available.');
        }

        // One-time column migration for attempt resumption
        if (!$db->fieldExists('time_remaining', 'lesson_label_attempt')) {
            \Config\Database::forge()->addColumn('lesson_label_attempt', [
                'time_remaining' => ['type' => 'INT', 'null' => true, 'default' => null],
            ]);
        }

        $attempt        = $this->lessonLabelModel->getStudentAttempt($quizzeId, $userId);
        $remainingSeconds = 0;
        $savedAnswers   = [];

        if ($attempt) {
            if ($attempt['status'] === 'submitted') {
                return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}/label/{$quizzeId}/score");
            }
            $durationSecs = (int) $quiz['quizze_duration'] * 60;
            if ($durationSecs > 0) {
                $remainingSeconds = $attempt['time_remaining'] !== null
                    ? (int) $attempt['time_remaining']
                    : max(0, $durationSecs - (time() - strtotime($attempt['started_at'])));
                if ($remainingSeconds <= 0) {
                    $savedAnswers = $this->lessonLabelModel->getSavedAnswers((int) $attempt['attempt_id']);
                    $this->lessonLabelModel->submitAttempt((int) $attempt['attempt_id'], $savedAnswers, 'submitted');
                    return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}/label/{$quizzeId}/score");
                }
            }
            $savedAnswers = $this->lessonLabelModel->getSavedAnswers((int) $attempt['attempt_id']);
        } else {
            $attemptId        = $this->lessonLabelModel->startAttempt($quizzeId, $lessonId, $userId);
            $attempt          = $db->table('lesson_label_attempt')->where('attempt_id', $attemptId)->get()->getRowArray();
            $remainingSeconds = (int) $quiz['quizze_duration'] * 60;
        }

        $lesson    = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        $questions = $this->lessonLabelModel->getAssessmentData($quizzeId);

        $this->setPageData($quiz['quizze_name'], 'Classroom', 'Assessment');

        $data['quiz']             = $quiz;
        $data['attempt']          = $attempt;
        $data['lesson']           = $lesson;
        $data['questions']        = $questions;
        $data['classSubId']       = $classSubId;
        $data['remainingSeconds'] = $remainingSeconds;
        $data['savedAnswers']     = $savedAnswers;
        $data['tickUrl']          = base_url("classroom/label/attempt/{$attempt['attempt_id']}/tick");
        $data['saveAnswerUrl']    = base_url("classroom/label/attempt/{$attempt['attempt_id']}/save-answer");
        $data['submitUrl']        = base_url("classroom/lesson/{$lessonId}/label/{$quizzeId}/attempt/{$attempt['attempt_id']}/submit");
        $data['scoreUrl']         = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}/label/{$quizzeId}/score");
        $data['backUrl']          = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}");
        $data['_view']            = 'app/classroom/student/label_take';

        return view('app/layouts/main', $data);
    }

    public function submitLabelAttempt(int $lessonId, int $quizzeId, int $attemptId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Unauthorized']);
        }

        $userId  = (int) $this->session->get('userID');
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_label_attempt')->where('attempt_id', $attemptId)->get()->getRowArray();

        if (!$attempt || (int)$attempt['user_id_fk'] !== $userId) {
            return $this->response->setJSON(['success'=>false,'message'=>'Invalid attempt.']);
        }
        if ($attempt['status'] === 'submitted') {
            return $this->response->setJSON(['success'=>false,'message'=>'Already submitted.']);
        }

        $status  = $this->request->getPost('status') ?? 'submitted';
        $answers = $this->request->getPost('answers') ?? [];

        $result = $this->lessonLabelModel->submitAttempt($attemptId, (array)$answers, $status);
        return $this->response->setJSON($result);
    }

    public function studentLabelScore(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        if ((int)$this->session->get('roleCatID') !== 4) return redirect()->to('classroom/my');

        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonLabelModel->getStudentAttempt($quizzeId, $userId);

        if (!$attempt || $attempt['status'] !== 'submitted') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error','No completed attempt found.');
        }

        $db            = \Config\Database::connect();
        $attemptDetail = $this->lessonLabelModel->getAttemptWithAnswers((int)$attempt['attempt_id']);
        $quiz          = $db->table('lesson_quizze')->where('lesson_quizze_id', $quizzeId)->get()->getRowArray();
        $lesson        = $this->classroomLessonModel->getLessonWithSteps($lessonId);

        $this->setPageData('Assessment Results','Classroom','Results');

        $data['attempt']       = $attemptDetail;
        $data['quiz']          = $quiz;
        $data['lesson']        = $lesson;
        $data['classSubId']    = $classSubId;
        $data['backUrl']       = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}");
        $data['transcriptUrl'] = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}/label/{$quizzeId}/transcript");
        $data['_view']         = 'app/classroom/student/label_score';

        return view('app/layouts/main', $data);
    }

    public function downloadLabelTranscript(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        if ((int)$this->session->get('roleCatID') !== 4) return redirect()->to('classroom/my');

        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonLabelModel->getStudentAttempt($quizzeId, $userId);

        if (!$attempt || $attempt['status'] !== 'submitted') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")->with('error','No completed attempt found.');
        }

        $db            = \Config\Database::connect();
        $attemptDetail = $this->lessonLabelModel->getAttemptWithAnswers((int)$attempt['attempt_id']);
        $quiz          = $db->table('lesson_quizze')->where('lesson_quizze_id',$quizzeId)->get()->getRowArray();
        $lesson        = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        $studentName   = trim($this->session->get('fname') . ' ' . $this->session->get('lname'));

        $data['attempt']     = $attemptDetail;
        $data['quiz']        = $quiz;
        $data['lesson']      = $lesson;
        $data['studentName'] = $studentName;

        return view('app/classroom/student/label_transcript', $data);
    }

    // ================================================================
    // TAKE DRAG-DROP — GET classroom/student/{classSubId}/lesson/{lessonId}/dragdrop/{quizzeId}/take
    // ================================================================
    public function takeDragDrop(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $roleCatId = (int) $this->session->get('roleCatID');
        if ($roleCatId !== 4) return redirect()->to('classroom/my');

        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        $classroomSubject = $db->query("
            SELECT cs.class_sub_id, cs.class_id_fk AS class_id, c.class_status
            FROM classroom_subject cs
            INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cs.class_sub_id = ?
        ", [$classSubId])->getRowArray();
        if (!$classroomSubject) return redirect()->to('classroom/my')->with('error', 'Subject not found.');

        if ($classroomSubject['class_status'] !== 'Active') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'This classroom is no longer active. Assessments cannot be taken.');
        }

        $enrolled = $db->table('classroom_student')
            ->where('class_id_fk', (int) $classroomSubject['class_id'])
            ->where('user_id_fk', $userId)
            ->where('class_stud_status', 'Active')->get()->getRowArray();
        if (!$enrolled) return redirect()->to('classroom/my')->with('error', 'Not enrolled in this classroom.');

        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizzeId)
            ->where('lesson_id_fk', $lessonId)
            ->where('assessment_type', 'drag_drop')
            ->where('quizze_status', 'Published')
            ->get()->getRowArray();
        if (!$quiz) {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'Assessment not available.');
        }

        // One-time column migration for attempt resumption
        if (!$db->fieldExists('time_remaining', 'lesson_dragdrop_attempt')) {
            \Config\Database::forge()->addColumn('lesson_dragdrop_attempt', [
                'time_remaining' => ['type' => 'INT', 'null' => true, 'default' => null],
            ]);
        }

        $attempt          = $this->lessonDragDropModel->getStudentAttempt($quizzeId, $userId);
        $remainingSeconds = 0;
        $savedPlacements  = [];

        if ($attempt) {
            if ($attempt['status'] === 'submitted') {
                return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}/dragdrop/{$quizzeId}/score");
            }
            $durationSecs = (int) $quiz['quizze_duration'] * 60;
            if ($durationSecs > 0) {
                $remainingSeconds = $attempt['time_remaining'] !== null
                    ? (int) $attempt['time_remaining']
                    : max(0, $durationSecs - (time() - strtotime($attempt['started_at'])));
                if ($remainingSeconds <= 0) {
                    $savedPlacements = $this->lessonDragDropModel->getSavedPlacements((int) $attempt['attempt_id']);
                    $this->lessonDragDropModel->submitAttempt((int) $attempt['attempt_id'], $savedPlacements, 'submitted');
                    return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}/dragdrop/{$quizzeId}/score");
                }
            }
            $savedPlacements = $this->lessonDragDropModel->getSavedPlacements((int) $attempt['attempt_id']);
        } else {
            $attemptId        = $this->lessonDragDropModel->startAttempt($quizzeId, $lessonId, $userId);
            $attempt          = $db->table('lesson_dragdrop_attempt')->where('attempt_id', $attemptId)->get()->getRowArray();
            $remainingSeconds = (int) $quiz['quizze_duration'] * 60;
        }

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        $dd     = $this->lessonDragDropModel->getAssessmentData($quizzeId);
        $items  = $dd['items'];
        shuffle($items);

        $this->setPageData($quiz['quizze_name'], 'Classroom', 'Assessment');

        $data['quiz']             = $quiz;
        $data['attempt']          = $attempt;
        $data['lesson']           = $lesson;
        $data['items']            = $items;
        $data['zones']            = $dd['zones'];
        $data['classSubId']       = $classSubId;
        $data['remainingSeconds'] = $remainingSeconds;
        $data['savedPlacements']  = $savedPlacements;
        $data['tickUrl']          = base_url("classroom/dragdrop/attempt/{$attempt['attempt_id']}/tick");
        $data['saveAnswerUrl']    = base_url("classroom/dragdrop/attempt/{$attempt['attempt_id']}/save-answer");
        $data['submitUrl']        = base_url("classroom/lesson/{$lessonId}/dragdrop/{$quizzeId}/attempt/{$attempt['attempt_id']}/submit");
        $data['scoreUrl']         = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}/dragdrop/{$quizzeId}/score");
        $data['backUrl']          = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}");
        $data['_view']            = 'app/classroom/student/dragdrop_take';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // SUBMIT DRAG-DROP — POST classroom/lesson/{lessonId}/dragdrop/{quizzeId}/attempt/{attemptId}/submit
    // ================================================================
    public function submitDragDropAttempt(int $lessonId, int $quizzeId, int $attemptId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId  = (int) $this->session->get('userID');
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_dragdrop_attempt')
            ->where('attempt_id', $attemptId)->get()->getRowArray();

        if (!$attempt || (int) $attempt['user_id_fk'] !== $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid attempt.']);
        }
        if ($attempt['status'] === 'submitted') {
            return $this->response->setJSON(['success' => false, 'message' => 'Already submitted.']);
        }

        $status     = $this->request->getPost('status') ?? 'submitted';
        $placements = $this->request->getPost('placements') ?? [];

        $result = $this->lessonDragDropModel->submitAttempt($attemptId, (array) $placements, $status);
        return $this->response->setJSON($result);
    }

    // ================================================================
    // DRAG-DROP SCORE — GET classroom/student/{classSubId}/lesson/{lessonId}/dragdrop/{quizzeId}/score
    // ================================================================
    public function studentDragDropScore(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $roleCatId = (int) $this->session->get('roleCatID');
        if ($roleCatId !== 4) return redirect()->to('classroom/my');

        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonDragDropModel->getStudentAttempt($quizzeId, $userId);

        if (!$attempt || $attempt['status'] !== 'submitted') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'No completed attempt found.');
        }

        $db            = \Config\Database::connect();
        $attemptDetail = $this->lessonDragDropModel->getAttemptWithItems((int) $attempt['attempt_id']);
        $quiz          = $db->table('lesson_quizze')->where('lesson_quizze_id', $quizzeId)->get()->getRowArray();
        $lesson        = $this->classroomLessonModel->getLessonWithSteps($lessonId);

        $this->setPageData('Assessment Results', 'Classroom', 'Results');

        $data['attempt']       = $attemptDetail;
        $data['quiz']          = $quiz;
        $data['lesson']        = $lesson;
        $data['classSubId']    = $classSubId;
        $data['backUrl']       = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}");
        $data['transcriptUrl'] = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}/dragdrop/{$quizzeId}/transcript");
        $data['_view']         = 'app/classroom/student/dragdrop_score';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // DRAG-DROP TRANSCRIPT — GET classroom/student/{classSubId}/lesson/{lessonId}/dragdrop/{quizzeId}/transcript
    // ================================================================
    public function downloadDragDropTranscript(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $roleCatId = (int) $this->session->get('roleCatID');
        if ($roleCatId !== 4) return redirect()->to('classroom/my');

        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonDragDropModel->getStudentAttempt($quizzeId, $userId);

        if (!$attempt || $attempt['status'] !== 'submitted') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'No completed attempt found.');
        }

        $db            = \Config\Database::connect();
        $attemptDetail = $this->lessonDragDropModel->getAttemptWithItems((int) $attempt['attempt_id']);
        $quiz          = $db->table('lesson_quizze')->where('lesson_quizze_id', $quizzeId)->get()->getRowArray();
        $lesson        = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        $studentName   = trim($this->session->get('fname') . ' ' . $this->session->get('lname'));

        $data['attempt']     = $attemptDetail;
        $data['quiz']        = $quiz;
        $data['lesson']      = $lesson;
        $data['studentName'] = $studentName;

        return view('app/classroom/student/dragdrop_transcript', $data);
    }

    // ================================================================
    // TAKE QUIZ — GET classroom/student/{classSubId}/lesson/{lessonId}/quiz/{quizzeId}/take
    // ================================================================

    public function takeQuiz(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $roleCatId = (int) $this->session->get('roleCatID');
        if ($roleCatId !== 4) return redirect()->to('classroom/my');

        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        $classroomSubject = $db->query("
            SELECT cs.class_sub_id, cs.class_id_fk AS class_id, c.class_status
            FROM classroom_subject cs
            INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cs.class_sub_id = ?
        ", [$classSubId])->getRowArray();

        if (!$classroomSubject) {
            return redirect()->to('classroom/my')->with('error', 'Subject not found.');
        }

        if ($classroomSubject['class_status'] !== 'Active') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'This classroom is no longer active. Quizzes cannot be taken.');
        }

        $enrolled = $db->table('classroom_student')
            ->where('class_id_fk', (int) $classroomSubject['class_id'])
            ->where('user_id_fk',  $userId)
            ->where('class_stud_status', 'Active')
            ->get()->getRowArray();

        if (!$enrolled) {
            return redirect()->to('classroom/my')->with('error', 'Not enrolled in this classroom.');
        }

        $quiz = $this->lessonQuizzeModel->getQuizWithQuestions($quizzeId);
        if (!$quiz || (int) $quiz['lesson_id_fk'] !== $lessonId || $quiz['quizze_status'] !== 'Published') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'Quiz not available.');
        }

        // One-time column migration for attempt resumption
        if (!$db->fieldExists('time_remaining', 'lesson_quizze_attempt')) {
            \Config\Database::forge()->addColumn('lesson_quizze_attempt', [
                'time_remaining' => ['type' => 'INT', 'null' => true, 'default' => null],
            ]);
        }

        $attempt          = $this->lessonQuizzeAttemptModel->getStudentAttempt($quizzeId, $userId);
        $remainingSeconds = 0;
        $savedResponses   = [];

        if ($attempt) {
            if (in_array($attempt['status'], ['submitted', 'timed_out'])) {
                return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}/quiz/{$quizzeId}/score");
            }
            $durationSecs = (int) $quiz['quizze_duration'] * 60;
            if ($durationSecs > 0) {
                // Use DB-persisted time_remaining if available; otherwise fall back to wall-clock elapsed
                $remainingSeconds = $attempt['time_remaining'] !== null
                    ? (int) $attempt['time_remaining']
                    : max(0, $durationSecs - (time() - strtotime($attempt['started_at'])));
                if ($remainingSeconds <= 0) {
                    $savedResponses = $this->lessonQuizzeAttemptModel->getSavedResponses((int) $attempt['attempt_id']);
                    $this->lessonQuizzeAttemptModel->submitAttempt((int) $attempt['attempt_id'], $savedResponses, 'timed_out');
                    return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}/quiz/{$quizzeId}/score");
                }
            }
            $savedResponses = $this->lessonQuizzeAttemptModel->getSavedResponses((int) $attempt['attempt_id']);
        } else {
            $attemptId        = $this->lessonQuizzeAttemptModel->startAttempt($quizzeId, $lessonId, $userId);
            $attempt          = $this->lessonQuizzeAttemptModel->find($attemptId);
            $remainingSeconds = (int) $quiz['quizze_duration'] * 60;
        }

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);

        $this->setPageData($quiz['quizze_name'], 'Classroom', 'Quiz');

        $data['quiz']             = $quiz;
        $data['attempt']          = $attempt;
        $data['lesson']           = $lesson;
        $data['classSubId']       = $classSubId;
        $data['remainingSeconds'] = $remainingSeconds;
        $data['savedResponses']   = $savedResponses;
        $data['tickUrl']          = base_url("classroom/quiz/attempt/{$attempt['attempt_id']}/tick");
        $data['saveAnswerUrl']    = base_url("classroom/quiz/attempt/{$attempt['attempt_id']}/save-answer");
        $data['submitUrl']        = base_url("classroom/lesson/{$lessonId}/quiz/{$quizzeId}/attempt/{$attempt['attempt_id']}/submit");
        $data['scoreUrl']         = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}/quiz/{$quizzeId}/score");
        $data['backUrl']          = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}");
        $data['_view']            = 'app/classroom/student/quiz_take';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // SUBMIT QUIZ ATTEMPT — POST classroom/lesson/{lessonId}/quiz/{quizzeId}/attempt/{attemptId}/submit
    // ================================================================

    public function submitQuizAttempt(int $lessonId, int $quizzeId, int $attemptId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonQuizzeAttemptModel->find($attemptId);

        if (!$attempt || (int) $attempt['user_id_fk'] !== $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid attempt.']);
        }

        if ($attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['success' => false, 'message' => 'Attempt already submitted.']);
        }

        $status    = $this->request->getPost('status') === 'timed_out' ? 'timed_out' : 'submitted';
        $responses = $this->request->getPost('responses') ?? [];

        $result = $this->lessonQuizzeAttemptModel->submitAttempt($attemptId, (array) $responses, $status);

        return $this->response->setJSON($result);
    }

    // ================================================================
    // QUIZ TICK — POST classroom/quiz/attempt/{attemptId}/tick
    // ================================================================

    public function saveQuizTick(int $attemptId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false]);
        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonQuizzeAttemptModel->find($attemptId);
        if (!$attempt || (int) $attempt['user_id_fk'] !== $userId || $attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['success' => false]);
        }
        $secs = max(0, (int) $this->request->getPost('time_remaining'));
        $this->lessonQuizzeAttemptModel->update($attemptId, ['time_remaining' => $secs]);
        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // QUIZ SAVE ANSWER — POST classroom/quiz/attempt/{attemptId}/save-answer
    // ================================================================

    public function saveQuizAnswer(int $attemptId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false]);
        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonQuizzeAttemptModel->find($attemptId);
        if (!$attempt || (int) $attempt['user_id_fk'] !== $userId || $attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['success' => false]);
        }
        $questionId = (int) $this->request->getPost('question_id');
        $answerId   = (int) $this->request->getPost('answer_id');
        if (!$questionId || !$answerId) return $this->response->setJSON(['success' => false]);

        $db       = \Config\Database::connect();
        $existing = $db->table('lesson_quizze_response')
            ->where('attempt_id_fk', $attemptId)
            ->where('question_id_fk', $questionId)
            ->get()->getRowArray();
        if ($existing) {
            $db->table('lesson_quizze_response')
                ->where('response_id', $existing['response_id'])
                ->update(['answer_id_fk' => $answerId]);
        } else {
            $db->table('lesson_quizze_response')->insert([
                'attempt_id_fk'  => $attemptId,
                'question_id_fk' => $questionId,
                'answer_id_fk'   => $answerId,
                'is_correct'     => 0,
            ]);
        }
        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // DRAG-DROP TICK — POST classroom/dragdrop/attempt/{attemptId}/tick
    // ================================================================

    public function saveDragDropTick(int $attemptId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false]);
        $userId  = (int) $this->session->get('userID');
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_dragdrop_attempt')->where('attempt_id', $attemptId)->get()->getRowArray();
        if (!$attempt || (int) $attempt['user_id_fk'] !== $userId || $attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['success' => false]);
        }
        $secs = max(0, (int) $this->request->getPost('time_remaining'));
        $db->table('lesson_dragdrop_attempt')->where('attempt_id', $attemptId)->update(['time_remaining' => $secs]);
        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // DRAG-DROP SAVE ANSWER — POST classroom/dragdrop/attempt/{attemptId}/save-answer
    // ================================================================

    public function saveDragDropAnswer(int $attemptId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false]);
        $userId  = (int) $this->session->get('userID');
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_dragdrop_attempt')->where('attempt_id', $attemptId)->get()->getRowArray();
        if (!$attempt || (int) $attempt['user_id_fk'] !== $userId || $attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['success' => false]);
        }
        $itemId = (int) $this->request->getPost('item_id');
        $raw    = $this->request->getPost('zone_id');
        $zoneId = ($raw !== null && $raw !== '' && (int) $raw > 0) ? (int) $raw : null;
        if (!$itemId) return $this->response->setJSON(['success' => false]);

        $existing = $db->table('lesson_dragdrop_attempt_item')
            ->where('attempt_id_fk', $attemptId)->where('item_id_fk', $itemId)
            ->get()->getRowArray();
        if ($existing) {
            $db->table('lesson_dragdrop_attempt_item')
                ->where('id', $existing['id'])->update(['zone_id_fk' => $zoneId]);
        } else {
            $db->table('lesson_dragdrop_attempt_item')->insert([
                'attempt_id_fk' => $attemptId,
                'item_id_fk'    => $itemId,
                'zone_id_fk'    => $zoneId,
                'is_correct'    => 0,
            ]);
        }
        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // LABEL TICK — POST classroom/label/attempt/{attemptId}/tick
    // ================================================================

    public function saveLabelTick(int $attemptId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false]);
        $userId  = (int) $this->session->get('userID');
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_label_attempt')->where('attempt_id', $attemptId)->get()->getRowArray();
        if (!$attempt || (int) $attempt['user_id_fk'] !== $userId || $attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['success' => false]);
        }
        $secs = max(0, (int) $this->request->getPost('time_remaining'));
        $db->table('lesson_label_attempt')->where('attempt_id', $attemptId)->update(['time_remaining' => $secs]);
        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // LABEL SAVE ANSWER — POST classroom/label/attempt/{attemptId}/save-answer
    // ================================================================

    public function saveLabelAnswer(int $attemptId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false]);
        $userId  = (int) $this->session->get('userID');
        $db      = \Config\Database::connect();
        $attempt = $db->table('lesson_label_attempt')->where('attempt_id', $attemptId)->get()->getRowArray();
        if (!$attempt || (int) $attempt['user_id_fk'] !== $userId || $attempt['status'] !== 'in_progress') {
            return $this->response->setJSON(['success' => false]);
        }
        $markerId     = (int) $this->request->getPost('marker_id');
        $studentLabel = trim($this->request->getPost('student_label') ?? '');
        if (!$markerId) return $this->response->setJSON(['success' => false]);

        $existing = $db->table('lesson_label_attempt_answer')
            ->where('attempt_id_fk', $attemptId)->where('marker_id_fk', $markerId)
            ->get()->getRowArray();
        if ($existing) {
            $db->table('lesson_label_attempt_answer')
                ->where('id', $existing['id'])
                ->update(['student_label' => $studentLabel, 'is_correct' => 0]);
        } else {
            $db->table('lesson_label_attempt_answer')->insert([
                'attempt_id_fk' => $attemptId,
                'marker_id_fk'  => $markerId,
                'student_label' => $studentLabel,
                'is_correct'    => 0,
            ]);
        }
        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // PAST CLASSROOMS — GET classroom/past-classrooms?type=student|teacher
    // ================================================================

    public function getPastClassrooms(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthenticated']);
        }

        $userId = (int) $this->session->get('userID');
        $type   = $this->request->getGet('type') ?? 'student';
        $db     = \Config\Database::connect();

        if ($type === 'teacher') {
            $rows = $db->query("
                SELECT
                    c.class_id, c.class_name, c.class_year, c.class_status,
                    school.sch_name, school.sch_logo,
                    cr.cs_role AS role,
                    COUNT(DISTINCT cst.class_sub_id_fk) AS subject_count
                FROM classroom_subject_teacher cst
                INNER JOIN classroom_subject cs2 ON cs2.class_sub_id = cst.class_sub_id_fk
                INNER JOIN classroom c            ON c.class_id       = cs2.class_id_fk
                LEFT JOIN stream     ON stream.stream_id       = c.stream_id_fk
                LEFT JOIN sch_level  ON sch_level.sch_level_id = stream.sch_level_id_fk
                LEFT JOIN school     ON school.sch_id          = sch_level.sch_id_fk
                LEFT JOIN classroom_role cr
                    ON cr.class_id_fk = c.class_id AND cr.user_id_fk = ? AND cr.cs_status = 'Active'
                WHERE cst.user_id_fk = ?
                  AND c.class_status != 'Active'
                GROUP BY c.class_id, c.class_name, c.class_year, c.class_status,
                         school.sch_name, school.sch_logo, cr.cs_role
                ORDER BY c.class_year DESC, c.class_name ASC
            ", [$userId, $userId])->getResultArray();
        } else {
            $rows = $db->query("
                SELECT
                    c.class_id, c.class_name, c.class_year, c.class_status,
                    school.sch_name, school.sch_logo,
                    cr.cs_role AS role,
                    (SELECT COUNT(*) FROM classroom_subject cs3 WHERE cs3.class_id_fk = c.class_id) AS subject_count
                FROM classroom c
                INNER JOIN classroom_student cstu
                    ON cstu.class_id_fk = c.class_id AND cstu.user_id_fk = ?
                LEFT JOIN stream     ON stream.stream_id       = c.stream_id_fk
                LEFT JOIN sch_level  ON sch_level.sch_level_id = stream.sch_level_id_fk
                LEFT JOIN school     ON school.sch_id          = sch_level.sch_id_fk
                LEFT JOIN classroom_role cr
                    ON cr.class_id_fk = c.class_id AND cr.user_id_fk = ? AND cr.cs_status = 'Active'
                WHERE c.class_status != 'Active'
                ORDER BY c.class_year DESC, c.class_name ASC
            ", [$userId, $userId])->getResultArray();
        }

        $navuliLogo = base_url('app/assets/media/logos/navuli_logo.png');
        $data = array_map(function ($row) use ($navuliLogo) {
            return [
                'class_name'    => $row['class_name'],
                'class_year'    => $row['class_year'],
                'class_status'  => $row['class_status'],
                'sch_name'      => $row['sch_name'] ?? '—',
                'logo_url'      => !empty($row['sch_logo'])
                                   ? base_url('uploads/schoolLogo/' . $row['sch_logo'])
                                   : $navuliLogo,
                'role'          => $row['role'] ?? null,
                'subject_count' => (int) ($row['subject_count'] ?? 0),
            ];
        }, $rows);

        return $this->response->setJSON(['data' => $data]);
    }

    // ================================================================
    // STUDENT QUIZ SCORE — GET classroom/student/{classSubId}/lesson/{lessonId}/quiz/{quizzeId}/score
    // ================================================================

    public function studentQuizScore(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $roleCatId = (int) $this->session->get('roleCatID');
        if ($roleCatId !== 4) return redirect()->to('classroom/my');

        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonQuizzeAttemptModel->getStudentAttempt($quizzeId, $userId);

        if (!$attempt || $attempt['status'] === 'in_progress') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'No completed attempt found.');
        }

        $attemptDetail = $this->lessonQuizzeAttemptModel->getAttemptWithResponses((int) $attempt['attempt_id']);
        $quiz          = $this->lessonQuizzeModel->getQuizWithQuestions($quizzeId);
        $lesson        = $this->classroomLessonModel->getLessonWithSteps($lessonId);

        $this->setPageData('Quiz Score', 'Classroom', 'Score');

        $data['attempt']    = $attemptDetail;
        $data['quiz']       = $quiz;
        $data['lesson']     = $lesson;
        $data['classSubId'] = $classSubId;
        $data['backUrl']    = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}");
        $data['transcriptUrl'] = base_url("classroom/student/{$classSubId}/lesson/{$lessonId}/quiz/{$quizzeId}/transcript");
        $data['_view']      = 'app/classroom/student/quiz_score';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // DOWNLOAD QUIZ TRANSCRIPT — GET classroom/student/{classSubId}/lesson/{lessonId}/quiz/{quizzeId}/transcript
    // ================================================================

    public function downloadQuizTranscript(int $classSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $roleCatId = (int) $this->session->get('roleCatID');
        if ($roleCatId !== 4) return redirect()->to('classroom/my');

        $userId  = (int) $this->session->get('userID');
        $attempt = $this->lessonQuizzeAttemptModel->getStudentAttempt($quizzeId, $userId);

        if (!$attempt || $attempt['status'] === 'in_progress') {
            return redirect()->to("classroom/student/{$classSubId}/lesson/{$lessonId}")
                ->with('error', 'No completed attempt found.');
        }

        $db            = \Config\Database::connect();
        $attemptDetail = $this->lessonQuizzeAttemptModel->getAttemptWithResponses((int) $attempt['attempt_id']);
        $quiz          = $this->lessonQuizzeModel->getQuizWithQuestions($quizzeId);
        $lesson        = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        $studentName   = trim($this->session->get('fname') . ' ' . $this->session->get('lname'));

        $data['attempt']     = $attemptDetail;
        $data['quiz']        = $quiz;
        $data['lesson']      = $lesson;
        $data['studentName'] = $studentName;

        return view('app/classroom/student/quiz_transcript', $data);
    }

    // ================================================================
    // STORE LESSON — POST classroom/teacher/{classId}/lesson/store
    // ================================================================

    public function storeLesson(int $schSubId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $classSubId = (int) $this->request->getPost('class_sub_id');
            $title      =       $this->request->getPost('lesson_title');
            $desc       =       $this->request->getPost('lesson_desc') ?? '';
            $term       = (int) $this->request->getPost('lesson_term') ?: 1;
            $week       = (int) $this->request->getPost('lesson_week') ?: null;
            $day        = (int) $this->request->getPost('lesson_day')  ?: null; // 1=Mon…5=Fri
            $year       = (int) $this->request->getPost('lesson_year') ?: (int) date('Y');

            if (!$classSubId || !$title || !$week) {
                return $this->response->setJSON(['success' => false, 'message' => 'Title, week, and subject are required.']);
            }
            if ($day !== null && ($day < 1 || $day > 5)) $day = null;

            $db = \Config\Database::connect();

            $classStatus = $db->query("
                SELECT c.class_status FROM classroom_subject cs
                INNER JOIN classroom c ON c.class_id = cs.class_id_fk
                WHERE cs.class_sub_id = ?
            ", [$classSubId])->getRow();
            if (!$classStatus || $classStatus->class_status !== 'Active') {
                return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active. Lessons cannot be added.']);
            }

            // Auto-assign lesson_order (next in this term/week/day)
            $maxOrder = $db->query(
                "SELECT COALESCE(MAX(lesson_order),0)+1 AS next_order FROM classroom_lesson WHERE class_sub_id_fk = ? AND lesson_term = ? AND lesson_week = ?",
                [$classSubId, $term, $week]
            )->getRow()->next_order;

            $lessonId = $this->classroomLessonModel->insert([
                'class_sub_id_fk' => $classSubId,
                'lesson_title'    => $title,
                'lesson_desc'     => $desc ?: null,
                'lesson_term'     => $term,
                'lesson_week'     => $week,
                'lesson_day'      => $day,
                'lesson_year'     => $year,
                'lesson_order'    => (int) $maxOrder,
                'lesson_status'   => 'Published',
                'created_by'      => (int) $this->session->get('userID'),
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            return $this->response->setJSON([
                'success'    => true,
                'lesson_id'  => $lessonId,
                'message'    => 'Lesson created.',
                'redirect'   => base_url("classroom/teacher/{$schSubId}/lesson/{$lessonId}"),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::storeLesson] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // LESSON DETAIL — GET classroom/teacher/{classId}/lesson/{lessonId}
    // ================================================================

    public function teacherLessonDetail(int $schSubId, int $lessonId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        if (!$lesson) {
            return redirect()->to("classroom/teacher/{$schSubId}/lessons")->with('error', 'Lesson not found.');
        }

        $db         = \Config\Database::connect();
        $subjectData = $db->query("
            SELECT ss.sch_sub_id, sub.subject_name
            FROM sch_subject ss
            INNER JOIN subject sub ON sub.subject_id = ss.subject_id_fk
            WHERE ss.sch_sub_id = ?
        ", [$schSubId])->getRowArray();

        $classroomStatus = $db->query("
            SELECT c.class_status
            FROM classroom_lesson cl
            INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
            INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cl.lesson_id = ?
        ", [$lessonId])->getRow();

        $userId = (int) $this->session->get('userID');

        $this->setPageData($lesson['lesson_title'], 'Classroom', 'Lesson');

        $data['lesson']        = $lesson;
        $data['subjectData']   = $subjectData;
        $data['isActive']      = $classroomStatus && $classroomStatus->class_status === 'Active';
        $data['schSubId']      = $schSubId;
        $sessionPhoto          = $this->session->get('photo');
        $data['sessionFname']  = $this->session->get('fname') ?? 'you';
        $data['sessionPhotoUrl'] = $sessionPhoto ? base_url('uploads/profilePhoto/' . $sessionPhoto) : null;
        $data['sessionUserId']   = $userId;
        $data['discussions']     = $this->lessonDiscussionModel->getDiscussions($lessonId, $userId);
        $data['quizzes']         = $this->lessonQuizzeModel->getQuizzesWithQuestionsForLesson($lessonId);

        // Enrich each assessment with content_count so the view can compute publish-readiness
        foreach ($data['quizzes'] as &$_q) {
            $_qId   = (int) $_q['lesson_quizze_id'];
            $_qType = $_q['assessment_type'] ?? 'quiz';
            if ($_qType === 'drag_drop') {
                $_q['content_count'] = $db->table('lesson_dragdrop_item')->where('quizze_id_fk', $_qId)->countAllResults();
                $_q['zone_count']    = $db->table('lesson_dragdrop_zone')->where('quizze_id_fk', $_qId)->countAllResults();
            } elseif ($_qType === 'labelling') {
                $_q['content_count'] = $db->table('lesson_label_question')->where('quizze_id_fk', $_qId)->countAllResults();
            } else {
                $_q['content_count'] = count($_q['questions']);
            }
        }
        unset($_q);

        // Attempt counts per quiz (submitted or timed_out only)
        $quizAttemptCounts = [];
        if (!empty($data['quizzes'])) {
            $quizIds      = array_column($data['quizzes'], 'lesson_quizze_id');
            $placeholders = implode(',', array_fill(0, count($quizIds), '?'));
            $counts       = $db->query("
                SELECT quizze_id_fk, COUNT(*) AS attempt_count
                FROM lesson_quizze_attempt
                WHERE quizze_id_fk IN ({$placeholders}) AND status IN ('submitted','timed_out')
                GROUP BY quizze_id_fk
            ", $quizIds)->getResultArray();
            foreach ($counts as $c) {
                $quizAttemptCounts[(int) $c['quizze_id_fk']] = (int) $c['attempt_count'];
            }
        }
        $data['quizAttemptCounts'] = $quizAttemptCounts;
        $data['_view']           = 'app/classroom/teacher/lesson_detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STORE LESSON STEP — POST classroom/lesson/{lessonId}/step/store
    // ================================================================

    // ================================================================
    // UPDATE LESSON — POST classroom/lesson/{lessonId}/update
    // ================================================================

    public function updateLesson(int $lessonId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $title  = trim($this->request->getPost('lesson_title') ?? '');
        $desc   = trim($this->request->getPost('lesson_desc')  ?? '');
        $term   = (int) $this->request->getPost('lesson_term');
        $week   = (int) $this->request->getPost('lesson_week');
        $day    = (int) $this->request->getPost('lesson_day');
        $year   = (int) $this->request->getPost('lesson_year') ?: (int) date('Y');
        $status = $this->request->getPost('lesson_status');

        if (!$title) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lesson title is required.']);
        }
        if (!in_array($status, ['Published', 'Draft', 'Archived'])) {
            $status = 'Published';
        }
        $day = ($day >= 1 && $day <= 5) ? $day : null;

        $db = \Config\Database::connect();

        $classStatus = $db->query("
            SELECT c.class_status FROM classroom_lesson l
            INNER JOIN classroom_subject cs ON cs.class_sub_id = l.class_sub_id_fk
            INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE l.lesson_id = ?
        ", [$lessonId])->getRow();
        if (!$classStatus || $classStatus->class_status !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active. Lessons cannot be edited.']);
        }

        if ($status === 'Published') {
            $assessments = $db->table('lesson_quizze')->where('lesson_id_fk', $lessonId)->get()->getResultArray();
            $blockers    = [];
            foreach ($assessments as $a) {
                $aId   = (int) $a['lesson_quizze_id'];
                $aName = $a['quizze_name'];
                $aType = $a['assessment_type'] ?? 'quiz';
                if ($aType === 'quiz') {
                    if ($db->table('lesson_quizze_question')->where('lesson_quizze_id_fk', $aId)->countAllResults() === 0) {
                        $blockers[] = "Quiz \"{$aName}\" has no questions.";
                    }
                } elseif ($aType === 'drag_drop') {
                    $items = $db->table('lesson_dragdrop_item')->where('quizze_id_fk', $aId)->countAllResults();
                    $zones = $db->table('lesson_dragdrop_zone')->where('quizze_id_fk', $aId)->countAllResults();
                    if ($items === 0 || $zones === 0) {
                        $blockers[] = "Drag & Drop \"{$aName}\" needs at least one item and one zone.";
                    }
                } elseif ($aType === 'labelling') {
                    if ($db->table('lesson_label_question')->where('quizze_id_fk', $aId)->countAllResults() === 0) {
                        $blockers[] = "Labelling \"{$aName}\" has no questions.";
                    }
                }
            }
            if (!empty($blockers)) {
                return $this->response->setJSON(['success' => false, 'message' => implode(' ', $blockers)]);
            }
        }

        $this->classroomLessonModel->update($lessonId, [
            'lesson_title'  => $title,
            'lesson_desc'   => $desc ?: null,
            'lesson_term'   => $term ?: 1,
            'lesson_week'   => $week ?: null,
            'lesson_day'    => $day,
            'lesson_year'   => $year,
            'lesson_status' => $status,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'lesson'  => [
                'lesson_title'  => $title,
                'lesson_desc'   => $desc,
                'lesson_term'   => $term ?: 1,
                'lesson_status' => $status,
            ],
        ]);
    }

    public function storeLessonStep(int $lessonId)
    {
        return $this->response->setJSON(['success' => false, 'message' => 'Not implemented.']);
    }

    public function deleteLessonStep(int $lessonId, int $stepId)
    {
        return $this->response->setJSON(['success' => false, 'message' => 'Not implemented.']);
    }

    // ================================================================
    // LESSON FILES
    // ================================================================

    public function uploadLessonFile(int $lessonId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db    = \Config\Database::connect();
        $count = $db->table('lesson_file')->where('lesson_id_fk', $lessonId)->countAllResults();

        if ($count >= 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'Maximum 10 files allowed per lesson.']);
        }

        $files = $this->request->getFileMultiple('lesson_files');
        if (empty($files) || !$files[0]->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'No files selected.']);
        }

        $allowed    = ['pdf','doc','docx','xls','xlsx','ppt','pptx','txt','zip','rar','jpg','jpeg','png','gif','webp','svg'];
        $uploadPath = FCPATH . 'uploads/lesson_files/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

        $userId   = (int) $this->session->get('userID');
        $uploaded = [];

        foreach ($files as $file) {
            if (!$file->isValid() || $file->hasMoved()) continue;
            if (($count + count($uploaded)) >= 10) break;

            $ext = strtolower($file->getClientExtension());
            if (!in_array($ext, $allowed)) continue;

            $newName = 'lesson_' . $lessonId . '_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
            $file->move($uploadPath, $newName);

            // Build a clean display name from original filename
            $base = pathinfo($file->getClientName(), PATHINFO_FILENAME);
            $base = str_replace(['_', '-', '.'], ' ', $base);
            $base = preg_replace('/[^\w\s]/u', '', $base);
            $base = ucwords(strtolower(trim(preg_replace('/\s+/', ' ', $base))));
            $displayName = ($base !== '' ? (mb_strlen($base) > 30 ? mb_substr($base, 0, 28) . '…' : $base) : $file->getClientName());

            $db->table('lesson_file')->insert([
                'lesson_id_fk' => $lessonId,
                'file_path'    => $newName,
                'file_name'    => $displayName,
                'file_type'    => $ext,
                'file_size'    => $file->getSize(),
                'uploaded_at'  => date('Y-m-d H:i:s'),
                'uploaded_by'  => $userId,
            ]);

            $uploaded[] = [
                'file_id'   => $db->insertID(),
                'file_name' => $file->getClientName(),
                'file_path' => $newName,
                'file_type' => $ext,
                'file_size' => $file->getSize(),
            ];
        }

        if (empty($uploaded)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No valid files uploaded. Allowed: pdf, doc, docx, xls, xlsx, ppt, pptx, txt, zip, rar, jpg, jpeg, png, gif, webp, svg']);
        }

        return $this->response->setJSON(['success' => true, 'files' => $uploaded, 'total' => $count + count($uploaded)]);
    }

    public function deleteLessonFile(int $lessonId, int $fileId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db   = \Config\Database::connect();
        $file = $db->table('lesson_file')
            ->where('file_id', $fileId)->where('lesson_id_fk', $lessonId)
            ->get()->getRowArray();

        if (!$file) {
            return $this->response->setJSON(['success' => false, 'message' => 'File not found.']);
        }

        $path = FCPATH . 'uploads/lesson_files/' . $file['file_path'];
        if (file_exists($path)) unlink($path);

        $db->table('lesson_file')->where('file_id', $fileId)->delete();

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // LESSON VIDEOS
    // ================================================================

    public function addLessonVideo(int $lessonId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db    = \Config\Database::connect();
        $count = $db->table('lesson_video')->where('lesson_id_fk', $lessonId)->countAllResults();

        if ($count >= 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'Maximum 10 videos allowed per lesson.']);
        }

        $url   = trim($this->request->getPost('video_url')   ?? '');
        $title = trim($this->request->getPost('video_title') ?? '');

        if (!$url) {
            return $this->response->setJSON(['success' => false, 'message' => 'Video URL is required.']);
        }

        $db->table('lesson_video')->insert([
            'lesson_id_fk' => $lessonId,
            'video_url'    => $url,
            'video_title'  => $title ?: null,
            'video_order'  => $count + 1,
        ]);

        $videoId = $db->insertID();

        return $this->response->setJSON([
            'success' => true,
            'video'   => ['video_id' => $videoId, 'video_url' => $url, 'video_title' => $title, 'video_order' => $count + 1],
            'total'   => $count + 1,
        ]);
    }

    public function deleteLessonVideo(int $lessonId, int $videoId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        \Config\Database::connect()->table('lesson_video')
            ->where('video_id', $videoId)->where('lesson_id_fk', $lessonId)->delete();

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // LESSON LINKS
    // ================================================================

    public function addLessonLink(int $lessonId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db    = \Config\Database::connect();
        $count = $db->table('lesson_link')->where('lesson_id_fk', $lessonId)->countAllResults();

        if ($count >= 10) {
            return $this->response->setJSON(['success' => false, 'message' => 'Maximum 10 links allowed per lesson.']);
        }

        $url   = trim($this->request->getPost('link_url')   ?? '');
        $title = trim($this->request->getPost('link_title') ?? '');

        if (!$url) {
            return $this->response->setJSON(['success' => false, 'message' => 'Link URL is required.']);
        }

        $db->table('lesson_link')->insert([
            'lesson_id_fk' => $lessonId,
            'link_url'     => $url,
            'link_title'   => $title ?: null,
            'link_order'   => $count + 1,
        ]);

        $linkId = $db->insertID();

        return $this->response->setJSON([
            'success' => true,
            'link'    => ['link_id' => $linkId, 'link_url' => $url, 'link_title' => $title, 'link_order' => $count + 1],
            'total'   => $count + 1,
        ]);
    }

    public function deleteLessonLink(int $lessonId, int $linkId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        \Config\Database::connect()->table('lesson_link')
            ->where('link_id', $linkId)->where('lesson_id_fk', $lessonId)->delete();

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // LESSON DISCUSSION
    // ================================================================

    public function postDiscussion(int $lessonId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $message = trim($this->request->getPost('message') ?? '');
        if ($message === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Message cannot be empty.']);
        }

        $userId = (int) $this->session->get('userID');
        $now    = date('Y-m-d H:i:s');

        $this->lessonDiscussionModel->insert([
            'lesson_id_fk'   => $lessonId,
            'author'         => $userId,
            'message'        => $message,
            'created_at'     => $now,
            'updated_at'     => $now,
            'created_time'   => time(),
            'message_status' => 1,
        ]);

        $discId = $this->lessonDiscussionModel->getInsertID();

        $db   = \Config\Database::connect();
        $disc = $db->query("
            SELECT ld.lesson_discussion_id, ld.message, ld.created_at,
                   ld.author AS author_id,
                   CONCAT(u.fname, ' ', u.lname) AS author_name,
                   u.profile_photo AS author_photo
            FROM lesson_discussion ld
            INNER JOIN users u ON u.user_id = ld.author
            WHERE ld.lesson_discussion_id = ?
        ", [$discId])->getRowArray();

        $disc['comment_count'] = 0;
        $disc['like_count']    = 0;
        $disc['dislike_count'] = 0;
        $disc['user_reaction'] = null;
        $disc['comments']      = [];

        return $this->response->setJSON(['success' => true, 'discussion' => $disc]);
    }

    public function likeDiscussion(int $lessonId, int $discussionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $type   = $this->request->getPost('type') === 'dislike' ? 'dislike' : 'like';
        $userId = (int) $this->session->get('userID');
        $result = $this->lessonDiscussionModel->toggleLike($discussionId, $userId, $type);

        return $this->response->setJSON(['success' => true] + $result);
    }

    public function postDiscussionComment(int $lessonId, int $discussionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $comment = trim($this->request->getPost('comment') ?? '');
        if ($comment === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment cannot be empty.']);
        }

        $userId = (int) $this->session->get('userID');
        $now    = date('Y-m-d H:i:s');
        $db     = \Config\Database::connect();

        $db->table('lesson_discussion_comment')->insert([
            'discussion_id_fk' => $discussionId,
            'author'           => $userId,
            'comment'          => $comment,
            'created_at'       => $now,
            'comment_status'   => 'Active',
        ]);

        $commentId = $db->insertID();

        $row = $db->query("
            SELECT ldc.comment_id, ldc.comment, ldc.created_at,
                   ldc.author AS author_id,
                   CONCAT(u.fname, ' ', u.lname) AS author_name,
                   u.profile_photo AS author_photo
            FROM lesson_discussion_comment ldc
            INNER JOIN users u ON u.user_id = ldc.author
            WHERE ldc.comment_id = ?
        ", [$commentId])->getRowArray();

        return $this->response->setJSON(['success' => true, 'comment' => $row]);
    }

    public function deleteDiscussion(int $lessonId, int $discussionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        $disc = $db->table('lesson_discussion')
            ->where('lesson_discussion_id', $discussionId)
            ->where('lesson_id_fk', $lessonId)
            ->get()->getRowArray();

        if (!$disc || (int) $disc['author'] !== $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not allowed.']);
        }

        $db->table('lesson_discussion')
            ->where('lesson_discussion_id', $discussionId)
            ->update(['message_status' => 0]);

        return $this->response->setJSON(['success' => true]);
    }

    public function likeDiscussionComment(int $lessonId, int $discussionId, int $commentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $type   = $this->request->getPost('type') === 'dislike' ? 'dislike' : 'like';
        $userId = (int) $this->session->get('userID');
        $result = $this->lessonDiscussionModel->toggleCommentLike($commentId, $userId, $type);

        return $this->response->setJSON(['success' => true] + $result);
    }

    public function getDiscussionReactions(int $lessonId, int $discussionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $reactions = $this->lessonDiscussionModel->getDiscussionReactions($discussionId);
        return $this->response->setJSON(['success' => true, 'reactions' => $reactions]);
    }

    public function getCommentReactions(int $lessonId, int $discussionId, int $commentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $reactions = $this->lessonDiscussionModel->getCommentReactions($commentId);
        return $this->response->setJSON(['success' => true, 'reactions' => $reactions]);
    }

    // ================================================================
    // QUIZ DETAIL — GET classroom/teacher/{schSubId}/lesson/{lessonId}/quiz/{quizId}
    // ================================================================

    public function teacherQuizDetail(int $schSubId, int $lessonId, int $quizId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $quiz = $this->lessonQuizzeModel->getQuizWithQuestions($quizId);
        if (!$quiz || (int) $quiz['lesson_id_fk'] !== $lessonId) {
            return redirect()->to("classroom/teacher/{$schSubId}/lesson/{$lessonId}")->with('error', 'Quiz not found.');
        }

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        if (!$lesson) {
            return redirect()->to("classroom/teacher/{$schSubId}/lessons")->with('error', 'Lesson not found.');
        }

        $this->setPageData($quiz['quizze_name'], 'Classroom', 'Quiz');

        $data['quiz']      = $quiz;
        $data['lesson']    = $lesson;
        $data['schSubId']  = $schSubId;
        $data['_view']     = 'app/classroom/teacher/quizze_detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // QUIZ — POST classroom/lesson/{lessonId}/quiz/store
    // ================================================================

    public function storeQuiz(int $lessonId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $name     = trim($this->request->getPost('quizze_name') ?? '');
        $duration = (int) ($this->request->getPost('quizze_duration') ?? 0);
        $status   = in_array($this->request->getPost('quizze_status'), ['Draft', 'Published'])
                    ? $this->request->getPost('quizze_status') : 'Draft';
        $validTypes     = ['quiz', 'drag_drop', 'labelling', 'simulation'];
        $assessmentType = in_array($this->request->getPost('assessment_type'), $validTypes)
                    ? $this->request->getPost('assessment_type') : 'quiz';

        if (!$name) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assessment name is required.']);
        }

        $db = \Config\Database::connect();
        $db->table('lesson_quizze')->insert([
            'lesson_id_fk'    => $lessonId,
            'assessment_type' => $assessmentType,
            'quizze_name'     => $name,
            'quizze_duration' => $duration,
            'quizze_status'   => $status,
        ]);
        $newId = (int) $db->insertID();

        // For non-quiz types, return a builder redirect URL instead of a card
        $redirectUrl = null;
        if ($assessmentType === 'drag_drop') {
            // schSubId is not available here; the JS already knows it — return the partial path
            $redirectUrl = base_url('classroom/lesson/' . $lessonId . '/dragdrop/' . $newId . '/builder-redirect');
        }

        return $this->response->setJSON([
            'success'      => true,
            'redirect_url' => $redirectUrl,
            'quiz'         => [
                'lesson_quizze_id' => $newId,
                'assessment_type'  => $assessmentType,
                'quizze_name'      => $name,
                'quizze_duration'  => $duration,
                'quizze_status'    => $status,
                'questions'        => [],
            ],
        ]);
    }

    public function deleteQuiz(int $lessonId, int $quizId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db   = \Config\Database::connect();
        $quiz = $db->table('lesson_quizze')->where('lesson_quizze_id', $quizId)->get()->getRowArray();

        if ($quiz && ($quiz['quizze_status'] ?? '') === 'Published') {
            return $this->response->setJSON(['success' => false, 'message' => 'Published assessments cannot be deleted. Change the status to Draft first.']);
        }

        $aType = $quiz['assessment_type'] ?? 'quiz';
        if ($aType === 'drag_drop') {
            $this->lessonDragDropModel->deleteAll($quizId);
        } elseif ($aType === 'labelling') {
            $this->lessonLabelModel->deleteAll($quizId);
        } else {
            $questions = $db->table('lesson_quizze_question')
                ->where('lesson_quizze_id_fk', $quizId)->get()->getResultArray();
            foreach ($questions as $q) {
                $qId   = (int) $q['quizze_quest_id'];
                $files = $db->table('lesson_quizze_question_file')
                    ->where('quizze_quest_id_fk', $qId)->get()->getResultArray();
                foreach ($files as $f) {
                    $path = FCPATH . 'uploads/quiz_files/' . $f['file_src'];
                    if (file_exists($path)) unlink($path);
                }
                $db->table('lesson_quizze_question_file')->where('quizze_quest_id_fk', $qId)->delete();
                $db->table('lesson_quizze_answer')->where('quizze_quest_id_fk', $qId)->delete();
            }
            $db->table('lesson_quizze_question')->where('lesson_quizze_id_fk', $quizId)->delete();
        }
        $db->table('lesson_quizze')->where('lesson_quizze_id', $quizId)->delete();

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // UPDATE QUIZ — POST classroom/lesson/{lessonId}/quiz/{quizId}/update
    // ================================================================

    public function updateQuiz(int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $name     = trim($this->request->getPost('quizze_name') ?? '');
        $duration = (int) ($this->request->getPost('quizze_duration') ?? 0);
        $status   = $this->request->getPost('quizze_status') ?? 'Draft';

        if (!$name) {
            return $this->response->setJSON(['success' => false, 'message' => 'Quiz name is required.']);
        }

        $db   = \Config\Database::connect();
        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizzeId)
            ->where('lesson_id_fk', $lessonId)
            ->get()->getRowArray();

        if (!$quiz) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assessment not found.']);
        }

        if (($quiz['quizze_status'] ?? '') === 'Published') {
            return $this->response->setJSON(['success' => false, 'message' => 'Published assessments cannot be edited.']);
        }

        $hasAttempts = (int) $db->table('lesson_quizze_attempt')
            ->where('quizze_id_fk', $quizzeId)
            ->whereIn('status', ['submitted', 'timed_out'])
            ->countAllResults() > 0;

        if ($hasAttempts) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot edit an assessment that students have already attempted.']);
        }

        $newStatus = in_array($status, ['Published', 'Draft']) ? $status : 'Draft';

        if ($newStatus === 'Published') {
            $aType   = $quiz['assessment_type'] ?? 'quiz';
            $blocker = null;
            if ($aType === 'quiz') {
                if ($db->table('lesson_quizze_question')->where('lesson_quizze_id_fk', $quizzeId)->countAllResults() === 0) {
                    $blocker = 'Add at least one question before publishing this quiz.';
                }
            } elseif ($aType === 'drag_drop') {
                $items = $db->table('lesson_dragdrop_item')->where('quizze_id_fk', $quizzeId)->countAllResults();
                $zones = $db->table('lesson_dragdrop_zone')->where('quizze_id_fk', $quizzeId)->countAllResults();
                if ($items === 0 || $zones === 0) {
                    $blocker = 'Add at least one item and one drop zone before publishing this drag & drop activity.';
                }
            } elseif ($aType === 'labelling') {
                if ($db->table('lesson_label_question')->where('quizze_id_fk', $quizzeId)->countAllResults() === 0) {
                    $blocker = 'Add at least one labelling question before publishing this activity.';
                }
            }
            if ($blocker) {
                return $this->response->setJSON(['success' => false, 'message' => $blocker]);
            }
        }

        $db->table('lesson_quizze')->where('lesson_quizze_id', $quizzeId)->update([
            'quizze_name'     => $name,
            'quizze_duration' => $duration,
            'quizze_status'   => $newStatus,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'quiz'    => [
                'lesson_quizze_id' => $quizzeId,
                'quizze_name'      => $name,
                'quizze_duration'  => $duration,
                'quizze_status'    => $newStatus,
            ],
        ]);
    }

    // ================================================================
    // TEACHER QUIZ ANALYSIS — GET classroom/teacher/{schSubId}/lesson/{lessonId}/quiz/{quizzeId}/analysis
    // ================================================================

    public function teacherQuizAnalysis(int $schSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $db   = \Config\Database::connect();
        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizzeId)
            ->where('lesson_id_fk', $lessonId)
            ->get()->getRowArray();

        if (!$quiz) {
            return redirect()->to("classroom/teacher/{$schSubId}/lesson/{$lessonId}")
                ->with('error', 'Quiz not found.');
        }

        $attempts  = $db->table('lesson_quizze_attempt')
            ->where('quizze_id_fk', $quizzeId)
            ->whereIn('status', ['submitted', 'timed_out'])
            ->get()->getResultArray();

        $totalAttempts  = count($attempts);
        $scores         = array_map(fn($a) => (float) $a['score'], $attempts);
        $avgScore       = $totalAttempts > 0 ? round(array_sum($scores) / $totalAttempts, 2) : 0;
        $highScore      = $totalAttempts > 0 ? (float) max($scores) : 0;
        $lowScore       = $totalAttempts > 0 ? (float) min($scores) : 0;
        $submittedCount = count(array_filter($attempts, fn($a) => $a['status'] === 'submitted'));
        $timedOutCount  = $totalAttempts - $submittedCount;

        $distribution = [0, 0, 0, 0, 0]; // 0-19, 20-39, 40-59, 60-79, 80-100
        foreach ($scores as $s) {
            if      ($s < 20) $distribution[0]++;
            elseif  ($s < 40) $distribution[1]++;
            elseif  ($s < 60) $distribution[2]++;
            elseif  ($s < 80) $distribution[3]++;
            else               $distribution[4]++;
        }

        // Per-question stats
        $questions     = $db->table('lesson_quizze_question')
            ->where('lesson_quizze_id_fk', $quizzeId)
            ->orderBy('quizze_quest_id', 'ASC')
            ->get()->getResultArray();

        $questionStats = [];
        foreach ($questions as $qi => $q) {
            $qId = (int) $q['quizze_quest_id'];
            $row = $db->query("
                SELECT
                    COUNT(*)                   AS total,
                    SUM(r.is_correct)          AS correct
                FROM lesson_quizze_response r
                INNER JOIN lesson_quizze_attempt a ON a.attempt_id = r.attempt_id_fk
                WHERE r.question_id_fk = ? AND a.status IN ('submitted','timed_out')
            ", [$qId])->getRowArray();

            $total   = (int) ($row['total']   ?? 0);
            $correct = (int) ($row['correct'] ?? 0);
            $label   = mb_strlen($q['question']) > 60 ? mb_substr($q['question'], 0, 57) . '…' : $q['question'];

            $questionStats[] = [
                'number'    => $qi + 1,
                'question'  => $label,
                'total'     => $total,
                'correct'   => $correct,
                'incorrect' => $total - $correct,
                'pct'       => $total > 0 ? round(($correct / $total) * 100, 1) : 0,
            ];
        }

        // Enrolled students
        $classRow = $db->query("
            SELECT cs.class_id_fk
            FROM classroom_subject cs
            INNER JOIN classroom_lesson cl ON cl.class_sub_id_fk = cs.class_sub_id
            WHERE cl.lesson_id = ?
            LIMIT 1
        ", [$lessonId])->getRowArray();

        $enrolledCount = $classRow
            ? (int) $db->table('classroom_student')
                ->where('class_id_fk', $classRow['class_id_fk'])
                ->where('class_stud_status', 'Active')
                ->countAllResults()
            : 0;

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);

        $this->setPageData('Quiz Analysis', 'Classroom', 'Analysis');

        $data['quiz']            = $quiz;
        $data['lesson']          = $lesson;
        $data['schSubId']        = $schSubId;
        $data['lessonId']        = $lessonId;
        $data['quizzeId']        = $quizzeId;
        $data['totalAttempts']   = $totalAttempts;
        $data['enrolledCount']   = $enrolledCount;
        $data['avgScore']        = $avgScore;
        $data['highScore']       = $highScore;
        $data['lowScore']        = $lowScore;
        $data['distribution']    = $distribution;
        $data['submittedCount']  = $submittedCount;
        $data['timedOutCount']   = $timedOutCount;
        $data['questionStats']   = $questionStats;
        $data['totalQuestions']  = count($questions);
        $data['backUrl']         = base_url("classroom/teacher/{$schSubId}/lesson/{$lessonId}");
        $data['_view']           = 'app/classroom/teacher/quiz_analysis';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // TEACHER QUIZ ATTEMPTS — GET classroom/teacher/{schSubId}/lesson/{lessonId}/quiz/{quizzeId}/attempts
    // ================================================================

    public function teacherQuizAttempts(int $schSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $attempts = $db->query("
            SELECT
                a.attempt_id, a.status, a.score, a.correct_answers, a.total_questions,
                a.submitted_at,
                CONCAT(u.fname, ' ', u.lname) AS student_name
            FROM lesson_quizze_attempt a
            INNER JOIN users u ON u.user_id = a.user_id_fk
            WHERE a.quizze_id_fk = ? AND a.status IN ('submitted','timed_out')
            ORDER BY a.score DESC
        ", [$quizzeId])->getResultArray();

        foreach ($attempts as &$a) {
            $a['submitted_at'] = $a['submitted_at'] ? date('M j, Y g:i A', strtotime($a['submitted_at'])) : null;
        }

        return $this->response->setJSON(['success' => true, 'attempts' => $attempts]);
    }

    public function storeQuizQuestion(int $lessonId, int $quizId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $question = trim($this->request->getPost('question') ?? '');
        if (!$question) {
            return $this->response->setJSON(['success' => false, 'message' => 'Question text is required.']);
        }

        $answers = $this->request->getPost('answers') ?? [];
        $correct = (int) ($this->request->getPost('correct_answer') ?? -1);
        $filled  = array_filter($answers, fn($a) => trim($a) !== '');

        if (count($filled) < 2) {
            return $this->response->setJSON(['success' => false, 'message' => 'At least 2 answers are required.']);
        }
        if ($correct < 0 || $correct > 3 || trim($answers[$correct] ?? '') === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select a valid correct answer.']);
        }

        $db = \Config\Database::connect();
        $db->table('lesson_quizze_question')->insert([
            'lesson_quizze_id_fk' => $quizId,
            'question'            => $question,
            'status'              => 'Active',
        ]);
        $questionId = $db->insertID();

        $savedAnswers = [];
        foreach ($answers as $idx => $ans) {
            $ans = trim($ans);
            if ($ans === '') continue;
            $db->table('lesson_quizze_answer')->insert([
                'quizze_quest_id_fk' => $questionId,
                'answer'             => $ans,
                'is_correct_answer'  => ($idx === $correct) ? 1 : 0,
            ]);
            $savedAnswers[] = [
                'answer'            => $ans,
                'is_correct_answer' => ($idx === $correct) ? 1 : 0,
            ];
        }

        // Image uploads (max 4, images only)
        $uploadedFiles = [];
        $files         = $this->request->getFileMultiple('question_images');
        if (!empty($files) && $files[0]->isValid()) {
            $allowedImg = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $uploadPath = FCPATH . 'uploads/quiz_files/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

            $imgCount = 0;
            foreach ($files as $file) {
                if (!$file->isValid() || $file->hasMoved() || $imgCount >= 4) break;
                $ext = strtolower($file->getClientExtension());
                if (!in_array($ext, $allowedImg)) continue;
                $newName = 'quiz_' . $quizId . '_q' . $questionId . '_' . time() . '_' . random_int(100, 999) . '.' . $ext;
                $file->move($uploadPath, $newName);
                $db->table('lesson_quizze_question_file')->insert([
                    'quizze_quest_id_fk' => $questionId,
                    'file_src'           => $newName,
                    'status'             => 'Active',
                ]);
                $uploadedFiles[] = ['file_src' => $newName];
                $imgCount++;
            }
        }

        return $this->response->setJSON([
            'success'  => true,
            'question' => [
                'quizze_quest_id' => $questionId,
                'question'        => $question,
                'files'           => $uploadedFiles,
                'answers'         => $savedAnswers,
            ],
        ]);
    }

    public function deleteQuizQuestion(int $lessonId, int $quizId, int $questionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db    = \Config\Database::connect();
        $files = $db->table('lesson_quizze_question_file')
            ->where('quizze_quest_id_fk', $questionId)->get()->getResultArray();
        foreach ($files as $f) {
            $path = FCPATH . 'uploads/quiz_files/' . $f['file_src'];
            if (file_exists($path)) unlink($path);
        }
        $db->table('lesson_quizze_question_file')->where('quizze_quest_id_fk', $questionId)->delete();
        $db->table('lesson_quizze_answer')->where('quizze_quest_id_fk', $questionId)->delete();
        $db->table('lesson_quizze_question')->where('quizze_quest_id', $questionId)->delete();

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // DRAG & DROP — Teacher builder
    // GET classroom/teacher/{schSubId}/lesson/{lessonId}/dragdrop/{quizzeId}
    // ================================================================
    public function teacherDragDropDetail(int $schSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $db   = \Config\Database::connect();
        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizzeId)
            ->where('lesson_id_fk', $lessonId)
            ->where('assessment_type', 'drag_drop')
            ->get()->getRowArray();

        if (!$quiz) {
            return redirect()->to("classroom/teacher/{$schSubId}/lesson/{$lessonId}")
                ->with('error', 'Assessment not found.');
        }

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        if (!$lesson) {
            return redirect()->to("classroom/teacher/{$schSubId}/lessons")->with('error', 'Lesson not found.');
        }

        $dd = $this->lessonDragDropModel->getAssessmentData($quizzeId);

        $this->setPageData($quiz['quizze_name'], 'Classroom', 'Drag & Drop Builder');

        $data['quiz']       = $quiz;
        $data['lesson']     = $lesson;
        $data['schSubId']   = $schSubId;
        $data['items']      = $dd['items'];
        $data['zones']      = $dd['zones'];
        $data['answerMap']  = $dd['answers'];
        $data['_view']      = 'app/classroom/teacher/dragdrop_detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // DRAG & DROP ANALYSIS — GET classroom/teacher/{schSubId}/lesson/{lessonId}/dragdrop/{quizzeId}/analysis
    // ================================================================
    public function teacherDragDropAnalysis(int $schSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $db   = \Config\Database::connect();
        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizzeId)
            ->where('lesson_id_fk', $lessonId)
            ->where('assessment_type', 'drag_drop')
            ->get()->getRowArray();

        if (!$quiz) {
            return redirect()->to("classroom/teacher/{$schSubId}/lesson/{$lessonId}")
                ->with('error', 'Assessment not found.');
        }

        $attempts = $db->table('lesson_dragdrop_attempt')
            ->where('quizze_id_fk', $quizzeId)
            ->where('status', 'submitted')
            ->get()->getResultArray();

        $totalAttempts = count($attempts);
        $scores        = array_map(fn($a) => (float) $a['score'], $attempts);
        $avgScore      = $totalAttempts > 0 ? round(array_sum($scores) / $totalAttempts, 2) : 0;
        $highScore     = $totalAttempts > 0 ? (float) max($scores) : 0;
        $lowScore      = $totalAttempts > 0 ? (float) min($scores) : 0;

        $distribution = [0, 0, 0, 0, 0];
        foreach ($scores as $s) {
            if      ($s < 20) $distribution[0]++;
            elseif  ($s < 40) $distribution[1]++;
            elseif  ($s < 60) $distribution[2]++;
            elseif  ($s < 80) $distribution[3]++;
            else               $distribution[4]++;
        }

        $items     = $db->table('lesson_dragdrop_item')
            ->where('quizze_id_fk', $quizzeId)
            ->orderBy('item_order', 'ASC')->orderBy('item_id', 'ASC')
            ->get()->getResultArray();

        $zones     = $db->table('lesson_dragdrop_zone')
            ->where('quizze_id_fk', $quizzeId)
            ->orderBy('zone_order', 'ASC')->orderBy('zone_id', 'ASC')
            ->get()->getResultArray();

        $answerMap = $this->lessonDragDropModel->getAnswerMap($quizzeId);
        $zoneById  = array_column($zones, null, 'zone_id');
        $itemStats = [];

        foreach ($items as $idx => $item) {
            $itemId = (int) $item['item_id'];
            $row = $db->query("
                SELECT COUNT(*) AS total, SUM(ai.is_correct) AS correct
                FROM lesson_dragdrop_attempt_item ai
                INNER JOIN lesson_dragdrop_attempt a ON a.attempt_id = ai.attempt_id_fk
                WHERE ai.item_id_fk = ? AND a.status = 'submitted'
            ", [$itemId])->getRowArray();

            $total         = (int) ($row['total']   ?? 0);
            $correct       = (int) ($row['correct'] ?? 0);
            $corrZoneId    = $answerMap[$itemId] ?? null;
            $corrZoneLabel = $corrZoneId ? ($zoneById[$corrZoneId]['zone_label'] ?? '—') : '—';

            $itemStats[] = [
                'number'       => $idx + 1,
                'item_text'    => $item['item_text'],
                'correct_zone' => $corrZoneLabel,
                'total'        => $total,
                'correct'      => $correct,
                'incorrect'    => $total - $correct,
                'pct'          => $total > 0 ? round(($correct / $total) * 100, 1) : 0,
            ];
        }

        $classRow = $db->query("
            SELECT cs.class_id_fk
            FROM classroom_subject cs
            INNER JOIN classroom_lesson cl ON cl.class_sub_id_fk = cs.class_sub_id
            WHERE cl.lesson_id = ? LIMIT 1
        ", [$lessonId])->getRowArray();

        $enrolledCount = $classRow
            ? (int) $db->table('classroom_student')
                ->where('class_id_fk', $classRow['class_id_fk'])
                ->where('class_stud_status', 'Active')
                ->countAllResults()
            : 0;

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        $this->setPageData('Drag & Drop Analysis', 'Classroom', 'Analysis');

        $data['quiz']          = $quiz;
        $data['lesson']        = $lesson;
        $data['schSubId']      = $schSubId;
        $data['lessonId']      = $lessonId;
        $data['quizzeId']      = $quizzeId;
        $data['totalAttempts'] = $totalAttempts;
        $data['enrolledCount'] = $enrolledCount;
        $data['avgScore']      = $avgScore;
        $data['highScore']     = $highScore;
        $data['lowScore']      = $lowScore;
        $data['distribution']  = $distribution;
        $data['itemStats']     = $itemStats;
        $data['totalItems']    = count($items);
        $data['backUrl']       = base_url("classroom/teacher/{$schSubId}/lesson/{$lessonId}");
        $data['_view']         = 'app/classroom/teacher/dragdrop_analysis';

        return view('app/layouts/main', $data);
    }

    // GET classroom/teacher/{schSubId}/lesson/{lessonId}/dragdrop/{quizzeId}/attempts  (AJAX)
    public function teacherDragDropAttempts(int $schSubId, int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db       = \Config\Database::connect();
        $attempts = $db->query("
            SELECT a.attempt_id, a.status, a.score, a.correct_items, a.total_items,
                   a.submitted_at, CONCAT(u.fname, ' ', u.lname) AS student_name
            FROM lesson_dragdrop_attempt a
            INNER JOIN users u ON u.user_id = a.user_id_fk
            WHERE a.quizze_id_fk = ? AND a.status = 'submitted'
            ORDER BY a.score DESC
        ", [$quizzeId])->getResultArray();

        foreach ($attempts as &$a) {
            $a['submitted_at'] = $a['submitted_at'] ? date('M j, Y g:i A', strtotime($a['submitted_at'])) : null;
        }

        return $this->response->setJSON(['success' => true, 'attempts' => $attempts]);
    }

    // ================================================================
    // DRAG & DROP — Update item
    // POST classroom/lesson/{lessonId}/dragdrop/{quizzeId}/item/{itemId}/update
    // ================================================================
    public function updateDragDropItem(int $lessonId, int $quizzeId, int $itemId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $text = trim($this->request->getPost('item_text') ?? '');
        if (!$text) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item text is required.']);
        }

        $db   = \Config\Database::connect();
        $item = $db->table('lesson_dragdrop_item')
            ->where('item_id', $itemId)
            ->where('quizze_id_fk', $quizzeId)
            ->get()->getRowArray();

        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item not found.']);
        }

        $updateData = ['item_text' => $text];

        $file = $this->request->getFile('item_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext     = strtolower($file->getClientExtension());
            if (in_array($ext, $allowed)) {
                if (!empty($item['item_image'])) {
                    $old = FCPATH . 'uploads/dragdrop_files/' . $item['item_image'];
                    if (file_exists($old)) unlink($old);
                }
                $dir = FCPATH . 'uploads/dragdrop_files/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $imageName = 'dd_' . $quizzeId . '_item_' . time() . '_' . random_int(100, 999) . '.' . $ext;
                $file->move($dir, $imageName);
                $updateData['item_image'] = $imageName;
            }
        }

        $db->table('lesson_dragdrop_item')->where('item_id', $itemId)->update($updateData);

        return $this->response->setJSON([
            'success' => true,
            'item'    => [
                'item_id'    => $itemId,
                'item_text'  => $text,
                'item_image' => $updateData['item_image'] ?? $item['item_image'],
            ],
        ]);
    }

    // ================================================================
    // DRAG & DROP — Update zone
    // POST classroom/lesson/{lessonId}/dragdrop/{quizzeId}/zone/{zoneId}/update
    // ================================================================
    public function updateDragDropZone(int $lessonId, int $quizzeId, int $zoneId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $label = trim($this->request->getPost('zone_label') ?? '');
        if (!$label) {
            return $this->response->setJSON(['success' => false, 'message' => 'Zone label is required.']);
        }

        $db   = \Config\Database::connect();
        $zone = $db->table('lesson_dragdrop_zone')
            ->where('zone_id', $zoneId)
            ->where('quizze_id_fk', $quizzeId)
            ->get()->getRowArray();

        if (!$zone) {
            return $this->response->setJSON(['success' => false, 'message' => 'Zone not found.']);
        }

        $db->table('lesson_dragdrop_zone')->where('zone_id', $zoneId)->update(['zone_label' => $label]);

        return $this->response->setJSON([
            'success' => true,
            'zone'    => ['zone_id' => $zoneId, 'zone_label' => $label],
        ]);
    }

    // ================================================================
    // DRAG & DROP — Store item
    // POST classroom/lesson/{lessonId}/dragdrop/{quizzeId}/item/store
    // ================================================================
    public function storeDragDropItem(int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $text = trim($this->request->getPost('item_text') ?? '');
        if (!$text) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item text is required.']);
        }

        $db    = \Config\Database::connect();
        $count = (int) $db->table('lesson_dragdrop_item')->where('quizze_id_fk', $quizzeId)->countAllResults();

        $imageName = null;
        $file      = $this->request->getFile('item_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext     = strtolower($file->getClientExtension());
            if (in_array($ext, $allowed)) {
                $dir = FCPATH . 'uploads/dragdrop_files/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $imageName = 'dd_' . $quizzeId . '_item_' . time() . '_' . random_int(100, 999) . '.' . $ext;
                $file->move($dir, $imageName);
            }
        }

        $db->table('lesson_dragdrop_item')->insert([
            'quizze_id_fk' => $quizzeId,
            'item_text'    => $text,
            'item_image'   => $imageName,
            'item_order'   => $count + 1,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'item'    => [
                'item_id'      => $db->insertID(),
                'item_text'    => $text,
                'item_image'   => $imageName,
                'item_order'   => $count + 1,
            ],
        ]);
    }

    // ================================================================
    // DRAG & DROP — Delete item
    // POST classroom/lesson/{lessonId}/dragdrop/{quizzeId}/item/{itemId}/delete
    // ================================================================
    public function deleteDragDropItem(int $lessonId, int $quizzeId, int $itemId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db   = \Config\Database::connect();
        $item = $db->table('lesson_dragdrop_item')
            ->where('item_id', $itemId)
            ->where('quizze_id_fk', $quizzeId)
            ->get()->getRowArray();

        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item not found.']);
        }

        if (!empty($item['item_image'])) {
            $path = FCPATH . 'uploads/dragdrop_files/' . $item['item_image'];
            if (file_exists($path)) unlink($path);
        }

        $db->table('lesson_dragdrop_answer')->where('item_id_fk', $itemId)->delete();
        $db->table('lesson_dragdrop_item')->where('item_id', $itemId)->delete();

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // DRAG & DROP — Store zone
    // POST classroom/lesson/{lessonId}/dragdrop/{quizzeId}/zone/store
    // ================================================================
    public function storeDragDropZone(int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $label = trim($this->request->getPost('zone_label') ?? '');
        if (!$label) {
            return $this->response->setJSON(['success' => false, 'message' => 'Zone label is required.']);
        }

        $db    = \Config\Database::connect();
        $count = (int) $db->table('lesson_dragdrop_zone')->where('quizze_id_fk', $quizzeId)->countAllResults();

        $db->table('lesson_dragdrop_zone')->insert([
            'quizze_id_fk' => $quizzeId,
            'zone_label'   => $label,
            'zone_order'   => $count + 1,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'zone'    => [
                'zone_id'    => $db->insertID(),
                'zone_label' => $label,
                'zone_order' => $count + 1,
            ],
        ]);
    }

    // ================================================================
    // DRAG & DROP — Delete zone
    // POST classroom/lesson/{lessonId}/dragdrop/{quizzeId}/zone/{zoneId}/delete
    // ================================================================
    public function deleteDragDropZone(int $lessonId, int $quizzeId, int $zoneId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();

        // Unlink any items mapped to this zone
        $db->table('lesson_dragdrop_answer')
            ->where('quizze_id_fk', $quizzeId)
            ->where('zone_id_fk', $zoneId)
            ->delete();

        $db->table('lesson_dragdrop_zone')
            ->where('zone_id', $zoneId)
            ->where('quizze_id_fk', $quizzeId)
            ->delete();

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // DRAG & DROP — Save answer mappings
    // POST classroom/lesson/{lessonId}/dragdrop/{quizzeId}/answers/save
    // ================================================================
    public function saveDragDropAnswers(int $lessonId, int $quizzeId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // mappings: array of {item_id, zone_id}
        $mappings = $this->request->getPost('mappings') ?? [];
        if (!is_array($mappings)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data.']);
        }

        $db    = \Config\Database::connect();
        $saved = 0;

        foreach ($mappings as $m) {
            $itemId = (int) ($m['item_id'] ?? 0);
            $zoneId = (int) ($m['zone_id'] ?? 0);
            if (!$itemId) continue;

            // Verify item belongs to this assessment
            $item = $db->table('lesson_dragdrop_item')
                ->where('item_id', $itemId)
                ->where('quizze_id_fk', $quizzeId)
                ->get()->getRowArray();
            if (!$item) continue;

            if ($zoneId) {
                // Verify zone belongs to this assessment
                $zone = $db->table('lesson_dragdrop_zone')
                    ->where('zone_id', $zoneId)
                    ->where('quizze_id_fk', $quizzeId)
                    ->get()->getRowArray();
                if (!$zone) continue;
            }

            // Upsert answer
            $existing = $db->table('lesson_dragdrop_answer')
                ->where('item_id_fk', $itemId)
                ->get()->getRowArray();

            if ($zoneId === 0) {
                // Remove mapping if zone_id is 0 (unlinked)
                if ($existing) {
                    $db->table('lesson_dragdrop_answer')->where('item_id_fk', $itemId)->delete();
                }
            } elseif ($existing) {
                $db->table('lesson_dragdrop_answer')
                    ->where('answer_id', $existing['answer_id'])
                    ->update(['zone_id_fk' => $zoneId]);
                $saved++;
            } else {
                $db->table('lesson_dragdrop_answer')->insert([
                    'quizze_id_fk' => $quizzeId,
                    'item_id_fk'   => $itemId,
                    'zone_id_fk'   => $zoneId,
                ]);
                $saved++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'saved'   => $saved,
            'message' => $saved . ' mapping' . ($saved !== 1 ? 's' : '') . ' saved.',
        ]);
    }

    private function getTeacherClassrooms(int $userId, string $status = 'Active'): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT DISTINCT
                c.class_id, c.class_name, c.class_year, c.class_status,
                s.stream_name, l.level_name, sch.sch_name, sch.sch_logo,
                sch.sch_address, sch.sch_phone,
                (SELECT cr_ct.user_id_fk
                 FROM classroom_role cr_ct
                 WHERE cr_ct.class_id_fk = c.class_id
                   AND cr_ct.cs_role = 'Class Teacher' AND cr_ct.cs_status = 'Active'
                 LIMIT 1) AS class_teacher_id,
                (SELECT CONCAT(u_ct.fname, ' ', u_ct.lname)
                 FROM classroom_role cr_ct
                 INNER JOIN users u_ct ON u_ct.user_id = cr_ct.user_id_fk
                 WHERE cr_ct.class_id_fk = c.class_id
                   AND cr_ct.cs_role = 'Class Teacher' AND cr_ct.cs_status = 'Active'
                 LIMIT 1) AS class_teacher,
                (SELECT u_ct.profile_photo
                 FROM classroom_role cr_ct
                 INNER JOIN users u_ct ON u_ct.user_id = cr_ct.user_id_fk
                 WHERE cr_ct.class_id_fk = c.class_id
                   AND cr_ct.cs_role = 'Class Teacher' AND cr_ct.cs_status = 'Active'
                 LIMIT 1) AS class_teacher_photo,
                (SELECT CONCAT(u_cc.fname, ' ', u_cc.lname)
                 FROM classroom_role cr_cc
                 INNER JOIN users u_cc ON u_cc.user_id = cr_cc.user_id_fk
                 WHERE cr_cc.class_id_fk = c.class_id
                   AND cr_cc.cs_role = 'Class Captain' AND cr_cc.cs_status = 'Active'
                 LIMIT 1) AS class_captain,
                (SELECT u_cc.profile_photo
                 FROM classroom_role cr_cc
                 INNER JOIN users u_cc ON u_cc.user_id = cr_cc.user_id_fk
                 WHERE cr_cc.class_id_fk = c.class_id
                   AND cr_cc.cs_role = 'Class Captain' AND cr_cc.cs_status = 'Active'
                 LIMIT 1) AS class_captain_photo,
                (SELECT COUNT(*) FROM classroom_student cs2
                 WHERE cs2.class_id_fk = c.class_id
                   AND cs2.class_stud_status = 'Active') AS student_count
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject csub ON csub.class_sub_id = cst.class_sub_id_fk
            INNER JOIN classroom c            ON c.class_id        = csub.class_id_fk
            INNER JOIN stream s               ON s.stream_id       = c.stream_id_fk
            INNER JOIN sch_level sl           ON sl.sch_level_id   = s.sch_level_id_fk
            INNER JOIN level l                ON l.level_id        = sl.level_id_fk
            INNER JOIN school sch             ON sch.sch_id        = sl.sch_id_fk
            WHERE cst.user_id_fk = ? AND cst.class_sub_teacher_status = ?
            ORDER BY c.class_year DESC, c.class_name ASC
        ", [$userId, $status])->getResultArray();
    }

    private function getAllTeacherClassrooms(int $userId, string $exclude = ''): array
    {
        $db = \Config\Database::connect();
        $rows = $db->query("
            SELECT DISTINCT
                c.class_id, c.class_name, c.class_year, c.class_status,
                s.stream_name, l.level_name, sch.sch_name, sch.sch_logo,
                (SELECT COUNT(*) FROM classroom_student cs2
                 WHERE cs2.class_id_fk = c.class_id) AS student_count
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject csub ON csub.class_sub_id = cst.class_sub_id_fk
            INNER JOIN classroom c            ON c.class_id        = csub.class_id_fk
            INNER JOIN stream s               ON s.stream_id       = c.stream_id_fk
            INNER JOIN sch_level sl           ON sl.sch_level_id   = s.sch_level_id_fk
            INNER JOIN level l                ON l.level_id        = sl.level_id_fk
            INNER JOIN school sch             ON sch.sch_id        = sl.sch_id_fk
            WHERE cst.user_id_fk = ?
            ORDER BY c.class_year DESC, c.class_name ASC
        ", [$userId])->getResultArray();

        if ($exclude !== '') {
            $rows = array_values(array_filter($rows, fn($r) => $r['class_status'] !== $exclude));
        }
        return $rows;
    }

    // ── Student: distinct years from classroom_student ──────────────────
    private function getStudentClassroomYears(int $userId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT DISTINCT c.class_year
            FROM classroom_student cs
            INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cs.user_id_fk = ?
            ORDER BY c.class_year DESC
        ", [$userId])->getResultArray();
        return array_column($rows, 'class_year');
    }

    private function getStudentDefaultYear(int $userId): ?int
    {
        $db  = \Config\Database::connect();
        $row = $db->query("
            SELECT c.class_year FROM classroom_student cs
            INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cs.user_id_fk = ? AND c.class_status = 'Active'
            ORDER BY c.class_year DESC LIMIT 1
        ", [$userId])->getRowArray();
        if ($row) return (int) $row['class_year'];
        $row = $db->query("
            SELECT c.class_year FROM classroom_student cs
            INNER JOIN classroom c ON c.class_id = cs.class_id_fk
            WHERE cs.user_id_fk = ?
            ORDER BY c.class_year DESC LIMIT 1
        ", [$userId])->getRowArray();
        return $row ? (int) $row['class_year'] : null;
    }

    private function getStudentClassroomsForYear(int $userId, int $year): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT
                c.class_id, c.class_name, c.class_year, c.class_status,
                s.stream_id, s.stream_name, l.level_name,
                sch.sch_id, sch.sch_name, sch.sch_logo, sch.sch_address, sch.sch_phone, sch.sch_email,
                cs.class_stud_status,
                (SELECT COUNT(*) FROM classroom_student cs3 WHERE cs3.class_id_fk = c.class_id) AS student_count,
                (SELECT u2.user_id FROM classroom_role cr2
                 INNER JOIN users u2 ON u2.user_id = cr2.user_id_fk
                 WHERE cr2.class_id_fk = c.class_id AND cr2.cs_role = 'Class Teacher' LIMIT 1) AS class_teacher_id,
                (SELECT u2.profile_photo FROM classroom_role cr2
                 INNER JOIN users u2 ON u2.user_id = cr2.user_id_fk
                 WHERE cr2.class_id_fk = c.class_id AND cr2.cs_role = 'Class Teacher' LIMIT 1) AS class_teacher_photo,
                (SELECT CONCAT(u2.fname,' ',u2.lname) FROM classroom_role cr2
                 INNER JOIN users u2 ON u2.user_id = cr2.user_id_fk
                 WHERE cr2.class_id_fk = c.class_id AND cr2.cs_role = 'Class Teacher' LIMIT 1) AS class_teacher,
                (SELECT u4.user_id FROM classroom_role cr4
                 INNER JOIN users u4 ON u4.user_id = cr4.user_id_fk
                 WHERE cr4.class_id_fk = c.class_id AND cr4.cs_role = 'Class Captain' LIMIT 1) AS class_captain_id,
                (SELECT u4.profile_photo FROM classroom_role cr4
                 INNER JOIN users u4 ON u4.user_id = cr4.user_id_fk
                 WHERE cr4.class_id_fk = c.class_id AND cr4.cs_role = 'Class Captain' LIMIT 1) AS class_captain_photo,
                (SELECT CONCAT(u4.fname,' ',u4.lname) FROM classroom_role cr4
                 INNER JOIN users u4 ON u4.user_id = cr4.user_id_fk
                 WHERE cr4.class_id_fk = c.class_id AND cr4.cs_role = 'Class Captain' LIMIT 1) AS class_captain
            FROM classroom_student cs
            INNER JOIN classroom c  ON c.class_id     = cs.class_id_fk
            INNER JOIN stream s     ON s.stream_id     = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
            INNER JOIN level l      ON l.level_id      = sl.level_id_fk
            INNER JOIN school sch   ON sch.sch_id      = sl.sch_id_fk
            WHERE cs.user_id_fk = ? AND c.class_year = ?
            ORDER BY c.class_name ASC
        ", [$userId, $year])->getResultArray();

        foreach ($rows as &$cls) {
            $cls['subjects']    = $this->getClassroomSubjects((int) $cls['class_id']);
            $cls['students']    = $this->getClassroomAllStudents((int) $cls['class_id'], true);
            $cls['discussions'] = $this->classDiscussionModel->getPosts((int) $cls['class_id'], $userId);
        }
        unset($cls);
        return $rows;
    }

    // ── Teacher: distinct years from classroom_subject_teacher ───────────
    private function getTeacherClassroomYears(int $userId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT DISTINCT c.class_year FROM (
                SELECT csub.class_id_fk AS class_id
                FROM classroom_subject_teacher cst
                INNER JOIN classroom_subject csub ON csub.class_sub_id = cst.class_sub_id_fk
                WHERE cst.user_id_fk = ?
                UNION
                SELECT cr.class_id_fk AS class_id
                FROM classroom_role cr
                WHERE cr.user_id_fk = ? AND cr.cs_role = 'Class Teacher' AND cr.cs_status = 'Active'
            ) AS src
            INNER JOIN classroom c ON c.class_id = src.class_id
            ORDER BY c.class_year DESC
        ", [$userId, $userId])->getResultArray();
        return array_column($rows, 'class_year');
    }

    private function getTeacherDefaultYear(int $userId): ?int
    {
        $db  = \Config\Database::connect();
        $row = $db->query("
            SELECT c.class_year FROM (
                SELECT csub.class_id_fk AS class_id
                FROM classroom_subject_teacher cst
                INNER JOIN classroom_subject csub ON csub.class_sub_id = cst.class_sub_id_fk
                WHERE cst.user_id_fk = ?
                UNION
                SELECT cr.class_id_fk AS class_id
                FROM classroom_role cr
                WHERE cr.user_id_fk = ? AND cr.cs_role = 'Class Teacher' AND cr.cs_status = 'Active'
            ) AS src
            INNER JOIN classroom c ON c.class_id = src.class_id
            WHERE c.class_status = 'Active'
            ORDER BY c.class_year DESC LIMIT 1
        ", [$userId, $userId])->getRowArray();
        if ($row) return (int) $row['class_year'];
        $row = $db->query("
            SELECT c.class_year FROM (
                SELECT csub.class_id_fk AS class_id
                FROM classroom_subject_teacher cst
                INNER JOIN classroom_subject csub ON csub.class_sub_id = cst.class_sub_id_fk
                WHERE cst.user_id_fk = ?
                UNION
                SELECT cr.class_id_fk AS class_id
                FROM classroom_role cr
                WHERE cr.user_id_fk = ? AND cr.cs_role = 'Class Teacher' AND cr.cs_status = 'Active'
            ) AS src
            INNER JOIN classroom c ON c.class_id = src.class_id
            ORDER BY c.class_year DESC LIMIT 1
        ", [$userId, $userId])->getRowArray();
        return $row ? (int) $row['class_year'] : null;
    }

    private function getTeacherClassroomsForYear(int $userId, int $year): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT DISTINCT
                c.class_id, c.class_name, c.class_year, c.class_status,
                s.stream_id, s.stream_name, l.level_name, sch.sch_name, sch.sch_logo,
                sch.sch_address, sch.sch_phone,
                (SELECT cr_ct.user_id_fk FROM classroom_role cr_ct
                 WHERE cr_ct.class_id_fk = c.class_id AND cr_ct.cs_role = 'Class Teacher' LIMIT 1) AS class_teacher_id,
                (SELECT CONCAT(u_ct.fname,' ',u_ct.lname) FROM classroom_role cr_ct
                 INNER JOIN users u_ct ON u_ct.user_id = cr_ct.user_id_fk
                 WHERE cr_ct.class_id_fk = c.class_id AND cr_ct.cs_role = 'Class Teacher' LIMIT 1) AS class_teacher,
                (SELECT u_ct.profile_photo FROM classroom_role cr_ct
                 INNER JOIN users u_ct ON u_ct.user_id = cr_ct.user_id_fk
                 WHERE cr_ct.class_id_fk = c.class_id AND cr_ct.cs_role = 'Class Teacher' LIMIT 1) AS class_teacher_photo,
                (SELECT CONCAT(u_cc.fname,' ',u_cc.lname) FROM classroom_role cr_cc
                 INNER JOIN users u_cc ON u_cc.user_id = cr_cc.user_id_fk
                 WHERE cr_cc.class_id_fk = c.class_id AND cr_cc.cs_role = 'Class Captain' LIMIT 1) AS class_captain,
                (SELECT u_cc.profile_photo FROM classroom_role cr_cc
                 INNER JOIN users u_cc ON u_cc.user_id = cr_cc.user_id_fk
                 WHERE cr_cc.class_id_fk = c.class_id AND cr_cc.cs_role = 'Class Captain' LIMIT 1) AS class_captain_photo,
                (SELECT COUNT(*) FROM classroom_student cs2 WHERE cs2.class_id_fk = c.class_id) AS student_count,
                (SELECT COUNT(*) FROM classroom_subject_teacher cst_a
                 INNER JOIN classroom_subject csub_a ON csub_a.class_sub_id = cst_a.class_sub_id_fk
                 WHERE csub_a.class_id_fk = c.class_id AND cst_a.user_id_fk = ?
                   AND cst_a.class_sub_teacher_status = 'Active') AS active_subject_count,
                (SELECT COUNT(*) FROM classroom_subject_teacher cst_t
                 INNER JOIN classroom_subject csub_t ON csub_t.class_sub_id = cst_t.class_sub_id_fk
                 WHERE csub_t.class_id_fk = c.class_id AND cst_t.user_id_fk = ?) AS total_subject_count
            FROM (
                SELECT csub.class_id_fk AS class_id
                FROM classroom_subject_teacher cst
                INNER JOIN classroom_subject csub ON csub.class_sub_id = cst.class_sub_id_fk
                WHERE cst.user_id_fk = ?
                UNION
                SELECT cr.class_id_fk AS class_id
                FROM classroom_role cr
                WHERE cr.user_id_fk = ? AND cr.cs_role = 'Class Teacher' AND cr.cs_status = 'Active'
            ) AS src
            INNER JOIN classroom c   ON c.class_id      = src.class_id
            INNER JOIN stream s      ON s.stream_id      = c.stream_id_fk
            INNER JOIN sch_level sl  ON sl.sch_level_id  = s.sch_level_id_fk
            INNER JOIN level l       ON l.level_id       = sl.level_id_fk
            INNER JOIN school sch    ON sch.sch_id       = sl.sch_id_fk
            WHERE c.class_year = ?
            ORDER BY c.class_name ASC
        ", [$userId, $userId, $userId, $userId, $year])->getResultArray();

        foreach ($rows as &$cls) {
            $cls['students']         = $this->getClassroomAllStudents((int) $cls['class_id'], true);
            $cls['teacher_subjects'] = $this->getTeacherSubjectsForClassroom((int) $cls['class_id'], $userId, true);
            $cls['discussions']      = $this->classDiscussionModel->getPosts((int) $cls['class_id'], $userId);
        }
        unset($cls);
        return $rows;
    }

    // ── AJAX: year-switched classroom content ────────────────────────────
    public function classroomsByYear(int $year)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $userId       = (int) $this->session->get('userID');
        $roleCatId    = (int) $this->session->get('roleCatID');
        $sessionPhoto = $this->session->get('photo');

        $shared = [
            'sessionFname'    => $this->session->get('fname') ?? '',
            'sessionPhotoUrl' => $sessionPhoto ? base_url('uploads/profilePhoto/' . $sessionPhoto) : null,
            'sessionUserId'   => $userId,
        ];

        if ($roleCatId === 4) {
            $html = view('app/classroom/student/_year_classrooms', array_merge($shared, [
                'classrooms' => $this->getStudentClassroomsForYear($userId, $year),
            ]));
        } elseif ($roleCatId === 3) {
            $html = view('app/classroom/teacher/_year_classrooms', array_merge($shared, [
                'classrooms' => $this->getTeacherClassroomsForYear($userId, $year),
                'userId'     => $userId,
            ]));
        } else {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        return $this->response->setJSON(['html' => $html]);
    }

    private function getTeacherSubjectsForClassroom(int $classId, int $userId, bool $anyStatus = false): array
    {
        $db = \Config\Database::connect();
        $statusClause = $anyStatus ? '' : "AND cst.class_sub_teacher_status = 'Active'";
        return $db->query("
            SELECT
                cs.class_sub_id,
                ss.sch_sub_id,
                sub.subject_name, sub.sub_image,
                d.dept_name,
                cst.class_sub_teacher_status,
                (SELECT COUNT(*) FROM classroom_student cstu
                 WHERE cstu.class_id_fk = cs.class_id_fk
                ) AS student_count
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject cs  ON cs.class_sub_id  = cst.class_sub_id_fk
            INNER JOIN sch_subject ss        ON ss.sch_sub_id    = cs.sub_id_fk
            INNER JOIN subject sub           ON sub.subject_id   = ss.subject_id_fk
            LEFT  JOIN sch_department sd     ON sd.sch_dept_id   = ss.sch_dept_id_fk
            LEFT  JOIN department d          ON d.dept_id        = sd.dept_id_fk
            WHERE cs.class_id_fk = ? AND cst.user_id_fk = ? {$statusClause}
            ORDER BY sub.subject_name ASC
        ", [$classId, $userId])->getResultArray();
    }

    private function getTeacherSubjects(int $userId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT DISTINCT
                ss.sch_sub_id,
                sub.subject_name,
                sub.sub_image,
                l.level_name,
                d.dept_name
            FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject csub ON csub.class_sub_id  = cst.class_sub_id_fk
            INNER JOIN sch_subject ss         ON ss.sch_sub_id      = csub.sub_id_fk
            INNER JOIN subject sub            ON sub.subject_id     = ss.subject_id_fk
            LEFT  JOIN level l                ON l.level_id         = sub.level_id_fk
            LEFT  JOIN sch_department sd      ON sd.sch_dept_id     = ss.sch_dept_id_fk
            LEFT  JOIN department d           ON d.dept_id          = sd.dept_id_fk
            WHERE cst.user_id_fk = ? AND cst.class_sub_teacher_status = 'Active'
            ORDER BY sub.subject_name ASC
        ", [$userId])->getResultArray();
    }

    private function getMyEnrolledClassrooms(int $userId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT
                c.class_id, c.class_name, c.class_year, c.class_status,
                s.stream_id, s.stream_name,
                l.level_name,
                sch.sch_id, sch.sch_name, sch.sch_logo, sch.sch_address, sch.sch_phone, sch.sch_email,
                e.enrol_status, e.enrol_year,
                (SELECT COUNT(*) FROM classroom_student cs3
                 WHERE cs3.class_id_fk = c.class_id AND cs3.class_stud_status = 'Active'
                ) AS student_count,
                (SELECT u2.user_id
                 FROM classroom_role cs2 INNER JOIN users u2 ON u2.user_id = cs2.user_id_fk
                 WHERE cs2.class_id_fk = c.class_id AND cs2.cs_role = 'Class Teacher' AND cs2.cs_status = 'Active' LIMIT 1
                ) AS class_teacher_id,
                (SELECT u2.profile_photo
                 FROM classroom_role cs2 INNER JOIN users u2 ON u2.user_id = cs2.user_id_fk
                 WHERE cs2.class_id_fk = c.class_id AND cs2.cs_role = 'Class Teacher' AND cs2.cs_status = 'Active' LIMIT 1
                ) AS class_teacher_photo,
                (SELECT u4.user_id
                 FROM classroom_role cs4 INNER JOIN users u4 ON u4.user_id = cs4.user_id_fk
                 WHERE cs4.class_id_fk = c.class_id AND cs4.cs_role = 'Class Captain' AND cs4.cs_status = 'Active' LIMIT 1
                ) AS class_captain_id,
                (SELECT u4.profile_photo
                 FROM classroom_role cs4 INNER JOIN users u4 ON u4.user_id = cs4.user_id_fk
                 WHERE cs4.class_id_fk = c.class_id AND cs4.cs_role = 'Class Captain' AND cs4.cs_status = 'Active' LIMIT 1
                ) AS class_captain_photo,
                (SELECT COUNT(*) FROM enrolment e2
                 WHERE e2.stream_id_fk = c.stream_id_fk AND e2.enrol_year = c.class_year
                ) AS student_count,
                (SELECT CONCAT(u2.fname, ' ', u2.lname)
                 FROM classroom_role cs2
                 INNER JOIN users u2 ON u2.user_id = cs2.user_id_fk
                 WHERE cs2.class_id_fk = c.class_id
                   AND cs2.cs_role = 'Class Teacher' AND cs2.cs_status = 'Active'
                 LIMIT 1
                ) AS class_teacher,
                (SELECT CONCAT(u3.fname, ' ', u3.lname)
                 FROM classroom_role cs3
                 INNER JOIN users u3 ON u3.user_id = cs3.user_id_fk
                 WHERE cs3.class_id_fk = c.class_id
                   AND cs3.cs_role = 'Class Captain' AND cs3.cs_status = 'Active'
                 LIMIT 1
                ) AS class_captain
            FROM enrolment e
            INNER JOIN admission a  ON a.admission_id  = e.admission_id_fk
            INNER JOIN classroom c  ON c.stream_id_fk  = e.stream_id_fk AND c.class_year = e.enrol_year
            INNER JOIN stream s     ON s.stream_id      = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id  = s.sch_level_id_fk
            INNER JOIN level l      ON l.level_id        = sl.level_id_fk
            INNER JOIN school sch   ON sch.sch_id        = a.sch_id_fk
            WHERE a.user_id_fk = ?
            ORDER BY e.enrol_year DESC, c.class_name
        ", [$userId])->getResultArray();
    }

    // ================================================================
    // PRIVATE: subjects for a classroom (with teacher + student count)
    // ================================================================

    private function getClassroomSubjects(int $classId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT
                cs.class_sub_id,
                sub.subject_id, sub.subject_name, sub.sub_image,
                d.dept_name,
                (SELECT u.user_id
                 FROM classroom_subject_teacher cst2
                 INNER JOIN users u ON u.user_id = cst2.user_id_fk
                 WHERE cst2.class_sub_id_fk = cs.class_sub_id AND cst2.class_sub_teacher_status = 'Active'
                 LIMIT 1) AS teacher_id,
                (SELECT CONCAT(u.fname, ' ', u.lname)
                 FROM classroom_subject_teacher cst2
                 INNER JOIN users u ON u.user_id = cst2.user_id_fk
                 WHERE cst2.class_sub_id_fk = cs.class_sub_id AND cst2.class_sub_teacher_status = 'Active'
                 LIMIT 1) AS teacher_name,
                (SELECT u.profile_photo
                 FROM classroom_subject_teacher cst2
                 INNER JOIN users u ON u.user_id = cst2.user_id_fk
                 WHERE cst2.class_sub_id_fk = cs.class_sub_id AND cst2.class_sub_teacher_status = 'Active'
                 LIMIT 1) AS teacher_photo,
                (SELECT COUNT(*)
                 FROM classroom_student cstu
                 WHERE cstu.class_id_fk = cs.class_id_fk AND cstu.class_stud_status = 'Active'
                ) AS student_count
            FROM classroom_subject cs
            INNER JOIN sch_subject ss   ON ss.sch_sub_id  = cs.sub_id_fk
            INNER JOIN subject sub      ON sub.subject_id = ss.subject_id_fk
            LEFT  JOIN sch_department sd ON sd.sch_dept_id = ss.sch_dept_id_fk
            LEFT  JOIN department d      ON d.dept_id      = sd.dept_id_fk
            WHERE cs.class_id_fk = ?
            ORDER BY sub.subject_name ASC
        ", [$classId])->getResultArray();
    }

    private function getClassroomAllStudents(int $classId, bool $anyStatus = false): array
    {
        $db = \Config\Database::connect();
        $where = $anyStatus ? '' : "AND cs.class_stud_status = 'Active'";
        return $db->query("
            SELECT
                u.user_id, u.fname, u.lname, u.gender, u.profile_photo, u.email, u.username
            FROM classroom_student cs
            INNER JOIN users u ON u.user_id = cs.user_id_fk
            WHERE cs.class_id_fk = ? {$where}
            ORDER BY u.lname ASC, u.fname ASC
        ", [$classId])->getResultArray();
    }

    // ================================================================
    // PRIVATE: classrooms for all children of a parent user
    // ================================================================

    private function getChildrensClassrooms(int $parentId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT
                c.class_id, c.class_name, c.class_year, c.class_status,
                s.stream_id, s.stream_name,
                l.level_name,
                sch.sch_id, sch.sch_name, sch.sch_logo,
                e.enrol_status, e.enrol_year,
                stu.user_id AS student_id,
                stu.fname   AS student_fname,
                stu.lname   AS student_lname,
                stu.profile_photo AS student_photo,
                ps.relationship,
                (SELECT COUNT(*) FROM enrolment e2
                 WHERE e2.stream_id_fk = c.stream_id_fk AND e2.enrol_year = c.class_year
                ) AS student_count,
                (SELECT CONCAT(u2.fname, ' ', u2.lname)
                 FROM classroom_role cs2
                 INNER JOIN users u2 ON u2.user_id = cs2.user_id_fk
                 WHERE cs2.class_id_fk = c.class_id
                   AND cs2.cs_role = 'Class Teacher' AND cs2.cs_status = 'Active'
                 LIMIT 1
                ) AS class_teacher,
                (SELECT CONCAT(u3.fname, ' ', u3.lname)
                 FROM classroom_role cs3
                 INNER JOIN users u3 ON u3.user_id = cs3.user_id_fk
                 WHERE cs3.class_id_fk = c.class_id
                   AND cs3.cs_role = 'Class Captain' AND cs3.cs_status = 'Active'
                 LIMIT 1
                ) AS class_captain
            FROM parent_student ps
            INNER JOIN users stu ON stu.user_id = ps.student_user_id_fk
            INNER JOIN admission a  ON a.user_id_fk = stu.user_id AND a.admission_status = 'Active'
            INNER JOIN enrolment e  ON e.admission_id_fk = a.admission_id
            INNER JOIN classroom c  ON c.stream_id_fk    = e.stream_id_fk AND c.class_year = e.enrol_year
            INNER JOIN stream s     ON s.stream_id        = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id    = s.sch_level_id_fk
            INNER JOIN level l      ON l.level_id          = sl.level_id_fk
            INNER JOIN school sch   ON sch.sch_id          = a.sch_id_fk
            WHERE ps.parent_user_id_fk = ?
            ORDER BY stu.fname, stu.lname, e.enrol_year DESC
        ", [$parentId])->getResultArray();
    }

    // ================================================================
    // UPDATE STAFF STATUS
    // ================================================================

    public function updateStaffStatus(int $csId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $record = $this->classroomStaffModel->find($csId);
            if (!$record) {
                return $this->response->setJSON(['success' => false, 'message' => 'Record not found.']);
            }

            $newStatus = $record['cs_status'] === 'Active' ? 'Inactive' : 'Active';
            $this->classroomStaffModel->update($csId, ['cs_status' => $newStatus]);

            $this->userLogModel->insert([
                'user_id_fk'  => (int) $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Staff Status Updated',
                'log_desc'    => $record['cs_role'] . ' (ID: ' . $csId . ') set to ' . $newStatus,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-switch"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => $newStatus === 'Active' ? 'success' : 'warning',
            ]);

            return $this->response->setJSON([
                'success'    => true,
                'message'    => 'Status updated to ' . $newStatus . '.',
                'new_status' => $newStatus,
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ClassroomController::updateStaffStatus] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // SUBJECT DISCUSSION
    // ================================================================

    // ================================================================
    // CLASS DISCUSSION
    // ================================================================

    private function userCanPostDiscussion(int $classId, int $userId): bool
    {
        $db = \Config\Database::connect();

        // Active student enrollment
        if ($db->table('classroom_student')
            ->where('class_id_fk', $classId)->where('user_id_fk', $userId)
            ->where('class_stud_status', 'Active')->countAllResults() > 0) {
            return true;
        }

        // Classroom role (class teacher, etc.)
        if ($db->table('classroom_role')
            ->where('class_id_fk', $classId)->where('user_id_fk', $userId)
            ->where('cs_status', 'Active')->countAllResults() > 0) {
            return true;
        }

        // Subject teacher in this class
        if ($db->query("
            SELECT COUNT(*) AS c FROM classroom_subject_teacher cst
            INNER JOIN classroom_subject cs ON cs.class_sub_id = cst.class_sub_id_fk
            WHERE cs.class_id_fk = ? AND cst.user_id_fk = ? AND cst.class_sub_teacher_status = 'Active'
        ", [$classId, $userId])->getRowArray()['c'] > 0) {
            return true;
        }

        // Super admin
        if ((int) $this->session->get('roleID') === 1) {
            return true;
        }

        return false;
    }

    public function classDiscussionPost(int $classId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = (int) $this->session->get('userID');
        if (!$this->userCanPostDiscussion($classId, $userId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $db        = \Config\Database::connect();
        $classroom = $db->table('classroom')->select('class_status')->where('class_id', $classId)->get()->getRowArray();
        if (!$classroom || $classroom['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active. No new posts allowed.']);
        }

        $message   = trim($this->request->getPost('message') ?? '');
        $files     = $this->request->getFileMultiple('photos');
        $hasPhotos = !empty($files) && $files[0] instanceof \CodeIgniter\HTTP\Files\UploadedFile && $files[0]->isValid();

        if ($message === '' && !$hasPhotos) {
            return $this->response->setJSON(['success' => false, 'message' => 'Post must have a message or at least one photo.']);
        }

        $uploadPath = FCPATH . 'uploads/class_discussion/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $allowedImg = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $photos     = [];

        if ($hasPhotos) {
            $validFiles = array_filter($files, fn($f) => $f instanceof \CodeIgniter\HTTP\Files\UploadedFile && $f->isValid() && !$f->hasMoved());
            if (count($validFiles) > 10) {
                return $this->response->setJSON(['success' => false, 'message' => 'Maximum 10 photos allowed.']);
            }
            foreach ($validFiles as $file) {
                $ext = strtolower($file->getClientExtension());
                if (!in_array($ext, $allowedImg)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Only image files allowed (jpg, jpeg, png, gif, webp).']);
                }
                $newName  = 'cdp_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
                $file->move($uploadPath, $newName);
                $photos[] = $newName;
            }
        }

        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->table('class_discussion')->insert([
            'class_id_fk' => $classId,
            'author'      => $userId,
            'message'     => $message ?: null,
            'created_at'  => $now,
            'post_status' => 1,
        ]);
        $cdId = $db->insertID();

        foreach ($photos as $i => $path) {
            $db->table('class_discussion_photo')->insert([
                'cd_id_fk'    => $cdId,
                'photo_path'  => $path,
                'photo_order' => $i,
            ]);
        }

        $post = $db->query("
            SELECT cd.cd_id, cd.message, cd.created_at, cd.author AS author_id,
                   CONCAT(u.fname,' ',u.lname) AS author_name, u.profile_photo AS author_photo
            FROM class_discussion cd INNER JOIN users u ON u.user_id = cd.author
            WHERE cd.cd_id = ?
        ", [$cdId])->getRowArray();

        $post['photos']        = $this->classDiscussionModel->getPhotos($cdId);
        $post['comments']      = [];
        $post['comment_count'] = 0;
        $post['like_count']    = 0;
        $post['dislike_count'] = 0;
        $post['user_reaction'] = null;

        return $this->response->setJSON(['success' => true, 'post' => $post]);
    }

    public function classDiscussionLike(int $postId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $type   = $this->request->getPost('type') === 'dislike' ? 'dislike' : 'like';
        $userId = (int) $this->session->get('userID');
        $result = $this->classDiscussionModel->togglePostLike($postId, $userId, $type);
        return $this->response->setJSON(['success' => true] + $result);
    }

    public function classDiscussionDelete(int $postId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();
        $post   = $db->table('class_discussion')->where('cd_id', $postId)->get()->getRowArray();
        if (!$post || (int) $post['author'] !== $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not allowed.']);
        }
        $classroom = $db->table('classroom')->select('class_status')->where('class_id', $post['class_id_fk'])->get()->getRowArray();
        if (!$classroom || $classroom['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active.']);
        }
        $db->table('class_discussion')->where('cd_id', $postId)->update(['post_status' => 0]);
        return $this->response->setJSON(['success' => true]);
    }

    public function classDiscussionComment(int $postId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $comment = trim($this->request->getPost('comment') ?? '');
        if ($comment === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment cannot be empty.']);
        }
        $userId = (int) $this->session->get('userID');
        $now    = date('Y-m-d H:i:s');
        $db     = \Config\Database::connect();

        $post = $db->table('class_discussion')->where('cd_id', $postId)->where('post_status', 1)->get()->getRowArray();
        if (!$post) {
            return $this->response->setJSON(['success' => false, 'message' => 'Post not found.']);
        }
        $classroom = $db->table('classroom')->select('class_status')->where('class_id', $post['class_id_fk'])->get()->getRowArray();
        if (!$classroom || $classroom['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active.']);
        }

        $db->table('class_discussion_comment')->insert([
            'cd_id_fk'       => $postId,
            'author'         => $userId,
            'comment'        => $comment,
            'created_at'     => $now,
            'comment_status' => 'Active',
        ]);
        $cdcId = $db->insertID();

        $row = $db->query("
            SELECT cdc.cdc_id, cdc.comment, cdc.created_at, cdc.author AS author_id,
                   CONCAT(u.fname,' ',u.lname) AS author_name, u.profile_photo AS author_photo
            FROM class_discussion_comment cdc INNER JOIN users u ON u.user_id = cdc.author
            WHERE cdc.cdc_id = ?
        ", [$cdcId])->getRowArray();

        $row['like_count']    = 0;
        $row['dislike_count'] = 0;
        $row['user_reaction'] = null;
        $row['replies']       = [];

        return $this->response->setJSON(['success' => true, 'comment' => $row]);
    }

    public function classDiscussionCommentLike(int $commentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $type   = $this->request->getPost('type') === 'dislike' ? 'dislike' : 'like';
        $userId = (int) $this->session->get('userID');
        $result = $this->classDiscussionModel->toggleCommentLike($commentId, $userId, $type);
        return $this->response->setJSON(['success' => true] + $result);
    }

    public function classDiscussionCommentDelete(int $commentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $userId  = (int) $this->session->get('userID');
        $db      = \Config\Database::connect();
        $comment = $db->table('class_discussion_comment')->where('cdc_id', $commentId)->get()->getRowArray();
        if (!$comment || (int) $comment['author'] !== $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not allowed.']);
        }
        $classStatus = $db->query("
            SELECT c.class_status FROM class_discussion_comment cdc
            INNER JOIN class_discussion cd ON cd.cd_id = cdc.cd_id_fk
            INNER JOIN classroom c ON c.class_id = cd.class_id_fk
            WHERE cdc.cdc_id = ?
        ", [$commentId])->getRow();
        if (!$classStatus || $classStatus->class_status !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active.']);
        }
        $db->table('class_discussion_comment')->where('cdc_id', $commentId)->update(['comment_status' => 'Deleted']);
        return $this->response->setJSON(['success' => true]);
    }

    public function classDiscussionCommentReply(int $commentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $reply = trim($this->request->getPost('reply') ?? '');
        if ($reply === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Reply cannot be empty.']);
        }
        $userId = (int) $this->session->get('userID');
        $now    = date('Y-m-d H:i:s');
        $db     = \Config\Database::connect();

        $comment = $db->table('class_discussion_comment')->where('cdc_id', $commentId)->where('comment_status', 'Active')->get()->getRowArray();
        if (!$comment) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment not found.']);
        }
        $classStatus = $db->query("
            SELECT c.class_status FROM class_discussion_comment cdc
            INNER JOIN class_discussion cd ON cd.cd_id = cdc.cd_id_fk
            INNER JOIN classroom c ON c.class_id = cd.class_id_fk
            WHERE cdc.cdc_id = ?
        ", [$commentId])->getRow();
        if (!$classStatus || $classStatus->class_status !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active.']);
        }

        $db->table('class_discussion_comment_reply')->insert([
            'cdc_id_fk'    => $commentId,
            'author'       => $userId,
            'reply'        => $reply,
            'created_at'   => $now,
            'reply_status' => 'Active',
        ]);
        $replyId = $db->insertID();

        $row = $db->query("
            SELECT r.cdcr_id, r.reply, r.created_at, r.author AS author_id,
                   CONCAT(u.fname,' ',u.lname) AS author_name, u.profile_photo AS author_photo
            FROM class_discussion_comment_reply r INNER JOIN users u ON u.user_id = r.author
            WHERE r.cdcr_id = ?
        ", [$replyId])->getRowArray();

        $row['like_count']    = 0;
        $row['dislike_count'] = 0;
        $row['user_reaction'] = null;

        return $this->response->setJSON(['success' => true, 'reply' => $row]);
    }

    public function classDiscussionCommentReplyLike(int $replyId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $type   = $this->request->getPost('type') === 'dislike' ? 'dislike' : 'like';
        $userId = (int) $this->session->get('userID');
        $result = $this->classDiscussionModel->toggleReplyLike($replyId, $userId, $type);
        return $this->response->setJSON(['success' => true] + $result);
    }

    public function classDiscussionCommentReplyDelete(int $replyId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();
        $reply  = $db->table('class_discussion_comment_reply')->where('cdcr_id', $replyId)->get()->getRowArray();
        if (!$reply || (int) $reply['author'] !== $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not allowed.']);
        }
        $classStatus = $db->query("
            SELECT c.class_status FROM class_discussion_comment_reply r
            INNER JOIN class_discussion_comment cdc ON cdc.cdc_id = r.cdc_id_fk
            INNER JOIN class_discussion cd ON cd.cd_id = cdc.cd_id_fk
            INNER JOIN classroom c ON c.class_id = cd.class_id_fk
            WHERE r.cdcr_id = ?
        ", [$replyId])->getRow();
        if (!$classStatus || $classStatus->class_status !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active.']);
        }
        $db->table('class_discussion_comment_reply')->where('cdcr_id', $replyId)->update(['reply_status' => 'Deleted']);
        return $this->response->setJSON(['success' => true]);
    }

    public function classDiscussionReactions(int $postId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        return $this->response->setJSON([
            'success'   => true,
            'reactions' => $this->classDiscussionModel->getPostReactions($postId),
        ]);
    }

    public function classDiscussionCommentReactions(int $commentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        return $this->response->setJSON([
            'success'   => true,
            'reactions' => $this->classDiscussionModel->getCommentReactions($commentId),
        ]);
    }

    public function classDiscussionReplyReactions(int $replyId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        return $this->response->setJSON([
            'success'   => true,
            'reactions' => $this->classDiscussionModel->getReplyReactions($replyId),
        ]);
    }

    // ================================================================
    // TERM EXAM — SUBJECT TEACHER
    // ================================================================

    public function saveExamMark(int $schSubId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        $userId = (int) $this->session->get('userID');
        $assign = $this->resolveTeacherClassSub($schSubId, $userId, true);
        if (!$assign) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $db = \Config\Database::connect();
        $classStatus = $db->table('classroom')->select('class_status')->where('class_id', $assign['class_id'])->get()->getRowArray();
        if (!$classStatus || $classStatus['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active. Marks cannot be saved.']);
        }

        $classSubId = (int) $assign['class_sub_id'];
        $classId    = (int) $assign['class_id'];
        $studentId  = (int) $this->request->getPost('student_id');
        $term       = (int) $this->request->getPost('term');
        $markVal    = $this->request->getPost('mark');
        $total      = (float) ($this->request->getPost('total_mark') ?? 100);
        $comment    = trim($this->request->getPost('teacher_comment') ?? '');
        $isAbsent   = (int) ($this->request->getPost('is_absent') ?? 0);

        if (!$studentId || $term < 1 || $term > 3) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data.']);
        }

        // Block editing once class teacher has submitted
        $status = $this->termExamModel->getReportStatus($classId, $term);
        if (in_array($status['status'], ['ct_submitted', 'published'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Marks are locked — report has been submitted.']);
        }

        $mark = $isAbsent ? null : (($markVal !== null && $markVal !== '') ? (float) $markVal : null);
        if (!$isAbsent && $mark !== null && ($mark < 0 || $mark > $total)) {
            return $this->response->setJSON(['success' => false, 'message' => "Mark must be between 0 and {$total}."]);
        }

        $this->termExamModel->saveExamMark($classSubId, $classId, $studentId, $term, $mark, $total, $comment, $userId, $isAbsent);
        $pct   = (!$isAbsent && $mark !== null && $total > 0) ? round(($mark / $total) * 100, 1) : null;
        $grade = $isAbsent ? 'ABS' : ($pct !== null ? \App\Models\TermExamModel::grade($pct) : '—');
        return $this->response->setJSON(['success' => true, 'mark' => $mark, 'total' => $total, 'pct' => $pct, 'grade' => $grade, 'is_absent' => $isAbsent]);
    }

    // ================================================================
    // TERM EXAM — CLASS TEACHER REVIEW
    // ================================================================

    public function classTeacherExamReview(int $classId, int $term = 1)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        // Must be Class Teacher for this class
        $isClassTeacher = $db->table('classroom_role')
            ->where('class_id_fk', $classId)->where('user_id_fk', $userId)
            ->where('cs_role', 'Class Teacher')->where('cs_status', 'Active')
            ->get()->getRowArray();
        if (!$isClassTeacher) {
            return redirect()->to('classroom/my')->with('error', 'Access denied — Class Teacher only.');
        }

        $term = max(1, min(3, $term));

        $classroom = $db->query("
            SELECT c.class_id, c.class_name, c.class_year, c.class_status, s.stream_id, s.stream_name, sch.sch_name, sch.sch_logo
            FROM classroom c INNER JOIN stream s ON s.stream_id = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
            INNER JOIN school sch ON sch.sch_id = sl.sch_id_fk
            WHERE c.class_id = ?
        ", [$classId])->getRowArray();

        if (!$classroom) return redirect()->to('classroom/my')->with('error', 'Classroom not found.');

        $this->setPageData('Class Exam Review', 'Classroom', 'Exam Review');
        $result = $this->termExamModel->getAllMarksForClassTerm($classId, $term);

        // Core-subject completeness check (temporarily bypassed — always allow submit)
        $classRoll         = count($result['students']);
        $coreSubjectStatus = [];
        $canSubmit         = true;

        $data['classroom']         = $classroom;
        $data['classId']           = $classId;
        $data['term']              = $term;
        $data['students']          = $result['students'];
        $data['subjects']          = $result['subjects'];
        $data['reportStatus']      = $this->termExamModel->getReportStatus($classId, $term);
        $data['canSubmit']         = $canSubmit;
        $data['coreSubjectStatus'] = $coreSubjectStatus;
        $data['isActive']          = ($classroom['class_status'] ?? '') === 'Active';
        $data['_view']             = 'app/classroom/teacher/class_exam_review';
        return view('app/layouts/main', $data);
    }

    public function saveCtComment(int $classId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        $isClassTeacher = $db->table('classroom_role')
            ->where('class_id_fk', $classId)->where('user_id_fk', $userId)
            ->where('cs_role', 'Class Teacher')->where('cs_status', 'Active')
            ->get()->getRowArray();
        if (!$isClassTeacher) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $classStatus = $db->table('classroom')->select('class_status')->where('class_id', $classId)->get()->getRowArray();
        if (!$classStatus || $classStatus['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active. Comments cannot be saved.']);
        }

        $studentId = (int) $this->request->getPost('student_id');
        $term      = (int) $this->request->getPost('term');
        $comment   = trim($this->request->getPost('comment') ?? '');

        if (!$studentId || $term < 1 || $term > 3 || $comment === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Student, term, and comment are required.']);
        }

        $status = $this->termExamModel->getReportStatus($classId, $term);
        if (in_array($status['status'], ['ct_submitted', 'published'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Report already submitted.']);
        }

        $this->termExamModel->saveCtComment($classId, $studentId, $term, $comment, $userId);
        return $this->response->setJSON(['success' => true]);
    }

    public function classTeacherSubmit(int $classId, int $term)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        $isClassTeacher = $db->table('classroom_role')
            ->where('class_id_fk', $classId)->where('user_id_fk', $userId)
            ->where('cs_role', 'Class Teacher')->where('cs_status', 'Active')
            ->get()->getRowArray();
        if (!$isClassTeacher) return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);

        $classStatus = $db->table('classroom')->select('class_status')->where('class_id', $classId)->get()->getRowArray();
        if (!$classStatus || $classStatus['class_status'] !== 'Active') {
            return $this->response->setJSON(['success' => false, 'message' => 'This classroom is no longer active.']);
        }

        $this->termExamModel->submitToPrincipal($classId, $term, $userId);
        return $this->response->setJSON(['success' => true, 'message' => 'Term ' . $term . ' report submitted to Principal.']);
    }

    // ================================================================
    // TERM EXAM — PRINCIPAL REVIEW
    // ================================================================

    public function principalExamReview(int $classId, int $term = 1)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $roleName     = strtolower(trim($this->session->get('roleName') ?? ''));
        if (!$isSuperAdmin && $roleName !== 'principal' && $this->require_access('_exam_publish') !== true) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $term = max(1, min(3, $term));
        $db   = \Config\Database::connect();

        $classroom = $db->query("
            SELECT c.class_id, c.class_name, c.class_year, s.stream_name, sch.sch_name, sch.sch_logo
            FROM classroom c INNER JOIN stream s ON s.stream_id = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
            INNER JOIN school sch ON sch.sch_id = sl.sch_id_fk
            WHERE c.class_id = ?
        ", [$classId])->getRowArray();

        if (!$classroom) return redirect()->to('classroom')->with('error', 'Classroom not found.');

        $this->setPageData('Principal Exam Review', 'Classroom', 'Review');
        $result = $this->termExamModel->getAllMarksForClassTerm($classId, $term);
        $data['classroom']    = $classroom;
        $data['classId']      = $classId;
        $data['term']         = $term;
        $data['students']     = $result['students'];
        $data['subjects']     = $result['subjects'];
        $data['reportStatus'] = $this->termExamModel->getReportStatus($classId, $term);
        $data['_view']        = 'app/classroom/principal/exam_review';
        return view('app/layouts/main', $data);
    }

    public function savePrincipalComment(int $classId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $roleName     = strtolower(trim($this->session->get('roleName') ?? ''));
        if (!$isSuperAdmin && $roleName !== 'principal' && $this->require_access('_exam_publish') !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $userId    = (int) $this->session->get('userID');
        $studentId = (int) $this->request->getPost('student_id');
        $term      = (int) $this->request->getPost('term');
        $comment   = trim($this->request->getPost('comment') ?? '');

        if (!$studentId || $term < 1 || $term > 3 || $comment === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields required.']);
        }

        $status = $this->termExamModel->getReportStatus($classId, $term);
        if ($status['status'] === 'published') {
            return $this->response->setJSON(['success' => false, 'message' => 'Report already published.']);
        }

        $this->termExamModel->savePrincipalComment($classId, $studentId, $term, $comment, $userId);
        return $this->response->setJSON(['success' => true]);
    }

    public function publishReport(int $classId, int $term)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $roleName     = strtolower(trim($this->session->get('roleName') ?? ''));
        if (!$isSuperAdmin && $roleName !== 'principal' && $this->require_access('_exam_publish') !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $this->termExamModel->publishReport($classId, $term, (int) $this->session->get('userID'));
        return $this->response->setJSON(['success' => true, 'message' => 'Term ' . $term . ' report published successfully.']);
    }

    // ================================================================
    // TERM EXAM — REPORT CARD VIEW / PRINT
    // ================================================================

    public function reportCard(int $classId, int $studentId, int $term)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        $status = $this->termExamModel->getReportStatus($classId, $term);
        $roleCatId    = (int) $this->session->get('roleCatID');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        // Access: published → anyone; not published → teacher/admin only
        if ($status['status'] !== 'published') {
            $isTeacher = $roleCatId === 3;
            $roleName  = strtolower(trim($this->session->get('roleName') ?? ''));
            $isAdmin   = $isSuperAdmin || $roleName === 'principal' || $this->require_access('_exam_publish') === true;
            if (!$isTeacher && !$isAdmin) {
                return redirect()->to('classroom/my')->with('error', 'Report not yet published.');
            }
        }

        $classroom = $db->query("
            SELECT c.class_id, c.class_name, c.class_year, s.stream_name,
                   sch.sch_name, sch.sch_logo, sch.sch_address, sch.sch_phone, sch.sch_email
            FROM classroom c INNER JOIN stream s ON s.stream_id = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
            INNER JOIN school sch ON sch.sch_id = sl.sch_id_fk
            WHERE c.class_id = ?
        ", [$classId])->getRowArray();

        $student = $db->query("
            SELECT u.user_id, u.fname, u.lname, u.profile_photo, u.gender, u.dob
            FROM users u WHERE u.user_id = ?
        ", [$studentId])->getRowArray();

        if (!$classroom || !$student) {
            return redirect()->to('classroom/my')->with('error', 'Record not found.');
        }

        $report = $this->termExamModel->getStudentReport($classId, $studentId, $term);
        $stats  = $this->termExamModel->getClassStats($classId, $term, $studentId);

        $this->setPageData('Report Card', 'Classroom', 'Report');
        $data['classroom'] = $classroom;
        $data['student']   = $student;
        $data['term']      = $term;
        $data['report']    = $report;
        $data['stats']     = $stats;
        $data['status']    = $status;
        $data['_view']     = 'app/classroom/report_card';
        return view('app/layouts/main', $data);
    }


    // ================================================================
    // REPORT CARD PDF
    // ================================================================

    public function reportCardPdf(int $classId, int $studentId, int $term)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $db = \Config\Database::connect();

        $status       = $this->termExamModel->getReportStatus($classId, $term);
        $roleCatId    = (int) $this->session->get('roleCatID');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        if ($status['status'] !== 'published') {
            $isTeacher = $roleCatId === 3;
            $isAdmin   = $isSuperAdmin || $this->require_access('_exam_publish') === true;
            if (!$isTeacher && !$isAdmin) {
                return redirect()->to('classroom/my')->with('error', 'Report not yet published.');
            }
        }

        $classroom = $db->query("
            SELECT c.class_id, c.class_name, c.class_year, s.stream_name,
                   sch.sch_name, sch.sch_logo, sch.sch_address, sch.sch_phone, sch.sch_email
            FROM classroom c INNER JOIN stream s ON s.stream_id = c.stream_id_fk
            INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
            INNER JOIN school sch ON sch.sch_id = sl.sch_id_fk
            WHERE c.class_id = ?
        ", [$classId])->getRowArray();

        $student = $db->query("
            SELECT u.user_id, u.fname, u.lname, u.profile_photo, u.gender, u.dob
            FROM users u WHERE u.user_id = ?
        ", [$studentId])->getRowArray();

        if (!$classroom || !$student) {
            return redirect()->to('classroom/my')->with('error', 'Record not found.');
        }

        $rep   = $this->termExamModel->getStudentReport($classId, $studentId, $term);
        $stats = $this->termExamModel->getClassStats($classId, $term, $studentId);

        $marks  = $rep['marks']           ?? [];
        $totalE = (float)($rep['total_earned']   ?? 0);
        $totalP = (float)($rep['total_possible'] ?? 0);
        $ovPct  = $rep['overall_pct']     ?? null;
        $grade  = $ovPct !== null ? \App\Models\TermExamModel::grade((float)$ovPct) : null;

        $gradeRgb = static function(string $g): array {
            return match(true) {
                str_starts_with($g, 'A') => [21, 128, 61],
                $g === 'B'               => [29, 78, 216],
                $g === 'C'               => [7, 89, 133],
                default                  => [185, 28, 28],
            };
        };

        // ── PDF setup ─────────────────────────────────────────────────────
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

        // Suppress libpng iCCP profile warnings — TCPDF captures GD output with
        // ob_start() and treats any output as a fatal error, so we must prevent
        // the warning from reaching the output buffer at all.
        set_error_handler(static function(int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP')
                || str_contains($errstr, 'gd-png')
                || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetAuthor('Navuli Fiji School Management System');
        $pdf->SetTitle('Report Card - ' . $student['fname'] . ' ' . $student['lname'] . ' - Term ' . $term);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();

        $sx = 12;   // startX
        $cw = 186;  // contentWidth
        $y  = 12.0;

        // ── Double border frame ───────────────────────────────────────────
        $pdf->SetLineStyle(['width' => 1.0, 'color' => [26, 86, 219]]);
        $pdf->Rect(8, 8, 194, 281, 'D');
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Rect(10, 10, 190, 277, 'D');

        // ── Header ───────────────────────────────────────────────────────
        $logoPath = FCPATH . 'uploads/school/logo/' . ($classroom['sch_logo'] ?? '');
        if (!empty($classroom['sch_logo']) && file_exists($logoPath)) {
            $pdf->Image($logoPath, $sx, $y, 22, 22, '', '', 'T', false, 300);
        }
        $navuliLogo = FCPATH . 'icon.png';
        if (file_exists($navuliLogo)) {
            $pdf->Image($navuliLogo, $sx + $cw - 22, $y, 20, 20, '', '', 'T', false, 300);
        }

        $centerX = $sx + 24;
        $centerW = $cw - 46;

        $pdf->SetXY($centerX, $y + 1);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($centerW, 6, strtoupper($classroom['sch_name']), 0, 1, 'C');

        $pdf->SetXY($centerX, $y + 8);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell($centerW, 4, 'TERM ' . $term . ' EXAMINATION REPORT CARD', 0, 1, 'C');

        $pdf->SetXY($centerX, $y + 13);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($centerW, 4, $classroom['class_name'] . ' - ' . $classroom['class_year'], 0, 1, 'C');

        $contactParts = array_filter([
            $classroom['sch_address'] ?? '',
            !empty($classroom['sch_phone']) ? 'Ph: ' . $classroom['sch_phone'] : '',
            $classroom['sch_email'] ?? '',
        ]);
        if (!empty($contactParts)) {
            $pdf->SetXY($centerX, $y + 18);
            $pdf->SetFont('helvetica', '', 6.5);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->Cell($centerW, 4, implode('  |  ', $contactParts), 0, 1, 'C');
        }

        $y += 30;

        // Double divider
        $pdf->SetLineStyle(['width' => 0.7, 'color' => [26, 86, 219]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [147, 197, 253]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 6;

        // ── Student info ─────────────────────────────────────────────────
        if (!empty($student['profile_photo'])) {
            $photoPath = FCPATH . 'uploads/profilePhoto/' . $student['profile_photo'];
            if (file_exists($photoPath)) {
                $pdf->Image($photoPath, $sx, $y, 22, 26, '', '', 'T', false, 300);
            }
        } else {
            $pdf->SetFillColor(219, 234, 254);
            $pdf->SetDrawColor(219, 234, 254);
            $pdf->RoundedRect($sx, $y, 22, 26, 2, '1111', 'DF');
            $pdf->SetXY($sx, $y + 8);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(29, 78, 216);
            $pdf->Cell(22, 10, strtoupper(substr($student['fname'], 0, 1) . substr($student['lname'], 0, 1)), 0, 0, 'C');
        }

        $infoX      = $sx + 25;
        $infoLabelW = 26;
        $infoValueW = 70;

        $pdf->SetXY($infoX, $y + 1);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($infoLabelW, 5, 'Full Name', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->Cell($infoValueW, 5, $student['fname'] . ' ' . $student['lname'], 0, 1, 'L');

        $pdf->SetXY($infoX, $y + 8);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($infoLabelW, 5, 'Class', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->Cell($infoValueW, 5, $classroom['class_name'], 0, 1, 'L');

        $pdf->SetXY($infoX, $y + 15);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($infoLabelW, 5, 'Year', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->Cell($infoValueW, 5, $classroom['class_year'], 0, 1, 'L');

        // Score / Position / Grade box
        if ($ovPct !== null && $grade !== null) {
            $gRgb = $gradeRgb($grade);
            $st   = $stats ?? [];
            $boxX = $sx + $cw - 62;
            $boxW = 62;
            $boxH = 26;

            $pdf->SetFillColor(248, 250, 252);
            $pdf->SetDrawColor(226, 232, 240);
            $pdf->SetLineStyle(['width' => 0.4, 'color' => [226, 232, 240]]);
            $pdf->RoundedRect($boxX, $y, $boxW, $boxH, 2, '1111', 'DF');

            $pdf->SetXY($boxX, $y + 2);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(...$gRgb);
            $pdf->Cell($boxW, 6, round($totalE, 1) . ' / ' . round($totalP, 1), 0, 1, 'C');

            $pdf->SetLineStyle(['width' => 0.3, 'color' => [221, 228, 237]]);
            $pdf->Line($boxX + 2, $y + 11, $boxX + $boxW - 2, $y + 11);

            $hasPos = !empty($st['position']);
            $halfW  = $hasPos ? $boxW / 2 : $boxW;

            if ($hasPos) {
                $pdf->SetXY($boxX, $y + 13);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetTextColor(29, 78, 216);
                $pdf->Cell($halfW, 4.5, (string)(int)$st['position'], 0, 0, 'C');

                $pdf->SetXY($boxX, $y + 18.5);
                $pdf->SetFont('helvetica', '', 5);
                $pdf->SetTextColor(130, 130, 130);
                $pdf->Cell($halfW, 3, 'CLASS POSITION', 0, 1, 'C');

                $pdf->SetLineStyle(['width' => 0.2, 'color' => [221, 228, 237]]);
                $pdf->Line($boxX + $halfW, $y + 12, $boxX + $halfW, $y + $boxH - 1);
            }

            $gradeX = $hasPos ? ($boxX + $halfW) : $boxX;
            $pdf->SetXY($gradeX, $y + 13);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetTextColor(...$gRgb);
            $pdf->Cell($halfW, 4.5, $grade, 0, 0, 'C');

            $pdf->SetXY($gradeX, $y + 18.5);
            $pdf->SetFont('helvetica', '', 5);
            $pdf->SetTextColor(130, 130, 130);
            $pdf->Cell($halfW, 3, 'GRADE', 0, 1, 'C');
        }

        $y += 30;

        // Thin section divider
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [241, 241, 244]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 5;

        // ── Marks table ──────────────────────────────────────────────────
        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell($cw, 5, 'Subject Results', 0, 1, 'L');
        $y += 6;

        // Column widths (total = 186)
        $cSub     = 62;
        $cMark    = 20;
        $cTotal   = 20;
        $cPct     = 18;
        $cGrade   = 18;
        $cComment = $cw - $cSub - $cMark - $cTotal - $cPct - $cGrade; // 48

        $rh = 7;

        // Header row
        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [226, 232, 240]]);
        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell($cSub,     $rh, '  Subject',      'TLBR', 0, 'L', true);
        $pdf->Cell($cMark,    $rh, 'Mark',            'TLBR', 0, 'C', true);
        $pdf->Cell($cTotal,   $rh, 'Total',           'TLBR', 0, 'C', true);
        $pdf->Cell($cPct,     $rh, '%',               'TLBR', 0, 'C', true);
        $pdf->Cell($cGrade,   $rh, 'Grade',           'TLBR', 0, 'C', true);
        $pdf->Cell($cComment, $rh, 'Teacher Comment', 'TLBR', 1, 'L', true);
        $y += $rh;

        // Data rows
        $shade = false;
        foreach ($marks as $m) {
            $mp    = ($m['mark'] !== null && (float)$m['total_mark'] > 0)
                   ? round(((float)$m['mark'] / (float)$m['total_mark']) * 100, 1)
                   : null;
            $mg    = $mp !== null ? \App\Models\TermExamModel::grade($mp) : '-';
            $mgRgb = ($mp !== null) ? $gradeRgb($mg) : [130, 130, 130];

            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [255, 255, 255]));
            $pdf->SetXY($sx, $y);

            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->Cell($cSub, $rh, '  ' . $m['subject_name'], 'TLBR', 0, 'L', true);

            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->Cell($cMark, $rh, $m['mark'] !== null ? (string)$m['mark'] : '-', 'TLBR', 0, 'C', true);

            $pdf->SetFont('helvetica', '', 7.5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell($cTotal, $rh, (string)$m['total_mark'], 'TLBR', 0, 'C', true);
            $pdf->Cell($cPct,   $rh, $mp !== null ? $mp . '%' : '-',  'TLBR', 0, 'C', true);

            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->SetTextColor(...$mgRgb);
            $pdf->Cell($cGrade, $rh, $mg, 'TLBR', 0, 'C', true);

            $pdf->SetFont('helvetica', 'I', 7);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell($cComment, $rh, $m['teacher_comment'] ?? '', 'TLBR', 1, 'L', true);

            $y    += $rh;
            $shade = !$shade;
        }

        // Overall row
        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->Cell($cSub,   $rh, '  Overall',                         'TLBR', 0, 'L', true);
        $pdf->Cell($cMark,  $rh, (string)round($totalE, 1),            'TLBR', 0, 'C', true);
        $pdf->Cell($cTotal, $rh, (string)round($totalP, 1),            'TLBR', 0, 'C', true);
        $pdf->Cell($cPct,   $rh, $ovPct !== null ? $ovPct . '%' : '-', 'TLBR', 0, 'C', true);

        if ($grade !== null) {
            $pdf->SetTextColor(...$gradeRgb($grade));
        } else {
            $pdf->SetTextColor(130, 130, 130);
        }
        $pdf->Cell($cGrade,   $rh, $grade ?? '-', 'TLBR', 0, 'C', true);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell($cComment, $rh, '',             'TLBR', 1, 'L', true);
        $y += $rh + 4;

        // ── Grade scale ──────────────────────────────────────────────────
        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'B', 6.5);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(24, 4, 'Grade Scale:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->Cell(0, 4, 'A+ >= 90%  |  A >= 80%  |  B >= 70%  |  C >= 50% (Pass)  |  F < 50% (Fail)', 0, 1, 'L');
        $y += 7;

        // ── Class stats ──────────────────────────────────────────────────
        $st        = $stats ?? [];
        $statItems = [
            ['label' => 'NO. SAT',  'value' => (string)(int)($st['number_sat']  ?? 0), 'rgb' => [20, 20, 20]],
            ['label' => 'PASS',     'value' => (string)(int)($st['number_pass'] ?? 0), 'rgb' => [21, 128, 61]],
            ['label' => 'FAIL',     'value' => (string)(int)($st['number_fail'] ?? 0), 'rgb' => [185, 28, 28]],
        ];
        if ((int)($st['number_absent'] ?? 0) > 0) {
            $statItems[] = ['label' => 'ABSENT', 'value' => (string)(int)$st['number_absent'], 'rgb' => [161, 98, 7]];
        }
        $statItems[] = ['label' => 'PASS %',    'value' => ($st['pct_pass'] ?? 0) . '%',                              'rgb' => [7, 89, 133]];
        $statItems[] = ['label' => 'CLASS AVG', 'value' => $st['avg_score'] !== null ? $st['avg_score'] . '%' : '-', 'rgb' => [29, 78, 216]];

        $statBoxH = 13;
        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [226, 232, 240]]);
        $pdf->RoundedRect($sx, $y, $cw, $statBoxH, 2, '1111', 'DF');

        $pdf->SetXY($sx + 2, $y + 2);
        $pdf->SetFont('helvetica', 'B', 5.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell(18, 4, 'CLASS STATS', 0, 0, 'L');

        $statStartX = $sx + 21;
        $statAvailW = $cw - 21;
        $nStats     = count($statItems);
        $statColW   = $statAvailW / $nStats;

        foreach ($statItems as $i => $si) {
            $itemX = $statStartX + ($i * $statColW);
            $pdf->SetLineStyle(['width' => 0.2, 'color' => [226, 232, 240]]);
            $pdf->Line($itemX, $y + 1, $itemX, $y + $statBoxH - 1);

            $pdf->SetXY($itemX, $y + 2);
            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->SetTextColor(...$si['rgb']);
            $pdf->Cell($statColW, 4.5, $si['value'], 0, 0, 'C');

            $pdf->SetXY($itemX, $y + 7);
            $pdf->SetFont('helvetica', '', 5);
            $pdf->SetTextColor(130, 130, 130);
            $pdf->Cell($statColW, 3.5, $si['label'], 0, 0, 'C');
        }
        $y += $statBoxH + 4;

        // ── Comments ─────────────────────────────────────────────────────
        $halfW = ($cw - 3) / 2;
        $comH  = 32;

        // CT comment
        $pdf->SetFillColor(240, 244, 255);
        $pdf->SetDrawColor(199, 210, 254);
        $pdf->SetLineStyle(['width' => 0.4, 'color' => [199, 210, 254]]);
        $pdf->RoundedRect($sx, $y, $halfW, $comH, 2, '1111', 'DF');

        $pdf->SetXY($sx + 3, $y + 2.5);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell($halfW - 6, 4, 'Class Teacher Comment', 0, 1, 'L');

        $ctText = $rep['ct_comment'] ?? '';
        $pdf->SetXY($sx + 3, $y + 8);
        $pdf->SetFont('helvetica', 'I', 7);
        $pdf->SetTextColor(80, 80, 80);
        if ($ctText) {
            $pdf->MultiCell($halfW - 6, 4, $ctText, 0, 'L', false, 1, '', '', true);
        } else {
            $pdf->Cell($halfW - 6, 4, 'No comment.', 0, 1, 'L');
        }

        $pdf->SetLineStyle(['width' => 0.3, 'color' => [199, 210, 254]]);
        $pdf->Line($sx + 3, $y + $comH - 5, $sx + $halfW - 3, $y + $comH - 5);
        $pdf->SetXY($sx + 3, $y + $comH - 4);
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell($halfW - 6, 3, 'Signature:', 0, 1, 'L');

        // Principal comment
        $rightX = $sx + $halfW + 3;
        $pdf->SetFillColor(240, 253, 244);
        $pdf->SetDrawColor(187, 247, 208);
        $pdf->SetLineStyle(['width' => 0.4, 'color' => [187, 247, 208]]);
        $pdf->RoundedRect($rightX, $y, $halfW, $comH, 2, '1111', 'DF');

        $pdf->SetXY($rightX + 3, $y + 2.5);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell($halfW - 6, 4, 'Principal Comment', 0, 1, 'L');

        $princText = $rep['principal_comment'] ?? '';
        $pdf->SetXY($rightX + 3, $y + 8);
        $pdf->SetFont('helvetica', 'I', 7);
        $pdf->SetTextColor(80, 80, 80);
        if ($princText) {
            $pdf->MultiCell($halfW - 6, 4, $princText, 0, 'L', false, 1, '', '', true);
        } else {
            $pdf->Cell($halfW - 6, 4, 'No comment.', 0, 1, 'L');
        }

        $pdf->SetLineStyle(['width' => 0.3, 'color' => [187, 247, 208]]);
        $pdf->Line($rightX + 3, $y + $comH - 5, $rightX + $halfW - 3, $y + $comH - 5);
        $pdf->SetXY($rightX + 3, $y + $comH - 4);
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell($halfW - 6, 3, 'Signature:', 0, 1, 'L');

        $y += $comH + 5;

        // ── Footer ───────────────────────────────────────────────────────
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [226, 232, 240]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 3;

        $footerLeft = $classroom['sch_name'] . ' | Term ' . $term . ', ' . $classroom['class_year'];
        if (!empty($rep['published_at'])) {
            $footerLeft .= ' | Published ' . date('d M Y', strtotime($rep['published_at']));
        }

        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell($cw / 2, 4, $footerLeft, 0, 0, 'L');
        $pdf->Cell($cw / 2, 4, 'Parent/Guardian Signature: _________________________', 0, 0, 'R');

        // Navuli system footer (fixed near bottom border)
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [200, 200, 200]]);
        $pdf->Line($sx, 273, $sx + $cw, 273);
        $pdf->SetXY($sx, 274);
        $pdf->SetFont('helvetica', 'I', 6.5);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->Cell($cw, 4, 'Generated by Navuli Fiji School Management System on ' . date('d F Y \a\t h:i A') . '.', 0, 1, 'C');

        // ── QR code (bottom-right, for report verification) ──────────────
        $qrUrl  = base_url('classroom/report/' . $classId . '/student/' . $studentId . '/term/' . $term . '/pdf');
        $qrSize = 27;
        $qrX    = $sx + ($cw - $qrSize) / 2;
        $qrY    = 240;
        $pdf->write2DBarcode($qrUrl, 'QRCODE,H', $qrX, $qrY, $qrSize, $qrSize, [
            'border'   => false,
            'vpadding' => 0,
            'hpadding' => 0,
            'fgcolor'  => [0, 0, 0],
            'bgcolor'  => false,
        ], 'N');
        $pdf->SetXY($qrX, $qrY + $qrSize + 0.5);
        $pdf->SetFont('helvetica', '', 5);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell($qrSize, 3, 'Scan to verify', 0, 1, 'C');

        // ── Output ───────────────────────────────────────────────────────
        restore_error_handler();
        $filename = 'report_card_' . $studentId . '_term' . $term . '.pdf';
        $pdf->Output($filename, 'I');
        exit;
    }
}
