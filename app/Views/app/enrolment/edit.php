<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Edit Enrolment
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('enrolment') ?>" class="text-muted text-hover-primary">Enrolments</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Edit</li>
            </ul>
        </div>
        <a href="<?= base_url('enrolment/detail/' . $enrolment['enrol_id']) ?>"
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
<div class="row g-6">

    <!--begin::Left panel-->
    <div class="col-lg-8">
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">

        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">Edit Enrolment</h3>
            </div>
        </div>

        <div class="card-body">

            <?php $isLocked = $enrolment['enrol_status'] === 'Completed' && !$isSuperAdmin; ?>

            <?php if ($isLocked): ?>
            <div class="d-flex align-items-center gap-3 p-4 bg-light-warning rounded-3 mb-6">
                <i class="ki-duotone ki-lock-2 fs-2x text-warning flex-shrink-0">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div>
                    <div class="fw-bold text-gray-800 fs-7">Editing Restricted</div>
                    <div class="text-muted fs-8">This enrolment is <strong>Completed</strong>. Only the <strong>Status</strong> field can be changed.</div>
                </div>
            </div>
            <?php endif; ?>

            <!--begin::Student info-->
            <div class="mb-7 p-4 bg-light-primary rounded-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-45px symbol-circle">
                        <?php if (!empty($enrolment['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $enrolment['profile_photo']) ?>"
                                 alt="<?= esc($enrolment['fname']) ?>" />
                        <?php else: ?>
                            <div class="symbol-label bg-primary text-white fw-bold fs-6">
                                <?= strtoupper(substr($enrolment['fname'] ?? 'U', 0, 1) . substr($enrolment['lname'] ?? '', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-gray-800 fs-6">
                            <?= esc(trim($enrolment['fname'] . ' ' . ($enrolment['oname'] ? $enrolment['oname'] . ' ' : '') . $enrolment['lname'])) ?>
                        </div>
                        <div class="text-muted fs-8"><?= esc($enrolment['email'] ?? '') ?></div>
                    </div>
                    <div class="text-end">
                        <span class="badge badge-light-primary"><?= esc($enrolment['role_name'] ?? '') ?></span>
                        <div class="text-muted fs-9 mt-1">Read-only</div>
                    </div>
                </div>
            </div>
            <!--end::Student info-->

            <form id="edit_enrolment_form">

                <!--begin::Stream-->
                <div class="mb-7">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="w-4px h-20px bg-primary rounded-2 d-inline-block flex-shrink-0"></span>
                        <span class="fw-bold text-gray-800 fs-6">Stream / Class <span class="text-danger">*</span></span>
                    </div>
                    <label class="form-label fw-semibold fs-7 required">Stream</label>
                    <select class="form-select form-select-sm"
                            name="stream_id_fk"
                            id="edit_stream_select"
                            <?= $isLocked ? 'disabled' : 'required' ?>>
                        <?php
                        $lastLevel = '';
                        foreach ($streams as $stream):
                            $levelLabel = $stream['level_name'] ?? 'Unknown Level';
                            if ($levelLabel !== $lastLevel):
                                if ($lastLevel !== '') echo '</optgroup>';
                                echo '<optgroup label="' . esc($levelLabel) . '">';
                                $lastLevel = $levelLabel;
                            endif;
                            $selected = ((int)$stream['stream_id'] === (int)$enrolment['stream_id']) ? 'selected' : '';
                        ?>
                        <option value="<?= $stream['stream_id'] ?>" <?= $selected ?>>
                            <?= esc($stream['stream_name']) ?>
                            <?php if ($isSuperAdmin && !empty($stream['sch_name'])): ?>
                                (<?= esc($stream['sch_name']) ?>)
                            <?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                        <?php if ($lastLevel !== '') echo '</optgroup>'; ?>
                    </select>
                </div>
                <!--end::Stream-->

                <!--begin::Details-->
                <div class="mb-7">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="w-4px h-20px bg-primary rounded-2 d-inline-block flex-shrink-0"></span>
                        <span class="fw-bold text-gray-800 fs-6">Enrolment Details</span>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7 required">Year</label>
                            <input type="number"
                                   class="form-control form-control-sm"
                                   name="enrol_year"
                                   value="<?= esc($enrolment['enrol_year'] ?? date('Y')) ?>"
                                   min="2000" max="2099"
                                   <?= $isLocked ? 'readonly' : 'required' ?> />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7 required">Term</label>
                            <select class="form-select form-select-sm" name="enrol_term" <?= $isLocked ? 'disabled' : 'required' ?>>
                                <?php for ($t = 1; $t <= 3; $t++): ?>
                                <option value="<?= $t ?>"
                                        <?= (int)$enrolment['enrol_term'] === $t ? 'selected' : '' ?>>
                                    Term <?= $t ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7 required">Date</label>
                            <input type="date"
                                   class="form-control form-control-sm"
                                   name="enrol_date"
                                   value="<?= esc($enrolment['enrol_date'] ?? date('Y-m-d')) ?>"
                                   <?= $isLocked ? 'readonly' : 'required' ?> />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7 required">Status</label>
                            <select class="form-select form-select-sm" name="enrol_status" required>
                                <?php foreach (['Active', 'Inactive', 'Completed'] as $s): ?>
                                <option value="<?= $s ?>"
                                        <?= $enrolment['enrol_status'] === $s ? 'selected' : '' ?>>
                                    <?= $s ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!--end::Details-->

                <!--begin::Note-->
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="w-4px h-20px bg-primary rounded-2 d-inline-block flex-shrink-0"></span>
                        <span class="fw-bold text-gray-800 fs-6">Note</span>
                        <span class="text-muted fw-normal fs-8 ms-1">Optional</span>
                    </div>
                    <textarea class="form-control form-control-sm"
                              name="enrol_note" rows="3"
                              placeholder="Any notes..."
                              <?= $isLocked ? 'readonly' : '' ?>><?= esc($enrolment['enrol_note'] ?? '') ?></textarea>
                </div>
                <!--end::Note-->

            </form>
        </div>

        <div class="card-footer d-flex justify-content-end gap-3 py-4">
            <a href="<?= base_url('enrolment/detail/' . $enrolment['enrol_id']) ?>"
               class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_update_enrolment">
                <span class="indicator-label">
                    <i class="ki-duotone ki-check fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Save Changes
                </span>
                <span class="indicator-progress">
                    Saving...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>

    </div>
    </div>
    <!--end::Left panel-->

    <!--begin::Right panel-->
    <div class="col-lg-4">

        <!--begin::Notes card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7">Edit Notes</h6>
            </div>
            <div class="card-body pt-3">
                <ul class="text-muted fs-8 ps-4 mb-0">
                    <li class="mb-2">The student (admission) cannot be changed.</li>
                    <li class="mb-2">You can change the stream, year, term and status.</li>
                    <li>Enrolment ID: <strong>#<?= str_pad($enrolment['enrol_id'], 6, '0', STR_PAD_LEFT) ?></strong></li>
                </ul>
            </div>
        </div>
        <!--end::Notes card-->

        <!--begin::Core Subjects card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5 pb-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-light-primary fs-8 px-3 py-2">Core Subjects</span>
                    <span class="text-muted fs-8" id="core_count_badge">(<?= count($studentSubjects['core']) ?>)</span>
                </div>
            </div>
            <div class="card-body pt-0 pb-3" id="core_subjects_list">
                <?php if (empty($studentSubjects['core'])): ?>
                <div class="text-muted fs-8 text-center py-3" id="core_empty_msg">No core subjects added yet.</div>
                <?php else: ?>
                <?php foreach ($studentSubjects['core'] as $sub): ?>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-gray-100 subject-row" id="sub_row_<?= $sub['stud_sub_id'] ?>">
                    <span class="fw-semibold fs-7 text-gray-800"><?= esc($sub['subject_name']) ?></span>
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-subject"
                            data-stud-sub-id="<?= $sub['stud_sub_id'] ?>" title="Remove">
                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="card-footer border-0 pt-0 pb-4 px-5">
                <button type="button" class="btn btn-light-primary btn-sm w-100" id="btn_add_core"
                        data-bs-toggle="modal" data-bs-target="#modal_add_core">
                    <i class="ki-duotone ki-plus fs-4"><span class="path1"></span><span class="path2"></span></i>
                    Add Core Subjects
                </button>
            </div>
        </div>
        <!--end::Core Subjects card-->

        <!--begin::Optional Subjects card-->
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5 pb-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-light-warning fs-8 px-3 py-2">Optional Subjects</span>
                    <span class="text-muted fs-8" id="optional_count_badge">(<?= count($studentSubjects['optional']) ?>)</span>
                </div>
            </div>
            <div class="card-body pt-0 pb-3" id="optional_subjects_list">
                <?php if (empty($studentSubjects['optional'])): ?>
                <div class="text-muted fs-8 text-center py-3" id="optional_empty_msg">No optional subjects added yet.</div>
                <?php else: ?>
                <?php foreach ($studentSubjects['optional'] as $sub): ?>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-gray-100 subject-row" id="sub_row_<?= $sub['stud_sub_id'] ?>">
                    <div>
                        <span class="fw-semibold fs-7 text-gray-800"><?= esc($sub['subject_name']) ?></span>
                        <?php if (!empty($sub['option_num'])): ?>
                        <span class="badge badge-light-warning fs-9 ms-1">Grp <?= $sub['option_num'] ?></span>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-subject"
                            data-stud-sub-id="<?= $sub['stud_sub_id'] ?>" title="Remove">
                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="card-footer border-0 pt-0 pb-4 px-5">
                <button type="button" class="btn btn-light-warning btn-sm w-100" id="btn_add_optional"
                        data-bs-toggle="modal" data-bs-target="#modal_add_optional">
                    <i class="ki-duotone ki-plus fs-4"><span class="path1"></span><span class="path2"></span></i>
                    Add Optional Subjects
                </button>
            </div>
        </div>
        <!--end::Optional Subjects card-->

    </div>
    <!--end::Right panel-->

</div>
</div>
</div>

<!--begin::Add Core Subjects Modal-->
<div class="modal fade" id="modal_add_core" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-light-primary fs-8 px-3 py-2">Core Subjects</span>
                    <h5 class="modal-title fw-bold text-gray-800 fs-6 mb-0">Add Core Subjects</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4" id="core_modal_body">
                <div class="text-center py-6 text-muted fs-7" id="core_modal_loader">
                    <span class="spinner-border spinner-border-sm me-2 text-primary"></span>Loading...
                </div>
                <div id="core_modal_content" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <span class="text-muted fs-8 me-auto" id="core_modal_selected_count"></span>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_core_subjects">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Add Selected
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Core Subjects Modal-->

<!--begin::Add Optional Subjects Modal-->
<div class="modal fade" id="modal_add_optional" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-light-warning fs-8 px-3 py-2">Optional Subjects</span>
                    <h5 class="modal-title fw-bold text-gray-800 fs-6 mb-0">Add Optional Subjects</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4" id="optional_modal_body">
                <div class="text-center py-6 text-muted fs-7" id="optional_modal_loader">
                    <span class="spinner-border spinner-border-sm me-2 text-primary"></span>Loading...
                </div>
                <div id="optional_modal_content" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning btn-sm text-white" id="btn_save_optional_subjects">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Add Selected
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Optional Subjects Modal-->

    </div>
    <!--end::Right panel-->

</div>
</div>
</div>

<script>
"use strict";

const ENROL_ID = <?= (int) $enrolment['enrol_id'] ?>;

$('#edit_stream_select').select2({ placeholder: 'Select stream...', width: '100%' });

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Save enrolment ───────────────────────────────────────────────────
document.getElementById('btn_update_enrolment').addEventListener('click', function () {
    const btn      = this;
    const formData = new FormData(document.getElementById('edit_enrolment_form'));
    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;
    $.ajax({
        url: '<?= base_url('enrolment/update/' . $enrolment['enrol_id']) ?>',
        type: 'POST', data: formData, processData: false, contentType: false,
        success: function (res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                Swal.fire({ title: 'Updated!', text: res.message, icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(() => window.location.href = res.redirect);
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function () {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error', buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
});

// ── Remove subject ───────────────────────────────────────────────────
$(document).on('click', '.btn-remove-subject', function () {
    const studSubId = $(this).data('stud-sub-id');
    const row       = document.getElementById('sub_row_' + studSubId);
    Swal.fire({
        title: 'Remove Subject?',
        text: 'This subject will be removed from the student\'s enrolment.',
        icon: 'warning', showCancelButton: true, buttonsStyling: false,
        confirmButtonText: 'Yes, remove', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(function (result) {
        if (!result.isConfirmed) return;
        $.post('<?= base_url('enrolment/subject/remove') ?>/' + studSubId, function (res) {
            if (res.success) { row.remove(); updateSubjectCounts(); _availableCache = null; }
            else Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
        }, 'json');
    });
});

// ── Count badges & empty state ───────────────────────────────────────
function updateSubjectCounts() {
    const coreRows     = document.querySelectorAll('#core_subjects_list .subject-row').length;
    const optionalRows = document.querySelectorAll('#optional_subjects_list .subject-row').length;
    document.getElementById('core_count_badge').textContent     = '(' + coreRows + ')';
    document.getElementById('optional_count_badge').textContent = '(' + optionalRows + ')';
    const ce = document.getElementById('core_empty_msg');
    const oe = document.getElementById('optional_empty_msg');
    if (ce) ce.style.display = coreRows     === 0 ? '' : 'none';
    if (oe) oe.style.display = optionalRows === 0 ? '' : 'none';
}

// ── Available subjects (busted after each add/remove) ────────────────
let _availableCache = null;
function loadAvailable(callback) {
    if (_availableCache) { callback(_availableCache); return; }
    $.get('<?= base_url('enrolment/available-subjects') ?>/' + ENROL_ID, function (res) {
        if (res.success) { _availableCache = res; callback(res); }
    }, 'json');
}

// ── Append added subjects to the correct list ────────────────────────
function appendSubjectRows(subjects) {
    subjects.forEach(function (sub) {
        const type    = sub.subject_type === 'optional' ? 'optional' : 'core';
        const listEl  = document.getElementById(type === 'core' ? 'core_subjects_list' : 'optional_subjects_list');
        const emptyEl = listEl.querySelector('[id$="_empty_msg"]');
        if (emptyEl) emptyEl.remove();

        const badge = (type === 'optional' && sub.option_num)
            ? `<span class="badge badge-light-warning fs-9 ms-1">Grp ${sub.option_num}</span>` : '';

        listEl.insertAdjacentHTML('beforeend', `
            <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-gray-100 subject-row" id="sub_row_${sub.stud_sub_id}">
                <div>
                    <span class="fw-semibold fs-7 text-gray-800">${escHtml(sub.subject_name)}</span>${badge}
                </div>
                <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-subject"
                        data-stud-sub-id="${sub.stud_sub_id}" title="Remove">
                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                </button>
            </div>`);
    });
    updateSubjectCounts();
}

// ── Batch save helper ────────────────────────────────────────────────
function saveBatch(schSubIds, btn, modalId) {
    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('enrol_id', ENROL_ID);
    schSubIds.forEach(id => formData.append('sch_sub_ids[]', id));

    $.ajax({
        url: '<?= base_url('enrolment/subject/add-batch') ?>',
        type: 'POST', data: formData, processData: false, contentType: false,
        success: function (res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
                if (res.added && res.added.length > 0) appendSubjectRows(res.added);
                _availableCache = null;
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function () {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error', buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
}

// ── Core modal ───────────────────────────────────────────────────────
document.getElementById('modal_add_core').addEventListener('show.bs.modal', function () {
    const loader  = document.getElementById('core_modal_loader');
    const content = document.getElementById('core_modal_content');
    const counter = document.getElementById('core_modal_selected_count');
    loader.style.display = ''; content.style.display = 'none'; counter.textContent = '';

    loadAvailable(function (res) {
        loader.style.display = 'none';

        if (!res.core || res.core.length === 0) {
            content.innerHTML = '<div class="text-muted fs-8 text-center py-4">All core subjects are already added.</div>';
            content.style.display = '';
            return;
        }

        let html = `
            <div class="mb-3 pb-2 border-bottom border-gray-200">
                <label class="d-flex align-items-center gap-3 cursor-pointer">
                    <input type="checkbox" class="form-check-input mt-0" id="core_select_all">
                    <span class="fw-bold fs-7 text-gray-700">Select All</span>
                </label>
            </div>
            <div class="row g-3">`;
        res.core.forEach(function (s) {
            html += `
                <div class="col-12">
                    <label class="d-flex align-items-center gap-3 p-3 rounded-2 border border-dashed border-gray-300 cursor-pointer core-modal-item">
                        <input type="checkbox" class="form-check-input mt-0 core-modal-cb" value="${s.sch_sub_id}">
                        <span class="fw-semibold fs-7 text-gray-800">${escHtml(s.subject_name)}</span>
                    </label>
                </div>`;
        });
        html += '</div>';
        content.innerHTML = html;
        content.style.display = '';

        // Select All toggle
        document.getElementById('core_select_all').addEventListener('change', function () {
            const checked = this.checked;
            content.querySelectorAll('.core-modal-cb').forEach(cb => cb.checked = checked);
            const n = checked ? content.querySelectorAll('.core-modal-cb').length : 0;
            counter.textContent = n > 0 ? n + ' selected' : '';
        });

        // Live selection counter + sync Select All state
        content.addEventListener('change', function (e) {
            if (e.target.classList.contains('core-modal-cb')) {
                const all   = content.querySelectorAll('.core-modal-cb');
                const n     = content.querySelectorAll('.core-modal-cb:checked').length;
                const selAll = document.getElementById('core_select_all');
                selAll.checked       = n === all.length;
                selAll.indeterminate = n > 0 && n < all.length;
                counter.textContent  = n > 0 ? n + ' selected' : '';
            }
        });
    });
});

document.getElementById('btn_save_core_subjects').addEventListener('click', function () {
    const checked = [...document.querySelectorAll('.core-modal-cb:checked')].map(cb => cb.value);
    if (checked.length === 0) {
        Swal.fire({ title: 'Nothing selected', text: 'Please select at least one subject.', icon: 'warning', buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }
    saveBatch(checked, this, 'modal_add_core');
});

// ── Optional modal ───────────────────────────────────────────────────
document.getElementById('modal_add_optional').addEventListener('show.bs.modal', function () {
    const loader  = document.getElementById('optional_modal_loader');
    const content = document.getElementById('optional_modal_content');
    loader.style.display = ''; content.style.display = 'none';

    loadAvailable(function (res) {
        loader.style.display = 'none';

        if (!res.optional || res.optional.length === 0) {
            content.innerHTML = '<div class="text-muted fs-8 text-center py-4">All optional subjects are already added.</div>';
            content.style.display = '';
            return;
        }

        // Group by option_num
        const groups = {};
        res.optional.forEach(function (s) {
            if (!groups[s.option_num]) groups[s.option_num] = [];
            groups[s.option_num].push(s);
        });

        let html = ''; let idx = 1;
        Object.keys(groups).forEach(function (gnum) {
            html += `<div class="mb-4">
                <div class="text-muted fs-8 fw-semibold mb-2">Group ${idx++} — select one</div>
                <div class="row g-2">`;
            groups[gnum].forEach(function (s) {
                html += `
                    <div class="col-12">
                        <label class="d-flex align-items-center gap-3 p-3 rounded-2 border border-dashed border-gray-300 cursor-pointer optional-modal-item">
                            <input type="radio" class="form-check-input mt-0 optional-modal-radio" name="optional_group_${gnum}" value="${s.sch_sub_id}">
                            <span class="fw-semibold fs-7 text-gray-800">${escHtml(s.subject_name)}</span>
                        </label>
                    </div>`;
            });
            html += '</div></div>';
        });

        content.innerHTML = html;
        content.style.display = '';
    });
});

document.getElementById('btn_save_optional_subjects').addEventListener('click', function () {
    const checked = [...document.querySelectorAll('.optional-modal-radio:checked')].map(r => r.value);
    if (checked.length === 0) {
        Swal.fire({ title: 'Nothing selected', text: 'Please select at least one subject.', icon: 'warning', buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }
    saveBatch(checked, this, 'modal_add_optional');
});
</script>
