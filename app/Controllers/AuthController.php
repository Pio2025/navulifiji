<?php

namespace App\Controllers;

// Add this line in Base Controller after everything working
use DateTime;
use Exception;
use App\Services\EmailService;

class AuthController extends BaseController
{
    protected $validation;
    protected $session;
    protected $email;
    protected $helpers = ['form', 'url', 'cookie']; // Added cookie helper
    
    
    public function __construct()
    {
        helper('form,url,cookie'); // Added cookie helper
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();
    }
    
    
    public function index(): string
    {
        return $this->login();
    }
	
	public function login()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Check if already logged in
        if ($this->session->get('isLoggedIn')) {
            
            // Get school ID from session
            /*$schID = $this->session->get('schID');
            // Redirect based on school status
            if ($this->session->get('schStatus') === 'Active') {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/school/setup/' . $this->session->get('schID'));   
            }*/
            return redirect()->to('/dashboard');
        }
        
        $data['_view'] = 'app/auth/login';
        
        // Get validation errors from session if they exist
        $data['validation'] = $this->session->getFlashdata('validation');
        
        // Pass validation object if it exists in session (for new validation structure)
        if (session()->has('validation')) {
            $data['validation'] = session('validation');
        }
        
        $data['error'] = $this->session->getFlashdata('error');
        $data['old']   = $this->session->getFlashdata('old');

        // Pre-fill remembered credentials
        $data['remembered_email']    = $_COOKIE['remember_email']    ?? '';
        $data['remembered_password'] = '';
        if (!empty($_COOKIE['remember_password'])) {
            try {
                $data['remembered_password'] = $this->deobfuscate($_COOKIE['remember_password']);
            } catch (\Throwable $e) {
                $data['remembered_password'] = '';
            }
        }

        return view('app/layouts/auth_main', $data);
    }
    
    //process school login form
    public function process_login(){
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Check if already logged in
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Only allow POST requests
        if (!$this->request->is('post')) {
            return redirect()->to('/school/login');
        }
        
        // Define validation rules
        $rules = [
            'email'    => 'required',
            'password' => 'required|min_length[8]|max_length[30]',
        ];

        // Custom error messages
        $messages = [
            'email' => [
                'required' => 'Email or username is required.',
            ],
            'password' => [
                'required'   => 'Password is required.',
                'min_length' => 'Password must be at least 8 characters.',
                'max_length' => 'Password must not exceed 30 characters.',
            ],
        ];
        
        // Validate the input
        if (!$this->validate($rules, $messages)) {
            // Validation failed - redirect back with errors and old input
            return redirect()->back()
                ->with('validation', $this->validator)
                ->with('error', 'Please correct the errors below.')
                ->with('old', $this->request->getPost())
                ->withInput();
        }
        
        // Validation passed - process login
        $identifier = trim($this->request->getPost('email'));
        $password   = $this->request->getPost('password');
        $rememberMe = $this->request->getPost('remember_me');

        try {
            // Check if model is available
            if (!isset($this->schoolModel)){
                return redirect()->back()
                    ->with('error', 'System configuration error. Please contact administrator.')
                    ->with('old', $this->request->getPost())
                    ->withInput();
            }

            // Look up by email if the identifier contains @, otherwise by username
            $isEmail = str_contains($identifier, '@');
            $user = $this->userModel
                ->select('users.*, user_role.*, district.*')
                ->join('user_role', 'user_role.user_id_fk = users.user_id',   'left')
                ->join('district',  'district.district_id = users.district_id_fk', 'left')
                ->where($isEmail ? 'users.email' : 'users.username', $identifier)
                ->first();

            if (!$user) {
                $hint = $isEmail ? 'email address' : 'username';
                return redirect()->back()
                    ->with('error', "No account found with that {$hint}. Please check and try again.")
                    ->with('old', $this->request->getPost())
                    ->withInput();
            }

            // Verify password
            if (!password_verify($password, $user['password'])) {
                // Log failed login attempt
                $this->logFailedLogin($identifier);
                
                return redirect()->back()
                    ->with('error', 'Invalid password. Please enter correct password.')
                    ->with('old', $this->request->getPost())
                    ->withInput();
            }
            
            $userStatus = $user['user_status'] ?? 'Inactive';
            $userID = $user['user_id'];
            
            // Handle Remember Me functionality
            if ($rememberMe) {
                $this->setRememberMeCookie($identifier, $password);
            } else {
                $this->clearRememberMeCookie();
            }
            
            $tfData = $this->twoFactorModel->get2FAData($userID);
            
            if (!empty($tfData['two_factor_enabled']) && $tfData['two_factor_enabled'] == 1) {
                // Don't set isLoggedIn yet — store pending state
                $this->session->set([
                    '2fa_pending' => true,
                    '2fa_user_id' => $userID,
                    '2fa_method'  => $tfData['two_factor_method'],
                ]);
            
                return redirect()->to('auth/2fa/verify');
            }
            
            //update online status
            $this->userModel->updateUser($userID,array('online_status'=>'Online'));
            
            //get user role
            $userRole = $this->userRoleModel->findActiveUserRole($userID);
            
            if($userRole){
                $roleID = $userRole['role_id_fk'];
                $roleName = $userRole['role_name'];
                $rank = $userRole['role_rank'];
                $roleCatID = $userRole['role_cat_id'];
                $roleCatName = $userRole['role_cat_name'];
                //check if user is also a parent if so ask if user want to login as parent or as user with role
            }else{
                $roleID = 0;
                $rank = 900000000;
                $roleName = 'Unknown';
                $roleCatID = 0;
                $roleCatName = 'Unknown';
            }
            
            //Add user log
            $userLogData = [
                'user_id_fk' => $userID,
                'ip_aadress' => $this->ipAddress,
                'user_agent' => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title' => 'User Login',
                'log_desc' => 'Successfully login to Navuli Fiji.',
                'log_date' => date('Y-m-d'),
                'log_time' => time(),
                'log_icon' => '<i class="ki-duotone ki-entrance-left"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme' => 'primary'
            ];
            
            $addUserLog = $this->userLogModel->addUserLog($userLogData);
            

            // Set session data
            if($user['oname'] != ''){
                $name = $user['fname'].' '.$user['oname'].' '.$user['lname'];
            }else{
                $name = $user['fname'].' '.$user['lname'];
            }
            
            
            if($user['profile_photo'] != ''){
                $photo = $user['profile_photo'];
            }else{
                if($user['gender'] != ''){
                    if($user['gender'] == 'Female'){
                        $photo = 'default_female.png';
                    }else{
                        $photo = 'default_male.jpg';
                    }
                }else{
                    $photo = 'default_male.jpg';
                }
            }

            //check admission data if any — getAdmissionByUser returns findAll() (array of rows)
            $admissions   = $this->admissionModel->getAdmissionByUser($userID);
            $getAdmission = !empty($admissions) ? $admissions[0] : [];
            $schID        = !empty($getAdmission['sch_id_fk']) ? (int)$getAdmission['sch_id_fk'] : 0;

            // For staff/teachers with no admission record, derive school from staff table,
            // then fall back to their active classroom assignment chain.
            if ($schID === 0) {
                $staffRow = $this->db->table('staff')
                    ->select('sch_id_fk')
                    ->where('user_id_fk', $userID)
                    ->where('staff_status', 'Active')
                    ->limit(1)
                    ->get()->getRowArray();
                if ($staffRow) {
                    $schID = (int) $staffRow['sch_id_fk'];
                }
            }
            if ($schID === 0) {
                $classroomRow = $this->db->table('classroom_subject_teacher cst')
                    ->select('sl.sch_id_fk')
                    ->join('classroom_subject cs', 'cs.class_sub_id = cst.class_sub_id_fk')
                    ->join('classroom c', 'c.class_id = cs.class_id_fk')
                    ->join('stream s', 's.stream_id = c.stream_id_fk')
                    ->join('sch_level sl', 'sl.sch_level_id = s.sch_level_id_fk')
                    ->where('cst.user_id_fk', $userID)
                    ->where('cst.class_sub_teacher_status', 'Active')
                    ->limit(1)
                    ->get()->getRowArray();
                if ($classroomRow) {
                    $schID = (int) $classroomRow['sch_id_fk'];
                }
            }
            
            $sessionData = [
                'fname' => $user['fname'],
                'lname' => $user['lname'],
                'oname' => $user['oname'],
                'userID' => $userID,
                'schID' => $schID,
                'roleID' => $roleID,
                'roleName' => $roleName,
                'roleRank' => $rank,
                'roleCatID' => $roleCatID,
                'roleCatName' => $roleCatName,
                'name' => $name,
                'gender' => $user['gender'],
                'address' => $user['address'] ?? 'Unknown Address',
                'phone' => $user['phone'] ?? 'Unknown Phone',
                'status' => $userStatus,
                'email' => $user['email']  ?? 'Unknown Email',
                'initial' => $this->generateAcronym($name),
                'district' => $user['district_name'] ?? 'Unknown District',
                'photo' => $photo,
                'isLoggedIn' => true,
                'logged_in' => time()
            ];

            $this->session->set($sessionData);

            // Store school category term config for dynamic term-based UI rendering
            if ($schID > 0) {
                $db     = \Config\Database::connect();
                $school = $db->table('school')
                    ->select('sch_cat_id_fk')
                    ->where('sch_id', $schID)
                    ->get()->getRowArray();
                if ($school && !empty($school['sch_cat_id_fk'])) {
                    $catCfg = $this->schoolCategoryConfigModel->getByCategoryId((int) $school['sch_cat_id_fk']);
                    if ($catCfg) {
                        $catTerms = $this->schoolCategoryTermModel->getByConfigId((int) $catCfg['sch_cat_con_id']);
                        $termData = [];
                        foreach ($catTerms as $t) {
                            $termData[(int) $t['term_num']] = [
                                'num_of_week'      => (int) $t['num_of_week'],
                                'term_start_day'   => (int) $t['term_start_day'],
                                'term_start_month' => (int) $t['term_start_month'],
                                'term_end_day'     => (int) $t['term_end_day'],
                                'term_end_month'   => (int) $t['term_end_month'],
                            ];
                        }
                        $this->session->set('sch_cat_data', [
                            'label' => $catCfg['label_for_term'],
                            'terms' => $termData,
                        ]);
                    }
                }
            }

            // ── Create user session record ────────────────────────────────────────
            $sessionToken = bin2hex(random_bytes(32));
            $this->session->set('sessionToken', $sessionToken);
            
            $ua       = $this->request->getUserAgent()->getAgentString();
            $parsed   = $this->parseUserAgent($ua);
            $location = $this->getLocationFromIP($this->ipAddress);
            
            $this->userSessionModel->insert([
                'user_id_fk'     => $userID,
                'session_token'  => $sessionToken,
                'ip_address'     => $this->ipAddress,
                'user_agent'     => $ua,
                'device_type'    => $this->deviceInfo['device_type'],
                'device_os'      => $parsed['os'],
                'browser'        => $parsed['browser'],
                'country'        => $location['country'],
                'city'           => $location['city'],
                'login_date'     => date('Y-m-d'),
                'login_time'     => time(),
                'last_active'    => time(),
                'session_status' => 'Active',
            ]);
            // ────────────────────────────────────────────────────────────────────────

            // Redirect based on school status
            if ($userStatus === 'Active') {
                
                //handle redirect to last page visit
                $url = $this->session->get('url');
                
                if(isset($url) && !empty($url)){
                    return redirect()->to($url)->with('success', 'You have been redirected to the last page you visit.');
                }else{
                    return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['fname'] . '!');
                }
            } else {
                return redirect()->to('/user/activate/' . $userID);
            }

        } catch (\Exception $e) {
            // Log the error
            log_message('error', 'Login error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'An error occurred during login. Please try again.')
                ->with('old', $this->request->getPost())
                ->withInput();
        }
    }
    
    /**
     * Set remember me cookies — stores email plaintext and password XOR-encrypted.
     */
    private function setRememberMeCookie(string $email, string $password)
    {
        $expire = time() + 2592000; // 30 days
        setcookie('remember_email',    $email,                              $expire, '/', '', false, true);
        setcookie('remember_password', $this->obfuscate($password),         $expire, '/', '', false, true);
    }

    /**
     * Clear remember me cookies
     */
    private function clearRememberMeCookie()
    {
        foreach (['remember_email', 'remember_password'] as $name) {
            setcookie($name, '', time() - 3600, '/');
            unset($_COOKIE[$name]);
        }
    }

    /** XOR each byte of $value against a repeating key. */
    private function xorWithKey(string $value): string
    {
        $key    = md5(env('encryption.key', 'navuli-secret-key'));
        $result = '';
        for ($i = 0, $len = strlen($value); $i < $len; $i++) {
            $result .= chr(ord($value[$i]) ^ ord($key[$i % strlen($key)]));
        }
        return $result;
    }

    private function obfuscate(string $value): string
    {
        return base64_encode($this->xorWithKey($value));
    }

    private function deobfuscate(string $value): string
    {
        return $this->xorWithKey(base64_decode($value));
    }
    
    /**
     * Log failed login attempt
     */
    private function logFailedLogin($email)
    {
        $failedLoginData = [
            'email' => $email,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent->getAgentString(),
            'device_type' => $this->deviceInfo['device_type'],
            'attempt_time' => date('Y-m-d H:i:s')
        ];
        
        log_message('warning', 'Failed login attempt: ' . json_encode($failedLoginData));
    }
    
    public function logout()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    
        $userID       = $this->session->get('userID');
        $Name         = $this->session->get('name');
        $sessionToken = $this->session->get('sessionToken');
    
        if ($userID) {
            try {
                // ── Mark session as Signed Out ────────────────────────────
                if ($sessionToken) {
                    $this->userSessionModel
                        ->where('session_token', $sessionToken)
                        ->where('user_id_fk', $userID)
                        ->set(['session_status' => 'Signed Out'])
                        ->update();
                }
                // ────────────────────────────────────────────────────────────
    
                // Update online status
                $this->userModel->updateUser($userID, ['online_status' => 'Offline']);
    
                // Log activity
                $this->userLogModel->addUserLog([
                    'user_id_fk'  => $userID,
                    'ip_aadress'  => $this->ipAddress,
                    'user_agent'  => $this->userAgent->getAgentString(),
                    'user_device' => $this->deviceInfo['device_type'],
                    'log_title'   => 'User Logout',
                    'log_desc'    => 'Successfully logged out from Navuli Fiji.',
                    'log_date'    => date('Y-m-d'),
                    'log_time'    => time(),
                    'log_icon'    => '<i class="ki-duotone ki-entrance-right"><span class="path1"></span><span class="path2"></span></i>',
                    'log_theme'   => 'danger'
                ]);
    
            } catch (\Exception $e) {
                log_message('error', 'Error during logout: ' . $e->getMessage());
            }
        }
    
        log_message('info', "User {$Name} (ID: {$userID}) logged out");
    
        // Destroy session
        $this->session->destroy();
    
        // Clear session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    
        return redirect()->to('/auth/login')->with('success', 'You have been logged out successfully.');
    }
    
    // ================================================================
    // FORGOT PASSWORD — Show form
    // ================================================================
    
    public function forgotPassword()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('dashboard');
        }
    
        $data['_view'] = 'app/auth/forgot_password';
        return view('app/layouts/auth_main', $data);
    }
    
    // ================================================================
    // FORGOT PASSWORD — Process form submission
    // ================================================================
    
    public function processVerify()
    {
        if (!$this->session->get('2fa_pending')) {
            return redirect()->to('auth/login');
        }
    
        $code   = trim($this->request->getPost('code') ?? '');
        $userId = $this->session->get('2fa_user_id');
        $method = $this->session->get('2fa_method');
    
        // ── Server-side empty check ───────────────────────────────────
        if (empty($code) || strlen($code) !== 6 || !ctype_digit($code)) {
            $this->session->setFlashdata('2fa_error', 'Please enter the complete 6-digit verification code.');
            $this->session->setFlashdata('2fa_error_type', 'validation');
            return redirect()->to('auth/2fa/verify');
        }
    
        try {
            $data  = $this->twoFactorModel->get2FAData($userId);
            $valid = false;
    
            if ($method === 'authenticator') {
                $google2fa = new Google2FA();
                $valid     = $google2fa->verifyKey($data['two_factor_secret'], $code, 2);
    
            } elseif ($method === 'otp_email') {
                // ── Debug log to trace OTP comparison ────────────────
                log_message('debug', '[2FA OTP] Submitted: "' . $code . '" | Stored: "' . ($data['otp_code'] ?? 'NULL') . '" | Expiry: ' . ($data['otp_expiry'] ?? 'NULL') . ' | Now: ' . time());
    
                if (empty($data['otp_code'])) {
                    $this->session->setFlashdata('2fa_error', 'No OTP found. Please request a new code.');
                    $this->session->setFlashdata('2fa_error_type', 'expired');
                    return redirect()->to('auth/2fa/verify');
                }
    
                if (time() > (int) $data['otp_expiry']) {
                    $this->twoFactorModel->clearOtp($userId);
                    $this->session->setFlashdata('2fa_error', 'Your code has expired. Please request a new one.');
                    $this->session->setFlashdata('2fa_error_type', 'expired');
                    return redirect()->to('auth/2fa/verify');
                }
    
                // Trim both sides to prevent whitespace issues
                $storedCode    = trim((string) $data['otp_code']);
                $submittedCode = trim($code);
    
                log_message('debug', '[2FA OTP] Comparing "' . $submittedCode . '" === "' . $storedCode . '" → ' . ($storedCode === $submittedCode ? 'MATCH' : 'NO MATCH'));
    
                if ($storedCode === $submittedCode) {
                    $valid = true;
                    $this->twoFactorModel->clearOtp($userId);
                }
            }
    
            if (!$valid) {
                $this->session->setFlashdata('2fa_error', 'Invalid verification code. Please check the code and try again.');
                $this->session->setFlashdata('2fa_error_type', 'invalid');
                return redirect()->to('auth/2fa/verify');
            }
    
            // ── 2FA passed — build full session ──────────────────────
            $user       = $this->userModel->findUserFull($userId);
            $userStatus = $user['status'] ?? 'Active';
    
            $this->userModel->updateUser($userId, ['online_status' => 'Online']);
    
            $userRole    = $this->userRoleModel->findActiveUserRole($userId);
            $roleID      = $userRole['role_id_fk']   ?? 0;
            $roleName    = $userRole['role_name']     ?? 'Unknown';
            $rank        = $userRole['role_rank']     ?? 900000000;
            $roleCatID   = $userRole['role_cat_id']   ?? 0;
            $roleCatName = $userRole['role_cat_name'] ?? 'Unknown';
    
            $name = trim($user['fname'] . ' ' . ($user['oname'] ? $user['oname'] . ' ' : '') . $user['lname']);
    
            if (!empty($user['profile_photo'])) {
                $photo = $user['profile_photo'];
            } elseif (strtolower($user['gender'] ?? '') === 'female') {
                $photo = 'default_female.png';
            } else {
                $photo = 'default_male.jpg';
            }
    
            $this->userLogModel->insert([
                'user_id_fk'  => $userId,
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'User Login (2FA)',
                'log_desc'    => 'Successfully logged in via two-factor authentication (' . $method . ').',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-entrance-left"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'primary',
            ]);
    
            $this->session->set([
                'fname'       => $user['fname'],
                'userID'      => $userId,
                'roleID'      => $roleID,
                'roleName'    => $roleName,
                'roleRank'    => $rank,
                'roleCatID'   => $roleCatID,
                'roleCatName' => $roleCatName,
                'name'        => $name,
                'gender'      => $user['gender'],
                'address'     => $user['address']  ?? 'Unknown Address',
                'phone'       => $user['phone']    ?? 'Unknown Phone',
                'status'      => $userStatus,
                'email'       => $user['email']    ?? 'Unknown Email',
                'initial'     => $this->generateAcronym($name),
                'district'    => $user['district_name'] ?? 'Unknown District',
                'photo'       => $photo,
                'isLoggedIn'  => true,
                'logged_in'   => time(),
            ]);
    
            $this->session->remove(['2fa_pending', '2fa_user_id', '2fa_method']);
            $this->create2FASessionRecord($userId);
    
            if ($userStatus === 'Active') {
                $url = $this->session->get('url');
                if (!empty($url)) {
                    return redirect()->to($url)->with('success', 'Welcome back, ' . $user['fname'] . '!');
                }
                return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['fname'] . '!');
            }
    
            return redirect()->to('/user/activate/' . $userId);
    
        } catch (\Exception $e) {
            log_message('error', '[TwoFactorController::processVerify] ' . $e->getMessage());
            $this->session->setFlashdata('2fa_error', 'Verification failed. Please try again.');
            $this->session->setFlashdata('2fa_error_type', 'error');
            return redirect()->to('auth/2fa/verify');
        }
    }
    
    // ================================================================
    // RESET PASSWORD — Show reset form
    // ================================================================
    
    public function resetPassword(string $token)
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('dashboard');
        }
    
        // Validate token
        $user = $this->userModel
            ->where('reset_token', $token)
            ->where('reset_token_expiry >', time())
            ->first();
    
        if (!$user) {
            $data['_view']   = 'app/auth/reset_password_invalid';
            return view('app/layouts/auth_main', $data);
        }
    
        $data['_view'] = 'app/auth/reset_password';
        $data['token'] = $token;
        $data['email'] = $user['email'];
    
        return view('app/layouts/auth_main', $data);
    }
    
    // ================================================================
    // RESET PASSWORD — Process new password
    // ================================================================
    
    public function processResetPassword(string $token)
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('dashboard');
        }
    
        // Validate token again
        $user = $this->userModel
            ->where('reset_token', $token)
            ->where('reset_token_expiry >', time())
            ->first();
    
        if (!$user) {
            return redirect()->to('auth/login')
                ->with('error', 'This password reset link is invalid or has expired. Please request a new one.');
        }
    
        $password        = $this->request->getPost('password')         ?? '';
        $confirmPassword = $this->request->getPost('confirm_password') ?? '';
    
        // Validate password
        $errors = [];
    
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter.';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number.';
        }
    
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }
    
        if (!empty($errors)) {
            return redirect()->back()
                ->with('errors', $errors)
                ->withInput();
        }
    
        try {
            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Update user password and clear reset token
            $this->userModel->update($user['user_id'], [
                'password'           => $hashedPassword,
                'reset_token'        => null,
                'reset_token_expiry' => null,
            ]);
    
            // Save to password history if you use userPasswordModel
            $this->userPasswordModel->insert([
                'user_id_fk'    => $user['user_id'],
                'password_hash' => $hashedPassword,
                'created_date'  => date('Y-m-d'),
                'created_time'  => time(),
            ]);
    
            // Sign out all active sessions for security
            $this->userSessionModel
                ->where('user_id_fk', $user['user_id'])
                ->where('session_status', 'Active')
                ->set(['session_status' => 'Signed Out'])
                ->update();
    
            // Send confirmation email
            $this->sendEmail([
                'to'       => $user['email'],
                'subject'  => 'Password Changed Successfully — Navuli Fiji',
                'view'     => 'email/password_changed',
                'viewData' => [
                    'name'  => $user['fname'],
                    'email' => $user['email'],
                    'ip'    => $this->ipAddress,
                    'time'  => date('d F Y \a\t h:i A'),
                ],
            ]);
    
            // Log
            $this->userLogModel->insert([
                'user_id_fk'  => $user['user_id'],
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Password Reset Successful',
                'log_desc'    => 'Password was successfully reset via email link.',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-lock-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
                'log_theme'   => 'success',
            ]);
    
            return redirect()->to('auth/login')
                ->with('success', 'Your password has been reset successfully. You can now sign in with your new password.');
    
        } catch (\Exception $e) {
            log_message('error', '[AuthController::processResetPassword] ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred. Please try again.')
                ->withInput();
        }
    }
}
