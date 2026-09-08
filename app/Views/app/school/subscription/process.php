<?php
$status = $subscription['subscription_status'] ?? '';
$isVerification = $status === 'Pending Verification';
$isPayment = $status === 'Pending Payment';
?>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Process Subscription</h1>
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

		<?php if ($isVerification): ?>
			<div class="alert alert-info d-flex align-items-center mb-6">
				<i class="ki-duotone ki-information-5 fs-2hx text-info me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
				<div>Review and correct the subscription data below if needed, then click <strong>Verify &amp; Send Invoice</strong>. This will set the status to <strong>Pending Payment</strong> and email a PDF invoice to <strong><?= esc($subscription['sch_email'] ?? 'the school') ?></strong>.</div>
			</div>
		<?php elseif ($isPayment): ?>
			<div class="alert alert-warning d-flex align-items-center mb-6">
				<i class="ki-duotone ki-information-5 fs-2hx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
				<div>This subscription is awaiting payment. Once payment has been confirmed, click <strong>Confirm Payment &amp; Activate</strong> to activate the subscription.</div>
			</div>
		<?php endif; ?>

		<div class="card">
			<div class="card-header">
				<div class="card-title">
					<h3 class="fw-bold"><?= esc($subscription['sch_name'] ?? 'School') ?> — Subscription #<?= esc($subscription['subscription_id']) ?></h3>
				</div>
			</div>

			<?php if ($isVerification): ?>
			<form id="kt_subscription_process_form" action="<?= base_url('school/subscription/process/' . $subscription['subscription_id']) ?>" method="post">
				<?= csrf_field() ?>
				<input type="hidden" name="process_action" value="verify" />
				<div class="card-body">
					<?= $this->include('app/school/subscription/_form_fields') ?>
				</div>
				<div class="card-footer d-flex justify-content-end py-6 px-9">
					<a href="<?= base_url('school/subscription') ?>" class="btn btn-light me-3">Cancel</a>
					<button type="submit" class="btn btn-primary" id="submit-btn">
						<i class="ki-duotone ki-verify fs-2"><span class="path1"></span><span class="path2"></span></i>
						Verify &amp; Send Invoice
					</button>
				</div>
			</form>
			<?php elseif ($isPayment): ?>
			<div class="card-body">
				<div class="row mb-4">
					<div class="col-md-6">
						<table class="table table-borderless align-middle">
							<tr><td class="text-muted w-150px">Plan</td><td class="fw-semibold"><?= esc($subscription['plan_name'] ?? 'N/A') ?></td></tr>
							<tr><td class="text-muted">Payment Mode</td><td class="fw-semibold"><?= esc($subscription['payment_mode'] ?? 'N/A') ?></td></tr>
							<tr><td class="text-muted">Amount Due</td><td class="fw-semibold">FJD <?= number_format((float) ($subscription['amount_paid'] ?? 0), 2) ?></td></tr>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table table-borderless align-middle">
							<tr><td class="text-muted w-150px">School Email</td><td class="fw-semibold"><?= esc($subscription['sch_email'] ?? 'N/A') ?></td></tr>
							<tr><td class="text-muted">Start Date</td><td class="fw-semibold"><?= !empty($subscription['subscription_start_date']) ? date('d M Y', strtotime($subscription['subscription_start_date'])) : 'N/A' ?></td></tr>
							<tr><td class="text-muted">Expiry Date</td><td class="fw-semibold"><?= !empty($subscription['subscription_end_date']) ? date('d M Y', strtotime($subscription['subscription_end_date'])) : 'N/A' ?></td></tr>
						</table>
					</div>
				</div>
			</div>
			<form id="kt_subscription_process_form" action="<?= base_url('school/subscription/process/' . $subscription['subscription_id']) ?>" method="post">
				<?= csrf_field() ?>
				<input type="hidden" name="process_action" value="activate" />
				<div class="card-footer d-flex justify-content-end py-6 px-9">
					<a href="<?= base_url('school/subscription') ?>" class="btn btn-light me-3">Cancel</a>
					<button type="submit" class="btn btn-success" id="submit-btn">
						<i class="ki-duotone ki-check-circle fs-2"><span class="path1"></span><span class="path2"></span></i>
						Confirm Payment &amp; Activate
					</button>
				</div>
			</form>
			<?php endif; ?>
		</div>
	</div>
</div>
<!--end::Content-->

<script>
"use strict";

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kt_subscription_process_form');
    const submitBtn = document.getElementById('submit-btn');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        });
    }
});
</script>
