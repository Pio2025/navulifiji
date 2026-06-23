<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                View Subject Attendance
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">View Subject Attendance</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('attendance/subject/add') ?>" class="btn btn-sm btn-primary">
                <i class="ki-duotone ki-plus fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                Add Subject Attendance
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if (($error ?? null) === 'unauthorised_role'): ?>
    <div class="card shadow-sm">
        <div class="card-body p-10 text-center">
            <i class="ki-duotone ki-lock-3 fs-5x text-danger mb-5">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <h3 class="fs-2 fw-bold text-gray-900 mb-3">Access Denied</h3>
            <p class="text-gray-600 fs-5 mb-0">Your user role is not authorised to view student subject attendance records.</p>
        </div>
    </div>
    <?php else: ?>

    <!--begin::Toast-->
    <div aria-live="polite" aria-atomic="true" class="position-fixed" style="top:80px;right:20px;z-index:9999;min-width:320px;">
        <div id="att-toast" class="toast align-items-center border-0 text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-semibold d-flex align-items-center gap-2">
                    <i id="att-toast-icon" class="fs-4"></i>
                    <span id="att-toast-msg"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <!--end::Toast-->

    <!--begin::Selector Card-->
    <div class="card shadow-sm mb-6">
        <div class="card-body py-5">
            <div class="row g-4 align-items-end">
                <!--begin::Stream-->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Select Stream / Class</label>
                    <select id="stream_select" class="form-select form-select-solid">
                        <option value="">-- Choose a stream --</option>
                        <?php foreach ($streams as $s): ?>
                        <option value="<?= $s['stream_id'] ?>">
                            <?= esc($s['stream_name']) ?>
                            <?php if (!empty($s['level_name'])): ?>(<?= esc($s['level_name']) ?>)<?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($streams)): ?>
                    <div class="text-danger fs-7 mt-1">No streams found for your school.</div>
                    <?php endif; ?>
                </div>
                <!--end::Stream-->

                <!--begin::Subject-->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Select Subject</label>
                    <div id="subject-loading" class="d-none align-items-center gap-2 py-2">
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        <span class="text-muted fs-7">Loading subjects…</span>
                    </div>
                    <select id="subject_select" class="form-select form-select-solid" disabled>
                        <option value="">-- Select stream first --</option>
                    </select>
                </div>
                <!--end::Subject-->

                <!--begin::Status badges-->
                <div class="col-md-3 d-flex align-items-end gap-3">
                    <div id="cal-loading" class="d-none align-items-center gap-2">
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        <span class="text-muted fs-7">Loading attendance…</span>
                    </div>
                    <span id="cal-event-count" class="badge badge-light-success fs-7 px-4 py-2 d-none"></span>
                </div>
                <!--end::Status badges-->

                <div class="col-md-1 d-flex align-items-end justify-content-end">
                    <div class="text-muted fs-8 text-end">
                        <i class="ki-duotone ki-information-5 fs-5 me-1">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        Click any date to add
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Selector Card-->

    <!--begin::Calendar Card-->
    <div class="card shadow-sm mb-10">
        <div class="card-body p-6 pb-8">
            <div id="att_calendar"></div>
        </div>
    </div>
    <!--end::Calendar Card-->

    <!-- ============================================================
         MODAL 1 — Add Attendance (from calendar dateClick)
         ============================================================ -->
    <div class="modal fade" id="add-att-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <form id="add-att-form" class="modal-content" autocomplete="off">
                <div class="modal-header py-4 border-bottom">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">
                            <i class="ki-duotone ki-calendar-add fs-3 text-primary me-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Add Subject Attendance — <span id="add-modal-stream" class="text-primary"></span>
                        </h5>
                        <div class="text-muted fs-7 mt-1">
                            <span id="add-modal-subject-label" class="badge badge-light-primary me-2"></span>
                            <span id="add-modal-date-label"></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" id="add-modal-close-btn" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <input type="hidden" name="stream_id"       id="add-stream-id-input" />
                    <input type="hidden" name="subject_id"      id="add-subject-id-input" />
                    <input type="hidden" name="attendance_date" id="add-date-input" />

                    <div id="add-modal-loading" class="text-center py-15">
                        <div class="spinner-border text-primary mb-4" role="status" style="width:3rem;height:3rem;">
                            <span class="visually-hidden">Loading…</span>
                        </div>
                        <p class="text-muted fs-6">Loading students…</p>
                    </div>

                    <div id="add-modal-table-wrap" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 mb-0">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 min-w-55px rounded-start">Photo</th>
                                        <th class="min-w-180px">Student Name</th>
                                        <th class="min-w-200px">Attendance Note</th>
                                        <th class="min-w-160px">Status</th>
                                        <th class="min-w-90px rounded-end text-center">Files</th>
                                    </tr>
                                </thead>
                                <tbody id="add-modal-students"></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="add-modal-empty" class="d-none text-center py-12">
                        <i class="ki-duotone ki-people fs-5x text-muted mb-4">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                        <p class="text-muted fs-5 mb-0">No students enrolled in this stream for <?= date('Y') ?>.</p>
                    </div>

                    <div id="add-modal-error" class="d-none">
                        <div class="alert alert-danger m-6 mb-0"><span id="add-modal-error-msg"></span></div>
                    </div>
                </div>
                <div class="modal-footer py-3 border-top" id="add-modal-footer" style="display:none!important;">
                    <div class="me-auto">
                        <span class="badge badge-light-primary fs-7 px-3 py-2" id="add-modal-count-badge"></span>
                    </div>
                    <button type="button" class="btn btn-light" id="add-modal-close-btn2">Cancel</button>
                    <button type="button" id="btn-save-add" class="btn btn-success">
                        <i class="ki-duotone ki-check-circle fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Save Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================
         MODAL 2 — View/Edit Detail
         ============================================================ -->
    <div class="modal fade" id="att-detail-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header py-4">
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="detail-modal-title">Subject Attendance Detail</h5>
                        <div class="text-muted fs-7" id="detail-modal-subtitle"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="detail-modal-body">
                    <div id="detail-loading" class="text-center py-15">
                        <div class="spinner-border text-primary mb-4" role="status" style="width:3rem;height:3rem;">
                            <span class="visually-hidden">Loading…</span>
                        </div>
                        <p class="text-muted fs-6">Loading attendance records…</p>
                    </div>
                    <div id="detail-content"></div>
                </div>
                <div class="modal-footer py-3" id="detail-modal-footer" style="display:none!important;">
                    <button type="button" id="btn-delete-all-day" class="btn btn-danger me-auto">
                        <i class="ki-duotone ki-trash fs-4 me-1">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                        Delete All for This Day
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btn-save-all" class="btn btn-primary">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Save All Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         MODAL 3 — File Picker (shared)
         ============================================================ -->
    <div class="modal fade" id="file-picker-modal" tabindex="-1" aria-hidden="true" style="z-index:1070;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="ki-duotone ki-paper-clip fs-3 text-primary me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <span id="fp-modal-title">Files for</span> <span id="fp-student-name" class="text-primary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-8">
                    <div id="fp-drop-zone"
                         class="border border-2 border-dashed border-primary rounded p-10 text-center mb-6"
                         style="cursor:pointer; transition:background 0.2s;">
                        <i class="ki-duotone ki-file-up fs-4x text-primary mb-4">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <p class="fs-5 text-gray-700 mb-2">Drag &amp; drop files here</p>
                        <p class="text-muted fs-7 mb-4">or click to browse your computer</p>
                        <button type="button" id="fp-btn-browse" class="btn btn-light-primary btn-sm">Browse Files</button>
                        <input type="file" id="fp-file-input" multiple class="d-none" />
                    </div>
                    <div id="fp-file-list" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold text-gray-800">Selected Files</span>
                            <button type="button" id="fp-btn-clear" class="btn btn-sm btn-light-danger">Clear All</button>
                        </div>
                        <div id="fp-items-container" class="d-flex flex-column gap-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="fp-btn-action" class="btn btn-primary">Apply Files</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #file-picker-modal { z-index: 1070; }
        .modal-backdrop:nth-of-type(2) { z-index: 1065; }
        .modal-backdrop:nth-of-type(3) { z-index: 1069; }
        .fc-daygrid-event           { white-space: normal !important; cursor: pointer; }
        .fc-daygrid-event-harness   { margin-bottom: 2px !important; }
        .att-event-box              { padding: 4px 6px; line-height: 1.55; width: 100%; }
        .att-event-box .ev-detail   { font-size: 0.76em; opacity: 0.92; }
        .att-event-box .ev-pct      { font-size: 0.78em; font-weight: 600; margin-top: 1px; }
    </style>

    <?php endif; ?>
</div>
</div>
<!--end::Content-->

<?php if (($error ?? null) === null): ?>
<script>
(function () {
    'use strict';

    const BASE_URL     = '<?= base_url() ?>';
    const ATT_FILE_URL = '<?= base_url('uploads/attendance/') ?>';

    const streamSel      = document.getElementById('stream_select');
    const subjectSel     = document.getElementById('subject_select');
    const subjectLoading = document.getElementById('subject-loading');
    const calLoading     = document.getElementById('cal-loading');
    const calEvtBadge    = document.getElementById('cal-event-count');

    let viewStreamId  = null;
    let viewSubjectId = null;
    let viewDate      = null;

    let addStreamId   = null;
    let addSubjectId  = null;
    let addDate       = null;
    let pendingAddFiles = {};

    let fpMode        = 'add';
    let fpEnrolId     = null;
    let fpAttId       = null;
    let fpStudentName = null;
    let fpFiles       = [];

    // ── Toast ──────────────────────────────────────────────────────────
    const toastEl    = document.getElementById('att-toast');
    const toastBsObj = new bootstrap.Toast(toastEl, { delay: 5000 });

    function showToast(msg, type = 'warning') {
        const iconMap = { warning:'ki-duotone ki-shield-exclamation fs-4', danger:'ki-duotone ki-shield-cross fs-4', success:'ki-duotone ki-check-circle fs-4', info:'ki-duotone ki-information-5 fs-4' };
        const bgMap   = { warning:'bg-warning', danger:'bg-danger', success:'bg-success', info:'bg-info' };
        toastEl.className = 'toast align-items-center border-0 text-white ' + (bgMap[type] || bgMap.warning);
        document.getElementById('att-toast-icon').className = iconMap[type] || iconMap.warning;
        document.getElementById('att-toast-msg').textContent = msg;
        toastBsObj.show();
    }

    // ── Calendar ───────────────────────────────────────────────────────
    const calEl   = document.getElementById('att_calendar');
    const calendar = new FullCalendar.Calendar(calEl, {
        headerToolbar: { left:'prev,next today', center:'title', right:'dayGridMonth,dayGridWeek,listMonth' },
        initialView : 'dayGridMonth',
        height      : 'auto',
        nowIndicator: true,
        dayMaxEvents: false,
        eventDisplay: 'block',
        noEventsContent: 'Select a stream and subject above to load attendance.',

        eventContent: function(arg) {
            const p   = arg.event.extendedProps;
            const pct = p.pct_present;
            const pctColor = pct >= 90 ? 'rgba(255,255,255,1)' : pct >= 70 ? '#ffd980' : '#ffb3b3';
            return { html:
                '<div class="att-event-box">' +
                    '<div class="ev-detail">Present: ' + p.present_count + '</div>' +
                    '<div class="ev-detail">Absent: '  + p.absent_count  + '</div>' +
                    '<div class="ev-pct" style="color:' + pctColor + ';">' + pct + '% Present</div>' +
                '</div>'
            };
        },

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            const p       = info.event.extendedProps;
            viewStreamId  = p.stream_id;
            viewSubjectId = p.subject_id;
            viewDate      = p.att_date;
            openDetailModal(viewStreamId, viewSubjectId, viewDate, p.student_count, p.present_count, p.absent_count, p.pct_present);
        },

        dateClick: function(info) {
            const clickedDate = info.dateStr;
            const today       = new Date().toISOString().split('T')[0];

            if (!streamSel.value) {
                showToast('Please select a stream first.', 'warning');
                streamSel.focus();
                return;
            }
            if (!subjectSel.value) {
                showToast('Please select a subject first.', 'warning');
                subjectSel.focus();
                return;
            }
            if (clickedDate > today) {
                showToast('Cannot add attendance for a future date.', 'warning');
                return;
            }

            const streamId  = streamSel.value;
            const subjectId = subjectSel.value;

            fetch(BASE_URL + 'attendance/subject/check?stream_id=' + streamId +
                  '&subject_id=' + subjectId + '&date=' + clickedDate)
                .then(r => r.json())
                .then(function(res) {
                    if (res.exists) {
                        showToast(
                            'Attendance for this subject and date has already been recorded (' +
                            res.count + ' student' + (res.count !== 1 ? 's' : '') +
                            '). Click the event to view or edit.',
                            'info'
                        );
                        return;
                    }
                    openAddAttModal(streamId, subjectId, clickedDate);
                })
                .catch(function() { openAddAttModal(streamId, subjectId, clickedDate); });
        },
    });
    calendar.render();

    // ── Stream → load subjects ─────────────────────────────────────────
    streamSel.addEventListener('change', function () {
        const streamId = this.value;
        subjectSel.innerHTML = '<option value="">-- Select a subject --</option>';
        subjectSel.disabled  = true;
        calendar.removeAllEventSources();
        calEvtBadge.classList.add('d-none');

        if (!streamId) return;

        subjectLoading.classList.remove('d-none');
        subjectLoading.classList.add('d-flex');

        fetch(BASE_URL + 'attendance/subject/subjects?stream_id=' + streamId)
            .then(r => r.json())
            .then(function(res) {
                subjectLoading.classList.add('d-none');
                subjectLoading.classList.remove('d-flex');

                if (!res.success || !res.subjects.length) {
                    subjectSel.innerHTML = '<option value="">No subjects found for this stream</option>';
                    return;
                }

                let coreHtml = '', optHtml = '';
                res.subjects.forEach(function(s) {
                    const opt = '<option value="' + s.sch_sub_id + '">' + escHtml(s.subject_name) + '</option>';
                    if (s.subject_type === 'Core') coreHtml += opt; else optHtml += opt;
                });

                let html = '<option value="">-- Select a subject --</option>';
                if (coreHtml) html += '<optgroup label="Core Subjects">'     + coreHtml + '</optgroup>';
                if (optHtml)  html += '<optgroup label="Optional Subjects">' + optHtml  + '</optgroup>';

                subjectSel.innerHTML = html;
                subjectSel.disabled  = false;
            })
            .catch(function() {
                subjectLoading.classList.add('d-none');
                subjectLoading.classList.remove('d-flex');
                subjectSel.innerHTML = '<option value="">Failed to load subjects</option>';
            });
    });

    // ── Subject → load calendar ────────────────────────────────────────
    subjectSel.addEventListener('change', function () {
        const streamId  = streamSel.value;
        const subjectId = this.value;
        calendar.removeAllEventSources();
        calEvtBadge.classList.add('d-none');

        if (!streamId || !subjectId) return;

        calLoading.classList.remove('d-none');
        calLoading.classList.add('d-flex');

        calendar.addEventSource({
            url:         BASE_URL + 'attendance/subject/events',
            extraParams: { stream_id: streamId, subject_id: subjectId },
            failure: function() {
                calLoading.classList.add('d-none');
                calLoading.classList.remove('d-flex');
                showToast('Failed to load calendar events.', 'danger');
            },
            success: function(events) {
                calLoading.classList.add('d-none');
                calLoading.classList.remove('d-flex');
                const cnt = events.length;
                calEvtBadge.textContent = cnt + ' day' + (cnt !== 1 ? 's' : '') + ' recorded';
                calEvtBadge.classList.remove('d-none');
            },
        });
    });

    // ── ADD MODAL ──────────────────────────────────────────────────────
    const addModalEl    = document.getElementById('add-att-modal');
    const addModalBs    = new bootstrap.Modal(addModalEl);
    const addModalLoad  = document.getElementById('add-modal-loading');
    const addModalTable = document.getElementById('add-modal-table-wrap');
    const addModalEmpty = document.getElementById('add-modal-empty');
    const addModalErr   = document.getElementById('add-modal-error');
    const addModalFoot  = document.getElementById('add-modal-footer');

    function resetAddModal() {
        addModalLoad.style.display  = '';
        addModalTable.classList.add('d-none');
        addModalEmpty.classList.add('d-none');
        addModalErr.classList.add('d-none');
        addModalFoot.style.setProperty('display', 'none', 'important');
        document.getElementById('add-modal-students').innerHTML = '';
        pendingAddFiles = {};
    }

    function openAddAttModal(streamId, subjectId, date) {
        addStreamId  = streamId;
        addSubjectId = subjectId;
        addDate      = date;

        const streamName  = streamSel.options[streamSel.selectedIndex].text;
        const subjectName = subjectSel.options[subjectSel.selectedIndex].text;

        document.getElementById('add-modal-stream').textContent        = streamName;
        document.getElementById('add-modal-subject-label').textContent = subjectName;
        document.getElementById('add-modal-date-label').textContent    = formatDate(date);
        document.getElementById('add-stream-id-input').value           = streamId;
        document.getElementById('add-subject-id-input').value          = subjectId;
        document.getElementById('add-date-input').value                = date;

        resetAddModal();
        addModalBs.show();

        fetch(BASE_URL + 'attendance/subject/students?stream_id=' + streamId)
            .then(r => r.json())
            .then(function(res) {
                addModalLoad.style.display = 'none';
                if (!res.success) {
                    document.getElementById('add-modal-error-msg').textContent = res.message || 'Failed to load students.';
                    addModalErr.classList.remove('d-none');
                    return;
                }
                if (!res.count) { addModalEmpty.classList.remove('d-none'); return; }

                document.getElementById('add-modal-students').innerHTML = res.html;
                document.getElementById('add-modal-count-badge').textContent = res.count + ' student' + (res.count !== 1 ? 's' : '');
                addModalTable.classList.remove('d-none');
                addModalFoot.style.removeProperty('display');
                bindAddUploadButtons();
            })
            .catch(function() {
                addModalLoad.style.display = 'none';
                document.getElementById('add-modal-error-msg').textContent = 'An error occurred loading students. Please try again.';
                addModalErr.classList.remove('d-none');
            });
    }

    document.getElementById('add-modal-close-btn').addEventListener('click',  () => addModalBs.hide());
    document.getElementById('add-modal-close-btn2').addEventListener('click', () => addModalBs.hide());

    function bindAddUploadButtons() {
        addModalEl.querySelectorAll('.btn-upload-files').forEach(function(btn) {
            btn.addEventListener('click', function() {
                fpMode        = 'add';
                fpEnrolId     = this.dataset.enrolId;
                fpStudentName = this.dataset.studentName;
                fpFiles       = [...(pendingAddFiles[fpEnrolId] || [])];
                openFilePicker('Upload Files for', fpStudentName, 'Apply Files');
            });
        });
    }

    document.getElementById('btn-save-add').addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Saving…';

        const fd = new FormData(document.getElementById('add-att-form'));
        for (const [enrolId, files] of Object.entries(pendingAddFiles)) {
            files.forEach(f => fd.append('files_' + enrolId + '[]', f));
        }

        fetch(BASE_URL + 'attendance/subject/save-ajax', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(function(res) {
                btn.disabled = false;
                btn.innerHTML = '<i class="ki-duotone ki-check-circle fs-3 me-1"><span class="path1"></span><span class="path2"></span></i> Save Attendance';
                if (res.success) {
                    addModalBs.hide();
                    refreshCalendarEvents();
                    showToast(res.message || 'Attendance saved!', 'success');
                } else {
                    showToast(res.message || 'Failed to save attendance.', 'danger');
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = 'Save Attendance';
                showToast('An unexpected error occurred.', 'danger');
            });
    });

    // ── DETAIL MODAL ───────────────────────────────────────────────────
    function openDetailModal(streamId, subjectId, date, count, present, absent, pct) {
        const modal     = new bootstrap.Modal(document.getElementById('att-detail-modal'));
        const titleEl   = document.getElementById('detail-modal-title');
        const subEl     = document.getElementById('detail-modal-subtitle');
        const loadingEl = document.getElementById('detail-loading');
        const contentEl = document.getElementById('detail-content');
        const footerEl  = document.getElementById('detail-modal-footer');

        const subjectName = subjectSel.options[subjectSel.selectedIndex].text;
        titleEl.textContent = streamSel.options[streamSel.selectedIndex].text + ' — ' + subjectName;
        subEl.innerHTML     = formatDate(date) +
            ' &nbsp;·&nbsp; <strong>' + count + '</strong> student' + (count !== 1 ? 's' : '') +
            ' &nbsp;·&nbsp; Present: <strong>' + (present ?? '—') + '</strong>' +
            ' &nbsp;·&nbsp; Absent: <strong>'  + (absent  ?? '—') + '</strong>' +
            ' &nbsp;·&nbsp; <strong>' + (pct ?? '—') + '%</strong> present';

        loadingEl.style.display = '';
        contentEl.innerHTML     = '';
        footerEl.style.setProperty('display', 'none', 'important');

        modal.show();

        fetch(BASE_URL + 'attendance/subject/detail?stream_id=' + streamId +
              '&subject_id=' + subjectId + '&date=' + date)
            .then(r => r.json())
            .then(function(res) {
                loadingEl.style.display = 'none';
                if (!res.success) {
                    contentEl.innerHTML = '<div class="alert alert-danger m-6">' + escHtml(res.message || 'Failed to load.') + '</div>';
                    return;
                }
                contentEl.innerHTML = res.html;
                footerEl.style.removeProperty('display');
                bindDetailActions();
            })
            .catch(function() {
                loadingEl.style.display = 'none';
                contentEl.innerHTML = '<div class="alert alert-danger m-6">An error occurred loading attendance data.</div>';
            });
    }

    function bindDetailActions() {
        // Save individual row
        document.querySelectorAll('#detail-content .btn-save-row').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id   = this.dataset.id;
                const row  = document.getElementById('att-row-' + id);
                const status = row.querySelector('.att-status-select').value;
                const note   = row.querySelector('.att-note-input').value;

                const origBtn = this;
                origBtn.disabled = true;
                origBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Saving…';

                const fd = new FormData();
                fd.append('attendance_status', status);
                fd.append('attendance_note',   note);

                fetch(BASE_URL + 'attendance/subject/update/' + id, { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            origBtn.className = 'btn btn-sm btn-success btn-save-row';
                            origBtn.innerHTML = '<i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i> Saved';
                            setTimeout(() => {
                                origBtn.className = 'btn btn-sm btn-light-primary btn-save-row';
                                origBtn.innerHTML = 'Save';
                                origBtn.disabled  = false;
                            }, 2000);
                        } else {
                            origBtn.disabled = false;
                            origBtn.innerHTML = 'Save';
                        }
                    })
                    .catch(() => { origBtn.disabled = false; origBtn.innerHTML = 'Save'; });
            });
        });

        // Delete individual row
        document.querySelectorAll('#detail-content .btn-delete-row').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Delete Record?',
                    text: 'This student\'s subject attendance record will be permanently removed.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#f1416c',
                    reverseButtons: true,
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    fetch(BASE_URL + 'attendance/subject/delete/' + id, { method: 'POST' })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) {
                                const row = document.getElementById('att-row-' + id);
                                if (row) row.remove();
                                refreshCalendarEvents();
                            }
                        });
                });
            });
        });

        // Upload more files
        document.querySelectorAll('#detail-content .btn-upload-more').forEach(function(btn) {
            btn.addEventListener('click', function() {
                fpMode        = 'upload';
                fpAttId       = this.dataset.attId;
                fpStudentName = this.dataset.studentName;
                fpFiles       = [];
                openFilePicker('Upload Files for', fpStudentName, 'Upload Files');
            });
        });

        // Delete individual file
        document.querySelectorAll('#detail-content .btn-delete-file').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const fileId   = this.dataset.fileId;
                const fileItem = document.getElementById('file-item-' + fileId);
                Swal.fire({
                    title: 'Delete File?',
                    text: 'This file will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#f1416c',
                    reverseButtons: true,
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    fetch(BASE_URL + 'attendance/subject/delete-file/' + fileId, { method: 'POST' })
                        .then(r => r.json())
                        .then(res => { if (res.success && fileItem) fileItem.remove(); });
                });
            });
        });
    }

    document.getElementById('btn-save-all').addEventListener('click', function() {
        document.querySelectorAll('#detail-content .btn-save-row').forEach(b => b.click());
    });

    document.getElementById('btn-delete-all-day').addEventListener('click', function() {
        Swal.fire({
            title: 'Delete All Records?',
            text: 'All subject attendance records for this day will be permanently deleted. This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete all',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f1416c',
            reverseButtons: true,
        }).then(function(result) {
            if (!result.isConfirmed) return;
            const fd = new FormData();
            fd.append('stream_id',  viewStreamId);
            fd.append('subject_id', viewSubjectId);
            fd.append('date',       viewDate);
            fetch(BASE_URL + 'attendance/subject/delete-all-day', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        bootstrap.Modal.getInstance(document.getElementById('att-detail-modal')).hide();
                        refreshCalendarEvents();
                    }
                });
        });
    });

    // ── FILE PICKER ────────────────────────────────────────────────────
    const fpDropZone  = document.getElementById('fp-drop-zone');
    const fpFileInput = document.getElementById('fp-file-input');
    const fpItemsCont = document.getElementById('fp-items-container');
    const fpListEl    = document.getElementById('fp-file-list');
    let fpModalBs     = null;

    function openFilePicker(titlePrefix, studentName, actionLabel) {
        document.getElementById('fp-modal-title').textContent  = titlePrefix;
        document.getElementById('fp-student-name').textContent = studentName;
        document.getElementById('fp-btn-action').textContent   = actionLabel;
        renderFpList();
        if (!fpModalBs) fpModalBs = new bootstrap.Modal(document.getElementById('file-picker-modal'));
        fpModalBs.show();
    }

    fpDropZone.addEventListener('dragover', e => { e.preventDefault(); fpDropZone.style.background = '#f0f4ff'; });
    fpDropZone.addEventListener('dragleave', () => { fpDropZone.style.background = ''; });
    fpDropZone.addEventListener('drop', function(e) {
        e.preventDefault(); fpDropZone.style.background = '';
        fpFiles = fpFiles.concat(Array.from(e.dataTransfer.files)); renderFpList();
    });
    document.getElementById('fp-btn-browse').addEventListener('click', () => fpFileInput.click());
    fpDropZone.addEventListener('click', function(e) {
        if (e.target === fpDropZone || e.target.tagName === 'P' || e.target.tagName === 'I') fpFileInput.click();
    });
    fpFileInput.addEventListener('change', function() {
        fpFiles = fpFiles.concat(Array.from(this.files)); renderFpList(); this.value = '';
    });
    document.getElementById('fp-btn-clear').addEventListener('click', function() { fpFiles = []; renderFpList(); });

    function renderFpList() {
        fpItemsCont.innerHTML = '';
        if (!fpFiles.length) { fpListEl.classList.add('d-none'); return; }
        fpListEl.classList.remove('d-none');
        fpFiles.forEach(function(f, idx) {
            const size = f.size < 1048576 ? (f.size/1024).toFixed(1)+' KB' : (f.size/1048576).toFixed(1)+' MB';
            const el   = document.createElement('div');
            el.className = 'd-flex align-items-center justify-content-between border border-gray-200 rounded px-4 py-3';
            el.innerHTML =
                '<div class="d-flex align-items-center gap-3">' +
                    '<i class="ki-duotone '+getFileIcon(f.name)+' fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>' +
                    '<div><div class="fw-semibold fs-7">'+escHtml(f.name)+'</div><div class="text-muted fs-8">'+size+'</div></div>' +
                '</div>' +
                '<button type="button" class="btn btn-sm btn-icon btn-light-danger" data-idx="'+idx+'">' +
                    '<i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>' +
                '</button>';
            el.querySelector('button').addEventListener('click', function() { fpFiles.splice(parseInt(this.dataset.idx),1); renderFpList(); });
            fpItemsCont.appendChild(el);
        });
    }

    document.getElementById('fp-btn-action').addEventListener('click', function() {
        if (!fpFiles.length) { fpModalBs.hide(); return; }

        if (fpMode === 'add') {
            pendingAddFiles[fpEnrolId] = [...fpFiles];
            if (typeof DataTransfer !== 'undefined') {
                const dt = new DataTransfer();
                fpFiles.forEach(f => dt.items.add(f));
                const hiddenInput = addModalEl.querySelector('#fi-' + fpEnrolId);
                if (hiddenInput) hiddenInput.files = dt.files;
            }
            const badge = addModalEl.querySelector('#file-badge-' + fpEnrolId);
            if (badge) { badge.textContent = fpFiles.length; badge.classList.toggle('d-none', fpFiles.length === 0); }
            fpModalBs.hide();

        } else {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Uploading…';

            const fd = new FormData();
            fpFiles.forEach(f => fd.append('upload_files[]', f));

            fetch(BASE_URL + 'attendance/subject/upload-files/' + fpAttId, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(function(res) {
                    btn.disabled = false;
                    btn.innerHTML = 'Upload Files';
                    if (res.success) {
                        const fileListEl = document.getElementById('file-list-' + fpAttId);
                        if (fileListEl && res.files) {
                            res.files.forEach(f => fileListEl.insertAdjacentHTML('beforeend', buildFileItem(f)));
                            bindDetailActions();
                        }
                        fpFiles = [];
                        fpModalBs.hide();
                        showToast('Files uploaded successfully.', 'success');
                    } else {
                        showToast('Upload failed. Please try again.', 'danger');
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    btn.innerHTML = 'Upload Files';
                    showToast('An error occurred during upload.', 'danger');
                });
        }
    });

    // ── Helpers ────────────────────────────────────────────────────────
    function refreshCalendarEvents() {
        const streamId  = streamSel.value;
        const subjectId = subjectSel.value;
        if (!streamId || !subjectId) return;
        calendar.removeAllEventSources();
        calendar.addEventSource({
            url:         BASE_URL + 'attendance/subject/events',
            extraParams: { stream_id: streamId, subject_id: subjectId },
            success: function(events) {
                const cnt = events.length;
                calEvtBadge.textContent = cnt + ' day' + (cnt !== 1 ? 's' : '') + ' recorded';
                calEvtBadge.classList.remove('d-none');
            },
        });
    }

    function buildFileItem(f) {
        const url = ATT_FILE_URL + f.stud_att_file_src;
        return '<div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light" id="file-item-' + f.stud_att_file_id + '">' +
            '<a href="' + url + '" target="_blank" class="d-flex align-items-center gap-2 text-gray-700 text-hover-primary">' +
                '<i class="ki-duotone ' + getFileIcon(f.stud_att_file_src) + ' fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>' +
                '<span class="fs-7">' + escHtml(f.stud_att_file_src) + '</span>' +
            '</a>' +
            '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete-file" data-file-id="' + f.stud_att_file_id + '">' +
                '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span>' +
                '<span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
            '</button>' +
        '</div>';
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-FJ', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    }

    function getFileIcon(name) {
        const ext = (name || '').split('.').pop().toLowerCase();
        if (['jpg','jpeg','png','gif','webp'].includes(ext)) return 'ki-picture';
        if (ext === 'pdf')                                    return 'ki-file-pdf';
        if (['doc','docx'].includes(ext))                     return 'ki-file-doc';
        if (['xls','xlsx'].includes(ext))                     return 'ki-file-sheet';
        return 'ki-file';
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

})();
</script>
<?php endif; ?>
