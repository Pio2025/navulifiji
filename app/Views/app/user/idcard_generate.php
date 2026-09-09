<style>
.idcard-preview-wrap { display:flex; justify-content:center; padding: 10px 0; }
.idcard-preview {
    width: 320px; aspect-ratio: 86 / 54; border-radius: 14px; overflow: hidden;
    position: relative; box-shadow: 0 8px 24px rgba(0,0,0,.18);
    background: <?= esc($school['sch_primary_color'] ?? '#12263a') ?>;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    color:#fff;
}
.idcard-preview .ic-header { padding: 8px 10px 6px; display:flex; align-items:center; gap:8px; }
.idcard-preview .ic-header img { width:26px; height:26px; border-radius:4px; object-fit:cover; background:#fff; }
.idcard-preview .ic-header .ic-sch-name { font-size:11px; font-weight:700; line-height:1.2; }
.idcard-preview .ic-header .ic-sub { font-size:8px; opacity:.85; letter-spacing:.5px; }
.idcard-preview .ic-accent { height:3px; background: <?= esc($school['sch_secondary_color'] ?? '#EE2A7B') ?>; }
.idcard-preview .ic-body { background:#fff; color:#1a1a1a; padding:10px; display:flex; gap:10px; height: calc(100% - 44px); }
.idcard-preview .ic-photo { width:64px; height:78px; border-radius:6px; object-fit:cover; background:#eef1f5; border:1px solid #dfe3ea; flex-shrink:0; }
.idcard-preview .ic-name { font-size:13px; font-weight:700; line-height:1.2; }
.idcard-preview .ic-role { font-size:9px; font-weight:700; color: <?= esc($school['sch_secondary_color'] ?? '#EE2A7B') ?>; margin:2px 0 6px; }
.idcard-preview .ic-field { font-size:9px; color:#5a5a5a; margin-bottom:3px; }
.idcard-preview .ic-field b { color:#1a1a1a; font-weight:600; }

.photo-choice-card { border:1px solid #e4e6ef; border-radius:8px; padding:14px; cursor:pointer; transition:.15s; }
.photo-choice-card.active { border-color: var(--bs-primary); background: #f1faff; }
.photo-choice-card input[type="radio"] { margin-right:8px; }
#idcard_video, #idcard_canvas { width:100%; max-width:280px; border-radius:8px; background:#000; }
#idcard_captured_preview { width:120px; height:150px; object-fit:cover; border-radius:8px; border:1px solid #e4e6ef; }
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Generate User ID Card</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted"><a href="<?= base_url('user') ?>" class="text-muted text-hover-primary">Users</a></li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted"><a href="<?= base_url('user/detail/' . $userID) ?>" class="text-muted text-hover-primary"><?= esc($user['fname'] . ' ' . $user['lname']) ?></a></li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted">Generate ID Card</li>
			</ul>
		</div>
	</div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
	<div id="kt_app_content_container" class="app-container container-xxl">

		<?= $this->include('templates/flash_messages') ?>

		<?php if (empty($user['dob']) || empty($user['address'])): ?>
		<div class="alert alert-warning d-flex align-items-center mb-5">
			<i class="ki-duotone ki-information-5 fs-2 me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
			<div>
				Date of birth and/or address are missing from this profile. The card will show "—" for those fields.
				<a href="<?= base_url('user/detail/' . $userID) ?>">Edit the profile</a> first if you'd like them included.
			</div>
		</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-lg-5 mb-5">
				<div class="card">
					<div class="card-header"><h3 class="card-title">Card Preview</h3></div>
					<div class="card-body">
						<div class="idcard-preview-wrap">
							<div class="idcard-preview">
								<div class="ic-header">
									<?php if (!empty($school['sch_logo'])): ?>
										<img src="<?= base_url('uploads/school/logo/' . $school['sch_logo']) ?>" alt="">
									<?php endif; ?>
									<div>
										<div class="ic-sch-name"><?= esc(strtoupper($school['sch_name'] ?? 'Navuli')) ?></div>
										<div class="ic-sub">IDENTITY CARD</div>
									</div>
								</div>
								<div class="ic-accent"></div>
								<div class="ic-body">
									<img id="idcard_preview_photo" class="ic-photo"
										 src="<?= !empty($user['profile_photo']) ? base_url('uploads/profilePhoto/' . $user['profile_photo']) : base_url('uploads/profilePhoto/default_male.jpg') ?>" alt="">
									<div>
										<div class="ic-name"><?= esc($user['fname'] . ' ' . $user['lname']) ?></div>
										<div class="ic-role"><?= esc(strtoupper($role['role_cat_name'] ?? 'MEMBER')) ?></div>
										<div class="ic-field">DOB: <b><?= !empty($user['dob']) ? esc(date('d M Y', strtotime($user['dob']))) : '—' ?></b></div>
										<div class="ic-field">Address: <b><?= !empty($user['address']) ? esc($user['address']) : '—' ?></b></div>
									</div>
								</div>
							</div>
						</div>
						<p class="text-muted fs-8 text-center mb-0">The back of the card carries a QR code linked to Navuli's verification page, plus Navuli's contact details.</p>
					</div>
				</div>
			</div>

			<div class="col-lg-7 mb-5">
				<div class="card">
					<div class="card-header"><h3 class="card-title">Photo</h3></div>
					<div class="card-body">
						<form id="kt_idcard_form">
							<?php if (!empty($user['profile_photo'])): ?>
							<label class="photo-choice-card d-flex align-items-center mb-3 active" id="idcard_choice_existing">
								<input type="radio" name="photo_choice" value="existing" checked>
								<img src="<?= base_url('uploads/profilePhoto/' . $user['profile_photo']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:6px;margin-right:10px;">
								<span>Use current profile photo</span>
							</label>
							<?php endif; ?>

							<label class="photo-choice-card d-flex align-items-center mb-4<?= empty($user['profile_photo']) ? ' active' : '' ?>" id="idcard_choice_new">
								<input type="radio" name="photo_choice" value="new" <?= empty($user['profile_photo']) ? 'checked' : '' ?>>
								<span>Take a new picture<?= empty($user['profile_photo']) ? ' <em class="text-muted">(no photo on file yet)</em>' : '' ?></span>
							</label>

							<div id="idcard_capture_area" class="<?= empty($user['profile_photo']) ? '' : 'd-none' ?> mb-4">
								<div class="d-flex flex-wrap gap-4 align-items-start">
									<div>
										<video id="idcard_video" autoplay playsinline></video>
										<canvas id="idcard_canvas" class="d-none"></canvas>
										<div class="mt-2">
											<button type="button" id="idcard_btn_start_cam" class="btn btn-sm btn-light-primary">
												<i class="ki-duotone ki-video fs-4"><span class="path1"></span><span class="path2"></span></i>
												Start Camera
											</button>
											<button type="button" id="idcard_btn_capture" class="btn btn-sm btn-primary d-none">Capture</button>
											<button type="button" id="idcard_btn_retake" class="btn btn-sm btn-light-danger d-none">Retake</button>
										</div>
									</div>
									<div>
										<div class="fs-7 text-muted mb-2">or upload a photo from this device</div>
										<input type="file" id="idcard_file_input" accept="image/png,image/jpeg" class="form-control form-control-sm" style="max-width:220px;">
										<div class="mt-3">
											<img id="idcard_captured_preview" class="d-none" alt="Captured photo">
										</div>
									</div>
								</div>
							</div>

							<input type="hidden" id="idcard_photo_data" name="photo_data" value="">

							<div class="separator my-4"></div>

							<button type="submit" id="idcard_btn_generate" class="btn btn-primary">
								<i class="ki-duotone ki-printer fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
								Generate ID Card
							</button>
							<a href="<?= base_url('user/detail/' . $userID) ?>" class="btn btn-light ms-2">Cancel</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url('app/assets/js/custom/apps/user-management/users/view/idcard-generate.js') ?>?v=<?= filemtime(FCPATH . 'app/assets/js/custom/apps/user-management/users/view/idcard-generate.js') ?>"></script>
<script>
    KTIdCardGenerate.init({
        userId: <?= (int) $userID ?>,
        saveUrl: "<?= site_url('user/idcard/' . $userID . '/save') ?>",
        csrfName: "<?= csrf_token() ?>",
        csrfHash: "<?= csrf_hash() ?>"
    });
</script>
