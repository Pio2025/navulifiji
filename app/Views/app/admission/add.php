<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Required section highlight */
.adm-required-section {
    border: 1.5px solid #e4e6ef;
    border-left: 4px solid #009ef7;
    border-radius: 6px;
    padding: 1.25rem 1.5rem;
    background: #f9fbff;
    transition: border-color .2s, box-shadow .2s;
}
.adm-required-section:focus-within {
    border-color: #009ef7;
    box-shadow: 0 0 0 3px rgba(0,158,247,.1);
}
.adm-required-section .adm-required-badge {
    font-size: .7rem;
    font-weight: 700;
    color: #f1416c;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-left: .5rem;
}
.adm-section-label {
    display: flex;
    align-items: center;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #7e8299;
    margin-bottom: 1rem;
}
/* Subject grid in modal */
.subject-group-title {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #7e8299;
    margin-bottom: .65rem;
}
.subject-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: 3rem;
    gap: .4rem;
    width: 100%;
    margin-bottom: 1.5rem;
}
.subject-checkbox-card {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: 0 1rem;
    height: 3rem;
    border-radius: 2px;
    border: 1.5px solid #f1f1f4;
    background: #fff;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    overflow: hidden;
}
.subject-checkbox-card:hover { border-color: #009ef7; background: #f8faff; }
.subject-checkbox-card.selected { border-color: #009ef7; background: #e8f4ff; }
.subject-checkbox-card.disabled-card { opacity: .45; cursor: not-allowed; }
.subject-checkbox-card input[type="checkbox"] { cursor: pointer; flex-shrink: 0; }
.subject-checkbox-card .sub-name {
    font-size: .8rem;
    font-weight: 600;
    color: #181c32;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
/* Counter dots */
.counter-dot { width: 10px; height: 10px; border-radius: 50%; background: #e4e6ef; transition: background .2s; }
.counter-dot.filled { background: #009ef7; }
/* Selected subject grid */
.selected-subject-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: 3rem;
    gap: .4rem;
    width: 100%;
}
.selected-subject-item {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: 0 .85rem;
    height: 3rem;
    border-radius: 2px;
    background: #e8f4ff;
    border: 1.5px solid #c5dff8;
    overflow: hidden;
}
.selected-subject-item .sub-label {
    flex: 1;
    font-size: .8rem;
    font-weight: 600;
    color: #181c32;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.selected-subject-item .sub-dept {
    font-size: .72rem;
    color: #7e8299;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 30%;
}
.selected-subject-item .remove-sub {
    cursor: pointer;
    color: #7e8299;
    flex-shrink: 0;
    transition: color .15s;
    line-height: 1;
}
.selected-subject-item .remove-sub:hover { color: #f1416c; }
</style>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Add New Admission
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('admission') ?>" class="text-muted text-hover-primary">Admission</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Add New</li>
            </ul>
        </div>
        <a href="<?= base_url('admission') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back to Admissions
        </a>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6 justify-content-center">

    <div class="col-lg-8">
    <div class="card border-0 shadow-sm">

        <div class="card-header border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900 fs-4 mb-0">New Admission</h3>
            </div>
        </div>

        <div class="card-body pt-6">
        <form id="admission_form">

            <!--begin::School Selection-->
            <div class="mb-8 adm-required-section">
                <label class="adm-section-label">
                    <i class="ki-duotone ki-bank fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>
                    School
                    <span class="adm-required-badge">required</span>
                </label>

                <?php if ($isSuperAdmin): ?>
                <label class="form-label fw-semibold fs-7 required">Select School</label>
                <select class="form-select form-select-sm"
                        name="sch_id_fk"
                        id="school_select"
                        data-required
                        data-label="School"
                        data-placeholder="Search school...">
                    <option value="">Search and select a school...</option>
                    <?php foreach ($schools as $school): ?>
                    <option value="<?= $school['sch_id'] ?>">
                        <?= esc($school['sch_name']) ?>
                        <?php if (!empty($school['sch_address'])): ?>
                            — <?= esc($school['sch_address']) ?>
                        <?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text text-muted fs-8 mt-2">As Super Admin you can admit users to any school.</div>

                <?php else: ?>
                <?php
                $sessionSchool = null;
                foreach ($schools as $school) {
                    if ((int) $school['sch_id'] === (int) $sessionSchId) { $sessionSchool = $school; break; }
                }
                ?>
                <input type="hidden" name="sch_id_fk" value="<?= (int) $sessionSchId ?>" />
                <div class="d-flex align-items-center gap-3 p-4 bg-light-primary rounded-3">
                    <div class="symbol symbol-40px symbol-circle flex-shrink-0">
                        <div class="symbol-label bg-primary">
                            <i class="ki-duotone ki-bank fs-4 text-white"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-gray-800 fs-6"><?= esc($sessionSchool['sch_name'] ?? 'Your School') ?></div>
                        <?php if (!empty($sessionSchool['sch_address'])): ?>
                        <div class="text-muted fs-8"><?= esc($sessionSchool['sch_address']) ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="badge badge-light-primary">Your School</span>
                </div>
                <div class="form-text text-muted fs-8 mt-2">Admissions are restricted to your school only.</div>
                <?php endif; ?>
            </div>
            <!--end::School Selection-->

            <!--begin::User Selection-->
            <div class="mb-8 adm-required-section">
                <label class="adm-section-label">
                    <i class="ki-duotone ki-profile-circle fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    User
                    <span class="adm-required-badge">required</span>
                </label>
                <label class="form-label fw-semibold fs-7 required">Select User to Admit</label>

                <?php if (empty($eligibleUsers)): ?>
                <div class="d-flex align-items-center gap-3 p-4 bg-light-warning rounded-3">
                    <i class="ki-duotone ki-information fs-2x text-warning flex-shrink-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <span class="fs-7 text-gray-700">All eligible users already have an active admission. No users are available for new admission.</span>
                </div>

                <?php else: ?>
                <select class="form-select form-select-sm"
                        name="user_id_fk"
                        id="user_select"
                        data-required
                        data-label="User">
                    <option value="">Search and select a user...</option>
                    <?php foreach ($eligibleUsers as $u):
                        $fullName = trim($u['fname'] . ' ' . ($u['oname'] ? $u['oname'] . ' ' : '') . $u['lname']);
                    ?>
                    <option value="<?= $u['user_id'] ?>"
                            data-role="<?= esc($u['role_name'] ?? '') ?>"
                            data-cat="<?= esc($u['role_cat_name'] ?? '') ?>"
                            data-email="<?= esc($u['email'] ?? '') ?>">
                        <?= esc($fullName) ?> — <?= esc($u['role_name'] ?? 'No Role') ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <!--begin::User preview-->
                <div id="user_preview" class="mt-3 p-4 bg-light rounded-3" style="display:none;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-40px symbol-circle flex-shrink-0">
                            <div class="symbol-label bg-primary text-white fw-bold fs-6" id="user_preview_initials">—</div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-gray-800 fs-7" id="user_preview_name">—</div>
                            <div class="text-muted fs-8" id="user_preview_email">—</div>
                        </div>
                        <span class="badge badge-light-primary fs-8" id="user_preview_role">—</span>
                    </div>
                </div>
                <!--end::User preview-->
                <?php endif; ?>
            </div>
            <!--end::User Selection-->

            <!--begin::Admission Details-->
            <div class="mb-8">
                <label class="adm-section-label">
                    <i class="ki-duotone ki-calendar fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>
                    Admission Details
                </label>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Admission Date</label>
                        <input type="date"
                               class="form-control form-control-sm"
                               name="admission_date"
                               id="admission_date"
                               value="<?= date('Y-m-d') ?>"
                               data-required
                               data-label="Admission Date" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold fs-7 required">Admission Status</label>
                        <select class="form-select form-select-sm"
                                name="admission_status"
                                data-required
                                data-label="Admission Status">
                            <option value="Active" selected>Active</option>
                            <option value="Pending">Pending</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                </div>
            </div>
            <!--end::Admission Details-->

            <!--begin::Admission Note-->
            <div class="mb-8">
                <label class="adm-section-label">
                    <i class="ki-duotone ki-note fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>
                    Admission Note
                    <span class="text-muted fw-normal fs-8 ms-2 text-lowercase">— optional</span>
                </label>
                <textarea class="form-control form-control-sm"
                          name="admission_note"
                          rows="4"
                          placeholder="Add any relevant notes about this admission..."></textarea>
            </div>
            <!--end::Admission Note-->

            <!--begin::Enrolment Section (Student role only)-->
            <div id="enrolment_section" class="mb-8" style="display:none;">
                <label class="adm-section-label">
                    <i class="ki-duotone ki-abstract-28 fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>
                    Enrolment
                    <span class="text-muted fw-normal fs-8 ms-2 text-lowercase">— optional: enrol this student into a stream now</span>
                </label>

                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7">Stream / Class</label>
                    <select class="form-select form-select-sm" name="enrol_stream_id" id="enrol_stream_select" disabled>
                        <option value="">— Select a school &amp; student first —</option>
                    </select>
                    <div id="enrol_stream_loader" class="text-muted fs-8 mt-1" style="display:none;">
                        <span class="spinner-border spinner-border-sm me-1 text-primary"></span> Loading streams...
                    </div>
                </div>

                <div class="row g-3 mb-4" id="enrol_details_row" style="display:none;">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">Year</label>
                        <input type="number" name="enrol_year" class="form-control form-control-sm"
                               value="<?= date('Y') ?>" min="2000" max="2099">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">Term</label>
                        <select name="enrol_term" class="form-select form-select-sm">
                            <option value="1">Term 1</option>
                            <option value="2">Term 2</option>
                            <option value="3">Term 3</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-7">Enrolment Date</label>
                        <input type="date" name="enrol_date" class="form-control form-control-sm"
                               value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div id="enrol_subjects_panel" style="display:none;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge badge-light-info fs-9 px-3 py-1">Subjects</span>
                        <span class="text-muted fs-9">Select the subjects this student is taking</span>
                    </div>
                    <div id="enrol_subjects_loader" class="text-center text-muted fs-8 py-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm me-1 text-primary"></span> Loading subjects...
                    </div>
                    <div id="enrol_subjects_content"></div>
                </div>

                <input type="hidden" name="has_subjects" id="has_subjects_adm" value="0">
            </div>
            <!--end::Enrolment Section-->

            <!--begin::Teaching Subjects (Teacher role only)-->
            <div id="teaching_subject_section" class="mb-4" style="display:none;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <label class="adm-section-label mb-0">
                        <i class="ki-duotone ki-book fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>
                        Teaching Subjects
                        <span class="text-muted fw-normal fs-8 ms-2 text-lowercase">— max 7</span>
                    </label>
                    <button type="button" class="btn btn-light-primary btn-xs py-1 px-3 fs-8" id="btn_open_subject_modal">
                        <i class="ki-duotone ki-plus fs-6"><span class="path1"></span><span class="path2"></span></i>
                        Add Subject
                    </button>
                </div>

                <!--begin::Subject list-->
                <div id="teaching_subject_list" class="mb-2"></div>
                <!--end::Subject list-->

                <input type="hidden" name="teaching_subjects" id="teaching_subjects_input" value="[]" />
            </div>
            <!--end::Teaching Subjects-->

        </form>
        </div>

        <div class="card-footer border-0 d-flex justify-content-end gap-3 py-5 px-7">
            <a href="<?= base_url('admission') ?>" class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_save_admission"
                    <?= empty($eligibleUsers) ? 'disabled' : '' ?>>
                <span class="indicator-label">
                    <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Save Admission
                </span>
                <span class="indicator-progress">
                    Saving... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>

    </div>
    </div>

    <!--begin::Info Sidebar-->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7 mb-0">
                    <i class="ki-duotone ki-information-5 fs-4 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Eligibility Notes
                </h6>
            </div>
            <div class="card-body pt-4">
                <ul class="text-muted fs-8 ps-4 mb-0" style="line-height:1.8">
                    <li class="mb-1">Only users <strong>without</strong> an existing active admission are listed.</li>
                    <li class="mb-1">Super Admin and Parent/Guardian roles are excluded from admission.</li>
                    <li class="mb-1">
                        <?= $isSuperAdmin ? 'As Super Admin, you can admit users to any school.' : 'You can only admit users to your assigned school.' ?>
                    </li>
                    <li class="mb-1">Admission date defaults to today but can be changed.</li>
                    <li>After admission, you can proceed to enrol the student into a class or stream.</li>
                </ul>
            </div>
        </div>

        <!--begin::Stats-->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-100">
                    <span class="text-muted fs-8 fw-semibold">Eligible Users</span>
                    <span class="badge badge-light-primary fs-8"><?= count($eligibleUsers) ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-gray-100">
                    <span class="text-muted fs-8 fw-semibold">Available Schools</span>
                    <span class="badge badge-light-info fs-8"><?= $isSuperAdmin ? count($schools) : 1 ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span class="text-muted fs-8 fw-semibold">Your Role</span>
                    <span class="badge badge-light-success fs-8"><?= $isSuperAdmin ? 'Super Admin' : 'School Admin' ?></span>
                </div>
            </div>
        </div>
        <!--end::Stats-->
    </div>
    <!--end::Info Sidebar-->

</div>
</div>
</div>
<!--end::Content-->

<!--begin::Teaching Subject Modal-->
<div class="modal fade" id="modal_teaching_subjects" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h4 class="modal-title fw-bold">Select Teaching Subjects</h4>
                    <p class="text-muted fs-7 mb-0">Check subjects this teacher will teach. Maximum 7 subjects allowed.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!--begin::Counter bar-->
            <div class="px-7 pt-4">
                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                    <span class="text-gray-600 fw-semibold fs-7">Selected:</span>
                    <div class="d-flex align-items-center gap-3">
                        <div class="subject-counter-dots d-flex gap-1" id="counter_dots"></div>
                        <span class="fw-bold text-gray-800 fs-6" id="subject_counter">0 / 7</span>
                    </div>
                </div>
            </div>
            <!--end::Counter bar-->

            <div class="modal-body px-7 py-5" id="modal_subject_body">
                <div class="d-flex align-items-center justify-content-center py-10">
                    <span class="spinner-border text-primary me-3"></span>
                    <span class="text-muted fs-6">Loading subjects...</span>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_confirm_subjects" disabled>
                    <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Add Selected Subjects
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Teaching Subject Modal-->

<!--begin::Scripts-->
<script>
"use strict";

// ── Initialize Select2 for searchable dropdowns ───────────────────
$(document).ready(function() {

    // School select (Super Admin only)
    <?php if ($isSuperAdmin): ?>
    $('#school_select').select2({
        placeholder:         'Search and select a school...',
        allowClear:          true,
        width:               '100%',
        dropdownCssClass:    'fs-7',
        selectionCssClass:   'fs-7',
    });
    <?php endif; ?>

    // User select
    $('#user_select').select2({
        placeholder: 'Search by name, role or email...',
        allowClear:  true,
        width:       '100%',
        dropdownCssClass:  'fs-7',
        selectionCssClass: 'fs-7',
        templateResult:    formatUserOption,
        templateSelection: formatUserSelection,
    });

    // ── User option template ──────────────────────────────────────
    function formatUserOption(option) {
        if (!option.id) return option.text;

        const role  = $(option.element).data('role')  || '';
        const email = $(option.element).data('email') || '';
        const cat   = $(option.element).data('cat')   || '';

        return $(
            '<div class="d-flex align-items-center gap-2 py-1">' +
                '<div class="d-flex flex-column">' +
                    '<span class="fw-bold text-gray-800 fs-7">' + option.text.split('—')[0].trim() + '</span>' +
                    '<span class="text-muted fs-8">' + email + ' &bull; ' + role + '</span>' +
                '</div>' +
                '<span class="badge badge-light-primary fs-9 ms-auto">' + cat + '</span>' +
            '</div>'
        );
    }

    function formatUserSelection(option) {
        if (!option.id) return option.text;
        return option.text.split('—')[0].trim();
    }

    // ── Show user preview + toggle teaching section ──────────────
    $('#user_select').on('change', function() {
        const selected = $(this).find('option:selected');
        const id       = $(this).val();

        if (!id) {
            $('#user_preview').hide();
            $('#teaching_subject_section').hide();
            return;
        }

        const name    = selected.text().split('—')[0].trim();
        const role    = selected.data('role')  || '—';
        const email   = selected.data('email') || '—';
        const cat     = selected.data('cat')   || '';

        const parts    = name.split(' ');
        const initials = parts.map(p => p[0]).join('').substring(0, 2).toUpperCase();

        $('#user_preview_initials').text(initials);
        $('#user_preview_name').text(name);
        $('#user_preview_email').text(email);
        $('#user_preview_role').text(role);
        $('#user_preview').show();

        // Show teaching subjects for Teachers; show enrolment section for Students
        const isTeacher = cat.toLowerCase().trim() === 'teacher';
        const isStudent = cat.toLowerCase().trim() === 'student';

        $('#teaching_subject_section').toggle(isTeacher);
        $('#enrolment_section').toggle(isStudent);

        if (!isTeacher) resetTeachingSubjects();

        if (isStudent) {
            var schId = getAdmissionSchId();
            if (schId) {
                loadEnrolStreams(schId);
            } else {
                // School not yet selected — show section with prompt
                var streamSel = document.getElementById('enrol_stream_select');
                streamSel.innerHTML = '<option value="">— Select a school first to load streams —</option>';
                streamSel.disabled  = true;
                resetEnrolSubjects();
            }
        } else {
            resetEnrolmentSection();
        }
    });
});

// ── Enrolment Section (Students) ─────────────────────────────────
var ENROL_STREAMS_URL  = '<?= base_url('enrolment/streams/') ?>';
var ENROL_SUBJECTS_URL = '<?= base_url('enrolment/subjects/') ?>';

function getAdmissionSchId() {
    var schSelect = document.getElementById('school_select');
    if (schSelect) return parseInt(schSelect.value) || 0;
    var hidden = document.querySelector('input[name="sch_id_fk"]');
    return hidden ? parseInt(hidden.value) || 0 : 0;
}

function loadEnrolStreams(schId) {
    if (!schId) return;
    var streamSel = document.getElementById('enrol_stream_select');
    streamSel.innerHTML = '<option value="">— Loading... —</option>';
    streamSel.disabled  = true;
    document.getElementById('enrol_stream_loader').style.display = '';
    resetEnrolSubjects();

    $.get(ENROL_STREAMS_URL + schId, function(res) {
        document.getElementById('enrol_stream_loader').style.display = 'none';
        streamSel.innerHTML = '<option value="">— Select stream —</option>';
        if (res.success && res.streams && res.streams.length) {
            var lastLevel = '';
            res.streams.forEach(function(s) {
                var lvl = s.level_name || 'Unknown';
                if (lvl !== lastLevel) {
                    if (lastLevel) streamSel.insertAdjacentHTML('beforeend', '</optgroup>');
                    streamSel.insertAdjacentHTML('beforeend', '<optgroup label="' + escHtmlTS(lvl) + '">');
                    lastLevel = lvl;
                }
                streamSel.insertAdjacentHTML('beforeend',
                    '<option value="' + s.stream_id + '">' + escHtmlTS(s.stream_name) + '</option>');
            });
            if (lastLevel) streamSel.insertAdjacentHTML('beforeend', '</optgroup>');
            streamSel.disabled = false;
        } else {
            streamSel.innerHTML = '<option value="">— No streams available —</option>';
        }
    }, 'json').fail(function() {
        document.getElementById('enrol_stream_loader').style.display = 'none';
        streamSel.innerHTML = '<option value="">— Failed to load —</option>';
    });
}

function resetEnrolSubjects() {
    document.getElementById('enrol_details_row').style.display    = 'none';
    document.getElementById('enrol_subjects_panel').style.display = 'none';
    document.getElementById('enrol_subjects_content').innerHTML   = '';
    document.getElementById('has_subjects_adm').value             = '0';
}

function resetEnrolmentSection() {
    var streamSel = document.getElementById('enrol_stream_select');
    streamSel.innerHTML = '<option value="">— Select school &amp; student first —</option>';
    streamSel.disabled  = true;
    resetEnrolSubjects();
}

document.getElementById('enrol_stream_select').addEventListener('change', function() {
    var streamId = parseInt(this.value) || 0;
    resetEnrolSubjects();
    if (!streamId) return;

    document.getElementById('enrol_details_row').style.display    = '';
    document.getElementById('enrol_subjects_panel').style.display  = '';
    document.getElementById('enrol_subjects_loader').style.display = '';

    $.get(ENROL_SUBJECTS_URL + streamId, function(res) {
        document.getElementById('enrol_subjects_loader').style.display = 'none';
        var html = '';

        if (res.success && ((res.core && res.core.length) || (res.optional && res.optional.length))) {
            document.getElementById('has_subjects_adm').value = '1';

            if (res.core && res.core.length) {
                html += '<div class="mb-4"><div class="d-flex align-items-center gap-2 mb-2">' +
                        '<span class="badge badge-light-primary fs-9 px-3 py-1">Core Subjects</span>' +
                        '<span class="text-danger fs-9">* select all</span></div>';
                res.core.forEach(function(s) {
                    html += '<label class="d-flex align-items-center gap-3 py-2 border-bottom border-gray-100 cursor-pointer">' +
                            '<input type="checkbox" class="form-check-input mt-0" name="core_subjects[]" value="' + s.sch_sub_id + '" checked>' +
                            '<span class="fw-semibold fs-7 text-gray-800">' + escHtmlTS(s.subject_name) + '</span></label>';
                });
                html += '</div>';
            }

            if (res.optional && res.optional.length) {
                var groups = {};
                res.optional.forEach(function(s) {
                    if (!groups[s.option_num]) groups[s.option_num] = [];
                    groups[s.option_num].push(s);
                });
                var gIdx = 1;
                Object.keys(groups).forEach(function(gnum) {
                    html += '<div class="mb-3"><div class="d-flex align-items-center gap-2 mb-2">' +
                            '<span class="badge badge-light-warning fs-9 px-3 py-1">Optional Group ' + gIdx++ + '</span>' +
                            '<span class="text-muted fs-9">select one</span></div>';
                    groups[gnum].forEach(function(s) {
                        html += '<label class="d-flex align-items-center gap-3 py-2 border-bottom border-gray-100 cursor-pointer">' +
                                '<input type="radio" class="form-check-input mt-0" name="optional_group_' + gnum + '" value="' + s.sch_sub_id + '">' +
                                '<span class="fw-semibold fs-7 text-gray-800">' + escHtmlTS(s.subject_name) + '</span></label>';
                    });
                    html += '</div>';
                });
            }
        } else {
            html = '<div class="text-muted fs-8 py-2">No subjects configured for this stream.</div>';
        }

        document.getElementById('enrol_subjects_content').innerHTML = html;
    }, 'json').fail(function() {
        document.getElementById('enrol_subjects_loader').style.display = 'none';
        document.getElementById('enrol_subjects_content').innerHTML =
            '<div class="text-danger fs-8">Failed to load subjects.</div>';
    });
});

// ── Teaching Subjects ─────────────────────────────────────────────
const MAX_SUBJECTS   = 7;
let selectedSubjects = [];   // [{sch_sub_id, subject_name, dept_name}]
let subjectsCache    = null; // cached AJAX response

function getSelectedSchId() {
    const schSelect = document.getElementById('school_select');
    if (schSelect) return schSelect.value;
    const hidden = document.querySelector('input[name="sch_id_fk"]');
    return hidden ? hidden.value : '';
}

function resetTeachingSubjects() {
    selectedSubjects = [];
    subjectsCache    = null;
    renderSubjectTags();
}

function renderSubjectTags() {
    const list = document.getElementById('teaching_subject_list');
    document.getElementById('teaching_subjects_input').value = JSON.stringify(selectedSubjects);

    if (selectedSubjects.length === 0) {
        list.innerHTML = '<p class="text-muted fs-8 mb-0">No teaching subjects added yet.</p>';
        return;
    }

    list.innerHTML =
        '<div class="selected-subject-grid">' +
        selectedSubjects.map((s, i) =>
            `<div class="selected-subject-item">
                <i class="ki-duotone ki-book fs-7 text-primary flex-shrink-0"><span class="path1"></span><span class="path2"></span></i>
                <span class="sub-label">${escHtmlTS(s.subject_name)}</span>
                <span class="sub-dept">${escHtmlTS(s.dept_name)}</span>
                <span class="remove-sub" data-idx="${i}" title="Remove">
                    <i class="ki-duotone ki-cross fs-7"><span class="path1"></span><span class="path2"></span></i>
                </span>
            </div>`
        ).join('') +
        '</div>';

    list.querySelectorAll('.remove-sub').forEach(el => {
        el.addEventListener('click', function() {
            selectedSubjects.splice(parseInt(this.dataset.idx), 1);
            renderSubjectTags();
        });
    });
}

function updateCounterUI(checked) {
    document.getElementById('subject_counter').textContent = checked + ' / ' + MAX_SUBJECTS;
    const dots = document.getElementById('counter_dots');
    dots.innerHTML = Array.from({length: MAX_SUBJECTS}, (_, i) =>
        `<span class="counter-dot ${i < checked ? 'filled' : ''}"></span>`
    ).join('');
    document.getElementById('btn_confirm_subjects').disabled = checked === 0;
}

function buildModalBody(data) {
    if (!data.hasSubjects || !data.subjects || data.subjects.length === 0) {
        return '<div class="text-center py-8 text-muted fs-6">No subjects configured for this school yet.</div>';
    }

    // Group by dept_name
    const groups = {};
    data.subjects.forEach(s => {
        const g = s.dept_name || 'General';
        if (!groups[g]) groups[g] = [];
        groups[g].push(s);
    });

    // Pre-mark already selected
    const alreadyIds = new Set(selectedSubjects.map(s => String(s.sch_sub_id)));

    let html = '';
    Object.keys(groups).sort().forEach(dept => {
        html += `<p class="subject-group-title">${escHtmlTS(dept)}</p><div class="subject-grid">`;
        groups[dept].forEach(s => {
            const checked  = alreadyIds.has(String(s.sch_sub_id)) ? 'checked' : '';
            const selClass = checked ? 'selected' : '';
            html += `
            <label class="subject-checkbox-card ${selClass}" data-sub-id="${s.sch_sub_id}">
                <input type="checkbox" class="form-check-input subject-cb mt-0"
                       value="${s.sch_sub_id}"
                       data-name="${escHtmlTS(s.subject_name)}"
                       data-dept="${escHtmlTS(s.dept_name || 'General')}"
                       ${checked} />
                <span class="sub-name">${escHtmlTS(s.subject_name)}</span>
            </label>`;
        });
        html += '</div>';
    });
    return html;
}

function attachModalCheckboxEvents() {
    const cbs = document.querySelectorAll('.subject-cb');
    function syncUI() {
        const checked = document.querySelectorAll('.subject-cb:checked').length;
        updateCounterUI(checked);
        cbs.forEach(cb => {
            const card = cb.closest('.subject-checkbox-card');
            card.classList.toggle('selected', cb.checked);
            if (!cb.checked && checked >= MAX_SUBJECTS) {
                card.classList.add('disabled-card');
                cb.disabled = true;
            } else {
                card.classList.remove('disabled-card');
                cb.disabled = false;
                if (cb.checked) { card.classList.remove('disabled-card'); cb.disabled = false; }
            }
        });
    }
    cbs.forEach(cb => {
        cb.addEventListener('change', function() {
            const checked = document.querySelectorAll('.subject-cb:checked').length;
            if (this.checked && checked > MAX_SUBJECTS) { this.checked = false; return; }
            syncUI();
        });
    });
    syncUI();
}

// Open modal
document.getElementById('btn_open_subject_modal').addEventListener('click', function() {
    const schId = getSelectedSchId();
    if (!schId) {
        Swal.fire({ title: 'Select a School First', icon: 'warning', buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }
    const modal = new bootstrap.Modal(document.getElementById('modal_teaching_subjects'));
    modal.show();

    if (subjectsCache) {
        document.getElementById('modal_subject_body').innerHTML = buildModalBody(subjectsCache);
        attachModalCheckboxEvents();
        updateCounterUI(selectedSubjects.length);
        return;
    }

    $.ajax({
        url:      '<?= base_url('admission/school-subjects') ?>/' + schId,
        type:     'GET',
        dataType: 'json',
        success: function(data) {
            subjectsCache = data;
            document.getElementById('modal_subject_body').innerHTML = buildModalBody(data);
            attachModalCheckboxEvents();
            updateCounterUI(selectedSubjects.length);
        },
        error: function() {
            document.getElementById('modal_subject_body').innerHTML =
                '<div class="text-center py-8 text-danger fs-6">Failed to load subjects. Please try again.</div>';
        }
    });
});

// Reset subjects cache when school changes (super admin)
<?php if ($isSuperAdmin): ?>
$('#school_select').on('change', function() {
    subjectsCache = null;
    resetTeachingSubjects();

    var cat      = ($('#user_select').find('option:selected').data('cat') || '').toLowerCase().trim();
    var newSchId = parseInt($(this).val()) || 0;

    if (cat === 'student') {
        // Ensure the enrolment section is visible
        $('#enrolment_section').show();
        if (newSchId) {
            loadEnrolStreams(newSchId);
        } else {
            resetEnrolmentSection();
        }
    } else if (cat === 'teacher') {
        // School changed — teacher subject cache is stale, section stays visible
        if (!newSchId) resetEnrolmentSection();
    } else {
        // No user selected yet — reset enrolment
        resetEnrolmentSection();
    }
});
<?php endif; ?>

// Confirm subjects from modal
document.getElementById('btn_confirm_subjects').addEventListener('click', function() {
    const checked = document.querySelectorAll('.subject-cb:checked');
    selectedSubjects = Array.from(checked).map(cb => ({
        sch_sub_id:   cb.value,
        subject_name: cb.dataset.name,
        dept_name:    cb.dataset.dept,
    }));
    renderSubjectTags();
    bootstrap.Modal.getInstance(document.getElementById('modal_teaching_subjects')).hide();
});

function escHtmlTS(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Form validation and submit ────────────────────────────────────
document.getElementById('btn_save_admission').addEventListener('click', function() {
    const btn = this;

    // Validate required fields
    const errors = [];

    <?php if ($isSuperAdmin): ?>
    const schoolVal = $('#school_select').val();
    if (!schoolVal) errors.push('Please select a school.');
    <?php endif; ?>

    const userVal = $('#user_select').val();
    if (!userVal) errors.push('Please select a user to admit.');

    const dateVal = document.getElementById('admission_date').value;
    if (!dateVal) errors.push('Please enter the admission date.');

    // Teacher must have at least one teaching subject
    const userCat = $('#user_select').find('option:selected').data('cat') || '';
    if (userCat.toLowerCase().trim() === 'teacher' && selectedSubjects.length === 0) {
        errors.push('Please add at least one teaching subject for this teacher.');
    }

    if (errors.length > 0) {
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

    // Confirm before submitting
    Swal.fire({
        title: 'Confirm Admission',
        html:
            '<p class="text-gray-700 fs-6 mb-3">You are about to admit this user to the school.</p>' +
            '<div class="bg-light-primary rounded p-4 text-start">' +
                '<div class="fw-bold text-gray-800 fs-7 mb-1">User: ' + ($('#user_select option:selected').text().split('—')[0].trim()) + '</div>' +
                '<div class="text-muted fs-8">Date: ' + dateVal + '</div>' +
            '</div>',
        icon:              'question',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, Admit',
        cancelButtonText:  'Cancel',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton:  'btn btn-light',
        }
    }).then(function(result) {
        if (!result.isConfirmed) return;

        btn.setAttribute('data-kt-indicator', 'on');
        btn.disabled = true;

        const formData = new FormData(document.getElementById('admission_form'));

        $.ajax({
            url:         '<?= base_url('admission/store') ?>',
            type:        'POST',
            data:        formData,
            processData: false,
            contentType: false,
            success: function(response) {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;

                if (response.success) {
                    Swal.fire({
                        title:             'Admission Added!',
                        text:              response.message,
                        icon:              'success',
                        buttonsStyling:    false,
                        confirmButtonText: 'View Admissions',
                        showCancelButton:  true,
                        cancelButtonText:  'Add Another',
                        customClass: {
                            confirmButton: 'btn btn-success me-3',
                            cancelButton:  'btn btn-light',
                        }
                    }).then(function(r) {
                        if (r.isConfirmed) {
                            window.location.href = response.redirect || '<?= base_url('admission') ?>';
                        } else {
                            // Reset form for another entry
                            document.getElementById('admission_form').reset();
                            $('#user_select').val('').trigger('change');
                            <?php if ($isSuperAdmin): ?>
                            $('#school_select').val('').trigger('change');
                            <?php endif; ?>
                            $('#user_preview').hide();
                            // Reload to refresh eligible users list
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title:             'Failed',
                        text:              response.message,
                        icon:              'error',
                        buttonsStyling:    false,
                        confirmButtonText: 'Close',
                        customClass:       { confirmButton: 'btn btn-danger' }
                    });
                }
            },
            error: function() {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;
                Swal.fire({
                    title:             'Error',
                    text:              'An unexpected error occurred. Please try again.',
                    icon:              'error',
                    buttonsStyling:    false,
                    confirmButtonText: 'Close',
                    customClass:       { confirmButton: 'btn btn-danger' }
                });
            }
        });
    });
});
</script>
<!--end::Scripts-->