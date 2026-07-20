<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc(session('roleCatName') ?: 'School Admin') ?> Dashboard
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc(session('roleCatName') ?: 'Admin') ?></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted fs-7 fw-semibold d-none d-md-inline-flex align-items-center me-1">
                <i class="ki-duotone ki-calendar fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                <?= date('l, d F Y') ?>
            </span>
            <a href="<?= base_url('dashboard/announcement') ?>" class="btn btn-sm btn-light-primary fw-bold">
                <i class="ki-duotone ki-notification-bing fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Announcements
            </a>
            <a href="<?= base_url('dashboard/notice') ?>" class="btn btn-sm btn-light-warning fw-bold">
                <i class="ki-duotone ki-message-text-2 fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Notice Board
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php include APPPATH . 'Views/app/dashboard/_school_admin_body.php'; ?>

</div>
</div>
<!--end::Content-->
