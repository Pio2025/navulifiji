<?php
$pr_children      = $pr_children      ?? [];
$pr_announcements = $pr_announcements ?? [];
$pr_no_children   = $pr_no_children   ?? false;

$parentFname    = session('fname') ?? 'Parent';
$parentPhoto    = session('photo');
$parentPhotoUrl = ($parentPhoto && file_exists(FCPATH . 'uploads/profilePhoto/' . $parentPhoto))
                  ? base_url('uploads/profilePhoto/' . $parentPhoto)
                  : base_url('app/assets/media/avatars/blank.png');
?>
<?php include APPPATH . 'Views/app/dashboard/_parent_styles.php'; ?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Parent Dashboard</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">Home</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Parent</li>
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
    <?php $activeIndex = 0; include APPPATH . 'Views/app/dashboard/_parent_child_tabs.php'; ?>
</div>
<?php endif; ?>

<!-- ── Per-child panels ───────────────────────────────────────────────────── -->
<?php $activeIndex = 0; include APPPATH . 'Views/app/dashboard/_parent_child_panels.php'; ?>

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
