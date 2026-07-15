<!--begin::Login Form-->
<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
      action="<?php echo base_url('auth/login'); ?>" method="POST" autocomplete="on"
      data-kt-redirect-url="<?php echo base_url('dashboard'); ?>">
    <?= csrf_field() ?>
    
    <!--begin::Heading-->
    <div class="text-center mb-11">
        <!--begin::Title-->
        <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
        <!--end::Title-->
        <!--begin::Subtitle-->
        <div class="text-gray-500 fw-semibold fs-6">Access Your Navuli Account</div>
        <!--end::Subtitle-->
    </div>
    <!--end::Heading-->

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger d-flex align-items-center mb-8 p-4" role="alert">
        <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-3">
            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
        </i>
        <div class="d-flex flex-column">
            <span class="fw-semibold"><?= esc($error) ?></span>
        </div>
    </div>
    <?php endif ?>

    <!--begin::Input group (Identifier)-->
    <div class="fv-row mb-8">
        <!--begin::Label-->
        <label class="form-label fs-6 fw-bolder text-gray-900">Email or Username</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input class="form-control bg-transparent <?php
            $validationObj = session()->has('validation') ? session('validation') : (isset($validation) ? $validation : null);
            echo ($validationObj && $validationObj->hasError('email')) ? 'is-invalid' : '';
        ?>"
               type="text"
               name="email"
               placeholder="Enter your email or username"
               value="<?= old('email', $remembered_email ?? '') ?>"
               autocomplete="username" />
        <!--end::Input-->
        <?php
        $validationObj = session()->has('validation') ? session('validation') : (isset($validation) ? $validation : null);
        if($validationObj && $validationObj->hasError('email')):
        ?>
            <div class="fv-plugins-message-container mt-2">
                <div class="fv-help-block">
                    <span class="text-danger"><?= $validationObj->getError('email') ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!--end::Input group-->
    
    <!--begin::Input group (Password)-->
    <div class="fv-row mb-3">
        <!--begin::Label-->
        <label class="form-label fw-bolder text-gray-900 fs-6 mb-0">Password</label>
        <!--end::Label-->
        <!--begin::Input wrapper-->
        <div class="position-relative mb-3">
            <input class="form-control bg-transparent <?php 
                $validationObj = session()->has('validation') ? session('validation') : (isset($validation) ? $validation : null);
                echo ($validationObj && $validationObj->hasError('password')) ? 'is-invalid' : ''; 
            ?>" 
                   type="password"
                   name="password"
                   id="kt_sign_in_password"
                   placeholder="Enter your password"
                   value="<?= old('password', $remembered_password ?? '') ?>"
                   autocomplete="current-password" />
            <!--begin::Visibility toggle-->
            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" 
                  id="kt_password_visibility_toggle"
                  style="cursor: pointer;">
                <i class="ki-duotone ki-eye-slash fs-2" id="password_hide_icon">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                </i>
                <i class="ki-duotone ki-eye fs-2 d-none" id="password_show_icon">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </span>
            <!--end::Visibility toggle-->
        </div>
        <!--end::Input wrapper-->
        <?php 
        $validationObj = session()->has('validation') ? session('validation') : (isset($validation) ? $validation : null);
        if($validationObj && $validationObj->hasError('password')): 
        ?>
            <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                    <span class="text-danger"><?= $validationObj->getError('password') ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!--end::Input group-->
    
    <!--begin::Wrapper-->
    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
        <!--begin::Remember me-->
        <div class="form-check form-check-custom form-check-solid">
            <input class="form-check-input" 
                   type="checkbox" 
                   name="remember_me" 
                   id="remember_me" 
                   value="1"
                   <?= !empty($remembered_email) ? 'checked' : '' ?> />
            <label class="form-check-label" for="remember_me">
                Remember Me
            </label>
        </div>
        <!--end::Remember me-->
        
        <!--begin::Link-->
        <a href="<?php echo base_url('auth/forgot-password'); ?>" class="link-primary">
            Forgot Password?
        </a>
        <!--end::Link-->
    </div>
    <!--end::Wrapper-->
    
    <!--begin::Submit button-->
    <div class="d-grid mb-10">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
            <!--begin::Indicator label-->
            <span class="indicator-label">
                <i class="ki-duotone ki-entrance-left fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Sign In
            </span>
            <!--end::Indicator label-->
            <!--begin::Indicator progress-->
            <span class="indicator-progress">
                Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
            <!--end::Indicator progress-->
        </button>
    </div>
    <!--end::Submit button-->
    
    <!--begin::Sign up-->
    <div class="text-gray-500 text-center fw-semibold fs-6">
        Not registered yet?
        <a href="<?php echo base_url('auth/register'); ?>" class="link-primary">
            Create an Account
        </a>
    </div>
    <!--end::Sign up-->
</form>
<!--end::Login Form-->

<!--begin::Custom CSS-->
<style>
/* Password visibility toggle styling */
#kt_password_visibility_toggle {
    z-index: 10;
}

#kt_password_visibility_toggle:hover {
    background-color: rgba(0, 0, 0, 0.05);
    border-radius: 0.475rem;
}

/* Custom checkbox styling */
.form-check-input:checked {
    background-color: #009ef7;
    border-color: #009ef7;
}

/* Input focus effects */
.form-control:focus {
    border-color: #009ef7;
    box-shadow: 0 0 0 0.25rem rgba(0, 158, 247, 0.1);
}

/* Remember me label hover */
.form-check-label {
    cursor: pointer;
    user-select: none;
}

/* Invalid input styling */
.form-control.is-invalid {
    border-color: #f1416c;
}

.form-control.is-invalid:focus {
    border-color: #f1416c;
    box-shadow: 0 0 0 0.25rem rgba(241, 65, 108, 0.1);
}

/* Alert auto-hide animation */
@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}

.alert-success.auto-hide {
    animation: fadeOut 0.5s ease-in-out 4.5s forwards;
}
</style>
<!--end::Custom CSS-->

<script>
"use strict";

// Password Visibility Toggle
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('kt_sign_in_password');
    const toggleButton = document.getElementById('kt_password_visibility_toggle');
    const hideIcon = document.getElementById('password_hide_icon');
    const showIcon = document.getElementById('password_show_icon');
    
    if (toggleButton && passwordInput) {
        toggleButton.addEventListener('click', function() {
            // Toggle password visibility
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                hideIcon.classList.add('d-none');
                showIcon.classList.remove('d-none');
            } else {
                passwordInput.type = 'password';
                showIcon.classList.add('d-none');
                hideIcon.classList.remove('d-none');
            }
        });
    }
    
    // Form submission is handled by general.js (FormValidation + form.submit)
    
    // Auto-hide success messages after 5 seconds
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(function(alert) {
        alert.classList.add('auto-hide');
        setTimeout(function() {
            alert.style.display = 'none';
        }, 5000);
    });
});
</script>
