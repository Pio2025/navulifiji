<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Assignment Analysis</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/teacher/'.$schSubId.'/assignments') ?>" class="text-muted text-hover-primary">Assignments</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Analysis</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('classroom/teacher/'.$schSubId.'/assignment/'.$assignment['assignment_id'].'/mark') ?>" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-pencil fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Mark Assignment
            </a>
            <a href="<?= base_url('classroom/teacher/'.$schSubId.'/assignments') ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>Back
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$totalSub    = (int)($subStats['total_submitted'] ?? 0);
$totalGraded = (int)($subStats['total_graded'] ?? 0);
$totalLate   = (int)($subStats['total_late'] ?? 0);
$notSub      = $enrolledCount - $totalSub;
$avgMark     = $scoreStats['avg_mark'] ?? null;
$highMark    = $scoreStats['high_mark'] ?? null;
$lowMark     = $scoreStats['low_mark'] ?? null;
$avgPct      = ($avgMark !== null && $totalScore > 0) ? round(($avgMark / $totalScore) * 100, 1) : null;
$isPastDue   = !empty($assignment['assignment_due_date']) && strtotime($assignment['assignment_due_date']) < time();
?>

<!--begin::Assignment info bar-->
<div class="card border-0 shadow-sm mb-6">
    <div class="card-body px-6 py-4">
        <div class="d-flex align-items-center flex-wrap gap-6">
            <div>
                <div class="fw-bold text-gray-800 fs-5 mb-1"><?= esc($assignment['assignment_name']) ?></div>
                <div class="text-muted fs-8">Posted by <?= esc($assignment['creator_name'] ?? '—') ?> &bull;
                    <?= !empty($assignment['assignment_due_date']) ? 'Due ' . date('d M Y', strtotime($assignment['assignment_due_date'])) : 'No due date' ?>
                    <?= $isPastDue ? '<span class="badge badge-light-danger ms-1 fs-9">Past Due</span>' : '' ?>
                </div>
            </div>
            <div class="ms-auto d-flex align-items-center gap-2">
                <span class="badge badge-light-<?= $assignment['assignment_status']==='Published'?'success':($assignment['assignment_status']==='Draft'?'warning':'secondary') ?> fs-8"><?= $assignment['assignment_status'] ?></span>
                <span class="fw-semibold text-muted fs-8">Total: <?= $totalScore ?> marks</span>
            </div>
        </div>
    </div>
</div>
<!--end::Assignment info bar-->

<!--begin::Stats row-->
<div class="row g-5 mb-6">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-4 p-5">
                <div class="symbol symbol-50px">
                    <div class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-people fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </div>
                </div>
                <div>
                    <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $enrolledCount ?></div>
                    <div class="text-muted fs-8">Enrolled</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-4 p-5">
                <div class="symbol symbol-50px">
                    <div class="symbol-label bg-light-success">
                        <i class="ki-duotone ki-send fs-2 text-success"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div>
                    <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $totalSub ?></div>
                    <div class="text-muted fs-8">Submitted</div>
                    <?php if ($totalLate > 0): ?>
                    <div class="badge badge-light-warning fs-9 mt-1"><?= $totalLate ?> Late</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-4 p-5">
                <div class="symbol symbol-50px">
                    <div class="symbol-label bg-light-info">
                        <i class="ki-duotone ki-pencil fs-2 text-info"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div>
                    <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $totalGraded ?></div>
                    <div class="text-muted fs-8">Graded</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-4 p-5">
                <div class="symbol symbol-50px">
                    <div class="symbol-label bg-light-danger">
                        <i class="ki-duotone ki-cross-circle fs-2 text-danger"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div>
                    <div class="fw-bold text-gray-800 fs-2x lh-1 mb-1"><?= $notSub ?></div>
                    <div class="text-muted fs-8">Not Submitted</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Stats row-->

<!--begin::Score stats row-->
<?php if ($totalGraded > 0): ?>
<div class="row g-5 mb-6">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-6">
                <div class="fw-bold text-primary fs-2x mb-1"><?= $avgMark ?></div>
                <div class="text-muted fs-8">Average Mark</div>
                <div class="badge badge-light-primary fs-9 mt-1"><?= $avgPct ?>%</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-6">
                <div class="fw-bold text-success fs-2x mb-1"><?= $highMark ?></div>
                <div class="text-muted fs-8">Highest Mark</div>
                <div class="badge badge-light-success fs-9 mt-1"><?= $totalScore > 0 ? round(($highMark/$totalScore)*100,1) : 0 ?>%</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-6">
                <div class="fw-bold text-danger fs-2x mb-1"><?= $lowMark ?></div>
                <div class="text-muted fs-8">Lowest Mark</div>
                <div class="badge badge-light-danger fs-9 mt-1"><?= $totalScore > 0 ? round(($lowMark/$totalScore)*100,1) : 0 ?>%</div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!--end::Score stats row-->

<!--begin::Charts row-->
<div class="row g-5 mb-6">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5 pb-3 px-6">
                <h5 class="fw-bold text-gray-800 fs-6 mb-0">Score Distribution</h5>
                <span class="text-muted fs-8">Percentage bins across graded submissions</span>
            </div>
            <div class="card-body px-4 pb-4 pt-0">
                <div id="scoreDistChart" style="min-height:260px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pt-5 pb-3 px-6">
                <h5 class="fw-bold text-gray-800 fs-6 mb-0">Submission Status</h5>
            </div>
            <div class="card-body px-4 pb-4 pt-0 d-flex align-items-center justify-content-center">
                <div id="statusDonutChart" style="min-height:260px;width:100%;"></div>
            </div>
        </div>
    </div>
</div>
<!--end::Charts row-->

<!--begin::Student breakdown table-->
<div class="card border-0 shadow-sm" style="overflow:hidden;">
    <div class="card-header border-0 pt-5 pb-4 px-6">
        <h5 class="fw-bold text-gray-800 fs-6 mb-0">Student Breakdown</h5>
    </div>
    <div class="separator separator-dashed mx-6 mb-0"></div>
    <div class="card-body p-0">
        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-2 mb-0" style="min-width:600px;">
            <thead>
                <tr class="fw-bold text-muted fs-8" style="background:#f9f9f9;">
                    <th class="ps-7 py-4">#</th>
                    <th class="py-4">Student</th>
                    <th class="py-4">Submitted</th>
                    <th class="py-4">Status</th>
                    <th class="py-4">Mark</th>
                    <th class="pe-7 py-4">Percentage</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($students as $i => $st):
                $hasSubmit = !empty($st['submitted_at']);
                $hasGrade  = $st['assignment_mark'] !== null;
                $sPct      = ($hasGrade && $totalScore > 0) ? round(($st['assignment_mark']/$totalScore)*100,1) : null;
                $sColor    = !$hasGrade ? 'secondary' : ($sPct>=70?'success':($sPct>=50?'warning':'danger'));
                $initials  = strtoupper(substr($st['student_name'],0,1));
            ?>
            <tr>
                <td class="ps-7 py-4 text-muted fs-9"><?= $i+1 ?></td>
                <td class="py-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-30px">
                            <?php if (!empty($st['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/'.$st['profile_photo']) ?>" class="rounded-circle" style="width:30px;height:30px;object-fit:cover;">
                            <?php else: ?>
                            <div class="symbol-label bg-light-primary fw-bold text-primary fs-9"><?= $initials ?></div>
                            <?php endif; ?>
                        </div>
                        <span class="fw-semibold text-gray-800 fs-8"><?= esc($st['student_name']) ?></span>
                    </div>
                </td>
                <td class="py-4 fs-8 text-gray-600"><?= $hasSubmit ? date('d M Y', strtotime($st['submitted_at'])) : '<span class="text-muted fst-italic">—</span>' ?></td>
                <td class="py-4">
                    <?php if (!$hasSubmit): ?>
                    <span class="badge badge-light-danger fs-9">Not Submitted</span>
                    <?php else: ?>
                    <span class="badge badge-light-<?= $st['submission_status']==='Graded'?'success':($st['submission_status']==='Late'?'warning':'primary') ?> fs-9"><?= $st['submission_status'] ?></span>
                    <?php endif; ?>
                </td>
                <td class="py-4 fw-bold fs-7 text-<?= $sColor ?>">
                    <?= $hasGrade ? $st['assignment_mark'] . ' / ' . $totalScore : '<span class="text-muted fst-italic fw-normal fs-8">—</span>' ?>
                </td>
                <td class="pe-7 py-4">
                    <?php if ($sPct !== null): ?>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="height:7px;border-radius:4px;">
                            <div class="progress-bar bg-<?= $sColor ?>" style="width:<?= $sPct ?>%"></div>
                        </div>
                        <span class="fw-semibold fs-8 text-<?= $sColor ?>" style="min-width:40px;"><?= $sPct ?>%</span>
                    </div>
                    <?php else: ?>
                    <span class="text-muted fs-8">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!--end::Student breakdown table-->

</div>
</div>

<script>
// Score distribution bar chart
new ApexCharts(document.getElementById('scoreDistChart'), {
    series: [{ name: 'Students', data: <?= json_encode($scoreDist) ?> }],
    chart: { type:'bar', height:260, toolbar:{ show:false } },
    plotOptions: { bar:{ borderRadius:6, columnWidth:'50%', distributed:true } },
    colors: ['#ef4444','#f97316','#f59e0b','#3b82f6','#22c55e'],
    dataLabels: { enabled:true, style:{ fontSize:'12px', fontWeight:700 } },
    xaxis: { categories:['0–19%','20–39%','40–59%','60–79%','80–100%'], labels:{ style:{ fontSize:'11px' } } },
    yaxis: { title:{ text:'Students' }, tickAmount:Math.max(...<?= json_encode($scoreDist) ?>), labels:{ formatter:v=>Math.round(v) } },
    legend: { show:false },
    grid: { borderColor:'#f1f5f9' },
    tooltip: { y:{ formatter:v=>v+' student'+(v!==1?'s':'') } }
}).render();

// Submission status donut
new ApexCharts(document.getElementById('statusDonutChart'), {
    series: [<?= $totalGraded ?>, <?= $totalSub - $totalGraded ?>, <?= $notSub ?>],
    chart: { type:'donut', height:260 },
    labels: ['Graded','Submitted','Not Submitted'],
    colors: ['#22c55e','#3b82f6','#ef4444'],
    plotOptions: { pie:{ donut:{ size:'65%', labels:{ show:true, total:{ show:true, label:'Total Students', fontSize:'12px', fontWeight:600, formatter:()=><?= $enrolledCount ?> } } } } },
    legend: { position:'bottom', fontSize:'12px' },
    dataLabels: { formatter:(val,opts)=>opts.w.config.series[opts.seriesIndex] },
    tooltip: { y:{ formatter:v=>v+' student'+(v!==1?'s':'') } }
}).render();
</script>
