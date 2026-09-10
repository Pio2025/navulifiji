<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($allocation['student_fname'] . ' ' . $allocation['student_lname']) ?> &mdash; <?= esc($allocation['academic_year']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('transportation') ?>" class="text-muted text-hover-primary">Transportation</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <?php if ($canGenerateForm): ?>
            <a href="<?= base_url('transportation/form/' . $allocation['allocation_id']) ?>" target="_blank" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-file-down fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                Generate Ministry Form (PDF)
            </a>
            <?php endif; ?>
            <?php if ($canEdit): ?>
            <a href="<?= base_url('transportation/edit/' . $allocation['allocation_id']) ?>" class="btn btn-sm btn-light-warning">
                <i class="ki-duotone ki-pencil fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                Edit
            </a>
            <?php endif; ?>
            <?php if ($canDelete): ?>
            <button class="btn btn-sm btn-light-danger" onclick="confirmDelete(<?= $allocation['allocation_id'] ?>)">
                <i class="ki-duotone ki-trash fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                Delete
            </button>
            <?php endif; ?>
            <a href="<?= base_url('transportation') ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
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

            <!--begin::Student & status -->
            <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header border-0 pt-6">
                    <div class="card-title"><h3 class="fw-bold text-gray-900 fs-4">Section A: Student Information</h3></div>
                    <div class="card-toolbar">
                        <?php
                            $statusColor = [
                                'Draft'     => 'secondary',
                                'Submitted' => 'primary',
                                'Vetted'    => 'warning',
                                'Approved'  => 'success',
                                'Rejected'  => 'danger',
                            ][$allocation['application_status']] ?? 'secondary';
                        ?>
                        <span class="badge badge-light-<?= $statusColor ?> fs-7"><?= esc($allocation['application_status']) ?></span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4 fs-7">
                        <div class="col-md-6"><span class="fw-semibold text-muted">Student:</span> <?= esc($allocation['student_fname'] . ' ' . $allocation['student_lname']) ?></div>
                        <div class="col-md-6"><span class="fw-semibold text-muted">Academic Year:</span> <?= esc($allocation['academic_year']) ?></div>
                        <div class="col-md-6"><span class="fw-semibold text-muted">DOB:</span> <?= esc($allocation['dob'] ?? '—') ?></div>
                        <div class="col-md-6"><span class="fw-semibold text-muted">Address:</span> <?= esc($allocation['address'] ?? '—') ?></div>
                        <div class="col-md-6"><span class="fw-semibold text-muted">Phone:</span> <?= esc($allocation['phone'] ?? '—') ?></div>
                        <div class="col-md-6"><span class="fw-semibold text-muted">School:</span> <?= esc($allocation['sch_name'] ?? '—') ?></div>
                        <div class="col-md-6"><span class="fw-semibold text-muted">Class / Stream:</span> <?= esc(trim(($allocation['level_name'] ?? '') . ' ' . ($allocation['stream_name'] ?? ''))) ?: '—' ?></div>
                        <div class="col-md-6"><span class="fw-semibold text-muted">E-Transport Card No.:</span> <?= esc($allocation['e_transport_card_number'] ?? '—') ?></div>
                    </div>
                </div>
            </div>
            <!--end::Student & status-->

            <!--begin::Household -->
            <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header border-0 pt-6">
                    <div class="card-title"><h3 class="fw-bold text-gray-900 fs-4">Section B: Household Information</h3></div>
                </div>
                <div class="card-body pt-0">
                    <div class="fs-7 mb-4">
                        <span class="fw-semibold text-muted">Receiving Social Welfare:</span>
                        <?= !empty($allocation['receiving_social_welfare']) ? 'Yes' : 'No' ?>
                        <?php if (!empty($allocation['receiving_social_welfare']) && !empty($allocation['social_welfare_number'])): ?>
                        &mdash; No.: <?= esc($allocation['social_welfare_number']) ?>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2">
                            <thead>
                                <tr class="fw-bold text-muted fs-8 text-uppercase">
                                    <th>Full Name</th><th>DOB</th><th>Relationship</th><th>Occupation</th>
                                    <th>Annual Income</th><th>TIN Number</th><th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($household)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">No household members recorded.</td></tr>
                            <?php else: foreach ($household as $m): ?>
                                <tr class="fs-7">
                                    <td><?= esc($m['member_name']) ?></td>
                                    <td><?= esc($m['dob'] ?? '—') ?></td>
                                    <td><?= esc($m['relationship'] ?? '—') ?></td>
                                    <td><?= esc($m['occupation'] ?? '—') ?></td>
                                    <td><?= $m['annual_income'] !== null ? number_format((float) $m['annual_income'], 2) : '—' ?></td>
                                    <td><?= esc($m['tin_number'] ?? '—') ?></td>
                                    <td><?= esc($m['phone'] ?? '—') ?></td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end::Household-->

            <!--begin::Transport -->
            <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header border-0 pt-6">
                    <div class="card-title"><h3 class="fw-bold text-gray-900 fs-4">Section C: Transport Information</h3></div>
                </div>
                <div class="card-body pt-0">
                    <?php
                        $tripDirections = [
                            'To School' => ['label' => 'From Home to School', 'destKey' => 'to_school_final_destination', 'fareKey' => 'to_school_total_fare'],
                            'To Home'   => ['label' => 'From School to Home', 'destKey' => 'to_home_final_destination', 'fareKey' => 'to_home_total_fare'],
                        ];
                    ?>
                    <?php foreach ($tripDirections as $direction => $dir):
                        $directionTrips = array_values(array_filter($trips, fn($t) => $t['direction'] === $direction));
                    ?>
                    <h6 class="fw-bold text-gray-700 mb-3"><?= esc($dir['label']) ?></h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2">
                            <thead>
                                <tr class="fw-bold text-muted fs-8 text-uppercase">
                                    <th class="w-60px">Trip</th><th>Boarding Point</th><th>Fare</th><th>Mode</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 0; $i < 3; $i++): $trip = $directionTrips[$i] ?? null; ?>
                                <tr class="fs-7">
                                    <td class="text-muted">Trip <?= $i + 1 ?></td>
                                    <td><?= esc($trip['boarding_point'] ?? '—') ?></td>
                                    <td><?= $trip && $trip['fare'] !== null ? number_format((float) $trip['fare'], 2) : '—' ?></td>
                                    <td><?= esc($trip['mode'] ?? '—') ?></td>
                                </tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="fs-7 mb-6">
                        <span class="fw-semibold text-muted">Final Destination:</span> <?= esc($allocation[$dir['destKey']] ?? '—') ?>
                        &nbsp;&bull;&nbsp;
                        <span class="fw-semibold text-muted">Total Fare:</span> <?= $allocation[$dir['fareKey']] !== null ? number_format((float) $allocation[$dir['fareKey']], 2) : '—' ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!--end::Transport-->

        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header border-0 pt-6">
                    <div class="card-title"><h3 class="fw-bold text-gray-900 fs-5">Office Use</h3></div>
                </div>
                <div class="card-body pt-0 fs-7">
                    <div class="mb-4">
                        <span class="fw-semibold text-muted d-block">Vetted By</span>
                        <?= esc($allocation['vetted_by_name'] ?? '—') ?>
                        <?php if (!empty($allocation['vetted_date'])): ?> (<?= esc($allocation['vetted_date']) ?>)<?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <span class="fw-semibold text-muted d-block">Principal Signed</span>
                        <?= esc($allocation['principal_signed_date'] ?? '—') ?>
                    </div>
                    <div class="mb-4">
                        <span class="fw-semibold text-muted d-block">FEMIS Entry</span>
                        <?= esc($allocation['femis_entry_by'] ?? '—') ?>
                        <?php if (!empty($allocation['femis_entry_date'])): ?> (<?= esc($allocation['femis_entry_date']) ?>)<?php endif; ?>
                    </div>
                    <div class="text-muted fs-8 pt-2 border-top">
                        Submitted <?= !empty($allocation['created_at']) ? date('d M Y', strtotime($allocation['created_at'])) : '—' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script>
"use strict";
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Application?',
        text: 'This transport allocation and its household/trip details will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
    }).then(result => {
        if (!result.isConfirmed) return;
        $.post('<?= base_url('transportation/remove/') ?>' + id, { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
            .done(function (d) {
                if (d.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted', text: 'Application removed.', timer: 1500, showConfirmButton: false });
                    setTimeout(() => window.location.href = '<?= base_url('transportation') ?>', 1600);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: d.message || 'Failed to delete.' });
                }
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' });
            });
    });
}
</script>
