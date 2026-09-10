<?php
$users  = $users ?? [];
$search = $search ?? '';
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Doc Manager Lookup</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('doc-manager') ?>" class="text-muted text-hover-primary">Doc Manager</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Lookup</li>
            </ul>
        </div>
        <a href="<?= base_url('doc-manager') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2 me-1"><span class="path1"></span><span class="path2"></span></i>
            My Documents
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<div class="card mb-6">
    <div class="card-body py-4">
        <form method="GET" action="<?= base_url('doc-manager/lookup') ?>" class="d-flex gap-3">
            <input type="text" name="search" class="form-control" placeholder="Search by name…" value="<?= esc($search) ?>" />
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900 fs-5">Users (<?= count($users) ?>)</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <?php if (empty($users)): ?>
        <div class="text-center py-16">
            <i class="ki-duotone ki-people fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
            <div class="fs-6 fw-semibold text-gray-600">No users found.</div>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted fs-7 bg-light">
                        <th class="ps-4">Name</th>
                        <th>Role</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td class="ps-4">
                        <div class="symbol symbol-30px symbol-circle me-3 d-inline-block align-middle">
                            <?php if (!empty($u['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . esc($u['profile_photo'])) ?>" alt="" />
                            <?php else: ?>
                            <span class="symbol-label bg-light-primary text-primary fw-bold">
                                <?= strtoupper(substr($u['fname'], 0, 1) . substr($u['lname'], 0, 1)) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <span class="fw-semibold text-gray-900"><?= esc(trim($u['fname'] . ' ' . $u['lname'])) ?></span>
                    </td>
                    <td><?= esc($u['role_name']) ?></td>
                    <td class="text-end pe-4">
                        <a href="<?= base_url('doc-manager/lookup/' . (int) $u['user_id']) ?>" class="btn btn-sm btn-light-primary">View Documents</a>
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
