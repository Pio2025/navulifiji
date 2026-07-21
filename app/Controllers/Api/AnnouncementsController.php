<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Libraries\SchoolAccess;
use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class AnnouncementsController extends Controller
{
    protected $announcementModel;
    protected $schoolAccess;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->announcementModel = new AnnouncementModel();
        $this->schoolAccess      = new SchoolAccess();
    }

    /**
     * GET /api/announcements?sch_id= — requires Bearer token (apijwt filter).
     */
    public function index()
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        $schools = $this->schoolAccess->accessibleSchools($myId, $roleCatId, $ownSchId);

        if (empty($schools)) {
            return $this->response->setJSON(['success' => true, 'announcements' => [], 'schools' => [], 'activeSchoolId' => 0]);
        }

        $requested = $this->request->getGet('sch_id');
        $schID     = $this->schoolAccess->resolveActiveSchoolId($schools, $requested !== null ? (int) $requested : null);

        $announcements = $this->announcementModel->getActiveForSchool($schID);

        return $this->response->setJSON([
            'success'        => true,
            'schools'        => $schools,
            'activeSchoolId' => $schID,
            'announcements'  => array_map(static function ($a) {
                return [
                    'id'        => (int) $a['announcement_id'],
                    'title'     => $a['title'],
                    'content'   => $a['content'],
                    'priority'  => $a['priority'],
                    'postedBy'  => trim(($a['fname'] ?? '') . ' ' . ($a['lname'] ?? '')),
                    'createdAt' => $a['created_at'],
                ];
            }, $announcements),
        ]);
    }
}
