<?php

namespace App\Controllers;

class SubjectController extends BaseController
{
    // =========================================================================
    // INDEX
    // =========================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $this->setPageData('Subjects', 'Subject', 'Subject Listing');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_subject_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $subjects = $this->subjectModel->getAllWithLevel();
        $levels   = $this->levelModel->findAll();

        $data['subjects']  = $subjects;
        $data['levels']    = $levels;
        $data['canAdd']    = $isSuperAdmin || $this->grant_access('_add_subject');
        $data['canEdit']   = $isSuperAdmin || $this->grant_access('_edit_subject');
        $data['canDelete'] = $isSuperAdmin || $this->grant_access('_remove_subject');
        $data['_view']     = 'app/subject/index';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // ADD
    // =========================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_add_subject')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Add Subject', 'Subject', 'Add Subject');

        $data['levels']  = $this->levelModel->findAll();
        $data['isEdit']  = false;
        $data['subject'] = null;
        $data['_view']   = 'app/subject/form';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // STORE
    // =========================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_add_subject')) {
            return redirect()->to('subject')->with('error', 'Access denied.');
        }

        if (!$this->validate([
            'subject_name' => 'required|min_length[2]|max_length[60]',
            'level_id_fk'  => 'required|integer',
        ])) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->subjectModel->insert([
            'subject_name'  => trim($this->request->getPost('subject_name')),
            'level_id_fk'   => (int) $this->request->getPost('level_id_fk'),
            'is_examinable' => (int) ($this->request->getPost('is_examinable') ?? 0),
            'sub_image'     => '',
        ]);

        return redirect()->to('subject')->with('success', 'Subject added successfully.');
    }

    // =========================================================================
    // EDIT
    // =========================================================================

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_edit_subject')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $subject = $this->subjectModel->find($id);
        if (!$subject) {
            return redirect()->to('subject')->with('error', 'Subject not found.');
        }

        $this->setPageData('Edit Subject', 'Subject', 'Edit Subject');

        $data['levels']  = $this->levelModel->findAll();
        $data['isEdit']  = true;
        $data['subject'] = $subject;
        $data['_view']   = 'app/subject/form';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_edit_subject')) {
            return redirect()->to('subject')->with('error', 'Access denied.');
        }

        if (!$this->subjectModel->find($id)) {
            return redirect()->to('subject')->with('error', 'Subject not found.');
        }

        if (!$this->validate([
            'subject_name' => 'required|min_length[2]|max_length[60]',
            'level_id_fk'  => 'required|integer',
        ])) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->subjectModel->update($id, [
            'subject_name'  => trim($this->request->getPost('subject_name')),
            'level_id_fk'   => (int) $this->request->getPost('level_id_fk'),
            'is_examinable' => (int) ($this->request->getPost('is_examinable') ?? 0),
        ]);

        return redirect()->to('subject')->with('success', 'Subject updated successfully.');
    }

    // =========================================================================
    // DELETE (AJAX)
    // =========================================================================

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_remove_subject')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.'])->setStatusCode(403);
        }

        if (!$this->subjectModel->find($id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Subject not found.']);
        }

        $db    = \Config\Database::connect();
        $inUse = $db->table('sch_subject')->where('subject_id_fk', $id)->countAllResults();
        if ($inUse > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "This subject is registered in {$inUse} school(s). Remove those school assignments before deleting.",
            ]);
        }

        $this->subjectModel->delete($id);

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Subject deleted successfully.',
            'csrf_hash' => csrf_hash(),
        ]);
    }
}
