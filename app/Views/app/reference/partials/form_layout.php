<?php
// Usage: include this and set $formConfig array before including
// $formConfig = ['title' => '', 'breadcrumb' => '', 'generateUrl' => '', 'formId' => '']
?>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($formConfig['title']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('user') ?>" class="text-muted text-hover-primary">Users</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('user/detail/' . $userID) ?>" class="text-muted text-hover-primary">
                        <?= esc($user['fname'] . ' ' . $user['lname']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($formConfig['title']) ?></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('reference/user-references/' . $userID) ?>" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-folder fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                All References
            </a>
            <a href="<?= base_url('user/detail/' . $userID) ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Back to Profile
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->