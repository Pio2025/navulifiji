<?php

namespace App\Services;

use CodeIgniter\Email\Email;
use Config\Services;

class EmailService
{
    protected $email;
    protected $parser;

    public function __construct()
    {
        $this->email = Services::email();
        $this->parser = Services::parser();
    }

    /**
     * Send welcome email to new administrator
     */
    public function sendWelcomeEmail($userData, $schoolName = 'Navuli Fiji')
    {
        // Prepare email data
        $data = [
            'userName'    => $userData['fname'] . ' ' . $userData['lname'],
            'userEmail'   => $userData['email'],
            'password'    => $userData['plain_password'],
            'schoolName'  => $schoolName,
            'loginUrl'    => site_url('school/login'),
            'currentYear' => date('Y')
        ];

        try {
            // Render the HTML template
            $htmlMessage = $this->parser->setData($data)->render('email/email_template');

            // Configure email
            $this->email->setTo($userData['email']);
            $this->email->setSubject("Welcome to {$schoolName} - Administrator Account Created");
            $this->email->setMessage($htmlMessage);
            
            // Send email
            if ($this->email->send()) {
                log_message('info', "Welcome email sent successfully to: {$userData['email']}");
                return [
                    'status' => true,
                    'message' => 'Email sent successfully'
                ];
            } else {
                $debug = $this->email->printDebugger(['headers']);
                log_message('error', "Failed to send welcome email to {$userData['email']}: {$debug}");
                return [
                    'status' => false,
                    'message' => 'Failed to send email: ' . $debug
                ];
            }
        } catch (\Exception $e) {
            log_message('error', "Email sending exception to {$userData['email']}: " . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Email exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Test email configuration
     */
    public function testEmail($toEmail = null)
    {
        $testEmail = $toEmail ?? 'test@example.com';
        
        $data = [
            'userName'    => 'Test User',
            'userEmail'   => $testEmail,
            'password'    => 'test123',
            'schoolName'  => 'Navuli Fiji',
            'loginUrl'    => site_url('school/login'),
            'currentYear' => date('Y')
        ];

        $htmlMessage = $this->parser->setData($data)->render('email/email_template');

        $this->email->setTo($testEmail);
        $this->email->setSubject('Test Email - Navuli Fiji');
        $this->email->setMessage($htmlMessage);
        
        if ($this->email->send()) {
            return "Test email sent successfully to {$testEmail}";
        } else {
            return "Test email failed: " . $this->email->printDebugger(['headers']);
        }
    }
}