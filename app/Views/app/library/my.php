<?php
$children  = $children  ?? [];
$issues    = $issues    ?? [];
$admission = $admission ?? null;
$isStudent = $isStudent ?? false;
$isParent  = $isParent  ?? false;

$statusColor = [
    'Issued'   => 'warning',
    'Returned' => 'success',
    'Lost'     => 'danger',
];

function lib_render_issues_table(array $issues): void {
    $statusColor = ['Issued' => 'warning', 'Returned' => 'success', 'Lost' => 'danger'];
    $today = date('Y-m-d');
    if (empty($issues)) {
        echo '<p class="text-muted fs-7 mb-0">No books borrowed yet.</p>';
        return;
    }
    ?>
    <div class="table-responsive">
        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
            <thead><tr class="fw-bold text-muted">
                <th>Book</th><th>Issue Date</th><th>Due Date</th><th>Return Date</th>
                <th class="text-center">Fine</th><th class="text-center">Status</th>
            </tr></thead>
            <tbody>
            <?php foreach ($issues as $iss):
                $isOverdue = $iss['status'] === 'Issued' && $iss['due_date'] < $today;
            ?>
            <tr>
                <td>
                    <span class="fw-semibold"><?= esc($iss['title']) ?></span>
                    <?php if (!empty($iss['author'])): ?><div class="text-muted fs-8"><?= esc($iss['author']) ?></div><?php endif; ?>
                </td>
                <td><?= esc($iss['issue_date']) ?></td>
                <td>
                    <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>"><?= esc($iss['due_date']) ?></span>
                    <?php if ($isOverdue): ?><div class="badge badge-light-danger fs-9">Overdue</div><?php endif; ?>
                </td>
                <td><?= esc($iss['return_date'] ?? '—') ?></td>
                <td class="text-center">
                    <?= (float)($iss['fine_amount'] ?? 0) > 0 ? '$' . number_format((float)$iss['fine_amount'], 2) : '—' ?>
                    <?php if ((int)($iss['fine_paid'] ?? 0) === 1 && (float)($iss['fine_amount'] ?? 0) > 0): ?>
                        <span class="badge badge-light-success fs-9 ms-1">Paid</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <span class="badge badge-light-<?= $statusColor[$iss['status']] ?? 'secondary' ?>"><?= esc($iss['status']) ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isParent ? "Children's Library" : 'My Library' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Library</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php if ($isStudent): ?>
    <!-- ── Student view ──────────────────────────────────────────────────── -->
    <?php if (!$admission): ?>
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-10 text-center">
            <i class="ki-duotone ki-book fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
            <p class="text-muted fs-6">You are not currently enrolled. Please contact your school administrator.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6"><h3 class="fw-bold text-gray-900 fs-5">My Borrowed Books</h3></div>
        <div class="card-body pt-0">
            <?php lib_render_issues_table($issues); ?>
        </div>
    </div>
    <?php endif; ?>

<?php else: ?>
    <!-- ── Parent view ───────────────────────────────────────────────────── -->

    <?php if (empty($children)): ?>
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-10 text-center">
            <i class="ki-duotone ki-people fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
            <p class="text-muted fs-6">No linked children found. Please contact the school to link your children to your account.</p>
        </div>
    </div>
    <?php else: ?>

    <?php foreach ($children as $cd):
        $child      = $cd['child'];
        $adm        = $cd['admission'];
        $childIssues = $cd['issues'];
        $initials   = strtoupper(substr($child['fname'], 0, 1) . substr($child['lname'], 0, 1));
    ?>
    <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title d-flex align-items-center gap-3">
                <?php if (!empty($child['profile_photo'])): ?>
                <img src="<?= base_url('uploads/profilePhoto/' . esc($child['profile_photo'])) ?>" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" alt="">
                <?php else: ?>
                <span class="symbol symbol-40px symbol-circle d-inline-flex align-items-center justify-content-center bg-light-primary text-primary fw-bold fs-7" style="width:40px;height:40px;border-radius:50%;"><?= $initials ?></span>
                <?php endif; ?>
                <div>
                    <h3 class="fw-bold text-gray-900 fs-5 mb-0"><?= esc($child['fname'] . ' ' . $child['lname']) ?></h3>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <?php if (!$adm): ?>
            <p class="text-muted fs-7">Not currently enrolled.</p>
            <?php else: ?>
            <?php lib_render_issues_table($childIssues); ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <?php endif; ?>

<?php endif; ?>

</div>
</div>
