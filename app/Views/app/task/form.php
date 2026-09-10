<?php
$task     = $task  ?? [];
$staff    = $staff ?? [];
$oldInput = session()->getFlashdata('_ci_old_input') ?? [];
$hasOld   = !empty($oldInput);

$val = function (string $field, $default = '') use ($task, $oldInput, $hasOld) {
    if ($hasOld) {
        return $oldInput[$field] ?? '';
    }
    return $task[$field] ?? $default;
};
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit Task</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('task') ?>" class="text-muted text-hover-primary">Tasks</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Edit</li>
            </ul>
        </div>
        <a href="<?= base_url('task/detail/' . (int) $task['task_id']) ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to Task
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <form action="<?= base_url('task/update/' . (int) $task['task_id']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="card mb-6">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold text-gray-800 mb-0 fs-5">Task Details</h3>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-5">
                        <div class="col-12">
                            <label class="form-label required fw-semibold">Title</label>
                            <input type="text" name="title" maxlength="200"
                                class="form-control <?= session('validation')?->hasError('title') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('title')) ?>" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="4" class="form-control"><?= esc($val('description')) ?></textarea>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label required fw-semibold">Assign To</label>
                            <select id="edit_task_assignee" name="assigned_to" class="form-select">
                                <?php foreach ($staff as $s): ?>
                                <option value="<?= (int) $s['user_id'] ?>" <?= (int) $val('assigned_to_user_id_fk') === (int) $s['user_id'] ? 'selected' : '' ?>>
                                    <?= esc(trim($s['fname'] . ' ' . $s['lname'])) ?> — <?= esc($s['role_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold">Priority</label>
                            <select name="priority" class="form-select">
                                <?php foreach (['Low', 'Medium', 'High', 'Urgent'] as $p): ?>
                                <option value="<?= $p ?>" <?= $val('priority') === $p ? 'selected' : '' ?>><?= $p ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="<?= esc($val('due_date')) ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mb-10">
                <a href="<?= base_url('task/detail/' . (int) $task['task_id']) ?>" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

<script>
"use strict";
$('#edit_task_assignee').select2({ width: '100%' });
</script>
