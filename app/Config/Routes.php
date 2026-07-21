<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Custom 404 handler — renders inside main layout when logged in
$routes->set404Override('App\Controllers\ErrorController::show404');

// Catch malformed URLs and redirect to home
//$routes->get('.*', 'Home::index');

// School registration routes - both GET and POST to same method
$routes->match(['GET', 'POST'], 'account/subscribe', 'AccountController::subscribe');
$routes->get('help/index', 'Help::index');
$routes->get('help/', 'Help::index');

// Make sure you have a route for district controller
$routes->post('district/getDistrictByProvince', 'DistrictController::getDistrictByProvince');
$routes->get('district/getDistrictByProvince', 'DistrictController::getDistrictByProvince');
$routes->get('district/getDistrictByProvince2', 'DistrictController::getDistrictByProvince2');
$routes->post('district/getDistrictByProvince2', 'DistrictController::getDistrictByProvince2');
$routes->get('district/', 'DistrictController::index');
$routes->get('district/index', 'DistrictController::index');

$routes->post('account/kill', 'AccountController::kill');
$routes->get('account/progress', 'AccountController::progress'); // For displaying the form


$routes->match(['GET', 'POST'], 'auth/school', 'AuthController::school');

$routes->get('school', 'SchoolController::index');
$routes->get('school/index', 'SchoolController::index');

$routes->get('school/setup/(:num)', 'SchoolController::setup/$1');
$routes->get('school/dashboard', 'SchoolController::dashboard');
$routes->get('school/login', 'SchoolController::login');
$routes->post('school/login', 'SchoolController::process_login');
$routes->match(['GET', 'POST'], 'school/logout', 'SchoolController::logout');
$routes->post('school/configure', 'SchoolController::configure');


$routes->match(['GET', 'POST'], 'school/get_level_subject', 'SchoolController::get_level_subject');
$routes->post('school/addcoresubject', 'SchoolController::addcoresubject');
$routes->post('school/addoptionalsubject', 'SchoolController::addoptionalsubject');

//deleting core and optional subject
$routes->post('school/remove_core', 'SchoolController::remove_core');
$routes->post('school/remove_optional', 'SchoolController::remove_optional');

//temp remove later

$routes->get('school/email', 'SchoolController::email');

//navigation on school profile
$routes->get('school/overview', 'SchoolController::overview');
$routes->get('school/department', 'SchoolController::department');
$routes->get('school/level', 'SchoolController::level');
$routes->get('school/stream', 'SchoolController::stream');
$routes->get('school/subject', 'SchoolController::subject');
$routes->get('school/extracurricular', 'SchoolController::extracurricular');

// School Management (Admin)
$routes->get('school', 'SchoolController::index');
$routes->get('school/detail/(:num)', 'SchoolController::detail/$1');
$routes->get('school/get-departments/(:num)', 'SchoolController::getDepartmentsJson/$1');
$routes->post('school/save-departments/(:num)', 'SchoolController::saveDepartments/$1');
$routes->get('school/get-levels/(:num)', 'SchoolController::getLevelsJson/$1');
$routes->post('school/save-levels/(:num)', 'SchoolController::saveLevels/$1');
$routes->post('school/update-department/(:num)', 'SchoolController::updateDepartmentStatus/$1');
$routes->post('school/delete-department/(:num)', 'SchoolController::deleteDepartment/$1');
$routes->post('school/delete-level/(:num)', 'SchoolController::deleteLevel/$1');
$routes->post('school/add-stream/(:num)', 'SchoolController::addStream/$1');
$routes->post('school/delete-stream/(:num)', 'SchoolController::deleteStream/$1');
$routes->get('school/get-stream-subjects/(:num)', 'SchoolController::getStreamSubjectsJson/$1');
$routes->post('school/add-core-subject/(:num)', 'SchoolController::addCoreSubject/$1');
$routes->post('school/delete-core-subject/(:num)', 'SchoolController::deleteCoreSubject/$1');
$routes->post('school/edit-core-subject/(:num)', 'SchoolController::editCoreSubject/$1');
$routes->get('school/get-stream-optional-subjects/(:num)', 'SchoolController::getStreamOptionalSubjectsJson/$1');
$routes->post('school/add-optional-subjects/(:num)', 'SchoolController::addOptionalSubjects/$1');
$routes->post('school/delete-optional-subject/(:num)', 'SchoolController::deleteOptionalSubject/$1');
$routes->post('school/edit-sch-subject/(:num)', 'SchoolController::editSchSubject/$1');
$routes->post('school/delete-sch-subject/(:num)', 'SchoolController::deleteSchSubject/$1');
$routes->get( 'school/available-subjects/(:num)', 'SchoolController::getAvailableSubjectsForSchool/$1');
$routes->post('school/add-school-subject/(:num)', 'SchoolController::addSchoolSubject/$1');
$routes->get('school/add', 'SchoolController::add');
$routes->post('school/store', 'SchoolController::store');
$routes->get('school/edit/(:num)', 'SchoolController::edit/$1');
$routes->post('school/edit/(:num)', 'SchoolController::edit/$1');
$routes->post('school/delete/(:num)', 'SchoolController::delete/$1');

//$routes->post('school/getSchoolListing', 'SchoolController::getSchoolListing');
// ✅ FIX: Allow both GET and POST
$routes->match(['GET', 'POST'], 'school/getSchoolListing', 'SchoolController::getSchoolListing');




$routes->get('account/overview', 'SchoolController::account');
$routes->get('account/setting', 'SchoolController::setting');
$routes->get('account/billing', 'SchoolController::billing');
$routes->get('school/faq', 'SchoolController::faq');
$routes->get('school/guide', 'SchoolController::guide');




$routes->get('school/update/(:num)', 'SchoolController::update/$1');
$routes->post('school/processUpdate/(:num)', 'SchoolController::processUpdate/$1');
$routes->post('school/edit/(:num)', 'SchoolController::edit/$1');


$routes->get('account/activate/(:alphanum)', 'AccountController::activate/$1');


$routes->get('auth', 'AuthController::login');
$routes->get('auth/login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::process_login');
$routes->match(['GET', 'POST'], 'auth/logout', 'AuthController::logout');

// 2FA Setup routes (logged in users)
$routes->post('auth/2fa/setup-authenticator',  'TwoFactorController::setupAuthenticator');
$routes->post('auth/2fa/verify-authenticator', 'TwoFactorController::verifyAuthenticator');
$routes->post('auth/2fa/setup-otp-email',      'TwoFactorController::setupOtpEmail');
$routes->post('auth/2fa/verify-otp-email',     'TwoFactorController::verifyOtpEmail');
$routes->post('auth/2fa/disable',              'TwoFactorController::disable');
$routes->get( 'auth/2fa/status',              'TwoFactorController::status');

// 2FA Login flow routes
$routes->get( 'auth/2fa/verify',              'TwoFactorController::verify');
$routes->post('auth/2fa/verify',              'TwoFactorController::processVerify');
$routes->post('auth/2fa/resend-otp',         'TwoFactorController::resendOtp');

$routes->get( 'auth/forgot-password',          'AuthController::forgotPassword');
$routes->post('auth/forgot-password',          'AuthController::processForgotPassword');
$routes->get( 'auth/reset-password/(:segment)', 'AuthController::resetPassword/$1');
$routes->post('auth/reset-password/(:segment)', 'AuthController::processResetPassword/$1');

$routes->get('dashboard', 'DashboardController::index');
$routes->get('dashboard/unread-counts', 'DashboardController::unreadCounts');
$routes->post('dashboard/mark-read', 'DashboardController::markRead');

// ============================================================================
// NOTICES (combined parent-facing notices + announcements read-only view)
// ============================================================================
$routes->get('notices', 'NoticesController::index');

// ============================================================================
// NOTICE BOARD Routes
// ============================================================================
$routes->get( 'dashboard/notice',                  'NoticeBoardController::index');
$routes->post('dashboard/notice/store',            'NoticeBoardController::store');
$routes->post('dashboard/notice/(:num)/update',    'NoticeBoardController::update/$1');
$routes->post('dashboard/notice/(:num)/delete',    'NoticeBoardController::delete/$1');
$routes->post('dashboard/notice/(:num)/pin',       'NoticeBoardController::togglePin/$1');

// ============================================================================
// ANNOUNCEMENT Routes
// ============================================================================
$routes->get( 'dashboard/announcement',                    'AnnouncementController::index');
$routes->post('dashboard/announcement/store',              'AnnouncementController::store');
$routes->post('dashboard/announcement/(:num)/update',      'AnnouncementController::update/$1');
$routes->post('dashboard/announcement/(:num)/delete',      'AnnouncementController::delete/$1');
$routes->get( 'dashboard/announcement/(:num)/download',    'AnnouncementController::download/$1');


$routes->get('user/activate/(:num)', 'UserController::activate/$1');



// ============================================================================
// ROLE Routes
// ============================================================================
// Add this route BEFORE any wildcard routes
$routes->post('role/getRoleListing', 'RoleController::getRoleListing');
$routes->get('role', 'RoleController::index');
$routes->get('role/add', 'RoleController::add');
$routes->post('role/store', 'RoleController::store');
$routes->get('role/detail/(:num)', 'RoleController::detail/$1');
$routes->get('role/edit/(:num)', 'RoleController::edit/$1');
$routes->post('role/update/(:num)', 'RoleController::update/$1');
$routes->post('role/delete/(:num)', 'RoleController::delete/$1');
$routes->get('role/permission/(:num)', 'RoleController::permission/$1');
$routes->post('role/permission/(:num)', 'RoleController::updatePermissions/$1');

// ============================================================================
// PERMISSION Routes
// ============================================================================
$routes->get('permission', 'PermissionController::index');
$routes->get('permission/add', 'PermissionController::add');
$routes->get('permission/detail/(:num)', 'PermissionController::detail/$1');
$routes->get('permission/edit/(:num)', 'PermissionController::edit/$1');

// POST routes after GET routes
$routes->post('permission/getPermissionListing', 'PermissionController::getPermissionListing');
$routes->post('permission/store', 'PermissionController::store');
$routes->post('permission/update/(:num)', 'PermissionController::update/$1');
$routes->post('permission/delete/(:num)', 'PermissionController::delete/$1');

// ============================================================================
// USER Routes
// ============================================================================
$routes->get('user', 'UserController::index');
$routes->get('user/add', 'UserController::add');
$routes->get('user/generate-username', 'UserController::generateUsername');
$routes->get('user/detail/(:num)', 'UserController::detail/$1');
$routes->get('profile/my',         'UserController::my');
$routes->get('user/edit/(:num)', 'UserController::edit/$1');
//$routes->get('user/medical/(:num)', 'UserController::medical/$1');

// POST routes after GET routes
$routes->post('user/getUserListing', 'UserController::getUserListing');
$routes->get('user/chatUserList',   'UserController::getChatUserList');
$routes->get('user/chatUserInfo/(:num)', 'UserController::getChatUserInfo/$1');

// ============================================================================
// CHAT Routes
// ============================================================================
$routes->get( 'chat/token',                    'ChatController::getToken');
$routes->get( 'chat/unread-count',             'ChatController::getUnreadCount');
$routes->get( 'chat/unread-per-user',          'ChatController::getUnreadPerUser');
$routes->get( 'chat/conversations',            'ChatController::getConversations');
$routes->get( 'chat/conversation/(:num)',      'ChatController::getOrCreateConversation/$1');
$routes->get( 'chat/messages/(:num)',          'ChatController::getMessages/$1');
$routes->get( 'chat/messages/(:num)/new',     'ChatController::getNewMessages/$1');
$routes->post('chat/message/(:num)/delete',   'ChatController::deleteMessage/$1');
$routes->post('chat/conversation/(:num)/clear', 'ChatController::clearConversation/$1');
$routes->post('chat/messages',                 'ChatController::sendMessage');
$routes->post('chat/upload',                   'ChatController::uploadFile');
$routes->post('chat/read/(:num)',              'ChatController::markRead/$1');
$routes->post('chat/call-event',               'ChatController::callEvent');
$routes->post('chat/message/(:num)/react',     'ChatController::reactMessage/$1');
$routes->post('chat/block/(:num)',             'ChatController::block/$1');
$routes->get( 'chat/block-status/(:num)',      'ChatController::blockStatus/$1');
$routes->post('chat/internal/block-check',     'ChatController::internalBlockCheck');
$routes->get( 'chat/transcript/(:num)',        'ChatController::transcript/$1');

$routes->get('message',                        'MessageController::index');
$routes->get('message/(:num)',                 'MessageController::index/$1');

$routes->post('user/store', 'UserController::store');
$routes->post('user/update/(:num)', 'UserController::update/$1');
$routes->post('user/delete/(:num)', 'UserController::delete/$1');

$routes->post('user/updateEmail', 'UserController::updateEmail');
$routes->post('user/updatePassword', 'UserController::updatePassword');
$routes->post('user/updateRole', 'UserController::updateRole');

$routes->get('user/verifyemail/(:segment)', 'UserController::verifyEmail/$1');

$routes->post('user/signOutSession/(:num)',    'UserController::signOutSession/$1');
$routes->post('user/signOutAllSessions/(:num)', 'UserController::signOutAllSessions/$1');

$routes->get('user/getUserLogs/(:num)',      'UserController::getUserLogs/$1');
$routes->get('user/downloadUserLogs/(:num)', 'UserController::downloadUserLogs/$1');

$routes->get( 'user/notification',               'UserController::notification');
$routes->get( 'user/getNotifications',           'UserController::getNotifications');
$routes->post('user/markNotificationsRead',      'UserController::markNotificationsRead');

$routes->get( 'user/medical/(:num)',              'MedicalController::index/$1');
$routes->get( 'user/medical/add/(:num)',           'MedicalController::add/$1');
$routes->post('user/medical/store/(:num)',         'MedicalController::store/$1');
$routes->get( 'user/medical/edit/(:num)',          'MedicalController::edit/$1');
$routes->post('user/medical/update/(:num)',        'MedicalController::update/$1');
$routes->post('user/medical/delete/(:num)',        'MedicalController::delete/$1');
$routes->post('user/medical/delete-file/(:num)',   'MedicalController::deleteFile/$1');
$routes->get( 'user/medical/file/(:num)',          'MedicalController::viewFile/$1');

$routes->post('user/saveNotifications', 'UserController::saveNotifications');


// Next of Kin Routes
$routes->post('nextofkin/add', 'NextOfKinController::add');
$routes->post('nextofkin/update', 'NextOfKinController::update');
$routes->get('nextofkin/get/(:num)', 'NextOfKinController::get/$1');
$routes->post('nextofkin/delete/(:num)', 'NextOfKinController::delete/$1');


// ============================================================================
// LAUNCH NOTIFICATION Routes (Add BEFORE any wildcard routes)
// ============================================================================
$routes->get('coming-soon', 'LaunchController::index');
$routes->get('launch', 'LaunchController::index'); // Alternative URL
$routes->post('launch/subscribe', 'LaunchController::subscribe'); // Changed from api/launch/subscribe
$routes->get('launch/subscriber-count', 'LaunchController::getSubscriberCount');
$routes->get('launch/export-subscribers', 'LaunchController::exportSubscribers');

$routes->get('launch/test', 'LaunchController::test');

// Alternative routes for API style
$routes->post('api/launch/subscribe', 'LaunchController::subscribe');
$routes->get('api/launch/subscriber-count', 'LaunchController::getSubscriberCount');


$routes->get('reference/certificate-of-enrollment/(:num)', 'ReferenceController::certificateOfEnrollment/$1');

$routes->get( 'reference/character-reference/(:num)',          'ReferenceController::characterReference/$1');
$routes->post('reference/generate-character-reference/(:num)', 'ReferenceController::generateCharacterReference/$1');
$routes->get( 'reference/user-references/(:num)',              'ReferenceController::userReferences/$1');
$routes->get( 'reference/view/(:num)',                         'ReferenceController::viewReference/$1');

// Certificate of Enrollment
$routes->get( 'reference/certificate-of-enrollment/(:num)',  'ReferenceController::certificateOfEnrollment/$1');
$routes->post('reference/generate-enrollment/(:num)',         'ReferenceController::generateEnrollment/$1');

// Character Reference
$routes->get( 'reference/character-reference/(:num)',          'ReferenceController::characterReference/$1');
$routes->post('reference/generate-character-reference/(:num)', 'ReferenceController::generateCharacterReference/$1');

// Recommendation Letter
$routes->get( 'reference/recommendation-letter/(:num)',  'ReferenceController::recommendationLetter/$1');
$routes->post('reference/generate-recommendation/(:num)', 'ReferenceController::generateRecommendation/$1');

// Conduct Certificate
$routes->get( 'reference/conduct-certificate/(:num)',  'ReferenceController::conductCertificate/$1');
$routes->post('reference/generate-conduct/(:num)',      'ReferenceController::generateConduct/$1');

// Clearance Certificate
$routes->get( 'reference/clearance-certificate/(:num)', 'ReferenceController::clearanceCertificate/$1');
$routes->post('reference/generate-clearance/(:num)',     'ReferenceController::generateClearance/$1');

// Certificate of Employment
$routes->get( 'reference/certificate-of-employment/(:num)', 'ReferenceController::certificateOfEmployment/$1');
$routes->post('reference/generate-employment/(:num)',        'ReferenceController::generateEmployment/$1');

// Performance Recommendation
$routes->get( 'reference/performance-recommendation/(:num)', 'ReferenceController::performanceRecommendation/$1');
$routes->post('reference/generate-performance/(:num)',        'ReferenceController::generatePerformance/$1');

// Parent Guardian Certificate
$routes->get( 'reference/parent-guardian-certificate/(:num)', 'ReferenceController::parentGuardianCertificate/$1');
$routes->post('reference/generate-parent-guardian/(:num)',     'ReferenceController::generateParentGuardian/$1');

// Parent Involvement Certificate
$routes->get( 'reference/parent-involvement-certificate/(:num)', 'ReferenceController::parentInvolvementCertificate/$1');
$routes->post('reference/generate-parent-involvement/(:num)',     'ReferenceController::generateParentInvolvement/$1');

// Financial Clearance
$routes->get( 'reference/financial-clearance/(:num)',  'ReferenceController::financialClearance/$1');
$routes->post('reference/generate-financial-clearance/(:num)', 'ReferenceController::generateFinancialClearance/$1');

// View all + view single
$routes->get('reference/user-references/(:num)', 'ReferenceController::userReferences/$1');
$routes->get('reference/view/(:num)',             'ReferenceController::viewReference/$1');

$routes->post('reference/delete/(:num)', 'ReferenceController::deleteReference/$1');

$routes->get( 'reference/transcript-request/(:num)',   'ReferenceController::transcriptRequest/$1');
$routes->post('reference/generate-transcript/(:num)',  'ReferenceController::generateTranscript/$1');

$routes->post('reference/request/store',              'ReferenceController::storeRequest');
$routes->get( 'reference/request',                    'ReferenceController::requests');
$routes->get( 'reference/requests',                   'ReferenceController::requests');
$routes->post('reference/request/update/(:num)',      'ReferenceController::updateRequest/$1');


$routes->post('admission/delete/(:num)', 'AdmissionController::delete/$1');

// ============================================================================
// SCHOOL CATEGORY Routes
// ============================================================================
$routes->get( 'school/category',                'SchoolCategoryController::index');
$routes->get( 'school/category/add',            'SchoolCategoryController::add');
$routes->post('school/category/store',          'SchoolCategoryController::store');
$routes->get( 'school/category/edit/(:num)',    'SchoolCategoryController::edit/$1');
$routes->post('school/category/update/(:num)',  'SchoolCategoryController::update/$1');
$routes->post('school/category/remove/(:num)',  'SchoolCategoryController::delete/$1');

// ============================================================================
// EXAM Routes
// ============================================================================
$routes->get( 'exam',                              'ExamController::index');
$routes->get( 'exam/my',                           'ExamController::my');
$routes->get( 'exam/add',                          'ExamController::add');
$routes->post('exam/store',                        'ExamController::store');
$routes->get( 'exam/detail/(:num)',                        'ExamController::detail/$1');
$routes->get( 'exam/detail/(:num)/school/(:num)',          'ExamController::schoolDetail/$1/$2');
$routes->get( 'exam/edit/(:num)',                  'ExamController::edit/$1');
$routes->post('exam/update/(:num)',                'ExamController::update/$1');
$routes->post('exam/delete/(:num)',                'ExamController::delete/$1');
$routes->post('exam/(:num)/students/add',          'ExamController::addStudents/$1');
$routes->post('exam/student/(:num)/remove',                  'ExamController::removeStudent/$1');
$routes->post('exam/(:num)/school/(:num)/students/drop-all', 'ExamController::dropAllStudents/$1/$2');
$routes->get( 'exam/stream/(:num)/exams',                    'ExamController::getExamsForStream/$1');
$routes->get( 'exam/student/(:num)/marks',                   'ExamController::marks/$1');
$routes->post('exam/student/(:num)/marks/save',              'ExamController::saveMarks/$1');
$routes->get( 'exam/student/(:num)/subjects/assign',          'ExamController::showAssignSubjects/$1');
$routes->post('exam/student/(:num)/subjects/assign',          'ExamController::assignSubjects/$1');
$routes->get( 'exam/detail/(:num)/school/(:num)/report/pdf',       'ExamController::schoolReportPdf/$1/$2');
$routes->get( 'exam/student/(:num)/marks/report/pdf',              'ExamController::studentReportPdf/$1');
$routes->get( 'exam/detail/(:num)/school/(:num)/students/by-year', 'ExamController::studentsByYear/$1/$2');

// ============================================================================
// CONDUCT Routes
// ============================================================================
$routes->get( 'conduct',                        'ConductController::index');
$routes->get( 'conduct/my',                      'ConductController::my');
$routes->get( 'conduct/child/my',               'ConductController::my');
$routes->get( 'conduct/report',                  'ConductController::report');
$routes->get( 'conduct/add',                     'ConductController::add');
$routes->post('conduct/store',                   'ConductController::store');
$routes->get( 'conduct/edit/(:num)',             'ConductController::edit/$1');
$routes->post('conduct/update/(:num)',           'ConductController::update/$1');
$routes->post('conduct/remove/(:num)',           'ConductController::delete/$1');
$routes->get( 'conduct/detail/(:num)',           'ConductController::detail/$1');
$routes->post('conduct/(:num)/actions/add',      'ConductController::addAction/$1');
$routes->post('conduct/action/(:num)/complete',  'ConductController::completeAction/$1');
$routes->post('conduct/(:num)/notify',           'ConductController::notify/$1');
$routes->post('conduct/file/(:num)/delete',      'ConductController::deleteFile/$1');
$routes->get( 'conduct/file/(:num)',             'ConductController::viewFile/$1');
$routes->get( 'conduct/my/detail/(:num)',        'ConductController::myDetail/$1');
$routes->post('conduct/appeal/(:num)',           'ConductController::appeal/$1');
$routes->get( 'conduct/appeals',                 'ConductController::appeals');
$routes->post('conduct/appeal/(:num)/process',   'ConductController::processAppeal/$1');
$routes->get( 'conduct/appeal/file/(:num)',      'ConductController::viewAppealFile/$1');

// ============================================================================
// ADMISSION
// ============================================================================
$routes->get( 'admission',                    'AdmissionController::index');
$routes->get( 'admission/report',             'AdmissionController::report');
$routes->get( 'admission/my',                  'AdmissionController::my');
$routes->post('admission/my/listing',          'AdmissionController::getMyAdmissionListing');
$routes->get( 'admission/my/detail/(:num)',    'AdmissionController::myDetail/$1');
$routes->get( 'admission/child/my',            'AdmissionController::childMy');
$routes->post('admission/child/my/listing',    'AdmissionController::getChildAdmissionListing');
$routes->get( 'admission/detail/(:num)',      'AdmissionController::detail/$1');
$routes->get( 'admission/add',                'AdmissionController::add');
$routes->post('admission/store',              'AdmissionController::store');
$routes->get( 'admission/edit/(:num)',        'AdmissionController::edit/$1');
$routes->post('admission/update/(:num)',      'AdmissionController::update/$1');
$routes->post('admission/delete/(:num)',      'AdmissionController::delete/$1');
$routes->get( 'admission/add/(:num)',         'AdmissionController::add/$1');
$routes->get( 'admission/school-subjects/(:num)',       'AdmissionController::getSchoolSubjects/$1');
$routes->get( 'admission/school-departments/(:num)',    'AdmissionController::getSchoolDepartments/$1');
$routes->post('admission/save-teaching-subjects/(:num)',   'AdmissionController::saveTeachingSubjects/$1');
$routes->post('admission/delete-teaching-subject/(:num)', 'AdmissionController::deleteTeachingSubject/$1');
$routes->post('admission/save-hod/(:num)',              'AdmissionController::saveHod/$1');
$routes->post('admission/save-student-role/(:num)',     'AdmissionController::saveStudentRole/$1');

$routes->get( 'enrolment',                  'EnrolmentController::index');
$routes->get( 'enrolment/report',           'EnrolmentController::report');
$routes->get( 'enrolment/my',                'EnrolmentController::my');
$routes->post('enrolment/my/listing',        'EnrolmentController::getMyEnrolmentListing');
$routes->get( 'enrolment/my/detail/(:num)',  'EnrolmentController::myDetail/$1');
$routes->get( 'enrolment/child/my',          'EnrolmentController::childMy');
$routes->post('enrolment/child/my/listing',  'EnrolmentController::getChildEnrolmentListing');
$routes->get( 'enrolment/detail/(:num)',    'EnrolmentController::detail/$1');
$routes->get( 'enrolment/add',              'EnrolmentController::add');
$routes->post('enrolment/store',            'EnrolmentController::store');
$routes->get( 'enrolment/edit/(:num)',      'EnrolmentController::edit/$1');
$routes->post('enrolment/update/(:num)',    'EnrolmentController::update/$1');
$routes->post('enrolment/delete/(:num)',    'EnrolmentController::delete/$1');
$routes->get( 'enrolment/streams/(:num)',   'EnrolmentController::streamsForSchool/$1');
$routes->get( 'enrolment/subjects/(:num)',          'EnrolmentController::subjectsForStream/$1');
$routes->get( 'enrolment/available-subjects/(:num)', 'EnrolmentController::availableSubjects/$1');
$routes->post('enrolment/subject/add',               'EnrolmentController::addStudentSubject');
$routes->post('enrolment/subject/add-batch',         'EnrolmentController::addStudentSubjectBatch');
$routes->post('enrolment/subject/remove/(:num)',      'EnrolmentController::removeStudentSubject/$1');

$routes->get( 'classroom',                 'ClassroomController::index');
$routes->get( 'classroom/detail/(:num)',   'ClassroomController::detail/$1');
$routes->get( 'classroom/add',             'ClassroomController::add');
$routes->post('classroom/store',           'ClassroomController::store');
$routes->get( 'classroom/edit/(:num)',     'ClassroomController::edit/$1');
$routes->post('classroom/update/(:num)',   'ClassroomController::update/$1');
$routes->post('classroom/delete/(:num)',          'ClassroomController::delete/$1');
$routes->get( 'classroom/streams/(:num)',          'ClassroomController::getStreams/$1');
$routes->post('classroom/staff/assign/(:num)',    'ClassroomController::assignStaff/$1');
$routes->post('classroom/staff/status/(:num)',    'ClassroomController::updateStaffStatus/$1');
$routes->post('classroom/assign-subject',         'ClassroomController::assignSubjectTeacher');
$routes->get( 'classroom/my',                           'ClassroomController::myClassroom');
$routes->get( 'classroom/my/year/(:num)',               'ClassroomController::classroomsByYear/$1');
$routes->get( 'classroom/my/(:num)',                    'ClassroomController::mySubject/$1');
$routes->get( 'classroom/child/my',                     'ClassroomController::parentChildClassroom');
$routes->get( 'classroom/child/my/year/(:num)',         'ClassroomController::parentChildClassroomByYear/$1');
$routes->get( 'classroom/child/view/(:num)',            'ClassroomController::parentViewChildClassroom/$1');
$routes->get( 'classroom/students/eligible/(:num)',       'ClassroomController::eligibleStudents/$1');
$routes->post('classroom/students/admit/(:num)',          'ClassroomController::admitStudents/$1');
$routes->post('classroom/students/remove/(:num)',         'ClassroomController::removeStudent/$1');
$routes->get( 'classroom/subjects/available/(:num)',      'ClassroomController::availableSubjectsForClassroom/$1');
$routes->post('classroom/subjects/add/(:num)',            'ClassroomController::addClassroomSubjects/$1');
$routes->post('classroom/subjects/assign-teacher',        'ClassroomController::assignClassroomSubjectTeacher');
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)',  'ClassroomController::teacherLessonDetail/$1/$2');
$routes->post('classroom/teacher/(:num)/lesson/store',  'ClassroomController::storeLesson/$1');
$routes->post('classroom/lesson/(:num)/update',              'ClassroomController::updateLesson/$1');
$routes->post('classroom/lesson/(:num)/step/store',          'ClassroomController::storeLessonStep/$1');
$routes->post('classroom/lesson/(:num)/step/delete/(:num)',  'ClassroomController::deleteLessonStep/$1/$2');
$routes->post('classroom/lesson/(:num)/file/upload',         'ClassroomController::uploadLessonFile/$1');
$routes->post('classroom/lesson/(:num)/file/delete/(:num)',  'ClassroomController::deleteLessonFile/$1/$2');
$routes->post('classroom/lesson/(:num)/video/add',           'ClassroomController::addLessonVideo/$1');
$routes->post('classroom/lesson/(:num)/video/delete/(:num)', 'ClassroomController::deleteLessonVideo/$1/$2');
$routes->post('classroom/lesson/(:num)/link/add',                       'ClassroomController::addLessonLink/$1');
$routes->post('classroom/lesson/(:num)/link/delete/(:num)',              'ClassroomController::deleteLessonLink/$1/$2');
$routes->post('classroom/lesson/(:num)/discussion/post',                 'ClassroomController::postDiscussion/$1');
$routes->post('classroom/lesson/(:num)/discussion/(:num)/like',          'ClassroomController::likeDiscussion/$1/$2');
$routes->post('classroom/lesson/(:num)/discussion/(:num)/comment',       'ClassroomController::postDiscussionComment/$1/$2');
$routes->post('classroom/lesson/(:num)/discussion/(:num)/delete',        'ClassroomController::deleteDiscussion/$1/$2');
$routes->post('classroom/lesson/(:num)/discussion/(:num)/comment/(:num)/like',      'ClassroomController::likeDiscussionComment/$1/$2/$3');
$routes->get( 'classroom/lesson/(:num)/discussion/(:num)/reactions',                'ClassroomController::getDiscussionReactions/$1/$2');
$routes->get( 'classroom/lesson/(:num)/discussion/(:num)/comment/(:num)/reactions', 'ClassroomController::getCommentReactions/$1/$2/$3');
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/quiz/(:num)',           'ClassroomController::teacherQuizDetail/$1/$2/$3');
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/quiz/(:num)/analysis',  'ClassroomController::teacherQuizAnalysis/$1/$2/$3');
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/quiz/(:num)/attempts',  'ClassroomController::teacherQuizAttempts/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/quiz/store',                                  'ClassroomController::storeQuiz/$1');
$routes->post('classroom/lesson/(:num)/quiz/(:num)/update',                          'ClassroomController::updateQuiz/$1/$2');
$routes->post('classroom/lesson/(:num)/quiz/(:num)/delete',                          'ClassroomController::deleteQuiz/$1/$2');
$routes->post('classroom/lesson/(:num)/quiz/(:num)/question/store',                  'ClassroomController::storeQuizQuestion/$1/$2');
$routes->post('classroom/lesson/(:num)/quiz/(:num)/question/(:num)/delete',          'ClassroomController::deleteQuizQuestion/$1/$2/$3');
// Labelling assessment builder (teacher)
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/label/(:num)',                       'ClassroomController::teacherLabelDetail/$1/$2/$3');
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/label/(:num)/analysis',             'ClassroomController::teacherLabelAnalysis/$1/$2/$3');
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/label/(:num)/attempts',             'ClassroomController::teacherLabelAttempts/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/label/(:num)/question/store',                       'ClassroomController::storeLabelQuestion/$1/$2');
$routes->post('classroom/lesson/(:num)/label/(:num)/question/(:num)/update',               'ClassroomController::updateLabelQuestion/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/label/(:num)/question/(:num)/delete',               'ClassroomController::deleteLabelQuestion/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/label/(:num)/question/(:num)/marker/store',         'ClassroomController::storeLabelMarker/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/label/(:num)/question/(:num)/marker/(:num)/update', 'ClassroomController::updateLabelMarker/$1/$2/$3/$4');
$routes->post('classroom/lesson/(:num)/label/(:num)/question/(:num)/marker/(:num)/delete', 'ClassroomController::deleteLabelMarker/$1/$2/$3/$4');
// Labelling assessment (student)
$routes->get( 'classroom/student/(:num)/lesson/(:num)/label/(:num)/take',                  'ClassroomController::takeLabel/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/label/(:num)/attempt/(:num)/submit',                'ClassroomController::submitLabelAttempt/$1/$2/$3');
$routes->get( 'classroom/student/(:num)/lesson/(:num)/label/(:num)/score',                 'ClassroomController::studentLabelScore/$1/$2/$3');
$routes->get( 'classroom/student/(:num)/lesson/(:num)/label/(:num)/transcript',            'ClassroomController::downloadLabelTranscript/$1/$2/$3');
// Drag & Drop assessment builder
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/dragdrop/(:num)',              'ClassroomController::teacherDragDropDetail/$1/$2/$3');
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/dragdrop/(:num)/analysis',    'ClassroomController::teacherDragDropAnalysis/$1/$2/$3');
$routes->get( 'classroom/teacher/(:num)/lesson/(:num)/dragdrop/(:num)/attempts',    'ClassroomController::teacherDragDropAttempts/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/dragdrop/(:num)/item/store',                  'ClassroomController::storeDragDropItem/$1/$2');
$routes->post('classroom/lesson/(:num)/dragdrop/(:num)/item/(:num)/update',          'ClassroomController::updateDragDropItem/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/dragdrop/(:num)/item/(:num)/delete',          'ClassroomController::deleteDragDropItem/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/dragdrop/(:num)/zone/store',                  'ClassroomController::storeDragDropZone/$1/$2');
$routes->post('classroom/lesson/(:num)/dragdrop/(:num)/zone/(:num)/update',          'ClassroomController::updateDragDropZone/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/dragdrop/(:num)/zone/(:num)/delete',          'ClassroomController::deleteDragDropZone/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/dragdrop/(:num)/answers/save',                'ClassroomController::saveDragDropAnswers/$1/$2');
// Term Exam
$routes->post('classroom/teacher/(:num)/exam/mark/save',            'ClassroomController::saveExamMark/$1');
$routes->post('classroom/term-exam/create',                         'ClassroomController::createTermExam');
$routes->post('classroom/term-exam/(:num)/rename',                  'ClassroomController::renameTermExam/$1');
$routes->post('classroom/term-exam/(:num)/delete',                  'ClassroomController::deleteTermExam/$1');
$routes->get( 'classroom/class-exam/(:num)/term/(:num)',            'ClassroomController::classTeacherExamReview/$1/$2');
$routes->get( 'classroom/class-exam/(:num)',                        'ClassroomController::classTeacherExamReview/$1');
$routes->post('classroom/class-exam/(:num)/comment',                'ClassroomController::saveCtComment/$1');
$routes->post('classroom/class-exam/(:num)/save-mark',              'ClassroomController::saveMarkAsClassTeacher/$1');
$routes->post('classroom/class-exam/(:num)/term/(:num)/submit',     'ClassroomController::classTeacherSubmit/$1/$2');
$routes->get( 'classroom/principal-exam/(:num)/term/(:num)',        'ClassroomController::principalExamReview/$1/$2');
$routes->get( 'classroom/principal-exam/(:num)',                    'ClassroomController::principalExamReview/$1');
$routes->post('classroom/principal-exam/(:num)/comment',            'ClassroomController::savePrincipalComment/$1');
$routes->post('classroom/principal-exam/(:num)/term/(:num)/publish','ClassroomController::publishReport/$1/$2');
$routes->get( 'classroom/report/(:num)/student/(:num)/term/(:num)', 'ClassroomController::reportCard/$1/$2/$3');
$routes->get( 'classroom/report/(:num)/student/(:num)/term/(:num)/pdf', 'ClassroomController::reportCardPdf/$1/$2/$3');
// Class Discussion
$routes->post('classroom/(:num)/discussion/post',              'ClassroomController::classDiscussionPost/$1');
$routes->post('classroom/discussion/(:num)/like',              'ClassroomController::classDiscussionLike/$1');
$routes->post('classroom/discussion/(:num)/delete',            'ClassroomController::classDiscussionDelete/$1');
$routes->post('classroom/discussion/(:num)/comment',           'ClassroomController::classDiscussionComment/$1');
$routes->post('classroom/discussion/comment/(:num)/like',      'ClassroomController::classDiscussionCommentLike/$1');
$routes->post('classroom/discussion/comment/(:num)/delete',    'ClassroomController::classDiscussionCommentDelete/$1');
$routes->post('classroom/discussion/comment/(:num)/reply',     'ClassroomController::classDiscussionCommentReply/$1');
$routes->post('classroom/discussion/reply/(:num)/like',        'ClassroomController::classDiscussionCommentReplyLike/$1');
$routes->post('classroom/discussion/reply/(:num)/delete',      'ClassroomController::classDiscussionCommentReplyDelete/$1');
$routes->get( 'classroom/discussion/(:num)/reactions',         'ClassroomController::classDiscussionReactions/$1');
$routes->get( 'classroom/discussion/comment/(:num)/reactions', 'ClassroomController::classDiscussionCommentReactions/$1');
$routes->get( 'classroom/discussion/reply/(:num)/reactions',   'ClassroomController::classDiscussionReplyReactions/$1');
$routes->post('classroom/teacher/(:num)/assignment/store',           'ClassroomController::teacherAssignmentStore/$1');
$routes->post('classroom/teacher/(:num)/assignment/(:num)/update',   'ClassroomController::teacherAssignmentUpdate/$1/$2');
$routes->post('classroom/teacher/(:num)/assignment/(:num)/delete',   'ClassroomController::teacherAssignmentDelete/$1/$2');
$routes->post('classroom/teacher/(:num)/assignment/(:num)/file/(:num)/delete', 'ClassroomController::teacherAssignmentFileDelete/$1/$2/$3');
$routes->get( 'classroom/teacher/(:num)',               'ClassroomController::teacherClassroom/$1');
$routes->get( 'classroom/teacher/(:num)/(:segment)',    'ClassroomController::teacherClassroom/$1/$2');
$routes->get( 'classroom/student/(:num)',                                                    'ClassroomController::studentClassroom/$1');
$routes->get( 'classroom/student/(:num)/lesson/(:num)/quiz/(:num)/take',                    'ClassroomController::takeQuiz/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/quiz/(:num)/attempt/(:num)/submit',                  'ClassroomController::submitQuizAttempt/$1/$2/$3');
$routes->post('classroom/quiz/attempt/(:num)/tick',                                         'ClassroomController::saveQuizTick/$1');
$routes->post('classroom/quiz/attempt/(:num)/save-answer',                                  'ClassroomController::saveQuizAnswer/$1');
$routes->get( 'classroom/student/(:num)/lesson/(:num)/quiz/(:num)/score',                   'ClassroomController::studentQuizScore/$1/$2/$3');
$routes->get( 'classroom/student/(:num)/lesson/(:num)/quiz/(:num)/transcript',              'ClassroomController::downloadQuizTranscript/$1/$2/$3');
// Drag & Drop student routes
$routes->get( 'classroom/student/(:num)/lesson/(:num)/dragdrop/(:num)/take',                'ClassroomController::takeDragDrop/$1/$2/$3');
$routes->post('classroom/lesson/(:num)/dragdrop/(:num)/attempt/(:num)/submit',              'ClassroomController::submitDragDropAttempt/$1/$2/$3');
$routes->post('classroom/dragdrop/attempt/(:num)/tick',                                     'ClassroomController::saveDragDropTick/$1');
$routes->post('classroom/dragdrop/attempt/(:num)/save-answer',                              'ClassroomController::saveDragDropAnswer/$1');
$routes->get( 'classroom/student/(:num)/lesson/(:num)/dragdrop/(:num)/score',               'ClassroomController::studentDragDropScore/$1/$2/$3');
$routes->get( 'classroom/student/(:num)/lesson/(:num)/dragdrop/(:num)/transcript',          'ClassroomController::downloadDragDropTranscript/$1/$2/$3');
// Label student routes
$routes->post('classroom/label/attempt/(:num)/tick',                                        'ClassroomController::saveLabelTick/$1');
$routes->post('classroom/label/attempt/(:num)/save-answer',                                 'ClassroomController::saveLabelAnswer/$1');
$routes->get( 'classroom/past-classrooms',                                                  'ClassroomController::getPastClassrooms');
$routes->post('classroom/student/(:num)/feedback/store',               'ClassroomController::studentFeedbackStore/$1');
$routes->get( 'classroom/student/(:num)/assignment/(:num)/submit',     'ClassroomController::studentAssignmentSubmit/$1/$2');
$routes->post('classroom/student/(:num)/assignment/(:num)/submit',     'ClassroomController::studentAssignmentSubmitStore/$1/$2');
$routes->post('copyleaks/webhook/(:segment)',                          'CopyleaksController::handle/$1');
$routes->get( 'classroom/student/(:num)/assignment/(:num)/assessment', 'ClassroomController::studentAssignmentAssessment/$1/$2');
$routes->get( 'classroom/teacher/(:num)/assignment/(:num)/mark',       'ClassroomController::teacherAssignmentMark/$1/$2');
$routes->post('classroom/teacher/(:num)/assignment/(:num)/mark/save',  'ClassroomController::teacherAssignmentMarkSave/$1/$2');
$routes->get( 'classroom/teacher/(:num)/assignment/(:num)/analysis',   'ClassroomController::teacherAssignmentAnalysis/$1/$2');
$routes->get( 'classroom/student/(:num)/lesson/(:num)',                                     'ClassroomController::studentLessonDetail/$1/$2');
$routes->get( 'classroom/student/(:num)/(:segment)',                                        'ClassroomController::studentClassroom/$1/$2');

// ============================================================================
// ATTENDANCE Routes
// ============================================================================
// Pages
$routes->get( 'attendance',             'AttendanceController::index');
$routes->get( 'attendance/add',         'AttendanceController::add');
$routes->get( 'attendance/my/daily',        'AttendanceController::myDailyAttendance');
$routes->get( 'attendance/my/daily/pdf',    'AttendanceController::myDailyPdf');
$routes->post('attendance/holiday/add',     'AttendanceController::addHoliday');
$routes->post('attendance/holiday/remove/(:num)', 'AttendanceController::removeHoliday/$1');

// AJAX helpers
$routes->get( 'attendance/check',    'AttendanceController::checkExists');
$routes->get( 'attendance/students', 'AttendanceController::getStudentsByStream');
$routes->get( 'attendance/events',   'AttendanceController::getCalendarEvents');
$routes->get( 'attendance/detail',   'AttendanceController::getDateDetail');

// Write actions
$routes->get( 'attendance/grid/pdf',          'AttendanceController::gridPdf');
$routes->post('attendance/grid/save',         'AttendanceController::saveGrid');
$routes->post('attendance/save',              'AttendanceController::save');
$routes->post('attendance/save-ajax',         'AttendanceController::saveAjax');
$routes->post('attendance/update/(:num)',      'AttendanceController::updateRecord/$1');
$routes->post('attendance/delete/(:num)',      'AttendanceController::deleteRecord/$1');
$routes->post('attendance/delete-all-day',    'AttendanceController::deleteAllForDate');
$routes->post('attendance/upload-files/(:num)','AttendanceController::uploadFiles/$1');
$routes->post('attendance/delete-file/(:num)', 'AttendanceController::deleteFile/$1');

// ── Subject Attendance ───────────────────────────────────────────────────────
$routes->get( 'attendance/subject',                       'SubjectAttendanceController::index');
$routes->get( 'attendance/subject/add',                   'SubjectAttendanceController::add');
$routes->get( 'attendance/my/subject',                    'SubjectAttendanceController::mySubjectAttendance');

// AJAX helpers
$routes->get( 'attendance/subject/check',                 'SubjectAttendanceController::checkExists');
$routes->get( 'attendance/subject/subjects',              'SubjectAttendanceController::getSubjectsByStream');
$routes->get( 'attendance/subject/students',              'SubjectAttendanceController::getStudentsByStream');
$routes->get( 'attendance/subject/events',                'SubjectAttendanceController::getCalendarEvents');
$routes->get( 'attendance/subject/detail',                'SubjectAttendanceController::getDateDetail');

// Write actions
$routes->post('attendance/subject/save',                  'SubjectAttendanceController::save');
$routes->post('attendance/subject/save-ajax',             'SubjectAttendanceController::saveAjax');
$routes->post('attendance/subject/update/(:num)',          'SubjectAttendanceController::updateRecord/$1');
$routes->post('attendance/subject/delete/(:num)',          'SubjectAttendanceController::deleteRecord/$1');
$routes->post('attendance/subject/delete-all-day',        'SubjectAttendanceController::deleteAllForDate');
$routes->post('attendance/subject/upload-files/(:num)',   'SubjectAttendanceController::uploadFiles/$1');
$routes->post('attendance/subject/delete-file/(:num)',    'SubjectAttendanceController::deleteFile/$1');

// Parent–student linking
$routes->post('user/link-child',                  'UserController::linkChild');
$routes->post('user/unlink-child/(:num)',          'UserController::unlinkChild/$1');
$routes->get( 'user/searchStudents',              'UserController::searchStudents');
$routes->get( 'user/searchNonStudents',           'UserController::searchNonStudents');

// My Child (parent's own children listing)
$routes->get( 'user/child/my',            'UserController::myChild');
$routes->post('user/child/my/listing',    'UserController::getMyChildListing');

// ============================================================================
// WALL Routes
// ============================================================================
$routes->get( 'wall',                              'WallController::index');
$routes->get( 'wall/feed',                         'WallController::feed');
$routes->post('wall/post',                         'WallController::createPost');
$routes->post('wall/post/(:num)/delete',           'WallController::deletePost/$1');
$routes->get( 'wall/post/(:num)/data',            'WallController::getPostData/$1');
$routes->post('wall/post/(:num)/update',          'WallController::updatePost/$1');
$routes->get( 'wall/post/(:num)/comments',         'WallController::getComments/$1');
$routes->post('wall/post/(:num)/comment',          'WallController::addComment/$1');
$routes->post('wall/comment/(:num)/delete',        'WallController::deleteComment/$1');
$routes->post('wall/react',                        'WallController::react');
$routes->get( 'wall/reactions',                    'WallController::reactionDetail');
$routes->get( 'wall/media/(:num)',                 'WallController::viewMedia/$1');

// ============================================================================
// SUBJECT Routes
// ============================================================================
$routes->get( 'subject',                           'SubjectController::index');
$routes->post('subject/listing',                  'SubjectController::getSubjectListing');
$routes->get( 'subject/export',                   'SubjectController::export');
$routes->get( 'subject/category',                 'SubjectCategoryController::index');
$routes->post('subject/category/listing',         'SubjectCategoryController::getCategoryListing');
$routes->get( 'subject/category/add',             'SubjectCategoryController::add');
$routes->post('subject/category/store',           'SubjectCategoryController::store');
$routes->get( 'subject/category/edit/(:num)',     'SubjectCategoryController::edit/$1');
$routes->post('subject/category/update/(:num)',   'SubjectCategoryController::update/$1');
$routes->post('subject/category/remove/(:num)',   'SubjectCategoryController::delete/$1');
$routes->get( 'subject/add',             'SubjectController::add');
$routes->post('subject/store',           'SubjectController::store');
$routes->get( 'subject/edit/(:num)',     'SubjectController::edit/$1');
$routes->post('subject/update/(:num)',   'SubjectController::update/$1');
$routes->post('subject/remove/(:num)',   'SubjectController::delete/$1');

// ============================================================================
// TIMETABLE Routes
// ============================================================================
$routes->get( 'timetable',                          'TimetableController::index');
$routes->get( 'timetable/my',                       'TimetableController::my');
$routes->get( 'timetable/setup',                    'TimetableController::setup');
$routes->post('timetable/setup/save',               'TimetableController::saveSetup');
$routes->get( 'timetable/add',                      'TimetableController::add');
$routes->post('timetable/store',                    'TimetableController::store');
$routes->get( 'timetable/edit/(:num)',              'TimetableController::edit/$1');
$routes->post('timetable/update/(:num)',            'TimetableController::update/$1');
$routes->get( 'timetable/detail/(:num)',            'TimetableController::detail/$1');
$routes->post('timetable/remove/(:num)',            'TimetableController::delete/$1');
$routes->get( 'timetable/report/(:num)',            'TimetableController::report/$1');
$routes->get( 'timetable/report/(:num)/pdf',        'TimetableController::reportPdf/$1');
$routes->get( 'timetable/stream-info/(:num)',       'TimetableController::streamInfo/$1');
$routes->get( 'timetable/subject-teachers/(:num)', 'TimetableController::subjectTeachers/$1');

// ============================================================================
// EVENT Routes
// ============================================================================
$routes->get( 'event',                             'EventController::index');
$routes->get( 'event/add',                         'EventController::add');
$routes->post('event/store',                       'EventController::store');
$routes->get( 'event/edit/(:num)',                 'EventController::edit/$1');
$routes->post('event/update/(:num)',               'EventController::update/$1');
$routes->post('event/remove/(:num)',               'EventController::delete/$1');
$routes->get( 'event/detail/(:num)',               'EventController::detail/$1');
$routes->get( 'event/calendar',                    'EventController::calendar');
$routes->get( 'event/calendar/feed',               'EventController::calendarFeed');
$routes->get( 'event/report',                      'EventController::report');

// ============================================================================
// MOBILE API Routes (stateless JWT — Navuli mobile app)
// ============================================================================
$routes->post('api/auth/login', 'Api\AuthController::login', ['filter' => 'cors']);

$routes->group('api', ['filter' => 'cors'], static function ($routes) {
    $routes->group('', ['filter' => 'apijwt'], static function ($routes) {
        $routes->get('auth/me',       'Api\AuthController::me');
        $routes->get('notices',       'Api\NoticesController::index');
        $routes->get('announcements', 'Api\AnnouncementsController::index');

        $routes->get('dashboard', 'Api\DashboardController::index');

        $routes->get('wall/feed',                    'Api\WallController::feed');
        $routes->post('wall/post',                    'Api\WallController::createPost');
        $routes->get('wall/post/(:num)/comments',     'Api\WallController::comments/$1');
        $routes->post('wall/post/(:num)/comment',     'Api\WallController::addComment/$1');
        $routes->post('wall/react',                   'Api\WallController::react');

        $routes->get('notifications',            'Api\NotificationController::index');
        $routes->post('notifications/mark-read', 'Api\NotificationController::markRead');
    });
});


