<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Subject Catalogue</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Subjects</li>
            </ul>
        </div>
        <?php if ($canAdd): ?>
        <a href="<?= base_url('subject/add') ?>" class="btn btn-primary btn-sm">
            <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i>
            Add Subject
        </a>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$flashSuccess = session('success');
$flashError   = session('error');
?>

<!--begin::Stats-->
<div class="row g-4 mb-6">
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center gap-4 py-5">
                <div class="symbol symbol-50px flex-shrink-0">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-book fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= (int)$totalSubjects ?></div>
                    <div class="text-muted fs-7">Total Subjects</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center gap-4 py-5">
                <div class="symbol symbol-50px flex-shrink-0">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-duotone ki-check-circle fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= (int)$totalExaminable ?></div>
                    <div class="text-muted fs-7">Examinable</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center gap-4 py-5">
                <div class="symbol symbol-50px flex-shrink-0">
                    <span class="symbol-label bg-light-warning">
                        <i class="ki-duotone ki-information fs-2x text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= (int)$totalNonExam ?></div>
                    <div class="text-muted fs-7">Non-Examinable</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Stats-->

<!--begin::Table card-->
<div class="card">
    <!--begin::Card header (fully responsive)-->
    <div class="card-header border-0 flex-wrap py-5 gap-3" style="min-height:unset;">
        <div class="d-flex flex-wrap align-items-center gap-3" style="flex:1 1 auto;min-width:0;">
            <!--Search-->
            <div class="d-flex align-items-center position-relative" style="flex:1 1 200px;min-width:160px;">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" id="search-subject" class="form-control w-100 ps-12" placeholder="Search subjects...">
            </div>
            <!--Level filter-->
            <select id="filter-level" class="form-select" style="flex:1 1 155px;min-width:140px;max-width:220px;">
                <option value="">All Levels</option>
                <?php foreach ($levels as $lvl): ?>
                <option value="<?= (int)$lvl['level_id'] ?>"><?= esc($lvl['level_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <!--Type filter-->
            <select id="filter-type" class="form-select" style="flex:1 1 140px;min-width:130px;max-width:200px;">
                <option value="">All Types</option>
                <option value="1">Examinable</option>
                <option value="0">Non-Examinable</option>
            </select>
        </div>
        <!--Export-->
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button type="button" class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span class="path2"></span></i>
                    Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" id="export-csv">
                            <i class="ki-duotone ki-file-down fs-3 me-2 text-success"><span class="path1"></span><span class="path2"></span></i>
                            Export CSV
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" id="export-excel">
                            <i class="ki-duotone ki-file-sheet fs-3 me-2 text-success"><span class="path1"></span><span class="path2"></span></i>
                            Export Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" id="export-pdf">
                            <i class="ki-duotone ki-file-up fs-3 me-2 text-danger"><span class="path1"></span><span class="path2"></span></i>
                            Export PDF
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#" id="export-copy">
                            <i class="ki-duotone ki-copy fs-3 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            Copy to Clipboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Card header-->

    <div class="card-body pt-4">
        <div class="table-responsive">
        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="subject-table">
            <thead>
                <tr class="fw-bold text-muted fs-7 text-uppercase border-bottom border-gray-200">
                    <th class="min-w-200px">Subject Name</th>
                    <th class="min-w-140px">Level</th>
                    <th class="min-w-110px">Type</th>
                    <th class="min-w-90px text-end">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
    </div>
</div>
<!--end::Table card-->

</div>
</div>

<script>
(function () {
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash   = '<?= csrf_hash() ?>';

    // ── DataTable ────────────────────────────────────────────────────────────
    const table = document.getElementById('subject-table');
    let datatable;

    datatable = $(table).DataTable({
        processing:  true,
        serverSide:  true,
        searchDelay: 500,
        ajax: {
            url:  '<?= base_url('subject/listing') ?>',
            type: 'POST',
            data: function (d) {
                d[csrfName]     = csrfHash;
                d.level_id      = $('#filter-level').val();
                d.is_examinable = $('#filter-type').val();
                return d;
            },
            dataSrc: function (json) {
                if (json.csrf_hash) csrfHash = json.csrf_hash;
                return json.data;
            }
        },
        columns: [
            { data: 'subject_name',  name: 'subject_name' },
            { data: 'level_name',    name: 'level_name' },
            { data: 'is_examinable', name: 'is_examinable', orderable: false },
            { data: 'actions',       name: 'actions',       orderable: false, searchable: false },
        ],
        order:      [[0, 'asc']],
        pageLength: 15,
        searching:  true,
        dom: 't<"d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4"ip>',
        language: {
            processing:  '<span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading...',
            lengthMenu:  '_MENU_ per page',
            paginate: {
                next:     '<i class="ki-duotone ki-arrow-right fs-6"><span class="path1"></span><span class="path2"></span></i>',
                previous: '<i class="ki-duotone ki-arrow-left fs-6"><span class="path1"></span><span class="path2"></span></i>',
            },
            emptyTable:   'No subjects found.',
            zeroRecords:  'No matching subjects found.',
            info:         'Showing _START_ to _END_ of _TOTAL_ subjects',
            infoFiltered: '(filtered from _MAX_ total)',
            infoEmpty:    'No subjects found.',
        },
        drawCallback: function () {
            KTMenu.createInstances();
            initDeleteHandlers();
        }
    });

    // ── Flash messages ───────────────────────────────────────────────────────
    <?php if ($flashSuccess): ?>
    Swal.fire({ icon: 'success', title: 'Success', text: '<?= esc($flashSuccess, 'js') ?>', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    <?php endif; ?>
    <?php if ($flashError): ?>
    Swal.fire({ icon: 'error', title: 'Error', text: '<?= esc($flashError, 'js') ?>', toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true });
    <?php endif; ?>

    // ── Export helpers ───────────────────────────────────────────────────────
    function exportParams(format) {
        return new URLSearchParams({
            format,
            search:        document.getElementById('search-subject').value,
            level_id:      document.getElementById('filter-level').value,
            is_examinable: document.getElementById('filter-type').value,
        });
    }

    ['export-csv', 'export-excel', 'export-pdf'].forEach(function (id) {
        document.getElementById(id).addEventListener('click', function (e) {
            e.preventDefault();
            const format = id.replace('export-', '');
            window.location.href = '<?= base_url('subject/export') ?>?' + exportParams(format).toString();
        });
    });

    document.getElementById('export-copy').addEventListener('click', async function (e) {
        e.preventDefault();
        try {
            const res  = await fetch('<?= base_url('subject/export') ?>?' + exportParams('copy').toString());
            const text = await res.text();
            await navigator.clipboard.writeText(text);
            Swal.fire({ icon: 'success', title: 'Copied!', text: 'Subject data copied to clipboard.', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Copy Failed', text: 'Your browser may not support clipboard access. Try CSV or Excel instead.' });
        }
    });

    // ── Search / filter hooks ────────────────────────────────────────────────
    let searchTimer;
    document.getElementById('search-subject').addEventListener('input', function () {
        clearTimeout(searchTimer);
        const val = this.value;
        searchTimer = setTimeout(function () {
            datatable.search(val).draw();
        }, 400);
    });

    $('#filter-level, #filter-type').on('change', function () {
        datatable.ajax.reload();
    });

    // ── Delete ───────────────────────────────────────────────────────────────
    function initDeleteHandlers() {
        document.querySelectorAll('.btn-delete-subject').forEach(function (btn) {
            if (btn._deleteBound) return;
            btn._deleteBound = true;

            btn.addEventListener('click', function () {
                const id   = btn.dataset.id;
                const name = btn.dataset.name;

                Swal.fire({
                    icon: 'warning',
                    title: 'Delete Subject?',
                    html: 'Are you sure you want to delete <strong>' + name + '</strong>?<br><small class="text-muted">This will fail if the subject is assigned to any school.</small>',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    confirmButtonColor: '#F1416C',
                    cancelButtonText: 'Cancel',
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    const fd = new FormData();
                    fd.append(csrfName, csrfHash);

                    fetch('<?= base_url('subject/remove/') ?>' + id, { method: 'POST', body: fd })
                        .then(r => r.json())
                        .then(function (data) {
                            if (data.csrf_hash) csrfHash = data.csrf_hash;
                            if (data.success) {
                                datatable.ajax.reload(null, false);
                                Swal.fire({ icon: 'success', title: 'Deleted', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                            } else {
                                Swal.fire({ icon: 'warning', title: 'Cannot Delete', text: data.message });
                            }
                        })
                        .catch(function () {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                        });
                });
            });
        });
    }
})();
</script>
