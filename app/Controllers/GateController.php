<?php
namespace App\Controllers;

class GateController extends BaseController
{
    private array $passTypes = ['Early Leave', 'Late Entry', 'Half Day', 'Other'];

    // ================================================================
    // NOTIFICATIONS — in-app activity feed via UserLogModel.
    // ================================================================

    private function notifyUsers(array $userIds, string $title, string $desc, string $icon, string $theme): void
    {
        $now = date('Y-m-d H:i:s');
        foreach (array_unique(array_filter($userIds)) as $userId) {
            $this->userLogModel->insert([
                'user_id_fk'  => (int) $userId,
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'] ?? 'Desktop',
                'log_title'   => $title,
                'log_desc'    => $desc,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => $icon,
                'log_theme'   => $theme,
                'log_type'    => 'Alert',
                'log_status'  => 'Unread',
            ]);
        }
    }

    /** User IDs of staff at a school who hold a given gate permission code. */
    private function usersWithGatePermission(int $schId, string $permCode): array
    {
        $db = \Config\Database::connect();
        $rows = $db->query("
            SELECT DISTINCT s.user_id_fk
            FROM staff s
            WHERE s.sch_id_fk = ? AND s.staff_status = 'Active'
            AND s.user_id_fk IN (
                SELECT ur.user_id_fk
                FROM user_role ur
                INNER JOIN role_permission rp ON rp.role_id_fk = ur.role_id_fk
                INNER JOIN permission p ON p.perm_id = rp.perm_id_fk
                WHERE p.perm_code = ? AND ur.user_role_status = 'Active'
            )
        ", [$schId, $permCode])->getResultArray();

        return array_map(fn ($r) => (int) $r['user_id_fk'], $rows);
    }

    // ================================================================
    // INDEX — tabbed Gate Management screen.
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_access')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Gate Management', 'Gate Management', 'Overview');

        $userId    = (int) $this->session->get('userID');
        $schId     = $isSuperAdmin
            ? (int) ($this->request->getGet('sch_id') ?? 0)
            : (int) $this->session->get('schID');
        $canMark   = $isSuperAdmin || $this->grant_access('_gate_mark');
        $canApprove = $isSuperAdmin || $this->grant_access('_gate_pass_approve');
        $canReports = $isSuperAdmin || $this->grant_access('_gate_reports');
        $canManageAll = $isSuperAdmin || $this->grant_access('_gate_manage_all');
        $needsSchoolSelect = $isSuperAdmin || $schId === 0;

        $visitors = [];
        $entries  = [];
        $passes   = [];
        $settings = null;
        $formOptions = [];

        if ($schId > 0) {
            $visitors    = $this->gateVisitorModel->getForSchool($schId, null, 25);
            $entries     = $this->gateEntryLogModel->getForSchool($schId, 25);
            $passes      = $canApprove
                ? $this->gatePassModel->getForSchool($schId, null, 25)
                : $this->gatePassModel->getForUser($userId, 25);
            $settings    = $this->gateSettingModel->forSchool($schId);
            $formOptions = $this->gateFormOptionModel->getActiveGrouped($schId);
        }

        $data['visitors']          = $visitors;
        $data['entries']           = $entries;
        $data['passes']            = $passes;
        $data['settings']          = $settings;
        $data['formOptions']       = $formOptions;
        $data['passTypes']         = $this->passTypes;
        $data['canMark']           = $canMark;
        $data['canApprove']        = $canApprove;
        $data['canReports']        = $canReports;
        $data['canManageAll']      = $canManageAll;
        $data['isSuperAdmin']      = $isSuperAdmin;
        $data['needsSchoolSelect'] = $needsSchoolSelect;
        $data['allSchools']        = $needsSchoolSelect ? $this->getAllSchools() : [];
        $data['currentSchId']      = $schId;
        $data['myUserId']          = $userId;
        $data['_view']             = 'app/gate/index';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // VISITOR MANAGEMENT
    // ================================================================

    public function visitorStore()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_mark')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $schId = $isSuperAdmin ? (int) $this->request->getPost('sch_id') : (int) $this->session->get('schID');
            if ($schId <= 0) {
                throw new \InvalidArgumentException('Please select a school.');
            }

            $visitorName = trim((string) $this->request->getPost('visitor_name'));
            if ($visitorName === '') {
                throw new \InvalidArgumentException('Visitor name is required.');
            }

            $meetUserId = (int) $this->request->getPost('meet_user_id');
            $now        = date('Y-m-d H:i:s');

            $visitorId = $this->gateVisitorModel->insert([
                'sch_id_fk'              => $schId,
                'pass_number'            => $this->gateSettingModel->nextPassNumber($schId),
                'visitor_name'           => $visitorName,
                'visitor_phone'          => trim((string) $this->request->getPost('visitor_phone')) ?: null,
                'purpose'                => trim((string) $this->request->getPost('purpose')) ?: null,
                'meet_user_id_fk'        => $meetUserId > 0 ? $meetUserId : null,
                'meet_person_name'       => trim((string) $this->request->getPost('meet_person_name')) ?: null,
                'id_proof'               => trim((string) $this->request->getPost('id_proof')) ?: null,
                'items_carried'          => trim((string) $this->request->getPost('items_carried')) ?: null,
                'check_in_at'            => $now,
                'status'                 => 'Inside',
                'recorded_by_user_id_fk' => (int) $this->session->get('userID'),
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);

            $settings = $this->gateSettingModel->forSchool($schId);
            if ($meetUserId > 0 && !empty($settings['notify_on_visitor_checkin'])) {
                $this->notifyUsers(
                    [$meetUserId],
                    'Visitor Arrived',
                    esc($visitorName) . ' has arrived at the gate to see you.',
                    '<i class="ki-duotone ki-user-tick fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
                    'info'
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Visitor checked in.',
                'visitor' => $this->gateVisitorModel->getDetail((int) $visitorId),
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            log_message('error', '[GateController::visitorStore] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function visitorCheckout(int $visitorId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_mark')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $visitor = $this->gateVisitorModel->find($visitorId);
        if (!$visitor) {
            return $this->response->setJSON(['success' => false, 'message' => 'Visitor record not found.']);
        }
        if ($visitor['status'] === 'Checked Out') {
            return $this->response->setJSON(['success' => false, 'message' => 'Visitor already checked out.']);
        }

        $this->gateVisitorModel->checkOut($visitorId, (int) $this->session->get('userID'));

        return $this->response->setJSON(['success' => true, 'message' => 'Visitor checked out.']);
    }

    // ================================================================
    // USER ENTRY MANAGEMENT
    // ================================================================

    public function entryStore()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_mark')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $schId = $isSuperAdmin ? (int) $this->request->getPost('sch_id') : (int) $this->session->get('schID');
            if ($schId <= 0) {
                throw new \InvalidArgumentException('Please select a school.');
            }

            $targetUserId = (int) $this->request->getPost('user_id');
            if ($targetUserId <= 0) {
                throw new \InvalidArgumentException('Please select a user.');
            }

            $direction = $this->request->getPost('direction');
            if (!in_array($direction, ['In', 'Out'], true)) {
                throw new \InvalidArgumentException('Invalid direction.');
            }

            $userRole  = $this->userRoleModel->findActiveUserRole($targetUserId);
            $roleCatId = (int) ($userRole['role_cat_id_fk'] ?? 0);
            $now       = date('Y-m-d H:i:s');

            $entryId = $this->gateEntryLogModel->insert([
                'sch_id_fk'              => $schId,
                'user_id_fk'             => $targetUserId,
                'role_cat_id_fk'         => $roleCatId,
                'direction'              => $direction,
                'event_at'               => $now,
                'remarks'                => trim((string) $this->request->getPost('remarks')) ?: null,
                'recorded_by_user_id_fk' => (int) $this->session->get('userID'),
                'created_at'             => $now,
            ]);

            $entries = $this->gateEntryLogModel->getForSchool($schId, 1);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Gate entry recorded.',
                'entry'   => $entries[0] ?? null,
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            log_message('error', '[GateController::entryStore] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    /** GET /gate/search-users?sch_id=&q= — typeahead for visitor "meet" and entry-log targets. */
    public function searchUsers()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId = $isSuperAdmin ? (int) $this->request->getGet('sch_id') : (int) $this->session->get('schID');
        $search = trim((string) $this->request->getGet('q'));

        if ($schId <= 0 || $search === '') {
            return $this->response->setJSON(['success' => true, 'users' => []]);
        }

        $like = '%' . $search . '%';
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT DISTINCT u.user_id, u.fname, u.lname
            FROM users u
            WHERE (u.fname LIKE ? OR u.lname LIKE ?)
            AND u.user_id IN (
                SELECT user_id_fk FROM staff WHERE sch_id_fk = ? AND staff_status = 'Active'
                UNION
                SELECT user_id_fk FROM admission WHERE sch_id_fk = ? AND admission_status = 'Active'
            )
            ORDER BY u.fname
            LIMIT 20
        ", [$like, $like, $schId, $schId])->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'users'   => array_map(fn ($u) => [
                'userId' => (int) $u['user_id'],
                'name'   => trim($u['fname'] . ' ' . $u['lname']),
            ], $rows),
        ]);
    }

    // ================================================================
    // GATE PASS MANAGEMENT
    // ================================================================

    public function passStore()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_access')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $schId = $isSuperAdmin ? (int) $this->request->getPost('sch_id') : (int) $this->session->get('schID');
            if ($schId <= 0) {
                throw new \InvalidArgumentException('Please select a school.');
            }

            $forUserId = (int) $this->request->getPost('for_user_id') ?: (int) $this->session->get('userID');

            $passType = $this->request->getPost('pass_type');
            if (!in_array($passType, $this->passTypes, true)) {
                $passType = 'Other';
            }

            $reason = trim((string) $this->request->getPost('reason'));
            if ($reason === '') {
                throw new \InvalidArgumentException('Please provide a reason for the gate pass.');
            }

            $requestedTimeInput = $this->request->getPost('requested_time');
            $requestedTime = $requestedTimeInput ? date('Y-m-d H:i:s', strtotime($requestedTimeInput)) : date('Y-m-d H:i:s');

            $now = date('Y-m-d H:i:s');
            $passId = $this->gatePassModel->insert([
                'sch_id_fk'               => $schId,
                'pass_number'             => $this->gateSettingModel->nextPassNumber($schId),
                'for_user_id_fk'          => $forUserId,
                'requested_by_user_id_fk' => (int) $this->session->get('userID'),
                'pass_type'               => $passType,
                'reason'                  => $reason,
                'requested_time'          => $requestedTime,
                'status'                  => 'Pending',
                'created_at'              => $now,
                'updated_at'              => $now,
            ]);

            $settings = $this->gateSettingModel->forSchool($schId);
            if (!empty($settings['notify_on_pass_request'])) {
                $requester = $this->userModel->find((int) $this->session->get('userID'));
                $requesterName = $requester ? trim($requester['fname'] . ' ' . $requester['lname']) : 'A user';
                $this->notifyUsers(
                    $this->usersWithGatePermission($schId, '_gate_pass_approve'),
                    'Gate Pass Requested',
                    esc($requesterName) . ' requested a gate pass (' . esc($passType) . '): ' . esc($reason),
                    '<i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span></i>',
                    'warning'
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Gate pass requested.',
                'pass'    => $this->gatePassModel->getDetail((int) $passId),
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            log_message('error', '[GateController::passStore] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function passDecide(int $passId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_pass_approve')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $pass = $this->gatePassModel->find($passId);
        if (!$pass) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gate pass not found.']);
        }
        if ($pass['status'] !== 'Pending') {
            return $this->response->setJSON(['success' => false, 'message' => 'This pass has already been decided.']);
        }

        $status = $this->request->getPost('status');
        if (!in_array($status, ['Approved', 'Rejected'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid decision.']);
        }

        $this->gatePassModel->decide(
            $passId,
            $status,
            (int) $this->session->get('userID'),
            trim((string) $this->request->getPost('decision_remarks')) ?: null
        );

        $settings = $this->gateSettingModel->forSchool((int) $pass['sch_id_fk']);
        if (!empty($settings['notify_on_pass_decision'])) {
            $this->notifyUsers(
                [(int) $pass['requested_by_user_id_fk']],
                'Gate Pass ' . $status,
                'Your gate pass request (' . esc($pass['pass_number']) . ') was ' . strtolower($status) . '.',
                '<i class="ki-duotone ki-shield-tick fs-2"><span class="path1"></span><span class="path2"></span></i>',
                $status === 'Approved' ? 'success' : 'danger'
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Gate pass ' . strtolower($status) . '.',
            'pass'    => $this->gatePassModel->getDetail($passId),
        ]);
    }

    public function passMarkUsed(int $passId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_mark')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $pass = $this->gatePassModel->find($passId);
        if (!$pass) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gate pass not found.']);
        }
        if ($pass['status'] !== 'Approved') {
            return $this->response->setJSON(['success' => false, 'message' => 'Only approved passes can be marked as used.']);
        }

        $this->gatePassModel->markUsed($passId, (int) $this->session->get('userID'));

        return $this->response->setJSON(['success' => true, 'message' => 'Gate pass marked as used.']);
    }

    public function passCancel(int $passId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $pass = $this->gatePassModel->find($passId);
        if (!$pass) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gate pass not found.']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $isOwner = (int) $pass['requested_by_user_id_fk'] === (int) $this->session->get('userID');
        if (!$isSuperAdmin && !$isOwner && !$this->grant_access('_gate_manage_all')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($pass['status'] !== 'Pending') {
            return $this->response->setJSON(['success' => false, 'message' => 'Only pending passes can be cancelled.']);
        }

        $this->gatePassModel->cancel($passId);

        return $this->response->setJSON(['success' => true, 'message' => 'Gate pass cancelled.']);
    }

    // ================================================================
    // SETTINGS — pass prefix, notifications, form options.
    // ================================================================

    public function settingsSave()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_manage_all')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $schId = $isSuperAdmin ? (int) $this->request->getPost('sch_id') : (int) $this->session->get('schID');
        if ($schId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select a school.']);
        }

        $settings = $this->gateSettingModel->forSchool($schId);

        $prefix = trim((string) $this->request->getPost('pass_prefix'));
        if ($prefix === '') {
            $prefix = $settings['pass_prefix'];
        }

        $this->gateSettingModel->update($settings['gate_setting_id'], [
            'pass_prefix'               => mb_substr($prefix, 0, 10),
            'notify_on_visitor_checkin' => (int) (bool) $this->request->getPost('notify_on_visitor_checkin'),
            'notify_on_pass_request'    => (int) (bool) $this->request->getPost('notify_on_pass_request'),
            'notify_on_pass_decision'   => (int) (bool) $this->request->getPost('notify_on_pass_decision'),
            'updated_at'                => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Settings saved.',
            'settings' => $this->gateSettingModel->find($settings['gate_setting_id']),
        ]);
    }

    public function formOptionStore()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_manage_all')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $schId = $isSuperAdmin ? (int) $this->request->getPost('sch_id') : (int) $this->session->get('schID');
        $type  = $this->request->getPost('option_type');
        $label = trim((string) $this->request->getPost('option_label'));

        if ($schId <= 0 || !in_array($type, ['visit_purpose', 'pass_reason'], true) || $label === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Please provide a valid option.']);
        }

        $optionId = $this->gateFormOptionModel->insert([
            'sch_id_fk'     => $schId,
            'option_type'   => $type,
            'option_label'  => $label,
            'sort_order'    => 0,
            'option_status' => 'Active',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Option added.',
            'option'  => $this->gateFormOptionModel->find($optionId),
        ]);
    }

    public function formOptionDelete(int $optionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_manage_all')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $this->gateFormOptionModel->update($optionId, ['option_status' => 'Inactive']);

        return $this->response->setJSON(['success' => true, 'message' => 'Option removed.']);
    }

    // ================================================================
    // REPORTS — graph + tabular, "Privileged employee" tier only.
    // ================================================================

    public function reports()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_gate_reports')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $schId = $isSuperAdmin ? (int) $this->request->getGet('sch_id') : (int) $this->session->get('schID');
        if ($schId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select a school.']);
        }

        $days = max(7, min(90, (int) ($this->request->getGet('days') ?: 30)));

        return $this->response->setJSON([
            'success'      => true,
            'days'         => $days,
            'visitorDaily' => $this->gateVisitorModel->dailyCounts($schId, $days),
            'entryDaily'   => $this->gateEntryLogModel->dailyCounts($schId, $days),
            'passDaily'    => $this->gatePassModel->dailyCounts($schId, $days),
            'passByType'   => $this->gatePassModel->typeCounts($schId, $days),
            'insideNow'    => $this->gateVisitorModel->countInsideForSchool($schId),
            'pendingPasses'=> $this->gatePassModel->countPendingForSchool($schId),
        ]);
    }

    private function getAllSchools(): array
    {
        return \Config\Database::connect()
            ->table('school')
            ->select('sch_id, sch_name')
            ->where('sch_status', 'Active')
            ->orderBy('sch_name', 'ASC')
            ->get()->getResultArray();
    }
}
