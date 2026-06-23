<?php

namespace App\Models;

use CodeIgniter\Model;

class LaunchNotificationModel extends Model
{
    protected $table = 'launch_notification';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'email',
        'date',
        'ip_address',
        'user_agent',
        'status'
    ];
    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date';
    protected $updatedField = '';
    protected $deletedField = '';
    
    // Validation
    protected $validationRules = [
        'email' => [
            'rules' => 'required|valid_email|max_length[255]',
            'label' => 'Email',
            'errors' => [
                'required' => 'Email is required',
                'valid_email' => 'Please enter a valid email address',
                'max_length' => 'Email must not exceed 255 characters'
            ]
        ]
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
    
    /**
     * Check if email already exists
     *
     * @param string $email
     * @return bool
     */
    public function emailExists($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }
    
    /**
     * Get subscriber by email
     *
     * @param string $email
     * @return array|null
     */
    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Get all pending notifications
     *
     * @return array
     */
    public function getPendingNotifications()
    {
        return $this->where('status', 'pending')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get total subscribers count
     *
     * @return int
     */
    public function getTotalSubscribers()
    {
        return $this->countAllResults();
    }
    
    /**
     * Mark notification as sent
     *
     * @param int $id
     * @return bool
     */
    public function markAsNotified($id)
    {
        return $this->update($id, ['status' => 'notified']);
    }
    
    /**
     * Register new subscriber with additional info
     *
     * @param string $email
     * @param string|null $ipAddress
     * @param string|null $userAgent
     * @return int|bool Insert ID or false
     */
    public function registerSubscriber($email, $ipAddress = null, $userAgent = null)
    {
        $data = [
            'email' => $email,
            'date' => date('Y-m-d H:i:s'),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'status' => 'pending'
        ];
        
        return $this->insert($data);
    }
}