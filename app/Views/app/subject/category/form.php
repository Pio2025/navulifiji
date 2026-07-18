<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isEdit ? 'Edit Subject Category' : 'Add Subject Category' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('subject') ?>" class="text-muted text-hover-primary">Subjects</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('subject/category') ?>" class="text-muted text-hover-primary">Categories</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= $isEdit ? 'Edit' : 'Add' ?></li>
            </ul>
        </div>
        <a href="<?= base_url('subject/category') ?>" class="btn btn-light btn-sm">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to List
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$validation = session('validation');
$formErrors = $validation ? array_values($validation->getErrors()) : [];
$errName    = $validation ? $validation->getError('sub_cat_name') : null;
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $isEdit ? 'Edit Category' : 'New Subject Category' ?></h3>
    </div>
    <div class="card-body pt-6">

        <form id="category-form" method="POST" action="<?= $isEdit
            ? base_url('subject/category/update/' . (int)$category['sub_cat_id'])
            : base_url('subject/category/store') ?>">
            <?= csrf_field() ?>

            <!--Category Name-->
            <div class="mb-6">
                <label class="form-label required fw-semibold" for="sub_cat_name">Category Name</label>
                <input type="text" id="sub_cat_name" name="sub_cat_name"
                    class="form-control<?= $errName ? ' is-invalid' : '' ?>"
                    placeholder="e.g. Sciences, Humanities, Languages"
                    value="<?= esc(old('sub_cat_name', $category['sub_cat_name'] ?? '')) ?>"
                    maxlength="260">
                <?php if ($errName): ?>
                <div class="invalid-feedback"><?= esc($errName) ?></div>
                <?php else: ?>
                <div class="form-text text-muted">Max 260 characters.</div>
                <?php endif; ?>
            </div>

            <!--Status-->
            <div class="mb-8">
                <label class="form-label fw-semibold">Status</label>
                <?php $statusVal = (int) old('sub_cat_status', $category['sub_cat_status'] ?? 1); ?>
                <div class="d-flex gap-6 mt-2">
                    <label class="d-flex align-items-center gap-3 cursor-pointer">
                        <span class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="sub_cat_status" value="1"
                                <?= $statusVal === 1 ? 'checked' : '' ?>>
                        </span>
                        <span>
                            <span class="fw-semibold text-gray-800 d-block">Active</span>
                            <span class="text-muted fs-8">Available for use in subjects</span>
                        </span>
                    </label>
                    <label class="d-flex align-items-center gap-3 cursor-pointer">
                        <span class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="sub_cat_status" value="0"
                                <?= $statusVal === 0 ? 'checked' : '' ?>>
                        </span>
                        <span>
                            <span class="fw-semibold text-gray-800 d-block">Inactive</span>
                            <span class="text-muted fs-8">Hidden from subject forms</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="<?= base_url('subject/category') ?>" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-2"><span class="path1"></span><span class="path2"></span></i>
                    <?= $isEdit ? 'Save Changes' : 'Add Category' ?>
                </button>
            </div>
        </form>

    </div>
</div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('sub_cat_name');

    // ── Backend validation errors ─────────────────────────────────────────────
    <?php if (!empty($formErrors)): ?>
    Swal.fire({
        icon: 'error',
        title: 'Please fix the following',
        html: '<ul class="text-start ps-3 mb-0 fs-6">'
            + <?= json_encode(array_map(fn($e) => '<li>' . esc($e) . '</li>', $formErrors)) ?>.join('')
            + '</ul>',
        confirmButtonText: 'OK',
        confirmButtonColor: '#009ef7',
        customClass: { confirmButton: 'btn btn-primary' },
    });
    <?php endif; ?>

    // ── Clear highlight on input ──────────────────────────────────────────────
    nameInput.addEventListener('input', function () {
        this.classList.remove('is-invalid');
        const fb = this.nextElementSibling;
        if (fb && fb.classList.contains('invalid-feedback')) fb.remove();
    });

    // ── Frontend validation ───────────────────────────────────────────────────
    document.getElementById('category-form').addEventListener('submit', function (e) {
        const errors = [];
        nameInput.classList.remove('is-invalid');

        const name = nameInput.value.trim();
        if (!name) {
            errors.push('Category name is required.');
            nameInput.classList.add('is-invalid');
        } else if (name.length < 2) {
            errors.push('Category name must be at least 2 characters.');
            nameInput.classList.add('is-invalid');
        }

        if (errors.length > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Please fix the following',
                html: '<ul class="text-start ps-3 mb-0 fs-6">'
                    + errors.map(function (msg) { return '<li>' + msg + '</li>'; }).join('')
                    + '</ul>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#009ef7',
                customClass: { confirmButton: 'btn btn-primary' },
            });
        }
    });
});
</script>
