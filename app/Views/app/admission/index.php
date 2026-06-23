<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Admissions
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Admissions</li>
            </ul>
        </div>
        <?php if ($canAdd): ?>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('admission/add') ?>" class="btn btn-sm btn-primary">
                <i class="ki-duotone ki-plus fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Add Admission
            </a>
        </div>
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

        <!--begin::Card body-->
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
                               id="admission_search"
                               class="form-control form-control-solid ps-12 w-100"
                               placeholder="Search admissions..." />
                    </div>
                </div>
                <!--end::Search-->

                <!--begin::Status filter-->
                <div class="col-6 col-md-auto">
                    <select id="filter_status" class="form-select form-select-sm form-select-solid w-100">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <!--end::Status filter-->

                <!--begin::Role filter-->
                <div class="col-6 col-md-auto">
                    <select id="filter_role" class="form-select form-select-sm form-select-solid w-100">
                        <option value="">All Roles</option>
                        <?php
                        $roles = array_unique(array_column($admissions, 'role_name'));
                        sort($roles);
                        foreach ($roles as $r):
                        ?>
                        <option value="<?= esc($r) ?>"><?= esc($r) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!--end::Role filter-->

                <!--begin::Generate Report-->
                <div class="col-12 col-md-auto">
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
            <div class="row g-4 mb-6">
                <?php
                $total    = count($admissions);
                $active   = count(array_filter($admissions, fn($a) => $a['admission_status'] === 'Active'));
                $pending  = count(array_filter($admissions, fn($a) => $a['admission_status'] === 'Pending'));
                $inactive = $total - $active - $pending;
                ?>
                <div class="col-md-3">
                    <div class="d-flex align-items-center bg-light-primary rounded p-4 gap-3">
                        <i class="ki-duotone ki-element-plus fs-2tx text-primary">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $total ?></div>
                            <div class="text-muted fs-8">Total</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="d-flex align-items-center bg-light-warning rounded p-4 gap-3">
                        <i class="ki-duotone ki-time fs-2tx text-warning">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $pending ?></div>
                            <div class="text-muted fs-8">Pending</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center bg-light-danger rounded p-4 gap-3">
                        <i class="ki-duotone ki-cross-circle fs-2tx text-danger">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $inactive ?></div>
                            <div class="text-muted fs-8">Other</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Stats-->

            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4 dataTable"
                       id="admissions_table">
                    <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                        <tr class="text-start text-muted text-uppercase gs-0">
                            <th class="min-w-200px">User</th>
                            <th class="min-w-120px">Role</th>
                            <th class="min-w-180px">School</th>
                            <th class="min-w-120px">Admission Date</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-80px text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        <?php foreach ($admissions as $adm):
                            $fullName = trim(
                                ($adm['fname'] ?? '') . ' ' .
                                (!empty($adm['oname']) ? $adm['oname'] . ' ' : '') .
                                ($adm['lname'] ?? '')
                            );
                            $statusColors = [
                                'Active'   => 'success',
                                'Pending'  => 'warning',
                                'Inactive' => 'danger',
                                'Rejected' => 'danger',
                            ];
                            $statusColor = $statusColors[$adm['admission_status'] ?? ''] ?? 'secondary';
                        ?>
                        <tr id="admission_row_<?= $adm['admission_id'] ?>">

                            <!--begin::User-->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-40px symbol-circle flex-shrink-0">
                                        <?php if (!empty($adm['profile_photo'])): ?>
                                            <img src="<?= base_url('uploads/profilePhoto/' . $adm['profile_photo']) ?>"
                                                 alt="<?= esc($fullName) ?>" />
                                        <?php else: ?>
                                            <div class="symbol-label bg-light-primary text-primary fw-bold fs-7">
                                                <?= strtoupper(substr($adm['fname'] ?? 'U', 0, 1) . substr($adm['lname'] ?? '', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <a href="<?= base_url('admission/detail/' . $adm['admission_id']) ?>"
                                           class="text-gray-800 text-hover-primary fw-bold fs-7 d-block">
                                            <?= esc($fullName) ?>
                                        </a>
                                        <span class="text-muted fs-8"><?= esc($adm['email'] ?? '') ?></span>
                                    </div>
                                </div>
                            </td>
                            <!--end::User-->

                            <!--begin::Role-->
                            <td>
                                <span class="badge badge-light-info fs-8">
                                    <?= esc($adm['role_name'] ?? '—') ?>
                                </span>
                                <div class="text-muted fs-9 mt-1">
                                    <?= esc($adm['role_cat_name'] ?? '') ?>
                                </div>
                            </td>
                            <!--end::Role-->

                            <!--begin::School-->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php
                                    $logoPath = FCPATH . 'uploads/school/logo/' . ($adm['sch_logo'] ?? '');
                                    if (!empty($adm['sch_logo']) && file_exists($logoPath)):
                                    ?>
                                    <img src="<?= base_url('uploads/school/logo/' . $adm['sch_logo']) ?>"
                                         class="rounded w-25px h-25px object-fit-cover flex-shrink-0"
                                         alt="<?= esc($adm['sch_name']) ?>" />
                                    <?php else: ?>
                                    <div class="symbol symbol-25px flex-shrink-0">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-bank fs-6 text-primary">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <span class="text-gray-700 fs-7">
                                        <?= esc($adm['sch_name'] ?? '—') ?>
                                    </span>
                                </div>
                            </td>
                            <!--end::School-->

                            <!--begin::Date-->
                            <td class="text-gray-700 fs-7">
                                <?= !empty($adm['admission_date'])
                                    ? date('d M Y', strtotime($adm['admission_date']))
                                    : '—' ?>
                            </td>
                            <!--end::Date-->

                            <!--begin::Status-->
                            <td>
                                <span class="badge badge-light-<?= $statusColor ?> fs-8 fw-bold">
                                    <?= esc($adm['admission_status'] ?? 'Unknown') ?>
                                </span>
                            </td>
                            <!--end::Status-->

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

                                    <!--begin::View-->
                                    <div class="menu-item px-3">
                                        <a href="<?= base_url('admission/detail/' . $adm['admission_id']) ?>"
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
                                    <!--end::View-->

                                    <?php if ($canEdit): ?>
                                    <!--begin::Edit-->
                                    <div class="menu-item px-3">
                                        <a href="<?= base_url('admission/edit/' . $adm['admission_id']) ?>"
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
                                    <!--end::Edit-->
                                    <?php endif; ?>

                                    <?php if ($canDelete): ?>
                                    <div class="separator my-2"></div>
                                    <!--begin::Delete-->
                                    <div class="menu-item px-3">
                                        <a href="#"
                                           class="menu-link px-3 text-danger btn-delete-admission"
                                           data-id="<?= $adm['admission_id'] ?>"
                                           data-name="<?= esc($fullName) ?>"
                                           data-school="<?= esc($adm['sch_name'] ?? '') ?>">
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
                                    <!--end::Delete-->
                                    <?php endif; ?>

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
        <!--end::Card body-->
    </div>
    <!--end::Card-->

</div>
</div>
<!--end::Content-->

<script>
"use strict";

// ── DataTable ─────────────────────────────────────────────────────
const admissionsTable = $('#admissions_table').DataTable({
    pageLength:  15,
    lengthMenu:  [[10, 15, 25, 50], [10, 15, 25, 50]],
    order:       [[3, 'desc']],
    dom:
        '<"row align-items-center mb-4"' +
            '<"col-sm-6"l>' +
            '<"col-sm-6 d-flex justify-content-end"p>' +
        '>' +
        't' +
        '<"row align-items-center mt-4"' +
            '<"col-sm-6 text-muted fs-7"i>' +
            '<"col-sm-6 d-flex justify-content-end"p>' +
        '>',
    language: {
        lengthMenu:  'Show _MENU_ admissions',
        info:        'Showing _START_ to _END_ of _TOTAL_ admissions',
        infoEmpty:   'No admissions found',
        emptyTable:  '<div class="text-center text-muted py-10">No admission records found</div>',
        paginate: {
            previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
            next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
        }
    },
    columnDefs: [
        { targets: 5, orderable: false }
    ],
    drawCallback: function() {
        // Re-init KTMenu after each draw so dropdowns work on paginated rows
        KTMenu.init();
        $('.dataTables_paginate .paginate_button').addClass('btn btn-sm btn-light me-1');
        $('.dataTables_paginate .paginate_button.current').removeClass('btn-light').addClass('btn-primary');
    }
});

// ── Search ────────────────────────────────────────────────────────
$('#admission_search').on('keyup', function() {
    admissionsTable.search($(this).val()).draw();
});

// ── Status filter ─────────────────────────────────────────────────
$('#filter_status').on('change', function() {
    admissionsTable.column(4).search($(this).val()).draw();
});

// ── Role filter ───────────────────────────────────────────────────
$('#filter_role').on('change', function() {
    admissionsTable.column(1).search($(this).val()).draw();
});

// ── Generate Report ───────────────────────────────────────────────
$('#btn_generate_report').on('click', function () {
    const status = $('#filter_status').val();
    const role   = $('#filter_role').val();

    const missing = [];
    if (!status) missing.push('<strong>Status</strong>');
    if (!role)   missing.push('<strong>Role</strong>');

    if (missing.length > 0) {
        Swal.fire({
            icon:              'warning',
            title:             'Selection Required',
            html:              'Please select ' + missing.join(' and ') + ' before generating the report.',
            buttonsStyling:    false,
            confirmButtonText: 'OK',
            customClass:       { confirmButton: 'btn btn-warning' },
        });
        return;
    }

    const url = '<?= base_url('admission/report') ?>?status=' + encodeURIComponent(status) + '&role=' + encodeURIComponent(role);
    window.open(url, '_blank');
});

// ── Delete ────────────────────────────────────────────────────────
$(document).on('click', '.btn-delete-admission', function(e) {
    e.preventDefault();

    const id     = $(this).data('id');
    const name   = $(this).data('name');
    const school = $(this).data('school');

    Swal.fire({
        title: 'Delete Admission?',
        html:
            '<p class="text-gray-700 mb-3 fs-6">You are about to delete the admission for:</p>' +
            '<div class="bg-light-danger rounded p-4 mb-4 text-start">' +
                '<div class="fw-bold text-gray-800 fs-6">' + name + '</div>' +
                '<div class="text-muted fs-8">' + school + '</div>' +
            '</div>' +
            '<p class="text-danger fw-semibold fs-8 mb-0">' +
                '<i class="ki-duotone ki-information-5 fs-5 me-1 text-danger">' +
                    '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
                '</i>' +
                'All related enrolment records will also be deleted permanently.' +
            '</p>',
        icon:              'warning',
        showCancelButton:  true,
        buttonsStyling:    false,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-3',
            cancelButton:  'btn btn-light',
        },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;

        $.ajax({
            url:  '<?= base_url('admission/delete') ?>/' + id,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    admissionsTable.row('#admission_row_' + id).remove().draw();
                    Swal.fire({
                        title:             'Deleted!',
                        text:              response.message,
                        icon:              'success',
                        timer:             2000,
                        showConfirmButton: false,
                    });
                } else {
                    Swal.fire({
                        title:             'Failed',
                        text:              response.message,
                        icon:              'error',
                        buttonsStyling:    false,
                        confirmButtonText: 'Close',
                        customClass:       { confirmButton: 'btn btn-danger' }
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title:             'Error',
                    text:              'An error occurred. Please try again.',
                    icon:              'error',
                    buttonsStyling:    false,
                    confirmButtonText: 'Close',
                    customClass:       { confirmButton: 'btn btn-danger' }
                });
            }
        });
    });
});

<?php if (!empty($noActiveAdmission)): ?>
Swal.fire({
    icon:             'warning',
    title:            'No Active Admission',
    text:             'You do not have an active admission and cannot view any admission data.',
    confirmButtonText: 'OK',
    customClass:      { confirmButton: 'btn btn-warning' },
    allowOutsideClick: false,
}).then(function () {
    if (document.referrer && document.referrer !== window.location.href) {
        window.location.href = document.referrer;
    } else {
        window.location.href = '<?= base_url('dashboard') ?>';
    }
});
<?php endif; ?>
</script>