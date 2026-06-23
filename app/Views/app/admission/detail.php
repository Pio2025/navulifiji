<style>
.adm-detail-row {
    border-bottom: 1px dashed #c4c7d0;
}
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Admission Detail
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
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('admission/edit/' . $admission['admission_id']) ?>"
               class="btn btn-sm btn-light-warning">
                <i class="ki-duotone ki-pencil fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Edit
            </a>
            <?php endif; ?>
            <?php if ($canDelete): ?>
            <button type="button"
                    class="btn btn-sm btn-light-danger"
                    id="btn_delete_from_detail"
                    data-id="<?= $admission['admission_id'] ?>"
                    data-name="<?= esc(trim($admission['fname'] . ' ' . $admission['lname'])) ?>">
                <i class="ki-duotone ki-trash fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                </i>
                Delete
            </button>
            <?php endif; ?>
            <a href="<?= base_url('admission') ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Back
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <!--begin::Left Column-->
    <div class="col-lg-4">

        <!--begin::User Card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body text-center p-8">
                <div class="symbol symbol-80px symbol-circle mx-auto mb-5">
                    <?php if (!empty($admission['profile_photo'])): ?>
                        <img src="<?= base_url('uploads/profilePhoto/' . $admission['profile_photo']) ?>"
                             alt="<?= esc($admission['fname']) ?>" />
                    <?php else: ?>
                        <div class="symbol-label fs-1 fw-bold bg-light-primary text-primary">
                            <?= strtoupper(substr($admission['fname'] ?? 'U', 0, 1) . substr($admission['lname'] ?? '', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h4 class="fw-bold text-gray-900 mb-1">
                    <?= esc(trim($admission['fname'] . ' ' . ($admission['oname'] ? $admission['oname'] . ' ' : '') . $admission['lname'])) ?>
                </h4>
                <?php if (!empty($admission['email'])): ?>
                <div class="text-muted fs-7 mb-2"><?= esc($admission['email']) ?></div>
                <?php endif; ?>
                <?php if (!empty($admission['role_name'])): ?>
                <span class="badge badge-light-primary mb-3"><?= esc($admission['role_name']) ?></span>
                <?php endif; ?>

                <div class="separator my-4"></div>

                <?php
                $statusColors = ['Active' => 'success', 'Pending' => 'warning', 'Inactive' => 'danger', 'Rejected' => 'danger'];
                $statusColor  = $statusColors[$admission['admission_status'] ?? ''] ?? 'secondary';
                ?>
                <span class="badge badge-light-<?= $statusColor ?> fs-6 fw-bold px-5 py-3">
                    <?= esc($admission['admission_status'] ?? 'Unknown') ?>
                </span>

                <div class="mt-4">
                    <a href="<?= base_url('user/detail/' . $admission['user_id']) ?>"
                       class="btn btn-light-info btn-sm w-100">
                        <i class="ki-duotone ki-profile-circle fs-4 me-1">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        View User Profile
                    </a>
                </div>
            </div>
        </div>
        <!--end::User Card-->

        <!--begin::School Card-->
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7">School</h6>
            </div>
            <div class="card-body pt-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <?php
                    $logoPath = FCPATH . 'uploads/school/logo/' . ($admission['sch_logo'] ?? '');
                    if (!empty($admission['sch_logo']) && file_exists($logoPath)):
                    ?>
                    <img src="<?= base_url('uploads/school/logo/' . $admission['sch_logo']) ?>"
                         class="rounded w-45px h-45px object-fit-cover flex-shrink-0" />
                    <?php else: ?>
                    <div class="symbol symbol-45px flex-shrink-0">
                        <div class="symbol-label bg-light-primary">
                            <i class="ki-duotone ki-bank fs-2 text-primary">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div>
                        <div class="fw-bold text-gray-800 fs-7"><?= esc($admission['sch_name'] ?? '—') ?></div>
                        <div class="text-muted fs-8"><?= esc($admission['sch_address'] ?? '') ?></div>
                    </div>
                </div>
                <?php if (!empty($admission['sch_phone'])): ?>
                <div class="d-flex justify-content-between py-2 adm-detail-row">
                    <span class="text-muted fs-8">Phone</span>
                    <span class="text-gray-800 fw-semibold fs-8"><?= esc($admission['sch_phone']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($admission['sch_email'])): ?>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted fs-8">Email</span>
                    <span class="text-gray-800 fw-semibold fs-8"><?= esc($admission['sch_email']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::School Card-->

        <!--begin::Previous Admissions Card-->
        <?php if (!empty($previousAdmissions)): ?>
        <div class="card shadow-sm border-0 mt-5">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7 mb-0">
                    <i class="ki-duotone ki-time fs-4 text-muted me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Previous Admission Records
                    <span class="badge badge-light-secondary ms-1 fs-9"><?= count($previousAdmissions) ?></span>
                </h6>
            </div>
            <div class="card-body pt-3 pb-4">
                <?php foreach ($previousAdmissions as $prev):
                    $prevColor = ['Active'=>'success','Pending'=>'warning','Completed'=>'info','Rejected'=>'danger'][$prev['admission_status']] ?? 'secondary';
                ?>
                <div class="d-flex align-items-center gap-3 py-2 adm-detail-row">
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-gray-700 fs-8">
                            <a href="<?= base_url('admission/detail/' . $prev['admission_id']) ?>"
                               class="text-gray-700 text-hover-primary">
                                #<?= str_pad($prev['admission_id'], 6, '0', STR_PAD_LEFT) ?>
                            </a>
                        </div>
                        <div class="text-muted fs-9"><?= esc($prev['sch_name']) ?></div>
                    </div>
                    <div class="text-end">
                        <span class="badge badge-light-<?= $prevColor ?> fs-9"><?= esc($prev['admission_status']) ?></span>
                        <div class="text-muted fs-9 mt-1"><?= !empty($prev['admission_date']) ? date('d M Y', strtotime($prev['admission_date'])) : '—' ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <!--end::Previous Admissions Card-->

    </div>
    <!--end::Left Column-->

    <!--begin::Right Column-->
    <div class="col-lg-8">

        <!--begin::Admission Details Card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">Admission Information</h5>
            </div>
            <div class="card-body pt-2">
                <?php
                $details = [
                    ['Admission ID',    '#' . str_pad($admission['admission_id'], 6, '0', STR_PAD_LEFT)],
                    ['Admission Date',  !empty($admission['admission_date']) ? date('d F Y', strtotime($admission['admission_date'])) : '—'],
                    ['Status',          $admission['admission_status'] ?? '—'],
                    ['Role',            $admission['role_name'] ?? '—'],
                    ['Role Category',   $admission['role_cat_name'] ?? '—'],
                    ['Gender',          ucfirst($admission['gender'] ?? '—')],
                    ['Date of Birth',   !empty($admission['dob']) ? date('d F Y', strtotime($admission['dob'])) : '—'],
                    ['Phone',           $admission['phone'] ?? '—'],
                    ['Address',         $admission['address'] ?? '—'],
                ];
                foreach ($details as $i => $row):
                    $isLast = ($i === count($details) - 1);
                ?>
                <div class="d-flex justify-content-between py-3 <?= !$isLast ? 'adm-detail-row' : '' ?>">
                    <span class="text-gray-600 fw-semibold fs-7"><?= $row[0] ?></span>
                    <span class="text-gray-800 fw-bold fs-7 text-end"><?= esc($row[1]) ?></span>
                </div>
                <?php endforeach; ?>

                <?php if (!empty($admission['admission_note'])): ?>
                <div class="mt-4 p-4 bg-light rounded">
                    <div class="fw-bold text-gray-700 fs-7 mb-2">Admission Note</div>
                    <p class="text-muted fs-7 mb-0"><?= nl2br(esc($admission['admission_note'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Admission Details Card-->

        <!--begin::Enrolment Records (hidden for Teachers)-->
        <?php if ($roleCatId !== 3): ?>
        <div class="card shadow-sm mb-5 border-0">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">
                    Enrolment Records
                    <span class="badge badge-light-primary ms-2"><?= count($enrolments) ?></span>
                </h5>
            </div>
            <div class="card-body pt-2">
                <?php if (empty($enrolments)): ?>
                <div class="text-center py-10">
                    <i class="ki-duotone ki-abstract-28 fs-4x text-muted mb-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="text-muted fs-7 fw-semibold">No enrolment records found</div>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-7 gy-3">
                        <thead class="border-bottom border-gray-200 fs-8 fw-bold text-uppercase text-muted">
                            <tr>
                                <th>Year</th>
                                <th>Term</th>
                                <th>Stream</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th class="text-end">Enrolled</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            <?php foreach ($enrolments as $enrol):
                                $enrolColor = $statusColors[$enrol['enrol_status'] ?? ''] ?? 'info';
                            ?>
                            <tr>
                                <td class="fw-bold text-gray-800"><?= esc($enrol['enrol_year'] ?? '—') ?></td>
                                <td>Term <?= esc($enrol['enrol_term'] ?? '—') ?></td>
                                <td><?= esc($enrol['stream_name'] ?? '—') ?></td>
                                <td><?= esc($enrol['level_name'] ?? '—') ?></td>
                                <td>
                                    <span class="badge badge-light-<?= $enrolColor ?> fs-9">
                                        <?= esc($enrol['enrol_status'] ?? '—') ?>
                                    </span>
                                </td>
                                <td class="text-end text-muted fs-8">
                                    <?= !empty($enrol['enrol_date']) ? date('d M Y', strtotime($enrol['enrol_date'])) : '—' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Enrolment Records-->
        <?php endif; // not Teacher ?>

        <!--begin::Teaching Subjects Card (Teacher roles)-->
        <?php if (in_array($roleCatId, [2, 3, 5])): ?>
        
        <!--begin::HOD Card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">
                    <i class="ki-duotone ki-crown-2 fs-3 text-warning me-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Head of Department
                </h5>
            </div>
            <div class="card-body pt-2 pb-5">
                <?php if ($hodRecord): ?>
                <div class="d-flex align-items-center gap-4 p-4 bg-light-warning rounded-3">
                    <div class="symbol symbol-50px flex-shrink-0">
                        <div class="symbol-label bg-warning">
                            <i class="ki-duotone ki-crown-2 fs-2 text-white">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            
                        </div>
                    </div>
                    <div>
                        <div class="fw-bold text-gray-800 fs-6">
                            <?= esc($hodRecord['dept_name'] ?? '—') ?>
                        </div>
                        <div class="text-muted fs-8 mt-1">Assigned as Head of Department</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge badge-light-warning fs-8 fw-bold px-4 py-2">HOD</span>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-8 text-muted">
                    <i class="ki-duotone ki-crown-2 fs-3x text-muted mb-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="fw-semibold fs-7">Not assigned as Head of Department</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::HOD Card-->
        
        <!--begin::Teaching Subjects Card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">
                    <i class="ki-duotone ki-book-open fs-3 text-primary me-2">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span>
                    </i>
                    Teaching Subjects
                    <?php if (!empty($teachingSubjects)): ?>
                    <span class="badge badge-light-primary ms-2"><?= count($teachingSubjects) ?></span>
                    <?php endif; ?>
                </h5>
                <?php if ($canEdit): ?>
                <div class="card-toolbar">
                    <a href="<?= base_url('admission/edit/' . $admission['admission_id']) ?>"
                       class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-pencil fs-5 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Manage
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-body pt-3 pb-5">
                <?php if (empty($teachingSubjects)): ?>
                <div class="text-center py-8 text-muted">
                    <i class="ki-duotone ki-book fs-3x text-muted mb-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="fw-semibold fs-7">No teaching subjects assigned.</div>
                </div>
                <?php else: ?>
                <?php
                $byDept = [];
                foreach ($teachingSubjects as $ts) {
                    $byDept[$ts['dept_name'] ?? 'General'][] = $ts;
                }
                ?>
                <?php foreach ($byDept as $deptName => $subs): ?>
                <div class="mb-4">
                    <div class="text-gray-500 fw-bold fs-8 text-uppercase ls-1 mb-2"><?= esc($deptName) ?>
                        <span class="badge badge-light-secondary ms-1 fs-9"><?= count($subs) ?></span>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($subs as $sub): ?>
                        <div class="d-flex align-items-center gap-3 px-4 bg-light rounded-2"
                             style="height:2.75rem;">
                            <i class="ki-duotone ki-book fs-6 text-primary flex-shrink-0">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <span class="text-gray-700 fw-semibold fs-7 flex-grow-1"><?= esc($sub['subject_name']) ?></span>
                            <?php
                            $stColor = match($sub['adm_teach_sub_status'] ?? 'Active') {
                                'Active'    => 'success',
                                'Completed' => 'info',
                                'Pending'   => 'warning',
                                'Rejected'  => 'danger',
                                default     => 'secondary',
                            };
                            ?>
                            <span class="badge badge-light-<?= $stColor ?> fs-9"><?= esc($sub['adm_teach_sub_status'] ?? 'Active') ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Teaching Subjects Card-->
        
        <?php endif; // Teacher roles ?>
        
        <!--begin::Student Leadership Role Card-->
        <?php if ($roleCatId === 4): ?>
        
        <?php
        $roleLabels = [
            'head_boy'          => ['label' => 'Head Boy',          'icon' => 'ki-crown-2',     'color' => 'danger'],
            'head_girl'         => ['label' => 'Head Girl',         'icon' => 'ki-crown-2',     'color' => 'danger'],
            'deputy_head_boy'   => ['label' => 'Deputy Head Boy',   'icon' => 'ki-award',       'color' => 'warning'],
            'deputy_head_girl'  => ['label' => 'Deputy Head Girl',  'icon' => 'ki-award',       'color' => 'warning'],
            'school_prefect'    => ['label' => 'School Prefect',    'icon' => 'ki-shield-tick', 'color' => 'primary'],
            'hostel_prefect'    => ['label' => 'Hostel Prefect',    'icon' => 'ki-home-1',      'color' => 'info'],
            'junior_prefect'    => ['label' => 'Junior Prefect',    'icon' => 'ki-star',        'color' => 'warning'],
            'relieving_prefect' => ['label' => 'Relieving Prefect', 'icon' => 'ki-time',        'color' => 'secondary'],
        ];
        $currentRole    = $studentRole['leadership_role'] ?? '';
        $currentRoleInfo = $roleLabels[$currentRole] ?? null;
        ?>
        
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">
                    <i class="ki-duotone ki-medal-star fs-3 text-primary me-2">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Leadership / Prefect Role
                </h5>
                <?php if ($canEdit): ?>
                <div class="card-toolbar">
                    <a href="<?= base_url('admission/edit/' . $admission['admission_id']) ?>"
                       class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-pencil fs-5 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Edit
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-body pt-3 pb-5">
                <?php if ($currentRoleInfo): ?>
                <div class="d-flex align-items-center gap-4 p-4 bg-light-<?= $currentRoleInfo['color'] ?> rounded-3">
                    <div class="symbol symbol-55px flex-shrink-0">
                        <div class="symbol-label bg-<?= $currentRoleInfo['color'] ?>">
                            <i class="ki-duotone <?= $currentRoleInfo['icon'] ?> fs-2 text-white">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-bold text-gray-800 fs-5">
                            <?= esc($currentRoleInfo['label']) ?>
                        </div>
                        <div class="text-muted fs-8 mt-1">
                            Current assigned leadership role
                        </div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge badge-light-<?= $currentRoleInfo['color'] ?> fs-7 fw-bold px-4 py-2">
                            Active
                        </span>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-8 text-muted">
                    <i class="ki-duotone ki-medal-star fs-3x text-muted mb-3">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <div class="fw-semibold fs-7">No leadership or prefect role assigned.</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Student Leadership Role Card-->
        
        <?php endif; // Student ?>

    </div>
    <!--end::Right Column-->

</div>
</div>
</div>
<!--end::Content-->

<script>
"use strict";

<?php if (session()->getFlashdata('edit_restricted')): ?>
Swal.fire({
    title:             'Editing Restricted',
    html:              '<?= addslashes(session()->getFlashdata('edit_restricted')) ?>',
    icon:              'warning',
    buttonsStyling:    false,
    confirmButtonText: 'Understood',
    customClass:       { confirmButton: 'btn btn-warning' },
});
<?php endif; ?>

<?php if ($canDelete): ?>
document.getElementById('btn_delete_from_detail')?.addEventListener('click', function() {
    const id   = this.dataset.id;
    const name = this.dataset.name;

    Swal.fire({
        title: 'Delete Admission?',
        html:
            '<p class="text-gray-700 mb-3 fs-6">Delete admission for <strong>' + name + '</strong>?</p>' +
            '<p class="text-danger fw-semibold fs-8 mb-0">All enrolment records will also be permanently deleted.</p>',
        icon:              'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-3',
            cancelButton:  'btn btn-light',
        },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;

        $.ajax({
            url:  '<?= base_url('admission/delete') ?>/' + id,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title:             'Deleted!',
                        text:              response.message,
                        icon:              'success',
                        timer:             1500,
                        showConfirmButton: false,
                    }).then(() => {
                        window.location.href = '<?= base_url('admission') ?>';
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
            }
        });
    });
});
<?php endif; ?>
</script>