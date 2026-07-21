<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Libraries\SchoolAccess;
use App\Models\NoticeBoardModel;
use CodeIgniter\Controller;

class NoticesController extends Controller
{
    protected $noticeBoardModel;
    protected $schoolAccess;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->noticeBoardModel = new NoticeBoardModel();
        $this->schoolAccess     = new SchoolAccess();
    }

    /**
     * GET /api/notices?sch_id= — requires Bearer token (apijwt filter).
     */
    public function index()
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        $schools = $this->schoolAccess->accessibleSchools($myId, $roleCatId, $ownSchId);

        if (empty($schools)) {
            return $this->response->setJSON(['success' => true, 'notices' => [], 'schools' => [], 'activeSchoolId' => 0]);
        }

        $requested = $this->request->getGet('sch_id');
        $schID     = $this->schoolAccess->resolveActiveSchoolId($schools, $requested !== null ? (int) $requested : null);

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

        $notices = $this->noticeBoardModel->getActiveForSchool($schID, $audience);

        return $this->response->setJSON([
            'success'        => true,
            'schools'        => $schools,
            'activeSchoolId' => $schID,
            'notices'        => array_map(static function ($n) {
                return [
                    'id'        => (int) $n['notice_id'],
                    'title'     => $n['title'],
                    'content'   => $n['content'],
                    'priority'  => $n['priority'],
                    'isPinned'  => (bool) $n['is_pinned'],
                    'postedBy'  => trim(($n['fname'] ?? '') . ' ' . ($n['lname'] ?? '')),
                    'createdAt' => $n['created_at'],
                ];
            }, $notices),
        ]);
    }
}
