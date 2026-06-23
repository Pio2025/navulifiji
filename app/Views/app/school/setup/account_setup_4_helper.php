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
				    <input type="hidden" name="current_step" value="3" id="current_step">
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
                                // Debug: Check if helper can be loaded
                                try {
                                    helper('stream_subject');
                                    echo '<!-- Helper loaded successfully -->';
                                } catch (\Exception $e) {
                                    echo '<!-- Error loading helper: ' . $e->getMessage() . ' -->';
                                }
                                
                                // Debug: Check if function exists
                                if (function_exists('show_stream_subjects')) {
                                    echo '<!-- Function exists -->';
                                } else {
                                    echo '<!-- Function does NOT exist -->';
                                    // Try alternative loading
                                    require_once APPPATH . 'Helpers/StreamSubjectHelper.php';
                                }
                                
                                $schID = session()->get('schID');
                                
                                // Try calling the function with error handling
                                try {
                                    echo show_stream_subjects($schID, [
                                        'columns' => 2,
                                        'show_actions' => true
                                    ]);
                                } catch (\Exception $e) {
                                    echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                                    // Fallback to original code if helper fails
                                    echo get_original_stream_content($schID);
                                }
                            ?>
                            
						</div>
					</div>
					<!--end::Card body-->
					<?php
					   // Note: Button state is now handled by JavaScript in the helper
					?>
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button type="submit" class="btn btn-primary" disabled="disabled">Complete Setup</button>
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

<?php
// Add modals using helper functions
echo get_stream_subject_modal_html('core');
echo get_stream_subject_modal_html('optional');
?>

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