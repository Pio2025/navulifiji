<?php
$book    = $book    ?? [];
$history = $history ?? [];

$statusColor = [
    'Issued'   => 'warning',
    'Returned' => 'success',
    'Lost'     => 'danger',
];
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= esc($book['title']) ?></h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('library') ?>" class="text-muted text-hover-primary">Book Catalog</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <a href="<?= base_url('library') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to Catalog
        </a>
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
                        <div class="text-muted fs-8">Author</div>
                        <div class="fw-semibold fs-6"><?= esc($book['author'] ?? '—') ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">ISBN</div>
                        <div class="fw-semibold fs-6"><?= esc($book['isbn'] ?? '—') ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Publisher</div>
                        <div class="fw-semibold fs-6"><?= esc($book['publisher'] ?? '—') ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Edition</div>
                        <div class="fw-semibold fs-6"><?= esc($book['edition'] ?? '—') ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Category</div>
                        <div class="fw-semibold fs-6">
                            <?php if (!empty($book['category_name'])): ?>
                            <span class="badge badge-light-primary"><?= esc($book['category_name']) ?></span>
                            <?php else: ?>—<?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Shelf Location</div>
                        <div class="fw-semibold fs-6"><?= esc($book['shelf_location'] ?? '—') ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Total Copies</div>
                        <div class="fw-semibold fs-6"><?= (int) $book['total_copies'] ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-muted fs-8">Available Copies</div>
                        <div class="fw-semibold fs-6">
                            <span class="badge badge-light-<?= (int)$book['available_copies'] > 0 ? 'success' : 'danger' ?>">
                                <?= (int) $book['available_copies'] ?>
                            </span>
                        </div>
                    </div>
                    <?php if (!empty($book['description'])): ?>
                    <div class="col-lg-12">
                        <div class="text-muted fs-8">Description</div>
                        <div class="fs-6 text-gray-700"><?= esc($book['description']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <h3 class="fw-bold text-gray-900 fs-5">Issue History</h3>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($history)): ?>
                <div class="text-center py-10">
                    <span class="text-muted fs-7">This book has not been issued yet.</span>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 bg-light">
                                <th class="ps-4">Borrower</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th class="text-center">Fine</th>
                                <th class="text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($history as $h): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold"><?= esc(trim(($h['borrower_fname'] ?? '') . ' ' . ($h['borrower_lname'] ?? ''))) ?></span>
                                <div class="text-muted fs-8"><?= esc($h['borrower_role_cat'] ?? '') ?></div>
                            </td>
                            <td><?= esc($h['issue_date']) ?></td>
                            <td><?= esc($h['due_date']) ?></td>
                            <td><?= esc($h['return_date'] ?? '—') ?></td>
                            <td class="text-center">
                                <?= (float)($h['fine_amount'] ?? 0) > 0 ? '$' . number_format((float)$h['fine_amount'], 2) : '—' ?>
                            </td>
                            <td class="text-center pe-4">
                                <span class="badge badge-light-<?= $statusColor[$h['status']] ?? 'secondary' ?>"><?= esc($h['status']) ?></span>
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
