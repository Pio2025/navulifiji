<?php

namespace App\Controllers;

use App\Libraries\DocManagerAggregator;

class DocManagerController extends BaseController
{
    private DocManagerAggregator $aggregator;

    public function __construct()
    {
        $this->aggregator = new DocManagerAggregator();
    }

    private function ensureUploadDir(): string
    {
        $dir = FCPATH . 'uploads/doc_manager/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * Whether the logged-in user may view/download/share the given resolved
     * document: its owner, a parent of the owner, a Super Admin, a holder of
     * `_doc_manager_manage_others`, or someone it's been explicitly shared
     * with in-app.
     */
    private function canAccessDocument(array $doc): bool
    {
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $userId       = (int) $this->session->get('userID');
        $ownerId      = (int) $doc['owner_user_id'];

        if ($isSuperAdmin || $userId === $ownerId) {
            return true;
        }
        if ($this->grant_access('_doc_manager_manage_others')) {
            return true;
        }
        if ($this->aggregator->isParentOfUser($userId, $ownerId)) {
            return true;
        }
        if (in_array($doc['source_type'], DocManagerAggregator::CLASS_ACCESS_SOURCES, true)
            && $this->aggregator->canUserAccessClassResource($doc['source_type'], $userId, (int) $doc['source_file_id'])) {
            return true;
        }

        return $this->docManagerShareModel
            ->where('source_type', $doc['source_type'])
            ->where('source_file_id', $doc['source_file_id'])
            ->where('share_type', 'user')
            ->where('shared_with_user_id_fk', $userId)
            ->where('revoked_at', null)
            ->groupStart()
                ->where('expires_at', null)
                ->orWhere('expires_at >', date('Y-m-d H:i:s'))
            ->groupEnd()
            ->first() !== null;
    }

    private function streamFile(array $doc, bool $forceDownload): void
    {
        if (!empty($doc['is_external'])) {
            header('Location: ' . $doc['url']);
            exit;
        }

        $path = FCPATH . 'uploads/' . $this->aggregator->folderFor($doc['source_type']) . '/' . $doc['file_name'];
        if (!file_exists($path)) {
            $data['_view'] = 'app/auth/access_control';
            echo view('app/layouts/main', $data);
            return;
        }

        $mime = function_exists('mime_content_type') ? (mime_content_type($path) ?: 'application/octet-stream') : 'application/octet-stream';
        $disposition = $forceDownload ? 'attachment' : 'inline';

        header('Content-Type: ' . $mime);
        header('Content-Disposition: ' . $disposition . '; filename="' . $doc['original_name'] . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    // ================================================================
    // MY DOCUMENTS
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('My Documents', 'Doc Manager', 'My Documents');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_doc_manager_access')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $userId = (int) $this->session->get('userID');
        $schId  = (int) $this->session->get('schID');

        $data['documents']       = $this->aggregator->getDocumentsForUser($userId);
        $data['sharedCount']     = count($this->docManagerShareModel->getSharedWithUser($userId));
        $data['canManageOthers'] = $isSuperAdmin || $this->grant_access('_doc_manager_manage_others');
        $data['viewingUserId']   = $userId;
        $data['viewingUser']     = null;
        $data['staff']           = $schId > 0 ? $this->admissionModel->getActiveStaffBySchool($schId) : [];
        $data['_view']           = 'app/doc_manager/index';

        return view('app/layouts/main', $data);
    }

    public function upload()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_doc_manager_access')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $file = $this->request->getFile('document');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please choose a file to upload.']);
        }
        if ($file->getSize() > 15 * 1024 * 1024) {
            return $this->response->setJSON(['success' => false, 'message' => 'File is too large (max 15MB).']);
        }

        $allowedExt = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'txt', 'zip'];
        $ext        = strtolower($file->getExtension());
        if (!in_array($ext, $allowedExt, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'That file type is not supported.']);
        }

        $userId      = (int) $this->session->get('userID');
        $schId       = (int) $this->session->get('schID');
        $originalName = $file->getClientName();
        $newName     = 'doc_' . $userId . '_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;

        $file->move($this->ensureUploadDir(), $newName);

        $this->docManagerFileModel->insert([
            'sch_id_fk'              => $schId,
            'user_id_fk'             => $userId,
            'file_name'              => $newName,
            'original_name'          => $originalName,
            'description'            => trim((string) $this->request->getPost('description')) ?: null,
            'uploaded_by_user_id_fk' => $userId,
            'created_at'             => date('Y-m-d H:i:s'),
            'updated_at'             => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Document uploaded.', 'redirect' => base_url('doc-manager')]);
    }

    public function delete(int $docId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $doc = $this->docManagerFileModel->find($docId);
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $userId       = (int) $this->session->get('userID');

        if (!$doc || (!$isSuperAdmin && (int) $doc['user_id_fk'] !== $userId)) {
            return redirect()->to('doc-manager')->with('error', 'Access denied.');
        }

        $path = FCPATH . 'uploads/doc_manager/' . $doc['file_name'];
        if (file_exists($path)) {
            unlink($path);
        }

        $this->docManagerShareModel
            ->where('source_type', DocManagerAggregator::SOURCE_PERSONAL)
            ->where('source_file_id', $docId)
            ->delete();

        $this->docManagerFileModel->delete($docId);

        return redirect()->to('doc-manager')->with('success', 'Document removed.');
    }

    // ================================================================
    // VIEW / DOWNLOAD (owner, parent, admin, or in-app share grantee)
    // ================================================================

    public function viewFile(string $sourceType, int $sourceId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $doc = $this->aggregator->isValidSourceType($sourceType) ? $this->aggregator->resolveFile($sourceType, $sourceId) : null;
        if (!$doc || !$this->canAccessDocument($doc)) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->streamFile($doc, false);
    }

    public function download(string $sourceType, int $sourceId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $doc = $this->aggregator->isValidSourceType($sourceType) ? $this->aggregator->resolveFile($sourceType, $sourceId) : null;
        if (!$doc || !$this->canAccessDocument($doc)) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->streamFile($doc, true);
    }

    // ================================================================
    // SHARING (in-app + public link)
    // ================================================================

    public function share(string $sourceType, int $sourceId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $doc = $this->aggregator->isValidSourceType($sourceType) ? $this->aggregator->resolveFile($sourceType, $sourceId) : null;
        if (!$doc || !$this->canAccessDocument($doc)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $shareType   = $this->request->getPost('share_type');
        $userId      = (int) $this->session->get('userID');
        $canDownload = $this->request->getPost('can_download') !== '0' ? 1 : 0;
        $expiresAt   = $this->request->getPost('expires_at') ?: null;

        $payload = [
            'source_type'           => $sourceType,
            'source_file_id'        => $sourceId,
            'owner_user_id_fk'      => (int) $doc['owner_user_id'],
            'shared_by_user_id_fk'  => $userId,
            'can_download'          => $canDownload,
            'expires_at'            => $expiresAt,
            'created_at'            => date('Y-m-d H:i:s'),
            'updated_at'            => date('Y-m-d H:i:s'),
        ];

        if ($shareType === 'link') {
            $payload['share_type'] = 'link';
            $payload['token']      = $this->docManagerShareModel->generateToken();

            $this->docManagerShareModel->insert($payload);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Share link created.',
                'link'    => base_url('doc-manager/shared-link/' . $payload['token']),
            ]);
        }

        $sharedWith = (int) $this->request->getPost('shared_with_user_id');
        if ($sharedWith <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select a staff member to share with.']);
        }

        $payload['share_type']             = 'user';
        $payload['shared_with_user_id_fk'] = $sharedWith;

        $this->docManagerShareModel->insert($payload);

        return $this->response->setJSON(['success' => true, 'message' => 'Document shared.']);
    }

    public function shareList(string $sourceType, int $sourceId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $doc = $this->aggregator->isValidSourceType($sourceType) ? $this->aggregator->resolveFile($sourceType, $sourceId) : null;
        if (!$doc || !$this->canAccessDocument($doc)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $shares = $this->docManagerShareModel->getActiveForSource($sourceType, $sourceId);
        $items  = array_map(function ($s) {
            return [
                'share_id'    => (int) $s['share_id'],
                'share_type'  => $s['share_type'],
                'shared_with' => $s['share_type'] === 'user' ? trim(($s['shared_with_fname'] ?? '') . ' ' . ($s['shared_with_lname'] ?? '')) : null,
                'link'        => $s['share_type'] === 'link' ? base_url('doc-manager/shared-link/' . $s['token']) : null,
                'can_download'=> (int) $s['can_download'] === 1,
                'expires_at'  => $s['expires_at'],
            ];
        }, $shares);

        return $this->response->setJSON(['success' => true, 'shares' => $items]);
    }

    public function revokeShare(int $shareId)
    {
        $isAjax = $this->request->isAJAX();

        if (!$this->isLoggedIn()) {
            return $isAjax ? $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']) : redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $share        = $this->docManagerShareModel->find($shareId);
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $userId       = (int) $this->session->get('userID');

        if (!$share || (!$isSuperAdmin
            && (int) $share['owner_user_id_fk'] !== $userId
            && (int) $share['shared_by_user_id_fk'] !== $userId
            && !$this->grant_access('_doc_manager_manage_others'))) {
            return $isAjax ? $this->response->setJSON(['success' => false, 'message' => 'Access denied.']) : redirect()->back()->with('error', 'Access denied.');
        }

        $this->docManagerShareModel->revoke($shareId);

        return $isAjax ? $this->response->setJSON(['success' => true, 'message' => 'Share revoked.']) : redirect()->back()->with('success', 'Share revoked.');
    }

    public function sharedWithMe()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Shared With Me', 'Doc Manager', 'Shared With Me');

        $userId = (int) $this->session->get('userID');
        $shares = $this->docManagerShareModel->getSharedWithUser($userId);

        $items = [];
        foreach ($shares as $s) {
            $doc = $this->aggregator->resolveFile($s['source_type'], (int) $s['source_file_id']);
            if ($doc) {
                $doc['share_id']      = $s['share_id'];
                $doc['can_download']  = (int) $s['can_download'] === 1;
                $doc['shared_by']     = trim($s['owner_fname'] . ' ' . $s['owner_lname']);
                $items[]              = $doc;
            }
        }

        $data['items'] = $items;
        $data['_view'] = 'app/doc_manager/shared_with_me';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // PUBLIC SHARE LINK (no login required)
    // ================================================================

    public function sharedView(string $token)
    {
        $share = $this->docManagerShareModel->findActiveByToken($token);
        $doc   = $share ? $this->aggregator->resolveFile($share['source_type'], (int) $share['source_file_id']) : null;

        if (!$doc) {
            $data['_view'] = 'app/doc_manager/shared_link_invalid';
            return view('app/layouts/auth_main', $data);
        }

        $data['doc']         = $doc;
        $data['token']       = $token;
        $data['canDownload'] = (int) $share['can_download'] === 1;
        $data['_view']       = 'app/doc_manager/shared_link_view';

        return view('app/layouts/auth_main', $data);
    }

    public function sharedStream(string $token)
    {
        $share = $this->docManagerShareModel->findActiveByToken($token);
        $doc   = $share ? $this->aggregator->resolveFile($share['source_type'], (int) $share['source_file_id']) : null;

        if (!$doc) {
            $data['_view'] = 'app/doc_manager/shared_link_invalid';
            return view('app/layouts/auth_main', $data);
        }

        $this->streamFile($doc, false);
    }

    public function sharedDownload(string $token)
    {
        $share = $this->docManagerShareModel->findActiveByToken($token);
        $doc   = $share ? $this->aggregator->resolveFile($share['source_type'], (int) $share['source_file_id']) : null;

        if (!$doc || (int) $share['can_download'] !== 1) {
            $data['_view'] = 'app/doc_manager/shared_link_invalid';
            return view('app/layouts/auth_main', $data);
        }

        $this->streamFile($doc, true);
    }

    // ================================================================
    // ADMIN LOOKUP (oversight — _doc_manager_manage_others)
    // ================================================================

    public function lookup()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('Doc Manager Lookup', 'Doc Manager', 'Lookup');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_doc_manager_manage_others')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId  = (int) $this->session->get('schID');
        $search = $this->request->getGet('search') ?: null;

        $data['users']  = $schId > 0 ? $this->admissionModel->getAllActiveBySchool($schId, $search) : [];
        $data['search'] = $search;
        $data['_view']  = 'app/doc_manager/lookup';

        return view('app/layouts/main', $data);
    }

    public function lookupUser(int $userId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_doc_manager_manage_others')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('doc-manager/lookup')->with('error', 'User not found.');
        }

        $this->setPageData('Doc Manager Lookup', 'Doc Manager', 'Lookup');

        $schId = (int) $this->session->get('schID');

        $data['documents']       = $this->aggregator->getDocumentsForUser($userId);
        $data['sharedCount']     = count($this->docManagerShareModel->getSharedWithUser($userId));
        $data['canManageOthers'] = true;
        $data['viewingUserId']   = $userId;
        $data['viewingUser']     = $user;
        $data['staff']           = $schId > 0 ? $this->admissionModel->getActiveStaffBySchool($schId) : [];
        $data['_view']           = 'app/doc_manager/index';

        return view('app/layouts/main', $data);
    }
}
