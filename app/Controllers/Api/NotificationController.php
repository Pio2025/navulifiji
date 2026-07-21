<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\UserLogModel;
use CodeIgniter\Controller;

/**
 * Mobile Notifications — reuses UserLogModel directly (same query logic as
 * UserController::getNotifications()/markNotificationsRead()).
 */
class NotificationController extends Controller
{
    protected $userLogModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userLogModel = new UserLogModel();
    }

    private function timeAgo(int $ts): string
    {
        $diff = time() - $ts;
        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . ' min ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
        if ($diff < 604800) return floor($diff / 86400) . ' day' . (floor($diff / 86400) > 1 ? 's' : '') . ' ago';
        return date('d M Y', $ts);
    }

    private function format(array $logs): array
    {
        $out = [];
        foreach ($logs as $log) {
            $out[] = [
                'title'  => $log['log_title'] ?? '',
                'desc'   => $log['log_desc'] ?? '',
                'icon'   => $log['log_icon'] ?? '',
                'theme'  => $log['log_theme'] ?? 'primary',
                'status' => $log['log_status'] ?? 'Read',
                'date'   => date('d M Y', strtotime($log['log_date'])),
                'time'   => date('h:i A', (int) $log['log_time']),
                'age'    => $this->timeAgo((int) $log['log_time']),
            ];
        }
        return $out;
    }

    /**
     * GET /api/notifications — requires Bearer token (apijwt filter).
     */
    public function index()
    {
        $myId        = ApiAuth::userId();
        $unreadCount = $this->userLogModel->getUnreadCount($myId);
        $activities  = $this->userLogModel->getRecentByType($myId, 'Activity', 6);
        $alerts      = $this->userLogModel->getRecentByType($myId, 'Alert', 6);

        return $this->response->setJSON([
            'success'     => true,
            'unreadCount' => $unreadCount,
            'activities'  => $this->format($activities),
            'alerts'      => $this->format($alerts),
        ]);
    }

    /**
     * POST /api/notifications/mark-read
     */
    public function markRead()
    {
        $this->userLogModel->markAllRead(ApiAuth::userId());
        return $this->response->setJSON(['success' => true]);
    }
}
