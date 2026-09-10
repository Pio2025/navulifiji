<?php

namespace App\Controllers;

class HostelAllocationController extends BaseController
{
    // ================================================================
    // ALLOCATE / VACATE
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Room Allocation', 'Hostel', 'Room Allocation');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_hostel_allocate')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId = $isSuperAdmin ? 0 : (int) $this->session->get('schID');

        $data['occupied']  = $this->hostelAllocationModel->getActiveBySchool($schId);
        $data['rooms']     = $this->hostelRoomModel->getAvailableBySchool($schId);
        $data['boarders']  = $this->admissionModel->getActiveStudentsBySchool($schId);
        $data['canDetail'] = $isSuperAdmin || $this->grant_access('_hostel_allocation_detail');
        $data['_view']     = 'app/hostel/allocation/index';

        return view('app/layouts/main', $data);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_allocate')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $roomId      = (int) $this->request->getPost('room_id');
        $admissionId = (int) $this->request->getPost('boarder_admission_id');
        $feeAmount   = (float) ($this->request->getPost('fee_amount') ?: 0);

        if ($roomId <= 0 || $admissionId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select both a room and a boarder.']);
        }

        $room = $this->hostelRoomModel->find($roomId);
        if (!$room) {
            return $this->response->setJSON(['success' => false, 'message' => 'Room not found.']);
        }
        if ($this->hostelRoomModel->getOccupiedCount($roomId) >= (int) $room['capacity']) {
            return $this->response->setJSON(['success' => false, 'message' => 'This room is already at full capacity.']);
        }

        $admission = $this->admissionModel->find($admissionId);
        if (!$admission) {
            return $this->response->setJSON(['success' => false, 'message' => 'Boarder not found.']);
        }
        if ($this->hostelAllocationModel->getActiveByBoarder($admissionId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'This boarder is already allocated to a room.']);
        }

        $this->hostelAllocationModel->insert([
            'room_id_fk'               => $roomId,
            'hostel_id_fk'             => (int) $room['hostel_id_fk'],
            'boarder_admission_id_fk'  => $admissionId,
            'sch_id_fk'                => (int) $room['sch_id_fk'],
            'check_in_date'            => date('Y-m-d'),
            'status'                   => 'Active',
            'fee_amount'               => $feeAmount,
            'fee_paid'                 => 0,
            'allocated_by'             => (int) $this->session->get('userID'),
            'created_at'               => date('Y-m-d H:i:s'),
            'updated_at'               => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Boarder allocated to room ' . $room['room_number'] . ' successfully.',
            'redirect' => base_url('hostel/allocation'),
        ]);
    }

    public function vacate(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_allocate')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $allocation = $this->hostelAllocationModel->find($allocationId);
        if (!$allocation || $allocation['status'] !== 'Active') {
            return redirect()->to('hostel/allocation')->with('error', 'Allocation record not found or already vacated.');
        }

        $this->hostelAllocationModel->update($allocationId, [
            'check_out_date' => date('Y-m-d'),
            'status'         => 'Vacated',
            'vacated_by'     => (int) $this->session->get('userID'),
            'remarks'        => trim((string) $this->request->getPost('remarks')) ?: null,
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('hostel/allocation')->with('success', 'Boarder vacated successfully.');
    }

    // ================================================================
    // ALLOCATION DETAIL (fee / visitor log / leave)
    // ================================================================

    public function detail(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Allocation Detail', 'Hostel', 'Room Allocation');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_hostel_allocation_detail')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $allocation = $this->hostelAllocationModel->getDetail($allocationId);
        if (!$allocation) {
            return redirect()->to('hostel/allocation')->with('error', 'Allocation record not found.');
        }

        $data['allocation']     = $allocation;
        $data['visitors']       = $this->hostelVisitorLogModel->getByAllocation($allocationId);
        $data['leaves']         = $this->hostelLeaveModel->getByAllocation($allocationId);
        $data['canVacate']      = $isSuperAdmin || $this->grant_access('_hostel_allocate');
        $data['canLogVisitor']  = $isSuperAdmin || $this->grant_access('_hostel_visitor_log');
        $data['canManageLeave'] = $isSuperAdmin || $this->grant_access('_hostel_leave_manage');
        $data['_view']          = 'app/hostel/allocation/detail';

        return view('app/layouts/main', $data);
    }

    public function storeVisitor(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_visitor_log')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $allocation = $this->hostelAllocationModel->find($allocationId);
        if (!$allocation) {
            return redirect()->to('hostel/allocation')->with('error', 'Allocation record not found.');
        }

        $rules = [
            'visitor_name' => 'required|max_length[150]',
            'relationship' => 'permit_empty|max_length[100]',
            'visit_date'   => 'required|valid_date',
            'time_in'      => 'permit_empty',
            'time_out'     => 'permit_empty',
            'purpose'      => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->hostelVisitorLogModel->insert([
            'allocation_id_fk'        => $allocationId,
            'boarder_admission_id_fk' => (int) $allocation['boarder_admission_id_fk'],
            'sch_id_fk'               => (int) $allocation['sch_id_fk'],
            'visitor_name'            => trim($this->request->getPost('visitor_name')),
            'relationship'            => trim((string) $this->request->getPost('relationship')) ?: null,
            'visit_date'              => $this->request->getPost('visit_date'),
            'time_in'                 => $this->request->getPost('time_in') ?: null,
            'time_out'                => $this->request->getPost('time_out') ?: null,
            'purpose'                 => trim((string) $this->request->getPost('purpose')) ?: null,
            'recorded_by'             => (int) $this->session->get('userID'),
            'created_at'              => date('Y-m-d H:i:s'),
            'updated_at'              => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('hostel/allocation/detail/' . $allocationId)
            ->with('success', 'Visitor entry recorded successfully.');
    }

    public function storeLeave(int $allocationId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_leave_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $allocation = $this->hostelAllocationModel->find($allocationId);
        if (!$allocation) {
            return redirect()->to('hostel/allocation')->with('error', 'Allocation record not found.');
        }

        $rules = [
            'from_date' => 'required|valid_date',
            'to_date'   => 'required|valid_date',
            'reason'    => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $fromDate = $this->request->getPost('from_date');
        $toDate   = $this->request->getPost('to_date');

        if ($toDate < $fromDate) {
            return redirect()->back()->withInput()
                ->with('error', 'The leave end date cannot be before the start date.');
        }

        $this->hostelLeaveModel->insert([
            'allocation_id_fk'        => $allocationId,
            'boarder_admission_id_fk' => (int) $allocation['boarder_admission_id_fk'],
            'sch_id_fk'               => (int) $allocation['sch_id_fk'],
            'from_date'               => $fromDate,
            'to_date'                 => $toDate,
            'reason'                  => trim((string) $this->request->getPost('reason')) ?: null,
            'status'                  => 'Pending',
            'created_at'              => date('Y-m-d H:i:s'),
            'updated_at'              => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('hostel/allocation/detail/' . $allocationId)
            ->with('success', 'Leave request recorded successfully.');
    }

    public function decideLeave(int $leaveId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_leave_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $leave = $this->hostelLeaveModel->find($leaveId);
        if (!$leave || $leave['status'] !== 'Pending') {
            return redirect()->to('hostel/allocation')->with('error', 'Leave request not found or already decided.');
        }

        $decision = $this->request->getPost('decision');
        if (!in_array($decision, ['Approved', 'Rejected'], true)) {
            return redirect()->back()->with('error', 'Invalid decision.');
        }

        $this->hostelLeaveModel->update($leaveId, [
            'status'      => $decision,
            'decided_by'  => (int) $this->session->get('userID'),
            'remarks'     => trim((string) $this->request->getPost('remarks')) ?: null,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('hostel/allocation/detail/' . (int) $leave['allocation_id_fk'])
            ->with('success', "Leave request {$decision}.");
    }

    // ================================================================
    // MY HOSTEL — self-service (Student sees own, Parent sees children's)
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
            if ($this->grant_access('_my_hostel') || $this->grant_access('_my_child_hostel')) {
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

        $this->setPageData('My Hostel', 'Hostel', $isParent ? "Child's Hostel" : 'My Hostel');

        $data['isStudent'] = $isStudent;
        $data['isParent']  = $isParent || $isParentStaff;
        $data['admission'] = null;
        $data['allocations'] = [];
        $data['children']  = [];

        if ($isStudent) {
            $admissions = $this->admissionModel->getAdmissionByUser($userID);
            $admission  = !empty($admissions) ? $admissions[0] : null;

            $data['admission']   = $admission;
            $data['allocations'] = $admission
                ? $this->hostelAllocationModel->getByBorrowers([(int) $admission['admission_id']])
                : [];
        } else {
            $children     = $this->parentStudentModel->getChildrenOf($userID);
            $childrenData = [];

            foreach ($children as $child) {
                $childId    = (int) $child['user_id'];
                $admissions = $this->admissionModel->getAdmissionByUser($childId);
                $admission  = !empty($admissions) ? $admissions[0] : null;

                $childrenData[] = [
                    'child'       => $child,
                    'admission'   => $admission,
                    'allocations' => $admission
                        ? $this->hostelAllocationModel->getByBorrowers([(int) $admission['admission_id']])
                        : [],
                ];
            }

            $data['children'] = $childrenData;
        }

        $data['_view'] = 'app/hostel/my';
        return view('app/layouts/main', $data);
    }
}
