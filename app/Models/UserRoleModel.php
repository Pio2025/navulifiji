<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table = 'user_role';
    protected $primaryKey = 'user_role_id';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = ['role_id_fk', 'user_id_fk', 'user_role_status', 'created_date', 'updated_date'];
    
    protected $useTimestamps = false;
    
    // Datatables properties
    protected $columnOrder = [null, 'user_role_id', 'role_id_fk', 'user_id_fk'];
    protected $columnSearch = ['user_role_id', 'role_id_fk', 'user_id_fk'];
    protected $order = ['user_role_id' => 'asc'];
    
    /**
     * Get user_role by user_role_id
     */
    public function get_user_role($user_role_id)
    {
        return $this->find($user_role_id);
    }
    
    /**
     * Get user_role by user_id_fk with role details
     */
    public function findActiveUserRole($user_id_fk)
    {
        return $this->select('
                user_role.*,
                role.role_id,
                role.role_name,
                role.role_desc,
                role.role_rank,
                role.role_cat_id_fk,
                role_category.role_cat_id,
                role_category.role_cat_name
            ')
            ->join('role',          'role.role_id              = user_role.role_id_fk',         'inner')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',          'inner')
            ->where('user_role.user_id_fk',     $user_id_fk)
            ->where('user_role.user_role_status', 'Active')
            ->first();
    }
    
    /**
     * Get user_role by user_id_fk with role details (returns query builder)
     */
    public function getUserRoleAll($user_id_fk)
    {
        return $this->select('user_role.*, role.*')
                    ->join('role', 'role.role_id = user_role.role_id_fk', 'inner')
                    ->where('user_role.user_id_fk', $user_id_fk);
    }
    
    /**
     * Get all user_role
     */
    public function get_all_user_role()
    {
        return $this->orderBy('user_role_id', 'DESC')
                    ->findAll();
    }
    
    /**
     * Add new user_role
     */
    public function add_user_role($params)
    {
        $this->insert($params);
        return $this->getInsertID();
    }
    
    /**
     * Update user_role
     */
    public function update_user_role($user_role_id, $params)
    {
        return $this->update($user_role_id, $params);
    }
    
    /**
     * Delete user_role
     */
    public function delete_user_role($user_role_id)
    {
        return $this->delete($user_role_id);
    }
    
    // =============================
    // Datatables Methods
    // =============================
    
    /**
     * Fetch members data from the database
     * @param array $postData filter data based on the posted parameters
     */
    public function getRows($postData)
    {
        $this->_get_datatables_query($postData);
        
        if ($postData['length'] != -1) {
            $this->limit($postData['length'], $postData['start']);
        }
        
        return $this->get()->getResultArray();
    }
    
    /**
     * Count all records
     */
    public function countAll()
    {
        $builder = $this->builder();
        return $builder->countAll();
    }
    
    /**
     * Count records based on the filter params
     * @param array $postData filter data based on the posted parameters
     */
    public function countFiltered($postData)
    {
        $this->_get_datatables_query($postData);
        return $this->countAllResults();
    }
    
    /**
     * Perform the SQL queries needed for server-side processing
     * @param array $postData filter data based on the posted parameters
     */
    private function _get_datatables_query($postData)
    {
        $builder = $this->builder();
        
        $i = 0;
        // Loop searchable columns
        foreach ($this->columnSearch as $item) {
            // If datatable sends POST for search
            if (!empty($postData['search']['value'])) {
                // First loop
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $postData['search']['value']);
                } else {
                    $builder->orLike($item, $postData['search']['value']);
                }
                
                // Last loop
                if (count($this->columnSearch) - 1 == $i) {
                    $builder->groupEnd();
                }
            }
            $i++;
        }
        
        // Order by
        if (isset($postData['order'])) {
            $builder->orderBy(
                $this->columnOrder[$postData['order']['0']['column']], 
                $postData['order']['0']['dir']
            );
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
    }
    
    /**
     * Alternative simplified datatables method for CI4
     * This might be easier to work with
     */
    public function getDatatableData($params = [])
    {
        $builder = $this->builder();
        
        // Search
        if (!empty($params['search'])) {
            $builder->groupStart();
            foreach ($this->columnSearch as $column) {
                $builder->orLike($column, $params['search']);
            }
            $builder->groupEnd();
        }
        
        // Order
        if (!empty($params['order_column']) && !empty($params['order_dir'])) {
            $builder->orderBy($params['order_column'], $params['order_dir']);
        } else {
            foreach ($this->order as $column => $direction) {
                $builder->orderBy($column, $direction);
            }
        }
        
        // Pagination
        if (isset($params['start']) && isset($params['length']) && $params['length'] != -1) {
            $builder->limit($params['length'], $params['start']);
        }
        
        return $builder->get()->getResultArray();
    }
}