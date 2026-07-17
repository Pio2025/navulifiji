<?php
$schools        = $schools        ?? [];
$activeSchoolId = $activeSchoolId ?? 0;
$announcements  = $announcements  ?? [];
$notices        = $notices        ?? [];

function nb_age(string $d): string {
    $s = time() - strtotime($d);
    if ($s < 60)    return 'Just now';
    if ($s < 3600)  return floor($s/60).'m ago';
    if ($s < 86400) return floor($s/3600).'h ago';
    return date('d M Y', strtotime($d));
}

$annPriority = [
    'Critical'  => ['color'=>'#f1416c','light'=>'#fff5f8','badge'=>'badge-light-danger',  'label'=>'Critical'],
    'Important' => ['color'=>'#ff9500','light'=>'#fff8dd','badge'=>'badge-light-warning', 'label'=>'Important'],
    'Info'      => ['color'=>'#009ef7','light'=>'#f1faff','badge'=>'badge-light-primary', 'label'=>'Info'],
];
$noticePriority = [
    'Urgent'    => ['color'=>'#f1416c','light'=>'#fff5f8','badge'=>'badge-light-danger',  'label'=>'Urgent'],
    'Important' => ['color'=>'#ffc700','light'=>'#fff8dd','badge'=>'badge-light-warning', 'label'=>'Important'],
    'Normal'    => ['color'=>'#009ef7','light'=>'#f1faff','badge'=>'badge-light-primary', 'label'=>'Normal'],
];
$audienceCfg = [
    'All'      => ['badge'=>'badge-light-success', 'label'=>'Everyone'],
    'Teachers' => ['badge'=>'badge-light-primary', 'label'=>'Teachers'],
    'Students' => ['badge'=>'badge-light-info',    'label'=>'Students'],
    'Parents'  => ['badge'=>'badge-light-warning', 'label'=>'Parents'],
];

$pinnedNotices  = array_filter($notices, fn($n) => (int)$n['is_pinned'] === 1);
$regularNotices = array_filter($notices, fn($n) => (int)$n['is_pinned'] === 0);

// Active school name / logo for display
$activeSch = null;
foreach ($schools as $s) {
    if ((int)$s['sch_id'] === (int)$activeSchoolId) { $activeSch = $s; break; }
}
?>
<style>
/* ── Notices page ────────────────────────────────────────────────────────────── */
.nb-school-tabs { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:.75rem; }
.nb-tab { display:inline-flex; align-items:center; gap:.5rem; padding:.42rem 1rem; border-radius:8px; font-size:.86rem; font-weight:600; text-decoration:none; border:2px solid transparent; transition:.15s; }
.nb-tab.active { background:#0095e8; color:#fff; border-color:#0095e8; }
.nb-tab.inactive { background:#fff; color:#5e6278; border-color:#e9edf0; }
.nb-tab.inactive:hover { border-color:#b8c0d0; color:#181c32; text-decoration:none; }
.nb-tab img { width:18px;height:18px;object-fit:contain;border-radius:2px;flex-shrink:0; }

/* ── Section header ────────────────────────────────────────────────────────── */
.nb-sec-head { display:flex; align-items:center; gap:.6rem; margin-bottom:1rem; }
.nb-sec-title { font-size:1rem; font-weight:700; color:#181c32; }

/* ── Announcement card ─────────────────────────────────────────────────────── */
.nba-card { background:#fff; border-radius:12px; border:1px solid #e9edf0; overflow:hidden; position:relative; transition:box-shadow .18s,transform .18s; margin-bottom:.9rem; }
.nba-card:hover { box-shadow:0 4px 20px rgba(0,0,0,.08); transform:translateY(-1px); }
.nba-stripe { position:absolute; left:0; top:0; bottom:0; width:4px; }
.nba-body { padding:1.1rem 1.2rem 1rem 1.5rem; }
.nba-title { font-size:.92rem; font-weight:700; color:#181c32; margin-bottom:.4rem; }
.nba-content { font-size:.84rem; color:#5e6278; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; white-space:pre-line; margin-bottom:.55rem; }
.nba-meta { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.nba-footer { padding:.6rem 1.2rem; background:#f9f9f9; border-top:1px solid #f1f3f5; display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; font-size:.78rem; color:#7e8299; }

/* ── Notice card ────────────────────────────────────────────────────────────── */
.nbn-card { background:#fff; border-radius:12px; border:1px solid #e9edf0; overflow:hidden; position:relative; transition:box-shadow .18s,transform .18s; margin-bottom:.9rem; }
.nbn-card:hover { box-shadow:0 4px 20px rgba(0,0,0,.08); transform:translateY(-1px); }
.nbn-card.pinned { border-color:rgba(0,158,247,.3); background:linear-gradient(135deg,#f8fcff 0%,#fff 60%); }
.nbn-stripe { position:absolute; left:0; top:0; bottom:0; width:4px; }
.nbn-body { padding:1.1rem 1.2rem 1rem 1.5rem; }
.nbn-title { font-size:.92rem; font-weight:700; color:#181c32; margin-bottom:.4rem; }
.nbn-content { font-size:.84rem; color:#5e6278; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; white-space:pre-line; margin-bottom:.55rem; }
.nbn-meta { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.nbn-pin { display:inline-flex; align-items:center; gap:.3rem; font-size:.75rem; font-weight:600; color:#009ef7; }

/* ── Empty state ────────────────────────────────────────────────────────────── */
.nb-empty { text-align:center; padding:2.5rem 1rem; color:#a1a5b7; }
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Notices &amp; Announcements</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Notices &amp; Announcements</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<!-- ── School tabs ─────────────────────────────────────────────────────────── -->
<?php if (count($schools) > 1): ?>
<div class="nb-school-tabs">
    <?php foreach ($schools as $s): ?>
    <?php $isActive = (int)$s['sch_id'] === (int)$activeSchoolId; ?>
    <a href="<?= base_url('notices?sch_id=' . (int)$s['sch_id']) ?>"
       class="nb-tab <?= $isActive ? 'active' : 'inactive' ?>">
        <?php if (!empty($s['sch_logo'])): ?>
        <img src="<?= base_url('uploads/schoolLogo/' . esc($s['sch_logo'])) ?>" alt="">
        <?php else: ?>
        <i class="ki-outline ki-bank fs-6" style="flex-shrink:0;<?= $isActive ? 'color:#fff;' : '' ?>"></i>
        <?php endif; ?>
        <?= esc($s['sch_name']) ?>
    </a>
    <?php endforeach; ?>
</div>
<hr class="mt-0 mb-5" style="border-color:#c4c8d6;">
<?php elseif (!empty($schools)): ?>
<!-- Single school — show as header badge -->
<div class="d-flex align-items-center gap-2 mb-4">
    <?php if (!empty($schools[0]['sch_logo'])): ?>
    <img src="<?= base_url('uploads/school/logo/' . esc($schools[0]['sch_logo'])) ?>"
         alt="" style="height:32px;width:32px;object-fit:contain;border-radius:4px;border:1px solid #e9edf0;">
    <?php else: ?>
    <i class="ki-duotone ki-bank fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
    <?php endif; ?>
    <span class="fw-bold fs-5 text-gray-800"><?= esc($schools[0]['sch_name']) ?></span>
</div>
<hr class="mt-0 mb-5" style="border-color:#c4c8d6;">
<?php endif; ?>

<?php if (empty($schools)): ?>
<div class="alert alert-warning d-flex align-items-center gap-3 p-4 rounded-3">
    <i class="ki-duotone ki-information-5 fs-1 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
    <div>
        <div class="fw-bold">No school linked to your account</div>
        <div class="text-muted fs-7">Contact your school administrator to link your account to a school.</div>
    </div>
</div>
<?php else: ?>

<!-- ── Two-column layout ──────────────────────────────────────────────────── -->
<div class="row g-6">

    <!-- LEFT: Announcements -->
    <div class="col-lg-6">
        <div class="nb-sec-head">
            <i class="ki-duotone ki-notification-bing fs-3 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <span class="nb-sec-title">Announcements</span>
            <span class="badge badge-light-info ms-auto"><?= count($announcements) ?></span>
        </div>

        <?php if (empty($announcements)): ?>
        <div class="nb-empty">
            <i class="ki-duotone ki-notification fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="fw-semibold text-gray-600">No announcements</div>
            <div class="text-muted fs-8 mt-1">Check back later for updates from your school.</div>
        </div>
        <?php else: ?>
        <?php foreach ($announcements as $ann):
            $pri  = $annPriority[$ann['priority']] ?? $annPriority['Info'];
            $hasAttachment = !empty($ann['attachment']);
            $poster = trim(($ann['fname'] ?? '') . ' ' . ($ann['lname'] ?? ''));
        ?>
        <div class="nba-card">
            <div class="nba-stripe" style="background:<?= $pri['color'] ?>;"></div>
            <div class="nba-body">
                <div class="nba-title"><?= esc($ann['title']) ?></div>
                <?php if ($ann['content']): ?>
                <div class="nba-content"><?= esc(strip_tags($ann['content'])) ?></div>
                <?php endif; ?>
                <div class="nba-meta">
                    <span class="badge <?= $pri['badge'] ?> fs-8"><?= $pri['label'] ?></span>
                    <?php if ($poster): ?>
                    <span class="text-muted fs-8">By <?= esc($poster) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="nba-footer">
                <i class="ki-duotone ki-clock fs-6 text-gray-400"><span class="path1"></span><span class="path2"></span></i>
                <?= nb_age($ann['created_at']) ?>
                <?php if (!empty($ann['expires_at'])): ?>
                &bull;
                <i class="ki-duotone ki-calendar-tick fs-6 text-gray-400"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                Expires <?= date('d M Y', strtotime($ann['expires_at'])) ?>
                <?php endif; ?>
                <?php if ($hasAttachment): ?>
                <a href="<?= base_url('dashboard/announcement/' . $ann['announcement_id'] . '/download') ?>"
                   class="btn btn-light-primary btn-sm ms-auto" style="font-size:.76rem;padding:.2rem .7rem;">
                    <i class="ki-duotone ki-file-down fs-6"><span class="path1"></span><span class="path2"></span></i>
                    Download
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- RIGHT: Notices -->
    <div class="col-lg-6">
        <div class="nb-sec-head">
            <i class="ki-duotone ki-bulletin-board fs-3 text-warning"><span class="path1"></span><span class="path2"></span></i>
            <span class="nb-sec-title">Notice Board</span>
            <span class="badge badge-light-warning ms-auto"><?= count($notices) ?></span>
        </div>

        <?php if (empty($notices)): ?>
        <div class="nb-empty">
            <i class="ki-duotone ki-bulletin-board fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span></i>
            <div class="fw-semibold text-gray-600">No notices</div>
            <div class="text-muted fs-8 mt-1">No notices have been posted for you yet.</div>
        </div>
        <?php else: ?>

        <!-- Pinned notices first -->
        <?php if (!empty($pinnedNotices)): ?>
        <div class="text-muted fs-8 fw-bold text-uppercase mb-2" style="letter-spacing:.5px;">Pinned</div>
        <?php foreach ($pinnedNotices as $n):
            $pri = $noticePriority[$n['priority']] ?? $noticePriority['Normal'];
            $aud = $audienceCfg[$n['audience']]    ?? $audienceCfg['All'];
            $poster = trim(($n['fname'] ?? '') . ' ' . ($n['lname'] ?? ''));
        ?>
        <div class="nbn-card pinned">
            <div class="nbn-stripe" style="background:<?= $pri['color'] ?>;"></div>
            <div class="nbn-body">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                    <div class="nbn-title"><?= esc($n['title']) ?></div>
                    <span class="nbn-pin" style="white-space:nowrap;">
                        <i class="ki-duotone ki-pin fs-6 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        Pinned
                    </span>
                </div>
                <?php if ($n['content']): ?>
                <div class="nbn-content"><?= esc(strip_tags($n['content'])) ?></div>
                <?php endif; ?>
                <div class="nbn-meta">
                    <span class="badge <?= $pri['badge'] ?> fs-8"><?= $pri['label'] ?></span>
                    <span class="badge <?= $aud['badge'] ?> fs-8"><?= $aud['label'] ?></span>
                    <?php if ($poster): ?>
                    <span class="text-muted fs-8">By <?= esc($poster) ?></span>
                    <?php endif; ?>
                    <span class="text-muted fs-8 ms-auto"><?= nb_age($n['created_at']) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Regular notices -->
        <?php if (!empty($pinnedNotices) && !empty($regularNotices)): ?>
        <div class="text-muted fs-8 fw-bold text-uppercase mb-2 mt-3" style="letter-spacing:.5px;">All Notices</div>
        <?php endif; ?>
        <?php foreach ($regularNotices as $n):
            $pri = $noticePriority[$n['priority']] ?? $noticePriority['Normal'];
            $aud = $audienceCfg[$n['audience']]    ?? $audienceCfg['All'];
            $poster = trim(($n['fname'] ?? '') . ' ' . ($n['lname'] ?? ''));
        ?>
        <div class="nbn-card">
            <div class="nbn-stripe" style="background:<?= $pri['color'] ?>;"></div>
            <div class="nbn-body">
                <div class="nbn-title"><?= esc($n['title']) ?></div>
                <?php if ($n['content']): ?>
                <div class="nbn-content"><?= esc(strip_tags($n['content'])) ?></div>
                <?php endif; ?>
                <div class="nbn-meta">
                    <span class="badge <?= $pri['badge'] ?> fs-8"><?= $pri['label'] ?></span>
                    <span class="badge <?= $aud['badge'] ?> fs-8"><?= $aud['label'] ?></span>
                    <?php if ($poster): ?>
                    <span class="text-muted fs-8">By <?= esc($poster) ?></span>
                    <?php endif; ?>
                    <span class="text-muted fs-8 ms-auto"><?= nb_age($n['created_at']) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; // notices not empty ?>
    </div>

</div><!-- /row -->

<?php endif; // schools not empty ?>

</div><!-- /container -->
</div><!-- /content -->
