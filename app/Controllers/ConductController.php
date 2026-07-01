<?php
namespace App\Controllers;

class ConductController extends BaseController
{
    private function ensureConductUploadDir(): string
    {
        $dir = FCPATH . 'uploads/conduct/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    private function ensureAppealUploadDir(): string
    {
        $dir = FCPATH . 'uploads/conduct_appeals/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    // ================================================================
    // INDEX — Listing of conduct incidents (school-scoped)
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Conduct', 'Conduct', 'All Incidents');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_conduct_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId = $isSuperAdmin ? 0 : (int) $this->session->get('schID');

        $filters = [
            'student_id'     => $this->request->getGet('student_id'),
            'category'       => $this->request->getGet('category'),
            'severity_level' => $this->request->getGet('severity_level'),
            'is_positive'    => $this->request->getGet('is_positive'),
            'is_resolved'    => $this->request->getGet('is_resolved'),
            'date_from'      => $this->request->getGet('date_from'),
            'date_to'        => $this->request->getGet('date_to'),
        ];

        $incidents = $this->conductIncidentModel->getBySchool($schId, $filters);

        $totalIncidents  = count($incidents);
        $positivePoints  = 0;
        $negativePoints  = 0;
        $severityCounts  = [];

        foreach ($incidents as $incident) {
            $points = (int) ($incident['points_awarded'] ?? 0);
            if ($points >= 0) {
                $positivePoints += $points;
            } else {
                $negativePoints += $points;
            }

            $severity = $incident['severity_level'] ?? 'Unknown';
            $severityCounts[$severity] = ($severityCounts[$severity] ?? 0) + 1;
        }

        $data['incidents']       = $incidents;
        $data['totalIncidents']  = $totalIncidents;
        $data['positivePoints']  = $positivePoints;
        $data['negativePoints']  = $negativePoints;
        $data['severityCounts']  = $severityCounts;
        $data['typesGrouped']    = $this->conductTypeModel->getGroupedByCategory();
        $data['filters']         = $filters;
        $data['canAdd']          = $isSuperAdmin || $this->grant_access('_add_conduct');
        $data['canEdit']         = $isSuperAdmin || $this->grant_access('_edit_conduct');
        $data['canDelete']       = $isSuperAdmin || $this->grant_access('_remove_conduct');
        $data['isSuperAdmin']    = $isSuperAdmin;
        $data['_view']           = 'app/conduct/index';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ADD — Show form to log a new incident
    // ================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_add_conduct')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Log Incident', 'Conduct', 'Add Incident');

        $schId = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $data['students']     = $this->conductIncidentModel->getActiveStudentsBySchool($schId);
        $data['typesGrouped'] = $this->conductTypeModel->getGroupedByCategory();
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['incident']     = null;
        $data['files']        = [];
        $data['isEdit']       = false;
        $data['_view']        = 'app/conduct/form';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STORE — Save new incident
    // ================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_add_conduct')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $studentId = (int) $this->request->getPost('student_id');
            $typeId    = (int) $this->request->getPost('type_id_fk');
            $points    = $this->request->getPost('points_awarded');

            if ($points === null || $points === '') {
                $type   = $this->conductTypeModel->find($typeId);
                $points = $type['default_points'] ?? 0;
            }

            $incidentId = $this->conductIncidentModel->insert([
                'student_id'           => $studentId,
                'staff_id'             => (int) $this->session->get('userID'),
                'type_id_fk'           => $typeId,
                'points_awarded'       => (int) $points,
                'incident_description' => $this->request->getPost('incident_description') ?? '',
                'incident_date'        => $this->request->getPost('incident_date') ?: date('Y-m-d H:i:s'),
                'location'             => $this->request->getPost('location') ?? '',
                'is_resolved'          => 0,
            ]);

            $this->handleFileUploads((int) $incidentId);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Conduct Incident Logged',
                'log_desc'    => 'Conduct incident logged for admission ID ' . $studentId,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-shield-cross"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'warning',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Incident logged successfully.',
                'redirect' => base_url('conduct/detail/' . $incidentId),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ConductController::store] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // EDIT — Show edit form
    // ================================================================

    public function edit(int $incidentId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_edit_conduct')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $incident = $this->conductIncidentModel->getDetail($incidentId);
        if (!$incident) {
            return redirect()->to('conduct')->with('error', 'Incident not found.');
        }

        $this->setPageData('Edit Incident', 'Conduct', 'Edit Incident');

        $schId = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $data['students']     = $this->conductIncidentModel->getActiveStudentsBySchool($schId);
        $data['typesGrouped'] = $this->conductTypeModel->getGroupedByCategory();
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['incident']     = $incident;
        $data['files']        = $this->conductIncidentFileModel->getByIncident($incidentId);
        $data['isEdit']       = true;
        $data['_view']        = 'app/conduct/form';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // UPDATE — Save edited incident
    // ================================================================

    public function update(int $incidentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_edit_conduct')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $incident = $this->conductIncidentModel->find($incidentId);
            if (!$incident) {
                return $this->response->setJSON(['success' => false, 'message' => 'Incident not found.']);
            }

            $this->conductIncidentModel->update($incidentId, [
                'student_id'           => (int) $this->request->getPost('student_id'),
                'type_id_fk'           => (int) $this->request->getPost('type_id_fk'),
                'points_awarded'       => (int) $this->request->getPost('points_awarded'),
                'incident_description' => $this->request->getPost('incident_description') ?? '',
                'incident_date'        => $this->request->getPost('incident_date') ?: $incident['incident_date'],
                'location'             => $this->request->getPost('location') ?? '',
                'is_resolved'          => (int) ($this->request->getPost('is_resolved') ?? $incident['is_resolved']),
            ]);

            $this->handleFileUploads($incidentId);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Conduct Incident Updated',
                'log_desc'    => 'Conduct incident ID ' . $incidentId . ' updated',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-shield-cross"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'warning',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Incident updated successfully.',
                'redirect' => base_url('conduct/detail/' . $incidentId),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ConductController::update] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DELETE — Delete incident + files + actions + notifications
    // ================================================================

    public function delete(int $incidentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_remove_conduct')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $incident = $this->conductIncidentModel->find($incidentId);
            if (!$incident) {
                return $this->response->setJSON(['success' => false, 'message' => 'Incident not found.']);
            }

            $files = $this->conductIncidentFileModel->getByIncident($incidentId);
            foreach ($files as $file) {
                $path = FCPATH . 'uploads/conduct/' . $file['file_src'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // No FK cascade — manually clean up child rows
            $this->conductIncidentFileModel->where('incident_id_fk', $incidentId)->delete();
            $this->conductActionModel->where('incident_id', $incidentId)->delete();
            $this->conductNotificationModel->where('incident_id', $incidentId)->delete();
            $this->conductIncidentModel->delete($incidentId);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Conduct Incident Deleted',
                'log_desc'    => 'Conduct incident ID ' . $incidentId . ' deleted',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'danger',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Incident deleted successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ConductController::delete] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DETAIL — View one incident (files, actions, notifications)
    // ================================================================

    public function detail(int $incidentId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_conduct_detail')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $incident = $this->conductIncidentModel->getDetail($incidentId);
        if (!$incident) {
            return redirect()->to('conduct')->with('error', 'Incident not found.');
        }

        $this->setPageData('Incident Detail', 'Conduct', 'Detail');

        $data['incident']          = $incident;
        $data['files']             = $this->conductIncidentFileModel->getByIncident($incidentId);
        $data['actions']           = $this->conductActionModel->getByIncident($incidentId);
        $data['notifications']     = $this->conductNotificationModel->getByIncident($incidentId);
        $data['appeal']            = $this->conductAppealModel->getByIncident($incidentId);
        $data['appealFiles']       = $data['appeal'] ? $this->conductAppealFileModel->getByAppeal((int) $data['appeal']['appeal_id']) : [];
        $data['canEdit']           = $isSuperAdmin || $this->grant_access('_edit_conduct');
        $data['canDelete']         = $isSuperAdmin || $this->grant_access('_remove_conduct');
        $data['canProcessAppeal']  = $isSuperAdmin || $this->grant_access('_process_conduct_appeal');
        $data['_view']             = 'app/conduct/detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ADD ACTION — Attach a disciplinary/restorative action
    // ================================================================

    public function addAction(int $incidentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_conduct_detail')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $incident = $this->conductIncidentModel->find($incidentId);
            if (!$incident) {
                return $this->response->setJSON(['success' => false, 'message' => 'Incident not found.']);
            }

            $this->conductActionModel->insert([
                'incident_id'    => $incidentId,
                'action_type'    => $this->request->getPost('action_type') ?? '',
                'action_date'    => $this->request->getPost('action_date') ?: date('Y-m-d'),
                'duration_hours' => $this->request->getPost('duration_hours') ?: null,
                'is_completed'   => 0,
                'notes'          => $this->request->getPost('notes') ?? '',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Action added successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ConductController::addAction] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // COMPLETE ACTION — Mark an action as completed
    // ================================================================

    public function completeAction(int $actionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_conduct_detail')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $action = $this->conductActionModel->find($actionId);
        if (!$action) {
            return $this->response->setJSON(['success' => false, 'message' => 'Action not found.']);
        }

        $this->conductActionModel->markCompleted($actionId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Action marked as completed.',
        ]);
    }

    // ================================================================
    // NOTIFY — Send a parent notification for an incident
    // ================================================================

    public function notify(int $incidentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_conduct_detail')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $incident = $this->conductIncidentModel->getDetail($incidentId);
        if (!$incident) {
            return $this->response->setJSON(['success' => false, 'message' => 'Incident not found.']);
        }

        $sentVia = $this->request->getPost('sent_via') ?: 'Email';
        $message = $this->request->getPost('message') ?: (
            'A conduct incident (' . ($incident['type_name'] ?? 'Incident') . ') has been logged for ' .
            trim($incident['student_fname'] . ' ' . $incident['student_lname']) . '.'
        );

        $parents = $this->parentStudentModel->getParentsOf((int) $incident['student_user_id']);

        if (empty($parents)) {
            $this->conductNotificationModel->log($incidentId, 'Parent', $sentVia, $message);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'No linked parent found — notification logged only.',
            ]);
        }

        foreach ($parents as $parent) {
            $parentUser = $this->userModel->find($parent['user_id']);
            $parentEmail = $parentUser['email'] ?? null;

            if ($sentVia === 'Email' && !empty($parentEmail)) {
                $this->sendEmail([
                    'to'      => $parentEmail,
                    'subject' => 'Conduct Notification — ' . trim($incident['student_fname'] . ' ' . $incident['student_lname']),
                    'view'    => 'email/conduct_notification',
                    'viewData' => [
                        'parentName'  => trim($parent['fname'] . ' ' . $parent['lname']),
                        'studentName' => trim($incident['student_fname'] . ' ' . $incident['student_lname']),
                        'message'     => $message,
                        'incident'    => $incident,
                    ],
                ]);
            }

            $this->conductNotificationModel->log($incidentId, 'Parent', $sentVia, $message);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Notification sent successfully.',
        ]);
    }

    // ================================================================
    // DELETE FILE — Delete single attached file
    // ================================================================

    public function deleteFile(int $fileId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $file = $this->conductIncidentFileModel->find($fileId);
            if (!$file) {
                return $this->response->setJSON(['success' => false, 'message' => 'File not found.']);
            }

            $path = FCPATH . 'uploads/conduct/' . $file['file_src'];
            if (file_exists($path)) {
                unlink($path);
            }

            $this->conductIncidentFileModel->delete($fileId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'File deleted successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ConductController::deleteFile] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // VIEW FILE — Stream file to browser
    // ================================================================

    public function viewFile(int $fileId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $file = $this->conductIncidentFileModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }

        $path = FCPATH . 'uploads/conduct/' . $file['file_src'];
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File does not exist on server.');
        }

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . $file['file_src'] . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    // ================================================================
    // REPORT — Per-school conduct points summary
    // ================================================================

    public function report()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_conduct_report')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Conduct Report', 'Conduct', 'Report');

        $schId = $isSuperAdmin ? 0 : (int) $this->session->get('schID');
        $year  = (int) ($this->request->getGet('year') ?: date('Y'));

        $data['summary']      = $this->conductIncidentModel->getPointsSummaryBySchool($schId, $year);
        $data['year']         = $year;
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['_view']        = 'app/conduct/report';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // MY CONDUCT — Student / Parent / Staff-who-is-parent view
    // ================================================================

    public function my()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userID    = (int) $this->session->get('userID');
        $roleCatID = (int) $this->session->get('roleCatID');

        // roleCatID 4 = Student, 6 = Parent
        $isStudent = ($roleCatID === 4);
        $isParent  = ($roleCatID === 6);

        // Teachers/staff who are also parents get parent view via is_a_parent flag
        $isParentStaff = false;
        if (!$isStudent && !$isParent) {
            if ($this->grant_access('_my_conduct')) {
                $isParent = true;
            } else {
                $user = $this->userModel->find($userID);
                if (!empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1) {
                    $isParentStaff = true;
                    $isParent      = true;
                } else {
                    $data['_view'] = 'app/auth/access_control';
                    return view('app/layouts/main', $data);
                }
            }
        }

        $this->setPageData('My Conduct', 'Conduct', 'My Conduct');

        $data['isStudent'] = $isStudent;
        $data['isParent']  = $isParent || $isParentStaff;
        $data['incidents'] = [];
        $data['admission'] = null;
        $data['children']  = [];

        if ($isStudent) {
            $admissions = $this->admissionModel->getAdmissionByUser($userID);
            $admission  = !empty($admissions) ? $admissions[0] : null;

            $data['admission'] = $admission;
            $data['incidents'] = $admission
                ? $this->conductIncidentModel->getByStudent((int) $admission['admission_id'])
                : [];
        } else {
            $children     = $this->parentStudentModel->getChildrenOf($userID);
            $childrenData = [];

            foreach ($children as $child) {
                $childId    = (int) $child['user_id'];
                $admissions = $this->admissionModel->getAdmissionByUser($childId);
                $admission  = !empty($admissions) ? $admissions[0] : null;

                $childrenData[] = [
                    'child'     => $child,
                    'admission' => $admission,
                    'incidents' => $admission
                        ? $this->conductIncidentModel->getByStudent((int) $admission['admission_id'])
                        : [],
                ];
            }

            $data['children'] = $childrenData;
        }

        $data['_view'] = 'app/conduct/my';
        return view('app/layouts/main', $data);
    }

    // ================================================================
    // MY DETAIL — Student/parent view of one specific incident
    // ================================================================

    public function myDetail(int $incidentId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userID    = (int) $this->session->get('userID');
        $roleCatID = (int) $this->session->get('roleCatID');

        $isStudent = ($roleCatID === 4);
        $isParent  = ($roleCatID === 6);

        if (!$isStudent && !$isParent) {
            $allowed = false;
            if ($this->grant_access('_my_conduct')) {
                $isParent = $allowed = true;
            } else {
                $user = $this->userModel->find($userID);
                if (!empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1) {
                    $isParent = $allowed = true;
                }
            }
            if (!$allowed) {
                $data['_view'] = 'app/auth/access_control';
                return view('app/layouts/main', $data);
            }
        }

        $incident = $this->conductIncidentModel->getDetail($incidentId);
        if (!$incident) {
            return redirect()->to('conduct/my')->with('error', 'Incident not found.');
        }

        // Ownership check: incident must belong to this student or a child of this parent
        $ownerAdmissionId = (int) $incident['student_id'];

        if ($isStudent) {
            $admissions = $this->admissionModel->getAdmissionByUser($userID);
            $myAdmissionId = !empty($admissions) ? (int) $admissions[0]['admission_id'] : 0;
            if ($myAdmissionId !== $ownerAdmissionId) {
                return redirect()->to('conduct/my')->with('error', 'Access denied.');
            }
        } else {
            $children = $this->parentStudentModel->getChildrenOf($userID);
            $childAdmissionIds = [];
            foreach ($children as $child) {
                $admissions = $this->admissionModel->getAdmissionByUser((int) $child['user_id']);
                if (!empty($admissions)) {
                    $childAdmissionIds[] = (int) $admissions[0]['admission_id'];
                }
            }
            if (!in_array($ownerAdmissionId, $childAdmissionIds)) {
                return redirect()->to('conduct/my')->with('error', 'Access denied.');
            }
        }

        $this->setPageData('Incident Detail', 'Conduct', 'My Conduct');

        $appeal    = $this->conductAppealModel->getByIncident($incidentId);
        $canAppeal = ($appeal === null) && !(bool) $incident['is_resolved'];

        $data['incident']    = $incident;
        $data['files']       = $this->conductIncidentFileModel->getByIncident($incidentId);
        $data['appeal']      = $appeal;
        $data['appealFiles'] = $appeal ? $this->conductAppealFileModel->getByAppeal((int) $appeal['appeal_id']) : [];
        $data['canAppeal']   = $canAppeal;
        $data['_view']       = 'app/conduct/my_detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // APPEAL — Student/parent submits an appeal (AJAX POST)
    // ================================================================

    public function appeal(int $incidentId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userID    = (int) $this->session->get('userID');
        $roleCatID = (int) $this->session->get('roleCatID');

        $isStudent = ($roleCatID === 4);
        $isParent  = ($roleCatID === 6);

        if (!$isStudent && !$isParent) {
            $allowed = false;
            if ($this->grant_access('_my_conduct')) {
                $isParent = $allowed = true;
            } else {
                $user = $this->userModel->find($userID);
                if (!empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1) {
                    $isParent = $allowed = true;
                }
            }
            if (!$allowed) {
                return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
            }
        }

        $incident = $this->conductIncidentModel->getDetail($incidentId);
        if (!$incident) {
            return $this->response->setJSON(['success' => false, 'message' => 'Incident not found.']);
        }

        // One appeal per incident
        $existing = $this->conductAppealModel->getByIncident($incidentId);
        if ($existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'An appeal has already been submitted for this incident.']);
        }

        $reason = trim((string) $this->request->getPost('appeal_reason'));
        if ($reason === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Please provide a reason for your appeal.']);
        }

        try {
            $this->conductAppealModel->insert([
                'incident_id'   => $incidentId,
                'student_id'    => (int) $incident['student_id'],
                'appeal_reason' => $reason,
                'appeal_status' => 'Pending',
                'submitted_date'=> date('Y-m-d H:i:s'),
            ]);

            $appealId = (int) $this->conductAppealModel->getInsertID();
            $this->handleAppealFileUploads($appealId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Your appeal has been submitted successfully.',
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ConductController::appeal] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // APPEALS — Staff appeal queue
    // ================================================================

    public function appeals()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_process_conduct_appeal')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Conduct Appeals', 'Conduct', 'Appeals');

        $schId  = $isSuperAdmin ? 0 : (int) $this->session->get('schID');
        $status = $this->request->getGet('status') ?: '';

        $filters = [];
        if ($status !== '') {
            $filters['appeal_status'] = $status;
        }

        $data['appeals']      = $this->conductAppealModel->getBySchool($schId, $filters);
        $data['statusFilter'] = $status;
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['_view']        = 'app/conduct/appeals';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // PROCESS APPEAL — Staff approve or reject
    // ================================================================

    public function processAppeal(int $appealId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_process_conduct_appeal')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $appeal = $this->conductAppealModel->find($appealId);
        if (!$appeal) {
            return $this->response->setJSON(['success' => false, 'message' => 'Appeal not found.']);
        }

        if ($appeal['appeal_status'] !== 'Pending') {
            return $this->response->setJSON(['success' => false, 'message' => 'This appeal has already been processed.']);
        }

        $decision     = $this->request->getPost('decision'); // 'Approved' or 'Rejected'
        $reviewNotes  = trim((string) $this->request->getPost('review_notes'));
        $ptsRestored  = (int) $this->request->getPost('points_restored');

        if (!in_array($decision, ['Approved', 'Rejected'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid decision.']);
        }

        try {
            $this->conductAppealModel->update($appealId, [
                'appeal_status'  => $decision,
                'reviewed_by'    => (int) $this->session->get('userID'),
                'reviewed_date'  => date('Y-m-d H:i:s'),
                'review_notes'   => $reviewNotes,
                'points_restored'=> $decision === 'Approved' ? $ptsRestored : 0,
            ]);

            if ($decision === 'Approved' && $ptsRestored > 0) {
                $incident      = $this->conductIncidentModel->find((int) $appeal['incident_id']);
                $currentPoints = (int) ($incident['points_awarded'] ?? 0);
                $newPoints     = $currentPoints + $ptsRestored;
                $this->conductIncidentModel->update((int) $appeal['incident_id'], [
                    'points_awarded' => $newPoints,
                    'is_resolved'    => 1,
                ]);
            } elseif ($decision === 'Approved') {
                $this->conductIncidentModel->update((int) $appeal['incident_id'], [
                    'is_resolved' => 1,
                ]);
            }

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Conduct Appeal Processed',
                'log_desc'    => 'Appeal ID ' . $appealId . ' ' . strtolower($decision),
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-shield-tick"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => $decision === 'Approved' ? 'success' : 'warning',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appeal ' . strtolower($decision) . ' successfully.',
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ConductController::processAppeal] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // PRIVATE — Handle multiple file uploads
    // ================================================================

    private function handleFileUploads(int $incidentId): void
    {
        $dir = $this->ensureConductUploadDir();

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        $uploadedFiles = $this->request->getFiles();

        if (empty($uploadedFiles) || empty($uploadedFiles['conduct_files'])) {
            return;
        }

        $files = $uploadedFiles['conduct_files'];

        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if (!$file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                continue;
            }

            if (!$file->isValid()) {
                log_message('warning', '[ConductController::handleFileUploads] Invalid file: ' . $file->getErrorString());
                continue;
            }

            if ($file->hasMoved()) {
                continue;
            }

            if ($file->getSize() > 10 * 1024 * 1024) {
                log_message('warning', '[ConductController::handleFileUploads] File too large: ' . $file->getClientName());
                continue;
            }

            $mime = $file->getMimeType();
            if (!in_array($mime, $allowedTypes)) {
                log_message('warning', '[ConductController::handleFileUploads] Rejected mime: ' . $mime . ' for file: ' . $file->getClientName());
                continue;
            }

            $ext     = $file->getExtension();
            $newName = 'conduct_' . $incidentId . '_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;

            try {
                $file->move($dir, $newName);

                $this->conductIncidentFileModel->insert([
                    'incident_id_fk' => $incidentId,
                    'file_src'       => $newName,
                    'file_type'      => $mime,
                ]);

                log_message('info', '[ConductController::handleFileUploads] Saved: ' . $newName . ' for incident ID ' . $incidentId);

            } catch (\Exception $e) {
                log_message('error', '[ConductController::handleFileUploads] Failed to move file: ' . $e->getMessage());
            }
        }
    }

    private function handleAppealFileUploads(int $appealId): void
    {
        $dir = $this->ensureAppealUploadDir();

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        $uploadedFiles = $this->request->getFiles();

        if (empty($uploadedFiles) || empty($uploadedFiles['appeal_files'])) {
            return;
        }

        $files = $uploadedFiles['appeal_files'];
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if (!$file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                continue;
            }
            if (!$file->isValid() || $file->hasMoved()) {
                continue;
            }
            if ($file->getSize() > 10 * 1024 * 1024) {
                log_message('warning', '[ConductController::handleAppealFileUploads] File too large: ' . $file->getClientName());
                continue;
            }
            $mime = $file->getMimeType();
            if (!in_array($mime, $allowedTypes)) {
                log_message('warning', '[ConductController::handleAppealFileUploads] Rejected mime: ' . $mime);
                continue;
            }

            $ext     = $file->getExtension();
            $newName = 'appeal_' . $appealId . '_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;

            try {
                $file->move($dir, $newName);
                $this->conductAppealFileModel->insert([
                    'appeal_id' => $appealId,
                    'file_src'  => $newName,
                    'file_type' => $mime,
                ]);
            } catch (\Exception $e) {
                log_message('error', '[ConductController::handleAppealFileUploads] ' . $e->getMessage());
            }
        }
    }

    // ================================================================
    // VIEW APPEAL FILE — Stream appeal document to browser
    // ================================================================

    public function viewAppealFile(int $fileId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $file = $this->conductAppealFileModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }

        $path = FCPATH . 'uploads/conduct_appeals/' . $file['file_src'];
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File does not exist on server.');
        }

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . $file['file_src'] . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
