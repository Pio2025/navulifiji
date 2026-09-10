<?php
$children    = $children    ?? [];
$allocations = $allocations  ?? [];
$admission   = $admission    ?? null;
$isStudent   = $isStudent    ?? false;
$isParent    = $isParent     ?? false;
$year        = $year         ?? (int) date('Y');

$statusColor = [
    'Draft'     => 'secondary',
    'Submitted' => 'primary',
    'Vetted'    => 'warning',
    'Approved'  => 'success',
    'Rejected'  => 'danger',
];

function tp_current_year_allocation(array $allocations, int $year): ?array {
    foreach ($allocations as $a) {
        if ((int) $a['academic_year'] === $year) {
            return $a;
        }
    }
    return null;
}
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isParent ? "Children's Transport Allocation" : 'My Transport Allocation' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Transportation</li>
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
            <i class="ki-duotone ki-bus fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span></i>
            <p class="text-muted fs-6">You are not currently enrolled. Please contact your school administrator.</p>
        </div>
    </div>
    <?php else:
        $current = tp_current_year_allocation($allocations, $year);
    ?>
    <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-6 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-gray-900 mb-1"><?= $year ?> Application</h4>
                <?php if ($current): ?>
                <span class="badge badge-light-<?= $statusColor[$current['application_status']] ?? 'secondary' ?>"><?= esc($current['application_status']) ?></span>
                <?php else: ?>
                <p class="text-muted fs-7 mb-0">You have not yet applied for the <?= $year ?> transport assistance allocation.</p>
                <?php endif; ?>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('transportation/my/form') ?>" class="btn btn-sm btn-primary">
                    <i class="ki-duotone ki-pencil fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                    <?= $current ? 'Edit ' . $year . ' Application' : 'Apply for ' . $year ?>
                </a>
                <?php if ($current): ?>
                <a href="<?= base_url('transportation/form/' . $current['allocation_id']) ?>" target="_blank" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Download Form (PDF)
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($allocations)): ?>
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6"><h3 class="fw-bold text-gray-900 fs-5">Application History</h3></div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead><tr class="fw-bold text-muted">
                        <th>Year</th><th>E-Transport Card</th><th>Status</th><th class="text-end">Form</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($allocations as $a): ?>
                    <tr>
                        <td><?= esc($a['academic_year']) ?></td>
                        <td><span class="text-muted fs-7"><?= esc($a['e_transport_card_number'] ?? '—') ?></span></td>
                        <td><span class="badge badge-light-<?= $statusColor[$a['application_status']] ?? 'secondary' ?>"><?= esc($a['application_status']) ?></span></td>
                        <td class="text-end">
                            <a href="<?= base_url('transportation/form/' . $a['allocation_id']) ?>" target="_blank" class="btn btn-sm btn-light-primary">View PDF</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
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
        $child       = $cd['child'];
        $adm         = $cd['admission'];
        $childAllocs = $cd['allocations'];
        $current     = tp_current_year_allocation($childAllocs, $year);
        $initials    = strtoupper(substr($child['fname'], 0, 1) . substr($child['lname'], 0, 1));
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
                    <span class="text-muted fs-8"><?= esc($adm['sch_name'] ?? '') ?></span>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <?php if (!$adm): ?>
            <p class="text-muted fs-7">Not currently enrolled.</p>
            <?php else: ?>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 p-4 bg-light rounded">
                <div>
                    <span class="fw-semibold fs-7"><?= $year ?> Application:</span>
                    <?php if ($current): ?>
                    <span class="badge badge-light-<?= $statusColor[$current['application_status']] ?? 'secondary' ?> ms-1"><?= esc($current['application_status']) ?></span>
                    <?php else: ?>
                    <span class="text-muted fs-8 ms-1">Not yet applied</span>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('transportation/my/form?student_user_id=' . $child['user_id']) ?>" class="btn btn-sm btn-primary">
                        <i class="ki-duotone ki-pencil fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        <?= $current ? 'Edit Application' : 'Apply for ' . $year ?>
                    </a>
                    <?php if ($current): ?>
                    <a href="<?= base_url('transportation/form/' . $current['allocation_id']) ?>" target="_blank" class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Download Form (PDF)
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($childAllocs)): ?>
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead><tr class="fw-bold text-muted">
                        <th>Year</th><th>E-Transport Card</th><th>Status</th><th class="text-end">Form</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($childAllocs as $a): ?>
                    <tr>
                        <td><?= esc($a['academic_year']) ?></td>
                        <td><span class="text-muted fs-7"><?= esc($a['e_transport_card_number'] ?? '—') ?></span></td>
                        <td><span class="badge badge-light-<?= $statusColor[$a['application_status']] ?? 'secondary' ?>"><?= esc($a['application_status']) ?></span></td>
                        <td class="text-end">
                            <a href="<?= base_url('transportation/form/' . $a['allocation_id']) ?>" target="_blank" class="btn btn-sm btn-light-primary">View PDF</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <?php endif; ?>

<?php endif; ?>

</div>
</div>
