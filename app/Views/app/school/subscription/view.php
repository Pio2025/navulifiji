<?php
$statusColor = match ($subscription['subscription_status'] ?? '') {
    'Active'                => 'success',
    'Pending Payment'       => 'warning',
    'Pending Verification'  => 'info',
    'Expired'               => 'danger',
    default                 => 'secondary',
};
?>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Subscription Details</h1>
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
		<div class="d-flex align-items-center gap-2">
			<a href="<?= base_url('school/subscription/edit/' . $subscription['subscription_id']) ?>" class="btn btn-sm btn-light-primary">
				<i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
				Edit
			</a>
			<?php if (!in_array($subscription['subscription_status'] ?? '', ['Active', 'Expired'], true)): ?>
			<a href="<?= base_url('school/subscription/process/' . $subscription['subscription_id']) ?>" class="btn btn-sm btn-primary">
				<i class="ki-duotone ki-verify fs-5"><span class="path1"></span><span class="path2"></span></i>
				Process
			</a>
			<?php endif; ?>
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
					<h3 class="fw-bold">Subscription #<?= esc($subscription['subscription_id']) ?></h3>
				</div>
				<div class="card-toolbar">
					<span class="badge badge-light-<?= $statusColor ?> fs-7"><?= esc($subscription['subscription_status'] ?? 'Unknown') ?></span>
				</div>
			</div>
			<div class="card-body">
				<div class="row mb-8">
					<div class="col-md-6">
						<h5 class="text-gray-800 fw-bold mb-4">School</h5>
						<table class="table table-borderless align-middle">
							<tr>
								<td class="text-muted w-150px">Name</td>
								<td class="fw-semibold">
									<a href="<?= base_url('school/detail/' . $subscription['sch_id_fk']) ?>"><?= esc($subscription['sch_name'] ?? 'N/A') ?></a>
								</td>
							</tr>
							<tr>
								<td class="text-muted">Email</td>
								<td class="fw-semibold"><?= esc($subscription['sch_email'] ?? 'N/A') ?></td>
							</tr>
							<tr>
								<td class="text-muted">Phone</td>
								<td class="fw-semibold"><?= esc($subscription['sch_phone'] ?? 'N/A') ?></td>
							</tr>
							<tr>
								<td class="text-muted">Address</td>
								<td class="fw-semibold"><?= esc($subscription['sch_address'] ?? 'N/A') ?></td>
							</tr>
						</table>
					</div>
					<div class="col-md-6">
						<h5 class="text-gray-800 fw-bold mb-4">Subscription</h5>
						<table class="table table-borderless align-middle">
							<tr>
								<td class="text-muted w-150px">Plan</td>
								<td class="fw-semibold"><?= esc($subscription['plan_name'] ?? 'N/A') ?></td>
							</tr>
							<tr>
								<td class="text-muted">Start Date</td>
								<td class="fw-semibold"><?= !empty($subscription['subscription_start_date']) ? date('d M Y', strtotime($subscription['subscription_start_date'])) : 'N/A' ?></td>
							</tr>
							<tr>
								<td class="text-muted">Expiry Date</td>
								<td class="fw-semibold"><?= !empty($subscription['subscription_end_date']) ? date('d M Y', strtotime($subscription['subscription_end_date'])) : 'N/A' ?></td>
							</tr>
							<tr>
								<td class="text-muted">Term</td>
								<td class="fw-semibold"><?= esc($subscription['subscription_term'] ?? 'N/A') ?> month(s)</td>
							</tr>
							<tr>
								<td class="text-muted">Billing Cycle</td>
								<td class="fw-semibold"><?= esc(ucfirst($subscription['billing_cycle'] ?? 'N/A')) ?></td>
							</tr>
							<tr>
								<td class="text-muted">Package Type</td>
								<td class="fw-semibold"><?= $subscription['package_type'] === 'web_mobile' ? 'Web + Mobile' : 'Web Only' ?></td>
							</tr>
							<tr>
								<td class="text-muted">Payment Mode</td>
								<td class="fw-semibold"><?= esc($subscription['payment_mode'] ?? 'N/A') ?></td>
							</tr>
							<tr>
								<td class="text-muted">Discount</td>
								<td class="fw-semibold"><?= esc($subscription['discount_percent'] ?? 0) ?>%</td>
							</tr>
							<tr>
								<td class="text-muted">Amount Paid</td>
								<td class="fw-semibold">FJD <?= number_format((float) ($subscription['amount_paid'] ?? 0), 2) ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="card-footer d-flex justify-content-end">
				<a href="<?= base_url('school/subscription') ?>" class="btn btn-light">Back to List</a>
			</div>
		</div>
	</div>
</div>
<!--end::Content-->
