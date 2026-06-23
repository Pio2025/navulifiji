<?= $this->include('app/reference/partials/form_layout') ?>

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <!--begin::Main Card-->
    <div class="col-lg-8">
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">
                    Certificate of Enrollment —
                    <span class="text-primary"><?= esc($user['fname'] . ' ' . $user['lname']) ?></span>
                </h3>
            </div>
        </div>
        <div class="card-body">

            <!--begin::Info notice-->
            <div class="notice d-flex bg-light-info rounded border border-info border-dashed p-5 mb-7">
                <i class="ki-duotone ki-information fs-2tx text-info me-4 flex-shrink-0">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <div>
                    <h5 class="fw-bold text-gray-900 mb-1 fs-6">Auto-Generated Document</h5>
                    <p class="text-muted fs-7 mb-0">
                        This certificate is generated automatically from the student's active enrolment
                        records. No additional input is required. Review the details below before generating.
                    </p>
                </div>
            </div>
            <!--end::Info notice-->

            <!--begin::Enrollment Details-->
            <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                Enrolment Details to be Included
            </h6>

            <div style="background-color:#F9F9F9; border-radius:8px; padding:8px 20px; margin-bottom:24px;">

                <?php
                $rows = [
                    ['Student Name',     trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? ''))],
                    ['Gender',           ucfirst($enrollment['gender'] ?? 'N/A')],
                    ['Date of Birth',    !empty($enrollment['dob']) ? date('d F Y', strtotime($enrollment['dob'])) : 'N/A'],
                    ['School',           $enrollment['sch_name']     ?? 'N/A'],
                    ['School Category',  $enrollment['sch_cat_name'] ?? 'N/A'],
                    ['Year Level',       $enrollment['level_name']   ?? 'N/A'],
                    ['Stream / Class',   $enrollment['stream_name']  ?? 'N/A'],
                    ['Term',             'Term ' . ($enrollment['enrol_term'] ?? 'N/A')],
                    ['Academic Year',    $enrollment['enrol_year']   ?? 'N/A'],
                    ['Admission Date',   !empty($enrollment['admission_date']) ? date('d F Y', strtotime($enrollment['admission_date'])) : 'N/A'],
                    ['Admission Status', $enrollment['admission_status'] ?? 'N/A'],
                    ['Enrolment Status', $enrollment['enrol_status']     ?? 'N/A'],
                ];
                $last = count($rows) - 1;
                foreach ($rows as $i => $row):
                ?>
                <div class="d-flex justify-content-between align-items-center py-3"
                     style="<?= $i < $last ? 'border-bottom:1px dashed #E4E6EF;' : '' ?>">
                    <span class="text-muted fw-semibold fs-7"><?= esc($row[0]) ?></span>
                    <span class="text-gray-800 fw-bold fs-7"><?= esc($row[1]) ?></span>
                </div>
                <?php endforeach; ?>

            </div>
            <!--end::Enrollment Details-->

            <form id="ref_form">
                <input type="hidden" name="force_new" id="force_new" value="0" />
            </form>

        </div>
        <div class="card-footer d-flex justify-content-end gap-3 py-4">
            <a href="<?= base_url('user/detail/' . $userID) ?>" class="btn btn-light btn-sm">
                Cancel
            </a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_generate_ref">
                <span class="indicator-label">
                    <i class="ki-duotone ki-document fs-4 me-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Generate & Save Certificate
                </span>
                <span class="indicator-progress">
                    Generating...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
    </div>
    <!--end::Main Card-->

    <!--begin::Sidebar-->
    <div class="col-lg-4">
        <?= $this->include('app/reference/partials/student_sidebar') ?>
    </div>
    <!--end::Sidebar-->

</div>
</div>
</div>

<?= $this->include('app/reference/partials/generate_js') ?>
<script>
handleGenerateReference(
    'ref_form',
    '<?= base_url('reference/generate-enrollment/' . $userID) ?>',
    'btn_generate_ref'
);
</script>