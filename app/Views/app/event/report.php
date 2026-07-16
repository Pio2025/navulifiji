<?php
$events    = $events    ?? [];
$stats     = $stats     ?? ['total'=>0,'Upcoming'=>0,'Ongoing'=>0,'Completed'=>0,'Cancelled'=>0];
$breakdown = $breakdown ?? [];
$year      = $year      ?? date('Y');

use App\Models\EventModel;
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Event Report</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('event') ?>" class="text-muted text-hover-primary">Events</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Report</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('event') ?>" class="btn btn-sm btn-light fw-bold">
                <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Back to Events
            </a>
            <a href="<?= base_url('event/calendar') ?>" class="btn btn-sm btn-light-info fw-bold">
                <i class="ki-duotone ki-calendar fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Calendar
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <!--begin::Year filter-->
    <div class="card card-flush mb-6">
        <div class="card-body py-4 px-5 d-flex align-items-center gap-3 flex-wrap">
            <span class="fw-semibold text-gray-700 fs-7">Filter by Year:</span>
            <form method="GET" action="<?= base_url('event/report') ?>" class="d-flex align-items-center gap-2">
                <select name="year" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <?php for ($y = (int)date('Y'); $y >= (int)date('Y') - 5; $y--): ?>
                    <option value="<?= $y ?>" <?= (int)$year === $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </form>
            <span class="text-muted fs-7"><?= count($events) ?> event<?= count($events) !== 1 ? 's' : '' ?> in <?= $year ?></span>
        </div>
    </div>
    <!--end::Year filter-->

    <!--begin::Summary stats-->
    <div class="row g-4 mb-6">
        <?php
        $statCards = [
            ['label' => 'Total',     'value' => $stats['total'],     'color' => 'primary'],
            ['label' => 'Upcoming',  'value' => $stats['Upcoming'],  'color' => 'info'],
            ['label' => 'Ongoing',   'value' => $stats['Ongoing'],   'color' => 'success'],
            ['label' => 'Completed', 'value' => $stats['Completed'], 'color' => 'secondary'],
            ['label' => 'Cancelled', 'value' => $stats['Cancelled'], 'color' => 'danger'],
        ];
        foreach ($statCards as $c):
        ?>
        <div class="col">
            <div class="card card-flush text-center py-5">
                <div class="fs-1 fw-bolder text-<?= $c['color'] ?>"><?= $c['value'] ?></div>
                <div class="fs-7 text-muted"><?= $c['label'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <!--end::Summary stats-->

    <div class="row g-5">

        <!--begin::Type breakdown-->
        <div class="col-xl-4">
            <div class="card card-flush h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-800 fs-6">By Event Type</h3>
                </div>
                <div class="card-body pt-0">
                    <?php if (empty($breakdown)): ?>
                    <div class="text-muted fs-7 text-center py-5">No data available.</div>
                    <?php else: ?>
                    <?php
                    $bTotal = array_sum(array_column($breakdown, 'cnt'));
                    foreach ($breakdown as $row):
                        $pct = $bTotal > 0 ? round(($row['cnt'] / $bTotal) * 100) : 0;
                        $color = EventModel::typeColor($row['event_type']);
                    ?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-1 flex-shrink-0" style="width:10px;height:10px;background:<?= $color ?>;display:inline-block;"></span>
                                <span class="fs-7 fw-semibold text-gray-700"><?= esc($row['event_type']) ?></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold fs-7"><?= $row['cnt'] ?></span>
                                <span class="text-muted fs-8">(<?= $pct ?>%)</span>
                            </div>
                        </div>
                        <div class="progress h-6px">
                            <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $color ?>;" role="progressbar"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!--end::Type breakdown-->

        <!--begin::Events table-->
        <div class="col-xl-8">
            <div class="card card-flush">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-800 fs-6">Events in <?= $year ?></h3>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 fs-7" id="report_table">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="ps-3">Event</th>
                                    <th class="w-100px">Type</th>
                                    <th class="w-100px">Start</th>
                                    <th class="w-100px">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($events)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-8">No events for <?= $year ?>.</td></tr>
                            <?php else: ?>
                            <?php foreach ($events as $e): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="rounded-circle flex-shrink-0" style="width:8px;height:8px;background:<?= esc($e['color'] ?: EventModel::typeColor($e['event_type'])) ?>;"></span>
                                        <a href="<?= base_url('event/detail/' . (int)$e['event_id']) ?>" class="text-gray-800 text-hover-primary fw-semibold"><?= esc($e['title']) ?></a>
                                    </div>
                                    <?php if ($e['location']): ?>
                                    <div class="text-muted fs-8 ms-3"><?= esc($e['location']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge <?= EventModel::typeBadge($e['event_type']) ?>"><?= esc($e['event_type']) ?></span></td>
                                <td class="text-muted"><?= esc(date('d M', strtotime($e['start_date']))) ?></td>
                                <td><span class="badge <?= EventModel::statusBadge($e['status']) ?>"><?= esc($e['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Events table-->

    </div>

</div>
</div>
<!--end::Content-->

<script>
"use strict";
$(function () {
    $('#report_table').DataTable({
        pageLength: 20,
        order: [[2, 'asc']],
        dom:
            '<"row align-items-center mb-4"' +
                '<"col-sm-6"l>' +
                '<"col-sm-6 d-flex justify-content-end"f>' +
            '>' +
            't' +
            '<"row align-items-center mt-4"' +
                '<"col-sm-6 text-muted fs-7"i>' +
                '<"col-sm-6 d-flex justify-content-end"p>' +
            '>',
        language: {
            search: '',
            searchPlaceholder: 'Search...',
            lengthMenu: 'Show _MENU_',
            info: '_START_–_END_ of _TOTAL_',
            infoEmpty: 'No events',
        },
    });
});
</script>
