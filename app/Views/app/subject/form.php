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

<?= $this->include('templates/flash_messages') ?>

<div class="card mw-600px mx-auto">
    <div class="card-header">
        <h3 class="card-title"><?= $isEdit ? 'Edit Subject' : 'New Subject' ?></h3>
    </div>
    <div class="card-body">

        <?php
        $validation = session('validation');
        if ($validation): ?>
        <div class="alert alert-danger d-flex align-items-center p-5 mb-6">
            <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-dark">Please fix the errors below</h4>
                <ul class="mb-0 ps-4">
                    <?php foreach ($validation->getErrors() as $err): ?>
                    <li class="fs-7"><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= $isEdit
            ? base_url('subject/update/' . (int)$subject['subject_id'])
            : base_url('subject/store') ?>">
            <?= csrf_field() ?>

            <!--Subject Name-->
            <div class="mb-6">
                <label class="form-label required fw-semibold">Subject Name</label>
                <input type="text" name="subject_name" class="form-control form-control-solid"
                    placeholder="e.g. Year 9 Physical Education"
                    value="<?= esc(old('subject_name', $subject['subject_name'] ?? '')) ?>"
                    maxlength="60" required>
                <div class="form-text text-muted">Max 60 characters.</div>
            </div>

            <!--Level-->
            <div class="mb-6">
                <label class="form-label required fw-semibold">Year Level</label>
                <select name="level_id_fk" class="form-select form-select-solid" required>
                    <option value="">— Select Level —</option>
                    <?php foreach ($levels as $lvl): ?>
                    <?php $sel = old('level_id_fk', $subject['level_id_fk'] ?? '') == $lvl['level_id'] ? 'selected' : ''; ?>
                    <option value="<?= (int)$lvl['level_id'] ?>" <?= $sel ?>><?= esc($lvl['level_name']) ?></option>
                    <?php endforeach; ?>
                </select>
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
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-2"><span class="path1"></span><span class="path2"></span></i>
                    <?= $isEdit ? 'Save Changes' : 'Add Subject' ?>
                </button>
            </div>
        </form>

    </div>
</div>

</div>
</div>
