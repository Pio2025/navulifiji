<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\AdmissionModel;
use App\Models\ClassroomModel;
use App\Models\ClassroomStaffModel;
use App\Models\ParentStudentModel;
use App\Models\RolePermissionModel;
use App\Models\SchoolModel;
use CodeIgniter\Controller;

class ClassroomController extends Controller
{
    protected $classroomModel;
    protected $classroomStaffModel;
    protected $admissionModel;
    protected $parentStudentModel;
    protected $rolePermissionModel;
    protected $schoolModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->classroomModel      = new ClassroomModel();
        $this->classroomStaffModel = new ClassroomStaffModel();
        $this->admissionModel      = new AdmissionModel();
        $this->parentStudentModel  = new ParentStudentModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->schoolModel         = new SchoolModel();
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
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        $isSuperAdmin      = $roleId === 1;
        $canViewAllListing = $isSuperAdmin || $this->grantAccess($roleId, '_classroom_listing');
        $canAdd            = $isSuperAdmin || $this->grantAccess($roleId, '_add_classroom');

        $activeAdmissions   = $this->admissionModel->getAdmissionByUser($myId);
        $hasActiveAdmission = !empty($activeAdmissions);
        $admissionSchId     = $hasActiveAdmission ? (int) $activeAdmissions[0]['sch_id_fk'] : 0;

        $children  = $this->parentStudentModel->getChildrenOf($myId);
        $isAParent = $roleCatId === 6 || !empty($children);

        return [
            'myId'               => $myId,
            'roleId'             => $roleId,
            'roleCatId'          => $roleCatId,
            'ownSchId'           => $ownSchId,
            'isSuperAdmin'       => $isSuperAdmin,
            'canViewAllListing'  => $canViewAllListing,
            'canAdd'             => $canAdd,
            'hasActiveAdmission' => $hasActiveAdmission,
            'admissionSchId'     => $admissionSchId,
            'children'           => $children,
            'canViewMyClassroom' => in_array($roleCatId, [3, 4], true),
            'canViewMyChildClassroom' => $isAParent && !empty($children),
        ];
    }

    private function permissionsOut(array $ctx): array
    {
        return [
            'canAdd'                  => $ctx['canAdd'],
            'canViewMyClassroom'      => $ctx['canViewMyClassroom'],
            'canViewMyChildClassroom' => $ctx['canViewMyChildClassroom'],
            'hasActiveAdmission'      => $ctx['hasActiveAdmission'],
            'canViewAllListing'       => $ctx['canViewAllListing'],
        ];
    }

    private function classroomOut(array $c): array
    {
        $out = [
            'id'           => (int) $c['class_id'],
            'name'         => $c['class_name'],
            'year'         => (int) $c['class_year'],
            'status'       => $c['class_status'],
            'streamName'   => $c['stream_name'] ?? null,
            'levelName'    => $c['level_name'] ?? null,
            'schoolId'     => isset($c['sch_id']) ? (int) $c['sch_id'] : null,
            'schoolName'   => $c['sch_name'] ?? null,
            'studentCount' => (int) ($c['student_count'] ?? 0),
            'classTeacher' => $c['class_teacher'] ?? null,
        ];
        if (isset($c['child_user_id'])) {
            $out['childUserId'] = (int) $c['child_user_id'];
            $out['childName']   = $c['child_name'] ?? null;
        }
        return $out;
    }

    /**
     * Resolves the school a request is allowed to act on/filter by.
     * Returns null only when the caller can see "all schools" and asked for no specific one.
     */
    private function effectiveSchId(array $ctx, ?int $requestedSchId): ?int
    {
        if ($ctx['hasActiveAdmission']) {
            return $ctx['admissionSchId'];
        }
        if ($ctx['canViewAllListing']) {
            return $requestedSchId;
        }
        return $ctx['ownSchId'] ?: null;
    }

    private function canAccessClassroom(array $ctx, array $classroom): bool
    {
        if ($ctx['isSuperAdmin'] || $ctx['canViewAllListing']) {
            return true;
        }
        $classSchId = (int) ($classroom['sch_id'] ?? 0);
        if ($ctx['hasActiveAdmission']) {
            return $classSchId === $ctx['admissionSchId'];
        }
        return $classSchId === $ctx['ownSchId'];
    }

    /**
     * GET /api/classroom?scope=all|mine|child&childId=&search=&status=&sch_id=&limit=&offset=
     */
    public function index()
    {
        $ctx = $this->context();

        $scope   = (string) ($this->request->getGet('scope') ?? 'all');
        $search  = $this->request->getGet('search');
        $status  = $this->request->getGet('status');
        $limit   = (int) ($this->request->getGet('limit') ?? 10);
        $limit   = max(5, min(50, $limit ?: 10));
        $offset  = max(0, (int) ($this->request->getGet('offset') ?? 0));

        $requestedSchId = $this->request->getGet('sch_id');
        $requestedSchId = $requestedSchId !== null && $requestedSchId !== '' ? (int) $requestedSchId : null;
        $schId = $this->effectiveSchId($ctx, $requestedSchId);

        $schools = [];
        if ($ctx['canViewAllListing'] && !$ctx['hasActiveAdmission']) {
            $schools = array_map(
                fn ($s) => ['schId' => (int) $s['sch_id'], 'schName' => $s['sch_name'], 'schLogo' => $s['sch_logo'] ?: null],
                $this->schoolModel->getAllSchool()
            );
        }

        if ($scope === 'mine' && $ctx['canViewMyClassroom']) {
            $rows    = $this->classroomModel->getMyClassroomsForUser($ctx['myId'], $ctx['roleCatId']);
            $total   = count($rows);
            $hasMore = false;
        } elseif ($scope === 'child' && $ctx['canViewMyChildClassroom']) {
            $childId  = $this->request->getGet('childId');
            $childIds = $childId !== null && $childId !== ''
                ? [(int) $childId]
                : array_column($ctx['children'], 'user_id');
            $rows    = $this->classroomModel->getChildClassrooms($childIds);
            $total   = count($rows);
            $hasMore = false;
        } else {
            $total   = $this->classroomModel->countForApi($schId, $search, $status);
            $rows    = $this->classroomModel->getPageForApi($schId, $search, $status, $limit, $offset);
            $hasMore = ($offset + count($rows)) < $total;
        }

        return $this->response->setJSON([
            'success'     => true,
            'classrooms'  => array_map(fn ($c) => $this->classroomOut($c), $rows),
            'total'       => $total,
            'hasMore'     => $hasMore,
            'schools'     => $schools,
            'permissions' => $this->permissionsOut($ctx),
        ]);
    }

    /**
     * GET /api/classroom/streams?sch_id=
     */
    public function streams()
    {
        $ctx = $this->context();
        $requestedSchId = $this->request->getGet('sch_id');
        $requestedSchId = $requestedSchId !== null && $requestedSchId !== '' ? (int) $requestedSchId : null;
        $schId = $this->effectiveSchId($ctx, $requestedSchId);

        $streams = array_map(fn ($s) => [
            'stream_id'   => (int) $s['stream_id'],
            'stream_name' => $s['stream_name'] ?? null,
            'level_name'  => $s['level_name'] ?? null,
            'sch_name'    => $s['sch_name'] ?? null,
        ], $this->classroomModel->getStreamsForSchool($schId));

        return $this->response->setJSON([
            'success' => true,
            'streams' => $streams,
        ]);
    }

    /**
     * POST /api/classroom — body (JSON): stream_id, class_name, class_year, class_status?, sch_id?
     */
    public function store()
    {
        $ctx = $this->context();
        if (!$ctx['canAdd']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to add a classroom.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $streamId  = (int) ($body['stream_id'] ?? 0);
        $className = trim((string) ($body['class_name'] ?? ''));
        $classYear = (int) ($body['class_year'] ?? 0);
        $status    = $body['class_status'] ?? 'Active';
        if (!in_array($status, ['Active', 'Inactive', 'Archived'], true)) {
            $status = 'Active';
        }

        if (!$streamId || !$className || !$classYear) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Stream, class name and year are required.']);
        }
        if (!$this->classroomModel->isClassNameUnique($className)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'A classroom named "' . $className . '" already exists. Please use a unique name.']);
        }

        $now = date('Y-m-d H:i:s');
        $classId = $this->classroomModel->insert([
            'stream_id_fk'     => $streamId,
            'class_name'       => $className,
            'class_year'       => $classYear,
            'class_created_at' => $now,
            'class_updated_at' => $now,
            'class_created_by' => $ctx['myId'],
            'class_updated_by' => $ctx['myId'],
            'class_status'     => $status,
        ]);

        if (!$classId) {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Failed to create classroom.']);
        }

        return $this->response->setJSON([
            'success'   => true,
            'classroom' => $this->classroomOut($this->classroomModel->getDetail((int) $classId)),
        ]);
    }

    /**
     * GET /api/classroom/{id} — Overview tab payload.
     */
    public function detail(int $id)
    {
        $ctx       = $this->context();
        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        $subjects     = $this->classroomModel->getClassroomSubjectData($id);
        $subjectCount = count($subjects['core']) + array_sum(array_map('count', $subjects['optional']));
        $studentCount = count($this->classroomModel->getClassroomStudents($id));
        $classTeacher = $this->classroomStaffModel->getActiveByRole($id, 'Class Teacher');
        $lessonCount  = (int) (\Config\Database::connect()->query("
            SELECT COUNT(*) AS cnt
            FROM classroom_lesson cl
            INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
            WHERE cs.class_id_fk = ?
        ", [$id])->getRowArray()['cnt'] ?? 0);

        return $this->response->setJSON([
            'success'    => true,
            'canEdit'    => $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_edit_classroom'),
            'canDelete'  => $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_remove_classroom'),
            'classroom'  => [
                'id'          => (int) $classroom['class_id'],
                'name'        => $classroom['class_name'],
                'year'        => (int) $classroom['class_year'],
                'status'      => $classroom['class_status'],
                'streamId'    => isset($classroom['stream_id']) ? (int) $classroom['stream_id'] : null,
                'streamName'  => $classroom['stream_name'] ?? null,
                'levelName'   => $classroom['level_name'] ?? null,
                'schoolId'    => isset($classroom['sch_id']) ? (int) $classroom['sch_id'] : null,
                'schoolName'  => $classroom['sch_name'] ?? null,
                'schoolLogo'  => $classroom['sch_logo'] ?? null,
                'createdAt'   => $classroom['class_created_at'] ?? null,
                'updatedAt'   => $classroom['class_updated_at'] ?? null,
                'createdBy'   => trim(($classroom['creator_fname'] ?? '') . ' ' . ($classroom['creator_lname'] ?? '')),
                'updatedBy'   => trim(($classroom['updater_fname'] ?? '') . ' ' . ($classroom['updater_lname'] ?? '')),
                'subjectCount' => $subjectCount,
                'studentCount' => $studentCount,
                'lessonCount'  => $lessonCount,
                'classTeacher' => $classTeacher
                    ? trim(($classTeacher['fname'] ?? '') . ' ' . ($classTeacher['lname'] ?? ''))
                    : null,
                'classTeacherPhoto' => $classTeacher['profile_photo'] ?? null,
            ],
        ]);
    }

    /**
     * PUT /api/classroom/{id} — body (JSON): stream_id, class_name, class_year, class_status
     */
    public function update(int $id)
    {
        $ctx = $this->context();
        $canEdit = $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_edit_classroom');
        if (!$canEdit) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to edit this classroom.']);
        }

        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $streamId  = (int) ($body['stream_id'] ?? 0);
        $className = trim((string) ($body['class_name'] ?? ''));
        $classYear = (int) ($body['class_year'] ?? 0);
        $status    = $body['class_status'] ?? 'Active';
        if (!in_array($status, ['Active', 'Inactive', 'Archived'], true)) {
            $status = 'Active';
        }

        if (!$streamId || !$className || !$classYear) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Stream, class name and year are required.']);
        }
        if (!$this->classroomModel->isClassNameUnique($className, $id)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'A classroom named "' . $className . '" already exists. Please use a unique name.']);
        }

        $oldStatus = $classroom['class_status'] ?? '';

        $this->classroomModel->update($id, [
            'stream_id_fk'     => $streamId,
            'class_name'       => $className,
            'class_year'       => $classYear,
            'class_updated_at' => date('Y-m-d H:i:s'),
            'class_updated_by' => $ctx['myId'],
            'class_status'     => $status,
        ]);

        if ($status !== $oldStatus) {
            $db = \Config\Database::connect();

            $db->table('classroom_student')
               ->where('class_id_fk', $id)
               ->update(['class_stud_status' => $status]);

            $classSubIds = array_column(
                $db->table('classroom_subject')->select('class_sub_id')
                   ->where('class_id_fk', $id)->get()->getResultArray(),
                'class_sub_id'
            );
            if (!empty($classSubIds)) {
                $db->table('classroom_subject_teacher')
                   ->whereIn('class_sub_id_fk', $classSubIds)
                   ->update(['class_sub_teacher_status' => $status]);
            }

            $db->table('classroom_role')
               ->where('class_id_fk', $id)
               ->update(['cs_status' => $status]);
        }

        return $this->response->setJSON([
            'success'   => true,
            'classroom' => $this->classroomOut($this->classroomModel->getDetail($id)),
        ]);
    }

    /**
     * DELETE /api/classroom/{id}
     */
    public function delete(int $id)
    {
        $ctx = $this->context();
        $canDelete = $ctx['isSuperAdmin'] || $this->grantAccess($ctx['roleId'], '_remove_classroom');
        if (!$canDelete) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to delete this classroom.']);
        }

        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        $db = \Config\Database::connect();

        $staffCount = $db->table('classroom_role')
            ->where('class_id_fk', $id)
            ->where('cs_status', 'Active')
            ->countAllResults();
        if ($staffCount > 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Cannot delete this classroom — it has ' . $staffCount . ' staff member(s) assigned. Please remove all staff first.',
            ]);
        }

        $studentCount = $db->table('enrolment')
            ->where('stream_id_fk', $classroom['stream_id_fk'])
            ->where('enrol_year',   $classroom['class_year'])
            ->countAllResults();
        if ($studentCount > 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Cannot delete this classroom — it has ' . $studentCount . ' enrolled student(s). Please remove all students first.',
            ]);
        }

        $this->classroomModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Classroom deleted successfully.',
        ]);
    }

    /**
     * GET /api/classroom/{id}/subjects
     */
    public function subjects(int $id)
    {
        $ctx       = $this->context();
        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        $isTeacher     = $ctx['roleCatId'] === 3;
        $isStudent     = $ctx['roleCatId'] === 4;
        $canFullAccess = $isTeacher || $isStudent || $this->childLinkedToClassroom($ctx, $id);

        $subjects = $this->classroomModel->getClassroomSubjectData($id);
        if ($canFullAccess && $isTeacher) {
            $mine = fn ($s) => (int) ($s['teacher_id'] ?? 0) === $ctx['myId'];
            $subjects['core']     = array_values(array_filter($subjects['core'], $mine));
            $subjects['optional'] = array_map(
                fn ($group) => array_values(array_filter($group, $mine)),
                $subjects['optional']
            );
        }

        return $this->response->setJSON([
            'success'       => true,
            'canFullAccess' => $canFullAccess,
            'subjects'      => $subjects,
        ]);
    }

    /**
     * Whether one of the caller's linked children has an active enrolment in this classroom.
     */
    private function childLinkedToClassroom(array $ctx, int $classId): bool
    {
        $childIds = array_column($ctx['children'], 'user_id');
        if (empty($childIds)) {
            return false;
        }
        $ph  = implode(',', array_fill(0, count($childIds), '?'));
        $row = \Config\Database::connect()->query("
            SELECT COUNT(*) AS cnt FROM classroom_student
            WHERE class_id_fk = ? AND user_id_fk IN ($ph) AND class_stud_status = 'Active'
        ", [$classId, ...$childIds])->getRowArray();
        return (int) ($row['cnt'] ?? 0) > 0;
    }

    /**
     * GET /api/classroom/{id}/staff
     */
    public function staff(int $id)
    {
        $ctx       = $this->context();
        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        $rows = $this->classroomStaffModel->getByClassroom($id);
        $byRole = [
            'Class Teacher'           => null,
            'Assistant Class Teacher' => null,
            'Class Captain'           => null,
            'Assistant Class Captain' => null,
        ];
        foreach ($rows as $r) {
            if (($r['cs_status'] ?? '') !== 'Active') {
                continue;
            }
            $role = $r['cs_role'] ?? '';
            if (array_key_exists($role, $byRole)) {
                $byRole[$role] = [
                    'userId' => (int) $r['user_id_fk'],
                    'name'   => trim(($r['fname'] ?? '') . ' ' . ($r['lname'] ?? '')),
                    'photo'  => $r['profile_photo'] ?? null,
                ];
            }
        }

        return $this->response->setJSON(['success' => true, 'staff' => $byRole]);
    }

    /**
     * GET /api/classroom/{id}/students?limit=&offset=
     */
    public function students(int $id)
    {
        $ctx       = $this->context();
        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        $limit  = max(5, min(50, (int) ($this->request->getGet('limit') ?? 20) ?: 20));
        $offset = max(0, (int) ($this->request->getGet('offset') ?? 0));

        $all     = $this->classroomModel->getClassroomStudents($id);
        $total   = count($all);
        $page    = array_slice($all, $offset, $limit);
        $hasMore = ($offset + count($page)) < $total;

        return $this->response->setJSON([
            'success'  => true,
            'total'    => $total,
            'hasMore'  => $hasMore,
            'students' => array_map(fn ($s) => [
                'userId' => (int) $s['user_id'],
                'name'   => trim(($s['fname'] ?? '') . ' ' . ($s['lname'] ?? '')),
                'gender' => $s['gender'] ?? null,
                'photo'  => $s['profile_photo'] ?? null,
                'status' => $s['class_stud_status'] ?? null,
            ], $page),
        ]);
    }
}
