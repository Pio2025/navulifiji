<?php
namespace App\Controllers;

class PollController extends BaseController
{
    private array $audienceRoleCats = [
        2 => 'School Admin',
        3 => 'Teacher',
        4 => 'Student',
        5 => 'Support Staff',
        6 => 'Parent',
    ];

    // ================================================================
    // INDEX — Polls visible to the current user, plus a "Create Poll"
    // form for anyone with _poll_create.
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_poll_access')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Polls', 'Poll', 'All Polls');

        $userId    = (int) $this->session->get('userID');
        $roleCatId = (int) $this->session->get('roleCatID');
        $schId     = $isSuperAdmin ? null : (int) $this->session->get('schID');

        $polls = $this->pollModel->getVisibleForUser($schId, $userId, $roleCatId);
        foreach ($polls as &$poll) {
            $poll['is_closed']  = $this->pollModel->isClosed($poll);
            $poll['user_voted'] = $this->pollVoteModel->getUserVoteOptionId((int) $poll['poll_id'], $userId) !== null;
        }
        unset($poll);

        $canCreate = $isSuperAdmin || $this->grant_access('_poll_create');

        $data['polls']            = $polls;
        $data['canCreate']        = $canCreate;
        $data['canManageAll']     = $isSuperAdmin || $this->grant_access('_poll_manage_all');
        $data['myUserId']         = $userId;
        $data['audienceRoleCats'] = $this->audienceRoleCats;
        $data['isSuperAdmin']     = $isSuperAdmin;
        $data['allSchools']       = ($isSuperAdmin && $canCreate) ? $this->getAllSchools() : [];
        $data['_view']            = 'app/poll/index';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STORE — Create a poll with its options and optional audience.
    // ================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_poll_create')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        try {
            $question = trim((string) $this->request->getPost('question'));
            if ($question === '') {
                throw new \InvalidArgumentException('Please provide a poll question.');
            }

            $options = $this->request->getPost('options') ?? [];
            $options = is_array($options) ? array_values(array_filter(array_map('trim', $options), fn ($o) => $o !== '')) : [];
            if (count($options) < 2) {
                throw new \InvalidArgumentException('Please provide at least two options.');
            }

            $schId = $isSuperAdmin ? (int) $this->request->getPost('sch_id') : (int) $this->session->get('schID');
            if ($schId <= 0) {
                throw new \InvalidArgumentException($isSuperAdmin ? 'Please select a school for this poll.' : 'No school is associated with your account.');
            }

            $closesAtInput = $this->request->getPost('closes_at');
            $closesAt      = $closesAtInput ? date('Y-m-d H:i:s', strtotime($closesAtInput)) : null;

            $now    = date('Y-m-d H:i:s');
            $pollId = (int) $this->pollModel->insert([
                'sch_id_fk'             => $schId,
                'created_by_user_id_fk' => (int) $this->session->get('userID'),
                'question'              => $question,
                'description'           => trim((string) $this->request->getPost('description')) ?: null,
                'status'                => 'Active',
                'closes_at'             => $closesAt,
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);

            foreach ($options as $i => $optionText) {
                $this->pollOptionModel->insert([
                    'poll_id_fk'  => $pollId,
                    'option_text' => $optionText,
                    'sort_order'  => $i,
                ]);
            }

            $audience = $this->request->getPost('audience') ?? [];
            $audience = is_array($audience) ? array_map('intval', $audience) : [];
            foreach ($audience as $roleCatId) {
                if (isset($this->audienceRoleCats[$roleCatId])) {
                    $this->pollAudienceModel->insert(['poll_id_fk' => $pollId, 'role_cat_id_fk' => $roleCatId]);
                }
            }

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Poll created.',
                'redirect' => base_url('poll'),
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            log_message('error', '[PollController::store] ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    // ================================================================
    // RESULTS — AJAX JSON snapshot of live results, for polling refresh.
    // ================================================================

    public function results(int $pollId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $poll = $this->pollModel->getDetail($pollId);
        if (!$poll || !$this->canView($poll)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Poll not found.']);
        }

        $options    = $this->pollOptionModel->getWithCounts($pollId);
        $totalVotes = $this->pollVoteModel->countForPoll($pollId);
        $userVote   = $this->pollVoteModel->getUserVoteOptionId($pollId, (int) $this->session->get('userID'));

        return $this->response->setJSON([
            'success'     => true,
            'is_closed'   => $this->pollModel->isClosed($poll),
            'total_votes' => $totalVotes,
            'user_vote'   => $userVote,
            'options'     => array_map(fn ($o) => [
                'option_id'   => (int) $o['option_id'],
                'option_text' => $o['option_text'],
                'vote_count'  => (int) $o['vote_count'],
                'percent'     => $totalVotes > 0 ? round(((int) $o['vote_count'] / $totalVotes) * 100, 1) : 0,
            ], $options),
        ]);
    }

    // ================================================================
    // VOTE — Cast (or change) a single-choice vote on an active poll.
    // ================================================================

    public function vote(int $pollId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_poll_access')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $poll = $this->pollModel->getDetail($pollId);
        if (!$poll || !$this->canView($poll)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Poll not found.']);
        }
        if ($this->pollModel->isClosed($poll)) {
            return $this->response->setJSON(['success' => false, 'message' => 'This poll is closed.']);
        }

        $optionId = (int) $this->request->getPost('option_id');
        $option   = $this->pollOptionModel->find($optionId);
        if (!$option || (int) $option['poll_id_fk'] !== $pollId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid option.']);
        }

        $this->pollVoteModel->castVote($pollId, $optionId, (int) $this->session->get('userID'));

        return $this->response->setJSON(['success' => true, 'message' => 'Vote recorded.']);
    }

    // ================================================================
    // CLOSE — Manually close a poll (creator or admin-tier only).
    // ================================================================

    public function close(int $pollId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $poll = $this->pollModel->find($pollId);
        if (!$poll) {
            return $this->response->setJSON(['success' => false, 'message' => 'Poll not found.']);
        }
        if (!$this->canManage($poll)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $this->pollModel->close($pollId);

        return $this->response->setJSON(['success' => true, 'message' => 'Poll closed.']);
    }

    // ================================================================
    // DELETE — Delete a poll and all its options/audience/votes.
    // ================================================================

    public function delete(int $pollId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $poll = $this->pollModel->find($pollId);
        if (!$poll) {
            return $this->response->setJSON(['success' => false, 'message' => 'Poll not found.']);
        }
        if (!$this->canManage($poll)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $this->pollModel->deleteCascade($pollId);

        return $this->response->setJSON(['success' => true, 'message' => 'Poll deleted.']);
    }

    private function getAllSchools(): array
    {
        return \Config\Database::connect()
            ->table('school')
            ->select('sch_id, sch_name')
            ->where('sch_status', 'Active')
            ->orderBy('sch_name', 'ASC')
            ->get()->getResultArray();
    }

    // ─── access helpers ────────────────────────────────────────────────

    private function canView(array $poll): bool
    {
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if ($isSuperAdmin) {
            return true;
        }

        $userId = (int) $this->session->get('userID');
        if ((int) $poll['created_by_user_id_fk'] === $userId) {
            return true;
        }

        $schId = (int) $this->session->get('schID');
        if ((int) $poll['sch_id_fk'] !== $schId) {
            return false;
        }

        $roleCatId = (int) $this->session->get('roleCatID');
        return $this->pollModel->isVisibleToRoleCat((int) $poll['poll_id'], $roleCatId);
    }

    private function canManage(array $poll): bool
    {
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if ($isSuperAdmin || $this->grant_access('_poll_manage_all')) {
            return true;
        }

        return (int) $poll['created_by_user_id_fk'] === (int) $this->session->get('userID');
    }
}
