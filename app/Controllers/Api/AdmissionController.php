<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\AdmissionModel;
use App\Models\ParentStudentModel;
use App\Models\SchoolModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class AdmissionController extends Controller
{
    protected $admissionModel;
    protected $parentStudentModel;
    protected $schoolModel;
    protected $userModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->admissionModel     = new AdmissionModel();
        $this->parentStudentModel = new ParentStudentModel();
        $this->schoolModel        = new SchoolModel();
        $this->userModel          = new UserModel();
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
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        $isSuperAdmin = $roleId === 1;
        $isAdmin      = $roleCatId === 7;
        // Super Admin or Admin only, per the Intake tab's access rule.
        $canAccess = $isSuperAdmin || $isAdmin;

        $user      = $this->userModel->find($myId);
        $isAParent = !empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1;
        $children  = $isAParent ? $this->parentStudentModel->getChildrenOf($myId) : [];

        return [
            'myId'         => $myId,
            'roleId'       => $roleId,
            'roleCatId'    => $roleCatId,
            'ownSchId'     => $ownSchId,
            'isSuperAdmin' => $isSuperAdmin,
            'canAccess'    => $canAccess,
            'isAParent'    => $isAParent,
            'children'     => $children,
        ];
    }

    private function permissionsOut(array $ctx): array
    {
        return [
            'canViewListing'          => $ctx['canAccess'],
            'canAdd'                  => $ctx['canAccess'],
            'canViewMyChildAdmission' => $ctx['isAParent'],
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
            'schoolId'      => isset($a['sch_id_fk']) ? (int) $a['sch_id_fk'] : null,
            'schoolName'    => $a['sch_name'] ?? null,
            'admissionDate' => $a['admission_date'] ?? null,
            'status'        => $a['admission_status'] ?? null,
            'note'          => $a['admission_note'] ?? null,
        ];
    }

    /**
     * GET /api/admission?scope=all|child&childId=&search=&status=&sch_id=&limit=&offset=
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
        $schId          = $ctx['isSuperAdmin'] ? $requestedSchId : ($ctx['ownSchId'] ?: null);

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
     */
    public function schools()
    {
        $ctx = $this->context();
        if (!$ctx['canAccess']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission.']);
        }

        $schools = $ctx['isSuperAdmin']
            ? array_map(fn ($s) => ['schId' => (int) $s['sch_id'], 'schName' => $s['sch_name']], $this->schoolModel->getAllSchool())
            : [];

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

        if (!$userId || !$schId || !$date) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'User, school and admission date are required.']);
        }

        // Non Super Admin — enforce their own school, same as the web flow.
        if (!$ctx['isSuperAdmin']) {
            $schId = $ctx['ownSchId'];
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
}
