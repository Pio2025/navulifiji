<?php

namespace App\Libraries;

use App\Models\PermissionModel;
use App\Models\RolePermissionModel;

/**
 * PermissionViewLibrary
 * 
 * Handles the display and checking of permissions for roles
 */
class PermissionViewLibrary
{
    protected $permissionModel;
    protected $rolePermissionModel;
    protected $rolePermissions = [];
    
    public function __construct()
    {
        $this->permissionModel = new PermissionModel();
        $this->rolePermissionModel = new RolePermissionModel();
    }
    
    /**
     * Get all permissions with role assignment status
     * 
     * @param int $roleId The role ID to check permissions for
     * @return array Array of permissions with 'has_permission' flag
     */
    public function getAllPermissionsWithStatus($roleId)
    {
        // Get all permissions
        $allPermissions = $this->permissionModel->findAll();
        
        // Get permissions assigned to this role
        $rolePermissions = $this->rolePermissionModel->getRolePermissions($roleId);
        
        // Create array of permission codes that role has
        $assignedPermCodes = array_column($rolePermissions, 'perm_code');
        
        // Add 'has_permission' flag to each permission
        $permissionsWithStatus = [];
        foreach ($allPermissions as $permission) {
            $permData = is_array($permission) ? $permission : (array)$permission;
            
            $permData['has_permission'] = in_array(
                $permData['perm_code'], 
                $assignedPermCodes
            );
            
            $permissionsWithStatus[] = $permData;
        }
        
        return $permissionsWithStatus;
    }
    
    /**
     * Render permissions as checkboxes in a grid layout (3 columns)
     * Updated: Allows editing of granted permissions
     * 
     * @param int $roleId The role ID
     * @param array $permissions Optional pre-fetched permissions
     * @param bool $canEdit Whether user can edit permissions
     * @return string HTML output
     */
    public function renderPermissionGrid($roleId, $permissions = null, $canEdit = true)
    {
        if ($permissions === null) {
            $permissions = $this->getAllPermissionsWithStatus($roleId);
        }
        
        if (empty($permissions)) {
            return '<div class="alert alert-info">No permissions available.</div>';
        }
        
        $html = '<div class="row g-5 mb-4">';
        
        foreach ($permissions as $index => $permission) {
            $permId = $permission['perm_id'];
            $permCode = $permission['perm_code'];
            $permName = $permission['perm_name'];
            $permDesc = $permission['perm_desc'] ?? '';
            $hasPermission = $permission['has_permission'];
            
            // Determine checkbox state - REMOVED disabled attribute
            $checked = $hasPermission ? 'checked' : '';
            $disabled = $canEdit ? '' : 'disabled'; // Only disable if user cannot edit
            $checkboxClass = $hasPermission ? 'form-check-input-success' : '';
            $badgeClass = $hasPermission ? 'badge-light-success' : 'badge-light-secondary';
            $borderClass = $hasPermission ? 'border-success' : '';
            
            // Start new row every 3 items
            if ($index > 0 && $index % 3 == 0) {
                $html .= '</div><div class="row g-5 mb-4">';
            }
            
            $html .= '
            <div class="col-md-4">
                <div class="card card-flush h-100 border ' . $borderClass . '">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="form-check form-check-custom form-check-solid me-3">
                                <input class="form-check-input ' . $checkboxClass . '" 
                                       type="checkbox" 
                                       name="permissions[]" 
                                       value="' . $permId . '" 
                                       id="perm_' . $permId . '" 
                                       ' . $checked . ' 
                                       ' . $disabled . ' />
                            </div>
                            <span class="badge ' . $badgeClass . ' fs-7 fw-bold">' . strtoupper($permCode) . '</span>
                        </div>
                        
                        <label class="form-check-label fw-bold text-gray-800 fs-6 mb-2 ' . ($canEdit ? 'cursor-pointer' : '') . '" for="perm_' . $permId . '">
                            ' . esc($permName) . '
                        </label>
                        
                        ' . ($permDesc ? '<p class="text-gray-600 fs-7 mb-0">' . esc($permDesc) . '</p>' : '') . '
                        
                        ' . ($hasPermission ? '
                        <div class="mt-3">
                            <span class="badge badge-success">
                                <i class="ki-duotone ki-check-circle fs-4 text-white me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Currently Granted
                            </span>
                        </div>
                        ' : '') . '
                    </div>
                </div>
            </div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render permissions as simple checkboxes in a grid (alternative style)
     * 
     * @param int $roleId The role ID
     * @param array $permissions Optional pre-fetched permissions
     * @param bool $canEdit Whether user can edit permissions
     * @return string HTML output
     */
    public function renderSimplePermissionGrid($roleId, $permissions = null, $canEdit = true)
    {
        if ($permissions === null) {
            $permissions = $this->getAllPermissionsWithStatus($roleId);
        }
        
        if (empty($permissions)) {
            return '<div class="alert alert-info">No permissions available.</div>';
        }
        
        $html = '<div class="row g-3">';
        
        foreach ($permissions as $index => $permission) {
            $permId = $permission['perm_id'];
            $permCode = $permission['perm_code'];
            $permName = $permission['perm_name'];
            $hasPermission = $permission['has_permission'];
            
            $checked = $hasPermission ? 'checked' : '';
            $disabled = $canEdit ? '' : 'disabled';
            $checkboxClass = $hasPermission ? 'form-check-input-success' : '';
            
            $html .= '
            <div class="col-md-4">
                <div class="form-check form-check-custom form-check-solid ' . ($hasPermission ? 'bg-light-success' : 'bg-light') . ' p-4 rounded">
                    <input class="form-check-input ' . $checkboxClass . '" 
                           type="checkbox" 
                           name="permissions[]" 
                           value="' . $permId . '" 
                           id="perm_simple_' . $permId . '" 
                           ' . $checked . ' 
                           ' . $disabled . ' />
                    <label class="form-check-label fw-semibold text-gray-800" for="perm_simple_' . $permId . '">
                        ' . esc($permName) . '
                        ' . ($hasPermission ? '<i class="ki-duotone ki-check-circle fs-4 text-success ms-2"><span class="path1"></span><span class="path2"></span></i>' : '') . '
                    </label>
                </div>
            </div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Check if role has specific permission
     * 
     * @param int $roleId
     * @param string $permCode Permission code
     * @return bool
     */
    public function roleHasPermission($roleId, $permCode)
    {
        $rolePermissions = $this->rolePermissionModel->getRolePermissions($roleId);
        $assignedPermCodes = array_column($rolePermissions, 'perm_code');
        
        return in_array($permCode, $assignedPermCodes);
    }
    
    /**
     * Get permissions grouped by module
     * 
     * @param int $roleId
     * @return array
     */
    public function getPermissionsByModule($roleId)
    {
        $permissions = $this->getAllPermissionsWithStatus($roleId);
        $grouped = [];
        
        foreach ($permissions as $permission) {
            $moduleId = $permission['module_id_fk'] ?? 'General';
            
            if (!isset($grouped[$moduleId])) {
                $grouped[$moduleId] = [];
            }
            
            $grouped[$moduleId][] = $permission;
        }
        
        return $grouped;
    }
    
    /**
     * Render permissions grouped by module
     * 
     * @param int $roleId
     * @param bool $canEdit Whether user can edit permissions
     * @return string HTML output
     */
    public function renderPermissionsByModule($roleId, $canEdit = true)
    {
        $groupedPermissions = $this->getPermissionsByModule($roleId);
        
        $html = '';
        
        foreach ($groupedPermissions as $moduleId => $permissions) {
            $html .= '
            <div class="mb-10">
                <h4 class="text-gray-800 fw-bold mb-5">
                    <i class="ki-duotone ki-abstract-26 fs-2 text-primary me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Module: ' . esc($moduleId) . '
                </h4>
                
                <div class="row g-5">';
            
            foreach ($permissions as $index => $permission) {
                $permId = $permission['perm_id'];
                $permName = $permission['perm_name'];
                $permDesc = $permission['perm_desc'] ?? '';
                $hasPermission = $permission['has_permission'];
                
                $checked = $hasPermission ? 'checked' : '';
                $disabled = $canEdit ? '' : 'disabled';
                $checkboxClass = $hasPermission ? 'form-check-input-success' : '';
                
                $html .= '
                <div class="col-md-4 mb-4">
                    <div class="form-check form-check-custom form-check-solid ' . ($hasPermission ? 'bg-light-success' : 'bg-light') . ' p-4 rounded">
                        <input class="form-check-input ' . $checkboxClass . '" 
                               type="checkbox" 
                               name="permissions[]" 
                               value="' . $permId . '" 
                               id="perm_cat_' . $permId . '" 
                               ' . $checked . ' 
                               ' . $disabled . ' />
                        <label class="form-check-label fw-semibold text-gray-800 d-block" for="perm_cat_' . $permId . '">
                            ' . esc($permName) . '
                            ' . ($hasPermission ? '<span class="badge badge-success badge-sm ms-2">Granted</span>' : '') . '
                        </label>
                        ' . ($permDesc ? '<span class="text-muted fs-8">' . esc($permDesc) . '</span>' : '') . '
                    </div>
                </div>';
            }
            
            $html .= '
                </div>
            </div>';
        }
        
        return $html;
    }
}
