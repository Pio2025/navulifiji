<?php
$documents       = $documents ?? [];
$staff           = $staff ?? [];
$canManageOthers = $canManageOthers ?? false;
$viewingUserId   = $viewingUserId ?? 0;
$viewingUser     = $viewingUser ?? null;
$isLookup        = $viewingUser !== null;
$sharedCount     = $sharedCount ?? 0;

$categoryOrder = ['Image', 'PDF', 'Word', 'Excel', 'PowerPoint', 'Video', 'Other'];
$categoryMeta  = [
    'Image'      => ['color' => 'info'],
    'PDF'        => ['color' => 'danger'],
    'Word'       => ['color' => 'primary'],
    'Excel'      => ['color' => 'success'],
    'PowerPoint' => ['color' => 'warning'],
    'Video'      => ['color' => 'danger'],
    'Other'      => ['color' => 'secondary'],
];

$jsDocs = array_map(function ($d) use ($isLookup, $categoryOrder) {
    return [
        'source_type'    => $d['source_type'],
        'source_file_id' => (int) $d['source_file_id'],
        'name'           => $d['original_name'],
        'label'          => ($d['label'] && $d['label'] !== $d['original_name']) ? $d['label'] : '',
        'source_label'   => $d['source_label'],
        'category'       => in_array($d['category'], $categoryOrder, true) ? $d['category'] : 'Other',
        'icon'           => $d['icon'],
        'color'          => $d['color'],
        'created_at'     => (string) $d['created_at'],
        'is_external'    => !empty($d['is_external']),
        'can_delete'     => !$isLookup && $d['source_type'] === 'personal',
    ];
}, $documents);
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

<!--begin::View toggle-->
<div class="btn-group mb-6" role="group">
    <button type="button" class="btn btn-primary" id="docmgr_btn_folder_view" onclick="docmgrSwitchView('folder')">
        <i class="ki-duotone ki-folder fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
        Folder View
    </button>
    <button type="button" class="btn btn-light" id="docmgr_btn_list_view" onclick="docmgrSwitchView('list')">
        <i class="ki-duotone ki-row-horizontal fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
        List View
    </button>
</div>
<!--end::View toggle-->

<!--begin::Folder view-->
<div id="docmgr_folder_view">
    <div id="docmgr_folder_grid" class="row g-4"></div>

    <div id="docmgr_folder_contents" class="card d-none">
        <div class="card-header border-0 pt-6">
            <div class="card-title d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-icon btn-light me-3" onclick="docmgrCloseFolder()" title="Back to folders">
                    <i class="ki-duotone ki-black-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                </button>
                <h3 class="fw-bold text-gray-900 fs-5 mb-0" id="docmgr_folder_contents_title"></h3>
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
                    <tbody id="docmgr_folder_contents_body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--end::Folder view-->

<!--begin::List view-->
<div id="docmgr_list_view" class="d-none">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-line-tabs mb-5" id="docmgr_list_tabs">
                <?php foreach ($categoryOrder as $i => $cat): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $i === 0 ? 'active' : '' ?>" data-bs-toggle="tab" href="#tab_docmgr_<?= strtolower($cat) ?>"><?= esc($cat) ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content">
                <?php foreach ($categoryOrder as $i => $cat): ?>
                <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="tab_docmgr_<?= strtolower($cat) ?>">
                    <div class="table-responsive">
                        <table id="docmgr_dt_<?= strtolower($cat) ?>" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 w-100">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 bg-light">
                                    <th class="ps-4">File</th>
                                    <th>Source</th>
                                    <th>Date</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!--end::List view-->

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

var CATEGORY_ORDER   = <?= json_encode($categoryOrder, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
var CATEGORY_COLOR   = <?= json_encode(array_map(fn($c) => $c['color'], $categoryMeta), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
var docmgrDocuments   = <?= json_encode($jsDocs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
var DOCMGR_VIEW_BASE     = '<?= base_url('doc-manager/view/') ?>';
var DOCMGR_DOWNLOAD_BASE = '<?= base_url('doc-manager/download/') ?>';

function docmgrEsc(s) {
    var div = document.createElement('div');
    div.textContent = (s === null || s === undefined) ? '' : String(s);
    return div.innerHTML;
}

function docmgrFileIconHtml(doc) {
    return '<i class="ki-duotone ' + doc.icon + ' fs-2x text-' + doc.color + '"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';
}

function docmgrNameCellHtml(doc) {
    var html = '<div class="d-flex align-items-center">';
    html += '<div class="me-3">' + docmgrFileIconHtml(doc) + '</div><div>';
    html += '<a href="javascript:void(0)" class="fw-semibold text-gray-900 text-hover-primary docmgr-btn-preview" data-source-type="' + docmgrEsc(doc.source_type) + '" data-source-id="' + doc.source_file_id + '">' + docmgrEsc(doc.name) + '</a>';
    if (doc.label) {
        html += '<div class="text-muted fs-8">' + docmgrEsc(doc.label) + '</div>';
    }
    html += '</div></div>';
    return html;
}

function docmgrActionsHtml(doc) {
    var html = '<div class="d-flex justify-content-end gap-1">';
    html += '<a href="' + DOCMGR_VIEW_BASE + doc.source_type + '/' + doc.source_file_id + '" target="_blank" class="btn btn-sm btn-icon btn-light-info" title="View"><i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></a>';
    if (!doc.is_external) {
        html += '<a href="' + DOCMGR_DOWNLOAD_BASE + doc.source_type + '/' + doc.source_file_id + '" class="btn btn-sm btn-icon btn-light-success" title="Download"><i class="ki-duotone ki-down fs-5"><span class="path1"></span><span class="path2"></span></i></a>';
        html += '<button type="button" class="btn btn-sm btn-icon btn-light-dark docmgr-btn-print" title="Print" data-source-type="' + docmgrEsc(doc.source_type) + '" data-source-id="' + doc.source_file_id + '"><i class="ki-duotone ki-printer fs-5"><span class="path1"></span><span class="path2"></span></i></button>';
    }
    html += '<button type="button" class="btn btn-sm btn-icon btn-light-primary docmgr-btn-share" title="Share" data-source-type="' + docmgrEsc(doc.source_type) + '" data-source-id="' + doc.source_file_id + '" data-name="' + docmgrEsc(doc.name) + '"><i class="ki-duotone ki-share fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></button>';
    if (doc.can_delete) {
        html += '<button type="button" class="btn btn-sm btn-icon btn-light-danger docmgr-btn-delete" title="Remove" data-source-id="' + doc.source_file_id + '"><i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
    }
    html += '</div>';
    return html;
}

$(document).on('click', '.docmgr-btn-preview', function () {
    docmgrPreview($(this).data('source-type'), $(this).data('source-id'));
});
$(document).on('click', '.docmgr-btn-print', function () {
    docmgrPrint(DOCMGR_VIEW_BASE + $(this).data('source-type') + '/' + $(this).data('source-id'));
});
$(document).on('click', '.docmgr-btn-share', function () {
    docmgrOpenShare($(this).data('source-type'), $(this).data('source-id'), $(this).data('name'));
});
$(document).on('click', '.docmgr-btn-delete', function () {
    docmgrDelete($(this).data('source-id'));
});

// ── Folder view ──────────────────────────────────────────────────────
function docmgrRenderFolders() {
    var counts = {};
    CATEGORY_ORDER.forEach(function (c) { counts[c] = 0; });
    docmgrDocuments.forEach(function (d) { counts[d.category] = (counts[d.category] || 0) + 1; });

    var html = '';
    CATEGORY_ORDER.forEach(function (cat) {
        var color = CATEGORY_COLOR[cat] || 'secondary';
        html += '<div class="col-6 col-md-3 col-lg-2">';
        html += '<div class="card card-flush h-100 cursor-pointer docmgr-folder-tile" data-cat="' + cat + '">';
        html += '<div class="card-body text-center py-8">';
        html += '<i class="ki-duotone ki-folder fs-5x text-' + color + ' mb-3"><span class="path1"></span><span class="path2"></span></i>';
        html += '<div class="fw-bold text-gray-900 fs-5">' + docmgrEsc(cat) + '</div>';
        html += '<div class="text-muted fs-8">' + counts[cat] + ' file' + (counts[cat] === 1 ? '' : 's') + '</div>';
        html += '</div></div></div>';
    });
    $('#docmgr_folder_grid').html(html);
}

$(document).on('click', '.docmgr-folder-tile', function () {
    docmgrOpenFolder($(this).data('cat'));
});

function docmgrOpenFolder(cat) {
    $('#docmgr_folder_grid').addClass('d-none');
    $('#docmgr_folder_contents').removeClass('d-none');
    $('#docmgr_folder_contents_title').text(cat);

    var docs = docmgrDocuments.filter(function (d) { return d.category === cat; });
    var body = '';
    if (!docs.length) {
        body = '<tr><td colspan="4" class="text-center text-muted py-8">No files in this folder.</td></tr>';
    } else {
        docs.forEach(function (d) {
            body += '<tr>';
            body += '<td class="ps-4">' + docmgrNameCellHtml(d) + '</td>';
            body += '<td><span class="badge badge-light-secondary">' + docmgrEsc(d.source_label) + '</span></td>';
            body += '<td class="text-muted fs-7">' + docmgrEsc(d.created_at.substring(0, 16)) + '</td>';
            body += '<td class="text-end pe-4">' + docmgrActionsHtml(d) + '</td>';
            body += '</tr>';
        });
    }
    $('#docmgr_folder_contents_body').html(body);
}

function docmgrCloseFolder() {
    $('#docmgr_folder_contents').addClass('d-none');
    $('#docmgr_folder_grid').removeClass('d-none');
}

// ── List view (tabbed DataTables) ───────────────────────────────────
var docmgrDtInited     = {};
var docmgrListViewOpen = false;

function docmgrInitListTab(cat) {
    if (docmgrDtInited[cat]) return;
    docmgrDtInited[cat] = true;

    var docs = docmgrDocuments.filter(function (d) { return d.category === cat; });

    $('#docmgr_dt_' + cat.toLowerCase()).DataTable({
        data: docs,
        pageLength: 10,
        order: [[2, 'desc']],
        columns: [
            { data: null, render: function (d) { return docmgrNameCellHtml(d); } },
            { data: 'source_label', render: function (v) { return '<span class="badge badge-light-secondary">' + docmgrEsc(v) + '</span>'; } },
            { data: 'created_at', render: function (v) { return docmgrEsc((v || '').substring(0, 16)); } },
            { data: null, className: 'text-end', orderable: false, render: function (d) { return docmgrActionsHtml(d); } },
        ],
        language: { emptyTable: 'No documents in this category.' },
    });
}

function docmgrSwitchView(mode) {
    if (mode === 'folder') {
        $('#docmgr_folder_view').removeClass('d-none');
        $('#docmgr_list_view').addClass('d-none');
        $('#docmgr_btn_folder_view').addClass('btn-primary').removeClass('btn-light');
        $('#docmgr_btn_list_view').addClass('btn-light').removeClass('btn-primary');
    } else {
        $('#docmgr_folder_view').addClass('d-none');
        $('#docmgr_list_view').removeClass('d-none');
        $('#docmgr_btn_list_view').addClass('btn-primary').removeClass('btn-light');
        $('#docmgr_btn_folder_view').addClass('btn-light').removeClass('btn-primary');
        if (!docmgrListViewOpen) {
            docmgrListViewOpen = true;
            docmgrInitListTab(CATEGORY_ORDER[0]);
        }
    }
}

$('#docmgr_list_tabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    docmgrInitListTab($(e.target).text().trim());
});

docmgrRenderFolders();

// ── Preview / print / delete / share ────────────────────────────────
function docmgrPreview(sourceType, sourceId) {
    window.open(DOCMGR_VIEW_BASE + sourceType + '/' + sourceId, '_blank');
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
