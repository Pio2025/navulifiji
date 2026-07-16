
<style>
.notif-item-border { border-bottom: 2px dashed #F1F1F4 !important; }
.adm-detail-row    { border-bottom: 1px dashed #c4c7d0; }
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">User Details</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('user') ?>" class="text-muted text-hover-primary">Users</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">User Details</li>
			</ul>
		</div>
	</div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<div id="kt_app_content_container" class="app-container container-xxl">
	    
	    <?= $this->include('templates/flash_messages') ?>
	    
	    <!--begin::Layout-->
		<div class="d-flex flex-column flex-lg-row">
			<!--begin::Sidebar-->
			<div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
				<!--begin::Card-->
				<div class="card mb-5 mb-xl-8">
					<!--begin::Card body-->
					<div class="card-body">
						<!--begin::Summary-->
						<div class="d-flex flex-center flex-column py-5">
							<!--begin::Avatar-->
							<div class="symbol symbol-100px symbol-circle mb-7">
								<?php if (!empty($user['profile_photo'])): ?>
									<img src="<?= base_url('uploads/profilePhoto/' . $user['profile_photo']) ?>" alt="<?= esc($user['fname'] . ' ' . $user['lname']) ?>" />
								<?php else: ?>
									<div class="symbol-label fs-2 fw-bold bg-light-primary text-primary">
										<?= strtoupper(substr($user['fname'], 0, 1) . substr($user['lname'], 0, 1)) ?>
									</div>
								<?php endif; ?>
							</div>
							<!--end::Avatar-->
							
							<!--begin::Name-->
							<span class="fs-3 text-gray-800 fw-bold mb-3">
								<?= esc($user['fname'] . ' ' . $user['lname']) ?>
							</span>
							<!--end::Name-->
							
							<!--begin::Position-->
							<div class="mb-9">
								<?php if (!empty($role['role_name'])): ?>
									<div class="badge badge-lg badge-light-primary d-inline">
										<?= esc($role['role_name']) ?>
									</div>
								<?php endif; ?>
								
								<div class="mt-2">
									<?php if ($user['user_status'] === 'Active'): ?>
										<span class="badge badge-light-success">Active</span>
									<?php else: ?>
										<span class="badge badge-light-danger">Inactive</span>
									<?php endif; ?>
								</div>
							</div>
							<!--end::Position-->
						</div>
						<!--end::Summary-->
						
						<?php if($user['email'] != '' || $user['phone'] != ''){ ?>
						
						<!--begin::Details-->
						<div class="d-flex flex-stack fs-4 py-3">
							<div class="fw-bold collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
								<span>Contact Details</span>
								<span class="ms-2 rotate-arrow">
									<i class="ki-duotone ki-down fs-3"></i>
								</span>
							</div>
						</div>
						<!--end::Details-->
						
						<div class="separator"></div>
						
						<!--begin::Details content-->
						<div id="kt_user_view_details" class="collapse show">
							<div class="pb-5 fs-6">
								<!--begin::Details item-->
								<div class="fw-bold mt-5">Email</div>
								<div class="text-gray-600">
									<a href="mailto:<?= esc($user['email']) ?>" class="text-gray-600 text-hover-primary">
										<?= esc($user['email']) ?>
									</a>
								</div>
								<!--end::Details item-->
								
								<!--begin::Details item-->
								<?php if (!empty($user['phone'])): ?>
								<div class="fw-bold mt-5">Phone</div>
								<div class="text-gray-600"><?= esc($user['phone']) ?></div>
								<?php endif; ?>
								<!--end::Details item-->
							</div>
						</div>
						<!--end::Details content-->
						<?php } ?>
					</div>
					<!--end::Card body-->
				</div>
				<!--end::Card-->
				
				<!--begin::Actions-->
				<div class="card">
					<div class="card-body">
						<a href="<?= base_url('user/medical/' . $user['user_id']) ?>" class="btn btn-primary w-100 mb-3">
							<i class="ki-duotone ki-eye fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                            </i>
							View User Medical Record
						</a>
						
						<?php if (!($isOwnProfile ?? false)): ?>
						<a href="<?= base_url('user') ?>" class="btn btn-light-warning w-100">
							<i class="ki-duotone ki-arrow-left fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							Back to User Listing
						</a>
						<?php endif; ?>
						
					</div>
				</div>
				<!--end::Actions-->
			</div>
			<!--end::Sidebar-->
			
			<!--begin::Content-->
			<div class="flex-lg-row-fluid ms-lg-15">
				<!--begin:::Tabs-->
				<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
					<!--begin:::Tab item-->
					<li class="nav-item">
						<a class="nav-link text-active-primary pb-4 <?php if(session()->get('activeTab') == '' || session()->get('activeTab') == 'profile'){echo 'active';} ?>" data-bs-toggle="tab" href="#kt_user_view_overview_tab">Overview</a>
					</li>
					<!--end:::Tab item-->
					<!--begin:::Tab item-->
					<li class="nav-item">
						<a class="nav-link text-active-primary pb-4 <?php if(session()->get('activeTab') == 'security'){echo 'active';} ?>" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#kt_user_view_overview_security">Security</a>
					</li>
					<!--end:::Tab item-->
					<!--begin:::Tab item-->
					<li class="nav-item">
						<a class="nav-link text-active-primary pb-4 <?php if(session()->get('activeTab') == 'log'){echo 'active';} ?>" data-bs-toggle="tab" href="#kt_user_view_overview_events_and_logs_tab">Events & Logs</a>
					</li>
					<!--end:::Tab item-->
					<!--begin:::Tab item-->
                    <li class="nav-item ms-auto">
                        <?php
                        // Use role data from controller ($role comes from findActiveUserRole())
                        $roleCategoryClean = trim(strtolower($role['role_cat_name'] ?? ''));
                    
                        $isStudent = $roleCategoryClean === 'student';
                        $isTeacher = in_array($roleCategoryClean, ['teacher', 'support staff']);
                        $isParent  = in_array($roleCategoryClean, ['parent or guardian', 'parent', 'guardian']);
                        ?>
                    
                        <?php if ($isStudent || $isTeacher || $isParent): ?>
                        <!--begin::Reference menu-->
                        <a href="#" class="btn btn-primary ps-7"
                           data-kt-menu-trigger="click"
                           data-kt-menu-attach="parent"
                           data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-document fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Reference
                            <i class="ki-duotone ki-down fs-2 ms-1 me-0"></i>
                        </a>
                    
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6" data-kt-menu="true">
                    
                            <!--begin::Menu header-->
                            <div class="menu-item px-5">
                                <div class="menu-content pb-2 px-5 fs-7 text-uppercase text-muted">
                                    <?php if ($isStudent): ?>
                                        Student References
                                    <?php elseif ($isTeacher): ?>
                                        Staff References
                                    <?php else: ?>
                                        Parent &amp; Guardian References
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!--end::Menu header-->
                    
                            <?php if ($isStudent): ?>

                                <?php if ($isOwnProfile ?? false): ?>

                                <!--begin::Student own-profile: 3 items only-->
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/certificate-of-enrollment/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-document fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Certificate of Enrolment
                                    </a>
                                </div>

                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link px-5" id="sdOpenRefRequestModal">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-send fs-6 me-2 text-warning">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Request a Reference
                                        <span class="ms-auto badge badge-light-warning fs-8">New</span>
                                    </a>
                                </div>
                                <!--end::Student own-profile-->

                                <?php else: ?>

                                <!--begin::Admin view: all reference links-->
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/certificate-of-enrollment/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-document fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Certificate of Enrolment
                                    </a>
                                </div>

                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/character-reference/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-profile-circle fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </span>
                                        Character Reference
                                    </a>
                                </div>

                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/recommendation-letter/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-send fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Recommendation Letter
                                    </a>
                                </div>

                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/transcript-request/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-clipboard fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Transcript Request
                                    </a>
                                </div>

                                <div class="separator my-2"></div>

                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/conduct-certificate/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-shield-tick fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Conduct Certificate
                                    </a>
                                </div>

                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/clearance-certificate/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-verify fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Clearance Certificate
                                    </a>
                                </div>
                                <!--end::Admin view-->

                                <?php endif; ?>
                    
                            <?php elseif ($isTeacher): ?>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/certificate-of-employment/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-document fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Certificate of Employment
                                    </a>
                                </div>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/performance-recommendation/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-graph-up fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Performance Recommendation
                                    </a>
                                </div>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/character-reference/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-profile-circle fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </span>
                                        Character Reference
                                    </a>
                                </div>
                    
                                <div class="separator my-2"></div>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/conduct-certificate/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-shield-tick fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Conduct Certificate
                                    </a>
                                </div>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/clearance-certificate/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-verify fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Clearance Certificate
                                    </a>
                                </div>
                    
                            <?php elseif ($isParent): ?>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/parent-guardian-certificate/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-people fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </span>
                                        Parent Guardian Certificate
                                    </a>
                                </div>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/parent-involvement-certificate/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-heart fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Parent Involvement Certificate
                                    </a>
                                </div>
                    
                                <div class="separator my-2"></div>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/conduct-certificate/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-shield-tick fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        Conduct Certificate
                                    </a>
                                </div>
                    
                                <div class="menu-item px-5">
                                    <a href="<?= base_url('reference/financial-clearance/' . $user['user_id']) ?>" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-dollar fs-6 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </span>
                                        Financial Clearance
                                    </a>
                                </div>
                    
                            <?php endif; ?>
                            
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="<?= base_url('reference/user-references/' . $user['user_id']) ?>" class="btn btn-light-info w-100 mb-3">
                                    <i class="ki-duotone ki-folder fs-2 me-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    View Generated References
                                </a>
                            </div>
                    
                        </div>
                        <!--end::Menu-->
                        <?php endif; ?>
                    
                    </li>
                    <!--end:::Tab item-->
				</ul>
				<!--end:::Tabs-->
				<!--begin:::Tab content-->
				<div class="tab-content" id="myTabContent">
					<!--begin:::Tab pane-->
					<div class="tab-pane fade <?php if(session()->get('activeTab') == '' || session()->get('activeTab') == 'profile'){echo 'show active';} ?>" id="kt_user_view_overview_tab" role="tabpanel">
					    
					    <!--begin::Card-->
        				<div class="card card-flush mb-6 mb-xl-9">
        					<!--begin::Card header-->
							<div class="card-header mt-6">
								<!--begin::Card title-->
								<div class="card-title flex-column">
									<h2 class="mb-1">Personal Information</h2>
									<div class="fs-6 fw-semibold text-muted">User profile detail</div>
								</div>
								<!--end::Card title-->
								<!--begin::Card toolbar-->
								<?php if (!($isOwnProfile ?? false)): ?>
								<div class="card-toolbar">
									<a type="button" class="btn btn-light-primary btn-sm" href="<?php echo base_url('user/edit/'.$user['user_id']); ?>">
									<i class="ki-duotone ki-pencil fs-2">
        								<span class="path1"></span>
        								<span class="path2"></span>
        							</i>Edit Personal Detail</a>
								</div>
								<?php endif; ?>
								<!--end::Card toolbar-->
							</div>
							<!--end::Card header-->
        					<div class="separator"></div>
        					<!--begin::Card body-->
        					<div class="card-body px-9 pt-4 pb-6">
        						<?php
        						$infoRows = [
        							['Full Name',   esc($user['fname'] . ' ' . ($user['oname'] ?? '') . ' ' . $user['lname']), null],
        							['Email',       '<a href="mailto:' . esc($user['email']) . '" class="text-gray-800 text-hover-primary">' . esc($user['email']) . '</a>', null],
        							['Phone',       !empty($user['phone']) ? esc($user['phone']) : null, null],
        							['Gender',      esc($user['gender'] ?? 'Not specified'), null],
        							['Date of Birth', !empty($user['dob']) ? date('d F Y', strtotime($user['dob'])) : null, null],
        							['Address',     !empty($user['address']) ? nl2br(esc($user['address'])) : null, null],
        							['District',    !empty($user['district_name']) ? esc($user['district_name']) : null, null],
        							['Role',        null, 'role'],
        							['Status',      null, 'status'],
        							['Account Created', date('d F Y', strtotime($user['created_date'])), null],
        							['Online Status', null, 'online'],
        						];
        						$infoRows = array_filter($infoRows, fn($r) => $r[1] !== null || $r[2] !== null);
        						$infoRows = array_values($infoRows);
        						$last     = count($infoRows) - 1;
        						foreach ($infoRows as $i => [$label, $value, $type]):
        						?>
        						<div class="d-flex justify-content-between align-items-center py-3 <?= $i < $last ? 'adm-detail-row' : '' ?>">
        							<span class="text-gray-600 fw-semibold fs-7"><?= $label ?></span>
        							<span class="text-gray-800 fw-bold fs-7 text-end">
        								<?php if ($type === 'role'): ?>
        									<?php if (!empty($role['role_name'])): ?>
        										<span class="badge badge-light-primary fs-8 fw-bold"><?= esc($role['role_name']) ?></span>
        									<?php else: ?>
        										<span class="badge badge-light-secondary fs-8">No Role Assigned</span>
        									<?php endif; ?>
        								<?php elseif ($type === 'status'): ?>
        									<?php if ($user['user_status'] === 'Active'): ?>
        										<span class="badge badge-light-success fs-8 fw-bold">Active</span>
        									<?php else: ?>
        										<span class="badge badge-light-danger fs-8 fw-bold">Inactive</span>
        									<?php endif; ?>
        								<?php elseif ($type === 'online'): ?>
        									<?php if ($user['online_status'] === 'Online'): ?>
        										<span class="badge badge-light-success fs-8"><span class="bullet bullet-dot bg-success me-1"></span>Online</span>
        									<?php else: ?>
        										<span class="badge badge-light-danger fs-8"><span class="bullet bullet-dot bg-danger me-1"></span>Offline</span>
        									<?php endif; ?>
        								<?php else: ?>
        									<?= $value ?>
        								<?php endif; ?>
        							</span>
        						</div>
        						<?php endforeach; ?>
        					</div>
        					<!--end::Card body-->
        				</div>
        				<!--end::Card-->
        				
        				
        				<?php if ($showAdmission): ?>
<!--begin::Card - School Admission-->
<div class="card card-bordered mb-6 mb-xl-9">

    <div class="card-header mt-6">
        <div class="card-title flex-column">
            <h2 class="mb-1">School Admission</h2>
            <div class="fs-6 fw-semibold text-muted">
                <?php if ($isStudent): ?>
                    Admission and enrolment records for this student
                <?php else: ?>
                    School admission records
                <?php endif; ?>
            </div>
        </div>
        <!--begin::Toolbar-->
        <div class="card-toolbar">
            <?php if ($canEditUser): ?>
            <a href="<?= base_url('admission/add/' . $user['user_id']) ?>"
               class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-plus fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Add Admission
            </a>
            <?php endif; ?>
        </div>
        <!--end::Toolbar-->
    </div>

    <div class="separator"></div>

    <div class="card-body p-9 pt-4">

        <?php if (empty($admissions)): ?>
        <!--begin::Empty state-->
        <div class="text-center py-12">
            <i class="ki-duotone ki-element-plus fs-5x text-muted mb-5 ">
                <span class="path1"></span><span class="path2"></span>
                <span class="path3"></span><span class="path4"></span>
                <span class="path5"></span>
            </i>
            <h4 class="text-gray-600 fw-semibold mb-2">No Admission Records Found</h4>
            <p class="text-muted fs-7 mb-6">
                This user has not been admitted to any school yet.
            </p>
            <?php if ($canEditUser): ?>
            <a href="<?= base_url('admission/add/' . $user['user_id']) ?>"
               class="btn btn-primary btn-sm">
                <i class="ki-duotone ki-plus fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Add Admission
            </a>
            <?php endif; ?>
        </div>
        <!--end::Empty state-->

        <?php else: ?>
        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4 dataTable"
                   id="admission_datatable">
                <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                    <tr class="text-start text-muted text-uppercase gs-0">
                        <th class="min-w-180px">School</th>
                        <th class="min-w-120px">Admission Date</th>
                        <th class="min-w-100px">Status</th>
                        <?php if ($isStudent): ?>
                            <th class="min-w-100px">Year / Term</th>
                            <th class="min-w-120px">Stream / Level</th>
                            <th class="min-w-90px">Enrolment</th>
                        <?php endif; ?>
                        <?php if ($canEditUser): ?>
                            <th class="min-w-80px text-end pe-3">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    <?php foreach ($admissions as $adm): ?>
                    <tr id="admission_row_<?= $adm['admission_id'] ?>">

                        <!--begin::School-->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <?php
                                $logoPath = FCPATH . 'uploads/school/logo/' . ($adm['sch_logo'] ?? '');
                                if (!empty($adm['sch_logo']) && file_exists($logoPath)):
                                ?>
                                <div class="symbol symbol-35px flex-shrink-0">
                                    <img src="<?= base_url('uploads/school/logo/' . $adm['sch_logo']) ?>"
                                         alt="<?= esc($adm['sch_name']) ?>"
                                         class="rounded object-fit-cover" />
                                </div>
                                <?php else: ?>
                                <div class="symbol symbol-35px flex-shrink-0">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-bank fs-4 text-primary">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-bold text-gray-800 fs-7">
                                        <?= esc($adm['sch_name'] ?? 'Unknown School') ?>
                                    </div>
                                    <?php if (!empty($adm['sch_address'])): ?>
                                    <div class="text-muted fs-8"><?= esc($adm['sch_address']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <!--end::School-->

                        <!--begin::Date-->
                        <td>
                            <span class="text-gray-700 fs-7">
                                <?= !empty($adm['admission_date'])
                                    ? date('d M Y', strtotime($adm['admission_date']))
                                    : '—' ?>
                            </span>
                        </td>
                        <!--end::Date-->

                        <!--begin::Admission Status-->
                        <td>
                            <?php
                            $statusColors = [
                                'Active'   => 'success',
                                'Inactive' => 'danger',
                                'Pending'  => 'warning',
                                'Rejected' => 'danger',
                            ];
                            $statusColor = $statusColors[$adm['admission_status'] ?? ''] ?? 'secondary';
                            ?>
                            <span class="badge badge-light-<?= $statusColor ?> fs-8 fw-bold">
                                <?= esc($adm['admission_status'] ?? 'Unknown') ?>
                            </span>
                        </td>
                        <!--end::Admission Status-->

                        <?php if ($isStudent): ?>
                        <!--begin::Year / Term-->
                        <td>
                            <?php if (!empty($adm['enrol_year'])): ?>
                                <div class="fw-bold text-gray-800 fs-7"><?= esc($adm['enrol_year']) ?></div>
                                <div class="text-muted fs-8">Term <?= esc($adm['enrol_term'] ?? '—') ?></div>
                            <?php else: ?>
                                <span class="text-muted fs-8">—</span>
                            <?php endif; ?>
                        </td>
                        <!--end::Year / Term-->

                        <!--begin::Stream / Level-->
                        <td>
                            <?php if (!empty($adm['stream_name'])): ?>
                                <div class="fw-bold text-gray-800 fs-7"><?= esc($adm['stream_name']) ?></div>
                                <div class="text-muted fs-8"><?= esc($adm['level_name'] ?? '') ?></div>
                            <?php else: ?>
                                <span class="text-muted fs-8">Not Enrolled</span>
                            <?php endif; ?>
                        </td>
                        <!--end::Stream / Level-->

                        <!--begin::Enrolment Status-->
                        <td>
                            <?php if (!empty($adm['enrol_status'])): ?>
                                <?php $enrolColor = $statusColors[$adm['enrol_status']] ?? 'secondary'; ?>
                                <span class="badge badge-light-<?= $enrolColor ?> fs-8 fw-bold">
                                    <?= esc($adm['enrol_status']) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge badge-light-warning fs-8">Not Enrolled</span>
                            <?php endif; ?>
                        </td>
                        <!--end::Enrolment Status-->
                        <?php endif; // isStudent ?>

                        <?php if ($canEditUser): ?>
                        <!--begin::Actions-->
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= base_url('admission/edit/' . $adm['admission_id']) ?>"
                                   class="btn btn-icon btn-sm btn-light-primary"
                                   title="Edit">
                                    <i class="ki-duotone ki-pencil fs-4">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </a>
                                <button type="button"
                                        class="btn btn-icon btn-sm btn-light-danger btn-delete-admission"
                                        data-id="<?= $adm['admission_id'] ?>"
                                        data-school="<?= esc($adm['sch_name'] ?? 'Unknown') ?>"
                                        title="Delete">
                                    <i class="ki-duotone ki-trash fs-4">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </button>
                            </div>
                        </td>
                        <!--end::Actions-->
                        <?php endif; ?>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!--end::Table-->
        <?php endif; // empty admissions ?>

    </div>
</div>
<!--end::Card - School Admission-->
<?php endif; // showAdmission ?>
        				
        				<!--begin::Card-->
        				<div class="card card-bordered mb-6 mb-xl-9">
        					<!--begin::Card header-->
                            <div class="card-header mt-6">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Next of Kin Information</h2>
                                    <div class="fs-6 fw-semibold text-muted">In case of emergency (Maximum 3 contacts)</div>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <?php
                                $currentKinCount = count($next_of_kin);
                                if ($currentKinCount < 3 && !($isOwnProfile ?? false)):
                                ?>
                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_next_of_kin" onclick="openAddModal()">
                                            <i class="ki-duotone ki-plus fs-2"></i>
                                            Add Contact
                                            <span class="badge badge-light-success ms-2"><?= $currentKinCount ?>/3</span>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="card-toolbar">
                                        <span class="badge badge-light-warning">
                                            <i class="ki-duotone ki-information-5 fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Maximum 3/3 contacts reached
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
        					<div class="separator"></div>
        					<!--begin::Card body-->
        					<div class="card-body p-9 pt-4">
        						<!--begin::Table-->
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-row-gray-300 align-middle gy-5" id="next_of_kin_table">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                                <th class="min-w-150px">Name</th>
                                                <th class="min-w-100px">Relationship</th>
                                                <th class="min-w-100px">Phone</th>
                                                <th class="min-w-150px">Email</th>
                                                <th class="min-w-100px">Type</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="next_of_kin_tbody">
                                            <?php if (!empty($next_of_kin)): ?>
                                                <?php foreach ($next_of_kin as $kin): ?>
                                                    <tr id="kin_row_<?= $kin['next_of_kin_id'] ?>">
                                                        <td class="fw-bold text-gray-800"><?= esc($kin['next_of_kin_name']) ?></td>
                                                        <td><?= esc($kin['next_of_kin_relationship']) ?></td>
                                                        <td><?= esc($kin['next_of_kin_phone']) ?></td>
                                                        <td><?= esc($kin['next_of_kin_email']) ?></td>
                                                        <td>
                                                            <?php if ($kin['is_primary_contact']): ?>
                                                                <span class="badge badge-primary badge-sm">Primary</span>
                                                            <?php endif; ?>
                                                            <?php if ($kin['is_emergency_contact']): ?>
                                                                <span class="badge badge-danger badge-sm">Emergency</span>
                                                            <?php endif; ?>
                                                            <?php if ($kin['authorized_pickup']): ?>
                                                                <span class="badge badge-success badge-sm">Pickup</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-end">
                                                            <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2" onclick="editNextOfKin(<?= $kin['next_of_kin_id'] ?>)" title="Edit">
                                                                <i class="ki-duotone ki-pencil fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </button>
                                                            <button type="button" class="btn btn-icon btn-sm btn-light-danger" onclick="deleteNextOfKin(<?= $kin['next_of_kin_id'] ?>)" title="Delete">
                                                                <i class="ki-duotone ki-trash fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                    <span class="path4"></span>
                                                                    <span class="path5"></span>
                                                                </i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr id="no_data_row">
                                                    <td colspan="6" class="text-center text-muted py-10">
                                                        <i class="ki-duotone ki-information-5 fs-3x text-primary mb-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                        <div class="fw-bold">No next of kin contacts added yet</div>
                                                        <div class="text-gray-600 fs-7">Click "Add Contact" button to add emergency contacts</div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
        					</div>
        					<!--end::Card body-->
        				</div>
        				<!--end::Card-->

                        <?php if (!empty($linkedChildren) || ($roleCatId === 6 || !empty($isAParentFlag))): ?>
                        <!--begin::Card - Linked Children-->
                        <div class="card card-bordered mb-6 mb-xl-9">
                            <div class="card-header mt-6">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Linked Children</h2>
                                    <div class="fs-6 fw-semibold text-muted">Student accounts linked to this parent/guardian</div>
                                </div>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_link_child">
                                        <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        Link Child
                                    </button>
                                </div>
                            </div>
                            <div class="separator"></div>
                            <div class="card-body p-9 pt-4">
                                <?php if (empty($linkedChildren)): ?>
                                <div class="text-center py-8">
                                    <i class="ki-duotone ki-people fs-3x text-gray-300 mb-3">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                    <div class="text-muted fs-7">No children linked yet. Click "Link Child" to add.</div>
                                </div>
                                <?php else: ?>
                                <div class="row g-4" id="linked_children_list">
                                    <?php foreach ($linkedChildren as $child): ?>
                                    <div class="col-md-6 col-xl-4" id="child_card_<?= $child['parent_student_id'] ?>">
                                        <div class="d-flex align-items-center border border-dashed border-gray-300 rounded p-3">
                                            <div class="symbol symbol-45px symbol-circle me-3">
                                                <?php if (!empty($child['profile_photo'])): ?>
                                                    <img src="<?= base_url('uploads/profilePhoto/' . esc($child['profile_photo'])) ?>" alt="" />
                                                <?php else: ?>
                                                    <div class="symbol-label fs-4 fw-bold bg-light-primary text-primary">
                                                        <?= strtoupper(substr($child['fname'], 0, 1) . substr($child['lname'], 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <a href="<?= base_url('user/detail/' . $child['user_id']) ?>" class="fw-bold text-gray-800 text-hover-primary fs-6">
                                                    <?= esc($child['fname'] . ' ' . $child['lname']) ?>
                                                </a>
                                                <div class="text-muted fs-8"><?= esc($child['relationship']) ?></div>
                                            </div>
                                            <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-unlink-child"
                                                    data-link-id="<?= $child['parent_student_id'] ?>"
                                                    title="Unlink">
                                                <i class="ki-duotone ki-cross fs-3">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!--end::Card - Linked Children-->
                        <?php endif; ?>

                        <?php if (!empty($linkedParents)): ?>
                        <!--begin::Card - Linked Parents-->
                        <div class="card card-bordered mb-6 mb-xl-9">
                            <div class="card-header mt-6">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Parent / Guardian Accounts</h2>
                                    <div class="fs-6 fw-semibold text-muted">System accounts linked as parent/guardian for this student</div>
                                </div>
                            </div>
                            <div class="separator"></div>
                            <div class="card-body p-9 pt-4">
                                <div class="row g-4">
                                    <?php foreach ($linkedParents as $parent): ?>
                                    <div class="col-md-6 col-xl-4">
                                        <div class="d-flex align-items-center border border-dashed border-gray-300 rounded p-3">
                                            <div class="symbol symbol-45px symbol-circle me-3">
                                                <?php if (!empty($parent['profile_photo'])): ?>
                                                    <img src="<?= base_url('uploads/profilePhoto/' . esc($parent['profile_photo'])) ?>" alt="" />
                                                <?php else: ?>
                                                    <div class="symbol-label fs-4 fw-bold bg-light-success text-success">
                                                        <?= strtoupper(substr($parent['fname'], 0, 1) . substr($parent['lname'], 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <a href="<?= base_url('user/detail/' . $parent['user_id']) ?>" class="fw-bold text-gray-800 text-hover-primary fs-6">
                                                    <?= esc($parent['fname'] . ' ' . $parent['lname']) ?>
                                                </a>
                                                <div class="text-muted fs-8"><?= esc($parent['relationship']) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Card - Linked Parents-->
                        <?php endif; ?>

					</div>
					<!--end:::Tab pane-->

					<!--begin:::Tab pane-->
                    <div class="tab-pane fade <?php if(session()->get('activeTab') == 'security'){echo 'show active';} ?>"
                         id="kt_user_view_overview_security" role="tabpanel">
                    
                        <?php if (!$isOwnProfile): ?>
                        <!--begin::Access Restricted-->
                        <div class="card">
                            <div class="card-body text-center py-20">
                                
                                <i class="ki-duotone ki-shield-slash fs-5x text-danger mb-5 ">
                                 <span class="path1"></span>
                                 <span class="path2"></span>
                                 <span class="path3"></span>
                                </i>
                                <h3 class="text-gray-700 fw-bold mb-3">Security Settings Restricted</h3>
                                <p class="text-muted fs-6 mb-0">
                                    Security settings, two-factor authentication, and notification preferences
                                    can only be configured on your own account.
                                </p>
                            </div>
                        </div>
                        <!--end::Access Restricted-->
                    
                        <?php else: ?>
                    
                        <!--begin::Card - Profile Security-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2>Profile Security</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-5">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed gy-5">
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            <tr>
                                                <td class="min-w-150px fw-bold text-gray-800">Email</td>
                                                <td><?= esc($user['email']) ?></td>
                                                <td class="text-end">
                                                    <button type="button"
                                                            class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                                            data-bs-toggle="modal" data-bs-target="#kt_modal_update_email">
                                                        <i class="ki-duotone ki-pencil fs-3">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold text-gray-800">Password</td>
                                                <td>••••••••</td>
                                                <td class="text-end">
                                                    <button type="button"
                                                            class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                                            data-bs-toggle="modal" data-bs-target="#kt_modal_update_password">
                                                        <i class="ki-duotone ki-pencil fs-3">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold text-gray-800">Role</td>
                                                <td>
                                                    <?php if (!empty($role['role_name'])): ?>
                                                        <?= esc($role['role_name']) ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-light-warning">No Role Assigned</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button"
                                                            class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                                            data-bs-toggle="modal" data-bs-target="#kt_modal_update_role">
                                                        <i class="ki-duotone ki-pencil fs-3">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--end::Card-->
                    
                        <!--begin::Card - Two Step Authentication-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <div class="card-header border-0">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Two-Step Authentication</h2>
                                    <div class="fs-6 fw-semibold text-muted">
                                        Keep your account extra secure with a second authentication step.
                                    </div>
                                </div>
                                <!--begin::Toggle-->
                                <div class="card-toolbar d-flex align-items-center gap-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-muted fs-7 fw-semibold" id="twofa_toggle_label">Disabled</span>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input w-45px h-25px"
                                                   type="checkbox"
                                                   id="twofa_master_toggle"
                                                   style="cursor:pointer;" />
                                            <label class="form-check-label" for="twofa_master_toggle"></label>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Toggle-->
                            </div>
                        
                            <div class="card-body pb-5" id="twofa_body">
                        
                                <!--begin::Status Banner-->
                                <div id="twofa_status_banner" class="d-flex align-items-center gap-3 mb-6"
                                     style="display:none !important;">
                                    <span id="twofa_status_badge" class="badge badge-light-danger fs-7 px-4 py-2">
                                        Not Enabled
                                    </span>
                                    <span class="text-muted fs-7" id="twofa_method_label"></span>
                                </div>
                                <!--end::Status Banner-->
                        
                                <!--begin::Method Cards-->
                                <div class="row g-5" id="twofa_method_cards" style="display:none;">
                        
                                    <!--begin::Authenticator App-->
                                    <div class="col-md-6">
                                        <div class="card h-100 border border-dashed rounded p-5 twofa-method-card"
                                             id="card_authenticator_app"
                                             style="border-color: #E4E6EF !important; transition: all 0.2s ease;">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="symbol symbol-50px me-4">
                                                    <div class="symbol-label bg-light-primary">
                                                        <i class="ki-duotone ki-phone fs-2x text-primary">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fw-bold text-gray-800 mb-0 fs-6">Authenticator App</h5>
                                                    <span class="text-muted fs-8">Google Authenticator, Authy</span>
                                                </div>
                                                <div id="auth_app_status_icon" style="display:none;">
                                                    <span class="badge badge-light-success fs-8">
                                                        <i class="ki-duotone ki-check-circle fs-5 text-success me-1">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                        Active
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-muted fs-7 mb-5">
                                                Use a time-based one-time password (TOTP). Works offline —
                                                most secure option.
                                            </p>
                                            <button type="button"
                                                    class="btn btn-sm btn-primary w-100 mt-auto"
                                                    id="btn_setup_authenticator"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_add_auth_app">
                                                <i class="ki-duotone ki-fingerprint-scanning fs-4 me-1">
                                                    <span class="path1"></span><span class="path2"></span>
                                                    <span class="path3"></span><span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
                                                Set Up Authenticator
                                            </button>
                                        </div>
                                    </div>
                                    <!--end::Authenticator App-->
                        
                                    <!--begin::Email OTP-->
                                    <div class="col-md-6">
                                        <div class="card h-100 border border-dashed rounded p-5 twofa-method-card"
                                             id="card_otp_email"
                                             style="border-color: #E4E6EF !important; transition: all 0.2s ease;">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="symbol symbol-50px me-4">
                                                    <div class="symbol-label bg-light-warning">
                                                        <i class="ki-duotone ki-sms fs-2x text-warning">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fw-bold text-gray-800 mb-0 fs-6">Email OTP</h5>
                                                    <span class="text-muted fs-8">One-time code via email</span>
                                                </div>
                                                <div id="otp_email_status_icon" style="display:none;">
                                                    <span class="badge badge-light-success fs-8">
                                                        <i class="ki-duotone ki-check-circle fs-5 text-success me-1">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                        Active
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-muted fs-7 mb-5">
                                                Receive a 6-digit code to your email address each time you sign in.
                                            </p>
                                            <button type="button"
                                                    class="btn btn-sm btn-warning text-white w-100 mt-auto"
                                                    id="btn_setup_otp"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_add_one_time_password">
                                                <i class="ki-duotone ki-sms fs-4 me-1">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                                Enable Email OTP
                                            </button>
                                        </div>
                                    </div>
                                    <!--end::Email OTP-->
                        
                                </div>
                                <!--end::Method Cards-->
                        
                                <!--begin::Disabled State-->
                                <div id="twofa_disabled_state" class="text-center py-8">
                                    <i class="ki-duotone ki-shield fs-5x text-danger-light mb-4">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <div class="text-muted fs-6 fw-semibold mb-2">
                                        Two-factor authentication is currently disabled
                                    </div>
                                    <div class="text-muted fs-7">
                                        Toggle the switch above to enable an extra layer of security on your account.
                                    </div>
                                </div>
                                <!--end::Disabled State-->
                        
                                <!--begin::Info Note (shown when enabled)-->
                                <div class="notice d-flex bg-light-info rounded border border-info border-dashed p-4 mt-5"
                                     id="twofa_info_note" style="display:none !important;">
                                    <i class="ki-duotone ki-information fs-2tx text-info me-3 flex-shrink-0">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                    <div class="fs-7 text-gray-700">
                                        Only one method can be active at a time. Enabling a new method replaces
                                        the existing one. If you lose access to your authentication device,
                                        contact your system administrator.
                                    </div>
                                </div>
                                <!--end::Info Note-->
                        
                            </div>
                        </div>
                        <!--end::Card - Two Step Authentication-->
                    
                        <!--begin::Card - Email Notifications-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <div class="card-header border-0">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">Email Notifications</h2>
                                    <div class="fs-6 fw-semibold text-muted">
                                        Choose which module notifications you would like to receive by email.
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="notification_form">
                                    <input type="hidden" name="user_id" value="<?= $sessionUserID ?>" />
                    
                                    <?php
                                    $notifModules = [
                                        ['key' => 'dashboard',     'label' => 'Dashboard',      'desc' => 'Receive system dashboard summary and alerts.',                    'icon' => 'ki-element-11'],
                                        ['key' => 'rbac',          'label' => 'Roles & Access',  'desc' => 'Notifications about role changes and permission updates.',         'icon' => 'ki-abstract-41'],
                                        ['key' => 'user',          'label' => 'User Management', 'desc' => 'Alerts for user account changes, new registrations, status updates.','icon' => 'ki-user'],
                                        ['key' => 'school',        'label' => 'School',          'desc' => 'School profile updates and configuration changes.',                 'icon' => 'ki-bank'],
                                        ['key' => 'admission',     'label' => 'Admissions',      'desc' => 'New admission requests, approvals and rejections.',                 'icon' => 'ki-element-plus'],
                                        ['key' => 'enrolment',     'label' => 'Enrolment',       'desc' => 'Student enrolment confirmations and updates.',                      'icon' => 'ki-abstract-28'],
                                        ['key' => 'classroom',     'label' => 'Classroom',       'desc' => 'Classroom assignments and timetable notifications.',                'icon' => 'ki-element-7'],
                                        ['key' => 'exam',          'label' => 'Examinations',    'desc' => 'Exam schedules, results and report notifications.',                 'icon' => 'ki-chart-pie-3'],
                                        ['key' => 'conduct',       'label' => 'Conduct',         'desc' => 'Student conduct reports and disciplinary actions.',                 'icon' => 'ki-bucket'],
                                        ['key' => 'timetable',     'label' => 'Timetable',       'desc' => 'Timetable changes and schedule updates.',                          'icon' => 'ki-calendar-8'],
                                        ['key' => 'event',         'label' => 'Events',          'desc' => 'School events, activities and announcements.',                      'icon' => 'ki-calendar-tick'],
                                        ['key' => 'communication', 'label' => 'Communication',   'desc' => 'Messages, announcements and school communications.',                'icon' => 'ki-message-text-2'],
                                        ['key' => 'security',      'label' => 'Security',        'desc' => 'Login alerts, 2FA changes and security-related events.',            'icon' => 'ki-shield-tick'],
                                        ['key' => 'medical',       'label' => 'Medical',         'desc' => 'Medical record additions, updates and emergency notifications.',    'icon' => 'ki-heart-circle'],
                                        ['key' => 'reference',     'label' => 'References',      'desc' => 'Generated references and certificate notifications.',               'icon' => 'ki-document'],
                                    ];
                    
                                    foreach ($notifModules as $index => $mod):
                                        $isLast = ($index === count($notifModules) - 1);
                                    
                                        // OFF if: no record exists in DB, OR record exists but value is explicitly 0
                                        // ON only if: record exists AND value is explicitly 1
                                        $isEnabled = !empty($notifications)
                                                  && isset($notifications['notif_' . $mod['key']])
                                                  && (int) $notifications['notif_' . $mod['key']] === 1;
                                    ?>
                                    <!--begin::Notification Item-->
                                    <div class="d-flex align-items-center py-4 <?= !$isLast ? 'notif-item-border' : '' ?>">
                                    
                                        <!--begin::Icon-->
                                        <div class="symbol symbol-40px me-4 flex-shrink-0">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="ki-duotone <?= $mod['icon'] ?> fs-3 text-primary">
                                                    <span class="path1"></span><span class="path2"></span>
                                                    <span class="path3"></span><span class="path4"></span>
                                                    <span class="path5"></span><span class="path6"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <!--end::Icon-->
                                    
                                        <!--begin::Info-->
                                        <div class="flex-grow-1 me-4">
                                            <div class="fw-bold text-gray-800 fs-6"><?= esc($mod['label']) ?></div>
                                            <div class="text-muted fs-7"><?= esc($mod['desc']) ?></div>
                                        </div>
                                        <!--end::Info-->
                                    
                                        <!--begin::Toggle-->
                                        <div class="form-check form-switch form-check-custom form-check-solid flex-shrink-0">
                                            <input class="form-check-input notif-toggle"
                                                   type="checkbox"
                                                   name="notif_<?= $mod['key'] ?>"
                                                   id="notif_<?= $mod['key'] ?>"
                                                   value="1"
                                                   <?= $isEnabled ? 'checked' : '' ?> />
                                            <label class="form-check-label" for="notif_<?= $mod['key'] ?>"></label>
                                        </div>
                                        <!--end::Toggle-->
                                    
                                    </div>
                                    <!--end::Notification Item-->
                                    <?php endforeach; ?>
                    
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-between align-items-center mt-8">
                                        <div class="d-flex gap-3">
                                            <button type="button" class="btn btn-sm btn-light-primary" id="btn_notif_enable_all">
                                                Enable All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-danger" id="btn_notif_disable_all">
                                                Disable All
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="btn_save_notifications">
                                            <span class="indicator-label">
                                                <i class="ki-duotone ki-check fs-4 me-1">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                                Save Preferences
                                            </span>
                                            <span class="indicator-progress">
                                                Saving...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                    <!--end::Actions-->
                    
                                </form>
                            </div>
                        </div>
                        <!--end::Card - Email Notifications-->
                    
                        <?php endif; // isOwnProfile ?>
                    
                    </div>
                    <!--end:::Tab pane-->
					<!--begin:::Tab pane-->
					<div class="tab-pane fade <?php if(session()->get('activeTab') == 'log'){echo 'show active';} ?>" id="kt_user_view_overview_events_and_logs_tab" role="tabpanel">
						
						<!--begin::Card - Login Sessions-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <div class="card-header border-0 flex-column flex-md-row gap-3 pb-4">

                                <!--begin::Title row-->
                                <div class="card-title">
                                    <h2 class="mb-0">Login Sessions</h2>
                                </div>
                                <!--end::Title row-->
                        
                                <!--begin::Toolbar row — search + filter + button all in one line-->
                                <div class="d-flex align-items-center flex-wrap gap-3 ms-md-auto">
                        
                                    <!--begin::Search-->
                                    <div class="d-flex align-items-center position-relative">
                                        <i class="ki-duotone ki-magnifier fs-4 position-absolute ms-3">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <input type="text"
                                               id="session_search"
                                               class="form-control form-control-sm form-control-solid ps-10 w-180px"
                                               placeholder="Search sessions..." />
                                    </div>
                                    <!--end::Search-->
                        
                                    <!--begin::Status filter-->
                                    <select id="session_status_filter"
                                            class="form-select form-select-sm form-select-solid w-130px">
                                        <option value="">All Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Signed Out">Signed Out</option>
                                        <option value="Expired">Expired</option>
                                    </select>
                                    <!--end::Status filter-->
                        
                                    <!--begin::Sign out all button-->
                                    <?php if ($canEditUser): ?>
                                    <button type="button"
                                            class="btn btn-sm btn-light-danger"
                                            onclick="signOutAllSessions(<?= $user['user_id'] ?>)">
                                        <i class="ki-duotone ki-entrance-right fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Sign Out All
                                    </button>
                                    <?php endif; ?>
                                    <!--end::Sign out all button-->
                        
                                </div>
                                <!--end::Toolbar row-->
                        
                            </div>

                            <div class="card-body pt-0 pb-5">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed gy-4 dataTable"
                                           id="sessions_datatable">
                                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-150px">Location</th>
                                                <th class="min-w-120px">Device</th>
                                                <th class="min-w-100px">IP Address</th>
                                                <th class="min-w-125px">Time</th>
                                                <th class="min-w-80px">Status</th>
                                                <th class="min-w-70px text-end pe-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            <?php if (!empty($sessions)): ?>
                                                <?php foreach ($sessions as $s):
                                                    $isCurrentSession = ($s['session_token'] === ($currentSessionToken ?? ''));
                                                    $isActive         = ($s['session_status'] === 'Active');
                                                    $timeAgo          = time() - $s['last_active'];
                        
                                                    if ($timeAgo < 60)         $timeLabel = 'Just now';
                                                    elseif ($timeAgo < 3600)   $timeLabel = floor($timeAgo / 60) . ' min ago';
                                                    elseif ($timeAgo < 86400)  $timeLabel = floor($timeAgo / 3600) . ' hours ago';
                                                    elseif ($timeAgo < 604800) $timeLabel = floor($timeAgo / 86400) . ' days ago';
                                                    else                       $timeLabel = date('d M Y', $s['login_time']);
                                                ?>
                                                <tr id="session_row_<?= $s['session_id'] ?>"
                                                    data-status="<?= esc($s['session_status']) ?>">
                        
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="ki-duotone ki-geolocation fs-3 text-muted">
                                                                <span class="path1"></span><span class="path2"></span>
                                                            </i>
                                                            <div>
                                                                <div class="fw-bold text-gray-800 fs-7">
                                                                    <?= esc($s['country'] ?? 'Unknown') ?>
                                                                </div>
                                                                <div class="text-muted fs-8"><?= esc($s['city'] ?? '') ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                        
                                                    <td>
                                                        <div class="fw-bold text-gray-800 fs-7">
                                                            <?= esc($s['browser'] ?? 'Unknown') ?>
                                                        </div>
                                                        <div class="text-muted fs-8">
                                                            <?= esc($s['device_os'] ?? '') ?>
                                                            <?php if (!empty($s['device_type'])): ?>
                                                                &mdash; <?= esc($s['device_type']) ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                        
                                                    <td class="text-gray-600 fs-7"><?= esc($s['ip_address'] ?? '—') ?></td>
                                                    <td class="text-gray-600 fs-7"><?= $timeLabel ?></td>
                        
                                                    <td>
                                                        <?php if ($isCurrentSession): ?>
                                                            <span class="badge badge-light-success">Current</span>
                                                        <?php elseif ($s['session_status'] === 'Active'): ?>
                                                            <span class="badge badge-light-primary">Active</span>
                                                        <?php elseif ($s['session_status'] === 'Signed Out'): ?>
                                                            <span class="badge badge-light-danger">Signed Out</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-light-warning">Expired</span>
                                                        <?php endif; ?>
                                                    </td>
                        
                                                    <td class="text-end pe-3">
                                                        <?php if ($isCurrentSession): ?>
                                                            <span class="badge badge-light-success fs-8">You</span>
                                                        <?php elseif ($isActive && $canEditUser): ?>
                                                            <a href="#"
                                                               class="btn btn-sm btn-light-danger py-1 px-3 fs-8"
                                                               onclick="signOutSession(<?= $s['session_id'] ?>, this); return false;">
                                                                Sign out
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted fs-8">—</span>
                                                        <?php endif; ?>
                                                    </td>
                        
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--end::Card - Login Sessions-->
                        
                        <script>
                        function signOutSession(sessionId, link) {
                            Swal.fire({
                                title: 'Sign Out Session?',
                                text: 'This will immediately end that login session.',
                                icon: 'warning',
                                showCancelButton: true,
                                buttonsStyling: false,
                                confirmButtonText: 'Yes, sign out',
                                cancelButtonText: 'Cancel',
                                customClass: {
                                    confirmButton: 'btn btn-danger',
                                    cancelButton: 'btn btn-light ms-2'
                                }
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '<?= base_url('user/signOutSession') ?>/' + sessionId,
                                        type: 'POST',
                                        data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                                        success: function(response) {
                                            if (response.success) {
                                                // Update row UI without reload
                                                const row = document.getElementById('session_row_' + sessionId);
                                                if (row) {
                                                    // Update status badge
                                                    row.querySelector('td:nth-child(5)').innerHTML =
                                                        '<span class="badge badge-light-danger">Signed Out</span>';
                                                    // Remove action link
                                                    row.querySelector('td:nth-child(6)').innerHTML =
                                                        '<span class="text-muted fs-8">—</span>';
                                                }
                                                Swal.fire({
                                                    title: 'Session Ended',
                                                    text: response.message,
                                                    icon: 'success',
                                                    buttonsStyling: false,
                                                    confirmButtonText: 'OK',
                                                    customClass: { confirmButton: 'btn btn-success' }
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: 'Failed',
                                                    text: response.message,
                                                    icon: 'error',
                                                    buttonsStyling: false,
                                                    confirmButtonText: 'Close',
                                                    customClass: { confirmButton: 'btn btn-danger' }
                                                });
                                            }
                                        },
                                        error: function() {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'An error occurred. Please try again.',
                                                icon: 'error',
                                                buttonsStyling: false,
                                                confirmButtonText: 'Close',
                                                customClass: { confirmButton: 'btn btn-danger' }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                        
                        function signOutAllSessions(userId) {
                            Swal.fire({
                                title: 'Sign Out All Sessions?',
                                text: 'This will end all active sessions for this user except the current one.',
                                icon: 'warning',
                                showCancelButton: true,
                                buttonsStyling: false,
                                confirmButtonText: 'Yes, sign out all',
                                cancelButtonText: 'Cancel',
                                customClass: {
                                    confirmButton: 'btn btn-danger',
                                    cancelButton: 'btn btn-light ms-2'
                                }
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '<?= base_url('user/signOutAllSessions') ?>/' + userId,
                                        type: 'POST',
                                        data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                                        success: function(response) {
                                            if (response.success) {
                                                Swal.fire({
                                                    title: 'Done',
                                                    text: response.message,
                                                    icon: 'success',
                                                    buttonsStyling: false,
                                                    confirmButtonText: 'OK',
                                                    customClass: { confirmButton: 'btn btn-success' }
                                                }).then(function() {
                                                    location.reload();
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: 'Failed',
                                                    text: response.message,
                                                    icon: 'error',
                                                    buttonsStyling: false,
                                                    confirmButtonText: 'Close',
                                                    customClass: { confirmButton: 'btn btn-danger' }
                                                });
                                            }
                                        },
                                        error: function() {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'An error occurred. Please try again.',
                                                icon: 'error',
                                                buttonsStyling: false,
                                                confirmButtonText: 'Close',
                                                customClass: { confirmButton: 'btn btn-danger' }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                        </script>
						
						<!--begin::Card - Logs-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2>Activity Logs</h2>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#" class="btn btn-sm btn-light-primary" id="btn_download_logs">
                                        <i class="ki-duotone ki-cloud-download fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Download Report
                                    </a>
                                </div>
                            </div>
                            <!--end::Card header-->
                        
                            <!--begin::Card body-->
                            <div class="card-body py-4">
                        
                                <!--begin::Filters-->
                                <div class="row g-3 mb-5">
                                    <!--begin::Search-->
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text border-0 bg-light">
                                                <i class="ki-duotone ki-magnifier fs-4">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                            <input type="text"
                                                   class="form-control form-control-sm bg-light border-0"
                                                   id="log_search"
                                                   placeholder="Search logs..." />
                                        </div>
                                    </div>
                                    <!--end::Search-->
                        
                                    <!--begin::Date From-->
                                    <div class="col-lg-2 col-md-6">
                                        <input type="date"
                                               class="form-control form-control-sm bg-light border-0"
                                               id="log_date_from"
                                               placeholder="Date from" />
                                    </div>
                                    <!--end::Date From-->
                        
                                    <!--begin::Date To-->
                                    <div class="col-lg-2 col-md-6">
                                        <input type="date"
                                               class="form-control form-control-sm bg-light border-0"
                                               id="log_date_to"
                                               placeholder="Date to" />
                                    </div>
                                    <!--end::Date To-->
                        
                                    <!--begin::Theme Filter-->
                                    <div class="col-lg-2 col-md-6">
                                        <select class="form-select form-select-sm bg-light border-0" id="log_theme">
                                            <option value="">All Types</option>
                                            <option value="primary">Login</option>
                                            <option value="success">Success</option>
                                            <option value="warning">Warning</option>
                                            <option value="danger">Danger</option>
                                            <option value="info">Info</option>
                                        </select>
                                    </div>
                                    <!--end::Theme Filter-->
                        
                                    <!--begin::Per Page-->
                                    <div class="col-lg-1 col-md-6">
                                        <select class="form-select form-select-sm bg-light border-0" id="log_per_page">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <!--end::Per Page-->
                        
                                    <!--begin::Search Button-->
                                    <div class="col-lg-1 col-md-6">
                                        <button type="button" class="btn btn-sm btn-primary w-100" id="btn_filter_logs">
                                            Filter
                                        </button>
                                    </div>
                                    <!--end::Search Button-->
                                </div>
                                <!--end::Filters-->
                        
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fw-semibold text-gray-600 fs-6 gy-4">
                                        <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-40px"></th>
                                                <th class="min-w-200px">Activity</th>
                                                <th class="min-w-120px">Device / IP</th>
                                                <th class="pe-0 text-end min-w-130px">Date &amp; Time</th>
                                            </tr>
                                        </thead>
                                        <tbody id="log_table_body" class="fs-6 fw-semibold text-gray-600">
                                            <tr>
                                                <td colspan="4" class="text-center py-10">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                                    <span class="ms-2 text-muted fs-7">Loading logs...</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table wrapper-->
                        
                                <!--begin::Pagination-->
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-5" id="log_pagination_wrapper">
                                    <div class="text-muted fs-7" id="log_pagination_info"></div>
                                    <div class="d-flex gap-2" id="log_pagination_btns"></div>
                                </div>
                                <!--end::Pagination-->
                        
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card - Logs-->
                        
                        <script>
                        (function() {
                            const userId   = <?= $user['user_id'] ?>;
                            const baseUrl  = '<?= base_url() ?>';
                            let currentPage = 1;
                        
                            function getFilters() {
                                return {
                                    search:   document.getElementById('log_search').value.trim(),
                                    dateFrom: document.getElementById('log_date_from').value,
                                    dateTo:   document.getElementById('log_date_to').value,
                                    theme:    document.getElementById('log_theme').value,
                                    perPage:  document.getElementById('log_per_page').value,
                                };
                            }
                        
                            function loadLogs(page) {
                                currentPage = page || 1;
                                const filters = getFilters();
                        
                                document.getElementById('log_table_body').innerHTML = `
                                    <tr>
                                        <td colspan="4" class="text-center py-10">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                            <span class="ms-2 text-muted fs-7">Loading...</span>
                                        </td>
                                    </tr>`;
                        
                                $.ajax({
                                    url: baseUrl + 'user/getUserLogs/' + userId,
                                    type: 'GET',
                                    data: {
                                        page:     currentPage,
                                        perPage:  filters.perPage,
                                        search:   filters.search,
                                        dateFrom: filters.dateFrom,
                                        dateTo:   filters.dateTo,
                                        theme:    filters.theme,
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            document.getElementById('log_table_body').innerHTML = response.html;
                                            renderPagination(response.pagination);
                                        }
                                    },
                                    error: function() {
                                        document.getElementById('log_table_body').innerHTML = `
                                            <tr>
                                                <td colspan="4" class="text-center text-danger py-10">
                                                    Failed to load logs. Please try again.
                                                </td>
                                            </tr>`;
                                    }
                                });
                            }
                        
                            function renderPagination(p) {
                                const info = document.getElementById('log_pagination_info');
                                const btns = document.getElementById('log_pagination_btns');
                        
                                if (p.total === 0) {
                                    info.innerHTML = '';
                                    btns.innerHTML = '';
                                    return;
                                }
                        
                                info.innerHTML = `Showing <strong>${p.from}</strong> to <strong>${p.to}</strong> of <strong>${p.total}</strong> logs`;
                        
                                let html = '';
                        
                                // Previous
                                html += `<button class="btn btn-sm btn-light ${p.currentPage <= 1 ? 'disabled' : ''}"
                                                 onclick="window._loadLogs(${p.currentPage - 1})">
                                            <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>
                                         </button>`;
                        
                                // Page numbers — show max 5 around current
                                const start = Math.max(1, p.currentPage - 2);
                                const end   = Math.min(p.totalPages, p.currentPage + 2);
                        
                                if (start > 1) {
                                    html += `<button class="btn btn-sm btn-light" onclick="window._loadLogs(1)">1</button>`;
                                    if (start > 2) html += `<span class="btn btn-sm btn-light disabled">...</span>`;
                                }
                        
                                for (let i = start; i <= end; i++) {
                                    html += `<button class="btn btn-sm ${i === p.currentPage ? 'btn-primary' : 'btn-light'}"
                                                     onclick="window._loadLogs(${i})">${i}</button>`;
                                }
                        
                                if (end < p.totalPages) {
                                    if (end < p.totalPages - 1) html += `<span class="btn btn-sm btn-light disabled">...</span>`;
                                    html += `<button class="btn btn-sm btn-light" onclick="window._loadLogs(${p.totalPages})">${p.totalPages}</button>`;
                                }
                        
                                // Next
                                html += `<button class="btn btn-sm btn-light ${p.currentPage >= p.totalPages ? 'disabled' : ''}"
                                                 onclick="window._loadLogs(${p.currentPage + 1})">
                                            <i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>
                                         </button>`;
                        
                                btns.innerHTML = html;
                            }
                        
                            // Expose loadLogs globally for pagination button onclick
                            window._loadLogs = loadLogs;
                        
                            // Filter button
                            document.getElementById('btn_filter_logs').addEventListener('click', function() {
                                loadLogs(1);
                            });
                        
                            // Search on Enter key
                            document.getElementById('log_search').addEventListener('keypress', function(e) {
                                if (e.key === 'Enter') loadLogs(1);
                            });
                        
                            // Per page change
                            document.getElementById('log_per_page').addEventListener('change', function() {
                                loadLogs(1);
                            });
                        
                            // Download button
                            document.getElementById('btn_download_logs').addEventListener('click', function(e) {
                                e.preventDefault();
                                const filters = getFilters();
                                const params  = new URLSearchParams({
                                    search:   filters.search,
                                    dateFrom: filters.dateFrom,
                                    dateTo:   filters.dateTo,
                                    theme:    filters.theme,
                                });
                                window.location.href = baseUrl + 'user/downloadUserLogs/' + userId + '?' + params.toString();
                            });
                        
                            // Load on page init
                            loadLogs(1);
                        })();
                        </script>
						
						
					</div>
					<!--end:::Tab pane-->
				</div>
				<!--end:::Tab content-->
			</div>
			<!--end::Content-->
			
		</div>
		<!--end::Layout-->
	</div>
</div>
<!--end::Content-->

<style>
.symbol-label {
    font-size: 2rem;
}

/* Only rotate the arrow icon, not the text */
.rotate-arrow {
    display: inline-block;
    transition: transform 0.3s ease;
}

/* When collapsed, rotate arrow to point right */
.collapsible.collapsed .rotate-arrow {
    transform: rotate(-90deg);
}

/* When expanded, keep arrow pointing down (no transform needed) */
.collapsible:not(.collapsed) .rotate-arrow {
    transform: rotate(0deg);
}

/* Make the button area interactive */
.collapsible {
    cursor: pointer;
    user-select: none;
}

.collapsible:hover {
    color: #009ef7;
}
</style>

<!--begin::Modal - Link Child-->
<div class="modal fade" id="modal_link_child" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Link a Child</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-8 py-6">
                <input type="hidden" id="link_parent_id" value="<?= $user['user_id'] ?>" />
                <div class="mb-5">
                    <label class="form-label required fw-semibold">Student Name</label>
                    <select id="link_student_select" class="form-select" style="width:100%">
                        <option value="">Search student...</option>
                    </select>
                    <div class="form-text text-muted">Type a name to search for student accounts.</div>
                </div>
                <div class="mb-5">
                    <label class="form-label fw-semibold">Relationship</label>
                    <select id="link_relationship" class="form-select">
                        <option value="Parent">Parent</option>
                        <option value="Mother">Mother</option>
                        <option value="Father">Father</option>
                        <option value="Guardian">Guardian</option>
                        <option value="Step-Parent">Step-Parent</option>
                        <option value="Grandparent">Grandparent</option>
                        <option value="Sibling">Sibling</option>
                        <option value="Uncle/Aunt">Uncle/Aunt</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn_save_link_child" class="btn btn-primary">
                    <span class="indicator-label">Link Child</span>
                    <span class="indicator-progress">Saving...<span class="spinner-border spinner-border-sm ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal - Link Child-->

<!--begin::Modal - Add/Edit Next of Kin-->
<div class="modal fade" id="kt_modal_add_next_of_kin" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <!--begin::Modal title-->
                <h2 class="fw-bold" id="next_of_kin_modal_title">Add Next of Kin</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_next_of_kin_form" class="form" action="#">
                    <!--begin::Hidden fields-->
                    <input type="hidden" name="next_of_kin_id" id="next_of_kin_id" value="" />
                    <input type="hidden" name="user_id_fk" id="user_id_fk" value="<?= $userID ?? '' ?>" />
                    <!--end::Hidden fields-->
                    
                    <!--begin::Row-->
                    <div class="row g-5 mb-7">
                        <!--begin::Full Name-->
                        <div class="col-md-6">
                            <div class="fv-row">
                                <label class="required fs-6 fw-semibold form-label mb-2">Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter full name" name="next_of_kin_name" id="next_of_kin_name" />
                            </div>
                        </div>
                        <!--end::Full Name-->
                        
                        <!--begin::Relationship-->
                        <div class="col-md-6">
                            <div class="fv-row">
                                <label class="required fs-6 fw-semibold form-label mb-2">Relationship</label>
                                <select class="form-select " name="next_of_kin_relationship" id="next_of_kin_relationship">
                                    <option value="">Select relationship...</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Father">Father</option>
                                    <option value="Guardian">Guardian</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Aunt">Aunt</option>
                                    <option value="Uncle">Uncle</option>
                                    <option value="Sister">Sister</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    <!--begin::Row-->
                    <div class="row g-5 mb-7">
                        <!--begin::Phone-->
                        <div class="col-md-6">
                            <div class="fv-row">
                                <label class="required fs-6 fw-semibold form-label mb-2">Phone Number</label>
                                <input type="text" class="form-control" placeholder="1234567" name="next_of_kin_phone" id="next_of_kin_phone" maxlength="7" />
                                <div class="form-text">7-digit phone number</div>
                            </div>
                        </div>
                        <!--end::Phone-->
                        
                        <!--begin::Email-->
                        <div class="col-md-6">
                            <div class="fv-row">
                                <label class="fs-6 fw-semibold form-label mb-2">Email Address</label>
                                <input type="email" class="form-control" placeholder="email@example.com" name="next_of_kin_email" id="next_of_kin_email" />
                                <div class="form-text">Optional</div>
                            </div>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    <!--begin::Address-->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold form-label mb-2">Physical Address</label>
                        <textarea class="form-control" rows="3" placeholder="Enter physical address" name="next_of_kin_address" id="next_of_kin_address"></textarea>
                        <div class="form-text">Optional</div>
                    </div>
                    <!--end::Address-->
                    
                    <!--begin::Separator-->
                    <div class="separator separator-dashed my-7"></div>
                    <!--end::Separator-->
                    
                    <!--begin::Checkboxes-->
                    <div class="row g-5 mb-7">
                        <div class="col-md-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_primary_contact" id="is_primary_contact" value="1" />
                                <label class="form-check-label fw-semibold" for="is_primary_contact">
                                    Primary Contact
                                </label>
                            </div>
                            <div class="form-text">Main person to contact</div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_emergency_contact" id="is_emergency_contact" value="1" />
                                <label class="form-check-label fw-semibold" for="is_emergency_contact">
                                    Emergency Contact
                                </label>
                            </div>
                            <div class="form-text">Call in emergencies</div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="authorized_pickup" id="authorized_pickup" value="1" />
                                <label class="form-check-label fw-semibold" for="authorized_pickup">
                                    Authorized Pickup
                                </label>
                            </div>
                            <div class="form-text">Can pick up student</div>
                        </div>
                    </div>
                    <!--end::Checkboxes-->
                    
                    <!--begin::Actions-->
                    <div class="text-center pt-5">
                        <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">Save</span>
                            <span class="indicator-progress">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add/Edit Next of Kin-->

<!--begin::Modal - Update email-->
<div class="modal fade" id="kt_modal_update_email" tabindex="-1" aria-hidden="true">
	<!--begin::Modal dialog-->
	<div class="modal-dialog modal-dialog-centered mw-650px">
		<!--begin::Modal content-->
		<div class="modal-content">
			<!--begin::Modal header-->
			<div class="modal-header">
				<!--begin::Modal title-->
				<h2 class="fw-bold">Update Email Address</h2>
				<!--end::Modal title-->
				<!--begin::Close-->
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
				<!--end::Close-->
			</div>
			<!--end::Modal header-->
			<!--begin::Modal body-->
			<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
				<!--begin::Form-->
				<form id="kt_modal_update_email_form" class="form" action="#">
					<!--begin::Notice-->
					<!--begin::Notice-->
					<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
						<!--begin::Icon-->
						<i class="ki-duotone ki-information fs-2tx text-primary me-4">
							<span class="path1"></span>
							<span class="path2"></span>
							<span class="path3"></span>
						</i>
						<!--end::Icon-->
						<!--begin::Wrapper-->
						<div class="d-flex flex-stack flex-grow-1">
							<!--begin::Content-->
							<div class="fw-semibold">
								<div class="fs-6 text-gray-700">Please note that a valid email address is required to complete the email verification.</div>
							</div>
							<!--end::Content-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Notice-->
					<!--end::Notice-->
					<!--begin::Input group-->
					<div class="fv-row mb-7">
						<!--begin::Label-->
						<label class="fs-6 fw-semibold form-label mb-2">
							<span class="required">Email Address</span>
						</label>
						<!--end::Label-->
						<!--begin::Input-->
						<input type="hidden" value="<?php echo $user['user_id']; ?>" name="user_id">
						<input class="form-control form-control-solid" placeholder="" name="new_email" value="<?php echo $user['email']; ?>" />
						<!--end::Input-->
					</div>
					<!--end::Input group-->
					<!--begin::Actions-->
					<div class="text-center pt-15">
						<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
						<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
							<span class="indicator-label">Submit</span>
							<span class="indicator-progress">Please wait... 
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
					<!--end::Actions-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Modal body-->
		</div>
		<!--end::Modal content-->
	</div>
	<!--end::Modal dialog-->
</div>
<!--end::Modal - Update email-->

<!--begin::Modal - Update password-->
<div class="modal fade" id="kt_modal_update_password" tabindex="-1" aria-hidden="true">
	<!--begin::Modal dialog-->
	<div class="modal-dialog modal-dialog-centered mw-650px">
		<!--begin::Modal content-->
		<div class="modal-content">
			<!--begin::Modal header-->
			<div class="modal-header">
				<!--begin::Modal title-->
				<h2 class="fw-bold">Update Password</h2>
				<!--end::Modal title-->
				<!--begin::Close-->
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
				<!--end::Close-->
			</div>
			<!--end::Modal header-->
			<!--begin::Modal body-->
			<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
				<!--begin::Form-->
				<form id="kt_modal_update_password_form" class="form" action="#">
					<!--begin::Input group=-->
					<div class="fv-row mb-10">
						<label class="required form-label fs-6 mb-2">Current Password</label>
						<input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
						<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="current_password" autocomplete="off" />
					</div>
					<!--end::Input group=-->
					<!--begin::Input group-->
					<div class="mb-10 fv-row" data-kt-password-meter="true">
						<!--begin::Wrapper-->
						<div class="mb-1">
							<!--begin::Label-->
							<label class="form-label fw-semibold fs-6 mb-2">New Password</label>
							<!--end::Label-->
							<!--begin::Input wrapper-->
							<div class="position-relative mb-3">
								<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="new_password" autocomplete="off" />
								<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
									<i class="ki-duotone ki-eye-slash fs-1">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
									<i class="ki-duotone ki-eye d-none fs-1">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
							</div>
							<!--end::Input wrapper-->
							<!--begin::Meter-->
							<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
							</div>
							<!--end::Meter-->
						</div>
						<!--end::Wrapper-->
						<!--begin::Hint-->
						<div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
						<!--end::Hint-->
					</div>
					<!--end::Input group=-->
					<!--begin::Input group=-->
					<div class="fv-row mb-10">
						<label class="form-label fw-semibold fs-6 mb-2">Confirm New Password</label>
						<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="confirm_new_password" autocomplete="off" />
					</div>
					<!--end::Input group=-->
					<!--begin::Actions-->
					<div class="text-center pt-15">
						<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
						<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
							<span class="indicator-label">Submit</span>
							<span class="indicator-progress">Please wait... 
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
					<!--end::Actions-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Modal body-->
		</div>
		<!--end::Modal content-->
	</div>
	<!--end::Modal dialog-->
</div>
<!--end::Modal - Update password-->

<!--begin::Modal - Update role-->
<div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
	<!--begin::Modal dialog-->
	<div class="modal-dialog modal-dialog-centered mw-650px">
		<!--begin::Modal content-->
		<div class="modal-content">
			<!--begin::Modal header-->
			<div class="modal-header">
				<!--begin::Modal title-->
				<h2 class="fw-bold">Update User Role</h2>
				<!--end::Modal title-->
				<!--begin::Close-->
				<div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
					<i class="ki-duotone ki-cross fs-1">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
				<!--end::Close-->
			</div>
			<!--end::Modal header-->
			<!--begin::Modal body-->
			<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
				<!--begin::Form-->
				<form id="kt_modal_update_role_form" class="form" action="#">
					<!--begin::Notice-->
					<!--begin::Notice-->
					<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
						<!--begin::Icon-->
						<i class="ki-duotone ki-information fs-2tx text-primary me-4">
							<span class="path1"></span>
							<span class="path2"></span>
							<span class="path3"></span>
						</i>
						<!--end::Icon-->
						<!--begin::Wrapper-->
						<div class="d-flex flex-stack flex-grow-1">
							<!--begin::Content-->
							<div class="fw-semibold">
								<div class="fs-6 text-gray-700">Use this feature only if you intend to create a new role for the user. If you want to update the current active role please go to <a href="<?php echo base_url('user/edit/'.$user['user_id']); ?>">Edit User</a>. Please note that reducing a user role rank, that user will lose all priviledges that was assigned to the previous role.</div>
							</div>
							<!--end::Content-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Notice-->
					
					<div class="fv-row mb-7">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mb-5">
                        <span class="required">Select a user role</span>
                    </label>
                    <!--end::Label-->
                    
                    <!--begin::Hidden Fields-->
                    <input type="hidden" name="user_id" value="<?= esc($user['user_id'] ?? '') ?>" class="form-control">
                    <input type="hidden" name="role_rank" value="<?= esc($role['role_rank'] ?? '0') ?>" class="form-control">
                    <input type="hidden" name="user_role_id" value="<?= esc($role['user_role_id'] ?? '') ?>" class="form-control">
                    <!--end::Hidden Fields-->
                    
                    <?php 
                    $i = 1;
                    $total = count($roles);
                    
                    // ✅ CRITICAL FIX: Extract current role ID safely
                    $currentRoleId = isset($role['role_id']) ? $role['role_id'] : null;
                    
                    foreach($roles as $row): 
                        // ✅ CRITICAL FIX: Safe comparison with null checks
                        if ($currentRoleId && isset($row['role_id']) && $currentRoleId == $row['role_id']) {
                            $checked = 'checked="checked"';
                        } else {
                            $checked = '';
                        }
                    ?>
                        <!--begin::Input row-->
                        <div class="d-flex mb-5">
                            <!--begin::Radio-->
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input me-3" 
                                       type="radio" 
                                       name="role_id" 
                                       value="<?= esc($row['role_id'] ?? '') ?>" 
                                       id="role_<?= esc($row['role_id'] ?? '') ?>"
                                       <?= $checked ?>>
                                
                                <label class="form-check-label" for="role_<?= esc($row['role_id'] ?? '') ?>">
                                    <div class="fw-bold text-gray-800">
                                        <?= esc($row['role_name'] ?? 'Unknown Role') ?>
                                    </div>
                                    <?php if (!empty($row['role_desc'])): ?>
                                        <div class="text-gray-600">
                                            <?= esc($row['role_desc']) ?>
                                        </div>
                                    <?php endif; ?>
                                </label>
                            </div>
                            <!--end::Radio-->
                        </div>
                        <!--end::Input row-->
                        
                        <?php if ($i < $total): ?>
                            <div class='separator separator-dashed my-5'></div>
                        <?php endif; ?>
                        
                    <?php 
                        $i++;
                    endforeach; 
                    ?>
                </div>
					
					<!--begin::Actions-->
					<div class="text-center pt-15">
						<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
						<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
							<span class="indicator-label">Submit</span>
							<span class="indicator-progress">Please wait... 
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
					<!--end::Actions-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Modal body-->
		</div>
		<!--end::Modal content-->
	</div>
	<!--end::Modal dialog-->
</div>
<!--end::Modal - Update role-->

<!--begin::Modal - Add Authenticator App-->
<div class="modal fade" id="kt_modal_add_auth_app" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">

            <div class="modal-header">
                <h2 class="fw-bold">Set Up Authenticator App</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-7 py-5">

                <!--begin::Step 1 - QR Code-->
                <div id="auth_step_1">
                    <div class="notice d-flex bg-light-primary rounded border border-primary border-dashed p-4 mb-5">
                        <i class="ki-duotone ki-information fs-2tx text-primary me-3 flex-shrink-0">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        <div class="fs-7 text-gray-700">
                            Download an authenticator app like
                            <strong>Google Authenticator</strong> or <strong>Authy</strong>,
                            then scan the QR code below.
                        </div>
                    </div>

                    <div class="text-center mb-5" id="auth_qr_container">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted fs-8 mt-2">Generating QR code...</p>
                    </div>

                    <div class="mb-5" id="auth_secret_container" style="display:none;">
                        <p class="text-muted fs-8 text-center mb-2">
                            Can't scan? Enter this key manually:
                        </p>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <code class="fs-7 fw-bold text-primary" id="auth_secret_display"></code>
                            <button type="button" class="btn btn-icon btn-xs btn-light"
                                    onclick="copySecret()" title="Copy">
                                <i class="ki-duotone ki-copy fs-5">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary btn-sm" id="btn_auth_next">
                            Next: Enter Code
                            <i class="ki-duotone ki-arrow-right fs-4 ms-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </button>
                    </div>
                </div>
                <!--end::Step 1-->

                <!--begin::Step 2 - Verify Code-->
                <div id="auth_step_2" style="display:none;">
                    <p class="text-gray-700 fs-6 mb-5">
                        Enter the 6-digit code shown in your authenticator app to confirm setup:
                    </p>

                    <div class="d-flex justify-content-center gap-3 mb-5" id="auth_otp_inputs">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text"
                               class="form-control form-control-solid text-center fw-bold fs-3 auth-digit"
                               maxlength="1"
                               style="width:48px; height:48px;"
                               inputmode="numeric"
                               pattern="[0-9]" />
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" id="auth_code_hidden" />

                    <div id="auth_verify_error" class="text-danger fs-8 text-center mb-3" style="display:none;"></div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-light btn-sm" id="btn_auth_back">
                            <i class="ki-duotone ki-arrow-left fs-4 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Back
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="btn_auth_verify">
                            <span class="indicator-label">
                                <i class="ki-duotone ki-shield-tick fs-4 me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Verify & Enable
                            </span>
                            <span class="indicator-progress">
                                Verifying...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <!--end::Step 2-->

            </div>
        </div>
    </div>
</div>
<!--end::Modal - Add Authenticator App-->

<!--begin::Modal - Enable OTP Email-->
<div class="modal fade" id="kt_modal_add_one_time_password" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <div class="modal-content">

            <div class="modal-header">
                <h2 class="fw-bold">Enable Email OTP</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-7 py-5">

                <!--begin::Step 1 - Send OTP-->
                <div id="otp_step_1">
                    <div class="notice d-flex bg-light-warning rounded border border-warning border-dashed p-4 mb-5">
                        <i class="ki-duotone ki-information fs-2tx text-warning me-3 flex-shrink-0">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        <div class="fs-7 text-gray-700">
                            A 6-digit code will be sent to your registered email address
                            each time you log in. You must enter it to complete sign in.
                        </div>
                    </div>

                    <div class="text-center">
                        <i class="ki-duotone ki-sms fs-4x text-warning mb-4 ">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <p class="text-gray-700 fw-semibold fs-6 mb-2">
                            We'll send a verification code to:
                        </p>
                        <p class="text-primary fw-bold fs-5" id="otp_email_display">
                            <?= esc($user['email'] ?? '') ?>
                        </p>
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <button type="button" class="btn btn-warning btn-sm text-white" id="btn_send_otp">
                            <span class="indicator-label">
                                <i class="ki-duotone ki-send fs-4 me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Send Verification Code
                            </span>
                            <span class="indicator-progress">
                                Sending...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <!--end::Step 1-->

                <!--begin::Step 2 - Enter OTP-->
                <div id="otp_step_2" style="display:none;">
                    <p class="text-gray-700 fs-6 mb-2">
                        Enter the 6-digit code sent to your email:
                    </p>
                    <p class="text-muted fs-8 mb-5">
                        The code expires in 10 minutes.
                    </p>

                    <div class="d-flex justify-content-center gap-3 mb-5">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text"
                               class="form-control form-control-solid text-center fw-bold fs-3 otp-setup-digit"
                               maxlength="1"
                               style="width:48px; height:48px;"
                               inputmode="numeric"
                               pattern="[0-9]" />
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" id="otp_setup_hidden" />

                    <div id="otp_verify_error" class="text-danger fs-8 text-center mb-3" style="display:none;"></div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="#" class="text-muted fs-8" id="btn_resend_setup_otp">Resend code</a>
                        <button type="button" class="btn btn-warning btn-sm text-white" id="btn_verify_otp">
                            <span class="indicator-label">
                                <i class="ki-duotone ki-shield-tick fs-4 me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Verify & Enable
                            </span>
                            <span class="indicator-progress">
                                Verifying...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <!--end::Step 2-->

            </div>
        </div>
    </div>
</div>
<!--end::Modal - Enable OTP Email-->





<script src="<?php echo base_url(); ?>app/assets/js/custom/apps/user-management/users/view/add-next-of-kin.js"></script>

<script src="<?php echo base_url(); ?>app/assets/js/custom/apps/user-management/users/view/update-user-security.js"></script>
<script src="<?php echo base_url(); ?>app/assets/js/custom/apps/user-management/users/view/add-auth-app.js"></script>
<script src="<?php echo base_url(); ?>app/assets/js/custom/apps/user-management/users/view/add-one-time-password.js"></script>


<script src="<?php echo base_url(); ?>app/assets/js/custom/apps/user-management/users/view/update-details.js"></script>

<script>
"use strict";

// ================================================================
// SESSIONS DATATABLE
// ================================================================

const sessionTable = $('#sessions_datatable').DataTable({
    pageLength:  10,
    lengthMenu:  [[10, 25, 50], [10, 25, 50]],
    order:       [[3, 'desc']],
    dom:
        '<"row align-items-center mb-4"' +
            '<"col-sm-6"l>' +
            '<"col-sm-6 d-flex justify-content-end"p>' +
        '>' +
        't' +
        '<"row align-items-center mt-4"' +
            '<"col-sm-6 text-muted fs-7"i>' +
            '<"col-sm-6 d-flex justify-content-end"p>' +
        '>',
    language: {
        lengthMenu:  'Show _MENU_ sessions',
        info:        'Showing _START_ to _END_ of _TOTAL_ sessions',
        infoEmpty:   'No sessions found',
        emptyTable:  '<div class="text-center text-muted py-8">No login sessions recorded</div>',
        paginate: {
            previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
            next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
        }
    },
    columnDefs: [
        { targets: 5, orderable: false }
    ],
    drawCallback: function() {
        $('.dataTables_paginate .paginate_button').addClass('btn btn-sm btn-light me-1');
        $('.dataTables_paginate .paginate_button.current').removeClass('btn-light').addClass('btn-primary');
    }
});

// Search
$('#session_search').on('keyup', function() {
    sessionTable.search($(this).val()).draw();
});

// Status filter
$('#session_status_filter').on('change', function() {
    sessionTable.column(4).search($(this).val()).draw();
});

// ── Sign out single session ───────────────────────────────────────
function signOutSession(sessionId, link) {
    Swal.fire({
        title: 'Sign Out Session?',
        text:  'This will immediately end that login session.',
        icon:  'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, sign out',
        cancelButtonText:  'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton:  'btn btn-light',
        },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;

        $.ajax({
            url:  '<?= base_url('user/signOutSession') ?>/' + sessionId,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    const row = document.getElementById('session_row_' + sessionId);
                    if (row) {
                        row.querySelector('td:nth-child(5)').innerHTML =
                            '<span class="badge badge-light-danger">Signed Out</span>';
                        row.querySelector('td:nth-child(6)').innerHTML =
                            '<span class="text-muted fs-8">—</span>';
                        row.setAttribute('data-status', 'Signed Out');
                    }
                    Swal.fire({
                        title: 'Session Ended',
                        text:  response.message,
                        icon:  'success',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                } else {
                    Swal.fire({
                        title:             'Failed',
                        text:              response.message,
                        icon:              'error',
                        buttonsStyling:    false,
                        confirmButtonText: 'Close',
                        customClass:       { confirmButton: 'btn btn-danger' }
                    });
                }
            }
        });
    });
}

// ── Sign out all sessions ─────────────────────────────────────────
function signOutAllSessions(userId) {
    Swal.fire({
        title: 'Sign Out All Sessions?',
        text:  'This will end all active sessions for this user except the current one.',
        icon:  'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, sign out all',
        cancelButtonText:  'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton:  'btn btn-light',
        },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url:  '<?= base_url('user/signOutAllSessions') ?>/' + userId,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title:             'Done',
                        text:              response.message,
                        icon:              'success',
                        buttonsStyling:    false,
                        confirmButtonText: 'OK',
                        customClass:       { confirmButton: 'btn btn-success' }
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        title:             'Failed',
                        text:              response.message,
                        icon:              'error',
                        buttonsStyling:    false,
                        confirmButtonText: 'Close',
                        customClass:       { confirmButton: 'btn btn-danger' }
                    });
                }
            }
        });
    });
}

// ================================================================
// 2FA — SHARED STATE
// ================================================================

const twofaToggle        = document.getElementById('twofa_master_toggle');
const twofaToggleLabel   = document.getElementById('twofa_toggle_label');
const twofaStatusBadge   = document.getElementById('twofa_status_badge');
const twofaMethodLabel   = document.getElementById('twofa_method_label');
const twofaMethodCards   = document.getElementById('twofa_method_cards');
const twofaDisabledState = document.getElementById('twofa_disabled_state');
const twofaInfoNote      = document.getElementById('twofa_info_note');
const twofaStatusBanner  = document.getElementById('twofa_status_banner');
const authAppIcon        = document.getElementById('auth_app_status_icon');
const otpEmailIcon       = document.getElementById('otp_email_status_icon');
const authCard           = document.getElementById('card_authenticator_app');
const otpCard            = document.getElementById('card_otp_email');

let current2FAState = { enabled: false, method: null };

// ── Apply UI state ────────────────────────────────────────────────
function apply2FAState(enabled, method) {
    current2FAState = { enabled, method };

    // ── Toggle switch ─────────────────────────────────────────────
    if (twofaToggle) twofaToggle.checked = enabled;

    if (twofaToggleLabel) {
        twofaToggleLabel.textContent = enabled ? 'Enabled' : 'Disabled';
        twofaToggleLabel.className   = enabled
            ? 'text-success fs-7 fw-bold'
            : 'text-muted fs-7 fw-semibold';
    }

    // ── Status badge ──────────────────────────────────────────────
    if (twofaStatusBadge) {
        twofaStatusBadge.className   = enabled
            ? 'badge badge-light-success fs-7 px-4 py-2'
            : 'badge badge-light-danger fs-7 px-4 py-2';
        twofaStatusBadge.textContent = enabled ? 'Enabled' : 'Not Enabled';
    }

    // ── Reset method icons and borders ────────────────────────────
    if (authAppIcon)  authAppIcon.style.display  = 'none';
    if (otpEmailIcon) otpEmailIcon.style.display = 'none';
    if (authCard)     authCard.style.borderColor  = '#E4E6EF';
    if (otpCard)      otpCard.style.borderColor   = '#E4E6EF';

    // ── Reset button labels ───────────────────────────────────────
    const authBtn = document.getElementById('btn_setup_authenticator');
    const otpBtn  = document.getElementById('btn_setup_otp');

    if (authBtn) {
        authBtn.innerHTML = '<i class="ki-duotone ki-fingerprint-scanning fs-4 me-1">' +
            '<span class="path1"></span><span class="path2"></span>' +
            '<span class="path3"></span><span class="path4"></span><span class="path5"></span>' +
            '</i> Set Up Authenticator';
        authBtn.className = authBtn.className
            .replace('btn-light-primary', 'btn-primary');
    }
    if (otpBtn) {
        otpBtn.innerHTML = '<i class="ki-duotone ki-sms fs-4 me-1">' +
            '<span class="path1"></span><span class="path2"></span>' +
            '</i> Enable Email OTP';
    }

    if (enabled) {
        // Show method cards + info note + status banner
        if (twofaMethodCards)   twofaMethodCards.style.display   = '';
        if (twofaDisabledState) twofaDisabledState.style.display = 'none';
        if (twofaInfoNote)      twofaInfoNote.style.display      = '';
        if (twofaStatusBanner)  twofaStatusBanner.style.display  = '';

        // Method label
        const methodLabels = {
            'authenticator': 'via Authenticator App',
            'otp_email':     'via Email OTP',
        };
        if (twofaMethodLabel) {
            twofaMethodLabel.textContent = methodLabels[method] || '';
        }

        // Highlight active method
        if (method === 'authenticator') {
            if (authAppIcon)  authAppIcon.style.display  = 'block';
            if (authCard)     authCard.style.borderColor  = '#50cd89';
            if (authBtn) {
                authBtn.innerHTML = '<i class="ki-duotone ki-pencil fs-4 me-1">' +
                    '<span class="path1"></span><span class="path2"></span></i> Reconfigure';
                authBtn.className = authBtn.className
                    .replace('btn-primary', 'btn-light-primary');
            }
        }

        if (method === 'otp_email') {
            if (otpEmailIcon) otpEmailIcon.style.display = 'block';
            if (otpCard)      otpCard.style.borderColor   = '#50cd89';
            if (otpBtn) {
                otpBtn.innerHTML = '<i class="ki-duotone ki-pencil fs-4 me-1">' +
                    '<span class="path1"></span><span class="path2"></span></i> Reconfigure';
            }
        }

    } else {
        // Show disabled state, hide everything else
        if (twofaMethodCards)   twofaMethodCards.style.display   = 'none';
        if (twofaDisabledState) twofaDisabledState.style.display = '';
        if (twofaInfoNote)      twofaInfoNote.style.display      = 'none';
        if (twofaStatusBanner)  twofaStatusBanner.style.display  = 'none';
        if (twofaMethodLabel)   twofaMethodLabel.textContent     = '';
    }
}

// ── Load 2FA status from server ───────────────────────────────────
function load2FAStatus() {
    $.get('<?= base_url('auth/2fa/status') ?>', function(response) {
        if (response.success !== false) {
            apply2FAState(!!response.enabled, response.method || null);
        }
    });
}

<?php if ($isOwnProfile): ?>

load2FAStatus();

// ================================================================
// 2FA MASTER TOGGLE HANDLER
// ================================================================

if (twofaToggle) {
    twofaToggle.addEventListener('change', function() {
        const wantsEnabled = this.checked;

        if (wantsEnabled) {
            // ── Toggling ON — revert until user confirms method ───
            this.checked = false;

            Swal.fire({
                title: 'Enable Two-Factor Authentication',
                html: `
                    <p class="text-gray-600 fs-6 mb-5">
                        Choose your preferred verification method:
                    </p>
                    <div class="d-flex flex-column gap-3 text-start">

                        <label class="d-flex align-items-center gap-4 p-4 rounded border border-dashed
                                      border-gray-300 cursor-pointer method-choice-card"
                               for="choice_authenticator"
                               style="transition:all 0.15s ease; cursor:pointer;">
                            <input type="radio" name="twofa_method_choice"
                                   id="choice_authenticator" value="authenticator"
                                   class="form-check-input mt-0 flex-shrink-0" />
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-phone fs-2 text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold text-gray-800 fs-6">Authenticator App</div>
                                <div class="text-muted fs-8">
                                    Google Authenticator, Authy — works offline, most secure
                                </div>
                            </div>
                        </label>

                        <label class="d-flex align-items-center gap-4 p-4 rounded border border-dashed
                                      border-gray-300 cursor-pointer method-choice-card"
                               for="choice_otp_email"
                               style="transition:all 0.15s ease; cursor:pointer;">
                            <input type="radio" name="twofa_method_choice"
                                   id="choice_otp_email" value="otp_email"
                                   class="form-check-input mt-0 flex-shrink-0" />
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-warning">
                                    <i class="ki-duotone ki-sms fs-2 text-warning">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold text-gray-800 fs-6">Email OTP</div>
                                <div class="text-muted fs-8">
                                    6-digit code sent to your email address each login
                                </div>
                            </div>
                        </label>

                    </div>
                `,
                showCancelButton:  true,
                buttonsStyling:    false,
                confirmButtonText: '<i class="ki-duotone ki-arrow-right fs-5 me-1"><span class="path1"></span><span class="path2"></span></i> Continue',
                cancelButtonText:  'Cancel',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton:  'btn btn-light',
                    popup:         'w-500px',
                },
                didOpen: function() {
                    // Highlight card when radio selected
                    document.querySelectorAll('.method-choice-card').forEach(function(card) {
                        card.addEventListener('click', function() {
                            // Reset all cards
                            document.querySelectorAll('.method-choice-card').forEach(function(c) {
                                c.style.borderColor = '#E4E6EF';
                                c.style.background  = '#fff';
                            });
                            // Highlight this card
                            card.style.borderColor = '#009ef7';
                            card.style.background  = '#f1faff';
                            // Check its radio
                            const radio = card.querySelector('input[type="radio"]');
                            if (radio) radio.checked = true;
                        });
                    });
                },
                preConfirm: function() {
                    const selected = document.querySelector('input[name="twofa_method_choice"]:checked');
                    if (!selected) {
                        Swal.showValidationMessage(
                            '<i class="ki-duotone ki-information-5 fs-5 me-1 text-danger">' +
                            '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
                            '</i> Please select a verification method to continue.'
                        );
                        return false;
                    }
                    return selected.value;
                }
            }).then(function(result) {
                if (!result.isConfirmed) return; // User cancelled — toggle stays off

                const chosenMethod = result.value;

                if (chosenMethod === 'authenticator') {
                    const modal = new bootstrap.Modal(
                        document.getElementById('kt_modal_add_auth_app')
                    );
                    modal.show();
                } else if (chosenMethod === 'otp_email') {
                    const modal = new bootstrap.Modal(
                        document.getElementById('kt_modal_add_one_time_password')
                    );
                    modal.show();
                }
            });

        } else {
            // ── Toggling OFF — revert until user confirms disable ─
            this.checked = true;

            Swal.fire({
                title: 'Disable Two-Factor Authentication?',
                html:
                    '<div class="d-flex align-items-center gap-4 p-4 bg-light-danger ' +
                    'rounded border border-dashed border-danger mb-5">' +
                        '<i class="ki-duotone ki-shield-cross fs-3x text-danger flex-shrink-0">' +
                            '<span class="path1"></span><span class="path2"></span>' +
                        '</i>' +
                        '<div class="text-start">' +
                            '<div class="fw-bold text-gray-800 fs-6 mb-1">Security Warning</div>' +
                            '<div class="text-muted fs-7">Your account will only be protected ' +
                            'by your password. This reduces your account security significantly.</div>' +
                        '</div>' +
                    '</div>' +
                    '<p class="text-gray-600 fs-7 mb-0">' +
                    'Are you sure you want to disable two-factor authentication?</p>',
                icon:              'warning',
                showCancelButton:  true,
                buttonsStyling:    false,
                confirmButtonText: 'Yes, Disable',
                cancelButtonText:  'Keep Enabled',
                customClass: {
                    confirmButton: 'btn btn-danger me-3',
                    cancelButton:  'btn btn-light',
                },
                reverseButtons: true,
            }).then(function(result) {
                if (!result.isConfirmed) {
                    // User cancelled — keep toggle ON
                    if (twofaToggle) twofaToggle.checked = true;
                    return;
                }

                $.ajax({
                    url:  '<?= base_url('auth/2fa/disable') ?>',
                    type: 'POST',
                    data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                    success: function(response) {
                        if (response.success) {
                            apply2FAState(false, null);
                            Swal.fire({
                                title:             'Disabled',
                                text:              'Two-factor authentication has been disabled.',
                                icon:              'success',
                                timer:             2000,
                                showConfirmButton: false,
                            });
                        } else {
                            // Failed — revert toggle back to ON
                            if (twofaToggle) twofaToggle.checked = true;
                            Swal.fire({
                                title:             'Failed',
                                text:              response.message,
                                icon:              'error',
                                buttonsStyling:    false,
                                confirmButtonText: 'Close',
                                customClass:       { confirmButton: 'btn btn-danger' }
                            });
                        }
                    },
                    error: function() {
                        if (twofaToggle) twofaToggle.checked = true;
                        Swal.fire({
                            title:             'Error',
                            text:              'An error occurred. Please try again.',
                            icon:              'error',
                            buttonsStyling:    false,
                            confirmButtonText: 'Close',
                            customClass:       { confirmButton: 'btn btn-danger' }
                        });
                    }
                });
            });
        }
    });
}

// ── Reload 2FA status when modals close ───────────────────────────
$('#kt_modal_add_auth_app').on('hidden.bs.modal', function() {
    load2FAStatus();
});

$('#kt_modal_add_one_time_password').on('hidden.bs.modal', function() {
    load2FAStatus();
});

<?php endif; // isOwnProfile ?>

// ================================================================
// AUTHENTICATOR APP SETUP
// ================================================================

let authSecret = '';

$('#kt_modal_add_auth_app').on('shown.bs.modal', function() {
    document.getElementById('auth_step_1').style.display = 'block';
    document.getElementById('auth_step_2').style.display = 'none';
    document.getElementById('auth_qr_container').innerHTML =
        '<div class="spinner-border text-primary" role="status"></div>' +
        '<p class="text-muted fs-8 mt-2">Generating QR code...</p>';
    document.getElementById('auth_secret_container').style.display = 'none';

    $.ajax({
        url:  '<?= base_url('auth/2fa/setup-authenticator') ?>',
        type: 'POST',
        data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
        success: function(response) {
            if (response.success) {
                authSecret = response.secret;
                document.getElementById('auth_qr_container').innerHTML =
                    '<img src="data:' + response.qr_mime + ';base64,' + response.qr_image + '" ' +
                    'style="width:220px; height:220px; border-radius:8px; border:1px solid #E4E6EF;" />';
                document.getElementById('auth_secret_display').textContent = response.secret;
                document.getElementById('auth_secret_container').style.display = 'block';
            } else {
                document.getElementById('auth_qr_container').innerHTML =
                    '<p class="text-danger fs-7">' + response.message + '</p>';
            }
        },
        error: function() {
            document.getElementById('auth_qr_container').innerHTML =
                '<p class="text-danger fs-7">Failed to generate QR code. Please try again.</p>';
        }
    });
});

const authDigits = document.querySelectorAll('.auth-digit');
const authHidden = document.getElementById('auth_code_hidden');

if (authDigits.length && authHidden) {
    authDigits.forEach((input, index) => {
        input.addEventListener('keyup', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value && index < authDigits.length - 1) authDigits[index + 1].focus();
            if (e.key === 'Backspace' && !this.value && index > 0) authDigits[index - 1].focus();
            authHidden.value = Array.from(authDigits).map(d => d.value).join('');
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData)
                .getData('text').replace(/\D/g, '').slice(0, 6);
            pasted.split('').forEach((c, i) => { if (authDigits[i]) authDigits[i].value = c; });
            authHidden.value = pasted;
            if (authDigits[pasted.length - 1]) authDigits[pasted.length - 1].focus();
        });
    });

    const btnAuthNext = document.getElementById('btn_auth_next');
    const btnAuthBack = document.getElementById('btn_auth_back');
    const btnAuthVerify = document.getElementById('btn_auth_verify');

    if (btnAuthNext) {
        btnAuthNext.addEventListener('click', function() {
            document.getElementById('auth_step_1').style.display = 'none';
            document.getElementById('auth_step_2').style.display = 'block';
            authDigits[0].focus();
        });
    }

    if (btnAuthBack) {
        btnAuthBack.addEventListener('click', function() {
            document.getElementById('auth_step_2').style.display = 'none';
            document.getElementById('auth_step_1').style.display = 'block';
        });
    }

    if (btnAuthVerify) {
        btnAuthVerify.addEventListener('click', function() {
            const btn    = this;
            const code   = authHidden.value;
            const errDiv = document.getElementById('auth_verify_error');

            if (code.length !== 6) {
                if (errDiv) { errDiv.style.display = 'block'; errDiv.textContent = 'Please enter the full 6-digit code.'; }
                return;
            }

            btn.setAttribute('data-kt-indicator', 'on');
            btn.disabled = true;
            if (errDiv) errDiv.style.display = 'none';

            $.ajax({
                url:  '<?= base_url('auth/2fa/verify-authenticator') ?>',
                type: 'POST',
                data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>', code: code },
                success: function(response) {
                    btn.removeAttribute('data-kt-indicator');
                    btn.disabled = false;

                    if (response.success) {
                        $('#kt_modal_add_auth_app').modal('hide');
                        apply2FAState(true, 'authenticator');
                        Swal.fire({
                            title: 'Authenticator Enabled!',
                            html:  '<p class="text-gray-700 fs-6 mb-0">Your account is now protected by an authenticator app.</p>',
                            icon:  'success',
                            buttonsStyling:    false,
                            confirmButtonText: 'Great!',
                            customClass:       { confirmButton: 'btn btn-success' }
                        });
                    } else {
                        if (errDiv) { errDiv.style.display = 'block'; errDiv.textContent = response.message; }
                        authDigits.forEach(d => { d.value = ''; d.classList.add('is-invalid'); });
                        authDigits[0].focus();
                        authHidden.value = '';
                    }
                },
                error: function() {
                    btn.removeAttribute('data-kt-indicator');
                    btn.disabled = false;
                    Swal.fire({
                        title: 'Error', text: 'An error occurred. Please try again.',
                        icon: 'error', buttonsStyling: false, confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' }
                    });
                }
            });
        });
    }
}

function copySecret() {
    navigator.clipboard.writeText(authSecret).then(function() {
        Swal.fire({
            title: 'Copied!',
            text:  'Secret key copied to clipboard.',
            icon:  'success',
            timer: 1500,
            showConfirmButton: false,
        });
    });
}

// ================================================================
// EMAIL OTP SETUP
// ================================================================

const otpSetupDigits = document.querySelectorAll('.otp-setup-digit');
const otpSetupHidden = document.getElementById('otp_setup_hidden');

if (otpSetupDigits.length && otpSetupHidden) {
    otpSetupDigits.forEach((input, index) => {
        input.addEventListener('keyup', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value && index < otpSetupDigits.length - 1) otpSetupDigits[index + 1].focus();
            if (e.key === 'Backspace' && !this.value && index > 0) otpSetupDigits[index - 1].focus();
            otpSetupHidden.value = Array.from(otpSetupDigits).map(d => d.value).join('');
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData)
                .getData('text').replace(/\D/g, '').slice(0, 6);
            pasted.split('').forEach((c, i) => { if (otpSetupDigits[i]) otpSetupDigits[i].value = c; });
            otpSetupHidden.value = pasted;
            if (otpSetupDigits[pasted.length - 1]) otpSetupDigits[pasted.length - 1].focus();
        });
    });

    const btnSendOtp   = document.getElementById('btn_send_otp');
    const btnVerifyOtp = document.getElementById('btn_verify_otp');
    const btnResendOtp = document.getElementById('btn_resend_setup_otp');

    if (btnSendOtp) {
        btnSendOtp.addEventListener('click', function() {
            const btn = this;
            btn.setAttribute('data-kt-indicator', 'on');
            btn.disabled = true;

            $.ajax({
                url:  '<?= base_url('auth/2fa/setup-otp-email') ?>',
                type: 'POST',
                data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                success: function(response) {
                    btn.removeAttribute('data-kt-indicator');
                    btn.disabled = false;
                    if (response.success) {
                        document.getElementById('otp_step_1').style.display = 'none';
                        document.getElementById('otp_step_2').style.display = 'block';
                        otpSetupDigits[0].focus();
                    } else {
                        Swal.fire({
                            title: 'Error', text: response.message, icon: 'error',
                            buttonsStyling: false, confirmButtonText: 'Close',
                            customClass: { confirmButton: 'btn btn-danger' }
                        });
                    }
                },
                error: function() {
                    btn.removeAttribute('data-kt-indicator');
                    btn.disabled = false;
                    Swal.fire({
                        title: 'Error', text: 'Failed to send OTP. Please try again.',
                        icon: 'error', buttonsStyling: false, confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' }
                    });
                }
            });
        });
    }

    if (btnVerifyOtp) {
        btnVerifyOtp.addEventListener('click', function() {
            const btn    = this;
            const code   = otpSetupHidden.value;
            const errDiv = document.getElementById('otp_verify_error');

            if (code.length !== 6) {
                if (errDiv) { errDiv.style.display = 'block'; errDiv.textContent = 'Please enter the full 6-digit code.'; }
                return;
            }

            btn.setAttribute('data-kt-indicator', 'on');
            btn.disabled = true;
            if (errDiv) errDiv.style.display = 'none';

            $.ajax({
                url:  '<?= base_url('auth/2fa/verify-otp-email') ?>',
                type: 'POST',
                data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>', code: code },
                success: function(response) {
                    btn.removeAttribute('data-kt-indicator');
                    btn.disabled = false;

                    if (response.success) {
                        $('#kt_modal_add_one_time_password').modal('hide');
                        apply2FAState(true, 'otp_email');
                        Swal.fire({
                            title: 'Email OTP Enabled!',
                            html:  '<p class="text-gray-700 fs-6 mb-0">You will receive a 6-digit code by email each time you sign in.</p>',
                            icon:  'success',
                            buttonsStyling:    false,
                            confirmButtonText: 'Great!',
                            customClass:       { confirmButton: 'btn btn-success' }
                        });
                    } else {
                        if (errDiv) { errDiv.style.display = 'block'; errDiv.textContent = response.message; }
                        otpSetupDigits.forEach(d => { d.value = ''; d.classList.add('is-invalid'); });
                        otpSetupDigits[0].focus();
                        otpSetupHidden.value = '';
                    }
                },
                error: function() {
                    btn.removeAttribute('data-kt-indicator');
                    btn.disabled = false;
                    Swal.fire({
                        title: 'Error', text: 'An error occurred. Please try again.',
                        icon: 'error', buttonsStyling: false, confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' }
                    });
                }
            });
        });
    }

    if (btnResendOtp) {
        btnResendOtp.addEventListener('click', function(e) {
            e.preventDefault();
            $.ajax({
                url:  '<?= base_url('auth/2fa/setup-otp-email') ?>',
                type: 'POST',
                data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Code Resent!',
                            text:  response.message,
                            icon:  'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        // Clear inputs for fresh entry
                        otpSetupDigits.forEach(d => { d.value = ''; d.classList.remove('is-invalid', 'is-valid'); });
                        otpSetupHidden.value = '';
                        otpSetupDigits[0].focus();
                    } else {
                        Swal.fire({
                            title: 'Failed', text: response.message || 'Could not resend code.',
                            icon: 'error', buttonsStyling: false, confirmButtonText: 'Close',
                            customClass: { confirmButton: 'btn btn-danger' }
                        });
                    }
                }
            });
        });
    }
}

// ================================================================
// EMAIL NOTIFICATIONS
// ================================================================

<?php if ($isOwnProfile): ?>

const btnEnableAll  = document.getElementById('btn_notif_enable_all');
const btnDisableAll = document.getElementById('btn_notif_disable_all');
const notifForm     = document.getElementById('notification_form');

if (btnEnableAll) {
    btnEnableAll.addEventListener('click', function() {
        document.querySelectorAll('.notif-toggle').forEach(t => t.checked = true);
    });
}

if (btnDisableAll) {
    btnDisableAll.addEventListener('click', function() {
        document.querySelectorAll('.notif-toggle').forEach(t => t.checked = false);
    });
}

if (notifForm) {
    notifForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const btn      = document.getElementById('btn_save_notifications');
        const formData = new FormData(this);

        // Ensure unchecked toggles are submitted as 0
        document.querySelectorAll('.notif-toggle').forEach(function(t) {
            if (!t.checked) formData.set(t.name, '0');
        });

        btn.setAttribute('data-kt-indicator', 'on');
        btn.disabled = true;

        $.ajax({
            url:         '<?= base_url('user/saveNotifications') ?>',
            type:        'POST',
            data:        formData,
            processData: false,
            contentType: false,
            success: function(response) {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;

                if (response.success) {
                    Swal.fire({
                        title:             'Saved!',
                        text:              response.message,
                        icon:              'success',
                        timer:             2000,
                        showConfirmButton: false,
                    });
                } else {
                    Swal.fire({
                        title:             'Error',
                        text:              response.message,
                        icon:              'error',
                        buttonsStyling:    false,
                        confirmButtonText: 'Close',
                        customClass:       { confirmButton: 'btn btn-danger' }
                    });
                }
            },
            error: function() {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;
                Swal.fire({
                    title:             'Error',
                    text:              'An unexpected error occurred. Please try again.',
                    icon:              'error',
                    buttonsStyling:    false,
                    confirmButtonText: 'Close',
                    customClass:       { confirmButton: 'btn btn-danger' }
                });
            }
        });
    });
}

    // ================================================================
    // ADMISSION DATATABLE
    // ================================================================
    
    <?php if ($showAdmission && !empty($admissions)): ?>
    
    const admissionTable = $('#admission_datatable').DataTable({
        pageLength:  10,
        lengthMenu:  [[5, 10, 25], [5, 10, 25]],
        order:       [[1, 'desc']],
        dom:
            '<"row align-items-center mb-4"' +
                '<"col-sm-6"l>' +
                '<"col-sm-6 d-flex justify-content-end"f>' +
            '>' +
            't' +
            '<"row align-items-center mt-4"' +
                '<"col-sm-6 text-muted fs-7"i>' +
                '<"col-sm-6 d-flex justify-content-end"p>' +
            '>',
        language: {
            search:      '',
            searchPlaceholder: 'Search admissions...',
            lengthMenu:  'Show _MENU_',
            info:        'Showing _START_ to _END_ of _TOTAL_ records',
            infoEmpty:   'No records found',
            emptyTable:  '<div class="text-center text-muted py-8">No admission records</div>',
            paginate: {
                previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
                next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
            }
        },
        <?php if (!$canEditUser): ?>
        columnDefs: [{ targets: -1, orderable: false }],
        <?php else: ?>
        columnDefs: [{ targets: -1, orderable: false }],
        <?php endif; ?>
        drawCallback: function() {
            $('.dataTables_paginate .paginate_button').addClass('btn btn-sm btn-light me-1');
            $('.dataTables_paginate .paginate_button.current').removeClass('btn-light').addClass('btn-primary');
        }
    });
    
    <?php endif; ?>
    
    // ── Delete Admission ──────────────────────────────────────────────
    <?php if ($canEditUser): ?>
    $(document).on('click', '.btn-delete-admission', function() {
        const btn    = $(this);
        const id     = btn.data('id');
        const school = btn.data('school');
    
        Swal.fire({
            title: 'Delete Admission?',
            html:
                '<p class="text-gray-700 mb-3">You are about to delete the admission record for:</p>' +
                '<div class="bg-light-danger rounded p-4 mb-3">' +
                    '<div class="fw-bold text-gray-800 fs-6">' + school + '</div>' +
                '</div>' +
                '<p class="text-danger fw-semibold fs-7 mb-0">' +
                    '<i class="ki-duotone ki-information-5 fs-5 me-1 text-danger">' +
                        '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
                    '</i>' +
                    'This will also delete all related enrolment records. This action cannot be undone.' +
                '</p>',
            icon: 'warning',
            showCancelButton:  true,
            buttonsStyling:    false,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText:  'Cancel',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton:  'btn btn-light',
            },
            reverseButtons: true,
        }).then(function(result) {
            if (!result.isConfirmed) return;
    
            btn.attr('disabled', true);
            btn.html('<span class="spinner-border spinner-border-sm"></span>');
    
            $.ajax({
                url:  '<?= base_url('admission/delete') ?>/' + id,
                type: 'POST',
                data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                success: function(response) {
                    if (response.success) {
                        // Remove row from DataTable
                        if (typeof admissionTable !== 'undefined') {
                            admissionTable.row('#admission_row_' + id).remove().draw();
                        } else {
                            $('#admission_row_' + id).fadeOut(300, function() { $(this).remove(); });
                        }
    
                        Swal.fire({
                            title:             'Deleted!',
                            text:              response.message || 'Admission record deleted successfully.',
                            icon:              'success',
                            timer:             2000,
                            showConfirmButton: false,
                        });
                    } else {
                        btn.attr('disabled', false);
                        btn.html(
                            '<i class="ki-duotone ki-trash fs-4">' +
                                '<span class="path1"></span><span class="path2"></span>' +
                                '<span class="path3"></span><span class="path4"></span><span class="path5"></span>' +
                            '</i>'
                        );
                        Swal.fire({
                            title:             'Failed',
                            text:              response.message,
                            icon:              'error',
                            buttonsStyling:    false,
                            confirmButtonText: 'Close',
                            customClass:       { confirmButton: 'btn btn-danger' }
                        });
                    }
                },
                error: function() {
                    btn.attr('disabled', false);
                    btn.html(
                        '<i class="ki-duotone ki-trash fs-4">' +
                            '<span class="path1"></span><span class="path2"></span>' +
                            '<span class="path3"></span><span class="path4"></span><span class="path5"></span>' +
                        '</i>'
                    );
                    Swal.fire({
                        title:             'Error',
                        text:              'An error occurred. Please try again.',
                        icon:              'error',
                        buttonsStyling:    false,
                        confirmButtonText: 'Close',
                        customClass:       { confirmButton: 'btn btn-danger' }
                    });
                }
            });
        });
    });
    <?php endif; ?>

<?php endif; // isOwnProfile ?>

// ================================================================
// PARENT–STUDENT LINKING
// ================================================================
(function() {
    // Select2 for student search
    if ($('#link_student_select').length && typeof $.fn.select2 !== 'undefined') {
        $('#link_student_select').select2({
            dropdownParent: $('#modal_link_child'),
            placeholder: 'Search by student name...',
            minimumInputLength: 2,
            ajax: {
                url: '<?= base_url('user/searchStudents') ?>',
                dataType: 'json',
                delay: 300,
                data: function(params) { return { q: params.term }; },
                processResults: function(data) {
                    return {
                        results: (data.results || []).map(function(u) {
                            return { id: u.id, text: u.text, role: u.role };
                        })
                    };
                },
                cache: true
            },
            templateResult: function(u) {
                if (!u.id) return u.text;
                return $('<span>' + u.text + ' <small class="text-muted">(' + (u.role || '') + ')</small></span>');
            }
        });
    }

    // Save link
    $('#btn_save_link_child').on('click', function() {
        var studentId = $('#link_student_select').val();
        if (!studentId) {
            Swal.fire({ icon: 'warning', title: 'Select a student', text: 'Please search and select a student to link.', confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' } });
            return;
        }
        var $btn = $(this);
        $btn.attr('data-kt-indicator', 'on');
        $.ajax({
            url: '<?= base_url('user/link-child') ?>',
            method: 'POST',
            data: {
                parent_user_id:  $('#link_parent_id').val(),
                student_user_id: studentId,
                relationship:    $('#link_relationship').val(),
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(res) {
                $btn.removeAttr('data-kt-indicator');
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Linked!', text: res.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' } })
                        .then(function() { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
                }
            },
            error: function() {
                $btn.removeAttr('data-kt-indicator');
                Swal.fire({ icon: 'error', title: 'Error', text: 'An unexpected error occurred.', confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            }
        });
    });

    // Unlink
    $(document).on('click', '.btn-unlink-child', function() {
        var linkId  = $(this).data('link-id');
        var $card   = $(this).closest('[id^="child_card_"]');
        Swal.fire({
            icon: 'warning',
            title: 'Unlink child?',
            text: 'This will remove the parent-child link. The student account will not be deleted.',
            showCancelButton: true,
            confirmButtonText: 'Yes, unlink',
            cancelButtonText: 'Cancel',
            customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light' }
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('user/unlink-child/') ?>' + linkId,
                    method: 'POST',
                    data: { <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                    success: function(res) {
                        if (res.success) {
                            $card.fadeOut(300, function() { $(this).remove(); });
                            Swal.fire({ icon: 'success', title: 'Unlinked', text: res.message, timer: 1500, showConfirmButton: false });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
                        }
                    }
                });
            }
        });
    });
})();

// ── Tab persistence ───────────────────────────────────────────────────────────
(function() {
    const TAB_KEY = 'navuli_user_tab_<?= $user['user_id'] ?>';

    const tabMap = {
        '#kt_user_view_overview_tab':                  'overview',
        '#kt_user_view_overview_security':             'security',
        '#kt_user_view_overview_events_and_logs_tab':  'log',
    };

    // On page load: restore last active tab from sessionStorage
    const saved = sessionStorage.getItem(TAB_KEY);
    if (saved) {
        const selector = Object.keys(tabMap).find(k => tabMap[k] === saved);
        if (selector) {
            const tabEl = document.querySelector('[data-bs-toggle="tab"][href="' + selector + '"]');
            if (tabEl) bootstrap.Tab.getOrCreateInstance(tabEl).show();
        }
    }

    // On tab change: save to sessionStorage so redirects come back to the same tab
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(el) {
        el.addEventListener('shown.bs.tab', function(e) {
            const href  = e.target.getAttribute('href');
            const label = tabMap[href];
            if (label) sessionStorage.setItem(TAB_KEY, label);
        });
    });
})();

</script>

<?php if ($isOwnProfile ?? false): ?>
<!--begin::Reference Request Modal-->
<div class="modal fade" id="kt_modal_ref_request" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-550px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Request a Reference</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-10 py-8">
                <p class="text-muted fs-7 mb-7">
                    Select the school and reference type below. A staff member will review and process your request.
                    Track the status under <strong>View Generated References</strong>.
                </p>

                <?php
                $admissionsForModal = $admissionsForModal ?? [];
                $refCategories      = $refCategories ?? [];

                // Fallback: if categories still empty, hard-code the student ones
                if (empty($refCategories)) {
                    $refCategories = [
                        ['ref_cat_id' => 2, 'ref_cat_name' => 'Character Reference'],
                        ['ref_cat_id' => 3, 'ref_cat_name' => 'Recommendation Letter'],
                        ['ref_cat_id' => 4, 'ref_cat_name' => 'Transcript Request'],
                        ['ref_cat_id' => 5, 'ref_cat_name' => 'Conduct Certificate'],
                        ['ref_cat_id' => 6, 'ref_cat_name' => 'Clearance Certificate'],
                    ];
                }
                ?>

                <!--begin::Hidden student ID-->
                <input type="hidden" id="sdRefStudentId" value="<?= (int) $user['user_id'] ?>">
                <!--end::Hidden student ID-->

                <div class="mb-5">
                    <label class="form-label required">School / Admission</label>
                    <?php if (count($admissionsForModal) === 1): ?>
                        <input type="hidden" id="sdRefAdmissionId" value="<?= (int) $admissionsForModal[0]['admission_id'] ?>">
                        <div class="form-control form-control-solid d-flex align-items-center gap-3 py-3"
                             style="background:#f9f9f9;cursor:default;">
                            <i class="ki-duotone ki-home-2 fs-3 text-primary">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <span>
                                <strong><?= esc($admissionsForModal[0]['sch_name'] ?? 'Unknown School') ?></strong>
                                <span class="ms-2 badge badge-light-<?= ($admissionsForModal[0]['admission_status'] ?? '') === 'Active' ? 'success' : 'warning' ?>">
                                    <?= esc($admissionsForModal[0]['admission_status'] ?? '') ?>
                                </span>
                            </span>
                        </div>
                    <?php elseif (count($admissionsForModal) > 1): ?>
                        <select class="form-select form-select-solid" id="sdRefAdmissionId">
                            <option value="">— Select School —</option>
                            <?php foreach ($admissionsForModal as $adm): ?>
                            <option value="<?= (int) $adm['admission_id'] ?>">
                                <?= esc($adm['sch_name'] ?? 'Unknown School') ?>
                                — <?= esc($adm['admission_status'] ?? '') ?>
                                <?= !empty($adm['admission_date']) ? ' (' . date('Y', strtotime($adm['admission_date'])) . ')' : '' ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <div class="alert alert-warning py-3">
                            No school admission found. Please contact an administrator.
                        </div>
                        <input type="hidden" id="sdRefAdmissionId" value="">
                    <?php endif; ?>
                </div>

                <div class="mb-5">
                    <label class="form-label required">Reference Type</label>
                    <select class="form-select form-select-solid" id="sdRefCatId">
                        <option value="">— Select Type —</option>
                        <?php foreach ($refCategories as $cat): ?>
                        <option value="<?= (int) $cat['ref_cat_id'] ?>"><?= esc($cat['ref_cat_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Additional Note <span class="text-muted fs-7">(optional)</span></label>
                    <textarea class="form-control form-control-solid" id="sdRefNote" rows="3"
                              placeholder="Add any details relevant to your request..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSubmitRefRequest">Submit Request</button>
            </div>
        </div>
    </div>
</div>
<!--end::Reference Request Modal-->
<?php endif; ?>

<?php if ($isOwnProfile ?? false): ?>
<script>
// Modal trigger
document.getElementById('sdOpenRefRequestModal')?.addEventListener('click', function (e) {
    e.preventDefault();
    if (document.getElementById('sdRefNote')) document.getElementById('sdRefNote').value = '';
    if (document.getElementById('sdRefCatId')) document.getElementById('sdRefCatId').value = '';
    bootstrap.Modal.getOrCreateInstance(document.getElementById('kt_modal_ref_request')).show();
});

// Submit
document.getElementById('btnSubmitRefRequest').addEventListener('click', function () {
    var btn         = this;
    var admEl       = document.getElementById('sdRefAdmissionId');
    var refTypeSel  = document.getElementById('sdRefCatId');
    var studentId   = (document.getElementById('sdRefStudentId') || {}).value || '';
    var admissionId = admEl ? admEl.value : '';

    if (!admissionId) {
        Swal.fire({ icon: 'warning', title: 'School Required',
            text: 'Please select a school / admission.',
            confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' } });
        return;
    }
    if (!refTypeSel || !refTypeSel.value) {
        Swal.fire({ icon: 'warning', title: 'Reference Type Required',
            text: 'Please select a reference type.',
            confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' } });
        return;
    }

    btn.disabled    = true;
    btn.textContent = 'Submitting...';

    var params = 'student_id='    + encodeURIComponent(studentId)
               + '&user_id='      + encodeURIComponent(studentId)
               + '&admission_id=' + encodeURIComponent(admissionId)
               + '&ref_cat_id='   + encodeURIComponent(refTypeSel.value)
               + '&ref_type_name='+ encodeURIComponent(refTypeSel.options[refTypeSel.selectedIndex].text.trim())
               + '&request_note=' + encodeURIComponent((document.getElementById('sdRefNote') || {}).value || '');

    fetch('<?= base_url('reference/request/store') ?>', {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body:    params,
    })
    .then(function (r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(function (data) {
        bootstrap.Modal.getInstance(document.getElementById('kt_modal_ref_request'))?.hide();
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Request Submitted',
                text: data.message, timer: 3500, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'warning', title: 'Notice', text: data.message,
                confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-primary' } });
        }
    })
    .catch(function (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Could not submit request. Please try again.' });
        console.error(err);
    })
    .finally(function () {
        btn.disabled    = false;
        btn.textContent = 'Submit Request';
    });
});
</script>
<?php endif; ?>