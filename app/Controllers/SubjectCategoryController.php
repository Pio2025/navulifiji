<?php

namespace App\Controllers;

class SubjectCategoryController extends BaseController
{
    // =========================================================================
    // INDEX
    // =========================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $this->setPageData('Subject Categories', 'Subject', 'Subject Category Listing');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_subject_category_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $categories = $this->subjectCategoryModel->orderBy('sub_cat_name', 'ASC')->findAll();

        $data['categories'] = $categories;
        $data['total']      = count($categories);
        $data['active']     = count(array_filter($categories, fn($c) => (int)$c['sub_cat_status'] === 1));
        $data['inactive']   = count(array_filter($categories, fn($c) => (int)$c['sub_cat_status'] === 0));
        $data['canManage']  = $isSuperAdmin || $this->grant_access('_add_subject_category');
        $data['_view']      = 'app/subject/category/index';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // ADD
    // =========================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_add_subject_category')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Add Subject Category', 'Subject', 'Add Subject Category');

        $data['isEdit']    = false;
        $data['category']  = null;
        $data['_view']     = 'app/subject/category/form';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // STORE
    // =========================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_add_subject_category')) {
            return redirect()->to('subject/category')->with('error', 'Access denied.');
        }

        if (!$this->validate([
            'sub_cat_name' => 'required|min_length[2]|max_length[260]',
        ])) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $today = date('Y-m-d');
        $this->subjectCategoryModel->insert([
            'sub_cat_name'   => trim($this->request->getPost('sub_cat_name')),
            'sub_cat_status' => (int) ($this->request->getPost('sub_cat_status') ?? 1),
            'created_date'   => $today,
            'updated_date'   => $today,
        ]);

        return redirect()->to('subject/category')->with('success', 'Subject category added successfully.');
    }

    // =========================================================================
    // EDIT
    // =========================================================================

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_add_subject_category')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $category = $this->subjectCategoryModel->find($id);
        if (!$category) {
            return redirect()->to('subject/category')->with('error', 'Category not found.');
        }

        $this->setPageData('Edit Subject Category', 'Subject', 'Edit Subject Category');

        $data['isEdit']   = true;
        $data['category'] = $category;
        $data['_view']    = 'app/subject/category/form';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_add_subject_category')) {
            return redirect()->to('subject/category')->with('error', 'Access denied.');
        }

        if (!$this->subjectCategoryModel->find($id)) {
            return redirect()->to('subject/category')->with('error', 'Category not found.');
        }

        if (!$this->validate([
            'sub_cat_name' => 'required|min_length[2]|max_length[260]',
        ])) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->subjectCategoryModel->update($id, [
            'sub_cat_name'   => trim($this->request->getPost('sub_cat_name')),
            'sub_cat_status' => (int) ($this->request->getPost('sub_cat_status') ?? 1),
            'updated_date'   => date('Y-m-d'),
        ]);

        return redirect()->to('subject/category')->with('success', 'Subject category updated successfully.');
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
        if (!$isSuperAdmin && !$this->require_access('_add_subject_category')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.'])->setStatusCode(403);
        }

        if (!$this->subjectCategoryModel->find($id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Category not found.']);
        }

        $db    = \Config\Database::connect();
        $inUse = $db->table('subject')->where('sub_cat_id_fk', $id)->countAllResults();
        if ($inUse > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "This category is used by {$inUse} subject(s). Reassign those subjects before deleting.",
            ]);
        }

        $this->subjectCategoryModel->delete($id);

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Category deleted successfully.',
            'csrf_hash' => csrf_hash(),
        ]);
    }
}
