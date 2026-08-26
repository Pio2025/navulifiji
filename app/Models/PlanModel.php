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
     * Discount applied when a paid plan is billed annually instead of monthly.
     */
    public const ANNUAL_DISCOUNT_PERCENT = 5.0;

    /**
     * Total annual cost for a plan after the annual billing discount.
     * Free plans (cost 0) and custom-quote plans (cost NULL) always return 0.
     */
    public function getAnnualCost(array $plan): float
    {
        if ($plan['plan_monthly_cost'] === null) {
            return 0.0;
        }

        $monthlyCost = (float) $plan['plan_monthly_cost'];

        if ($monthlyCost <= 0) {
            return 0.0;
        }

        return round($monthlyCost * 12 * (1 - self::ANNUAL_DISCOUNT_PERCENT / 100), 2);
    }

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