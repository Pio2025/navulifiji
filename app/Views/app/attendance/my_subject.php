<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                My Subject Attendance
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Subject Attendance</li>
            </ul>
        </div>
        <button type="button" onclick="history.back()" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back
        </button>
    </div>
</div>
<!--end::Toolbar-->

<?php
$streamName = '';
if (!empty($streamInfo)) {
    $streamName = $streamInfo['stream_name'] ?? '';
    if (!empty($streamInfo['level_name'])) $streamName .= ' (' . $streamInfo['level_name'] . ')';
}

// Build per-subject summary
$bySubject = [];
$total = count($records ?? []);
$present = 0; $absent = 0;
foreach ($records ?? [] as $r) {
    $subj = $r['subject_name'] ?? '—';
    $s    = strtolower($r['attendance_status'] ?? '');
    if (!isset($bySubject[$subj])) {
        $bySubject[$subj] = ['present' => 0, 'absent' => 0, 'late' => 0, 'other' => 0, 'total' => 0];
    }
    $bySubject[$subj]['total']++;
    if ($s === 'present')     { $bySubject[$subj]['present']++; $present++; }
    elseif ($s === 'absent')  { $bySubject[$subj]['absent']++;  $absent++; }
    elseif ($s === 'late')    $bySubject[$subj]['late']++;
    else                      $bySubject[$subj]['other']++;
}
$overallPct    = $total > 0 ? round($present / $total * 100, 1) : 0;
$overallColor  = $overallPct >= 80 ? 'success' : ($overallPct >= 60 ? 'warning' : 'danger');
?>

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if (!$streamId): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-16 text-muted">
            <i class="ki-duotone ki-information-5 fs-4x text-gray-200 mb-4">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <div class="fs-6 fw-semibold text-gray-600 mb-2">No Classroom Selected</div>
            <div class="fs-7 mb-5">Please navigate here from your classroom's Attendance tab.</div>
            <a href="<?= base_url('classroom/my') ?>" class="btn btn-primary btn-sm">Go to My Classroom</a>
        </div>
    </div>
    <?php else: ?>

    <!--begin::Stream header-->
    <?php if ($streamName): ?>
    <div class="d-flex align-items-center gap-3 mb-6">
        <i class="ki-duotone ki-calendar-tick fs-2 text-info">
            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            <span class="path4"></span><span class="path5"></span><span class="path6"></span>
        </i>
        <div>
            <span class="fw-bold text-gray-900 fs-5"><?= esc($streamName) ?></span>
            <span class="text-muted fs-7 ms-2">— Subject Attendance</span>
        </div>
    </div>
    <?php endif; ?>
    <!--end::Stream header-->

    <!--begin::Summary cards-->
    <div class="row g-5 mb-6">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold fs-1 text-gray-900 mb-1"><?= $total ?></div>
                    <div class="text-muted fs-7">Total Records</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold fs-1 text-success mb-1"><?= $present ?></div>
                    <div class="text-muted fs-7">Present</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold fs-1 text-danger mb-1"><?= $absent ?></div>
                    <div class="text-muted fs-7">Absent</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <div class="fw-bold fs-1 text-<?= $overallColor ?> mb-1"><?= $overallPct ?>%</div>
                    <div class="text-muted fs-7">Attendance Rate</div>
                    <?php if ($total > 0): ?>
                    <div class="progress mt-2" style="height:4px;">
                        <div class="progress-bar bg-<?= $overallColor ?>" style="width:<?= $overallPct ?>%"></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!--end::Summary cards-->

    <?php if (!empty($bySubject)): ?>
    <!--begin::Per-subject summary-->
    <div class="card border-0 shadow-sm mb-6">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-book-open fs-3 text-info">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <h5 class="fw-bold text-gray-800 mb-0">By Subject</h5>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted fs-8 bg-light">
                            <th class="ps-4 rounded-start min-w-160px">Subject</th>
                            <th class="min-w-80px text-center">Present</th>
                            <th class="min-w-80px text-center">Absent</th>
                            <th class="min-w-80px text-center">Late</th>
                            <th class="min-w-80px text-center">Total</th>
                            <th class="min-w-120px rounded-end">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bySubject as $subjName => $counts):
                        $sPct   = $counts['total'] > 0 ? round($counts['present'] / $counts['total'] * 100, 1) : 0;
                        $sColor = $sPct >= 80 ? 'success' : ($sPct >= 60 ? 'warning' : 'danger');
                    ?>
                    <tr>
                        <td class="ps-4 fw-semibold text-gray-800 fs-7"><?= esc($subjName) ?></td>
                        <td class="text-center"><span class="badge badge-light-success"><?= $counts['present'] ?></span></td>
                        <td class="text-center"><span class="badge badge-light-danger"><?= $counts['absent'] ?></span></td>
                        <td class="text-center"><span class="badge badge-light-warning"><?= $counts['late'] ?></span></td>
                        <td class="text-center fw-bold text-gray-800 fs-8"><?= $counts['total'] ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-<?= $sColor ?>" style="width:<?= $sPct ?>%"></div>
                                </div>
                                <span class="fw-bold fs-8 text-<?= $sColor ?> w-35px text-end"><?= $sPct ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Per-subject summary-->
    <?php endif; ?>

    <!--begin::Full records table-->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-calendar-8 fs-3 text-primary">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <h5 class="fw-bold text-gray-800 mb-0">All Records</h5>
                    <span class="badge badge-light-primary fs-9 ms-1"><?= $total ?></span>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <?php if (empty($records)): ?>
            <div class="text-center py-16 text-muted">
                <i class="ki-duotone ki-book-open fs-4x text-gray-200 mb-4">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                <div class="fs-6 fw-semibold text-gray-600 mb-2">No subject attendance records found</div>
                <div class="fs-8">No subject attendance has been recorded for your class yet.</div>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3" id="subj-att-table">
                    <thead>
                        <tr class="fw-bold text-muted fs-8 bg-light">
                            <th class="ps-4 min-w-40px rounded-start">#</th>
                            <th class="min-w-140px">Date</th>
                            <th class="min-w-160px">Subject</th>
                            <th class="min-w-110px">Status</th>
                            <th class="min-w-180px rounded-end">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($records as $i => $r):
                        $status    = $r['attendance_status'] ?? '';
                        $statusLow = strtolower($status);
                        $badgeColor = match($statusLow) {
                            'present' => 'success',
                            'absent'  => 'danger',
                            'late'    => 'warning',
                            default   => 'secondary',
                        };
                        $date = !empty($r['attendance_date']) ? date('D, d M Y', strtotime($r['attendance_date'])) : '—';
                    ?>
                    <tr>
                        <td class="ps-4 text-muted fs-8"><?= $i + 1 ?></td>
                        <td class="fw-semibold text-gray-800 fs-7"><?= esc($date) ?></td>
                        <td class="fs-7"><?= esc($r['subject_name'] ?? '—') ?></td>
                        <td>
                            <span class="badge badge-light-<?= $badgeColor ?> fs-8"><?= esc($status ?: '—') ?></span>
                        </td>
                        <td class="text-muted fs-8"><?= esc($r['attendance_note'] ?? '') ?: '<span class="fst-italic">—</span>' ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!--end::Full records table-->

    <?php endif; ?>

</div>
</div>

<script>
(function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable && document.getElementById('subj-att-table')) {
        $('#subj-att-table').DataTable({
            order: [[1, 'desc']],
            pageLength: 25,
            columnDefs: [{ orderable: false, targets: [0, 4] }],
            language: {
                search: '', searchPlaceholder: 'Search...',
                lengthMenu: 'Show _MENU_',
                info: 'Showing _START_–_END_ of _TOTAL_ records',
                paginate: { previous: '‹', next: '›' }
            },
            dom: '<"d-flex align-items-center justify-content-between mb-4"lf>rt<"d-flex align-items-center justify-content-between mt-4"ip>'
        });
    }
})();
</script>
