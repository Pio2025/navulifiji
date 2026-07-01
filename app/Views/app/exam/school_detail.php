<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($school['sch_name']) ?>
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
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('exam/detail/' . $exam['exam_id']) ?>" class="text-muted text-hover-primary">
                        <?= esc($exam['exam_name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($school['sch_name']) ?></li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('exam/detail/' . $exam['exam_id']) ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>
                Back to Schools
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Context Banner-->
    <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-4">
            <div class="row g-4 align-items-center">
                <div class="col-sm-6 col-md-3">
                    <div class="fw-semibold text-muted fs-7 mb-1">Exam</div>
                    <div class="fw-bold text-gray-900 fs-6"><?= esc($exam['exam_name']) ?></div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="fw-semibold text-muted fs-7 mb-1">Level</div>
                    <div class="fw-bold text-gray-900 fs-6"><?= esc($exam['level_name'] ?? '—') ?></div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="fw-semibold text-muted fs-7 mb-1">Enrolled</div>
                    <div class="fw-bold text-primary fs-4" id="enrolled-count"><?= count($students) ?></div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="fw-semibold text-muted fs-7 mb-2">Filter by Year</div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" id="exam-year-input"
                               class="form-control form-control-sm form-control-solid w-100px"
                               value="<?= $defaultYear ?>" min="2000" max="2100" maxlength="4"
                               placeholder="Year">
                        <button type="button" id="fetch-year-btn" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-magnifier fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Fetch
                        </button>
                        <a href="<?= base_url('exam/detail/' . $exam['exam_id'] . '/school/' . $school['sch_id'] . '/report/pdf') ?>?year=<?= $defaultYear ?>"
                           id="print-pdf-btn"
                           target="_blank"
                           class="btn btn-sm btn-light-primary<?= $hasStudentsForDefaultYear ? '' : ' disabled' ?>"
                           <?= $hasStudentsForDefaultYear ? '' : 'aria-disabled="true" tabindex="-1"' ?>>
                            <i class="ki-duotone ki-printer fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Print PDF Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Context Banner-->

    <!--begin::Year fetch loader (beneath context banner)-->
    <div id="year-fetch-loader" class="d-none text-center py-4 mb-6">
        <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>
        <span class="text-muted fs-7">Loading exam data...</span>
    </div>
    <!--end::Year fetch loader-->

    <!--begin::Year no-data message-->
    <div id="year-no-data" class="alert alert-warning d-flex align-items-center mb-6 <?= empty($students) ? '' : 'd-none' ?>">
        <i class="ki-duotone ki-information-5 fs-2 me-3 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <div>No results found. No students or exam data for this school in the selected year.</div>
    </div>
    <!--end::Year no-data message-->

    <div id="stats-section" class="<?= empty($students) ? 'd-none' : '' ?>">
    <!--begin::Stats Section-->

    <!--begin::Stat Cards-->
    <div class="row g-4 mb-5">
        <?php
        $statCards = [
            ['id' => 'stat-enrolled',  'label' => 'Enrolled',     'value' => $stats['enrolled'],                                 'color' => 'primary',   'icon' => 'ki-people'],
            ['id' => 'stat-sat',       'label' => 'Marks Entered','value' => $stats['sat'],                                     'color' => 'info',      'icon' => 'ki-pencil'],
            ['id' => 'stat-passed',    'label' => 'Passed',       'value' => $stats['passed'],                                   'color' => 'success',   'icon' => 'ki-check-circle'],
            ['id' => 'stat-failed',    'label' => 'Failed',       'value' => $stats['failed'],                                   'color' => 'danger',    'icon' => 'ki-cross-circle'],
            ['id' => 'stat-pass-pct',  'label' => 'Pass %',       'value' => $stats['sat'] > 0 ? $stats['pass_pct'].'%' : '—',  'color' => 'warning',   'icon' => 'ki-chart-pie-4'],
            ['id' => 'stat-highest',   'label' => 'Highest',      'value' => $stats['highest'] !== null ? $stats['highest'].'/'.$stats['max_score'] : '—', 'color' => 'success', 'icon' => 'ki-arrow-up-right'],
            ['id' => 'stat-lowest',    'label' => 'Lowest',       'value' => $stats['lowest']  !== null ? $stats['lowest'].'/'.$stats['max_score']  : '—', 'color' => 'danger',  'icon' => 'ki-arrow-down-right'],
            ['id' => 'stat-average',   'label' => 'Average',      'value' => $stats['average'] !== null ? $stats['average'].'/'.$stats['max_score'] : '—', 'color' => 'info',    'icon' => 'ki-chart-line'],
        ];
        ?>
        <?php foreach ($statCards as $card): ?>
        <div class="col-6 col-md-3 col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-header pt-5 pb-0">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2" id="<?= $card['id'] ?>"><?= $card['value'] ?></span>
                        <span class="text-gray-500 pt-1 fw-semibold fs-7"><?= $card['label'] ?></span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <span class="badge badge-light-<?= $card['color'] ?> fs-base">
                        <i class="ki-duotone <?= $card['icon'] ?> fs-5 text-<?= $card['color'] ?> me-1">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        <?= $card['label'] ?>
                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <!--end::Stat Cards-->

    <!--begin::Scoring Rule Notice-->
    <div id="scoring-notice" class="notice d-flex bg-light-primary rounded border border-primary border-dashed p-3 mb-5 <?= $stats['sat'] > 0 ? '' : 'd-none' ?>">
        <i class="ki-duotone ki-information-5 fs-3 text-primary me-3 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <div class="text-gray-700 fs-7">
            <strong>Scoring rule (<?= esc($exam['level_name']) ?>):</strong>
            English + Best <span id="scoring-best-n"><?= $stats['best_n'] ?></span> other subjects = max <span id="scoring-max-score"><?= $stats['max_score'] ?></span> marks.
            Pass threshold: average ≥ 50 across counted subjects.
        </div>
    </div>
    <!--end::Scoring Rule Notice-->

    <!--begin::Charts Row-->
    <div id="charts-row" class="row g-5 mb-5 <?= $stats['sat'] > 0 ? '' : 'd-none' ?>">
        <!--begin::Pass/Fail Donut-->
        <div class="col-xl-4">
            <div class="card card-flush h-100">
                <div class="card-header pt-5">
                    <h3 class="card-title fw-bold fs-6 text-gray-700">Pass / Fail</h3>
                </div>
                <div class="card-body pt-2 d-flex justify-content-center align-items-center">
                    <div id="chart-pass-fail" style="min-height:220px;width:100%;"></div>
                </div>
            </div>
        </div>
        <!--end::Pass/Fail Donut-->

        <!--begin::Score Distribution-->
        <div class="col-xl-4">
            <div class="card card-flush h-100">
                <div class="card-header pt-5">
                    <h3 class="card-title fw-bold fs-6 text-gray-700">Score Distribution</h3>
                </div>
                <div class="card-body pt-2">
                    <div id="chart-distribution" style="min-height:220px;width:100%;"></div>
                </div>
            </div>
        </div>
        <!--end::Score Distribution-->

        <!--begin::Subject Averages-->
        <div class="col-xl-4">
            <div class="card card-flush h-100">
                <div class="card-header pt-5">
                    <h3 class="card-title fw-bold fs-6 text-gray-700">Subject Averages</h3>
                </div>
                <div class="card-body pt-2">
                    <div id="chart-subject-avg" style="min-height:220px;width:100%;"></div>
                </div>
            </div>
        </div>
        <!--end::Subject Averages-->
    </div>
    <!--end::Charts Row-->

    <!--begin::Subject Stats Table-->
    <div id="subject-stats-card" class="card card-flush mb-6 <?= !empty($stats['subject_stats']) ? '' : 'd-none' ?>">
        <div class="card-header pt-5">
            <h3 class="card-title fw-bold fs-5 text-gray-900">
                <i class="ki-duotone ki-book fs-3 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                Subject-wise Statistics
            </h3>
        </div>
        <div class="card-body py-4">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2 fs-7">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-180px">Subject</th>
                            <th class="min-w-80px text-center">Sat</th>
                            <th class="min-w-80px text-center">Avg</th>
                            <th class="min-w-80px text-center">Highest</th>
                            <th class="min-w-80px text-center">Lowest</th>
                            <th class="min-w-120px">Avg Bar</th>
                        </tr>
                    </thead>
                    <tbody id="subject-stats-tbody">
                    <?php foreach ($stats['subject_stats'] as $ss): ?>
                    <tr>
                        <td class="fw-semibold text-gray-800"><?= esc($ss['subject']) ?></td>
                        <td class="text-center text-muted"><?= $ss['sat'] ?></td>
                        <td class="text-center fw-bold text-<?= $ss['avg'] >= 50 ? 'success' : 'danger' ?>">
                            <?= $ss['avg'] ?>
                        </td>
                        <td class="text-center text-success fw-semibold"><?= $ss['highest'] ?></td>
                        <td class="text-center text-danger fw-semibold"><?= $ss['lowest'] ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress w-100px h-6px bg-light-<?= $ss['avg'] >= 50 ? 'success' : 'danger' ?>">
                                    <div class="progress-bar bg-<?= $ss['avg'] >= 50 ? 'success' : 'danger' ?>"
                                         style="width:<?= min($ss['avg'], 100) ?>%"></div>
                                </div>
                                <span class="text-muted fs-8"><?= $ss['avg'] ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Subject Stats Table-->

    <!--end::Stats Section-->
    </div><!-- /#stats-section -->

    <!--begin::No matching enrolments alert-->
    <div id="no-matching-enrolments" class="alert alert-warning d-flex align-items-center mb-6 <?= $totalAtLevel === 0 ? '' : 'd-none' ?>">
        <i class="ki-duotone ki-information-5 fs-2 me-3 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <div>
            <span class="fw-bold">No matching enrolments found.</span>
            <?= esc($school['sch_name']) ?> has no Active students enrolled at the
            <strong><?= esc($exam['level_name'] ?? 'required') ?></strong> level for <?= $currentYear ?>.
        </div>
    </div>
    <!--end::No matching enrolments alert-->

    <?php if ($canManageStudents): ?>
    <!--begin::Add Students Card-->
    <div id="add-students-card" class="card shadow-sm mb-6 <?= !empty($eligibleStudents) ? '' : 'd-none' ?>" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold fs-5 text-gray-900">
                    <i class="ki-duotone ki-user-add fs-3 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Add Students from <?= esc($school['sch_name']) ?>
                </h3>
            </div>
        </div>
        <div class="card-body pt-2">
            <form method="post" id="add-students-form" action="<?= base_url('exam/' . $exam['exam_id'] . '/students/add') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="sch_id" value="<?= $school['sch_id'] ?>">
                <input type="hidden" name="exam_year" id="add-students-year" value="<?= $currentYear ?>">
                <input type="hidden" name="exam_term" value="1">

                <div class="text-muted fs-7 mb-3">
                    Students will be added for exam year
                    <span class="fw-bold text-gray-900" id="add-students-year-label"><?= $currentYear ?></span>
                    (the current year — back-dated registration is not allowed).
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="fw-semibold fs-7" id="eligible-count-label"><?= count($eligibleStudents) ?> student(s) not yet enrolled in this exam</span>
                        <a href="#" class="fs-8 text-primary" onclick="toggleAll(true); return false;">Select All</a>
                        <span class="text-muted fs-8">|</span>
                        <a href="#" class="fs-8 text-muted" onclick="toggleAll(false); return false;">Clear All</a>
                    </div>

                    <div class="table-responsive" style="max-height:280px; overflow-y:auto;">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-2 fs-7">
                            <thead class="sticky-top bg-white">
                                <tr class="fw-bold text-muted">
                                    <th style="width:40px;"></th>
                                    <th>Student</th>
                                    <th>Stream</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="eligible-students-tbody">
                            <?php foreach ($eligibleStudents as $es): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="enrol_ids[]"
                                               value="<?= $es['enrol_id'] ?>"
                                               class="form-check-input student-check" checked>
                                    </td>
                                    <td class="fw-semibold text-gray-900">
                                        <?= esc($es['fname'] . ' ' . ($es['oname'] ? $es['oname'] . ' ' : '') . $es['lname']) ?>
                                    </td>
                                    <td class="text-muted"><?= esc($es['stream_name'] ?? '—') ?></td>
                                    <td class="text-muted"><?= esc($es['enrol_status'] ?? '—') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="ki-duotone ki-user-tick fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        Add Selected Students
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!--end::Add Students Card-->

    <!--begin::All enrolled alert-->
    <div id="all-enrolled-alert" class="alert alert-info d-flex align-items-center mb-6 <?= ($totalAtLevel > 0 && empty($eligibleStudents)) ? '' : 'd-none' ?>">
        <i class="ki-duotone ki-information-5 fs-2 me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        All Active students from <?= esc($school['sch_name']) ?> at the
        <strong class="mx-1"><?= esc($exam['level_name'] ?? 'required') ?></strong> level
        have already been enrolled in this exam for <?= $currentYear ?>.
    </div>
    <!--end::All enrolled alert-->
    <?php endif; ?>
    <!--end::Add Students Card-->

    <!--begin::Enrolled Students Card-->
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold fs-5 text-gray-900">
                    <i class="ki-duotone ki-people fs-3 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    Enrolled Students
                    <span class="badge badge-light-primary ms-2"><?= count($students) ?></span>
                </h3>
            </div>
            <div class="card-toolbar gap-2">
                <div class="position-relative">
                    <i class="ki-duotone ki-magnifier fs-4 position-absolute top-50 translate-middle-y ms-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" id="student_search"
                           class="form-control form-control-sm form-control-solid ps-10 w-200px"
                           placeholder="Search students...">
                </div>
                <?php if ($canDelete): ?>
                <button type="button" id="drop-all-btn"
                        class="btn btn-sm btn-light-danger"
                        <?= (empty($students) || $anyStudentHasMarks) ? 'disabled' : '' ?>
                        <?php if ($anyStudentHasMarks): ?>
                        title="Cannot drop students who have marks entered"
                        <?php endif; ?>>
                    <i class="ki-duotone ki-cross-circle fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Drop All
                </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body py-4">

            <div id="students-empty" class="text-center text-muted py-8 <?= !empty($students) ? 'd-none' : '' ?>">
                <i class="ki-duotone ki-people fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                <p class="fs-6">No students from this school have been enrolled in this exam yet.</p>
            </div>
            <div id="students-table-wrap" class="<?= empty($students) ? 'd-none' : '' ?>">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="student_table">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-180px">Student</th>
                            <th>Stream</th>
                            <th>Year</th>
                            <th>Term</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr data-name="<?= esc(strtolower($s['fname'] . ' ' . $s['lname'])) ?>">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-35px">
                                        <?php if (!empty($s['profile_photo'])): ?>
                                        <img src="<?= base_url('uploads/profilePhoto/' . $s['profile_photo']) ?>" alt="photo" class="rounded-circle">
                                        <?php else: ?>
                                        <div class="symbol-label fs-7 fw-bold bg-light-primary text-primary">
                                            <?= strtoupper(substr($s['fname'], 0, 1) . substr($s['lname'], 0, 1)) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="fw-bold text-gray-900">
                                        <?= esc($s['fname'] . ' ' . ($s['oname'] ? $s['oname'] . ' ' : '') . $s['lname']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-muted fs-7"><?= esc($s['stream_name'] ?? '—') ?></td>
                            <td class="text-muted fs-7"><?= esc($s['exam_year']) ?></td>
                            <td class="text-muted fs-7">Term <?= esc($s['exam_term']) ?></td>
                            <td>
                                <span class="badge badge-light-<?= $s['student_exam_status'] === 'Active' ? 'success' : 'secondary' ?> fs-8">
                                    <?= esc($s['student_exam_status']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end py-4 fs-7">
                                        <li>
                                            <a class="dropdown-item py-2"
                                               href="<?= base_url('exam/student/' . $s['student_exam_id'] . '/marks') ?>">
                                                <i class="ki-duotone ki-pencil fs-6 me-2"><span class="path1"></span><span class="path2"></span></i>
                                                Exam Mark
                                            </a>
                                        </li>
                                        <?php if ($canDelete): ?>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <?php $hasMarks = isset($markedAdmissions[$s['admission_id']]); ?>
                                        <li>
                                            <?php if ($hasMarks): ?>
                                            <span class="dropdown-item py-2 text-muted" style="cursor:not-allowed;"
                                                  title="Cannot drop — marks have been entered">
                                                <i class="ki-duotone ki-cross-circle fs-6 me-2"><span class="path1"></span><span class="path2"></span></i>
                                                Drop
                                            </span>
                                            <?php else: ?>
                                            <a class="dropdown-item py-2 text-danger drop-student"
                                               href="#"
                                               data-id="<?= $s['student_exam_id'] ?>">
                                                <i class="ki-duotone ki-cross-circle fs-6 me-2"><span class="path1"></span><span class="path2"></span></i>
                                                Drop
                                            </a>
                                            <?php endif; ?>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <?php if ($canDelete): ?>
                                <form id="drop-form-<?= $s['student_exam_id'] ?>" method="post"
                                      action="<?= base_url('exam/student/' . $s['student_exam_id'] . '/remove') ?>"
                                      class="d-none">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="sch_id" value="<?= $school['sch_id'] ?>">
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div><!-- /#students-table-wrap -->

        </div>
    </div>
    <!--end::Enrolled Students Card-->

    <?php if ($canDelete): ?>
    <form id="drop-all-form" method="post"
          action="<?= base_url('exam/' . $exam['exam_id'] . '/school/' . $school['sch_id'] . '/students/drop-all') ?>"
          class="d-none">
        <?= csrf_field() ?>
    </form>
    <?php endif; ?>

</div>
</div>

<script>
// ── Exam Statistics Charts (amCharts 5) ───────────────────────────────────
var examChartRoots = [];

function disposeCharts() {
    examChartRoots.forEach(function(r) { r.dispose(); });
    examChartRoots = [];
}

function buildCharts(stats) {
    disposeCharts();

    // 1. Pass / Fail donut
    var r1 = am5.Root.new('chart-pass-fail');
    examChartRoots.push(r1);
    r1.setThemes([am5themes_Animated.new(r1)]);
    var pie = r1.container.children.push(am5percent.PieChart.new(r1, {
        innerRadius: am5.percent(55), layout: r1.verticalLayout
    }));
    var ps = pie.series.push(am5percent.PieSeries.new(r1, {
        valueField: 'value', categoryField: 'category',
        alignLabels: false,
    }));
    ps.slices.template.setAll({ strokeWidth: 2, stroke: am5.color(0xffffff) });
    ps.labels.template.setAll({ text: '{category}: {value}', fontSize: 12, radius: 5 });
    ps.ticks.template.set('visible', false);
    ps.data.setAll([
        { category: 'Passed', value: stats.passed, fill: am5.color(0x50cd89) },
        { category: 'Failed', value: stats.failed, fill: am5.color(0xf1416c) },
    ]);
    ps.slices.template.setAll({ templateField: 'fill' });
    var legend1 = pie.children.push(am5.Legend.new(r1, { centerX: am5.percent(50), x: am5.percent(50) }));
    legend1.data.setAll(ps.dataItems);

    // 2. Score distribution bar
    var distData = Object.keys(stats.distribution).map(function(k) {
        return { cat: k, val: stats.distribution[k] };
    });
    var r2 = am5.Root.new('chart-distribution');
    examChartRoots.push(r2);
    r2.setThemes([am5themes_Animated.new(r2)]);
    var xy2 = r2.container.children.push(am5xy.XYChart.new(r2, { panX: false, panY: false }));
    var xr2 = xy2.xAxes.push(am5xy.CategoryAxis.new(r2, {
        categoryField: 'cat', renderer: am5xy.AxisRendererX.new(r2, { minGridDistance: 20 })
    }));
    var yr2 = xy2.yAxes.push(am5xy.ValueAxis.new(r2, {
        min: 0, renderer: am5xy.AxisRendererY.new(r2, {})
    }));
    var col2 = xy2.series.push(am5xy.ColumnSeries.new(r2, {
        xAxis: xr2, yAxis: yr2, valueYField: 'val', categoryXField: 'cat',
        tooltip: am5.Tooltip.new(r2, { labelText: '{valueY} student(s)' }),
    }));
    col2.columns.template.setAll({ cornerRadiusTL: 4, cornerRadiusTR: 4, fill: am5.color(0x009ef7), strokeOpacity: 0 });
    col2.bullets.push(function() {
        return am5.Bullet.new(r2, { sprite: am5.Label.new(r2, { text: '{valueY}', centerY: am5.p100, populateText: true, fontSize: 11 }) });
    });
    xr2.data.setAll(distData);
    col2.data.setAll(distData);

    // 3. Subject averages bar
    if (stats.subject_stats && stats.subject_stats.length) {
        var subData = stats.subject_stats.map(function(s) { return { sub: s.subject, avg: s.avg }; });
        var r3 = am5.Root.new('chart-subject-avg');
        examChartRoots.push(r3);
        r3.setThemes([am5themes_Animated.new(r3)]);
        var xy3 = r3.container.children.push(am5xy.XYChart.new(r3, { panX: false, panY: false }));
        var xr3 = xy3.xAxes.push(am5xy.CategoryAxis.new(r3, {
            categoryField: 'sub', renderer: am5xy.AxisRendererX.new(r3, { minGridDistance: 5 })
        }));
        xr3.get('renderer').labels.template.setAll({ rotation: -30, centerY: am5.p50, centerX: am5.p100, fontSize: 10 });
        var yr3 = xy3.yAxes.push(am5xy.ValueAxis.new(r3, {
            min: 0, max: 100, renderer: am5xy.AxisRendererY.new(r3, {})
        }));
        var col3 = xy3.series.push(am5xy.ColumnSeries.new(r3, {
            xAxis: xr3, yAxis: yr3, valueYField: 'avg', categoryXField: 'sub',
            tooltip: am5.Tooltip.new(r3, { labelText: '{categoryX}: {valueY}' }),
        }));
        col3.columns.template.setAll({ cornerRadiusTL: 4, cornerRadiusTR: 4, fill: am5.color(0x7239ea), strokeOpacity: 0 });
        col3.bullets.push(function() {
            return am5.Bullet.new(r3, { sprite: am5.Label.new(r3, { text: '{valueY}', centerY: am5.p100, populateText: true, fontSize: 10 }) });
        });
        // Pass line at 50
        var range3 = yr3.makeDataItem({ value: 50 });
        yr3.createAxisRange(range3);
        range3.get('grid').setAll({ stroke: am5.color(0xf1416c), strokeWidth: 2, strokeDasharray: [4,4] });
        xr3.data.setAll(subData);
        col3.data.setAll(subData);
    }
}

function renderSubjectRows(subjectStats) {
    if (!subjectStats || !subjectStats.length) return '';
    return subjectStats.map(function(ss) {
        var color = ss.avg >= 50 ? 'success' : 'danger';
        return `<tr>
            <td class="fw-semibold text-gray-800">${ss.subject}</td>
            <td class="text-center text-muted">${ss.sat}</td>
            <td class="text-center fw-bold text-${color}">${ss.avg}</td>
            <td class="text-center text-success fw-semibold">${ss.highest}</td>
            <td class="text-center text-danger fw-semibold">${ss.lowest}</td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div class="progress w-100px h-6px bg-light-${color}">
                        <div class="progress-bar bg-${color}" style="width:${Math.min(ss.avg, 100)}%"></div>
                    </div>
                    <span class="text-muted fs-8">${ss.avg}%</span>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderStats(stats) {
    document.getElementById('stat-enrolled').textContent  = stats.enrolled;
    document.getElementById('stat-sat').textContent       = stats.sat;
    document.getElementById('stat-passed').textContent    = stats.passed;
    document.getElementById('stat-failed').textContent    = stats.failed;
    document.getElementById('stat-pass-pct').textContent  = stats.sat > 0 ? stats.pass_pct + '%' : '—';
    document.getElementById('stat-highest').textContent   = stats.highest !== null ? stats.highest + '/' + stats.max_score : '—';
    document.getElementById('stat-lowest').textContent    = stats.lowest  !== null ? stats.lowest  + '/' + stats.max_score : '—';
    document.getElementById('stat-average').textContent   = stats.average !== null ? stats.average + '/' + stats.max_score : '—';

    var scoringNotice = document.getElementById('scoring-notice');
    var chartsRow      = document.getElementById('charts-row');
    var subjectCard     = document.getElementById('subject-stats-card');

    if (stats.sat > 0) {
        document.getElementById('scoring-best-n').textContent     = stats.best_n;
        document.getElementById('scoring-max-score').textContent  = stats.max_score;
        scoringNotice.classList.remove('d-none');
        chartsRow.classList.remove('d-none');
        buildCharts(stats);
    } else {
        scoringNotice.classList.add('d-none');
        chartsRow.classList.add('d-none');
        disposeCharts();
    }

    if (stats.subject_stats && stats.subject_stats.length) {
        document.getElementById('subject-stats-tbody').innerHTML = renderSubjectRows(stats.subject_stats);
        subjectCard.classList.remove('d-none');
    } else {
        document.getElementById('subject-stats-tbody').innerHTML = '';
        subjectCard.classList.add('d-none');
    }
}

<?php if (!empty($students) && $stats['sat'] > 0): ?>
buildCharts(<?= json_encode($stats) ?>);
<?php endif; ?>
// ──────────────────────────────────────────────────────────────────────────

const sRows = document.querySelectorAll('#student_table tbody tr[data-name]');
document.getElementById('student_search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    sRows.forEach(r => r.style.display = !q || r.dataset.name.includes(q) ? '' : 'none');
});

function toggleAll(checked) {
    document.querySelectorAll('.student-check').forEach(c => c.checked = checked);
}

document.querySelectorAll('.drop-student').forEach(function(el) {
    el.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        Swal.fire({
            title: 'Drop Student?',
            text: 'This will remove the student and all their marks from this exam.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Drop',
            cancelButtonText: 'Cancel',
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('drop-form-' + id).submit();
            }
        });
    });
});

const dropAllBtn = document.getElementById('drop-all-btn');
if (dropAllBtn) {
    dropAllBtn.addEventListener('click', function() {
        Swal.fire({
            title: 'Drop All Students?',
            text: 'This will remove ALL enrolled students and their marks from this exam for this school. This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Drop All',
            cancelButtonText: 'Cancel',
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('drop-all-form').submit();
            }
        });
    });
}

// ── Year filter fetch ─────────────────────────────────────────────────────
(function () {
    const examId   = <?= (int)$exam['exam_id'] ?>;
    const schId    = <?= (int)$school['sch_id'] ?>;
    const canDelete = <?= json_encode((bool)$canDelete) ?>;
    const canManageStudents = <?= json_encode((bool)$canManageStudents) ?>;
    const baseUrl  = '<?= base_url() ?>';

    function profilePhotoUrl(photo) {
        return photo ? (baseUrl + 'uploads/profilePhoto/' + photo) : null;
    }

    function buildInitials(fname, lname) {
        return (fname.charAt(0) + lname.charAt(0)).toUpperCase();
    }

    function renderRows(students, markedAdmissions) {
        if (!students.length) return null;

        return students.map(function(s) {
            const fullName = [s.fname, s.oname, s.lname].filter(Boolean).join(' ');
            const safeName = fullName.replace(/"/g, '&quot;');
            const initials = buildInitials(s.fname, s.lname);
            const photoUrl = profilePhotoUrl(s.profile_photo);
            const hasMarks = markedAdmissions.includes(String(s.admission_id)) || markedAdmissions.includes(parseInt(s.admission_id));
            const statusBadge = s.student_exam_status === 'Active' ? 'success' : 'secondary';

            const photoHtml = photoUrl
                ? `<img src="${photoUrl}" alt="photo" class="rounded-circle" style="width:35px;height:35px;object-fit:cover;">`
                : `<div class="symbol-label fs-7 fw-bold bg-light-primary text-primary" style="width:35px;height:35px;border-radius:50%;display:flex;align-items:center;justify-content:center;">${initials}</div>`;

            let dropHtml = '';
            if (canDelete) {
                if (hasMarks) {
                    dropHtml = `<li><hr class="dropdown-divider my-1"></li>
                        <li><span class="dropdown-item py-2 text-muted" style="cursor:not-allowed;" title="Cannot drop — marks have been entered">
                            <i class="ki-duotone ki-cross-circle fs-6 me-2"><span class="path1"></span><span class="path2"></span></i>Drop
                        </span></li>`;
                } else {
                    dropHtml = `<li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2 text-danger drop-student" href="#" data-id="${s.student_exam_id}">
                            <i class="ki-duotone ki-cross-circle fs-6 me-2"><span class="path1"></span><span class="path2"></span></i>Drop
                        </a></li>`;
                }
            }

            const dropForm = canDelete
                ? `<form id="drop-form-${s.student_exam_id}" method="post" action="${baseUrl}exam/student/${s.student_exam_id}/remove" class="d-none">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <input type="hidden" name="sch_id" value="${schId}">
                   </form>`
                : '';

            return `<tr data-name="${fullName.toLowerCase()}">
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-35px">${photoHtml}</div>
                        <span class="fw-bold text-gray-900">${fullName}</span>
                    </div>
                </td>
                <td class="text-muted fs-7">${s.stream_name || '—'}</td>
                <td class="text-muted fs-7">${s.exam_year}</td>
                <td class="text-muted fs-7">Term ${s.exam_term}</td>
                <td><span class="badge badge-light-${statusBadge} fs-8">${s.student_exam_status}</span></td>
                <td class="text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
                        <ul class="dropdown-menu dropdown-menu-end py-4 fs-7">
                            <li><a class="dropdown-item py-2" href="${baseUrl}exam/student/${s.student_exam_id}/marks">
                                <i class="ki-duotone ki-pencil fs-6 me-2"><span class="path1"></span><span class="path2"></span></i>Exam Mark
                            </a></li>
                            ${dropHtml}
                        </ul>
                    </div>
                    ${dropForm}
                </td>
            </tr>`;
        }).join('');
    }

    function updatePdfButton(year, hasStudents) {
        const pdfBtn = document.getElementById('print-pdf-btn');
        if (!pdfBtn) return;
        pdfBtn.href = `${baseUrl}exam/detail/${examId}/school/${schId}/report/pdf?year=${year}`;
        if (hasStudents) {
            pdfBtn.classList.remove('disabled');
            pdfBtn.removeAttribute('aria-disabled');
            pdfBtn.removeAttribute('tabindex');
        } else {
            pdfBtn.classList.add('disabled');
            pdfBtn.setAttribute('aria-disabled', 'true');
            pdfBtn.setAttribute('tabindex', '-1');
        }
    }
    document.getElementById('print-pdf-btn')?.addEventListener('click', function(e) {
        if (this.classList.contains('disabled')) e.preventDefault();
    });

    function rebindDropListeners() {
        document.querySelectorAll('.drop-student').forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Drop Student?',
                    text: 'This will remove the student and all their marks from this exam.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, Drop',
                    cancelButtonText: 'Cancel',
                }).then(function(result) {
                    if (result.isConfirmed) document.getElementById('drop-form-' + id).submit();
                });
            });
        });
    }

    function doFetch() {
        const input  = document.getElementById('exam-year-input');
        const loader = document.getElementById('year-fetch-loader');
        const btn    = document.getElementById('fetch-year-btn');
        const val    = input.value.trim();

        if (!val) {
            Swal.fire({ icon: 'error', title: 'Year Required', text: 'Please enter a year before fetching.' }); return;
        }
        if (!/^\d{4}$/.test(val)) {
            Swal.fire({ icon: 'error', title: 'Invalid Year', text: 'Year must be exactly 4 digits (e.g. 2024).' }); return;
        }

        loader.classList.remove('d-none');
        btn.disabled = true;

        fetch(`${baseUrl}exam/detail/${examId}/school/${schId}/students/by-year?year=${val}`)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                loader.classList.add('d-none');
                btn.disabled = false;

                if (!data.success) {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to fetch students.' }); return;
                }

                document.getElementById('enrolled-count').textContent = data.count;
                updatePdfButton(val, data.students.length > 0);

                const tbody     = document.querySelector('#student_table tbody');
                const empty     = document.getElementById('students-empty');
                const wrap      = document.getElementById('students-table-wrap');
                const statsSec  = document.getElementById('stats-section');
                const noData    = document.getElementById('year-no-data');

                if (!data.students.length) {
                    if (tbody) tbody.innerHTML = '';
                    empty.classList.remove('d-none');
                    wrap.classList.add('d-none');
                    statsSec.classList.add('d-none');
                    noData.classList.remove('d-none');
                    disposeCharts();
                } else {
                    empty.classList.add('d-none');
                    wrap.classList.remove('d-none');
                    statsSec.classList.remove('d-none');
                    noData.classList.add('d-none');
                    if (tbody) tbody.innerHTML = renderRows(data.students, data.markedAdmissions);
                    rebindDropListeners();

                    // rebind search
                    const q = document.getElementById('student_search')?.value.toLowerCase();
                    if (q) {
                        document.querySelectorAll('#student_table tbody tr[data-name]').forEach(function(r) {
                            r.style.display = r.dataset.name.includes(q) ? '' : 'none';
                        });
                    }
                }

                if (data.stats) renderStats(data.stats);
            })
            .catch(function(err) {
                loader.classList.add('d-none');
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server. Please try again.' });
            });
    }

    document.getElementById('fetch-year-btn').addEventListener('click', doFetch);

    document.getElementById('exam-year-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') doFetch();
    });
})();
</script>
