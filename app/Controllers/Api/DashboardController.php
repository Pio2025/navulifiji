<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Libraries\DashboardStats;
use App\Models\UserModel;
use CodeIgniter\Controller;

/**
 * Mobile dashboard — reuses DashboardStats (extracted from the web
 * DashboardController) so both surfaces share identical query logic.
 */
class DashboardController extends Controller
{
    protected $userModel;
    protected $dashboardStats;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userModel      = new UserModel();
        $this->dashboardStats = new DashboardStats();
    }

    /**
     * GET /api/dashboard — requires Bearer token (apijwt filter).
     */
    public function index()
    {
        $claims    = ApiAuth::claims();
        $userId    = ApiAuth::userId();
        $roleID    = (int) ($claims['roleID'] ?? 0);
        $roleCatID = (int) ($claims['roleCatID'] ?? 0);
        $schID     = (int) ($claims['schID'] ?? 0);

        $role = match ($roleCatID) {
            1 => 'super_admin',
            2, 7 => 'school_admin',
            3 => 'teacher',
            4 => 'student',
            6 => 'parent',
            default => 'other',
        };

        $stats = match (true) {
            $roleID === 1 || $roleCatID === 1 => $this->dashboardStats->superAdminStats(),
            $roleCatID === 2 || $roleCatID === 7 => $this->dashboardStats->schoolAdminStats($schID),
            $roleCatID === 3 => $this->dashboardStats->teacherStats($userId),
            $roleCatID === 4 => $this->dashboardStats->studentStats($userId),
            default => [],
        };

        $user      = $this->userModel->find($userId);
        $isAParent = (int) ($user['is_a_parent'] ?? 0) === 1 || $roleCatID === 6;

        $childStats    = [];
        $announcements = [];

        if ($isAParent) {
            $parentData = $this->dashboardStats->parentStats($userId);
            $announcements = $parentData['pr_announcements'] ?? [];

            foreach ($parentData['pr_children'] ?? [] as $child) {
                $childStats[] = [
                    'childUserId'  => (int) $child['user_id'],
                    'name'         => trim(($child['fname'] ?? '') . ' ' . ($child['lname'] ?? '')),
                    'relationship' => $child['relationship'] ?? '',
                    'stats'        => $child,
                ];
            }
        }

        if ($roleCatID === 6) {
            $stats = ['announcements' => $announcements];
        }

        return $this->response->setJSON([
            'success'    => true,
            'role'       => $role,
            'stats'      => $stats,
            'childStats' => $childStats,
        ]);
    }
}
