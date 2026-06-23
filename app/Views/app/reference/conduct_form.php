<?php
$formConfig = [
    'title' => 'Conduct Certificate',
    'tips'  => [
        'Select a conduct rating that accurately reflects the student behaviour.',
        'Select all observed positive behaviours.',
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
                <h3 class="fw-bold text-gray-900 fs-4">Conduct Certificate —
                    <span class="text-primary"><?= esc($user['fname'] . ' ' . $user['lname']) ?></span>
                </h3>
            </div>
        </div>
        <div class="card-body">
        <form id="ref_form">
            <input type="hidden" name="force_new" id="force_new" value="0" />

            <!--begin::Conduct Rating-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Conduct Rating <span class="text-danger">*</span>
                </h6>
                <div class="row g-3">
                    <?php
                    $ratings = [
                        'Excellent'          => 'success',
                        'Very Good'          => 'primary',
                        'Good'               => 'info',
                        'Satisfactory'       => 'warning',
                        'Needs Improvement'  => 'danger',
                    ];
                    foreach ($ratings as $rating => $color):
                    ?>
                    <div class="col-md-4">
                        <label class="d-flex flex-column align-items-center cursor-pointer">
                            <input type="radio" name="conduct_rating"
                                   value="<?= esc($rating) ?>"
                                   class="form-check-input conduct-rating-radio mb-2" />
                            <span class="badge badge-light-<?= $color ?> fs-7 fw-bold px-4 py-2 w-100 text-center">
                                <?= esc($rating) ?>
                            </span>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div id="rating_error" class="text-danger fs-8 mt-2" style="display:none;">
                    Please select a conduct rating.
                </div>
            </div>
            <!--end::Conduct Rating-->

            <!--begin::Behaviours-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-1 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Observed Behaviours
                    <span class="text-danger ms-1">*</span>
                    <span class="text-muted fw-normal fs-8 ms-2">(Select at least 2)</span>
                </h6>
                <div class="row g-3 mt-1">
                    <?php
                    $behaviours = [
                        'Respectful to teachers and peers',
                        'Punctual and reliable',
                        'Follows school rules consistently',
                        'Positive attitude toward learning',
                        'Honest and trustworthy',
                        'Shows leadership qualities',
                        'Cooperative in group settings',
                        'Responsible with school property',
                        'Participates actively in class',
                        'Handles conflict peacefully',
                    ];
                    foreach ($behaviours as $b):
                    ?>
                    <div class="col-md-6">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input behaviour-check"
                                   type="checkbox" name="behaviours[]"
                                   value="<?= esc($b) ?>" id="b_<?= md5($b) ?>" />
                            <label class="form-check-label fs-7" for="b_<?= md5($b) ?>">
                                <?= esc($b) ?>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div id="behaviour_error" class="text-danger fs-8 mt-2" style="display:none;">
                    Please select at least 2 observed behaviours.
                </div>
            </div>
            <!--end::Behaviours-->

            <!--begin::Incidents-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Disciplinary Incidents
                    <span class="text-muted fw-normal fs-8 ms-2">Optional — enter "none" if none</span>
                </h6>
                <textarea class="form-control form-control-sm" name="incidents" rows="2"
                          placeholder="e.g. none / minor verbal warning in Term 1"></textarea>
            </div>
            <!--end::Incidents-->

            <!--begin::Notes-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Additional Notes
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm" name="additional_notes" rows="2"
                          placeholder="Any other relevant conduct information..."></textarea>
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
    let valid = true;

    const rating = document.querySelector('.conduct-rating-radio:checked');
    document.getElementById('rating_error').style.display = rating ? 'none' : 'block';
    if (!rating) valid = false;

    const behaviours = document.querySelectorAll('.behaviour-check:checked');
    document.getElementById('behaviour_error').style.display = behaviours.length >= 2 ? 'none' : 'block';
    if (behaviours.length < 2) valid = false;

    if (!valid) return false;
}, true);

handleGenerateReference(
    'ref_form',
    '<?= base_url('reference/generate-conduct/' . $userID) ?>',
    'btn_generate_ref'
);
</script>