<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\UserModel;
use CodeIgniter\Controller;

class IntakeController extends Controller
{
    protected $userModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->userModel = new UserModel();
    }

    /**
     * GET /api/intake/permissions — drives the Intake tab's menu buttons.
     */
    public function permissions()
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleId    = (int) ($claims['roleID'] ?? 0);
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);

        $isSuperAdmin = $roleId === 1;
        $isAdmin      = $roleCatId === 7;
        $canAccess    = $isSuperAdmin || $isAdmin;

        $user      = $this->userModel->find($myId);
        $isAParent = !empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1;

        return $this->response->setJSON([
            'success'     => true,
            'permissions' => [
                'canAccessAdmission'      => $canAccess,
                'canAccessEnrolment'      => $canAccess,
                'canViewMyChildAdmission' => $isAParent,
                'canViewMyChildEnrolment' => $isAParent,
            ],
        ]);
    }
}
