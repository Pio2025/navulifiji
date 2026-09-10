<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isEdit ? 'Edit Application' : 'New Application' ?>
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
                <li class="breadcrumb-item text-muted"><?= $isEdit ? 'Edit' : 'Add' ?></li>
            </ul>
        </div>
        <a href="<?= base_url('transportation') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <form id="transport_form">

    <!--begin::Section A - Student -->
    <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">Section A: Student Information</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="form-label fw-semibold fs-7 required">Student</label>
                    <select class="form-select form-select-sm" name="student_id" id="student_select" required>
                        <option value="">— Select student —</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?= $s['admission_id'] ?>"
                                data-dob="<?= esc($s['dob'] ?? '') ?>"
                                data-address="<?= esc($s['address'] ?? '') ?>"
                                data-phone="<?= esc($s['phone'] ?? '') ?>"
                                data-school="<?= esc($s['sch_name'] ?? '') ?>"
                                <?= (!empty($allocation) && (int) $allocation['student_id'] === (int) $s['admission_id']) ? 'selected' : '' ?>>
                            <?= esc(trim($s['fname'] . ' ' . ($s['oname'] ? $s['oname'] . ' ' : '') . $s['lname'])) ?>
                            <?php if ($isSuperAdmin): ?>(<?= esc($s['sch_name'] ?? '') ?>)<?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold fs-7 required">Academic Year</label>
                    <input type="number" class="form-control form-control-sm" name="academic_year"
                           value="<?= esc($allocation['academic_year'] ?? date('Y')) ?>" min="2020" max="2100" required />
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold fs-7">E-Transport Card Number</label>
                    <input type="text" class="form-control form-control-sm" name="e_transport_card_number"
                           value="<?= esc($allocation['e_transport_card_number'] ?? '') ?>"
                           placeholder="Vodafone e-ticketing card no." />
                </div>
            </div>

            <div id="autofill_panel" class="alert alert-light-primary d-flex flex-column gap-1 fs-7 mb-0" style="<?= !empty($allocation) ? '' : 'display:none;' ?>">
                <div class="fw-bold text-primary mb-1">Auto-filled from student record</div>
                <div><span class="fw-semibold">DOB:</span> <span id="af_dob"><?= esc($allocation['dob'] ?? '') ?></span></div>
                <div><span class="fw-semibold">Address:</span> <span id="af_address"><?= esc($allocation['address'] ?? '') ?></span></div>
                <div><span class="fw-semibold">Phone:</span> <span id="af_phone"><?= esc($allocation['phone'] ?? '') ?></span></div>
                <div><span class="fw-semibold">School:</span> <span id="af_school"><?= esc($allocation['sch_name'] ?? '') ?></span>
                    <?php if (!empty($allocation['stream_name'])): ?>
                    &bull; <?= esc($allocation['level_name'] ?? '') ?> <?= esc($allocation['stream_name']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!--end::Section A-->

    <!--begin::Section B - Household -->
    <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">Section B: Household Information</h3>
            </div>
        </div>
        <div class="card-body">

            <div class="mb-5 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="receiving_social_welfare" id="receiving_social_welfare" value="1"
                       <?= !empty($allocation['receiving_social_welfare']) ? 'checked' : '' ?>>
                <label class="form-check-label fs-7 fw-semibold" for="receiving_social_welfare">
                    Family is currently receiving Social Welfare assistance
                </label>
            </div>
            <div class="mb-5" id="social_welfare_number_wrap" style="<?= !empty($allocation['receiving_social_welfare']) ? '' : 'display:none;' ?>">
                <label class="form-label fw-semibold fs-7">Social Welfare Number</label>
                <input type="text" class="form-control form-control-sm" name="social_welfare_number"
                       value="<?= esc($allocation['social_welfare_number'] ?? '') ?>" style="max-width:260px;" />
            </div>

            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2" id="household_table">
                    <thead>
                        <tr class="fw-bold text-muted fs-8 text-uppercase">
                            <th class="min-w-150px">Full Name</th>
                            <th class="min-w-120px">DOB</th>
                            <th class="min-w-120px">Relationship</th>
                            <th class="min-w-150px">Occupation</th>
                            <th class="min-w-120px">Annual Income</th>
                            <th class="min-w-120px">TIN Number</th>
                            <th class="min-w-100px">Phone</th>
                            <th class="w-40px"></th>
                        </tr>
                    </thead>
                    <tbody id="household_rows"></tbody>
                </table>
            </div>
            <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_household_row">
                <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Add Household Member
            </button>
        </div>
    </div>
    <!--end::Section B-->

    <!--begin::Section C - Transport -->
    <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">Section C: Transport Information</h3>
            </div>
        </div>
        <div class="card-body">

            <?php
                $allTrips = $trips ?? [];
                $tripDirections = [
                    'to_school' => ['label' => 'From Home to School', 'trips' => array_values(array_filter($allTrips, fn($t) => $t['direction'] === 'To School')), 'destKey' => 'to_school_final_destination', 'fareKey' => 'to_school_total_fare'],
                    'to_home'   => ['label' => 'From School to Home', 'trips' => array_values(array_filter($allTrips, fn($t) => $t['direction'] === 'To Home')), 'destKey' => 'to_home_final_destination', 'fareKey' => 'to_home_total_fare'],
                ];
            ?>
            <?php foreach ($tripDirections as $dirKey => $dir): ?>
            <h6 class="fw-bold text-gray-700 mb-3"><?= esc($dir['label']) ?></h6>
            <div class="table-responsive mb-3">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2">
                    <thead>
                        <tr class="fw-bold text-muted fs-8 text-uppercase">
                            <th class="w-60px">Trip</th>
                            <th class="min-w-200px">Boarding Point</th>
                            <th class="min-w-120px">Fare</th>
                            <th class="min-w-120px">Mode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 3; $i++): $trip = $dir['trips'][$i] ?? null; ?>
                        <tr>
                            <td class="text-muted fs-7">Trip <?= $i + 1 ?></td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="trips_<?= $dirKey ?>[<?= $i ?>][boarding_point]"
                                       value="<?= esc($trip['boarding_point'] ?? '') ?>" />
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="trips_<?= $dirKey ?>[<?= $i ?>][fare]"
                                       value="<?= esc($trip['fare'] ?? '') ?>" />
                            </td>
                            <td>
                                <select class="form-select form-select-sm" name="trips_<?= $dirKey ?>[<?= $i ?>][mode]">
                                    <option value="">—</option>
                                    <?php foreach (['Bus', 'Boat', 'Carrier', 'Minibus'] as $mode): ?>
                                    <option value="<?= $mode ?>" <?= (($trip['mode'] ?? '') === $mode) ? 'selected' : '' ?>><?= $mode ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
            <div class="row g-4 mb-6">
                <div class="col-md-8">
                    <label class="form-label fw-semibold fs-7">Final Destination</label>
                    <input type="text" class="form-control form-control-sm" name="<?= $dir['destKey'] ?>"
                           value="<?= esc($allocation[$dir['destKey']] ?? '') ?>" />
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold fs-7">Total Fare</label>
                    <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="<?= $dir['fareKey'] ?>"
                           value="<?= esc($allocation[$dir['fareKey']] ?? '') ?>" />
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>
    <!--end::Section C-->

    </form>

    <div class="d-flex justify-content-end gap-3 mb-10">
        <a href="<?= base_url('transportation') ?>" class="btn btn-light btn-sm">Cancel</a>
        <button type="button" class="btn btn-primary btn-sm" id="btn_save_transport">
            <span class="indicator-label">
                <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                <?= $isEdit ? 'Update Application' : 'Save Application' ?>
            </span>
            <span class="indicator-progress">
                Saving...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>

</div>
</div>

<script>
"use strict";

$('#student_select').select2({ placeholder: '— Select student —', width: '100%' });

$('#student_select').on('change', function() {
    const opt = this.options[this.selectedIndex];
    const panel = document.getElementById('autofill_panel');
    if (!opt || !opt.value) { panel.style.display = 'none'; return; }
    document.getElementById('af_dob').textContent     = opt.dataset.dob || '—';
    document.getElementById('af_address').textContent = opt.dataset.address || '—';
    document.getElementById('af_phone').textContent    = opt.dataset.phone || '—';
    document.getElementById('af_school').textContent   = opt.dataset.school || '—';
    panel.style.display = 'block';
});

$('#receiving_social_welfare').on('change', function() {
    $('#social_welfare_number_wrap').toggle(this.checked);
});

// ── Household member rows ────────────────────────────────────────
let householdIndex = 0;

function householdRowHtml(index, member) {
    member = member || {};
    return `
        <tr id="household_row_${index}">
            <td><input type="text" class="form-control form-control-sm" name="household[${index}][member_name]" value="${member.member_name || ''}" /></td>
            <td><input type="date" class="form-control form-control-sm" name="household[${index}][dob]" value="${member.dob || ''}" /></td>
            <td><input type="text" class="form-control form-control-sm" name="household[${index}][relationship]" value="${member.relationship || ''}" /></td>
            <td><input type="text" class="form-control form-control-sm" name="household[${index}][occupation]" value="${member.occupation || ''}" /></td>
            <td><input type="number" step="0.01" min="0" class="form-control form-control-sm" name="household[${index}][annual_income]" value="${member.annual_income || ''}" /></td>
            <td><input type="text" class="form-control form-control-sm" name="household[${index}][tin_number]" value="${member.tin_number || ''}" /></td>
            <td><input type="text" class="form-control form-control-sm" name="household[${index}][phone]" value="${member.phone || ''}" /></td>
            <td class="text-end">
                <button type="button" class="btn btn-icon btn-xs btn-light-danger" onclick="removeHouseholdRow(${index})">
                    <i class="ki-duotone ki-cross fs-4"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </td>
        </tr>`;
}

function addHouseholdRow(member) {
    $('#household_rows').append(householdRowHtml(householdIndex, member));
    householdIndex++;
}

function removeHouseholdRow(index) {
    $('#household_row_' + index).remove();
}

<?php if (!empty($household)): ?>
const existingHousehold = <?= json_encode($household) ?>;
existingHousehold.forEach(function(member) { addHouseholdRow(member); });
<?php else: ?>
addHouseholdRow();
<?php endif; ?>

$('#btn_add_household_row').on('click', function() { addHouseholdRow(); });

// ── Save ──────────────────────────────────────────────────────────
document.getElementById('btn_save_transport').addEventListener('click', function() {
    const btn = this;
    const formData = new FormData(document.getElementById('transport_form'));
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    const url = <?= $isEdit
        ? "'" . base_url('transportation/update/' . ($allocation['allocation_id'] ?? 0)) . "'"
        : "'" . base_url('transportation/store') . "'"
    ?>;

    $.ajax({
        url: url, type: 'POST', data: formData, processData: false, contentType: false,
        success: function(response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Saved!', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(function() { window.location.href = response.redirect; });
            } else {
                Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
            }
        },
        error: function() {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An unexpected error occurred.', icon: 'error' });
        }
    });
});
</script>
