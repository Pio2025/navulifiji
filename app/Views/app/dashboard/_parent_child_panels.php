<?php
// Expects $pr_children (array), $pr_announcements (array) and $activeIndex (int|null) to be set by the caller before including.
function pd_grade(float $pct): string {
    if ($pct >= 90) return 'A+';
    if ($pct >= 80) return 'A';
    if ($pct >= 70) return 'B';
    if ($pct >= 50) return 'C';
    return 'F';
}
function pd_grade_cls(float $pct): string {
    if ($pct >= 80) return 'success';
    if ($pct >= 70) return 'primary';
    if ($pct >= 50) return 'warning';
    return 'danger';
}
function pd_grade_color(float $pct): string {
    if ($pct >= 80) return '#50cd89';
    if ($pct >= 70) return '#009ef7';
    if ($pct >= 50) return '#ffc700';
    return '#f1416c';
}
function pd_severity_cls(string $s): string {
    return match($s) {
        'Positive' => 'success',
        'Minor'    => 'warning',
        'Major'    => 'danger',
        'Critical' => 'danger',
        default    => 'secondary',
    };
}
?>
<?php foreach ($pr_children as $i => $c): ?>
<?php
    $cPhoto = $c['photo'] ?? null;
    $cPhotoUrl = ($cPhoto && file_exists(FCPATH . 'uploads/profilePhoto/' . $cPhoto))
                 ? base_url('uploads/profilePhoto/' . $cPhoto)
                 : base_url('app/assets/media/avatars/blank.png');
    $cName     = trim(($c['fname'] ?? '') . ' ' . ($c['lname'] ?? ''));
    $rel       = $c['relationship'] ?? '';
    $enrolYear = (int) ($c['row']['enrol_year'] ?? date('Y'));
    $enrolTerm = max(1, min(3, (int) ($c['row']['enrol_term'] ?? 1)));
    $schName   = $c['row']['sch_name']   ?? '';
    $schLogo   = $c['row']['sch_logo']   ?? '';
    $levelName = $c['row']['level_name'] ?? '';
    $streamName= $c['row']['stream_name'] ?? '';
    $className = $c['classroom']['class_name'] ?? '';
    $classId   = $c['classroom']['class_id_fk'] ?? 0;
    $childId   = $c['user_id'];

    $overallPct   = $c['overall_pct'] ?? null;
    $classRank    = $c['class_rank'] ?? null;
    $classSize    = $c['class_size'] ?? null;
    $attPct       = $c['attendance_pct'] ?? null;
    $attData      = $c['attendance_data'] ?? ['present'=>0,'absent'=>0,'total'=>0];
    $conductNet   = ($c['conduct_positive'] ?? 0) - ($c['conduct_negative'] ?? 0);

    // Prepare marks rows
    $currentMarks = $c['current_marks'] ?? [];
    $marksRows    = $currentMarks['marks'] ?? [];
    $allTermMarks = $c['all_term_marks'] ?? [];

    // Attendance color helper inline
    $attClr = $attPct === null ? '#a1a5b7' : ($attPct >= 90 ? '#50cd89' : ($attPct >= 75 ? '#ffc700' : '#f1416c'));
    $attBg  = $attPct === null ? '#f5f8fa' : ($attPct >= 90 ? '#e8fff3' : ($attPct >= 75 ? '#fff8dd' : '#fff5f8'));
?>
<div class="pd-child-panel <?= $activeIndex === $i ? 'active' : '' ?>" id="pd-panel-<?= $i ?>">

<?php if (!empty($c['no_data'])): ?>
<div class="alert alert-secondary d-flex gap-3 p-4 rounded-3 mb-4">
    <i class="ki-duotone ki-information-5 fs-2 text-muted"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
    <div>
        <div class="fw-bold"><?= esc($cName) ?> — no admission record</div>
        <div class="text-muted fs-7">Contact the school administrator to set up this student's record.</div>
    </div>
</div>
<?php else: ?>

<!-- Child info bar -->
<div class="pd-child-bar mb-5">
    <img src="<?= esc($cPhotoUrl) ?>" alt="<?= esc($cName) ?>" class="pd-child-bar-avatar">
    <div>
        <div class="pd-child-bar-name"><?= esc($cName) ?></div>
        <div class="pd-child-bar-meta">
            <?php if ($rel): ?><?= esc(ucfirst($rel)) ?><?php endif; ?>
            <?php if ($rel && $schName): ?> &bull; <?php endif; ?>
            <?php if ($schName): ?><?= esc($schName) ?><?php endif; ?>
        </div>
    </div>
    <div class="pd-child-bar-chips">
        <?php if ($className): ?>
        <span class="badge badge-light-primary fs-8"><?= esc($className) ?></span>
        <?php endif; ?>
        <?php if ($levelName): ?>
        <span class="badge badge-light-info fs-8"><?= esc($levelName) ?></span>
        <?php endif; ?>
        <?php if ($streamName): ?>
        <span class="badge badge-light-secondary fs-8"><?= esc($streamName) ?></span>
        <?php endif; ?>
        <span class="badge badge-light-dark fs-8">Year <?= $enrolYear ?> · Term <?= $enrolTerm ?></span>
        <?php if ($classId): ?>
        <a href="<?= base_url('classroom/child/view/' . $classId) ?>" class="badge badge-light-success fs-8 text-decoration-none">
            <i class="ki-duotone ki-arrow-right fs-9"><span class="path1"></span><span class="path2"></span></i> View Classroom
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- KPI row -->
<div class="row g-4 mb-5">
    <!-- Attendance -->
    <div class="col-6 col-lg-3">
        <div class="pd-kpi">
            <div class="pd-kpi-icon" style="background:<?= $attBg ?>;">
                <i class="ki-duotone ki-calendar-tick fs-2" style="color:<?= $attClr ?>;"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
            </div>
            <div>
                <div class="pd-kpi-val" style="color:<?= $attClr ?>;"><?= $attPct !== null ? $attPct . '%' : '—' ?></div>
                <div class="pd-kpi-label">Attendance</div>
                <?php if ($attData['total'] > 0): ?>
                <div class="pd-kpi-sub"><?= $attData['present'] ?> / <?= $attData['total'] ?> days</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Term average -->
    <div class="col-6 col-lg-3">
        <?php
        $avgClr = $overallPct === null ? '#a1a5b7' : pd_grade_color($overallPct);
        $avgBg  = $overallPct === null ? '#f5f8fa' : ($overallPct >= 80 ? '#e8fff3' : ($overallPct >= 70 ? '#e8f3ff' : ($overallPct >= 50 ? '#fff8dd' : '#fff5f8')));
        ?>
        <div class="pd-kpi">
            <div class="pd-kpi-icon" style="background:<?= $avgBg ?>;">
                <i class="ki-duotone ki-award fs-2" style="color:<?= $avgClr ?>;"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            </div>
            <div>
                <div class="pd-kpi-val" style="color:<?= $avgClr ?>;"><?= $overallPct !== null ? $overallPct . '%' : '—' ?></div>
                <div class="pd-kpi-label">Term Average</div>
                <?php if ($overallPct !== null): ?>
                <div class="pd-kpi-sub">Grade <strong><?= pd_grade($overallPct) ?></strong> · Term <?= $enrolTerm ?></div>
                <?php else: ?>
                <div class="pd-kpi-sub text-muted">No marks yet</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Class rank -->
    <div class="col-6 col-lg-3">
        <div class="pd-kpi">
            <div class="pd-kpi-icon" style="background:#fff8dd;">
                <i class="ki-duotone ki-medal-star fs-2 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
            </div>
            <div>
                <div class="pd-kpi-val text-gray-800">
                    <?php if ($classRank !== null): ?>
                        <?= $classRank ?><span style="font-size:.95rem;font-weight:500;color:#a1a5b7;">/<?= $classSize ?></span>
                    <?php else: ?><span style="color:#a1a5b7;">—</span><?php endif; ?>
                </div>
                <div class="pd-kpi-label">Class Rank</div>
                <div class="pd-kpi-sub"><?= $className ? esc($className) : 'No class yet' ?></div>
            </div>
        </div>
    </div>

    <!-- Conduct score -->
    <div class="col-6 col-lg-3">
        <div class="pd-kpi">
            <div class="pd-kpi-icon" style="background:<?= $conductNet >= 0 ? '#e8fff3' : '#fff5f8' ?>;">
                <i class="ki-duotone ki-abstract-26 fs-2" style="color:<?= $conductNet >= 0 ? '#50cd89' : '#f1416c' ?>;"><span class="path1"></span><span class="path2"></span></i>
            </div>
            <div>
                <div class="pd-kpi-val" style="color:<?= $conductNet >= 0 ? '#50cd89' : '#f1416c' ?>;"><?= ($conductNet >= 0 ? '+' : '') . $conductNet ?></div>
                <div class="pd-kpi-label">Conduct Score</div>
                <div class="pd-kpi-sub">+<?= $c['conduct_positive'] ?? 0 ?> / −<?= $c['conduct_negative'] ?? 0 ?> pts</div>
            </div>
        </div>
    </div>
</div><!-- /KPI row -->

<!-- Main grid: Academic + Attendance -->
<div class="row g-5 mb-5">

    <!-- Academic Performance -->
    <div class="col-lg-7">
        <div class="pd-card h-100">
            <div class="pd-card-head">
                <i class="ki-duotone ki-book-open fs-4 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                <span class="pd-card-title">Academic Performance</span>
                <span class="badge badge-light-primary ms-auto">Term <?= $enrolTerm ?>, <?= $enrolYear ?></span>
            </div>
            <div class="pd-card-body">

                <?php if (empty($marksRows)): ?>
                <div class="pd-empty">
                    <i class="ki-duotone ki-chart-line-star fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div>No marks recorded for this term yet.</div>
                </div>
                <?php else: ?>

                <!-- Subject marks list -->
                <?php foreach ($marksRows as $m): ?>
                <?php
                    $hasMark = $m['mark'] !== null && (float)$m['total_mark'] > 0;
                    $pct     = $hasMark ? round(((float)$m['mark']/(float)$m['total_mark'])*100,1) : null;
                    $grade   = ($pct !== null && !$m['is_absent']) ? pd_grade($pct) : ($m['is_absent'] ? 'ABS' : '—');
                    $gcls    = ($pct !== null && !$m['is_absent']) ? pd_grade_cls($pct) : 'secondary';
                    $barPct  = $pct ?? 0;
                    $barClr  = $pct !== null ? pd_grade_color($pct) : '#e4e6ef';
                ?>
                <div class="pd-sm-row">
                    <div class="pd-sm-name"><?= esc($m['subject_name']) ?></div>
                    <div class="pd-sm-bar-wrap">
                        <div class="pd-sm-bar-bg">
                            <div class="pd-sm-bar-fill" style="width:<?= $barPct ?>%;background:<?= $barClr ?>;"></div>
                        </div>
                    </div>
                    <div class="pd-sm-score" style="color:<?= $barClr ?>;">
                        <?php if ($m['is_absent']): ?>
                            <span class="text-muted">ABS</span>
                        <?php elseif ($pct !== null): ?>
                            <?= $pct ?>%
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </div>
                    <div class="pd-sm-grade">
                        <span class="badge badge-light-<?= $gcls ?>" style="font-size:.7rem;"><?= $grade ?></span>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Overall summary -->
                <?php if ($overallPct !== null): ?>
                <div class="mt-4 p-3 rounded-3" style="background:<?= $avgBg ?>;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <div class="fw-bold fs-6" style="color:<?= $avgClr ?>;"><?= $overallPct ?>% — Grade <?= pd_grade($overallPct) ?></div>
                            <div class="text-muted fs-8">Overall Term <?= $enrolTerm ?> average</div>
                        </div>
                        <?php if ($classRank): ?>
                        <div class="text-end">
                            <div class="fw-bold fs-5 text-gray-800">Rank #<?= $classRank ?></div>
                            <div class="text-muted fs-8">of <?= $classSize ?> students</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Term trend mini bars -->
                <?php
                $trendData = [];
                for ($t = 1; $t <= 3; $t++) {
                    $tp = $allTermMarks[$t]['overall_pct'] ?? null;
                    if ($tp !== null) $trendData[$t] = (float)$tp;
                }
                if (count($trendData) > 1):
                ?>
                <div class="mt-4">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-2" style="letter-spacing:.5px;">Term Trend</div>
                    <div class="pd-trend">
                        <?php foreach ([1,2,3] as $t): ?>
                        <?php if (isset($trendData[$t])): $tp = $trendData[$t]; $tc = pd_grade_color($tp); ?>
                        <div class="pd-trend-bar-wrap">
                            <div class="pd-trend-bar-bg">
                                <div class="pd-trend-bar-fill" style="height:<?= $tp ?>%;background:<?= $tc ?>;"></div>
                            </div>
                            <div class="pd-trend-label" style="color:<?= $tc ?>;"><?= $tp ?>%</div>
                            <div class="pd-trend-label">T<?= $t ?></div>
                        </div>
                        <?php else: ?>
                        <div class="pd-trend-bar-wrap" style="opacity:.35;">
                            <div class="pd-trend-bar-bg"><div class="pd-trend-bar-fill" style="height:0%;background:#e4e6ef;"></div></div>
                            <div class="pd-trend-label">—</div>
                            <div class="pd-trend-label">T<?= $t ?></div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php endif; // end marks ?>
            </div>
        </div>
    </div>

    <!-- Attendance -->
    <div class="col-lg-5">
        <div class="pd-card h-100">
            <div class="pd-card-head">
                <i class="ki-duotone ki-calendar-tick fs-4 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                <span class="pd-card-title">Attendance</span>
                <?php if ($attPct !== null): ?>
                <span class="badge ms-auto" style="background:<?= $attBg ?>;color:<?= $attClr ?>;"><?= $attPct ?>%</span>
                <?php endif; ?>
            </div>
            <div class="pd-card-body">

                <?php if ($attData['total'] === 0): ?>
                <div class="pd-empty">
                    <i class="ki-duotone ki-calendar fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span></i>
                    <div>No attendance records yet.</div>
                </div>
                <?php else: ?>

                <!-- Radial ring (CSS conic-gradient) -->
                <?php if ($attPct !== null): ?>
                <div class="pd-att-ring-wrap">
                    <div class="pd-att-ring" style="background:conic-gradient(<?= $attClr ?> <?= $attPct ?>%, #f1f3f5 0%);">
                        <div class="pd-att-ring-inner">
                            <div class="pd-att-ring-pct" style="color:<?= $attClr ?>;"><?= $attPct ?>%</div>
                            <div class="pd-att-ring-lbl">Present</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Pill stats -->
                <div class="pd-att-pills">
                    <div class="pd-att-pill" style="background:#e8fff3;">
                        <div class="pd-att-pill-val text-success"><?= $attData['present'] ?></div>
                        <div class="pd-att-pill-lbl">Present</div>
                    </div>
                    <div class="pd-att-pill" style="background:#fff5f8;">
                        <div class="pd-att-pill-val text-danger"><?= $attData['absent'] ?></div>
                        <div class="pd-att-pill-lbl">Absent</div>
                    </div>
                    <div class="pd-att-pill" style="background:#f5f8fa;">
                        <div class="pd-att-pill-val text-gray-700"><?= $attData['total'] ?></div>
                        <div class="pd-att-pill-lbl">Total</div>
                    </div>
                </div>

                <!-- Monthly mini trend -->
                <?php if (!empty($c['attendance_monthly'])): ?>
                <div class="text-muted fs-8 fw-bold text-uppercase mb-1" style="letter-spacing:.5px;">Monthly Trend</div>
                <div class="pd-monthly mb-3">
                    <?php foreach ($c['attendance_monthly'] as $mo): ?>
                    <?php $mp = $mo['total'] > 0 ? round(($mo['present']/$mo['total'])*100) : 0; $mc = $mp >= 90 ? '#50cd89' : ($mp >= 75 ? '#ffc700' : '#f1416c'); ?>
                    <div class="pd-monthly-col">
                        <div class="pd-monthly-bar-bg">
                            <div class="pd-monthly-bar-fill" style="height:<?= $mp ?>%;background:<?= $mc ?>;"></div>
                        </div>
                        <div class="pd-monthly-lbl"><?= esc(substr($mo['label'],0,3)) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Subject attendance bars -->
                <?php if (!empty($c['subject_attendance'])): ?>
                <div class="text-muted fs-8 fw-bold text-uppercase mb-1" style="letter-spacing:.5px;">By Subject</div>
                <?php foreach (array_slice($c['subject_attendance'], 0, 6) as $sa): ?>
                <?php $sp = $sa['total'] > 0 ? round(($sa['present']/$sa['total'])*100) : 0; $sc = $sp >= 90 ? '#50cd89' : ($sp >= 75 ? '#ffc700' : '#f1416c'); ?>
                <div class="pd-sub-att-row">
                    <div class="pd-sub-att-name" title="<?= esc($sa['subject_name']) ?>"><?= esc($sa['subject_name']) ?></div>
                    <div class="pd-sub-att-bar"><div class="pd-sub-att-fill" style="width:<?= $sp ?>%;background:<?= $sc ?>;"></div></div>
                    <div class="pd-sub-att-pct" style="color:<?= $sc ?>;"><?= $sp ?>%</div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php endif; // end total > 0 ?>
            </div>
        </div>
    </div>

</div><!-- /main grid -->

<!-- Bottom row: Conduct + Announcements -->
<div class="row g-5 mb-5">

    <!-- Conduct -->
    <div class="col-lg-6">
        <?php $cNetClr = $conductNet >= 0 ? 'success' : 'danger'; ?>
        <div class="pd-card h-100">
            <div class="pd-card-head">
                <i class="ki-duotone ki-shield-tick fs-4 text-<?= $cNetClr ?>"><span class="path1"></span><span class="path2"></span></i>
                <span class="pd-card-title">Conduct Record</span>
                <span class="badge badge-light-<?= $cNetClr ?> ms-auto"><?= $conductNet >= 0 ? '+' : '' ?><?= $conductNet ?> pts net</span>
            </div>
            <div class="pd-card-body">

                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="text-center p-2 rounded-3" style="background:#e8fff3;">
                            <div class="fw-bold fs-4 text-success">+<?= $c['conduct_positive'] ?? 0 ?></div>
                            <div class="text-muted fs-8">Positive</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-2 rounded-3" style="background:#fff5f8;">
                            <div class="fw-bold fs-4 text-danger">−<?= $c['conduct_negative'] ?? 0 ?></div>
                            <div class="text-muted fs-8">Negative</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-2 rounded-3" style="background:#f5f8fa;">
                            <div class="fw-bold fs-4 text-gray-700"><?= $c['total_incidents'] ?? 0 ?></div>
                            <div class="text-muted fs-8">Total</div>
                        </div>
                    </div>
                </div>

                <?php if (empty($c['conduct_incidents'])): ?>
                <div class="pd-empty" style="padding:1.25rem 1rem;">
                    <i class="ki-duotone ki-check-circle fs-2x text-success mb-2"><span class="path1"></span><span class="path2"></span></i>
                    <div class="mt-2">No conduct incidents on record.</div>
                </div>
                <?php else: ?>
                <div class="text-muted fs-8 fw-bold text-uppercase mb-2" style="letter-spacing:.5px;">Recent Incidents</div>
                <?php foreach ($c['conduct_incidents'] as $inc): ?>
                <?php
                    $isPos  = (bool) $inc['is_positive'];
                    $sev    = $inc['severity_level'] ?? 'Minor';
                    $sevCls = $isPos ? 'success' : pd_severity_cls($sev);
                    $dotClr = $isPos ? '#50cd89' : ($sev === 'Major' || $sev === 'Critical' ? '#f1416c' : '#ffc700');
                    $pts    = (int) $inc['points_awarded'];
                ?>
                <div class="pd-ci-row">
                    <div class="pd-ci-dot" style="background:<?= $dotClr ?>;"></div>
                    <div class="pd-ci-main">
                        <div class="pd-ci-type"><?= esc($inc['type_name'] ?? 'Incident') ?></div>
                        <div class="pd-ci-meta">
                            <span class="badge badge-light-<?= $sevCls ?>" style="font-size:.68rem;"><?= $isPos ? 'Positive' : esc($sev) ?></span>
                            &bull; <?= esc($inc['category'] ?? '') ?>
                            &bull; <?= date('d M Y', strtotime($inc['incident_date'])) ?>
                            <?php if ($inc['is_resolved']): ?>&bull; <span class="text-success">Resolved</span><?php endif; ?>
                        </div>
                        <?php if ($inc['incident_description']): ?>
                        <div class="fs-8 text-muted mt-1" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:320px;"><?= esc($inc['incident_description']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="pd-ci-pts" style="color:<?= $isPos ? '#50cd89' : '#f1416c' ?>;"><?= ($isPos ? '+' : '−') . abs($pts) ?></div>
                </div>
                <?php endforeach; ?>

                <?php if (($c['total_incidents'] ?? 0) > 5): ?>
                <div class="text-center mt-3">
                    <a href="<?= base_url('conduct/my') ?>" class="btn btn-light-primary btn-sm">View all <?= $c['total_incidents'] ?> incidents</a>
                </div>
                <?php endif; ?>
                <?php endif; // conduct incidents ?>
            </div>
        </div>
    </div>

    <!-- Announcements (from this child's school) -->
    <div class="col-lg-6">
        <?php
        $childSchId   = (int) ($c['row']['sch_id_fk'] ?? 0);
        $childAnns    = array_filter($pr_announcements, fn($a) => (int)$a['sch_id_fk'] === $childSchId);
        ?>
        <div class="pd-card h-100">
            <div class="pd-card-head">
                <i class="ki-duotone ki-notification fs-4 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <span class="pd-card-title">School Announcements</span>
                <a href="<?= base_url('wall') ?>" class="btn btn-light btn-sm ms-auto" style="font-size:.76rem;padding:.22rem .7rem;">School Wall</a>
            </div>
            <div class="pd-card-body">

                <?php if (empty($childAnns)): ?>
                <div class="pd-empty">
                    <i class="ki-duotone ki-notification fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div>No announcements at this time.</div>
                </div>
                <?php else: ?>
                <?php
                $priClr = ['High' => '#f1416c', 'Medium' => '#ffc700', 'Low' => '#50cd89'];
                foreach ($childAnns as $ann):
                    $dotClr = $priClr[$ann['priority']] ?? '#a1a5b7';
                ?>
                <div class="pd-ann-row">
                    <div class="pd-ann-dot" style="background:<?= $dotClr ?>;margin-top:.45rem;"></div>
                    <div>
                        <div class="pd-ann-title"><?= esc($ann['title']) ?></div>
                        <?php if ($ann['content']): ?>
                        <div class="text-muted fs-8 mt-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= esc(strip_tags($ann['content'])) ?></div>
                        <?php endif; ?>
                        <div class="pd-ann-date">
                            <span class="badge" style="background:<?= $dotClr ?>20;color:<?= $dotClr ?>;font-size:.68rem;"><?= esc($ann['priority']) ?></span>
                            &bull; <?= date('d M Y', strtotime($ann['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div><!-- /bottom row -->

<?php endif; // end no_data check ?>
</div><!-- /pd-child-panel -->
<?php endforeach; // children loop ?>
