<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit School</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('school') ?>" class="text-muted text-hover-primary">Schools</a>
				</li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted"><?= esc($school['sch_name']) ?></li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted">Edit</li>
			</ul>
		</div>
	</div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<div id="kt_app_content_container" class="app-container container-xxl">

		<?= $this->include('templates/flash_messages') ?>

		<!--begin::Card-->
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Edit School — <?= esc($school['sch_name']) ?></h3>
				<div class="card-toolbar">
					<a href="<?= base_url('school/detail/' . (int)$school['sch_id']) ?>" class="btn btn-light me-2">
						<i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
						View Detail
					</a>
					<a href="<?= base_url('school') ?>" class="btn btn-light">
						<i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
						Back to List
					</a>
				</div>
			</div>

			<div class="card-body">
				<form id="kt_school_edit_form" action="<?= base_url('school/edit/' . (int)$school['sch_id']) ?>" method="POST" enctype="multipart/form-data">
					<?= csrf_field() ?>

					<!--begin::School Information-->
					<div class="row mb-4">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">School Information</label>

						<!--begin::School Name-->
						<div class="col-lg-6 mb-3">
							<label class="form-label required">School Name</label>
							<input type="text" name="sch_name"
							       class="form-control <?= session('validation')?->hasError('sch_name') ? 'is-invalid' : '' ?>"
							       value="<?= old('sch_name', $school['sch_name']) ?>" />
							<?php if (session('validation')?->hasError('sch_name')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('sch_name') ?></div>
							<?php endif; ?>
						</div>
						<!--end::School Name-->

						<!--begin::Category-->
						<div class="col-lg-6 mb-3">
							<label class="form-label">School Category</label>
							<select name="sch_cat_id_fk" class="form-select">
								<?php foreach ($schoolCategory as $cat): ?>
								<option value="<?= (int)$cat['sch_cat_id'] ?>"
								    <?= (old('sch_cat_id_fk', $school['sch_cat_id_fk']) == $cat['sch_cat_id']) ? 'selected' : '' ?>>
									<?= esc($cat['sch_cat_name']) ?>
								</option>
								<?php endforeach; ?>
							</select>
						</div>
						<!--end::Category-->

						<!--begin::Motto-->
						<div class="col-lg-12 mb-3">
							<label class="form-label">School Motto</label>
							<input type="text" name="sch_motto"
							       class="form-control <?= session('validation')?->hasError('sch_motto') ? 'is-invalid' : '' ?>"
							       value="<?= old('sch_motto', $school['sch_motto'] ?? '') ?>" />
							<?php if (session('validation')?->hasError('sch_motto')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('sch_motto') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Motto-->

						<!--begin::Status-->
						<div class="col-lg-6 mb-3">
							<label class="form-label required">Status</label>
							<select name="sch_status" class="form-select">
								<?php
								$statuses = ['Active', 'Inactive', 'Step 1 Configured', 'Step 2 Configured', 'Step 3 Configured', 'Step 4 Configured'];
								$currentStatus = old('sch_status', $school['sch_status']);
								foreach ($statuses as $s): ?>
								<option value="<?= esc($s) ?>" <?= $currentStatus === $s ? 'selected' : '' ?>><?= esc($s) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<!--end::Status-->

						<!--begin::Logo-->
						<div class="col-lg-6 mb-3">
							<label class="form-label">School Logo</label>
							<?php if (!empty($school['sch_logo'])): ?>
							<div class="mb-2">
								<img src="<?= base_url('uploads/school/logo/' . esc($school['sch_logo'])) ?>"
								     alt="Current Logo" class="h-50px rounded" onerror="this.style.display='none'">
								<span class="text-muted fs-8 ms-2">Current logo</span>
							</div>
							<?php endif; ?>
							<input type="file" name="sch_logo" class="form-control" accept="image/*" />
							<div class="form-text">Leave blank to keep current logo. Allowed: JPG, PNG, GIF (Max: 2MB)</div>
						</div>
						<!--end::Logo-->
					</div>
					<!--end::School Information-->

					<!--begin::Contact Information-->
					<div class="row mb-4">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Contact Information</label>

						<!--begin::Email-->
						<div class="col-lg-6 mb-3">
							<label class="form-label required">Email</label>
							<input type="email" name="sch_email"
							       class="form-control <?= session('validation')?->hasError('sch_email') ? 'is-invalid' : '' ?>"
							       value="<?= old('sch_email', $school['sch_email']) ?>" />
							<?php if (session('validation')?->hasError('sch_email')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('sch_email') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Email-->

						<!--begin::Phone-->
						<div class="col-lg-6 mb-3">
							<label class="form-label required">Phone</label>
							<input type="text" name="sch_phone"
							       class="form-control <?= session('validation')?->hasError('sch_phone') ? 'is-invalid' : '' ?>"
							       value="<?= old('sch_phone', $school['sch_phone']) ?>" maxlength="7" />
							<div class="form-text">7-digit phone number</div>
							<?php if (session('validation')?->hasError('sch_phone')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('sch_phone') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Phone-->

						<!--begin::Address-->
						<div class="col-lg-12 mb-3">
							<label class="form-label required">Address</label>
							<textarea name="sch_address"
							          class="form-control <?= session('validation')?->hasError('sch_address') ? 'is-invalid' : '' ?>"
							          rows="3"><?= old('sch_address', $school['sch_address']) ?></textarea>
							<?php if (session('validation')?->hasError('sch_address')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('sch_address') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Address-->
					</div>
					<!--end::Contact Information-->

					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-0">
						<a href="<?= base_url('school/detail/' . (int)$school['sch_id']) ?>" class="btn btn-light me-3">Cancel</a>
						<button type="submit" class="btn btn-primary" id="edit-submit-btn">
							<i class="ki-duotone ki-check fs-2"><span class="path1"></span><span class="path2"></span></i>
							Save Changes
						</button>
					</div>
					<!--end::Actions-->

				</form>
			</div>
		</div>
		<!--end::Card-->

	</div>
</div>
<!--end::Content-->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('kt_school_edit_form');
    const btn  = document.getElementById('edit-submit-btn');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        });
    }
});
</script>
