<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\GateEntryLogModel;
use App\Models\GateFormOptionModel;
use App\Models\GatePassModel;
use App\Models\GateSettingModel;
use App\Models\GateVisitorModel;
use App\Models\RolePermissionModel;
use App\Models\UserLogModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;
use CodeIgniter\Controller;

/**
 * Mobile Gate Management API — Visitor Management, User Entry Management,
 * and Gate Pass Management. Mirrors GateController's (web) permission model.
 */
class GateController extends Controller
{
    protected $gateVisitorModel;
    protected $gateEntryLogModel;
    protected $gatePassModel;
    protected $gateSettingModel;
    protected $gateFormOptionModel;
    protected $rolePermissionModel;
    protected $userRoleModel;
    protected $userLogModel;
    protected $userModel;

    private array $passTypes = ['Early Leave', 'Late Entry', 'Half Day', 'Other'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->gateVisitorModel    = new GateVisitorModel();
        $this->gateEntryLogModel   = new GateEntryLogModel();
        $this->gatePassModel       = new GatePassModel();
        $this->gateSettingModel    = new GateSettingModel();
        $this->gateFormOptionModel = new GateFormOptionModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->userRoleModel       = new UserRoleModel();
        $this->userLogModel        = new UserLogModel();
        $this->userModel           = new UserModel();

        $this->gateVisitorModel->ensureTables();
        $this->gateEntryLogModel->ensureTables();
        $this->gatePassModel->ensureTables();
        $this->gateSettingModel->ensureTables();
        $this->gateFormOptionModel->ensureTables();
    }

    // ─── permission helpers ─────────────────────────────────────────────

    private function grantAccess(int $roleId, string $permCode): bool
    {
        return $roleId === 1 || !empty($this->rolePermissionModel->grant_role_access($roleId, $permCode));
    }

    private function notifyUsers(array $userIds, string $title, string $desc, string $icon, string $theme): void
    {
        $now = date('Y-m-d H:i:s');
        foreach (array_unique(array_filter($userIds)) as $userId) {
            $this->userLogModel->insert([
                'user_id_fk'  => (int) $userId,
                'ip_aadress'  => $this->request->getIPAddress(),
                'user_agent'  => (string) $this->request->getUserAgent(),
                'user_device' => 'Mobile',
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

    private function canAccess(int $roleId): bool { return $this->grantAccess($roleId, '_gate_access'); }
    private function canMark(int $roleId): bool { return $this->grantAccess($roleId, '_gate_mark'); }
    private function canApprove(int $roleId): bool { return $this->grantAccess($roleId, '_gate_pass_approve'); }
    private function canReports(int $roleId): bool { return $this->grantAccess($roleId, '_gate_reports'); }
    private function canManageAll(int $roleId): bool { return $this->grantAccess($roleId, '_gate_manage_all'); }

    private function claims(): array
    {
        $claims = ApiAuth::claims();
        return [
            'userId'    => ApiAuth::userId(),
            'roleId'    => (int) ($claims['roleID'] ?? 0),
            'roleCatId' => (int) ($claims['roleCatID'] ?? 0),
            'schId'     => (int) ($claims['schID'] ?? 0),
        ];
    }

    private function visitorOut(array $v): array
    {
        return [
            'visitorId'    => (int) $v['visitor_id'],
            'passNumber'   => $v['pass_number'],
            'visitorName'  => $v['visitor_name'],
            'visitorPhone' => $v['visitor_phone'],
            'purpose'      => $v['purpose'],
            'meetName'     => trim(($v['meet_fname'] ?? '') . ' ' . ($v['meet_lname'] ?? '')) ?: $v['meet_person_name'],
            'status'       => $v['status'],
            'checkInAt'    => $v['check_in_at'],
            'checkOutAt'   => $v['check_out_at'],
            'recordedBy'   => trim(($v['recorded_by_fname'] ?? '') . ' ' . ($v['recorded_by_lname'] ?? '')),
        ];
    }

    private function entryOut(array $e): array
    {
        return [
            'entryId'    => (int) $e['entry_id'],
            'userId'     => (int) $e['user_id_fk'],
            'userName'   => trim(($e['fname'] ?? '') . ' ' . ($e['lname'] ?? '')),
            'direction'  => $e['direction'],
            'eventAt'    => $e['event_at'],
            'remarks'    => $e['remarks'],
            'recordedBy' => trim(($e['recorded_by_fname'] ?? '') . ' ' . ($e['recorded_by_lname'] ?? '')),
        ];
    }

    private function passOut(array $p, int $myId): array
    {
        return [
            'passId'          => (int) $p['pass_id'],
            'passNumber'      => $p['pass_number'],
            'forUserId'       => (int) $p['for_user_id_fk'],
            'forName'         => trim(($p['for_fname'] ?? '') . ' ' . ($p['for_lname'] ?? '')),
            'requestedByName' => trim(($p['requested_by_fname'] ?? '') . ' ' . ($p['requested_by_lname'] ?? '')),
            'passType'        => $p['pass_type'],
            'reason'          => $p['reason'],
            'requestedTime'   => $p['requested_time'],
            'status'          => $p['status'],
            'decidedByName'   => trim(($p['decided_by_fname'] ?? '') . ' ' . ($p['decided_by_lname'] ?? '')),
            'decisionRemarks' => $p['decision_remarks'],
            'usedAt'          => $p['used_at'],
            'createdAt'       => $p['created_at'],
            'isMine'          => (int) $p['requested_by_user_id_fk'] === $myId,
        ];
    }

    // ================================================================
    // GET /api/gate/summary
    // ================================================================
    public function summary()
    {
        $c = $this->claims();
        if (!$this->canAccess($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($c['schId'] <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No school associated with your account.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'insideVisitors' => $this->gateVisitorModel->countInsideForSchool($c['schId']),
            'pendingPasses'  => $this->gatePassModel->countPendingForSchool($c['schId']),
            'permissions' => [
                'canMark'      => $this->canMark($c['roleId']),
                'canApprove'   => $this->canApprove($c['roleId']),
                'canReports'   => $this->canReports($c['roleId']),
                'canManageAll' => $this->canManageAll($c['roleId']),
            ],
        ]);
    }

    // ================================================================
    // GET /api/gate/search-users?q=
    // ================================================================
    public function searchUsers()
    {
        $c = $this->claims();
        if (!$this->canMark($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $search = trim((string) $this->request->getGet('q'));
        if ($c['schId'] <= 0 || $search === '') {
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
        ", [$like, $like, $c['schId'], $c['schId']])->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'users'   => array_map(fn ($u) => [
                'userId' => (int) $u['user_id'],
                'name'   => trim($u['fname'] . ' ' . $u['lname']),
            ], $rows),
        ]);
    }

    // ================================================================
    // VISITOR MANAGEMENT
    // ================================================================

    public function visitors()
    {
        $c = $this->claims();
        if (!$this->canAccess($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($c['schId'] <= 0) {
            return $this->response->setJSON(['success' => true, 'visitors' => []]);
        }

        $status = $this->request->getGet('status') ?: null;
        $offset = max(0, (int) $this->request->getGet('offset'));

        $rows = $this->gateVisitorModel->getForSchool($c['schId'], $status, 21, $offset);
        $hasMore = count($rows) > 20;
        if ($hasMore) array_pop($rows);

        return $this->response->setJSON([
            'success'  => true,
            'hasMore'  => $hasMore,
            'canMark'  => $this->canMark($c['roleId']),
            'visitors' => array_map(fn ($v) => $this->visitorOut($v), $rows),
        ]);
    }

    public function createVisitor()
    {
        $c = $this->claims();
        if (!$this->canMark($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($c['schId'] <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'No school associated with your account.']);
        }

        $body = $this->request->getJSON(true) ?? [];
        $visitorName = trim((string) ($body['visitorName'] ?? ''));
        if ($visitorName === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Visitor name is required.']);
        }

        $meetUserId = (int) ($body['meetUserId'] ?? 0);
        $now = date('Y-m-d H:i:s');

        $visitorId = $this->gateVisitorModel->insert([
            'sch_id_fk'              => $c['schId'],
            'pass_number'            => $this->gateSettingModel->nextPassNumber($c['schId']),
            'visitor_name'           => $visitorName,
            'visitor_phone'          => trim((string) ($body['visitorPhone'] ?? '')) ?: null,
            'purpose'                => trim((string) ($body['purpose'] ?? '')) ?: null,
            'meet_user_id_fk'        => $meetUserId > 0 ? $meetUserId : null,
            'meet_person_name'       => trim((string) ($body['meetPersonName'] ?? '')) ?: null,
            'id_proof'               => trim((string) ($body['idProof'] ?? '')) ?: null,
            'items_carried'          => trim((string) ($body['itemsCarried'] ?? '')) ?: null,
            'check_in_at'            => $now,
            'status'                 => 'Inside',
            'recorded_by_user_id_fk' => $c['userId'],
            'created_at'             => $now,
            'updated_at'             => $now,
        ]);

        $settings = $this->gateSettingModel->forSchool($c['schId']);
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
            'visitor' => $this->visitorOut($this->gateVisitorModel->getDetail((int) $visitorId)),
        ]);
    }

    public function checkoutVisitor(int $visitorId)
    {
        $c = $this->claims();
        if (!$this->canMark($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $visitor = $this->gateVisitorModel->find($visitorId);
        if (!$visitor) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Visitor record not found.']);
        }
        if ($visitor['status'] === 'Checked Out') {
            return $this->response->setJSON(['success' => false, 'message' => 'Visitor already checked out.']);
        }

        $this->gateVisitorModel->checkOut($visitorId, $c['userId']);

        return $this->response->setJSON([
            'success' => true,
            'visitor' => $this->visitorOut($this->gateVisitorModel->getDetail($visitorId)),
        ]);
    }

    // ================================================================
    // USER ENTRY MANAGEMENT
    // ================================================================

    public function entries()
    {
        $c = $this->claims();
        if (!$this->canAccess($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($c['schId'] <= 0) {
            return $this->response->setJSON(['success' => true, 'entries' => []]);
        }

        $offset = max(0, (int) $this->request->getGet('offset'));
        $rows = $this->gateEntryLogModel->getForSchool($c['schId'], 21, $offset);
        $hasMore = count($rows) > 20;
        if ($hasMore) array_pop($rows);

        return $this->response->setJSON([
            'success' => true,
            'hasMore' => $hasMore,
            'canMark' => $this->canMark($c['roleId']),
            'entries' => array_map(fn ($e) => $this->entryOut($e), $rows),
        ]);
    }

    public function createEntry()
    {
        $c = $this->claims();
        if (!$this->canMark($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($c['schId'] <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'No school associated with your account.']);
        }

        $body = $this->request->getJSON(true) ?? [];
        $targetUserId = (int) ($body['userId'] ?? 0);
        $direction    = $body['direction'] ?? '';

        if ($targetUserId <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Please select a user.']);
        }
        if (!in_array($direction, ['In', 'Out'], true)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Invalid direction.']);
        }

        $userRole  = $this->userRoleModel->findActiveUserRole($targetUserId);
        $roleCatId = (int) ($userRole['role_cat_id_fk'] ?? 0);
        $now       = date('Y-m-d H:i:s');

        $entryId = $this->gateEntryLogModel->insert([
            'sch_id_fk'              => $c['schId'],
            'user_id_fk'             => $targetUserId,
            'role_cat_id_fk'         => $roleCatId,
            'direction'              => $direction,
            'event_at'               => $now,
            'remarks'                => trim((string) ($body['remarks'] ?? '')) ?: null,
            'recorded_by_user_id_fk' => $c['userId'],
            'created_at'             => $now,
        ]);

        $rows = $this->gateEntryLogModel->getForSchool($c['schId'], 1);

        return $this->response->setJSON([
            'success' => true,
            'entry'   => isset($rows[0]) ? $this->entryOut($rows[0]) : null,
        ]);
    }

    // ================================================================
    // GATE PASS MANAGEMENT
    // ================================================================

    public function passes()
    {
        $c = $this->claims();
        if (!$this->canAccess($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $status  = $this->request->getGet('status') ?: null;
        $offset  = max(0, (int) $this->request->getGet('offset'));
        $canApprove = $this->canApprove($c['roleId']);

        $rows = $canApprove
            ? $this->gatePassModel->getForSchool($c['schId'], $status, 21, $offset)
            : $this->gatePassModel->getForUser($c['userId'], 21, $offset);

        $hasMore = count($rows) > 20;
        if ($hasMore) array_pop($rows);

        return $this->response->setJSON([
            'success'    => true,
            'hasMore'    => $hasMore,
            'canApprove' => $canApprove,
            'canMark'    => $this->canMark($c['roleId']),
            'passTypes'  => $this->passTypes,
            'passes'     => array_map(fn ($p) => $this->passOut($p, $c['userId']), $rows),
        ]);
    }

    public function createPass()
    {
        $c = $this->claims();
        if (!$this->canAccess($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($c['schId'] <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'No school associated with your account.']);
        }

        $body = $this->request->getJSON(true) ?? [];
        $forUserId = (int) ($body['forUserId'] ?? 0) ?: $c['userId'];
        $passType  = in_array($body['passType'] ?? '', $this->passTypes, true) ? $body['passType'] : 'Other';
        $reason    = trim((string) ($body['reason'] ?? ''));

        if ($reason === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Please provide a reason for the gate pass.']);
        }

        $requestedTimeInput = $body['requestedTime'] ?? null;
        $requestedTime = $requestedTimeInput ? date('Y-m-d H:i:s', strtotime($requestedTimeInput)) : date('Y-m-d H:i:s');

        $now = date('Y-m-d H:i:s');
        $passId = $this->gatePassModel->insert([
            'sch_id_fk'               => $c['schId'],
            'pass_number'             => $this->gateSettingModel->nextPassNumber($c['schId']),
            'for_user_id_fk'          => $forUserId,
            'requested_by_user_id_fk' => $c['userId'],
            'pass_type'               => $passType,
            'reason'                  => $reason,
            'requested_time'          => $requestedTime,
            'status'                  => 'Pending',
            'created_at'              => $now,
            'updated_at'              => $now,
        ]);

        $settings = $this->gateSettingModel->forSchool($c['schId']);
        if (!empty($settings['notify_on_pass_request'])) {
            $requester = $this->userModel->find($c['userId']);
            $requesterName = $requester ? trim($requester['fname'] . ' ' . $requester['lname']) : 'A user';
            $this->notifyUsers(
                $this->usersWithGatePermission($c['schId'], '_gate_pass_approve'),
                'Gate Pass Requested',
                esc($requesterName) . ' requested a gate pass (' . esc($passType) . '): ' . esc($reason),
                '<i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span></i>',
                'warning'
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'pass'    => $this->passOut($this->gatePassModel->getDetail((int) $passId), $c['userId']),
        ]);
    }

    public function decidePass(int $passId)
    {
        $c = $this->claims();
        if (!$this->canApprove($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $pass = $this->gatePassModel->find($passId);
        if (!$pass) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Gate pass not found.']);
        }
        if ($pass['status'] !== 'Pending') {
            return $this->response->setJSON(['success' => false, 'message' => 'This pass has already been decided.']);
        }

        $body   = $this->request->getJSON(true) ?? [];
        $status = $body['status'] ?? '';
        if (!in_array($status, ['Approved', 'Rejected'], true)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Invalid decision.']);
        }

        $this->gatePassModel->decide($passId, $status, $c['userId'], trim((string) ($body['decisionRemarks'] ?? '')) ?: null);

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
            'pass'    => $this->passOut($this->gatePassModel->getDetail($passId), $c['userId']),
        ]);
    }

    public function markPassUsed(int $passId)
    {
        $c = $this->claims();
        if (!$this->canMark($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $pass = $this->gatePassModel->find($passId);
        if (!$pass) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Gate pass not found.']);
        }
        if ($pass['status'] !== 'Approved') {
            return $this->response->setJSON(['success' => false, 'message' => 'Only approved passes can be marked as used.']);
        }

        $this->gatePassModel->markUsed($passId, $c['userId']);

        return $this->response->setJSON([
            'success' => true,
            'pass'    => $this->passOut($this->gatePassModel->getDetail($passId), $c['userId']),
        ]);
    }

    public function cancelPass(int $passId)
    {
        $c = $this->claims();
        $pass = $this->gatePassModel->find($passId);
        if (!$pass) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Gate pass not found.']);
        }

        $isOwner = (int) $pass['requested_by_user_id_fk'] === $c['userId'];
        if (!$isOwner && !$this->canManageAll($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($pass['status'] !== 'Pending') {
            return $this->response->setJSON(['success' => false, 'message' => 'Only pending passes can be cancelled.']);
        }

        $this->gatePassModel->cancel($passId);

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // REPORTS — "Privileged employee" tier only.
    // ================================================================

    public function reports()
    {
        $c = $this->claims();
        if (!$this->canReports($c['roleId'])) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }
        if ($c['schId'] <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No school associated with your account.']);
        }

        $days = max(7, min(90, (int) ($this->request->getGet('days') ?: 30)));

        return $this->response->setJSON([
            'success'      => true,
            'days'         => $days,
            'visitorDaily' => $this->gateVisitorModel->dailyCounts($c['schId'], $days),
            'entryDaily'   => $this->gateEntryLogModel->dailyCounts($c['schId'], $days),
            'passDaily'    => $this->gatePassModel->dailyCounts($c['schId'], $days),
            'passByType'   => $this->gatePassModel->typeCounts($c['schId'], $days),
            'insideNow'    => $this->gateVisitorModel->countInsideForSchool($c['schId']),
            'pendingPasses'=> $this->gatePassModel->countPendingForSchool($c['schId']),
        ]);
    }

    // ================================================================
    // GET /api/gate/form-options — dropdown lists for visitor purpose / pass reason.
    // ================================================================
    public function formOptions()
    {
        $c = $this->claims();
        if (!$this->canAccess($c['roleId']) || $c['schId'] <= 0) {
            return $this->response->setJSON(['success' => true, 'visitPurpose' => [], 'passReason' => []]);
        }

        $grouped = $this->gateFormOptionModel->getActiveGrouped($c['schId']);

        return $this->response->setJSON([
            'success'      => true,
            'visitPurpose' => array_map(fn ($o) => $o['option_label'], $grouped['visit_purpose']),
            'passReason'   => array_map(fn ($o) => $o['option_label'], $grouped['pass_reason']),
        ]);
    }
}
