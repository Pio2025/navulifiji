<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\DistrictModel;
use App\Models\ProvinceModel;
use App\Models\DepartmentModel;
use App\Models\SubscriptionModel;
use App\Models\SchoolModel;
use App\Models\SubjectModel;
use App\Models\SchoolSubjectModel;
use App\Models\LevelModel;
use App\Models\SchoolLevelModel;
use App\Models\SchoolStreamModel;
use App\Models\SchoolDepartmentModel;
use App\Models\StreamCoreSubjectModel;
use App\Models\StreamOptionalSubjectModel;
use App\Models\UserModel;
use App\Models\UserSessionModel;
use App\Models\UserRoleModel;
use App\Models\UserPasswordModel;
use App\Models\UserLogModel;
use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\RolePermissionModel;
use App\Models\ModuleModel;
use App\Models\NextOfKinModel;
use App\Models\SchoolCategoryModel;
use App\Models\PlanModel;
use App\Models\AdmissionModel;
use App\Models\EnrolmentModel;
use App\Models\LaunchNotificationModel;
use App\Models\GeneratedReferenceModel;
use App\Models\ReferenceCategoryModel;
use App\Models\UserMedicalModel;
use App\Models\UserMedicalFilesModel;
use App\Models\TwoFactorModel;
use App\Models\UserNotificationModel;
use App\Models\ClassroomModel;
use App\Models\ClassroomStaffModel;
use App\Models\ParentStudentModel;
use App\Models\StudentAttendanceModel;
use App\Models\StudentAttendanceFileModel;
use App\Models\ExamModel;
use App\Models\StudentExamModel;
use App\Models\ExamRegistrationModel;
use App\Models\ExamSubjectModel;
use App\Models\ConductTypeModel;
use App\Models\ConductIncidentModel;
use App\Models\ConductIncidentFileModel;
use App\Models\ConductActionModel;
use App\Models\ConductNotificationModel;
use App\Models\ConductAppealModel;
use App\Models\ConductAppealFileModel;
use App\Models\SchoolCategoryConfigModel;
use App\Models\SchoolCategoryTermModel;
use App\Models\PublicHolidayModel;



/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $provinceModel;
    protected $districtModel;   
    protected $departmentModel;
    protected $schoolModel;
    protected $subjectModel;
    protected $schoolSubjectModel;
    protected $subscriptionModel;
    protected $levelModel;
    protected $schoolLevelModel;
    protected $schoolDepartmentModel;
    protected $schoolStreamModel;
    protected $streamCoreSubjectModel;
    protected $streamOptionalSubjectModel;
    protected $userModel;
    protected $userSessionModel;
    protected $userRoleModel;
    protected $userPasswordModel;
    protected $userLogModel;
    protected $roleModel;
    protected $permissionModel;
    protected $rolePermissionModel;
    protected $moduleModel;
    protected $nextOfKinModel;
    protected $schoolCategoryModel;
    protected $planModel;
    protected $admissionModel;
    protected $enrolmentModel;
    protected $launchNotificationModel;
    protected $generatedReferenceModel;
    protected $referenceCategoryModel;
    protected $userMedicalModel;
    protected $userMedicalFilesModel;
    protected $twoFactorModel;
    protected $userNotificationModel;
    protected $classroomModel;
    protected $classroomStaffModel;
    protected $classroomLessonModel;
    protected $lessonDiscussionModel;
    protected $parentStudentModel;
    protected $studentAttendanceModel;
    protected $studentAttendanceFileModel;
    protected $lessonQuizzeModel;
    protected $lessonQuizzeAttemptModel;
    protected $subjectFeedbackModel;
    protected $classDiscussionModel;
    protected $termExamModel;
    protected $lessonDragDropModel;
    protected $lessonLabelModel;
    protected $examModel;
    protected $studentExamModel;
    protected $examRegistrationModel;
    protected $examSubjectModel;
    protected $conductTypeModel;
    protected $conductIncidentModel;
    protected $conductIncidentFileModel;
    protected $conductActionModel;
    protected $conductNotificationModel;
    protected $conductAppealModel;
    protected $conductAppealFileModel;
    protected $schoolCategoryConfigModel;
    protected $schoolCategoryTermModel;
    protected $publicHolidayModel;


    /**
     * Client IP Address
     *
     * @var string
     */
    protected $ipAddress;
    
    /**
     * User Agent Object
     *
     * @var \CodeIgniter\HTTP\UserAgent
     */
    protected $userAgent;
    
    /**
     * Device Information Array
     *
     * @var array
     */
    protected $deviceInfo;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['session','form', 'url', 'security', 'text', 'array', 'cookie', 'date', 'html', 'number', 'inflector','stream_display','subject_display','stream_subject'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
     
    protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = service('session');
        
        // You can also set common session data here
        $this->initializeCommonSessionData();
        
        //load all models
        $this->provinceModel = new ProvinceModel();
        $this->districtModel = new DistrictModel();
        $this->departmentModel = new DepartmentModel();
        $this->schoolModel = new SchoolModel();
        $this->subjectModel = new SubjectModel();
        $this->schoolSubjectModel = new SchoolSubjectModel();
        $this->subscriptionModel = new SubscriptionModel();
        $this->levelModel = new LevelModel();
        $this->schoolLevelModel = new SchoolLevelModel();
        $this->schoolDepartmentModel = new SchoolDepartmentModel();
        $this->schoolStreamModel = new SchoolStreamModel();
        $this->streamCoreSubjectModel = new StreamCoreSubjectModel();
        $this->streamOptionalSubjectModel = new StreamOptionalSubjectModel();
        $this->userModel = new UserModel();
        $this->userRoleModel = new UserRoleModel();
        $this->userPasswordModel = new UserPasswordModel();
        $this->userLogModel = new UserLogModel();
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->moduleModel = new ModuleModel();
        $this->nextOfKinModel = new NextOfKinModel();
        $this->schoolCategoryModel = new SchoolCategoryModel();
        $this->planModel = new PlanModel();
        $this->admissionModel = new AdmissionModel();
        $this->enrolmentModel = new EnrolmentModel();
        $this->launchNotificationModel = new LaunchNotificationModel();
        $this->userSessionModel = new UserSessionModel();
        $this->generatedReferenceModel = new GeneratedReferenceModel();
        $this->referenceCategoryModel  = new ReferenceCategoryModel();
        $this->userMedicalModel      = new UserMedicalModel();
        $this->userMedicalFilesModel = new UserMedicalFilesModel();
        $this->twoFactorModel = new TwoFactorModel();
        $this->userNotificationModel = new UserNotificationModel();
        $this->classroomModel          = new ClassroomModel();
        $this->classroomStaffModel     = new ClassroomStaffModel();
        $this->classroomLessonModel    = new \App\Models\ClassroomLessonModel();
        $this->lessonDiscussionModel   = new \App\Models\LessonDiscussionModel();
        $this->lessonDiscussionModel->ensureTables();
        $this->parentStudentModel         = new ParentStudentModel();
        $this->studentAttendanceModel     = new StudentAttendanceModel();
        $this->studentAttendanceFileModel = new StudentAttendanceFileModel();
        $this->lessonQuizzeModel          = new \App\Models\LessonQuizzeModel();
        $this->lessonQuizzeAttemptModel   = new \App\Models\LessonQuizzeAttemptModel();
        $this->subjectFeedbackModel       = new \App\Models\SubjectFeedbackModel();
        $this->classDiscussionModel       = new \App\Models\ClassDiscussionModel();
        $this->classDiscussionModel->ensureTables();
        $this->termExamModel              = new \App\Models\TermExamModel();
        $this->termExamModel->ensureTables();
        $this->lessonDragDropModel        = new \App\Models\LessonDragDropModel();
        $this->lessonDragDropModel->ensureTables();
        $this->lessonLabelModel           = new \App\Models\LessonLabelModel();
        $this->lessonLabelModel->ensureTables();
        $this->examModel              = new ExamModel();
        $this->studentExamModel       = new StudentExamModel();
        $this->examRegistrationModel  = new ExamRegistrationModel();
        $this->examSubjectModel       = new ExamSubjectModel();
        $this->conductTypeModel         = new ConductTypeModel();
        $this->conductIncidentModel     = new ConductIncidentModel();
        $this->conductIncidentFileModel = new ConductIncidentFileModel();
        $this->conductActionModel       = new ConductActionModel();
        $this->conductNotificationModel = new ConductNotificationModel();
        $this->conductAppealModel           = new ConductAppealModel();
        $this->conductAppealFileModel       = new ConductAppealFileModel();
        $this->schoolCategoryConfigModel    = new SchoolCategoryConfigModel();
        $this->schoolCategoryTermModel      = new SchoolCategoryTermModel();
        $this->publicHolidayModel           = new PublicHolidayModel();
        $this->publicHolidayModel->ensureTable();

        // ===================================================
        // ADD THIS SECTION FOR IP & DEVICE TRACKING
        // ===================================================
        
        // Get User Agent
        $this->userAgent = $this->request->getUserAgent();
        
        // Get IP Address
        $this->ipAddress = $this->getClientIP();
        
        // Get Device Information
        $this->deviceInfo = $this->getDeviceInfo();
        
        // Log initial access (optional)
        $this->logInitialAccess();
        
        
    }
    
    /**
     * Get location data from IP address using free ip-api.com
     */
    protected function getLocationFromIP(string $ip): array
    {
        // Skip for local/private IPs
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return ['country' => 'Local', 'city' => 'Local'];
        }
    
        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country,city,status");
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['status']) && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? 'Unknown',
                        'city'    => $data['city']    ?? 'Unknown',
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('warning', '[BaseController::getLocationFromIP] ' . $e->getMessage());
        }
    
        return ['country' => 'Unknown', 'city' => 'Unknown'];
    }
    
    /**
     * Parse browser and OS from user agent string
     */
    protected function parseUserAgent(string $ua): array
    {
        $browser = 'Unknown';
        $os      = 'Unknown';
    
        // Detect browser
        if (str_contains($ua, 'Edg/'))         $browser = 'Edge';
        elseif (str_contains($ua, 'OPR/'))     $browser = 'Opera';
        elseif (str_contains($ua, 'Chrome/'))  $browser = 'Chrome';
        elseif (str_contains($ua, 'Firefox/')) $browser = 'Firefox';
        elseif (str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome/')) $browser = 'Safari';
    
        // Detect OS
        if (str_contains($ua, 'Windows NT'))      $os = 'Windows';
        elseif (str_contains($ua, 'Mac OS X'))    $os = 'macOS';
        elseif (str_contains($ua, 'Android'))     $os = 'Android';
        elseif (str_contains($ua, 'iPhone'))      $os = 'iOS';
        elseif (str_contains($ua, 'iPad'))        $os = 'iPadOS';
        elseif (str_contains($ua, 'Linux'))       $os = 'Linux';
    
        return ['browser' => $browser, 'os' => $os];
    }
    
    /**
     * Initialize common session data used across all controllers
     */
    protected function initializeCommonSessionData()
    {
        // Set site-wide session variables
        if (!$this->session->has('site_initialized')) {
            $this->session->set([
                'site_initialized' => true,
                'site_language' => 'en',
                'timezone' => 'Pacific/Fiji',
                'currency' => 'FJD'
            ]);
        }
    }
    
    protected function loadCommonData($view, $additionalData = []){
        $data = array();
        $schStatus = $this->session->get('schStatus');
        
        $data['initial'] = $this->session->get('initial');
        $data['name'] = $this->session->get('name');
        $data['email'] = $this->session->get('email');
        $data['status'] = $this->session->get('status');
        $data['gender'] = $this->session->get('gender');
        $data['fname'] = $this->session->get('fname');
        $data['address'] = $this->session->get('address');
        $data['phone'] = $this->session->get('phone');
        $data['photo'] = $this->session->get('photo');
        $data['userID'] = $this->session->get('userID');
        $data['roleID'] = $this->session->get('roleID');
        
        $data['_view'] = $view;
        
        // Merge additional data
        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }
        
        return $data;
    }
    
    /* 
        ========================================================================
        
            Middleware to manage access right
            
        ========================================================================
    */
    
    /**
     * Check if user has access to a specific permission
     * Optimized version with caching for better performance
     *
     * @param string $perm_code
     * @return mixed Returns permission data if granted, false if denied
     */
    protected function grant_access($perm_code)
    {
        // Get role_id from session
        $role_id = $this->session->get('roleID');
        $user_id = $this->session->get('userID');
        
        if (!$role_id || !$user_id) {
            return false;
        }
        
        // Check if permission exists for this role
        $permission = $this->rolePermissionModel->grant_role_access($role_id, $perm_code);
        
        $hasAccess = !empty($permission);
        if($hasAccess){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Require access with automatic redirects
     * Use this in your controller methods
     *
     * @param string $perm_code
     * @return bool|RedirectResponse
     */
    protected function require_access($perm_code)
    {
        if (!$this->grant_access($perm_code)) {
            // You can log unauthorized access attempts here
            log_message('warning', "Unauthorized access attempt by user {$this->session->get('user_id')} to {$perm_code}");
            return false;
        }
        
        return true;
    }
    
    
    /**
     * Check if user is logged in
     *
     * @return bool
     */
    protected function isLoggedIn()
    {
        return $this->session->get('isLoggedIn') === true;
    }
    
    /**
     * Clear permission cache (call this when permissions change)
     */
    protected function clearPermissionCache()
    {
        $user_id = $this->session->get('user_id');
        if ($user_id) {
            $this->session->remove("user_{$user_id}_permissions");
        }
    }
    
    /**
     * Set page metadata helper method
     */
    protected function setPageData($title = '', $navMenu = '', $subNavMenu = '')
    {
        $this->session->set('page_title', $title);
        $this->session->set('nav_menu', $navMenu);
        $this->session->set('sub_nav_menu', $subNavMenu);
        $this->session->set('prevUrl', $this->session->get('currentUrl'));
        $this->session->set('currentUrl', current_url());
    }
    
    
    /**
     * ===================================================
     * IP ADDRESS & DEVICE TRACKING METHODS
     * ===================================================
     */
    
    /**
     * Get client IP address with proxy support
     */
    protected function getClientIP(): string
    {
        $ip = $this->request->getIPAddress();
        
        // If behind proxy/load balancer, check forwarded headers
        if ($this->request->getServer('HTTP_X_FORWARDED_FOR')) {
            $forwardedIps = explode(',', $this->request->getServer('HTTP_X_FORWARDED_FOR'));
            $ip = trim($forwardedIps[0]);
        } elseif ($this->request->getServer('HTTP_CLIENT_IP')) {
            $ip = $this->request->getServer('HTTP_CLIENT_IP');
        }
        
        // Validate IP address
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }
    
    /**
     * Get complete device information
     */
    protected function getDeviceInfo(): array
    {
        $agent = $this->userAgent;
        
        return [
            'ip_address'      => $this->ipAddress,
            'user_agent'      => $agent->getAgentString(),
            'browser'         => $agent->isBrowser() ? $agent->getBrowser() : 'Unknown',
            'browser_version' => $agent->isBrowser() ? $agent->getVersion() : 'Unknown',
            'platform'        => $agent->getPlatform(),
            'device_type'     => $this->detectDeviceType(),
            'is_mobile'       => $agent->isMobile(),
            'is_robot'        => $agent->isRobot(),
            'is_browser'      => $agent->isBrowser(),
            'referrer'        => $agent->getReferrer() ?: 'Direct',
            'language'        => $this->request->getHeaderLine('Accept-Language'),
        ];
    }
    
    /**
     * Detect device type (Mobile/Tablet/Desktop/Robot)
     */
    protected function detectDeviceType(): string
    {
        $agent = $this->userAgent;
        
        if ($agent->isRobot()) {
            return 'Robot';
        }
        
        if ($agent->isMobile()) {
            $mobile = $agent->getMobile();
            
            // Check for tablet
            if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i', $mobile)) {
                return 'Tablet';
            }
            return 'Mobile';
        }
        
        return 'Desktop';
    }
    
    /**
     * Log initial access to the application (optional)
     */
    protected function logInitialAccess(): void
    {
        // Only log if not already logged for this session
        if (!$this->session->has('access_logged')) {
            
            $logData = [
                'session_id'    => session_id(),
                'user_id'       => $this->session->get('user_id') ?? null,
                'ip_address'    => $this->ipAddress,
                'user_agent'    => substr($this->deviceInfo['user_agent'], 0, 255),
                'browser'       => $this->deviceInfo['browser'],
                'platform'      => $this->deviceInfo['platform'],
                'device_type'   => $this->deviceInfo['device_type'],
                'referrer'      => $this->deviceInfo['referrer'],
                'access_time'   => date('Y-m-d H:i:s'),
                'page_url'      => current_url(),
            ];
            
            // Save to your UserLogModel if needed
            // $this->userLogModel->insert($logData);
            
            // Mark as logged for this session
            $this->session->set('access_logged', true);
            $this->session->set('initial_access_time', date('Y-m-d H:i:s'));
        }
    }
    
    /**
     * Log user activity (call this method from any controller)
     * 
     * @param string $action
     * @param array $additionalData
     * @return void
     */
    protected function logActivity(string $action, array $additionalData = []): void
    {
        $logData = [
            'user_id'     => $this->session->get('user_id') ?? null,
            'session_id'  => session_id(),
            'ip_address'  => $this->ipAddress,
            'user_agent'  => substr($this->deviceInfo['user_agent'], 0, 255),
            'action'      => $action,
            'page_url'    => current_url(),
            'referrer'    => $this->deviceInfo['referrer'],
            'logged_at'   => date('Y-m-d H:i:s'),
        ];
        
        // Merge with additional data
        $logData = array_merge($logData, $additionalData);
        
        // Save to your UserLogModel
        $this->userLogModel->insert($logData);
    }
    
    /**
     * Quick access to IP address
     * 
     * @return string
     */
    protected function getIP(): string
    {
        return $this->ipAddress;
    }
    
    /**
     * Quick check if user is on mobile
     * 
     * @return bool
     */
    protected function isMobile(): bool
    {
        return $this->userAgent->isMobile();
    }
    
    /**
     * Get browser name
     * 
     * @return string
     */
    protected function getBrowser(): string
    {
        return $this->deviceInfo['browser'];
    }
    
    /**
     * Send email using a view template
     *
     * @param array $data {
     *     @type string $to          Recipient email address (required)
     *     @type string $subject     Email subject (required)
     *     @type string $view        View file path e.g. 'email/welcome' (required)
     *     @type array  $viewData    Data to pass into the view template (optional)
     *     @type string $from        Sender email (optional, defaults to noreply@navulifiji.com)
     *     @type string $fromName    Sender name (optional, defaults to 'Navuli Fiji')
     *     @type string $replyTo     Reply-to email (optional)
     * }
     * @return bool
     */
    protected function sendEmail(array $data): bool
    {
        try {
            $emailService = \Config\Services::email();
    
            $emailService->initialize([
                'protocol'    => 'smtp',
                'SMTPHost'    => 'mail.navulifiji.com',
                'SMTPUser'    => 'noreply@navulifiji.com',
                'SMTPPass'    => 'N0r3pp!@25',
                'SMTPPort'    => 587,
                'SMTPCrypto'  => 'tls',
                'mailType'    => 'html',
                'charset'     => 'utf-8',
                'wordWrap'    => true,
                'SMTPTimeout' => 30,
                'newline'     => "\r\n",
                'CRLF'        => "\r\n"
            ]);
    
            $from     = $data['from']     ?? 'noreply@navulifiji.com';
            $fromName = $data['fromName'] ?? 'Navuli Fiji';
            $replyTo  = $data['replyTo'] ?? $from;
    
            $emailService->setFrom($from, $fromName);
            $emailService->setTo($data['to']);
            $emailService->setSubject($data['subject']);
            $emailService->setReplyTo($replyTo, $fromName);
    
            $emailService->setHeader('Precedence', '1');
            $emailService->setHeader('X-Mailer', 'Navuli Fiji Mailer');
            $emailService->setHeader('List-Unsubscribe', '<mailto:' . $from . '>');
    
            // Render the view template with the provided data
            $viewData = $data['viewData'] ?? [];
            $emailMessage = view($data['view'], $viewData);
    
            $emailService->setMessage($emailMessage);
    
            $result = $emailService->send();
    
            if (!$result) {
                log_message('error', '[BaseController::sendEmail] Failed to send email to ' . $data['to'] . ' | Debug: ' . $emailService->printDebugger(['headers']));
            }
    
            return $result;
    
        } catch (\Exception $e) {
            log_message('error', '[BaseController::sendEmail] Exception: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Calculate subscription end date
     */
    protected function calculateSubscriptionEnd($months)
    {
        return date('Y-m-d', strtotime("+{$months} months"));
    }
    
    protected function generateAcronym(?string $name): string
    {
        if (empty($name)) return '';
        $words   = explode(' ', trim($name));
        $acronym = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $acronym .= strtoupper($word[0]);
            }
        }
        return substr($acronym, 0, 3);
    }
    
}
