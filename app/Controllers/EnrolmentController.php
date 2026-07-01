<?php
namespace App\Controllers;

class EnrolmentController extends BaseController
{
    // ================================================================
    // INDEX — Listing
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Enrolments', 'Enrolment', 'All Enrolments');

        $accessCheck = $this->require_access('_enrolment_listing');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $data['enrolments']   = $this->enrolmentModel->getAllWithDetails($schId);
        $data['canAdd']       = ($this->require_access('_add_enrolment')    === true);
        $data['canEdit']      = ($this->require_access('_edit_enrolment')   === true);
        $data['canDelete']    = ($this->require_access('_delete_enrolment') === true);
        $data['isSuperAdmin'] = $isSuperAdmin;

        if ($isSuperAdmin) {
            $data['canAdd'] = $data['canEdit'] = $data['canDelete'] = true;
        }

        $data['_view'] = 'app/enrolment/index';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // DETAIL
    // ================================================================

    public function detail(int $enrolId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $enrolment = $this->enrolmentModel->getDetail($enrolId);
        if (!$enrolment) {
            return redirect()->to('enrolment')->with('error', 'Enrolment not found.');
        }

        $this->setPageData('Enrolment Detail', 'Enrolment', 'Detail');

        $data['enrolment']  = $enrolment;
        $data['canEdit']    = ($this->require_access('_edit_enrolment')   === true);
        $data['canDelete']  = ($this->require_access('_delete_enrolment') === true);
        $data['isSuperAdmin'] = (int) $this->session->get('roleID') === 1;

        if ($data['isSuperAdmin']) {
            $data['canEdit'] = $data['canDelete'] = true;
        }

        $data['_view'] = 'app/enrolment/detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ADD — Show form
    // ================================================================

    public function add()
    {
        
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $accessCheck = $this->require_access('_add_enrolment');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Add Enrolment', 'Enrolment', 'Add Enrolment');

        $roleCatID    = (int) $this->session->get('roleCatID');
        $schIDSession = (int) $this->session->get('schID');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $isTeacher    = $roleCatID === 3;

        // Only Super Admin or a Teacher assigned to a school may add enrolments
        if (!$isSuperAdmin && !($isTeacher && $schIDSession > 0)) {
            $data['schID'] = $schIDSession;
            $data['message'] = 'You have reached this page because your account does not have the required permissions. Access is restricted to Super Admin users and Teachers with active admissions. If you believe this is an error, please verify your account status or contact system administration.';
            $data['_view'] = 'app/auth/view_access_error';
            return view('app/layouts/main', $data);
        }

        // Super Admin sees all schools; Teacher sees their school only
        $schIdFilter = $isSuperAdmin ? null : $schIDSession;

        $data['eligibleAdmissions'] = $this->enrolmentModel->getEligibleAdmissions($schIdFilter);
        $data['isSuperAdmin']       = $isSuperAdmin;
        $data['sessionSchId']       = $schIdFilter;
        $data['currentYear']        = (int) date('Y');
        $data['currentTerm']        = $this->getCurrentTerm();
        $data['_view']              = 'app/enrolment/add';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STREAMS FOR SCHOOL — AJAX endpoint
    // GET enrolment/streams/{schId}
    // Returns streams grouped by level for the given school.
    // ================================================================

    public function streamsForSchool(int $schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $streams = $this->enrolmentModel->getStreams($schId > 0 ? $schId : null);

        return $this->response->setJSON([
            'success' => true,
            'streams' => $streams,
        ]);
    }

    // ================================================================
    // SUBJECTS FOR STREAM — AJAX endpoint
    // GET enrolment/subjects/{streamId}
    // ================================================================

    public function subjectsForStream(int $streamId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $data = $this->enrolmentModel->getSubjectsByStream($streamId);

        return $this->response->setJSON([
            'success'  => true,
            'core'     => $data['core'],
            'optional' => $data['optional'],
        ]);
    }

    // ================================================================
    // STORE — Save new enrolment
    // ================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $admissionId      = (int) $this->request->getPost('admission_id_fk');
            $streamId         = (int) $this->request->getPost('stream_id_fk');
            $enrolDate        = $this->request->getPost('enrol_date');
            $enrolTerm        = (int) $this->request->getPost('enrol_term');
            $enrolYear        = (int) $this->request->getPost('enrol_year');
            $enrolNote        = $this->request->getPost('enrol_note') ?? '';
            $enrolStatus      = $this->request->getPost('enrol_status') ?? 'Active';
            $hasSubjects      = (int) $this->request->getPost('has_subjects');
            $coreSubjects     = $this->request->getPost('core_subjects') ?? [];

            // Collect optional subject selections (one per group: optional_group_N)
            $optionalSubjects = [];
            foreach ($this->request->getPost() as $key => $value) {
                if (preg_match('/^optional_group_\d+$/', $key) && !empty($value)) {
                    $optionalSubjects[] = (int) $value;
                }
            }

            if (!$admissionId || !$streamId || !$enrolDate || !$enrolTerm || !$enrolYear) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please fill in all required fields.'
                ]);
            }

            // Validate subjects if the stream has subjects loaded
            if ($hasSubjects) {
                $db          = \Config\Database::connect();
                $coreCount   = (int) $db->table('stream_core_subject')
                    ->where('stream_id_fk', $streamId)->countAllResults();
                $minRequired = $coreCount > 0 ? $coreCount : 1;
                if (count($coreSubjects) < $minRequired) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'At least ' . $minRequired . ' core ' . ($minRequired === 1 ? 'subject' : 'subjects') . ' must be selected.'
                    ]);
                }
            }

            // Check no existing active enrolment for this admission
            $db       = \Config\Database::connect();
            $existing = $db->table('enrolment')
                ->where('admission_id_fk', $admissionId)
                ->where('enrol_status', 'Active')
                ->get()->getRowArray();

            if ($existing) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This admission already has an active enrolment.'
                ]);
            }

            $enrolId = $this->enrolmentModel->insert([
                'admission_id_fk' => $admissionId,
                'stream_id_fk'    => $streamId,
                'enrol_date'      => $enrolDate,
                'enrol_time'      => time(),
                'enrol_term'      => $enrolTerm,
                'enrol_year'      => $enrolYear,
                'enrol_note'      => $enrolNote ?: null,
                'enrol_status'    => $enrolStatus,
            ]);

            if (!$enrolId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save enrolment.'
                ]);
            }

            // Insert student subjects
            if ($hasSubjects) {
                $classRow = $db->table('classroom')
                    ->where('stream_id_fk', $streamId)
                    ->where('class_year',   $enrolYear)
                    ->get()->getRowArray();
                $classId = $classRow ? (int)$classRow['class_id'] : 0;

                $allSubjects = array_merge(
                    array_map('intval', $coreSubjects),
                    $optionalSubjects
                );
                foreach ($allSubjects as $schSubId) {
                    if ($schSubId > 0) {
                        $db->table('student_subject')->insert([
                            'admission_id_fk' => $admissionId,
                            'class_id_fk'     => $classId,
                            'sch_sub_id_fk'   => $schSubId,
                            'stud_sub_status' => 'Active',
                        ]);
                    }
                }
            }

            // Get admission details for log
            $admission = $this->admissionModel->find($admissionId);
            $user      = $admission ? $this->userModel->find($admission['user_id_fk']) : null;

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Enrolment Added',
                'log_desc'    => 'Enrolment created for ' .
                                 ($user ? $user['fname'] . ' ' . $user['lname'] : 'User ID ' . ($admission['user_id_fk'] ?? '')) .
                                 ' | Year: ' . $enrolYear . ' Term: ' . $enrolTerm,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-abstract-28"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'success',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Enrolment saved successfully.',
                'redirect' => base_url('enrolment'),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[EnrolmentController::store] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // EDIT — Show edit form
    // ================================================================

    public function edit(int $enrolId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $enrolment = $this->enrolmentModel->getDetail($enrolId);
        if (!$enrolment) {
            return redirect()->to('enrolment')->with('error', 'Enrolment not found.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        // Block direct URL access for non-super-admin on Completed enrolments
        if ($enrolment['enrol_status'] === 'Completed' && !$isSuperAdmin) {
            return redirect()->to('enrolment/detail/' . $enrolId)
                ->with('edit_restricted', 'This enrolment is <strong>Completed</strong>. Only a Super Admin can edit it. You may view the details below.');
        }

        $this->setPageData('Edit Enrolment', 'Enrolment', 'Edit');

        // Always use the student's own school — never the session user's school
        $studentSchId = (int) ($enrolment['sch_id'] ?? 0);

        $data['enrolment']        = $enrolment;
        $data['isSuperAdmin']     = $isSuperAdmin;
        $data['streams']          = $this->enrolmentModel->getStreams($studentSchId ?: null);
        $data['currentYear']      = (int) date('Y');
        $data['studentSubjects']  = $this->enrolmentModel->getStudentSubjects($enrolId);
        $data['_view']            = 'app/enrolment/edit';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // UPDATE — Save edits
    // ================================================================

    public function update(int $enrolId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $enrolment = $this->enrolmentModel->find($enrolId);
            if (!$enrolment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Enrolment not found.']);
            }

            $isSuperAdmin  = (int) $this->session->get('roleID') === 1;
            $isLocked      = $enrolment['enrol_status'] === 'Completed' && !$isSuperAdmin;
            $newStatus     = $this->request->getPost('enrol_status') ?? 'Active';

            if ($isLocked) {
                // Non-super-admin: only status change allowed on Completed enrolments
                $this->enrolmentModel->update($enrolId, [
                    'enrol_status' => $newStatus,
                ]);
            } else {
                $this->enrolmentModel->update($enrolId, [
                    'stream_id_fk' => (int) $this->request->getPost('stream_id_fk'),
                    'enrol_date'   => $this->request->getPost('enrol_date'),
                    'enrol_term'   => (int) $this->request->getPost('enrol_term'),
                    'enrol_year'   => (int) $this->request->getPost('enrol_year'),
                    'enrol_note'   => $this->request->getPost('enrol_note') ?: null,
                    'enrol_status' => $newStatus,
                ]);
            }

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Enrolment Updated',
                'log_desc'    => 'Enrolment ID ' . $enrolId . ' updated.',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-pencil"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'warning',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Enrolment updated successfully.',
                'redirect' => base_url('enrolment/detail/' . $enrolId),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[EnrolmentController::update] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DELETE
    // ================================================================

    public function delete(int $enrolId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $enrolment = $this->enrolmentModel->find($enrolId);
            if (!$enrolment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Enrolment not found.']);
            }

            $this->enrolmentModel->delete($enrolId);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Enrolment Deleted',
                'log_desc'    => 'Enrolment ID ' . $enrolId . ' deleted.',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'danger',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrolment deleted successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[EnrolmentController::delete] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // AVAILABLE SUBJECTS — AJAX GET enrolment/available-subjects/{enrolId}
    // ================================================================

    public function availableSubjects(int $enrolId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $data = $this->enrolmentModel->getAvailableSubjects($enrolId);

        return $this->response->setJSON([
            'success'  => true,
            'core'     => $data['core'],
            'optional' => $data['optional'],
        ]);
    }

    // ================================================================
    // ADD STUDENT SUBJECT — AJAX POST enrolment/subject/add
    // ================================================================

    public function addStudentSubject()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $enrolId   = (int) $this->request->getPost('enrol_id');
            $schSubId  = (int) $this->request->getPost('sch_sub_id');

            if (!$enrolId || !$schSubId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid data.']);
            }

            $db = \Config\Database::connect();

            $enrolment   = $db->table('enrolment')
                ->select('admission_id_fk, stream_id_fk, enrol_year')
                ->where('enrol_id', $enrolId)->get()->getRowArray();
            $admissionId = (int)($enrolment['admission_id_fk'] ?? 0);
            $classRow    = $db->table('classroom')
                ->where('stream_id_fk', (int)($enrolment['stream_id_fk'] ?? 0))
                ->where('class_year',   (int)($enrolment['enrol_year']   ?? 0))
                ->get()->getRowArray();
            $classId = $classRow ? (int)$classRow['class_id'] : 0;

            // Prevent duplicates
            $exists = $db->table('student_subject')
                ->where('admission_id_fk', $admissionId)
                ->where('sch_sub_id_fk', $schSubId)
                ->get()->getRowArray();

            if ($exists) {
                return $this->response->setJSON(['success' => false, 'message' => 'Subject already added.']);
            }

            $db->table('student_subject')->insert([
                'admission_id_fk' => $admissionId,
                'class_id_fk'     => $classId,
                'sch_sub_id_fk'   => $schSubId,
                'stud_sub_status' => 'Active',
            ]);

            $studSubId = $db->insertID();

            // Return the new subject details for DOM update
            $subject = $db->query("
                SELECT ss.stud_sub_id, ss.sch_sub_id_fk, sub.subject_name,
                       CASE WHEN scs.stream_core_sub_id IS NOT NULL THEN 'core'
                            WHEN sos.stream_opt_sub_id  IS NOT NULL THEN 'optional'
                            ELSE 'other' END AS subject_type,
                       sos.option_num
                FROM student_subject ss
                LEFT JOIN sch_subject schsub ON schsub.sch_sub_id  = ss.sch_sub_id_fk
                LEFT JOIN subject sub        ON sub.subject_id     = schsub.subject_id_fk
                LEFT JOIN (
                    SELECT scs2.sch_sub_id_fk, scs2.stream_core_sub_id
                    FROM stream_core_subject scs2
                    INNER JOIN enrolment e ON e.stream_id_fk = scs2.stream_id_fk AND e.enrol_id = ?
                ) scs ON scs.sch_sub_id_fk = ss.sch_sub_id_fk
                LEFT JOIN (
                    SELECT sos2.sch_sub_id_fk, sos2.stream_opt_sub_id, sos2.option_num
                    FROM stream_optional_subject sos2
                    INNER JOIN enrolment e ON e.stream_id_fk = sos2.stream_id_fk AND e.enrol_id = ?
                ) sos ON sos.sch_sub_id_fk = ss.sch_sub_id_fk
                WHERE ss.stud_sub_id = ?
            ", [$enrolId, $enrolId, $studSubId])->getRowArray();

            return $this->response->setJSON(['success' => true, 'subject' => $subject]);

        } catch (\Exception $e) {
            log_message('error', '[EnrolmentController::addStudentSubject] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // ADD STUDENT SUBJECTS BATCH — AJAX POST enrolment/subject/add-batch
    // ================================================================

    public function addStudentSubjectBatch()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $enrolId   = (int) $this->request->getPost('enrol_id');
            $schSubIds = $this->request->getPost('sch_sub_ids') ?? [];

            if (!$enrolId || empty($schSubIds)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No subjects selected.']);
            }

            $db = \Config\Database::connect();

            // Get stream, admission, and class for subject insertion
            $enrolment   = $db->table('enrolment')->select('stream_id_fk, admission_id_fk, enrol_year')
                ->where('enrol_id', $enrolId)->get()->getRowArray();
            $streamId    = (int)($enrolment['stream_id_fk']    ?? 0);
            $admissionId = (int)($enrolment['admission_id_fk'] ?? 0);
            $classRow    = $db->table('classroom')
                ->where('stream_id_fk', $streamId)
                ->where('class_year',   (int)($enrolment['enrol_year'] ?? 0))
                ->get()->getRowArray();
            $classId = $classRow ? (int)$classRow['class_id'] : 0;

            $added = [];
            foreach ($schSubIds as $schSubId) {
                $schSubId = (int) $schSubId;
                if (!$schSubId) continue;

                // Skip duplicates
                $exists = $db->table('student_subject')
                    ->where('admission_id_fk', $admissionId)
                    ->where('sch_sub_id_fk', $schSubId)
                    ->get()->getRowArray();
                if ($exists) continue;

                $db->table('student_subject')->insert([
                    'admission_id_fk' => $admissionId,
                    'class_id_fk'     => $classId,
                    'sch_sub_id_fk'   => $schSubId,
                    'stud_sub_status' => 'Active',
                ]);
                $studSubId = $db->insertID();

                $subject = $db->query("
                    SELECT ss.stud_sub_id, ss.sch_sub_id_fk, sub.subject_name,
                           CASE WHEN scs.stream_core_sub_id IS NOT NULL THEN 'core'
                                WHEN sos.stream_opt_sub_id  IS NOT NULL THEN 'optional'
                                ELSE 'other' END AS subject_type,
                           sos.option_num
                    FROM student_subject ss
                    LEFT JOIN sch_subject schsub ON schsub.sch_sub_id = ss.sch_sub_id_fk
                    LEFT JOIN subject sub        ON sub.subject_id    = schsub.subject_id_fk
                    LEFT JOIN (SELECT sch_sub_id_fk, stream_core_sub_id FROM stream_core_subject WHERE stream_id_fk = ?) scs
                           ON scs.sch_sub_id_fk = ss.sch_sub_id_fk
                    LEFT JOIN (SELECT sch_sub_id_fk, stream_opt_sub_id, option_num FROM stream_optional_subject WHERE stream_id_fk = ?) sos
                           ON sos.sch_sub_id_fk = ss.sch_sub_id_fk
                    WHERE ss.stud_sub_id = ?
                ", [$streamId, $streamId, $studSubId])->getRowArray();

                if ($subject) $added[] = $subject;
            }

            return $this->response->setJSON(['success' => true, 'added' => $added]);

        } catch (\Exception $e) {
            log_message('error', '[EnrolmentController::addStudentSubjectBatch] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // REMOVE STUDENT SUBJECT — AJAX POST enrolment/subject/remove/{studSubId}
    // ================================================================

    public function removeStudentSubject(int $studSubId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $db = \Config\Database::connect();
            $row = $db->table('student_subject')->where('stud_sub_id', $studSubId)->get()->getRowArray();

            if (!$row) {
                return $this->response->setJSON(['success' => false, 'message' => 'Subject not found.']);
            }

            $db->table('student_subject')->where('stud_sub_id', $studSubId)->delete();

            return $this->response->setJSON(['success' => true]);

        } catch (\Exception $e) {
            log_message('error', '[EnrolmentController::removeStudentSubject] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // REPORT — GET enrolment/report?status=X&stream=Y&year=Z
    // ================================================================

    public function report()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $status = trim($this->request->getGet('status') ?? '');
        $stream = trim($this->request->getGet('stream') ?? '');
        $year   = trim($this->request->getGet('year')   ?? '');

        if (!$status || !$stream || !$year) {
            return redirect()->to('enrolment')->with('error', 'Status, stream, and year are required for the report.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $all = $this->enrolmentModel->getAllWithDetails($schId);

        $rows = array_values(array_filter($all, function ($e) use ($status, $stream, $year) {
            return ($e['enrol_status'] ?? '') === $status
                && ($e['stream_name']  ?? '') === $stream
                && (string)($e['enrol_year'] ?? '') === $year;
        }));

        // Keep only students who have the selected status for ALL terms in this set;
        // display only their highest-term record.
        if (!empty($rows)) {
            $allTerms = array_values(array_unique(array_map(
                fn($r) => (int)($r['enrol_term'] ?? 0), $rows
            )));
            sort($allTerms);

            $byStudent = [];
            foreach ($rows as $row) {
                $byStudent[$row['admission_id_fk'] ?? $row['user_id']][] = $row;
            }

            $rows = [];
            foreach ($byStudent as $studentRows) {
                $studentTerms = array_values(array_unique(array_map(
                    fn($r) => (int)($r['enrol_term'] ?? 0), $studentRows
                )));
                sort($studentTerms);
                if ($studentTerms === $allTerms) {
                    usort($studentRows, fn($a, $b) =>
                        (int)($b['enrol_term'] ?? 0) <=> (int)($a['enrol_term'] ?? 0)
                    );
                    $rows[] = $studentRows[0];
                }
            }
        }

        // ── School info for header ─────────────────────────────────────────
        $schLogo = null;
        $schName = null;
        if (!empty($rows)) {
            $schLogo = $rows[0]['sch_logo'] ?? null;
            $schName = $rows[0]['sch_name'] ?? null;
        } elseif ($schId) {
            $db      = \Config\Database::connect();
            $school  = $db->table('school')->where('sch_id', $schId)->get()->getRowArray();
            $schLogo = $school['sch_logo'] ?? null;
            $schName = $school['sch_name'] ?? null;
        }

        // ── TCPDF ──────────────────────────────────────────────────────
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

        set_error_handler(static function(int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP')
                || str_contains($errstr, 'gd-png')
                || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetTitle('Enrolment Report');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        $sx      = 15;
        $cw      = 267;
        $bottomY = 188;

        // ── Header: logos + school name + report title ────────────────────
        $y = 15;

        $logoPath = !empty($schLogo) ? FCPATH . 'uploads/school/logo/' . $schLogo : null;
        if ($logoPath && file_exists($logoPath)) {
            $pdf->Image($logoPath, $sx, $y, 22, 22, '', '', 'T', false, 300);
        }
        $navuliLogo = FCPATH . 'icon.png';
        if (file_exists($navuliLogo)) {
            $pdf->Image($navuliLogo, $sx + $cw - 20, $y, 20, 20, '', '', 'T', false, 300);
        }

        $centerX = $sx + 25;
        $centerW = $cw - 47;

        $pdf->SetXY($centerX, $y + 2);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($centerW, 6, strtoupper($schName ?? 'NAVULI FIJI'), 0, 1, 'C');

        $pdf->SetXY($centerX, $y + 9);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell($centerW, 5, 'ENROLMENT REPORT', 0, 1, 'C');

        $pdf->SetXY($centerX, $y + 15);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($centerW, 4, 'Stream: ' . $stream . '   |   Year: ' . $year . '   |   Status: ' . $status, 0, 1, 'C');

        $pdf->SetXY($centerX, $y + 20);
        $pdf->SetFont('helvetica', 'I', 7.5);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($centerW, 4, 'Generated: ' . date('d M Y, H:i'), 0, 1, 'C');

        $y += 28;
        $pdf->SetLineStyle(['width' => 0.7, 'color' => [26, 86, 219]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [147, 197, 253]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 6;
        $pdf->SetY($y);

        // ── Column widths: # | Student | Username | School | Year | Term | Enrol Date | Status
        $cols    = [8, 68, 62, 54, 16, 14, 26, 19]; // sum = 267mm
        $headers = ['#', 'Student', 'Username', 'School', 'Year', 'Term', 'Enrol Date', 'Status'];
        $aligns  = ['C', 'L', 'L', 'L', 'C', 'C', 'C', 'C'];

        $drawHead = function () use ($pdf, $cols, $headers, $aligns) {
            $pdf->SetFillColor(248, 250, 252);
            $pdf->SetDrawColor(226, 232, 240);
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [226, 232, 240]]);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(50, 50, 50);
            foreach ($headers as $i => $h) {
                $pdf->Cell($cols[$i], 7, $h, 'TLBR', 0, $aligns[$i], true);
            }
            $pdf->Ln();
            $pdf->SetTextColor(40, 40, 40);
            $pdf->SetFont('helvetica', '', 7.5);
        };

        $drawRow = function (array $row, int $num) use ($pdf, $cols, $aligns) {
            $shade = ($num % 2 === 0);
            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [255, 255, 255]));
            $pdf->SetDrawColor(226, 232, 240);
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [226, 232, 240]]);
            $pdf->SetFont('helvetica', '', 7.5);
            $pdf->SetTextColor(40, 40, 40);
            $fullName = trim(
                ($row['fname'] ?? '') . ' ' .
                (!empty($row['oname']) ? $row['oname'] . ' ' : '') .
                ($row['lname'] ?? '')
            );
            $enrolDate = !empty($row['enrol_date']) ? date('d M Y', strtotime($row['enrol_date'])) : '—';
            $cells = [
                $num,
                $fullName,
                $row['username']     ?? '—',
                $row['sch_name']     ?? '—',
                $row['enrol_year']   ?? '—',
                'T' . ($row['enrol_term'] ?? '—'),
                $enrolDate,
                $row['enrol_status'] ?? '—',
            ];
            foreach ($cells as $i => $val) {
                $pdf->Cell($cols[$i], 6.5, $val, 'TLBR', 0, $aligns[$i], $shade);
            }
            $pdf->Ln();
        };

        if (empty($rows)) {
            $pdf->SetFont('helvetica', 'I', 9);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell($cw, 8, 'No enrolments found for the selected criteria.', 0, 1, 'C');
        } else {
            $drawHead();
            foreach ($rows as $i => $row) {
                if ($pdf->GetY() > $bottomY) {
                    $pdf->AddPage();
                    $drawHead();
                }
                $drawRow($row, $i + 1);
            }
        }

        $pdf->Ln(3);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($cw, 5, 'Total: ' . count($rows) . ' record(s)', 0, 1, 'R');

        $pdf->Ln(4);
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [226, 232, 240]]);
        $pdf->Line($sx, $pdf->GetY(), $sx + $cw, $pdf->GetY());
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', 'I', 7);
        $pdf->SetTextColor(160, 160, 160);
        $pdf->Cell($cw, 5, 'Generated by Navuli Fiji School Management System on ' . date('d M Y \a\t H:i'), 0, 0, 'C');

        while (ob_get_level()) { ob_end_clean(); }
        $pdf->Output('Enrolment_Report_' . $stream . '_' . $year . '.pdf', 'I');
        exit;
    }

    // ================================================================
    // PRIVATE HELPERS
    // ================================================================

    private function getCurrentTerm(): int
    {
        $month = (int) date('n');
        if ($month <= 4)  return 1;
        if ($month <= 8)  return 2;
        return 3;
    }
}