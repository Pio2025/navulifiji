<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class AnnouncementController extends BaseController
{
    private AnnouncementModel $annModel;
    private const UPLOAD_DIR   = 'uploads/announcements/';
    private const MAX_FILE_MB  = 10;
    private const ALLOWED_MIME = [
        'application/pdf',
        'image/jpeg', 'image/png', 'image/webp',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->annModel = new AnnouncementModel();
    }

    // ─── access helpers ───────────────────────────────────────────────────────

    private function isSuperAdmin(): bool
    {
        return (int) $this->session->get('roleID') === 1;
    }

    /** School Admin (2), Admin (7), or Super Admin (1), or granted permission. */
    private function canPost(): bool
    {
        $cat = (int) $this->session->get('roleCatID');
        return $this->isSuperAdmin()
            || in_array($cat, [2, 7])
            || $this->grant_access('_post_announcement');
    }

    private function canManage(): bool
    {
        return $this->isSuperAdmin() || $this->grant_access('_manage_announcement');
    }

    private function uploadDir(): string
    {
        $dir = FCPATH . self::UPLOAD_DIR;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    // ─── school ID resolution (handles stale sessions where schID = 0) ────────

    private function resolveSchoolId(): int
    {
        $schId  = (int) $this->session->get('schID');
        $userId = (int) $this->session->get('userID');

        if ($schId !== 0) {
            return $schId;
        }

        $db = \Config\Database::connect();

        // Try staff table first
        $row = $db->table('staff')
            ->select('sch_id_fk')
            ->where('user_id_fk', $userId)
            ->where('staff_status', 'Active')
            ->limit(1)
            ->get()->getRowArray();
        if ($row) {
            $schId = (int) $row['sch_id_fk'];
            $this->session->set('schID', $schId);
            return $schId;
        }

        // Fall back to classroom assignment chain
        $row = $db->table('classroom_subject_teacher cst')
            ->select('sl.sch_id_fk')
            ->join('classroom_subject cs', 'cs.class_sub_id = cst.class_sub_id_fk')
            ->join('classroom c',          'c.class_id = cs.class_id_fk')
            ->join('stream s',             's.stream_id = c.stream_id_fk')
            ->join('sch_level sl',         'sl.sch_level_id = s.sch_level_id_fk')
            ->where('cst.user_id_fk', $userId)
            ->limit(1)
            ->get()->getRowArray();
        if ($row) {
            $schId = (int) $row['sch_id_fk'];
            $this->session->set('schID', $schId);
            return $schId;
        }

        return 0;
    }

    // ─── All schools (for admin school picker) ───────────────────────────────

    private function getAllSchools(): array
    {
        return \Config\Database::connect()
            ->table('school')
            ->select('sch_id, sch_name')
            ->where('sch_status', 'Active')
            ->orderBy('sch_name', 'ASC')
            ->get()->getResultArray();
    }

    // ─── Parent school resolution ─────────────────────────────────────────────

    private function resolveParentSchools(int $userId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT DISTINCT s.sch_id, s.sch_name, s.sch_logo
            FROM parent_student ps
            INNER JOIN users stu ON stu.user_id = ps.student_user_id_fk
            INNER JOIN admission a
                ON a.user_id_fk = stu.user_id AND a.admission_status = 'Active'
            INNER JOIN school s ON s.sch_id = a.sch_id_fk
            WHERE ps.parent_user_id_fk = ?
            ORDER BY s.sch_name
        ", [$userId])->getResultArray();
    }

    // ─── INDEX ────────────────────────────────────────────────────────────────

    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->annModel->expireOld();

        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');

        $isPureParent    = $roleCat === 6;
        $isTeacherParent = $roleCat === 3 && $this->hasParentFlag($userId);
        // Other staff categories with is_a_parent set behave like a pure parent (unchanged legacy behavior)
        $isParent = $isPureParent
            || ($roleCat !== 3 && $this->hasParentFlag($userId));

        $parentSchools  = [];
        $activeSchoolId = 0;

        if ($isParent) {
            $parentSchools = $this->resolveParentSchools($userId);

            $reqSchId = (int) $this->request->getGet('sch_id');
            $schIds   = array_column($parentSchools, 'sch_id');
            $activeSchoolId = in_array($reqSchId, $schIds, false) ? $reqSchId
                : (empty($parentSchools) ? 0 : (int) $parentSchools[0]['sch_id']);

            $schId = $activeSchoolId;
        } elseif ($isTeacherParent) {
            $ownSchId = $this->resolveSchoolId();
            $childSchools = array_values(array_filter(
                $this->resolveParentSchools($userId),
                fn ($s) => (int) $s['sch_id'] !== $ownSchId
            ));

            if (!empty($childSchools)) {
                $ownSchoolRow = \Config\Database::connect()->table('school')
                    ->select('sch_id, sch_name, sch_logo')->where('sch_id', $ownSchId)
                    ->get()->getRowArray();
                $parentSchools = array_merge([$ownSchoolRow], $childSchools);

                $reqSchId = (int) $this->request->getGet('sch_id');
                $schIds   = array_column($parentSchools, 'sch_id');
                $activeSchoolId = in_array($reqSchId, $schIds, false) ? $reqSchId : $ownSchId;
                $schId = $activeSchoolId;
            } else {
                $schId = $ownSchId;
            }
        } else {
            $schId = $this->resolveSchoolId();
        }

        $announcements = $this->annModel->getActiveForSchool($schId);

        // Mark all visible announcements as read for this user on page visit
        $this->annModel->markAllReadForUser($userId, $schId);

        // Super admins (and anyone without an assigned school) must choose a school when posting
        $needsSchoolSelect = $this->isSuperAdmin() || (int) $this->session->get('schID') === 0;
        $allSchools        = ($needsSchoolSelect && $this->canPost()) ? $this->getAllSchools() : [];

        $this->setPageData('Announcements', 'Dashboard', 'Announcements');
        $data = $this->loadCommonData('app/announcement/index', [
            'announcements'     => $announcements,
            'canPost'           => $this->canPost(),
            'canManage'         => $this->canManage(),
            'myUserId'          => $userId,
            'parentSchools'     => $parentSchools,
            'activeSchoolId'    => $activeSchoolId,
            'needsSchoolSelect' => $needsSchoolSelect,
            'allSchools'        => $allSchools,
        ]);

        return view('app/layouts/main', $data);
    }

    // ─── STORE ───────────────────────────────────────────────────────────────

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn() || !$this->canPost()) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $userId = (int) $this->session->get('userID');

        // Super admins / unassigned users must post to a chosen school
        if ($this->isSuperAdmin() || (int) $this->session->get('schID') === 0) {
            $schId = (int) $this->request->getPost('sch_id');
            if ($schId <= 0) {
                return redirect()->back()->with('error', 'Please select a school for this announcement.')->withInput();
            }
        } else {
            $schId = (int) $this->session->get('schID');
        }

        $title    = trim($this->request->getPost('title')   ?? '');
        $content  = trim($this->request->getPost('content') ?? '');
        $priority = $this->request->getPost('priority') ?: 'Info';
        $expiresInput = $this->request->getPost('expires_at');

        if ($title === '' || $content === '') {
            return redirect()->back()->with('error', 'Title and content are required.')->withInput();
        }
        if (!in_array($priority, ['Info', 'Important', 'Critical'])) {
            $priority = 'Info';
        }

        $expiresAt = null;
        if (!empty($expiresInput)) {
            $expiresAt = date('Y-m-d 23:59:59', strtotime($expiresInput));
        }

        // Handle single file attachment
        [$fileName, $fileType, $origName] = $this->handleUpload('attachment');

        $this->annModel->insert([
            'sch_id_fk'            => $schId,
            'posted_by'            => $userId,
            'title'                => $title,
            'content'              => $content,
            'priority'             => $priority,
            'attachment'           => $fileName,
            'attachment_type'      => $fileType,
            'attachment_name'      => $origName,
            'expires_at'           => $expiresAt,
            'announcement_status'  => 'Active',
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('dashboard/announcement')->with('success', 'Announcement published.');
    }

    // ─── UPDATE ──────────────────────────────────────────────────────────────

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->back()->with('error', 'Please log in.');
        }

        $ann = $this->annModel->find($id);
        if (!$ann) {
            return redirect()->back()->with('error', 'Announcement not found.');
        }

        $userId  = (int) $this->session->get('userID');
        $isOwner = (int)$ann['posted_by'] === $userId;
        if (!$isOwner && !$this->canManage()) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $title    = trim($this->request->getPost('title')   ?? '');
        $content  = trim($this->request->getPost('content') ?? '');
        $priority = $this->request->getPost('priority') ?: 'Info';
        $expiresInput = $this->request->getPost('expires_at');

        if ($title === '' || $content === '') {
            return redirect()->back()->with('error', 'Title and content are required.');
        }
        if (!in_array($priority, ['Info', 'Important', 'Critical'])) {
            $priority = 'Info';
        }

        $expiresAt = empty($expiresInput) ? $ann['expires_at'] : date('Y-m-d 23:59:59', strtotime($expiresInput));

        // Optional new file
        [$fileName, $fileType, $origName] = $this->handleUpload('attachment');
        if ($fileName) {
            // Delete old file
            if ($ann['attachment']) {
                $old = FCPATH . self::UPLOAD_DIR . $ann['attachment'];
                if (file_exists($old)) unlink($old);
            }
        } else {
            // Keep existing
            $fileName  = $ann['attachment'];
            $fileType  = $ann['attachment_type'];
            $origName  = $ann['attachment_name'];
        }

        $this->annModel->update($id, [
            'title'           => $title,
            'content'         => $content,
            'priority'        => $priority,
            'attachment'      => $fileName,
            'attachment_type' => $fileType,
            'attachment_name' => $origName,
            'expires_at'      => $expiresAt,
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('dashboard/announcement')->with('success', 'Announcement updated.');
    }

    // ─── DELETE ──────────────────────────────────────────────────────────────

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->back()->with('error', 'Please log in.');
        }

        $ann = $this->annModel->find($id);
        if (!$ann) {
            return redirect()->back()->with('error', 'Announcement not found.');
        }

        $userId  = (int) $this->session->get('userID');
        $isOwner = (int)$ann['posted_by'] === $userId;
        if (!$isOwner && !$this->canManage()) {
            return redirect()->back()->with('error', 'You cannot delete this announcement.');
        }

        if ($ann['attachment']) {
            $path = FCPATH . self::UPLOAD_DIR . $ann['attachment'];
            if (file_exists($path)) unlink($path);
        }

        $this->annModel->update($id, ['announcement_status' => 'Archived', 'updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('dashboard/announcement')->with('success', 'Announcement removed.');
    }

    // ─── DOWNLOAD ────────────────────────────────────────────────────────────

    public function download(int $id): \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $ann = $this->annModel->find($id);
        if (!$ann || !$ann['attachment']) {
            return redirect()->back()->with('error', 'File not found.');
        }

        $path = FCPATH . self::UPLOAD_DIR . $ann['attachment'];
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        $mime = mime_content_type($path) ?: 'application/octet-stream';
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . ($ann['attachment_name'] ?: $ann['attachment']) . '"')
            ->setHeader('Content-Length', (string)filesize($path))
            ->setBody(file_get_contents($path));
    }

    // ─── private: file upload ─────────────────────────────────────────────────

    private function handleUpload(string $inputName): array
    {
        $file = $this->request->getFile($inputName);
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return [null, null, null];
        }
        if ($file->getSize() > self::MAX_FILE_MB * 1024 * 1024) {
            return [null, null, null];
        }
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME)) {
            return [null, null, null];
        }

        $ext      = strtolower($file->getExtension());
        $newName  = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $origName = $file->getClientName();
        $file->move($this->uploadDir(), $newName);

        return [$newName, $ext, $origName];
    }
}
