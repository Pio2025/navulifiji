<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Models\NoticeBoardModel;
use CodeIgniter\Controller;

class NoticesController extends Controller
{
    protected $noticeBoardModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->noticeBoardModel = new NoticeBoardModel();
    }

    /**
     * GET /api/notices — requires Bearer token (apijwt filter).
     */
    public function index()
    {
        $claims = ApiAuth::claims();
        $schID  = (int) ($claims['schID'] ?? 0);

        if ($schID === 0) {
            return $this->response->setJSON(['success' => true, 'notices' => []]);
        }

        $audience = match ((int) ($claims['roleCatID'] ?? 0)) {
            3 => 'Teachers',
            4 => 'Students',
            6 => 'Parents',
            default => 'All',
        };

        $notices = $this->noticeBoardModel->getActiveForSchool($schID, $audience);

        return $this->response->setJSON([
            'success' => true,
            'notices' => array_map(static function ($n) {
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
