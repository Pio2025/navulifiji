<?php

namespace App\Libraries;

use App\Models\RolePermissionModel;
use App\Models\ModuleModel;

class Navigation
{
    protected $session;
    protected $rolePermissionModel;
    protected $moduleModel;
    
    public function __construct()
    {
        $this->session = session();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->moduleModel = new ModuleModel();
    }
    
    /**
     * Generate the complete navigation menu
     */
    public function generateMenu()
    {
        // Check if user is logged in
        if (!$this->session->get('userID')) {
            return '';
        }
        
        $role_id = $this->session->get('roleID');
        $permissions = $this->rolePermissionModel->get_permission_for_role($role_id);
        
        if (!$permissions) {
            return '<div class="alert alert-warning m-3">No permissions defined for your role.</div>';
        }
        
        $all_modules = $this->moduleModel->get_all_modules();
        $menuItems = [];
        
        foreach ($all_modules as $module) {
            // Check if module has accessible permissions
            $modulePermissions = array_filter($permissions, function($perm) use ($module) {
                return $perm['module_id_fk'] == $module['module_id'] && $perm['show_in_nav'] == 1;
            });
            
            if (empty($modulePermissions)) {
                continue;
            }
            
            // Build menu item
            $menuItems[] = $this->buildMenuItem($module, $modulePermissions);
        }
        
        return implode('', $menuItems);
    }
    
    /**
     * Build a single menu item with its submenus
     */
    private function buildMenuItem($module, $permissions)
    {
        $active = $this->isMenuActive($module['module_name']) ? 'here show' : '';
        $icon = !empty($module['module_icon']) ? $module['module_icon'] : '<i class="ki-duotone ki-abstract-28 fs-2"><span class="path1"/><span class="path2"/></i>';
        
        $html = '<!--begin:Menu item-->
    				<div data-kt-menu-trigger="click" class="menu-item menu-accordion '.$active.'">
    					<!--begin:Menu link-->
    					<span class="menu-link">
    						<span class="menu-icon">
    							'.$icon.'
    						</span>
    						<span class="menu-title">'.$module['module_name'].'</span>
    						<span class="menu-arrow"></span>
    					</span>
    					<!--end:Menu link-->';
        
        foreach ($permissions as $permission) {
            $html .= $this->buildSubMenuItem($permission);
        }
        
        $html .= '</div>
                <!--end:Menu item-->';
        
        return $html;
    }
    
    /**
     * Build a single submenu item
     */
    private function buildSubMenuItem($permission)
    {
        
        
        $sub_active = $this->isSubMenuActive($permission['perm_name']) ? 'active' : '';
        $url = base_url($permission['perm_controller'] ?? '');
                
        return '<!--begin:Menu sub-->
				<div class="menu-sub menu-sub-accordion">
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link '.$sub_active.'" href="'.$url.'">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">' . $permission['perm_name'] . '</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
				</div>
				<!--end:Menu sub-->';
    }
    
    /**
     * Check if main menu is active
     */
    private function isMenuActive($moduleName)
    {
        return $this->session->get('nav_menu') == $moduleName;
    }
    
    /**
     * Check if submenu is active
     */
    private function isSubMenuActive($permissionName)
    {
        return $this->session->get('sub_nav_menu') == $permissionName;
    }
    
    /**
     * Set menu session variables
     */
    public function setMenuSession($mainMenu = '', $subMenu = '')
    {
        if ($mainMenu) {
            $this->session->set('nav_menu', $mainMenu);
        }
        
        if ($subMenu) {
            $this->session->set('sub_nav_menu', $subMenu);
        }
    }
    
    /**
     * Clear menu session variables
     */
    public function clearMenuSession()
    {
        $this->session->remove('nav_menu');
        $this->session->remove('sub_nav_menu');
    }
}