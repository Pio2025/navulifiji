<style>
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
.edit-subject-group-title {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #7e8299;
    margin-bottom: .65rem;
}
.edit-subject-grid {
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
.subject-checkbox-card:hover  { border-color: #009ef7; background: #f8faff; }
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
.counter-dot { width: 10px; height: 10px; border-radius: 50%; background: #e4e6ef; transition: background .2s; }
.counter-dot.filled { background: #009ef7; }
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Edit Admission
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('admission') ?>" class="text-muted text-hover-primary">Admissions</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('admission/detail/' . $admission['admission_id']) ?>"
                       class="text-muted text-hover-primary">
                        Detail
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Edit</li>
            </ul>
        </div>
        <a href="<?= base_url('admission/detail/' . $admission['admission_id']) ?>"
           class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back to Detail
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
                <h3 class="fw-bold text-gray-900 fs-4 mb-0">Edit Admission</h3>
            </div>
        </div>

        <div class="card-body">

            <?php $isLocked = $admission['admission_status'] === 'Completed' && !$isSuperAdmin; ?>

            <?php if ($isLocked): ?>
            <!--begin::Locked notice-->
            <div class="d-flex align-items-center gap-3 p-4 bg-light-warning rounded-3 mb-6">
                <i class="ki-duotone ki-lock-2 fs-2x text-warning flex-shrink-0">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div class="flex-grow-1">
                    <div class="fw-bold text-gray-800 fs-7">Editing Restricted</div>
                    <div class="text-muted fs-8">
                        This admission is <strong><?= esc($admission['admission_status']) ?></strong>.
                        Only the <strong>Admission Status</strong> field can be changed.
                        Reactivate to unlock all fields.
                    </div>
                </div>
            </div>
            <!--end::Locked notice-->
            <?php endif; ?>

            <!--begin::User Info (read-only)-->
            <div class="mb-8 p-4 bg-light-primary rounded-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-50px symbol-circle flex-shrink-0">
                        <?php if (!empty($admission['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $admission['profile_photo']) ?>"
                                 alt="<?= esc($admission['fname']) ?>" />
                        <?php else: ?>
                            <div class="symbol-label bg-light-primary text-primary fw-bold fs-5">
                                <?= strtoupper(substr($admission['fname'] ?? 'U', 0, 1) . substr($admission['lname'] ?? '', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="fw-bold text-gray-800 fs-6">
                            <?= esc(trim($admission['fname'] . ' ' . ($admission['oname'] ? $admission['oname'] . ' ' : '') . $admission['lname'])) ?>
                        </div>
                        <div class="text-muted fs-7"><?= esc($admission['email'] ?? '') ?></div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge badge-light-primary"><?= esc($admission['role_name'] ?? '') ?></span>
                        <div class="text-muted fs-9 text-center mt-1">Read-only</div>
                    </div>
                </div>
            </div>
            <!--end::User Info-->

            <form id="edit_admission_form">

                <!--begin::School-->
                <div class="mb-8">
                    <label class="adm-section-label">
                        <i class="ki-duotone ki-bank fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>
                        School
                    </label>

                    <?php if ($isSuperAdmin): ?>
                    <label class="form-label fw-semibold fs-7 required">School</label>
                    <select class="form-select form-select-sm" name="sch_id_fk" id="edit_school_select"
                            <?= $isLocked ? 'disabled' : '' ?>>
                        <?php foreach ($schools as $school): ?>
                        <option value="<?= $school['sch_id'] ?>"
                                <?= (int)$school['sch_id'] === (int)$admission['sch_id_fk'] ? 'selected' : '' ?>>
                            <?= esc($school['sch_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php else: ?>
                    <input type="hidden" name="sch_id_fk" value="<?= (int)$admission['sch_id_fk'] ?>" />
                    <div class="d-flex align-items-center gap-3 p-4 bg-light rounded-3">
                        <div class="symbol symbol-40px symbol-circle flex-shrink-0">
                            <div class="symbol-label bg-primary">
                                <i class="ki-duotone ki-bank fs-4 text-white"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-gray-800 fs-6"><?= esc($admission['sch_name'] ?? '—') ?></div>
                            <div class="text-muted fs-8"><?= esc($admission['sch_address'] ?? '') ?></div>
                        </div>
                        <span class="badge badge-light-secondary">Locked</span>
                    </div>
                    <?php endif; ?>
                </div>
                <!--end::School-->

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
                                   value="<?= esc($admission['admission_date'] ?? date('Y-m-d')) ?>"
                                   <?= $isLocked ? 'readonly' : 'required' ?> />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-7 required">Admission Status</label>
                            <select class="form-select form-select-sm" name="admission_status" required>
                                <?php
                                $statuses = ['Active', 'Pending', 'Completed', 'Rejected'];
                                foreach ($statuses as $s):
                                ?>
                                <option value="<?= $s ?>"
                                        <?= $admission['admission_status'] === $s ? 'selected' : '' ?>>
                                    <?= $s ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!--end::Admission Details-->

                <!--begin::Note-->
                <div class="mb-8">
                    <label class="adm-section-label">
                        <i class="ki-duotone ki-note fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>
                        Admission Note
                        <span class="text-muted fw-normal fs-8 ms-2 text-lowercase">— optional</span>
                    </label>
                    <textarea class="form-control form-control-sm"
                              name="admission_note"
                              rows="4"
                              placeholder="Add notes..."
                              <?= $isLocked ? 'readonly' : '' ?>><?= esc($admission['admission_note'] ?? '') ?></textarea>
                </div>
                <!--end::Note-->
                
                <!--begin::Is Head Of Department (Teacher only)-->
                <?php if (in_array($roleCatId, [2, 3, 5])): ?>
                <div class="mb-0 mt-5">
                    <div class="separator my-5"></div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-bold text-gray-800 mb-0 fs-6">Head of Department</h6>
                            <div class="text-muted fs-8 mt-1">Assign this teacher as Head of Department.</div>
                        </div>
                        <div class="form-check form-switch form-check-custom form-check-solid ms-3">
                            <input class="form-check-input h-25px w-45px"
                                   type="checkbox"
                                   id="switch_is_hod"
                                   <?= $hodRecord ? 'checked' : '' ?>
                                   <?= $isLocked ? 'disabled' : '' ?> />
                        </div>
                    </div>
                
                    <!-- Hidden fields submitted with main form -->
                    <input type="hidden" name="is_hod"      id="hidden_is_hod"      value="<?= $hodRecord ? '1' : '0' ?>" />
                    <input type="hidden" name="hod_dept_id" id="hidden_hod_dept_id" value="<?= $hodRecord ? (int)$hodRecord['sch_dept_id'] : '0' ?>" />
                
                    <!--begin::HOD department selector-->
                    <div id="hod_dept_section" class="mt-4" style="<?= $hodRecord ? '' : 'display:none;' ?>">
                        <label class="form-label fw-semibold fs-7 required">Select Department</label>
                        <select class="form-select form-select-sm" id="hod_dept_select">
                            <option value="">Loading departments...</option>
                        </select>
                    </div>
                    <!--end::HOD department selector-->
                </div>
                <?php endif; ?>
                <!--end::Is Head Of Department-->
                
                <!--begin::Student Leadership Role-->
                <?php if ($roleCatId === 4): ?>
                <div class="mb-0 mt-5">
                    <div class="separator my-5"></div>
                    <h6 class="fw-bold text-gray-800 mb-1 fs-6">Leadership / Prefect Role</h6>
                    <div class="text-muted fs-8 mb-4">
                        A student can only hold one leadership role. Selecting one disables all others.
                        This will be saved with the main form.
                    </div>
                
                    <!-- Hidden field submitted with main form -->
                    <input type="hidden" name="leadership_role" id="hidden_leadership_role"
                           value="<?= esc($studentRole['leadership_role'] ?? '') ?>" />
                
                    <?php
                    $roles = [
                        'school_prefect'    => ['label' => 'School Prefect',    'icon' => 'ki-shield-tick', 'color' => 'primary'],
                        'hostel_prefect'    => ['label' => 'Hostel Prefect',    'icon' => 'ki-home-1',       'color' => 'info'],
                        'junior_prefect'    => ['label' => 'Junior Prefect',    'icon' => 'ki-star',         'color' => 'warning'],
                        'relieving_prefect' => ['label' => 'Relieving Prefect', 'icon' => 'ki-time',         'color' => 'secondary'],
                    ];
                
                    if ($gender === 'male') {
                        $roles = array_merge([
                            'head_boy'        => ['label' => 'Head Boy',        'icon' => 'ki-crown-2', 'color' => 'danger'],
                            'deputy_head_boy' => ['label' => 'Deputy Head Boy', 'icon' => 'ki-award',   'color' => 'warning'],
                        ], $roles);
                    } else {
                        $roles = array_merge([
                            'head_girl'        => ['label' => 'Head Girl',        'icon' => 'ki-crown-2', 'color' => 'danger'],
                            'deputy_head_girl' => ['label' => 'Deputy Head Girl', 'icon' => 'ki-award',   'color' => 'warning'],
                        ], $roles);
                    }
                
                    $currentRole = $studentRole['leadership_role'] ?? '';
                    ?>
                
                    <div class="row g-3 <?= $isLocked ? 'opacity-50 pe-none' : '' ?>" id="prefect_roles_container">
                        <?php foreach ($roles as $roleKey => $roleData): ?>
                        <div class="col-md-6">
                            <label class="prefect-role-card d-flex align-items-center gap-3 p-3 rounded border cursor-pointer
                                          <?= $currentRole === $roleKey
                                                ? 'border-' . $roleData['color'] . ' bg-light-' . $roleData['color']
                                                : 'border-gray-300' ?>"
                                   style="transition: all 0.2s; cursor:pointer;"
                                   for="role_<?= $roleKey ?>">
                                <input type="radio"
                                       name="_prefect_ui_radio"
                                       id="role_<?= $roleKey ?>"
                                       value="<?= $roleKey ?>"
                                       class="prefect-radio"
                                       <?= $currentRole === $roleKey ? 'checked' : '' ?>
                                       style="display:none;" />
                                <div class="symbol symbol-35px flex-shrink-0">
                                    <div class="symbol-label bg-light-<?= $roleData['color'] ?>">
                                        <i class="ki-duotone <?= $roleData['icon'] ?> fs-3 text-<?= $roleData['color'] ?>">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <span class="fw-semibold text-gray-700 fs-7"><?= $roleData['label'] ?></span>
                                <div class="ms-auto">
                                    <div class="role-check-indicator symbol symbol-20px <?= $currentRole === $roleKey ? '' : 'd-none' ?>">
                                        <div class="symbol-label bg-<?= $roleData['color'] ?>">
                                            <i class="ki-duotone ki-check fs-7 text-white">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-light-danger" id="btn_clear_role">
                            <i class="ki-duotone ki-cross-circle fs-4 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Clear Role
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                <!--end::Student Leadership Role-->

            </form>
        </div>

        <div class="card-footer border-0 d-flex justify-content-end gap-3 py-5 px-7">
            <a href="<?= base_url('admission/detail/' . $admission['admission_id']) ?>"
               class="btn btn-light btn-sm">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm" id="btn_update_admission">
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

    <!--begin::Sidebar-->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7 mb-0">
                    <i class="ki-duotone ki-information-5 fs-4 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Edit Notes
                </h6>
            </div>
            <div class="card-body pt-3">
                <ul class="text-muted fs-8 ps-4 mb-0">
                    <li class="mb-2">The user cannot be changed after admission is created.</li>
                    <li class="mb-2">
                        <?= $isSuperAdmin ? 'As Super Admin you can reassign to any school.' : 'School is locked to your assigned school.' ?>
                    </li>
                    <li class="mb-2">Changing status to Completed or Rejected may affect enrolments.</li>
                    <li>Admission ID: <strong>#<?= str_pad($admission['admission_id'], 6, '0', STR_PAD_LEFT) ?></strong></li>
                </ul>
            </div>
        </div>
        
        <!--begin::Teaching Subjects Card (Teacher only)-->
        <?php if (in_array($roleCatId, [2, 3, 5])): ?>
        <div class="card border-0 shadow-sm mt-5">
            <div class="card-header border-0 pt-5">
                <h6 class="card-title fw-bold text-gray-800 fs-7">
                    <i class="ki-duotone ki-book-open fs-4 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span>
                    </i>
                    Teaching Subjects
                </h6>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-primary" id="btn_open_subject_modal"
                            <?= $isLocked ? 'disabled title="Unlock admission to manage subjects"' : '' ?>>
                        <i class="ki-duotone ki-plus fs-4 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Add Subject
                    </button>
                </div>
            </div>
            <div class="card-body pt-3 pb-5" id="teaching_subjects_container">
                <?php if (empty($teachingSubjects)): ?>
                <div class="text-center text-muted py-6" id="no_subjects_msg">
                    <i class="ki-duotone ki-book fs-3x text-muted mb-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="fw-semibold fs-7">No teaching subjects assigned.</div>
                    <div class="fs-8 mt-1">Click "Add Subject" to assign.</div>
                </div>
                <?php else: ?>
                <?php
                $byDept = [];
                foreach ($teachingSubjects as $ts) {
                    $key = $ts['dept_name'] ?? 'General';
                    $byDept[$key][] = $ts;
                }
                foreach ($byDept as $deptName => $subs):
                ?>
                <div class="mb-3" data-dept-group="<?= esc($deptName) ?>">
                    <div class="fw-bold text-gray-500 fs-8 mb-2 text-uppercase ls-1">
                        <?= esc($deptName) ?>
                    </div>
                    <?php foreach ($subs as $sub): ?>
                    <div class="d-flex align-items-center gap-2 py-1 subject-row"
                         data-adm-teach-sub-id="<?= (int)$sub['adm_teach_sub_id'] ?>">
                        <i class="ki-duotone ki-check-circle fs-6 text-success flex-shrink-0">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <span class="text-gray-700 fs-8 flex-grow-1"><?= esc($sub['subject_name']) ?></span>
                        <span class="btn-delete-subject btn btn-icon btn-xs btn-light-danger"
                              data-adm-teach-sub-id="<?= (int)$sub['adm_teach_sub_id'] ?>"
                              data-sch-sub-id="<?= (int)$sub['sch_sub_id_fk'] ?>"
                              data-subject-name="<?= esc($sub['subject_name']) ?>"
                              title="Remove subject">
                            <i class="ki-duotone ki-trash fs-7">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                            </i>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <!--end::Sidebar-->

</div>
</div>
</div>
<!--end::Content-->



<!--begin::Teaching Subject Modal-->
<?php if (in_array($roleCatId, [2, 3, 5])): ?>
<div class="modal fade" id="modal_teaching_subject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h4 class="modal-title fw-bold">Select Teaching Subjects</h4>
                    <p class="text-muted fs-7 mb-0">Check subjects this teacher will teach. Maximum 7 subjects allowed.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="px-7 pt-4">
                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                    <span class="text-gray-600 fw-semibold fs-7">Selected:</span>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex gap-1" id="edit_counter_dots"></div>
                        <span class="fw-bold text-gray-800 fs-6" id="edit_subject_counter">0 / 7</span>
                    </div>
                </div>
            </div>
            <div class="modal-body px-7 py-5" id="subject_modal_body">
                <div class="d-flex align-items-center justify-content-center py-10">
                    <span class="spinner-border text-primary me-3"></span>
                    <span class="text-muted fs-6">Loading subjects...</span>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_subjects" disabled>
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Save Subjects
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Teaching Subject Modal-->
<?php endif; ?>

<script>
"use strict";

const ADMISSION_ID = <?= $admissionId ?>;
const SCH_ID       = <?= (int) $admission['sch_id_fk'] ?>;
const ROLE_CAT_ID  = <?= $roleCatId ?>;

// ================================================================
// TEACHER — TEACHING SUBJECTS MODAL
// ================================================================
<?php if (in_array($roleCatId, [2, 3, 5])): ?>

const MAX_EDIT_SUBJECTS = 7;
let allSubjectsData     = [];
let subjectsCacheLoaded = false;

document.getElementById('btn_open_subject_modal')?.addEventListener('click', function () {
    if (assignedSchSubIds.size >= MAX_EDIT_SUBJECTS) {
        Swal.fire({
            title:             'Limit Reached',
            html:              'This teacher already has <strong>' + MAX_EDIT_SUBJECTS + ' teaching subjects</strong> assigned.<br>Delete a subject first to add a new one.',
            icon:              'warning',
            buttonsStyling:    false,
            confirmButtonText: 'OK',
            customClass:       { confirmButton: 'btn btn-warning' },
        });
        return;
    }
    const modal = new bootstrap.Modal(document.getElementById('modal_teaching_subject'));
    modal.show();
    if (!subjectsCacheLoaded) loadSubjects();
    else { renderEditSubjectModal(); }
});

function loadSubjects() {
    document.getElementById('subject_modal_body').innerHTML =
        '<div class="d-flex align-items-center justify-content-center py-10">' +
        '<span class="spinner-border text-primary me-3"></span>' +
        '<span class="text-muted fs-6">Loading subjects...</span></div>';

    fetch('<?= base_url('admission/school-subjects/') ?>' + SCH_ID)
        .then(r => r.json())
        .then(data => {
            if (!data.success || !data.hasSubjects) {
                document.getElementById('subject_modal_body').innerHTML =
                    '<div class="text-center py-8 text-muted fs-6">No subjects configured for this school yet.</div>';
                return;
            }
            allSubjectsData     = data.subjects;
            subjectsCacheLoaded = true;
            renderEditSubjectModal();
        })
        .catch(() => {
            document.getElementById('subject_modal_body').innerHTML =
                '<div class="text-center py-8 text-danger fs-6">Failed to load subjects. Please try again.</div>';
        });
}

// Track assigned sch_sub_id_fk values dynamically (updated after adds/deletes)
let assignedSchSubIds = new Set(<?= json_encode(array_column($teachingSubjects, 'sch_sub_id_fk')) ?>.map(String));

function renderEditSubjectModal() {
    const byDept = {};
    allSubjectsData.forEach(s => {
        const g = s.dept_name || 'General';
        if (!byDept[g]) byDept[g] = [];
        byDept[g].push(s);
    });

    let html = '';
    Object.keys(byDept).sort().forEach(dept => {
        html += `<p class="edit-subject-group-title">${escEdit(dept)}</p><div class="edit-subject-grid">`;
        byDept[dept].forEach(s => {
            const alreadyAssigned = assignedSchSubIds.has(String(s.sch_sub_id));
            if (alreadyAssigned) {
                // Show greyed-out "already added" card — not selectable
                html += `
                <label class="subject-checkbox-card disabled-card" style="cursor:default;">
                    <input type="checkbox" class="form-check-input mt-0" disabled checked />
                    <span class="sub-name">${escEdit(s.subject_name)}</span>
                    <span class="badge badge-light-success ms-auto fs-9" style="white-space:nowrap;">Added</span>
                </label>`;
            } else {
                html += `
                <label class="subject-checkbox-card" data-sub-id="${s.sch_sub_id}">
                    <input type="checkbox" class="form-check-input subject-cb mt-0"
                           value="${s.sch_sub_id}" data-sub-id="${s.sch_sub_id}" />
                    <span class="sub-name">${escEdit(s.subject_name)}</span>
                </label>`;
            }
        });
        html += '</div>';
    });

    document.getElementById('subject_modal_body').innerHTML = html;
    attachEditCheckboxEvents();
    updateEditCounterUI(assignedSchSubIds.size); // start counter at existing count
}

function attachEditCheckboxEvents() {
    const cbs    = document.querySelectorAll('#subject_modal_body .subject-cb');
    const maxNew = MAX_EDIT_SUBJECTS - assignedSchSubIds.size; // remaining slots

    function syncUI() {
        const newlyChecked = document.querySelectorAll('#subject_modal_body .subject-cb:checked').length;
        updateEditCounterUI(assignedSchSubIds.size + newlyChecked); // total = existing + new
        document.getElementById('btn_save_subjects').disabled = newlyChecked === 0;
        cbs.forEach(cb => {
            const card = cb.closest('.subject-checkbox-card');
            card.classList.toggle('selected', cb.checked);
            const overLimit = !cb.checked && newlyChecked >= maxNew;
            card.classList.toggle('disabled-card', overLimit);
            cb.disabled = overLimit;
        });
    }
    cbs.forEach(cb => cb.addEventListener('change', function() {
        const newlyChecked = document.querySelectorAll('#subject_modal_body .subject-cb:checked').length;
        if (this.checked && newlyChecked > maxNew) { this.checked = false; return; }
        syncUI();
    }));
    syncUI();
}

function updateEditCounterUI(count) {
    document.getElementById('edit_subject_counter').textContent = count + ' / ' + MAX_EDIT_SUBJECTS;
    document.getElementById('edit_counter_dots').innerHTML =
        Array.from({length: MAX_EDIT_SUBJECTS}, (_, i) =>
            `<span class="counter-dot ${i < count ? 'filled' : ''}"></span>`
        ).join('');
}

function escEdit(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Save (add new) subjects via AJAX — does NOT remove existing ones
document.getElementById('btn_save_subjects')?.addEventListener('click', function () {
    const btn     = this;
    const checked = document.querySelectorAll('#subject_modal_body .subject-cb:checked');
    const subjects = Array.from(checked).map(cb => ({ sch_sub_id: cb.dataset.subId }));

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url:  '<?= base_url('admission/save-teaching-subjects/') ?>' + ADMISSION_ID,
        type: 'POST',
        data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>', subjects },
        success: function (response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_teaching_subject')).hide();
                Swal.fire({ title: 'Added!', text: response.message, icon: 'success',
                    timer: 1500, showConfirmButton: false }).then(() => location.reload());
            } else {
                Swal.fire({ title: 'Failed', text: response.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close',
                    customClass: { confirmButton: 'btn btn-danger' } });
            }
        }
    });
});

// ── Delete individual teaching subject ───────────────────────────
document.getElementById('teaching_subjects_container').addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-delete-subject');
    if (!btn) return;

    const admTeachSubId = btn.dataset.admTeachSubId;
    const subjectName   = btn.dataset.subjectName;

    Swal.fire({
        title:             'Remove Subject?',
        html:              `Remove <strong>${subjectName}</strong> from this teacher's teaching subjects?`,
        icon:              'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, Remove',
        cancelButtonText:  'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-3',
            cancelButton:  'btn btn-light',
        }
    }).then(result => {
        if (!result.isConfirmed) return;

        $.ajax({
            url:  '<?= base_url('admission/delete-teaching-subject/') ?>' + admTeachSubId,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    // 1. Remove sch_sub_id from the live tracking Set (using reliable data attribute)
                    const schSubId = btn.dataset.schSubId;
                    if (schSubId) assignedSchSubIds.delete(String(schSubId));

                    // 2. Remove the DOM row
                    const row = document.querySelector(`.subject-row[data-adm-teach-sub-id="${admTeachSubId}"]`);
                    if (row) {
                        const group = row.closest('[data-dept-group]');
                        row.remove();
                        if (group && group.querySelectorAll('.subject-row').length === 0) group.remove();
                    }

                    // 3. Show empty state if nothing left
                    if (document.querySelectorAll('.subject-row').length === 0) {
                        document.getElementById('teaching_subjects_container').innerHTML =
                            '<div class="text-center text-muted py-6">' +
                            '<i class="ki-duotone ki-book fs-3x text-muted mb-3 d-block"><span class="path1"></span><span class="path2"></span></i>' +
                            '<div class="fw-semibold fs-7">No teaching subjects assigned.</div>' +
                            '<div class="fs-8 mt-1">Click "Add Subject" to assign.</div></div>';
                    }

                    // 4. Force modal to rebuild with updated assigned list next open
                    subjectsCacheLoaded = false;

                    // 5. Enable/disable Add Subject button based on current count
                    const addBtn  = document.getElementById('btn_open_subject_modal');
                    const underLimit = assignedSchSubIds.size < MAX_EDIT_SUBJECTS;
                    if (addBtn) {
                        addBtn.disabled = !underLimit;
                        addBtn.title    = underLimit ? '' : 'Maximum 7 subjects reached — delete one first';
                    }
                } else {
                    Swal.fire({ title: 'Failed', text: response.message, icon: 'error',
                        buttonsStyling: false, confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' } });
                }
            }
        });
    });
});

// ================================================================
// TEACHER — HOD (updates hidden field, no separate save button)
// ================================================================

let departmentsLoaded = false;
let existingHodDeptId = <?= $hodRecord ? (int)$hodRecord['sch_dept_id'] : 'null' ?>;

const hodSwitch   = document.getElementById('switch_is_hod');
const hodSection  = document.getElementById('hod_dept_section');
const hiddenIsHod = document.getElementById('hidden_is_hod');
const hiddenDept  = document.getElementById('hidden_hod_dept_id');

if (hodSwitch) {
    if (hodSwitch.checked) loadDepartments();

    hodSwitch.addEventListener('change', function () {
        hiddenIsHod.value = this.checked ? '1' : '0';
        if (this.checked) {
            hodSection.style.display = '';
            loadDepartments();
        } else {
            hodSection.style.display = 'none';
            hiddenDept.value = '0';
        }
    });
}

function loadDepartments() {
    if (departmentsLoaded) return;

    const select = document.getElementById('hod_dept_select');
    select.innerHTML = '<option value="">Loading departments...</option>';

    fetch('<?= base_url('admission/school-departments/') ?>' + SCH_ID)
        .then(r => r.json())
        .then(data => {
            departmentsLoaded = true;

            if (!data.success || data.departments.length === 0) {
                select.innerHTML = '<option value="">No departments configured for this school</option>';
                return;
            }

            select.innerHTML = '<option value="">Select department...</option>';
            data.departments.forEach(d => {
                const opt = document.createElement('option');
                opt.value       = d.sch_dept_id;
                opt.textContent = d.dept_name;
                if (existingHodDeptId && String(d.sch_dept_id) === String(existingHodDeptId)) {
                    opt.selected = true;
                    hiddenDept.value = d.sch_dept_id; // ensure hidden is set
                }
                select.appendChild(opt);
            });

            // Keep hidden field in sync when user changes selection
            select.addEventListener('change', function () {
                hiddenDept.value = this.value;
            });
        });
}

<?php endif; // end Teacher ?>

// ================================================================
// STUDENT — LEADERSHIP ROLE (updates hidden field, no separate save)
// ================================================================
<?php if ($roleCatId === 4): ?>

const roleColors = {
    head_boy: 'danger', head_girl: 'danger',
    deputy_head_boy: 'warning', deputy_head_girl: 'warning',
    school_prefect: 'primary', hostel_prefect: 'info',
    junior_prefect: 'warning', relieving_prefect: 'secondary',
};

const hiddenRoleInput = document.getElementById('hidden_leadership_role');

function updatePrefectUI(selectedValue) {
    document.querySelectorAll('.prefect-radio').forEach(radio => {
        const label     = radio.closest('label');
        const indicator = label.querySelector('.role-check-indicator');
        const color     = roleColors[radio.value] || 'primary';

        if (selectedValue && radio.value !== selectedValue) {
            label.style.opacity       = '0.45';
            label.style.pointerEvents = 'none';
            label.classList.remove('border-' + color, 'bg-light-' + color);
            if (indicator) indicator.classList.add('d-none');
        } else if (radio.value === selectedValue) {
            label.style.opacity       = '1';
            label.style.pointerEvents = 'auto';
            label.classList.add('border-' + color, 'bg-light-' + color);
            label.classList.remove('border-gray-300');
            if (indicator) indicator.classList.remove('d-none');
        } else {
            label.style.opacity       = '1';
            label.style.pointerEvents = 'auto';
            label.classList.remove('border-' + color, 'bg-light-' + color);
            label.classList.add('border-gray-300');
            if (indicator) indicator.classList.add('d-none');
        }
    });
}

// Init on page load
const checkedRadio = document.querySelector('.prefect-radio:checked');
if (checkedRadio) updatePrefectUI(checkedRadio.value);

document.querySelectorAll('.prefect-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        hiddenRoleInput.value = this.value;  // sync hidden field
        updatePrefectUI(this.value);
    });
});

document.getElementById('btn_clear_role')?.addEventListener('click', function () {
    document.querySelectorAll('.prefect-radio').forEach(r => r.checked = false);
    hiddenRoleInput.value = '';  // clear hidden field
    updatePrefectUI(null);
});

<?php endif; // end Student ?>

// ================================================================
// MAIN SAVE — captures everything in one submit
// ================================================================

document.getElementById('btn_update_admission').addEventListener('click', function () {
    const btn = this;

    if (!document.querySelector('[name="admission_date"]').value) {
        Swal.fire({
            title:             'Validation Error',
            text:              'Please enter the admission date.',
            icon:              'warning',
            buttonsStyling:    false,
            confirmButtonText: 'OK',
            customClass:       { confirmButton: 'btn btn-warning' }
        });
        return;
    }

    // Validate HOD — if switch is on, a department must be selected
    <?php if (in_array($roleCatId, [2, 3, 5])): ?>
    const isHodOn   = document.getElementById('switch_is_hod')?.checked;
    const hodDeptId = document.getElementById('hidden_hod_dept_id')?.value;
    if (isHodOn && (!hodDeptId || hodDeptId === '0')) {
        Swal.fire({
            title:             'Select Department',
            text:              'Head of Department is enabled. Please select a department.',
            icon:              'warning',
            buttonsStyling:    false,
            confirmButtonText: 'OK',
            customClass:       { confirmButton: 'btn btn-warning' }
        });
        return;
    }
    <?php endif; ?>

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    const formData = new FormData(document.getElementById('edit_admission_form'));

    $.ajax({
        url:         '<?= base_url('admission/update/' . $admissionId) ?>',
        type:        'POST',
        data:        formData,
        processData: false,
        contentType: false,
        success: function (response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;

            if (response.success) {
                Swal.fire({
                    title:             'Updated!',
                    text:              response.message,
                    icon:              'success',
                    timer:             1500,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = response.redirect;
                });
            } else if (response.active_conflict) {
                Swal.fire({
                    title:             'Active Admission Exists',
                    text:              response.message,
                    icon:              'warning',
                    buttonsStyling:    false,
                    confirmButtonText: 'OK',
                    customClass:       { confirmButton: 'btn btn-warning' }
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
        error: function () {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            Swal.fire({
                title:             'Error',
                text:              'An error occurred. Please try again.',
                icon:              'error',
                buttonsStyling:    false,
                confirmButtonText: 'Close',
                customClass:       { confirmButton: 'btn btn-danger' }
            });
        }
    });
});
</script>

