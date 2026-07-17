<?php

namespace App\Controllers;

// Add this line in Base Controller after everything working
use DateTime;
use Exception;
use App\Services\EmailService;

class SchoolController extends BaseController
{
    protected $validation;
    protected $db;
    
    
    public function __construct(){
        helper('form', 'url');
        
        // Initialize services
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        
        // Initialize database connection
        $this->db = \Config\Database::connect();
    }
    
    public function index()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'school');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('School Listing', 'School', 'School Listing');
        
        // Check permission
        if (!$this->require_access('_school_listing')) {
            $data['_view'] = 'app/auth/access_control';
        } else {
            $data['_view'] = 'app/school/management/index';
        }
        
        return view('app/layouts/main', $data);
    }
    
    public function email(){
        //echo 'hi!!!!!!';
        return view('email/email_template');
    }
    
    public function overview(){
        
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $schID = $this->session->get('schID');

        //check for login
        if (!$this->session->get('isLoggedIn') || !$schID) {
             return redirect()->to('/school/login');
        }

        $this->session->set('active_nav','overview');

        $this->session->set('active_page','overview');
        $view = 'app/school/profile/overview';

        // Load common data
        $commonData = $this->loadCommonData($view);

        // Get school details
        $schoolDetails = $this->schoolModel->findFullSchoolDetail($schID);

        if (!$schoolDetails || !isset($schoolDetails['sch_id'])) {
            return redirect()->to('/school/login')->with('error', 'School session invalid. Please log in again.');
        }

        // Merge both arrays
        $data = array_merge($commonData, [
            'school' => $schoolDetails
        ]);

        return view('app/layouts/school_main',$data);
    }
    
    public function department(){
        
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        //check for login        
        if (!$this->session->get('isLoggedIn')) {
             return redirect()->to('/school/login');
        }
        
        $this->session->set('active_nav','department');
        
        $this->session->set('active_page','department');
        $view = 'app/school/profile/department';
        
        // Load common data
        $commonData = $this->loadCommonData($view);
        
        $schID = $this->session->get('schID');
        
        // Get school details
        $schoolDetails = $this->schoolModel->findFullSchoolDetail($schID);
        
        //Get chool department
        $schDepartment = $this->schoolDepartmentModel->findFullSchoolDepartment($schID);
        
        // Merge both arrays
        $data = array_merge($commonData, [
            'school' => $schoolDetails,
            'schDepartment' => $schDepartment
        ]);
        
        return view('app/layouts/school_main',$data);
    }
    
    public function level(){
        
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        //check for login        
        if (!$this->session->get('isLoggedIn')) {
             return redirect()->to('/school/login');
        }
        
        $this->session->set('active_nav','level');
        
        $this->session->set('active_page','level');
        $view = 'app/school/profile/level';
        
        // Load common data
        $commonData = $this->loadCommonData($view);
        
        $schID = $this->session->get('schID');
        
        // Get school details
        $schoolDetails = $this->schoolModel->findFullSchoolDetail($schID);
        
        //Get chool department
        $schLevel = $this->schoolLevelModel->findFullSchoolLevel($schID);
        
        // ✅ LOAD THE HELPER
        helper('stream_display');
        
        // Merge both arrays
        $data = array_merge($commonData, [
            'school' => $schoolDetails,
            'schLevel' => $schLevel
        ]);
        
        return view('app/layouts/school_main',$data);
    }
    
    public function subject(){
        
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        //check for login        
        if (!$this->session->get('isLoggedIn')) {
             return redirect()->to('/school/login');
        }
        
        $this->session->set('active_nav','subject');
        
        $this->session->set('active_page','subject');
        $view = 'app/school/profile/subject';
        
        // Load common data
        $commonData = $this->loadCommonData($view);
        
        $schID = $this->session->get('schID');
        
        // Get school details
        $schoolDetails = $this->schoolModel->findFullSchoolDetail($schID);
        
        //Get chool department
        $schLevel = $this->schoolLevelModel->findFullSchoolLevel($schID);
        
        // Merge both arrays
        $data = array_merge($commonData, [
            'school' => $schoolDetails,
            'schLevel' => $schLevel
        ]);
        
        return view('app/layouts/school_main',$data);
    }
    
    public function loadCommonData($view, $additionalData = []){
        $data = array();
        $schStatus = $this->session->get('schStatus');
        
        $data['schInitial'] = $this->generateAcronym($this->session->get('schName'));
        $data['schName'] = $this->session->get('schName');
        $data['schEmail'] = $this->session->get('schEmail');
        $data['schStatus'] = $this->session->get('schStatus');
        $data['schCat'] = $this->session->get('schCat');
        $data['schCatID'] = $this->session->get('schCatID');
        $data['schAddress'] = $this->session->get('schAddress');
        $data['schPhone'] = $this->session->get('schPhone');
        
        $schLogo = $this->session->get('schLogo');
        
        if($schLogo != ''){
            $logo = $schLogo;
        }else{
            $logo = 'sch_logo_default.png';
        }
        
        $data['schLogo'] = $logo;
        
        $data['_view'] = $view;
        
        // Merge additional data if provided
        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }
        
        return $data;
    }
    
    public function dashboard(){
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        //check for login
        if (!$this->session->get('isLoggedIn')) {
             return redirect()->to('/school/login');
        }
        
        // Check permission
        /*if (!$this->require_access('_school_listing')) {
            $data['_view'] = 'app/auth/access_control';
        } else {
            $data['_view'] = 'app/school/management/index';
        }*/
        
        $view = 'app/school/dashboard/index';
        $data = $this->loadCommonData($view);
        
        return view('app/layouts/school_main',$data);
    }
    
    //display school login form
    public function login(){
        
        // Check if already logged in
        $schID = $this->session->get('schID');
        if ($this->session->get('isLoggedIn') && $schID) {
            // Redirect based on school status
            if ($this->session->get('schStatus') === 'Active') {
                return redirect()->to('/school/dashboard');
            } else {
                return redirect()->to('/school/setup/' . $schID);
            }
        }
        
        $data['_view'] = 'app/school/auth/login';
        
        // Get validation errors from session if they exist
        $data['validation'] = $this->session->getFlashdata('validation');
        $data['error'] = $this->session->getFlashdata('error');
        $data['old'] = $this->session->getFlashdata('old');
        
        
        return view('app/layouts/auth_main', $data);
    }
    
    //process school login form
    public function process_login(){
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
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]|max_length[30]'
        ];

        // Custom error messages
        $messages = [
            'email' => [
                'required' => 'Email is required',
                'valid_email' => 'Please enter a valid email address',
            ],
            'password' => [
                'required' => 'Password is required',
                'min_length' => 'Password must be at least 8 characters',
                'max_length' => 'Password must not exceed 30 characters'
            ]
        ];
        
        // Validate the input
        if (!$this->validate($rules, $messages)) {
            // Validation failed - redirect back with errors and old input
            return redirect()->back()
                ->with('validation', $this->validator)
                ->with('old', $this->request->getPost())
                ->withInput();
        }
        
        // Validation passed - process login
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        try {
            // Check if model is available
            if (!isset($this->schoolModel)) {
                return redirect()->back()
                    ->with('error', 'System configuration error. Please contact administrator.')
                    ->with('old', $this->request->getPost())
                    ->withInput();
            }

            // Find user by email
            //$school = $this->schoolModel->where('sch_email', $email)->first();
            
            // Find school with joined category and district data
            $school = $this->schoolModel
                ->select('school.*, sch_category.*, district.district_name')
                ->join('sch_category', 'sch_category.sch_cat_id = school.sch_cat_id_fk')
                ->join('district', 'district.district_id = school.district_id_fk')
                ->where('school.sch_email', $email)
                ->first();

            if (!$school) {
                return redirect()->back()
                    ->with('error', 'Invalid email. Please enter a registered email.')
                    ->with('old', $this->request->getPost())
                    ->withInput();
            }

            // Verify password
            if (!password_verify($password, $school['sch_password'])) {
                return redirect()->back()
                    ->with('error', 'Invalid password. Please enter correct password.')
                    ->with('old', $this->request->getPost())
                    ->withInput();
            }

            // Check school status
            $schoolStatus = $school['sch_status'] ?? 'Inactive';
            $schID = $school['sch_id'];

            // Set session data
            $sessionData = [
                'schID' => $schID,
                'schStatus' => $schoolStatus,
                'schEmail' => $school['sch_email']  ?? 'Unknown Email',
                'schAddress' => $school['sch_address'] ?? 'Unknown Address',
                'schPhone' => $school['sch_phone'] ?? 'Unknown Phone',
                'schName' => $school['sch_name'] ?? 'Unknown School',
                'schCat' => $school['sch_cat_name'] ?? 'Unknown Category',
                'schCatID' => $school['sch_cat_id'],
                'schDistrict' => $school['district_name'] ?? 'Unknown School',
                'schLogo' => $school['sch_logo'] ?? 'sch_logo_default.png',
                'isLoggedIn' => true,
                'logged_in' => time()
            ];

            $this->session->set($sessionData);

            // Redirect based on school status
            if ($schoolStatus === 'Active') {
                
                //handle redirect to last page visit
                
                return redirect()->to('/school/dashboard')->with('success', 'Welcome back!');
            } else {
                return redirect()->to('/school/setup/' . $schID);
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
    
    public function logout()
    {
        // Optional: Add logout activity logging
        $schID = $this->session->get('schID');
        $schName = $this->session->get('schName');
        
        // Log the logout activity
        log_message('info', "School {$schName} (ID: {$schID}) logged out");
        
        // Destroy all session data
        $this->session->destroy();
        
        // Clear any existing session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        // Redirect to login page with success message
        return redirect()->to('/school/login')->with('success', 'You have been logged out successfully.');
    }
    
    public function setup($id){
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // In your controller method
        helper(['stream_subject']); // Make sure this line is before loading the view
        
        //check for login
        if (!$this->session->get('isLoggedIn')) {
             return redirect()->to('/school/login');
        }
        
        //check for sch ID
        $data = array();
        
        $schStatus = $this->session->get('schStatus');
        
        $data['schInitial'] = $this->generateAcronym($this->session->get('schName'));
        $data['schName'] = $this->session->get('schName');
        $data['schEmail'] = $this->session->get('schEmail');
        $data['schStatus'] = $this->session->get('schStatus');
        $data['schCat'] = $this->session->get('schCat');
        $data['schCatID'] = $this->session->get('schCatID');
        $data['schAddress'] = $this->session->get('schAddress');
        $data['schPhone'] = $this->session->get('schPhone');
        
        $schLogo = $this->session->get('schLogo');
        
        if($schLogo != ''){
            $logo = $schLogo;
        }else{
            $logo = 'sch_logo_default.png';
        }
        
        $data['schLogo'] = $logo;
        
        // Load necessary data
        $data['district'] = $this->districtModel->getAllDistrict();
        $data['province'] = $this->provinceModel->getAllProvince();
        $data['department'] = $this->departmentModel->getAllDepartment();
        $data['level'] = $this->schoolLevelModel->getAllSchoolLevel();
        $data['schLevel'] = $this->schoolLevelModel->getAllSchoolLevel();
        
        // ✅ IMPORTANT: Get validation data from flashdata
        $data['validation'] = session()->getFlashdata('validation');
        $data['old'] = session()->getFlashdata('old');
        $data['error'] = session()->getFlashdata('error');
        $data['success'] = session()->getFlashdata('success');
        
        if($schStatus == 'Step 1 Configured'){
            $data['percent'] = 20;
            $data['_view'] = 'app/school/setup/account_setup_1';
            $data['theme'] = 'danger';
            $data['step'] = 2;
        }else if($schStatus == 'Step 2 Configured'){
            $data['percent'] = 40;
            $data['_view'] = 'app/school/setup/account_setup_2';
            $data['theme'] = 'warning';
            $data['step'] = 3;
        }else if($schStatus == 'Step 3 Configured'){
            $data['percent'] = 60;
            $data['_view'] = 'app/school/setup/account_setup_3';
            $data['theme'] = 'primary';
            $data['step'] = 4;
        }else if($schStatus == 'Step 4 Configured'){
            $data['percent'] = 80;
            $data['_view'] = 'app/school/setup/account_setup_4';
            $data['theme'] = 'info';
            $data['step'] = 5;
        }else{
            return redirect()->to('/dashboard');
        }
        return view('app/layouts/main', $data);
    }
    
    public function configure()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        //check for login
        if (!$this->session->get('isLoggedIn')) {
             return redirect()->to('/school/login');
        }
        
        // Check which button was submitted
        $step_1 = $this->request->getPost('step_btn_1');
        $current_step = $this->request->getPost('current_step');
        // ✅ FIX: Get school ID from session
        $schID = $this->session->get('schID');
        
        
        if ($current_step == '1') {
            log_message('debug', 'Step 1 processing started');
            // Use CodeIgniter's validation
            $rules = [
                'departments' => 'required',
                'levells' => 'required'
            ];
    
            $messages = [
                'departments' => [
                    'required' => 'Please select at least one department.'
                ],
                'levells' => [
                    'required' => 'Please select at least one school level.'
                ]
            ];
    
            if (!$this->validate($rules, $messages)) {
                return redirect()->back()
                    ->with('validation', $this->validator)
                    ->with('old', $this->request->getPost())
                    ->withInput();
            }
            
            log_message('debug', 'Validation passed');
            
            // Process the data
            try {
                
                $departments = $this->request->getPost('departments');
                $levels = $this->request->getPost('levells');
                
                log_message('debug', 'Departments: ' . print_r($departments, true));
                log_message('debug', 'Levels: ' . print_r($levels, true));
                
                // Start database transaction for data consistency
                $db = \Config\Database::connect();
                $db->transStart();
                
                // 1. Save Departments to sch_department table
                if (!empty($departments)) {
                    foreach ($departments as $deptID) {
                        $deptData = [
                            'sch_id_fk' => $schID,
                            'dept_id_fk' => $deptID
                        ];
                        $this->schoolDepartmentModel->insert($deptData);
                        
                    }
                }
                
                // 2. Save Levels to sch_level table
                if (!empty($levels)) {
                    foreach ($levels as $levelID) {
                        $levelData = [
                            'sch_id_fk' => $schID,
                            'level_id_fk' => $levelID
                        ];
                        $this->schoolLevelModel->insert($levelData); // ✅ Fixed variable name
                    }
                }
                
                // 3. Update school status to mark step as completed
                $this->schoolModel->update($schID, [
                    'sch_status' => 'Step 2 Configured'
                ]);
                
                // Commit transaction
                $db->transComplete();
                
                if ($db->transStatus() === FALSE) {
                    throw new \Exception('Database transaction failed');
                }
                
                // Update session status
                $this->session->set('schStatus', 'Step 2 Configured');
                
                return redirect()->to('/school/setup/'.$schID)
                    ->with('success', 'School departments and levels configured successfully!');
                    
            } catch (\Exception $e) {
                log_message('error', 'Configuration error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'An error occurred while saving configuration. Please try again.')
                    ->withInput();
            }
        }else if($current_step == '2'){
            // Get form data
            $current_step = $this->request->getPost('current_step');
            $subjects = $this->request->getPost('subject') ?? [];
            $departments = $this->request->getPost('subDept') ?? [];
            $level_ids = $this->request->getPost('level_ids') ?? [];
            
            // Get school ID from session
            $schID = session()->get('schID');
            
            if (!$schID) {
                session()->setFlashdata('error', 'School session not found. Please login again.');
                return redirect()->back()->withInput();
            }
            
            // Perform server-side validation
            $validationErrors = $this->validateSubjectDepartment($subjects, $departments, $level_ids, $schID);
            
            if (!empty($validationErrors)) {
                // Store errors as a single string or array
                $errorMessage = implode('<br>', $validationErrors);
                session()->setFlashdata('error', $errorMessage);
                
                // Store form data for repopulation
                return redirect()->back()->withInput();
            }
            
            // If validation passes, process the data
            return $this->processSubjectAssignments($subjects, $departments, $schID);
            
        }else if ($current_step == '3') {
            // Use CodeIgniter's validation
            $rules = [
                'stream_tag' => 'required',
                'streams' => 'required'
            ];
    
            $messages = [
                'stream_tag' => [
                    'required' => 'Please select a stream tag.'
                ],
                'streams' => [
                    'required' => 'Please select a number of stream the respective level.'
                ]
            ];
    
            if (!$this->validate($rules, $messages)) {
                return redirect()->back()
                    ->with('validation', $this->validator)
                    ->with('old', $this->request->getPost())
                    ->withInput();
            }
            
            
            
            try{
                $tag = $this->request->getPost('stream_tag');
                if($tag == 'numeric'){
                    $tagArray = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10'];
                }else{
                    $tagArray = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                }
                
                $streamsArray = $this->request->getPost('streams');
                
                $levels = $this->schoolLevelModel->getAllSchoolLevel();
                
                $allFilled = true;

                for($i = 0; $i < count($streamsArray); $i++) {
                    if (empty($streamsArray[$i])) {
                        $allFilled = false;
                        break; // Stop checking once we find one empty
                    }
                }
                
                if($allFilled){
                    // 1. Save stream to stream table
                    if (!empty($streamsArray)) {
                        $j = 0;
                        foreach($levels as $row){
                            $sch_level_id = $row['sch_level_id'];    
                            for($i=0;$i < $streamsArray[$j]; $i++){
                                $postData = [
                                    'sch_level_id_fk' => $sch_level_id,
                                    'stream_name' => $row['level_name'].$tagArray[$i]
                                ];
                                $this->schoolStreamModel->insert($postData);
                            }
                            $j++;
                        }
                        
                    }
                    
                    // 2. Update school status to mark step as completed
                    $this->schoolModel->update($schID, ['sch_status' => 'Step 4 Configured']);
                    
                    // Update session status
                    $this->session->set('schStatus', 'Step 4 Configured');
                    
                    return redirect()->to('/school/setup/'.$schID)
                        ->with('success', 'School streams configured successfully!');
                }else{
                    return redirect()->back()->with('error', 'Invalid form submission. Looks like you have not fully configure number of stream for all school level.');
                }
            }catch(\Exception $e) {
                log_message('error', 'Configuration error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'An error occurred while saving configuration. Please try again.')
                    ->withInput();
            }
        }else if ($current_step == '4') {
            // 2. Update school status to mark step as completed
            $update = $this->schoolModel->update($schID, ['sch_status' => 'Active']);
            
            if($update){
                // Update session status
                $this->session->set('schStatus', 'Active');
                
                return redirect()->to('/school/overview')
                    ->with('success', 'School curriculum configured successfully!');
            }else{
                return redirect()->to('/school/overview')
                    ->with('error', 'Fail to update school curriculum configuration step.');
            }
            
        }/*else if ($current_step == '4'){
            $message = '';
            // Store all POST data for repopulation
            //$data['old'] = $this->request->getPost();
            
            // Use CodeIgniter's validation
            $rules = [
                'fname' => 'required|min_length[3]|max_length[100]',
                'lname' => 'required|min_length[3]|max_length[100]',
                'gender' => 'required',
                'dob' => 'required',
                'province' => 'permit_empty|integer',
                'email' => 'required|valid_email|is_unique[users.email]',
                'phone' => 'required|min_length[7]|max_length[7]',
                'address' => 'required|min_length[5]|max_length[255]',
                'password' => 'required|min_length[8]|matches[re-type-password]',
                're-type-password' => 'required',
            ];
            
            // Get the input value for 'province'
            $province = $this->request->getPost('province');
            
            $getDistrict = $this->districtModel->getDistrictByProvince($province);
            //$data['provinceDistrict'] = $getDistrict;
            
            // Add district validation only if province is selected
            if (!empty($province)) {
                $rules['district'] = 'required';
            }
    
            $messages = [
                'fname' => [
                    'required' => 'Please enter first name.',
                    'min_length' => 'First name must be at leat 3 character long',
                    'max_length' => 'First name must not exceed 100 character'
                ],
                'lname' => [
                    'required' => 'Please enter last name.',
                    'min_length' => 'Last name must be at leat 3 character long',
                    'max_length' => 'Last name must not exceed 100 character'
                ],
                'gender' => [
                    'required' => 'Please select a gender'
                ],
                'dob' => [
                    'required' => 'Please select a date of birth'
                ],
                'province' => [
                    'required' => 'Please select a province'
                ],
                'district' => [
                    'required' => 'Please select a district'
                ],
                'email' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please enter a valid email address',
                    'is_unique' => 'This email is already registered'
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
                'password' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 8 characters',
                    'matches' => 'Passwords do not match'
                ],
                're-type-password' => [
                    'required' => 'Please re-type your password'
                ]
            ];
    
            if (!$this->validate($rules, $messages)) {
                return redirect()->back()
                    ->with('validation', $this->validator)
                    ->with('old', $this->request->getPost())
                    ->with('provinceDistrict',$getDistrict) 
                    ->withInput();
            }else{
                // Validation passed - process the data
                $postData = $this->request->getPost();
                $plainPassword = $this->request->getPost('password');
                
                // Encrypt password using CodeIgniter 4's built-in password_hash
                $encryptedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
                
                $dob_input = $this->request->getPost('dob');
                $dob_mysql = null;
                
                if (!empty($dob_input)) {
                    try {
                        $date = new \DateTime($dob_input); // Add backslash
                        $dob_mysql = $date->format('Y-m-d');
                    } catch (\Exception $e) { // Also fix Exception
                        // Log error and handle appropriately
                        log_message('error', 'DOB conversion error: ' . $e->getMessage());
                        return redirect()->back()->with('error', 'Invalid date format');
                    }
                }
                
                // Add validation for district existence
                $districtId = $this->request->getPost('district');
                $districtExists = $this->districtModel->find($districtId);
                
                if (!$districtExists) {
                    return redirect()->back()->with('error', 'Invalid district selected.')->withInput();
                }
                
                $emailAddress = $this->request->getPost('email');
                
                $userData = [
                    'district_id_fk'  => $districtId,
                    'password'  => $encryptedPassword,
                    'fname'     => $this->request->getPost('fname'),
                    'lname'     => $this->request->getPost('lname'),
                    'oname'     => $this->request->getPost('oname'),
                    'gender'    => $this->request->getPost('gender'),
                    'dob'       => $dob_mysql,
                    'address'   => $this->request->getPost('address'),
                    'phone'     => $emailAddress,
                    'email'     => $this->request->getPost('email'),
                    'created_date' => date('Y-m-d'),
                    'created_time' => time(),
                    'online_status' => 'Offline',
                    'user_status'  => 'Active',
                ];
                
                try{
                    // Temporary debug - remove after fixing
                    log_message('debug', 'User data to insert: ' . print_r($userData, true));

                    $add = $this->userModel->addUser($userData);
                    
                    // Check what insert returns
                    log_message('debug', 'Insert result: ' . print_r($add, true));
                
                    if($add){
                        $message .= 'Successfully added user data into the database.';
                        
                        //update school status
                        $update = $this->schoolModel->update($schID, ['sch_status' => 'Active']);
                        
                        if($update){
                            // Update session status
                            $this->session->set('schStatus', 'Active');
                            
                            $message .= ' School status now being activated.';
                        }else{
                            $message .= '<font color="red"> Fail to update school status.</font>';
                        }
                        
                        //add user role
                        $roleData = [
                            'user_id_fk' => $add,
                            'role_id_fk' => 2
                        ];
                        
                        $addRole = $this->userRoleModel->addUserRole($roleData);
                        
                        if($addRole){
                            $message .= ' Successfully added user role.';
                        }else{
                            $message .= '<font color="red"> Fail to add user role.</font>';
                        }
                        
                        //send email
                        $emailService = new EmailService();
                        
                        $emailData = [
                            'fname' => $this->request->getPost('fname'),
                            'lname' => $this->request->getPost('lname'),
                            'email' => $emailAddress,
                            'plain_password' => $plainPassword // Make sure you have this variable
                        ];
                        
                        $schoolName = session('schName') ?? 'Navuli Fiji';
                        
                        $emailResult = $emailService->sendWelcomeEmail($emailData, $schoolName);
                        
                        if ($emailResult['status']) {
                            $message .= ' Welcome email has been sent to the '.$emailAddress.'.';
                        } else {
                            $message .= '<font color="red"> Fail to send email notification to user.</font>';
                            // Log the email error for debugging
                            log_message('error', 'Email error: ' . $emailResult['message']);
                        }
                        //redirect
                        return redirect()->to('/school/dashboard')->with('success', $message);
                    }else{
                        // Get database error if available
                        $db = db_connect();
                        $error = $db->error();
                        
                        /// Check what insert returns
                        log_message('debug', 'Insert result: ' . print_r($add, true));
                        
                        $errorMessage = 'Failed to add user data to the database. ';
                        
                        if (!empty($error['message'])) {
                            // Check for common database errors
                            if (strpos($error['message'], 'Duplicate entry') !== false) {
                                $errorMessage .= 'User with this email or username already exists.';
                            } elseif (strpos($error['message'], 'foreign key constraint') !== false) {
                                $errorMessage .= 'Invalid reference data (e.g., role not found).';
                            } elseif (strpos($error['message'], 'Data too long') !== false) {
                                $errorMessage .= 'Data exceeds column length limit.';
                            } else {
                                $errorMessage .= 'Database Error: ' . $error['message'];
                            }
                        } else {
                            $errorMessage .= 'Please try again.';
                        }
                        
                        return redirect()->to('/school/dashboard')->with('error', $errorMessage);
                    }
                }catch (\Exception $e) {
                    // Log the full exception for debugging
                    log_message('error', 'Exception in user creation: ' . $e->getMessage());
                    log_message('error', 'Exception trace: ' . $e->getTraceAsString());
                    return redirect()->back()->with('error', 'System error: ' . $e->getMessage())->withInput();
                }
                
                
            } 
        }*/else{
            return redirect()->to('/dashboard');
        }
    }
    
    /**
     * Validate subject-department assignments
     */
    private function validateSubjectDepartment($subjects, $departments, $level_ids, $schID)
    {
        $errors = [];
        
        // Check if any level exists
        if (empty($level_ids)) {
            $errors[] = 'No school levels found.';
            return $errors;
        }
        
        // Validate each level
        foreach ($level_ids as $level_id) {
            $levelSubjects = $subjects[$level_id] ?? [];
            $levelDepartments = $departments[$level_id] ?? [];
            
            // Get available subjects for this level
            $availableSubjects = $this->subjectModel->getSubjectByLevel((int)$level_id);
            
            // If there are available subjects but none selected, show error
            if (empty($levelSubjects) && !empty($availableSubjects)) {
                // Get level name for better error message
                $level = $this->levelModel->find($level_id);
                $levelName = $level['level_name'] ?? "Level ID: {$level_id}";
                
                $errors[] = "At least one subject must be selected for {$levelName}";
                continue;
            }
            
            // Rule 2 & 3: Check subject-department pairs
            foreach ($levelSubjects as $subjectId) {
                // Check if subject exists and belongs to this level
                $subjectExists = false;
                $subjectName = '';
                
                foreach ($availableSubjects as $subject) {
                    if ($subject['subject_id'] == $subjectId) {
                        $subjectExists = true;
                        $subjectName = $subject['subject_name'];
                        break;
                    }
                }
                
                if (!$subjectExists) {
                    $errors[] = "Invalid subject ID: {$subjectId} for this level";
                    continue;
                }
                
                // Check if department is selected for this subject
                if (!isset($levelDepartments[$subjectId]) || empty($levelDepartments[$subjectId])) {
                    $errors[] = "No department selected for subject: {$subjectName}";
                } else {
                    // Validate department exists and belongs to this school
                    $department = $this->schoolDepartmentModel->where('sch_dept_id', $levelDepartments[$subjectId])
                                          ->where('sch_id_fk', $schID)
                                          ->first();
                    
                    if (!$department) {
                        $errors[] = "Invalid department selected for subject: {$subjectName}";
                    }
                }
            }
            
            // Check for departments selected without subjects
            foreach ($levelDepartments as $subjectId => $deptId) {
                if (!empty($deptId) && !in_array($subjectId, $levelSubjects)) {
                    $errors[] = "Department selected for non-checked subject ID: {$subjectId}";
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Process and save subject assignments
     */
    private function processSubjectAssignments($subjects, $departments, $schID)
    {
        $successCount = 0;
        $errorMessages = [];
        
        // Begin transaction for data consistency
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            
            foreach ($subjects as $levelId => $levelSubjects) {
                foreach ($levelSubjects as $subjectId) {
                    $deptId = $departments[$levelId][$subjectId] ?? null;
                    
                    if ($deptId) {
                        // Prepare data for insertion based on your table structure
                        $data = [
                            'sch_id_fk' => $schID,
                            'subject_id_fk' => $subjectId,
                            'sch_dept_id_fk' => $deptId,
                            'sch_sub_status' => 'Active',
                        ];
                        
                        if ($this->schoolSubjectModel->insert($data)) {
                            $successCount++;
                        } else {
                            $errorMessages[] = "Failed to save subject ID: {$subjectId}";
                        }
                    }
                }
            }
            
            // Commit transaction
            $db->transCommit();
            
            if ($successCount > 0) {
                $message = "Successfully saved {$successCount} subject assignments.";
    
                // Store saved subject IDs for highlighting
                $savedSubjects = [];
                foreach ($subjects as $levelId => $levelSubjects) {
                    foreach ($levelSubjects as $subjectId) {
                        $deptId = $departments[$levelId][$subjectId] ?? null;
                        if ($deptId) {
                            $savedSubjects[] = $subjectId;
                        }
                    }
                }
                
                // Store in session for one-time use
                session()->setFlashdata('saved_subjects', $savedSubjects);
                
                //session()->setFlashdata('success', $message);
                
                // Update session status
                $this->session->set('schStatus', 'Step 3 Configured');
                
                $this->schoolModel->update($schID,array('sch_status'=>'Step 3 Configured'));
                
                return redirect()->to('/school/setup/'.$schID)->with('success', $message);
            } else {
                session()->setFlashdata('error', 'No subject assignments were saved. Please try again.');
                return redirect()->back()->withInput();
            }
            
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            
            log_message('error', 'School subject assignment error: ' . $e->getMessage());
            
            session()->setFlashdata('error', 'An error occurred while saving. Please try again.');
            return redirect()->back()->withInput();
        }
    }
    
    function complete(){
        $data['_view'] = 'app/school/setup/account_setup_1';
        return view('app/layouts/school_main', $data);
    }
    
    function get_level_subject(){
        $html = '';
        
        // ✅ Get the POST data properly
        $levelID = $this->request->getPost('level');
        $streamID = $this->request->getPost('stream');
        
        $count = 0;
        
        //$getSubject = $this->subjectModel->getSubjectByLevel($levelID);
        $getSubject = $this->schoolSubjectModel->getSubjectByLevel($levelID);
        
        //$html .= 'Level ID = '.$levelID.' <br>Stream ID = '.$streamID;
        
        // Debug: Log the received data
        log_message('debug', 'Received level ID: ' . $levelID);
        
        if (!empty($levelID)) {
            if(!empty($getSubject)){
                $html .= '<div class="row">';
                foreach($getSubject as $row){
                    $subjectId = $row['sch_sub_id'];
                    $isCoreSubject = !empty($this->streamCoreSubjectModel->getStreamCoreSubjectEntry($subjectId, $streamID));
                    $isOptionalSubject = !empty($this->streamOptionalSubjectModel->getStreamOptionalSubjectEntry($subjectId, $streamID));
                    // Show subject only if it's NOT a core subject AND NOT an optional subject
                    if(!$isCoreSubject && !$isOptionalSubject){
                        $html .= '<div class="col-md-4 mb-5"><div class="form-check">
                            <input class="form-check-input" type="checkbox" name="subjects[]" value="'.$row['sch_sub_id'].'" />
                            <label class="form-check-label" for="flexCheckDefault">'.$row['subject_name'].'</label>
                        </div></div>';
                        $count++;
                    }
                }
                $html .= '';
                if($count > 0){
                    $html .= '</div><div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save changes</button>';
                }else{
                    $html .= '<div class="d-flex flex-column"><div class="alert alert-warning">All registered level subjects has been assigned .</div>
                    </div></div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>';
                }
                $html .= '</div>
                </form>';
            }else{
                $html .= '<div class="alert alert-warning">No school subject found for the corresponding school level!</div>';
            }
        } else {
            $html .= '<div class="alert alert-warning">No level ID received!</div>';
        }
        
        // ✅ Return the HTML response
        return $this->response->setBody($html);
    }
    
    /*function get_level_subject(){
    $html = '';
    
    // ✅ Get the POST data properly
    $levelID = $this->request->getPost('level');
    $streamID = $this->request->getPost('stream');
    
    // 🔍 DEBUG 1: Log received parameters
    log_message('debug', '=== get_level_subject() STARTED ===');
    log_message('debug', 'POST Data - level: ' . $levelID . ', stream: ' . $streamID);
    log_message('debug', 'Request Method: ' . $this->request->getMethod());
    
    // 📊 DEBUG 2: Validate input data
    if (empty($levelID) || empty($streamID)) {
        log_message('warning', 'Missing required parameters: level=' . $levelID . ', stream=' . $streamID);
        $html .= '<div class="alert alert-warning">Missing level or stream ID!</div>';
        log_message('debug', '=== get_level_subject() ENDED WITH ERROR ===');
        return $this->response->setBody($html);
    }
    
    // Start timer for performance monitoring
    $startTime = microtime(true);
    
    try {
        // 📝 DEBUG 3: Log database call start
        log_message('debug', 'Calling getSubjectByLevel with levelID: ' . $levelID);
        
        //$getSubject = $this->subjectModel->getSubjectByLevel($levelID);
        $getSubject = $this->schoolSubjectModel->getSubjectByLevel($levelID);
        
        // 📊 DEBUG 4: Log database results
        log_message('debug', 'getSubjectByLevel returned ' . count($getSubject) . ' subjects');
        
        if (ENVIRONMENT === 'development') {
            log_message('debug', 'Subjects data sample: ' . json_encode(array_slice($getSubject, 0, 3)));
        }
        
        // 🔍 DEBUG 5: Check if subjects were found
        if (empty($getSubject)) {
            log_message('info', 'No subjects found for level ID: ' . $levelID);
            $html .= '<div class="alert alert-info">No subjects found for this level.</div>';
            $html .= '<div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>';
            
            $endTime = microtime(true);
            log_message('debug', 'Function execution time: ' . round(($endTime - $startTime) * 1000, 2) . 'ms');
            log_message('debug', '=== get_level_subject() COMPLETED - NO SUBJECTS ===');
            
            return $this->response->setBody($html);
        }
        
        // 📝 DEBUG 6: Log core/optional subject checks
        log_message('debug', 'Checking core/optional status for ' . count($getSubject) . ' subjects');
        
        $html .= '<div class="row">';
        $subjectCount = 0;
        $filteredSubjectCount = 0;
        
        foreach($getSubject as $row){
            $subjectCount++;
            $subjectId = $row['subject_id'];
            
            // 🔍 DEBUG 7: Log subject being processed
            if (ENVIRONMENT === 'development') {
                log_message('debug', 'Processing subject #' . $subjectCount . ': ID=' . $subjectId . ', Name=' . $row['subject_name']);
            }
            
            // Check if core subject
            log_message('debug', 'Checking if subject ' . $subjectId . ' is core subject for stream ' . $streamID);
            $isCoreSubject = !empty($this->streamCoreSubjectModel->getStreamCoreSubjectEntry($subjectId, $streamID));
            log_message('debug', 'Core subject check result: ' . ($isCoreSubject ? 'YES' : 'NO'));
            
            // Check if optional subject
            log_message('debug', 'Checking if subject ' . $subjectId . ' is optional subject for stream ' . $streamID);
            $isOptionalSubject = !empty($this->streamOptionalSubjectModel->getStreamOptionalSubjectEntry($subjectId, $streamID));
            log_message('debug', 'Optional subject check result: ' . ($isOptionalSubject ? 'YES' : 'NO'));
            
            // Show subject only if it's NOT a core subject AND NOT an optional subject
            if(!$isCoreSubject && !$isOptionalSubject){
                $filteredSubjectCount++;
                $html .= '<div class="col-md-4 mb-5"><div class="form-check">
                    <input class="form-check-input" type="checkbox" name="subjects[]" value="'.$row['subject_id'].'" />
                    <label class="form-check-label" for="flexCheckDefault">'.$row['subject_name'].'</label>
                </div></div>';
                
                // 🔍 DEBUG 8: Log included subjects
                log_message('debug', 'Subject ' . $row['subject_name'] . ' (ID: ' . $subjectId . ') included in list');
            } else {
                // 🔍 DEBUG 9: Log excluded subjects
                log_message('debug', 'Subject ' . $row['subject_name'] . ' (ID: ' . $subjectId . ') excluded - Already core/optional');
            }
        }
        
        $html .= '</div>';
        
        // 📊 DEBUG 10: Log filtering results
        log_message('info', 'Subject filtering complete: ' . $filteredSubjectCount . ' of ' . $subjectCount . ' subjects available for selection');
        
        if ($filteredSubjectCount === 0) {
            log_message('info', 'All subjects are already assigned as core or optional');
            $html .= '<div class="alert alert-info mt-3">All subjects for this level are already assigned to this stream.</div>';
        }
        
        $html .= '<div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" ' . ($filteredSubjectCount === 0 ? 'disabled' : '') . '>Save changes</button>
        </div>
        </form>';
        
    } catch (\Exception $e) {
        // ❌ DEBUG 11: Log any exceptions
        log_message('error', 'Exception in get_level_subject: ' . $e->getMessage());
        log_message('error', 'Exception trace: ' . $e->getTraceAsString());
        
        $html .= '<div class="alert alert-danger">An error occurred while loading subjects. Please try again.</div>';
        $html .= '<div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        </div>';
    }
    
    // 📊 DEBUG 12: Performance metrics
    $endTime = microtime(true);
    $executionTime = round(($endTime - $startTime) * 1000, 2);
    log_message('debug', 'Total function execution time: ' . $executionTime . 'ms');
    
    if ($executionTime > 1000) {
        log_message('warning', 'Function execution time exceeds 1 second: ' . $executionTime . 'ms');
    }
    
    log_message('debug', '=== get_level_subject() COMPLETED ===');
    
    // ✅ Return the HTML response
    return $this->response->setBody($html);
}*/
    
    function addoptionalsubject(){
        $opt_num = $this->streamOptionalSubjectModel->getNextOptionNum();
        
        $streamID = $this->request->getPost('streamID');
        $subjectArray = $this->request->getPost('subjects');
        $schID = $this->session->get('schID');
        
        // Check if at least two subjects are selected
        $atLeastTwoSubjects = (is_array($subjectArray) && count($subjectArray) >= 2);
        
        if($atLeastTwoSubjects){
            $count = 0;
            for($i = 0; $i < count($subjectArray); $i++){
                $postData = [
                    'sch_sub_id_fk' => $subjectArray[$i],
                    'stream_id_fk' => $streamID,
                    'option_num' => $opt_num // Add the option number
                ];
                $add = $this->streamOptionalSubjectModel->insert($postData); // Make sure you're using the correct model
                if($add){
                    $count++;
                }
            }
            
            if($count > 0){
                if($count > 1){
                    $s = 's';
                }else{
                    $s = '';
                }
                return redirect()->to('/school/setup/'.$schID)->with('success', 'Successfully added '.$count.' optional subject'.$s.'.');
            }else{
                return redirect()->to('/school/setup/'.$schID)->with('error', 'Cannot add school stream optional subject.');
            }
        }else{
            return redirect()->to('/school/setup/'.$schID)->with('error', 'Please select at least two subjects.');
        }
    }
    
    function remove_core(){
        
        //check for login
        if (!$this->session->get('isLoggedIn')) {
             return redirect()->to('/school/login');
        }
        
        $id = $this->request->getPost('coreSubID');
        $schID = $this->session->get('schID');
        
        $find = $this->streamCoreSubjectModel->getStreamCoreSubject($id);
        
        if(empty($find)){
            return redirect()->to('/school/setup/'.$schID)->with('error', 'The optional subject combination you are trying to delete does not exist.');
        }else{
            $delete = $this->streamCoreSubjectModel->deleteStreamCoreSubject($id);
            
            if($delete){
                return redirect()->to('/school/setup/'.$schID)->with('success', 'Successfully deleted stream core subject.');
            }else{
                return redirect()->to('/school/setup/'.$schID)->with('error', 'Fail to delete stream core subject.');
            }
        }
    }
    
    function remove_optional(){
        
        //check for login
        if (!$this->session->get('isLoggedIn')) {
             return redirect()->to('/school/login');
        }
        
        $id = $this->request->getPost('optID');
        $schID = $this->session->get('schID');
        
        $find = $this->streamOptionalSubjectModel->getStreamOptionalSubjectByOptNum($id);
        
        if(empty($find)){
            return redirect()->to('/school/setup/'.$schID)->with('error', 'The optional subject combination you are trying to delete does not exist.');
        }else{
            $delete = $this->streamOptionalSubjectModel->deleteStreamOptionalSubjectByOptNum($id);
            
            if($delete){
                return redirect()->to('/school/setup/'.$schID)->with('success', 'Successfully deleted stream optional subject combination.');
            }else{
                return redirect()->to('/school/setup/'.$schID)->with('error', 'Fail to delete stream subject combination.');
            }
        }
    }
    
    //rename this function to a proper name
    public function update($id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/school/login');
        }

        $roleCatID    = (int) $this->session->get('roleCatID');
        $schIDSession = (int) $this->session->get('schID');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $isTeacher    = $roleCatID === 3;

        // Allow: Super Admin, or Teacher assigned to a school
        if (!$isSuperAdmin && !($isTeacher && $schIDSession > 0)) {
            $view = 'app/auth/view_access_error';
            $data = $this->loadCommonData($view);
            return view('app/layouts/school_main', $data);
        }

        $school = $this->schoolModel->find($id);
        if (!$school) {
            return redirect()->to('school/overview')->with('error', 'The school you want to update profile cannot be found in the database.');
        }

        $this->session->set('active_page', 'update');
        

        // Check permission
        if (!$this->require_access('_edit_school')) {
            $data['_view'] = 'app/auth/access_control';
        } else {
            $view = 'app/school/profile/update';
        }

        $commonData = $this->loadCommonData($view);

        $schoolDetails     = $this->schoolModel->findFullSchoolDetail($id);
        $currentProvinceId = $schoolDetails['province_id'] ?? null;

        $data = array_merge($commonData, [
            'school'           => $schoolDetails,
            'validation'       => session()->getFlashdata('validation'),
            'schoolCategory'   => $this->schoolCategoryModel->getAllSchoolCategory(),
            'province'         => $this->provinceModel->getAllProvince(),
            'provinceDistrict' => $this->districtModel->getDistrictByProvince($currentProvinceId),
            'isSuperAdmin'     => $isSuperAdmin,
        ]);

        $layout = $isSuperAdmin ? 'app/layouts/main' : 'app/layouts/school_main';
        return view($layout, $data);
    }

    public function processUpdate($id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/school/login');
        }

        $school = $this->schoolModel->find($id);
        if (!$school) {
            return redirect()->to('school/overview')->with('error', 'School not found.');
        }

        $rules = [
            'sch_name'    => "required|min_length[3]|max_length[200]|is_unique[school.sch_name,sch_id,{$id}]",
            'sch_email'   => "required|valid_email|max_length[100]|is_unique[school.sch_email,sch_id,{$id}]",
            'sch_phone'   => 'required|exact_length[7]|numeric',
            'sch_address' => 'required|min_length[6]|max_length[255]',
            'sch_motto'   => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        $updateData = [
            'sch_name'            => $this->request->getPost('sch_name'),
            'sch_email'           => $this->request->getPost('sch_email'),
            'sch_phone'           => $this->request->getPost('sch_phone'),
            'sch_address'         => $this->request->getPost('sch_address'),
            'sch_motto'           => $this->request->getPost('sch_motto'),
            'sch_cat_id_fk'       => $this->request->getPost('sch_cat_id_fk') ?: null,
            'district_id_fk'      => $this->request->getPost('district_id_fk') ?: null,
            'sch_primary_color'   => $this->request->getPost('sch_primary_color') ?: null,
            'sch_secondary_color' => $this->request->getPost('sch_secondary_color') ?: null,
            'sch_y_coord'         => $this->request->getPost('sch_y_coord') ?: null,
            'sch_x_coord'         => $this->request->getPost('sch_x_coord') ?: null,
        ];

        if ($isSuperAdmin) {
            $validStatuses = ['Active', 'Inactive', 'Step 1 Configured', 'Step 2 Configured', 'Step 3 Configured', 'Step 4 Configured'];
            $postedStatus  = $this->request->getPost('sch_status');
            if ($postedStatus && in_array($postedStatus, $validStatuses, true)) {
                $updateData['sch_status'] = $postedStatus;
            }
        }

        try {
            $file = $this->request->getFile('sch_logo');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = 'logo_' . $id . '_' . random_int(100000, 999999) . '.' . $file->getExtension();
                $file->move('uploads/school/logo', $newName);
                $updateData['sch_logo'] = $newName;

                $oldLogo = $school['sch_logo'];
                if ($oldLogo && file_exists('uploads/school/logo/' . $oldLogo)) {
                    unlink('uploads/school/logo/' . $oldLogo);
                }
            }

            $this->schoolModel->update($id, $updateData);
            //return redirect()->to('school/update/'.$id)->with('success', 'School profile updated successfully.');
            return redirect()->to('school')->with('success', 'School profile updated successfully.');

        } catch (\Exception $e) {
            log_message('error', 'School processUpdate error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating. ' . $e->getMessage());
        }
    }



    /*public function edit($id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $school = $this->schoolModel->find($id);
        if (!$school) {
            return redirect()->to('school')->with('error', 'School not found.');
        }

        // GET — show the edit form
        if ($this->request->getMethod() === 'get') {
            $this->setPageData('Edit School', 'School', 'Edit School');

            $data['_view']          = 'app/school/management/edit';
            $data['school']         = $school;
            $data['schoolCategory'] = $this->schoolCategoryModel->getAllSchoolCategory();
            $data['plans']          = $this->planModel->getAllPlan();
            $data['province']       = $this->provinceModel->getAllProvince();
            $data['districts']      = $this->districtModel->getDistrictByProvince($school['district_id_fk'] ?? null);
            $data['validation']     = session()->getFlashdata('validation');

            return view('app/layouts/main', $data);
        }

        // POST — validate and save
        $rules = [
            'sch_name'    => 'required|min_length[3]|max_length[200]',
            'sch_email'   => "required|valid_email|max_length[100]|is_unique[school.sch_email,sch_id,{$id}]",
            'sch_phone'   => 'required|exact_length[7]|numeric',
            'sch_address' => 'required|min_length[6]|max_length[255]',
            'sch_motto'   => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }
    
        $updateData = [
            'sch_name'      => $this->request->getPost('sch_name'),
            'sch_email'     => $this->request->getPost('sch_email'),
            'sch_phone'     => $this->request->getPost('sch_phone'),
            'sch_address'   => $this->request->getPost('sch_address'),
            'sch_motto'     => $this->request->getPost('sch_motto'),
            'sch_status'    => $this->request->getPost('sch_status'),
            'sch_cat_id_fk' => $this->request->getPost('sch_cat_id_fk'),
        ];

        try {
            $file = $this->request->getFile('sch_logo');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = 'logo_' . $id . '_' . random_int(100000, 999999) . '.' . $file->getExtension();
                $file->move('uploads/school/logo', $newName);
                $updateData['sch_logo'] = $newName;

                $oldLogo = $school['sch_logo'];
                if ($oldLogo && file_exists('uploads/school/logo/' . $oldLogo)) {
                    unlink('uploads/school/logo/' . $oldLogo);
                }
            }

            $this->schoolModel->update($id, $updateData);

            return redirect()->to('school/detail/' . $id)->with('success', 'School updated successfully.');

        } catch (\Exception $e) {
            log_message('error', 'School update error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating. ' . $e->getMessage());
        }
    }*/
    
    public function add()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $this->session->set('prevUrl', $this->session->get('url'));
        $this->session->set('url', 'school/add');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Set page metadata
        $this->setPageData('Add New School', 'School', 'Add School');
        
        // Check permission
        $accessCheck = $this->require_access('_add_school');
        if ($accessCheck !== true) {
            $data['_view'] = 'app/auth/access_control';
        } else {
            // Get all roles for dropdown
            $data['roles'] = $this->roleModel->getAllRoles();
            
            $data['schoolCategory'] = $this->schoolCategoryModel->getAllSchoolCategory();
            $data['plans'] = $this->planModel->getAllPlan();
            
            // Get all districts for dropdown
            $data['districts'] = $this->districtModel->findAll();
            $data['province'] = $this->provinceModel->getAllProvince();
            
            // Get the input value for 'province'
            $province = $this->session->get('province');
            $data['povince_id'] = $province;
            
            $getDistrict = $this->districtModel->getDistrictByProvince($province);
            $data['provinceDistrict'] = $getDistrict;
            
            $data['_view'] = 'app/school/management/add';
        }
        
        return view('app/layouts/main', $data);
    }
    
    /**
     * Store new school
     */
    public function store()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Check permission
        if (!$this->require_access('_add_school')) {
            return redirect()->to('dashboard')->with('error', 'You do not have permission to add schools.');
        }
        
        // Validation rules
        $rules = [
            'school_name' => 'required|min_length[3]|max_length[200]|is_unique[school.sch_name]',
            'school_category' => 'required|integer',
            'email' => 'required|valid_email|is_unique[school.sch_email]',
            'phone' => 'required|exact_length[7]|numeric',
            'province' => 'permit_empty|integer',
            'district' => 'permit_empty|integer',
            'address' => 'required|min_length[10]|max_length[500]',
            'plan_id' => 'required|integer',
            'plan_term' => 'required|integer|in_list[1,12,24,36]',
            'payment_mode' => 'required|in_list[Cash,Check,Bank Transfer,Master Card,MPaisa,MyCash]',
            'school_logo' => 'if_exist|uploaded[school_logo]|max_size[school_logo,2048]|ext_in[school_logo,jpg,jpeg,png,gif]'
        ];
        
        $messages = [
            'school_name' => [
                'required' => 'School name is required',
                'min_length' => 'School name must be at least 3 characters',
                'max_length' => 'School name cannot exceed 200 characters',
                'is_unique' => 'This school is already registered'
            ],
            'school_category' => [
                'required' => 'Please select a school category',
                'integer' => 'Invalid school category'
            ],
            'email' => [
                'required' => 'Email is required',
                'valid_email' => 'Please enter a valid email address',
                'is_unique' => 'This email is already registered'
            ],
            'phone' => [
                'required' => 'Phone number is required',
                'exact_length' => 'Phone number must be exactly 7 digits',
                'numeric' => 'Phone number must contain only numbers'
            ],
            'province' => [
                'required' => 'Please select a province',
                'integer' => 'Invalid province'
            ],
            'district' => [
                'required' => 'Please select a district',
                'integer' => 'Invalid district'
            ],
            'address' => [
                'required' => 'Address is required',
                'min_length' => 'Address must be at least 10 characters',
                'max_length' => 'Address cannot exceed 500 characters'
            ],
            'plan_id' => [
                'required' => 'Please select a subscription plan',
                'integer' => 'Invalid plan selected'
            ],
            'plan_term' => [
                'required' => 'Please select a subscription term',
                'integer' => 'Invalid term',
                'in_list' => 'Please select a valid term (1, 12, 24, or 36 months)'
            ],
            'payment_mode' => [
                'required' => 'Please select a payment mode',
                'in_list' => 'Invalid payment mode selected'
            ],
            'school_logo' => [
                'uploaded' => 'Please upload a school logo',
                'max_size' => 'Logo file size cannot exceed 2MB',
                'ext_in' => 'Only JPG, JPEG, PNG, and GIF files are allowed'
            ]
        ];
        
        $this->session->set('province',$this->request->getPost('province'));
        
        // Validate input
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            // Handle logo upload
            //$logoFileName = $this->handleLogoUpload();
            
            // Generate random password
            //$randomPassword = $this->generateRandomPassword();
            
            // Prepare school data
            $email = $this->request->getPost('email'); 
            $name = $this->request->getPost('school_name');
            $schoolData = [
                'sch_cat_id_fk' => $this->request->getPost('school_category'),
                'district_id_fk' => $this->request->getPost('district'),
                'sch_name' => $name,
                'sch_email' => $email,
                'sch_phone' => $this->request->getPost('phone'),
                'sch_address' => $this->request->getPost('address'),
                'sch_status' => 'Step 1 Configured', // Initial setup status
                'sch_motto' => $this->request->getPost('school_motto'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Insert school
            $schoolId = $this->schoolModel->insert($schoolData);
            
            if (!$schoolId) {
                throw new \Exception('Failed to create school record');
            }
            
            // Create subscription record
            $term = $this->request->getPost('plan_term');
            $subscriptionData = [
                'sch_id_fk' => $schoolId,
                'plan_id_fk' => $this->request->getPost('plan_id'),
                'subscription_term' => $term,
                'payment_mode' => $this->request->getPost('payment_mode'),
                'subscription_start_date' => date('Y-m-d'),
                'subscription_end_date' => $this->calculateSubscriptionEnd($this->request->getPost('plan_term')),
                'subscription_status' => 'Pending Verification',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $subscriptionId = $this->subscriptionModel->insert($subscriptionData);
            
            $planData = $this->planModel->getPlan($this->request->getPost('plan_id'));
            
            if (!$subscriptionId) {
                throw new \Exception('Failed to create subscription record');
            }
            
            // Commit transaction
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }
            
            if (!empty($email)) {
                // Prepare email data
                log_message('info', 'Preparing to send notification email');
                
                $emailData = [
                    'schoolId' => $schoolId,
                    'schoolName' => $name,
                    'email' => $email, 
                    'schoolAddress' => $this->request->getPost('address'),
                    'schoolPhone' => $this->request->getPost('phone'),
                    'planName' => $planData['plan_name'],
                    'planDescription' => $planData['plan_desc'],
                    'planPrice' => ($planData['plan_monthly_cost'] * $term),  // ✅ Changed from 'planCost'
                    'planFeatures' => explode(',', $planData['plan_features'] ?? ''),  // Add this if you have features
                    
                    'subscriptionTerm' => $term,
                    'subscriptionStart' => date('Y-m-d'),  // ✅ Changed from 'start'
                    'subscriptionEnd' => $this->calculateSubscriptionEnd($term),  // ✅ Changed from 'end'
                    'paymentMode' => $this->request->getPost('payment_mode'),
                    
                    'vatRate' => 15,
                    
                    // Email config (if needed)
                    'page' => 'add_school_notification',
                    'subject' => 'School Account Created'
                ];
                
                try {
                    $emailSent = $this->sendEmail($emailData);
                    
                    if ($emailSent) {
                        log_message('info', 'Notification email sent successfully to: ' . $email);
                    } else {
                        log_message('warning', 'Failed to send notification email to: ' . $email);
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Email sending exception: ' . $e->getMessage());
                }
            } else {
                log_message('info', 'No email to send (email not provided)');
            }
            
            // Check if photo was uploaded
            $photo = $this->request->getFile('school_logo');
            $photoName = null;
            
            log_message('info', 'Checking for photo upload');
            log_message('debug', 'Photo object: ' . ($photo ? 'exists' : 'null'));
            
            if ($photo) {
                log_message('debug', 'Photo name: ' . $photo->getName());
                log_message('debug', 'Photo size: ' . $photo->getSize() . ' bytes');
                log_message('debug', 'Photo MIME type: ' . $photo->getMimeType());
                log_message('debug', 'Photo is valid: ' . ($photo->isValid() ? 'Yes' : 'No'));
                log_message('debug', 'Photo has moved: ' . ($photo->hasMoved() ? 'Yes' : 'No'));
                log_message('debug', 'Photo error code: ' . $photo->getError());
            }
            
            if ($photo && $photo->isValid() && !$photo->hasMoved()) {
                log_message('info', 'Photo upload detected - processing');
                
                // Validate file type
                $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $maxSize = 2048; // 2MB in KB
                
                $photoMimeType = $photo->getMimeType();
                log_message('debug', 'Validating MIME type: ' . $photoMimeType);
                
                if (!in_array($photoMimeType, $validTypes)) {
                    log_message('error', 'Invalid file type: ' . $photoMimeType);
                    return redirect()->back()
                                    ->withInput()
                                    ->with('error', 'Invalid file type. Only JPG, PNG, and GIF are allowed.');
                }
                
                log_message('info', 'MIME type validation passed');
                
                $photoSize = $photo->getSize();
                log_message('debug', 'Validating file size: ' . $photoSize . ' bytes (Max: ' . ($maxSize * 1024) . ' bytes)');
                
                if ($photoSize > ($maxSize * 1024)) {
                    log_message('error', 'File size too large: ' . $photoSize . ' bytes');
                    return redirect()->back()
                                    ->withInput()
                                    ->with('error', 'File size too large. Maximum size is 2MB.');
                }
                
                log_message('info', 'File size validation passed');
                
                // Check if upload directory exists
                $uploadPath = FCPATH . 'uploads/school/logo';
                log_message('debug', 'Upload path: ' . $uploadPath);
                
                if (!is_dir($uploadPath)) {
                    log_message('warning', 'Upload directory does not exist, creating: ' . $uploadPath);
                    mkdir($uploadPath, 0755, true);
                }
                
                if (!is_writable($uploadPath)) {
                    log_message('error', 'Upload directory is not writable: ' . $uploadPath);
                    return redirect()->back()
                                    ->withInput()
                                    ->with('error', 'Upload directory is not writable. Please contact administrator.');
                }
                
                log_message('info', 'Upload directory is writable');
                
                // Generate unique filename
                $photoName = $photo->getRandomName();
                log_message('info', 'Generated photo filename: ' . $photoName);
                
                // Move to uploads/profilePhoto folder
                try {
                    $moved = $photo->move($uploadPath, $photoName);
                    
                    if ($moved) {
                        log_message('info', 'Photo uploaded successfully: ' . $photoName);
                        log_message('debug', 'Full path: ' . $uploadPath . '/' . $photoName);
                        $this->schoolModel->updateSchool($schoolId,array('sch_logo' => $photoName));
                    } else {
                        log_message('error', 'Photo move failed - returned false');
                        $photoName = null;
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Photo upload exception: ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                    $photoName = null;
                }
            } else {
                if ($photo) {
                    if (!$photo->isValid()) {
                        log_message('warning', 'Photo is not valid - Error: ' . $photo->getErrorString());
                    }
                    if ($photo->hasMoved()) {
                        log_message('warning', 'Photo has already been moved');
                    }
                } else {
                    log_message('info', 'No photo uploaded');
                }
            }
        
            $userLogData = [
                'user_id_fk' => $this->session->get('userID'),
                'ip_aadress' => $this->ipAddress,
                'user_agent' => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title' => 'Add New School',
                'log_desc' => 'School "' . $this->request->getPost('school_name'). '" has been created successfully!',
                'log_date' => date('Y-m-d'),
                'log_time' => time(),
                'log_icon' => '<i class="ki-duotone ki-save-2"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme' => 'success'
            ];
            
            $this->userLogModel->insert($userLogData);
            
            //return redirect()->to('school/setup/'.$schoolId)->with('success', 'School created successfully!');
            return redirect()->to('school')->with('success', 'School created successfully!');
                
        } catch (\Exception $e) {
            // Rollback transaction
            $db->transRollback();
            
            // Log error
            log_message('error', 'School creation failed: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Delete uploaded logo if exists
            if (isset($logoFileName) && !empty($logoFileName)) {
                $this->deleteUploadedFile($logoFileName);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create school: ' . $e->getMessage());
        }
    }
    
    /**
     * Get user listing data for DataTables (AJAX)
     */
    public function getSchoolListing()
    {
        try {
            $request = service('request');
            
            // Log incoming request for debugging
            log_message('debug', 'DataTables Request: ' . json_encode($request->getPost()));
            
            // Get DataTables parameters
            $draw = (int)($request->getPost('draw') ?? 1);
            $start = (int)($request->getPost('start') ?? 0);
            $length = (int)($request->getPost('length') ?? 10);
            
            // Get search parameter
            $searchData = $request->getPost('search');
            $searchValue = is_array($searchData) ? ($searchData['value'] ?? '') : '';
            
            // Get order parameter
            $orderData = $request->getPost('order');
            $orderColumnIndex = is_array($orderData) && isset($orderData[0]['column']) 
                ? (int)$orderData[0]['column'] 
                : 0;
            $orderDir = is_array($orderData) && isset($orderData[0]['dir']) 
                ? $orderData[0]['dir'] 
                : 'asc';
            
            // Map column index to database column name
            $columns = [
                'school.sch_name', 
                'school.sch_email', 
                'school.sch_phone', 
                'school.sch_address', 
                'district.district_name',
                'school.sch_status', 
                null // Actions column
            ];
            $orderColumn = $columns[$orderColumnIndex] ?? 'school.sch_name';
            
            // Prevent sorting on actions column
            if ($orderColumn === null || $orderColumnIndex === 6) {
                $orderColumn = 'users.fname';
            }
            
            // Validate sort direction
            $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
            
            log_message('debug', "Order by: {$orderColumn} {$orderDir}");
            
            // Build query with joins
            $builder = $this->db->table('school');
            $builder->select('*');
            $builder->join('sch_category', 'sch_category.sch_cat_id = school.sch_cat_id_fk', 'left');
            $builder->join('district', 'district.district_id = school.district_id_fk', 'left');
            
            // Apply search filter
            if (!empty($searchValue)) {
                $builder->groupStart()
                    ->like('school.sch_name', $searchValue)
                    ->orLike('school.address', $searchValue)
                    ->orLike('schoolsch_.email', $searchValue)
                    ->orLike('school.sch_phone', $searchValue)
                    ->orLike('district.district_name', $searchValue)
                    ->orLike('school.sch_status', $searchValue)
                    ->groupEnd();
            }
            
            // Get total filtered count before pagination
            $recordsFiltered = $builder->countAllResults(false);
            
            // Apply ordering
            $builder->orderBy($orderColumn, $orderDir);
            
            // Apply pagination
            if ($length != -1) {
                $builder->limit($length, $start);
            }
            
            // Get data
            $schools = $builder->get()->getResultArray();
            
            // Get total count (without filters)
            $recordsTotal = $this->db->table('users')->countAllResults();
            
            log_message('debug', "Found {$recordsFiltered} filtered users out of {$recordsTotal} total");
            
            // Format data for DataTables
            $data = [];
            foreach ($schools as $school) {
                
                //get plan
                $schPlan = $this->subscriptionModel->hasActiveSubscription($school['sch_id']);
                
                $data[] = [
                    $this->formatUserName($school),
                    //$school['sch_email'] ?? 'N/A',
                    //$school['sch_phone'] ?? 'N/A',
                    $school['district_name'] ?? 'N/A',
                    $schPlan['plan_name'] ?? 'N/A',
                    $this->formatStatus($school['sch_status'] ?? 'Inactive'),
                    $this->createActionButtons($school['sch_id'])
                ];
            }
            
            // Return JSON response
            $response = [
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ];
            
            log_message('debug', 'DataTables Response: ' . json_encode($response));
            
            return $this->response->setJSON($response);
            
        } catch (\Exception $e) {
            log_message('error', 'User listing error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'draw' => $request->getPost('draw') ?? 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Format user name with photo
     */
    private function formatUserName($school)
    {
        $fullName = trim(($school['sch_name'] ?? ''));
        $logo = $school['sch_logo'] ?? 'navuli_icon_small_color.png';
        $photoUrl = base_url('uploads/school/logo/' . $logo);
        
        // Generate initials for avatar if no photo
        $initials = '';
        if ($school['sch_name']) {
            $initials .= strtoupper(substr($school['sch_name'], 0, 1));
        }
        
        return '
        <div class="d-flex align-items-center">
            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                <a href="' . base_url('school/detail/' . $school['sch_id']) . '">
                    ' . ($logo && $logo !== 'navuli_icon_small_color.png' 
                        ? '<div class="symbol-label"><img src="' . $photoUrl . '" alt="' . esc($fullName) . '" class="w-100" /></div>'
                        : '<div class="symbol-label fs-3 bg-light-primary text-primary">' . $initials . '</div>'
                    ) . '
                </a>
            </div>
            <div class="d-flex flex-column">
                <a href="' . base_url('school/detail/' . $school['sch_id']) . '" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                    ' . esc($fullName) . '
                </a>
                <span class="text-muted fs-7">' . ucfirst($school['sch_address'] ?? 'Unknown') . '</span>
            </div>
        </div>';
    }
    
    /**
     * Format Status value
     */
    private function formatStatus($status)
    {
        if ($status === 'Active') {
            return '<span class="badge badge-light-success">Active</span>';
        } else if ($status === 'Step 1 Configured' || $status === 'Step 2 Configured' || $status === 'Step 3 Configured' || $status === 'Step 4 Configured' || $status === 'Step 5 Configured'){
            return '<span class="badge badge-light-warning">Pending Configuration</span>';
        }else {
            return '<span class="badge badge-light-danger">Inactive</span>';
        }
    }
    
    /**
     * Create action buttons HTML for each user
     * 
     * @param int $userId
     * @return string
     */
    private function createActionButtons($schID)
    {
        return '
        <div class="d-flex justify-content-end flex-shrink-0">
            <button class="btn btn-icon btn-bg-light btn-light-primary btn-sm me-1" data-kt-menu-trigger="click" title="Quick Action" data-kt-menu-placement="bottom-end">
                <i class="ki-duotone ki-down fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </button>
            
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                <div class="menu-item px-3">
                    <a href="' . base_url('school/detail/' . $schID) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-eye fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        View Details
                    </a>
                </div>
                
                <div class="menu-item px-3">
                    <a href="' . base_url('school/update/' . $schID) . '" class="menu-link px-3">
                        <i class="ki-duotone ki-pencil fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Edit School
                    </a>
                </div>
                
                <div class="separator my-2"></div>
                
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3 text-danger" data-kt-schools-table-filter="delete_row" data-sch-id="' . $schID . '">
                        <i class="ki-duotone ki-trash fs-5 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Delete School
                    </a>
                </div>
            </div>
        </div>';
    }

    /**
     * Delete a school — AJAX endpoint
     * Checks for any existing configuration before deleting.
     */
    public function delete($id)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized.'])->setStatusCode(401);
        }

        $school = $this->schoolModel->find((int) $id);
        if (!$school) {
            return $this->response->setJSON(['success' => false, 'message' => 'School not found.']);
        }

        // Hard blockers — school cannot be deleted if any of these are configured
        $levelCount   = $this->schoolLevelModel->where('sch_id_fk', (int) $id)->countAllResults();
        $deptCount    = $this->schoolDepartmentModel->where('sch_id_fk', (int) $id)->countAllResults();
        $subjectCount = $this->schoolSubjectModel->where('sch_id_fk', (int) $id)->countAllResults();
        $admissionCount = $this->db->table('admission')->where('sch_id_fk', (int) $id)->countAllResults();
        $houseCount     = $this->db->table('house')->where('sch_id_fk', (int) $id)->countAllResults();
        $staffCount     = $this->db->table('staff')->where('sch_id_fk', (int) $id)->countAllResults();

        $blocked = [];
        if ($levelCount > 0) {
            $blocked[] = "{$levelCount} school level(s)";
        }
        if ($deptCount > 0) {
            $blocked[] = "{$deptCount} department(s)";
        }
        if ($subjectCount > 0) {
            $blocked[] = "{$subjectCount} registered subject(s)";
        }
        if ($admissionCount > 0) {
            $blocked[] = "{$admissionCount} admission record(s)";
        }
        if ($houseCount > 0) {
            $blocked[] = "{$houseCount} house record(s)";
        }
        if ($staffCount > 0) {
            $blocked[] = "{$staffCount} staff record(s)";
        }

        if (!empty($blocked)) {
            return $this->response->setJSON([
                'success' => false,
                'blocked' => true,
                'message' => 'Cannot delete "' . $school['sch_name'] . '" because it has configured: ' . implode(', ', $blocked) . '. Remove all configuration before deleting this school.',
            ]);
        }

        // No hard blockers — proceed with deletion inside a transaction
        $this->db->transStart();

        // Delete subscription records first (FK: ON DELETE RESTRICT)
        $this->db->table('subscription')->where('sch_id_fk', (int) $id)->delete();

        // Delete the school record
        $this->schoolModel->delete((int) $id);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete school. Please try again.']);
        }

        // Delete logo file after successful DB deletion
        $logo = $school['sch_logo'] ?? '';
        if ($logo && file_exists(FCPATH . 'uploads/school/logo/' . $logo)) {
            unlink(FCPATH . 'uploads/school/logo/' . $logo);
        }

        return $this->response->setJSON([
            'success'    => true,
            'message'    => '"' . $school['sch_name'] . '" has been deleted successfully.',
            'csrf_hash'  => csrf_hash(),
        ]);
    }


    /**
     * View school details
     *
     * @param int $schID
     * @return mixed
     */
    public function detail($schID)
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }
        
        // Check permission
        if (!$this->require_access('_school_profile')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }
        
        // Set page metadata
        $this->setPageData('School Details', 'School', 'School Listing');
        
        try {
            // Get school with all related data
            $school = $this->schoolModel
                ->select('
                    school.*,
                    sch_category.sch_cat_name,
                    sch_category.sch_cat_id,
                    district.district_name,
                    province.province_name
                ')
                ->join('sch_category', 'sch_category.sch_cat_id = school.sch_cat_id_fk', 'left')
                ->join('district', 'district.district_id = school.district_id_fk', 'left')
                ->join('province', 'province.province_id = district.province_id_fk', 'left')
                ->where('school.sch_id', $schID)
                ->first();
            
            if (!$school) {
                return redirect()->to('school')
                    ->with('error', 'School not found.');
            }
            
            // Get subscription details
            $subscription = $this->subscriptionModel->hasActiveSubscription($schID);
            
            // If no subscription, create a default one to avoid errors
            if (!$subscription) {
                $subscription = [
                    'plan_name' => 'No Active Subscription',
                    'subscription_end_date' => date('Y-m-d')
                ];
            }
            
            // Get school departments
            $departments = $this->schoolDepartmentModel
                ->select('sch_department.*, department.*')
                ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
                ->where('sch_department.sch_id_fk', $schID)
                ->findAll();
            
            // Get school levels
            $levels = $this->schoolLevelModel
                ->select('sch_level.*, level.level_name')
                ->join('level', 'level.level_id = sch_level.level_id_fk', 'left')
                ->where('sch_level.sch_id_fk', $schID)
                ->orderBy('level.level_id', 'ASC')
                ->findAll();
            
            // Initialize empty array
            $levelsWithStreams = [];
            
            // Only process if levels exist
            if (!empty($levels)) {
                $db = \Config\Database::connect();
                
                foreach ($levels as $level) {
                    // Get streams for this level
                    $streams = $db->table('stream')
                        ->select('stream.*')
                        ->where('stream.sch_level_id_fk', $level['sch_level_id'])
                        ->orderBy('stream.stream_name', 'ASC')
                        ->get()
                        ->getResultArray();
                    
                    // Get subjects for each stream
                    foreach ($streams as &$stream) {
                        // ✅ FIXED: Get core subjects with correct column names
                        $coreSubjects = $db->table('stream_core_subject')
                            ->select('stream_core_subject.stream_core_sub_id, stream_core_subject.sch_sub_id_fk, stream_core_subject.stream_id_fk, subject.subject_name, sch_subject.sch_dept_id_fk')
                            ->join('sch_subject', 'sch_subject.sch_sub_id = stream_core_subject.sch_sub_id_fk', 'left')
                            ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk', 'left')
                            ->where('stream_core_subject.stream_id_fk', $stream['stream_id'])
                            ->get()
                            ->getResultArray();
                        
                        // ✅ FIXED: Get optional subjects with correct column names
                        $optionalSubjects = $db->table('stream_optional_subject')
                            ->select('stream_optional_subject.*, subject.subject_name')
                            ->join('sch_subject', 'sch_subject.sch_sub_id = stream_optional_subject.sch_sub_id_fk', 'left')
                            ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk', 'left')
                            ->where('stream_optional_subject.stream_id_fk', $stream['stream_id'])
                            ->orderBy('stream_optional_subject.option_num', 'ASC')
                            ->get()
                            ->getResultArray();
                        
                        // Group optional subjects by option_num
                        $groupedOptionals = [];
                        foreach ($optionalSubjects as $optSub) {
                            if (isset($optSub['option_num'])) {
                                $groupedOptionals[$optSub['option_num']][] = $optSub;
                            }
                        }
                        
                        $stream['core_subjects'] = $coreSubjects;
                        $stream['optional_subjects'] = $groupedOptionals;
                        $stream['core_count'] = count($coreSubjects);
                        $stream['optional_count'] = count($optionalSubjects);
                    }
                    
                    $levelsWithStreams[] = [
                        'level' => $level,
                        'streams' => $streams
                    ];
                }
            }
            
            // Counts for Add Department / Add Level button enable/disable logic
            $totalDepartments = $this->departmentModel->countAllResults();

            $catId = $school['sch_cat_id'] ?? null;
            $levelQuery = $this->levelModel;
            if ($catId) {
                $levelQuery->where('sch_cat_id_fk', $catId);
            }
            $totalLevelsForCategory = $levelQuery->countAllResults();

            // ✅ GET SCHOOL STATISTICS
            $statistics = $this->schoolModel->getSchoolStatistics($schID);
            
            // Calculate percentages
            $totalUsers = $statistics['total_users'] > 0 ? $statistics['total_users'] : 1;
            
            $statistics['percentages'] = [
                'active' => round(($statistics['active_users'] / $totalUsers) * 100, 1),
                'inactive' => round(($statistics['inactive_users'] / $totalUsers) * 100, 1),
                'parents' => round(($statistics['parents'] / $totalUsers) * 100, 1),
                'students' => round(($statistics['students'] / $totalUsers) * 100, 1),
                'teachers' => round(($statistics['teachers'] / $totalUsers) * 100, 1),
                'support_staff' => round(($statistics['support_staff'] / $totalUsers) * 100, 1),
            ];
            
            // School Registered Subjects
            $dbConn = \Config\Database::connect();
            $schoolSubjectsFlat = $dbConn->table('sch_subject')
                ->select('sch_subject.sch_sub_id, sch_subject.sch_dept_id_fk, sch_subject.sch_sub_status, subject.subject_name, level.level_id, level.level_name, department.dept_name')
                ->join('subject', 'subject.subject_id = sch_subject.subject_id_fk', 'left')
                ->join('level', 'level.level_id = subject.level_id_fk', 'left')
                ->join('sch_department', 'sch_department.sch_dept_id = sch_subject.sch_dept_id_fk', 'left')
                ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
                ->where('sch_subject.sch_id_fk', $schID)
                ->orderBy('level.level_id', 'ASC')
                ->orderBy('subject.subject_name', 'ASC')
                ->get()->getResultArray();

            $schoolSubjectsByLevel = [];
            foreach ($schoolSubjectsFlat as $sub) {
                $lvlId = $sub['level_id'] ?? 0;
                if (!isset($schoolSubjectsByLevel[$lvlId])) {
                    $schoolSubjectsByLevel[$lvlId] = [
                        'level_id'   => $lvlId,
                        'level_name' => $sub['level_name'] ?? 'Unknown',
                        'subjects'   => [],
                    ];
                }
                $schoolSubjectsByLevel[$lvlId]['subjects'][] = $sub;
            }
            $schoolSubjectsByLevel = array_values($schoolSubjectsByLevel);

            // All subscription history for this school
            $allSubscriptions = $dbConn->table('subscription')
                ->select('
                    subscription.*,
                    plans.plan_name,
                    plans.plan_monthly_cost
                ')
                ->join('plans', 'plans.plan_id = subscription.plan_id_fk', 'left')
                ->where('subscription.sch_id_fk', $schID)
                ->orderBy('subscription.subscription_id', 'DESC')
                ->get()->getResultArray();

            // Prepare data for view
            $data = [
                '_view' => 'app/school/management/detail',
                'school' => $school,
                'subscription' => $subscription,
                'allSubscriptions' => $allSubscriptions,
                'departments' => $departments,
                'levelsWithStreams' => $levelsWithStreams,
                'statistics' => $statistics,
                'totalDepartments' => $totalDepartments,
                'totalLevelsForCategory' => $totalLevelsForCategory,
                'schoolSubjectsByLevel' => $schoolSubjectsByLevel,
                'schoolSubjectsTotal'   => count($schoolSubjectsFlat),
            ];
            
            return view('app/layouts/main', $data);
            
        } catch (\Exception $e) {
            // Log the actual error for debugging
            log_message('error', 'School detail error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Show detailed error in development
            if (ENVIRONMENT === 'development') {
                throw $e;
            }
            
            return redirect()->to('school')
                ->with('error', 'Error loading school details.');
        }
    }

    public function getLevelsJson($schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $school = $this->schoolModel->select('sch_cat_id_fk')->find($schId);
        $catId  = $school['sch_cat_id_fk'] ?? null;

        $query = $this->levelModel->orderBy('level_id', 'ASC');
        if ($catId) {
            $query->where('sch_cat_id_fk', $catId);
        }
        $allLevels = $query->findAll();

        $configured    = $this->schoolLevelModel->where('sch_id_fk', $schId)->findAll();
        $configuredIds = array_map('intval', array_column($configured, 'level_id_fk'));

        return $this->response->setJSON([
            'success'    => true,
            'levels'     => $allLevels,
            'configured' => $configuredIds,
        ]);
    }

    public function saveLevels($schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $levelIds = $this->request->getPost('levels') ?? [];

        if (empty($levelIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select at least one level.']);
        }

        try {
            $existing    = $this->schoolLevelModel->where('sch_id_fk', $schId)->findAll();
            $existingIds = array_map('intval', array_column($existing, 'level_id_fk'));

            $newLevelIds = [];
            foreach ($levelIds as $levelId) {
                if (!in_array((int) $levelId, $existingIds, true)) {
                    $this->schoolLevelModel->insert([
                        'sch_id_fk'   => (int) $schId,
                        'level_id_fk' => (int) $levelId,
                    ]);
                    $newLevelIds[] = (int) $levelId;
                }
            }

            $allLevels = $this->schoolLevelModel
                ->select('sch_level.sch_level_id, sch_level.sch_id_fk, sch_level.level_id_fk, level.level_name')
                ->join('level', 'level.level_id = sch_level.level_id_fk', 'left')
                ->where('sch_level.sch_id_fk', $schId)
                ->orderBy('level.level_id', 'ASC')
                ->findAll();

            $newLevels = array_values(array_filter($allLevels, function ($l) use ($newLevelIds) {
                return in_array((int) $l['level_id_fk'], $newLevelIds, true);
            }));

            return $this->response->setJSON([
                'success'   => true,
                'message'   => count($newLevelIds) . ' new level(s) added successfully.',
                'levels'    => $allLevels,
                'newLevels' => $newLevels,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'saveLevels error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred. Please try again.']);
        }
    }

    public function getDepartmentsJson($schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $allDepts = $this->departmentModel->getAllDepartment();

        $configured = $this->schoolDepartmentModel
            ->where('sch_id_fk', $schId)
            ->findAll();
        $configuredIds = array_column($configured, 'dept_id_fk');

        return $this->response->setJSON([
            'success' => true,
            'departments' => $allDepts,
            'configured' => array_map('intval', $configuredIds),
        ]);
    }

    public function saveDepartments($schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $deptIds = $this->request->getPost('departments') ?? [];

        if (empty($deptIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select at least one department.']);
        }

        try {
            $existing = $this->schoolDepartmentModel
                ->where('sch_id_fk', $schId)
                ->findAll();
            $existingIds = array_map('intval', array_column($existing, 'dept_id_fk'));

            $added = 0;
            foreach ($deptIds as $deptId) {
                if (!in_array((int) $deptId, $existingIds, true)) {
                    $this->schoolDepartmentModel->insert([
                        'sch_id_fk'   => (int) $schId,
                        'dept_id_fk'  => (int) $deptId,
                        'dept_status' => 'Established',
                    ]);
                    $added++;
                }
            }

            $departments = $this->schoolDepartmentModel
                ->select('sch_department.*, department.*')
                ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
                ->where('sch_department.sch_id_fk', $schId)
                ->findAll();

            return $this->response->setJSON([
                'success'     => true,
                'message'     => $added . ' new department(s) added successfully.',
                'departments' => $departments,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'saveDepartments error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred. Please try again.']);
        }
    }

    public function updateDepartmentStatus($schDeptId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $status  = $this->request->getPost('status');
        $allowed = ['Established', 'Non Established'];

        if (!in_array($status, $allowed, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status value.']);
        }

        $updated = $this->schoolDepartmentModel->update((int) $schDeptId, ['dept_status' => $status]);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Status updated to ' . $status . '.', 'status' => $status]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status.']);
    }

    public function deleteDepartment($schDeptId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $subjectCount = $this->schoolSubjectModel->where('sch_dept_id_fk', (int) $schDeptId)->countAllResults();
        if ($subjectCount > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'This department cannot be deleted because ' . $subjectCount . ' subject(s) are currently assigned to it. Please reassign or remove those subjects before deleting this department.']);
        }

        $deleted = $this->schoolDepartmentModel->delete((int) $schDeptId);

        if ($deleted) {
            return $this->response->setJSON(['success' => true, 'message' => 'Department removed successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete department.']);
    }

    public function addStream($schLevelId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $count  = $this->request->getPost('count');
        $naming = $this->request->getPost('naming') === 'alphabetical' ? 'alphabetical' : 'numerical';

        if (!is_numeric($count) || (int) $count < 1 || (int) $count > 26 || (float) $count !== floor((float) $count)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please enter a valid whole number of streams (1–26).']);
        }
        $count = (int) $count;

        $schLevel = $this->schoolLevelModel
            ->select('sch_level.sch_level_id, sch_level.sch_id_fk, sch_level.level_id_fk, level.level_name')
            ->join('level', 'level.level_id = sch_level.level_id_fk', 'left')
            ->find((int) $schLevelId);

        if (!$schLevel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Level not found.']);
        }

        $levelName     = $schLevel['level_name'];
        $existingCount = $this->schoolStreamModel->where('sch_level_id_fk', $schLevelId)->countAllResults();

        if ($naming === 'alphabetical' && ($existingCount + $count) > 26) {
            return $this->response->setJSON(['success' => false, 'message' => 'Adding ' . $count . ' stream(s) would exceed the 26 alphabetical stream limit for this level.']);
        }

        $streamNames = [];
        if ($naming === 'numerical') {
            preg_match('/\d+/', $levelName, $matches);
            $levelNum = isset($matches[0]) ? (int) $matches[0] : 1;
            $base = $levelNum * 100;
            for ($i = 1; $i <= $count; $i++) {
                $streamNames[] = (string) ($base + $existingCount + $i);
            }
        } else {
            $letters = range('A', 'Z');
            for ($i = 0; $i < $count; $i++) {
                $streamNames[] = $levelName . $letters[$existingCount + $i];
            }
        }

        $insertedStreams = [];
        foreach ($streamNames as $name) {
            $this->schoolStreamModel->insert([
                'sch_level_id_fk' => (int) $schLevelId,
                'stream_name'     => $name,
            ]);
            $insertedStreams[] = [
                'stream_id'       => $this->schoolStreamModel->getInsertID(),
                'sch_level_id_fk' => (int) $schLevelId,
                'stream_name'     => $name,
            ];
        }

        return $this->response->setJSON([
            'success'      => true,
            'message'      => count($insertedStreams) . ' stream(s) added successfully.',
            'streams'      => $insertedStreams,
            'totalStreams'  => $existingCount + count($insertedStreams),
        ]);
    }

    public function deleteStream($streamId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        $coreCount     = $db->table('stream_core_subject')->where('stream_id_fk', (int) $streamId)->countAllResults();
        $optionalCount = $db->table('stream_optional_subject')->where('stream_id_fk', (int) $streamId)->countAllResults();

        if ($coreCount > 0 || $optionalCount > 0) {
            $parts = [];
            if ($coreCount > 0)     $parts[] = $coreCount . ' core subject(s)';
            if ($optionalCount > 0) $parts[] = $optionalCount . ' optional subject(s)';
            return $this->response->setJSON(['success' => false, 'message' => 'This stream cannot be deleted because it has ' . implode(' and ', $parts) . ' assigned to it. Please remove all subjects from this stream before deleting.']);
        }

        $deleted = $this->schoolStreamModel->delete((int) $streamId);

        if ($deleted) {
            return $this->response->setJSON(['success' => true, 'message' => 'Stream deleted successfully.', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete stream.']);
    }

    public function getStreamSubjectsJson($streamId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $stream = $this->schoolStreamModel->find((int) $streamId);
        if (!$stream) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stream not found.']);
        }

        $schLevel = $this->schoolLevelModel->find($stream['sch_level_id_fk']);
        if (!$schLevel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Level not found.']);
        }

        $levelId = $schLevel['level_id_fk'];
        $schId   = $schLevel['sch_id_fk'];

        $subjects = $this->subjectModel
            ->where('level_id_fk', $levelId)
            ->orderBy('subject_name', 'ASC')
            ->findAll();

        $db = \Config\Database::connect();
        $configuredRows = $db->table('stream_core_subject')
            ->select('sch_subject.subject_id_fk')
            ->join('sch_subject', 'sch_subject.sch_sub_id = stream_core_subject.sch_sub_id_fk', 'left')
            ->where('stream_core_subject.stream_id_fk', (int) $streamId)
            ->get()->getResultArray();
        $configuredSubjectIds = array_map('intval', array_column($configuredRows, 'subject_id_fk'));

        $departments = $this->schoolDepartmentModel
            ->select('sch_department.sch_dept_id, department.dept_name')
            ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
            ->where('sch_department.sch_id_fk', $schId)
            ->findAll();

        return $this->response->setJSON([
            'success'              => true,
            'subjects'             => $subjects,
            'configured_subject_ids' => $configuredSubjectIds,
            'departments'          => $departments,
        ]);
    }

    public function addCoreSubject($streamId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $subjectIds   = $this->request->getPost('subjectIds') ?? [];
        $subjectDepts = $this->request->getPost('subjectDepts') ?? [];

        if (empty($subjectIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select at least one subject.']);
        }

        $stream = $this->schoolStreamModel->find((int) $streamId);
        if (!$stream) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stream not found.']);
        }

        $schLevel = $this->schoolLevelModel->find($stream['sch_level_id_fk']);
        $schId    = $schLevel['sch_id_fk'];

        $db             = \Config\Database::connect();
        $added          = [];
        $newSchSubjects = [];

        foreach ($subjectIds as $subjectId) {
            $subjectId = (int) $subjectId;
            $deptId    = $subjectDepts[$subjectId] ?? null;

            // Find or create sch_subject
            $existing = $db->table('sch_subject')
                ->where('sch_id_fk', $schId)
                ->where('subject_id_fk', $subjectId)
                ->get()->getRowArray();

            $isNew = false;
            if ($existing) {
                $schSubId = (int) $existing['sch_sub_id'];
                if ($deptId && empty($existing['sch_dept_id_fk'])) {
                    $db->table('sch_subject')->where('sch_sub_id', $schSubId)->update(['sch_dept_id_fk' => $deptId]);
                }
            } else {
                $db->table('sch_subject')->insert([
                    'sch_id_fk'      => $schId,
                    'subject_id_fk'  => $subjectId,
                    'sch_dept_id_fk' => $deptId ?: null,
                    'sch_sub_status' => 'Active',
                ]);
                $schSubId = (int) $db->insertID();
                $isNew    = true;
            }

            // Insert into stream_core_subject if not already there
            $alreadyCore = $this->streamCoreSubjectModel
                ->where('stream_id_fk', (int) $streamId)
                ->where('sch_sub_id_fk', $schSubId)
                ->first();

            if (!$alreadyCore) {
                $this->streamCoreSubjectModel->insert([
                    'stream_id_fk'  => (int) $streamId,
                    'sch_sub_id_fk' => $schSubId,
                ]);
                $streamCoreSubId = (int) $this->streamCoreSubjectModel->getInsertID();

                $subject  = $this->subjectModel->find($subjectId);
                $deptName = '';
                if ($deptId) {
                    $deptRow = $db->table('sch_department')
                        ->select('department.dept_name')
                        ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
                        ->where('sch_department.sch_dept_id', (int) $deptId)
                        ->get()->getRowArray();
                    $deptName = $deptRow['dept_name'] ?? '';
                }

                $added[] = [
                    'stream_core_sub_id' => $streamCoreSubId,
                    'sch_sub_id_fk'      => $schSubId,
                    'subject_name'       => $subject['subject_name'] ?? '',
                    'sch_dept_id_fk'     => $deptId ?: null,
                    'dept_name'          => $deptName,
                ];

                if ($isNew) {
                    $levelRow  = $db->table('level')->select('level.level_id, level.level_name')->join('subject', 'subject.level_id_fk = level.level_id')->where('subject.subject_id', $subjectId)->get()->getRowArray();
                    $newSchSubjects[] = [
                        'sch_sub_id'     => $schSubId,
                        'subject_name'   => $subject['subject_name'] ?? '',
                        'level_id'       => $levelRow['level_id'] ?? 0,
                        'level_name'     => $levelRow['level_name'] ?? '',
                        'dept_name'      => $deptName,
                        'sch_dept_id_fk' => $deptId ?: null,
                        'sch_sub_status' => 'Active',
                    ];
                }
            }
        }

        $newTotal = $this->streamCoreSubjectModel->where('stream_id_fk', (int) $streamId)->countAllResults();

        // Check if school is now fully configured and activate if so
        $activated = $this->checkAndActivateSchool($schId);

        return $this->response->setJSON([
            'success'        => true,
            'message'        => count($added) . ' subject(s) added successfully.',
            'subjects'       => $added,
            'coreTotal'      => $newTotal,
            'activated'      => $activated,
            'newSchSubjects' => $newSchSubjects,
            'csrf_hash'      => csrf_hash(),
        ]);
    }

    /**
     * Checks whether every school level has at least one stream,
     * and every stream has at least one core subject.
     * If true, sets school status to Active.
     */
    private function checkAndActivateSchool(int $schId): bool
    {
        $db = \Config\Database::connect();

        $levels = $db->table('sch_level')->where('sch_id_fk', $schId)->get()->getResultArray();

        if (empty($levels)) {
            return false;
        }

        foreach ($levels as $level) {
            $streams = $db->table('stream')
                ->where('sch_level_id_fk', $level['sch_level_id'])
                ->get()->getResultArray();

            if (empty($streams)) {
                return false;
            }

            foreach ($streams as $stream) {
                $coreCount = $db->table('stream_core_subject')
                    ->where('stream_id_fk', $stream['stream_id'])
                    ->countAllResults();

                if ($coreCount === 0) {
                    return false;
                }
            }
        }

        // All conditions met — activate the school
        $school = $this->schoolModel->find($schId);
        if ($school && $school['sch_status'] !== 'Active') {
            $this->schoolModel->update($schId, ['sch_status' => 'Active']);
        }

        return true;
    }

    public function deleteCoreSubject($streamCoreSubId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $record = $this->streamCoreSubjectModel->find((int) $streamCoreSubId);
        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Record not found.']);
        }

        $streamId = $record['stream_id_fk'];
        $this->streamCoreSubjectModel->delete((int) $streamCoreSubId);
        $newTotal = $this->streamCoreSubjectModel->where('stream_id_fk', $streamId)->countAllResults();

        return $this->response->setJSON(['success' => true, 'message' => 'Subject removed.', 'coreTotal' => $newTotal, 'csrf_hash' => csrf_hash()]);
    }

    public function editCoreSubject($streamCoreSubId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $deptId = $this->request->getPost('dept_id');
        $record = $this->streamCoreSubjectModel->find((int) $streamCoreSubId);
        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Record not found.']);
        }

        $db = \Config\Database::connect();
        $db->table('sch_subject')
            ->where('sch_sub_id', $record['sch_sub_id_fk'])
            ->update(['sch_dept_id_fk' => $deptId ?: null]);

        $deptName = '';
        if ($deptId) {
            $deptRow = $db->table('sch_department')
                ->select('department.dept_name')
                ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
                ->where('sch_department.sch_dept_id', (int) $deptId)
                ->get()->getRowArray();
            $deptName = $deptRow['dept_name'] ?? '';
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Department updated.', 'dept_name' => $deptName, 'dept_id' => $deptId, 'csrf_hash' => csrf_hash()]);
    }

    public function getStreamOptionalSubjectsJson($streamId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $stream = $this->schoolStreamModel->find((int) $streamId);
        if (!$stream) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stream not found.']);
        }

        $schLevel = $this->schoolLevelModel->find($stream['sch_level_id_fk']);
        if (!$schLevel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Level not found.']);
        }

        $levelId = $schLevel['level_id_fk'];
        $schId   = $schLevel['sch_id_fk'];

        $subjects = $this->subjectModel
            ->where('level_id_fk', $levelId)
            ->orderBy('subject_name', 'ASC')
            ->findAll();

        $db = \Config\Database::connect();

        $optionalRows = $db->table('stream_optional_subject')
            ->select('sch_subject.subject_id_fk')
            ->join('sch_subject', 'sch_subject.sch_sub_id = stream_optional_subject.sch_sub_id_fk', 'left')
            ->where('stream_optional_subject.stream_id_fk', (int) $streamId)
            ->get()->getResultArray();

        $coreRows = $db->table('stream_core_subject')
            ->select('sch_subject.subject_id_fk')
            ->join('sch_subject', 'sch_subject.sch_sub_id = stream_core_subject.sch_sub_id_fk', 'left')
            ->where('stream_core_subject.stream_id_fk', (int) $streamId)
            ->get()->getResultArray();

        $optionalSubjectIds = array_map('intval', array_column($optionalRows, 'subject_id_fk'));
        $coreSubjectIds     = array_map('intval', array_column($coreRows, 'subject_id_fk'));
        $configuredSubjectIds = array_values(array_unique(array_merge($optionalSubjectIds, $coreSubjectIds)));

        $departments = $this->schoolDepartmentModel
            ->select('sch_department.sch_dept_id, department.dept_name')
            ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
            ->where('sch_department.sch_id_fk', $schId)
            ->findAll();

        return $this->response->setJSON([
            'success'                => true,
            'subjects'               => $subjects,
            'configured_subject_ids' => $configuredSubjectIds,
            'core_subject_ids'       => $coreSubjectIds,
            'optional_subject_ids'   => $optionalSubjectIds,
            'departments'            => $departments,
        ]);
    }

    public function addOptionalSubjects($streamId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $subjectIds   = $this->request->getPost('subjectIds') ?? [];
        $subjectDepts = $this->request->getPost('subjectDepts') ?? [];

        if (count($subjectIds) < 2) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select at least 2 subjects for an optional group.']);
        }

        $stream = $this->schoolStreamModel->find((int) $streamId);
        if (!$stream) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stream not found.']);
        }

        $schLevel = $this->schoolLevelModel->find($stream['sch_level_id_fk']);
        $schId    = $schLevel['sch_id_fk'];

        $db = \Config\Database::connect();

        $maxRow = $db->table('stream_optional_subject')
            ->selectMax('option_num', 'max_opt')
            ->where('stream_id_fk', (int) $streamId)
            ->get()->getRowArray();
        $optionNum = ($maxRow && $maxRow['max_opt']) ? (int)$maxRow['max_opt'] + 1 : 1;

        $added          = [];
        $newSchSubjects = [];

        foreach ($subjectIds as $subjectId) {
            $subjectId = (int) $subjectId;
            $deptId    = $subjectDepts[$subjectId] ?? null;

            $existing = $db->table('sch_subject')
                ->where('sch_id_fk', $schId)
                ->where('subject_id_fk', $subjectId)
                ->get()->getRowArray();

            $isNew = false;
            if ($existing) {
                $schSubId = (int) $existing['sch_sub_id'];
                if ($deptId && empty($existing['sch_dept_id_fk'])) {
                    $db->table('sch_subject')->where('sch_sub_id', $schSubId)->update(['sch_dept_id_fk' => $deptId]);
                }
            } else {
                $db->table('sch_subject')->insert([
                    'sch_id_fk'      => $schId,
                    'subject_id_fk'  => $subjectId,
                    'sch_dept_id_fk' => $deptId ?: null,
                    'sch_sub_status' => 'Active',
                ]);
                $schSubId = (int) $db->insertID();
                $isNew    = true;
            }

            $alreadyOpt = $db->table('stream_optional_subject')
                ->where('stream_id_fk', (int) $streamId)
                ->where('sch_sub_id_fk', $schSubId)
                ->get()->getRowArray();

            if (!$alreadyOpt) {
                $db->table('stream_optional_subject')->insert([
                    'stream_id_fk'  => (int) $streamId,
                    'sch_sub_id_fk' => $schSubId,
                    'option_num'    => $optionNum,
                ]);
                $streamOptSubId = (int) $db->insertID();

                $subject  = $this->subjectModel->find($subjectId);
                $deptName = '';
                if ($deptId) {
                    $deptRow  = $db->table('sch_department')
                        ->select('department.dept_name')
                        ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
                        ->where('sch_department.sch_dept_id', (int) $deptId)
                        ->get()->getRowArray();
                    $deptName = $deptRow['dept_name'] ?? '';
                }

                $added[] = [
                    'stream_opt_sub_id' => $streamOptSubId,
                    'sch_sub_id_fk'     => $schSubId,
                    'subject_name'      => $subject['subject_name'] ?? '',
                    'sch_dept_id_fk'    => $deptId ?: null,
                    'option_num'        => $optionNum,
                ];

                if ($isNew) {
                    $levelRow = $db->table('level')->select('level.level_id, level.level_name')->join('subject', 'subject.level_id_fk = level.level_id')->where('subject.subject_id', $subjectId)->get()->getRowArray();
                    $newSchSubjects[] = [
                        'sch_sub_id'     => $schSubId,
                        'subject_name'   => $subject['subject_name'] ?? '',
                        'level_id'       => $levelRow['level_id'] ?? 0,
                        'level_name'     => $levelRow['level_name'] ?? '',
                        'dept_name'      => $deptName,
                        'sch_dept_id_fk' => $deptId ?: null,
                        'sch_sub_status' => 'Active',
                    ];
                }
            }
        }

        $newTotal = $db->table('stream_optional_subject')
            ->where('stream_id_fk', (int) $streamId)
            ->countAllResults();

        return $this->response->setJSON([
            'success'        => true,
            'message'        => count($added) . ' optional subject(s) added (Option ' . $optionNum . ').',
            'optionGroup'    => ['option_num' => $optionNum, 'subjects' => $added],
            'optionalTotal'  => $newTotal,
            'newSchSubjects' => $newSchSubjects,
            'csrf_hash'      => csrf_hash(),
        ]);
    }

    public function deleteOptionalSubject($streamOptSubId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $record = $this->streamOptionalSubjectModel->find((int) $streamOptSubId);
        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Record not found.']);
        }

        $streamId  = (int) $record['stream_id_fk'];
        $optionNum = (int) $record['option_num'];

        $this->streamOptionalSubjectModel->delete((int) $streamOptSubId);

        $remaining = $this->streamOptionalSubjectModel
            ->where('stream_id_fk', $streamId)
            ->where('option_num', $optionNum)
            ->countAllResults();

        $deletedOption = false;
        if ($remaining < 2) {
            $this->streamOptionalSubjectModel
                ->where('stream_id_fk', $streamId)
                ->where('option_num', $optionNum)
                ->delete();
            $deletedOption = true;
        }

        $db = \Config\Database::connect();
        $newTotal = $db->table('stream_optional_subject')
            ->where('stream_id_fk', $streamId)
            ->countAllResults();

        return $this->response->setJSON([
            'success'       => true,
            'message'       => $deletedOption ? 'Option group removed (too few subjects remaining).' : 'Subject removed.',
            'deletedOption' => $deletedOption,
            'optionNum'     => $optionNum,
            'streamId'      => $streamId,
            'optionalTotal' => $newTotal,
            'csrf_hash'     => csrf_hash(),
        ]);
    }

    public function deleteLevel($schLevelId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $schLevel = $this->schoolLevelModel->find((int) $schLevelId);
        if (!$schLevel) {
            return $this->response->setJSON(['success' => false, 'message' => 'Level not found.']);
        }

        $streamCount = $this->schoolStreamModel->where('sch_level_id_fk', (int) $schLevelId)->countAllResults();
        if ($streamCount > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'This level cannot be deleted because it has ' . $streamCount . ' stream(s) configured under it. Please remove all streams from this level before deleting.']);
        }

        $schId   = $schLevel['sch_id_fk'];
        $deleted = $this->schoolLevelModel->delete((int) $schLevelId);

        if ($deleted) {
            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Level removed successfully.',
                'redirect' => base_url('school/detail/' . $schId) . '#kt_tab_pane_4',
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete level.']);
    }

    public function editSchSubject($schSubId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $db  = \Config\Database::connect();
        $row = $db->table('sch_subject')->where('sch_sub_id', (int) $schSubId)->get()->getRowArray();

        if (!$row) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subject not found.']);
        }

        $deptId = $this->request->getPost('sch_dept_id_fk');
        $status = $this->request->getPost('sch_sub_status');

        if (!in_array($status, ['Active', 'Inactive'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status.']);
        }

        $db->table('sch_subject')->where('sch_sub_id', (int) $schSubId)->update([
            'sch_dept_id_fk' => $deptId ? (int) $deptId : null,
            'sch_sub_status' => $status,
        ]);

        $deptName = '';
        if ($deptId) {
            $deptRow  = $db->table('sch_department')
                ->select('department.dept_name')
                ->join('department', 'department.dept_id = sch_department.dept_id_fk', 'left')
                ->where('sch_department.sch_dept_id', (int) $deptId)
                ->get()->getRowArray();
            $deptName = $deptRow['dept_name'] ?? '';
        }

        return $this->response->setJSON([
            'success'        => true,
            'message'        => 'Subject updated successfully.',
            'sch_sub_id'     => (int) $schSubId,
            'sch_dept_id_fk' => $deptId ? (int) $deptId : null,
            'dept_name'      => $deptName,
            'sch_sub_status' => $status,
            'csrf_hash'      => csrf_hash(),
        ]);
    }

    public function deleteSchSubject($schSubId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $db  = \Config\Database::connect();
        $row = $db->table('sch_subject')->where('sch_sub_id', (int) $schSubId)->get()->getRowArray();

        if (!$row) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subject not found.']);
        }

        $coreCount     = $db->table('stream_core_subject')->where('sch_sub_id_fk', (int) $schSubId)->countAllResults();
        $optionalCount = $db->table('stream_optional_subject')->where('sch_sub_id_fk', (int) $schSubId)->countAllResults();

        if ($coreCount > 0 || $optionalCount > 0) {
            $parts = [];
            if ($coreCount > 0)     $parts[] = $coreCount . ' core stream assignment(s)';
            if ($optionalCount > 0) $parts[] = $optionalCount . ' optional stream assignment(s)';
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This subject cannot be deleted because it has ' . implode(' and ', $parts) . '. Please remove all stream assignments for this subject before deleting.',
            ]);
        }

        $db->table('sch_subject')->where('sch_sub_id', (int) $schSubId)->delete();

        return $this->response->setJSON([
            'success'    => true,
            'message'    => 'Subject deleted successfully.',
            'sch_sub_id' => (int) $schSubId,
            'csrf_hash'  => csrf_hash(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Available subjects for school (not yet in sch_subject for this school)
    // -------------------------------------------------------------------------
    public function getAvailableSubjectsForSchool($schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $schId = (int) $schId;
        $db    = \Config\Database::connect();

        // Level IDs this school has
        $levelRows = $db->table('sch_level')
            ->select('level_id_fk')
            ->where('sch_id_fk', $schId)
            ->get()->getResultArray();
        $levelIds = array_column($levelRows, 'level_id_fk');

        if (empty($levelIds)) {
            return $this->response->setJSON(['success' => true, 'subjects' => []]);
        }

        // Subject IDs already registered for this school
        $existingRows = $db->table('sch_subject')
            ->select('subject_id_fk')
            ->where('sch_id_fk', $schId)
            ->get()->getResultArray();
        $existingIds = array_column($existingRows, 'subject_id_fk');

        // All subjects for those levels, excluding already-registered ones
        $q = $db->table('subject')
            ->select('subject.subject_id, subject.subject_name, subject.is_examinable, level.level_id, level.level_name')
            ->join('level', 'level.level_id = subject.level_id_fk', 'left')
            ->whereIn('subject.level_id_fk', $levelIds)
            ->orderBy('level.level_id', 'ASC')
            ->orderBy('subject.subject_name', 'ASC');

        if (!empty($existingIds)) {
            $q->whereNotIn('subject.subject_id', $existingIds);
        }

        $rows = $q->get()->getResultArray();

        // Group by level
        $grouped = [];
        foreach ($rows as $row) {
            $lid = $row['level_id'] ?? 0;
            if (!isset($grouped[$lid])) {
                $grouped[$lid] = [
                    'level_id'   => $lid,
                    'level_name' => $row['level_name'] ?? 'Unknown',
                    'subjects'   => [],
                ];
            }
            $grouped[$lid]['subjects'][] = [
                'subject_id'    => (int) $row['subject_id'],
                'subject_name'  => $row['subject_name'],
                'is_examinable' => (int) $row['is_examinable'],
            ];
        }

        return $this->response->setJSON([
            'success'  => true,
            'subjects' => array_values($grouped),
        ]);
    }

    // -------------------------------------------------------------------------
    // Add subjects directly to school (without stream assignment)
    // -------------------------------------------------------------------------
    public function addSchoolSubject($schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $schId      = (int) $schId;
        $subjectIds = $this->request->getPost('subjectIds') ?? [];

        if (empty($subjectIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select at least one subject.']);
        }

        $db    = \Config\Database::connect();
        $added = [];

        foreach ($subjectIds as $subjectId) {
            $subjectId = (int) $subjectId;

            // Skip if already registered
            $existing = $db->table('sch_subject')
                ->where('sch_id_fk', $schId)
                ->where('subject_id_fk', $subjectId)
                ->get()->getRowArray();
            if ($existing) continue;

            $db->table('sch_subject')->insert([
                'sch_id_fk'      => $schId,
                'subject_id_fk'  => $subjectId,
                'sch_dept_id_fk' => null,
                'sch_sub_status' => 'Active',
            ]);
            $schSubId = (int) $db->insertID();

            $subject = $this->subjectModel->find($subjectId);
            $level   = $db->table('level')
                ->where('level_id', $subject['level_id_fk'] ?? 0)
                ->get()->getRowArray();

            $added[] = [
                'sch_sub_id'     => $schSubId,
                'subject_name'   => $subject['subject_name'] ?? '',
                'level_id'       => (int) ($subject['level_id_fk'] ?? 0),
                'level_name'     => $level['level_name'] ?? '',
                'sch_sub_status' => 'Active',
            ];
        }

        return $this->response->setJSON([
            'success'   => true,
            'message'   => count($added) . ' subject(s) added successfully.',
            'added'     => $added,
            'csrf_hash' => csrf_hash(),
        ]);
    }

}
