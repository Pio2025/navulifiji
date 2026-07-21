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
                    <a href="<?= base_url($backUrl ?? 'classroom/my') ?>" class="text-muted text-hover-primary">
                        <?= ($isParentView ?? false) ? "Children's Classrooms" : 'My Classroom' ?>
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($subjectData['subject_name'] ?? 'Subject') ?></li>
            </ul>
        </div>
        <button type="button" onclick="history.back()" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back
        </button>
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
        ['label' => 'Dashboard',   'icon' => 'ki-element-11',     'slug' => 'dashboard', 'badge' => 0],
        ['label' => 'Lessons',     'icon' => 'ki-book-open',      'slug' => 'lessons',     'badge' => (int) ($unreadLessons ?? 0)],
        ['label' => 'Assignments', 'icon' => 'ki-document',       'slug' => 'assignments', 'badge' => (int) ($unreadAssignments ?? 0)],
        ['label' => 'Feedback',    'icon' => 'ki-message-text-2', 'slug' => 'feedback', 'badge' => 0],
    ];

    $navBadgeText = fn(int $n) => $n > 9 ? '9+' : (string) $n;
    ?>

    <div class="row g-6">

        <!--begin::Left panel-->
        <div class="col-md-3">

            <!--begin::Subject card-->
            <div class="card border-0 shadow-sm mb-5" style="border-radius:.75rem;overflow:hidden;">
                <div class="p-5 bg-light-success">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="symbol symbol-40px">
                            <?php if (!empty($subjectData['sub_image'])): ?>
                            <img src="<?= base_url('uploads/subjects/' . $subjectData['sub_image']) ?>"
                                 class="rounded" style="width:40px;height:40px;object-fit:cover;" />
                            <?php else: ?>
                            <div class="symbol-label bg-success">
                                <i class="ki-duotone ki-book fs-3 text-white">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <span class="badge badge-light-success fs-9">Active</span>
                    </div>
                    <div class="fw-bold text-gray-900 fs-6 lh-1"><?= esc($subjectData['subject_name'] ?? '—') ?></div>
                    <div class="text-muted fs-8 mt-1"><?= esc($subjectData['level_name'] ?? '—') ?></div>
                    <div class="text-muted fs-9"><?= esc($subjectData['dept_name'] ?? '') ?></div>
                </div>
                <div class="px-5 py-4">
                    <!--begin::Class Teacher-->
                    <?php
                        $ctParts = explode(' ', $cls['class_teacher'] ?? '', 2);
                        $ctFname = $ctParts[0] ?? ''; $ctLname = $ctParts[1] ?? '';
                        $ctClick = !empty($cls['class_teacher_id'])
                            ? 'openChatForUser(' . $cls['class_teacher_id'] . ',' . json_encode($ctFname) . ',' . json_encode($ctLname) . ',' . json_encode($cls['class_teacher_photo'] ?? '') . ')'
                            : '';
                    ?>
                    <div class="user-link d-flex align-items-center gap-3 mb-3 rounded-2 p-1 mx-n1 <?= $ctClick ? 'cursor-pointer' : '' ?>"
                         <?= $ctClick ? 'onclick="' . $ctClick . '"' : '' ?>>
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
                            <div class="fw-bold user-link-name text-gray-800 fs-8 text-truncate">
                                <?= $cls['class_teacher'] ? esc($cls['class_teacher']) : '<span class="text-muted fst-italic fw-normal">Not assigned</span>' ?>
                            </div>
                        </div>
                    </div>
                    <!--end::Class Teacher-->
                    <!--begin::Class Captain-->
                    <?php
                        $ccParts = explode(' ', $cls['class_captain'] ?? '', 2);
                        $ccFname = $ccParts[0] ?? ''; $ccLname = $ccParts[1] ?? '';
                        $ccClick = !empty($cls['class_captain_id'])
                            ? 'openChatForUser(' . $cls['class_captain_id'] . ',' . json_encode($ccFname) . ',' . json_encode($ccLname) . ',' . json_encode($cls['class_captain_photo'] ?? '') . ')'
                            : '';
                    ?>
                    <div class="user-link d-flex align-items-center gap-3 mb-3 rounded-2 p-1 mx-n1 <?= $ccClick ? 'cursor-pointer' : '' ?>"
                         <?= $ccClick ? 'onclick="' . $ccClick . '"' : '' ?>>
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
                            <div class="text-muted fs-9 lh-1 mb-1">Class Captain</div>
                            <div class="fw-bold user-link-name text-gray-800 fs-8 text-truncate">
                                <?= $cls['class_captain'] ? esc($cls['class_captain']) : '<span class="text-muted fst-italic fw-normal">Not assigned</span>' ?>
                            </div>
                        </div>
                    </div>
                    <!--end::Class Captain-->
                    <div class="d-flex align-items-center gap-2 pt-3" style="border-top:1px solid #f1f1f4;">
                        <i class="ki-duotone ki-people fs-5 text-info">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                        <span class="fw-bold text-gray-800 fs-7"><?= (int) $cls['student_count'] ?></span>
                        <span class="text-muted fs-8">student<?= (int) $cls['student_count'] !== 1 ? 's' : '' ?> in class</span>
                    </div>
                </div>
            </div>
            <!--end::Subject card-->

            <!--begin::Navigation card-->
            <div class="card border-0 shadow-sm">
                <div class="card-body py-4 px-0">
                    <ul class="nav nav-pills nav-pills-custom flex-column border-transparent fs-5 fw-bold">
                        <?php foreach ($navLinks as $nav):
                            $isActive = $section === $nav['slug'];
                        ?>
                        <li class="nav-item mt-2">
                            <a class="nav-link text-muted text-active-primary ms-0 py-0 me-10 ps-9 border-0 d-flex align-items-center <?= $isActive ? 'active' : '' ?>"
                               href="<?= base_url('classroom/student/' . $classSubId . '/' . $nav['slug']) ?>">
                                <i class="ki-duotone <?= $nav['icon'] ?> fs-3 text-muted me-3">
                                    <span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span>
                                </i><?= $nav['label'] ?>
                                <?php if ($nav['badge'] > 0): ?>
                                <span class="badge badge-circle badge-danger fs-9 ms-auto"><?= $navBadgeText($nav['badge']) ?></span>
                                <?php endif; ?>
                                <span class="bullet-custom position-absolute start-0 top-0 w-3px h-100 bg-primary rounded-end"></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <!--end::Navigation card-->

            <!--begin::Classmates card-->
            <div class="card border-0 shadow-sm mt-5">
                <div class="card-header border-0 pt-4 pb-2 px-5 min-h-auto">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ki-duotone ki-people fs-4 text-info">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                        <span class="fw-bold text-gray-800 fs-7">Subject Classmates</span>
                        <span class="badge badge-light-info fs-9 ms-1"><?= count($classmates ?? []) ?></span>
                    </div>
                </div>
                <div class="card-body px-5 pt-3 pb-4">
                <?php if (empty($classmates)): ?>
                    <div class="text-center py-4 text-muted fs-8">No classmates found.</div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-1" style="max-height:490px;overflow-y:auto;overflow-x:hidden;padding-right:2px;">
                    <?php foreach ($classmates as $cm):
                        $initials = strtoupper(substr($cm['fname'], 0, 1) . substr($cm['lname'], 0, 1));
                    ?>
                    <div class="d-flex align-items-center gap-3 rounded-2 px-2 py-2 mx-n2">
                        <div class="symbol symbol-32px flex-shrink-0">
                            <?php if (!empty($cm['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $cm['profile_photo']) ?>"
                                 class="rounded-circle" style="width:32px;height:32px;object-fit:cover;" alt="">
                            <?php else: ?>
                            <div class="symbol-label bg-light-primary fw-bold text-primary fs-9"><?= $initials ?></div>
                            <?php endif; ?>
                        </div>
                        <span class="text-gray-700 fw-semibold fs-8 text-truncate"><?= esc($cm['fname'] . ' ' . $cm['lname']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
            </div>
            <!--end::Classmates card-->

        </div>
        <!--end::Left panel-->

        <!--begin::Right panel-->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-6">

                    <?php if ($section === 'dashboard'): ?>
                    <!--begin::Dashboard-->
                    <?php
                    $st = $dashStats ?? [];
                    $publishedForDash = array_values(array_filter($lessons ?? [], fn($l) => $l['lesson_status'] === 'Published'));
                    usort($publishedForDash, fn($a, $b) => strtotime($b['created_at'] ?? '0') <=> strtotime($a['created_at'] ?? '0'));
                    $recentLessons = array_slice($publishedForDash, 0, 4);
                    $totalResources = (int)($st['files'] ?? 0) + (int)($st['videos'] ?? 0) + (int)($st['links'] ?? 0);
                    ?>

                    <!--begin::Stat cards-->
                    <div class="row g-4 mb-7">
                        <!--Classmates-->
                        <div class="col-6 col-md-3">
                            <div class="card border-0 h-100" style="background:#eef2ff;">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="symbol symbol-40px">
                                            <div class="symbol-label" style="background:#c7d2fe;">
                                                <i class="ki-duotone ki-people fs-3 text-primary">
                                                    <span class="path1"></span><span class="path2"></span>
                                                    <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                                </i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-gray-900 fs-2x lh-1 mb-1"><?= (int)($st['students'] ?? 0) ?></div>
                                    <div class="text-muted fs-8 fw-semibold">Classmates</div>
                                </div>
                            </div>
                        </div>
                        <!--Lessons-->
                        <div class="col-6 col-md-3">
                            <div class="card border-0 h-100" style="background:#f0fdf4;">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="symbol symbol-40px">
                                            <div class="symbol-label" style="background:#bbf7d0;">
                                                <i class="ki-duotone ki-book-open fs-3 text-success">
                                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                                </i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-gray-900 fs-2x lh-1 mb-1"><?= (int)($st['lessons'] ?? 0) ?></div>
                                    <div class="text-muted fs-8 fw-semibold">Published Lessons</div>
                                </div>
                            </div>
                        </div>
                        <!--Assignments-->
                        <div class="col-6 col-md-3">
                            <div class="card border-0 h-100" style="background:#fff7ed;">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="symbol symbol-40px">
                                            <div class="symbol-label" style="background:#fed7aa;">
                                                <i class="ki-duotone ki-document fs-3 text-warning">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-gray-900 fs-2x lh-1 mb-1"><?= (int)($st['assignments'] ?? 0) ?></div>
                                    <div class="text-muted fs-8 fw-semibold">Assignments</div>
                                </div>
                            </div>
                        </div>
                        <!--Resources-->
                        <div class="col-6 col-md-3">
                            <div class="card border-0 h-100" style="background:#fdf4ff;">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="symbol symbol-40px">
                                            <div class="symbol-label" style="background:#e9d5ff;">
                                                <i class="ki-duotone ki-folder fs-3 text-purple" style="color:#7c3aed;">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-gray-900 fs-2x lh-1 mb-1"><?= $totalResources ?></div>
                                    <div class="text-muted fs-8 fw-semibold">Total Resources</div>
                                    <?php if ($totalResources > 0): ?>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <?php if ((int)($st['files'] ?? 0) > 0): ?>
                                        <span class="badge badge-light-primary fs-9"><?= $st['files'] ?> file<?= $st['files'] != 1 ? 's' : '' ?></span>
                                        <?php endif; ?>
                                        <?php if ((int)($st['videos'] ?? 0) > 0): ?>
                                        <span class="badge badge-light-danger fs-9"><?= $st['videos'] ?> video<?= $st['videos'] != 1 ? 's' : '' ?></span>
                                        <?php endif; ?>
                                        <?php if ((int)($st['links'] ?? 0) > 0): ?>
                                        <span class="badge badge-light-info fs-9"><?= $st['links'] ?> link<?= $st['links'] != 1 ? 's' : '' ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Stat cards-->

                    <!--begin::Quick Access-->
                    <div class="d-flex align-items-center mb-4">
                        <span class="fw-bold text-gray-700 fs-7">Quick Access</span>
                    </div>
                    <div class="row g-3 mb-7">
                        <?php
                        $quickLinks = [
                            ['label' => 'Lessons',     'icon' => 'ki-book-open',      'slug' => 'lessons',     'color' => 'primary'],
                            ['label' => 'Assignments', 'icon' => 'ki-document',        'slug' => 'assignments', 'color' => 'success'],
                            ['label' => 'Feedback',    'icon' => 'ki-message-text-2',  'slug' => 'feedback',    'color' => 'info'],
                        ];
                        foreach ($quickLinks as $ql):
                        ?>
                        <div class="col-6 col-md-4">
                            <a href="<?= base_url('classroom/student/' . $classSubId . '/' . $ql['slug']) ?>"
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
                    <!--end::Quick Access-->

                    <!--begin::Assessment Analysis-->
                    <?php
                    $as = $assessmentStats ?? ['assignments'=>[],'quizzes'=>[],'avg_assignment'=>null,'avg_quiz'=>null,'overall_avg'=>null];
                    $hasAssignmentData = !empty(array_filter($as['assignments'], fn($a) => $a['student_pct'] !== null || $a['class_avg_pct'] !== null));
                    $hasQuizData       = !empty(array_filter($as['quizzes'],     fn($q) => $q['student_score'] !== null || $q['class_avg_score'] !== null));
                    $hasAnyData        = $hasAssignmentData || $hasQuizData;
                    ?>
                    <?php if ($hasAnyData): ?>
                    <div class="mb-7">
                        <div class="d-flex align-items-center gap-2 mb-5">
                            <i class="ki-duotone ki-chart-simple-3 fs-3 text-primary">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span><span class="path4"></span>
                            </i>
                            <span class="fw-bold text-gray-700 fs-7">Assessment Analysis</span>
                        </div>
                        <div class="row g-5 align-items-start">
                            <div class="col-md-3">
                                <div class="card border-0 h-100" style="background:#f8fafc;">
                                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                                        <div id="chart_overall_ring" style="min-height:160px;"></div>
                                        <div class="fw-bold text-gray-800 fs-6 mt-2">Overall Average</div>
                                        <?php if ($as['avg_assignment'] !== null): ?>
                                        <div class="text-muted fs-9 mt-1">
                                            Assignments: <span class="fw-bold text-primary"><?= $as['avg_assignment'] ?>%</span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($as['avg_quiz'] !== null): ?>
                                        <div class="text-muted fs-9">
                                            Quizzes: <span class="fw-bold text-info"><?= $as['avg_quiz'] ?>%</span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <?php if ($hasAssignmentData): ?>
                                <div class="mb-5">
                                    <div class="text-muted fs-8 fw-semibold mb-3">
                                        <i class="ki-duotone ki-document fs-6 me-1 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                        Assignment Scores vs Class Average
                                    </div>
                                    <div id="chart_assignments" style="min-height:200px;"></div>
                                </div>
                                <?php endif; ?>
                                <?php if ($hasQuizData): ?>
                                <div>
                                    <div class="text-muted fs-8 fw-semibold mb-3">
                                        <i class="ki-duotone ki-questionnaire-tablet fs-6 me-1 text-info"><span class="path1"></span><span class="path2"></span></i>
                                        Quiz Scores vs Class Average
                                    </div>
                                    <div id="chart_quizzes" style="min-height:200px;"></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <script>
                    (function () {
                        const PRIMARY = '#3b82f6', INFO = '#06b6d4', GRAY = '#e5e7eb';
                        const FONT    = { fontFamily: 'inherit', fontSize: '11px' };
                        function gradeColor(pct) {
                            if (pct >= 80) return '#22c55e';
                            if (pct >= 65) return '#3b82f6';
                            if (pct >= 50) return '#f59e0b';
                            if (pct >= 40) return '#f97316';
                            return '#ef4444';
                        }
                        const overallVal = <?= $as['overall_avg'] !== null ? $as['overall_avg'] : 0 ?>;
                        new ApexCharts(document.getElementById('chart_overall_ring'), {
                            chart: { type: 'radialBar', height: 160, sparkline: { enabled: true } },
                            series: [overallVal],
                            plotOptions: { radialBar: { hollow: { size: '55%' }, dataLabels: { name: { show: false }, value: { fontSize: '20px', fontWeight: 700, fontFamily: 'inherit', formatter: v => v + '%', color: gradeColor(overallVal) } }, track: { background: GRAY } } },
                            fill: { colors: [gradeColor(overallVal)] },
                            labels: [<?= $as['overall_avg'] === null ? "'No data yet'" : "'Average'" ?>],
                        }).render();
                        <?php if ($hasAssignmentData): ?>
                        const aLabels  = <?= json_encode(array_map(fn($a) => strlen($a['assignment_name']) > 20 ? substr($a['assignment_name'],0,18).'…' : $a['assignment_name'], $as['assignments'])) ?>;
                        const aStudent = <?= json_encode(array_map(fn($a) => $a['student_pct'] !== null ? (float)$a['student_pct'] : null, $as['assignments'])) ?>;
                        const aClass   = <?= json_encode(array_map(fn($a) => $a['class_avg_pct'] !== null ? (float)$a['class_avg_pct'] : null, $as['assignments'])) ?>;
                        new ApexCharts(document.getElementById('chart_assignments'), {
                            chart: { type: 'bar', height: 200, toolbar: { show: false } },
                            series: [{ name: 'Your Score %', data: aStudent }, { name: 'Class Average %', data: aClass }],
                            xaxis: { categories: aLabels, labels: { style: FONT, rotate: -20 } },
                            yaxis: { min: 0, max: 100, labels: { style: FONT, formatter: v => v + '%' } },
                            colors: [PRIMARY, '#94a3b8'],
                            plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
                            dataLabels: { enabled: false },
                            legend: { fontSize: '11px', fontFamily: 'inherit' },
                            tooltip: { y: { formatter: v => v !== null ? v + '%' : 'Not graded' } },
                            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                        }).render();
                        <?php endif; ?>
                        <?php if ($hasQuizData): ?>
                        const qLabels  = <?= json_encode(array_map(fn($q) => strlen($q['quizze_name']) > 20 ? substr($q['quizze_name'],0,18).'…' : $q['quizze_name'], $as['quizzes'])) ?>;
                        const qStudent = <?= json_encode(array_map(fn($q) => $q['student_score'] !== null ? (float)$q['student_score'] : null, $as['quizzes'])) ?>;
                        const qClass   = <?= json_encode(array_map(fn($q) => $q['class_avg_score'] !== null ? (float)$q['class_avg_score'] : null, $as['quizzes'])) ?>;
                        new ApexCharts(document.getElementById('chart_quizzes'), {
                            chart: { type: 'bar', height: 200, toolbar: { show: false } },
                            series: [{ name: 'Your Score %', data: qStudent }, { name: 'Class Average %', data: qClass }],
                            xaxis: { categories: qLabels, labels: { style: FONT, rotate: -20 } },
                            yaxis: { min: 0, max: 100, labels: { style: FONT, formatter: v => v + '%' } },
                            colors: [INFO, '#94a3b8'],
                            plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
                            dataLabels: { enabled: false },
                            legend: { fontSize: '11px', fontFamily: 'inherit' },
                            tooltip: { y: { formatter: v => v !== null ? v + '%' : 'Not attempted' } },
                            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                        }).render();
                        <?php endif; ?>
                    })();
                    </script>
                    <?php endif; // hasAnyData ?>
                    <!--end::Assessment Analysis-->

                    <!--begin::Recent Lessons-->
                    <?php if (!empty($recentLessons)): ?>
                    <div class="mt-7">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="fw-bold text-gray-700 fs-7">Recent Lessons</span>
                            <a href="<?= base_url('classroom/student/' . $classSubId . '/lessons') ?>"
                               class="text-primary fs-8 fw-semibold text-hover-primary">View all</a>
                        </div>
                        <div class="d-flex flex-column gap-3">
                        <?php foreach ($recentLessons as $rl): ?>
                        <a href="<?= base_url('classroom/student/' . $classSubId . '/lesson/' . $rl['lesson_id']) ?>"
                           class="text-decoration-none">
                            <div class="d-flex align-items-center gap-4 p-4 rounded-2 border border-dashed border-gray-200 quick-link-card">
                                <div class="symbol symbol-40px flex-shrink-0">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-book-open fs-4 text-primary">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-bold text-gray-800 fs-7 text-truncate"><?= esc($rl['lesson_title']) ?></div>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="text-muted fs-9">Term <?= $rl['lesson_term'] ?> · Week <?= $rl['lesson_week'] ?></span>
                                        <?php if ((int)$rl['file_count'] > 0): ?>
                                        <span class="badge badge-light-primary fs-9"><?= (int)$rl['file_count'] ?> file<?= (int)$rl['file_count'] != 1 ? 's' : '' ?></span>
                                        <?php endif; ?>
                                        <?php if ((int)$rl['video_count'] > 0): ?>
                                        <span class="badge badge-light-danger fs-9"><?= (int)$rl['video_count'] ?> video<?= (int)$rl['video_count'] != 1 ? 's' : '' ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <i class="ki-duotone ki-arrow-right fs-5 text-muted flex-shrink-0">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!--end::Recent Lessons-->
                    <!--end::Dashboard-->

                    <?php elseif ($section === 'lessons'): ?>
                    <!--begin::Lessons-->
                    <?php
                    $termSchedule   = $termSchedule   ?? [];
                    $holidays       = $holidays        ?? [];
                    $activeTerm     = $activeTerm      ?? 1;
                    $lessonYear     = $lessonYear      ?? (int) date('Y');
                    $availableYears = $availableYears  ?? [];
                    $currentWeekNum = $currentWeekNum  ?? 1;
                    $todayStr       = date('Y-m-d');
                    $todayDow       = min(5, max(1, (int) date('N')));

                    // Only Published lessons, bucketed by term/week/day
                    $lessonMap  = [];
                    $termCounts = [1 => 0, 2 => 0, 3 => 0];
                    foreach (($lessons ?? []) as $l) {
                        if ($l['lesson_status'] !== 'Published') continue;
                        $t = (int) $l['lesson_term'];
                        $w = (int) ($l['lesson_week'] ?? 0);
                        $d = (int) ($l['lesson_day']  ?? 0);
                        if ($t >= 1 && $t <= 3) { $lessonMap[$t][$w][$d][] = $l; $termCounts[$t]++; }
                    }
                    $dayShort = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri'];
                    $dayLong  = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday'];
                    $totalPublished = array_sum($termCounts);
                    ?>

                    <!--begin::Header row-->
                    <div class="d-flex align-items-center justify-content-between mb-6 flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ki-duotone ki-book-open fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <h4 class="fw-bold text-gray-800 mb-0">Lessons</h4>
                            <span class="badge badge-light-primary ms-1 fs-8"><?= $totalPublished ?></span>
                        </div>
                        <?php if (count($availableYears) > 1): ?>
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-semibold fs-8 text-muted mb-0">Year:</label>
                            <select id="student_lesson_year_select" class="form-select form-select-sm w-auto fs-8">
                                <?php foreach ($availableYears as $yr): ?>
                                <option value="<?= $yr ?>" <?= (int)$yr === $lessonYear ? 'selected' : '' ?>><?= $yr ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!--end::Header row-->

                    <?php if (empty($termSchedule)): ?>
                    <div class="text-center py-16 text-muted">
                        <i class="ki-duotone ki-calendar fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fs-6 fw-semibold">School calendar not configured yet.</div>
                    </div>
                    <?php else: ?>

                    <!--begin::Term tabs-->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-semibold mb-6">
                        <?php foreach ([1, 2, 3] as $t): if (!isset($termSchedule[$t])) continue; ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $t === $activeTerm ? 'active' : '' ?> text-active-primary pb-4"
                               data-bs-toggle="tab" data-bs-target="#stud_lessons_term_<?= $t ?>">
                                Term <?= $t ?>
                                <span class="badge badge-light ms-1 fs-9"><?= $termCounts[$t] ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tab-content">
                    <?php foreach ([1, 2, 3] as $t):
                        if (!isset($termSchedule[$t])) continue;
                    ?>
                    <div class="tab-pane fade <?= $t === $activeTerm ? 'show active' : '' ?>" id="stud_lessons_term_<?= $t ?>">
                        <?php foreach ($termSchedule[$t] as $wNum => $week):
                            $isCurrentWeek = $week['is_current_week'];
                            $wLessonCount  = 0;
                            for ($d = 1; $d <= 5; $d++) $wLessonCount += count($lessonMap[$t][$wNum][$d] ?? []);
                            $wLessonCount += count($lessonMap[$t][$wNum][0] ?? []); // unscheduled
                        ?>
                        <!--begin::Week block-->
                        <div class="mb-8">
                            <!--begin::Week header-->
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <span class="badge <?= $isCurrentWeek ? 'badge-primary' : 'badge-light-primary' ?> fs-8 fw-bold px-3 py-2">
                                    Week <?= $wNum ?>
                                </span>
                                <?php if ($isCurrentWeek): ?><span class="badge badge-light-success fs-9">Current Week</span><?php endif; ?>
                                <div class="text-muted fs-9"><?= date('M j', strtotime($week['start_date'])) ?> – <?= date('M j', strtotime($week['end_date'])) ?></div>
                                <div class="flex-grow-1" style="height:1px;background:#f1f1f4;"></div>
                                <span class="text-muted fs-9"><?= $wLessonCount ?> lesson<?= $wLessonCount !== 1 ? 's' : '' ?></span>
                            </div>
                            <!--end::Week header-->

                            <!--begin::Day cards row-->
                            <div class="d-flex gap-2 mb-3" style="overflow-x:auto;padding-bottom:2px;">
                            <?php for ($d = 1; $d <= 5; $d++):
                                $dayDate     = $week['days'][$d];
                                $dayLessons  = $lessonMap[$t][$wNum][$d] ?? [];
                                $lCount      = count($dayLessons);
                                $fCount      = array_sum(array_column($dayLessons, 'file_count'));
                                $vCount      = array_sum(array_column($dayLessons, 'video_count'));
                                $lkCount     = array_sum(array_column($dayLessons, 'link_count'));
                                $isToday     = ($dayDate === $todayStr);
                                $isPast      = ($dayDate < $todayStr);
                                $panelId     = "stud_panel_t{$t}_w{$wNum}_d{$d}";
                                $isHoliday   = isset($holidays[$dayDate]);
                                $holidayName = $holidays[$dayDate]['name'] ?? null;
                                $isObserved  = $holidays[$dayDate]['is_observed'] ?? false;
                            ?>
                            <div class="flex-fill" style="min-width:110px;max-width:190px;">
                                <?php if ($isHoliday && $lCount === 0): ?>
                                <!--Holiday, no lessons-->
                                <div class="card h-100 text-center bg-light-danger"
                                     style="border-radius:.75rem;border:1px dashed #f1416c!important;"
                                     title="Public Holiday: <?= esc($holidayName) ?>">
                                    <div class="card-body p-3">
                                        <div class="fw-bold fs-8 text-danger"><?= $dayShort[$d] ?></div>
                                        <div class="text-danger fs-9 mb-1"><?= date('M j', strtotime($dayDate)) ?></div>
                                        <?php if ($isToday): ?><div class="mb-1"><span class="badge badge-danger rounded-pill px-2 py-1" style="font-size:9px;">Today</span></div><?php endif; ?>
                                        <div class="mb-1"><i class="ki-duotone ki-flag fs-6 text-danger"><span class="path1"></span><span class="path2"></span></i></div>
                                        <div class="text-danger fw-semibold lh-sm" style="font-size:9px;word-break:break-word;"><?= esc($holidayName) ?></div>
                                        <?php if ($isObserved): ?><div class="mt-1"><span class="badge badge-light-warning" style="font-size:8px;">Observed</span></div><?php endif; ?>
                                    </div>
                                </div>

                                <?php elseif ($lCount > 0): ?>
                                <!--Has lessons-->
                                <div class="card h-100 text-center cursor-pointer <?= $isHoliday ? 'bg-light-danger' : ($isToday ? 'border-primary bg-light-primary' : 'border border-dashed border-gray-300') ?>"
                                     style="border-radius:.75rem;transition:box-shadow .15s;<?= $isHoliday ? 'border:1px dashed #f1416c!important;' : '' ?>"
                                     onclick="studToggleDayPanel('<?= $panelId ?>', this)"
                                     title="<?= $isHoliday ? 'Public Holiday: ' . esc($holidayName) . ' — ' : '' ?><?= $dayLong[$d] ?> <?= date('M j', strtotime($dayDate)) ?>">
                                    <div class="card-body p-3">
                                        <div class="fw-bold fs-8 <?= $isHoliday ? 'text-danger' : ($isToday ? 'text-primary' : 'text-gray-700') ?>"><?= $dayShort[$d] ?></div>
                                        <div class="<?= $isHoliday ? 'text-danger' : ($isPast && !$isToday ? 'text-muted' : 'text-gray-600') ?> fs-9 mb-1"><?= date('M j', strtotime($dayDate)) ?></div>
                                        <?php if ($isToday && !$isHoliday): ?><div class="mb-1"><span class="badge badge-primary rounded-pill px-2 py-1" style="font-size:9px;">Today</span></div><?php endif; ?>
                                        <?php if ($isHoliday): ?>
                                        <div class="mb-1"><i class="ki-duotone ki-flag fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i></div>
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
                                        <div class="mt-2"><i class="ki-duotone ki-down fs-8 text-muted stud-day-chevron" style="transition:transform .2s;"><span class="path1"></span><span class="path2"></span></i></div>
                                    </div>
                                </div>

                                <?php else: ?>
                                <!--Empty day-->
                                <div class="card h-100 text-center border border-dashed <?= $isPast ? 'bg-light' : '' ?>"
                                     style="border-radius:.75rem;border-color:#e4e6ea!important;">
                                    <div class="card-body p-3">
                                        <div class="fw-semibold fs-8 text-gray-400"><?= $dayShort[$d] ?></div>
                                        <div class="text-muted fs-9 mb-2"><?= date('M j', strtotime($dayDate)) ?></div>
                                        <?php if ($isToday): ?><div class="mb-1"><span class="badge badge-primary rounded-pill px-2 py-1" style="font-size:9px;">Today</span></div><?php endif; ?>
                                        <div class="text-gray-300" style="font-size:11px;">—</div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endfor; ?>
                            </div>
                            <!--end::Day cards row-->

                            <!--begin::Day lesson panels-->
                            <?php for ($d = 1; $d <= 5; $d++):
                                $dayLessons   = $lessonMap[$t][$wNum][$d] ?? [];
                                if (empty($dayLessons)) continue;
                                $panelDayDate = $week['days'][$d];
                                $_ph          = $holidays[$panelDayDate] ?? null;
                                $panelHoliday = $_ph['name'] ?? null;
                                $panelObsrvd  = $_ph['is_observed'] ?? false;
                                $autoOpen     = ($isCurrentWeek && $d === $todayDow);
                            ?>
                            <div id="stud_panel_t<?= $t ?>_w<?= $wNum ?>_d<?= $d ?>"
                                 class="navuli-stud-day-panel mb-3<?= $autoOpen ? '' : ' d-none' ?>">
                                <div class="card border-0 shadow-xs"
                                     style="background:<?= $panelHoliday ? '#fff5f8' : '#f8f9fc' ?>;border-radius:.75rem;<?= $panelHoliday ? 'border:1px dashed #f1416c!important;' : '' ?>">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                            <div class="fw-bold fs-7 <?= $panelHoliday ? 'text-danger' : 'text-gray-700' ?>">
                                                <i class="ki-duotone <?= $panelHoliday ? 'ki-flag text-danger' : 'ki-calendar text-primary' ?> fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                                                <?= $dayLong[$d] ?>, <?= date('M j, Y', strtotime($panelDayDate)) ?>
                                                <?php if ($panelHoliday): ?>
                                                <span class="badge badge-light-danger ms-2 fw-normal fs-9"><?= esc($panelHoliday) ?></span>
                                                <?php if ($panelObsrvd): ?><span class="badge badge-light-warning ms-1 fw-normal fs-9">Observed</span><?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-2">
                                        <?php foreach ($dayLessons as $lesson):
                                            $lu = base_url("classroom/student/{$classSubId}/lesson/{$lesson['lesson_id']}");
                                        ?>
                                        <a href="<?= $lu ?>" class="text-decoration-none">
                                            <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-2 hover-elevate-up">
                                                <div class="w-35px h-35px rounded-2 bg-light-primary d-flex align-items-center justify-content-center flex-shrink-0">
                                                    <i class="ki-duotone ki-book-open fs-5 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <div class="fw-bold text-gray-800 fs-7 text-hover-primary text-truncate lh-sm"><?= esc($lesson['lesson_title']) ?></div>
                                                    <?php if (!empty($lesson['lesson_desc'])): ?>
                                                    <div class="text-muted fs-9 text-truncate"><?= esc(mb_substr($lesson['lesson_desc'], 0, 90)) ?><?= mb_strlen($lesson['lesson_desc'] ?? '') > 90 ? '…' : '' ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex gap-1 flex-shrink-0 flex-wrap justify-content-end">
                                                    <?php if ((int)$lesson['file_count']  > 0): ?><span class="badge badge-light-primary fs-9"><?= (int)$lesson['file_count'] ?>f</span><?php endif; ?>
                                                    <?php if ((int)$lesson['video_count'] > 0): ?><span class="badge badge-light-danger fs-9"><?= (int)$lesson['video_count'] ?>v</span><?php endif; ?>
                                                    <?php if ((int)$lesson['link_count']  > 0): ?><span class="badge badge-light-info fs-9"><?= (int)$lesson['link_count'] ?>lk</span><?php endif; ?>
                                                    <i class="ki-duotone ki-arrow-right fs-5 text-primary ms-1"><span class="path1"></span><span class="path2"></span></i>
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

                            <?php
                            // Lessons with no specific day scheduled (lesson_day = 0 or null)
                            $unscheduled = $lessonMap[$t][$wNum][0] ?? [];
                            if (!empty($unscheduled)):
                            ?>
                            <div class="mt-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge badge-light-warning fs-9">Unscheduled this week</span>
                                    <div class="flex-grow-1" style="height:1px;background:#f1f1f4;"></div>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                <?php foreach ($unscheduled as $lesson):
                                    $lu = base_url("classroom/student/{$classSubId}/lesson/{$lesson['lesson_id']}");
                                ?>
                                <a href="<?= $lu ?>" class="text-decoration-none">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-2 hover-elevate-up" style="border:1px dashed #e4e6ea;border-radius:.75rem!important;">
                                        <div class="w-35px h-35px rounded-2 bg-light-warning d-flex align-items-center justify-content-center flex-shrink-0">
                                            <i class="ki-duotone ki-book-open fs-5 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-bold text-gray-800 fs-7 text-hover-primary text-truncate"><?= esc($lesson['lesson_title']) ?></div>
                                            <?php if (!empty($lesson['lesson_desc'])): ?>
                                            <div class="text-muted fs-9 text-truncate"><?= esc(mb_substr($lesson['lesson_desc'], 0, 90)) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <i class="ki-duotone ki-arrow-right fs-5 text-primary flex-shrink-0"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
                        <!--end::Week block-->
                        <?php endforeach; ?>

                        <?php if ($termCounts[$t] === 0): ?>
                        <div class="text-center py-10 text-muted">
                            <i class="ki-duotone ki-book-open fs-3x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="fs-7">No lessons published for Term <?= $t ?> yet.</div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <!--end::Term tabs-->
                    <?php endif; ?>

                    <script>
                    function studToggleDayPanel(panelId, cardEl) {
                        const panel = document.getElementById(panelId);
                        if (!panel) return;
                        const isOpen = !panel.classList.contains('d-none');
                        if (isOpen) {
                            panel.classList.add('d-none');
                            cardEl?.querySelector('.stud-day-chevron')?.style.setProperty('transform', '');
                        } else {
                            panel.classList.remove('d-none');
                            cardEl?.querySelector('.stud-day-chevron')?.style.setProperty('transform', 'rotate(180deg)');
                        }
                    }
                    document.getElementById('student_lesson_year_select')?.addEventListener('change', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('year', this.value);
                        window.location.href = url.toString();
                    });
                    </script>
                    <!--end::Lessons-->

                    <?php elseif ($section === 'assignments'): ?>
                    <!--begin::Assignments-->
                    <div class="d-flex align-items-center mb-6">
                        <i class="ki-duotone ki-document fs-2 text-primary me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <h4 class="fw-bold text-gray-800 mb-0">Assignments</h4>
                        <span class="badge badge-light-primary ms-2 fs-8"><?= count($assignments ?? []) ?></span>
                    </div>

                    <?php if (empty($assignments)): ?>
                    <div class="text-center py-16 text-muted">
                        <i class="ki-duotone ki-document fs-4x text-gray-200 mb-3">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div class="fs-6 fw-semibold">No assignments available yet.</div>
                    </div>
                    <?php else: ?>
                    <div class="row g-5">
                    <?php foreach ($assignments as $asgn):
                        $isPastDue    = !empty($asgn['assignment_due_date']) && strtotime($asgn['assignment_due_date']) < time();
                        $isSubmitted  = !empty($asgn['submission_id']);
                        if ($isSubmitted) {
                            $submitColor = 'success';
                            $submitLabel = 'Submitted';
                        } elseif ($isPastDue) {
                            $submitColor = 'danger';
                            $submitLabel = 'Overdue — Not Submitted';
                        } else {
                            $daysLeft    = !empty($asgn['assignment_due_date'])
                                ? (int) ceil((strtotime($asgn['assignment_due_date']) - time()) / 86400)
                                : null;
                            $submitColor = 'warning';
                            $submitLabel = 'Not Submitted' . ($daysLeft !== null ? ' — Due in ' . $daysLeft . 'd' : '');
                        }
                    ?>
                    <div class="col-md-4">
                        <div class="card border border-dashed h-100" style="border-radius:.75rem;border-color:#c4c4d4!important;overflow:visible;position:relative;">
                            <!--begin::Dropdown-->
                            <div style="position:absolute;top:10px;right:10px;z-index:10;">
                                <button type="button" class="btn btn-sm btn-icon btn-light"
                                        style="width:30px;height:30px;border-radius:6px;"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ki-duotone ki-down fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end py-2 fs-7 fw-semibold" style="min-width:175px;">
                                    <?php if (!($isParentView ?? false) && ($fullAccess ?? false)): ?>
                                    <li>
                                        <a class="dropdown-item py-2"
                                           href="<?= base_url('classroom/student/' . $classSubId . '/assignment/' . $asgn['assignment_id'] . '/submit') ?>">
                                            <i class="ki-duotone ki-send fs-6 me-2"><span class="path1"></span><span class="path2"></span></i>
                                            Submit Assignment
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <li>
                                        <a class="dropdown-item py-2"
                                           href="<?= base_url('classroom/student/' . $classSubId . '/assignment/' . $asgn['assignment_id'] . '/assessment') ?>">
                                            <i class="ki-duotone ki-chart-simple-3 fs-6 me-2">
                                                <span class="path1"></span><span class="path2"></span>
                                                <span class="path3"></span><span class="path4"></span>
                                            </i>View Assessment
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!--end::Dropdown-->
                            <div class="card-body p-5 pt-4">
                                <span class="badge badge-light-success fs-9 mb-3">Published</span>
                                <?php if (!($isParentView ?? false)): ?>
                                <span class="badge badge-light-<?= $submitColor ?> fs-9 mb-3 ms-1"><?= esc($submitLabel) ?></span>
                                <?php endif; ?>
                                <div class="fw-bold text-gray-800 fs-6 mb-3 pe-8 lh-sm"><?= esc($asgn['assignment_name']) ?></div>
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
                                        Posted <?= !empty($asgn['created_at']) ? date('d M Y', strtotime($asgn['created_at'])) : '—' ?>
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

                    <?php elseif ($section === 'exam'): ?>
                    <!--begin::Exam-->
                    <?php
                    $studentExamReports = $studentExamReports ?? [];
                    $reportStatuses     = $reportStatuses     ?? [1=>['status'=>'collecting'],2=>['status'=>'collecting'],3=>['status'=>'collecting']];
                    $userId             = (int) session()->get('userID');
                    ?>
                    <div class="d-flex align-items-center mb-5">
                        <i class="ki-duotone ki-note-2 fs-2 text-primary me-2">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span>
                        </i>
                        <h4 class="fw-bold text-gray-800 mb-0">Term Examination Results</h4>
                    </div>

                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-semibold mb-5">
                        <?php foreach ([1,2,3] as $t): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $t===1?'active':'' ?> text-active-primary pb-4"
                               data-bs-toggle="tab" data-bs-target="#stu_exam_term_<?= $t ?>">
                                Term <?= $t ?>
                                <?php if (($reportStatuses[$t]['status'] ?? '') === 'published'): ?>
                                <span class="badge badge-light-success ms-1 fs-9">Published</span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tab-content">
                    <?php foreach ([1,2,3] as $t):
                        $tStatus  = $reportStatuses[$t]['status']  ?? 'collecting';
                        $rep      = $studentExamReports[$t]        ?? null;
                        $isPublished = $tStatus === 'published';
                    ?>
                    <div class="tab-pane fade <?= $t===1?'show active':'' ?>" id="stu_exam_term_<?= $t ?>">
                        <?php if (!$isPublished): ?>
                        <div class="text-center py-12 text-muted">
                            <i class="ki-duotone ki-lock fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
                            <div class="fs-6 fw-semibold mb-1">Results not yet published</div>
                            <div class="fs-8">Term <?= $t ?> results will appear here once published by the Principal.</div>
                        </div>
                        <?php elseif (!$rep || empty($rep['marks'])): ?>
                        <div class="text-center py-12 text-muted">
                            <div class="fs-7">No marks recorded for Term <?= $t ?>.</div>
                        </div>
                        <?php else:
                            $totalE = $rep['total_earned'];
                            $totalP = $rep['total_possible'];
                            $ovPct  = $rep['overall_pct'];
                            $grade  = $ovPct !== null ? \App\Models\TermExamModel::grade($ovPct) : null;
                            $gColor = $grade ? \App\Models\TermExamModel::gradeColor($grade) : 'secondary';
                        ?>

                        <!-- Overall summary -->
                        <div class="row g-4 mb-6">
                            <div class="col-6 col-md-3">
                                <div class="card border-0 bg-light-primary text-center p-4">
                                    <div class="fw-bold fs-2x text-primary"><?= $ovPct !== null ? $ovPct.'%' : '—' ?></div>
                                    <div class="text-muted fs-8">Overall</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 bg-light-<?= $gColor ?> text-center p-4">
                                    <div class="fw-bold fs-2x text-<?= $gColor ?>"><?= $grade ?? '—' ?></div>
                                    <div class="text-muted fs-8">Grade</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="card border-0 bg-light-info text-center p-4">
                                    <div class="fw-bold fs-2x text-info"><?= round($totalE,1) ?>/<?= round($totalP,1) ?></div>
                                    <div class="text-muted fs-8">Total Marks</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 d-flex align-items-center">
                                <a href="<?= base_url('classroom/report/'.$classId.'/student/'.$userId.'/term/'.$t) ?>"
                                   target="_blank" class="btn btn-primary btn-sm w-100">
                                    <i class="ki-duotone ki-printer fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                                    View / Print Report
                                </a>
                            </div>
                        </div>

                        <!-- Subject marks table -->
                        <div class="table-responsive mb-5">
                            <table class="table table-row-bordered align-middle gs-0 gy-3 fs-7">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4">Subject</th>
                                        <th class="text-center">Mark</th>
                                        <th class="text-center">Out Of</th>
                                        <th class="text-center">%</th>
                                        <th class="text-center">Grade</th>
                                        <th>Teacher Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($rep['marks'] as $m):
                                    $mp  = ($m['mark'] !== null && $m['total_mark'] > 0) ? round(($m['mark']/$m['total_mark'])*100,1) : null;
                                    $mg  = $mp !== null ? \App\Models\TermExamModel::grade($mp) : '—';
                                    $mgc = $mp !== null ? \App\Models\TermExamModel::gradeColor($mg) : 'secondary';
                                ?>
                                <tr>
                                    <td class="ps-4 fw-semibold"><?= esc($m['subject_name']) ?></td>
                                    <td class="text-center fw-bold"><?= $m['mark'] !== null ? $m['mark'] : '—' ?></td>
                                    <td class="text-center text-muted"><?= $m['total_mark'] ?></td>
                                    <td class="text-center"><?= $mp !== null ? $mp.'%' : '—' ?></td>
                                    <td class="text-center"><span class="badge badge-light-<?= $mgc ?> fs-8"><?= $mg ?></span></td>
                                    <td class="text-muted fst-italic fs-8"><?= $m['teacher_comment'] ? esc($m['teacher_comment']) : '' ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Comments -->
                        <div class="row g-4">
                            <?php if ($rep['ct_comment']): ?>
                            <div class="col-md-6">
                                <div class="fw-semibold text-gray-600 fs-8 mb-2">Class Teacher Comment</div>
                                <div class="p-4 rounded-2 bg-light-primary fs-8 lh-lg"><?= nl2br(esc($rep['ct_comment'])) ?></div>
                            </div>
                            <?php endif; ?>
                            <?php if ($rep['principal_comment']): ?>
                            <div class="col-md-6">
                                <div class="fw-semibold text-gray-600 fs-8 mb-2">Principal Comment</div>
                                <div class="p-4 rounded-2 bg-light-success fs-8 lh-lg"><?= nl2br(esc($rep['principal_comment'])) ?></div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php endif; // isPublished ?>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <!--end::Exam-->

                    <?php elseif ($section === 'feedback'): ?>
                    <!--begin::Feedback-->
                    <?php if ($isParentView ?? false): ?>
                    <div class="text-center py-14">
                        <i class="ki-duotone ki-lock-2 fs-4x text-gray-300 mb-4">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div class="fs-6 fw-semibold text-gray-600 mb-2">Parent View</div>
                        <div class="text-muted fs-8">Course feedback can only be submitted by the student.</div>
                    </div>
                    <?php else: ?>
                    <?php
                    $fb       = $existingFeedback ?? null;
                    $teacher  = $subjectTeacher   ?? null;
                    $ratings  = [
                        'overall_rating'    => ['label' => 'Overall Experience',    'icon' => 'ki-star',          'desc' => 'How would you rate this subject overall?'],
                        'teaching_rating'   => ['label' => 'Teaching Quality',      'icon' => 'ki-teacher',       'desc' => 'How effectively does the teacher explain the content?'],
                        'content_rating'    => ['label' => 'Course Content',        'icon' => 'ki-book-open',     'desc' => 'How useful and relevant is the course material?'],
                        'engagement_rating' => ['label' => 'Engagement & Support',  'icon' => 'ki-message-edit',  'desc' => 'How engaging and supportive is the learning environment?'],
                    ];
                    ?>

                    <!--begin::Feedback header-->
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ki-duotone ki-message-text-2 fs-2 text-primary me-1">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <h4 class="fw-bold text-gray-800 mb-0">Course Feedback</h4>
                        </div>
                    </div>

                    <!--begin::Teacher card-->
                    <?php if ($teacher): ?>
                    <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-6" style="background:#f8fafc;border:1px solid #e2e8f0;">
                        <div class="symbol symbol-45px flex-shrink-0">
                            <?php if (!empty($teacher['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/'.$teacher['profile_photo']) ?>"
                                 class="rounded-circle" style="width:45px;height:45px;object-fit:cover;">
                            <?php else: ?>
                            <div class="symbol-label bg-light-primary fw-bold text-primary fs-7">
                                <?= strtoupper(substr($teacher['teacher_name'],0,1)) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="fw-bold text-gray-800 fs-7"><?= esc($teacher['teacher_name']) ?></div>
                            <div class="text-muted fs-9">Subject Teacher</div>
                        </div>
                        <div class="ms-auto text-muted fs-9 fst-italic">Your feedback is used to improve teaching quality.</div>
                    </div>
                    <?php endif; ?>
                    <!--end::Teacher card-->

                    <?php if (!$fb): ?>
                    <!--begin::Feedback form-->
                    <form id="feedbackForm">

                        <!--Star rating categories-->
                        <?php foreach ($ratings as $field => $cfg): $val = (int)($fb[$field] ?? 0); ?>
                        <div class="mb-6 feedback-category" data-field="<?= $field ?>">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="ki-duotone <?= $cfg['icon'] ?> fs-5 text-primary">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                </i>
                                <span class="fw-bold text-gray-700 fs-7"><?= $cfg['label'] ?></span>
                                <?php if ($field === 'overall_rating'): ?>
                                <span class="badge badge-light-danger fs-9 ms-1">Required</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted fs-9 mb-3"><?= $cfg['desc'] ?></div>
                            <div class="star-rating" id="stars_<?= $field ?>">
                                <?php for ($s = 5; $s >= 1; $s--): ?>
                                <input type="radio" name="<?= $field ?>" id="<?= $field ?>_<?= $s ?>" value="<?= $s ?>"
                                       <?= $val === $s ? 'checked' : '' ?>>
                                <label for="<?= $field ?>_<?= $s ?>" title="<?= $s ?> star<?= $s>1?'s':'' ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <div class="star-label text-muted fs-9 mt-1" id="label_<?= $field ?>">
                                <?= $val > 0 ? ['','Very Poor','Poor','Average','Good','Excellent'][$val] : 'Click to rate' ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <!--Comment-->
                        <div class="mb-5">
                            <label class="form-label fw-semibold fs-7">
                                Overall Comment <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <textarea class="form-control form-control-sm" id="fbComment" name="comment"
                                      rows="3" maxlength="1000"
                                      placeholder="Share any additional thoughts about this course…"><?= esc($fb['comment'] ?? '') ?></textarea>
                            <div class="d-flex justify-content-end text-muted fs-9 mt-1">
                                <span id="charCount">0</span> / 1000
                            </div>
                        </div>

                        <!--Anonymous toggle-->
                        <div class="d-flex align-items-center gap-3 mb-6 p-3 rounded-2 bg-light">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="fbAnonymous" name="is_anonymous"
                                       value="1" <?= !empty($fb['is_anonymous']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold fs-7 ms-2" for="fbAnonymous">
                                    Submit anonymously
                                </label>
                            </div>
                            <span class="text-muted fs-9">Your name will not be shown to the teacher.</span>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="btnSubmitFeedback">
                                <span class="indicator-label">
                                    <i class="ki-duotone ki-send fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>
                                    <?= $fb ? 'Update Feedback' : 'Submit Feedback' ?>
                                </span>
                                <span class="indicator-progress">Saving… <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                    <!--end::Feedback form-->

                    <?php else: ?>
                    <!--begin::Feedback read-only-->
                    <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-6"
                         style="background:#f0fdf4;border:1px solid #bbf7d0;">
                        <i class="ki-duotone ki-check-circle fs-2 text-success flex-shrink-0">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold text-gray-800 fs-7">Feedback submitted</div>
                            <div class="text-muted fs-9">
                                Submitted on <?= date('d M Y \a\t g:i A', strtotime($fb['updated_at'])) ?>
                                <?= $fb['is_anonymous'] ? ' &bull; <span class="badge badge-light-secondary fs-9">Anonymous</span>' : '' ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    $roRatings = [
                        'overall_rating'    => ['label' => 'Overall Experience',   'icon' => 'ki-star'],
                        'teaching_rating'   => ['label' => 'Teaching Quality',     'icon' => 'ki-teacher'],
                        'content_rating'    => ['label' => 'Course Content',       'icon' => 'ki-book-open'],
                        'engagement_rating' => ['label' => 'Engagement & Support', 'icon' => 'ki-message-edit'],
                    ];
                    $roLabels = ['','Very Poor','Poor','Average','Good','Excellent'];
                    ?>
                    <div class="d-flex flex-column gap-0">
                    <?php foreach ($roRatings as $field => $cfg):
                        $val = (int)($fb[$field] ?? 0);
                    ?>
                    <div class="d-flex align-items-center justify-content-between py-4"
                         style="border-bottom:1px dashed #f1f1f4;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ki-duotone <?= $cfg['icon'] ?> fs-5 text-primary">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <span class="fw-semibold text-gray-700 fs-7"><?= $cfg['label'] ?></span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                <span style="font-size:1.5rem;color:<?= $s <= $val ? '#fbbf24' : '#e5e7eb' ?>;">★</span>
                                <?php endfor; ?>
                            </div>
                            <span class="fw-bold fs-8 text-gray-600" style="min-width:60px;">
                                <?= $val > 0 ? $roLabels[$val] : '<span class="text-muted fst-italic">Not rated</span>' ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>

                    <?php if (!empty($fb['comment'])): ?>
                    <div class="mt-5 p-4 rounded-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
                        <div class="fw-bold text-gray-700 fs-8 mb-2 d-flex align-items-center gap-2">
                            <i class="ki-duotone ki-message-text-2 fs-5 text-muted">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            Your Comment
                        </div>
                        <div class="text-gray-600 fs-8 lh-lg"><?= nl2br(esc($fb['comment'])) ?></div>
                    </div>
                    <?php endif; ?>
                    <!--end::Feedback read-only-->
                    <?php endif; ?>

                    <script>
                    (function() {
                        const LABELS = ['','Very Poor','Poor','Average','Good','Excellent'];

                        // Live star label update
                        document.querySelectorAll('.star-rating input').forEach(function(input) {
                            input.addEventListener('change', function() {
                                const field = this.name;
                                const val   = parseInt(this.value);
                                const lbl   = document.getElementById('label_' + field);
                                if (lbl) lbl.textContent = LABELS[val] || '';
                            });
                        });

                        // Char counter
                        const textarea = document.getElementById('fbComment');
                        const counter  = document.getElementById('charCount');
                        if (textarea && counter) {
                            counter.textContent = textarea.value.length;
                            textarea.addEventListener('input', function() {
                                counter.textContent = this.value.length;
                            });
                        }

                        // Submit
                        document.getElementById('btnSubmitFeedback')?.addEventListener('click', function() {
                            const overall = document.querySelector('input[name="overall_rating"]:checked');
                            if (!overall) {
                                Swal.fire({ title:'Overall rating required', text:'Please give at least an overall star rating.',
                                    icon:'warning', buttonsStyling:false, confirmButtonText:'OK',
                                    customClass:{ confirmButton:'btn btn-warning' } });
                                return;
                            }
                            const btn = this;
                            btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;

                            const fd = new FormData();
                            ['overall_rating','teaching_rating','content_rating','engagement_rating'].forEach(function(f) {
                                const el = document.querySelector('input[name="'+f+'"]:checked');
                                fd.append(f, el ? el.value : '0');
                            });
                            fd.append('comment',      document.getElementById('fbComment').value);
                            fd.append('is_anonymous', document.getElementById('fbAnonymous').checked ? '1' : '0');

                            $.ajax({
                                url:         '<?= base_url('classroom/student/'.$classSubId.'/feedback/store') ?>',
                                type:        'POST',
                                data:        fd,
                                processData: false,
                                contentType: false,
                                success: function(res) {
                                    btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
                                    if (res.success) {
                                        Swal.fire({ title: res.is_update ? 'Feedback Updated!' : 'Thank You!',
                                            text: res.message, icon:'success', timer:2000, showConfirmButton:false })
                                            .then(() => location.reload());
                                    } else {
                                        Swal.fire({ title:'Error', text:res.message, icon:'error',
                                            buttonsStyling:false, confirmButtonText:'Close',
                                            customClass:{ confirmButton:'btn btn-danger' } });
                                    }
                                },
                                error: function() {
                                    btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
                                    Swal.fire({ title:'Error', text:'Something went wrong.', icon:'error',
                                        buttonsStyling:false, confirmButtonText:'Close',
                                        customClass:{ confirmButton:'btn btn-danger' } });
                                }
                            });
                        });
                    })();
                    </script>

                    <style>
                    /* ── Star Rating ─────────────────────────── */
                    .star-rating {
                        display: inline-flex;
                        flex-direction: row-reverse;
                        gap: 4px;
                    }
                    .star-rating input { display: none; }
                    .star-rating label {
                        color: #d1d5db;
                        cursor: pointer;
                        font-size: 2.4rem;
                        line-height: 1;
                        transition: color .12s, transform .1s;
                    }
                    .star-rating label:hover { transform: scale(1.15); }
                    /* Checked state */
                    .star-rating input:checked + label,
                    .star-rating input:checked ~ label { color: #fbbf24; }
                    /* Hover resets checked, shows hover fill */
                    .star-rating:hover label { color: #d1d5db; }
                    .star-rating label:hover,
                    .star-rating label:hover ~ label { color: #fbbf24; }
                    .star-label { min-height: 18px; font-weight: 600; }
                    /* Category divider */
                    .feedback-category + .feedback-category {
                        padding-top: 1.25rem;
                        border-top: 1px dashed #f1f1f4;
                    }
                    </style>
                    <?php endif; // end isParentView else ?>
                    <!--end::Feedback-->

                    <?php elseif ($section === 'discussions'): ?>
                    <?= view('app/classroom/teacher/_class_discussion', [
                        'discussions'     => $discussions     ?? [],
                        'sessionFname'    => $sessionFname    ?? 'Student',
                        'sessionPhotoUrl' => $sessionPhotoUrl ?? null,
                        'sessionUserId'   => $sessionUserId   ?? 0,
                        'sdPostUrl'       => base_url('classroom/' . $classId . '/discussion/post'),
                        'canPost'         => !($isParentView ?? false) && ($fullAccess ?? false),
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

<script>
function openChatForUser(userId, fname, lname, photo) {
    if (typeof openChat !== 'function') return;
    openChat({ user_id: userId, fname: fname || '', lname: lname || '', profile_photo: photo || '', online_status: 'Offline' }, null);
}
</script>

<style>
.nav-pills-custom .nav-link:not(.active):hover {
    background: #f5f8ff;
    border-radius: 0.475rem;
    color: var(--bs-primary) !important;
}
.nav-pills-custom .nav-link:not(.active):hover i { color: var(--bs-primary) !important; }
</style>
<script>
function ldToggle(id) {
    const s = document.getElementById('ldesc_s_' + id);
    const f = document.getElementById('ldesc_f_' + id);
    s.classList.toggle('d-none');
    f.classList.toggle('d-none');
}
</script>
<style>
.quick-link-card:hover { background: #f5f8ff; border-color: var(--bs-primary) !important; }
.user-link { transition: background .15s; }
.user-link:hover { background: #f0f4ff; }
.user-link:hover .user-link-name { color: var(--bs-primary) !important; }
</style>

