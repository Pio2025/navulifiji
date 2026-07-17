<?php
$activeClassrooms  = $ts_active_classrooms  ?? 0;
$subjectsTaught    = $ts_subjects_taught    ?? 0;
$totalStudents     = $ts_total_students     ?? 0;
$lessonsPublished  = $ts_lessons_published  ?? 0;
$assignmentsActive = $ts_assignments_active ?? 0;
$marksEntered      = $ts_marks_entered      ?? 0;
$markBands         = $ts_mark_bands         ?? [];
$assignmentSub     = $ts_assignment_sub     ?? [];
$attendanceStats   = $ts_attendance         ?? [];
$recentLessons     = $ts_recent_lessons     ?? [];

$teacherName = ($fname ?? '') ?: ($name ?? 'Teacher');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Teacher Dashboard</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Teacher</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted fs-7 fw-semibold me-2 d-none d-md-inline-flex align-items-center">
                <i class="ki-duotone ki-calendar fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                <?= date('l, d F Y') ?>
            </span>
            <a href="<?= base_url('classroom/my') ?>" class="btn btn-sm btn-light-primary fw-semibold">
                <i class="ki-duotone ki-teacher fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                My Classrooms
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<!--begin::Welcome banner-->
<div class="card bg-primary mb-6">
    <div class="card-body d-flex align-items-center py-5 px-7 gap-5">
        <div class="symbol symbol-60px flex-shrink-0">
            <div class="symbol-label bg-white bg-opacity-20">
                <i class="ki-duotone ki-teacher fs-2x text-white">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>
        </div>
        <div>
            <div class="fs-4 fw-bold text-white mb-1">Welcome back, <?= esc($teacherName) ?>!</div>
            <div class="text-white text-opacity-75 fs-7">
                You are managing <strong class="text-white"><?= $activeClassrooms ?></strong> classroom<?= $activeClassrooms !== 1 ? 's' : '' ?>
                with <strong class="text-white"><?= $totalStudents ?></strong> student<?= $totalStudents !== 1 ? 's' : '' ?> enrolled.
            </div>
        </div>
    </div>
</div>
<!--end::Welcome banner-->

<!--begin::KPI cards-->
<div class="row g-5 mb-6">

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                <div class="symbol symbol-50px mb-3">
                    <div class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-book-open fs-2 text-primary">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                        </i>
                    </div>
                </div>
                <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $activeClassrooms ?></div>
                <div class="text-muted fs-8 fw-semibold">Active Classes</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                <div class="symbol symbol-50px mb-3">
                    <div class="symbol-label bg-light-info">
                        <i class="ki-duotone ki-abstract-26 fs-2 text-info">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $subjectsTaught ?></div>
                <div class="text-muted fs-8 fw-semibold">Subjects Taught</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                <div class="symbol symbol-50px mb-3">
                    <div class="symbol-label bg-light-success">
                        <i class="ki-duotone ki-people fs-2 text-success">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                    </div>
                </div>
                <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $totalStudents ?></div>
                <div class="text-muted fs-8 fw-semibold">My Students</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                <div class="symbol symbol-50px mb-3">
                    <div class="symbol-label bg-light-warning">
                        <i class="ki-duotone ki-notepad fs-2 text-warning">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                    </div>
                </div>
                <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $lessonsPublished ?></div>
                <div class="text-muted fs-8 fw-semibold">Lessons Published</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                <div class="symbol symbol-50px mb-3">
                    <div class="symbol-label bg-light-danger">
                        <i class="ki-duotone ki-document fs-2 text-danger">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $assignmentsActive ?></div>
                <div class="text-muted fs-8 fw-semibold">Active Assignments</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                <div class="symbol symbol-50px mb-3">
                    <div class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-check-square fs-2 text-primary">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $marksEntered ?></div>
                <div class="text-muted fs-8 fw-semibold">Marks Entered</div>
            </div>
        </div>
    </div>

</div>
<!--end::KPI cards-->

<!--begin::Charts row-->
<div class="row g-5 mb-6">

    <!--begin::Mark distribution-->
    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold text-gray-800">Student Mark Distribution</h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-primary fs-8">Term Exam Results</span>
                </div>
            </div>
            <div class="card-body pt-2">
                <?php if (array_sum($markBands) === 0): ?>
                <div class="d-flex flex-column align-items-center justify-content-center py-10 text-center">
                    <i class="ki-duotone ki-information-5 fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="text-muted fs-7">No exam marks recorded yet.</div>
                </div>
                <?php else: ?>
                <div id="chart_mark_dist" style="height:260px;"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--end::Mark distribution-->

    <!--begin::Attendance-->
    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold text-gray-800">Attendance Rate by Class</h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-success fs-8">Daily Attendance</span>
                </div>
            </div>
            <div class="card-body pt-2">
                <?php if (empty($attendanceStats)): ?>
                <div class="d-flex flex-column align-items-center justify-content-center py-10 text-center">
                    <i class="ki-duotone ki-information-5 fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="text-muted fs-7">No attendance records yet.</div>
                </div>
                <?php else: ?>
                <div id="chart_attendance" style="height:260px;"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--end::Attendance-->

</div>
<!--end::Charts row-->

<!--begin::Bottom row-->
<div class="row g-5 mb-6">

    <!--begin::Assignment submissions-->
    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold text-gray-800">Assignment Submissions</h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-warning fs-8">Published Only</span>
                </div>
            </div>
            <div class="card-body pt-2">
                <?php if (empty($assignmentSub)): ?>
                <div class="d-flex flex-column align-items-center justify-content-center py-10 text-center">
                    <i class="ki-duotone ki-information-5 fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="text-muted fs-7">No published assignments yet.</div>
                </div>
                <?php else: ?>
                <div id="chart_assignments" style="height:260px;"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--end::Assignment submissions-->

    <!--begin::Recent lessons-->
    <div class="col-12 col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold text-gray-800">Recent Lessons</h3>
                <div class="card-toolbar">
                    <a href="<?= base_url('classroom/my') ?>" class="btn btn-sm btn-light-primary">View All</a>
                </div>
            </div>
            <div class="card-body pt-3">
                <?php if (empty($recentLessons)): ?>
                <div class="d-flex flex-column align-items-center justify-content-center py-10 text-center">
                    <i class="ki-duotone ki-information-5 fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="text-muted fs-7">No lessons published yet.</div>
                </div>
                <?php else: ?>
                <div class="d-flex flex-column gap-4">
                    <?php foreach ($recentLessons as $lesson): ?>
                    <div class="d-flex align-items-center gap-4">
                        <div class="symbol symbol-40px flex-shrink-0">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-notepad fs-3 text-primary">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                </i>
                            </div>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-gray-800 fs-7 text-truncate"><?= esc($lesson['lesson_title']) ?></div>
                            <div class="text-muted fs-8">
                                <?= esc($lesson['subject_name']) ?> &bull; <?= esc($lesson['class_name']) ?>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <span class="badge badge-light-<?= $lesson['lesson_status'] === 'Published' ? 'success' : 'secondary' ?> fs-9">
                                <?= esc($lesson['lesson_status']) ?>
                            </span>
                            <div class="text-muted fs-9 mt-1">
                                <?= date('d M', strtotime($lesson['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--end::Recent lessons-->

</div>
<!--end::Bottom row-->

</div><!--end::Content container-->
</div><!--end::Content-->

<script>
document.addEventListener('DOMContentLoaded', function () {
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark'
        || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
    var gridColor  = isDark ? '#2d2d43' : '#f1f1f4';
    var labelColor = isDark ? '#9d9da6' : '#7e8299';
    var baseFont   = { fontFamily: 'inherit' };

    // ─── Mark Distribution ───────────────────────────────────────────────
    <?php if (array_sum($markBands) > 0): ?>
    (function () {
        var el = document.getElementById('chart_mark_dist');
        if (!el) return;
        var data = [
            <?= (int)($markBands['below50'] ?? 0) ?>,
            <?= (int)($markBands['f50']     ?? 0) ?>,
            <?= (int)($markBands['f60']     ?? 0) ?>,
            <?= (int)($markBands['f70']     ?? 0) ?>,
            <?= (int)($markBands['f80']     ?? 0) ?>,
            <?= (int)($markBands['f90']     ?? 0) ?>,
            <?= (int)($markBands['absent']  ?? 0) ?>
        ];
        var colors = ['#f1416c','#fd7e14','#ffc700','#50cd89','#009ef7','#7239ea','#a1a5b7'];
        new ApexCharts(el, {
            chart:  { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [{ name: 'Students', data: data }],
            colors: colors,
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    distributed: true,
                    columnWidth: '60%',
                }
            },
            xaxis: {
                categories: ['< 50%', '50–59%', '60–69%', '70–79%', '80–89%', '90–100%', 'Absent'],
                labels: { style: { colors: Array(7).fill(labelColor), fontSize: '11px' } },
                axisBorder: { show: false },
                axisTicks:  { show: false },
            },
            yaxis: {
                labels: { style: { colors: [labelColor] } },
                tickAmount: 4,
            },
            grid:   { borderColor: gridColor, strokeDashArray: 4 },
            legend: { show: false },
            tooltip: {
                y: { formatter: function (v) { return v + ' student' + (v !== 1 ? 's' : ''); } }
            },
            dataLabels: { enabled: true, style: { fontSize: '11px' } },
        }).render();
    })();
    <?php endif; ?>

    // ─── Attendance ───────────────────────────────────────────────────────
    <?php if (!empty($attendanceStats)): ?>
    (function () {
        var el = document.getElementById('chart_attendance');
        if (!el) return;
        var cats  = <?= json_encode(array_column($attendanceStats, 'class_name')) ?>;
        var pcts  = <?= json_encode(array_column($attendanceStats, 'pct')) ?>;
        new ApexCharts(el, {
            chart:  { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [{ name: 'Attendance %', data: pcts }],
            colors: ['#50cd89'],
            plotOptions: {
                bar: { borderRadius: 4, horizontal: true, barHeight: '50%' }
            },
            xaxis: {
                categories: cats,
                min: 0, max: 100,
                labels: {
                    formatter: function (v) { return v + '%'; },
                    style: { colors: Array(cats.length).fill(labelColor) }
                },
                axisBorder: { show: false },
                axisTicks:  { show: false },
            },
            yaxis: { labels: { style: { colors: [labelColor] } } },
            grid:   { borderColor: gridColor, strokeDashArray: 4, xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
            dataLabels: {
                enabled: true,
                formatter: function (v) { return v + '%'; },
                style: { fontSize: '11px' }
            },
            tooltip: {
                y: { formatter: function (v) { return v + '%'; } }
            },
        }).render();
    })();
    <?php endif; ?>

    // ─── Assignment Submissions ────────────────────────────────────────────
    <?php if (!empty($assignmentSub)): ?>
    (function () {
        var el = document.getElementById('chart_assignments');
        if (!el) return;
        var names     = <?= json_encode(array_column($assignmentSub, 'name')) ?>;
        var enrolled  = <?= json_encode(array_column($assignmentSub, 'enrolled')) ?>;
        var submitted = <?= json_encode(array_column($assignmentSub, 'submitted')) ?>;
        var notSub    = enrolled.map(function (e, i) { return Math.max(0, e - submitted[i]); });
        new ApexCharts(el, {
            chart:  { type: 'bar', height: 260, stacked: true, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [
                { name: 'Submitted',     data: submitted },
                { name: 'Not Submitted', data: notSub },
            ],
            colors: ['#009ef7','#e4e6ea'],
            plotOptions: { bar: { borderRadius: 3, horizontal: true, barHeight: '50%' } },
            xaxis: {
                categories: names,
                labels: { style: { colors: Array(names.length).fill(labelColor) } },
                axisBorder: { show: false },
                axisTicks:  { show: false },
            },
            yaxis: { labels: { style: { colors: [labelColor] } } },
            grid:   { borderColor: gridColor, strokeDashArray: 4, xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
            legend: { show: true, position: 'top', horizontalAlign: 'right', fontSize: '11px', labels: { colors: [labelColor] } },
            dataLabels: { enabled: false },
            tooltip: {
                y: { formatter: function (v) { return v + ' student' + (v !== 1 ? 's' : ''); } }
            },
        }).render();
    })();
    <?php endif; ?>
});
</script>
