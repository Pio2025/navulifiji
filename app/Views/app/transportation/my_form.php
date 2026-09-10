<?php
$household = $household ?? [];
$trips     = $trips     ?? [];
$allTrips  = $trips;
?>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Transport Assistance Application &mdash; <?= $year ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('transportation/my') ?>" class="text-muted text-hover-primary">Transportation</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= $isSelf ? 'My Application' : 'Child Application' ?></li>
            </ul>
        </div>
        <a href="<?= base_url('transportation/my') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="alert alert-light-warning fs-7 mb-6">
        <strong>Note:</strong> This eligibility application is filled digitally for record-keeping, then a copy is
        generated as a PDF (mimicking the official Ministry of Education Transport Assistance Application Form)
        for the Head Teacher / Principal's physical signature and submission to the Ministry.
        Only combined household annual income of <strong>$16,000 or less</strong> is eligible, and this
        application must be renewed every academic year.
    </div>

    <form id="transport_my_form">
        <input type="hidden" name="student_user_id" value="<?= (int) $targetUserId ?>" />

        <!--begin::Section A - Student -->
        <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-6">
                <div class="card-title"><h3 class="fw-bold text-gray-900 fs-4">Section A: Student Information (auto-filled)</h3></div>
            </div>
            <div class="card-body pt-0">
                <div class="row g-4 fs-7 mb-5">
                    <div class="col-md-6"><span class="fw-semibold text-muted">Name:</span> <?= esc(trim(($admission['fname'] ?? '') . ' ' . ($admission['oname'] ?? '') . ' ' . ($admission['lname'] ?? ''))) ?></div>
                    <div class="col-md-3"><span class="fw-semibold text-muted">DOB:</span> <?= esc($admission['dob'] ?? '—') ?></div>
                    <div class="col-md-3"><span class="fw-semibold text-muted">Gender:</span> <?= esc($admission['gender'] ?? '—') ?></div>
                    <div class="col-md-6"><span class="fw-semibold text-muted">Address:</span> <?= esc($admission['address'] ?? '—') ?></div>
                    <div class="col-md-3"><span class="fw-semibold text-muted">Phone:</span> <?= esc($admission['phone'] ?? '—') ?></div>
                    <div class="col-md-6"><span class="fw-semibold text-muted">School:</span> <?= esc($admission['sch_name'] ?? '—') ?></div>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold fs-7">E-Transport Card Number</label>
                    <input type="text" class="form-control form-control-sm" name="e_transport_card_number" style="max-width:300px;"
                           value="<?= esc($allocation['e_transport_card_number'] ?? '') ?>"
                           placeholder="Vodafone e-ticketing card no. (leave blank if not yet issued)" />
                </div>
            </div>
        </div>
        <!--end::Section A-->

        <!--begin::Section B - Household -->
        <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-6">
                <div class="card-title"><h3 class="fw-bold text-gray-900 fs-4">Section B: Household Information</h3></div>
            </div>
            <div class="card-body">

                <div class="mb-5 form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="receiving_social_welfare" id="receiving_social_welfare" value="1"
                           <?= !empty($allocation['receiving_social_welfare']) ? 'checked' : '' ?>>
                    <label class="form-check-label fs-7 fw-semibold" for="receiving_social_welfare">
                        My family is currently receiving Social Welfare assistance
                    </label>
                </div>
                <div class="mb-5" id="social_welfare_number_wrap" style="<?= !empty($allocation['receiving_social_welfare']) ? '' : 'display:none;' ?>">
                    <label class="form-label fw-semibold fs-7">Social Welfare Number</label>
                    <input type="text" class="form-control form-control-sm" name="social_welfare_number"
                           value="<?= esc($allocation['social_welfare_number'] ?? '') ?>" style="max-width:260px;" />
                </div>

                <p class="text-muted fs-8 mb-3">List every member of your household (parents/guardians, siblings) and their annual income.</p>
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
                <div class="card-title"><h3 class="fw-bold text-gray-900 fs-4">Section C: Transport Information</h3></div>
            </div>
            <div class="card-body">

                <?php
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
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm trip-fare-input" data-dir="<?= $dirKey ?>" name="trips_<?= $dirKey ?>[<?= $i ?>][fare]"
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
                        <input type="number" step="0.01" min="0" class="form-control form-control-sm bg-light total-fare-input" data-dir="<?= $dirKey ?>" name="<?= $dir['fareKey'] ?>" readonly
                               value="<?= esc($allocation[$dir['fareKey']] ?? '') ?>" />
                        <div class="form-text fs-8">Automatically calculated from the 3 trip fares above.</div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
        <!--end::Section C-->

    </form>

    <div class="d-flex justify-content-end gap-3 mb-10">
        <a href="<?= base_url('transportation/my') ?>" class="btn btn-light btn-sm">Cancel</a>
        <button type="button" class="btn btn-primary btn-sm" id="btn_save_transport">
            <span class="indicator-label">
                <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Submit Application
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

$('#receiving_social_welfare').on('change', function() {
    $('#social_welfare_number_wrap').toggle(this.checked);
});

// ── Total fare auto-calculation ────────────────────────────────────
function recalcTotalFare(dirKey) {
    let total = 0;
    document.querySelectorAll('.trip-fare-input[data-dir="' + dirKey + '"]').forEach(function(input) {
        const val = parseFloat(input.value);
        if (!isNaN(val)) { total += val; }
    });
    const target = document.querySelector('.total-fare-input[data-dir="' + dirKey + '"]');
    if (target) { target.value = total > 0 ? total.toFixed(2) : ''; }
}

document.querySelectorAll('.trip-fare-input').forEach(function(input) {
    input.addEventListener('input', function() { recalcTotalFare(this.dataset.dir); });
    recalcTotalFare(input.dataset.dir);
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
    const formData = new FormData(document.getElementById('transport_my_form'));
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url: '<?= base_url('transportation/my/store') ?>', type: 'POST', data: formData, processData: false, contentType: false,
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
