<?php
$formConfig = [
    'title' => 'Certificate of Employment',
    'tips'  => [
        'Position and date commenced are required for a valid certificate.',
        'Employment type must be selected.',
        'Signatory name and title are required.',
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
                <h3 class="fw-bold text-gray-900 fs-4">Certificate of Employment —
                    <span class="text-primary"><?= esc($user['fname'] . ' ' . $user['lname']) ?></span>
                </h3>
            </div>
        </div>
        <div class="card-body">
        <form id="ref_form">
            <input type="hidden" name="force_new" id="force_new" value="0" />

            <!--begin::Employment Details-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Employment Details
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Position / Role</label>
                        <input type="text" class="form-control form-control-sm"
                               name="position" placeholder="e.g. Class Teacher, HOD Mathematics"
                               data-required data-label="Position" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Department</label>
                        <input type="text" class="form-control form-control-sm"
                               name="department" placeholder="e.g. Mathematics, Science"
                               data-required data-label="Department" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Date Commenced</label>
                        <input type="date" class="form-control form-control-sm"
                               name="date_started"
                               data-required data-label="Date Commenced" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Employment Type</label>
                        <select class="form-select form-select-sm" name="employment_type"
                                data-required data-label="Employment Type">
                            <option value="">Select...</option>
                            <option>Full-Time</option>
                            <option>Part-Time</option>
                            <option>Contract</option>
                            <option>Casual</option>
                            <option>Volunteer</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7 required">Purpose of Certificate</label>
                        <input type="text" class="form-control form-control-sm"
                               name="purpose" placeholder="e.g. bank loan, visa application, reference"
                               data-required data-label="Purpose" />
                    </div>
                </div>
            </div>
            <!--end::Employment Details-->

            <!--begin::Notes-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Additional Notes
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm" name="additional_notes" rows="3"
                          placeholder="Any additional employment information..."></textarea>
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
handleGenerateReference(
    'ref_form',
    '<?= base_url('reference/generate-employment/' . $userID) ?>',
    'btn_generate_ref'
);
</script>