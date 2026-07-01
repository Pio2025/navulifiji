<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Manage Subjects — <?= esc($studentExam['fname'] . ' ' . $studentExam['lname']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('exam/student/' . $studentExam['student_exam_id'] . '/marks') ?>" class="text-muted text-hover-primary">
                        Exam Marks
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Assign Subjects</li>
            </ul>
        </div>
        <a href="<?= base_url('exam/student/' . $studentExam['student_exam_id'] . '/marks') ?>"
           class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>
            Back to Marks
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold fs-5 text-gray-900">
                    <i class="ki-duotone ki-book-open fs-3 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    <?= $hasExistingSubjects ? 'Reassign Subjects' : 'Assign Subjects' ?>
                </h3>
            </div>
        </div>
        <div class="card-body py-4">

            <?php if ($hasExistingSubjects): ?>
            <div class="notice d-flex bg-light-warning rounded border border-warning border-dashed p-4 mb-6">
                <i class="ki-duotone ki-information-5 fs-2tx text-warning me-4 flex-shrink-0">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                <div class="text-gray-700 fs-7">
                    Saving will <strong>replace</strong> the current subject selection. Existing marks for removed subjects will be lost.
                </div>
            </div>
            <?php endif; ?>

            <?php if (empty($streamSubjects['core']) && empty($streamSubjects['optional'])): ?>
            <div class="text-center text-muted py-8">
                <i class="ki-duotone ki-book fs-3x text-muted mb-3 d-block"><span class="path1"></span><span class="path2"></span></i>
                <p class="fs-6">No subjects are configured for this student's stream.</p>
            </div>
            <?php else: ?>

            <form method="post" action="<?= base_url('exam/student/' . $studentExam['student_exam_id'] . '/subjects/assign') ?>">
                <?= csrf_field() ?>

                <?php if (!empty($streamSubjects['core'])): ?>
                <div class="mb-5">
                    <div class="fw-semibold text-gray-700 fs-6 mb-3">
                        Core Subjects
                        <span class="text-muted fs-7 fw-normal ms-1">(uncheck to exclude)</span>
                    </div>
                    <div class="row g-3">
                        <?php foreach ($streamSubjects['core'] as $s): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="exam_core_subs[]"
                                       value="<?= esc($s['sch_sub_id']) ?>"
                                       id="core_<?= $s['sch_sub_id'] ?>"
                                       <?= in_array((int)$s['sch_sub_id'], $assignedCore) ? 'checked' : '' ?>>
                                <label class="form-check-label fs-7" for="core_<?= $s['sch_sub_id'] ?>">
                                    <?= esc($s['subject_name']) ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($streamSubjects['optional'])): ?>
                <?php
                    $optGroups = [];
                    foreach ($streamSubjects['optional'] as $s) {
                        $optGroups[$s['option_num']][] = $s;
                    }
                ?>
                <div class="mb-5">
                    <div class="fw-semibold text-gray-700 fs-6 mb-3">Optional Subjects</div>
                    <?php foreach ($optGroups as $grpNum => $options): ?>
                    <div class="card card-bordered mb-3">
                        <div class="card-header min-h-40px py-2 px-4">
                            <span class="card-title fw-semibold text-gray-700 fs-7 m-0">Optional Group <?= esc($grpNum) ?></span>
                            <div class="card-toolbar"><span class="badge badge-light-warning fs-8">choose one</span></div>
                        </div>
                        <div class="card-body py-3 px-4">
                            <div class="row g-2">
                                <?php foreach ($options as $i => $s):
                                    $isSelected = isset($assignedOptional[$grpNum]) && (int)$assignedOptional[$grpNum] === (int)$s['sch_sub_id'];
                                ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="exam_opt_group_<?= $grpNum ?>"
                                               value="<?= esc($s['sch_sub_id']) ?>"
                                               id="opt_<?= $grpNum ?>_<?= $s['sch_sub_id'] ?>"
                                               <?= ($isSelected || ($i === 0 && empty($assignedOptional[$grpNum]))) ? 'checked' : '' ?>>
                                        <label class="form-check-label fs-7" for="opt_<?= $grpNum ?>_<?= $s['sch_sub_id'] ?>">
                                            <?= esc($s['subject_name']) ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="d-flex justify-content-end pt-4 border-top mt-2">
                    <a href="<?= base_url('exam/student/' . $studentExam['student_exam_id'] . '/marks') ?>"
                       class="btn btn-light btn-sm me-3">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Save Subjects
                    </button>
                </div>
            </form>
            <?php endif; ?>

        </div>
    </div>

</div>
</div>
