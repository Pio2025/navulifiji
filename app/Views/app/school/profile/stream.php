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
			    <div class="row">
			    <?php
				    // First check if $schLevel is valid and not empty
                    if (!empty($schLevel) && is_iterable($schLevel)):
                        foreach($schLevel as $row):
                            
                            $name = esc($row['level_name'] ?? '');
				?>
				
				<div class="col-md-4  mb-8">
    				<div class="card card-flush h-md-100" style="border: 1px solid #ecf0f1; border-left: 6px solid #0d6efd;">
                        <!--begin::Body-->
                        <div class="card-body pt-6">
                            <!--begin::Item-->
    						<div class="d-flex align-items-center">
    							<!--begin::Description-->
    							<div class="flex-grow-1">
    								<a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Create FireStone Logo</a>
    								<span class="text-muted fw-semibold d-block">Due in 2 Days</span>
    							</div>
    							<!--end::Description-->
    							<span class="badge badge-light-success fs-8 fw-bold">New</span>
    						</div>
    						<!--end:Item-->
                        </div>
                        <!--end: Card Body-->
                    </div>
			    </div>
			    <?php 
                    endforeach;
                    
                    echo '</div>';
                    
                    else: 
                    // Show empty state if no level
                ?>
                
                    <!--begin::Alert-->
                    <div class="alert alert-danger d-flex align-items-center p-5">
                        <!--begin::Icon-->
                        <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <!--end::Icon-->
                    
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column">
                            <!--begin::Title-->
                            <h4 class="mb-1 text-dark">CAUTION</h4>
                            <!--end::Title-->
                    
                            <!--begin::Content-->
                            <span>There is no record found for school stream in the database.</span>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Alert-->
                
                <?php endif; ?>
                        
			</div>
			<!--end::Col-->
			
			
			
		</div>
		<!--end::Row-->
	</div>
	<!--end::Content container-->
</div>
<!--end::Content-->

