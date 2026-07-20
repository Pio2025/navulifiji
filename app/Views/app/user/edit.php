<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">User Management</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted"><a href="<?= base_url('user') ?>" class="text-muted text-hover-primary">Users</a></li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted">Edit User</li>
			</ul>
		</div>
	</div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<div id="kt_app_content_container" class="app-container container-xxl">
		<?= $this->include('templates/flash_messages') ?>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Edit User: <?= esc($user['fname'] . ' ' . $user['lname']) ?></h3>
				<div class="card-toolbar">
					<a href="<?= base_url('user') ?>" class="btn btn-light me-2"><i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i> Back to List</a>
					<a href="<?= base_url('user/detail/' . $user['user_id']) ?>" class="btn btn-light-primary"><i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> View Details</a>
				</div>
			</div>
			<div class="card-body">
				<form id="kt_user_edit_form" action="<?= base_url('user/update/' . $user['user_id']) ?>" method="POST" enctype="multipart/form-data">
					<?= csrf_field() ?>

					<!--begin::Photo-->
					<div class="row mb-8">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-4">Profile Photo</label>
						<div class="col-lg-12">
							<!--begin::Image input-->
							<div class="image-input image-input-outline <?= empty($user['profile_photo']) ? 'image-input-empty' : '' ?>"
							     data-kt-image-input="true"
							     style="background-image: url('<?= base_url('assets/media/avatars/blank.png') ?>')">

								<!--begin::Preview-->
								<div class="image-input-wrapper w-125px h-125px"
								     style="background-image: url('<?= !empty($user['profile_photo'])
								         ? base_url('uploads/profilePhoto/' . $user['profile_photo'])
								         : base_url('assets/media/avatars/blank.png') ?>')">
								</div>
								<!--end::Preview-->

								<!--begin::Edit button-->
								<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
								       data-kt-image-input-action="change"
								       data-bs-toggle="tooltip"
								       title="Change photo">
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
								      title="Cancel photo">
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
					<!--end::Photo-->

					<!--begin::Personal Information-->
					<div class="row mb-6">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Personal Information</label>
						<div class="col-lg-4 mb-6">
							<label class="form-label required">First Name</label>
							<input type="text" name="fname" class="form-control <?= session('validation')?->hasError('fname') ? 'is-invalid' : '' ?>" placeholder="Enter first name" value="<?= old('fname', $user['fname']) ?>" />
							<?php if (session('validation')?->hasError('fname')): ?><div class="invalid-feedback"><?= session('validation')->getError('fname') ?></div><?php endif; ?>
						</div>
						<div class="col-lg-4 mb-6">
							<label class="form-label required">Last Name</label>
							<input type="text" name="lname" class="form-control <?= session('validation')?->hasError('lname') ? 'is-invalid' : '' ?>" placeholder="Enter last name" value="<?= old('lname', $user['lname']) ?>" />
							<?php if (session('validation')?->hasError('lname')): ?><div class="invalid-feedback"><?= session('validation')->getError('lname') ?></div><?php endif; ?>
						</div>
						<div class="col-lg-4 mb-6">
							<label class="form-label">Other Name</label>
							<input type="text" name="oname" class="form-control" placeholder="Enter other name (optional)" value="<?= old('oname', $user['oname'] ?? '') ?>" />
						</div>
					</div>
					<!--end::Personal Information-->

					<!--begin::FEMIS ID-->
					<div class="row mb-6">
						<div class="col-lg-4 mb-6">
							<label class="form-label">FEMIS ID</label>
							<input type="number" name="femis_id"
							       class="form-control <?= session('validation')?->hasError('femis_id') ? 'is-invalid' : '' ?>"
							       placeholder="Enter FEMIS ID (optional)"
							       value="<?= old('femis_id', $user['femis_id'] ?? '') ?>" min="1" />
							<?php if (session('validation')?->hasError('femis_id')): ?>
								<div class="invalid-feedback"><?= session('validation')->getError('femis_id') ?></div>
							<?php endif; ?>
						</div>
					</div>
					<!--end::FEMIS ID-->

					<!--begin::Contact Information-->
					<div class="row mb-6">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Contact Information</label>
						<div class="col-lg-6 mb-6">
							<label class="form-label required" id="email-label">Email</label>
							<input type="email" name="email" id="email-field" class="form-control <?= session('validation')?->hasError('email') ? 'is-invalid' : '' ?>" placeholder="user@example.com" value="<?= old('email', $user['email']) ?>" />
							<?php if (session('validation')?->hasError('email')): ?><div class="invalid-feedback"><?= session('validation')->getError('email') ?></div><?php endif; ?>
						</div>
						<div class="col-lg-6 mb-6">
							<label class="form-label">Phone</label>
							<input type="text" name="phone" class="form-control <?= session('validation')?->hasError('phone') ? 'is-invalid' : '' ?>" placeholder="+679 1234567" value="<?= old('phone', $user['phone'] ?? '') ?>" />
							<?php if (session('validation')?->hasError('phone')): ?><div class="invalid-feedback"><?= session('validation')->getError('phone') ?></div><?php endif; ?>
						</div>
					</div>
					<!--end::Contact Information-->

					<!--begin::Personal Details-->
					<div class="row mb-6">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Personal Details</label>
						<div class="col-lg-4 mb-6">
							<label class="form-label required">Gender</label>
							<select name="gender" class="form-select form-select-solid <?= session('validation')?->hasError('gender') ? 'is-invalid' : '' ?>">
								<option value="">Select Gender...</option>
								<option value="Male"   <?= old('gender', $user['gender']) === 'Male'   ? 'selected' : '' ?>>Male</option>
								<option value="Female" <?= old('gender', $user['gender']) === 'Female' ? 'selected' : '' ?>>Female</option>
							</select>
							<?php if (session('validation')?->hasError('gender')): ?><div class="invalid-feedback"><?= session('validation')->getError('gender') ?></div><?php endif; ?>
						</div>
						<div class="col-lg-4 mb-6">
							<label class="form-label">Date of Birth</label>
							<input type="date" name="dob" class="form-control" value="<?= old('dob', $user['dob'] ?? '') ?>" />
						</div>
					</div>
					<!--end::Personal Details-->

					<!--begin::Province & District-->
					<div class="fv-row mb-4">
						<div class="row">
							<div class="col-lg-6">
								<label class="form-label mb-3 required">Select Province</label>
								<select class="form-select selectProvince <?= session('validation')?->hasError('province') ? 'is-invalid' : '' ?>" name="province" id="province-select">
									<option value="">Select ...</option>
									<?php foreach ($province as $row): ?>
										<option value="<?= $row['province_id'] ?>" <?= ((old('province') ? old('province') == $row['province_id'] : $province_id == $row['province_id'])) ? 'selected' : '' ?>><?= esc($row['province_name']) ?></option>
									<?php endforeach; ?>
								</select>
								<?php if (session('validation')?->hasError('province')): ?><div class="invalid-feedback d-block"><?= session('validation')->getError('province') ?></div><?php endif; ?>
							</div>
							<div class="col-lg-6">
								<span id="loader" style="display:none;"><img src="<?= base_url('resources/ajax-loader/ajax-loader-3.gif') ?>" /></span>
								<div class="response">
									<?php $showDistrict = (old('province') !== '') || !empty($province_id); ?>
									<?php if ($showDistrict): ?>
										<label class="form-label mb-3 required">Select District</label>
										<select class="form-select <?= session('validation')?->hasError('district') ? 'is-invalid' : '' ?>" name="district" id="district-select">
											<?php $cp = (old('province') !== '') ? (int)old('province') : (int)$province_id; if ($cp !== 16): ?><option value="">Select ...</option><?php endif; ?>
											<?php foreach ($provinceDistrict as $row): ?>
												<option value="<?= esc($row['district_id']) ?>" <?= ((int)$row['district_id'] === (int)$district_id) ? 'selected' : '' ?>><?= esc($row['district_name']) ?></option>
											<?php endforeach; ?>
										</select>
										<?php if (session('validation')?->hasError('district')): ?><div class="invalid-feedback d-block"><?= session('validation')->getError('district') ?></div><?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<!--end::Province & District-->

					<!--begin::Address-->
					<div class="row mb-6">
						<div class="col-lg-12 mb-6">
							<label class="form-label">Address</label>
							<textarea name="address" class="form-control" rows="3" placeholder="Enter full address"><?= old('address', $user['address'] ?? '') ?></textarea>
						</div>
					</div>
					<!--end::Address-->

					<!--begin::Account Information-->
					<div class="row mb-6">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Account Information</label>
						<div class="col-lg-4 mb-6">
							<label class="form-label required">Role</label>
							<select name="role_id" id="user-role-select" class="form-select form-select-solid <?= session('validation')?->hasError('role_id') ? 'is-invalid' : '' ?>">
								<option value="" data-role-cat-id="">Select Role...</option>
								<?php foreach ($roles as $role): ?>
									<?php
									$roleId    = is_array($role) ? $role['role_id']         : $role->role_id;
									$roleName  = is_array($role) ? $role['role_name']        : $role->role_name;
									$roleCatId = is_array($role) ? ($role['role_cat_id_fk'] ?? 0) : ($role->role_cat_id_fk ?? 0);
									?>
									<option value="<?= $roleId ?>" data-role-cat-id="<?= (int)$roleCatId ?>" <?= old('role_id', $user['role_id_fk']) == $roleId ? 'selected' : '' ?>><?= esc($roleName) ?></option>
								<?php endforeach; ?>
							</select>
							<?php if (session('validation')?->hasError('role_id')): ?><div class="invalid-feedback"><?= session('validation')->getError('role_id') ?></div><?php endif; ?>
						</div>
						<div class="col-lg-4 mb-6">
							<label class="form-label required">Status</label>
							<select name="user_status" class="form-select form-select-solid">
								<option value="Active"   <?= old('user_status', $user['user_status']) === 'Active'   ? 'selected' : '' ?>>Active</option>
								<option value="Inactive" <?= old('user_status', $user['user_status']) === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
							</select>
						</div>
					</div>
					<!--end::Account Information-->

					<!--begin::Parent Checkbox-->
					<?php $showParentCheckbox = !in_array((int)$currentRoleCatId, [4, 6]); ?>
					<div class="row mb-6" id="parent-checkbox-row" style="<?= $showParentCheckbox ? '' : 'display:none;' ?>">
						<div class="col-lg-12">
							<div class="d-flex align-items-start gap-3 rounded border border-dashed border-warning px-6 py-4">
								<div class="form-check form-check-custom flex-shrink-0 mt-1">
									<input class="form-check-input" type="checkbox" name="is_a_parent" value="1" id="is_a_parent" <?= old('is_a_parent', $user['is_a_parent'] ?? 0) ? 'checked' : '' ?> />
								</div>
								<div>
									<label class="form-check-label fw-semibold text-gray-700 cursor-pointer fs-6 d-block mb-1" for="is_a_parent">
										Check if user is a parent
									</label>
									<div class="form-text mt-0">Select this option if the user is also a parent and requires access to the Navuli SMIS parent portal</div>
								</div>
							</div>
						</div>
					</div>
					<!--end::Parent Checkbox-->

					<!--begin::User Admission Detail (read-only display)-->
					<?php $showAdm = in_array((int)$currentRoleCatId, [3, 4]); ?>
					<div id="admission-detail-section" class="row mb-4" style="<?= $showAdm ? '' : 'display:none;' ?>">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-2">User Admission Detail</label>
						<div class="col-lg-12">
							<?php if ($activeAdmission): ?>
								<div class="card bg-light-primary border border-primary border-opacity-25">
									<div class="card-body py-4 px-5">
										<div class="row g-4 align-items-center">
											<div class="col-sm-6 col-lg-4">
												<span class="text-muted fs-8 d-block mb-1">School</span>
												<span class="fw-bold text-gray-800"><?= esc($activeAdmission['sch_name'] ?? '—') ?></span>
											</div>
											<div class="col-sm-6 col-lg-3">
												<span class="text-muted fs-8 d-block mb-1">Admission Status</span>
												<?php
												$bc = match($activeAdmission['admission_status'] ?? '') {
													'Active'    => 'badge-light-success',
													'Inactive'  => 'badge-light-danger',
													'Suspended' => 'badge-light-warning',
													'Graduated' => 'badge-light-info',
													default     => 'badge-light-secondary',
												};
												?>
												<span class="badge <?= $bc ?> fs-7"><?= esc($activeAdmission['admission_status']) ?></span>
											</div>
											<div class="col-sm-6 col-lg-3">
												<span class="text-muted fs-8 d-block mb-1">Admission Date</span>
												<span class="fw-bold text-gray-800"><?= esc($activeAdmission['admission_date'] ?? '—') ?></span>
											</div>
											<div class="col-sm-6 col-lg-2 d-flex justify-content-lg-end">
												<a href="<?= base_url('admission/detail/' . (int)$activeAdmission['admission_id']) ?>" class="btn btn-sm btn-light-primary">
													<i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> View
												</a>
											</div>
										</div>
										<?php if (!empty($activeAdmission['admission_note'])): ?>
										<div class="mt-3 pt-3 border-top border-primary border-opacity-25">
											<span class="text-muted fs-8 d-block mb-1">Note</span>
											<span class="text-gray-700 fs-7"><?= esc($activeAdmission['admission_note']) ?></span>
										</div>
										<?php endif; ?>
									</div>
								</div>
							<?php else: ?>
								<div class="alert alert-info d-flex align-items-center p-4">
									<i class="ki-duotone ki-information-5 fs-2x text-info me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
									<div class="flex-grow-1">
										<span class="fw-semibold">No active admission found for this user.</span>
										<span class="text-muted ms-2 fs-7">This user has no active school admission record.</span>
									</div>
									<a href="<?= base_url('admission/add/' . (int)$user['user_id']) ?>" class="btn btn-sm btn-info ms-3">
										<i class="ki-duotone ki-plus fs-5"></i> Add Admission
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<!--end::User Admission Detail-->

					<!--begin::Active Admission Information (editable)-->
					<div id="admission-section" class="row mb-6" style="<?= $showAdm ? '' : 'display:none;' ?>">
						<label class="col-lg-12 col-form-label fw-bold fs-5 mb-3">Active Admission Information</label>
						<div class="col-lg-6 mb-6">
							<label class="form-label required">School</label>
							<select name="admission_sch_id" id="admission-sch-id" class="form-select form-select-solid">
								<option value="">Select School...</option>
								<?php foreach ($schools as $sch): ?>
									<option value="<?= (int)$sch['sch_id'] ?>" <?= old('admission_sch_id', $activeAdmission['sch_id_fk'] ?? '') == $sch['sch_id'] ? 'selected' : '' ?>><?= esc($sch['sch_name']) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-lg-6 mb-6">
							<label class="form-label required">Admission Status</label>
							<select name="admission_status" id="admission-status" class="form-select form-select-solid">
								<?php
								$admStatuses = ['Active', 'Inactive', 'Suspended', 'Graduated', 'Withdrawn'];
								$currentSt   = old('admission_status', $activeAdmission['admission_status'] ?? 'Active');
								foreach ($admStatuses as $st): ?>
									<option value="<?= $st ?>" <?= $currentSt === $st ? 'selected' : '' ?>><?= $st ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-lg-12 mb-3">
							<label class="form-label">Admission Note</label>
							<textarea name="admission_note" id="admission-note" class="form-control" rows="3" placeholder="Optional note about this admission..."><?= old('admission_note', $activeAdmission['admission_note'] ?? '') ?></textarea>
						</div>
					</div>
					<!--end::Active Admission Information-->

					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<a href="<?= base_url('user') ?>" class="btn btn-light me-3">Cancel</a>
						<button type="submit" id="submit-btn" class="btn btn-primary">
							<i class="ki-duotone ki-check fs-2"></i> Update User
						</button>
					</div>
					<!--end::Actions-->

				</form>
			</div>
		</div>
	</div>
</div>
<!--end::Content-->

<script>
"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect          = document.getElementById('user-role-select');
    const admissionSection    = document.getElementById('admission-section');
    const admissionDetailSect = document.getElementById('admission-detail-section');
    const admSchId            = document.getElementById('admission-sch-id');
    const admStatus           = document.getElementById('admission-status');
    const admNote             = document.getElementById('admission-note');
    const parentCheckboxRow   = document.getElementById('parent-checkbox-row');
    const isAParentInput      = document.getElementById('is_a_parent');

    function toggleAdmissionSection() {
        const opt   = roleSelect ? roleSelect.options[roleSelect.selectedIndex] : null;
        const catId = opt ? parseInt(opt.getAttribute('data-role-cat-id') || '0', 10) : 0;
        const show  = catId === 3 || catId === 4;

        if (admissionDetailSect) admissionDetailSect.style.display = show ? '' : 'none';
        if (admissionSection)    admissionSection.style.display    = show ? '' : 'none';

        if (!show) {
            if (admSchId)  admSchId.value  = '';
            if (admStatus) admStatus.value = 'Active';
            if (admNote)   admNote.value   = '';
        }
    }

    function toggleParentCheckbox() {
        const opt   = roleSelect ? roleSelect.options[roleSelect.selectedIndex] : null;
        const catId = opt ? parseInt(opt.getAttribute('data-role-cat-id') || '0', 10) : 0;
        const show  = catId !== 4 && catId !== 6;

        if (parentCheckboxRow) parentCheckboxRow.style.display = show ? '' : 'none';
        if (!show && isAParentInput) isAParentInput.checked = false;
    }

    if (roleSelect) {
        roleSelect.addEventListener('change', toggleAdmissionSection);
        roleSelect.addEventListener('change', toggleParentCheckbox);
        toggleAdmissionSection();
        toggleParentCheckbox();
    }

    const form = document.getElementById('kt_user_edit_form');
    const btn  = document.getElementById('submit-btn');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled  = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        });
    }
});

// Initialise Metronic image input plugin
KTImageInput.createInstances();

$(document).ready(function () {
    $('.selectProvince').change(function () {
        var id = $(this).val();
        if (!id) { $('.response').html(''); return; }
        $.ajax({
            url: '<?= base_url('district/getDistrictByProvince') ?>',
            type: 'POST',
            data: { id: id, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            dataType: 'json',
            beforeSend: function () { $('#loader').show(); $('.response').hide(); },
            success: function (r) {
                $('#loader').hide(); $('.response').show();
                $('.response').html(r.success ? r.html : '<div class="alert alert-danger">' + r.error + '</div>');
            },
            error: function () { $('#loader').hide(); $('.response').show(); $('.response').html('<div class="alert alert-danger">Error loading districts.</div>'); }
        });
    });
});
</script>
