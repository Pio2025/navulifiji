<?php
$users     = $users ?? [];
$documents = $documents ?? [];
$search    = $search ?? '';

$jsDocs = array_map(function ($d) {
    return [
        'source_type'    => $d['source_type'],
        'source_file_id' => (int) $d['source_file_id'],
        'name'           => $d['original_name'],
        'label'          => ($d['label'] && $d['label'] !== $d['original_name']) ? $d['label'] : '',
        'source_label'   => $d['source_label'],
        'owner_user_id'  => $d['owner_user_id'],
        'owner_name'     => $d['owner_name'],
        'icon'           => $d['icon'],
        'color'          => $d['color'],
        'created_at'     => (string) $d['created_at'],
        'is_external'    => !empty($d['is_external']),
    ];
}, $documents);
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
            <input type="text" name="search" class="form-control" placeholder="Search by user name or document name…" value="<?= esc($search) ?>" />
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

<?php if ($search !== ''): ?>
<div class="card mb-6">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900 fs-5">Documents (<?= count($documents) ?>)</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <?php if (empty($documents)): ?>
        <div class="text-center py-16">
            <i class="ki-duotone ki-file fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="fs-6 fw-semibold text-gray-600">No documents matched "<?= esc($search) ?>".</div>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table id="docmgr_lookup_dt" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 w-100">
                <thead>
                    <tr class="fw-bold text-muted fs-7 bg-light">
                        <th class="ps-4">File</th>
                        <th>Source</th>
                        <th>Owner</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

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

<?php if ($search !== '' && !empty($documents)): ?>
<style>
.dataTables_wrapper .dataTables_filter { display: none; }
.dataTables_wrapper .dataTables_info { padding-top: 1rem; font-size: 0.925rem; color: #7e8299; }
.dataTables_wrapper .dataTables_paginate { padding-top: 0.5rem; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.5rem 0.75rem; margin: 0 0.25rem; border-radius: 0.475rem; border: 0; background: transparent; color: #7e8299; font-weight: 500; }
.dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f9f9f9; color: #009ef7; border: 0; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #009ef7; color: #fff; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.5; cursor: not-allowed; }
</style>

<script>
"use strict";

var docmgrLookupDocs      = <?= json_encode($jsDocs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
var DOCMGR_LOOKUP_VIEW     = '<?= base_url('doc-manager/view/') ?>';
var DOCMGR_LOOKUP_DOWNLOAD = '<?= base_url('doc-manager/download/') ?>';
var DOCMGR_LOOKUP_USER     = '<?= base_url('doc-manager/lookup/') ?>';

function docmgrLookupEsc(s) {
    var div = document.createElement('div');
    div.textContent = (s === null || s === undefined) ? '' : String(s);
    return div.innerHTML;
}

function docmgrLookupNameCell(doc) {
    var html = '<div class="d-flex align-items-center">';
    html += '<div class="me-3"><i class="ki-duotone ' + doc.icon + ' fs-2x text-' + doc.color + '"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></div><div>';
    html += '<a href="' + DOCMGR_LOOKUP_VIEW + doc.source_type + '/' + doc.source_file_id + '" target="_blank" class="fw-semibold text-gray-900 text-hover-primary">' + docmgrLookupEsc(doc.name) + '</a>';
    if (doc.label) {
        html += '<div class="text-muted fs-8">' + docmgrLookupEsc(doc.label) + '</div>';
    }
    html += '</div></div>';
    return html;
}

function docmgrLookupOwnerCell(doc) {
    return '<a href="' + DOCMGR_LOOKUP_USER + doc.owner_user_id + '" class="text-gray-700 text-hover-primary">' + docmgrLookupEsc(doc.owner_name) + '</a>';
}

function docmgrLookupActionsCell(doc) {
    var html = '<div class="d-flex justify-content-end gap-1">';
    html += '<a href="' + DOCMGR_LOOKUP_VIEW + doc.source_type + '/' + doc.source_file_id + '" target="_blank" class="btn btn-sm btn-icon btn-light-info" title="View"><i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></a>';
    if (!doc.is_external) {
        html += '<a href="' + DOCMGR_LOOKUP_DOWNLOAD + doc.source_type + '/' + doc.source_file_id + '" class="btn btn-sm btn-icon btn-light-success" title="Download"><i class="ki-duotone ki-down fs-5"><span class="path1"></span><span class="path2"></span></i></a>';
    }
    html += '</div>';
    return html;
}

$('#docmgr_lookup_dt').DataTable({
    data: docmgrLookupDocs,
    pageLength: 10,
    order: [[3, 'desc']],
    columns: [
        { data: null, render: function (d) { return docmgrLookupNameCell(d); } },
        { data: 'source_label', render: function (v) { return '<span class="badge badge-light-secondary">' + docmgrLookupEsc(v) + '</span>'; } },
        { data: null, render: function (d) { return docmgrLookupOwnerCell(d); } },
        { data: 'created_at', render: function (v) { return docmgrLookupEsc((v || '').substring(0, 16)); } },
        { data: null, className: 'text-end', orderable: false, render: function (d) { return docmgrLookupActionsCell(d); } },
    ],
    language: { emptyTable: 'No documents matched.' },
});
</script>
<?php endif; ?>
