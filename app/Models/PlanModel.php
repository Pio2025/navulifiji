<?php
namespace App\Models;

use CodeIgniter\Model;

class PlanModel extends Model
{
    protected $table = 'plans';
    protected $primaryKey = 'plan_id';
    protected $allowedFields = ['plan_name','plan_desc','plan_monthly_cost','plan_monthly_cost_web_n_mobile'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Discount applied when a paid plan is billed annually instead of monthly.
     */
    public const ANNUAL_DISCOUNT_PERCENT = 5.0;

    /**
     * Monthly cost for a plan under the given package type. Custom-quote
     * plans (NULL in both cost columns) return null regardless of package.
     */
    public function getMonthlyCost(array $plan, string $packageType = 'web'): ?float
    {
        $costField = $packageType === 'web_mobile' ? 'plan_monthly_cost_web_n_mobile' : 'plan_monthly_cost';
        $cost = $plan[$costField] ?? null;

        return $cost === null ? null : (float) $cost;
    }

    /**
     * Total annual cost for a plan after the annual billing discount.
     * Free plans (cost 0) and custom-quote plans (cost NULL) always return 0.
     */
    public function getAnnualCost(array $plan, string $packageType = 'web'): float
    {
        $monthlyCost = $this->getMonthlyCost($plan, $packageType);

        if ($monthlyCost === null || $monthlyCost <= 0) {
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