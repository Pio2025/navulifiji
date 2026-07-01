<?php
namespace App\Controllers;

class AttendanceController extends BaseController
{
    private const UNAUTHORIZED_ROLES = [1, 4, 5, 6];
    private const TEACHER_ROLE_CAT   = 3;

    // ================================================================
    // ADD — landing page
    // ================================================================
    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Add Daily Attendance', 'Attendance', 'Add Daily Attendance');

        $roleCatID = (int) $this->session->get('roleCatID');
        $userID    = (int) $this->session->get('userID');
        $schID     = (int) $this->session->get('schID');

        $data['_view']        = 'app/attendance/add';
        $data['error']        = null;
        $data['streams']      = [];
        $data['teacherAdmId'] = null;

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            $data['error'] = 'unauthorised_role';
            return view('app/layouts/main', $data);
        }

        if ($roleCatID === self::TEACHER_ROLE_CAT) {
            $admission = $this->studentAttendanceModel->getTeacherActiveAdmission($userID, $schID);
            if (!$admission) {
                $data['error'] = 'no_admission';
                return view('app/layouts/main', $data);
            }
            $data['teacherAdmId'] = (int) $admission['admission_id'];
            if (!$schID) {
                $schID = (int) $admission['sch_id_fk'];
            }
        }

        $data['streams'] = $this->studentAttendanceModel->getStreamsBySchool($schID);
        $data['schID']   = $schID;
        $data['today']   = date('Y-m-d');

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // VIEW — calendar index OR term grid (when ?stream_id=X&term=N)
    // ================================================================
    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $termNo   = (int) $this->request->getGet('term');
        $streamId = (int) $this->request->getGet('stream_id');

        if ($termNo > 0 && $streamId > 0) {
            return $this->_showTermGrid($streamId, $termNo);
        }

        $this->setPageData('View Daily Attendance', 'Attendance', 'View Daily Attendance');

        $roleCatID = (int) $this->session->get('roleCatID');
        $userID    = (int) $this->session->get('userID');
        $schID     = (int) $this->session->get('schID');

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            $data['_view']   = 'app/attendance/view';
            $data['error']   = 'unauthorised_role';
            $data['streams'] = [];
            return view('app/layouts/main', $data);
        }

        if ($roleCatID === self::TEACHER_ROLE_CAT && !$schID) {
            $admission = $this->studentAttendanceModel->getTeacherActiveAdmission($userID, 0);
            if ($admission) {
                $schID = (int) $admission['sch_id_fk'];
            }
        }

        $data['_view']        = 'app/attendance/view';
        $data['error']        = null;
        $data['streams']      = $this->studentAttendanceModel->getStreamsBySchool($schID);
        $data['schID']        = $schID;
        $data['preStreamId']  = $streamId;

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // TERM GRID — private renderer called from index()
    // ================================================================
    private function _showTermGrid(int $streamId, int $termNo)
    {
        $this->setPageData('Term Attendance Grid', 'Attendance', 'View Daily Attendance');

        $roleCatID  = (int) $this->session->get('roleCatID');
        $schID      = (int) $this->session->get('schID');
        $schCatData = $this->session->get('sch_cat_data') ?? [];

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            return redirect()->to('dashboard')->with('error', 'You are not authorised to view attendance.');
        }

        $streamInfo = $this->studentAttendanceModel->getStreamById($streamId);
        if (!$streamInfo || ($schID !== 0 && (int) $streamInfo['sch_id_fk'] !== $schID)) {
            return redirect()->to('attendance')->with('error', 'Stream not found or access denied.');
        }

        // Load sch_cat_data from DB if not in session (user logged in before it was introduced)
        if (empty($schCatData['terms'])) {
            $schCatData = $this->_loadSchCatData((int) $streamInfo['sch_id_fk']);
        }

        $termLabel = $schCatData['label'] ?? 'Term';
        $termInfo  = $schCatData['terms'][$termNo] ?? null;

        if (!$termInfo) {
            return redirect()->to('attendance')->with('error', 'Term data not configured. Please ask your administrator to set up the school category.');
        }

        $numWeeks   = (int) $termInfo['num_of_week'];
        $startDay   = (int) $termInfo['term_start_day'];
        $startMonth = (int) $termInfo['term_start_month'];
        $dayNames   = ['M', 'T', 'W', 'TH', 'F'];

        $startDt = \DateTime::createFromFormat('Y-n-j', date('Y') . '-' . $startMonth . '-' . $startDay);
        if (!$startDt) {
            return redirect()->to('attendance')->with('error', 'Invalid term start date in category configuration.');
        }

        $weeks    = [];
        $allDates = [];
        for ($w = 0; $w < $numWeeks; $w++) {
            $weekRow = [];
            foreach ($dayNames as $di => $dn) {
                $dt      = clone $startDt;
                $dt->modify('+' . ($w * 7 + $di) . ' days');
                $dateStr = $dt->format('Y-m-d');
                $weekRow[$dn] = $dateStr;
                $allDates[]   = $dateStr;
            }
            $weeks[] = $weekRow;
        }

        $students          = $this->studentAttendanceModel->getStudentsInStream($streamId);
        $attendance        = $this->studentAttendanceModel->getTermAttendance($streamId, $allDates);
        $holidays          = $this->publicHolidayModel->getByDates($allDates, (int) $streamInfo['sch_id_fk']);
        $allSchoolHolidays = $this->publicHolidayModel->getForSchool((int) $streamInfo['sch_id_fk']);
        $attStats          = $this->_computeAttStats($students, $attendance, $allDates, $weeks, $holidays);

        $data['_view']             = 'app/attendance/grid';
        $data['streamInfo']        = $streamInfo;
        $data['streamId']          = $streamId;
        $data['termNo']            = $termNo;
        $data['termLabel']         = $termLabel;
        $data['termInfo']          = $termInfo;
        $data['weeks']             = $weeks;
        $data['dayNames']          = $dayNames;
        $data['students']          = $students;
        $data['attendance']        = $attendance;
        $data['holidays']          = $holidays;
        $data['allSchoolHolidays'] = $allSchoolHolidays;
        $data['today']             = date('Y-m-d');
        $data['studentStats'] = $attStats['studentStats'];
        $data['summaryStats'] = $attStats['summaryStats'];
        $data['weeklyStats']  = $attStats['weeklyStats'];

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // SAVE GRID — POST from the term attendance grid form
    // ================================================================
    public function saveGrid()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $roleCatID  = (int) $this->session->get('roleCatID');
        $schID      = (int) $this->session->get('schID');
        $schCatData = $this->session->get('sch_cat_data') ?? [];

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $streamId = (int) $this->request->getPost('stream_id');
        $termNo   = (int) $this->request->getPost('term_no');

        if (!$streamId || !$termNo) {
            return redirect()->to('attendance')->with('error', 'Invalid request.');
        }

        $streamInfo = $this->studentAttendanceModel->getStreamById($streamId);
        if (!$streamInfo || ($schID !== 0 && (int) $streamInfo['sch_id_fk'] !== $schID)) {
            return redirect()->to('attendance')->with('error', 'Access denied.');
        }

        if (empty($schCatData['terms'])) {
            $schCatData = $this->_loadSchCatData((int) $streamInfo['sch_id_fk']);
        }

        $termInfo = $schCatData['terms'][$termNo] ?? null;
        if (!$termInfo) {
            return redirect()->to("attendance?stream_id={$streamId}&term={$termNo}")->with('error', 'Term configuration not found.');
        }

        $numWeeks   = (int) $termInfo['num_of_week'];
        $startDay   = (int) $termInfo['term_start_day'];
        $startMonth = (int) $termInfo['term_start_month'];
        $dayNames   = ['M', 'T', 'W', 'TH', 'F'];
        $today      = date('Y-m-d');

        $startDt = \DateTime::createFromFormat('Y-n-j', date('Y') . '-' . $startMonth . '-' . $startDay);
        if (!$startDt) {
            return redirect()->to("attendance?stream_id={$streamId}&term={$termNo}")->with('error', 'Invalid term configuration.');
        }

        $allDates = [];
        for ($w = 0; $w < $numWeeks; $w++) {
            foreach ($dayNames as $di => $dn) {
                $dt   = clone $startDt;
                $dt->modify('+' . ($w * 7 + $di) . ' days');
                $date = $dt->format('Y-m-d');
                if ($date <= $today) {
                    $allDates[] = $date;
                }
            }
        }

        if (empty($allDates)) {
            return redirect()->to("attendance?stream_id={$streamId}&term={$termNo}")->with('error', 'No past or current dates to save.');
        }

        $students = $this->studentAttendanceModel->getStudentsInStream($streamId);
        if (empty($students)) {
            return redirect()->to("attendance?stream_id={$streamId}&term={$termNo}")->with('error', 'No students enrolled in this stream.');
        }

        $postAtt = $this->request->getPost('att') ?? [];
        $db      = \Config\Database::connect();
        $db->transStart();

        foreach ($students as $student) {
            $admId   = (int) $student['admission_id_fk'];
            $enrolId = (int) $student['enrol_id'];

            foreach ($allDates as $date) {
                $rawVal = $postAtt[$admId][$date] ?? '';
                if ($rawVal === 'P')      $status = 'Present';
                elseif ($rawVal === 'A')  $status = 'Absent';
                else                      $status = null;   // Not Marked — no record

                $existing = $db->table('student_attendance')
                    ->where('enrol_id_fk', $enrolId)
                    ->where('stream_id_fk', $streamId)
                    ->where('attendance_date', $date)
                    ->where('attendance_type', 'Daily')
                    ->get()->getRowArray();

                if ($status === null) {
                    // Not Marked: remove any previous record for this date
                    if ($existing) {
                        $db->table('student_attendance')
                            ->where('stud_att_id', $existing['stud_att_id'])
                            ->delete();
                    }
                } elseif ($existing) {
                    $db->table('student_attendance')
                        ->where('stud_att_id', $existing['stud_att_id'])
                        ->update(['attendance_status' => $status]);
                } else {
                    $db->table('student_attendance')->insert([
                        'enrol_id_fk'       => $enrolId,
                        'stream_id_fk'      => $streamId,
                        'admission_id_fk'   => $admId,
                        'attendance_date'   => $date,
                        'attendance_type'   => 'Daily',
                        'attendance_status' => $status,
                    ]);
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to("attendance?stream_id={$streamId}&term={$termNo}")->with('error', 'Failed to save attendance. Please try again.');
        }

        return redirect()->to("attendance?stream_id={$streamId}&term={$termNo}")
            ->with('success', 'Attendance saved successfully.');
    }

    // ================================================================
    // HELPER — compute attendance statistics from loaded data
    // ================================================================
    private function _computeAttStats(array $students, array $attendance, array $allDates, array $weeks, array $holidays = []): array
    {
        $today       = date('Y-m-d');
        // Exclude holidays and future dates from the denominator
        $markedDates = array_values(array_filter($allDates, fn($d) => $d <= $today && !isset($holidays[$d])));
        $numMarked   = count($markedDates);

        $studentStats  = [];
        $totalPresent  = 0;
        $totalAbsent   = 0;
        $totalUnmarked = 0;

        foreach ($students as $s) {
            $eid = (int) $s['enrol_id'];
            $p = $a = $u = 0;
            foreach ($markedDates as $d) {
                $st = $attendance[$eid][$d] ?? null;
                if ($st === 'Present')      $p++;
                elseif ($st === 'Absent')   $a++;
                else                        $u++;
            }
            $pct = $numMarked > 0 ? round($p / $numMarked * 100, 1) : 0.0;
            $studentStats[$eid] = ['present' => $p, 'absent' => $a, 'unmarked' => $u, 'pct' => $pct];
            $totalPresent  += $p;
            $totalAbsent   += $a;
            $totalUnmarked += $u;
        }

        $numStudents      = count($students);
        $totalSlots       = $numStudents * $numMarked;
        $classAvgPct      = $totalSlots > 0 ? round($totalPresent / $totalSlots * 100, 1) : 0.0;
        $perfectCount     = count(array_filter($studentStats, fn($s) => $s['pct'] >= 100.0));
        $belowThreshCount = count(array_filter($studentStats, fn($s) => $numMarked > 0 && $s['pct'] < 80.0));

        $weeklyStats = [];
        foreach ($weeks as $wi => $weekDays) {
            $wp = $wa = 0;
            foreach ($weekDays as $dt) {
                if ($dt > $today || isset($holidays[$dt])) continue;
                foreach ($students as $s) {
                    $st = $attendance[(int)$s['enrol_id']][$dt] ?? null;
                    if ($st === 'Present')    $wp++;
                    elseif ($st === 'Absent') $wa++;
                }
            }
            $wTotal = $wp + $wa;
            $weeklyStats[] = [
                'week'    => $wi + 1,
                'pct'     => $wTotal > 0 ? round($wp / $wTotal * 100, 1) : 0.0,
                'present' => $wp,
                'total'   => $wTotal,
            ];
        }

        return [
            'studentStats' => $studentStats,
            'weeklyStats'  => $weeklyStats,
            'summaryStats' => [
                'markedDays'       => $numMarked,
                'totalStudents'    => $numStudents,
                'classAvgPct'      => $classAvgPct,
                'perfectCount'     => $perfectCount,
                'belowThreshCount' => $belowThreshCount,
                'totalPresent'     => $totalPresent,
                'totalAbsent'      => $totalAbsent,
                'totalUnmarked'    => $totalUnmarked,
            ],
        ];
    }

    // ================================================================
    // HELPER — load school category term data from DB by school ID
    // Used as fallback when sch_cat_data is not yet in session
    // ================================================================
    private function _loadSchCatData(int $schId): array
    {
        if (!$schId) {
            return ['label' => 'Term', 'terms' => []];
        }

        $db     = \Config\Database::connect();
        $school = $db->table('school')
            ->select('sch_cat_id_fk')
            ->where('sch_id', $schId)
            ->get()->getRowArray();

        if (!$school || empty($school['sch_cat_id_fk'])) {
            return ['label' => 'Term', 'terms' => []];
        }

        $config = $this->schoolCategoryConfigModel->getByCategoryId((int) $school['sch_cat_id_fk']);
        if (!$config) {
            return ['label' => 'Term', 'terms' => []];
        }

        $rows     = $this->schoolCategoryTermModel->getByConfigId((int) $config['sch_cat_con_id']);
        $termData = [];
        foreach ($rows as $t) {
            $termData[(int) $t['term_num']] = [
                'num_of_week'      => (int) $t['num_of_week'],
                'term_start_day'   => (int) $t['term_start_day'],
                'term_start_month' => (int) $t['term_start_month'],
                'term_end_day'     => (int) $t['term_end_day'],
                'term_end_month'   => (int) $t['term_end_month'],
            ];
        }

        $result = ['label' => $config['label_for_term'], 'terms' => $termData];
        $this->session->set('sch_cat_data', $result);
        return $result;
    }

    // ================================================================
    // PDF — A3 landscape attendance grid (TCPDF)
    // ================================================================
    public function gridPdf()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $roleCatID  = (int) $this->session->get('roleCatID');
        $schID      = (int) $this->session->get('schID');
        $schCatData = $this->session->get('sch_cat_data') ?? [];

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $streamId = (int) $this->request->getGet('stream_id');
        $termNo   = (int) $this->request->getGet('term');

        if (!$streamId || !$termNo) {
            return redirect()->to('attendance')->with('error', 'Invalid request.');
        }

        $streamInfo = $this->studentAttendanceModel->getStreamById($streamId);
        if (!$streamInfo || ($schID !== 0 && (int) $streamInfo['sch_id_fk'] !== $schID)) {
            return redirect()->to('attendance')->with('error', 'Access denied.');
        }

        if (empty($schCatData['terms'])) {
            $schCatData = $this->_loadSchCatData((int) $streamInfo['sch_id_fk']);
        }

        $termLabel = $schCatData['label'] ?? 'Term';
        $termInfo  = $schCatData['terms'][$termNo] ?? null;
        if (!$termInfo) {
            return redirect()->to('attendance')->with('error', 'Term data not configured.');
        }

        // Load school details
        $db     = \Config\Database::connect();
        $school = $db->table('school')->where('sch_id', $streamInfo['sch_id_fk'])->get()->getRowArray() ?? [];

        // Build weeks / dates
        $numWeeks   = (int) $termInfo['num_of_week'];
        $startDay   = (int) $termInfo['term_start_day'];
        $startMonth = (int) $termInfo['term_start_month'];
        $dayKeys    = ['M', 'T', 'W', 'TH', 'F'];

        $startDt = \DateTime::createFromFormat('Y-n-j', date('Y') . '-' . $startMonth . '-' . $startDay);
        if (!$startDt) {
            return redirect()->to('attendance')->with('error', 'Invalid term start date configuration.');
        }

        $weeks    = [];
        $allDates = [];
        for ($w = 0; $w < $numWeeks; $w++) {
            $weekRow = [];
            foreach ($dayKeys as $di => $dk) {
                $dt      = clone $startDt;
                $dt->modify('+' . ($w * 7 + $di) . ' days');
                $dateStr = $dt->format('Y-m-d');
                $weekRow[$dk] = $dateStr;
                $allDates[]   = $dateStr;
            }
            $weeks[] = $weekRow;
        }

        $students   = $this->studentAttendanceModel->getStudentsInStream($streamId);
        $attendance = $this->studentAttendanceModel->getTermAttendance($streamId, $allDates);
        $today      = date('Y-m-d');
        $holidays   = $this->publicHolidayModel->getByDates($allDates, (int) $streamInfo['sch_id_fk']);
        $attStats   = $this->_computeAttStats($students, $attendance, $allDates, $weeks, $holidays);
        $studentStats = $attStats['studentStats'];
        $summaryStats = $attStats['summaryStats'];
        $weeklyStats  = $attStats['weeklyStats'];

        // ── TCPDF ──────────────────────────────────────────────────────────────
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

        // Suppress libpng iCCP profile warnings (same pattern as reportCardPdf)
        set_error_handler(static function (int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP')
                || str_contains($errstr, 'gd-png')
                || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        // A3 landscape
        $pdf = new \TCPDF('L', 'mm', 'A3', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetAuthor('Navuli Fiji School Management System');
        $pdf->SetTitle(strtoupper($school['sch_name'] ?? '') . ' — ' . strtoupper($termLabel) . ' ' . $termNo . ' ATTENDANCE');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(false, 0);

        // ── Layout constants (A3 landscape = 420 × 297 mm) ────────────────────
        $sx     = 12;    // start X
        $cw     = 396;   // content width (420 − 12 − 12)
        $pageH  = 297;   // page height
        $wNum   = 8;     // # column width
        $wName  = 48;    // student name column width
        $wDay   = round(($cw - $wNum - $wName) / ($numWeeks * 5), 3);
        $hWk    = 5.0;   // week-label header row height
        $hDay   = 6.0;   // day-label/date header row height
        $hHdr   = $hWk + $hDay;
        $hRow   = 6.0;   // data row height

        // Colours
        $cBlue  = [26,  86,  219];
        $cLtBl  = [147, 197, 253];
        $cHdrBg = [235, 242, 255];

        // ── Page 1 ─────────────────────────────────────────────────────────────
        $pdf->AddPage();
        $y = 12.0;

        // Double border frame
        $pdf->SetLineStyle(['width' => 1.0, 'color' => $cBlue]);
        $pdf->Rect(8, 8, 404, 281, 'D');
        $pdf->SetLineStyle(['width' => 0.3, 'color' => $cLtBl]);
        $pdf->Rect(10, 10, 400, 277, 'D');

        // ── Centered header block: [school logo]  [text]  [navuli logo] ──────
        // Size the block to fit the actual text so logos sit tight against content
        $logoW    = 22.0;
        $navLogoW = 20.0;
        $gap      = 2.0;

        $streamLabelTmp = ($streamInfo['stream_name'] ?? '');
        if (!empty($streamInfo['level_name'])) $streamLabelTmp .= ' (' . $streamInfo['level_name'] . ')';
        $contactPartsTmp = array_filter([
            $school['sch_address'] ?? '',
            !empty($school['sch_phone']) ? 'Ph: ' . $school['sch_phone'] : '',
            $school['sch_email'] ?? '',
        ]);

        $pdf->SetFont('helvetica', 'B', 13);
        $w1 = $pdf->GetStringWidth(strtoupper($school['sch_name'] ?? ''));
        $pdf->SetFont('helvetica', 'B', 8);
        $w2 = $pdf->GetStringWidth(strtoupper($streamLabelTmp) . ' — STUDENT ATTENDANCE');
        $pdf->SetFont('helvetica', '', 8);
        $w3 = $pdf->GetStringWidth(strtoupper($termLabel) . ' ' . $termNo . '  |  YEAR ' . date('Y'));
        $pdf->SetFont('helvetica', '', 6.5);
        $w4 = !empty($contactPartsTmp) ? $pdf->GetStringWidth(implode('  |  ', $contactPartsTmp)) : 0;

        $textW  = max($w1, $w2, $w3, $w4) + 8.0;  // 4 mm breathing room each side
        $blockW = $logoW + $gap + $textW + $gap + $navLogoW;
        $blockW = min($blockW, $cw);                // never wider than content area
        $blockX = $sx + ($cw - $blockW) / 2;
        $textX  = $blockX + $logoW + $gap;
        $navLogoX = $blockX + $blockW - $navLogoW;

        // School logo (left of block)
        if (!empty($school['sch_logo'])) {
            $logoPath = FCPATH . 'uploads/school/logo/' . $school['sch_logo'];
            if (file_exists($logoPath)) {
                $pdf->Image($logoPath, $blockX, $y, $logoW, $logoW, '', '', 'T', false, 300);
            }
        }

        // Navuli logo (right of block)
        $navuliLogo = FCPATH . 'icon.png';
        if (file_exists($navuliLogo)) {
            $pdf->Image($navuliLogo, $navLogoX, $y, $navLogoW, $navLogoW, '', '', 'T', false, 300);
        }

        // School name
        $pdf->SetXY($textX, $y + 1);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($textW, 6, strtoupper($school['sch_name'] ?? ''), 0, 1, 'C');

        // "Stream X — Student Attendance"
        $streamLabel = ($streamInfo['stream_name'] ?? '');
        if (!empty($streamInfo['level_name'])) {
            $streamLabel .= ' (' . $streamInfo['level_name'] . ')';
        }
        $pdf->SetXY($textX, $y + 8);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell($textW, 4, strtoupper($streamLabel) . ' — STUDENT ATTENDANCE', 0, 1, 'C');

        // "Term N  |  Year YYYY"
        $pdf->SetXY($textX, $y + 13);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($textW, 4, strtoupper($termLabel) . ' ' . $termNo . '  |  YEAR ' . date('Y'), 0, 1, 'C');

        // Contact line
        $contactParts = array_filter([
            $school['sch_address'] ?? '',
            !empty($school['sch_phone']) ? 'Ph: ' . $school['sch_phone'] : '',
            $school['sch_email'] ?? '',
        ]);
        if (!empty($contactParts)) {
            $pdf->SetXY($textX, $y + 18);
            $pdf->SetFont('helvetica', '', 6.5);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->Cell($textW, 4, implode('  |  ', $contactParts), 0, 1, 'C');
        }

        // Double divider
        $y += 30;
        $pdf->SetLineStyle(['width' => 0.7, 'color' => $cBlue]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => $cLtBl]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 5;

        // ── Draw table column headers ──────────────────────────────────────────
        $y = $this->_drawAttGridHeaders($pdf, $y, $sx, $wNum, $wName, $wDay, $hWk, $hDay, $weeks, $dayKeys, $cBlue, $cHdrBg);

        // ── Data rows ──────────────────────────────────────────────────────────
        foreach ($students as $si => $student) {

            // Page break
            if ($y + $hRow > $pageH - 13) {
                $pdf->AddPage();
                $y = 12.0;
                $pdf->SetFont('helvetica', 'I', 6.5);
                $pdf->SetTextColor(150, 150, 150);
                $pdf->SetXY($sx, $y);
                $pdf->Cell($cw, 4,
                    strtoupper($school['sch_name'] ?? '') . '  —  '
                    . strtoupper($streamLabel) . '  —  '
                    . strtoupper($termLabel) . ' ' . $termNo . ' ATTENDANCE (CONTINUED)',
                    0, 0, 'C');
                $y += 5;
                $y = $this->_drawAttGridHeaders($pdf, $y, $sx, $wNum, $wName, $wDay, $hWk, $hDay, $weeks, $dayKeys, $cBlue, $cHdrBg);
            }

            $enrolId  = (int) $student['enrol_id'];
            $admId    = (int) $student['admission_id_fk'];
            $fullName = trim(($student['lname'] ?? '') . ', ' . ($student['fname'] ?? ''));
            $rowBg    = ($si % 2 === 0) ? [255, 255, 255] : [248, 248, 252];

            $pdf->SetLineStyle(['width' => 0.1, 'color' => [200, 210, 230]]);

            // # cell
            $pdf->SetFillColor($rowBg[0], $rowBg[1], $rowBg[2]);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->SetFont('helvetica', '', 6);
            $pdf->SetXY($sx, $y);
            $pdf->Cell($wNum, $hRow, $si + 1, 1, 0, 'C', true);

            // Student name cell
            $pdf->SetXY($sx + $wNum, $y);
            $pdf->SetFont('helvetica', '', 6.5);
            $pdf->Cell($wName, $hRow, $fullName, 1, 0, 'L', true);

            // Attendance cells
            $xc = $sx + $wNum + $wName;
            $pdf->SetFont('dejavusans', '', 7);
            foreach ($weeks as $wi => $weekDays) {
                foreach ($dayKeys as $dk) {
                    $date   = $weekDays[$dk] ?? '';
                    $status = $attendance[$enrolId][$date] ?? null;

                    if (isset($holidays[$date])) {
                        $pdf->SetFillColor(237, 233, 254);
                        $pdf->SetTextColor(109, 40, 217);
                        $text = 'H';
                    } elseif ($date > $today) {
                        $pdf->SetFillColor(245, 245, 245);
                        $pdf->SetTextColor(210, 210, 210);
                        $text = '';
                    } elseif ($status === 'Present') {
                        $pdf->SetFillColor(209, 250, 229);
                        $pdf->SetTextColor(6, 95, 70);
                        $text = '✓';
                    } elseif ($status === 'Absent') {
                        $pdf->SetFillColor(254, 226, 226);
                        $pdf->SetTextColor(153, 27, 27);
                        $text = '✗';
                    } else {
                        $pdf->SetFillColor($rowBg[0], $rowBg[1], $rowBg[2]);
                        $pdf->SetTextColor(185, 185, 185);
                        $text = '·';
                    }

                    $pdf->SetXY($xc, $y);
                    $pdf->Cell($wDay, $hRow, $text, 1, 0, 'C', true);
                    $xc += $wDay;
                }
            }

            $y += $hRow;
        }

        // ── Footer text ────────────────────────────────────────────────────────
        $y += 5;
        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'I', 5.5);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->Cell($cw / 2, 4, 'Total Students: ' . count($students), 0, 0, 'L');
        $pdf->SetXY($sx + $cw / 2, $y);
        $pdf->Cell($cw / 2, 4, 'Generated by Navuli Fiji School Management System — ' . date('d M Y H:i'), 0, 0, 'R');

        // ══════════════════════════════════════════════════════════════════════
        // PAGE 2 — STATISTICS
        // ══════════════════════════════════════════════════════════════════════
        $pdf->AddPage();
        $y = 12.0;

        // Border frame (same double-border as page 1)
        $pdf->SetLineStyle(['width' => 1.0, 'color' => $cBlue]);
        $pdf->Rect(8, 8, 404, 281, 'D');
        $pdf->SetLineStyle(['width' => 0.3, 'color' => $cLtBl]);
        $pdf->Rect(10, 10, 400, 277, 'D');

        // Page 2 header (compact — reuse same block variables from page 1)
        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($cw, 5, strtoupper($school['sch_name'] ?? ''), 0, 1, 'C');
        $pdf->SetXY($sx, $y + 6);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($cw, 4, strtoupper($termLabel) . ' ' . $termNo . '  |  YEAR ' . date('Y') . '  —  ATTENDANCE STATISTICS REPORT', 0, 1, 'C');
        $y += 14;
        $pdf->SetLineStyle(['width' => 0.7, 'color' => $cBlue]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => $cLtBl]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 5;

        // ── Summary stat boxes ─────────────────────────────────────────────
        $boxW = $cw / 5;
        $boxH = 18.0;
        $summaryBoxes = [
            ['label' => 'Students',      'value' => $summaryStats['totalStudents'],    'color' => [26,86,219]],
            ['label' => 'Days Marked',   'value' => $summaryStats['markedDays'],       'color' => [100,100,100]],
            ['label' => 'Class Average', 'value' => $summaryStats['classAvgPct'].'%',  'color' => ($summaryStats['classAvgPct'] >= 80 ? [5,150,105] : [185,28,28])],
            ['label' => 'Perfect (100%)','value' => $summaryStats['perfectCount'],      'color' => [5,150,105]],
            ['label' => 'Below 80%',     'value' => $summaryStats['belowThreshCount'],  'color' => [185,28,28]],
        ];
        foreach ($summaryBoxes as $bi => $box) {
            $bx = $sx + $bi * $boxW;
            $pdf->SetLineStyle(['width' => 0.3, 'color' => [200,210,230]]);
            $pdf->SetFillColor(248, 250, 255);
            $pdf->Rect($bx + 1, $y, $boxW - 2, $boxH, 'DF');
            // value
            $pdf->SetXY($bx + 1, $y + 2);
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetTextColor($box['color'][0], $box['color'][1], $box['color'][2]);
            $pdf->Cell($boxW - 2, 8, (string)$box['value'], 0, 0, 'C');
            // label
            $pdf->SetXY($bx + 1, $y + 10);
            $pdf->SetFont('helvetica', '', 6);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->Cell($boxW - 2, 4, strtoupper($box['label']), 0, 0, 'C');
        }
        $y += $boxH + 4;

        // ── Horizontal bar chart — per-student attendance % ────────────────
        $chartH   = min(count($students) * 4.5 + 10, 90.0);
        $chartW   = $cw * 0.60;   // left 60%
        $barAreaX = $sx + 42;     // space for student name label
        $barAreaW = $chartW - 42;
        $barH     = 3.2;
        $barGap   = 1.3;

        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($chartW, 5, 'Student Attendance Rate (%)', 0, 1, 'L');
        $y += 5;

        // Sort students by pct desc for the chart
        $sortedStudents = $students;
        usort($sortedStudents, function ($a, $b) use ($studentStats) {
            return ($studentStats[(int)$b['enrol_id']]['pct'] ?? 0) <=> ($studentStats[(int)$a['enrol_id']]['pct'] ?? 0);
        });

        $yBar = $y;
        foreach ($sortedStudents as $s) {
            $eid  = (int)$s['enrol_id'];
            $pct  = $studentStats[$eid]['pct'] ?? 0;
            $name = substr(trim(($s['lname'] ?? '') . ', ' . ($s['fname'] ?? '')), 0, 18);
            $barW = ($barAreaW * $pct / 100);

            $pdf->SetXY($sx, $yBar);
            $pdf->SetFont('helvetica', '', 5.5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell(40, $barH, $name, 0, 0, 'R');

            // Background track
            $pdf->SetFillColor(235, 238, 245);
            $pdf->Rect($barAreaX, $yBar + 0.3, $barAreaW, $barH - 0.6, 'F');

            // Filled bar
            if ($barW > 0) {
                if ($pct >= 90)      $pdf->SetFillColor(5,  150, 105);
                elseif ($pct >= 80)  $pdf->SetFillColor(16, 185, 129);
                elseif ($pct >= 70)  $pdf->SetFillColor(245,158,11);
                else                 $pdf->SetFillColor(239,68, 68);
                $pdf->Rect($barAreaX, $yBar + 0.3, $barW, $barH - 0.6, 'F');
            }

            // Pct label
            $pdf->SetXY($barAreaX + $barAreaW + 1, $yBar);
            $pdf->SetFont('helvetica', 'B', 5.5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->Cell(12, $barH, $pct . '%', 0, 0, 'L');

            $yBar += $barH + $barGap;
        }

        // ── Weekly trend line chart (right side) ───────────────────────────
        $trendX = $sx + $chartW + 4;
        $trendW = $cw - $chartW - 4;
        $trendH = min(max($yBar - $y, 35.0), 90.0);
        $trendY = $y;

        $pdf->SetXY($trendX, $trendY);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($trendW, 5, 'Weekly Attendance Rate (%)', 0, 1, 'L');
        $trendY += 5;

        // Draw 0%, 50%, 100% grid lines
        $axisH = $trendH - 8;  // reserve bottom for week labels
        foreach ([0, 25, 50, 75, 100] as $mark) {
            $lineY = $trendY + $axisH - ($axisH * $mark / 100);
            $pdf->SetLineStyle(['width' => 0.1, 'color' => [220,220,220]]);
            $pdf->Line($trendX + 8, $lineY, $trendX + $trendW, $lineY);
            $pdf->SetXY($trendX, $lineY - 1.5);
            $pdf->SetFont('helvetica', '', 4.5);
            $pdf->SetTextColor(160, 160, 160);
            $pdf->Cell(8, 3, $mark . '%', 0, 0, 'R');
        }

        // Plot bars per week
        $nw = count($weeklyStats);
        if ($nw > 0) {
            $wBW = ($trendW - 10) / $nw;
            foreach ($weeklyStats as $wi => $wk) {
                $bx  = $trendX + 8 + $wi * $wBW + $wBW * 0.15;
                $bw  = $wBW * 0.70;
                $bh  = $axisH * $wk['pct'] / 100;
                $by  = $trendY + $axisH - $bh;

                if ($wk['pct'] >= 90)     $pdf->SetFillColor(5,  150, 105);
                elseif ($wk['pct'] >= 80) $pdf->SetFillColor(16, 185, 129);
                elseif ($wk['pct'] >= 70) $pdf->SetFillColor(245,158,11);
                else                      $pdf->SetFillColor(239, 68,  68);

                if ($bh > 0) $pdf->Rect($bx, $by, $bw, $bh, 'F');

                // Week label
                $pdf->SetXY($bx - 1, $trendY + $axisH + 0.5);
                $pdf->SetFont('helvetica', '', 4.5);
                $pdf->SetTextColor(100, 100, 100);
                $pdf->Cell($bw + 2, 3, 'W' . $wk['week'], 0, 0, 'C');

                // % above bar
                if ($wk['pct'] > 0) {
                    $pdf->SetXY($bx - 1, max($by - 3.5, $trendY));
                    $pdf->SetFont('helvetica', 'B', 4.5);
                    $pdf->SetTextColor(60, 60, 60);
                    $pdf->Cell($bw + 2, 3, $wk['pct'] . '%', 0, 0, 'C');
                }
            }
        }

        $y = max($yBar, $trendY + $trendH) + 6;

        // ── Per-student summary table ──────────────────────────────────────
        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($cw, 5, 'Student Attendance Summary', 0, 1, 'L');
        $y += 5;

        // Header row
        $colW = [$cw * 0.04, $cw * 0.36, $cw * 0.12, $cw * 0.12, $cw * 0.12, $cw * 0.14, $cw * 0.10];
        $colH = [['#'], ['Student Name'], ['Present'], ['Absent'], ['Not Marked'], ['Att. Rate'], ['Status']];
        $pdf->SetFillColor(26, 86, 219);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 6);
        $pdf->SetLineStyle(['width' => 0.1, 'color' => [200,210,230]]);
        $hx = $sx;
        foreach ($colW as $ci => $cwidth) {
            $pdf->SetXY($hx, $y);
            $pdf->Cell($cwidth, 5, $colH[$ci][0], 1, 0, 'C', true);
            $hx += $cwidth;
        }
        $y += 5;

        // Data rows
        foreach ($sortedStudents as $si => $s) {
            $eid  = (int)$s['enrol_id'];
            $st   = $studentStats[$eid] ?? ['present'=>0,'absent'=>0,'unmarked'=>0,'pct'=>0];
            $name = trim(($s['lname'] ?? '') . ', ' . ($s['fname'] ?? ''));
            $rowBg2 = ($si % 2 === 0) ? [255,255,255] : [245,247,255];

            if ($st['pct'] >= 90)      $statusTxt = 'Excellent';
            elseif ($st['pct'] >= 80)  $statusTxt = 'Good';
            elseif ($st['pct'] >= 70)  $statusTxt = 'Average';
            else                       $statusTxt = 'At Risk';

            $pctColor = ($st['pct'] >= 80) ? [5,150,105] : [185,28,28];
            $rowData  = [$si+1, $name, $st['present'], $st['absent'], $st['unmarked'], $st['pct'].'%', $statusTxt];

            $pdf->SetFillColor($rowBg2[0], $rowBg2[1], $rowBg2[2]);
            $pdf->SetFont('helvetica', '', 6);
            $rx = $sx;
            foreach ($colW as $ci => $cwidth) {
                $pdf->SetXY($rx, $y);
                if ($ci === 5) {
                    $pdf->SetTextColor($pctColor[0], $pctColor[1], $pctColor[2]);
                    $pdf->SetFont('helvetica', 'B', 6);
                } else {
                    $pdf->SetTextColor(60, 60, 60);
                    $pdf->SetFont('helvetica', '', 6);
                }
                $align = $ci === 1 ? 'L' : 'C';
                $pdf->Cell($cwidth, 4.5, (string)$rowData[$ci], 1, 0, $align, true);
                $rx += $cwidth;
            }
            $y += 4.5;

            if ($y > 270) break; // guard against overflow
        }

        restore_error_handler();

        // Output PDF for download
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_',
            'attendance_' . ($streamInfo['stream_name'] ?? 'stream')
            . '_' . $termLabel . $termNo . '_' . date('Y')
        ) . '.pdf';

        $content = $pdf->Output($filename, 'S');

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->setBody($content);
    }

    // ── Private helper: draw the 2-row column header for the attendance grid ──
    private function _drawAttGridHeaders(
        \TCPDF $pdf, float $y,
        float $sx, float $wNum, float $wName, float $wDay,
        float $hWk, float $hDay,
        array $weeks, array $dayKeys,
        array $cBlue, array $cHdrBg
    ): float {
        $hdrCombined = $hWk + $hDay;

        $pdf->SetLineStyle(['width' => 0.2, 'color' => [180, 200, 230]]);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor($cBlue[0], $cBlue[1], $cBlue[2]);

        // # header — spans both header rows via tall cell
        $pdf->SetFillColor($cHdrBg[0], $cHdrBg[1], $cHdrBg[2]);
        $pdf->SetXY($sx, $y);
        $pdf->Cell($wNum, $hdrCombined, '#', 1, 0, 'C', true);

        // Student Name header — spans both rows
        $pdf->SetXY($sx + $wNum, $y);
        $pdf->Cell($wName, $hdrCombined, 'STUDENT NAME', 1, 0, 'C', true);

        // Week headers (row 1 of the 2-row column header)
        $xw = $sx + $wNum + $wName;
        foreach ($weeks as $wi => $weekDays) {
            $bg = ($wi % 2 === 0) ? $cHdrBg : [215, 230, 255];
            $pdf->SetFillColor($bg[0], $bg[1], $bg[2]);
            $pdf->SetXY($xw, $y);
            $pdf->SetFont('helvetica', 'B', 6);
            $pdf->Cell($wDay * 5, $hWk, 'WEEK ' . ($wi + 1), 1, 0, 'C', true);
            $xw += $wDay * 5;
        }

        // Day / date sub-headers (row 2)
        $xd = $sx + $wNum + $wName;
        foreach ($weeks as $wi => $weekDays) {
            $bg = ($wi % 2 === 0) ? [240, 246, 255] : [225, 235, 255];
            foreach ($dayKeys as $dk) {
                // Day label (top half)
                $pdf->SetFillColor($bg[0], $bg[1], $bg[2]);
                $pdf->SetTextColor($cBlue[0], $cBlue[1], $cBlue[2]);
                $pdf->SetFont('helvetica', 'B', 5);
                $pdf->SetXY($xd, $y + $hWk);
                $pdf->Cell($wDay, $hDay / 2, $dk, 'LRT', 0, 'C', true);
                // Date (bottom half)
                $pdf->SetFont('helvetica', '', 4.5);
                $pdf->SetTextColor(100, 120, 160);
                $pdf->SetXY($xd, $y + $hWk + $hDay / 2);
                $pdf->Cell($wDay, $hDay / 2, date('d/m', strtotime($weekDays[$dk])), 'LRB', 0, 'C', true);
                $xd += $wDay;
            }
        }

        return $y + $hdrCombined;
    }

    // ================================================================
    // STUDENT SELF-VIEW — my daily attendance
    // ================================================================
    public function myDailyAttendance()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('My Daily Attendance', 'Attendance', 'My Daily Attendance');

        $userId   = (int) $this->session->get('userID');
        $streamId = (int) $this->request->getGet('stream_id');
        $termNo   = (int) $this->request->getGet('term');

        $streamInfo = $streamId ? $this->studentAttendanceModel->getStreamById($streamId) : null;

        // Load term config
        $schCatData = $this->session->get('sch_cat_data') ?? [];
        if (!empty($streamInfo) && empty($schCatData['terms'])) {
            $schCatData = $this->_loadSchCatData((int) $streamInfo['sch_id_fk']);
        }
        $termLabel = $schCatData['label'] ?? 'Term';
        $termInfo  = ($termNo && !empty($schCatData['terms'])) ? ($schCatData['terms'][$termNo] ?? null) : null;

        // Build week/date grid
        $dayNames = ['M', 'T', 'W', 'TH', 'F'];
        $weeks    = [];
        $allDates = [];
        if ($termInfo) {
            $startDt = \DateTime::createFromFormat(
                'Y-n-j',
                date('Y') . '-' . $termInfo['term_start_month'] . '-' . $termInfo['term_start_day']
            );
            if ($startDt) {
                for ($w = 0; $w < (int) $termInfo['num_of_week']; $w++) {
                    $weekRow = [];
                    foreach ($dayNames as $di => $dn) {
                        $dt = clone $startDt;
                        $dt->modify('+' . ($w * 7 + $di) . ' days');
                        $dateStr        = $dt->format('Y-m-d');
                        $weekRow[$dn]   = $dateStr;
                        $allDates[]     = $dateStr;
                    }
                    $weeks[] = $weekRow;
                }
            }
        }

        // Get student's enrol_id for this stream
        $db  = \Config\Database::connect();
        $adm = $db->table('admission')->select('admission_id')
            ->where('user_id_fk', $userId)
            ->where('admission_status', 'Active')
            ->get()->getRowArray();
        if (!$adm) {
            $adm = $db->table('admission')->select('admission_id')
                ->where('user_id_fk', $userId)
                ->get()->getRowArray();
        }

        $enrolId    = 0;
        $attendance = [];   // keyed by date → status
        if ($adm && $streamId) {
            $enrolRow = $db->table('enrolment')
                ->select('enrol_id')
                ->where('admission_id_fk', $adm['admission_id'])
                ->where('stream_id_fk', $streamId)
                ->where('enrol_status', 'Active')
                ->get()->getRowArray();
            if ($enrolRow) {
                $enrolId = (int) $enrolRow['enrol_id'];
            }
            if ($enrolId && !empty($allDates)) {
                $rows = $db->table('student_attendance')
                    ->select('attendance_date, attendance_status')
                    ->where('enrol_id_fk', $enrolId)
                    ->where('stream_id_fk', $streamId)
                    ->where('attendance_type', 'Daily')
                    ->whereIn('attendance_date', $allDates)
                    ->get()->getResultArray();
                foreach ($rows as $row) {
                    $attendance[$row['attendance_date']] = $row['attendance_status'];
                }
            }
        }

        // Holidays + summary stats
        $today       = date('Y-m-d');
        $holidays    = $streamInfo ? $this->publicHolidayModel->getByDates($allDates, (int) $streamInfo['sch_id_fk']) : [];
        $markedDates = array_values(array_filter($allDates, fn($d) => $d <= $today && !isset($holidays[$d])));
        $present = $absent = $unmarked = 0;
        foreach ($markedDates as $d) {
            $st = $attendance[$d] ?? null;
            if ($st === 'Present')    $present++;
            elseif ($st === 'Absent') $absent++;
            else                      $unmarked++;
        }
        $numMarked = count($markedDates);
        $pct       = $numMarked > 0 ? round($present / $numMarked * 100, 1) : 0.0;

        $data['_view']        = 'app/attendance/my_daily';
        $data['streamId']     = $streamId;
        $data['streamInfo']   = $streamInfo;
        $data['termNo']       = $termNo;
        $data['termLabel']    = $termLabel;
        $data['termInfo']     = $termInfo;
        $data['weeks']        = $weeks;
        $data['dayNames']     = $dayNames;
        $data['attendance']   = $attendance;
        $data['holidays']     = $holidays;
        $data['today']        = $today;
        $data['summaryStats'] = [
            'present'   => $present,
            'absent'    => $absent,
            'unmarked'  => $unmarked,
            'numMarked' => $numMarked,
            'pct'       => $pct,
        ];

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // MY DAILY ATTENDANCE — TCPDF download
    // ================================================================
    public function myDailyPdf()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userId   = (int) $this->session->get('userID');
        $streamId = (int) $this->request->getGet('stream_id');
        $termNo   = (int) $this->request->getGet('term');

        if (!$streamId || !$termNo) {
            return redirect()->to('attendance/my/daily')->with('error', 'Invalid request.');
        }

        $streamInfo = $this->studentAttendanceModel->getStreamById($streamId);
        if (!$streamInfo) {
            return redirect()->to('attendance/my/daily')->with('error', 'Stream not found.');
        }

        // School details
        $db     = \Config\Database::connect();
        $school = $db->table('school')->where('sch_id', (int)$streamInfo['sch_id_fk'])->get()->getRowArray();

        // Student name
        $userRow = $db->table('users')->select('fname, lname, oname')->where('user_id', $userId)->get()->getRowArray();
        $studentName = trim(($userRow['fname'] ?? '') . ' ' . ($userRow['oname'] ?? '') . ' ' . ($userRow['lname'] ?? ''));

        // Term config
        $schCatData = $this->session->get('sch_cat_data') ?? [];
        if (empty($schCatData['terms'])) {
            $schCatData = $this->_loadSchCatData((int) $streamInfo['sch_id_fk']);
        }
        $termLabel = $schCatData['label'] ?? 'Term';
        $termInfo  = $schCatData['terms'][$termNo] ?? null;
        if (!$termInfo) {
            return redirect()->to("attendance/my/daily?stream_id={$streamId}&term={$termNo}")->with('error', 'Term not configured.');
        }

        // Build week/date grid
        $dayNames = ['M', 'T', 'W', 'TH', 'F'];
        $weeks    = [];
        $allDates = [];
        $startDt  = \DateTime::createFromFormat('Y-n-j', date('Y') . '-' . $termInfo['term_start_month'] . '-' . $termInfo['term_start_day']);
        if (!$startDt) {
            return redirect()->to("attendance/my/daily?stream_id={$streamId}&term={$termNo}")->with('error', 'Invalid term date.');
        }
        for ($w = 0; $w < (int) $termInfo['num_of_week']; $w++) {
            $weekRow = [];
            foreach ($dayNames as $di => $dn) {
                $dt = clone $startDt;
                $dt->modify('+' . ($w * 7 + $di) . ' days');
                $dateStr      = $dt->format('Y-m-d');
                $weekRow[$dn] = $dateStr;
                $allDates[]   = $dateStr;
            }
            $weeks[] = $weekRow;
        }

        // Get enrol_id and attendance
        $adm = $db->table('admission')->select('admission_id')->where('user_id_fk', $userId)->where('admission_status', 'Active')->get()->getRowArray();
        if (!$adm) $adm = $db->table('admission')->select('admission_id')->where('user_id_fk', $userId)->get()->getRowArray();

        $enrolId    = 0;
        $attendance = [];
        if ($adm) {
            $enrolRow = $db->table('enrolment')->select('enrol_id')
                ->where('admission_id_fk', $adm['admission_id'])
                ->where('stream_id_fk', $streamId)
                ->where('enrol_status', 'Active')
                ->get()->getRowArray();
            if ($enrolRow) $enrolId = (int) $enrolRow['enrol_id'];
            if ($enrolId && !empty($allDates)) {
                $rows = $db->table('student_attendance')
                    ->select('attendance_date, attendance_status')
                    ->where('enrol_id_fk', $enrolId)
                    ->where('stream_id_fk', $streamId)
                    ->where('attendance_type', 'Daily')
                    ->whereIn('attendance_date', $allDates)
                    ->get()->getResultArray();
                foreach ($rows as $row) {
                    $attendance[$row['attendance_date']] = $row['attendance_status'];
                }
            }
        }

        // Holidays + summary stats
        $today       = date('Y-m-d');
        $holidays    = $this->publicHolidayModel->getByDates($allDates, (int) $streamInfo['sch_id_fk']);
        $markedDates = array_values(array_filter($allDates, fn($d) => $d <= $today && !isset($holidays[$d])));
        $present = $absent = $unmarked = 0;
        foreach ($markedDates as $d) {
            $st = $attendance[$d] ?? null;
            if ($st === 'Present')    $present++;
            elseif ($st === 'Absent') $absent++;
            else                      $unmarked++;
        }
        $numMarked = count($markedDates);
        $pct       = $numMarked > 0 ? round($present / $numMarked * 100, 1) : 0.0;

        // ── TCPDF ────────────────────────────────────────────────────────────
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
        set_error_handler(static function (int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP') || str_contains($errstr, 'gd-png') || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji SMS');
        $pdf->SetAuthor('Navuli Fiji School Management System');
        $pdf->SetTitle($termLabel . ' ' . $termNo . ' Daily Attendance — ' . $studentName);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);

        // A4 landscape: 297×210 mm
        $sx  = 12.0;
        $cw  = 273.0;   // 297 - 2*12
        $cBlue  = [26,  86,  219];
        $cLtBl  = [147, 197, 253];

        $pdf->AddPage();
        $y = 12.0;

        // Double border
        $pdf->SetLineStyle(['width' => 1.0, 'color' => $cBlue]);
        $pdf->Rect(8, 8, 281, 194, 'D');
        $pdf->SetLineStyle(['width' => 0.3, 'color' => $cLtBl]);
        $pdf->Rect(10, 10, 277, 190, 'D');

        // ── Header block (same dynamic-width logo+text+logo pattern) ────────
        $logoW    = 18.0;
        $navLogoW = 16.0;
        $gap      = 2.0;

        $streamLabelH  = ($streamInfo['stream_name'] ?? '');
        if (!empty($streamInfo['level_name'])) $streamLabelH .= ' (' . $streamInfo['level_name'] . ')';
        $contactPartsH = array_filter([
            $school['sch_address'] ?? '',
            !empty($school['sch_phone']) ? 'Ph: ' . $school['sch_phone'] : '',
            $school['sch_email'] ?? '',
        ]);

        $pdf->SetFont('helvetica', 'B', 11);
        $tw1 = $pdf->GetStringWidth(strtoupper($school['sch_name'] ?? ''));
        $pdf->SetFont('helvetica', 'B', 7);
        $tw2 = $pdf->GetStringWidth(strtoupper($streamLabelH) . ' — STUDENT DAILY ATTENDANCE');
        $pdf->SetFont('helvetica', '', 7);
        $tw3 = $pdf->GetStringWidth(strtoupper($termLabel) . ' ' . $termNo . '  |  YEAR ' . date('Y'));
        $pdf->SetFont('helvetica', 'B', 7);
        $tw4 = $pdf->GetStringWidth('STUDENT: ' . strtoupper($studentName));
        $pdf->SetFont('helvetica', '', 6);
        $tw5 = !empty($contactPartsH) ? $pdf->GetStringWidth(implode('  |  ', $contactPartsH)) : 0;

        $textW  = max($tw1, $tw2, $tw3, $tw4, $tw5) + 8.0;
        $blockW = min($logoW + $gap + $textW + $gap + $navLogoW, $cw);
        $blockX = $sx + ($cw - $blockW) / 2;
        $textX  = $blockX + $logoW + $gap;
        $navLogoX = $blockX + $blockW - $navLogoW;

        if (!empty($school['sch_logo'])) {
            $logoPath = FCPATH . 'uploads/school/logo/' . $school['sch_logo'];
            if (file_exists($logoPath)) $pdf->Image($logoPath, $blockX, $y, $logoW, $logoW, '', '', 'T', false, 300);
        }
        $navuliLogo = FCPATH . 'icon.png';
        if (file_exists($navuliLogo)) $pdf->Image($navuliLogo, $navLogoX, $y, $navLogoW, $navLogoW, '', '', 'T', false, 300);

        $pdf->SetXY($textX, $y + 0.5);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($textW, 5, strtoupper($school['sch_name'] ?? ''), 0, 0, 'C');

        $pdf->SetXY($textX, $y + 6.5);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell($textW, 3.5, strtoupper($streamLabelH) . ' — STUDENT DAILY ATTENDANCE', 0, 0, 'C');

        $pdf->SetXY($textX, $y + 10.5);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell($textW, 3.5, strtoupper($termLabel) . ' ' . $termNo . '  |  YEAR ' . date('Y'), 0, 0, 'C');

        $pdf->SetXY($textX, $y + 14.5);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($textW, 3.5, 'STUDENT: ' . strtoupper($studentName), 0, 0, 'C');

        if (!empty($contactPartsH)) {
            $pdf->SetXY($textX, $y + 18.5);
            $pdf->SetFont('helvetica', '', 6);
            $pdf->SetTextColor(150, 150, 150);
            $pdf->Cell($textW, 3, implode('  |  ', $contactPartsH), 0, 0, 'C');
        }

        $y += 24;
        $pdf->SetLineStyle(['width' => 0.7, 'color' => $cBlue]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => $cLtBl]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 5;

        // ── Summary stat boxes ───────────────────────────────────────────────
        $pctColor3 = $pct >= 80 ? [5,150,105] : ($pct >= 70 ? [217,119,6] : [185,28,28]);
        $sBoxes = [
            ['Days Marked',  $numMarked, [80,80,80]],
            ['Present',      $present,   [5,150,105]],
            ['Absent',       $absent,    [185,28,28]],
            ['Not Marked',   $unmarked,  [120,120,120]],
            ['Attendance %', $pct . '%', $pctColor3],
        ];
        $bW = $cw / count($sBoxes);
        $bH = 14.0;
        foreach ($sBoxes as $bi => $box) {
            $bx = $sx + $bi * $bW;
            $pdf->SetFillColor(248, 250, 255);
            $pdf->SetLineStyle(['width' => 0.2, 'color' => [200,210,230]]);
            $pdf->Rect($bx + 1, $y, $bW - 2, $bH, 'DF');
            $pdf->SetXY($bx + 1, $y + 1.5);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetTextColor($box[2][0], $box[2][1], $box[2][2]);
            $pdf->Cell($bW - 2, 6, (string)$box[1], 0, 0, 'C');
            $pdf->SetXY($bx + 1, $y + 8);
            $pdf->SetFont('helvetica', '', 5.5);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->Cell($bW - 2, 3.5, strtoupper($box[0]), 0, 0, 'C');
        }
        $y += $bH + 5;

        // ── Attendance grid: rows = days (M T W TH F), cols = weeks ─────────
        $numWeeks = count($weeks);
        $dayLabelW = 12.0;
        $cellW     = min(($cw - $dayLabelW) / max($numWeeks, 1), 20.0);
        $cellH     = 10.0;
        $cGreen    = [5,  150, 105];
        $cRed      = [185, 28,  28];
        $cGray     = [150, 150, 150];
        $cBgGreen  = [209, 250, 229];
        $cBgRed    = [254, 226, 226];
        $cBgGray   = [243, 244, 246];
        $cBgFut    = [249, 250, 251];

        // Week header row
        $pdf->SetXY($sx + $dayLabelW, $y);
        foreach ($weeks as $wi => $weekDays) {
            $wx = $sx + $dayLabelW + $wi * $cellW;
            $bg = $wi % 2 === 0 ? [255,248,230] : [255,244,210];
            $pdf->SetFillColor($bg[0], $bg[1], $bg[2]);
            $pdf->SetLineStyle(['width' => 0.2, 'color' => [210,210,210]]);
            $pdf->SetXY($wx, $y);
            $pdf->SetFont('helvetica', 'B', 7);
            $pdf->SetTextColor(180, 100, 0);
            $pdf->Cell($cellW, $cellH * 0.7, 'W' . ($wi + 1), 1, 0, 'C', true);
        }
        $y += $cellH * 0.7;

        // Day rows
        foreach ($dayNames as $dk) {
            // Day label
            $pdf->SetXY($sx, $y);
            $pdf->SetFillColor(238, 242, 255);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(26, 86, 219);
            $pdf->Cell($dayLabelW, $cellH, $dk, 1, 0, 'C', true);

            foreach ($weeks as $wi => $weekDays) {
                $date     = $weekDays[$dk] ?? '';
                $isFuture = $date > $today;
                $status   = $isFuture ? null : ($attendance[$date] ?? null);
                $wx       = $sx + $dayLabelW + $wi * $cellW;

                if (isset($holidays[$date])) {
                    $pdf->SetFillColor(237, 233, 254);
                    $pdf->SetTextColor(109, 40, 217);
                    $text = 'H';
                } elseif ($isFuture) {
                    $pdf->SetFillColor($cBgFut[0], $cBgFut[1], $cBgFut[2]);
                    $pdf->SetTextColor($cGray[0], $cGray[1], $cGray[2]);
                    $text = '';
                } elseif ($status === 'Present') {
                    $pdf->SetFillColor($cBgGreen[0], $cBgGreen[1], $cBgGreen[2]);
                    $pdf->SetTextColor($cGreen[0], $cGreen[1], $cGreen[2]);
                    $text = '✓';
                } elseif ($status === 'Absent') {
                    $pdf->SetFillColor($cBgRed[0], $cBgRed[1], $cBgRed[2]);
                    $pdf->SetTextColor($cRed[0], $cRed[1], $cRed[2]);
                    $text = '✗';
                } else {
                    $pdf->SetFillColor($cBgGray[0], $cBgGray[1], $cBgGray[2]);
                    $pdf->SetTextColor($cGray[0], $cGray[1], $cGray[2]);
                    $text = '—';
                }

                $pdf->SetLineStyle(['width' => 0.2, 'color' => [210,210,210]]);
                $pdf->SetFont('dejavusans', 'B', 9);
                $pdf->SetXY($wx, $y);
                $pdf->Cell($cellW, $cellH, $text, 1, 0, 'C', true);
            }
            $y += $cellH;
        }

        // ── Charts section: donut (left) + weekly bars (right) ──────────────
        $y += 5;
        $chartAreaH = 52.0;
        $donutW     = $cw * 0.38;
        $barW       = $cw - $donutW - 6;
        $barX       = $sx + $donutW + 6;

        // Section labels
        $pdf->SetFont('helvetica', 'B', 6.5);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->SetXY($sx, $y);
        $pdf->Cell($donutW, 4, 'OVERALL ATTENDANCE', 0, 0, 'C');
        $pdf->SetXY($barX, $y);
        $pdf->Cell($barW, 4, 'WEEKLY ATTENDANCE RATE (%)', 0, 0, 'C');
        $y += 5;

        // ── Donut chart ───────────────────────────────────────────────────
        $total3 = $present + $absent + $unmarked;
        $r      = 20.0;                  // outer radius
        $ri     = 11.0;                  // inner radius (hole)
        $cx     = $sx + $donutW / 2;
        $cy     = $y + $chartAreaH / 2 - 2;

        if ($total3 > 0) {
            $segments = [
                ['val' => $present,  'fill' => [5,150,105],  'label' => 'Present'],
                ['val' => $absent,   'fill' => [239,68,68],  'label' => 'Absent'],
                ['val' => $unmarked, 'fill' => [209,213,219],'label' => 'Not Marked'],
            ];
            $angle = 90.0;  // start from top
            foreach ($segments as $seg) {
                if ($seg['val'] <= 0) continue;
                $sweep  = 360.0 * $seg['val'] / $total3;
                $endAng = $angle + $sweep;
                $pdf->SetFillColor($seg['fill'][0], $seg['fill'][1], $seg['fill'][2]);
                $pdf->SetDrawColor(255, 255, 255);
                $pdf->SetLineStyle(['width' => 0.4, 'color' => [255,255,255]]);
                $pdf->PieSector($cx, $cy, $r, $angle, $endAng, 'F', false, 0);
                $angle = $endAng;
            }
            // White donut hole
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->Circle($cx, $cy, $ri, 0, 360, 'F');
            // Center text
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetTextColor(26, 86, 219);
            $pdf->SetXY($cx - 12, $cy - 3.5);
            $pdf->Cell(24, 4, $pct . '%', 0, 0, 'C');
            $pdf->SetFont('helvetica', '', 5);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->SetXY($cx - 12, $cy + 1);
            $pdf->Cell(24, 3, 'Attendance', 0, 0, 'C');
        } else {
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Circle($cx, $cy, $r, 0, 360, 'F');
            $pdf->SetFont('helvetica', '', 6);
            $pdf->SetTextColor(160, 160, 160);
            $pdf->SetXY($cx - 15, $cy - 2);
            $pdf->Cell(30, 4, 'No data', 0, 0, 'C');
        }

        // Legend below donut
        $legY  = $cy + $r + 3;
        $dots  = [
            ['Present',    [5,150,105]],
            ['Absent',     [239,68,68]],
            ['Not Marked', [209,213,219]],
        ];
        $legX = $sx + 4;
        foreach ($dots as $dot) {
            $pdf->SetFillColor($dot[1][0], $dot[1][1], $dot[1][2]);
            $pdf->Rect($legX, $legY + 0.5, 3, 3, 'F');
            $pdf->SetFont('helvetica', '', 5.5);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->SetXY($legX + 4, $legY);
            $pdf->Cell(20, 4, $dot[0], 0, 0, 'L');
            $legX += 26;
        }

        // ── Weekly bar chart ──────────────────────────────────────────────
        $barChartH = $chartAreaH - 8;  // reserve 8mm for week labels
        $nw        = count($weeks);
        if ($nw > 0) {
            $bw  = ($barW - 4) / $nw;
            $pdf->SetFont('helvetica', '', 5);
            // Grid lines at 0, 50, 100%
            foreach ([0, 25, 50, 75, 100] as $mark) {
                $lineY = $y + $barChartH - ($barChartH * $mark / 100);
                $pdf->SetLineStyle(['width' => 0.1, 'color' => [220,220,220]]);
                $pdf->Line($barX + 8, $lineY, $barX + $barW, $lineY);
                $pdf->SetTextColor(170, 170, 170);
                $pdf->SetXY($barX, $lineY - 1.5);
                $pdf->Cell(7, 3, $mark . '%', 0, 0, 'R');
            }
            foreach ($weeks as $wi => $weekDays) {
                $wP = $wA = 0;
                foreach ($weekDays as $dt) {
                    if ($dt > $today) continue;
                    $st = $attendance[$dt] ?? null;
                    if ($st === 'Present')    $wP++;
                    elseif ($st === 'Absent') $wA++;
                }
                $wTotal = $wP + $wA;
                $wPct   = $wTotal > 0 ? round($wP / $wTotal * 100, 1) : 0;
                $bh     = $barChartH * $wPct / 100;
                $bx2    = $barX + 8 + $wi * $bw + $bw * 0.15;
                $bwInner = $bw * 0.7;
                $by2    = $y + $barChartH - $bh;

                if ($wPct >= 90)      $pdf->SetFillColor(5,  150, 105);
                elseif ($wPct >= 80)  $pdf->SetFillColor(16, 185, 129);
                elseif ($wPct >= 70)  $pdf->SetFillColor(245,158, 11);
                else                  $pdf->SetFillColor(239, 68,  68);

                if ($bh > 0) $pdf->Rect($bx2, $by2, $bwInner, $bh, 'F');

                // % label above bar
                if ($wPct > 0) {
                    $pdf->SetFont('helvetica', 'B', 5);
                    $pdf->SetTextColor(60, 60, 60);
                    $pdf->SetXY($bx2 - 1, max($by2 - 4, $y));
                    $pdf->Cell($bwInner + 2, 3.5, $wPct . '%', 0, 0, 'C');
                }

                // Week label below
                $pdf->SetFont('helvetica', '', 5);
                $pdf->SetTextColor(100, 100, 100);
                $pdf->SetXY($bx2 - 1, $y + $barChartH + 1);
                $pdf->Cell($bwInner + 2, 3, 'W' . ($wi + 1), 0, 0, 'C');
            }
        }

        // Footer text (no divider line)
        $yF = 192;
        $pdf->SetXY($sx, $yF);
        $pdf->SetFont('helvetica', 'I', 5.5);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->Cell($cw / 2, 4, 'Student: ' . $studentName . '  |  ' . $termLabel . ' ' . $termNo . '  |  ' . ($streamInfo['stream_name'] ?? ''), 0, 0, 'L');
        $pdf->SetXY($sx + $cw / 2, $yF);
        $pdf->Cell($cw / 2, 4, 'Generated by Navuli Fiji School Management System — ' . date('d M Y H:i'), 0, 0, 'R');

        restore_error_handler();

        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_',
            'my_attendance_' . $termLabel . $termNo . '_' . date('Y') . '_' . ($streamInfo['stream_name'] ?? 'stream')
        ) . '.pdf';

        $content = $pdf->Output($filename, 'S');
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->setBody($content);
    }

    // ================================================================
    // PUBLIC HOLIDAYS — add / remove (admin / teacher)
    // ================================================================
    public function addHoliday()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }
        $schID = (int) $this->session->get('schID');
        $date  = trim($this->request->getPost('holiday_date') ?? '');
        $name  = trim($this->request->getPost('holiday_name') ?? '');

        if (!$date || !$name || !\DateTime::createFromFormat('Y-m-d', $date)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid date or name.']);
        }

        $db = \Config\Database::connect();
        $existing = $db->table('public_holidays')
            ->where('holiday_date', $date)
            ->where($schID > 0 ? 'sch_id_fk' : 'sch_id_fk IS NULL', $schID > 0 ? $schID : null, $schID > 0)
            ->get()->getRowArray();

        if ($existing) {
            $db->table('public_holidays')->where('holiday_id', $existing['holiday_id'])->update(['holiday_name' => $name]);
        } else {
            $db->table('public_holidays')->insert([
                'holiday_date' => $date,
                'holiday_name' => $name,
                'sch_id_fk'   => $schID > 0 ? $schID : null,
            ]);
        }

        return $this->response->setJSON(['success' => true, 'holiday_id' => $db->insertID(), 'date' => $date, 'name' => $name]);
    }

    public function removeHoliday(int $holidayId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }
        $schID = (int) $this->session->get('schID');
        $db    = \Config\Database::connect();
        $row   = $db->table('public_holidays')->where('holiday_id', $holidayId)->get()->getRowArray();

        if (!$row) {
            return $this->response->setJSON(['success' => false, 'message' => 'Holiday not found.']);
        }
        if ($schID > 0 && (int)($row['sch_id_fk'] ?? 0) !== $schID) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $db->table('public_holidays')->where('holiday_id', $holidayId)->delete();
        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // AJAX — duplicate check before loading students
    // ================================================================
    public function checkExists()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false]);
        }

        $streamId = (int)   $this->request->getGet('stream_id');
        $date     = (string) $this->request->getGet('date');

        if (!$streamId || !$date) {
            return $this->response->setJSON(['exists' => false, 'count' => 0]);
        }

        $count = $this->studentAttendanceModel->checkExists($streamId, $date);

        return $this->response->setJSON([
            'exists' => $count > 0,
            'count'  => $count,
        ]);
    }

    // ================================================================
    // AJAX — load students for a given stream (add page)
    // ================================================================
    public function getStudentsByStream()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $streamId = (int) $this->request->getGet('stream_id');

        if (!$streamId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid stream selected.']);
        }

        $year     = (int) date('Y');
        $students = $this->studentAttendanceModel->getEnrolledStudents($streamId, $year);

        if (empty($students)) {
            return $this->response->setJSON([
                'success' => true,
                'count'   => 0,
                'html'    => '<tr><td colspan="5" class="text-center py-8 text-muted">
                                <i class="ki-duotone ki-information-5 fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                No students are enrolled in this stream for ' . $year . '.
                              </td></tr>',
            ]);
        }

        $html = view('app/attendance/partials/student_rows', [
            'students' => $students,
            'year'     => $year,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'count'   => count($students),
            'html'    => $html,
        ]);
    }

    // ================================================================
    // AJAX — FullCalendar event source for a stream
    // ================================================================
    public function getCalendarEvents()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([]);
        }

        $streamId = (int) $this->request->getGet('stream_id');

        if (!$streamId) {
            return $this->response->setJSON([]);
        }

        $rows   = $this->studentAttendanceModel->getAttendanceDatesForStream($streamId);
        $events = [];

        foreach ($rows as $row) {
            $total   = (int) $row['student_count'];
            $present = (int) $row['present_count'];
            $absent  = (int) $row['absent_count'];
            $pct     = $total > 0 ? (int) round(($present / $total) * 100) : 0;

            $events[] = [
                'id'              => $streamId . '_' . $row['attendance_date'],
                'title'           => $total . ' student' . ($total !== 1 ? 's' : ''),
                'start'           => $row['attendance_date'],
                'allDay'          => true,
                'backgroundColor' => '#50cd89',
                'borderColor'     => '#47be7d',
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'stream_id'     => $streamId,
                    'att_date'      => $row['attendance_date'],
                    'student_count' => $total,
                    'present_count' => $present,
                    'absent_count'  => $absent,
                    'pct_present'   => $pct,
                ],
            ];
        }

        return $this->response->setJSON($events);
    }

    // ================================================================
    // AJAX — attendance detail for a stream+date (modal content)
    // ================================================================
    public function getDateDetail()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $streamId = (int)   $this->request->getGet('stream_id');
        $date     = (string) $this->request->getGet('date');

        if (!$streamId || !$date) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing parameters.']);
        }

        $records = $this->studentAttendanceModel->getAttendanceForStreamDate($streamId, $date);
        $stream  = $this->studentAttendanceModel->getStreamById($streamId);

        if (empty($records)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No attendance records found for this date.']);
        }

        $attIds   = array_column($records, 'stud_att_id');
        $allFiles = $this->studentAttendanceFileModel->getFilesByAttendanceIds($attIds);

        $filesByAtt = [];
        foreach ($allFiles as $f) {
            $filesByAtt[$f['stud_att_id_fk']][] = $f;
        }
        foreach ($records as &$rec) {
            $rec['files'] = $filesByAtt[$rec['stud_att_id']] ?? [];
        }
        unset($rec);

        $html = view('app/attendance/partials/date_detail', [
            'records' => $records,
            'stream'  => $stream,
            'date'    => $date,
        ]);

        return $this->response->setJSON(['success' => true, 'html' => $html]);
    }

    // ================================================================
    // POST — update a single attendance record (status + note)
    // ================================================================
    public function updateRecord(int $id)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $status = trim((string) $this->request->getPost('attendance_status'));
        $note   = trim((string) $this->request->getPost('attendance_note'));

        if (!$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status is required.']);
        }

        $ok = $this->studentAttendanceModel->updateAttendance($id, [
            'attendance_status' => $status,
            'attendance_note'   => $note,
        ]);

        return $this->response->setJSON(['success' => (bool) $ok]);
    }

    // ================================================================
    // POST — delete a single attendance record (and its files)
    // ================================================================
    public function deleteRecord(int $id)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $files = $this->studentAttendanceFileModel->getFilesByAttendance($id);
        $this->removePhysicalFiles($files);
        $this->studentAttendanceFileModel->deleteAllForAttendanceIds([$id]);

        $ok = $this->studentAttendanceModel->deleteAttendance($id);

        return $this->response->setJSON(['success' => (bool) $ok]);
    }

    // ================================================================
    // POST — delete ALL attendance for a stream+date
    // ================================================================
    public function deleteAllForDate()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $streamId = (int)   $this->request->getPost('stream_id');
        $date     = (string) $this->request->getPost('date');

        if (!$streamId || !$date) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing parameters.']);
        }

        $ids   = $this->studentAttendanceModel->getIdsByStreamDate($streamId, $date);
        $files = $this->studentAttendanceFileModel->getFilesByAttendanceIds($ids);
        $this->removePhysicalFiles($files);
        $this->studentAttendanceFileModel->deleteAllForAttendanceIds($ids);
        $this->studentAttendanceModel->deleteAllForStreamDate($streamId, $date);

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // POST — upload additional files to an existing attendance record
    // ================================================================
    public function uploadFiles(int $attId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $uploadPath = FCPATH . 'uploads/attendance/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        try {
            $files = $this->request->getFileMultiple('upload_files');
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'No files received.']);
        }

        if (empty($files)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No files received.']);
        }

        $saved = [];

        foreach ($files as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                continue;
            }

            $ext     = strtolower($file->getClientExtension());
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $file->move($uploadPath, $newName);

            $fileId = $this->studentAttendanceFileModel->addFile([
                'stud_att_id_fk'    => $attId,
                'stud_att_file_src' => $newName,
                'stud_att_file_type'=> $ext,
            ]);

            if ($fileId) {
                $saved[] = [
                    'stud_att_file_id'  => $fileId,
                    'stud_att_id_fk'    => $attId,
                    'stud_att_file_src' => $newName,
                    'stud_att_file_type'=> $ext,
                ];
            }
        }

        return $this->response->setJSON(['success' => true, 'files' => $saved]);
    }

    // ================================================================
    // POST — delete a single file
    // ================================================================
    public function deleteFile(int $fileId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $file = $this->studentAttendanceFileModel->deleteFile($fileId);

        if ($file) {
            $physicalPath = FCPATH . 'uploads/attendance/' . $file['stud_att_file_src'];
            if (file_exists($physicalPath)) {
                unlink($physicalPath);
            }
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'File not found.']);
    }

    // ================================================================
    // POST — save attendance (full-page form redirect version)
    // ================================================================
    public function save()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $roleCatID = (int) $this->session->get('roleCatID');
        $userID    = (int) $this->session->get('userID');
        $schID     = (int) $this->session->get('schID');

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            return redirect()->to('attendance/add')->with('error', 'You are not authorised to add student attendance.');
        }

        $streamId   = (int)   $this->request->getPost('stream_id');
        $attDate    = (string) $this->request->getPost('attendance_date');
        $attendance = $this->request->getPost('attendance') ?? [];

        if (!$streamId || !$attDate || empty($attendance)) {
            return redirect()->to('attendance/add')->with('error', 'Please select a stream and date, then load students before saving.');
        }

        $admissionId = $this->resolveAdmissionId($roleCatID, $userID, $schID);

        if ($admissionId === false) {
            return redirect()->to('attendance/add')->with('error', 'No active admission found. Cannot save attendance.');
        }

        $uploadPath = FCPATH . 'uploads/attendance/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $savedCount = $this->persistAttendance($streamId, $attDate, $attendance, $admissionId, $uploadPath);

        return redirect()->to('attendance/add')->with('success', "Attendance saved successfully for {$savedCount} student(s).");
    }

    // ================================================================
    // POST — save attendance via AJAX (calendar add modal)
    // ================================================================
    public function saveAjax()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated.']);
        }

        $roleCatID = (int) $this->session->get('roleCatID');
        $userID    = (int) $this->session->get('userID');
        $schID     = (int) $this->session->get('schID');

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            return $this->response->setJSON(['success' => false, 'message' => 'You are not authorised to add attendance.']);
        }

        $streamId   = (int)   $this->request->getPost('stream_id');
        $attDate    = (string) $this->request->getPost('attendance_date');
        $attendance = $this->request->getPost('attendance') ?? [];

        if (!$streamId || !$attDate || empty($attendance)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required data. Ensure stream, date and students are provided.']);
        }

        $admissionId = $this->resolveAdmissionId($roleCatID, $userID, $schID);

        if ($admissionId === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'No active admission found. Cannot save attendance.']);
        }

        $uploadPath = FCPATH . 'uploads/attendance/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $savedCount = $this->persistAttendance($streamId, $attDate, $attendance, $admissionId, $uploadPath);

        return $this->response->setJSON([
            'success' => true,
            'count'   => $savedCount,
            'message' => "Attendance saved for {$savedCount} student(s).",
        ]);
    }

    // ================================================================
    // PRIVATE — resolve teacher/admin admission ID
    // Returns false only when Teacher has no active admission
    // ================================================================
    private function resolveAdmissionId(int $roleCatID, int $userID, int $schID): int|false
    {
        if ($roleCatID === self::TEACHER_ROLE_CAT) {
            $admission = $this->studentAttendanceModel->getTeacherActiveAdmission($userID, $schID);
            if (!$admission) {
                return false;
            }
            return (int) $admission['admission_id'];
        }

        $admission = $this->studentAttendanceModel->getTeacherActiveAdmission($userID, $schID);
        return $admission ? (int) $admission['admission_id'] : 0;
    }

    // ================================================================
    // PRIVATE — loop and persist attendance rows + files
    // ================================================================
    private function persistAttendance(int $streamId, string $attDate, array $attendance, int $admissionId, string $uploadPath): int
    {
        $savedCount = 0;

        foreach ($attendance as $rawEnrolId => $attData) {
            $enrolId = (int) $rawEnrolId;
            $status  = trim($attData['status'] ?? 'Present');
            $note    = trim($attData['note']   ?? '');

            $attId = $this->studentAttendanceModel->addAttendance([
                'enrol_id_fk'       => $enrolId,
                'stream_id_fk'      => $streamId,
                'admission_id_fk'   => $admissionId,
                'attendance_date'   => $attDate,
                'attendance_note'   => $note,
                'attendance_type'   => 'Daily',
                'attendance_status' => $status,
            ]);

            if ($attId) {
                $savedCount++;
                $this->processStudentFiles($attId, $enrolId, $uploadPath);
            }
        }

        return $savedCount;
    }

    // ================================================================
    // PRIVATE — handle file uploads for one student's attendance record
    // ================================================================
    private function processStudentFiles(int $attId, int $enrolId, string $uploadPath): void
    {
        $fieldName = 'files_' . $enrolId;

        try {
            $files = $this->request->getFileMultiple($fieldName);
        } catch (\Throwable $e) {
            return;
        }

        if (empty($files)) {
            return;
        }

        foreach ($files as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                continue;
            }

            $ext     = strtolower($file->getClientExtension());
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $file->move($uploadPath, $newName);

            $this->studentAttendanceFileModel->addFile([
                'stud_att_id_fk'    => $attId,
                'stud_att_file_src' => $newName,
                'stud_att_file_type'=> $ext,
            ]);
        }
    }

    // ================================================================
    // PRIVATE — delete physical files from disk
    // ================================================================
    private function removePhysicalFiles(array $files): void
    {
        foreach ($files as $file) {
            $path = FCPATH . 'uploads/attendance/' . $file['stud_att_file_src'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
