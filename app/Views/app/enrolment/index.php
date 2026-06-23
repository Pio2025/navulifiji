<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Enrolments
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Enrolments</li>
            </ul>
        </div>
        <?php if ($canAdd): ?>
        <a href="<?= base_url('enrolment/add') ?>" class="btn btn-sm btn-primary">
            <i class="ki-duotone ki-plus fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Add Enrolment
        </a>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Card-->
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">

        <div class="card-body py-5">

            <!--begin::Controls row-->
            <div class="row g-2 align-items-center mb-5">

                <!--begin::Search-->
                <div class="col-12 col-md">
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input type="text"
                               id="enrolment_search"
                               class="form-control form-control-solid ps-12 w-100"
                               placeholder="Search enrolments..." />
                    </div>
                </div>
                <!--end::Search-->

                <!--begin::Status filter-->
                <div class="col-6 col-sm-4 col-md-auto">
                    <select id="filter_status" class="form-select form-select-sm form-select-solid w-100">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <!--end::Status filter-->

                <!--begin::Stream filter-->
                <div class="col-6 col-sm-4 col-md-auto">
                    <select id="filter_stream" class="form-select form-select-sm form-select-solid w-100">
                        <option value="">All Streams</option>
                        <?php
                        $streams = array_unique(array_filter(array_column($enrolments, 'stream_name')));
                        sort($streams);
                        foreach ($streams as $st):
                        ?>
                        <option value="<?= esc($st) ?>"><?= esc($st) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!--end::Stream filter-->

                <!--begin::Year filter-->
                <div class="col-6 col-sm-4 col-md-auto">
                    <select id="filter_year" class="form-select form-select-sm form-select-solid w-100">
                        <option value="">All Years</option>
                        <?php
                        $years = array_unique(array_column($enrolments, 'enrol_year'));
                        rsort($years);
                        foreach ($years as $yr):
                        ?>
                        <option value="<?= $yr ?>"><?= $yr ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!--end::Year filter-->

                <!--begin::Generate Report-->
                <div class="col-6 col-sm-12 col-md-auto">
                    <button type="button" id="btn_generate_report" class="btn btn-sm btn-light-primary w-100">
                        <i class="ki-duotone ki-document fs-4 me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Generate Report
                    </button>
                </div>
                <!--end::Generate Report-->

            </div>
            <!--end::Controls row-->

            <!--begin::Stats-->
            <?php
            $total     = count($enrolments);
            $active    = count(array_filter($enrolments, fn($e) => $e['enrol_status'] === 'Active'));
            $inactive  = $total - $active;
            ?>
            <div class="row g-4 mb-6">
                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-light-primary rounded p-4 gap-3">
                        <i class="ki-duotone ki-abstract-28 fs-2tx text-primary">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $total ?></div>
                            <div class="text-muted fs-8">Total Enrolments</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-light-success rounded p-4 gap-3">
                        <i class="ki-duotone ki-verify fs-2tx text-success">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $active ?></div>
                            <div class="text-muted fs-8">Active</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-light-danger rounded p-4 gap-3">
                        <i class="ki-duotone ki-cross-circle fs-2tx text-danger">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $inactive ?></div>
                            <div class="text-muted fs-8">Inactive</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Stats-->

            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4 dataTable"
                       id="enrolments_table">
                    <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                        <tr class="text-start text-muted text-uppercase gs-0">
                            <th class="min-w-200px">Student</th>
                            <th class="min-w-150px">School</th>
                            <th class="min-w-120px">Stream / Level</th>
                            <th class="min-w-80px">Year</th>
                            <th class="min-w-70px">Term</th>
                            <th class="min-w-100px">Enrol Date</th>
                            <th class="min-w-80px">Status</th>
                            <th class="min-w-80px text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        <?php foreach ($enrolments as $enrol):
                            $fullName = trim(
                                ($enrol['fname'] ?? '') . ' ' .
                                (!empty($enrol['oname']) ? $enrol['oname'] . ' ' : '') .
                                ($enrol['lname'] ?? '')
                            );
                            $statusColors = [
                                'Active'    => 'success',
                                'Inactive'  => 'danger',
                                'Completed' => 'info',
                            ];
                            $statusColor = $statusColors[$enrol['enrol_status'] ?? ''] ?? 'secondary';
                        ?>
                        <tr id="enrol_row_<?= $enrol['enrol_id'] ?>">

                            <!--begin::Student-->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-35px symbol-circle flex-shrink-0">
                                        <?php if (!empty($enrol['profile_photo'])): ?>
                                            <img src="<?= base_url('uploads/profilePhoto/' . $enrol['profile_photo']) ?>"
                                                 alt="<?= esc($fullName) ?>" />
                                        <?php else: ?>
                                            <div class="symbol-label bg-light-primary text-primary fw-bold fs-7">
                                                <?= strtoupper(substr($enrol['fname'] ?? 'U', 0, 1) . substr($enrol['lname'] ?? '', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <a href="<?= base_url('enrolment/detail/' . $enrol['enrol_id']) ?>"
                                           class="text-gray-800 text-hover-primary fw-bold fs-7 d-block">
                                            <?= esc($fullName) ?>
                                        </a>
                                        <span class="text-muted fs-8"><?= esc($enrol['email'] ?? '') ?></span>
                                    </div>
                                </div>
                            </td>
                            <!--end::Student-->

                            <td>
                                <span class="text-gray-700 fs-7"><?= esc($enrol['sch_name'] ?? '—') ?></span>
                            </td>

                            <td>
                                <div class="fw-bold text-gray-800 fs-7"><?= esc($enrol['stream_name'] ?? '—') ?></div>
                                <div class="text-muted fs-8"><?= esc($enrol['level_name'] ?? '') ?></div>
                            </td>

                            <td class="fw-bold text-gray-800 fs-7"><?= esc($enrol['enrol_year'] ?? '—') ?></td>

                            <td>
                                <span class="badge badge-light-secondary fs-8">
                                    Term <?= esc($enrol['enrol_term'] ?? '—') ?>
                                </span>
                            </td>

                            <td class="text-gray-700 fs-7">
                                <?= !empty($enrol['enrol_date'])
                                    ? date('d M Y', strtotime($enrol['enrol_date']))
                                    : '—' ?>
                            </td>

                            <td>
                                <span class="badge badge-light-<?= $statusColor ?> fs-8 fw-bold">
                                    <?= esc($enrol['enrol_status'] ?? 'Unknown') ?>
                                </span>
                            </td>

                            <!--begin::Actions-->
                            <td class="text-end pe-3">
                                <a href="#"
                                   class="btn btn-sm btn-light btn-active-light-primary"
                                   data-kt-menu-trigger="click"
                                   data-kt-menu-placement="bottom-end">
                                    Actions
                                    <i class="ki-duotone ki-down fs-5 ms-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded
                                            menu-gray-600 menu-state-bg-light-primary fw-semibold
                                            fs-7 w-175px py-4" data-kt-menu="true">

                                    <div class="menu-item px-3">
                                        <a href="<?= base_url('enrolment/detail/' . $enrol['enrol_id']) ?>"
                                           class="menu-link px-3">
                                            <span class="menu-icon">
                                                <i class="ki-duotone ki-eye fs-5 text-primary me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span>
                                            View Detail
                                        </a>
                                    </div>

                                    <?php if ($canEdit): ?>
                                    <div class="menu-item px-3">
                                        <a href="<?= base_url('enrolment/edit/' . $enrol['enrol_id']) ?>"
                                           class="menu-link px-3">
                                            <span class="menu-icon">
                                                <i class="ki-duotone ki-pencil fs-5 text-warning me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                            Edit
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <div class="separator my-2"></div>

                                    <div class="menu-item px-3">
                                        <a href="#"
                                           class="menu-link px-3 text-danger btn-delete-enrolment"
                                           data-id="<?= $enrol['enrol_id'] ?>"
                                           data-name="<?= esc($fullName) ?>">
                                            <span class="menu-icon">
                                                <i class="ki-duotone ki-trash fs-5 text-danger me-2">
                                                    <span class="path1"></span><span class="path2"></span>
                                                    <span class="path3"></span><span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
                                            </span>
                                            Delete
                                        </a>
                                    </div>

                                </div>
                            </td>
                            <!--end::Actions-->

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!--end::Table-->

        </div>
    </div>
    <!--end::Card-->

</div>
</div>

<script>
"use strict";

const enrolTable = $('#enrolments_table').DataTable({
    pageLength:  15,
    lengthMenu:  [[10, 15, 25, 50], [10, 15, 25, 50]],
    order:       [[3, 'desc']],
    dom:
        '<"row align-items-center mb-4"<"col-sm-6"l><"col-sm-6 d-flex justify-content-end"p>>' +
        't' +
        '<"row align-items-center mt-4"<"col-sm-6 text-muted fs-7"i><"col-sm-6 d-flex justify-content-end"p>>',
    language: {
        lengthMenu:  'Show _MENU_',
        info:        'Showing _START_ to _END_ of _TOTAL_ enrolments',
        infoEmpty:   'No enrolments found',
        emptyTable:  '<div class="text-center text-muted py-10">No enrolment records found</div>',
        paginate: {
            previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
            next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
        }
    },
    columnDefs: [{ targets: 7, orderable: false }],
    drawCallback: function() {
        if (typeof KTMenu !== 'undefined') KTMenu.init();
        $('.dataTables_paginate .paginate_button').addClass('btn btn-sm btn-light me-1');
        $('.dataTables_paginate .paginate_button.current').removeClass('btn-light').addClass('btn-primary');
    }
});

$('#enrolment_search').on('keyup', function() {
    enrolTable.search($(this).val()).draw();
});

$('#filter_status').on('change', function() {
    enrolTable.column(6).search($(this).val()).draw();
});

$('#filter_stream').on('change', function() {
    const val = $(this).val();
    enrolTable.column(2).search(val ? '^' + $.fn.dataTable.util.escapeRegex(val) : '', true, false).draw();
});

$('#filter_year').on('change', function() {
    enrolTable.column(3).search($(this).val()).draw();
});

$('#btn_generate_report').on('click', function() {
    const status = $('#filter_status').val();
    const stream = $('#filter_stream').val();
    const year   = $('#filter_year').val();
    const missing = [];
    if (!status) missing.push('<strong>Status</strong>');
    if (!stream) missing.push('<strong>Stream</strong>');
    if (!year)   missing.push('<strong>Year</strong>');
    if (missing.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Selection Required',
            html: 'Please select ' + missing.join(' and ') + ' before generating a report.',
            buttonsStyling: false,
            confirmButtonText: 'OK',
            customClass: { confirmButton: 'btn btn-primary' }
        });
        return;
    }
    window.open(
        '<?= base_url('enrolment/report') ?>?status=' + encodeURIComponent(status) +
        '&stream=' + encodeURIComponent(stream) +
        '&year='   + encodeURIComponent(year),
        '_blank'
    );
});

$(document).on('click', '.btn-delete-enrolment', function(e) {
    e.preventDefault();
    const id   = $(this).data('id');
    const name = $(this).data('name');

    Swal.fire({
        title: 'Delete Enrolment?',
        html:
            '<p class="text-gray-700 mb-3">Delete enrolment for <strong>' + name + '</strong>?</p>' +
            '<p class="text-danger fw-semibold fs-8 mb-0">This action cannot be undone.</p>',
        icon:              'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-light' },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url:  '<?= base_url('enrolment/delete') ?>/' + id,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    enrolTable.row('#enrol_row_' + id).remove().draw();
                    Swal.fire({ title: 'Deleted!', text: response.message, icon: 'success', timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire({ title: 'Failed', text: response.message, icon: 'error', buttonsStyling: false, confirmButtonText: 'Close', customClass: { confirmButton: 'btn btn-danger' } });
                }
            }
        });
    });
});
</script>