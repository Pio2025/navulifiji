<?= $this->include('app/reference/partials/form_layout') ?>

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <div class="col-lg-8">
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">
                    Transcript Request —
                    <span class="text-primary"><?= esc($user['fname'] . ' ' . $user['lname']) ?></span>
                </h3>
            </div>
        </div>
        <div class="card-body">
        <form id="ref_form">
            <input type="hidden" name="force_new" id="force_new" value="0" />

            <!--begin::Recipient-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Recipient Details
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">Title</label>
                        <select class="form-select form-select-sm" name="recipient_title">
                            <option value="">Select...</option>
                            <option>Mr.</option><option>Mrs.</option>
                            <option>Ms.</option><option>Dr.</option><option>Prof.</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold fs-7">Recipient Name</label>
                        <input type="text" class="form-control form-control-sm"
                               name="recipient_name"
                               placeholder="To Whom It May Concern" />
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">Organisation / Institution</label>
                        <input type="text" class="form-control form-control-sm"
                               name="recipient_org"
                               placeholder="e.g. University of the South Pacific" />
                    </div>
                </div>
            </div>
            <!--end::Recipient-->

            <!--begin::Purpose-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Purpose <span class="text-danger">*</span>
                </h6>
                <label class="form-label fw-semibold fs-7 required">
                    Purpose of Transcript Request
                </label>
                <input type="text" class="form-control form-control-sm"
                       name="purpose"
                       placeholder="e.g. university admission, scholarship, employment"
                       data-required data-label="Purpose" />
            </div>
            <!--end::Purpose-->

            <!--begin::Academic Years-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-1 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Academic Years to Include
                    <span class="text-danger ms-1">*</span>
                    <span class="text-muted fw-normal fs-8 ms-2">(Select at least 1)</span>
                </h6>
                <div class="row g-3 mt-1">
                    <?php
                    // Show last 6 years
                    $currentYear = (int) date('Y');
                    for ($yr = $currentYear; $yr >= $currentYear - 5; $yr--):
                        // Pre-check current enrollment year
                        $checked = (!empty($enrollment['enrol_year']) && (int)$enrollment['enrol_year'] === $yr)
                            ? 'checked' : '';
                    ?>
                    <div class="col-md-4">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input year-check"
                                   type="checkbox"
                                   name="years_included[]"
                                   value="<?= $yr ?>"
                                   id="yr_<?= $yr ?>" <?= $checked ?> />
                            <label class="form-check-label fs-7 fw-semibold"
                                   for="yr_<?= $yr ?>">
                                Year <?= $yr ?>
                                <?php if (!empty($enrollment['enrol_year']) && (int)$enrollment['enrol_year'] === $yr): ?>
                                    <span class="badge badge-light-primary fs-8 ms-1">Current</span>
                                <?php endif; ?>
                            </label>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
                <div id="years_error" class="text-danger fs-8 mt-2" style="display:none;">
                    Please select at least one academic year.
                </div>
            </div>
            <!--end::Academic Years-->

            <!--begin::Conduct Note-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Conduct & Behaviour Note
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm"
                          name="conduct_note" rows="2"
                          placeholder="e.g. Student demonstrated excellent behaviour throughout the academic year..."></textarea>
            </div>
            <!--end::Conduct Note-->

            <!--begin::Additional Notes-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Additional Notes
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm"
                          name="additional_notes" rows="2"
                          placeholder="Any additional information to include..."></textarea>
            </div>
            <!--end::Additional Notes-->

            <!--begin::Signatory-->
            <div class="mb-5">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Signatory Details
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Signatory Full Name</label>
                        <input type="text" class="form-control form-control-sm"
                               name="signatory_name"
                               placeholder="Full name of signing officer"
                               data-required data-label="Signatory Name" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Signatory Title</label>
                        <select class="form-select form-select-sm" name="signatory_title"
                                data-required data-label="Signatory Title">
                            <option value="">Select...</option>
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
            <a href="<?= base_url('user/detail/' . $userID) ?>"
               class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_generate_ref">
                <span class="indicator-label">
                    <i class="ki-duotone ki-document fs-4 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Generate & Save PDF
                </span>
                <span class="indicator-progress">
                    Generating...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
    const checked = document.querySelectorAll('.year-check:checked');
    const errDiv  = document.getElementById('years_error');
    if (checked.length === 0) {
        errDiv.style.display = 'block';
        return false;
    }
    errDiv.style.display = 'none';
}, true);

handleGenerateReference(
    'ref_form',
    '<?= base_url('reference/generate-transcript/' . $userID) ?>',
    'btn_generate_ref'
);
</script>