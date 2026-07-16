<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                My Notifications
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Notifications</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="card shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <!-- Search -->
                    <div class="input-group input-group-sm" style="width:210px;">
                        <span class="input-group-text bg-body border-end-0">
                            <i class="ki-duotone ki-magnifier fs-4 text-muted"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <input type="text" id="log-search" class="form-control form-control-sm  border-start-0 ps-1" placeholder="Search logs…">
                    </div>
                    <!-- Type filter -->
                    <select id="log-type-filter" class="form-select form-select-sm " style="width:130px;">
                        <option value="" <?= ($defaultType ?? '') === '' ? 'selected' : '' ?>>All Types</option>
                        <option value="Activity" <?= ($defaultType ?? '') === 'Activity' ? 'selected' : '' ?>>Activity</option>
                        <option value="Alert" <?= ($defaultType ?? '') === 'Alert' ? 'selected' : '' ?>>Alert</option>
                    </select>
                    <!-- Date from -->
                    <input type="date" id="log-date-from" class="form-control form-control-sm " style="width:145px;" title="From">
                    <!-- Date to -->
                    <input type="date" id="log-date-to"   class="form-control form-control-sm " style="width:145px;" title="To">
                    <button class="btn btn-sm btn-light-primary" id="log-filter-btn">
                        <i class="ki-duotone ki-filter fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Filter
                    </button>
                    <button class="btn btn-sm btn-light" id="log-reset-btn">Reset</button>
                </div>
            </div>
        </div>

        <div class="card-body pt-4">
            <!-- Log table -->
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted fs-8 text-uppercase">
                            <th class="w-45px"></th>
                            <th class="min-w-200px">Activity</th>
                            <th class="min-w-90px">Type</th>
                            <th class="min-w-120px">IP / Device</th>
                            <th class="min-w-120px text-end pe-0">Date / Time</th>
                        </tr>
                    </thead>
                    <tbody id="log-tbody">
                        <tr><td colspan="5" class="text-center py-10">
                            <span class="spinner-border spinner-border-sm text-primary"></span>
                        </td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3" id="log-pagination-wrap">
                <div class="text-muted fs-8" id="log-showing-text"></div>
                <div id="log-pagination" class="d-flex gap-1"></div>
            </div>
        </div>
    </div>

</div><!-- /#kt_app_content_container -->
</div><!-- /#kt_app_content -->

<script>
(function () {
    const MY_ID       = <?= (int) $myId ?>;
    const LOGS_URL    = '<?= base_url('user/getUserLogs/') ?>' + MY_ID;
    let currentPage   = 1;
    let currentPerPage = 20;

    function fetchLogs(page) {
        currentPage = page || 1;
        const search   = document.getElementById('log-search').value.trim();
        const logType  = document.getElementById('log-type-filter').value;
        const dateFrom = document.getElementById('log-date-from').value;
        const dateTo   = document.getElementById('log-date-to').value;

        const params = new URLSearchParams({
            page:     currentPage,
            perPage:  currentPerPage,
            search,
            logType,
            dateFrom,
            dateTo,
        });

        document.getElementById('log-tbody').innerHTML =
            '<tr><td colspan="5" class="text-center py-8"><span class="spinner-border spinner-border-sm text-primary"></span></td></tr>';

        fetch(LOGS_URL + '?' + params)
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                document.getElementById('log-tbody').innerHTML = data.html;
                renderPagination(data.pagination);
            })
            .catch(() => {
                document.getElementById('log-tbody').innerHTML =
                    '<tr><td colspan="5" class="text-center py-8 text-danger">Failed to load logs.</td></tr>';
            });
    }

    function renderPagination(p) {
        const wrap = document.getElementById('log-pagination-wrap');
        const pDiv = document.getElementById('log-pagination');
        const txt  = document.getElementById('log-showing-text');

        if (!p || p.total === 0) {
            wrap.style.display = 'none';
            return;
        }
        wrap.style.display = '';
        txt.textContent = `Showing ${p.from}–${p.to} of ${p.total} entries`;

        let html = '';
        if (p.currentPage > 1) {
            html += `<button class="btn btn-sm btn-light" data-page="${p.currentPage - 1}">‹</button>`;
        }
        const start = Math.max(1, p.currentPage - 2);
        const end   = Math.min(p.totalPages, p.currentPage + 2);
        for (let i = start; i <= end; i++) {
            html += `<button class="btn btn-sm ${i === p.currentPage ? 'btn-primary' : 'btn-light'}" data-page="${i}">${i}</button>`;
        }
        if (p.currentPage < p.totalPages) {
            html += `<button class="btn btn-sm btn-light" data-page="${p.currentPage + 1}">›</button>`;
        }
        pDiv.innerHTML = html;

        pDiv.querySelectorAll('button[data-page]').forEach(btn => {
            btn.addEventListener('click', () => fetchLogs(parseInt(btn.dataset.page)));
        });
    }

    // Wire controls
    document.getElementById('log-filter-btn').addEventListener('click', () => fetchLogs(1));
    document.getElementById('log-reset-btn').addEventListener('click', () => {
        document.getElementById('log-search').value       = '';
        document.getElementById('log-type-filter').value  = '';
        document.getElementById('log-date-from').value    = '';
        document.getElementById('log-date-to').value      = '';
        fetchLogs(1);
    });
    document.getElementById('log-search').addEventListener('keydown', e => {
        if (e.key === 'Enter') fetchLogs(1);
    });

    // Initial load
    fetchLogs(1);
})();
</script>
