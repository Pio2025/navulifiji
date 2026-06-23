<?php

namespace App\Controllers;

class RoleController extends BaseController
{
    protected $validation;
    protected $session;
    protected $email;
    protected $helpers = ['form', 'url'];
    
    public function __construct()
    {
        helper('form,url');
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();
    }
    
    /**
     * Display role listing page
     */
    public function index()
    {
        $view = '';
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'role');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('View Role Listing', 'RBAC', 'Role Listing');
        
        // Check permission
        $accessCheck = $this->require_access('_role_listing');
        if ($accessCheck !== true) {
            $view = 'app/auth/access_control';
        } else {
            $view = 'app/role/index';
        }
        
        $data = $this->loadCommonData($view);
        
        //Add user log
        $userLogData = [
            'user_id_fk' => $this->session->get('userID'),
            'ip_aadress' => $this->ipAddress,
            'user_agent' => $this->userAgent->getAgentString(),
            'user_device' => $this->deviceInfo['device_type'],
            'log_title' => 'View Role Listing',
            'log_desc' => 'User view role listing.',
            'log_date' => date('Y-m-d'),
            'log_time' => time(),
            'log_icon' => '<i class="ki-duotone ki-eye"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
            'log_theme' => 'warning'
        ];
        
        $addUserLog = $this->userLogModel->addUserLog($userLogData);
        
        return view('app/layouts/main', $data);
    }
    
    /**
     * Get role listing data for DataTables (AJAX)
     */
    public function getRoleListing()
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
            $columns = ['role_name', 'role_desc', null]; // null for actions column
            $orderColumn = $columns[$orderColumnIndex] ?? 'role_name';
            
            // Prevent sorting on actions column
            if ($orderColumn === null || $orderColumnIndex === 2) {
                $orderColumn = 'role_name';
            }
            
            // Validate sort direction
            $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
            
            log_message('debug', "Order by: {$orderColumn} {$orderDir}");
            
            // Prepare data for model
            $postData = [
                'start' => $start,
                'length' => $length,
                'search' => [
                    'value' => $searchValue
                ],
                'order' => [
                    'column' => $orderColumn,
                    'dir' => $orderDir
                ]
            ];
            
            // Get data from model
            $roles = $this->roleModel->getRows($postData);
            $recordsTotal = $this->roleModel->countAll();
            $recordsFiltered = $this->roleModel->countFiltered($postData);
            
            log_message('debug', "Found {$recordsFiltered} filtered roles out of {$recordsTotal} total");
            
            // Format data for DataTables
            $data = [];
            foreach ($roles as $role) {
                $data[] = [
                    $role->role_name ?? '',
                    $role->role_desc ?? null,
                    $role->role_rank ?? '#',
                    $this->createActionButtons($role->role_id)
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
            log_message('error', 'Role listing error: ' . $e->getMessage());
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
     * Create action buttons HTML for each role
     * 
     * @param int $roleId
     * @return string
     */
    private function createActionButtons($roleId)
    {
        return '
        <div class="d-flex justify-content-end flex-shrink-0">
            <button class="btn btn-icon btn-bg-light btn-light-primary  btn-sm me-1" data-kt-menu-trigger="click" title="Quick Action" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-down fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </button>
            
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                <!--div class="menu-item px-3">
                    <a href="' . base_url('role/detail/' . $roleId) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-eye fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        View Details
                    </a>
                </div-->
                
                <div class="menu-item px-3">
                    <a href="' . base_url('role/edit/' . $roleId) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-pencil fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Edit Role
                    </a>
                </div>
                
                <div class="menu-item px-3">
                    <a href="' . base_url('role/permission/' . $roleId) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-shield-tick fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Permissions
                    </a>
                </div>
                
                <div class="separator my-2"></div>
                
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3 text-danger" data-kt-roles-table-filter="delete_row" data-role-id="' . $roleId . '">
                        <i class="ki-duotone ki-trash fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Delete Role
                    </a>
                </div>
            </div>
        </div>';
    }
    
    /**
     * Add new role page
     */
    public function add(){
        
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $view = '';
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'role/add');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('Add New Role', 'RBAC', 'Add Role');
        
        // Check permission
        $accessCheck = $this->require_access('_add_role');
        if ($accessCheck !== true) {
            $view = 'app/auth/access_control';
        } else {
            $view = 'app/role/add';
        }
        
        $data['_view'] = $view;
        
        return view('app/layouts/main', $data);
    }
    
    public function store()
    {
        $this->session->set('prevUrl',$this->session->get('url'));
        $this->session->set('url','role/add');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Check permission
        $accessCheck = $this->require_access('_add_role');
        if ($accessCheck !== true) {
            return redirect()->to('role')->with('error', 'You do not have permission to add roles.');
        }
        
        // Validation rules
        $rules = [
            'role_name' => [
                'rules' => 'required|min_length[3]|max_length[100]|is_unique[role.role_name]',
                'errors' => [
                    'required' => 'Role name is required',
                    'min_length' => 'Role name must be at least 3 characters',
                    'max_length' => 'Role name cannot exceed 100 characters',
                    'is_unique' => 'This role name already exists'
                ]
            ],
            'role_rank' => [
                'rules' => 'required|integer|greater_than[0]|less_than[1000]',
                'errors' => [
                    'required' => 'Role rank is required',
                    'integer' => 'Role rank must be a valid number',
                    'greater_than' => 'Role rank must be greater than 0',
                    'less_than' => 'Role rank must be less than 1000'
                ]
            ],
            'role_desc' => [
                'rules' => 'required|min_length[3]|max_length[1000]',
                'errors' => [
                    'required' => 'Role description is required',
                    'min_length' => 'Role name must be at least 3 characters',
                    'max_length' => 'Role description cannot exceed 1000 characters'
                ]
            ]
        ];
        
        // Validate input
        if (!$this->validate($rules)) {
            // Store validation errors in session
            return redirect()->back()
                            ->withInput()
                            ->with('validation', $this->validator);
        }
        
        try {
            // Prepare data
            $data = [
                'role_name' => $this->request->getPost('role_name'),
                'role_rank' => $this->request->getPost('role_rank') ?: null,
                'role_desc' => $this->request->getPost('role_desc') ?: null,
                'created_at' => date('Y-m-d')
            ];
            
            // Insert role
            $roleId = $this->roleModel->add_role($data);
            
            if ($roleId) {
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Add New Role',
                    'log_desc' => 'Role ' . $data['role_name'] . ' has been created successfully!',
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-shield-tick"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'success'
                ];
                
                $addUserLog = $this->userLogModel->addUserLog($userLogData);
                
                return redirect()->to('role')
                                ->with('success', 'Role "' . $data['role_name'] . '" has been created successfully!');
            } else {
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Failed to create role. Please try again.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error creating role: ' . $e->getMessage());
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while creating the role: ' . $e->getMessage());
        }
    }
    
    /**
     * Display edit role form
     */
    public function edit($roleId)
    {
        try {
            $this->session->set('prevUrl', $this->session->get('url'));
            $this->session->set('url', 'role/edit/'.$roleId);
            
            // Check if user is logged in
            if (!$this->isLoggedIn()) {
                log_message('info', 'Edit role attempt without login - Role ID: ' . $roleId);
                return redirect()->to('auth/login')->with('error', 'Please login to continue.');
            }
            
            // Set page metadata
            $this->setPageData('Edit Role', 'RBAC', 'Edit Role');
            
            // Check permission
            $accessCheck = $this->require_access('_edit_role');
            if ($accessCheck !== true) {
                log_message('warning', 'User lacks permission to edit role - User ID: ' . $this->session->get('userID') . ', Role ID: ' . $roleId);
                $data['_view'] = 'app/auth/access_control';
                return view('app/layouts/main', $data);
            }
            
            // Get role data
            log_message('debug', 'Fetching role data for ID: ' . $roleId);
            $role = $this->roleModel->get_role($roleId);
            
            if (!$role) {
                log_message('error', 'Role not found - ID: ' . $roleId);
                return redirect()->to('role')->with('error', 'The role that you are trying to edit not found.');
            }
            
            log_message('debug', 'Role data retrieved: ' . print_r($role, true));
            
            $data = [
                'role' => $role,
                '_view' => 'app/role/edit'
            ];
            
            return view('app/layouts/main', $data);
            
        } catch (\Throwable $e) {
            // Log the full error with stack trace
            log_message('critical', 'Exception in RoleController::edit() - ' . $e->getMessage());
            log_message('critical', 'File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            log_message('critical', 'Stack trace: ' . $e->getTraceAsString());
            
            // Show user-friendly error
            return redirect()->to('role')->with('error', 'An error occurred while loading the edit form. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Update role
     */
    public function update($roleId)
    {
        try {
            log_message('info', '=== START: Role Update Process - Role ID: ' . $roleId . ' ===');
            
            $this->session->set('prevUrl', $this->session->get('url'));
            $this->session->set('url', 'role/edit/' . $roleId);
            
            // Check if user is logged in
            if (!$this->isLoggedIn()) {
                log_message('warning', 'Update role attempt without login - Role ID: ' . $roleId);
                return redirect()->to('auth/login')->with('error', 'Please login to continue.');
            }
            
            log_message('debug', 'User logged in - User ID: ' . $this->session->get('userID'));
            
            // Check permission
            $accessCheck = $this->require_access('_edit_role');
            if ($accessCheck !== true) {
                log_message('warning', 'User lacks permission to edit role');
                return redirect()->to('role')->with('error', 'You do not have permission to edit roles.');
            }
            
            log_message('debug', 'Permission check passed');
            
            // Check if role exists
            log_message('debug', 'Checking if role exists - ID: ' . $roleId);
            $existingRole = $this->roleModel->get_role($roleId);
            
            if (!$existingRole) {
                log_message('error', 'Role not found for update - ID: ' . $roleId);
                return redirect()->to('role')->with('error', 'Role not found.');
            }
            
            log_message('debug', 'Existing role found');
            
            // Validation rules
            $rules = [
                'role_name' => [
                    'rules' => 'required|min_length[3]|max_length[100]',
                    'errors' => [
                        'required' => 'Role name is required',
                        'min_length' => 'Role name must be at least 3 characters',
                        'max_length' => 'Role name cannot exceed 100 characters'
                    ]
                ],
                'role_rank' => [
                    'rules' => 'required|integer|greater_than[0]|less_than[1000]',
                    'errors' => [
                        'required' => 'Role rank is required',
                        'integer' => 'Role rank must be a valid number',
                        'greater_than' => 'Role rank must be greater than 0',
                        'less_than' => 'Role rank must be less than 1000'
                    ]
                ],
                'role_desc' => [
                    'rules' => 'required|min_length[3]|max_length[1000]',
                    'errors' => [
                        'required' => 'Role description is required',
                        'min_length' => 'Role description must be at least 3 characters',
                        'max_length' => 'Role description cannot exceed 1000 characters'
                    ]
                ]
            ];
            
            log_message('debug', 'Starting validation');
            
            // Validate input
            if (!$this->validate($rules)) {
                $errors = $this->validator->getErrors();
                log_message('warning', 'Validation failed: ' . print_r($errors, true));
                return redirect()->back()
                                ->withInput()
                                ->with('validation', $this->validator);
            }
            
            log_message('debug', 'Validation passed');
            
            // Prepare data - REMOVE updated_at, let CI4 handle it
            $data = [
                'role_name' => $this->request->getPost('role_name'),
                'role_rank' => $this->request->getPost('role_rank'),
                'role_desc' => $this->request->getPost('role_desc')
                // updated_at is automatically handled by CI4
            ];
            
            log_message('debug', 'Data prepared for update: ' . print_r($data, true));
            
            // Update role
            log_message('debug', 'Calling roleModel->update_role()');
            $updated = $this->roleModel->update_role($roleId, $data);
            
            log_message('debug', 'Update result: ' . ($updated ? 'SUCCESS' : 'FAILED'));
            
            if ($updated) {
                log_message('info', 'Role updated successfully - ID: ' . $roleId . ', Name: ' . $data['role_name']);
                
                // Log activity
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Edit Role',
                    'log_desc' => 'Role "' . $data['role_name'] . '" has been updated successfully!',
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-verify"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'info'
                ];
                
                log_message('debug', 'Logging user activity');
                
                try {
                    $this->userLogModel->insert($userLogData);
                    log_message('debug', 'User log created');
                } catch (\Exception $logException) {
                    log_message('error', 'Failed to create user log: ' . $logException->getMessage());
                }
                
                log_message('info', '=== END: Role Update Process - SUCCESS ===');
                
                return redirect()->to('role')
                                ->with('success', 'Role "' . $data['role_name'] . '" has been updated successfully!');
            } else {
                log_message('error', 'Role update failed - No database change occurred');
                log_message('info', '=== END: Role Update Process - FAILED ===');
                
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Failed to update role. No changes were made to the database.');
            }
            
        } catch (\Throwable $e) {
            log_message('critical', '=== EXCEPTION in RoleController::update() ===');
            log_message('critical', 'Error: ' . $e->getMessage());
            log_message('critical', 'File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            log_message('critical', 'Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function delete($roleId)
    {
        // Use getMethod() instead of isPost()
        if (!$this->request->isAJAX() && strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ])->setStatusCode(405);
        }
        
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to continue'
            ])->setStatusCode(401);
        }
        
        try {
            // Get role
            $role = $this->roleModel->find($roleId);
            
            if (!$role) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Role not found'
                ])->setStatusCode(404);
            }
            
            // Get role name (handle both array and object)
            $roleName = is_array($role) ? $role['role_name'] : $role->role_name;
            
            // Delete role
            if ($this->roleModel->delete_role($roleId)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Role '{$roleName}' has been deleted successfully"
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete role from database'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting role: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the role'
            ])->setStatusCode(500);
        }
    }
    
    public function permission($roleId)
    {
        $view = '';
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'role/permission/'.$roleId);
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('View Role Permission', 'RBAC', 'Role Listing');
        
        // Get current user's role and rank
        $currentUserRoleId = $this->session->get('roleID');
        $currentUserRole = $this->roleModel->find($currentUserRoleId);
        $currentUserRank = $currentUserRole ? (is_array($currentUserRole) ? $currentUserRole['role_rank'] : $currentUserRole->role_rank) : 999;
        
        // Check if user has permission to manage role permissions
        $canManagePermissions = $this->require_access('_manage_role_permission');
        
        // Get target role data
        $role = $this->roleModel->get_role($roleId);
        
        if (!$role) {
            return redirect()->to('role')->with('error', 'The role that you are trying to view permission not found.');
        }
        
        // Get target role rank
        $targetRoleRank = is_array($role) ? ($role['role_rank'] ?? 999) : ($role->role_rank ?? 999);
        $targetRoleName = is_array($role) ? $role['role_name'] : $role->role_name;
        
        // Determine if user can edit this role's permissions
        // User can only edit roles with same or higher rank number (lower priority)
        $canEditThisRole = false;
        $rankRestrictionMessage = '';
        
        if ($canManagePermissions === true) {
            if ($currentUserRank <= $targetRoleRank) {
                // Current user has equal or better rank (lower number = higher priority)
                $canEditThisRole = true;
            } else {
                // Current user has lower rank (higher number = lower priority)
                $rankRestrictionMessage = "You cannot manage permissions for '{$targetRoleName}' (Rank {$targetRoleRank}) because your role rank ({$currentUserRank}) is lower. You can only manage roles with rank {$currentUserRank} or above.";
            }
        } else {
            $rankRestrictionMessage = "You do not have permission to manage role permissions. You are in view-only mode.";
        }
        
        // Create library instance
        $permissionViewLib = new \App\Libraries\PermissionViewLibrary();
        
        // Get all permissions with status
        $permissions = $permissionViewLib->getAllPermissionsWithStatus($roleId);
        
        // Pass data to view
        $data = [
            '_view' => 'app/role/permission',
            'role' => $role,
            'role_id' => $roleId,
            'permissions' => $permissions,
            'permissionCount' => count($permissions),
            'grantedCount' => count(array_filter($permissions, function($p) {
                return $p['has_permission'];
            })),
            'canEditPermissions' => $canEditThisRole,
            'rankRestrictionMessage' => $rankRestrictionMessage,
            'currentUserRank' => $currentUserRank,
            'targetRoleRank' => $targetRoleRank
        ];
        
        return view('app/layouts/main', $data);
    }
    
    /**
     * Update role permissions
     */
    public function updatePermissions($roleId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Get current user's role and rank
        $currentUserRoleId = $this->session->get('roleID');
        $currentUserRole = $this->roleModel->find($currentUserRoleId);
        $currentUserRank = $currentUserRole ? (is_array($currentUserRole) ? $currentUserRole['role_rank'] : $currentUserRole->role_rank) : 999;
        
        // Check permission to manage role permissions
        $canManagePermissions = $this->require_access('_manage_role_permission');
        if ($canManagePermissions !== true) {
            return redirect()->to('role/permission/' . $roleId)
                            ->with('error', 'You do not have permission to manage role permissions.');
        }
        
        // Get role data
        $role = $this->roleModel->find($roleId);
        
        if (!$role) {
            return redirect()->to('role')->with('error', 'Role not found.');
        }
        
        // Get target role rank
        $targetRoleRank = is_array($role) ? ($role['role_rank'] ?? 999) : ($role->role_rank ?? 999);
        $targetRoleName = is_array($role) ? $role['role_name'] : $role->role_name;
        
        // Check rank restriction
        if ($currentUserRank > $targetRoleRank) {
            return redirect()->to('role/permission/' . $roleId)
                            ->with('error', "You cannot manage permissions for '{$targetRoleName}' because its rank ({$targetRoleRank}) is higher than yours ({$currentUserRank}).");
        }
        
        try {
            // Get selected permissions from form
            $selectedPermissions = $this->request->getPost('permissions') ?? [];
            
            // Update role permissions
            $updated = $this->rolePermissionModel->updateRolePermissions($roleId, $selectedPermissions);
            
            if ($updated) {
                // Log activity
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Update Role Permissions',
                    'log_desc' => 'Permissions for role ' . $targetRoleName . ' have been updated. Total permissions: ' . count($selectedPermissions),
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-shield-tick"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'success'
                ];
                
                $addUserLog = $this->userLogModel->addUserLog($userLogData);
                
                return redirect()->to('role/permission/' . $roleId)
                                ->with('success', "Permissions for role '{$targetRoleName}' have been updated successfully!");
            } else {
                return redirect()->back()
                                ->with('error', 'Failed to update permissions. Please try again.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error updating permissions: ' . $e->getMessage());
            
            return redirect()->back()
                            ->with('error', 'An error occurred while updating permissions.');
        }
    }
}