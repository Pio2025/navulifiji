<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Add Classroom
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
                <li class="breadcrumb-item text-muted">Add</li>
            </ul>
        </div>
        <a href="<?= base_url('classroom') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back
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
                <h3 class="fw-bold text-gray-900 fs-4">New Classroom</h3>
            </div>
        </div>
        <div class="card-body">
        <form id="classroom_form">

            <!--begin::Stream-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Stream <span class="text-danger">*</span>
                </h6>
                <?php if (empty($streams)): ?>
                <div class="notice d-flex bg-light-warning rounded border border-warning border-dashed p-4">
                    <i class="ki-duotone ki-information fs-2tx text-warning me-3 flex-shrink-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <div class="fs-7 text-gray-700">
                        No streams found. Please configure streams in the school settings first.
                    </div>
                </div>
                <?php else: ?>
                <label class="form-label fw-semibold fs-7 required">Select Stream</label>
                <select class="form-select form-select-sm" name="stream_id_fk" id="stream_select" required>
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
                    ?>
                    <option value="<?= $stream['stream_id'] ?>"
                            data-stream-name="<?= esc($stream['stream_name']) ?>">
                        <?= esc($stream['stream_name']) ?>
                        <?php if ($isSuperAdmin && !empty($stream['sch_name'])): ?>
                            — <?= esc($stream['sch_name']) ?>
                        <?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                    <?php if ($lastLevel !== '') echo '</optgroup>'; ?>
                </select>
                <?php endif; ?>
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
                               placeholder="Auto-filled from stream + year (editable)"
                               required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7 required">Academic Year</label>
                        <input type="number"
                               class="form-control form-control-sm"
                               name="class_year"
                               value="<?= $currentYear ?>"
                               min="2000" max="2099"
                               required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7 required">Status</label>
                        <select class="form-select form-select-sm" name="class_status" required>
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--end::Details-->

        </form>
        </div>

        <div class="card-footer d-flex justify-content-end gap-3 py-4">
            <a href="<?= base_url('classroom') ?>" class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_save_classroom"
                    <?= empty($streams) ? 'disabled' : '' ?>>
                <span class="indicator-label">
                    <i class="ki-duotone ki-check fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Save Classroom
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
                <h6 class="card-title fw-bold text-gray-800 fs-7">
                    <i class="ki-duotone ki-information-5 fs-4 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Notes
                </h6>
            </div>
            <div class="card-body pt-3">
                <ul class="text-muted fs-8 ps-4 mb-0">
                    <li class="mb-2">Streams are grouped by year level.</li>
                    <li class="mb-2">
                        <?= $isSuperAdmin
                            ? 'As Super Admin you can see streams from all schools.'
                            : 'Only streams from your school are shown.' ?>
                    </li>
                    <li class="mb-2">Give a descriptive name e.g. "Year 9A Science".</li>
                    <li>Year defaults to the current academic year.</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm mt-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted fs-8 fw-semibold">Available Streams</span>
                    <span class="badge badge-light-primary"><?= count($streams) ?></span>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>

<script>
"use strict";

$('#stream_select').select2({
    placeholder: 'Select a stream...',
    width: '100%',
});

// Pre-fill stream when arriving from "no classroom yet" prompt (e.g. Admission form)
(function preselectStreamFromQuery() {
    const params   = new URLSearchParams(window.location.search);
    const streamId = params.get('stream_id_fk');
    if (streamId && document.querySelector('#stream_select option[value="' + streamId + '"]')) {
        $('#stream_select').val(streamId).trigger('change');
        autoFillClassName();
    }
})();

function autoFillClassName() {
    const sel        = document.getElementById('stream_select');
    const opt        = sel.options[sel.selectedIndex];
    const streamName = opt ? (opt.getAttribute('data-stream-name') || '') : '';
    const year       = document.querySelector('[name="class_year"]').value.trim();
    const nameField  = document.querySelector('[name="class_name"]');
    if (streamName && year) {
        nameField.value = streamName + ' ' + year;
    }
}

$('#stream_select').on('change', autoFillClassName);
document.querySelector('[name="class_year"]').addEventListener('input', autoFillClassName);

document.getElementById('btn_save_classroom')?.addEventListener('click', function() {
    const btn      = this;
    const formData = new FormData(document.getElementById('classroom_form'));

    const errors = [];
    if (!document.querySelector('[name="stream_id_fk"]').value) errors.push('Please select a stream.');
    if (!document.querySelector('[name="class_name"]').value.trim()) errors.push('Please enter a classroom name.');
    if (!document.querySelector('[name="class_year"]').value) errors.push('Please enter the academic year.');

    if (errors.length) {
        Swal.fire({
            title: 'Validation Error',
            html:  '<ul class="text-start ps-4 mb-0">' +
                   errors.map(e => '<li class="mb-1 fs-7">' + e + '</li>').join('') +
                   '</ul>',
            icon:  'warning',
            buttonsStyling:    false,
            confirmButtonText: 'Fix & Retry',
            customClass:       { confirmButton: 'btn btn-warning' }
        });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url:         '<?= base_url('classroom/store') ?>',
        type:        'POST',
        data:        formData,
        processData: false,
        contentType: false,
        success: function(response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Created!', text: response.message, icon: 'success',
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