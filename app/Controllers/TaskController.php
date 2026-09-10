<?php

namespace App\Controllers;

class TaskController extends BaseController
{
    /**
     * Whether the logged-in user may view/act on the given task: its
     * assignee, its creator, a Super Admin, or a holder of `_task_manage_all`.
     */
    private function canAccessTask(array $task): bool
    {
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $userId       = (int) $this->session->get('userID');

        return $isSuperAdmin
            || $this->taskModel->isParticipant($task, $userId)
            || $this->grant_access('_task_manage_all');
    }

    // ================================================================
    // MY TASKS
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('My Tasks', 'Task', 'My Tasks');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_task_access')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $userId = (int) $this->session->get('userID');
        $schId  = (int) $this->session->get('schID');

        $data['tasks']       = $this->taskModel->getForUser($userId);
        $data['staff']       = $schId > 0 ? $this->admissionModel->getActiveStaffBySchool($schId) : [];
        $data['canManageAll'] = $isSuperAdmin || $this->grant_access('_task_manage_all');
        $data['_view']        = 'app/task/index';

        return view('app/layouts/main', $data);
    }

    public function all()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $this->setPageData('All Tasks', 'Task', 'All Tasks');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->require_access('_task_manage_all')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $schId    = $isSuperAdmin ? 0 : (int) $this->session->get('schID');
        $status   = $this->request->getGet('status') ?: null;
        $priority = $this->request->getGet('priority') ?: null;

        $data['tasks']    = $this->taskModel->getAllForSchool($schId, $status, $priority);
        $data['status']   = $status;
        $data['priority'] = $priority;
        $data['_view']    = 'app/task/all';

        return view('app/layouts/main', $data);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_task_access')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $schId = (int) $this->session->get('schID');
        if ($schId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'No school context found for your account.']);
        }

        $title      = trim((string) $this->request->getPost('title'));
        $assignedTo = (int) $this->request->getPost('assigned_to');
        $priority   = $this->request->getPost('priority') ?: 'Medium';
        $dueDate    = $this->request->getPost('due_date') ?: null;

        if ($title === '' || $assignedTo <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please provide a title and an assignee.']);
        }
        if (!in_array($priority, ['Low', 'Medium', 'High', 'Urgent'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid priority.']);
        }

        $assignee = $this->userModel->find($assignedTo);
        if (!$assignee) {
            return $this->response->setJSON(['success' => false, 'message' => 'Assignee not found.']);
        }

        $taskId = $this->taskModel->insert([
            'sch_id_fk'              => $schId,
            'title'                  => $title,
            'description'            => trim((string) $this->request->getPost('description')) ?: null,
            'priority'               => $priority,
            'status'                 => 'To Do',
            'due_date'               => $dueDate,
            'assigned_to_user_id_fk' => $assignedTo,
            'created_by_user_id_fk'  => (int) $this->session->get('userID'),
            'created_at'             => date('Y-m-d H:i:s'),
            'updated_at'             => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Task created and assigned to ' . trim($assignee['fname'] . ' ' . $assignee['lname']) . '.',
            'redirect' => base_url('task/detail/' . $taskId),
        ]);
    }

    public function edit(int $taskId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $task = $this->taskModel->getDetail($taskId);
        if (!$task || !$this->canAccessTask($task)) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Edit Task', 'Task', 'My Tasks');

        $schId = (int) $task['sch_id_fk'];
        $data['task']  = $task;
        $data['staff'] = $this->admissionModel->getActiveStaffBySchool($schId);
        $data['_view'] = 'app/task/form';

        return view('app/layouts/main', $data);
    }

    public function update(int $taskId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $task = $this->taskModel->getDetail($taskId);
        if (!$task || !$this->canAccessTask($task)) {
            return redirect()->to('task')->with('error', 'Access denied.');
        }

        $title      = trim((string) $this->request->getPost('title'));
        $assignedTo = (int) $this->request->getPost('assigned_to');
        $priority   = $this->request->getPost('priority') ?: 'Medium';
        $dueDate    = $this->request->getPost('due_date') ?: null;

        if ($title === '' || $assignedTo <= 0) {
            return redirect()->back()->withInput()->with('error', 'Please provide a title and an assignee.');
        }
        if (!in_array($priority, ['Low', 'Medium', 'High', 'Urgent'], true)) {
            return redirect()->back()->withInput()->with('error', 'Invalid priority.');
        }

        $this->taskModel->update($taskId, [
            'title'                  => $title,
            'description'            => trim((string) $this->request->getPost('description')) ?: null,
            'priority'               => $priority,
            'due_date'               => $dueDate,
            'assigned_to_user_id_fk' => $assignedTo,
            'updated_at'             => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('task/detail/' . $taskId)->with('success', 'Task updated successfully.');
    }

    public function delete(int $taskId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $task = $this->taskModel->getDetail($taskId);
        if (!$task || !$this->canAccessTask($task)) {
            return redirect()->to('task')->with('error', 'Access denied.');
        }

        $this->taskModel->delete($taskId);

        return redirect()->to('task')->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(int $taskId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $task = $this->taskModel->getDetail($taskId);
        if (!$task || !$this->canAccessTask($task)) {
            return redirect()->to('task')->with('error', 'Access denied.');
        }

        $status = $this->request->getPost('status');
        if (!in_array($status, ['To Do', 'In Progress', 'Done', 'Cancelled'], true)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $this->taskModel->update($taskId, [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', "Task marked as {$status}.");
    }

    // ================================================================
    // DETAIL (checklist / comments)
    // ================================================================

    public function detail(int $taskId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $task = $this->taskModel->getDetail($taskId);
        if (!$task || !$this->canAccessTask($task)) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Task Detail', 'Task', 'My Tasks');

        $userId = (int) $this->session->get('userID');

        $data['task']        = $task;
        $data['checklist']   = $this->taskChecklistItemModel->getByTask($taskId);
        $data['comments']    = $this->taskCommentModel->getByTask($taskId);
        $data['canEdit']     = (int) $this->session->get('roleID') === 1
            || (int) $task['created_by_user_id_fk'] === $userId
            || $this->grant_access('_task_manage_all');
        $data['_view'] = 'app/task/detail';

        return view('app/layouts/main', $data);
    }

    public function storeChecklistItem(int $taskId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $task = $this->taskModel->getDetail($taskId);
        if (!$task || !$this->canAccessTask($task)) {
            return redirect()->to('task')->with('error', 'Access denied.');
        }

        $itemText = trim((string) $this->request->getPost('item_text'));
        if ($itemText === '') {
            return redirect()->back()->with('error', 'Please provide checklist item text.');
        }

        $this->taskChecklistItemModel->insert([
            'task_id_fk' => $taskId,
            'item_text'  => $itemText,
            'is_done'    => 0,
            'sort_order' => $this->taskChecklistItemModel->nextSortOrder($taskId),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('task/detail/' . $taskId)->with('success', 'Checklist item added.');
    }

    public function toggleChecklistItem(int $itemId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $item = $this->taskChecklistItemModel->find($itemId);
        if (!$item) {
            return redirect()->to('task')->with('error', 'Checklist item not found.');
        }

        $task = $this->taskModel->getDetail((int) $item['task_id_fk']);
        if (!$task || !$this->canAccessTask($task)) {
            return redirect()->to('task')->with('error', 'Access denied.');
        }

        $this->taskChecklistItemModel->update($itemId, [
            'is_done'    => (int) $item['is_done'] === 1 ? 0 : 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('task/detail/' . (int) $item['task_id_fk']);
    }

    public function deleteChecklistItem(int $itemId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $item = $this->taskChecklistItemModel->find($itemId);
        if (!$item) {
            return redirect()->to('task')->with('error', 'Checklist item not found.');
        }

        $task = $this->taskModel->getDetail((int) $item['task_id_fk']);
        if (!$task || !$this->canAccessTask($task)) {
            return redirect()->to('task')->with('error', 'Access denied.');
        }

        $this->taskChecklistItemModel->delete($itemId);

        return redirect()->to('task/detail/' . (int) $item['task_id_fk'])->with('success', 'Checklist item removed.');
    }

    public function storeComment(int $taskId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login')->with('error', 'Please login to continue.');
        }

        $task = $this->taskModel->getDetail($taskId);
        if (!$task || !$this->canAccessTask($task)) {
            return redirect()->to('task')->with('error', 'Access denied.');
        }

        $commentText = trim((string) $this->request->getPost('comment_text'));
        if ($commentText === '') {
            return redirect()->back()->with('error', 'Please provide a comment.');
        }

        $this->taskCommentModel->insert([
            'task_id_fk'   => $taskId,
            'user_id_fk'   => (int) $this->session->get('userID'),
            'comment_text' => $commentText,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('task/detail/' . $taskId)->with('success', 'Comment added.');
    }
}
