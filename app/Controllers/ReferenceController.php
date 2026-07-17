<?php
namespace App\Controllers;

class ReferenceController extends BaseController
{
    // Reference category IDs matching your DB
    const REF_CAT_ENROLLMENT         = 1;
    const REF_CAT_CHARACTER          = 2;
    const REF_CAT_RECOMMENDATION     = 3;
    const REF_CAT_TRANSCRIPT         = 4;
    const REF_CAT_CONDUCT            = 5;
    const REF_CAT_CLEARANCE          = 6;
    const REF_CAT_EMPLOYMENT         = 7;
    const REF_CAT_PERFORMANCE        = 8;
    const REF_CAT_PARENT_GUARDIAN    = 9;
    const REF_CAT_PARENT_INVOLVEMENT = 10;
    const REF_CAT_FINANCIAL_CLEARANCE= 11;

    // ── Shared: Check existing + save record ─────────────────────────

    private function checkExisting(int $userId, int $refCatId): ?array
    {
        return $this->generatedReferenceModel
            ->getModernReference($userId, $refCatId);
    }

    private function saveReference(int $userId, int $refCatId, string $filename): void
    {
        // Mark existing as outdated
        $this->generatedReferenceModel->markOutdated($userId, $refCatId);

        // Insert new record
        $this->generatedReferenceModel->insert([
            'ref_cat_id_fk'     => $refCatId,
            'user_id_fk'        => $userId,
            'gen_ref_by'        => $this->session->get('userID'),
            'gen_ref_file_name' => $filename,
            'gen_ref_date'      => date('Y-m-d H:i:s'),
            'gen_ref_time'      => time(),
            'gen_ref_status'    => 'Current',
        ]);
    }

    private function ensureUploadDir(): string
    {
        // Use absolute path for Bluehost
        $dir = FCPATH . 'uploads/reference/';
    
        if (!is_dir($dir)) {
            // Create recursively with correct permissions
            if (!mkdir($dir, 0755, true)) {
                log_message('error', 'Failed to create directory: ' . $dir);
            }
        }
    
        // Verify it's writable
        if (!is_writable($dir)) {
            log_message('error', 'Directory not writable: ' . $dir);
            // Try to fix permissions
            chmod($dir, 0755);
        }
    
        log_message('debug', 'Reference upload dir: ' . $dir . ' | Exists: ' . (is_dir($dir) ? 'YES' : 'NO') . ' | Writable: ' . (is_writable($dir) ? 'YES' : 'NO'));
    
        return $dir;
    }

    private function logReference(string $title, string $desc): void
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
            'log_icon'    => '<i class="ki-duotone ki-document"><span class="path1"></span><span class="path2"></span></i>',
            'log_theme'   => 'info',
        ]);
    }

    private function getStudentData(int $userId): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('enrolment')
            ->select('
                enrolment.*,
                admission.admission_id,
                admission.admission_date,
                admission.admission_status,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.gender,
                users.dob,
                users.address,
                users.email,
                users.phone,
                school.sch_id,
                school.sch_name,
                school.sch_address,
                school.sch_phone,
                school.sch_email,
                school.sch_motto,
                school.sch_logo,
                stream.stream_name,
                level.level_name,
                sch_category.sch_cat_name
            ')
            ->join('admission',    'admission.admission_id    = enrolment.admission_id_fk')
            ->join('users',        'users.user_id             = admission.user_id_fk')
            ->join('school',       'school.sch_id             = admission.sch_id_fk')
            ->join('sch_category', 'sch_category.sch_cat_id  = school.sch_cat_id_fk', 'left')
            ->join('stream',       'stream.stream_id          = enrolment.stream_id_fk', 'left')
            ->join('sch_level',    'sch_level.sch_level_id   = stream.sch_level_id_fk', 'left')
            ->join('level',        'level.level_id            = sch_level.level_id_fk', 'left')
            ->where('users.user_id', $userId)
            ->where('admission.admission_status', 'Active')
            ->where('enrolment.enrol_status', 'Active')
            ->orderBy('enrolment.enrol_id', 'DESC')
            ->get()->getRowArray();
    }

    private function getSchoolData(int $userId): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('admission')
            ->select('school.*, sch_category.sch_cat_name')
            ->join('school',       'school.sch_id           = admission.sch_id_fk')
            ->join('sch_category', 'sch_category.sch_cat_id = school.sch_cat_id_fk', 'left')
            ->where('admission.user_id_fk', $userId)
            ->where('admission.admission_status', 'Active')
            ->get()->getRowArray();
    }

    private function fullName(array $user): string
    {
        return trim(
            $user['fname'] . ' ' .
            (!empty($user['oname']) ? $user['oname'] . ' ' : '') .
            $user['lname']
        );
    }

    // ── Shared PDF header ─────────────────────────────────────────────

    private function buildPdfHeader(\TCPDF $pdf, array $school, int $startX, int $contentW, float &$y): void
    {
        // School logo — left
        $logoPath = FCPATH . 'uploads/school/logo/' . ($school['sch_logo'] ?? '');
        if (!empty($school['sch_logo']) && file_exists($logoPath)) {
            $pdf->Image($logoPath, $startX, $y, 22, 22, '', '', 'T', false, 300);
        }
    
        // Navuli logo — right
        $navuliLogo = FCPATH . 'navuli_icon_small_color.png';
        if (file_exists($navuliLogo)) {
            $pdf->Image($navuliLogo, 172, $y, 18, 18, '', '', 'T', false, 300);
        }
    
        // School name — center
        $pdf->SetXY($startX + 24, $y);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 44, 6, strtoupper($school['sch_name'] ?? 'SCHOOL NAME'), 0, 1, 'C');
    
        // School address
        $pdf->SetXY($startX + 24, $y + 7);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell($contentW - 44, 4, $school['sch_address'] ?? '', 0, 1, 'C');
    
        // Phone + Email
        $contact = '';
        if (!empty($school['sch_phone'])) $contact .= 'Tel: ' . $school['sch_phone'];
        if (!empty($school['sch_phone']) && !empty($school['sch_email'])) $contact .= '   |   ';
        if (!empty($school['sch_email'])) $contact .= 'Email: ' . $school['sch_email'];
    
        $pdf->SetXY($startX + 24, $y + 12);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($contentW - 44, 4, $contact, 0, 1, 'C');
    
        // ── School motto in header (replaces school category) ─────────
        if (!empty($school['sch_motto'])) {
            $pdf->SetXY($startX + 24, $y + 17);
            $pdf->SetFont('helvetica', 'I', 7.5);
            $pdf->SetTextColor(26, 86, 219);
            $pdf->Cell($contentW - 44, 4, '"' . $school['sch_motto'] . '"', 0, 1, 'C');
            $y += 28;
        } else {
            $y += 24;
        }
    
        // Double divider
        $pdf->SetLineStyle(['width' => 0.7, 'color' => [26, 86, 219]]);
        $pdf->Line($startX, $y, $startX + $contentW, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [147, 197, 253]]);
        $pdf->Line($startX, $y, $startX + $contentW, $y);
        $y += 10;
    }

    private function buildPdfFooter(\TCPDF $pdf, int $startX, int $contentW): void
    {
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [200, 200, 200]]);
        $pdf->Line($startX, 273, $startX + $contentW, 273);
        $pdf->SetXY($startX, 274);
        $pdf->SetFont('helvetica', 'I', 6.5);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->Cell($contentW, 4,
            'Generated by Navuli Fiji School Management System on ' . date('d F Y \a\t h:i A') . '.',
            0, 1, 'C');
    }

    private function buildSignatures(\TCPDF $pdf, int $startX, int $sigY, string $leftLabel = 'Class Teacher', string $rightLabel = 'Principal / Head Teacher'): void
    {
        $pdf->SetLineStyle(['width' => 0.4, 'color' => [80, 80, 80]]);
        $pdf->Line($startX + 5, $sigY, $startX + 65, $sigY);
        $pdf->SetXY($startX + 5, $sigY + 1);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell(60, 4, $leftLabel, 0, 1, 'C');
        $pdf->SetXY($startX + 5, $sigY + 5);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(60, 4, 'Name, Signature & Date', 0, 1, 'C');

        $pdf->SetLineStyle(['width' => 0.4, 'color' => [80, 80, 80]]);
        $pdf->Line($startX + 110, $sigY, $startX + 170, $sigY);
        $pdf->SetXY($startX + 110, $sigY + 1);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell(60, 4, $rightLabel, 0, 1, 'C');
        $pdf->SetXY($startX + 110, $sigY + 5);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(60, 4, 'Name, Signature & Date', 0, 1, 'C');
    }
    
    private function buildTwoColumnTable(
        \TCPDF $pdf,
        int    $startX,
        int    $contentW,
        float  &$y,
        array  $rows,
        int    $rowH = 8
    ): void {
        // Column widths — gap prevents right column touching border
        $labelW  = 40;
        $valueW  = 41;
        $gap     = 8; // gap between left and right pairs
        $shade   = false;
    
        foreach ($rows as $row) {
            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [252, 252, 255]));
    
            // Left label
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(90, 90, 90);
            $pdf->Cell($labelW, $rowH, '  ' . ($row[0] ?? ''), 'LTB', 0, 'L', true);
    
            // Left value
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->Cell($valueW, $rowH, ($row[1] ?? ''), 'RTB', 0, 'L', true);
    
            // Gap
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell($gap, $rowH, '', 0, 0, 'C', false);
    
            // Right label
            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [252, 252, 255]));
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(90, 90, 90);
            $pdf->Cell($labelW, $rowH, '  ' . ($row[2] ?? ''), 'LTB', 0, 'L', true);
    
            // Right value — width calculated to not exceed border
            $rightValueW = $contentW - $labelW - $valueW - $gap - $labelW;
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->Cell($rightValueW, $rowH, ($row[3] ?? ''), 'RTB', 1, 'L', true);
    
            $y    += $rowH;
            $shade = !$shade;
        }
    }

    // ================================================================
    // SHARED PDF SETUP
    // ================================================================
    
    private function newPdf(string $title): \TCPDF
    {
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
    
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetAuthor('Navuli Fiji School Management System');
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
    
        // Remove default header and footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    
        // Margins
        $pdf->SetMargins(20, 20, 20);
    
        // ── CRITICAL: false = never create a second page ──────────────
        $pdf->SetAutoPageBreak(false, 0);
        // ────────────────────────────────────────────────────────────────
        
        $pdf->AddPage();
    
        // ── Double border frame ───────────────────────────────────────
        $pdf->SetLineStyle(['width' => 1.0, 'color' => [26, 86, 219]]);
        $pdf->Rect(8, 8, 194, 281, 'D');
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Rect(10, 10, 190, 277, 'D');
        // ────────────────────────────────────────────────────────────────
    
        return $pdf;
    }

    // ================================================================
    // CERTIFICATE OF ENROLLMENT
    // ================================================================

    public function certificateOfEnrollment(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
    
        $user = $this->userModel->find($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');
    
        // Check if student has active enrolment first
        $enrollmentData = $this->getStudentData($userId);
        if (!$enrollmentData) {
            return redirect()->back()->with('error', 'No active enrolment found for this student.');
        }
    
        $existing = $this->checkExisting($userId, self::REF_CAT_ENROLLMENT);
        $this->setPageData('Certificate of Enrollment', 'Reference', 'Certificate of Enrollment');
    
        $data['_view']      = 'app/reference/enrollment_confirm';
        $data['user']       = $user;
        $data['userID']     = $userId;
        $data['existing']   = $existing;
        $data['enrollment'] = $enrollmentData;
        $data['formConfig'] = [
            'title' => 'Certificate of Enrollment',
            'tips'  => [
                'This certificate is generated automatically from the student enrolment records.',
                'Ensure the student has an active enrolment before generating.',
                'Generating a new certificate will mark the previous one as Outdated.',
            ],
        ];
    
        return view('app/layouts/main', $data);
    }

    public function generateEnrollment(int $userId)
    {
        log_message('debug', 'Save path: ' . FCPATH . 'uploads/reference/');
        log_message('debug', 'FCPATH: ' . FCPATH);

        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $data = $this->getStudentData($userId);
        if (!$data) {
            return $this->response->setJSON(['success' => false, 'message' => 'No active enrolment found.']);
        }

        $existing = $this->checkExisting($userId, self::REF_CAT_ENROLLMENT);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Certificate of Enrollment already exists for this student.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($data);
        $filename = 'enrollment_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateEnrollmentPDF($data, $savePath);
        $this->saveReference($userId, self::REF_CAT_ENROLLMENT, $filename);
        $this->logReference('Certificate of Enrollment Generated', 'Certificate of Enrollment generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Certificate of Enrollment generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // CHARACTER REFERENCE
    // ================================================================

    public function characterReference(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_CHARACTER);
        $this->setPageData('Character Reference', 'Reference', 'Character Reference');

        $data['_view']    = 'app/reference/character_reference_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [                          // ← add this
            'title' => 'Character Reference',
            'tips'  => [
                'Select at least 3 character qualities for a meaningful reference.',
                'Signatory name and title are required to produce a valid document.',
                'Leave recipient blank to use "To Whom It May Concern".',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generateCharacterReference(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user     = $this->userModel->findUserFull($userId);
        if (!$user) return $this->response->setJSON(['success' => false, 'message' => 'User not found']);

        $school   = $this->getSchoolData($userId) ?? [];
        $formData = [
            'recipient_name'   => $this->request->getPost('recipient_name')   ?? 'To Whom It May Concern',
            'recipient_title'  => $this->request->getPost('recipient_title')  ?? '',
            'recipient_org'    => $this->request->getPost('recipient_org')    ?? '',
            'purpose'          => $this->request->getPost('purpose')          ?? 'general purposes',
            'known_duration'   => $this->request->getPost('known_duration')   ?? '',
            'relationship'     => $this->request->getPost('relationship')     ?? 'student',
            'qualities'        => $this->request->getPost('qualities')        ?? [],
            'additional_notes' => $this->request->getPost('additional_notes') ?? '',
            'signatory_name'   => $this->request->getPost('signatory_name')   ?? '',
            'signatory_title'  => $this->request->getPost('signatory_title')  ?? 'Principal',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_CHARACTER);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A character reference already exists for this student.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'char_ref_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateCharacterReferencePDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_CHARACTER, $filename);
        $this->logReference('Character Reference Generated', 'Character Reference generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Character Reference generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // RECOMMENDATION LETTER
    // ================================================================

    public function recommendationLetter(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_RECOMMENDATION);
        $this->setPageData('Recommendation Letter', 'Reference', 'Recommendation Letter');

        $data['_view']    = 'app/reference/recommendation_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [
            'title' => 'Recommendation Letter',
            'tips'  => [
                'Select at least 2 academic strengths to make the letter meaningful.',
                'Include specific achievements to strengthen the recommendation.',
                'Signatory name and title are required.',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generateRecommendation(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user   = $this->userModel->findUserFull($userId);
        $school = $this->getSchoolData($userId) ?? [];

        $formData = [
            'recipient_name'      => $this->request->getPost('recipient_name')      ?? 'To Whom It May Concern',
            'recipient_title'     => $this->request->getPost('recipient_title')     ?? '',
            'recipient_org'       => $this->request->getPost('recipient_org')       ?? '',
            'purpose'             => $this->request->getPost('purpose')             ?? '',
            'academic_strengths'  => $this->request->getPost('academic_strengths')  ?? [],
            'achievements'        => $this->request->getPost('achievements')        ?? '',
            'signatory_name'      => $this->request->getPost('signatory_name')      ?? '',
            'signatory_title'     => $this->request->getPost('signatory_title')     ?? 'Principal',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_RECOMMENDATION);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Recommendation Letter already exists for this student.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'recommendation_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateRecommendationPDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_RECOMMENDATION, $filename);
        $this->logReference('Recommendation Letter Generated', 'Recommendation Letter generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Recommendation Letter generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // CONDUCT CERTIFICATE
    // ================================================================

    public function conductCertificate(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_CONDUCT);
        $this->setPageData('Conduct Certificate', 'Reference', 'Conduct Certificate');

        $data['_view']    = 'app/reference/conduct_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [
            'title' => 'Conduct Certificate',
            'tips'  => [
                'Select a conduct rating that accurately reflects the student behaviour.',
                'Select all observed positive behaviours.',
                'Signatory name and title are required.',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generateConduct(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user   = $this->userModel->findUserFull($userId);
        $school = $this->getSchoolData($userId) ?? [];

        $formData = [
            'conduct_rating'   => $this->request->getPost('conduct_rating')   ?? 'Good',
            'behaviours'       => $this->request->getPost('behaviours')       ?? [],
            'incidents'        => $this->request->getPost('incidents')        ?? 'none',
            'additional_notes' => $this->request->getPost('additional_notes') ?? '',
            'signatory_name'   => $this->request->getPost('signatory_name')   ?? '',
            'signatory_title'  => $this->request->getPost('signatory_title')  ?? 'Principal',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_CONDUCT);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Conduct Certificate already exists for this user.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'conduct_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateConductPDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_CONDUCT, $filename);
        $this->logReference('Conduct Certificate Generated', 'Conduct Certificate generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Conduct Certificate generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // CLEARANCE CERTIFICATE
    // ================================================================

    public function clearanceCertificate(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_CLEARANCE);
        $this->setPageData('Clearance Certificate', 'Reference', 'Clearance Certificate');

        $data['_view']    = 'app/reference/clearance_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [
            'title' => 'Clearance Certificate',
            'tips'  => [
                'Tick all items that have been cleared.',
                'Leave outstanding field blank or enter "none" if all cleared.',
                'Signatory name and title are required.',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generateClearance(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user   = $this->userModel->findUserFull($userId);
        $school = $this->getSchoolData($userId) ?? [];

        $formData = [
            'clearance_items'  => $this->request->getPost('clearance_items')  ?? [],
            'outstanding'      => $this->request->getPost('outstanding')      ?? 'none',
            'additional_notes' => $this->request->getPost('additional_notes') ?? '',
            'signatory_name'   => $this->request->getPost('signatory_name')   ?? '',
            'signatory_title'  => $this->request->getPost('signatory_title')  ?? 'Principal',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_CLEARANCE);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Clearance Certificate already exists for this user.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'clearance_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateClearancePDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_CLEARANCE, $filename);
        $this->logReference('Clearance Certificate Generated', 'Clearance Certificate generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Clearance Certificate generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // CERTIFICATE OF EMPLOYMENT (Teacher/Staff)
    // ================================================================

    public function certificateOfEmployment(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_EMPLOYMENT);
        $this->setPageData('Certificate of Employment', 'Reference', 'Certificate of Employment');

        $data['_view']    = 'app/reference/employment_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [
            'title' => 'Certificate of Employment',
            'tips'  => [
                'Position and date commenced are required for a valid certificate.',
                'Employment type must be selected.',
                'Signatory name and title are required.',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generateEmployment(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user   = $this->userModel->findUserFull($userId);
        $school = $this->getSchoolData($userId) ?? [];

        $formData = [
            'position'         => $this->request->getPost('position')         ?? '',
            'department'       => $this->request->getPost('department')       ?? '',
            'date_started'     => $this->request->getPost('date_started')     ?? '',
            'employment_type'  => $this->request->getPost('employment_type')  ?? 'Full-Time',
            'purpose'          => $this->request->getPost('purpose')          ?? 'general purposes',
            'additional_notes' => $this->request->getPost('additional_notes') ?? '',
            'signatory_name'   => $this->request->getPost('signatory_name')   ?? '',
            'signatory_title'  => $this->request->getPost('signatory_title')  ?? 'Principal',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_EMPLOYMENT);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Certificate of Employment already exists for this staff member.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'employment_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateEmploymentPDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_EMPLOYMENT, $filename);
        $this->logReference('Certificate of Employment Generated', 'Certificate of Employment generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Certificate of Employment generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // PERFORMANCE RECOMMENDATION (Teacher/Staff)
    // ================================================================

    public function performanceRecommendation(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_PERFORMANCE);
        $this->setPageData('Performance Recommendation', 'Reference', 'Performance Recommendation');

        $data['_view']    = 'app/reference/performance_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [
            'title' => 'Performance Recommendation',
            'tips'  => [
                'Select a performance rating that accurately reflects the staff member.',
                'Select at least 2 professional strengths.',
                'Include specific achievements or contributions.',
                'Signatory name and title are required.',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generatePerformance(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user   = $this->userModel->findUserFull($userId);
        $school = $this->getSchoolData($userId) ?? [];

        $formData = [
            'recipient_name'   => $this->request->getPost('recipient_name')   ?? 'To Whom It May Concern',
            'recipient_title'  => $this->request->getPost('recipient_title')  ?? '',
            'recipient_org'    => $this->request->getPost('recipient_org')    ?? '',
            'position'         => $this->request->getPost('position')         ?? '',
            'performance_rating' => $this->request->getPost('performance_rating') ?? 'Excellent',
            'strengths'        => $this->request->getPost('strengths')        ?? [],
            'achievements'     => $this->request->getPost('achievements')     ?? '',
            'additional_notes' => $this->request->getPost('additional_notes') ?? '',
            'signatory_name'   => $this->request->getPost('signatory_name')   ?? '',
            'signatory_title'  => $this->request->getPost('signatory_title')  ?? 'Principal',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_PERFORMANCE);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Performance Recommendation already exists for this staff member.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'performance_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generatePerformancePDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_PERFORMANCE, $filename);
        $this->logReference('Performance Recommendation Generated', 'Performance Recommendation generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Performance Recommendation generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // PARENT GUARDIAN CERTIFICATE
    // ================================================================

    public function parentGuardianCertificate(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_PARENT_GUARDIAN);
        $this->setPageData('Parent Guardian Certificate', 'Reference', 'Parent Guardian Certificate');

        $data['_view']    = 'app/reference/parent_guardian_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [
            'title' => 'Parent Guardian Certificate',
            'tips'  => [
                'Enter the full name of the child/student this parent is linked to.',
                'Relationship to child is required.',
                'Purpose of the certificate is required.',
                'Signatory name and title are required.',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generateParentGuardian(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user   = $this->userModel->findUserFull($userId);
        $school = $this->getSchoolData($userId) ?? [];

        $formData = [
            'child_name'       => $this->request->getPost('child_name')       ?? '',
            'relationship'     => $this->request->getPost('relationship')     ?? 'Parent',
            'purpose'          => $this->request->getPost('purpose')          ?? 'general purposes',
            'additional_notes' => $this->request->getPost('additional_notes') ?? '',
            'signatory_name'   => $this->request->getPost('signatory_name')   ?? '',
            'signatory_title'  => $this->request->getPost('signatory_title')  ?? 'Principal',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_PARENT_GUARDIAN);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Parent Guardian Certificate already exists.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'parent_guardian_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateParentGuardianPDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_PARENT_GUARDIAN, $filename);
        $this->logReference('Parent Guardian Certificate Generated', 'Parent Guardian Certificate generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Parent Guardian Certificate generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // PARENT INVOLVEMENT CERTIFICATE
    // ================================================================

    public function parentInvolvementCertificate(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_PARENT_INVOLVEMENT);
        $this->setPageData('Parent Involvement Certificate', 'Reference', 'Parent Involvement Certificate');

        $data['_view']    = 'app/reference/parent_involvement_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [
            'title' => 'Parent Involvement Certificate',
            'tips'  => [
                'Select all areas where the parent has been actively involved.',
                'At least 2 involvement areas must be selected.',
                'Child name and years involved are required.',
                'Signatory name and title are required.',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generateParentInvolvement(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user   = $this->userModel->findUserFull($userId);
        $school = $this->getSchoolData($userId) ?? [];

        $formData = [
            'child_name'         => $this->request->getPost('child_name')         ?? '',
            'involvement_items'  => $this->request->getPost('involvement_items')  ?? [],
            'years_involved'     => $this->request->getPost('years_involved')     ?? '',
            'additional_notes'   => $this->request->getPost('additional_notes')   ?? '',
            'signatory_name'     => $this->request->getPost('signatory_name')     ?? '',
            'signatory_title'    => $this->request->getPost('signatory_title')    ?? 'Principal',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_PARENT_INVOLVEMENT);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Parent Involvement Certificate already exists.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'parent_involvement_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateParentInvolvementPDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_PARENT_INVOLVEMENT, $filename);
        $this->logReference('Parent Involvement Certificate Generated', 'Parent Involvement Certificate generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Parent Involvement Certificate generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // FINANCIAL CLEARANCE
    // ================================================================

    public function financialClearance(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $existing = $this->checkExisting($userId, self::REF_CAT_FINANCIAL_CLEARANCE);
        $this->setPageData('Financial Clearance', 'Reference', 'Financial Clearance');

        $data['_view']    = 'app/reference/financial_clearance_form';
        $data['user']     = $user;
        $data['userID']   = $userId;
        $data['existing'] = $existing;
        $data['formConfig'] = [
            'title' => 'Financial Clearance',
            'tips'  => [
                'Tick all financial items that have been cleared.',
                'At least one item must be cleared.',
                'Bursar or Principal signature is required.',
                'Period covered is required for record accuracy.',
            ],
        ];

        return view('app/layouts/main', $data);
    }

    public function generateFinancialClearance(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $user   = $this->userModel->findUserFull($userId);
        $school = $this->getSchoolData($userId) ?? [];

        $formData = [
            'clearance_items'  => $this->request->getPost('clearance_items')  ?? [],
            'outstanding'      => $this->request->getPost('outstanding')      ?? 'none',
            'period_covered'   => $this->request->getPost('period_covered')   ?? date('Y'),
            'additional_notes' => $this->request->getPost('additional_notes') ?? '',
            'signatory_name'   => $this->request->getPost('signatory_name')   ?? '',
            'signatory_title'  => $this->request->getPost('signatory_title')  ?? 'School Bursar',
        ];

        $existing = $this->checkExisting($userId, self::REF_CAT_FINANCIAL_CLEARANCE);
        $forceNew = $this->request->getPost('force_new') === '1';

        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Financial Clearance already exists.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }

        $fullName = $this->fullName($user);
        $filename = 'financial_clearance_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;

        $this->generateFinancialClearancePDF($user, $school, $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_FINANCIAL_CLEARANCE, $filename);
        $this->logReference('Financial Clearance Generated', 'Financial Clearance generated for ' . $fullName);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Financial Clearance generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }

    // ================================================================
    // VIEW ALL REFERENCES
    // ================================================================

    public function userReferences(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $user = $this->userModel->find($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');

        $this->setPageData('Generated References', 'Reference', 'User References');

        $data['_view']      = 'app/reference/user_references';
        $data['user']       = $user;
        $data['userID']     = $userId;
        $data['references'] = $this->generatedReferenceModel->getUserReferences($userId);

        return view('app/layouts/main', $data);
    }

    public function viewReference(int $genRefId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $ref = $this->generatedReferenceModel->find($genRefId);
        if (!$ref) return redirect()->back()->with('error', 'Reference not found.');

        $filePath = FCPATH . 'uploads/reference/' . $ref['gen_ref_file_name'];
        if (!file_exists($filePath)) return redirect()->back()->with('error', 'File not found.');

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $ref['gen_ref_file_name'] . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    // ================================================================
    // PDF GENERATORS
    // ================================================================

    private function generateEnrollmentPDF(array $d, string $savePath): void
    {
        $pdf      = $this->newPdf('Certificate of Enrollment');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;
    
        // Header already includes motto via buildPdfHeader
        $this->buildPdfHeader($pdf, $d, $startX, $contentW, $y);
    
        // ── Certificate title ─────────────────────────────────────────
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'CERTIFICATE OF ENROLLMENT', 0, 1, 'C');
        $y += 9;
    
        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 40, $y, $cx + 40, $y);
        $y += 7;
    
        // ── Ref + Date ────────────────────────────────────────────────
        $refNo = 'CE-' . str_pad($d['admission_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date Issued: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;
    
        // ── Intro paragraph — LEFT aligned ────────────────────────────
        $pdf->SetXY($startX + 8, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->MultiCell($contentW - 16, 5.5,
            'This is to certify that the following student is duly enrolled and in good standing at ' .
            ($d['sch_name'] ?? 'this institution') . ' for the Academic Year ' . ($d['enrol_year'] ?? date('Y')) . '.',
            0, 'C', false, 1
        );
        $y = $pdf->GetY() + 5;
    
        // ── Student name banner ───────────────────────────────────────
        $fullName = $this->fullName($d);
    
        $pdf->SetFillColor(239, 246, 255);
        $pdf->SetDrawColor(147, 197, 253);
        $pdf->RoundedRect($startX + 10, $y, $contentW - 20, 18, 3, '1111', 'DF');
        $pdf->SetXY($startX + 10, $y + 2);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(100, 130, 200);
        $pdf->Cell($contentW - 20, 4, 'STUDENT FULL NAME', 0, 1, 'C');
        $pdf->SetXY($startX + 10, $y + 6);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 20, 11, strtoupper($fullName), 0, 1, 'C');
        $y += 24;
    
        // ── Section label ─────────────────────────────────────────────
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 5, 'ENROLLMENT DETAILS', 0, 1, 'L');
        $y += 1;
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Line($startX, $y, $startX + $contentW, $y);
        $y += 5;
    
        // ── Two column table using shared helper ──────────────────────
        $this->buildTwoColumnTable($pdf, $startX, $contentW, $y, [
            ['Gender',           ucfirst($d['gender'] ?? 'N/A'),
             'Academic Year',    $d['enrol_year']   ?? 'N/A'],
    
            ['Date of Birth',    !empty($d['dob'])
                                    ? date('d F Y', strtotime($d['dob']))
                                    : 'N/A',
             'Term',             'Term ' . ($d['enrol_term'] ?? 'N/A')],
    
            ['Year Level',       $d['level_name']   ?? 'N/A',
             'Stream / Class',   $d['stream_name']  ?? 'N/A'],
    
            ['Admission Date',   !empty($d['admission_date'])
                                    ? date('d F Y', strtotime($d['admission_date']))
                                    : 'N/A',
             'Admission Status', $d['admission_status'] ?? 'N/A'],
    
            ['Enrolment Status', $d['enrol_status']  ?? 'N/A',
             'School Type',      $d['sch_cat_name']  ?? 'N/A'],
        ]);
    
        $y += 8;
    
        // ── Closing statement — LEFT aligned ─────────────────────────
        $pdf->SetXY($startX + 8, $y);
        $pdf->SetFont('helvetica', 'I', 9);
        $pdf->SetTextColor(70, 70, 70);
        $pdf->MultiCell($contentW - 16, 5,
            'This certificate is issued upon request for official purposes. ' .
            'The student named herein is recognized as a duly enrolled member of this institution.',
            0, 'L', false, 1  // ← L = left
        );
    
        // ── Signatures pinned ─────────────────────────────────────────
        $this->buildSignatures($pdf, $startX, 242);
    
        // ── Footer pinned ─────────────────────────────────────────────
        $this->buildPdfFooter($pdf, $startX, $contentW);
    
        $pdf->Output($savePath, 'F');
    }

    private function generateCharacterReferencePDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Character Reference');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;
    
        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);
    
        // ── Title ─────────────────────────────────────────────────────
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'CHARACTER REFERENCE', 0, 1, 'C');
        $y += 9;
    
        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 35, $y, $cx + 35, $y);
        $y += 7;
    
        // ── Ref + Date ────────────────────────────────────────────────
        $refNo = 'CR-' . str_pad($user['user_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;
    
        // ── Recipient ─────────────────────────────────────────────────
        $recipient = trim(($form['recipient_title'] ? $form['recipient_title'] . ' ' : '') . $form['recipient_name']);
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->Cell($contentW, 5, $recipient ?: 'To Whom It May Concern', 0, 1, 'L');
        $y += 5;
    
        if (!empty($form['recipient_org'])) {
            $pdf->SetXY($startX, $y);
            $pdf->Cell($contentW, 5, $form['recipient_org'], 0, 1, 'L');
            $y += 5;
        }
        $y += 3;
    
        // ── Salutation ────────────────────────────────────────────────
        $salutation = (!empty($form['recipient_name']) && $form['recipient_name'] !== 'To Whom It May Concern')
            ? 'Dear ' . $form['recipient_name'] . ','
            : 'To Whom It May Concern,';
    
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 9.5);
        $pdf->Cell($contentW, 5, $salutation, 0, 1, 'L');
        $y += 8;
    
        // ── Body ──────────────────────────────────────────────────────
        $fullName = $this->fullName($user);
        $gender   = strtolower($user['gender'] ?? '');
        $pronoun  = ($gender === 'female') ? 'she' : (($gender === 'male') ? 'he' : 'they');
        $pronoun2 = ($gender === 'female') ? 'her' : (($gender === 'male') ? 'his' : 'their');
    
        $duration = !empty($form['known_duration'])
            ? 'for ' . $form['known_duration']
            : 'during ' . $pronoun2 . ' time at this institution';
    
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->MultiCell($contentW, 6,
            'I am pleased to write this character reference for ' . $fullName .
            ', who has been known to me as a ' . $form['relationship'] . ' ' . $duration . '. ' .
            'This reference is provided for ' . ($form['purpose'] ?: 'general purposes') . '.',
            0, 'J', false, 1);
        $y = $pdf->GetY() + 5;
    
        // ── Qualities paragraph ───────────────────────────────────────
        if (!empty($form['qualities'])) {
            $qualityList = is_array($form['qualities'])
                ? implode(', ', $form['qualities'])
                : $form['qualities'];
    
            $pdf->SetXY($startX, $y);
            $pdf->MultiCell($contentW, 6,
                'Throughout my observation, ' . $fullName . ' has consistently demonstrated the following qualities: ' .
                $qualityList . '. ' .
                ucfirst($pronoun) . ' has shown a strong commitment to ' . $pronoun2 .
                ' responsibilities and has been a positive contributor to the school community.',
                0, 'J', false, 1);
            $y = $pdf->GetY() + 5;
        }
    
        // ── Additional notes ──────────────────────────────────────────
        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->MultiCell($contentW, 6, $form['additional_notes'], 0, 'J', false, 1);
            $y = $pdf->GetY() + 5;
        }
    
        // ── Closing paragraph ─────────────────────────────────────────
        $pdf->SetXY($startX, $y);
        $pdf->MultiCell($contentW, 6,
            'I have no hesitation in recommending ' . $fullName . ' and believe ' .
            $pronoun . ' will be a valuable asset in any endeavour ' . $pronoun . ' undertakes. ' .
            'Should you require any further information, please do not hesitate to contact me.',
            0, 'J', false, 1);
        $y = $pdf->GetY() + 6;
    
        // ── Sign off ──────────────────────────────────────────────────
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->Cell($contentW, 5, 'Yours sincerely,', 0, 1, 'L');
        $y += 14;
    
        // ── Signature line ────────────────────────────────────────────
        $pdf->SetLineStyle(['width' => 0.4, 'color' => [80, 80, 80]]);
        $pdf->Line($startX, $y, $startX + 70, $y);
        $y += 2;
    
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->Cell($contentW, 5,
            !empty($form['signatory_name']) ? $form['signatory_name'] : '________________________________',
            0, 1, 'L');
        $y += 5;
    
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 8.5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell($contentW, 5, $form['signatory_title'], 0, 1, 'L');
        $y += 4;
    
        $pdf->SetXY($startX, $y);
        $pdf->Cell($contentW, 5, $school['sch_name'] ?? '', 0, 1, 'L');
        $y += 4;
    
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($contentW, 5, 'Date: ' . date('d F Y'), 0, 1, 'L');
    
        // ── Footer pinned to Y=271 ────────────────────────────────────
        $this->buildPdfFooter($pdf, $startX, $contentW);
    
        // ── Save to disk ──────────────────────────────────────────────
        $pdf->Output($savePath, 'F');
    }

    private function generateRecommendationPDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Recommendation Letter');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;

        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'RECOMMENDATION LETTER', 0, 1, 'C');
        $y += 9;

        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 35, $y, $cx + 35, $y);
        $y += 8;

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Date: ' . date('d F Y'), 0, 1, 'L');
        $y += 9;

        // Recipient
        $recipient = trim(($form['recipient_title'] ? $form['recipient_title'] . ' ' : '') . $form['recipient_name']);
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->Cell($contentW, 5, $recipient ?: 'To Whom It May Concern', 0, 1, 'L');
        $y += 5;

        if (!empty($form['recipient_org'])) {
            $pdf->SetXY($startX, $y);
            $pdf->Cell($contentW, 5, $form['recipient_org'], 0, 1, 'L');
            $y += 5;
        }
        $y += 3;

        $salutation = ($form['recipient_name'] && $form['recipient_name'] !== 'To Whom It May Concern')
            ? 'Dear ' . $form['recipient_name'] . ','
            : 'To Whom It May Concern,';

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 9.5);
        $pdf->Cell($contentW, 5, $salutation, 0, 1, 'L');
        $y += 8;

        $fullName = $this->fullName($user);
        $gender   = strtolower($user['gender'] ?? '');
        $pronoun  = ($gender === 'female') ? 'she' : 'he';
        $pronoun2 = ($gender === 'female') ? 'her' : 'his';

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->MultiCell($contentW, 6,
            'It is with great pleasure and without reservation that I recommend ' . $fullName .
            ' for ' . ($form['purpose'] ?: 'further opportunities') . '. ' .
            'I have had the privilege of knowing ' . $fullName . ' as a student at ' . ($school['sch_name'] ?? 'our institution') . '.',
            0, 'J', false, 1);
        $y = $pdf->GetY() + 5;

        if (!empty($form['academic_strengths'])) {
            $strengthList = is_array($form['academic_strengths'])
                ? implode(', ', $form['academic_strengths'])
                : $form['academic_strengths'];

            $pdf->SetXY($startX, $y);
            $pdf->MultiCell($contentW, 6,
                ucfirst($pronoun) . ' has demonstrated outstanding ability in the following areas: ' . $strengthList . '. ' .
                'These strengths have been consistently evident throughout ' . $pronoun2 . ' academic journey.',
                0, 'J', false, 1);
            $y = $pdf->GetY() + 5;
        }

        if (!empty($form['achievements'])) {
            $pdf->SetXY($startX, $y);
            $pdf->MultiCell($contentW, 6, $form['achievements'], 0, 'J', false, 1);
            $y = $pdf->GetY() + 5;
        }

        $pdf->SetXY($startX, $y);
        $pdf->MultiCell($contentW, 6,
            'I am confident that ' . $fullName . ' will bring the same level of dedication, integrity, and excellence to any future endeavour. ' .
            'I recommend ' . $pronoun . ' with the highest confidence.',
            0, 'J', false, 1);
        $y = $pdf->GetY() + 6;

        $pdf->SetXY($startX, $y);
        $pdf->Cell($contentW, 5, 'Yours sincerely,', 0, 1, 'L');
        $y += 14;

        $pdf->SetLineStyle(['width' => 0.4, 'color' => [80, 80, 80]]);
        $pdf->Line($startX, $y, $startX + 70, $y);
        $y += 2;

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($contentW, 5, $form['signatory_name'] ?: '________________________________', 0, 1, 'L');
        $y += 5;

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 8.5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell($contentW, 5, $form['signatory_title'], 0, 1, 'L');
        $y += 4;

        $pdf->SetXY($startX, $y);
        $pdf->Cell($contentW, 5, $school['sch_name'] ?? '', 0, 1, 'L');

        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }

    private function generateConductPDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Conduct Certificate');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;

        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'CONDUCT CERTIFICATE', 0, 1, 'C');
        $y += 9;

        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 35, $y, $cx + 35, $y);
        $y += 8;

        $refNo = 'CC-' . str_pad($user['user_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;

        // Intro
        $fullName = $this->fullName($user);
        $pdf->SetXY($startX + 10, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->MultiCell($contentW - 20, 6,
            'This is to certify that ' . $fullName . ' has demonstrated conduct of a ' .
            strtoupper($form['conduct_rating']) . ' standard during ' .
            (!empty($user['gender']) && strtolower($user['gender']) === 'female' ? 'her' : 'his') .
            ' time at ' . ($school['sch_name'] ?? 'this institution') . '.',
            0, 'C', false, 1);
        $y = $pdf->GetY() + 8;

        // Student Name Banner
        $pdf->SetFillColor(239, 246, 255);
        $pdf->SetDrawColor(147, 197, 253);
        $pdf->RoundedRect($startX + 10, $y, $contentW - 20, 18, 3, '1111', 'DF');
        $pdf->SetXY($startX + 10, $y + 2);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(100, 130, 200);
        $pdf->Cell($contentW - 20, 4, 'STUDENT / STAFF NAME', 0, 1, 'C');
        $pdf->SetXY($startX + 10, $y + 6);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 20, 11, strtoupper($fullName), 0, 1, 'C');
        $y += 24;

        // Conduct Rating Box
        $ratingColors = [
            'Excellent'      => [16, 185, 129],
            'Very Good'      => [59, 130, 246],
            'Good'           => [16, 185, 129],
            'Satisfactory'   => [245, 158, 11],
            'Needs Improvement' => [239, 68, 68],
        ];
        $ratingColor = $ratingColors[$form['conduct_rating']] ?? [16, 185, 129];

        $pdf->SetFillColor(...$ratingColor);
        $pdf->SetXY($startX + 50, $y);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->RoundedRect($startX + 50, $y, 70, 12, 3, '1111', 'F');
        $pdf->SetXY($startX + 50, $y + 1);
        $pdf->Cell(70, 10, 'CONDUCT: ' . strtoupper($form['conduct_rating']), 0, 1, 'C');
        $y += 18;

        // ── Behaviours — replace unicode tick with [x] ────────────────
        if (!empty($form['behaviours'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(26, 86, 219);
            $pdf->Cell($contentW, 5, 'OBSERVED BEHAVIOURS', 0, 1, 'L');
            $y += 1;
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
            $pdf->Line($startX, $y, $startX + $contentW, $y);
            $y += 5;
        
            $behaviours = is_array($form['behaviours']) ? $form['behaviours'] : [$form['behaviours']];
            $col = 0;
            foreach ($behaviours as $b) {
                $xPos = $col === 0 ? $startX : $startX + 88;
                $pdf->SetTextColor(40, 40, 40);
                // ── Unicode tick via dejavusans ───────────────────────────
                $this->writeTickItem($pdf, $xPos, $y, ucfirst($b), 85, 6);
                if ($col === 1) $y += 6;
                $col = $col === 0 ? 1 : 0;
            }
            if ($col === 1) $y += 6;
            $y += 5;
        }
        
        // ── Incidents — LEFT aligned ──────────────────────────────────
        if (!empty($form['incidents']) && strtolower($form['incidents']) !== 'none') {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'I', 8.5);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->MultiCell($contentW, 5, 'Incidents noted: ' . $form['incidents'], 0, 'L', false, 1);
            $y = $pdf->GetY() + 3;
        }
        
        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->MultiCell($contentW, 5, $form['additional_notes'], 0, 'L', false, 1);
            $y = $pdf->GetY() + 3;
        }

        $this->buildSignatures($pdf, $startX, 242);
        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }

    private function generateClearancePDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Clearance Certificate');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;

        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'CLEARANCE CERTIFICATE', 0, 1, 'C');
        $y += 9;

        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 35, $y, $cx + 35, $y);
        $y += 8;

        $refNo = 'CLR-' . str_pad($user['user_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;

        $fullName = $this->fullName($user);

        $pdf->SetXY($startX + 10, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->MultiCell($contentW - 20, 6,
            'This is to certify that ' . $fullName . ' has fulfilled all obligations and requirements at ' .
            ($school['sch_name'] ?? 'this institution') . ' and is hereby CLEARED.',
            0, 'C', false, 1);
        $y = $pdf->GetY() + 8;

        // Name Banner
        $pdf->SetFillColor(239, 246, 255);
        $pdf->SetDrawColor(147, 197, 253);
        $pdf->RoundedRect($startX + 10, $y, $contentW - 20, 18, 3, '1111', 'DF');
        $pdf->SetXY($startX + 10, $y + 2);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(100, 130, 200);
        $pdf->Cell($contentW - 20, 4, 'NAME', 0, 1, 'C');
        $pdf->SetXY($startX + 10, $y + 6);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 20, 11, strtoupper($fullName), 0, 1, 'C');
        $y += 24;

        // Clearance checklist
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 5, 'CLEARANCE CHECKLIST', 0, 1, 'L');
        $y += 1;
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Line($startX, $y, $startX + $contentW, $y);
        $y += 5;

        $items   = is_array($form['clearance_items']) ? $form['clearance_items'] : [];
        $shade   = false;
        $rowH    = 8;

        $allItems = [
            'Library Books Returned',
            'School Fees Cleared',
            'School Property Returned',
            'Uniform Returned',
            'ID Card Returned',
            'Documents Collected',
        ];

        foreach ($allItems as $item) {
            $cleared = in_array($item, $items);
            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [252, 252, 255]));
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell($contentW - 30, $rowH, '  ' . $item, 'LTB', 0, 'L', true);
            // ── Unicode tick/cross via dejavusans ─────────────────────────
            $this->writeStatusCell($pdf, 30, $rowH, $cleared, 'RTB');
            $y    += $rowH;
            $shade = !$shade;
        }

        $y += 8;

        if (!empty($form['outstanding']) && $form['outstanding'] !== 'none') {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'I', 8.5);
            $pdf->SetTextColor(239, 68, 68);
            $pdf->MultiCell($contentW, 5, 'Outstanding matters: ' . $form['outstanding'], 0, 'L', false, 1);
            $y = $pdf->GetY() + 3;
        }

        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->MultiCell($contentW, 5, $form['additional_notes'], 0, 'J', false, 1);
        }

        $this->buildSignatures($pdf, $startX, 242);
        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }

    private function generateEmploymentPDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Certificate of Employment');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;

        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'CERTIFICATE OF EMPLOYMENT', 0, 1, 'C');
        $y += 9;

        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 35, $y, $cx + 35, $y);
        $y += 8;

        $refNo = 'EMP-' . str_pad($user['user_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;

        $fullName = $this->fullName($user);

        $pdf->SetXY($startX + 10, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->MultiCell($contentW - 20, 6,
            'This is to certify that ' . $fullName . ' is currently employed at ' .
            ($school['sch_name'] ?? 'this institution') . ' for the purpose of ' . $form['purpose'] . '.',
            0, 'C', false, 1);
        $y = $pdf->GetY() + 8;

        // Name Banner
        $pdf->SetFillColor(239, 246, 255);
        $pdf->SetDrawColor(147, 197, 253);
        $pdf->RoundedRect($startX + 10, $y, $contentW - 20, 18, 3, '1111', 'DF');
        $pdf->SetXY($startX + 10, $y + 2);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(100, 130, 200);
        $pdf->Cell($contentW - 20, 4, 'EMPLOYEE NAME', 0, 1, 'C');
        $pdf->SetXY($startX + 10, $y + 6);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 20, 11, strtoupper($fullName), 0, 1, 'C');
        $y += 24;

        // Employment details table
        $details = [
            ['Position / Role',   $form['position']        ?? 'N/A'],
            ['Department',        $form['department']      ?? 'N/A'],
            ['Employment Type',   $form['employment_type'] ?? 'N/A'],
            ['Date Commenced',    !empty($form['date_started']) ? date('d F Y', strtotime($form['date_started'])) : 'N/A'],
            ['Institution',       $school['sch_name']      ?? 'N/A'],
        ];

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 5, 'EMPLOYMENT DETAILS', 0, 1, 'L');
        $y += 1;
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Line($startX, $y, $startX + $contentW, $y);
        $y += 5;

        $shade = false;
        foreach ($details as $row) {
            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [252, 252, 255]));
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'B', 8.5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell(60, 8, '  ' . $row[0], 'LTB', 0, 'L', true);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->Cell(110, 8, $row[1], 'RTB', 1, 'L', true);
            $y    += 8;
            $shade = !$shade;
        }

        $y += 8;

        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'I', 9);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->MultiCell($contentW, 5, $form['additional_notes'], 0, 'J', false, 1);
        }

        $this->buildSignatures($pdf, $startX, 242, 'HR Officer / Bursar', 'Principal / Head Teacher');
        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }

    private function generatePerformancePDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Performance Recommendation');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;

        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'PERFORMANCE RECOMMENDATION', 0, 1, 'C');
        $y += 9;

        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 40, $y, $cx + 40, $y);
        $y += 8;

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW, 4, 'Date: ' . date('d F Y'), 0, 1, 'L');
        $y += 9;

        $fullName = $this->fullName($user);
        $gender   = strtolower($user['gender'] ?? '');
        $pronoun  = ($gender === 'female') ? 'she' : 'he';
        $pronoun2 = ($gender === 'female') ? 'her' : 'his';

        // Recipient
        $recipient = trim(($form['recipient_title'] ? $form['recipient_title'] . ' ' : '') . $form['recipient_name']);
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(30, 30, 30);
        $pdf->Cell($contentW, 5, $recipient ?: 'To Whom It May Concern', 0, 1, 'L');
        $y += 5;

        if (!empty($form['recipient_org'])) {
            $pdf->SetXY($startX, $y);
            $pdf->Cell($contentW, 5, $form['recipient_org'], 0, 1, 'L');
            $y += 5;
        }
        $y += 3;

        $salutation = ($form['recipient_name'] && $form['recipient_name'] !== 'To Whom It May Concern')
            ? 'Dear ' . $form['recipient_name'] . ','
            : 'To Whom It May Concern,';

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 9.5);
        $pdf->Cell($contentW, 5, $salutation, 0, 1, 'L');
        $y += 8;

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->MultiCell($contentW, 6,
            'I am writing to highly recommend ' . $fullName . ' who serves as ' . ($form['position'] ?: 'a staff member') .
            ' at ' . ($school['sch_name'] ?? 'our institution') . '. ' .
            'Based on my observation and assessment, ' . $pronoun . ' has demonstrated a performance rating of ' .
            strtoupper($form['performance_rating']) . '.',
            0, 'J', false, 1);
        $y = $pdf->GetY() + 5;

        if (!empty($form['strengths'])) {
            $strengthList = is_array($form['strengths']) ? implode(', ', $form['strengths']) : $form['strengths'];
            $pdf->SetXY($startX, $y);
            $pdf->MultiCell($contentW, 6,
                ucfirst($pronoun) . ' has consistently excelled in: ' . $strengthList . '. ' .
                'These qualities make ' . $pronoun . ' an exemplary member of our team.',
                0, 'J', false, 1);
            $y = $pdf->GetY() + 5;
        }

        if (!empty($form['achievements'])) {
            $pdf->SetXY($startX, $y);
            $pdf->MultiCell($contentW, 6, $form['achievements'], 0, 'J', false, 1);
            $y = $pdf->GetY() + 5;
        }

        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->MultiCell($contentW, 6, $form['additional_notes'], 0, 'J', false, 1);
            $y = $pdf->GetY() + 5;
        }

        $pdf->SetXY($startX, $y);
        $pdf->MultiCell($contentW, 6,
            'I recommend ' . $fullName . ' without reservation and am confident ' .
            $pronoun . ' will continue to excel in any capacity ' . $pronoun . ' undertakes.',
            0, 'J', false, 1);
        $y = $pdf->GetY() + 6;

        $pdf->SetXY($startX, $y);
        $pdf->Cell($contentW, 5, 'Yours sincerely,', 0, 1, 'L');
        $y += 14;

        $pdf->SetLineStyle(['width' => 0.4, 'color' => [80, 80, 80]]);
        $pdf->Line($startX, $y, $startX + 70, $y);
        $y += 2;

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($contentW, 5, $form['signatory_name'] ?: '________________________________', 0, 1, 'L');
        $y += 5;

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 8.5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell($contentW, 5, $form['signatory_title'], 0, 1, 'L');
        $y += 4;

        $pdf->SetXY($startX, $y);
        $pdf->Cell($contentW, 5, $school['sch_name'] ?? '', 0, 1, 'L');

        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }

    private function generateParentGuardianPDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Parent Guardian Certificate');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;

        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'PARENT / GUARDIAN CERTIFICATE', 0, 1, 'C');
        $y += 9;

        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 40, $y, $cx + 40, $y);
        $y += 8;

        $refNo = 'PGC-' . str_pad($user['user_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;

        $fullName = $this->fullName($user);

        $pdf->SetXY($startX + 10, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->MultiCell($contentW - 20, 6,
            'This is to certify that ' . $fullName . ' is a recognized ' . $form['relationship'] .
            ' of student ' . ($form['child_name'] ?: '[Student Name]') .
            ' enrolled at ' . ($school['sch_name'] ?? 'this institution') . '.',
            0, 'C', false, 1);
        $y = $pdf->GetY() + 8;

        // Name Banner
        $pdf->SetFillColor(239, 246, 255);
        $pdf->SetDrawColor(147, 197, 253);
        $pdf->RoundedRect($startX + 10, $y, $contentW - 20, 18, 3, '1111', 'DF');
        $pdf->SetXY($startX + 10, $y + 2);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(100, 130, 200);
        $pdf->Cell($contentW - 20, 4, 'PARENT / GUARDIAN NAME', 0, 1, 'C');
        $pdf->SetXY($startX + 10, $y + 6);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 20, 11, strtoupper($fullName), 0, 1, 'C');
        $y += 24;

        // Details
        $details = [
            ['Relationship',   $form['relationship']],
            ['Student Name',   $form['child_name'] ?: 'N/A'],
            ['Institution',    $school['sch_name'] ?? 'N/A'],
            ['Purpose',        $form['purpose']],
        ];

        $shade = false;
        foreach ($details as $row) {
            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [252, 252, 255]));
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'B', 8.5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell(55, 8, '  ' . $row[0], 'LTB', 0, 'L', true);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->Cell(115, 8, $row[1], 'RTB', 1, 'L', true);
            $y    += 8;
            $shade = !$shade;
        }

        $y += 8;

        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'I', 9);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->MultiCell($contentW, 5, $form['additional_notes'], 0, 'J', false, 1);
        }

        $this->buildSignatures($pdf, $startX, 242);
        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }

    private function generateParentInvolvementPDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Parent Involvement Certificate');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;

        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'PARENT INVOLVEMENT CERTIFICATE', 0, 1, 'C');
        $y += 9;

        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 40, $y, $cx + 40, $y);
        $y += 8;

        $refNo = 'PIC-' . str_pad($user['user_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;

        $fullName = $this->fullName($user);

        $pdf->SetXY($startX + 10, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(50, 50, 50);
        $period = !empty($form['years_involved']) ? 'for ' . $form['years_involved'] : '';
        $pdf->MultiCell($contentW - 20, 6,
            'This is to certify that ' . $fullName . ' has been an active and dedicated parent/guardian ' .
            $period . ' at ' . ($school['sch_name'] ?? 'this institution') . ', ' .
            'positively contributing to the school community and the education of ' .
            ($form['child_name'] ?: 'their child') . '.',
            0, 'C', false, 1);
        $y = $pdf->GetY() + 8;

        // Name Banner
        $pdf->SetFillColor(239, 246, 255);
        $pdf->SetDrawColor(147, 197, 253);
        $pdf->RoundedRect($startX + 10, $y, $contentW - 20, 18, 3, '1111', 'DF');
        $pdf->SetXY($startX + 10, $y + 2);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(100, 130, 200);
        $pdf->Cell($contentW - 20, 4, 'PARENT / GUARDIAN NAME', 0, 1, 'C');
        $pdf->SetXY($startX + 10, $y + 6);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 20, 11, strtoupper($fullName), 0, 1, 'C');
        $y += 24;

        // Involvement items
        if (!empty($form['involvement_items'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(26, 86, 219);
            $pdf->Cell($contentW, 5, 'AREAS OF INVOLVEMENT', 0, 1, 'L');
            $y += 1;
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
            $pdf->Line($startX, $y, $startX + $contentW, $y);
            $y += 5;

            $items = is_array($form['involvement_items']) ? $form['involvement_items'] : [$form['involvement_items']];
            $col   = 0;
            foreach ($items as $item) {
                $xPos = $col === 0 ? $startX : $startX + 88;
                $pdf->SetTextColor(40, 40, 40);
                // ── Unicode tick via dejavusans ───────────────────────────────
                $this->writeTickItem($pdf, $xPos, $y, ucfirst($item), 85, 6);
                if ($col === 1) $y += 6;
                $col = $col === 0 ? 1 : 0;
            }
            if ($col === 1) $y += 6;
            $y += 5;
        }

        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'I', 9);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->MultiCell($contentW, 5, $form['additional_notes'], 0, 'J', false, 1);
        }

        $this->buildSignatures($pdf, $startX, 242);
        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }

    private function generateFinancialClearancePDF(array $user, array $school, array $form, string $savePath): void
    {
        $pdf      = $this->newPdf('Financial Clearance');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;

        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);

        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'FINANCIAL CLEARANCE', 0, 1, 'C');
        $y += 9;

        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 35, $y, $cx + 35, $y);
        $y += 8;

        $refNo = 'FIN-' . str_pad($user['user_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;

        $fullName = $this->fullName($user);

        $pdf->SetXY($startX + 10, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->MultiCell($contentW - 20, 6,
            'This is to certify that ' . $fullName . ' has settled all financial obligations at ' .
            ($school['sch_name'] ?? 'this institution') . ' for the period of ' . $form['period_covered'] . ' and is hereby financially CLEARED.',
            0, 'C', false, 1);
        $y = $pdf->GetY() + 8;

        // Name Banner
        $pdf->SetFillColor(239, 246, 255);
        $pdf->SetDrawColor(147, 197, 253);
        $pdf->RoundedRect($startX + 10, $y, $contentW - 20, 18, 3, '1111', 'DF');
        $pdf->SetXY($startX + 10, $y + 2);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(100, 130, 200);
        $pdf->Cell($contentW - 20, 4, 'NAME', 0, 1, 'C');
        $pdf->SetXY($startX + 10, $y + 6);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 20, 11, strtoupper($fullName), 0, 1, 'C');
        $y += 24;

        // Financial checklist
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 5, 'FINANCIAL CLEARANCE ITEMS', 0, 1, 'L');
        $y += 1;
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Line($startX, $y, $startX + $contentW, $y);
        $y += 5;

        $allItems = [
            'School Fees',
            'Canteen / Tuck Shop',
            'Excursion Fees',
            'Library Fines',
            'Sports Fees',
            'Uniform Fees',
        ];
        $cleared = is_array($form['clearance_items']) ? $form['clearance_items'] : [];
        $shade   = false;

        foreach ($allItems as $item) {
            $isCleared = in_array($item, $cleared);
            $pdf->SetFillColor(...($shade ? [239, 246, 255] : [252, 252, 255]));
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->Cell($contentW - 30, 8, '  ' . $item, 'LTB', 0, 'L', true);
            // ── Unicode tick/cross via dejavusans ─────────────────────────
            $this->writeStatusCell($pdf, 30, 8, $isCleared, 'RTB');
            $y    += 8;
            $shade = !$shade;
        }

        $y += 8;

        if (!empty($form['outstanding']) && $form['outstanding'] !== 'none') {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'BI', 8.5);
            $pdf->SetTextColor(239, 68, 68);
            $pdf->MultiCell($contentW, 5, 'Outstanding: ' . $form['outstanding'], 0, 'L', false, 1);
            $y = $pdf->GetY() + 3;
        }

        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->MultiCell($contentW, 5, $form['additional_notes'], 0, 'J', false, 1);
        }

        $this->buildSignatures($pdf, $startX, 242, 'School Bursar', 'Principal / Head Teacher');
        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }
    
    public function deleteReference(int $genRefId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        try {
            $ref = $this->generatedReferenceModel->find($genRefId);
    
            if (!$ref) {
                return $this->response->setJSON(['success' => false, 'message' => 'Reference not found.']);
            }
    
            // Delete file from disk
            $filePath = FCPATH . 'uploads/reference/' . $ref['gen_ref_file_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
    
            // Delete from DB
            $this->generatedReferenceModel->delete($genRefId);
    
            // Log
            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Reference Deleted',
                'log_desc'    => 'Deleted reference: ' . $ref['gen_ref_file_name'] . ' (ID: ' . $genRefId . ')',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'danger',
            ]);
    
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reference deleted successfully.',
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[ReferenceController::deleteReference] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }
    
    // ================================================================
    // TRANSCRIPT REQUEST
    // ================================================================
    
    public function transcriptRequest(int $userId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
    
        $user = $this->userModel->findUserFull($userId);
        if (!$user) return redirect()->back()->with('error', 'User not found.');
    
        $enrollmentData = $this->getStudentData($userId);
    
        $existing = $this->checkExisting($userId, self::REF_CAT_TRANSCRIPT);
        $this->setPageData('Transcript Request', 'Reference', 'Transcript Request');
    
        $data['_view']      = 'app/reference/transcript_form';
        $data['user']       = $user;
        $data['userID']     = $userId;
        $data['existing']   = $existing;
        $data['enrollment'] = $enrollmentData;
        $data['formConfig'] = [
            'title' => 'Transcript Request',
            'tips'  => [
                'Select the academic years to be included in the transcript.',
                'Specify the purpose and recipient of the transcript.',
                'Signatory name and title are required.',
                'Transcripts are official academic records — ensure details are accurate.',
            ],
        ];
    
        return view('app/layouts/main', $data);
    }
    
    public function generateTranscript(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
    
        $user = $this->userModel->findUserFull($userId);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found.']);
        }
    
        $enrollment = $this->getStudentData($userId);
        $school     = $this->getSchoolData($userId) ?? [];
    
        $formData = [
            'recipient_name'    => $this->request->getPost('recipient_name')    ?? 'To Whom It May Concern',
            'recipient_title'   => $this->request->getPost('recipient_title')   ?? '',
            'recipient_org'     => $this->request->getPost('recipient_org')     ?? '',
            'purpose'           => $this->request->getPost('purpose')           ?? '',
            'years_included'    => $this->request->getPost('years_included')    ?? [],
            'conduct_note'      => $this->request->getPost('conduct_note')      ?? '',
            'additional_notes'  => $this->request->getPost('additional_notes')  ?? '',
            'signatory_name'    => $this->request->getPost('signatory_name')    ?? '',
            'signatory_title'   => $this->request->getPost('signatory_title')   ?? 'Principal',
        ];
    
        $existing = $this->checkExisting($userId, self::REF_CAT_TRANSCRIPT);
        $forceNew = $this->request->getPost('force_new') === '1';
    
        if ($existing && !$forceNew) {
            return $this->response->setJSON([
                'success'       => false,
                'has_existing'  => true,
                'message'       => 'A Transcript Request already exists for this student.',
                'existing_url'  => base_url('uploads/reference/' . $existing['gen_ref_file_name']),
                'existing_date' => date('d M Y', strtotime($existing['gen_ref_date'])),
            ]);
        }
    
        $fullName = $this->fullName($user);
        $filename = 'transcript_' . $userId . '_' . date('Ymd_His') . '.pdf';
        $savePath = $this->ensureUploadDir() . $filename;
    
        $this->generateTranscriptPDF($user, $school, $enrollment ?? [], $formData, $savePath);
        $this->saveReference($userId, self::REF_CAT_TRANSCRIPT, $filename);
        $this->logReference('Transcript Request Generated', 'Transcript generated for ' . $fullName);
    
        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Transcript generated successfully.',
            'file_url' => base_url('uploads/reference/' . $filename),
        ]);
    }
    
    private function generateTranscriptPDF(
        array  $user,
        array  $school,
        array  $enrollment,
        array  $form,
        string $savePath
    ): void {
        $pdf      = $this->newPdf('Academic Transcript');
        $startX   = 20;
        $contentW = 170;
        $y        = 14;
    
        $this->buildPdfHeader($pdf, $school, $startX, $contentW, $y);
    
        // ── Title ─────────────────────────────────────────────────────
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 8, 'ACADEMIC TRANSCRIPT', 0, 1, 'C');
        $y += 9;
    
        $cx = $startX + ($contentW / 2);
        $pdf->SetLineStyle(['width' => 0.5, 'color' => [147, 197, 253]]);
        $pdf->Line($cx - 35, $y, $cx + 35, $y);
        $y += 7;
    
        // ── Ref + Date ────────────────────────────────────────────────
        $refNo = 'TRX-' . str_pad($user['user_id'], 6, '0', STR_PAD_LEFT) . '-' . date('Y');
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->SetTextColor(130, 130, 130);
        $pdf->Cell($contentW / 2, 4, 'Reference No: ' . $refNo, 0, 0, 'L');
        $pdf->Cell($contentW / 2, 4, 'Date Issued: ' . date('d F Y'), 0, 1, 'R');
        $y += 9;
    
        // ── Recipient ─────────────────────────────────────────────────
        if (!empty($form['recipient_name']) && $form['recipient_name'] !== 'To Whom It May Concern') {
            $recipient = trim(($form['recipient_title'] ? $form['recipient_title'] . ' ' : '') . $form['recipient_name']);
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(30, 30, 30);
            $pdf->Cell($contentW, 5, $recipient, 0, 1, 'L');
            $y += 5;
    
            if (!empty($form['recipient_org'])) {
                $pdf->SetXY($startX, $y);
                $pdf->Cell($contentW, 5, $form['recipient_org'], 0, 1, 'L');
                $y += 5;
            }
            $y += 2;
        }
    
        // ── Intro — LEFT aligned ──────────────────────────────────────
        $fullName = $this->fullName($user);
        $pdf->SetXY($startX + 8, $y);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->MultiCell($contentW - 16, 5.5,
            'This is to certify that the following academic transcript has been issued for ' .
            $fullName . ' upon official request for ' . ($form['purpose'] ?: 'academic purposes') . '.',
            0, 'L', false, 1
        );
        $y = $pdf->GetY() + 5;
    
        // ── Student name banner ───────────────────────────────────────
        $pdf->SetFillColor(239, 246, 255);
        $pdf->SetDrawColor(147, 197, 253);
        $pdf->RoundedRect($startX + 10, $y, $contentW - 20, 18, 3, '1111', 'DF');
        $pdf->SetXY($startX + 10, $y + 2);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(100, 130, 200);
        $pdf->Cell($contentW - 20, 4, 'STUDENT NAME', 0, 1, 'C');
        $pdf->SetXY($startX + 10, $y + 6);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW - 20, 11, strtoupper($fullName), 0, 1, 'C');
        $y += 24;
    
        // ── Student info — two column using shared helper ──────────────
        $pdf->SetXY($startX, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($contentW, 5, 'STUDENT INFORMATION', 0, 1, 'L');
        $y += 1;
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Line($startX, $y, $startX + $contentW, $y);
        $y += 4;
    
        $this->buildTwoColumnTable($pdf, $startX, $contentW, $y, [
            ['Gender',        ucfirst($user['gender'] ?? 'N/A'),
             'Date of Birth', !empty($user['dob']) ? date('d F Y', strtotime($user['dob'])) : 'N/A'],
    
            ['Year Level',    $enrollment['level_name']  ?? 'N/A',
             'Stream/Class',  $enrollment['stream_name'] ?? 'N/A'],
    
            ['Academic Year', $enrollment['enrol_year']  ?? 'N/A',
             'Institution',   $school['sch_name']        ?? 'N/A'],
        ], 7);
    
        $y += 6;
    
        // ── Academic Years Covered — dash instead of tick ─────────────
        if (!empty($form['years_included'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(26, 86, 219);
            $pdf->Cell($contentW, 5, 'ACADEMIC YEARS COVERED', 0, 1, 'L');
            $y += 1;
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
            $pdf->Line($startX, $y, $startX + $contentW, $y);
            $y += 5;
    
            $years = is_array($form['years_included']) ? $form['years_included'] : [$form['years_included']];
            sort($years);
            $col = 0;
            foreach ($years as $yr) {
                $xPos = $startX + ($col * 55);
                $pdf->SetTextColor(40, 40, 40);
                // ── Unicode tick via dejavusans ───────────────────────────────
                $this->writeTickItem($pdf, $xPos, $y, 'Year ' . $yr, 52, 6);
                $col++;
                if ($col >= 3) { $col = 0; $y += 6; }
            }
            if ($col > 0) $y += 6;
            $y += 5;
        }
    
        // ── Conduct note ──────────────────────────────────────────────
        if (!empty($form['conduct_note'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(26, 86, 219);
            $pdf->Cell($contentW, 5, 'CONDUCT & BEHAVIOUR NOTE', 0, 1, 'L');
            $y += 1;
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
            $pdf->Line($startX, $y, $startX + $contentW, $y);
            $y += 4;
    
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetTextColor(50, 50, 50);
            $pdf->MultiCell($contentW, 5, $form['conduct_note'], 0, 'L', false, 1);
            $y = $pdf->GetY() + 5;
        }
    
        // ── Additional notes ──────────────────────────────────────────
        if (!empty($form['additional_notes'])) {
            $pdf->SetXY($startX, $y);
            $pdf->SetFont('helvetica', 'I', 8.5);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->MultiCell($contentW, 5, $form['additional_notes'], 0, 'L', false, 1);
            $y = $pdf->GetY() + 3;
        }
    
        // ── Closing ───────────────────────────────────────────────────
        $pdf->SetXY($startX + 8, $y);
        $pdf->SetFont('helvetica', 'I', 8.5);
        $pdf->SetTextColor(70, 70, 70);
        $pdf->MultiCell($contentW - 16, 5,
            'This transcript is issued as an official academic record of ' .
            ($school['sch_name'] ?? 'this institution') .
            '. Any alteration or unauthorized reproduction of this document renders it invalid.',
            0, 'L', false, 1
        );
    
        $this->buildSignatures($pdf, $startX, 242, 'Class Teacher / HOD', 'Principal / Head Teacher');
        $this->buildPdfFooter($pdf, $startX, $contentW);
        $pdf->Output($savePath, 'F');
    }
    
    /**
     * Write a tick [checked] item using DejaVu font for Unicode support
     * then restore helvetica for the rest of the document
     */
    private function writeTickItem(
        \TCPDF $pdf,
        float  $x,
        float  $y,
        string $text,
        float  $cellW = 85,
        float  $cellH = 6,
        string $border = '0',
        string $align = 'L'
    ): void {
        $pdf->SetXY($x, $y);
        $pdf->SetFont('dejavusans', '', 8.5);
        $pdf->Cell($cellW, $cellH, "\u{2713}  " . $text, $border, 0, $align);
        // Restore helvetica after tick
        $pdf->SetFont('helvetica', '', 8.5);
    }
    
    /**
     * Write a status cell with tick or cross using DejaVu font
     */
    private function writeStatusCell(
        \TCPDF $pdf,
        float  $cellW,
        float  $cellH,
        bool   $cleared,
        string $border = 'RTB'
    ): void {
        $pdf->SetFont('dejavusans', 'B', 8.5);
        if ($cleared) {
            $pdf->SetTextColor(16, 185, 129);
            $pdf->Cell($cellW, $cellH, "\u{2713}  CLEARED", $border, 1, 'C', true);
        } else {
            $pdf->SetTextColor(239, 68, 68);
            $pdf->Cell($cellW, $cellH, "\u{2717}  PENDING", $border, 1, 'C', true);
        }
        // Restore helvetica
        $pdf->SetFont('helvetica', '', 8.5);
        $pdf->SetTextColor(40, 40, 40);
    }

    // ── Reference Requests ──────────────────────────────────────────────

    public function storeRequest()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId      = (int) $this->session->get('userID');
        $admissionId = (int) $this->request->getPost('admission_id');
        $refCatId    = (int) $this->request->getPost('ref_cat_id');
        $typeName    = trim($this->request->getPost('ref_type_name') ?? '');
        $note        = trim($this->request->getPost('request_note') ?? '');

        if (!$admissionId || !$refCatId || !$typeName) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please fill in all required fields.']);
        }

        // Validate admission belongs to this user (or to a child linked to this parent)
        $db = \Config\Database::connect();
        $admission = $db->table('admission')
            ->where('admission_id', $admissionId)
            ->where('user_id_fk', $userId)
            ->get()->getRowArray();

        if (!$admission) {
            // Allow parent requesting on behalf of their child
            $roleCat  = (int) $this->session->get('roleCatID');
            $userRow  = $this->userModel->find($userId);
            $isParent = $roleCat === 6 || (int) ($userRow['is_a_parent'] ?? 0) === 1;
            if ($isParent) {
                $admission = $db->query("
                    SELECT a.admission_id, a.sch_id_fk, a.user_id_fk
                    FROM admission a
                    INNER JOIN parent_student ps ON ps.student_user_id_fk = a.user_id_fk
                    WHERE a.admission_id = ? AND ps.parent_user_id_fk = ?
                ", [$admissionId, $userId])->getRowArray();
            }
            if (!$admission) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid admission selected.']);
            }
        }

        // Validate reference category exists
        $cat = $this->referenceCategoryModel->find($refCatId);
        if (!$cat) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid reference type.']);
        }
        $typeName = $cat['ref_cat_name'];

        // One request per reference type per school per year (across all admissions at that school).
        // A student admitted to a different school the same year may request the same type there.
        $currentYear = date('Y');
        $existing = $db->query("
            SELECT COUNT(*) AS cnt
            FROM reference_requests rr
            JOIN admission a ON a.admission_id = rr.admission_id_fk
            WHERE rr.user_id_fk   = {$userId}
              AND rr.ref_cat_id   = {$refCatId}
              AND a.sch_id_fk     = {$admission['sch_id_fk']}
              AND rr.request_status != 'Rejected'
              AND YEAR(rr.created_at) = {$currentYear}
        ")->getRow()->cnt;

        if ($existing > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "You already have a {$typeName} request for this school in {$currentYear}.",
            ]);
        }

        $this->referenceRequestModel->insert([
            'user_id_fk'     => $userId,
            'admission_id_fk'=> $admissionId,
            'ref_cat_id'     => $refCatId,
            'ref_type_name'  => $typeName,
            'request_note'   => $note ?: null,
            'request_status' => 'Pending',
        ]);

        $this->logReference('Reference Requested', "Student requested: {$typeName}");

        return $this->response->setJSON([
            'success' => true,
            'message' => "Your request for '{$typeName}' has been submitted. A staff member will process it shortly.",
        ]);
    }

    public function requests()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }
        $this->grant_access('_reference_requests');

        $schId        = (int) $this->session->get('schID');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $requests     = $this->referenceRequestModel->getAll($isSuperAdmin ? 0 : $schId);

        $this->setPageData('Reference Requests', 'User', 'Reference Requests');

        $data = [
            'requests' => $requests,
            '_view'    => 'app/reference/requests',
        ];

        return view('app/layouts/main', $data);
    }

    public function updateRequest(int $requestId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        $this->grant_access('_reference_requests');

        $status     = $this->request->getPost('request_status');
        $reviewNote = trim($this->request->getPost('review_note') ?? '');
        $validStatuses = ['Pending', 'In Progress', 'Completed', 'Rejected'];

        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $this->referenceRequestModel->updateStatus(
            $requestId,
            $status,
            (int) $this->session->get('userID'),
            $reviewNote
        );

        return $this->response->setJSON(['success' => true, 'message' => 'Request updated.']);
    }
}