<style>
.adm-detail-row { border-bottom: 1px dashed #c4c7d0; }
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Enrolment Detail</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('enrolment') ?>" class="text-muted text-hover-primary">Enrolments</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('enrolment/edit/' . $enrolment['enrol_id']) ?>" class="btn btn-sm btn-light-warning">
                <i class="ki-duotone ki-pencil fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Edit
            </a>
            <?php endif; ?>
            <?php if ($canDelete): ?>
            <button type="button" class="btn btn-sm btn-light-danger"
                    id="btn_delete_enrolment"
                    data-id="<?= $enrolment['enrol_id'] ?>"
                    data-name="<?= esc(trim($enrolment['fname'] . ' ' . $enrolment['lname'])) ?>">
                <i class="ki-duotone ki-trash fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                </i>
                Delete
            </button>
            <?php endif; ?>
            <a href="<?= base_url('enrolment') ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Back
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <!--begin::Left-->
    <div class="col-lg-4">

        <!--begin::Student Card-->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body text-center p-8">
                <div class="symbol symbol-80px symbol-circle mb-5 mx-auto">
                    <?php if (!empty($enrolment['profile_photo'])): ?>
                        <img src="<?= base_url('uploads/profilePhoto/' . $enrolment['profile_photo']) ?>" alt="<?= esc($enrolment['fname']) ?>" />
                    <?php else: ?>
                        <div class="symbol-label fs-1 fw-bold bg-light-primary text-primary">
                            <?= strtoupper(substr($enrolment['fname'] ?? 'U', 0, 1) . substr($enrolment['lname'] ?? '', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h4 class="fw-bold text-gray-900 mb-1">
                    <?= esc(trim($enrolment['fname'] . ' ' . ($enrolment['oname'] ? $enrolment['oname'] . ' ' : '') . $enrolment['lname'])) ?>
                </h4>
                <div class="text-muted fs-7 mb-2"><?= esc($enrolment['email'] ?? '') ?></div>
                <?php if (!empty($enrolment['role_name'])): ?>
                <span class="badge badge-light-primary mb-3"><?= esc($enrolment['role_name']) ?></span>
                <?php endif; ?>

                <div class="separator my-4"></div>

                <?php
                $statusColors = ['Active' => 'success', 'Inactive' => 'danger', 'Completed' => 'info', 'Pending' => 'warning'];
                $sc = $statusColors[$enrolment['enrol_status'] ?? ''] ?? 'secondary';
                ?>
                <span class="badge badge-light-<?= $sc ?> fs-6 fw-bold px-5 py-3">
                    <?= esc($enrolment['enrol_status'] ?? 'Unknown') ?>
                </span>

                <div class="mt-4 d-flex flex-column gap-2">
                    <a href="<?= base_url('user/detail/' . $enrolment['user_id']) ?>" class="btn btn-light-info btn-sm">
                        <i class="ki-duotone ki-profile-circle fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        View User Profile
                    </a>
                    <a href="<?= base_url('admission/detail/' . $enrolment['admission_id']) ?>" class="btn btn-light-primary btn-sm">
                        <i class="ki-duotone ki-element-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        View Admission
                    </a>
                </div>
            </div>
        </div>
        <!--end::Student Card-->

        <!--begin::School Card-->
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7">School</h6>
            </div>
            <div class="card-body pt-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <?php
                    $logoPath = FCPATH . 'uploads/school/logo/' . ($enrolment['sch_logo'] ?? '');
                    if (!empty($enrolment['sch_logo']) && file_exists($logoPath)):
                    ?>
                    <img src="<?= base_url('uploads/school/logo/' . $enrolment['sch_logo']) ?>"
                         class="rounded w-45px h-45px object-fit-cover flex-shrink-0" />
                    <?php else: ?>
                    <div class="symbol symbol-45px flex-shrink-0">
                        <div class="symbol-label bg-light-primary">
                            <i class="ki-duotone ki-bank fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div>
                        <div class="fw-bold text-gray-800 fs-7"><?= esc($enrolment['sch_name'] ?? '—') ?></div>
                        <div class="text-muted fs-8"><?= esc($enrolment['sch_address'] ?? '') ?></div>
                    </div>
                </div>
                <?php if (!empty($enrolment['sch_phone'])): ?>
                <div class="d-flex justify-content-between py-2 adm-detail-row">
                    <span class="text-muted fs-8">Phone</span>
                    <span class="text-gray-800 fw-semibold fs-8"><?= esc($enrolment['sch_phone']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($enrolment['sch_email'])): ?>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted fs-8">Email</span>
                    <span class="text-gray-800 fw-semibold fs-8"><?= esc($enrolment['sch_email']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::School Card-->

    </div>
    <!--end::Left-->

    <!--begin::Right-->
    <div class="col-lg-8">

        <div class="card shadow-sm border-0">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">Enrolment Information</h5>
            </div>
            <div class="card-body pt-2">
                <?php
                $enrolColor = $statusColors[$enrolment['enrol_status'] ?? ''] ?? 'secondary';
                $admColor   = $statusColors[$enrolment['admission_status'] ?? ''] ?? 'secondary';

                $rows = [
                    ['Enrolment ID',     '#' . str_pad($enrolment['enrol_id'], 6, '0', STR_PAD_LEFT), null],
                    ['Academic Year',    $enrolment['enrol_year']  ?? '—', null],
                    ['Term',             'Term ' . ($enrolment['enrol_term'] ?? '—'), null],
                    ['Stream / Class',   $enrolment['stream_name'] ?? '—', null],
                    ['Year Level',       $enrolment['level_name']  ?? '—', null],
                    ['Enrolment Date',   !empty($enrolment['enrol_date']) ? date('d F Y', strtotime($enrolment['enrol_date'])) : '—', null],
                    ['Status',           $enrolment['enrol_status'] ?? '—', $enrolColor],
                    ['Admission Status', $enrolment['admission_status'] ?? '—', $admColor],
                    ['Admission Date',   !empty($enrolment['admission_date']) ? date('d F Y', strtotime($enrolment['admission_date'])) : '—', null],
                    ['Gender',           ucfirst($enrolment['gender'] ?? '—'), null],
                    ['Date of Birth',    !empty($enrolment['dob']) ? date('d F Y', strtotime($enrolment['dob'])) : '—', null],
                    ['Phone',            $enrolment['phone']   ?? '—', null],
                    ['Address',          $enrolment['address'] ?? '—', null],
                ];
                $last = count($rows) - 1;
                foreach ($rows as $i => [$label, $value, $badge]):
                ?>
                <div class="d-flex justify-content-between align-items-center py-3 <?= $i < $last ? 'adm-detail-row' : '' ?>">
                    <span class="text-gray-600 fw-semibold fs-7"><?= esc($label) ?></span>
                    <?php if ($badge): ?>
                        <span class="badge badge-light-<?= $badge ?> fs-7 fw-bold"><?= esc($value) ?></span>
                    <?php else: ?>
                        <span class="text-gray-800 fw-bold fs-7 text-end"><?= esc($value) ?></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

                <?php if (!empty($enrolment['enrol_note'])): ?>
                <div class="mt-4 p-4 bg-light rounded-3">
                    <div class="fw-bold text-gray-700 fs-7 mb-2">Enrolment Note</div>
                    <p class="text-muted fs-7 mb-0"><?= nl2br(esc($enrolment['enrol_note'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <!--end::Right-->

</div>
</div>
</div>

<script>
"use strict";

<?php if (session()->getFlashdata('edit_restricted')): ?>
Swal.fire({
    title:             'Editing Restricted',
    html:              '<?= addslashes(session()->getFlashdata('edit_restricted')) ?>',
    icon:              'warning',
    buttonsStyling:    false,
    confirmButtonText: 'Understood',
    customClass:       { confirmButton: 'btn btn-warning' },
});
<?php endif; ?>

<?php if ($canDelete): ?>
document.getElementById('btn_delete_enrolment')?.addEventListener('click', function() {
    const id   = this.dataset.id;
    const name = this.dataset.name;

    Swal.fire({
        title: 'Delete Enrolment?',
        html:  '<p class="text-gray-700 fs-6">Delete enrolment for <strong>' + name + '</strong>?</p>' +
               '<p class="text-danger fw-semibold fs-8 mb-0">This cannot be undone.</p>',
        icon:  'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-light' },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url:  '<?= base_url('enrolment/delete') ?>/' + id,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({ title: 'Deleted!', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false })
                        .then(() => window.location.href = '<?= base_url('enrolment') ?>');
                } else {
                    Swal.fire({ title: 'Failed', text: response.message, icon: 'error', buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
                }
            }
        });
    });
});
<?php endif; ?>
</script>
