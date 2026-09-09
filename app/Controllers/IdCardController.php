<?php

namespace App\Controllers;

class IdCardController extends BaseController
{
    /** QR verification link stays scannable for the life of a physical card. */
    private const QR_TTL_SECONDS = 315360000; // ~10 years

    /** Signature length for the compact verify token — short enough to keep the QR pattern coarse. */
    private const VERIFY_TOKEN_SIG_BYTES = 9;

    private const ADMISSION_ROLE_CATS = [2, 3, 4, 5]; // School Admin, Teacher, Student, Support Staff

    /** Fields the card prints that can't be captured on this page — must already be on the profile. */
    private const REQUIRED_PROFILE_FIELDS = [
        'dob'           => 'Date of Birth',
        'address'       => 'Address',
        'district_name' => 'District',
        'province_name' => 'Province',
    ];

    public function generate($userId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Generate ID Card', 'User', 'User Listing');

        if ($this->require_access('_edit_user') !== true) {
            return view('app/layouts/main', ['_view' => 'app/auth/access_control']);
        }

        $user = $this->userModel->findUserFull($userId);
        if (!$user) {
            return redirect()->to('user')->with('error', 'User not found.');
        }

        $role      = $this->userRoleModel->findActiveUserRole($userId);
        $roleCatId = (int) ($role['role_cat_id'] ?? 0);

        $school = $this->currentSchoolFor((int) $userId, $roleCatId);

        $data = [
            '_view'         => 'app/user/idcard_generate',
            'userID'        => $userId,
            'user'          => $user,
            'role'          => $role,
            'school'        => $school,
            'missingFields' => $this->missingRequiredFields($user),
        ];

        return view('app/layouts/main', $data);
    }

    private function missingRequiredFields(array $user): array
    {
        $missing = [];
        foreach (self::REQUIRED_PROFILE_FIELDS as $field => $label) {
            if (empty($user[$field])) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

    public function save($userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Please login to continue.']);
        }
        if ($this->require_access('_edit_user') !== true) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to do this.']);
        }

        $user = $this->userModel->findUserFull($userId);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'User not found.']);
        }

        $missing = $this->missingRequiredFields($user);
        if (!empty($missing)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Please complete the following on the profile first: ' . implode(', ', $missing) . '.',
            ]);
        }

        $useExisting = $this->request->getPost('use_existing') === '1';
        $photoName   = $user['profile_photo'] ?? null;

        if (!$useExisting) {
            $dataUrl = (string) $this->request->getPost('photo_data');
            if (!preg_match('/^data:image\/(png|jpe?g);base64,/', $dataUrl, $m)) {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'No valid photo was captured.']);
            }

            $raw = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1), true);
            if ($raw === false || strlen($raw) > (4 * 1024 * 1024)) {
                return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid photo data.']);
            }

            $uploadPath = FCPATH . 'uploads/profilePhoto';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $ext        = strtolower($m[1]) === 'png' ? 'png' : 'jpg';
            $oldPhoto   = $user['profile_photo'] ?? null;
            $photoName  = bin2hex(random_bytes(16)) . '.' . $ext;
            file_put_contents($uploadPath . '/' . $photoName, $raw);

            $this->userModel->updateUser($userId, ['profile_photo' => $photoName]);

            if ($oldPhoto && $oldPhoto !== $photoName && file_exists($uploadPath . '/' . $oldPhoto)) {
                @unlink($uploadPath . '/' . $oldPhoto);
            }
        }

        if (!$photoName) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'A photo is required to generate the ID card.']);
        }

        return $this->response->setJSON(['success' => true, 'redirect' => site_url('user/idcard/' . $userId . '/pdf')]);
    }

    public function pdf($userId)
    {
        if (!$this->isLoggedIn() || $this->require_access('_edit_user') !== true) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $user = $this->userModel->findUserFull($userId);
        if (!$user) {
            return redirect()->to('user')->with('error', 'User not found.');
        }
        if (empty($user['profile_photo'])) {
            return redirect()->to('user/idcard/' . $userId)->with('error', 'Please add a photo before generating the ID card.');
        }

        $missing = $this->missingRequiredFields($user);
        if (!empty($missing)) {
            return redirect()->to('user/idcard/' . $userId)
                ->with('error', 'Please complete the following on the profile first: ' . implode(', ', $missing) . '.');
        }

        $role      = $this->userRoleModel->findActiveUserRole($userId);
        $roleCatId = (int) ($role['role_cat_id'] ?? 0);
        $roleCat   = $role['role_cat_name'] ?? 'Member';
        $school    = $this->currentSchoolFor((int) $userId, $roleCatId);

        $token     = $this->makeVerifyToken((int) $userId, self::QR_TTL_SECONDS);
        $verifyUrl = site_url('idcard/verify/' . $token);

        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

        // CR80 card size (mm), landscape, two pages: front then back.
        $pdf = new \TCPDF('L', 'mm', [54, 86], true, 'UTF-8', false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCreator('Navuli');
        $pdf->SetTitle('ID Card - ' . trim($user['fname'] . ' ' . $user['lname']));

        $primary   = $this->hexToRgb($school['sch_primary_color']   ?? '#005B96');
        $secondary = $this->hexToRgb($school['sch_secondary_color'] ?? '#EE2A7B');

        $this->renderFront($pdf, $user, $school, $roleCat, $primary, $secondary);
        $this->renderBack($pdf, $verifyUrl, $primary);

        // TCPDF sends its own headers and echoes the PDF directly; exit before
        // CodeIgniter's response cycle can overwrite them with text/html.
        $pdf->Output('id-card-' . $userId . '.pdf', 'I');
        exit;
    }

    /**
     * Public — reached by scanning the QR code on the back of a printed card.
     * Deliberately unauthenticated so anyone holding the card can verify it.
     */
    public function verify(string $token)
    {
        $targetId = $this->parseVerifyToken($token);
        $valid    = false;
        $info     = null;

        if ($targetId) {
            $user = $this->userModel->find($targetId);

            if ($user) {
                $role     = $this->userRoleModel->findActiveUserRole($targetId);
                $roleCat  = $role['role_cat_name'] ?? 'Member';
                $isActive = ($user['user_status'] ?? '') === 'Active';

                // A student whose account is no longer active is shown as an alumnus.
                if (!$isActive && strcasecmp($roleCat, 'Student') === 0) {
                    $roleCat = 'Alumni';
                }

                $valid = true;
                $info  = [
                    'name'        => trim($user['fname'] . ' ' . $user['lname']),
                    'designation' => $roleCat,
                    'status'      => $isActive ? 'Active' : 'Inactive',
                    'photo'       => !empty($user['profile_photo']) ? base_url('uploads/profilePhoto/' . $user['profile_photo']) : null,
                ];
            }
        }

        return view('app/user/idcard_verify', ['valid' => $valid, 'info' => $info]);
    }

    private function currentSchoolFor(int $userId, int $roleCatId): ?array
    {
        if (!in_array($roleCatId, self::ADMISSION_ROLE_CATS, true)) {
            return null;
        }

        $rows = $roleCatId === 4
            ? $this->admissionModel->getAdmissionWithEnrolment($userId)
            : $this->admissionModel->getAdmissionWithSchool($userId);

        return $rows[0] ?? null;
    }

    private function renderFront(\TCPDF $pdf, array $user, ?array $school, string $roleCat, array $primary, array $secondary): void
    {
        $pdf->AddPage();

        $pdf->SetFillColor(...$primary);
        $pdf->Rect(0, 0, 86, 54, 'F');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(0, 13, 86, 41, 'F');
        $pdf->SetFillColor(...$secondary);
        $pdf->Rect(0, 13, 86, 1, 'F');

        $logoPath  = FCPATH . 'uploads/school/logo/' . ($school['sch_logo'] ?? '');
        $hasLogo   = !empty($school['sch_logo']) && file_exists($logoPath);
        $headerX   = 4;
        if ($hasLogo) {
            $pdf->Image($logoPath, 3, 2.5, 8, 8, '', '', 'T', false, 300, '', false, false, 0, 'CM');
            $headerX = 13;
        }

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY($headerX, 3);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(86 - $headerX - 3, 4.5, strtoupper($school['sch_name'] ?? 'Navuli'), 0, 1);
        $pdf->SetXY($headerX, 7.5);
        $pdf->SetFont('helvetica', '', 5);
        $pdf->Cell(86 - $headerX - 3, 4, 'IDENTITY CARD', 0, 1);

        $photoPath = FCPATH . 'uploads/profilePhoto/' . $user['profile_photo'];
        if (file_exists($photoPath)) {
            $photoBoxW = 20;
            $photoBoxH = 24;
            $cropped   = $this->coverCropTemp($photoPath, $photoBoxW / $photoBoxH);
            $pdf->Image($cropped ?? $photoPath, 4, 17, $photoBoxW, $photoBoxH, '', '', '', false, 300, '', false, false, 0, $cropped ? false : 'CM');
            $pdf->SetLineWidth(0.3);
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Rect(4, 17, $photoBoxW, $photoBoxH, 'D');
            if ($cropped) {
                @unlink($cropped);
            }
        }

        $x = 27;
        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetXY($x, 17.5);
        $pdf->SetFont('helvetica', 'B', 9.5);
        $pdf->MultiCell(56, 5, trim($user['fname'] . ' ' . $user['lname']), 0, 'L');

        $pdf->SetTextColor(...$secondary);
        $pdf->SetXY($x, 23);
        $pdf->SetFont('helvetica', 'B', 6.5);
        $pdf->Cell(56, 4, strtoupper($roleCat), 0, 1);

        $pdf->SetTextColor(90, 90, 90);
        $rows = [
            ['DOB',      !empty($user['dob']) ? date('d M Y', strtotime($user['dob'])) : '—'],
            ['Address',  !empty($user['address']) ? trim(preg_replace('/\s+/', ' ', $user['address'])) : '—'],
            ['District', !empty($user['district_name']) ? $user['district_name'] : '—'],
            ['Province', !empty($user['province_name']) ? $user['province_name'] : '—'],
        ];
        $ry = 28;
        foreach ($rows as [$label, $val]) {
            $pdf->SetXY($x, $ry);
            $pdf->SetFont('helvetica', 'B', 5.5);
            $pdf->Cell(14, 3.6, $label . ':', 0, 0);
            $pdf->SetFont('helvetica', '', 5.5);
            $pdf->SetXY($x + 14, $ry);
            $pdf->MultiCell(42, 3.2, $val, 0, 'L');
            $ry = max($ry + 4.3, $pdf->GetY() + 0.3);
        }
    }

    private function renderBack(\TCPDF $pdf, string $verifyUrl, array $primary): void
    {
        $pdf->AddPage();
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(0, 0, 86, 54, 'F');
        $pdf->SetFillColor(...$primary);
        $pdf->Rect(0, 0, 86, 1.2, 'F');

        $qrSize = 28;
        $qrX    = 5;
        $qrY    = 8;
        $pdf->SetLineWidth(0.2);
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Rect($qrX - 1, $qrY - 1, $qrSize + 2, $qrSize + 2, 'D');
        // 'M' (not 'H') keeps the QR at a lower version for this URL length, so modules
        // stay large/scannable in the fixed box; still 15% error-correction headroom.
        $pdf->write2DBarcode($verifyUrl, 'QRCODE,M', $qrX, $qrY, $qrSize, $qrSize, [
            'border'   => false,
            'vpadding' => 0,
            'hpadding' => 0,
            'fgcolor'  => [0, 0, 0],
            'bgcolor'  => false,
        ], 'N');

        $tx = $qrX + $qrSize + 8;
        $navuliLogo = FCPATH . 'web/assets/img/logo.png';
        if (file_exists($navuliLogo)) {
            $pdf->Image($navuliLogo, $tx, 8, 32, 0, '', '', 'T', false, 300);
        }

        $pdf->SetTextColor(60, 60, 60);
        $pdf->SetXY($tx, 18);
        $pdf->SetFont('helvetica', '', 5);
        $pdf->MultiCell(42, 3.3, "School Management Information System\nwww.navulifiji.com\ninfo@navulifiji.com\n+679 989 6700", 0, 'L');

        $pdf->SetXY($qrX - 1, $qrY + $qrSize + 2);
        $pdf->SetFont('helvetica', 'I', 3.6);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->MultiCell($qrSize + 2, 2.8, "Scan the QR code to verify this card is genuine and see the holder's current status.", 0, 'C');

        $pdf->SetXY(4, 48);
        $pdf->SetFont('helvetica', 'I', 4.3);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->MultiCell(78, 3.2, 'This card is a property and issued by the Navuli School Management Information System.', 0, 'C');
    }

    /**
     * Center-crops $srcPath to the given width:height ratio (cover, not contain)
     * and writes the result to a temp JPEG. Returns null on failure.
     */
    private function coverCropTemp(string $srcPath, float $targetRatioWH): ?string
    {
        $info = @getimagesize($srcPath);
        if (!$info) {
            return null;
        }

        [$srcW, $srcH] = $info;
        if ($srcW < 1 || $srcH < 1) {
            return null;
        }

        $srcRatio = $srcW / $srcH;
        if ($srcRatio > $targetRatioWH) {
            $cropH = $srcH;
            $cropW = (int) round($srcH * $targetRatioWH);
        } else {
            $cropW = $srcW;
            $cropH = (int) round($srcW / $targetRatioWH);
        }
        $cropX = (int) (($srcW - $cropW) / 2);
        $cropY = (int) (($srcH - $cropH) / 2);

        $srcImg = match ($info['mime']) {
            'image/png'  => @imagecreatefrompng($srcPath),
            'image/jpeg' => @imagecreatefromjpeg($srcPath),
            'image/gif'  => @imagecreatefromgif($srcPath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($srcPath) : null,
            default      => null,
        };
        if (!$srcImg) {
            return null;
        }

        $dst = imagecreatetruecolor($cropW, $cropH);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagecopy($dst, $srcImg, 0, 0, $cropX, $cropY, $cropW, $cropH);
        imagedestroy($srcImg);

        $tmpPath = tempnam(sys_get_temp_dir(), 'idcard_') . '.jpg';
        imagejpeg($dst, $tmpPath, 90);
        imagedestroy($dst);

        return $tmpPath;
    }

    /**
     * Compact, self-contained verify token (userId + expiry + truncated HMAC, base36/base64url)
     * — deliberately much shorter than a full JWT so the QR code stays at a low version,
     * i.e. fewer/bigger modules, since QR module count is driven by payload length.
     */
    private function makeVerifyToken(int $userId, int $ttlSeconds): string
    {
        $exp = time() + $ttlSeconds;

        return base_convert((string) $userId, 10, 36) . '.' . base_convert((string) $exp, 10, 36) . '.' . $this->verifyTokenSig($userId, $exp);
    }

    /** Returns the verified userId, or null if the token is malformed/tampered/expired. */
    private function parseVerifyToken(string $token): ?int
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$uidPart, $expPart, $sig] = $parts;
        $userId = (int) base_convert($uidPart, 36, 10);
        $exp    = (int) base_convert($expPart, 36, 10);

        if ($userId <= 0 || $exp <= 0 || time() >= $exp) {
            return null;
        }
        if (!hash_equals($this->verifyTokenSig($userId, $exp), $sig)) {
            return null;
        }

        return $userId;
    }

    private function verifyTokenSig(int $userId, int $exp): string
    {
        $secret = env('MOBILE_JWT_SECRET', 'navuli-mobile-secret-change-me-in-production');
        $hash   = hash_hmac('sha256', "$userId.$exp", $secret, true);

        return rtrim(strtr(base64_encode(substr($hash, 0, self::VERIFY_TOKEN_SIG_BYTES)), '+/', '-_'), '=');
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
            return [0, 91, 150];
        }

        return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    }
}
