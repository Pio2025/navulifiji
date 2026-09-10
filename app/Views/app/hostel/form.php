<?php
$hostel   = $hostel ?? null;
$isEdit   = $hostel !== null;
$oldInput = session()->getFlashdata('_ci_old_input') ?? [];
$hasOld   = !empty($oldInput);

$val = function (string $field, $default = '') use ($hostel, $oldInput, $hasOld) {
    if ($hasOld) {
        return $oldInput[$field] ?? '';
    }
    return $hostel[$field] ?? $default;
};

$formAction = $isEdit
    ? base_url('hostel/update/' . (int) $hostel['hostel_id'])
    : base_url('hostel/store');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isEdit ? 'Edit Hostel' : 'Add Hostel' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('hostel') ?>" class="text-muted text-hover-primary">Hostels</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= $isEdit ? 'Edit' : 'Add' ?></li>
            </ul>
        </div>
        <a href="<?= base_url('hostel') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to Hostels
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <form action="<?= $formAction ?>" method="POST">
            <?= csrf_field() ?>

            <div class="card mb-6">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center bg-light-primary rounded-2 flex-shrink-0" style="width:40px;height:40px;">
                                <i class="ki-duotone ki-home-3 fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-gray-800 mb-0 fs-5">Hostel Information</h3>
                                <span class="text-muted fs-7">Building details for this hostel</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-5">
                        <div class="col-lg-8">
                            <label class="form-label required fw-semibold">Hostel Name</label>
                            <input type="text" name="hostel_name"
                                class="form-control <?= session('validation')?->hasError('hostel_name') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('hostel_name')) ?>" />
                            <?php if (session('validation')?->hasError('hostel_name')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('hostel_name') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label required fw-semibold">Type</label>
                            <select name="hostel_type" class="form-select <?= session('validation')?->hasError('hostel_type') ? 'is-invalid' : '' ?>">
                                <option value="">-- Select --</option>
                                <?php foreach (['Boys', 'Girls', 'Mixed'] as $type): ?>
                                <option value="<?= $type ?>" <?= $val('hostel_type') === $type ? 'selected' : '' ?>><?= $type ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('validation')?->hasError('hostel_type')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('hostel_type') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label fw-semibold">Address</label>
                            <input type="text" name="address"
                                class="form-control <?= session('validation')?->hasError('address') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('address')) ?>" />
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="3"
                                class="form-control <?= session('validation')?->hasError('description') ? 'is-invalid' : '' ?>"><?= esc($val('description')) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mb-10">
                <a href="<?= base_url('hostel') ?>" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <?= $isEdit ? 'Save Changes' : 'Submit' ?>
                </button>
            </div>

        </form>
    </div>
</div>
