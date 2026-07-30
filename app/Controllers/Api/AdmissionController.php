<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\AdmissionModel;
use App\Models\ParentStudentModel;
use App\Models\RolePermissionModel;
use App\Models\SchoolModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class AdmissionController extends Controller
{
    protected $admissionModel;
    protected $parentStudentModel;
    protected $rolePermissionModel;
    protected $schoolModel;
    protected $userModel;

    private const LEADERSHIP_ROLES = [
        'school_prefect', 'hostel_prefect',
        'head_boy', 'deputy_head_boy',
        'head_girl', 'deputy_head_girl',
    ];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->admissionModel     = new AdmissionModel();
        $this->parentStudentModel = new ParentStudentModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->schoolModel        = new SchoolModel();
        $this->userModel          = new UserModel();
    }

    private function grantAccess(int $roleId, string $permCode): bool
    {
        return !empty($this->rolePermissionModel->grant_role_access($roleId, $permCode));
    }

    /**
     * Gathers everything needed to scope/gate a request, once per call.
     */
    private function context(): array
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleId    = (int) ($claims['roleID'] ?? 0);
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);
        $claimSchId = (int) ($claims['schID'] ?? 0);

        $isSuperAdmin = $roleId === 1;
        $isAdmin      = $roleCatId === 7;
        $isTeacher    = $roleCatId === 3;
        $isStudent    = $roleCatId === 4;

        // Resolve "own school" fresh from an active admission (never trust the
        // JWT's schID unconditionally — it can go stale over the token's 30-day life).
        $activeAdmissions = $this->admissionModel->getAdmissionByUser($myId);
        $resolvedSchId    = !empty($activeAdmissions) ? (int) $activeAdmissions[0]['sch_id_fk'] : $claimSchId;

        // Super Admin, Admin or Teacher — Teacher/Admin scoped to their own school below.
        $canAccess = $isSuperAdmin || $isAdmin || $isTeacher;

        $user      = $this->userModel->find($myId);
        $isAParent = !empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1;
        $children  = $isAParent ? $this->parentStudentModel->getChildrenOf($myId) : [];

        return [
            'myId'          => $myId,
            'roleId'        => $roleId,
            'roleCatId'     => $roleCatId,
            'resolvedSchId' => $resolvedSchId,
            'isSuperAdmin'  => $isSuperAdmin,
            'isAdmin'       => $isAdmin,
            'isTeacher'     => $isTeacher,
            'isStudent'     => $isStudent,
            'canAccess'     => $canAccess,
            'isAParent'     => $isAParent,
            'children'      => $children,
        ];
    }

    private function permissionsOut(array $ctx): array
    {
        return [
            'canViewListing'          => $ctx['canAccess'],
            'canAdd'                  => $ctx['canAccess'],
            'canViewMyChildAdmission' => $ctx['isAParent'],
            'canViewMyAdmission'      => $ctx['isStudent'],
        ];
    }

    private function admissionOut(array $a): array
    {
        return [
            'id'            => (int) $a['admission_id'],
            'userId'        => (int) $a['user_id_fk'],
            'userName'      => trim(($a['fname'] ?? '') . ' ' . ($a['lname'] ?? '')),
            'userEmail'     => $a['email'] ?? null,
            'gender'        => $a['gender'] ?? null,
            'profilePhoto'  => $a['profile_photo'] ?? null,
            'roleName'      => $a['role_name'] ?? null,
            'roleCatName'   => $a['role_cat_name'] ?? null,
            'roleCatId'     => isset($a['role_cat_id']) ? (int) $a['role_cat_id'] : null,
            'schoolId'      => isset($a['sch_id_fk']) ? (int) $a['sch_id_fk'] : null,
            'schoolName'    => $a['sch_name'] ?? null,
            'admissionDate' => $a['admission_date'] ?? null,
            'status'        => $a['admission_status'] ?? null,
            'note'          => $a['admission_note'] ?? null,
        ];
    }

    /**
     * Whether the caller may view a given admission row (from getAdmissionDetail()).
     */
    private function canViewAdmission(array $ctx, array $a): bool
    {
        if ($ctx['isSuperAdmin']) {
            return true;
        }
        $schId  = (int) ($a['sch_id_fk'] ?? $a['sch_id'] ?? 0);
        $userId = (int) ($a['user_id_fk'] ?? $a['user_id'] ?? 0);

        if ($ctx['canAccess'] && $schId === $ctx['resolvedSchId']) {
            return true;
        }
        if ($ctx['isStudent'] && $userId === $ctx['myId']) {
            return true;
        }
        if ($ctx['isAParent'] && in_array($userId, array_column($ctx['children'], 'user_id'), true)) {
            return true;
        }
        return false;
    }

    /**
     * GET /api/admission?scope=all|child|mine&childId=&search=&status=&sch_id=&limit=&offset=
     */
    public function index()
    {
        $ctx   = $this->context();
        $scope = (string) ($this->request->getGet('scope') ?? 'all');

        if ($scope === 'child') {
            if (!$ctx['isAParent']) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to view child admissions.']);
            }
            $childId  = $this->request->getGet('childId');
            $childIds = $childId !== null && $childId !== '' ? [(int) $childId] : array_column($ctx['children'], 'user_id');
            $rows     = $this->admissionModel->getChildAdmissions($childIds);

            return $this->response->setJSON([
                'success'     => true,
                'admissions'  => array_map(fn ($a) => $this->admissionOut($a), $rows),
                'total'       => count($rows),
                'hasMore'     => false,
                'schools'     => [],
                'permissions' => $this->permissionsOut($ctx),
            ]);
        }

        if ($scope === 'mine') {
            if (!$ctx['isStudent']) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to view this.']);
            }
            $rows = $this->admissionModel->getChildAdmissions([$ctx['myId']]);

            return $this->response->setJSON([
                'success'     => true,
                'admissions'  => array_map(fn ($a) => $this->admissionOut($a), $rows),
                'total'       => count($rows),
                'hasMore'     => false,
                'schools'     => [],
                'permissions' => $this->permissionsOut($ctx),
            ]);
        }

        if (!$ctx['canAccess']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to view admissions.']);
        }

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $limit  = (int) ($this->request->getGet('limit') ?? 10);
        $limit  = max(5, min(50, $limit ?: 10));
        $offset = max(0, (int) ($this->request->getGet('offset') ?? 0));

        $requestedSchId = $this->request->getGet('sch_id');
        $requestedSchId = $requestedSchId !== null && $requestedSchId !== '' ? (int) $requestedSchId : null;
        $schId          = $ctx['isSuperAdmin'] ? $requestedSchId : ($ctx['resolvedSchId'] ?: null);

        $schools = [];
        if ($ctx['isSuperAdmin']) {
            $schools = array_map(
                fn ($s) => ['schId' => (int) $s['sch_id'], 'schName' => $s['sch_name'], 'schLogo' => $s['sch_logo'] ?: null],
                $this->schoolModel->getAllSchool()
            );
        }

        $total   = $this->admissionModel->countForApi($schId, $search, $status);
        $rows    = $this->admissionModel->getPageForApi($schId, $search, $status, $limit, $offset);
        $hasMore = ($offset + count($rows)) < $total;

        return $this->response->setJSON([
            'success'     => true,
            'admissions'  => array_map(fn ($a) => $this->admissionOut($a), $rows),
            'total'       => $total,
            'hasMore'     => $hasMore,
            'schools'     => $schools,
            'permissions' => $this->permissionsOut($ctx),
        ]);
    }

    /**
     * GET /api/admission/schools — school picker for the Add Admission form.
     * Super Admin gets every school; Admin/Teacher get only their own (single-item list).
     */
    public function schools()
    {
        $ctx = $this->context();
        if (!$ctx['canAccess']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission.']);
        }

        if ($ctx['isSuperAdmin']) {
            $schools = array_map(fn ($s) => ['schId' => (int) $s['sch_id'], 'schName' => $s['sch_name']], $this->schoolModel->getAllSchool());
        } else {
            $own     = $ctx['resolvedSchId'] ? $this->schoolModel->getSchool($ctx['resolvedSchId']) : null;
            $schools = $own ? [['schId' => (int) $own['sch_id'], 'schName' => $own['sch_name']]] : [];
        }

        return $this->response->setJSON(['success' => true, 'schools' => $schools]);
    }

    /**
     * GET /api/admission/users — eligible-user picker for the Add Admission form.
     */
    public function eligibleUsers()
    {
        $ctx = $this->context();
        if (!$ctx['canAccess']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission.']);
        }

        $rows  = $this->userModel->getUsersWithoutActiveAdmission();
        $users = array_map(fn ($u) => [
            'userId'      => (int) $u['user_id'],
            'name'        => trim(($u['fname'] ?? '') . ' ' . ($u['lname'] ?? '')),
            'email'       => $u['email'] ?? null,
            'roleName'    => $u['role_name'] ?? null,
            'roleCatName' => $u['role_cat_name'] ?? null,
        ], $rows);

        return $this->response->setJSON(['success' => true, 'users' => $users]);
    }

    /**
     * POST /api/admission — body (JSON): user_id, sch_id, admission_date, admission_status?, admission_note?
     */
    public function store()
    {
        $ctx = $this->context();
        if (!$ctx['canAccess']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to add an admission.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $userId = (int) ($body['user_id'] ?? 0);
        $schId  = (int) ($body['sch_id'] ?? 0);
        $date   = trim((string) ($body['admission_date'] ?? ''));
        $status = $body['admission_status'] ?? 'Active';
        $note   = $body['admission_note'] ?? null;
        if (!in_array($status, ['Active', 'Inactive'], true)) {
            $status = 'Active';
        }

        // Non Super Admin — enforce their own resolved school, same as the web flow.
        if (!$ctx['isSuperAdmin']) {
            $schId = $ctx['resolvedSchId'];
        }

        if (!$userId || !$schId || !$date) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'User, school and admission date are required.']);
        }

        $existing = $this->admissionModel->where('user_id_fk', $userId)->where('admission_status', 'Active')->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'This user already has an active admission.']);
        }

        $admissionId = $this->admissionModel->addAdmission([
            'user_id_fk'       => $userId,
            'sch_id_fk'        => $schId,
            'admission_date'   => $date,
            'admission_time'   => time(),
            'admission_note'   => $note ?: null,
            'admission_status' => $status,
        ]);

        if (!$admissionId) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to create admission.']);
        }

        return $this->response->setJSON([
            'success'   => true,
            'admission' => $this->admissionOut($this->admissionModel->getAdmissionDetail((int) $admissionId)),
        ]);
    }

    /**
     * GET /api/admission/{id} — full detail payload for the Admission Detail screen.
     */
    public function detail(int $id)
    {
        $ctx = $this->context();
        $a   = $this->admissionModel->getAdmissionDetail($id);
        if (!$a) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Admission not found.']);
        }
        if (!$this->canViewAdmission($ctx, $a)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this admission.']);
        }

        $canEdit   = $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_edit_admission');
        $canDelete = $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_remove_admission');

        $enrolments = array_map(fn ($e) => [
            'id'        => (int) $e['enrol_id'],
            'enrolDate' => $e['enrol_date'] ?? null,
            'term'      => $e['enrol_term'] ?? null,
            'year'      => isset($e['enrol_year']) ? (int) $e['enrol_year'] : null,
            'status'    => $e['enrol_status'] ?? null,
            'streamName' => $e['stream_name'] ?? null,
            'levelName'  => $e['level_name'] ?? null,
        ], $this->admissionModel->getEnrolmentsForAdmission($id));

        $roleCatId = isset($a['role_cat_id']) ? (int) $a['role_cat_id'] : null;
        $leadershipRole = $roleCatId === 4 ? $this->admissionModel->getLeadershipRole($id) : null;

        return $this->response->setJSON([
            'success'   => true,
            'canEdit'   => $canEdit,
            'canDelete' => $canDelete,
            'admission' => array_merge($this->admissionOut($a), [
                'dob'           => $a['dob'] ?? null,
                'phone'         => $a['phone'] ?? null,
                'address'       => $a['address'] ?? null,
                'schoolAddress' => $a['sch_address'] ?? null,
                'schoolPhone'   => $a['sch_phone'] ?? null,
                'schoolEmail'   => $a['sch_email'] ?? null,
                'schoolLogo'    => $a['sch_logo'] ?? null,
            ]),
            'enrolments'      => $enrolments,
            'showLeadership'  => $roleCatId === 4,
            'leadershipRole'  => $leadershipRole,
            'leadershipRoles' => self::LEADERSHIP_ROLES,
        ]);
    }

    /**
     * PUT /api/admission/{id} — body (JSON): sch_id?, admission_date, admission_status, admission_note?
     */
    public function update(int $id)
    {
        $ctx = $this->context();
        $a   = $this->admissionModel->getAdmissionDetail($id);
        if (!$a) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Admission not found.']);
        }
        if (!$this->canViewAdmission($ctx, $a)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this admission.']);
        }
        $canEdit = $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_edit_admission');
        if (!$canEdit) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to edit this admission.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $date   = trim((string) ($body['admission_date'] ?? ($a['admission_date'] ?? '')));
        $status = $body['admission_status'] ?? $a['admission_status'];
        $note   = $body['admission_note'] ?? $a['admission_note'];
        if (!in_array($status, ['Active', 'Inactive'], true)) {
            $status = 'Active';
        }

        $schId = (int) ($a['sch_id_fk'] ?? 0);
        if ($ctx['isSuperAdmin'] && isset($body['sch_id']) && (int) $body['sch_id']) {
            $schId = (int) $body['sch_id'];
        }

        if (!$date) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Admission date is required.']);
        }

        $this->admissionModel->updateAdmission($id, [
            'sch_id_fk'        => $schId,
            'admission_date'   => $date,
            'admission_status' => $status,
            'admission_note'   => $note ?: null,
        ]);

        return $this->response->setJSON([
            'success'   => true,
            'admission' => $this->admissionOut($this->admissionModel->getAdmissionDetail($id)),
        ]);
    }

    /**
     * DELETE /api/admission/{id}
     */
    public function delete(int $id)
    {
        $ctx = $this->context();
        $a   = $this->admissionModel->getAdmissionDetail($id);
        if (!$a) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Admission not found.']);
        }
        if (!$this->canViewAdmission($ctx, $a)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this admission.']);
        }
        $canDelete = $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_remove_admission');
        if (!$canDelete) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to delete this admission.']);
        }

        $db = \Config\Database::connect();
        $db->table('enrolment')->where('admission_id_fk', $id)->delete();
        $this->admissionModel->deleteAdmission($id);

        return $this->response->setJSON(['success' => true, 'message' => 'Admission deleted successfully.']);
    }

    /**
     * POST /api/admission/{id}/leadership — body (JSON): role (one of LEADERSHIP_ROLES, or null/empty to clear)
     */
    public function leadership(int $id)
    {
        $ctx = $this->context();
        $a   = $this->admissionModel->getAdmissionDetail($id);
        if (!$a) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Admission not found.']);
        }
        if (!$this->canViewAdmission($ctx, $a)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this admission.']);
        }
        $canEdit = $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_edit_admission');
        if (!$canEdit) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to edit this admission.']);
        }

        $body = $this->request->getJSON(true) ?? [];
        $role = trim((string) ($body['role'] ?? ''));
        if ($role !== '' && !in_array($role, self::LEADERSHIP_ROLES, true)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Invalid leadership role.']);
        }

        $this->admissionModel->saveLeadershipRole($id, $role ?: null);

        return $this->response->setJSON(['success' => true, 'leadershipRole' => $role ?: null]);
    }
}
