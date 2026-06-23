<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LaunchNotificationModel;

class LaunchController extends BaseController
{
    protected $notificationModel;
    
    public function __construct()
    {
        $this->notificationModel = new LaunchNotificationModel();
    }
    
    /**
     * Display the coming soon page
     */
    public function index()
    {
        return view('launch/coming_soon');
    }
    
    /**
     * Handle email subscription via AJAX
     */
    public function subscribe()
    {
        // Debug logging
        log_message('info', '=== Subscribe Method Called ===');
        log_message('info', 'Request Method: ' . $this->request->getMethod());
        
        // ✅ FIX: Convert to lowercase for comparison
        $method = strtolower($this->request->getMethod());
        
        if ($method !== 'post') {
            log_message('error', 'Invalid method: ' . $this->request->getMethod());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ])->setStatusCode(400);
        }
        
        // Get email from request
        $email = $this->request->getPost('email');
        
        // If POST is empty, try getting from raw input
        if (empty($email)) {
            $rawInput = $this->request->getBody();
            parse_str($rawInput, $postData);
            $email = $postData['email'] ?? null;
        }
        
        log_message('info', 'Extracted email: ' . ($email ?? 'EMPTY'));
        
        // Validate email
        if (empty($email)) {
            log_message('error', 'Email is empty');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email address is required'
            ])->setStatusCode(400);
        }
        
        // Trim email
        $email = trim($email);
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Invalid email format: ' . $email);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please enter a valid email address'
            ])->setStatusCode(400);
        }
        
        // Check if email already exists
        if ($this->notificationModel->emailExists($email)) {
            log_message('info', 'Email already exists: ' . $email);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This email is already registered. You will be notified when we launch!',
                'already_registered' => true
            ])->setStatusCode(409);
        }
        
        // Get additional information
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString();
        
        log_message('info', 'Attempting to register: ' . $email . ' | IP: ' . $ipAddress);
        
        // Register subscriber
        try {
            $insertId = $this->notificationModel->registerSubscriber($email, $ipAddress, $userAgent);
            
            if ($insertId) {
                log_message('info', "✓ Successfully registered: {$email} (ID: {$insertId})");
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Thank you! We\'ll notify you when Navuli Fiji launches.',
                    'email' => $email
                ])->setStatusCode(201);
            } else {
                throw new \Exception('Insert failed - returned false/null');
            }
        } catch (\Exception $e) {
            log_message('error', 'Database error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Get total subscribers count
     */
    public function getSubscriberCount()
    {
        $count = $this->notificationModel->getTotalSubscribers();
        
        return $this->response->setJSON([
            'success' => true,
            'count' => $count
        ]);
    }
    
    /**
     * Export subscribers list (for admin use)
     */
    public function exportSubscribers()
    {
        // TODO: Add authentication check here
        
        $subscribers = $this->notificationModel
            ->select('email, date, status')
            ->orderBy('date', 'DESC')
            ->findAll();
        
        if (empty($subscribers)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No subscribers found'
            ]);
        }
        
        // Set headers for CSV download
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="launch_subscribers_' . date('Y-m-d') . '.csv"');
        
        // Create CSV content
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['Email', 'Registration Date', 'Status']);
        
        // Add data
        foreach ($subscribers as $subscriber) {
            fputcsv($output, [
                $subscriber['email'],
                $subscriber['date'],
                $subscriber['status']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Test endpoint
     */
    public function test()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'LaunchController is working!',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}