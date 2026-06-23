<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Medical Records
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('user') ?>" class="text-muted text-hover-primary">Users</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('user/detail/' . $userID) ?>" class="text-muted text-hover-primary">
                        <?= esc($user['fname'] . ' ' . $user['lname']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Medical Records</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('user/medical/add/' . $userID) ?>"
               class="btn btn-sm btn-primary">
                <i class="ki-duotone ki-plus fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Add Medical Record
            </a>
            <?php endif; ?>
            <a href="<?= base_url('user/detail/' . $userID) ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Back to Profile
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <!--begin::Sidebar-->
    <div class="col-lg-3">

        <!--begin::User card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body text-center p-6">
                <div class="symbol symbol-80px symbol-circle mb-4 mx-auto">
                    <?php if (!empty($user['profile_photo'])): ?>
                        <img src="<?= base_url('uploads/profilePhoto/' . $user['profile_photo']) ?>"
                             alt="<?= esc($user['fname']) ?>" />
                    <?php else: ?>
                        <div class="symbol-label fs-1 fw-bold bg-light-primary text-primary">
                            <?= strtoupper(substr($user['fname'], 0, 1) . substr($user['lname'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h5 class="fw-bold text-gray-900 mb-1 fs-5">
                    <?= esc($user['fname'] . ' ' . $user['lname']) ?>
                </h5>
                <?php if (!empty($user['email'])): ?>
                <span class="text-muted fs-7 d-block mb-1">
                    <i class="ki-duotone ki-sms fs-6 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <?= esc($user['email']) ?>
                </span>
                <?php endif; ?>
                <?php if (!empty($user['phone'])): ?>
                <span class="text-muted fs-7 d-block mb-3">
                    <i class="ki-duotone ki-phone fs-6 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <?= esc($user['phone']) ?>
                </span>
                <?php endif; ?>
                <?php if (!empty($user['gender'])): ?>
                <span class="badge badge-light-primary mb-3"><?= esc($user['gender']) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($canEdit): ?>
            <div class="card-footer p-4 pt-0 d-flex flex-column gap-2">
                <a href="<?= base_url('user/medical/add/' . $userID) ?>"
                   class="btn btn-sm btn-primary w-100">
                    <i class="ki-duotone ki-plus fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Add Record
                </a>
                <a href="<?= base_url('user/detail/' . $userID) ?>"
                   class="btn btn-sm btn-light-info w-100">
                    <i class="ki-duotone ki-profile-circle fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    View Profile
                </a>
            </div>
            <?php endif; ?>
        </div>
        <!--end::User card-->

        <!--begin::Summary card-->
        <?php if (!empty($records)): ?>
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7">Quick Summary</h6>
            </div>
            <div class="card-body pt-3 pb-5">
                <?php
                $latest = $records[0];
                $summaryItems = [
                    ['Blood Type',  $latest['blood_type']  ?? 'N/A', 'danger'],
                    ['Allergies',   !empty($latest['allergies'])   ? 'On record' : 'None recorded', 'warning'],
                    ['Medications', !empty($latest['medications']) ? 'On record' : 'None recorded', 'info'],
                    ['Records',     count($records) . ' record(s)', 'primary'],
                ];
                foreach ($summaryItems as $item):
                ?>
                <div class="d-flex justify-content-between align-items-center py-2
                    <?= $item !== end($summaryItems) ? 'border-bottom border-dashed' : '' ?>">
                    <span class="text-muted fs-8 fw-semibold"><?= $item[0] ?></span>
                    <span class="badge badge-light-<?= $item[2] ?> fs-8"><?= esc($item[1]) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <!--end::Summary card-->

    </div>
    <!--end::Sidebar-->

    <!--begin::Main content-->
    <div class="col-lg-9">

        <?php if (empty($records)): ?>
        <!--begin::Empty state-->
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body text-center py-20">
                <i class="ki-duotone ki-heart-circle fs-4x text-muted mb-5 d-block">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <h4 class="text-gray-700 fw-bold mb-2">No Medical Records Found</h4>
                <p class="text-muted fs-6 mb-6">
                    No medical records have been added for this user yet.
                </p>
                <?php if ($canEdit): ?>
                <a href="<?= base_url('user/medical/add/' . $userID) ?>"
                   class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-3 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Add First Medical Record
                </a>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Empty state-->

        <?php else: ?>

        <?php foreach ($records as $record): ?>
        <!--begin::Medical Record Card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;"
             id="medical_card_<?= $record['medical_id'] ?>">

            <!--begin::Card header-->
            <div class="card-header border-0 pt-5">
                <div class="card-title d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px">
                        <div class="symbol-label bg-light-danger">
                            <i class="ki-duotone ki-heart-circle fs-3 text-danger">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold text-gray-900 mb-0 fs-6">
                            Medical Record
                            <?php if (!empty($record['blood_type'])): ?>
                                <span class="badge badge-light-danger ms-2 fs-8">
                                    <?= esc($record['blood_type']) ?>
                                </span>
                            <?php endif; ?>
                        </h5>
                        <span class="text-muted fs-8">
                            Added: <?= date('d M Y', strtotime($record['medical_date'])) ?>
                        </span>
                    </div>
                </div>
                <?php if ($canEdit): ?>
                <div class="card-toolbar d-flex gap-2">
                    <a href="<?= base_url('user/medical/edit/' . $record['medical_id']) ?>"
                       class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-pencil fs-4 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Edit
                    </a>
                    <button type="button"
                            class="btn btn-sm btn-light-danger btn-delete-medical"
                            data-id="<?= $record['medical_id'] ?>"
                            data-date="<?= date('d M Y', strtotime($record['medical_date'])) ?>">
                        <i class="ki-duotone ki-trash fs-4 me-1">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Delete
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <!--end::Card header-->

            <div class="card-body pt-2 pb-5">

                <div class="row g-5">

                    <!--begin::Medical Info-->
                    <div class="col-md-6">
                        <h6 class="fw-bold text-gray-700 mb-3 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                            Medical Information
                        </h6>
                        <?php
                        $medInfo = [
                            ['Blood Type',         $record['blood_type']        ?? '—'],
                            ['Medical Conditions',  $record['medical_condition'] ?? '—'],
                            ['Allergies',           $record['allergies']         ?? '—'],
                            ['Medications',         $record['medications']       ?? '—'],
                        ];
                        foreach ($medInfo as $row):
                        ?>
                        <div class="d-flex justify-content-between py-2 border-bottom border-dashed border-gray-100">
                            <span class="text-muted fw-semibold fs-7 w-40"><?= $row[0] ?></span>
                            <span class="text-gray-800 fw-bold fs-7 text-end w-60">
                                <?= esc($row[1]) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!--end::Medical Info-->

                    <!--begin::Emergency + Doctor-->
                    <div class="col-md-6">
                        <h6 class="fw-bold text-gray-700 mb-3 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                            Emergency Contact
                        </h6>
                        <?php
                        $emergency = [
                            ['Name',        $record['emergency_contact_name']     ?? '—'],
                            ['Phone',       $record['emergency_contact_phone']    ?? '—'],
                            ['Relationship',$record['emergency_contact_relation'] ?? '—'],
                        ];
                        foreach ($emergency as $row):
                        ?>
                        <div class="d-flex justify-content-between py-2 border-bottom border-dashed border-gray-100">
                            <span class="text-muted fw-semibold fs-7"><?= $row[0] ?></span>
                            <span class="text-gray-800 fw-bold fs-7"><?= esc($row[1]) ?></span>
                        </div>
                        <?php endforeach; ?>

                        <h6 class="fw-bold text-gray-700 mt-4 mb-3 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                            Doctor / Physician
                        </h6>
                        <?php
                        $doctor = [
                            ['Name',    $record['doctor_name']    ?? '—'],
                            ['Phone',   $record['doctor_phone']   ?? '—'],
                            ['Address', $record['doctor_address'] ?? '—'],
                        ];
                        foreach ($doctor as $row):
                        ?>
                        <div class="d-flex justify-content-between py-2 border-bottom border-dashed border-gray-100">
                            <span class="text-muted fw-semibold fs-7"><?= $row[0] ?></span>
                            <span class="text-gray-800 fw-bold fs-7 text-end"><?= esc($row[1]) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!--end::Emergency + Doctor-->

                    <!--begin::Notes-->
                    <?php if (!empty($record['notes'])): ?>
                    <div class="col-12">
                        <h6 class="fw-bold text-gray-700 mb-2 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                            Additional Notes
                        </h6>
                        <p class="text-gray-700 fs-7 mb-0"><?= nl2br(esc($record['notes'])) ?></p>
                    </div>
                    <?php endif; ?>
                    <!--end::Notes-->

                    <!--begin::Files-->
                    <?php if (!empty($record['files'])): ?>
                    <div class="col-12">
                        <h6 class="fw-bold text-gray-700 mb-3 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                            Attached Files
                            <span class="badge badge-light-primary ms-2 fs-8">
                                <?= count($record['files']) ?>
                            </span>
                        </h6>
                        <div class="row g-3">
                            <?php foreach ($record['files'] as $file):
                                $ext      = pathinfo($file['file_original_name'], PATHINFO_EXTENSION);
                                $isImage  = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']);
                                $isPdf    = strtolower($ext) === 'pdf';
                                $iconClass = $isPdf ? 'ki-document text-danger' : ($isImage ? 'ki-picture text-primary' : 'ki-file text-info');
                                $sizeKb   = round($file['file_size'] / 1024, 1);
                            ?>
                            <div class="col-md-4" id="file_card_<?= $file['file_id'] ?>">
                                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                                    <!--begin::Icon-->
                                    <div class="symbol symbol-40px flex-shrink-0">
                                        <?php if ($isImage): ?>
                                            <img src="<?= base_url('user/medical/file/' . $file['file_id']) ?>"
                                                 class="rounded object-fit-cover w-40px h-40px"
                                                 alt="<?= esc($file['file_original_name']) ?>" />
                                        <?php else: ?>
                                            <div class="symbol-label bg-light">
                                                <i class="ki-duotone <?= $iconClass ?> fs-2">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Icon-->

                                    <!--begin::Info-->
                                    <div class="flex-grow-1 overflow-hidden">
                                        <a href="<?= base_url('user/medical/file/' . $file['file_id']) ?>"
                                           target="_blank"
                                           class="text-gray-800 fw-bold fs-8 d-block text-truncate text-hover-primary">
                                            <?= esc($file['file_original_name']) ?>
                                        </a>
                                        <span class="text-muted fs-9"><?= $sizeKb ?> KB</span>
                                    </div>
                                    <!--end::Info-->

                                    <!--begin::Delete-->
                                    <?php if ($canEdit): ?>
                                    <button type="button"
                                            class="btn btn-icon btn-xs btn-light-danger flex-shrink-0 btn-delete-file"
                                            data-file-id="<?= $file['file_id'] ?>"
                                            title="Delete file">
                                        <i class="ki-duotone ki-trash fs-5">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                    <?php endif; ?>
                                    <!--end::Delete-->
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!--end::Files-->

                </div>
            </div>
        </div>
        <!--end::Medical Record Card-->
        <?php endforeach; ?>

        <?php endif; ?>
    </div>
    <!--end::Main content-->

</div>
</div>
</div>
<!--end::Content-->

<script>
"use strict";

// ── Delete medical record ─────────────────────────────────────────
$(document).on('click', '.btn-delete-medical', function() {
    const btn  = $(this);
    const id   = btn.data('id');
    const date = btn.data('date');

    Swal.fire({
        title: 'Delete Medical Record?',
        html:
            '<p class="text-gray-700 mb-2">This will permanently delete the medical record from <strong>' + date + '</strong> and all associated files.</p>' +
            '<p class="text-danger fw-bold fs-7 mb-0">This action cannot be undone.</p>',
        icon: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton:  'btn btn-light',
        },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;

        btn.attr('disabled', true);

        $.ajax({
            url:  '<?= base_url('user/medical/delete') ?>/' + id,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    $('#medical_card_' + id).fadeOut(300, function() {
                        $(this).remove();

                        // Show empty state if no records left
                        if ($('[id^="medical_card_"]').length === 0) {
                            location.reload();
                        }
                    });

                    Swal.fire({
                        title: 'Deleted!',
                        text:  response.message,
                        icon:  'success',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                } else {
                    btn.attr('disabled', false);
                    Swal.fire({
                        title: 'Failed',
                        text:  response.message,
                        icon:  'error',
                        buttonsStyling: false,
                        confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' }
                    });
                }
            }
        });
    });
});

// ── Delete single file ────────────────────────────────────────────
$(document).on('click', '.btn-delete-file', function() {
    const btn    = $(this);
    const fileId = btn.data('file-id');

    Swal.fire({
        title: 'Delete File?',
        text:  'This will permanently delete this file.',
        icon:  'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton:  'btn btn-light',
        },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;

        btn.attr('disabled', true);

        $.ajax({
            url:  '<?= base_url('user/medical/delete-file') ?>/' + fileId,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    $('#file_card_' + fileId).fadeOut(200, function() { $(this).remove(); });
                    Swal.fire({
                        title: 'Deleted!',
                        text:  response.message,
                        icon:  'success',
                        timer: 1500,
                        showConfirmButton: false,
                    });
                } else {
                    btn.attr('disabled', false);
                    Swal.fire({
                        title: 'Failed',
                        text:  response.message,
                        icon:  'error',
                        buttonsStyling: false,
                        confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' }
                    });
                }
            }
        });
    });
});
</script>