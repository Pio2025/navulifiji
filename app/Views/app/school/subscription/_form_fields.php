<?php
/**
 * Shared subscription form fields, used by both edit.php and process.php.
 * Expects $subscription (array) and $plans (array) in scope.
 */
?>
<div class="row">
	<div class="col-lg-6 mb-5">
		<label class="form-label required">Plan</label>
		<select name="plan_id_fk" class="form-select <?= session('validation')?->hasError('plan_id_fk') ? 'is-invalid' : '' ?>">
			<?php foreach ($plans as $plan): ?>
				<option value="<?= esc($plan['plan_id']) ?>" <?= (old('plan_id_fk') ?? $subscription['plan_id_fk']) == $plan['plan_id'] ? 'selected' : '' ?>>
					<?= esc($plan['plan_name']) ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php if (session('validation')?->hasError('plan_id_fk')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('plan_id_fk') ?></div>
		<?php endif; ?>
	</div>

	<div class="col-lg-6 mb-5">
		<label class="form-label required">Package Type</label>
		<select name="package_type" class="form-select <?= session('validation')?->hasError('package_type') ? 'is-invalid' : '' ?>">
			<option value="web" <?= (old('package_type') ?? $subscription['package_type']) === 'web' ? 'selected' : '' ?>>Web Only</option>
			<option value="web_mobile" <?= (old('package_type') ?? $subscription['package_type']) === 'web_mobile' ? 'selected' : '' ?>>Web + Mobile</option>
		</select>
		<?php if (session('validation')?->hasError('package_type')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('package_type') ?></div>
		<?php endif; ?>
	</div>

	<div class="col-lg-6 mb-5">
		<label class="form-label required">Start Date</label>
		<input type="date" name="subscription_start_date" class="form-control <?= session('validation')?->hasError('subscription_start_date') ? 'is-invalid' : '' ?>"
		       value="<?= esc(old('subscription_start_date') ?? $subscription['subscription_start_date']) ?>" />
		<?php if (session('validation')?->hasError('subscription_start_date')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('subscription_start_date') ?></div>
		<?php endif; ?>
	</div>

	<div class="col-lg-6 mb-5">
		<label class="form-label required">Expiry Date</label>
		<input type="date" name="subscription_end_date" class="form-control <?= session('validation')?->hasError('subscription_end_date') ? 'is-invalid' : '' ?>"
		       value="<?= esc(old('subscription_end_date') ?? $subscription['subscription_end_date']) ?>" />
		<?php if (session('validation')?->hasError('subscription_end_date')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('subscription_end_date') ?></div>
		<?php endif; ?>
	</div>

	<div class="col-lg-4 mb-5">
		<label class="form-label required">Term (Months)</label>
		<input type="number" min="1" name="subscription_term" class="form-control <?= session('validation')?->hasError('subscription_term') ? 'is-invalid' : '' ?>"
		       value="<?= esc(old('subscription_term') ?? $subscription['subscription_term']) ?>" />
		<?php if (session('validation')?->hasError('subscription_term')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('subscription_term') ?></div>
		<?php endif; ?>
	</div>

	<div class="col-lg-4 mb-5">
		<label class="form-label required">Billing Cycle</label>
		<select name="billing_cycle" class="form-select <?= session('validation')?->hasError('billing_cycle') ? 'is-invalid' : '' ?>">
			<option value="monthly" <?= (old('billing_cycle') ?? $subscription['billing_cycle']) === 'monthly' ? 'selected' : '' ?>>Monthly</option>
			<option value="annual" <?= (old('billing_cycle') ?? $subscription['billing_cycle']) === 'annual' ? 'selected' : '' ?>>Annual</option>
			<option value="trial" <?= (old('billing_cycle') ?? $subscription['billing_cycle']) === 'trial' ? 'selected' : '' ?>>Trial</option>
		</select>
		<?php if (session('validation')?->hasError('billing_cycle')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('billing_cycle') ?></div>
		<?php endif; ?>
	</div>

	<div class="col-lg-4 mb-5">
		<label class="form-label required">Payment Mode</label>
		<select name="payment_mode" class="form-select <?= session('validation')?->hasError('payment_mode') ? 'is-invalid' : '' ?>">
			<option value="">Select payment mode...</option>
			<?php foreach (['Cash', 'Check', 'Bank Transfer', 'Master Card', 'MPaisa', 'MyCash'] as $mode): ?>
				<option value="<?= esc($mode) ?>" <?= (old('payment_mode') ?? $subscription['payment_mode']) === $mode ? 'selected' : '' ?>><?= esc($mode) ?></option>
			<?php endforeach; ?>
		</select>
		<?php if (session('validation')?->hasError('payment_mode')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('payment_mode') ?></div>
		<?php endif; ?>
	</div>

	<div class="col-lg-6 mb-5">
		<label class="form-label">Amount Paid (FJD)</label>
		<input type="number" step="0.01" min="0" name="amount_paid" class="form-control <?= session('validation')?->hasError('amount_paid') ? 'is-invalid' : '' ?>"
		       value="<?= esc(old('amount_paid') ?? $subscription['amount_paid']) ?>" />
		<?php if (session('validation')?->hasError('amount_paid')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('amount_paid') ?></div>
		<?php endif; ?>
	</div>

	<div class="col-lg-6 mb-5">
		<label class="form-label">Discount (%)</label>
		<input type="number" step="0.01" min="0" max="100" name="discount_percent" class="form-control <?= session('validation')?->hasError('discount_percent') ? 'is-invalid' : '' ?>"
		       value="<?= esc(old('discount_percent') ?? $subscription['discount_percent']) ?>" />
		<?php if (session('validation')?->hasError('discount_percent')): ?>
			<div class="invalid-feedback"><?= session('validation')->getError('discount_percent') ?></div>
		<?php endif; ?>
	</div>
</div>
