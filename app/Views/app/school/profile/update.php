<link rel="stylesheet" href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" />

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">School Profile</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('school/dashboard') ?>" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
				<li class="breadcrumb-item text-muted">Update Profile</li>
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
		<div class="card mb-5 mb-xl-10">
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_school_profile_details" aria-expanded="true">
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">School Details</h3>
				</div>
			</div>

			<div id="kt_school_profile_details" class="collapse show">
				<form id="schoolUpdateForm" class="form" enctype="multipart/form-data" method="post"
				      action="<?= site_url('school/processUpdate/' . (int)$school['sch_id']) ?>">
					<?= csrf_field() ?>

					<div class="card-body border-top p-9">

						<!--begin::School Name-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required fw-semibold fs-6">School Name</label>
							<div class="col-lg-8 fv-row">
								<input type="text" name="sch_name"
								       class="form-control form-control-lg <?= (isset($validation) && $validation->hasError('sch_name')) ? 'is-invalid' : '' ?>"
								       placeholder="Enter school name"
								       value="<?= old('sch_name', esc($school['sch_name'] ?? '')) ?>">
								<?php if (isset($validation) && $validation->hasError('sch_name')): ?>
									<div class="invalid-feedback"><?= $validation->getError('sch_name') ?></div>
								<?php endif; ?>
							</div>
						</div>
						<!--end::School Name-->

						<!--begin::Logo-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6">School Logo</label>
							<div class="col-lg-8">
								<div class="image-input image-input-outline" data-kt-image-input="true"
								     style="background-image: url('<?= base_url('assets/media/svg/avatars/blank.svg') ?>')">
									<div class="image-input-wrapper w-125px h-125px"
									     style="background-image: url('<?= base_url('uploads/school/logo/' . esc($school['sch_logo'] ?? '')) ?>')"></div>
									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
									       data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change logo">
										<i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
										<input type="file" name="sch_logo" accept=".png,.jpg,.jpeg" />
										<input type="hidden" name="sch_logo_remove" />
									</label>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
									      data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel">
										<i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
									</span>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
									      data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove">
										<i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
									</span>
								</div>
								<div class="form-text">Allowed: png, jpg, jpeg.</div>
							</div>
						</div>
						<!--end::Logo-->

						<!--begin::School Category-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6">School Category</label>
							<div class="col-lg-8 fv-row">
								<select name="sch_cat_id_fk" class="form-select form-select-lg">
									<option value="">Select category...</option>
									<?php foreach ($schoolCategory as $cat): ?>
										<option value="<?= (int)$cat['sch_cat_id'] ?>"
										        <?= old('sch_cat_id_fk', $school['sch_cat_id_fk']) == $cat['sch_cat_id'] ? 'selected' : '' ?>>
											<?= esc($cat['sch_cat_name']) ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<!--end::School Category-->

						<!--begin::Province & District-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6">Province</label>
							<div class="col-lg-8 fv-row">
								<select name="province_id" id="province-select" class="form-select form-select-lg">
									<option value="">Select province...</option>
									<?php foreach ($province as $prov): ?>
										<option value="<?= (int)$prov['province_id'] ?>"
										        <?= old('province_id', $school['province_id'] ?? '') == $prov['province_id'] ? 'selected' : '' ?>>
											<?= esc($prov['province_name']) ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6">District</label>
							<div class="col-lg-8 fv-row">
								<span id="district-loader" style="display:none;">
									<img src="<?= base_url('resources/ajax-loader/ajax-loader-3.gif') ?>" alt="Loading..." />
								</span>
								<div class="district-response">
									<?php if (!empty($provinceDistrict)): ?>
										<select name="district_id_fk" id="district-select" class="form-select form-select-lg">
											<option value="">Select district...</option>
											<?php foreach ($provinceDistrict as $dist): ?>
												<option value="<?= (int)$dist['district_id'] ?>"
												        <?= old('district_id_fk', $school['district_id_fk'] ?? '') == $dist['district_id'] ? 'selected' : '' ?>>
													<?= esc($dist['district_name']) ?>
												</option>
											<?php endforeach; ?>
										</select>
									<?php else: ?>
										<select name="district_id_fk" id="district-select" class="form-select form-select-lg">
											<option value="">Select province first...</option>
										</select>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<!--end::Province & District-->

						<!--begin::Email-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>
							<div class="col-lg-8 fv-row">
								<input type="email" name="sch_email"
								       class="form-control form-control-lg <?= (isset($validation) && $validation->hasError('sch_email')) ? 'is-invalid' : '' ?>"
								       placeholder="Enter school email"
								       value="<?= old('sch_email', esc($school['sch_email'])) ?>">
								<?php if (isset($validation) && $validation->hasError('sch_email')): ?>
									<div class="invalid-feedback"><?= $validation->getError('sch_email') ?></div>
								<?php endif; ?>
							</div>
						</div>
						<!--end::Email-->

						<!--begin::Phone-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required fw-semibold fs-6">School Phone</label>
							<div class="col-lg-8 fv-row">
								<input type="tel" name="sch_phone"
								       class="form-control form-control-lg <?= (isset($validation) && $validation->hasError('sch_phone')) ? 'is-invalid' : '' ?>"
								       placeholder="Enter 7-digit phone number"
								       value="<?= old('sch_phone', esc($school['sch_phone'])) ?>"
								       maxlength="7">
								<div class="form-text">Must be exactly 7 digits</div>
								<?php if (isset($validation) && $validation->hasError('sch_phone')): ?>
									<div class="invalid-feedback"><?= $validation->getError('sch_phone') ?></div>
								<?php endif; ?>
							</div>
						</div>
						<!--end::Phone-->

						<!--begin::Address-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label required fw-semibold fs-6">School Address</label>
							<div class="col-lg-8 fv-row">
								<textarea name="sch_address" rows="3"
								          class="form-control form-control-lg <?= (isset($validation) && $validation->hasError('sch_address')) ? 'is-invalid' : '' ?>"
								          placeholder="Enter school address"><?= old('sch_address', esc($school['sch_address'])) ?></textarea>
								<div class="form-text">Minimum 6 characters</div>
								<?php if (isset($validation) && $validation->hasError('sch_address')): ?>
									<div class="invalid-feedback"><?= $validation->getError('sch_address') ?></div>
								<?php endif; ?>
							</div>
						</div>
						<!--end::Address-->

						<!--begin::Motto-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6">School Motto</label>
							<div class="col-lg-8 fv-row">
								<input type="text" name="sch_motto"
								       class="form-control form-control-lg <?= (isset($validation) && $validation->hasError('sch_motto')) ? 'is-invalid' : '' ?>"
								       placeholder="Enter school motto"
								       value="<?= old('sch_motto', esc($school['sch_motto'] ?? '')) ?>">
								<?php if (isset($validation) && $validation->hasError('sch_motto')): ?>
									<div class="invalid-feedback"><?= $validation->getError('sch_motto') ?></div>
								<?php endif; ?>
							</div>
						</div>
						<!--end::Motto-->

						<!--begin::School Colors-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6">School Colors</label>
							<div class="col-lg-8">
								<div class="row">
									<!--begin::Primary Color-->
									<div class="col-6">
										<label class="form-label">Primary Color</label>
										<div class="d-flex align-items-center gap-3">
											<input type="color" id="primary-color-picker" name="sch_primary_color"
											       class="form-control form-control-color w-50px h-40px p-1"
											       value="<?= esc($school['sch_primary_color'] ?? '#007bff') ?>"
											       title="Pick primary color">
											<input type="text" id="primary-color-text"
											       class="form-control form-control-lg"
											       value="<?= esc($school['sch_primary_color'] ?? '#007bff') ?>"
											       maxlength="7" placeholder="#000000">
										</div>
										<div class="form-text">Primary brand color (hex)</div>
									</div>
									<!--end::Primary Color-->

									<!--begin::Secondary Color-->
									<div class="col-6">
										<label class="form-label">Secondary Color</label>
										<div class="d-flex align-items-center gap-3">
											<input type="color" id="secondary-color-picker" name="sch_secondary_color"
											       class="form-control form-control-color w-50px h-40px p-1"
											       value="<?= esc($school['sch_secondary_color'] ?? '#6c757d') ?>"
											       title="Pick secondary color">
											<input type="text" id="secondary-color-text"
											       class="form-control form-control-lg"
											       value="<?= esc($school['sch_secondary_color'] ?? '#6c757d') ?>"
											       maxlength="7" placeholder="#000000">
										</div>
										<div class="form-text">Secondary brand color (hex)</div>
									</div>
									<!--end::Secondary Color-->
								</div>
							</div>
						</div>
						<!--end::School Colors-->

						<!--begin::School Location-->
						<div class="row mb-6">
							<label class="col-lg-4 col-form-label fw-semibold fs-6">School Location</label>
							<div class="col-lg-8">
								<div class="d-flex gap-3 mb-3">
									<div class="flex-fill">
										<label class="form-label text-muted fs-8 mb-1">Latitude</label>
										<input type="text" id="coord-lat-display" class="form-control form-control-sm" readonly
										       placeholder="Click map to set" value="<?= esc($school['sch_y_coord'] ?? '') ?>">
									</div>
									<div class="flex-fill">
										<label class="form-label text-muted fs-8 mb-1">Longitude</label>
										<input type="text" id="coord-lng-display" class="form-control form-control-sm" readonly
										       placeholder="Click map to set" value="<?= esc($school['sch_x_coord'] ?? '') ?>">
									</div>
									<div class="d-flex align-items-end">
										<button type="button" id="use-my-location" class="btn btn-light-primary btn-sm text-nowrap">
											<i class="ki-duotone ki-geolocation fs-4"><span class="path1"></span><span class="path2"></span></i>
											My Location
										</button>
									</div>
								</div>

								<!--begin::Map-->
								<div id="school-update-map" style="height:380px; border-radius:8px; border:1px solid #e4e6ef;"></div>
								<!--end::Map-->

								<div class="form-text mt-2">Click anywhere on the map to pin the school location.</div>

								<input type="hidden" name="sch_y_coord" id="sch_y_coord" value="<?= esc($school['sch_y_coord'] ?? '') ?>">
								<input type="hidden" name="sch_x_coord" id="sch_x_coord" value="<?= esc($school['sch_x_coord'] ?? '') ?>">
							</div>
						</div>
						<!--end::School Location-->

					</div>
					<!--end::Card body-->

					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
						<button type="submit" id="update-submit-btn" class="btn btn-primary">Save Changes</button>
					</div>
					<!--end::Actions-->

				</form>
			</div>
		</div>
		<!--end::Card-->

	</div>
</div>
<!--end::Content-->

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Province-District AJAX
    const provinceSelect = document.getElementById('province-select');
    const districtResponse = document.querySelector('.district-response');
    const districtLoader = document.getElementById('district-loader');

    if (provinceSelect && districtResponse) {
        provinceSelect.addEventListener('change', function () {
            const provinceId = this.value;
            if (!provinceId) {
                districtResponse.innerHTML = '<select name="district_id_fk" id="district-select" class="form-select form-select-lg"><option value="">Select province first...</option></select>';
                return;
            }

            if (districtLoader) districtLoader.style.display = 'inline-block';
            districtResponse.style.display = 'none';

            fetch('<?= base_url('district/getDistrictByProvince') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'id': provinceId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(r => r.json())
            .then(data => {
                if (districtLoader) districtLoader.style.display = 'none';
                districtResponse.style.display = 'block';
                if (data.success) {
                    districtResponse.innerHTML = data.html;
                    // Rename field to match what processUpdate() expects
                    const sel = districtResponse.querySelector('select');
                    if (sel) sel.name = 'district_id_fk';
                } else {
                    districtResponse.innerHTML = '<div class="alert alert-danger">' + data.error + '</div>';
                }
            })
            .catch(() => {
                if (districtLoader) districtLoader.style.display = 'none';
                districtResponse.style.display = 'block';
                districtResponse.innerHTML = '<div class="alert alert-danger">Error loading districts. Please try again.</div>';
            });
        });
    }

    // Color picker sync
    function syncColor(pickerId, textId) {
        const picker = document.getElementById(pickerId);
        const text   = document.getElementById(textId);
        if (!picker || !text) return;

        picker.addEventListener('input', function () {
            text.value = this.value;
        });

        text.addEventListener('input', function () {
            const val = this.value.trim();
            if (/^#[0-9a-fA-F]{6}$/.test(val)) {
                picker.value = val;
            }
        });
    }

    syncColor('primary-color-picker', 'primary-color-text');
    syncColor('secondary-color-picker', 'secondary-color-text');

    // Submit button loading state
    const form = document.getElementById('schoolUpdateForm');
    const btn  = document.getElementById('update-submit-btn');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        });
    }
});
</script>

<script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>
<script>
(function () {
    // sch_x_coord = longitude, sch_y_coord = latitude
    const savedLat = parseFloat('<?= esc($school['sch_y_coord'] ?? '') ?>') || null;
    const savedLng = parseFloat('<?= esc($school['sch_x_coord'] ?? '') ?>') || null;

    // Fiji full extent: Yasawa (NW) → Rotuma (far N) → Lau group (E) → S islands
    // [SW lng, SW lat], [NE lng, NE lat]
    const FIJI_BOUNDS = [[176.5, -21.5], [180.5, -12.0]];

    const map = new maplibregl.Map({
        container:  'school-update-map',
        style:      'https://tiles.openfreemap.org/styles/liberty',
        center:     savedLng !== null ? [savedLng, savedLat] : [178.65, -17.5],
        zoom:       savedLat !== null ? 15 : 7,
        minZoom:    6,
        maxBounds:  FIJI_BOUNDS
    });

    map.addControl(new maplibregl.NavigationControl(), 'top-right');
    map.addControl(new maplibregl.ScaleControl({ unit: 'metric' }), 'bottom-left');

    // Draggable marker
    let marker = null;

    function placeMarker(lng, lat) {
        if (marker) {
            marker.setLngLat([lng, lat]);
        } else {
            marker = new maplibregl.Marker({ draggable: true, color: '#e74c3c' })
                .setLngLat([lng, lat])
                .addTo(map);

            marker.on('dragend', function () {
                const pos = marker.getLngLat();
                updateCoords(pos.lng, pos.lat);
            });
        }
        updateCoords(lng, lat);
    }

    function updateCoords(lng, lat) {
        const latR = parseFloat(lat.toFixed(6));
        const lngR = parseFloat(lng.toFixed(6));
        document.getElementById('sch_y_coord').value       = latR;
        document.getElementById('sch_x_coord').value       = lngR;
        document.getElementById('coord-lat-display').value = latR;
        document.getElementById('coord-lng-display').value = lngR;
    }

    // Once style loads: place saved marker OR fit to show all of Fiji
    map.on('load', function () {
        if (savedLat !== null && savedLng !== null) {
            placeMarker(savedLng, savedLat);
        } else {
            map.fitBounds(FIJI_BOUNDS, { padding: 30, duration: 0 });
        }
    });

    // Click anywhere to place / move marker
    map.on('click', function (e) {
        placeMarker(e.lngLat.lng, e.lngLat.lat);
    });

    // Change cursor to crosshair over the map
    map.getCanvas().style.cursor = 'crosshair';

    // "My Location" button
    document.getElementById('use-my-location').addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Locating...';
        const btn = this;

        navigator.geolocation.getCurrentPosition(
            function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                map.flyTo({ center: [lng, lat], zoom: 16 });
                placeMarker(lng, lat);
                btn.disabled = false;
                btn.innerHTML = '<i class="ki-duotone ki-geolocation fs-4"><span class="path1"></span><span class="path2"></span></i> My Location';
            },
            function () {
                alert('Unable to retrieve your location.');
                btn.disabled = false;
                btn.innerHTML = '<i class="ki-duotone ki-geolocation fs-4"><span class="path1"></span><span class="path2"></span></i> My Location';
            }
        );
    });
})();
</script>
