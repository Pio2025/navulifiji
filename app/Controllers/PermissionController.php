<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PermissionController extends BaseController
{
    protected $validation;
    protected $db; // Add this property
    
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Call parent first to initialize all models
        parent::initController($request, $response, $logger);
        
        // Initialize database connection
        $this->db = \Config\Database::connect();
        
        // Initialize validation service
        $this->validation = \Config\Services::validation();
        
        // Models are already initialized in BaseController:
        // - $this->permissionModel
        // - $this->moduleModel
        // - $this->userLogModel
        // - $this->session
    }
    
    /**
     * Display permission listing page
     */
    public function index()
    {
        $view = '';
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'permission');
        
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata (if you have this method in BaseController)
        $this->setPageData('View Permission Listing', 'RBAC', 'Permission Listing');
        
        // Check permission
        $accessCheck = $this->require_access('_permission_listing');
        if ($accessCheck !== true) {
            $view = 'app/auth/access_control';
        } else {
            $view = 'app/permission/index';
        }
        
        $data['_view'] = $view;
        
        // Add user log using parent's method
        $userLogData = [
            'user_id_fk' => $this->session->get('userID'),
            'ip_aadress' => $this->ipAddress,
            'user_agent' => $this->userAgent->getAgentString(),
            'user_device' => $this->deviceInfo['device_type'],
            'log_title' => 'View Permission Listing',
            'log_desc' => 'User viewed permission listing.',
            'log_date' => date('Y-m-d'),
            'log_time' => time(),
            'log_icon' => '<i class="ki-duotone ki-shield-tick"><span class="path1"></span><span class="path2"></span></i>',
            'log_theme' => 'info'
        ];
        
        $this->userLogModel->insert($userLogData);
        
        return view('app/layouts/main', $data);
    }
    
    /**
     * Get permission listing data for DataTables (AJAX)
     */
    public function getPermissionListing()
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
            $columns = ['modules.module_name', 'permission.perm_name', 'permission.perm_code', 'permission.show_in_nav', 'permission.perm_status', null];
            $orderColumn = $columns[$orderColumnIndex] ?? 'modules.module_name';
            
            // Prevent sorting on actions column
            if ($orderColumn === null || $orderColumnIndex === 5) {
                $orderColumn = 'modules.module_name';
            }
            
            // Validate sort direction
            $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
            
            log_message('debug', "Order by: {$orderColumn} {$orderDir}");
            
            // Build query with module join
            $builder = $this->db->table('permission');
            $builder->select('permission.*, modules.module_name');
            $builder->join('modules', 'modules.module_id = permission.module_id_fk', 'left');
            
            // Apply search filter
            if (!empty($searchValue)) {
                $builder->groupStart()
                    ->like('permission.perm_name', $searchValue)
                    ->orLike('permission.perm_code', $searchValue)
                    ->orLike('modules.module_name', $searchValue)
                    ->orLike('permission.perm_status', $searchValue)
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
            $permissions = $builder->get()->getResultArray();
            
            // Get total count (without filters)
            $recordsTotal = $this->db->table('permission')->countAllResults();
            
            log_message('debug', "Found {$recordsFiltered} filtered permissions out of {$recordsTotal} total");
            
            // Format data for DataTables
            $data = [];
            foreach ($permissions as $permission) {
                $data[] = [
                    $permission['module_name'] ?? 'N/A',
                    $permission['perm_name'] ?? '',
                    $permission['perm_code'] ?? '',
                    $this->formatShowInNav($permission['show_in_nav'] ?? 0),
                    $this->formatStatus($permission['perm_status'] ?? 'Inactive'),
                    $this->createActionButtons($permission['perm_id'])
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
            log_message('error', 'Permission listing error: ' . $e->getMessage());
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
     * Format Show In Nav value
     */
    private function formatShowInNav($value)
    {
        if ($value == 1 || $value === 'Yes') {
            return '<span class="badge badge-light-success">Yes</span>';
        } else {
            return '<span class="badge badge-light-danger">No</span>';
        }
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
     * Create action buttons HTML for each permission
     * 
     * @param int $permId
     * @return string
     */
    private function createActionButtons($permId)
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
                <!--div class="menu-item px-3">
                    <a href="' . base_url('permission/detail/' . $permId) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-eye fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        View Details
                    </a>
                </div-->
                
                <div class="menu-item px-3">
                    <a href="' . base_url('permission/edit/' . $permId) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-pencil fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Edit Permission
                    </a>
                </div>
                
                <div class="separator my-2"></div>
                
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3 text-danger" data-kt-permissions-table-filter="delete_row" data-permission-id="' . $permId . '">
                        <i class="ki-duotone ki-trash fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Delete Permission
                    </a>
                </div>
            </div>
        </div>';
    }
    
    /**
     * Display add permission form
     */
    public function add()
    {
        $view = '';
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'permission/add');
        
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Check permission
        $accessCheck = $this->require_access('_add_permission');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
        } else {
            // Get all modules for dropdown
            $modules = $this->moduleModel->get_all_modules();
            
            $data['modules'] = $modules;
            $data['_view'] = 'app/permission/add';
        }
        
        return view('app/layouts/main', $data);
    }
    
    /**
     * Store new permission
     */
    public function store()
    {
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'permission/add');
        
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Check permission
        $accessCheck = $this->require_access('_add_permission');
        if ($accessCheck !== true) {
            return redirect()->to('permission')->with('error', 'You do not have permission to add permissions.');
        }
        
        // Validation rules
        $rules = [
            'perm_name' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Permission name is required',
                    'min_length' => 'Permission name must be at least 3 characters',
                    'max_length' => 'Permission name cannot exceed 255 characters'
                ]
            ],
            'perm_code' => [
                'rules' => 'required|min_length[3]|max_length[50]|is_unique[permission.perm_code]',
                'errors' => [
                    'required' => 'Permission code is required',
                    'min_length' => 'Permission code must be at least 3 characters',
                    'max_length' => 'Permission code cannot exceed 50 characters',
                    'is_unique' => 'This permission code already exists'
                ]
            ],
            'module_id_fk' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Module is required',
                    'integer' => 'Module must be a valid selection'
                ]
            ],
            'perm_desc' => [
                'rules' => 'permit_empty|max_length[1000]',
                'errors' => [
                    'max_length' => 'Description cannot exceed 1000 characters'
                ]
            ],
            'perm_controller' => [
                'rules' => 'permit_empty|max_length[100]',
                'errors' => [
                    'max_length' => 'Controller name cannot exceed 100 characters'
                ]
            ]
        ];
        
        // Validate input
        if (!$this->validate($rules)) {
            return redirect()->back()
                            ->withInput()
                            ->with('validation', $this->validator);
        }
        
        try {
            // Prepare data
            $data = [
                'module_id_fk' => $this->request->getPost('module_id_fk'),
                'perm_name' => $this->request->getPost('perm_name'),
                'perm_code' => $this->request->getPost('perm_code'),
                'perm_desc' => $this->request->getPost('perm_desc') ?: null,
                'perm_controller' => $this->request->getPost('perm_controller') ?: null,
                'show_in_nav' => $this->request->getPost('show_in_nav') ? 1 : 0,
                'perm_status' => $this->request->getPost('perm_status') ?: 'Active'
            ];
            
            // Insert permission
            $permId = $this->permissionModel->insert($data);
            
            if ($permId) {
                // Log activity
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Add New Permission',
                    'log_desc' => 'Permission "' . $data['perm_name'] . '" has been created successfully!',
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-shield-tick"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'success'
                ];
                
                $this->userLogModel->insert($userLogData);
                
                return redirect()->to('permission')
                                ->with('success', 'Permission "' . $data['perm_name'] . '" has been created successfully!');
            } else {
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Failed to create permission. Please try again.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error creating permission: ' . $e->getMessage());
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred while creating the permission: ' . $e->getMessage());
        }
    }
    
    /**
     * Display edit permission form
     */
    public function edit($permId)
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $view = '';
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'permission/edit/' . $permId);
        
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Check permission
        $accessCheck = $this->require_access('_edit_permission');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
        } else {
            // Get permission data
            $permission = $this->permissionModel->find($permId);
            
            if (!$permission) {
                return redirect()->to('permission')->with('error', 'The permission you are trying to edit was not found.');
            }
            
            // Get all modules for dropdown
            $modules = $this->moduleModel->get_all_modules();
            
            $data['permission'] = $permission;
            $data['modules'] = $modules;
            $data['_view'] = 'app/permission/edit';
        }
        
        return view('app/layouts/main', $data);
    }
    
    public function update($permId)
    {
        try {
            $this->session->set('prevUrl', $this->session->get('url'));
            $this->session->set('url', 'permission/edit/' . $permId);
            
            // Check if user is logged in
            if (!$this->session->get('isLoggedIn')) {
                return redirect()->to('auth/login')->with('error', 'Please login to continue.');
            }
            
            // Check permission
            $accessCheck = $this->require_access('_edit_permission');
            if ($accessCheck !== true) {
                return redirect()->to('permission')->with('error', 'You do not have permission to edit permissions.');
            }
            
            // Check if permission exists
            $existingPermission = $this->permissionModel->find($permId);
            if (!$existingPermission) {
                return redirect()->to('permission')->with('error', 'Permission not found.');
            }
            
            // Validation rules
            $rules = [
                'perm_name' => 'required|min_length[3]|max_length[255]',
                'perm_code' => "required|min_length[3]|max_length[50]|is_unique[permission.perm_code,perm_id,{$permId}]",
                'module_id_fk' => 'required|integer',
                'perm_desc' => 'permit_empty|max_length[1000]',
                'perm_controller' => 'permit_empty|max_length[100]'
            ];
            
            // Validate input
            if (!$this->validate($rules)) {
                return redirect()->back()
                                ->withInput()
                                ->with('validation', $this->validator);
            }
            
            // Prepare data - ensure correct types
            $data = [
                'module_id_fk' => (int)$this->request->getPost('module_id_fk'),
                'perm_name' => trim($this->request->getPost('perm_name')),
                'perm_code' => trim($this->request->getPost('perm_code')),
                'perm_desc' => $this->request->getPost('perm_desc') ? trim($this->request->getPost('perm_desc')) : null,
                'perm_controller' => $this->request->getPost('perm_controller') ? trim($this->request->getPost('perm_controller')) : null,
                'show_in_nav' => (int)($this->request->getPost('show_in_nav') ? 1 : 0),
                'perm_status' => $this->request->getPost('perm_status') ?: 'Active'
            ];
            
            log_message('debug', 'Update data: ' . json_encode($data));
            
            // Use model's update directly instead of update_permission
            $updated = $this->permissionModel->update($permId, $data);
            
            log_message('debug', 'Update result: ' . var_export($updated, true));
            log_message('debug', 'Affected rows: ' . $this->permissionModel->db->affectedRows());
            
            // Check affected rows instead of just boolean result
            $affectedRows = $this->permissionModel->db->affectedRows();
            
            if ($updated !== false || $affectedRows >= 0) {
                // Log activity
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Edit Permission',
                    'log_desc' => 'Permission "' . $data['perm_name'] . '" has been updated successfully!',
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-verify"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'info'
                ];
                
                $this->userLogModel->insert($userLogData);
                
                if ($affectedRows > 0) {
                    $message = 'Permission "' . $data['perm_name'] . '" has been updated successfully!';
                } else {
                    $message = 'Permission "' . $data['perm_name'] . '" - No changes detected (data was already up to date).';
                }
                
                return redirect()->to('permission')
                                ->with('success', $message);
            } else {
                // Check for database error
                $dbError = $this->permissionModel->db->error();
                log_message('error', 'Database error: ' . json_encode($dbError));
                
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Failed to update permission. Database error: ' . ($dbError['message'] ?? 'Unknown error'));
            }
            
        } catch (\Throwable $e) {
            log_message('critical', 'Exception in update: ' . $e->getMessage());
            log_message('critical', 'Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    
    /**
     * Delete permission
     */
    public function delete($permId)
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check permission
        $accessCheck = $this->require_access('_delete_permission');
        if ($accessCheck !== true) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have permission to delete permissions'
            ])->setStatusCode(403);
        }
        
        try {
            // Get permission
            $permission = $this->permissionModel->find($permId);
            
            if (!$permission) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Permission not found'
                ])->setStatusCode(404);
            }
            
            $permName = $permission['perm_name'];
            
            // Delete permission
            if ($this->permissionModel->delete($permId)) {
                // Log activity
                $userLogData = [
                    'user_id_fk' => $this->session->get('userID'),
                    'ip_aadress' => $this->ipAddress,
                    'user_agent' => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title' => 'Delete Permission',
                    'log_desc' => "Permission '{$permName}' has been deleted",
                    'log_date' => date('Y-m-d'),
                    'log_time' => time(),
                    'log_icon' => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme' => 'danger'
                ];
                
                $this->userLogModel->insert($userLogData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Permission '{$permName}' has been deleted successfully"
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete permission from database'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting permission: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the permission'
            ])->setStatusCode(500);
        }
    }
}