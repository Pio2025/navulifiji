<style>
/* Validation styles */
.is-invalid {
    border-color: #f1416c !important;
    box-shadow: 0 0 0 0.25rem rgba(241, 65, 108, 0.25) !important;
}

.is-valid {
    border-color: #50cd89 !important;
    box-shadow: 0 0 0 0.25rem rgba(80, 205, 137, 0.25) !important;
}

/* Make invalid checkboxes more visible */
.form-check-input.is-invalid {
    border-width: 2px;
}

/* Highlight recently saved items */
.list-group-item.saved {
    background-color: rgba(80, 205, 137, 0.1);
    border-left: 4px solid #50cd89 !important;
    transition: all 0.3s ease;
}

/* Highlight invalid items */
.list-group-item.invalid {
    background-color: rgba(241, 65, 108, 0.05);
    border-left: 4px solid #f1416c !important;
    transition: all 0.3s ease;
}

/* Fade in animation for alerts */
.alert {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Style for checked items */
.form-check-input:checked + .form-check-label {
    font-weight: bold;
    color: #009ef7;
}

/* Level validation states */
.d-flex.flex-stack.level-invalid {
    border: 2px solid #f1416c !important;
    border-radius: 8px;
    padding: 10px !important;
    margin-bottom: 10px !important;
    background: rgba(241, 65, 108, 0.05) !important;
    position: relative;
}

/*.d-flex.flex-stack.level-valid {
    border-left: 4px solid #50cd89 !important;
    background-color: rgba(80, 205, 137, 0.05) !important;
}*/

.level-warning {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.8rem;
    color: #f1416c;
    background: rgba(241, 65, 108, 0.1);
    padding: 2px 8px;
    border-radius: 4px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Validation error alert */
.validation-error-alert {
    animation: slideDown 0.4s ease-out;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(241, 65, 108, 0.3);
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Loading state */
.form-submitting {
    opacity: 0.7;
    pointer-events: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .list-group-item {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .list-group-item .form-select {
        width: 100% !important;
        margin-top: 10px;
    }
    
    .level-warning {
        position: static !important;
        margin-top: 10px;
        display: block;
    }
}
</style>

<?= $this->include('app/school/setup/toolbar') ?>

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<!--begin::Content container-->
	<div id="kt_app_content_container" class="app-container container-xxl">
	    
	    <?= $this->include('app/school/setup/navbar') ?>
	    
	    <!-- Flash messages will appear here -->
	    <div id="flash-messages-container">
	        <?= $this->include('templates/flash_messages') ?>
	    </div>
	    
		<!--begin::Basic info-->
		<div class="card mb-5 mb-xl-10">
			<!--begin::Card header-->
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
				<!--begin::Card title-->
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">Step 3 - School Subject Department Assignment Registration</h3>
				</div>
				<!--end::Card title-->
			</div>
			<!--begin::Card header-->
			<!--begin::Content-->
			<div id="kt_account_settings_profile_details" class="collapse show">
				<!--begin::Form-->
				<form id="kt_account_profile_details_form" class="form" method="post" action="<?= site_url('school/configure') ?>">
				    <?= csrf_field() ?>
				    <input type="hidden" name="current_step" value="2" id="current_step">
					<!--begin::Card body-->
					<div class="card-body border-top p-9">
						
						<!--begin::Input group-->
						<div class="mb-10">
							<!--begin::Label-->
							<h3 class="fw-semibold badge-light-primary mb-8" style="width:100%;padding:20px;">School Subject</h3>
							<!--end::Label-->
							
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
                                    <h4 class="mb-1 text-info">IMPORTANT NOTICE:</h4>
                                    <!--end::Title-->
                            
                                    <!--begin::Content-->
                                    <span>To ensure proper Navuli account functionality, it is mandatory to assign at least one subject per level to a school department. For comprehensive system operation, we strongly recommend assigning all available subjects to their respective departments. Incomplete or inaccurate assignments may adversely affect account performance.</span>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Alert-->
							
							
							
							<!-- Add hidden input to track level count -->
                            <input type="hidden" name="level_count" value="<?= count($schLevel ?? []) ?>">
                            
                            <!-- Add hidden inputs for level IDs -->
                            <?php if (!empty($schLevel)): ?>
                                <?php foreach($schLevel as $row): ?>
                                    <input type="hidden" name="level_ids[]" value="<?= esc($row['level_id']) ?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
							
							<?php
						    // First check if $schLevel is valid and not empty
                            if (!empty($schLevel) && is_iterable($schLevel)):
                                $i = 1;
                                foreach($schLevel as $row):
                                    
                                    $name = esc($row['level_name'] ?? '');
                                    $level_id = esc($row['level_id'] ?? '');
    						?>
    						<!--begin::Item-->
    						<div class="d-flex flex-stack level-section" data-level-id="<?= $level_id ?>">
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
    									<span class="text-gray-500 fw-semibold d-block fs-6"><?= 'Register subject(s) for '.$name.' below by checking on the subject and assigning it to a school department.' ?></span>
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
    						    helper('subject_display');
    						?>
    						
    						    <div class="row level-content" data-level-id="<?= $level_id ?>">
    						        <?= \App\Helpers\show_subjects($level_id, [
                                        'empty_message' => 'No subject for ' . $name,
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
						<!--end::Input group-->
						
					</div>
					<!--end::Card body-->
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
						<button type="submit" class="btn btn-primary" id="submit-btn">Save Changes</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Highlight saved items
    <?php if (session()->getFlashdata('saved_subjects')): ?>
        const savedSubjects = <?= json_encode(session()->getFlashdata('saved_subjects')) ?>;
        savedSubjects.forEach(function(subjectId) {
            const checkbox = document.querySelector(`input[value="${subjectId}"]`);
            if (checkbox) {
                const row = checkbox.closest('.list-group-item');
                row.classList.add('saved');
                
                // Add a checkmark icon temporarily
                const checkmark = document.createElement('span');
                checkmark.innerHTML = ' ✓';
                checkmark.style.color = '#50cd89';
                checkmark.style.fontWeight = 'bold';
                checkmark.style.marginLeft = '5px';
                checkmark.style.animation = 'fadeIn 0.3s ease-in';
                
                const label = checkbox.nextElementSibling;
                if (label) {
                    label.appendChild(checkmark);
                    
                    // Remove highlight and checkmark after 5 seconds
                    setTimeout(function() {
                        row.classList.remove('saved');
                        checkmark.remove();
                    }, 5000);
                }
            }
        });
    <?php endif; ?>
    
    // Function to check ALL levels for validation in REAL-TIME
    function checkAllLevelsValidation() {
        document.querySelectorAll('.level-section').forEach(function(levelSection) {
            checkSingleLevelValidation(levelSection);
        });
    }
    
    // Function to check a single level for validation
    function checkSingleLevelValidation(levelSection) {
        if (!levelSection) return;
        
        const levelId = levelSection.getAttribute('data-level-id');
        const levelContent = document.querySelector(`.level-content[data-level-id="${levelId}"]`);
        
        if (!levelContent) return;
        
        // Get all checkboxes in this level
        const checkboxes = levelContent.querySelectorAll('input[type="checkbox"]');
        let hasValidPair = false;
        
        // Check each checkbox if it's checked AND has a selected department
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                const row = checkbox.closest('.list-group-item');
                const select = row.querySelector('select');
                
                if (select && select.value !== '') {
                    hasValidPair = true;
                }
            }
        });
        
        // Update the level display
        updateLevelDisplay(levelSection, hasValidPair);
    }
    
    // Function to update level display
    function updateLevelDisplay(levelSection, hasValidPair) {
        // Remove existing warning
        const existingWarning = levelSection.querySelector('.level-warning');
        if (existingWarning) {
            existingWarning.remove();
        }
        
        // Update styling
        if (hasValidPair) {
            // Level is valid - remove all invalid styling
            levelSection.classList.remove('level-invalid');
            levelSection.style.border = '';
            levelSection.style.borderRadius = '';
            levelSection.style.padding = '';
            levelSection.style.marginBottom = '';
            levelSection.style.background = '';
        } else {
            // Level is invalid - add invalid styling
            levelSection.classList.add('level-invalid');
            
            // Add warning message
            const warning = document.createElement('div');
            warning.className = 'level-warning';
            warning.textContent = '⚠️ Requires at least one subject with department';
            levelSection.appendChild(warning);
        }
    }
    
    // Function to update row visual state
    function updateRowVisualState(row) {
        const checkbox = row.querySelector('input[type="checkbox"]');
        const select = row.querySelector('select');
        
        if (checkbox && select) {
            if (checkbox.checked && select.value !== '') {
                // Valid state
                row.classList.remove('invalid');
                checkbox.classList.remove('is-invalid');
                select.classList.remove('is-invalid');
                checkbox.classList.add('is-valid');
                select.classList.add('is-valid');
            } else if (checkbox.checked && select.value === '') {
                // Invalid: Checked but no department
                row.classList.add('invalid');
                checkbox.classList.remove('is-valid');
                select.classList.remove('is-valid');
                checkbox.classList.add('is-invalid');
                select.classList.add('is-invalid');
            } else if (!checkbox.checked && select.value !== '') {
                // Invalid: Department but not checked
                row.classList.add('invalid');
                checkbox.classList.remove('is-valid');
                select.classList.remove('is-valid');
                checkbox.classList.add('is-invalid');
                select.classList.add('is-invalid');
            } else {
                // Neutral state
                row.classList.remove('invalid');
                checkbox.classList.remove('is-valid', 'is-invalid');
                select.classList.remove('is-valid', 'is-invalid');
            }
        }
    }
    
    // Event listener for ALL select elements
    document.addEventListener('change', function(e) {
        // Check if it's a select element in a list-group-item
        if (e.target.tagName === 'SELECT' && e.target.closest('.list-group-item')) {
            const row = e.target.closest('.list-group-item');
            const checkbox = row.querySelector('input[type="checkbox"]');
            
            if (checkbox) {
                if (e.target.value !== '') {
                    // If department is selected, check the checkbox
                    checkbox.checked = true;
                } else {
                    // If department is deselected, uncheck the checkbox
                    checkbox.checked = false;
                }
                
                // Update row visual state
                updateRowVisualState(row);
            }
            
            // Check ALL levels for validation (in case this affects multiple levels)
            checkAllLevelsValidation();
        }
        
        // Check if it's a checkbox in a list-group-item
        if (e.target.type === 'checkbox' && e.target.closest('.list-group-item')) {
            const row = e.target.closest('.list-group-item');
            const select = row.querySelector('select');
            
            if (select) {
                if (!e.target.checked) {
                    // If checkbox is unchecked, clear the department selection
                    select.value = '';
                }
                
                // Update row visual state
                updateRowVisualState(row);
            }
            
            // Check ALL levels for validation
            checkAllLevelsValidation();
        }
    });
    
    // Initialize visual states on page load
    document.querySelectorAll('.list-group-item').forEach(function(row) {
        updateRowVisualState(row);
    });
    
    // Initialize level validation on page load
    checkAllLevelsValidation();

    // Validate on submit
    document.getElementById('kt_account_profile_details_form').addEventListener('submit', function(e) {
        console.log('Form submit triggered');
        
        let isValid = true;
        const errors = [];
        
        // Add submitting state
        this.classList.add('form-submitting');
        const submitBtn = this.querySelector('#submit-btn');
        const originalText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            submitBtn.disabled = true;
        }

        // Clear previous validation styles
        document.querySelectorAll('.is-invalid, .invalid').forEach(el => {
            el.classList.remove('is-invalid', 'invalid');
        });

        // Check each level section
        document.querySelectorAll('.level-section').forEach(function(levelSection) {
            const levelId = levelSection.getAttribute('data-level-id');
            const levelContent = document.querySelector(`.level-content[data-level-id="${levelId}"]`);
            const levelName = levelSection.querySelector('a').textContent;
            
            if (!levelContent) return;
            
            const checkboxes = levelContent.querySelectorAll('input[type="checkbox"]:checked');
            const allCheckboxes = levelContent.querySelectorAll('input[type="checkbox"]');
            
            let levelHasValidPair = false;
            
            // Skip validation if no subjects available
            if (allCheckboxes.length === 0) return;
            
            // Check each checked checkbox has a department
            checkboxes.forEach(function(checkbox) {
                const row = checkbox.closest('.list-group-item');
                const select = row.querySelector('select');
                const subjectName = checkbox.nextElementSibling.textContent.trim();
                
                if (!select || select.value === '') {
                    isValid = false;
                    select.classList.add('is-invalid');
                    checkbox.classList.add('is-invalid');
                    row.classList.add('invalid');
                    errors.push(`Please select a department for "${subjectName}" in ${levelName}`);
                } else {
                    levelHasValidPair = true;
                }
            });
            
            // Check for departments selected without checkbox checked
            levelContent.querySelectorAll('select').forEach(function(select) {
                if (select.value !== '') {
                    const row = select.closest('.list-group-item');
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    
                    if (!checkbox || !checkbox.checked) {
                        isValid = false;
                        select.classList.add('is-invalid');
                        if (checkbox) checkbox.classList.add('is-invalid');
                        row.classList.add('invalid');
                        const subjectName = checkbox ? checkbox.nextElementSibling.textContent.trim() : 'Subject';
                        errors.push(`Subject "${subjectName}" in ${levelName} has a department selected but is not checked`);
                    }
                }
            });

            // MAIN RULE: Check if level has at least one valid subject-department pair
            if (!levelHasValidPair) {
                isValid = false;
                errors.push(`At least one subject must be checked AND assigned to a department for ${levelName}`);
                
                // Make sure level is marked as invalid
                levelSection.classList.add('level-invalid');
                
                // Add warning if not exists
                if (!levelSection.querySelector('.level-warning')) {
                    const warning = document.createElement('div');
                    warning.className = 'level-warning';
                    warning.textContent = '⚠️ Requires at least one subject with department';
                    levelSection.appendChild(warning);
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            
            // Remove submitting state
            this.classList.remove('form-submitting');
            if (submitBtn) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
            
            // Remove any existing validation error alerts
            const existingAlerts = document.querySelectorAll('.validation-error-alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // Show error alert
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger alert-dismissible fade show validation-error-alert';
            errorAlert.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Form Validation Failed</h4>
                        <p class="mb-2">Please fix the following issues before submitting:</p>
                        <ul class="mb-0">
                            ${errors.map(error => `<li class="mb-1">${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="this.parentElement.remove()"></button>
            `;
            
            // Insert at the top of the content container (after flash messages)
            const flashContainer = document.getElementById('flash-messages-container');
            if (flashContainer && flashContainer.nextElementSibling) {
                flashContainer.parentNode.insertBefore(errorAlert, flashContainer.nextElementSibling);
            } else if (flashContainer) {
                flashContainer.parentNode.appendChild(errorAlert);
            } else {
                // Fallback
                const contentContainer = document.getElementById('kt_app_content_container');
                if (contentContainer) {
                    contentContainer.insertBefore(errorAlert, contentContainer.firstChild);
                }
            }
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            // If valid, allow form submission
            // Remove submitting state after a short delay to show animation
            setTimeout(() => {
                this.classList.remove('form-submitting');
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            }, 1000);
        }
    });
    
    // Add a small delay to ensure DOM is fully loaded, then check validation one more time
    setTimeout(() => {
        checkAllLevelsValidation();
    }, 100);
});
</script>