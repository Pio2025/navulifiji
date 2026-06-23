<?php
$formConfig = [
    'title' => 'Parent Involvement Certificate',
    'tips'  => [
        'Select all areas where the parent has been actively involved.',
        'At least 2 involvement areas must be selected.',
        'Child name and years involved are required.',
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
                <h3 class="fw-bold text-gray-900 fs-4">Parent Involvement Certificate —
                    <span class="text-primary"><?= esc($user['fname'] . ' ' . $user['lname']) ?></span>
                </h3>
            </div>
        </div>
        <div class="card-body">
        <form id="ref_form">
            <input type="hidden" name="force_new" id="force_new" value="0" />

            <!--begin::Basic Info-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Basic Information
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Child / Student Name</label>
                        <input type="text" class="form-control form-control-sm"
                               name="child_name" placeholder="Full name of child"
                               data-required data-label="Child Name" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Years / Duration Involved</label>
                        <input type="text" class="form-control form-control-sm"
                               name="years_involved" placeholder="e.g. 3 years, since 2022"
                               data-required data-label="Years Involved" />
                    </div>
                </div>
            </div>
            <!--end::Basic Info-->

            <!--begin::Involvement Areas-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-1 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Areas of Involvement
                    <span class="text-danger ms-1">*</span>
                    <span class="text-muted fw-normal fs-8 ms-2">(Select at least 2)</span>
                </h6>
                <div class="row g-3 mt-1">
                    <?php
                    $involvements = [
                        'School Board / Committee Member',
                        'Parent-Teacher Association (PTA)',
                        'School Events & Functions',
                        'Classroom Volunteer',
                        'School Fundraising Activities',
                        'Sports Day & Excursions',
                        'Cultural & Traditional Activities',
                        'Reading & Literacy Programs',
                        'School Canteen Support',
                        'School Maintenance / Working Bees',
                    ];
                    foreach ($involvements as $inv):
                    ?>
                    <div class="col-md-6">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input involvement-check"
                                   type="checkbox" name="involvement_items[]"
                                   value="<?= esc($inv) ?>" id="inv_<?= md5($inv) ?>" />
                            <label class="form-check-label fs-7" for="inv_<?= md5($inv) ?>">
                                <?= esc($inv) ?>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div id="involvement_error" class="text-danger fs-8 mt-2" style="display:none;">
                    Please select at least 2 areas of involvement.
                </div>
            </div>
            <!--end::Involvement Areas-->

            <!--begin::Notes-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Additional Notes
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm" name="additional_notes" rows="3"
                          placeholder="Specific contributions, leadership roles, notable involvement..."></textarea>
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
document.getElementById('btn_generate_ref').addEventListener('click', function() {
    const checked = document.querySelectorAll('.involvement-check:checked');
    const errDiv  = document.getElementById('involvement_error');
    if (checked.length < 2) { errDiv.style.display = 'block'; return false; }
    errDiv.style.display = 'none';
}, true);

handleGenerateReference(
    'ref_form',
    '<?= base_url('reference/generate-parent-involvement/' . $userID) ?>',
    'btn_generate_ref'
);
</script>