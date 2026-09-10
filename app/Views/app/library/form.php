<?php
$book     = $book ?? null;
$isEdit   = $book !== null;
$oldInput = session()->getFlashdata('_ci_old_input') ?? [];
$hasOld   = !empty($oldInput);

$val = function (string $field, $default = '') use ($book, $oldInput, $hasOld) {
    if ($hasOld) {
        return $oldInput[$field] ?? '';
    }
    return $book[$field] ?? $default;
};

$formAction = $isEdit
    ? base_url('library/update/' . (int) $book['book_id'])
    : base_url('library/store');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isEdit ? 'Edit Book' : 'Add Book' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('library') ?>" class="text-muted text-hover-primary">Book Catalog</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= $isEdit ? 'Edit' : 'Add' ?></li>
            </ul>
        </div>
        <a href="<?= base_url('library') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to Catalog
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
                                <i class="ki-duotone ki-book fs-3 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-gray-800 mb-0 fs-5">Book Information</h3>
                                <span class="text-muted fs-7">Catalog details for this title</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-5">
                        <div class="col-lg-8">
                            <label class="form-label required fw-semibold">Title</label>
                            <input type="text" name="title"
                                class="form-control <?= session('validation')?->hasError('title') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('title')) ?>" />
                            <?php if (session('validation')?->hasError('title')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('title') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select <?= session('validation')?->hasError('category_id') ? 'is-invalid' : '' ?>">
                                <option value="">-- None --</option>
                                <?php foreach (($categories ?? []) as $cat): ?>
                                <option value="<?= (int) $cat['category_id'] ?>" <?= (string) $val('category_id_fk') === (string) $cat['category_id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['category_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Author</label>
                            <input type="text" name="author"
                                class="form-control <?= session('validation')?->hasError('author') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('author')) ?>" />
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">ISBN</label>
                            <input type="text" name="isbn"
                                class="form-control <?= session('validation')?->hasError('isbn') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('isbn')) ?>" />
                            <?php if (session('validation')?->hasError('isbn')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('isbn') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Edition</label>
                            <input type="text" name="edition"
                                class="form-control <?= session('validation')?->hasError('edition') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('edition')) ?>" />
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Publisher</label>
                            <input type="text" name="publisher"
                                class="form-control <?= session('validation')?->hasError('publisher') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('publisher')) ?>" />
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Shelf Location</label>
                            <input type="text" name="shelf_location"
                                class="form-control <?= session('validation')?->hasError('shelf_location') ? 'is-invalid' : '' ?>"
                                placeholder="e.g. A-12"
                                value="<?= esc($val('shelf_location')) ?>" />
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label required fw-semibold">Total Copies</label>
                            <input type="number" name="total_copies" min="1"
                                class="form-control <?= session('validation')?->hasError('total_copies') ? 'is-invalid' : '' ?>"
                                value="<?= esc($val('total_copies', 1)) ?>" />
                            <?php if ($isEdit): ?>
                            <div class="form-text text-muted">Currently available: <?= (int) $book['available_copies'] ?></div>
                            <?php endif; ?>
                            <?php if (session('validation')?->hasError('total_copies')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('total_copies') ?></div>
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
                <a href="<?= base_url('library') ?>" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <?= $isEdit ? 'Save Changes' : 'Submit' ?>
                </button>
            </div>

        </form>
    </div>
</div>
