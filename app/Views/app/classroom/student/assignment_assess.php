<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">My Assessment</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/student/'.$classSubId.'/assignments') ?>" class="text-muted text-hover-primary">Assignments</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Assessment</li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/student/'.$classSubId.'/assignments') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$totalScore = (float)($assignment['assignment_total_score'] ?? 100);
$isPastDue  = !empty($assignment['assignment_due_date']) && strtotime($assignment['assignment_due_date']) < time();
$submitted  = !empty($submission);
$graded     = $submitted && !empty($score);
$mark       = $graded ? (float)$score['assignment_mark'] : null;
$pct        = ($graded && $totalScore > 0) ? round(($mark / $totalScore) * 100, 1) : null;
$avgPct     = (!empty($classStats['avg_mark']) && $totalScore > 0) ? round(($classStats['avg_mark'] / $totalScore) * 100, 1) : null;
$grade      = $pct === null ? '—' : ($pct >= 80 ? 'A' : ($pct >= 65 ? 'B' : ($pct >= 50 ? 'C' : ($pct >= 40 ? 'D' : 'F'))));
$scoreColor = $pct === null ? 'primary' : ($pct >= 70 ? 'success' : ($pct >= 50 ? 'warning' : 'danger'));
$asgnFiles  = $assignment['files'] ?? [];
?>

<!--begin::Assignment info bar-->
<div class="card border-0 shadow-sm mb-6">
    <div class="card-body px-6 py-4">
        <div class="d-flex align-items-center flex-wrap gap-5">
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-document fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                <span class="fw-bold text-gray-800 fs-6"><?= esc($assignment['assignment_name']) ?></span>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted fs-8">
                <i class="ki-duotone ki-award fs-4 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Total: <strong class="text-gray-700 ms-1"><?= $totalScore ?> marks</strong>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted fs-8">
                <i class="ki-duotone ki-calendar fs-4 <?= $isPastDue ? 'text-danger' : 'text-warning' ?>"><span class="path1"></span><span class="path2"></span></i>
                Due: <strong class="<?= $isPastDue ? 'text-danger' : 'text-gray-700' ?> ms-1"><?= !empty($assignment['assignment_due_date']) ? date('d M Y, g:i A', strtotime($assignment['assignment_due_date'])) : '—' ?></strong>
            </div>
        </div>
    </div>
</div>
<!--end::Assignment info bar-->

<?php if (!empty($asgnFiles)): ?>
<!--begin::Assignment files-->
<div class="card border-0 shadow-sm mb-6">
    <div class="card-header border-0 pt-5 pb-3 px-6">
        <div class="d-flex align-items-center gap-2">
            <i class="ki-duotone ki-folder-up fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
            <h5 class="fw-bold text-gray-800 fs-5 mb-0">Assignment Files</h5>
        </div>
        <span class="badge badge-light-primary fs-9"><?= count($asgnFiles) ?> file<?= count($asgnFiles) !== 1 ? 's' : '' ?></span>
    </div>
    <div class="card-body px-6 pb-6 pt-3">
        <div class="d-flex flex-wrap gap-3">
        <?php foreach ($asgnFiles as $aFile):
            $aExt = strtolower(pathinfo($aFile['file_src'], PATHINFO_EXTENSION));
            $aUrl = base_url('uploads/assignments/' . $aFile['file_src']);
            [$aIcon, $aColor, $aBg] = match($aExt) {
                'pdf'                          => ['ki-file-down',   'text-danger',  '#fff5f5'],
                'jpg','jpeg','png','gif','webp' => ['ki-picture',     'text-primary', '#eff6ff'],
                'doc','docx'                   => ['ki-file',         'text-info',    '#f0f9ff'],
                'xls','xlsx'                   => ['ki-chart-simple', 'text-success', '#f0fdf4'],
                'ppt','pptx'                   => ['ki-file',         'text-warning', '#fffbeb'],
                'zip','tar','gz','rar'         => ['ki-folder',       'text-warning', '#fffbeb'],
                default                        => ['ki-file',         'text-muted',   '#f9fafb'],
            };
        ?>
        <a href="<?= $aUrl ?>" target="_blank" class="text-decoration-none" title="<?= esc($aFile['file_src']) ?>">
            <div class="rounded-2 border text-center py-3 px-3" style="background:<?= $aBg ?>;min-width:90px;">
                <i class="ki-duotone <?= $aIcon ?> fs-2x <?= $aColor ?> d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                <span class="badge badge-light-secondary fs-10"><?= strtoupper($aExt) ?></span>
            </div>
        </a>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<!--end::Assignment files-->
<?php endif; ?>

<?php if (!$submitted): ?>
<!--begin::Not submitted-->
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-16">
        <i class="ki-duotone ki-send fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span></i>
        <div class="fw-bold text-gray-700 fs-5 mb-2">You haven't submitted this assignment yet.</div>
        <div class="text-muted fs-7 mb-6">Submit your work to receive a grade.</div>
        <a href="<?= base_url('classroom/student/'.$classSubId.'/assignment/'.$assignment['assignment_id'].'/submit') ?>" class="btn btn-primary">
            <i class="ki-duotone ki-send fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>Submit Now
        </a>
    </div>
</div>
<!--end::Not submitted-->

<?php elseif (!$graded): ?>
<!--begin::Awaiting marking-->
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-14">
        <i class="ki-duotone ki-time fs-4x text-warning mb-4"><span class="path1"></span><span class="path2"></span></i>
        <div class="fw-bold text-gray-700 fs-5 mb-2">Awaiting Marking</div>
        <div class="text-muted fs-7 mb-6">Your submission has been received. Your teacher will mark it soon.</div>
        <div class="d-inline-flex align-items-center gap-2 bg-light-success rounded-2 px-4 py-3">
            <i class="ki-duotone ki-check-circle fs-4 text-success"><span class="path1"></span><span class="path2"></span></i>
            <div class="text-start">
                <div class="fw-bold text-gray-700 fs-8">Submitted <?= $submission['submission_status'] === 'Late' ? '<span class="badge badge-light-warning ms-1 fs-9">Late</span>' : '' ?></div>
                <div class="text-muted fs-9"><?= date('d M Y, g:i A', strtotime($submission['submitted_at'])) ?></div>
            </div>
        </div>
    </div>
</div>
<!--end::Awaiting marking-->

<?php else: ?>
<!--begin::Graded results-->
<div class="row g-6 mb-6">

    <!--begin::Score ring-->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-10">
                <div class="position-relative mb-4" style="width:160px;height:160px;">
                    <svg viewBox="0 0 160 160" style="transform:rotate(-90deg);width:160px;height:160px;">
                        <circle cx="80" cy="80" r="68" fill="none" stroke="#f1f5f9" stroke-width="14"/>
                        <circle cx="80" cy="80" r="68" fill="none"
                                stroke="<?= $pct >= 70 ? '#22c55e' : ($pct >= 50 ? '#f59e0b' : '#ef4444') ?>"
                                stroke-width="14" stroke-linecap="round"
                                stroke-dasharray="427.3"
                                stroke-dashoffset="<?= 427.3 - (427.3 * min($pct,100) / 100) ?>"/>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="fw-bold fs-2x text-<?= $scoreColor ?>"><?= $pct ?>%</div>
                        <div class="fw-bold text-gray-500 fs-8">Grade <?= $grade ?></div>
                    </div>
                </div>
                <div class="fw-bold text-gray-800 fs-3 mb-1"><?= $mark ?> / <?= $totalScore ?></div>
                <div class="text-muted fs-8">Your Score</div>
            </div>
        </div>
    </div>
    <!--end::Score ring-->

    <!--begin::Stats + comparison-->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5 pb-3 px-6">
                <h5 class="fw-bold text-gray-800 fs-6 mb-0">Class Comparison</h5>
            </div>
            <div class="card-body px-6 pb-6 pt-2">
                <div id="comparisonChart" style="min-height:180px;"></div>
                <div class="row g-4 mt-2">
                    <div class="col-4 text-center">
                        <div class="fw-bold text-primary fs-4"><?= $classStats['avg_mark'] !== null ? $classStats['avg_mark'] : '—' ?></div>
                        <div class="text-muted fs-9">Class Avg</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="fw-bold text-success fs-4"><?= $classStats['high_mark'] ?? '—' ?></div>
                        <div class="text-muted fs-9">Highest</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="fw-bold text-danger fs-4"><?= $classStats['low_mark'] ?? '—' ?></div>
                        <div class="text-muted fs-9">Lowest</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Stats + comparison-->
</div>

<!--begin::Feedback + submission info-->
<div class="row g-6">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5 pb-3 px-6">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-message-text-2 fs-3 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <h5 class="fw-bold text-gray-800 fs-6 mb-0">Teacher Feedback</h5>
                </div>
            </div>
            <div class="card-body px-6 pb-6 pt-3">
                <?php if (!empty($score['feedback'])): ?>
                <div class="p-4 rounded-2 bg-light-primary border border-dashed border-primary-subtle fs-7 text-gray-700 lh-lg">
                    <?= nl2br(esc($score['feedback'])) ?>
                </div>
                <?php else: ?>
                <div class="text-center py-6 text-muted fs-8 fst-italic">No feedback provided.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5 pb-3 px-6">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-file fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    <h5 class="fw-bold text-gray-800 fs-6 mb-0">Submission Details</h5>
                </div>
            </div>
            <div class="card-body px-6 pb-6 pt-3">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f1f4;">
                        <span class="text-muted fs-8">Submitted</span>
                        <span class="fw-semibold fs-8"><?= date('d M Y, g:i A', strtotime($submission['submitted_at'])) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f1f4;">
                        <span class="text-muted fs-8">Status</span>
                        <span class="badge badge-light-<?= $submission['submission_status']==='Late'?'warning':'success' ?> fs-9"><?= $submission['submission_status'] ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f1f4;">
                        <span class="text-muted fs-8">Marked</span>
                        <span class="fw-semibold fs-8"><?= $score['graded_at'] ? date('d M Y', strtotime($score['graded_at'])) : '—' ?></span>
                    </div>
                    <div class="pt-1">
                        <a href="<?= base_url('uploads/assignment_submissions/'.$submission['submission_file']) ?>" target="_blank" class="btn btn-sm btn-light-primary w-100">
                            <i class="ki-duotone ki-file-down fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Download My Submission
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Feedback + submission info-->
<?php endif; ?>

</div>
</div>

<?php if ($graded): ?>
<script>
var options = {
    series: [{
        name: 'Score (%)',
        data: [
            <?= $pct ?>,
            <?= $avgPct ?? 'null' ?>,
            <?= (!empty($classStats['high_mark']) && $totalScore > 0) ? round(($classStats['high_mark']/$totalScore)*100,1) : 'null' ?>,
            <?= (!empty($classStats['low_mark']) && $totalScore > 0) ? round(($classStats['low_mark']/$totalScore)*100,1) : 'null' ?>
        ]
    }],
    chart: { type: 'bar', height: 180, toolbar: { show: false }, sparkline: { enabled: false } },
    plotOptions: { bar: { borderRadius: 6, columnWidth: '40%', distributed: true } },
    colors: ['#3b82f6', '#94a3b8', '#22c55e', '#ef4444'],
    dataLabels: { enabled: true, formatter: v => v !== null ? v + '%' : '—', style: { fontSize: '11px', fontWeight: 600 } },
    xaxis: { categories: ['You', 'Class Avg', 'Highest', 'Lowest'], labels: { style: { fontSize: '11px' } } },
    yaxis: { max: 100, labels: { formatter: v => v + '%' } },
    legend: { show: false },
    tooltip: { y: { formatter: v => v + '%' } },
    grid: { borderColor: '#f1f5f9' }
};
new ApexCharts(document.getElementById('comparisonChart'), options).render();
</script>
<?php endif; ?>
