
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
					<h3 class="fw-bold m-0">Step 2 - School Curriculum Setup</h3>
				</div>
				<!--end::Card title-->
			</div>
			<!--begin::Card header-->
			<!--begin::Content-->
			<div id="kt_account_settings_profile_details" class="collapse show">
				<!--begin::Form-->
				<form id="kt_account_profile_details_form" class="form" method="post" action="<?= site_url('school/configure') ?>">
					<!--begin::Card body-->
					<div class="card-body border-top p-9">
						<!--begin::Input group-->
						<div class="row mb-6">
						    <div class="col-md-12">
						        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">School Department</h3>
						    </div>
						    <!--begin::Col-->
                            <div class="col-md-12 mb-2 mt-3">
                                <!--begin::Label-->
                                <span class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="check_all_dept" id="check_all_dept"/>
                                    <label class="form-label mb-3 required">Setup School Department</label>
                                </span>
                            </div>
                            <!--end::Col-->
                            
                            <div class="row col-md-12 ms-0">
                                <?php foreach($department as $row): ?>
                                    <?php if($row['dept_name'] != "Other"): ?>
                                        <div class="form-check  col-md-4 mb-5">
                                            <input class="form-check-input" type="checkbox" value="<?= $row['dept_id']; ?>" name="departments[]" />
                                            <label class="form-check-label"><?= $row['dept_name']; ?></label>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <!-- Department Error Display -->
                                <?php if (isset($validation) && $validation->hasError('departments')): ?>
                                    <div class="text-danger mt-2">
                                        <?= $validation->getError('departments') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
						</div>
						<!--end::Input group-->
						<div class="row mt-5">
						    <div class="col-md-12">
						        <h3 class="fw-semibold badge-light-primary" style="width:100%;padding:20px;">School Level</h3>
						    </div>
						    <!--begin::Col-->
                            <div class="col-md-12 mb-2 mt-3">
                                <!--begin::Label-->
                                <span class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="check_all_level" id="check_all_level"/>
                                    <label class="form-label mb-3 required">Setup School Level</label>
                                </span>
                            </div>
                            <div class="row col-md-12 ms-0">
                                <?php if($schCatID == 1){ ?>
                                    <!-- Pre School -->
                                    <div class="form-check  col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="1" name="levells[]" />
                                        <label class="form-check-label">Pre School</label>
                                    </div>
                                <?php }else if($schCatID == 2){ ?>
                                    <!-- Kindergarten -->
                                    <div class="form-check  col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="1" name="levells[]" />
                                        <label class="form-check-label">Pre School</label>
                                    </div>
                                <?php }else if($schCatID == 3){ ?>
                                    <!-- Primary Years 1-8 -->
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="3" name="levells[]" />
                                        <label class="form-check-label">Year 1</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="4" name="levells[]" />
                                        <label class="form-check-label">Year 2</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="5" name="levells[]" />
                                        <label class="form-check-label">Year 3</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="6" name="levells[]" />
                                        <label class="form-check-label">Year 4</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="7" name="levells[]" />
                                        <label class="form-check-label">Year 5</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="8" name="levells[]" />
                                        <label class="form-check-label">Year 6</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="9" name="levells[]" />
                                        <label class="form-check-label">Year 7</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="10" name="levells[]" />
                                        <label class="form-check-label">Year 8</label>
                                    </div>
                                <?php }else if($schCatID == 4){ ?>
                                    <!-- Secondary Years 9-13 -->
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="11" name="levells[]" />
                                        <label class="form-check-label">Year 9</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="12" name="levells[]" />
                                        <label class="form-check-label">Year 10</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="13" name="levells[]" />
                                        <label class="form-check-label">Year 11</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="14" name="levells[]" />
                                        <label class="form-check-label">Year 12</label>
                                    </div>
                                    <div class="form-check col-md-4 mb-5">
                                        <input class="form-check-input" type="checkbox" value="15" name="levells[]" />
                                        <label class="form-check-label">Year 13</label>
                                    </div>
                                <?php }else{echo 'No school category found so relative school level cannot be configured.';} ?>
                                <!-- Levels Error Display -->
                                <?php if (isset($validation) && $validation->hasError('levells')): ?>
                                    <div class="text-danger mt-2">
                                        <?= $validation->getError('levells') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
					</div>
					<!--end::Card body-->
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
						<button type="submit" class="btn btn-primary" name="step_btn_1">Save Changes</button>
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

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Store school category ID for reference
    var schCatID = <?= $schCatID ?? 0 ?>;

    // Department Check All Functionality
    $('#check_all_dept').change(function() {
        var isChecked = $(this).is(':checked');
        $('input[name="departments[]"]').prop('checked', isChecked);
        updateDepartmentMasterCheckbox();
    });

    // Individual Department Checkbox Functionality
    $('input[name="departments[]"]').change(function() {
        updateDepartmentMasterCheckbox();
    });

    // Level Check All Functionality
    $('#check_all_level').change(function() {
        var isChecked = $(this).is(':checked');
        
        // Only check levels relevant to current school category
        switch(schCatID) {
            case 1: // Pre School
            case 2: // Kindergarten
                $('input[name="levells[]"]').prop('checked', isChecked);
                break;
            case 3: // Primary (Years 1-8)
                $('input[name="levells[]"][value="3"], input[name="levells[]"][value="4"], input[name="levells[]"][value="5"], input[name="levells[]"][value="6"], input[name="levells[]"][value="7"], input[name="levells[]"][value="8"], input[name="levells[]"][value="9"], input[name="levells[]"][value="10"]').prop('checked', isChecked);
                break;
            case 4: // Secondary (Years 9-13)
                $('input[name="levells[]"][value="11"], input[name="levells[]"][value="12"], input[name="levells[]"][value="13"], input[name="levells[]"][value="14"], input[name="levells[]"][value="15"]').prop('checked', isChecked);
                break;
            default:
                // Handle other cases or do nothing
        }
        
        updateLevelMasterCheckbox();
    });

    // Individual Level Checkbox Functionality
    $('input[name="levells[]"]').change(function() {
        updateLevelMasterCheckbox();
    });

    // Function to update department master checkbox
    function updateDepartmentMasterCheckbox() {
        var totalDept = $('input[name="departments[]"]').length;
        var checkedDept = $('input[name="departments[]"]:checked').length;
        
        $('#check_all_dept').prop('checked', checkedDept === totalDept && totalDept > 0);
        $('#check_all_dept').prop('indeterminate', checkedDept > 0 && checkedDept < totalDept);
    }

    // Function to update level master checkbox
    function updateLevelMasterCheckbox() {
        var totalLevels = $('input[name="levells[]"]').length;
        var checkedLevels = $('input[name="levells[]"]:checked').length;
        
        $('#check_all_level').prop('checked', checkedLevels === totalLevels && totalLevels > 0);
        $('#check_all_level').prop('indeterminate', checkedLevels > 0 && checkedLevels < totalLevels);
    }

    // Initialize on page load
    updateDepartmentMasterCheckbox();
    updateLevelMasterCheckbox();
});
</script>