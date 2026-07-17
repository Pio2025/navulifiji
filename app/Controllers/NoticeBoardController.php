<?php

namespace App\Controllers;

use App\Models\NoticeBoardModel;

class NoticeBoardController extends BaseController
{
    private NoticeBoardModel $noticeModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->noticeModel = new NoticeBoardModel();
    }

    // ─── helpers ──────────────────────────────────────────────────────────────

    private function isSuperAdmin(): bool
    {
        return (int) $this->session->get('roleID') === 1;
    }

    private function canPost(): bool
    {
        $roleCatID = (int) $this->session->get('roleCatID');
        return $this->isSuperAdmin()
            || $this->grant_access('_post_notice')
            || $roleCatID === 3; // Teacher
    }

    /** Audience string for this user's role so they only see relevant notices. */
    private function myAudience(): string
    {
        $cat = (int) $this->session->get('roleCatID');
        return match($cat) {
            3 => 'Teachers',
            4 => 'Students',
            6 => 'Parents',
            default => 'All',
        };
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

    // ─── INDEX ─────────────────────────────────────────────────────────────────

    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }
        if ($this->require_access('_view_notice_board') !== true) {
            return redirect()->to('dashboard')->with('error', 'Access denied.');
        }

        // Expire stale notices lazily
        $this->noticeModel->expireOld();

        $userId   = (int) $this->session->get('userID');
        $roleCat  = (int) $this->session->get('roleCatID');
        $audience = $this->myAudience();

        $isParent = $roleCat === 6
            || (int) (($this->userModel->find($userId))['is_a_parent'] ?? 0) === 1;

        $parentSchools   = [];
        $activeSchoolId  = 0;

        if ($isParent) {
            $parentSchools = $this->resolveParentSchools($userId);

            // Honour ?sch_id= tab switch, validated against accessible schools
            $reqSchId = (int) $this->request->getGet('sch_id');
            $schIds   = array_column($parentSchools, 'sch_id');
            $activeSchoolId = in_array($reqSchId, $schIds, false) ? $reqSchId
                : (empty($parentSchools) ? 0 : (int) $parentSchools[0]['sch_id']);

            $schId = $activeSchoolId;
        } else {
            $schId = (int) $this->session->get('schID');
        }

        $notices = $this->noticeModel->getActiveForSchool($schId, $audience);

        // Mark all visible notices as read for this user on page visit
        $this->noticeModel->markAllReadForUser($userId, $schId, $audience);

        $this->setPageData('Notice Board', 'Dashboard', 'Notice Board');
        $data = $this->loadCommonData('app/notice_board/index', [
            'notices'        => $notices,
            'canPost'        => $this->canPost(),
            'canPin'         => $this->grant_access('_pin_notice') || $this->isSuperAdmin(),
            'canManage'      => $this->isSuperAdmin() || $this->grant_access('_remove_notice'),
            'myUserId'       => $userId,
            'parentSchools'  => $parentSchools,
            'activeSchoolId' => $activeSchoolId,
        ]);

        return view('app/layouts/main', $data);
    }

    // ─── STORE ─────────────────────────────────────────────────────────────────

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn() || !$this->canPost()) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $schId  = (int) $this->session->get('schID');
        $userId = (int) $this->session->get('userID');

        $title    = trim($this->request->getPost('title') ?? '');
        $content  = trim($this->request->getPost('content') ?? '');
        $priority = $this->request->getPost('priority') ?: 'Normal';
        $audience = $this->request->getPost('audience') ?: 'All';
        $isPinned = (int)(bool)$this->request->getPost('is_pinned');
        $expiresAt = $this->request->getPost('expires_at');

        if ($title === '' || $content === '') {
            return redirect()->back()->with('error', 'Title and content are required.')->withInput();
        }

        // Default expiry: 7 days from now
        if (empty($expiresAt)) {
            $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
        } else {
            $expiresAt = date('Y-m-d H:i:s', strtotime($expiresAt . ' 23:59:59'));
        }

        // Validate priority and audience
        if (!in_array($priority, ['Normal', 'Important', 'Urgent'])) $priority = 'Normal';
        if (!in_array($audience, ['All', 'Teachers', 'Students', 'Parents'])) $audience = 'All';

        // Only admins can pin by default
        if ($isPinned && !$this->isSuperAdmin() && !$this->grant_access('_pin_notice')) {
            $isPinned = 0;
        }

        $this->noticeModel->insert([
            'sch_id_fk'     => $schId,
            'posted_by'     => $userId,
            'title'         => $title,
            'content'       => $content,
            'priority'      => $priority,
            'audience'      => $audience,
            'is_pinned'     => $isPinned,
            'expires_at'    => $expiresAt,
            'notice_status' => 'Active',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        $this->userLogModel->insert([
            'user_id_fk'  => $userId,
            'log_action'  => 'Posted notice: ' . $title,
            'log_details' => 'Audience: ' . $audience,
            'log_ip'      => $this->request->getIPAddress(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('dashboard/notice')->with('success', 'Notice posted successfully.');
    }

    // ─── UPDATE ────────────────────────────────────────────────────────────────

    public function update(int $noticeId): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->back()->with('error', 'Please log in.');
        }

        $notice = $this->noticeModel->find($noticeId);
        if (!$notice) {
            return redirect()->back()->with('error', 'Notice not found.');
        }

        $userId = (int) $this->session->get('userID');
        $isOwner = (int)$notice['posted_by'] === $userId;

        if (!$isOwner && !$this->isSuperAdmin() && !$this->grant_access('_edit_notice')) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $title    = trim($this->request->getPost('title') ?? '');
        $content  = trim($this->request->getPost('content') ?? '');
        $priority = $this->request->getPost('priority') ?: 'Normal';
        $audience = $this->request->getPost('audience') ?: 'All';
        $isPinned = (int)(bool)$this->request->getPost('is_pinned');
        $expiresAt = $this->request->getPost('expires_at');

        if ($title === '' || $content === '') {
            return redirect()->back()->with('error', 'Title and content are required.');
        }

        if (!in_array($priority, ['Normal', 'Important', 'Urgent'])) $priority = 'Normal';
        if (!in_array($audience, ['All', 'Teachers', 'Students', 'Parents'])) $audience = 'All';

        if ($isPinned && !$this->isSuperAdmin() && !$this->grant_access('_pin_notice')) {
            $isPinned = (int)$notice['is_pinned']; // keep existing
        }

        $expiresAt = empty($expiresAt)
            ? $notice['expires_at']
            : date('Y-m-d H:i:s', strtotime($expiresAt . ' 23:59:59'));

        $this->noticeModel->update($noticeId, [
            'title'      => $title,
            'content'    => $content,
            'priority'   => $priority,
            'audience'   => $audience,
            'is_pinned'  => $isPinned,
            'expires_at' => $expiresAt,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('dashboard/notice')->with('success', 'Notice updated.');
    }

    // ─── DELETE ────────────────────────────────────────────────────────────────

    public function delete(int $noticeId): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->back()->with('error', 'Please log in.');
        }

        $notice = $this->noticeModel->find($noticeId);
        if (!$notice) {
            return redirect()->back()->with('error', 'Notice not found.');
        }

        $userId  = (int) $this->session->get('userID');
        $isOwner = (int)$notice['posted_by'] === $userId;

        if (!$isOwner && !$this->isSuperAdmin() && !$this->grant_access('_remove_notice')) {
            return redirect()->back()->with('error', 'You can only delete your own notices.');
        }

        $this->noticeModel->update($noticeId, ['notice_status' => 'Archived', 'updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('dashboard/notice')->with('success', 'Notice removed.');
    }

    // ─── PIN TOGGLE ────────────────────────────────────────────────────────────

    public function togglePin(int $noticeId): \CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->back()->with('error', 'Please log in.');
        }
        if (!$this->isSuperAdmin() && !$this->grant_access('_pin_notice')) {
            return redirect()->back()->with('error', 'Access denied.');
        }

        $notice = $this->noticeModel->find($noticeId);
        if (!$notice) {
            return redirect()->back()->with('error', 'Notice not found.');
        }

        $this->noticeModel->update($noticeId, [
            'is_pinned'  => $notice['is_pinned'] ? 0 : 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('dashboard/notice');
    }
}
