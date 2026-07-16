<?php
$events      = $events      ?? [];
$stats       = $stats       ?? ['total'=>0,'Upcoming'=>0,'Ongoing'=>0,'Completed'=>0,'Cancelled'=>0];
$filters     = $filters     ?? [];
$canAdd      = $canAdd      ?? false;
$canEdit     = $canEdit     ?? false;
$canDelete   = $canDelete   ?? false;
$canDetail   = $canDetail   ?? false;
$canCalendar = $canCalendar ?? false;
$canReport   = $canReport   ?? false;

use App\Models\EventModel;
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Events</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Events</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canCalendar): ?>
            <a href="<?= base_url('event/calendar') ?>" class="btn btn-sm btn-light-info fw-bold">
                <i class="ki-duotone ki-calendar fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Calendar
            </a>
            <?php endif; ?>
            <?php if ($canReport): ?>
            <a href="<?= base_url('event/report') ?>" class="btn btn-sm btn-light fw-bold">
                <i class="ki-duotone ki-chart-pie-3 fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Report
            </a>
            <?php endif; ?>
            <?php if ($canAdd): ?>
            <a href="<?= base_url('event/add') ?>" class="btn btn-sm btn-primary fw-bold">
                <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Add Event
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Stats cards-->
    <div class="row g-4 mb-6">
        <?php
        $statCards = [
            ['label' => 'Total Events',  'value' => $stats['total'],     'color' => 'primary',   'icon' => 'ki-calendar-tick'],
            ['label' => 'Upcoming',      'value' => $stats['Upcoming'],  'color' => 'info',      'icon' => 'ki-calendar-add'],
            ['label' => 'Ongoing',       'value' => $stats['Ongoing'],   'color' => 'success',   'icon' => 'ki-calendar-8'],
            ['label' => 'Completed',     'value' => $stats['Completed'], 'color' => 'secondary', 'icon' => 'ki-check-circle'],
            ['label' => 'Cancelled',     'value' => $stats['Cancelled'], 'color' => 'danger',    'icon' => 'ki-cross-circle'],
        ];
        foreach ($statCards as $c):
        ?>
        <div class="col">
            <div class="card card-flush h-md-100">
                <div class="card-body d-flex align-items-center gap-4 py-5 px-6">
                    <div class="d-flex align-items-center justify-content-center bg-light-<?= $c['color'] ?> rounded-2" style="width:48px;height:48px;flex-shrink:0;">
                        <i class="ki-duotone <?= $c['icon'] ?> fs-2 text-<?= $c['color'] ?>"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold text-gray-800"><?= $c['value'] ?></div>
                        <div class="fs-7 text-muted"><?= $c['label'] ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <!--end::Stats cards-->

    <!--begin::Events table card-->
    <div class="card card-flush">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold text-gray-800 fs-6">All Events</h3>
            <!--begin::Filters-->
            <div class="card-toolbar d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" action="<?= base_url('event') ?>" class="d-flex align-items-center gap-2 flex-wrap" id="filter-form">
                    <select name="type" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <?php foreach (['Academic','Sports','Cultural','Meeting','Holiday','Examination','Other'] as $t): ?>
                        <option value="<?= $t ?>" <?= ($filters['type'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <?php foreach (['Upcoming','Ongoing','Completed','Cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="from" class="form-control form-control-sm w-auto" value="<?= esc($filters['from'] ?? '') ?>" placeholder="From" onchange="this.form.submit()">
                    <input type="date" name="to"   class="form-control form-control-sm w-auto" value="<?= esc($filters['to'] ?? '') ?>"   placeholder="To"   onchange="this.form.submit()">
                    <?php if (array_filter($filters)): ?>
                    <a href="<?= base_url('event') ?>" class="btn btn-sm btn-light-danger">
                        <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                        Clear
                    </a>
                    <?php endif; ?>
                </form>
            </div>
            <!--end::Filters-->
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 fs-7" id="events_table">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="ps-4 w-40px">#</th>
                            <th>Event Title</th>
                            <th class="w-110px">Type</th>
                            <th class="w-110px">Start Date</th>
                            <th class="w-110px">End Date</th>
                            <th>Location</th>
                            <th class="w-100px">Status</th>
                            <th class="w-100px text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($events)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-10">No events found.</td></tr>
                    <?php else: ?>
                    <?php foreach ($events as $i => $e): ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle d-inline-block" style="width:10px;height:10px;background:<?= esc($e['color'] ?: EventModel::typeColor($e['event_type'])) ?>;flex-shrink:0;"></span>
                                <?php if ($canDetail): ?>
                                <a href="<?= base_url('event/detail/' . (int)$e['event_id']) ?>" class="fw-semibold text-gray-800 text-hover-primary"><?= esc($e['title']) ?></a>
                                <?php else: ?>
                                <span class="fw-semibold text-gray-800"><?= esc($e['title']) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($e['organizer']): ?>
                            <div class="text-muted fs-8 ms-3">by <?= esc($e['organizer']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge <?= EventModel::typeBadge($e['event_type']) ?>"><?= esc($e['event_type']) ?></span></td>
                        <td class="fw-semibold"><?= esc(date('d M Y', strtotime($e['start_date']))) ?></td>
                        <td class="text-muted"><?= $e['end_date'] ? esc(date('d M Y', strtotime($e['end_date']))) : '—' ?></td>
                        <td class="text-muted"><?= $e['location'] ? esc($e['location']) : '—' ?></td>
                        <td><span class="badge <?= EventModel::statusBadge($e['status']) ?>"><?= esc($e['status']) ?></span></td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                <?php if ($canDetail): ?>
                                <a href="<?= base_url('event/detail/' . (int)$e['event_id']) ?>" class="btn btn-icon btn-sm btn-light-primary" title="View">
                                    <i class="ki-duotone ki-eye fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </a>
                                <?php endif; ?>
                                <?php if ($canEdit): ?>
                                <a href="<?= base_url('event/edit/' . (int)$e['event_id']) ?>" class="btn btn-icon btn-sm btn-light-warning" title="Edit">
                                    <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                                <?php endif; ?>
                                <?php if ($canDelete): ?>
                                <button class="btn btn-icon btn-sm btn-light-danger del-btn" data-id="<?= (int)$e['event_id'] ?>" data-title="<?= esc($e['title']) ?>" title="Delete">
                                    <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Events table card-->

</div>
</div>
<!--end::Content-->

<script>
"use strict";
$(function () {
    $('#events_table').DataTable({
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
        order: [[3, 'desc']],
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
            search:      '',
            searchPlaceholder: 'Search events...',
            lengthMenu:  'Show _MENU_ events',
            info:        'Showing _START_ to _END_ of _TOTAL_ events',
            infoEmpty:   'No events found',
            emptyTable:  '<div class="text-center text-muted py-10">No event records found</div>',
            paginate: {
                previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
                next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
            },
        },
        columnDefs: [
            { orderable: false, targets: 7 },
        ],
    });

    // Delete
    $(document).on('click', '.del-btn', function () {
        const id    = $(this).data('id');
        const title = $(this).data('title');
        Swal.fire({
            icon: 'warning',
            title: 'Delete Event?',
            html: 'This will permanently delete <strong>' + title + '</strong>.',
            showCancelButton: true,
            confirmButtonColor: '#f1416c',
            confirmButtonText: 'Delete',
        }).then(r => {
            if (!r.isConfirmed) return;
            $.post('<?= base_url('event/remove/') ?>' + id, { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
                .done(d => {
                    if (d.success) {
                        Swal.fire({ icon: 'success', title: 'Deleted', text: 'Event removed.', timer: 1500, showConfirmButton: false });
                        setTimeout(() => location.reload(), 1600);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: d.error || 'Failed.' });
                    }
                });
        });
    });
});
</script>
