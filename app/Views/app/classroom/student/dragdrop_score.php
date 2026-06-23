<?php
$score       = (float) $attempt['score'];
$correct     = (int)   $attempt['correct_items'];
$total       = (int)   $attempt['total_items'];
$status      = $attempt['status'];
$statusLabel = $status === 'submitted' ? 'Submitted' : 'Submitted';
$scoreColor  = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
$submittedAt = $attempt['submitted_at'] ? date('M j, Y g:i A', strtotime($attempt['submitted_at'])) : '—';
$IMG_BASE    = base_url('uploads/dragdrop_files/');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Assessment Results
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= $backUrl ?>" class="text-muted text-hover-primary"><?= esc($lesson['lesson_title']) ?></a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Results</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= $transcriptUrl ?>" target="_blank" class="btn btn-sm btn-light-success">
                <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Download Transcript
            </a>
            <a href="<?= $backUrl ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                Back to Lesson
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <!--begin::Score summary card-->
    <div class="card border-0 shadow-sm mb-6">
        <div class="card-body p-0">
            <div class="row g-0">
                <!--begin::Score circle-->
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center p-8" style="border-right:1px solid #f1f1f4;">
                    <div class="position-relative mb-4" style="width:140px;height:140px;">
                        <svg viewBox="0 0 36 36" style="width:140px;height:140px;transform:rotate(-90deg);">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f1f1f4" stroke-width="2.5"/>
                            <circle cx="18" cy="18" r="15.9" fill="none"
                                    stroke="<?= $scoreColor === 'success' ? '#50cd89' : ($scoreColor === 'warning' ? '#ffc700' : '#f1416c') ?>"
                                    stroke-width="2.5"
                                    stroke-dasharray="<?= round($score, 1) ?> 100"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="fw-bold text-gray-900 fs-1"><?= number_format($score, 1) ?>%</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold text-gray-700 fs-5 mb-1"><?= $correct ?> / <?= $total ?> Correct</div>
                        <span class="badge badge-light-success">Submitted</span>
                    </div>
                </div>
                <!--end::Score circle-->

                <!--begin::Stats-->
                <div class="col-md-8 p-6">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="badge badge-light-primary fs-9">Drag &amp; Drop</span>
                        <h5 class="fw-bold text-gray-800 mb-0"><?= esc($quiz['quizze_name']) ?></h5>
                    </div>
                    <div class="row g-4">
                        <div class="col-6 col-md-4">
                            <div class="bg-light-primary rounded-2 p-4 text-center">
                                <div class="fw-bold text-primary fs-2x mb-1"><?= $total ?></div>
                                <div class="text-muted fs-9">Total Items</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="bg-light-success rounded-2 p-4 text-center">
                                <div class="fw-bold text-success fs-2x mb-1"><?= $correct ?></div>
                                <div class="text-muted fs-9">Correct</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="bg-light-danger rounded-2 p-4 text-center">
                                <div class="fw-bold text-danger fs-2x mb-1"><?= $total - $correct ?></div>
                                <div class="text-muted fs-9">Incorrect</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f1f4;">
                                <span class="text-muted fs-8">Submitted</span>
                                <span class="fw-semibold text-gray-800 fs-8"><?= $submittedAt ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f1f4;">
                                <span class="text-muted fs-8">Duration</span>
                                <span class="fw-semibold text-gray-800 fs-8">
                                    <?= (int) $quiz['quizze_duration'] > 0 ? (int) $quiz['quizze_duration'] . ' min' : 'No limit' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Stats-->
            </div>
        </div>
    </div>
    <!--end::Score summary card-->

    <!--begin::Item review-->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-5 pb-0">
            <h5 class="fw-bold text-gray-800 mb-0">Item Review</h5>
            <span class="text-muted fs-8">How you placed each item vs the correct answer</span>
        </div>
        <div class="card-body p-6">
            <div class="table-responsive">
            <table class="table table-row-bordered align-middle gs-0 gy-3 fs-7">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-50px">#</th>
                        <th class="min-w-50px">Image</th>
                        <th class="min-w-200px">Item</th>
                        <th class="min-w-160px">Your Answer</th>
                        <th class="min-w-160px">Correct Zone</th>
                        <th class="text-center min-w-80px">Result</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($attempt['items'] as $idx => $item): ?>
                <tr class="<?= $item['is_correct'] ? 'bg-light-success' : ($item['is_placed'] ? 'bg-light-danger' : '') ?>">
                    <td class="ps-4 text-muted fs-8"><?= $idx + 1 ?></td>
                    <td>
                        <?php if (!empty($item['item_image'])): ?>
                        <img src="<?= $IMG_BASE . esc($item['item_image']) ?>" class="rounded-2"
                             style="width:36px;height:36px;object-fit:cover;" alt="">
                        <?php else: ?>
                        <span class="text-muted fs-9">—</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="fw-semibold text-gray-800"><?= esc($item['item_text']) ?></span></td>
                    <td>
                        <?php if ($item['is_placed']): ?>
                        <span class="fw-semibold <?= $item['is_correct'] ? 'text-success' : 'text-danger' ?>">
                            <?= esc($item['student_zone_label']) ?>
                        </span>
                        <?php else: ?>
                        <span class="badge badge-light-secondary fs-9">Not placed</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($item['correct_zone_id']): ?>
                        <span class="fw-semibold text-success"><?= esc($item['correct_zone_label']) ?></span>
                        <?php else: ?>
                        <span class="text-muted fs-9">Not mapped</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($item['is_correct']): ?>
                        <span class="badge badge-light-success fs-9">
                            <i class="ki-duotone ki-check fs-8"><span class="path1"></span><span class="path2"></span></i> Correct
                        </span>
                        <?php elseif ($item['is_placed']): ?>
                        <span class="badge badge-light-danger fs-9">
                            <i class="ki-duotone ki-cross fs-8"><span class="path1"></span><span class="path2"></span></i> Incorrect
                        </span>
                        <?php else: ?>
                        <span class="badge badge-light-secondary fs-9">Not placed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <!--end::Item review-->

</div>
</div>
