<?php

namespace App\Controllers;

class LibraryCategoryController extends BaseController
{
    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Book Categories', 'Library', 'Book Categories');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_library_category_manage')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId = $isSuperAdmin ? 0 : (int) $this->session->get('schID');

        $data['categories'] = $this->libraryCategoryModel->getBySchool($schId);
        $data['canAdd']     = true;
        $data['canEdit']    = true;
        $data['canDelete']  = true;
        $data['_view']      = 'app/library/category/index';

        return view('app/layouts/main', $data);
    }

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Add Book Category', 'Library', 'Book Categories');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_library_category_manage')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $data['_view'] = 'app/library/category/add';

        return view('app/layouts/main', $data);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_library_category_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $rules = [
            'category_name' => 'required|max_length[100]',
            'description'   => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $schId = (int) $this->session->get('schID');
        $name  = trim($this->request->getPost('category_name'));

        if ($schId <= 0) {
            return redirect()->back()->withInput()
                ->with('error', 'No school context found for your account. Please contact an administrator.');
        }

        if ($this->libraryCategoryModel->nameExists($schId, $name)) {
            return redirect()->back()->withInput()
                ->with('error', "A category named \"{$name}\" already exists.");
        }

        $this->libraryCategoryModel->insert([
            'sch_id_fk'     => $schId,
            'category_name' => $name,
            'description'   => trim($this->request->getPost('description')) ?: null,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('library/category')
            ->with('success', "Book category \"{$name}\" added successfully.");
    }

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Edit Book Category', 'Library', 'Book Categories');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_library_category_manage')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $category = $this->libraryCategoryModel->find($id);
        if (!$category) {
            return redirect()->to('library/category')->with('error', 'Book category not found.');
        }

        $data['category'] = $category;
        $data['_view']     = 'app/library/category/edit';

        return view('app/layouts/main', $data);
    }

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_library_category_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $category = $this->libraryCategoryModel->find($id);
        if (!$category) {
            return redirect()->to('library/category')->with('error', 'Book category not found.');
        }

        $rules = [
            'category_name' => 'required|max_length[100]',
            'description'   => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $name = trim($this->request->getPost('category_name'));

        if ($this->libraryCategoryModel->nameExists((int) $category['sch_id_fk'], $name, $id)) {
            return redirect()->back()->withInput()
                ->with('error', "A category named \"{$name}\" already exists.");
        }

        $this->libraryCategoryModel->update($id, [
            'category_name' => $name,
            'description'   => trim($this->request->getPost('description')) ?: null,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('library/category')
            ->with('success', 'Book category updated successfully.');
    }

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_library_category_manage')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $category = $this->libraryCategoryModel->find($id);
        if (!$category) {
            return redirect()->to('library/category')->with('error', 'Book category not found.');
        }

        if ($this->libraryCategoryModel->isInUse($id)) {
            return redirect()->to('library/category')
                ->with('error', "Cannot delete \"{$category['category_name']}\" — one or more books are still assigned to this category.");
        }

        $this->libraryCategoryModel->delete($id);

        return redirect()->to('library/category')
            ->with('success', "Book category \"{$category['category_name']}\" deleted successfully.");
    }
}
