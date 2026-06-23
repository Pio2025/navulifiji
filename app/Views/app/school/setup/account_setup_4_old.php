
<!-- Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- Or add custom CSS -->
<style>
.bg-light-danger {
    background-color: rgba(255, 0, 0, 0.05) !important;
}
</style>

<?= $this->include('app/school/setup/toolbar') ?>


<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<!--begin::Content container-->
	<div id="kt_app_content_container" class="app-container container-xxl">
	    
	    <?= $this->include('app/school/setup/navbar') ?>
	    
	    
	    <?= $this->include('templates/flash_messages') ?>
	    
		<!--begin::Basic info-->
		<div class="card mb-5 mb-xl-10">
			<!--begin::Card header-->
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
				<!--begin::Card title-->
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">Step 5 - Complete Setup</h3>
				</div>
				<!--end::Card title-->
			</div>
			<!--begin::Card header-->
			<!--begin::Content-->
			<div id="kt_account_settings_profile_details" class="collapse show">
				<!--begin::Form-->
				<form id="kt_account_profile_details_form" class="form" method="post" action="<?= site_url('school/configure') ?>">
				    <?= csrf_field() ?>
				    <input type="hidden" name="current_step" value="4" id="current_step">
					<!--begin::Card body-->
					<div class="card-body border-top p-9">
						
						<div class="row">
						    
						    
                            <!--begin::Alert-->
                            <div class="alert alert-info d-flex align-items-center p-5">
                                <!--begin::Icon-->
                                <span class="svg-icon svg-icon-2hx svg-icon-info me-4">
                                    <i class="ki-duotone ki-information fs-2hx text-info">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                <!--end::Icon-->
                            
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column">
                                    <!--begin::Title-->
                                    <h4 class="mb-1 text-info">NOTE:</h4>
                                    <!--end::Title-->
                            
                                    <!--begin::Content-->
                                    <span>Complete your school account setup by creating your administrator profile. This profile will serve as your primary login credentials for system access. As an administrator, you can create additional users, assign roles, manage student admissions and enrollments, and configure system settings. For optimal system performance, we recommend completing all initial configurations before activating the account for daily operations.</span>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Alert-->
                            
                            <div class="col-md-12">
						        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">Personal Detail</h3>
						    </div>
						    
						    <!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <!--begin::Label-->
                                        <label class="form-label mb-3 required">First Name</label>
                                        <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('fname')) ? 'is-invalid' : '' ?>" value="<?= old('fname', isset($old['fname']) ? $old['fname'] : '') ?>" placeholder="Enter first name" name="fname">
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('fname')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('fname') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <label class="form-label mb-3">Middle Name</label>
                                        <input type="text" class="form-control" value="<?= old('oname', isset($old['oname']) ? $old['oname'] : '') ?>" placeholder="Enter middle name" name="oname">
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <label class="form-label mb-3 required">Last Name</label>
                                        <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('lname')) ? 'is-invalid' : '' ?>" value="<?= old('lname', isset($old['lname']) ? $old['lname'] : '') ?>" placeholder="Enter last name" name="lname">
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('lname')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('lname') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
								<br>
							</div>
					        <!--end::Input group-->
					        <!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <!--begin::Label-->
                                        <label class="form-label mb-3 required">Select Province</label>
                                        <select class="form-select selectProvince <?= (isset($validation) && $validation->hasError('province')) ? 'is-invalid' : '' ?>" aria-label="Select example" name="province">
                                            <option value="">Select ...</option>
                                            <?php foreach($province as $row): ?>
                                                <?php if($row['province_name'] != "Other"): ?>
                                                    <option value="<?= $row['province_id'] ?>" <?= (old('province', isset($old['province']) ? $old['province'] : '') == $row['province_id']) ? 'selected' : '' ?>>
                                                        <?= $row['province_name'] ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('province')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('province') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <span id="loader" style="display: none;">
                                            <img src="<?php echo base_url('resources/ajax-loader/ajax-loader-3.gif'); ?>" />
                                        </span>
                                        <div class="response">
                                            <?php if (!empty(old('province'))): ?> 
                                                <!--begin::Label-->
                                                <label class="form-label mb-3 required">Select District</label>
                                                
                                                <select class="form-select <?= (isset($validation) && $validation->hasError('district')) ? 'is-invalid' : '' ?>" aria-label="Select example" name="district">
                                                    <option value="">Select ...</option>
                                                    <?php 
                                                    // Try to get provinceDistrict from session if not directly available
                                                    $districts = $provinceDistrict ?? session()->getFlashdata('provinceDistrict') ?? [];
                                                    ?>
                                                    <?php foreach ($districts as $row): ?>
                                                        <option value="<?= esc($row['district_id']) ?>" 
                                                            <?= old('district') == $row['district_id'] ? 'selected' : '' ?>>
                                                            <?= esc($row['district_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            
                                                <!-- Error display -->
                                                <?php if (isset($validation) && $validation->hasError('district')): ?>
                                                    <div class="invalid-feedback">
                                                        <?= $validation->getError('district') ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
								<br>
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <!--begin::Label-->
                                        <label class="form-label mb-3 required">Gender</label>
                                        <select class="form-select <?= (isset($validation) && $validation->hasError('gender')) ? 'is-invalid' : '' ?>" aria-label="Select example" name="gender">
                                            <option value="">Select ...</option>
                                            <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Male</option>
                                            <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('gender')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('gender') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <label class="form-label mb-3 required">DOB</label>
                                        <input class="form-control <?= (isset($validation) && $validation->hasError('dob')) ? 'is-invalid' : '' ?>" placeholder="Pick date rage" id="kt_daterangepicker_3"value="<?= old('dob', isset($old['dob']) ? $old['dob'] : '') ?>" name="dob"/>
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('dob')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('dob') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
								<br>
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <!--begin::Label-->
                                        <label class="form-label mb-3 required">Email</label>
                                        <input type="email" class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>" value="<?= old('email', isset($old['email']) ? $old['email'] : '') ?>" placeholder="Enter email" name="email">
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('email') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <label class="form-label mb-3 required">Phone</label>
                                        <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('phone')) ? 'is-invalid' : '' ?>" value="<?= old('phone', isset($old['phone']) ? $old['phone'] : '') ?>" placeholder="Enter phone" name="phone">
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('phone')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('phone') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
								<br>
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row">
								<!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12">
                                        <!--begin::Label-->
                                        <label class="form-label mb-3 required">Address</label>
                                        <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('address')) ? 'is-invalid' : '' ?>" value="<?= old('address', isset($old['address']) ? $old['address'] : '') ?>" placeholder="Enter address" name="address">
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('address')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('address') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
								<br>
							</div>
							<!--end::Input group-->
							
							<!--begin::Input group-->
                            <div class="fv-row">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <!--begin::Label-->
                                        <label class="form-label mb-3 required">Password</label>
                                        <!--begin::Input wrapper with icon-->
                                        <div class="input-group">
                                            <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>" value="" placeholder="Enter password" name="password" id="password">
                                            <span class="input-group-text toggle-password" data-target="password">
                                                <i class="bi bi-eye-slash"></i> <!-- Bootstrap Icons -->
                                                <!-- Alternatively, use Font Awesome: <i class="fas fa-eye-slash"></i> -->
                                            </span>
                                        </div>
                                        <!--end::Input wrapper-->
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('password')): ?>
                                            <div class="invalid-feedback d-block">
                                                <?= $validation->getError('password') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-lg-6">
                                        <label class="form-label mb-3 required">Re-type Password</label>
                                        <!--begin::Input wrapper with icon-->
                                        <div class="input-group">
                                            <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('re-type-password')) ? 'is-invalid' : '' ?>" value="" placeholder="Re-type password" name="re-type-password" id="re-type-password">
                                            <span class="input-group-text toggle-password" data-target="re-type-password">
                                                <i class="bi bi-eye-slash"></i> <!-- Bootstrap Icons -->
                                                <!-- Alternatively, use Font Awesome: <i class="fas fa-eye-slash"></i> -->
                                            </span>
                                        </div>
                                        <!--end::Input wrapper-->
                                        <!-- Error display -->
                                        <?php if (isset($validation) && $validation->hasError('re-type-password')): ?>
                                            <div class="invalid-feedback d-block">
                                                <?= $validation->getError('re-type-password') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <br>
                            </div>
                            <!--end::Input group-->
						</div>
					</div>
					<!--end::Card body-->
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button type="submit" class="btn btn-primary">Complete Setup</button>
					</div>
					<!--end::Actions-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Basic info-->
	</div>
	<!--end::Content container-->
</div>
<!--end::Content-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        //-------------------------------------------------------------------------------------
        $(".selectProvince").change(function(){
            // Get the selected value directly
            var id = $(this).val();
            
            // Check if a valid option is selected
            if (!id) {
                $(".response").html('');
                return;
            }
            
            $.ajax({
                url: "<?= base_url('district/getDistrictByProvince') ?>",
                type: 'POST',
                data: {
                    id: id, 
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                beforeSend: function(){
                    $("#loader").show();
                    $(".response").hide();
                },
                success: function(response){
                    $('.response').empty();
                    $(".response").show();
                    
                    if (response.success) {
                        $('.response').html(response.html);
                    } else {
                        $('.response').html('<div class="alert alert-danger">' + response.error + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.log('Response:', xhr.responseText);
                    $('.response').show().html('<div class="alert alert-danger">Error loading districts</div>');
                },
                complete: function(){
                    $("#loader").hide();
                }
            });
        });
        
        //-------------------------------------------------------------------------------------
        //password visibility/hide toggle icon
        $('.toggle-password').click(function() {
            const targetId = $(this).data('target');
            const passwordInput = $('#' + targetId);
            const icon = $(this).find('i');
            
            // Toggle password visibility
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
                // For Font Awesome: icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
                // For Font Awesome: icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
        
        //-------------------------------------------------------------------------------------
        
        $("#kt_daterangepicker_3").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                //minYear: 1901,
                //maxYear: parseInt(moment().format("YYYY"),12)
            }, function(start, end, label) {
                //var years = moment().diff(start, "years");
                //alert("You are " + years + " years old!");
            }
        );
        
    });

</script>
