<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Add Enrolment
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
                <li class="breadcrumb-item text-muted">Add</li>
            </ul>
        </div>
        <a href="<?= base_url('enrolment') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back to List
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<div class="row g-6">

    <!--begin::Left panel-->
    <div class="col-lg-8">
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">

        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4">New Enrolment</h3>
            </div>
        </div>

        <div class="card-body">
            <form id="add_enrolment_form">
                <input type="hidden" name="has_subjects" id="has_subjects" value="0">

                <!--begin::Student-->
                <div class="mb-7">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="w-4px h-20px bg-primary rounded-2 d-inline-block flex-shrink-0"></span>
                        <span class="fw-bold text-gray-800 fs-6">Student <span class="text-danger">*</span></span>
                    </div>
                    <label class="form-label fw-semibold fs-7 required">Select Student</label>
                    <select class="form-select form-select-sm" name="admission_id_fk" id="admission_select" required>
                        <option value="">— Select student —</option>
                        <?php foreach ($eligibleAdmissions as $adm): ?>
                        <option value="<?= $adm['admission_id'] ?>"
                                data-sch="<?= (int)$adm['sch_id_fk'] ?>"
                                data-fname="<?= esc($adm['fname']) ?>"
                                data-lname="<?= esc($adm['lname']) ?>"
                                data-email="<?= esc($adm['email'] ?? '') ?>">
                            <?= esc(trim($adm['fname'] . ' ' . ($adm['oname'] ? $adm['oname'] . ' ' : '') . $adm['lname'])) ?>
                            <?php if ($isSuperAdmin): ?>(<?= esc($adm['sch_name'] ?? '') ?>)<?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <!--begin::Student preview-->
                    <div id="student_preview" class="mt-4 p-4 bg-light-primary rounded-3" style="display:none;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="symbol symbol-40px symbol-circle flex-shrink-0">
                                <div class="symbol-label bg-primary text-white fw-bold fs-6" id="preview_initials">??</div>
                            </div>
                            <div>
                                <div class="fw-bold text-gray-800 fs-7" id="preview_name">—</div>
                                <div class="text-muted fs-8" id="preview_email">—</div>
                            </div>
                        </div>
                    </div>
                    <!--end::Student preview-->
                </div>
                <!--end::Student-->

                <!--begin::Stream-->
                <div class="mb-7">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="w-4px h-20px bg-primary rounded-2 d-inline-block flex-shrink-0"></span>
                        <span class="fw-bold text-gray-800 fs-6">Stream / Class <span class="text-danger">*</span></span>
                    </div>
                    <label class="form-label fw-semibold fs-7 required">Stream</label>
                    <select class="form-select form-select-sm" name="stream_id_fk" id="stream_select" required disabled>
                        <option value="">— Select student first —</option>
                    </select>
                    <div id="stream_loader" class="text-muted fs-8 mt-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm me-1 text-primary"></span> Loading streams...
                    </div>
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
                            <input type="number" class="form-control form-control-sm"
                                   name="enrol_year" id="enrol_year"
                                   value="<?= $currentYear ?>"
                                   min="2000" max="2099" required />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7 required">Term</label>
                            <select class="form-select form-select-sm" name="enrol_term" required>
                                <?php for ($t = 1; $t <= 3; $t++): ?>
                                <option value="<?= $t ?>" <?= (int)($currentTerm ?? 1) === $t ? 'selected' : '' ?>>
                                    Term <?= $t ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7 required">Date</label>
                            <input type="date" class="form-control form-control-sm"
                                   name="enrol_date"
                                   value="<?= date('Y-m-d') ?>" required />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7 required">Status</label>
                            <select class="form-select form-select-sm" name="enrol_status" required>
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
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
                              placeholder="Any notes about this enrolment..."></textarea>
                </div>
                <!--end::Note-->

            </form>
        </div>

        <div class="card-footer d-flex justify-content-end gap-3 py-4">
            <a href="<?= base_url('enrolment') ?>" class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_save_enrolment">
                <span class="indicator-label">
                    <i class="ki-duotone ki-check fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Save Enrolment
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
                <h6 class="card-title fw-bold text-gray-800 fs-7">Notes</h6>
            </div>
            <div class="card-body pt-3">
                <ul class="text-muted fs-8 ps-4 mb-0">
                    <li class="mb-2">Only students with an <strong>Active</strong> admission and no current active enrolment appear in the list.</li>
                    <li class="mb-2">Select a student first — streams will load for their school.</li>
                    <li>Subjects (core &amp; optional) load after you choose a stream.</li>
                </ul>
            </div>
        </div>
        <!--end::Notes card-->

        <!--begin::Subjects card-->
        <div class="card shadow-sm" id="subjects_card" style="border:1px solid #E4E6EF; border-radius:4px; display:none;">
            <div class="card-header border-0 pt-5 pb-3">
                <h6 class="card-title fw-bold text-gray-800 fs-7">Subjects</h6>
            </div>
            <div class="card-body pt-0 pb-4" id="subjects_body">
                <div class="text-center py-4 text-muted fs-8" id="subjects_loader" style="display:none;">
                    <span class="spinner-border spinner-border-sm me-1 text-primary"></span> Loading subjects...
                </div>
                <div id="subjects_content"></div>
            </div>
        </div>
        <!--end::Subjects card-->

    </div>
    <!--end::Right panel-->

</div>
</div>
</div>

<script>
"use strict";

var STREAMS_URL  = '<?= base_url('enrolment/streams/') ?>';
var SUBJECTS_URL = '<?= base_url('enrolment/subjects/') ?>';
var SESSION_SCH  = <?= (int)($sessionSchId ?? 0) ?>;

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Student select ───────────────────────────────────────────────────
$('#admission_select').select2({ placeholder: '— Select student —', width: '100%' });

$('#admission_select').on('change', function () {
    var opt   = this.options[this.selectedIndex];
    var schId = parseInt(opt.dataset.sch) || 0;

    // Reset streams and subjects
    var streamSel = document.getElementById('stream_select');
    streamSel.innerHTML = '<option value="">— Select student first —</option>';
    streamSel.disabled  = true;
    document.getElementById('subjects_card').style.display = 'none';
    document.getElementById('subjects_content').innerHTML  = '';
    document.getElementById('has_subjects').value          = '0';
    document.getElementById('student_preview').style.display = 'none';

    if (!this.value) return;

    // Guard: student must have a valid school admission
    if (!schId) {
        $('#admission_select').val('').trigger('change');
        Swal.fire({
            title: 'No Active Admission',
            text: 'This student does not have an active school admission. Please admit the student first before enrolling.',
            icon: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: 'Go to Admission',
            cancelButtonText: 'Cancel',
            customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-light' }
        }).then(function (result) {
            if (result.isConfirmed) window.location.href = '<?= base_url('admission/add') ?>';
        });
        return;
    }

    // Update preview
    var fname    = opt.dataset.fname || '';
    var lname    = opt.dataset.lname || '';
    var email    = opt.dataset.email || '';
    var initials = ((fname[0] || '') + (lname[0] || '')).toUpperCase() || '??';
    document.getElementById('preview_initials').textContent = initials;
    document.getElementById('preview_name').textContent     = (fname + ' ' + lname).trim();
    document.getElementById('preview_email').textContent    = email || '—';
    document.getElementById('student_preview').style.display = '';

    // Load streams for the student's school only
    streamSel.innerHTML = '<option value="">— Loading... —</option>';
    document.getElementById('stream_loader').style.display = '';
    $.get(STREAMS_URL + schId, function (res) {
        document.getElementById('stream_loader').style.display = 'none';
        streamSel.innerHTML = '<option value="">— Select stream —</option>';
        if (res.success && res.streams && res.streams.length > 0) {
            var lastLevel = '';
            res.streams.forEach(function (s) {
                var level = s.level_name || 'Unknown Level';
                if (level !== lastLevel) {
                    if (lastLevel) streamSel.insertAdjacentHTML('beforeend', '</optgroup>');
                    streamSel.insertAdjacentHTML('beforeend', '<optgroup label="' + escHtml(level) + '">');
                    lastLevel = level;
                }
                streamSel.insertAdjacentHTML('beforeend',
                    '<option value="' + s.stream_id + '">' + escHtml(s.stream_name) + '</option>');
            });
            if (lastLevel) streamSel.insertAdjacentHTML('beforeend', '</optgroup>');
            streamSel.disabled = false;
            $('#stream_select').trigger('change.select2');
        } else {
            streamSel.innerHTML = '<option value="">— No streams available for this school —</option>';
        }
    }, 'json').fail(function () {
        document.getElementById('stream_loader').style.display = 'none';
        streamSel.innerHTML = '<option value="">— Failed to load streams —</option>';
    });
});

// ── Stream select ────────────────────────────────────────────────────
$('#stream_select').select2({ placeholder: '— Select student first —', width: '100%' });

$('#stream_select').on('change', function () {
    var streamId = parseInt(this.value) || 0;

    document.getElementById('subjects_content').innerHTML = '';
    document.getElementById('has_subjects').value         = '0';

    if (!streamId) {
        document.getElementById('subjects_card').style.display = 'none';
        return;
    }

    document.getElementById('subjects_card').style.display  = '';
    document.getElementById('subjects_loader').style.display = '';

    $.get(SUBJECTS_URL + streamId, function (res) {
        document.getElementById('subjects_loader').style.display = 'none';
        var html = '';

        if (res.success && ((res.core && res.core.length) || (res.optional && res.optional.length))) {
            document.getElementById('has_subjects').value = '1';

            if (res.core && res.core.length) {
                html += '<div class="mb-5">';
                html += '<div class="d-flex align-items-center gap-2 mb-3">';
                html += '<span class="badge badge-light-primary fs-9 px-3 py-1">Core Subjects</span>';
                html += '<span class="text-danger fs-9">* select all</span>';
                html += '</div>';
                res.core.forEach(function (s) {
                    html += '<label class="d-flex align-items-center gap-3 py-2 border-bottom border-gray-100 cursor-pointer">';
                    html += '<input type="checkbox" class="form-check-input mt-0 core-cb" name="core_subjects[]" value="' + s.sch_sub_id + '" checked>';
                    html += '<span class="fw-semibold fs-7 text-gray-800">' + escHtml(s.subject_name) + '</span>';
                    html += '</label>';
                });
                html += '</div>';
            }

            if (res.optional && res.optional.length) {
                var groups = {};
                res.optional.forEach(function (s) {
                    if (!groups[s.option_num]) groups[s.option_num] = [];
                    groups[s.option_num].push(s);
                });
                var idx = 1;
                Object.keys(groups).forEach(function (gnum) {
                    html += '<div class="mb-4">';
                    html += '<div class="d-flex align-items-center gap-2 mb-2">';
                    html += '<span class="badge badge-light-warning fs-9 px-3 py-1">Optional Group ' + idx++ + '</span>';
                    html += '<span class="text-muted fs-9">select one</span>';
                    html += '</div>';
                    groups[gnum].forEach(function (s) {
                        html += '<label class="d-flex align-items-center gap-3 py-2 border-bottom border-gray-100 cursor-pointer">';
                        html += '<input type="radio" class="form-check-input mt-0" name="optional_group_' + gnum + '" value="' + s.sch_sub_id + '">';
                        html += '<span class="fw-semibold fs-7 text-gray-800">' + escHtml(s.subject_name) + '</span>';
                        html += '</label>';
                    });
                    html += '</div>';
                });
            }
        } else {
            html = '<div class="text-muted fs-8 text-center py-4">No subjects configured for this stream.</div>';
        }

        document.getElementById('subjects_content').innerHTML = html;
    }, 'json').fail(function () {
        document.getElementById('subjects_loader').style.display = 'none';
        document.getElementById('subjects_content').innerHTML =
            '<div class="text-danger fs-8">Failed to load subjects.</div>';
    });
});

// ── Submit ───────────────────────────────────────────────────────────
document.getElementById('btn_save_enrolment').addEventListener('click', function () {
    var btn      = this;
    var formData = new FormData(document.getElementById('add_enrolment_form'));

    if (!formData.get('admission_id_fk')) {
        Swal.fire({ title: 'Missing Student', text: 'Please select a student.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK',
            customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }
    if (!formData.get('stream_id_fk')) {
        Swal.fire({ title: 'Missing Stream', text: 'Please select a stream.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK',
            customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url: '<?= base_url('enrolment/store') ?>',
        type: 'POST', data: formData, processData: false, contentType: false,
        success: function (res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                Swal.fire({ title: 'Saved!', text: res.message, icon: 'success',
                    timer: 1500, showConfirmButton: false })
                    .then(function () { window.location.href = res.redirect; });
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close',
                    customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function () {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'Close',
                customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
});
</script>
