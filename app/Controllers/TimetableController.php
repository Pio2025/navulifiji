<?php

namespace App\Controllers;

use App\Models\TimetableModel;
use App\Models\TimetableEntryModel;
use App\Models\TimetableTemplateModel;
use App\Models\TimetableTemplateSlotModel;

class TimetableController extends BaseController
{
    private TimetableModel             $ttModel;
    private TimetableEntryModel        $ttEntryModel;
    private TimetableTemplateModel     $ttTemplateModel;
    private TimetableTemplateSlotModel $ttSlotModel;

    private function boot(): void
    {
        $this->ttModel         = new TimetableModel();
        $this->ttEntryModel    = new TimetableEntryModel();
        $this->ttTemplateModel = new TimetableTemplateModel();
        $this->ttSlotModel     = new TimetableTemplateSlotModel();
    }

    // =========================================================================
    // LISTING
    // =========================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Timetable', 'Timetable', 'Timetable List');

        if (!$this->require_access('_timetable_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = (int) $this->session->get('schID');

        $timetables = $isSuperAdmin
            ? $this->ttModel->getAllWithDetails()
            : $this->ttModel->getBySchool($schId);

        $data['timetables']        = $timetables;
        $data['isSuperAdmin']      = $isSuperAdmin;
        $data['canAdd']            = $isSuperAdmin || $this->grant_access('_add_timetable');
        $data['canEdit']           = $isSuperAdmin || $this->grant_access('_edit_timetable');
        $data['canDelete']         = $isSuperAdmin || $this->grant_access('_remove_timetable');
        $data['hasSchoolTemplate'] = !$isSuperAdmin && $this->schoolHasTemplate($schId);
        $data['_view']             = 'app/timetable/index';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // SETUP (timetable structure configuration per school)
    // =========================================================================

    public function setup()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Timetable Setup', 'Timetable', 'Configure Structure');

        if (!$this->require_access('_edit_timetable')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId    = (int) $this->session->get('schID');
        $existing = $this->getSchoolTemplate($schId);

        // Reconstruct config values from existing template slots
        $cfg = $this->parseTemplateConfig($existing);

        $data['existing'] = $existing;
        $data['cfg']      = $cfg;
        $data['_view']    = 'app/timetable/setup';
        return view('app/layouts/main', $data);
    }

    public function saveSetup()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();

        if (!$this->require_access('_edit_timetable')) {
            return redirect()->to('timetable')->with('error', 'Access denied.');
        }

        $schId        = (int) $this->session->get('schID');
        if ($schId === 0) {
            return redirect()->to('timetable/setup')->with('error', 'Setup must be done within a specific school context.');
        }

        $numDays      = max(1, min(10, (int) ($this->request->getPost('num_days')         ?: 6)));
        $morningPds   = max(1, min(8,  (int) ($this->request->getPost('morning_periods')  ?: 3)));
        $middayPds    = max(0, min(8,  (int) ($this->request->getPost('midday_periods')   ?: 3)));
        $afternoonPds = max(0, min(8,  (int) ($this->request->getPost('afternoon_periods') ?: 3)));
        $pdDuration   = max(20, min(120, (int) ($this->request->getPost('period_duration')  ?: 40)));
        $recessDur    = max(5,  min(60,  (int) ($this->request->getPost('recess_duration')  ?: 20)));
        $lunchDur     = max(10, min(120, (int) ($this->request->getPost('lunch_duration')   ?: 40)));
        $startTime    = $this->request->getPost('start_time') ?: '08:00';

        $db = \Config\Database::connect();

        // Update existing or create new school-specific template
        $existing = $db->table('timetable_template')->where('sch_id_fk', $schId)->get()->getRowArray();
        if ($existing) {
            $templateId = (int) $existing['template_id'];
            $db->table('timetable_template')->where('template_id', $templateId)->update([
                'template_name' => 'School Structure',
                'num_days'      => $numDays,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            $db->table('timetable_template_slot')->where('template_id_fk', $templateId)->delete();
        } else {
            $db->table('timetable_template')->insert([
                'template_name' => 'School Structure',
                'sch_cat_id_fk' => 0,
                'sch_id_fk'     => $schId,
                'is_default'    => 1,
                'num_days'      => $numDays,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            $templateId = $db->insertID();
        }

        // Generate slots from config
        $order   = 1;
        $timePtr = strtotime(date('Y-m-d') . ' ' . $startTime . ':00');

        $insertSlot = function (string $label, bool $teaching, int $duration) use ($db, $templateId, &$order, &$timePtr) {
            $end = $timePtr + $duration * 60;
            $db->table('timetable_template_slot')->insert([
                'template_id_fk' => $templateId,
                'slot_order'     => $order++,
                'slot_type'      => $teaching ? 'period' : 'break',
                'label'          => $label,
                'is_teaching'    => $teaching ? 1 : 0,
                'start_time'     => date('H:i:s', $timePtr),
                'end_time'       => date('H:i:s', $end),
            ]);
            $timePtr = $end;
        };

        for ($i = 1; $i <= $morningPds; $i++) {
            $insertSlot('Period ' . $i, true, $pdDuration);
        }
        $insertSlot('Recess', false, $recessDur);

        for ($i = $morningPds + 1; $i <= $morningPds + $middayPds; $i++) {
            $insertSlot('Period ' . $i, true, $pdDuration);
        }

        if ($afternoonPds > 0) {
            $insertSlot('Lunch', false, $lunchDur);
            for ($i = $morningPds + $middayPds + 1; $i <= $morningPds + $middayPds + $afternoonPds; $i++) {
                $insertSlot('Period ' . $i, true, $pdDuration);
            }
        }

        $this->logAction('Timetable Setup Saved', "Configured school timetable: {$numDays} days, " . ($morningPds + $middayPds + $afternoonPds) . ' periods');

        return redirect()->to('timetable')->with('success', 'Timetable structure saved. Use this template when creating new timetables.');
    }

    // =========================================================================
    // ADD
    // =========================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Add Timetable', 'Timetable', 'Add Timetable');

        if (!$this->require_access('_add_timetable')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = (int) $this->session->get('schID');

        $data['streams']           = $isSuperAdmin
            ? $this->schoolStreamModel->getAllStream()
            : $this->schoolStreamModel->getAllStreamsBySchool($schId);
        $data['templates']         = $this->ttTemplateModel->getAll();
        $data['isSuperAdmin']      = $isSuperAdmin;
        $data['currentYear']       = (int) date('Y');
        $data['hasSchoolTemplate'] = !$isSuperAdmin && $this->schoolHasTemplate($schId);
        $data['_view']             = 'app/timetable/add';
        return view('app/layouts/main', $data);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();

        if (!$this->require_access('_add_timetable')) {
            return redirect()->to('timetable')->with('error', 'Access denied.');
        }

        if (!$this->validate([
            'stream_id_fk'   => 'required|integer',
            'template_id_fk' => 'required|integer',
            'academic_year'  => 'required|integer|min_length[4]|max_length[4]',
            'term'           => 'required|integer|in_list[1,2,3]',
        ])) {
            return redirect()->back()->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = (int) $this->session->get('schID');
        $streamId     = (int) $this->request->getPost('stream_id_fk');
        $templateId   = (int) $this->request->getPost('template_id_fk');
        $year         = (int) $this->request->getPost('academic_year');
        $term         = (int) $this->request->getPost('term');
        $startDate    = $this->request->getPost('rotation_start_date') ?: null;
        $startDay     = (int) ($this->request->getPost('rotation_start_day') ?: 1);
        $status       = $this->request->getPost('timetable_status') ?: 'Draft';

        if ($isSuperAdmin || $schId === 0) {
            $schId = $this->schIdFromStream($streamId);
        }

        $id = $this->ttModel->insert([
            'sch_id_fk'           => $schId,
            'stream_id_fk'        => $streamId,
            'template_id_fk'      => $templateId,
            'academic_year'       => $year,
            'term'                => $term,
            'rotation_start_date' => $startDate,
            'rotation_start_day'  => $startDay,
            'timetable_status'    => $status,
            'created_by'          => (int) $this->session->get('userID'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        if (!$id) {
            return redirect()->back()->withInput()->with('error', 'Failed to create timetable. A timetable for this stream/year/term may already exist.');
        }

        $this->logAction('Timetable Created', 'Created timetable #' . $id . ' for stream ' . $streamId);

        return redirect()->to('timetable/edit/' . $id)
            ->with('success', 'Timetable created — fill in the periods below and save.');
    }

    // =========================================================================
    // EDIT (grid editor)
    // =========================================================================

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Edit Timetable', 'Timetable', 'Edit Timetable');

        if (!$this->require_access('_edit_timetable')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $slots       = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $entryMap    = $this->ttEntryModel->getMappedByTimetable($id);
        $subjectGrps = $this->streamSubjectGroups((int) $tt['stream_id_fk']);

        // Flat subject list for teacher map
        $optSubjects = [];
        foreach ($subjectGrps['optional_groups'] as $grp) {
            $optSubjects = array_merge($optSubjects, $grp['subjects']);
        }
        $teacherMap = $this->subjectTeacherMap(array_merge($subjectGrps['core'], $optSubjects));

        // Build optional entry data for JS init
        $optEntryMapJs = [];
        foreach ($entryMap as $day => $slotCells) {
            foreach ($slotCells as $slotId => $cell) {
                if (!empty($cell['is_optional'])) {
                    $subs = [];
                    foreach ($cell['entries'] as $e) {
                        $subs[(string) $e['sch_sub_id_fk']] = [
                            'teacher_id_fk' => $e['teacher_id_fk'],
                            'room'          => $e['room'] ?? '',
                        ];
                    }
                    $optEntryMapJs[$day][$slotId] = [
                        'option_num' => $cell['option_num'],
                        'subjects'   => $subs,
                    ];
                }
            }
        }

        $numDays = max(1, (int) ($tt['num_days'] ?? 6));

        $data['tt']            = $tt;
        $data['slots']         = $slots;
        $data['entryMap']      = $entryMap;
        $data['subjectGrps']   = $subjectGrps;
        $data['teacherMap']    = $teacherMap;
        $data['optEntryMapJs'] = $optEntryMapJs;
        $data['days']          = range(1, $numDays);
        $data['templates']     = $this->ttTemplateModel->getAll();
        $data['_view']         = 'app/timetable/edit';
        return view('app/layouts/main', $data);
    }

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();

        if (!$this->require_access('_edit_timetable')) {
            return redirect()->to('timetable')->with('error', 'Access denied.');
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $this->ttModel->update($id, [
            'rotation_start_date' => $this->request->getPost('rotation_start_date') ?: null,
            'rotation_start_day'  => (int) ($this->request->getPost('rotation_start_day') ?: 1),
            'timetable_status'    => $this->request->getPost('timetable_status') ?: 'Draft',
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        $rawEntries = $this->request->getPost('entries')    ?? [];
        $optEntries = $this->request->getPost('opt_entries') ?? [];
        $slotsList  = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $numDays    = max(1, (int) ($tt['num_days'] ?? 6));
        $entries    = [];

        foreach (range(1, $numDays) as $day) {
            foreach ($slotsList as $slot) {
                if (!(int) $slot['is_teaching']) continue;
                $slotId     = (int) $slot['slot_id'];
                $isOptional = !empty($rawEntries[$day][$slotId]['is_optional']);

                if ($isOptional && !empty($optEntries[$day][$slotId])) {
                    $optNum = (int) ($rawEntries[$day][$slotId]['option_num'] ?? 1);
                    foreach ((array) $optEntries[$day][$slotId] as $subId => $sData) {
                        $subId = (int) $subId;
                        if ($subId <= 0) continue;
                        $entries[] = [
                            'day_number'    => $day,
                            'slot_id_fk'    => $slotId,
                            'option_num'    => $optNum ?: 1,
                            'sch_sub_id_fk' => $subId,
                            'teacher_id_fk' => (int) ($sData['teacher_id_fk'] ?? 0),
                            'room'          => trim($sData['room'] ?? ''),
                            'notes'         => '',
                        ];
                    }
                } else {
                    $subId     = (int) ($rawEntries[$day][$slotId]['sch_sub_id_fk'] ?? 0);
                    $teacherId = (int) ($rawEntries[$day][$slotId]['teacher_id_fk']  ?? 0);
                    $room      = trim($rawEntries[$day][$slotId]['room'] ?? '');
                    if ($subId > 0 || $teacherId > 0) {
                        $entries[] = [
                            'day_number'    => $day,
                            'slot_id_fk'    => $slotId,
                            'option_num'    => null,
                            'sch_sub_id_fk' => $subId,
                            'teacher_id_fk' => $teacherId,
                            'room'          => $room,
                            'notes'         => '',
                        ];
                    }
                }
            }
        }

        $this->ttEntryModel->replaceEntries($id, $entries);
        $this->logAction('Timetable Updated', 'Updated timetable #' . $id);

        return redirect()->to('timetable/detail/' . $id)
            ->with('success', 'Timetable saved successfully.');
    }

    // =========================================================================
    // DETAIL
    // =========================================================================

    public function detail(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Timetable', 'Timetable', 'View Timetable');

        if (!$this->require_access('_timetable_detail')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $slots    = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $entryMap = $this->ttEntryModel->getMappedByTimetable($id);
        $weekMap  = [];

        if (!empty($tt['rotation_start_date'])) {
            $weekMap = $this->ttModel->getWeekDayMap(
                $tt['rotation_start_date'],
                (int) $tt['rotation_start_day']
            );
        }

        $numDays = max(1, (int) ($tt['num_days'] ?? 6));

        $data['tt']       = $tt;
        $data['slots']    = $slots;
        $data['entryMap'] = $entryMap;
        $data['weekMap']  = $weekMap;
        $data['days']     = range(1, $numDays);
        $data['canEdit']  = (int) $this->session->get('roleID') === 1
                            || $this->grant_access('_edit_timetable');
        $data['_view']    = 'app/timetable/detail';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();

        if (!$this->require_access('_remove_timetable')) {
            return redirect()->to('timetable')->with('error', 'Access denied.');
        }

        $this->ttEntryModel->where('timetable_id_fk', $id)->delete();
        $this->ttModel->delete($id);
        $this->logAction('Timetable Deleted', 'Deleted timetable #' . $id);

        return redirect()->to('timetable')->with('success', 'Timetable deleted.');
    }

    // =========================================================================
    // REPORT (HTML printable)
    // =========================================================================

    public function report(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Timetable Report', 'Timetable', 'Timetable Report');

        if (!$this->require_access('_timetable_report')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $numDays  = max(1, (int) ($tt['num_days'] ?? 6));
        $db       = \Config\Database::connect();
        $school   = $db->table('school')->where('sch_id', (int) $tt['sch_id_fk'])->get()->getRowArray();

        $data['tt']       = $tt;
        $data['school']   = $school;
        $data['slots']    = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $data['entryMap'] = $this->ttEntryModel->getMappedByTimetable($id);
        $data['days']     = range(1, $numDays);
        $data['_view']    = 'app/timetable/report';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // REPORT PDF (landscape A4 — matches exam PDF style)
    // =========================================================================

    public function reportPdf(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();

        if (!$this->require_access('_timetable_report')) {
            return redirect()->to('timetable')->with('error', 'Access denied.');
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $slots    = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $entryMap = $this->ttEntryModel->getMappedByTimetable($id);
        $numDays  = max(1, (int) ($tt['num_days'] ?? 6));
        $days     = range(1, $numDays);

        $db     = \Config\Database::connect();
        $school = $db->table('school')->where('sch_id', (int) $tt['sch_id_fk'])->get()->getRowArray() ?: [];

        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
        set_error_handler(static function (int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP') || str_contains($errstr, 'gd-png') || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        // ── Landscape A4 (297 × 210 mm) ──────────────────────────────────────
        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetTitle('Timetable — ' . ($tt['stream_name'] ?? '') . ' ' . $tt['academic_year']);
        $pdf->SetAuthor($school['sch_name'] ?? 'Navuli Fiji');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();

        // ── Outer borders (same double-border style as exam PDF) ─────────────
        $pdf->SetLineStyle(['width' => 1.0, 'color' => [26, 86, 219]]);
        $pdf->Rect(6, 6, 285, 198, 'D');
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Rect(8, 8, 281, 194, 'D');

        $sx = 10; $pw = 277; $y = 10.0;

        // ── Logos ─────────────────────────────────────────────────────────────
        $logoPath = FCPATH . 'uploads/school/logo/' . ($school['sch_logo'] ?? '');
        if (!empty($school['sch_logo']) && file_exists($logoPath)) {
            $pdf->Image($logoPath, $sx, $y, 22, 22, '', '', 'T', false, 300);
        }
        $navuliLogo = FCPATH . 'icon.png';
        if (file_exists($navuliLogo)) {
            $pdf->Image($navuliLogo, $sx + $pw - 22, $y, 20, 20, '', '', 'T', false, 300);
        }

        // ── Header text ───────────────────────────────────────────────────────
        $cx = $sx + 24; $centerW = $pw - 46;

        $pdf->SetXY($cx, $y + 1);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($centerW, 7, strtoupper($school['sch_name'] ?? ($tt['sch_name'] ?? '')), 0, 1, 'C');

        $pdf->SetXY($cx, $y + 9);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor(55, 65, 81);
        $subTitle = 'CLASS TIMETABLE — ' . strtoupper($tt['stream_name'] ?? '') . '  ·  ' . $tt['academic_year'] . ' TERM ' . $tt['term'];
        $pdf->Cell($centerW, 5, $subTitle, 0, 1, 'C');

        $contactParts = array_filter([
            $school['sch_address'] ?? '',
            !empty($school['sch_phone']) ? 'Ph: ' . $school['sch_phone'] : '',
            $school['sch_email']   ?? '',
        ]);
        $pdf->SetXY($cx, $y + 15);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($centerW, 4, implode('  |  ', $contactParts), 0, 1, 'C');

        // ── Divider lines ─────────────────────────────────────────────────────
        $y += 25;
        $pdf->SetLineStyle(['width' => 0.7, 'color' => [26, 86, 219]]);
        $pdf->Line($sx, $y, $sx + $pw, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [147, 197, 253]]);
        $pdf->Line($sx, $y, $sx + $pw, $y);
        $y += 3.5;

        // ── Rotation info ─────────────────────────────────────────────────────
        if (!empty($tt['rotation_start_date'])) {
            $pdf->SetXY($sx, $y);
            $pdf->SetFont('helvetica', 'I', 7);
            $pdf->SetTextColor(120, 120, 120);
            $pdf->Cell($pw, 4,
                'Rotation: Day 1–' . $numDays . ' cycle  ·  Day ' . (int) $tt['rotation_start_day']
                . ' began on ' . date('d F Y', strtotime($tt['rotation_start_date'])),
                0, 1, 'C'
            );
            $y += 5;
        }

        // ── Grid column widths ────────────────────────────────────────────────
        $colTime = 30;
        $colDay  = round(($pw - $colTime) / count($days), 2);

        // ── Pre-calculate row heights ─────────────────────────────────────────
        $rowHeights = [];
        foreach ($slots as $slot) {
            if (!(int) $slot['is_teaching']) {
                $rowHeights[$slot['slot_id']] = 6.0;
            } else {
                $rh = 11.0;
                foreach ($days as $d) {
                    $cell = $entryMap[$d][$slot['slot_id']] ?? null;
                    if ($cell && !empty($cell['is_optional'])) {
                        $needed = count($cell['entries']) * 5.0 + 3;
                        if ($needed > $rh) $rh = $needed;
                    }
                }
                $rowHeights[$slot['slot_id']] = $rh;
            }
        }

        // ── Grid header row ───────────────────────────────────────────────────
        $pdf->SetDrawColor(200, 220, 240);
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [200, 220, 240]]);
        $pdf->SetFillColor(240, 247, 255);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(26, 86, 219);
        $hdrH = 8;

        $pdf->MultiCell($colTime, $hdrH, 'Period / Time', 1, 'C', true, 0, $sx, $y, true, 0, false, true, $hdrH, 'M');
        $x = $sx + $colTime;
        foreach ($days as $d) {
            $pdf->MultiCell($colDay, $hdrH, 'Day ' . $d, 1, 'C', true, 0, $x, $y, true, 0, false, true, $hdrH, 'M');
            $x += $colDay;
        }
        $y += $hdrH;

        // ── Grid data rows ────────────────────────────────────────────────────
        $rowIdx = 0;
        foreach ($slots as $slot) {
            $isBreak = !(int) $slot['is_teaching'];
            $rowH    = $rowHeights[$slot['slot_id']];

            $pdf->SetDrawColor(220, 228, 240);
            $pdf->SetLineStyle(['width' => 0.25, 'color' => [220, 228, 240]]);

            // ── Time column ───────────────────────────────────────────────────
            if ($isBreak) {
                $pdf->SetFillColor(232, 237, 252);
                $pdf->SetFont('helvetica', 'I', 7.5);
                $pdf->SetTextColor(80, 90, 130);
                $pdf->MultiCell($colTime, $rowH, $slot['label'], 1, 'C', true, 0, $sx, $y, true, 0, false, true, $rowH, 'M');
            } else {
                $fill = ($rowIdx % 2 === 0) ? [248, 250, 253] : [255, 255, 255];
                $pdf->SetFillColor(...$fill);
                $pdf->SetFont('helvetica', 'B', 7.5);
                $pdf->SetTextColor(55, 65, 81);
                $timeLabel = $slot['label'];
                if ($slot['start_time'] && $slot['end_time']) {
                    $timeLabel .= "\n" . substr($slot['start_time'], 0, 5) . '–' . substr($slot['end_time'], 0, 5);
                }
                $pdf->MultiCell($colTime, $rowH, $timeLabel, 1, 'C', true, 0, $sx, $y, true, 0, false, true, $rowH, 'M');
            }

            // ── Day columns ───────────────────────────────────────────────────
            $x = $sx + $colTime;
            foreach ($days as $d) {
                $cell = $entryMap[$d][$slot['slot_id']] ?? null;

                if ($isBreak) {
                    $pdf->SetFillColor(232, 237, 252);
                    $pdf->SetFont('helvetica', 'I', 7);
                    $pdf->SetTextColor(100, 110, 140);
                    $pdf->MultiCell($colDay, $rowH, '— ' . $slot['label'] . ' —', 1, 'C', true, 0, $x, $y, true, 0, false, true, $rowH, 'M');
                } elseif ($cell && !empty($cell['is_optional'])) {
                    $fill = ($rowIdx % 2 === 0) ? [245, 248, 255] : [252, 252, 255];
                    $pdf->SetFillColor(...$fill);
                    $lines = [];
                    foreach ($cell['entries'] as $e) {
                        $sub = $e['subject_name'] ?? '';
                        $tch = trim(($e['fname'] ?? '') . ' ' . ($e['lname'] ?? ''));
                        $lines[] = $sub . ($tch ? ' (' . $tch . ')' : '');
                    }
                    $pdf->SetFont('helvetica', '', 6.5);
                    $pdf->SetTextColor(50, 80, 180);
                    $pdf->MultiCell($colDay, $rowH, implode("\n", $lines), 1, 'L', true, 0, $x, $y, true, 0, false, true, $rowH, 'T');
                } elseif ($cell && ($cell['sch_sub_id_fk'] || $cell['teacher_id_fk'])) {
                    $fill = ($rowIdx % 2 === 0) ? [248, 250, 253] : [255, 255, 255];
                    $pdf->SetFillColor(...$fill);
                    $subj = $cell['subject_name'] ?? '';
                    $tch  = trim(($cell['fname'] ?? '') . ' ' . ($cell['lname'] ?? ''));
                    $room = $cell['room'] ?? '';
                    $txt  = $subj;
                    if ($tch)  $txt .= "\n" . $tch;
                    if ($room) $txt .= ' [' . $room . ']';
                    $pdf->SetFont('helvetica', 'B', 7.5);
                    $pdf->SetTextColor(26, 36, 56);
                    $pdf->MultiCell($colDay, $rowH, $txt, 1, 'L', true, 0, $x, $y, true, 0, false, true, $rowH, 'M');
                } else {
                    $fill = ($rowIdx % 2 === 0) ? [248, 250, 253] : [255, 255, 255];
                    $pdf->SetFillColor(...$fill);
                    $pdf->SetFont('helvetica', '', 7);
                    $pdf->SetTextColor(190, 195, 210);
                    $pdf->MultiCell($colDay, $rowH, '—', 1, 'C', true, 0, $x, $y, true, 0, false, true, $rowH, 'M');
                }
                $x += $colDay;
            }

            $y += $rowH;
            if (!(int) $slot['is_teaching']) continue;
            $rowIdx++;
        }

        // ── Footer ────────────────────────────────────────────────────────────
        $y += 3;
        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'I', 6.5);
        $pdf->SetTextColor(160, 160, 175);
        $pdf->Cell($pw, 4,
            'Generated: ' . date('d M Y, H:i') . '  ·  ' . ($tt['template_name'] ?? '') . '  ·  Navuli Fiji School Management System',
            0, 1, 'C'
        );

        restore_error_handler();

        $fname = 'timetable_' . preg_replace('/[^a-z0-9_]/i', '_', ($tt['stream_name'] ?? 'class'))
               . '_' . $tt['academic_year'] . '_T' . $tt['term'] . '.pdf';
        $pdf->Output($fname, 'I');
        exit;
    }

    // =========================================================================
    // AJAX helpers
    // =========================================================================

    public function streamInfo(int $streamId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        $this->boot();

        $stream = $this->schoolStreamModel->getStream($streamId);
        if (!$stream) return $this->response->setJSON(['error' => 'Stream not found'])->setStatusCode(404);

        $db     = \Config\Database::connect();
        $level  = $db->table('sch_level')->where('sch_level_id', $stream['sch_level_id_fk'])->get()->getRowArray();
        $schId  = $level ? (int) $level['sch_id_fk'] : 0;
        $school = $schId ? $db->table('school')->where('sch_id', $schId)->get()->getRowArray() : null;
        $catId  = $school ? (int) $school['sch_cat_id_fk'] : 0;

        $template = $this->ttTemplateModel->getDefaultForCategory($catId, $schId);
        $slots    = $template ? $this->ttSlotModel->getByTemplate((int) $template['template_id']) : [];
        $subjects = $this->streamSubjects($streamId);

        return $this->response->setJSON([
            'template'   => $template,
            'slots'      => $slots,
            'subjects'   => $subjects,
            'teacherMap' => $this->subjectTeacherMap($subjects),
        ]);
    }

    public function subjectTeachers(int $schSubId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON([])->setStatusCode(401);

        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT u.user_id, u.fname, u.lname
            FROM   admission_teaching_subject ats
            INNER  JOIN admission a ON a.admission_id = ats.admission_id_fk
            INNER  JOIN users u    ON u.user_id = a.user_id_fk
            WHERE  ats.sch_sub_id_fk = ?
              AND  ats.adm_teach_sub_status = 'Active'
            ORDER  BY u.fname, u.lname
        ", [$schSubId])->getResultArray();

        return $this->response->setJSON($rows);
    }

    // =========================================================================
    // Private helpers
    // =========================================================================

    private function streamSubjects(int $streamId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT ss.sch_sub_id, sub.subject_name, 'Core' AS subject_type, NULL AS option_num
            FROM   stream_core_subject scs
            INNER  JOIN sch_subject ss ON ss.sch_sub_id = scs.sch_sub_id_fk
            INNER  JOIN subject sub    ON sub.subject_id = ss.subject_id_fk
            WHERE  scs.stream_id_fk = ?
            UNION
            SELECT ss.sch_sub_id, sub.subject_name, 'Optional' AS subject_type,
                   COALESCE(sos.option_num, 1) AS option_num
            FROM   stream_optional_subject sos
            INNER  JOIN sch_subject ss ON ss.sch_sub_id = sos.sch_sub_id_fk
            INNER  JOIN subject sub    ON sub.subject_id = ss.subject_id_fk
            WHERE  sos.stream_id_fk = ?
            ORDER  BY subject_type, option_num, subject_name
        ", [$streamId, $streamId])->getResultArray();
    }

    /**
     * Returns subjects split into core array and optional_groups array.
     * Each optional group: { option_num, label, subjects[] }
     */
    private function streamSubjectGroups(int $streamId): array
    {
        $rows     = $this->streamSubjects($streamId);
        $core     = [];
        $optGroups = [];

        foreach ($rows as $r) {
            if ($r['subject_type'] === 'Core') {
                $core[] = $r;
            } else {
                $num = (int) ($r['option_num'] ?? 1);
                $optGroups[$num]['subjects'][] = $r;
                $optGroups[$num]['option_num'] = $num;
            }
        }

        foreach ($optGroups as $num => &$grp) {
            $grp['label'] = implode(' | ', array_column($grp['subjects'], 'subject_name'));
        }
        unset($grp);

        return ['core' => $core, 'optional_groups' => array_values($optGroups)];
    }

    private function subjectTeacherMap(array $subjects): array
    {
        if (empty($subjects)) return [];

        $db  = \Config\Database::connect();
        $ids = implode(',', array_map('intval', array_column($subjects, 'sch_sub_id')));
        if (!$ids) return [];

        $rows = $db->query("
            SELECT ats.sch_sub_id_fk, u.user_id, u.fname, u.lname
            FROM   admission_teaching_subject ats
            INNER  JOIN admission a ON a.admission_id = ats.admission_id_fk
            INNER  JOIN users u    ON u.user_id = a.user_id_fk
            WHERE  ats.sch_sub_id_fk IN ({$ids})
              AND  ats.adm_teach_sub_status = 'Active'
            ORDER  BY u.fname, u.lname
        ")->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['sch_sub_id_fk']][] = $row;
        }
        return $map;
    }

    private function schIdFromStream(int $streamId): int
    {
        $db     = \Config\Database::connect();
        $stream = $this->schoolStreamModel->getStream($streamId);
        if (!$stream) return 0;
        $level  = $db->table('sch_level')->where('sch_level_id', $stream['sch_level_id_fk'])->get()->getRowArray();
        return $level ? (int) $level['sch_id_fk'] : 0;
    }

    private function getSchoolTemplate(int $schId): ?array
    {
        if ($schId === 0) return null;
        $db  = \Config\Database::connect();
        $tpl = $db->table('timetable_template')->where('sch_id_fk', $schId)->get()->getRowArray();
        if (!$tpl) return null;

        $slots = $db->table('timetable_template_slot')
            ->where('template_id_fk', $tpl['template_id'])
            ->orderBy('slot_order', 'ASC')
            ->get()->getResultArray();

        $tpl['slots'] = $slots;
        return $tpl;
    }

    private function schoolHasTemplate(int $schId): bool
    {
        if ($schId === 0) return false;
        $db = \Config\Database::connect();
        return (bool) $db->table('timetable_template')->where('sch_id_fk', $schId)->countAllResults();
    }

    /**
     * Reconstructs config values (period counts, durations) from saved template slots.
     */
    private function parseTemplateConfig(?array $tpl): array
    {
        $defaults = [
            'num_days'        => 6,
            'morning_periods' => 3,
            'midday_periods'  => 3,
            'afternoon_periods' => 3,
            'period_duration' => 40,
            'recess_duration' => 20,
            'lunch_duration'  => 40,
            'start_time'      => '08:00',
        ];

        if (!$tpl || empty($tpl['slots'])) return $defaults;

        $defaults['num_days'] = (int) ($tpl['num_days'] ?? 6);

        $section = 'morning';
        $morning = $midday = $afternoon = 0;
        $pdDur = $recessDur = $lunchDur = 0;
        $startSet = false;

        foreach ($tpl['slots'] as $s) {
            if (!$startSet && (int) $s['is_teaching']) {
                $defaults['start_time'] = substr($s['start_time'], 0, 5);
                $startSet = true;
            }
            $dur = (int) round((strtotime('2000-01-01 ' . $s['end_time']) - strtotime('2000-01-01 ' . $s['start_time'])) / 60);

            if ((int) $s['is_teaching']) {
                if ($section === 'morning')   { $morning++;   $pdDur = $dur; }
                elseif ($section === 'midday') { $midday++;    $pdDur = $dur; }
                else                           { $afternoon++; $pdDur = $dur; }
            } else {
                if ($s['label'] === 'Recess' || $section === 'morning') {
                    $recessDur = $dur;
                    $section   = 'midday';
                } else {
                    $lunchDur = $dur;
                    $section  = 'afternoon';
                }
            }
        }

        $defaults['morning_periods']   = $morning   ?: 3;
        $defaults['midday_periods']    = $midday    ?: 3;
        $defaults['afternoon_periods'] = $afternoon ?: 3;
        $defaults['period_duration']   = $pdDur     ?: 40;
        $defaults['recess_duration']   = $recessDur ?: 20;
        $defaults['lunch_duration']    = $lunchDur  ?: 40;

        return $defaults;
    }

    private function logAction(string $title, string $desc = ''): void
    {
        $this->userLogModel->insert([
            'user_id_fk'  => $this->session->get('userID'),
            'ip_aadress'  => $this->ipAddress,
            'user_agent'  => $this->userAgent->getAgentString(),
            'user_device' => $this->deviceInfo['device_type'] ?? 'Unknown',
            'log_title'   => $title,
            'log_desc'    => $desc ?: $title,
            'log_date'    => date('Y-m-d'),
            'log_time'    => time(),
            'log_icon'    => '<i class="ki-duotone ki-calendar-8"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>',
            'log_theme'   => 'primary',
        ]);
    }
}
