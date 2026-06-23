<?php
$formConfig = [
    'title' => 'Character Reference',
    'tips'  => [
        'Select at least 3 character qualities for a meaningful reference.',
        'Signatory name and title are required to produce a valid document.',
        'Leave recipient blank to use "To Whom It May Concern".',
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
                <h3 class="fw-bold text-gray-900 fs-4">Character Reference —
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
                        <label class="form-label fw-semibold fs-7">Recipient Title</label>
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
                        <label class="form-label fw-semibold fs-7">Organisation / Institution</label>
                        <input type="text" class="form-control form-control-sm"
                               name="recipient_org" placeholder="e.g. University of the South Pacific" />
                    </div>
                </div>
            </div>
            <!--end::Recipient-->

            <!--begin::Context-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Reference Context
                </h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Relationship to Student</label>
                        <select class="form-select form-select-sm" name="relationship"
                                data-required data-label="Relationship">
                            <option value="">Select...</option>
                            <option value="student">Student</option>
                            <option value="former student">Former Student</option>
                            <option value="class member">Class Member</option>
                            <option value="school member">School Member</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Known Duration</label>
                        <input type="text" class="form-control form-control-sm"
                               name="known_duration" placeholder="e.g. 3 years, 2 terms"
                               data-required data-label="Known Duration" />
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold fs-7 required">Purpose of Reference</label>
                        <input type="text" class="form-control form-control-sm"
                               name="purpose" placeholder="e.g. university application, scholarship"
                               data-required data-label="Purpose" />
                    </div>
                </div>
            </div>
            <!--end::Context-->

            <!--begin::Qualities-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-1 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Character Qualities
                    <span class="text-danger ms-1">*</span>
                    <span class="text-muted fw-normal fs-8 ms-2">(Select at least 3)</span>
                </h6>
                <div class="mb-3"
                     data-required-group="qualities[]"
                     data-group-label="Character Qualities (minimum 3)"></div>
                <div class="row g-3">
                    <?php
                    $qualities = [
                        'responsible and trustworthy', 'hardworking and dedicated',
                        'respectful and courteous',    'honest and integrity-driven',
                        'a natural leader',            'an excellent team player',
                        'self-motivated and disciplined', 'creative and innovative',
                        'a strong communicator',       'academically diligent',
                        'empathetic and compassionate','resilient and adaptable',
                    ];
                    foreach ($qualities as $q):
                    ?>
                    <div class="col-md-6">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input quality-check"
                                   type="checkbox" name="qualities[]"
                                   value="<?= esc($q) ?>" id="q_<?= md5($q) ?>" />
                            <label class="form-check-label fs-7 fw-semibold" for="q_<?= md5($q) ?>">
                                <?= ucfirst(esc($q)) ?>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div id="qualities_error" class="text-danger fs-8 mt-2" style="display:none;">
                    Please select at least 3 character qualities.
                </div>
            </div>
            <!--end::Qualities-->

            <!--begin::Additional Notes-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Additional Notes
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm" name="additional_notes" rows="3"
                          placeholder="Specific achievements, awards, or contributions..."></textarea>
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
                               name="signatory_name" placeholder="Full name of signing officer"
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
// Extra validation for minimum 3 qualities
document.getElementById('btn_generate_ref').addEventListener('click', function() {
    const checked = document.querySelectorAll('.quality-check:checked');
    const errDiv  = document.getElementById('qualities_error');
    if (checked.length < 3) {
        errDiv.style.display = 'block';
        return false;
    }
    errDiv.style.display = 'none';
}, true); // capture phase so it runs before handleGenerateReference

handleGenerateReference(
    'ref_form',
    '<?= base_url('reference/generate-character-reference/' . $userID) ?>',
    'btn_generate_ref'
);
</script>