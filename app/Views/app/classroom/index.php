<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Classrooms
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Classrooms</li>
            </ul>
        </div>
        <?php if ($canAdd): ?>
        <a href="<?= base_url('classroom/add') ?>" class="btn btn-sm btn-primary">
            <i class="ki-duotone ki-plus fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Add Classroom
        </a>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Stats-->
    <?php
    $total    = count($classrooms);
    $active   = count(array_filter($classrooms, fn($c) => $c['class_status'] === 'Active'));
    $inactive = $total - $active;
    $years    = array_unique(array_column($classrooms, 'class_year'));
    rsort($years);
    ?>
    <div class="row g-4 mb-6">
        <div class="col-md-3">
            <div class="d-flex align-items-center bg-light-primary rounded p-4 gap-3">
                <i class="ki-duotone ki-element-7 fs-2tx text-primary">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div>
                    <div class="fw-bold fs-2 text-gray-800"><?= $total ?></div>
                    <div class="text-muted fs-8">Total Classrooms</div>
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
        <div class="col-md-3">
            <div class="d-flex align-items-center bg-light-info rounded p-4 gap-3">
                <i class="ki-duotone ki-calendar fs-2tx text-info">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div>
                    <div class="fw-bold fs-2 text-gray-800"><?= count($years) ?></div>
                    <div class="text-muted fs-8">Academic Years</div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Stats-->

    <!--begin::Card-->
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">

        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" id="classroom_search"
                           class="form-control form-control-solid w-250px ps-13"
                           placeholder="Search classrooms..." />
                </div>
            </div>
            <div class="card-toolbar d-flex align-items-center gap-3">
                <select id="filter_status" class="form-select form-select-sm form-select-solid w-130px">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Archived">Archived</option>
                </select>
                <select id="filter_year" class="form-select form-select-sm form-select-solid w-120px">
                    <option value="">All Years</option>
                    <?php foreach ($years as $yr): ?>
                    <option value="<?= $yr ?>"><?= $yr ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($isSuperAdmin): ?>
                <select id="filter_school" class="form-select form-select-sm form-select-solid w-180px">
                    <option value="">All Schools</option>
                    <?php
                    $schools = array_unique(array_column($classrooms, 'sch_name'));
                    sort($schools);
                    foreach ($schools as $sch):
                    ?>
                    <option value="<?= esc($sch) ?>"><?= esc($sch) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body py-4">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-4 dataTable"
                       id="classrooms_table">
                    <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                        <tr class="text-start text-muted text-uppercase gs-0">
                            <th class="min-w-200px">Classroom</th>
                            <?php if ($isSuperAdmin): ?>
                            <th class="min-w-180px">School</th>
                            <?php endif; ?>
                            <th class="min-w-130px">Stream / Level</th>
                            <th class="min-w-80px">Year</th>
                            <th class="min-w-120px">Created</th>
                            <th class="min-w-80px">Status</th>
                            <th class="min-w-80px text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        <?php foreach ($classrooms as $cls):
                            $statusColor = match($cls['class_status']) {
                                'Active'   => 'success',
                                'Inactive' => 'danger',
                                'Archived' => 'secondary',
                                default    => 'secondary'
                            };
                        ?>
                        <tr id="class_row_<?= $cls['class_id'] ?>">

                            <!--begin::Name-->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-40px flex-shrink-0">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-element-7 fs-3 text-primary">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="<?= base_url('classroom/detail/' . $cls['class_id']) ?>"
                                           class="text-gray-800 text-hover-primary fw-bold fs-7 d-block">
                                            <?= esc($cls['class_name']) ?>
                                        </a>
                                        <span class="text-muted fs-8">
                                            By <?= esc(($cls['creator_fname'] ?? '') . ' ' . ($cls['creator_lname'] ?? '')) ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <!--end::Name-->

                            <?php if ($isSuperAdmin): ?>
                            <td>
                                <span class="text-gray-700 fs-7"><?= esc($cls['sch_name'] ?? '—') ?></span>
                            </td>
                            <?php endif; ?>

                            <td>
                                <div class="fw-bold text-gray-800 fs-7"><?= esc($cls['stream_name'] ?? '—') ?></div>
                                <div class="text-muted fs-8"><?= esc($cls['level_name'] ?? '') ?></div>
                            </td>

                            <td class="fw-bold text-gray-800 fs-7"><?= esc($cls['class_year']) ?></td>

                            <td class="text-gray-600 fs-8">
                                <?= !empty($cls['class_created_at'])
                                    ? date('d M Y', strtotime($cls['class_created_at']))
                                    : '—' ?>
                            </td>

                            <td>
                                <span class="badge badge-light-<?= $statusColor ?> fs-8 fw-bold">
                                    <?= esc($cls['class_status']) ?>
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
                                        <a href="<?= base_url('classroom/detail/' . $cls['class_id']) ?>"
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
                                        <a href="<?= base_url('classroom/edit/' . $cls['class_id']) ?>"
                                           class="menu-link px-3">
                                            <span class="menu-icon">
                                                <i class="ki-duotone ki-pencil fs-5 text-warning me-2">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                            </span>
                                            Edit
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <div class="separator my-2"></div>

                                    <div class="menu-item px-3">
                                        <a href="#"
                                           class="menu-link px-3 text-danger btn-delete-classroom"
                                           data-id="<?= $cls['class_id'] ?>"
                                           data-name="<?= esc($cls['class_name']) ?>">
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
        </div>
    </div>
    <!--end::Card-->

</div>
</div>

<script>
"use strict";

const classTable = $('#classrooms_table').DataTable({
    pageLength: 15,
    lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]],
    order:      [[<?= $isSuperAdmin ? '3' : '2' ?>, 'desc']],
    dom:
        '<"row align-items-center mb-4"<"col-sm-6"l><"col-sm-6 d-flex justify-content-end"p>>' +
        't' +
        '<"row align-items-center mt-4"<"col-sm-6 text-muted fs-7"i><"col-sm-6 d-flex justify-content-end"p>>',
    language: {
        lengthMenu: 'Show _MENU_',
        info:       'Showing _START_ to _END_ of _TOTAL_ classrooms',
        infoEmpty:  'No classrooms found',
        emptyTable: '<div class="text-center text-muted py-10">No classroom records found</div>',
        paginate: {
            previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
            next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
        }
    },
    columnDefs: [{ targets: -1, orderable: false }],
    drawCallback: function() {
        if (typeof KTMenu !== 'undefined') KTMenu.init();
        $('.dataTables_paginate .paginate_button').addClass('btn btn-sm btn-light me-1');
        $('.dataTables_paginate .paginate_button.current').removeClass('btn-light').addClass('btn-primary');
    }
});

$('#classroom_search').on('keyup', function() {
    classTable.search($(this).val()).draw();
});

$('#filter_status').on('change', function() {
    classTable.column(<?= $isSuperAdmin ? '5' : '4' ?>).search($(this).val()).draw();
});

$('#filter_year').on('change', function() {
    classTable.column(<?= $isSuperAdmin ? '3' : '2' ?>).search($(this).val()).draw();
});

<?php if ($isSuperAdmin): ?>
$('#filter_school').on('change', function() {
    classTable.column(1).search($(this).val()).draw();
});
<?php endif; ?>

$(document).on('click', '.btn-delete-classroom', function(e) {
    e.preventDefault();
    const id   = $(this).data('id');
    const name = $(this).data('name');

    Swal.fire({
        title: 'Delete Classroom?',
        html:
            '<p class="text-gray-700 mb-3">Delete classroom <strong>' + name + '</strong>?</p>' +
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
            url:  '<?= base_url('classroom/delete') ?>/' + id,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    classTable.row('#class_row_' + id).remove().draw();
                    Swal.fire({ title: 'Deleted!', text: response.message, icon: 'success',
                        timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire({ title: 'Failed', text: response.message, icon: 'error',
                        buttonsStyling: false, confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' } });
                }
            }
        });
    });
});
</script>