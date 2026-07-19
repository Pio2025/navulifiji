<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Role Management</h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url(); ?>/dashboard" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url('role'); ?>" class="text-muted text-hover-primary">Role Listing</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Role Permission</li>
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
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
			<div class="card-header border-0 pt-6">
				<div class="card-title">
					<!--begin::Role Info-->
					<div class="d-flex align-items-center">
						<div class="symbol symbol-50px me-3">
							<div class="symbol-label fs-2 fw-bold bg-light-primary text-primary rounded">
								<?= is_array($role) ? ($role['role_rank'] ?? '#') : ($role->role_rank ?? '#') ?>
							</div>
						</div>
						<div class="d-flex flex-column">
							<span class="text-gray-800 text-hover-primary fw-bold fs-4">
								<?= esc(is_array($role) ? $role['role_name'] : $role->role_name) ?>
							</span>
							<span class="text-muted fs-7">
								<?= $grantedCount ?> of <?= $permissionCount ?> permissions granted
							</span>
						</div>
					</div>
					<!--end::Role Info-->
				</div>
				
				<!--begin::Card toolbar-->
				<div class="card-toolbar">
					<a href="<?= base_url('role') ?>" class="btn btn-light me-3">
						<i class="ki-duotone ki-arrow-left fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						Back to Roles
					</a>
					
					<?php if ($canEditPermissions): ?>
						<button type="button" class="btn btn-primary" onclick="document.getElementById('permissionForm').submit();">
							<i class="ki-duotone ki-check fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							Save Permissions
						</button>
					<?php endif; ?>
				</div>
				<!--end::Card toolbar-->
			</div>
			<!--end::Card header-->
			
			<!--begin::Card body-->
			<div class="card-body py-4">
				
				<!--begin::Restriction Notice-->
				<?php if (!$canEditPermissions && !empty($rankRestrictionMessage)): ?>
					<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
						<i class="ki-duotone ki-shield-cross fs-2tx text-warning me-4">
							<span class="path1"></span>
							<span class="path2"></span>
							<span class="path3"></span>
						</i>
						<div class="d-flex flex-stack flex-grow-1">
							<div class="fw-semibold">
								<h4 class="text-gray-900 fw-bold">View-Only Mode</h4>
								<div class="fs-6 text-gray-700">
									<?= esc($rankRestrictionMessage) ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<!--end::Restriction Notice-->
				
				<!--begin::Form-->
				<form id="permissionForm" action="<?= base_url('role/permission/' . $role_id) ?>" method="POST">
					<?= csrf_field() ?>
					
					<!--begin::Info Notice-->
					<?php if ($canEditPermissions): ?>
						<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
							<i class="ki-duotone ki-information-5 fs-2tx text-primary me-4">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
							</i>
							<div class="d-flex flex-stack flex-grow-1">
								<div class="fw-semibold">
									<h4 class="text-gray-900 fw-bold">Permission Management</h4>
									<div class="fs-6 text-gray-700">
										You can grant or revoke permissions for this role. 
										Permissions marked with <span class="badge badge-success mx-1">Currently Granted</span> 
										are currently assigned to this role and can be unchecked to revoke access.
										<br>
										<strong>Your Rank:</strong> <?= $currentUserRank ?> | 
										<strong>Role Rank:</strong> <?= $targetRoleRank ?>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<!--end::Info Notice-->
					
					<!--begin::Permission Search-->
					<div class="mb-6">
						<div class="position-relative">
							<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle-y ms-4">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<input type="text" id="permSearch" class="form-control form-control-solid ps-12" placeholder="Search permissions..." autocomplete="off" />
						</div>
					</div>
					<!--end::Permission Search-->

					<!--begin::Permissions Grid-->
					<div id="permissionGridWrap">
					<?php
					// Use the library to render permissions
					$permissionViewLib = new \App\Libraries\PermissionViewLibrary();
					echo $permissionViewLib->renderPermissionGrid($role_id, $permissions, $canEditPermissions);
					?>
					</div>
					<div id="permSearchEmpty" class="text-center text-muted py-10 d-none">No permissions match your search.</div>
					<!--end::Permissions Grid-->
					
				</form>
				<!--end::Form-->
			</div>
			<!--end::Card body-->
			
			<!--begin::Card footer-->
			<div class="card-footer d-flex justify-content-end py-6 px-9">
				<a href="<?= base_url('role') ?>" class="btn btn-light me-3">
					<?= $canEditPermissions ? 'Cancel' : 'Close' ?>
				</a>
				
				<?php if ($canEditPermissions): ?>
					<button type="button" class="btn btn-primary" onclick="document.getElementById('permissionForm').submit();">
						<i class="ki-duotone ki-check fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						Save Changes
					</button>
				<?php endif; ?>
			</div>
			<!--end::Card footer-->
		</div>
		<!--end::Card-->
	</div>
</div>
<!--end::Content-->

<script>
"use strict";

document.addEventListener('DOMContentLoaded', function() {
    // Quick client-side permission search
    const permSearch = document.getElementById('permSearch');
    const gridWrap    = document.getElementById('permissionGridWrap');
    const emptyMsg    = document.getElementById('permSearchEmpty');
    if (permSearch && gridWrap) {
        const cards = Array.from(gridWrap.querySelectorAll('.col-md-4'));
        const rows  = Array.from(gridWrap.querySelectorAll('.row'));
        permSearch.addEventListener('keyup', function (e) {
            const term = e.target.value.trim().toLowerCase();
            let visibleCount = 0;
            cards.forEach(function (card) {
                const matches = term === '' || card.textContent.toLowerCase().includes(term);
                card.style.display = matches ? '' : 'none';
                if (matches) visibleCount++;
            });
            rows.forEach(function (row) {
                const hasVisible = row.querySelector('.col-md-4:not([style*="display: none"])');
                row.style.display = hasVisible ? '' : 'none';
            });
            if (emptyMsg) emptyMsg.classList.toggle('d-none', visibleCount !== 0);
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Form submission with loading
    const form = document.getElementById('permissionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            <?php if ($canEditPermissions): ?>
                e.preventDefault();
                
                // Count selected permissions
                const selectedPerms = form.querySelectorAll('input[name="permissions[]"]:checked').length;
                
                // Show confirmation
                Swal.fire({
                    title: 'Update Permissions?',
                    text: `You are about to update permissions for this role. ${selectedPerms} permission(s) will be granted.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update!',
                    cancelButtonText: 'Cancel',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Saving...',
                            text: 'Updating permissions, please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit form
                        form.submit();
                    }
                });
            <?php endif; ?>
        });
    }
    
    <?php if (!$canEditPermissions): ?>
        // Disable all checkboxes if user cannot edit
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.disabled = true;
            checkbox.style.cursor = 'not-allowed';
        });
        
        // Add visual indicator
        const labels = document.querySelectorAll('.form-check-label');
        labels.forEach(function(label) {
            label.style.cursor = 'not-allowed';
            label.style.opacity = '0.7';
        });
    <?php endif; ?>
});
</script>
