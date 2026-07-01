<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">User Management</h1>
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
				<li class="breadcrumb-item text-muted">Add User</li>
			</ul>
		</div>
	</div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<div id="kt_app_content_container" class="app-container container-xxl">
	    
	    <?= $this->include('templates/flash_messages') ?>
	    
		<!--begin::Card-->
		<div class="card">
			<!--begin::Card header-->
			<div class="card-header">
				<h3 class="card-title">User Information</h3>
				<div class="card-toolbar">
					<a href="<?= base_url('user') ?>" class="btn btn-light">
						<i class="ki-duotone ki-arrow-left fs-2">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						Back to List
					</a>
				</div>
			</div>
			<!--end::Card header-->
			
			<!--begin::Card body-->
			<div class="card-body">
				<!--begin::Form-->
				<form id="kt_user_add_form" action="<?= base_url('user/store') ?>" method="POST" enctype="multipart/form-data">
					<?= csrf_field() ?>
					
					<!--begin::Row - Personal Information-->
					<div class="row mb-6">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Personal Information</label>
						
						<!--begin::First Name-->
						<div class="col-lg-4 mb-3">
							<label class="form-label required">First Name</label>
							<input type="text" name="fname" class="form-control <?= session('validation')?->hasError('fname') ? 'is-invalid' : '' ?>" 
							       placeholder="Enter first name" value="<?= old('fname') ?>" />
							<?php if (session('validation')?->hasError('fname')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('fname') ?></div>
							<?php endif; ?>
						</div>
						<!--end::First Name-->
						
						<!--begin::Last Name-->
						<div class="col-lg-4 mb-3">
							<label class="form-label required">Last Name</label>
							<input type="text" name="lname" class="form-control <?= session('validation')?->hasError('lname') ? 'is-invalid' : '' ?>" 
							       placeholder="Enter last name" value="<?= old('lname') ?>" />
							<?php if (session('validation')?->hasError('lname')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('lname') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Last Name-->
						
						<!--begin::Other Name-->
						<div class="col-lg-4 mb-3">
							<label class="form-label">Other Name</label>
							<input type="text" name="oname" class="form-control" 
							       placeholder="Enter other name (optional)" value="<?= old('oname') ?>" />
						</div>
						<!--end::Other Name-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Username-->
					<div class="row mb-3">
						<div class="col-lg-6 mb-3">
							<label class="form-label required fw-bold">User ID</label>
							<div class="input-group">
								<input type="text" name="username" id="username_field"
								       class="form-control <?= session('validation')?->hasError('username') ? 'is-invalid' : '' ?>"
								       placeholder="Auto-generated"
								       value="<?= old('username') ?>"
								       maxlength="10" inputmode="numeric"
								       pattern="\d{10}" autocomplete="off" />
								<button type="button" class="btn btn-light-primary fw-semibold" id="btn_generate_username" title="Generate new username">
									<i class="ki-duotone ki-arrows-circle fs-5"><span class="path1"></span><span class="path2"></span></i>
									Generate
								</button>
							</div>
							<?php if (session('validation')?->hasError('username')): ?>
								<div class="text-danger fs-8 mt-1"><?= session('validation')->getError('username') ?></div>
							<?php endif; ?>
							<div class="form-text">10-digit unique ID. Auto-generated on load — you can also type one manually or click Generate.</div>
						</div>
					</div>
					<!--end::Row - Username-->

					<!--begin::Row - Contact Information-->
					<div class="row mb-3">
						<!--begin::Email-->
						<div class="col-lg-6 mb-3">
							<label class="form-label" id="email-label">Email</label>
							<input type="email" name="email" id="email-field" class="form-control <?= session('validation')?->hasError('email') ? 'is-invalid' : '' ?>"
							       placeholder="user@example.com" value="<?= old('email') ?>" />
							<?php if (session('validation')?->hasError('email')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('email') ?></div>
							<?php endif; ?>
							<div class="form-text">Optional — must be a valid email address if provided</div>
						</div>
						<!--end::Email-->
						
						<!--begin::Phone-->
						<div class="col-lg-6 mb-3">
							<label class="form-label">Phone</label>
							<input type="text" name="phone" class="form-control <?= session('validation')?->hasError('phone') ? 'is-invalid' : '' ?>" 
							       placeholder="1234567" value="<?= old('phone') ?>" maxlength="7" />
							<?php if (session('validation')?->hasError('phone')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('phone') ?></div>
							<?php endif; ?>
							<div class="form-text">7-digit phone number</div>
						</div>
						<!--end::Phone-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Personal Details-->
					<div class="row">
						<!--begin::Gender-->
						<div class="col-lg-4 mb-3">
							<label class="form-label required">Gender</label>
							<select name="gender" class="form-select <?= session('validation')?->hasError('gender') ? 'is-invalid' : '' ?>">
								<option value="">Select Gender...</option>
								<option value="Male" <?= old('gender') === 'Male' ? 'selected' : '' ?>>Male</option>
								<option value="Female" <?= old('gender') === 'Female' ? 'selected' : '' ?>>Female</option>
							</select>
							<?php if (session('validation')?->hasError('gender')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('gender') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Gender-->
						
						<!--begin::Date of Birth-->
						<div class="col-lg-4 mb-3">
							<label class="form-label">Date of Birth</label>
							<input type="date" name="dob" class="form-control  <?= session('validation')?->hasError('dob') ? 'is-invalid' : '' ?>" value="<?= old('dob') ?>" />
							<?php if (session('validation')?->hasError('dob')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('dob') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Date of Birth-->
						
					</div>
					<!--end::Row-->
					
					<!--begin::Input group - Province & District-->
					<div class="fv-row mb-3">
						<!--begin::Row-->
                        <div class="row">
                            <!--begin::Col - Province-->
                            <div class="col-lg-6">
                                <label class="form-label mb-3 required">Select Province</label>
                                <select class="form-select selectProvince <?= session('validation')?->hasError('province') ? 'is-invalid' : '' ?>" 
                                        aria-label="Select province" 
                                        name="province"
                                        id="province-select">
                                    <option value="">Select ...</option>
                                    <?php if (!empty($province)): ?>
                                        <?php foreach($province as $row): ?>
                                            <option value="<?= $row['province_id'] ?>" 
                                                    <?= (old('province') == $row['province_id']) ? 'selected' : '' ?>>
                                                <?= esc($row['province_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                
                                <!-- Error display for province -->
                                <?php if (session('validation')?->hasError('province')): ?>
                                    <div class="invalid-feedback d-block"><?= session('validation')->getError('province') ?></div>
                                <?php endif; ?>
                            </div>
                            <!--end::Col-->
                            
                            <!--begin::Col - District-->
                            <div class="col-lg-6">
                                <span id="loader" style="display: none;">
                                    <img src="<?= base_url('resources/ajax-loader/ajax-loader-3.gif') ?>" />
                                </span>
                                
                                <div class="response">
                                    <?php if (!empty(old('province'))): ?> 
                                        <label class="form-label mb-3" id="district-label">Select District</label>
                                        
                                        <select class="form-select <?= session('validation')?->hasError('district') ? 'is-invalid' : '' ?>" 
                                                aria-label="Select district" 
                                                name="district"
                                                id="district-select">
                                            
                                            
                                            <?php if (!empty(session('provinceDistrict'))):
                                                $provinceDistrict = session('provinceDistrict');
                                                
                                                if(old('province') != 16){
                                                    echo '<option value="">Select ...</option>';
                                                }
                                            ?>
                                                <?php foreach ($provinceDistrict as $row): ?>
                                                    <option value="<?= esc($row['district_id']) ?>" 
                                                        <?= (old('district') == $row['district_id']) ? 'selected' : '' ?>>
                                                        <?= esc($row['district_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    
                                        <!-- Error display for district -->
                                        <?php if (session('validation')?->hasError('district')): ?>
                                            <div class="invalid-feedback d-block"><?= session('validation')->getError('district') ?></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
					</div>
					<!--end::Input group-->
					
					<!--begin::Row - Address-->
					<div class="row mb-3">
						<div class="col-lg-12 mb-3">
							<label class="form-label">Address</label>
							<textarea name="address" class="form-control" rows="3" 
							          placeholder="Enter full address"><?= old('address') ?></textarea>
						</div>
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Profile Photo-->
					<div class="row mb-8">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-4">Profile Photo</label>
						<div class="col-lg-12">
							<!--begin::Image input-->
							<div class="image-input image-input-outline image-input-empty"
							     data-kt-image-input="true"
							     style="background-image: url('<?= base_url('assets/media/avatars/blank.png') ?>')">

								<!--begin::Preview-->
								<div class="image-input-wrapper w-125px h-125px"
								     style="background-image: url('<?= base_url('assets/media/avatars/blank.png') ?>')">
								</div>
								<!--end::Preview-->

								<!--begin::Edit button-->
								<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
								       data-kt-image-input-action="change"
								       data-bs-toggle="tooltip"
								       title="Upload photo">
									<i class="ki-duotone ki-pencil fs-7">
										<span class="path1"></span><span class="path2"></span>
									</i>
									<input type="file" name="photo" accept=".png,.jpg,.jpeg,.gif" />
									<input type="hidden" name="photo_remove" />
								</label>
								<!--end::Edit button-->

								<!--begin::Cancel button-->
								<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
								      data-kt-image-input-action="cancel"
								      data-bs-toggle="tooltip"
								      title="Cancel">
									<i class="ki-duotone ki-cross fs-2">
										<span class="path1"></span><span class="path2"></span>
									</i>
								</span>
								<!--end::Cancel button-->

								<!--begin::Remove button-->
								<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
								      data-kt-image-input-action="remove"
								      data-bs-toggle="tooltip"
								      title="Remove photo">
									<i class="ki-duotone ki-cross fs-2">
										<span class="path1"></span><span class="path2"></span>
									</i>
								</span>
								<!--end::Remove button-->

							</div>
							<!--end::Image input-->

							<div class="form-text mt-3">Allowed file types: PNG, JPG, GIF. Max size: 2 MB.</div>
						</div>
					</div>
					<!--end::Row - Profile Photo-->
					
					<!--begin::Row - Account Information-->
					<div class="row mb-4">
					    <label class="col-lg-12 col-form-label fw-bold fs-5">Account Information</label>
						
						<!--begin::Role-->
						<div class="col-lg-4 mb-4">
							<label class="form-label required">Role</label>
							<select name="role_id" id="role-select" class="form-select <?= session('validation')?->hasError('role_id') ? 'is-invalid' : '' ?>">
								<option value="">Select Role...</option>
								<?php if (!empty($roles)): ?>
									<?php foreach ($roles as $role): ?>
										<?php 
										$roleId = is_array($role) ? $role['role_id'] : $role->role_id;
										$roleName = is_array($role) ? $role['role_name'] : $role->role_name;
										?>
										<option value="<?= $roleId ?>" data-role-name="<?= esc($roleName) ?>" <?= old('role_id') == $roleId ? 'selected' : '' ?>>
											<?= esc($roleName) ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<?php if (session('validation')?->hasError('role_id')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('role_id') ?></div>
							<?php endif; ?>
						</div>
						<!--end::Role-->
						
						<!--begin::Status-->
						<div class="col-lg-4 mb-4">
							<label class="form-label required">Status</label>
							<select name="user_status" class="form-select">
								<option value="Active" <?= old('user_status', 'Active') === 'Active' ? 'selected' : '' ?>>Active</option>
								<option value="Inactive" <?= old('user_status') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
							</select>
						</div>
						<!--end::Status-->
						
						<!--begin::Photo-->
						<div class="col-lg-4 mb-4" id="admission-selection" style="display: none;">
							<?php if (!empty($schools)): ?>
                                <label class="form-label required">Admission</label>
                                <select name="sch_id" class="form-select <?= session('validation')?->hasError('sch_id') ? 'is-invalid' : '' ?>">
                                    <option value="">Select a school</option>
                                    <?php foreach ($schools as $row): ?>
                                        <option value="<?= esc($row['sch_id']) ?>" <?= (old('sch_id') == $row['sch_id']) ? 'selected' : '' ?>>
                                            <?= esc($row['sch_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (session('validation')?->hasError('sch_id')): ?>
                                    <div class="invalid-feedback"><?= session('validation')->getError('sch_id') ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
						</div>
						<!--end::Photo-->
					</div>
					<!--end::Row-->
					
					<!--begin::Row - Student Enrollment (Hidden by default)-->
                    <div class="row mb-6" id="student-checkbox-row" style="display: none;">
                        <label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Enrollment Information</label>

                        <!--begin::Stream Alert-->
                        <div class="col-lg-12 mb-3" id="stream-alert" style="display:none;">
                            <div class="notice d-flex bg-light-danger rounded border border-danger border-dashed p-4">
                                <i class="ki-duotone ki-information-5 fs-2tx text-danger me-4 flex-shrink-0">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                </i>
                                <div class="text-gray-700 fs-7">
                                    Cannot perform enrolment for the school at the moment because the school stream is not configured.
                                </div>
                            </div>
                        </div>
                        <!--end::Stream Alert-->

                        <!--begin::Enrollment Fields-->
                        <div id="enrollment-fields" style="display:none;">

                            

                            <div class="row">
                                <!--begin::Stream-->
                                <div class="col-lg-2 mb-3">
                                    <label class="form-label required">Stream</label>
                                    <select name="stream_id_fk" id="enrol-stream-select"
                                            class="form-select <?= session('validation')?->hasError('stream_id_fk') ? 'is-invalid' : '' ?>">
                                        <option value="">Select a stream...</option>
                                    </select>
                                    <?php if (session('validation')?->hasError('stream_id_fk')): ?>
                                        <div class="invalid-feedback"><?= session('validation')->getError('stream_id_fk') ?></div>
                                    <?php endif; ?>
                                </div>
                                <!--end::Stream-->

                                <!--begin::Term-->
                                <div class="col-lg-2 mb-3">
                                    <label class="form-label required">Term</label>
                                    <select class="form-select <?= session('validation')?->hasError('enrol_term') ? 'is-invalid' : '' ?>" name="enrol_term">
                                        <option value="">Select</option>
                                        <option value="1" <?= old('enrol_term') == '1' ? 'selected' : '' ?>>Term 1</option>
                                        <option value="2" <?= old('enrol_term') == '2' ? 'selected' : '' ?>>Term 2</option>
                                        <option value="3" <?= old('enrol_term') == '3' ? 'selected' : '' ?>>Term 3</option>
                                    </select>
                                    <?php if (session('validation')?->hasError('enrol_term')): ?>
                                        <div class="invalid-feedback"><?= session('validation')->getError('enrol_term') ?></div>
                                    <?php endif; ?>
                                </div>
                                <!--end::Term-->

                                <!--begin::Year-->
                                <div class="col-lg-2 mb-3">
                                    <label class="form-label required">Year</label>
                                    <input class="form-control <?= session('validation')?->hasError('enrol_year') ? 'is-invalid' : '' ?>"
                                           type="number"
                                           name="enrol_year"
                                           value="<?= old('enrol_year', date('Y')) ?>"
                                           min="2000" max="2099">
                                    <?php if (session('validation')?->hasError('enrol_year')): ?>
                                        <div class="invalid-feedback"><?= session('validation')->getError('enrol_year') ?></div>
                                    <?php endif; ?>
                                </div>
                                <!--end::Year-->

                                <!--begin::Note-->
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Enrollment Notes</label>
                                    <textarea class="form-control <?= session('validation')?->hasError('enrol_note') ? 'is-invalid' : '' ?>"
                                              name="enrol_note"
                                              rows="3"
                                              placeholder="Optional notes about this enrollment"><?= old('enrol_note') ?></textarea>
                                    <?php if (session('validation')?->hasError('enrol_note')): ?>
                                        <div class="invalid-feedback"><?= session('validation')->getError('enrol_note') ?></div>
                                    <?php endif; ?>
                                </div>
                                <!--end::Note-->
                            </div>

                        <!--begin::Subject Section-->
                        <div id="exam-section" style="display:none;" class="mt-2">
                            <div class="separator separator-dashed my-4"></div>

                            <div class="d-flex align-items-center mb-3">
                                <i class="ki-duotone ki-book fs-2 text-primary me-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <span class="fw-bold fs-6 text-gray-800">Select Subjects</span>
                            </div>

                            <div id="exam-subjects-loading" class="text-muted fs-7 mb-3" style="display:none;">
                                <span class="spinner-border spinner-border-sm me-1 align-middle"></span> Loading subjects…
                            </div>

                            <div id="exam-subjects-content"></div>
                        </div>
                        <!--end::Subject Section-->

                        </div>

                        </div>
                        <!--end::Enrollment Fields-->
                    </div>
                    <!--end::Row-->
					
					<!--begin::Row - Parent Checkbox (Hidden by default)-->
					<div class="row mb-6" id="parent-checkbox-row">
						<div class="col-lg-12">
							<div class="form-check form-check-custom">
								<input class="form-check-input" type="checkbox" name="is_a_parent" value="1" id="is_a_parent" <?= old('is_a_parent') ? 'checked' : '' ?> />
								<label class="form-check-label fw-semibold text-gray-700" for="is_a_parent">
									Check if user is a parent
								</label>
							</div>
								<div class="form-text">Select this option if the user is also a parent and require to access the Navuli SMIS parent portal</div>
						</div>
					</div>
					<!--end::Row-->
					
					
					
					<div class="mb-6"></div>
					
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<a href="<?= base_url('user') ?>" class="btn btn-light me-3">Cancel</a>
						<button type="submit" class="btn btn-primary" id="submit-btn">
							<i class="ki-duotone ki-check fs-2"></i>
							Save User
						</button>
					</div>
					<!--end::Actions-->
					
				</form>
				<!--end::Form-->
			</div>
			<!--end::Card body-->
		</div>
		<!--end::Card-->
	</div>
</div>
<!--end::Content-->

<script>
"use strict";

KTImageInput.createInstances();

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kt_user_add_form');
    const roleSelect = document.getElementById('role-select');
    const parentCheckboxRow = document.getElementById('parent-checkbox-row');
    const studentCheckboxRow = document.getElementById('student-checkbox-row');
    const admissionSelection = document.getElementById('admission-selection');
    
    // Function to toggle parent checkbox visibility
    function toggleParentCheckbox() {
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const roleName = selectedOption.getAttribute('data-role-name');
        
        // Show checkbox only if role is NOT "Parent" or "Student"
        if (roleName && roleName !== 'Parent' && roleName !== 'Student') {
            parentCheckboxRow.classList.remove('d-none');
            parentCheckboxRow.removeAttribute('style');
        } else {
            parentCheckboxRow.classList.add('d-none');
            if (document.getElementById('is_a_parent')) {
                document.getElementById('is_a_parent').checked = false;
            }
        }
    }
    
    // Function to toggle student enrollment section
    function toggleStudentEnrollment() {
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const roleName = selectedOption.getAttribute('data-role-name');

        if (roleName && roleName === 'Student') {
            studentCheckboxRow.classList.remove('d-none');
            studentCheckboxRow.removeAttribute('style');
            // If school already selected, fetch streams immediately
            const schoolSel = document.querySelector('select[name="sch_id"]');
            if (schoolSel && schoolSel.value) {
                fetchSchoolStreams(schoolSel.value);
            }
        } else {
            studentCheckboxRow.classList.add('d-none');
            resetEnrollmentFields();
        }
    }

    function resetEnrollmentFields() {
        const termField = document.querySelector('select[name="enrol_term"]');
        const yearField = document.querySelector('input[name="enrol_year"]');
        const noteField = document.querySelector('textarea[name="enrol_note"]');
        const streamField = document.getElementById('enrol-stream-select');
        if (termField)  termField.value  = '';
        if (yearField)  yearField.value  = '<?= date('Y') ?>';
        if (noteField)  noteField.value  = '';
        if (streamField) { streamField.innerHTML = '<option value="">Select a stream...</option>'; }
        document.getElementById('stream-alert').style.display    = 'none';
        document.getElementById('enrollment-fields').style.display = 'none';
        resetExamSection();
    }

    // Old subject selections captured from redirect-back (cleared after first apply)
    <?php
        $ciOldPost      = session('_ci_old_input')['post'] ?? [];
        $oldCoreSubs    = array_map('intval', (array)($ciOldPost['exam_core_subs'] ?? []));
        $oldOptGroups   = [];
        foreach ($ciOldPost as $_key => $_val) {
            if (preg_match('/^exam_opt_group_(\d+)$/', $_key, $_m)) {
                $oldOptGroups[(int)$_m[1]] = (int)$_val;
            }
        }
    ?>
    let _oldCoreSubs  = <?= json_encode($oldCoreSubs) ?>;
    let _oldOptGroups = <?= json_encode($oldOptGroups) ?>;

    function applyOldSubjectSelections() {
        if (_oldCoreSubs.length > 0) {
            document.querySelectorAll('input.exam-core-sub').forEach(function(cb) {
                cb.checked = _oldCoreSubs.includes(parseInt(cb.value));
            });
        }
        Object.keys(_oldOptGroups).forEach(function(grp) {
            const radio = document.querySelector(
                'input[name="exam_opt_group_' + grp + '"][value="' + _oldOptGroups[grp] + '"]'
            );
            if (radio) radio.checked = true;
        });
        _oldCoreSubs  = [];
        _oldOptGroups = {};
    }

    function resetExamSection() {
        document.getElementById('exam-section').style.display        = 'none';
        document.getElementById('exam-subjects-loading').style.display = 'none';
        document.getElementById('exam-subjects-content').innerHTML   = '';
    }

    function fetchExamForStream(streamId) {
        if (!streamId) { resetExamSection(); return; }

        const examSection = document.getElementById('exam-section');
        const subsLoading = document.getElementById('exam-subjects-loading');
        const subsContent = document.getElementById('exam-subjects-content');

        examSection.style.display = '';
        subsLoading.style.display = '';
        subsContent.innerHTML     = '';

        $.ajax({
            url:      '<?= base_url('enrolment/subjects') ?>/' + streamId,
            type:     'GET',
            dataType: 'json',
            success: function(sr) {
                subsLoading.style.display = 'none';
                renderExamSubjects(sr, subsContent);
                applyOldSubjectSelections();
            },
            error: function() {
                subsLoading.style.display = 'none';
                subsContent.innerHTML = '<div class="text-danger fs-7">Could not load subjects.</div>';
            }
        });
    }

    function renderExamSubjects(data, container) {
        if ((!data.core || data.core.length === 0) && (!data.optional || data.optional.length === 0)) {
            container.innerHTML = '<div class="text-muted fs-7">No subjects configured for this stream.</div>';
            return;
        }

        let html = '';

        if (data.core && data.core.length > 0) {
            html += '<div class="mb-4">';
            html += '<div class="fw-semibold text-gray-700 fs-7 mb-2">Core Subjects <span class="text-muted">(select all that apply)</span></div>';
            html += '<div class="row g-2">';
            data.core.forEach(function(s) {
                html += '<div class="col-lg-3 col-md-4 col-sm-6">'
                      + '<div class="form-check">'
                      + '<input class="form-check-input exam-core-sub" type="checkbox"'
                      + ' name="exam_core_subs[]" value="' + s.sch_sub_id + '"'
                      + ' id="ec_' + s.sch_sub_id + '" checked>'
                      + '<label class="form-check-label fs-7" for="ec_' + s.sch_sub_id + '">'
                      + escHtml(s.subject_name)
                      + '</label>'
                      + '</div></div>';
            });
            html += '</div></div>';
        }

        if (data.optional && data.optional.length > 0) {
            // Group by option_num
            const groups = {};
            data.optional.forEach(function(s) {
                if (!groups[s.option_num]) groups[s.option_num] = [];
                groups[s.option_num].push(s);
            });

            Object.keys(groups).forEach(function(grp) {
                html += '<div class="card card-bordered mb-3">';
                html += '<div class="card-header min-h-40px py-2 px-4">';
                html += '<span class="card-title fw-semibold text-gray-700 fs-7 m-0">Optional Group ' + grp + '</span>';
                html += '<div class="card-toolbar"><span class="badge badge-light-warning fs-8">choose one</span></div>';
                html += '</div>';
                html += '<div class="card-body py-3 px-4">';
                html += '<div class="row g-2">';
                groups[grp].forEach(function(s, i) {
                    html += '<div class="col-lg-3 col-md-4 col-sm-6">'
                          + '<div class="form-check">'
                          + '<input class="form-check-input" type="radio"'
                          + ' name="exam_opt_group_' + grp + '" value="' + s.sch_sub_id + '"'
                          + ' id="eo_' + grp + '_' + s.sch_sub_id + '"'
                          + (i === 0 ? ' checked' : '') + '>'
                          + '<label class="form-check-label fs-7" for="eo_' + grp + '_' + s.sch_sub_id + '">'
                          + escHtml(s.subject_name)
                          + '</label>'
                          + '</div></div>';
                });
                html += '</div></div></div>';
            });
        }

        container.innerHTML = html;
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function fetchSchoolStreams(schId) {
        if (!schId) {
            document.getElementById('stream-alert').style.display    = 'none';
            document.getElementById('enrollment-fields').style.display = 'none';
            return;
        }

        $.ajax({
            url:  '<?= base_url('classroom/streams') ?>/' + schId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const alertEl   = document.getElementById('stream-alert');
                const fieldsEl  = document.getElementById('enrollment-fields');
                const streamSel = document.getElementById('enrol-stream-select');

                if (response.streams && response.streams.length > 0) {
                    streamSel.innerHTML = '<option value="">Select a stream...</option>';
                    let lastLevel = '';
                    response.streams.forEach(function(s) {
                        if (s.level_name !== lastLevel) {
                            if (lastLevel !== '') streamSel.innerHTML += '</optgroup>';
                            streamSel.innerHTML += '<optgroup label="' + s.level_name + '">';
                            lastLevel = s.level_name;
                        }
                        streamSel.innerHTML += '<option value="' + s.stream_id + '">' + s.stream_name + '</option>';
                    });
                    if (lastLevel !== '') streamSel.innerHTML += '</optgroup>';

                    // Restore previously selected stream (e.g. after server validation failure)
                    const oldStream = '<?= old('stream_id_fk') ?>';
                    if (oldStream) {
                        streamSel.value = oldStream;
                        fetchExamForStream(oldStream);
                    }

                    alertEl.style.display  = 'none';
                    fieldsEl.style.display = '';
                } else {
                    alertEl.style.display  = '';
                    fieldsEl.style.display = 'none';
                }
            },
            error: function() {
                document.getElementById('stream-alert').style.display  = '';
                document.getElementById('enrollment-fields').style.display = 'none';
            }
        });
    }

    // Watch stream select — fetch exam when stream changes
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'enrol-stream-select') {
            resetExamSection();
            if (e.target.value) {
                fetchExamForStream(e.target.value);
            }
        }
    });

    // Watch school select — fetch streams when Student role is active
    const schoolSelect = document.querySelector('select[name="sch_id"]');
    if (schoolSelect) {
        schoolSelect.addEventListener('change', function() {
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const roleName = selectedOption.getAttribute('data-role-name');
            if (roleName === 'Student') {
                fetchSchoolStreams(this.value);
            }
        });
    }
    
    // Function to toggle admission/school selection
    function toggleAdmissionSelection() {
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const roleName = selectedOption.getAttribute('data-role-name');
        
        // Show admission selection if role is NOT "Parent" and NOT "Super Admin"
        if (roleName && roleName !== 'Parent' && roleName !== 'Super Admin') {
            admissionSelection.classList.remove('d-none');
            admissionSelection.removeAttribute('style');
        } else {
            admissionSelection.classList.add('d-none');
            // Clear school selection when hidden
            const schoolSelect = admissionSelection.querySelector('select');
            if (schoolSelect) schoolSelect.value = '';
        }
    }
    
    // Main function to handle all role-based visibility
    function handleRoleChange() {
        toggleParentCheckbox();
        toggleStudentEnrollment();
        toggleAdmissionSelection();
    }
    
    // Listen for role change
    if (roleSelect) {
        roleSelect.addEventListener('change', handleRoleChange);
        
        // Trigger on page load if role is already selected
        if (roleSelect.value) {
            handleRoleChange();
        }
    }
    
    // Helper: check if an element is currently visible (not hidden by d-none or inline style)
    function isVisible(el) {
        return el && !el.classList.contains('d-none') && el.style.display !== 'none';
    }

    // Helper: mark a field invalid and show a message
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        let fb = field.parentElement.querySelector('.js-invalid-feedback');
        if (!fb) {
            fb = document.createElement('div');
            fb.className = 'invalid-feedback js-invalid-feedback';
            field.parentElement.appendChild(fb);
        }
        fb.textContent = message;
    }

    // Helper: clear all client-side validation states set by this script
    function clearClientErrors() {
        document.querySelectorAll('.js-invalid-feedback').forEach(el => el.remove());
        [
            roleSelect,
            document.querySelector('select[name="sch_id"]'),
            document.getElementById('enrol-stream-select'),
            document.querySelector('select[name="enrol_term"]'),
            document.querySelector('input[name="enrol_year"]')
        ].forEach(function(el) { if (el) el.classList.remove('is-invalid'); });
    }

    // Form submission — only block client-side for conditional fields that are
    // dynamically shown/hidden. Always-required fields (fname, lname, gender,
    // province, role) are validated server-side so the flash banner and inline
    // errors appear naturally after the round-trip.
    if (form) {
        form.addEventListener('submit', function(e) {
            clearClientErrors();

            let valid = true;
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const roleName = selectedOption ? selectedOption.getAttribute('data-role-name') : null;

            // Validate admission (sch_id) when role is Student or Teacher
            if (roleName === 'Student' || roleName === 'Teacher') {
                const schSelect = document.querySelector('select[name="sch_id"]');
                if (schSelect && !schSelect.value) {
                    showFieldError(schSelect, 'Please select a school for admission');
                    valid = false;
                }
            }

            // Validate enrollment fields when role is Student and fields are visible
            if (roleName === 'Student') {
                const enrollmentFields = document.getElementById('enrollment-fields');
                if (isVisible(enrollmentFields)) {
                    const streamSel = document.getElementById('enrol-stream-select');
                    const termSel   = document.querySelector('select[name="enrol_term"]');
                    const yearInput = document.querySelector('input[name="enrol_year"]');

                    if (streamSel && !streamSel.value) {
                        showFieldError(streamSel, 'Please select a stream');
                        valid = false;
                    }
                    if (termSel && !termSel.value) {
                        showFieldError(termSel, 'Please select a term');
                        valid = false;
                    }
                    if (yearInput && !yearInput.value) {
                        showFieldError(yearInput, 'Please enter an enrollment year');
                        valid = false;
                    }
                }
            }

            if (!valid) {
                e.preventDefault();
                const firstError = form.querySelector('.is-invalid');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        });
    }
});

// AJAX for district loading
$(document).ready(function(){
    $(".selectProvince").change(function(){
        var id = $(this).val();
        
        if (!id) {
            $(".response").html('');
            return;
        }
        
        $.ajax({
            url: "<?= base_url('district/getDistrictByProvince') ?>",
            type: 'POST',
            data: {
                id: id, 
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            beforeSend: function(){
                $("#loader").show();
                $(".response").hide();
            },
            success: function(response){
                $('.response').empty();
                $(".response").show();
                
                if (response.success) {
                    $('.response').html(response.html);
                    
                    // Update district label to show required if province is selected
                    const districtLabel = $('.response').find('label');
                    if (districtLabel.length) {
                        districtLabel.addClass('required');
                    }
                } else {
                    $('.response').html('<label class="form-label mb-3">Select District</label><div class="alert alert-danger">' + response.error + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.log('Response:', xhr.responseText);
                $('.response').show().html('<label class="form-label mb-3">Select District</label><div class="alert alert-danger">Error loading districts</div>');
            },
            complete: function(){
                $("#loader").hide();
            }
        });
    });
});

// ── Province/District restoration on validation error redirect ───
$(document).ready(function() {
    const oldDistrict = '<?= old('district') ?>';
    const provinceEl  = document.getElementById('province-select');

    if (provinceEl && provinceEl.value) {
        const responseEl = document.querySelector('.response');
        const hasDist    = responseEl && responseEl.querySelector('select[name="district"]');

        if (hasDist) {
            // PHP rendered district list from session — ensure old selection is applied
            if (oldDistrict) {
                $(responseEl).find('select[name="district"]').val(oldDistrict);
            }
        } else {
            // Session data missing — reload districts via AJAX then restore selection
            $.ajax({
                url:      '<?= base_url('district/getDistrictByProvince') ?>',
                type:     'POST',
                data:     { id: provinceEl.value, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('.response').html(response.html);
                        if (oldDistrict) {
                            $('.response').find('select[name="district"]').val(oldDistrict);
                        }
                        const lbl = $('.response').find('label');
                        if (lbl.length) lbl.addClass('required');
                    }
                }
            });
        }
    }
});

// ── Username auto-generate ────────────────────────────────────────
const GENERATE_URL = '<?= base_url('user/generate-username') ?>';

function generateUsername(callback) {
    const btn = document.getElementById('btn_generate_username');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Generating…';

    fetch(GENERATE_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ki-duotone ki-arrows-circle fs-5"><span class="path1"></span><span class="path2"></span></i> Generate';
            if (data.success) {
                document.getElementById('username_field').value = data.username;
                document.getElementById('username_field').classList.remove('is-invalid');
                if (typeof callback === 'function') callback(data.username);
            } else {
                Swal.fire({ title: 'Error', text: data.message, icon: 'error', buttonsStyling: false,
                    confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger btn-sm' } });
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ki-duotone ki-arrows-circle fs-5"><span class="path1"></span><span class="path2"></span></i> Generate';
            Swal.fire({ title: 'Network Error', text: 'Could not reach the server.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger btn-sm' } });
        });
}

document.getElementById('btn_generate_username')?.addEventListener('click', function() {
    generateUsername();
});

// Auto-generate on page load if field is empty (fresh form, not a validation round-trip)
document.addEventListener('DOMContentLoaded', function() {
    const field = document.getElementById('username_field');
    if (field && !field.value) {
        generateUsername();
    }
});
</script>