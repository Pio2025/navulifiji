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
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/teacher/' . $schSubId . '/lessons') ?>" class="text-muted text-hover-primary">Lessons</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($lesson['lesson_title']) ?></li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/teacher/' . $schSubId . '/lessons') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back to Lessons
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php $isReadonly = !($isActive ?? true); ?>
    <?php if ($isReadonly): ?>
    <div class="d-flex align-items-center gap-2 px-6 py-3 mb-6 rounded-2" style="background:#fff8e1;border:1px solid #ffe082;">
        <i class="ki-duotone ki-lock-2 fs-4 text-warning"><span class="path1"></span><span class="path2"></span></i>
        <span class="fs-7 fw-semibold text-warning">Read-only — this classroom is no longer active. No content can be added or modified.</span>
    </div>
    <?php endif; ?>

    <?php
    $stepConfig = [
        'intro'      => ['label' => 'Introduction', 'icon' => 'ki-flag',                   'color' => 'primary'],
        'video'      => ['label' => 'Video',         'icon' => 'ki-video',                  'color' => 'danger'],
        'reading'    => ['label' => 'Reading',       'icon' => 'ki-document',               'color' => 'info'],
        'activity'   => ['label' => 'Activity',      'icon' => 'ki-abstract-26',            'color' => 'warning'],
        'quiz'       => ['label' => 'Quiz',          'icon' => 'ki-questionnaire-tablet',   'color' => 'success'],
        'discussion' => ['label' => 'Discussion',    'icon' => 'ki-message-edit',           'color' => 'dark'],
        'summary'    => ['label' => 'Summary',       'icon' => 'ki-check-circle',           'color' => 'success'],
    ];
    $statusColor = match($lesson['lesson_status']) {
        'Published' => 'success', 'Draft' => 'warning', 'Archived' => 'secondary', default => 'secondary',
    };
    ?>

    <div class="row g-6">

        <!--begin::Left panel-->
        <div class="col-md-12">

            <!--begin::Lesson info card-->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-5">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div>
                            <span class="badge badge-light-primary fs-9 mb-2">Lesson <?= esc($lesson['lesson_order']) ?></span>
                            <div class="fw-bold text-gray-900 fs-5 lh-sm" id="lesson_title_display"><?= esc($lesson['lesson_title']) ?></div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-2">
                            <span class="badge badge-light-<?= $statusColor ?> fs-9" id="lesson_status_badge"><?= esc($lesson['lesson_status']) ?></span>
                            <?php if (!$isReadonly): ?>
                            <button type="button" class="btn btn-sm btn-icon btn-light" id="btn_edit_lesson" title="Edit lesson">
                                <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <p class="text-muted fs-7 mb-4" id="lesson_desc_display" style="<?= $lesson['lesson_desc'] ? '' : 'display:none;' ?>"><?= nl2br(esc($lesson['lesson_desc'])) ?></p>

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
                        <span class="fw-semibold text-gray-800 fs-8" id="lesson_term_display">Term <?= esc($lesson['lesson_term']) ?></span>
                    </div>
                </div>
            </div>
            <!--end::Lesson info card-->

            <!--begin::Lesson flow summary-->
            <?php if (!empty($lesson['steps'])): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 pt-4 pb-2">
                    <h6 class="card-title fw-bold text-gray-700 fs-7 mb-0">Lesson Flow</h6>
                </div>
                <div class="card-body pt-2 pb-4">
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <?php foreach ($lesson['steps'] as $si => $step):
                            $sc = $stepConfig[$step['step_type']] ?? ['label' => ucfirst($step['step_type']), 'icon' => 'ki-abstract-26', 'color' => 'secondary'];
                        ?>
                        <?php if ($si > 0): ?>
                        <i class="ki-duotone ki-right fs-8 text-gray-400"><span class="path1"></span><span class="path2"></span></i>
                        <?php endif; ?>
                        <span class="badge badge-light-<?= $sc['color'] ?> fs-10 py-1 px-2"><?= $sc['label'] ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
        <!--end::Left panel-->

        

    </div>

    <?php
    function lessonFileColor(string $ext): string {
        return match($ext) {
            'pdf'                        => 'danger',
            'doc','docx'                 => 'primary',
            'xls','xlsx'                 => 'success',
            'ppt','pptx'                 => 'warning',
            'zip','rar'                  => 'dark',
            'jpg','jpeg','png','gif',
            'webp','svg'                 => 'info',
            default                      => 'secondary',
        };
    }
    function lessonFileLabel(string $ext): string {
        return match($ext) {
            'jpeg' => 'JPG',
            default => strtoupper($ext),
        };
    }
    function lessonFileSize(int $bytes): string {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
    $fileCount  = count($lesson['files']  ?? []);
    $videoCount = count($lesson['videos'] ?? []);
    $linkCount  = count($lesson['links']  ?? []);
    ?>

    <?php
    function youtubeId(string $url): string {
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m);
        return $m[1] ?? '';
    }
    $imageExts = ['jpg','jpeg','png','gif','webp','svg'];
    ?>

    <!--begin::Files Videos Links card-->
    <div class="card shadow-sm mt-6">
        <div class="card-body p-0">

            <!--begin::Files-->
            <div class="p-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h6 class="fw-bold text-gray-800 mb-0">
                            <i class="ki-duotone ki-file-up fs-4 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                            Reading Materials
                        </h6>
                        <span class="text-muted fs-9" id="file_count_label"><?= $fileCount ?> of 10 files</span>
                    </div>
                    <?php if (!$isReadonly): ?>
                    <label class="btn btn-sm btn-light-primary mb-0" id="btn_pick_files"<?= $fileCount >= 10 ? ' style="display:none;"' : '' ?>>
                        <i class="ki-duotone ki-upload fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Upload Files
                        <input type="file" id="lesson_file_input" name="lesson_files[]" multiple
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.jpg,.jpeg,.png,.gif,.webp,.svg" style="display:none;">
                    </label>
                    <?php endif; ?>
                </div>
                <div id="files_list">
                <?php if (empty($lesson['files'])): ?>
                    <div class="text-center py-8 text-muted" id="files_empty">
                        <i class="ki-duotone ki-file fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fs-8">No files yet. Upload PDF, images, Word, Excel, PowerPoint, ZIP or text files.</div>
                    </div>
                <?php else: ?>
                <div class="row g-3" id="files_grid">
                    <?php foreach ($lesson['files'] as $f):
                        $ext   = strtolower($f['file_type'] ?? '');
                        $color = lessonFileColor($ext);
                        $label = lessonFileLabel($ext);
                        $isImg = in_array($ext, $imageExts);
                    ?>
                    <div class="col-6 col-sm-4 col-md-2 file-item" id="file_item_<?= $f['file_id'] ?>">
                        <div class="media-card position-relative border border-gray-100 rounded-2 overflow-hidden">
                            <a href="<?= base_url('uploads/lesson_files/' . $f['file_path']) ?>" target="_blank" class="d-block media-thumb">
                                <?php if ($isImg): ?>
                                <img src="<?= base_url('uploads/lesson_files/' . $f['file_path']) ?>"
                                     alt="<?= esc($f['file_name']) ?>"
                                     style="width:100%;height:110px;object-fit:cover;display:block;">
                                <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light-<?= $color ?>"
                                     style="height:110px;">
                                    <span class="fw-bold text-<?= $color ?> fs-2"><?= $label ?></span>
                                </div>
                                <?php endif; ?>
                            </a>
                            <?php if (!$isReadonly): ?>
                            <button type="button" class="btn btn-icon btn-danger btn-del-file position-absolute top-0 end-0 m-1 media-del-btn"
                                    data-file-id="<?= $f['file_id'] ?>">
                                <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </button>
                            <?php endif; ?>
                            <div class="p-2">
                                <div class="text-truncate fw-semibold text-gray-800 fs-9" title="<?= esc($f['file_name']) ?>"><?= esc($f['file_name']) ?></div>
                                <span class="badge bg-light-<?= $color ?> text-<?= $color ?> fs-10 px-1"><?= $label ?></span>
                                <span class="text-muted fs-10 ms-1"><?= lessonFileSize((int) $f['file_size']) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                </div>
            </div>
            <!--end::Files-->

            <hr class="m-0">

            <!--begin::Videos-->
            <div class="p-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h6 class="fw-bold text-gray-800 mb-0">
                            <i class="ki-duotone ki-video fs-4 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>
                            Video Content
                        </h6>
                        <span class="text-muted fs-9" id="video_count_label"><?= $videoCount ?> of 10 videos</span>
                    </div>
                    <?php if (!$isReadonly): ?>
                    <button type="button" class="btn btn-sm btn-light-danger" id="btn_toggle_video_form"<?= $videoCount >= 10 ? ' style="display:none;"' : '' ?>>
                        <i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Add Video
                    </button>
                    <?php endif; ?>
                </div>
                <div id="video_add_form" style="display:none;" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-8 mb-1">Video URL</label>
                            <input type="url" class="form-control form-control-sm" id="new_video_url" placeholder="https://youtube.com/watch?v=..." />
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-8 mb-1">Title <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control form-control-sm" id="new_video_title" placeholder="e.g. Newton's Laws" maxlength="255" />
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-primary flex-grow-1" id="btn_save_video">Save</button>
                            <button type="button" class="btn btn-sm btn-light" id="btn_cancel_video">
                                <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="videos_list">
                <?php if (empty($lesson['videos'])): ?>
                    <div class="text-center py-8 text-muted" id="videos_empty">
                        <i class="ki-duotone ki-video fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fs-8">No videos yet. Add a YouTube, Vimeo or any video URL.</div>
                    </div>
                <?php else: ?>
                <div class="row g-3" id="videos_grid">
                    <?php foreach ($lesson['videos'] as $v):
                        $ytId    = youtubeId($v['video_url']);
                        $thumb   = $ytId ? 'https://img.youtube.com/vi/' . $ytId . '/mqdefault.jpg' : '';
                        $label   = esc($v['video_title'] ?: $v['video_url']);
                    ?>
                    <div class="col-6 col-sm-4 col-md-2 video-item" id="video_item_<?= $v['video_id'] ?>">
                        <div class="media-card position-relative border border-gray-100 rounded-2 overflow-hidden">
                            <a href="<?= esc($v['video_url']) ?>" target="_blank" class="d-block media-thumb">
                                <?php if ($thumb): ?>
                                <img src="<?= $thumb ?>" alt="<?= $label ?>"
                                     style="width:100%;height:110px;object-fit:cover;display:block;">
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
                            <?php if (!$isReadonly): ?>
                            <button type="button" class="btn btn-icon btn-danger btn-del-video position-absolute top-0 end-0 m-1 media-del-btn"
                                    data-video-id="<?= $v['video_id'] ?>">
                                <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </button>
                            <?php endif; ?>
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
            </div>
            <!--end::Videos-->

            <hr class="m-0">

            <!--begin::Links-->
            <div class="p-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h6 class="fw-bold text-gray-800 mb-0">
                            <i class="ki-duotone ki-link fs-4 text-info me-2"><span class="path1"></span><span class="path2"></span></i>
                            Supplementary Links
                        </h6>
                        <span class="text-muted fs-9" id="link_count_label"><?= $linkCount ?> of 10 referrals</span>
                    </div>
                    <?php if (!$isReadonly): ?>
                    <button type="button" class="btn btn-sm btn-light-info" id="btn_toggle_link_form"<?= $linkCount >= 10 ? ' style="display:none;"' : '' ?>>
                        <i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Add Link
                    </button>
                    <?php endif; ?>
                </div>
                <div id="link_add_form" style="display:none;" class="mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-8 mb-1">URL</label>
                            <input type="url" class="form-control form-control-sm" id="new_link_url" placeholder="https://..." />
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-8 mb-1">Title <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control form-control-sm" id="new_link_title" placeholder="e.g. Khan Academy - Newton's Laws" maxlength="255" />
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-primary flex-grow-1" id="btn_save_link">Save</button>
                            <button type="button" class="btn btn-sm btn-light" id="btn_cancel_link">
                                <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="links_list">
                <?php if (empty($lesson['links'])): ?>
                    <div class="text-center py-8 text-muted" id="links_empty">
                        <i class="ki-duotone ki-link fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fs-8">No referrals yet. Add links to external resources, articles or websites.</div>
                    </div>
                <?php else: ?>
                <div class="row g-3" id="links_grid">
                    <?php foreach ($lesson['links'] as $lnk):
                        $domain = parse_url($lnk['link_url'], PHP_URL_HOST) ?: $lnk['link_url'];
                    ?>
                    <div class="col-6 col-sm-4 col-md-2 link-item" id="link_item_<?= $lnk['link_id'] ?>">
                        <div class="media-card position-relative border border-gray-100 rounded-2 overflow-hidden">
                            <a href="<?= esc($lnk['link_url']) ?>" target="_blank" class="d-block media-thumb">
                                <div class="d-flex align-items-center justify-content-center bg-light-info" style="height:110px;">
                                    <img src="https://www.google.com/s2/favicons?domain=<?= urlencode($lnk['link_url']) ?>&sz=64"
                                         style="width:40px;height:40px;object-fit:contain;"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <i class="ki-duotone ki-link fs-2x text-info" style="display:none;"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                            </a>
                            <?php if (!$isReadonly): ?>
                            <button type="button" class="btn btn-icon btn-danger btn-del-link position-absolute top-0 end-0 m-1 media-del-btn"
                                    data-link-id="<?= $lnk['link_id'] ?>">
                                <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </button>
                            <?php endif; ?>
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
            </div>
            <!--end::Links-->

        </div>
    </div>
    <!--end::Files Videos Links card-->

    <!--begin::Quizzes card-->
    <?php
    $totalAssessments = count($quizzes ?? []);
    $aTypeCfgMap = [
        'quiz'       => ['label' => 'Quiz',        'icon' => 'ki-questionnaire-tablet', 'color' => 'success'],
        'drag_drop'  => ['label' => 'Drag & Drop', 'icon' => 'ki-abstract-26',          'color' => 'primary'],
        'labelling'  => ['label' => 'Labelling',   'icon' => 'ki-tag',                  'color' => 'info'],
        'simulation' => ['label' => 'Simulation',  'icon' => 'ki-rocket',               'color' => 'danger'],
    ];
    ?>
    <div class="card border-0 shadow-sm mt-6">
        <div class="card-header border-0 pt-5 pb-0 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-element-plus fs-3 text-primary">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                </i>
                <div>
                    <h6 class="fw-bold text-gray-800 mb-0">Assessments</h6>
                    <span class="text-muted fs-9" id="quiz_count_label"><?= $totalAssessments ?> assessment<?= $totalAssessments !== 1 ? 's' : '' ?> attached to this lesson</span>
                </div>
            </div>
            <?php if (!$isReadonly): ?>
            <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_quiz">
                <i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                Add Assessment
            </button>
            <?php endif; ?>
        </div>
        <div class="card-body pt-4 pb-6">
            <div id="quiz_list">
            <?php if (empty($quizzes)): ?>
            <div class="text-center py-8 text-muted" id="quiz_empty_state">
                <i class="ki-duotone ki-element-plus fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                <div class="fs-8">No assessments yet. Create your first assessment for this lesson.</div>
            </div>
            <?php else: ?>
            <div class="row g-3" id="quiz_grid">
                <?php foreach ($quizzes as $quiz):
                    $qId          = (int) $quiz['lesson_quizze_id'];
                    $aType        = $quiz['assessment_type'] ?? 'quiz';
                    $aCfg         = $aTypeCfgMap[$aType] ?? $aTypeCfgMap['quiz'];
                    $qCount       = count($quiz['questions']);
                    $statusColor  = $quiz['quizze_status'] === 'Published' ? 'success' : 'warning';
                    $isPublished  = $quiz['quizze_status'] === 'Published';
                    $updateUrl    = base_url('classroom/lesson/' . $lesson['lesson_id'] . '/quiz/' . $qId . '/update');
                    $attemptCount = $quizAttemptCounts[$qId] ?? 0;
                    $hasAttempts  = $attemptCount > 0;
                    $editLocked   = $isPublished || $hasAttempts;
                    $analysisUrl = base_url('classroom/teacher/' . $schSubId . '/lesson/' . $lesson['lesson_id'] . '/' .
                        ($aType === 'drag_drop' ? 'dragdrop' : ($aType === 'labelling' ? 'label' : 'quiz')) .
                        '/' . $qId . '/analysis');
                    if ($aType === 'drag_drop') {
                        $primaryActionUrl   = base_url('classroom/teacher/' . $schSubId . '/lesson/' . $lesson['lesson_id'] . '/dragdrop/' . $qId);
                        $primaryActionLabel = 'Build Assessment';
                        $primaryActionIcon  = 'ki-abstract-26';
                        $buildLocked        = $isPublished;
                    } elseif ($aType === 'labelling') {
                        $primaryActionUrl   = base_url('classroom/teacher/' . $schSubId . '/lesson/' . $lesson['lesson_id'] . '/label/' . $qId);
                        $primaryActionLabel = 'Build Assessment';
                        $primaryActionIcon  = 'ki-tag';
                        $buildLocked        = $isPublished;
                    } else {
                        $primaryActionUrl   = base_url('classroom/teacher/' . $schSubId . '/lesson/' . $lesson['lesson_id'] . '/quiz/' . $qId);
                        $primaryActionLabel = 'Add Question';
                        $primaryActionIcon  = 'ki-plus-circle';
                        $buildLocked        = false;
                    }
                ?>
                <div class="col-6 col-sm-4 col-md-3 quiz-item" id="quiz_item_<?= $qId ?>">
                    <div class="media-card border border-gray-100 rounded-2 position-relative" style="overflow:visible;">
                        <!--begin::Menu-->
                        <div class="position-absolute" style="top:7px;right:7px;z-index:1050;">
                            <button class="btn btn-icon bg-white bg-opacity-95 border border-gray-300 shadow-sm rounded-circle"
                                    style="width:30px;height:30px;"
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end"
                                    data-kt-menu-overflow="true"
                                    title="Options">
                                <i class="ki-duotone ki-dots-vertical fs-5 text-gray-700"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold fs-8 w-200px py-2" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <?php if ($buildLocked || $isReadonly): ?>
                                    <span class="menu-link px-3 text-muted pe-none">
                                        <span class="menu-icon"><i class="ki-duotone ki-lock fs-5 <?= $isReadonly ? 'text-warning' : 'text-success' ?>"><span class="path1"></span><span class="path2"></span></i></span>
                                        <?= $primaryActionLabel ?>
                                        <?php if ($buildLocked): ?><span class="ms-auto badge badge-light-success fs-10">Published</span><?php endif; ?>
                                    </span>
                                    <?php else: ?>
                                    <a class="menu-link px-3" href="<?= $primaryActionUrl ?>">
                                        <span class="menu-icon"><i class="ki-duotone <?= $primaryActionIcon ?> fs-5 text-<?= $aCfg['color'] ?>"><span class="path1"></span><span class="path2"></span></i></span>
                                        <?= $primaryActionLabel ?>
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <?php if ($isPublished): ?>
                                <div class="separator my-2"></div>
                                <div class="menu-item px-3">
                                    <a class="menu-link px-3" href="<?= $analysisUrl ?>">
                                        <span class="menu-icon"><i class="ki-duotone ki-chart-line-up fs-5 text-info"><span class="path1"></span><span class="path2"></span></i></span>
                                        View Analysis
                                    </a>
                                </div>
                                <?php endif; ?>
                                <div class="separator my-2"></div>
                                <div class="menu-item px-3">
                                    <?php if ($editLocked || $isReadonly): ?>
                                    <span class="menu-link px-3 text-muted pe-none">
                                        <span class="menu-icon"><i class="ki-duotone ki-lock fs-5 text-<?= $isReadonly ? 'warning' : ($isPublished ? 'success' : 'warning') ?>"><span class="path1"></span><span class="path2"></span></i></span>
                                        Edit Assessment
                                        <?php if ($editLocked): ?><span class="ms-auto badge badge-light-<?= $isPublished ? 'success' : 'warning' ?> fs-10"><?= $isPublished ? 'Published' : 'Has attempts' ?></span><?php endif; ?>
                                    </span>
                                    <?php else: ?>
                                    <a class="menu-link px-3 btn-edit-quiz" href="javascript:void(0)"
                                       data-quiz-id="<?= $qId ?>"
                                       data-quiz-name="<?= esc($quiz['quizze_name'], 'attr') ?>"
                                       data-quiz-duration="<?= (int) $quiz['quizze_duration'] ?>"
                                       data-quiz-status="<?= esc($quiz['quizze_status'], 'attr') ?>"
                                       data-has-attempts="0"
                                       data-is-published="0"
                                       data-assessment-type="<?= esc($aType, 'attr') ?>"
                                       data-content-count="<?= (int)($quiz['content_count'] ?? 0) ?>"
                                       data-zone-count="<?= (int)($quiz['zone_count'] ?? 0) ?>"
                                       data-update-url="<?= $updateUrl ?>">
                                        <span class="menu-icon"><i class="ki-duotone ki-pencil fs-5 text-primary"><span class="path1"></span><span class="path2"></span></i></span>
                                        Edit Assessment
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <div class="separator my-2"></div>
                                <div class="menu-item px-3">
                                    <?php if ($isPublished || $isReadonly): ?>
                                    <span class="menu-link px-3 text-muted pe-none">
                                        <span class="menu-icon"><i class="ki-duotone ki-lock fs-5 text-<?= $isReadonly ? 'warning' : 'success' ?>"><span class="path1"></span><span class="path2"></span></i></span>
                                        Delete Assessment
                                        <?php if ($isPublished): ?><span class="ms-auto badge badge-light-success fs-10">Published</span><?php endif; ?>
                                    </span>
                                    <?php else: ?>
                                    <a class="menu-link px-3 text-danger btn-del-quiz" href="javascript:void(0)"
                                       data-quiz-id="<?= $qId ?>"
                                       data-has-attempts="<?= $hasAttempts ? '1' : '0' ?>">
                                        <span class="menu-icon"><i class="ki-duotone ki-trash fs-5 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></span>
                                        Delete Assessment
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <div class="separator my-2"></div>
                                <div class="menu-item px-3">
                                    <a class="menu-link px-3 btn-share-lesson" href="javascript:void(0)">
                                        <span class="menu-icon"><i class="ki-duotone ki-copy fs-5 text-info"><span class="path1"></span><span class="path2"></span></i></span>
                                        Copy Lesson Link
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu-->
                        <div class="d-flex align-items-center justify-content-center bg-light-<?= $aCfg['color'] ?> position-relative"
                             style="height:110px;border-radius:.375rem .375rem 0 0;">
                            <i class="ki-duotone <?= $aCfg['icon'] ?> fs-2x text-<?= $aCfg['color'] ?>"><span class="path1"></span><span class="path2"></span></i>
                            <?php if ($attemptCount > 0): ?>
                            <div class="position-absolute bottom-0 start-0 ps-2 pb-1">
                                <span class="badge badge-light-primary fs-10"><?= $attemptCount ?> attempt<?= $attemptCount !== 1 ? 's' : '' ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-2">
                            <div class="text-truncate fw-semibold text-gray-800 fs-9" title="<?= esc($quiz['quizze_name']) ?>"><?= esc($quiz['quizze_name']) ?></div>
                            <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                                <span class="badge bg-light-<?= $aCfg['color'] ?> text-<?= $aCfg['color'] ?> fs-10 px-1"><?= $aCfg['label'] ?></span>
                                <span class="badge bg-light-<?= $statusColor ?> text-<?= $statusColor ?> fs-10 px-1 quiz-status-badge"><?= esc($quiz['quizze_status']) ?></span>
                                <?php if ($aType === 'quiz'): ?>
                                <span class="text-muted fs-10 quiz-meta"><?= $qCount ?> Q<?= $quiz['quizze_duration'] > 0 ? ' · ' . $quiz['quizze_duration'] . 'min' : '' ?></span>
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
    </div>
    <!--end::Assessments card-->

    <?php
    function timeAgo(string $datetime): string {
        $diff = time() - strtotime($datetime);
        if ($diff < 60)     return 'Just now';
        if ($diff < 3600)   return floor($diff / 60) . ' min ago';
        if ($diff < 86400)  return floor($diff / 3600) . ' hr ago';
        if ($diff < 604800) { $d = floor($diff / 86400); return $d . ' day' . ($d > 1 ? 's' : '') . ' ago'; }
        return date('M j, Y', strtotime($datetime));
    }
    $totalDiscussions = count($discussions ?? []);
    ?>

    <!--begin::Discussion card-->
    <div class="card shadow-sm mt-6 mb-6">
        <div class="card-header border-0 pt-5 pb-0 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-message-edit fs-3 text-warning">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div>
                    <h6 class="fw-bold text-gray-800 mb-0">Discussion Board</h6>
                    <span class="text-muted fs-9" id="disc_total_label"><?php echo $totalDiscussions; ?> post<?php echo $totalDiscussions !== 1 ? 's' : ''; ?></span>
                </div>
            </div>
        </div>
        <div class="card-body pt-4 pb-6">

            <!--begin::Post form-->
            <?php if (!$isReadonly): ?>
            <div class="card card-flush mb-8 border border-dashed border-gray-200">
                <div class="card-header justify-content-start align-items-center pt-4 pb-0 border-0">
                    <div class="symbol symbol-40px me-4 flex-shrink-0">
                        <?php if ($sessionPhotoUrl): ?>
                        <img src="<?php echo $sessionPhotoUrl; ?>" class="rounded-circle" style="object-fit:cover;width:40px;height:40px;" alt="" />
                        <?php else: ?>
                        <div class="symbol-label bg-light-primary fw-bold text-primary fs-5">
                            <?php echo strtoupper(substr($sessionFname, 0, 1)); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <span class="text-muted fw-semibold fs-7">What's on your mind, <?php echo esc($sessionFname); ?>?</span>
                </div>
                <div class="card-body pt-3 pb-2">
                    <textarea class="form-control bg-transparent border-0 px-0" id="disc_post_input" rows="2" placeholder="Share something with the class..."></textarea>
                </div>
                <div class="card-footer d-flex justify-content-end pt-0 border-0 pb-4">
                    <button type="button" class="btn btn-sm btn-primary" id="btn_post_discussion">
                        <span class="indicator-label">
                            <i class="ki-duotone ki-send fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Post
                        </span>
                        <span class="indicator-progress">Posting... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                    </button>
                </div>
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
            <?php foreach ($discussions as $disc):
                $discId       = (int) $disc['lesson_discussion_id'];
                $isOwn        = (int) $disc['author_id'] === (int) $sessionUserId;
                $userReaction = $disc['user_reaction'] ?? null;
            ?>
            <div class="card card-flush mb-6 border border-dashed border-gray-200 disc-post" id="disc_post_<?php echo $discId; ?>">
                <div class="card-header pt-6 pb-0 border-0">
                    <a href="<?php echo base_url('user/detail/' . $disc['author_id']); ?>"
                       class="user-link d-flex align-items-center flex-grow-1 text-decoration-none">
                        <div class="symbol symbol-40px me-4 flex-shrink-0">
                            <?php if (!empty($disc['author_photo'])): ?>
                            <img src="<?php echo base_url('uploads/profilePhoto/' . $disc['author_photo']); ?>" class="rounded-circle" style="object-fit:cover;" alt="" />
                            <?php else: ?>
                            <div class="symbol-label bg-light-primary fw-bold text-primary fs-5"><?php echo strtoupper(substr($disc['author_name'], 0, 1)); ?></div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="user-link-name fw-bold text-gray-800 fs-6"><?php echo esc($disc['author_name']); ?></span>
                            <span class="text-muted fw-semibold fs-8 d-block"><?php echo timeAgo($disc['created_at']); ?></span>
                        </div>
                    </a>
                    <?php if ($isOwn && !$isReadonly): ?>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-del-disc" data-disc-id="<?php echo $discId; ?>" title="Delete">
                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-body py-4">
                    <div class="fs-6 fw-normal text-gray-700 disc-body-text clamped" id="disc_text_<?php echo $discId; ?>"><?php echo nl2br(esc($disc['message'])); ?></div>
                    <button type="button" class="btn btn-link btn-sm p-0 mt-1 fs-8 fw-bold text-primary btn-show-more" data-target="disc_text_<?php echo $discId; ?>" style="display:none;">Show more</button>
                </div>
                <div class="card-footer pt-0 border-0">
                    <div class="separator separator-solid mb-3"></div>
                    <ul class="nav py-1">
                        <?php if (!$isReadonly): ?>
                        <li class="nav-item">
                            <button type="button" class="btn btn-sm btn-color-gray-600 btn-active-color-primary fw-bold px-4 me-1 btn-like-disc <?php echo $userReaction === 'like' ? 'text-primary' : ''; ?>"
                                    data-disc-id="<?php echo $discId; ?>" data-type="like">
                                <i class="ki-duotone ki-heart fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                <span class="like-count"><?php echo (int) $disc['like_count']; ?></span> Like<?php echo (int) $disc['like_count'] !== 1 ? 's' : ''; ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-sm btn-color-gray-600 btn-active-color-danger fw-bold px-4 me-1 btn-like-disc <?php echo $userReaction === 'dislike' ? 'text-danger' : ''; ?>"
                                    data-disc-id="<?php echo $discId; ?>" data-type="dislike">
                                <i class="ki-duotone ki-dislike fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                <span class="dislike-count"><?php echo (int) $disc['dislike_count']; ?></span> Dislike<?php echo (int) $disc['dislike_count'] !== 1 ? 's' : ''; ?>
                            </button>
                        </li>
                        <?php endif; ?>
                        <?php $totalR = (int)$disc['like_count'] + (int)$disc['dislike_count']; ?>
                        <?php if ($totalR > 0): ?>
                        <li class="nav-item">
                            <button type="button" class="btn btn-sm btn-color-gray-400 fw-semibold px-3 btn-disc-reactions"
                                    data-disc-id="<?php echo $discId; ?>"
                                    data-likes="<?php echo (int)$disc['like_count']; ?>" data-dislikes="<?php echo (int)$disc['dislike_count']; ?>">
                                <?php echo $totalR; ?> Reaction<?php echo $totalR !== 1 ? 's' : ''; ?>
                            </button>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item ms-auto">
                            <a class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 collapsed"
                               data-bs-toggle="collapse" href="#disc_comments_<?php echo $discId; ?>">
                                <i class="ki-duotone ki-message-text-2 fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <span class="comment-count"><?php echo (int) $disc['comment_count']; ?></span> Comment<?php echo (int) $disc['comment_count'] !== 1 ? 's' : ''; ?>
                            </a>
                        </li>
                    </ul>
                    <div class="separator separator-solid mb-3"></div>
                    <div class="collapse" id="disc_comments_<?php echo $discId; ?>">
                        <div class="pt-2" id="comments_area_<?php echo $discId; ?>">
                        <?php foreach ($disc['comments'] as $cmt): ?>
                        <div class="d-flex align-items-start gap-3 mb-4" id="comment_item_<?php echo $cmt['comment_id']; ?>">
                            <a href="<?php echo base_url('user/detail/' . $cmt['author_id']); ?>"
                               class="user-link symbol symbol-32px flex-shrink-0 text-decoration-none">
                                <?php if (!empty($cmt['author_photo'])): ?>
                                <img src="<?php echo base_url('uploads/profilePhoto/' . $cmt['author_photo']); ?>" class="rounded-circle" style="object-fit:cover;" alt="" />
                                <?php else: ?>
                                <div class="symbol-label bg-light-success fw-bold text-success fs-8"><?php echo strtoupper(substr($cmt['author_name'], 0, 1)); ?></div>
                                <?php endif; ?>
                            </a>
                            <div class="flex-grow-1 bg-light-secondary rounded-2 px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <a href="<?php echo base_url('user/detail/' . $cmt['author_id']); ?>"
                                       class="user-link user-link-name fw-bold text-gray-800 fs-8 text-decoration-none"><?php echo esc($cmt['author_name']); ?></a>
                                    <span class="text-muted fs-9"><?php echo timeAgo($cmt['created_at']); ?></span>
                                </div>
                                <span class="text-gray-700 fs-7 disc-body-text clamped d-block" id="cmt_text_<?php echo $cmt['comment_id']; ?>"><?php echo nl2br(esc($cmt['comment'])); ?></span>
                                <button type="button" class="btn btn-link btn-sm p-0 mt-1 fs-9 fw-bold text-primary btn-show-more" data-target="cmt_text_<?php echo $cmt['comment_id']; ?>" style="display:none;">Show more</button>
                                <div class="mt-2 d-flex align-items-center gap-3">
                                    <?php if (!$isReadonly): ?>
                                    <button type="button" class="btn btn-xs btn-color-gray-500 p-0 pe-1 btn-like-comment <?php echo $cmt['user_reaction'] === 'like' ? 'text-primary' : ''; ?>"
                                            data-comment-id="<?php echo $cmt['comment_id']; ?>" data-disc-id="<?php echo $discId; ?>" data-type="like">
                                        <i class="ki-duotone ki-heart fs-6 me-1"><span class="path1"></span><span class="path2"></span></i><span class="clike-count"><?php echo (int)$cmt['like_count']; ?></span> Like
                                    </button>
                                    <button type="button" class="btn btn-xs btn-color-gray-500 p-0 pe-1 btn-like-comment <?php echo $cmt['user_reaction'] === 'dislike' ? 'text-danger' : ''; ?>"
                                            data-comment-id="<?php echo $cmt['comment_id']; ?>" data-disc-id="<?php echo $discId; ?>" data-type="dislike">
                                        <i class="ki-duotone ki-dislike fs-6 me-1"><span class="path1"></span><span class="path2"></span></i><span class="cdislike-count"><?php echo (int)$cmt['dislike_count']; ?></span> Dislike
                                    </button>
                                    <?php endif; ?>
                                    <?php $totalCR = (int)$cmt['like_count'] + (int)$cmt['dislike_count']; if ($totalCR > 0): ?>
                                    <button type="button" class="btn btn-xs btn-color-gray-400 p-0 btn-comment-reactions"
                                            data-comment-id="<?php echo $cmt['comment_id']; ?>" data-disc-id="<?php echo $discId; ?>"
                                            data-likes="<?php echo (int)$cmt['like_count']; ?>" data-dislikes="<?php echo (int)$cmt['dislike_count']; ?>">
                                        <?php echo $totalCR; ?> reaction<?php echo $totalCR !== 1 ? 's' : ''; ?>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        </div>
                        <?php if (!$isReadonly): ?>
                        <div class="d-flex align-items-center gap-3 mt-3">
                            <div class="symbol symbol-32px flex-shrink-0">
                                <?php if ($sessionPhotoUrl): ?>
                                <img src="<?php echo $sessionPhotoUrl; ?>" class="rounded-circle" style="object-fit:cover;width:32px;height:32px;" alt="" />
                                <?php else: ?>
                                <div class="symbol-label bg-light-primary fw-bold text-primary fs-8"><?php echo strtoupper(substr($sessionFname, 0, 1)); ?></div>
                                <?php endif; ?>
                            </div>
                            <textarea class="form-control form-control-sm form-control-solid border flex-grow-1 disc-comment-input"
                                      rows="1" placeholder="Write a comment..." data-disc-id="<?php echo $discId; ?>"></textarea>
                            <button type="button" class="btn btn-sm btn-primary flex-shrink-0 btn-send-comment" data-disc-id="<?php echo $discId; ?>">
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

<!--begin::Edit Lesson Modal-->
<div class="modal fade" id="modal_edit_lesson" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Edit Lesson</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-5">
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Lesson Title</label>
                    <input type="text" class="form-control form-control-sm" id="edit_lesson_title"
                           maxlength="255" placeholder="e.g. Unit 3 — Forces and Motion">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7">Description <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea class="form-control form-control-sm" id="edit_lesson_desc" rows="3"
                              placeholder="Brief overview of what students will learn..."></textarea>
                </div>
                <div class="row g-4">
                    <div class="col-4">
                        <label class="form-label fw-semibold fs-7 required">Term</label>
                        <select class="form-select form-select-sm" id="edit_lesson_term">
                            <option value="1">Term 1</option>
                            <option value="2">Term 2</option>
                            <option value="3">Term 3</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold fs-7">Week</label>
                        <input type="number" class="form-control form-control-sm" id="edit_lesson_week"
                               min="1" max="20" placeholder="e.g. 1">
                    </div>
                    <div class="col-4">
                        <label class="form-label fw-semibold fs-7">Status</label>
                        <select class="form-select form-select-sm" id="edit_lesson_status">
                            <option value="Published">Published</option>
                            <option value="Draft">Draft</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div id="publish_blocker_alert" class="alert alert-danger d-flex align-items-start gap-2 p-3 mt-4 d-none" style="border-radius:.75rem;">
                    <i class="ki-duotone ki-shield-cross fs-5 flex-shrink-0 mt-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="fs-8 lh-lg" id="publish_blocker_text"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_lesson_edit">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Save Changes
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Edit Lesson Modal-->

<!--begin::Edit Quiz Modal-->
<div class="modal fade" id="modal_edit_quiz" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Edit Assessment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-5">
                <input type="hidden" id="edit_quiz_id">
                <input type="hidden" id="edit_quiz_update_url">
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Quiz Name</label>
                    <input type="text" class="form-control form-control-sm" id="edit_quiz_name" maxlength="260">
                </div>
                <div class="row g-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-7">Duration <span class="text-muted fw-normal">(minutes)</span></label>
                        <input type="number" class="form-control form-control-sm" id="edit_quiz_duration" min="0" max="300" placeholder="0 = no limit">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-7">Status</label>
                        <select class="form-select form-select-sm" id="edit_quiz_status">
                            <option value="Draft">Draft</option>
                            <option value="Published">Published</option>
                        </select>
                    </div>
                </div>
                <div id="quiz_publish_blocker_alert" class="alert alert-danger d-flex align-items-start gap-2 p-3 mt-4 d-none" style="border-radius:.75rem;">
                    <i class="ki-duotone ki-shield-cross fs-5 flex-shrink-0 mt-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="fs-8 lh-lg" id="quiz_publish_blocker_text"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_quiz_edit">
                    <span class="indicator-label"><i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Save Changes</span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Edit Quiz Modal-->


<!--begin::Add Assessment Modal-->
<div class="modal fade" id="modal_add_quiz" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-element-plus fs-2 text-primary">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Add Assessment</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-5">

                <!--begin::Type selector-->
                <div class="mb-5">
                    <label class="form-label fw-semibold fs-7 required mb-3">Assessment Type</label>
                    <div class="row g-3" id="assessment_type_picker">
                        <div class="col-4">
                            <label class="assessment-type-card d-flex align-items-start gap-3 p-3 border border-2 border-gray-200 rounded-2 cursor-pointer h-100" style="transition:all .15s;">
                                <input type="radio" name="assessment_type" value="quiz" class="form-check-input mt-0 flex-shrink-0" checked>
                                <div>
                                    <i class="ki-duotone ki-questionnaire-tablet fs-2x text-success d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                                    <div class="fw-bold text-gray-800 fs-8">Quiz</div>
                                    <div class="text-muted fs-9 lh-sm">Multiple choice questions with auto-grading</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-4">
                            <label class="assessment-type-card d-flex align-items-start gap-3 p-3 border border-2 border-gray-200 rounded-2 cursor-pointer h-100" style="transition:all .15s;">
                                <input type="radio" name="assessment_type" value="drag_drop" class="form-check-input mt-0 flex-shrink-0">
                                <div>
                                    <i class="ki-duotone ki-abstract-26 fs-2x text-primary d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                                    <div class="fw-bold text-gray-800 fs-8">Drag &amp; Drop</div>
                                    <div class="text-muted fs-9 lh-sm">Students match items to drop zones</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-4">
                            <label class="assessment-type-card d-flex align-items-start gap-3 p-3 border border-2 border-gray-200 rounded-2 cursor-pointer h-100" style="transition:all .15s;">
                                <input type="radio" name="assessment_type" value="labelling" class="form-check-input mt-0 flex-shrink-0">
                                <div>
                                    <i class="ki-duotone ki-tag fs-2x text-info d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                                    <div class="fw-bold text-gray-800 fs-8">Labelling</div>
                                    <div class="text-muted fs-9 lh-sm">Click markers on an image to label</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <!--end::Type selector-->

                <div class="separator separator-dashed mb-5"></div>

                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Assessment Name</label>
                    <input type="text" class="form-control form-control-sm" id="quiz_name_input" placeholder="e.g. Chapter 1 Quiz" maxlength="260">
                </div>
                <div class="row g-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-7">Duration <span class="text-muted fw-normal">(minutes, optional)</span></label>
                        <input type="number" class="form-control form-control-sm" id="quiz_duration_input" min="1" max="300" placeholder="e.g. 30">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-7">Status</label>
                        <select class="form-select form-select-sm" id="quiz_status_input">
                            <option value="Draft">Draft</option>
                            <option value="Published">Published</option>
                        </select>
                    </div>
                </div>

                <!--begin::Builder hint-->
                <div id="dd_type_hint" class="alert alert-primary d-flex align-items-center gap-2 py-3 mt-4" style="display:none!important;">
                    <i class="ki-duotone ki-information-5 fs-4 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <span class="fs-8" id="dd_type_hint_text">After creating, you'll be taken to the builder to set up the assessment.</span>
                </div>
                <!--end::Builder hint-->

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_quiz">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        <span id="btn_save_quiz_label">Create Assessment</span>
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Assessment Modal-->

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
                    <li class="nav-item">
                        <a class="nav-link text-active-primary active" id="react_tab_all" data-bs-toggle="tab" href="#react_pane_all">All 0</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary d-flex align-items-center gap-1" id="react_tab_like" data-bs-toggle="tab" href="#react_pane_like">
                            <i class="ki-duotone ki-heart fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i> Like 0
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary d-flex align-items-center gap-1" id="react_tab_dislike" data-bs-toggle="tab" href="#react_pane_dislike">
                            <i class="ki-duotone ki-dislike fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i> Dislike 0
                        </a>
                    </li>
                </ul>
                <div class="tab-content" style="max-height:320px;overflow-y:auto;">
                    <div class="tab-pane fade show active" id="react_pane_all"></div>
                    <div class="tab-pane fade" id="react_pane_like"></div>
                    <div class="tab-pane fade" id="react_pane_dislike"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Reactions Modal-->

<!--begin::Add Step Modal-->
<div class="modal fade" id="modal_add_step" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Add Lesson Step</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Step Type</label>
                    <div class="row g-2" id="step_type_picker">
                        <?php foreach ($stepConfig as $type => $cfg): ?>
                        <div class="col-6 col-md-4">
                            <label class="d-flex align-items-center gap-3 p-3 rounded-2 border border-dashed border-gray-300 cursor-pointer step-type-card">
                                <input type="radio" name="step_type" value="<?= $type ?>" class="form-check-input mt-0 flex-shrink-0"
                                       <?= $type === 'intro' ? 'checked' : '' ?>>
                                <div>
                                    <i class="ki-duotone <?= $cfg['icon'] ?> fs-4 text-<?= $cfg['color'] ?> mb-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <div class="fw-semibold text-gray-800 fs-8"><?= $cfg['label'] ?></div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Step Title</label>
                    <input type="text" class="form-control form-control-sm" id="step_title"
                           placeholder="e.g. Watch: Newton's Laws explained" maxlength="255" />
                </div>
                <div class="mb-4" id="step_url_group" style="display:none;">
                    <label class="form-label fw-semibold fs-7">Video / Link URL</label>
                    <input type="url" class="form-control form-control-sm" id="step_url"
                           placeholder="https://youtube.com/..." />
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7">Instructions / Content <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea class="form-control form-control-sm" id="step_content" rows="3"
                              placeholder="Brief description, instructions, or notes for students..."></textarea>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold fs-7">Duration <span class="text-muted fw-normal">(min, optional)</span></label>
                    <input type="number" class="form-control form-control-sm" id="step_duration" min="1" max="180" placeholder="e.g. 15" />
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_step">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Add Step
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Step Modal-->

<script>
const LESSON_ID = <?= (int) $lesson['lesson_id'] ?>;
const CLASS_ID  = <?= (int) $schSubId ?>;

// ── EDIT LESSON ────────────────────────────────────────────────
const LESSON_UPDATE_URL = '<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/update') ?>';
const STATUS_COLORS = { Published: 'success', Draft: 'warning', Archived: 'secondary' };

<?php
$publishBlockers = [];
foreach (($quizzes ?? []) as $_pq) {
    $_pType = $_pq['assessment_type'] ?? 'quiz';
    $_ready = match ($_pType) {
        'drag_drop'  => (($_pq['content_count'] ?? 0) > 0 && ($_pq['zone_count'] ?? 0) > 0),
        'labelling'  => (($_pq['content_count'] ?? 0) > 0),
        'quiz'       => (($_pq['content_count'] ?? 0) > 0),
        default      => true,
    };
    if (!$_ready) {
        $_pLabel = match ($_pType) {
            'drag_drop' => 'Drag & Drop',
            'labelling' => 'Labelling',
            'quiz'      => 'Quiz',
            default     => ucfirst($_pType),
        };
        $publishBlockers[] = "{$_pLabel} \"{$_pq['quizze_name']}\" has no content yet — add at least one question or item before publishing.";
    }
}
?>
const PUBLISH_BLOCKERS = <?= json_encode($publishBlockers) ?>;

// Show/hide publish warning when status changes
document.getElementById('edit_lesson_status')?.addEventListener('change', function() {
    const alertEl = document.getElementById('publish_blocker_alert');
    if (this.value === 'Published' && PUBLISH_BLOCKERS.length > 0) {
        document.getElementById('publish_blocker_text').innerHTML =
            '<strong>Cannot publish yet:</strong><br>' + PUBLISH_BLOCKERS.join('<br>');
        alertEl.classList.remove('d-none');
    } else {
        alertEl.classList.add('d-none');
    }
});

// Also hide alert when modal closes
document.getElementById('modal_edit_lesson')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('publish_blocker_alert').classList.add('d-none');
});

document.getElementById('btn_edit_lesson')?.addEventListener('click', function() {
    document.getElementById('edit_lesson_title').value  = <?= json_encode($lesson['lesson_title']) ?>;
    document.getElementById('edit_lesson_desc').value   = <?= json_encode($lesson['lesson_desc'] ?? '') ?>;
    document.getElementById('edit_lesson_term').value   = <?= (int) $lesson['lesson_term'] ?>;
    document.getElementById('edit_lesson_week').value   = <?= (int) ($lesson['lesson_week'] ?? '') ?>;
    document.getElementById('edit_lesson_status').value = <?= json_encode($lesson['lesson_status']) ?>;
    new bootstrap.Modal(document.getElementById('modal_edit_lesson')).show();
});

document.getElementById('btn_save_lesson_edit')?.addEventListener('click', function() {
    const btn   = this;
    const title = document.getElementById('edit_lesson_title').value.trim();
    if (!title) {
        document.getElementById('edit_lesson_title').classList.add('is-invalid');
        return;
    }
    document.getElementById('edit_lesson_title').classList.remove('is-invalid');

    const selectedStatus = document.getElementById('edit_lesson_status').value;
    if (selectedStatus === 'Published' && PUBLISH_BLOCKERS.length > 0) {
        Swal.fire({
            title: 'Cannot Publish',
            html: '<div class="text-start">' + PUBLISH_BLOCKERS.join('<br>') + '</div>',
            icon: 'warning',
            buttonsStyling: false,
            confirmButtonText: 'OK',
            customClass: { confirmButton: 'btn btn-warning' },
        });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;

    const fd = new FormData();
    fd.append('lesson_title',  title);
    fd.append('lesson_desc',   document.getElementById('edit_lesson_desc').value);
    fd.append('lesson_term',   document.getElementById('edit_lesson_term').value);
    fd.append('lesson_week',   document.getElementById('edit_lesson_week').value);
    fd.append('lesson_status', document.getElementById('edit_lesson_status').value);

    $.ajax({
        url: LESSON_UPDATE_URL, type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_edit_lesson')).hide();
                // Update card display
                document.getElementById('lesson_title_display').textContent = res.lesson.lesson_title;
                document.querySelector('h1.page-heading').textContent       = res.lesson.lesson_title;
                const descEl = document.getElementById('lesson_desc_display');
                if (res.lesson.lesson_desc) {
                    descEl.innerHTML = res.lesson.lesson_desc.replace(/\n/g, '<br>');
                    descEl.style.display = '';
                } else {
                    descEl.style.display = 'none';
                }
                document.getElementById('lesson_term_display').textContent = 'Term ' + res.lesson.lesson_term;
                const badge = document.getElementById('lesson_status_badge');
                badge.textContent = res.lesson.lesson_status;
                badge.className   = 'badge badge-light-' + (STATUS_COLORS[res.lesson.lesson_status] || 'secondary') + ' fs-9';
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false,
                    confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            }
        }
    });
});

// Show URL field for video/reading step types
document.querySelectorAll('input[name="step_type"]').forEach(function(r) {
    r.addEventListener('change', function() {
        const showUrl = ['video', 'reading'].includes(this.value);
        document.getElementById('step_url_group').style.display = showUrl ? '' : 'none';
    });
});
// Highlight selected step type card
document.getElementById('step_type_picker').addEventListener('change', function(e) {
    document.querySelectorAll('.step-type-card').forEach(c => c.classList.remove('border-primary', 'bg-light-primary'));
    if (e.target.type === 'radio') {
        e.target.closest('.step-type-card').classList.add('border-primary', 'bg-light-primary');
    }
});
// Init first card
document.querySelector('.step-type-card')?.classList.add('border-primary', 'bg-light-primary');

// Add Step
document.getElementById('btn_save_step').addEventListener('click', function() {
    const btn   = this;
    const type  = document.querySelector('input[name="step_type"]:checked')?.value;
    const title = document.getElementById('step_title').value.trim();

    if (!title) {
        Swal.fire({ title: 'Required', text: 'Please enter a step title.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;

    const fd = new FormData();
    fd.append('step_type',     type);
    fd.append('step_title',    title);
    fd.append('step_content',  document.getElementById('step_content').value);
    fd.append('step_url',      document.getElementById('step_url').value);
    fd.append('step_duration', document.getElementById('step_duration').value);

    $.ajax({
        url: '<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/step/store') ?>',
        type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_add_step')).hide();
                window.location.reload();
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function() {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
        }
    });
});

// Delete Step
$(document).on('click', '.btn-delete-step', function() {
    const stepId = $(this).data('step-id');
    Swal.fire({
        title: 'Remove this step?', text: 'This cannot be undone.',
        icon: 'warning', showCancelButton: true, buttonsStyling: false,
        confirmButtonText: 'Yes, remove', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.post('<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/step/delete') ?>/' + stepId, function(res) {
            if (res.success) document.getElementById('step_item_' + stepId)?.remove();
        }, 'json');
    });
});

// ── FILES ──────────────────────────────────────────────────────
const FILE_EXT_COLORS = {
    pdf:'danger', doc:'primary', docx:'primary',
    xls:'success', xlsx:'success', ppt:'warning', pptx:'warning',
    zip:'dark', rar:'dark',
    jpg:'info', jpeg:'info', png:'info', gif:'info', webp:'info', svg:'info',
};
function fileColor(ext) { return FILE_EXT_COLORS[ext] || 'secondary'; }
function fileLabel(ext) { return (ext === 'jpeg' ? 'JPG' : ext.toUpperCase()); }
function cleanFileNameJs(name) {
    const noExt = name.replace(/\.[^/.]+$/, '');
    let clean = noExt.replace(/[_\-.]/g, ' ').replace(/[^\w\s]/g, '').trim().replace(/\s+/g, ' ');
    clean = clean.replace(/\b\w/g, c => c.toUpperCase());
    return clean.length > 30 ? clean.substring(0, 28) + '…' : (clean || noExt);
}
function fileSize(b) {
    if (b < 1024) return b + ' B';
    if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
    return (b/1048576).toFixed(1) + ' MB';
}
const IMAGE_EXTS = ['jpg','jpeg','png','gif','webp','svg'];
function youtubeIdJs(url) {
    const m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    return m ? m[1] : '';
}
function renderFileCard(f) {
    const color = fileColor(f.file_type);
    const label = fileLabel(f.file_type);
    const isImg = IMAGE_EXTS.includes(f.file_type);
    const thumb = isImg
        ? `<img src="<?= base_url('uploads/lesson_files/') ?>${f.file_path}" style="width:100%;height:110px;object-fit:cover;display:block;">`
        : `<div class="d-flex align-items-center justify-content-center bg-light-${color}" style="height:110px;"><span class="fw-bold text-${color} fs-2">${label}</span></div>`;
    return `<div class="col-6 col-sm-4 col-md-2 file-item" id="file_item_${f.file_id}">
        <div class="media-card position-relative border border-gray-100 rounded-2 overflow-hidden">
            <a href="<?= base_url('uploads/lesson_files/') ?>${f.file_path}" target="_blank" class="d-block media-thumb">${thumb}</a>
            <button type="button" class="btn btn-icon btn-danger btn-del-file position-absolute top-0 end-0 m-1 media-del-btn" data-file-id="${f.file_id}">
                <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
            </button>
            <div class="p-2">
                <div class="text-truncate fw-semibold text-gray-800 fs-9" title="${f.file_name}">${cleanFileNameJs(f.file_name)}</div>
                <span class="badge bg-light-${color} text-${color} fs-10 px-1">${label}</span>
                <span class="text-muted fs-10 ms-1">${fileSize(f.file_size)}</span>
            </div>
        </div></div>`;
}

document.getElementById('lesson_file_input')?.addEventListener('change', function() {
    if (!this.files.length) return;
    const fd = new FormData();
    for (const f of this.files) fd.append('lesson_files[]', f);
    $.ajax({
        url: '<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/file/upload') ?>',
        type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (res.success) {
                document.getElementById('files_empty')?.remove();
                const fg = document.getElementById('files_grid') || (() => { const g = document.createElement('div'); g.className='row g-3'; g.id='files_grid'; document.getElementById('files_list').appendChild(g); return g; })();
                res.files.forEach(f => $(fg).append(renderFileCard(f)));
                document.getElementById('file_count_label').textContent = res.total + ' of 10 files';
                if (res.total >= 10) document.getElementById('btn_pick_files').style.display = 'none';
            } else {
                Swal.fire({ title: 'Upload failed', text: res.message, icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            }
        }
    });
    this.value = '';
});

$(document).on('click', '.btn-del-file', function() {
    const fileId = $(this).data('file-id');
    Swal.fire({ title: 'Delete this file?', icon: 'warning', showCancelButton: true, buttonsStyling: false,
        confirmButtonText: 'Delete', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post('<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/file/delete') ?>/' + fileId, function(res) {
            if (res.success) {
                document.getElementById('file_item_' + fileId)?.remove();
                const cnt = document.querySelectorAll('.file-item').length;
                document.getElementById('file_count_label').textContent = cnt + ' of 10 files';
                if (cnt < 10) document.getElementById('btn_pick_files').style.display = '';
            }
        }, 'json');
    });
});

// ── VIDEOS ─────────────────────────────────────────────────────
function renderVideoCard(v) {
    const ytId  = youtubeIdJs(v.video_url);
    const label = v.video_title || v.video_url;
    const badge = ytId ? `<span class="badge bg-light-danger text-danger fs-10 px-1">YouTube</span>`
                       : `<span class="badge bg-light-secondary text-muted fs-10 px-1">Video</span>`;
    const thumb = ytId
        ? `<img src="https://img.youtube.com/vi/${ytId}/mqdefault.jpg" style="width:100%;height:110px;object-fit:cover;display:block;">
           <div class="position-absolute top-50 start-50 translate-middle">
               <div class="bg-dark bg-opacity-50 rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                   <i class="ki-duotone ki-triangle fs-5 text-white ms-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
               </div></div>`
        : `<div class="d-flex align-items-center justify-content-center bg-light-danger" style="height:110px;">
               <i class="ki-duotone ki-video fs-2x text-danger"><span class="path1"></span><span class="path2"></span></i></div>`;
    return `<div class="col-6 col-sm-4 col-md-2 video-item" id="video_item_${v.video_id}">
        <div class="media-card position-relative border border-gray-100 rounded-2 overflow-hidden">
            <a href="${v.video_url}" target="_blank" class="d-block media-thumb position-relative">${thumb}</a>
            <button type="button" class="btn btn-icon btn-danger btn-del-video position-absolute top-0 end-0 m-1 media-del-btn" data-video-id="${v.video_id}">
                <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
            </button>
            <div class="p-2">
                <div class="text-truncate fw-semibold text-gray-800 fs-9" title="${label}">${label}</div>
                ${badge}
            </div>
        </div></div>`;
}

document.getElementById('btn_toggle_video_form')?.addEventListener('click', () => {
    document.getElementById('video_add_form').style.display = '';
    document.getElementById('new_video_url').focus();
});
document.getElementById('btn_cancel_video')?.addEventListener('click', () => {
    document.getElementById('video_add_form').style.display = 'none';
    document.getElementById('new_video_url').value = '';
    document.getElementById('new_video_title').value = '';
});
document.getElementById('btn_save_video')?.addEventListener('click', function() {
    const url   = document.getElementById('new_video_url').value.trim();
    const title = document.getElementById('new_video_title').value.trim();
    if (!url) { alert('Please enter a video URL.'); return; }
    const fd = new FormData();
    fd.append('video_url', url); fd.append('video_title', title);
    $.ajax({
        url: '<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/video/add') ?>',
        type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (res.success) {
                document.getElementById('videos_empty')?.remove();
                document.getElementById('videos_empty')?.remove();
                const vg = document.getElementById('videos_grid') || (() => { const g = document.createElement('div'); g.className='row g-3'; g.id='videos_grid'; document.getElementById('videos_list').appendChild(g); return g; })();
                $(vg).append(renderVideoCard(res.video));
                document.getElementById('video_count_label').textContent = res.total + ' of 10 videos';
                document.getElementById('btn_cancel_video').click();
                if (res.total >= 10) document.getElementById('btn_toggle_video_form').style.display = 'none';
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false,
                    confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            }
        }
    });
});

$(document).on('click', '.btn-del-video', function() {
    const id = $(this).data('video-id');
    $.post('<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/video/delete') ?>/' + id, function(res) {
        if (res.success) {
            document.getElementById('video_item_' + id)?.remove();
            const cnt = document.querySelectorAll('.video-item').length;
            document.getElementById('video_count_label').textContent = cnt + ' of 10 videos';
            if (cnt < 10) document.getElementById('btn_toggle_video_form').style.display = '';
        }
    }, 'json');
});

// ── LINKS ──────────────────────────────────────────────────────
function renderLinkCard(lnk) {
    const label  = lnk.link_title || lnk.link_url;
    let domain = lnk.link_url;
    try { domain = new URL(lnk.link_url).hostname; } catch(e) {}
    const sub = lnk.link_title ? `<span class="text-muted fs-10 text-truncate d-block">${domain}</span>` : '';
    return `<div class="col-6 col-sm-4 col-md-2 link-item" id="link_item_${lnk.link_id}">
        <div class="media-card position-relative border border-gray-100 rounded-2 overflow-hidden">
            <a href="${lnk.link_url}" target="_blank" class="d-block media-thumb">
                <div class="d-flex align-items-center justify-content-center bg-light-info" style="height:110px;">
                    <img src="https://www.google.com/s2/favicons?domain=${encodeURIComponent(lnk.link_url)}&sz=64"
                         style="width:40px;height:40px;object-fit:contain;"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <i class="ki-duotone ki-link fs-2x text-info" style="display:none;"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </a>
            <button type="button" class="btn btn-icon btn-danger btn-del-link position-absolute top-0 end-0 m-1 media-del-btn" data-link-id="${lnk.link_id}">
                <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
            </button>
            <div class="p-2">
                <div class="text-truncate fw-semibold text-gray-800 fs-9" title="${label}">${label}</div>
                ${sub}
            </div>
        </div></div>`;
}

document.getElementById('btn_toggle_link_form')?.addEventListener('click', () => {
    document.getElementById('link_add_form').style.display = '';
    document.getElementById('new_link_url').focus();
});
document.getElementById('btn_cancel_link')?.addEventListener('click', () => {
    document.getElementById('link_add_form').style.display = 'none';
    document.getElementById('new_link_url').value = '';
    document.getElementById('new_link_title').value = '';
});
document.getElementById('btn_save_link')?.addEventListener('click', function() {
    const url   = document.getElementById('new_link_url').value.trim();
    const title = document.getElementById('new_link_title').value.trim();
    if (!url) { alert('Please enter a URL.'); return; }
    const fd = new FormData();
    fd.append('link_url', url); fd.append('link_title', title);
    $.ajax({
        url: '<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/link/add') ?>',
        type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (res.success) {
                document.getElementById('links_empty')?.remove();
                const lg = document.getElementById('links_grid') || (() => { const g = document.createElement('div'); g.className='row g-3'; g.id='links_grid'; document.getElementById('links_list').appendChild(g); return g; })();
                $(lg).append(renderLinkCard(res.link));
                document.getElementById('link_count_label').textContent = res.total + ' of 10 referrals';
                document.getElementById('btn_cancel_link').click();
                if (res.total >= 10) document.getElementById('btn_toggle_link_form').style.display = 'none';
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false,
                    confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            }
        }
    });
});

$(document).on('click', '.btn-del-link', function() {
    const id = $(this).data('link-id');
    $.post('<?= base_url('classroom/lesson/' . $lesson['lesson_id'] . '/link/delete') ?>/' + id, function(res) {
        if (res.success) {
            document.getElementById('link_item_' + id)?.remove();
            const cnt = document.querySelectorAll('.link-item').length;
            document.getElementById('link_count_label').textContent = cnt + ' of 10 referrals';
            if (cnt < 10) document.getElementById('btn_toggle_link_form').style.display = '';
        }
    }, 'json');
});

// ── DISCUSSION ─────────────────────────────────────────────────
const DISC_POST_URL   = '<?php echo base_url("classroom/lesson/" . $lesson["lesson_id"] . "/discussion/post"); ?>';
const DISC_LIKE_BASE  = '<?php echo base_url("classroom/lesson/" . $lesson["lesson_id"] . "/discussion"); ?>';
const UPLOADS_BASE    = '<?php echo base_url("uploads/profilePhoto/"); ?>';
const PROFILE_BASE    = '<?php echo base_url("user/detail/"); ?>';
const SESSION_PHOTO   = '<?php echo addslashes($sessionPhotoUrl ?? ""); ?>';
const SESSION_INITIAL = '<?php echo strtoupper(substr($sessionFname, 0, 1)); ?>';

function discAvHtml(photo, name, size) {
    if (photo) return '<img src="' + UPLOADS_BASE + photo + '" class="rounded-circle" style="object-fit:cover;width:' + size + 'px;height:' + size + 'px;" alt="">';
    return '<div class="symbol-label bg-light-primary fw-bold text-primary fs-8">' + name.charAt(0).toUpperCase() + '</div>';
}
function discCommentHtml(cmt, discId) {
    return '<div class="d-flex align-items-start gap-3 mb-4" id="comment_item_' + cmt.comment_id + '">' +
        '<a href="' + PROFILE_BASE + cmt.author_id + '" class="user-link symbol symbol-32px flex-shrink-0 text-decoration-none">' +
        discAvHtml(cmt.author_photo, cmt.author_name, 32) +
        '</a><div class="flex-grow-1 bg-light-secondary rounded-2 px-4 py-3">' +
        '<div class="d-flex justify-content-between align-items-center mb-1">' +
        '<a href="' + PROFILE_BASE + cmt.author_id + '" class="user-link user-link-name fw-bold text-gray-800 fs-8 text-decoration-none">' + cmt.author_name + '</a>' +
        '<span class="text-muted fs-9">Just now</span></div>' +
        '<span class="text-gray-700 fs-7 disc-body-text clamped d-block" id="cmt_text_' + cmt.comment_id + '">' + cmt.comment.replace(/\n/g, '<br>') + '</span>' +
        '<button type="button" class="btn btn-link btn-sm p-0 mt-1 fs-9 fw-bold text-primary btn-show-more" data-target="cmt_text_' + cmt.comment_id + '" style="display:none;">Show more</button>' +
        '<div class="mt-2 d-flex align-items-center gap-3">' +
        '<button type="button" class="btn btn-xs btn-color-gray-500 p-0 pe-1 btn-like-comment" data-comment-id="' + cmt.comment_id + '" data-disc-id="' + discId + '" data-type="like">' +
        '<i class="ki-duotone ki-heart fs-6 me-1"><span class="path1"></span><span class="path2"></span></i><span class="clike-count">0</span> Like</button>' +
        '<button type="button" class="btn btn-xs btn-color-gray-500 p-0 pe-1 btn-like-comment" data-comment-id="' + cmt.comment_id + '" data-disc-id="' + discId + '" data-type="dislike">' +
        '<i class="ki-duotone ki-dislike fs-6 me-1"><span class="path1"></span><span class="path2"></span></i><span class="cdislike-count">0</span> Dislike</button>' +
        '</div>' +
        '</div></div>';
}
function discPostHtml(d) {
    const myAv = SESSION_PHOTO
        ? '<img src="' + SESSION_PHOTO + '" class="rounded-circle" style="object-fit:cover;width:32px;height:32px;" alt="">'
        : '<div class="symbol-label bg-light-primary fw-bold text-primary fs-8">' + SESSION_INITIAL + '</div>';
    return '<div class="card card-flush mb-6 border border-dashed border-gray-200 disc-post" id="disc_post_' + d.lesson_discussion_id + '">' +
        '<div class="card-header pt-6 pb-0 border-0">' +
        '<a href="' + PROFILE_BASE + d.author_id + '" class="user-link d-flex align-items-center flex-grow-1 text-decoration-none">' +
        '<div class="symbol symbol-40px me-4 flex-shrink-0">' + discAvHtml(d.author_photo, d.author_name, 40) + '</div>' +
        '<div><span class="user-link-name fw-bold text-gray-800 fs-6">' + d.author_name + '</span>' +
        '<span class="text-muted fw-semibold fs-8 d-block">Just now</span></div></a>' +
        '<div class="card-toolbar"><button type="button" class="btn btn-icon btn-sm btn-light-danger btn-del-disc" data-disc-id="' + d.lesson_discussion_id + '" title="Delete">' +
        '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
        '</button></div></div>' +
        '<div class="card-body py-4"><div class="fs-6 fw-normal text-gray-700 disc-body-text clamped" id="disc_text_' + d.lesson_discussion_id + '">' + d.message.replace(/\n/g, '<br>') + '</div>' +
        '<button type="button" class="btn btn-link btn-sm p-0 mt-1 fs-8 fw-bold text-primary btn-show-more" data-target="disc_text_' + d.lesson_discussion_id + '" style="display:none;">Show more</button></div>' +
        '<div class="card-footer pt-0 border-0"><div class="separator separator-solid mb-3"></div>' +
        '<ul class="nav py-1">' +
        '<li class="nav-item"><button type="button" class="btn btn-sm btn-color-gray-600 btn-active-color-primary fw-bold px-4 me-1 btn-like-disc" data-disc-id="' + d.lesson_discussion_id + '" data-type="like">' +
        '<i class="ki-duotone ki-heart fs-4 me-1"><span class="path1"></span><span class="path2"></span></i><span class="like-count">0</span> Likes</button></li>' +
        '<li class="nav-item"><button type="button" class="btn btn-sm btn-color-gray-600 btn-active-color-danger fw-bold px-4 me-1 btn-like-disc" data-disc-id="' + d.lesson_discussion_id + '" data-type="dislike">' +
        '<i class="ki-duotone ki-dislike fs-4 me-1"><span class="path1"></span><span class="path2"></span></i><span class="dislike-count">0</span> Dislikes</button></li>' +
        '<li class="nav-item reactions-nav-item" style="display:none;"><button type="button" class="btn btn-sm btn-color-gray-400 fw-semibold px-3 btn-disc-reactions" data-disc-id="' + d.lesson_discussion_id + '" data-likes="0" data-dislikes="0"><span class="total-reactions">0</span> Reactions</button></li>' +
        '<li class="nav-item ms-auto"><a class="nav-link btn btn-sm btn-color-gray-600 btn-active-color-primary btn-active-light-primary fw-bold px-4 collapsed" data-bs-toggle="collapse" href="#disc_comments_' + d.lesson_discussion_id + '">' +
        '<i class="ki-duotone ki-message-text-2 fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>' +
        '<span class="comment-count">0</span> Comments</a></li></ul>' +
        '<div class="separator separator-solid mb-3"></div>' +
        '<div class="collapse" id="disc_comments_' + d.lesson_discussion_id + '">' +
        '<div class="pt-2" id="comments_area_' + d.lesson_discussion_id + '"></div>' +
        '<div class="d-flex align-items-center gap-3 mt-3"><div class="symbol symbol-32px flex-shrink-0">' + myAv + '</div>' +
        '<textarea class="form-control form-control-sm form-control-solid border flex-grow-1 disc-comment-input" rows="1" placeholder="Write a comment..." data-disc-id="' + d.lesson_discussion_id + '"></textarea>' +
        '<button type="button" class="btn btn-sm btn-primary flex-shrink-0 btn-send-comment" data-disc-id="' + d.lesson_discussion_id + '">' +
        '<i class="ki-duotone ki-send fs-5"><span class="path1"></span><span class="path2"></span></i></button></div></div></div></div>';
}

document.getElementById('btn_post_discussion')?.addEventListener('click', function() {
    const btn     = this;
    const message = document.getElementById('disc_post_input').value.trim();
    if (!message) {
        Swal.fire({ title: 'Required', text: 'Please write something before posting.', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }
    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;
    const fd = new FormData();
    fd.append('message', message);
    $.ajax({
        url: DISC_POST_URL, type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                document.getElementById('disc_post_input').value = '';
                document.getElementById('disc_empty_state')?.remove();
                $('#discussions_list').prepend(discPostHtml(res.discussion));
                initShowMore(document.getElementById('disc_text_' + res.discussion.lesson_discussion_id));
                const cnt = document.querySelectorAll('.disc-post').length;
                document.getElementById('disc_total_label').textContent = cnt + ' post' + (cnt !== 1 ? 's' : '');
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false,
                    confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
            }
        },
        error: function() { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; }
    });
});

$(document).on('click', '.btn-like-disc', function() {
    const discId = $(this).data('disc-id');
    const type   = $(this).data('type');
    const fd = new FormData();
    fd.append('type', type);
    $.ajax({
        url: DISC_LIKE_BASE + '/' + discId + '/like', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (!res.success) return;
            const card    = document.getElementById('disc_post_' + discId);
            const likes   = res.likes;
            const dislikes = res.dislikes;
            const total   = likes + dislikes;
            card.querySelector('.like-count').textContent    = likes;
            card.querySelector('.dislike-count').textContent = dislikes;
            card.querySelectorAll('.btn-like-disc').forEach(b => b.classList.remove('text-primary', 'text-danger'));
            if (res.reaction === 'like')    card.querySelector('[data-type="like"]').classList.add('text-primary');
            if (res.reaction === 'dislike') card.querySelector('[data-type="dislike"]').classList.add('text-danger');
            // Update reactions button
            const rBtn = card.querySelector('.btn-disc-reactions');
            const rLi  = card.querySelector('.reactions-nav-item');
            if (rBtn && rLi) {
                rBtn.dataset.likes    = likes;
                rBtn.dataset.dislikes = dislikes;
                if (total > 0) { rBtn.querySelector('.total-reactions').textContent = total; rLi.style.display = ''; }
                else rLi.style.display = 'none';
            }
        }
    });
});

$(document).on('click', '.btn-send-comment', function() {
    const discId   = $(this).data('disc-id');
    const textarea = document.querySelector('.disc-comment-input[data-disc-id="' + discId + '"]');
    const comment  = textarea.value.trim();
    if (!comment) return;
    const fd = new FormData();
    fd.append('comment', comment);
    $.ajax({
        url: DISC_LIKE_BASE + '/' + discId + '/comment', type: 'POST', data: fd, processData: false, contentType: false,
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
    Swal.fire({ title: 'Delete this post?', text: 'This cannot be undone.', icon: 'warning', showCancelButton: true,
        buttonsStyling: false, confirmButtonText: 'Delete', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        const fd = new FormData();
        $.ajax({
            url: DISC_LIKE_BASE + '/' + discId + '/delete', type: 'POST', data: fd, processData: false, contentType: false,
            success: function(res) {
                if (res.success) {
                    document.getElementById('disc_post_' + discId)?.remove();
                    const cnt = document.querySelectorAll('.disc-post').length;
                    document.getElementById('disc_total_label').textContent = cnt + ' post' + (cnt !== 1 ? 's' : '');
                    if (cnt === 0) {
                        document.getElementById('discussions_list').innerHTML =
                            '<div class="text-center py-12 text-muted" id="disc_empty_state">' +
                            '<i class="ki-duotone ki-message-edit fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>' +
                            '<div class="fs-6 fw-semibold mb-1">No posts yet</div>' +
                            '<div class="fs-8">Be the first to start a discussion.</div></div>';
                    }
                }
            }
        });
    });
});

$(document).on('click', '.btn-like-comment', function() {
    const btn       = this;
    const commentId = $(btn).data('comment-id');
    const discId    = $(btn).data('disc-id');
    const type      = $(btn).data('type') || 'like';
    const fd = new FormData();
    fd.append('type', type);
    $.ajax({
        url: DISC_LIKE_BASE + '/' + discId + '/comment/' + commentId + '/like',
        type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (!res.success) return;
            const item = document.getElementById('comment_item_' + commentId);
            // Update like/dislike buttons
            item.querySelectorAll('.btn-like-comment').forEach(b => b.classList.remove('text-primary','text-danger'));
            const likeBtn    = item.querySelector('[data-type="like"]');
            const dislikeBtn = item.querySelector('[data-type="dislike"]');
            if (likeBtn)    likeBtn.querySelector('.clike-count').textContent    = res.likes;
            if (dislikeBtn) dislikeBtn.querySelector('.cdislike-count').textContent = res.dislikes;
            if (res.reaction === 'like')    likeBtn?.classList.add('text-primary');
            if (res.reaction === 'dislike') dislikeBtn?.classList.add('text-danger');
            // Update or show/hide reactions link
            const total = res.likes + res.dislikes;
            let rBtn = item.querySelector('.btn-comment-reactions');
            if (total > 0) {
                if (!rBtn) {
                    const span = document.createElement('button');
                    span.type = 'button';
                    span.className = 'btn btn-xs btn-color-gray-400 p-0 btn-comment-reactions';
                    span.dataset.commentId = commentId;
                    span.dataset.discId    = discId;
                    item.querySelector('.d-flex.mt-2').appendChild(span);
                    rBtn = span;
                }
                rBtn.dataset.likes    = res.likes;
                rBtn.dataset.dislikes = res.dislikes;
                rBtn.textContent = total + ' reaction' + (total !== 1 ? 's' : '');
            } else if (rBtn) {
                rBtn.remove();
            }
        }
    });
});

// ── REACTIONS MODAL ────────────────────────────────────────────
const REACTIONS_MODAL_URL_BASE = DISC_LIKE_BASE;

function reactionUserHtml(r) {
    const av = r.photo
        ? '<img src="' + UPLOADS_BASE + r.photo + '" class="rounded-circle" style="object-fit:cover;width:48px;height:48px;" alt="">'
        : '<div class="symbol-label bg-light-primary fw-bold text-primary fs-6">' + r.name.charAt(0).toUpperCase() + '</div>';
    const icon = r.like_type === 'like'
        ? '<div class="position-absolute bottom-0 start-0 bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:18px;height:18px;"><i class="ki-duotone ki-heart fs-9 text-white"><span class="path1"></span><span class="path2"></span></i></div>'
        : '<div class="position-absolute bottom-0 start-0 bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:18px;height:18px;"><i class="ki-duotone ki-dislike fs-9 text-white"><span class="path1"></span><span class="path2"></span></i></div>';
    return '<div class="d-flex align-items-center gap-3 mb-4">' +
        '<div class="symbol symbol-48px position-relative flex-shrink-0">' + av + icon + '</div>' +
        '<span class="fw-semibold text-gray-800 fs-7">' + r.name + '</span>' +
        '</div>';
}

function buildReactionTabs(reactions, likes, dislikes) {
    const total = likes + dislikes;
    const allHtml   = reactions.map(r => reactionUserHtml(r)).join('');
    const likeHtml  = reactions.filter(r => r.like_type === 'like').map(r => reactionUserHtml(r)).join('');
    const disHtml   = reactions.filter(r => r.like_type === 'dislike').map(r => reactionUserHtml(r)).join('');
    document.getElementById('react_tab_all').textContent   = 'All ' + total;
    document.getElementById('react_tab_like').innerHTML    = '<i class="ki-duotone ki-heart fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i> Like ' + likes;
    document.getElementById('react_tab_dislike').innerHTML = '<i class="ki-duotone ki-dislike fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i> Dislike ' + dislikes;
    document.getElementById('react_pane_all').innerHTML    = allHtml   || '<p class="text-muted text-center py-4">No reactions</p>';
    document.getElementById('react_pane_like').innerHTML   = likeHtml  || '<p class="text-muted text-center py-4">No likes</p>';
    document.getElementById('react_pane_dislike').innerHTML = disHtml  || '<p class="text-muted text-center py-4">No dislikes</p>';
    // Activate All tab
    document.querySelectorAll('#reactions_modal .nav-link').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('#reactions_modal .tab-pane').forEach(p => { p.classList.remove('show','active'); });
    document.getElementById('react_tab_all').classList.add('active');
    document.getElementById('react_pane_all').classList.add('show','active');
}

$(document).on('click', '.btn-disc-reactions', function() {
    const discId   = $(this).data('disc-id');
    const likes    = parseInt($(this).data('likes'))    || 0;
    const dislikes = parseInt($(this).data('dislikes')) || 0;
    $.get(REACTIONS_MODAL_URL_BASE + '/' + discId + '/reactions', function(res) {
        if (!res.success) return;
        buildReactionTabs(res.reactions, likes, dislikes);
        new bootstrap.Modal(document.getElementById('reactions_modal')).show();
    });
});

// ── ASSESSMENTS ───────────────────────────────────────────────
const QUIZ_BASE          = '<?= base_url("classroom/lesson/" . $lesson["lesson_id"] . "/quiz") ?>';
const QUIZ_DETAIL_BASE   = '<?= base_url("classroom/teacher/" . $schSubId . "/lesson/" . $lesson["lesson_id"] . "/quiz/") ?>';
const QUIZ_ANALYSIS_BASE = '<?= base_url("classroom/teacher/" . $schSubId . "/lesson/" . $lesson["lesson_id"] . "/quiz/") ?>';
const DD_BUILDER_BASE      = '<?= base_url("classroom/teacher/" . $schSubId . "/lesson/" . $lesson["lesson_id"] . "/dragdrop/") ?>';
const LABEL_BUILDER_BASE   = '<?= base_url("classroom/teacher/" . $schSubId . "/lesson/" . $lesson["lesson_id"] . "/label/") ?>';
const DD_ANALYSIS_BASE     = '<?= base_url("classroom/teacher/" . $schSubId . "/lesson/" . $lesson["lesson_id"] . "/dragdrop/") ?>';
const LABEL_ANALYSIS_BASE  = '<?= base_url("classroom/teacher/" . $schSubId . "/lesson/" . $lesson["lesson_id"] . "/label/") ?>';

const ATYPE_CFG = {
    quiz:       { label: 'Quiz',       icon: 'ki-questionnaire-tablet', color: 'success' },
    drag_drop:  { label: 'Drag & Drop',icon: 'ki-abstract-26',          color: 'primary' },
    labelling:  { label: 'Labelling',  icon: 'ki-tag',                  color: 'info'    },
    simulation: { label: 'Simulation', icon: 'ki-rocket',               color: 'danger'  },
};

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function renderAssessmentCardHtml(quiz) {
    const aType  = quiz.assessment_type || 'quiz';
    const cfg    = ATYPE_CFG[aType] || ATYPE_CFG.quiz;
    const sc     = quiz.quizze_status === 'Published' ? 'success' : 'warning';
    const dur    = parseInt(quiz.quizze_duration) > 0 ? ' · ' + quiz.quizze_duration + 'min' : '';
    const qId    = quiz.lesson_quizze_id;
    const updateUrl = QUIZ_BASE + '/' + qId + '/update';

    let primaryUrl, primaryLabel, primaryIcon, buildLocked = false;
    if (aType === 'drag_drop') {
        primaryUrl   = DD_BUILDER_BASE + qId;
        primaryLabel = 'Build Assessment';
        primaryIcon  = 'ki-abstract-26';
    } else if (aType === 'labelling') {
        primaryUrl   = LABEL_BUILDER_BASE + qId;
        primaryLabel = 'Build Assessment';
        primaryIcon  = 'ki-tag';
    } else {
        primaryUrl   = QUIZ_DETAIL_BASE + qId;
        primaryLabel = 'Add Question';
        primaryIcon  = 'ki-plus-circle';
    }

    const typeBadge  = `<span class="badge bg-light-${cfg.color} text-${cfg.color} fs-10 px-1">${cfg.label}</span>`;
    const metaSpan   = aType === 'quiz' ? `<span class="text-muted fs-10 quiz-meta">0 Q${dur}</span>` : '';
    const isPublished = quiz.quizze_status === 'Published';

    // Build Analysis URL
    const aBase      = aType === 'drag_drop' ? DD_BUILDER_BASE.replace('/dragdrop/', '/dragdrop/').replace('dragdrop/', 'dragdrop/')
                     : aType === 'labelling' ? LABEL_BUILDER_BASE
                     : QUIZ_ANALYSIS_BASE;
    const analysisUrl = aType === 'quiz' ? QUIZ_ANALYSIS_BASE + qId + '/analysis'
                      : aType === 'drag_drop' ? DD_BUILDER_BASE.replace('classroom/teacher/', 'classroom/teacher/').replace(/\/lesson\/\d+\/dragdrop\/$/, '') + qId + '/analysis'
                      : LABEL_BUILDER_BASE + qId + '/analysis';

    // Simpler: build analysis URL from known base constants
    const analysisHref = aType === 'quiz'      ? `${QUIZ_ANALYSIS_BASE}${qId}/analysis`
                       : aType === 'drag_drop'  ? `${DD_BUILDER_BASE.slice(0,-1).replace('/dragdrop/','/dragdrop/')}/${qId}/analysis`.replace(/\/+/g,'/')
                       : `${LABEL_BUILDER_BASE}${qId}/analysis`;

    const primaryItem = (isPublished && aType !== 'quiz')
        ? `<div class="menu-item px-3"><span class="menu-link px-3 text-muted pe-none">
               <span class="menu-icon"><i class="ki-duotone ki-lock fs-5 text-success"><span class="path1"></span><span class="path2"></span></i></span>
               ${primaryLabel} <span class="ms-auto badge badge-light-success fs-10">Published</span>
           </span></div>`
        : `<div class="menu-item px-3"><a class="menu-link px-3" href="${primaryUrl}">
               <span class="menu-icon"><i class="ki-duotone ${primaryIcon} fs-5 text-${cfg.color}"><span class="path1"></span><span class="path2"></span></i></span>
               ${primaryLabel}
           </a></div>`;

    const analysisItem = `<div class="menu-item px-3"><a class="menu-link px-3" href="${analysisHref}">
        <span class="menu-icon"><i class="ki-duotone ki-chart-line-up fs-5 text-info"><span class="path1"></span><span class="path2"></span></i></span>
        View Analysis
    </a></div>`;

    const editItem = isPublished
        ? `<div class="menu-item px-3"><span class="menu-link px-3 text-muted pe-none">
               <span class="menu-icon"><i class="ki-duotone ki-lock fs-5 text-success"><span class="path1"></span><span class="path2"></span></i></span>
               Edit Assessment <span class="ms-auto badge badge-light-success fs-10">Published</span>
           </span></div>`
        : `<div class="menu-item px-3"><a class="menu-link px-3 btn-edit-quiz" href="javascript:void(0)"
               data-quiz-id="${qId}" data-quiz-name="${escHtml(quiz.quizze_name)}"
               data-quiz-duration="${quiz.quizze_duration}" data-quiz-status="${escHtml(quiz.quizze_status)}"
               data-has-attempts="0" data-is-published="0"
               data-assessment-type="${escHtml(quiz.assessment_type || 'quiz')}"
               data-content-count="0" data-zone-count="0"
               data-update-url="${updateUrl}">
               <span class="menu-icon"><i class="ki-duotone ki-pencil fs-5 text-primary"><span class="path1"></span><span class="path2"></span></i></span>
               Edit Assessment
           </a></div>`;

    const deleteItem = isPublished
        ? `<div class="menu-item px-3"><span class="menu-link px-3 text-muted pe-none">
               <span class="menu-icon"><i class="ki-duotone ki-lock fs-5 text-success"><span class="path1"></span><span class="path2"></span></i></span>
               Delete Assessment <span class="ms-auto badge badge-light-success fs-10">Published</span>
           </span></div>`
        : `<div class="menu-item px-3"><a class="menu-link px-3 text-danger btn-del-quiz" href="javascript:void(0)"
               data-quiz-id="${qId}" data-has-attempts="0">
               <span class="menu-icon"><i class="ki-duotone ki-trash fs-5 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></span>
               Delete Assessment
           </a></div>`;

    return `<div class="col-6 col-sm-4 col-md-3 quiz-item" id="quiz_item_${qId}">
        <div class="media-card border border-gray-100 rounded-2 position-relative" style="overflow:visible;">
            <div class="position-absolute" style="top:7px;right:7px;z-index:10;">
                <button class="btn btn-icon bg-white bg-opacity-95 border border-gray-300 shadow-sm rounded-circle" style="width:30px;height:30px;" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true" title="Options">
                    <i class="ki-duotone ki-dots-vertical fs-5 text-gray-700"><span class="path1"></span><span class="path2"></span></i>
                </button>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold fs-8 w-200px py-2" data-kt-menu="true">
                    ${primaryItem}
                    ${isPublished ? '<div class="separator my-2"></div>' + analysisItem : ''}
                    <div class="separator my-2"></div>
                    ${editItem}
                    <div class="separator my-2"></div>
                    ${deleteItem}
                    <div class="separator my-2"></div>
                    <div class="menu-item px-3"><a class="menu-link px-3 btn-share-lesson" href="javascript:void(0)">
                        <span class="menu-icon"><i class="ki-duotone ki-copy fs-5 text-info"><span class="path1"></span><span class="path2"></span></i></span>
                        Copy Lesson Link
                    </a></div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center bg-light-${cfg.color}" style="height:110px;border-radius:.375rem .375rem 0 0;">
                <i class="ki-duotone ${cfg.icon} fs-2x text-${cfg.color}"><span class="path1"></span><span class="path2"></span></i>
            </div>
            <div class="p-2">
                <div class="text-truncate fw-semibold text-gray-800 fs-9">${escHtml(quiz.quizze_name)}</div>
                <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                    ${typeBadge}
                    <span class="badge bg-light-${sc} text-${sc} fs-10 px-1 quiz-status-badge">${escHtml(quiz.quizze_status)}</span>
                    ${metaSpan}
                </div>
            </div>
        </div>
    </div>`;
}

// Highlight selected type card
document.getElementById('assessment_type_picker')?.addEventListener('change', function(e) {
    document.querySelectorAll('.assessment-type-card').forEach(c => {
        c.style.borderColor   = '';
        c.style.background    = '';
    });
    if (e.target.type === 'radio') {
        const card = e.target.closest('.assessment-type-card');
        if (card) { card.style.borderColor = 'var(--bs-primary)'; card.style.background = 'var(--bs-light)'; }
    }
    const hint = document.getElementById('dd_type_hint');
    const hintText = document.getElementById('dd_type_hint_text');
    const sel  = document.querySelector('input[name="assessment_type"]:checked')?.value;
    const builderTypes = { drag_drop: 'Drag & Drop Builder', labelling: 'Labelling Builder' };
    if (hint) hint.style.display = builderTypes[sel] ? '' : 'none';
    if (hintText && builderTypes[sel]) hintText.textContent = "After creating, you'll be taken to the " + builderTypes[sel] + " to set up the assessment.";
    const lblMap = { quiz: 'Create Assessment', drag_drop: 'Create & Open Builder', labelling: 'Create & Open Builder' };
    const lbl = document.getElementById('btn_save_quiz_label');
    if (lbl) lbl.textContent = lblMap[sel] || 'Create Assessment';
});
// Init first card highlight
document.querySelector('.assessment-type-card')?.style && (document.querySelector('.assessment-type-card').style.borderColor = 'var(--bs-primary)');

document.getElementById('btn_add_quiz')?.addEventListener('click', function() {
    document.getElementById('quiz_name_input').value     = '';
    document.getElementById('quiz_duration_input').value = '';
    document.getElementById('quiz_status_input').value   = 'Draft';
    // Reset type to quiz
    const firstRadio = document.querySelector('input[name="assessment_type"][value="quiz"]');
    if (firstRadio) { firstRadio.checked = true; firstRadio.dispatchEvent(new Event('change', {bubbles:true})); }
    new bootstrap.Modal(document.getElementById('modal_add_quiz')).show();
});

document.getElementById('btn_save_quiz')?.addEventListener('click', function() {
    const btn  = this;
    const name = document.getElementById('quiz_name_input').value.trim();
    if (!name) { document.getElementById('quiz_name_input').classList.add('is-invalid'); return; }
    document.getElementById('quiz_name_input').classList.remove('is-invalid');
    const aType = document.querySelector('input[name="assessment_type"]:checked')?.value || 'quiz';
    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;
    const fd = new FormData();
    fd.append('quizze_name',     name);
    fd.append('quizze_duration', document.getElementById('quiz_duration_input').value);
    fd.append('quizze_status',   document.getElementById('quiz_status_input').value);
    fd.append('assessment_type', aType);
    $.ajax({
        url: QUIZ_BASE + '/store', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_add_quiz')).hide();
                if (aType === 'drag_drop') {
                    window.location.href = DD_BUILDER_BASE + res.quiz.lesson_quizze_id;
                    return;
                }
                if (aType === 'labelling') {
                    window.location.href = LABEL_BUILDER_BASE + res.quiz.lesson_quizze_id;
                    return;
                }
                document.getElementById('quiz_empty_state')?.remove();
                let grid = document.getElementById('quiz_grid');
                if (!grid) {
                    grid = document.createElement('div');
                    grid.className = 'row g-3'; grid.id = 'quiz_grid';
                    document.getElementById('quiz_list').appendChild(grid);
                }
                $(grid).append(renderAssessmentCardHtml(res.quiz));
                KTMenu.createInstances();
                const cnt = document.querySelectorAll('.quiz-item').length;
                document.getElementById('quiz_count_label').textContent = cnt + ' assessment' + (cnt !== 1 ? 's' : '') + ' attached to this lesson';
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false,
                    confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            }
        }
    });
});

// ── Delete Quiz ────────────────────────────────────────────────
$(document).on('click', '.btn-del-quiz', function(e) {
    e.preventDefault();
    const quizId      = $(this).data('quiz-id');
    const hasAttempts = $(this).data('has-attempts') == 1;

    if (hasAttempts) {
        Swal.fire({ title: 'Cannot Delete', html: '<p>Students have already attempted this assessment.</p><p class="text-muted fs-8">Delete is locked to preserve student scores.</p>', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    Swal.fire({ title: 'Delete this assessment?', text: 'All content and data will be permanently removed.',
        icon: 'warning', showCancelButton: true, buttonsStyling: false,
        confirmButtonText: 'Yes, delete', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(QUIZ_BASE + '/' + quizId + '/delete', function(res) {
            if (res.success) {
                document.getElementById('quiz_item_' + quizId)?.remove();
                const cnt = document.querySelectorAll('.quiz-item').length;
                document.getElementById('quiz_count_label').textContent = cnt + ' assessment' + (cnt !== 1 ? 's' : '') + ' attached to this lesson';
                if (cnt === 0) {
                    document.getElementById('quiz_list').innerHTML =
                        '<div class="text-center py-8 text-muted" id="quiz_empty_state">' +
                        '<i class="ki-duotone ki-element-plus fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
                        '<div class="fs-8">No assessments yet. Create your first assessment for this lesson.</div></div>';
                }
            }
        }, 'json');
    });
});

// ── Edit Quiz ──────────────────────────────────────────────────
let _editQuizContentCount = 0;
let _editQuizZoneCount    = 0;
let _editQuizAType        = 'quiz';

function quizPublishBlockerMsg(aType, contentCount, zoneCount) {
    if (aType === 'quiz' && contentCount === 0)
        return 'Add at least one question before publishing this quiz.';
    if (aType === 'drag_drop' && (contentCount === 0 || zoneCount === 0))
        return 'Add at least one item and one drop zone before publishing this drag & drop activity.';
    if (aType === 'labelling' && contentCount === 0)
        return 'Add at least one labelling question before publishing this activity.';
    return null;
}

document.getElementById('edit_quiz_status')?.addEventListener('change', function() {
    const alertEl = document.getElementById('quiz_publish_blocker_alert');
    const msg     = quizPublishBlockerMsg(_editQuizAType, _editQuizContentCount, _editQuizZoneCount);
    if (this.value === 'Published' && msg) {
        document.getElementById('quiz_publish_blocker_text').textContent = msg;
        alertEl.classList.remove('d-none');
    } else {
        alertEl.classList.add('d-none');
    }
});

document.getElementById('modal_edit_quiz')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('quiz_publish_blocker_alert').classList.add('d-none');
});

$(document).on('click', '.btn-edit-quiz', function(e) {
    e.preventDefault();
    const hasAttempts = $(this).data('has-attempts') == 1;

    if (hasAttempts) {
        Swal.fire({ title: 'Cannot Edit', html: '<p>Students have already attempted this assessment.</p><p class="text-muted fs-8">Editing is locked to preserve student scores and results.</p>', icon: 'warning',
            buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    document.getElementById('edit_quiz_id').value         = $(this).data('quiz-id');
    document.getElementById('edit_quiz_name').value       = $(this).data('quiz-name');
    document.getElementById('edit_quiz_duration').value   = $(this).data('quiz-duration');
    document.getElementById('edit_quiz_status').value     = $(this).data('quiz-status');
    document.getElementById('edit_quiz_update_url').value = $(this).data('update-url');
    _editQuizAType        = $(this).data('assessment-type') || 'quiz';
    _editQuizContentCount = parseInt($(this).data('content-count')) || 0;
    _editQuizZoneCount    = parseInt($(this).data('zone-count'))    || 0;
    // Show warning immediately if current status is already Published (shouldn't happen, but safe)
    document.getElementById('quiz_publish_blocker_alert').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('modal_edit_quiz')).show();
});

document.getElementById('btn_save_quiz_edit')?.addEventListener('click', function() {
    const btn      = this;
    const name     = document.getElementById('edit_quiz_name').value.trim();
    const updateUrl= document.getElementById('edit_quiz_update_url').value;
    if (!name) { document.getElementById('edit_quiz_name').classList.add('is-invalid'); return; }
    document.getElementById('edit_quiz_name').classList.remove('is-invalid');

    const selStatus = document.getElementById('edit_quiz_status').value;
    const blockMsg  = quizPublishBlockerMsg(_editQuizAType, _editQuizContentCount, _editQuizZoneCount);
    if (selStatus === 'Published' && blockMsg) {
        Swal.fire({ title: 'Cannot Publish', text: blockMsg, icon: 'warning', buttonsStyling: false,
            confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } });
        return;
    }

    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;
    const fd = new FormData();
    fd.append('quizze_name',     name);
    fd.append('quizze_duration', document.getElementById('edit_quiz_duration').value);
    fd.append('quizze_status',   document.getElementById('edit_quiz_status').value);
    $.ajax({ url: updateUrl, type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_edit_quiz')).hide();
                // Update card labels
                const card = document.getElementById('quiz_item_' + res.quiz.lesson_quizze_id);
                if (card) {
                    card.querySelector('.text-truncate')?.setAttribute('title', res.quiz.quizze_name);
                    if (card.querySelector('.text-truncate')) card.querySelector('.text-truncate').textContent = res.quiz.quizze_name;
                    const sc = res.quiz.quizze_status === 'Published' ? 'success' : 'warning';
                    const badge = card.querySelector('.quiz-status-badge');
                    if (badge) { badge.textContent = res.quiz.quizze_status; badge.className = `badge bg-light-${sc} text-${sc} fs-10 px-1 quiz-status-badge`; }
                    const meta = card.querySelector('.quiz-meta');
                    const dur  = parseInt(res.quiz.quizze_duration) > 0 ? ' · ' + res.quiz.quizze_duration + 'min' : '';
                    if (meta) meta.textContent = (meta.textContent.split(' ')[0] || '0 Q') + dur;
                    // Update icon area color
                    const iconArea = card.querySelector('.bg-light-success, .bg-light-warning');
                    if (iconArea) { iconArea.className = iconArea.className.replace(/bg-light-(success|warning)/, 'bg-light-' + sc); }
                    // Update dropdown data
                    card.querySelector('.btn-edit-quiz')?.setAttribute('data-quiz-name', res.quiz.quizze_name);
                    card.querySelector('.btn-edit-quiz')?.setAttribute('data-quiz-duration', res.quiz.quizze_duration);
                    card.querySelector('.btn-edit-quiz')?.setAttribute('data-quiz-status', res.quiz.quizze_status);
                }
                Swal.fire({ title: 'Updated!', text: 'Assessment details saved.', icon: 'success', timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false,
                    confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            }
        }
    });
});

// ── SHOW MORE / SHOW LESS ──────────────────────────────────────
function toggleShowMore(el, btn) {
    if (!el) return;
    if (el.classList.contains('clamped')) {
        el.classList.remove('clamped');
        el.style.cursor = 'default';
        if (btn) { btn.style.display = ''; btn.textContent = 'Show less'; }
    } else {
        el.classList.add('clamped');
        el.style.cursor = 'pointer';
        if (btn) btn.textContent = 'Show more';
    }
}
function initShowMore(el) {
    if (!el) return;
    const btn = el.nextElementSibling;
    if (!btn || !btn.classList.contains('btn-show-more')) return;
    // scrollHeight > clientHeight reliably detects -webkit-line-clamp overflow
    if (el.scrollHeight > el.clientHeight + 1) {
        btn.style.display = '';
        el.style.cursor = 'pointer';
        el.onclick = function() { toggleShowMore(el, btn); };
    }
}
$(document).on('click', '.btn-show-more', function() {
    const el = document.getElementById(this.dataset.target);
    toggleShowMore(el, this);
});
// Defer init so layout is fully computed before measuring heights
requestAnimationFrame(function() {
    document.querySelectorAll('.disc-post .disc-body-text').forEach(initShowMore);
});
// Init comment bodies when their collapse section is first opened
$(document).on('shown.bs.collapse', '[id^="disc_comments_"]', function() {
    $(this).find('.disc-body-text').each(function() { initShowMore(this); });
});

// ── SHARE / COPY LESSON LINK ──────────────────────────────────
$(document).on('click', '.btn-share-lesson', function() {
    const url = window.location.href;
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function() {
            Swal.fire({ title: 'Copied!', text: 'Lesson link copied to clipboard.', icon: 'success', timer: 1500, showConfirmButton: false });
        });
    } else {
        const ta = document.createElement('textarea');
        ta.value = url; ta.style.position = 'fixed'; ta.style.left = '-9999px';
        document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
        Swal.fire({ title: 'Copied!', text: 'Lesson link copied to clipboard.', icon: 'success', timer: 1500, showConfirmButton: false });
    }
});


$(document).on('click', '.btn-comment-reactions', function() {
    const commentId = $(this).data('comment-id');
    const discId    = $(this).data('disc-id');
    const likes     = parseInt($(this).data('likes'))    || 0;
    const dislikes  = parseInt($(this).data('dislikes')) || 0;
    $.get(REACTIONS_MODAL_URL_BASE + '/' + discId + '/comment/' + commentId + '/reactions', function(res) {
        if (!res.success) return;
        buildReactionTabs(res.reactions, likes, dislikes);
        new bootstrap.Modal(document.getElementById('reactions_modal')).show();
    });
});
</script>

<style>
.info-row-border { border-bottom: 1px solid #d4d4e0; }
.step-item:hover { background: #fafbff; }
.media-card { transition: box-shadow .15s; }
.media-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.14); }
.media-del-btn { width:22px;height:22px;min-width:0;min-height:0;padding:0;opacity:0;transition:opacity .15s; }
.media-card:hover .media-del-btn { opacity:1; }
.media-thumb { position:relative; }
.user-link { transition: opacity .15s; }
.user-link:hover { opacity: .8; }
.user-link:hover .user-link-name { color: var(--bs-primary) !important; }
.disc-body-text.clamped { display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }
.disc-body-text.clamped { cursor:pointer; }
.disc-body-text:not(.clamped) { cursor:default; }
.btn-show-more { text-decoration:none; }
.btn-show-more:hover { text-decoration:underline; }
</style>
