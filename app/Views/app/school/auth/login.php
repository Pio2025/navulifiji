<!--begin::Form-->
<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form2" method="post" action="<?= site_url('school/login') ?>">
	<!--begin::Heading-->
	<div class="text-center mb-11">
		<!--begin::Title-->
		<h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
		<!--end::Title-->
	</div>
	<!--begin::Heading-->
	<!--begin::Input group=-->
	<div class="fv-row mb-8">
		<!--begin::Email-->
		<input type="text" placeholder="Email" name="email" autocomplete="off" value="<?= old('email', isset($old['email']) ? $old['email'] : '') ?>" class="form-control bg-transparent" />
		<!--end::Email-->
		<!-- Error display -->
        <?php if (isset($validation) && $validation->hasError('email')): ?>
            <div class="text-danger">
                <?= $validation->getError('email') ?>
            </div>
        <?php endif; ?>
	</div>
	<!--end::Input group=-->
	<div class="fv-row mb-3">
		<!--begin::Password-->
		<input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" />
		<!--end::Password-->
		<!-- Error display -->
        <?php if (isset($validation) && $validation->hasError('password')): ?>
            <div class="text-danger">
                <?= $validation->getError('password') ?>
            </div>
        <?php endif; ?>
	</div>
	<!--end::Input group=-->
	<!--begin::Wrapper-->
	<div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
		<div></div>
		<!--begin::Link-->
		<a href="authentication/layouts/corporate/reset-password.html" class="link-primary">Forgot Password ?</a>
		<!--end::Link-->
	</div>
	<!--end::Wrapper-->
	<!--begin::Submit button-->
	<div class="d-grid mb-10">
		<button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
			<!--begin::Indicator label-->
			<span class="indicator-label">Sign In</span>
			<!--end::Indicator label-->
			<!--begin::Indicator progress-->
			<span class="indicator-progress">Please wait... 
			<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
			<!--end::Indicator progress-->
		</button>
	</div>
	<!--end::Submit button-->
	<!--begin::Sign up-->
	<!--div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet? 
	<a href="authentication/layouts/corporate/sign-up.html" class="link-primary">Sign up</a></div>
	<!--end::Sign up-->
</form>
							<!--end::Form-->