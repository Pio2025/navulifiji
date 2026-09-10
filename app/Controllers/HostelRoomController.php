<?php

namespace App\Controllers;

class HostelRoomController extends BaseController
{
    private function roomValidationRules(): array
    {
        return [
            'room_number' => 'required|max_length[20]',
            'capacity'    => 'required|integer|greater_than[0]',
            'description' => 'permit_empty|max_length[255]',
        ];
    }

    public function add(int $hostelId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Add Room', 'Hostel', 'Hostels');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_hostel_room_manage')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $hostel = $this->hostelModel->find($hostelId);
        if (!$hostel) {
            return redirect()->to('hostel')->with('error', 'Hostel not found.');
        }

        $data['hostel'] = $hostel;
        $data['room']   = null;
        $data['_view']  = 'app/hostel/room_form';

        return view('app/layouts/main', $data);
    }

    public function store(int $hostelId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_room_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $hostel = $this->hostelModel->find($hostelId);
        if (!$hostel) {
            return redirect()->to('hostel')->with('error', 'Hostel not found.');
        }

        if (!$this->validate($this->roomValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $roomNumber = trim($this->request->getPost('room_number'));
        if ($this->hostelRoomModel->numberExists($hostelId, $roomNumber)) {
            return redirect()->back()->withInput()
                ->with('error', "Room \"{$roomNumber}\" already exists in this hostel.");
        }

        $this->hostelRoomModel->insert([
            'hostel_id_fk' => $hostelId,
            'sch_id_fk'    => (int) $hostel['sch_id_fk'],
            'room_number'  => $roomNumber,
            'capacity'     => (int) $this->request->getPost('capacity'),
            'description'  => trim((string) $this->request->getPost('description')) ?: null,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('hostel/detail/' . $hostelId)
            ->with('success', "Room \"{$roomNumber}\" added successfully.");
    }

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Edit Room', 'Hostel', 'Hostels');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_hostel_room_manage')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $room = $this->hostelRoomModel->getDetail($id);
        if (!$room) {
            return redirect()->to('hostel')->with('error', 'Room not found.');
        }

        $data['hostel'] = ['hostel_id' => (int) $room['hostel_id_fk'], 'hostel_name' => $room['hostel_name']];
        $data['room']   = $room;
        $data['_view']  = 'app/hostel/room_form';

        return view('app/layouts/main', $data);
    }

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_room_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $room = $this->hostelRoomModel->find($id);
        if (!$room) {
            return redirect()->to('hostel')->with('error', 'Room not found.');
        }

        if (!$this->validate($this->roomValidationRules())) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $roomNumber = trim($this->request->getPost('room_number'));
        if ($this->hostelRoomModel->numberExists((int) $room['hostel_id_fk'], $roomNumber, $id)) {
            return redirect()->back()->withInput()
                ->with('error', "Room \"{$roomNumber}\" already exists in this hostel.");
        }

        $newCapacity  = (int) $this->request->getPost('capacity');
        $occupied     = $this->hostelRoomModel->getOccupiedCount($id);
        if ($occupied > $newCapacity) {
            return redirect()->back()->withInput()
                ->with('error', "Cannot set capacity below the number of boarders currently occupying this room ({$occupied}).");
        }

        $this->hostelRoomModel->update($id, [
            'room_number' => $roomNumber,
            'capacity'    => $newCapacity,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('hostel/detail/' . (int) $room['hostel_id_fk'])
            ->with('success', "Room \"{$roomNumber}\" updated successfully.");
    }

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_hostel_room_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $room = $this->hostelRoomModel->find($id);
        if (!$room) {
            return redirect()->to('hostel')->with('error', 'Room not found.');
        }

        $hostelId = (int) $room['hostel_id_fk'];

        if ($this->hostelRoomModel->isInUse($id)) {
            return redirect()->to('hostel/detail/' . $hostelId)
                ->with('error', "Cannot delete room \"{$room['room_number']}\" — it has allocation history on file.");
        }

        $this->hostelRoomModel->delete($id);

        return redirect()->to('hostel/detail/' . $hostelId)
            ->with('success', "Room \"{$room['room_number']}\" deleted successfully.");
    }
}
