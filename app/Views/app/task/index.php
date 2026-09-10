<?php
$tasks        = $tasks ?? [];
$staff        = $staff ?? [];
$canManageAll = $canManageAll ?? false;

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
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">My Tasks</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Tasks</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canManageAll): ?>
            <a href="<?= base_url('task/all') ?>" class="btn btn-light">
                <i class="ki-duotone ki-element-11 fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                All Tasks
            </a>
            <?php endif; ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTaskModal">
                <i class="ki-duotone ki-plus fs-2"></i>
                New Task
            </button>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

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
            <div class="fs-6 fw-semibold text-gray-600">No tasks yet. Create one to get started.</div>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted fs-7 bg-light">
                        <th class="ps-4">Task</th>
                        <th>Assignee</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Due</th>
                        <th class="text-center">Checklist</th>
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
                        <?php if ((int) ($t['comment_count'] ?? 0) > 0): ?>
                        <div class="text-muted fs-8"><i class="ki-duotone ki-message-text-2 fs-7 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><?= (int) $t['comment_count'] ?> comment<?= (int) $t['comment_count'] === 1 ? '' : 's' ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?= esc(trim($t['assignee_fname'] . ' ' . $t['assignee_lname'])) ?></td>
                    <td class="text-center"><?= task_priority_badge($t['priority']) ?></td>
                    <td class="text-center"><?= task_status_badge($t['status']) ?></td>
                    <td class="text-center"><?= esc($t['due_date'] ?? '—') ?></td>
                    <td class="text-center">
                        <?php if ((int) ($t['checklist_total'] ?? 0) > 0): ?>
                            <?= (int) $t['checklist_done'] ?>/<?= (int) $t['checklist_total'] ?>
                        <?php else: ?>—<?php endif; ?>
                    </td>
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

<!--begin::New Task modal-->
<div class="modal fade" id="newTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="new_task_form">
                    <?= csrf_field() ?>
                    <div class="row g-5">
                        <div class="col-12">
                            <label class="form-label required fw-semibold">Title</label>
                            <input type="text" name="title" class="form-control" maxlength="200" required />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Assign To</label>
                            <select id="new_task_assignee" name="assigned_to" class="form-select">
                                <option value="">— Select staff —</option>
                                <?php foreach ($staff as $s): ?>
                                <option value="<?= (int) $s['user_id'] ?>">
                                    <?= esc(trim($s['fname'] . ' ' . $s['lname'])) ?> — <?= esc($s['role_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn_create_task" class="btn btn-primary">Create Task</button>
            </div>
        </div>
    </div>
</div>
<!--end::New Task modal-->

<script>
"use strict";

$('#new_task_assignee').select2({ placeholder: '— Select staff —', width: '100%', dropdownParent: $('#newTaskModal') });

document.getElementById('btn_create_task').addEventListener('click', function () {
    var btn = this;
    var title = document.querySelector('#new_task_form [name="title"]').value.trim();
    var assignee = document.getElementById('new_task_assignee').value;

    if (!title || !assignee) {
        Swal.fire({ title: 'Missing information', text: 'Please provide a title and select an assignee.', icon: 'warning' });
        return;
    }

    var formData = new FormData(document.getElementById('new_task_form'));

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url: '<?= base_url('task/store') ?>', type: 'POST', data: formData, processData: false, contentType: false,
        success: function (response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Created!', text: response.message, icon: 'success', timer: 1800, showConfirmButton: false })
                    .then(function () { window.location.href = response.redirect; });
            } else {
                Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
            }
        },
        error: function () {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An unexpected error occurred.', icon: 'error' });
        }
    });
});
</script>
