<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table = 'permission';
    protected $primaryKey = 'perm_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'module_id_fk',
        'perm_name',
        'perm_desc',
        'perm_controller',
        'perm_code',  
        'show_in_nav',
        'perm_status',
        'created_at',
        'updated_at'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'perm_name' => 'required|min_length[3]|max_length[100]',
        'perm_code' => 'required|min_length[3]|max_length[50]|is_unique[permission.perm_code,perm_id,{perm_id}]',
        'module_id_fk' => 'required|integer',
        'perm_controller' => 'permit_empty|max_length[100]',
    ];
    
    protected $validationMessages = [
        'perm_name' => [
            'required' => 'Permission name is required',
            'min_length' => 'Permission name must be at least 3 characters long',
            'max_length' => 'Permission name cannot exceed 100 characters',
        ],
        'perm_code' => [
            'required' => 'Permission code is required',
            'min_length' => 'Permission code must be at least 3 characters long',
            'max_length' => 'Permission code cannot exceed 50 characters',
            'is_unique' => 'Permission code already exists',
        ],
        'module_id_fk' => [
            'required' => 'Module is required',
            'integer' => 'Module must be a valid integer',
        ],
    ];
    
    protected $skipValidation = true;
    
    // Datatables properties
    protected $columnOrder = ['perm_id', 'module_id_fk', 'perm_name', 'perm_desc', 'perm_controller', 'perm_code', 'show_in_nav', 'perm_status', null];
    protected $columnSearch = ['perm_id', 'module_id_fk', 'perm_name', 'perm_desc', 'perm_controller', 'perm_code', 'show_in_nav', 'perm_status'];
    protected $order = ['perm_name' => 'asc'];
    
    /**
     * Get all active permissions
     * 
     * @return array
     */
    public function getActivePermissions()
    {
        return $this->where('perm_status', 'Active')
                    ->orderBy('module_id_fk', 'ASC')
                    ->orderBy('perm_name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get permission by code
     * 
     * @param string $permCode
     * @return array|null
     */
    public function getByCode($permCode)
    {
        return $this->where('perm_code', $permCode)->first();
    }
    
    /**
     * Get permissions by category
     * 
     * @param string $category
     * @return array
     */
    public function getByCategory($category)
    {
        return $this->where('module_id_fk', $category)
                    ->orderBy('perm_name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get all permission categories
     * 
     * @return array
     */
    public function getCategories()
    {
        return $this->select('module_id_fk')
                    ->distinct()
                    ->orderBy('module_id_fk', 'ASC')
                    ->findColumn('module_id_fk');
    }
    
    /**
     * Get permission by perm_id with module info
     * 
     * @param int $perm_id
     * @return array|null
     */
    public function get_permission($perm_id)
    {
        try {
            return $this->db->table('permission')
                ->select('permission.*, modules.*')
                ->join('modules', 'modules.module_id = permission.module_id_fk', 'inner')
                ->where('permission.perm_id', $perm_id)
                ->get()
                ->getRowArray();
        } catch (\Exception $e) {
            log_message('error', 'Error getting permission: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get permission by name
     * 
     * @param string $name
     * @return array|null
     */
    public function get_permission_name($name)
    {
        try {
            return $this->where('perm_name', $name)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error getting permission by name: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get permission by code
     * 
     * @param string $code
     * @return array|null
     */
    public function get_permission_by_code($code)
    {
        try {
            return $this->where('perm_code', $code)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error getting permission by code: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all permissions
     * 
     * @return array
     */
    public function get_all_permissions()
    {
        return $this->orderBy('perm_id', 'DESC')->findAll();
    }
    
    /**
     * Get permissions with module info
     * 
     * @return array
     */
    public function get_all_permissions_with_modules()
    {
        try {
            return $this->db->table('permission')
                ->select('permission.*, modules.module_name')
                ->join('modules', 'modules.module_id = permission.module_id_fk', 'left')
                ->orderBy('permission.perm_name', 'ASC')
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error getting permissions with modules: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Add new permission
     * 
     * @param array $params
     * @return int|bool Insert ID or false on failure
     */
    public function add_permission($params)
    {
        try {
            return $this->insert($params);
        } catch (\Exception $e) {
            log_message('error', 'Error adding permission: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update permission
     * 
     * @param int $perm_id
     * @param array $params
     * @return bool
     */
    /*public function update_permission($perm_id, $params)
    {
        try {
            return $this->update($perm_id, $params);
        } catch (\Exception $e) {
            log_message('error', 'Error updating permission: ' . $e->getMessage());
            return false;
        }
    }*/
    
    /**
     * Update permission
     */
    public function update_permission($perm_id, $params)
    {
        try {
            log_message('debug', '=== PermissionModel::update_permission() START ===');
            log_message('debug', 'Permission ID: ' . $perm_id);
            log_message('debug', 'Params received: ' . json_encode($params));
            
            // Get existing permission
            $existingPermission = $this->find($perm_id);
            
            if (!$existingPermission) {
                log_message('error', 'Permission not found - ID: ' . $perm_id);
                return false;
            }
            
            log_message('debug', 'Existing permission: ' . json_encode($existingPermission));
            
            // Compare each field
            foreach ($params as $key => $value) {
                $oldValue = $existingPermission[$key] ?? 'NULL';
                $newValue = $value ?? 'NULL';
                
                if ($oldValue != $newValue) {
                    log_message('debug', "CHANGED: {$key} = '{$oldValue}' -> '{$newValue}'");
                } else {
                    log_message('debug', "SAME: {$key} = '{$oldValue}'");
                }
            }
            
            // Try direct update with CI4's update()
            log_message('debug', 'Attempting update...');
            
            // Use update() directly
            $result = $this->update($perm_id, $params);
            
            log_message('debug', 'Update result: ' . var_export($result, true));
            log_message('debug', 'Affected rows: ' . $this->db->affectedRows());
            
            // Check for database errors
            if ($this->db->error()) {
                $error = $this->db->error();
                log_message('error', 'Database error: ' . json_encode($error));
            }
            
            // If update returns false but data is same, that's okay
            if ($result === false) {
                $affectedRows = $this->db->affectedRows();
                
                if ($affectedRows === 0) {
                    // Check if data is actually the same
                    $isIdentical = true;
                    foreach ($params as $key => $value) {
                        if (isset($existingPermission[$key]) && $existingPermission[$key] != $value) {
                            $isIdentical = false;
                            break;
                        }
                    }
                    
                    if ($isIdentical) {
                        log_message('info', 'No changes needed - data is identical');
                        return true;
                    }
                }
                
                log_message('error', 'Update failed - Affected rows: ' . $affectedRows);
                return false;
            }
            
            log_message('info', 'Update successful');
            return true;
            
        } catch (\Exception $e) {
            log_message('error', 'Exception in update_permission: ' . $e->getMessage());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            return false;
        }
    }
    
    
    
    /**
     * Delete permission
     * 
     * @param int $perm_id
     * @return bool
     */
    public function delete_permission($perm_id)
    {
        try {
            return $this->delete($perm_id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting permission: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Search permissions
     * 
     * @param string $string
     * @param int $limit
     * @return array
     */
    public function searchPermission($string, $limit = 10)
    {
        try {
            return $this->like('perm_name', $string)
                       ->orderBy('perm_id', 'ASC')
                       ->limit($limit)
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching permissions: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get permissions by module
     * 
     * @param int $module_id
     * @return array
     */
    public function get_permissions_by_module($module_id)
    {
        return $this->where('module_id_fk', $module_id)
                   ->orderBy('perm_name', 'ASC')
                   ->findAll();
    }
    
    // ================================
    // Datatables Methods
    // ================================
    
    /**
     * Fetch permissions data for DataTables
     * 
     * @param array $postData
     * @return array
     */
    public function getRows($postData)
    {
        $this->_get_datatables_query($postData);
        
        if (isset($postData['length']) && $postData['length'] != -1) {
            $this->limit($postData['length'], $postData['start']);
        }
        
        $query = $this->get();
        return $query->getResultArray();
    }
    
    /**
     * Count all records
     * 
     * @return int
     */
    public function countAll()
    {
        return $this->countAllResults();
    }
    
    /**
     * Count filtered records
     * 
     * @param array $postData
     * @return int
     */
    public function countFiltered($postData)
    {
        $this->_get_datatables_query($postData);
        return $this->countAllResults();
    }
    
    /**
     * Build the DataTables query with module join
     * 
     * @param array $postData
     */
    private function _get_datatables_query($postData)
    {
        $this->select('permission.*, modules.module_name');
        $this->from($this->table);
        $this->join('modules', 'modules.module_id = permission.module_id_fk', 'inner');
        
        $i = 0;
        
        // Loop through searchable columns
        foreach ($this->columnSearch as $item) {
            // Check if search value exists
            if (isset($postData['search']['value']) && !empty($postData['search']['value'])) {
                // First loop
                if ($i === 0) {
                    $this->groupStart();
                    $this->like($item, $postData['search']['value']);
                } else {
                    $this->orLike($item, $postData['search']['value']);
                }
                
                // Last loop
                if (count($this->columnSearch) - 1 == $i) {
                    $this->groupEnd();
                }
            }
            $i++;
        }
        
        // Order by
        if (isset($postData['order'])) {
            $columnIndex = $postData['order']['0']['column'];
            $columnName = isset($this->columnOrder[$columnIndex]) ? $this->columnOrder[$columnIndex] : null;
            $orderDir = $postData['order']['0']['dir'];
            
            if ($columnName) {
                // Handle special case for joined columns
                if ($columnName === 'module_id_fk') {
                    $this->orderBy('modules.module_name', $orderDir);
                } else {
                    $this->orderBy($columnName, $orderDir);
                }
            }
        } else if (isset($this->order)) {
            foreach ($this->order as $key => $value) {
                $this->orderBy($key, $value);
            }
        }
    }
    
    /**
     * Get permissions for DataTables (simplified version)
     * 
     * @param array $filters
     * @return array
     */
    public function getDatatablePermissions($filters = [])
    {
        $builder = $this->db->table('permission')
            ->select('permission.*, modules.module_name')
            ->join('modules', 'modules.module_id = permission.module_id_fk', 'left');
        
        // Apply filters
        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('permission.perm_name', $filters['search'])
                   ->orLike('permission.perm_code', $filters['search'])
                   ->orLike('modules.module_name', $filters['search'])
                   ->groupEnd();
        }
        
        if (!empty($filters['module_id'])) {
            $builder->where('permission.module_id_fk', $filters['module_id']);
        }
        
        // Get total count
        $total = $builder->countAllResults(false);
        
        // Apply pagination
        if (!empty($filters['length']) && $filters['length'] != -1) {
            $builder->limit($filters['length'], $filters['start']);
        }
        
        // Apply ordering
        if (!empty($filters['order_by']) && !empty($filters['order_dir'])) {
            $builder->orderBy($filters['order_by'], $filters['order_dir']);
        } else {
            $builder->orderBy('permission.perm_name', 'ASC');
        }
        
        $data = $builder->get()->getResultArray();
        
        return [
            'data' => $data,
            'total' => $total,
            'filtered' => $total, // For filtered total, you'd need separate count
        ];
    }
    
    /**
     * Get permissions grouped by module
     * 
     * @return array
     */
    public function getPermissionsGroupedByModule()
    {
        try {
            $permissions = $this->db->table('permission')
                ->select('permission.*, modules.module_name')
                ->join('modules', 'modules.module_id = permission.module_id_fk', 'left')
                ->orderBy('modules.module_name', 'ASC')
                ->orderBy('permission.perm_name', 'ASC')
                ->get()
                ->getResultArray();
            
            $grouped = [];
            foreach ($permissions as $perm) {
                $moduleName = $perm['module_name'] ?? 'Uncategorized';
                $grouped[$moduleName][] = $perm;
            }
            
            return $grouped;
        } catch (\Exception $e) {
            log_message('error', 'Error grouping permissions by module: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Sync permissions from controllers (auto-discovery)
     * 
     * @return array
     */
    public function syncPermissionsFromControllers($module_id = null)
    {
        $newPermissions = [];
        
        // Get all controllers (you'll need to implement this based on your structure)
        $controllers = $this->discoverControllers();
        
        foreach ($controllers as $controller) {
            $methods = $this->getControllerMethods($controller);
            
            foreach ($methods as $method) {
                $permCode = strtolower($controller . '_' . $method);
                $permName = ucfirst($controller) . ' ' . ucfirst($method);
                
                // Check if permission exists
                $existing = $this->where('perm_code', $permCode)->first();
                
                if (!$existing) {
                    $permissionData = [
                        'perm_name' => $permName,
                        'perm_code' => $permCode,
                        'perm_controller' => $controller,
                        'perm_method' => $method,
                        'module_id_fk' => $module_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    if ($this->add_permission($permissionData)) {
                        $newPermissions[] = $permissionData;
                    }
                }
            }
        }
        
        return $newPermissions;
    }
    
    /**
     * Helper method to discover controllers
     */
    private function discoverControllers()
    {
        $controllers = [];
        $controllerPath = APPPATH . 'Controllers/';
        
        // Recursively scan for controller files
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($controllerPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace([$controllerPath, '.php'], '', $file->getPathname());
                $className = str_replace('/', '\\', $relativePath);
                $controllers[] = $className;
            }
        }
        
        return $controllers;
    }
    
    /**
     * Helper method to get controller methods
     */
    private function getControllerMethods($controller)
    {
        // This is a simplified version
        // You'll need to adjust based on your actual controller structure
        $methods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
        return $methods;
    }
}