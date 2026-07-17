<?php
// ── Unpack data ──────────────────────────────────────────────────────────────
$st_row                = $st_row                ?? [];
$st_classroom          = $st_classroom          ?? null;
$st_current_marks      = $st_current_marks      ?? [];
$st_all_term_marks     = $st_all_term_marks     ?? [];
$st_overall_pct        = $st_overall_pct        ?? null;
$st_class_rank         = $st_class_rank         ?? null;
$st_class_size         = $st_class_size         ?? null;
$st_attendance_pct     = $st_attendance_pct     ?? null;
$st_attendance_data    = $st_attendance_data    ?? ['present'=>0,'absent'=>0,'total'=>0];
$st_attendance_monthly = $st_attendance_monthly ?? [];
$st_subject_attendance = $st_subject_attendance ?? [];
$st_conduct_incidents  = $st_conduct_incidents  ?? [];
$st_conduct_positive   = $st_conduct_positive   ?? 0;
$st_conduct_negative   = $st_conduct_negative   ?? 0;
$st_conduct_resolved   = $st_conduct_resolved   ?? 0;
$st_total_incidents    = $st_total_incidents    ?? 0;
$st_announcements      = $st_announcements      ?? [];

$fname    = session('fname') ?? 'Student';
$photo    = session('photo');
$photoUrl = ($photo && file_exists(FCPATH . 'uploads/profilePhoto/' . $photo))
            ? base_url('uploads/profilePhoto/' . $photo)
            : base_url('app/assets/media/avatars/blank.png');

$enrolTerm  = (int) ($st_row['enrol_term']  ?? 1);
$enrolYear  = (int) ($st_row['enrol_year']  ?? date('Y'));
$schName    = $st_row['sch_name']  ?? 'School';
$streamName = $st_row['stream_name'] ?? '';
$levelName  = $st_row['level_name']  ?? '';
$className  = $st_classroom['class_name'] ?? null;

// ── Helpers ───────────────────────────────────────────────────────────────────
function st_grade(float $pct): string {
    if ($pct >= 90) return 'A+';
    if ($pct >= 80) return 'A';
    if ($pct >= 70) return 'B';
    if ($pct >= 50) return 'C';
    return 'F';
}
function st_grade_cls(float $pct): string {
    if ($pct >= 80) return 'success';
    if ($pct >= 70) return 'primary';
    if ($pct >= 50) return 'warning';
    return 'danger';
}
function st_severity_cls(string $s): string {
    return match($s) {
        'Positive'  => 'success',
        'Minor'     => 'warning',
        'Major'     => 'danger',
        'Critical'  => 'danger',
        default     => 'secondary',
    };
}

// ── Chart data prep ───────────────────────────────────────────────────────────
$chartSubjects = [];
$chartPcts     = [];
$chartColors   = [];
$marksRows     = $st_current_marks['marks'] ?? [];
foreach ($marksRows as $m) {
    if ($m['mark'] !== null && (float)$m['total_mark'] > 0) {
        $pct = round(((float)$m['mark'] / (float)$m['total_mark']) * 100, 1);
        $chartSubjects[] = $m['subject_name'];
        $chartPcts[]     = $pct;
        $chartColors[]   = $pct >= 80 ? '#50cd89' : ($pct >= 70 ? '#009ef7' : ($pct >= 50 ? '#ffc700' : '#f1416c'));
    }
}

// Term trend (line chart)
$termTrendPcts    = [];
$termTrendLabels  = [];
for ($t = 1; $t <= 3; $t++) {
    $pct = $st_all_term_marks[$t]['overall_pct'] ?? null;
    if ($pct !== null) {
        $termTrendLabels[] = 'Term ' . $t;
        $termTrendPcts[]   = $pct;
    }
}

// Monthly attendance chart
$attMonthLabels = array_column($st_attendance_monthly, 'label');
$attMonthPcts   = array_map(fn($m) => $m['total'] > 0 ? round(($m['present']/$m['total'])*100,1) : 0, $st_attendance_monthly);

$conductNet = $st_conduct_positive - $st_conduct_negative;
$attColor   = $st_attendance_pct >= 90 ? '#50cd89' : ($st_attendance_pct >= 75 ? '#ffc700' : '#f1416c');
?>
<style>
/* ── Student dashboard ───────────────────────────────────────────────────────── */
.sd-hero { background: linear-gradient(135deg, #1a6fc4 0%, #0095e8 55%, #00c6ff 100%); border-radius: 16px; overflow: hidden; position: relative; margin-bottom: 1.5rem; }
.sd-hero::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='400' height='200' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='340' cy='40' r='120' fill='rgba(255,255,255,.06)'/%3E%3Ccircle cx='50' cy='170' r='80' fill='rgba(255,255,255,.04)'/%3E%3C/svg%3E") no-repeat right top; }
.sd-hero-inner { position:relative; z-index:1; padding: 2rem 2.25rem; display:flex; align-items:center; gap:1.75rem; flex-wrap:wrap; }
.sd-hero-avatar { width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,.35); flex-shrink:0; box-shadow:0 4px 20px rgba(0,0,0,.2); }
.sd-hero-name { font-size:1.65rem; font-weight:800; color:#fff; line-height:1.2; margin-bottom:.35rem; }
.sd-hero-sub { color:rgba(255,255,255,.82); font-size:.92rem; }
.sd-hero-badge { display:inline-flex; align-items:center; gap:.4rem; background:rgba(255,255,255,.15); backdrop-filter:blur(4px); border:1px solid rgba(255,255,255,.25); border-radius:20px; padding:.25rem .85rem; font-size:.8rem; color:#fff; font-weight:500; margin-top:.45rem; margin-right:.35rem; }
.sd-hero-right { margin-left:auto; text-align:right; }
.sd-hero-date { color:rgba(255,255,255,.6); font-size:.75rem; text-transform:uppercase; letter-spacing:.8px; }
.sd-hero-dateval { color:#fff; font-size:1.15rem; font-weight:700; margin-top:.15rem; }

/* ── KPI cards ───────────────────────────────────────────────────── */
.sd-kpi { border-radius: 14px; border: 1px solid #e9edf0; background: #fff; padding: 1.4rem 1.6rem; box-shadow: 0 2px 10px rgba(0,0,0,.05); display:flex; align-items:center; gap:1.1rem; height:100%; }
.sd-kpi-icon { width:54px; height:54px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.5rem; }
.sd-kpi-val { font-size:1.85rem; font-weight:800; line-height:1; letter-spacing:-.5px; }
.sd-kpi-label { font-size:.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#a1a5b7; margin-top:.2rem; }
.sd-kpi-sub { font-size:.78rem; color:#7e8299; margin-top:.3rem; }

/* ── Section cards ───────────────────────────────────────────────── */
.sd-card { background:#fff; border-radius:16px; border:1px solid #e9edf0; box-shadow:0 2px 10px rgba(0,0,0,.05); overflow:hidden; }
.sd-card-head { padding:1.1rem 1.5rem; border-bottom:1px solid #f1f3f5; display:flex; align-items:center; gap:.6rem; }
.sd-card-title { font-weight:700; font-size:.95rem; color:#181c32; }
.sd-card-body { padding:1.25rem 1.5rem; }

/* ── Subject marks table ─────────────────────────────────────────── */
.sm-row { display:flex; align-items:center; gap:.85rem; padding:.55rem 0; border-bottom:1px solid #f5f5f5; }
.sm-row:last-child { border-bottom:none; }
.sm-name { flex:1; font-size:.88rem; font-weight:600; color:#3f4254; min-width:0; }
.sm-bar-wrap { width:140px; flex-shrink:0; }
.sm-bar-bg { height:6px; border-radius:3px; background:#f1f3f5; overflow:hidden; }
.sm-bar-fill { height:100%; border-radius:3px; transition:width .5s; }
.sm-score { width:54px; text-align:right; font-size:.85rem; font-weight:700; flex-shrink:0; }
.sm-grade { width:38px; text-align:center; flex-shrink:0; }

/* ── Conduct incidents ───────────────────────────────────────────── */
.ci-row { display:flex; align-items:flex-start; gap:.85rem; padding:.7rem 0; border-bottom:1px solid #f5f5f5; }
.ci-row:last-child { border-bottom:none; }
.ci-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; margin-top:.35rem; }
.ci-main { flex:1; min-width:0; }
.ci-type { font-size:.85rem; font-weight:600; color:#181c32; }
.ci-meta { font-size:.76rem; color:#a1a5b7; margin-top:.1rem; }
.ci-pts { font-size:.85rem; font-weight:700; flex-shrink:0; }

/* ── Attendance subject bars ─────────────────────────────────────── */
.att-sub-row { display:flex; align-items:center; gap:.75rem; padding:.4rem 0; }
.att-sub-name { width:120px; font-size:.8rem; color:#5e6278; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.att-sub-bar { flex:1; height:6px; border-radius:3px; background:#f1f3f5; overflow:hidden; }
.att-sub-fill { height:100%; border-radius:3px; }
.att-sub-pct { width:40px; text-align:right; font-size:.78rem; font-weight:600; color:#5e6278; }

/* ── Announcement card ───────────────────────────────────────────── */
.ann-row { display:flex; align-items:flex-start; gap:.75rem; padding:.6rem 0; border-bottom:1px solid #f5f5f5; }
.ann-row:last-child { border-bottom:none; }
.ann-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; margin-top:.4rem; }
.ann-title { font-size:.87rem; font-weight:600; color:#181c32; line-height:1.3; }
.ann-date { font-size:.74rem; color:#a1a5b7; margin-top:.15rem; }

/* ── No-data placeholder ─────────────────────────────────────────── */
.sd-empty { text-align:center; padding:2.5rem 1rem; color:#a1a5b7; }

/* ── Responsive ──────────────────────────────────────────────────── */
@media (max-width:767px) {
    .sd-hero-inner { padding:1.5rem; gap:1rem; }
    .sd-hero-avatar { width:68px; height:68px; }
    .sd-hero-name { font-size:1.25rem; }
    .sd-hero-right { margin-left:0; text-align:left; }
    .sm-bar-wrap { width:80px; }
}
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Student Dashboard</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">Home</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Student</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <span class="text-muted fs-7 fw-semibold d-none d-md-inline-flex align-items-center">
                <i class="ki-duotone ki-calendar fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                <?= date('l, d F Y') ?>
            </span>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php if (!empty($st_no_data)): ?>
    <div class="alert alert-warning d-flex align-items-center gap-3 p-4 rounded-3">
        <i class="ki-duotone ki-information-5 fs-1 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <div>
            <div class="fw-bold">No admission record found</div>
            <div class="text-muted fs-7">Please contact your school administrator to set up your account.</div>
        </div>
    </div>
<?php else: ?>

<!-- ── Hero ───────────────────────────────────────────────────────────────── -->
<div class="sd-hero mb-6">
    <div class="sd-hero-inner">
        <img src="<?= esc($photoUrl) ?>" alt="<?= esc($fname) ?>" class="sd-hero-avatar">
        <div>
            <div class="sd-hero-name">Welcome back, <?= esc($fname) ?>!</div>
            <div class="sd-hero-sub"><?= esc($schName) ?></div>
            <div class="mt-1">
                <?php if ($className): ?>
                <span class="sd-hero-badge"><i class="ki-duotone ki-book fs-6 text-white-50"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i><?= esc($className) ?></span>
                <?php endif; ?>
                <?php if ($levelName): ?>
                <span class="sd-hero-badge"><i class="ki-duotone ki-award fs-6 text-white-50"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><?= esc($levelName) ?></span>
                <?php endif; ?>
                <?php if ($streamName): ?>
                <span class="sd-hero-badge"><i class="ki-duotone ki-abstract-28 fs-6 text-white-50"><span class="path1"></span><span class="path2"></span></i><?= esc($streamName) ?></span>
                <?php endif; ?>
                <span class="sd-hero-badge"><i class="ki-duotone ki-calendar fs-6 text-white-50"><span class="path1"></span><span class="path2"></span></i>Year <?= $enrolYear ?> &bull; Term <?= $enrolTerm ?></span>
            </div>
        </div>
        <div class="sd-hero-right ms-auto">
            <div class="sd-hero-date">Today</div>
            <div class="sd-hero-dateval"><?= date('D, d M Y') ?></div>
            <?php if ($st_attendance_pct !== null): ?>
            <div class="mt-2">
                <span style="background:rgba(255,255,255,.18);border-radius:20px;padding:.25rem 1rem;font-size:.82rem;color:#fff;font-weight:600;">
                    <?= $st_attendance_pct ?>% Attendance
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ── KPI row ─────────────────────────────────────────────────────────────── -->
<div class="row g-4 mb-6">

    <!-- Attendance -->
    <div class="col-6 col-lg-3">
        <div class="sd-kpi">
            <div class="sd-kpi-icon" style="background:<?= $st_attendance_pct === null ? '#f5f8fa' : ($st_attendance_pct >= 90 ? '#e8fff3' : ($st_attendance_pct >= 75 ? '#fff8dd' : '#fff5f8')) ?>;">
                <i class="ki-duotone ki-calendar-tick fs-2" style="color:<?= $st_attendance_pct === null ? '#a1a5b7' : ($st_attendance_pct >= 90 ? '#50cd89' : ($st_attendance_pct >= 75 ? '#ffc700' : '#f1416c')) ?>;"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
            </div>
            <div>
                <div class="sd-kpi-val" style="color:<?= $st_attendance_pct === null ? '#a1a5b7' : ($st_attendance_pct >= 90 ? '#50cd89' : ($st_attendance_pct >= 75 ? '#ffc700' : '#f1416c')) ?>;">
                    <?= $st_attendance_pct !== null ? $st_attendance_pct . '%' : '—' ?>
                </div>
                <div class="sd-kpi-label">Attendance</div>
                <?php if ($st_attendance_data['total'] > 0): ?>
                <div class="sd-kpi-sub"><?= $st_attendance_data['present'] ?> of <?= $st_attendance_data['total'] ?> days</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Average Mark -->
    <div class="col-6 col-lg-3">
        <div class="sd-kpi">
            <div class="sd-kpi-icon" style="background:<?= $st_overall_pct === null ? '#f5f8fa' : ($st_overall_pct >= 80 ? '#e8fff3' : ($st_overall_pct >= 70 ? '#e8f3ff' : ($st_overall_pct >= 50 ? '#fff8dd' : '#fff5f8'))) ?>;">
                <i class="ki-duotone ki-award fs-2" style="color:<?= $st_overall_pct === null ? '#a1a5b7' : ($st_overall_pct >= 80 ? '#50cd89' : ($st_overall_pct >= 70 ? '#009ef7' : ($st_overall_pct >= 50 ? '#ffc700' : '#f1416c'))) ?>;"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            </div>
            <div>
                <div class="sd-kpi-val" style="color:<?= $st_overall_pct === null ? '#a1a5b7' : ($st_overall_pct >= 80 ? '#50cd89' : ($st_overall_pct >= 70 ? '#009ef7' : ($st_overall_pct >= 50 ? '#ffc700' : '#f1416c'))) ?>;">
                    <?= $st_overall_pct !== null ? $st_overall_pct . '%' : '—' ?>
                </div>
                <div class="sd-kpi-label">Term Average</div>
                <?php if ($st_overall_pct !== null): ?>
                <div class="sd-kpi-sub">Grade <strong><?= st_grade($st_overall_pct) ?></strong> &bull; Term <?= $enrolTerm ?></div>
                <?php else: ?>
                <div class="sd-kpi-sub text-muted">No marks yet</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Class Rank -->
    <div class="col-6 col-lg-3">
        <div class="sd-kpi">
            <div class="sd-kpi-icon" style="background:#fff8dd;">
                <i class="ki-duotone ki-medal-star fs-2 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
            </div>
            <div>
                <div class="sd-kpi-val text-gray-800">
                    <?php if ($st_class_rank !== null): ?>
                        <?= $st_class_rank ?><span style="font-size:1rem;font-weight:500;color:#a1a5b7;">/<?= $st_class_size ?></span>
                    <?php else: ?><span style="color:#a1a5b7;">—</span><?php endif; ?>
                </div>
                <div class="sd-kpi-label">Class Rank</div>
                <div class="sd-kpi-sub"><?= $className ? esc($className) : 'Not enrolled' ?></div>
            </div>
        </div>
    </div>

    <!-- Conduct Balance -->
    <div class="col-6 col-lg-3">
        <div class="sd-kpi">
            <div class="sd-kpi-icon" style="background:<?= $conductNet >= 0 ? '#e8fff3' : '#fff5f8' ?>;">
                <i class="ki-duotone ki-abstract-26 fs-2" style="color:<?= $conductNet >= 0 ? '#50cd89' : '#f1416c' ?>;"><span class="path1"></span><span class="path2"></span></i>
            </div>
            <div>
                <div class="sd-kpi-val" style="color:<?= $conductNet >= 0 ? '#50cd89' : '#f1416c' ?>;">
                    <?= ($conductNet >= 0 ? '+' : '') . $conductNet ?>
                </div>
                <div class="sd-kpi-label">Conduct Score</div>
                <div class="sd-kpi-sub">+<?= $st_conduct_positive ?> / −<?= $st_conduct_negative ?> pts</div>
            </div>
        </div>
    </div>

</div><!-- /KPI row -->

<!-- ── Main grid ──────────────────────────────────────────────────────────── -->
<div class="row g-5 mb-5">

    <!-- LEFT: Academic Performance -->
    <div class="col-lg-7">
        <div class="sd-card h-100">
            <div class="sd-card-head">
                <i class="ki-duotone ki-book-open fs-4 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                <span class="sd-card-title">Academic Performance</span>
                <span class="badge badge-light-primary ms-auto">Term <?= $enrolTerm ?>, <?= $enrolYear ?></span>
            </div>
            <div class="sd-card-body">

                <?php if (empty($chartSubjects)): ?>
                    <div class="sd-empty">
                        <i class="ki-duotone ki-chart-line-star fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div>No marks recorded for this term yet.</div>
                    </div>
                <?php else: ?>

                <!-- Bar chart -->
                <div id="sd-marks-chart" style="min-height:<?= max(180, count($chartSubjects) * 42) ?>px;"></div>

                <!-- Subject marks table -->
                <div class="mt-4">
                    <?php foreach ($marksRows as $m): ?>
                    <?php
                        $hasMark = $m['mark'] !== null && (float)$m['total_mark'] > 0;
                        $pct     = $hasMark ? round(((float)$m['mark']/(float)$m['total_mark'])*100,1) : null;
                        $grade   = ($pct !== null && !$m['is_absent']) ? st_grade($pct) : ($m['is_absent'] ? 'ABS' : '—');
                        $gcls    = ($pct !== null && !$m['is_absent']) ? st_grade_cls($pct) : 'secondary';
                        $barPct  = $pct ?? 0;
                        $barClr  = $pct !== null ? ($pct >= 80 ? '#50cd89' : ($pct >= 70 ? '#009ef7' : ($pct >= 50 ? '#ffc700' : '#f1416c'))) : '#e4e6ef';
                    ?>
                    <div class="sm-row">
                        <div class="sm-name"><?= esc($m['subject_name']) ?></div>
                        <div class="sm-bar-wrap">
                            <div class="sm-bar-bg">
                                <div class="sm-bar-fill" style="width:<?= $barPct ?>%;background:<?= $barClr ?>;"></div>
                            </div>
                        </div>
                        <div class="sm-score" style="color:<?= $barClr ?>;">
                            <?php if ($m['is_absent']): ?>
                                <span class="text-muted">ABS</span>
                            <?php elseif ($pct !== null): ?>
                                <?= $pct ?>%
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </div>
                        <div class="sm-grade">
                            <span class="badge badge-light-<?= $gcls ?>" style="font-size:.72rem;"><?= $grade ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Overall summary -->
                <?php if ($st_overall_pct !== null): ?>
                <div class="mt-4 p-3 rounded-3" style="background:<?= $st_overall_pct >= 80 ? '#e8fff3' : ($st_overall_pct >= 70 ? '#e8f3ff' : ($st_overall_pct >= 50 ? '#fff8dd' : '#fff5f8')) ?>;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold fs-6" style="color:<?= $st_overall_pct >= 80 ? '#50cd89' : ($st_overall_pct >= 70 ? '#009ef7' : ($st_overall_pct >= 50 ? '#ffc700' : '#f1416c')) ?>;">
                                <?= $st_overall_pct ?>% — Grade <?= st_grade($st_overall_pct) ?>
                            </div>
                            <div class="text-muted fs-8">Overall Term <?= $enrolTerm ?> average</div>
                        </div>
                        <?php if ($st_class_rank): ?>
                        <div class="text-end">
                            <div class="fw-bold fs-5 text-gray-800">Rank #<?= $st_class_rank ?></div>
                            <div class="text-muted fs-8">out of <?= $st_class_size ?> students</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Term trend (if multiple terms have data) -->
                <?php if (count($termTrendPcts) > 1): ?>
                <div class="mt-4">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-2" style="letter-spacing:.5px;">Term Trend</div>
                    <div id="sd-trend-chart" style="height:100px;"></div>
                </div>
                <?php endif; ?>

                <?php endif; // end if chartSubjects ?>
            </div>
        </div>
    </div>

    <!-- RIGHT: Attendance -->
    <div class="col-lg-5">
        <div class="sd-card h-100">
            <div class="sd-card-head">
                <i class="ki-duotone ki-calendar-tick fs-4 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                <span class="sd-card-title">Attendance</span>
                <?php if ($st_attendance_pct !== null): ?>
                <span class="badge ms-auto" style="background:<?= $st_attendance_pct >= 90 ? '#e8fff3' : ($st_attendance_pct >= 75 ? '#fff8dd' : '#fff5f8') ?>;color:<?= $st_attendance_pct >= 90 ? '#50cd89' : ($st_attendance_pct >= 75 ? '#b07d00' : '#f1416c') ?>;">
                    <?= $st_attendance_pct ?>%
                </span>
                <?php endif; ?>
            </div>
            <div class="sd-card-body">

                <?php if ($st_attendance_data['total'] === 0): ?>
                    <div class="sd-empty">
                        <i class="ki-duotone ki-calendar fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span></i>
                        <div>No attendance records yet.</div>
                    </div>
                <?php else: ?>

                <!-- Donut chart -->
                <div id="sd-att-donut" style="height:190px;"></div>

                <!-- Present / Absent stat pills -->
                <div class="d-flex gap-3 justify-content-center mt-1 mb-4">
                    <div class="text-center px-3 py-2 rounded-3" style="background:#e8fff3;flex:1;">
                        <div class="fw-bold fs-5 text-success"><?= $st_attendance_data['present'] ?></div>
                        <div class="text-muted fs-8">Present</div>
                    </div>
                    <div class="text-center px-3 py-2 rounded-3" style="background:#fff5f8;flex:1;">
                        <div class="fw-bold fs-5 text-danger"><?= $st_attendance_data['absent'] ?></div>
                        <div class="text-muted fs-8">Absent</div>
                    </div>
                    <div class="text-center px-3 py-2 rounded-3" style="background:#f5f8fa;flex:1;">
                        <div class="fw-bold fs-5 text-gray-700"><?= $st_attendance_data['total'] ?></div>
                        <div class="text-muted fs-8">Total Days</div>
                    </div>
                </div>

                <!-- Monthly bar chart -->
                <?php if (!empty($attMonthLabels)): ?>
                <div class="text-muted fs-8 fw-bold text-uppercase mb-2" style="letter-spacing:.5px;">Monthly Trend</div>
                <div id="sd-att-monthly" style="height:110px;"></div>
                <?php endif; ?>

                <!-- Subject attendance -->
                <?php if (!empty($st_subject_attendance)): ?>
                <div class="mt-4">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-2" style="letter-spacing:.5px;">By Subject</div>
                    <?php foreach ($st_subject_attendance as $sa): ?>
                    <?php
                        $sp = $sa['total'] > 0 ? round(($sa['present']/$sa['total'])*100) : 0;
                        $sc = $sp >= 90 ? '#50cd89' : ($sp >= 75 ? '#ffc700' : '#f1416c');
                    ?>
                    <div class="att-sub-row">
                        <div class="att-sub-name" title="<?= esc($sa['subject_name']) ?>"><?= esc($sa['subject_name']) ?></div>
                        <div class="att-sub-bar"><div class="att-sub-fill" style="width:<?= $sp ?>%;background:<?= $sc ?>;"></div></div>
                        <div class="att-sub-pct" style="color:<?= $sc ?>;"><?= $sp ?>%</div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php endif; // end if total > 0 ?>
            </div>
        </div>
    </div>

</div><!-- /main grid -->

<!-- ── Bottom row: Conduct + Announcements ────────────────────────────────── -->
<div class="row g-5 mb-5">

    <!-- Conduct -->
    <div class="col-lg-6">
        <div class="sd-card h-100">
            <div class="sd-card-head">
                <i class="ki-duotone ki-shield-tick fs-4 text-<?= $conductNet >= 0 ? 'success' : 'danger' ?>"><span class="path1"></span><span class="path2"></span></i>
                <span class="sd-card-title">Conduct Record</span>
                <span class="badge badge-light-<?= $conductNet >= 0 ? 'success' : 'danger' ?> ms-auto"><?= $conductNet >= 0 ? '+' : '' ?><?= $conductNet ?> pts net</span>
            </div>
            <div class="sd-card-body">

                <!-- Points summary -->
                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="text-center p-2 rounded-3" style="background:#e8fff3;">
                            <div class="fw-bold fs-4 text-success">+<?= $st_conduct_positive ?></div>
                            <div class="text-muted fs-8">Positive</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-2 rounded-3" style="background:#fff5f8;">
                            <div class="fw-bold fs-4 text-danger">−<?= $st_conduct_negative ?></div>
                            <div class="text-muted fs-8">Negative</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center p-2 rounded-3" style="background:#f5f8fa;">
                            <div class="fw-bold fs-4 text-gray-700"><?= $st_total_incidents ?></div>
                            <div class="text-muted fs-8">Total</div>
                        </div>
                    </div>
                </div>

                <?php if (empty($st_conduct_incidents)): ?>
                    <div class="sd-empty" style="padding:1.5rem 1rem;">
                        <i class="ki-duotone ki-check-circle fs-2x text-success mb-2"><span class="path1"></span><span class="path2"></span></i>
                        <div class="mt-2">No conduct incidents on record. Keep it up!</div>
                    </div>
                <?php else: ?>
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-2" style="letter-spacing:.5px;">Recent Incidents</div>
                    <?php foreach ($st_conduct_incidents as $inc): ?>
                    <?php
                        $isPos   = (bool) $inc['is_positive'];
                        $sev     = $inc['severity_level'] ?? 'Minor';
                        $sevCls  = $isPos ? 'success' : st_severity_cls($sev);
                        $dotClr  = $isPos ? '#50cd89' : ($sev === 'Major' || $sev === 'Critical' ? '#f1416c' : '#ffc700');
                        $pts     = (int) $inc['points_awarded'];
                    ?>
                    <div class="ci-row">
                        <div class="ci-dot" style="background:<?= $dotClr ?>;"></div>
                        <div class="ci-main">
                            <div class="ci-type"><?= esc($inc['type_name'] ?? 'Incident') ?></div>
                            <div class="ci-meta">
                                <span class="badge badge-light-<?= $sevCls ?>" style="font-size:.7rem;"><?= $isPos ? 'Positive' : esc($sev) ?></span>
                                &bull; <?= esc($inc['category'] ?? '') ?>
                                &bull; <?= date('d M Y', strtotime($inc['incident_date'])) ?>
                                <?php if ($inc['is_resolved']): ?>
                                &bull; <span class="text-success">Resolved</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($inc['incident_description']): ?>
                            <div class="fs-8 text-muted mt-1" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:340px;"><?= esc($inc['incident_description']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="ci-pts" style="color:<?= $isPos ? '#50cd89' : '#f1416c' ?>;">
                            <?= ($isPos ? '+' : '−') . abs($pts) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if ($st_total_incidents > 5): ?>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('conduct/my') ?>" class="btn btn-light-primary btn-sm">View all <?= $st_total_incidents ?> incidents</a>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="col-lg-6">
        <div class="sd-card h-100">
            <div class="sd-card-head">
                <i class="ki-duotone ki-notification fs-4 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <span class="sd-card-title">School Announcements</span>
                <a href="<?= base_url('announcement') ?>" class="btn btn-light btn-sm ms-auto" style="font-size:.78rem;padding:.25rem .75rem;">View all</a>
            </div>
            <div class="sd-card-body">

                <?php if (empty($st_announcements)): ?>
                    <div class="sd-empty">
                        <i class="ki-duotone ki-notification fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div>No announcements at this time.</div>
                    </div>
                <?php else: ?>
                    <?php
                    $priCls = ['High' => '#f1416c', 'Medium' => '#ffc700', 'Low' => '#50cd89'];
                    foreach ($st_announcements as $ann):
                        $dotClr = $priCls[$ann['priority']] ?? '#a1a5b7';
                    ?>
                    <div class="ann-row">
                        <div class="ann-dot" style="background:<?= $dotClr ?>;"></div>
                        <div>
                            <div class="ann-title"><?= esc($ann['title']) ?></div>
                            <?php if ($ann['content']): ?>
                            <div class="text-muted fs-8 mt-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= esc(strip_tags($ann['content'])) ?></div>
                            <?php endif; ?>
                            <div class="ann-date">
                                <span class="badge" style="background:<?= $dotClr ?>20;color:<?= $dotClr ?>;font-size:.7rem;"><?= esc($ann['priority']) ?></span>
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

<?php endif; // end if no_data ?>

</div><!-- /container-xxl -->
</div><!-- /kt_app_content -->

<script>
(function () {

// ── Subject marks bar chart ──────────────────────────────────────────────────
<?php if (!empty($chartSubjects)): ?>
var marksChart = new ApexCharts(document.getElementById('sd-marks-chart'), {
    chart: { type: 'bar', height: <?= max(180, count($chartSubjects) * 42) ?>, toolbar: { show: false }, sparkline: { enabled: false } },
    plotOptions: { bar: { horizontal: true, borderRadius: 5, barHeight: '60%',
        dataLabels: { position: 'center' }
    }},
    dataLabels: {
        enabled: true,
        formatter: function(v) { return v + '%'; },
        style: { fontSize: '12px', fontWeight: 700, colors: ['#fff'] },
    },
    series: [{ name: 'Score', data: <?= json_encode($chartPcts) ?> }],
    colors: <?= json_encode($chartColors) ?>,
    xaxis: {
        categories: <?= json_encode($chartSubjects) ?>,
        min: 0, max: 100,
        labels: { formatter: v => v + '%', style: { fontSize: '12px' } }
    },
    yaxis: { labels: { style: { fontSize: '12px', fontWeight: 600 } } },
    grid: { borderColor: '#f1f3f5', strokeDashArray: 4 },
    tooltip: { y: { formatter: v => v + ' %' } },
});
marksChart.render();
<?php endif; ?>

// ── Term trend chart ─────────────────────────────────────────────────────────
<?php if (count($termTrendPcts) > 1): ?>
var trendChart = new ApexCharts(document.getElementById('sd-trend-chart'), {
    chart: { type: 'line', height: 100, toolbar: { show: false }, sparkline: { enabled: false } },
    series: [{ name: 'Average', data: <?= json_encode($termTrendPcts) ?> }],
    stroke: { curve: 'smooth', width: 3 },
    colors: ['#009ef7'],
    markers: { size: 5, colors: ['#009ef7'], strokeWidth: 2, strokeColors: '#fff' },
    xaxis: { categories: <?= json_encode($termTrendLabels) ?>, labels: { style: { fontSize: '11px' } } },
    yaxis: { min: 0, max: 100, labels: { formatter: v => v + '%', style: { fontSize: '11px' } } },
    grid: { borderColor: '#f1f3f5', strokeDashArray: 4 },
    dataLabels: { enabled: true, formatter: v => v + '%', style: { fontSize: '11px', colors: ['#009ef7'] }, background: { enabled: false } },
    tooltip: { y: { formatter: v => v + ' %' } },
});
trendChart.render();
<?php endif; ?>

// ── Attendance donut ─────────────────────────────────────────────────────────
<?php if ($st_attendance_data['total'] > 0): ?>
var attDonut = new ApexCharts(document.getElementById('sd-att-donut'), {
    chart: { type: 'donut', height: 190, toolbar: { show: false } },
    series: [<?= $st_attendance_data['present'] ?>, <?= $st_attendance_data['absent'] ?>],
    labels: ['Present', 'Absent'],
    colors: ['#50cd89', '#f1416c'],
    plotOptions: { pie: { donut: {
        size: '68%',
        labels: { show: true, total: { show: true, label: 'Rate', fontSize: '13px', fontWeight: 700,
            formatter: () => '<?= $st_attendance_pct ?>%'
        }}
    }}},
    legend: { position: 'bottom', fontSize: '12px' },
    dataLabels: { enabled: false },
    stroke: { width: 2 },
});
attDonut.render();
<?php endif; ?>

// ── Monthly attendance bar chart ─────────────────────────────────────────────
<?php if (!empty($attMonthLabels)): ?>
var attMonthly = new ApexCharts(document.getElementById('sd-att-monthly'), {
    chart: { type: 'bar', height: 110, toolbar: { show: false }, sparkline: { enabled: false } },
    series: [{ name: 'Attendance %', data: <?= json_encode($attMonthPcts) ?> }],
    colors: ['<?= $attColor ?>'],
    plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
    xaxis: { categories: <?= json_encode($attMonthLabels) ?>, labels: { style: { fontSize: '10px' } } },
    yaxis: { min: 0, max: 100, labels: { formatter: v => v + '%', style: { fontSize: '10px' } } },
    grid: { borderColor: '#f1f3f5', strokeDashArray: 4 },
    dataLabels: { enabled: false },
    tooltip: { y: { formatter: v => v + ' %' } },
});
attMonthly.render();
<?php endif; ?>

})();
</script>
