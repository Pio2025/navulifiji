<?php
$items = $items ?? [];
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Shared With Me</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('doc-manager') ?>" class="text-muted text-hover-primary">Doc Manager</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Shared With Me</li>
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

<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900 fs-5">Documents (<?= count($items) ?>)</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <?php if (empty($items)): ?>
        <div class="text-center py-16">
            <i class="ki-duotone ki-share fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="fs-6 fw-semibold text-gray-600">Nothing has been shared with you yet.</div>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted fs-7 bg-light">
                        <th class="ps-4">File</th>
                        <th>Shared By</th>
                        <th>Source</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $doc): ?>
                <tr>
                    <td class="ps-4">
                        <i class="ki-duotone <?= esc($doc['icon']) ?> fs-3 text-<?= esc($doc['color']) ?> me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        <span class="fw-semibold text-gray-900"><?= esc($doc['original_name']) ?></span>
                    </td>
                    <td><?= esc($doc['shared_by']) ?></td>
                    <td><span class="badge badge-light-secondary"><?= esc($doc['source_label']) ?></span></td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="<?= base_url('doc-manager/view/' . $doc['source_type'] . '/' . $doc['source_file_id']) ?>" target="_blank" class="btn btn-sm btn-icon btn-light-info" title="View">
                                <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </a>
                            <?php if ($doc['can_download']): ?>
                            <a href="<?= base_url('doc-manager/download/' . $doc['source_type'] . '/' . $doc['source_file_id']) ?>" class="btn btn-sm btn-icon btn-light-success" title="Download">
                                <i class="ki-duotone ki-down fs-5"><span class="path1"></span><span class="path2"></span></i>
                            </a>
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
