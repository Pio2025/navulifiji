<?php
namespace App\Controllers;

class AdmissionController extends BaseController
{
    // ================================================================
    // INDEX — Listing
    // ================================================================

    public function index()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Admissions', 'Admission', 'All Admissions');

        $accessCheck = $this->require_access('_admission_listing');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $userId       = (int) $this->session->get('userID');
        $db           = \Config\Database::connect();

        // Resolve which school's admissions this user may see
        $schId = null;
        $noActiveAdmission = false;

        if (!$isSuperAdmin) {
            $userAdmission = $db->table('admission')
                ->select('sch_id_fk')
                ->where('user_id_fk', $userId)
                ->where('admission_status', 'Active')
                ->get()->getRowArray();

            if (!$userAdmission) {
                $noActiveAdmission = true;
            } else {
                $schId = (int) $userAdmission['sch_id_fk'];
            }
        }

        $data['noActiveAdmission'] = $noActiveAdmission;
        $data['admissions']        = $noActiveAdmission ? [] : $this->admissionModel->getAllAdmissionsWithDetails($schId);
        $data['canAdd']            = !$noActiveAdmission && $this->require_access('_add_admission')    === true;
        $data['canEdit']           = !$noActiveAdmission && $this->require_access('_edit_admission')   === true;
        $data['canDelete']         = !$noActiveAdmission && $this->require_access('_remove_admission') === true;
        $data['isSuperAdmin']      = $isSuperAdmin;
        $data['_view']             = 'app/admission/index';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // MY ADMISSION — logged-in user's own admission history
    // ================================================================

    public function my()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('My Admission', 'Admission', 'My Admission');

        $data['_view'] = 'app/admission/my';
        return view('app/layouts/main', $data);
    }

    public function getMyAdmissionListing()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []])->setStatusCode(401);
        }

        $userId = (int) $this->session->get('userID');
        $db     = \Config\Database::connect();

        $base = function () use ($db, $userId) {
            return $db->table('admission')
                ->join('school', 'school.sch_id = admission.sch_id_fk', 'left')
                ->where('admission.user_id_fk', $userId);
        };

        return $this->buildAdmissionListingResponse($base, [
            'school.sch_name', 'admission.admission_date', 'admission.admission_status', null,
        ], ['school.sch_name', 'admission.admission_status'], 'admission.admission_date');
    }

    // ================================================================
    // CHILD ADMISSION — parent / is_a_parent viewing children's admissions
    // ================================================================

    public function childMy()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');

        if ($roleCat !== 6 && !$this->hasParentFlag($userId)) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Child Admission', 'Admission', 'Child Admission');

        $data['children'] = $this->parentStudentModel->getChildrenOf($userId);
        $data['_view']    = 'app/admission/child_my';
        return view('app/layouts/main', $data);
    }

    public function getChildAdmissionListing()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []])->setStatusCode(401);
        }

        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');

        if ($roleCat !== 6 && !$this->hasParentFlag($userId)) {
            return $this->response->setJSON(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []])->setStatusCode(403);
        }

        $childIds = array_column($this->parentStudentModel->getChildrenOf($userId), 'user_id');

        if (empty($childIds)) {
            $draw = (int) (service('request')->getPost('draw') ?? 1);
            return $this->response->setJSON([
                'draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'csrf_hash' => csrf_hash(),
            ]);
        }

        $db   = \Config\Database::connect();
        $base = function () use ($db, $childIds) {
            return $db->table('admission')
                ->join('school', 'school.sch_id = admission.sch_id_fk', 'left')
                ->join('users',  'users.user_id  = admission.user_id_fk', 'left')
                ->whereIn('admission.user_id_fk', $childIds);
        };

        return $this->buildAdmissionListingResponse($base, [
            'users.fname', 'school.sch_name', 'admission.admission_date', 'admission.admission_status', null,
        ], ['users.fname', 'users.lname', 'school.sch_name', 'admission.admission_status'], 'admission.admission_date', true);
    }

    // ================================================================
    // Shared AJAX listing builder for my()/childMy() datatables
    // ================================================================

    private function buildAdmissionListingResponse(\Closure $base, array $columnMap, array $searchFields, string $defaultOrderCol, bool $withChild = false)
    {
        $req    = service('request');
        $draw   = (int) ($req->getPost('draw') ?? 1);
        $start  = (int) ($req->getPost('start') ?? 0);
        $length = (int) ($req->getPost('length') ?? 10);

        $searchData  = $req->getPost('search');
        $searchValue = is_array($searchData) ? trim($searchData['value'] ?? '') : '';

        $orderData        = $req->getPost('order');
        $orderColumnIndex = is_array($orderData) ? (int) ($orderData[0]['column'] ?? 0) : 0;
        $orderDir         = is_array($orderData) ? ($orderData[0]['dir'] ?? 'desc') : 'desc';
        $orderDir         = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
        $orderCol         = $columnMap[$orderColumnIndex] ?? $defaultOrderCol;

        $recordsTotal = $base()->countAllResults();

        $select = 'admission.admission_id, admission.admission_date, admission.admission_status,
                    school.sch_name, school.sch_logo';
        if ($withChild) {
            $select .= ', users.user_id, users.fname, users.lname, users.profile_photo';
        }

        $builder = $base()->select($select);

        if ($searchValue !== '') {
            $builder->groupStart();
            foreach ($searchFields as $i => $field) {
                $i === 0 ? $builder->like($field, $searchValue) : $builder->orLike($field, $searchValue);
            }
            $builder->groupEnd();
        }

        $recordsFiltered = $builder->countAllResults(false);
        $builder->orderBy($orderCol, $orderDir);

        if ($length !== -1) {
            $builder->limit($length, $start);
        }

        $rows = $builder->get()->getResultArray();

        $data = [];
        foreach ($rows as $row) {
            $entry = [];
            if ($withChild) {
                $entry['child_name'] = esc(trim(($row['fname'] ?? '') . ' ' . ($row['lname'] ?? '')));
            }
            $entry['sch_name']         = esc($row['sch_name'] ?? '—');
            $entry['admission_date']   = $row['admission_date'] ? date('d M Y', strtotime($row['admission_date'])) : '—';
            $entry['admission_status'] = $this->statusBadge($row['admission_status'] ?? '');
            $entry['actions']          = '<button type="button" class="btn btn-icon btn-sm btn-light-primary btn-view-admission" data-id="' . (int) $row['admission_id'] . '">'
                . '<i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></button>';
            $data[] = $entry;
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
            'csrf_hash'       => csrf_hash(),
        ]);
    }

    private function statusBadge(string $status): string
    {
        $cls = match (strtolower($status)) {
            'active'                          => 'success',
            'pending'                         => 'warning',
            'inactive', 'rejected', 'declined' => 'danger',
            default                           => 'secondary',
        };
        return '<span class="badge badge-light-' . $cls . ' fs-8">' . esc($status !== '' ? $status : '—') . '</span>';
    }

    // ================================================================
    // MY / CHILD DETAIL MODAL (AJAX, read-only)
    // ================================================================

    public function myDetail(int $admissionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $admission = $this->admissionModel->getAdmissionDetail($admissionId);
        if (!$admission) {
            return $this->response->setJSON(['success' => false, 'message' => 'Admission not found.']);
        }

        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');

        $isOwn   = (int) $admission['user_id_fk'] === $userId;
        $isChild = false;

        if (!$isOwn && ($roleCat === 6 || $this->hasParentFlag($userId))) {
            $childIds = array_map('intval', array_column($this->parentStudentModel->getChildrenOf($userId), 'user_id'));
            $isChild  = in_array((int) $admission['user_id_fk'], $childIds, true);
        }

        if (!$isOwn && !$isChild) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.'])->setStatusCode(403);
        }

        return $this->response->setJSON([
            'success' => true,
            'html'    => view('app/admission/_my_detail_modal', ['admission' => $admission]),
        ]);
    }

    // ================================================================
    // DETAIL — View single admission
    // ================================================================

    public function detail(int $admissionId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }
    
        $admission = $this->admissionModel->getAdmissionDetail($admissionId);
        if (!$admission) {
            return redirect()->to('admission')->with('error', 'Admission not found.');
        }
    
        $this->setPageData('Admission Detail', 'Admission', 'Detail');
    
        $roleCatId = (int) ($admission['role_cat_id'] ?? 0);
    
        $db = \Config\Database::connect();
    
        // Enrolments
        $enrolments = $db->table('enrolment')
            ->select('enrolment.*, stream.stream_name, level.level_name')
            ->join('stream',    'stream.stream_id       = enrolment.stream_id_fk',  'left')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk', 'left')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',  'left')
            ->where('enrolment.admission_id_fk', $admissionId)
            ->orderBy('enrolment.enrol_year', 'DESC')
            ->get()->getResultArray();
    
        // Teaching subjects (Teacher roles: 2, 3, 5)
        $teachingSubjects = [];
        $hodRecord        = null;
        if (in_array($roleCatId, [2, 3, 5])) {
            $teachingSubjects = $db->table('admission_teaching_subject')
                ->select('admission_teaching_subject.*, subject.subject_name,
                          department.dept_name')
                ->join('sch_subject',   'sch_subject.sch_sub_id    = admission_teaching_subject.sch_sub_id_fk', 'left')
                ->join('subject',       'subject.subject_id        = sch_subject.subject_id_fk',               'left')
                ->join('sch_department','sch_department.sch_dept_id = sch_subject.sch_dept_id_fk',             'left')
                ->join('department',    'department.dept_id        = sch_department.dept_id_fk',               'left')
                ->where('admission_teaching_subject.admission_id_fk', $admissionId)
                ->orderBy('department.dept_name', 'ASC')
                ->get()->getResultArray();
    
            $hodRecord = $db->table('admission_hod')
                ->select('admission_hod.*, department.dept_name, department.dept_icon,
                          department.dept_theme, sch_department.sch_dept_id')
                ->join('sch_department', 'sch_department.sch_dept_id = admission_hod.sch_dept_id_fk', 'left')
                ->join('department',     'department.dept_id         = sch_department.dept_id_fk',    'left')
                ->where('admission_hod.admission_id_fk', $admissionId)
                ->get()->getRowArray();
        }
    
        // Student role (Student: 4)
        $studentRole = null;
        if ($roleCatId === 4) {
            $studentRole = $db->table('admission_student_role')
                ->where('admission_id_fk', $admissionId)
                ->get()->getRowArray();
        }
    
        // Previous admissions for this user (excluding current)
        $previousAdmissions = $db->table('admission')
            ->select('admission.admission_id, admission.admission_date,
                      admission.admission_status, admission.admission_note,
                      school.sch_name')
            ->join('school', 'school.sch_id = admission.sch_id_fk', 'left')
            ->where('admission.user_id_fk', $admission['user_id_fk'])
            ->where('admission.admission_id !=', $admissionId)
            ->orderBy('admission.admission_date', 'DESC')
            ->get()->getResultArray();

        $data['admission']          = $admission;
        $data['enrolments']         = $enrolments;
        $data['roleCatId']          = $roleCatId;
        $data['teachingSubjects']   = $teachingSubjects;
        $data['hodRecord']          = $hodRecord;
        $data['studentRole']        = $studentRole;
        $data['previousAdmissions'] = $previousAdmissions;
        $data['canEdit']          = ($this->require_access('_edit_admission')   === true);
        $data['canDelete']        = ($this->require_access('_delete_admission') === true);
        $data['isSuperAdmin']     = (int) $this->session->get('roleID') === 1;
    
        if ($data['isSuperAdmin']) {
            $data['canEdit'] = $data['canDelete'] = true;
        }
    
        $data['_view'] = 'app/admission/detail';
    
        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ADD — Show form
    // ================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Add New Admission', 'Admission', 'Add Admission');

        $accessCheck = $this->require_access('_add_admission');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $data['schools']       = $this->schoolModel->getAllSchool();
        $data['eligibleUsers'] = $this->userModel->getUsersWithoutActiveAdmission();
        $data['isSuperAdmin']  = (int) $this->session->get('roleID') === 1;
        $data['sessionSchId']  = $this->session->get('schID') ?? null;
        $data['_view']         = 'app/admission/add';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STORE — Save new admission
    // ================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if (!$this->require_access('_add_admission')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $userId = (int) $this->request->getPost('user_id_fk');
            $schId  = (int) $this->request->getPost('sch_id_fk');
            $date   = $this->request->getPost('admission_date');
            $status = $this->request->getPost('admission_status') ?? 'Active';
            $note   = $this->request->getPost('admission_note')   ?? '';

            if (!$userId || !$schId || !$date) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User, school and admission date are required.'
                ]);
            }

            // Check no existing active admission
            $existing = $this->admissionModel
                ->where('user_id_fk', $userId)
                ->where('admission_status', 'Active')
                ->first();

            if ($existing) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This user already has an active admission.'
                ]);
            }

            $db = \Config\Database::connect();

            // If enrolling into a stream, an active classroom must already exist for it
            // this year — check upfront so we don't create an admission/enrolment that
            // has nowhere to attach (no classroom to register subjects/attendance against).
            $enrolStreamId = (int) $this->request->getPost('enrol_stream_id');
            $classId       = 0;
            $enrolYear     = 0;
            if ($enrolStreamId > 0) {
                $enrolYear = (int) ($this->request->getPost('enrol_year') ?: date('Y'));

                $classRow = $db->table('classroom')
                    ->where('stream_id_fk', $enrolStreamId)
                    ->where('class_year',   $enrolYear)
                    ->where('class_status', 'Active')
                    ->get()->getRowArray();

                if (!$classRow) {
                    $stream = $db->table('stream')->where('stream_id', $enrolStreamId)->get()->getRowArray();
                    return $this->response->setJSON([
                        'success'      => false,
                        'no_classroom' => true,
                        'stream_id'    => $enrolStreamId,
                        'message'      => 'No active classroom exists for "' . ($stream['stream_name'] ?? 'this stream')
                                          . '" in ' . $enrolYear . '. Please create one first, then try enrolling again.'
                    ]);
                }

                $classId = (int) $classRow['class_id'];
            }

            // Non Super Admin — enforce their school
            $isSuperAdmin = (int) $this->session->get('roleID') === 1;
            if (!$isSuperAdmin) {
                $schId = (int) $this->session->get('schID');
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
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to save admission.'
                ]);
            }

            $user = $this->userModel->find($userId);

            // Save teaching subjects if provided (Teacher role)
            $teachingSubjectsJson = $this->request->getPost('teaching_subjects');
            if (!empty($teachingSubjectsJson)) {
                $subjects = json_decode($teachingSubjectsJson, true);
                if (is_array($subjects) && count($subjects) > 0) {
                    foreach (array_slice($subjects, 0, 7) as $sub) {
                        $db->table('admission_teaching_subject')->insert([
                            'admission_id_fk'      => $admissionId,
                            'sch_sub_id_fk'        => (int)$sub['sch_sub_id'],
                            'created_date'         => date('Y-m-d'),
                            'created_time'         => time(),
                            'adm_teach_sub_status' => 'Active',
                        ]);
                    }
                }
            }

            // Create enrolment + student subjects if a stream was selected (Student role).
            // Classroom existence for ($enrolStreamId, $enrolYear) was already verified above.
            if ($enrolStreamId > 0) {
                // Prevent duplicate active enrolment
                $existingEnrol = $db->table('enrolment')
                    ->where('admission_id_fk', $admissionId)
                    ->where('enrol_status', 'Active')
                    ->get()->getRowArray();

                if (!$existingEnrol) {
                    $enrolTerm = (int) ($this->request->getPost('enrol_term') ?: 1);
                    $enrolDate = $this->request->getPost('enrol_date') ?: date('Y-m-d');

                    $enrolId = $this->enrolmentModel->insert([
                        'admission_id_fk' => $admissionId,
                        'stream_id_fk'    => $enrolStreamId,
                        'enrol_date'      => $enrolDate,
                        'enrol_time'      => time(),
                        'enrol_term'      => $enrolTerm,
                        'enrol_year'      => $enrolYear,
                        'enrol_status'    => 'Active',
                    ]);

                    if ($enrolId) {
                        if ((int) $this->request->getPost('has_subjects')) {
                            $coreSubjects = $this->request->getPost('core_subjects') ?? [];
                            $optSubjects  = [];
                            foreach ($this->request->getPost() as $key => $val) {
                                if (preg_match('/^optional_group_\d+$/', $key) && !empty($val)) {
                                    $optSubjects[] = (int) $val;
                                }
                            }

                            $allSubjects = array_merge(array_map('intval', $coreSubjects), $optSubjects);
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

                        // Auto-enrol into classroom_student, same as EnrolmentController/UserController,
                        // so the student immediately sees this classroom on "My Classroom".
                        $alreadyEnrolled = $db->table('classroom_student')
                            ->where('class_id_fk', $classId)
                            ->where('user_id_fk',  $userId)
                            ->countAllResults() > 0;

                        if (!$alreadyEnrolled) {
                            $db->table('classroom_student')->insert([
                                'class_id_fk'       => $classId,
                                'user_id_fk'        => $userId,
                                'class_stud_status' => 'Active',
                                'admitted_at'       => date('Y-m-d'),
                                'admitted_by'       => (int) $this->session->get('userID'),
                            ]);
                        }
                    }
                }
            }

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Admission Added',
                'log_desc'    => 'New admission for ' . ($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''),
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-element-plus"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'success',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Admission saved successfully.',
                'redirect' => base_url('admission'),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AdmissionController::store] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // EDIT — Show edit form
    // ================================================================

    public function edit(int $admissionId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }
    
        // ── Use getAdmissionDetail() not getAdmissionWithSchool() ──
        $admission = $this->admissionModel->getAdmissionDetail($admissionId);
        if (!$admission) {
            return redirect()->to('admission')->with('error', 'Admission not found.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        // Block direct URL access for non-super-admin on Completed admissions
        if ($admission['admission_status'] === 'Completed' && !$isSuperAdmin) {
            return redirect()->to('admission/detail/' . $admissionId)
                ->with('edit_restricted', 'This admission is <strong>Completed</strong>. Only a Super Admin can edit it. You may view the details below.');
        }

        $this->setPageData('Edit Admission', 'Admission', 'Edit Admission');
    
        // Get role category for the admitted user
        $db      = \Config\Database::connect();
        $roleRow = $db->table('user_role')
            ->select('role_category.role_cat_id, role_category.role_cat_name, role.role_name')
            ->join('role',          'role.role_id              = user_role.role_id_fk',  'left')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',  'left')
            ->where('user_role.user_id_fk',     $admission['user_id_fk'])
            ->where('user_role.user_role_status', 'Active')
            ->get()->getRowArray();
    
        $roleCatId = (int) ($roleRow['role_cat_id'] ?? 0);
    
        // Fallback from name if needed
        if ($roleCatId === 0 && !empty($roleRow['role_cat_name'])) {
            $map = [
                'system admin' => 1, 'school admin' => 2, 'teacher' => 3,
                'student' => 4, 'support staff' => 5, 'parent or guardian' => 6,
            ];
            $roleCatId = $map[strtolower(trim($roleRow['role_cat_name']))] ?? 0;
        }
    
        $user   = $this->userModel->find($admission['user_id_fk']);
        $gender = strtolower($user['gender'] ?? 'male');
    
        // Load existing teacher/student data
        $teachingSubjects = [];
        $hodRecord        = null;
        $studentRole      = null;
    
        if (in_array($roleCatId, [2, 3, 5])) {
            $teachingSubjects = $db->table('admission_teaching_subject')
            ->select('admission_teaching_subject.*, subject.subject_name,
                      department.dept_name')
            ->join('sch_subject',   'sch_subject.sch_sub_id   = admission_teaching_subject.sch_sub_id_fk', 'left')
            ->join('subject',       'subject.subject_id       = sch_subject.subject_id_fk',                'left')
            ->join('sch_department','sch_department.sch_dept_id = sch_subject.sch_dept_id_fk',             'left')
            ->join('department',    'department.dept_id        = sch_department.dept_id_fk',               'left')
            ->where('admission_teaching_subject.admission_id_fk', $admissionId)
            ->orderBy('department.dept_name', 'ASC')
            ->get()->getResultArray();
    
            $hodRecord = $db->table('admission_hod')
                ->select('admission_hod.*, department.dept_name, sch_department.sch_dept_id')
                ->join('sch_department', 'sch_department.sch_dept_id = admission_hod.sch_dept_id_fk', 'left')
                ->join('department',     'department.dept_id         = sch_department.dept_id_fk',    'left')
                ->where('admission_hod.admission_id_fk', $admissionId)
                ->get()->getRowArray();
        }
    
        if ($roleCatId === 4) {
            $studentRole = $db->table('admission_student_role')
                ->where('admission_id_fk', $admissionId)
                ->get()->getRowArray();
        }
    
        $data['admission']        = $admission;
        $data['admissionId']      = $admissionId;
        $data['isSuperAdmin']     = $isSuperAdmin;
        $data['roleCatId']        = $roleCatId;
        $data['roleRow']          = $roleRow;
        $data['gender']           = $gender;
        $data['teachingSubjects'] = $teachingSubjects;
        $data['hodRecord']        = $hodRecord;
        $data['studentRole']      = $studentRole;
        $data['_view']            = 'app/admission/edit';
    
        if ($isSuperAdmin) {
            $data['schools'] = $this->schoolModel->getAllSchool();
        }
    
        return view('app/layouts/main', $data);
    }

    // ================================================================
    // UPDATE — Save edited admission
    // ================================================================

    public function update(int $admissionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        try {
            $admission = $this->admissionModel->find($admissionId);
            if (!$admission) {
                return $this->response->setJSON(['success' => false, 'message' => 'Admission not found.']);
            }

            $schId  = (int) $this->request->getPost('sch_id_fk');
            $date   = $this->request->getPost('admission_date');
            $status = $this->request->getPost('admission_status') ?? 'Active';
            $note   = $this->request->getPost('admission_note') ?? '';

            $isSuperAdmin = (int) $this->session->get('roleID') === 1;
            if (!$isSuperAdmin) {
                $schId = $admission['sch_id_fk'];
            }

            $db = \Config\Database::connect();

            // ── If setting to Active, block if another active admission already exists ──
            if ($status === 'Active' && $admission['admission_status'] !== 'Active') {
                $conflict = $db->table('admission')
                    ->where('user_id_fk', $admission['user_id_fk'])
                    ->where('admission_status', 'Active')
                    ->where('admission_id !=', $admissionId)
                    ->get()->getRowArray();

                if ($conflict) {
                    return $this->response->setJSON([
                        'success'          => false,
                        'active_conflict'  => true,
                        'message'          => 'This user already has an active admission (ID #' .
                                             str_pad($conflict['admission_id'], 6, '0', STR_PAD_LEFT) .
                                             '). A user can only have one active admission at a time.',
                    ]);
                }
            }

            // ── Core admission fields ──────────────────────────────────────
            // Non-super-admin cannot change anything except status when Completed
            $currentStatus = $admission['admission_status'];
            $isLocked      = $currentStatus === 'Completed' && !$isSuperAdmin;

            if ($isLocked) {
                $this->admissionModel->updateAdmission($admissionId, [
                    'admission_status' => $status,
                ]);
            } else {
                $this->admissionModel->updateAdmission($admissionId, [
                    'sch_id_fk'        => $schId,
                    'admission_date'   => $date,
                    'admission_note'   => $note ?: null,
                    'admission_status' => $status,
                ]);
            }

            // ── Sync status to admission_teaching_subject ──────────────────
            $db->table('admission_teaching_subject')
               ->where('admission_id_fk', $admissionId)
               ->update(['adm_teach_sub_status' => $status]);
    
            if (!$isLocked) {
                // ── HOD (Teacher roles) ───────────────────────────────────
                $isHod     = $this->request->getPost('is_hod') === '1';
                $schDeptId = (int) $this->request->getPost('hod_dept_id');

                $db->table('admission_hod')->where('admission_id_fk', $admissionId)->delete();

                if ($isHod && $schDeptId) {
                    $db->table('admission_hod')->insert([
                        'admission_id_fk' => $admissionId,
                        'sch_dept_id_fk'  => $schDeptId,
                        'created_date'    => date('Y-m-d'),
                        'created_time'    => time(),
                    ]);
                }

                // ── Student leadership role ───────────────────────────────
                $leadershipRole = $this->request->getPost('leadership_role') ?? '';

                $db->table('admission_student_role')->where('admission_id_fk', $admissionId)->delete();

                if (!empty($leadershipRole)) {
                    $db->table('admission_student_role')->insert([
                        'admission_id_fk'      => $admissionId,
                        'leadership_role'      => $leadershipRole,
                        'created_date'         => date('Y-m-d'),
                        'created_time'         => time(),
                        'adm_stud_role_status' => 'Active',
                    ]);
                }
            }

            // ── Sync status to enrolment (and cascade further to classroom_student) ──
            // A Completed admission leaves enrolment Inactive (not "Completed" — that value
            // is reserved for enrolments that finished their own course/stream independently),
            // but the student's classroom membership itself is marked Completed since their
            // time in that classroom is genuinely over, not merely paused.
            $enrolStatus     = ($status === 'Completed') ? 'Inactive' : $status;
            $classStudStatus = $status === 'Completed'
                ? 'Completed'
                : (in_array($enrolStatus, ['Active', 'Inactive'], true) ? $enrolStatus : null);

            $enrolRows = $db->table('enrolment')
                ->where('admission_id_fk', $admissionId)
                ->get()->getResultArray();

            $db->table('enrolment')
               ->where('admission_id_fk', $admissionId)
               ->update(['enrol_status' => $enrolStatus]);

            if ($classStudStatus !== null) {
                foreach ($enrolRows as $enrolRow) {
                    $classRow = $db->table('classroom')
                        ->where('stream_id_fk', $enrolRow['stream_id_fk'])
                        ->where('class_year',   $enrolRow['enrol_year'])
                        ->get()->getRowArray();

                    if ($classRow) {
                        $db->table('classroom_student')
                            ->where('class_id_fk', (int) $classRow['class_id'])
                            ->where('user_id_fk',  $admission['user_id_fk'])
                            ->update(['class_stud_status' => $classStudStatus]);
                    }
                }
            }

            $db->table('admission_student_role')
               ->where('admission_id_fk', $admissionId)
               ->update(['adm_stud_role_status' => $status]);
    
            $user = $this->userModel->find($admission['user_id_fk']);
    
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Admission Updated',
                'log_desc'    => 'Admission ID ' . $admissionId . ' updated for ' .
                                 ($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''),
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-pencil"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'warning',
            ]);
    
            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Admission updated successfully.',
                'redirect' => base_url('admission/detail/' . $admissionId),
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[AdmissionController::update] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DELETE
    // ================================================================

    public function delete(int $admissionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if (!$this->require_access('_remove_admission')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $admission = $this->admissionModel->find($admissionId);
            if (!$admission) {
                return $this->response->setJSON(['success' => false, 'message' => 'Admission not found.']);
            }

            // Delete enrolments first
            $db = \Config\Database::connect();
            $db->table('enrolment')
               ->where('admission_id_fk', $admissionId)
               ->delete();

            $this->admissionModel->delete($admissionId);

            $user = $this->userModel->find($admission['user_id_fk']);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Admission Deleted',
                'log_desc'    => 'Admission ID ' . $admissionId . ' deleted for ' . ($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''),
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'danger',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Admission deleted successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AdmissionController::delete] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }
    
    // ================================================================
    // REPORT — Stream PDF report filtered by status + role
    // ================================================================

    public function report()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $status = trim($this->request->getGet('status') ?? '');
        $role   = trim($this->request->getGet('role')   ?? '');

        if (empty($status) || empty($role)) {
            return redirect()->to('admission')->with('error', 'Both status and role are required to generate a report.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $userId       = (int) $this->session->get('userID');
        $db           = \Config\Database::connect();

        $schId   = null;
        $schName = null;
        $schLogo = null;

        if (!$isSuperAdmin) {
            $userAdm = $db->table('admission')
                ->select('sch_id_fk')
                ->where('user_id_fk', $userId)
                ->where('admission_status', 'Active')
                ->get()->getRowArray();

            if ($userAdm) {
                $schId   = (int) $userAdm['sch_id_fk'];
                $school  = $db->table('school')->where('sch_id', $schId)->get()->getRowArray();
                $schName = $school['sch_name'] ?? null;
                $schLogo = $school['sch_logo'] ?? null;
            }
        }

        // Check if the selected role belongs to the Student category (role_cat_id = 4)
        $roleRow       = $db->table('role')
            ->select('role_category.role_cat_id')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk', 'left')
            ->where('role.role_name', $role)
            ->get()->getRowArray();
        $isStudentRole = ((int)($roleRow['role_cat_id'] ?? 0) === 4);

        $builder = $db->table('admission')
            ->select('admission.admission_id, admission.admission_date, admission.admission_status,
                      users.fname, users.lname, users.username, users.gender,
                      school.sch_name, role.role_name, role_category.role_cat_name')
            ->join('users',         'users.user_id             = admission.user_id_fk',  'left')
            ->join('user_role',     'user_role.user_id_fk      = users.user_id',         'left')
            ->join('role',          'role.role_id              = user_role.role_id_fk',  'left')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',  'left')
            ->join('school',        'school.sch_id             = admission.sch_id_fk',   'left')
            ->where('user_role.user_role_status', 'Active')
            ->where('admission.admission_status', $status)
            ->where('role.role_name', $role);

        if ($schId !== null) {
            $builder->where('admission.sch_id_fk', $schId);
        }

        $admissions = $builder->orderBy('admission.admission_id', 'DESC')->get()->getResultArray();

        // ── For student role: fetch latest enrolment per student and group by stream ──
        $grouped = null;
        if ($isStudentRole && !empty($admissions)) {
            $idList    = implode(',', array_map('intval', array_column($admissions, 'admission_id')));
            $enrolRows = $db->query("
                SELECT e.admission_id_fk, e.enrol_year, e.enrol_status,
                       s.stream_name, l.level_name
                FROM enrolment e
                INNER JOIN (
                    SELECT admission_id_fk, MAX(enrol_year) AS max_year
                    FROM enrolment
                    WHERE admission_id_fk IN ($idList)
                      AND enrol_status = 'Active'
                    GROUP BY admission_id_fk
                ) latest ON e.admission_id_fk = latest.admission_id_fk
                         AND e.enrol_year     = latest.max_year
                LEFT JOIN stream    s  ON s.stream_id        = e.stream_id_fk
                LEFT JOIN sch_level sl ON sl.sch_level_id    = s.sch_level_id_fk
                LEFT JOIN level     l  ON l.level_id         = sl.level_id_fk
                WHERE e.enrol_status = 'Active'
            ")->getResultArray();

            $enrolMap = [];
            foreach ($enrolRows as $e) {
                $enrolMap[$e['admission_id_fk']] = $e;
            }

            $grouped = [];
            foreach ($admissions as $adm) {
                $e   = $enrolMap[$adm['admission_id']] ?? null;
                $adm['stream_name'] = $e['stream_name'] ?? null;
                $adm['level_name']  = $e['level_name']  ?? null;
                $adm['enrol_year']  = $e['enrol_year']  ?? null;

                if ($e && !empty($e['stream_name'])) {
                    $key = trim(($e['level_name'] ? $e['level_name'] . ' — ' : '') . $e['stream_name']);
                } else {
                    $key = '';
                }
                $grouped[$key][] = $adm;
            }

            // Enrolled streams sorted alphabetically; unenrolled (key='') always last
            uksort($grouped, function ($a, $b) {
                if ($a === '' && $b !== '') return 1;
                if ($a !== '' && $b === '') return -1;
                return strcmp($a, $b);
            });
        }

        // ── PDF (TCPDF, landscape A4) ─────────────────────────────────────
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

        set_error_handler(static function(int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP')
                || str_contains($errstr, 'gd-png')
                || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetAuthor('Navuli Fiji School Management System');
        $pdf->SetTitle('Admission Report');
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
        $pdf->Cell($centerW, 5, 'ADMISSION REPORT', 0, 1, 'C');

        $pdf->SetXY($centerX, $y + 15);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($centerW, 4, 'Status: ' . $status . '   |   Role: ' . $role, 0, 1, 'C');

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

        // ── Column config ─────────────────────────────────────────────────
        if ($isStudentRole) {
            $col     = [8, 58, 60, 18, 56, 36, 31];
            $headers = ['#', 'Full Name', 'Username', 'Gender', 'School', 'Enrol Year', 'Adm. Date'];
            $aligns  = ['C', 'L', 'L', 'C', 'L', 'C', 'C'];
        } else {
            $col     = [8, 58, 60, 18, 56, 36, 31];
            $headers = ['#', 'Full Name', 'Username', 'Gender', 'School', 'Role', 'Adm. Date'];
            $aligns  = ['C', 'L', 'L', 'C', 'L', 'C', 'C'];
        }

        // ── Closure: draw column header row ───────────────────────────────
        $drawHead = function () use ($pdf, $col, $headers, $aligns) {
            $pdf->SetFillColor(248, 250, 252);
            $pdf->SetDrawColor(226, 232, 240);
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [226, 232, 240]]);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(50, 50, 50);
            foreach ($headers as $k => $h) {
                $pdf->Cell($col[$k], 7, $h, 'TLBR', 0, $aligns[$k], true);
            }
            $pdf->Ln();
            $pdf->SetTextColor(40, 40, 40);
            $pdf->SetFont('helvetica', '', 7.5);
        };

        // ── Closure: draw one data row ─────────────────────────────────────
        $drawRow = function (int $n, array $adm, bool $isStudent) use ($pdf, $col, $aligns) {
            $shade = ($n % 2 === 0);
            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [255, 255, 255]));
            $pdf->SetDrawColor(226, 232, 240);
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [226, 232, 240]]);
            $pdf->SetFont('helvetica', '', 7.5);
            $pdf->SetTextColor(40, 40, 40);
            $name  = trim(($adm['fname'] ?? '') . ' ' . ($adm['lname'] ?? ''));
            $date  = !empty($adm['admission_date']) ? date('d M Y', strtotime($adm['admission_date'])) : '—';
            $col6  = $isStudent ? ($adm['enrol_year'] ?? '—') : ($adm['role_name'] ?? '—');
            $cells = [$n, $name, $adm['username'] ?? '—', $adm['gender'] ?? '—', $adm['sch_name'] ?? '—', $col6, $date];
            foreach ($cells as $k => $val) {
                $pdf->Cell($col[$k], 6.5, $val, 'TLBR', 0, $aligns[$k], $shade);
            }
            $pdf->Ln();
        };

        $drawHead();

        // ── Render rows ───────────────────────────────────────────────────
        $contentW = $cw;
        if ($isStudentRole && $grouped !== null) {
            $rowNum = 1;
            foreach ($grouped as $streamKey => $students) {
                $label = $streamKey ?: 'Not Enrolled in Any Stream';
                $count = count($students);

                if ($pdf->GetY() > $bottomY - 14) {
                    $pdf->AddPage();
                    $drawHead();
                }

                $pdf->SetFillColor(219, 234, 254);
                $pdf->SetDrawColor(226, 232, 240);
                $pdf->SetTextColor(26, 86, 219);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(
                    $contentW, 6.5,
                    '  ' . $label . '   (' . $count . ' student' . ($count !== 1 ? 's' : '') . ')',
                    'TLBR', 1, 'L', true
                );
                $pdf->SetTextColor(40, 40, 40);
                $pdf->SetFont('helvetica', '', 7.5);

                foreach ($students as $adm) {
                    if ($pdf->GetY() > $bottomY) {
                        $pdf->AddPage();
                        $drawHead();
                    }
                    $drawRow($rowNum, $adm, true);
                    $rowNum++;
                }
            }
        } else {
            foreach ($admissions as $i => $adm) {
                if ($pdf->GetY() > $bottomY) {
                    $pdf->AddPage();
                    $drawHead();
                }
                $drawRow($i + 1, $adm, false);
            }
        }

        // ── Total + footer ────────────────────────────────────────────────
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($cw, 5, 'Total: ' . count($admissions) . ' record(s)', 0, 1, 'R');

        $pdf->Ln(4);
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [226, 232, 240]]);
        $pdf->Line($sx, $pdf->GetY(), $sx + $cw, $pdf->GetY());
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', 'I', 7);
        $pdf->SetTextColor(160, 160, 160);
        $pdf->Cell($cw, 5, 'Generated by Navuli Fiji School Management System on ' . date('d M Y \a\t H:i'), 0, 0, 'C');

        while (ob_get_level()) { ob_end_clean(); }
        $pdf->Output('admission_report_' . date('Y-m-d') . '.pdf', 'I');
        exit;
    }

    // ================================================================
    // AJAX — Get streams + subjects for a school (for teaching subject modal)
    // ================================================================
    
    public function getSchoolSubjects(int $schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        $db = \Config\Database::connect();
    
        // Get all streams for this school
        $streams = $db->table('stream')
            ->select('stream.stream_id, stream.stream_name, level.level_name')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk', 'left')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',  'left')
            ->where('sch_level.sch_id_fk', $schId)
            ->orderBy('level.level_name', 'ASC')
            ->orderBy('stream.stream_name', 'ASC')
            ->get()->getResultArray();
    
        // Get all subjects for this school from sch_subject
        $subjects = $db->table('sch_subject')
            ->select('sch_subject.sch_sub_id, sch_subject.sch_sub_status,
                      subject.subject_name, subject.level_id_fk,
                      department.dept_name, sch_department.sch_dept_id')
            ->join('subject',       'subject.subject_id        = sch_subject.subject_id_fk',      'left')
            ->join('sch_department','sch_department.sch_dept_id = sch_subject.sch_dept_id_fk',    'left')
            ->join('department',    'department.dept_id        = sch_department.dept_id_fk',      'left')
            ->where('sch_subject.sch_id_fk', $schId)
            ->where('sch_subject.sch_sub_status', 'Active')
            ->orderBy('department.dept_name', 'ASC')
            ->orderBy('subject.subject_name', 'ASC')
            ->get()->getResultArray();
    
        if (empty($subjects)) {
            return $this->response->setJSON([
                'success'     => true,
                'streams'     => $streams,
                'subjects'    => [],
                'hasSubjects' => false,
            ]);
        }
    
        return $this->response->setJSON([
            'success'     => true,
            'streams'     => $streams,
            'subjects'    => $subjects,
            'hasSubjects' => true,
        ]);
    }
    
    // ================================================================
    // AJAX — Get school departments
    // ================================================================
    
    public function getSchoolDepartments(int $schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        $db = \Config\Database::connect();
        $depts = $db->table('sch_department')
            ->select('sch_department.sch_dept_id, department.dept_name,
                      department.dept_icon, department.dept_theme')
            ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
            ->where('sch_department.sch_id_fk', $schId)
            ->where('sch_department.dept_status', 'Established')
            ->orderBy('department.dept_name', 'ASC')
            ->get()->getResultArray();
    
        return $this->response->setJSON([
            'success' => true,
            'departments' => $depts,
        ]);
    }
    
    // ================================================================
    // AJAX — Save teacher teaching subjects
    // ================================================================
    
    public function saveTeachingSubjects(int $admissionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $subjects = $this->request->getPost('subjects');
            if (empty($subjects) || !is_array($subjects)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No subjects provided.']);
            }

            $db = \Config\Database::connect();

            // Count current assignments
            $currentCount = $db->table('admission_teaching_subject')
                ->where('admission_id_fk', $admissionId)
                ->countAllResults();

            // Get already-assigned sch_sub_id_fk values to avoid duplicates
            $existing = $db->table('admission_teaching_subject')
                ->select('sch_sub_id_fk')
                ->where('admission_id_fk', $admissionId)
                ->get()->getResultArray();
            $existingIds = array_column($existing, 'sch_sub_id_fk');

            $added = 0;
            foreach ($subjects as $sub) {
                $schSubId = (int) $sub['sch_sub_id'];
                if (in_array($schSubId, $existingIds)) continue; // skip duplicates
                if (($currentCount + $added) >= 7) break;        // enforce max 7

                $db->table('admission_teaching_subject')->insert([
                    'admission_id_fk'      => $admissionId,
                    'sch_sub_id_fk'        => $schSubId,
                    'created_date'         => date('Y-m-d'),
                    'created_time'         => time(),
                    'adm_teach_sub_status' => 'Active',
                ]);
                $added++;
            }

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Teaching Subjects Added',
                'log_desc'    => $added . ' subject(s) added for admission ID ' . $admissionId,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-book"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'info',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => $added . ' subject(s) added successfully.',
                'added'   => $added,
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AdmissionController::saveTeachingSubjects] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // AJAX — Delete a single teaching subject by adm_teach_sub_id
    // ================================================================

    public function deleteTeachingSubject(int $admTeachSubId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $db  = \Config\Database::connect();
            $row = $db->table('admission_teaching_subject')
                ->where('adm_teach_sub_id', $admTeachSubId)
                ->get()->getRowArray();

            if (!$row) {
                return $this->response->setJSON(['success' => false, 'message' => 'Record not found.']);
            }

            $db->table('admission_teaching_subject')
                ->where('adm_teach_sub_id', $admTeachSubId)
                ->delete();

            return $this->response->setJSON(['success' => true, 'message' => 'Subject removed.']);

        } catch (\Exception $e) {
            log_message('error', '[AdmissionController::deleteTeachingSubject] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }
    
    // ================================================================
    // AJAX — Save HOD assignment
    // ================================================================
    
    public function saveHod(int $admissionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        try {
            $schDeptId = (int) $this->request->getPost('sch_dept_id');
            $isHod     = $this->request->getPost('is_hod') === '1';
    
            $db = \Config\Database::connect();
            $db->table('admission_hod')->where('admission_id_fk', $admissionId)->delete();
    
            if ($isHod && $schDeptId) {
                $db->table('admission_hod')->insert([
                    'admission_id_fk' => $admissionId,
                    'sch_dept_id_fk'  => $schDeptId,
                    'created_date'    => date('Y-m-d'),
                    'created_time'    => time(),
                ]);
            }
    
            return $this->response->setJSON(['success' => true, 'message' => 'HOD assignment saved.']);
    
        } catch (\Exception $e) {
            log_message('error', '[AdmissionController::saveHod] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }
    
    // ================================================================
    // AJAX — Save student leadership role
    // ================================================================
    
    public function saveStudentRole(int $admissionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        try {
            $role = $this->request->getPost('leadership_role') ?? '';
    
            $db = \Config\Database::connect();
            $db->table('admission_student_role')->where('admission_id_fk', $admissionId)->delete();
    
            if (!empty($role)) {
                $db->table('admission_student_role')->insert([
                    'admission_id_fk' => $admissionId,
                    'leadership_role' => $role,
                    'created_date'    => date('Y-m-d'),
                    'created_time'    => time(),
                ]);
            }
    
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Student Role Updated',
                'log_desc'    => 'Student leadership role updated for admission ID ' . $admissionId,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-medal-star"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
                'log_theme'   => 'info',
            ]);
    
            return $this->response->setJSON(['success' => true, 'message' => 'Student role saved.']);
    
        } catch (\Exception $e) {
            log_message('error', '[AdmissionController::saveStudentRole] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }
}