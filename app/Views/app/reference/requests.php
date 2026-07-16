<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Reference Requests
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
                <li class="breadcrumb-item text-muted">Reference Requests</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <input type="text" id="refReqSearch" class="form-control form-control-solid w-250px ps-13" placeholder="Search requests...">
            </div>
        </div>
        <div class="card-toolbar gap-2">
            <select id="refReqStatusFilter" class="form-select form-select-solid w-150px">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="refReqTable">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-200px">Student</th>
                    <th class="min-w-150px">Reference Type</th>
                    <th class="min-w-100px">Status</th>
                    <th class="min-w-200px">Student Note</th>
                    <th class="min-w-120px">Requested</th>
                    <th class="min-w-100px text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-10">No reference requests found.</td>
                </tr>
                <?php else: foreach ($requests as $r): ?>
                <tr data-status="<?= esc($r['request_status']) ?>">
                    <td>
                        <div class="d-flex align-items-center">
                            <?php
                            $photo = !empty($r['profile_photo'])
                                ? base_url('uploads/profile_photo/' . $r['profile_photo'])
                                : base_url('assets/media/avatars/blank.png');
                            ?>
                            <div class="symbol symbol-45px me-5">
                                <img src="<?= esc($photo) ?>" alt="photo" style="object-fit:cover;">
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold fs-6">
                                    <?= esc(trim($r['fname'] . ' ' . ($r['oname'] ?? '') . ' ' . $r['lname'])) ?>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td><?= esc($r['ref_type_name']) ?></td>
                    <td>
                        <?php
                        $badgeClass = match($r['request_status']) {
                            'Pending'     => 'badge-light-warning',
                            'In Progress' => 'badge-light-primary',
                            'Completed'   => 'badge-light-success',
                            'Rejected'    => 'badge-light-danger',
                            default       => 'badge-light-secondary',
                        };
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= esc($r['request_status']) ?></span>
                    </td>
                    <td>
                        <?= $r['request_note'] ? esc($r['request_note']) : '<span class="text-muted">—</span>' ?>
                    </td>
                    <td><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                    <td class="text-end pe-3">
                        <button class="btn btn-light btn-sm btn-update-request"
                            data-id="<?= $r['request_id'] ?>"
                            data-status="<?= esc($r['request_status']) ?>"
                            data-type="<?= esc($r['ref_type_name']) ?>"
                            data-student="<?= esc(trim($r['fname'] . ' ' . $r['lname'])) ?>"
                            data-review-note="<?= esc($r['review_note'] ?? '') ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#kt_modal_update_request">
                            Update
                        </button>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>
<!--end::Content-->

<!--begin::Update Request Modal-->
<div class="modal fade" id="kt_modal_update_request" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Update Reference Request</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-10 py-8">
                <p class="mb-1">
                    <strong id="uReqStudent"></strong> — <span id="uReqType" class="text-muted"></span>
                </p>
                <div class="mb-5 mt-5">
                    <label class="form-label required">Status</label>
                    <select class="form-select form-select-solid" id="uReqStatus">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Note to Student</label>
                    <textarea class="form-control form-control-solid" id="uReqNote" rows="3" placeholder="Optional note..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveRequest">Save</button>
            </div>
        </div>
    </div>
</div>
<!--end::Update Request Modal-->

<script>
(function () {
    let currentRequestId = null;

    // Populate modal on open
    document.addEventListener('show.bs.modal', function (e) {
        if (e.target.id !== 'kt_modal_update_request') return;
        const btn = e.relatedTarget;
        if (!btn) return;
        currentRequestId = btn.dataset.id;
        document.getElementById('uReqStudent').textContent  = btn.dataset.student;
        document.getElementById('uReqType').textContent     = btn.dataset.type;
        document.getElementById('uReqStatus').value         = btn.dataset.status;
        document.getElementById('uReqNote').value           = btn.dataset.reviewNote || '';
    });

    // Save
    document.getElementById('btnSaveRequest')?.addEventListener('click', function () {
        if (!currentRequestId) return;
        const btn    = this;
        btn.disabled = true;
        btn.textContent = 'Saving...';

        fetch('<?= base_url('reference/request/update/') ?>' + currentRequestId, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
            body: new URLSearchParams({
                request_status: document.getElementById('uReqStatus').value,
                review_note:    document.getElementById('uReqNote').value,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('kt_modal_update_request'))?.hide();
                location.reload();
            } else {
                alert(data.message || 'Error updating request.');
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Save';
        });
    });

    // Search filter
    const searchInput = document.getElementById('refReqSearch');
    const statusFilter = document.getElementById('refReqStatusFilter');
    function filterTable() {
        const query  = searchInput.value.toLowerCase();
        const status = statusFilter.value;
        document.querySelectorAll('#refReqTable tbody tr').forEach(row => {
            const text   = row.textContent.toLowerCase();
            const rowSt  = row.dataset.status || '';
            const matchQ = !query  || text.includes(query);
            const matchS = !status || rowSt === status;
            row.style.display = (matchQ && matchS) ? '' : 'none';
        });
    }
    searchInput?.addEventListener('input', filterTable);
    statusFilter?.addEventListener('change', filterTable);
})();
</script>
