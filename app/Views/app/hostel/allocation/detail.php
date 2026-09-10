<?php
$allocation     = $allocation ?? [];
$visitors       = $visitors   ?? [];
$leaves         = $leaves     ?? [];
$canVacate      = $canVacate      ?? false;
$canLogVisitor  = $canLogVisitor  ?? false;
$canManageLeave = $canManageLeave ?? false;

$leaveStatusColor = ['Pending' => 'warning', 'Approved' => 'success', 'Rejected' => 'danger'];
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc(trim(($allocation['borrower_fname'] ?? '') . ' ' . ($allocation['borrower_lname'] ?? ''))) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('hostel/allocation') ?>" class="text-muted text-hover-primary">Room Allocation</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <a href="<?= base_url('hostel/allocation') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to Allocation
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <!--begin::Allocation summary-->
        <div class="card mb-6">
            <div class="card-body">
                <div class="row g-5">
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Hostel / Room</div>
                        <div class="fw-semibold fs-6"><?= esc($allocation['hostel_name']) ?> — <?= esc($allocation['room_number']) ?></div>
                    </div>
                    <div class="col-lg-2">
                        <div class="text-muted fs-8">Status</div>
                        <div class="fw-semibold fs-6">
                            <span class="badge badge-light-<?= $allocation['status'] === 'Active' ? 'success' : 'secondary' ?>"><?= esc($allocation['status']) ?></span>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="text-muted fs-8">Check-In</div>
                        <div class="fw-semibold fs-6"><?= esc($allocation['check_in_date']) ?></div>
                    </div>
                    <div class="col-lg-2">
                        <div class="text-muted fs-8">Check-Out</div>
                        <div class="fw-semibold fs-6"><?= esc($allocation['check_out_date'] ?? '—') ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Boarding Fee</div>
                        <div class="fw-semibold fs-6">
                            <?= (float) ($allocation['fee_amount'] ?? 0) > 0 ? number_format((float) $allocation['fee_amount'], 2) : '—' ?>
                            <?php if ((int) ($allocation['fee_paid'] ?? 0) === 1 && (float) ($allocation['fee_amount'] ?? 0) > 0): ?>
                                <span class="badge badge-light-success fs-9 ms-1">Paid</span>
                            <?php elseif ((float) ($allocation['fee_amount'] ?? 0) > 0): ?>
                                <span class="badge badge-light-warning fs-9 ms-1">Unpaid</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($allocation['remarks'])): ?>
                    <div class="col-lg-12">
                        <div class="text-muted fs-8">Remarks</div>
                        <div class="fs-6 text-gray-700"><?= esc($allocation['remarks']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ($canVacate && $allocation['status'] === 'Active'): ?>
                <div class="mt-5">
                    <button type="button" class="btn btn-sm btn-light-danger"
                        onclick="openVacateModal(<?= (int) $allocation['allocation_id'] ?>, '<?= esc(trim($allocation['borrower_fname'] . ' ' . $allocation['borrower_lname']), 'js') ?>')">
                        Vacate Room
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Allocation summary-->

        <div class="row g-6">
            <!--begin::Visitor log-->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header border-0 pt-6">
                        <h3 class="fw-bold text-gray-900 fs-5">Visitor Log</h3>
                    </div>
                    <div class="card-body pt-0">
                        <?php if ($canLogVisitor): ?>
                        <form method="POST" action="<?= base_url('hostel/allocation/visitor/store/' . (int) $allocation['allocation_id']) ?>" class="mb-5">
                            <?= csrf_field() ?>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="text" name="visitor_name" class="form-control form-control-sm" placeholder="Visitor name" required />
                                </div>
                                <div class="col-6">
                                    <input type="text" name="relationship" class="form-control form-control-sm" placeholder="Relationship" />
                                </div>
                                <div class="col-4">
                                    <input type="date" name="visit_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required />
                                </div>
                                <div class="col-4">
                                    <input type="time" name="time_in" class="form-control form-control-sm" />
                                </div>
                                <div class="col-4">
                                    <input type="time" name="time_out" class="form-control form-control-sm" />
                                </div>
                                <div class="col-8">
                                    <input type="text" name="purpose" class="form-control form-control-sm" placeholder="Purpose of visit" />
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-sm btn-light-primary w-100">Log Visit</button>
                                </div>
                            </div>
                        </form>
                        <?php endif; ?>

                        <?php if (empty($visitors)): ?>
                        <p class="text-muted fs-7 mb-0">No visitors recorded yet.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-muted fs-8">
                                        <th>Visitor</th><th>Date</th><th>Time</th><th>Purpose</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($visitors as $v): ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold fs-7"><?= esc($v['visitor_name']) ?></span>
                                        <?php if (!empty($v['relationship'])): ?><div class="text-muted fs-9"><?= esc($v['relationship']) ?></div><?php endif; ?>
                                    </td>
                                    <td class="fs-7"><?= esc($v['visit_date']) ?></td>
                                    <td class="fs-7"><?= esc($v['time_in'] ?? '—') ?><?= !empty($v['time_out']) ? ' – ' . esc($v['time_out']) : '' ?></td>
                                    <td class="fs-7"><?= esc($v['purpose'] ?? '—') ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!--end::Visitor log-->

            <!--begin::Leave log-->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header border-0 pt-6">
                        <h3 class="fw-bold text-gray-900 fs-5">Leave Requests</h3>
                    </div>
                    <div class="card-body pt-0">
                        <?php if ($canManageLeave): ?>
                        <form method="POST" action="<?= base_url('hostel/allocation/leave/store/' . (int) $allocation['allocation_id']) ?>" class="mb-5">
                            <?= csrf_field() ?>
                            <div class="row g-3">
                                <div class="col-4">
                                    <input type="date" name="from_date" class="form-control form-control-sm" required />
                                </div>
                                <div class="col-4">
                                    <input type="date" name="to_date" class="form-control form-control-sm" required />
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-sm btn-light-primary w-100">Request Leave</button>
                                </div>
                                <div class="col-12">
                                    <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason for leave" />
                                </div>
                            </div>
                        </form>
                        <?php endif; ?>

                        <?php if (empty($leaves)): ?>
                        <p class="text-muted fs-7 mb-0">No leave requests recorded yet.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-muted fs-8">
                                        <th>From</th><th>To</th><th>Reason</th><th class="text-center">Status</th>
                                        <?php if ($canManageLeave): ?><th class="text-end">Actions</th><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($leaves as $l): ?>
                                <tr>
                                    <td class="fs-7"><?= esc($l['from_date']) ?></td>
                                    <td class="fs-7"><?= esc($l['to_date']) ?></td>
                                    <td class="fs-7"><?= esc($l['reason'] ?? '—') ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-light-<?= $leaveStatusColor[$l['status']] ?? 'secondary' ?> fs-9"><?= esc($l['status']) ?></span>
                                    </td>
                                    <?php if ($canManageLeave): ?>
                                    <td class="text-end">
                                        <?php if ($l['status'] === 'Pending'): ?>
                                        <form method="POST" action="<?= base_url('hostel/allocation/leave/decide/' . (int) $l['leave_id']) ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="decision" value="Approved" />
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-success" title="Approve">
                                                <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= base_url('hostel/allocation/leave/decide/' . (int) $l['leave_id']) ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="decision" value="Rejected" />
                                            <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Reject">
                                                <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <span class="text-muted fs-8">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!--end::Leave log-->
        </div>

    </div>
</div>

<?php if ($canVacate && $allocation['status'] === 'Active'): ?>
<!--begin::Vacate modal-->
<div class="modal fade" id="vacateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Vacate Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="vacateForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body pt-4 pb-2">
                    <p class="text-gray-700 fs-6">Vacating <strong id="vacateBoarderName"></strong> from their room.</p>
                    <label class="form-label fw-semibold">Remarks</label>
                    <input type="text" name="remarks" class="form-control" placeholder="Optional remarks" />
                </div>
                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Vacate</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Vacate modal-->

<script>
function openVacateModal(allocationId, name) {
    document.getElementById('vacateBoarderName').textContent = name;
    document.getElementById('vacateForm').action = '<?= base_url('hostel/allocation/vacate/') ?>' + allocationId;
    var modal = new bootstrap.Modal(document.getElementById('vacateModal'));
    modal.show();
}
</script>
<?php endif; ?>
