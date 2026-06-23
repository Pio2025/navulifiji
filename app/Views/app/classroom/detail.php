<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Classroom Detail
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
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('classroom/edit/' . $classroom['class_id']) ?>"
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
                    id="btn_delete_classroom"
                    data-id="<?= $classroom['class_id'] ?>"
                    data-name="<?= esc($classroom['class_name']) ?>">
                <i class="ki-duotone ki-trash fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                </i>
                Delete
            </button>
            <?php endif; ?>
            <a href="<?= base_url('classroom') ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Back
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">
<div class="row g-6">

    <!--begin::Left sidebar-->
    <div class="col-lg-4">

        <!--begin::Classroom identity card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body text-center p-8">
                <div class="symbol symbol-80px mx-auto mb-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-element-7 fs-2tx text-primary">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <h4 class="fw-bold text-gray-900 mb-1"><?= esc($classroom['class_name']) ?></h4>
                <div class="text-muted fs-7 mb-1"><?= esc($classroom['stream_name'] ?? '—') ?></div>
                <div class="text-muted fs-8 mb-3"><?= esc($classroom['level_name'] ?? '') ?></div>

                <?php
                $statusColor = match($classroom['class_status']) {
                    'Active'   => 'success',
                    'Inactive' => 'danger',
                    'Archived' => 'secondary',
                    default    => 'secondary'
                };
                ?>
                <span class="badge badge-light-<?= $statusColor ?> fs-6 fw-bold px-5 py-3">
                    <?= esc($classroom['class_status']) ?>
                </span>
            </div>
        </div>
        <!--end::Classroom identity card-->

        <!--begin::School card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7">
                    <i class="ki-duotone ki-bank fs-4 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    School
                </h6>
            </div>
            <div class="card-body pt-3 pb-5">
                <div class="d-flex align-items-center gap-3">
                    <?php
                    $logoPath = FCPATH . 'uploads/school/logo/' . ($classroom['sch_logo'] ?? '');
                    if (!empty($classroom['sch_logo']) && file_exists($logoPath)):
                    ?>
                    <img src="<?= base_url('uploads/school/logo/' . $classroom['sch_logo']) ?>"
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
                        <div class="fw-bold text-gray-800 fs-7"><?= esc($classroom['sch_name'] ?? '—') ?></div>
                        <div class="text-muted fs-8"><?= esc($classroom['sch_address'] ?? '') ?></div>
                    </div>
                </div>
                <?php if (!empty($classroom['sch_phone']) || !empty($classroom['sch_email'])): ?>
                <div class="separator my-3"></div>
                <?php if (!empty($classroom['sch_phone'])): ?>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted fs-8">Phone</span>
                    <span class="text-gray-700 fw-semibold fs-8"><?= esc($classroom['sch_phone']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($classroom['sch_email'])): ?>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted fs-8">Email</span>
                    <span class="text-gray-700 fw-semibold fs-8"><?= esc($classroom['sch_email']) ?></span>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <!--end::School card-->

        <!--begin::Audit card-->
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7">
                    <i class="ki-duotone ki-time fs-4 text-muted me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Audit
                </h6>
            </div>
            <div class="card-body pt-3 pb-5">
                <?php
                $auditRows = [
                    ['Created',     !empty($classroom['class_created_at']) ? date('d M Y H:i', strtotime($classroom['class_created_at'])) : '—'],
                    ['Created By',  trim(($classroom['creator_fname'] ?? '') . ' ' . ($classroom['creator_lname'] ?? '')) ?: '—'],
                    ['Last Updated',!empty($classroom['class_updated_at']) ? date('d M Y H:i', strtotime($classroom['class_updated_at'])) : '—'],
                    ['Updated By',  trim(($classroom['updater_fname'] ?? '') . ' ' . ($classroom['updater_lname'] ?? '')) ?: '—'],
                ];
                foreach ($auditRows as $i => $row):
                    $isLast = $i === count($auditRows) - 1;
                ?>
                <div class="d-flex justify-content-between py-2
                    <?= !$isLast ? 'info-row-border' : '' ?>">
                    <span class="text-muted fs-8"><?= $row[0] ?></span>
                    <span class="text-gray-700 fw-semibold fs-8 text-end"><?= esc($row[1]) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!--end::Audit card-->

        <!--begin::Classroom Subjects card-->
        <?php
        $coreSubjects     = $classroomSubjects['core'];
        $optionalSubjects = $classroomSubjects['optional'];
        $hasSubjects      = !empty($coreSubjects) || !empty($optionalSubjects);
        function subjectTeacherInitials(string $name): string {
            $parts = array_filter(explode(' ', $name));
            return strtoupper(implode('', array_map(fn($w) => $w[0], $parts)));
        }
        ?>
        <div class="card shadow-sm mt-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5 pb-0">
                <h6 class="card-title fw-bold text-gray-800 fs-7">
                    <i class="ki-duotone ki-book fs-4 text-success me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Classroom Subjects
                </h6>
                <div class="card-toolbar d-flex align-items-center gap-2">
                    <?php if ($hasSubjects): ?>
                    <span class="badge badge-light fs-9 fw-semibold">
                        <?= count($coreSubjects) + array_sum(array_map('count', $optionalSubjects)) ?> subject<?= (count($coreSubjects) + array_sum(array_map('count', $optionalSubjects))) !== 1 ? 's' : '' ?>
                    </span>
                    <button type="button" class="btn btn-xs btn-light-success"
                            data-bs-toggle="modal" data-bs-target="#modal_add_classroom_subject"
                            style="font-size:10px;padding:3px 8px;">
                        <i class="ki-duotone ki-plus fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Add
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body pt-3 pb-5">

                <?php if (!$hasSubjects): ?>
                <!--begin::Empty state-->
                <div class="text-center py-6">
                    <i class="ki-duotone ki-book fs-3tx text-gray-200 mb-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="text-muted fs-8 mb-3">No subjects added to this classroom yet.</div>
                    <button type="button" class="btn btn-sm btn-light-success"
                            data-bs-toggle="modal" data-bs-target="#modal_add_classroom_subject">
                        <i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Add Classroom Subject
                    </button>
                </div>
                <!--end::Empty state-->
                <?php else: ?>

                <!--begin::Core Subjects-->
                <?php if (!empty($coreSubjects)): ?>
                <div class="<?= !empty($optionalSubjects) ? 'mb-5' : '' ?>">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-light-success fw-bold fs-9 me-2">Core</span>
                        <span class="text-muted fs-9"><?= count($coreSubjects) ?> subject<?= count($coreSubjects) !== 1 ? 's' : '' ?></span>
                    </div>
                    <?php foreach ($coreSubjects as $i => $sub):
                        $isLast = $i === count($coreSubjects) - 1;
                        $tInit  = $sub['teacher_name'] ? subjectTeacherInitials($sub['teacher_name']) : '';
                    ?>
                    <div class="d-flex align-items-center justify-content-between py-2 <?= !$isLast ? 'info-row-border' : '' ?>">
                        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                            <i class="ki-duotone ki-book-open fs-6 text-success flex-shrink-0">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <span class="fw-semibold text-gray-800 fs-8 text-truncate"><?= esc($sub['subject_name']) ?></span>
                        </div>
                        <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-2">
                            <?php if ($sub['teacher_id']): ?>
                            <div class="symbol symbol-25px" title="<?= esc($sub['teacher_name']) ?>">
                                <?php if (!empty($sub['teacher_photo'])): ?>
                                <img src="<?= base_url('uploads/profilePhoto/' . $sub['teacher_photo']) ?>"
                                     class="rounded-circle" style="width:25px;height:25px;object-fit:cover;" />
                                <?php else: ?>
                                <div class="symbol-label bg-light-primary fs-9 fw-bold text-primary" style="width:25px;height:25px;"><?= substr($tInit, 0, 2) ?></div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <button class="btn btn-xs <?= $sub['teacher_id'] ? 'btn-icon btn-light' : 'btn-light-success' ?> btn-assign-cls-subject"
                                    data-class-sub-id="<?= $sub['class_sub_id'] ?>"
                                    data-class-id="<?= $classroom['class_id'] ?>"
                                    data-subject-name="<?= esc($sub['subject_name']) ?>"
                                    title="<?= $sub['teacher_id'] ? 'Change teacher' : 'Assign teacher' ?>"
                                    style="<?= $sub['teacher_id'] ? 'width:22px;height:22px;' : 'font-size:10px;padding:2px 6px;' ?>">
                                <?php if ($sub['teacher_id']): ?>
                                <i class="ki-duotone ki-arrows-circle fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                <?php else: ?>
                                <i class="ki-duotone ki-plus fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Assign
                                <?php endif; ?>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <!--end::Core Subjects-->

                <!--begin::Optional Subjects-->
                <?php if (!empty($optionalSubjects)): ?>
                <div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-light-warning fw-bold fs-9 me-2">Optional</span>
                        <span class="text-muted fs-9"><?= count($optionalSubjects) ?> group<?= count($optionalSubjects) !== 1 ? 's' : '' ?></span>
                    </div>
                    <?php $grpIdx = 0; foreach ($optionalSubjects as $optNum => $group): $grpIdx++; ?>
                    <div class="<?= $grpIdx < count($optionalSubjects) ? 'mb-4' : '' ?>">
                        <div class="mb-1"><span class="badge badge-secondary fs-9 fw-semibold">Option <?= $grpIdx ?></span></div>
                        <?php foreach ($group as $j => $sub):
                            $isLast = $j === count($group) - 1;
                            $tInit  = $sub['teacher_name'] ? subjectTeacherInitials($sub['teacher_name']) : '';
                        ?>
                        <div class="d-flex align-items-center justify-content-between py-2 <?= !$isLast ? 'info-row-border' : '' ?>">
                            <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                                <i class="ki-duotone ki-book fs-6 text-warning flex-shrink-0"><span class="path1"></span><span class="path2"></span></i>
                                <span class="fw-semibold text-gray-800 fs-8 text-truncate"><?= esc($sub['subject_name']) ?></span>
                            </div>
                            <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-2">
                                <?php if ($sub['teacher_id']): ?>
                                <div class="symbol symbol-25px" title="<?= esc($sub['teacher_name']) ?>">
                                    <?php if (!empty($sub['teacher_photo'])): ?>
                                    <img src="<?= base_url('uploads/profilePhoto/' . $sub['teacher_photo']) ?>"
                                         class="rounded-circle" style="width:25px;height:25px;object-fit:cover;" />
                                    <?php else: ?>
                                    <div class="symbol-label bg-light-warning fs-9 fw-bold text-warning" style="width:25px;height:25px;"><?= substr($tInit, 0, 2) ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <button class="btn btn-xs <?= $sub['teacher_id'] ? 'btn-icon btn-light' : 'btn-light-warning' ?> btn-assign-cls-subject"
                                        data-class-sub-id="<?= $sub['class_sub_id'] ?>"
                                        data-class-id="<?= $classroom['class_id'] ?>"
                                        data-subject-name="<?= esc($sub['subject_name']) ?>"
                                        title="<?= $sub['teacher_id'] ? 'Change teacher' : 'Assign teacher' ?>"
                                        style="<?= $sub['teacher_id'] ? 'width:22px;height:22px;' : 'font-size:10px;padding:2px 6px;' ?>">
                                    <?php if ($sub['teacher_id']): ?>
                                    <i class="ki-duotone ki-arrows-circle fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    <?php else: ?>
                                    <i class="ki-duotone ki-plus fs-8 me-1"><span class="path1"></span><span class="path2"></span></i>Assign
                                    <?php endif; ?>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <!--end::Optional Subjects-->

                <?php endif; ?>
            </div>
        </div>
        <!--end::Classroom Subjects card-->

    </div>
    <!--end::Left sidebar-->

    <!--begin::Right content-->
    <div class="col-lg-8">

        <!--begin::Info card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">Classroom Information</h5>
            </div>
            <div class="card-body pt-2">
                <?php
                $details = [
                    ['Classroom ID',  '#' . str_pad($classroom['class_id'], 6, '0', STR_PAD_LEFT)],
                    ['Classroom Name', $classroom['class_name']],
                    ['Academic Year',  $classroom['class_year']],
                    ['Stream',         $classroom['stream_name'] ?? '—'],
                    ['Year Level',     $classroom['level_name']  ?? '—'],
                    ['School',         $classroom['sch_name']    ?? '—'],
                    ['Students',       count($classroomStudents)],
                    ['Status',         $classroom['class_status']],
                ];
                foreach ($details as $i => $row):
                    $isLast = $i === count($details) - 1;
                ?>
                <div class="d-flex justify-content-between py-3 <?= !$isLast ? 'info-row-border' : '' ?>">
                    <span class="text-muted fw-semibold fs-7"><?= esc($row[0]) ?></span>
                    <span class="text-gray-800 fw-bold fs-7 text-end"><?= esc($row[1]) ?></span>
                </div>
                <?php endforeach; ?>
                <style>.info-row-border { border-bottom: 1px dashed #d4d4e0; }</style>
            </div>
        </div>
        <!--end::Info card-->

        <!--begin::Class Staff card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">
                    <i class="ki-duotone ki-teacher fs-3 text-primary me-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Class Staff
                </h5>
            </div>
            <div class="card-body pt-2">
                <?php
                $teacherRoles = ['Class Teacher', 'Assistant Class Teacher'];
                foreach ($teacherRoles as $i => $role):
                    $person = $staff[$role] ?? null;
                    $isLast = $i === count($teacherRoles) - 1;
                    $initials = $person
                        ? strtoupper(substr($person['fname'] ?? 'U', 0, 1) . substr($person['lname'] ?? '', 0, 1))
                        : '';
                ?>
                <div class="d-flex align-items-center justify-content-between py-4
                    <?= !$isLast ? 'info-row-border' : '' ?>">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <?php if ($person): ?>
                        <div class="symbol symbol-40px flex-shrink-0">
                            <?php if (!empty($person['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $person['profile_photo']) ?>"
                                 class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" />
                            <?php else: ?>
                            <div class="symbol-label bg-light-primary">
                                <span class="text-primary fw-bold fs-7"><?= $initials ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="fw-bold text-gray-800 fs-7">
                                <?= esc(trim(($person['fname'] ?? '') . ' ' . ($person['lname'] ?? ''))) ?>
                            </div>
                            <div class="text-muted fs-8"><?= esc($role) ?></div>
                        </div>
                        <?php else: ?>
                        <div class="symbol symbol-40px flex-shrink-0">
                            <div class="symbol-label bg-light-secondary">
                                <i class="ki-duotone ki-user fs-4 text-muted">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted fs-7 fst-italic">Not assigned</div>
                            <div class="text-muted fs-8"><?= esc($role) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-3">
                        <?php if ($person): ?>
                        <span class="badge badge-light-success fs-9 fw-semibold">Active</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ki-duotone ki-dots-vertical fs-5">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end fs-7 py-2">
                                <li>
                                    <a class="dropdown-item py-2 btn-assign-staff" href="#"
                                       data-role="<?= esc($role) ?>" data-type="staff"
                                       data-class-id="<?= $classroom['class_id'] ?>">
                                        <i class="ki-duotone ki-arrows-circle fs-6 me-2 text-primary">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        Change
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger btn-deactivate-staff" href="#"
                                       data-cs-id="<?= $person['cs_id'] ?>"
                                       data-role="<?= esc($role) ?>"
                                       data-name="<?= esc(trim(($person['fname'] ?? '') . ' ' . ($person['lname'] ?? ''))) ?>">
                                        <i class="ki-duotone ki-cross-circle fs-6 me-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        Deactivate
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php else: ?>
                        <button class="btn btn-sm btn-light-primary btn-assign-staff"
                                data-role="<?= esc($role) ?>" data-type="staff"
                                data-class-id="<?= $classroom['class_id'] ?>">
                            <i class="ki-duotone ki-plus fs-6 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Assign
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!--end::Class Staff card-->

        <!--begin::Class Captains card-->
        <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">
                    <i class="ki-duotone ki-crown fs-3 text-warning me-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Class Captains
                </h5>
            </div>
            <div class="card-body pt-2">
                <?php
                $captainRoles = ['Class Captain', 'Assistant Class Captain'];
                foreach ($captainRoles as $i => $role):
                    $person = $staff[$role] ?? null;
                    $isLast = $i === count($captainRoles) - 1;
                    $initials = $person
                        ? strtoupper(substr($person['fname'] ?? 'U', 0, 1) . substr($person['lname'] ?? '', 0, 1))
                        : '';
                ?>
                <div class="d-flex align-items-center justify-content-between py-4
                    <?= !$isLast ? 'info-row-border' : '' ?>">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <?php if ($person): ?>
                        <div class="symbol symbol-40px flex-shrink-0">
                            <?php if (!empty($person['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $person['profile_photo']) ?>"
                                 class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" />
                            <?php else: ?>
                            <div class="symbol-label bg-light-warning">
                                <span class="text-warning fw-bold fs-7"><?= $initials ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="fw-bold text-gray-800 fs-7">
                                <?= esc(trim(($person['fname'] ?? '') . ' ' . ($person['lname'] ?? ''))) ?>
                            </div>
                            <div class="text-muted fs-8"><?= esc($role) ?></div>
                        </div>
                        <?php else: ?>
                        <div class="symbol symbol-40px flex-shrink-0">
                            <div class="symbol-label bg-light-secondary">
                                <i class="ki-duotone ki-user fs-4 text-muted">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted fs-7 fst-italic">Not assigned</div>
                            <div class="text-muted fs-8"><?= esc($role) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-3">
                        <?php if ($person): ?>
                        <span class="badge badge-light-warning fs-9 fw-semibold">Active</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ki-duotone ki-dots-vertical fs-5">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end fs-7 py-2">
                                <li>
                                    <a class="dropdown-item py-2 btn-assign-staff" href="#"
                                       data-role="<?= esc($role) ?>" data-type="student"
                                       data-class-id="<?= $classroom['class_id'] ?>">
                                        <i class="ki-duotone ki-arrows-circle fs-6 me-2 text-primary">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        Change
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger btn-deactivate-staff" href="#"
                                       data-cs-id="<?= $person['cs_id'] ?>"
                                       data-role="<?= esc($role) ?>"
                                       data-name="<?= esc(trim(($person['fname'] ?? '') . ' ' . ($person['lname'] ?? ''))) ?>">
                                        <i class="ki-duotone ki-cross-circle fs-6 me-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        Deactivate
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php else: ?>
                        <button class="btn btn-sm btn-light-warning btn-assign-staff"
                                data-role="<?= esc($role) ?>" data-type="student"
                                data-class-id="<?= $classroom['class_id'] ?>">
                            <i class="ki-duotone ki-plus fs-6 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Assign
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!--end::Class Captains card-->

        <!--begin::Enrolled Students card-->
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-header border-0 pt-5">
                <h5 class="card-title fw-bold text-gray-800">
                    <i class="ki-duotone ki-people fs-3 text-info me-2">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    Enrolled Students
                </h5>
                <div class="card-toolbar d-flex align-items-center gap-3">
                    <span class="badge badge-light-info fs-7 fw-bold" id="student_count_badge">
                        <?= count($classroomStudents) ?> student<?= count($classroomStudents) !== 1 ? 's' : '' ?>
                    </span>
                    <button type="button" class="btn btn-sm btn-light-primary"
                            data-bs-toggle="modal" data-bs-target="#modal_admit_students">
                        <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Admit Student
                    </button>
                </div>
            </div>
            <div class="card-body pt-3 pb-4">
                <?php if (empty($classroomStudents)): ?>
                <div class="text-center py-8" id="students_empty_state">
                    <i class="ki-duotone ki-people fs-3tx text-gray-200 mb-3">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    <div class="text-muted fs-7 mb-4">No students admitted to this classroom yet.</div>
                    <button type="button" class="btn btn-primary btn-sm"
                            data-bs-toggle="modal" data-bs-target="#modal_admit_students">
                        <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Admit Student to Class
                    </button>
                </div>
                <?php endif; ?>
                <div class="table-responsive <?= empty($classroomStudents) ? 'd-none' : '' ?>" id="students_table_wrapper">
                    <table id="classroom_students_table"
                           class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3 mb-0">
                        <thead>
                            <tr class="fw-bold text-muted fs-8 text-uppercase">
                                <th class="ps-0 min-w-200px">Student</th>
                                <th class="text-center">Term</th>
                                <th class="text-center">Admitted</th>
                                <th class="text-center">Enrolment</th>
                                <th class="text-center">Status</th>
                                <?php if (!empty($canManageStudents)): ?>
                                <th class="text-center w-60px"></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($classroomStudents as $student):
                            $initials    = strtoupper(substr($student['fname'] ?? 'U', 0, 1) . substr($student['lname'] ?? '', 0, 1));
                            $enrolColor  = match($student['enrol_status'] ?? '') {
                                'Active'    => 'success',
                                'Completed' => 'primary',
                                'Inactive'  => 'danger',
                                default     => 'secondary',
                            };
                            $studColor = $student['class_stud_status'] === 'Active' ? 'success' : 'danger';
                        ?>
                        <tr id="stud_row_<?= $student['class_stud_id'] ?>">
                            <td class="ps-0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-35px flex-shrink-0">
                                        <?php if (!empty($student['profile_photo'])): ?>
                                        <img src="<?= base_url('uploads/profilePhoto/' . $student['profile_photo']) ?>"
                                             class="rounded-circle" style="width:35px;height:35px;object-fit:cover;" />
                                        <?php else: ?>
                                        <div class="symbol-label bg-light-info">
                                            <span class="text-info fw-bold fs-8"><?= $initials ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-gray-800 fs-7">
                                            <?= esc(trim($student['fname'] . ' ' . ($student['oname'] ? $student['oname'] . ' ' : '') . $student['lname'])) ?>
                                        </div>
                                        <div class="text-muted fs-8"><?= esc($student['gender'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="text-gray-700 fw-semibold fs-8">
                                    <?= $student['enrol_term'] ? 'Term ' . esc($student['enrol_term']) : '—' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-gray-700 fw-semibold fs-8">
                                    <?= !empty($student['admitted_at']) ? date('d M Y', strtotime($student['admitted_at'])) : '—' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-light-<?= $enrolColor ?> fs-9">
                                    <?= esc($student['enrol_status'] ?? '—') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-light-<?= $studColor ?> fs-9">
                                    <?= esc($student['class_stud_status']) ?>
                                </span>
                            </td>
                            <?php if (!empty($canManageStudents)): ?>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-icon btn-sm btn-light-danger btn-remove-student"
                                        data-class-stud-id="<?= $student['class_stud_id'] ?>"
                                        data-name="<?= esc(trim($student['fname'] . ' ' . $student['lname'])) ?>"
                                        title="Remove from classroom">
                                    <i class="ki-duotone ki-trash fs-5">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                </button>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Enrolled Students card-->

    </div>
    <!--end::Right content-->

</div>
</div>
</div>

<!--begin::Subject Assignment Modal-->
<div class="modal fade" id="modal_assign_subject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header py-4 px-6">
                <h5 class="modal-title fw-bold fs-5" id="modal_assign_sub_title">Assign Teacher</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body py-6 px-6">
                <input type="hidden" id="assign_sub_sch_sub_id" value="" />
                <input type="hidden" id="assign_sub_class_id" value="" />
                <div class="mb-3">
                    <label class="form-label fw-semibold fs-7 required">Select Teacher</label>
                    <select class="form-select form-select-sm" id="assign_sub_user_select">
                        <option value="">Search and select...</option>
                    </select>
                    <div class="text-muted fs-8 mt-2">Only teachers admitted to this school are listed.</div>
                </div>
            </div>
            <div class="modal-footer py-4 px-6">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" id="btn_save_sub_assignment">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Save Assignment
                    </span>
                    <span class="indicator-progress">
                        Saving... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Subject Assignment Modal-->

<!--begin::Assignment Modal-->
<div class="modal fade" id="modal_assign_staff" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header py-4 px-6">
                <h5 class="modal-title fw-bold fs-5" id="modal_assign_title">Assign Role</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body py-6 px-6">
                <input type="hidden" id="assign_role" value="" />
                <input type="hidden" id="assign_class_id" value="" />
                <div class="mb-3">
                    <label class="form-label fw-semibold fs-7 required" id="assign_label">Select Person</label>
                    <select class="form-select form-select-sm" id="assign_user_select">
                        <option value="">Search and select...</option>
                    </select>
                    <div class="text-muted fs-8 mt-2" id="assign_hint"></div>
                </div>
            </div>
            <div class="modal-footer py-4 px-6">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_assignment">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Save Assignment
                    </span>
                    <span class="indicator-progress">
                        Saving... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Assignment Modal-->

<!--begin::Add Classroom Subject Modal-->
<div class="modal fade" id="modal_add_classroom_subject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-book fs-2 text-success me-1"><span class="path1"></span><span class="path2"></span></i>
                    <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Add Classroom Subjects</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center py-6" id="cls_sub_modal_loader">
                    <span class="spinner-border spinner-border-sm me-2 text-primary"></span>
                    <span class="text-muted fs-7">Loading subjects...</span>
                </div>
                <div id="cls_sub_modal_content" style="display:none;">

                    <!--begin::Core-->
                    <div id="cls_core_section" style="display:none;" class="mb-5">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-light-success fs-8 px-3 py-2">Core Subjects</span>
                                <span class="text-muted fs-8">Minimum 4 required</span>
                            </div>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="form-check-input mt-0" id="cls_core_select_all">
                                <span class="fw-semibold fs-8 text-gray-700">Select All</span>
                            </label>
                        </div>
                        <div id="cls_core_list" class="row g-2"></div>
                    </div>
                    <!--end::Core-->

                    <!--begin::Optional-->
                    <div id="cls_optional_section" style="display:none;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-light-warning fs-8 px-3 py-2">Optional Subjects</span>
                                <span class="text-muted fs-8">Select subjects to add per group</span>
                            </div>
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="form-check-input mt-0" id="cls_optional_select_all">
                                <span class="fw-semibold fs-8 text-gray-700">Select All</span>
                            </label>
                        </div>
                        <div id="cls_optional_list"></div>
                    </div>
                    <!--end::Optional-->

                    <div id="cls_sub_empty" style="display:none;" class="text-center py-4 text-muted fs-8">
                        All stream subjects have already been added to this classroom.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <span class="text-muted fs-8 me-auto" id="cls_sub_selected_count"></span>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" id="btn_save_cls_subjects">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Add Subjects
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Classroom Subject Modal-->

<!--begin::Admit Students Modal-->
<div class="modal fade" id="modal_admit_students" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-people fs-2 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Admit Students to Class</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center py-6" id="admit_modal_loader">
                    <span class="spinner-border spinner-border-sm me-2 text-primary"></span>
                    <span class="text-muted fs-7">Loading eligible students...</span>
                </div>
                <div id="admit_modal_content" style="display:none;">
                    <div class="mb-3 pb-3 border-bottom border-gray-200 d-flex align-items-center justify-content-between">
                        <label class="d-flex align-items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="form-check-input mt-0" id="admit_select_all">
                            <span class="fw-bold fs-7 text-gray-700">Select All</span>
                        </label>
                        <span class="text-muted fs-8" id="admit_selected_count"></span>
                    </div>
                    <div id="admit_students_list" class="row g-3" style="max-height:420px;overflow-y:auto;"></div>
                </div>
                <div id="admit_modal_empty" style="display:none;" class="text-center py-6">
                    <i class="ki-duotone ki-people fs-3tx text-gray-200 mb-3">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    <div class="text-muted fs-7">All enrolled students have already been admitted to this classroom.</div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_admit_students">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Admit Students
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Admit Students Modal-->

<script>
"use strict";

<?php if ($canDelete): ?>
document.getElementById('btn_delete_classroom')?.addEventListener('click', function() {
    const id   = this.dataset.id;
    const name = this.dataset.name;

    Swal.fire({
        title: 'Delete Classroom?',
        html:
            '<p class="text-gray-700 fs-6 mb-3">Delete <strong>' + name + '</strong>?</p>' +
            '<p class="text-danger fw-semibold fs-8 mb-0">This action cannot be undone.</p>',
        icon:              'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-light' },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url:  '<?= base_url('classroom/delete') ?>/' + id,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({ title: 'Deleted!', text: response.message, icon: 'success',
                        timer: 1500, showConfirmButton: false })
                        .then(() => window.location.href = '<?= base_url('classroom') ?>');
                } else {
                    Swal.fire({ title: 'Failed', text: response.message, icon: 'error',
                        buttonsStyling: false, confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' } });
                }
            }
        });
    });
});
<?php endif; ?>

// ── Staff / Captain assignment ──────────────────────────────────────────────

const staffUsers       = <?= json_encode(array_values($staffUsers)) ?>;
const classroomStudents = <?= json_encode(array_values(array_map(fn($s) => [
    'user_id'  => $s['user_id'],
    'fname'    => $s['fname'],
    'lname'    => $s['lname'],
], $classroomStudents))) ?>;

let assignSelect2 = null;

function populateAssignSelect(type) {
    const users = type === 'staff' ? staffUsers : classroomStudents;
    const $sel  = $('#assign_user_select');
    $sel.empty().append('<option value="">Search and select...</option>');
    users.forEach(function(u) {
        const label = u.fname + ' ' + u.lname + (u.role_name ? ' — ' + u.role_name : '');
        $sel.append(new Option(label, u.user_id, false, false));
    });

    if (assignSelect2) {
        assignSelect2.destroy();
    }
    assignSelect2 = $sel.select2({
        placeholder:     'Search and select...',
        width:           '100%',
        dropdownParent:  $('#modal_assign_staff'),
    }).data('select2');
}

document.querySelectorAll('.btn-assign-staff').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const role    = this.dataset.role;
        const type    = this.dataset.type;
        const classId = this.dataset.classId;

        document.getElementById('assign_role').value     = role;
        document.getElementById('assign_class_id').value = classId;
        document.getElementById('modal_assign_title').textContent = 'Assign ' + role;
        document.getElementById('assign_label').textContent = type === 'staff' ? 'Select Teacher' : 'Select Student';
        document.getElementById('assign_hint').textContent  = type === 'staff'
            ? 'Showing staff with active admission to this school.'
            : 'Showing students admitted to this classroom.';

        populateAssignSelect(type);
        $('#modal_assign_staff').modal('show');
    });
});

document.getElementById('btn_save_assignment').addEventListener('click', function() {
    const btn     = this;
    const role    = document.getElementById('assign_role').value;
    const classId = document.getElementById('assign_class_id').value;
    const userId  = document.getElementById('assign_user_select').value;

    if (!userId) {
        Swal.fire({ title: 'Required', text: 'Please select a person.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK',
            customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url:  '<?= base_url('classroom/staff/assign') ?>/' + classId,
        type: 'POST',
        data: {
            user_id_fk: userId,
            cs_role:    role,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
        },
        success: function(response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            $('#modal_assign_staff').modal('hide');
            if (response.success) {
                Swal.fire({ title: 'Assigned!', text: response.message, icon: 'success',
                    timer: 1500, showConfirmButton: false })
                    .then(() => window.location.reload());
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

document.querySelectorAll('.btn-deactivate-staff').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const csId = this.dataset.csId;
        const name = this.dataset.name;
        const role = this.dataset.role;

        Swal.fire({
            title: 'Deactivate ' + role + '?',
            html:  '<p class="text-gray-700 fs-6 mb-0">Remove <strong>' + name + '</strong> from this role?</p>',
            icon:              'warning',
            showCancelButton:  true,
            buttonsStyling:    false,
            confirmButtonText: 'Yes, Deactivate',
            cancelButtonText:  'Cancel',
            customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-light' },
            reverseButtons: true,
        }).then(function(result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url:  '<?= base_url('classroom/staff/status') ?>/' + csId,
                type: 'POST',
                data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ title: 'Done!', text: response.message, icon: 'success',
                            timer: 1500, showConfirmButton: false })
                            .then(() => window.location.reload());
                    } else {
                        Swal.fire({ title: 'Failed', text: response.message, icon: 'error',
                            buttonsStyling: false, confirmButtonText: 'Close',
                            customClass: { confirmButton: 'btn btn-danger' } });
                    }
                }
            });
        });
    });
});

// ── Classroom subject teacher assignment ─────────────────────────────────────

let assignSubSelect2 = null;

$(document).on('click', '.btn-assign-cls-subject', function() {
    const classSubId  = this.dataset.classSubId;
    const subjectName = this.dataset.subjectName;

    document.getElementById('assign_sub_sch_sub_id').value = classSubId;
    document.getElementById('assign_sub_class_id').value   = this.dataset.classId;
    document.getElementById('modal_assign_sub_title').textContent = 'Assign Teacher — ' + subjectName;

    const $sel = $('#assign_sub_user_select');
    $sel.empty().append('<option value="">Search and select...</option>');
    staffUsers.forEach(function(u) {
        const label = u.fname + ' ' + u.lname + (u.role_name ? ' — ' + u.role_name : '');
        $sel.append(new Option(label, u.user_id, false, false));
    });

    if (assignSubSelect2) assignSubSelect2.destroy();
    assignSubSelect2 = $sel.select2({
        placeholder: 'Search and select...', width: '100%',
        dropdownParent: $('#modal_assign_subject'),
    }).data('select2');

    $('#modal_assign_subject').modal('show');
});

document.getElementById('btn_save_sub_assignment').addEventListener('click', function() {
    const btn        = this;
    const classSubId = document.getElementById('assign_sub_sch_sub_id').value;
    const userId     = document.getElementById('assign_sub_user_select').value;

    if (!userId) {
        Swal.fire({ title: 'Required', text: 'Please select a teacher.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url:  '<?= base_url('classroom/subjects/assign-teacher') ?>',
        type: 'POST',
        data: { class_sub_id: classSubId, user_id: userId, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
        success: function(response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            $('#modal_assign_subject').modal('hide');
            if (response.success) {
                Swal.fire({ title: 'Assigned!', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire({ title: 'Failed', text: response.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
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

// ── Enrolled Students DataTable ──────────────────────────────────────
if (document.getElementById('classroom_students_table')) {
    $('#classroom_students_table').DataTable({
        pageLength:  10,
        lengthMenu:  [[10, 25, 50, -1], [10, 25, 50, 'All']],
        order:       [[0, 'asc']],
        columnDefs:  [{ orderable: false, targets: [2, 3, 4] }],
        language: {
            search:         '',
            searchPlaceholder: 'Search students...',
            lengthMenu:     'Show _MENU_',
            info:           '_START_–_END_ of _TOTAL_ students',
            infoEmpty:      '0 students',
            zeroRecords:    'No matching students found',
        },
        dom: '<"d-flex align-items-center justify-content-between mb-4"<"d-flex align-items-center gap-3"lf>>rtip',
    });
}

// ── Admit Students modal ─────────────────────────────────────────────
const CLASS_ID = <?= (int) $classroom['class_id'] ?>;

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Add Classroom Subject modal ──────────────────────────────────────────────
document.getElementById('modal_add_classroom_subject').addEventListener('show.bs.modal', function () {
    const loader  = document.getElementById('cls_sub_modal_loader');
    const content = document.getElementById('cls_sub_modal_content');
    const counter = document.getElementById('cls_sub_selected_count');
    loader.style.display = ''; content.style.display = 'none'; counter.textContent = '';

    $.get('<?= base_url('classroom/subjects/available') ?>/' + CLASS_ID, function (res) {
        loader.style.display = 'none';
        content.style.display = '';

        const hasCore     = res.core     && res.core.length     > 0;
        const hasOptional = res.optional && res.optional.length > 0;

        if (!hasCore && !hasOptional) {
            document.getElementById('cls_sub_empty').style.display = '';
            document.getElementById('cls_core_section').style.display    = 'none';
            document.getElementById('cls_optional_section').style.display = 'none';
            return;
        }

        document.getElementById('cls_sub_empty').style.display = 'none';

        // Core
        if (hasCore) {
            const list = document.getElementById('cls_core_list');
            list.innerHTML = '';
            res.core.forEach(function (s) {
                list.innerHTML += `
                    <div class="col-md-6">
                        <label class="d-flex align-items-center gap-3 p-3 rounded-2 border border-dashed border-gray-300 cursor-pointer cls-sub-card">
                            <input type="checkbox" class="form-check-input mt-0 cls-core-cb" value="${s.sch_sub_id}">
                            <span class="fw-semibold fs-7 text-gray-800">${escHtml(s.subject_name)}</span>
                        </label>
                    </div>`;
            });
            document.getElementById('cls_core_section').style.display = '';
        } else {
            document.getElementById('cls_core_section').style.display = 'none';
        }

        // Optional
        if (hasOptional) {
            const optList = document.getElementById('cls_optional_list');
            optList.innerHTML = '';
            const groups = {};
            res.optional.forEach(s => { if (!groups[s.option_num]) groups[s.option_num] = []; groups[s.option_num].push(s); });
            let idx = 1;
            Object.keys(groups).forEach(function (gnum) {
                let html = `<div class="mb-4"><div class="text-muted fs-8 fw-semibold mb-2">Group ${idx++}</div><div class="row g-2">`;
                groups[gnum].forEach(s => {
                    html += `<div class="col-md-6">
                        <label class="d-flex align-items-center gap-3 p-3 rounded-2 border border-dashed border-gray-300 cursor-pointer cls-sub-card">
                            <input type="checkbox" class="form-check-input mt-0 cls-opt-cb" value="${s.sch_sub_id}">
                            <span class="fw-semibold fs-7 text-gray-800">${escHtml(s.subject_name)}</span>
                        </label></div>`;
                });
                html += '</div></div>';
                optList.innerHTML += html;
            });
            document.getElementById('cls_optional_section').style.display = '';
        } else {
            document.getElementById('cls_optional_section').style.display = 'none';
        }

        updateClsSubCounter();
    }, 'json');
});

// Select All — core
document.getElementById('cls_core_select_all').addEventListener('change', function () {
    document.querySelectorAll('.cls-core-cb').forEach(cb => cb.checked = this.checked);
    updateClsSubCounter();
});

// Select All — optional
document.getElementById('cls_optional_select_all').addEventListener('change', function () {
    document.querySelectorAll('.cls-opt-cb').forEach(cb => cb.checked = this.checked);
    updateClsSubCounter();
});

// Live counter + select-all sync
document.getElementById('modal_add_classroom_subject').addEventListener('change', function (e) {
    if (e.target.classList.contains('cls-core-cb')) {
        const all = document.querySelectorAll('.cls-core-cb');
        const n   = document.querySelectorAll('.cls-core-cb:checked').length;
        const sa  = document.getElementById('cls_core_select_all');
        sa.checked       = n === all.length && all.length > 0;
        sa.indeterminate = n > 0 && n < all.length;
    }
    if (e.target.classList.contains('cls-opt-cb')) {
        const all = document.querySelectorAll('.cls-opt-cb');
        const n   = document.querySelectorAll('.cls-opt-cb:checked').length;
        const sa  = document.getElementById('cls_optional_select_all');
        sa.checked       = n === all.length && all.length > 0;
        sa.indeterminate = n > 0 && n < all.length;
    }
    updateClsSubCounter();
});

function updateClsSubCounter() {
    const n = document.querySelectorAll('.cls-core-cb:checked, .cls-opt-cb:checked').length;
    document.getElementById('cls_sub_selected_count').textContent = n > 0 ? n + ' selected' : '';
}

document.getElementById('btn_save_cls_subjects').addEventListener('click', function () {
    const btn     = this;
    const checked = [...document.querySelectorAll('.cls-core-cb:checked, .cls-opt-cb:checked')].map(cb => cb.value);

    if (checked.length === 0) {
        Swal.fire({ title: 'Nothing selected', text: 'Please select at least one subject.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;

    const formData = new FormData();
    checked.forEach(id => formData.append('sch_sub_ids[]', id));

    $.ajax({
        url: '<?= base_url('classroom/subjects/add') ?>/' + CLASS_ID,
        type: 'POST', data: formData, processData: false, contentType: false,
        success: function (res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_add_classroom_subject')).hide();
                Swal.fire({ title: 'Subjects Added!', text: res.message, icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function () {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
});

document.getElementById('modal_admit_students').addEventListener('show.bs.modal', function () {
    const loader   = document.getElementById('admit_modal_loader');
    const content  = document.getElementById('admit_modal_content');
    const empty    = document.getElementById('admit_modal_empty');
    const list     = document.getElementById('admit_students_list');
    const selAll   = document.getElementById('admit_select_all');
    const counter  = document.getElementById('admit_selected_count');

    loader.style.display  = '';
    content.style.display = 'none';
    empty.style.display   = 'none';
    list.innerHTML        = '';
    selAll.checked = selAll.indeterminate = false;
    counter.textContent = '';

    $.get('<?= base_url('classroom/students/eligible') ?>/' + CLASS_ID, function (res) {
        loader.style.display = 'none';

        if (!res.success || !res.students || res.students.length === 0) {
            empty.style.display = '';
            return;
        }

        res.students.forEach(function (s) {
            const fullName = [s.fname, s.oname, s.lname].filter(Boolean).join(' ');
            const initials = ((s.fname || 'U')[0] + (s.lname || '')[0]).toUpperCase();
            const photo    = s.profile_photo
                ? `<img src="<?= base_url('uploads/profilePhoto/') ?>${s.profile_photo}" class="rounded-circle" style="width:38px;height:38px;object-fit:cover;" />`
                : `<div class="symbol-label bg-light-info" style="width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;"><span class="text-info fw-bold fs-8">${escHtml(initials)}</span></div>`;

            list.innerHTML += `
                <div class="col-md-6">
                    <label class="d-flex align-items-center gap-3 p-3 rounded-2 border border-dashed border-gray-300 cursor-pointer admit-card h-100">
                        <input type="checkbox" class="form-check-input mt-0 flex-shrink-0 admit-cb" value="${s.user_id}">
                        <div class="symbol symbol-38px flex-shrink-0">${photo}</div>
                        <div class="min-w-0">
                            <div class="fw-bold text-gray-800 fs-7 text-truncate">${escHtml(fullName)}</div>
                            <div class="text-muted fs-8">${escHtml(s.gender || '')}${s.enrol_term ? ' · Term ' + s.enrol_term : ''}</div>
                        </div>
                    </label>
                </div>`;
        });

        content.style.display = '';
        updateAdmitCounter();
    }, 'json');
});

function updateAdmitCounter() {
    const all      = document.querySelectorAll('.admit-cb');
    const checked  = document.querySelectorAll('.admit-cb:checked');
    const selAll   = document.getElementById('admit_select_all');
    const counter  = document.getElementById('admit_selected_count');
    const n = checked.length;
    selAll.checked       = n === all.length && all.length > 0;
    selAll.indeterminate = n > 0 && n < all.length;
    counter.textContent  = n > 0 ? n + ' selected' : '';
}

document.getElementById('admit_select_all').addEventListener('change', function () {
    document.querySelectorAll('.admit-cb').forEach(cb => cb.checked = this.checked);
    updateAdmitCounter();
});

document.getElementById('admit_students_list').addEventListener('change', function (e) {
    if (e.target.classList.contains('admit-cb')) updateAdmitCounter();
});

document.getElementById('btn_admit_students').addEventListener('click', function () {
    const btn      = this;
    const userIds = [...document.querySelectorAll('.admit-cb:checked')].map(cb => cb.value);

    if (userIds.length === 0) {
        Swal.fire({ title: 'Nothing selected', text: 'Please select at least one student.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    const formData = new FormData();
    userIds.forEach(id => formData.append('user_ids[]', id));

    $.ajax({
        url:         '<?= base_url('classroom/students/admit') ?>/' + CLASS_ID,
        type:        'POST',
        data:        formData,
        processData: false,
        contentType: false,
        success: function (res) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;

            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_admit_students')).hide();
                Swal.fire({
                    title: 'Students Admitted!',
                    text:  res.message,
                    icon:  'success',
                    timer: 1800,
                    showConfirmButton: false,
                }).then(() => window.location.reload());
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function () {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
});

// ── Remove student from classroom ────────────────────────────────────────────
document.addEventListener('click', function (e) {
    var btn = e.target.closest('.btn-remove-student');
    if (!btn) return;

    var classStudId = btn.dataset.classStudId;
    var name        = btn.dataset.name;

    Swal.fire({
        title: 'Remove Student?',
        html:  '<strong>' + name + '</strong> will be removed from this classroom.',
        icon:  'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, remove',
        cancelButtonText:  'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(function (result) {
        if (!result.isConfirmed) return;

        btn.disabled = true;

        $.post('<?= base_url('classroom/students/remove') ?>/' + classStudId, function (res) {
            if (res.success) {
                var row = document.getElementById('stud_row_' + classStudId);
                if (row) row.remove();

                // Update badge count
                var remaining = document.querySelectorAll('#classroom_students_table tbody tr').length;
                var badge = document.getElementById('student_count_badge');
                if (badge) badge.textContent = remaining + ' student' + (remaining !== 1 ? 's' : '');

                // Show empty state if no students left
                if (remaining === 0) {
                    document.getElementById('students_table_wrapper').classList.add('d-none');
                    var es = document.getElementById('students_empty_state');
                    if (es) es.style.display = '';
                }

                Swal.fire({ title: 'Removed', text: res.message, icon: 'success',
                    timer: 1500, showConfirmButton: false });
            } else {
                btn.disabled = false;
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close',
                    customClass: { confirmButton: 'btn btn-danger' } });
            }
        }, 'json').fail(function () {
            btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'Close',
                customClass: { confirmButton: 'btn btn-danger' } });
        });
    });
});
</script>
