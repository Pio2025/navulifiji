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
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url(); ?>/dashboard" class="text-muted text-hover-primary">Home</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url('role'); ?>" class="text-muted text-hover-primary">Role Listing</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">Add Role</li>
				<!--end::Item-->
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
			<div class="card-header border pt-6 pb-4">
				<div class="card-title">
					<h3 class="fw-bold m-0">Role Information</h3>
				</div>
				<!--begin::Card toolbar-->
				<div class="card-toolbar">
					<a href="<?php echo base_url('role'); ?>" class="btn btn-light-primary btn-sm">
						<i class="ki-duotone ki-arrow-left fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						Back to List
					</a>
				</div>
				<!--end::Card toolbar-->
			</div>
			<!--end::Card header-->
			
			<!--begin::Card body-->
			<div class="card-body py-4">
				<!--begin::Form-->
				<form id="kt_role_add_form" class="form" action="<?php echo base_url('role/store'); ?>" method="POST">
					<?= csrf_field() ?>
					
					<!--begin::Row-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-12 col-form-label required fw-semibold fs-6">Role Name & Rank</label>
						<!--end::Label-->
						
						<!--begin::Col-->
						<div class="col-lg-12">
							<div class="row g-3">
								<!--begin::Role Name-->
								<div class="col-md-8">
								    <!--begin::Label-->
            						<label class="col-lg-12 col-form-label required fw-semibold fs-6">Role Name</label>
            						<!--end::Label-->
									<input type="text" 
										   name="role_name" 
										   id="role_name"
										   class="form-control form-control-lg <?= (session()->has('validation') && session('validation')->hasError('role_name')) || (isset($validation) && $validation->hasError('role_name')) ? 'is-invalid' : '' ?>" 
										   placeholder="Enter role name (e.g., Super Admin, Teacher, Student)"
										   value="<?= old('role_name') ?>" />
									<?php 
									$validationObj = session()->has('validation') ? session('validation') : (isset($validation) ? $validation : null);
									if($validationObj && $validationObj->hasError('role_name')): 
									?>
										<div class="fv-plugins-message-container">
											<div class="fv-help-block">
												<span role="alert" class="text-danger"><?= $validationObj->getError('role_name') ?></span>
											</div>
										</div>
									<?php endif; ?>
								</div>
								<!--end::Role Name-->
								
								<!--begin::Role Rank-->
								<div class="col-md-4">
								    <!--begin::Label-->
            						<label class="col-lg-12 col-form-label required fw-semibold fs-6">Role Rank</label>
            						<!--end::Label-->
									<input type="number" 
										   name="role_rank" 
										   id="role_rank"
										   class="form-control form-control-lg <?= (session()->has('validation') && session('validation')->hasError('role_rank')) || (isset($validation) && $validation->hasError('role_rank')) ? 'is-invalid' : '' ?>" 
										   placeholder="Rank (e.g., 1)"
										   value="<?= old('role_rank') ?>"
										   min="1"
										   step="1" />
									<?php 
									$validationObj = session()->has('validation') ? session('validation') : (isset($validation) ? $validation : null);
									if($validationObj && $validationObj->hasError('role_rank')): 
									?>
										<div class="fv-plugins-message-container">
											<div class="fv-help-block">
												<span role="alert" class="text-danger"><?= $validationObj->getError('role_rank') ?></span>
											</div>
										</div>
									<?php endif; ?>
								</div>
								<!--end::Role Rank-->
							</div>
						</div>
						<!--end::Col-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row-->
					<div class="row mb-6">
						<!--begin::Label-->
						<label class="col-lg-12 col-form-label fw-semibold fs-6">Description</label>
						<!--end::Label-->
						
						<!--begin::Col-->
						<div class="col-lg-12">
							<textarea name="role_desc" 
									  id="role_desc"
									  class="form-control form-control-lg <?= (session()->has('validation') && session('validation')->hasError('role_desc')) || (isset($validation) && $validation->hasError('role_desc')) ? 'is-invalid' : '' ?>" 
									  rows="5"
									  placeholder="Enter a detailed description of this role's responsibilities and access level..."><?= old('role_desc') ?></textarea>
							<div class="form-text">Provide a clear description of the role's purpose and responsibilities</div>
							<?php 
							$validationObj = session()->has('validation') ? session('validation') : (isset($validation) ? $validation : null);
							if($validationObj && $validationObj->hasError('role_desc')): 
							?>
								<div class="fv-plugins-message-container">
									<div class="fv-help-block">
										<span role="alert" class="text-danger"><?= $validationObj->getError('role_desc') ?></span>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<!--end::Col-->
					</div>
					<!--end::Row-->
					
					<!--begin::Separator-->
					<div class="separator separator-dashed my-10"></div>
					<!--end::Separator-->
					
					<!--begin::Actions-->
					<div class="row">
						<div class="col-lg-12">
							<div class="d-flex justify-content-end gap-3">
								<!--begin::Button-->
								<a href="<?php echo base_url('role'); ?>" class="btn btn-light btn-lg">
									<i class="ki-duotone ki-cross fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									Cancel
								</a>
								<!--end::Button-->
								
								<!--begin::Button-->
								<button type="reset" class="btn btn-light-primary btn-lg">
									<i class="ki-duotone ki-arrows-circle fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									Reset
								</button>
								<!--end::Button-->
								
								<!--begin::Button-->
								<button type="submit" class="btn btn-primary btn-lg" id="kt_role_submit">
									<span class="indicator-label">
										<i class="ki-duotone ki-check fs-2"></i>
										Save Role
									</span>
									<span class="indicator-progress">
										Please wait... 
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
									</span>
								</button>
								<!--end::Button-->
							</div>
						</div>
					</div>
					<!--end::Actions-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Card body-->
		</div>
		<!--end::Card-->
		
		<!--begin::Info Card-->
		<div class="card mt-5">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="symbol symbol-50px me-5">
						<span class="symbol-label bg-light-info">
							<i class="ki-duotone ki-information-5 fs-2x text-info">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
							</i>
						</span>
					</div>
					<div class="flex-grow-1">
						<h4 class="text-gray-900 mb-1">Role Guidelines</h4>
						<p class="text-gray-700 fw-semibold mb-0">
							<strong>Role Name:</strong> Use clear, descriptive names (e.g., "Super Admin", "Teacher", "Student")<br>
							<strong>Rank:</strong> Lower numbers indicate higher authority (1 = highest, 2 = second highest, etc.)<br>
							<strong>Description:</strong> Explain the role's responsibilities, access level, and limitations
						</p>
					</div>
				</div>
			</div>
		</div>
		<!--end::Info Card-->
	</div>
</div>
<!--end::Content-->

<!--begin::Custom CSS-->
<style>
/* Form field focus effects */
.form-control-solid:focus {
    background-color: #f9f9f9;
    border-color: #009ef7;
}

/* Required field asterisk */
.required:after {
    content: " *";
    color: #f1416c;
    font-weight: bold;
}

/* Button hover effects */
.btn-primary:hover {
    background-color: #0095e8;
    border-color: #0095e8;
}

/* Form validation */
.fv-plugins-message-container {
    margin-top: 0.5rem;
}
</style>
<!--end::Custom CSS-->

<script>
"use strict";

// Form Validation and Submission
var KTRoleAdd = function() {
    var form;
    var submitButton;
    var validator;

    var handleForm = function() {
        // Init form validation rules
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'role_name': {
                        validators: {
                            notEmpty: {
                                message: 'Role name is required'
                            },
                            stringLength: {
                                min: 3,
                                max: 100,
                                message: 'Role name must be between 3 and 100 characters'
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9\s\-\_]+$/,
                                message: 'Role name can only contain letters, numbers, spaces, hyphens and underscores'
                            }
                        }
                    },
                    'role_rank': {
                        validators: {
                            integer: {
                                message: 'Rank must be a valid number'
                            },
                            between: {
                                min: 1,
                                max: 999,
                                message: 'Rank must be between 1 and 999'
                            }
                        }
                    },
                    'role_desc': {
                        validators: {
                            stringLength: {
                                max: 1000,
                                message: 'Description cannot exceed 1000 characters'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');
                        submitButton.disabled = true;

                        // Submit form
                        form.submit();
                    } else {
                        // Show error popup
                        Swal.fire({
                            text: "Please fill in all required fields correctly.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
        });
    };

    // Auto-generate role rank suggestion
    var handleRankSuggestion = function() {
        var roleNameInput = document.getElementById('role_name');
        var roleRankInput = document.getElementById('role_rank');
        
        if (roleNameInput && roleRankInput) {
            roleNameInput.addEventListener('blur', function() {
                // Only suggest if rank is empty
                if (!roleRankInput.value) {
                    var roleName = this.value.toLowerCase();
                    
                    // Suggest ranks based on common role names
                    if (roleName.includes('super') || roleName.includes('admin')) {
                        roleRankInput.value = '1';
                    } else if (roleName.includes('manager') || roleName.includes('head')) {
                        roleRankInput.value = '2';
                    } else if (roleName.includes('teacher') || roleName.includes('staff')) {
                        roleRankInput.value = '3';
                    } else if (roleName.includes('student') || roleName.includes('user')) {
                        roleRankInput.value = '4';
                    }
                }
            });
        }
    };

    return {
        init: function() {
            form = document.querySelector('#kt_role_add_form');
            submitButton = document.querySelector('#kt_role_submit');

            if (form && submitButton) {
                handleForm();
                handleRankSuggestion();
            }
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTRoleAdd.init();
});
</script>