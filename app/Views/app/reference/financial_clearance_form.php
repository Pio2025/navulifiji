<?php
$formConfig = [
    'title' => 'Financial Clearance',
    'tips'  => [
        'Tick all financial items that have been cleared.',
        'At least one item must be cleared.',
        'Bursar or Principal signature is required.',
        'Period covered is required for record accuracy.',
    ],
];
?>
<?= $this->include('app/reference/partials/form_layout') ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <div class="col-lg-8">
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">Financial Clearance —
                    <span class="text-primary"><?= esc($user['fname'] . ' ' . $user['lname']) ?></span>
                </h3>
            </div>
        </div>
        <div class="card-body">
        <form id="ref_form">
            <input type="hidden" name="force_new" id="force_new" value="0" />

            <!--begin::Period-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Period Covered
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Year / Period</label>
                        <input type="text" class="form-control form-control-sm"
                               name="period_covered" placeholder="e.g. 2025, Term 1-3 2025"
                               value="<?= date('Y') ?>"
                               data-required data-label="Period Covered" />
                    </div>
                </div>
            </div>
            <!--end::Period-->

            <!--begin::Financial Items-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-1 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Financial Clearance Items
                    <span class="text-danger ms-1">*</span>
                    <span class="text-muted fw-normal fs-8 ms-2">(Select all that are cleared)</span>
                </h6>
                <div class="row g-3 mt-1">
                    <?php
                    $finItems = [
                        'School Fees',
                        'Canteen / Tuck Shop',
                        'Excursion Fees',
                        'Library Fines',
                        'Sports Fees',
                        'Uniform Fees',
                    ];
                    foreach ($finItems as $item):
                    ?>
                    <div class="col-md-6">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input financial-check"
                                   type="checkbox" name="clearance_items[]"
                                   value="<?= esc($item) ?>" id="fi_<?= md5($item) ?>" />
                            <label class="form-check-label fs-7 fw-semibold" for="fi_<?= md5($item) ?>">
                                <?= esc($item) ?>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div id="financial_error" class="text-danger fs-8 mt-2" style="display:none;">
                    Please select at least one cleared financial item.
                </div>
            </div>
            <!--end::Financial Items-->

            <!--begin::Outstanding-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Outstanding Amounts
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm" name="outstanding" rows="2"
                          placeholder="e.g. none / $20 canteen balance outstanding"></textarea>
            </div>
            <!--end::Outstanding-->

            <!--begin::Notes-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Additional Notes
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm" name="additional_notes" rows="2"
                          placeholder="Any additional financial clearance notes..."></textarea>
            </div>
            <!--end::Notes-->

            <!--begin::Signatory-->
            <div class="mb-5">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Signatory Details
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Signatory Full Name</label>
                        <input type="text" class="form-control form-control-sm"
                               name="signatory_name" placeholder="Full name"
                               data-required data-label="Signatory Name" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Signatory Title</label>
                        <select class="form-select form-select-sm" name="signatory_title"
                                data-required data-label="Signatory Title">
                            <option value="">Select...</option>
                            <option>School Bursar</option>
                            <option>Finance Officer</option>
                            <option>Principal</option>
                            <option>Head Teacher</option>
                            <option>Vice Principal</option>
                            <option>Assitant Head Teacher</option>
                            <option>Assistant Principal</option>
                            <option>Class Teacher</option>
                            <option>HOD</option>
                            <option>School Admin</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--end::Signatory-->

        </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-3 py-4">
            <a href="<?= base_url('user/detail/' . $userID) ?>" class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_generate_ref">
                <span class="indicator-label">
                    <i class="ki-duotone ki-document fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>Generate & Save PDF
                </span>
                <span class="indicator-progress">
                    Generating... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
    </div>

    <div class="col-lg-4">
        <?= $this->include('app/reference/partials/student_sidebar') ?>
    </div>

</div>
</div>
</div>

<?= $this->include('app/reference/partials/generate_js') ?>
<script>
document.getElementById('btn_generate_ref').addEventListener('click', function() {
    const checked = document.querySelectorAll('.financial-check:checked');
    const errDiv  = document.getElementById('financial_error');
    if (checked.length === 0) { errDiv.style.display = 'block'; return false; }
    errDiv.style.display = 'none';
}, true);

handleGenerateReference(
    'ref_form',
    '<?= base_url('reference/generate-financial-clearance/' . $userID) ?>',
    'btn_generate_ref'
);
</script>