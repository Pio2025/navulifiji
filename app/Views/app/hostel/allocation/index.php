<?php
$occupied  = $occupied  ?? [];
$rooms     = $rooms     ?? [];
$boarders  = $boarders  ?? [];
$canDetail = $canDetail ?? false;
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Room Allocation</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('hostel') ?>" class="text-muted text-hover-primary">Hostel</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Room Allocation</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <!--begin::Allocate form-->
        <div class="card mb-6">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold text-gray-900 fs-5">Allocate a Room</h3>
                </div>
            </div>
            <div class="card-body pt-4">
                <form id="allocate_form">
                    <?= csrf_field() ?>
                    <div class="row g-5 align-items-end">
                        <div class="col-lg-4">
                            <label class="form-label required fw-semibold">Room</label>
                            <select id="room_select" name="room_id" class="form-select">
                                <option value="">— Select room —</option>
                                <?php foreach ($rooms as $r): ?>
                                <option value="<?= (int) $r['room_id'] ?>">
                                    <?= esc($r['hostel_name']) ?> — <?= esc($r['room_number']) ?>
                                    (<?= (int) $r['occupied_count'] ?>/<?= (int) $r['capacity'] ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label required fw-semibold">Boarder</label>
                            <select id="boarder_select" name="boarder_admission_id" class="form-select">
                                <option value="">— Select boarder —</option>
                                <?php foreach ($boarders as $b): ?>
                                <option value="<?= (int) $b['admission_id'] ?>">
                                    <?= esc(trim($b['fname'] . ' ' . $b['lname'])) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold">Fee Amount</label>
                            <input type="number" step="0.01" min="0" id="fee_amount" name="fee_amount" class="form-control" placeholder="0.00" />
                        </div>
                        <div class="col-lg-2">
                            <button type="button" id="btn_allocate" class="btn btn-primary w-100">
                                <i class="ki-duotone ki-arrow-right-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                                Allocate
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Allocate form-->

        <!--begin::Currently allocated-->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold text-gray-900 fs-5">Currently Allocated (<?= count($occupied) ?>)</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($occupied)): ?>
                <div class="text-center py-16">
                    <i class="ki-duotone ki-home-3 fs-4x text-gray-200 mb-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="fs-6 fw-semibold text-gray-600">No boarders are currently allocated.</div>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 bg-light">
                                <th class="ps-4">Boarder</th>
                                <th>Hostel / Room</th>
                                <th>Check-In</th>
                                <th class="text-center">Fee</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($occupied as $row): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold"><?= esc(trim(($row['borrower_fname'] ?? '') . ' ' . ($row['borrower_lname'] ?? ''))) ?></span>
                                <div class="text-muted fs-8"><?= esc($row['borrower_role_cat'] ?? '') ?></div>
                            </td>
                            <td>
                                <span class="fw-semibold"><?= esc($row['hostel_name']) ?></span>
                                <div class="text-muted fs-8">Room <?= esc($row['room_number']) ?></div>
                            </td>
                            <td><?= esc($row['check_in_date']) ?></td>
                            <td class="text-center">
                                <?= (float) ($row['fee_amount'] ?? 0) > 0 ? number_format((float) $row['fee_amount'], 2) : '—' ?>
                                <?php if ((int) ($row['fee_paid'] ?? 0) === 1 && (float) ($row['fee_amount'] ?? 0) > 0): ?>
                                    <span class="badge badge-light-success fs-9 ms-1">Paid</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <?php if ($canDetail): ?>
                                    <a href="<?= base_url('hostel/allocation/detail/' . (int) $row['allocation_id']) ?>"
                                       class="btn btn-sm btn-icon btn-light-info" title="View">
                                        <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-light-danger"
                                        onclick="openVacateModal(<?= (int) $row['allocation_id'] ?>, '<?= esc(trim($row['borrower_fname'] . ' ' . $row['borrower_lname']), 'js') ?>')">
                                        Vacate
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Currently allocated-->

    </div>
</div>

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
"use strict";

$('#room_select').select2({ placeholder: '— Select room —', width: '100%' });
$('#boarder_select').select2({ placeholder: '— Select boarder —', width: '100%' });

document.getElementById('btn_allocate').addEventListener('click', function () {
    var btn = this;
    var roomId    = document.getElementById('room_select').value;
    var boarderId = document.getElementById('boarder_select').value;

    if (!roomId || !boarderId) {
        Swal.fire({ title: 'Missing information', text: 'Please select both a room and a boarder.', icon: 'warning' });
        return;
    }

    var formData = new FormData(document.getElementById('allocate_form'));

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url: '<?= base_url('hostel/allocation/store') ?>', type: 'POST', data: formData, processData: false, contentType: false,
        success: function (response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Allocated!', text: response.message, icon: 'success', timer: 1800, showConfirmButton: false })
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

function openVacateModal(allocationId, name) {
    document.getElementById('vacateBoarderName').textContent = name;
    document.getElementById('vacateForm').action = '<?= base_url('hostel/allocation/vacate/') ?>' + allocationId;
    var modal = new bootstrap.Modal(document.getElementById('vacateModal'));
    modal.show();
}
</script>
