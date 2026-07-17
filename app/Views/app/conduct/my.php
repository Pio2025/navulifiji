<?php
$children  = $children  ?? [];
$incidents = $incidents  ?? [];
$admission = $admission  ?? null;
$isStudent = $isStudent  ?? false;
$isParent  = $isParent   ?? false;

function cc_severity_cls(string $s): string {
    return match(strtolower($s)) {
        'positive' => 'success',
        'minor'    => 'warning',
        'major'    => 'danger',
        'critical' => 'danger',
        default    => 'secondary',
    };
}
function cc_severity_color(string $s): string {
    return match(strtolower($s)) {
        'positive' => '#50cd89',
        'minor'    => '#ffc700',
        'major'    => '#f1416c',
        'critical' => '#f1416c',
        default    => '#a1a5b7',
    };
}
function cc_compute_stats(array $incidents): array {
    $posCount = $negCount = $openCount = $resolvedCount = 0;
    $posPoints = $negPoints = 0;
    $monthly = [];
    $byType  = [];
    $bySev   = [];

    for ($i = 5; $i >= 0; $i--) {
        $monthly[date('M', strtotime("-$i months"))] = ['pos' => 0, 'neg' => 0];
    }

    foreach ($incidents as $inc) {
        $pos = !empty($inc['is_positive']);
        $pts = (int) $inc['points_awarded'];
        if ($pos) { $posCount++; $posPoints += $pts; }
        else       { $negCount++; $negPoints += $pts; }
        if (!empty($inc['is_resolved'])) $resolvedCount++;
        else $openCount++;

        $m = date('M', strtotime($inc['incident_date']));
        if (isset($monthly[$m])) {
            if ($pos) $monthly[$m]['pos']++;
            else      $monthly[$m]['neg']++;
        }

        $t = $inc['type_name'] ?? 'Unknown';
        $byType[$t] = ($byType[$t] ?? 0) + 1;

        $sv = $inc['severity_level'] ?? 'Unknown';
        $bySev[$sv] = ($bySev[$sv] ?? 0) + 1;
    }

    arsort($byType);
    $byType = array_slice($byType, 0, 5, true);

    return compact('posCount','negCount','openCount','resolvedCount',
                   'posPoints','negPoints','monthly','byType','bySev');
}
?>
<style>
/* ── Conduct child view ───────────────────────────────────────────────────── */
.cc-tab { display:inline-flex; align-items:center; gap:.5rem; padding:.45rem 1rem; border-radius:10px; border:2px solid #e9edf0; background:#fff; cursor:pointer; font-weight:600; font-size:.86rem; color:#5e6278; transition:.15s; text-decoration:none; }
.cc-tab:hover { border-color:#c5c7d4; color:#181c32; }
.cc-tab.active { border-color:#7c3aed; background:#f5f0ff; color:#7c3aed; }
.cc-tab img { width:28px; height:28px; border-radius:50%; object-fit:cover; }
.cc-panel { display:none; }
.cc-panel.active { display:block; }

.cc-info-bar { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; background:#fff; border:1px solid #e9edf0; border-radius:14px; padding:.9rem 1.4rem; margin-bottom:1.25rem; box-shadow:0 2px 8px rgba(0,0,0,.04); }
.cc-info-avatar { width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #e9edf0; flex-shrink:0; }
.cc-info-name { font-size:1rem; font-weight:700; color:#181c32; }
.cc-info-meta { font-size:.78rem; color:#7e8299; margin-top:.1rem; }
.cc-info-chips { margin-left:auto; display:flex; gap:.4rem; flex-wrap:wrap; }

.cc-kpi { border-radius:14px; border:1px solid #e9edf0; background:#fff; padding:1.1rem 1.3rem; box-shadow:0 2px 8px rgba(0,0,0,.04); display:flex; align-items:center; gap:.85rem; height:100%; }
.cc-kpi-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.cc-kpi-val { font-size:1.6rem; font-weight:800; line-height:1; letter-spacing:-.3px; }
.cc-kpi-label { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#a1a5b7; margin-top:.18rem; }

.cc-card { background:#fff; border-radius:14px; border:1px solid #e9edf0; box-shadow:0 2px 8px rgba(0,0,0,.04); overflow:hidden; margin-bottom:1.25rem; }
.cc-card-head { padding:.85rem 1.3rem; border-bottom:1px solid #f1f3f5; display:flex; align-items:center; gap:.5rem; }
.cc-card-title { font-weight:700; font-size:.9rem; color:#181c32; }
.cc-card-body { padding:1.1rem 1.3rem; }

/* Donut */
.cc-donut-wrap { display:flex; justify-content:center; align-items:center; padding:.75rem 0; }
.cc-donut { width:120px; height:120px; border-radius:50%; display:flex; align-items:center; justify-content:center; }
.cc-donut-inner { width:86px; height:86px; border-radius:50%; background:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; }
.cc-donut-pct { font-size:1.2rem; font-weight:800; line-height:1; }
.cc-donut-lbl { font-size:.62rem; font-weight:600; color:#a1a5b7; text-transform:uppercase; letter-spacing:.3px; margin-top:1px; }

/* Monthly mini bars */
.cc-bars { display:flex; align-items:flex-end; gap:4px; height:64px; }
.cc-bar-col { flex:1; display:flex; flex-direction:column; align-items:center; gap:2px; }
.cc-bar-stack { width:100%; flex:1; background:#f1f3f5; border-radius:3px 3px 0 0; display:flex; flex-direction:column; justify-content:flex-end; overflow:hidden; }
.cc-bar-neg { width:100%; background:#f1416c; }
.cc-bar-pos { width:100%; background:#50cd89; }
.cc-bar-lbl { font-size:.58rem; color:#c4c7d5; font-weight:500; white-space:nowrap; width:100%; text-align:center; }

/* Type bars */
.cc-type-row { display:flex; align-items:center; gap:.6rem; padding:.3rem 0; }
.cc-type-name { flex:1; font-size:.79rem; color:#5e6278; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; min-width:0; }
.cc-type-bar { width:90px; height:5px; border-radius:3px; background:#f1f3f5; overflow:hidden; flex-shrink:0; }
.cc-type-fill { height:100%; border-radius:3px; background:#7c3aed; }
.cc-type-count { width:20px; text-align:right; font-size:.78rem; font-weight:700; color:#5e6278; flex-shrink:0; }

/* Severity pills */
.cc-sev-grid { display:flex; gap:.5rem; flex-wrap:wrap; }
.cc-sev-pill { flex:1; min-width:80px; text-align:center; padding:.55rem .4rem; border-radius:10px; }
.cc-sev-val { font-size:1.3rem; font-weight:800; line-height:1; }
.cc-sev-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.3px; margin-top:.25rem; color:#7e8299; }

/* Table */
.cc-table th { font-size:.75rem; text-transform:uppercase; letter-spacing:.4px; color:#a1a5b7; }
.cc-status-dot { width:8px; height:8px; border-radius:50%; display:inline-block; flex-shrink:0; }

@media (max-width:767px) {
    .cc-info-chips { margin-left:0; }
    .cc-bars { gap:2px; }
}
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isParent ? "Children's Conduct" : "My Conduct" ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Conduct</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php if ($isStudent): ?>
    <!-- ── Student view ──────────────────────────────────────────────────── -->
    <?php if (!$admission): ?>
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-10 text-center">
            <i class="ki-duotone ki-shield-tick fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span></i>
            <p class="text-muted fs-6">You are not currently enrolled. Please contact your school administrator.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-5">
            <?php if (empty($incidents)): ?>
            <div class="text-center py-10">
                <i class="ki-duotone ki-shield-tick fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span></i>
                <p class="text-muted fs-6">No conduct incidents on record. Keep up the good work!</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead><tr class="fw-bold text-muted">
                        <th>Type</th><th>Points</th><th>Severity</th>
                        <th>Date</th><th>Location</th><th>Status</th><th class="text-end"></th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($incidents as $row): ?>
                    <tr>
                        <td><span class="badge badge-light-<?= $row['is_positive'] ? 'success' : 'danger' ?>"><?= esc($row['type_name'] ?? '—') ?></span></td>
                        <td><span class="fw-bold <?= (int)$row['points_awarded'] >= 0 ? 'text-success' : 'text-danger' ?>"><?= (int)$row['points_awarded'] >= 0 ? '+' : '' ?><?= $row['points_awarded'] ?></span></td>
                        <td><span class="text-muted fs-7"><?= esc($row['severity_level'] ?? '—') ?></span></td>
                        <td><span class="text-muted fs-7"><?= date('d M Y', strtotime($row['incident_date'])) ?></span></td>
                        <td><span class="text-muted fs-7"><?= esc($row['location'] ?: '—') ?></span></td>
                        <td><span class="badge badge-light-<?= $row['is_resolved'] ? 'success' : 'warning' ?>"><?= $row['is_resolved'] ? 'Resolved' : 'Open' ?></span></td>
                        <td class="text-end"><a href="<?= base_url('conduct/my/detail/'.$row['incident_id']) ?>" class="btn btn-sm btn-light-primary">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

<?php else: ?>
    <!-- ── Parent view ───────────────────────────────────────────────────── -->

    <?php if (empty($children)): ?>
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-10 text-center">
            <i class="ki-duotone ki-people fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
            <p class="text-muted fs-6">No linked children found. Please contact the school to link your children to your account.</p>
        </div>
    </div>

    <?php else: ?>

    <?php if (count($children) > 1): ?>
    <!-- Child tabs -->
    <div class="d-flex gap-2 flex-wrap mb-4">
        <?php foreach ($children as $i => $cd): $child = $cd['child']; ?>
        <button class="cc-tab <?= $i === 0 ? 'active' : '' ?>" onclick="ccShowTab(<?= $i ?>)">
            <?php if (!empty($child['profile_photo'])): ?>
            <img src="<?= base_url('uploads/profilePhoto/' . esc($child['profile_photo'])) ?>" alt="">
            <?php else: ?>
            <span class="symbol symbol-28px symbol-circle d-inline-flex align-items-center justify-content-center bg-light-primary text-primary fw-bold fs-8" style="width:28px;height:28px;border-radius:50%;flex-shrink:0;"><?= strtoupper(substr($child['fname'],0,1).substr($child['lname'],0,1)) ?></span>
            <?php endif; ?>
            <?= esc($child['fname'] . ' ' . $child['lname']) ?>
        </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php foreach ($children as $i => $cd):
        $child  = $cd['child'];
        $adm    = $cd['admission'];
        $incs   = $cd['incidents'];
        $s      = cc_compute_stats($incs);
        $total  = count($incs);
        $netPts = $s['posPoints'] + $s['negPoints'];
        $posPct = $total > 0 ? round($s['posCount'] / $total * 100) : 0;
        $negPct = 100 - $posPct;
        $monthMax = max(1, ...array_map(fn($m) => $m['pos'] + $m['neg'], array_values($s['monthly'])));
        $typeMax  = max(1, ...array_values($s['byType'] ?: [1]));
        $initials = strtoupper(substr($child['fname'],0,1).substr($child['lname'],0,1));
    ?>
    <div class="cc-panel <?= $i === 0 ? 'active' : '' ?>" id="cc-panel-<?= $i ?>">

        <!-- Info bar -->
        <div class="cc-info-bar">
            <?php if (!empty($child['profile_photo'])): ?>
            <img src="<?= base_url('uploads/profilePhoto/' . esc($child['profile_photo'])) ?>" class="cc-info-avatar" alt="">
            <?php else: ?>
            <div class="cc-info-avatar d-flex align-items-center justify-content-center bg-light-primary text-primary fw-bold fs-5"><?= $initials ?></div>
            <?php endif; ?>
            <div>
                <div class="cc-info-name"><?= esc($child['fname'] . ' ' . $child['lname']) ?></div>
                <div class="cc-info-meta">
                    <?= esc(ucfirst($child['relationship'] ?? '')) ?>
                    <?php if ($adm): ?>
                    &nbsp;·&nbsp; <?= esc($adm['sch_name'] ?? '') ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="cc-info-chips">
                <?php if ($adm): ?>
                <span class="badge badge-light-<?= ($adm['admission_status'] ?? '') === 'Active' ? 'success' : 'warning' ?>"><?= esc($adm['admission_status'] ?? '') ?></span>
                <?php endif; ?>
                <span class="badge badge-light-primary"><?= $total ?> incident<?= $total !== 1 ? 's' : '' ?></span>
                <?php if ($s['openCount'] > 0): ?>
                <span class="badge badge-light-warning"><?= $s['openCount'] ?> open</span>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($total === 0): ?>
        <div class="cc-card">
            <div class="cc-card-body text-center py-10">
                <div class="mb-4">
                    <i class="ki-duotone ki-shield-tick fs-3x text-muted"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <p class="text-muted fs-6 mb-0">No conduct incidents on record for <?= esc($child['fname']) ?>. Great behaviour!</p>
            </div>
        </div>
        <?php else: ?>

        <!-- KPI row -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-lg-3">
                <div class="cc-kpi">
                    <div class="cc-kpi-icon bg-light-primary"><i class="ki-duotone ki-shield-tick fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i></div>
                    <div>
                        <div class="cc-kpi-val text-gray-900"><?= $total ?></div>
                        <div class="cc-kpi-label">Total Incidents</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="cc-kpi">
                    <div class="cc-kpi-icon bg-light-success"><i class="ki-duotone ki-arrow-up fs-2 text-success"><span class="path1"></span><span class="path2"></span></i></div>
                    <div>
                        <div class="cc-kpi-val text-success">+<?= $s['posPoints'] ?></div>
                        <div class="cc-kpi-label">Positive Pts</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="cc-kpi">
                    <div class="cc-kpi-icon bg-light-danger"><i class="ki-duotone ki-arrow-down fs-2 text-danger"><span class="path1"></span><span class="path2"></span></i></div>
                    <div>
                        <div class="cc-kpi-val text-danger"><?= $s['negPoints'] ?></div>
                        <div class="cc-kpi-label">Negative Pts</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="cc-kpi">
                    <div class="cc-kpi-icon <?= $netPts >= 0 ? 'bg-light-success' : 'bg-light-danger' ?>">
                        <i class="ki-duotone ki-graph fs-2 <?= $netPts >= 0 ? 'text-success' : 'text-danger' ?>"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <div>
                        <div class="cc-kpi-val <?= $netPts >= 0 ? 'text-success' : 'text-danger' ?>"><?= $netPts >= 0 ? '+' : '' ?><?= $netPts ?></div>
                        <div class="cc-kpi-label">Net Balance</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts row -->
        <div class="row g-4 mb-4">

            <!-- Positive/Negative donut + Severity -->
            <div class="col-lg-4">
                <div class="cc-card h-100">
                    <div class="cc-card-head">
                        <i class="ki-duotone ki-chart-pie-simple fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        <span class="cc-card-title">Positive vs Negative</span>
                    </div>
                    <div class="cc-card-body">
                        <?php $donutGrad = "conic-gradient(#50cd89 0% {$posPct}%, #f1416c {$posPct}% 100%)"; ?>
                        <div class="cc-donut-wrap">
                            <div class="cc-donut" style="background:<?= $donutGrad ?>;">
                                <div class="cc-donut-inner">
                                    <div class="cc-donut-pct <?= $posPct >= 50 ? 'text-success' : 'text-danger' ?>"><?= $posPct ?>%</div>
                                    <div class="cc-donut-lbl">Positive</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-4 mb-4">
                            <div class="text-center">
                                <div class="d-flex align-items-center gap-1 justify-content-center">
                                    <span class="cc-status-dot" style="background:#50cd89;"></span>
                                    <span class="fw-bold fs-6 text-success"><?= $s['posCount'] ?></span>
                                </div>
                                <div class="text-muted fs-8">Positive</div>
                            </div>
                            <div class="text-center">
                                <div class="d-flex align-items-center gap-1 justify-content-center">
                                    <span class="cc-status-dot" style="background:#f1416c;"></span>
                                    <span class="fw-bold fs-6 text-danger"><?= $s['negCount'] ?></span>
                                </div>
                                <div class="text-muted fs-8">Negative</div>
                            </div>
                            <div class="text-center">
                                <div class="d-flex align-items-center gap-1 justify-content-center">
                                    <span class="cc-status-dot" style="background:#ffc700;"></span>
                                    <span class="fw-bold fs-6 text-warning"><?= $s['openCount'] ?></span>
                                </div>
                                <div class="text-muted fs-8">Open</div>
                            </div>
                        </div>
                        <!-- Severity breakdown -->
                        <?php if (!empty($s['bySev'])): ?>
                        <div class="separator mb-3"></div>
                        <div class="fw-semibold text-muted fs-8 text-uppercase letter-spacing mb-2">By Severity</div>
                        <div class="cc-sev-grid">
                            <?php foreach ($s['bySev'] as $sv => $svCount): ?>
                            <div class="cc-sev-pill bg-light-<?= cc_severity_cls($sv) ?>">
                                <div class="cc-sev-val text-<?= cc_severity_cls($sv) ?>"><?= $svCount ?></div>
                                <div class="cc-sev-lbl"><?= esc($sv) ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Monthly trend -->
            <div class="col-lg-4">
                <div class="cc-card h-100">
                    <div class="cc-card-head">
                        <i class="ki-duotone ki-calendar fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        <span class="cc-card-title">Monthly Trend (6 months)</span>
                    </div>
                    <div class="cc-card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="d-flex align-items-center gap-1 fs-8 text-muted"><span style="width:8px;height:8px;border-radius:2px;background:#50cd89;display:inline-block;"></span> Positive</span>
                            <span class="d-flex align-items-center gap-1 fs-8 text-muted"><span style="width:8px;height:8px;border-radius:2px;background:#f1416c;display:inline-block;"></span> Negative</span>
                        </div>
                        <div class="cc-bars">
                            <?php foreach ($s['monthly'] as $mon => $mc):
                                $barTotal = $mc['pos'] + $mc['neg'];
                                $posH = $monthMax > 0 ? round(($mc['pos'] / $monthMax) * 100) : 0;
                                $negH = $monthMax > 0 ? round(($mc['neg'] / $monthMax) * 100) : 0;
                            ?>
                            <div class="cc-bar-col" title="<?= $mon ?>: <?= $mc['pos'] ?> pos, <?= $mc['neg'] ?> neg">
                                <div class="cc-bar-stack">
                                    <?php if ($posH > 0): ?><div class="cc-bar-pos" style="height:<?= $posH ?>%;"></div><?php endif; ?>
                                    <?php if ($negH > 0): ?><div class="cc-bar-neg" style="height:<?= $negH ?>%;"></div><?php endif; ?>
                                </div>
                                <div class="cc-bar-lbl"><?= $mon ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($s['byType'])): ?>
                        <div class="separator mt-4 mb-3"></div>
                        <div class="fw-semibold text-muted fs-8 text-uppercase letter-spacing mb-2">Top Incident Types</div>
                        <?php foreach ($s['byType'] as $tn => $tc): ?>
                        <div class="cc-type-row">
                            <div class="cc-type-name" title="<?= esc($tn) ?>"><?= esc($tn) ?></div>
                            <div class="cc-type-bar"><div class="cc-type-fill" style="width:<?= round($tc / $typeMax * 100) ?>%;"></div></div>
                            <div class="cc-type-count"><?= $tc ?></div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Resolution status + points breakdown -->
            <div class="col-lg-4">
                <div class="cc-card h-100">
                    <div class="cc-card-head">
                        <i class="ki-duotone ki-check-circle fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        <span class="cc-card-title">Resolution Status</span>
                    </div>
                    <div class="cc-card-body">
                        <?php
                        $resPct  = $total > 0 ? round($s['resolvedCount'] / $total * 100) : 0;
                        $resGrad = "conic-gradient(#50cd89 0% {$resPct}%, #e9edf0 {$resPct}% 100%)";
                        ?>
                        <div class="cc-donut-wrap">
                            <div class="cc-donut" style="background:<?= $resGrad ?>;">
                                <div class="cc-donut-inner">
                                    <div class="cc-donut-pct text-success"><?= $resPct ?>%</div>
                                    <div class="cc-donut-lbl">Resolved</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-4 mb-4">
                            <div class="text-center">
                                <div class="fw-bold fs-5 text-success"><?= $s['resolvedCount'] ?></div>
                                <div class="text-muted fs-8">Resolved</div>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold fs-5 text-warning"><?= $s['openCount'] ?></div>
                                <div class="text-muted fs-8">Open</div>
                            </div>
                        </div>
                        <div class="separator mb-3"></div>
                        <div class="fw-semibold text-muted fs-8 text-uppercase letter-spacing mb-2">Points Summary</div>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-8 text-muted">Positive points earned</span>
                                <span class="fw-bold text-success fs-7">+<?= $s['posPoints'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-8 text-muted">Negative points</span>
                                <span class="fw-bold text-danger fs-7"><?= $s['negPoints'] ?></span>
                            </div>
                            <div class="separator"></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-8 fw-semibold">Net balance</span>
                                <span class="fw-bold <?= $netPts >= 0 ? 'text-success' : 'text-danger' ?> fs-6"><?= $netPts >= 0 ? '+' : '' ?><?= $netPts ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Incidents table -->
        <div class="cc-card">
            <div class="cc-card-head">
                <i class="ki-duotone ki-document-text fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
                <span class="cc-card-title">Incident History</span>
                <span class="ms-auto badge badge-light-primary"><?= $total ?> total</span>
            </div>
            <div class="cc-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-4 gy-2 mb-0 cc-table">
                        <thead><tr class="fw-bold text-muted">
                            <th class="ps-4">Type</th>
                            <th>Points</th>
                            <th>Severity</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="text-end pe-4"></th>
                        </tr></thead>
                        <tbody>
                        <?php foreach ($incs as $row): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="cc-status-dot" style="background:<?= $row['is_positive'] ? '#50cd89' : '#f1416c' ?>;"></span>
                                    <span class="fw-semibold fs-7 text-gray-800"><?= esc($row['type_name'] ?? '—') ?></span>
                                </div>
                                <?php if (!empty($row['incident_description'])): ?>
                                <div class="text-muted fs-8 mt-1"><?= esc(mb_strimwidth($row['incident_description'], 0, 60, '…')) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold <?= (int)$row['points_awarded'] >= 0 ? 'text-success' : 'text-danger' ?> fs-7">
                                    <?= (int)$row['points_awarded'] >= 0 ? '+' : '' ?><?= $row['points_awarded'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-light-<?= cc_severity_cls($row['severity_level'] ?? '') ?> fs-8">
                                    <?= esc($row['severity_level'] ?? '—') ?>
                                </span>
                            </td>
                            <td><span class="text-muted fs-7"><?= date('d M Y', strtotime($row['incident_date'])) ?></span></td>
                            <td><span class="text-muted fs-7"><?= esc($row['location'] ?: '—') ?></span></td>
                            <td>
                                <span class="badge badge-light-<?= $row['is_resolved'] ? 'success' : 'warning' ?> fs-8">
                                    <?= $row['is_resolved'] ? 'Resolved' : 'Open' ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?= base_url('conduct/my/detail/' . $row['incident_id']) ?>"
                                   class="btn btn-sm btn-light-primary">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php endif; // total > 0 ?>
    </div>
    <?php endforeach; ?>

    <?php endif; // !empty children ?>

<?php endif; // isStudent / isParent ?>

</div>
</div>

<?php if ($isParent && count($children) > 1): ?>
<script>
function ccShowTab(idx) {
    document.querySelectorAll('.cc-panel').forEach(function(p) { p.classList.remove('active'); });
    document.querySelectorAll('.cc-tab').forEach(function(t) { t.classList.remove('active'); });
    var panel = document.getElementById('cc-panel-' + idx);
    if (panel) panel.classList.add('active');
    var tabs = document.querySelectorAll('.cc-tab');
    if (tabs[idx]) tabs[idx].classList.add('active');
}
</script>
<?php endif; ?>
