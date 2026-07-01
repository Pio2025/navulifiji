<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($assignment['assignment_name']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('classroom/student/' . $classSubId . '/assignments') ?>" class="text-muted text-hover-primary">Assignments</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Submit</li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/student/' . $classSubId . '/assignments') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$asgnFiles     = $assignment['files'] ?? [];
$hasFile       = !empty($asgnFiles);
$pdfFiles      = array_values(array_filter($asgnFiles, fn($f) => strtolower(pathinfo($f['file_src'], PATHINFO_EXTENSION)) === 'pdf'));
$hasPdf        = !empty($pdfFiles);
$primaryPdfSrc = $hasPdf ? $pdfFiles[0]['file_src'] : null;
$primaryPdfUrl = $hasPdf ? base_url('uploads/assignments/' . $primaryPdfSrc) : '';
$gridFiles     = $asgnFiles;
$hasGrid       = $hasFile;
$isPastDue     = !empty($assignment['assignment_due_date']) && strtotime($assignment['assignment_due_date']) < time();
$submitted     = !empty($submission);
?>

<!--begin::Assignment meta bar-->
<div class="card border-0 shadow-sm mb-6">
    <div class="card-body px-6 py-4">
        <div class="d-flex align-items-center flex-wrap gap-5">
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-document fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                <span class="fw-bold text-gray-800 fs-6"><?= esc($assignment['assignment_name']) ?></span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-calendar fs-4 <?= $isPastDue ? 'text-danger' : 'text-warning' ?>">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="fw-semibold fs-7 <?= $isPastDue ? 'text-danger' : 'text-gray-700' ?>">
                    Due: <?= !empty($assignment['assignment_due_date']) ? date('d M Y, g:i A', strtotime($assignment['assignment_due_date'])) : '—' ?>
                </span>
                <?php if ($isPastDue): ?>
                <span class="badge badge-light-danger fs-9">Past Due</span>
                <?php endif; ?>
            </div>
            <?php if ($submitted): ?>
            <span class="badge badge-light-<?= $submission['submission_status'] === 'Late' ? 'warning' : ($submission['submission_status'] === 'Graded' ? 'success' : 'primary') ?> fs-8">
                <?= esc($submission['submission_status']) ?>
                &bull; <?= date('d M Y', strtotime($submission['submitted_at'])) ?>
            </span>
            <?php endif; ?>
            <div class="ms-auto text-muted fs-8 d-flex align-items-center gap-1">
                <i class="ki-duotone ki-user fs-5"><span class="path1"></span><span class="path2"></span></i>
                Posted by <?= esc($assignment['creator_name'] ?? '—') ?>
            </div>
        </div>
    </div>
</div>
<!--end::Assignment meta bar-->

<div class="row g-6">

<!--begin::PDF Flipbook card-->
<?php if ($hasPdf): ?>
<div class="col-12">
<div class="card border-0 shadow-sm overflow-hidden">

    <!--begin::Card header-->
    <div class="card-header border-0 pt-5 pb-3 px-6" style="background:#fff;">
        <div class="d-flex align-items-center gap-2">
            <i class="ki-duotone ki-book-open fs-3 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <h5 class="fw-bold text-gray-800 fs-5 mb-0">Assignment Documents</h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button id="soundToggle" class="btn btn-sm btn-light d-inline-flex align-items-center gap-2" title="Toggle flip sound"
                    style="height:34px;padding:0 12px;border-radius:8px;font-size:.8rem;font-weight:600;">
                <span class="sound-on">🔊 Sound On</span>
                <span class="sound-off d-none">🔇 Muted</span>
            </button>
            <?php if (count($pdfFiles) === 1): ?>
            <a href="<?= $primaryPdfUrl ?>" target="_blank" class="btn btn-sm btn-light-primary" style="height:34px;">
                <i class="ki-duotone ki-exit-right fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                Open PDF
            </a>
            <?php else: ?>
            <div class="dropdown">
                <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false" style="height:34px;">
                    <i class="ki-duotone ki-exit-right fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Open PDF
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:200px;">
                    <?php foreach ($pdfFiles as $pi => $pf): ?>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 pdf-switcher"
                           href="#"
                           data-url="<?= base_url('uploads/assignments/' . $pf['file_src']) ?>">
                            <i class="ki-duotone ki-file-down fs-6 text-danger"><span class="path1"></span><span class="path2"></span></i>
                            PDF Document #<?= $pi + 1 ?>
                            <?php if ($pi === 0): ?>
                            <span class="badge badge-light-success ms-auto fs-9 pdf-current-badge">Current</span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <button id="btnFullscreen" class="btn btn-sm btn-dark d-flex align-items-center justify-content-center gap-1"
                    title="Toggle fullscreen"
                    style="height:34px;padding:0 10px;flex-shrink:0;font-size:.78rem;font-weight:600;white-space:nowrap;">
                <span class="fs-icon-enter d-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 1v4h1V2h3V1H1zm9 0v1h3v3h1V1h-4zM1 15h4v-1H2v-3H1v4zm10 0h4v-4h-1v3h-3v1z"/>
                    </svg>
                    Full
                </span>
                <span class="fs-icon-exit d-none d-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5 0h-1v4h-4v1h5V0zm6 0v5h5v-1h-4V0h-1zM0 11v1h4v4h1v-5H0zm11 5h1v-4h4v-1h-5v5z"/>
                    </svg>
                    Exit
                </span>
            </button>
        </div>
    </div>
    <!--end::Card header-->

    <!--begin::Book stage (dark reading area)-->
    <div class="book-stage">

        <!--begin::Ambient glow orbs-->
        <div class="book-glow book-glow-l"></div>
        <div class="book-glow book-glow-r"></div>

        <!--begin::Loading state-->
        <div id="pdfLoading" class="book-loading">
            <div class="book-loading-icon mb-4">
                <i class="ki-duotone ki-book fs-4x text-white opacity-50"><span class="path1"></span><span class="path2"></span></i>
            </div>
            <div class="text-white fw-semibold fs-6 mb-3" id="pdfLoadText">Preparing document…</div>
            <div class="progress-track">
                <div class="progress-fill" id="pdfProgressBar" style="width:0%"></div>
            </div>
            <div class="text-white opacity-50 fs-8 mt-2" id="pdfProgressLabel"></div>
        </div>
        <!--end::Loading state-->

        <!--begin::Book wrapper (hidden until loaded)-->
        <div id="bookWrap" style="display:none;">

            <!--begin::Side nav arrows-->
            <button class="side-nav-btn side-nav-left" id="pdfPrev" disabled title="Previous (←)">
                <i class="ki-duotone ki-arrow-left fs-1"><span class="path1"></span><span class="path2"></span></i>
            </button>

            <!--begin::Flip book container-->
            <div class="flipbook-container">
                <!--begin::Open book shell-->
                <div class="book-outer">
                    <!--Top hardcover binding-->
                    <div class="book-binding book-binding-top" aria-hidden="true">
                        <div class="book-binding-left"></div>
                        <div class="book-binding-spine-notch"></div>
                        <div class="book-binding-right"></div>
                    </div>
                    <!--Pages area with spine overlay-->
                    <div class="book-pages-wrap" id="bookPagesWrap">
                        <div id="flipBook"></div>
                        <!--Center spine shadow overlay (pointer-events:none)-->
                        <div class="book-spine-overlay" aria-hidden="true"></div>
                        <!--Left page edge shading-->
                        <div class="book-page-edge book-page-edge-left" aria-hidden="true"></div>
                        <!--Right page edge shading-->
                        <div class="book-page-edge book-page-edge-right" aria-hidden="true"></div>
                    </div>
                    <!--Bottom hardcover binding-->
                    <div class="book-binding book-binding-bottom" aria-hidden="true">
                        <div class="book-binding-left"></div>
                        <div class="book-binding-spine-notch"></div>
                        <div class="book-binding-right"></div>
                    </div>
                </div>
                <!--end::Open book shell-->
            </div>
            <!--end::Flip book container-->

            <button class="side-nav-btn side-nav-right" id="pdfNext" disabled title="Next (→)">
                <i class="ki-duotone ki-arrow-right fs-1"><span class="path1"></span><span class="path2"></span></i>
            </button>

        </div>
        <!--end::Book wrapper-->

        <!--begin::Page counter-->
        <div class="book-footer" id="bookFooter" style="display:none;">
            <div class="page-counter">
                <span id="pageInfo">—</span>
            </div>
            <div class="key-hint">Use ← → arrow keys or drag pages to flip</div>
        </div>
        <!--end::Page counter-->

    </div>
    <!--end::Book stage-->

</div>
</div>
<?php elseif (!$hasFile): ?>
<!--begin::No files card-->
<div class="col-12">
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-16 text-muted">
        <i class="ki-duotone ki-document fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-6 fw-semibold">No files attached to this assignment.</div>
    </div>
</div>
</div>
<?php endif; ?>
<!--end::PDF Flipbook card-->

<?php if ($hasGrid): ?>
<!--begin::Files grid card-->
<div class="col-12">
<div class="card border-0 shadow-sm">
    <div class="card-header border-0 pt-5 pb-3 px-6">
        <div class="d-flex align-items-center gap-2">
            <i class="ki-duotone ki-folder-up fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
            <h5 class="fw-bold text-gray-800 fs-5 mb-0">Assignment Files</h5>
        </div>
        <span class="badge badge-light-primary fs-9"><?= count($gridFiles) ?> file<?= count($gridFiles) !== 1 ? 's' : '' ?></span>
    </div>
    <div class="card-body px-6 pb-6 pt-3">
        <div class="asgn-file-grid">
        <?php foreach ($gridFiles as $gFile):
            $gExt  = strtolower(pathinfo($gFile['file_src'], PATHINFO_EXTENSION));
            $gUrl  = base_url('uploads/assignments/' . $gFile['file_src']);
            [$gIcon, $gColor, $gBg] = match($gExt) {
                'pdf'                          => ['ki-file-down',   'text-danger',  '#fff5f5'],
                'jpg','jpeg','png','gif','webp' => ['ki-picture',     'text-primary', '#eff6ff'],
                'doc','docx'                   => ['ki-file',         'text-info',    '#f0f9ff'],
                'xls','xlsx'                   => ['ki-chart-simple', 'text-success', '#f0fdf4'],
                'ppt','pptx'                   => ['ki-file',         'text-warning', '#fffbeb'],
                'zip','tar','gz','rar'         => ['ki-folder',       'text-warning', '#fffbeb'],
                'txt'                          => ['ki-file',         'text-muted',   '#f9fafb'],
                default                        => ['ki-file',         'text-muted',   '#f9fafb'],
            };
        ?>
        <div class="asgn-file-grid-item"
             data-bs-toggle="tooltip"
             data-bs-placement="top"
             title="<?= esc($gFile['file_src']) ?>">
            <a href="<?= $gUrl ?>" target="_blank" class="file-card-link d-block text-decoration-none">
                <div class="rounded-2 border text-center py-3 px-1"
                     style="background:<?= $gBg ?>;min-height:90px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;">
                    <i class="ki-duotone <?= $gIcon ?> fs-2x <?= $gColor ?>"><span class="path1"></span><span class="path2"></span></i>
                    <span class="badge badge-light-secondary fs-10 mt-1"><?= strtoupper($gExt) ?></span>
                    <div class="text-muted px-1 mt-1 asgn-file-name"><?= esc($gFile['file_src']) ?></div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
</div>
<!--end::Files grid card-->
<?php endif; ?>

<!--begin::Submission card-->
<div class="col-12">
<div class="card border-0 shadow-sm">
    <?php
    $totalScore  = (float)($assignment['assignment_total_score'] ?? 100);
    $isGraded    = $submitted && !empty($score) && $score['assignment_mark'] !== null;
    $mark        = $isGraded ? (float)$score['assignment_mark'] : null;
    $pct         = ($isGraded && $totalScore > 0) ? round(($mark / $totalScore) * 100, 1) : null;
    $grade       = $pct === null ? null : ($pct >= 80 ? 'A' : ($pct >= 65 ? 'B' : ($pct >= 50 ? 'C' : ($pct >= 40 ? 'D' : 'F'))));
    $scoreColor  = $pct === null ? 'primary' : ($pct >= 70 ? 'success' : ($pct >= 50 ? 'warning' : 'danger'));
    $scoreHex    = $pct === null ? '#3b82f6' : ($pct >= 70 ? '#22c55e' : ($pct >= 50 ? '#f59e0b' : '#ef4444'));
    $circumf     = 427.3;
    $dashOffset  = $pct !== null ? $circumf - ($circumf * min($pct,100) / 100) : $circumf;
    ?>
    <div class="card-header border-0 pt-5 pb-3 px-6">
        <div class="d-flex align-items-center gap-2">
            <i class="ki-duotone ki-send fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
            <h5 class="fw-bold text-gray-800 fs-5 mb-0">
                <?= $isGraded ? 'Graded' : ($submitted ? 'Resubmit Your Work' : 'Submit Your Work') ?>
            </h5>
        </div>
        <?php if ($submitted): ?>
        <span class="badge badge-light-<?= $submission['submission_status']==='Late'?'warning':($isGraded?'success':'primary') ?> fs-8">
            <?= $isGraded ? 'Graded' : esc($submission['submission_status']) ?>
        </span>
        <?php endif; ?>
    </div>
    <div class="card-body px-6 pb-6 pt-4">

        <?php if ($isGraded): ?>
        <!--begin::Grade result block-->
        <div class="row g-5 mb-5">

            <!--Score ring-->
            <div class="col-md-4 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <div class="position-relative mx-auto mb-3" style="width:130px;height:130px;">
                        <svg viewBox="0 0 160 160" style="transform:rotate(-90deg);width:130px;height:130px;">
                            <circle cx="80" cy="80" r="68" fill="none" stroke="#f1f5f9" stroke-width="14"/>
                            <circle cx="80" cy="80" r="68" fill="none"
                                    stroke="<?= $scoreHex ?>"
                                    stroke-width="14"
                                    stroke-linecap="round"
                                    stroke-dasharray="<?= $circumf ?>"
                                    stroke-dashoffset="<?= $dashOffset ?>"/>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <div class="fw-bold fs-1 lh-1 text-<?= $scoreColor ?>"><?= $pct ?>%</div>
                            <div class="fw-bold text-gray-400 fs-9 mt-1">Grade <?= $grade ?></div>
                        </div>
                    </div>
                    <div class="fw-bold text-gray-800 fs-4"><?= $mark ?> / <?= $totalScore ?></div>
                    <div class="text-muted fs-9 mt-1">Your Mark</div>
                </div>
            </div>

            <!--Feedback + submission info-->
            <div class="col-md-8">
                <div class="d-flex flex-column gap-4 h-100 justify-content-center">

                    <!--Feedback-->
                    <div class="rounded-2 p-4" style="background:#f8fafc;border:1px solid #e2e8f0;">
                        <div class="fw-bold text-gray-700 fs-8 mb-2 d-flex align-items-center gap-2">
                            <i class="ki-duotone ki-message-text-2 fs-5 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Teacher Feedback
                        </div>
                        <?php if (!empty($score['feedback'])): ?>
                        <div class="text-gray-600 fs-8 lh-lg"><?= nl2br(esc($score['feedback'])) ?></div>
                        <?php else: ?>
                        <div class="text-muted fs-9 fst-italic">No feedback provided.</div>
                        <?php endif; ?>
                    </div>

                    <!--Submission meta-->
                    <div class="d-flex flex-wrap gap-4 align-items-center">
                        <a href="<?= base_url('uploads/assignment_submissions/'.$submission['submission_file']) ?>"
                           target="_blank" class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-file-down fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                            My <?= strtoupper(esc($submission['submission_file_type'])) ?> Submission
                        </a>
                        <div class="text-muted fs-9">
                            Submitted <?= date('d M Y', strtotime($submission['submitted_at'])) ?>
                            <?= $submission['submission_status']==='Late' ? '<span class="badge badge-light-warning ms-1 fs-9">Late</span>' : '' ?>
                        </div>
                        <?php if (!empty($score['graded_at'])): ?>
                        <div class="text-muted fs-9">Marked <?= date('d M Y', strtotime($score['graded_at'])) ?></div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
        <!--end::Grade result block-->

        <?php if (!empty($plagiarism) && ($plagiarism['status'] === 'completed') && $plagiarism['score'] !== null): ?>
        <?php $plScoreG = (float)$plagiarism['score']; $plColorG = $plScoreG >= 40 ? 'danger' : ($plScoreG >= 20 ? 'warning' : 'success'); ?>
        <div class="d-flex align-items-center gap-3 p-3 rounded-2 mb-4" style="background:#f8fafc;border:1px solid #e2e8f0;">
            <i class="ki-duotone ki-scan-barcode fs-2 text-muted"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></i>
            <div class="flex-grow-1">
                <div class="text-muted fs-9 mb-1">Plagiarism similarity (Copyleaks)</div>
                <span class="badge badge-light-<?= $plColorG ?> fs-8 fw-bold"><?= number_format($plScoreG, 1) ?>% similarity</span>
            </div>
        </div>
        <?php endif; ?>

        <?php elseif ($submitted): ?>
        <!--begin::Submitted awaiting grade-->
        <div class="d-flex align-items-center gap-3 p-4 rounded-2 mb-5" style="background:#f0fdf4;border:1px solid #bbf7d0;">
            <i class="ki-duotone ki-check-circle fs-2 text-success"><span class="path1"></span><span class="path2"></span></i>
            <div class="flex-grow-1 min-w-0">
                <div class="fw-bold text-gray-800 fs-7 mb-1">
                    Work submitted
                    <?= $submission['submission_status'] === 'Late' ? '<span class="badge badge-light-warning ms-2 fs-9">Late</span>' : '' ?>
                </div>
                <a href="<?= base_url('uploads/assignment_submissions/'.$submission['submission_file']) ?>"
                   target="_blank" class="text-success fw-semibold fs-8">
                    <i class="ki-duotone ki-file-down fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                    <?= strtoupper(esc($submission['submission_file_type'])) ?> file &bull; <?= date('d M Y, g:i A', strtotime($submission['submitted_at'])) ?>
                </a>
            </div>
        </div>
        <!--end::Submitted awaiting grade-->

        <?php if (!empty($plagiarism)): ?>
        <?php
            $plStatus = $plagiarism['status'];
            $plScore  = ($plagiarism['score'] !== null) ? (float) $plagiarism['score'] : null;
        ?>
        <!--begin::Plagiarism status-->
        <div class="d-flex align-items-center gap-3 p-3 rounded-2 mb-5"
             style="background:#f8fafc;border:1px solid #e2e8f0;">
            <?php if ($plStatus === 'completed' && $plScore !== null):
                $plColor  = $plScore >= 40 ? '#dc2626' : ($plScore >= 20 ? '#d97706' : '#16a34a');
                $plBg     = $plScore >= 40 ? '#fef2f2' : ($plScore >= 20 ? '#fffbeb' : '#f0fdf4');
                $plBorder = $plScore >= 40 ? '#fecaca' : ($plScore >= 20 ? '#fde68a' : '#bbf7d0');
                $plLabel  = $plScore >= 40 ? 'High similarity detected' : ($plScore >= 20 ? 'Moderate similarity' : 'Low similarity');
            ?>
            <div class="d-flex align-items-center gap-3 p-3 rounded-2 flex-grow-1"
                 style="background:<?= $plBg ?>;border:1px solid <?= $plBorder ?>;">
                <div class="fw-black fs-2" style="color:<?= $plColor ?>;"><?= number_format($plScore, 1) ?>%</div>
                <div>
                    <div class="fw-bold fs-8" style="color:<?= $plColor ?>;"><?= $plLabel ?></div>
                    <div class="text-muted fs-9">Similarity score via Copyleaks plagiarism detection</div>
                    <?php if ($plScore >= 20): ?>
                    <div class="text-muted fs-9 mt-1">Your teacher will review this result when marking.</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php elseif ($plStatus === 'submitted' || $plStatus === 'scanning' || $plStatus === 'pending'): ?>
            <i class="ki-duotone ki-scan-barcode fs-2 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></i>
            <div>
                <div class="fw-bold fs-8 text-info">Plagiarism check in progress</div>
                <div class="text-muted fs-9">Copyleaks is scanning your submission. Results will appear here and be visible to your teacher.</div>
            </div>
            <?php elseif ($plStatus === 'error' || $plStatus === 'credits_expired'): ?>
            <i class="ki-duotone ki-information-5 fs-2 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div>
                <div class="fw-bold fs-8 text-warning">Plagiarism check unavailable</div>
                <div class="text-muted fs-9">The automated check could not be completed. Your submission has still been received.</div>
            </div>
            <?php endif; ?>
        </div>
        <!--end::Plagiarism status-->
        <?php endif; ?>

        <?php endif; ?>

        <?php if (!$isGraded): ?>
        <!--begin::Important notes alert-->
        <div class="alert d-flex align-items-start gap-4 mb-6 p-4"
             style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;">
            <i class="ki-duotone ki-information-5 fs-2 flex-shrink-0 mt-1" style="color:#0284c7;">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <div class="flex-grow-1">
                <div class="fw-bold fs-7 mb-2" style="color:#0369a1;">Important Submission Notes</div>
                <ul class="mb-0 ps-4 d-flex flex-column gap-2 fs-8 text-gray-700">
                    <li><strong>Review all materials first.</strong> Read every file in the Assignment Files section above before you begin your work.</li>
                    <li><strong>Reference correctly.</strong> Include all sources using <strong>APA 7th Edition</strong> format.
                        <a href="https://www.usp.ac.fj/library/referencing/" target="_blank" class="fw-semibold" style="color:#0284c7;">USP Fiji Referencing Guide ↗</a></li>
                    <li><strong>Submit early — do not wait until the last minute.</strong> Technical issues (internet, device) are not accepted as grounds for late submission. The system closes at the due date and time.</li>
                    <li><strong>Academic integrity.</strong> Your submission must be entirely your own work. Plagiarism is treated as a serious academic offence and may result in a mark of zero and disciplinary action.
                        Check your work before submitting using a free plagiarism checker:
                        <a href="https://www.grammarly.com/plagiarism-checker" target="_blank" class="fw-semibold" style="color:#0284c7;">Grammarly ↗</a> &nbsp;·&nbsp;
                        <a href="https://smallseotools.com/plagiarism-checker/" target="_blank" class="fw-semibold" style="color:#0284c7;">SmallSEOTools ↗</a> &nbsp;·&nbsp;
                        <a href="https://www.duplichecker.com/" target="_blank" class="fw-semibold" style="color:#0284c7;">DupliChecker ↗</a></li>
                    <li><strong>File format.</strong> Submit as <strong>PDF</strong>, <strong>ZIP</strong>, or <strong>RAR</strong> only. Verify your file opens correctly before uploading.</li>
                </ul>
            </div>
        </div>
        <!--end::Important notes alert-->
        <div class="mb-5">
            <label class="form-label fw-semibold fs-7 required">
                <?= $submitted ? 'Upload New File (replaces current submission)' : 'Upload Your Work' ?>
            </label>
            <div class="dropzone-area" id="dropzoneArea">
                <input type="file" id="submissionFile" accept=".pdf,.zip,.rar" class="d-none">
                <div id="dropzonePrompt" class="text-center py-8">
                    <i class="ki-duotone ki-cloud-add fs-3x text-gray-300 mb-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="fw-semibold text-gray-600 fs-7 mb-1">Drag &amp; drop your file here</div>
                    <div class="text-muted fs-8 mb-4">or</div>
                    <button type="button" class="btn btn-sm btn-light-primary" onclick="document.getElementById('submissionFile').click()">
                        <i class="ki-duotone ki-folder-up fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Browse File
                    </button>
                    <div class="text-muted fs-9 mt-3">Accepted formats: PDF, ZIP, RAR</div>
                </div>
                <div id="dropzoneSelected" class="d-none text-center py-6">
                    <i class="ki-duotone ki-file-added fs-3x text-success mb-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="fw-bold text-gray-800 fs-7 mb-1" id="selectedFileName"></div>
                    <div class="text-muted fs-9 mb-3" id="selectedFileSize"></div>
                    <button type="button" class="btn btn-sm btn-light-danger" id="btnClearFile">
                        <i class="ki-duotone ki-trash fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Remove
                    </button>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="btnSubmitWork">
                <span class="indicator-label">
                    <i class="ki-duotone ki-send fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>
                    <?= $submitted ? 'Resubmit' : 'Submit Assignment' ?>
                </span>
                <span class="indicator-progress">Uploading… <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
        <?php else: ?>
        <div class="text-center py-4 text-muted fs-7 fst-italic">
            <i class="ki-duotone ki-lock fs-3 text-gray-300 mb-2"><span class="path1"></span><span class="path2"></span></i><br>
            This submission has been graded and is now locked.
        </div>
        <?php endif; ?>

    </div>
</div>
</div>
<!--end::Submission card-->

</div><!-- /row -->
</div>
</div>

<!--begin::Scripts-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://unpkg.com/page-flip@2.0.7/dist/js/page-flip.browser.js"></script>
<script>
(function () {
'use strict';

// ─── Sound Engine ───────────────────────────────────────────────────────────
let audioCtx   = null;
let soundMuted = false;

function getAudioCtx() {
    if (!audioCtx) {
        try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e) {}
    }
    return audioCtx;
}

function playFlipSound() {
    if (soundMuted) return;
    const ctx = getAudioCtx();
    if (!ctx) return;
    try {
        const duration  = 0.24;
        const sr        = ctx.sampleRate;
        const numFrames = Math.ceil(sr * duration);
        const buf       = ctx.createBuffer(2, numFrames, sr);

        for (let ch = 0; ch < 2; ch++) {
            const data = buf.getChannelData(ch);
            let b0=0,b1=0,b2=0,b3=0,b4=0,b5=0,b6=0;
            for (let i = 0; i < numFrames; i++) {
                const white = Math.random() * 2 - 1;
                // Paul Kellet's pink-noise approximation
                b0 = 0.99886*b0 + white*0.0555179;
                b1 = 0.99332*b1 + white*0.0750759;
                b2 = 0.96900*b2 + white*0.1538520;
                b3 = 0.86650*b3 + white*0.3104856;
                b4 = 0.55000*b4 + white*0.5329522;
                b5 = -0.7616*b5 - white*0.0168980;
                const pink = (b0+b1+b2+b3+b4+b5+b6 + white*0.5362) * 0.11;
                b6 = white * 0.115926;
                // Fast-attack, exponential-decay envelope
                const t = i / numFrames;
                const env = Math.exp(-t * 22) * (1 - Math.exp(-t * 90));
                // Stereo spread
                data[i] = pink * env * (ch === 0 ? 1.0 : 0.85);
            }
        }

        const src  = ctx.createBufferSource();
        src.buffer = buf;

        const bpf = ctx.createBiquadFilter();
        bpf.type = 'bandpass';
        bpf.frequency.value = 3400;
        bpf.Q.value         = 0.75;

        const hpf = ctx.createBiquadFilter();
        hpf.type = 'highpass';
        hpf.frequency.value = 1800;

        const gain = ctx.createGain();
        gain.gain.value = 0.5;

        src.connect(bpf);
        bpf.connect(hpf);
        hpf.connect(gain);
        gain.connect(ctx.destination);
        src.start();
    } catch(e) {}
}

// Sound toggle button
document.getElementById('soundToggle')?.addEventListener('click', function() {
    soundMuted = !soundMuted;
    this.querySelector('.sound-on').classList.toggle('d-none', soundMuted);
    this.querySelector('.sound-off').classList.toggle('d-none', !soundMuted);
    if (!soundMuted) { getAudioCtx()?.resume(); }
});

<?php if ($hasPdf): ?>
// ─── PDF + PageFlip Viewer ──────────────────────────────────────────────────
pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const loadText    = document.getElementById('pdfLoadText');
const progressBar = document.getElementById('pdfProgressBar');
const progressLbl = document.getElementById('pdfProgressLabel');
const loadingEl   = document.getElementById('pdfLoading');
const bookWrap    = document.getElementById('bookWrap');
const bookFooter  = document.getElementById('bookFooter');
const btnPrev     = document.getElementById('pdfPrev');
const btnNext     = document.getElementById('pdfNext');
const pageInfoEl  = document.getElementById('pageInfo');

let pageFlip     = null;
let totalPgs     = 0;
let currentPdfUrl = <?= json_encode($primaryPdfUrl) ?>;

async function initViewer(pdfUrl) {
    currentPdfUrl = pdfUrl;
    console.log('[PDF] initViewer() called → url:', pdfUrl);

    // Destroy previous PageFlip instance cleanly
    if (pageFlip) {
        console.log('[PDF] Destroying previous PageFlip instance...');
        try { pageFlip.destroy(); console.log('[PDF] destroy() OK'); }
        catch(destroyErr) { console.warn('[PDF] destroy() threw (non-fatal):', destroyErr); }
        pageFlip = null;
    }

    // Replace the flipBook div with a fresh element to clear any stale PageFlip DOM
    const pagesWrapEl = document.getElementById('bookPagesWrap');
    if (pagesWrapEl) {
        const oldBook = document.getElementById('flipBook');
        const freshBook = document.createElement('div');
        freshBook.id = 'flipBook';
        if (oldBook) {
            pagesWrapEl.replaceChild(freshBook, oldBook);
            console.log('[PDF] flipBook element replaced with fresh node');
        } else {
            pagesWrapEl.insertBefore(freshBook, pagesWrapEl.firstChild);
            console.warn('[PDF] flipBook was missing — inserted fresh node');
        }
    } else {
        console.error('[PDF] bookPagesWrap element not found! Aborting.');
        return;
    }

    // Reset UI to loading state
    if (loadingEl)   { loadingEl.style.display   = '';      }
    if (bookWrap)    { bookWrap.style.display     = 'none';  }
    if (bookFooter)  { bookFooter.style.display   = 'none';  }
    if (progressBar) { progressBar.style.width    = '0%';    }
    if (progressLbl) { progressLbl.textContent    = '';      }
    if (loadText)    { loadText.textContent       = 'Loading document…'; }
    if (btnPrev)     { btnPrev.disabled           = true;    }
    if (btnNext)     { btnNext.disabled           = true;    }

    try {
        console.log('[PDF] Calling pdfjsLib.getDocument() ...');
        const loadingTask = pdfjsLib.getDocument(pdfUrl);
        loadingTask.onProgress = function(data) {
            if (data.total && progressBar) {
                const pct = Math.round((data.loaded / data.total) * 40);
                progressBar.style.width = pct + '%';
            }
        };
        const pdf = await loadingTask.promise;
        totalPgs  = pdf.numPages;
        console.log('[PDF] PDF loaded ✓  total pages:', totalPgs);

        // Measure stage
        const stage  = document.querySelector('.book-stage');
        const stageW = stage ? (stage.clientWidth || window.innerWidth) : window.innerWidth;
        console.log('[PDF] Stage clientWidth:', stageW);

        const spreadAvail = Math.max(stageW - 64 - 148, 320);
        const pg1      = await pdf.getPage(1);
        const vp1      = pg1.getViewport({ scale: 1 });
        const PG_W     = Math.min(Math.floor(spreadAvail / 2), 640);
        const SCALE    = PG_W / vp1.width;
        const PG_H     = Math.round(vp1.height * SCALE);
        const SPREAD_W = PG_W * 2;
        console.log('[PDF] Dimensions — PG_W:', PG_W, ' PG_H:', PG_H, ' SCALE:', SCALE.toFixed(4), ' SPREAD_W:', SPREAD_W);

        if (stage)   stage.style.minHeight = (PG_H + 200) + 'px';
        if (bookWrap) bookWrap.style.height = (PG_H + 28)  + 'px';
        const pagesWrap = document.getElementById('bookPagesWrap');
        if (pagesWrap) {
            pagesWrap.style.width  = SPREAD_W + 'px';
            pagesWrap.style.height = PG_H     + 'px';
        }
        document.querySelectorAll('.book-binding').forEach(b => { b.style.width = SPREAD_W + 'px'; });

        if (loadText) loadText.textContent = 'Rendering pages…';

        // Render all pages to JPEG data URLs
        const dataUrls = [];
        for (let i = 1; i <= totalPgs; i++) {
            const page     = await pdf.getPage(i);
            const viewport = page.getViewport({ scale: SCALE });
            const canvas   = document.createElement('canvas');
            canvas.width   = viewport.width;
            canvas.height  = viewport.height;
            const ctx2d    = canvas.getContext('2d');
            ctx2d.fillStyle = '#fff';
            ctx2d.fillRect(0, 0, canvas.width, canvas.height);
            await page.render({ canvasContext: ctx2d, viewport }).promise;
            dataUrls.push(canvas.toDataURL('image/jpeg', 0.90));
            const pct = 40 + Math.round((i / totalPgs) * 60);
            if (progressBar) progressBar.style.width = pct + '%';
            if (progressLbl) progressLbl.textContent = 'Page ' + i + ' of ' + totalPgs;
        }
        console.log('[PDF] All pages rendered ✓  dataUrls.length:', dataUrls.length);

        // Show book
        if (loadingEl)  loadingEl.style.display  = 'none';
        if (bookWrap)   bookWrap.style.display    = 'flex';
        if (bookFooter) bookFooter.style.display  = 'block';

        // Init StPageFlip
        const currentBookEl = document.getElementById('flipBook');
        console.log('[PDF] Initialising St.PageFlip on element:', currentBookEl);
        if (!currentBookEl) {
            console.error('[PDF] flipBook element missing at PageFlip init — aborting.');
            return;
        }
        pageFlip = new St.PageFlip(currentBookEl, {
            width:               PG_W,
            height:              PG_H,
            size:                'fixed',
            drawShadow:          true,
            flippingTime:        750,
            usePortrait:         false,
            autoSize:            false,
            maxShadowOpacity:    0.7,
            showCover:           false,
            mobileScrollSupport: false,
            clickEventForward:   true,
            useMouseEvents:      true,
            swipeDistance:       10,
            startPage:           0,
        });
        console.log('[PDF] St.PageFlip created ✓');

        pageFlip.loadFromImages(dataUrls);
        console.log('[PDF] loadFromImages() called ✓');

        pageFlip.on('flip', function(e) {
            playFlipSound();
            updateInfo(e.data);
        });
        pageFlip.on('init', function() {
            console.log('[PDF] PageFlip init event ✓');
            updateInfo(0);
            if (btnPrev) btnPrev.disabled = false;
            if (btnNext) btnNext.disabled = false;
        });

    } catch (err) {
        console.error('[PDF] ✗ initViewer error:', err.name, err.message, err);
        if (loadingEl) loadingEl.innerHTML =
            '<div class="text-danger fw-semibold fs-7 text-center p-5">' +
            '<i class="ki-duotone ki-cross-circle fs-2x text-danger mb-3"><span class="path1"></span><span class="path2"></span></i>' +
            '<div class="mb-2">Failed to load PDF</div>' +
            '<div class="text-muted fs-9 mb-4 font-monospace">' + err.name + ': ' + err.message + '</div>' +
            '<a href="' + pdfUrl + '" target="_blank" class="btn btn-sm btn-warning">Open file directly ↗</a></div>';
    }
}

function updateInfo(pageIndex) {
    if (!pageFlip) return;
    const total = pageFlip.getPageCount();
    const left  = pageIndex + 1;
    const right = Math.min(pageIndex + 2, total);
    if (pageInfoEl) pageInfoEl.textContent = left === right
        ? 'Page ' + left + ' of ' + total
        : 'Pages ' + left + ' – ' + right + ' of ' + total;
    if (btnPrev) btnPrev.disabled = pageIndex <= 0;
    if (btnNext) btnNext.disabled = pageIndex >= total - 2;
}

function switchPDF(url) {
    console.log('[PDF] switchPDF() →', url);
    document.querySelectorAll('.pdf-switcher').forEach(function(a) {
        const badge = a.querySelector('.pdf-current-badge');
        if (a.dataset.url === url) {
            if (!badge) {
                const b = document.createElement('span');
                b.className = 'badge badge-light-success ms-auto fs-9 pdf-current-badge';
                b.textContent = 'Current';
                a.appendChild(b);
            }
        } else {
            if (badge) badge.remove();
        }
    });
    document.querySelector('.book-stage')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    initViewer(url);
}

// Button nav
if (btnPrev) btnPrev.addEventListener('click', function() { if (pageFlip) pageFlip.flipPrev(); });
if (btnNext) btnNext.addEventListener('click', function() { if (pageFlip) pageFlip.flipNext(); });

// Keyboard nav
document.addEventListener('keydown', function(e) {
    if (!pageFlip) return;
    if (e.key === 'ArrowRight' || e.key === 'PageDown') pageFlip.flipNext();
    if (e.key === 'ArrowLeft'  || e.key === 'PageUp')   pageFlip.flipPrev();
});

// Fullscreen
(function() {
    const btnFs     = document.getElementById('btnFullscreen');
    const fsCard    = btnFs ? btnFs.closest('.card') : null;
    const iconEnter = document.querySelector('.fs-icon-enter');
    const iconExit  = document.querySelector('.fs-icon-exit');

    if (!btnFs || !fsCard) return;

    function enterFs() {
        const req = fsCard.requestFullscreen || fsCard.webkitRequestFullscreen || fsCard.mozRequestFullScreen;
        if (req) req.call(fsCard).catch(function(err) {
            console.warn('[PDF] Fullscreen request failed:', err.message);
        });
    }
    function exitFs() {
        const ex = document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen;
        if (ex) ex.call(document);
    }

    btnFs.addEventListener('click', function() {
        const isFs = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
        isFs ? exitFs() : enterFs();
    });

    function onFsChange() {
        const isFs = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
        if (iconEnter) iconEnter.classList.toggle('d-none', isFs);
        if (iconExit)  iconExit.classList.toggle('d-none', !isFs);
        btnFs.title = isFs ? 'Exit full screen' : 'Full screen';
        // Re-render the flipbook at the new stage dimensions
        if (currentPdfUrl) {
            setTimeout(function() { initViewer(currentPdfUrl); }, 200);
        }
    }
    document.addEventListener('fullscreenchange',       onFsChange);
    document.addEventListener('webkitfullscreenchange', onFsChange);
    document.addEventListener('mozfullscreenchange',    onFsChange);
})();

// pdf-switcher — event delegation so it works inside Bootstrap dropdowns
document.addEventListener('click', function(e) {
    const el = e.target.closest('.pdf-switcher');
    if (!el) return;
    console.log('[PDF] pdf-switcher clicked — url:', el.dataset.url, ' el:', el);
    e.preventDefault();
    switchPDF(el.dataset.url);
});

// Initial load
console.log('[PDF] Starting initial load →', <?= json_encode($primaryPdfUrl) ?>);
initViewer(<?= json_encode($primaryPdfUrl) ?>);
<?php endif; ?>

// ─── Submission Upload ──────────────────────────────────────────────────────
const fileInput = document.getElementById('submissionFile');
const dropArea  = document.getElementById('dropzoneArea');
const prompt    = document.getElementById('dropzonePrompt');
const selected  = document.getElementById('dropzoneSelected');
const nameEl    = document.getElementById('selectedFileName');
const sizeEl    = document.getElementById('selectedFileSize');
const clearBtn  = document.getElementById('btnClearFile');
const submitBtn = document.getElementById('btnSubmitWork');

if (fileInput) {
    const ALLOWED = ['pdf','zip','rar'];

    function fmtSize(b) {
        return b < 1024 ? b + ' B'
             : b < 1048576 ? (b/1024).toFixed(1) + ' KB'
             : (b/1048576).toFixed(1) + ' MB';
    }

    function showFile(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        if (!ALLOWED.includes(ext)) {
            Swal.fire({ title: 'Invalid file type', text: 'Please upload a PDF, ZIP, or RAR.', icon: 'warning',
                buttonsStyling: false, confirmButtonText: 'OK',
                customClass: { confirmButton: 'btn btn-warning' } });
            fileInput.value = '';
            return;
        }
        nameEl.textContent = file.name;
        sizeEl.textContent = fmtSize(file.size);
        prompt.classList.add('d-none');
        selected.classList.remove('d-none');
    }

    fileInput.addEventListener('change', function() { if (this.files[0]) showFile(this.files[0]); });

    dropArea.addEventListener('dragover',  e => { e.preventDefault(); dropArea.classList.add('dz-hover'); });
    dropArea.addEventListener('dragleave', ()=> dropArea.classList.remove('dz-hover'));
    dropArea.addEventListener('drop', e => {
        e.preventDefault(); dropArea.classList.remove('dz-hover');
        if (e.dataTransfer.files[0]) { fileInput.files = e.dataTransfer.files; showFile(e.dataTransfer.files[0]); }
    });

    clearBtn?.addEventListener('click', function() {
        fileInput.value = '';
        prompt.classList.remove('d-none');
        selected.classList.add('d-none');
    });

    submitBtn?.addEventListener('click', function() {
        if (!fileInput?.files[0]) {
            Swal.fire({ title: 'No file selected', text: 'Please choose a file.', icon: 'warning',
                buttonsStyling: false, confirmButtonText: 'OK',
                customClass: { confirmButton: 'btn btn-warning' } });
            return;
        }
        Swal.fire({
            title: 'Submit Assignment?',
            text:  'Your work will be submitted for review.',
            icon:  'question', showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: 'Yes, Submit',
            customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-light' }
        }).then(r => {
            if (!r.isConfirmed) return;
            submitBtn.setAttribute('data-kt-indicator', 'on');
            submitBtn.disabled = true;
            const fd = new FormData();
            fd.append('submission_file', fileInput.files[0]);
            $.ajax({
                url:         '<?= base_url('classroom/student/' . $classSubId . '/assignment/' . $assignment['assignment_id'] . '/submit') ?>',
                type:        'POST',
                data:        fd,
                processData: false,
                contentType: false,
                success: function(res) {
                    submitBtn.removeAttribute('data-kt-indicator');
                    submitBtn.disabled = false;
                    if (res.success) {
                        Swal.fire({
                            title: res.status === 'Late' ? 'Submitted (Late)' : 'Submitted!',
                            text:  res.status === 'Late' ? 'Submitted after the due date.' : 'Your work was submitted successfully.',
                            icon:  'success', timer: 2200, showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                            buttonsStyling: false, confirmButtonText: 'Close',
                            customClass: { confirmButton: 'btn btn-danger' } });
                    }
                },
                error: function() {
                    submitBtn.removeAttribute('data-kt-indicator');
                    submitBtn.disabled = false;
                    Swal.fire({ title: 'Error', text: 'Upload failed. Try again.', icon: 'error',
                        buttonsStyling: false, confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' } });
                }
            });
        });
    });
}

// Bootstrap tooltips for file grid items
if (window.bootstrap && window.bootstrap.Tooltip) {
    document.querySelectorAll('.asgn-file-grid-item[data-bs-toggle="tooltip"]').forEach(function(el) {
        new bootstrap.Tooltip(el, { trigger: 'hover', delay: { show: 150, hide: 0 } });
    });
}

})();
</script>
<!--end::Scripts-->

<style>
/* ══════════════════════════════════════════════════════
   BOOK STAGE — dark reading surface
══════════════════════════════════════════════════════ */
.book-stage {
    position: relative;
    background: linear-gradient(160deg, #0d1b2a 0%, #1a2744 50%, #0a1628 100%);
    padding: 44px 16px 36px;   /* minimal side padding — book uses full width */
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    box-sizing: border-box;
}
/* Desk surface gradient at bottom */
.book-stage::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 56px;
    background: linear-gradient(to top, rgba(0,0,0,.5), transparent);
    pointer-events: none;
}
/* Ambient glow blobs */
.book-glow {
    position: absolute;
    width: 420px; height: 420px;
    border-radius: 50%;
    filter: blur(100px);
    pointer-events: none;
    opacity: .15;
}
.book-glow-l { top: -100px; left: -80px;   background: radial-gradient(circle, #3b82f6, transparent 70%); }
.book-glow-r { bottom: -80px; right: -60px; background: radial-gradient(circle, #7c3aed, transparent 70%); }

/* ══ Loading ══ */
.book-loading {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    min-height: 340px; width: 100%; padding: 40px;
}
.book-loading-icon { animation: bookPulse 2s ease-in-out infinite; }
@keyframes bookPulse {
    0%,100% { transform: scale(1);    opacity: .45; }
    50%      { transform: scale(1.08); opacity: .9; }
}
.progress-track {
    width: 280px; height: 7px;
    background: rgba(255,255,255,.1);
    border-radius: 99px; overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #7c3aed);
    border-radius: 99px;
    transition: width .3s ease;
}

/* ══ Book wrapper ══ */
#bookWrap {
    position: relative; z-index: 2;
    display: flex; align-items: center; justify-content: center;
    gap: 22px; width: 100%;
}

/* ══ Flip book container — drop-shadow on the whole book shell ══ */
.flipbook-container {
    flex-shrink: 0;
    filter:
        drop-shadow(0 32px 64px rgba(0,0,0,.85))
        drop-shadow(0 8px 20px rgba(0,0,0,.6))
        drop-shadow(0 0 2px rgba(0,0,0,.9));
}

/* ══════════════════════════════════════════════════════
   OPEN BOOK SHELL
══════════════════════════════════════════════════════ */
.book-outer {
    display: flex;
    flex-direction: column;
    border-radius: 4px;
    overflow: visible;
}

/* ── Hardcover binding strips (top & bottom) ── */
.book-binding {
    display: flex;
    height: 14px;
    position: relative;
    z-index: 4;
}
.book-binding-top    { border-radius: 5px 5px 0 0; }
.book-binding-bottom { border-radius: 0 0 5px 5px; }

.book-binding-left,
.book-binding-right {
    flex: 1;
    background: linear-gradient(180deg, #4a2c14 0%, #6b3d1e 40%, #3a2010 100%);
}
.book-binding-top .book-binding-left  { border-radius: 5px 0 0 0; }
.book-binding-top .book-binding-right { border-radius: 0 5px 0 0; }
.book-binding-bottom .book-binding-left  { border-radius: 0 0 0 5px; }
.book-binding-bottom .book-binding-right { border-radius: 0 0 5px 0; }

/* Spine notch in the binding (center divot where spine meets cover) */
.book-binding-spine-notch {
    width: 22px;
    flex-shrink: 0;
    background: linear-gradient(180deg, #1a0d06 0%, #2e1a0a 50%, #1a0d06 100%);
    box-shadow: inset 0 2px 4px rgba(0,0,0,.6), inset 0 -2px 4px rgba(0,0,0,.6);
}

/* ── Pages area ── */
.book-pages-wrap {
    position: relative;
    display: flex;
    overflow: visible;
}

/* ── Center spine overlay ── */
.book-spine-overlay {
    position: absolute;
    top: 0; bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 18px;
    pointer-events: none;
    z-index: 5;
    background: linear-gradient(to right,
        rgba(0,0,0,.45)  0%,
        rgba(0,0,0,.18)  30%,
        rgba(255,255,255,.04) 50%,
        rgba(0,0,0,.18)  70%,
        rgba(0,0,0,.45)  100%
    );
}
/* Inner glow lines on spine */
.book-spine-overlay::before,
.book-spine-overlay::after {
    content: '';
    position: absolute;
    top: 0; bottom: 0;
    width: 1px;
    background: rgba(255,255,255,.06);
}
.book-spine-overlay::before { left: 4px; }
.book-spine-overlay::after  { right: 4px; }

/* ── Left / right page outer-edge shading ── */
.book-page-edge {
    position: absolute;
    top: 0; bottom: 0;
    width: 12px;
    pointer-events: none;
    z-index: 4;
}
.book-page-edge-left {
    left: 0;
    background: linear-gradient(to right, rgba(0,0,0,.25), transparent);
}
.book-page-edge-right {
    right: 0;
    background: linear-gradient(to left, rgba(0,0,0,.25), transparent);
}

/* ══ Side navigation arrows ══ */
.side-nav-btn {
    width: 52px; height: 52px;
    border-radius: 50%; border: none;
    background: rgba(255,255,255,.09);
    backdrop-filter: blur(10px);
    color: rgba(255,255,255,.7);
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0;
    transition: background .2s, color .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 2px 14px rgba(0,0,0,.35);
}
.side-nav-btn:hover:not(:disabled) {
    background: rgba(255,255,255,.2);
    color: #fff;
    transform: scale(1.1);
    box-shadow: 0 6px 22px rgba(0,0,0,.55);
}
.side-nav-btn:disabled { opacity: .18; cursor: default; }

/* ══ Footer / page counter ══ */
.book-footer {
    position: relative; z-index: 2;
    text-align: center; padding-top: 22px;
}
.page-counter {
    display: inline-block;
    background: rgba(255,255,255,.09);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.11);
    border-radius: 99px;
    padding: 5px 22px;
    font-size: .78rem; font-weight: 600;
    color: rgba(255,255,255,.82);
    margin-bottom: 8px; letter-spacing: .3px;
}
.key-hint { font-size: .71rem; color: rgba(255,255,255,.3); letter-spacing: .2px; }

/* ══ Dropzone ══ */
.dropzone-area {
    border: 2px dashed #c4c4d4;
    border-radius: 14px;
    background: #fafafa;
    cursor: pointer;
    transition: border-color .2s, background .2s;
}
.dropzone-area:hover, .dropzone-area.dz-hover {
    border-color: var(--bs-primary);
    background: #f0f4ff;
}

/* ══ Assignment file grid (8 per row) ══ */
.asgn-file-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    overflow: visible;
}
.asgn-file-grid-item {
    flex: 0 0 calc(12.5% - 7px);
    min-width: 78px;
    position: relative;
}
@media (max-width: 767px) {
    .asgn-file-grid-item { flex: 0 0 calc(16.667% - 7px); min-width: 68px; }
}
@media (max-width: 480px) {
    .asgn-file-grid-item { flex: 0 0 calc(25% - 6px); min-width: 60px; }
}


/* ══ File card hover — dark background ══ */
.file-card-link > div {
    transition: background .18s, border-color .18s, transform .15s, box-shadow .15s;
}
.file-card-link:hover > div {
    background: #1e293b !important;
    border-color: #334155 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,.3);
}
.file-card-link:hover > div i[class*="ki-"] {
    color: #94a3b8 !important;
}
.file-card-link:hover > div .badge {
    background: rgba(255,255,255,.1) !important;
    color: #cbd5e1 !important;
    border-color: transparent !important;
}
.file-card-link:hover > div .asgn-file-name {
    color: #64748b !important;
}

/* ══ Filename label inside file card ══ */
.asgn-file-name {
    font-size: .58rem;
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding: 0 6px;
    line-height: 1.2;
}

/* ══ Fullscreen flipbook ══ */
.card:fullscreen,
.card:-webkit-full-screen,
.card:-moz-full-screen {
    border-radius: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    overflow: hidden !important;
}
.card:fullscreen .card-header,
.card:-webkit-full-screen .card-header,
.card:-moz-full-screen .card-header {
    background: #0f172a !important;
    border-bottom: 1px solid rgba(255,255,255,.1) !important;
    flex-shrink: 0;
}
.card:fullscreen .card-header *,
.card:-webkit-full-screen .card-header * {
    color: rgba(255,255,255,.85) !important;
}
.card:fullscreen .book-stage,
.card:-webkit-full-screen .book-stage,
.card:-moz-full-screen .book-stage {
    flex: 1 !important;
    min-height: 0 !important;
    height: 100% !important;
}

/* ══ Responsive ══ */
@media (max-width: 640px) {
    .side-nav-btn { width: 38px; height: 38px; }
    #bookWrap { gap: 10px; }
    .book-stage { padding: 28px 10px 24px; }
    .book-binding { height: 10px; }
}
</style>
