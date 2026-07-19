<?php
$pr_children      = $pr_children      ?? [];
$pr_announcements = $pr_announcements ?? [];
?>
<?php include APPPATH . 'Views/app/dashboard/_parent_styles.php'; ?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Dashboard</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Dashboard</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted fs-7 fw-semibold me-2 d-none d-md-inline-flex align-items-center">
                <i class="ki-duotone ki-calendar fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                <?= date('l, d F Y') ?>
            </span>
            <a href="<?= base_url('classroom/my') ?>" class="btn btn-sm btn-light-primary fw-semibold">
                <i class="ki-duotone ki-teacher fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                My Classrooms
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<div class="pd-child-tabs mb-2" id="pd-child-tabs">
    <button type="button" class="pd-child-tab active" data-child="self">
        <i class="ki-duotone ki-teacher fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
        My Dashboard
    </button>
    <?php $activeIndex = null; include APPPATH . 'Views/app/dashboard/_parent_child_tabs.php'; ?>
</div>

<div class="pd-child-panel active" id="pd-panel-self">
<?php include APPPATH . 'Views/app/dashboard/_teacher_body.php'; ?>
</div>

<?php $activeIndex = null; include APPPATH . 'Views/app/dashboard/_parent_child_panels.php'; ?>

</div><!--end::Content container-->
</div><!--end::Content-->

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
