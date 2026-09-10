<?php
$hostels     = $hostels     ?? [];
$canAdd      = $canAdd      ?? false;
$canEdit     = $canEdit     ?? false;
$canDelete   = $canDelete   ?? false;
$canAllocate = $canAllocate ?? false;
?>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Hostels</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Hostel</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canAllocate): ?>
            <a href="<?= base_url('hostel/allocation') ?>" class="btn btn-light-primary">
                <i class="ki-duotone ki-arrow-right-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                Room Allocation
            </a>
            <?php endif; ?>
            <?php if ($canAdd): ?>
            <a href="<?= base_url('hostel/add') ?>" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Add Hostel
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <div class="card">
            <div class="card-body py-4">
                <?php if (empty($hostels)): ?>
                <div class="text-center py-16">
                    <i class="ki-duotone ki-home-3 fs-4x text-gray-200 mb-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="fs-6 fw-semibold text-gray-600 mb-2">No hostels found</div>
                    <div class="fs-7 text-muted mb-6">Add your first hostel to get started.</div>
                    <?php if ($canAdd): ?>
                    <a href="<?= base_url('hostel/add') ?>" class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-plus fs-4 me-1"></i>Add Hostel
                    </a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 bg-light">
                                <th class="ps-4 min-w-40px rounded-start">#</th>
                                <th class="min-w-200px">Hostel</th>
                                <th class="min-w-100px">Type</th>
                                <th class="min-w-100px text-center">Rooms</th>
                                <th class="min-w-120px text-center">Occupancy</th>
                                <th class="min-w-120px text-end rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($hostels as $i => $h):
                            $capacity = (int) $h['total_capacity'];
                            $occupied = (int) $h['occupied_count'];
                            $pct      = $capacity > 0 ? round(($occupied / $capacity) * 100) : 0;
                        ?>
                        <tr>
                            <td class="ps-4 text-muted fs-7"><?= $i + 1 ?></td>
                            <td>
                                <a href="<?= base_url('hostel/detail/' . (int) $h['hostel_id']) ?>" class="fw-bold text-gray-800 text-hover-primary fs-6">
                                    <?= esc($h['hostel_name']) ?>
                                </a>
                                <?php if (!empty($h['address'])): ?>
                                <div class="text-muted fs-8"><?= esc($h['address']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-light-info"><?= esc($h['hostel_type']) ?></span></td>
                            <td class="text-center"><?= (int) $h['room_count'] ?></td>
                            <td class="text-center">
                                <span class="badge badge-light-<?= $capacity > 0 && $occupied >= $capacity ? 'danger' : 'success' ?>">
                                    <?= $occupied ?> / <?= $capacity ?>
                                </span>
                                <?php if ($capacity > 0): ?><div class="text-muted fs-9"><?= $pct ?>% full</div><?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?= base_url('hostel/detail/' . (int) $h['hostel_id']) ?>"
                                       class="btn btn-sm btn-icon btn-light-info" title="View">
                                        <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </a>
                                    <?php if ($canEdit): ?>
                                    <a href="<?= base_url('hostel/edit/' . (int) $h['hostel_id']) ?>"
                                       class="btn btn-sm btn-icon btn-light-primary" title="Edit">
                                        <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($canDelete): ?>
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-light-danger"
                                        title="Delete"
                                        data-hostel-id="<?= (int) $h['hostel_id'] ?>"
                                        data-hostel-name="<?= esc($h['hostel_name'], 'attr') ?>"
                                        onclick="confirmDelete(this)">
                                        <i class="ki-duotone ki-trash fs-5">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
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
        </div>

    </div>
</div>

<!--begin::Delete confirm modal-->
<div class="modal fade" id="deleteHostelModal" tabindex="-1" aria-hidden="true">
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
                        <h5 class="fw-bold text-gray-800 mb-0">Delete Hostel</h5>
                        <span class="text-muted fs-7">This action cannot be undone</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4 pb-2">
                <p class="text-gray-700 fs-6">
                    Are you sure you want to delete <strong id="deleteHostelName"></strong>?
                </p>
                <div class="notice d-flex bg-light-warning rounded p-3 mt-3">
                    <i class="ki-duotone ki-information-5 fs-4 text-warning me-2 flex-shrink-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <span class="fs-7 text-gray-700">Deletion is blocked if the hostel still has any rooms.</span>
                </div>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteHostelForm" method="POST" class="d-inline">
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
    var id   = btn.getAttribute('data-hostel-id');
    var name = btn.getAttribute('data-hostel-name');
    document.getElementById('deleteHostelName').textContent = name;
    document.getElementById('deleteHostelForm').action = '<?= base_url('hostel/remove/') ?>' + id;
    var modal = new bootstrap.Modal(document.getElementById('deleteHostelModal'));
    modal.show();
}
</script>
