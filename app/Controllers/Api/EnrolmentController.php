<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\EnrolmentModel;
use App\Models\ParentStudentModel;
use App\Models\SchoolModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class EnrolmentController extends Controller
{
    protected $enrolmentModel;
    protected $parentStudentModel;
    protected $schoolModel;
    protected $userModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->enrolmentModel     = new EnrolmentModel();
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
            'canViewMyChildEnrolment' => $ctx['isAParent'],
        ];
    }

    private function enrolmentOut(array $e): array
    {
        return [
            'id'           => (int) $e['enrol_id'],
            'admissionId'  => (int) $e['admission_id_fk'],
            'userName'     => trim(($e['fname'] ?? '') . ' ' . ($e['lname'] ?? '')),
            'userEmail'    => $e['email'] ?? null,
            'profilePhoto' => $e['profile_photo'] ?? null,
            'schoolId'     => isset($e['sch_id_fk']) ? (int) $e['sch_id_fk'] : (isset($e['sch_id']) ? (int) $e['sch_id'] : null),
            'schoolName'   => $e['sch_name'] ?? null,
            'streamId'     => isset($e['stream_id_fk']) ? (int) $e['stream_id_fk'] : (isset($e['stream_id']) ? (int) $e['stream_id'] : null),
            'streamName'   => $e['stream_name'] ?? null,
            'levelName'    => $e['level_name'] ?? null,
            'enrolDate'    => $e['enrol_date'] ?? null,
            'enrolTerm'    => $e['enrol_term'] ?? null,
            'enrolYear'    => isset($e['enrol_year']) ? (int) $e['enrol_year'] : null,
            'status'       => $e['enrol_status'] ?? null,
            'note'         => $e['enrol_note'] ?? null,
        ];
    }

    private function effectiveSchId(array $ctx, ?int $requestedSchId): ?int
    {
        return $ctx['isSuperAdmin'] ? $requestedSchId : ($ctx['ownSchId'] ?: null);
    }

    /**
     * GET /api/enrolment?scope=all|child&childId=&search=&status=&sch_id=&limit=&offset=
     */
    public function index()
    {
        $ctx   = $this->context();
        $scope = (string) ($this->request->getGet('scope') ?? 'all');

        if ($scope === 'child') {
            if (!$ctx['isAParent']) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to view child enrolments.']);
            }
            $childId  = $this->request->getGet('childId');
            $childIds = $childId !== null && $childId !== '' ? [(int) $childId] : array_column($ctx['children'], 'user_id');
            $rows     = $this->enrolmentModel->getChildEnrolments($childIds);

            return $this->response->setJSON([
                'success'     => true,
                'enrolments'  => array_map(fn ($e) => $this->enrolmentOut($e), $rows),
                'total'       => count($rows),
                'hasMore'     => false,
                'schools'     => [],
                'permissions' => $this->permissionsOut($ctx),
            ]);
        }

        if (!$ctx['canAccess']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to view enrolments.']);
        }

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $limit  = (int) ($this->request->getGet('limit') ?? 10);
        $limit  = max(5, min(50, $limit ?: 10));
        $offset = max(0, (int) ($this->request->getGet('offset') ?? 0));

        $requestedSchId = $this->request->getGet('sch_id');
        $requestedSchId = $requestedSchId !== null && $requestedSchId !== '' ? (int) $requestedSchId : null;
        $schId          = $this->effectiveSchId($ctx, $requestedSchId);

        $schools = [];
        if ($ctx['isSuperAdmin']) {
            $schools = array_map(
                fn ($s) => ['schId' => (int) $s['sch_id'], 'schName' => $s['sch_name'], 'schLogo' => $s['sch_logo'] ?: null],
                $this->schoolModel->getAllSchool()
            );
        }

        $total   = $this->enrolmentModel->countForApi($schId, $search, $status);
        $rows    = $this->enrolmentModel->getPageForApi($schId, $search, $status, $limit, $offset);
        $hasMore = ($offset + count($rows)) < $total;

        return $this->response->setJSON([
            'success'     => true,
            'enrolments'  => array_map(fn ($e) => $this->enrolmentOut($e), $rows),
            'total'       => $total,
            'hasMore'     => $hasMore,
            'schools'     => $schools,
            'permissions' => $this->permissionsOut($ctx),
        ]);
    }

    /**
     * GET /api/enrolment/streams?sch_id=
     */
    public function streams()
    {
        $ctx            = $this->context();
        $requestedSchId = $this->request->getGet('sch_id');
        $requestedSchId = $requestedSchId !== null && $requestedSchId !== '' ? (int) $requestedSchId : null;
        $schId          = $this->effectiveSchId($ctx, $requestedSchId);

        $streams = array_map(fn ($s) => [
            'streamId'   => (int) $s['stream_id'],
            'streamName' => $s['stream_name'] ?? null,
            'levelName'  => $s['level_name'] ?? null,
            'schName'    => $s['sch_name'] ?? null,
        ], $this->enrolmentModel->getStreams($schId));

        return $this->response->setJSON(['success' => true, 'streams' => $streams]);
    }

    /**
     * GET /api/enrolment/admissions?sch_id= — eligible-admission picker for the Add Enrolment form.
     */
    public function eligibleAdmissions()
    {
        $ctx = $this->context();
        if (!$ctx['canAccess']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission.']);
        }

        $requestedSchId = $this->request->getGet('sch_id');
        $requestedSchId = $requestedSchId !== null && $requestedSchId !== '' ? (int) $requestedSchId : null;
        $schId          = $this->effectiveSchId($ctx, $requestedSchId);

        $rows       = $this->enrolmentModel->getEligibleAdmissions($schId);
        $admissions = array_map(fn ($a) => [
            'admissionId' => (int) $a['admission_id'],
            'userName'    => trim(($a['fname'] ?? '') . ' ' . ($a['lname'] ?? '')),
            'email'       => $a['email'] ?? null,
            'schoolName'  => $a['sch_name'] ?? null,
        ], $rows);

        return $this->response->setJSON(['success' => true, 'admissions' => $admissions]);
    }

    /**
     * POST /api/enrolment — body (JSON): admission_id, stream_id, enrol_date, enrol_term, enrol_year, enrol_note?, enrol_status?
     */
    public function store()
    {
        $ctx = $this->context();
        if (!$ctx['canAccess']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to add an enrolment.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $admissionId = (int) ($body['admission_id'] ?? 0);
        $streamId    = (int) ($body['stream_id'] ?? 0);
        $date        = trim((string) ($body['enrol_date'] ?? ''));
        $term        = trim((string) ($body['enrol_term'] ?? ''));
        $year        = (int) ($body['enrol_year'] ?? date('Y'));
        $note        = $body['enrol_note'] ?? null;
        $status      = $body['enrol_status'] ?? 'Active';
        if (!in_array($status, ['Active', 'Inactive'], true)) {
            $status = 'Active';
        }

        if (!$admissionId || !$streamId || !$date || !$term) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Admission, stream, date and term are required.']);
        }

        $existing = $this->enrolmentModel->where('admission_id_fk', $admissionId)->where('enrol_status', 'Active')->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'This admission already has an active enrolment.']);
        }

        $enrolId = $this->enrolmentModel->addEnrolment([
            'admission_id_fk' => $admissionId,
            'stream_id_fk'    => $streamId,
            'enrol_date'      => $date,
            'enrol_time'      => time(),
            'enrol_term'      => $term,
            'enrol_year'      => $year,
            'enrol_note'      => $note ?: null,
            'enrol_status'    => $status,
        ]);

        if (!$enrolId) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to create enrolment.']);
        }

        return $this->response->setJSON([
            'success'   => true,
            'enrolment' => $this->enrolmentOut($this->enrolmentModel->getDetail((int) $enrolId)),
        ]);
    }
}
