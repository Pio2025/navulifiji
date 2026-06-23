<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Quiz Results
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

    <?php
    $score        = (float) $attempt['score'];
    $correct      = (int)   $attempt['correct_answers'];
    $total        = (int)   $attempt['total_questions'];
    $status       = $attempt['status'];
    $responded    = count($attempt['responses'] ?? []);
    $scoreColor   = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
    $statusLabel  = $status === 'timed_out' ? 'Timed Out' : 'Submitted';
    $statusColor  = $status === 'timed_out' ? 'warning' : 'success';
    $submittedAt  = $attempt['submitted_at'] ? date('M j, Y g:i A', strtotime($attempt['submitted_at'])) : '—';
    ?>

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
                        <span class="badge badge-light-<?= $statusColor ?>"><?= $statusLabel ?></span>
                    </div>
                </div>
                <!--end::Score circle-->

                <!--begin::Stats-->
                <div class="col-md-8 p-6">
                    <h5 class="fw-bold text-gray-800 mb-5"><?= esc($quiz['quizze_name']) ?></h5>
                    <div class="row g-4">
                        <div class="col-6 col-md-3">
                            <div class="bg-light-primary rounded-2 p-4 text-center">
                                <div class="fw-bold text-primary fs-2x mb-1"><?= $total ?></div>
                                <div class="text-muted fs-9">Questions</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="bg-light-success rounded-2 p-4 text-center">
                                <div class="fw-bold text-success fs-2x mb-1"><?= $correct ?></div>
                                <div class="text-muted fs-9">Correct</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="bg-light-danger rounded-2 p-4 text-center">
                                <div class="fw-bold text-danger fs-2x mb-1"><?= $total - $correct ?></div>
                                <div class="text-muted fs-9">Incorrect</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="bg-light-warning rounded-2 p-4 text-center">
                                <div class="fw-bold text-warning fs-2x mb-1"><?= $total - $responded ?></div>
                                <div class="text-muted fs-9">Unanswered</div>
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
                                <span class="text-muted fs-8">Status</span>
                                <span class="badge badge-light-<?= $statusColor ?> fs-9"><?= $statusLabel ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Stats-->
            </div>
        </div>
    </div>
    <!--end::Score summary card-->

    <!--begin::Question review-->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-5 pb-0">
            <h5 class="fw-bold text-gray-800 mb-0">Question Review</h5>
            <span class="text-muted fs-8"><?= $responded ?> of <?= $total ?> questions answered</span>
        </div>
        <div class="card-body p-6">
            <?php
            // Build a lookup: question_id → response
            $responseMap = [];
            foreach ($attempt['responses'] as $r) {
                $responseMap[(int) $r['question_id_fk']] = $r;
            }
            $letters = ['A','B','C','D','E'];
            $qi = 0;
            foreach ($quiz['questions'] as $q):
                $qi++;
                $qId        = (int) $q['quizze_quest_id'];
                $response   = $responseMap[$qId] ?? null;
                $isCorrect  = $response && (int) $response['is_correct'] === 1;
                $isAnswered = $response !== null;
                $borderClr  = !$isAnswered ? 'gray-200' : ($isCorrect ? 'success' : 'danger');
                $bgClr      = !$isAnswered ? '' : ($isCorrect ? 'bg-light-success' : 'bg-light-danger');
            ?>
            <div class="border border-<?= $borderClr ?> rounded-2 p-5 mb-4 <?= $bgClr ?>">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="symbol symbol-35px flex-shrink-0">
                        <div class="symbol-label fw-bold fs-7
                            <?= !$isAnswered ? 'bg-light-secondary text-gray-600' : ($isCorrect ? 'bg-success text-white' : 'bg-danger text-white') ?>">
                            <?= $qi ?>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-gray-800 fs-6 lh-base mb-1"><?= nl2br(esc($q['question'])) ?></div>
                        <?php if (!$isAnswered): ?>
                        <span class="badge badge-light-secondary fs-9">Not answered</span>
                        <?php elseif ($isCorrect): ?>
                        <span class="badge badge-light-success fs-9">
                            <i class="ki-duotone ki-check fs-8"><span class="path1"></span><span class="path2"></span></i>
                            Correct
                        </span>
                        <?php else: ?>
                        <span class="badge badge-light-danger fs-9">
                            <i class="ki-duotone ki-cross fs-8"><span class="path1"></span><span class="path2"></span></i>
                            Incorrect
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!--begin::Question images-->
                <?php if (!empty($q['files'])): ?>
                <div class="d-flex flex-wrap gap-3 ps-11 mb-3">
                    <?php foreach ($q['files'] as $f): ?>
                    <a href="<?= base_url('uploads/quiz_files/' . $f['file_src']) ?>" target="_blank">
                        <img src="<?= base_url('uploads/quiz_files/' . $f['file_src']) ?>"
                             class="rounded-2 border border-gray-200"
                             style="max-height:160px;max-width:260px;object-fit:contain;" alt="">
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <!--end::Question images-->

                <!--begin::Answer list-->
                <div class="ps-11">
                    <?php foreach ($q['answers'] as $ai => $ans):
                        $aId        = (int) $ans['lesson_quizze_answer_id'];
                        $selected   = $response && (int) $response['answer_id_fk'] === $aId;
                        $isRight    = (int) $ans['is_correct_answer'] === 1;
                        $ansBg      = $isRight ? 'bg-light-success border-success' : ($selected && !$isRight ? 'bg-light-danger border-danger' : 'bg-white border-gray-200');
                        $ansText    = $isRight ? 'text-success fw-bold' : ($selected && !$isRight ? 'text-danger' : 'text-gray-600');
                    ?>
                    <div class="d-flex align-items-center gap-3 p-2 rounded-2 mb-2 border <?= $ansBg ?>">
                        <div class="symbol symbol-22px flex-shrink-0">
                            <div class="symbol-label fs-10 fw-bold <?= $isRight ? 'bg-success text-white' : 'bg-light-secondary text-gray-600' ?>"><?= $letters[$ai] ?? chr(65 + $ai) ?></div>
                        </div>
                        <span class="fs-8 <?= $ansText ?>"><?= esc($ans['answer']) ?></span>
                        <?php if ($isRight): ?>
                        <i class="ki-duotone ki-check-circle fs-5 text-success ms-auto"><span class="path1"></span><span class="path2"></span></i>
                        <?php elseif ($selected): ?>
                        <i class="ki-duotone ki-cross-circle fs-5 text-danger ms-auto"><span class="path1"></span><span class="path2"></span></i>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!--end::Answer list-->
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!--end::Question review-->

</div>
</div>
