<?php
$documents       = $documents ?? [];
$staff           = $staff ?? [];
$canManageOthers = $canManageOthers ?? false;
$viewingUserId   = $viewingUserId ?? 0;
$viewingUser     = $viewingUser ?? null;
$isLookup        = $viewingUser !== null;
$sharedCount     = $sharedCount ?? 0;

$grouped = [];
foreach ($documents as $doc) {
    $grouped[$doc['category']][] = $doc;
}
ksort($grouped);
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= $isLookup ? 'Documents — ' . esc(trim($viewingUser['fname'] . ' ' . $viewingUser['lname'])) : 'My Documents' ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <?php if ($isLookup): ?>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('doc-manager/lookup') ?>" class="text-muted text-hover-primary">Doc Manager Lookup</a>
                </li>
                <?php else: ?>
                <li class="breadcrumb-item text-muted">Doc Manager</li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($isLookup): ?>
            <a href="<?= base_url('doc-manager/lookup') ?>" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2 me-1"><span class="path1"></span><span class="path2"></span></i>
                Back to Lookup
            </a>
            <?php else: ?>
            <a href="<?= base_url('doc-manager/shared') ?>" class="btn btn-light position-relative">
                <i class="ki-duotone ki-share fs-2 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Shared With Me
                <?php if ($sharedCount > 0): ?>
                <span class="badge badge-circle badge-primary position-absolute top-0 start-100 translate-middle"><?= (int) $sharedCount ?></span>
                <?php endif; ?>
            </a>
            <?php if ($canManageOthers): ?>
            <a href="<?= base_url('doc-manager/lookup') ?>" class="btn btn-light">
                <i class="ki-duotone ki-search-list fs-2 me-1"><span class="path1"></span><span class="path2"></span></i>
                Lookup
            </a>
            <?php endif; ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
                <i class="ki-duotone ki-plus fs-2"></i>
                Upload Document
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php if (empty($documents)): ?>
<div class="card">
    <div class="card-body text-center py-16">
        <i class="ki-duotone ki-file fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <div class="fs-6 fw-semibold text-gray-600">No documents found.</div>
    </div>
</div>
<?php else: ?>

<div class="row g-4 mb-6">
    <?php foreach ($grouped as $category => $docs): $first = $docs[0]; ?>
    <div class="col-6 col-md-3 col-lg-2">
        <div class="card card-flush h-100">
            <div class="card-body text-center py-6">
                <i class="ki-duotone <?= esc($first['icon']) ?> fs-3x text-<?= esc($first['color']) ?> mb-2">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                </i>
                <div class="fw-bold text-gray-900"><?= esc($category) ?></div>
                <div class="text-muted fs-8"><?= count($docs) ?> file<?= count($docs) === 1 ? '' : 's' ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php foreach ($grouped as $category => $docs): $first = $docs[0]; ?>
<div class="card mb-6">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <i class="ki-duotone <?= esc($first['icon']) ?> fs-2 text-<?= esc($first['color']) ?> me-2">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
            </i>
            <h3 class="fw-bold text-gray-900 fs-5 mb-0"><?= esc($category) ?> (<?= count($docs) ?>)</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted fs-7 bg-light">
                        <th class="ps-4">File</th>
                        <th>Source</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($docs as $doc): ?>
                <tr>
                    <td class="ps-4">
                        <a href="javascript:void(0)" onclick="docmgrPreview('<?= esc($doc['source_type']) ?>', <?= (int) $doc['source_file_id'] ?>)" class="fw-semibold text-gray-900 text-hover-primary">
                            <?= esc($doc['original_name']) ?>
                        </a>
                        <?php if (!empty($doc['label']) && $doc['label'] !== $doc['original_name']): ?>
                        <div class="text-muted fs-8"><?= esc($doc['label']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge badge-light-secondary"><?= esc($doc['source_label']) ?></span></td>
                    <td class="text-muted fs-7"><?= esc(substr((string) $doc['created_at'], 0, 16)) ?></td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="<?= base_url('doc-manager/view/' . $doc['source_type'] . '/' . $doc['source_file_id']) ?>" target="_blank" class="btn btn-sm btn-icon btn-light-info" title="View">
                                <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </a>
                            <a href="<?= base_url('doc-manager/download/' . $doc['source_type'] . '/' . $doc['source_file_id']) ?>" class="btn btn-sm btn-icon btn-light-success" title="Download">
                                <i class="ki-duotone ki-down fs-5"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-icon btn-light-dark" title="Print" onclick="docmgrPrint('<?= base_url('doc-manager/view/' . $doc['source_type'] . '/' . $doc['source_file_id']) ?>')">
                                <i class="ki-duotone ki-printer fs-5"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-light-primary" title="Share"
                                onclick="docmgrOpenShare('<?= esc($doc['source_type']) ?>', <?= (int) $doc['source_file_id'] ?>, '<?= esc(addslashes($doc['original_name'])) ?>')">
                                <i class="ki-duotone ki-share fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </button>
                            <?php if (!$isLookup && $doc['source_type'] === 'personal'): ?>
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger" title="Remove" onclick="docmgrDelete(<?= (int) $doc['source_file_id'] ?>)">
                                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php endif; ?>

</div>
</div>

<!--begin::Upload modal-->
<?php if (!$isLookup): ?>
<div class="modal fade" id="uploadDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="upload_doc_form">
                    <?= csrf_field() ?>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">File</label>
                        <input type="file" name="document" class="form-control" required />
                        <div class="form-text">PDF, Word, Excel, PowerPoint, images, txt, or zip — max 15MB.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" maxlength="255" class="form-control" placeholder="Optional note about this file" />
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn_upload_doc" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>
<form id="deleteDocForm" action="" method="POST" class="d-none">
    <?= csrf_field() ?>
</form>
<?php endif; ?>
<!--end::Upload modal-->

<!--begin::Share modal-->
<div class="modal fade" id="shareDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Share "<span id="share_doc_name"></span>"</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <ul class="nav nav-tabs nav-line-tabs mb-5">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_share_staff">Share with Staff</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_share_link">Public Link</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab_share_staff">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Staff member</label>
                            <select id="share_staff_select" class="form-select" style="width:100%">
                                <option value="">— Select staff —</option>
                                <?php foreach ($staff as $s): ?>
                                <option value="<?= (int) $s['user_id'] ?>"><?= esc(trim($s['fname'] . ' ' . $s['lname'])) ?> — <?= esc($s['role_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" id="btn_share_staff" class="btn btn-primary btn-sm">Share with Staff</button>
                    </div>
                    <div class="tab-pane fade" id="tab_share_link">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Expires (optional)</label>
                                <input type="date" id="share_link_expires" class="form-control" />
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Allow download</label>
                                <select id="share_link_can_download" class="form-select">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">View only</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" id="btn_share_link" class="btn btn-primary btn-sm mb-3">Generate Link</button>
                        <div id="share_link_result" class="d-none input-group">
                            <input type="text" id="share_link_output" class="form-control" readonly />
                            <button type="button" class="btn btn-light-primary" onclick="docmgrCopyLink()">Copy</button>
                        </div>
                    </div>
                </div>
                <div class="separator my-5"></div>
                <div class="fw-semibold fs-7 text-muted mb-2">Active Shares</div>
                <div id="share_list_container" class="fs-7">Loading…</div>
            </div>
        </div>
    </div>
</div>
<!--end::Share modal-->

<script>
"use strict";

$('#share_staff_select').select2({ placeholder: '— Select staff —', width: '100%', dropdownParent: $('#shareDocModal') });

var docmgrCurrentSource = null;
var docmgrCurrentId     = null;

function docmgrPreview(sourceType, sourceId) {
    window.open('<?= base_url('doc-manager/view/') ?>' + sourceType + '/' + sourceId, '_blank');
}

function docmgrPrint(url) {
    var win = window.open(url, '_blank');
    if (win) {
        win.addEventListener('load', function () {
            setTimeout(function () { win.print(); }, 400);
        });
    }
}

function docmgrDelete(docId) {
    Swal.fire({
        title: 'Remove this document?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove',
    }).then(function (result) {
        if (result.isConfirmed) {
            var form = document.getElementById('deleteDocForm');
            form.action = '<?= base_url('doc-manager/remove/') ?>' + docId;
            form.submit();
        }
    });
}

function docmgrLoadShares() {
    $('#share_list_container').html('Loading…');
    $.get('<?= base_url('doc-manager/shares/') ?>' + docmgrCurrentSource + '/' + docmgrCurrentId, function (res) {
        if (!res.success || !res.shares.length) {
            $('#share_list_container').html('<span class="text-muted">No active shares.</span>');
            return;
        }
        var html = '<div class="d-flex flex-column gap-2">';
        res.shares.forEach(function (s) {
            html += '<div class="d-flex align-items-center justify-content-between border rounded px-3 py-2">';
            html += '<div>';
            if (s.share_type === 'user') {
                html += '<i class="ki-duotone ki-profile-circle fs-4 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>' + s.shared_with;
            } else {
                html += '<i class="ki-duotone ki-link fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>Public link' + (s.can_download ? '' : ' (view only)');
            }
            html += '</div>';
            html += '<button type="button" class="btn btn-sm btn-icon btn-light-danger" onclick="docmgrRevokeShare(' + s.share_id + ')"><i class="ki-duotone ki-cross fs-6"><span class="path1"></span><span class="path2"></span></i></button>';
            html += '</div>';
        });
        html += '</div>';
        $('#share_list_container').html(html);
    });
}

function docmgrOpenShare(sourceType, sourceId, name) {
    docmgrCurrentSource = sourceType;
    docmgrCurrentId     = sourceId;
    $('#share_doc_name').text(name);
    $('#share_link_result').addClass('d-none');
    $('#share_staff_select').val('').trigger('change');
    var modal = new bootstrap.Modal(document.getElementById('shareDocModal'));
    modal.show();
    docmgrLoadShares();
}

function docmgrRevokeShare(shareId) {
    $.post('<?= base_url('doc-manager/share/revoke/') ?>' + shareId, { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' }, function () {
        docmgrLoadShares();
    });
}

$('#btn_share_staff').on('click', function () {
    var staffId = $('#share_staff_select').val();
    if (!staffId) {
        Swal.fire({ title: 'Missing information', text: 'Please select a staff member.', icon: 'warning' });
        return;
    }
    $.post('<?= base_url('doc-manager/share/') ?>' + docmgrCurrentSource + '/' + docmgrCurrentId, {
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
        share_type: 'user',
        shared_with_user_id: staffId,
    }, function (res) {
        if (res.success) {
            $('#share_staff_select').val('').trigger('change');
            docmgrLoadShares();
        } else {
            Swal.fire({ title: 'Error', text: res.message, icon: 'error' });
        }
    });
});

$('#btn_share_link').on('click', function () {
    $.post('<?= base_url('doc-manager/share/') ?>' + docmgrCurrentSource + '/' + docmgrCurrentId, {
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
        share_type: 'link',
        can_download: $('#share_link_can_download').val(),
        expires_at: $('#share_link_expires').val(),
    }, function (res) {
        if (res.success) {
            $('#share_link_output').val(res.link);
            $('#share_link_result').removeClass('d-none');
            docmgrLoadShares();
        } else {
            Swal.fire({ title: 'Error', text: res.message, icon: 'error' });
        }
    });
});

function docmgrCopyLink() {
    var el = document.getElementById('share_link_output');
    el.select();
    document.execCommand('copy');
}

<?php if (!$isLookup): ?>
document.getElementById('btn_upload_doc').addEventListener('click', function () {
    var btn = this;
    var fileInput = document.querySelector('#upload_doc_form [name="document"]');
    if (!fileInput.files.length) {
        Swal.fire({ title: 'Missing file', text: 'Please choose a file to upload.', icon: 'warning' });
        return;
    }

    var formData = new FormData(document.getElementById('upload_doc_form'));

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url: '<?= base_url('doc-manager/upload') ?>', type: 'POST', data: formData, processData: false, contentType: false,
        success: function (response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Uploaded!', text: response.message, icon: 'success', timer: 1800, showConfirmButton: false })
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
<?php endif; ?>
</script>
