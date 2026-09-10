<?php
$hostel   = $hostel ?? [];
$room     = $room   ?? null;
$isEdit   = $room !== null;
$oldInput = session()->getFlashdata('_ci_old_input') ?? [];
$hasOld   = !empty($oldInput);

$val = function (string $field, $default = '') use ($room, $oldInput, $hasOld) {
    if ($hasOld) {
        return $oldInput[$field] ?? '';
    }
    return $room[$field] ?? $default;
};

$formAction = $isEdit
    ? base_url('hostel/room/update/' . (int) $room['room_id'])
    : base_url('hostel/room/store/' . (int) $hostel['hostel_id']);
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isEdit ? 'Edit Room' : 'Add Room' ?>
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
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('hostel/detail/' . (int) $hostel['hostel_id']) ?>" class="text-muted text-hover-primary"><?= esc($hostel['hostel_name']) ?></a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= $isEdit ? 'Edit Room' : 'Add Room' ?></li>
            </ul>
        </div>
        <a href="<?= base_url('hostel/detail/' . (int) $hostel['hostel_id']) ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to Hostel
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
                        <h3 class="fw-bold text-gray-800 mb-0 fs-5">Room Details — <?= esc($hostel['hostel_name']) ?></h3>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-5">
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Room Number</label>
                            <input type="text" name="room_number"
                                class="form-control <?= session('validation')?->hasError('room_number') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('room_number')) ?>" />
                            <?php if (session('validation')?->hasError('room_number')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('room_number') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Capacity</label>
                            <input type="number" name="capacity" min="1"
                                class="form-control <?= session('validation')?->hasError('capacity') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('capacity', 1)) ?>" />
                            <?php if ($isEdit): ?>
                            <div class="form-text text-muted">Currently occupied: <?= (int) ($room['occupied_count'] ?? 0) ?></div>
                            <?php endif; ?>
                            <?php if (session('validation')?->hasError('capacity')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('capacity') ?></div>
                            <?php endif; ?>
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
                <a href="<?= base_url('hostel/detail/' . (int) $hostel['hostel_id']) ?>" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <?= $isEdit ? 'Save Changes' : 'Submit' ?>
                </button>
            </div>

        </form>
    </div>
</div>
