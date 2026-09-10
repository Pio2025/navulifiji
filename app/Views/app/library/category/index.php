<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Book Categories</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('library') ?>" class="text-muted text-hover-primary">Library</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Book Categories</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canAdd ?? false): ?>
            <a href="<?= base_url('library/category/add') ?>" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Add Category
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <!--begin::Card-->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" id="cat_search" class="form-control form-control-solid w-250px ps-13" placeholder="Search category..." />
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body py-4">
                <?php if (empty($categories)): ?>
                <div class="text-center py-16">
                    <i class="ki-duotone ki-book fs-4x text-gray-200 mb-4">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                    </i>
                    <div class="fs-6 fw-semibold text-gray-600 mb-2">No book categories found</div>
                    <div class="fs-7 text-muted mb-6">Add your first book category to get started.</div>
                    <?php if ($canAdd ?? false): ?>
                    <a href="<?= base_url('library/category/add') ?>" class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-plus fs-4 me-1"></i>Add Category
                    </a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="cat_table">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 bg-light">
                                <th class="ps-4 min-w-40px rounded-start">#</th>
                                <th class="min-w-200px">Category Name</th>
                                <th class="min-w-250px">Description</th>
                                <th class="min-w-100px text-end rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $canEdit   = $canEdit   ?? false;
                        $canDelete = $canDelete ?? false;
                        foreach ($categories as $i => $cat):
                        ?>
                        <tr>
                            <td class="ps-4 text-muted fs-7"><?= $i + 1 ?></td>
                            <td>
                                <span class="fw-bold text-gray-800 fs-6"><?= esc($cat['category_name']) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($cat['description'])): ?>
                                    <span class="text-gray-700 fs-7"><?= esc($cat['description']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <?php if ($canEdit): ?>
                                    <a href="<?= base_url('library/category/edit/' . (int)$cat['category_id']) ?>"
                                       class="btn btn-sm btn-icon btn-light-primary"
                                       title="Edit">
                                        <i class="ki-duotone ki-pencil fs-5">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($canDelete): ?>
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-light-danger"
                                        title="Delete"
                                        data-cat-id="<?= (int)$cat['category_id'] ?>"
                                        data-cat-name="<?= esc($cat['category_name'], 'attr') ?>"
                                        onclick="confirmDelete(this)">
                                        <i class="ki-duotone ki-trash fs-5">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

    </div>
</div>
<!--end::Content-->

<!--begin::Delete confirm modal-->
<div class="modal fade" id="deleteCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-light-danger rounded-2" style="width:44px;height:44px;">
                        <i class="ki-duotone ki-trash fs-2 text-danger">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-gray-800 mb-0">Delete Book Category</h5>
                        <span class="text-muted fs-7">This action cannot be undone</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4 pb-2">
                <p class="text-gray-700 fs-6">
                    Are you sure you want to delete <strong id="deleteCatName"></strong>?
                </p>
                <div class="notice d-flex bg-light-warning rounded p-3 mt-3">
                    <i class="ki-duotone ki-information-5 fs-4 text-warning me-2 flex-shrink-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <span class="fs-7 text-gray-700">Deletion is blocked if any book is currently assigned to this category.</span>
                </div>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCatForm" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Delete confirm modal-->

<script>
function confirmDelete(btn) {
    var id   = btn.getAttribute('data-cat-id');
    var name = btn.getAttribute('data-cat-name');
    document.getElementById('deleteCatName').textContent = name;
    document.getElementById('deleteCatForm').action = '<?= base_url('library/category/remove/') ?>' + id;
    var modal = new bootstrap.Modal(document.getElementById('deleteCatModal'));
    modal.show();
}

(function () {
    var searchInput = document.getElementById('cat_search');
    var table = document.getElementById('cat_table');
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function () {
            var q = this.value.toLowerCase();
            table.querySelectorAll('tbody tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
})();
</script>
