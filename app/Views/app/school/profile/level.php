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
						<?php
						    // First check if $schLevel is valid and not empty
                            if (!empty($schLevel) && is_iterable($schLevel)):
                                $i = 1;
                                foreach($schLevel as $row):
                                    
                                    $name = esc($row['level_name'] ?? '');
                                    $level_id = esc($row['sch_level_id'] ?? '');
						?>
						<!--begin::Item-->
						<div class="d-flex flex-stack">
							<!--begin::Wrapper-->
							<div class="d-flex align-items-center me-3">
								<!--begin::Symbol-->
                                <div class="symbol symbol-40px me-4">
                                    <div class="symbol-label fs-5 fw-semibold bg-primary text-inverse-danger">
                                        <?= $i ?>
                                    </div>
                                </div>
                                <!--end::Symbol-->
								<!--begin::Section-->
								<div class="flex-grow-1">
									<!--begin::Text-->
									<a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0"><?= $name ?></a>
									<!--end::Text-->
									<!--begin::Description-->
									<span class="text-gray-500 fw-semibold d-block fs-6">List of registered stream(s) is displayed below.</span>
									<!--end::Description=-->
								</div>
								<!--end::Section-->
							</div>
							<!--end::Wrapper-->
							
						</div>
						<!--end::Item-->
						<!--begin::Separator-->
						<div class="separator separator-dashed my-3 mb-8"></div>
						<!--end::Separator-->
						
						<?php
						    helper('stream_display');
						?>
						
						    <div class="row">
						        
						        <?= \App\Helpers\show_streams($level_id, [
                                    'empty_message' => 'No streams for ' . $name,
                                    'view_type' => 'list'
                                ]) ?>
						        
						    </div>
						
						<?php 
						    $i++;
                            endforeach;
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
                                    <span>There is no record found for school level in the database.</span>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Alert-->
                        
                        <?php endif; ?>
						
							
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

