<?php
$hostel         = $hostel ?? [];
$rooms          = $rooms  ?? [];
$canManageRooms = $canManageRooms ?? false;
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= esc($hostel['hostel_name']) ?></h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('hostel') ?>" class="text-muted text-hover-primary">Hostels</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canManageRooms): ?>
            <a href="<?= base_url('hostel/room/add/' . (int) $hostel['hostel_id']) ?>" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Add Room
            </a>
            <?php endif; ?>
            <a href="<?= base_url('hostel') ?>" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                Back to Hostels
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <div class="card mb-6">
            <div class="card-body">
                <div class="row g-5">
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Type</div>
                        <div class="fw-semibold fs-6"><span class="badge badge-light-info"><?= esc($hostel['hostel_type']) ?></span></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-muted fs-8">Address</div>
                        <div class="fw-semibold fs-6"><?= esc($hostel['address'] ?? '—') ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Rooms</div>
                        <div class="fw-semibold fs-6"><?= count($rooms) ?></div>
                    </div>
                    <?php if (!empty($hostel['description'])): ?>
                    <div class="col-lg-12">
                        <div class="text-muted fs-8">Description</div>
                        <div class="fs-6 text-gray-700"><?= esc($hostel['description']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <h3 class="fw-bold text-gray-900 fs-5">Rooms</h3>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($rooms)): ?>
                <div class="text-center py-10">
                    <span class="text-muted fs-7">No rooms have been added to this hostel yet.</span>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 bg-light">
                                <th class="ps-4">Room</th>
                                <th class="text-center">Capacity</th>
                                <th class="text-center">Occupied</th>
                                <th>Description</th>
                                <?php if ($canManageRooms): ?>
                                <th class="text-end pe-4">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rooms as $r):
                            $occupied = (int) $r['occupied_count'];
                            $capacity = (int) $r['capacity'];
                        ?>
                        <tr>
                            <td class="ps-4 fw-semibold"><?= esc($r['room_number']) ?></td>
                            <td class="text-center"><?= $capacity ?></td>
                            <td class="text-center">
                                <span class="badge badge-light-<?= $occupied >= $capacity ? 'danger' : 'success' ?>"><?= $occupied ?></span>
                            </td>
                            <td><span class="text-gray-700 fs-7"><?= esc($r['description'] ?? '—') ?></span></td>
                            <?php if ($canManageRooms): ?>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?= base_url('hostel/room/edit/' . (int) $r['room_id']) ?>"
                                       class="btn btn-sm btn-icon btn-light-primary" title="Edit">
                                        <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-light-danger"
                                        title="Delete"
                                        data-room-id="<?= (int) $r['room_id'] ?>"
                                        data-room-number="<?= esc($r['room_number'], 'attr') ?>"
                                        onclick="confirmDeleteRoom(this)">
                                        <i class="ki-duotone ki-trash fs-5">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                        </i>
                                    </button>
                                </div>
                            </td>
                            <?php endif; ?>
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

<!--begin::Delete room confirm modal-->
<div class="modal fade" id="deleteRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Delete Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4 pb-2">
                <p class="text-gray-700 fs-6">Are you sure you want to delete room <strong id="deleteRoomNumber"></strong>?</p>
                <div class="notice d-flex bg-light-warning rounded p-3 mt-3">
                    <i class="ki-duotone ki-information-5 fs-4 text-warning me-2 flex-shrink-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <span class="fs-7 text-gray-700">Deletion is blocked if the room has any allocation history on file.</span>
                </div>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteRoomForm" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Delete room confirm modal-->

<script>
function confirmDeleteRoom(btn) {
    var id     = btn.getAttribute('data-room-id');
    var number = btn.getAttribute('data-room-number');
    document.getElementById('deleteRoomNumber').textContent = number;
    document.getElementById('deleteRoomForm').action = '<?= base_url('hostel/room/remove/') ?>' + id;
    var modal = new bootstrap.Modal(document.getElementById('deleteRoomModal'));
    modal.show();
}
</script>
