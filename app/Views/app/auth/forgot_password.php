<!--begin::Form-->
<form class="form w-100" method="POST" action="<?= base_url('auth/forgot-password') ?>" id="kt_password_reset_form">
    <?= csrf_field() ?>

    <!--begin::Heading-->
    <div class="text-center mb-10">
        <h1 class="text-gray-900 fw-bolder mb-3">Forgot Password?</h1>
        <div class="text-gray-500 fw-semibold fs-6">
            Enter your email address and we'll send you a link to reset your password.
        </div>
    </div>
    <!--end::Heading-->

    <!--begin::Success Alert-->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success d-flex align-items-center p-5 mb-8">
        <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4 flex-shrink-0">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div class="d-flex flex-column">
            <span class="fw-semibold fs-6"><?= session()->getFlashdata('success') ?></span>
        </div>
    </div>
    <?php endif; ?>
    <!--end::Success Alert-->

    <!--begin::Error Alert-->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger d-flex align-items-center p-5 mb-8">
        <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4 flex-shrink-0">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div class="d-flex flex-column">
            <span class="fw-semibold fs-6"><?= session()->getFlashdata('error') ?></span>
        </div>
    </div>
    <?php endif; ?>
    <!--end::Error Alert-->

    <!--begin::Email Input-->
    <div class="fv-row mb-8">
        <label class="form-label fw-bold text-gray-900 fs-6 required">Email Address</label>
        <input type="email"
               class="form-control form-control-solid"
               name="email"
               placeholder="Enter your email address"
               value="<?= old('email') ?>"
               autocomplete="email"
               required />
    </div>
    <!--end::Email Input-->

    <!--begin::Actions-->
    <div class="d-flex flex-wrap justify-content-center pb-lg-0 gap-3">
        <button type="submit" class="btn btn-primary" id="kt_password_reset_submit">
            <span class="indicator-label">
                <i class="ki-duotone ki-send fs-4 me-2">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Send Reset Link
            </span>
            <span class="indicator-progress">
                Sending...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
        <a href="<?= base_url('auth/login') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-4 me-2">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back to Login
        </a>
    </div>
    <!--end::Actions-->

</form>
<!--end::Form-->

<script>
document.getElementById('kt_password_reset_form').addEventListener('submit', function() {
    document.getElementById('kt_password_reset_submit').setAttribute('data-kt-indicator', 'on');
});
</script>