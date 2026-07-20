<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($lesson['lesson_title']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/student/' . $classSubId . '/lessons') ?>" class="text-muted text-hover-primary">Lessons</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($lesson['lesson_title']) ?></li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/student/' . $classSubId . '/lessons') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back to Lessons
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php
    $statusColor = 'success'; // students only see Published lessons
    function stu_lessonFileColor(string $ext): string {
        return match($ext) {
            'pdf' => 'danger', 'doc','docx' => 'primary', 'xls','xlsx' => 'success',
            'ppt','pptx' => 'warning', 'zip','rar' => 'dark',
            'jpg','jpeg','png','gif','webp','svg' => 'info', default => 'secondary',
        };
    }
    function stu_lessonFileLabel(string $ext): string { return $ext === 'jpeg' ? 'JPG' : strtoupper($ext); }
    function stu_lessonFileSize(int $bytes): string {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
    function stu_timeAgo(string $datetime): string {
        $diff = time() - strtotime($datetime);
        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . ' min ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
        if ($diff < 604800) { $d = floor($diff / 86400); return $d . ' day' . ($d > 1 ? 's' : '') . ' ago'; }
        return date('M j, Y', strtotime($datetime));
    }
    function stu_youtubeId(string $url): string {
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m);
        return $m[1] ?? '';
    }
    $imageExts = ['jpg','jpeg','png','gif','webp','svg'];
    $totalDiscussions = count($discussions ?? []);
    $totalAssessments = count($quizzes ?? []);
    $aTypeCfgMap = [
        'quiz'       => ['label' => 'Quiz',        'icon' => 'ki-questionnaire-tablet', 'color' => 'success'],
        'drag_drop'  => ['label' => 'Drag & Drop',  'icon' => 'ki-abstract-26',          'color' => 'primary'],
        'labelling'  => ['label' => 'Labelling',    'icon' => 'ki-tag',                  'color' => 'info'],
        'simulation' => ['label' => 'Simulation',   'icon' => 'ki-rocket',               'color' => 'danger'],
    ];
    ?>

    <!--begin::Lesson info card-->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body p-5">
            <div class="d-flex align-items-start justify-content-between mb-4">
                <div>
                    <span class="badge badge-light-primary fs-9 mb-2">Lesson <?= esc($lesson['lesson_order']) ?></span>
                    <div class="fw-bold text-gray-900 fs-5 lh-sm"><?= esc($lesson['lesson_title']) ?></div>
                </div>
                <span class="badge badge-light-success fs-9 flex-shrink-0 ms-2">Published</span>
            </div>
            <?php if ($lesson['lesson_desc']): ?>
            <p class="text-muted fs-7 mb-4"><?= nl2br(esc($lesson['lesson_desc'])) ?></p>
            <?php endif; ?>
            <div class="info-row-border py-2 d-flex justify-content-between">
                <span class="text-muted fs-8">Subject</span>
                <span class="fw-semibold text-gray-800 fs-8"><?= esc($lesson['subject_name']) ?></span>
            </div>
            <div class="info-row-border py-2 d-flex justify-content-between">
                <span class="text-muted fs-8">Year Level</span>
                <span class="fw-semibold text-gray-800 fs-8"><?= esc($lesson['level_name'] ?? '—') ?></span>
            </div>
            <div class="py-2 d-flex justify-content-between">
                <span class="text-muted fs-8">Term</span>
                <span class="fw-semibold text-gray-800 fs-8">Term <?= esc($lesson['lesson_term']) ?></span>
            </div>
        </div>
    </div>
    <!--end::Lesson info card-->

    <!--begin::Files Videos Links card-->
    <div class="card shadow-sm mt-4">
        <div class="card-body p-0">

            <!--begin::Files-->
            <div class="p-6">
                <div class="mb-4">
                    <h6 class="fw-bold text-gray-800 mb-0">
                        <i class="ki-duotone ki-file-up fs-4 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                        Lesson Files
                    </h6>
                    <span class="text-muted fs-9"><?= count($lesson['files'] ?? []) ?> file<?= count($lesson['files'] ?? []) !== 1 ? 's' : '' ?></span>
                </div>
                <?php if (empty($lesson['files'])): ?>
                <div class="text-center py-6 text-muted">
                    <i class="ki-duotone ki-file fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                    <div class="fs-8">No files attached to this lesson.</div>
                </div>
                <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($lesson['files'] as $f):
                        $ext   = strtolower($f['file_type'] ?? '');
                        $color = stu_lessonFileColor($ext);
                        $label = stu_lessonFileLabel($ext);
                        $isImg = in_array($ext, $imageExts);
                    ?>
                    <div class="col-6 col-sm-4 col-md-2">
                        <div class="media-card border border-gray-100 rounded-2 overflow-hidden">
                            <a href="<?= base_url('uploads/lesson_files/' . $f['file_path']) ?>" target="_blank" class="d-block media-thumb">
                                <?php if ($isImg): ?>
                                <img src="<?= base_url('uploads/lesson_files/' . $f['file_path']) ?>"
                                     alt="<?= esc($f['file_name']) ?>" style="width:100%;height:110px;object-fit:cover;display:block;">
                                <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light-<?= $color ?>" style="height:110px;">
                                    <span class="fw-bold text-<?= $color ?> fs-2"><?= $label ?></span>
                                </div>
                                <?php endif; ?>
                            </a>
                            <div class="p-2">
                                <div class="text-truncate fw-semibold text-gray-800 fs-9" title="<?= esc($f['file_name']) ?>"><?= esc($f['file_name']) ?></div>
                                <span class="badge bg-light-<?= $color ?> text-<?= $color ?> fs-10 px-1"><?= $label ?></span>
                                <span class="text-muted fs-10 ms-1"><?= stu_lessonFileSize((int) $f['file_size']) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <!--end::Files-->

            <hr class="m-0">

            <!--begin::Videos-->
            <div class="p-6">
                <div class="mb-4">
                    <h6 class="fw-bold text-gray-800 mb-0">
                        <i class="ki-duotone ki-video fs-4 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>
                        Lesson Videos
                    </h6>
                    <span class="text-muted fs-9"><?= count($lesson['videos'] ?? []) ?> video<?= count($lesson['videos'] ?? []) !== 1 ? 's' : '' ?></span>
                </div>
                <?php if (empty($lesson['videos'])): ?>
                <div class="text-center py-6 text-muted">
                    <i class="ki-duotone ki-video fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                    <div class="fs-8">No videos attached to this lesson.</div>
                </div>
                <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($lesson['videos'] as $v):
                        $ytId  = stu_youtubeId($v['video_url']);
                        $thumb = $ytId ? 'https://img.youtube.com/vi/' . $ytId . '/mqdefault.jpg' : '';
                        $label = esc($v['video_title'] ?: $v['video_url']);
                    ?>
                    <div class="col-6 col-sm-4 col-md-2">
                        <div class="media-card border border-gray-100 rounded-2 overflow-hidden">
                            <a href="<?= esc($v['video_url']) ?>" target="_blank" class="d-block media-thumb position-relative">
                                <?php if ($thumb): ?>
                                <img src="<?= $thumb ?>" alt="<?= $label ?>" style="width:100%;height:110px;object-fit:cover;display:block;">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <div class="bg-dark bg-opacity-50 rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                        <i class="ki-duotone ki-triangle fs-5 text-white ms-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light-danger" style="height:110px;">
                                    <i class="ki-duotone ki-video fs-2x text-danger"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <?php endif; ?>
                            </a>
                            <div class="p-2">
                                <div class="text-truncate fw-semibold text-gray-800 fs-9" title="<?= esc($v['video_title'] ?: $v['video_url']) ?>"><?= $label ?></div>
                                <?php if ($ytId): ?>
                                <span class="badge bg-light-danger text-danger fs-10 px-1">YouTube</span>
                                <?php else: ?>
                                <span class="badge bg-light-secondary text-muted fs-10 px-1">Video</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <!--end::Videos-->

            <hr class="m-0">

            <!--begin::Links-->
            <div class="p-6">
                <div class="mb-4">
                    <h6 class="fw-bold text-gray-800 mb-0">
                        <i class="ki-duotone ki-link fs-4 text-info me-2"><span class="path1"></span><span class="path2"></span></i>
                        Lesson Referrals
                    </h6>
                    <span class="text-muted fs-9"><?= count($lesson['links'] ?? []) ?> referral<?= count($lesson['links'] ?? []) !== 1 ? 's' : '' ?></span>
                </div>
                <?php if (empty($lesson['links'])): ?>
                <div class="text-center py-6 text-muted">
                    <i class="ki-duotone ki-link fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                    <div class="fs-8">No referrals attached to this lesson.</div>
                </div>
                <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($lesson['links'] as $lnk):
                        $domain = parse_url($lnk['link_url'], PHP_URL_HOST) ?: $lnk['link_url'];
                    ?>
                    <div class="col-6 col-sm-4 col-md-2">
                        <div class="media-card border border-gray-100 rounded-2 overflow-hidden">
                            <a href="<?= esc($lnk['link_url']) ?>" target="_blank" class="d-block media-thumb">
                                <div class="d-flex align-items-center justify-content-center bg-light-info" style="height:110px;">
                                    <img src="https://www.google.com/s2/favicons?domain=<?= urlencode($lnk['link_url']) ?>&sz=64"
                                         style="width:40px;height:40px;object-fit:contain;"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <i class="ki-duotone ki-link fs-2x text-info" style="display:none;"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                            </a>
                            <div class="p-2">
                                <div class="text-truncate fw-semibold text-gray-800 fs-9" title="<?= esc($lnk['link_title'] ?: $lnk['link_url']) ?>"><?= esc($lnk['link_title'] ?: $domain) ?></div>
                                <span class="text-muted fs-10 text-truncate d-block"><?= esc($domain) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <!--end::Links-->

        </div>
    </div>
    <!--end::Files Videos Links card-->

    <!--begin::Assessments card-->
    <div class="card border-0 shadow-sm mt-6">
        <div class="card-header border-0 pt-5 pb-0 d-flex align-items-center gap-2">
            <i class="ki-duotone ki-element-plus fs-3 text-primary">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
            </i>
            <div>
                <h6 class="fw-bold text-gray-800 mb-0">Assessments</h6>
                <span class="text-muted fs-9"><?= $totalAssessments ?> assessment<?= $totalAssessments !== 1 ? 's' : '' ?></span>
            </div>
        </div>
        <div class="card-body pt-4 pb-6">
            <?php if (empty($quizzes)): ?>
            <div class="text-center py-8 text-muted">
                <i class="ki-duotone ki-element-plus fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                <div class="fs-8">No assessments for this lesson yet.</div>
            </div>
            <?php else: ?>
            <div class="row g-3">
                <?php foreach ($quizzes as $quiz):
                    $qId      = (int) $quiz['lesson_quizze_id'];
                    $aType    = $quiz['assessment_type'] ?? 'quiz';
                    $aCfg     = $aTypeCfgMap[$aType] ?? $aTypeCfgMap['quiz'];
                    $qCount   = count($quiz['questions']);

                    if ($aType === 'drag_drop') {
                        $qAttempt      = $ddAttempts[$qId] ?? null;
                        $isAttempted   = $qAttempt && $qAttempt['status'] === 'submitted';
                        $takeUrl       = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/dragdrop/' . $qId . '/take');
                        $scoreUrl      = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/dragdrop/' . $qId . '/score');
                        $transcriptUrl = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/dragdrop/' . $qId . '/transcript');
                    } elseif ($aType === 'labelling') {
                        $qAttempt      = $labelAttempts[$qId] ?? null;
                        $isAttempted   = $qAttempt && $qAttempt['status'] === 'submitted';
                        $takeUrl       = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/label/' . $qId . '/take');
                        $scoreUrl      = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/label/' . $qId . '/score');
                        $transcriptUrl = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/label/' . $qId . '/transcript');
                    } else {
                        $qAttempt      = $quizAttempts[$qId] ?? null;
                        $isAttempted   = $qAttempt && in_array($qAttempt['status'], ['submitted', 'timed_out']);
                        $takeUrl       = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/quiz/' . $qId . '/take');
                        $scoreUrl      = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/quiz/' . $qId . '/score');
                        $transcriptUrl = base_url('classroom/student/' . $classSubId . '/lesson/' . $lesson['lesson_id'] . '/quiz/' . $qId . '/transcript');
                    }

                    if ($isAttempted) {
                        $qScore    = (float) $qAttempt['score'];
                        $iconColor = $qScore >= 70 ? 'success' : ($qScore >= 50 ? 'info' : 'danger');
                    } else {
                        $iconColor = $aCfg['color'];
                    }
                ?>
                <div class="col-6 col-sm-4 col-md-3">
                    <div class="media-card border border-gray-100 rounded-2 position-relative" style="overflow:visible;">
                        <!--begin::3-dot menu-->
                        <div class="position-absolute" style="top:7px;right:7px;z-index:10;">
                            <div class="dropdown">
                                <button class="btn btn-icon bg-white bg-opacity-95 border border-gray-300 shadow-sm rounded-2"
                                        style="width:30px;height:30px;"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ki-duotone ki-down fs-5 text-gray-700"><span class="path1"></span></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end fs-8 py-2" style="min-width:175px;">
                                    <?php if ($quiz['quizze_status'] === 'Published'): ?>
                                    <?php
                                    $aInfo = match($aType) {
                                        'drag_drop'  => 'Match items to their correct drop zones',
                                        'labelling'  => 'Label the numbered markers on the image',
                                        default      => $qCount . ' question' . ($qCount !== 1 ? 's' : ''),
                                    };
                                    ?>
                                    <?php if ($isAttempted || ($fullAccess ?? false)): ?>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 btn-take-assessment"
                                           href="javascript:void(0)"
                                           data-atype="<?= esc($aType, 'attr') ?>"
                                           data-name="<?= esc($quiz['quizze_name'], 'attr') ?>"
                                           data-duration="<?= (int) $quiz['quizze_duration'] ?>"
                                           data-info="<?= esc($aInfo, 'attr') ?>"
                                           data-is-attempted="<?= $isAttempted ? '1' : '0' ?>"
                                           data-take-url="<?= $takeUrl ?>"
                                           data-score-url="<?= $scoreUrl ?>">
                                            <i class="ki-duotone ki-play fs-5 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                            Take Assessment
                                        </a>
                                    </li>
                                    <?php else: ?>
                                    <li>
                                        <span class="dropdown-item text-muted d-flex align-items-center gap-2 py-2">
                                            <i class="ki-duotone ki-lock fs-5 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                            View only — access restricted
                                        </span>
                                    </li>
                                    <?php endif; ?>
                                    <?php if ($isAttempted): ?>
                                    <li><div class="dropdown-divider my-1"></div></li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= $scoreUrl ?>">
                                            <i class="ki-duotone ki-chart-pie-4 fs-5 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                            View Score
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= $transcriptUrl ?>" target="_blank">
                                            <i class="ki-duotone ki-file-down fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
                                            Download Transcript
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <li>
                                        <span class="dropdown-item text-muted d-flex align-items-center gap-2 py-2">
                                            <i class="ki-duotone ki-lock fs-5 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                            Not yet available
                                        </span>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <!--end::3-dot menu-->
                        <!--begin::Assessment icon area-->
                        <div class="d-flex align-items-center justify-content-center bg-light-<?= $iconColor ?> position-relative"
                             style="height:118px; border-radius:.375rem .375rem 0 0; flex-direction:column; gap:6px;">
                            <i class="ki-duotone <?= $aCfg['icon'] ?> fs-2x text-<?= $iconColor ?>">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <?php if ($isAttempted): ?>
                            <div class="w-100 px-0 position-absolute bottom-0 start-0">
                                <?php $qScorePct = min(100, max(0, (float) $qAttempt['score'])); ?>
                                <div style="height:5px;background:rgba(0,0,0,.08);border-radius:0;">
                                    <div style="height:5px;width:<?= $qScorePct ?>%;background:var(--bs-<?= $iconColor ?>);border-radius:0 0 0 .375rem;transition:width .4s ease;"></div>
                                </div>
                                <div class="ps-2 pb-1 pt-1">
                                    <span class="badge badge-light-<?= $iconColor ?> fs-10">
                                        Done &nbsp;<?= number_format($qScorePct, 1) ?>%
                                    </span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <!--end::Assessment icon area-->
                        <div class="p-2">
                            <div class="text-truncate fw-semibold text-gray-800 fs-9" title="<?= esc($quiz['quizze_name']) ?>"><?= esc($quiz['quizze_name']) ?></div>
                            <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                                <span class="badge bg-light-<?= $aCfg['color'] ?> text-<?= $aCfg['color'] ?> fs-10 px-1"><?= $aCfg['label'] ?></span>
                                <?php if ($aType === 'quiz' && $qCount > 0): ?>
                                <span class="text-muted fs-10"><?= $qCount ?> Q</span>
                                <?php endif; ?>
                                <?php if ($quiz['quizze_duration'] > 0): ?>
                                <span class="text-muted fs-10"><?= $quiz['quizze_duration'] ?>min</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!--end::Assessments card-->

    <!--begin::Discussion card-->
    <div class="card border-0 shadow-sm mt-6 mb-6">
        <div class="card-header border-0 pt-5 pb-0 d-flex align-items-center gap-2">
            <i class="ki-duotone ki-message-edit fs-3 text-warning"><span class="path1"></span><span class="path2"></span></i>
            <div>
                <h6 class="fw-bold text-gray-800 mb-0">Lesson Discussion</h6>
                <span class="text-muted fs-9" id="disc_total_label"><?= $totalDiscussions ?> post<?= $totalDiscussions !== 1 ? 's' : '' ?></span>
            </div>
        </div>
        <div class="card-body pt-4 pb-6">

            <!--begin::Post form-->
            <?php if ($fullAccess ?? false): ?>
            <div class="card card-flush mb-8 border border-dashed border-gray-200">
                <div class="card-header justify-content-start align-items-center pt-4 pb-0 border-0">
                    <div class="symbol symbol-40px me-4 flex-shrink-0">
                        <?php if ($sessionPhotoUrl): ?>
                        <img src="<?= $sessionPhotoUrl ?>" class="rounded-circle" style="object-fit:cover;width:40px;height:40px;" alt="" />
                        <?php else: ?>
                        <div class="symbol-label bg-light-primary fw-bold text-primary fs-5"><?= strtoupper(substr($sessionFname, 0, 1)) ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="text-muted fw-semibold fs-7">What's on your mind, <?= esc($sessionFname) ?>?</span>
                </div>
                <div class="card-body pt-3 pb-2">
                    <textarea class="form-control bg-transparent border-0 px-0" id="disc_post_input" rows="2" placeholder="Share something with the class..."></textarea>
                </div>
                <div class="card-footer d-flex justify-content-end pt-0 border-0 pb-4">
                    <button type="button" class="btn btn-sm btn-primary" id="btn_post_discussion">
                        <span class="indicator-label"><i class="ki-duotone ki-send fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Post</span>
                        <span class="indicator-progress">Posting... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                    </button>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-light-warning d-flex align-items-center gap-2 mb-8 py-3">
                <i class="ki-duotone ki-lock fs-3 text-warning"><span class="path1"></span><span class="path2"></span></i>
                <span class="fs-7 fw-semibold text-gray-700">Your access to this classroom is view-only. You can no longer post in discussions.</span>
            </div>
            <?php endif; ?>
            <!--end::Post form-->

            <!--begin::Discussions list-->
            <div id="discussions_list">
            <?php if (empty($discussions)): ?>
            <div class="text-center py-12 text-muted" id="disc_empty_state">
                <i class="ki-duotone ki-message-edit fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
                <div class="fs-6 fw-semibold mb-1">No posts yet</div>
                <div class="fs-8">Be the first to start a discussion.</div>
            </div>
            <?php else: ?>
            <?php $letters = ['A','B','C','D']; ?>
            <?php foreach ($discussions as $disc):
                $discId       = (int) $disc['lesson_discussion_id'];
                $isOwn        = (int) $disc['author_id'] === (int) $sessionUserId;
                $userReaction = $disc['user_reaction'] ?? null;
            ?>
            <div class="card card-flush mb-6 border border-dashed border-gray-200 disc-post" id="disc_post_<?= $discId ?>">
                <div class="card-header pt-6 pb-0 border-0">
                    <a href="javascript:void(0)" onclick="openChatForUser(<?= $disc['author_id'] ?>,<?= json_encode(explode(' ',$disc['author_name'])[0]) ?>,<?= json_encode(explode(' ',$disc['author_name'],2)[1] ?? '') ?>,<?= json_encode($disc['author_photo'] ?? '') ?>)"
                       class="user-link d-flex align-items-center flex-grow-1 text-decoration-none">
                        <div class="symbol symbol-40px me-4 flex-shrink-0">
                            <?php if (!empty($disc['author_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $disc['author_photo']) ?>" class="rounded-circle" style="object-fit:cover;" alt="" />
                            <?php else: ?>
                            <div class="symbol-label bg-light-primary fw-bold text-primary fs-5"><?= strtoupper(substr($disc['author_name'], 0, 1)) ?></div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="user-link-name fw-bold text-gray-800 fs-6"><?= esc($disc['author_name']) ?></span>
                            <span class="text-muted fw-semibold fs-8 d-block"><?= stu_timeAgo($disc['created_at']) ?></span>
                        </div>
                    </a>
                    <?php if ($isOwn): ?>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-del-disc" data-disc-id="<?= $discId ?>" title="Delete">
                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-body py-4">
                    <div class="fs-6 fw-normal text-gray-700 disc-body-text clamped" id="disc_text_<?= $discId ?>"><?= nl2br(esc($disc['message'])) ?></div>
                    <button type="button" class="btn btn-link btn-sm p-0 mt-1 fs-8 fw-bold text-primary btn-show-more" data-target="disc_text_<?= $discId ?>" style="display:none;">Show more</button>
                </div>
                <div class="card-footer pt-0 border-0">
                    <div class="separator separator-solid mb-3"></div>
                    <ul class="nav py-1">
                        <li class="nav-item">
                            <button type="button" class="btn btn-sm btn-color-gray-600 btn-active-color-primary fw-bold px-4 me-1 btn-like-disc <?= $userReaction === 'like' ? 'text-primary' : '' ?>"
                                    data-disc-id="<?= $discId ?>" data-type="like">
                                <i class="ki-duotone ki-heart fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                <span class="like-count"><?= (int) $disc['like_count'] ?></span> Like<?= (int) $disc['like_count'] !== 1 ? 's' : '' ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-sm btn-color-gray-600 btn-active-color-danger fw-bold px-4 me-1 btn-like-disc <?= $userReaction === 'dislike' ? 'text-danger' : '' ?>"
                                    data-disc-id="<?= $discId ?>" data-type="dislike">
                                <i class="ki-duotone ki-dislike fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                <span class="dislike-count"><?= (int) $disc['dislike_count'] ?></span> Dislike<?= (int) $disc['dislike_count'] !== 1 ? 's' : '' ?>
                            </button>
                        </li>
                        <?php $totalR = (int)$disc['like_count'] + (int)$disc['dislike_count']; ?>
                        <?php if ($totalR > 0): ?>
                        <li class="nav-item">
                            <button type="button" class="btn btn-sm btn-color-gray-400 fw-semibold px-3 btn-disc-reactions"
                                    data-disc-id="<?= $discId ?>" data-likes="<?= (int)$disc['like_count'] ?>" data-dislikes="<?= (int)$disc['dislike_count'] ?>">
                                <?= $totalR ?> Reaction<?= $totalR !== 1 ? 's' : '' ?>
                            </button>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item ms-auto">
                            <a class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 collapsed"
                               data-bs-toggle="collapse" href="#disc_comments_<?= $discId ?>">
                                <i class="ki-duotone ki-message-text-2 fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <span class="comment-count"><?= (int) $disc['comment_count'] ?></span> Comment<?= (int) $disc['comment_count'] !== 1 ? 's' : '' ?>
                            </a>
                        </li>
                    </ul>
                    <div class="separator separator-solid mb-3"></div>
                    <div class="collapse" id="disc_comments_<?= $discId ?>">
                        <div class="pt-2" id="comments_area_<?= $discId ?>">
                        <?php foreach ($disc['comments'] as $cmt): ?>
                        <div class="d-flex align-items-start gap-3 mb-4" id="comment_item_<?= $cmt['comment_id'] ?>">
                            <a href="javascript:void(0)" onclick="openChatForUser(<?= $cmt['author_id'] ?>,<?= json_encode(explode(' ',$cmt['author_name'])[0]) ?>,<?= json_encode(explode(' ',$cmt['author_name'],2)[1] ?? '') ?>,<?= json_encode($cmt['author_photo'] ?? '') ?>)"
                               class="user-link symbol symbol-32px flex-shrink-0 text-decoration-none">
                                <?php if (!empty($cmt['author_photo'])): ?>
                                <img src="<?= base_url('uploads/profilePhoto/' . $cmt['author_photo']) ?>" class="rounded-circle" style="object-fit:cover;" alt="" />
                                <?php else: ?>
                                <div class="symbol-label bg-light-success fw-bold text-success fs-8"><?= strtoupper(substr($cmt['author_name'], 0, 1)) ?></div>
                                <?php endif; ?>
                            </a>
                            <div class="flex-grow-1 bg-light-secondary rounded-2 px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <a href="javascript:void(0)" onclick="openChatForUser(<?= $cmt['author_id'] ?>,<?= json_encode(explode(' ',$cmt['author_name'])[0]) ?>,<?= json_encode(explode(' ',$cmt['author_name'],2)[1] ?? '') ?>,<?= json_encode($cmt['author_photo'] ?? '') ?>)"
                                       class="user-link user-link-name fw-bold text-gray-800 fs-8 text-decoration-none"><?= esc($cmt['author_name']) ?></a>
                                    <span class="text-muted fs-9"><?= stu_timeAgo($cmt['created_at']) ?></span>
                                </div>
                                <span class="text-gray-700 fs-7 disc-body-text clamped d-block" id="cmt_text_<?= $cmt['comment_id'] ?>"><?= nl2br(esc($cmt['comment'])) ?></span>
                                <button type="button" class="btn btn-link btn-sm p-0 mt-1 fs-9 fw-bold text-primary btn-show-more" data-target="cmt_text_<?= $cmt['comment_id'] ?>" style="display:none;">Show more</button>
                                <div class="mt-2 d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-xs btn-color-gray-500 p-0 pe-1 btn-like-comment <?= $cmt['user_reaction'] === 'like' ? 'text-primary' : '' ?>"
                                            data-comment-id="<?= $cmt['comment_id'] ?>" data-disc-id="<?= $discId ?>" data-type="like">
                                        <i class="ki-duotone ki-heart fs-6 me-1"><span class="path1"></span><span class="path2"></span></i><span class="clike-count"><?= (int)$cmt['like_count'] ?></span> Like
                                    </button>
                                    <button type="button" class="btn btn-xs btn-color-gray-500 p-0 pe-1 btn-like-comment <?= $cmt['user_reaction'] === 'dislike' ? 'text-danger' : '' ?>"
                                            data-comment-id="<?= $cmt['comment_id'] ?>" data-disc-id="<?= $discId ?>" data-type="dislike">
                                        <i class="ki-duotone ki-dislike fs-6 me-1"><span class="path1"></span><span class="path2"></span></i><span class="cdislike-count"><?= (int)$cmt['dislike_count'] ?></span> Dislike
                                    </button>
                                    <?php $totalCR = (int)$cmt['like_count'] + (int)$cmt['dislike_count']; if ($totalCR > 0): ?>
                                    <button type="button" class="btn btn-xs btn-color-gray-400 p-0 btn-comment-reactions"
                                            data-comment-id="<?= $cmt['comment_id'] ?>" data-disc-id="<?= $discId ?>"
                                            data-likes="<?= (int)$cmt['like_count'] ?>" data-dislikes="<?= (int)$cmt['dislike_count'] ?>">
                                        <?= $totalCR ?> reaction<?= $totalCR !== 1 ? 's' : '' ?>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        </div>
                        <?php if ($fullAccess ?? false): ?>
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <div class="symbol symbol-32px flex-shrink-0">
                                <?php if ($sessionPhotoUrl): ?>
                                <img src="<?= $sessionPhotoUrl ?>" class="rounded-circle" style="object-fit:cover;width:32px;height:32px;" alt="" />
                                <?php else: ?>
                                <div class="symbol-label bg-light-primary fw-bold text-primary fs-8"><?= strtoupper(substr($sessionFname, 0, 1)) ?></div>
                                <?php endif; ?>
                            </div>
                            <textarea class="form-control form-control-sm form-control-solid border flex-grow-1 disc-comment-input"
                                      rows="1" placeholder="Write a comment..." data-disc-id="<?= $discId ?>"></textarea>
                            <button type="button" class="btn btn-sm btn-primary flex-shrink-0 btn-send-comment" data-disc-id="<?= $discId ?>">
                                <i class="ki-duotone ki-send fs-5"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
            </div>
            <!--end::Discussions list-->
        </div>
    </div>
    <!--end::Discussion card-->

</div>
</div>

<!--begin::Reactions Modal-->
<div class="modal fade" id="reactions_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-6 mb-0">Reactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3 pb-0">
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-7 fw-semibold mb-4">
                    <li class="nav-item"><a class="nav-link text-active-primary active" id="react_tab_all" data-bs-toggle="tab" href="#react_pane_all">All 0</a></li>
                    <li class="nav-item"><a class="nav-link text-active-primary d-flex align-items-center gap-1" id="react_tab_like" data-bs-toggle="tab" href="#react_pane_like"><i class="ki-duotone ki-heart fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i> Like 0</a></li>
                    <li class="nav-item"><a class="nav-link text-active-primary d-flex align-items-center gap-1" id="react_tab_dislike" data-bs-toggle="tab" href="#react_pane_dislike"><i class="ki-duotone ki-dislike fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i> Dislike 0</a></li>
                </ul>
                <div class="tab-content" style="max-height:320px;overflow-y:auto;">
                    <div class="tab-pane fade show active" id="react_pane_all"></div>
                    <div class="tab-pane fade" id="react_pane_like"></div>
                    <div class="tab-pane fade" id="react_pane_dislike"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-2"><button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
<!--end::Reactions Modal-->

<script>
const LESSON_ID       = <?= (int) $lesson['lesson_id'] ?>;
const DISC_POST_URL   = '<?= base_url("classroom/lesson/" . $lesson["lesson_id"] . "/discussion/post") ?>';
const DISC_LIKE_BASE  = '<?= base_url("classroom/lesson/" . $lesson["lesson_id"] . "/discussion") ?>';
const UPLOADS_BASE    = '<?= base_url("uploads/profilePhoto/") ?>';
const PROFILE_BASE    = '<?= base_url("user/detail/") ?>';
const SESSION_PHOTO   = '<?= addslashes($sessionPhotoUrl ?? "") ?>';
const SESSION_INITIAL = '<?= strtoupper(substr($sessionFname, 0, 1)) ?>';

function openChatForUser(userId, fname, lname, photo) {
    if (typeof openChat !== 'function') return;
    openChat({ user_id: userId, fname: fname || '', lname: lname || '', profile_photo: photo || '', online_status: 'Offline' }, null);
}

function discAvHtml(photo, name, size) {
    if (photo) return '<img src="' + UPLOADS_BASE + photo + '" class="rounded-circle" style="object-fit:cover;width:' + size + 'px;height:' + size + 'px;" alt="">';
    return '<div class="symbol-label bg-light-primary fw-bold text-primary fs-8">' + name.charAt(0).toUpperCase() + '</div>';
}

function discCommentHtml(cmt, discId) {
    return '<div class="d-flex align-items-start gap-3 mb-4" id="comment_item_' + cmt.comment_id + '">' +
        '<a href="javascript:void(0)" onclick="openChatForUser(' + cmt.author_id + ',\'' + (cmt.author_name||'').split(' ')[0] + '\',\'' + ((cmt.author_name||'').split(' ').slice(1).join(' ')) + '\',\'' + (cmt.author_photo||'') + '\')" class="user-link symbol symbol-32px flex-shrink-0 text-decoration-none">' +
        discAvHtml(cmt.author_photo, cmt.author_name, 32) + '</a>' +
        '<div class="flex-grow-1 bg-light-secondary rounded-2 px-4 py-3">' +
        '<div class="d-flex justify-content-between align-items-center mb-1">' +
        '<a href="javascript:void(0)" onclick="openChatForUser(' + cmt.author_id + ',\'' + (cmt.author_name||'').split(' ')[0] + '\',\'' + ((cmt.author_name||'').split(' ').slice(1).join(' ')) + '\',\'' + (cmt.author_photo||'') + '\')" class="user-link user-link-name fw-bold text-gray-800 fs-8 text-decoration-none">' + cmt.author_name + '</a>' +
        '<span class="text-muted fs-9">Just now</span></div>' +
        '<span class="text-gray-700 fs-7 disc-body-text clamped d-block" id="cmt_text_' + cmt.comment_id + '">' + cmt.comment.replace(/\n/g,'<br>') + '</span>' +
        '<button type="button" class="btn btn-link btn-sm p-0 mt-1 fs-9 fw-bold text-primary btn-show-more" data-target="cmt_text_' + cmt.comment_id + '" style="display:none;">Show more</button>' +
        '<div class="mt-2 d-flex align-items-center gap-3">' +
        '<button type="button" class="btn btn-xs btn-color-gray-500 p-0 pe-1 btn-like-comment" data-comment-id="' + cmt.comment_id + '" data-disc-id="' + discId + '" data-type="like"><i class="ki-duotone ki-heart fs-6 me-1"><span class="path1"></span><span class="path2"></span></i><span class="clike-count">0</span> Like</button>' +
        '<button type="button" class="btn btn-xs btn-color-gray-500 p-0 pe-1 btn-like-comment" data-comment-id="' + cmt.comment_id + '" data-disc-id="' + discId + '" data-type="dislike"><i class="ki-duotone ki-dislike fs-6 me-1"><span class="path1"></span><span class="path2"></span></i><span class="cdislike-count">0</span> Dislike</button>' +
        '</div></div></div>';
}

function discPostHtml(d) {
    const myAv = SESSION_PHOTO
        ? '<img src="' + SESSION_PHOTO + '" class="rounded-circle" style="object-fit:cover;width:32px;height:32px;" alt="">'
        : '<div class="symbol-label bg-light-primary fw-bold text-primary fs-8">' + SESSION_INITIAL + '</div>';
    const nameParts = (d.author_name||'').split(' ');
    const aFname = nameParts[0]||''; const aLname = nameParts.slice(1).join(' ')||'';
    return '<div class="card card-flush mb-6 border border-dashed border-gray-200 disc-post" id="disc_post_' + d.lesson_discussion_id + '">' +
        '<div class="card-header pt-6 pb-0 border-0">' +
        '<a href="javascript:void(0)" onclick="openChatForUser(' + d.author_id + ',\'' + aFname + '\',\'' + aLname + '\',\'' + (d.author_photo||'') + '\')" class="user-link d-flex align-items-center flex-grow-1 text-decoration-none">' +
        '<div class="symbol symbol-40px me-4 flex-shrink-0">' + discAvHtml(d.author_photo, d.author_name, 40) + '</div>' +
        '<div><span class="user-link-name fw-bold text-gray-800 fs-6">' + d.author_name + '</span><span class="text-muted fw-semibold fs-8 d-block">Just now</span></div></a>' +
        '<div class="card-toolbar"><button type="button" class="btn btn-icon btn-sm btn-light-danger btn-del-disc" data-disc-id="' + d.lesson_discussion_id + '" title="Delete">' +
        '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button></div></div>' +
        '<div class="card-body py-4"><div class="fs-6 fw-normal text-gray-700 disc-body-text clamped" id="disc_text_' + d.lesson_discussion_id + '">' + d.message.replace(/\n/g,'<br>') + '</div>' +
        '<button type="button" class="btn btn-link btn-sm p-0 mt-1 fs-8 fw-bold text-primary btn-show-more" data-target="disc_text_' + d.lesson_discussion_id + '" style="display:none;">Show more</button></div>' +
        '<div class="card-footer pt-0 border-0"><div class="separator separator-solid mb-3"></div>' +
        '<ul class="nav py-1">' +
        '<li class="nav-item"><button type="button" class="btn btn-sm btn-color-gray-600 btn-active-color-primary fw-bold px-4 me-1 btn-like-disc" data-disc-id="' + d.lesson_discussion_id + '" data-type="like"><i class="ki-duotone ki-heart fs-4 me-1"><span class="path1"></span><span class="path2"></span></i><span class="like-count">0</span> Likes</button></li>' +
        '<li class="nav-item"><button type="button" class="btn btn-sm btn-color-gray-600 btn-active-color-danger fw-bold px-4 me-1 btn-like-disc" data-disc-id="' + d.lesson_discussion_id + '" data-type="dislike"><i class="ki-duotone ki-dislike fs-4 me-1"><span class="path1"></span><span class="path2"></span></i><span class="dislike-count">0</span> Dislikes</button></li>' +
        '<li class="nav-item reactions-nav-item" style="display:none;"><button type="button" class="btn btn-sm btn-color-gray-400 fw-semibold px-3 btn-disc-reactions" data-disc-id="' + d.lesson_discussion_id + '" data-likes="0" data-dislikes="0"><span class="total-reactions">0</span> Reactions</button></li>' +
        '<li class="nav-item ms-auto"><a class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 collapsed" data-bs-toggle="collapse" href="#disc_comments_' + d.lesson_discussion_id + '"><i class="ki-duotone ki-message-text-2 fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><span class="comment-count">0</span> Comments</a></li></ul>' +
        '<div class="separator separator-solid mb-3"></div>' +
        '<div class="collapse" id="disc_comments_' + d.lesson_discussion_id + '"><div class="pt-2" id="comments_area_' + d.lesson_discussion_id + '"></div>' +
        '<div class="d-flex align-items-center gap-3 mt-3"><div class="symbol symbol-32px flex-shrink-0">' + myAv + '</div>' +
        '<textarea class="form-control form-control-sm form-control-solid border flex-grow-1 disc-comment-input" rows="1" placeholder="Write a comment..." data-disc-id="' + d.lesson_discussion_id + '"></textarea>' +
        '<button type="button" class="btn btn-sm btn-primary flex-shrink-0 btn-send-comment" data-disc-id="' + d.lesson_discussion_id + '"><i class="ki-duotone ki-send fs-5"><span class="path1"></span><span class="path2"></span></i></button></div></div></div></div>';
}

// Discussion interactions (identical to teacher view — students fully participate)
document.getElementById('btn_post_discussion')?.addEventListener('click', function() {
    const btn = this, message = document.getElementById('disc_post_input').value.trim();
    if (!message) { Swal.fire({ title: 'Required', text: 'Please write something before posting.', icon: 'warning', buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } }); return; }
    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;
    const fd = new FormData(); fd.append('message', message);
    $.ajax({ url: DISC_POST_URL, type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                document.getElementById('disc_post_input').value = '';
                document.getElementById('disc_empty_state')?.remove();
                $('#discussions_list').prepend(discPostHtml(res.discussion));
                initShowMore(document.getElementById('disc_text_' + res.discussion.lesson_discussion_id));
                const cnt = document.querySelectorAll('.disc-post').length;
                document.getElementById('disc_total_label').textContent = cnt + ' post' + (cnt !== 1 ? 's' : '');
            } else { Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } }); }
        }, error: function() { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; }
    });
});

$(document).on('click', '.btn-like-disc', function() {
    const discId = $(this).data('disc-id'), type = $(this).data('type');
    const fd = new FormData(); fd.append('type', type);
    $.ajax({ url: DISC_LIKE_BASE + '/' + discId + '/like', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (!res.success) return;
            const card = document.getElementById('disc_post_' + discId);
            card.querySelector('.like-count').textContent = res.likes;
            card.querySelector('.dislike-count').textContent = res.dislikes;
            card.querySelectorAll('.btn-like-disc').forEach(b => b.classList.remove('text-primary','text-danger'));
            if (res.reaction === 'like') card.querySelector('[data-type="like"]').classList.add('text-primary');
            if (res.reaction === 'dislike') card.querySelector('[data-type="dislike"]').classList.add('text-danger');
            const total = res.likes + res.dislikes;
            const rBtn = card.querySelector('.btn-disc-reactions'), rLi = card.querySelector('.reactions-nav-item');
            if (rBtn && rLi) { rBtn.dataset.likes = res.likes; rBtn.dataset.dislikes = res.dislikes; if (total > 0) { rBtn.querySelector('.total-reactions').textContent = total; rLi.style.display = ''; } else rLi.style.display = 'none'; }
        }
    });
});

$(document).on('click', '.btn-send-comment', function() {
    const discId = $(this).data('disc-id'), textarea = document.querySelector('.disc-comment-input[data-disc-id="' + discId + '"]');
    const comment = textarea.value.trim(); if (!comment) return;
    const fd = new FormData(); fd.append('comment', comment);
    $.ajax({ url: DISC_LIKE_BASE + '/' + discId + '/comment', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (res.success) {
                textarea.value = '';
                document.getElementById('comments_area_' + discId).insertAdjacentHTML('beforeend', discCommentHtml(res.comment, discId));
                initShowMore(document.getElementById('cmt_text_' + res.comment.comment_id));
                const cnt = document.querySelectorAll('#comments_area_' + discId + ' > div').length;
                document.getElementById('disc_post_' + discId).querySelector('.comment-count').textContent = cnt;
            }
        }
    });
});

$(document).on('click', '.btn-del-disc', function() {
    const discId = $(this).data('disc-id');
    Swal.fire({ title: 'Delete this post?', text: 'This cannot be undone.', icon: 'warning', showCancelButton: true, buttonsStyling: false,
        confirmButtonText: 'Delete', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({ url: DISC_LIKE_BASE + '/' + discId + '/delete', type: 'POST', data: new FormData(), processData: false, contentType: false,
            success: function(res) {
                if (res.success) {
                    document.getElementById('disc_post_' + discId)?.remove();
                    const cnt = document.querySelectorAll('.disc-post').length;
                    document.getElementById('disc_total_label').textContent = cnt + ' post' + (cnt !== 1 ? 's' : '');
                    if (cnt === 0) document.getElementById('discussions_list').innerHTML = '<div class="text-center py-12 text-muted" id="disc_empty_state"><i class="ki-duotone ki-message-edit fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i><div class="fs-6 fw-semibold mb-1">No posts yet</div><div class="fs-8">Be the first to start a discussion.</div></div>';
                }
            }
        });
    });
});

$(document).on('click', '.btn-like-comment', function() {
    const commentId = $(this).data('comment-id'), discId = $(this).data('disc-id'), type = $(this).data('type') || 'like';
    const fd = new FormData(); fd.append('type', type);
    $.ajax({ url: DISC_LIKE_BASE + '/' + discId + '/comment/' + commentId + '/like', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (!res.success) return;
            const item = document.getElementById('comment_item_' + commentId);
            item.querySelectorAll('.btn-like-comment').forEach(b => b.classList.remove('text-primary','text-danger'));
            const lb = item.querySelector('[data-type="like"]'), db = item.querySelector('[data-type="dislike"]');
            if (lb) lb.querySelector('.clike-count').textContent = res.likes;
            if (db) db.querySelector('.cdislike-count').textContent = res.dislikes;
            if (res.reaction === 'like') lb?.classList.add('text-primary');
            if (res.reaction === 'dislike') db?.classList.add('text-danger');
            const total = res.likes + res.dislikes;
            let rBtn = item.querySelector('.btn-comment-reactions');
            if (total > 0) {
                if (!rBtn) { rBtn = document.createElement('button'); rBtn.type = 'button'; rBtn.className = 'btn btn-xs btn-color-gray-400 p-0 btn-comment-reactions'; rBtn.dataset.commentId = commentId; rBtn.dataset.discId = discId; item.querySelector('.d-flex.mt-2').appendChild(rBtn); }
                rBtn.dataset.likes = res.likes; rBtn.dataset.dislikes = res.dislikes;
                rBtn.textContent = total + ' reaction' + (total !== 1 ? 's' : '');
            } else if (rBtn) rBtn.remove();
        }
    });
});

// Reactions modal
const REACTIONS_MODAL_URL_BASE = DISC_LIKE_BASE;
function reactionUserHtml(r) {
    const av = r.photo ? '<img src="' + UPLOADS_BASE + r.photo + '" class="rounded-circle" style="object-fit:cover;width:48px;height:48px;" alt="">'
        : '<div class="symbol-label bg-light-primary fw-bold text-primary fs-6">' + r.name.charAt(0).toUpperCase() + '</div>';
    const icon = r.like_type === 'like'
        ? '<div class="position-absolute bottom-0 start-0 bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:18px;height:18px;"><i class="ki-duotone ki-heart fs-9 text-white"><span class="path1"></span><span class="path2"></span></i></div>'
        : '<div class="position-absolute bottom-0 start-0 bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:18px;height:18px;"><i class="ki-duotone ki-dislike fs-9 text-white"><span class="path1"></span><span class="path2"></span></i></div>';
    return '<div class="d-flex align-items-center gap-3 mb-4"><div class="symbol symbol-48px position-relative flex-shrink-0">' + av + icon + '</div><span class="fw-semibold text-gray-800 fs-7">' + r.name + '</span></div>';
}
function buildReactionTabs(reactions, likes, dislikes) {
    const total = likes + dislikes;
    document.getElementById('react_tab_all').textContent = 'All ' + total;
    document.getElementById('react_tab_like').innerHTML = '<i class="ki-duotone ki-heart fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i> Like ' + likes;
    document.getElementById('react_tab_dislike').innerHTML = '<i class="ki-duotone ki-dislike fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i> Dislike ' + dislikes;
    document.getElementById('react_pane_all').innerHTML    = reactions.map(r => reactionUserHtml(r)).join('') || '<p class="text-muted text-center py-4">No reactions</p>';
    document.getElementById('react_pane_like').innerHTML   = reactions.filter(r => r.like_type === 'like').map(r => reactionUserHtml(r)).join('') || '<p class="text-muted text-center py-4">No likes</p>';
    document.getElementById('react_pane_dislike').innerHTML = reactions.filter(r => r.like_type === 'dislike').map(r => reactionUserHtml(r)).join('') || '<p class="text-muted text-center py-4">No dislikes</p>';
    document.querySelectorAll('#reactions_modal .nav-link').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('#reactions_modal .tab-pane').forEach(p => { p.classList.remove('show','active'); });
    document.getElementById('react_tab_all').classList.add('active');
    document.getElementById('react_pane_all').classList.add('show','active');
}
$(document).on('click', '.btn-disc-reactions', function() {
    const discId = $(this).data('disc-id'), likes = parseInt($(this).data('likes'))||0, dislikes = parseInt($(this).data('dislikes'))||0;
    $.get(REACTIONS_MODAL_URL_BASE + '/' + discId + '/reactions', function(res) { if (!res.success) return; buildReactionTabs(res.reactions, likes, dislikes); new bootstrap.Modal(document.getElementById('reactions_modal')).show(); });
});
$(document).on('click', '.btn-comment-reactions', function() {
    const commentId = $(this).data('comment-id'), discId = $(this).data('disc-id'), likes = parseInt($(this).data('likes'))||0, dislikes = parseInt($(this).data('dislikes'))||0;
    $.get(REACTIONS_MODAL_URL_BASE + '/' + discId + '/comment/' + commentId + '/reactions', function(res) { if (!res.success) return; buildReactionTabs(res.reactions, likes, dislikes); new bootstrap.Modal(document.getElementById('reactions_modal')).show(); });
});

// Show more / show less
function toggleShowMore(el, btn) {
    if (!el) return;
    if (el.classList.contains('clamped')) { el.classList.remove('clamped'); el.style.cursor = 'default'; if (btn) { btn.style.display = ''; btn.textContent = 'Show less'; } }
    else { el.classList.add('clamped'); el.style.cursor = 'pointer'; if (btn) btn.textContent = 'Show more'; }
}
function initShowMore(el) {
    if (!el) return;
    const btn = el.nextElementSibling;
    if (!btn || !btn.classList.contains('btn-show-more')) return;
    if (el.scrollHeight > el.clientHeight + 1) { btn.style.display = ''; el.style.cursor = 'pointer'; el.onclick = function() { toggleShowMore(el, btn); }; }
}
$(document).on('click', '.btn-show-more', function() { const el = document.getElementById(this.dataset.target); toggleShowMore(el, this); });
requestAnimationFrame(function() { document.querySelectorAll('.disc-post .disc-body-text').forEach(initShowMore); });
$(document).on('shown.bs.collapse', '[id^="disc_comments_"]', function() { $(this).find('.disc-body-text').each(function() { initShowMore(this); }); });

// ── Take Assessment (unified for all types) ──────────────────────
const ATYPE_ICONS = {
    quiz:       'ki-questionnaire-tablet',
    drag_drop:  'ki-abstract-26',
    labelling:  'ki-tag',
    simulation: 'ki-rocket',
};
const ATYPE_LABELS = {
    quiz:       'Quiz',
    drag_drop:  'Drag &amp; Drop',
    labelling:  'Labelling',
    simulation: 'Simulation',
};
const ATYPE_COLORS = { quiz:'success', drag_drop:'primary', labelling:'info', simulation:'danger' };

$(document).on('click', '.btn-take-assessment', function() {
    const isAttempted = $(this).data('is-attempted') == 1;
    const takeUrl     = $(this).data('take-url');
    const scoreUrl    = $(this).data('score-url');
    const name        = $(this).data('name');
    const duration    = parseInt($(this).data('duration')) || 0;
    const info        = $(this).data('info') || '';
    const atype       = $(this).data('atype') || 'quiz';
    const color       = ATYPE_COLORS[atype] || 'primary';
    const icon        = ATYPE_ICONS[atype]  || 'ki-questionnaire-tablet';
    const typeLabel   = ATYPE_LABELS[atype] || 'Assessment';

    // Already attempted → offer to view score
    if (isAttempted) {
        Swal.fire({
            title: 'Already Completed',
            html: `<p class="mb-1 fs-7 text-gray-700">You have already submitted this assessment.</p>
                   <p class="fs-8 text-muted">Would you like to view your score?</p>`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'View Score',
            cancelButtonText: 'Close',
            buttonsStyling: false,
            customClass: { confirmButton: 'btn btn-info me-2', cancelButton: 'btn btn-light' }
        }).then(r => { if (r.isConfirmed) window.location = scoreUrl; });
        return;
    }

    // Build duration row
    const durationRow = duration > 0
        ? `<div class="d-flex align-items-center gap-2 mb-2">
               <i class="ki-duotone ki-time fs-5 text-warning"><span class="path1"></span><span class="path2"></span></i>
               <span><strong>${duration} minute${duration !== 1 ? 's' : ''}</strong> time limit</span>
           </div>`
        : `<div class="d-flex align-items-center gap-2 mb-2">
               <i class="ki-duotone ki-time fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
               <span class="text-success fw-semibold">No time limit</span>
           </div>`;

    const timerWarning = duration > 0
        ? `<div class="alert alert-warning d-flex align-items-start gap-2 py-3 mt-3 mb-0 text-start fs-8">
               <i class="ki-duotone ki-information-5 fs-4 flex-shrink-0 mt-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
               <span>Once you start, the timer <strong>cannot be paused</strong>. Your answers will be auto-submitted when time runs out.</span>
           </div>`
        : '';

    Swal.fire({
        title: 'Start Assessment?',
        html: `<div class="text-start px-1">
            <div class="fw-bold text-gray-900 fs-6 mb-3">${name}</div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge badge-light-${color} fs-8 px-3 py-2">
                    <i class="ki-duotone ${icon} fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>${typeLabel}
                </span>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2 text-muted fs-8">
                <i class="ki-duotone ki-information fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <span>${info}</span>
            </div>
            ${durationRow}${timerWarning}
        </div>`,
        icon: duration > 0 ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Start Now',
        cancelButtonText: 'Cancel',
        buttonsStyling: false,
        customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-light' }
    }).then(r => { if (r.isConfirmed) window.location = takeUrl; });
});
</script>

<style>
.info-row-border { border-bottom: 1px solid #d4d4e0; }
.media-card { transition: box-shadow .15s, transform .15s; }
.media-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.12); transform: translateY(-2px); }
.media-thumb { position: relative; }
.disc-body-text.clamped { display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }
.disc-body-text.clamped { cursor:pointer; }
.btn-show-more { text-decoration:none; }
.btn-show-more:hover { text-decoration:underline; }
.user-link { transition: opacity .15s; }
.user-link:hover { opacity: .8; }
.user-link:hover .user-link-name { color: var(--bs-primary) !important; }
</style>
