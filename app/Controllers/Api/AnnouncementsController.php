<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class AnnouncementsController extends Controller
{
    protected $announcementModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->announcementModel = new AnnouncementModel();
    }

    /**
     * GET /api/announcements — requires Bearer token (apijwt filter).
     */
    public function index()
    {
        $claims = ApiAuth::claims();
        $schID  = (int) ($claims['schID'] ?? 0);

        if ($schID === 0) {
            return $this->response->setJSON(['success' => true, 'announcements' => []]);
        }

        $announcements = $this->announcementModel->getActiveForSchool($schID);

        return $this->response->setJSON([
            'success' => true,
            'announcements' => array_map(static function ($a) {
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
