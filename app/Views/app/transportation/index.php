<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Transport Allocations
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Transportation</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <?php if ($canAdd): ?>
            <a href="<?= base_url('transportation/add') ?>" class="btn btn-sm btn-primary">
                <i class="ki-duotone ki-plus fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                New Application
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-5">

            <form class="row g-2 align-items-center mb-5">
                <div class="col-6 col-sm-auto">
                    <select name="year" class="form-select form-select-sm form-select-solid" onchange="this.form.submit()">
                        <?php for ($y = (int) date('Y') + 1; $y >= (int) date('Y') - 4; $y--): ?>
                        <option value="<?= $y ?>" <?= $y === $year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="allocations_table">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">Student</th>
                            <th class="min-w-100px">Year</th>
                            <th class="min-w-150px">E-Transport Card</th>
                            <th class="min-w-120px">Status</th>
                            <th class="min-w-100px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allocations as $row): ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('transportation/detail/' . $row['allocation_id']) ?>"
                                   class="text-gray-900 fw-bold text-hover-primary fs-6">
                                    <?= esc($row['student_fname'] . ' ' . $row['student_lname']) ?>
                                </a>
                                <?php if ($isSuperAdmin): ?>
                                <div class="text-muted fs-8"><?= esc($row['sch_name'] ?? '') ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($row['academic_year']) ?></td>
                            <td><span class="text-muted fs-7"><?= esc($row['e_transport_card_number'] ?? '—') ?></span></td>
                            <td>
                                <?php
                                    $statusColor = [
                                        'Draft'     => 'secondary',
                                        'Submitted' => 'primary',
                                        'Vetted'    => 'warning',
                                        'Approved'  => 'success',
                                        'Rejected'  => 'danger',
                                    ][$row['application_status']] ?? 'secondary';
                                ?>
                                <span class="badge badge-light-<?= $statusColor ?>"><?= esc($row['application_status']) ?></span>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('transportation/detail/' . $row['allocation_id']) ?>"
                                   class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="View">
                                    <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </a>
                                <?php if ($canEdit): ?>
                                <a href="<?= base_url('transportation/edit/' . $row['allocation_id']) ?>"
                                   class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1" title="Edit">
                                    <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                                <?php endif; ?>
                                <?php if ($canDelete): ?>
                                <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete"
                                        onclick="confirmDelete(<?= $row['allocation_id'] ?>)">
                                    <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
</div>

<script>
"use strict";
$(function () {
    $('#allocations_table').DataTable({
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
        order: [[0, 'asc']],
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
            searchPlaceholder: 'Search applications...',
            lengthMenu: 'Show _MENU_ applications',
            info: 'Showing _START_ to _END_ of _TOTAL_ applications',
            infoEmpty: 'No applications found',
            emptyTable: '<div class="text-center text-muted py-8">No transport allocations found.</div>',
            paginate: {
                previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
                next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
            },
        },
        columnDefs: [
            { orderable: false, targets: 4 },
        ],
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Application?',
        text: 'This transport allocation and its household/trip details will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
    }).then(result => {
        if (!result.isConfirmed) return;
        $.post('<?= base_url('transportation/remove/') ?>' + id, { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
            .done(function (d) {
                if (d.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted', text: 'Application removed.', timer: 1500, showConfirmButton: false });
                    setTimeout(() => location.reload(), 1600);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: d.message || 'Failed to delete.' });
                }
            })
            .fail(function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' });
            });
    });
}
</script>
