<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscription';
    protected $primaryKey = 'subscription_id';
    protected $allowedFields = [
        'subscription_start_date', 
        'subscription_end_date', 
        'subscription_time', 
        'subscription_term', 
        'sch_id_fk', 
        'plan_id_fk',
        'subscription_status'
    ];
    protected $returnType = 'array';

    /**
     * Get Subscription by subscription_id
     */
    public function getSubscription($subscription_id)
    {
        return $this->find($subscription_id);
    }


    /**
     * Get all Subscriptions
     */
    public function getAllSubscription()
    {
        return $this->orderBy('subscription_id', 'ASC')
                   ->findAll();
    }

    public function getActiveSubscriptionBySchool($school_id)
    {
        return $this->where('sch_id_fk', $school_id)
            ->where('subscription_status', 'Active')
            ->first();
    }

    /**
     * Add new Subscription
     */
    public function addSubscription($data)
    {
        return $this->insert($data);
    }

    /**
     * Update Subscription
     */
    public function updateSubscription($subscription_id, $data)
    {
        return $this->update($subscription_id, $data);
    }

    /**
     * Delete Subscription
     */
    public function deleteSubscription($subscription_id)
    {
        return $this->delete($subscription_id);
    }
    
    /**
     * Check if school has active subscription with plan details
     * 
     * @param int $school_id School ID
     * @return array|null Returns subscription with plan details or null
     */
    public function hasActiveSubscription($school_id)
    {
        return $this->select('
                subscription.subscription_id,
                subscription.sch_id_fk,
                subscription.plan_id_fk,
                subscription.subscription_term,
                subscription.subscription_start_date,
                subscription.subscription_end_date,
                subscription.subscription_status,
                subscription.payment_mode,
                plans.plan_id,
                plans.plan_name,
                plans.plan_desc,
                plans.plan_monthly_cost
            ')
            ->join('plans', 'plans.plan_id = subscription.plan_id_fk', 'left')
            ->where('subscription.sch_id_fk', $school_id)
            ->whereIn('subscription.subscription_status', ['Active', 'Pending Payment'])
            ->orderBy('subscription.subscription_id', 'DESC')
            ->first();
    }

}