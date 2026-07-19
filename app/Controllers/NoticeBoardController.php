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

    // ─── School ID resolution (handles schID=0 in stale sessions) ───────────

    private function resolveSchoolId(): int
    {
        $schId  = (int) $this->session->get('schID');
        $userId = (int) $this->session->get('userID');

        if ($schId !== 0) return $schId;

        $db = \Config\Database::connect();

        // Staff table first
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

        // Classroom assignment chain
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

        $isPureParent    = $roleCat === 6;
        $isTeacherParent = $roleCat === 3 && $this->hasParentFlag($userId);
        // Other staff categories with is_a_parent set behave like a pure parent (unchanged legacy behavior)
        $isParent = $isPureParent
            || ($roleCat !== 3 && $this->hasParentFlag($userId));

        $parentSchools   = [];
        $activeSchoolId  = 0;
        $ownSchId        = 0;

        if ($isParent) {
            $parentSchools = $this->resolveParentSchools($userId);

            // Honour ?sch_id= tab switch, validated against accessible schools
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

        // When viewing a linked child's school (not our own), we hold no staff role there
        $audience = ($isTeacherParent && $activeSchoolId !== 0 && $activeSchoolId !== $ownSchId)
            ? 'Parents'
            : $this->myAudience();

        $notices = $this->noticeModel->getActiveForSchool($schId, $audience);

        // Mark all visible notices as read for this user on page visit
        $this->noticeModel->markAllReadForUser($userId, $schId, $audience);

        // Super admins (and anyone without an assigned school) must choose a school when posting
        $needsSchoolSelect = $this->isSuperAdmin() || (int) $this->session->get('schID') === 0;
        $allSchools        = ($needsSchoolSelect && $this->canPost()) ? $this->getAllSchools() : [];

        $this->setPageData('Notice Board', 'Dashboard', 'Notice Board');
        $data = $this->loadCommonData('app/notice_board/index', [
            'notices'           => $notices,
            'canPost'           => $this->canPost(),
            'canPin'            => $this->grant_access('_pin_notice') || $this->isSuperAdmin(),
            'canManage'         => $this->isSuperAdmin() || $this->grant_access('_remove_notice'),
            'myUserId'          => $userId,
            'parentSchools'     => $parentSchools,
            'activeSchoolId'    => $activeSchoolId,
            'needsSchoolSelect' => $needsSchoolSelect,
            'allSchools'        => $allSchools,
        ]);

        return view('app/layouts/main', $data);
    }

    // ─── STORE ─────────────────────────────────────────────────────────────────

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
                return redirect()->back()->with('error', 'Please select a school for this notice.')->withInput();
            }
        } else {
            $schId = (int) $this->session->get('schID');
        }

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
