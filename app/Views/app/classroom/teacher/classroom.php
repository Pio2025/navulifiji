<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($subjectData['subject_name'] ?? 'Subject') ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($subjectData['subject_name'] ?? 'Subject') ?></li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/my') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php
    $cls        = $classroomCard;
    $ctInitials = !empty($cls['class_teacher'])
        ? strtoupper(implode('', array_map(fn($w) => $w[0], array_filter(explode(' ', $cls['class_teacher'])))))
        : '';
    $ccInitials = !empty($cls['class_captain'])
        ? strtoupper(implode('', array_map(fn($w) => $w[0], array_filter(explode(' ', $cls['class_captain'])))))
        : '';

    $navLinks = [
        ['label' => 'Dashboard',   'icon' => 'ki-element-11',    'slug' => 'dashboard'],
        ['label' => 'Lessons',     'icon' => 'ki-book-open',     'slug' => 'lessons'],
        ['label' => 'Assignments', 'icon' => 'ki-document',      'slug' => 'assignments'],
        ['label' => 'Feedback',    'icon' => 'ki-message-text-2','slug' => 'feedback'],
    ];
    ?>

    <div class="row g-6">

        <!--begin::Left panel-->
        <div class="col-md-3">

            <!--begin::Subject card-->
            <?php
            $clsStatus = $classroomCard['class_status'] ?? 'Active';
            $headerBg  = match($clsStatus) {
                'Active'    => 'bg-light-success',
                'Completed' => 'bg-light-info',
                default     => 'bg-light-secondary',
            };
            $iconBg    = match($clsStatus) {
                'Active'    => 'bg-success',
                'Completed' => 'bg-info',
                default     => 'bg-secondary',
            };
            $badgeCls  = match($clsStatus) {
                'Active'    => 'badge-light-success',
                'Completed' => 'badge-light-info',
                default     => 'badge-light-secondary',
            };
            ?>
            <div class="card border-0 shadow-sm mb-5" style="border-radius:.75rem;overflow:hidden;">
                <div class="p-5 <?= $headerBg ?>">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="symbol symbol-40px">
                            <?php if (!empty($subjectData['sub_image'])): ?>
                            <img src="<?= base_url('uploads/subjects/' . $subjectData['sub_image']) ?>"
                                 class="rounded" style="width:40px;height:40px;object-fit:cover;" />
                            <?php else: ?>
                            <div class="symbol-label <?= $iconBg ?>">
                                <i class="ki-duotone ki-book fs-3 text-white">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            <span class="badge <?= $badgeCls ?> fs-9"><?= esc($clsStatus) ?></span>
                            <span class="badge badge-light fs-9 text-muted"><?= esc($classroomCard['class_year'] ?? '') ?></span>
                        </div>
                    </div>
                    <div class="fw-bold text-gray-900 fs-6 lh-1"><?= esc($subjectData['subject_name'] ?? '—') ?></div>
                    <div class="text-muted fs-8 mt-1"><?= esc($subjectData['level_name'] ?? '—') ?></div>
                    <div class="text-muted fs-9"><?= esc($subjectData['dept_name'] ?? '') ?></div>
                    <div class="text-muted fs-9 mt-1"><?= esc($classroomCard['class_name'] ?? '') ?></div>
                </div>
                <div class="px-5 py-4">
                    <!--begin::Class Teacher-->
                    <?php $ctHref = !empty($cls['class_teacher_id']) ? base_url('user/detail/' . $cls['class_teacher_id']) : '#'; ?>
                    <a href="<?= $ctHref ?>" class="user-link d-flex align-items-center gap-3 mb-3 text-decoration-none rounded-2 p-1 mx-n1">
                        <div class="flex-shrink-0">
                            <?php if (!empty($cls['class_teacher_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $cls['class_teacher_photo']) ?>"
                                 class="rounded-circle" style="width:30px;height:30px;object-fit:cover;" />
                            <?php elseif ($cls['class_teacher']): ?>
                            <div class="symbol symbol-30px">
                                <div class="symbol-label bg-light-primary fs-9 fw-bold text-primary"><?= substr($ctInitials, 0, 2) ?></div>
                            </div>
                            <?php else: ?>
                            <div class="symbol symbol-30px">
                                <div class="symbol-label bg-light-secondary">
                                    <i class="ki-duotone ki-user fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0">
                            <div class="text-muted fs-9 lh-1 mb-1">Class Teacher</div>
                            <div class="fw-bold text-gray-800 fs-8 text-truncate user-link-name">
                                <?= $cls['class_teacher'] ? esc($cls['class_teacher']) : '<span class="text-muted fst-italic fw-normal">Not assigned</span>' ?>
                            </div>
                        </div>
                    </a>
                    <!--end::Class Teacher-->
                    <!--begin::Form Captain-->
                    <?php $ccHref = !empty($cls['class_captain_id']) ? base_url('user/detail/' . $cls['class_captain_id']) : '#'; ?>
                    <a href="<?= $ccHref ?>" class="user-link d-flex align-items-center gap-3 mb-3 text-decoration-none rounded-2 p-1 mx-n1">
                        <div class="flex-shrink-0">
                            <?php if (!empty($cls['class_captain_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $cls['class_captain_photo']) ?>"
                                 class="rounded-circle" style="width:30px;height:30px;object-fit:cover;" />
                            <?php elseif ($cls['class_captain']): ?>
                            <div class="symbol symbol-30px">
                                <div class="symbol-label bg-light-warning fs-9 fw-bold text-warning"><?= substr($ccInitials, 0, 2) ?></div>
                            </div>
                            <?php else: ?>
                            <div class="symbol symbol-30px">
                                <div class="symbol-label bg-light-secondary">
                                    <i class="ki-duotone ki-user fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0">
                            <div class="text-muted fs-9 lh-1 mb-1">Form Captain</div>
                            <div class="fw-bold text-gray-800 fs-8 text-truncate user-link-name">
                                <?= $cls['class_captain'] ? esc($cls['class_captain']) : '<span class="text-muted fst-italic fw-normal">Not assigned</span>' ?>
                            </div>
                        </div>
                    </a>
                    <!--end::Form Captain-->
                    <div class="d-flex align-items-center gap-2 pt-3" style="border-top:1px solid #f1f1f4;">
                        <i class="ki-duotone ki-people fs-5 text-info">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                        <span class="fw-bold text-gray-800 fs-7"><?= (int) $cls['student_count'] ?></span>
                        <span class="text-muted fs-8">student<?= (int) $cls['student_count'] !== 1 ? 's' : '' ?> taking this subject</span>
                    </div>
                </div>
            </div>
            <!--end::Classroom card-->

            <!--begin::Navigation card-->
            <div class="card border-0 shadow-sm">
                <div class="card-body py-4 px-0">
                    <ul class="nav nav-pills nav-pills-custom flex-column border-transparent fs-5 fw-bold">
                        <?php foreach ($navLinks as $nav):
                            $isActive = $section === $nav['slug'];
                        ?>
                        <li class="nav-item mt-2">
                            <a class="nav-link text-muted text-active-primary ms-0 py-0 me-10 ps-9 border-0 <?= $isActive ? 'active' : '' ?>"
                               href="<?= base_url('classroom/teacher/' . $schSubId . '/' . $nav['slug']) ?>">
                                <i class="ki-duotone <?= $nav['icon'] ?> fs-3 text-muted me-3">
                                    <span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span>
                                </i><?= $nav['label'] ?>
                                <span class="bullet-custom position-absolute start-0 top-0 w-3px h-100 bg-primary rounded-end"></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <!--end::Navigation card-->

            <!--begin::Students card-->
            <div class="card border-0 shadow-sm mt-5">
                <div class="card-header border-0 pt-4 pb-2 px-5 min-h-auto">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ki-duotone ki-people fs-4 text-info">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                        <span class="fw-bold text-gray-800 fs-7">Students</span>
                        <span class="badge badge-light-info fs-9 ms-1"><?= count($students ?? []) ?></span>
                    </div>
                </div>
                <div class="card-body px-5 pt-3 pb-4">
                <?php if (empty($students)): ?>
                    <div class="text-center py-4 text-muted fs-8">No students enrolled.</div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-1">
                    <?php foreach ($students as $s):
                        $initials = strtoupper(substr($s['fname'], 0, 1) . substr($s['lname'], 0, 1));
                    ?>
                    <a href="<?= base_url('user/detail/' . $s['user_id']) ?>"
                       class="user-link d-flex align-items-center gap-3 text-decoration-none rounded-2 px-2 py-2 mx-n2">
                        <div class="symbol symbol-32px flex-shrink-0">
                            <?php if (!empty($s['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $s['profile_photo']) ?>"
                                 class="rounded-circle" style="width:32px;height:32px;object-fit:cover;" alt="">
                            <?php else: ?>
                            <div class="symbol-label bg-light-primary fw-bold text-primary fs-9"><?= $initials ?></div>
                            <?php endif; ?>
                        </div>
                        <span class="user-link-name text-gray-700 fw-semibold fs-8 text-truncate"><?= esc($s['fname'] . ' ' . $s['lname']) ?></span>
                    </a>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
            </div>
            <!--end::Students card-->

        </div>
        <!--end::Left panel-->

        <!--begin::Right panel-->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <?php if (!$classroomIsActive): ?>
                <div class="d-flex align-items-center gap-2 px-6 py-3" style="background:#fff8e1;border-bottom:1px solid #ffe082;">
                    <i class="ki-duotone ki-lock-2 fs-4 text-warning"><span class="path1"></span><span class="path2"></span></i>
                    <span class="fs-7 fw-semibold text-warning">Read-only — this classroom is <strong><?= esc($classroomCard['class_status'] ?? '') ?></strong>. No new content can be added or modified.</span>
                </div>
                <?php endif; ?>
                <div class="card-body p-6">

                    <?php if ($section === 'dashboard'): ?>
                    <!--begin::Dashboard-->
                    <?php
                    $st      = $dashStats ?? [];
                    $avgSc   = $st['avg_score'] ?? null;
                    $avgCol  = $avgSc === null ? 'secondary' : ($avgSc >= 70 ? 'success' : ($avgSc >= 50 ? 'warning' : 'danger'));
                    $naCol   = ($st['need_attention'] ?? 0) > 0 ? 'warning' : 'success';
                    $aTypeCfg = [
                        'quiz'      => ['label'=>'Quiz',        'color'=>'success', 'icon'=>'ki-questionnaire-tablet'],
                        'drag_drop' => ['label'=>'Drag & Drop', 'color'=>'primary', 'icon'=>'ki-abstract-26'],
                        'labelling' => ['label'=>'Labelling',   'color'=>'info',    'icon'=>'ki-tag'],
                    ];
                    ?>

                    <!--begin::Stats row-->
                    <div class="row g-3 mb-6">
                        <div class="col-6 col-xl-4">
                            <div class="card border-0 bg-light-primary h-100">
                                <div class="card-body p-4 d-flex align-items-center gap-3">
                                    <div class="symbol symbol-45px flex-shrink-0">
                                        <div class="symbol-label bg-primary">
                                            <i class="ki-duotone ki-people fs-2 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-primary fs-2x lh-1"><?= $st['students'] ?? 0 ?></div>
                                        <div class="text-muted fs-8 mt-1">Students Enrolled</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-4">
                            <div class="card border-0 bg-light-success h-100">
                                <div class="card-body p-4 d-flex align-items-center gap-3">
                                    <div class="symbol symbol-45px flex-shrink-0">
                                        <div class="symbol-label bg-success">
                                            <i class="ki-duotone ki-book-open fs-2 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-success fs-2x lh-1"><?= $st['lessons'] ?? 0 ?></div>
                                        <div class="text-muted fs-8 mt-1">Published Lessons</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-4">
                            <div class="card border-0 bg-light-danger h-100">
                                <div class="card-body p-4 d-flex align-items-center gap-3">
                                    <div class="symbol symbol-45px flex-shrink-0">
                                        <div class="symbol-label bg-danger">
                                            <i class="ki-duotone ki-element-plus fs-2 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-danger fs-2x lh-1"><?= $st['assessments'] ?? 0 ?></div>
                                        <div class="text-muted fs-8 mt-1">Published Assessments</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-4">
                            <div class="card border-0 bg-light-info h-100">
                                <div class="card-body p-4 d-flex align-items-center gap-3">
                                    <div class="symbol symbol-45px flex-shrink-0">
                                        <div class="symbol-label bg-info">
                                            <i class="ki-duotone ki-send fs-2 text-white"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-info fs-2x lh-1"><?= $st['total_attempts'] ?? 0 ?></div>
                                        <div class="text-muted fs-8 mt-1">Total Submissions</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-4">
                            <div class="card border-0 bg-light-<?= $avgCol ?> h-100">
                                <div class="card-body p-4 d-flex align-items-center gap-3">
                                    <div class="symbol symbol-45px flex-shrink-0">
                                        <div class="symbol-label bg-<?= $avgCol ?>">
                                            <i class="ki-duotone ki-chart-line-up fs-2 text-white"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-<?= $avgCol ?> fs-2x lh-1"><?= $avgSc !== null ? $avgSc . '%' : '—' ?></div>
                                        <div class="text-muted fs-8 mt-1">Avg. Assessment Score</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-xl-4">
                            <div class="card border-0 bg-light-<?= $naCol ?> h-100">
                                <div class="card-body p-4 d-flex align-items-center gap-3">
                                    <div class="symbol symbol-45px flex-shrink-0">
                                        <div class="symbol-label bg-<?= $naCol ?>">
                                            <i class="ki-duotone ki-notification-on fs-2 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-<?= $naCol ?> fs-2x lh-1"><?= $st['need_attention'] ?? 0 ?></div>
                                        <div class="text-muted fs-8 mt-1">No Attempts Yet</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Stats row-->

                    <!--begin::Charts row-->
                    <div class="row g-4 mb-6">
                        <!--begin::Score distribution-->
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header border-0 pt-5 pb-0">
                                    <h6 class="fw-bold text-gray-800 fs-7 mb-0">Score Distribution</h6>
                                    <span class="text-muted fs-9">All submitted assessment attempts</span>
                                </div>
                                <div class="card-body pt-3">
                                    <?php if (array_sum($st['score_dist'] ?? [0,0,0,0,0]) > 0): ?>
                                    <div id="chart_score_dist" style="min-height:220px;"></div>
                                    <?php else: ?>
                                    <div class="text-center py-10 text-muted">
                                        <i class="ki-duotone ki-chart-simple fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                        <div class="fs-8">No submissions yet</div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Score distribution-->
                        <!--begin::Lessons by term-->
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header border-0 pt-5 pb-0">
                                    <h6 class="fw-bold text-gray-800 fs-7 mb-0">Lessons by Term</h6>
                                    <span class="text-muted fs-9">Published lessons</span>
                                </div>
                                <div class="card-body pt-3">
                                    <?php if (array_sum($st['lesson_by_term'] ?? [0,0,0]) > 0): ?>
                                    <div id="chart_lessons_term" style="min-height:220px;"></div>
                                    <?php else: ?>
                                    <div class="text-center py-10 text-muted">
                                        <i class="ki-duotone ki-book-open fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <div class="fs-8">No published lessons</div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Lessons by term-->
                    </div>
                    <!--end::Charts row-->

                    <!--begin::Assessment performance table-->
                    <?php if (!empty($st['assessment_list'])): ?>
                    <div class="card border-0 shadow-sm mb-6" style="overflow:hidden;">
                        <div class="card-header border-0 pt-5 pb-3">
                            <h6 class="fw-bold text-gray-800 fs-7 mb-0">Assessment Performance</h6>
                            <span class="text-muted fs-9"><?= count($st['assessment_list']) ?> published assessment<?= count($st['assessment_list']) !== 1 ? 's' : '' ?></span>
                        </div>
                        <div class="separator separator-dashed mx-5 mb-0"></div>
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-200 align-middle mb-0 fs-8" style="min-width:480px;">
                                <thead>
                                    <tr class="fw-bold text-muted" style="background:#f9f9f9;">
                                        <th class="ps-6 py-4">Assessment</th>
                                        <th class="text-center w-90px py-4">Type</th>
                                        <th class="text-center w-80px py-4">Submissions</th>
                                        <th class="text-center w-80px py-4">Avg Score</th>
                                        <th class="w-160px pe-6 py-4">Participation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($st['assessment_list'] as $asm):
                                    $aCfgRow  = $aTypeCfg[$asm['type']] ?? ['label'=>ucfirst($asm['type']),'color'=>'secondary','icon'=>'ki-element-plus'];
                                    $avgColor = $asm['avg_score'] === null ? 'secondary' : ($asm['avg_score'] >= 70 ? 'success' : ($asm['avg_score'] >= 50 ? 'warning' : 'danger'));
                                    $partBar  = $asm['participation'] >= 70 ? '#50cd89' : ($asm['participation'] >= 40 ? '#ffc700' : '#f1416c');
                                ?>
                                <tr>
                                    <td class="ps-6 py-4 fw-semibold text-gray-800" style="max-width:200px;">
                                        <div class="text-truncate"><?= esc($asm['name']) ?></div>
                                    </td>
                                    <td class="text-center py-4">
                                        <span class="badge badge-light-<?= $aCfgRow['color'] ?> fs-10"><?= $aCfgRow['label'] ?></span>
                                    </td>
                                    <td class="text-center fw-bold text-gray-700 py-4"><?= $asm['attempt_count'] ?></td>
                                    <td class="text-center py-4">
                                        <?php if ($asm['avg_score'] !== null): ?>
                                        <span class="fw-bold text-<?= $avgColor ?>"><?= $asm['avg_score'] ?>%</span>
                                        <?php else: ?>
                                        <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-6 py-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="flex-grow-1" style="height:7px;background:#f1f1f4;border-radius:4px;">
                                                <div style="height:7px;width:<?= $asm['participation'] ?>%;background:<?= $partBar ?>;border-radius:4px;transition:width .5s;"></div>
                                            </div>
                                            <span class="fw-bold text-gray-600 fs-9 flex-shrink-0" style="min-width:32px;"><?= $asm['participation'] ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!--end::Assessment performance table-->

                    <!--begin::Bottom row: Top students + Recent activity-->
                    <div class="row g-4 mb-6">
                        <!--begin::Top students-->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header border-0 pt-5 pb-3">
                                    <h6 class="fw-bold text-gray-800 fs-7 mb-0">
                                        <i class="ki-duotone ki-award fs-4 text-warning me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        Top Performers
                                    </h6>
                                    <span class="text-muted fs-9">By average assessment score</span>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <?php if (empty($st['top_students'])): ?>
                                    <div class="text-center py-8 text-muted">
                                        <i class="ki-duotone ki-people fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        <div class="fs-8">No submissions yet</div>
                                    </div>
                                    <?php else: ?>
                                    <?php foreach ($st['top_students'] as $ri => $ts):
                                        $tsColor = $ts['avg_sc'] >= 70 ? 'success' : ($ts['avg_sc'] >= 50 ? 'warning' : 'danger');
                                        $medal   = match($ri) { 0 => ['🥇','warning'], 1 => ['🥈','secondary'], 2 => ['🥉','danger'], default => [($ri+1).'','muted'] };
                                    ?>
                                    <div class="d-flex align-items-center gap-3 py-2 <?= $ri < count($st['top_students'])-1 ? 'border-bottom border-gray-100' : '' ?>">
                                        <span class="fw-bold text-<?= $medal[1] ?> fs-7 flex-shrink-0" style="min-width:22px;"><?= $medal[0] ?></span>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold text-gray-800 fs-8 text-truncate"><?= esc($ts['student_name']) ?></div>
                                            <div class="text-muted fs-9"><?= $ts['attempts'] ?> attempt<?= $ts['attempts'] != 1 ? 's' : '' ?></div>
                                        </div>
                                        <span class="badge badge-light-<?= $tsColor ?> fs-8 fw-bold flex-shrink-0"><?= $ts['avg_sc'] ?>%</span>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Top students-->
                        <!--begin::Recent activity-->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header border-0 pt-5 pb-3">
                                    <h6 class="fw-bold text-gray-800 fs-7 mb-0">
                                        <i class="ki-duotone ki-time fs-4 text-info me-1"><span class="path1"></span><span class="path2"></span></i>
                                        Recent Submissions
                                    </h6>
                                    <span class="text-muted fs-9">Latest 8 across all assessments</span>
                                </div>
                                <div class="card-body pt-2 pb-4">
                                    <?php if (empty($st['recent_attempts'])): ?>
                                    <div class="text-center py-8 text-muted">
                                        <i class="ki-duotone ki-send fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                                        <div class="fs-8">No submissions yet</div>
                                    </div>
                                    <?php else: ?>
                                    <?php foreach ($st['recent_attempts'] as $ri => $ra):
                                        $raCol = (float)$ra['score'] >= 70 ? 'success' : ((float)$ra['score'] >= 50 ? 'warning' : 'danger');
                                    ?>
                                    <div class="d-flex align-items-center gap-3 py-2 <?= $ri < count($st['recent_attempts'])-1 ? 'border-bottom border-gray-100' : '' ?>">
                                        <div class="symbol symbol-30px flex-shrink-0">
                                            <div class="symbol-label bg-light-<?= $raCol ?> fw-bold text-<?= $raCol ?> fs-9">
                                                <?= strtoupper(substr($ra['sname'] ?? $ra['student_name'] ?? '?', 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-semibold text-gray-800 fs-9 text-truncate"><?= esc($ra['sname'] ?? $ra['student_name'] ?? '') ?></div>
                                            <div class="text-muted fs-10 text-truncate"><?= esc($ra['aname'] ?? $ra['assessment_name'] ?? '') ?></div>
                                        </div>
                                        <div class="text-end flex-shrink-0">
                                            <div class="fw-bold text-<?= $raCol ?> fs-8"><?= number_format((float)$ra['score'], 1) ?>%</div>
                                            <div class="text-muted fs-10"><?= $ra['submitted_at'] ?></div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Recent activity-->
                    </div>
                    <!--end::Bottom row-->

                    <!--begin::Quick links-->
                    <div class="fw-bold text-gray-700 fs-7 mb-3">Quick Access</div>
                    <div class="row g-3">
                        <?php
                        $quickLinks = [
                            ['label' => 'Lessons',     'icon' => 'ki-book-open',      'slug' => 'lessons',     'color' => 'primary'],
                            ['label' => 'Assignments', 'icon' => 'ki-document',       'slug' => 'assignments', 'color' => 'warning'],
                            ['label' => 'Feedback',    'icon' => 'ki-message-text-2', 'slug' => 'feedback',    'color' => 'dark'],
                        ];
                        foreach ($quickLinks as $ql):
                        ?>
                        <div class="col-4">
                            <a href="<?= base_url('classroom/teacher/' . $schSubId . '/' . $ql['slug']) ?>"
                               class="d-flex align-items-center gap-3 p-4 rounded-2 border border-dashed border-gray-200 text-decoration-none quick-link-card">
                                <div class="symbol symbol-40px flex-shrink-0">
                                    <div class="symbol-label bg-light-<?= $ql['color'] ?>">
                                        <i class="ki-duotone <?= $ql['icon'] ?> fs-3 text-<?= $ql['color'] ?>">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                        </i>
                                    </div>
                                </div>
                                <span class="fw-semibold text-gray-800 fs-7"><?= $ql['label'] ?></span>
                                <i class="ki-duotone ki-arrow-right fs-6 text-muted ms-auto"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!--end::Quick links-->

                    <!--begin::Dashboard charts JS-->
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof ApexCharts === 'undefined') return;

                        <?php $distData = $st['score_dist'] ?? [0,0,0,0,0]; ?>
                        <?php if (array_sum($distData) > 0): ?>
                        new ApexCharts(document.getElementById('chart_score_dist'), {
                            series: [{ name: 'Students', data: <?= json_encode(array_values($distData)) ?> }],
                            chart: { type: 'bar', height: 220, toolbar: { show: false }, sparkline: { enabled: false } },
                            plotOptions: { bar: { borderRadius: 5, columnWidth: '55%', dataLabels: { position: 'top' } } },
                            dataLabels: { enabled: true, offsetY: -16, style: { fontSize: '11px', colors: ['#5e6278'] } },
                            xaxis: {
                                categories: ['0–19%', '20–39%', '40–59%', '60–79%', '80–100%'],
                                labels: { style: { fontSize: '11px', colors: '#a1a5b7' } },
                                axisBorder: { show: false }, axisTicks: { show: false }
                            },
                            yaxis: { labels: { style: { colors: '#a1a5b7' }, formatter: v => Math.round(v) }, min: 0 },
                            colors: ['#009ef7'],
                            grid: { strokeDashArray: 4, borderColor: '#f1f1f4' },
                            tooltip: { y: { formatter: v => v + ' student' + (v !== 1 ? 's' : '') } }
                        }).render();
                        <?php endif; ?>

                        <?php $termData = array_values($st['lesson_by_term'] ?? [0,0,0]); ?>
                        <?php if (array_sum($termData) > 0): ?>
                        new ApexCharts(document.getElementById('chart_lessons_term'), {
                            series: <?= json_encode($termData) ?>,
                            chart: { type: 'donut', height: 220 },
                            labels: ['Term 1', 'Term 2', 'Term 3'],
                            colors: ['#50cd89', '#009ef7', '#ffc700'],
                            legend: { position: 'bottom', fontSize: '12px' },
                            dataLabels: { formatter: (val, opts) => opts.w.config.series[opts.seriesIndex] },
                            plotOptions: { pie: { donut: { size: '60%',
                                labels: { show: true, total: { show: true, label: 'Lessons', fontSize: '12px',
                                    formatter: () => <?= array_sum($termData) ?> + ' total' } } } } },
                            stroke: { width: 2 }
                        }).render();
                        <?php endif; ?>
                    });
                    </script>
                    <!--end::Dashboard charts JS-->
                    <!--end::Dashboard-->

                    <?php elseif ($section === 'lessons'): ?>
                    <!--begin::Lessons-->
                    <?php
                    // Group lessons: [term][week][day(0=unscheduled)] = lesson[]
                    $lessonMap  = [];
                    $termCounts = [1 => 0, 2 => 0, 3 => 0];
                    foreach (($lessons ?? []) as $l) {
                        $t = (int) $l['lesson_term'];
                        $w = (int) ($l['lesson_week'] ?? 0);
                        $d = (int) ($l['lesson_day']  ?? 0); // 0 = no day assigned
                        if ($t >= 1 && $t <= 3) {
                            $lessonMap[$t][$w][$d][] = $l;
                            $termCounts[$t]++;
                        }
                    }
                    $dayShort = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri'];
                    $dayLong  = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday'];
                    $todayStr = date('Y-m-d');
                    $todayDow = min(5, max(1, (int) date('N')));
                    $schSubIdV = (int) $schSubId;
                    ?>

                    <!--begin::Lessons header-->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ki-duotone ki-book-open fs-2 text-primary me-1">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <h4 class="fw-bold text-gray-800 mb-0">Lessons</h4>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <?php if ($classroomIsActive): ?>
                            <button type="button" class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal" data-bs-target="#modal_add_lesson">
                                <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                Add Lesson
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!--end::Lessons header-->

                    <!--begin::Term tabs-->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-semibold mb-6">
                        <?php foreach ([1, 2, 3] as $t): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $t === ($activeTerm ?? 1) ? 'active' : '' ?> text-active-primary pb-4"
                               data-bs-toggle="tab" data-bs-target="#lessons_term_<?= $t ?>">
                                Term <?= $t ?>
                                <span class="badge badge-light ms-1 fs-9"><?= $termCounts[$t] ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <!--end::Term tabs-->

                    <div class="tab-content">
                    <?php foreach ([1, 2, 3] as $t): ?>
                    <div class="tab-pane fade <?= $t === ($activeTerm ?? 1) ? 'show active' : '' ?>" id="lessons_term_<?= $t ?>">

                        <?php if (empty($termSchedule[$t])): ?>
                        <div class="text-center py-12 text-muted">
                            <i class="ki-duotone ki-information-5 fs-3x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="fs-6 fw-semibold mb-1">No schedule configured for Term <?= $t ?></div>
                            <div class="fs-8">Set Term <?= $t ?> start/end dates in School Configuration.</div>
                        </div>

                        <?php else: ?>

                        <?php foreach ($termSchedule[$t] as $wNum => $week):
                            $weekTotalLessons = 0;
                            for ($d = 1; $d <= 5; $d++) $weekTotalLessons += count($lessonMap[$t][$wNum][$d] ?? []);
                            $isCurrentWeek = $week['is_current_week'];
                        ?>
                        <!--begin::Week-->
                        <div class="mb-7" id="week_t<?= $t ?>_w<?= $wNum ?>">

                            <!--begin::Week header-->
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge <?= $isCurrentWeek ? 'badge-primary' : 'badge-light-primary' ?> fs-8 fw-bold px-3 py-2">
                                    Week <?= $wNum ?>
                                </span>
                                <span class="text-muted fs-8">
                                    <?= date('M j', strtotime($week['start_date'])) ?> – <?= date('M j', strtotime($week['end_date'])) ?>
                                </span>
                                <?php if ($isCurrentWeek): ?>
                                <span class="badge badge-light-success fs-9 py-1 px-2">Current week</span>
                                <?php endif; ?>
                                <div class="flex-grow-1" style="height:1px;background:#f1f1f4;"></div>
                                <?php if ($weekTotalLessons > 0): ?>
                                <span class="text-muted fs-9"><?= $weekTotalLessons ?> lesson<?= $weekTotalLessons !== 1 ? 's' : '' ?></span>
                                <?php endif; ?>
                            </div>
                            <!--end::Week header-->

                            <!--begin::Day cards row-->
                            <div class="d-flex gap-2" style="overflow-x:auto;padding-bottom:2px;">
                            <?php for ($d = 1; $d <= 5; $d++):
                                $dayDate     = $week['days'][$d];
                                $dayLessons  = $lessonMap[$t][$wNum][$d] ?? [];
                                $lCount      = count($dayLessons);
                                $fCount      = array_sum(array_column($dayLessons, 'file_count'));
                                $vCount      = array_sum(array_column($dayLessons, 'video_count'));
                                $lkCount     = array_sum(array_column($dayLessons, 'link_count'));
                                $isToday     = ($dayDate === $todayStr);
                                $isPast      = ($dayDate < $todayStr);
                                $panelId     = "panel_t{$t}_w{$wNum}_d{$d}";
                                $isHoliday   = isset($holidays[$dayDate]);
                                $holidayName = $holidays[$dayDate]['name'] ?? null;
                                $isObserved  = $holidays[$dayDate]['is_observed'] ?? false;
                            ?>
                            <div class="flex-fill" style="min-width:110px;max-width:190px;">
                                <?php if ($isHoliday && $lCount === 0): ?>
                                <!--begin::Day card — public holiday (no lessons)-->
                                <div class="card h-100 text-center bg-light-danger border-danger"
                                     style="border-radius:.75rem;border:1px dashed #f1416c!important;"
                                     title="Public Holiday: <?= esc($holidayName) ?>">
                                    <div class="card-body p-3">
                                        <div class="fw-bold fs-8 text-danger"><?= $dayShort[$d] ?></div>
                                        <div class="text-danger fs-9 mb-1"><?= date('M j', strtotime($dayDate)) ?></div>
                                        <?php if ($isToday): ?><div class="mb-1"><span class="badge badge-danger rounded-pill px-2 py-1" style="font-size:9px;">Today</span></div><?php endif; ?>
                                        <div class="mb-1">
                                            <i class="ki-duotone ki-flag fs-6 text-danger mb-1"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <div class="text-danger fw-semibold lh-sm" style="font-size:9px;word-break:break-word;"><?= esc($holidayName) ?></div>
                                        <?php if ($isObserved): ?><div class="mt-1"><span class="badge badge-light-warning" style="font-size:8px;">Observed</span></div><?php endif; ?>
                                    </div>
                                </div>
                                <!--end::Day card — public holiday (no lessons)-->

                                <?php elseif ($lCount > 0): ?>
                                <!--begin::Day card — has lessons-->
                                <div class="card h-100 text-center cursor-pointer <?= $isHoliday ? 'bg-light-danger' : ($isToday ? 'border-primary bg-light-primary' : 'border border-dashed border-gray-300') ?>"
                                     style="border-radius:.75rem;transition:box-shadow .15s,border-color .15s;<?= $isHoliday ? 'border:1px dashed #f1416c!important;' : '' ?>"
                                     onclick="navuliToggleDayPanel('<?= $panelId ?>', this)"
                                     title="<?= $isHoliday ? 'Public Holiday: ' . esc($holidayName) . ' — ' : '' ?><?= $dayLong[$d] ?> <?= date('M j', strtotime($dayDate)) ?>">
                                    <div class="card-body p-3">
                                        <div class="fw-bold fs-8 <?= $isHoliday ? 'text-danger' : ($isToday ? 'text-primary' : 'text-gray-700') ?>"><?= $dayShort[$d] ?></div>
                                        <div class="<?= $isHoliday ? 'text-danger' : ($isPast && !$isToday ? 'text-muted' : 'text-gray-600') ?> fs-9 mb-1"><?= date('M j', strtotime($dayDate)) ?></div>
                                        <?php if ($isToday && !$isHoliday): ?><div class="mb-1"><span class="badge badge-primary rounded-pill px-2 py-1" style="font-size:9px;">Today</span></div><?php endif; ?>
                                        <?php if ($isHoliday): ?>
                                        <div class="mb-1">
                                            <i class="ki-duotone ki-flag fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <div class="text-danger lh-sm mb-1" style="font-size:9px;word-break:break-word;"><?= esc($holidayName) ?></div>
                                        <?php if ($isObserved): ?><div class="mb-1"><span class="badge badge-light-warning" style="font-size:8px;">Observed</span></div><?php endif; ?>
                                        <?php endif; ?>
                                        <div class="mb-1">
                                            <span class="badge <?= $isHoliday ? 'badge-light-warning' : 'badge-light-success' ?> fw-bold" style="font-size:9px;"><?= $lCount ?> lesson<?= $lCount !== 1 ? 's' : '' ?></span>
                                        </div>
                                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                                            <?php if ($fCount > 0): ?><span class="badge badge-light-primary" style="font-size:9px;"><?= $fCount ?> file<?= $fCount !== 1 ? 's' : '' ?></span><?php endif; ?>
                                            <?php if ($vCount > 0): ?><span class="badge badge-light-danger" style="font-size:9px;"><?= $vCount ?> vid<?= $vCount !== 1 ? 's' : '' ?></span><?php endif; ?>
                                            <?php if ($lkCount > 0): ?><span class="badge badge-light-info" style="font-size:9px;"><?= $lkCount ?> link<?= $lkCount !== 1 ? 's' : '' ?></span><?php endif; ?>
                                        </div>
                                        <div class="mt-2">
                                            <i class="ki-duotone ki-down fs-8 text-muted day-chevron" style="transition:transform .2s;"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Day card — has lessons-->

                                <?php else: ?>
                                <!--begin::Day card — empty-->
                                <div class="card h-100 text-center <?= $isToday ? 'border-primary border border-dashed' : 'border border-dashed' ?> <?= $isPast ? 'bg-light' : '' ?>"
                                     style="border-radius:.75rem;<?= !$isPast ? 'border-color:#e4e6ea!important;' : '' ?>">
                                    <div class="card-body p-3">
                                        <div class="fw-semibold fs-8 text-gray-400"><?= $dayShort[$d] ?></div>
                                        <div class="text-muted fs-9 mb-2"><?= date('M j', strtotime($dayDate)) ?></div>
                                        <?php if ($isToday): ?><div class="mb-1"><span class="badge badge-primary rounded-pill px-2 py-1" style="font-size:9px;">Today</span></div><?php endif; ?>
                                        <div class="text-gray-300 mb-2" style="font-size:11px;">—</div>
                                        <button type="button"
                                                class="btn btn-icon btn-light-primary"
                                                style="width:24px;height:24px;"
                                                title="Add lesson for <?= $dayLong[$d] ?>"
                                                onclick="navuliOpenLessonModal(<?= $t ?>, <?= $wNum ?>, <?= $d ?>, event)"
                                                data-bs-toggle="modal" data-bs-target="#modal_add_lesson">
                                            <i class="ki-duotone ki-plus p-0" style="font-size:11px;"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                    </div>
                                </div>
                                <!--end::Day card — empty-->
                                <?php endif; ?>
                            </div>
                            <?php endfor; ?>
                            </div>
                            <!--end::Day cards row-->

                            <!--begin::Day lesson panels-->
                            <?php for ($d = 1; $d <= 5; $d++):
                                $dayLessons   = $lessonMap[$t][$wNum][$d] ?? [];
                                if (empty($dayLessons)) continue;
                                $panelDayDate    = $week['days'][$d];
                                $_ph             = $holidays[$panelDayDate] ?? null;
                                $panelHoliday    = $_ph['name'] ?? null;
                                $panelObserved   = $_ph['is_observed'] ?? false;
                                $autoOpen     = ($isCurrentWeek && $d === $todayDow);
                            ?>
                            <div id="panel_t<?= $t ?>_w<?= $wNum ?>_d<?= $d ?>" class="navuli-day-panel mt-3<?= $autoOpen ? '' : ' d-none' ?>">
                                <div class="card border-0 shadow-xs" style="background:<?= $panelHoliday ? '#fff5f8' : '#f8f9fc' ?>;border-radius:.75rem;<?= $panelHoliday ? 'border:1px dashed #f1416c!important;' : '' ?>">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="fw-bold fs-7 <?= $panelHoliday ? 'text-danger' : 'text-gray-700' ?>">
                                                <i class="ki-duotone <?= $panelHoliday ? 'ki-flag text-danger' : 'ki-calendar text-primary' ?> fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                                                <?= $dayLong[$d] ?>, <?= date('M j, Y', strtotime($panelDayDate)) ?>
                                                <?php if ($panelHoliday): ?>
                                                <span class="badge badge-light-danger ms-2 fw-normal fs-9"><?= esc($panelHoliday) ?></span>
                                                <?php if ($panelObserved): ?><span class="badge badge-light-warning ms-1 fw-normal fs-9">Observed</span><?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!$panelHoliday): ?>
                                            <button type="button"
                                                    class="btn btn-sm btn-light-primary py-1 px-3"
                                                    onclick="navuliOpenLessonModal(<?= $t ?>, <?= $wNum ?>, <?= $d ?>, event)"
                                                    data-bs-toggle="modal" data-bs-target="#modal_add_lesson">
                                                <i class="ki-duotone ki-plus fs-7 me-1"><span class="path1"></span><span class="path2"></span></i>Add
                                            </button>
                                            <?php else: ?>
                                            <span class="badge badge-light-danger fs-9 py-2 px-3">
                                                <i class="ki-duotone ki-shield-cross fs-7 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>Public Holiday
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex flex-column gap-2">
                                        <?php foreach ($dayLessons as $lesson):
                                            $sc = match($lesson['lesson_status']) { 'Published'=>'success','Draft'=>'warning',default=>'secondary' };
                                            $lu = base_url("classroom/teacher/{$schSubIdV}/lesson/{$lesson['lesson_id']}");
                                        ?>
                                        <a href="<?= $lu ?>" class="text-decoration-none">
                                            <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-2 hover-elevate-up">
                                                <div class="w-35px h-35px rounded-2 bg-light-primary d-flex align-items-center justify-content-center flex-shrink-0">
                                                    <i class="ki-duotone ki-book-open fs-5 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <div class="fw-bold text-gray-800 fs-7 text-hover-primary text-truncate lh-sm"><?= esc($lesson['lesson_title']) ?></div>
                                                    <?php if (!empty($lesson['lesson_desc'])): ?>
                                                    <div class="text-muted fs-9 text-truncate"><?= esc(mb_substr($lesson['lesson_desc'], 0, 90)) ?><?= mb_strlen($lesson['lesson_desc']) > 90 ? '…' : '' ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                                                    <span class="badge badge-light-<?= $sc ?> fs-10"><?= $lesson['lesson_status'] ?></span>
                                                    <div class="d-flex gap-1">
                                                        <?php if ((int)$lesson['file_count'] > 0): ?><span class="badge badge-light-primary fs-10"><?= (int)$lesson['file_count'] ?>f</span><?php endif; ?>
                                                        <?php if ((int)$lesson['video_count'] > 0): ?><span class="badge badge-light-danger fs-10"><?= (int)$lesson['video_count'] ?>v</span><?php endif; ?>
                                                        <?php if ((int)$lesson['link_count'] > 0): ?><span class="badge badge-light-info fs-10"><?= (int)$lesson['link_count'] ?>lk</span><?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endfor; ?>
                            <!--end::Day lesson panels-->

                            <!--begin::Unscheduled (no day) lessons for this week-->
                            <?php if (!empty($lessonMap[$t][$wNum][0])): ?>
                            <div class="mt-3 p-4 rounded-2" style="background:#fffbf0;border:1px dashed #f9c74f;">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="ki-duotone ki-information-5 fs-6 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <span class="fw-semibold text-gray-700 fs-8">Unscheduled — no day assigned</span>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                <?php foreach ($lessonMap[$t][$wNum][0] as $lesson):
                                    $sc = match($lesson['lesson_status']) { 'Published'=>'success','Draft'=>'warning',default=>'secondary' };
                                    $lu = base_url("classroom/teacher/{$schSubIdV}/lesson/{$lesson['lesson_id']}");
                                ?>
                                <a href="<?= $lu ?>" class="text-decoration-none">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-2">
                                        <div class="w-30px h-30px rounded-2 bg-light-warning d-flex align-items-center justify-content-center flex-shrink-0">
                                            <i class="ki-duotone ki-book-open fs-6 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-bold text-gray-800 fs-7 text-hover-primary text-truncate"><?= esc($lesson['lesson_title']) ?></div>
                                        </div>
                                        <span class="badge badge-light-<?= $sc ?> fs-10 flex-shrink-0"><?= $lesson['lesson_status'] ?></span>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <!--end::Unscheduled-->

                        </div>
                        <!--end::Week-->
                        <?php endforeach; // weeks ?>

                        <!--begin::Lessons with no week-->
                        <?php if (!empty($lessonMap[$t][0])): ?>
                        <div class="mb-7 p-4 rounded-2" style="background:#f8f8ff;border:1px dashed #d1d3e0;">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <span class="badge badge-light-warning fw-bold fs-8 px-3 py-2">No Week Assigned</span>
                                <div class="flex-grow-1" style="height:1px;background:#e4e6ea;"></div>
                            </div>
                            <div class="d-flex flex-column gap-2">
                            <?php foreach ($lessonMap[$t][0] as $dayGroup):
                                foreach ($dayGroup as $lesson):
                                    $sc = match($lesson['lesson_status']) { 'Published'=>'success','Draft'=>'warning',default=>'secondary' };
                                    $lu = base_url("classroom/teacher/{$schSubIdV}/lesson/{$lesson['lesson_id']}");
                            ?>
                            <a href="<?= $lu ?>" class="text-decoration-none">
                                <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-2">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-bold text-gray-800 fs-7 text-hover-primary text-truncate"><?= esc($lesson['lesson_title']) ?></div>
                                    </div>
                                    <span class="badge badge-light-<?= $sc ?> fs-10"><?= $lesson['lesson_status'] ?></span>
                                </div>
                            </a>
                            <?php endforeach; endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <!--end::Lessons with no week-->

                        <?php endif; // termSchedule check ?>
                    </div>
                    <?php endforeach; // terms ?>
                    </div>
                    <!--end::Lessons-->

                    <?php elseif ($section === 'assignments'): ?>
                    <!--begin::Assignments-->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ki-duotone ki-document fs-2 text-primary me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <h4 class="fw-bold text-gray-800 mb-0">Assignments</h4>
                            <span class="badge badge-light-primary ms-2 fs-8"><?= count($assignments ?? []) ?></span>
                        </div>
                        <?php if ($classroomIsActive): ?>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_assignment">
                            <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Add Assignment
                        </button>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($assignments)): ?>
                    <div class="text-center py-16 text-muted">
                        <i class="ki-duotone ki-document fs-4x text-gray-200 mb-3">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div class="fs-6 fw-semibold mb-3">No assignments yet.</div>
                        <?php if ($classroomIsActive): ?>
                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modal_add_assignment">
                            <i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Create First Assignment
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="row g-5" id="assignments_grid">
                    <?php foreach ($assignments as $asgn):
                        $statusColor = match($asgn['assignment_status']) {
                            'Published' => 'success', 'Draft' => 'warning', 'Archived' => 'secondary', default => 'secondary'
                        };
                        $isPublished = $asgn['assignment_status'] === 'Published';
                        $isPastDue   = !empty($asgn['assignment_due_date']) && strtotime($asgn['assignment_due_date']) < time();
                    ?>
                    <div class="col-md-4" id="asgn_card_<?= $asgn['assignment_id'] ?>">
                        <div class="card border border-dashed h-100" style="border-radius:.75rem;overflow:visible;position:relative;border-color:#c4c4d4!important;">
                            <!--begin::Dropdown-->
                            <div style="position:absolute;top:10px;right:10px;z-index:10;">
                                <button type="button" class="btn btn-sm btn-icon btn-light"
                                        style="width:30px;height:30px;border-radius:6px;"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ki-duotone ki-down fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end py-2 fs-7 fw-semibold" style="min-width:175px;">
                                    <?php if ($classroomIsActive): ?>
                                    <li>
                                        <a href="#" class="dropdown-item py-2 btn-edit-assignment <?= $isPublished ? 'text-muted pe-none' : '' ?>"
                                           data-id="<?= $asgn['assignment_id'] ?>"
                                           data-name="<?= esc($asgn['assignment_name']) ?>"
                                           data-due="<?= $asgn['assignment_due_date'] ? date('Y-m-d H:i:s', strtotime($asgn['assignment_due_date'])) : '' ?>"
                                           data-total="<?= $asgn['assignment_total_score'] ?? 100 ?>"
                                           data-status="<?= $asgn['assignment_status'] ?>"
                                           data-file="<?= esc($asgn['assignment_file'] ?? '') ?>"
                                           data-is-published="<?= $isPublished ? '1' : '0' ?>">
                                            <i class="ki-duotone <?= $isPublished ? 'ki-lock' : 'ki-pencil' ?> fs-6 me-2">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>Edit Assignment
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <li>
                                        <a href="<?= base_url('classroom/teacher/' . $schSubId . '/assignment/' . $asgn['assignment_id'] . '/mark') ?>"
                                           class="dropdown-item py-2">
                                            <i class="ki-duotone ki-pencil fs-6 me-2">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>Mark Assignment
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('classroom/teacher/' . $schSubId . '/assignment/' . $asgn['assignment_id'] . '/analysis') ?>"
                                           class="dropdown-item py-2">
                                            <i class="ki-duotone ki-chart-simple-3 fs-6 me-2">
                                                <span class="path1"></span><span class="path2"></span>
                                                <span class="path3"></span><span class="path4"></span>
                                            </i>View Analysis
                                        </a>
                                    </li>
                                    <?php if ($classroomIsActive): ?>
                                    <li><div class="separator separator-dashed my-1"></div></li>
                                    <li>
                                        <a href="#" class="dropdown-item py-2 btn-del-assignment <?= $isPublished ? 'text-muted pe-none' : 'text-danger' ?>"
                                           data-id="<?= $asgn['assignment_id'] ?>"
                                           data-name="<?= esc($asgn['assignment_name']) ?>"
                                           data-is-published="<?= $isPublished ? '1' : '0' ?>">
                                            <i class="ki-duotone <?= $isPublished ? 'ki-lock' : 'ki-trash' ?> fs-6 me-2">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>Delete Assignment
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <!--end::Dropdown-->
                            <div class="card-body p-5 pt-4">
                                <span class="badge badge-light-<?= $statusColor ?> fs-9 mb-3"><?= $asgn['assignment_status'] ?></span>
                                <div class="fw-bold text-gray-800 fs-6 mb-3 pe-8 lh-sm"><?= esc($asgn['assignment_name']) ?></div>
                                <?php if (!empty($asgn['assignment_file'])): ?>
                                <a href="<?= base_url('uploads/assignments/' . $asgn['assignment_file']) ?>" target="_blank"
                                   class="d-flex align-items-center gap-2 text-decoration-none mb-3 p-2 rounded-2 bg-light-primary">
                                    <i class="ki-duotone ki-file-down fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="text-primary fw-semibold fs-8 text-truncate"><?= esc($asgn['assignment_file']) ?></span>
                                </a>
                                <?php endif; ?>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="ki-duotone ki-calendar fs-5 <?= $isPastDue ? 'text-danger' : 'text-warning' ?>">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <span class="fs-8 fw-semibold <?= $isPastDue ? 'text-danger' : 'text-gray-700' ?>">
                                        Due: <?= !empty($asgn['assignment_due_date']) ? date('d M Y, g:i A', strtotime($asgn['assignment_due_date'])) : '—' ?>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 pt-3 mt-1" style="border-top:1px solid #f1f1f4;">
                                    <i class="ki-duotone ki-time fs-6 text-muted"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="text-muted fs-9">
                                        Created <?= !empty($asgn['created_at']) ? date('d M Y', strtotime($asgn['created_at'])) : '—' ?>
                                        <?= !empty($asgn['creator_name']) ? ' by ' . esc($asgn['creator_name']) : '' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <!--end::Assignments-->

                    <?php elseif ($section === 'exams'): ?>
                    <!--begin::Exams-->
                    <?php
                    $examStudents    = $examStudents    ?? [];
                    $examMarks       = $examMarks       ?? [1=>[],2=>[],3=>[]];
                    $reportStatuses  = $reportStatuses  ?? [1=>['status'=>'collecting'],2=>['status'=>'collecting'],3=>['status'=>'collecting']];
                    $isClassTeacher  = $isClassTeacher  ?? false;
                    ?>
                    <div class="d-flex align-items-center mb-5">
                        <i class="ki-duotone ki-note-2 fs-2 text-primary me-2">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span>
                        </i>
                        <h4 class="fw-bold text-gray-800 mb-0">Term Examinations</h4>
                    </div>

                    <?php if (empty($examStudents)): ?>
                    <div class="text-center py-12 text-muted">
                        <i class="ki-duotone ki-people fs-4x text-gray-200 mb-3">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                        <div class="fs-7">No students enrolled in this class yet.</div>
                    </div>
                    <?php else: ?>

                    <!--begin::Term tabs-->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-semibold mb-5">
                        <?php foreach ([1,2,3] as $t):
                            $tStatus = $reportStatuses[$t]['status'] ?? 'collecting';
                            $tBadge  = match($tStatus) {
                                'published'    => '<span class="badge badge-light-success ms-2 fs-9">Published</span>',
                                'ct_submitted' => '<span class="badge badge-light-warning ms-2 fs-9">Pending Principal</span>',
                                default        => '',
                            };
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $t===1?'active':'' ?> text-active-primary pb-4"
                               data-bs-toggle="tab" data-bs-target="#exam_term_<?= $t ?>">
                                Term <?= $t ?><?= $tBadge ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tab-content">
                    <?php foreach ([1,2,3] as $t):
                        $tStatus     = $reportStatuses[$t]['status'] ?? 'collecting';
                        $isLocked    = in_array($tStatus, ['ct_submitted','published']);
                        $isPublished = $tStatus === 'published';
                        $examReadonly = $isLocked || !$classroomIsActive;
                    ?>
                    <div class="tab-pane fade <?= $t===1?'show active':'' ?>" id="exam_term_<?= $t ?>">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <?php if ($examReadonly): ?>
                            <div class="alert alert-<?= $isPublished?'success':($isLocked?'warning':'secondary') ?> d-flex align-items-center gap-2 py-2 px-3 mb-0 flex-grow-1 me-3">
                                <i class="ki-duotone ki-lock fs-5 flex-shrink-0"><span class="path1"></span><span class="path2"></span></i>
                                <span class="fs-7 fw-semibold">
                                    <?php if ($isPublished): ?>Report published — marks are finalised.
                                    <?php elseif ($isLocked): ?>Submitted to Principal — marks are locked.
                                    <?php else: ?>View only — this classroom is no longer active.
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php else: ?>
                            <div></div>
                            <?php endif; ?>
                            <?php if ($isClassTeacher && $classroomIsActive): ?>
                            <?php if ($isPublished): ?>
                            <span class="btn btn-sm btn-light disabled" title="Report already published" style="cursor:not-allowed;">
                                <i class="ki-duotone ki-shield-tick fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                                Class Teacher Review
                                <span class="badge badge-light-success ms-1 fs-9">Published</span>
                            </span>
                            <?php else: ?>
                            <a href="<?= base_url('classroom/class-exam/' . $classId . '/term/' . $t) ?>"
                               class="btn btn-sm btn-light-primary <?= $isLocked ? 'btn-light disabled' : '' ?>"
                               <?= $isLocked ? 'title="Submitted to principal — locked" style="cursor:not-allowed;"' : '' ?>>
                                <i class="ki-duotone ki-shield-tick fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                                Class Teacher Review
                                <?php if ($isLocked): ?>
                                <span class="badge badge-light-warning ms-1 fs-9">Locked</span>
                                <?php endif; ?>
                            </a>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3 fs-7">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="ps-4 min-w-150px">Student</th>
                                    <th class="min-w-100px text-center">Mark</th>
                                    <th class="min-w-80px text-center">Out Of</th>
                                    <th class="min-w-60px text-center">%</th>
                                    <th class="min-w-60px text-center">Grade</th>
                                    <th class="min-w-200px">Teacher Comment</th>
                                    <th class="text-center min-w-120px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($examStudents as $stu):
                                $sid      = $stu['user_id'];
                                $mRow     = $examMarks[$t][$sid] ?? null;
                                $isAbsent = (bool) ($mRow['is_absent'] ?? false);
                                $mark     = $mRow ? $mRow['mark']            : '';
                                $total    = $mRow ? $mRow['total_mark']      : '100';
                                $tcmt     = $mRow ? ($mRow['teacher_comment'] ?? '') : '';
                                $pct      = (!$isAbsent && $mark !== null && $mark !== '' && $total > 0) ? round(($mark / $total) * 100, 1) : null;
                                $grade    = $isAbsent ? 'ABS' : ($pct !== null ? \App\Models\TermExamModel::grade($pct) : '—');
                                $gColor   = $isAbsent ? 'danger' : ($pct !== null ? \App\Models\TermExamModel::gradeColor($grade) : 'secondary');
                                $initials = strtoupper(substr($stu['fname'],0,1).substr($stu['lname'],0,1));
                            ?>
                            <tr id="exam_row_<?= $t ?>_<?= $sid ?>" data-absent="<?= $isAbsent?'1':'0' ?>">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (!empty($stu['profile_photo'])): ?>
                                        <img src="<?= base_url('uploads/profilePhoto/'.$stu['profile_photo']) ?>"
                                             class="rounded-circle" style="width:32px;height:32px;object-fit:cover;" />
                                        <?php else: ?>
                                        <div class="symbol symbol-32px">
                                            <div class="symbol-label bg-light-primary fw-bold text-primary fs-9"><?= $initials ?></div>
                                        </div>
                                        <?php endif; ?>
                                        <div>
                                            <span class="fw-semibold text-gray-800 fs-7"><?= esc($stu['fname'].' '.$stu['lname']) ?></span>
                                            <?php if ($isAbsent): ?>
                                            <span class="badge badge-light-danger fs-9 ms-2">ABSENT</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($examReadonly): ?>
                                    <?php if ($isAbsent): ?>
                                    <span class="badge badge-light-danger fs-8">ABS</span>
                                    <?php else: ?>
                                    <span class="fw-bold fs-7"><?= $mark !== '' ? $mark : '—' ?></span>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <div class="exam-mark-cell-<?= $t ?>-<?= $sid ?>">
                                        <?php if ($isAbsent): ?>
                                        <span class="badge badge-light-danger fs-8">ABS</span>
                                        <?php else: ?>
                                        <input type="number" class="form-control form-control-sm text-center exam-mark-input"
                                               style="width:80px;margin:auto;"
                                               data-sid="<?= $sid ?>" data-term="<?= $t ?>"
                                               value="<?= esc($mark) ?>" min="0" max="<?= $total ?>" step="0.5"
                                               placeholder="0" />
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($examReadonly): ?>
                                    <span class="text-muted fs-7"><?= $isAbsent ? '—' : $total ?></span>
                                    <?php else: ?>
                                    <div class="exam-total-cell-<?= $t ?>-<?= $sid ?>">
                                        <?php if ($isAbsent): ?>
                                        <span class="text-muted fs-8">—</span>
                                        <?php else: ?>
                                        <input type="number" class="form-control form-control-sm text-center exam-total-input"
                                               style="width:70px;margin:auto;"
                                               data-sid="<?= $sid ?>" data-term="<?= $t ?>"
                                               value="<?= esc($total) ?>" min="1" max="1000" step="0.5" />
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-7 exam-pct-<?= $t ?>-<?= $sid ?>">
                                        <?= $isAbsent ? '<span class="text-danger">ABS</span>' : ($pct !== null ? $pct.'%' : '—') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light-<?= $gColor ?> fs-8 exam-grade-<?= $t ?>-<?= $sid ?>">
                                        <?= $grade ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($examReadonly): ?>
                                    <span class="text-muted fs-8"><?= $isAbsent ? '—' : ($tcmt ? esc($tcmt) : '—') ?></span>
                                    <?php else: ?>
                                    <div class="exam-cmt-cell-<?= $t ?>-<?= $sid ?>">
                                        <?php if (!$isAbsent): ?>
                                        <input type="text" class="form-control form-control-sm exam-cmt-input"
                                               data-sid="<?= $sid ?>" data-term="<?= $t ?>"
                                               value="<?= esc($tcmt) ?>" placeholder="Optional comment..." maxlength="500" />
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center" style="white-space:nowrap;">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <?php if (!$examReadonly): ?>
                                        <button class="btn btn-sm btn-icon btn-light-primary btn-save-mark"
                                                data-sid="<?= $sid ?>" data-term="<?= $t ?>"
                                                title="Save mark" style="width:32px;height:32px;"
                                                <?= $isAbsent?'disabled':'' ?>>
                                            <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                        <button class="btn btn-sm <?= $isAbsent?'btn-danger':'btn-outline-danger' ?> btn-toggle-absent d-flex align-items-center gap-1"
                                                data-sid="<?= $sid ?>" data-term="<?= $t ?>"
                                                data-total="<?= esc($total) ?>"
                                                style="padding:4px 8px;font-size:.7rem;font-weight:600;height:32px;">
                                            <?php if ($isAbsent): ?>
                                            <i class="ki-duotone ki-check fs-7"><span class="path1"></span><span class="path2"></span></i>Present
                                            <?php else: ?>
                                            <i class="ki-duotone ki-user-cross fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>ABS
                                            <?php endif; ?>
                                        </button>
                                        <?php endif; ?>
                                        <a href="<?= base_url('classroom/report/' . $classId . '/student/' . $sid . '/term/' . $t) ?>"
                                           target="_blank"
                                           class="btn btn-sm btn-icon btn-light-info"
                                           title="Preview report card" style="width:32px;height:32px;">
                                            <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <!--end::Term tabs-->

                    <?php endif; // empty examStudents ?>

                    <script>
                    const EXAM_SAVE_URL = '<?= base_url('classroom/teacher/'.$schSubId.'/exam/mark/save') ?>';

                    // Auto-activate term tab if ?term= is in the URL
                    (function() {
                        const urlTerm = new URLSearchParams(window.location.search).get('term');
                        if (urlTerm && ['1','2','3'].includes(urlTerm)) {
                            const tabEl = document.querySelector('[data-bs-target="#exam_term_' + urlTerm + '"]');
                            if (tabEl) new bootstrap.Tab(tabEl).show();
                        }
                    })();

                    // ── Toast helper ─────────────────────────────────────────────
                    const ExamToast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        didOpen: t => {
                            t.addEventListener('mouseenter', Swal.stopTimer);
                            t.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    const GRADE_COLORS = {'A+':'success','A':'success','B':'primary','C':'info','F':'danger','ABS':'danger','—':'secondary'};

                    function applyMarkResult(res, sid, term) {
                        const pctEl   = $(`.exam-pct-${term}-${sid}`);
                        const gradeEl = $(`.exam-grade-${term}-${sid}`);
                        const row     = $(`#exam_row_${term}_${sid}`);

                        if (res.is_absent) {
                            pctEl.html('<span class="text-danger fw-bold">ABS</span>');
                            gradeEl.text('ABS').attr('class', `badge badge-light-danger fs-8 exam-grade-${term}-${sid}`);
                            row.attr('data-absent','1');
                        } else {
                            pctEl.text(res.pct !== null ? res.pct + '%' : '—');
                            const g = res.grade || '—';
                            gradeEl.text(g).attr('class', `badge badge-light-${GRADE_COLORS[g]||'secondary'} fs-8 exam-grade-${term}-${sid}`);
                            row.attr('data-absent','0');
                        }
                    }

                    // ── Save mark ─────────────────────────────────────────────────
                    $(document).on('click', '.btn-save-mark', function() {
                        const btn  = $(this);
                        const sid  = btn.data('sid');
                        const term = btn.data('term');
                        const mark  = $(`input.exam-mark-input[data-sid="${sid}"][data-term="${term}"]`).val().trim();
                        const total = $(`input.exam-total-input[data-sid="${sid}"][data-term="${term}"]`).val() || 100;
                        const cmt   = $(`input.exam-cmt-input[data-sid="${sid}"][data-term="${term}"]`).val().trim();

                        // ── Validation ────────────────────────────────────────────
                        if (mark === '' || mark === null) {
                            ExamToast.fire({ icon: 'warning', title: 'Mark is required before saving' });
                            $(`input.exam-mark-input[data-sid="${sid}"][data-term="${term}"]`).focus();
                            return;
                        }
                        if (cmt.length < 4) {
                            ExamToast.fire({ icon: 'warning', title: 'Comment must be at least 4 characters' });
                            $(`input.exam-cmt-input[data-sid="${sid}"][data-term="${term}"]`).focus();
                            return;
                        }
                        // ─────────────────────────────────────────────────────────

                        btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

                        $.post(EXAM_SAVE_URL, {
                            student_id: sid, term, mark, total_mark: total, teacher_comment: cmt, is_absent: 0
                        }, res => {
                            btn.html('<i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>').prop('disabled', false);
                            if (res.success) {
                                applyMarkResult(res, sid, term);
                                ExamToast.fire({ icon: 'success', title: 'Mark saved successfully' });
                            } else {
                                ExamToast.fire({ icon: 'error', title: res.message });
                            }
                        }).fail(() => {
                            btn.html('<i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>').prop('disabled', false);
                            ExamToast.fire({ icon: 'error', title: 'Failed to save' });
                        });
                    });

                    // ── Absent toggle ─────────────────────────────────────────────
                    $(document).on('click', '.btn-toggle-absent', function() {
                        const btn      = $(this);
                        const sid      = btn.data('sid');
                        const term     = btn.data('term');
                        const row      = $(`#exam_row_${term}_${sid}`);
                        const wasAbsent= row.attr('data-absent') === '1';
                        const newAbsent= wasAbsent ? 0 : 1;
                        const total    = btn.data('total') || 100;
                        const markCell = $(`.exam-mark-cell-${term}-${sid}`);
                        const totalCell= $(`.exam-total-cell-${term}-${sid}`);
                        const cmtCell  = $(`.exam-cmt-cell-${term}-${sid}`);
                        const saveBtn  = $(`.btn-save-mark[data-sid="${sid}"][data-term="${term}"]`);
                        const nameCell = row.find('td:first-child');

                        btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

                        $.post(EXAM_SAVE_URL, {
                            student_id: sid, term, mark: '', total_mark: total, teacher_comment: '', is_absent: newAbsent
                        }, res => {
                            btn.prop('disabled', false);
                            if (!res.success) {
                                // Restore button label on failure
                                if (wasAbsent) {
                                    btn.html('<i class="ki-duotone ki-check fs-7"><span class="path1"></span><span class="path2"></span></i>Present');
                                } else {
                                    btn.html('<i class="ki-duotone ki-user-cross fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>ABS');
                                }
                                ExamToast.fire({ icon: 'error', title: res.message });
                                return;
                            }
                            if (newAbsent) {
                                    // Switch to absent state
                                    btn.html('<i class="ki-duotone ki-check fs-7"><span class="path1"></span><span class="path2"></span></i>Present')
                                       .removeClass('btn-outline-danger').addClass('btn-danger');
                                    markCell.html('<span class="badge badge-light-danger fs-8">ABS</span>');
                                    totalCell.html('<span class="text-muted fs-8">—</span>');
                                    cmtCell.html('');
                                    saveBtn.prop('disabled', true);
                                    nameCell.find('.badge').remove();
                                    nameCell.find('span.fw-semibold').after('<span class="badge badge-light-danger fs-9 ms-2">ABSENT</span>');
                                    ExamToast.fire({ icon: 'warning', title: 'Marked as ABSENT' });
                                } else {
                                    // Switch to present state
                                    btn.html('<i class="ki-duotone ki-user-cross fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>ABS')
                                       .removeClass('btn-danger').addClass('btn-outline-danger');
                                    markCell.html(`<input type="number" class="form-control form-control-sm text-center exam-mark-input" style="width:80px;margin:auto;" data-sid="${sid}" data-term="${term}" value="" min="0" max="${total}" step="0.5" placeholder="0" />`);
                                    totalCell.html(`<input type="number" class="form-control form-control-sm text-center exam-total-input" style="width:70px;margin:auto;" data-sid="${sid}" data-term="${term}" value="${total}" min="1" max="1000" step="0.5" />`);
                                    cmtCell.html(`<input type="text" class="form-control form-control-sm exam-cmt-input" data-sid="${sid}" data-term="${term}" value="" placeholder="Optional comment..." maxlength="500" />`);
                                    saveBtn.prop('disabled', false);
                                    nameCell.find('.badge').remove();
                                    ExamToast.fire({ icon: 'success', title: 'Marked as Present' });
                                }
                                applyMarkResult(res, sid, term);
                        }).fail(() => {
                            btn.prop('disabled', false);
                            ExamToast.fire({ icon: 'error', title: 'Failed to update' });
                        });
                    });

                    // ── Auto-cap mark input when total changes ────────────────────
                    $(document).on('change', '.exam-mark-input, .exam-total-input', function() {
                        const sid  = $(this).data('sid');
                        const term = $(this).data('term');
                        const mEl  = $(`input.exam-mark-input[data-sid="${sid}"][data-term="${term}"]`);
                        const tEl  = $(`input.exam-total-input[data-sid="${sid}"][data-term="${term}"]`);
                        const t    = parseFloat(tEl.val()) || 100;
                        mEl.attr('max', t);
                    });

                    // ── Save on Enter in mark input ───────────────────────────────
                    $(document).on('keydown', '.exam-mark-input, .exam-cmt-input', function(e) {
                        if (e.key === 'Enter') {
                            const sid  = $(this).data('sid');
                            const term = $(this).data('term');
                            $(`.btn-save-mark[data-sid="${sid}"][data-term="${term}"]`).click();
                        }
                    });
                    </script>
                    <!--end::Exams-->

                    <?php elseif ($section === 'feedback'): ?>
                    <!--begin::Feedback Analysis-->
                    <?php
                    $avgs      = $feedbackAverages ?? [];
                    $fbList    = $feedbackList     ?? [];
                    $dist      = $feedbackDist     ?? [1=>0,2=>0,3=>0,4=>0,5=>0];
                    $enrolled  = $enrolledCount    ?? 0;
                    $total     = (int)($avgs['total_responses'] ?? 0);
                    $respRate  = $enrolled > 0 ? round(($total / $enrolled) * 100) : 0;
                    $cats = [
                        'avg_overall'    => ['label'=>'Overall Experience',   'icon'=>'ki-star',         'color'=>'warning'],
                        'avg_teaching'   => ['label'=>'Teaching Quality',     'icon'=>'ki-teacher',      'color'=>'primary'],
                        'avg_content'    => ['label'=>'Course Content',       'icon'=>'ki-book-open',    'color'=>'success'],
                        'avg_engagement' => ['label'=>'Engagement & Support', 'icon'=>'ki-message-edit', 'color'=>'info'],
                    ];
                    ?>

                    <!--begin::Header-->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ki-duotone ki-message-text-2 fs-2 text-primary me-1">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <h4 class="fw-bold text-gray-800 mb-0">Feedback Analysis</h4>
                            <span class="badge badge-light-primary ms-2 fs-8"><?= $total ?> response<?= $total!==1?'s':'' ?></span>
                        </div>
                        <div class="text-muted fs-8"><?= $respRate ?>% response rate (<?= $total ?> / <?= $enrolled ?>)</div>
                    </div>
                    <!--end::Header-->

                    <?php if ($total === 0): ?>
                    <div class="text-center py-14 text-muted">
                        <i class="ki-duotone ki-message-text-2 fs-4x text-gray-200 mb-3">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        <div class="fs-6 fw-semibold mb-1">No feedback yet</div>
                        <div class="fs-8">Students haven't submitted any feedback for this subject.</div>
                    </div>
                    <?php else: ?>

                    <!--begin::Category averages-->
                    <div class="row g-4 mb-6">
                    <?php foreach ($cats as $key => $cfg):
                        $avg = (float)($avgs[$key] ?? 0);
                        $filled = round($avg);
                    ?>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm h-100 text-center p-4">
                            <i class="ki-duotone <?= $cfg['icon'] ?> fs-2x text-<?= $cfg['color'] ?> mb-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <div class="fw-bold text-gray-800 fs-2x mb-1"><?= number_format($avg,1) ?></div>
                            <div class="mb-2">
                                <?php for ($s=1;$s<=5;$s++): ?>
                                <span style="color:<?= $s<=$filled?'#fbbf24':'#e5e7eb' ?>;font-size:1rem;">★</span>
                                <?php endfor; ?>
                            </div>
                            <div class="text-muted fs-9"><?= $cfg['label'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <!--end::Category averages-->

                    <!--begin::Charts row-->
                    <div class="row g-5 mb-6">

                        <!--Rating distribution bar chart-->
                        <div class="col-md-7">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header border-0 pt-4 pb-2 px-5">
                                    <h6 class="fw-bold text-gray-700 fs-7 mb-0">Overall Rating Distribution</h6>
                                </div>
                                <div class="card-body px-3 pb-4 pt-1">
                                    <div id="fbDistChart" style="min-height:220px;"></div>
                                </div>
                            </div>
                        </div>

                        <!--Radar / category comparison-->
                        <div class="col-md-5">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header border-0 pt-4 pb-2 px-5">
                                    <h6 class="fw-bold text-gray-700 fs-7 mb-0">Category Averages</h6>
                                </div>
                                <div class="card-body px-2 pb-4 pt-1 d-flex align-items-center">
                                    <div id="fbRadarChart" style="min-height:220px;width:100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Charts row-->

                    <!--begin::Response rate progress-->
                    <div class="card border-0 shadow-sm mb-6">
                        <div class="card-body px-6 py-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-semibold text-gray-700 fs-7">Student Response Rate</span>
                                <span class="fw-bold text-gray-800 fs-7"><?= $total ?> / <?= $enrolled ?> students</span>
                            </div>
                            <div class="progress" style="height:10px;border-radius:6px;">
                                <div class="progress-bar bg-primary" style="width:<?= $respRate ?>%;border-radius:6px;"
                                     role="progressbar"></div>
                            </div>
                            <div class="text-muted fs-9 mt-1"><?= $respRate ?>% of enrolled students have submitted feedback</div>
                        </div>
                    </div>
                    <!--end::Response rate progress-->

                    <!--begin::Comments-->
                    <?php $comments = array_filter($fbList, fn($f) => !empty($f['comment'])); ?>
                    <?php if (!empty($comments)): ?>
                    <div class="mb-2">
                        <div class="fw-bold text-gray-700 fs-7 mb-4 d-flex align-items-center gap-2">
                            <i class="ki-duotone ki-message-text-2 fs-4 text-primary">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            Student Comments <span class="badge badge-light-primary fs-9 ms-1"><?= count($comments) ?></span>
                        </div>
                        <div class="d-flex flex-column gap-3">
                        <?php foreach ($comments as $fb):
                            $fbStars = (int)($fb['overall_rating'] ?? 0);
                        ?>
                        <div class="p-4 rounded-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-semibold text-gray-700 fs-8">
                                    <?= $fb['is_anonymous'] ? '<span class="text-muted fst-italic">Anonymous</span>' : esc($fb['student_name'] ?? 'Student') ?>
                                </span>
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <?php for ($s=1;$s<=5;$s++): ?>
                                        <span style="color:<?= $s<=$fbStars?'#fbbf24':'#e5e7eb' ?>;font-size:.85rem;">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-muted fs-9"><?= date('d M Y', strtotime($fb['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="text-gray-600 fs-8 lh-lg"><?= nl2br(esc($fb['comment'])) ?></div>
                        </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!--end::Comments-->

                    <script>
                    // Rating distribution bar chart
                    new ApexCharts(document.getElementById('fbDistChart'), {
                        series: [{ name:'Responses', data:[<?= implode(',', array_values($dist)) ?>] }],
                        chart: { type:'bar', height:220, toolbar:{ show:false } },
                        plotOptions: { bar:{ borderRadius:6, columnWidth:'45%', distributed:true } },
                        colors: ['#ef4444','#f97316','#f59e0b','#3b82f6','#22c55e'],
                        dataLabels: { enabled:true, style:{ fontSize:'12px', fontWeight:700 } },
                        xaxis: { categories:['1 ★','2 ★','3 ★','4 ★','5 ★'], labels:{ style:{ fontSize:'12px' } } },
                        yaxis: { tickAmount:Math.max(...[<?= implode(',', array_values($dist)) ?>]), labels:{ formatter:v=>Math.round(v) } },
                        legend: { show:false },
                        grid: { borderColor:'#f1f5f9' },
                        tooltip: { y:{ formatter:v=>v+' student'+(v!==1?'s':'') } }
                    }).render();

                    // Radar chart
                    new ApexCharts(document.getElementById('fbRadarChart'), {
                        series: [{ name:'Average', data:[
                            <?= (float)($avgs['avg_overall']??0) ?>,
                            <?= (float)($avgs['avg_teaching']??0) ?>,
                            <?= (float)($avgs['avg_content']??0) ?>,
                            <?= (float)($avgs['avg_engagement']??0) ?>
                        ]}],
                        chart: { type:'radar', height:220, toolbar:{ show:false } },
                        xaxis: { categories:['Overall','Teaching','Content','Engagement'] },
                        yaxis: { min:0, max:5, tickAmount:5 },
                        fill: { opacity:0.25 },
                        stroke: { width:2 },
                        markers: { size:4 },
                        colors: ['#3b82f6'],
                        grid: { borderColor:'#f1f5f9' },
                        tooltip: { y:{ formatter:v=>v.toFixed(1)+' / 5' } }
                    }).render();
                    </script>

                    <?php endif; ?>
                    <!--end::Feedback Analysis-->

                    <?php elseif ($section === 'discussions'): ?>
                    <?= view('app/classroom/teacher/_class_discussion', [
                        'discussions'     => $discussions     ?? [],
                        'sessionFname'    => $sessionFname    ?? 'Teacher',
                        'sessionPhotoUrl' => $sessionPhotoUrl ?? null,
                        'sessionUserId'   => $sessionUserId   ?? 0,
                        'sdPostUrl'       => base_url('classroom/' . $classId . '/discussion/post'),
                        'canPost'         => $classroomIsActive,
                    ]) ?>

                    <?php else: ?>
                    <div class="text-center py-16 text-muted">
                        <div class="fs-6 fw-semibold">Section not found.</div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <!--end::Right panel-->

    </div>

</div>
</div>

<!--begin::Add Lesson Modal-->
<div class="modal fade" id="modal_add_lesson" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-book-open fs-2 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Create New Lesson</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <input type="hidden" id="lesson_class_sub_id" value="<?= (int) $classSubId ?>">
                <input type="hidden" id="lesson_year" value="<?= (int) ($lessonYear ?? date('Y')) ?>">
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Lesson Title</label>
                    <input type="text" class="form-control form-control-sm" id="lesson_title"
                           placeholder="e.g. Unit 3 — Forces and Motion" maxlength="255" />
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7">Description <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea class="form-control form-control-sm" id="lesson_desc" rows="2"
                              placeholder="Brief overview of what students will learn..."></textarea>
                </div>
                <div class="row g-3">
                    <div class="col-4">
                        <label class="form-label fw-semibold fs-7 required">Term</label>
                        <select class="form-select form-select-sm" id="lesson_term">
                            <option value="1">Term 1</option>
                            <option value="2" <?= ($activeTerm ?? 1) == 2 ? 'selected' : '' ?>>Term 2</option>
                            <option value="3" <?= ($activeTerm ?? 1) == 3 ? 'selected' : '' ?>>Term 3</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold fs-7 required">Week</label>
                        <input type="number" class="form-control form-control-sm" id="lesson_week"
                               value="<?= (int) ($currentWeekNum ?? 1) ?>" min="1" max="20" required />
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold fs-7">Day</label>
                        <select class="form-select form-select-sm" id="lesson_day">
                            <option value="">— Any —</option>
                            <option value="1" <?= ($currentDayNum ?? 0) == 1 ? 'selected' : '' ?>>Monday</option>
                            <option value="2" <?= ($currentDayNum ?? 0) == 2 ? 'selected' : '' ?>>Tuesday</option>
                            <option value="3" <?= ($currentDayNum ?? 0) == 3 ? 'selected' : '' ?>>Wednesday</option>
                            <option value="4" <?= ($currentDayNum ?? 0) == 4 ? 'selected' : '' ?>>Thursday</option>
                            <option value="5" <?= ($currentDayNum ?? 0) == 5 ? 'selected' : '' ?>>Friday</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light rounded-2">
                    <div class="text-muted fs-9">
                        <i class="ki-duotone ki-information-5 fs-7 me-1 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        Selecting a <strong>day</strong> places this lesson on that weekday card in the schedule view.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_lesson">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Create Lesson
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Lesson Modal-->

<!--begin::Add Assignment Modal-->
<div class="modal fade" id="modal_add_assignment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-document fs-2 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Create Assignment</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Assignment Name</label>
                    <input type="text" class="form-control form-control-sm" id="add_asgn_name"
                           placeholder="e.g. Term 1 Research Paper" maxlength="255" />
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7">Assignment File <span class="text-muted fw-normal">(PDF only)</span></label>
                    <input type="file" class="form-control form-control-sm" id="add_asgn_file" accept=".pdf" />
                    <div class="text-muted fs-9 mt-1">Upload the assignment document in PDF format.</div>
                </div>
                <div class="row g-4 mb-2">
                    <div class="col-8">
                        <label class="form-label fw-semibold fs-7 required">Due Date &amp; Time</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ki-duotone ki-calendar fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <input type="text" class="form-control form-control-sm border-start-0"
                                   id="add_asgn_due" placeholder="Select or type date &amp; time" />
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold fs-7 required">Total Score</label>
                        <input type="number" class="form-control form-control-sm" id="add_asgn_total" value="100" min="1" max="1000" step="0.5" />
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_assignment">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Create Assignment
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Assignment Modal-->

<!--begin::Edit Assignment Modal-->
<div class="modal fade" id="modal_edit_assignment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-pencil fs-2 text-primary me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Edit Assignment</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <input type="hidden" id="edit_asgn_id" />
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Assignment Name</label>
                    <input type="text" class="form-control form-control-sm" id="edit_asgn_name" maxlength="255" />
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7">Replace File <span class="text-muted fw-normal">(PDF only, leave blank to keep existing)</span></label>
                    <input type="file" class="form-control form-control-sm" id="edit_asgn_file" accept=".pdf" />
                    <div class="text-muted fs-9 mt-1" id="edit_asgn_current_file"></div>
                </div>
                <div class="row g-4 mb-4">
                    <div class="col-8">
                        <label class="form-label fw-semibold fs-7 required">Due Date &amp; Time</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ki-duotone ki-calendar fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <input type="text" class="form-control form-control-sm border-start-0"
                                   id="edit_asgn_due" placeholder="Select or type date &amp; time" />
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold fs-7 required">Total Score</label>
                        <input type="number" class="form-control form-control-sm" id="edit_asgn_total" min="1" max="1000" step="0.5" />
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold fs-7">Status</label>
                    <select class="form-select form-select-sm" id="edit_asgn_status">
                        <option value="Draft">Draft</option>
                        <option value="Published">Published</option>
                        <option value="Archived">Archived</option>
                    </select>
                    <div class="text-muted fs-9 mt-1">Once Published, the assignment cannot be edited or deleted.</div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_update_assignment">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Save Changes
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Edit Assignment Modal-->

<script>
// Pre-select term when "Create First Lesson" is clicked from an empty term tab
document.querySelectorAll('[data-term]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const t = this.dataset.term;
        if (t) document.getElementById('lesson_term').value = t;
    });
});

// ── Assignment JS ──────────────────────────────────────────────
const ASGN_STORE_URL  = '<?= base_url('classroom/teacher/' . $schSubId . '/assignment/store') ?>';
const ASGN_UPDATE_BASE = '<?= base_url('classroom/teacher/' . $schSubId . '/assignment/') ?>';

// ── Flatpickr date+time pickers ───────────────────────────────
const fpCfg = {
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    time_24hr: false,
    minuteIncrement: 5,
    allowInput: true,
};
const fpAdd  = flatpickr(document.getElementById('add_asgn_due'),  fpCfg);
const fpEdit = flatpickr(document.getElementById('edit_asgn_due'), fpCfg);

// Add assignment
document.getElementById('btn_save_assignment')?.addEventListener('click', function() {
    const btn  = this;
    const name = document.getElementById('add_asgn_name').value.trim();
    const due  = document.getElementById('add_asgn_due').value;
    const file = document.getElementById('add_asgn_file').files[0];
    if (!name || !due) {
        Swal.fire({ title: 'Required fields missing', text: 'Please enter assignment name and due date.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }
    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;
    const fd = new FormData();
    fd.append('assignment_name', name);
    fd.append('assignment_due_date', due);
    fd.append('assignment_total_score', document.getElementById('add_asgn_total').value || '100');
    if (file) fd.append('assignment_file', file);
    $.ajax({
        url: ASGN_STORE_URL, type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_add_assignment')).hide();
                Swal.fire({ title: 'Assignment Created!', icon: 'success', timer: 1200, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function() {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
});

// Clear add modal on open
document.getElementById('modal_add_assignment')?.addEventListener('show.bs.modal', function() {
    document.getElementById('add_asgn_name').value = '';
    document.getElementById('add_asgn_file').value = '';
    fpAdd.clear();
});

// Open edit modal
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-edit-assignment');
    if (!btn) return;
    e.preventDefault();
    if (btn.dataset.isPublished === '1') {
        Swal.fire({ title: 'Cannot Edit', text: 'This assignment has been published and cannot be edited.', icon: 'info',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' } });
        return;
    }
    document.getElementById('edit_asgn_id').value     = btn.dataset.id;
    document.getElementById('edit_asgn_name').value   = btn.dataset.name;
    document.getElementById('edit_asgn_total').value  = btn.dataset.total || '100';
    if (btn.dataset.due) { fpEdit.setDate(btn.dataset.due, true); } else { fpEdit.clear(); }
    document.getElementById('edit_asgn_status').value = btn.dataset.status || 'Draft';
    document.getElementById('edit_asgn_file').value   = '';
    const cf = document.getElementById('edit_asgn_current_file');
    cf.textContent = btn.dataset.file ? 'Current file: ' + btn.dataset.file : 'No file uploaded.';
    new bootstrap.Modal(document.getElementById('modal_edit_assignment')).show();
});

// Save edit
document.getElementById('btn_update_assignment')?.addEventListener('click', function() {
    const btn  = this;
    const id   = document.getElementById('edit_asgn_id').value;
    const name = document.getElementById('edit_asgn_name').value.trim();
    const due  = document.getElementById('edit_asgn_due').value;
    const stat = document.getElementById('edit_asgn_status').value;
    const file = document.getElementById('edit_asgn_file').files[0];
    if (!name || !due) {
        Swal.fire({ title: 'Required fields missing', text: 'Please enter assignment name and due date.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }
    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;
    const fd = new FormData();
    fd.append('assignment_name', name);
    fd.append('assignment_due_date', due);
    fd.append('assignment_status', stat);
    fd.append('assignment_total_score', document.getElementById('edit_asgn_total').value || '100');
    if (file) fd.append('assignment_file', file);
    $.ajax({
        url: ASGN_UPDATE_BASE + id + '/update', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_edit_assignment')).hide();
                Swal.fire({ title: 'Assignment Updated!', icon: 'success', timer: 1200, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function() {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
});

// Delete assignment
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-del-assignment');
    if (!btn) return;
    e.preventDefault();
    if (btn.dataset.isPublished === '1') {
        Swal.fire({ title: 'Cannot Delete', text: 'Published assignments cannot be deleted.', icon: 'info',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' } });
        return;
    }
    Swal.fire({
        title: 'Delete Assignment?',
        text: '"' + btn.dataset.name + '" will be permanently removed.',
        icon: 'warning', showCancelButton: true,
        buttonsStyling: false, confirmButtonText: 'Yes, Delete',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: ASGN_UPDATE_BASE + btn.dataset.id + '/delete', type: 'POST',
            success: function(res) {
                if (res.success) {
                    const card = document.getElementById('asgn_card_' + btn.dataset.id);
                    if (card) card.remove();
                    Swal.fire({ title: 'Deleted!', icon: 'success', timer: 1000, showConfirmButton: false });
                } else {
                    Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                        buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
                }
            }
        });
    });
});
// ── End Assignment JS ──────────────────────────────────────────

document.getElementById('btn_save_lesson')?.addEventListener('click', function() {
    const btn   = this;
    const subId = document.getElementById('lesson_class_sub_id').value;
    const title = document.getElementById('lesson_title').value.trim();
    const desc  = document.getElementById('lesson_desc').value.trim();
    const term  = document.getElementById('lesson_term').value;
    const week  = document.getElementById('lesson_week').value;
    const day   = document.getElementById('lesson_day').value;
    const year  = document.getElementById('lesson_year').value;

    if (!title || !week) {
        Swal.fire({ title: 'Required fields missing', text: 'Please enter a lesson title and week number.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;

    const fd = new FormData();
    fd.append('class_sub_id', subId);
    fd.append('lesson_title', title);
    fd.append('lesson_desc',  desc);
    fd.append('lesson_term',  term);
    fd.append('lesson_week',  week);
    fd.append('lesson_day',   day);
    fd.append('lesson_year',  year);

    $.ajax({
        url:         '<?= base_url('classroom/teacher/' . $schSubId . '/lesson/store') ?>',
        type:        'POST',
        data:        fd,
        processData: false,
        contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_add_lesson')).hide();
                Swal.fire({ title: 'Lesson Created!', text: 'Redirecting to lesson editor...', icon: 'success',
                    timer: 1200, showConfirmButton: false })
                    .then(() => window.location.href = res.redirect);
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function() {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An error occurred.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
        }
    });
});

// ── Day panel toggle (expand lesson list below day cards row) ──────────────
function navuliToggleDayPanel(panelId, cardEl) {
    const panel   = document.getElementById(panelId);
    if (!panel) return;
    const weekEl  = panel.closest('[id^="week_"]');
    const isOpen  = !panel.classList.contains('d-none');

    // Collapse all panels + reset all cards in this week
    weekEl?.querySelectorAll('.navuli-day-panel').forEach(p => p.classList.add('d-none'));
    weekEl?.querySelectorAll('[onclick*="navuliToggleDayPanel"]').forEach(c => {
        c.classList.remove('border-primary', 'bg-light-primary');
        const chev = c.querySelector('.day-chevron');
        if (chev) chev.style.transform = '';
    });

    if (!isOpen) {
        panel.classList.remove('d-none');
        if (cardEl) {
            cardEl.classList.add('border-primary', 'bg-light-primary');
            const chev = cardEl.querySelector('.day-chevron');
            if (chev) chev.style.transform = 'rotate(180deg)';
        }
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// ── Pre-fill modal from day card "+" button ────────────────────────────────
function navuliOpenLessonModal(term, week, day, event) {
    if (event) event.stopPropagation();
    const t = document.getElementById('lesson_term');
    const w = document.getElementById('lesson_week');
    const d = document.getElementById('lesson_day');
    if (t) t.value = term;
    if (w) w.value = week;
    if (d) d.value = day;
}

// ── Year selector navigation ───────────────────────────────────────────────
document.getElementById('lesson_year_select')?.addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('year', this.value);
    window.location.href = url.toString();
});
</script>

<style>
.nav-pills-custom .nav-link:not(.active):hover {
    background: #f5f8ff;
    border-radius: 0.475rem;
    color: var(--bs-primary) !important;
}
.nav-pills-custom .nav-link:not(.active):hover i { color: var(--bs-primary) !important; }
.quick-link-card:hover { background: #f5f8ff; border-color: var(--bs-primary) !important; }
.user-link { transition: background .15s; }
.user-link:hover { background: #f0f4ff; }
.user-link:hover .user-link-name { color: var(--bs-primary) !important; }
.user-link:hover img.rounded-circle { opacity: .85; }
</style>

