<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">My Child</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">My Child</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$flashSuccess = session('success');
$flashError   = session('error');
?>

<!--begin::Table card-->
<div class="card">
    <div class="card-header border-0 flex-wrap py-5 gap-3" style="min-height:unset;">
        <div class="d-flex flex-wrap align-items-center gap-3" style="flex:1 1 auto;min-width:0;">
            <!--Search-->
            <div class="d-flex align-items-center position-relative" style="flex:1 1 200px;min-width:160px;">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" id="search-my-child" class="form-control w-100 ps-12" placeholder="Search children...">
            </div>
        </div>
    </div>

    <div class="card-body pt-4">
        <div class="table-responsive">
        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="my-child-table">
            <thead>
                <tr class="fw-bold text-muted fs-7 text-uppercase border-bottom border-gray-200">
                    <th class="min-w-250px">Child</th>
                    <th class="min-w-150px">Relationship</th>
                    <th class="min-w-100px">Status</th>
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
    const table = document.getElementById('my-child-table');

    const datatable = $(table).DataTable({
        processing:  true,
        serverSide:  true,
        searchDelay: 500,
        ajax: {
            url:  '<?= base_url('user/child/my/listing') ?>',
            type: 'POST',
            data: function (d) {
                d[csrfName] = csrfHash;
                return d;
            },
            dataSrc: function (json) {
                if (json.csrf_hash) csrfHash = json.csrf_hash;
                return json.data;
            }
        },
        columns: [
            { data: 'child_name',   name: 'child_name' },
            { data: 'relationship', name: 'relationship' },
            { data: 'user_status',  name: 'user_status', orderable: false },
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
            emptyTable:   'No children linked to your account yet.',
            zeroRecords:  'No matching children found.',
            info:         'Showing _START_ to _END_ of _TOTAL_ children',
            infoFiltered: '(filtered from _MAX_ total)',
            infoEmpty:    'No children linked to your account yet.',
        },
    });

    // ── Flash messages ───────────────────────────────────────────────────────
    <?php if ($flashSuccess): ?>
    Swal.fire({ icon: 'success', title: 'Success', text: '<?= esc($flashSuccess, 'js') ?>', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    <?php endif; ?>
    <?php if ($flashError): ?>
    Swal.fire({ icon: 'error', title: 'Error', text: '<?= esc($flashError, 'js') ?>', toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true });
    <?php endif; ?>

    // ── Search ───────────────────────────────────────────────────────────────
    let searchTimer;
    document.getElementById('search-my-child').addEventListener('input', function () {
        clearTimeout(searchTimer);
        const val = this.value;
        searchTimer = setTimeout(function () {
            datatable.search(val).draw();
        }, 400);
    });
})();
</script>
