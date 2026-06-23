
			<!--begin::How It Works Section-->
			<div class="mb-n10 mb-lg-n20 z-index-2">
				<!--begin::Container-->
				<div class="container">
					<!--begin::Heading-->
					<div class="text-center mb-17">
						<!--begin::Title-->
						<h3 class="fs-2hx text-gray-900 mb-5" id="how-it-works" data-kt-scroll-offset="{default: 100, lg: 150}">School Account Registration</h3>
						<!--end::Title-->
						<!--begin::Text-->
						<div class="fs-5 text-muted fw-bold">Welcome! Please complete the form below to register your school for Navuli Fiji. This initial setup creates your school's master account, which you can use to add administrators, teachers, and students once approved. All applications are reviewed to ensure the best experience for every school.</div>
						<!--end::Text-->
					</div>
					<!--end::Heading-->
					<!--begin::Row-->
					<div class="row w-100 gy-10 mb-md-20">
						<!--begin::Col-->
						<div class="col-md-12 px-5">
						    
						    <!-- Display errors -->
                            <?= $this->include('templates/flash_messages') ?>
						    
						    
							<!--begin::Stepper-->
									<div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid gap-10" id="kt_create_account_stepper2">
										
										<!--begin::Content-->
										<div class="card d-flex flex-row-fluid flex-center">
											<!--begin::Form-->
											<form class="card-body py-20 w-100 mw-xl-1000px px-9" method="post" enctype="multipart/form-data" action="<?= site_url('account/subscribe') ?>">
                                            <?= csrf_field() ?>
                                            
												<!--begin::Step 1-->
												<div class="current" data-kt-stepper-element="content">
													<!--begin::Wrapper-->
													<div class="w-100">
														<!--begin::Heading-->
														<div class="pb-10 pb-lg-15">
															<!--begin::Title-->
															<h2 class="fw-bold d-flex align-items-center text-gray-900">Choose Account Type
															<span class="ms-1" data-bs-toggle="tooltip" title="Billing is issued based on your selected account type">
																<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
																	<span class="path1"></span>
																	<span class="path2"></span>
																	<span class="path3"></span>
																</i>
															</span></h2>
															<!--end::Title-->
															<!--begin::Notice-->
															<div class="text-muted fw-semibold fs-6">Select the plan that best fits your school's needs and size. Each tier is designed to provide the right tools and support to streamline your administration and enhance learning outcomes.
															<a href="<?= site_url('help') ?>" target="_blank" class="link-primary fw-bold">Help Page</a>..</div>
															<!--end::Notice-->
														</div>
														<!--end::Heading-->
														
														
														
														<!--begin::Input group-->
														<div class="fv-row">
															<!--begin::Row-->
															<div class="row">
															    
															    <div class="col-md-12 mb-4">
    														        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">Account Plan</h3>
    														    </div>
															    
															    
															    <!--begin::Label-->
															<label class="form-label mb-3">Select a plan <span><a href="<?php echo base_url(); ?>account/plan" target="_blank">Learn more...</a></span></label>
															<!--end::Label-->
																<!--begin::Col-->
																<div class="col-lg-4">
																	<!--begin::Option-->
																	<input type="radio" class="btn-check" checked name="account_type" <?= (isset($old['account_type']) && $old['account_type'] == '1') ? 'checked' : '' ?> value="1" id="kt_create_account_form_account_type_personal" />
																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-10" for="kt_create_account_form_account_type_personal">
																		<i class="ki-duotone ki-badge fs-3x me-5">
																			<span class="path1"></span>
																			<span class="path2"></span>
																			<span class="path3"></span>
																			<span class="path4"></span>
																			<span class="path5"></span>
																		</i>
																		<!--begin::Info-->
																		<span class="d-block fw-semibold text-start">
																			<span class="text-gray-900 fw-bold d-block fs-4 mb-2">Trial</span>
																			<span class="text-muted fw-semibold fs-6">$0 - Limited time & features</span>
																		</span>
																		<!--end::Info-->
																	</label>
																	<!--end::Option-->
																</div>
																<!--end::Col-->
																<!--begin::Col-->
																<div class="col-lg-4">
																	<!--begin::Option-->
																	<input type="radio" disabled class="btn-check" name="account_type" <?= (isset($old['account_type']) && $old['account_type'] == '2') ? 'checked' : '' ?> value="2" id="kt_create_account_form_account_type_corporate" />
																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center" for="kt_create_account_form_account_type_corporate">
																		<i class="ki-duotone ki-briefcase fs-3x me-5">
																			<span class="path1"></span>
																			<span class="path2"></span>
																		</i>
																		<!--begin::Info-->
																		<span class="d-block fw-semibold text-start">
																			<span class="text-gray-900 fw-bold d-block fs-4 mb-2">Standard</span>
																			<span class="text-muted fw-semibold fs-6">$150FJD / month</span>
																		</span>
																		<!--end::Info-->
																	</label>
																	<!--end::Option-->
																</div>
																<!--end::Col-->
																<!--begin::Col-->
																<div class="col-lg-4">
																	<!--begin::Option-->
																	<input type="radio" disabled class="btn-check" name="account_type" <?= (isset($old['account_type']) && $old['account_type'] == '3') ? 'checked' : '' ?> value="3" id="kt_create_account_form_account_type_corporate2" />
																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center" for="kt_create_account_form_account_type_corporate2">
																		<i class="ki-duotone ki-briefcase fs-3x me-5">
																			<span class="path1"></span>
																			<span class="path2"></span>
																		</i>
																		<!--begin::Info-->
																		<span class="d-block fw-semibold text-start">
																			<span class="text-gray-900 fw-bold d-block fs-4 mb-2">Advanced</span>
																			<span class="text-muted fw-semibold fs-6">$250FJD / month</span>
																		</span>
																		<!--end::Info-->
																	</label>
																	<!--end::Option-->
																</div>
																<!--end::Col-->
																
																<!-- Error display for account_type -->
                                                                <?php if (isset($validation) && $validation->hasError('account_type')): ?>
                                                                    <div class="text-danger mb-2">
                                                                        <small><?= $validation->getError('account_type') ?></small>
                                                                    </div>
                                                                <?php endif; ?>
															</div>
															<!--end::Row-->
															
															<!--begin::Row-->
															<div class="row" data-kt-buttons="true">
															    
															    <div class="col-md-12 mb-4">
    														        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">School Detail</h3>
    														    </div>
															    
															    <!--begin::Label-->
    															<label class="form-label mb-3">Select school category</label>
    															<!--end::Label-->
    															
    															    <!--begin::Col-->
    																<div class="col">
    																	<!--begin::Option-->
    																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 <?= (isset($old['sch_category']) && $old['sch_category'] == '1') ? 'active' : '' ?>">
    																		<input type="radio" class="btn-check" <?= (isset($old['sch_category']) && $old['sch_category'] == '1') ? 'checked' : '' ?> name="sch_category" value="1" />
    																		<span class="fw-bold fs-3">Pre-School</span>
    																	</label>
    																	<!--end::Option-->
    																</div>
    																<!--end::Col-->
    																<!--begin::Col-->
    																<div class="col">
    																	<!--begin::Option-->
    																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 <?= (isset($old['sch_category']) && $old['sch_category'] == '2') ? 'active' : '' ?>">
    																		<input type="radio" class="btn-check" <?= (isset($old['sch_category']) && $old['sch_category'] == '2') ? 'checked' : '' ?> name="sch_category" value="2" />
    																		<span class="fw-bold fs-3">Kindergarten</span>
    																	</label>
    																	<!--end::Option-->
    																</div>
    																<!--end::Col-->
    																<!--begin::Col-->
    																<div class="col">
    																	<!--begin::Option-->
    																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 <?= (isset($old['sch_category']) && $old['sch_category'] == '3') ? 'active' : '' ?>">
    																		<input type="radio" class="btn-check" name="sch_category" <?= (isset($old['sch_category']) && $old['sch_category'] == '3') ? 'checked' : '' ?> value="3" />
    																		<span class="fw-bold fs-3">Primary</span>
    																	</label>
    																	<!--end::Option-->
    																</div>
    																<!--end::Col-->
    																<!--begin::Col-->
    																<div class="col">
    																	<!--begin::Option-->
    																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 <?= (isset($old['sch_category']) && $old['sch_category'] == '4') ? 'active' : '' ?>">
    																		<input type="radio" class="btn-check" name="sch_category" value="4" />
    																		<span class="fw-bold fs-3">Secondary</span>
    																	</label>
    																	<!--end::Option-->
    																</div>
    																<!--end::Col-->
    															 
																<!-- Error display for sch_category -->
                                                                <?php if (isset($validation) && $validation->hasError('sch_category')): ?>
                                                                    <div class="text-danger mb-2">
                                                                        <small><?= $validation->getError('sch_category') ?></small>
                                                                    </div>
                                                                <?php endif; ?>   
																
															</div>
															<!--end::Row-->
														</div>
														<!--end::Input group-->
														
														<br>
														<!--begin::Input group-->
														<div class="mb-10 fv-row">
															<!--begin::Label-->
															<label class="form-label mb-3">School Name</label>
															<!--end::Label-->
															<!--begin::Input-->
															<input type="text" class="form-control form-control-lg <?= (isset($validation) && $validation->hasError('account_name')) ? 'is-invalid' : '' ?>" name="account_name" placeholder="" value="<?= old('account_name', isset($old['account_name']) ? $old['account_name'] : '') ?>" />
															<!--end::Input-->
															<!-- Error display -->
                                                            <?php if (isset($validation) && $validation->hasError('account_name')): ?>
                                                                <div class="invalid-feedback">
                                                                    <?= $validation->getError('account_name') ?>
                                                                </div>
                                                            <?php endif; ?>
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
                                                                            <?php if($row['province_name'] != "Foreign Citizen"): ?>
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
                                                                        <?php if (!empty($old['province'])): ?> 
                                                                        <!--begin::Label-->
                                                                        <label class="form-label mb-3 required">Select District</label>
                                                                        
                                                                        <select class="form-select <?= (isset($validation) && $validation->hasError('district')) ? 'is-invalid' : '' ?>" 
                                                                                aria-label="Select example" 
                                                                                name="district">
                                                                            
                                                                            <option value="">Select ...</option>
                                                                            
                                                                            <?php foreach ($provinceDistrict as $row): ?>
                                                                                <option value="<?= esc($row['district_id']) ?>" 
                                                                                    <?= (old('district', $old['district'] ?? '') == $row['district_id']) ? 'selected' : '' ?>>
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
                                                                    <label class="form-label mb-3 required">School Phone</label>
                                                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('phone')) ? 'is-invalid' : '' ?>" value="<?= old('phone', isset($old['phone']) ? $old['phone'] : '') ?>" placeholder="Enter school phone" name="phone">
                                                                    <!-- Error display -->
                                                                    <?php if (isset($validation) && $validation->hasError('phone')): ?>
                                                                        <div class="invalid-feedback">
                                                                            <?= $validation->getError('phone') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <!--end::Col-->
                                                                <!--begin::Col-->
                                                                <div class="col-lg-6">
                                                                    <!--begin::Label-->
                                                                    <label class="form-label mb-3 required">School Address</label>
                                                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('address')) ? 'is-invalid' : '' ?>" value="<?= old('address', isset($old['address']) ? $old['address'] : '') ?>" placeholder="Enter school address" name="address">
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
                                                                <div class="col-lg-12">
                                                                    <!--begin::Label-->
                                                                    <label class="form-label mb-3 required">School Motto</label>
                                                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('motto')) ? 'is-invalid' : '' ?>" value="<?= old('motto', isset($old['motto']) ? $old['motto'] : '') ?>" placeholder="Enter school motto" name="motto">
                                                                    <!-- Error display -->
                                                                    <?php if (isset($validation) && $validation->hasError('motto')): ?>
                                                                        <div class="invalid-feedback">
                                                                            <?= $validation->getError('motto') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <!--end::Col-->
                                                            </div>
                                                            <!--end::Row-->
                											<br>
                										</div>
                										<!--end::Input group-->
                										
                										<div class="col-md-12 mb-4">
													        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">Personal Detail</h3>
													    </div>
													    
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
                                                                <h4 class="mb-1 text-info">Note:</h4>
                                                                <!--end::Title-->
                                                        
                                                                <!--begin::Content-->
                                                                <span>Your personalized administrator account will be created, granting you full access to configure your school's profile, manage user permissions, and oversee all operations—from student admissions to enrollment processes. Access your dedicated dashboard with your secure credentials to begin shaping your institution's digital future.</span>
                                                                <!--end::Content-->
                                                            </div>
                                                            <!--end::Wrapper-->
                                                        </div>
                                                        <!--end::Alert-->
													    
													    
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
                                                                <!--begin::Col-->
                                                                <div class="col-lg-4">
                                                                    <label class="form-label mb-3 required">Other Name(s)</label>
                                                                    <input type="text" class="form-control" value="<?= old('oname', isset($old['oname']) ? $old['oname'] : '') ?>" placeholder="Enter other name(s)" name="oname">
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
                                                                    <select class="form-select <?= (isset($validation) && $validation->hasError('gender')) ? 'is-invalid' : '' ?>" value="<?= old('gender', isset($old['gender']) ? $old['gender'] : '') ?>" name="gender">
                                                                        <option value="">Select Gender</option>
                                                                        <option value="Male">Male</option>
                                                                        <option value="Female">Female</option>
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
                                                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('dob')) ? 'is-invalid' : '' ?>" value="<?= old('dob', isset($old['dob']) ? $old['dob'] : '') ?>" placeholder="Enter your dob" id="kt_daterangepicker_3" name="dob" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false">
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
                                                                    <input type="email" class="form-control <?= (isset($validation) && $validation->hasError('my_email')) ? 'is-invalid' : '' ?>" value="<?= old('my_email', isset($old['my_email']) ? $old['my_email'] : '') ?>" placeholder="Enter your personal email" name="my_email">
                                                                    <!-- Error display -->
                                                                    <?php if (isset($validation) && $validation->hasError('my_email')): ?>
                                                                        <div class="invalid-feedback">
                                                                            <?= $validation->getError('my_email') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <!--end::Col-->
                                                                <!--begin::Col-->
                                                                <div class="col-lg-6">
                                                                    <label class="form-label mb-3 required">Phone</label>
                                                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('my_phone')) ? 'is-invalid' : '' ?>" value="<?= old('my_phone', isset($old['my_phone']) ? $old['my_phone'] : '') ?>" placeholder="Enter your personal phone" name="my_phone">
                                                                    <!-- Error display -->
                                                                    <?php if (isset($validation) && $validation->hasError('my_phone')): ?>
                                                                        <div class="invalid-feedback">
                                                                            <?= $validation->getError('my_phone') ?>
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
                                                                    <select class="form-select selectProvince2 <?= (isset($validation) && $validation->hasError('province2')) ? 'is-invalid' : '' ?>" aria-label="Select example" name="province2">
                                                                        <option value="">Select ...</option>
                                                                        <?php foreach($province as $row): ?>
                                                                                <option value="<?= $row['province_id'] ?>" <?= (old('province2', isset($old['province2']) ? $old['province2'] : '') == $row['province_id']) ? 'selected' : '' ?>>
                                                                                    <?= $row['province_name'] ?>
                                                                                </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    
                                                                    <!-- Error display -->
                                                                    <?php if (isset($validation) && $validation->hasError('province2')): ?>
                                                                        <div class="invalid-feedback">
                                                                            <?= $validation->getError('province2') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <!--end::Col-->
                                                                <!--begin::Col-->
                                                                <div class="col-lg-6">
                                                                    <span id="loader2" style="display: none;">
                                                                        <img src="<?php echo base_url('resources/ajax-loader/ajax-loader-3.gif'); ?>" />
                                                                    </span>
                                                                    <div class="response2">
                                                                        <?php if (!empty($old['province2'])): ?> 
                                                                        <!--begin::Label-->
                                                                        <label class="form-label mb-3 required">Select District</label>
                                                                        
                                                                        <select class="form-select <?= (isset($validation) && $validation->hasError('district2')) ? 'is-invalid' : '' ?>" 
                                                                                aria-label="Select example" 
                                                                                name="district2">
                                                                            
                                                                            <option value="">Select ...</option>
                                                                            
                                                                            <?php foreach ($provinceDistrict2 as $row): ?>
                                                                                <option value="<?= esc($row['district_id']) ?>" 
                                                                                    <?= (old('district2', $old['district2'] ?? '') == $row['district_id']) ? 'selected' : '' ?>>
                                                                                    <?= esc($row['district_name']) ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    
                                                                        <!-- Error display -->
                                                                        <?php if (isset($validation) && $validation->hasError('district2')): ?>
                                                                            <div class="invalid-feedback">
                                                                                <?= $validation->getError('district2') ?>
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
                                                                <div class="col-lg-12">
                                                                    <!--begin::Label-->
                                                                    <label class="form-label mb-3 required">Address</label>
                                                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('my_address')) ? 'is-invalid' : '' ?>" value="<?= old('my_address', isset($old['my_address']) ? $old['my_address'] : '') ?>" placeholder="Enter your personal address" name="my_address">
                                                                    <!-- Error display -->
                                                                    <?php if (isset($validation) && $validation->hasError('my_address')): ?>
                                                                        <div class="invalid-feedback">
                                                                            <?= $validation->getError('my_address') ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <!--end::Col-->
                                                            </div>
                                                            <!--end::Row-->
                											<br>
                										</div>
                										<!--end::Input group-->
                										
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
                                                                <h4 class="mb-1 text-info">Strong Password:</h4>
                                                                <!--end::Title-->
                                                        
                                                                <!--begin::Content-->
                                                                <span>
                                                                    <div class="form-text mb-3">
                                                                        <div class="text-muted">
                                                                            <strong>Password Requirements:</strong>
                                                                            <ul class="small mb-0 mt-2">
                                                                                <li>Minimum 8 characters, maximum 32 characters</li>
                                                                                <li>At least one uppercase letter (A-Z)</li>
                                                                                <li>At least one lowercase letter (a-z)</li>
                                                                                <li>At least one number (0-9)</li>
                                                                                <li>At least one special character (@$!%*?&)</li>
                                                                            </ul>
                                                                            <p class="small mt-2 mb-0"><strong>Example:</strong> <code>MySchool@2024</code></p>
                                                                        </div>
                                                                    </div>
                                                                </span>
                                                                <!--end::Content-->
                                                            </div>
                                                            <!--end::Wrapper-->
                                                        </div>
                                                        <!--end::Alert-->
														
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
													<!--end::Wrapper-->
												</div>
												<!--end::Step 1-->
												
												
												<!--begin::Actions-->
												<div class="d-flex flex-stack pt-10">
													<!--begin::Wrapper-->
													<div>
														<button type="submit" class="btn btn-lg btn-primary" name="submit_btn">Submit 
														<i class="ki-duotone ki-arrow-right fs-4 ms-1 me-0">
															<span class="path1"></span>
															<span class="path2"></span>
														</i></button>
													</div>
													<!--end::Wrapper-->
												</div>
												<!--end::Actions-->
											</form>
											<!--end::Form-->
										</div>
										<!--end::Content-->
									</div>
									<!--end::Stepper-->
						</div>
						<!--end::Col-->
					</div>
					<!--end::Row-->
				</div>
				<!--end::Container-->
			</div>
			<!--end::How It Works Section-->
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
                                    $('.response').html('<label class="form-label mb-3 required">Select District</label><div class="alert alert-danger">' + response.error + '</div>');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', error);
                                console.log('Response:', xhr.responseText);
                                $('.response').show().html('<label class="form-label mb-3 required">Select District</label><div class="alert alert-danger">Error loading districts</div>');
                            },
                            complete: function(){
                                $("#loader").hide();
                            }
                        });
                    });
                    
                    //-------------------------------------------------------------------------------------
                    
                    //-------------------------------------------------------------------------------------
                    $(".selectProvince2").change(function(){
                        // Get the selected value directly
                        var id = $(this).val();
                        
                        // Check if a valid option is selected
                        if (!id) {
                            $(".response2").html('');
                            return;
                        }
                        
                        $.ajax({
                            url: "<?= base_url('district/getDistrictByProvince2') ?>",
                            type: 'POST',
                            data: {
                                id: id, 
                                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                            },
                            dataType: 'json',
                            beforeSend: function(){
                                $("#loader2").show();
                                $(".response2").hide();
                            },
                            success: function(response){
                                $('.response2').empty();
                                $(".response2").show();
                                
                                if (response.success) {
                                    $('.response2').html(response.html);
                                } else {
                                    $('.response2').html('<label class="form-label mb-3 required">Select District</label><div class="alert alert-danger">' + response.error + '</div>');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', error);
                                console.log('Response:', xhr.responseText);
                                $('.response2').show().html('<label class="form-label mb-3 required">Select District</label><div class="alert alert-danger">Error loading districts</div>');
                            },
                            complete: function(){
                                $("#loader2").hide();
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
                    
                    
                    
                });
                
                /*
                // Function to handle color changes for both inputs
                function handleColorChange(event) {
                    const colorInput = event.target;
                    
                    // Update the value attribute (for form submission)
                    colorInput.setAttribute('value', colorInput.value);
                    
                    // Update the displayed hex value based on which input changed
                    if (colorInput.id === 'primaryColor') {
                        document.getElementById('primaryColorValue').textContent = colorInput.value;
                    } else if (colorInput.id === 'secondaryColor') {
                        document.getElementById('secondaryColorValue').textContent = colorInput.value;
                    }
                    
                    console.log(`${colorInput.id}:`, colorInput.value);
                }
                
                // Add event listeners to both color inputs
                document.getElementById('primaryColor').addEventListener('input', handleColorChange);
                document.getElementById('secondaryColor').addEventListener('input', handleColorChange);
                
                */
    
            </script>