<?php
$dayKeys    = ['M', 'T', 'W', 'TH', 'F'];
$dayLabels  = ['M' => 'M', 'T' => 'T', 'W' => 'W', 'TH' => 'TH', 'F' => 'F'];
$streamName = esc($streamInfo['stream_name'] ?? '');
$levelName  = esc($streamInfo['level_name']  ?? '');
$numWeeks   = count($weeks);
$termMonthStart = !empty($termInfo['term_start_month'])
    ? date('M', mktime(0, 0, 0, (int)$termInfo['term_start_month'], 1)) : '';
$termMonthEnd   = !empty($termInfo['term_end_month'])
    ? date('M', mktime(0, 0, 0, (int)$termInfo['term_end_month'],   1)) : '';
$printTitle = esc($termLabel) . ' ' . (int)$termNo . ' Daily Attendance — ' . $streamName . ' (' . date('Y') . ')';
?>

<!--begin::Toolbar (hidden in print)-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6 no-print">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($termLabel) ?> <?= (int)$termNo ?> — Student Daily Attendance
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('attendance') ?>" class="text-muted text-hover-primary">Attendance</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($termLabel) ?> <?= (int)$termNo ?> Grid</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('attendance/grid/pdf?stream_id=' . (int)$streamId . '&term=' . (int)$termNo) ?>"
               class="btn btn-light-danger btn-sm" target="_blank">
                <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Download PDF (A3)
            </a>
            <button type="button" onclick="window.print()" class="btn btn-light-primary btn-sm">
                <i class="ki-duotone ki-printer fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                Print (A3)
            </button>
            <a href="<?= base_url('attendance') ?>" class="btn btn-light btn-sm">
                <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>
                Back
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="no-print"><?= $this->include('templates/flash_messages') ?></div>

        <!--begin::Print header (only visible when printing)-->
        <div class="print-only mb-4">
            <div class="fw-bold fs-4 text-gray-900"><?= $printTitle ?></div>
            <div class="fs-7 text-muted">
                <?= (int)$numWeeks ?> weeks
                <?php if ($termMonthStart): ?>
                &nbsp;·&nbsp; <?= (int)($termInfo['term_start_day'] ?? '') ?> <?= $termMonthStart ?>
                to <?= (int)($termInfo['term_end_day'] ?? '') ?> <?= $termMonthEnd ?>
                <?php endif; ?>
                &nbsp;·&nbsp; Printed <?= date('d M Y') ?>
            </div>
        </div>
        <!--end::Print header-->

        <!--begin::Info card (hidden in print)-->
        <div class="card mb-5 no-print">
            <div class="card-body py-4">
                <div class="d-flex align-items-center gap-5 flex-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-light-success rounded-2" style="width:48px;height:48px;">
                            <i class="ki-duotone ki-calendar-tick fs-2 text-success">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                            </i>
                        </div>
                        <div>
                            <div class="fw-bold text-gray-800 fs-5"><?= $streamName ?><?= $levelName ? " ({$levelName})" : '' ?></div>
                            <div class="text-muted fs-7">
                                <?= esc($termLabel) ?> <?= (int)$termNo ?>
                                &nbsp;·&nbsp; <?= (int)$numWeeks ?> weeks
                                <?php if ($termMonthStart): ?>
                                &nbsp;·&nbsp; <?= (int)($termInfo['term_start_day'] ?? '') ?> <?= $termMonthStart ?>
                                – <?= (int)($termInfo['term_end_day'] ?? '') ?> <?= $termMonthEnd ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="ms-auto d-flex align-items-center gap-3 flex-wrap">
                        <span class="d-flex align-items-center gap-1">
                            <span class="att-demo is-present"></span>
                            <span class="fs-8 text-gray-700">Present</span>
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="att-demo is-absent"></span>
                            <span class="fs-8 text-gray-700">Absent</span>
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="att-demo is-empty"></span>
                            <span class="fs-8 text-gray-700">Not marked</span>
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="att-demo is-holiday"></span>
                            <span class="fs-8 text-gray-700">Public Holiday</span>
                        </span>
                        <button type="button" class="btn btn-sm btn-light-warning no-print ms-2" data-bs-toggle="modal" data-bs-target="#manageHolidaysModal">
                            <i class="ki-duotone ki-calendar-add fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            Holidays
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Info card-->

        <?php if (empty($students)): ?>
        <div class="card no-print">
            <div class="card-body text-center py-16">
                <i class="ki-duotone ki-people fs-4x text-gray-200 mb-4">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    <span class="path4"></span><span class="path5"></span>
                </i>
                <div class="fs-6 fw-semibold text-gray-600 mb-2">No students enrolled in this stream</div>
                <div class="text-muted fs-7">Students must be actively enrolled before attendance can be marked.</div>
            </div>
        </div>
        <?php else: ?>

        <script>
        function attToggle(cell) {
            var inp = cell.querySelector('.att-val');
            if (cell.classList.contains('is-empty')) {
                cell.classList.remove('is-empty');
                cell.classList.add('is-present');
                if (inp) inp.value = 'P';
            } else if (cell.classList.contains('is-present')) {
                cell.classList.remove('is-present');
                cell.classList.add('is-absent');
                if (inp) inp.value = 'A';
            } else {
                cell.classList.remove('is-absent');
                cell.classList.add('is-empty');
                if (inp) inp.value = '';
            }
        }
        </script>

        <!--begin::Grid form-->
        <form method="POST" action="<?= base_url('attendance/grid/save') ?>" id="att_grid_form">
            <?= csrf_field() ?>
            <input type="hidden" name="stream_id" value="<?= (int)$streamId ?>">
            <input type="hidden" name="term_no"   value="<?= (int)$termNo ?>">

            <div class="card">
                <div class="card-header border-0 pt-5 pb-3 no-print">
                    <div class="card-title fw-bold text-gray-800 fs-6">
                        <?= count($students) ?> student<?= count($students) !== 1 ? 's' : '' ?>
                    </div>
                    <div class="card-toolbar gap-2">
                        <button type="button" class="btn btn-sm btn-light-warning" id="markAllPresent">
                            <i class="ki-duotone ki-check fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            All Present Today
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="ki-duotone ki-check-circle fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Save Attendance
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="att-grid-wrapper">
                        <table class="att-grid-table" id="att_grid_table">
                            <thead>
                                <tr class="att-head-week">
                                    <th class="att-th-num"  rowspan="2">#</th>
                                    <th class="att-th-name" rowspan="2">Student Name</th>
                                    <?php foreach ($weeks as $wi => $weekDays): ?>
                                    <th colspan="5" class="att-th-week <?= $wi % 2 === 0 ? 'week-even' : 'week-odd' ?>">
                                        Week <?= $wi + 1 ?>
                                    </th>
                                    <?php endforeach; ?>
                                    <th colspan="3" class="att-th-week att-th-summary">Summary</th>
                                </tr>
                                <tr class="att-head-day">
                                    <?php foreach ($weeks as $wi => $weekDays): ?>
                                    <?php foreach ($dayKeys as $dk): ?>
                                    <th class="att-th-day <?= $wi % 2 === 0 ? 'week-even' : 'week-odd' ?> <?= $dk === 'M' ? 'week-sep' : '' ?>">
                                        <div class="day-label"><?= $dk ?></div>
                                        <div class="day-date"><?= date('d/m', strtotime($weekDays[$dk] ?? date('Y-m-d'))) ?></div>
                                    </th>
                                    <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    <th class="att-th-day att-sum-p week-sep">P</th>
                                    <th class="att-th-day att-sum-a">A</th>
                                    <th class="att-th-day att-sum-pct">%</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($students as $si => $student):
                                $enrolId  = (int) $student['enrol_id'];
                                $admId    = (int) $student['admission_id_fk'];
                                $fullName = trim(($student['lname'] ?? '') . ', ' . ($student['fname'] ?? ''));
                            ?>
                            <tr class="att-row <?= $si % 2 === 0 ? '' : 'att-row-alt' ?>">
                                <td class="att-td-num"><?= $si + 1 ?></td>
                                <td class="att-td-name"><?= esc($fullName) ?></td>
                                <?php foreach ($weeks as $wi => $weekDays): ?>
                                <?php foreach ($dayKeys as $dk):
                                    $date      = $weekDays[$dk] ?? '';
                                    $isFuture  = $date > $today;
                                    $isToday   = $date === $today;
                                    $isHoliday = isset($holidays[$date]);
                                    $status    = $attendance[$enrolId][$date] ?? null;
                                    $isPresent = ($status === 'Present');
                                    $tdClass   = ($wi % 2 === 0 ? 'week-even' : 'week-odd')
                                               . ($dk === 'M' ? ' week-sep' : '')
                                               . ($isToday   ? ' day-today' : '');
                                ?>
                                <td class="att-td-cell <?= $tdClass ?>">
                                    <?php if ($isHoliday): ?>
                                    <span class="att-holiday" title="<?= esc($holidays[$date]) ?>">H</span>
                                    <?php elseif ($isFuture): ?>
                                    <span class="att-future">—</span>
                                    <?php else: ?>
                                    <span class="att-cell <?= $isPresent ? 'is-present' : ($status === 'Absent' ? 'is-absent' : 'is-empty') ?>" onclick="attToggle(this)">
                                        <input type="hidden"
                                               name="att[<?= $admId ?>][<?= esc($date) ?>]"
                                               class="att-val"
                                               value="<?= $isPresent ? 'P' : ($status === 'Absent' ? 'A' : '') ?>">
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; ?>
                                <?php endforeach; ?>
                                <?php
                                    $st  = $studentStats[$enrolId] ?? ['present'=>0,'absent'=>0,'unmarked'=>0,'pct'=>0];
                                    $pctClass = $st['pct'] >= 80 ? 'text-success' : ($st['pct'] >= 70 ? 'text-warning' : 'text-danger');
                                ?>
                                <td class="att-td-cell att-sum-p week-sep fw-bold text-success"><?= $st['present'] ?></td>
                                <td class="att-td-cell att-sum-a fw-bold text-danger"><?= $st['absent'] ?></td>
                                <td class="att-td-cell att-sum-pct fw-bold <?= $pctClass ?>"><?= $st['pct'] ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center py-4 no-print">
                    <div class="d-flex align-items-center gap-3 text-muted fs-8">
                        <span>Click a cell to toggle: <strong>—</strong> → <strong class="text-success">✓</strong> → <strong class="text-danger">✗</strong> → <strong>—</strong></span>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="ki-duotone ki-check-circle fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                        Save Attendance
                    </button>
                </div>
            </div>
        </form>
        <!--end::Grid form-->

        <?php endif; ?>

        <?php if (!empty($students) && !empty($summaryStats)): ?>
        <!--begin::Statistics section-->
        <div class="no-print mt-6">

            <!--begin::Summary stat cards-->
            <div class="row g-4 mb-6">
                <?php
                $statCards = [
                    ['label'=>'Total Students',   'value'=>$summaryStats['totalStudents'],           'icon'=>'ki-people',         'color'=>'primary'],
                    ['label'=>'Days Marked',       'value'=>$summaryStats['markedDays'],              'icon'=>'ki-calendar-tick',  'color'=>'info'],
                    ['label'=>'Class Average',     'value'=>$summaryStats['classAvgPct'].'%',         'icon'=>'ki-chart-line-up',  'color'=>($summaryStats['classAvgPct']>=80?'success':'danger')],
                    ['label'=>'Perfect Attendance','value'=>$summaryStats['perfectCount'],             'icon'=>'ki-verify',         'color'=>'success'],
                    ['label'=>'Below 80%',         'value'=>$summaryStats['belowThreshCount'],         'icon'=>'ki-information',    'color'=>'danger'],
                ];
                foreach ($statCards as $card):
                ?>
                <div class="col">
                    <div class="card card-flush h-100">
                        <div class="card-body d-flex align-items-center gap-4 py-5 px-6">
                            <div class="d-flex align-items-center justify-content-center bg-light-<?= $card['color'] ?> rounded-2" style="width:48px;height:48px;flex-shrink:0">
                                <i class="ki-duotone <?= $card['icon'] ?> fs-2 text-<?= $card['color'] ?>">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                                </i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold text-gray-800"><?= $card['value'] ?></div>
                                <div class="fs-7 text-muted"><?= $card['label'] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!--end::Summary stat cards-->

            <!--begin::Charts row-->
            <div class="row g-4 mb-6">
                <div class="col-lg-7">
                    <div class="card card-flush h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title fw-bold text-gray-800 fs-6">Student Attendance Rate (%)</h3>
                        </div>
                        <div class="card-body pt-0 pb-4">
                            <div id="att_bar_chart" style="min-height:<?= max(count($students)*28, 200) ?>px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card card-flush h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title fw-bold text-gray-800 fs-6">Weekly Attendance Rate (%)</h3>
                        </div>
                        <div class="card-body pt-0 pb-4">
                            <div id="att_weekly_chart" style="min-height:280px"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Charts row-->

            <!--begin::Per-student summary table-->
            <div class="card mb-6">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-800 fs-6">Student Attendance Summary</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 mb-0 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 w-30px">#</th>
                                <th>Student Name</th>
                                <th class="text-center w-70px">Present</th>
                                <th class="text-center w-70px">Absent</th>
                                <th class="text-center w-80px">Not Marked</th>
                                <th class="text-center w-80px">Att. Rate</th>
                                <th class="text-center w-90px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sortedStudents = $students;
                        usort($sortedStudents, function($a, $b) use ($studentStats) {
                            return ($studentStats[(int)$b['enrol_id']]['pct'] ?? 0) <=> ($studentStats[(int)$a['enrol_id']]['pct'] ?? 0);
                        });
                        foreach ($sortedStudents as $si => $s):
                            $eid = (int)$s['enrol_id'];
                            $st  = $studentStats[$eid] ?? ['present'=>0,'absent'=>0,'unmarked'=>0,'pct'=>0];
                            $name = trim(($s['lname'] ?? '') . ', ' . ($s['fname'] ?? ''));
                            if ($st['pct'] >= 90)     { $badge = 'badge-light-success'; $label = 'Excellent'; }
                            elseif ($st['pct'] >= 80) { $badge = 'badge-light-primary'; $label = 'Good'; }
                            elseif ($st['pct'] >= 70) { $badge = 'badge-light-warning'; $label = 'Average'; }
                            else                      { $badge = 'badge-light-danger';  $label = 'At Risk'; }
                        ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $si + 1 ?></td>
                            <td class="fw-semibold text-gray-800"><?= esc($name) ?></td>
                            <td class="text-center fw-bold text-success"><?= $st['present'] ?></td>
                            <td class="text-center fw-bold text-danger"><?= $st['absent'] ?></td>
                            <td class="text-center text-muted"><?= $st['unmarked'] ?></td>
                            <td class="text-center">
                                <span class="fw-bold <?= $st['pct'] >= 80 ? 'text-success' : ($st['pct'] >= 70 ? 'text-warning' : 'text-danger') ?>">
                                    <?= $st['pct'] ?>%
                                </span>
                            </td>
                            <td class="text-center"><span class="badge <?= $badge ?>"><?= $label ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Per-student summary table-->

        </div>
        <!--end::Statistics section-->
        <?php endif; ?>

    </div>
</div>
<!--end::Content-->

<!--begin::Manage Holidays Modal-->
<div class="modal fade" id="manageHolidaysModal" tabindex="-1" aria-labelledby="manageHolidaysModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header py-4">
                <h5 class="modal-title fw-bold fs-6" id="manageHolidaysModalLabel">
                    <i class="ki-duotone ki-calendar-add fs-3 text-warning me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    Manage Public Holidays
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-2">
                <!--begin::Add form-->
                <form id="hol_add_form" class="mb-5">
                    <?= csrf_field() ?>
                    <div class="fw-semibold fs-7 text-gray-700 mb-3">Add a Public Holiday</div>
                    <div class="d-flex gap-3 align-items-end">
                        <div class="flex-shrink-0">
                            <label class="form-label fs-8 text-muted mb-1">Date</label>
                            <input type="date" id="hol_date" class="form-control form-control-sm" style="width:145px;" required>
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label fs-8 text-muted mb-1">Holiday Name</label>
                            <input type="text" id="hol_name" class="form-control form-control-sm" placeholder="e.g. Constitution Day" required>
                        </div>
                        <div class="flex-shrink-0">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ki-duotone ki-plus fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                Add
                            </button>
                        </div>
                    </div>
                </form>
                <!--end::Add form-->
                <!--begin::Holiday list-->
                <div class="separator mb-4"></div>
                <div class="fw-semibold fs-7 text-gray-700 mb-3">Existing Holidays</div>
                <div style="max-height:280px;overflow-y:auto;">
                    <table class="table table-row-bordered align-middle gs-0 fs-7 mb-0">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4">Date</th>
                                <th>Name</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody id="hol_list">
                        <?php if (empty($allSchoolHolidays)): ?>
                        <tr id="hol_empty_row">
                            <td colspan="3" class="text-center text-muted py-4 fs-8">No holidays added yet</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($allSchoolHolidays as $hol): ?>
                        <tr data-hid="<?= (int)$hol['holiday_id'] ?>">
                            <td class="ps-4 fs-7 fw-semibold"><?= esc($hol['holiday_date']) ?></td>
                            <td class="fs-7"><?= esc($hol['holiday_name']) ?></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-icon btn-sm btn-light-danger hol-del-btn"
                                        data-hid="<?= (int)$hol['holiday_id'] ?>" title="Remove">
                                    <i class="ki-duotone ki-trash fs-5">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!--end::Holiday list-->
            </div>
            <div class="modal-footer py-3">
                <div class="text-muted fs-8 me-auto">
                    <i class="ki-duotone ki-information fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Reload the page after changes to see updated cells.
                </div>
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Manage Holidays Modal-->

<style>
/* ── Attendance grid ────────────────────────────────────────────────── */
.att-grid-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.att-grid-table {
    border-collapse: collapse;
    width: max-content;
    min-width: 100%;
    font-size: 12px;
}
/* Fixed left columns */
.att-th-num, .att-td-num {
    position: sticky; left: 0; z-index: 3;
    background: #fff;
    min-width: 36px; width: 36px;
    text-align: center;
    padding: 6px 4px;
    border-right: 1px solid #e4e6ea;
}
.att-th-name, .att-td-name {
    position: sticky; left: 36px; z-index: 3;
    background: #fff;
    min-width: 160px;
    padding: 6px 10px;
    white-space: nowrap;
    box-shadow: 3px 0 6px -2px rgba(0,0,0,.08);
    border-right: 2px solid #e4e6ea;
}
thead .att-th-num, thead .att-th-name { z-index: 5; background: #f8f9fa; }
/* Week headers */
.att-th-week {
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    padding: 5px 4px;
    letter-spacing: .3px;
    border-bottom: 1px solid #e4e6ea;
}
/* Day headers */
.att-th-day {
    text-align: center;
    padding: 4px 2px;
    min-width: 42px;
    width: 42px;
    border-bottom: 2px solid #dee2e6;
}
.day-label { font-weight: 700; font-size: 11px; line-height: 1.2; }
.day-date  { font-size: 9px;  color: #6c757d;  line-height: 1.2; }
/* Alternating week shading */
.week-even { background-color: #f8f9fa; }
.week-odd  { background-color: #eef2ff; }
/* Week separator (left border on Monday) */
.week-sep { border-left: 2px solid #adb5bd !important; }
/* Data cells */
.att-td-cell {
    text-align: center;
    padding: 3px 2px;
    border-bottom: 1px solid #f1f3f5;
}
.day-today { outline: 2px solid #ffc107; outline-offset: -2px; }
.att-row-alt td { background-color: rgba(0,0,0,.015); }
.att-row-alt .att-td-num, .att-row-alt .att-td-name { background: #fbfcfc; }
/* Attendance toggle cell */
.att-cell {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 800;
    transition: transform .1s;
    user-select: none;
}
.att-cell input[type=hidden] { display: none; }
.att-cell.is-present { background: #d1fae5; color: #065f46; }
.att-cell.is-absent  { background: #fee2e2; color: #991b1b; }
.att-cell.is-empty   { background: #f3f4f6; color: #9ca3af; }
.att-cell.is-present::after { content: '✓'; }
.att-cell.is-absent::after  { content: '✗'; }
.att-cell.is-empty::after   { content: '—'; font-size: 12px; }
.att-cell:hover { transform: scale(1.2); }
.att-future { color: #ced4da; font-size: 12px; }
/* Legend demo dots */
.att-demo {
    display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; border-radius: 4px; font-size: 12px; font-weight: 800;
}
.att-demo.is-present { background: #d1fae5; color: #065f46; }
.att-demo.is-present::after { content: '✓'; }
.att-demo.is-absent  { background: #fee2e2; color: #991b1b; }
.att-demo.is-absent::after  { content: '✗'; }
.att-demo.is-empty   { background: #f3f4f6; color: #9ca3af; }
.att-demo.is-empty::after   { content: '—'; font-size: 10px; }
/* Holiday cell */
.att-holiday {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 5px;
    background: #ede9fe; color: #6d28d9;
    font-size: 12px; font-weight: 800; cursor: default;
}
.att-demo.is-holiday { background: #ede9fe; color: #6d28d9; }
.att-demo.is-holiday::after { content: 'H'; }

/* Summary P/A/% sticky columns */
.att-th-summary { background: #f0f4ff !important; color: #1a56db !important; }
.att-sum-p, .att-sum-a, .att-sum-pct {
    position: sticky; right: 0; z-index: 2;
    background: #f8f9ff;
    min-width: 36px; width: 36px;
    text-align: center;
    font-size: 11px;
    padding: 4px 2px;
}
.att-sum-p  { right: 78px; border-left: 2px solid #dee2e6 !important; }
.att-sum-a  { right: 42px; }
.att-sum-pct{ right: 0;    font-size: 10px; min-width: 42px; width: 42px; }
thead .att-sum-p, thead .att-sum-a, thead .att-sum-pct { background: #eef2ff; z-index: 5; }

/* ── Print / PDF (A3 landscape) ─────────────────────────────────────── */
.print-only { display: none; }
@media print {
    @page { size: A3 landscape; margin: 8mm 10mm; }
    /* Show print-only header, hide web UI chrome */
    .print-only  { display: block !important; }
    .no-print,
    #kt_app_header,
    #kt_app_sidebar,
    #kt_app_sidebar_toggle,
    .app-toolbar,
    .card-header,
    .card-footer,
    .btn           { display: none !important; }
    /* Reset layout for print */
    body, html, #kt_app_body, #kt_app_wrapper,
    #kt_app_content, #kt_app_content_container,
    .app-content, .app-container { margin: 0 !important; padding: 0 !important;
        width: 100% !important; max-width: 100% !important; display: block !important; }
    .card { border: none !important; box-shadow: none !important; }
    .card-body { padding: 0 !important; }
    /* Grid print sizing */
    .att-grid-wrapper { overflow: visible !important; }
    .att-grid-table   { font-size: 8pt !important; width: 100% !important; min-width: unset !important; }
    .att-th-num, .att-td-num  { position: static !important; min-width: 18pt !important; width: 18pt !important; font-size: 7pt !important; }
    .att-th-name,.att-td-name { position: static !important; min-width: 80pt !important; font-size: 8pt !important; box-shadow: none !important; }
    .att-th-week { font-size: 7pt !important; padding: 2pt !important; }
    .att-th-day  { min-width: 16pt !important; width: 16pt !important; padding: 1pt !important; }
    .day-label   { font-size: 7pt !important; }
    .day-date    { font-size: 6pt !important; }
    .att-td-cell { padding: 1pt !important; }
    .att-cell    { width: 16pt !important; height: 16pt !important; font-size: 10pt !important; border-radius: 2pt !important; }
    .att-future  { font-size: 7pt !important; }
    .att-cell.is-present { background: #d1fae5 !important; color: #065f46 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .att-cell.is-absent  { background: #fee2e2 !important; color: #991b1b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .att-holiday         { background: #ede9fe !important; color: #6d28d9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .week-even { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .week-odd  { background-color: #eef2ff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>

<script>
// ── ApexCharts ────────────────────────────────────────────────────────────
(function () {
    if (typeof ApexCharts === 'undefined') return;

    var studentNames = <?= json_encode(array_map(fn($s) => trim(($s['lname']??'').', '.($s['fname']??'')), $sortedStudents ?? $students)) ?>;
    var studentPcts  = <?= json_encode(array_values(array_map(fn($s) => $studentStats[(int)$s['enrol_id']]['pct'] ?? 0, $sortedStudents ?? $students))) ?>;
    var weekLabels   = <?= json_encode(array_map(fn($w) => 'W'.$w['week'], $weeklyStats)) ?>;
    var weekPcts     = <?= json_encode(array_map(fn($w) => $w['pct'], $weeklyStats)) ?>;

    // Horizontal bar — student attendance %
    var barEl = document.querySelector('#att_bar_chart');
    if (barEl) {
        new ApexCharts(barEl, {
            chart:  { type: 'bar', height: Math.max(studentNames.length * 28, 200), toolbar: { show: false } },
            plotOptions: { bar: { horizontal: true, barHeight: '60%', borderRadius: 4,
                dataLabels: { position: 'right' } } },
            dataLabels: { enabled: true, formatter: function(v) { return v + '%'; },
                style: { fontSize: '11px', fontWeight: 700 }, offsetX: 8 },
            series: [{ name: 'Attendance %', data: studentPcts }],
            xaxis:  { categories: studentNames, min: 0, max: 100,
                labels: { formatter: function(v) { return v + '%'; }, style: { fontSize: '11px' } } },
            yaxis:  { labels: { style: { fontSize: '11px' } } },
            colors: studentPcts.map(function(p) {
                return p >= 90 ? '#059669' : p >= 80 ? '#10b981' : p >= 70 ? '#f59e0b' : '#ef4444';
            }),
            grid:   { xaxis: { lines: { show: true } } },
            tooltip:{ y: { formatter: function(v) { return v + '%'; } } },
        }).render();
    }

    // Column bar — weekly trend
    var wkEl = document.querySelector('#att_weekly_chart');
    if (wkEl) {
        new ApexCharts(wkEl, {
            chart:  { type: 'bar', height: 280, toolbar: { show: false } },
            plotOptions: { bar: { columnWidth: '55%', borderRadius: 4,
                dataLabels: { position: 'top' } } },
            dataLabels: { enabled: true, formatter: function(v) { return v + '%'; },
                style: { fontSize: '11px', fontWeight: 700 }, offsetY: -18 },
            series: [{ name: 'Attendance %', data: weekPcts }],
            xaxis:  { categories: weekLabels, labels: { style: { fontSize: '11px' } } },
            yaxis:  { min: 0, max: 100, labels: { formatter: function(v) { return v + '%'; },
                style: { fontSize: '11px' } } },
            colors: weekPcts.map(function(p) {
                return p >= 90 ? '#059669' : p >= 80 ? '#10b981' : p >= 70 ? '#f59e0b' : '#ef4444';
            }),
            markers: { size: 4 },
            stroke:  { curve: 'smooth', width: 2 },
            grid:    { yaxis: { lines: { show: true } } },
            tooltip: { y: { formatter: function(v) { return v + '%'; } } },
        }).render();
    }
})();

// ── Manage Holidays modal ─────────────────────────────────────────────────
(function () {
    var addForm    = document.getElementById('hol_add_form');
    var holList    = document.getElementById('hol_list');
    var addHolUrl  = '<?= base_url('attendance/holiday/add') ?>';
    var remHolBase = '<?= base_url('attendance/holiday/remove') ?>/';
    var csrfName   = '<?= csrf_token() ?>';
    var csrfHash   = '<?= csrf_hash() ?>';

    function buildRow(h) {
        var tr = document.createElement('tr');
        tr.dataset.hid = h.id;
        tr.innerHTML =
            '<td class="ps-4 fs-7 fw-semibold">' + esc(h.date) + '</td>' +
            '<td class="fs-7">' + esc(h.name) + '</td>' +
            '<td class="text-end pe-4">' +
            '<button type="button" class="btn btn-icon btn-sm btn-light-danger hol-del-btn" data-hid="' + h.id + '" title="Remove">' +
            '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
            '</button></td>';
        return tr;
    }

    function esc(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function refreshCsrf(data) {
        if (data && data.csrf) {
            csrfHash = data.csrf;
            document.querySelectorAll('input[name="' + csrfName + '"]').forEach(function(el) { el.value = data.csrf; });
        }
    }

    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var date = document.getElementById('hol_date').value.trim();
            var name = document.getElementById('hol_name').value.trim();
            if (!date || !name) return;

            var body = new URLSearchParams();
            body.set(csrfName, csrfHash);
            body.set('holiday_date', date);
            body.set('holiday_name', name);

            fetch(addHolUrl, { method: 'POST', body: body,
                headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function (d) {
                refreshCsrf(d);
                if (d.success) {
                    var row = buildRow({ id: d.holiday_id, date: d.date, name: d.name });
                    if (holList) holList.appendChild(row);
                    addForm.reset();
                } else {
                    alert(d.message || 'Failed to add holiday.');
                }
            });
        });
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.hol-del-btn');
        if (!btn) return;
        var hid = btn.dataset.hid;
        if (!confirm('Remove this public holiday?')) return;

        var body = new URLSearchParams();
        body.set(csrfName, csrfHash);

        fetch(remHolBase + hid, { method: 'POST', body: body,
            headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.json(); })
        .then(function (d) {
            refreshCsrf(d);
            if (d.success) {
                var row = holList ? holList.querySelector('tr[data-hid="' + hid + '"]') : null;
                if (row) row.remove();
            } else {
                alert(d.message || 'Failed to remove holiday.');
            }
        });
    });
})();

// ── Mark all present for today ────────────────────────────────────────────
(function () {
    var markBtn = document.getElementById('markAllPresent');
    if (markBtn) {
        markBtn.addEventListener('click', function () {
            document.querySelectorAll('td.day-today .att-cell').forEach(function (cell) {
                if (!cell.classList.contains('is-present')) {
                    cell.classList.remove('is-empty', 'is-absent');
                    cell.classList.add('is-present');
                    var inp = cell.querySelector('.att-val');
                    if (inp) inp.value = 'P';
                }
            });
        });
    }
})();
</script>
