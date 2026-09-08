<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit Subscription</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('school/subscription') ?>" class="text-muted text-hover-primary">Subscriptions</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<div id="kt_app_content_container" class="app-container container-xxl">

	    <?= $this->include('templates/flash_messages') ?>

		<div class="card">
			<div class="card-header">
				<div class="card-title">
					<h3 class="fw-bold"><?= esc($subscription['sch_name'] ?? 'School') ?> — Subscription #<?= esc($subscription['subscription_id']) ?></h3>
				</div>
			</div>
			<form id="kt_subscription_edit_form" action="<?= base_url('school/subscription/edit/' . $subscription['subscription_id']) ?>" method="post">
				<?= csrf_field() ?>
				<div class="card-body">
					<?= $this->include('app/school/subscription/_form_fields') ?>

					<div class="row">
						<div class="col-lg-6 mb-2">
							<label class="form-label required">Status</label>
							<select name="subscription_status" class="form-select <?= session('validation')?->hasError('subscription_status') ? 'is-invalid' : '' ?>">
								<?php foreach (['Pending Verification', 'Pending Payment', 'Active', 'Expired'] as $st): ?>
									<option value="<?= esc($st) ?>" <?= (old('subscription_status') ?? $subscription['subscription_status']) === $st ? 'selected' : '' ?>><?= esc($st) ?></option>
								<?php endforeach; ?>
							</select>
							<?php if (session('validation')?->hasError('subscription_status')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('subscription_status') ?></div>
							<?php endif; ?>
							<div class="form-text">Changing the status here does not send any notification email. Use the "Process" action from the list to send the verification invoice.</div>
						</div>
					</div>
				</div>

				<div class="card-footer d-flex justify-content-end py-6 px-9">
					<a href="<?= base_url('school/subscription') ?>" class="btn btn-light me-3">Cancel</a>
					<button type="submit" class="btn btn-primary" id="submit-btn">
						<i class="ki-duotone ki-check fs-2"></i>
						Save Changes
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end::Content-->

<script>
"use strict";

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kt_subscription_edit_form');
    const submitBtn = document.getElementById('submit-btn');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        });
    }
});
</script>
