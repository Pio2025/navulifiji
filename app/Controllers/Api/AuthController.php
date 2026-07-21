<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Libraries\ApiJwt;
use App\Models\AdmissionModel;
use App\Models\TwoFactorModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;
use CodeIgniter\Controller;

/**
 * Stateless JWT-based auth for the Navuli mobile app.
 * Mirrors AuthController::process_login()'s lookup/validation logic, but
 * issues a Bearer token instead of a PHP session.
 */
class AuthController extends Controller
{
    protected $userModel;
    protected $userRoleModel;
    protected $twoFactorModel;
    protected $admissionModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->userModel     = new UserModel();
        $this->userRoleModel = new UserRoleModel();
        $this->twoFactorModel = new TwoFactorModel();
        $this->admissionModel = new AdmissionModel();
    }

    /**
     * POST /api/auth/login
     * Body: { "identifier": "email or username", "password": "..." }
     */
    public function login()
    {
        $identifier = trim((string) $this->request->getPost('identifier') ?: (string) ($this->request->getJSON(true)['identifier'] ?? ''));
        $password   = (string) ($this->request->getPost('password') ?: ($this->request->getJSON(true)['password'] ?? ''));

        if ($identifier === '' || $password === '') {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Identifier and password are required.',
            ]);
        }

        $isEmail = str_contains($identifier, '@');
        $user = $this->userModel
            ->select('users.*, user_role.*, district.*')
            ->join('user_role', 'user_role.user_id_fk = users.user_id', 'left')
            ->join('district', 'district.district_id = users.district_id_fk', 'left')
            ->where($isEmail ? 'users.email' : 'users.username', $identifier)
            ->first();

        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'No account found with that ' . ($isEmail ? 'email address' : 'username') . '.',
            ]);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Invalid password. Please enter correct password.',
            ]);
        }

        $userStatus = $user['user_status'] ?? 'Inactive';
        $userID     = (int) $user['user_id'];

        if ($userStatus !== 'Active') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Your account is not active yet. Please activate your account first.',
            ]);
        }

        $tfData = $this->twoFactorModel->get2FAData($userID);
        if (!empty($tfData['two_factor_enabled']) && $tfData['two_factor_enabled'] == 1) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Two-factor authentication is enabled on this account. Mobile login for 2FA accounts is not yet supported — please log in via the website.',
            ]);
        }

        $userRole = $this->userRoleModel->findActiveUserRole($userID);
        if ($userRole) {
            $roleID      = (int) $userRole['role_id_fk'];
            $roleName    = $userRole['role_name'];
            $roleCatID   = (int) $userRole['role_cat_id'];
            $roleCatName = $userRole['role_cat_name'];
        } else {
            $roleID      = 0;
            $roleName    = 'Unknown';
            $roleCatID   = 0;
            $roleCatName = 'Unknown';
        }

        $name = $user['oname'] !== '' && $user['oname'] !== null
            ? trim($user['fname'] . ' ' . $user['oname'] . ' ' . $user['lname'])
            : trim($user['fname'] . ' ' . $user['lname']);

        if (!empty($user['profile_photo'])) {
            $photo = $user['profile_photo'];
        } else {
            $photo = ($user['gender'] ?? '') === 'Female' ? 'default_female.png' : 'default_male.jpg';
        }

        $admissions   = $this->admissionModel->getAdmissionByUser($userID);
        $getAdmission = !empty($admissions) ? $admissions[0] : [];
        $schID        = !empty($getAdmission['sch_id_fk']) ? (int) $getAdmission['sch_id_fk'] : 0;

        if ($schID === 0) {
            $db = \Config\Database::connect();
            $staffRow = $db->table('staff')
                ->select('sch_id_fk')
                ->where('user_id_fk', $userID)
                ->where('staff_status', 'Active')
                ->limit(1)
                ->get()->getRowArray();
            if ($staffRow) {
                $schID = (int) $staffRow['sch_id_fk'];
            }
            if ($schID === 0) {
                $classroomRow = $db->table('classroom_subject_teacher cst')
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
        }

        $token = ApiJwt::encode([
            'userId'      => $userID,
            'schID'       => $schID,
            'roleID'      => $roleID,
            'roleCatID'   => $roleCatID,
            'roleCatName' => $roleCatName,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'token'   => $token,
            'user'    => [
                'userId'      => $userID,
                'name'        => $name,
                'email'       => $user['email'] ?? '',
                'gender'      => $user['gender'] ?? '',
                'photo'       => base_url('uploads/profilePhoto/' . $photo),
                'roleID'      => $roleID,
                'roleName'    => $roleName,
                'roleCatID'   => $roleCatID,
                'roleCatName' => $roleCatName,
                'schID'       => $schID,
            ],
        ]);
    }

    /**
     * GET /api/auth/me — requires Bearer token (apijwt filter).
     */
    public function me()
    {
        $userID = ApiAuth::userId();
        $user   = $this->userModel->find($userID);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found.']);
        }

        $claims = ApiAuth::claims();
        $name = !empty($user['oname'])
            ? trim($user['fname'] . ' ' . $user['oname'] . ' ' . $user['lname'])
            : trim($user['fname'] . ' ' . $user['lname']);

        $photo = !empty($user['profile_photo'])
            ? $user['profile_photo']
            : (($user['gender'] ?? '') === 'Female' ? 'default_female.png' : 'default_male.jpg');

        return $this->response->setJSON([
            'success' => true,
            'user' => [
                'userId'      => $userID,
                'name'        => $name,
                'email'       => $user['email'] ?? '',
                'gender'      => $user['gender'] ?? '',
                'photo'       => base_url('uploads/profilePhoto/' . $photo),
                'roleID'      => $claims['roleID'] ?? 0,
                'roleCatID'   => $claims['roleCatID'] ?? 0,
                'roleCatName' => $claims['roleCatName'] ?? 'Unknown',
                'schID'       => $claims['schID'] ?? 0,
            ],
        ]);
    }
}
