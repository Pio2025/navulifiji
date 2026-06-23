<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Generated References
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('user') ?>" class="text-muted text-hover-primary">Users</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('user/detail/' . $userID) ?>"
                       class="text-muted text-hover-primary">
                        <?= esc($user['fname'] . ' ' . $user['lname']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">References</li>
            </ul>
        </div>
        <a href="<?= base_url('user/detail/' . $userID) ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back to Profile
        </a>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <!--begin::Card-->
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">

        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text"
                           id="ref_search"
                           class="form-control form-control-solid w-250px ps-13"
                           placeholder="Search references..." />
                </div>
                <!--end::Search-->
            </div>

            <div class="card-toolbar d-flex align-items-center gap-3">
                <!--begin::Filter by status-->
                <select id="filter_status" class="form-select form-select-sm form-select-solid w-150px">
                    <option value="">All Status</option>
                    <option value="Current">Current</option>
                    <option value="Outdated">Outdated</option>
                </select>
                <!--end::Filter-->

                <!--begin::Filter by type-->
                <select id="filter_type" class="form-select form-select-sm form-select-solid w-200px">
                    <option value="">All Types</option>
                    <?php
                    $types = array_unique(array_column($references, 'ref_cat_name'));
                    sort($types);
                    foreach ($types as $type):
                    ?>
                    <option value="<?= esc($type) ?>"><?= esc($type) ?></option>
                    <?php endforeach; ?>
                </select>
                <!--end::Filter-->
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">

            <!--begin::Stats row-->
            <div class="row g-4 mb-6">
                <?php
                $total    = count($references);
                $modern   = count(array_filter($references, fn($r) => $r['gen_ref_status'] === 'Current'));
                $outdated = $total - $modern;
                ?>
                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-light-primary rounded p-4 gap-3">
                        <i class="ki-duotone ki-folder fs-2tx text-primary">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $total ?></div>
                            <div class="text-muted fs-7">Total References</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-light-success rounded p-4 gap-3">
                        <i class="ki-duotone ki-verify fs-2tx text-success">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $modern ?></div>
                            <div class="text-muted fs-7">Current</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center bg-light-danger rounded p-4 gap-3">
                        <i class="ki-duotone ki-information fs-2tx text-danger">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div>
                            <div class="fw-bold fs-2 text-gray-800"><?= $outdated ?></div>
                            <div class="text-muted fs-7">Outdated</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Stats row-->

            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-4 dataTable"
                   id="ref_table">
                <thead>
                    <tr class="text-start text-muted text-uppercase fw-bold fs-7 border-bottom border-gray-200">
                        <th class="min-w-200px">Reference Type</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-150px">Generated By</th>
                        <th class="min-w-150px">Date & Time</th>
                        <th class="min-w-80px text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    <?php if (!empty($references)): ?>
                        <?php foreach ($references as $ref): ?>
                        <tr data-ref-id="<?= $ref['gen_ref_id'] ?>"
                            data-status="<?= esc($ref['gen_ref_status']) ?>"
                            data-type="<?= esc($ref['ref_cat_name']) ?>">

                            <!--begin::Type-->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-40px">
                                        <div class="symbol-label
                                            <?= $ref['gen_ref_status'] === 'Modern' ? 'bg-light-primary' : 'bg-light-secondary' ?>">
                                            <i class="ki-duotone ki-document fs-3
                                                <?= $ref['gen_ref_status'] === 'Modern' ? 'text-primary' : 'text-muted' ?>">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-gray-800 fs-7 d-block">
                                            <?= esc($ref['ref_cat_name']) ?>
                                        </span>
                                        <span class="text-muted fs-8">
                                            <?= esc($ref['gen_ref_file_name']) ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <!--end::Type-->

                            <!--begin::Status-->
                            <td>
                                <?php if ($ref['gen_ref_status'] === 'Current'): ?>
                                    <span class="badge badge-light-success fs-8 fw-bold">
                                        <i class="ki-duotone ki-check-circle fs-6 me-1">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        Current
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-light-danger fs-8 fw-bold">
                                        <i class="ki-duotone ki-information-5 fs-6 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        Outdated
                                    </span>
                                <?php endif; ?>
                            </td>
                            <!--end::Status-->

                            <!--begin::Generated By-->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="symbol symbol-30px symbol-circle">
                                        <div class="symbol-label bg-light-info fs-8 fw-bold text-info">
                                            <?= strtoupper(substr($ref['gen_by_fname'], 0, 1) . substr($ref['gen_by_lname'], 0, 1)) ?>
                                        </div>
                                    </div>
                                    <span class="text-gray-700 fs-7">
                                        <?= esc($ref['gen_by_fname'] . ' ' . $ref['gen_by_lname']) ?>
                                    </span>
                                </div>
                            </td>
                            <!--end::Generated By-->

                            <!--begin::Date-->
                            <td>
                                <span class="text-gray-700 fw-bold fs-7 d-block">
                                    <?= date('d M Y', strtotime($ref['gen_ref_date'])) ?>
                                </span>
                                <span class="text-muted fs-8">
                                    <?= date('h:i A', $ref['gen_ref_time']) ?>
                                </span>
                            </td>
                            <!--end::Date-->

                            <!--begin::Actions-->
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <!--begin::View-->
                                    <a href="<?= base_url('reference/view/' . $ref['gen_ref_id']) ?>"
                                       target="_blank"
                                       class="btn btn-icon btn-sm btn-light-primary"
                                       title="View PDF">
                                        <i class="ki-duotone ki-eye fs-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </a>
                                    <!--end::View-->

                                    <!--begin::Delete-->
                                    <button type="button"
                                            class="btn btn-icon btn-sm btn-light-danger btn-delete-ref"
                                            data-ref-id="<?= $ref['gen_ref_id'] ?>"
                                            data-ref-name="<?= esc($ref['ref_cat_name']) ?>"
                                            data-ref-date="<?= date('d M Y', strtotime($ref['gen_ref_date'])) ?>"
                                            title="Delete">
                                        <i class="ki-duotone ki-trash fs-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                    <!--end::Delete-->
                                </div>
                            </td>
                            <!--end::Actions-->

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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

// ── DataTable init ────────────────────────────────────────────────
const table = $('#ref_table').DataTable({
    pageLength:  10,
    lengthMenu:  [[10, 25, 50, 100], [10, 25, 50, 100]],
    order:       [[3, 'desc']],
    dom:
        '<"row align-items-center mb-4"' +
            '<"col-sm-6"l>' +
            '<"col-sm-6 d-flex justify-content-end"p>' +
        '>' +
        '<"row"<"col-12"tr>>' +
        '<"row align-items-center mt-4"' +
            '<"col-sm-6 text-muted fs-7"i>' +
            '<"col-sm-6 d-flex justify-content-end"p>' +
        '>',
    language: {
        search:          '',
        lengthMenu:      'Show _MENU_ per page',
        info:            'Showing _START_ to _END_ of _TOTAL_ references',
        infoEmpty:       'No references found',
        infoFiltered:    '(filtered from _MAX_ total)',
        paginate: {
            previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
            next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
        },
        emptyTable: `
            <div class="text-center py-15">
                <i class="ki-duotone ki-folder-up fs-4x text-muted mb-4 d-block">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div class="text-muted fw-semibold fs-6">No references generated yet</div>
                <div class="text-gray-500 fs-7 mt-1">References generated for this user will appear here</div>
            </div>
        `,
    },
    columnDefs: [
        { targets: 4, orderable: false },  // Actions column not sortable
    ],
    drawCallback: function() {
        // Re-style pagination buttons
        $('.dataTables_paginate .paginate_button').addClass('btn btn-sm btn-light me-1');
        $('.dataTables_paginate .paginate_button.current').removeClass('btn-light').addClass('btn-primary');
    }
});

// ── Live search via our custom input ─────────────────────────────
$('#ref_search').on('keyup', function() {
    table.search($(this).val()).draw();
});

// ── Status filter ─────────────────────────────────────────────────
$('#filter_status').on('change', function() {
    table.column(1).search($(this).val()).draw();
});

// ── Type filter ───────────────────────────────────────────────────
$('#filter_type').on('change', function() {
    table.column(0).search($(this).val()).draw();
});

// ── Delete reference ──────────────────────────────────────────────
$(document).on('click', '.btn-delete-ref', function() {
    const btn      = $(this);
    const refId    = btn.data('ref-id');
    const refName  = btn.data('ref-name');
    const refDate  = btn.data('ref-date');

    Swal.fire({
        title: 'Delete Reference?',
        html:
            '<div class="text-start">' +
            '<p class="text-gray-700 mb-2">You are about to permanently delete:</p>' +
            '<div class="bg-light-danger rounded p-4 mb-3">' +
                '<div class="fw-bold text-gray-800 fs-6">' + refName + '</div>' +
                '<div class="text-muted fs-7">Generated on: ' + refDate + '</div>' +
            '</div>' +
            '<p class="text-danger fw-semibold fs-7 mb-0">' +
                '<i class="ki-duotone ki-information-5 fs-5 me-1 text-danger">' +
                    '<span class="path1"></span><span class="path2"></span><span class="path3"></span>' +
                '</i>' +
                'This will delete the PDF file and database record permanently. This action cannot be undone.' +
            '</p>' +
            '</div>',
        icon: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton:  'btn btn-light',
        },
        reverseButtons: true,
    }).then(function(result) {
        if (!result.isConfirmed) return;

        // Show loading state on button
        btn.attr('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url:  '<?= base_url('reference/delete') ?>/' + refId,
            type: 'POST',
            data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
            success: function(response) {
                if (response.success) {
                    // Remove row from DataTable
                    table.row(btn.closest('tr')).remove().draw();

                    // Update stats
                    updateStats();

                    Swal.fire({
                        title: 'Deleted!',
                        text:  response.message,
                        icon:  'success',
                        timer: 2000,
                        showConfirmButton: false,
                        buttonsStyling: false,
                    });
                } else {
                    btn.attr('disabled', false);
                    btn.html(
                        '<i class="ki-duotone ki-trash fs-4">' +
                            '<span class="path1"></span><span class="path2"></span>' +
                            '<span class="path3"></span><span class="path4"></span><span class="path5"></span>' +
                        '</i>'
                    );
                    Swal.fire({
                        title: 'Failed',
                        text:  response.message,
                        icon:  'error',
                        buttonsStyling: false,
                        confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' }
                    });
                }
            },
            error: function() {
                btn.attr('disabled', false);
                btn.html(
                    '<i class="ki-duotone ki-trash fs-4">' +
                        '<span class="path1"></span><span class="path2"></span>' +
                        '<span class="path3"></span><span class="path4"></span><span class="path5"></span>' +
                    '</i>'
                );
                Swal.fire({
                    title: 'Error',
                    text:  'An error occurred. Please try again.',
                    icon:  'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Close',
                    customClass: { confirmButton: 'btn btn-danger' }
                });
            }
        });
    });
});

// ── Update stats badges after delete ─────────────────────────────
function updateStats() {
    let total    = 0;
    let modern   = 0;
    let outdated = 0;

    table.rows().every(function() {
        const row = $(this.node());
        total++;
        if (row.data('status') === 'Current') modern++;
        else outdated++;
    });

    // Update the stat numbers
    $('.bg-light-primary .fw-bold.fs-2').text(total);
    $('.bg-light-success .fw-bold.fs-2').text(modern);
    $('.bg-light-danger .fw-bold.fs-2').text(outdated);
}
</script>