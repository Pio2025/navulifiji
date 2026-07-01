<?php

namespace App\Controllers;

class SchoolCategoryController extends BaseController
{
    private function validateTermDates(int $numTerms): ?string
    {
        for ($i = 1; $i <= $numTerms; $i++) {
            $sd  = (int) $this->request->getPost("term_start_day_{$i}");
            $sm  = (int) $this->request->getPost("term_start_month_{$i}");
            $ed  = (int) $this->request->getPost("term_end_day_{$i}");
            $em  = (int) $this->request->getPost("term_end_month_{$i}");
            $wks = (int) $this->request->getPost("num_of_week_{$i}");

            if ($sd < 1 || $sd > 31 || $sm < 1 || $sm > 12) {
                return "Term {$i}: invalid start date.";
            }
            if ($ed < 1 || $ed > 31 || $em < 1 || $em > 12) {
                return "Term {$i}: invalid end date.";
            }
            if ($em * 31 + $ed < $sm * 31 + $sd) {
                return "Term {$i}: end date cannot be before start date.";
            }
            if ($wks < 1) {
                return "Term {$i}: number of weeks must be at least 1.";
            }
        }
        return null;
    }

    private function buildTermRow(int $configId, int $i): array
    {
        return [
            'sch_cat_con_id_fk' => $configId,
            'term_num'          => $i,
            'num_of_week'       => (int) $this->request->getPost("num_of_week_{$i}"),
            'term_start_day'    => (int) $this->request->getPost("term_start_day_{$i}"),
            'term_start_month'  => (int) $this->request->getPost("term_start_month_{$i}"),
            'term_end_day'      => (int) $this->request->getPost("term_end_day_{$i}"),
            'term_end_month'    => (int) $this->request->getPost("term_end_month_{$i}"),
        ];
    }

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('School Category Listing', 'School', 'School Category Listing');

        if (!$this->require_access('_school_category_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $categories = $this->schoolCategoryModel->getAllSchoolCategory();

        foreach ($categories as &$cat) {
            $config = $this->schoolCategoryConfigModel->getByCategoryId($cat['sch_cat_id']);
            $cat['config'] = $config;
            $cat['terms']  = $config
                ? $this->schoolCategoryTermModel->getByConfigId($config['sch_cat_con_id'])
                : [];
        }
        unset($cat);

        $data['categories'] = $categories;
        $data['canAdd']     = $this->grant_access('_add_school_category');
        $data['canEdit']    = $this->grant_access('_edit_school_category');
        $data['canDelete']  = $this->grant_access('_remove_school_category');
        $data['_view']      = 'app/school/category/index';

        return view('app/layouts/main', $data);
    }

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Add School Category', 'School', 'Add School Category');

        if (!$this->require_access('_add_school_category')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $data['_view'] = 'app/school/category/add';

        return view('app/layouts/main', $data);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        if (!$this->require_access('_add_school_category')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $rules = [
            'sch_cat_initial'     => 'required|max_length[60]',
            'sch_cat_name'        => 'required|max_length[300]',
            'num_of_term_in_year' => 'required|integer|greater_than[0]|less_than[13]',
            'label_for_term'      => 'required|max_length[60]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $numTerms = (int) $this->request->getPost('num_of_term_in_year');

        if ($err = $this->validateTermDates($numTerms)) {
            return redirect()->back()->withInput()->with('error', $err);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $catId = $this->schoolCategoryModel->addSchoolCategory([
            'sch_cat_initial' => trim($this->request->getPost('sch_cat_initial')),
            'sch_cat_name'    => trim($this->request->getPost('sch_cat_name')),
        ]);

        $configId = $this->schoolCategoryConfigModel->insert([
            'sch_cat_id_fk'  => $catId,
            'label_for_term' => trim($this->request->getPost('label_for_term')),
        ]);

        for ($i = 1; $i <= $numTerms; $i++) {
            $this->schoolCategoryTermModel->insert($this->buildTermRow($configId, $i));
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to save school category. Please try again.');
        }

        return redirect()->to('school/category')
            ->with('success', 'School category "' . esc($this->request->getPost('sch_cat_name')) . '" added successfully.');
    }

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Edit School Category', 'School', 'School Category Listing');

        if (!$this->require_access('_edit_school_category')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $category = $this->schoolCategoryModel->find($id);
        if (!$category) {
            return redirect()->to('school/category')->with('error', 'School category not found.');
        }

        $config = $this->schoolCategoryConfigModel->getByCategoryId($id);
        $terms  = $config
            ? $this->schoolCategoryTermModel->getByConfigId($config['sch_cat_con_id'])
            : [];

        $data['category'] = $category;
        $data['config']   = $config;
        $data['terms']    = $terms;
        $data['_view']    = 'app/school/category/edit';

        return view('app/layouts/main', $data);
    }

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        if (!$this->require_access('_edit_school_category')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $category = $this->schoolCategoryModel->find($id);
        if (!$category) {
            return redirect()->to('school/category')->with('error', 'School category not found.');
        }

        $rules = [
            'sch_cat_initial'     => 'required|max_length[60]',
            'sch_cat_name'        => 'required|max_length[300]',
            'num_of_term_in_year' => 'required|integer|greater_than[0]|less_than[13]',
            'label_for_term'      => 'required|max_length[60]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $numTerms = (int) $this->request->getPost('num_of_term_in_year');

        if ($err = $this->validateTermDates($numTerms)) {
            return redirect()->back()->withInput()->with('error', $err);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->schoolCategoryModel->updateSchoolCategory($id, [
            'sch_cat_initial' => trim($this->request->getPost('sch_cat_initial')),
            'sch_cat_name'    => trim($this->request->getPost('sch_cat_name')),
        ]);

        $existingConfig = $this->schoolCategoryConfigModel->getByCategoryId($id);
        $configData = ['label_for_term' => trim($this->request->getPost('label_for_term'))];

        if ($existingConfig) {
            $this->schoolCategoryConfigModel->update($existingConfig['sch_cat_con_id'], $configData);
            $configId = $existingConfig['sch_cat_con_id'];
        } else {
            $configData['sch_cat_id_fk'] = $id;
            $configId = $this->schoolCategoryConfigModel->insert($configData);
        }

        $db->table('sch_cat_term_entry')->where('sch_cat_con_id_fk', $configId)->delete();
        for ($i = 1; $i <= $numTerms; $i++) {
            $this->schoolCategoryTermModel->insert($this->buildTermRow($configId, $i));
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update school category. Please try again.');
        }

        return redirect()->to('school/category')
            ->with('success', 'School category updated successfully.');
    }

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        if (!$this->require_access('_remove_school_category')) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $category = $this->schoolCategoryModel->find($id);
        if (!$category) {
            return redirect()->to('school/category')->with('error', 'School category not found.');
        }

        $db = \Config\Database::connect();
        $usedBy = $db->table('school')->where('sch_cat_id_fk', $id)->countAllResults();
        if ($usedBy > 0) {
            return redirect()->to('school/category')
                ->with('error', "Cannot delete \"{$category['sch_cat_name']}\" — {$usedBy} school(s) are currently using this category.");
        }

        $db->transStart();

        $config = $this->schoolCategoryConfigModel->getByCategoryId($id);
        if ($config) {
            $db->table('sch_cat_term_entry')->where('sch_cat_con_id_fk', $config['sch_cat_con_id'])->delete();
            $db->table('school_category_config')->where('sch_cat_con_id', $config['sch_cat_con_id'])->delete();
        }
        $this->schoolCategoryModel->deleteSchoolCategory($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('school/category')
                ->with('error', 'Failed to delete school category. Please try again.');
        }

        return redirect()->to('school/category')
            ->with('success', "School category \"{$category['sch_cat_name']}\" deleted successfully.");
    }
}
