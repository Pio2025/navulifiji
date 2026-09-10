<?php
$tasks    = $tasks ?? [];
$status   = $status ?? null;
$priority = $priority ?? null;

function task_priority_badge(string $priority): string {
    $map = ['Low' => 'secondary', 'Medium' => 'info', 'High' => 'warning', 'Urgent' => 'danger'];
    $cls = $map[$priority] ?? 'secondary';
    return "<span class=\"badge badge-light-{$cls}\">" . esc($priority) . '</span>';
}

function task_status_badge(string $status): string {
    $map = ['To Do' => 'secondary', 'In Progress' => 'primary', 'Done' => 'success', 'Cancelled' => 'danger'];
    $cls = $map[$status] ?? 'secondary';
    return "<span class=\"badge badge-light-{$cls}\">" . esc($status) . '</span>';
}
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">All Tasks</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('task') ?>" class="text-muted text-hover-primary">Tasks</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">All Tasks</li>
            </ul>
        </div>
        <a href="<?= base_url('task') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            My Tasks
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<div class="card mb-6">
    <div class="card-body py-4">
        <form method="GET" action="<?= base_url('task/all') ?>" class="row g-3 align-items-end">
            <div class="col-lg-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    <?php foreach (['To Do', 'In Progress', 'Done', 'Cancelled'] as $s): ?>
                    <option value="<?= esc($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= esc($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-label fw-semibold">Priority</label>
                <select name="priority" class="form-select" onchange="this.form.submit()">
                    <option value="">All priorities</option>
                    <?php foreach (['Low', 'Medium', 'High', 'Urgent'] as $p): ?>
                    <option value="<?= esc($p) ?>" <?= $priority === $p ? 'selected' : '' ?>><?= esc($p) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900 fs-5">Tasks (<?= count($tasks) ?>)</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <?php if (empty($tasks)): ?>
        <div class="text-center py-16">
            <i class="ki-duotone ki-check-square fs-4x text-gray-200 mb-4">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <div class="fs-6 fw-semibold text-gray-600">No tasks match this filter.</div>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted fs-7 bg-light">
                        <th class="ps-4">Task</th>
                        <th>Assignee</th>
                        <th>Created By</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Due</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($tasks as $t): ?>
                <tr>
                    <td class="ps-4">
                        <a href="<?= base_url('task/detail/' . (int) $t['task_id']) ?>" class="fw-semibold text-gray-900 text-hover-primary">
                            <?= esc($t['title']) ?>
                        </a>
                    </td>
                    <td><?= esc(trim($t['assignee_fname'] . ' ' . $t['assignee_lname'])) ?></td>
                    <td><?= esc(trim($t['creator_fname'] . ' ' . $t['creator_lname'])) ?></td>
                    <td class="text-center"><?= task_priority_badge($t['priority']) ?></td>
                    <td class="text-center"><?= task_status_badge($t['status']) ?></td>
                    <td class="text-center"><?= esc($t['due_date'] ?? '—') ?></td>
                    <td class="text-end pe-4">
                        <a href="<?= base_url('task/detail/' . (int) $t['task_id']) ?>" class="btn btn-sm btn-icon btn-light-info" title="View">
                            <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

</div>
</div>
