<?php

namespace App\Controllers;

class UserController extends BaseController
{
    protected $validation;
    protected $db;
    
    
    public function __construct(){
        helper('form', 'url');
        
        // Initialize services
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        
        // Initialize database connection
        $this->db = \Config\Database::connect();
    }
    
    
    public function index()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $view = '';
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'user');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('View User Listing', 'User', 'User Listing');
        
        // Check permission
        $accessCheck = $this->require_access('_user_listing');  
        if ($accessCheck !== true) {
            $view = 'app/auth/access_control';
        } else {
            $view = 'app/user/index';
        }
        
        $data['_view'] = $view;
        
        //Add user log
        $userLogData = [
            'user_id_fk' => $this->session->get('userID'),
            'ip_aadress' => $this->ipAddress,
            'user_agent' => $this->userAgent->getAgentString(),
            'user_device' => $this->deviceInfo['device_type'],
            'log_title' => 'View User Listing',
            'log_desc' => 'User view user listing.',
            'log_date' => date('Y-m-d'),
            'log_time' => time(),
            'log_icon' => '<i class="ki-duotone ki-user"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
            'log_theme' => 'warning'
        ];
        
        $addUserLog = $this->userLogModel->addUserLog($userLogData);
        
        return view('app/layouts/main', $data);
    }
    
    public function activate($id){
        
    }
    
    /**
     * Compact user list for the chat/users panel (AJAX, paginated)
     */
    public function getChatUserList()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'users' => []]);
        }

        $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
        $search  = trim($this->request->getGet('search') ?? '');
        $perPage = 50;
        $offset  = ($page - 1) * $perPage;

        $myRoleId       = (int) $this->session->get('roleID');
        $mySchId        = (int) $this->session->get('schID');
        $myId           = (int) $this->session->get('userID');
        $isUnaffiliated = $myRoleId === 1 || $mySchId === 0;

        $db = \Config\Database::connect();

        $searchSQL    = '';
        $searchParams = [];
        if ($search !== '') {
            $searchSQL    = " AND (u.fname LIKE ? OR u.lname LIKE ?)";
            $like         = '%' . $search . '%';
            $searchParams = [$like, $like];
        }

        if ($isUnaffiliated) {
            // Super Admin / Admin (or anyone without an active admission) can see and message everyone.
            $combinedSQL = "
                SELECT DISTINCT u.user_id, u.fname, u.lname, u.profile_photo, u.online_status
                FROM users u
                WHERE u.user_id != ? AND u.user_status = 'Active' $searchSQL
            ";
            $combinedParams = array_merge([$myId], $searchParams);
        } else {
            // Same-school users (normal visibility).
            $sameSchoolSQL = "
                SELECT DISTINCT u.user_id, u.fname, u.lname, u.profile_photo, u.online_status
                FROM users u
                INNER JOIN admission a ON a.user_id_fk = u.user_id AND a.sch_id_fk = ? AND a.admission_status = 'Active'
                WHERE u.user_id != ? AND u.user_status = 'Active' $searchSQL
            ";
            $sameSchoolParams = array_merge([$mySchId, $myId], $searchParams);

            // Plus: unaffiliated users (e.g. Super Admin/Admin) who have already messaged me and
            // whose conversation still has at least one message visible to me. They disappear again
            // once that conversation is fully cleared/deleted on my side.
            $crossSchoolSQL = "
                SELECT DISTINCT u.user_id, u.fname, u.lname, u.profile_photo, u.online_status
                FROM users u
                INNER JOIN chat_participants cp1 ON cp1.user_id = u.user_id
                INNER JOIN chat_participants cp2 ON cp2.conversation_id = cp1.conversation_id AND cp2.user_id = ?
                INNER JOIN chat_conversations cc ON cc.id = cp1.conversation_id AND cc.type = 'direct'
                WHERE u.user_id != ? AND u.user_status = 'Active'
                  AND NOT EXISTS (SELECT 1 FROM admission ad WHERE ad.user_id_fk = u.user_id AND ad.admission_status = 'Active')
                  AND EXISTS (
                      SELECT 1 FROM chat_messages m
                      LEFT JOIN chat_message_deletions cmd ON cmd.message_id = m.id AND cmd.user_id = ?
                      WHERE m.conversation_id = cc.id AND cmd.id IS NULL
                  )
                  $searchSQL
            ";
            $crossSchoolParams = array_merge([$myId, $myId, $myId], $searchParams);

            $combinedSQL    = "($sameSchoolSQL) UNION ($crossSchoolSQL)";
            $combinedParams = array_merge($sameSchoolParams, $crossSchoolParams);
        }

        $total = (int) $db->query(
            "SELECT COUNT(*) AS cnt FROM ($combinedSQL) AS combined",
            $combinedParams
        )->getRow()->cnt;

        $onlineCount = 0;
        if ($page === 1) {
            $onlineCount = (int) $db->query(
                "SELECT COUNT(*) AS cnt FROM ($combinedSQL) AS combined WHERE combined.online_status = 'Online'",
                $combinedParams
            )->getRow()->cnt;
        }

        $users = $db->query(
            "SELECT * FROM ($combinedSQL) AS combined
             ORDER BY CASE WHEN combined.online_status = 'Online' THEN 0 ELSE 1 END ASC, combined.fname ASC
             LIMIT ? OFFSET ?",
            array_merge($combinedParams, [$perPage, $offset])
        )->getResultArray();

        return $this->response->setJSON([
            'success'     => true,
            'users'       => $users,
            'hasMore'     => ($offset + $perPage) < $total,
            'nextPage'    => $page + 1,
            'total'       => $total,
            'onlineCount' => $onlineCount,
        ]);
    }

    /**
     * Single-user lookup used by the chat list's live "user just came online" socket handler —
     * returns the target user's row only if they're visible to me under the same rules as
     * getChatUserList() (same school, or an unaffiliated user I already have a conversation with).
     */
    public function getChatUserInfo($targetUserId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false]);
        }

        $targetUserId = (int) $targetUserId;
        $myId         = (int) $this->session->get('userID');
        if ($targetUserId === $myId) {
            return $this->response->setJSON(['success' => false]);
        }

        $myRoleId       = (int) $this->session->get('roleID');
        $mySchId        = (int) $this->session->get('schID');
        $isUnaffiliated = $myRoleId === 1 || $mySchId === 0;

        $db = \Config\Database::connect();

        if ($isUnaffiliated) {
            $row = $db->query(
                "SELECT user_id, fname, lname, profile_photo, online_status
                 FROM users WHERE user_id = ? AND user_status = 'Active'",
                [$targetUserId]
            )->getRowArray();
        } else {
            $row = $db->query("
                SELECT u.user_id, u.fname, u.lname, u.profile_photo, u.online_status
                FROM users u
                WHERE u.user_id = ? AND u.user_status = 'Active'
                  AND (
                      EXISTS (SELECT 1 FROM admission a WHERE a.user_id_fk = u.user_id AND a.sch_id_fk = ? AND a.admission_status = 'Active')
                      OR (
                          NOT EXISTS (SELECT 1 FROM admission ad WHERE ad.user_id_fk = u.user_id AND ad.admission_status = 'Active')
                          AND EXISTS (
                              SELECT 1
                              FROM chat_participants cp1
                              INNER JOIN chat_participants cp2 ON cp2.conversation_id = cp1.conversation_id AND cp2.user_id = ?
                              INNER JOIN chat_conversations cc ON cc.id = cp1.conversation_id AND cc.type = 'direct'
                              INNER JOIN chat_messages m ON m.conversation_id = cc.id
                              LEFT JOIN chat_message_deletions cmd ON cmd.message_id = m.id AND cmd.user_id = ?
                              WHERE cp1.user_id = u.user_id AND cmd.id IS NULL
                          )
                      )
                  )
            ", [$targetUserId, $mySchId, $myId, $myId])->getRowArray();
        }

        if (!$row) {
            return $this->response->setJSON(['success' => false]);
        }

        return $this->response->setJSON(['success' => true, 'user' => $row]);
    }

    /**
     * Get user listing data for DataTables (AJAX)
     */
    public function getUserListing()
    {
        try {
            $request = service('request');
            
            // Log incoming request for debugging
            log_message('debug', 'DataTables Request: ' . json_encode($request->getPost()));
            
            // Get DataTables parameters
            $draw = (int)($request->getPost('draw') ?? 1);
            $start = (int)($request->getPost('start') ?? 0);
            $length = (int)($request->getPost('length') ?? 10);
            
            // Get search parameter
            $searchData = $request->getPost('search');
            $searchValue = is_array($searchData) ? ($searchData['value'] ?? '') : '';
            
            // Get order parameter
            $orderData = $request->getPost('order');
            $orderColumnIndex = is_array($orderData) && isset($orderData[0]['column']) 
                ? (int)$orderData[0]['column'] 
                : 0;
            $orderDir = is_array($orderData) && isset($orderData[0]['dir']) 
                ? $orderData[0]['dir'] 
                : 'asc';
            
            // Map column index to database column name
            $columns = [
                'users.fname', 
                'users.email', 
                'users.phone', 
                'role.role_name', 
                'district.district_name',
                'users.user_status', 
                null // Actions column
            ];
            $orderColumn = $columns[$orderColumnIndex] ?? 'users.fname';
            
            // Prevent sorting on actions column
            if ($orderColumn === null || $orderColumnIndex === 6) {
                $orderColumn = 'users.fname';
            }
            
            // Validate sort direction
            $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
            
            log_message('debug', "Order by: {$orderColumn} {$orderDir}");
            
            // Determine visibility scope: Super Admin (roleID=1) and users with no
            // school affiliation (schID=0) see all users; everyone else sees only
            // users who have an admission record at the same school.
            $myRoleId  = (int) $this->session->get('roleID');
            $mySchId   = (int) $this->session->get('schID');
            $canSeeAll = ($myRoleId === 1 || $mySchId === 0);

            // Build query with joins
            $builder = $this->db->table('users');
            $builder->select('users.*, role.role_name, role.role_rank, district.district_name');
            $builder->join('user_role', 'user_role.user_id_fk = users.user_id', 'left');
            $builder->join('role', 'role.role_id = user_role.role_id_fk', 'left');
            $builder->join('district', 'district.district_id = users.district_id_fk', 'left');

            if (!$canSeeAll) {
                // Use EXISTS so no duplicate rows and counts stay accurate
                $builder->where("EXISTS (
                    SELECT 1 FROM admission
                    WHERE admission.user_id_fk = users.user_id
                      AND admission.sch_id_fk  = {$mySchId}
                )");
            }

            // Apply search filter
            if (!empty($searchValue)) {
                $builder->groupStart()
                    ->like('users.fname', $searchValue)
                    ->orLike('users.lname', $searchValue)
                    ->orLike('users.email', $searchValue)
                    ->orLike('users.phone', $searchValue)
                    ->orLike('role.role_name', $searchValue)
                    ->orLike('district.district_name', $searchValue)
                    ->orLike('users.user_status', $searchValue)
                    ->groupEnd();
            }

            // Get total filtered count before pagination
            $recordsFiltered = $builder->countAllResults(false);

            // Apply ordering
            $builder->orderBy($orderColumn, $orderDir);

            // Apply pagination
            if ($length != -1) {
                $builder->limit($length, $start);
            }

            // Get data
            $users = $builder->get()->getResultArray();

            // Total (unfiltered) count — scoped the same way
            if ($canSeeAll) {
                $recordsTotal = $this->db->table('users')->countAllResults();
            } else {
                $recordsTotal = (int) $this->db->query("
                    SELECT COUNT(*) AS cnt FROM users
                    WHERE EXISTS (
                        SELECT 1 FROM admission
                        WHERE admission.user_id_fk = users.user_id
                          AND admission.sch_id_fk  = ?
                    )
                ", [$mySchId])->getRow()->cnt;
            }
            
            log_message('debug', "Found {$recordsFiltered} filtered users out of {$recordsTotal} total");
            
            // Format data for DataTables
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    $this->formatUserName($user),
                    $user['email'] ?? 'N/A',
                    $user['phone'] ?? 'N/A',
                    $user['role_name'] ?? '<span class="badge badge-light-secondary">No Role</span>',
                    $user['district_name'] ?? 'N/A',
                    $this->formatStatus($user['user_status'] ?? 'Inactive'),
                    $this->createActionButtons($user['user_id'])
                ];
            }
            
            // Return JSON response
            $response = [
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ];
            
            log_message('debug', 'DataTables Response: ' . json_encode($response));
            
            return $this->response->setJSON($response);
            
        } catch (\Exception $e) {
            log_message('error', 'User listing error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'draw' => $request->getPost('draw') ?? 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Format user name with photo
     */
    private function formatUserName($user)
    {
        $fullName = trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''));
        $photo = $user['profile_photo'] ?? 'uploads/profilePhoto/default_male.jpg';
        $photoUrl = base_url('uploads/profilePhoto/' . $photo);
        
        // Generate initials for avatar if no photo
        $initials = '';
        if ($user['fname']) {
            $initials .= strtoupper(substr($user['fname'], 0, 1));
        }
        if ($user['lname']) {
            $initials .= strtoupper(substr($user['lname'], 0, 1));
        }
        
        return '
        <div class="d-flex align-items-center">
            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                <a href="' . base_url('user/detail/' . $user['user_id']) . '">
                    ' . ($photo && $photo !== 'default-avatar.png' 
                        ? '<div class="symbol-label"><img src="' . $photoUrl . '" alt="' . esc($fullName) . '" class="w-100" /></div>'
                        : '<div class="symbol-label fs-3 bg-light-primary text-primary">' . $initials . '</div>'
                    ) . '
                </a>
            </div>
            <div class="d-flex flex-column">
                <a href="' . base_url('user/detail/' . $user['user_id']) . '" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                    ' . esc($fullName) . '
                </a>
                <span class="text-muted fs-7">' . ucfirst($user['gender'] ?? 'Unknown') . '</span>
            </div>
        </div>';
    }
    
    /**
     * Format Status value
     */
    private function formatStatus($status)
    {
        if ($status === 'Active') {
            return '<span class="badge badge-light-success">Active</span>';
        } else {
            return '<span class="badge badge-light-danger">Inactive</span>';
        }
    }
    
    /**
     * Create action buttons HTML for each user
     * 
     * @param int $userId
     * @return string
     */
    private function createActionButtons($userId)
    {
        return '
        <div class="d-flex justify-content-end flex-shrink-0">
            <button class="btn btn-icon btn-bg-light btn-light-primary btn-sm me-1" data-kt-menu-trigger="click" title="Quick Action" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-down fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </button>
            
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                <div class="menu-item px-3">
                    <a href="' . base_url('user/detail/' . $userId) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-eye fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        View Details
                    </a>
                </div>
                
                <div class="menu-item px-3">
                    <a href="' . base_url('user/edit/' . $userId) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-pencil fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Edit User
                    </a>
                </div>
                
                <div class="separator my-2"></div>
                
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3 text-danger" data-kt-users-table-filter="delete_row" data-user-id="' . $userId . '">
                        <i class="ki-duotone ki-trash fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Delete User
                    </a>
                </div>
            </div>
        </div>';
    }
    
    /**
     * Display add user form
     */
    public function add()
    {
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'user/add');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('Add New User', 'User', 'Add User');
        
        // Check permission
        $accessCheck = $this->require_access('_add_user');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
        } else {
            // Roles: super admin sees all; everyone else only sees roles ranked below theirs
            $currentUserRoleId = $this->session->get('roleID');
            $isSuperAdmin      = (int) $currentUserRoleId === 1;
            $currentUserRole   = $this->roleModel->find($currentUserRoleId);
            $currentUserRank   = is_array($currentUserRole)
                ? (int) ($currentUserRole['role_rank'] ?? 999)
                : (int) ($currentUserRole->role_rank ?? 999);

            $allRoles = $this->roleModel->getAllRoles();
            if ($isSuperAdmin) {
                $data['roles'] = $allRoles;
            } else {
                $data['roles'] = array_values(array_filter($allRoles, function ($role) use ($currentUserRank) {
                    $rank = is_array($role) ? (int)($role['role_rank'] ?? 999) : (int)($role->role_rank ?? 999);
                    return $rank > $currentUserRank;
                }));
            }

            // Get all districts for dropdown
            $data['districts'] = $this->districtModel->findAll();
            $data['province'] = $this->provinceModel->getAllProvince();
            
            //get all schools 
            $data['schools'] = $this->schoolModel->getAllSchool();
            
            $data['_view'] = 'app/user/add';
        }
        
        return view('app/layouts/main', $data);
    }
    
    /**
     * Generate a unique username in the format YYMMRRRRRR (10 digits).
     * YY = last 2 digits of current year, MM = current month (zero-padded),
     * RRRRRR = 6 random digits. Loops until a username not in the DB is found.
     */
    public function generateUsername()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db      = \Config\Database::connect();
        $prefix  = date('ym'); // e.g. "2606"
        $attempt = 0;

        do {
            $candidate = $prefix . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $taken     = $db->table('users')->where('username', $candidate)->countAllResults() > 0;
            $attempt++;
        } while ($taken && $attempt < 30);

        if ($taken) {
            return $this->response->setJSON(['success' => false, 'message' => 'Could not generate a unique username. Please try again.']);
        }

        return $this->response->setJSON(['success' => true, 'username' => $candidate]);
    }

    /**
     * Store new user (Fixed with proper file upload and comprehensive logging)
     */
    public function store()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        log_message('info', '=== START: User creation process ===');
        
        // Load helper
        helper('password');
    
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'user/add');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            log_message('warning', 'User creation attempted without login');
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        log_message('info', 'User logged in - ID: ' . $this->session->get('userID'));
        
        // Check permission
        $accessCheck = $this->require_access('_add_user');
        if ($accessCheck !== true) {
            log_message('warning', 'User creation attempted without permission - User ID: ' . $this->session->get('userID'));
            return redirect()->to('user')->with('error', 'You do not have permission to add users.');
        }
        
        log_message('info', 'Permission check passed');
        
        // Get selected role to determine validation rules
        $selectedRoleId = $this->request->getPost('role_id');
        $selectedRole = null;
        $isStudentOrParent = false;
        $isStudent = false;
        $accountStatus = 'Active';
        $role = 'User';
        
        if ($selectedRoleId) {
            $selectedRole = $this->roleModel->find($selectedRoleId);
            $roleName = is_array($selectedRole) ? $selectedRole['role_name'] : $selectedRole->role_name;
            $isStudentOrParent = in_array($roleName, ['Student', 'Parent']);
            $isStudent = in_array($roleName, ['Student']);
            $isTeacher = in_array($roleName, ['Teacher']);
            $isParent = in_array($roleName, ['Parent']);
            $isSuperAdmin = in_array($roleName, ['Super Admin']);
            $role = $roleName;

            log_message('info', 'Selected role: ' . $roleName . ' (ID: ' . $selectedRoleId . ')');
            log_message('info', 'Is Student or Parent: ' . ($isStudentOrParent ? 'Yes' : 'No'));
        }
        
        // Base validation rules
        $rules = [
            'username' => [
                'rules'  => 'required|exact_length[10]|numeric|is_unique[users.username]',
                'errors' => [
                    'required'      => 'Username is required.',
                    'exact_length'  => 'Username must be exactly 10 digits.',
                    'numeric'       => 'Username must contain digits only.',
                    'is_unique'     => 'This username is already taken. Click Generate to get a new one.',
                ],
            ],
            'fname' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'First name is required',
                    'min_length' => 'First name must be at least 2 characters',
                    'max_length' => 'First name cannot exceed 100 characters'
                ]
            ],
            'lname' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'Last name is required',
                    'min_length' => 'Last name must be at least 2 characters',
                    'max_length' => 'Last name cannot exceed 100 characters'
                ]
            ],
            'phone' => [
                'rules' => 'permit_empty|min_length[7]|max_length[7]|numeric',
                'errors' => [
                    'min_length' => 'Phone must be 7 digits',
                    'max_length' => 'Phone must be 7 digits',
                    'numeric' => 'Phone must contain only numbers'
                ]
            ],
            'gender' => [
                'rules' => 'required|in_list[Male,Female,Other]',
                'errors' => [
                    'required' => 'Gender is required',
                    'in_list' => 'Please select a valid gender'
                ]
            ],
            'role_id' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Role is required',
                    'integer' => 'Please select a valid role'
                ]
            ],
            'femis_id' => [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'FEMIS ID must be a number'
                ]
            ],
        ];
        
        // Email is always optional but must be valid and unique if provided
        $rules['email'] = [
            'rules' => 'permit_empty|valid_email|is_unique[users.email]',
            'errors' => [
                'valid_email' => 'Please provide a valid email address',
                'is_unique' => 'This email is already registered'
            ]
        ];

        if ($isStudentOrParent) {
            $accountStatus = 'Pending Activation';
        }

        // Phone is always optional but must be exactly 7 digits if provided
        $rules['phone'] = [
            'rules' => 'permit_empty|min_length[7]|max_length[7]|numeric',
            'errors' => [
                'min_length' => 'Phone must be exactly 7 digits',
                'max_length' => 'Phone must be exactly 7 digits',
                'numeric' => 'Phone must contain only numbers'
            ]
        ];

        if ($isStudent) {
            $rules['dob'] = [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Date of Birth is required'
                ]
            ];
            log_message('info', 'DOB set as required for Student');
        }
        
        $rules['province'] = [
            'rules' => 'permit_empty|integer',
            'errors' => [
                'integer' => 'Invalid province selected'
            ]
        ];
        
        // District is required only if province is selected
        $provinceSelected = $this->request->getPost('province');
        
        if (!empty($provinceSelected)) {
            $rules['district'] = [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Please select a district',
                    'integer' => 'Invalid district selected'
                ]
            ];
            log_message('info', 'District validation added - Province ID: ' . $provinceSelected);
        } else {
            $rules['district'] = [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'Invalid district selected'
                ]
            ];
        }
        
        // Admission (school) is required when role is Student or Teacher
        if (($isStudent ?? false) || ($isTeacher ?? false)) {
            $rules['sch_id'] = [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Please select a school for admission',
                    'integer' => 'Invalid school selected'
                ]
            ];
        }

        // Enrollment fields are required when role is Student
        if ($isStudent) {
            $rules['stream_id_fk'] = [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Please select a stream',
                    'integer' => 'Invalid stream selected'
                ]
            ];
            $rules['enrol_term'] = [
                'rules' => 'required|in_list[1,2,3]',
                'errors' => [
                    'required' => 'Please select a term',
                    'in_list' => 'Please select a valid term (1, 2, or 3)'
                ]
            ];
            $rules['enrol_year'] = [
                'rules' => 'required|integer|greater_than_equal_to[2000]|less_than_equal_to[2099]',
                'errors' => [
                    'required' => 'Please enter an enrollment year',
                    'integer' => 'Year must be a valid number',
                    'greater_than_equal_to' => 'Year must be 2000 or later',
                    'less_than_equal_to' => 'Year must be 2099 or earlier'
                ]
            ];
        }

        log_message('info', 'Starting validation');

        // Validate input
        if (!$this->validate($rules)) {
            log_message('warning', 'Validation failed');
            log_message('debug', 'Validation errors: ' . json_encode($this->validator->getErrors()));
            
            $getDistrict = $this->districtModel->getDistrictByProvince($this->request->getPost('province'));
            $data['provinceDistrict'] = $getDistrict;
            $this->session->set($data);
            
            return redirect()->back()
                            ->withInput()
                            ->with('validation', $this->validator)
                            ->with('error', 'Please correct the errors below.');
        }
        
        log_message('info', 'Validation passed successfully');
        
        try {
            // Get email
            $email = $this->request->getPost('email');
            log_message('info', 'Email: ' . ($email ? $email : 'Not provided'));
            
            // Generate password
            $password = '';
            $tempPassword = '';
            
            if (!empty($email)) {
                $tempPassword = generateStrongPassword(10);
                $password = password_hash($tempPassword, PASSWORD_DEFAULT);
                log_message('info', 'Password generated for user with email');
            } else {
                log_message('info', 'No password generated (no email provided)');
            }
            
            // Prepare user data
            $dob = $this->request->getPost('dob');
            $dobFormatted = null;
            
            if (!empty($dob)) {
                $dobFormatted = date('Y-m-d', strtotime($dob));
                log_message('debug', 'DOB formatted: ' . $dobFormatted);
            }
            
            if($this->request->getPost('province') == 16){
                $district = 195;
            }else{
                $district = $this->request->getPost('district') ?: null;
            }
            
            $userData = [
                'password' => $password,
                'username' => $this->request->getPost('username'),
                'fname' => $this->request->getPost('fname'),
                'lname' => $this->request->getPost('lname'),
                'oname' => $this->request->getPost('oname'),
                'email' => $email,
                'phone' => $this->request->getPost('phone') ?: null,
                'gender' => $this->request->getPost('gender'),
                'dob' => $dobFormatted,
                'address' => $this->request->getPost('address') ?: null,
                'district_id_fk' => $district,
                'femis_id' => $this->request->getPost('femis_id') ?: null,
                'user_status' => $this->request->getPost('user_status') ?: 'Active',
                'is_a_parent' => $this->request->getPost('is_a_parent') ? 1 : 0,
                'created_date' => date('Y-m-d'),
                'created_time' => time(),
                'online_status' => 'Offline',
                'account_status' => $accountStatus
            ];
            
            log_message('info', 'User data prepared');
            log_message('debug', 'User data: ' . json_encode([
                'fname' => $userData['fname'],
                'lname' => $userData['lname'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'gender' => $userData['gender'],
                'is_a_parent' => $userData['is_a_parent']
            ]));
            
            // Insert user
            log_message('info', 'Attempting to insert user into database');
            $userId = $this->userModel->insert($userData);
            
            if ($userId) {
                log_message('info', 'User created successfully - User ID: ' . $userId);
                
                // Check if photo was uploaded
                $photo = $this->request->getFile('photo');
                $photoName = null;
                
                log_message('info', 'Checking for photo upload');
                log_message('debug', 'Photo object: ' . ($photo ? 'exists' : 'null'));
                
                if ($photo) {
                    log_message('debug', 'Photo name: ' . $photo->getName());
                    log_message('debug', 'Photo size: ' . $photo->getSize() . ' bytes');
                    log_message('debug', 'Photo MIME type: ' . $photo->getMimeType());
                    log_message('debug', 'Photo is valid: ' . ($photo->isValid() ? 'Yes' : 'No'));
                    log_message('debug', 'Photo has moved: ' . ($photo->hasMoved() ? 'Yes' : 'No'));
                    log_message('debug', 'Photo error code: ' . $photo->getError());
                }
                
                if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                    log_message('info', 'Photo upload detected - processing');
                    
                    // Validate file type
                    $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    $maxSize = 2048; // 2MB in KB
                    
                    $photoMimeType = $photo->getMimeType();
                    log_message('debug', 'Validating MIME type: ' . $photoMimeType);
                    
                    if (!in_array($photoMimeType, $validTypes)) {
                        log_message('error', 'Invalid file type: ' . $photoMimeType);
                        return redirect()->back()
                                        ->withInput()
                                        ->with('error', 'Invalid file type. Only JPG, PNG, and GIF are allowed.');
                    }
                    
                    log_message('info', 'MIME type validation passed');
                    
                    $photoSize = $photo->getSize();
                    log_message('debug', 'Validating file size: ' . $photoSize . ' bytes (Max: ' . ($maxSize * 1024) . ' bytes)');
                    
                    if ($photoSize > ($maxSize * 1024)) {
                        log_message('error', 'File size too large: ' . $photoSize . ' bytes');
                        return redirect()->back()
                                        ->withInput()
                                        ->with('error', 'File size too large. Maximum size is 2MB.');
                    }
                    
                    log_message('info', 'File size validation passed');
                    
                    // Check if upload directory exists
                    $uploadPath = FCPATH . 'uploads/profilePhoto';
                    log_message('debug', 'Upload path: ' . $uploadPath);
                    
                    if (!is_dir($uploadPath)) {
                        log_message('warning', 'Upload directory does not exist, creating: ' . $uploadPath);
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    if (!is_writable($uploadPath)) {
                        log_message('error', 'Upload directory is not writable: ' . $uploadPath);
                        return redirect()->back()
                                        ->withInput()
                                        ->with('error', 'Upload directory is not writable. Please contact administrator.');
                    }
                    
                    log_message('info', 'Upload directory is writable');
                    
                    // Generate unique filename
                    $photoName = $photo->getRandomName();
                    log_message('info', 'Generated photo filename: ' . $photoName);
                    
                    // Move to uploads/profilePhoto folder
                    try {
                        $moved = $photo->move($uploadPath, $photoName);
                        
                        if ($moved) {
                            log_message('info', 'Photo uploaded successfully: ' . $photoName);
                            log_message('debug', 'Full path: ' . $uploadPath . '/' . $photoName);
                            $this->userModel->updateUser($userId,array('profile_photo' => $photoName));
                        } else {
                            log_message('error', 'Photo move failed - returned false');
                            $photoName = null;
                        }
                    } catch (\Exception $e) {
                        log_message('error', 'Photo upload exception: ' . $e->getMessage());
                        log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                        $photoName = null;
                    }
                    
                } else {
                    if ($photo) {
                        if (!$photo->isValid()) {
                            log_message('warning', 'Photo is not valid - Error: ' . $photo->getErrorString());
                        }
                        if ($photo->hasMoved()) {
                            log_message('warning', 'Photo has already been moved');
                        }
                    } else {
                        log_message('info', 'No photo uploaded');
                    }
                }
                
                // Generate activation code
                $code = md5(time() + $userId);
                log_message('debug', 'Activation code generated: ' . $code);
                
                $this->userModel->update($userId, ['password_reset_code' => $code]);
                log_message('info', 'Activation code saved');
                
                if (!empty($password)) {
                    // Keep track of user password
                    log_message('info', 'Saving password to password history');
                    
                    $passwordData = [
                        'user_id_fk' => $userId,
                        'password' => $password,
                        'date_created' => date('Y-m-d'),
                        'time_created' => time(),
                        'password_status' => 'Active'
                    ];
                    
                    $addPassword = $this->userPasswordModel->insert($passwordData);
                    
                    if ($addPassword) {
                        log_message('info', 'Password history saved successfully');
                    } else {
                        log_message('error', 'Failed to save password history');
                    }
                }
                
                // Assign role to user
                log_message('info', 'Assigning role to user - Role ID: ' . $selectedRoleId);
                
                $userRoleData = [
                    'user_id_fk' => $userId,
                    'role_id_fk' => $selectedRoleId,
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s'),
                    'user_role_status' => 'Active'
                ];
                
                $roleAssigned = $this->userRoleModel->insert($userRoleData);
                
                if ($roleAssigned) {
                    log_message('info', 'Role assigned successfully');
                } else {
                    log_message('error', 'Failed to assign role');
                }
                
                
                //add admission data
                if($isSuperAdmin || $isParent){
                    //skip admission
                    //parents admission will be requested by parents when they can login to their portal and request access
                }else{
                    $admissionData = array(
                        'user_id_fk' =>  $userId,
                        'sch_id_fk' => $this->request->getPost('sch_id'),
                        'admission_date' => date('Y-m-d'),
                        'admission_time' => time(),
                        'admission_status' => 'Active',
                    );
                    
                    $addAdmission = $this->admissionModel->addAdmission($admissionData);
                    
                    if($addAdmission){
                        //add enrollment data
                        if($isStudent){
                            $enrolmentData = array(
                                'admission_id_fk' => $addAdmission,
                                'stream_id_fk'    => (int) $this->request->getPost('stream_id_fk'),
                                'enrol_term'      => $this->request->getPost('enrol_term'),
                                'enrol_year'      => $this->request->getPost('enrol_year'),
                                'enrol_date'      => date('Y-m-d'),
                                'enrol_time'      => time(),
                                'enrol_note'      => $this->request->getPost('enrol_note'),
                                'enrol_status'    => 'Active',
                            );
                            
                            $addEnrolment = $this->enrolmentModel->addEnrolment($enrolmentData);

                            if ($addEnrolment) {
                                // Find the active classroom for this stream + year
                                $streamId  = (int) $this->request->getPost('stream_id_fk');
                                $enrolYear = (int) $this->request->getPost('enrol_year');

                                $classRow = $this->db->table('classroom')
                                    ->where('stream_id_fk',  $streamId)
                                    ->where('class_year',    $enrolYear)
                                    ->where('class_status',  'Active')
                                    ->get()->getRowArray();

                                if ($classRow) {
                                    $classId = (int) $classRow['class_id'];

                                    // Auto-enrol into classroom_student if not already there
                                    $alreadyEnrolled = $this->db->table('classroom_student')
                                        ->where('class_id_fk', $classId)
                                        ->where('user_id_fk',  $userId)
                                        ->countAllResults() > 0;

                                    if (!$alreadyEnrolled) {
                                        $this->db->table('classroom_student')->insert([
                                            'class_id_fk'       => $classId,
                                            'user_id_fk'        => $userId,
                                            'class_stud_status' => 'Active',
                                            'admitted_at'       => date('Y-m-d'),
                                            'admitted_by'       => (int) $this->session->get('userID'),
                                        ]);
                                    }

                                    // Core subjects
                                    $coreSubIds = array_filter(
                                        array_map('intval', (array) ($this->request->getPost('exam_core_subs') ?? []))
                                    );
                                    foreach ($coreSubIds as $schSubId) {
                                        $this->db->table('student_subject')->insert([
                                            'admission_id_fk' => (int) $addAdmission,
                                            'class_id_fk'     => $classId,
                                            'sch_sub_id_fk'   => $schSubId,
                                            'stud_sub_status' => 'Active',
                                        ]);
                                    }

                                    // Optional subjects (one per group: exam_opt_group_{N})
                                    foreach ($this->request->getPost() as $key => $val) {
                                        if (preg_match('/^exam_opt_group_\d+$/', $key) && !empty($val)) {
                                            $this->db->table('student_subject')->insert([
                                                'admission_id_fk' => (int) $addAdmission,
                                                'class_id_fk'     => $classId,
                                                'sch_sub_id_fk'   => (int) $val,
                                                'stud_sub_status' => 'Active',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
                // Auto-create notification preferences: all ON if user has email, all OFF otherwise
                $notifVal = !empty($email) ? 1 : 0;
                $this->userNotificationModel->saveForUser($userId, [
                    'notif_dashboard'     => $notifVal,
                    'notif_rbac'          => $notifVal,
                    'notif_user'          => $notifVal,
                    'notif_school'        => $notifVal,
                    'notif_admission'     => $notifVal,
                    'notif_enrolment'     => $notifVal,
                    'notif_classroom'     => $notifVal,
                    'notif_exam'          => $notifVal,
                    'notif_conduct'       => $notifVal,
                    'notif_timetable'     => $notifVal,
                    'notif_event'         => $notifVal,
                    'notif_communication' => $notifVal,
                    'notif_security'      => $notifVal,
                    'notif_medical'       => $notifVal,
                    'notif_reference'     => $notifVal,
                ]);

                if (!empty($email)) {
                    // Prepare email data
                    log_message('info', 'Preparing to send activation email');
                    
                    $emailData = [
                        'name' => $this->request->getPost('fname') . ' ' . $this->request->getPost('lname'),
                        'email' => $email,
                        'code' => $code,
                        'page' => 'user_activation_notification',
                        'subject' => 'Activate User Account',
                        'role' => $role
                    ];
                    
                    try {
                        $emailSent = $this->sendEmail($emailData);
                        
                        if ($emailSent) {
                            log_message('info', 'Activation email sent successfully to: ' . $email);
                        } else {
                            log_message('warning', 'Failed to send activation email to: ' . $email);
                        }
                    } catch (\Exception $e) {
                        log_message('error', 'Email sending exception: ' . $e->getMessage());
                    }
                } else {
                    log_message('info', 'No email to send (email not provided)');
                }
                
                // Log activity
                log_message('info', 'Logging user creation activity');
                
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Add New User',
                    'log_desc' => 'User "' . $userData['fname'] . ' ' . $userData['lname'] . '" has been created successfully!',
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-user-tick"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
                    'log_theme' => 'success'
                ];
                
                $this->userLogModel->insert($userLogData);
                log_message('info', 'Activity logged successfully');
                
                log_message('info', '=== END: User creation successful ===');
                
                return redirect()->to('user')
                                ->with('success', 'User "' . $userData['fname'] . ' ' . $userData['lname'] . '" has been created successfully!');
            } else {
                log_message('error', 'Failed to insert user into database');
                
                // Delete uploaded photo if user creation failed
                if ($photoName && file_exists(FCPATH . 'uploads/profilePhoto/' . $photoName)) {
                    @unlink(FCPATH . 'uploads/profilePhoto/' . $photoName);
                    log_message('info', 'Deleted uploaded photo due to user creation failure');
                }
                
                log_message('info', '=== END: User creation failed ===');
                
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Failed to create user. Please try again.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'EXCEPTION during user creation: ' . $e->getMessage());
            log_message('error', 'Exception file: ' . $e->getFile());
            log_message('error', 'Exception line: ' . $e->getLine());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Delete uploaded photo if error occurred
            if (isset($photoName) && $photoName && file_exists(FCPATH . 'uploads/profilePhoto/' . $photoName)) {
                @unlink(FCPATH . 'uploads/profilePhoto/' . $photoName);
                log_message('info', 'Deleted uploaded photo due to exception');
            }
            
            log_message('info', '=== END: User creation failed with exception ===');
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }



    // UPDATED detail() method for UserController.php
    
    /**
     * Display user detail
     */
    public function detail($userId)
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'user/detail/' . $userId);
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('User Details', 'User', 'User Listing');
        
        // Check permission
        $accessCheck = $this->require_access('_user_profile');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
        } else {
            // Get user data with joins
            $user = $this->userModel->findUserFull($userId);
            
            if (!$user) {
                return redirect()->to('user')->with('error', 'User not found.');
            }
            
            // Expire old sessions first
            $this->userSessionModel->expireOldSessions($userId);
            
            // Pass sessions to view
            $data['sessions'] = $this->userSessionModel->getActiveSessions($userId);
            $data['currentSessionToken'] = $this->session->get('sessionToken');
            
            $data['canEditUser'] = $this->require_access('_edit_user') === true;
            
            // Pass data to view
            $data['role'] = $this->userRoleModel->findActiveUserRole($userId);
            $data['user'] = $user;
            $data['userID'] = $userId;
            $data['next_of_kin'] = $this->nextOfKinModel->getByUserId($userId);
            $data['role'] = $this->userRoleModel->findActiveUserRole($userId);
            $data['roles'] = $this->roleModel->getAllRoles();
            
            // Add to detail() method data array
            $notifications = $this->userNotificationModel->getByUser($userId);
            $data['notifications'] = $notifications; // can be null
            $data['isOwnProfile']    = ($this->session->get('userID') == $userId);
            $data['twoFactorData']   = $this->twoFactorModel->get2FAData($userId);
            
            $data['sessionUserID'] = $this->session->get('userID');
            
            // Add after existing data loading
            $viewedRole    = $this->userRoleModel->findActiveUserRole($userId);
            $roleCatId     = (int) ($viewedRole['role_cat_id'] ?? 0);
            $showAdmission = in_array($roleCatId, [2, 3, 4, 5]); // School Admin, Teacher, Student, Support Staff
            $isStudent     = $roleCatId === 4;
            
            $admissions = [];
            if ($showAdmission) {
                if ($isStudent) {
                    $admissions = $this->admissionModel->getAdmissionWithEnrolment($userId);
                } else {
                    $admissions = $this->admissionModel->getAdmissionWithSchool($userId);
                }
            }
            
            $data['showAdmission'] = $showAdmission;
            $data['isStudent']     = $isStudent;
            $data['admissions']    = $admissions;
            $data['roleCatId']     = $roleCatId;

            // Parent–student linking
            $isAParentFlag = (int) ($user['is_a_parent'] ?? 0);
            $linkedChildren = [];
            $linkedParents  = [];
            if ($roleCatId === 6 || $isAParentFlag === 1) {
                $linkedChildren = $this->parentStudentModel->getChildrenOf((int) $userId);
            }
            if ($roleCatId === 4) {
                $linkedParents = $this->parentStudentModel->getParentsOf((int) $userId);
            }
            $data['linkedChildren'] = $linkedChildren;
            $data['linkedParents']  = $linkedParents;
            $data['isAParentFlag']  = $isAParentFlag;
            
            // FOR EDIT MODAL: Add provinces and districts
            $data['provinces'] = $this->provinceModel->findAll();
            
            // Get districts for user's province
            if (!empty($user['province_id'])) {
                $data['districts'] = $this->districtModel
                    ->where('province_id_fk', $user['province_id'])
                    ->findAll();
            } else {
                $data['districts'] = [];
            }
            
            $data['_view'] = 'app/user/detail';
        }
        
        return view('app/layouts/main', $data);
    }

    /**
     * Own profile — same view as detail() but always allowed (no permission gate).
     */
    public function my()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $userId = (int) $this->session->get('userID');

        $this->setPageData('My Profile', 'Profile', 'My Profile');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) {
            return redirect()->to('dashboard')->with('error', 'Profile not found.');
        }

        $this->userSessionModel->expireOldSessions($userId);

        $viewedRole = $this->userRoleModel->findActiveUserRole($userId);
        $roleCatId  = (int) ($viewedRole['role_cat_id'] ?? 0);
        $isStudent  = $roleCatId === 4;
        $showAdmission = in_array($roleCatId, [2, 3, 4, 5]);

        $admissions = [];
        if ($showAdmission) {
            $admissions = $isStudent
                ? $this->admissionModel->getAdmissionWithEnrolment($userId)
                : $this->admissionModel->getAdmissionWithSchool($userId);
        }

        $linkedChildren = [];
        $linkedParents  = [];
        if ($roleCatId === 6 || (int) ($user['is_a_parent'] ?? 0) === 1) {
            $linkedChildren = $this->parentStudentModel->getChildrenOf($userId);
        }
        if ($isStudent) {
            $linkedParents = $this->parentStudentModel->getParentsOf($userId);
        }

        $province = (int) ($user['province_id'] ?? 0);

        $data['user']                = $user;
        $data['userID']              = $userId;
        $data['role']                = $viewedRole;
        $data['roles']               = $this->roleModel->getAllRoles();
        $data['sessions']            = $this->userSessionModel->getActiveSessions($userId);
        $data['currentSessionToken'] = $this->session->get('sessionToken');
        $data['notifications']       = $this->userNotificationModel->getByUser($userId);
        $data['emailVerified']       = (int) ($user['email_verified'] ?? 0);
        $data['twoFactorData']       = $this->twoFactorModel->get2FAData($userId);
        $data['next_of_kin']         = $this->nextOfKinModel->getByUserId($userId);
        $data['isOwnProfile']        = true;
        $data['canEditUser']         = true;
        $data['showAdmission']       = $showAdmission;
        $data['isStudent']           = $isStudent;
        $data['admissions']          = $admissions;
        $data['roleCatId']           = $roleCatId;
        $data['linkedChildren']      = $linkedChildren;
        $data['linkedParents']       = $linkedParents;
        $data['isAParentFlag']       = (int) ($user['is_a_parent'] ?? 0);
        $data['provinces']           = $this->provinceModel->findAll();
        $data['districts']           = $province
            ? $this->districtModel->where('province_id_fk', $province)->findAll()
            : [];
        $data['sessionUserID']       = $userId;
        $data['_view']               = 'app/user/detail';

        // Always load for own-profile modal (don't gate on $isStudent — role lookup can fail)
        $db = \Config\Database::connect();
        $data['refCategories'] = $db->table('reference_category')
            ->where('ref_cat_id !=', 1)
            ->orderBy('ref_cat_id', 'ASC')
            ->get()->getResultArray();

        // Direct admission+school query — use full table names (no alias) to avoid CI4 builder quirks
        $rawAdmissions = $db->table('admission')
            ->select('admission.admission_id, admission.sch_id_fk, admission.admission_status, admission.admission_date, school.sch_name, school.sch_logo')
            ->join('school', 'school.sch_id = admission.sch_id_fk', 'left')
            ->where('admission.user_id_fk', $userId)
            ->orderBy('admission.admission_id', 'DESC')
            ->get()->getResultArray();

        // Deduplicate by admission_id
        $seenAdm = [];
        $data['admissionsForModal'] = [];
        foreach ($rawAdmissions as $adm) {
            if (!isset($seenAdm[$adm['admission_id']])) {
                $seenAdm[$adm['admission_id']] = true;
                $data['admissionsForModal'][] = $adm;
            }
        }

        return view('app/layouts/main', $data);
    }

    /**
     * Save email notification preferences via AJAX
     */
    public function saveNotifications()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        
        $this->session->set('activeTab','security');
    
        $userId       = (int) $this->session->get('userID');
        $targetUserId = (int) $this->request->getPost('user_id');
    
        // Debug log to confirm values match
        log_message('debug', '[saveNotifications] Session userID: ' . $userId . ' | POST user_id: ' . $targetUserId);
    
        if ($targetUserId !== $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You can only update your own notifications. Session: ' . $userId . ' | Submitted: ' . $targetUserId
            ]);
        }

        // Gate on email presence and verification
        $currentUser = $this->userModel->find($userId);
        if (empty($currentUser['email'])) {
            return $this->response->setJSON([
                'success' => false,
                'code'    => 'no_email',
                'message' => 'You need to add an email address before enabling email notifications. Please update your email in the Security tab.',
            ]);
        }
        if (empty($currentUser['email_verified']) || (int) $currentUser['email_verified'] !== 1) {
            return $this->response->setJSON([
                'success' => false,
                'code'    => 'unverified',
                'message' => 'Your email address has not been verified yet. Please verify your email before enabling notifications.',
            ]);
        }

        try {
            $modules = [
                'dashboard', 'rbac', 'user', 'school',
                'admission', 'enrolment', 'classroom', 'exam',
                'conduct', 'timetable', 'event', 'communication',
                'security', 'medical', 'reference',
            ];
    
            $settings = [];
            foreach ($modules as $module) {
                $settings['notif_' . $module] = $this->request->getPost('notif_' . $module) ? 1 : 0;
            }
    
            $this->userNotificationModel->saveForUser($userId, $settings);
    
            $this->userLogModel->insert([
                'user_id_fk'  => $userId,
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Notification Settings Updated',
                'log_desc'    => 'Email notification preferences updated.',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-notification"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'info',
            ]);
    
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification preferences saved successfully.',
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[UserController::saveNotifications] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save preferences.']);
        }
    }
    
    /**
     * Get user logs via AJAX with pagination, search and filters
     */
    public function getUserLogs($userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        try {
            $perPage  = (int)($this->request->getGet('perPage')  ?? 20);
            $search   = $this->request->getGet('search')   ?? '';
            $dateFrom = $this->request->getGet('dateFrom')  ?? '';
            $dateTo   = $this->request->getGet('dateTo')    ?? '';
            $theme    = $this->request->getGet('theme')     ?? '';
            $logType  = $this->request->getGet('logType')   ?? '';

            $logs   = $this->userLogModel->getUserLogs($userId, $perPage, $search, $dateFrom, $dateTo, $theme, $logType);
            $pager  = $this->userLogModel->pager;
    
            $html = '';
            if (!empty($logs)) {
                foreach ($logs as $log) {
                    $date      = date('d M Y', strtotime($log['log_date']));
                    $time      = date('h:i A', $log['log_time']);
                    $theme     = $log['log_theme']   ?? 'primary';
                    $logType   = $log['log_type']    ?? 'Activity';
                    $logStatus = $log['log_status']  ?? 'Read';
                    $typeBadge = $logType === 'Alert'
                        ? '<span class="badge badge-light-danger fs-9">Alert</span>'
                        : '<span class="badge badge-light-primary fs-9">Activity</span>';
                    $unreadDot = $logStatus === 'Unread'
                        ? '<span class="bullet bullet-dot bg-danger h-6px w-6px ms-1" title="Unread"></span>'
                        : '';
                    $html .= '
                    <tr>
                        <td class="min-w-40px">
                            <div class="d-flex align-items-center justify-content-center w-35px h-35px rounded-circle bg-light-' . esc($theme) . '">
                                ' . $log['log_icon'] . '
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-gray-800 fs-7">' . esc($log['log_title']) . $unreadDot . '</div>
                            <div class="text-muted fs-8">' . esc($log['log_desc']) . '</div>
                        </td>
                        <td class="fs-8">' . $typeBadge . '</td>
                        <td class="fs-8 text-muted">
                            <div>' . esc($log['ip_aadress'] ?? '—') . '</div>
                            <div>' . esc($log['user_device'] ?? '—') . '</div>
                        </td>
                        <td class="pe-0 text-end min-w-130px fs-8 text-muted">
                            <div class="fw-bold text-gray-700">' . $date . '</div>
                            <div>' . $time . '</div>
                        </td>
                    </tr>';
                }
            } else {
                $html = '
                <tr>
                    <td colspan="5" class="text-center text-muted py-10">
                        <i class="ki-duotone ki-information-5 fs-3x text-muted mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="fw-bold">No logs found</div>
                        <div class="text-gray-500 fs-7">Try adjusting your search or filters</div>
                    </td>
                </tr>';
            }
    
            // Build pagination info
            $pagination = [
                'currentPage' => $pager->getCurrentPage('log'),
                'totalPages'  => $pager->getPageCount('log'),
                'total'       => $pager->getTotal('log'),
                'perPage'     => $perPage,
                'from'        => (($pager->getCurrentPage('log') - 1) * $perPage) + 1,
                'to'          => min($pager->getCurrentPage('log') * $perPage, $pager->getTotal('log')),
            ];
    
            return $this->response->setJSON([
                'success'    => true,
                'html'       => $html,
                'pagination' => $pagination,
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[UserController::getUserLogs] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }
    
    /**
     * Download all user logs as CSV
     */
    public function downloadUserLogs($userId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }
    
        try {
            $search   = $this->request->getGet('search')   ?? '';
            $dateFrom = $this->request->getGet('dateFrom')  ?? '';
            $dateTo   = $this->request->getGet('dateTo')    ?? '';
            $theme    = $this->request->getGet('theme')     ?? '';
    
            $user = $this->userModel->find($userId);
            if (!$user) {
                return redirect()->back()->with('error', 'User not found.');
            }
    
            $logs = $this->userLogModel->getAllUserLogsForExport($userId, $search, $dateFrom, $dateTo, $theme);
    
            // Build CSV
            $filename = 'user_logs_' . $user['fname'] . '_' . $user['lname'] . '_' . date('Ymd_His') . '.csv';
    
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');
    
            $output = fopen('php://output', 'w');
    
            // CSV Header row
            fputcsv($output, [
                'Log ID',
                'Title',
                'Description',
                'IP Address',
                'Device',
                'Date',
                'Time',
                'Theme'
            ]);
    
            // CSV Data rows
            foreach ($logs as $log) {
                fputcsv($output, [
                    $log['user_log_id'],
                    $log['log_title'],
                    $log['log_desc'],
                    $log['ip_aadress'] ?? '',
                    $log['user_device'] ?? '',
                    $log['log_date'],
                    date('h:i A', $log['log_time']),
                    $log['log_theme'],
                ]);
            }
    
            fclose($output);
            exit;
    
        } catch (\Exception $e) {
            log_message('error', '[UserController::downloadUserLogs] ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to download logs.');
        }
    }
    
    /**
     * Sign out a single session
     */
    public function signOutSession($sessionId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        if (!$this->require_access('_edit_user')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied']);
        }
    
        try {
            $session = $this->userSessionModel->find($sessionId);
    
            if (!$session) {
                return $this->response->setJSON(['success' => false, 'message' => 'Session not found']);
            }
    
            // Prevent signing out current session via this method
            if ($session['session_token'] === $this->session->get('sessionToken')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Cannot sign out your current session here.']);
            }
    
            $this->userSessionModel->signOutOne($sessionId, $session['user_id_fk']);
    
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Sign Out Session',
                'log_desc'    => 'Signed out session ID ' . $sessionId . ' for user ID ' . $session['user_id_fk'],
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-entrance-right"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'warning',
                'log_type'    => 'Alert',
            ]);
    
            return $this->response->setJSON(['success' => true, 'message' => 'Session signed out successfully.']);
    
        } catch (\Exception $e) {
            log_message('error', '[UserController::signOutSession] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }
    
    /**
     * Sign out all sessions for a user
     */
    public function signOutAllSessions($userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        if (!$this->require_access('_edit_user')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied']);
        }
    
        try {
            // Keep current session alive if signing out own sessions
            $currentToken = $this->session->get('userID') == $userId
                ? $this->session->get('sessionToken')
                : null;
    
            $this->userSessionModel->signOutAll($userId, $currentToken);
    
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Sign Out All Sessions',
                'log_desc'    => 'Signed out all sessions for user ID ' . $userId,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-entrance-right"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'danger',
                'log_type'    => 'Alert',
            ]);
    
            return $this->response->setJSON(['success' => true, 'message' => 'All sessions signed out successfully.']);
    
        } catch (\Exception $e) {
            log_message('error', '[UserController::signOutAllSessions] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }
    
    /**
     * Display edit user form
     */
    public function edit($userId)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'user/edit/' . $userId);
        
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        $this->setPageData('Edit User', 'User', 'User Listing');
        
        $accessCheck = $this->require_access('_edit_user');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }
        
        $currentUserRoleId = $this->session->get('roleID');
        $currentUserRole   = $this->roleModel->find($currentUserRoleId);
        $currentUserRank   = is_array($currentUserRole)
            ? ($currentUserRole['role_rank'] ?? 999)
            : ($currentUserRole->role_rank ?? 999);
        
        $builder = $this->db->table('users');
        $builder->select('users.*, user_role.role_id_fk, role.role_name, role.role_rank');
        $builder->join('user_role', 'user_role.user_id_fk = users.user_id', 'left');
        $builder->join('role',      'role.role_id = user_role.role_id_fk', 'left');
        $builder->where('users.user_id', $userId);
        $user = $builder->get()->getRowArray();
        
        if (!$user) {
            return redirect()->to('user')->with('error', 'User not found.');
        }
        
        $targetUserRank = $user['role_rank'] ?? 999;
        if ($currentUserRank > $targetUserRank) {
            $targetUserName = $user['fname'] . ' ' . $user['lname'];
            $targetUserRole = $user['role_name'] ?? 'Unknown Role';
            return redirect()->to('user')
                ->with('error', "Access denied. You cannot edit {$targetUserName} as they hold a higher authority role ({$targetUserRole} - Rank {$targetUserRank}) than your current role (Rank {$currentUserRank}).");
        }
        
        // Load roles with their category ID so the view can attach data-attributes
        $allRolesRaw = $this->db->table('role')
            ->select('role.role_id, role.role_name, role.role_rank, role.role_cat_id_fk')
            ->orderBy('role.role_name', 'ASC')
            ->get()->getResultArray();

        $availableRoles = [];
        foreach ($allRolesRaw as $role) {
            if ($currentUserRank <= (int)($role['role_rank'] ?? 999)) {
                $availableRoles[] = $role;
            }
        }
        
        // Location resolution
        $district_id      = $user['district_id_fk'] ?? null;
        $province_id      = null;
        $provinceDistrict = [];
        
        if (!empty($district_id)) {
            $district = $this->districtModel->getDistrictFull($district_id);
        
            if (!empty($district)) {
                // Now unambiguous — district.province_id_fk is explicitly aliased
                $province_id = $district['province_id_fk'] ?? null;
        
                $provinceDistrict = !empty($province_id)
                    ? $this->districtModel->getDistrictByProvince($province_id)
                    : [];
            }
        }
        
        // Active admission for this user (Student/Teacher) — join school name for display
        $activeAdmission = $this->db->table('admission')
            ->select('admission.*, school.sch_name')
            ->join('school', 'school.sch_id = admission.sch_id_fk', 'left')
            ->where('admission.user_id_fk', $userId)
            ->where('admission.admission_status', 'Active')
            ->orderBy('admission.admission_id', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        // Resolve current role category so the view knows whether to show the admission section
        $currentRoleCatId = 0;
        if (!empty($user['role_id_fk'])) {
            $roleRow = $this->db->table('role')
                ->where('role_id', $user['role_id_fk'])
                ->get()->getRowArray();
            $currentRoleCatId = (int)($roleRow['role_cat_id_fk'] ?? 0);
        }

        $data['user']               = $user;
        $data['roles']              = $availableRoles;
        $data['province']           = $this->provinceModel->getAllProvince();
        $data['district_id']        = $district_id;
        $data['province_id']        = $province_id;
        $data['provinceDistrict']   = $provinceDistrict;
        $data['schools']            = $this->schoolModel->getAllSchool();
        $data['activeAdmission']    = $activeAdmission ?: null;
        $data['currentRoleCatId']   = $currentRoleCatId;
        $data['_view']              = 'app/user/edit';

        return view('app/layouts/main', $data);
    }
    
    /**
     * Update user (FIXED - All Issues Corrected)
     */
    public function update($userId)
    {
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'user/edit/' . $userId);
        
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        $accessCheck = $this->require_access('_edit_user');
        if ($accessCheck !== true) {
            return redirect()->to('user')->with('error', 'You do not have permission to edit users.');
        }
        
        $existingUser = $this->userModel->find($userId);
        if (!$existingUser) {
            return redirect()->to('user')->with('error', 'User not found.');
        }
        
        // Role rank security checks
        $currentUserRoleId = $this->session->get('roleID');
        $currentUserRole = $this->roleModel->find($currentUserRoleId);
        $currentUserRank = is_array($currentUserRole) ? ($currentUserRole['role_rank'] ?? 999) : ($currentUserRole->role_rank ?? 999);
        
        $builder = $this->db->table('users');
        $builder->select('users.*, role.role_rank, role.role_name');
        $builder->join('user_role', 'user_role.user_id_fk = users.user_id', 'left');
        $builder->join('role', 'role.role_id = user_role.role_id_fk', 'left');
        $builder->where('users.user_id', $userId);
        $targetUser = $builder->get()->getRowArray();
        
        $targetUserRank = $targetUser['role_rank'] ?? 999;
        
        if ($currentUserRank > $targetUserRank) {
            $targetUserName = $existingUser['fname'] . ' ' . $existingUser['lname'];
            $targetUserRole = $targetUser['role_name'] ?? 'Unknown Role';
            
            return redirect()->to('user')
                ->with('error', "Access denied. You cannot modify {$targetUserName} as they hold a higher authority role ({$targetUserRole} - Rank {$targetUserRank}) than your current role (Rank {$currentUserRank}).");
        }
        
        $newRoleId = $this->request->getPost('role_id');
        if ($newRoleId) {
            $newRole = $this->roleModel->find($newRoleId);
            $newRoleRank = is_array($newRole) ? ($newRole['role_rank'] ?? 999) : ($newRole->role_rank ?? 999);
            
            if ($currentUserRank > $newRoleRank) {
                $newRoleName = is_array($newRole) ? ($newRole['role_name'] ?? 'Unknown') : ($newRole->role_name ?? 'Unknown');
                
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Access denied. You cannot assign the role '{$newRoleName}' (Rank {$newRoleRank}) as it has higher authority than your current role (Rank {$currentUserRank}).");
            }
        }
        
        // Validation rules
        $rules = [
            'fname' => 'required|min_length[2]|max_length[100]',
            'lname' => 'required|min_length[2]|max_length[100]',
            'email' => "permit_empty|valid_email|is_unique[users.email,user_id,{$userId}]",
            'phone' => 'permit_empty|min_length[7]|max_length[7]|numeric',
            'gender' => 'required|in_list[Male,Female,Other]',
            'role_id' => 'required|integer',
            'province' => 'permit_empty|integer',
            'femis_id' => 'permit_empty|integer',
        ];
        
        if (!$this->validate($rules)) {
            $getDistrict = $this->districtModel->getDistrictByProvince($this->request->getPost('province'));
            $data['provinceDistrict'] = $getDistrict;
            $this->session->set($data);
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        try {
            // DISTRICT VALIDATION - FIXED
            $provinceId = $this->request->getPost('province');
            $districtId = $this->request->getPost('district'); // Not 'district_id_fk'!
            
            log_message('debug', 'Province: ' . $provinceId . ', District: ' . $districtId);
            
            // Overseas handling
            if ($provinceId == 16) {
                $districtId = 195;
            }
            
            // Validate district
            if (empty($districtId)) {
                $districtId = null;
            } else {
                $districtId = (int)$districtId;
                
                $exists = $this->db->table('district')->where('district_id', $districtId)->countAllResults();
                if ($exists == 0) {
                    return redirect()->back()->withInput()->with('error', 'Invalid district selected.');
                }
                
                if ($provinceId != 16) {
                    $inProvince = $this->db->table('district')
                        ->where('district_id', $districtId)
                        ->where('province_id_fk', $provinceId)
                        ->countAllResults();
                    
                    if ($inProvince == 0) {
                        return redirect()->back()->withInput()->with('error', 'District does not belong to selected province.');
                    }
                }
            }
            
            // PHOTO UPLOAD - FIXED
            $photoName = $existingUser['profile_photo'] ?? null;
            $photo = $this->request->getFile('photo'); // Form field name is 'photo'
            
            if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                
                if (!in_array($photo->getMimeType(), $validTypes)) {
                    return redirect()->back()->withInput()->with('error', 'Invalid file type.');
                }
                
                if ($photo->getSize() > 2097152) {
                    return redirect()->back()->withInput()->with('error', 'File too large. Max 2MB.');
                }
                
                if ($photoName && file_exists(FCPATH . 'uploads/profilePhoto/' . $photoName)) {
                    @unlink(FCPATH . 'uploads/profilePhoto/' . $photoName);
                }
                
                $photoName = $photo->getRandomName();
                $photo->move(FCPATH . 'uploads/profilePhoto', $photoName);
            }
            
            // DOB FORMATTING
            $dob = $this->request->getPost('dob');
            $dob = !empty($dob) ? date('Y-m-d', strtotime($dob)) : null;
            
            // PREPARE USER DATA - FIXED FIELD NAMES
            $userData = [
                'fname' => $this->request->getPost('fname'),
                'lname' => $this->request->getPost('lname'),
                'oname' => $this->request->getPost('oname'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone') ?: null,
                'gender' => $this->request->getPost('gender'),
                'dob' => $dob,
                'address' => $this->request->getPost('address') ?: null,
                'district_id_fk' => $districtId,
                'femis_id' => $this->request->getPost('femis_id') ?: null,
                'profile_photo' => $photoName,
                'user_status' => $this->request->getPost('user_status') ?: 'Active',
                'is_a_parent' => $this->request->getPost('is_a_parent') ? 1 : 0
            ];
            
            if ($this->request->getPost('password')) {
                $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }
            
            // UPDATE USER
            if ($this->userModel->update($userId, $userData) !== false) {
                // Update role
                $this->userRoleModel->where('user_id_fk', $userId)->delete();
                $newRoleIdSaved = $this->request->getPost('role_id');
                $this->userRoleModel->insert([
                    'user_id_fk'       => $userId,
                    'role_id_fk'       => $newRoleIdSaved,
                    'user_role_status' => 'Active',
                    'updated_date'     => date('Y-m-d H:i:s'),
                ]);

                // Update admission if new role is Student (4) or Teacher (3)
                $newRoleData  = $this->db->table('role')->where('role_id', $newRoleIdSaved)->get()->getRowArray();
                $newRoleCatId = (int)($newRoleData['role_cat_id_fk'] ?? 0);
                $admSchId     = $this->request->getPost('admission_sch_id');
                $admStatus    = $this->request->getPost('admission_status');
                $admNote      = $this->request->getPost('admission_note');

                if (in_array($newRoleCatId, [3, 4]) && !empty($admSchId)) {
                    $existing = $this->db->table('admission')
                        ->where('user_id_fk', $userId)
                        ->orderBy('admission_id', 'DESC')
                        ->limit(1)
                        ->get()->getRowArray();

                    $admData = [
                        'sch_id_fk'        => (int)$admSchId,
                        'admission_status' => $admStatus ?: 'Active',
                        'admission_note'   => $admNote ?: null,
                    ];

                    if ($existing) {
                        $this->db->table('admission')
                            ->where('admission_id', $existing['admission_id'])
                            ->update($admData);
                    } else {
                        $admData['user_id_fk']      = $userId;
                        $admData['admission_date']   = date('Y-m-d');
                        $admData['admission_time']   = time();
                        $this->db->table('admission')->insert($admData);
                    }
                }
                
                // Log activity
                $this->userLogModel->insert([
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Edit User',
                    'log_desc' => 'User "' . $userData['fname'] . ' ' . $userData['lname'] . '" updated',
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-check-circle"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'info'
                ]);
                
                return redirect()->to('user')->with('success', 'User updated successfully!');
            }
            
            return redirect()->back()->withInput()->with('error', 'Failed to update user.');
            
        } catch (\Exception $e) {
            log_message('error', 'Update error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Display edit user form
     */
    public function medical($userId)
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'user/medical/' . $userId);
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('View Medical Detail', 'User', 'User Listing');
        
        // Check permission
        $accessCheck = $this->require_access('_edit_user');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }
        
        // Get current user's role rank
        $currentUserRoleId = $this->session->get('roleID');
        $currentUserRole = $this->roleModel->find($currentUserRoleId);
        $currentUserRank = is_array($currentUserRole) ? ($currentUserRole['role_rank'] ?? 999) : ($currentUserRole->role_rank ?? 999);
        
        // Get target user data with role and rank
        $builder = $this->db->table('users');
        $builder->select('
            users.*,
            user_role.role_id_fk,
            role.role_name,
            role.role_rank
        ');
        $builder->join('user_role', 'user_role.user_id_fk = users.user_id', 'left');
        $builder->join('role', 'role.role_id = user_role.role_id_fk', 'left');
        $builder->where('users.user_id', $userId);
        
        $user = $builder->get()->getRowArray();
        
        if (!$user) {
            return redirect()->to('user')->with('error', 'User not found.');
        }
        
        // Check role rank - prevent editing users with higher authority
        $targetUserRank = $user['role_rank'] ?? 999;
        
        if ($currentUserRank > $targetUserRank) {
            $targetUserName = $user['fname'] . ' ' . $user['lname'];
            $targetUserRole = $user['role_name'] ?? 'Unknown Role';
            
            return redirect()->to('user')
                ->with('error', "Access denied. You cannot view medical record {$targetUserName} as they hold a higher authority role ({$targetUserRole} - Rank {$targetUserRank}) than your current role (Rank {$currentUserRank}). Please contact a system administrator if you need to make changes to this user.");
        }
        
        // Get all roles for dropdown (only roles with equal or lower authority)
        $allRoles = $this->roleModel->getAllRoles();
        $availableRoles = [];
        
        foreach ($allRoles as $role) {
            $roleRank = is_array($role) ? ($role['role_rank'] ?? 999) : ($role->role_rank ?? 999);
            
            // Only show roles with equal or lower authority (higher or equal rank number)
            if ($currentUserRank <= $roleRank) {
                $availableRoles[] = $role;
            }
        }
        
        // Get all districts for dropdown
        $data['province'] = $this->provinceModel->getAllProvince();
        $district_id = $user['district_id_fk'];
        $data['district_id'] = $district_id;
        $district = $this->districtModel->getDistrictFull($district_id);
        $data['district'] = $district;
        $province_id = $district['province_id'];
        $data['province_id'] = $province_id;
        $getDistrict = $this->districtModel->getDistrictByProvince($province_id);
        $data['provinceDistrict'] = $getDistrict;
        $data['user'] = $user;
        $data['roles'] = $availableRoles;
        $data['_view'] = 'app/user/medical';
        
        return view('app/layouts/main', $data);
    }
    
    /**
     * Delete user (Solution 2: With Transaction Support - Production Ready)
     * 
     * Features:
     * - Database transaction for data integrity
     * - Automatic rollback on failure
     * - Role rank security checks
     * - Self-deletion prevention
     * - Comprehensive logging
     * - Photo file cleanup
     * - Foreign key constraint handling
     */
    public function delete($userId)
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check permission
        $accessCheck = $this->require_access('_remove_user');
        if ($accessCheck !== true) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have permission to delete users'
            ])->setStatusCode(403);
        }
        
        // Start database transaction
        $this->db->transStart();
        
        try {
            // Get current user's role rank
            $currentUserRoleId = $this->session->get('roleID');
            $currentUserRole = $this->roleModel->find($currentUserRoleId);
            $currentUserRank = is_array($currentUserRole) 
                ? ($currentUserRole['role_rank'] ?? 999) 
                : ($currentUserRole->role_rank ?? 999);
            
            // Get target user with role information
            $builder = $this->db->table('users');
            $builder->select('users.*, role.role_rank, role.role_name');
            $builder->join('user_role', 'user_role.user_id_fk = users.user_id', 'left');
            $builder->join('role', 'role.role_id = user_role.role_id_fk', 'left');
            $builder->where('users.user_id', $userId);
            $user = $builder->get()->getRowArray();
            
            if (!$user) {
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ])->setStatusCode(404);
            }
            
            $userName = $user['fname'] . ' ' . $user['lname'];
            $targetUserRank = $user['role_rank'] ?? 999;
            $targetUserRole = $user['role_name'] ?? 'Unknown Role';
            
            // Prevent self-deletion
            if ($userId == $this->session->get('userID')) {
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ])->setStatusCode(403);
            }
            
            // Check role rank - prevent deleting users with higher authority
            if ($currentUserRank > $targetUserRank) {
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Access denied. You cannot delete {$userName} as they hold a higher authority role ({$targetUserRole} - Rank {$targetUserRank}) than your current role (Rank {$currentUserRank}). Please contact a system administrator for assistance."
                ])->setStatusCode(403);
            }
            
            // Step 1: Delete user's role assignments (to avoid foreign key constraint)
            log_message('debug', "Deleting role assignments for user ID: {$userId}");
            $roleDeleted = $this->userRoleModel->where('user_id_fk', $userId)->delete();
            log_message('debug', "Role assignments deleted: " . ($roleDeleted ? 'Yes' : 'No'));
            
            // Step 2: Delete user's photo if exists
            if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/profilePhoto/' . $user['profile_photo'])) {
                $photoDeleted = @unlink(FCPATH . 'uploads/profilePhoto/' . $user['profile_photo']);
                log_message('debug', "Photo deleted: " . ($photoDeleted ? 'Yes' : 'No') . " - " . $user['profile_photo']);
            }
            
            // Step 3: Delete user's password (to avoid foreign key constraint)
            log_message('debug', "Deleting role assignments for user ID: {$userId}");
            $passwordDeleted = $this->userPasswordModel->where('user_id_fk', $userId)->delete();
            log_message('debug', "All user password deleted: " . ($passwordDeleted ? 'Yes' : 'No'));
            
            // Step 4: Delete user from database
            log_message('debug', "Deleting user ID: {$userId}");
            $userDeleted = $this->userModel->delete($userId);
            log_message('debug', "User deleted: " . ($userDeleted ? 'Yes' : 'No'));
            
            if ($userDeleted) {
                // Complete transaction
                $this->db->transComplete();
                
                // Check if transaction was successful
                if ($this->db->transStatus() === false) {
                    log_message('error', "Transaction failed when deleting user ID: {$userId}");
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to delete user. Transaction was rolled back.'
                    ])->setStatusCode(500);
                }
                
                // Log activity
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Delete User',
                    'log_desc' => "User '{$userName}' (Role: {$targetUserRole}) has been deleted successfully",
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'danger'
                ];
                
                $this->userLogModel->insert($userLogData);
                
                log_message('info', "User '{$userName}' (ID: {$userId}) deleted by user ID: " . $this->session->get('userID'));
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "User '{$userName}' has been deleted successfully"
                ]);
            } else {
                $this->db->transRollback();
                log_message('error', "Failed to delete user ID: {$userId}");
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete user from database'
                ])->setStatusCode(500);
            }
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Exception when deleting user ID ' . $userId . ': ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the user: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Update user email
     */
    public function updateEmail()
    {
        $this->session->set('activeTab', 'security');
    
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
    
        $rules = [
            'user_id'   => 'required|integer',
            'new_email' => [
                'rules'  => 'required|valid_email|is_unique[users.email,user_id,{user_id}]|is_unique[users.pending_email_update,user_id,{user_id}]',
                'errors' => [
                    'required'    => 'Email is required',
                    'valid_email' => 'Please provide a valid email address',
                    'is_unique'   => 'This email is already in use or has a pending verification request by another account. Please use a different email address.'
                ]
            ]
        ];
    
        $messages = [
            'new_email' => [
                'required'    => 'Email is required',
                'valid_email' => 'Please provide a valid email address',
                'is_unique'   => 'This email is already in use'
            ]
        ];
    
        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $this->validator->getErrors())
            ]);
        }
    
        try {
            $userId   = $this->request->getPost('user_id');
            $newEmail = $this->request->getPost('new_email');
    
            $user = $this->userModel->find($userId);
            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
            
            // ── Check if new email is same as current email ──────────────────────────
            if (strtolower(trim($newEmail)) === strtolower(trim($user['email']))) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'The new email address is the same as your current email. Please enter a different email address.'
                ]);
            }
            // ────────────────────────────────────────────────────────────────────────
    
            // ── Check if a pending request already exists with a valid token ──
            if (!empty($user['pending_email_update']) && !empty($user['security_token_expiry'])) {
                $now    = new \DateTime();
                $expiry = new \DateTime($user['security_token_expiry']);
    
                if ($now < $expiry) {
                    // Calculate remaining time
                    $diff    = $now->diff($expiry);
                    $hours   = $diff->h;
                    $minutes = $diff->i;
    
                    $timeLeft = '';
                    if ($hours > 0) {
                        $timeLeft .= $hours . ' hour' . ($hours > 1 ? 's' : '');
                    }
                    if ($minutes > 0) {
                        $timeLeft .= ($hours > 0 ? ' and ' : '') . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                    }
                    if (empty($timeLeft)) {
                        $timeLeft = 'less than a minute';
                    }
    
                    return $this->response->setJSON([
                        'success' => false,
                        'warning' => true,
                        'message' => 'A verification request to change your email to <strong>' . esc($user['pending_email_update']) . '</strong> is already pending. '
                                   . 'Please check your inbox and click the verification link. '
                                   . 'The link expires in <strong>' . $timeLeft . '</strong>. '
                                   . 'If you did not receive the email, please wait for the current link to expire before requesting a new one.'
                    ]);
                }
    
                // Token is expired — clear the old pending request and allow a fresh one
                $this->userModel->update($userId, [
                    'pending_email_update'  => null,
                    'security_token'        => null,
                    'security_token_expiry' => null,
                ]);
            }
            // ────────────────────────────────────────────────────────────────────
    
            // Generate secure token and set 24-hour expiry
            $token       = bin2hex(random_bytes(32));
            $tokenExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
            $updated = $this->userModel->update($userId, [
                'pending_email_update'  => $newEmail,
                'security_token'        => $token,
                'security_token_expiry' => $tokenExpiry,
                'updated_date'          => date('Y-m-d'),
                'updated_time'          => time(),
            ]);
    
            if ($updated) {
                $this->sendEmail([
                    'to'       => $newEmail,
                    'subject'  => 'Verify New Email - Navuli Fiji',
                    'view'     => 'email/verify_email_change',
                    'viewData' => [
                        'name'       => $user['fname'] . ' ' . $user['lname'],
                        'old_email'  => $user['email'],
                        'new_email'  => $newEmail,
                        'verify_url' => base_url('user/verifyemail/' . $token),
                        'token'      => $token,
                        'expiry'     => $tokenExpiry,
                    ],
                ]);
    
                $this->userLogModel->insert([
                    'user_id_fk'  => $this->session->get('userID'),
                    'ip_aadress'  => $this->ipAddress,
                    'user_agent'  => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title'   => 'Email Change Requested',
                    'log_desc'    => 'Email change requested from ' . $user['email'] . ' to ' . $newEmail . ' for user ' . $user['fname'] . ' ' . $user['lname'],
                    'log_date'    => date('Y-m-d'),
                    'log_time'    => time(),
                    'log_icon'    => '<i class="ki-duotone ki-directbox-default"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>',
                    'log_theme'   => 'info',
                    'log_type'    => 'Alert',
                ]);
    
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'A verification link has been sent to <strong>' . $newEmail . '</strong>. Please check your inbox and click the link to confirm your new email address. The link expires in 24 hours.'
                ]);
            }
    
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to initiate email update'
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[UserController::updateEmail] ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Verify email change token and apply the new email
     */
    public function verifyEmail($token = null)
    {
        if (empty($token)) {
            $data['_view']   = 'app/user/verify_email';
            $data['status']  = 'error';
            $data['message'] = 'Invalid or missing verification token.';
            $this->setPageData('Email Verification', 'User', 'Email Verification');
            return view('app/layouts/main', $data);
        }
    
        try {
            $user = $this->userModel
                ->where('security_token', $token)
                ->where('pending_email_update IS NOT NULL', null, false)
                ->first();
    
            if (!$user) {
                $data['_view']   = 'app/user/verify_email';
                $data['status']  = 'error';
                $data['message'] = 'Invalid verification link. It may have already been used or does not exist.';
                $this->setPageData('Email Verification', 'User', 'Email Verification');
                return view('app/layouts/main', $data);
            }
    
            // Check token expiry
            $now    = new \DateTime();
            $expiry = new \DateTime($user['security_token_expiry']);
    
            if ($now > $expiry) {
                $this->userModel->update($user['user_id'], [
                    'security_token'        => null,
                    'security_token_expiry' => null,
                ]);
    
                $data['_view']   = 'app/user/verify_email';
                $data['status']  = 'expired';
                $data['message'] = 'Your verification link has expired.';
                $data['name']    = $user['fname'] . ' ' . $user['lname'];
                $this->setPageData('Email Verification', 'User', 'Email Verification');
                return view('app/layouts/main', $data);
            }
    
            $oldEmail = $user['email'];
            $newEmail = $user['pending_email_update'];
    
            // Apply the email change and clear token fields
            $this->userModel->update($user['user_id'], [
                'email'                 => $newEmail,
                'pending_email_update'  => null,
                'security_token'        => null,
                'security_token_expiry' => null,
                'email_verified'        => 1,
                'updated_date'          => date('Y-m-d'),
                'updated_time'          => time(),
            ]);
    
            // Send confirmation email to NEW email
            $this->sendEmail([
                'to'       => $newEmail,
                'subject'  => 'Email Address Successfully Changed - Navuli Fiji',
                'view'     => 'email/email_change_success',
                'viewData' => [
                    'name'      => $user['fname'] . ' ' . $user['lname'],
                    'old_email' => $oldEmail,
                    'new_email' => $newEmail,
                    'date'      => date('d M Y, h:i A'),
                ],
            ]);
    
            // Notify OLD email
            $this->sendEmail([
                'to'       => $oldEmail,
                'subject'  => 'Email Address Changed - Security Notice',
                'view'     => 'email/email_change_old_notice',
                'viewData' => [
                    'name'        => $user['fname'] . ' ' . $user['lname'],
                    'old_email'   => $oldEmail,
                    'new_email'   => $newEmail,
                    'date'        => date('d M Y, h:i A'),
                    'support_url' => base_url('contact'),
                ],
            ]);
    
            // Log activity
            $this->userLogModel->insert([
                'user_id_fk'  => $user['user_id'],
                'ip_aadress'  => $this->request->getIPAddress(),
                'user_agent'  => $this->request->getUserAgent()->getAgentString(),
                'user_device' => 'Unknown',
                'log_title'   => 'Email Changed',
                'log_desc'    => 'Email successfully changed from ' . $oldEmail . ' to ' . $newEmail,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-check-circle"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'success',
                'log_type'    => 'Alert',
            ]);
    
            // Update session if user is currently logged in
            if ($this->session->get('userID') == $user['user_id']) {
                $this->session->set('email', $newEmail);
            }
    
            $data['_view']     = 'app/user/verify_email';
            $data['status']    = 'success';
            $data['message']   = 'Your email address has been successfully changed.';
            $data['name']      = $user['fname'] . ' ' . $user['lname'];
            $data['old_email'] = $oldEmail;
            $data['new_email'] = $newEmail;
            $data['date']      = date('d M Y, h:i A');
            $this->setPageData('Email Verification', 'User', 'Email Verification');
            return view('app/layouts/main', $data);
    
        } catch (\Exception $e) {
            log_message('error', '[UserController::verifyEmail] ' . $e->getMessage());
    
            $data['_view']   = 'app/user/verify_email';
            $data['status']  = 'error';
            $data['message'] = 'An error occurred while verifying your email. Please try again.';
            $this->setPageData('Email Verification', 'User', 'Email Verification');
            return view('app/layouts/main', $data);
        }
    }
    
    /**
     * Update user password
     */
    public function updatePassword()
    {
        $this->session->set('activeTab', 'security');
        
        // Check authentication
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
        
        // Validation rules
        $rules = [
            'user_id' => 'required|integer',
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_new_password' => 'required|matches[new_password]'
        ];
        
        $messages = [
            'current_password' => [
                'required' => 'Current password is required'
            ],
            'new_password' => [
                'required' => 'New password is required',
                'min_length' => 'Password must be at least 8 characters'
            ],
            'confirm_new_password' => [
                'required' => 'Please confirm the new password',
                'matches' => 'Passwords do not match'
            ]
        ];
        
        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $this->validator->getErrors())
            ]);
        }
        
        try {
            $userId = $this->request->getPost('user_id');
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');

            // Get user
            $user = $this->userModel->find($userId);
            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            // Verify current password against stored hash
            if (!password_verify($currentPassword, $user['password'])) {
                log_message('warning', "Failed password verification for user {$userId}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ]);
            }
            
            log_message('debug', "Current password verified successfully for user {$userId}");
            
            // ✅ FIX: Check if new password is same as current password
            if (password_verify($newPassword, $user['password'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'New password cannot be the same as current password'
                ]);
            }
            
            // ✅ FIX: Check if password was previously used
            $passwordModel = new \App\Models\UserPasswordModel();
            
            // Get all previous passwords for this user
            $previousPasswords = $passwordModel->where('user_id_fk', $userId)
                                               ->findAll();
            
            // Check if new password matches any previous password
            foreach ($previousPasswords as $prevPassword) {
                if (password_verify($newPassword, $prevPassword['password'])) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'This password has been used before. Please choose a different password.'
                    ]);
                }
            }
            
            log_message('debug', "New password is unique for user {$userId}");
            
            // Hash new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update user password
            $update = $this->userModel->update($userId, [
                'password' => $hashedPassword
            ]);
            
            if ($update) {
                // Insert new password record into password history
                $passwordModel->insert([
                    'user_id_fk' => $userId,
                    'password' => $hashedPassword,
                    'date_created' => date('Y-m-d'),
                    'time_created' => time(),
                    'password_status' => 'Active'
                ]);
                
                log_message('info', "Password updated successfully for user {$userId}");
                
                // Log activity
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Update Password',
                    'log_desc' => "Successfully updated password for user " . $user['fname'] . ' ' . $user['lname'],
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-security-user"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'info',
                    'log_type'  => 'Alert',
                ];
                
                $this->userLogModel->insert($userLogData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Password updated successfully!'
                ]);
            } else {
                log_message('error', "Failed to update password for user {$userId}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update password'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[UserController::updatePassword] ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update user role
     */
    public function updateRole()
    {
        $this->session->set('activeTab', 'security');
        
        // Check authentication
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
        
        // Check permission
        if (!$this->grant_access('_update_user_role')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have permission to update user roles'
            ]);
        }
        
        // Check role rank
        if ($this->session->get('role_rank') > $this->request->getPost('role_rank')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have permission to update user roles of a user that has a higher role rank'
            ]);
        }
        
        // Validation rules
        $rules = [
            'user_id' => 'required|integer',
            'role_id' => 'required|integer'
        ];
        
        $messages = [
            'role_id' => [
                'required' => 'Please select a role'
            ]
        ];
        
        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $this->validator->getErrors())
            ]);
        }
        
        try {
            $userId = $this->request->getPost('user_id');
            $newRoleId = $this->request->getPost('role_id');
            
            log_message('debug', "Updating role for userId: {$userId}, new roleId: {$newRoleId}");
            
            // Get user
            $user = $this->userModel->find($userId);
            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
            
            // Verify new role exists
            $role = $this->roleModel->find($newRoleId);
            if (!$role) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid role selected'
                ]);
            }
            
            // Get current active role for this user
            $currentActiveRole = $this->userRoleModel
                ->where('user_id_fk', $userId)
                ->where('user_role_status', 'Active')
                ->first();
            
            log_message('debug', "Current active role: " . json_encode($currentActiveRole));
            
            // Check if user is already on the selected role
            if ($currentActiveRole && $currentActiveRole['role_id_fk'] == $newRoleId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User is already assigned to this role'
                ]);
            }
            
            // Start transaction for data integrity
            $this->db->transStart();
            
            // Step 1: Deactivate ALL current active roles for this user
            $this->db->table('user_role')
                ->where('user_id_fk', $userId)
                ->where('user_role_status', 'Active')
                ->update([
                    'user_role_status' => 'Non Active',
                    'updated_date' => date('Y-m-d H:i:s')
                ]);
            
            log_message('debug', "Deactivated all active roles for user {$userId}");
            
            // Step 2: Check if user has had this role before (to reactivate instead of inserting duplicate)
            $existingRole = $this->userRoleModel
                ->where('user_id_fk', $userId)
                ->where('role_id_fk', $newRoleId)
                ->first();
            
            if ($existingRole) {
                // Reactivate existing role
                $this->userRoleModel->update($existingRole['user_role_id'], [
                    'user_role_status' => 'Active',
                    'updated_date' => date('Y-m-d H:i:s')
                ]);
                
                log_message('debug', "Reactivated existing role {$existingRole['user_role_id']}");
            } else {
                // Insert new role assignment
                /*$newUserRoleId = $this->userRoleModel->insert([
                    'user_id_fk' => $userId,
                    'role_id_fk' => $newRoleId,
                    'user_role_status' => 'Active',
                    'created_date' => date('Y-m-d H:i:s')
                ]);*/
                
                $inserdata = array(
                    'user_id_fk' => $userId,
                    'role_id_fk' => $newRoleId,
                    'user_role_status' => 'Active',
                    'created_date' => date('Y-m-d H:i:s')
                );
                
                $this->userRoleModel->add_user_role($inserdata);
                
                log_message('debug', "Inserted new role assignment with ID: {$newUserRoleId}");
                
                if (!$newUserRoleId) {
                    $this->db->transRollback();
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to assign new role'
                    ]);
                }
            }
            
            // Complete transaction
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                log_message('error', "Transaction failed when updating role for user {$userId}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user role. Transaction was rolled back.'
                ]);
            }
            
            // Get role name for logging
            $roleName = is_array($role) ? $role['role_name'] : $role->role_name;
            
            // Log activity
            $userLogData = [
                'user_id_fk' => $this->session->get('userID'),
                'ip_aadress' => $this->ipAddress,
                'user_agent' => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title' => 'Update User Role',
                'log_desc' => "Successfully updated user role to '{$roleName}' for user " . $user['fname'] . ' ' . $user['lname'],
                'log_date' => date('Y-m-d'),
                'log_time' => time(),
                'log_icon' => '<i class="ki-duotone ki-key-square"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme' => 'info',
                'log_type'  => 'Alert',
            ];
            
            $this->userLogModel->insert($userLogData);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "User role updated successfully to '{$roleName}'!"
            ]);
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', '[UserController::updateRole] ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    // ================================================================
    // PARENT–STUDENT LINKING
    // ================================================================

    public function linkChild()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $parentId   = (int) $this->request->getPost('parent_user_id');
            $studentId  = (int) $this->request->getPost('student_user_id');
            $relationship = trim($this->request->getPost('relationship') ?? 'Parent');

            if (!$parentId || !$studentId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid data.']);
            }

            if ($parentId === $studentId) {
                return $this->response->setJSON(['success' => false, 'message' => 'A user cannot be linked to themselves.']);
            }

            $exists = $this->parentStudentModel
                ->where('parent_user_id_fk', $parentId)
                ->where('student_user_id_fk', $studentId)
                ->countAllResults();

            if ($exists) {
                return $this->response->setJSON(['success' => false, 'message' => 'This child is already linked.']);
            }

            $this->parentStudentModel->insert([
                'parent_user_id_fk'  => $parentId,
                'student_user_id_fk' => $studentId,
                'relationship'       => $relationship,
                'created_by'         => (int) $this->session->get('userID'),
                'created_at'         => date('Y-m-d H:i:s'),
            ]);

            $student = $this->userModel->find($studentId);
            $name    = trim(($student['fname'] ?? '') . ' ' . ($student['lname'] ?? ''));

            $this->userLogModel->insert([
                'user_id_fk'  => (int) $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Child Linked',
                'log_desc'    => '"' . $name . '" linked as child of user ID ' . $parentId,
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-people"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'primary',
            ]);

            return $this->response->setJSON(['success' => true, 'message' => $name . ' linked successfully.']);

        } catch (\Exception $e) {
            log_message('error', '[UserController::linkChild] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function unlinkChild(int $linkId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $link = $this->parentStudentModel->find($linkId);
            if (!$link) {
                return $this->response->setJSON(['success' => false, 'message' => 'Link not found.']);
            }

            $this->parentStudentModel->delete($linkId);

            return $this->response->setJSON(['success' => true, 'message' => 'Child unlinked successfully.']);

        } catch (\Exception $e) {
            log_message('error', '[UserController::unlinkChild] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function searchStudents()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'results' => []]);
        }

        $q = trim($this->request->getGet('q') ?? '');
        if (strlen($q) < 2) {
            return $this->response->setJSON(['success' => true, 'results' => []]);
        }

        $db = \Config\Database::connect();
        $results = $db->query("
            SELECT u.user_id, u.fname, u.lname, u.profile_photo,
                   r.role_name, rc.role_cat_name
            FROM users u
            INNER JOIN user_role ur  ON ur.user_id_fk  = u.user_id AND ur.user_role_status = 'Active'
            INNER JOIN role r        ON r.role_id       = ur.role_id_fk
            INNER JOIN role_category rc ON rc.role_cat_id = r.role_cat_id_fk
            WHERE rc.role_cat_id = 4
              AND u.user_status = 'Active'
              AND (u.fname LIKE ? OR u.lname LIKE ? OR CONCAT(u.fname,' ',u.lname) LIKE ?)
            ORDER BY u.fname, u.lname
            LIMIT 15
        ", ["%$q%", "%$q%", "%$q%"])->getResultArray();

        $output = [];
        foreach ($results as $u) {
            $output[] = [
                'id'    => $u['user_id'],
                'text'  => $u['fname'] . ' ' . $u['lname'],
                'photo' => $u['profile_photo'],
                'role'  => $u['role_name'],
            ];
        }

        return $this->response->setJSON(['success' => true, 'results' => $output]);
    }

    // ─── Notifications page ────────────────────────────────────────────────────

    /**
     * GET user/notification — full notification log DataTable page for the current user.
     */
    public function notification()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $myId        = (int) $this->session->get('userID');
        $defaultType = in_array($this->request->getGet('type'), ['Activity', 'Alert'])
            ? $this->request->getGet('type')
            : '';

        $data = [
            'pageTitle'    => 'My Notifications',
            'myId'         => $myId,
            'defaultType'  => $defaultType,
            '_view'        => 'app/user/notifications',
        ];

        return view('app/layouts/main', $data);
    }

    /**
     * GET user/getNotifications — AJAX: unread count + recent Activity + recent Alert rows.
     */
    public function getNotifications()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false]);
        }

        $myId         = (int) $this->session->get('userID');
        $unreadCount  = $this->userLogModel->getUnreadCount($myId);
        $activities   = $this->userLogModel->getRecentByType($myId, 'Activity', 6);
        $alerts       = $this->userLogModel->getRecentByType($myId, 'Alert', 6);

        $format = function (array $logs): array {
            $out = [];
            foreach ($logs as $log) {
                $out[] = [
                    'title'   => $log['log_title']  ?? '',
                    'desc'    => $log['log_desc']   ?? '',
                    'icon'    => $log['log_icon']   ?? '',
                    'theme'   => $log['log_theme']  ?? 'primary',
                    'status'  => $log['log_status'] ?? 'Read',
                    'date'    => date('d M Y', strtotime($log['log_date'])),
                    'time'    => date('h:i A', (int)$log['log_time']),
                    'age'     => $this->_timeAgo((int)$log['log_time']),
                ];
            }
            return $out;
        };

        return $this->response->setJSON([
            'success'      => true,
            'unread_count' => $unreadCount,
            'activities'   => $format($activities),
            'alerts'       => $format($alerts),
        ]);
    }

    /**
     * POST user/markNotificationsRead — marks all unread log entries as Read.
     */
    public function markNotificationsRead()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false]);
        }

        $this->userLogModel->markAllRead((int) $this->session->get('userID'));

        return $this->response->setJSON(['success' => true]);
    }

    /** Human-readable time-ago string from a Unix timestamp. */
    private function _timeAgo(int $ts): string
    {
        $diff = time() - $ts;
        if ($diff < 60)      return 'Just now';
        if ($diff < 3600)    return floor($diff / 60)   . ' min ago';
        if ($diff < 86400)   return floor($diff / 3600) . ' hr ago';
        if ($diff < 604800)  return floor($diff / 86400)  . ' day' . (floor($diff / 86400)  > 1 ? 's' : '') . ' ago';
        return date('d M Y', $ts);
    }


}
