<?php
namespace App\Controllers;

class TransportationController extends BaseController
{
    // ================================================================
    // INDEX — Listing of transport allocations (school-scoped)
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Transport Allocations', 'Transportation', 'All Allocations');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_transport_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId = $isSuperAdmin ? 0 : (int) $this->session->get('schID');
        $year  = (int) ($this->request->getGet('year') ?: date('Y'));

        $data['allocations']  = $this->transportAllocationModel->getBySchool($schId, $year);
        $data['year']         = $year;
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['canAdd']       = $isSuperAdmin || $this->grant_access('_add_transport');
        $data['canEdit']      = $isSuperAdmin || $this->grant_access('_edit_transport');
        $data['canDelete']    = $isSuperAdmin || $this->grant_access('_remove_transport');
        $data['_view']        = 'app/transportation/index';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ADD — Show form to create a new allocation
    // ================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_add_transport')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('New Application', 'Transportation', 'Add Allocation');

        $schId = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $data['students']    = $this->transportAllocationModel->getActiveStudentsBySchool($schId);
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['allocation']  = null;
        $data['household']   = [];
        $data['trips']       = [];
        $data['isEdit']      = false;
        $data['_view']       = 'app/transportation/form';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STORE — Save new allocation + household members + trips
    // ================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_add_transport')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $studentId = (int) $this->request->getPost('student_id');
            $year      = (int) ($this->request->getPost('academic_year') ?: date('Y'));

            $existing = $this->transportAllocationModel->getByStudentAndYear($studentId, $year);
            if ($existing) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An application for this student already exists for ' . $year . '.',
                ]);
            }

            $allocationId = $this->saveAllocation(null, $studentId, $year);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Transport Allocation Created',
                'log_desc'    => 'Transport allocation created for admission ID ' . $studentId . ' (' . $year . ')',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-bus"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'success',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Application saved successfully.',
                'redirect' => base_url('transportation/detail/' . $allocationId),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TransportationController::store] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // EDIT — Show edit form
    // ================================================================

    public function edit(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_edit_transport')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $allocation = $this->transportAllocationModel->getDetail($allocationId);
        if (!$allocation) {
            return redirect()->to('transportation')->with('error', 'Application not found.');
        }

        $this->setPageData('Edit Application', 'Transportation', 'Edit Allocation');

        $schId = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $data['students']     = $this->transportAllocationModel->getActiveStudentsBySchool($schId);
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['allocation']   = $allocation;
        $data['household']    = $this->transportHouseholdMemberModel->getByAllocation($allocationId);
        $data['trips']        = $this->transportTripModel->getByAllocation($allocationId);
        $data['isEdit']       = true;
        $data['_view']        = 'app/transportation/form';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // UPDATE — Save edited allocation
    // ================================================================

    public function update(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_edit_transport')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $allocation = $this->transportAllocationModel->find($allocationId);
            if (!$allocation) {
                return $this->response->setJSON(['success' => false, 'message' => 'Application not found.']);
            }

            $studentId = (int) ($this->request->getPost('student_id') ?: $allocation['student_id']);
            $year      = (int) ($this->request->getPost('academic_year') ?: $allocation['academic_year']);

            $this->saveAllocation($allocationId, $studentId, $year);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Transport Allocation Updated',
                'log_desc'    => 'Transport allocation ID ' . $allocationId . ' updated',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-bus"><span class="path1"></span><span class="path2"></span></i>',
                'log_theme'   => 'success',
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Application updated successfully.',
                'redirect' => base_url('transportation/detail/' . $allocationId),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TransportationController::update] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DELETE — Delete allocation + child rows
    // ================================================================

    public function delete(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_remove_transport')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $allocation = $this->transportAllocationModel->find($allocationId);
            if (!$allocation) {
                return $this->response->setJSON(['success' => false, 'message' => 'Application not found.']);
            }

            // No FK cascade — manually clean up child rows
            $this->transportHouseholdMemberModel->where('allocation_id_fk', $allocationId)->delete();
            $this->transportTripModel->where('allocation_id_fk', $allocationId)->delete();
            $this->transportAllocationModel->delete($allocationId);

            $this->userLogModel->insert([
                'user_id_fk'  => $this->session->get('userID'),
                'ip_aadress'  => $this->ipAddress,
                'user_agent'  => $this->userAgent->getAgentString(),
                'user_device' => $this->deviceInfo['device_type'],
                'log_title'   => 'Transport Allocation Deleted',
                'log_desc'    => 'Transport allocation ID ' . $allocationId . ' deleted',
                'log_date'    => date('Y-m-d'),
                'log_time'    => time(),
                'log_icon'    => '<i class="ki-duotone ki-trash"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                'log_theme'   => 'danger',
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Application deleted successfully.',
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TransportationController::delete] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // DETAIL — View one allocation
    // ================================================================

    public function detail(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_transport_detail')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $allocation = $this->transportAllocationModel->getDetail($allocationId);
        if (!$allocation) {
            return redirect()->to('transportation')->with('error', 'Application not found.');
        }

        $this->setPageData('Allocation Detail', 'Transportation', 'Detail');

        $data['allocation']       = $allocation;
        $data['household']        = $this->transportHouseholdMemberModel->getByAllocation($allocationId);
        $data['trips']            = $this->transportTripModel->getByAllocation($allocationId);
        $data['canEdit']          = $isSuperAdmin || $this->grant_access('_edit_transport');
        $data['canDelete']        = $isSuperAdmin || $this->grant_access('_remove_transport');
        $data['canGenerateForm']  = $isSuperAdmin || $this->grant_access('_generate_transport_form');
        $data['_view']            = 'app/transportation/detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // GENERATE FORM — Printable mimic of the Ministry Transport
    // Assistance Application Form (Appendix A), for physical
    // Principal/Head signature and submission to the Ministry.
    // ================================================================

    public function generateForm(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $allowed      = $isSuperAdmin || $this->grant_access('_generate_transport_form');

        $allocation = $this->transportAllocationModel->getDetail($allocationId);
        if (!$allocation) {
            return redirect()->to('transportation')->with('error', 'Application not found.');
        }

        // Self-service: the student themself, or a confirmed parent of the
        // student, may also generate their own form without the staff permission.
        if (!$allowed) {
            $userId = (int) $this->session->get('userID');
            if ($this->isOwnOrLinkedChildAllocation($allocation, $userId)) {
                $allowed = true;
            }
        }

        if (!$allowed) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $household = $this->transportHouseholdMemberModel->getByAllocation($allocationId);
        $trips     = $this->transportTripModel->getByAllocation($allocationId);

        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCreator('Navuli');
        $pdf->SetTitle('Transport Assistance Application - ' . trim($allocation['student_fname'] . ' ' . $allocation['student_lname']));

        $this->renderMinistryForm($pdf, $allocation, $household, $trips);

        $pdf->Output('transport-assistance-form-' . $allocationId . '.pdf', 'I');
        exit;
    }

    /**
     * True if $userId is the student on $allocation, or a confirmed linked
     * parent of that student — the two self-service actors for this module.
     */
    private function isOwnOrLinkedChildAllocation(array $allocation, int $userId): bool
    {
        $studentUserId = (int) ($allocation['student_user_id'] ?? 0);
        if ($studentUserId === $userId) {
            return true;
        }

        $parents = $this->parentStudentModel->getParentsOf($studentUserId);
        foreach ($parents as $parent) {
            if ((int) $parent['user_id'] === $userId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renders the Ministry of Education Transport Assistance Application
     * Form (Appendix A) text content faithfully. The government coat-of-arms
     * graphic is intentionally omitted (no licensed asset available) — every
     * other field is reproduced.
     */
    private function renderMinistryForm(\TCPDF $pdf, array $allocation, array $household, array $trips): void
    {
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 5, 'MINISTRY OF EDUCATION', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 4, 'Marela House, 19 Thurston Street, Suva, Fiji', 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 6, 'APPENDIX A: TRANSPORT ASSISTANCE APPLICATION FORM', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'I', 7.5);
        $pdf->MultiCell(0, 4, "Eligibility: combined household income of $16,000 or less per annum. This assistance is granted annually — a new application must be submitted each year. One application per child.", 0, 'C');
        $pdf->Ln(3);

        $studentName = trim($allocation['student_fname'] . ' ' . $allocation['student_lname']);

        $this->sectionHeader($pdf, 'SECTION A: STUDENT INFORMATION');
        $this->fieldRow($pdf, [
            ['Student Name', $studentName],
            ['Student ID', (string) ($allocation['student_user_id'] ?? '')],
        ]);
        $this->fieldRow($pdf, [
            ['Class', $allocation['level_name'] ?? ''],
            ['Stream', $allocation['stream_name'] ?? ''],
        ]);
        $this->fieldRow($pdf, [
            ['Date of Birth', !empty($allocation['dob']) ? date('d M Y', strtotime($allocation['dob'])) : ''],
            ['E-Transport Card No.', $allocation['e_transport_card_number'] ?? ''],
        ]);
        $this->fieldRow($pdf, [
            ['Mobile No.', $allocation['phone'] ?? ''],
            ['Email Address', $allocation['email'] ?? ''],
        ]);
        $this->fieldRow($pdf, [
            ['Residential Address', $allocation['address'] ?? ''],
        ], true);
        $this->fieldRow($pdf, [
            ['School', $allocation['sch_name'] ?? ''],
            ['School Location', $allocation['sch_address'] ?? ''],
        ]);
        $pdf->Ln(2);

        $this->sectionHeader($pdf, 'SECTION B: HOUSEHOLD INFORMATION');
        $pdf->SetFont('helvetica', 'B', 7.5);
        $widths = [32, 18, 22, 25, 30, 22, 18, 15];
        $headers = ['Name', 'DOB', 'Phone No.', 'Relationship', 'Occupation', 'Annual Income', 'TIN #', 'Evidence'];
        foreach ($headers as $i => $h) {
            $pdf->Cell($widths[$i], 6, $h, 1, 0, 'C');
        }
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 7.5);
        foreach ($household as $member) {
            $pdf->Cell($widths[0], 6, $member['member_name'] ?? '', 1, 0, 'L');
            $pdf->Cell($widths[1], 6, !empty($member['dob']) ? date('d/m/Y', strtotime($member['dob'])) : '', 1, 0, 'C');
            $pdf->Cell($widths[2], 6, $member['phone'] ?? '', 1, 0, 'C');
            $pdf->Cell($widths[3], 6, $member['relationship'] ?? '', 1, 0, 'L');
            $pdf->Cell($widths[4], 6, $member['occupation'] ?? '', 1, 0, 'L');
            $pdf->Cell($widths[5], 6, $member['annual_income'] !== null ? number_format((float) $member['annual_income'], 2) : '', 1, 0, 'R');
            $pdf->Cell($widths[6], 6, $member['tin_number'] ?? '', 1, 0, 'C');
            $pdf->Cell($widths[7], 6, !empty($member['evidence_attached']) ? 'Yes' : 'No', 1, 0, 'C');
            $pdf->Ln();
        }
        $pdf->Ln(1);
        $pdf->SetFont('helvetica', '', 8);
        $swYes = !empty($allocation['receiving_social_welfare']);
        $pdf->Cell(0, 5, 'Is your family currently receiving family assistance from Social Welfare? ' . ($swYes ? 'YES' : 'NO') . ($swYes ? ('  —  Social Welfare No.: ' . ($allocation['social_welfare_number'] ?? '')) : ''), 0, 1, 'L');
        $pdf->Ln(2);

        $this->sectionHeader($pdf, 'SECTION C: TRANSPORT INFORMATION');
        $this->renderTripDirection($pdf, 'From Home to School', array_values(array_filter($trips, fn($t) => $t['direction'] === 'To School')), $allocation['to_school_final_destination'] ?? '', $allocation['to_school_total_fare'] ?? null);
        $this->renderTripDirection($pdf, 'From School to Home', array_values(array_filter($trips, fn($t) => $t['direction'] === 'To Home')), $allocation['to_home_final_destination'] ?? '', $allocation['to_home_total_fare'] ?? null);
        $pdf->Ln(2);

        $this->sectionHeader($pdf, 'SECTION D: DECLARATION');
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->MultiCell(0, 4, "I declare that the information provided in this application is true and correct. I understand that under the False Information Act 2016, providing false information is an offence punishable by a fine of up to $20,000 or imprisonment for up to 10 years, or both.", 0, 'L');
        $pdf->Ln(6);
        $pdf->Cell(90, 5, '_______________________________', 0, 0);
        $pdf->Cell(0, 5, 'Date: _______________', 0, 1);
        $pdf->Cell(90, 5, 'Signature of Parent/Guardian', 0, 1);
        $pdf->Ln(4);

        $this->sectionHeader($pdf, 'OFFICE USE ONLY');
        $pdf->SetFont('helvetica', '', 7.5);
        $pdf->Cell(0, 5, 'Application vetted by School Admin Officer: _______________________________  Date: _____________', 0, 1);
        $pdf->Ln(2);
        $pdf->Cell(0, 5, 'Head Teacher / Principal Signature: _______________________________  Official Stamp & Date: _____________', 0, 1);
        $pdf->Ln(2);
        $pdf->Cell(0, 5, 'Data entry into FEMIS by School Admin Officer: _______________________________  Date: _____________', 0, 1);
        $pdf->Ln(2);
        $pdf->Cell(0, 5, 'Verified by District Transport Assistance Officer: _______________________________', 0, 1);
    }

    private function sectionHeader(\TCPDF $pdf, string $title): void
    {
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 6, $title, 0, 1, 'L', true);
        $pdf->Ln(1);
    }

    /**
     * @param array<int, array{0: string, 1: string}> $fields
     */
    private function fieldRow(\TCPDF $pdf, array $fields, bool $fullWidth = false): void
    {
        $pdf->SetFont('helvetica', '', 8.5);
        $colWidth = $fullWidth ? 0 : 90;
        foreach ($fields as $i => [$label, $value]) {
            $pdf->SetFont('helvetica', 'B', 8.5);
            $pdf->Cell(($fullWidth ? 40 : 30), 5.5, $label . ':', 0, 0);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->Cell($fullWidth ? 0 : ($colWidth - 30), 5.5, (string) $value, 0, ($i === count($fields) - 1 ? 1 : 0));
        }
    }

    private function renderTripDirection(\TCPDF $pdf, string $label, array $trips, string $finalDestination, ?float $totalFare): void
    {
        $pdf->SetFont('helvetica', 'B', 8.5);
        $pdf->Cell(0, 5.5, $label, 0, 1);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $widths = ['Trip' => 15, 'Boarding Point' => 60, 'Fare' => 25, 'Mode' => 30];
        foreach ($widths as $h => $w) {
            $pdf->Cell($w, 5.5, $h, 1, 0, 'C');
        }
        $pdf->Ln();
        $pdf->SetFont('helvetica', '', 7.5);
        for ($i = 0; $i < 3; $i++) {
            $trip = $trips[$i] ?? null;
            $pdf->Cell($widths['Trip'], 5.5, 'Trip ' . ($i + 1), 1, 0, 'C');
            $pdf->Cell($widths['Boarding Point'], 5.5, $trip['boarding_point'] ?? '', 1, 0, 'L');
            $pdf->Cell($widths['Fare'], 5.5, $trip && $trip['fare'] !== null ? number_format((float) $trip['fare'], 2) : '', 1, 0, 'R');
            $pdf->Cell($widths['Mode'], 5.5, $trip['mode'] ?? '', 1, 0, 'C');
            $pdf->Ln();
        }
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->Cell(75, 5.5, 'Final Destination: ' . $finalDestination, 0, 0, 'L');
        $pdf->Cell(0, 5.5, 'Total Fare: ' . ($totalFare !== null ? number_format($totalFare, 2) : ''), 0, 1, 'L');
        $pdf->Ln(2);
    }

    // ================================================================
    // MY — Student/Parent self-service: view own/child allocations,
    // fill/submit a new-year application (auto-filled where possible).
    // ================================================================

    public function my()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userID    = (int) $this->session->get('userID');
        $roleCatID = (int) $this->session->get('roleCatID');

        $isStudent = ($roleCatID === 4);
        $isParent  = ($roleCatID === 6);

        $isParentStaff = false;
        if (!$isStudent && !$isParent) {
            if ($this->grant_access('_my_transport') || $this->grant_access('_my_child_transport')) {
                $isParent = true;
            } else {
                $user = $this->userModel->find($userID);
                if (!empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1) {
                    $isParentStaff = true;
                    $isParent      = true;
                } else {
                    $data['_view'] = 'app/auth/access_control';
                    return view('app/layouts/main', $data);
                }
            }
        }

        $this->setPageData('My Transport Allocation', 'Transportation', 'My Transport Allocation');

        $data['isStudent'] = $isStudent;
        $data['isParent']  = $isParent || $isParentStaff;
        $data['year']      = (int) date('Y');
        $data['admission'] = null;
        $data['children']  = [];

        if ($isStudent) {
            $admissions = $this->admissionModel->getAdmissionByUser($userID);
            $admission  = !empty($admissions) ? $admissions[0] : null;

            $data['admission']   = $admission;
            $data['allocations'] = $admission
                ? $this->transportAllocationModel->getByStudent((int) $admission['admission_id'])
                : [];
        } else {
            $children     = $this->parentStudentModel->getChildrenOf($userID);
            $childrenData = [];

            foreach ($children as $child) {
                $childId    = (int) $child['user_id'];
                $admissions = $this->admissionModel->getAdmissionByUser($childId);
                $admission  = !empty($admissions) ? $admissions[0] : null;
                if ($admission) {
                    $school               = $this->schoolModel->find((int) $admission['sch_id_fk']);
                    $admission['sch_name'] = $school['sch_name'] ?? null;
                }

                $childrenData[] = [
                    'child'       => $child,
                    'admission'   => $admission,
                    'allocations' => $admission
                        ? $this->transportAllocationModel->getByStudent((int) $admission['admission_id'])
                        : [],
                ];
            }

            $data['children'] = $childrenData;
        }

        $data['_view'] = 'app/transportation/my';
        return view('app/layouts/main', $data);
    }

    // ================================================================
    // MY FORM — Auto-filled application form for the student themself,
    // or a linked child (?student_user_id=). Fields already in the DB
    // are pre-filled; only the transport-specific fields are new input.
    // ================================================================

    public function myForm()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userID          = (int) $this->session->get('userID');
        $roleCatID       = (int) $this->session->get('roleCatID');
        $targetUserId    = (int) ($this->request->getGet('student_user_id') ?: $userID);

        $resolved = $this->resolveMyFormTarget($userID, $roleCatID, $targetUserId);
        if ($resolved === null) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        [$admission, $isSelf] = $resolved;

        $this->setPageData('Transport Assistance Application', 'Transportation', 'My Transport Allocation');

        $year       = (int) date('Y');
        $allocation = $this->transportAllocationModel->getByStudentAndYear((int) $admission['admission_id'], $year);

        $data['admission']  = $this->admissionModel->getAdmissionDetail((int) $admission['admission_id']) ?? $admission;
        $data['allocation'] = $allocation;
        $data['household']  = $allocation ? $this->transportHouseholdMemberModel->getByAllocation((int) $allocation['allocation_id']) : [];
        $data['trips']       = $allocation ? $this->transportTripModel->getByAllocation((int) $allocation['allocation_id']) : [];
        $data['year']        = $year;
        $data['isSelf']       = $isSelf;
        $data['targetUserId'] = $targetUserId;
        $data['_view']        = 'app/transportation/my_form';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // MY STORE — Self-service save of the current-year application
    // ================================================================

    public function myStore()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userID       = (int) $this->session->get('userID');
        $roleCatID    = (int) $this->session->get('roleCatID');
        $targetUserId = (int) ($this->request->getPost('student_user_id') ?: $userID);

        $resolved = $this->resolveMyFormTarget($userID, $roleCatID, $targetUserId);
        if ($resolved === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        [$admission] = $resolved;

        try {
            $year         = (int) date('Y');
            $studentId    = (int) $admission['admission_id'];
            $existing     = $this->transportAllocationModel->getByStudentAndYear($studentId, $year);
            $allocationId = $this->saveAllocation(
                $existing ? (int) $existing['allocation_id'] : null,
                $studentId,
                $year,
                $userID
            );

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Application saved successfully.',
                'redirect' => base_url('transportation/my'),
            ]);

        } catch (\Exception $e) {
            log_message('error', '[TransportationController::myStore] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    /**
     * Resolves the admission record the caller is allowed to fill a
     * transport form for: themself (Student), or a confirmed linked
     * child (Parent / staff flagged is_a_parent). Returns
     * [admission, isSelf] or null if not permitted / no active admission.
     */
    private function resolveMyFormTarget(int $userID, int $roleCatID, int $targetUserId): ?array
    {
        $isSelf = ($targetUserId === $userID);

        if ($isSelf) {
            if ($roleCatID !== 4) {
                return null; // only students fill their own transport form
            }
        } else {
            $isParent = ($roleCatID === 6);
            if (!$isParent) {
                $user = $this->userModel->find($userID);
                $isParent = !empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1;
            }
            if (!$isParent) {
                return null;
            }

            $children = $this->parentStudentModel->getChildrenOf($userID);
            $isLinked = false;
            foreach ($children as $child) {
                if ((int) $child['user_id'] === $targetUserId) {
                    $isLinked = true;
                    break;
                }
            }
            if (!$isLinked) {
                return null;
            }
        }

        $admissions = $this->admissionModel->getAdmissionByUser($targetUserId);
        $admission  = !empty($admissions) ? $admissions[0] : null;

        return $admission ? [$admission, $isSelf] : null;
    }

    /**
     * Inserts or updates a transport_allocation row plus its household
     * member and trip child rows from the current POST payload. Returns
     * the allocation_id.
     */
    private function saveAllocation(?int $allocationId, int $studentId, int $year, ?int $submittedBy = null): int
    {
        $now = date('Y-m-d H:i:s');

        $payload = [
            'student_id'                   => $studentId,
            'academic_year'                => $year,
            'e_transport_card_number'      => $this->request->getPost('e_transport_card_number') ?: null,
            'receiving_social_welfare'     => $this->request->getPost('receiving_social_welfare') ? 1 : 0,
            'social_welfare_number'        => $this->request->getPost('social_welfare_number') ?: null,
            'to_school_final_destination'  => $this->request->getPost('to_school_final_destination') ?: null,
            'to_school_total_fare'         => $this->request->getPost('to_school_total_fare') !== '' ? $this->request->getPost('to_school_total_fare') : null,
            'to_home_final_destination'    => $this->request->getPost('to_home_final_destination') ?: null,
            'to_home_total_fare'           => $this->request->getPost('to_home_total_fare') !== '' ? $this->request->getPost('to_home_total_fare') : null,
            'application_status'           => 'Submitted',
            'updated_at'                   => $now,
        ];

        if ($submittedBy !== null) {
            $payload['submitted_by'] = $submittedBy;
        }

        if ($allocationId) {
            $this->transportAllocationModel->update($allocationId, $payload);
        } else {
            $payload['created_at'] = $now;
            $allocationId = (int) $this->transportAllocationModel->insert($payload);
        }

        $members = $this->request->getPost('household') ?? [];
        $this->transportHouseholdMemberModel->replaceForAllocation($allocationId, is_array($members) ? $members : []);

        $trips = [];
        foreach (['To School', 'To Home'] as $direction) {
            $directionTrips = $this->request->getPost('trips_' . ($direction === 'To School' ? 'to_school' : 'to_home')) ?? [];
            if (!is_array($directionTrips)) {
                continue;
            }
            foreach (array_values($directionTrips) as $order => $trip) {
                $trip['direction']  = $direction;
                $trip['trip_order'] = $order + 1;
                $trips[]            = $trip;
            }
        }
        $this->transportTripModel->replaceForAllocation($allocationId, $trips);

        return $allocationId;
    }
}
