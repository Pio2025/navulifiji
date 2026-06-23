<?php

namespace App\Models;

use CodeIgniter\Model;

class RolePermissionModel extends Model
{
    protected $table = 'role_permission';
    protected $primaryKey = 'role_perm_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['role_id_fk', 'perm_id_fk'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'role_id_fk' => 'required|integer',
        'perm_id_fk' => 'required|integer',
    ];
    
    protected $validationMessages = [
        'role_id_fk' => [
            'required' => 'Role ID is required',
            'integer' => 'Role ID must be an integer',
        ],
        'perm_id_fk' => [
            'required' => 'Permission ID is required',
            'integer' => 'Permission ID must be an integer',
        ],
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get role_permission by role_perm_id
     * 
     * @param int $role_perm_id
     * @return array|null
     */
    public function get_role_permission($role_perm_id)
    {
        return $this->find($role_perm_id);
    }
    
    /**
     * Get all role_permission
     * 
     * @return array
     */
    public function get_all_role_permission()
    {
        return $this->orderBy('role_perm_id', 'ASC')->findAll();
    }
    
    /**
     * Add new role_permission
     * 
     * @param array $params
     * @return int|bool Insert ID or false on failure
     */
    public function add_role_permission($params)
    {
        try {
            return $this->insert($params);
        } catch (\Exception $e) {
            log_message('error', 'Error adding role permission: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update role_permission
     * 
     * @param int $role_perm_id
     * @param array $params
     * @return bool
     */
    public function update_role_permission($role_perm_id, $params)
    {
        try {
            return $this->update($role_perm_id, $params);
        } catch (\Exception $e) {
            log_message('error', 'Error updating role permission: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete role_permission
     * 
     * @param int $role_perm_id
     * @return bool
     */
    public function delete_role_permission($role_perm_id)
    {
        try {
            return $this->delete($role_perm_id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting role permission: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete role permission by role ID
     * 
     * @param int $role_id
     * @return bool
     */
    public function delete_role_permission_by_role($role_id)
    {
        try {
            return $this->where('role_id_fk', $role_id)->delete();
        } catch (\Exception $e) {
            log_message('error', 'Error deleting role permissions by role: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete role permission by role and permission
     * 
     * @param int $role_id
     * @param int $perm_id
     * @return bool
     */
    public function delete_role_permission_by_role_and_perm($role_id, $perm_id)
    {
        try {
            return $this->where('role_id_fk', $role_id)
                       ->where('perm_id_fk', $perm_id)
                       ->delete();
        } catch (\Exception $e) {
            log_message('error', 'Error deleting role permission by role and permission: ' . $e->getMessage());
            return false;
        }
    }
    
     /**
     * Get all permissions for a specific role
     * More efficient than checking one permission at a time
     */
    public function getRolePermissions($role_id)
    {
        return $this->db->table('role_permission')
            ->select('permission.perm_code, permission.perm_name')
            ->join('permission', 'permission.perm_id = role_permission.perm_id_fk')
            ->where('role_permission.role_id_fk', $role_id)
            ->get()
            ->getResultArray();
    }
    
    /**
     * Check if role has specific permission
     * 
     * @param int $roleId
     * @param int $permId
     * @return bool
     */
    public function roleHasPermission($roleId, $permId)
    {
        $result = $this->where('role_id_fk', $roleId)
                       ->where('perm_id_fk', $permId)
                       ->first();
        
        return $result !== null;
    }
    
    /**
     * Assign permission to role
     * 
     * @param int $roleId
     * @param int $permId
     * @return bool
     */
    public function assignPermission($roleId, $permId)
    {
        // Check if already exists
        if ($this->roleHasPermission($roleId, $permId)) {
            return true;
        }
        
        return $this->insert([
            'role_id_fk' => $roleId,
            'perm_id_fk' => $permId
        ]) !== false;
    }
    
    /**
     * Remove permission from role
     * 
     * @param int $roleId
     * @param int $permId
     * @return bool
     */
    public function removePermission($roleId, $permId)
    {
        return $this->where('role_id_fk', $roleId)
                    ->where('perm_id_fk', $permId)
                    ->delete();
    }
    
    /**
     * Update role permissions (remove old, add new)
     * 
     * @param int $roleId
     * @param array $permissionIds Array of permission IDs
     * @return bool
     */
    public function updateRolePermissions($roleId, $permissionIds = [])
    {
        // Start transaction
        $this->db->transStart();
        
        // Delete all existing permissions for this role
        $this->where('role_id_fk', $roleId)->delete();
        
        // Insert new permissions
        if (!empty($permissionIds)) {
            $data = [];
            foreach ($permissionIds as $permId) {
                $data[] = [
                    'role_id_fk' => $roleId,
                    'perm_id_fk' => $permId
                ];
            }
            
            $this->insertBatch($data);
        }
        
        // Complete transaction
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }
    
    /**
     * Get count of permissions for a role
     * 
     * @param int $roleId
     * @return int
     */
    public function countRolePermissions($roleId)
    {
        return $this->where('role_id_fk', $roleId)->countAllResults();
    }
    
    /**
     * Grant role access (check if role has specific permission)
     * 
     * @param int $role_id_fk
     * @param string $perm_code
     * @return array|bool
     */
    public function grant_role_access($role_id, $perm_code)
    {
        
        return $this->db->table('role_permission')
            ->select('*')
            ->join('permission', 'permission.perm_id = role_permission.perm_id_fk')
            ->where('role_permission.role_id_fk', $role_id)
            ->where('permission.perm_code', $perm_code)
            ->get()
            ->getRowArray();
    }
    
    /**
     * Get all permissions for a specific role
     * 
     * @param int $role_id
     * @return array|bool
     */
    public function get_permission_for_role($role_id)
    {
        try {
            return $this->db->table('role_permission')
                ->select('role_permission.*, permission.*')
                ->join('permission', 'permission.perm_id = role_permission.perm_id_fk', 'inner')
                ->where('role_permission.role_id_fk', $role_id)
                ->orderBy('role_permission.role_perm_id', 'ASC')
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error getting permissions for role: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user role permission by role and permission ID
     * 
     * @param int $role_id
     * @param int $perm_id
     * @return array|bool
     */
    public function get_user_role_permission($role_id, $perm_id)
    {
        try {
            $result = $this->where('role_id_fk', $role_id)
                          ->where('perm_id_fk', $perm_id)
                          ->first();
            
            return $result ?: false;
        } catch (\Exception $e) {
            log_message('error', 'Error getting user role permission: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if role has permission (simplified version)
     * 
     * @param int $role_id
     * @param string $perm_code
     * @return bool
     */
    public function hasPermission($role_id, $perm_code)
    {
        $result = $this->grant_role_access($role_id, $perm_code);
        return !empty($result);
    }
    
    /**
     * Get permission IDs for a role
     * 
     * @param int $role_id
     * @return array
     */
    public function getPermissionIdsForRole($role_id)
    {
        try {
            $result = $this->where('role_id_fk', $role_id)
                          ->findAll();
            
            return array_column($result, 'perm_id_fk');
        } catch (\Exception $e) {
            log_message('error', 'Error getting permission IDs for role: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Bulk assign permissions to role
     * 
     * @param int $role_id
     * @param array $permission_ids
     * @return bool
     */
    public function assignPermissionsToRole($role_id, array $permission_ids)
    {
        try {
            // Remove existing permissions for this role
            $this->delete_role_permission_by_role($role_id);
            
            // Add new permissions
            foreach ($permission_ids as $perm_id) {
                $data = [
                    'role_id_fk' => $role_id,
                    'perm_id_fk' => $perm_id,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->add_role_permission($data);
            }
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error assigning permissions to role: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get role IDs that have a specific permission
     * 
     * @param string $perm_code
     * @return array
     */
    public function getRolesWithPermission($perm_code)
    {
        try {
            $result = $this->db->table('role_permission')
                ->select('role_permission.role_id_fk')
                ->join('permission', 'permission.perm_id = role_permission.perm_id_fk')
                ->where('permission.perm_code', $perm_code)
                ->get()
                ->getResultArray();
            
            return array_column($result, 'role_id_fk');
        } catch (\Exception $e) {
            log_message('error', 'Error getting roles with permission: ' . $e->getMessage());
            return [];
        }
    }
}