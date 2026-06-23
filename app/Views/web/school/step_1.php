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
															    
															    <div class="col-md-12">
    														        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">Account Plan</h3>
    														    </div>
															    
															    
															    <!--begin::Label-->
															<label class="form-label mb-3">Select a plan</label>
															<!--end::Label-->
																<!--begin::Col-->
																<div class="col-lg-4">
																	<!--begin::Option-->
																	<input type="radio" class="btn-check" name="account_type" value="personal" checked="checked" id="kt_create_account_form_account_type_personal" />
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
																			<span class="text-gray-900 fw-bold d-block fs-4 mb-2">Free</span>
																			<span class="text-muted fw-semibold fs-6">$0 - Limited features</span>
																		</span>
																		<!--end::Info-->
																	</label>
																	<!--end::Option-->
																</div>
																<!--end::Col-->
																<!--begin::Col-->
																<div class="col-lg-4">
																	<!--begin::Option-->
																	<input type="radio" class="btn-check" name="account_type" value="corporate" id="kt_create_account_form_account_type_corporate" />
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
																	<input type="radio" class="btn-check" name="account_type" value="corporate" id="kt_create_account_form_account_type_corporate" />
																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center" for="kt_create_account_form_account_type_corporate">
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
															</div>
															<!--end::Row-->
															
															<!--begin::Row-->
															<div class="row mb-2" data-kt-buttons="true">
															    
															    <div class="col-md-12">
    														        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">School Detail</h3>
    														    </div>
															    
															    <!--begin::Label-->
    															<label class="form-label mb-3">Select school category</label>
    															<!--end::Label-->
																<!--begin::Col-->
																<div class="col">
																	<!--begin::Option-->
																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
																		<input type="radio" class="btn-check" name="sch_category" value="1" />
																		<span class="fw-bold fs-3">Pre-School</span>
																	</label>
																	<!--end::Option-->
																</div>
																<!--end::Col-->
																<!--begin::Col-->
																<div class="col">
																	<!--begin::Option-->
																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 active">
																		<input type="radio" class="btn-check" name="sch_category" checked="checked" value="2" />
																		<span class="fw-bold fs-3">Kindergarten</span>
																	</label>
																	<!--end::Option-->
																</div>
																<!--end::Col-->
																<!--begin::Col-->
																<div class="col">
																	<!--begin::Option-->
																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
																		<input type="radio" class="btn-check" name="sch_category" value="3" />
																		<span class="fw-bold fs-3">Primary</span>
																	</label>
																	<!--end::Option-->
																</div>
																<!--end::Col-->
																<!--begin::Col-->
																<div class="col">
																	<!--begin::Option-->
																	<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
																		<input type="radio" class="btn-check" name="sch_category" value="4" />
																		<span class="fw-bold fs-3">Secondary</span>
																	</label>
																	<!--end::Option-->
																</div>
																<!--end::Col-->
															</div>
															<!--end::Row-->
														</div>
														<!--end::Input group-->
														
														
															<!--begin::Input group-->
														<div class="mb-10 fv-row">
															<!--begin::Label-->
															<label class="form-label mb-3">School Name</label>
															<!--end::Label-->
															<!--begin::Input-->
															<input type="text" class="form-control form-control-lg" name="account_name" placeholder="" value="" />
															<!--end::Input-->
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
                                                                    <select class="form-select selectProvince" aria-label="Select example" name="province">
                                                                        <option value="">Select ...</option>
                                                                        <?php foreach($province as $row): ?>
                                                                            <?php if($row['province_name'] != "Other"): ?>
                                                                                <option value="<?= $row['province_id'] ?>" 
                                                                                        <?= (service('request')->getPost('province') == $row['province_id']) ? 'selected' : '' ?>>
                                                                                    <?= $row['province_name'] ?>
                                                                                </option>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <!--end::Col-->
                                                                <!--begin::Col-->
                                                                <div class="col-lg-6">
                                                                    <span id="loader" style="display: none;">
                                                                        <img src="<?php echo base_url('resources/ajax-loader/ajax-loader-3.gif'); ?>" />
                                                                    </span>
                                                                    <div class="response"></div>
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
                                                                    <input type="email" class="form-control" value="" placeholder="Enter email" name="email">
                                                                </div>
                                                                <!--end::Col-->
                                                                <!--begin::Col-->
                                                                <div class="col-lg-6">
                                                                    <label class="form-label mb-3 required">Phone</label>
                                                                    <input type="text" class="form-control" value="" placeholder="Enter phone" name="phone">
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
                                                                    <input type="text" class="form-control" value="" placeholder="Enter address" name="address">
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
                                                                        <input type="password" class="form-control" value="" placeholder="Enter password" name="password" id="password">
                                                                        <span class="input-group-text toggle-password" data-target="password">
                                                                            <i class="bi bi-eye-slash"></i> <!-- Bootstrap Icons -->
                                                                            <!-- Alternatively, use Font Awesome: <i class="fas fa-eye-slash"></i> -->
                                                                        </span>
                                                                    </div>
                                                                    <!--end::Input wrapper-->
                                                                </div>
                                                                <!--end::Col-->
                                                                <!--begin::Col-->
                                                                <div class="col-lg-6">
                                                                    <label class="form-label mb-3 required">Re-type Password</label>
                                                                    <!--begin::Input wrapper with icon-->
                                                                    <div class="input-group">
                                                                        <input type="password" class="form-control" value="" placeholder="Re-type password" name="re-type-password" id="re-type-password">
                                                                        <span class="input-group-text toggle-password" data-target="re-type-password">
                                                                            <i class="bi bi-eye-slash"></i> <!-- Bootstrap Icons -->
                                                                            <!-- Alternatively, use Font Awesome: <i class="fas fa-eye-slash"></i> -->
                                                                        </span>
                                                                    </div>
                                                                    <!--end::Input wrapper-->
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