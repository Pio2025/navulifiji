<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Add Daily Attendance
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Attendance</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Add Daily Attendance</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if ($error === 'unauthorised_role'): ?>
    <!--begin::Unauthorised Role Notice-->
    <div class="card shadow-sm">
        <div class="card-body p-10 text-center">
            <i class="ki-duotone ki-lock-3 fs-5x text-danger mb-5">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <h3 class="fs-2 fw-bold text-gray-900 mb-3">Access Denied</h3>
            <p class="text-gray-600 fs-5 mb-0">
                Your user role is not authorised to add student daily attendance records.
            </p>
        </div>
    </div>
    <!--end::Unauthorised Role Notice-->

    <?php elseif ($error === 'no_admission'): ?>
    <!--begin::No Admission Notice-->
    <div class="card shadow-sm">
        <div class="card-body p-10 text-center">
            <i class="ki-duotone ki-information-5 fs-5x text-warning mb-5">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <h3 class="fs-2 fw-bold text-gray-900 mb-3">No Active Admission</h3>
            <p class="text-gray-600 fs-5 mb-0">
                You do not have an active admission to a school, therefore you are not authorised to add student daily attendance records.
            </p>
        </div>
    </div>
    <!--end::No Admission Notice-->

    <?php else: ?>
    <!--begin::Attendance Form-->
    <form id="attendance-form" method="POST" action="<?= base_url('attendance/save') ?>" enctype="multipart/form-data">

        <!--begin::Stream & Date Selection Card-->
        <div class="card shadow-sm mb-6">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fs-4 fw-bold text-gray-800 mb-0">
                        <i class="ki-duotone ki-calendar-add fs-3 text-primary me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Attendance Setup
                    </h3>
                </div>
            </div>
            <div class="card-body pt-4 pb-6">
                <div class="row g-5">
                    <div class="col-md-5">
                        <label class="form-label required fw-semibold">Stream / Class</label>
                        <select id="stream_select" name="stream_id" class="form-select form-select-solid" required>
                            <option value="">-- Select a Stream --</option>
                            <?php foreach ($streams as $stream): ?>
                            <option value="<?= $stream['stream_id'] ?>">
                                <?= esc($stream['stream_name']) ?>
                                <?php if (!empty($stream['level_name'])): ?>
                                    (<?= esc($stream['level_name']) ?>)
                                <?php endif; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($streams)): ?>
                        <div class="text-danger fs-7 mt-2">
                            No streams found for your school. Please contact your administrator.
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required fw-semibold">Attendance Date</label>
                        <input type="date"
                               id="attendance_date"
                               name="attendance_date"
                               class="form-control form-control-solid"
                               value="<?= esc($today) ?>"
                               max="<?= esc($today) ?>"
                               required />
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="btn-load-students" class="btn btn-primary w-100" disabled>
                            <i class="ki-duotone ki-arrows-circle fs-3 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Load Students
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Stream & Date Selection Card-->

        <!--begin::Student List Card-->
        <div id="student-card" class="card shadow-sm" style="display:none;">
            <div class="card-header border-0 pt-6">
                <div class="card-title d-flex align-items-center gap-3">
                    <h3 class="fs-4 fw-bold text-gray-800 mb-0">
                        <i class="ki-duotone ki-people fs-3 text-primary me-2">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Student Attendance List
                    </h3>
                    <span id="student-count-badge" class="badge badge-light-primary fs-7">0 students</span>
                </div>
                <div class="card-toolbar">
                    <button type="submit" class="btn btn-success">
                        <i class="ki-duotone ki-check-circle fs-3 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Save Attendance
                    </button>
                </div>
            </div>
            <div class="card-body pt-2">
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-60px rounded-start">Photo</th>
                                <th class="min-w-180px">Student Name</th>
                                <th class="min-w-200px">Attendance Note</th>
                                <th class="min-w-160px">Status</th>
                                <th class="min-w-100px rounded-end text-center">Files</th>
                            </tr>
                        </thead>
                        <tbody id="student-table-body">
                            <tr>
                                <td colspan="5" class="text-center py-10 text-muted">
                                    Select a stream above and click <strong>Load Students</strong>.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--end::Table-->
                <div class="d-flex justify-content-end mt-4 pt-4 border-top">
                    <button type="submit" class="btn btn-success btn-lg px-8">
                        <i class="ki-duotone ki-check-circle fs-3 me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Save Attendance
                    </button>
                </div>
            </div>
        </div>
        <!--end::Student List Card-->

    </form>
    <!--end::Attendance Form-->

    <!--begin::Loading Placeholder Card-->
    <div id="loading-card" class="card shadow-sm" style="display:none;">
        <div class="card-body text-center py-15">
            <div class="spinner-border text-primary mb-4" role="status" style="width:3rem;height:3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted fs-5">Loading students&hellip;</p>
        </div>
    </div>
    <!--end::Loading Placeholder Card-->

    <!--begin::File Upload Modal-->
    <div class="modal fade" id="file-upload-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="ki-duotone ki-paper-clip fs-3 text-primary me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Upload Files for <span id="modal-student-name" class="text-primary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-8">
                    <!--begin::Drop Zone-->
                    <div id="file-drop-zone"
                         class="border border-2 border-dashed border-primary rounded p-10 text-center mb-6"
                         style="cursor:pointer; transition: background 0.2s;">
                        <i class="ki-duotone ki-file-up fs-4x text-primary mb-4">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <p class="fs-5 text-gray-700 mb-2">Drag &amp; drop files here</p>
                        <p class="text-muted fs-7 mb-4">or click to browse your computer</p>
                        <button type="button" id="btn-browse-files" class="btn btn-light-primary btn-sm">
                            Browse Files
                        </button>
                        <input type="file" id="modal-file-input" multiple class="d-none" />
                    </div>
                    <!--end::Drop Zone-->

                    <!--begin::Selected Files List-->
                    <div id="modal-file-list" class="d-none">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold text-gray-800">Selected Files</span>
                            <button type="button" id="btn-clear-files" class="btn btn-sm btn-light-danger">
                                <i class="ki-duotone ki-trash fs-5 me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                Clear All
                            </button>
                        </div>
                        <div id="file-items-container" class="d-flex flex-column gap-2"></div>
                    </div>
                    <!--end::Selected Files List-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="btn-apply-files" class="btn btn-primary">
                        <i class="ki-duotone ki-check fs-3 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Done — Apply Files
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::File Upload Modal-->

    <?php endif; ?>

</div>
</div>
<!--end::Content-->

<?php if ($error === null): ?>
<script>
(function () {
    'use strict';

    // ── State ──────────────────────────────────────────────────────────
    let pendingFiles   = {};   // { enrolId: File[] }
    let currentEnrolId = null;
    const BASE_URL     = '<?= base_url() ?>';

    // ── DOM refs ───────────────────────────────────────────────────────
    const streamSelect     = document.getElementById('stream_select');
    const btnLoadStudents  = document.getElementById('btn-load-students');
    const studentCard      = document.getElementById('student-card');
    const loadingCard      = document.getElementById('loading-card');
    const studentTableBody = document.getElementById('student-table-body');
    const studentBadge     = document.getElementById('student-count-badge');
    const dropZone         = document.getElementById('file-drop-zone');
    const modalFileInput   = document.getElementById('modal-file-input');
    const fileItemsCont    = document.getElementById('file-items-container');
    const modalFileList    = document.getElementById('modal-file-list');
    const modalStudentName = document.getElementById('modal-student-name');

    // ── Enable Load button only when stream selected ───────────────────
    streamSelect.addEventListener('change', function () {
        btnLoadStudents.disabled = !this.value;
    });

    // ── Load students via AJAX (with prior duplicate check) ────────────
    btnLoadStudents.addEventListener('click', function () {
        const streamId = streamSelect.value;
        const attDate  = document.getElementById('attendance_date').value;
        if (!streamId) return;

        studentCard.style.display  = 'none';
        loadingCard.style.display  = 'block';

        // Step 1 — check if attendance already taken for this stream+date
        fetch(BASE_URL + 'attendance/check?stream_id=' + streamId + '&date=' + attDate)
            .then(r => r.json())
            .then(function (checkRes) {
                if (checkRes.exists) {
                    loadingCard.style.display = 'none';

                    studentTableBody.innerHTML =
                        '<tr><td colspan="5" class="py-0">' +
                        '<div class="alert alert-warning d-flex align-items-center m-4">' +
                            '<i class="ki-duotone ki-shield-exclamation fs-2hx text-warning me-4">' +
                                '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
                            '</i>' +
                            '<div class="d-flex flex-column">' +
                                '<h4 class="mb-1 text-warning">Attendance Already Recorded</h4>' +
                                '<span>Attendance for this stream on <strong>' + attDate + '</strong> has already been added ' +
                                '(<strong>' + checkRes.count + ' student' + (checkRes.count !== 1 ? 's' : '') + '</strong>). ' +
                                '<a href="' + BASE_URL + 'attendance" class="fw-bold text-warning text-decoration-underline">View Student Daily Attendance</a> ' +
                                'to edit or review the records.</span>' +
                            '</div>' +
                        '</div>' +
                        '</td></tr>';

                    studentBadge.textContent  = '';
                    studentCard.style.display = 'block';
                    return;
                }

                // Step 2 — no duplicate, load students
                fetch(BASE_URL + 'attendance/students?stream_id=' + streamId)
                    .then(r => r.json())
                    .then(function (res) {
                        loadingCard.style.display = 'none';

                        if (!res.success) {
                            studentTableBody.innerHTML =
                                '<tr><td colspan="5" class="text-center py-8 text-danger">' +
                                (res.message || 'Failed to load students.') + '</td></tr>';
                            studentCard.style.display = 'block';
                            return;
                        }

                        studentTableBody.innerHTML = res.html;
                        studentBadge.textContent   = res.count + ' student' + (res.count !== 1 ? 's' : '');
                        studentCard.style.display  = 'block';
                        pendingFiles = {};
                        bindUploadButtons();
                    })
                    .catch(function (err) {
                        loadingCard.style.display = 'none';
                        studentTableBody.innerHTML =
                            '<tr><td colspan="5" class="text-center py-8 text-danger">An error occurred. Please try again.</td></tr>';
                        studentCard.style.display = 'block';
                        console.error('Attendance AJAX error:', err);
                    });
            })
            .catch(function () {
                // If check fails, proceed with loading anyway
                loadingCard.style.display = 'none';
            });
    });

    // ── Bind upload buttons inside dynamically loaded rows ─────────────
    function bindUploadButtons() {
        studentTableBody.querySelectorAll('.btn-upload-files').forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentEnrolId = this.dataset.enrolId;
                modalStudentName.textContent = this.dataset.studentName;
                renderModalFileList(pendingFiles[currentEnrolId] || []);
                new bootstrap.Modal(document.getElementById('file-upload-modal')).show();
            });
        });
    }

    // ── Drop-zone drag events ──────────────────────────────────────────
    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.style.background = '#f0f4ff';
    });
    dropZone.addEventListener('dragleave', function () {
        this.style.background = '';
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        this.style.background = '';
        addFilesToCurrent(Array.from(e.dataTransfer.files));
    });

    // ── Browse button ──────────────────────────────────────────────────
    document.getElementById('btn-browse-files').addEventListener('click', function () {
        modalFileInput.click();
    });
    dropZone.addEventListener('click', function (e) {
        if (e.target === dropZone || e.target.tagName === 'P' || e.target.tagName === 'I') {
            modalFileInput.click();
        }
    });
    modalFileInput.addEventListener('change', function () {
        addFilesToCurrent(Array.from(this.files));
        this.value = '';
    });

    // ── Clear all files ────────────────────────────────────────────────
    document.getElementById('btn-clear-files').addEventListener('click', function () {
        pendingFiles[currentEnrolId] = [];
        renderModalFileList([]);
    });

    // ── Add files to current student ───────────────────────────────────
    function addFilesToCurrent(newFiles) {
        if (!currentEnrolId) return;
        if (!pendingFiles[currentEnrolId]) pendingFiles[currentEnrolId] = [];
        pendingFiles[currentEnrolId] = pendingFiles[currentEnrolId].concat(newFiles);
        renderModalFileList(pendingFiles[currentEnrolId]);
    }

    // ── Render file list inside modal ──────────────────────────────────
    function renderModalFileList(files) {
        fileItemsCont.innerHTML = '';

        if (!files.length) {
            modalFileList.classList.add('d-none');
            return;
        }

        modalFileList.classList.remove('d-none');

        files.forEach(function (file, idx) {
            const size = file.size < 1024 * 1024
                ? (file.size / 1024).toFixed(1) + ' KB'
                : (file.size / 1024 / 1024).toFixed(1) + ' MB';

            const icon = getFileIcon(file.name);

            const item = document.createElement('div');
            item.className = 'd-flex align-items-center justify-content-between border border-gray-200 rounded px-4 py-3';
            item.innerHTML =
                '<div class="d-flex align-items-center gap-3">' +
                    '<i class="ki-duotone ' + icon + ' fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>' +
                    '<div>' +
                        '<div class="fw-semibold text-gray-800 fs-7">' + escHtml(file.name) + '</div>' +
                        '<div class="text-muted fs-8">' + size + '</div>' +
                    '</div>' +
                '</div>' +
                '<button type="button" class="btn btn-sm btn-icon btn-light-danger" data-idx="' + idx + '">' +
                    '<i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>' +
                '</button>';

            item.querySelector('button').addEventListener('click', function () {
                pendingFiles[currentEnrolId].splice(parseInt(this.dataset.idx), 1);
                renderModalFileList(pendingFiles[currentEnrolId]);
            });

            fileItemsCont.appendChild(item);
        });
    }

    // ── Apply files to hidden input and update badge ───────────────────
    document.getElementById('btn-apply-files').addEventListener('click', function () {
        if (!currentEnrolId) {
            bootstrap.Modal.getInstance(document.getElementById('file-upload-modal')).hide();
            return;
        }

        const files = pendingFiles[currentEnrolId] || [];

        // Transfer via DataTransfer API
        const hiddenInput = document.getElementById('fi-' + currentEnrolId);
        if (hiddenInput && typeof DataTransfer !== 'undefined') {
            const dt = new DataTransfer();
            files.forEach(function (f) { dt.items.add(f); });
            hiddenInput.files = dt.files;
        }

        // Update the badge on the row button
        const badge = document.getElementById('file-badge-' + currentEnrolId);
        if (badge) {
            badge.textContent = files.length;
            badge.classList.toggle('d-none', files.length === 0);
        }

        bootstrap.Modal.getInstance(document.getElementById('file-upload-modal')).hide();
    });

    // ── Helpers ────────────────────────────────────────────────────────
    function getFileIcon(name) {
        const ext = name.split('.').pop().toLowerCase();
        if (['jpg','jpeg','png','gif','webp'].includes(ext)) return 'ki-picture';
        if (['pdf'].includes(ext))                            return 'ki-file-pdf';
        if (['doc','docx'].includes(ext))                     return 'ki-file-doc';
        if (['xls','xlsx'].includes(ext))                     return 'ki-file-sheet';
        return 'ki-file';
    }

    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

})();
</script>
<?php endif; ?>
