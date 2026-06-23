<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit Permission</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('permission') ?>" class="text-muted text-hover-primary">Permissions</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Edit Permission</li>
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
			<!--begin::Card header-->
			<div class="card-header">
				<h3 class="card-title">Edit Permission: <?= esc($permission['perm_name']) ?></h3>
				<div class="card-toolbar">
					<a href="<?= base_url('permission') ?>" class="btn btn-light">
						<i class="ki-duotone ki-arrow-left fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						Back to List
					</a>
				</div>
			</div>
			<!--end::Card header-->
			
			<!--begin::Card body-->
			<div class="card-body">
				<!--begin::Form-->
				<form id="kt_permission_edit_form" action="<?= base_url('permission/update/' . $permission['perm_id']) ?>" method="POST">
					<?= csrf_field() ?>
					
					<!--begin::Input group - Module-->
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Module</label>
						<div class="col-lg-8">
							<select name="module_id_fk" id="module_id_fk" class="form-select  <?= session()->has('validation') && session('validation')->hasError('module_id_fk') ? 'is-invalid' : '' ?>">
								<option value="">Select Module...</option>
								<?php if (!empty($modules)): ?>
									<?php foreach ($modules as $module): ?>
										<option value="<?= $module['module_id'] ?>" 
										        <?= old('module_id_fk', $permission['module_id_fk']) == $module['module_id'] ? 'selected' : '' ?>>
											<?= esc($module['module_name']) ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<?php if (session()->has('validation') && session('validation')->hasError('module_id_fk')): ?>
								<div class="invalid-feedback d-block">
									<?= session('validation')->getError('module_id_fk') ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<!--end::Input group-->
					
					<!--begin::Input group - Permission Name-->
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Permission Name</label>
						<div class="col-lg-8">
							<input type="text" 
							       name="perm_name" 
							       id="perm_name" 
							       class="form-control  <?= session()->has('validation') && session('validation')->hasError('perm_name') ? 'is-invalid' : '' ?>" 
							       placeholder="e.g., Add User, Edit Role" 
							       value="<?= old('perm_name', $permission['perm_name']) ?>" />
							<?php if (session()->has('validation') && session('validation')->hasError('perm_name')): ?>
								<div class="invalid-feedback d-block">
									<?= session('validation')->getError('perm_name') ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<!--end::Input group-->
					
					<!--begin::Input group - Permission Code-->
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Permission Code</label>
						<div class="col-lg-8">
							<input type="text" 
							       name="perm_code" 
							       id="perm_code" 
							       class="form-control  <?= session()->has('validation') && session('validation')->hasError('perm_code') ? 'is-invalid' : '' ?>" 
							       placeholder="e.g., _add_user, _edit_role" 
							       value="<?= old('perm_code', $permission['perm_code']) ?>" />
							<div class="form-text">Use lowercase with underscores. Start with underscore.</div>
							<?php if (session()->has('validation') && session('validation')->hasError('perm_code')): ?>
								<div class="invalid-feedback d-block">
									<?= session('validation')->getError('perm_code') ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<!--end::Input group-->
					
					<!--begin::Input group - Description-->
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-semibold fs-6">Description</label>
						<div class="col-lg-8">
							<textarea name="perm_desc" 
							          id="perm_desc" 
							          class="form-control  <?= session()->has('validation') && session('validation')->hasError('perm_desc') ? 'is-invalid' : '' ?>" 
							          rows="3" 
							          placeholder="Describe what this permission allows..."><?= old('perm_desc', $permission['perm_desc'] ?? '') ?></textarea>
							<?php if (session()->has('validation') && session('validation')->hasError('perm_desc')): ?>
								<div class="invalid-feedback d-block">
									<?= session('validation')->getError('perm_desc') ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<!--end::Input group-->
					
					<!--begin::Input group - Controller-->
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-semibold fs-6">Controller</label>
						<div class="col-lg-8">
							<input type="text" 
							       name="perm_controller" 
							       id="perm_controller" 
							       class="form-control  <?= session()->has('validation') && session('validation')->hasError('perm_controller') ? 'is-invalid' : '' ?>" 
							       placeholder="e.g., UserController, RoleController" 
							       value="<?= old('perm_controller', $permission['perm_controller'] ?? '') ?>" />
							<div class="form-text">Optional: Controller name this permission is associated with.</div>
							<?php if (session()->has('validation') && session('validation')->hasError('perm_controller')): ?>
								<div class="invalid-feedback d-block">
									<?= session('validation')->getError('perm_controller') ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<!--end::Input group-->
					
					<!--begin::Input group - Show In Nav-->
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label fw-semibold fs-6">Show In Navigation</label>
						<div class="col-lg-8">
							<div class="form-check form-check-custom form-check-solid form-switch">
								<input class="form-check-input" 
								       type="checkbox" 
								       name="show_in_nav" 
								       id="show_in_nav" 
								       value="1" 
								       <?= old('show_in_nav', $permission['show_in_nav']) ? 'checked' : '' ?> />
								<label class="form-check-label" for="show_in_nav">
									Display this permission in navigation menu
								</label>
							</div>
						</div>
					</div>
					<!--end::Input group-->
					
					<!--begin::Input group - Status-->
					<div class="row mb-6">
						<label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
						<div class="col-lg-8">
							<select name="perm_status" id="perm_status" class="form-select ">
								<option value="Active" <?= old('perm_status', $permission['perm_status']) === 'Active' ? 'selected' : '' ?>>Active</option>
								<option value="Inactive" <?= old('perm_status', $permission['perm_status']) === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
							</select>
						</div>
					</div>
					<!--end::Input group-->
					
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<a href="<?= base_url('permission') ?>" class="btn btn-light me-3">Cancel</a>
						<button type="submit" class="btn btn-primary">
							<i class="ki-duotone ki-check fs-2"></i>
							Update Permission
						</button>
					</div>
					<!--end::Actions-->
					
				</form>
				<!--end::Form-->
			</div>
			<!--end::Card body-->
		</div>
		<!--end::Card-->
	</div>
</div>
<!--end::Content-->

<script>
"use strict";

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kt_permission_edit_form');
    
    // Form submission with loading
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
        });
    }
});
</script>
