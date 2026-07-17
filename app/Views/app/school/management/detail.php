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
				<li class="breadcrumb-item text-muted"><a href="<?= base_url('school') ?>" class="text-muted text-hover-primary">School Listing</a></li>
			</ul>
		</div>
	</div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<div id="kt_app_content_container" class="app-container container-xxl">
	    
	    <?= $this->include('templates/flash_messages') ?>
	    
		<!--begin::Tabs-->
<div class="card">
    <!--begin::Card header-->
    <div class="card-header card-header-stretch">
        <!--begin::Title-->
        <div class="card-title">
            <h3 class="fw-bold m-0">School Information</h3>
        </div>
        <!--end::Title-->
        
        <!--begin::Toolbar-->
        <div class="card-toolbar">
            <!--begin::Tab nav-->
            <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold" role="tablist">
                <!--begin::Tab 1-->
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" role="tab" href="javascript:void(0)" data-bs-target="#kt_tab_pane_1">
                        <i class="ki-duotone ki-home fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Overview
                    </a>
                </li>
                <!--end::Tab 1-->
                
                <!--begin::Tab 2-->
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="javascript:void(0)" data-bs-target="#kt_tab_pane_2">
                        <i class="ki-duotone ki-badge fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Subscription
                    </a>
                </li>
                <!--end::Tab 2-->
                
                <!--begin::Tab 3-->
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="javascript:void(0)" data-bs-target="#kt_tab_pane_3">
                        <i class="ki-duotone ki-profile-user fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        Users
                    </a>
                </li>
                <!--end::Tab 3-->
                
                <!--begin::Tab 4-->
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="javascript:void(0)" data-bs-target="#kt_tab_pane_4">
                        <i class="ki-duotone ki-setting-2 fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Curriculum
                    </a>
                </li>
                <!--end::Tab 4-->
                
                <!--begin::Tab 5-->
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="javascript:void(0)" data-bs-target="#kt_tab_pane_5">
                        <i class="ki-duotone ki-geolocation fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Location
                    </a>
                </li>
                <!--end::Tab 5-->
            </ul>
            <!--end::Tab nav-->
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Card header-->
    
    <!--begin::Card body-->
    <div class="card-body">
        <!--begin::Tab content-->
        <div class="tab-content" id="myTabContent">
            <!--begin::Tab pane 1-->
            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                <!--begin::Content-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <h3 class="fw-bold">School Details</h3>
                    <p class="text-gray-600">Provided below is the school overview information.</p>
                    
                    <!--begin::Row-->
                    <?php if (!empty($school['sch_logo'])): ?>
                    <div class="row py-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">School Logo</label>
                        <div class="col-lg-9">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="<?= base_url('uploads/school/logo/' . esc($school['sch_logo'])) ?>" alt="<?= esc($school['sch_name']) ?>" class="border border-gray-300 rounded" />
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!--end::Row-->
                    
                    <!--begin::Row-->
                    <div class="row mb-2 pb-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">Name</label>
                        <div class="col-lg-9 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">
                                <?= esc($school['sch_name']) ?>
                            </span>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    <!--begin::Row-->
                    <div class="row mb-2 pb-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">Category</label>
                        <div class="col-lg-9 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">
                                <?= esc($school['sch_cat_name']) ?>
                            </span>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    <!--begin::Row-->
                    <div class="row mb-2 pb-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">Email</label>
                        <div class="col-lg-9 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">
                                <a href="mailto:<?= esc($school['sch_email']) ?>" class="text-hover-primary">
                                    <?= esc($school['sch_email']) ?>
                                </a>
                            </span>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    
                    <!--begin::Row-->
                    <div class="row mb-2 pb-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">Phone</label>
                        <div class="col-lg-9 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">
                                <?= esc($school['sch_phone']) ?>
                            </span>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    
                    <!--begin::Row-->
                    <div class="row mb-2 pb-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">Address</label>
                        <div class="col-lg-9 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">
                                <?= esc($school['sch_address']) ?>
                            </span>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    <!--begin::Row-->
                    <div class="row mb-2 pb-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">District</label>
                        <div class="col-lg-9 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">
                                <?= esc($school['district_name']) ?>
                            </span>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    <!--begin::Row-->
                    <div class="row mb-2 pb-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">Motto</label>
                        <div class="col-lg-9 fv-row">
                            <span class="fw-semibold text-gray-800 fs-6">
                                <?= esc($school['sch_motto']) ?>
                            </span>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    <!--begin::Row-->
                    <?php if (!empty($school['sch_primary_color']) || !empty($school['sch_secondary_color'])): ?>
                    <div class="row mb-2 pb-5 border-bottom border-bottom-dashed border-gray-300">
                        <label class="col-lg-3 fw-bold text-muted">School Colors</label>
                        
                        <div class="col-lg-9 fv-row">
                            <div class="d-flex align-items-center gap-5">
                                <?php if (!empty($school['sch_primary_color'])): ?>
                                <div class="d-flex align-items-center">
                                    <div class="w-50px h-50px rounded" style="background-color: <?= esc($school['sch_primary_color']) ?>; border: 2px solid #E4E6EF;"></div>
                                    <span class="ms-3 text-gray-600 fs-7">Primary</span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($school['sch_secondary_color'])): ?>
                                <div class="d-flex align-items-center">
                                    <div class="w-50px h-50px rounded" style="background-color: <?= esc($school['sch_secondary_color']) ?>; border: 2px solid #E4E6EF;"></div>
                                    <span class="ms-3 text-gray-600 fs-7">Secondary</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!--end::Row-->
                    
                    
                    <!--begin::Row-->
                    <div class="row mb-2 pb-5 ">
                        <label class="col-lg-3 fw-bold text-muted">Status</label>
                        <div class="col-lg-9 fv-row">
                            <?php
                                $status = '';
                                $theme = '';
                                
                                if($school['sch_status'] == 'Step 1 Configured'
                                    || $school['sch_status'] == 'Step 2 Configured'
                                    || $school['sch_status'] == 'Step 3 Configured'
                                    || $school['sch_status'] == 'Step 4 Configured'
                                    || $school['sch_status'] == 'Step 5 Configured'
                                ){
                                    $status = 'Pending Configuration';
                                    $theme = 'warning';
                                }else if($school['sch_status'] == 'Active'){
                                    $status = 'Active';
                                    $theme = 'success';
                                }else if($school['sch_status'] == 'Not Active'){
                                    $status = 'Not Active';
                                    $theme = 'warning';
                                }else{
                                    $status = $school['sch_status'] ?? 'Unknown';
                                    $theme = 'info';
                                }
                            ?>
                            <span class="badge badge-light-<?php echo $theme; ?>"><?php echo $status; ?></span>
                        </div>
                    </div>
                    <!--end::Row-->
                    
                    
                </div>
                <!--end::Content-->
            </div>
            <!--end::Tab pane 1-->
            
            <!--begin::Tab pane 2-->
            <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                <div class="d-flex flex-column gap-7 gap-lg-10">

                    <!--begin::Active Subscription-->
                    <div>
                        <h3 class="fw-bold mb-4">Current Subscription</h3>
                        <?php if ($subscription && !empty($subscription['plan_name']) && $subscription['plan_name'] !== 'No Active Subscription'): ?>
                        <?php
                        $subStatusColor = match($subscription['subscription_status'] ?? '') {
                            'Active'          => 'success',
                            'Pending Payment' => 'warning',
                            'Expired'         => 'danger',
                            default           => 'secondary',
                        };
                        ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center gap-5">
                                    <div class="symbol symbol-60px flex-shrink-0">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-shield-tick fs-2x text-primary">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-3 mb-1">
                                            <span class="fw-bold text-gray-900 fs-4"><?= esc($subscription['plan_name']) ?> Plan</span>
                                            <span class="badge badge-light-<?= $subStatusColor ?> fs-7"><?= esc($subscription['subscription_status']) ?></span>
                                        </div>
                                        <div class="text-muted fs-7">
                                            <?= !empty($subscription['subscription_start_date']) ? date('d M Y', strtotime($subscription['subscription_start_date'])) : '—' ?>
                                            &nbsp;→&nbsp;
                                            <?= !empty($subscription['subscription_end_date']) ? date('d M Y', strtotime($subscription['subscription_end_date'])) : '—' ?>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-gray-800 fs-6">
                                            <?= !empty($subscription['plan_monthly_cost']) && $subscription['plan_monthly_cost'] > 0
                                                ? '$' . number_format($subscription['plan_monthly_cost'], 2) . ' /mo'
                                                : 'Free' ?>
                                        </div>
                                        <div class="text-muted fs-8">
                                            <?= esc($subscription['payment_mode'] ?? '—') ?>
                                            <?php if (!empty($subscription['subscription_term'])): ?>
                                            &bull; <?= $subscription['subscription_term'] ?> months
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="d-flex align-items-center gap-3 p-5 bg-light-warning rounded-3">
                            <i class="ki-duotone ki-information-5 fs-2x text-warning flex-shrink-0">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <div>
                                <div class="fw-bold text-gray-800 fs-6">No Active Subscription</div>
                                <div class="text-muted fs-7">This school does not have a current active or pending subscription.</div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!--end::Active Subscription-->

                    <!--begin::Subscription History-->
                    <div>
                        <h3 class="fw-bold mb-4">
                            Subscription History
                            <span class="badge badge-light-secondary ms-2 fs-7"><?= count($allSubscriptions) ?></span>
                        </h3>

                        <?php if (empty($allSubscriptions)): ?>
                        <div class="text-center py-8 text-muted fs-6">No subscription records found.</div>
                        <?php else: ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-7 gy-3 mb-0">
                                        <thead class="border-bottom border-gray-200 fs-8 fw-bold text-uppercase text-muted">
                                            <tr>
                                                <th class="ps-5">Plan</th>
                                                <th>Period</th>
                                                <th>Term</th>
                                                <th>Payment</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                        <?php foreach ($allSubscriptions as $sub):
                                            $hc = match($sub['subscription_status'] ?? '') {
                                                'Active'          => 'success',
                                                'Pending Payment' => 'warning',
                                                'Expired'         => 'danger',
                                                default           => 'secondary',
                                            };
                                        ?>
                                        <tr>
                                            <td class="ps-5">
                                                <div class="fw-bold text-gray-800"><?= esc($sub['plan_name'] ?? '—') ?></div>
                                                <div class="text-muted fs-8">
                                                    <?= !empty($sub['plan_monthly_cost']) && $sub['plan_monthly_cost'] > 0
                                                        ? '$' . number_format($sub['plan_monthly_cost'], 2) . ' /mo'
                                                        : 'Free' ?>
                                                </div>
                                            </td>
                                            <td class="text-gray-700 fs-8">
                                                <?= !empty($sub['subscription_start_date']) ? date('d M Y', strtotime($sub['subscription_start_date'])) : '—' ?>
                                                <span class="text-muted mx-1">→</span>
                                                <?= !empty($sub['subscription_end_date']) ? date('d M Y', strtotime($sub['subscription_end_date'])) : '—' ?>
                                            </td>
                                            <td class="text-gray-700 fs-8"><?= !empty($sub['subscription_term']) ? $sub['subscription_term'] . ' months' : '—' ?></td>
                                            <td class="text-gray-700 fs-8"><?= esc($sub['payment_mode'] ?? '—') ?></td>
                                            <td>
                                                <span class="badge badge-light-<?= $hc ?> fs-8"><?= esc($sub['subscription_status'] ?? '—') ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!--end::Subscription History-->

                </div>
            </div>
            <!--end::Tab pane 2-->
            
            <!--begin::Tab pane 3-->
            <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                <!--begin::Content-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <h3 class="fw-bold">User Management</h3>
                    <p class="text-gray-600">View and manage school users and permissions.</p>
                    
                    
                    <!--begin::Chart Card-->
                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                        <div class="col-xl-6">
                            <div class="card card-flush h-xl-100">
                                <div class="card-header pt-7">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-800">User Distribution</span>
                                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Breakdown by role category</span>
                                    </h3>
                                </div>
                                <div class="card-body pt-5">
                                    <div id="kt_school_user_distribution_chart" style="height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-6">
                            <div class="card card-flush h-xl-100">
                                <div class="card-header pt-7">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-800">User Status</span>
                                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Active vs Inactive users</span>
                                    </h3>
                                </div>
                                <div class="card-body pt-5">
                                    <div id="kt_school_user_status_chart" style="height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Chart Card-->


                    
                    
                </div>
                <!--end::Content-->
            </div>
            <!--end::Tab pane 3-->
            
            <!--begin::Tab pane 4-->
            <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                <!--begin::Content-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <h3 class="fw-bold">School Curriculum</h3>
                    <p class="text-gray-600">Comprehensive overview of school curriculum configuration including levels, departments, streams, and subjects.</p>
                    
                    <!--begin::Departments Section (Only for Secondary Schools)-->
                    <?php if ($school['sch_cat_id'] == 4): ?>
                    <div class="separator separator-dashed my-5"></div>
                    
                    <div>
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h4 class="fw-bold text-gray-800 mb-1">
                                    <i class="ki-duotone ki-category fs-2 text-primary me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    School Departments
                                </h4>
                                <p class="text-gray-600 fs-7 mb-0">Academic departments configured for this school</p>
                            </div>
                            <button type="button" id="btn-add-department"
                                class="btn btn-sm btn-primary"
                                data-bs-toggle="modal" data-bs-target="#kt_modal_configure_departments"
                                <?= count($departments) >= $totalDepartments ? 'disabled' : '' ?>>
                                <i class="ki-duotone ki-plus fs-4"><span class="path1"></span><span class="path2"></span></i>
                                Add Department
                            </button>
                        </div>

                        <div id="departments-section">
                        <?php if (empty($departments)): ?>
                            <div class="alert alert-warning d-flex align-items-center p-5">
                                <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-dark">School Departments Not Configured</h4>
                                    <span>Configure school departments to organize subjects and staff. <button type="button" class="btn btn-link p-0 fw-bold align-baseline" data-bs-toggle="modal" data-bs-target="#kt_modal_configure_departments">Configure now</button></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row g-5" id="departments-cards">
                                <?php foreach ($departments as $dept): ?>
                                <div class="col-md-4" id="dept-card-<?= (int)$dept['sch_dept_id'] ?>">
                                    <div class="card border-0 bg-light-<?= esc($dept['dept_theme']) ?> h-100">
                                        <div class="card-body p-5">
                                            <div class="d-flex align-items-start mb-3">
                                                <?= $dept['dept_icon'] ?>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="fw-bold text-gray-800 fs-5"><?= esc($dept['dept_name']) ?></div>
                                                    <span class="badge badge-light-<?= ($dept['dept_status'] ?? 'Established') === 'Established' ? 'success' : 'warning' ?> dept-status-badge mt-1">
                                                        <?= esc($dept['dept_status'] ?? 'Established') ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm btn-light-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                        Edit Status
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item dept-status-option" href="#" data-dept-id="<?= (int)$dept['sch_dept_id'] ?>" data-status="Established">Established</a></li>
                                                        <li><a class="dropdown-item dept-status-option" href="#" data-dept-id="<?= (int)$dept['sch_dept_id'] ?>" data-status="Non Established">Non Established</a></li>
                                                    </ul>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-light-danger btn-dept-delete" data-dept-id="<?= (int)$dept['sch_dept_id'] ?>" data-dept-name="<?= esc($dept['dept_name']) ?>">
                                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!--end::Departments Section-->
                    
                    <!--begin::Separator-->
                    <div class="separator separator-dashed my-5"></div>
                    <!--end::Separator-->
                    
                    <!--begin::Levels & Streams Section-->
                    <div>
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h4 class="fw-bold text-gray-800 mb-1">
                                    <i class="ki-duotone ki-book-open fs-2 text-success me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                    School Levels & Streams
                                </h4>
                                <p class="text-gray-600 fs-7 mb-0">Year levels, streams, and subject configuration</p>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-8 fw-semibold text-gray-600 mb-2">Stream Naming</span>
                                    <div class="d-flex gap-4">
                                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                                            <input class="form-check-input" type="radio" name="stream_naming" id="naming_numerical" value="numerical" checked>
                                            <label class="form-check-label fw-semibold fs-7" for="naming_numerical">Numerical</label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                                            <input class="form-check-input" type="radio" name="stream_naming" id="naming_alphabetical" value="alphabetical">
                                            <label class="form-check-label fw-semibold fs-7" for="naming_alphabetical">Alphabetical</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="btn-add-level"
                                    class="btn btn-sm btn-success"
                                    data-bs-toggle="modal" data-bs-target="#kt_modal_configure_levels"
                                    <?= count($levelsWithStreams) >= $totalLevelsForCategory ? 'disabled' : '' ?>>
                                    <i class="ki-duotone ki-plus fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    Add Level
                                </button>
                            </div>
                        </div>

                        <div id="levels-section-content">
                        <?php if (empty($levelsWithStreams)): ?>
                            <div id="levels-empty-state" class="alert alert-danger d-flex align-items-center p-5">
                                <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-dark">School Levels Not Configured</h4>
                                    <span>School levels are required for system operation. <button type="button" class="btn btn-link p-0 fw-bold align-baseline" data-bs-toggle="modal" data-bs-target="#kt_modal_configure_levels">Configure now</button></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <!--begin::Accordion-->
                            <div class="accordion accordion-icon-toggle" id="kt_accordion_curriculum">
                                <?php foreach ($levelsWithStreams as $index => $levelData): ?>
                                    <?php 
                                    $level = $levelData['level'];
                                    $streams = $levelData['streams'];
                                    $isExpanded = $index === 0 ? 'true' : 'false';
                                    $showClass = $index === 0 ? 'show' : '';
                                    ?>
                                    
                                    <!--begin::Level Item-->
                                    <div class="mb-5">
                                        <div class="d-flex align-items-center">
                                            <div class="accordion-header py-3 d-flex flex-grow-1 collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_<?= $index ?>">
                                                <span class="accordion-icon">
                                                    <i class="ki-duotone ki-arrow-right fs-4">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <h3 class="fs-4 fw-semibold mb-0 ms-4">
                                                    <?= esc($level['level_name']) ?>
                                                    <span class="badge badge-light-primary ms-3"><?= count($streams) ?> Stream(s)</span>
                                                </h3>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light-success ms-2 btn-add-stream"
                                                data-level-id="<?= (int)$level['sch_level_id'] ?>"
                                                data-level-name="<?= esc($level['level_name']) ?>">
                                                <i class="ki-duotone ki-plus fs-5"><span class="path1"></span><span class="path2"></span></i>
                                                Add Stream
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-danger ms-2 btn-level-delete"
                                                data-level-id="<?= (int)$level['sch_level_id'] ?>"
                                                data-level-name="<?= esc($level['level_name']) ?>">
                                                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </button>
                                        </div>
                                        
                                        <div id="kt_accordion_<?= $index ?>" class="fs-6 collapse <?= $showClass ?> ps-10" data-bs-parent="#kt_accordion_curriculum">
                                            <?php if (empty($streams)): ?>
                                                <div class="alert alert-info d-flex align-items-center p-4 mt-3">
                                                    <i class="ki-duotone ki-information fs-2x text-info me-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                    <span>No streams configured for this level.</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="py-5">
                                                    <?php foreach ($streams as $streamIndex => $stream): ?>
                                                        <!--begin::Stream Block-->
                                                        <div class="mb-7 pb-5 <?= $streamIndex < count($streams) - 1 ? 'border-bottom border-gray-300' : '' ?>" id="stream-block-<?= (int)$stream['stream_id'] ?>">
                                                            <!--begin::Stream Header-->
                                                            <div class="d-flex align-items-center justify-content-between mb-5 p-4 bg-light-success rounded">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ki-duotone ki-element-11 fs-2 text-success me-3">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                        <span class="path3"></span>
                                                                        <span class="path4"></span>
                                                                    </i>
                                                                    <h4 class="fw-bold text-gray-800 mb-0"><?= esc($stream['stream_name']) ?></h4>
                                                                </div>
                                                                <div class="d-flex gap-2 align-items-center">
                                                                    <span class="badge badge-success stream-core-badge">
                                                                        <i class="ki-duotone ki-check-circle fs-5 me-1">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                        </i>
                                                                        <?= $stream['core_count'] ?> Core
                                                                    </span>
                                                                    <span class="badge badge-info stream-optional-badge">
                                                                        <i class="ki-duotone ki-add-item fs-5 me-1">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                            <span class="path3"></span>
                                                                        </i>
                                                                        <?= $stream['optional_count'] ?> Optional
                                                                    </span>
                                                                    <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-stream-delete"
                                                                        data-stream-id="<?= (int)$stream['stream_id'] ?>"
                                                                        data-stream-name="<?= esc($stream['stream_name']) ?>">
                                                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <!--end::Stream Header-->
                                                            
                                                            <!--begin::Subjects Row-->
                                                            <div class="row g-5">
                                                                <!--begin::Core Subjects-->
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                                                        <h5 class="fw-bold text-gray-800 mb-0">
                                                                            <i class="ki-duotone ki-abstract-26 fs-2 text-success me-2">
                                                                                <span class="path1"></span>
                                                                                <span class="path2"></span>
                                                                            </i>
                                                                            Core Subjects
                                                                        </h5>
                                                                        <button type="button" class="btn btn-icon btn-sm btn-light-success btn-add-core-subject"
                                                                            data-stream-id="<?= (int)$stream['stream_id'] ?>">
                                                                            <i class="ki-duotone ki-plus fs-4"><span class="path1"></span><span class="path2"></span></i>
                                                                        </button>
                                                                    </div>
                                                                    <div id="core-subjects-<?= (int)$stream['stream_id'] ?>">
                                                                    <?php if (empty($stream['core_subjects'])): ?>
                                                                        <p class="text-gray-600 fs-7">No core subjects configured</p>
                                                                    <?php else: ?>
                                                                        <div class="d-flex flex-column gap-2">
                                                                            <?php foreach ($stream['core_subjects'] as $subject): ?>
                                                                                <div class="d-flex align-items-center justify-content-between" id="core-sub-<?= (int)$subject['stream_core_sub_id'] ?>">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ki-duotone ki-check fs-3 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
                                                                                        <span class="text-gray-700 fw-semibold fs-6"><?= esc($subject['subject_name'] ?? 'Unknown Subject') ?></span>
                                                                                    </div>
                                                                                    <div class="d-flex gap-1">
                                                                                        <button type="button" class="btn btn-icon btn-xs btn-light-primary btn-edit-core-subject"
                                                                                            data-core-id="<?= (int)$subject['stream_core_sub_id'] ?>"
                                                                                            data-dept-id="<?= (int)($subject['sch_dept_id_fk'] ?? 0) ?>"
                                                                                            data-subject-name="<?= esc($subject['subject_name'] ?? '') ?>">
                                                                                            <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                                                                                        </button>
                                                                                        <button type="button" class="btn btn-icon btn-xs btn-light-danger btn-delete-core-subject"
                                                                                            data-core-id="<?= (int)$subject['stream_core_sub_id'] ?>"
                                                                                            data-subject-name="<?= esc($subject['subject_name'] ?? '') ?>"
                                                                                            data-stream-id="<?= (int)$stream['stream_id'] ?>">
                                                                                            <i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <!--end::Core Subjects-->
                                                                
                                                                <!--begin::Optional Subjects-->
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                                                        <h5 class="fw-bold text-gray-800 mb-0">
                                                                            <i class="ki-duotone ki-category fs-2 text-info me-2">
                                                                                <span class="path1"></span>
                                                                                <span class="path2"></span>
                                                                                <span class="path3"></span>
                                                                                <span class="path4"></span>
                                                                            </i>
                                                                            Optional Subjects
                                                                        </h5>
                                                                        <button type="button" class="btn btn-icon btn-sm btn-light-info btn-add-optional-subject"
                                                                            data-stream-id="<?= (int)$stream['stream_id'] ?>">
                                                                            <i class="ki-duotone ki-plus fs-4"><span class="path1"></span><span class="path2"></span></i>
                                                                        </button>
                                                                    </div>
                                                                    <div id="optional-subjects-<?= (int)$stream['stream_id'] ?>">
                                                                    <?php if (empty($stream['optional_subjects'])): ?>
                                                                        <p class="text-gray-600 fs-7">No optional subjects configured</p>
                                                                    <?php else: ?>
                                                                        <div class="d-flex flex-column gap-4">
                                                                            <?php foreach ($stream['optional_subjects'] as $optionNum => $optSubjects): ?>
                                                                                <div class="border border-dashed border-info rounded p-3 bg-light-info"
                                                                                     id="opt-group-<?= (int)$stream['stream_id'] ?>-<?= $optionNum ?>">
                                                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                                                        <span class="fw-bold text-info fs-7">Option <?= $optionNum ?> (Choose 1)</span>
                                                                                    </div>
                                                                                    <div class="d-flex flex-column gap-1">
                                                                                        <?php foreach ($optSubjects as $subject): ?>
                                                                                            <div class="d-flex align-items-center justify-content-between"
                                                                                                 id="opt-sub-<?= (int)$subject['stream_opt_sub_id'] ?>">
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <i class="ki-duotone ki-double-right fs-5 text-info me-2">
                                                                                                        <span class="path1"></span>
                                                                                                        <span class="path2"></span>
                                                                                                    </i>
                                                                                                    <span class="text-gray-700 fs-7"><?= esc($subject['subject_name'] ?? 'Unknown Subject') ?></span>
                                                                                                </div>
                                                                                                <button type="button" class="btn btn-icon btn-xs btn-light-danger btn-remove-opt-sub"
                                                                                                    data-opt-sub-id="<?= (int)$subject['stream_opt_sub_id'] ?>"
                                                                                                    data-stream-id="<?= (int)$stream['stream_id'] ?>"
                                                                                                    data-option-num="<?= $optionNum ?>"
                                                                                                    data-subject-name="<?= esc($subject['subject_name'] ?? '') ?>">
                                                                                                    <i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        <?php endforeach; ?>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <!--end::Optional Subjects-->
                                                            </div>
                                                            <!--end::Subjects Row-->
                                                        </div>
                                                        <!--end::Stream Block-->
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!--end::Level Item-->
                                <?php endforeach; ?>
                            </div>
                            <!--end::Accordion-->
                        <?php endif; ?>
                        </div><!--end::levels-section-content-->
                    </div>
                    <!--end::Levels & Streams Section-->

                    <!--begin::Separator-->
                    <div class="separator separator-dashed my-5"></div>
                    <!--end::Separator-->

                    <!--begin::School Registered Subjects Section-->
                    <div>
                        <div class="d-flex align-items-start justify-content-between mb-5">
                            <div>
                                <h4 class="fw-bold text-gray-800 mb-1">
                                    <i class="ki-duotone ki-book fs-2 text-warning me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    School Registered Subjects
                                </h4>
                                <p class="text-gray-600 fs-7 mb-0">All subjects registered for this school, grouped by level</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-light-warning fs-7" id="sch-subjects-count-badge">
                                    <?= $schoolSubjectsTotal ?> Subject<?= $schoolSubjectsTotal !== 1 ? 's' : '' ?>
                                </span>
                                <button type="button" class="btn btn-sm btn-light-primary" id="btn-open-add-school-subject">
                                    <i class="ki-duotone ki-plus fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    Add Subject
                                </button>
                            </div>
                        </div>

                        <div id="sch-subjects-section">
                        <?php if (empty($schoolSubjectsByLevel)): ?>
                            <div class="alert alert-info d-flex align-items-center p-5" id="sch-subjects-empty">
                                <i class="ki-duotone ki-information-5 fs-2hx text-info me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-dark">No Subjects Registered Yet</h4>
                                    <span>Use the <strong>Add Subject</strong> button above to register subjects for this school, or they will be added automatically when assigned to streams.</span>
                                </div>
                            </div>
                        <?php else: ?>
                        <?php foreach ($schoolSubjectsByLevel as $levelGroup): ?>
                            <div class="sch-level-group mb-6" id="sch-level-group-<?= (int)$levelGroup['level_id'] ?>">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="bullet bullet-vertical bg-warning me-3 h-20px"></span>
                                    <h6 class="fw-bold text-gray-700 mb-0"><?= esc($levelGroup['level_name']) ?></h6>
                                    <span class="badge badge-light ms-3 sch-level-count"><?= count($levelGroup['subjects']) ?></span>
                                </div>
                                <div class="table-responsive ms-4">
                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-2 mb-0">
                                        <thead>
                                            <tr class="fw-semibold text-gray-500 fs-8 text-uppercase">
                                                <th>Subject</th>
                                                <th>Department</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sch-level-tbody-<?= (int)$levelGroup['level_id'] ?>">
                                        <?php foreach ($levelGroup['subjects'] as $schSub): ?>
                                            <tr id="sch-sub-row-<?= (int)$schSub['sch_sub_id'] ?>">
                                                <td>
                                                    <span class="fw-semibold text-gray-800"><?= esc($schSub['subject_name'] ?? '—') ?></span>
                                                </td>
                                                <td>
                                                    <span class="sch-sub-dept-name"><?= esc($schSub['dept_name'] ?? '—') ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-<?= $schSub['sch_sub_status'] === 'Active' ? 'success' : 'warning' ?> sch-sub-status-badge">
                                                        <?= esc($schSub['sch_sub_status']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit-sch-subject me-1"
                                                        data-sch-sub-id="<?= (int)$schSub['sch_sub_id'] ?>"
                                                        data-subject-name="<?= esc($schSub['subject_name'] ?? '') ?>"
                                                        data-dept-id="<?= (int)($schSub['sch_dept_id_fk'] ?? 0) ?>"
                                                        data-status="<?= esc($schSub['sch_sub_status']) ?>">
                                                        <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete-sch-subject"
                                                        data-sch-sub-id="<?= (int)$schSub['sch_sub_id'] ?>"
                                                        data-subject-name="<?= esc($schSub['subject_name'] ?? '') ?>"
                                                        data-level-id="<?= (int)$levelGroup['level_id'] ?>">
                                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                    <!--end::School Registered Subjects Section-->

                </div>
                <!--end::Content-->
            </div>
            <!--end::Tab pane 4-->
            

            
            <!--begin::Tab pane 5 - Map Location-->
            <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                <!--begin::Content-->
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <!--begin::Header-->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h3 class="fw-bold mb-2">School Location</h3>
                            <p class="text-gray-600 mb-0">
                                <i class="ki-duotone ki-geolocation fs-3 text-primary me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <?= esc($school['sch_address'] ?? 'Suva, Fiji') ?>
                            </p>
                            <?php if (empty($school['sch_x_coord']) || empty($school['sch_y_coord'])): ?>
                            <p class="text-warning fs-7 mb-0 mt-2">
                                <i class="ki-duotone ki-information fs-3 text-warning me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Exact coordinates not set. Showing general Suva area.
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <!--begin::Map Controls-->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-light-primary active" onclick="changeLayer('street')" id="btn-street">
                                <i class="ki-duotone ki-map fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Street
                            </button>
                            <button type="button" class="btn btn-sm btn-light" onclick="changeLayer('satellite')" id="btn-sat">
                                <i class="ki-duotone ki-focus fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Satellite
                            </button>
                            <button type="button" class="btn btn-sm btn-light" onclick="changeLayer('hybrid')" id="btn-hybrid">
                                <i class="ki-duotone ki-switch fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Hybrid
                            </button>
                        </div>
                        <!--end::Map Controls-->
                    </div>
                    <!--end::Header-->
                    
                    <!--begin::Map Container-->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div id="school-map" style="height: 500px; width: 100%; border-radius: 0.475rem;"></div>
                        </div>
                    </div>
                    <!--end::Map Container-->
                    
                    <!--begin::Map Info-->
                    <div class="row g-5">
                        <?php if (!empty($school['sch_x_coord']) && !empty($school['sch_y_coord'])): ?>
                        <div class="col-md-4">
                            <div class="card bg-light-primary">
                                <div class="card-body p-5">
                                    <i class="ki-duotone ki-geolocation fs-2x text-primary mb-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <div class="fw-bold text-gray-800 fs-6 mb-1">Coordinates</div>
                                    <div class="text-gray-600 fs-7" id="coordinates">
                                        <?php 
                                        $lat = floatval($school['sch_y_coord']);
                                        $lng = floatval($school['sch_x_coord']);
                                        $latDir = $lat >= 0 ? 'N' : 'S';
                                        $lngDir = $lng >= 0 ? 'E' : 'W';
                                        echo number_format(abs($lat), 4) . '° ' . $latDir . ', ';
                                        echo number_format(abs($lng), 4) . '° ' . $lngDir;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="<?= (!empty($school['sch_x_coord']) && !empty($school['sch_y_coord'])) ? 'col-md-4' : 'col-md-6' ?>">
                            <div class="card bg-light-success">
                                <div class="card-body p-5">
                                    <i class="ki-duotone ki-map fs-2x text-success mb-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div class="fw-bold text-gray-800 fs-6 mb-1">District</div>
                                    <div class="text-gray-600 fs-7"><?= esc($school['district_name'] ?? 'Suva') ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="<?= (!empty($school['sch_x_coord']) && !empty($school['sch_y_coord'])) ? 'col-md-4' : 'col-md-6' ?>">
                            <div class="card bg-light-info">
                                <div class="card-body p-5">
                                    <i class="ki-duotone ki-route fs-2x text-info mb-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <div class="fw-bold text-gray-800 fs-6 mb-1">Get Directions</div>
                                    <?php if (!empty($school['sch_x_coord']) && !empty($school['sch_y_coord'])): ?>
                                        <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $school['sch_y_coord'] ?>,<?= $school['sch_x_coord'] ?>" 
                                           target="_blank" 
                                           class="text-info text-hover-primary fs-7">
                                            Open in Google Maps
                                        </a>
                                    <?php else: ?>
                                        <a href="https://www.google.com/maps/place/Suva,+Fiji" 
                                           target="_blank" 
                                           class="text-info text-hover-primary fs-7">
                                            View Suva on Map
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Map Info-->
                    
                    <?php if (empty($school['sch_x_coord']) || empty($school['sch_y_coord'])): ?>
                    <!--begin::Update Location Notice-->
                    <div class="alert alert-warning d-flex align-items-center p-5">
                        <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-dark">Location Not Set</h4>
                            <span>Please update the school profile with exact GPS coordinates for accurate mapping.</span>
                            <a href="<?= base_url('school/edit/' . $school['sch_id']) ?>" class="btn btn-sm btn-warning mt-3" style="max-width: 150px;">
                                Update Location
                            </a>
                        </div>
                    </div>
                    <!--end::Update Location Notice-->
                    <?php endif; ?>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Tab pane 5-->
        </div>
        <!--end::Tab content-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Tabs-->


	</div>
</div>
<!--end::Content-->

<link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" />
<script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>

<!--end::Content-->

</div>
</div>
<!--end::Content-->

<script>
</script>

<script>
(function() {
    'use strict';

    let glMap   = null;
    let mapMode = 'street'; // track current mode for toggle buttons

    const hasCoordinates = <?php echo (!empty($school['sch_x_coord']) && !empty($school['sch_y_coord'])) ? 'true' : 'false'; ?>;
    const schoolLat  = <?php echo !empty($school['sch_y_coord']) ? floatval($school['sch_y_coord']) : -17.7134; ?>;
    const schoolLng  = <?php echo !empty($school['sch_x_coord']) ? floatval($school['sch_x_coord']) : 178.0650; ?>;
    const schoolName = <?php echo json_encode($school['sch_name']    ?? 'School'); ?>;
    const schoolAddr = <?php echo json_encode($school['sch_address'] ?? ''); ?>;
    const schoolTel  = <?php echo json_encode($school['sch_phone']   ?? ''); ?>;

    const STYLES = {
        street:    'https://tiles.openfreemap.org/styles/liberty',
        satellite: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        hybrid:    'https://tiles.openfreemap.org/styles/liberty',
    };

    document.addEventListener('DOMContentLoaded', function () {
        const mapTab = document.querySelector('a[data-bs-target="#kt_tab_pane_5"]');
        if (mapTab) {
            mapTab.addEventListener('shown.bs.tab', function () {
                setTimeout(initMap, 150);
            });
        }
    });

    function initMap() {
        if (glMap) { glMap.resize(); return; }

        glMap = new maplibregl.Map({
            container:   'school-map',
            style:       STYLES.street,
            center:      [schoolLng, schoolLat],
            zoom:        hasCoordinates ? 16 : 9,
            attributionControl: true,
        });

        glMap.addControl(new maplibregl.NavigationControl(), 'top-right');

        if (hasCoordinates) {
            // Custom SVG pin element
            const el = document.createElement('div');
            el.className = 'maplibre-pin';
            el.innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="28" viewBox="0 0 22 28">' +
                '<path fill="#009EF7" d="M11 0C4.9 0 0 4.9 0 11c0 8.3 11 17 11 17S22 19.3 22 11C22 4.9 17.1 0 11 0z"/>' +
                '<circle cx="11" cy="11" r="4.5" fill="white"/>' +
                '</svg>';

            const popupHTML =
                '<div style="padding:10px;min-width:200px;font-family:Inter,sans-serif;">' +
                '<div style="font-weight:700;font-size:15px;color:#009EF7;margin-bottom:8px;">' + schoolName + '</div>' +
                (schoolAddr ? '<div style="font-size:12px;color:#7e8299;text-transform:uppercase;font-weight:600;margin-bottom:2px;">Address</div><div style="font-size:13px;color:#3f4254;margin-bottom:8px;">' + schoolAddr + '</div>' : '') +
                (schoolTel  ? '<div style="font-size:12px;color:#7e8299;text-transform:uppercase;font-weight:600;margin-bottom:2px;">Phone</div><div style="font-size:13px;color:#3f4254;">' + schoolTel + '</div>' : '') +
                '</div>';

            const popup = new maplibregl.Popup({ offset: 30, closeButton: true })
                .setHTML(popupHTML);

            // Popup shows on click only (not open by default)
            new maplibregl.Marker({ element: el, anchor: 'bottom' })
                .setLngLat([schoolLng, schoolLat])
                .setPopup(popup)
                .addTo(glMap);
        }
    }

    // Layer toggle — street uses MapLibre GL style; satellite uses raster via addSource
    window.changeLayer = function(type) {
        if (!glMap) return;

        mapMode = type;

        if (type === 'satellite' || type === 'hybrid') {
            glMap.setStyle({
                version: 8,
                sources: {
                    esri: {
                        type: 'raster',
                        tiles: ['https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'],
                        tileSize: 256,
                        attribution: 'Tiles © Esri'
                    }
                },
                layers: [{ id: 'esri', type: 'raster', source: 'esri' }]
            });
        } else {
            glMap.setStyle(STYLES.street);
        }

        // Re-add marker after style reload
        glMap.once('styledata', function() {
            if (hasCoordinates) {
                const el = document.createElement('div');
                el.className = 'maplibre-pin';
                el.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="28" viewBox="0 0 22 28">' +
                    '<path fill="#009EF7" d="M11 0C4.9 0 0 4.9 0 11c0 8.3 11 17 11 17S22 19.3 22 11C22 4.9 17.1 0 11 0z"/>' +
                    '<circle cx="11" cy="11" r="4.5" fill="white"/>' +
                    '</svg>';
                const popupHTML =
                    '<div style="padding:10px;min-width:200px;font-family:Inter,sans-serif;">' +
                    '<div style="font-weight:700;font-size:15px;color:#009EF7;margin-bottom:8px;">' + schoolName + '</div>' +
                    (schoolAddr ? '<div style="font-size:12px;color:#7e8299;text-transform:uppercase;font-weight:600;margin-bottom:2px;">Address</div><div style="font-size:13px;color:#3f4254;margin-bottom:8px;">' + schoolAddr + '</div>' : '') +
                    (schoolTel  ? '<div style="font-size:12px;color:#7e8299;text-transform:uppercase;font-weight:600;margin-bottom:2px;">Phone</div><div style="font-size:13px;color:#3f4254;">' + schoolTel + '</div>' : '') +
                    '</div>';
                new maplibregl.Marker({ element: el, anchor: 'bottom' })
                    .setLngLat([schoolLng, schoolLat])
                    .setPopup(new maplibregl.Popup({ offset: 30 }).setHTML(popupHTML))
                    .addTo(glMap);
            }
        });

        // Update button styles
        document.querySelectorAll('.btn-group button').forEach(b => {
            b.classList.remove('btn-light-primary', 'active');
            b.classList.add('btn-light');
        });
        const ids = { street: 'btn-street', satellite: 'btn-sat', hybrid: 'btn-hybrid' };
        const btn = document.getElementById(ids[type]);
        if (btn) { btn.classList.remove('btn-light'); btn.classList.add('btn-light-primary', 'active'); }
    };

})();
</script>

<style>
#school-map {
    height: 500px;
    width: 100%;
    border-radius: 0.475rem;
}
.maplibre-pin {
    cursor: pointer;
    line-height: 0;
}
.maplibregl-popup-content {
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,.15);
    padding: 0;
}

.leaflet-popup-content {
    margin: 0;
    font-family: Inter, sans-serif;
}

.leaflet-popup-tip {
    box-shadow: 0 3px 14px rgba(0,0,0,0.15);
}

/* ✅ ZOOM CONTROL STYLING */
.leaflet-control-zoom a {
    color: #009EF7 !important;
}

.leaflet-control-zoom a:hover {
    background-color: #009EF7 !important;
    color: white !important;
}
</style>

<!--begin::Inline Scripts for Charts-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Detail.php scripts loaded');
    
    // Wait for tab to be shown before initializing charts
    const usersTab = document.querySelector('a[data-bs-target="#kt_tab_pane_3"]');
    
    if (usersTab) {
        usersTab.addEventListener('shown.bs.tab', function (e) {
            console.log('Users tab shown - initializing charts');
            
            // Small delay to ensure DOM is ready
            setTimeout(function() {
                initUserCharts();
            }, 100);
        });
        
        // Also init if tab is already active on page load
        if (usersTab.classList.contains('active')) {
            setTimeout(function() {
                initUserCharts();
            }, 500);
        }
    }
});

function initUserCharts() {
    console.log('initUserCharts called');
    
    // Check if ApexCharts is available
    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts not loaded!');
        return;
    }
    
    // User Distribution Chart
    const distributionElement = document.querySelector("#kt_school_user_distribution_chart");
    const statusElement = document.querySelector("#kt_school_user_status_chart");
    
    console.log('Chart elements:', {
        distribution: distributionElement,
        status: statusElement
    });
    
    if (!distributionElement || !statusElement) {
        console.error('Chart elements not found!');
        return;
    }
    
    // ============================================
    // USER DISTRIBUTION CHART (DONUT)
    // ============================================
    const labelColor = '#7E8299';
    const primaryColor = '#009EF7';
    const successColor = '#50CD89';
    const infoColor = '#7239EA';
    const warningColor = '#FFC700';
    
    const distributionOptions = {
        series: [
            <?= $statistics['students'] ?>,
            <?= $statistics['teachers'] ?>,
            <?= $statistics['parents'] ?>,
            <?= $statistics['support_staff'] ?>
        ],
        chart: {
            fontFamily: 'inherit',
            type: 'donut',
            height: 350,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '14px',
                            fontWeight: '600',
                            color: labelColor,
                            offsetY: 20
                        },
                        value: {
                            show: true,
                            fontSize: '26px',
                            fontWeight: '700',
                            color: '#3F4254',
                            offsetY: -20,
                            formatter: function(val) {
                                return val;
                            }
                        },
                        total: {
                            show: true,
                            showAlways: true,
                            label: 'Total Users',
                            fontSize: '14px',
                            fontWeight: '600',
                            color: labelColor,
                            formatter: function(w) {
                                return <?= $statistics['total_users'] ?>;
                            }
                        }
                    }
                }
            }
        },
        colors: [primaryColor, successColor, infoColor, warningColor],
        labels: ['Students', 'Teachers', 'Parents', 'Support Staff'],
        legend: {
            show: true,
            position: 'bottom',
            fontSize: '13px',
            fontWeight: '500',
            labels: {
                colors: labelColor
            },
            markers: {
                width: 12,
                height: 12,
                radius: 12
            },
            itemMargin: {
                horizontal: 10,
                vertical: 10
            }
        },
        stroke: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            style: {
                fontSize: '12px'
            },
            y: {
                formatter: function(val) {
                    const total = <?= $statistics['total_users'] ?>;
                    const percentage = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                    return val + " (" + percentage + "%)";
                }
            }
        }
    };
    
    const distributionChart = new ApexCharts(distributionElement, distributionOptions);
    distributionChart.render();
    console.log('Distribution chart rendered');
    
    // ============================================
    // USER STATUS CHART (RADIAL BAR)
    // ============================================
    const activePercentage = <?= $statistics['percentages']['active'] ?>;
    const inactivePercentage = <?= $statistics['percentages']['inactive'] ?>;
    
    const statusOptions = {
        series: [activePercentage, inactivePercentage],
        chart: {
            fontFamily: 'inherit',
            type: 'radialBar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: '65%'
                },
                dataLabels: {
                    name: {
                        fontSize: '16px',
                        fontWeight: '600',
                        color: labelColor,
                        offsetY: -10
                    },
                    value: {
                        fontSize: '30px',
                        fontWeight: '700',
                        color: '#3F4254',
                        offsetY: 10,
                        formatter: function(val) {
                            return val + '%';
                        }
                    },
                    total: {
                        show: true,
                        fontSize: '14px',
                        fontWeight: '600',
                        label: 'Active Users',
                        color: labelColor,
                        formatter: function(w) {
                            return <?= $statistics['active_users'] ?> + ' / ' + <?= $statistics['total_users'] ?>;
                        }
                    }
                },
                track: {
                    background: '#F1F4F9',
                    strokeWidth: '100%'
                }
            }
        },
        colors: [successColor, warningColor],
        labels: ['Active', 'Inactive'],
        legend: {
            show: true,
            position: 'bottom',
            fontSize: '13px',
            fontWeight: '500',
            labels: {
                colors: labelColor
            },
            markers: {
                width: 12,
                height: 12,
                radius: 12
            },
            itemMargin: {
                horizontal: 10,
                vertical: 10
            }
        },
        stroke: {
            lineCap: 'round'
        }
    };
    
    const statusChart = new ApexCharts(statusElement, statusOptions);
    statusChart.render();
    console.log('Status chart rendered');
}
</script>
<!--end::Inline Scripts for Charts-->

<!--begin::Configure Departments Modal-->
<div class="modal fade" id="kt_modal_configure_departments" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Configure School Departments</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" style="cursor:pointer;">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <div id="dept-checkboxes-loading" class="text-center py-10">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-gray-600">Loading departments...</p>
                </div>
                <div id="dept-checkboxes-container" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <p class="text-gray-600 mb-0">Select departments to configure for this school:</p>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light-primary" id="btn-dept-select-all">Select All</button>
                            <button type="button" class="btn btn-sm btn-light" id="btn-dept-deselect-all">Deselect All</button>
                        </div>
                    </div>
                    <div id="dept-checkboxes" class="row g-4"></div>
                    <div id="dept-error" class="alert alert-danger d-none mt-4"></div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-departments" class="btn btn-primary d-none">
                    <span class="indicator-label">Save Departments</span>
                    <span class="indicator-progress" style="display:none;">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Configure Departments Modal-->

<!--begin::Configure Levels Modal-->
<div class="modal fade" id="kt_modal_configure_levels" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Configure School Levels</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" style="cursor:pointer;">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <div id="level-checkboxes-loading" class="text-center py-10">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-gray-600">Loading levels...</p>
                </div>
                <div id="level-checkboxes-container" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <p class="text-gray-600 mb-0">Select year levels to configure for this school:</p>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light-success" id="btn-level-select-all">Select All</button>
                            <button type="button" class="btn btn-sm btn-light" id="btn-level-deselect-all">Deselect All</button>
                        </div>
                    </div>
                    <div id="level-checkboxes" class="row g-4"></div>
                    <div id="level-error" class="alert alert-danger d-none mt-4"></div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-levels" class="btn btn-success d-none">
                    <span class="indicator-label">Save Levels</span>
                    <span class="indicator-progress" style="display:none;">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Configure Levels Modal-->

<!--begin::Add Stream Modal-->
<div class="modal fade" id="kt_modal_add_stream" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Add Streams</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body py-5 px-8">
                <p class="text-gray-600 fs-6 mb-5" id="stream-modal-level-name"></p>
                <div class="fv-row">
                    <label class="required fw-semibold fs-6 mb-2">Number of Streams</label>
                    <input type="number" id="stream-count-input" class="form-control form-control-solid" min="1" max="26" placeholder="e.g. 4">
                    <div class="text-danger fs-7 mt-2 d-none" id="stream-count-error"></div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-stream" class="btn btn-primary">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Add Streams
                    </span>
                    <span class="indicator-progress" style="display:none;">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>Adding...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Stream Modal-->

<!--begin::Add Core Subject Modal-->
<div class="modal fade" id="kt_modal_add_core_subject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Add Core Subjects</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <div id="core-subject-loading" class="text-center py-10">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-gray-600">Loading subjects...</p>
                </div>
                <div id="core-subject-container" class="d-none">
                    <div class="d-flex justify-content-end mb-3">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light-success" id="btn-core-select-all">Select All</button>
                            <button type="button" class="btn btn-sm btn-light" id="btn-core-deselect-all">Deselect All</button>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height:380px; overflow-y:auto;">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2">
                            <thead class="sticky-top bg-white">
                                <tr class="fw-bold text-gray-600 fs-7 text-uppercase border-bottom border-gray-200">
                                    <th class="w-30px"></th>
                                    <th>Subject</th>
                                    <th class="min-w-175px">Department <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody id="core-subject-rows"></tbody>
                        </table>
                    </div>
                    <div id="core-subject-error" class="alert alert-danger d-none mt-3"></div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-core-subject" class="btn btn-success d-none">
                    <span class="indicator-label">Save Core Subjects</span>
                    <span class="indicator-progress" style="display:none;">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Core Subject Modal-->

<!--begin::Edit Core Subject Modal-->
<div class="modal fade" id="kt_modal_edit_core_subject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Edit Core Subject</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body py-5 px-8">
                <div class="mb-5">
                    <label class="fw-semibold fs-6 mb-2 text-gray-600">Subject</label>
                    <div id="edit-core-subject-name" class="fw-bold fs-5 text-gray-800 p-3 bg-light rounded"></div>
                </div>
                <div class="fv-row">
                    <label class="required fw-semibold fs-6 mb-2">Department</label>
                    <select id="edit-core-subject-dept" class="form-select form-select-solid">
                        <option value="">-- Select Department --</option>
                    </select>
                </div>
                <div id="edit-core-subject-error" class="alert alert-danger d-none mt-3"></div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-edit-core-subject" class="btn btn-primary">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress" style="display:none;">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Edit Core Subject Modal-->

<!--begin::Add Optional Subjects Modal-->
<div class="modal fade" id="kt_modal_add_optional_subject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="fw-bold mb-1">Add Optional Subject Group</h2>
                    <p class="text-gray-600 fs-7 mb-0">Select <strong>2 or more</strong> subjects — students choose 1 from the group.</p>
                </div>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body py-8 px-lg-17">
                <div id="opt-subject-loading" class="text-center py-10">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-gray-600">Loading subjects...</p>
                </div>
                <div id="opt-subject-container" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span id="opt-selected-count" class="badge badge-light-info fs-7">0 selected (min. 2)</span>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light-info" id="btn-opt-select-all">Select All</button>
                            <button type="button" class="btn btn-sm btn-light" id="btn-opt-deselect-all">Deselect All</button>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height:380px; overflow-y:auto;">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2">
                            <thead class="sticky-top bg-white">
                                <tr class="fw-bold text-gray-600 fs-7 text-uppercase border-bottom border-gray-200">
                                    <th class="w-30px"></th>
                                    <th>Subject</th>
                                    <th class="min-w-175px">Department <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody id="opt-subject-rows"></tbody>
                        </table>
                    </div>
                    <div id="opt-subject-error" class="alert alert-danger d-none mt-3"></div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-optional-subject" class="btn btn-info d-none">
                    <span class="indicator-label">Add Option Group</span>
                    <span class="indicator-progress" style="display:none;">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Optional Subjects Modal-->

<!--begin::Edit School Subject Modal-->
<div class="modal fade" id="kt_modal_edit_sch_subject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-450px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold mb-0">Edit Subject</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body py-8 px-lg-17">
                <div class="mb-5">
                    <label class="form-label fw-semibold text-gray-700">Subject</label>
                    <input type="text" id="edit-sch-sub-name" class="form-control form-control-solid" readonly>
                </div>
                <div class="mb-5">
                    <label class="form-label fw-semibold text-gray-700">Department</label>
                    <select id="edit-sch-sub-dept" class="form-select form-select-solid">
                        <option value="">— No Department —</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?= (int)$dept['sch_dept_id'] ?>"><?= esc($dept['dept_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="form-label fw-semibold text-gray-700">Status</label>
                    <select id="edit-sch-sub-status" class="form-select form-select-solid">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-sch-subject" class="btn btn-primary">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress" style="display:none;">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Edit School Subject Modal-->

<!--begin::Add School Subject Modal-->
<div class="modal fade" id="kt_modal_add_school_subject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Add School Subject</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <p class="text-muted fs-7 mb-5">Select subjects to register for this school. These will appear in the timetable subject dropdown for the matching year level.</p>
                <div id="add-school-sub-loading" class="text-center py-10">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-gray-600">Loading available subjects...</p>
                </div>
                <div id="add-school-sub-container" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light-primary" id="btn-sch-sub-select-all">Select All</button>
                            <button type="button" class="btn btn-sm btn-light" id="btn-sch-sub-deselect-all">Deselect All</button>
                        </div>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" id="toggle-non-examinable" checked>
                            <label class="form-check-label fs-7 text-muted" for="toggle-non-examinable">Non-examinable only</label>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-2">
                            <thead class="sticky-top bg-white">
                                <tr class="fw-semibold text-gray-500 fs-8 text-uppercase">
                                    <th class="w-30px"></th>
                                    <th>Subject</th>
                                    <th class="w-120px">Level</th>
                                    <th class="w-100px text-center">Type</th>
                                </tr>
                            </thead>
                            <tbody id="add-school-sub-rows"></tbody>
                        </table>
                    </div>
                    <div id="add-school-sub-empty" class="alert alert-info d-none mt-3">
                        <i class="ki-duotone ki-information-5 fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        All available subjects for this school are already registered.
                    </div>
                    <div id="add-school-sub-error" class="alert alert-danger d-none mt-3"></div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-school-subject" class="btn btn-primary d-none">
                    <span class="indicator-label">Add Selected</span>
                    <span class="indicator-progress" style="display:none;">
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add School Subject Modal-->

<script>
(function() {
    'use strict';

    const schId = <?= (int) $school['sch_id'] ?>;
    const tabStorageKey = 'school_detail_tab_' + schId;
    const csrfName = '<?= csrf_token() ?>';
    let   csrfHash = '<?= csrf_hash() ?>';
    const totalDepartments = <?= (int)$totalDepartments ?>;
    const totalLevelsForCategory = <?= (int)$totalLevelsForCategory ?>;
    const schoolDepartments = <?= json_encode(array_map(fn($d) => ['sch_dept_id' => (int)$d['sch_dept_id'], 'dept_name' => $d['dept_name']], $departments)) ?>;
    let coreModalDepartments = schoolDepartments.slice();

    // ---- TAB PERSISTENCE ----
    document.addEventListener('DOMContentLoaded', function() {
        const hash   = window.location.hash;
        const stored = localStorage.getItem(tabStorageKey);
        const target = hash || stored;

        if (target) {
            const tabEl = document.querySelector('a[data-bs-toggle="tab"][href="' + target + '"]');
            if (tabEl) {
                new bootstrap.Tab(tabEl).show();
            }
        }

        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function(el) {
            el.addEventListener('shown.bs.tab', function(e) {
                const href = e.target.getAttribute('href');
                history.replaceState(null, null, href);
                localStorage.setItem(tabStorageKey, href);
            });
        });
    });

    // ---- CONFIGURE DEPARTMENTS MODAL ----
    const deptModal = document.getElementById('kt_modal_configure_departments');
    if (deptModal) {
        deptModal.addEventListener('show.bs.modal', function() {
            const loading   = document.getElementById('dept-checkboxes-loading');
            const container = document.getElementById('dept-checkboxes-container');
            const saveBtn   = document.getElementById('btn-save-departments');

            loading.classList.remove('d-none');
            container.classList.add('d-none');
            saveBtn.classList.add('d-none');

            fetch('<?= base_url('school/get-departments/') ?>' + schId)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    loading.classList.add('d-none');
                    container.classList.remove('d-none');
                    saveBtn.classList.remove('d-none');

                    if (!data.success || !data.departments || data.departments.length === 0) {
                        document.getElementById('dept-checkboxes').innerHTML =
                            '<p class="text-warning col-12">No departments available.</p>';
                        return;
                    }

                    let html = '';
                    data.departments.forEach(function(dept) {
                        const isConfigured = data.configured.indexOf(parseInt(dept.dept_id)) !== -1;
                        const attrs = isConfigured ? 'checked disabled' : '';
                        const badge = isConfigured
                            ? ' <span class="badge badge-light-success ms-2 fs-8">Added</span>'
                            : '';
                        html += '<div class="col-md-6 mb-3">' +
                            '<div class="form-check form-check-custom form-check-solid">' +
                            '<input class="form-check-input" type="checkbox" ' +
                            'name="departments[]" value="' + dept.dept_id + '" ' +
                            'id="dept_' + dept.dept_id + '" ' + attrs + '>' +
                            '<label class="form-check-label fw-semibold" for="dept_' + dept.dept_id + '">' +
                            dept.dept_name + badge +
                            '</label>' +
                            '</div>' +
                            '</div>';
                    });
                    document.getElementById('dept-checkboxes').innerHTML = html;
                })
                .catch(function() {
                    loading.classList.add('d-none');
                    container.classList.remove('d-none');
                    document.getElementById('dept-checkboxes').innerHTML =
                        '<div class="alert alert-danger col-12">Failed to load departments. Please close and try again.</div>';
                });
        });

        document.getElementById('btn-dept-select-all').addEventListener('click', function() {
            document.querySelectorAll('#dept-checkboxes input[type="checkbox"]:not([disabled])').forEach(function(cb) { cb.checked = true; });
        });

        document.getElementById('btn-dept-deselect-all').addEventListener('click', function() {
            document.querySelectorAll('#dept-checkboxes input[type="checkbox"]:not([disabled])').forEach(function(cb) { cb.checked = false; });
        });

        document.getElementById('btn-save-departments').addEventListener('click', function() {
            const btn      = this;
            const label    = btn.querySelector('.indicator-label');
            const progress = btn.querySelector('.indicator-progress');
            const errorDiv = document.getElementById('dept-error');

            errorDiv.classList.add('d-none');

            const selected = document.querySelectorAll('#dept-checkboxes input[type="checkbox"]:checked:not([disabled])');
            if (selected.length === 0) {
                errorDiv.textContent = 'Please select at least one new department to add.';
                errorDiv.classList.remove('d-none');
                return;
            }

            btn.disabled = true;
            label.style.display = 'none';
            progress.style.display = 'inline-flex';

            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            selected.forEach(function(cb) {
                formData.append('departments[]', cb.value);
            });

            fetch('<?= base_url('school/save-departments/') ?>' + schId, {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';

                if (data.success) {
                    bootstrap.Modal.getInstance(deptModal).hide();
                    renderDepartmentsSection(data.departments);
                    Swal.fire({
                        icon: 'success',
                        title: 'Departments Saved',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.remove('d-none');
                }
            })
            .catch(function() {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('d-none');
            });
        });
    }

    // ---- CONFIGURE LEVELS MODAL ----
    const levelModal = document.getElementById('kt_modal_configure_levels');
    if (levelModal) {
        levelModal.addEventListener('show.bs.modal', function() {
            const loading   = document.getElementById('level-checkboxes-loading');
            const container = document.getElementById('level-checkboxes-container');
            const saveBtn   = document.getElementById('btn-save-levels');

            loading.classList.remove('d-none');
            container.classList.add('d-none');
            saveBtn.classList.add('d-none');

            fetch('<?= base_url('school/get-levels/') ?>' + schId)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    loading.classList.add('d-none');
                    container.classList.remove('d-none');
                    saveBtn.classList.remove('d-none');

                    if (!data.success || !data.levels || data.levels.length === 0) {
                        document.getElementById('level-checkboxes').innerHTML =
                            '<p class="text-warning col-12">No levels available for this school category.</p>';
                        return;
                    }

                    let html = '';
                    data.levels.forEach(function(level) {
                        const isConfigured = data.configured.indexOf(parseInt(level.level_id)) !== -1;
                        const attrs = isConfigured ? 'checked disabled' : '';
                        const badge = isConfigured
                            ? ' <span class="badge badge-light-success ms-2 fs-8">Added</span>'
                            : '';
                        html += '<div class="col-md-6 mb-3">' +
                            '<div class="form-check form-check-custom form-check-solid">' +
                            '<input class="form-check-input" type="checkbox" ' +
                            'name="levels[]" value="' + level.level_id + '" ' +
                            'id="level_' + level.level_id + '" ' + attrs + '>' +
                            '<label class="form-check-label fw-semibold" for="level_' + level.level_id + '">' +
                            level.level_name + badge +
                            '</label>' +
                            '</div>' +
                            '</div>';
                    });
                    document.getElementById('level-checkboxes').innerHTML = html;
                })
                .catch(function() {
                    loading.classList.add('d-none');
                    container.classList.remove('d-none');
                    document.getElementById('level-checkboxes').innerHTML =
                        '<div class="alert alert-danger col-12">Failed to load levels. Please close and try again.</div>';
                });
        });

        document.getElementById('btn-level-select-all').addEventListener('click', function() {
            document.querySelectorAll('#level-checkboxes input[type="checkbox"]:not([disabled])').forEach(function(cb) { cb.checked = true; });
        });

        document.getElementById('btn-level-deselect-all').addEventListener('click', function() {
            document.querySelectorAll('#level-checkboxes input[type="checkbox"]:not([disabled])').forEach(function(cb) { cb.checked = false; });
        });

        document.getElementById('btn-save-levels').addEventListener('click', function() {
            const btn      = this;
            const label    = btn.querySelector('.indicator-label');
            const progress = btn.querySelector('.indicator-progress');
            const errorDiv = document.getElementById('level-error');

            errorDiv.classList.add('d-none');

            const selected = document.querySelectorAll('#level-checkboxes input[type="checkbox"]:checked:not([disabled])');
            if (selected.length === 0) {
                errorDiv.textContent = 'Please select at least one new level to add.';
                errorDiv.classList.remove('d-none');
                return;
            }

            btn.disabled = true;
            label.style.display = 'none';
            progress.style.display = 'inline-flex';

            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            selected.forEach(function(cb) {
                formData.append('levels[]', cb.value);
            });

            fetch('<?= base_url('school/save-levels/') ?>' + schId, {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';

                if (data.success) {
                    bootstrap.Modal.getInstance(levelModal).hide();
                    const accordion = document.getElementById('kt_accordion_curriculum');
                    if (accordion) {
                        appendLevelsToAccordion(data.newLevels, accordion);
                        updateAddLevelBtn(data.levels.length);
                    } else {
                        renderLevelsSection(data.levels);
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Levels Saved',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.remove('d-none');
                }
            })
            .catch(function() {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('d-none');
            });
        });
    }

    // ---- HELPERS ----
    function updateAddDeptBtn(configuredCount) {
        const btn = document.getElementById('btn-add-department');
        if (btn) btn.disabled = configuredCount >= totalDepartments;
    }

    function updateAddLevelBtn(configuredCount) {
        const btn = document.getElementById('btn-add-level');
        if (btn) btn.disabled = configuredCount >= totalLevelsForCategory;
    }

    // ---- DEPT STATUS UPDATE (event delegation) ----
    document.addEventListener('click', function(e) {
        const statusOption = e.target.closest('.dept-status-option');
        if (!statusOption) return;
        e.preventDefault();

        const deptId = statusOption.dataset.deptId;
        const status = statusOption.dataset.status;

        const formData = new FormData();
        formData.append(csrfName, csrfHash);
        formData.append('status', status);

        fetch('<?= base_url('school/update-department/') ?>' + deptId, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    csrfHash = data.csrf_hash || csrfHash;
                    const card = document.getElementById('dept-card-' + deptId);
                    if (card) {
                        const badge = card.querySelector('.dept-status-badge');
                        if (badge) {
                            badge.textContent = status;
                            badge.className = 'badge mt-1 dept-status-badge ' + (status === 'Established' ? 'badge-light-success' : 'badge-light-warning');
                        }
                    }
                    Swal.fire({ icon: 'success', title: 'Status Updated', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            });
    });

    // ---- DEPT DELETE (event delegation) ----
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-dept-delete');
        if (!btn) return;

        const deptId   = btn.dataset.deptId;
        const deptName = btn.dataset.deptName;

        Swal.fire({
            title: 'Remove Department?',
            text: 'Remove "' + deptName + '" from this school?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f1416c',
        }).then(function(result) {
            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('school/delete-department/') ?>' + deptId, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        csrfHash = data.csrf_hash || csrfHash;
                        const card = document.getElementById('dept-card-' + deptId);
                        if (card) card.remove();

                        const remaining = document.querySelectorAll('[id^="dept-card-"]').length;
                        updateAddDeptBtn(remaining);

                        if (remaining === 0) {
                            const section = document.getElementById('departments-section');
                            if (section) {
                                section.innerHTML =
                                    '<div class="alert alert-warning d-flex align-items-center p-5">' +
                                    '<i class="ki-duotone ki-information-5 fs-2hx text-warning me-4">' +
                                    '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
                                    '</i>' +
                                    '<div class="d-flex flex-column">' +
                                    '<h4 class="mb-1 text-dark">School Departments Not Configured</h4>' +
                                    '<span>Configure school departments to organize subjects and staff. ' +
                                    '<button type="button" class="btn btn-link p-0 fw-bold align-baseline" data-bs-toggle="modal" data-bs-target="#kt_modal_configure_departments">Configure now</button>' +
                                    '</span></div></div>';
                            }
                        }

                        Swal.fire({ icon: 'success', title: 'Removed', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Cannot Delete Department', text: data.message, confirmButtonText: 'OK', confirmButtonColor: '#f1416c' });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                });
        });
    });

    // ---- LEVEL DELETE (event delegation) ----
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-level-delete');
        if (!btn) return;

        const levelId   = btn.dataset.levelId;
        const levelName = btn.dataset.levelName;

        Swal.fire({
            title: 'Remove Level?',
            text: 'Remove "' + levelName + '" from this school?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f1416c',
        }).then(function(result) {
            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('school/delete-level/') ?>' + levelId, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        csrfHash = data.csrf_hash || csrfHash;
                        const accordionItem = btn.closest('.mb-5');
                        if (accordionItem) accordionItem.remove();

                        const remaining = document.querySelectorAll('#kt_accordion_curriculum > .mb-5').length;
                        updateAddLevelBtn(remaining);

                        if (remaining === 0) {
                            const accordion = document.getElementById('kt_accordion_curriculum');
                            if (accordion) {
                                accordion.outerHTML =
                                    '<div id="levels-empty-state" class="alert alert-danger d-flex align-items-center p-5">' +
                                    '<i class="ki-duotone ki-information-5 fs-2hx text-danger me-4">' +
                                    '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
                                    '</i>' +
                                    '<div class="d-flex flex-column">' +
                                    '<h4 class="mb-1 text-dark">School Levels Not Configured</h4>' +
                                    '<span>School levels are required for system operation. ' +
                                    '<button type="button" class="btn btn-link p-0 fw-bold align-baseline" data-bs-toggle="modal" data-bs-target="#kt_modal_configure_levels">Configure now</button>' +
                                    '</span></div></div>';
                            }
                        }

                        Swal.fire({ icon: 'success', title: 'Removed', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Cannot Delete Level', text: data.message, confirmButtonText: 'OK', confirmButtonColor: '#f1416c' });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                });
        });
    });

    function appendLevelsToAccordion(newLevels, accordion) {
        if (!newLevels || newLevels.length === 0) return;
        const existingCount = accordion.querySelectorAll(':scope > .mb-5').length;
        const trashIcon = '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';
        const noStreamsAlert =
            '<div class="alert alert-info d-flex align-items-center p-4 mt-3">' +
            '<i class="ki-duotone ki-information fs-2x text-info me-3">' +
            '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
            '</i><span>No streams configured for this level.</span></div>';

        newLevels.forEach(function(level, i) {
            const index = existingCount + i;
            const item = document.createElement('div');
            item.className = 'mb-5';
            item.innerHTML =
                '<div class="d-flex align-items-center">' +
                '<div class="accordion-header py-3 d-flex flex-grow-1 collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_' + index + '">' +
                '<span class="accordion-icon"><i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i></span>' +
                '<h3 class="fs-4 fw-semibold mb-0 ms-4">' + level.level_name +
                '<span class="badge badge-light-primary ms-3">0 Stream(s)</span></h3>' +
                '</div>' +
                '<button type="button" class="btn btn-sm btn-light-success ms-2 btn-add-stream"' +
                ' data-level-id="' + level.sch_level_id + '" data-level-name="' + level.level_name + '">' +
                '<i class="ki-duotone ki-plus fs-5"><span class="path1"></span><span class="path2"></span></i> Add Stream</button>' +
                '<button type="button" class="btn btn-sm btn-light-danger ms-2 btn-level-delete"' +
                ' data-level-id="' + level.sch_level_id + '" data-level-name="' + level.level_name + '">' +
                trashIcon + '</button>' +
                '</div>' +
                '<div id="kt_accordion_' + index + '" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_curriculum">' +
                noStreamsAlert + '</div>';
            accordion.appendChild(item);
        });
    }

    function renderLevelsSection(levels) {
        const content = document.getElementById('levels-section-content');
        if (!content || !levels || levels.length === 0) return;

        const trashIcon = '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';
        const noStreamsAlert =
            '<div class="alert alert-info d-flex align-items-center p-4 mt-3">' +
            '<i class="ki-duotone ki-information fs-2x text-info me-3">' +
            '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
            '</i><span>No streams configured for this level.</span></div>';

        let html = '<div class="accordion accordion-icon-toggle" id="kt_accordion_curriculum">';
        levels.forEach(function(level, index) {
            const showClass = index === 0 ? 'show' : '';
            html += '<div class="mb-5">' +
                '<div class="d-flex align-items-center">' +
                '<div class="accordion-header py-3 d-flex flex-grow-1 collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_' + index + '">' +
                '<span class="accordion-icon">' +
                '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>' +
                '</span>' +
                '<h3 class="fs-4 fw-semibold mb-0 ms-4">' + level.level_name +
                '<span class="badge badge-light-primary ms-3">0 Stream(s)</span>' +
                '</h3>' +
                '</div>' +
                '<button type="button" class="btn btn-sm btn-light-success ms-2 btn-add-stream"' +
                ' data-level-id="' + level.sch_level_id + '" data-level-name="' + level.level_name + '">' +
                '<i class="ki-duotone ki-plus fs-5"><span class="path1"></span><span class="path2"></span></i> Add Stream</button>' +
                '<button type="button" class="btn btn-sm btn-light-danger ms-2 btn-level-delete"' +
                ' data-level-id="' + level.sch_level_id + '" data-level-name="' + level.level_name + '">' +
                trashIcon + '</button>' +
                '</div>' +
                '<div id="kt_accordion_' + index + '" class="fs-6 collapse ' + showClass + ' ps-10" data-bs-parent="#kt_accordion_curriculum">' +
                noStreamsAlert +
                '</div>' +
                '</div>';
        });
        html += '</div>';

        content.innerHTML = html;
        updateAddLevelBtn(levels.length);
    }

    function renderDepartmentsSection(departments) {
        const section = document.getElementById('departments-section');
        if (!section || !departments || departments.length === 0) return;

        const trashIcon = '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';

        let html = '<div class="row g-5" id="departments-cards">';
        departments.forEach(function(dept) {
            const theme  = dept.dept_theme || 'primary';
            const status = dept.dept_status || 'Established';
            const badgeCls = status === 'Established' ? 'badge-light-success' : 'badge-light-warning';
            const deptId = dept.sch_dept_id;

            html += '<div class="col-md-4" id="dept-card-' + deptId + '">' +
                '<div class="card border-0 bg-light-' + theme + ' h-100">' +
                '<div class="card-body p-5">' +
                '<div class="d-flex align-items-start mb-3">' +
                (dept.dept_icon || '') +
                '<div class="flex-grow-1 ms-3">' +
                '<div class="fw-bold text-gray-800 fs-5">' + dept.dept_name + '</div>' +
                '<span class="badge ' + badgeCls + ' dept-status-badge mt-1">' + status + '</span>' +
                '</div>' +
                '</div>' +
                '<div class="d-flex gap-2 justify-content-end">' +
                '<div class="dropdown">' +
                '<button type="button" class="btn btn-sm btn-light-primary dropdown-toggle" data-bs-toggle="dropdown">Edit Status</button>' +
                '<ul class="dropdown-menu">' +
                '<li><a class="dropdown-item dept-status-option" href="#" data-dept-id="' + deptId + '" data-status="Established">Established</a></li>' +
                '<li><a class="dropdown-item dept-status-option" href="#" data-dept-id="' + deptId + '" data-status="Non Established">Non Established</a></li>' +
                '</ul>' +
                '</div>' +
                '<button type="button" class="btn btn-sm btn-light-danger btn-dept-delete" data-dept-id="' + deptId + '" data-dept-name="' + dept.dept_name + '">' +
                trashIcon + ' Delete' +
                '</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
        });
        html += '</div>';
        section.innerHTML = html;
        updateAddDeptBtn(departments.length);
    }

    // ---- ADD STREAM ----
    let currentStreamLevelId   = null;
    let currentStreamAccordionItem = null;

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-add-stream');
        if (!btn) return;
        e.stopPropagation();

        currentStreamLevelId       = btn.dataset.levelId;
        currentStreamAccordionItem = btn.closest('.mb-5');

        document.getElementById('stream-modal-level-name').textContent = 'Level: ' + btn.dataset.levelName;
        document.getElementById('stream-count-input').value = '';
        document.getElementById('stream-count-error').classList.add('d-none');

        bootstrap.Modal.getOrCreateInstance(document.getElementById('kt_modal_add_stream')).show();
    });

    document.getElementById('btn-save-stream').addEventListener('click', function() {
        const btn      = this;
        const label    = btn.querySelector('.indicator-label');
        const progress = btn.querySelector('.indicator-progress');
        const countInput = document.getElementById('stream-count-input');
        const errorDiv   = document.getElementById('stream-count-error');

        errorDiv.classList.add('d-none');

        const raw = countInput.value.trim();
        const num = parseInt(raw, 10);
        if (!raw || isNaN(num) || num < 1 || String(num) !== raw) {
            errorDiv.textContent = 'Please enter a valid whole number (e.g. 4).';
            errorDiv.classList.remove('d-none');
            return;
        }

        const naming = (document.querySelector('input[name="stream_naming"]:checked') || {}).value || 'numerical';

        btn.disabled = true;
        label.style.display = 'none';
        progress.style.display = 'inline-flex';

        const formData = new FormData();
        formData.append(csrfName, csrfHash);
        formData.append('count', num);
        formData.append('naming', naming);

        fetch('<?= base_url('school/add-stream/') ?>' + currentStreamLevelId, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';

                if (data.success) {
                    csrfHash = data.csrf_hash || csrfHash;
                    bootstrap.Modal.getInstance(document.getElementById('kt_modal_add_stream')).hide();

                    appendStreamsToLevel(data.streams, currentStreamAccordionItem);

                    const badge = currentStreamAccordionItem.querySelector('.badge-light-primary');
                    if (badge) badge.textContent = data.totalStreams + ' Stream(s)';

                    Swal.fire({ icon: 'success', title: 'Streams Added', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.remove('d-none');
                }
            })
            .catch(function() {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('d-none');
            });
    });

    function appendStreamsToLevel(streams, accordionItem) {
        if (!streams || streams.length === 0 || !accordionItem) return;

        const collapsePanel = accordionItem.querySelector('.collapse');
        if (!collapsePanel) return;

        // Remove "no streams" alert if present
        const noAlert = collapsePanel.querySelector('.alert-info');
        if (noAlert) noAlert.remove();

        // Get or create .py-5 container
        let container = collapsePanel.querySelector('.py-5');
        if (!container) {
            container = document.createElement('div');
            container.className = 'py-5';
            collapsePanel.appendChild(container);
        }

        const trashIcon5 = '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';
        const plusIcon4  = '<i class="ki-duotone ki-plus fs-4"><span class="path1"></span><span class="path2"></span></i>';

        streams.forEach(function(stream) {
            const div = document.createElement('div');
            div.className = 'mb-7 pb-5';
            div.id = 'stream-block-' + stream.stream_id;
            div.innerHTML =
                '<div class="d-flex align-items-center justify-content-between mb-5 p-4 bg-light-success rounded">' +
                '<div class="d-flex align-items-center">' +
                '<i class="ki-duotone ki-element-11 fs-2 text-success me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>' +
                '<h4 class="fw-bold text-gray-800 mb-0">' + stream.stream_name + '</h4>' +
                '</div>' +
                '<div class="d-flex gap-2 align-items-center">' +
                '<span class="badge badge-success stream-core-badge"><i class="ki-duotone ki-check-circle fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>0 Core</span>' +
                '<span class="badge badge-info stream-optional-badge"><i class="ki-duotone ki-add-item fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>0 Optional</span>' +
                '<button type="button" class="btn btn-icon btn-sm btn-light-danger btn-stream-delete" data-stream-id="' + stream.stream_id + '" data-stream-name="' + stream.stream_name + '">' + trashIcon5 + '</button>' +
                '</div>' +
                '</div>' +
                '<div class="row g-5">' +
                '<div class="col-md-6">' +
                '<div class="d-flex align-items-center justify-content-between mb-4">' +
                '<h5 class="fw-bold text-gray-800 mb-0"><i class="ki-duotone ki-abstract-26 fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>Core Subjects</h5>' +
                '<button type="button" class="btn btn-icon btn-sm btn-light-success btn-add-core-subject" data-stream-id="' + stream.stream_id + '">' + plusIcon4 + '</button>' +
                '</div>' +
                '<div id="core-subjects-' + stream.stream_id + '"><p class="text-gray-600 fs-7">No core subjects configured</p></div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="d-flex align-items-center justify-content-between mb-4">' +
                '<h5 class="fw-bold text-gray-800 mb-0"><i class="ki-duotone ki-category fs-2 text-info me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>Optional Subjects</h5>' +
                '<button type="button" class="btn btn-icon btn-sm btn-light-info btn-add-optional-subject" data-stream-id="' + stream.stream_id + '">' + plusIcon4 + '</button>' +
                '</div>' +
                '<div id="optional-subjects-' + stream.stream_id + '"><p class="text-gray-600 fs-7">No optional subjects configured</p></div>' +
                '</div>' +
                '</div>';
            container.appendChild(div);
        });

        // Fix border-bottom: all but last get it
        const allDivs = container.querySelectorAll(':scope > .mb-7');
        allDivs.forEach(function(d, i) {
            d.classList.toggle('border-bottom', i < allDivs.length - 1);
            d.classList.toggle('border-gray-300', i < allDivs.length - 1);
        });

        // Expand this accordion item so user sees the new streams
        const collapseEl = new bootstrap.Collapse(collapsePanel, { toggle: false });
        collapseEl.show();
    }

    // ---- STREAM DELETE ----
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-stream-delete');
        if (!btn) return;

        const streamId   = btn.dataset.streamId;
        const streamName = btn.dataset.streamName;

        Swal.fire({
            title: 'Delete Stream?',
            text: 'Delete "' + streamName + '"? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f1416c',
        }).then(function(result) {
            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('school/delete-stream/') ?>' + streamId, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        csrfHash = data.csrf_hash || csrfHash;

                        const block = document.getElementById('stream-block-' + streamId);
                        const pyContainer = block ? block.closest('.py-5') : null;
                        if (block) block.remove();

                        if (pyContainer) {
                            const remaining = pyContainer.querySelectorAll(':scope > .mb-7').length;
                            const accordionItem = pyContainer.closest('.mb-5');
                            if (accordionItem) {
                                const badge = accordionItem.querySelector('.badge-light-primary');
                                if (badge) badge.textContent = remaining + ' Stream(s)';
                            }
                            if (remaining === 0) {
                                const collapsePanel = pyContainer.closest('.collapse');
                                pyContainer.remove();
                                if (collapsePanel) {
                                    collapsePanel.insertAdjacentHTML('beforeend',
                                        '<div class="alert alert-info d-flex align-items-center p-4 mt-3">' +
                                        '<i class="ki-duotone ki-information fs-2x text-info me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>' +
                                        '<span>No streams configured for this level.</span></div>'
                                    );
                                }
                            } else {
                                const allBlocks = pyContainer.querySelectorAll(':scope > .mb-7');
                                allBlocks.forEach(function(d, i) {
                                    d.classList.toggle('border-bottom', i < allBlocks.length - 1);
                                    d.classList.toggle('border-gray-300', i < allBlocks.length - 1);
                                });
                            }
                        }

                        Swal.fire({ icon: 'success', title: 'Deleted', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Cannot Delete Stream', text: data.message, confirmButtonText: 'OK', confirmButtonColor: '#f1416c' });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                });
        });
    });

    // ---- ADD CORE SUBJECT ----
    let currentCoreStreamId = null;

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-add-core-subject');
        if (!btn) return;

        currentCoreStreamId = btn.dataset.streamId;

        const loading    = document.getElementById('core-subject-loading');
        const container  = document.getElementById('core-subject-container');
        const saveBtn    = document.getElementById('btn-save-core-subject');
        const errorDiv   = document.getElementById('core-subject-error');

        loading.classList.remove('d-none');
        container.classList.add('d-none');
        saveBtn.classList.add('d-none');
        errorDiv.classList.add('d-none');

        bootstrap.Modal.getOrCreateInstance(document.getElementById('kt_modal_add_core_subject')).show();

        fetch('<?= base_url('school/get-stream-subjects/') ?>' + currentCoreStreamId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                loading.classList.add('d-none');
                container.classList.remove('d-none');
                saveBtn.classList.remove('d-none');

                if (!data.success) {
                    document.getElementById('core-subject-checkboxes').innerHTML =
                        '<p class="text-danger col-12">' + (data.message || 'Failed to load subjects.') + '</p>';
                    return;
                }

                // Update coreModalDepartments
                if (data.departments && data.departments.length > 0) {
                    coreModalDepartments = data.departments;
                }

                const subjects      = data.subjects || [];
                const configuredIds = data.configured_subject_ids || [];

                if (subjects.length === 0) {
                    document.getElementById('core-subject-rows').innerHTML =
                        '<tr><td colspan="3" class="text-warning py-4">No subjects available for this level.</td></tr>';
                } else {
                    let deptOptions = '<option value="">-- Select --</option>';
                    coreModalDepartments.forEach(function(dept) {
                        deptOptions += '<option value="' + dept.sch_dept_id + '">' + dept.dept_name + '</option>';
                    });

                    let rows = '';
                    subjects.forEach(function(subj) {
                        const isConfigured = configuredIds.indexOf(parseInt(subj.subject_id)) !== -1;
                        if (isConfigured) {
                            rows += '<tr class="text-muted">' +
                                '<td><input class="form-check-input" type="checkbox" checked disabled></td>' +
                                '<td class="fw-semibold">' + subj.subject_name + ' <span class="badge badge-light-success ms-1 fs-8">Added</span></td>' +
                                '<td><span class="text-gray-400 fs-7">Already configured</span></td>' +
                                '</tr>';
                        } else {
                            rows += '<tr>' +
                                '<td><input class="form-check-input core-subject-check" type="checkbox" value="' + subj.subject_id + '" id="cs_' + subj.subject_id + '"></td>' +
                                '<td><label class="fw-semibold cursor-pointer" for="cs_' + subj.subject_id + '">' + subj.subject_name + '</label></td>' +
                                '<td><select class="form-select form-select-sm form-select-solid core-dept-select" data-subject-id="' + subj.subject_id + '">' + deptOptions + '</select></td>' +
                                '</tr>';
                        }
                    });
                    document.getElementById('core-subject-rows').innerHTML = rows;
                }
            })
            .catch(function() {
                document.getElementById('core-subject-loading').classList.add('d-none');
                document.getElementById('core-subject-container').classList.remove('d-none');
                document.getElementById('core-subject-checkboxes').innerHTML =
                    '<div class="alert alert-danger col-12">Failed to load subjects. Please close and try again.</div>';
            });
    });

    document.getElementById('btn-core-select-all').addEventListener('click', function() {
        document.querySelectorAll('#core-subject-rows .core-subject-check').forEach(function(cb) { cb.checked = true; });
    });

    document.getElementById('btn-core-deselect-all').addEventListener('click', function() {
        document.querySelectorAll('#core-subject-rows .core-subject-check').forEach(function(cb) { cb.checked = false; });
    });

    document.getElementById('btn-save-core-subject').addEventListener('click', function() {
        const btn      = this;
        const label    = btn.querySelector('.indicator-label');
        const progress = btn.querySelector('.indicator-progress');
        const errorDiv = document.getElementById('core-subject-error');

        errorDiv.classList.add('d-none');

        // Collect checked rows and validate each has a department
        const checkedRows = [];
        let missingDept = false;
        document.querySelectorAll('#core-subject-rows .core-subject-check:checked').forEach(function(cb) {
            const row = cb.closest('tr');
            const deptSel = row ? row.querySelector('.core-dept-select') : null;
            const deptVal = deptSel ? deptSel.value : '';
            if (!deptVal) {
                missingDept = true;
                if (deptSel) deptSel.classList.add('is-invalid');
            } else {
                if (deptSel) deptSel.classList.remove('is-invalid');
                checkedRows.push({ subjectId: cb.value, deptId: deptVal });
            }
        });

        if (checkedRows.length === 0 && !missingDept) {
            errorDiv.textContent = 'Please select at least one subject.';
            errorDiv.classList.remove('d-none');
            return;
        }
        if (missingDept) {
            errorDiv.textContent = 'Please select a department for each selected subject.';
            errorDiv.classList.remove('d-none');
            return;
        }

        btn.disabled = true;
        label.style.display = 'none';
        progress.style.display = 'inline-flex';

        const formData = new FormData();
        formData.append(csrfName, csrfHash);
        checkedRows.forEach(function(item) {
            formData.append('subjectIds[]', item.subjectId);
            formData.append('subjectDepts[' + item.subjectId + ']', item.deptId);
        });

        fetch('<?= base_url('school/add-core-subject/') ?>' + currentCoreStreamId, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';

                if (data.success) {
                    csrfHash = data.csrf_hash || csrfHash;
                    bootstrap.Modal.getInstance(document.getElementById('kt_modal_add_core_subject')).hide();

                    const coreContainer = document.getElementById('core-subjects-' + currentCoreStreamId);
                    if (coreContainer && data.subjects && data.subjects.length > 0) {
                        const emptyMsg = coreContainer.querySelector('p');
                        if (emptyMsg) emptyMsg.remove();

                        let wrapper = coreContainer.querySelector('.d-flex.flex-column.gap-2');
                        if (!wrapper) {
                            wrapper = document.createElement('div');
                            wrapper.className = 'd-flex flex-column gap-2';
                            coreContainer.appendChild(wrapper);
                        }

                        data.subjects.forEach(function(subj) {
                            const row = document.createElement('div');
                            row.className = 'd-flex align-items-center justify-content-between';
                            row.id = 'core-sub-' + subj.stream_core_sub_id;
                            row.innerHTML =
                                '<div class="d-flex align-items-center">' +
                                '<i class="ki-duotone ki-check fs-3 text-success me-2"><span class="path1"></span><span class="path2"></span></i>' +
                                '<span class="text-gray-700 fw-semibold fs-6">' + subj.subject_name + '</span>' +
                                '</div>' +
                                '<div class="d-flex gap-1">' +
                                '<button type="button" class="btn btn-icon btn-xs btn-light-primary btn-edit-core-subject"' +
                                ' data-core-id="' + subj.stream_core_sub_id + '"' +
                                ' data-dept-id="' + (subj.sch_dept_id_fk || '') + '"' +
                                ' data-subject-name="' + subj.subject_name + '">' +
                                '<i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i></button>' +
                                '<button type="button" class="btn btn-icon btn-xs btn-light-danger btn-delete-core-subject"' +
                                ' data-core-id="' + subj.stream_core_sub_id + '"' +
                                ' data-subject-name="' + subj.subject_name + '"' +
                                ' data-stream-id="' + currentCoreStreamId + '">' +
                                '<i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>' +
                                '</div>';
                            wrapper.appendChild(row);
                        });
                    }

                    const streamBlock = document.getElementById('stream-block-' + currentCoreStreamId);
                    if (streamBlock && data.coreTotal !== undefined) {
                        const badge = streamBlock.querySelector('.stream-core-badge');
                        if (badge) badge.innerHTML = '<i class="ki-duotone ki-check-circle fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>' + data.coreTotal + ' Core';
                    }

                    appendNewSchSubjects(data.newSchSubjects);

                    if (data.activated) {
                        Swal.fire({
                            icon: 'success',
                            title: 'School Activated!',
                            html: data.message + '<br><small class="text-muted">All levels and streams now have core subjects. This school is now <strong>Active</strong> and open for admissions.</small>',
                            confirmButtonText: 'Great!',
                            confirmButtonColor: '#50CD89',
                        });
                    } else {
                        Swal.fire({ icon: 'success', title: 'Core Subjects Added', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                    }
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.remove('d-none');
                }
            })
            .catch(function() {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('d-none');
            });
    });

    // ---- DELETE CORE SUBJECT ----
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-core-subject');
        if (!btn) return;

        const coreId      = btn.dataset.coreId;
        const subjectName = btn.dataset.subjectName;
        const streamId    = btn.dataset.streamId;

        Swal.fire({
            title: 'Remove Core Subject?',
            text: 'Remove "' + subjectName + '" from this stream?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f1416c',
        }).then(function(result) {
            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('school/delete-core-subject/') ?>' + coreId, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        csrfHash = data.csrf_hash || csrfHash;

                        const row = document.getElementById('core-sub-' + coreId);
                        if (row) row.remove();

                        const streamBlock = document.getElementById('stream-block-' + streamId);
                        if (streamBlock && data.coreTotal !== undefined) {
                            const badge = streamBlock.querySelector('.stream-core-badge');
                            if (badge) badge.innerHTML = '<i class="ki-duotone ki-check-circle fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>' + data.coreTotal + ' Core';
                        }

                        const coreContainer = document.getElementById('core-subjects-' + streamId);
                        if (coreContainer) {
                            const wrapper = coreContainer.querySelector('.d-flex.flex-column.gap-2');
                            if (wrapper && wrapper.children.length === 0) {
                                wrapper.remove();
                                coreContainer.innerHTML = '<p class="text-gray-600 fs-7">No core subjects configured</p>';
                            }
                        }

                        Swal.fire({ icon: 'success', title: 'Removed', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                });
        });
    });

    // ---- EDIT CORE SUBJECT ----
    let currentEditCoreId = null;

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-edit-core-subject');
        if (!btn) return;

        currentEditCoreId   = btn.dataset.coreId;
        const subjectName   = btn.dataset.subjectName;
        const currentDeptId = parseInt(btn.dataset.deptId || '0');

        document.getElementById('edit-core-subject-name').textContent = subjectName;
        document.getElementById('edit-core-subject-error').classList.add('d-none');

        const deptSelect = document.getElementById('edit-core-subject-dept');
        deptSelect.innerHTML = '<option value="">-- Select Department --</option>';
        coreModalDepartments.forEach(function(dept) {
            const opt = document.createElement('option');
            opt.value = dept.sch_dept_id;
            opt.textContent = dept.dept_name;
            if (parseInt(dept.sch_dept_id) === currentDeptId) opt.selected = true;
            deptSelect.appendChild(opt);
        });

        bootstrap.Modal.getOrCreateInstance(document.getElementById('kt_modal_edit_core_subject')).show();
    });

    document.getElementById('btn-save-edit-core-subject').addEventListener('click', function() {
        const btn      = this;
        const label    = btn.querySelector('.indicator-label');
        const progress = btn.querySelector('.indicator-progress');
        const errorDiv = document.getElementById('edit-core-subject-error');

        errorDiv.classList.add('d-none');

        const deptId = document.getElementById('edit-core-subject-dept').value;
        if (!deptId) {
            errorDiv.textContent = 'Please select a department.';
            errorDiv.classList.remove('d-none');
            return;
        }

        btn.disabled = true;
        label.style.display = 'none';
        progress.style.display = 'inline-flex';

        const formData = new FormData();
        formData.append(csrfName, csrfHash);
        formData.append('dept_id', deptId);

        fetch('<?= base_url('school/edit-core-subject/') ?>' + currentEditCoreId, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';

                if (data.success) {
                    csrfHash = data.csrf_hash || csrfHash;
                    bootstrap.Modal.getInstance(document.getElementById('kt_modal_edit_core_subject')).hide();

                    const editBtn = document.querySelector('.btn-edit-core-subject[data-core-id="' + currentEditCoreId + '"]');
                    if (editBtn) editBtn.dataset.deptId = deptId;

                    Swal.fire({ icon: 'success', title: 'Updated', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.remove('d-none');
                }
            })
            .catch(function() {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('d-none');
            });
    });

    // ---- ADD OPTIONAL SUBJECT ----
    let currentOptionalStreamId = null;

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-add-optional-subject');
        if (!btn) return;

        currentOptionalStreamId = btn.dataset.streamId;

        const loading   = document.getElementById('opt-subject-loading');
        const container = document.getElementById('opt-subject-container');
        const saveBtn   = document.getElementById('btn-save-optional-subject');
        const errorDiv  = document.getElementById('opt-subject-error');
        const counter   = document.getElementById('opt-selected-count');

        loading.classList.remove('d-none');
        container.classList.add('d-none');
        saveBtn.classList.add('d-none');
        errorDiv.classList.add('d-none');
        if (counter) counter.textContent = '0 selected (min. 2)';

        bootstrap.Modal.getOrCreateInstance(document.getElementById('kt_modal_add_optional_subject')).show();

        fetch('<?= base_url('school/get-stream-optional-subjects/') ?>' + currentOptionalStreamId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                loading.classList.add('d-none');
                container.classList.remove('d-none');
                saveBtn.classList.remove('d-none');

                if (!data.success) {
                    document.getElementById('opt-subject-rows').innerHTML =
                        '<tr><td colspan="3" class="text-danger py-4">' + (data.message || 'Failed to load subjects.') + '</td></tr>';
                    return;
                }

                if (data.departments && data.departments.length > 0) {
                    coreModalDepartments = data.departments;
                }

                const subjects      = data.subjects || [];
                const configuredIds = data.configured_subject_ids || [];
                const coreIds       = data.core_subject_ids || [];
                const optionalIds   = data.optional_subject_ids || [];

                if (subjects.length === 0) {
                    document.getElementById('opt-subject-rows').innerHTML =
                        '<tr><td colspan="3" class="text-warning py-4">No subjects available for this level.</td></tr>';
                    return;
                }

                let deptOptions = '<option value="">-- Select --</option>';
                coreModalDepartments.forEach(function(dept) {
                    deptOptions += '<option value="' + dept.sch_dept_id + '">' + dept.dept_name + '</option>';
                });

                let rows = '';
                subjects.forEach(function(subj) {
                    const isConfigured = configuredIds.indexOf(parseInt(subj.subject_id)) !== -1;
                    if (isConfigured) {
                        const isCore     = coreIds.indexOf(parseInt(subj.subject_id)) !== -1;
                        const isOptional = optionalIds.indexOf(parseInt(subj.subject_id)) !== -1;
                        const label      = isCore && isOptional ? 'Core &amp; Optional'
                                         : isCore ? 'Core' : 'Optional';
                        const badgeCls   = isCore ? 'badge-light-success' : 'badge-light-info';
                        const reason     = isCore ? 'Already a core subject' : 'Already in an optional group';
                        rows += '<tr class="text-muted">' +
                            '<td><input class="form-check-input" type="checkbox" checked disabled></td>' +
                            '<td class="fw-semibold">' + subj.subject_name + ' <span class="badge ' + badgeCls + ' ms-1 fs-8">' + label + '</span></td>' +
                            '<td><span class="text-gray-400 fs-7">' + reason + '</span></td>' +
                            '</tr>';
                    } else {
                        rows += '<tr>' +
                            '<td><input class="form-check-input opt-subject-check" type="checkbox" value="' + subj.subject_id + '" id="os_' + subj.subject_id + '"></td>' +
                            '<td><label class="fw-semibold cursor-pointer" for="os_' + subj.subject_id + '">' + subj.subject_name + '</label></td>' +
                            '<td><select class="form-select form-select-sm form-select-solid opt-dept-select" data-subject-id="' + subj.subject_id + '">' + deptOptions + '</select></td>' +
                            '</tr>';
                    }
                });
                document.getElementById('opt-subject-rows').innerHTML = rows;

                // Live counter
                document.getElementById('opt-subject-rows').addEventListener('change', function(ev) {
                    if (!ev.target.classList.contains('opt-subject-check')) return;
                    const checked = document.querySelectorAll('#opt-subject-rows .opt-subject-check:checked').length;
                    const c = document.getElementById('opt-selected-count');
                    if (c) {
                        c.textContent = checked + ' selected (min. 2)';
                        c.className = 'badge fs-7 ' + (checked >= 2 ? 'badge-light-success' : 'badge-light-info');
                    }
                });
            })
            .catch(function() {
                document.getElementById('opt-subject-loading').classList.add('d-none');
                document.getElementById('opt-subject-container').classList.remove('d-none');
                document.getElementById('opt-subject-rows').innerHTML =
                    '<tr><td colspan="3"><div class="alert alert-danger">Failed to load subjects. Please close and try again.</div></td></tr>';
            });
    });

    document.getElementById('btn-opt-select-all').addEventListener('click', function() {
        document.querySelectorAll('#opt-subject-rows .opt-subject-check').forEach(function(cb) { cb.checked = true; cb.dispatchEvent(new Event('change', {bubbles:true})); });
    });

    document.getElementById('btn-opt-deselect-all').addEventListener('click', function() {
        document.querySelectorAll('#opt-subject-rows .opt-subject-check').forEach(function(cb) { cb.checked = false; cb.dispatchEvent(new Event('change', {bubbles:true})); });
    });

    document.getElementById('btn-save-optional-subject').addEventListener('click', function() {
        const btn      = this;
        const label    = btn.querySelector('.indicator-label');
        const progress = btn.querySelector('.indicator-progress');
        const errorDiv = document.getElementById('opt-subject-error');

        errorDiv.classList.add('d-none');

        const checkedRows = [];
        let missingDept = false;
        document.querySelectorAll('#opt-subject-rows .opt-subject-check:checked').forEach(function(cb) {
            const row     = cb.closest('tr');
            const deptSel = row ? row.querySelector('.opt-dept-select') : null;
            const deptVal = deptSel ? deptSel.value : '';
            if (!deptVal) {
                missingDept = true;
                if (deptSel) deptSel.classList.add('is-invalid');
            } else {
                if (deptSel) deptSel.classList.remove('is-invalid');
                checkedRows.push({ subjectId: cb.value, deptId: deptVal });
            }
        });

        if (checkedRows.length < 2 && !missingDept) {
            errorDiv.textContent = 'Please select at least 2 subjects for an optional group.';
            errorDiv.classList.remove('d-none');
            return;
        }
        if (missingDept) {
            errorDiv.textContent = 'Please select a department for each selected subject.';
            errorDiv.classList.remove('d-none');
            return;
        }

        btn.disabled = true;
        label.style.display = 'none';
        progress.style.display = 'inline-flex';

        const formData = new FormData();
        formData.append(csrfName, csrfHash);
        checkedRows.forEach(function(item) {
            formData.append('subjectIds[]', item.subjectId);
            formData.append('subjectDepts[' + item.subjectId + ']', item.deptId);
        });

        fetch('<?= base_url('school/add-optional-subjects/') ?>' + currentOptionalStreamId, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';

                if (data.success) {
                    csrfHash = data.csrf_hash || csrfHash;
                    bootstrap.Modal.getInstance(document.getElementById('kt_modal_add_optional_subject')).hide();

                    appendOptionalGroup(data.optionGroup, currentOptionalStreamId);
                    appendNewSchSubjects(data.newSchSubjects);

                    const streamBlock = document.getElementById('stream-block-' + currentOptionalStreamId);
                    if (streamBlock && data.optionalTotal !== undefined) {
                        const badge = streamBlock.querySelector('.stream-optional-badge');
                        if (badge) badge.innerHTML = '<i class="ki-duotone ki-add-item fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>' + data.optionalTotal + ' Optional';
                    }

                    Swal.fire({ icon: 'success', title: 'Optional Group Added', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.classList.remove('d-none');
                }
            })
            .catch(function() {
                btn.disabled = false;
                label.style.display = '';
                progress.style.display = 'none';
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('d-none');
            });
    });

    function appendOptionalGroup(optionGroup, streamId) {
        const optContainer = document.getElementById('optional-subjects-' + streamId);
        if (!optContainer || !optionGroup || !optionGroup.subjects || optionGroup.subjects.length === 0) return;

        const emptyMsg = optContainer.querySelector('p');
        if (emptyMsg) emptyMsg.remove();

        let wrapper = optContainer.querySelector('.d-flex.flex-column.gap-4');
        if (!wrapper) {
            wrapper = document.createElement('div');
            wrapper.className = 'd-flex flex-column gap-4';
            optContainer.appendChild(wrapper);
        }

        const group = document.createElement('div');
        group.className = 'border border-dashed border-info rounded p-3 bg-light-info';
        group.id = 'opt-group-' + streamId + '-' + optionGroup.option_num;

        let subjectRows = '';
        optionGroup.subjects.forEach(function(subj) {
            subjectRows +=
                '<div class="d-flex align-items-center justify-content-between" id="opt-sub-' + subj.stream_opt_sub_id + '">' +
                '<div class="d-flex align-items-center">' +
                '<i class="ki-duotone ki-double-right fs-5 text-info me-2"><span class="path1"></span><span class="path2"></span></i>' +
                '<span class="text-gray-700 fs-7">' + subj.subject_name + '</span>' +
                '</div>' +
                '<button type="button" class="btn btn-icon btn-xs btn-light-danger btn-remove-opt-sub"' +
                ' data-opt-sub-id="' + subj.stream_opt_sub_id + '"' +
                ' data-stream-id="' + streamId + '"' +
                ' data-option-num="' + optionGroup.option_num + '"' +
                ' data-subject-name="' + subj.subject_name + '">' +
                '<i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
                '</button>' +
                '</div>';
        });

        group.innerHTML =
            '<div class="d-flex align-items-center justify-content-between mb-2">' +
            '<span class="fw-bold text-info fs-7">Option ' + optionGroup.option_num + ' (Choose 1)</span>' +
            '</div>' +
            '<div class="d-flex flex-column gap-1">' + subjectRows + '</div>';

        wrapper.appendChild(group);
    }

    // ---- REMOVE OPTIONAL SUBJECT ----
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-opt-sub');
        if (!btn) return;

        const optSubId    = btn.dataset.optSubId;
        const streamId    = btn.dataset.streamId;
        const optionNum   = btn.dataset.optionNum;
        const subjectName = btn.dataset.subjectName;

        Swal.fire({
            title: 'Remove Subject?',
            html: 'Remove <strong>' + subjectName + '</strong> from Option ' + optionNum + '?<br><small class="text-muted">If fewer than 2 subjects remain, the entire option group will be deleted.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#f1416c',
        }).then(function(result) {
            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('school/delete-optional-subject/') ?>' + optSubId, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        csrfHash = data.csrf_hash || csrfHash;

                        if (data.deletedOption) {
                            const group = document.getElementById('opt-group-' + streamId + '-' + optionNum);
                            if (group) group.remove();
                        } else {
                            const row = document.getElementById('opt-sub-' + optSubId);
                            if (row) row.remove();
                        }

                        const optContainer = document.getElementById('optional-subjects-' + streamId);
                        if (optContainer) {
                            const wrapper = optContainer.querySelector('.d-flex.flex-column.gap-4');
                            if (wrapper && wrapper.children.length === 0) {
                                wrapper.remove();
                                optContainer.innerHTML = '<p class="text-gray-600 fs-7">No optional subjects configured</p>';
                            }
                        }

                        const streamBlock = document.getElementById('stream-block-' + streamId);
                        if (streamBlock && data.optionalTotal !== undefined) {
                            const badge = streamBlock.querySelector('.stream-optional-badge');
                            if (badge) badge.innerHTML = '<i class="ki-duotone ki-add-item fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>' + data.optionalTotal + ' Optional';
                        }

                        Swal.fire({ icon: 'success', title: 'Removed', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                });
        });
    });

    // ---- SCHOOL REGISTERED SUBJECTS ----

    function appendNewSchSubjects(newSchSubjects) {
        if (!newSchSubjects || newSchSubjects.length === 0) return;
        const section = document.getElementById('sch-subjects-section');
        const badge   = document.getElementById('sch-subjects-count-badge');
        if (!section) return;

        newSchSubjects.forEach(function(sub) {
            if (document.getElementById('sch-sub-row-' + sub.sch_sub_id)) return;

            const levelId = sub.level_id || 0;
            let group = document.getElementById('sch-level-group-' + levelId);

            if (!group) {
                // Hide empty state if present
                const empty = document.getElementById('sch-subjects-empty');
                if (empty) empty.classList.add('d-none');

                // Create new level group
                group = document.createElement('div');
                group.className = 'sch-level-group mb-6';
                group.id = 'sch-level-group-' + levelId;
                group.innerHTML =
                    '<div class="d-flex align-items-center mb-3">' +
                        '<span class="bullet bullet-vertical bg-warning me-3 h-20px"></span>' +
                        '<h6 class="fw-bold text-gray-700 mb-0">' + escHtml(sub.level_name || 'Unknown') + '</h6>' +
                        '<span class="badge badge-light ms-3 sch-level-count">0</span>' +
                    '</div>' +
                    '<div class="table-responsive ms-4">' +
                        '<table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-2 mb-0">' +
                            '<thead><tr class="fw-semibold text-gray-500 fs-8 text-uppercase">' +
                                '<th>Subject</th><th>Department</th><th>Status</th><th class="text-end">Actions</th>' +
                            '</tr></thead>' +
                            '<tbody id="sch-level-tbody-' + levelId + '"></tbody>' +
                        '</table>' +
                    '</div>';
                section.appendChild(group);
            }

            const tbody = document.getElementById('sch-level-tbody-' + levelId);
            if (!tbody) return;

            const tr = document.createElement('tr');
            tr.id = 'sch-sub-row-' + sub.sch_sub_id;
            tr.innerHTML =
                '<td><span class="fw-semibold text-gray-800">' + escHtml(sub.subject_name) + '</span></td>' +
                '<td><span class="sch-sub-dept-name">' + escHtml(sub.dept_name || '—') + '</span></td>' +
                '<td><span class="badge badge-light-success sch-sub-status-badge">Active</span></td>' +
                '<td class="text-end">' +
                    '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit-sch-subject me-1"' +
                        ' data-sch-sub-id="' + sub.sch_sub_id + '"' +
                        ' data-subject-name="' + escHtml(sub.subject_name) + '"' +
                        ' data-dept-id="' + (sub.sch_dept_id_fk || 0) + '"' +
                        ' data-status="Active">' +
                        '<i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>' +
                    '</button>' +
                    '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete-sch-subject"' +
                        ' data-sch-sub-id="' + sub.sch_sub_id + '"' +
                        ' data-subject-name="' + escHtml(sub.subject_name) + '"' +
                        ' data-level-id="' + levelId + '">' +
                        '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
                    '</button>' +
                '</td>';
            tbody.appendChild(tr);

            // Update level count badge
            const lvlBadge = group.querySelector('.sch-level-count');
            if (lvlBadge) lvlBadge.textContent = tbody.querySelectorAll('tr').length;
        });

        // Update total badge
        if (badge) {
            const total = document.querySelectorAll('#sch-subjects-section tr[id^="sch-sub-row-"]').length;
            badge.textContent = total + ' Subject' + (total !== 1 ? 's' : '');
        }
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Edit school subject
    let editSchSubId = null;
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-edit-sch-subject');
        if (!btn) return;
        editSchSubId = btn.dataset.schSubId;
        document.getElementById('edit-sch-sub-name').value   = btn.dataset.subjectName || '';
        document.getElementById('edit-sch-sub-dept').value   = btn.dataset.deptId || '';
        document.getElementById('edit-sch-sub-status').value = btn.dataset.status || 'Active';
        bootstrap.Modal.getOrCreateInstance(document.getElementById('kt_modal_edit_sch_subject')).show();
    });

    document.getElementById('btn-save-sch-subject').addEventListener('click', function() {
        if (!editSchSubId) return;
        const btn = this;
        btn.setAttribute('data-kt-indicator', 'on');

        const formData = new FormData();
        formData.append(csrfName, csrfHash);
        formData.append('sch_dept_id_fk', document.getElementById('edit-sch-sub-dept').value);
        formData.append('sch_sub_status',  document.getElementById('edit-sch-sub-status').value);

        fetch('<?= base_url('school/edit-sch-subject') ?>/' + editSchSubId, { method: 'POST', body: formData })
            .then(r => r.json())
            .then(function(data) {
                btn.removeAttribute('data-kt-indicator');
                if (data.csrf_hash) csrfHash = data.csrf_hash;
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('kt_modal_edit_sch_subject')).hide();
                    const row = document.getElementById('sch-sub-row-' + editSchSubId);
                    if (row) {
                        row.querySelector('.sch-sub-dept-name').textContent = data.dept_name || '—';
                        const badge = row.querySelector('.sch-sub-status-badge');
                        if (badge) {
                            badge.textContent = data.sch_sub_status;
                            badge.className = 'badge sch-sub-status-badge badge-light-' + (data.sch_sub_status === 'Active' ? 'success' : 'warning');
                        }
                        const editBtn = row.querySelector('.btn-edit-sch-subject');
                        if (editBtn) {
                            editBtn.dataset.deptId = data.sch_dept_id_fk || 0;
                            editBtn.dataset.status = data.sch_sub_status;
                        }
                    }
                    Swal.fire({ icon: 'success', title: 'Updated', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                }
            })
            .catch(function() {
                btn.removeAttribute('data-kt-indicator');
                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
            });
    });

    // Delete school subject
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-sch-subject');
        if (!btn) return;
        const schSubId    = btn.dataset.schSubId;
        const subjectName = btn.dataset.subjectName || 'this subject';

        Swal.fire({
            icon: 'warning',
            title: 'Delete Subject?',
            html: 'Are you sure you want to delete <strong>' + escHtml(subjectName) + '</strong> from this school?<br><small class="text-muted">This will fail if the subject is assigned to any stream.</small>',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#F1416C',
            cancelButtonText: 'Cancel',
        }).then(function(result) {
            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('school/delete-sch-subject') ?>/' + schSubId, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(function(data) {
                    if (data.csrf_hash) csrfHash = data.csrf_hash;
                    if (data.success) {
                        const row = document.getElementById('sch-sub-row-' + schSubId);
                        if (row) {
                            const levelId = btn.dataset.levelId;
                            row.remove();

                            // Update level count or remove entire level group if empty
                            if (levelId) {
                                const tbody   = document.getElementById('sch-level-tbody-' + levelId);
                                const group   = document.getElementById('sch-level-group-' + levelId);
                                if (tbody && group) {
                                    const remaining = tbody.querySelectorAll('tr').length;
                                    if (remaining === 0) {
                                        group.remove();
                                    } else {
                                        const lvlBadge = group.querySelector('.sch-level-count');
                                        if (lvlBadge) lvlBadge.textContent = remaining;
                                    }
                                }
                            }
                        }

                        const badge = document.getElementById('sch-subjects-count-badge');
                        if (badge) {
                            const total = document.querySelectorAll('#sch-subjects-section tr[id^="sch-sub-row-"]').length;
                            badge.textContent = total + ' Subject' + (total !== 1 ? 's' : '');
                            if (total === 0) {
                                const empty = document.getElementById('sch-subjects-empty');
                                if (empty) empty.classList.remove('d-none');
                            }
                        }
                        Swal.fire({ icon: 'success', title: 'Deleted', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Cannot Delete Subject', text: data.message });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                });
        });
    });

    // =========================================================================
    // Add School Subject
    // =========================================================================
    let addSchSubAllRows = []; // all rows fetched from server

    function renderAddSchSubRows(filterNonExam) {
        const tbody = document.getElementById('add-school-sub-rows');
        tbody.innerHTML = '';
        const visible = filterNonExam ? addSchSubAllRows.filter(r => r.is_examinable === 0) : addSchSubAllRows;
        if (visible.length === 0) {
            document.getElementById('add-school-sub-empty').classList.remove('d-none');
            document.getElementById('btn-save-school-subject').classList.add('d-none');
            return;
        }
        document.getElementById('add-school-sub-empty').classList.add('d-none');
        document.getElementById('btn-save-school-subject').classList.remove('d-none');
        visible.forEach(function(sub) {
            const tr = document.createElement('tr');
            tr.dataset.subjectId    = sub.subject_id;
            tr.dataset.isExaminable = sub.is_examinable;
            tr.innerHTML =
                '<td><div class="form-check form-check-custom form-check-solid">' +
                    '<input class="form-check-input sch-sub-checkbox" type="checkbox" value="' + sub.subject_id + '">' +
                '</div></td>' +
                '<td class="fw-semibold text-gray-800 fs-7">' + escHtml(sub.subject_name) + '</td>' +
                '<td class="text-muted fs-8">' + escHtml(sub.level_name) + '</td>' +
                '<td class="text-center">' +
                    (sub.is_examinable ? '<span class="badge badge-light-info fs-9">Examinable</span>' : '<span class="badge badge-light-warning fs-9">Non-exam</span>') +
                '</td>';
            tbody.appendChild(tr);
        });
    }

    document.getElementById('btn-open-add-school-subject').addEventListener('click', function() {
        const loading   = document.getElementById('add-school-sub-loading');
        const container = document.getElementById('add-school-sub-container');
        const errDiv    = document.getElementById('add-school-sub-error');
        loading.classList.remove('d-none');
        container.classList.add('d-none');
        errDiv.classList.add('d-none');
        addSchSubAllRows = [];
        bootstrap.Modal.getOrCreateInstance(document.getElementById('kt_modal_add_school_subject')).show();

        fetch('<?= base_url('school/available-subjects/') ?>' + schId)
            .then(r => r.json())
            .then(function(data) {
                loading.classList.add('d-none');
                container.classList.remove('d-none');
                if (!data.success) {
                    errDiv.textContent = data.message || 'Failed to load subjects.';
                    errDiv.classList.remove('d-none');
                    return;
                }
                // Flatten grouped subjects for rendering
                data.subjects.forEach(function(grp) {
                    grp.subjects.forEach(function(sub) {
                        addSchSubAllRows.push(Object.assign({}, sub, { level_name: grp.level_name }));
                    });
                });
                const filterNonExam = document.getElementById('toggle-non-examinable').checked;
                renderAddSchSubRows(filterNonExam);
            })
            .catch(function() {
                loading.classList.add('d-none');
                container.classList.remove('d-none');
                errDiv.textContent = 'Network error. Please try again.';
                errDiv.classList.remove('d-none');
            });
    });

    document.getElementById('toggle-non-examinable').addEventListener('change', function() {
        renderAddSchSubRows(this.checked);
    });

    document.getElementById('btn-sch-sub-select-all').addEventListener('click', function() {
        document.querySelectorAll('.sch-sub-checkbox').forEach(cb => cb.checked = true);
    });

    document.getElementById('btn-sch-sub-deselect-all').addEventListener('click', function() {
        document.querySelectorAll('.sch-sub-checkbox').forEach(cb => cb.checked = false);
    });

    document.getElementById('btn-save-school-subject').addEventListener('click', function() {
        const checked = Array.from(document.querySelectorAll('.sch-sub-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Nothing selected', text: 'Please tick at least one subject.', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
            return;
        }
        const btn = this;
        btn.setAttribute('data-kt-indicator', 'on');
        btn.disabled = true;

        const formData = new FormData();
        formData.append(csrfName, csrfHash);
        checked.forEach(id => formData.append('subjectIds[]', id));

        fetch('<?= base_url('school/add-school-subject/') ?>' + schId, { method: 'POST', body: formData })
            .then(r => r.json())
            .then(function(data) {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;
                if (data.csrf_hash) csrfHash = data.csrf_hash;
                if (!data.success) {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    return;
                }
                bootstrap.Modal.getInstance(document.getElementById('kt_modal_add_school_subject')).hide();

                const plusIcon = '<i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>';
                const trashIcon = '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';

                data.added.forEach(function(sub) {
                    const lid    = sub.level_id;
                    let group    = document.getElementById('sch-level-group-' + lid);
                    const secEl  = document.getElementById('sch-subjects-section');

                    // Create level group if it doesn't exist yet
                    if (!group) {
                        const emptyEl = document.getElementById('sch-subjects-empty');
                        if (emptyEl) emptyEl.classList.add('d-none');

                        group = document.createElement('div');
                        group.className = 'sch-level-group mb-6';
                        group.id = 'sch-level-group-' + lid;
                        group.innerHTML =
                            '<div class="d-flex align-items-center mb-3">' +
                                '<span class="bullet bullet-vertical bg-warning me-3 h-20px"></span>' +
                                '<h6 class="fw-bold text-gray-700 mb-0">' + escHtml(sub.level_name) + '</h6>' +
                                '<span class="badge badge-light ms-3 sch-level-count">0</span>' +
                            '</div>' +
                            '<div class="table-responsive ms-4">' +
                                '<table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-2 mb-0">' +
                                    '<thead><tr class="fw-semibold text-gray-500 fs-8 text-uppercase border-bottom border-gray-200">' +
                                        '<th>Subject</th><th>Department</th><th>Status</th><th class="text-end">Actions</th>' +
                                    '</tr></thead>' +
                                    '<tbody id="sch-level-tbody-' + lid + '"></tbody>' +
                                '</table>' +
                            '</div>';
                        secEl.appendChild(group);
                    }

                    const tbody = document.getElementById('sch-level-tbody-' + lid);
                    const tr    = document.createElement('tr');
                    tr.id = 'sch-sub-row-' + sub.sch_sub_id;
                    tr.innerHTML =
                        '<td><span class="fw-semibold text-gray-800">' + escHtml(sub.subject_name) + '</span></td>' +
                        '<td><span class="sch-sub-dept-name">—</span></td>' +
                        '<td><span class="badge badge-light-success sch-sub-status-badge">Active</span></td>' +
                        '<td class="text-end">' +
                            '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit-sch-subject me-1"' +
                                ' data-sch-sub-id="' + sub.sch_sub_id + '"' +
                                ' data-subject-name="' + escHtml(sub.subject_name) + '"' +
                                ' data-dept-id="0" data-status="Active">' + plusIcon + '</button>' +
                            '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete-sch-subject"' +
                                ' data-sch-sub-id="' + sub.sch_sub_id + '"' +
                                ' data-subject-name="' + escHtml(sub.subject_name) + '"' +
                                ' data-level-id="' + lid + '">' + trashIcon + '</button>' +
                        '</td>';
                    tbody.appendChild(tr);

                    // Update level count badge
                    const lvlCount = group.querySelector('.sch-level-count');
                    if (lvlCount) lvlCount.textContent = tbody.querySelectorAll('tr').length;
                });

                // Update total count badge
                const badge = document.getElementById('sch-subjects-count-badge');
                if (badge) {
                    const total = document.querySelectorAll('#sch-subjects-section tr[id^="sch-sub-row-"]').length;
                    badge.textContent = total + ' Subject' + (total !== 1 ? 's' : '');
                }

                Swal.fire({ icon: 'success', title: 'Done', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
            })
            .catch(function() {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
            });
    });

})();
</script>