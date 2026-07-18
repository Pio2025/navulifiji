<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Subject Categories</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('subject') ?>" class="text-muted text-hover-primary">Subjects</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Categories</li>
            </ul>
        </div>
        <?php if ($canManage): ?>
        <a href="<?= base_url('subject/category/add') ?>" class="btn btn-primary btn-sm">
            <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i>
            Add Category
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
                        <i class="ki-duotone ki-category fs-2x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= (int)$total ?></div>
                    <div class="text-muted fs-7">Total Categories</div>
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
                    <div class="fs-2 fw-bold text-gray-800"><?= (int)$active ?></div>
                    <div class="text-muted fs-7">Active</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center gap-4 py-5">
                <div class="symbol symbol-50px flex-shrink-0">
                    <span class="symbol-label bg-light-danger">
                        <i class="ki-duotone ki-cross-circle fs-2x text-danger"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= (int)$inactive ?></div>
                    <div class="text-muted fs-7">Inactive</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Stats-->

<!--begin::Table card-->
<div class="card">
    <div class="card-header border-0 flex-wrap py-5 gap-3" style="min-height:unset;">
        <div class="d-flex align-items-center position-relative" style="flex:1 1 200px;min-width:160px;">
            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
            <input type="text" id="search-category" class="form-control w-100 ps-12" placeholder="Search categories...">
        </div>
        <div class="d-flex align-items-center">
            <select id="filter-status" class="form-select" style="min-width:140px;">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
    <div class="card-body pt-4">
        <div class="table-responsive">
        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="category-table">
            <thead>
                <tr class="fw-bold text-muted fs-7 text-uppercase border-bottom border-gray-200">
                    <th class="min-w-250px">Category Name</th>
                    <th class="min-w-100px">Status</th>
                    <?php if ($canManage): ?>
                    <th class="min-w-100px text-end">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="category-tbody">
            <?php foreach ($categories as $cat): ?>
            <tr data-name="<?= strtolower(esc($cat['sub_cat_name'])) ?>"
                data-status="<?= (int)$cat['sub_cat_status'] ?>">
                <td>
                    <span class="fw-semibold text-gray-800 fs-7"><?= esc($cat['sub_cat_name']) ?></span>
                </td>
                <td>
                    <?php if ((int)$cat['sub_cat_status'] === 1): ?>
                    <span class="badge badge-light-success fs-8">Active</span>
                    <?php else: ?>
                    <span class="badge badge-light-danger fs-8">Inactive</span>
                    <?php endif; ?>
                </td>
                <?php if ($canManage): ?>
                <td class="text-end">
                    <a href="<?= base_url('subject/category/edit/' . (int)$cat['sub_cat_id']) ?>" class="btn btn-icon btn-sm btn-light-primary me-1">
                        <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-delete-category"
                        data-id="<?= (int)$cat['sub_cat_id'] ?>"
                        data-name="<?= esc($cat['sub_cat_name']) ?>">
                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div id="no-results" class="text-center py-10 d-none">
            <i class="ki-duotone ki-search-list fs-3x text-muted mb-3 d-block"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="text-muted fs-6">No categories match your filters.</div>
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

    // ── Flash messages ───────────────────────────────────────────────────────
    <?php if ($flashSuccess): ?>
    Swal.fire({ icon: 'success', title: 'Success', text: '<?= esc($flashSuccess, 'js') ?>', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
    <?php endif; ?>
    <?php if ($flashError): ?>
    Swal.fire({ icon: 'error', title: 'Error', text: '<?= esc($flashError, 'js') ?>', toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true });
    <?php endif; ?>

    // ── Client-side filter ───────────────────────────────────────────────────
    function filterTable() {
        const q      = document.getElementById('search-category').value.toLowerCase().trim();
        const status = document.getElementById('filter-status').value;
        const rows   = document.querySelectorAll('#category-tbody tr');
        let visible  = 0;

        rows.forEach(function (row) {
            const nameMatch   = !q      || row.dataset.name.includes(q);
            const statusMatch = status === '' || row.dataset.status === status;
            const show = nameMatch && statusMatch;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('no-results').classList.toggle('d-none', visible > 0);
    }

    document.getElementById('search-category').addEventListener('input', filterTable);
    document.getElementById('filter-status').addEventListener('change', filterTable);

    // ── Delete ───────────────────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-category');
        if (!btn) return;

        const id   = btn.dataset.id;
        const name = btn.dataset.name;

        Swal.fire({
            icon: 'warning',
            title: 'Delete Category?',
            html: 'Are you sure you want to delete <strong>' + name + '</strong>?<br><small class="text-muted">This will fail if any subjects are assigned to this category.</small>',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#F1416C',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            const fd = new FormData();
            fd.append(csrfName, csrfHash);

            fetch('<?= base_url('subject/category/remove/') ?>' + id, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(function (data) {
                    if (data.csrf_hash) csrfHash = data.csrf_hash;
                    if (data.success) {
                        btn.closest('tr').remove();
                        filterTable();
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
})();
</script>
