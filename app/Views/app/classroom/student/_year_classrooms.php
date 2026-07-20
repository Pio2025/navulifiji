<?php
$subjectColors   = ['primary','success','info','warning','danger','dark'];
$userId          = $sessionUserId ?? 0;
$ci              = 0;
$schCatData      = session('sch_cat_data') ?? [];
$termLabel       = $schCatData['label'] ?? 'Term';
$catTerms        = !empty($schCatData['terms']) ? $schCatData['terms'] : [1 => [], 2 => [], 3 => []];
$parentChildId   = $parentChildId ?? 0;           // non-zero when a parent is viewing their child's classroom
$allowPost       = isset($allowPost) ? (bool)$allowPost : true; // false for parent views
$attendanceSuffix = $parentChildId > 0 ? '&student_id=' . $parentChildId : '';
?>

<?php if (empty($classrooms)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-16 text-muted">
        <i class="ki-duotone ki-element-7 fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-6 fw-semibold text-gray-600 mb-2">No Classrooms Found</div>
        <div class="fs-8">No classrooms found for this year.</div>
    </div>
</div>

<?php else: ?>
<?php foreach ($classrooms as $cls):
    $statusColor = match($cls['class_stud_status']) {
        'Active'    => 'success',
        'Completed' => 'info',
        'Inactive'  => 'danger',
        default     => 'secondary',
    };
    $ctInitials = !empty($cls['class_teacher'])
        ? strtoupper(substr(explode(' ', $cls['class_teacher'])[0], 0, 1) . substr(explode(' ', $cls['class_teacher'])[1] ?? '', 0, 1))
        : '?';
    $ccInitials = !empty($cls['class_captain'])
        ? strtoupper(substr(explode(' ', $cls['class_captain'])[0], 0, 1) . substr(explode(' ', $cls['class_captain'])[1] ?? '', 0, 1))
        : '?';
    $isActive = ($cls['class_status'] === 'Active');
    $tableId  = 'students_table_' . $cls['class_id'];
    $tabClass = 'tab_class_'    . $cls['class_id'];
    $tabSubj  = 'tab_subj_'     . $cls['class_id'];
    $tabAtt   = 'tab_att_'      . $cls['class_id'];
    $tabExam  = 'tab_exam_'     . $cls['class_id'];
    $tabDisc  = 'tab_discuss_'  . $cls['class_id'];
    $ci++;
?>

<!--begin::Classroom card-->
<div class="card border-0 shadow-sm mb-8">

    <!--begin::Classroom info header-->
    <div class="card-body p-0">
        <div class="row g-0">

            <!--begin::School info-->
            <div class="col-md-4 p-6 d-flex align-items-center gap-4" style="border-right:1px solid #f1f1f4;">
                <div class="symbol symbol-70px flex-shrink-0">
                    <?php if (!empty($cls['sch_logo'])): ?>
                    <img src="<?= base_url('uploads/school/logo/' . $cls['sch_logo']) ?>"
                         class="rounded-2" style="width:70px;height:70px;object-fit:contain;" alt="">
                    <?php else: ?>
                    <div class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-bank fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <div class="fw-bold text-gray-900 fs-5 lh-sm mb-1"><?= esc($cls['sch_name']) ?></div>
                    <?php if (!empty($cls['sch_address'])): ?>
                    <div class="text-muted fs-8 d-flex align-items-center gap-1 mb-1">
                        <i class="ki-duotone ki-geolocation fs-7"><span class="path1"></span><span class="path2"></span></i>
                        <?= esc($cls['sch_address']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($cls['sch_phone'])): ?>
                    <div class="text-muted fs-8 d-flex align-items-center gap-1">
                        <i class="ki-duotone ki-phone fs-7"><span class="path1"></span><span class="path2"></span></i>
                        <?= esc($cls['sch_phone']) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <!--end::School info-->

            <!--begin::Classroom info-->
            <div class="col-md-4 p-6 d-flex flex-column justify-content-center" style="border-right:1px solid #f1f1f4;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge badge-light-<?= $statusColor ?> fs-8"><?= esc($cls['class_stud_status']) ?></span>
                    <span class="badge badge-light-primary fs-8"><?= esc($cls['class_year']) ?></span>
                </div>
                <div class="fw-bold text-gray-900 fs-3 mb-1"><?= esc($cls['class_name']) ?></div>
                <div class="text-muted fs-7 mb-1">
                    <i class="ki-duotone ki-abstract-26 fs-7 me-1"><span class="path1"></span><span class="path2"></span></i>
                    <?= esc($cls['stream_name']) ?> &bull; <?= esc($cls['level_name']) ?>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <i class="ki-duotone ki-people fs-5 text-info">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    <span class="fw-bold text-gray-800 fs-7"><?= (int) $cls['student_count'] ?></span>
                    <span class="text-muted fs-8">students enrolled</span>
                </div>
            </div>
            <!--end::Classroom info-->

            <!--begin::Roles-->
            <div class="col-md-4 p-6 d-flex flex-column justify-content-center gap-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px flex-shrink-0">
                        <?php if (!empty($cls['class_teacher_photo'])): ?>
                        <img src="<?= base_url('uploads/profilePhoto/' . $cls['class_teacher_photo']) ?>"
                             class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" alt="">
                        <?php elseif (!empty($cls['class_teacher'])): ?>
                        <div class="symbol-label bg-light-primary fw-bold text-primary fs-7"><?= $ctInitials ?></div>
                        <?php else: ?>
                        <div class="symbol-label bg-light-secondary">
                            <i class="ki-duotone ki-user fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-muted fs-9 lh-1 mb-1">Class Teacher</div>
                        <div class="fw-bold text-gray-800 fs-7">
                            <?= !empty($cls['class_teacher']) ? esc($cls['class_teacher']) : '<span class="text-muted fst-italic fw-normal fs-8">Not assigned</span>' ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px flex-shrink-0">
                        <?php if (!empty($cls['class_captain_photo'])): ?>
                        <img src="<?= base_url('uploads/profilePhoto/' . $cls['class_captain_photo']) ?>"
                             class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" alt="">
                        <?php elseif (!empty($cls['class_captain'])): ?>
                        <div class="symbol-label bg-light-warning fw-bold text-warning fs-7"><?= $ccInitials ?></div>
                        <?php else: ?>
                        <div class="symbol-label bg-light-secondary">
                            <i class="ki-duotone ki-user fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-muted fs-9 lh-1 mb-1">Class Captain</div>
                        <div class="fw-bold text-gray-800 fs-7">
                            <?= !empty($cls['class_captain']) ? esc($cls['class_captain']) : '<span class="text-muted fst-italic fw-normal fs-8">Not assigned</span>' ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Roles-->

        </div>
    </div>
    <!--end::Classroom info header-->

    <?php if (!$isActive): ?>
    <div class="d-flex align-items-center gap-2 px-6 py-3 border-top" style="background:#fff8e1;border-color:#ffe082!important;">
        <i class="ki-duotone ki-lock-2 fs-4 text-warning"><span class="path1"></span><span class="path2"></span></i>
        <span class="fs-7 fw-semibold text-warning">Read-only — this classroom is <strong><?= esc($cls['class_status']) ?></strong>. No new content can be added or modified.</span>
    </div>
    <?php endif; ?>

    <div style="border-top:1px solid #f1f1f4;"></div>

    <!--begin::Tab nav-->
    <div class="card-body px-6 py-0">
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-semibold">
            <li class="nav-item">
                <a class="nav-link active text-gray-700 py-4" data-bs-toggle="tab" href="#<?= $tabClass ?>">
                    <i class="ki-duotone ki-people fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>Class
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-gray-700 py-4" data-bs-toggle="tab" href="#<?= $tabSubj ?>">
                    <i class="ki-duotone ki-book-open fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>Subject
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-gray-700 py-4" data-bs-toggle="tab" href="#<?= $tabAtt ?>">
                    <i class="ki-duotone ki-calendar-tick fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                    </i>Attendance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-gray-700 py-4" data-bs-toggle="tab" href="#<?= $tabExam ?>">
                    <i class="ki-duotone ki-document fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>Exam
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-gray-700 py-4" data-bs-toggle="tab" href="#<?= $tabDisc ?>">
                    <i class="ki-duotone ki-message-text-2 fs-4 me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>Discussion
                </a>
            </li>
        </ul>
    </div>
    <!--end::Tab nav-->

    <!--begin::Tab content-->
    <div class="tab-content">

        <!--begin::Tab: Class (Classmates)-->
        <div class="tab-pane fade show active" id="<?= $tabClass ?>">
            <div style="border-top:1px solid #f1f1f4;"></div>
            <div class="card-body px-6 pt-6 pb-6">
                <div class="d-flex align-items-center gap-2 mb-5">
                    <i class="ki-duotone ki-people fs-3 text-info">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    <h5 class="fw-bold text-gray-800 mb-0">Classmates</h5>
                    <span class="badge badge-light-info fs-9 ms-1"><?= count($cls['students']) ?></span>
                </div>
                <?php if (empty($cls['students'])): ?>
                <div class="text-center py-8 text-muted fs-8">No students enrolled in this classroom yet.</div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3 need-datatable" id="<?= $tableId ?>">
                        <thead>
                            <tr class="fw-bold text-muted fs-8">
                                <th class="ps-0 w-40px">#</th>
                                <th>Student</th>
                                <th>Gender</th>
                                <th>Username</th>
                                <th class="text-end pe-0">Chat</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cls['students'] as $si => $stu):
                            $stuInitials    = strtoupper(substr($stu['fname'], 0, 1) . substr($stu['lname'], 0, 1));
                            $stuGenderColor = $stu['gender'] === 'Female' ? 'danger' : 'primary';
                            $stuChatOnclick = 'stuMyOpenChat(' . $stu['user_id'] . ',' . json_encode($stu['fname']) . ',' . json_encode($stu['lname']) . ',' . json_encode($stu['profile_photo'] ?? '') . ')';
                        ?>
                        <tr>
                            <td class="ps-0 text-muted fs-8"><?= $si + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3 <?= $isActive ? 'cursor-pointer user-link' : '' ?>"
                                     <?= $isActive ? 'onclick="' . $stuChatOnclick . '"' : '' ?>>
                                    <div class="symbol symbol-35px flex-shrink-0">
                                        <?php if (!empty($stu['profile_photo'])): ?>
                                        <img src="<?= base_url('uploads/profilePhoto/' . $stu['profile_photo']) ?>"
                                             class="rounded-circle" style="width:35px;height:35px;object-fit:cover;" alt="">
                                        <?php else: ?>
                                        <div class="symbol-label bg-light-<?= $stuGenderColor ?> fw-bold text-<?= $stuGenderColor ?> fs-8"><?= $stuInitials ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <span class="fw-semibold user-link-name text-gray-800 fs-7 d-block"><?= esc($stu['fname'] . ' ' . $stu['lname']) ?></span>
                                        <?php if (!empty($stu['email'])): ?>
                                        <span class="text-muted fs-9"><?= esc($stu['email']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-light-<?= $stuGenderColor ?> fs-9"><?= esc($stu['gender'] ?: '—') ?></span>
                            </td>
                            <td class="text-muted fs-8"><?= esc($stu['username'] ?: '—') ?></td>
                            <td class="text-end pe-0">
                                <button type="button" class="btn btn-icon btn-sm <?= $isActive ? 'btn-light-primary' : 'btn-light text-muted' ?>"
                                        <?= $isActive ? 'onclick="' . $stuChatOnclick . '"' : 'disabled' ?>
                                        title="<?= $isActive ? 'Open chat' : 'Chat disabled — classroom inactive' ?>">
                                    <i class="ki-duotone ki-message-text-2 fs-5">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Tab: Class-->

        <!--begin::Tab: Subject-->
        <div class="tab-pane fade" id="<?= $tabSubj ?>">
            <div style="border-top:1px solid #f1f1f4;"></div>
            <div class="card-body px-6 pt-6 pb-6">
                <div class="d-flex align-items-center gap-2 mb-5">
                    <i class="ki-duotone ki-book-open fs-3 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <h5 class="fw-bold text-gray-800 mb-0">Subjects</h5>
                    <span class="badge badge-light-primary fs-9 ms-1"><?= count($cls['subjects']) ?></span>
                </div>
                <?php if (empty($cls['subjects'])): ?>
                <div class="text-center py-8 text-muted fs-8">No subjects assigned to this classroom yet.</div>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($cls['subjects'] as $si => $subj):
                        $color = $subjectColors[$si % count($subjectColors)];
                        $subjInitial = strtoupper(substr($subj['subject_name'], 0, 1));
                    ?>
                    <div class="col-6 col-sm-4 col-md-2">
                        <a href="<?= base_url('classroom/student/' . $subj['class_sub_id']) ?>" class="text-decoration-none d-block h-100">
                        <div class="card border border-gray-100 h-100 subject-card">
                            <div class="card-body p-4 text-center">
                                <div class="d-flex align-items-center justify-content-center bg-light-<?= $color ?> rounded-2 mx-auto mb-3" style="width:52px;height:52px;">
                                    <?php if (!empty($subj['sub_image'])): ?>
                                    <img src="<?= base_url('uploads/subjects/' . $subj['sub_image']) ?>"
                                         style="width:36px;height:36px;object-fit:contain;" alt="">
                                    <?php else: ?>
                                    <span class="fw-bold text-<?= $color ?> fs-3"><?= $subjInitial ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="fw-bold text-gray-800 fs-7 mb-2 lh-sm"><?= esc($subj['subject_name']) ?></div>
                                <div class="separator separator-dashed mb-2"></div>
                                <?php if (!empty($subj['teacher_name'])): ?>
                                <?php
                                    $tParts = explode(' ', $subj['teacher_name'], 2);
                                    $tFname = $tParts[0] ?? ''; $tLname = $tParts[1] ?? '';
                                    $tClick = !empty($subj['teacher_id'])
                                        ? 'stuMyOpenChat(' . $subj['teacher_id'] . ',' . json_encode($tFname) . ',' . json_encode($tLname) . ',' . json_encode($subj['teacher_photo'] ?? '') . ')'
                                        : '';
                                ?>
                                <div class="user-link d-flex align-items-center justify-content-center gap-2 mb-2 <?= $tClick ? 'cursor-pointer' : '' ?>"
                                     <?= $tClick ? 'onclick="' . $tClick . '"' : '' ?>>
                                    <div class="symbol symbol-20px flex-shrink-0">
                                        <?php if (!empty($subj['teacher_photo'])): ?>
                                        <img src="<?= base_url('uploads/profilePhoto/' . $subj['teacher_photo']) ?>"
                                             class="rounded-circle" style="width:20px;height:20px;object-fit:cover;" alt="">
                                        <?php else: ?>
                                        <div class="symbol-label bg-light-primary fw-bold text-primary" style="font-size:9px;"><?= strtoupper(substr($subj['teacher_name'], 0, 1)) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-muted fs-9 text-truncate user-link-name"><?= esc($subj['teacher_name']) ?></span>
                                </div>
                                <?php else: ?>
                                <span class="text-muted fs-9 fst-italic d-block mb-2">No teacher</span>
                                <?php endif; ?>
                                <div class="d-flex align-items-center justify-content-center gap-1 text-muted fs-9">
                                    <i class="ki-duotone ki-people fs-8">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                    <?= (int) $subj['student_count'] ?> student<?= (int) $subj['student_count'] !== 1 ? 's' : '' ?>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Tab: Subject-->

        <!--begin::Tab: Attendance-->
        <div class="tab-pane fade" id="<?= $tabAtt ?>">
            <div style="border-top:1px solid #f1f1f4;"></div>
            <div class="card-body px-6 py-8">
                <div class="d-flex align-items-center gap-2 mb-6">
                    <i class="ki-duotone ki-calendar-tick fs-3 text-success">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                    </i>
                    <h5 class="fw-bold text-gray-800 mb-0">Attendance</h5>
                </div>
                <div class="row g-4">
                    <?php foreach ($catTerms as $termNo => $termMeta): ?>
                    <div class="col-md-4">
                        <div class="card border border-dashed border-gray-300">
                            <div class="card-body p-5">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="d-flex align-items-center justify-content-center bg-light-success rounded-2 flex-shrink-0" style="width:44px;height:44px;">
                                        <i class="ki-duotone ki-calendar-tick fs-3 text-success">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                            <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                                        </i>
                                    </div>
                                    <div class="fw-bold text-gray-800 fs-6"><?= esc($termLabel) ?> <?= (int)$termNo ?></div>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <a href="<?= base_url('attendance/my/daily?stream_id=' . $cls['stream_id'] . '&term=' . (int)$termNo . $attendanceSuffix) ?>"
                                       class="btn btn-sm btn-light-success">
                                        <i class="ki-duotone ki-calendar-8 fs-5 me-1">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                        Student Daily Attendance
                                    </a>
                                    <a href="<?= base_url('attendance/my/subject?stream_id=' . $cls['stream_id'] . '&term=' . (int)$termNo . $attendanceSuffix) ?>"
                                       class="btn btn-sm btn-light-info">
                                        <i class="ki-duotone ki-book-open fs-5 me-1">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                        Student Subject Attendance
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!--end::Tab: Attendance-->

        <!--begin::Tab: Exam-->
        <div class="tab-pane fade" id="<?= $tabExam ?>">
            <div style="border-top:1px solid #f1f1f4;"></div>
            <div class="card-body px-6 py-8">
                <div class="d-flex align-items-center gap-2 mb-6">
                    <i class="ki-duotone ki-document fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    <h5 class="fw-bold text-gray-800 mb-0">Report Cards</h5>
                </div>
                <div class="row g-4">
                    <?php foreach ($catTerms as $termNo => $termMeta): ?>
                    <div class="col-md-4">
                        <a href="<?= base_url('classroom/report/' . $cls['class_id'] . '/student/' . $userId . '/term/' . (int)$termNo) ?>"
                           class="text-decoration-none">
                            <div class="card border border-dashed border-gray-300 hover-elevate-up">
                                <div class="card-body p-6 d-flex align-items-center gap-4">
                                    <div class="d-flex align-items-center justify-content-center bg-light-primary rounded-2 flex-shrink-0" style="width:52px;height:52px;">
                                        <i class="ki-duotone ki-document fs-2x text-primary">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-gray-800 fs-6"><?= esc($termLabel) ?> <?= (int)$termNo ?></div>
                                        <div class="text-muted fs-8">View report card</div>
                                    </div>
                                    <i class="ki-duotone ki-arrow-right fs-4 text-muted ms-auto">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!--end::Tab: Exam-->

        <!--begin::Tab: Discussion-->
        <div class="tab-pane fade" id="<?= $tabDisc ?>">
            <div style="border-top:1px solid #f1f1f4;"></div>
            <div class="card-body px-6 py-6">
                <?= view('app/classroom/teacher/_class_discussion', [
                    'discussions'     => $cls['discussions'] ?? [],
                    'sessionFname'    => $sessionFname    ?? '',
                    'sessionPhotoUrl' => $sessionPhotoUrl ?? null,
                    'sessionUserId'   => $sessionUserId   ?? 0,
                    'sdPostUrl'       => base_url('classroom/' . $cls['class_id'] . '/discussion/post'),
                    'canPost'         => $isActive && $allowPost,
                    'sdPfx'           => 'cd_c' . $cls['class_id'],
                    'sdShowShared'    => false,
                ]) ?>
            </div>
        </div>
        <!--end::Tab: Discussion-->

    </div>
    <!--end::Tab content-->

</div>
<!--end::Classroom card-->

<?php endforeach; ?>
<?php endif; ?>

<script>
(function () {
    // Init DataTables for classmate tables in this year's content
    document.querySelectorAll('table.need-datatable').forEach(function (t) {
        if (t.id && typeof $ !== 'undefined' && $.fn.DataTable && !$.fn.DataTable.isDataTable('#' + t.id)) {
            $('#' + t.id).DataTable({
                pageLength: 15,
                lengthMenu: [10, 15, 25, 50],
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: [0, 4] }],
                language: {
                    search: '',
                    searchPlaceholder: 'Search students...',
                    lengthMenu: 'Show _MENU_',
                    info: 'Showing _START_–_END_ of _TOTAL_ students',
                    paginate: { previous: '‹', next: '›' }
                },
                dom: '<"d-flex align-items-center justify-content-between mb-4"lf>rt<"d-flex align-items-center justify-content-between mt-4"ip>'
            });
        }
    });
})();
</script>

<style>
.subject-card { transition: box-shadow .15s, transform .15s; }
.subject-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); transform: translateY(-2px); }
.user-link { transition: background .15s; }
.user-link:hover { background: #f0f4ff; opacity: 1; }
.user-link:hover .user-link-name { color: var(--bs-primary) !important; }
</style>
