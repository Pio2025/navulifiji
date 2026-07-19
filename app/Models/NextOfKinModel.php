<?php
namespace App\Models;
use CodeIgniter\Model;

class NextOfKinModel extends Model
{
    // Database table - FIXED: Changed from 'users' to 'next_of_kin'
    protected $table = 'next_of_kin';
    
    // Primary key - FIXED: Should be next_of_kin_id (or your actual PK)
    protected $primaryKey = 'next_of_kin_id';
    
    // Allowed fields (for security)
    protected $allowedFields = [
        'user_id_fk',
        'linked_user_id_fk',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_address',
        'next_of_kin_phone',
        'next_of_kin_email',
        'is_primary_contact',
        'is_emergency_contact',
        'authorized_pickup',
        'created_date',
        'updated_date'
    ];
    
    // Dates configuration
    protected $useTimestamps = false;
    protected $returnType = 'array';
    
    /**
     * Add new next of kin record
     */
    public function addNextOfKin($data)
    {
        return $this->insert($data);
    }
    
    /**
     * Update next of kin record
     */
    public function updateNextOfKin($id, $data)
    {
        return $this->update($id, $data);
    }
    
    /**
     * Delete next of kin record
     */
    public function deleteNextOfKin($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting next of kin: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get next of kin by user ID
     * Returns all next of kin records for a specific user
     * 
     * @param int $userId The user ID
     * @return array Array of next of kin records
     */
    public function getByUserId($userId)
    {
        return $this->where('user_id_fk', $userId)->findAll();
    }
    
    /**
     * Get primary next of kin by user ID
     * Returns only the primary contact
     * 
     * @param int $userId The user ID
     * @return array|null Single next of kin record or null
     */
    public function getPrimaryByUserId($userId)
    {
        return $this->where('user_id_fk', $userId)
                    ->where('is_primary_contact', 1)
                    ->first();
    }
    
    /**
     * Get all emergency contacts by user ID
     * Returns all contacts marked as emergency contacts
     * 
     * @param int $userId The user ID
     * @return array Array of emergency contact records
     */
    public function getEmergencyContactsByUserId($userId)
    {
        return $this->where('user_id_fk', $userId)
                    ->where('is_emergency_contact', 1)
                    ->findAll();
    }
    
    /**
     * Get next of kin with user details
     * Joins with users table to get student information
     * 
     * @param int $userId The user ID
     * @return array Array of next of kin with user details
     */
    public function getWithUserDetails($userId)
    {
        return $this->select('next_of_kin.*, users.fname, users.lname, users.email as student_email')
                    ->join('users', 'users.user_id = next_of_kin.user_id_fk', 'left')
                    ->where('next_of_kin.user_id_fk', $userId)
                    ->findAll();
    }
    
    /**
     * Check if user has next of kin
     * 
     * @param int $userId The user ID
     * @return bool True if has next of kin, false otherwise
     */
    public function hasNextOfKin($userId)
    {
        return $this->where('user_id_fk', $userId)->countAllResults() > 0;
    }
    
    /**
     * Set as primary contact
     * Unsets other primary contacts for this user and sets the specified one as primary
     * 
     * @param int $nextOfKinId The next of kin ID to set as primary
     * @param int $userId The user ID
     * @return bool Success status
     */
    public function setPrimaryContact($nextOfKinId, $userId)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Unset all primary contacts for this user
            $this->where('user_id_fk', $userId)
                 ->set(['is_primary_contact' => 0])
                 ->update();
            
            // Set the specified contact as primary
            $this->update($nextOfKinId, ['is_primary_contact' => 1]);
            
            $db->transComplete();
            
            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error setting primary contact: ' . $e->getMessage());
            return false;
        }
    }
}