<?php

namespace App\Controllers;

class HostelController extends BaseController
{
    private function hostelValidationRules(): array
    {
        return [
            'hostel_name' => 'required|max_length[150]',
            'hostel_type' => 'required|in_list[Boys,Girls,Mixed]',
            'address'     => 'permit_empty|max_length[255]',
            'description' => 'permit_empty|max_length[255]',
        ];
    }

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Hostels', 'Hostel', 'Hostels');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_hostel_manage')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId = $isSuperAdmin ? 0 : (int) $this->session->get('schID');

        $data['hostels']    = $this->hostelModel->getBySchoolWithOccupancy($schId);
        $data['canAdd']     = true;
        $data['canEdit']    = true;
        $data['canDelete']  = true;
        $data['canAllocate'] = $isSuperAdmin || $this->grant_access('_hostel_allocate');
        $data['_view']      = 'app/hostel/index';

        return view('app/layouts/main', $data);
    }

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Add Hostel', 'Hostel', 'Hostels');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_hostel_manage')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $data['_view'] = 'app/hostel/form';

        return view('app/layouts/main', $data);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        if (!$this->validate($this->hostelValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $schId = (int) $this->session->get('schID');
        if ($schId <= 0) {
            return redirect()->back()->withInput()
                ->with('error', 'No school context found for your account. Please contact an administrator.');
        }

        $name = trim($this->request->getPost('hostel_name'));
        if ($this->hostelModel->nameExists($schId, $name)) {
            return redirect()->back()->withInput()
                ->with('error', "A hostel named \"{$name}\" already exists.");
        }

        $this->hostelModel->insert([
            'sch_id_fk'   => $schId,
            'hostel_name' => $name,
            'hostel_type' => $this->request->getPost('hostel_type'),
            'address'     => trim((string) $this->request->getPost('address')) ?: null,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('hostel')
            ->with('success', "Hostel \"{$name}\" added successfully.");
    }

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Edit Hostel', 'Hostel', 'Hostels');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_hostel_manage')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $hostel = $this->hostelModel->find($id);
        if (!$hostel) {
            return redirect()->to('hostel')->with('error', 'Hostel not found.');
        }

        $data['hostel'] = $hostel;
        $data['_view']  = 'app/hostel/form';

        return view('app/layouts/main', $data);
    }

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $hostel = $this->hostelModel->find($id);
        if (!$hostel) {
            return redirect()->to('hostel')->with('error', 'Hostel not found.');
        }

        if (!$this->validate($this->hostelValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $name = trim($this->request->getPost('hostel_name'));
        if ($this->hostelModel->nameExists((int) $hostel['sch_id_fk'], $name, $id)) {
            return redirect()->back()->withInput()
                ->with('error', "A hostel named \"{$name}\" already exists.");
        }

        $this->hostelModel->update($id, [
            'hostel_name' => $name,
            'hostel_type' => $this->request->getPost('hostel_type'),
            'address'     => trim((string) $this->request->getPost('address')) ?: null,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('hostel')
            ->with('success', 'Hostel updated successfully.');
    }

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $hostel = $this->hostelModel->find($id);
        if (!$hostel) {
            return redirect()->to('hostel')->with('error', 'Hostel not found.');
        }

        if ($this->hostelModel->isInUse($id)) {
            return redirect()->to('hostel')
                ->with('error', "Cannot delete \"{$hostel['hostel_name']}\" — it still has one or more rooms. Remove its rooms first.");
        }

        $this->hostelModel->delete($id);

        return redirect()->to('hostel')
            ->with('success', "Hostel \"{$hostel['hostel_name']}\" deleted successfully.");
    }

    public function detail(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Hostel Detail', 'Hostel', 'Hostels');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_hostel_detail')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $hostel = $this->hostelModel->find($id);
        if (!$hostel) {
            return redirect()->to('hostel')->with('error', 'Hostel not found.');
        }

        $data['hostel']         = $hostel;
        $data['rooms']          = $this->hostelRoomModel->getByHostel($id);
        $data['canManageRooms'] = $isSuperAdmin || $this->grant_access('_hostel_room_manage');
        $data['_view']          = 'app/hostel/detail';

        return view('app/layouts/main', $data);
    }
}
