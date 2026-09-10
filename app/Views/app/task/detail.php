<?php
$task      = $task      ?? [];
$checklist = $checklist ?? [];
$comments  = $comments  ?? [];
$canEdit   = $canEdit   ?? false;

$priorityMap = ['Low' => 'secondary', 'Medium' => 'info', 'High' => 'warning', 'Urgent' => 'danger'];
$statusMap   = ['To Do' => 'secondary', 'In Progress' => 'primary', 'Done' => 'success', 'Cancelled' => 'danger'];
$doneCount   = count(array_filter($checklist, fn($c) => (int) $c['is_done'] === 1));
$totalCount  = count($checklist);
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= esc($task['title']) ?></h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('task') ?>" class="text-muted text-hover-primary">Tasks</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('task/edit/' . (int) $task['task_id']) ?>" class="btn btn-light-primary">
                <i class="ki-duotone ki-pencil fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                Edit
            </a>
            <button type="button" class="btn btn-light-danger" onclick="confirmDeleteTask()">
                <i class="ki-duotone ki-trash fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                Delete
            </button>
            <?php endif; ?>
            <a href="<?= base_url('task') ?>" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                Back
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<div class="row g-6">
    <div class="col-lg-8">

        <!--begin::Task info-->
        <div class="card mb-6">
            <div class="card-body">
                <div class="row g-5 mb-5">
                    <div class="col-6 col-lg-3">
                        <div class="text-muted fs-8">Priority</div>
                        <span class="badge badge-light-<?= $priorityMap[$task['priority']] ?? 'secondary' ?> fs-7"><?= esc($task['priority']) ?></span>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="text-muted fs-8">Status</div>
                        <span class="badge badge-light-<?= $statusMap[$task['status']] ?? 'secondary' ?> fs-7"><?= esc($task['status']) ?></span>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="text-muted fs-8">Due Date</div>
                        <div class="fw-semibold"><?= esc($task['due_date'] ?? '—') ?></div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="text-muted fs-8">Assignee</div>
                        <div class="fw-semibold"><?= esc(trim($task['assignee_fname'] . ' ' . $task['assignee_lname'])) ?></div>
                    </div>
                </div>
                <?php if (!empty($task['description'])): ?>
                <div class="mb-5">
                    <div class="text-muted fs-8">Description</div>
                    <div class="fs-6 text-gray-700"><?= nl2br(esc($task['description'])) ?></div>
                </div>
                <?php endif; ?>
                <div class="text-muted fs-8">Created by <?= esc(trim($task['creator_fname'] . ' ' . $task['creator_lname'])) ?></div>

                <div class="separator my-5"></div>

                <form id="status_form" action="<?= base_url('task/status/' . (int) $task['task_id']) ?>" method="POST" class="d-flex align-items-center gap-3">
                    <?= csrf_field() ?>
                    <label class="form-label fw-semibold mb-0">Update Status</label>
                    <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                        <?php foreach (['To Do', 'In Progress', 'Done', 'Cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= $task['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
        <!--end::Task info-->

        <!--begin::Checklist-->
        <div class="card mb-6">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold text-gray-900 fs-5">Checklist <?= $totalCount > 0 ? "({$doneCount}/{$totalCount})" : '' ?></h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($checklist)): ?>
                <p class="text-muted fs-7 mb-4">No checklist items yet.</p>
                <?php else: ?>
                <div class="mb-4">
                    <?php foreach ($checklist as $item): ?>
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-gray-200">
                        <form action="<?= base_url('task/checklist/toggle/' . (int) $item['checklist_item_id']) ?>" method="POST" class="d-flex align-items-center flex-grow-1">
                            <?= csrf_field() ?>
                            <input type="checkbox" class="form-check-input me-3" onchange="this.form.submit()" <?= (int) $item['is_done'] === 1 ? 'checked' : '' ?> />
                            <span class="<?= (int) $item['is_done'] === 1 ? 'text-muted text-decoration-line-through' : 'text-gray-800' ?>"><?= esc($item['item_text']) ?></span>
                        </form>
                        <form action="<?= base_url('task/checklist/remove/' . (int) $item['checklist_item_id']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Remove">
                                <i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form action="<?= base_url('task/checklist/store/' . (int) $task['task_id']) ?>" method="POST" class="d-flex gap-2">
                    <?= csrf_field() ?>
                    <input type="text" name="item_text" class="form-control" placeholder="Add a checklist item…" maxlength="255" />
                    <button type="submit" class="btn btn-light-primary">Add</button>
                </form>
            </div>
        </div>
        <!--end::Checklist-->

        <!--begin::Comments-->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold text-gray-900 fs-5">Comments (<?= count($comments) ?>)</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($comments)): ?>
                <p class="text-muted fs-7 mb-4">No comments yet.</p>
                <?php else: ?>
                <div class="mb-4">
                    <?php foreach ($comments as $c): ?>
                    <div class="d-flex mb-4">
                        <div class="symbol symbol-35px symbol-circle me-3">
                            <?php if (!empty($c['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . esc($c['profile_photo'])) ?>" alt="" />
                            <?php else: ?>
                            <span class="symbol-label bg-light-primary text-primary fw-bold">
                                <?= strtoupper(substr($c['fname'], 0, 1) . substr($c['lname'], 0, 1)) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-semibold text-gray-900"><?= esc(trim($c['fname'] . ' ' . $c['lname'])) ?></span>
                                <span class="text-muted fs-8"><?= esc($c['created_at']) ?></span>
                            </div>
                            <div class="fs-6 text-gray-700"><?= nl2br(esc($c['comment_text'])) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form action="<?= base_url('task/comment/store/' . (int) $task['task_id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <textarea name="comment_text" rows="2" class="form-control mb-3" placeholder="Write a comment…"></textarea>
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </form>
            </div>
        </div>
        <!--end::Comments-->

    </div>
</div>

</div>
</div>

<?php if ($canEdit): ?>
<form id="deleteTaskForm" action="<?= base_url('task/remove/' . (int) $task['task_id']) ?>" method="POST" class="d-none">
    <?= csrf_field() ?>
</form>
<script>
function confirmDeleteTask() {
    Swal.fire({
        title: 'Delete this task?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
    }).then(function (result) {
        if (result.isConfirmed) {
            document.getElementById('deleteTaskForm').submit();
        }
    });
}
</script>
<?php endif; ?>
