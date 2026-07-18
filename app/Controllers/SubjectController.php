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

        $db = \Config\Database::connect();
        $data['totalSubjects']   = $db->table('subject')->countAllResults();
        $data['totalExaminable'] = $db->table('subject')->where('is_examinable', 1)->countAllResults();
        $data['totalNonExam']    = $db->table('subject')->where('is_examinable', 0)->countAllResults();
        $data['levels']          = $this->levelModel->findAll();
        $data['canAdd']          = $isSuperAdmin || $this->grant_access('_add_subject');
        $data['_view']           = 'app/subject/index';
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
    // EXPORT (CSV / Excel / PDF / plain-text for clipboard)
    // =========================================================================

    public function export()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_subject_listing')) {
            return redirect()->to('subject');
        }

        $format     = $this->request->getGet('format') ?? 'csv';
        $search     = trim($this->request->getGet('search') ?? '');
        $levelId    = (int) ($this->request->getGet('level_id') ?? 0);
        $examFilter = $this->request->getGet('is_examinable');

        $db      = \Config\Database::connect();
        $builder = $db->table('subject s')
            ->select('s.subject_name, l.level_name, s.is_examinable')
            ->join('level l', 'l.level_id = s.level_id_fk', 'left');

        if ($search !== '') {
            $builder->groupStart()
                ->like('s.subject_name', $search)
                ->orLike('l.level_name', $search)
                ->groupEnd();
        }
        if ($levelId > 0) {
            $builder->where('s.level_id_fk', $levelId);
        }
        if ($examFilter !== null && $examFilter !== '') {
            $builder->where('s.is_examinable', (int) $examFilter);
        }

        $builder->orderBy('l.level_id', 'ASC')->orderBy('s.subject_name', 'ASC');
        $rows = $builder->get()->getResultArray();

        $stamp = date('Ymd_His');

        match ($format) {
            'excel' => $this->_doExportExcel($rows, "subjects_{$stamp}.xls"),
            'pdf'   => $this->_doExportPdf($rows,   "subjects_{$stamp}.pdf"),
            'copy'  => $this->_doExportCopy($rows),
            default => $this->_doExportCsv($rows,   "subjects_{$stamp}.csv"),
        };
    }

    private function _doExportCsv(array $rows, string $filename): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Subject Name', 'Year Level', 'Type']);
        foreach ($rows as $row) {
            fputcsv($out, [
                $row['subject_name'],
                $row['level_name'] ?? '',
                (int) $row['is_examinable'] ? 'Examinable' : 'Non-Examinable',
            ]);
        }
        fclose($out);
        exit;
    }

    private function _doExportExcel(array $rows, string $filename): void
    {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        echo '<table border="1" cellpadding="4" cellspacing="0">';
        echo '<thead><tr><th>Subject Name</th><th>Year Level</th><th>Type</th></tr></thead>';
        echo '<tbody>';
        foreach ($rows as $row) {
            $type = (int) $row['is_examinable'] ? 'Examinable' : 'Non-Examinable';
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['subject_name'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['level_name'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . $type . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        exit;
    }

    private function _doExportPdf(array $rows, string $filename): void
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('NavuliFiji');
        $pdf->SetTitle('Subject Catalogue');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Subject Catalogue', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(0, 6, 'Generated: ' . date('d M Y H:i'), 0, 1, 'C');
        $pdf->Ln(4);

        $html = '<table border="1" cellpadding="4" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color:#1b84ff;color:#ffffff;font-weight:bold;">
                    <th width="50%">Subject Name</th>
                    <th width="30%">Year Level</th>
                    <th width="20%">Type</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($rows as $i => $row) {
            $bg   = ($i % 2 === 0) ? '#ffffff' : '#f5f8ff';
            $type = (int) $row['is_examinable'] ? 'Examinable' : 'Non-Examinable';
            $html .= '<tr style="background-color:' . $bg . ';">';
            $html .= '<td>' . htmlspecialchars($row['subject_name'], ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . htmlspecialchars($row['level_name'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . $type . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($filename, 'D');
        exit;
    }

    private function _doExportCopy(array $rows): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        $lines = ["Subject Name\tYear Level\tType"];
        foreach ($rows as $row) {
            $type    = (int) $row['is_examinable'] ? 'Examinable' : 'Non-Examinable';
            $lines[] = $row['subject_name'] . "\t" . ($row['level_name'] ?? '') . "\t" . $type;
        }
        echo implode("\n", $lines);
        exit;
    }

    // =========================================================================
    // SERVER-SIDE DATATABLE LISTING
    // =========================================================================

    public function getSubjectListing()
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

        $columnMap = ['s.subject_name', 'l.level_name', null, null];
        $orderCol  = $columnMap[$orderColumnIndex] ?? 's.subject_name';

        $levelId    = (int) ($req->getPost('level_id') ?? 0);
        $examFilter = $req->getPost('is_examinable');

        $db = \Config\Database::connect();
        $recordsTotal = $db->table('subject')->countAllResults();

        $builder = $db->table('subject s')
            ->select('s.subject_id, s.subject_name, s.is_examinable, l.level_id, l.level_name')
            ->join('level l', 'l.level_id = s.level_id_fk', 'left');

        if ($searchValue !== '') {
            $builder->groupStart()
                ->like('s.subject_name', $searchValue)
                ->orLike('l.level_name', $searchValue)
                ->groupEnd();
        }

        if ($levelId > 0) {
            $builder->where('s.level_id_fk', $levelId);
        }

        if ($examFilter !== null && $examFilter !== '') {
            $builder->where('s.is_examinable', (int) $examFilter);
        }

        $recordsFiltered = $builder->countAllResults(false);

        if ($orderCol !== null) {
            $builder->orderBy($orderCol, $orderDir);
        } else {
            $builder->orderBy('l.level_id', 'ASC')->orderBy('s.subject_name', 'ASC');
        }

        if ($length !== -1) {
            $builder->limit($length, $start);
        }

        $rows = $builder->get()->getResultArray();

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $canEdit      = $isSuperAdmin || $this->grant_access('_edit_subject');
        $canDelete    = $isSuperAdmin || $this->grant_access('_remove_subject');

        $data = [];
        foreach ($rows as $row) {
            $badge = (int) $row['is_examinable']
                ? '<span class="badge badge-light-success fs-8">Examinable</span>'
                : '<span class="badge badge-light-warning fs-8">Non-Exam</span>';

            $actions = '';
            if ($canEdit) {
                $actions .= '<a href="' . base_url('subject/edit/' . (int)$row['subject_id']) . '" class="btn btn-icon btn-sm btn-light-primary me-1">'
                    . '<i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i></a>';
            }
            if ($canDelete) {
                $actions .= '<button type="button" class="btn btn-icon btn-sm btn-light-danger btn-delete-subject"'
                    . ' data-id="' . (int)$row['subject_id'] . '"'
                    . ' data-name="' . esc($row['subject_name']) . '">'
                    . '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
            }
            if (!$actions) {
                $actions = '—';
            }

            $data[] = [
                'subject_name'  => '<span class="fw-semibold text-gray-800 fs-7">' . esc($row['subject_name']) . '</span>',
                'level_name'    => '<span class="text-muted fs-7">' . esc($row['level_name'] ?? '—') . '</span>',
                'is_examinable' => $badge,
                'actions'       => '<div class="d-flex justify-content-end">' . $actions . '</div>',
            ];
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
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
