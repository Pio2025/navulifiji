<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;
use App\Models\NoticeBoardModel;

class NoticesController extends BaseController
{
    private AnnouncementModel $annModel;
    private NoticeBoardModel  $noticeModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface  $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface            $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->annModel    = new AnnouncementModel();
        $this->noticeModel = new NoticeBoardModel();
    }

    // ── Resolve schools the current user can see ──────────────────────────────

    private function resolveSchools(): array
    {
        $db      = \Config\Database::connect();
        $userId  = (int) $this->session->get('userID');
        $roleCat = (int) $this->session->get('roleCatID');
        $schId   = (int) $this->session->get('schID');

        $isParent = $roleCat === 6
            || (int) (($this->userModel->find($userId))['is_a_parent'] ?? 0) === 1;

        if ($isParent) {
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

        // Students / staff use their own school
        if ($schId > 0) {
            $row = $db->table('school')
                ->select('sch_id, sch_name, sch_logo')
                ->where('sch_id', $schId)
                ->get()->getRowArray();
            return $row ? [$row] : [];
        }

        return [];
    }

    // ── INDEX ─────────────────────────────────────────────────────────────────

    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        // Lazily expire old records
        $this->annModel->expireOld();
        $this->noticeModel->expireOld();

        $schools = $this->resolveSchools();
        $roleCat = (int) $this->session->get('roleCatID');

        // Determine active school from GET param, validated against accessible schools
        $reqSchId = (int) $this->request->getGet('sch_id');
        $schIds   = array_column($schools, 'sch_id');
        $activeSchoolId = in_array($reqSchId, $schIds, false) ? $reqSchId
            : (empty($schools) ? 0 : (int) $schools[0]['sch_id']);

        // Notice audience filter
        $audience = match($roleCat) {
            4       => 'Students',
            6       => 'Parents',
            default => 'All',
        };

        $announcements = $activeSchoolId
            ? $this->annModel->getActiveForSchool($activeSchoolId)
            : [];
        $notices = $activeSchoolId
            ? $this->noticeModel->getActiveForSchool($activeSchoolId, $audience)
            : [];

        $this->setPageData('Notices & Announcements', 'Dashboard', 'Notices & Announcements');
        $data = $this->loadCommonData('app/notices/index', [
            'schools'        => $schools,
            'activeSchoolId' => $activeSchoolId,
            'announcements'  => $announcements,
            'notices'        => $notices,
        ]);

        return view('app/layouts/main', $data);
    }
}
