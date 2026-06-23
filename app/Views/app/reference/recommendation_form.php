<?php
$formConfig = [
    'title' => 'Recommendation Letter',
    'tips'  => [
        'Select at least 2 academic strengths to make the letter meaningful.',
        'Include specific achievements to strengthen the recommendation.',
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
                <h3 class="fw-bold text-gray-900 fs-4">Recommendation Letter —
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
                               name="recipient_name" placeholder="To Whom It May Concern" />
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7">Organisation</label>
                        <input type="text" class="form-control form-control-sm"
                               name="recipient_org" placeholder="e.g. USP, Ministry of Education" />
                    </div>
                </div>
            </div>
            <!--end::Recipient-->

            <!--begin::Purpose-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Purpose
                </h6>
                <label class="form-label fw-semibold fs-7 required">Purpose of Recommendation</label>
                <input type="text" class="form-control form-control-sm"
                       name="purpose" placeholder="e.g. university admission, scholarship application"
                       data-required data-label="Purpose" />
            </div>
            <!--end::Purpose-->

            <!--begin::Academic Strengths-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-1 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Academic Strengths
                    <span class="text-danger ms-1">*</span>
                    <span class="text-muted fw-normal fs-8 ms-2">(Select at least 2)</span>
                </h6>
                <div class="mb-3"
                     data-required-group="academic_strengths[]"
                     data-group-label="Academic Strengths (minimum 2)"></div>
                <div class="row g-3 mt-1">
                    <?php
                    $strengths = [
                        'Mathematics', 'English & Literacy', 'Science',
                        'Social Studies', 'Critical Thinking', 'Research Skills',
                        'Leadership', 'Teamwork', 'Communication',
                        'Time Management', 'Creative Arts', 'Technology',
                    ];
                    foreach ($strengths as $s):
                    ?>
                    <div class="col-md-6">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input strength-check"
                                   type="checkbox" name="academic_strengths[]"
                                   value="<?= esc($s) ?>" id="s_<?= md5($s) ?>" />
                            <label class="form-check-label fs-7 fw-semibold" for="s_<?= md5($s) ?>">
                                <?= esc($s) ?>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div id="strengths_error" class="text-danger fs-8 mt-2" style="display:none;">
                    Please select at least 2 academic strengths.
                </div>
            </div>
            <!--end::Academic Strengths-->

            <!--begin::Achievements-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Notable Achievements
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm" name="achievements" rows="3"
                          placeholder="Awards, competitions, leadership roles, community service..."></textarea>
            </div>
            <!--end::Achievements-->

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
    const checked = document.querySelectorAll('.strength-check:checked');
    const errDiv  = document.getElementById('strengths_error');
    if (checked.length < 2) { errDiv.style.display = 'block'; return false; }
    errDiv.style.display = 'none';
}, true);

handleGenerateReference(
    'ref_form',
    '<?= base_url('reference/generate-recommendation/' . $userID) ?>',
    'btn_generate_ref'
);
</script>