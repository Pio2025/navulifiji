
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
					<h3 class="fw-bold m-0">Step 4 - Stream Subject Setup</h3>
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
                                    <h4 class="mb-1 text-info">Complete Your Academic Configuration</h4>
                                    <!--end::Title-->
                            
                                    <!--begin::Content-->
                                    <span>This critical phase finalizes your school account setup. Ensure all core curriculum subjects are properly configured, along with any elective courses where applicable. Once all academic streams have been thoroughly initialized, select 'Complete Setup' to activate your institutional account.</span>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Alert-->
						    
						    
						    
						    
						    <?php
						        // At the top of your view file
                                $schoolStreamModel = new \App\Models\SchoolStreamModel();
                                $streamCoreSubjectModel = new \App\Models\StreamCoreSubjectModel();
                                $streamOptionalSubjectModel = new \App\Models\StreamOptionalSubjectModel();
                                $schID = session()->get('schID');
                                $allStreams = $schoolStreamModel->getAllStreamsBySchool($schID);
                                $coreTracker = 0;
                                
                                $numOfStreams = count($allStreams);
                                
                                $disabled='disabled="disabled"';
                                $disabledText = 'Setup all core subjects and optional subject combination for all stream then the Save Button will be enable to move to the final step.';
                                
                                if(!empty($allStreams)){
                                    foreach($allStreams as $row){
                                        
                                        $AtLeatOneCore = $streamCoreSubjectModel->getStreamCoreSubjectByStreamFirst($row['stream_id']);
                                        $AtLeatOneOptional = $streamOptionalSubjectModel->getStreamOptionalSubjectByStreamFirst($row['stream_id']);
                                
                            ?>
						    <div class="col-md-6 mb-10">
						        <div class="card card-dashed">
                                    <div class="card-header">
                                        <h3 class="card-title"><?php echo $row['stream_name']; ?></h3>
                                        <div class="card-toolbar">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#add_core_subject_modal" data-stream-id="<?php echo $row['stream_id'].'-'.$row['level_id']; ?>" title="Add Core Subject" class="btn btn-light-primary btn-xs"><i class="bi bi-plus fs-4 me-2"></i> Core Subject</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#add_optional_subject_modal" data-stream-id="<?php echo $row['stream_id'].'-'.$row['level_id']; ?>" title="Add Optional Subject" class="btn btn-light-info btn-xs"><i class="bi bi-plus fs-4 me-2"></i> Optional Subject</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                            $core = $streamCoreSubjectModel->getStreamCoreSubjectByStream($row['stream_id']);
                                            $optional = $streamOptionalSubjectModel->getStreamOptionalSubjectByStream($row['stream_id']);
                                            if(!empty($core)){
                                                if(count($core) > 1){
                                                    $s = 's';
                                                    $coreTracker++;
                                                }else{
                                                    $s = '';
                                                }
                                                echo '<h3 class="card-title align-items-start flex-column">
    												<span class="card-label fw-bold text-gray-900">Stream Core Subject'.$s.'</span><br>
    												<span class="text-muted mt-1 fw-semibold fs-7">Total of '.count($core).' record'.$s.' found</span>
    											</h3>';
    											$j = 1;
                                                foreach($core as $item){
                                                    echo '<!--begin::Item-->
            										<div class="d-flex flex-stack">
            											<!--begin::Symbol-->
            											<div class="symbol symbol-40px me-4">
            												<div class="symbol-label fs-2 fw-semibold bg-success text-inverse-danger">C</div>
            											</div>
            											<!--end::Symbol-->
            											<!--begin::Section-->
            											<div class="d-flex align-items-center flex-row-fluid flex-wrap">
            												<!--begin:Author-->
            												<div class="flex-grow-1 me-2">
            													<a class="text-gray-800 text-hover-primary fs-6 fw-bold">'.$item['subject_name'].'</a>
            												</div>
            												<!--end:Author-->
            												<!--begin::Actions-->
            												<a href="#" data-bs-toggle="modal" data-bs-target="#deleteCoreModal" data-sub-id="'.$item['stream_core_sub_id'].'" title="Delete core subject" class="btn btn-sm btn-icon btn-bg-light btn-active-color-danger w-30px h-30px">
            													<i class="ki-duotone ki-trash fs-2">
                                                                 <span class="path1"></span>
                                                                 <span class="path2"></span>
                                                                 <span class="path3"></span>
                                                                 <span class="path4"></span>
                                                                 <span class="path5"></span>
                                                                </i>
            												</a>
            												<!--begin::Actions-->
            											</div>
            											<!--end::Section-->
            										</div>
            										<!--end::Item-->';
            										
            										if($j < count($core)){
            										    echo '<!--end::Item-->
                										<!--begin::Separator-->
                										<div class="separator separator-dashed my-4"></div>
                										<!--end::Separator-->';
            										}else{
            										   echo '<div class=" my-4"></div>'; 
            										}
            										$j++;
                                                }
                                                
                                            }else{
                                                echo '<!--begin::Alert-->
                                                <div class="alert alert-danger d-flex align-items-center p-5 mb-5">
                                                    <!--begin::Icon-->
                                                    <i class="ki-duotone ki-shield-tick fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                                                    <!--end::Icon-->
                                                
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex flex-column">
                                                        <!--begin::Title-->
                                                        <h4 class="mb-1 text-dark">Opps!</h4>
                                                        <!--end::Title-->
                                                
                                                        <!--begin::Content-->
                                                        <span>No core subject found in the database for the '.$row['stream_name'].' stream. </span>
                                                        <!--end::Content-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </div>
                                                <!--end::Alert-->';
                                            }
                                            
                                            if(!empty($optional)){
    											// Group subjects by option_num
                                                $groupedOptions = [];
                                                foreach($optional as $subject){
                                                    $optionNum = $subject['option_num'];
                                                    $groupedOptions[$optionNum][] = $subject;
                                                }
                                                
                                                // Display header
                                                $optionCount = count($groupedOptions);
                                                $s = ($optionCount > 1) ? 's' : '';
                                                echo '<h3 class="card-title align-items-start flex-column">
                                                    <span class="card-label fw-bold text-gray-900">Stream Optional Subject'.$s.'</span><br>
                                                    <span class="text-muted mt-1 fw-semibold fs-7">Total of '.$optionCount.' option'.$s.' found</span>
                                                </h3>';
                                                
                                                
                                                
                                                // Display each option group
                                                foreach($groupedOptions as $optionNum => $subjects){
                                                    echo '<h4 class="text-primary text-end bg-light-warning rounded-1 p-4">Option '.$optionNum.'</h4>';
                                                    $j = 1;
                                                    foreach($subjects as $subject){
                                                        echo '<!--begin::Item-->
                										<div class="d-flex flex-stack">
                											<!--begin::Symbol-->
                											<div class="symbol symbol-40px me-4">
                												<div class="symbol-label fs-2 fw-semibold bg-warning text-inverse-danger">O</div>
                											</div>
                											<!--end::Symbol-->
                											<!--begin::Section-->
                											<div class="d-flex align-items-center flex-row-fluid flex-wrap">
                												<!--begin:Author-->
                												<div class="flex-grow-1 me-2">
                													<a class="text-gray-800 text-hover-primary fs-6 fw-bold">'.$subject['subject_name'].'</a>
                												</div>
                												<!--end:Author-->
                												<!--begin::Actions-->
                												<a href="#" data-bs-toggle="modal" data-bs-target="#deleteOptionalModal" data-sub-id="'.$optionNum.'" title="Delete optional subject combination" class="btn btn-sm btn-icon btn-bg-light btn-active-color-danger w-30px h-30px">
                													<i class="ki-duotone ki-trash fs-2">
                                                                     <span class="path1"></span>
                                                                     <span class="path2"></span>
                                                                     <span class="path3"></span>
                                                                     <span class="path4"></span>
                                                                     <span class="path5"></span>
                                                                    </i>
                												</a>
                												<!--begin::Actions-->
                											</div>
                											<!--end::Section-->
                										</div>
                										<!--end::Item-->';
                										
                										if($j < $optionCount){
                										    echo '<!--end::Item-->
                    										<!--begin::Separator-->
                    										<div class="separator separator-dashed my-4"></div>
                    										<!--end::Separator-->';
                										}else{
                										   echo '<div class=" my-4"></div>'; 
                										}
                										$j++;
                                                    }
                                                }
    											
    											
                                            }else{
                                                echo '<!--begin::Alert-->
                                                <div class="alert alert-warning d-flex align-items-center p-5">
                                                    <!--begin::Icon-->
                                                    <i class="ki-duotone ki-shield-tick fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                                                    <!--end::Icon-->
                                                
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex flex-column">
                                                        <!--begin::Title-->
                                                        <h4 class="mb-1 text-dark">Opps!</h4>
                                                        <!--end::Title-->
                                                
                                                        <!--begin::Content-->
                                                        <span>No optional subject found in the database for the '.$row['stream_name'].' stream. </span>
                                                        <!--end::Content-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </div>
                                                <!--end::Alert-->';
                                            }
                                        ?>
                                    </div>
                                </div>
						    </div>
						    
						
						   <?php } }else{ 
    						        echo '<!--begin::Alert-->
                                    <div class="alert alert-danger d-flex align-items-center p-5 mb-20">
                                        <!--begin::Icon-->
                                        <i class="ki-duotone ki-shield-tick fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                                        <!--end::Icon-->
                                    
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column">
                                            <!--begin::Title-->
                                            <h4 class="mb-1 text-dark">Opps!</h4>
                                            <!--end::Title-->
                                    
                                            <!--begin::Content-->
                                            <span>There is no school stream found on the database.</span>
                                            <!--end::Content-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Alert-->';
    						   }
						   ?>
						    
						</div>
					</div>
					<!--end::Card body-->
					<?php
					   if($numOfStreams == $coreTracker){
					       $disabled='';
					   }else{
					       $disabled='disabled="disabled"';
					   }
					?>
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button <?php echo $disabled; ?> type="submit" class="btn btn-primary">Complete Setup</button>
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


<div class="modal fade" tabindex="-1" id="add_core_subject_modal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="bi bi-plus fs-2 text-primary"></i> Add Core Subject Modal</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form class="form" method="post" action="<?php echo base_url(); ?>school/addcoresubject">
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="streamID" value=""/>
                    <input type="hidden" class="form-control" name="levelID" value=""/>
                    
                    <div id="loader" class="text-center" style="display: none;">
                        <img src="<?php echo base_url('loader/Spinner-1s-200px.gif'); ?>" alt="Loading" />
                        <br><span>Loading level subjects....</span>
                    </div>
                    
                    <div id="alert-core">
                        <!--begin::Alert-->
                        <div class="alert alert-primary d-flex align-items-center p-5">
                            <!--begin::Icon-->
                            <span class="svg-icon svg-icon-2hx svg-icon-info me-4">
                                <i class="ki-duotone ki-information fs-2hx text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <!--end::Icon-->
                        
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column">
                                <!--begin::Title-->
                                <h4 class="mb-1 text-dark">Note</h4>
                                <!--end::Title-->
                        
                                <!--begin::Content-->
                                <span>Select all the core subject for the corresponding school level. At least one subject is required to submit this form.</span>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Alert-->
                    </div>
                    
                    <!-- ✅ FIXED: Use unique ID and class -->
                    <div id="response-core" class="response"></div>
                </div>
                
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="add_optional_subject_modal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="bi bi-plus fs-2 text-primary"></i> Add Optional Subject Modal</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form class="form" method="post" action="<?php echo base_url(); ?>school/addoptionalsubject">
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="streamID" value=""/>
                    <input type="hidden" class="form-control" name="levelID" value=""/>
                    
                    <div id="loader2" class="text-center" style="display: none;">
                        <img src="<?php echo base_url('loader/Spinner-1s-200px.gif'); ?>" alt="Loading" />
                        <br><span>Loading level subjects....</span>
                    </div>
                    
                    
                    <div id="alert-optional">
                        <!--begin::Alert-->
                        <div class="alert alert-primary d-flex align-items-center p-5">
                            <!--begin::Icon-->
                            <span class="svg-icon svg-icon-2hx svg-icon-info me-4">
                                <i class="ki-duotone ki-information fs-2hx text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <!--end::Icon-->
                        
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column">
                                <!--begin::Title-->
                                <h4 class="mb-1 text-dark">Note</h4>
                                <!--end::Title-->
                        
                                <!--begin::Content-->
                                <span>Select an optional subject option. For example Fijian and Economic is an option, so student can either take Fijian or Ecocomics. Another example could be Fijian, Economic and Hindi. <br><br>Therefore if you have 4 optional subject combination you will have to perform this task four times.</span>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Alert-->
                    </div>
                    
                    <!-- ✅ FIXED: Use unique ID and class -->
                    <div id="response-optional" class="response2"></div>
                </div>
                
            </form>
        </div>
    </div>
</div>

<!-- Modal-->
    <div class="modal fade" id="deleteCoreModal" tabindex="-1" role="dialog" aria-labelledby="deleteCoreModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteCoreModalLabel" style="color:#fff;">Confirm Delete</h5>
                    <!--begin::Close-->
					<div class="btn btn-sm btn-icon btn-active-color-white" data-bs-dismiss="modal">
						<i class="ki-duotone ki-cross fs-1 ">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
					<!--end::Close-->
                </div>
                <div class="modal-body">
    				<?php echo form_open('school/remove_core'); ?>
                    Are you sure you want to delete core subject? There is no undo after clicking the confirm button.
    				<input type="hidden" class="form-control" name="coreSubID" id="coreSubID" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger font-weight-bold" data-bs-dismiss="modal">Confirm</button>
    				<?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal-->
    <div class="modal fade" id="deleteOptionalModal" tabindex="-1" role="dialog" aria-labelledby="deleteOptionalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteOptionalModalLabel">Confirm Delete</h5>
                    <!--begin::Close-->
					<div class="btn btn-sm btn-icon btn-active-color-white" data-bs-dismiss="modal">
						<i class="ki-duotone ki-cross fs-1 ">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
					<!--end::Close-->
                </div>
                <div class="modal-body">
    				<?php echo form_open('school/remove_optional'); ?>
                    Are you sure you want to delete optional subject combination? There is no undo after clicking the confirm button.
    				<input type="hidden" class="form-control" name="optID" id="optID" value=""/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger font-weight-bold" data-bs-dismiss="modal">Confirm</button>
    				<?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>