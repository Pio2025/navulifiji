<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Libraries\SchoolAccess;
use App\Models\AnnouncementModel;
use App\Models\RolePermissionModel;
use CodeIgniter\Controller;

class AnnouncementsController extends Controller
{
    protected $announcementModel;
    protected $schoolAccess;
    protected $rolePermissionModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->announcementModel   = new AnnouncementModel();
        $this->schoolAccess        = new SchoolAccess();
        $this->rolePermissionModel = new RolePermissionModel();
    }

    // ─── permission helpers (mirror AnnouncementController's role rules) ───────

    private function grantAccess(int $roleId, string $permCode): bool
    {
        return !empty($this->rolePermissionModel->grant_role_access($roleId, $permCode));
    }

    private function canPost(int $roleId, int $roleCatId): bool
    {
        return $roleId === 1 || in_array($roleCatId, [2, 7], true) || $this->grantAccess($roleId, '_post_announcement');
    }

    private function canManage(int $roleId): bool
    {
        return $roleId === 1 || $this->grantAccess($roleId, '_manage_announcement');
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

    private function announcementOut(array $a, int $myId, bool $canManage): array
    {
        $isMine = (int) $a['posted_by'] === $myId;
        return [
            'id'        => (int) $a['announcement_id'],
            'title'     => $a['title'],
            'content'   => $a['content'],
            'priority'  => $a['priority'],
            'postedBy'  => trim(($a['fname'] ?? '') . ' ' . ($a['lname'] ?? '')),
            'createdAt' => $a['created_at'],
            'isMine'    => $isMine,
            'canEdit'   => $isMine || $canManage,
            'canDelete' => $isMine || $canManage,
        ];
    }

    /**
     * GET /api/announcements?sch_id=&offset=&limit= — requires Bearer token (apijwt filter).
     */
    public function index()
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleId    = (int) ($claims['roleID'] ?? 0);
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);

        $permissions = [
            'canPost'   => $this->canPost($roleId, $roleCatId),
            'canManage' => $this->canManage($roleId),
        ];

        $requested = $this->request->getGet('sch_id');
        [$schools, $schID] = $this->resolveSchools($requested !== null ? (int) $requested : null);

        if (empty($schools)) {
            return $this->response->setJSON(['success' => true, 'announcements' => [], 'hasMore' => false, 'schools' => [], 'activeSchoolId' => 0, 'permissions' => $permissions]);
        }

        $offset = max(0, (int) $this->request->getGet('offset'));
        $limit  = 10;

        $announcements = $this->announcementModel->getActiveForSchool($schID, $limit + 1, $offset);
        $hasMore = count($announcements) > $limit;
        if ($hasMore) array_pop($announcements);

        return $this->response->setJSON([
            'success'        => true,
            'schools'        => $schools,
            'activeSchoolId' => $schID,
            'hasMore'        => $hasMore,
            'permissions'    => $permissions,
            'announcements'  => array_map(
                fn ($a) => $this->announcementOut($a, $myId, $permissions['canManage']),
                $announcements
            ),
        ]);
    }

    /**
     * POST /api/announcements — body (JSON): title, content, priority?, expires_at?, sch_id?
     */
    public function store()
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleId    = (int) ($claims['roleID'] ?? 0);
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        if (!$this->canPost($roleId, $roleCatId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You do not have permission to post announcements.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $schId = $ownSchId;
        if ($ownSchId === 0) {
            $schId = (int) ($body['sch_id'] ?? 0);
            if ($schId <= 0) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Please select a school for this announcement.']);
            }
        }

        $title    = trim((string) ($body['title'] ?? ''));
        $content  = trim((string) ($body['content'] ?? ''));
        $priority = $body['priority'] ?? 'Info';
        $expiresInput = $body['expires_at'] ?? null;

        if ($title === '' || $content === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Title and content are required.']);
        }
        if (!in_array($priority, ['Info', 'Important', 'Critical'], true)) $priority = 'Info';

        $expiresAt = null;
        if (!empty($expiresInput)) {
            $expiresAt = date('Y-m-d 23:59:59', strtotime($expiresInput));
        }

        $announcementId = $this->announcementModel->insert([
            'sch_id_fk'           => $schId,
            'posted_by'           => $myId,
            'title'               => $title,
            'content'             => $content,
            'priority'            => $priority,
            'expires_at'          => $expiresAt,
            'announcement_status' => 'Active',
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        $announcement = $this->announcementModel->find((int) $announcementId);
        $author       = \Config\Database::connect()->table('users')->select('fname, lname')->where('user_id', $myId)->get()->getRowArray();
        $announcement = array_merge($announcement, $author ?: []);

        return $this->response->setJSON([
            'success'      => true,
            'announcement' => $this->announcementOut($announcement, $myId, $this->canManage($roleId)),
        ]);
    }

    /**
     * PUT /api/announcements/{id} — body (JSON): title, content, priority?, expires_at?
     */
    public function update(int $id)
    {
        $claims = ApiAuth::claims();
        $myId   = ApiAuth::userId();
        $roleId = (int) ($claims['roleID'] ?? 0);

        $announcement = $this->announcementModel->find($id);
        if (!$announcement) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Announcement not found.']);
        }

        $isOwner = (int) $announcement['posted_by'] === $myId;
        if (!$isOwner && !$this->canManage($roleId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You cannot edit this announcement.']);
        }

        $body = $this->request->getJSON(true) ?? [];

        $title    = trim((string) ($body['title'] ?? ''));
        $content  = trim((string) ($body['content'] ?? ''));
        $priority = $body['priority'] ?? 'Info';
        $expiresInput = $body['expires_at'] ?? null;

        if ($title === '' || $content === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Title and content are required.']);
        }
        if (!in_array($priority, ['Info', 'Important', 'Critical'], true)) $priority = 'Info';

        $expiresAt = empty($expiresInput) ? $announcement['expires_at'] : date('Y-m-d 23:59:59', strtotime($expiresInput));

        $this->announcementModel->update($id, [
            'title'      => $title,
            'content'    => $content,
            'priority'   => $priority,
            'expires_at' => $expiresAt,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $updated = $this->announcementModel->find($id);
        $author  = \Config\Database::connect()->table('users')->select('fname, lname')->where('user_id', $updated['posted_by'])->get()->getRowArray();
        $updated = array_merge($updated, $author ?: []);

        return $this->response->setJSON([
            'success'      => true,
            'announcement' => $this->announcementOut($updated, $myId, $this->canManage($roleId)),
        ]);
    }

    /**
     * DELETE /api/announcements/{id} — soft delete (Archived), matches web behavior.
     */
    public function delete(int $id)
    {
        $claims = ApiAuth::claims();
        $myId   = ApiAuth::userId();
        $roleId = (int) ($claims['roleID'] ?? 0);

        $announcement = $this->announcementModel->find($id);
        if (!$announcement) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Announcement not found.']);
        }

        $isOwner = (int) $announcement['posted_by'] === $myId;
        if (!$isOwner && !$this->canManage($roleId)) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'You cannot delete this announcement.']);
        }

        $this->announcementModel->update($id, ['announcement_status' => 'Archived', 'updated_at' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON(['success' => true]);
    }
}
