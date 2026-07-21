<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Libraries\SchoolAccess;
use App\Models\NoticeBoardModel;
use App\Models\RolePermissionModel;
use CodeIgniter\Controller;

class NoticesController extends Controller
{
    protected $noticeBoardModel;
    protected $schoolAccess;
    protected $rolePermissionModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->noticeBoardModel    = new NoticeBoardModel();
        $this->schoolAccess        = new SchoolAccess();
        $this->rolePermissionModel = new RolePermissionModel();
    }

    // ─── permission helpers (mirror NoticeBoardController's role rules) ────────

    private function grantAccess(int $roleId, string $permCode): bool
    {
        return !empty($this->rolePermissionModel->grant_role_access($roleId, $permCode));
    }

    private function canPost(int $roleId, int $roleCatId): bool
    {
        return $roleId === 1 || $roleCatId === 3 || $this->grantAccess($roleId, '_post_notice');
    }

    private function canPin(int $roleId): bool
    {
        return $roleId === 1 || $this->grantAccess($roleId, '_pin_notice');
    }

    private function canRemove(int $roleId): bool
    {
        return $roleId === 1 || $this->grantAccess($roleId, '_remove_notice');
    }

    private function canEditItem(int $roleId, int $myId, array $notice): bool
    {
        return $roleId === 1 || (int) $notice['posted_by'] === $myId || $this->grantAccess($roleId, '_edit_notice');
    }

    /**
     * @return array{0: array, 1: int} [$schools, $resolvedSchId]
     */
    private function resolveSchools(?int $requestedSchId): array
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        $schools = $this->schoolAccess->accessibleSchools($myId, $roleCatId, $ownSchId);
        $schId   = $this->schoolAccess->resolveActiveSchoolId($schools, $requestedSchId);

        return [$schools, $schId];
    }

    private function noticeOut(array $n, int $roleId, int $myId, bool $canPin, bool $canRemove): array
    {
        $isMine = (int) $n['posted_by'] === $myId;
        return [
            'id'        => (int) $n['notice_id'],
            'title'     => $n['title'],
            'content'   => $n['content'],
            'priority'  => $n['priority'],
            'audience'  => $n['audience'],
            'isPinned'  => (bool) $n['is_pinned'],
            'postedBy'  => trim(($n['fname'] ?? '') . ' ' . ($n['lname'] ?? '')),
            'createdAt' => $n['created_at'],
            'isMine'    => $isMine,
            'canEdit'   => $this->canEditItem($roleId, $myId, $n),
            'canPin'    => $canPin,
            'canDelete' => $isMine || $canRemove,
        ];
    }

    /**
     * GET /api/notices?sch_id=&offset=&limit= — requires Bearer token (apijwt filter).
     */
    public function index()
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleId    = (int) ($claims['roleID'] ?? 0);
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        $permissions = [
            'canPost' => $this->canPost($roleId, $roleCatId),
            'canPin'  => $this->canPin($roleId),
            'canRemove' => $this->canRemove($roleId),
        ];

        $requested = $this->request->getGet('sch_id');
        [$schools, $schID] = $this->resolveSchools($requested !== null ? (int) $requested : null);

        if (empty($schools)) {
            return $this->response->setJSON(['success' => true, 'notices' => [], 'hasMore' => false, 'schools' => [], 'activeSchoolId' => 0, 'permissions' => $permissions]);
        }

        $offset = max(0, (int) $this->request->getGet('offset'));
        $limit  = 10;

        // Viewing a linked child's school (not our own) — read it as a Parent would.
        $isOwnSchool = $schID === $ownSchId;
        $audience    = $isOwnSchool
            ? match ($roleCatId) {
                3 => 'Teachers',
                4 => 'Students',
                6 => 'Parents',
                default => 'All',
            }
            : 'Parents';

        $notices = $this->noticeBoardModel->getActiveForSchool($schID, $audience, $limit + 1, $offset);
        $hasMore = count($notices) > $limit;
        if ($hasMore) array_pop($notices);

        return $this->response->setJSON([
            'success'        => true,
            'schools'        => $schools,
            'activeSchoolId' => $schID,
            'hasMore'        => $hasMore,
            'permissions'    => $permissions,
            'notices'        => array_map(
                fn ($n) => $this->noticeOut($n, $roleId, $myId, $permissions['canPin'], $permissions['canRemove']),
                $notices
            ),
        ]);
    }

    /**
     * POST /api/notices — body (JSON): title, content, priority?, audience?, is_pinned?, expires_at?, sch_id?
     */
    public function store()
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleId    = (int) ($claims['roleID'] ?? 0);
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        if (!$this->canPost($roleId, $roleCatId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to post notices.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $schId = $ownSchId;
        if ($ownSchId === 0) {
            $schId = (int) ($body['sch_id'] ?? 0);
            if ($schId <= 0) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Please select a school for this notice.']);
            }
        }

        $title    = trim((string) ($body['title'] ?? ''));
        $content  = trim((string) ($body['content'] ?? ''));
        $priority = $body['priority'] ?? 'Normal';
        $audience = $body['audience'] ?? 'All';
        $isPinned = (int) (bool) ($body['is_pinned'] ?? false);
        $expiresAt = $body['expires_at'] ?? null;

        if ($title === '' || $content === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Title and content are required.']);
        }
        if (mb_strlen($content) > 250) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Notice content must be 250 characters or fewer. Post longer messages as an Announcement instead.']);
        }

        if (empty($expiresAt)) {
            $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
        } else {
            $expiresAt = date('Y-m-d H:i:s', strtotime($expiresAt . ' 23:59:59'));
        }

        if (!in_array($priority, ['Normal', 'Important', 'Urgent'], true)) $priority = 'Normal';
        if (!in_array($audience, ['All', 'Teachers', 'Students', 'Parents'], true)) $audience = 'All';

        if ($isPinned && !$this->canPin($roleId)) {
            $isPinned = 0;
        }

        $noticeId = $this->noticeBoardModel->insert([
            'sch_id_fk'     => $schId,
            'posted_by'     => $myId,
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

        $notice = $this->noticeBoardModel->findWithAuthor((int) $noticeId);

        return $this->response->setJSON([
            'success' => true,
            'notice'  => $this->noticeOut($notice, $roleId, $myId, $this->canPin($roleId), $this->canRemove($roleId)),
        ]);
    }

    /**
     * PUT /api/notices/{id} — body (JSON): title, content, priority?, audience?, is_pinned?, expires_at?
     */
    public function update(int $id)
    {
        $claims = ApiAuth::claims();
        $myId   = ApiAuth::userId();
        $roleId = (int) ($claims['roleID'] ?? 0);

        $notice = $this->noticeBoardModel->find($id);
        if (!$notice) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Notice not found.']);
        }
        if (!$this->canEditItem($roleId, $myId, $notice)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You cannot edit this notice.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $title    = trim((string) ($body['title'] ?? ''));
        $content  = trim((string) ($body['content'] ?? ''));
        $priority = $body['priority'] ?? 'Normal';
        $audience = $body['audience'] ?? 'All';
        $isPinned = (int) (bool) ($body['is_pinned'] ?? $notice['is_pinned']);
        $expiresAt = $body['expires_at'] ?? null;

        if ($title === '' || $content === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Title and content are required.']);
        }
        if (mb_strlen($content) > 250) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Notice content must be 250 characters or fewer. Post longer messages as an Announcement instead.']);
        }

        if (!in_array($priority, ['Normal', 'Important', 'Urgent'], true)) $priority = 'Normal';
        if (!in_array($audience, ['All', 'Teachers', 'Students', 'Parents'], true)) $audience = 'All';

        if ($isPinned && !$this->canPin($roleId)) {
            $isPinned = (int) $notice['is_pinned'];
        }

        $expiresAt = empty($expiresAt)
            ? $notice['expires_at']
            : date('Y-m-d H:i:s', strtotime($expiresAt . ' 23:59:59'));

        $this->noticeBoardModel->update($id, [
            'title'      => $title,
            'content'    => $content,
            'priority'   => $priority,
            'audience'   => $audience,
            'is_pinned'  => $isPinned,
            'expires_at' => $expiresAt,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $updated = $this->noticeBoardModel->findWithAuthor($id);

        return $this->response->setJSON([
            'success' => true,
            'notice'  => $this->noticeOut($updated, $roleId, $myId, $this->canPin($roleId), $this->canRemove($roleId)),
        ]);
    }

    /**
     * DELETE /api/notices/{id} — soft delete (Archived), matches web behavior.
     */
    public function delete(int $id)
    {
        $claims = ApiAuth::claims();
        $myId   = ApiAuth::userId();
        $roleId = (int) ($claims['roleID'] ?? 0);

        $notice = $this->noticeBoardModel->find($id);
        if (!$notice) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Notice not found.']);
        }

        $isOwner = (int) $notice['posted_by'] === $myId;
        if (!$isOwner && !$this->canRemove($roleId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You can only delete your own notices.']);
        }

        $this->noticeBoardModel->update($id, ['notice_status' => 'Archived', 'updated_at' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * POST /api/notices/{id}/pin — toggles is_pinned, requires canPin.
     */
    public function togglePin(int $id)
    {
        $claims = ApiAuth::claims();
        $myId   = ApiAuth::userId();
        $roleId = (int) ($claims['roleID'] ?? 0);

        if (!$this->canPin($roleId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to pin notices.']);
        }

        $notice = $this->noticeBoardModel->find($id);
        if (!$notice) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Notice not found.']);
        }

        $this->noticeBoardModel->update($id, [
            'is_pinned'  => $notice['is_pinned'] ? 0 : 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $updated = $this->noticeBoardModel->findWithAuthor($id);

        return $this->response->setJSON([
            'success' => true,
            'notice'  => $this->noticeOut($updated, $roleId, $myId, true, $this->canRemove($roleId)),
        ]);
    }
}
