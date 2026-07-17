<?php
$pr_children      = $pr_children      ?? [];
$pr_announcements = $pr_announcements ?? [];
$pr_no_children   = $pr_no_children   ?? false;

$parentFname    = session('fname') ?? 'Parent';
$parentPhoto    = session('photo');
$parentPhotoUrl = ($parentPhoto && file_exists(FCPATH . 'uploads/profilePhoto/' . $parentPhoto))
                  ? base_url('uploads/profilePhoto/' . $parentPhoto)
                  : base_url('app/assets/media/avatars/blank.png');

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
<style>
/* ── Parent dashboard ───────────────────────────────────────────────────────── */
.pd-hero { background: linear-gradient(135deg, #5f27cd 0%, #7c3aed 50%, #a855f7 100%); border-radius: 16px; overflow: hidden; position: relative; margin-bottom: 1.5rem; }
.pd-hero::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='500' height='200' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='420' cy='30' r='130' fill='rgba(255,255,255,.05)'/%3E%3Ccircle cx='60' cy='180' r='90' fill='rgba(255,255,255,.04)'/%3E%3Ccircle cx='240' cy='100' r='60' fill='rgba(255,255,255,.03)'/%3E%3C/svg%3E") no-repeat right top; }
.pd-hero-inner { position:relative; z-index:1; padding:2rem 2.25rem; display:flex; align-items:center; gap:1.75rem; flex-wrap:wrap; }
.pd-hero-avatar { width:88px; height:88px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,.3); flex-shrink:0; box-shadow:0 4px 20px rgba(0,0,0,.25); }
.pd-hero-name { font-size:1.6rem; font-weight:800; color:#fff; line-height:1.2; margin-bottom:.3rem; }
.pd-hero-sub { color:rgba(255,255,255,.78); font-size:.9rem; }
.pd-hero-badge { display:inline-flex; align-items:center; gap:.4rem; background:rgba(255,255,255,.15); backdrop-filter:blur(4px); border:1px solid rgba(255,255,255,.2); border-radius:20px; padding:.22rem .8rem; font-size:.78rem; color:#fff; font-weight:500; margin-top:.4rem; margin-right:.3rem; }
.pd-hero-right { margin-left:auto; text-align:right; }
.pd-hero-date { color:rgba(255,255,255,.55); font-size:.72rem; text-transform:uppercase; letter-spacing:.8px; }
.pd-hero-dateval { color:#fff; font-size:1.1rem; font-weight:700; margin-top:.1rem; }

/* ── Child tabs ──────────────────────────────────────────────────────────── */
.pd-child-tabs { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.pd-child-tab { display:flex; align-items:center; gap:.55rem; padding:.5rem 1rem; border-radius:10px; border:2px solid #e9edf0; background:#fff; cursor:pointer; transition:.15s; font-weight:600; font-size:.88rem; color:#5e6278; }
.pd-child-tab:hover { border-color:#c5c7d4; color:#181c32; }
.pd-child-tab.active { border-color:#7c3aed; background:#f5f0ff; color:#7c3aed; }
.pd-child-tab img { width:32px; height:32px; border-radius:50%; object-fit:cover; flex-shrink:0; }
.pd-child-panel { display:none; }
.pd-child-panel.active { display:block; }

/* ── Child info bar ──────────────────────────────────────────────────────── */
.pd-child-bar { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; background:#fff; border:1px solid #e9edf0; border-radius:14px; padding:1rem 1.5rem; margin-bottom:1.25rem; box-shadow:0 2px 8px rgba(0,0,0,.04); }
.pd-child-bar-avatar { width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid #e9edf0; flex-shrink:0; }
.pd-child-bar-name { font-size:1.05rem; font-weight:700; color:#181c32; }
.pd-child-bar-meta { font-size:.8rem; color:#7e8299; margin-top:.1rem; }
.pd-child-bar-chips { margin-left:auto; display:flex; gap:.5rem; flex-wrap:wrap; }

/* ── KPI cards ───────────────────────────────────────────────────────────── */
.pd-kpi { border-radius:14px; border:1px solid #e9edf0; background:#fff; padding:1.3rem 1.5rem; box-shadow:0 2px 8px rgba(0,0,0,.04); display:flex; align-items:center; gap:1rem; height:100%; }
.pd-kpi-icon { width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pd-kpi-val { font-size:1.75rem; font-weight:800; line-height:1; letter-spacing:-.4px; }
.pd-kpi-label { font-size:.74rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#a1a5b7; margin-top:.2rem; }
.pd-kpi-sub { font-size:.76rem; color:#7e8299; margin-top:.25rem; }

/* ── Section cards ───────────────────────────────────────────────────────── */
.pd-card { background:#fff; border-radius:14px; border:1px solid #e9edf0; box-shadow:0 2px 8px rgba(0,0,0,.04); overflow:hidden; }
.pd-card-head { padding:1rem 1.4rem; border-bottom:1px solid #f1f3f5; display:flex; align-items:center; gap:.55rem; }
.pd-card-title { font-weight:700; font-size:.93rem; color:#181c32; }
.pd-card-body { padding:1.2rem 1.4rem; }

/* ── Subject marks ───────────────────────────────────────────────────────── */
.pd-sm-row { display:flex; align-items:center; gap:.75rem; padding:.5rem 0; border-bottom:1px solid #f5f5f5; }
.pd-sm-row:last-child { border-bottom:none; }
.pd-sm-name { flex:1; font-size:.86rem; font-weight:600; color:#3f4254; min-width:0; }
.pd-sm-bar-wrap { width:120px; flex-shrink:0; }
.pd-sm-bar-bg { height:5px; border-radius:3px; background:#f1f3f5; overflow:hidden; }
.pd-sm-bar-fill { height:100%; border-radius:3px; transition:width .5s; }
.pd-sm-score { width:50px; text-align:right; font-size:.83rem; font-weight:700; flex-shrink:0; }
.pd-sm-grade { width:34px; text-align:center; flex-shrink:0; }

/* ── Term trend mini bars ────────────────────────────────────────────────── */
.pd-trend { display:flex; align-items:flex-end; gap:8px; height:60px; }
.pd-trend-bar-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:3px; }
.pd-trend-bar-bg { width:100%; flex:1; background:#f1f3f5; border-radius:3px 3px 0 0; display:flex; align-items:flex-end; overflow:hidden; }
.pd-trend-bar-fill { width:100%; border-radius:3px 3px 0 0; transition:height .5s; }
.pd-trend-label { font-size:.68rem; color:#a1a5b7; font-weight:600; white-space:nowrap; }

/* ── Attendance ──────────────────────────────────────────────────────────── */
.pd-att-pills { display:flex; gap:.6rem; margin-bottom:1rem; }
.pd-att-pill { flex:1; text-align:center; padding:.6rem .5rem; border-radius:10px; }
.pd-att-pill-val { font-size:1.35rem; font-weight:800; line-height:1; }
.pd-att-pill-lbl { font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-top:.25rem; color:#7e8299; }

/* ── Attendance radial ring ─────────────────────────────────────────────── */
.pd-att-ring-wrap { display:flex; justify-content:center; margin-bottom:.75rem; }
.pd-att-ring { width:110px; height:110px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pd-att-ring-inner { width:80px; height:80px; border-radius:50%; background:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; }
.pd-att-ring-pct { font-size:1.15rem; font-weight:800; line-height:1; }
.pd-att-ring-lbl { font-size:.62rem; font-weight:600; color:#a1a5b7; text-transform:uppercase; letter-spacing:.3px; margin-top:1px; }

/* ── Subject attendance bars ─────────────────────────────────────────────── */
.pd-sub-att-row { display:flex; align-items:center; gap:.6rem; padding:.35rem 0; }
.pd-sub-att-name { width:110px; font-size:.77rem; color:#5e6278; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pd-sub-att-bar { flex:1; height:5px; border-radius:3px; background:#f1f3f5; overflow:hidden; }
.pd-sub-att-fill { height:100%; border-radius:3px; }
.pd-sub-att-pct { width:36px; text-align:right; font-size:.76rem; font-weight:600; color:#5e6278; }

/* ── Monthly mini bar ────────────────────────────────────────────────────── */
.pd-monthly { display:flex; align-items:flex-end; gap:4px; height:56px; margin-top:.5rem; }
.pd-monthly-col { flex:1; display:flex; flex-direction:column; align-items:center; gap:2px; }
.pd-monthly-bar-bg { width:100%; flex:1; background:#f1f3f5; border-radius:2px 2px 0 0; display:flex; align-items:flex-end; overflow:hidden; }
.pd-monthly-bar-fill { width:100%; border-radius:2px 2px 0 0; }
.pd-monthly-lbl { font-size:.58rem; color:#c4c7d5; font-weight:500; white-space:nowrap; overflow:hidden; width:100%; text-align:center; }

/* ── Conduct ──────────────────────────────────────────────────────────────── */
.pd-ci-row { display:flex; align-items:flex-start; gap:.75rem; padding:.6rem 0; border-bottom:1px solid #f5f5f5; }
.pd-ci-row:last-child { border-bottom:none; }
.pd-ci-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; margin-top:.35rem; }
.pd-ci-main { flex:1; min-width:0; }
.pd-ci-type { font-size:.83rem; font-weight:600; color:#181c32; }
.pd-ci-meta { font-size:.74rem; color:#a1a5b7; margin-top:.1rem; }
.pd-ci-pts { font-size:.83rem; font-weight:700; flex-shrink:0; }

/* ── Announcements ───────────────────────────────────────────────────────── */
.pd-ann-row { display:flex; align-items:flex-start; gap:.7rem; padding:.55rem 0; border-bottom:1px solid #f5f5f5; }
.pd-ann-row:last-child { border-bottom:none; }
.pd-ann-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; margin-top:.4rem; }
.pd-ann-title { font-size:.85rem; font-weight:600; color:#181c32; line-height:1.3; }
.pd-ann-date { font-size:.72rem; color:#a1a5b7; margin-top:.12rem; }

/* ── Empty state ─────────────────────────────────────────────────────────── */
.pd-empty { text-align:center; padding:2rem 1rem; color:#a1a5b7; }

/* ── Responsive ──────────────────────────────────────────────────────────── */
@media (max-width:767px) {
    .pd-hero-inner { padding:1.4rem; gap:.9rem; }
    .pd-hero-avatar { width:64px; height:64px; }
    .pd-hero-name { font-size:1.2rem; }
    .pd-hero-right { margin-left:0; text-align:left; }
    .pd-sm-bar-wrap { width:70px; }
    .pd-child-bar-chips { margin-left:0; }
}
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">My Dashboard</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">Home</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Dashboard</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<!-- ── Hero ─────────────────────────────────────────────────────────────── -->
<div class="pd-hero mb-6">
    <div class="pd-hero-inner">
        <img src="<?= esc($parentPhotoUrl) ?>" alt="<?= esc($parentFname) ?>" class="pd-hero-avatar">
        <div>
            <div class="pd-hero-name">Welcome back, <?= esc($parentFname) ?>!</div>
            <div class="pd-hero-sub">Parent / Guardian Dashboard</div>
            <div class="mt-1">
                <span class="pd-hero-badge">
                    <i class="ki-duotone ki-people fs-7 text-white-50"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    <?= count($pr_children) ?> <?= count($pr_children) === 1 ? 'Child' : 'Children' ?> tracked
                </span>
                <?php
                $allSchools = [];
                foreach ($pr_children as $c) {
                    $sn = $c['row']['sch_name'] ?? null;
                    if ($sn && !in_array($sn, $allSchools)) $allSchools[] = $sn;
                }
                foreach ($allSchools as $sn):
                ?>
                <span class="pd-hero-badge">
                    <i class="ki-duotone ki-building fs-7 text-white-50"><span class="path1"></span><span class="path2"></span></i>
                    <?= esc($sn) ?>
                </span>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="pd-hero-right ms-auto">
            <div class="pd-hero-date">Today</div>
            <div class="pd-hero-dateval"><?= date('D, d M Y') ?></div>
        </div>
    </div>
</div>

<?php if ($pr_no_children || empty($pr_children)): ?>
<div class="alert alert-warning d-flex align-items-center gap-3 p-4 rounded-3">
    <i class="ki-duotone ki-information-5 fs-1 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
    <div>
        <div class="fw-bold">No children linked to your account</div>
        <div class="text-muted fs-7">Please contact your school administrator to link your children to your parent account.</div>
    </div>
</div>
<?php else: ?>

<!-- ── Child tabs (only if more than one child) ──────────────────────────── -->
<?php if (count($pr_children) > 1): ?>
<div class="pd-child-tabs mb-2" id="pd-child-tabs">
    <?php foreach ($pr_children as $i => $c): ?>
    <?php
        $cPhoto = $c['photo'] ?? null;
        $cPhotoUrl = ($cPhoto && file_exists(FCPATH . 'uploads/profilePhoto/' . $cPhoto))
                     ? base_url('uploads/profilePhoto/' . $cPhoto)
                     : base_url('app/assets/media/avatars/blank.png');
    ?>
    <button type="button" class="pd-child-tab <?= $i === 0 ? 'active' : '' ?>" data-child="<?= $i ?>">
        <img src="<?= esc($cPhotoUrl) ?>" alt="">
        <?= esc($c['fname']) ?> <?= esc($c['lname']) ?>
    </button>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── Per-child panels ───────────────────────────────────────────────────── -->
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
<div class="pd-child-panel <?= $i === 0 ? 'active' : '' ?>" id="pd-panel-<?= $i ?>">

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

<?php endif; // end no_children ?>

</div><!-- /container -->
</div><!-- /content -->

<script>
(function () {
    var tabs   = document.querySelectorAll('.pd-child-tab');
    var panels = document.querySelectorAll('.pd-child-panel');
    if (!tabs.length) return;

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var idx = this.dataset.child;
            tabs.forEach(function (t) { t.classList.remove('active'); });
            panels.forEach(function (p) { p.classList.remove('active'); });
            this.classList.add('active');
            var panel = document.getElementById('pd-panel-' + idx);
            if (panel) panel.classList.add('active');
        });
    });
})();
</script>
