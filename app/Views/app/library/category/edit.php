<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit Book Category</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('library/category') ?>" class="text-muted text-hover-primary">Book Categories</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($category['category_name']) ?></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Edit</li>
            </ul>
        </div>
        <a href="<?= base_url('library/category') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to List
        </a>
    </div>
</div>
<!--end::Toolbar-->

<?php
$oldInput = session()->getFlashdata('_ci_old_input') ?? [];
$hasOld   = !empty($oldInput);

$valName = $hasOld ? ($oldInput['category_name'] ?? '') : ($category['category_name'] ?? '');
$valDesc = $hasOld ? ($oldInput['description']   ?? '') : ($category['description']   ?? '');
?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <form action="<?= base_url('library/category/update/' . (int)$category['category_id']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="card mb-6">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center bg-light-primary rounded-2 flex-shrink-0" style="width:40px;height:40px;">
                                <i class="ki-duotone ki-book fs-3 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-gray-800 mb-0 fs-5">Category Information</h3>
                                <span class="text-muted fs-7">Basic details about the book category</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-5">
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Category Name</label>
                            <input type="text" name="category_name"
                                class="form-control <?= session('validation')?->hasError('category_name') ? 'is-invalid' : '' ?>"
                                value="<?= esc($valName) ?>" />
                            <?php if (session('validation')?->hasError('category_name')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('category_name') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Description</label>
                            <input type="text" name="description"
                                class="form-control <?= session('validation')?->hasError('description') ? 'is-invalid' : '' ?>"
                                value="<?= esc($valDesc) ?>" />
                            <?php if (session('validation')?->hasError('description')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('description') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mb-10">
                <a href="<?= base_url('library/category') ?>" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>
