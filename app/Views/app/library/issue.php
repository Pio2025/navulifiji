<?php
$issued    = $issued    ?? [];
$books     = $books     ?? [];
$borrowers = $borrowers ?? [];
$canReturn = $canReturn ?? false;

$today = date('Y-m-d');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Issue / Return</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('library') ?>" class="text-muted text-hover-primary">Library</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Issue / Return</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <!--begin::Issue form-->
        <div class="card mb-6">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold text-gray-900 fs-5">Issue a Book</h3>
                </div>
            </div>
            <div class="card-body pt-4">
                <form id="issue_form">
                    <?= csrf_field() ?>
                    <div class="row g-5 align-items-end">
                        <div class="col-lg-5">
                            <label class="form-label required fw-semibold">Book</label>
                            <select id="book_select" name="book_id" class="form-select">
                                <option value="">— Select book —</option>
                                <?php foreach ($books as $b): ?>
                                <option value="<?= (int) $b['book_id'] ?>">
                                    <?= esc($b['title']) ?><?= !empty($b['author']) ? ' — ' . esc($b['author']) : '' ?> (<?= (int) $b['available_copies'] ?> available)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label class="form-label required fw-semibold">Borrower</label>
                            <select id="borrower_select" name="borrower_admission_id" class="form-select">
                                <option value="">— Select borrower —</option>
                                <?php foreach ($borrowers as $p): ?>
                                <option value="<?= (int) $p['admission_id'] ?>">
                                    <?= esc(trim($p['fname'] . ' ' . $p['lname'])) ?> (<?= esc($p['role_cat_name'] ?? $p['role_name'] ?? '') ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" id="btn_issue" class="btn btn-primary w-100">
                                <i class="ki-duotone ki-arrow-right-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                                Issue
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Issue form-->

        <!--begin::Currently issued-->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold text-gray-900 fs-5">Currently Issued (<?= count($issued) ?>)</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($issued)): ?>
                <div class="text-center py-16">
                    <i class="ki-duotone ki-book-open fs-4x text-gray-200 mb-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="fs-6 fw-semibold text-gray-600">No books are currently issued.</div>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 bg-light">
                                <th class="ps-4">Book</th>
                                <th>Borrower</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th class="text-center">Est. Fine</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($issued as $row):
                            $isOverdue = $row['due_date'] < $today;
                            $estFine   = \App\Models\LibraryBookIssueModel::calculateFine($row['due_date'], $today);
                        ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold"><?= esc($row['title']) ?></span>
                                <?php if (!empty($row['isbn'])): ?><div class="text-muted fs-8">ISBN: <?= esc($row['isbn']) ?></div><?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-semibold"><?= esc(trim(($row['borrower_fname'] ?? '') . ' ' . ($row['borrower_lname'] ?? ''))) ?></span>
                                <div class="text-muted fs-8"><?= esc($row['borrower_role_cat'] ?? '') ?></div>
                            </td>
                            <td><?= esc($row['issue_date']) ?></td>
                            <td>
                                <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>"><?= esc($row['due_date']) ?></span>
                                <?php if ($isOverdue): ?><div class="badge badge-light-danger fs-9">Overdue</div><?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?= $estFine > 0 ? '$' . number_format($estFine, 2) : '—' ?>
                            </td>
                            <td class="text-end pe-4">
                                <?php if ($canReturn): ?>
                                <div class="d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-light-success"
                                        onclick="openReturnModal(<?= (int)$row['issue_id'] ?>, '<?= esc($row['title'], 'js') ?>', <?= $estFine ?>)">
                                        Return
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light-danger"
                                        onclick="openLostModal(<?= (int)$row['issue_id'] ?>, '<?= esc($row['title'], 'js') ?>')">
                                        Lost
                                    </button>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Currently issued-->

    </div>
</div>

<!--begin::Return modal-->
<div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Return Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="returnForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body pt-4 pb-2">
                    <p class="text-gray-700 fs-6">Returning <strong id="returnBookTitle"></strong></p>
                    <div id="returnFineNotice" class="notice d-flex bg-light-warning rounded p-3 mb-3" style="display:none;">
                        <i class="ki-duotone ki-information-5 fs-4 text-warning me-2 flex-shrink-0">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                        <span class="fs-7 text-gray-700">Overdue fine: $<span id="returnFineAmount">0.00</span></span>
                    </div>
                    <div class="form-check form-check-custom form-check-solid" id="returnFinePaidWrap" style="display:none;">
                        <input class="form-check-input" type="checkbox" name="fine_paid" id="returnFinePaid" />
                        <label class="form-check-label fw-semibold" for="returnFinePaid">Fine collected / paid</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Return</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Return modal-->

<!--begin::Lost modal-->
<div class="modal fade" id="lostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Mark Book as Lost</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="lostForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body pt-4 pb-2">
                    <p class="text-gray-700 fs-6">Marking <strong id="lostBookTitle"></strong> as lost.</p>
                    <label class="form-label fw-semibold">Remarks</label>
                    <input type="text" name="remarks" class="form-control" placeholder="Optional remarks" />
                </div>
                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Lost</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Lost modal-->

<script>
"use strict";

$('#book_select').select2({ placeholder: '— Select book —', width: '100%' });
$('#borrower_select').select2({ placeholder: '— Select borrower —', width: '100%' });

document.getElementById('btn_issue').addEventListener('click', function () {
    var btn = this;
    var bookId     = document.getElementById('book_select').value;
    var borrowerId = document.getElementById('borrower_select').value;

    if (!bookId || !borrowerId) {
        Swal.fire({ title: 'Missing information', text: 'Please select both a book and a borrower.', icon: 'warning' });
        return;
    }

    var formData = new FormData(document.getElementById('issue_form'));

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url: '<?= base_url('library/issue/store') ?>', type: 'POST', data: formData, processData: false, contentType: false,
        success: function (response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Issued!', text: response.message, icon: 'success', timer: 1800, showConfirmButton: false })
                    .then(function () { window.location.href = response.redirect; });
            } else {
                Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
            }
        },
        error: function () {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An unexpected error occurred.', icon: 'error' });
        }
    });
});

function openReturnModal(issueId, title, estFine) {
    document.getElementById('returnBookTitle').textContent = title;
    document.getElementById('returnForm').action = '<?= base_url('library/issue/return/') ?>' + issueId;

    var notice = document.getElementById('returnFineNotice');
    var payWrap = document.getElementById('returnFinePaidWrap');
    if (estFine > 0) {
        document.getElementById('returnFineAmount').textContent = parseFloat(estFine).toFixed(2);
        notice.style.display = '';
        payWrap.style.display = '';
    } else {
        notice.style.display = 'none';
        payWrap.style.display = 'none';
    }

    var modal = new bootstrap.Modal(document.getElementById('returnModal'));
    modal.show();
}

function openLostModal(issueId, title) {
    document.getElementById('lostBookTitle').textContent = title;
    document.getElementById('lostForm').action = '<?= base_url('library/issue/lost/') ?>' + issueId;
    var modal = new bootstrap.Modal(document.getElementById('lostModal'));
    modal.show();
}
</script>
