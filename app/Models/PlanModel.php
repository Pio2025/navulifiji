<?php
namespace App\Models;

use CodeIgniter\Model;

class PlanModel extends Model
{
    protected $table = 'plans';
    protected $primaryKey = 'plan_id';
    protected $allowedFields = ['plan_name','plan_desc','plan_monthly_cost'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Get Plan by sch_id
     */
    public function getPlan($plan_id)
    {
        return $this->find($plan_id);
    }

    /**
     * Get all Plans
     */
    public function getAllPlan()
    {
        return $this->orderBy('plan_id', 'ASC')
                   ->findAll();
    }
    

    /**
     * Add new Plan
     */
    public function addPlan($data)
    {
        return $this->insert($data);
    }

    /**
     * Update Plan
     */
    public function updatePlan($plan_id, $data)
    {
        return $this->update($plan_id, $data);
    }

    /**
     * Delete Plan
     */
    public function deletePlan($plan_id)
    {
        return $this->delete($plan_id);
    }

    
}