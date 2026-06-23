<!--begin::Invalid Token-->
<div class="text-center">
    <div class="symbol symbol-100px symbol-circle mx-auto mb-7">
        <div class="symbol-label bg-light-danger">
            <i class="ki-duotone ki-shield-cross fs-4x text-danger">
                <span class="path1"></span><span class="path2"></span>
            </i>
        </div>
    </div>

    <h1 class="text-gray-900 fw-bolder mb-3">Link Expired or Invalid</h1>
    <div class="text-gray-500 fw-semibold fs-6 mb-8">
        This password reset link has either expired or already been used.
        Reset links are only valid for <strong>1 hour</strong>.
    </div>

    <div class="notice d-flex bg-light-warning rounded border border-warning border-dashed p-5 mb-8 text-start">
        <i class="ki-duotone ki-information fs-2tx text-warning me-4 flex-shrink-0">
            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
        </i>
        <div class="fs-7 text-gray-700">
            For security, password reset links expire after 1 hour and can only be used once.
            Please request a new link if you still need to reset your password.
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="<?= base_url('auth/forgot-password') ?>" class="btn btn-primary">
            <i class="ki-duotone ki-lock fs-4 me-2">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Request New Reset Link
        </a>
        <a href="<?= base_url('auth/login') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-4 me-2">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back to Login
        </a>
    </div>
</div>
<!--end::Invalid Token-->