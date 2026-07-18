<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isEdit ? 'Edit Subject' : 'Add Subject' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('subject') ?>" class="text-muted text-hover-primary">Subjects</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= $isEdit ? 'Edit' : 'Add' ?></li>
            </ul>
        </div>
        <a href="<?= base_url('subject') ?>" class="btn btn-light btn-sm">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to List
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$validation    = session('validation');
$formErrors    = $validation ? array_values($validation->getErrors()) : [];
$errName       = $validation ? $validation->getError('subject_name') : null;
$errLevel      = $validation ? $validation->getError('level_id_fk')  : null;
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $isEdit ? 'Edit Subject' : 'New Subject' ?></h3>
    </div>
    <div class="card-body pt-6">

        <form id="subject-form" method="POST" action="<?= $isEdit
            ? base_url('subject/update/' . (int)$subject['subject_id'])
            : base_url('subject/store') ?>">
            <?= csrf_field() ?>

            <!--Subject Name-->
            <div class="mb-6">
                <label class="form-label required fw-semibold" for="subject_name">Subject Name</label>
                <input type="text" id="subject_name" name="subject_name"
                    class="form-control<?= $errName ? ' is-invalid' : '' ?>"
                    placeholder="e.g. Year 9 Physical Education"
                    value="<?= esc(old('subject_name', $subject['subject_name'] ?? '')) ?>"
                    maxlength="60">
                <?php if ($errName): ?>
                <div class="invalid-feedback"><?= esc($errName) ?></div>
                <?php else: ?>
                <div class="form-text text-muted">Max 60 characters.</div>
                <?php endif; ?>
            </div>

            <!--Level-->
            <div class="mb-6">
                <label class="form-label required fw-semibold" for="level_id_fk">Year Level</label>
                <select id="level_id_fk" name="level_id_fk"
                    class="form-select<?= $errLevel ? ' is-invalid' : '' ?>">
                    <option value="">— Select Level —</option>
                    <?php foreach ($levels as $lvl): ?>
                    <?php $sel = old('level_id_fk', $subject['level_id_fk'] ?? '') == $lvl['level_id'] ? 'selected' : ''; ?>
                    <option value="<?= (int)$lvl['level_id'] ?>" <?= $sel ?>><?= esc($lvl['level_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($errLevel): ?>
                <div class="invalid-feedback"><?= esc($errLevel) ?></div>
                <?php endif; ?>
            </div>

            <!--Is Examinable-->
            <div class="mb-8">
                <label class="form-label fw-semibold">Subject Type</label>
                <?php $examVal = (int) old('is_examinable', $subject['is_examinable'] ?? 0); ?>
                <div class="d-flex gap-6 mt-2">
                    <label class="d-flex align-items-center gap-3 cursor-pointer">
                        <span class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="is_examinable" value="1"
                                <?= $examVal === 1 ? 'checked' : '' ?>>
                        </span>
                        <span>
                            <span class="fw-semibold text-gray-800 d-block">Examinable</span>
                            <span class="text-muted fs-8">Appears in exam results and reports</span>
                        </span>
                    </label>
                    <label class="d-flex align-items-center gap-3 cursor-pointer">
                        <span class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="is_examinable" value="0"
                                <?= $examVal === 0 ? 'checked' : '' ?>>
                        </span>
                        <span>
                            <span class="fw-semibold text-gray-800 d-block">Non-Examinable</span>
                            <span class="text-muted fs-8">PE, Family Life, Career Ed, Religion, Arts, Drama, etc.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="<?= base_url('subject') ?>" class="btn btn-light">Cancel</a>
                <button type="submit" id="btn-submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-2"><span class="path1"></span><span class="path2"></span></i>
                    <?= $isEdit ? 'Save Changes' : 'Add Subject' ?>
                </button>
            </div>
        </form>

    </div>
</div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput  = document.getElementById('subject_name');
    const levelInput = document.getElementById('level_id_fk');

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

    // ── Clear highlights when user corrects a field ───────────────────────────
    nameInput.addEventListener('input', function () {
        this.classList.remove('is-invalid');
        const fb = this.nextElementSibling;
        if (fb && fb.classList.contains('invalid-feedback')) fb.remove();
    });
    levelInput.addEventListener('change', function () {
        this.classList.remove('is-invalid');
        const fb = this.nextElementSibling;
        if (fb && fb.classList.contains('invalid-feedback')) fb.remove();
    });

    // ── Frontend validation on submit ─────────────────────────────────────────
    document.getElementById('subject-form').addEventListener('submit', function (e) {
        const errors = [];

        // Reset previous highlights
        nameInput.classList.remove('is-invalid');
        levelInput.classList.remove('is-invalid');

        const name = nameInput.value.trim();
        if (!name) {
            errors.push('Subject name is required.');
            nameInput.classList.add('is-invalid');
        } else if (name.length < 2) {
            errors.push('Subject name must be at least 2 characters.');
            nameInput.classList.add('is-invalid');
        } else if (name.length > 60) {
            errors.push('Subject name cannot exceed 60 characters.');
            nameInput.classList.add('is-invalid');
        }

        const level = levelInput.value;
        if (!level) {
            errors.push('Please select a year level.');
            levelInput.classList.add('is-invalid');
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
