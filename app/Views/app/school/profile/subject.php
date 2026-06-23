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
							<span class="card-label fw-bold text-gray-800">School Level</span>
							<?php
							    $numOfLevel= count($schLevel);
							    $s = '';
							    if($numOfLevel > 1){
							        $s = 's';
							    }
							?>
							<span class="text-gray-500 mt-1 fw-semibold fs-6">Total of <?php echo $numOfLevel.' record'.$s; ?> found.</span>
						</h3>
						<!--end::Title-->
						<!--begin::Toolbar-->
						<div class="card-toolbar">
							<a href="#" class="btn btn-sm btn-light-primary">Update School Level</a>
						</div>
						<!--end::Toolbar-->
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body pt-6">
						
						
							
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

