<?php
namespace App\Models;

use CodeIgniter\Model;

class SchoolModel extends Model
{
    protected $table = 'school';
    protected $primaryKey = 'sch_id';
    protected $allowedFields = ['sch_id','sch_cat_name','district_name','district_id_fk','sch_cat_id_fk','sch_name', 'sch_address', 'sch_phone', 'sch_email', 'sch_password', 'sch_x_coord', 'sch_y_coord', 'sch_motto', 'sch_logo', 'sch_primary_color', 'sch_secondary_color', 'sch_created_at', 'sch_activation_key', 'sch_activation_time', 'sch_status'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Get School by sch_id
     */
    public function getSchool($sch_id)
    {
        return $this->find($sch_id);
    }

    /**
     * Get all Schools
     */
    public function getAllSchool()
    {
        return $this->orderBy('sch_id', 'ASC')
                   ->findAll();
    }

    /**
     * Find a school by activation key
     */
    public function findSchoolByActivationKey($activationKey)
    {
        return $this->where('sch_activation_key', $activationKey)->first();
    }

    /**
     * Add new School
     */
    public function addSchool($data)
    {
        return $this->insert($data);
    }

    /**
     * Update School
     */
    public function updateSchool($sch_id, $data)
    {
        return $this->update($sch_id, $data);
    }

    /**
     * Delete School
     */
    public function deleteSchool($sch_id)
    {
        return $this->delete($sch_id);
    }

    /**
     * Get Full School Details with Joins
     * Joins with subscription, plans, district, and province tables
     */
    public function findFullSchoolDetail($sch_id = null)
    {
        // Select all fields from school table and related tables
        $this->select('
            school.*,
            sch_category.*,
            subscription.subscription_id,
            subscription.subscription_start_date,
            subscription.subscription_end_date,
            subscription.subscription_time,
            subscription.subscription_term,
            subscription.subscription_status,
            plans.plan_id,
            plans.plan_name,
            plans.plan_desc,
            plans.plan_monthly_cost,
            district.district_id,
            district.district_name,
            district.province_id_fk,
            province.province_id,
            province.province_name
        ');
        
        
        // Join with subscription table (LEFT JOIN to include schools without subscription)
        $this->join('sch_category', 'sch_category.sch_cat_id = school.sch_cat_id_fk', 'left');
        
        // Join with subscription table (LEFT JOIN to include schools without subscription)
        $this->join('subscription', 'subscription.sch_id_fk = school.sch_id', 'left');
        
        // Join with plans table (LEFT JOIN to include schools without plan/subscription)
        $this->join('plans', 'plans.plan_id = subscription.plan_id_fk', 'left');
        
        // Join with district table (LEFT JOIN to include schools without district)
        $this->join('district', 'district.district_id = school.district_id_fk', 'left');
        
        // Join with province table through district
        $this->join('province', 'province.province_id = district.province_id_fk', 'left');
        
        // If specific school ID is provided
        if ($sch_id !== null) {
            $this->where('school.sch_id', $sch_id);
            return $this->first();
        }
        
        // Order by school ID
        $this->orderBy('school.sch_id', 'ASC');
        
        // Return all schools with full details
        return $this->findAll();
    }

    /**
     * Alternative: Get Full School Details with Active Subscription Only
     */
    public function findFullSchoolDetailWithActiveSubscription($sch_id = null)
    {
        $this->select('
            school.*,
            subscription.subscription_id,
            subscription.subscription_start_date,
            subscription.subscription_end_date,
            subscription.subscription_time,
            subscription.subscription_term,
            subscription.subscription_status,
            plans.plan_id,
            plans.plan_name,
            plans.plan_desc,
            plans.plan_monthly_cost,
            district.district_id,
            district.district_name,
            district.province_id_fk,
            province.province_id,
            province.province_name
        ');
        
        // INNER JOIN for subscription (only schools with subscription)
        $this->join('subscription', 'subscription.sch_id_fk = school.sch_id');
        
        // Filter for active subscriptions only
        $this->where('subscription.subscription_status', 'Active');
        
        // Join with other tables
        $this->join('plans', 'plans.plan_id = subscription.plan_id_fk');
        $this->join('district', 'district.district_id = school.district_id_fk', 'left');
        $this->join('province', 'province.province_id = district.province_id_fk', 'left');
        
        if ($sch_id !== null) {
            $this->where('school.sch_id', $sch_id);
            return $this->first();
        }
        
        $this->orderBy('school.sch_id', 'ASC');
        return $this->findAll();
    }

    /**
     * Get Schools by District
     */
    public function findSchoolsByDistrict($district_id)
    {
        return $this->where('district_id_fk', $district_id)
                   ->orderBy('sch_name', 'ASC')
                   ->findAll();
    }

    /**
     * Get Schools by Province
     */
    public function findSchoolsByProvince($province_id)
    {
        $this->select('school.*, district.district_name, province.province_name');
        $this->join('district', 'district.district_id = school.district_id_fk', 'left');
        $this->join('province', 'province.province_id = district.province_id_fk', 'left');
        $this->where('province.province_id', $province_id);
        $this->orderBy('school.sch_name', 'ASC');
        return $this->findAll();
    }

    /**
     * Get School Subscription Details
     */
    public function getSchoolSubscription($sch_id)
    {
        $this->select('
            subscription.*,
            plans.*
        ');
        $this->join('subscription', 'subscription.sch_id_fk = school.sch_id', 'left');
        $this->join('plans', 'plans.plan_id = subscription.plan_id_fk', 'left');
        $this->where('school.sch_id', $sch_id);
        return $this->first();
    }
    
    /**
     * Get comprehensive school statistics
     *
     * @param int $schoolId
     * @return array
     */
    public function getSchoolStatistics($schoolId)
    {
        $db = \Config\Database::connect();
        
        // Get all users for this school through admission with their roles
        $query = $db->query("
            SELECT 
                u.user_id,
                u.user_status,
                r.role_id,
                r.role_name,
                r.role_cat_id_fk
            FROM admission a
            INNER JOIN users u ON u.user_id = a.user_id_fk
            LEFT JOIN user_role ur ON ur.user_id_fk = u.user_id
            LEFT JOIN role r ON r.role_id = ur.role_id_fk
            WHERE a.sch_id_fk = ?
            AND a.admission_status = 'Active'
        ", [$schoolId]);
        
        $users = $query->getResultArray();
        
        // Debug log
        log_message('debug', 'School Statistics - Total rows fetched: ' . count($users));
        log_message('debug', 'Sample data: ' . json_encode(array_slice($users, 0, 3)));
        
        // Initialize counters
        $stats = [
            'total_users' => 0,
            'active_users' => 0,
            'inactive_users' => 0,
            'parents' => 0,        // role_cat_id = 6
            'students' => 0,       // role_cat_id = 4
            'teachers' => 0,       // role_cat_id = 3
            'support_staff' => 0,  // role_cat_id = 5
            'other_roles' => 0,
            'users_by_role' => []
        ];
        
        // Track unique users and their role categories
        $uniqueUsers = [];
        $userRoleCategories = []; // Track which role categories each user has
        
        foreach ($users as $user) {
            $userId = $user['user_id'];
            $roleCatId = $user['role_cat_id_fk'];
            
            // Count unique users
            if (!isset($uniqueUsers[$userId])) {
                $uniqueUsers[$userId] = [
                    'user_status' => $user['user_status'],
                    'role_categories' => []
                ];
                $stats['total_users']++;
                
                // Count active/inactive
                if ($user['user_status'] === 'Active') {
                    $stats['active_users']++;
                } else {
                    $stats['inactive_users']++;
                }
            }
            
            // Track role categories for this user (avoid double counting)
            if (!empty($roleCatId) && !in_array($roleCatId, $uniqueUsers[$userId]['role_categories'])) {
                $uniqueUsers[$userId]['role_categories'][] = $roleCatId;
                
                // Count by role category (each user counted once per category)
                switch (intval($roleCatId)) {
                    case 6: // Parents
                        $stats['parents']++;
                        log_message('debug', "User {$userId} counted as Parent");
                        break;
                    case 4: // Students
                        $stats['students']++;
                        log_message('debug', "User {$userId} counted as Student");
                        break;
                    case 3: // Teachers
                        $stats['teachers']++;
                        log_message('debug', "User {$userId} counted as Teacher");
                        break;
                    case 5: // Support Staff
                        $stats['support_staff']++;
                        log_message('debug', "User {$userId} counted as Support Staff");
                        break;
                    default:
                        $stats['other_roles']++;
                        log_message('debug', "User {$userId} counted as Other (role_cat_id: {$roleCatId})");
                }
            }
        }
        
        // Calculate percentages (avoid division by zero)
        $totalUsers = $stats['total_users'] > 0 ? $stats['total_users'] : 1;
        
        $stats['percentages'] = [
            'active' => round(($stats['active_users'] / $totalUsers) * 100, 1),
            'inactive' => round(($stats['inactive_users'] / $totalUsers) * 100, 1),
            'parents' => round(($stats['parents'] / $totalUsers) * 100, 1),
            'students' => round(($stats['students'] / $totalUsers) * 100, 1),
            'teachers' => round(($stats['teachers'] / $totalUsers) * 100, 1),
            'support_staff' => round(($stats['support_staff'] / $totalUsers) * 100, 1),
        ];
        
        // Final debug log
        log_message('info', 'School Statistics Final: ' . json_encode($stats));
        
        return $stats;
    }
}