<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    protected $validation;
    protected $session;
    protected $email;
    protected $helpers = ['form', 'url']; // Add form helper
    protected \App\Libraries\DashboardStats $dashboardStats;


    public function __construct()
    {
        helper('form,url'); // Load URL helper if you use base_url() in views
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();
        $this->dashboardStats = new \App\Libraries\DashboardStats();
    }
    
    
    public function index(){
        $view = '';
        $this->session->set('prevUrl',$this->session->get('url'));
        $this->session->set('url','dashboard');

        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('View Dashboard', 'Dashboard', 'Dashboard');

        $accessCheck = $this->require_access('_view_dashboard');
        if ($accessCheck !== true) {
            $view = 'app/auth/access_control';
            $data = $this->loadCommonData($view);
            return view('app/layouts/main', $data);
        }

        $roleCatID = (int) $this->session->get('roleCatID');
        $roleID    = (int) $this->session->get('roleID');
        $userId    = (int) $this->session->get('userID');
        $additionalData = [];

        // Any account outside Teacher/Student/Parent (System Admin, School Admin,
        // Support Staff, or any other role category) that is also flagged as a
        // parent with linked children gets a combined "own dashboard + child tabs"
        // view instead of full substitution, mirroring the Teacher-parent pattern.
        $ownView = null;
        $ownData = [];
        $parentView = null;

        if ($roleID === 1 || $roleCatID === 1) {
            $ownView    = 'app/dashboard/super_admin';
            $ownData    = $this->dashboardStats->superAdminStats();
            $parentView = 'app/dashboard/super_admin_parent';
        } elseif ($roleCatID === 2 || $roleCatID === 7) {
            $ownView    = 'app/dashboard/school_admin';
            $ownData    = $this->dashboardStats->schoolAdminStats((int) $this->session->get('schID'));
            $parentView = 'app/dashboard/school_admin_parent';
        } elseif ($roleCatID === 3) {
            $children = $this->hasParentFlag($userId) ? $this->parentStudentModel->getChildrenOf($userId) : [];

            if (!empty($children)) {
                $view = 'app/dashboard/teacher_parent';
                $additionalData = array_merge($this->dashboardStats->teacherStats($userId), $this->dashboardStats->parentStats($userId));
            } else {
                $view = 'app/dashboard/teacher';
                $additionalData = $this->dashboardStats->teacherStats($userId);
            }
        } elseif ($roleCatID === 4) {
            $view = 'app/dashboard/student';
            $additionalData = $this->dashboardStats->studentStats($userId);
        } elseif ($roleCatID === 6) {
            $view = 'app/dashboard/parent';
            $additionalData = $this->dashboardStats->parentStats($userId);
        } else {
            $ownView    = 'app/dashboard/index';
            $parentView = 'app/dashboard/generic_parent';
        }

        if ($ownView !== null) {
            $children = $this->hasParentFlag($userId) ? $this->parentStudentModel->getChildrenOf($userId) : [];

            if (!empty($children)) {
                $view = $parentView;
                $additionalData = array_merge($ownData, $this->dashboardStats->parentStats($userId));
            } else {
                $view = $ownView;
                $additionalData = $ownData;
            }
        }

        $data = $this->loadCommonData($view, $additionalData);
        return view('app/layouts/main', $data);
    }

    // ─── Unread counts for nav badges ─────────────────────────────────────────

    public function unreadCounts(): \CodeIgniter\HTTP\ResponseInterface
    {
        $zeroPayload = ['notices' => 0, 'announcements' => 0, 'conduct_appeals' => 0, 'events' => 0, 'wall' => 0, 'messages' => 0];

        if (!$this->isLoggedIn()) {
            return $this->response->setJSON($zeroPayload);
        }

        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');
        $db      = \Config\Database::connect();

        // Instant-message unread count is global to the user, independent of school scope.
        $messageCount = (new \App\Models\ChatModel())->getTotalUnreadCount($userId);

        $userRow  = (new \App\Models\UserModel())->find($userId);
        $isParent = $roleCat === 6
            || ($roleCat !== 3 && (int) (($userRow)['is_a_parent'] ?? 0) === 1);

        $audience = match ($roleCat) {
            3 => 'Teachers',
            4 => 'Students',
            6 => 'Parents',
            default => 'All',
        };

        if ($isParent) {
            $schools = $db->query("
                SELECT DISTINCT a.sch_id_fk
                FROM parent_student ps
                INNER JOIN admission a ON a.user_id_fk = ps.student_user_id_fk
                WHERE ps.parent_user_id_fk = ? AND a.admission_status = 'Active'
            ", [$userId])->getResultArray();

            $schIds = array_map('intval', array_column($schools, 'sch_id_fk'));

            if (empty($schIds)) {
                return $this->response->setJSON(array_merge($zeroPayload, ['messages' => $messageCount]));
            }

            $inList = implode(',', $schIds);
            $now    = date('Y-m-d H:i:s');

            try {
                $noticeCount = (int) $db->query("
                    SELECT COUNT(*) AS cnt
                    FROM notice_board nb
                    LEFT JOIN notice_reads nr ON nr.notice_id = nb.notice_id AND nr.user_id = ?
                    WHERE nb.sch_id_fk IN ({$inList})
                      AND nb.notice_status = 'Active'
                      AND (nb.expires_at IS NULL OR nb.expires_at > ?)
                      AND (nb.audience = 'All' OR nb.audience = 'Parents')
                      AND nr.nr_id IS NULL
                ", [$userId, $now])->getRow()->cnt;
            } catch (\Throwable $e) {
                $noticeCount = 0;
            }

            try {
                $annCount = (int) $db->query("
                    SELECT COUNT(*) AS cnt
                    FROM school_announcement sa
                    LEFT JOIN announcement_reads ar ON ar.announcement_id = sa.announcement_id AND ar.user_id = ?
                    WHERE sa.sch_id_fk IN ({$inList})
                      AND sa.announcement_status = 'Active'
                      AND (sa.expires_at IS NULL OR sa.expires_at > ?)
                      AND ar.ar_id IS NULL
                ", [$userId, $now])->getRow()->cnt;
            } catch (\Throwable $e) {
                $annCount = 0;
            }

            try {
                $eventCount = (int) $db->query("
                    SELECT COUNT(*) AS cnt
                    FROM school_event se
                    LEFT JOIN event_reads er ON er.event_id = se.event_id AND er.user_id = ?
                    WHERE se.sch_id_fk IN ({$inList})
                      AND er.er_id IS NULL
                ", [$userId])->getRow()->cnt;
            } catch (\Throwable $e) {
                $eventCount = 0;
            }

            try {
                $wallCount = (int) $db->query("
                    SELECT COUNT(*) AS cnt
                    FROM wall_post wp
                    LEFT JOIN wall_reads wr ON wr.wall_post_id = wp.wall_post_id AND wr.user_id = ?
                    WHERE wp.sch_id_fk IN ({$inList})
                      AND wp.post_status = 'Active'
                      AND wr.wr_id IS NULL
                ", [$userId])->getRow()->cnt;
            } catch (\Throwable $e) {
                $wallCount = 0;
            }

            $conductAppealCount = 0;

        } else {
            $schId = (int) $this->session->get('schID');
            if ($schId <= 0) {
                return $this->response->setJSON(array_merge($zeroPayload, ['messages' => $messageCount]));
            }

            $annModel    = new \App\Models\AnnouncementModel();
            $noticeModel = new \App\Models\NoticeBoardModel();
            $eventModel  = new \App\Models\EventModel();
            $wallModel   = new \App\Models\WallModel();

            $isSuperAdmin = (int) $this->session->get('roleID') === 1;

            $noticeCount = $noticeModel->getUnreadCountForUser($userId, $schId, $audience);
            $annCount    = $annModel->getUnreadCountForUser($userId, $schId);
            $eventCount  = $eventModel->getUnreadCountForUser($userId, $isSuperAdmin ? 0 : $schId);
            $wallCount   = $wallModel->getUnreadCountForUser($userId, $schId);

            $conductAppealCount = 0;
            if ($isSuperAdmin || $this->grant_access('_process_conduct_appeal')) {
                $conductAppealCount = (new \App\Models\ConductAppealModel())
                    ->getUnreadPendingCount($userId, $schId, $isSuperAdmin);
            }
        }

        return $this->response->setJSON([
            'notices'         => $noticeCount,
            'announcements'   => $annCount,
            'conduct_appeals' => $conductAppealCount,
            'events'          => $eventCount,
            'wall'            => $wallCount,
            'messages'        => $messageCount,
        ]);
    }

    // ─── Generic optimistic mark-read endpoint (badge store) ──────────────────

    public function markRead(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $domain  = (string) $this->request->getPost('domain');
        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');
        $db      = \Config\Database::connect();

        $userRow  = (new \App\Models\UserModel())->find($userId);
        $isParent = $roleCat === 6
            || ($roleCat !== 3 && (int) (($userRow)['is_a_parent'] ?? 0) === 1);

        $audience = match ($roleCat) {
            3 => 'Teachers',
            4 => 'Students',
            6 => 'Parents',
            default => 'All',
        };

        $schIds = [];
        if ($isParent) {
            $schools = $db->query("
                SELECT DISTINCT a.sch_id_fk
                FROM parent_student ps
                INNER JOIN admission a ON a.user_id_fk = ps.student_user_id_fk
                WHERE ps.parent_user_id_fk = ? AND a.admission_status = 'Active'
            ", [$userId])->getResultArray();
            $schIds = array_map('intval', array_column($schools, 'sch_id_fk'));
        } else {
            $schId = (int) $this->session->get('schID');
            if ($schId > 0) $schIds = [$schId];
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        switch ($domain) {
            case 'notice':
                $model = new \App\Models\NoticeBoardModel();
                foreach ($schIds as $schId) $model->markAllReadForUser($userId, $schId, $audience);
                break;

            case 'announcement':
                $model = new \App\Models\AnnouncementModel();
                foreach ($schIds as $schId) $model->markAllReadForUser($userId, $schId);
                break;

            case 'event':
                $model = new \App\Models\EventModel();
                if ($isSuperAdmin) {
                    $model->markAllReadForUser($userId, 0);
                } else {
                    foreach ($schIds as $schId) $model->markAllReadForUser($userId, $schId);
                }
                break;

            case 'wall':
                $model = new \App\Models\WallModel();
                foreach ($schIds as $schId) $model->markAllReadForUser($userId, $schId);
                break;

            case 'conduct_appeal':
                (new \App\Models\ConductAppealModel())
                    ->markPendingRead($userId, $schIds[0] ?? 0, $isSuperAdmin);
                break;

            case 'activity_alert':
                (new \App\Models\UserLogModel())->markAllRead($userId);
                break;

            default:
                return $this->response->setJSON(['success' => false, 'message' => 'Unknown domain.']);
        }

        return $this->response->setJSON(['success' => true]);
    }
}
