<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isEdit ? 'Edit Incident' : 'Log Incident' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('conduct') ?>" class="text-muted text-hover-primary">Conduct</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= $isEdit ? 'Edit' : 'Add' ?></li>
            </ul>
        </div>
        <a href="<?= base_url('conduct') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <div class="col-lg-8">
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4"><?= $isEdit ? 'Edit' : 'New' ?> Conduct Incident</h3>
            </div>
        </div>
        <div class="card-body">
        <form id="conduct_form" enctype="multipart/form-data">

            <div class="mb-7">
                <label class="form-label fw-semibold fs-7 required">Student</label>
                <select class="form-select form-select-sm" name="student_id" id="student_select" required>
                    <option value="">— Select student —</option>
                    <?php foreach ($students as $s): ?>
                    <option value="<?= $s['admission_id'] ?>"
                            <?= (!empty($incident) && (int) $incident['student_id'] === (int) $s['admission_id']) ? 'selected' : '' ?>>
                        <?= esc(trim($s['fname'] . ' ' . ($s['oname'] ? $s['oname'] . ' ' : '') . $s['lname'])) ?>
                        <?php if ($isSuperAdmin): ?>(<?= esc($s['sch_name'] ?? '') ?>)<?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row g-4 mb-7">
                <div class="col-md-7">
                    <label class="form-label fw-semibold fs-7 required">Conduct Type</label>
                    <select class="form-select form-select-sm" name="type_id_fk" id="type_select" required>
                        <option value="">— Select type —</option>
                        <?php foreach ($typesGrouped as $category => $types): ?>
                        <optgroup label="<?= esc($category) ?>">
                            <?php foreach ($types as $t): ?>
                            <option value="<?= $t['type_id'] ?>"
                                    data-points="<?= $t['default_points'] ?>"
                                    data-severity="<?= esc($t['severity_level']) ?>"
                                    data-positive="<?= (int) $t['is_positive'] ?>"
                                    <?= (!empty($incident) && (int) $incident['type_id_fk'] === (int) $t['type_id']) ? 'selected' : '' ?>>
                                <?= esc($t['type_name']) ?> (<?= $t['default_points'] > 0 ? '+' : '' ?><?= $t['default_points'] ?>)
                            </option>
                            <?php endforeach; ?>
                        </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold fs-7">Points</label>
                    <input type="number" class="form-control form-control-sm" name="points_awarded"
                           id="points_input"
                           value="<?= esc($incident['points_awarded'] ?? '') ?>" />
                    <span class="text-muted fs-9" id="severity_badge"></span>
                </div>
            </div>

            <div class="row g-4 mb-7">
                <div class="col-md-6">
                    <label class="form-label fw-semibold fs-7">Date / Time</label>
                    <input type="datetime-local" class="form-control form-control-sm" name="incident_date"
                           value="<?= !empty($incident['incident_date']) ? date('Y-m-d\TH:i', strtotime($incident['incident_date'])) : date('Y-m-d\TH:i') ?>" />
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold fs-7">Location</label>
                    <input type="text" class="form-control form-control-sm" name="location"
                           value="<?= esc($incident['location'] ?? '') ?>"
                           placeholder="e.g. Classroom 4B, Playground" />
                </div>
            </div>

            <div class="mb-7">
                <label class="form-label fw-semibold fs-7">Description</label>
                <textarea class="form-control form-control-sm" name="incident_description" rows="4"
                          placeholder="Describe what happened..."><?= esc($incident['incident_description'] ?? '') ?></textarea>
            </div>

            <?php if ($isEdit): ?>
            <div class="mb-7 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_resolved" id="is_resolved" value="1"
                       <?= !empty($incident['is_resolved']) ? 'checked' : '' ?>>
                <label class="form-check-label fs-7 fw-semibold" for="is_resolved">Resolved</label>
            </div>
            <?php endif; ?>

            <!--begin::File Upload-->
            <div class="mb-5">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Attachments
                    <span class="text-muted fw-normal fs-8 ms-2">Optional — multiple files allowed</span>
                </h6>

                <?php if (!empty($files)): ?>
                <div class="mb-5">
                    <p class="text-muted fs-8 fw-semibold mb-3">
                        Currently Attached Files
                        <span class="badge badge-light-primary ms-1"><?= count($files) ?></span>
                    </p>
                    <div class="row g-3" id="existing_files_container">
                        <?php foreach ($files as $file):
                            $ext     = strtolower(pathinfo($file['file_src'], PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            $isPdf   = $ext === 'pdf';
                            $iconColor = $isPdf ? 'danger' : ($isImage ? 'primary' : 'info');
                            $iconName  = $isPdf ? 'document' : ($isImage ? 'picture' : 'file');
                        ?>
                        <div class="col-md-6" id="existing_file_<?= $file['conduct_file_id'] ?>">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded border">
                                <?php if ($isImage): ?>
                                    <img src="<?= base_url('conduct/file/' . $file['conduct_file_id']) ?>"
                                         class="rounded w-40px h-40px object-fit-cover flex-shrink-0"
                                         alt="<?= esc($file['file_src']) ?>" />
                                <?php else: ?>
                                    <div class="symbol symbol-40px flex-shrink-0">
                                        <div class="symbol-label bg-light-<?= $iconColor ?>">
                                            <i class="ki-duotone ki-<?= $iconName ?> fs-3 text-<?= $iconColor ?>">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-grow-1 overflow-hidden">
                                    <a href="<?= base_url('conduct/file/' . $file['conduct_file_id']) ?>"
                                       target="_blank"
                                       class="text-gray-800 fw-bold fs-8 text-truncate d-block text-hover-primary">
                                        <?= esc($file['file_src']) ?>
                                    </a>
                                </div>
                                <button type="button"
                                        class="btn btn-icon btn-xs btn-light-danger flex-shrink-0 btn-delete-existing-file"
                                        data-file-id="<?= $file['conduct_file_id'] ?>"
                                        title="Delete">
                                    <i class="ki-duotone ki-trash fs-5">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div id="conduct_dropzone"
                     style="border:2px dashed #E4E6EF; border-radius:8px; padding:30px;
                            text-align:center; cursor:pointer; background:#F9F9F9;">
                    <input type="file" name="conduct_files[]" id="conduct_files_input" multiple
                           accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx" style="display:none;" />
                    <i class="ki-duotone ki-cloud-add fs-3x text-muted mb-3 d-block">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <p class="text-gray-600 fw-semibold fs-6 mb-1">
                        Drop files here or <span class="text-primary text-decoration-underline">click to browse</span>
                    </p>
                    <p class="text-muted fs-8 mb-0">JPG, PNG, GIF, WEBP, PDF, DOC, DOCX &bull; Max 10MB per file</p>
                </div>

                <div id="new_files_preview" class="mt-4" style="display:none;">
                    <p class="text-muted fs-8 fw-semibold mb-3">
                        Files ready to upload:
                        <span id="new_files_count" class="badge badge-light-success ms-1">0</span>
                    </p>
                    <div id="new_files_list" class="row g-3"></div>
                </div>
            </div>
            <!--end::File Upload-->

        </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-3 py-4">
            <a href="<?= base_url('conduct') ?>" class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_save_conduct">
                <span class="indicator-label">
                    <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                    <?= $isEdit ? 'Update Incident' : 'Save Incident' ?>
                </span>
                <span class="indicator-progress">
                    Saving...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
                    Tips
                </h6>
            </div>
            <div class="card-body pt-3">
                <ul class="text-muted fs-8 ps-4 mb-0">
                    <li class="mb-2">Selecting a conduct type auto-fills the points value — you can still adjust it.</li>
                    <li class="mb-2">Positive behavior earns points; misconduct deducts points.</li>
                    <li class="mb-2">You can attach supporting evidence such as photos or written statements.</li>
                    <li>Once saved, you can add disciplinary actions and notify parents from the incident detail page.</li>
                </ul>
            </div>
        </div>
    </div>

</div>
</div>
</div>

<script>
"use strict";

$('#student_select').select2({ placeholder: '— Select student —', width: '100%' });
$('#type_select').select2({ placeholder: '— Select type —', width: '100%' });

$('#type_select').on('change', function() {
    const opt = this.options[this.selectedIndex];
    if (!opt || !opt.value) { document.getElementById('severity_badge').textContent = ''; return; }
    document.getElementById('points_input').value = opt.dataset.points || 0;
    document.getElementById('severity_badge').textContent = opt.dataset.severity ? 'Severity: ' + opt.dataset.severity : '';
});

// ── File dropzone ─────────────────────────────────────────────────
const dropzone  = document.getElementById('conduct_dropzone');
const fileInput = document.getElementById('conduct_files_input');
let   selectedFiles = [];

dropzone.addEventListener('click', () => fileInput.click());
dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.style.borderColor = '#009ef7'; dropzone.style.background = '#f1faff'; });
dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = '#E4E6EF'; dropzone.style.background = '#F9F9F9'; });
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.style.borderColor = '#E4E6EF';
    dropzone.style.background  = '#F9F9F9';
    addFiles(Array.from(e.dataTransfer.files));
});
fileInput.addEventListener('change', () => {
    addFiles(Array.from(fileInput.files));
    fileInput.value = '';
});

function addFiles(newFiles) {
    const allowedExts = ['jpg','jpeg','png','gif','webp','pdf','doc','docx'];
    const maxSize     = 10 * 1024 * 1024;

    newFiles.forEach(file => {
        const ext = file.name.split('.').pop().toLowerCase();
        if (!allowedExts.includes(ext)) {
            Swal.fire({ title: 'Invalid File Type', text: file.name + ' is not allowed.', icon: 'warning' });
            return;
        }
        if (file.size > maxSize) {
            Swal.fire({ title: 'File Too Large', text: file.name + ' exceeds the 10MB limit.', icon: 'warning' });
            return;
        }
        const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (!exists) selectedFiles.push(file);
    });

    syncFileInput();
    renderPreview();
}

function syncFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

function removeNewFile(index) {
    selectedFiles.splice(index, 1);
    syncFileInput();
    renderPreview();
}

function renderPreview() {
    const preview     = document.getElementById('new_files_preview');
    const list        = document.getElementById('new_files_list');
    const countBadge  = document.getElementById('new_files_count');

    if (selectedFiles.length === 0) { preview.style.display = 'none'; list.innerHTML = ''; return; }

    preview.style.display = 'block';
    countBadge.textContent = selectedFiles.length;
    list.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const col = document.createElement('div');
        col.className = 'col-md-6';
        col.innerHTML = `
            <div class="d-flex align-items-center gap-3 p-3 bg-light-primary rounded border border-primary border-dashed">
                <div class="flex-grow-1 overflow-hidden">
                    <span class="text-gray-800 fw-bold fs-8 text-truncate d-block">${file.name}</span>
                    <span class="text-muted fs-9">${(file.size / 1024).toFixed(1)} KB</span>
                </div>
                <button type="button" class="btn btn-icon btn-xs btn-light-danger flex-shrink-0" onclick="removeNewFile(${index})">
                    <i class="ki-duotone ki-cross fs-4"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>`;
        list.appendChild(col);
    });
}

$(document).on('click', '.btn-delete-existing-file', function() {
    const btn    = $(this);
    const fileId = btn.data('file-id');

    Swal.fire({
        title: 'Delete File?',
        text:  'This will permanently delete this file.',
        icon:  'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
    }).then(function(result) {
        if (!result.isConfirmed) return;
        btn.attr('disabled', true);
        $.ajax({
            url:  '<?= base_url('conduct/file') ?>/' + fileId + '/delete',
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    $('#existing_file_' + fileId).fadeOut(200, function() { $(this).remove(); });
                } else {
                    btn.attr('disabled', false);
                    Swal.fire({ title: 'Failed', text: response.message, icon: 'error' });
                }
            }
        });
    });
});

document.getElementById('btn_save_conduct').addEventListener('click', function() {
    const btn      = this;
    const formData = new FormData(document.getElementById('conduct_form'));
    formData.delete('conduct_files[]');
    selectedFiles.forEach(function(file) { formData.append('conduct_files[]', file); });

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    const url = <?= $isEdit
        ? "'" . base_url('conduct/update/' . ($incident['incident_id'] ?? 0)) . "'"
        : "'" . base_url('conduct/store') . "'"
    ?>;

    $.ajax({
        url: url, type: 'POST', data: formData, processData: false, contentType: false,
        success: function(response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Saved!', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(function() { window.location.href = response.redirect; });
            } else {
                Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
            }
        },
        error: function() {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An unexpected error occurred.', icon: 'error' });
        }
    });
});
</script>
