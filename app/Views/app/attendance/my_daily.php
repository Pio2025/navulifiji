<?php
$streamName = '';
if (!empty($streamInfo)) {
    $streamName = $streamInfo['stream_name'] ?? '';
    if (!empty($streamInfo['level_name'])) $streamName .= ' (' . $streamInfo['level_name'] . ')';
}
$ss       = $summaryStats ?? ['present'=>0,'absent'=>0,'unmarked'=>0,'numMarked'=>0,'pct'=>0];
$pctColor = $ss['pct'] >= 80 ? 'success' : ($ss['pct'] >= 70 ? 'warning' : 'danger');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($termLabel ?? 'Term') ?> <?= (int)($termNo ?? 0) ?> — My Daily Attendance
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Daily Attendance</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($streamId && $termNo): ?>
            <a href="<?= base_url('attendance/my/daily/pdf?stream_id='.(int)$streamId.'&term='.(int)$termNo) ?>"
               class="btn btn-sm btn-light-danger" target="_blank">
                <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Download PDF
            </a>
            <button type="button" onclick="window.print()" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-printer fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                Print
            </button>
            <?php endif; ?>
            <button type="button" onclick="history.back()" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Back
            </button>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if (!$streamId || !$termNo): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-16 text-muted">
            <i class="ki-duotone ki-information-5 fs-4x text-gray-200 mb-4">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <div class="fs-6 fw-semibold text-gray-600 mb-2">No Classroom or Term Selected</div>
            <div class="fs-7 mb-5">Please navigate here from your classroom's Attendance tab.</div>
            <a href="<?= base_url('classroom/my') ?>" class="btn btn-primary btn-sm">Go to My Classroom</a>
        </div>
    </div>
    <?php else: ?>

    <!--begin::Stream header-->
    <div class="d-flex align-items-center gap-3 mb-6">
        <div class="d-flex align-items-center justify-content-center bg-light-success rounded-2" style="width:48px;height:48px;">
            <i class="ki-duotone ki-calendar-tick fs-2 text-success">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                <span class="path4"></span><span class="path5"></span><span class="path6"></span>
            </i>
        </div>
        <div>
            <div class="fw-bold text-gray-900 fs-5"><?= esc($streamName) ?></div>
            <div class="text-muted fs-7">
                <?= esc($termLabel) ?> <?= (int)$termNo ?>
                &nbsp;·&nbsp; <?= count($weeks) ?> weeks
                <?php if (!empty($termInfo['term_start_month'])): ?>
                &nbsp;·&nbsp;
                <?= (int)$termInfo['term_start_day'] ?> <?= date('M', mktime(0,0,0,(int)$termInfo['term_start_month'],1)) ?>
                – <?= (int)$termInfo['term_end_day'] ?> <?= date('M', mktime(0,0,0,(int)$termInfo['term_end_month'],1)) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--end::Stream header-->

    <!--begin::Summary cards-->
    <div class="row g-4 mb-6">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold fs-1 text-gray-900 mb-1"><?= $ss['numMarked'] ?></div>
                    <div class="text-muted fs-7">Days Marked</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold fs-1 text-success mb-1"><?= $ss['present'] ?></div>
                    <div class="text-muted fs-7">Present</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold fs-1 text-danger mb-1"><?= $ss['absent'] ?></div>
                    <div class="text-muted fs-7">Absent</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold fs-1 text-<?= $pctColor ?> mb-1"><?= $ss['pct'] ?>%</div>
                    <div class="text-muted fs-7">Attendance Rate</div>
                    <?php if ($ss['numMarked'] > 0): ?>
                    <div class="progress mt-2" style="height:4px;">
                        <div class="progress-bar bg-<?= $pctColor ?>" style="width:<?= $ss['pct'] ?>%"></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!--end::Summary cards-->

    <!--begin::Attendance grid-->
    <?php if (empty($weeks)): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-10 text-muted fs-7">
            Term dates not configured. Please ask your administrator.
        </div>
    </div>
    <?php else: ?>
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-5 pb-3">
            <h3 class="card-title fw-bold text-gray-800 fs-6">Weekly Attendance Grid</h3>
            <div class="card-toolbar gap-3">
                <span class="d-flex align-items-center gap-1 fs-8">
                    <span class="my-att-dot present"></span> Present
                </span>
                <span class="d-flex align-items-center gap-1 fs-8">
                    <span class="my-att-dot absent"></span> Absent
                </span>
                <span class="d-flex align-items-center gap-1 fs-8">
                    <span class="my-att-dot unmarked"></span> Not marked
                </span>
                <span class="d-flex align-items-center gap-1 fs-8">
                    <span class="my-att-dot holiday"></span> Public Holiday
                </span>
            </div>
        </div>
        <div class="card-body pt-0 pb-6">
            <div class="my-att-wrapper">
                <table class="my-att-table">
                    <thead>
                        <tr>
                            <th class="my-att-day-head"></th>
                            <?php foreach ($weeks as $wi => $weekDays): ?>
                            <th class="my-att-week-head">W<?= $wi + 1 ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($dayNames as $dk):  ?>
                        <tr>
                            <td class="my-att-day-label"><?= $dk ?></td>
                            <?php foreach ($weeks as $wi => $weekDays):
                                $date      = $weekDays[$dk] ?? '';
                                $isFuture  = $date > $today;
                                $isHoliday = isset($holidays[$date]);
                                $status    = ($isFuture || $isHoliday) ? null : ($attendance[$date] ?? null);
                                $isPresent = $status === 'Present';
                                $isAbsent  = $status === 'Absent';
                            ?>
                            <td class="my-att-cell <?= $wi % 2 === 0 ? 'col-even' : 'col-odd' ?>">
                                <?php if ($isFuture): ?>
                                    <span class="my-att-val future">—</span>
                                <?php elseif ($isHoliday): ?>
                                    <span class="my-att-val holiday" title="<?= esc($holidays[$date]) ?>">H</span>
                                <?php elseif ($isPresent): ?>
                                    <span class="my-att-val present" title="Present · <?= esc($date) ?>">✓</span>
                                <?php elseif ($isAbsent): ?>
                                    <span class="my-att-val absent" title="Absent · <?= esc($date) ?>">✗</span>
                                <?php else: ?>
                                    <span class="my-att-val unmarked" title="Not marked · <?= esc($date) ?>">—</span>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php
            // Compute weekly stats for charts
            $weeklyPcts   = [];
            $weeklyLabels = [];
            foreach ($weeks as $wi => $weekDays) {
                $wP = $wA = 0;
                foreach ($weekDays as $dt) {
                    if ($dt > $today) continue;
                    $st = $attendance[$dt] ?? null;
                    if ($st === 'Present')    $wP++;
                    elseif ($st === 'Absent') $wA++;
                }
                $wTotal = $wP + $wA;
                $weeklyPcts[]   = $wTotal > 0 ? round($wP / $wTotal * 100, 1) : 0;
                $weeklyLabels[] = 'W' . ($wi + 1);
            }
            ?>
            <!--begin::Charts-->
            <div class="mt-6 row g-4">
                <div class="col-lg-5">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-header border-0 pt-4 pb-0">
                            <h3 class="card-title fw-bold text-gray-800 fs-7">Overall Attendance</h3>
                        </div>
                        <div class="card-body pt-0 d-flex align-items-center justify-content-center py-3">
                            <div id="my_att_donut" style="width:240px;height:240px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-header border-0 pt-4 pb-0">
                            <h3 class="card-title fw-bold text-gray-800 fs-7">Weekly Attendance Rate (%)</h3>
                        </div>
                        <div class="card-body pt-0 py-3">
                            <div id="my_att_weekly" style="min-height:210px"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Charts-->
        </div>
    </div>
    <?php endif; ?>
    <!--end::Attendance grid-->

    <?php endif; ?>

</div>
</div>

<style>
.my-att-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.my-att-table {
    border-collapse: separate;
    border-spacing: 5px;
    width: max-content;
    min-width: 320px;
}
.my-att-day-head { width: 52px; }
.my-att-week-head {
    text-align: center;
    font-weight: 700;
    font-size: 13px;
    color: #e6a817;
    background: #fff8e6;
    border-radius: 8px;
    padding: 8px 12px;
    min-width: 58px;
    letter-spacing: .5px;
}
.my-att-day-label {
    font-weight: 700;
    font-size: 13px;
    color: #1a56db;
    text-align: right;
    padding-right: 10px;
    background: #eef2ff;
    border-radius: 8px;
    padding: 8px 12px;
    white-space: nowrap;
}
.my-att-cell {
    text-align: center;
    padding: 4px;
    border-radius: 8px;
}
.my-att-cell.col-even { background: #fafafa; }
.my-att-cell.col-odd  { background: #f4f6ff; }
.my-att-val {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 800;
    cursor: default;
}
.my-att-val.present  { background: #d1fae5; color: #065f46; }
.my-att-val.absent   { background: #fee2e2; color: #991b1b; }
.my-att-val.unmarked { background: #f3f4f6; color: #9ca3af; font-size: 13px; }
.my-att-val.future   { background: transparent; color: #d1d5db; font-size: 13px; }
.my-att-val.holiday  { background: #ede9fe; color: #6d28d9; font-size: 13px; font-weight: 800; }
/* Legend dots */
.my-att-dot {
    display: inline-block;
    width: 12px; height: 12px;
    border-radius: 3px;
}
.my-att-dot.present  { background: #d1fae5; border: 1px solid #059669; }
.my-att-dot.absent   { background: #fee2e2; border: 1px solid #dc2626; }
.my-att-dot.unmarked { background: #f3f4f6; border: 1px solid #9ca3af; }
.my-att-dot.holiday  { background: #ede9fe; border: 1px solid #7c3aed; }

/* chart cards match grid card look */
#my_att_donut, #my_att_weekly { width:100%; }

@media print {
    @page { size: A4 landscape; margin: 10mm 12mm; }
    #kt_app_header, #kt_app_sidebar, #kt_app_sidebar_toggle,
    .app-toolbar, .btn, .card-toolbar { display: none !important; }
    body, html, #kt_app_body, #kt_app_wrapper,
    #kt_app_content, #kt_app_content_container,
    .app-content, .app-container {
        margin: 0 !important; padding: 0 !important;
        width: 100% !important; max-width: 100% !important;
        display: block !important;
    }
    .card { border: none !important; box-shadow: none !important; }
    .card-header { padding-bottom: 4px !important; }
    .my-att-wrapper { overflow: visible !important; }
    .my-att-table { border-spacing: 3px; }
    .my-att-val { width: 28px; height: 28px; font-size: 13px; }
    .my-att-val.present  { background: #d1fae5 !important; color: #065f46 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .my-att-val.absent   { background: #fee2e2 !important; color: #991b1b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .my-att-val.holiday  { background: #ede9fe !important; color: #6d28d9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .my-att-week-head, .my-att-day-label {
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
}
</style>

<script>
(function () {
    if (typeof ApexCharts === 'undefined') return;

    var present  = <?= (int)($ss['present']  ?? 0) ?>;
    var absent   = <?= (int)($ss['absent']   ?? 0) ?>;
    var unmarked = <?= (int)($ss['unmarked'] ?? 0) ?>;
    var pct      = <?= (float)($ss['pct']    ?? 0) ?>;

    var weeklyLabels = <?= json_encode($weeklyLabels ?? []) ?>;
    var weeklyPcts   = <?= json_encode($weeklyPcts   ?? []) ?>;

    // ── Donut — overall attendance ───────────────────────────────────────
    var donutEl = document.querySelector('#my_att_donut');
    if (donutEl && (present + absent + unmarked) > 0) {
        new ApexCharts(donutEl, {
            chart: { type: 'donut', height: 240, toolbar: { show: false },
                animations: { enabled: true, speed: 600 } },
            series: [present, absent, unmarked],
            labels: ['Present', 'Absent', 'Not Marked'],
            colors: ['#059669', '#ef4444', '#d1d5db'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '62%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Attendance',
                                fontSize: '12px',
                                fontWeight: 700,
                                color: '#374151',
                                formatter: function () { return pct + '%'; }
                            },
                            value: { fontSize: '16px', fontWeight: 700 }
                        }
                    }
                }
            },
            legend: { position: 'bottom', fontSize: '12px' },
            dataLabels: { enabled: true, formatter: function (v) { return Math.round(v) + '%'; },
                style: { fontSize: '11px', fontWeight: 700 } },
            tooltip: { y: { formatter: function (v) { return v + ' days'; } } },
        }).render();
    } else if (donutEl) {
        donutEl.innerHTML = '<div class="text-center text-muted fs-7 py-10">No attendance recorded yet</div>';
    }

    // ── Bar — weekly attendance rate ─────────────────────────────────────
    var wkEl = document.querySelector('#my_att_weekly');
    if (wkEl && weeklyLabels.length > 0) {
        new ApexCharts(wkEl, {
            chart: { type: 'bar', height: 210, toolbar: { show: false },
                animations: { enabled: true, speed: 600 } },
            plotOptions: { bar: { columnWidth: '55%', borderRadius: 5,
                dataLabels: { position: 'top' } } },
            dataLabels: { enabled: true,
                formatter: function (v) { return v > 0 ? v + '%' : ''; },
                style: { fontSize: '10px', fontWeight: 700 }, offsetY: -16 },
            series: [{ name: 'Attendance %', data: weeklyPcts }],
            xaxis: { categories: weeklyLabels, labels: { style: { fontSize: '11px' } } },
            yaxis: { min: 0, max: 100,
                labels: { formatter: function (v) { return v + '%'; }, style: { fontSize: '11px' } } },
            colors: weeklyPcts.map(function (p) {
                return p >= 90 ? '#059669' : p >= 80 ? '#10b981' : p >= 70 ? '#f59e0b' : '#ef4444';
            }),
            grid: { yaxis: { lines: { show: true } }, padding: { top: 16 } },
            tooltip: { y: { formatter: function (v) { return v + '%'; } } },
        }).render();
    }
})();
</script>
