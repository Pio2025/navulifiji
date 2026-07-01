<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'role_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; 
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['role_name', 'role_desc', 'role_rank', 'role_cat_id_fk', 'created_at', 'updated_at'];
    
    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'role_name' => 'required|min_length[3]|max_length[100]|is_unique[role.role_name,role_id,{role_id}]',
    ];
    
    protected $validationMessages = [
        'role_name' => [
            'required' => 'Role name is required',
            'min_length' => 'Role name must be at least 3 characters long',
            'max_length' => 'Role name cannot exceed 100 characters',
            'is_unique' => 'Role name already exists',
        ],
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get role by role_id
     */
    public function get_role($role_id)
    {
        return $this->where('role_id', $role_id)->first();
        // OR
        //return $this->find($role_id);
    }
    
    /**
     * Get role by name
     */
    public function get_role_name($name)
    {
        return $this->where('role_name', $name)->first();
    }
    
    /**
     * Search roles
     */
    public function searchRole($string, $limit = 10)
    {
        return $this->like('role_name', $string)
                    ->orderBy('role_id', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }
    
    /**
     * Get all roles
     */
    public function getAllRoles()
    {
        return $this->orderBy('role_rank', 'ASC')->findAll();
    }
    
    /**
     * Add new role
     */
    public function add_role($params)
    {
        try {
            return $this->insert($params);
        } catch (\Exception $e) {
            log_message('error', 'Error adding role: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update role
     */
    /*public function update_role($role_id, $params)
    {
        try {
            return $this->update($role_id, $params);
        } catch (\Exception $e) {
            log_message('error', 'Error updating role: ' . $e->getMessage());
            return false;
        }
    }*/
    
    /**
     * Update role
     */
    public function update_role($role_id, $params)
    {
        try {
            log_message('debug', '=== RoleModel::update_role() START ===');
            log_message('debug', 'Role ID: ' . $role_id);
            log_message('debug', 'Params: ' . print_r($params, true));
            
            // Get existing role data for comparison
            $existingRole = $this->find($role_id);
            log_message('debug', 'Existing role: ' . print_r($existingRole, true));
            
            if (!$existingRole) {
                log_message('error', 'Role not found in database - ID: ' . $role_id);
                return false;
            }
            
            // Remove updated_at from params - let CI4 handle it automatically
            if (isset($params['updated_at'])) {
                unset($params['updated_at']);
                log_message('debug', 'Removed updated_at from params (CI4 will handle it)');
            }
            
            log_message('debug', 'Final params for update: ' . print_r($params, true));
            
            // Perform update
            $result = $this->update($role_id, $params);
            
            log_message('debug', 'Update result type: ' . gettype($result));
            log_message('debug', 'Update result value: ' . var_export($result, true));
            
            // CI4's update() returns true on success, false on failure
            // But it returns false if no rows were affected (data unchanged)
            if ($result === false) {
                log_message('warning', 'Update returned false - checking if data is identical');
                
                // Check if the data is actually the same
                $existingArray = is_object($existingRole) ? (array)$existingRole : $existingRole;
                $isIdentical = true;
                
                foreach ($params as $key => $value) {
                    if (isset($existingArray[$key]) && $existingArray[$key] != $value) {
                        $isIdentical = false;
                        log_message('debug', "Field '{$key}' changed: '{$existingArray[$key]}' -> '{$value}'");
                        break;
                    }
                }
                
                if ($isIdentical) {
                    log_message('info', 'No actual changes detected - data is identical');
                    // Return true because technically the role IS in the desired state
                    return true;
                } else {
                    log_message('error', 'Update failed despite data differences');
                    return false;
                }
            }
            
            // Verify the update
            $updatedRole = $this->find($role_id);
            log_message('debug', 'Updated role: ' . print_r($updatedRole, true));
            log_message('debug', '=== RoleModel::update_role() END - SUCCESS ===');
            
            return true;
            
        } catch (\Exception $e) {
            log_message('error', '=== RoleModel::update_role() EXCEPTION ===');
            log_message('error', 'Exception: ' . $e->getMessage());
            log_message('error', 'File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Delete role
     */
    public function delete_role($role_id)
    {
        try {
            return $this->delete($role_id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting role: ' . $e->getMessage());
            return false;
        }
    }
    
    // ================================
    // DataTables Server-Side Methods
    // ================================
    
    /**
     * Get rows for DataTables with server-side processing
     * 
     * @param array $postData
     * @return array
     */
    public function getRows($postData)
    {
        $builder = $this->db->table($this->table);
        
        // Select columns
        $builder->select('role_id, role_name, role_desc, role_rank');
        
        // Search functionality
        if (!empty($postData['search']['value'])) {
            $searchValue = $postData['search']['value'];
            $builder->groupStart()
                    ->like('role_name', $searchValue)
                    ->orLike('role_desc', $searchValue)
                    ->groupEnd();
        }
        
        // Ordering
        if (isset($postData['order'])) {
            $orderColumn = $postData['order']['column'] ?? 'role_name';
            $orderDir = $postData['order']['dir'] ?? 'ASC';
            
            // Validate column name to prevent SQL injection
            $allowedColumns = ['role_name', 'role_desc', 'role_rank'];

            if (in_array($orderColumn, $allowedColumns)) {
                $builder->orderBy($orderColumn, $orderDir);
            } else {
                $builder->orderBy('role_rank', 'ASC');
            }
        } else {
            $builder->orderBy('role_rank', 'ASC');
        }
        
        // Pagination
        if (isset($postData['length']) && $postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start'] ?? 0);
        }
        
        $query = $builder->get();
        
        return $query->getResult();
    }
    
    /**
     * Count all records (without filters)
     * 
     * @return int
     */
    public function countAll(): int
    {
        return $this->db->table($this->table)->countAllResults();
    }
    
    /**
     * Count filtered records (with search filter applied)
     * 
     * @param array $postData
     * @return int
     */
    public function countFiltered($postData): int
    {
        $builder = $this->db->table($this->table);
        
        // Apply same search conditions as getRows
        if (!empty($postData['search']['value'])) {
            $searchValue = $postData['search']['value'];
            $builder->groupStart()
                    ->like('role_name', $searchValue)
                    ->orLike('role_desc', $searchValue)
                    ->groupEnd();
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Get roles with pagination (alternative method for non-AJAX)
     */
    public function getRolesPaginated($perPage = 10, $page = 1, $search = null)
    {
        if (!empty($search)) {
            $this->groupStart()
                 ->like('role_name', $search)
                 ->orLike('role_desc', $search)
                 ->groupEnd();
        }
        
        return $this->orderBy('role_name', 'ASC')
                    ->paginate($perPage, 'default', $page);
    }
    
    /**
     * Get total count with search filter (for pagination info)
     */
    public function getRolesCount($search = null): int
    {
        if (!empty($search)) {
            $this->groupStart()
                 ->like('role_name', $search)
                 ->orLike('role_desc', $search)
                 ->groupEnd();
        }
        
        return $this->countAllResults(false); // false to not reset the query
    }
}