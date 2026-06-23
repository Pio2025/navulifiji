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
			    
			    
			    <!--begin::Table widget 14-->
				<div class="card card-flush h-md-100">
					<!--begin::Header-->
					<div class="card-header pt-7">
						<!--begin::Title-->
						<h3 class="card-title align-items-start flex-column">
							<span class="card-label fw-bold text-gray-800">School Department</span>
							<?php
							    $numOfDep = count($schDepartment);
							    $s = '';
							    if($numOfDep > 1){
							        $s = 's';
							    }
							?>
							<span class="text-gray-500 mt-1 fw-semibold fs-6">Total of <?php echo $numOfDep.' record'.$s; ?> found.</span>
						</h3>
						<!--end::Title-->
						<!--begin::Toolbar-->
						<div class="card-toolbar">
							<a href="#" class="btn btn-sm btn-light-primary">Update Department</a>
						</div>
						<!--end::Toolbar-->
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body pt-6">
						<!--begin::Table container-->
						<div class="table-responsive">
							<!--begin::Table-->
							<table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
								<!--begin::Table head-->
								<thead>
									<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
										<th>DEPARTMENT</th>
										<th>STATUS</th>
										<th class="pb-3 w-50px ">ACTION</th>
									</tr>
								</thead>
								<!--end::Table head-->
								<!--begin::Table body-->
								<tbody>
								    <?php
                                    // First check if $schDepartment is valid and not empty
                                    if (!empty($schDepartment) && is_iterable($schDepartment)):
                                        foreach($schDepartment as $row):
                                            // Fix 1: Use proper spelling and check if key exists
                                            $deptStatus = $row['dept_status'] ?? '';
                                            
                                            // Fix 2: Correct spelling of "Established" and "Unestablished"
                                            if($deptStatus == 'Established'){  // Fixed: Added 'h'
                                                $theme = 'success';
                                            } else if($deptStatus == 'Unestablished'){  // Fixed: Lowercase 'e', correct spelling
                                                $theme = 'danger';
                                            } else if($deptStatus == 'Registered'){
                                                $theme = 'primary';
                                            } else {
                                                $theme = 'warning';
                                            }
                                            
                                            // Fix 3: Use esc() for security and null coalescing for safety
                                            $deptName = esc($row['dept_name'] ?? 'N/A');
                                            $deptCode = esc($row['dept_code'] ?? 'ENG');  // Assuming you have dept_code field
                                            $codeTheme = esc($row['dept_theme'] ?? 'danger');
                                            
                                            // Fix 4: Use short echo tags for cleaner HTML
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Symbol-->
                                                    <div class="symbol symbol-40px me-4">
                                                        <div class="symbol-label fs-5 fw-semibold bg-<?= $codeTheme ?> text-inverse-danger">
                                                            <?= $deptCode ?>
                                                        </div>
                                                    </div>
                                                    <!--end::Symbol-->
                                                    <div class="d-flex justify-content-start flex-column">
                                                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                                            <?= $deptName ?>
                                                        </a>
                                                        <?php
                                                            // Get head information
                                                            $headId = $row['dept_head'] ?? 0;
                                                            $headFirstName = esc($row['fname'] ?? '');
                                                            $headLastName = esc($row['lname'] ?? '');
                                                            
                                                            if ($headId > 0 && !empty($headFirstName)) {
                                                                $headName = $headFirstName . ' ' . $headLastName;
                                                                $headEmail = esc($row['email'] ?? '');
                                                            } else {
                                                                $headName = 'No head assigned';
                                                                $headEmail = '';
                                                            }
                                                        ?>
                                                        <!-- If you have a contact person or head field -->
                                                        <span class="text-gray-500 fw-semibold d-block fs-7">
                                                            <?= $headName ?>
                                                            <?php if (!empty($headEmail)): ?>
                                                                <br><small class="text-muted"><?= $headEmail ?></small>
                                                            <?php endif; ?>
                                                        </span>
                                                        
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-start">
                                                <!-- Fix 5: Use dynamic theme from status -->
                                                <span class="badge py-3 px-4 fs-7 badge-light-<?= $theme ?>">
                                                    <?= $deptStatus ?>
                                                </span>
                                            </td>
                                            <td>
                                                <!--begin::Toolbar-->
                                                <div class="card-toolbar">
                                                    <!--begin::Menu-->
                                                    <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end" 
                                                            data-kt-menu-trigger="click" 
                                                            data-kt-menu-placement="bottom-end" 
                                                            data-kt-menu-overflow="true">
                                                        <i class="ki-duotone ki-dots-square fs-1 text-gray-500 me-n1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                        </i>
                                                    </button>
                                                    <!--begin::Menu 2-->
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">Quick Actions</div>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu separator-->
                                                        <div class="separator mb-3 opacity-75"></div>
                                                        <!--end::Menu separator-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3">View</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3">Update</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="#" class="menu-link px-3">Assign HOD</a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                    </div>
                                                    <!--end::Menu 2-->
                                                    <!--end::Menu-->
                                                </div>
                                                <!--end::Toolbar-->
                                            </td>
                                        </tr>
                                    <?php 
                                        endforeach;
                                    else: 
                                        // Show empty state if no departments
                                    ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-8">
                                                <div class="text-gray-600 fw-semibold fs-6">
                                                    No departments found for this school.
                                                </div>
                                                <div class="mt-3">
                                                    <a href="<?= site_url('school/department/add') ?>" class="btn btn-primary">
                                                        <i class="ki-duotone ki-plus fs-2 me-2"></i>
                                                        Add First Department
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
								</tbody>
								<!--end::Table body-->
							</table>
						</div>
						<!--end::Table-->
					</div>
					<!--end: Card Body-->
				</div>
				<!--end::Table widget 14-->
			    
			    
			    
			</div>
			<!--end::Col-->
		</div>
		<!--end::Row-->
	</div>
	<!--end::Content container-->
</div>
<!--end::Content-->

