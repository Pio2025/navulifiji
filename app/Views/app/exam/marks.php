<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Exam Marks — <?= esc($studentExam['fname'] . ' ' . $studentExam['lname']) ?>
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
                    <a href="<?= base_url('exam/detail/' . $studentExam['exam_id_fk'] . '/school/' . $studentExam['sch_id_fk']) ?>" class="text-muted text-hover-primary">
                        <?= esc($studentExam['sch_name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Marks Entry</li>
            </ul>
        </div>
        <a href="<?= base_url('exam/detail/' . $studentExam['exam_id_fk'] . '/school/' . $studentExam['sch_id_fk']) ?>"
           class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>
            Back to School
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Student Info Card-->
    <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-4">
            <div class="row g-4 align-items-center">
                <div class="col-auto">
                    <?php if (!empty($studentExam['profile_photo'])): ?>
                    <img src="<?= base_url('uploads/profilePhoto/' . $studentExam['profile_photo']) ?>"
                         alt="photo" class="rounded-circle" style="width:55px;height:55px;object-fit:cover;">
                    <?php else: ?>
                    <div class="symbol symbol-55px">
                        <div class="symbol-label fs-4 fw-bold bg-light-primary text-primary">
                            <?= strtoupper(substr($studentExam['fname'], 0, 1) . substr($studentExam['lname'], 0, 1)) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col">
                    <div class="fw-bold text-gray-900 fs-5">
                        <?= esc($studentExam['fname'] . ' ' . ($studentExam['oname'] ? $studentExam['oname'] . ' ' : '') . $studentExam['lname']) ?>
                    </div>
                    <div class="text-muted fs-7">
                        <?= esc($studentExam['sch_name']) ?>
                        <?php if (!empty($studentExam['stream_name'])): ?>
                        &bull; <?= esc($studentExam['stream_name']) ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($examReg['stud_index_num'])): ?>
                    <div class="text-muted fs-8 mt-1">
                        Index No: <span class="fw-semibold text-gray-700"><?= esc($examReg['stud_index_num']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-sm-auto">
                    <div class="d-flex gap-6 flex-wrap">
                        <div class="text-center">
                            <div class="fw-semibold text-muted fs-8 mb-1">Exam</div>
                            <div class="fw-bold text-gray-900 fs-7"><?= esc($studentExam['exam_name']) ?></div>
                        </div>
                        <div class="text-center">
                            <div class="fw-semibold text-muted fs-8 mb-1">Year</div>
                            <div class="fw-bold text-gray-900 fs-7"><?= esc($studentExam['exam_year']) ?></div>
                        </div>
                        <div class="text-center">
                            <div class="fw-semibold text-muted fs-8 mb-1">Term</div>
                            <div class="fw-bold text-gray-900 fs-7">Term <?= esc($studentExam['exam_term']) ?></div>
                        </div>
                        <?php if (!empty($studentExam['level_name'])): ?>
                        <div class="text-center">
                            <div class="fw-semibold text-muted fs-8 mb-1">Level</div>
                            <div class="fw-bold text-gray-900 fs-7"><?= esc($studentExam['level_name']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Student Info Card-->

    <?php if (!empty($subjects) && $studentScore['sat']): ?>
    <!--begin::Score Summary-->
    <div class="row g-4 mb-5">
        <?php
        $scoreLabel = $studentScore['total'] . ' / ' . $studentScore['max'];
        $pct        = $studentScore['max'] > 0 ? round($studentScore['total'] / $studentScore['max'] * 100, 1) : 0;
        $passed     = $studentScore['passed'];
        $summCards  = [
            ['label' => 'Total Score',  'value' => $scoreLabel,                      'color' => $passed ? 'success' : 'danger'],
            ['label' => 'Score %',      'value' => $pct . '%',                       'color' => $pct >= 50 ? 'success' : 'danger'],
            ['label' => 'Avg per Sub',  'value' => $studentScore['average'] ?? '—',  'color' => ($studentScore['average'] ?? 0) >= 50 ? 'success' : 'danger'],
            ['label' => 'English',      'value' => $studentScore['english'] ?? '—',  'color' => ($studentScore['english'] ?? 0) >= 50 ? 'success' : 'danger'],
            ['label' => 'Result',       'value' => $passed ? 'PASS' : 'FAIL',        'color' => $passed ? 'success' : 'danger'],
            ['label' => 'Position',     'value' => $position !== null ? $position . ' of ' . $positionTotal : '—', 'color' => 'primary'],
        ];
        ?>
        <?php foreach ($summCards as $sc): ?>
        <div class="col-6 col-sm-4 col-md-2-4">
            <div class="card card-flush">
                <div class="card-body py-3 px-4 text-center">
                    <div class="fs-2x fw-bold text-<?= $sc['color'] ?>"><?= $sc['value'] ?></div>
                    <div class="text-muted fs-8 mt-1"><?= $sc['label'] ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
    // Scoring rule notice
    $bestN = $scoringRule['best_n'];
    ?>
    <div class="notice d-flex bg-light-primary rounded border border-primary border-dashed p-3 mb-5">
        <i class="ki-duotone ki-information-5 fs-3 text-primary me-3 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <div class="text-gray-700 fs-7">
            <strong>Score:</strong> English (<?= $studentScore['english'] ?? '—' ?>) + Best <?= $bestN ?> subjects
            = <strong><?= $studentScore['total'] ?></strong> / <?= $studentScore['max'] ?> marks.
        </div>
    </div>
    <!--end::Score Summary-->

    <!--begin::Subject Chart-->
    <div class="card card-flush mb-5">
        <div class="card-header pt-5">
            <h3 class="card-title fw-bold fs-6 text-gray-700">Mark by Subject</h3>
        </div>
        <div class="card-body pt-2">
            <div id="chart-student-marks" style="min-height:240px;"></div>
        </div>
    </div>
    <!--end::Subject Chart-->
    <?php endif; ?>

    <?php if (empty($subjects)): ?>
    <!--begin::Assign Subjects Card-->
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold fs-5 text-gray-900">
                    <i class="ki-duotone ki-book-open fs-3 me-2 text-warning"><span class="path1"></span><span class="path2"></span></i>
                    Assign Exam Subjects
                </h3>
            </div>
        </div>
        <div class="card-body py-4">

            <div id="exam-none-notice">
                <div class="notice d-flex bg-light-warning rounded border border-warning border-dashed p-4 mb-6">
                    <i class="ki-duotone ki-information-5 fs-2tx text-warning me-4 flex-shrink-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <div class="text-gray-700 fs-7">
                        No subjects have been registered for this student yet. Select the subjects below and click
                        <strong>Assign Subjects</strong> to register them.
                    </div>
                </div>
            </div>

            <?php if (empty($streamSubjects['core']) && empty($streamSubjects['optional'])): ?>
            <div class="text-center text-muted py-8">
                <i class="ki-duotone ki-book fs-3x text-muted mb-3 d-block"><span class="path1"></span><span class="path2"></span></i>
                <p class="fs-6">No subjects are configured for this student's stream.</p>
                <p class="fs-7 text-muted">Please configure core and optional subjects for the stream first.</p>
            </div>
            <?php else: ?>

            <form method="post" action="<?= base_url('exam/student/' . $studentExam['student_exam_id'] . '/subjects/assign') ?>">
                <?= csrf_field() ?>

                <?php if (!empty($streamSubjects['core'])): ?>
                <div class="mb-5">
                    <div class="fw-semibold text-gray-700 fs-6 mb-3">
                        Core Subjects
                        <span class="text-muted fs-7 fw-normal ms-1">(all pre-selected — uncheck to exclude)</span>
                    </div>
                    <div class="row g-3">
                        <?php foreach ($streamSubjects['core'] as $s): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="exam_core_subs[]"
                                       value="<?= esc($s['sch_sub_id']) ?>"
                                       id="core_<?= $s['sch_sub_id'] ?>" checked>
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
                                <?php foreach ($options as $i => $s): ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="exam_opt_group_<?= $grpNum ?>"
                                               value="<?= esc($s['sch_sub_id']) ?>"
                                               id="opt_<?= $grpNum ?>_<?= $s['sch_sub_id'] ?>"
                                               <?= $i === 0 ? 'checked' : '' ?>>
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
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Assign Subjects
                    </button>
                </div>
            </form>
            <?php endif; ?>

        </div>
    </div>
    <!--end::Assign Subjects Card-->

    <?php else: ?>
    <!--begin::Marks Card-->
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold fs-5 text-gray-900">
                    <i class="ki-duotone ki-award fs-3 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Subject Marks
                    <span class="badge badge-light-primary ms-2"><?= count($subjects) ?> subject<?= count($subjects) !== 1 ? 's' : '' ?></span>
                </h3>
            </div>
            <div class="card-toolbar gap-2">
                <a href="<?= base_url('exam/student/' . $studentExam['student_exam_id'] . '/marks/report/pdf') ?>"
                   target="_blank"
                   class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-printer fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Generate PDF
                </a>
                <?php if ($canEnterMarks): ?>
                <a href="<?= base_url('exam/student/' . $studentExam['student_exam_id'] . '/subjects/assign') ?>"
                   class="btn btn-sm btn-light-warning">
                    <i class="ki-duotone ki-pencil fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Manage Subjects
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body py-4">

            <?php if (!$canEnterMarks): ?>
            <div class="alert alert-warning d-flex align-items-center mb-5">
                <i class="ki-duotone ki-information-5 fs-2 me-3 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div>You have view-only access. Contact your administrator to enter or update marks.</div>
            </div>
            <?php endif; ?>

            <?php if ($canEnterMarks): ?>
            <form id="marks-form" method="post"
                  action="<?= base_url('exam/student/' . $studentExam['student_exam_id'] . '/marks/save') ?>">
                <?= csrf_field() ?>
            <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-200px">Subject</th>
                                <th class="min-w-100px">Type</th>
                                <th class="min-w-160px">Mark (1–100)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($subjects as $sub):
                            $markVal = ((int)$sub['exam_mark'] > 0) ? (int)$sub['exam_mark'] : '';
                        ?>
                        <tr>
                            <td class="fw-semibold text-gray-900"><?= esc($sub['subject_name']) ?></td>
                            <td>
                                <span class="badge badge-light-<?= $sub['sub_type'] === 'Core' ? 'primary' : 'warning' ?> fs-8">
                                    <?= esc($sub['sub_type']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($canEnterMarks): ?>
                                <div>
                                    <input type="number"
                                           name="marks[<?= $sub['stud_sub_id'] ?>]"
                                           class="form-control form-control-sm form-control-solid mark-input"
                                           style="max-width:110px;"
                                           value="<?= $markVal ?>"
                                           min="1" max="100" step="1"
                                           placeholder="1–100">
                                    <div class="invalid-feedback d-block text-danger fs-8 mt-1 mark-error" style="display:none!important;"></div>
                                </div>
                                <?php else: ?>
                                <span class="fw-bold"><?= ($markVal !== '') ? $markVal : '—' ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php if ($canEnterMarks): ?>
                <div class="d-flex justify-content-between align-items-center pt-5 border-top mt-2">
                    <div class="text-muted fs-8">
                        <i class="ki-duotone ki-information-5 fs-6 me-1 text-muted"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        Mark must be a whole number between 1 and 100. Leave blank to save later.
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Save Marks
                    </button>
                </div>
            </form>
            <?php endif; ?>

        </div>
    </div>
    <!--end::Marks Card-->
    <?php endif; ?>

</div>
</div>

<script>
<?php if (!empty($subjects) && $studentScore['sat']): ?>
// ── Student marks bar chart (amCharts 5) ─────────────────────────────────
(function() {
    var subData = <?= json_encode(array_map(fn($s) => [
        'sub'  => $s['subject_name'],
        'mark' => (int)$s['exam_mark'] > 0 ? (int)$s['exam_mark'] : 0,
        'type' => $s['sub_type'],
    ], $subjects)) ?>;

    var root = am5.Root.new('chart-student-marks');
    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(am5xy.XYChart.new(root, { panX: false, panY: false }));

    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        categoryField: 'sub',
        renderer: am5xy.AxisRendererX.new(root, { minGridDistance: 5 })
    }));
    xAxis.get('renderer').labels.template.setAll({ rotation: -30, centerY: am5.p50, centerX: am5.p100, fontSize: 11 });

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        min: 0, max: 100,
        renderer: am5xy.AxisRendererY.new(root, {})
    }));

    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
        xAxis: xAxis, yAxis: yAxis,
        valueYField: 'mark', categoryXField: 'sub',
        tooltip: am5.Tooltip.new(root, { labelText: '{categoryX}: {valueY}/100' }),
    }));
    series.columns.template.setAll({
        cornerRadiusTL: 4, cornerRadiusTR: 4, strokeOpacity: 0,
        templateField: 'columnSettings'
    });
    series.bullets.push(function() {
        return am5.Bullet.new(root, {
            sprite: am5.Label.new(root, { text: '{valueY}', centerY: am5.p100, populateText: true, fontSize: 11 })
        });
    });

    // Colour bars: green if >= 50, red if < 50, grey if 0
    var coloured = subData.map(function(d) {
        return Object.assign({}, d, {
            columnSettings: {
                fill: am5.color(d.mark === 0 ? 0xe4e6ef : (d.mark >= 50 ? 0x50cd89 : 0xf1416c)),
            }
        });
    });

    // Pass line at 50
    var range = yAxis.makeDataItem({ value: 50 });
    yAxis.createAxisRange(range);
    range.get('grid').setAll({ stroke: am5.color(0xf1416c), strokeWidth: 2, strokeDasharray: [4, 4] });

    xAxis.data.setAll(coloured);
    series.data.setAll(coloured);
})();
<?php endif; ?>
// ─────────────────────────────────────────────────────────────────────────────

// Client-side validation before submit
const marksForm = document.getElementById('marks-form');
if (marksForm) {
    marksForm.addEventListener('submit', function(e) {
        let valid = true;
        document.querySelectorAll('.mark-input').forEach(function(input) {
            const errEl = input.closest('td').querySelector('.mark-error');
            input.classList.remove('is-invalid');
            if (errEl) errEl.style.setProperty('display', 'none', 'important');

            const val = input.value.trim();
            if (val === '') return;

            const num = parseInt(val, 10);
            if (isNaN(num) || num < 1 || num > 100 || String(num) !== val) {
                input.classList.add('is-invalid');
                if (errEl) {
                    errEl.textContent = 'Must be a whole number between 1 and 100.';
                    errEl.style.removeProperty('display');
                }
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
            const first = document.querySelector('.mark-input.is-invalid');
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
}
</script>
