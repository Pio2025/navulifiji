<?php

namespace App\Controllers;

use App\Models\ContactMessageModel;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            '_view'        => 'web/site/home',
            'active_page'  => 'home',
            'page_title'   => 'School Management System for Fiji',
            'plans'        => $this->planModel->getSelectablePlans(),
        ];

        $this->session->set('active_page', 'home');

        return view('web/layouts/site', $data);
    }

    public function feature(): string
    {
        $tiers = [];
        foreach ($this->moduleTours() as $slug => $tour) {
            $tiers[$tour['tier']][$slug] = $tour;
        }

        $data = [
            '_view'       => 'web/site/feature',
            'active_page' => 'feature',
            'page_title'  => 'Features',
            'tiers'       => $tiers,
        ];
        $this->session->set('active_page', 'feature');

        return view('web/layouts/site', $data);
    }

    public function pricing(): string
    {
        $data = [
            '_view'       => 'web/site/pricing',
            'active_page' => 'pricing',
            'page_title'  => 'Pricing',
            'plans'       => $this->planModel->getSelectablePlans(),
        ];
        $this->session->set('active_page', 'pricing');

        return view('web/layouts/site', $data);
    }

    public function featureTour(string $slug): string
    {
        $tours = $this->moduleTours();

        if (!isset($tours[$slug])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Feature tour '{$slug}' not found");
        }

        $tour = $tours[$slug];

        $related = [];
        foreach ($tours as $otherSlug => $otherTour) {
            if ($otherSlug !== $slug && $otherTour['tier'] === $tour['tier']) {
                $related[$otherSlug] = $otherTour;
            }
        }

        $data = [
            '_view'       => 'web/site/feature_tour',
            'active_page' => 'feature',
            'page_title'  => $tour['label'],
            'tour'        => $tour,
            'related'     => array_slice($related, 0, 6, true),
        ];
        $this->session->set('active_page', 'feature');

        return view('web/layouts/site', $data);
    }

    /**
     * Central content map for pricing-page module "feature tour" pages,
     * keyed by url_title() slug of the module label.
     */
    private function moduleTours(): array
    {
        return [
            // Standard Modules
            'multiple-dashboards' => ['tier' => 'Standard Modules', 'icon' => 'bi-speedometer2', 'label' => 'Multiple Dashboards', 'description' => 'Give every role — admin, teacher, student and parent — a dashboard tailored to what they actually need to see.', 'highlights' => ['Role-specific widgets and shortcuts', 'At-a-glance stats for attendance, notices and events', 'No clutter — each login sees only what\'s relevant']],
            'notice-board' => ['tier' => 'Standard Modules', 'icon' => 'bi-clipboard', 'label' => 'Notice Board', 'description' => 'Post school-wide notices that reach the right audience instantly, with read receipts so nothing gets missed.', 'highlights' => ['Target notices by role, class or the whole school', 'Read/unread tracking per recipient', 'Pin important notices to the top']],
            'announcement-management' => ['tier' => 'Standard Modules', 'icon' => 'bi-megaphone', 'label' => 'Announcement Management', 'description' => 'Broadcast time-sensitive announcements — closures, events, reminders — across the whole school in seconds.', 'highlights' => ['Schedule announcements for future dates', 'Attach files and links', 'Delivered instantly to web and mobile']],
            'timetable' => ['tier' => 'Standard Modules', 'icon' => 'bi-calendar-week', 'label' => 'Timetable', 'description' => 'Build and publish class timetables that students, teachers and parents can check from any device.', 'highlights' => ['Drag-and-drop weekly grid builder', 'Conflict detection for teachers and rooms', 'Auto-updates when subjects or streams change']],
            'student-admission' => ['tier' => 'Standard Modules', 'icon' => 'bi-person-plus', 'label' => 'Student Admission', 'description' => 'Take new students from enquiry to enrolled record with a guided, paperless admission workflow.', 'highlights' => ['Online admission forms with document upload', 'Approval workflow with status tracking', 'Converts directly into a student record']],
            'student-enrolment' => ['tier' => 'Standard Modules', 'icon' => 'bi-diagram-3', 'label' => 'Student Enrolment', 'description' => 'Place admitted students into the right class, stream and subjects for the academic year in a few clicks.', 'highlights' => ['Bulk enrolment by class or stream', 'Subject and elective assignment', 'Year-on-year rollover support']],
            'messaging-system' => ['tier' => 'Standard Modules', 'icon' => 'bi-chat-dots', 'label' => 'Messaging System', 'description' => 'Secure, direct messaging between staff, students and parents — no personal numbers required.', 'highlights' => ['One-to-one and group conversations', 'File and image sharing', 'Delivery and read status']],
            'student-attendance' => ['tier' => 'Standard Modules', 'icon' => 'bi-calendar2-check', 'label' => 'Student Attendance', 'description' => 'Mark daily or subject-level attendance in seconds and give parents live visibility into it.', 'highlights' => ['Class and subject-level registers', 'Instant absence view for parents', 'Attendance history and exports']],
            'curriculum-management' => ['tier' => 'Standard Modules', 'icon' => 'bi-journal-bookmark', 'label' => 'Curriculum Management', 'description' => 'Organise subjects, streams and departments so every class is teaching the right curriculum.', 'highlights' => ['Departments, streams and subject mapping', 'Core and optional subject handling', 'Consistent structure across school levels']],
            'examination' => ['tier' => 'Standard Modules', 'icon' => 'bi-pencil-square', 'label' => 'Examination', 'description' => 'Plan exams, assign students and manage the whole exam cycle from one place.', 'highlights' => ['Exam scheduling by class and subject', 'Student assignment and roll numbers', 'Results feed straight into report cards']],
            'user-management' => ['tier' => 'Standard Modules', 'icon' => 'bi-people', 'label' => 'User Management', 'description' => 'Manage every account in the school — staff, students and parents — from one control point.', 'highlights' => ['Role-based accounts and permissions', 'Bulk account creation and activation', 'Session and login history per user']],
            'report-center' => ['tier' => 'Standard Modules', 'icon' => 'bi-bar-chart', 'label' => 'Report Center', 'description' => 'Generate the reports admins actually need — attendance, admissions, exams and more — on demand.', 'highlights' => ['Ready-made reports across core modules', 'Export to PDF and spreadsheet', 'Filter by class, term or date range']],
            'admin-teacher-login' => ['tier' => 'Standard Modules', 'icon' => 'bi-person-badge', 'label' => 'Admin / Teacher Login', 'description' => 'A dedicated, secure login experience for administrators and teaching staff.', 'highlights' => ['Role-aware landing dashboard', 'Granular permission control', 'Session management and audit trail']],
            'students-parents-login' => ['tier' => 'Standard Modules', 'icon' => 'bi-person-check', 'label' => 'Students / Parents Login', 'description' => 'Give students and parents their own secure portal to track progress and stay informed.', 'highlights' => ['Separate student and parent views', 'Access to attendance, notices and results', 'Works on web and mobile']],
            'student-information' => ['tier' => 'Standard Modules', 'icon' => 'bi-person-lines-fill', 'label' => 'Student Information', 'description' => 'Keep a complete, up-to-date record for every student in one searchable profile.', 'highlights' => ['Personal, academic and family details', 'Document and photo storage', 'Quick search across the whole school']],
            'certificates-reference-documents' => ['tier' => 'Standard Modules', 'icon' => 'bi-file-earmark-text', 'label' => 'Certificates & Reference Documents', 'description' => 'Generate the official documents schools are asked for most — references, certificates and letters.', 'highlights' => ['Ready templates for common reference letters', 'Request and approval workflow', 'Downloadable, signed PDF output']],
            'sessions-logs' => ['tier' => 'Standard Modules', 'icon' => 'bi-clock-history', 'label' => 'Sessions & Logs', 'description' => 'See who\'s logged in, from where, and review activity history for accountability.', 'highlights' => ['Active session monitoring per user', 'Force sign-out on any device', 'Searchable activity log']],
            'certificate-generator' => ['tier' => 'Standard Modules', 'icon' => 'bi-award', 'label' => 'Certificate Generator', 'description' => 'Produce professional certificates for achievement, participation and completion in bulk.', 'highlights' => ['Customisable certificate templates', 'Bulk generation for a class or event', 'Instant PDF download']],
            'id-card-generator' => ['tier' => 'Standard Modules', 'icon' => 'bi-person-vcard', 'label' => 'ID Card Generator', 'description' => 'Design and print student and staff ID cards straight from their existing profile data.', 'highlights' => ['Template designer with school branding', 'Bulk print runs by class or department', 'Photo and barcode support']],
            'school-events-calendar' => ['tier' => 'Standard Modules', 'icon' => 'bi-calendar-event', 'label' => 'School / Events Calendar', 'description' => 'Keep the whole school aligned on events, holidays and important dates in one shared calendar.', 'highlights' => ['School-wide and class-level events', 'Holiday and term-date management', 'Synced view for staff, students and parents']],
            'gradebook' => ['tier' => 'Standard Modules', 'icon' => 'bi-journal-text', 'label' => 'Gradebook', 'description' => 'Record and track marks across subjects and terms in a single, always-current gradebook.', 'highlights' => ['Per-subject, per-term mark entry', 'Automatic grade calculation', 'Feeds directly into report cards']],

            // Premium Modules
            'library' => ['tier' => 'Premium Modules', 'icon' => 'bi-bookshelf', 'label' => 'Library', 'description' => 'Manage your school library\'s catalogue, loans and returns without a spreadsheet in sight.', 'highlights' => ['Searchable book catalogue', 'Issue, return and renewal tracking', 'Overdue reminders to students']],
            'data-management' => ['tier' => 'Premium Modules', 'icon' => 'bi-database', 'label' => 'Data Management', 'description' => 'Keep school-wide data clean, organised and easy to maintain as your student body grows.', 'highlights' => ['Centralised record housekeeping', 'Bulk edit and cleanup tools', 'Consistent data across all modules']],
            'email-integration' => ['tier' => 'Premium Modules', 'icon' => 'bi-envelope-at', 'label' => 'Email Integration', 'description' => 'Connect Navuli to your school\'s email so notices, reports and alerts land in real inboxes.', 'highlights' => ['Automatic email delivery for key events', 'Works with your existing school domain', 'Reduces missed communication']],
            'transportation' => ['tier' => 'Premium Modules', 'icon' => 'bi-bus-front', 'label' => 'Transportation', 'description' => 'Track school transport routes, vehicles and student assignments in one view.', 'highlights' => ['Route and stop management', 'Student-to-vehicle assignment', 'Driver and vehicle records']],
            'custom-import' => ['tier' => 'Premium Modules', 'icon' => 'bi-box-arrow-in-down', 'label' => 'Custom Import', 'description' => 'Bring existing student and staff data into Navuli quickly instead of entering it by hand.', 'highlights' => ['Spreadsheet-based bulk import', 'Field mapping and validation', 'Safe preview before committing changes']],
            'school-wall' => ['tier' => 'Premium Modules', 'icon' => 'bi-grid-3x3-gap', 'label' => 'School Wall', 'description' => 'A social-style feed where the school community shares updates, photos and celebrations.', 'highlights' => ['Posts, comments and reactions', 'Role-based visibility controls', 'Media sharing for events and achievements']],
            'poll' => ['tier' => 'Premium Modules', 'icon' => 'bi-bar-chart-line', 'label' => 'Poll', 'description' => 'Run quick polls to gather opinions from staff, students or parents on the topics that matter.', 'highlights' => ['Create polls in seconds', 'Target specific groups', 'Live results as votes come in']],
            'custom-report' => ['tier' => 'Premium Modules', 'icon' => 'bi-file-earmark-bar-graph', 'label' => 'Custom Report', 'description' => 'Build the exact report your school needs when the standard ones don\'t quite fit.', 'highlights' => ['Pick fields, filters and groupings', 'Save and reuse custom report layouts', 'Export to PDF or spreadsheet']],
            'theme' => ['tier' => 'Premium Modules', 'icon' => 'bi-palette', 'label' => 'Theme', 'description' => 'Give your school\'s Navuli account its own look, with colours and branding that match your identity.', 'highlights' => ['Custom colour palette', 'Logo and branding placement', 'Consistent look across web and mobile']],
            'placement' => ['tier' => 'Premium Modules', 'icon' => 'bi-briefcase', 'label' => 'Placement', 'description' => 'Track student placements, work experience and career pathways in one dedicated module.', 'highlights' => ['Placement records per student', 'Status tracking from application to completion', 'Reporting on outcomes over time']],
            'task' => ['tier' => 'Premium Modules', 'icon' => 'bi-list-task', 'label' => 'Task', 'description' => 'Assign, track and close out tasks between staff so nothing falls through the cracks.', 'highlights' => ['Assign tasks to individuals or teams', 'Due dates and status tracking', 'Notifications on assignment and completion']],
            'discipline' => ['tier' => 'Premium Modules', 'icon' => 'bi-shield-check', 'label' => 'Discipline', 'description' => 'Record conduct incidents, follow up on actions and keep a clear disciplinary history per student.', 'highlights' => ['Incident logging with severity levels', 'Action and follow-up tracking', 'Parent notification on serious incidents']],
            'data-export' => ['tier' => 'Premium Modules', 'icon' => 'bi-box-arrow-up', 'label' => 'Data Export', 'description' => 'Take your school\'s data out of Navuli whenever you need it, in the formats you need.', 'highlights' => ['Export by module or full school', 'Spreadsheet and PDF formats', 'Scheduled or on-demand exports']],
            'reminder' => ['tier' => 'Premium Modules', 'icon' => 'bi-bell', 'label' => 'Reminder', 'description' => 'Set automatic reminders for fees, deadlines and events so nothing slips past your staff or parents.', 'highlights' => ['Rule-based reminder scheduling', 'Delivered via notification and email', 'Reduces manual follow-up work']],
            'enquiry-registration' => ['tier' => 'Premium Modules', 'icon' => 'bi-clipboard-plus', 'label' => 'Enquiry & Registration', 'description' => 'Capture prospective-family enquiries and turn them into registrations without losing a lead.', 'highlights' => ['Public enquiry capture form', 'Follow-up and status tracking', 'Direct path from enquiry to admission']],

            // Ultimate Modules
            'digital-classroom' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-easel2', 'label' => 'Digital Classroom', 'description' => 'Move lessons online with a full digital classroom for content, discussion and assessment.', 'highlights' => ['Lesson content, videos and files in one place', 'Class discussion threads', 'Works alongside in-person teaching']],
            'subject-dashboard' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-grid-1x2', 'label' => 'Subject Dashboard', 'description' => 'A focused dashboard for each subject, showing teachers exactly how their classes are progressing.', 'highlights' => ['Per-subject performance overview', 'Quick access to lessons and assessments', 'Class comparison at a glance']],
            'lesson-management' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-journal-richtext', 'label' => 'Lesson Management', 'description' => 'Plan, structure and deliver lessons step-by-step, with content teachers can reuse term after term.', 'highlights' => ['Step-by-step lesson builder', 'Attach files, videos and links', 'Reusable across classes and years']],
            'assessment-management' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-clipboard-check', 'label' => 'Assessment Management', 'description' => 'Create quizzes, drag-and-drop and labelling assessments to check understanding as you teach.', 'highlights' => ['Multiple assessment formats', 'Auto-marking where possible', 'Attempt history per student']],
            'student-assignment' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-clipboard-data', 'label' => 'Student Assignment', 'description' => 'Set assignments, collect submissions and mark them without a single paper handout.', 'highlights' => ['Online submission with file upload', 'Due dates and late tracking', 'Marking and feedback in one place']],
            'online-assessment' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-laptop', 'label' => 'Online Assessment', 'description' => 'Run timed, online assessments that students complete from any device, marked automatically where possible.', 'highlights' => ['Timed online test delivery', 'Automatic scoring for objective questions', 'Instant results and analysis']],
            'attendance-report' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-calendar-check', 'label' => 'Attendance Report', 'description' => 'Turn raw attendance records into clear reports admins and teachers can act on.', 'highlights' => ['Class and school-wide summaries', 'Trend view over terms', 'Exportable for meetings and audits']],
            'feedback' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-chat-left-text', 'label' => 'Feedback', 'description' => 'Collect structured feedback from students on lessons and teaching to close the loop quickly.', 'highlights' => ['Simple feedback forms per lesson', 'Anonymous or attributed responses', 'Aggregated view for teachers']],
            'internal-exam-management' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-pencil-square', 'label' => 'Internal Exam Management', 'description' => 'Run your school\'s own internal exams end-to-end, from scheduling to mark entry.', 'highlights' => ['Internal exam scheduling and assignment', 'Mark entry by class teacher', 'Feeds directly into report cards']],
            'external-exam-management' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-journal-check', 'label' => 'External Exam Management', 'description' => 'Track external, board-set examinations separately from internal assessment, with the same reporting.', 'highlights' => ['Separate tracking for external exam bodies', 'Student registration and results recording', 'Historical results by year']],
            'report-cards' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-graph-up', 'label' => 'Report Cards', 'description' => 'Generate polished, verifiable report cards straight from exam and attendance data.', 'highlights' => ['Auto-populated from marks and attendance', 'Principal and class-teacher comments', 'Verifiable, shareable PDF output']],
            'audio-call' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-telephone', 'label' => 'Audio Call', 'description' => 'Let staff and parents jump into a voice call directly from a conversation, no extra app needed.', 'highlights' => ['One-tap calling from chat', 'Works over web and mobile', 'Call history alongside messages']],
            'video-call' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-camera-video', 'label' => 'Video Call', 'description' => 'Hold face-to-face video meetings between staff and parents without leaving Navuli.', 'highlights' => ['One-tap video calling from chat', 'Works over web and mobile', 'Useful for remote parent-teacher meetings']],
            'medical-next-of-kin' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-heart-pulse', 'label' => 'Medical & Next of Kin', 'description' => 'Keep critical medical details and next-of-kin contacts on hand for every student, when it matters most.', 'highlights' => ['Medical conditions and allergy records', 'Next-of-kin contact details', 'Quick access for staff in an emergency']],
            'parent-child-linking' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-person-check', 'label' => 'Parent-Child Linking', 'description' => 'Link parent accounts to their children automatically so families see the right information only.', 'highlights' => ['Verified parent-to-student linking', 'Support for multiple children per parent', 'Automatic access to linked child\'s records']],
            'alumni' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-mortarboard', 'label' => 'Alumni', 'description' => 'Stay connected with former students long after they\'ve graduated.', 'highlights' => ['Alumni directory and profiles', 'Track graduation year and achievements', 'Foundation for reunions and outreach']],
            'doc-manager' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-folder2-open', 'label' => 'Doc Manager', 'description' => 'A central place to store and organise every school document, policy and file.', 'highlights' => ['Folder-based document organisation', 'Role-based access to sensitive files', 'Fast search across all stored documents']],
            'gate-management' => ['tier' => 'Ultimate Modules', 'icon' => 'bi-door-open', 'label' => 'Gate Management', 'description' => 'Track who enters and leaves the school gate, for visitors, students and staff alike.', 'highlights' => ['Visitor sign-in and sign-out log', 'Student entry/exit tracking', 'Real-time gate activity overview']],
        ];
    }

    public function resources(): string
    {
        $data = [
            '_view'       => 'web/site/resources',
            'active_page' => 'resources',
            'page_title'  => 'Resources',
        ];
        $this->session->set('active_page', 'resources');

        return view('web/layouts/site', $data);
    }

    public function about(): string
    {
        $data = [
            '_view'       => 'web/site/about',
            'active_page' => 'about',
            'page_title'  => 'About Us',
        ];
        $this->session->set('active_page', 'about');

        return view('web/layouts/site', $data);
    }

    public function forSchools(): string
    {
        $data = [
            '_view'            => 'web/site/for_schools',
            'active_page'      => 'for-schools',
            'page_title'       => 'For Schools',
            'schoolCategories' => $this->schoolCategoryModel->getAllSchoolCategory(),
        ];
        $this->session->set('active_page', 'for-schools');

        return view('web/layouts/site', $data);
    }

    public function contact(): string
    {
        $data = [
            '_view'       => 'web/site/contact',
            'active_page' => 'contact',
            'page_title'  => 'Contact Us',
        ];
        $this->session->set('active_page', 'contact');

        return view('web/layouts/site', $data);
    }

    /**
     * Handles the public contact form submission (BootstrapMade php-email-form
     * contract: response body must be the literal string "OK" on success, or a
     * plain-text error message otherwise).
     */
    public function submitContact()
    {
        $contactMessageModel = new ContactMessageModel();

        $name    = trim((string) $this->request->getPost('name'));
        $email   = trim((string) $this->request->getPost('email'));
        $phone   = trim((string) $this->request->getPost('phone'));
        $school  = trim((string) $this->request->getPost('school_name'));
        $subject = trim((string) $this->request->getPost('subject'));
        $message = trim((string) $this->request->getPost('message'));

        if (empty($name) || empty($email) || empty($message)) {
            return $this->response->setStatusCode(200)->setBody('Please fill in your name, email, and message.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setStatusCode(200)->setBody('Please enter a valid email address.');
        }

        try {
            $contactMessageModel->registerMessage([
                'name'        => $name,
                'email'       => $email,
                'phone'       => $phone,
                'school_name' => $school,
                'subject'     => $subject,
                'message'     => $message,
                'ip_address'  => $this->request->getIPAddress(),
                'user_agent'  => substr((string) $this->request->getUserAgent(), 0, 255),
            ]);

            $this->sendEmail([
                'to'      => 'info@navulifiji.com',
                'subject' => 'New enquiry from ' . $name . ($school ? ' (' . $school . ')' : ''),
                'view'    => 'web/emails/contact_notification',
                'viewData' => [
                    'name' => $name, 'email' => $email, 'phone' => $phone,
                    'school_name' => $school, 'subject' => $subject, 'message' => $message,
                ],
                'replyTo' => $email,
            ]);

            return $this->response->setStatusCode(200)->setBody('OK');
        } catch (\Exception $e) {
            log_message('error', '[Home::submitContact] ' . $e->getMessage());
            return $this->response->setStatusCode(200)->setBody('Something went wrong on our end. Please try again shortly.');
        }
    }
}
