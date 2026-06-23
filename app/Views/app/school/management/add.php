<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">School Management</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('school') ?>" class="text-muted text-hover-primary">Schools</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Add School</li>
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
				<h3 class="card-title">School Information</h3>
				<div class="card-toolbar">
					<a href="<?= base_url('school') ?>" class="btn btn-light">
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
				<form id="kt_school_add_form" action="<?= base_url('school/store') ?>" method="POST" enctype="multipart/form-data">
					<?= csrf_field() ?>
					
					<!--begin::Row - School Information-->
					<div class="row mb-2">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">School Information</label>
						
						<!--begin::School Name-->
						<div class="col-lg-6 mb-2">
							<label class="form-label required">School Name</label>
							<input type="text" name="school_name" class="form-control <?= session('validation')?->hasError('school_name') ? 'is-invalid' : '' ?>" 
							       placeholder="Enter school name" value="<?= old('school_name') ?>" />
							<?php if (session('validation')?->hasError('school_name')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('school_name') ?></div>
							<?php endif; ?>
						</div>
						<!--end::School Name-->
						
						<!--begin::School Category-->
						<div class="col-lg-6 mb-2">
							<label class="form-label required">School Category</label>
							<select class="form-select <?= session('validation')?->hasError('school_category') ? 'is-invalid' : '' ?>" name="school_category">
							    <option value="">Select school category</option>
							    <?php if (!empty($schoolCategory)): ?>
							        <?php foreach ($schoolCategory as $row): ?>
							            <option value="<?= esc($row['sch_cat_id']) ?>" <?= old('school_category') == $row['sch_cat_id'] ? 'selected' : '' ?>>
							                <?= esc($row['sch_cat_name']) ?>
							            </option>
							        <?php endforeach; ?>
							    <?php endif; ?>
							</select>
							<?php if (session('validation')?->hasError('school_category')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('school_category') ?></div>
							<?php endif; ?>
						</div>
						<!--end::School Category-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Additional Info-->
					<div class="row mb-2">
						<!--begin::School Motto-->
						<div class="col-lg-12 mb-2">
							<label class="form-label">School Motto</label>
							<input type="text" name="school_motto" class="form-control <?= session('validation')?->hasError('school_motto') ? 'is-invalid' : '' ?>" 
							       placeholder="Enter school motto" value="<?= old('school_motto') ?>" />
							<?php if (session('validation')?->hasError('school_motto')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('school_motto') ?></div>
							<?php endif; ?>
						</div>
						<!--end::School Motto-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Logo Upload-->
					<div class="row mb-6">
					    <!--begin::Logo-->
						<div class="col-lg-12 mb-6">
							<label class="form-label">School Logo</label>
							<input type="file" name="school_logo" class="form-control <?= session('validation')?->hasError('school_logo') ? 'is-invalid' : '' ?>" accept="image/*" />
							<div class="form-text">Allowed: JPG, PNG, GIF (Max: 2MB)</div>
							<?php if (session('validation')?->hasError('school_logo')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('school_logo') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Logo-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Contact Information-->
					<div class="row mb-2">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Contact Information</label>
						
						<!--begin::Email-->
						<div class="col-lg-6 mb-2">
							<label class="form-label required">Email</label>
							<input type="email" name="email" class="form-control <?= session('validation')?->hasError('email') ? 'is-invalid' : '' ?>" 
							       placeholder="school@example.com" value="<?= old('email') ?>" />
							<?php if (session('validation')?->hasError('email')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('email') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Email-->
						
						<!--begin::Phone-->
						<div class="col-lg-6 mb-2">
							<label class="form-label required">Phone</label>
							<input type="text" name="phone" class="form-control <?= session('validation')?->hasError('phone') ? 'is-invalid' : '' ?>" 
							       placeholder="1234567" value="<?= old('phone') ?>" maxlength="7" />
							<?php if (session('validation')?->hasError('phone')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('phone') ?></div>
							<?php endif; ?>
							<div class="form-text">7-digit phone number</div>
						</div>
						<!--end::Phone-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Location-->
					<div class="row mb-4">
						<!--begin::Province-->
						<div class="col-lg-6 mb-2">
							<label class="form-label required">Province</label>
							<select class="form-select <?= session('validation')?->hasError('province') ? 'is-invalid' : '' ?>" 
							        name="province" id="province-select">
								<option value="">Select province...</option>
								<?php if (!empty($province)): ?>
									<?php foreach($province as $row): ?>
										<option value="<?= esc($row['province_id']) ?>" <?= old('province') == $row['province_id'] ? 'selected' : '' ?>>
											<?= esc($row['province_name']) ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<?php if (session('validation')?->hasError('province')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('province') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Province-->
						
						<!--begin::District-->
						<div class="col-lg-6 mb-2">
							<span id="loader" style="display: none;">
								<img src="<?= base_url('resources/ajax-loader/ajax-loader-3.gif') ?>" alt="Loading..." />
							</span>
							
							<div class="district-response">
								<?php if (!empty(old('province'))): ?> 
									<label class="form-label required">District</label>
									<select class="form-select <?= session('validation')?->hasError('district') ? 'is-invalid' : '' ?>" 
									        name="district" id="district-select">
										<option value="">Select district...</option>
										<?php if (!empty($provinceDistrict)): ?>
											<?php foreach ($provinceDistrict as $row): ?>
												<option value="<?= esc($row['district_id']) ?>" <?= old('district') == $row['district_id'] ? 'selected' : '' ?>>
													<?= esc($row['district_name']) ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<?php if (session('validation')?->hasError('district')): ?>
										<div class="invalid-feedback"><?= session('validation')->getError('district') ?></div>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
						<!--end::District-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Address-->
					<div class="row mb-6">
						<div class="col-lg-12 mb-6">
							<label class="form-label required">Address</label>
							<textarea name="address" class="form-control <?= session('validation')?->hasError('address') ? 'is-invalid' : '' ?>" 
							          rows="3" placeholder="Enter full address"><?= old('address') ?></textarea>
							<?php if (session('validation')?->hasError('address')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('address') ?></div>
							<?php endif; ?>
						</div>
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Subscription Information-->
					<div class="row mb-6">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Subscription Information</label>
						
						<!--begin::Plan-->
						<div class="col-lg-4 mb-2">
							<label class="form-label required">Navuli Plan</label>
							<select name="plan_id" class="form-select <?= session('validation')?->hasError('plan_id') ? 'is-invalid' : '' ?>">
								<option value="">Select plan...</option>
								<?php if (!empty($plans)): ?>
									<?php foreach ($plans as $plan): ?>
										<?php 
										$planId = is_array($plan) ? $plan['plan_id'] : $plan->plan_id;
										$planName = is_array($plan) ? $plan['plan_name'] : $plan->plan_name;
										?>
										<option value="<?= esc($planId) ?>" <?= old('plan_id') == $planId ? 'selected' : '' ?>>
											<?= esc($planName) ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<?php if (session('validation')?->hasError('plan_id')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('plan_id') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Plan-->
						
						<!--begin::Term-->
						<div class="col-lg-4 mb-2">
							<label class="form-label required">Term (Months)</label>
							<select name="plan_term" class="form-select <?= session('validation')?->hasError('plan_term') ? 'is-invalid' : '' ?>">
								<option value="">Select term...</option>
								<option value="1" <?= old('plan_term') === '1' ? 'selected' : '' ?>>1 Month</option>
								<option value="12" <?= old('plan_term') === '12' ? 'selected' : '' ?>>12 Months</option>
								<option value="24" <?= old('plan_term') === '24' ? 'selected' : '' ?>>24 Months</option>
								<option value="36" <?= old('plan_term') === '36' ? 'selected' : '' ?>>36 Months</option>
							</select>
							<?php if (session('validation')?->hasError('plan_term')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('plan_term') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Term-->
						
						<!--begin::Payment Mode-->
						<div class="col-lg-4 mb-2">
							<label class="form-label required">Payment Mode</label>
							<select name="payment_mode" class="form-select <?= session('validation')?->hasError('payment_mode') ? 'is-invalid' : '' ?>">
								<option value="">Select payment mode...</option>
								<option value="Cash" <?= old('payment_mode') === 'Cash' ? 'selected' : '' ?>>Cash</option>
								<option value="Check" <?= old('payment_mode') === 'Check' ? 'selected' : '' ?>>Check</option>
								<option value="Bank Transfer" <?= old('payment_mode') === 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
								<option value="Master Card" <?= old('payment_mode') === 'Master Card' ? 'selected' : '' ?>>Master Card</option>
								<option value="MPaisa" <?= old('payment_mode') === 'MPaisa' ? 'selected' : '' ?>>MPaisa</option>
								<option value="MyCash" <?= old('payment_mode') === 'MyCash' ? 'selected' : '' ?>>MyCash</option>
							</select>
							<?php if (session('validation')?->hasError('payment_mode')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('payment_mode') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Payment Mode-->
					</div>
					<!--end::Row-->
					
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<a href="<?= base_url('school') ?>" class="btn btn-light me-3">Cancel</a>
						<button type="submit" class="btn btn-primary" id="submit-btn">
							<i class="ki-duotone ki-check fs-2"></i>
							Save School
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
    const form = document.getElementById('kt_school_add_form');
    const submitBtn = document.getElementById('submit-btn');
    
    // Form submission with loading state
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        });
    }
    
    // Province-District AJAX
    const provinceSelect = document.getElementById('province-select');
    const districtResponse = document.querySelector('.district-response');
    const loader = document.getElementById('loader');
    
    if (provinceSelect && districtResponse) {
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            
            if (!provinceId) {
                districtResponse.innerHTML = '';
                return;
            }
            
            // Show loader
            if (loader) loader.style.display = 'inline-block';
            districtResponse.style.display = 'none';
            
            // Fetch districts
            fetch('<?= base_url('district/getDistrictByProvince') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'id': provinceId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (loader) loader.style.display = 'none';
                districtResponse.style.display = 'block';
                
                if (data.success) {
                    districtResponse.innerHTML = data.html;
                } else {
                    districtResponse.innerHTML = '<label class="form-label required">District</label>' +
                        '<div class="alert alert-danger">' + data.error + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (loader) loader.style.display = 'none';
                districtResponse.style.display = 'block';
                districtResponse.innerHTML = '<label class="form-label required">District</label>' +
                    '<div class="alert alert-danger">Error loading districts. Please try again.</div>';
            });
        });
    }
});
</script>
