<?php
$score       = (float) $attempt['score'];
$correct     = (int)   $attempt['correct_markers'];
$total       = (int)   $attempt['total_markers'];
$scoreColor  = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
$submittedAt = $attempt['submitted_at'] ? date('M j, Y g:i A', strtotime($attempt['submitted_at'])) : '—';
$IMG_BASE    = base_url('uploads/label_images/');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Assessment Results</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= $backUrl ?>" class="text-muted text-hover-primary"><?= esc($lesson['lesson_title']) ?></a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Results</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= $transcriptUrl ?>" target="_blank" class="btn btn-sm btn-light-success">
                <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Download Transcript
            </a>
            <a href="<?= $backUrl ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>Back to Lesson
            </a>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <!--begin::Score summary-->
    <div class="card border-0 shadow-sm mb-6">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center p-8" style="border-right:1px solid #f1f1f4;">
                    <div class="position-relative mb-4" style="width:140px;height:140px;">
                        <svg viewBox="0 0 36 36" style="width:140px;height:140px;transform:rotate(-90deg);">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f1f1f4" stroke-width="2.5"/>
                            <circle cx="18" cy="18" r="15.9" fill="none"
                                    stroke="<?= $scoreColor==='success'?'#50cd89':($scoreColor==='warning'?'#ffc700':'#f1416c') ?>"
                                    stroke-width="2.5" stroke-dasharray="<?= round($score,1) ?> 100" stroke-linecap="round"/>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="fw-bold text-gray-900 fs-1"><?= number_format($score,1) ?>%</div>
                        </div>
                    </div>
                    <div class="fw-bold text-gray-700 fs-5 mb-1"><?= $correct ?> / <?= $total ?> Correct</div>
                    <span class="badge badge-light-success">Submitted</span>
                </div>
                <div class="col-md-8 p-6">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="badge badge-light-info fs-9">Labelling</span>
                        <h5 class="fw-bold text-gray-800 mb-0"><?= esc($quiz['quizze_name']) ?></h5>
                    </div>
                    <div class="row g-4">
                        <div class="col-4"><div class="bg-light-primary rounded-2 p-4 text-center">
                            <div class="fw-bold text-primary fs-2x mb-1"><?= $total ?></div>
                            <div class="text-muted fs-9">Total Markers</div>
                        </div></div>
                        <div class="col-4"><div class="bg-light-success rounded-2 p-4 text-center">
                            <div class="fw-bold text-success fs-2x mb-1"><?= $correct ?></div>
                            <div class="text-muted fs-9">Correct</div>
                        </div></div>
                        <div class="col-4"><div class="bg-light-danger rounded-2 p-4 text-center">
                            <div class="fw-bold text-danger fs-2x mb-1"><?= $total - $correct ?></div>
                            <div class="text-muted fs-9">Incorrect</div>
                        </div></div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between py-2" style="border-bottom:1px solid #f1f1f4;">
                        <span class="text-muted fs-8">Submitted</span>
                        <span class="fw-semibold text-gray-800 fs-8"><?= $submittedAt ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--begin::Per-exercise review-->
    <?php foreach ($attempt['questions'] as $qi => $q): ?>
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header border-0 pt-4 pb-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-light-info fs-9">Exercise <?= $qi + 1 ?></span>
                <?php if (!empty($q['question_text'])): ?>
                <span class="fw-semibold text-gray-700 fs-7"><?= esc($q['question_text']) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body pt-0 pb-5">
            <?php if (!empty($q['bg_image'])): ?>
            <div class="position-relative d-inline-block mb-4" style="max-width:100%;">
                <img src="<?= $IMG_BASE . esc($q['bg_image']) ?>"
                     class="rounded-2 d-block" style="max-width:100%;max-height:400px;object-fit:contain;" alt="">
                <?php foreach ($q['markers'] as $mi => $m): ?>
                <div class="stu-score-dot <?= $m['is_correct'] ? 'dot-correct' : 'dot-wrong' ?>"
                     style="left:<?= $m['marker_x'] ?>%;top:<?= $m['marker_y'] ?>%;"
                     title="<?= $mi+1 ?>. Your: <?= esc($m['student_label'] ?: 'blank') ?> | Correct: <?= esc($m['correct_label']) ?>">
                    <?= $m['is_correct'] ? '✓' : ($mi + 1) ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="table-responsive">
            <table class="table table-row-bordered align-middle gs-0 gy-2 fs-8">
                <thead><tr class="fw-bold text-muted bg-light">
                    <th class="ps-3 w-40px">#</th>
                    <th>Your Answer</th>
                    <th>Correct Label</th>
                    <th class="text-center">Result</th>
                </tr></thead>
                <tbody>
                <?php foreach ($q['markers'] as $mi => $m): ?>
                <tr class="<?= $m['is_correct'] ? 'bg-light-success' : ($m['is_answered'] ? 'bg-light-danger' : '') ?>">
                    <td class="ps-3 text-muted"><?= $mi + 1 ?></td>
                    <td>
                        <?php if ($m['is_answered']): ?>
                        <span class="fw-semibold <?= $m['is_correct'] ? 'text-success' : 'text-danger' ?>">
                            <?= esc($m['student_label']) ?>
                        </span>
                        <?php else: ?>
                        <span class="badge badge-light-secondary">Blank</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="fw-semibold text-success"><?= esc($m['correct_label']) ?></span></td>
                    <td class="text-center">
                        <?php if ($m['is_correct']): ?>
                        <span class="badge badge-light-success"><i class="ki-duotone ki-check fs-8"><span class="path1"></span><span class="path2"></span></i> Correct</span>
                        <?php elseif ($m['is_answered']): ?>
                        <span class="badge badge-light-danger"><i class="ki-duotone ki-cross fs-8"><span class="path1"></span><span class="path2"></span></i> Wrong</span>
                        <?php else: ?>
                        <span class="badge badge-light-secondary">Blank</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>
</div>

<style>
.stu-score-dot {
    position:absolute; width:26px; height:26px; border-radius:50%;
    color:#fff; font-size:11px; font-weight:bold;
    display:flex; align-items:center; justify-content:center;
    transform:translate(-50%,-50%); z-index:10;
    border:2.5px solid #fff; box-shadow:0 2px 6px rgba(0,0,0,.35);
    cursor:default;
}
.dot-correct { background:#50cd89; }
.dot-wrong   { background:#f1416c; }
</style>
