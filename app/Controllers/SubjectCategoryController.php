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

        $db = \Config\Database::connect();
        $data['total']     = $db->table('subject_category')->countAllResults();
        $data['active']    = $db->table('subject_category')->where('sub_cat_status', 1)->countAllResults();
        $data['inactive']  = $db->table('subject_category')->where('sub_cat_status', 0)->countAllResults();
        $data['canManage'] = $isSuperAdmin || $this->grant_access('_add_subject_category');
        $data['_view']     = 'app/subject/category/index';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // AJAX LISTING (DataTables server-side)
    // =========================================================================

    public function getCategoryListing()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []])->setStatusCode(401);
        }

        $req    = service('request');
        $draw   = (int) ($req->getPost('draw') ?? 1);
        $start  = (int) ($req->getPost('start') ?? 0);
        $length = (int) ($req->getPost('length') ?? 15);

        $searchData  = $req->getPost('search');
        $searchValue = is_array($searchData) ? trim($searchData['value'] ?? '') : '';

        $orderData        = $req->getPost('order');
        $orderColumnIndex = is_array($orderData) ? (int) ($orderData[0]['column'] ?? 0) : 0;
        $orderDir         = is_array($orderData) ? ($orderData[0]['dir'] ?? 'asc') : 'asc';
        $orderDir         = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';

        $columnMap = ['sub_cat_name', 'sub_cat_status', null];
        $orderCol  = $columnMap[$orderColumnIndex] ?? 'sub_cat_name';

        $statusFilter = $req->getPost('status_filter');

        $db           = \Config\Database::connect();
        $recordsTotal = $db->table('subject_category')->countAllResults();

        $builder = $db->table('subject_category');

        if ($searchValue !== '') {
            $builder->like('sub_cat_name', $searchValue);
        }

        if ($statusFilter !== null && $statusFilter !== '') {
            $builder->where('sub_cat_status', (int) $statusFilter);
        }

        $recordsFiltered = $builder->countAllResults(false);

        if ($orderCol !== null) {
            $builder->orderBy($orderCol, $orderDir);
        } else {
            $builder->orderBy('sub_cat_name', 'ASC');
        }

        if ($length !== -1) {
            $builder->limit($length, $start);
        }

        $rows = $builder->get()->getResultArray();

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $canManage    = $isSuperAdmin || $this->grant_access('_add_subject_category');

        $data = [];
        foreach ($rows as $row) {
            $badge = (int) $row['sub_cat_status'] === 1
                ? '<span class="badge badge-light-success fs-8">Active</span>'
                : '<span class="badge badge-light-danger fs-8">Inactive</span>';

            $actions = '';
            if ($canManage) {
                $actions .= '<a href="' . base_url('subject/category/edit/' . (int)$row['sub_cat_id']) . '" class="btn btn-icon btn-sm btn-light-primary me-1">'
                    . '<i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i></a>';
                $actions .= '<button type="button" class="btn btn-icon btn-sm btn-light-danger btn-delete-category"'
                    . ' data-id="' . (int)$row['sub_cat_id'] . '"'
                    . ' data-name="' . esc($row['sub_cat_name']) . '">'
                    . '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
            }

            $data[] = [
                'sub_cat_name'   => esc($row['sub_cat_name']),
                'sub_cat_status' => $badge,
                'actions'        => $actions,
            ];
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
            'csrf_hash'       => csrf_hash(),
        ]);
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
