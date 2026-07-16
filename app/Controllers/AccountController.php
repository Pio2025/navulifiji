<?php

namespace App\Controllers;

use CodeIgniter\Email\Email;
use Config\Auth;
use Config\Services; // Add this line
use CodeIgniter\HTTP\Files\UploadedFile;

class AccountController extends BaseController
{
    protected $validation;
    protected $session;
    protected $email;
    protected $helpers = ['form', 'url']; // Add form helper

    public function __construct()
    {
        helper('form,url'); // Load URL helper if you use base_url() in views
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();
    }

    public function index(): string
    {
        return $this->subscribe();
    }

    public function testEmail(){
        $email = \Config\Services::email();
    
        $emailContent = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Test Email</title>
                </head>
                <body>
                    <h1>Hi There,</h1>
                    <p>This is a test email from CodeIgniter.</p>
                    <p>Best Regards,<br>Navuli Team</p>
                </body>
                </html>';

        $email->setTo('piobaleicoqe@yahoo.com');
        $email->setSubject('Test Email');
        //$email->setMessage(view('emails/welcome'));
        $email->setMessage($emailContent);
        
        if ($email->send()) {
            echo 'Email sent successfully';
        } else {
            echo '<pre>';
            print_r($email->printDebugger(['headers', 'subject', 'body']));
            echo '</pre>';
        }
    }

    function test(){
        $data['_view'] = 'web/feedback';
        return view('web/main', $data);
    }
    
    public function progress(){
        $data['_view'] = 'web/school/subscription_progress';
        return view('web/layouts/main_2', $data);
    }
    
    public function activate($code){
        $data = array();
        
        $success = '';
        $error = '';
        
        //authenticate code
        $fetchCode = $this->userModel->getActivationCode($code);
        
        if($fetchCode){
            $userID = $fetchCode['user_id'];
            $userStatus = $fetchCode['user_status'];
            if($userStatus == 'Active'){
                $error .= 'Your user accout is already been activated.';
            }else{
                $userData = [
                    'password_reset_code' => '',
                    'user_status'         => 'Active',
                    'email_verified'      => 1,
                ];
                $update = $this->userModel->updateUser($userID,$userData);
                if($update){
                    $success .= 'Successfully activated user account.';
                    
                    //Add user log
                    $userLogData = [
                        'user_id_fk' => $userID,
                        'ip_aadress' => $this->ipAddress,
                        'user_agent' => $this->userAgent->getAgentString(),
                        'user_device' => $this->deviceInfo['device_type'],
                        'log_title' => 'Activate User Account',
                        'log_desc' => 'Successfully verifyed user email address and user account activated.',
                        'log_date' => date('Y-m-d'),
                        'log_time' => time(),
                        'log_icon' => '<i class="ki-duotone ki-user-tick"> <span class="path1"></span> <span class="path2"></span> <span class="path3"></span></i>',
                        'log_theme' => 'info'
                    ];
                    
                    $addUserLog = $this->userLogModel->addUserLog($userLogData);
                    
                    if($addUserLog){
                        $success .= ' User activity successfully logged.';
                    }else{
                        $error .= 'Fail to log user activity.';
                    }
                }else{
                    $error .= ' Fail to activate user account. You may try login to your account and get activation code.';
                }
            }
        }else{
            $error .= 'The activation code is not valid. Please <a href="mailto:support@navulifiji.com">contact</a> the Navuli admin to get your user account activated.';    
        }
        
        $data['success'] = $success;
        $data['error'] = $error;
        
        return view('web/school/activate', $data);
    }
    
    public function subscribe() {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $data = [];
        
        $success = '';
        $error = '';
        
        // Load necessary data
        $data = [
            'district' => $this->districtModel->getAllDistrict(),
            'province' => $this->provinceModel->getAllProvince(),
            'department' => $this->departmentModel->getAllDepartment(),
            'feedback_title' => 'Account Subscription!',
            '_view' => 'web/school/subscribe'
        ];
        
        // Check if form is submitted
        if (!empty($this->request->getPost())) {
            // Get the input values
            $province = $this->request->getPost('province');
            $province2 = $this->request->getPost('province2');
            
            // Store all POST data for repopulation
            $data['old'] = $this->request->getPost();
            
            // Define validation rules
            $validationRules = [
                'account_type' => 'required|in_list[1,2,3]',
                'sch_category' => 'required|in_list[1,2,3,4]',
                'account_name' => 'required|min_length[3]|max_length[100]',
                'province' => 'required',
                'province2' => 'required',
                'gender' => 'required',
                'dob' => 'required',
                'phone' => 'required|min_length[7]|max_length[7]',
                'address' => 'required|min_length[5]|max_length[255]',
                'fname' => 'required|min_length[3]|max_length[100]',
                'lname' => 'required|min_length[3]|max_length[100]',
                'my_email' => 'required|valid_email|is_unique[users.email]',
                'my_phone' => 'required|min_length[7]|max_length[7]',
                'my_address' => 'required|min_length[5]|max_length[255]',
                'password' => 'required|min_length[8]|max_length[32]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]|matches[re-type-password]',
                're-type-password' => 'required',
                'motto' => 'required|min_length[3]|max_length[500]',
            ];
            
            // Add district validation only if province is selected
            if (!empty($province)) {
                $validationRules['district'] = 'required';
            }
            if (!empty($province2)) {
                $validationRules['district2'] = 'required'; // Make sure this matches your form field name
            }
            
            // Custom error messages (keep your existing ones)
            $validationMessages = [
                'account_type' => [
                    'required' => 'Please select an account type',
                    'in_list' => 'Please select a valid account type'
                ],
                'sch_category' => [
                    'required' => 'Please select school category',
                    'in_list' => 'Please select a valid school category'
                ],
                'account_name' => [
                    'required' => 'School name is required',
                    'min_length' => 'School name must be at least 3 characters',
                    'max_length' => 'School name cannot exceed 100 characters'
                ],
                'fname' => [
                    'required' => 'First name is required',
                    'min_length' => 'First name must be at least 3 characters',
                    'max_length' => 'First name cannot exceed 100 characters'
                ],
                'lname' => [
                    'required' => 'Last name is required',
                    'min_length' => 'Last name must be at least 3 characters',
                    'max_length' => 'Last name cannot exceed 100 characters'
                ],
                'gender' => [
                    'required' => 'Please select a gender'
                ],
                'dob' => [
                    'required' => 'Please select dob'
                ],
                'province' => [
                    'required' => 'Please select a province'
                ],
                'province2' => [
                    'required' => 'Please select a province'
                ],
                'district' => [
                    'required' => 'Please select a district'
                ],
                'district2' => [
                    'required' => 'Please select a district'
                ],
                'phone' => [
                    'required' => 'Phone number is required',
                    'min_length' => 'Phone number must be exactly 7 digits',
                    'max_length' => 'Phone number must be exactly 7 digits'
                ],
                'address' => [
                    'required' => 'Address is required',
                    'min_length' => 'Address must be at least 5 characters',
                    'max_length' => 'Address cannot exceed 255 characters'
                ],
                'my_email' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please enter a valid email address',
                    'is_unique' => 'This email is already registered'
                ],
                'my_phone' => [
                    'required' => 'Phone number is required',
                    'min_length' => 'Phone number must be exactly 7 digits',
                    'max_length' => 'Phone number must be exactly 7 digits'
                ],
                'my_address' => [
                    'required' => 'Address is required',
                    'min_length' => 'Address must be at least 5 characters',
                    'max_length' => 'Address cannot exceed 255 characters'
                ],
                'password' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 8 characters',
                    'max_length' => 'Password cannot exceed 32 characters',
                    'matches' => 'Passwords do not match',
                    'regex_match' => 'Password must contain:'
                        . '<ul class="mb-0">'
                        . '<li>At least one uppercase letter (A-Z)</li>'
                        . '<li>At least one lowercase letter (a-z)</li>'
                        . '<li>At least one number (0-9)</li>'
                        . '<li>At least one special character (@$!%*?&)</li>'
                        . '</ul>'
                ],
                're-type-password' => [
                    'required' => 'Please re-type your password'
                ]
            ];
            
            // Validate the data
            if (!$this->validate($validationRules, $validationMessages)) {
                // Get district data for repopulation
                
                // For School District
                if ($province) {
                    $getDistrict = $this->districtModel->getDistrictByProvince($province);
                    $data['provinceDistrict'] = $getDistrict;
                }
                
                // For Personal District
                if ($province2) {
                    $getDistrict2 = $this->districtModel->getDistrictByProvince($province2);
                    $data['provinceDistrict2'] = $getDistrict2;
                }
                
                $data['validation'] = $this->validator;
                
                // Return the view with errors
                return view('web/layouts/main_2', $data);
            } else {
                // Validation passed - process the data
                $plainPassword = $this->request->getPost('password');
                $encryptedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
                
                $schoolData = [
                    'sch_cat_id_fk'  => $this->request->getPost('sch_category'),
                    'district_id_fk'  => $this->request->getPost('district'), // School district
                    'sch_name'      => $this->request->getPost('account_name'),
                    'sch_address'   => $this->request->getPost('address'),
                    'sch_phone'     => $this->request->getPost('phone'),
                    'sch_email'     => $this->request->getPost('email'),
                    'sch_password'  => $encryptedPassword,
                    'sch_motto' => $this->request->getPost('motto'),
                    'sch_primary_color' => $this->request->getPost('primary_color'),
                    'sch_secondary_color' => $this->request->getPost('secondary_color'),
                    'sch_created_at'  => date('Y-m-d H:i:s'),
                    'sch_status'  => 'Step 1 Configured',
                ];
                
                // Also need to save personal district somewhere
                $personalDistrictId = $this->request->getPost('district2');
                
                // Save school data
                $addSchool = $this->schoolModel->addSchool($schoolData);
                
                if ($addSchool) {
                    $schoolId = $this->schoolModel->getInsertID();
                    
                    $success .= 'Successfully registered school data.';
                    
                    //Navuli subscription data
                    $subData = [
                        'plan_id_fk' => $this->request->getPost('account_type'),
                        'sch_id_fk ' => $addSchool,
                        'subscription_start_date' => date('Y-m-d'),
                        'subscription_time' => time(),
                        'subscription_term' => 1,
                        'subscription_status' => 'Active'
                    ];
                    
                    //add subscription data
                    $addSub = $this->subscriptionModel->addSubscription($subData);
                    
                    if($addSub){
                        $success .= ' Successfully added navuli subscription data.';
                    }else{
                        $success .= '<font>Fail to add navuli subscription data.</font>';
                    }
                    
                    
                    // Here you should also save the personal information
                    // You might have a separate table for admin/users
                    $time = time();
                    $userData = [
                        'district_id_fk' => $personalDistrictId, // Personal district
                        'password' => $encryptedPassword,
                        'fname' => $this->request->getPost('fname'),
                        'lname' => $this->request->getPost('lname'),
                        'oname' => $this->request->getPost('oname'),
                        'gender' => $this->request->getPost('gender'),
                        'dob' => date('Y-m-d', strtotime($this->request->getPost('dob'))),
                        'address' => $this->request->getPost('my_address'),
                        'email' => $this->request->getPost('my_email'),
                        'phone' => $this->request->getPost('my_phone'),
                        'created_date' => date('Y-m-d'),
                        'created_time' => $time,
                        'online_status' => 'Offline',
                        'password_reset_code' => md5($time),
                        'user_status' => 'Pending Email Confirmation'
                    ];
                    
                    
                    
                    // Save user/admin data (you need to implement this)
                    $addUser = $this->userModel->addUser($userData);
                    
                    if($addUser){
                        $success .= ' Successfully register user personal detail.';
                        
                        //Keep track of user password
                        $passwordData = [
                            'user_id_fk' => $addUser,
                            'password' => $encryptedPassword,
                            'date_created' => date('Y-m-d'),
                            'time_created' => time(),
                            'password_status' => 'Active'
                        ];
                        
                        $addPassword = $this->userPasswordModel->addPassword($passwordData);
                        
                        if($addPassword){
                            $success .= ' Successfully registered user password.';
                        }else{
                            $success .= '<font color="red"> Fail to register user password.</font>';
                        }
                        
                        //add user role
                        $userRoleData = [
                            'user_id_fk' =>  $addUser,
                            'role_id_fk' => 2
                        ];
                        
                        $addUserRole = $this->userRoleModel->addUserRole($userRoleData);
                        
                        if($addUserRole){
                            $success .= ' Successfully registered user role.';
                        }else{
                            $success .= '<font color="red"> Fail to register user role</font>';
                        }
                        
                        //user log data
                        $userLogData = [
                            'user_id_fk' => $addUser,
                            'ip_aadress' => $this->ipAddress,
                            'user_agent' => $this->userAgent->getAgentString(),
                            'user_device' => $this->deviceInfo['device_type'],
                            'log_title' => 'Register School Account',
                            'log_desc' => 'Successfully registered school information.',
                            'log_date' => date('Y-m-d'),
                            'log_time' => time(),
                            'log_icon' => '<i class="ki-duotone ki-copy-success"><span class="path1"></span><span class="path2"></span></i>',
                            'log_theme' => 'success'
                        ];
                        
                        $addUserLog = $this->userLogModel->addUserLog($userLogData);
                        
                        if($addUserLog){
                            $success .= ' User activity successfully logged.';
                        }else{
                            $success .= ' <font color="red">Fail to log user activity.</font>';
                        }
                        
                        // Prepare email data
                        $emailData = [
                            'name' => $this->request->getPost('fname').' '.$this->request->getPost('lname'),
                            'email' => $this->request->getPost('my_email'),
                            'code' => md5($time.$addUser)
                        ];
                        
                        $emailSent = $this->sendSubscriptionEmail($emailData);
                        
                        $addSchoolMessage = $success;
                        $message = $emailSent 
                            ? $addSchoolMessage.' Please check your email and activate your account. If you do not receive an email login to activate user account.'
                            : $addSchoolMessage.' <font color="red">We encountered an issue sending your confirmation email.</font>';
                        
                    }else{
                        $success .= '<font color="red">Fail to register user personal detail.</font>';
                    }
                    
                    return redirect()->to('/account/progress')->with('success', $message);
                } else {
                    return redirect()->to('/account/progress')->with('error', 'Failed to save school information. Please try again.');
                }
            }
        }
        
        // Initial page load - show form
        return view('web/layouts/main_2', $data);
    }
    
    
   /* private function uploadSchoolLogo($schoolId)
    {
        helper(['text', 'form']);
        
        log_message('debug', 'Starting logo upload for school ID: ' . $schoolId);
        
        // Check if file input exists and has a file
        if (!$this->request->getFile('avatar') || empty($_FILES['avatar']['name'])) {
            return ['status' => 'skip', 'message' => 'No logo selected - optional field'];
        }
        
        $file = $this->request->getFile('avatar');
        
        // Debug file information
        if (!$file) {
            log_message('debug', 'No file object found in request');
            return ['status' => 'error', 'message' => 'No file uploaded'];
        }
        
        log_message('debug', 'File object found: ' . $file->getName());
        log_message('debug', 'File isValid: ' . ($file->isValid() ? 'Yes' : 'No'));
        log_message('debug', 'File hasMoved: ' . ($file->hasMoved() ? 'Yes' : 'No'));
        
        if (!$file->isValid()) {
            $error = $file->getErrorString();
            log_message('debug', 'File validation error: ' . $error);
            return ['status' => 'error', 'message' => $error];
        }
        
        // Validation rules
        $validation = \Config\Services::validation();
        $rules = [
            'avatar' => [
                'label' => 'School Logo',
                'rules' => 'uploaded[avatar]|max_size[avatar,2048]|mime_in[avatar,image/jpg,image/jpeg,image/png]|ext_in[avatar,jpg,jpeg,png]',
                'errors' => [
                    'uploaded' => 'Please select a school logo.',
                    'max_size' => 'Logo size must be less than 2MB.',
                    'mime_in' => 'Only JPG, PNG images are allowed.',
                    'ext_in' => 'Only JPG, PNG images are allowed.'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            $errors = implode(', ', $validation->getErrors());
            log_message('debug', 'Validation errors: ' . $errors);
            return ['status' => 'error', 'message' => $errors];
        }
        
        if ($file->isValid() && !$file->hasMoved()) {
            // Generate random 10-digit number for filename
            $randomNumber = random_string('numeric', 10);
            
            // Get file extension
            $fileExtension = $file->getClientExtension();
            
            // Create new filename: logo_random-10-digits.extension
            $newFileName = "logo_{$randomNumber}.{$fileExtension}";
            
            // Create school-specific directory if it doesn't exist
            $uploadPath = WRITEPATH . 'uploads/school/logo/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
                log_message('debug', 'Created directory: ' . $uploadPath);
            }
            
            log_message('debug', 'Attempting to move file to: ' . $uploadPath . $newFileName);
            
            // Move file to school directory
            if ($file->move($uploadPath, $newFileName)) {
                log_message('debug', 'File moved successfully: ' . $newFileName);
                return [
                    'status' => 'success', 
                    'filename' => $newFileName,
                    'full_path' => $uploadPath . $newFileName
                ];
            } else {
                $moveError = 'Failed to move uploaded file.';
                log_message('debug', $moveError);
                return ['status' => 'error', 'message' => $moveError];
            }
        }
        
        $finalError = 'File upload failed - unknown reason.';
        log_message('debug', $finalError);
        return ['status' => 'error', 'message' => $finalError];
    }*/
    
    
    /**
     * Send Beautiful HTML Email Notification using the template
     */
    private function sendSubscriptionEmail($emailData)
    {
        try {
            //$email = Services::email();
            
            $email = \Config\Services::email();
            
            // Use the default config from Config/Email.php
            $config = [
                'protocol'    => 'smtp',
                'SMTPHost'    => 'mail.navulifiji.com',
                'SMTPUser'    => 'noreply@navulifiji.com',
                'SMTPPass'    => 'N0r3pp!@25',
                'SMTPPort'    => 587, // Use 587 with tls (not 465 with ssl)
                'SMTPCrypto'  => 'tls', // Changed from ssl to tls
                'mailType'    => 'html',
                'charset'     => 'utf-8',
                'wordWrap'    => true,
                'SMTPTimeout' => 30,
                'newline'     => "\r\n",
                'CRLF'        => "\r\n"
            ];
            
            $email->initialize($config);
            
            $email->setFrom('noreply@navulifiji.com', 'Navuli Fiji');
            $email->setTo($emailData['email']);
            $email->setSubject('Activate User Account');
            
            // Add reply-to
            $email->setReplyTo('support@navulifiji.com', 'Navuli Fiji Support');
            
            // Set important headers
            $email->setHeader('Precedence', 'bulk');
            $email->setHeader('X-Priority', '3');
            $email->setHeader('X-Mailer', 'Navuli Fiji Mailer');
            $email->setHeader('List-Unsubscribe', '<mailto:unsubscribe@navulifiji.com>');
            
            // Prepare data for the view
            $viewData = [
                'name' => $emailData['name'],
                'code' => $emailData['code'],
            ];
            
            
            // Render the email template
            $emailMessage = view('email/user_activation_notification', $viewData);
            
            $email->setMessage($emailMessage);
            
            if ($email->send()) {
                log_message('info', 'Subscription email sent successfully to: ' . $emailData['email']);
                return true;
            } else {
                log_message('error', 'Failed to send subscription email: ' . $email->printDebugger(['headers']));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Email sending error: ' . $e->getMessage());
            return false;
        }
    }
   

}