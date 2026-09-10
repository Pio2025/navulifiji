<?php
$children  = $children  ?? [];
$allocations = $allocations ?? [];
$admission = $admission ?? null;
$isStudent = $isStudent ?? false;
$isParent  = $isParent  ?? false;

function hostel_render_allocations_table(array $allocations): void {
    if (empty($allocations)) {
        echo '<p class="text-muted fs-7 mb-0">No hostel allocation on record.</p>';
        return;
    }
    ?>
    <div class="table-responsive">
        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
            <thead><tr class="fw-bold text-muted">
                <th>Hostel / Room</th><th>Check-In</th><th>Check-Out</th>
                <th class="text-center">Fee</th><th class="text-center">Status</th>
            </tr></thead>
            <tbody>
            <?php foreach ($allocations as $a): ?>
            <tr>
                <td>
                    <span class="fw-semibold"><?= esc($a['hostel_name']) ?></span>
                    <div class="text-muted fs-8">Room <?= esc($a['room_number']) ?></div>
                </td>
                <td><?= esc($a['check_in_date']) ?></td>
                <td><?= esc($a['check_out_date'] ?? '—') ?></td>
                <td class="text-center">
                    <?= (float)($a['fee_amount'] ?? 0) > 0 ? number_format((float)$a['fee_amount'], 2) : '—' ?>
                    <?php if ((int)($a['fee_paid'] ?? 0) === 1 && (float)($a['fee_amount'] ?? 0) > 0): ?>
                        <span class="badge badge-light-success fs-9 ms-1">Paid</span>
                    <?php elseif ((float)($a['fee_amount'] ?? 0) > 0): ?>
                        <span class="badge badge-light-warning fs-9 ms-1">Unpaid</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <span class="badge badge-light-<?= $a['status'] === 'Active' ? 'success' : 'secondary' ?>"><?= esc($a['status']) ?></span>
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
                <?= $isParent ? "Child's Hostel" : 'My Hostel' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Hostel</li>
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
            <i class="ki-duotone ki-home-3 fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span></i>
            <p class="text-muted fs-6">You are not currently enrolled. Please contact your school administrator.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6"><h3 class="fw-bold text-gray-900 fs-5">My Hostel Allocation</h3></div>
        <div class="card-body pt-0">
            <?php hostel_render_allocations_table($allocations); ?>
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
        $child           = $cd['child'];
        $adm             = $cd['admission'];
        $childAllocations = $cd['allocations'];
        $initials        = strtoupper(substr($child['fname'], 0, 1) . substr($child['lname'], 0, 1));
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
            <?php hostel_render_allocations_table($childAllocations); ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <?php endif; ?>

<?php endif; ?>

</div>
</div>
