<?php

namespace App\Models;

use CodeIgniter\Model;

class ModuleModel extends Model
{
    protected $table = 'modules';
    protected $primaryKey = 'module_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'module_name', 
        'module_icon', 
        'module_svg'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'module_name' => 'required|min_length[2]|max_length[100]'
    ];
    
    protected $validationMessages = [
        'module_name' => [
            'required' => 'Module name is required',
            'min_length' => 'Module name must be at least 2 characters long',
            'max_length' => 'Module name cannot exceed 100 characters',
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get module by module_id
     * 
     * @param int $module_id
     * @return array|null
     */
    public function get_module($module_id)
    {
        try {
            return $this->find($module_id);
        } catch (\Exception $e) {
            log_message('error', 'Error getting module: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get module by module_code
     * 
     * @param string $module_code
     * @return array|null
     */
    public function get_module_by_code($module_code)
    {
        try {
            return $this->where('module_code', $module_code)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error getting module by code: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all modules
     * 
     * @return array
     */
    public function get_all_modules()
    {
        return $this->orderBy('module_id', 'ASC')->findAll();
    }
    
    /**
     * Get active modules ordered by module_order
     * 
     * @return array
     */
    public function get_active_modules()
    {
        return $this->where('is_active', 1)
                   ->orderBy('module_order', 'ASC')
                   ->orderBy('module_name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get modules for sidebar navigation
     * 
     * @return array
     */
    public function get_navigation_modules()
    {
        return $this->select('module_id, module_name, module_code, module_icon, module_order')
                   ->where('is_active', 1)
                   ->orderBy('module_order', 'ASC')
                   ->orderBy('module_name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Add new module
     * 
     * @param array $params
     * @return int|bool Insert ID or false on failure
     */
    public function add_module($params)
    {
        try {
            // Set default values if not provided
            if (!isset($params['module_order'])) {
                $params['module_order'] = $this->getNextOrderNumber();
            }
            
            if (!isset($params['is_active'])) {
                $params['is_active'] = 1;
            }
            
            return $this->insert($params);
        } catch (\Exception $e) {
            log_message('error', 'Error adding module: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update module
     * 
     * @param int $module_id
     * @param array $params
     * @return bool
     */
    public function update_module($module_id, $params)
    {
        try {
            return $this->update($module_id, $params);
        } catch (\Exception $e) {
            log_message('error', 'Error updating module: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete module
     * 
     * @param int $module_id
     * @return bool
     */
    public function delete_module($module_id)
    {
        try {
            return $this->delete($module_id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting module: ' . $e->getMessage());
            return false;
        }
    }
    
}