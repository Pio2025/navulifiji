<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Drag &amp; Drop Analysis
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= $backUrl ?>" class="text-muted text-hover-primary"><?= esc($lesson['lesson_title']) ?></a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Analysis</li>
            </ul>
        </div>
        <a href="<?= $backUrl ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back to Lesson
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php
    $participationPct = $enrolledCount > 0 ? round(($totalAttempts / $enrolledCount) * 100) : 0;
    $statusColor      = $quiz['quizze_status'] === 'Published' ? 'success' : 'warning';
    ?>

    <!--begin::Assessment info bar-->
    <div class="card border-0 shadow-sm mb-6">
        <div class="card-body py-4 px-6 d-flex align-items-center gap-4 flex-wrap">
            <div class="symbol symbol-45px">
                <div class="symbol-label bg-light-primary">
                    <i class="ki-duotone ki-abstract-26 fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold text-gray-800 fs-5"><?= esc($quiz['quizze_name']) ?></div>
                <div class="text-muted fs-8">
                    <?= esc($lesson['subject_name'] ?? '') ?>
                    &bull; <?= esc($lesson['lesson_title']) ?>
                    &bull; <?= $totalItems ?> item<?= $totalItems !== 1 ? 's' : '' ?>
                    <?php if ((int) $quiz['quizze_duration'] > 0): ?>
                    &bull; <?= (int) $quiz['quizze_duration'] ?> min
                    <?php endif; ?>
                </div>
            </div>
            <span class="badge badge-light-<?= $statusColor ?> fs-8"><?= esc($quiz['quizze_status']) ?></span>
        </div>
    </div>
    <!--end::Assessment info bar-->

    <!--begin::Stats row-->
    <div class="row g-5 mb-6">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold text-primary fs-2x mb-1"><?= $totalAttempts ?></div>
                    <div class="fw-semibold text-gray-700 fs-7 mb-1">Total Attempts</div>
                    <div class="text-muted fs-9"><?= $enrolledCount ?> enrolled &mdash; <?= $participationPct ?>% participation</div>
                    <div class="mt-3" style="height:6px;background:#f1f1f4;border-radius:3px;">
                        <div style="height:6px;width:<?= $participationPct ?>%;background:#009ef7;border-radius:3px;transition:width .6s;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-5 text-center">
                    <?php $avgColor = $avgScore >= 70 ? 'success' : ($avgScore >= 50 ? 'warning' : 'danger'); ?>
                    <div class="fw-bold text-<?= $avgColor ?> fs-2x mb-1"><?= number_format($avgScore, 1) ?>%</div>
                    <div class="fw-semibold text-gray-700 fs-7 mb-1">Average Score</div>
                    <div class="text-muted fs-9"><?= $totalAttempts > 0 ? 'across ' . $totalAttempts . ' attempt' . ($totalAttempts !== 1 ? 's' : '') : 'No attempts yet' ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold text-success fs-2x mb-1"><?= number_format((float) $highScore, 1) ?>%</div>
                    <div class="fw-semibold text-gray-700 fs-7 mb-1">Highest Score</div>
                    <div class="fw-bold text-danger fs-3 mt-1"><?= number_format((float) $lowScore, 1) ?>%</div>
                    <div class="text-muted fs-9">Lowest Score</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold text-primary fs-2x mb-1"><?= $totalItems ?></div>
                    <div class="fw-semibold text-gray-700 fs-7 mb-1">Total Items</div>
                    <div class="text-muted fs-9">drag &amp; drop items to match</div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Stats row-->

    <!--begin::Charts row-->
    <div class="row g-5 mb-6">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-0 pt-5 pb-0">
                    <h5 class="fw-bold text-gray-800 fs-6 mb-0">Score Distribution</h5>
                    <span class="text-muted fs-9">How student scores are spread across ranges</span>
                </div>
                <div class="card-body pt-4">
                    <div id="chart_distribution" style="min-height:280px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-0 pt-5 pb-0">
                    <h5 class="fw-bold text-gray-800 fs-6 mb-0">Participation</h5>
                    <span class="text-muted fs-9"><?= $totalAttempts ?> of <?= $enrolledCount ?> enrolled students</span>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center pt-4">
                    <?php if ($enrolledCount > 0): ?>
                    <div id="chart_participation" style="min-height:220px;width:100%;"></div>
                    <div class="d-flex justify-content-center gap-5 mt-3">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:12px;height:12px;border-radius:50%;background:#009ef7;display:inline-block;"></span>
                            <span class="fs-8 text-muted">Attempted (<?= $totalAttempts ?>)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:12px;height:12px;border-radius:50%;background:#f1f1f4;display:inline-block;"></span>
                            <span class="fs-8 text-muted">Not yet (<?= $enrolledCount - $totalAttempts ?>)</span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-10 text-muted">
                        <i class="ki-duotone ki-chart-pie-4 fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                        <div class="fs-7 fw-semibold">No students enrolled</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!--end::Charts row-->

    <!--begin::Per-item table-->
    <div class="card border-0 shadow-sm mb-6" style="overflow:hidden;">
        <div class="card-header border-0 pt-5 pb-4">
            <h5 class="fw-bold text-gray-800 fs-6 mb-0">Per-Item Performance</h5>
            <span class="text-muted fs-9"><?= $totalItems ?> item<?= $totalItems !== 1 ? 's' : '' ?> &mdash; how often each item was placed correctly</span>
        </div>
        <div class="separator separator-dashed mx-6 mb-0"></div>
        <div class="card-body p-0">
            <?php if (empty($itemStats)): ?>
            <div class="text-center py-12 text-muted">
                <i class="ki-duotone ki-abstract-26 fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
                <div class="fs-7 fw-semibold">No items in this assessment yet.</div>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-200 align-middle mb-0" style="min-width:600px;">
                    <thead>
                        <tr class="fw-bold text-muted fs-8" style="background:#f9f9f9;">
                            <th class="ps-7 w-55px py-4">#</th>
                            <th class="py-4">Item</th>
                            <th class="py-4">Correct Zone</th>
                            <th class="text-center w-90px py-4">Attempts</th>
                            <th class="text-center w-90px py-4 text-success">Correct</th>
                            <th class="text-center w-90px py-4 text-danger">Wrong</th>
                            <th class="w-200px pe-7 py-4">Success Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($itemStats as $item):
                        $barColor  = $item['pct'] >= 70 ? '#50cd89' : ($item['pct'] >= 50 ? '#009ef7' : '#f1416c');
                        $textColor = $item['pct'] >= 70 ? 'text-success' : ($item['pct'] >= 50 ? 'text-info' : 'text-danger');
                    ?>
                    <tr>
                        <td class="ps-7 py-4">
                            <div class="symbol symbol-35px">
                                <div class="symbol-label bg-light-primary fw-bold text-primary fs-8"><?= $item['number'] ?></div>
                            </div>
                        </td>
                        <td class="fw-semibold text-gray-800 fs-7 py-4" style="max-width:260px;"><?= esc($item['item_text']) ?></td>
                        <td class="py-4">
                            <span class="badge badge-light-primary fs-9"><?= esc($item['correct_zone']) ?></span>
                        </td>
                        <td class="text-center fw-bold text-gray-700 fs-7 py-4"><?= $item['total'] ?></td>
                        <td class="text-center fw-bold text-success fs-7 py-4"><?= $item['correct'] ?></td>
                        <td class="text-center fw-bold text-danger fs-7 py-4"><?= $item['incorrect'] ?></td>
                        <td class="pe-7 py-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-grow-1" style="height:8px;background:#f1f1f4;border-radius:4px;">
                                    <div style="height:8px;width:<?= $item['pct'] ?>%;background:<?= $barColor ?>;border-radius:4px;transition:width .5s;"></div>
                                </div>
                                <span class="fw-bold <?= $textColor ?> fs-8 flex-shrink-0" style="min-width:40px;text-align:right;"><?= $item['pct'] ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!--end::Per-item table-->

    <!--begin::Attempts list-->
    <?php if ($totalAttempts > 0): ?>
    <div class="card border-0 shadow-sm mb-6" style="overflow:hidden;">
        <div class="card-header border-0 pt-5 pb-4">
            <h5 class="fw-bold text-gray-800 fs-6 mb-0">Student Attempts</h5>
            <span class="text-muted fs-9"><?= $totalAttempts ?> completed attempt<?= $totalAttempts !== 1 ? 's' : '' ?></span>
        </div>
        <div class="separator separator-dashed mx-6 mb-0"></div>
        <div class="card-body px-6 pt-5 pb-6">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3 mb-0" id="attempts_table">
                    <thead>
                        <tr class="fw-bold text-muted fs-8" style="background:#f9f9f9;">
                            <th class="ps-0 py-3">Student</th>
                            <th class="text-center w-100px py-3">Score</th>
                            <th class="text-center w-150px py-3">Correct / Total Items</th>
                            <th class="pe-0 w-160px py-3">Submitted</th>
                        </tr>
                    </thead>
                    <tbody id="attempts_tbody">
                        <tr><td colspan="4" class="text-center py-6"><span class="spinner-border spinner-border-sm text-primary"></span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--end::Attempts list-->

</div>
</div>

<script>
const DIST_DATA      = <?= json_encode(array_values($distribution)) ?>;
const TOTAL_ATTEMPTS = <?= (int) $totalAttempts ?>;
const ENROLLED_CNT   = <?= (int) $enrolledCount ?>;
const ATTEMPTS_URL   = '<?= base_url("classroom/teacher/{$schSubId}/lesson/{$lessonId}/dragdrop/{$quizzeId}/attempts") ?>';

document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    new ApexCharts(document.getElementById('chart_distribution'), {
        series: [{ name: 'Students', data: DIST_DATA }],
        chart: { type: 'bar', height: 280, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 6, columnWidth: '50%', dataLabels: { position: 'top' } } },
        dataLabels: { enabled: true, offsetY: -18, style: { fontSize: '12px', colors: ['#5e6278'] } },
        xaxis: {
            categories: ['0–19%', '20–39%', '40–59%', '60–79%', '80–100%'],
            labels: { style: { fontSize: '12px', colors: '#a1a5b7' } },
            axisBorder: { show: false }, axisTicks: { show: false }
        },
        yaxis: {
            title: { text: 'Number of Students', style: { fontSize: '12px', color: '#a1a5b7' } },
            labels: { style: { colors: '#a1a5b7' }, formatter: v => Math.round(v) },
            min: 0
        },
        colors: ['#009ef7'],
        grid: { strokeDashArray: 4, borderColor: '#f1f1f4' },
        tooltip: { y: { formatter: v => v + ' student' + (v !== 1 ? 's' : '') } }
    }).render();

    if (ENROLLED_CNT > 0) {
        const notYet = ENROLLED_CNT - TOTAL_ATTEMPTS;
        new ApexCharts(document.getElementById('chart_participation'), {
            series: [TOTAL_ATTEMPTS, notYet < 0 ? 0 : notYet],
            chart: { type: 'donut', height: 220 },
            labels: ['Attempted', 'Not yet'],
            colors: ['#009ef7', '#f1f1f4'],
            legend: { show: false },
            dataLabels: { formatter: (val, opts) => opts.w.config.series[opts.seriesIndex] },
            plotOptions: { pie: { donut: { size: '65%',
                labels: { show: true, total: { show: true, label: 'Enrolled', fontSize: '13px',
                    formatter: () => ENROLLED_CNT + ' student' + (ENROLLED_CNT !== 1 ? 's' : '') } } } } },
            stroke: { width: 2 }
        }).render();
    }
});

<?php if ($totalAttempts > 0): ?>
$.get(ATTEMPTS_URL, function(res) {
    if (!res.success || !res.attempts.length) {
        document.getElementById('attempts_tbody').innerHTML =
            '<tr><td colspan="4" class="text-center text-muted py-6 fs-8">No data available.</td></tr>';
        return;
    }
    const rows = res.attempts.map(function(a) {
        const sc    = parseFloat(a.score);
        const color = sc >= 70 ? 'success' : (sc >= 50 ? 'warning' : 'danger');
        return `<tr>
            <td class="ps-0 fw-semibold text-gray-800 fs-7">${a.student_name}</td>
            <td class="text-center"><span class="fw-bold text-${color} fs-6">${sc.toFixed(1)}%</span></td>
            <td class="text-center text-muted fs-8">${a.correct_items} / ${a.total_items}</td>
            <td class="pe-0 text-muted fs-8">${a.submitted_at || '—'}</td>
        </tr>`;
    }).join('');
    document.getElementById('attempts_tbody').innerHTML = rows;

    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#attempts_table').DataTable({
            pageLength: 20, order: [[1, 'desc']],
            language: { search: '', searchPlaceholder: 'Search students…', lengthMenu: 'Show _MENU_',
                info: '_START_–_END_ of _TOTAL_', paginate: { previous: '‹', next: '›' } },
            dom: '<"d-flex align-items-center justify-content-between mb-4"lf>rt<"d-flex justify-content-between mt-4"ip>'
        });
    }
}, 'json');
<?php endif; ?>
</script>
