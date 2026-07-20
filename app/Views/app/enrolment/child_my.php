<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Child Enrolment
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Child Enrolment</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if (empty($children)): ?>
    <div class="card">
        <div class="card-body text-center py-20">
            <i class="ki-duotone ki-information-5 fs-5x text-warning mb-5">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <h3 class="text-gray-700 fw-bold mb-3">No Children Linked</h3>
            <p class="text-muted fs-6 mb-0">No children are linked to your account yet. Contact your school administrator to link your child.</p>
        </div>
    </div>
    <?php else: ?>

    <!--begin::Table card-->
    <div class="card">
        <div class="card-header border-0 flex-wrap py-5 gap-3" style="min-height:unset;">
            <div class="d-flex flex-wrap align-items-center gap-3" style="flex:1 1 auto;min-width:0;">
                <div class="d-flex align-items-center position-relative" style="flex:1 1 200px;min-width:160px;">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                    <input type="text" id="search-child-enrolment" class="form-control w-100 ps-12" placeholder="Search child enrolments...">
                </div>
            </div>
        </div>

        <div class="card-body pt-4">
            <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="child-enrolment-table">
                <thead>
                    <tr class="fw-bold text-muted fs-7 text-uppercase border-bottom border-gray-200">
                        <th class="min-w-150px">Child</th>
                        <th class="min-w-200px">School</th>
                        <th class="min-w-150px">Stream / Level</th>
                        <th class="min-w-100px">Term</th>
                        <th class="min-w-80px">Year</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-80px text-end">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
        </div>
    </div>
    <!--end::Table card-->

    <?php endif; ?>

</div>
</div>

<?= $this->include('app/enrolment/_my_detail_modal_shell') ?>

<?php if (!empty($children)): ?>
<script>
(function () {
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash    = '<?= csrf_hash() ?>';

    const table = document.getElementById('child-enrolment-table');

    const datatable = $(table).DataTable({
        processing:  true,
        serverSide:  true,
        searchDelay: 500,
        ajax: {
            url:  '<?= base_url('enrolment/child/my/listing') ?>',
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
            { data: 'sch_name',     name: 'sch_name' },
            { data: 'stream_name',  name: 'stream_name' },
            { data: 'enrol_term',   name: 'enrol_term' },
            { data: 'enrol_year',   name: 'enrol_year' },
            { data: 'enrol_status', name: 'enrol_status', orderable: false },
            { data: 'actions',      name: 'actions', orderable: false, searchable: false },
        ],
        order:      [[4, 'desc']],
        pageLength: 10,
        dom: 't<"d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4"ip>',
        language: {
            processing:  '<span class="spinner-border spinner-border-sm align-middle me-2"></span>Loading...',
            lengthMenu:  '_MENU_ per page',
            paginate: {
                next:     '<i class="ki-duotone ki-arrow-right fs-6"><span class="path1"></span><span class="path2"></span></i>',
                previous: '<i class="ki-duotone ki-arrow-left fs-6"><span class="path1"></span><span class="path2"></span></i>',
            },
            emptyTable:   'No enrolment records found for your children.',
            zeroRecords:  'No matching enrolment records found.',
            info:         'Showing _START_ to _END_ of _TOTAL_ enrolments',
            infoFiltered: '(filtered from _MAX_ total)',
            infoEmpty:    'No enrolment records found.',
        },
        drawCallback: function () {
            bindEnrolmentViewButtons();
        }
    });

    let searchTimer;
    document.getElementById('search-child-enrolment').addEventListener('input', function () {
        clearTimeout(searchTimer);
        const val = this.value;
        searchTimer = setTimeout(function () {
            datatable.search(val).draw();
        }, 400);
    });

    function bindEnrolmentViewButtons() {
        document.querySelectorAll('.btn-view-enrolment').forEach(function (btn) {
            if (btn._viewBound) return;
            btn._viewBound = true;
            btn.addEventListener('click', function () {
                loadEnrolmentDetail(btn.dataset.id, '<?= base_url('enrolment/my/detail/') ?>');
            });
        });
    }
})();
</script>
<?php endif; ?>
