<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Two-Factor Verification — Navuli Fiji</title>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/global/plugins.bundle.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.bundle.css') ?>" />
    <style>
        body { background: linear-gradient(135deg, #f5f8fa 0%, #e8f0fe 100%); }
        .otp-digit {
            width: 52px !important;
            height: 52px !important;
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            text-align: center !important;
            border: 2px solid #E4E6EF !important;
            border-radius: 8px !important;
            background: #fff !important;
            transition: border-color 0.15s, box-shadow 0.15s !important;
            padding: 0 !important;
        }
        .otp-digit:focus {
            border-color: #009ef7 !important;
            box-shadow: 0 0 0 3px rgba(0,158,247,0.15) !important;
            outline: none !important;
        }
        .otp-digit.is-invalid {
            border-color: #f1416c !important;
            box-shadow: 0 0 0 3px rgba(241,65,108,0.15) !important;
            animation: shake 0.3s ease;
        }
        .otp-digit.is-valid {
            border-color: #50cd89 !important;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25%       { transform: translateX(-4px); }
            75%       { transform: translateX(4px); }
        }
    </style>
</head>
<body id="kt_body">

<div class="d-flex flex-column flex-root" style="min-height:100vh;">
<div class="d-flex flex-column flex-center flex-column-fluid py-10">
<div class="w-lg-480px bg-white rounded shadow-sm p-10 p-lg-15 mx-auto w-100" >

    <!--begin::Logo-->
    <div class="text-center mb-8">
        <img src="<?= base_url('navuli_icon_small_color.png') ?>"
             alt="Navuli Fiji" style="height:55px;" class="mb-4" />
        <h1 class="text-gray-900 fw-bolder mb-2 fs-2x">Two-Factor Verification</h1>
        <div class="text-gray-500 fw-semibold fs-6">
            <?php if ($method === 'authenticator'): ?>
                Enter the 6-digit code from your authenticator app
            <?php else: ?>
                Enter the 6-digit code sent to your email
            <?php endif; ?>
        </div>
    </div>
    <!--end::Logo-->

    <!--begin::Error from redirect-->
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger d-flex align-items-center p-4 mb-6" id="server_error_alert">
        <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-3 flex-shrink-0">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <span class="fw-semibold"><?= esc($error) ?></span>
    </div>
    <?php endif; ?>
    <!--end::Error-->

    <!--begin::Method icon-->
    <div class="text-center mb-7">
        <?php if ($method === 'authenticator'): ?>
        <div class="symbol symbol-70px symbol-circle mx-auto mb-3">
            <div class="symbol-label bg-light-primary">
                <i class="ki-duotone ki-phone fs-2tx text-primary">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
            </div>
        </div>
        <p class="text-muted fs-7 mb-0">Open your authenticator app and enter the current code.</p>
        <?php else: ?>
        <div class="symbol symbol-70px symbol-circle mx-auto mb-3">
            <div class="symbol-label bg-light-warning">
                <i class="ki-duotone ki-sms fs-2tx text-warning">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>
        </div>
        <p class="text-muted fs-7 mb-0">Check your email inbox for the verification code.</p>
        <?php endif; ?>
    </div>
    <!--end::Method icon-->

    <!--begin::Form-->
    <form method="POST" action="<?= base_url('auth/2fa/verify') ?>" id="verify_form">
        <?= csrf_field() ?>

        <!--begin::OTP inputs-->
        <div class="mb-8">
            <label class="form-label fw-semibold text-gray-700 fs-7 text-center d-block mb-4">
                Verification Code
            </label>
            <div class="d-flex justify-content-center align-items-center gap-2" id="otp_inputs_wrapper">
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric"
                       autocomplete="one-time-code" data-index="0" />
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric"
                       data-index="1" />
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric"
                       data-index="2" />

                <span class="text-muted fw-bold fs-3 mx-1">—</span>

                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric"
                       data-index="3" />
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric"
                       data-index="4" />
                <input type="text" class="otp-digit" maxlength="1" inputmode="numeric"
                       data-index="5" />
            </div>
            <input type="hidden" name="code" id="otp_hidden_code" />
        </div>
        <!--end::OTP inputs-->

        <!--begin::Submit-->
        <div class="d-grid mb-5">
            <button type="submit" class="btn btn-primary btn-lg" id="btn_verify">
                <span class="indicator-label">
                    <i class="ki-duotone ki-shield-tick fs-3 me-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Verify &amp; Sign In
                </span>
                <span class="indicator-progress">
                    Verifying...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <!--end::Submit-->

        <!--begin::Resend (OTP email only)-->
        <?php if ($method === 'otp_email'): ?>
        <div class="text-center mb-5">
            <span class="text-muted fs-7">Didn't receive the code? </span>
            <a href="#" class="fw-bold fs-7" id="btn_resend_otp">Resend Code</a>
            <span class="text-muted fs-8 ms-1" id="resend_countdown"></span>
        </div>
        <div id="resend_success_msg" class="text-center text-success fs-8 mb-3" style="display:none;"></div>
        <?php endif; ?>
        <!--end::Resend-->

        <!--begin::Separator-->
        <div class="separator separator-content my-5">
            <span class="text-muted fw-semibold fs-7">or</span>
        </div>
        <!--end::Separator-->

        <!--begin::Back-->
        <div class="text-center">
            <a href="<?= base_url('auth/login') ?>"
               class="btn btn-light btn-sm"
               onclick="return clearPending();">
                <i class="ki-duotone ki-arrow-left fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Back to Login
            </a>
        </div>
        <!--end::Back-->

    </form>
    <!--end::Form-->

    <!--begin::Footer-->
    <div class="text-center mt-8">
        <span class="text-muted fs-9">&copy; <?= date('Y') ?> Navuli Fiji School Management System</span>
    </div>
    <!--end::Footer-->

</div>
</div>
</div>

<script src="<?= base_url('assets/plugins/global/plugins.bundle.js') ?>"></script>
<script src="<?= base_url('assets/js/scripts.bundle.js') ?>"></script>

<script>
"use strict";

const digits     = Array.from(document.querySelectorAll('.otp-digit'));
const hiddenCode = document.getElementById('otp_hidden_code');
const verifyBtn  = document.getElementById('btn_verify');

// ── Auto-focus first digit on load ───────────────────────────────
digits[0].focus();

// ── Sync all digits into hidden input ────────────────────────────
function syncHidden() {
    hiddenCode.value = digits.map(d => d.value).join('');
}

// ── Clear all invalid states ──────────────────────────────────────
function clearInvalid() {
    digits.forEach(d => d.classList.remove('is-invalid'));
}

// ── Digit input events ────────────────────────────────────────────
digits.forEach((input, index) => {

    // Block non-numeric keydown
    input.addEventListener('keydown', function(e) {
        if (['Backspace','Delete','Tab','ArrowLeft','ArrowRight',
             'ArrowUp','ArrowDown'].includes(e.key)) return;
        if (!/^\d$/.test(e.key)) e.preventDefault();
    });

    input.addEventListener('input', function() {
        // Keep only one digit
        this.value = this.value.replace(/\D/g, '').slice(0, 1);
        syncHidden();
        clearInvalid();

        // Mark valid
        if (this.value) {
            this.classList.add('is-valid');
            // Auto advance
            if (index < digits.length - 1) digits[index + 1].focus();
        } else {
            this.classList.remove('is-valid');
        }
    });

    input.addEventListener('keyup', function(e) {
        if (e.key === 'Backspace') {
            this.value = '';
            this.classList.remove('is-valid', 'is-invalid');
            syncHidden();
            if (index > 0) digits[index - 1].focus();
        }
    });

    // Paste handler — paste full 6-digit code into any cell
    input.addEventListener('paste', function(e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData)
            .getData('text')
            .replace(/\D/g, '')
            .slice(0, 6);

        digits.forEach((d, i) => {
            d.value = pasted[i] || '';
            d.classList.remove('is-invalid');
            if (d.value) d.classList.add('is-valid');
            else d.classList.remove('is-valid');
        });

        syncHidden();

        // Focus the next empty or last
        const nextEmpty = digits.find(d => !d.value);
        (nextEmpty || digits[digits.length - 1]).focus();
    });

    // Click — select content for easy replacement
    input.addEventListener('click', function() {
        this.select();
    });
});

// ── Form submit ───────────────────────────────────────────────────
document.getElementById('verify_form').addEventListener('submit', function(e) {
    e.preventDefault();
    syncHidden();

    const code = hiddenCode.value;

    // ── Client-side validation — SweetAlert, NO disabled ─────────
    if (code.length !== 6 || !/^\d{6}$/.test(code)) {
        // Highlight empty fields
        digits.forEach(d => {
            if (!d.value) d.classList.add('is-invalid');
        });

        // Focus first empty
        const firstEmpty = digits.find(d => !d.value);
        if (firstEmpty) firstEmpty.focus();

        Swal.fire({
            title: 'Validation Error',
            html:  'Please enter the complete <strong>6-digit</strong> verification code before submitting.',
            icon:  'warning',
            buttonsStyling: false,
            confirmButtonText: 'OK, I\'ll enter it',
            customClass: {
                confirmButton: 'btn btn-warning'
            }
        });
        return; // ← fields remain enabled
    }

    // ── All good — submit ─────────────────────────────────────────
    verifyBtn.setAttribute('data-kt-indicator', 'on');
    verifyBtn.disabled = true;
    this.submit();
});

// ── Clear pending 2FA session when going back ─────────────────────
function clearPending() {
    fetch('<?= base_url('auth/2fa/cancel') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    });
    return true;
}

<?php if ($method === 'otp_email'): ?>
// ── Resend countdown ──────────────────────────────────────────────
let countdownVal  = 60;
const resendBtn   = document.getElementById('btn_resend_otp');
const cdownSpan   = document.getElementById('resend_countdown');
const resendMsg   = document.getElementById('resend_success_msg');
let   countTimer  = null;

function startCountdown() {
    countdownVal = 60;
    resendBtn.style.pointerEvents = 'none';
    resendBtn.style.opacity       = '0.4';

    if (countTimer) clearInterval(countTimer);

    countTimer = setInterval(() => {
        countdownVal--;
        cdownSpan.textContent = '(' + countdownVal + 's)';
        if (countdownVal <= 0) {
            clearInterval(countTimer);
            cdownSpan.textContent         = '';
            resendBtn.style.pointerEvents = 'auto';
            resendBtn.style.opacity       = '1';
        }
    }, 1000);
}

// Start countdown immediately — OTP was sent on page load
startCountdown();

resendBtn.addEventListener('click', function(e) {
    e.preventDefault();
    if (this.style.pointerEvents === 'none') return;

    resendMsg.style.display = 'none';
    resendBtn.textContent   = 'Sending...';

    fetch('<?= base_url('auth/2fa/resend-otp') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(r => r.json())
    .then(response => {
        resendBtn.textContent = 'Resend Code';

        if (response.success) {
            // Hide any server error alert
            const alertBox = document.getElementById('server_error_alert');
            if (alertBox) alertBox.style.display = 'none';

            // Show success message
            resendMsg.style.color   = '#50cd89';
            resendMsg.textContent   = response.message;
            resendMsg.style.display = 'block';

            startCountdown();

            // Clear inputs for fresh entry
            digits.forEach(d => {
                d.value = '';
                d.classList.remove('is-valid', 'is-invalid');
            });
            hiddenCode.value = '';
            digits[0].focus();

        } else {
            resendMsg.style.color   = '#f1416c';
            resendMsg.textContent   = response.message || 'Failed to send. Try again.';
            resendMsg.style.display = 'block';
        }
    })
    .catch(() => {
        resendBtn.textContent   = 'Resend Code';
        resendMsg.style.color   = '#f1416c';
        resendMsg.textContent   = 'Network error. Please try again.';
        resendMsg.style.display = 'block';
    });
});
<?php endif; ?>
</script>
</body>
</html>