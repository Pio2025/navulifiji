<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Edit Classroom
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('classroom') ?>" class="text-muted text-hover-primary">Classrooms</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Edit</li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/detail/' . $classroom['class_id']) ?>"
           class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back to Detail
        </a>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6 justify-content-center">

    <div class="col-lg-8">
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">
                    Edit — <span class="text-primary"><?= esc($classroom['class_name']) ?></span>
                </h3>
            </div>
        </div>
        <div class="card-body">
        <form id="edit_classroom_form">

            <!--begin::Stream-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Stream <span class="text-danger">*</span>
                </h6>
                <label class="form-label fw-semibold fs-7 required">Select Stream</label>
                <select class="form-select form-select-sm" name="stream_id_fk" id="edit_stream_select" required>
                    <option value="">Select a stream...</option>
                    <?php
                    $lastLevel = '';
                    foreach ($streams as $stream):
                        $levelLabel = $stream['level_name'] ?? 'Unknown';
                        if ($levelLabel !== $lastLevel):
                            if ($lastLevel !== '') echo '</optgroup>';
                            echo '<optgroup label="' . esc($levelLabel) . '">';
                            $lastLevel = $levelLabel;
                        endif;
                        $selected = (int)$stream['stream_id'] === (int)$classroom['stream_id_fk'] ? 'selected' : '';
                    ?>
                    <option value="<?= $stream['stream_id'] ?>" <?= $selected ?>>
                        <?= esc($stream['stream_name']) ?>
                        <?php if ($isSuperAdmin && !empty($stream['sch_name'])): ?>
                            — <?= esc($stream['sch_name']) ?>
                        <?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                    <?php if ($lastLevel !== '') echo '</optgroup>'; ?>
                </select>
            </div>
            <!--end::Stream-->

            <!--begin::Details-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Classroom Details
                </h6>
                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold fs-7 required">Classroom Name</label>
                        <input type="text"
                               class="form-control form-control-sm"
                               name="class_name"
                               value="<?= esc($classroom['class_name']) ?>"
                               required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7 required">Academic Year</label>
                        <input type="number"
                               class="form-control form-control-sm"
                               name="class_year"
                               value="<?= esc($classroom['class_year']) ?>"
                               min="2000" max="2099"
                               required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7 required">Status</label>
                        <select class="form-select form-select-sm" name="class_status" required>
                            <?php foreach (['Active', 'Inactive', 'Completed', 'Archived'] as $s): ?>
                            <option value="<?= $s ?>"
                                    <?= $classroom['class_status'] === $s ? 'selected' : '' ?>>
                                <?= $s ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <!--end::Details-->

        </form>
        </div>

        <div class="card-footer d-flex justify-content-end gap-3 py-4">
            <a href="<?= base_url('classroom/detail/' . $classroom['class_id']) ?>"
               class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_update_classroom">
                <span class="indicator-label">
                    <i class="ki-duotone ki-check fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Save Changes
                </span>
                <span class="indicator-progress">
                    Saving... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7">Info</h6>
            </div>
            <div class="card-body pt-3">
                <ul class="text-muted fs-8 ps-4 mb-0">
                    <li class="mb-2">You can change the stream, name, year and status.</li>
                    <li class="mb-2">Classroom names must be unique across the system.</li>
                    <li>
                        Classroom ID:
                        <strong>#<?= str_pad($classroom['class_id'], 6, '0', STR_PAD_LEFT) ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
</div>
</div>

<script>
"use strict";

$('#edit_stream_select').select2({
    placeholder: 'Select a stream...',
    width: '100%',
});

document.getElementById('btn_update_classroom').addEventListener('click', function() {
    const btn      = this;
    const formData = new FormData(document.getElementById('edit_classroom_form'));

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url:         '<?= base_url('classroom/update/' . $classroom['class_id']) ?>',
        type:        'POST',
        data:        formData,
        processData: false,
        contentType: false,
        success: function(response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Updated!', text: response.message, icon: 'success',
                    timer: 1500, showConfirmButton: false })
                    .then(() => window.location.href = response.redirect);
            } else {
                Swal.fire({ title: 'Failed', text: response.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close',
                    customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function() {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'Close',
                customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
});
</script>