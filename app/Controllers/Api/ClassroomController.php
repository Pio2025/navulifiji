<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\AdmissionModel;
use App\Models\ClassDiscussionModel;
use App\Models\ClassroomLessonModel;
use App\Models\ClassroomModel;
use App\Models\ClassroomStaffModel;
use App\Models\LessonDiscussionModel;
use App\Models\LessonQuizzeAttemptModel;
use App\Models\LessonQuizzeModel;
use App\Models\ParentStudentModel;
use App\Models\PublicHolidayModel;
use App\Models\RolePermissionModel;
use App\Models\SchoolCategoryConfigModel;
use App\Models\SchoolCategoryTermModel;
use App\Models\SchoolModel;
use App\Models\StudentAttendanceModel;
use App\Models\SubjectFeedbackModel;
use App\Models\TermExamModel;
use App\Models\UserLogModel;
use App\Libraries\RealtimeNotifier;
use CodeIgniter\Controller;

class ClassroomController extends Controller
{
    protected $classroomModel;
    protected $classroomStaffModel;
    protected $admissionModel;
    protected $parentStudentModel;
    protected $rolePermissionModel;
    protected $schoolModel;
    protected $studentAttendanceModel;
    protected $termExamModel;
    protected $classDiscussionModel;
    protected $classroomLessonModel;
    protected $lessonDiscussionModel;
    protected $lessonQuizzeModel;
    protected $lessonQuizzeAttemptModel;
    protected $subjectFeedbackModel;
    protected $schoolCategoryConfigModel;
    protected $schoolCategoryTermModel;
    protected $publicHolidayModel;
    protected $userLogModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userLogModel              = new UserLogModel();
        $this->classroomModel           = new ClassroomModel();
        $this->classroomStaffModel      = new ClassroomStaffModel();
        $this->admissionModel           = new AdmissionModel();
        $this->parentStudentModel       = new ParentStudentModel();
        $this->rolePermissionModel      = new RolePermissionModel();
        $this->schoolModel              = new SchoolModel();
        $this->studentAttendanceModel   = new StudentAttendanceModel();
        $this->termExamModel            = new TermExamModel();
        $this->classDiscussionModel     = new ClassDiscussionModel();
        $this->classroomLessonModel     = new ClassroomLessonModel();
        $this->lessonDiscussionModel    = new LessonDiscussionModel();
        $this->lessonQuizzeModel        = new LessonQuizzeModel();
        $this->lessonQuizzeAttemptModel = new LessonQuizzeAttemptModel();
        $this->subjectFeedbackModel     = new SubjectFeedbackModel();
        $this->schoolCategoryConfigModel = new SchoolCategoryConfigModel();
        $this->schoolCategoryTermModel   = new SchoolCategoryTermModel();
        $this->publicHolidayModel        = new PublicHolidayModel();
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

        $childId = $this->request->getGet('childId');
        if ($childId !== null && $childId !== '') {
            $childId = (int) $childId;
            if (!in_array($childId, $this->linkedChildIdsInClassroom($ctx, $id), true)) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'That child is not enrolled in this classroom.']);
            }
            $subjectIds = $this->studentSubjectIds($id, $childId);
            $inIds = fn ($s) => in_array((int) ($s['sch_sub_id'] ?? 0), $subjectIds, true);
            $subjects['core']     = array_values(array_filter($subjects['core'], $inIds));
            $subjects['optional'] = array_filter(
                array_map(fn ($group) => array_values(array_filter($group, $inIds)), $subjects['optional']),
                fn ($group) => !empty($group)
            );
        }

        return $this->response->setJSON([
            'success'       => true,
            'canFullAccess' => $canFullAccess,
            'subjects'      => $subjects,
        ]);
    }

    /**
     * IDs of the caller's linked children who have an active enrolment in this classroom.
     */
    private function linkedChildIdsInClassroom(array $ctx, int $classId): array
    {
        $childIds = array_column($ctx['children'], 'user_id');
        if (empty($childIds)) {
            return [];
        }
        $ph   = implode(',', array_fill(0, count($childIds), '?'));
        $rows = \Config\Database::connect()->query("
            SELECT user_id_fk FROM classroom_student
            WHERE class_id_fk = ? AND user_id_fk IN ($ph) AND class_stud_status = 'Active'
        ", [$classId, ...$childIds])->getResultArray();
        return array_map(fn ($r) => (int) $r['user_id_fk'], $rows);
    }

    /**
     * Whether one of the caller's linked children has an active enrolment in this classroom.
     */
    private function childLinkedToClassroom(array $ctx, int $classId): bool
    {
        return !empty($this->linkedChildIdsInClassroom($ctx, $classId));
    }

    /**
     * sch_sub_id values of the subjects a given student is actively enrolled in for a classroom.
     */
    private function studentSubjectIds(int $classId, int $childUserId): array
    {
        $rows = \Config\Database::connect()->query("
            SELECT ss.sch_sub_id_fk
            FROM student_subject ss
            INNER JOIN admission ad ON ad.admission_id = ss.admission_id_fk
            WHERE ss.class_id_fk = ? AND ad.user_id_fk = ? AND ss.stud_sub_status = 'Active'
        ", [$classId, $childUserId])->getResultArray();
        return array_map(fn ($r) => (int) $r['sch_sub_id_fk'], $rows);
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

    /**
     * GET /api/classroom/{id}/attendance — read-only, scoped to the viewer.
     */
    public function attendance(int $id)
    {
        $ctx       = $this->context();
        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        $streamId    = (int) ($classroom['stream_id'] ?? 0);
        $isTeacher   = $ctx['roleCatId'] === 3;
        $isStudent   = $ctx['roleCatId'] === 4;
        $childIds    = $this->linkedChildIdsInClassroom($ctx, $id);
        $privileged  = $isTeacher || $ctx['isSuperAdmin'] || $ctx['canViewAllListing'];

        if ($privileged) {
            return $this->response->setJSON([
                'success' => true,
                'mode'    => 'summary',
                'summary' => $this->summarizeAttendance($this->studentAttendanceModel->getAttendanceDatesForStream($streamId)),
            ]);
        }

        if (!empty($childIds)) {
            $children = [];
            foreach ($ctx['children'] as $child) {
                $childId = (int) $child['user_id'];
                if (!in_array($childId, $childIds, true)) {
                    continue;
                }
                $rows = $this->studentAttendanceModel->getStudentDailyAttendance($childId, $streamId);
                $children[] = [
                    'childUserId' => $childId,
                    'childName'   => trim(($child['fname'] ?? '') . ' ' . ($child['lname'] ?? '')),
                    'records'     => array_map(fn ($r) => [
                        'date'   => $r['attendance_date'] ?? null,
                        'status' => $r['attendance_status'] ?? null,
                    ], $rows),
                ];
            }
            return $this->response->setJSON(['success' => true, 'mode' => 'children', 'children' => $children]);
        }

        if ($isStudent) {
            $rows = $this->studentAttendanceModel->getStudentDailyAttendance($ctx['myId'], $streamId);
            return $this->response->setJSON([
                'success' => true,
                'mode'    => 'self',
                'records' => array_map(fn ($r) => [
                    'date'   => $r['attendance_date'] ?? null,
                    'status' => $r['attendance_status'] ?? null,
                ], $rows),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'mode'    => 'summary',
            'summary' => $this->summarizeAttendance($this->studentAttendanceModel->getAttendanceDatesForStream($streamId)),
        ]);
    }

    private function summarizeAttendance(array $dateRows): array
    {
        $present = 0;
        $absent  = 0;
        $days    = [];
        foreach ($dateRows as $r) {
            $present += (int) ($r['present_count'] ?? 0);
            $absent  += (int) ($r['absent_count'] ?? 0);
            $days[]   = [
                'date'         => $r['attendance_date'] ?? null,
                'studentCount' => (int) ($r['student_count'] ?? 0),
                'presentCount' => (int) ($r['present_count'] ?? 0),
                'absentCount'  => (int) ($r['absent_count'] ?? 0),
            ];
        }
        return [
            'totalPresent' => $present,
            'totalAbsent'  => $absent,
            'days'         => $days,
        ];
    }

    /**
     * GET /api/classroom/{id}/exam — read-only, scoped to the viewer.
     */
    public function exam(int $id)
    {
        $ctx       = $this->context();
        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        $isTeacher  = $ctx['roleCatId'] === 3;
        $isStudent  = $ctx['roleCatId'] === 4;
        $childIds   = $this->linkedChildIdsInClassroom($ctx, $id);
        $privileged = $isTeacher || $ctx['isSuperAdmin'] || $ctx['canViewAllListing'];

        if ($privileged) {
            $terms = [];
            for ($term = 1; $term <= 3; $term++) {
                $terms[$term] = [
                    'marks' => $this->termExamModel->getAllMarksForClassTerm($id, $term),
                    'stats' => $this->termExamModel->getClassStats($id, $term, 0),
                ];
            }
            return $this->response->setJSON(['success' => true, 'mode' => 'summary', 'terms' => $terms]);
        }

        if (!empty($childIds)) {
            $children = [];
            foreach ($ctx['children'] as $child) {
                $childId = (int) $child['user_id'];
                if (!in_array($childId, $childIds, true)) {
                    continue;
                }
                $terms = [];
                for ($term = 1; $term <= 3; $term++) {
                    $terms[$term] = $this->examReportOut($id, $childId, $term);
                }
                $children[] = [
                    'childUserId' => $childId,
                    'childName'   => trim(($child['fname'] ?? '') . ' ' . ($child['lname'] ?? '')),
                    'terms'       => $terms,
                ];
            }
            return $this->response->setJSON(['success' => true, 'mode' => 'children', 'children' => $children]);
        }

        if ($isStudent) {
            $terms = [];
            for ($term = 1; $term <= 3; $term++) {
                $terms[$term] = $this->examReportOut($id, $ctx['myId'], $term);
            }
            return $this->response->setJSON(['success' => true, 'mode' => 'self', 'terms' => $terms]);
        }

        $terms = [];
        for ($term = 1; $term <= 3; $term++) {
            $terms[$term] = [
                'marks' => $this->termExamModel->getAllMarksForClassTerm($id, $term),
                'stats' => $this->termExamModel->getClassStats($id, $term, 0),
            ];
        }
        return $this->response->setJSON(['success' => true, 'mode' => 'summary', 'terms' => $terms]);
    }

    /**
     * Self/child term report, gated: unpublished reports are hidden from the student/parent view.
     */
    private function examReportOut(int $classId, int $studentId, int $term): array
    {
        $report = $this->termExamModel->getStudentReport($classId, $studentId, $term);
        if (($report['status'] ?? '') !== 'published') {
            return ['published' => false, 'report' => null, 'stats' => null];
        }
        return [
            'published' => true,
            'report'    => $report,
            'stats'     => $this->termExamModel->getClassStats($classId, $term, $studentId),
        ];
    }

    /**
     * GET /api/classroom/{id}/discussion — classroom-shared post feed.
     */
    public function discussion(int $id)
    {
        $ctx       = $this->context();
        $classroom = $this->classroomModel->getDetail($id);
        if (!$classroom) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Classroom not found.']);
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have access to this classroom.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'posts'   => $this->classDiscussionModel->getPosts($id, $ctx['myId']),
        ]);
    }

    /**
     * Resolves and access-checks a classroom_subject id (class_sub_id). Returns either
     * an 'error' key (status+message, to be returned as-is) or the resolved context.
     */
    private function resolveSubjectAccess(int $classSubId): array
    {
        $ctx = $this->context();

        $cs = \Config\Database::connect()->table('classroom_subject')
            ->select('class_id_fk')->where('class_sub_id', $classSubId)->get()->getRowArray();
        if (!$cs) {
            return ['error' => ['status' => 404, 'message' => 'Subject not found.']];
        }

        $classId   = (int) $cs['class_id_fk'];
        $classroom = $this->classroomModel->getDetail($classId);
        if (!$classroom) {
            return ['error' => ['status' => 404, 'message' => 'Classroom not found.']];
        }
        if (!$this->canAccessClassroom($ctx, $classroom)) {
            return ['error' => ['status' => 403, 'message' => 'You do not have access to this classroom.']];
        }

        $isTeacher = $ctx['roleCatId'] === 3;
        $isStudent = $ctx['roleCatId'] === 4;
        $childIds  = $this->linkedChildIdsInClassroom($ctx, $classId);

        if (!$isTeacher && !$isStudent && empty($childIds)) {
            return ['error' => ['status' => 403, 'message' => 'You do not have access to this subject.']];
        }

        return [
            'ctx'       => $ctx,
            'classId'   => $classId,
            'isTeacher' => $isTeacher,
            'isStudent' => $isStudent,
            'childIds'  => $childIds,
        ];
    }

    private function errorResponse(array $r)
    {
        return $this->response->setStatusCode($r['error']['status'])->setJSON(['success' => false, 'message' => $r['error']['message']]);
    }

    /**
     * GET /api/classroom/subject/{classSubId}/dashboard — read-only class-wide analytics
     * (same aggregate data the web app computes for both the teacher and student dashboard views).
     */
    public function subjectDashboard(int $classSubId)
    {
        $r = $this->resolveSubjectAccess($classSubId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }

        return $this->response->setJSON([
            'success' => true,
            'stats'   => $this->classroomLessonModel->getDashboardStats($classSubId),
        ]);
    }

    /**
     * GET /api/classroom/subject/{classSubId}/lessons — read-only published lesson list.
     */
    public function subjectLessons(int $classSubId)
    {
        $r = $this->resolveSubjectAccess($classSubId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }

        return $this->response->setJSON([
            'success' => true,
            'lessons' => $this->classroomLessonModel->getLessonsForSubject($classSubId),
        ]);
    }

    /**
     * GET /api/classroom/subject/{classSubId}/lessons/calendar?term= — term/week/day
     * lesson calendar, driven by school_category_config + sch_cat_term_entry (mirrors
     * the term-grid date math already used by AttendanceController::_showTermGrid).
     */
    public function subjectLessonsCalendar(int $classSubId)
    {
        $r = $this->resolveSubjectAccess($classSubId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }

        $classroom  = $this->classroomModel->getDetail($r['classId']);
        $schId      = (int) ($classroom['sch_id'] ?? 0);
        $schCatData = $this->loadSchCatDataForSchool($schId);
        $termLabel  = $schCatData['label'];
        $terms      = $schCatData['terms'];

        if (empty($terms)) {
            return $this->response->setJSON([
                'success'      => true,
                'termLabel'    => $termLabel,
                'terms'        => [],
                'selectedTerm' => 0,
                'currentTerm'  => 0,
                'weeks'        => [],
            ]);
        }

        $termNums     = array_keys($terms);
        $currentTerm  = $this->resolveCurrentTermNum($terms);
        $requestedTerm = (int) ($this->request->getGet('term') ?? 0);
        $selectedTerm  = ($requestedTerm && isset($terms[$requestedTerm])) ? $requestedTerm : $currentTerm;

        $termsOut = array_map(
            fn ($n) => ['termNum' => $n, 'numWeeks' => (int) $terms[$n]['num_of_week']],
            $termNums
        );

        $termInfo   = $terms[$selectedTerm];
        $numWeeks   = (int) $termInfo['num_of_week'];
        $startDay   = (int) $termInfo['term_start_day'];
        $startMonth = (int) $termInfo['term_start_month'];

        $startDt = \DateTime::createFromFormat('Y-n-j', date('Y') . '-' . $startMonth . '-' . $startDay);
        if (!$startDt || $numWeeks <= 0) {
            return $this->response->setJSON([
                'success'      => true,
                'termLabel'    => $termLabel,
                'terms'        => $termsOut,
                'selectedTerm' => $selectedTerm,
                'currentTerm'  => $currentTerm,
                'weeks'        => [],
            ]);
        }

        $dayNames   = ['M', 'T', 'W', 'TH', 'F'];
        $weekDates  = [];
        $allDates   = [];
        for ($w = 0; $w < $numWeeks; $w++) {
            foreach ($dayNames as $di => $dn) {
                $dt = clone $startDt;
                $dt->modify('+' . ($w * 7 + $di) . ' days');
                $dateStr           = $dt->format('Y-m-d');
                $weekDates[$w][$di] = $dateStr;
                $allDates[]         = $dateStr;
            }
        }

        $holidays   = $this->publicHolidayModel->getByDates($allDates, $schId);
        $lessonRows = $this->classroomLessonModel->getLessonsForSubjectTerm($classSubId, $selectedTerm);

        $byBucket = [];
        foreach ($lessonRows as $lr) {
            $week = $lr['lesson_week'] !== null ? (int) $lr['lesson_week'] : null;
            $day  = $lr['lesson_day']  !== null ? (int) $lr['lesson_day']  : null;
            if ($week === null || $day === null || $day < 1 || $day > 5 || $week < 1 || $week > $numWeeks) {
                continue;
            }
            $key = ($week - 1) . '-' . ($day - 1);
            $byBucket[$key][] = [
                'lessonId'        => (int) $lr['lesson_id'],
                'title'           => $lr['lesson_title'],
                'desc'            => $lr['lesson_desc'],
                'fileCount'       => (int) $lr['file_count'],
                'videoCount'      => (int) $lr['video_count'],
                'linkCount'       => (int) $lr['link_count'],
                'assessmentCount' => (int) $lr['assessment_count'],
            ];
        }

        $weeks = [];
        for ($w = 0; $w < $numWeeks; $w++) {
            $days = [];
            foreach ($dayNames as $di => $dn) {
                $dateStr    = $weekDates[$w][$di];
                $dayLessons = $byBucket[$w . '-' . $di] ?? [];
                $days[] = [
                    'date'            => $dateStr,
                    'dayIndex'        => $di,
                    'isHoliday'       => isset($holidays[$dateStr]),
                    'holidayName'     => $holidays[$dateStr] ?? null,
                    'lessonCount'     => count($dayLessons),
                    'fileCount'       => array_sum(array_column($dayLessons, 'fileCount')),
                    'videoCount'      => array_sum(array_column($dayLessons, 'videoCount')),
                    'linkCount'       => array_sum(array_column($dayLessons, 'linkCount')),
                    'assessmentCount' => array_sum(array_column($dayLessons, 'assessmentCount')),
                    'lessons'         => $dayLessons,
                ];
            }
            $weeks[] = ['weekNum' => $w + 1, 'days' => $days];
        }

        return $this->response->setJSON([
            'success'      => true,
            'termLabel'    => $termLabel,
            'terms'        => $termsOut,
            'selectedTerm' => $selectedTerm,
            'currentTerm'  => $currentTerm,
            'weeks'        => $weeks,
        ]);
    }

    private function loadSchCatDataForSchool(int $schId): array
    {
        if (!$schId) {
            return ['label' => 'Term', 'terms' => []];
        }
        $school = \Config\Database::connect()->table('school')
            ->select('sch_cat_id_fk')->where('sch_id', $schId)->get()->getRowArray();
        if (!$school || empty($school['sch_cat_id_fk'])) {
            return ['label' => 'Term', 'terms' => []];
        }
        $config = $this->schoolCategoryConfigModel->getByCategoryId((int) $school['sch_cat_id_fk']);
        if (!$config) {
            return ['label' => 'Term', 'terms' => []];
        }
        $rows  = $this->schoolCategoryTermModel->getByConfigId((int) $config['sch_cat_con_id']);
        $terms = [];
        foreach ($rows as $t) {
            $terms[(int) $t['term_num']] = [
                'num_of_week'      => (int) $t['num_of_week'],
                'term_start_day'   => (int) $t['term_start_day'],
                'term_start_month' => (int) $t['term_start_month'],
                'term_end_day'     => (int) $t['term_end_day'],
                'term_end_month'   => (int) $t['term_end_month'],
            ];
        }
        ksort($terms);
        return ['label' => $config['label_for_term'] ?: 'Term', 'terms' => $terms];
    }

    /**
     * First term whose end date (this calendar year) is still >= today; falls back to
     * the last configured term if today is past every term's end date.
     */
    private function resolveCurrentTermNum(array $terms): int
    {
        $today = new \DateTime('today');
        $year  = (int) date('Y');
        foreach ($terms as $num => $t) {
            $end = \DateTime::createFromFormat('Y-n-j', $year . '-' . $t['term_end_month'] . '-' . $t['term_end_day']);
            if ($end && $today <= $end) {
                return $num;
            }
        }
        $termNums = array_keys($terms);
        return (int) end($termNums);
    }

    /**
     * GET /api/classroom/subject/{classSubId}/assignments — read-only, scoped to the viewer.
     */
    public function subjectAssignments(int $classSubId)
    {
        $r = $this->resolveSubjectAccess($classSubId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx = $r['ctx'];

        if (!empty($r['childIds'])) {
            $children = [];
            foreach ($ctx['children'] as $child) {
                $childId = (int) $child['user_id'];
                if (!in_array($childId, $r['childIds'], true)) {
                    continue;
                }
                $children[] = [
                    'childUserId' => $childId,
                    'childName'   => trim(($child['fname'] ?? '') . ' ' . ($child['lname'] ?? '')),
                    'assignments' => $this->subjectAssignmentsFor($classSubId, $childId),
                ];
            }
            return $this->response->setJSON(['success' => true, 'mode' => 'children', 'children' => $children]);
        }

        if ($r['isStudent']) {
            return $this->response->setJSON([
                'success'     => true,
                'mode'        => 'self',
                'assignments' => $this->subjectAssignmentsFor($classSubId, $ctx['myId']),
            ]);
        }

        return $this->response->setJSON([
            'success'     => true,
            'mode'        => 'summary',
            'assignments' => $this->subjectAssignmentsSummary($classSubId),
        ]);
    }

    private function subjectAssignmentsFor(int $classSubId, int $studentId): array
    {
        return \Config\Database::connect()->query("
            SELECT a.assignment_id, a.assignment_name, a.assignment_due_date, a.assignment_total_score,
                   asub.submission_id, asub.submission_status, asub.submitted_at, asub.grade
            FROM lesson_assignment a
            LEFT JOIN assignment_submission asub
                   ON asub.assignment_id_fk = a.assignment_id AND asub.user_id_fk = ?
            WHERE a.class_sub_id_fk = ? AND a.assignment_status = 'Published'
            ORDER BY a.assignment_due_date ASC
        ", [$studentId, $classSubId])->getResultArray();
    }

    private function subjectAssignmentsSummary(int $classSubId): array
    {
        return \Config\Database::connect()->query("
            SELECT a.assignment_id, a.assignment_name, a.assignment_due_date, a.assignment_total_score,
                   (SELECT COUNT(*) FROM assignment_submission s WHERE s.assignment_id_fk = a.assignment_id) AS submitted_count
            FROM lesson_assignment a
            WHERE a.class_sub_id_fk = ? AND a.assignment_status = 'Published'
            ORDER BY a.assignment_due_date ASC
        ", [$classSubId])->getResultArray();
    }

    /**
     * GET /api/classroom/subject/{classSubId}/assignment/{assignmentId} — assignment files,
     * class-wide stats (submissions, % passed, average, high/low), and the caller's (or a
     * linked child's) own submission/score + rank — mirroring the quiz score screen.
     */
    public function subjectAssignmentDetail(int $classSubId, int $assignmentId)
    {
        $r = $this->resolveSubjectAccess($classSubId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx = $r['ctx'];

        $db = \Config\Database::connect();
        $assignment = $db->query("
            SELECT assignment_id, assignment_name, assignment_due_date, assignment_total_score,
                   assignment_status, assignment_file
            FROM lesson_assignment
            WHERE assignment_id = ? AND class_sub_id_fk = ?
            LIMIT 1
        ", [$assignmentId, $classSubId])->getRowArray();

        if (!$assignment || (!$r['isTeacher'] && $assignment['assignment_status'] !== 'Published')) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Assignment not found.']);
        }

        $totalScore = (float) ($assignment['assignment_total_score'] ?? 100);

        $fileRows = $db->table('lesson_assignment_file')->where('assignment_id_fk', $assignmentId)->get()->getResultArray();
        if (empty($fileRows) && !empty($assignment['assignment_file'])) {
            $fileRows[] = ['assign_file_id' => null, 'file_src' => $assignment['assignment_file'], 'file_type' => null];
        }
        $files = array_map(fn ($f) => [
            'id'  => $f['assign_file_id'] !== null ? (int) $f['assign_file_id'] : null,
            'url' => base_url('uploads/assignments/' . $f['file_src']),
            'ext' => strtolower(pathinfo($f['file_src'], PATHINFO_EXTENSION)),
        ], $fileRows);

        $classStats = $db->query("
            SELECT COUNT(*) AS total_submitted, SUM(submission_status = 'Late') AS total_late
            FROM assignment_submission WHERE assignment_id_fk = ?
        ", [$assignmentId])->getRowArray();

        $scoreStats = $db->query("
            SELECT ROUND(AVG(assignment_mark), 2) AS avg_mark,
                   MAX(assignment_mark) AS high_mark,
                   MIN(assignment_mark) AS low_mark,
                   COUNT(*) AS graded_count,
                   SUM(assignment_mark >= ?) AS passed_count
            FROM student_assignment_score WHERE assignment_id_fk = ?
        ", [$totalScore * 0.5, $assignmentId])->getRowArray();

        $gradedCount = (int) ($scoreStats['graded_count'] ?? 0);
        $result = [
            'success'    => true,
            'assignment' => [
                'id'         => (int) $assignment['assignment_id'],
                'name'       => $assignment['assignment_name'],
                'dueDate'    => $assignment['assignment_due_date'],
                'totalScore' => $totalScore,
                'files'      => $files,
            ],
            'stats' => [
                'totalSubmitted' => (int) ($classStats['total_submitted'] ?? 0),
                'totalLate'      => (int) ($classStats['total_late'] ?? 0),
                'gradedCount'    => $gradedCount,
                'avgMark'        => $scoreStats['avg_mark'] !== null ? (float) $scoreStats['avg_mark'] : null,
                'avgPct'         => ($scoreStats['avg_mark'] !== null && $totalScore > 0)
                    ? round(((float) $scoreStats['avg_mark'] / $totalScore) * 100, 1) : null,
                'highMark'       => $scoreStats['high_mark'] !== null ? (float) $scoreStats['high_mark'] : null,
                'lowMark'        => $scoreStats['low_mark'] !== null ? (float) $scoreStats['low_mark'] : null,
                'passedCount'    => (int) ($scoreStats['passed_count'] ?? 0),
                'passedPct'      => $gradedCount > 0 ? round(((int) $scoreStats['passed_count'] / $gradedCount) * 100, 1) : null,
            ],
        ];

        $targetUserId = null;
        if (!empty($r['childIds'])) {
            $childId = $this->request->getGet('childId');
            if ($childId !== null && $childId !== '') {
                $childId = (int) $childId;
                if (!in_array($childId, $r['childIds'], true)) {
                    return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'That child is not enrolled in this classroom.']);
                }
                $targetUserId = $childId;
            } elseif (count($r['childIds']) === 1) {
                $targetUserId = $r['childIds'][0];
            } else {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'childId is required.', 'children' => $r['childIds']]);
            }
        } elseif ($r['isStudent']) {
            $targetUserId = $ctx['myId'];
        }

        if ($targetUserId === null) {
            $result['mode'] = 'summary';
            return $this->response->setJSON($result);
        }

        $submission = $db->table('assignment_submission')
            ->where('assignment_id_fk', $assignmentId)->where('user_id_fk', $targetUserId)
            ->get()->getRowArray();

        $score = $submission ? $db->table('student_assignment_score')
            ->where('assignment_id_fk', $assignmentId)->where('user_id_fk', $targetUserId)
            ->get()->getRowArray() : null;

        $position = null;
        if ($score) {
            $higher = $db->query("
                SELECT COUNT(*) AS c FROM student_assignment_score
                WHERE assignment_id_fk = ? AND assignment_mark > ?
            ", [$assignmentId, $score['assignment_mark']])->getRowArray();
            $position = 1 + (int) $higher['c'];
        }

        $mark = $score ? (float) $score['assignment_mark'] : null;
        $result['mode'] = 'self';
        $result['submission'] = $submission ? [
            'id'          => (int) $submission['submission_id'],
            'status'      => $submission['submission_status'],
            'submittedAt' => $submission['submitted_at'],
            'file'        => $submission['submission_file'] ? [
                'url' => base_url('uploads/assignment_submissions/' . $submission['submission_file']),
                'ext' => strtolower($submission['submission_file_type'] ?? pathinfo($submission['submission_file'], PATHINFO_EXTENSION)),
            ] : null,
        ] : null;
        $result['score'] = $score ? [
            'mark'     => $mark,
            'pct'      => $totalScore > 0 ? round(($mark / $totalScore) * 100, 1) : null,
            'feedback' => $score['feedback'],
            'gradedAt' => $score['graded_at'],
            'position' => $position,
            'outOf'    => $gradedCount,
        ] : null;

        return $this->response->setJSON($result);
    }

    /**
     * GET /api/classroom/subject/{classSubId}/feedback — read-only, scoped to the viewer.
     * Teachers see class averages + the feedback list; students/parents see one rating.
     */
    public function subjectFeedback(int $classSubId)
    {
        $r = $this->resolveSubjectAccess($classSubId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx = $r['ctx'];

        if ($r['isTeacher']) {
            return $this->response->setJSON([
                'success'   => true,
                'mode'      => 'summary',
                'averages'  => $this->subjectFeedbackModel->getSubjectAverages($classSubId),
                'feedbacks' => $this->subjectFeedbackModel->getClassFeedbacks($classSubId, true),
            ]);
        }

        if (!empty($r['childIds'])) {
            $children = [];
            foreach ($ctx['children'] as $child) {
                $childId = (int) $child['user_id'];
                if (!in_array($childId, $r['childIds'], true)) {
                    continue;
                }
                $children[] = [
                    'childUserId'  => $childId,
                    'childName'    => trim(($child['fname'] ?? '') . ' ' . ($child['lname'] ?? '')),
                    'childPhoto'   => $child['profile_photo'] ?? null,
                    'feedback'     => $this->subjectFeedbackModel->getStudentFeedback($classSubId, $childId),
                ];
            }
            return $this->response->setJSON(['success' => true, 'mode' => 'children', 'children' => $children]);
        }

        return $this->response->setJSON([
            'success'  => true,
            'mode'     => 'self',
            'feedback' => $this->subjectFeedbackModel->getStudentFeedback($classSubId, $ctx['myId']),
        ]);
    }

    /**
     * POST /api/classroom/subject/{classSubId}/feedback — the enrolled student rates their own subject.
     * Not available to teachers or parents viewing on a child's behalf.
     */
    public function subjectFeedbackStore(int $classSubId)
    {
        $r = $this->resolveSubjectAccess($classSubId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        if (!$r['isStudent']) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Only the enrolled student can submit feedback.']);
        }
        $ctx = $r['ctx'];

        $body       = $this->request->getJSON(true) ?? [];
        $overall    = (int) ($body['overall_rating'] ?? 0);
        $teaching   = (int) ($body['teaching_rating'] ?? 0);
        $content    = (int) ($body['content_rating'] ?? 0);
        $engagement = (int) ($body['engagement_rating'] ?? 0);
        $comment    = trim((string) ($body['comment'] ?? ''));
        $anonymous  = !empty($body['is_anonymous']);

        if ($overall < 1 || $overall > 5) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Overall rating is required (1-5 stars).']);
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

        $existing = $this->subjectFeedbackModel->getStudentFeedback($classSubId, $ctx['myId']);

        $row = [
            'class_sub_id_fk'   => $classSubId,
            'class_id_fk'       => $r['classId'],
            'student_id_fk'     => $ctx['myId'],
            'teacher_id_fk'     => $teacher['teacher_id'] ?? null,
            'sch_sub_id_fk'     => $teacher['sch_sub_id'] ?? null,
            'overall_rating'    => $overall,
            'teaching_rating'   => $teaching,
            'content_rating'    => $content,
            'engagement_rating' => $engagement,
            'comment'           => $comment ?: null,
            'is_anonymous'      => $anonymous ? 1 : 0,
        ];

        $ok = $this->subjectFeedbackModel->upsert($row, $existing['feedback_id'] ?? null);

        return $this->response->setJSON([
            'success'  => (bool) $ok,
            'message'  => $existing ? 'Feedback updated. Thank you!' : 'Thank you for your feedback!',
            'feedback' => $this->subjectFeedbackModel->getStudentFeedback($classSubId, $ctx['myId']),
        ]);
    }

    // ================================================================
    // LESSON DETAIL + LESSON DISCUSSION (mobile "My Classroom"/"My Child Classroom" flow)
    // ================================================================

    /**
     * Resolves and access-checks a classroom_lesson id. Returns either an 'error'
     * key or the resolved subjectAccess context plus 'lessonId'/'classSubId'.
     */
    private function resolveLessonAccess(int $lessonId): array
    {
        $lesson = \Config\Database::connect()->table('classroom_lesson')
            ->select('class_sub_id_fk')->where('lesson_id', $lessonId)->get()->getRowArray();
        if (!$lesson) {
            return ['error' => ['status' => 404, 'message' => 'Lesson not found.']];
        }

        $r = $this->resolveSubjectAccess((int) $lesson['class_sub_id_fk']);
        if (isset($r['error'])) {
            return $r;
        }

        $r['lessonId']  = $lessonId;
        $r['classSubId'] = (int) $lesson['class_sub_id_fk'];
        return $r;
    }

    private function resolveDiscussionAccess(int $discussionId): array
    {
        $discussion = \Config\Database::connect()->table('lesson_discussion')
            ->select('lesson_id_fk')->where('lesson_discussion_id', $discussionId)->get()->getRowArray();
        if (!$discussion) {
            return ['error' => ['status' => 404, 'message' => 'Discussion not found.']];
        }

        $r = $this->resolveLessonAccess((int) $discussion['lesson_id_fk']);
        if (isset($r['error'])) {
            return $r;
        }
        $r['discussionId'] = $discussionId;
        return $r;
    }

    private function resolveCommentAccess(int $commentId): array
    {
        $comment = \Config\Database::connect()->table('lesson_discussion_comment')
            ->select('discussion_id_fk')->where('comment_id', $commentId)->get()->getRowArray();
        if (!$comment) {
            return ['error' => ['status' => 404, 'message' => 'Comment not found.']];
        }

        $r = $this->resolveDiscussionAccess((int) $comment['discussion_id_fk']);
        if (isset($r['error'])) {
            return $r;
        }
        $r['commentId'] = $commentId;
        return $r;
    }

    private function resolveReplyAccess(int $replyId): array
    {
        $reply = \Config\Database::connect()->table('lesson_discussion_comment_reply')
            ->select('comment_id_fk')->where('reply_id', $replyId)->get()->getRowArray();
        if (!$reply) {
            return ['error' => ['status' => 404, 'message' => 'Reply not found.']];
        }

        $r = $this->resolveCommentAccess((int) $reply['comment_id_fk']);
        if (isset($r['error'])) {
            return $r;
        }
        $r['replyId'] = $replyId;
        return $r;
    }

    /**
     * Everyone with access to a lesson's discussion: all actively-enrolled students in
     * the classroom plus the subject's active teacher(s), excluding the actor themselves.
     */
    private function lessonDiscussionRecipients(int $classId, int $classSubId, int $excludeUserId): array
    {
        $db  = \Config\Database::connect();
        $ids = [];

        $students = $db->query(
            "SELECT user_id_fk FROM classroom_student WHERE class_id_fk = ? AND class_stud_status = 'Active'",
            [$classId]
        )->getResultArray();
        foreach ($students as $row) {
            $ids[] = (int) $row['user_id_fk'];
        }

        $teachers = $db->query(
            "SELECT user_id_fk FROM classroom_subject_teacher WHERE class_sub_id_fk = ? AND class_sub_teacher_status = 'Active'",
            [$classSubId]
        )->getResultArray();
        foreach ($teachers as $row) {
            $ids[] = (int) $row['user_id_fk'];
        }

        return array_values(array_diff(array_unique($ids), [$excludeUserId]));
    }

    private function lessonTitle(int $lessonId): string
    {
        $lesson = \Config\Database::connect()->table('classroom_lesson')
            ->select('lesson_title')->where('lesson_id', $lessonId)->get()->getRowArray();
        return $lesson['lesson_title'] ?? 'a lesson';
    }

    private function userName(int $userId): string
    {
        $user = \Config\Database::connect()->table('users')
            ->select("CONCAT(fname, ' ', lname) AS name")->where('user_id', $userId)->get()->getRowArray();
        return $user['name'] ?? 'Someone';
    }

    /**
     * Pushes a live 'lesson_discussion' socket event and logs an Activity entry (which
     * itself triggers a separate 'activity_alert' push) for everyone with access to the
     * lesson, so both the open discussion screen and the notifications feed stay in sync.
     */
    private function notifyLessonDiscussion(array $r, string $action, string $title, string $desc, array $payload = []): void
    {
        $actorId = (int) $r['ctx']['myId'];
        $recipients = $this->lessonDiscussionRecipients((int) $r['classId'], (int) $r['classSubId'], $actorId);
        if (empty($recipients)) {
            return;
        }

        RealtimeNotifier::notify($recipients, 'lesson_discussion', array_merge([
            'action'   => $action,
            'lessonId' => (int) $r['lessonId'],
        ], $payload));

        $now = time();
        foreach ($recipients as $userId) {
            $this->userLogModel->addUserLog([
                'user_id_fk' => $userId,
                'log_title'  => $title,
                'log_desc'   => $desc,
                'log_date'   => date('Y-m-d', $now),
                'log_time'   => $now,
                'log_theme'  => 'info',
            ]);
        }
    }

    /**
     * GET /api/classroom/lesson/{lessonId} — full lesson detail: info, files, videos,
     * links, published assessments (name/type/duration only), and discussion feed.
     */
    public function lessonDetail(int $lessonId)
    {
        $r = $this->resolveLessonAccess($lessonId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx = $r['ctx'];

        $lesson = $this->classroomLessonModel->getLessonWithSteps($lessonId);
        if (!$lesson) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Lesson not found.']);
        }

        $this->lessonDiscussionModel->ensureTables();

        $assessments = \Config\Database::connect()->table('lesson_quizze')
            ->select('lesson_quizze_id, quizze_name, assessment_type, quizze_duration')
            ->where('lesson_id_fk', $lessonId)->where('quizze_status', 'Published')
            ->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'lesson' => [
                'id'         => (int) $lesson['lesson_id'],
                'classSubId' => (int) $lesson['class_sub_id_fk'],
                'title'      => $lesson['lesson_title'],
                'desc'       => $lesson['lesson_desc'],
                'term'       => $lesson['lesson_term'] !== null ? (int) $lesson['lesson_term'] : null,
                'week'       => $lesson['lesson_week'] !== null ? (int) $lesson['lesson_week'] : null,
                'day'        => $lesson['lesson_day']  !== null ? (int) $lesson['lesson_day']  : null,
                'duration'   => $lesson['lesson_duration'],
                'status'     => $lesson['lesson_status'],
                'subjectName' => $lesson['subject_name'],
                'levelName'  => $lesson['level_name'],
                'schName'    => $lesson['sch_name'],
            ],
            'files'   => array_map(fn ($f) => [
                'id'   => (int) $f['file_id'],
                'name' => $f['file_name'],
                'path' => $f['file_path'],
                'type' => $f['file_type'],
                'size' => (int) ($f['file_size'] ?? 0),
            ], $lesson['files']),
            'videos'  => array_map(fn ($v) => [
                'id'    => (int) $v['video_id'],
                'url'   => $v['video_url'],
                'title' => $v['video_title'],
                'order' => (int) $v['video_order'],
            ], $lesson['videos']),
            'links'   => array_map(fn ($l) => [
                'id'    => (int) $l['link_id'],
                'url'   => $l['link_url'],
                'title' => $l['link_title'],
                'order' => (int) $l['link_order'],
            ], $lesson['links']),
            'assessments' => array_map(fn ($a) => [
                'id'       => (int) $a['lesson_quizze_id'],
                'name'     => $a['quizze_name'],
                'type'     => $a['assessment_type'],
                'duration' => (int) $a['quizze_duration'],
            ], $assessments),
            'discussion' => $this->lessonDiscussionModel->getDiscussions($lessonId, $ctx['myId']),
        ]);
    }

    /**
     * GET /api/classroom/lesson/quiz/{quizId}/score — the logged-in student's own completed
     * attempt for this quiz: score summary + per-question review (mirrors the web app's
     * classroom/student/{classSubId}/lesson/{lessonId}/quiz/{quizId}/score page, reused here
     * as the single data source for both the mobile "View Score" screen and the PDF transcript
     * it can generate client-side).
     */
    public function lessonQuizScore(int $quizId)
    {
        $quiz = \Config\Database::connect()->table('lesson_quizze')
            ->select('lesson_id_fk, quizze_name, quizze_duration')
            ->where('lesson_quizze_id', $quizId)->get()->getRowArray();
        if (!$quiz) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Quiz not found.']);
        }

        $r = $this->resolveLessonAccess((int) $quiz['lesson_id_fk']);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx = $r['ctx'];

        $targetUserId = $ctx['myId'];
        if (!$r['isStudent']) {
            if (empty($r['childIds'])) {
                return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Only students or their parents can view quiz results.']);
            }

            $childId = $this->request->getGet('childId');
            if ($childId !== null && $childId !== '') {
                $childId = (int) $childId;
                if (!in_array($childId, $r['childIds'], true)) {
                    return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'That child is not enrolled in this classroom.']);
                }
                $targetUserId = $childId;
            } elseif (count($r['childIds']) === 1) {
                $targetUserId = $r['childIds'][0];
            } else {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'childId is required.', 'children' => $r['childIds']]);
            }
        }

        $attempt = $this->lessonQuizzeAttemptModel->getStudentAttempt($quizId, $targetUserId);
        if (!$attempt || $attempt['status'] === 'in_progress') {
            return $this->response->setJSON(['success' => false, 'message' => 'No completed attempt found.']);
        }

        $attemptDetail = $this->lessonQuizzeAttemptModel->getAttemptWithResponses((int) $attempt['attempt_id']);
        $fullQuiz      = $this->lessonQuizzeModel->getQuizWithQuestions($quizId);
        $lesson        = $this->classroomLessonModel->getLessonWithSteps((int) $quiz['lesson_id_fk']);

        $score      = (float) $attemptDetail['score'];
        $correct    = (int) $attemptDetail['correct_answers'];
        $total      = (int) $attemptDetail['total_questions'];
        $responded  = count($attemptDetail['responses'] ?? []);
        $status     = $attemptDetail['status'];

        $responseMap = [];
        foreach ($attemptDetail['responses'] as $resp) {
            $responseMap[(int) $resp['question_id_fk']] = $resp;
        }

        $questions = array_map(function ($q) use ($responseMap) {
            $qId       = (int) $q['quizze_quest_id'];
            $response  = $responseMap[$qId] ?? null;
            $isCorrect = $response && (int) $response['is_correct'] === 1;

            return [
                'id'         => $qId,
                'question'   => $q['question'],
                'isAnswered' => $response !== null,
                'isCorrect'  => $isCorrect,
                'files'      => array_map(fn ($f) => [
                    'id'  => (int) $f['lesson_quizze_quest_file_id'],
                    'url' => base_url('uploads/quiz_files/' . $f['file_src']),
                ], $q['files']),
                'answers'    => array_map(fn ($a) => [
                    'id'         => (int) $a['lesson_quizze_answer_id'],
                    'answer'     => $a['answer'],
                    'isCorrect'  => (int) $a['is_correct_answer'] === 1,
                    'isSelected' => $response !== null && (int) $response['answer_id_fk'] === (int) $a['lesson_quizze_answer_id'],
                ], $q['answers']),
            ];
        }, $fullQuiz['questions']);

        return $this->response->setJSON([
            'success' => true,
            'quiz' => [
                'id'       => $quizId,
                'name'     => $fullQuiz['quizze_name'],
                'duration' => (int) $fullQuiz['quizze_duration'],
            ],
            'lesson' => [
                'title' => $lesson['lesson_title'] ?? '',
            ],
            'attempt' => [
                'score'         => $score,
                'correct'       => $correct,
                'total'         => $total,
                'unanswered'    => $total - $responded,
                'status'        => $status,
                'statusLabel'   => $status === 'timed_out' ? 'Timed Out' : 'Submitted',
                'startedAt'     => $attemptDetail['started_at'],
                'submittedAt'   => $attemptDetail['submitted_at'],
            ],
            'questions' => $questions,
        ]);
    }

    /**
     * GET /api/classroom/lesson/{lessonId}/discussion/feed — lightweight refresh endpoint
     * (just the discussion feed, not the full lesson payload), used by the mobile app's
     * realtime/polling refresh so it doesn't have to reload files/videos/assessments too.
     */
    public function lessonDiscussionFeed(int $lessonId)
    {
        $r = $this->resolveLessonAccess($lessonId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx = $r['ctx'];

        $this->lessonDiscussionModel->ensureTables();

        return $this->response->setJSON([
            'success'    => true,
            'discussion' => $this->lessonDiscussionModel->getDiscussions($lessonId, $ctx['myId']),
        ]);
    }

    /**
     * POST /api/classroom/lesson/{lessonId}/discussion — multipart: message?, photos[]?
     */
    public function lessonDiscussionPost(int $lessonId)
    {
        $r = $this->resolveLessonAccess($lessonId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx = $r['ctx'];

        $message   = trim($this->request->getPost('message') ?? '');
        $files     = $this->request->getFileMultiple('photos') ?? [];
        $validFiles = array_filter($files, fn ($f) => $f instanceof \CodeIgniter\HTTP\Files\UploadedFile && $f->isValid() && !$f->hasMoved());

        if ($message === '' && empty($validFiles)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Post must have a message or at least one photo.']);
        }
        if (count($validFiles) > 10) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Maximum 10 photos allowed.']);
        }

        $allowedImg = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $uploadPath = FCPATH . 'uploads/lesson_discussion/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $photos = [];
        foreach ($validFiles as $file) {
            $ext = strtolower($file->getClientExtension());
            if (!in_array($ext, $allowedImg, true)) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Only image files allowed (jpg, jpeg, png, gif, webp).']);
            }
            $newName = 'ldp_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
            $file->move($uploadPath, $newName);
            $photos[] = $newName;
        }

        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $db->table('lesson_discussion')->insert([
            'lesson_id_fk'   => $lessonId,
            'author'         => $ctx['myId'],
            'message'        => $message ?: null,
            'created_at'     => $now,
            'message_status' => 1,
        ]);
        $discussionId = $db->insertID();

        foreach ($photos as $i => $path) {
            $db->table('lesson_discussion_photo')->insert([
                'ld_id_fk'    => $discussionId,
                'photo_path'  => $path,
                'photo_order' => $i,
            ]);
        }

        $post = $db->query("
            SELECT ld.lesson_discussion_id, ld.message, ld.created_at, ld.author AS author_id,
                   CONCAT(u.fname, ' ', u.lname) AS author_name, u.profile_photo AS author_photo
            FROM lesson_discussion ld INNER JOIN users u ON u.user_id = ld.author
            WHERE ld.lesson_discussion_id = ?
        ", [$discussionId])->getRowArray();

        $post['lesson_discussion_id'] = (int) $post['lesson_discussion_id'];
        $post['author_id']     = (int) $post['author_id'];
        $post['photos']        = $this->lessonDiscussionModel->getPhotos($discussionId);
        $post['comments']      = [];
        $post['comment_count'] = 0;
        $post['like_count']    = 0;
        $post['dislike_count'] = 0;
        $post['user_reaction'] = null;

        $this->notifyLessonDiscussion(
            $r,
            'post',
            'New Discussion Post',
            $this->userName($ctx['myId']) . ' posted in ' . $this->lessonTitle($lessonId) . '.',
            ['discussionId' => $discussionId]
        );

        return $this->response->setJSON(['success' => true, 'post' => $post]);
    }

    /**
     * POST /api/classroom/lesson/discussion/{discussionId}/like — body/form: type ('like'|'dislike')
     */
    public function lessonDiscussionLike(int $discussionId)
    {
        $r = $this->resolveDiscussionAccess($discussionId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $body   = $this->request->getJSON(true) ?? [];
        $type   = ($this->request->getPost('type') ?? ($body['type'] ?? 'like')) === 'dislike' ? 'dislike' : 'like';
        $result = $this->lessonDiscussionModel->toggleLike($discussionId, $r['ctx']['myId'], $type);

        if ($result['reaction'] !== null) {
            $this->notifyLessonDiscussion(
                $r,
                'like',
                'New Reaction',
                $this->userName($r['ctx']['myId']) . ' reacted to a post in ' . $this->lessonTitle($r['lessonId']) . '.',
                ['discussionId' => $discussionId]
            );
        }

        return $this->response->setJSON(['success' => true] + $result);
    }

    /**
     * GET /api/classroom/lesson/discussion/{discussionId}/reactions — who liked/disliked this post
     */
    public function lessonDiscussionReactions(int $discussionId)
    {
        $r = $this->resolveDiscussionAccess($discussionId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $reactions = array_map(fn ($row) => [
            'type'  => $row['like_type'],
            'name'  => $row['name'],
            'photo' => $row['photo'],
        ], $this->lessonDiscussionModel->getDiscussionReactions($discussionId));

        return $this->response->setJSON(['success' => true, 'reactions' => $reactions]);
    }

    /**
     * POST /api/classroom/lesson/discussion/{discussionId}/comment — body/form: comment
     */
    public function lessonDiscussionComment(int $discussionId)
    {
        $r = $this->resolveDiscussionAccess($discussionId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx     = $r['ctx'];
        $body    = $this->request->getJSON(true) ?? [];
        $comment = trim($this->request->getPost('comment') ?? ($body['comment'] ?? ''));
        if ($comment === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Comment cannot be empty.']);
        }

        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $db->table('lesson_discussion_comment')->insert([
            'discussion_id_fk' => $discussionId,
            'author'           => $ctx['myId'],
            'comment'          => $comment,
            'created_at'       => $now,
            'comment_status'   => 'Active',
        ]);
        $commentId = $db->insertID();

        $row = $db->query("
            SELECT ldc.comment_id, ldc.comment, ldc.created_at, ldc.author AS author_id,
                   CONCAT(u.fname, ' ', u.lname) AS author_name, u.profile_photo AS author_photo
            FROM lesson_discussion_comment ldc INNER JOIN users u ON u.user_id = ldc.author
            WHERE ldc.comment_id = ?
        ", [$commentId])->getRowArray();

        $row['comment_id']    = (int) $row['comment_id'];
        $row['author_id']     = (int) $row['author_id'];
        $row['like_count']    = 0;
        $row['dislike_count'] = 0;
        $row['user_reaction'] = null;
        $row['replies']       = [];

        $this->notifyLessonDiscussion(
            $r,
            'comment',
            'New Comment',
            $this->userName($ctx['myId']) . ' commented in ' . $this->lessonTitle($r['lessonId']) . '.',
            ['discussionId' => $discussionId, 'commentId' => $commentId]
        );

        return $this->response->setJSON(['success' => true, 'comment' => $row]);
    }

    /**
     * POST /api/classroom/lesson/discussion/comment/{commentId}/like — body/form: type
     */
    public function lessonDiscussionCommentLike(int $commentId)
    {
        $r = $this->resolveCommentAccess($commentId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $body   = $this->request->getJSON(true) ?? [];
        $type   = ($this->request->getPost('type') ?? ($body['type'] ?? 'like')) === 'dislike' ? 'dislike' : 'like';
        $result = $this->lessonDiscussionModel->toggleCommentLike($commentId, $r['ctx']['myId'], $type);

        if ($result['reaction'] !== null) {
            $this->notifyLessonDiscussion(
                $r,
                'commentLike',
                'New Reaction',
                $this->userName($r['ctx']['myId']) . ' reacted to a comment in ' . $this->lessonTitle($r['lessonId']) . '.',
                ['commentId' => $commentId]
            );
        }

        return $this->response->setJSON(['success' => true] + $result);
    }

    /**
     * GET /api/classroom/lesson/discussion/comment/{commentId}/reactions — who liked/disliked this comment
     */
    public function lessonDiscussionCommentReactions(int $commentId)
    {
        $r = $this->resolveCommentAccess($commentId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $reactions = array_map(fn ($row) => [
            'type'  => $row['like_type'],
            'name'  => $row['name'],
            'photo' => $row['photo'],
        ], $this->lessonDiscussionModel->getCommentReactions($commentId));

        return $this->response->setJSON(['success' => true, 'reactions' => $reactions]);
    }

    /**
     * POST /api/classroom/lesson/discussion/comment/{commentId}/reply — body/form: reply
     */
    public function lessonDiscussionCommentReply(int $commentId)
    {
        $r = $this->resolveCommentAccess($commentId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx   = $r['ctx'];
        $body  = $this->request->getJSON(true) ?? [];
        $reply = trim($this->request->getPost('reply') ?? ($body['reply'] ?? ''));
        if ($reply === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Reply cannot be empty.']);
        }

        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $db->table('lesson_discussion_comment_reply')->insert([
            'comment_id_fk' => $commentId,
            'author'        => $ctx['myId'],
            'reply'         => $reply,
            'created_at'    => $now,
            'reply_status'  => 'Active',
        ]);
        $replyId = $db->insertID();

        $row = $db->query("
            SELECT r.reply_id, r.reply, r.created_at, r.author AS author_id,
                   CONCAT(u.fname, ' ', u.lname) AS author_name, u.profile_photo AS author_photo
            FROM lesson_discussion_comment_reply r INNER JOIN users u ON u.user_id = r.author
            WHERE r.reply_id = ?
        ", [$replyId])->getRowArray();

        $row['reply_id']            = (int) $row['reply_id'];
        $row['author_id']           = (int) $row['author_id'];
        $row['parent_reply_id_fk']  = null;
        $row['like_count']          = 0;
        $row['dislike_count']       = 0;
        $row['user_reaction']       = null;
        $row['replies']             = [];

        $this->notifyLessonDiscussion(
            $r,
            'commentReply',
            'New Reply',
            $this->userName($ctx['myId']) . ' replied in ' . $this->lessonTitle($r['lessonId']) . '.',
            ['commentId' => $commentId, 'replyId' => $replyId]
        );

        return $this->response->setJSON(['success' => true, 'reply' => $row]);
    }

    /**
     * POST /api/classroom/lesson/discussion/reply/{replyId}/reply — body/form: reply
     * Replies to an existing reply (nested thread), inheriting its parent comment.
     */
    public function lessonDiscussionReplyReply(int $replyId)
    {
        $r = $this->resolveReplyAccess($replyId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $ctx   = $r['ctx'];
        $body  = $this->request->getJSON(true) ?? [];
        $reply = trim($this->request->getPost('reply') ?? ($body['reply'] ?? ''));
        if ($reply === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Reply cannot be empty.']);
        }

        $db = \Config\Database::connect();
        $parent = $db->table('lesson_discussion_comment_reply')
            ->select('comment_id_fk')->where('reply_id', $replyId)->get()->getRowArray();

        $now = date('Y-m-d H:i:s');
        $db->table('lesson_discussion_comment_reply')->insert([
            'comment_id_fk'      => (int) $parent['comment_id_fk'],
            'parent_reply_id_fk' => $replyId,
            'author'             => $ctx['myId'],
            'reply'              => $reply,
            'created_at'         => $now,
            'reply_status'       => 'Active',
        ]);
        $newReplyId = $db->insertID();

        $row = $db->query("
            SELECT r.reply_id, r.parent_reply_id_fk, r.reply, r.created_at, r.author AS author_id,
                   CONCAT(u.fname, ' ', u.lname) AS author_name, u.profile_photo AS author_photo
            FROM lesson_discussion_comment_reply r INNER JOIN users u ON u.user_id = r.author
            WHERE r.reply_id = ?
        ", [$newReplyId])->getRowArray();

        $row['reply_id']           = (int) $row['reply_id'];
        $row['parent_reply_id_fk'] = (int) $row['parent_reply_id_fk'];
        $row['author_id']          = (int) $row['author_id'];
        $row['like_count']         = 0;
        $row['dislike_count']      = 0;
        $row['user_reaction']      = null;
        $row['replies']            = [];

        $this->notifyLessonDiscussion(
            $r,
            'replyReply',
            'New Reply',
            $this->userName($ctx['myId']) . ' replied in ' . $this->lessonTitle($r['lessonId']) . '.',
            ['commentId' => (int) $parent['comment_id_fk'], 'replyId' => $newReplyId]
        );

        return $this->response->setJSON(['success' => true, 'reply' => $row]);
    }

    /**
     * POST /api/classroom/lesson/discussion/reply/{replyId}/like — body/form: type
     */
    public function lessonDiscussionReplyLike(int $replyId)
    {
        $r = $this->resolveReplyAccess($replyId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $body   = $this->request->getJSON(true) ?? [];
        $type   = ($this->request->getPost('type') ?? ($body['type'] ?? 'like')) === 'dislike' ? 'dislike' : 'like';
        $result = $this->lessonDiscussionModel->toggleReplyLike($replyId, $r['ctx']['myId'], $type);

        if ($result['reaction'] !== null) {
            $this->notifyLessonDiscussion(
                $r,
                'replyLike',
                'New Reaction',
                $this->userName($r['ctx']['myId']) . ' reacted to a reply in ' . $this->lessonTitle($r['lessonId']) . '.',
                ['replyId' => $replyId]
            );
        }

        return $this->response->setJSON(['success' => true] + $result);
    }

    /**
     * GET /api/classroom/lesson/discussion/reply/{replyId}/reactions — who liked/disliked this reply
     */
    public function lessonDiscussionReplyReactions(int $replyId)
    {
        $r = $this->resolveReplyAccess($replyId);
        if (isset($r['error'])) {
            return $this->errorResponse($r);
        }
        $reactions = array_map(fn ($row) => [
            'type'  => $row['like_type'],
            'name'  => $row['name'],
            'photo' => $row['photo'],
        ], $this->lessonDiscussionModel->getReplyReactions($replyId));

        return $this->response->setJSON(['success' => true, 'reactions' => $reactions]);
    }
}
