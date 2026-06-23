<!--begin::Form-->
<form class="form w-100" method="POST"
      action="<?= base_url('auth/reset-password/' . $token) ?>"
      id="kt_new_password_form">
    <?= csrf_field() ?>

    <!--begin::Heading-->
    <div class="text-center mb-10">
        <h1 class="text-gray-900 fw-bolder mb-3">Set New Password</h1>
        <div class="text-gray-500 fw-semibold fs-6">
            Creating a new password for
            <span class="text-primary fw-bold"><?= esc($email) ?></span>
        </div>
    </div>
    <!--end::Heading-->

    <!--begin::Error Alerts-->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger d-flex align-items-center p-5 mb-8">
        <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4 flex-shrink-0">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <span class="fw-semibold"><?= session()->getFlashdata('error') ?></span>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger d-flex align-items-start p-5 mb-8">
        <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4 flex-shrink-0 mt-1">
            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
        </i>
        <div>
            <div class="fw-bold mb-2">Please fix the following:</div>
            <ul class="mb-0 ps-4">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li class="fs-7"><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    <!--end::Error Alerts-->

    <!--begin::New Password-->
    <div class="fv-row mb-8" data-kt-password-meter="true">
        <div class="mb-1">
            <label class="form-label fw-bold text-gray-900 fs-6 required">New Password</label>
            <div class="position-relative mb-3">
                <input class="form-control form-control-solid"
                       type="password"
                       name="password"
                       placeholder="Enter new password"
                       autocomplete="new-password"
                       id="new_password"
                       required />
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                      data-kt-password-meter-control="visibility">
                    <i class="ki-duotone ki-eye-slash fs-2">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span>
                    </i>
                    <i class="ki-duotone ki-eye d-none fs-2">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                </span>
            </div>
            <!--begin::Meter-->
            <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
            </div>
            <!--end::Meter-->
        </div>
        <div class="text-muted fs-7">
            Use 8+ characters with uppercase letters, numbers and symbols.
        </div>
    </div>
    <!--end::New Password-->

    <!--begin::Confirm Password-->
    <div class="fv-row mb-8">
        <label class="form-label fw-bold text-gray-900 fs-6 required">Confirm Password</label>
        <input class="form-control form-control-solid"
               type="password"
               name="confirm_password"
               placeholder="Confirm new password"
               autocomplete="new-password"
               id="confirm_password"
               required />
        <div id="password_match_msg" class="mt-2 fs-8" style="display:none;"></div>
    </div>
    <!--end::Confirm Password-->

    <!--begin::Password Requirements-->
    <div class="mb-8 p-4 bg-light-primary rounded border border-primary border-dashed">
        <div class="fw-bold text-gray-700 fs-7 mb-2">Password Requirements:</div>
        <div class="d-flex flex-column gap-1">
            <div class="d-flex align-items-center fs-8" id="req_length">
                <i class="ki-duotone ki-cross-circle fs-5 text-danger me-2 req-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="text-muted">At least 8 characters</span>
            </div>
            <div class="d-flex align-items-center fs-8" id="req_upper">
                <i class="ki-duotone ki-cross-circle fs-5 text-danger me-2 req-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="text-muted">At least one uppercase letter</span>
            </div>
            <div class="d-flex align-items-center fs-8" id="req_number">
                <i class="ki-duotone ki-cross-circle fs-5 text-danger me-2 req-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="text-muted">At least one number</span>
            </div>
            <div class="d-flex align-items-center fs-8" id="req_match">
                <i class="ki-duotone ki-cross-circle fs-5 text-danger me-2 req-icon">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="text-muted">Passwords match</span>
            </div>
        </div>
    </div>
    <!--end::Password Requirements-->

    <!--begin::Actions-->
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <button type="submit" class="btn btn-primary" id="btn_reset_submit">
            <span class="indicator-label">
                <i class="ki-duotone ki-lock-3 fs-4 me-2">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                Reset Password
            </span>
            <span class="indicator-progress">
                Saving...
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
"use strict";

const pwInput  = document.getElementById('new_password');
const cfInput  = document.getElementById('confirm_password');
const submitBtn = document.getElementById('btn_reset_submit');

function checkReq(id, condition) {
    const el   = document.getElementById(id);
    const icon = el.querySelector('.req-icon');
    if (condition) {
        icon.className = 'ki-duotone ki-check-circle fs-5 text-success me-2 req-icon';
        icon.innerHTML = '<span class="path1"></span><span class="path2"></span>';
        el.querySelector('span:last-child').className = 'text-success fw-semibold';
    } else {
        icon.className = 'ki-duotone ki-cross-circle fs-5 text-danger me-2 req-icon';
        icon.innerHTML = '<span class="path1"></span><span class="path2"></span>';
        el.querySelector('span:last-child').className = 'text-muted';
    }
}

function validateAll() {
    const pw  = pwInput.value;
    const cf  = cfInput.value;

    const hasLength = pw.length >= 8;
    const hasUpper  = /[A-Z]/.test(pw);
    const hasNumber = /[0-9]/.test(pw);
    const hasMatch  = pw === cf && pw.length > 0;

    checkReq('req_length', hasLength);
    checkReq('req_upper',  hasUpper);
    checkReq('req_number', hasNumber);
    checkReq('req_match',  hasMatch);

    return hasLength && hasUpper && hasNumber && hasMatch;
}

pwInput.addEventListener('input', validateAll);
cfInput.addEventListener('input', validateAll);

document.getElementById('kt_new_password_form').addEventListener('submit', function(e) {
    if (!validateAll()) {
        e.preventDefault();
        Swal.fire({
            title: 'Password Requirements Not Met',
            html:  'Please ensure your password meets all the requirements shown below the form.',
            icon:  'warning',
            buttonsStyling: false,
            confirmButtonText: 'OK',
            customClass: { confirmButton: 'btn btn-warning' }
        });
        return;
    }
    submitBtn.setAttribute('data-kt-indicator', 'on');
});
</script>