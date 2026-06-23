<?php
$profileUserId = session()->get('userID') ?? ($user['user_id'] ?? null);
$profileUrl    = $profileUserId
    ? base_url('user/detail/' . $profileUserId)
    : base_url('auth/login');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Email Verification</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('user') ?>" class="text-muted text-hover-primary">Users</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Email Verification</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <?php if ($status === 'success'): ?>
                <!--begin::Success Card-->
                <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                    <div class="card-body p-10 text-center">

                        <!--begin::Icon-->
                        <div class="mb-8">
                            <div class="d-inline-flex align-items-center justify-content-center w-100px h-100px rounded-circle bg-light-success mb-4">
                                <i class="ki-duotone ki-check-circle fs-3tx text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <h2 class="fw-bold text-gray-900 fs-1 mb-2">Email Verified!</h2>
                            <p class="text-muted fs-6 mb-0">Bula Vinaka, <?= esc($name) ?>! Your email has been successfully updated.</p>
                        </div>
                        <!--end::Icon-->

                        <!--begin::Details-->
                        <div style="background-color:#F9F9F9; border-radius:8px; padding:8px 20px; margin-bottom:24px;">
                            <div class="d-flex justify-content-between align-items-center py-3" style="border-bottom:1px dashed #E4E6EF;">
                                <span class="text-muted fw-semibold fs-7">Previous Email</span>
                                <span class="text-gray-700 fw-bold fs-7 text-decoration-line-through"><?= esc($old_email) ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3" style="border-bottom:1px dashed #E4E6EF;">
                                <span class="text-muted fw-semibold fs-7">New Email</span>
                                <span class="text-success fw-bold fs-7"><?= esc($new_email) ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted fw-semibold fs-7">Changed On</span>
                                <span class="text-gray-700 fw-bold fs-7"><?= esc($date) ?></span>
                            </div>
                        </div>
                        <!--end::Details-->

                        <!--begin::Notice-->
                        <div class="notice d-flex bg-light-info rounded border border-info border-dashed p-4 mb-8 text-start">
                            <i class="ki-duotone ki-information fs-2tx text-info me-4 flex-shrink-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div>
                                <h5 class="fw-bold text-gray-900 mb-1 fs-7">Confirmation emails sent</h5>
                                <p class="text-muted fs-7 mb-0">A confirmation has been sent to your new email and a security notice to your old email address.</p>
                            </div>
                        </div>
                        <!--end::Notice-->

                        <!--begin::Action-->
                        <div class="d-flex flex-column gap-3">
                            <a href="<?= base_url('auth/logout') ?>" class="btn btn-success">
                                <i class="ki-duotone ki-entrance-right fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Login with New Email
                            </a>
                            <a href="<?= $profileUrl ?>" class="btn btn-light-primary">
                                <i class="ki-duotone ki-arrow-left fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Back to User Profile
                            </a>
                        </div>
                        <!--end::Action-->

                    </div>
                </div>
                <!--end::Success Card-->

                <?php elseif ($status === 'expired'): ?>
                <!--begin::Expired Card-->
                <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                    <div class="card-body p-10 text-center">

                        <!--begin::Icon-->
                        <div class="mb-8">
                            <div class="d-inline-flex align-items-center justify-content-center w-100px h-100px rounded-circle bg-light-warning mb-4">
                                <i class="ki-duotone ki-timer fs-3tx text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                            <h2 class="fw-bold text-gray-900 fs-1 mb-2">Link Expired</h2>
                            <p class="text-muted fs-6 mb-0">
                                <?php if (!empty($name)): ?>
                                    Bula <?= esc($name) ?>, your
                                <?php else: ?>
                                    Your
                                <?php endif; ?>
                                verification link has expired after 24 hours.
                            </p>
                        </div>
                        <!--end::Icon-->

                        <!--begin::Notice-->
                        <div class="notice d-flex bg-light-warning rounded border border-warning border-dashed p-4 mb-8 text-start">
                            <i class="ki-duotone ki-information fs-2tx text-warning me-4 flex-shrink-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div>
                                <h5 class="fw-bold text-gray-900 mb-1 fs-7">What to do next?</h5>
                                <p class="text-muted fs-7 mb-0">Log in to your account and request a new email change. A fresh verification link will be sent to your new email address.</p>
                            </div>
                        </div>
                        <!--end::Notice-->

                        <!--begin::Action-->
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex gap-3">
                                <a href="<?= base_url('auth/login') ?>" class="btn btn-warning flex-grow-1">
                                    <i class="ki-duotone ki-entrance-right fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Back to Login
                                </a>
                                <a href="mailto:info@navulifiji.com" class="btn btn-light flex-grow-1">
                                    <i class="ki-duotone ki-sms fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Contact Support
                                </a>
                            </div>
                            <a href="<?= $profileUrl ?>" class="btn btn-light-primary">
                                <i class="ki-duotone ki-arrow-left fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Back to User Profile
                            </a>
                        </div>
                        <!--end::Action-->

                    </div>
                </div>
                <!--end::Expired Card-->

                <?php else: ?>
                <!--begin::Error Card-->
                <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                    <div class="card-body p-10 text-center">

                        <!--begin::Icon-->
                        <div class="mb-8">
                            <div class="d-inline-flex align-items-center justify-content-center w-100px h-100px rounded-circle bg-light-danger mb-4">
                                <i class="ki-duotone ki-cross-circle fs-3tx text-danger">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <h2 class="fw-bold text-gray-900 fs-1 mb-2">Verification Failed</h2>
                            <p class="text-muted fs-6 mb-0"><?= esc($message) ?></p>
                        </div>
                        <!--end::Icon-->

                        <!--begin::Notice-->
                        <div class="notice d-flex bg-light-danger rounded border border-danger border-dashed p-4 mb-8 text-start">
                            <i class="ki-duotone ki-information fs-2tx text-danger me-4 flex-shrink-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div>
                                <h5 class="fw-bold text-gray-900 mb-1 fs-7">Need assistance?</h5>
                                <p class="text-muted fs-7 mb-0">
                                    Contact our support team at
                                    <a href="mailto:info@navulifiji.com" class="text-danger fw-bold">info@navulifiji.com</a>
                                    or call <strong>+679 9896700</strong>.
                                </p>
                            </div>
                        </div>
                        <!--end::Notice-->

                        <!--begin::Action-->
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex gap-3">
                                <a href="<?= base_url('auth/login') ?>" class="btn btn-danger flex-grow-1">
                                    <i class="ki-duotone ki-entrance-right fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Back to Login
                                </a>
                                <a href="mailto:info@navulifiji.com" class="btn btn-light flex-grow-1">
                                    <i class="ki-duotone ki-sms fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Contact Support
                                </a>
                            </div>
                            <a href="<?= $profileUrl ?>" class="btn btn-light-primary">
                                <i class="ki-duotone ki-arrow-left fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Back to User Profile
                            </a>
                        </div>
                        <!--end::Action-->

                    </div>
                </div>
                <!--end::Error Card-->
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<!--end::Content-->