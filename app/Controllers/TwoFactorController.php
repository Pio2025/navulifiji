<?php
namespace App\Controllers;

use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends BaseController
{
    // ── NO property declaration here — inherited from BaseController ──

    // ── NO initController override — not needed ───────────────────

    // ================================================================
    // SETUP — Authenticator App
    // ================================================================

    public function setupAuthenticator()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $userId = $this->session->get('userID');
            $user   = $this->twoFactorModel->get2FAData($userId);

            $google2fa = new Google2FA();
            $secret    = $google2fa->generateSecretKey();

            $this->session->set('2fa_temp_secret', $secret);

            $appName   = 'Navuli Fiji';
            $email     = $user['email'];
            $qrCodeUrl = $google2fa->getQRCodeUrl($appName, $email, $secret);

            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(300),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer  = new \BaconQrCode\Writer($renderer);
            $qrImage = base64_encode($writer->writeString($qrCodeUrl));

            return $this->response->setJSON([
                'success'  => true,
                'secret'   => $secret,
                'qr_image' => $qrImage,
                'qr_mime'  => 'image/svg+xml',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TwoFactorController::setupAuthenticator] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to generate QR code: ' . $e->getMessage()]);
        }
    }

    public function verifyAuthenticator()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $code   = trim($this->request->getPost('code') ?? '');
        $secret = $this->session->get('2fa_temp_secret');
        $userId = $this->session->get('userID');

        if (empty($code) || empty($secret)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request.']);
        }

        try {
            $google2fa = new Google2FA();
            $valid     = $google2fa->verifyKey($secret, $code, 2);

            if (!$valid) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid code. Please try again.']);
            }

            $this->twoFactorModel->enableAuthenticator($userId, $secret);
            $this->session->remove('2fa_temp_secret');
            $this->logTwoFactorActivity('Authenticator App Enabled', '2FA via Authenticator App was enabled.');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Authenticator app enabled successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TwoFactorController::verifyAuthenticator] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Verification failed.']);
        }
    }

    // ================================================================
    // SETUP — OTP via Email
    // ================================================================

    public function setupOtpEmail()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $userId = $this->session->get('userID');
            $user   = $this->twoFactorModel->get2FAData($userId);

            $otp = $this->generateOtpCode();
            $this->twoFactorModel->saveOtp($userId, $otp);

            $sent = $this->sendEmail([
                'to'       => $user['email'],
                'subject'  => 'Your One-Time Password — Navuli Fiji',
                'view'     => 'email/otp_setup',
                'viewData' => [
                    'name'   => $user['fname'],
                    'otp'    => $otp,
                    'expiry' => '10 minutes',
                ],
            ]);

            if (!$sent) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to send OTP email.']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'OTP sent to ' . $user['email'] . '. Check your inbox.',
                'email'   => substr($user['email'], 0, 3) . '***@' . explode('@', $user['email'])[1],
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TwoFactorController::setupOtpEmail] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function verifyOtpEmail()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $code   = trim($this->request->getPost('code') ?? '');
        $userId = $this->session->get('userID');

        if (empty($code)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please enter the OTP code.']);
        }

        try {
            $data = $this->twoFactorModel->get2FAData($userId);

            if (empty($data['otp_code'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'No OTP found. Please request a new one.']);
            }

            if (time() > $data['otp_expiry']) {
                $this->twoFactorModel->clearOtp($userId);
                return $this->response->setJSON(['success' => false, 'message' => 'OTP has expired. Please request a new one.']);
            }

            if ($data['otp_code'] !== $code) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid OTP code.']);
            }

            $this->twoFactorModel->enableOtpEmail($userId);
            $this->twoFactorModel->clearOtp($userId);
            $this->logTwoFactorActivity('Email OTP Enabled', '2FA via Email OTP was enabled.');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Email OTP two-factor authentication enabled successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TwoFactorController::verifyOtpEmail] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Verification failed.']);
        }
    }

    // ================================================================
    // DISABLE 2FA
    // ================================================================

    public function disable()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $userId = $this->session->get('userID');
            $this->twoFactorModel->disable2FA($userId);
            $this->logTwoFactorActivity('2FA Disabled', 'Two-factor authentication was disabled.');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Two-factor authentication has been disabled.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TwoFactorController::disable] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to disable 2FA.']);
        }
    }

    // ================================================================
    // LOGIN FLOW
    // ================================================================

    public function verify()
    {
        if (!$this->session->get('2fa_pending')) {
            return redirect()->to('auth/login');
        }

        $method = $this->session->get('2fa_method');

        if ($method === 'otp_email') {
            $this->sendLoginOtp();
        }

        $data = [
            '_view'  => 'auth/two_factor_verify',
            'method' => $method,
        ];

        return view('app/layouts/auth_main', $data);
    }

    public function sendLoginOtp()
    {
        $userId = $this->session->get('2fa_user_id');
        if (!$userId) return;

        try {
            $user = $this->twoFactorModel->get2FAData($userId);
            $otp  = $this->generateOtpCode();
            $this->twoFactorModel->saveOtp($userId, $otp);

            $this->sendEmail([
                'to'       => $user['email'],
                'subject'  => 'Login Verification Code — Navuli Fiji',
                'view'     => 'email/otp_login',
                'viewData' => [
                    'name'   => $user['fname'],
                    'otp'    => $otp,
                    'expiry' => '10 minutes',
                ],
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TwoFactorController::sendLoginOtp] ' . $e->getMessage());
        }
    }

    public function resendOtp()
    {
        if (!$this->session->get('2fa_pending')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Session expired.']);
        }

        $this->sendLoginOtp();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'A new OTP has been sent to your email.',
        ]);
    }

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
            return redirect()->to('auth/2fa/verify?error=' . urlencode('Please enter the complete 6-digit verification code.'));
        }
    
        try {
            $data  = $this->twoFactorModel->get2FAData($userId);
            $valid = false;
    
            if ($method === 'authenticator') {
                $google2fa = new Google2FA();
                $valid     = $google2fa->verifyKey($data['two_factor_secret'], $code, 2);
    
            } elseif ($method === 'otp_email') {
                if (empty($data['otp_code'])) {
                    return redirect()->to('auth/2fa/verify?error=' . urlencode('No OTP found. Please request a new one.'));
                }
                if (time() > $data['otp_expiry']) {
                    $this->twoFactorModel->clearOtp($userId);
                    return redirect()->to('auth/2fa/verify?error=' . urlencode('Your code has expired. Please request a new one.'));
                }
                if ($data['otp_code'] === $code) {
                    $valid = true;
                    $this->twoFactorModel->clearOtp($userId);
                }
            }
    
            if (!$valid) {
                return redirect()->to('auth/2fa/verify?error=' . urlencode('Invalid verification code. Please try again.'));
            }
    
            // ── 2FA passed — now build the full session ───────────────
    
            // Get full user data
            $user       = $this->userModel->findUserFull($userId);
            $userStatus = $user['status'] ?? 'Active';
    
            // Update online status
            $this->userModel->updateUser($userId, ['online_status' => 'Online']);
    
            // Get user role
            $userRole    = $this->userRoleModel->findActiveUserRole($userId);
            $roleID      = $userRole['role_id_fk']    ?? 0;
            $roleName    = $userRole['role_name']      ?? 'Unknown';
            $rank        = $userRole['role_rank']      ?? 900000000;
            $roleCatID   = $userRole['role_cat_id']    ?? 0;
            $roleCatName = $userRole['role_cat_name']  ?? 'Unknown';
    
            // Build name
            $name = trim($user['fname'] . ' ' . ($user['oname'] ? $user['oname'] . ' ' : '') . $user['lname']);
    
            // Build photo
            if (!empty($user['profile_photo'])) {
                $photo = $user['profile_photo'];
            } elseif (strtolower($user['gender'] ?? '') === 'female') {
                $photo = 'default_female.png';
            } else {
                $photo = 'default_male.jpg';
            }
    
            // Add login log
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
    
            // Set session data — same structure as normal login
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
    
            // Clear 2FA pending flags
            $this->session->remove(['2fa_pending', '2fa_user_id', '2fa_method']);
    
            // Create session record
            $this->create2FASessionRecord($userId);
    
            // Redirect
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
            return redirect()->to('auth/2fa/verify?error=' . urlencode('Verification failed. Please try again.'));
        }
    }

    // ================================================================
    // STATUS
    // ================================================================

    public function status()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = $this->session->get('userID');
        $data   = $this->twoFactorModel->get2FAData($userId);

        return $this->response->setJSON([
            'success' => true,
            'enabled' => (bool) ($data['two_factor_enabled'] ?? false),
            'method'  => $data['two_factor_method'] ?? null,
        ]);
    }

    // ================================================================
    // PRIVATE HELPERS
    // ================================================================

    private function generateOtpCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function logTwoFactorActivity(string $title, string $desc): void
    {
        $this->userLogModel->insert([
            'user_id_fk'  => $this->session->get('userID'),
            'ip_aadress'  => $this->ipAddress,
            'user_agent'  => $this->userAgent->getAgentString(),
            'user_device' => $this->deviceInfo['device_type'],
            'log_title'   => $title,
            'log_desc'    => $desc,
            'log_date'    => date('Y-m-d'),
            'log_time'    => time(),
            'log_icon'    => '<i class="ki-duotone ki-shield-tick"><span class="path1"></span><span class="path2"></span></i>',
            'log_theme'   => 'success',
        ]);
    }

    private function create2FASessionRecord(int $userId): void
    {
        $sessionToken = bin2hex(random_bytes(32));
        $this->session->set('sessionToken', $sessionToken);

        $ua       = $this->request->getUserAgent()->getAgentString();
        $parsed   = $this->parseUserAgent($ua);
        $location = $this->getLocationFromIP($this->ipAddress);

        $this->userSessionModel->insert([
            'user_id_fk'     => $userId,
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
    }
}