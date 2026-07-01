<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Add Exam
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('exam') ?>" class="text-muted text-hover-primary">Exams</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Add</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php $validation = session('validation'); ?>

    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold fs-4 text-gray-900">Exam Details</h3>
            </div>
        </div>
        <div class="card-body py-4">

            <form method="post" action="<?= base_url('exam/store') ?>">
                <?= csrf_field() ?>

                <div class="row g-4">

                    <!--begin::Exam Name-->
                    <div class="col-12">
                        <label class="required fw-semibold fs-6 mb-2">Exam Name</label>
                        <input type="text" name="exam_name"
                               class="form-control form-control-solid <?= $validation && $validation->hasError('exam_name') ? 'is-invalid' : '' ?>"
                               placeholder="e.g. Year 9 Mid-Year Examination 2026"
                               value="<?= esc(old('exam_name')) ?>">
                        <?php if ($validation && $validation->hasError('exam_name')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('exam_name') ?></div>
                        <?php endif; ?>
                    </div>
                    <!--end::Exam Name-->

                    <!--begin::Level-->
                    <div class="col-md-6">
                        <label class="required fw-semibold fs-6 mb-2">Level</label>
                        <select name="level_id_fk"
                                class="form-select form-select-solid <?= $validation && $validation->hasError('level_id_fk') ? 'is-invalid' : '' ?>">
                            <option value="">— Select Level —</option>
                            <?php foreach ($levels as $level): ?>
                            <option value="<?= $level['level_id'] ?>"
                                <?= old('level_id_fk') == $level['level_id'] ? 'selected' : '' ?>>
                                <?= esc($level['level_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation && $validation->hasError('level_id_fk')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('level_id_fk') ?></div>
                        <?php endif; ?>
                    </div>
                    <!--end::Level-->

                    <!--begin::Status-->
                    <div class="col-md-6">
                        <label class="required fw-semibold fs-6 mb-2">Status</label>
                        <select name="exam_status"
                                class="form-select form-select-solid <?= $validation && $validation->hasError('exam_status') ? 'is-invalid' : '' ?>">
                            <option value="Active"    <?= old('exam_status', 'Active') === 'Active'   ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive"  <?= old('exam_status') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <?php if ($validation && $validation->hasError('exam_status')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('exam_status') ?></div>
                        <?php endif; ?>
                    </div>
                    <!--end::Status-->

                </div>

                <div class="d-flex justify-content-end mt-8 gap-3">
                    <a href="<?= base_url('exam') ?>" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-check fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Save Exam
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
</div>
