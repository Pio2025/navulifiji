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

    // ─── INDEX ────────────────────────────────────────────────────────────────

    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }
        if ($this->require_access('_view_announcement') !== true) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        $this->annModel->expireOld();

        $schId         = (int) $this->session->get('schID');
        $announcements = $this->annModel->getActiveForSchool($schId);

        $this->setPageData('Announcements', 'Dashboard', 'Announcements');
        $data = $this->loadCommonData('app/announcement/index', [
            'announcements' => $announcements,
            'canPost'       => $this->canPost(),
            'canManage'     => $this->canManage(),
            'myUserId'      => (int) $this->session->get('userID'),
        ]);

        return view('app/layouts/main', $data);
    }

    // ─── STORE ───────────────────────────────────────────────────────────────

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn() || !$this->canPost()) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $schId  = (int) $this->session->get('schID');
        $userId = (int) $this->session->get('userID');

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
