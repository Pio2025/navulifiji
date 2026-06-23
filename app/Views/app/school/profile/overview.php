<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Overview</h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url(); ?>school/dashboard" class="text-muted text-hover-primary">Home</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">School Profile</li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
	</div>
	<!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<!--begin::Content container-->
	<div id="kt_app_content_container" class="app-container container-xxl">
	    
	    
	    <?= $this->include('app/school/profile/navbar') ?>
	    
		
		<!--begin::Row-->
		<div class="row g-5 g-xxl-8">
			<!--begin::Col-->
			<div class="col-xl-12">
			    
			    <?= $this->include('templates/flash_messages') ?>
			    
				<!--begin::Charts Widget 1-->
				<div class="card mb-5 mb-xxl-8">
					<!--begin::Header-->
					<div class="card-header border-0 pt-5">
						<!--begin::Title-->
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bold fs-3 mb-1">School Profile</span>
							<span class="text-muted fw-semibold fs-7">Overview of school profile including the active NavuliFiji plan </span>
						</h3>
						<!--end::Title-->
						<!--begin::Toolbar-->
						<div class="card-toolbar">
							<a href="<?php echo base_url(); ?>school/update/<?php echo $school['sch_id']; ?>" class="btn btn-sm btn-light-primary" >Update School Profile</a>
						</div>
						<!--end::Toolbar-->
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body">    
						<!--begin::Step 1-->
						<div class="current" data-kt-stepper-element="content">
							<!--begin::Wrapper-->
							<div class="w-100">
								<!--begin::Input group-->
								<div class="fv-row">
									<!--begin::Row-->
									<div class="row">
									    
									    <div class="col-md-12">
									        <h3 class="fw-semibold badge-light-primary mb-10" style="width:100%;padding:20px;">Account Plan</h3>
									    </div>
									    
									    
										<!--begin::Col-->
										<div class="col-lg-4">
											<!--begin::Option-->
											<input type="radio" class="btn-check" <?php if($school['plan_id'] == 1){echo 'checked';} ?> id="kt_create_account_form_account_type_personal" />
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
											<input type="radio" disabled class="btn-check" <?php if($school['plan_id'] == 2){echo 'checked';} ?> id="kt_create_account_form_account_type_corporate" />
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
											<input type="radio" disabled class="btn-check" <?php if($school['plan_id'] == 3){echo 'checked';} ?> id="kt_create_account_form_account_type_corporate2" />
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
										
									</div>
									<!--end::Row-->
									
									<!--begin::Row-->
									<div class="row" data-kt-buttons="true">
									    
									    <div class="col-md-12">
									        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">School Detail</h3>
									    </div>
									    
									    <!--begin::Label-->
										<label class="form-label mb-3">School category</label>
										<!--end::Label-->
										
										    <!--begin::Col-->
											<div class="col">
												<!--begin::Option-->
												<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 <?php if($school['sch_cat_id'] == 1){echo 'active';} ?> disabled">
													<input type="radio" class="btn-check" disabled />
													<span class="fw-bold fs-3">Pre-School</span>
												</label>
												<!--end::Option-->
											</div>
											<!--end::Col-->
											<!--begin::Col-->
											<div class="col">
												<!--begin::Option-->
												<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 <?php if($school['sch_cat_id'] == 2){echo 'active';} ?> disabled">
													<input type="radio" class="btn-check" disabled />
													<span class="fw-bold fs-3">Kindergarten</span>
												</label>
												<!--end::Option-->
											</div>
											<!--end::Col-->
											<!--begin::Col-->
											<div class="col">
												<!--begin::Option-->
												<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 <?php if($school['sch_cat_id'] == 3){echo 'active';} ?> disabled">
													<input type="radio" class="btn-check" disabled/>
													<span class="fw-bold fs-3">Primary</span>
												</label>
												<!--end::Option-->
											</div>
											<!--end::Col-->
											<!--begin::Col-->
											<div class="col">
												<!--begin::Option-->
												<label class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4 <?php if($school['sch_cat_id'] == 4){echo 'active';} ?> disabled">
													<input type="radio" class="btn-check"disabled />
													<span class="fw-bold fs-3">Secondary</span>
												</label>
												<!--end::Option-->
											</div>
											<!--end::Col-->
										
									</div>
									<!--end::Row-->
								</div>
								<!--end::Input group-->
								
								<br>
								
								<div class="row">
								    
								</div>
								
									<!--begin::Input group-->
								<div class="mb-10 fv-row">
									<!--begin::Label-->
									<label class="form-label mb-3">School Name</label>
									<!--end::Label-->
									<!--begin::Input-->
									<input type="text" class="form-control form-control-lg" value="<?= esc($school['sch_name']) ?>" readonly/>
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
                                            <label class="form-label mb-3">School Province</label>
                                            <input type="text" class="form-control" value="<?= esc($school['province_name']) ?>" readonly />
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <label class="form-label mb-3">School District</label>
                                            <input type="text" class="form-control" value="<?= esc($school['district_name']) ?>" readonly />
                                            
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
                                            <label class="form-label mb-3">Email</label>
                                            <input type="email" class="form-control" value="<?= esc($school['sch_email']) ?>" readonly >
                                            
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <label class="form-label mb-3">Phone</label>
                                            <input type="text" class="form-control" value="<?= esc($school['sch_phone']) ?>" readonly>
                                            
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
                                            <label class="form-label mb-3">Address</label>
                                            <input type="text" class="form-control" value="<?= esc($school['sch_address']) ?>" readonly>
                                            
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
                                            <label class="form-label mb-3">School Motto</label>
                                            <input type="text" class="form-control" value="<?= esc($school['sch_motto']) ?>" readonly>
                                            
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
												
												
					</div>
					<!--end::Body-->
				</div>
				<!--end::Charts Widget 1-->
			</div>
			<!--end::Col-->
		</div>
		<!--end::Row-->
	</div>
	<!--end::Content container-->
</div>
<!--end::Content-->

