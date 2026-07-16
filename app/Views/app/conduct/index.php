<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Conduct
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Conduct</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('conduct/report') ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-chart-pie-3 fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                Report
            </a>
            <?php if ($canAdd): ?>
            <a href="<?= base_url('conduct/add') ?>" class="btn btn-sm btn-primary">
                <i class="ki-duotone ki-plus fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                Log Incident
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Stats-->
    <div class="row g-4 mb-6">
        <div class="col-sm-4">
            <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-body py-5 text-center">
                    <div class="fs-2 fw-bold text-gray-900"><?= $totalIncidents ?></div>
                    <div class="text-muted fs-7">Total Incidents</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-body py-5 text-center">
                    <div class="fs-2 fw-bold text-success">+<?= $positivePoints ?></div>
                    <div class="text-muted fs-7">Positive Points</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-body py-5 text-center">
                    <div class="fs-2 fw-bold text-danger"><?= $negativePoints ?></div>
                    <div class="text-muted fs-7">Negative Points</div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Stats-->

    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-5">

            <!--begin::Filters-->
            <form id="filter_form" class="row g-2 align-items-center mb-5">
                <div class="col-6 col-sm-auto">
                    <select name="category" class="form-select form-select-sm form-select-solid" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach (array_keys($typesGrouped) as $cat): ?>
                        <option value="<?= esc($cat) ?>" <?= ($filters['category'] ?? '') === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-sm-auto">
                    <select name="is_positive" class="form-select form-select-sm form-select-solid" onchange="this.form.submit()">
                        <option value="">Positive &amp; Negative</option>
                        <option value="1" <?= ($filters['is_positive'] ?? '') === '1' ? 'selected' : '' ?>>Positive Only</option>
                        <option value="0" <?= (isset($filters['is_positive']) && $filters['is_positive'] === '0') ? 'selected' : '' ?>>Negative Only</option>
                    </select>
                </div>
                <div class="col-6 col-sm-auto">
                    <select name="is_resolved" class="form-select form-select-sm form-select-solid" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="1" <?= ($filters['is_resolved'] ?? '') === '1' ? 'selected' : '' ?>>Resolved</option>
                        <option value="0" <?= (isset($filters['is_resolved']) && $filters['is_resolved'] === '0') ? 'selected' : '' ?>>Unresolved</option>
                    </select>
                </div>
                <div class="col-6 col-sm-auto">
                    <input type="date" name="date_from" class="form-control form-control-sm form-control-solid"
                           value="<?= esc($filters['date_from'] ?? '') ?>" onchange="this.form.submit()">
                </div>
                <div class="col-6 col-sm-auto">
                    <input type="date" name="date_to" class="form-control form-control-sm form-control-solid"
                           value="<?= esc($filters['date_to'] ?? '') ?>" onchange="this.form.submit()">
                </div>
                <?php if (!empty(array_filter($filters))): ?>
                <div class="col-6 col-sm-auto">
                    <a href="<?= base_url('conduct') ?>" class="btn btn-sm btn-light-danger">Clear</a>
                </div>
                <?php endif; ?>
            </form>
            <!--end::Filters-->

            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="incidents_table">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">Student</th>
                            <th class="min-w-150px">Type</th>
                            <th class="min-w-80px">Points</th>
                            <th class="min-w-100px">Severity</th>
                            <th class="min-w-120px">Date</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-100px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($incidents as $row): ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('conduct/detail/' . $row['incident_id']) ?>"
                                   class="text-gray-900 fw-bold text-hover-primary fs-6">
                                    <?= esc($row['student_fname'] . ' ' . $row['student_lname']) ?>
                                </a>
                                <?php if ($isSuperAdmin): ?>
                                <div class="text-muted fs-8"><?= esc($row['sch_id_fk'] ?? '') ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-light-<?= !empty($row['is_positive']) ? 'success' : 'danger' ?>">
                                    <?= esc($row['type_name'] ?? '—') ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold <?= (int) $row['points_awarded'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= (int) $row['points_awarded'] >= 0 ? '+' : '' ?><?= $row['points_awarded'] ?>
                                </span>
                            </td>
                            <td><span class="text-muted fs-7"><?= esc($row['severity_level'] ?? '—') ?></span></td>
                            <td><span class="text-muted fs-7"><?= esc(date('d M Y', strtotime($row['incident_date']))) ?></span></td>
                            <td>
                                <span class="badge badge-light-<?= !empty($row['is_resolved']) ? 'success' : 'warning' ?>">
                                    <?= !empty($row['is_resolved']) ? 'Resolved' : 'Open' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('conduct/detail/' . $row['incident_id']) ?>"
                                   class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="View">
                                    <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </a>
                                <?php if ($canEdit): ?>
                                <a href="<?= base_url('conduct/edit/' . $row['incident_id']) ?>"
                                   class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1" title="Edit">
                                    <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                                <?php endif; ?>
                                <?php if ($canDelete): ?>
                                <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete"
                                        onclick="confirmDelete(<?= $row['incident_id'] ?>)">
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
    $('#incidents_table').DataTable({
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
        order: [[4, 'desc']],
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
            searchPlaceholder: 'Search incidents...',
            lengthMenu: 'Show _MENU_ incidents',
            info: 'Showing _START_ to _END_ of _TOTAL_ incidents',
            infoEmpty: 'No incidents found',
            emptyTable: '<div class="text-center text-muted py-8">No conduct incidents found.</div>',
            paginate: {
                previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
                next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
            },
        },
        columnDefs: [
            { orderable: false, targets: 6 },
        ],
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Incident?',
        text: 'This incident and all its files, actions and notifications will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
    }).then(result => {
        if (!result.isConfirmed) return;
        $.post('<?= base_url('conduct/remove/') ?>' + id, { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
            .done(function (d) {
                if (d.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted', text: 'Incident removed.', timer: 1500, showConfirmButton: false });
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
