<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isEdit ? 'Edit Medical Record' : 'Add Medical Record' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('user/medical/' . $userID) ?>" class="text-muted text-hover-primary">
                        Medical Records
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <?= $isEdit ? 'Edit' : 'Add' ?>
                </li>
            </ul>
        </div>
        <a href="<?= base_url('user/medical/' . $userID) ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <!--begin::Form-->
    <div class="col-lg-8">
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">
                    <?= $isEdit ? 'Edit' : 'New' ?> Medical Record —
                    <span class="text-primary"><?= esc($user['fname'] . ' ' . $user['lname']) ?></span>
                </h3>
            </div>
        </div>
        <div class="card-body">
        <form id="medical_form" enctype="multipart/form-data">

            <!--begin::Medical Information-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Medical Information
                </h6>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">Blood Type</label>
                        <select class="form-select form-select-sm" name="blood_type">
                            <option value="">Unknown</option>
                            <?php
                            $bloodTypes = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                            foreach ($bloodTypes as $bt):
                                $sel = (!empty($record['blood_type']) && $record['blood_type'] === $bt) ? 'selected' : '';
                            ?>
                            <option value="<?= $bt ?>" <?= $sel ?>><?= $bt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold fs-7">Medical Conditions</label>
                        <input type="text" class="form-control form-control-sm"
                               name="medical_condition"
                               value="<?= esc($record['medical_condition'] ?? '') ?>"
                               placeholder="e.g. Diabetes, Asthma, Hypertension" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">Allergies</label>
                        <textarea class="form-control form-control-sm" name="allergies" rows="2"
                                  placeholder="e.g. Penicillin, Peanuts, Dust"><?= esc($record['allergies'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7">Current Medications</label>
                        <textarea class="form-control form-control-sm" name="medications" rows="2"
                                  placeholder="e.g. Metformin 500mg daily"><?= esc($record['medications'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            <!--end::Medical Information-->

            <!--begin::Emergency Contact-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Emergency Contact
                </h6>
                <div class="row g-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold fs-7">Contact Name</label>
                        <input type="text" class="form-control form-control-sm"
                               name="emergency_contact_name"
                               value="<?= esc($record['emergency_contact_name'] ?? '') ?>"
                               placeholder="Full name" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">Phone Number</label>
                        <input type="text" class="form-control form-control-sm"
                               name="emergency_contact_phone"
                               value="<?= esc($record['emergency_contact_phone'] ?? '') ?>"
                               placeholder="e.g. +679 123 4567" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">Relationship</label>
                        <select class="form-select form-select-sm" name="emergency_contact_relation">
                            <option value="">Select...</option>
                            <?php
                            $relations = ['Spouse','Parent','Sibling','Child','Friend','Guardian','Other'];
                            foreach ($relations as $rel):
                                $sel = (!empty($record['emergency_contact_relation']) && $record['emergency_contact_relation'] === $rel) ? 'selected' : '';
                            ?>
                            <option value="<?= $rel ?>" <?= $sel ?>><?= $rel ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <!--end::Emergency Contact-->

            <!--begin::Doctor Information-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Doctor / Physician
                </h6>
                <div class="row g-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold fs-7">Doctor Name</label>
                        <input type="text" class="form-control form-control-sm"
                               name="doctor_name"
                               value="<?= esc($record['doctor_name'] ?? '') ?>"
                               placeholder="Dr. Full Name" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold fs-7">Phone</label>
                        <input type="text" class="form-control form-control-sm"
                               name="doctor_phone"
                               value="<?= esc($record['doctor_phone'] ?? '') ?>"
                               placeholder="Phone number" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">Address / Clinic</label>
                        <input type="text" class="form-control form-control-sm"
                               name="doctor_address"
                               value="<?= esc($record['doctor_address'] ?? '') ?>"
                               placeholder="Clinic or hospital name" />
                    </div>
                </div>
            </div>
            <!--end::Doctor Information-->

            <!--begin::Notes-->
            <div class="mb-7">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Additional Notes
                    <span class="text-muted fw-normal fs-8 ms-2">Optional</span>
                </h6>
                <textarea class="form-control form-control-sm" name="notes" rows="3"
                          placeholder="Any other relevant medical information..."><?= esc($record['notes'] ?? '') ?></textarea>
            </div>
            <!--end::Notes-->

            <!--begin::File Upload-->
            <div class="mb-5">
                <h6 class="fw-bold text-gray-700 mb-4 pb-2 border-bottom border-dashed fs-7 text-uppercase">
                    Medical Documents
                    <span class="text-muted fw-normal fs-8 ms-2">Optional — multiple files allowed</span>
                </h6>
            
                <!--begin::Existing files (edit mode)-->
                <?php if (!empty($files)): ?>
                <div class="mb-5">
                    <p class="text-muted fs-8 fw-semibold mb-3">
                        Currently Attached Files
                        <span class="badge badge-light-primary ms-1"><?= count($files) ?></span>
                    </p>
                    <div class="row g-3" id="existing_files_container">
                        <?php foreach ($files as $file):
                            $ext     = strtolower(pathinfo($file['file_original_name'], PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            $isPdf   = $ext === 'pdf';
                            $sizeKb  = round($file['file_size'] / 1024, 1);
                            $iconColor = $isPdf ? 'danger' : ($isImage ? 'primary' : 'info');
                            $iconName  = $isPdf ? 'document' : ($isImage ? 'picture' : 'file');
                        ?>
                        <div class="col-md-6" id="existing_file_<?= $file['file_id'] ?>">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded border">
                                <?php if ($isImage): ?>
                                    <img src="<?= base_url('user/medical/file/' . $file['file_id']) ?>"
                                         class="rounded w-40px h-40px object-fit-cover flex-shrink-0"
                                         alt="<?= esc($file['file_original_name']) ?>" />
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
                                    <a href="<?= base_url('user/medical/file/' . $file['file_id']) ?>"
                                       target="_blank"
                                       class="text-gray-800 fw-bold fs-8 text-truncate d-block text-hover-primary"
                                       title="<?= esc($file['file_original_name']) ?>">
                                        <?= esc($file['file_original_name']) ?>
                                    </a>
                                    <span class="text-muted fs-9"><?= $sizeKb ?> KB &bull; <?= strtoupper($ext) ?></span>
                                </div>
                                <button type="button"
                                        class="btn btn-icon btn-xs btn-light-danger flex-shrink-0 btn-delete-existing-file"
                                        data-file-id="<?= $file['file_id'] ?>"
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
                <!--end::Existing files-->
            
                <!--begin::New file upload area-->
                <div id="medical_dropzone"
                     style="border:2px dashed #E4E6EF; border-radius:8px; padding:30px;
                            text-align:center; cursor:pointer; background:#F9F9F9;
                            transition:all 0.2s ease;">
                    <input type="file"
                           name="medical_files[]"
                           id="medical_files_input"
                           multiple
                           accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx"
                           style="display:none;" />
                    <i class="ki-duotone ki-cloud-add fs-3x text-muted mb-3 d-block">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <p class="text-gray-600 fw-semibold fs-6 mb-1">
                        Drop files here or
                        <span class="text-primary text-decoration-underline">click to browse</span>
                    </p>
                    <p class="text-muted fs-8 mb-0">
                        JPG, PNG, GIF, WEBP, PDF, DOC, DOCX &bull; Max 10MB per file &bull; Multiple allowed
                    </p>
                </div>
                <!--end::New file upload area-->
            
                <!--begin::Selected files preview-->
                <div id="new_files_preview" class="mt-4" style="display:none;">
                    <p class="text-muted fs-8 fw-semibold mb-3">
                        Files ready to upload:
                        <span id="new_files_count" class="badge badge-light-success ms-1">0</span>
                    </p>
                    <div id="new_files_list" class="row g-3"></div>
                </div>
                <!--end::Selected files preview-->
            
            </div>
            <!--end::File Upload-->

        </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-3 py-4">
            <a href="<?= base_url('user/medical/' . $userID) ?>" class="btn btn-light btn-sm">
                Cancel
            </a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_save_medical">
                <span class="indicator-label">
                    <i class="ki-duotone ki-check fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <?= $isEdit ? 'Update Record' : 'Save Record' ?>
                </span>
                <span class="indicator-progress">
                    Saving...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
    </div>
    <!--end::Form-->

    <!--begin::Sidebar-->
    <div class="col-lg-4">
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
                <h5 class="fw-bold text-gray-900 mb-1"><?= esc($user['fname'] . ' ' . $user['lname']) ?></h5>
                <?php if (!empty($user['email'])): ?>
                <span class="text-muted fs-7 d-block"><?= esc($user['email']) ?></span>
                <?php endif; ?>
                <?php if (!empty($user['phone'])): ?>
                <span class="text-muted fs-7 d-block"><?= esc($user['phone']) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <!--end::User card-->

        <!--begin::Tips-->
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
                    <li class="mb-2">All fields are optional — fill in what is known.</li>
                    <li class="mb-2">You can upload multiple files such as reports, prescriptions, or scans.</li>
                    <li class="mb-2">Files up to 10MB each are accepted (JPG, PNG, PDF, DOC).</li>
                    <li class="mb-2">Medical information is confidential and access-controlled.</li>
                    <li>Emergency contact details are critical — keep them up to date.</li>
                </ul>
            </div>
        </div>
        <!--end::Tips-->
    </div>
    <!--end::Sidebar-->

</div>
</div>
</div>
<!--end::Content-->

<script>
"use strict";

// ── File input state ──────────────────────────────────────────────
const dropzone  = document.getElementById('medical_dropzone');
const fileInput = document.getElementById('medical_files_input');
let   selectedFiles = []; // track files as array since FileList is immutable

// ── Dropzone click ────────────────────────────────────────────────
dropzone.addEventListener('click', () => fileInput.click());

// ── Drag over styling ─────────────────────────────────────────────
dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.style.borderColor  = '#009ef7';
    dropzone.style.background   = '#f1faff';
    dropzone.style.borderStyle  = 'solid';
});

dropzone.addEventListener('dragleave', () => {
    dropzone.style.borderColor = '#E4E6EF';
    dropzone.style.background  = '#F9F9F9';
    dropzone.style.borderStyle = 'dashed';
});

// ── Drop files ────────────────────────────────────────────────────
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.style.borderColor = '#E4E6EF';
    dropzone.style.background  = '#F9F9F9';
    dropzone.style.borderStyle = 'dashed';

    const droppedFiles = Array.from(e.dataTransfer.files);
    addFiles(droppedFiles);
});

// ── File input change ─────────────────────────────────────────────
fileInput.addEventListener('change', () => {
    const newFiles = Array.from(fileInput.files);
    addFiles(newFiles);
    // Reset input so same file can be added again if needed
    fileInput.value = '';
});

// ── Add files to selectedFiles array ─────────────────────────────
function addFiles(newFiles) {
    const allowedExts = ['jpg','jpeg','png','gif','webp','pdf','doc','docx'];
    const maxSize     = 10 * 1024 * 1024; // 10MB

    newFiles.forEach(file => {
        const ext = file.name.split('.').pop().toLowerCase();

        if (!allowedExts.includes(ext)) {
            Swal.fire({
                title: 'Invalid File Type',
                html:  '<strong>' + file.name + '</strong> is not allowed.<br><small>Allowed: JPG, PNG, PDF, DOC, DOCX</small>',
                icon:  'warning',
                buttonsStyling: false,
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'btn btn-warning' }
            });
            return;
        }

        if (file.size > maxSize) {
            Swal.fire({
                title: 'File Too Large',
                html:  '<strong>' + file.name + '</strong> exceeds the 10MB limit.',
                icon:  'warning',
                buttonsStyling: false,
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'btn btn-warning' }
            });
            return;
        }

        // Avoid duplicate filenames
        const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (!exists) {
            selectedFiles.push(file);
        }
    });

    syncFileInput();
    renderPreview();
}

// ── Sync selectedFiles array back to the file input ───────────────
function syncFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

// ── Remove a file from selectedFiles ─────────────────────────────
function removeNewFile(index) {
    selectedFiles.splice(index, 1);
    syncFileInput();
    renderPreview();
}

// ── Render new files preview ──────────────────────────────────────
function renderPreview() {
    const preview  = document.getElementById('new_files_preview');
    const list     = document.getElementById('new_files_list');
    const countBadge = document.getElementById('new_files_count');

    if (selectedFiles.length === 0) {
        preview.style.display = 'none';
        list.innerHTML = '';
        return;
    }

    preview.style.display = 'block';
    countBadge.textContent = selectedFiles.length;
    list.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const ext      = file.name.split('.').pop().toLowerCase();
        const isImage  = ['jpg','jpeg','png','gif','webp'].includes(ext);
        const isPdf    = ext === 'pdf';
        const sizeKb   = (file.size / 1024).toFixed(1);
        const iconColor = isPdf ? 'danger' : (isImage ? 'primary' : 'info');
        const iconName  = isPdf ? 'document' : (isImage ? 'picture' : 'file');

        const col = document.createElement('div');
        col.className = 'col-md-6';
        col.id = 'new_file_' + index;

        // Image preview or icon
        let thumbHtml = '';
        if (isImage) {
            const url = URL.createObjectURL(file);
            thumbHtml = `<img src="${url}" class="rounded w-40px h-40px object-fit-cover flex-shrink-0" alt="${file.name}" />`;
        } else {
            thumbHtml = `
                <div class="symbol symbol-40px flex-shrink-0">
                    <div class="symbol-label bg-light-${iconColor}">
                        <i class="ki-duotone ki-${iconName} fs-3 text-${iconColor}">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>`;
        }

        col.innerHTML = `
            <div class="d-flex align-items-center gap-3 p-3 bg-light-primary rounded border border-primary border-dashed">
                ${thumbHtml}
                <div class="flex-grow-1 overflow-hidden">
                    <span class="text-gray-800 fw-bold fs-8 text-truncate d-block" title="${file.name}">
                        ${file.name}
                    </span>
                    <span class="text-muted fs-9">${sizeKb} KB &bull; ${ext.toUpperCase()}</span>
                </div>
                <button type="button"
                        class="btn btn-icon btn-xs btn-light-danger flex-shrink-0"
                        onclick="removeNewFile(${index})"
                        title="Remove">
                    <i class="ki-duotone ki-cross fs-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </button>
            </div>`;

        list.appendChild(col);
    });
}

// ── Delete existing file (edit mode) ─────────────────────────────
$(document).on('click', '.btn-delete-existing-file', function() {
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
                    $('#existing_file_' + fileId).fadeOut(200, function() { $(this).remove(); });
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

// ── Save / Update form ────────────────────────────────────────────
document.getElementById('btn_save_medical').addEventListener('click', function() {
    const btn      = this;
    const formData = new FormData(document.getElementById('medical_form'));

    // Manually append each file since we manage them in selectedFiles array
    // Remove any auto-added medical_files entries first
    formData.delete('medical_files[]');

    // Append each file individually
    selectedFiles.forEach(function(file) {
        formData.append('medical_files[]', file);
    });

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    const url = <?= $isEdit
        ? "'" . base_url('user/medical/update/' . ($record['medical_id'] ?? 0)) . "'"
        : "'" . base_url('user/medical/store/' . $userID) . "'"
    ?>;

    $.ajax({
        url:         url,
        type:        'POST',
        data:        formData,
        processData: false,
        contentType: false,
        success: function(response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;

            if (response.success) {
                Swal.fire({
                    title: 'Saved!',
                    text:  response.message,
                    icon:  'success',
                    timer: 1500,
                    showConfirmButton: false,
                }).then(function() {
                    window.location.href = response.redirect;
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text:  response.message,
                    icon:  'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Close',
                    customClass: { confirmButton: 'btn btn-danger' }
                });
            }
        },
        error: function(xhr) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            log_message('error', xhr.responseText);
            Swal.fire({
                title: 'Error',
                text:  'An unexpected error occurred.',
                icon:  'error',
                buttonsStyling: false,
                confirmButtonText: 'Close',
                customClass: { confirmButton: 'btn btn-danger' }
            });
        }
    });
});
</script>