<?php
$canAdd    = $canAdd    ?? false;
$canExport = $canExport ?? false;
?>
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">School Category Listing</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">School Category Listing</li>
            </ul>
        </div>
        <?php if ($canAdd || $canExport): ?>
        <div class="d-flex align-items-center gap-2">
            <?= $this->include('templates/import_export_toolbar', ['canExport' => $canExport]) ?>
            <?php if ($canAdd): ?>
            <a href="<?= base_url('school/category/add') ?>" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Add School Category
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
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
                    <i class="ki-duotone ki-bank fs-4x text-gray-200 mb-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="fs-6 fw-semibold text-gray-600 mb-2">No school categories found</div>
                    <div class="fs-7 text-muted mb-6">Add your first school category to get started.</div>
                    <?php if ($canAdd): ?>
                    <a href="<?= base_url('school/category/add') ?>" class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-plus fs-4 me-1"></i>Add School Category
                    </a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 dataTable" id="cat_table">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 bg-light">
                                <th class="ps-4 min-w-40px rounded-start">#</th>
                                <th class="min-w-80px">Initial</th>
                                <th class="min-w-200px">Category Name</th>
                                <th class="min-w-100px text-center">Terms/Year</th>
                                <th class="min-w-100px">Term Label</th>
                                <th class="min-w-80px text-center">Terms Set</th>
                                <th class="min-w-100px text-end rounded-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $canEdit   = $canEdit   ?? false;
                        $canDelete = $canDelete ?? false;

                        // Term-detail blocks are rendered OUTSIDE the table body (see
                        // #cat_term_templates below) and pulled in on demand via DataTables'
                        // own row().child() API — NOT as sibling <tr class="collapse"> rows.
                        // DataTables expects exactly one <tr> per data row in <tbody>; a raw
                        // Bootstrap-collapse sibling row would get treated as its own
                        // sortable/paginatable/exportable "row", which both breaks pagination
                        // (an expanded category could land on a different page than its detail
                        // row) and would leak term-detail text into CSV/Excel/PDF export. Using
                        // row().child() keeps the detail HTML entirely outside DataTables' row
                        // model — it is never counted, sorted, paginated, or exported.
                        $termTemplates = '';

                        foreach ($categories as $i => $cat):
                            $config = $cat['config'] ?? null;
                            $terms  = $cat['terms']  ?? [];
                        ?>
                        <tr data-cat-id="<?= (int)$cat['sch_cat_id'] ?>">
                            <td class="ps-4 text-muted fs-7"><?= $i + 1 ?></td>
                            <td>
                                <span class="badge badge-light-primary fw-bold fs-7">
                                    <?= esc($cat['sch_cat_initial'] ?? '') ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-gray-800 fs-6"><?= esc($cat['sch_cat_name']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($terms)): ?>
                                    <span class="fw-bold text-gray-800"><?= count($terms) ?></span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic fs-8">Not set</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($config): ?>
                                    <span class="text-gray-700 fs-7"><?= esc($config['label_for_term']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($terms)): ?>
                                    <button type="button"
                                        class="btn btn-sm btn-light-success py-1 px-3 btn-toggle-terms"
                                        data-cat-id="<?= (int)$cat['sch_cat_id'] ?>">
                                        <?= count($terms) ?> terms
                                        <i class="ki-duotone ki-down fs-6 ms-1"><span class="path1"></span></i>
                                    </button>
                                <?php else: ?>
                                    <span class="badge badge-light-warning fs-9">No entries</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <?php if ($canEdit): ?>
                                    <a href="<?= base_url('school/category/edit/' . (int)$cat['sch_cat_id']) ?>"
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
                                        data-cat-id="<?= (int)$cat['sch_cat_id'] ?>"
                                        data-cat-name="<?= esc($cat['sch_cat_name'], 'attr') ?>"
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
                        <?php
                        if (!empty($terms)):
                            ob_start();
                            $MONTHS_SHORT = ['','Jan','Feb','Mar','Apr','May','Jun',
                                             'Jul','Aug','Sep','Oct','Nov','Dec'];
                            $termDateLabel = function (int $day, int $month) use ($MONTHS_SHORT): string {
                                if (!$day || !$month || !isset($MONTHS_SHORT[$month])) return '—';
                                return $day . ' ' . $MONTHS_SHORT[$month];
                            };
                        ?>
                        <div class="bg-light-primary rounded p-4">
                            <div class="fw-bold text-gray-700 fs-7 mb-3">
                                <i class="ki-duotone ki-calendar fs-5 me-1 text-primary">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <?= esc($config['label_for_term'] ?? 'Term') ?> Dates — <?= esc($cat['sch_cat_name']) ?>
                            </div>
                            <div class="row g-3">
                            <?php foreach ($terms as $term): ?>
                                <div class="col-md-4 col-lg-3">
                                    <div class="card border border-dashed border-primary-subtle">
                                        <div class="card-body p-3">
                                            <div class="fw-bold text-primary fs-7 mb-2">
                                                <?= esc($config['label_for_term'] ?? 'Term') ?> <?= (int) $term['term_num'] ?>
                                            </div>
                                            <div class="d-flex flex-column gap-1 fs-8 text-gray-700">
                                                <div>
                                                    <span class="text-muted">Weeks:</span>
                                                    <span class="fw-semibold"><?= (int)($term['num_of_week'] ?? 0) ?></span>
                                                </div>
                                                <div>
                                                    <span class="text-muted">Start:</span>
                                                    <span class="fw-semibold"><?= $termDateLabel((int)($term['term_start_day'] ?? 0), (int)($term['term_start_month'] ?? 0)) ?></span>
                                                </div>
                                                <div>
                                                    <span class="text-muted">End:</span>
                                                    <span class="fw-semibold"><?= $termDateLabel((int)($term['term_end_day'] ?? 0), (int)($term['term_end_month'] ?? 0)) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                        <?php
                            $termTemplates .= '<div data-cat-id="' . (int)$cat['sch_cat_id'] . '">' . ob_get_clean() . '</div>';
                        endif;
                        ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!--begin::Term detail templates (not part of the DataTable's row model)-->
                <div id="cat_term_templates" class="d-none"><?= $termTemplates ?></div>
                <!--end::Term detail templates-->
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
                        <h5 class="fw-bold text-gray-800 mb-0">Delete School Category</h5>
                        <span class="text-muted fs-7">This action cannot be undone</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4 pb-2">
                <p class="text-gray-700 fs-6">
                    Are you sure you want to delete <strong id="deleteCatName"></strong>?
                    This will also remove its configuration and all term date entries.
                </p>
                <div class="notice d-flex bg-light-warning rounded p-3 mt-3">
                    <i class="ki-duotone ki-information-5 fs-4 text-warning me-2 flex-shrink-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <span class="fs-7 text-gray-700">Deletion is blocked if any school is currently using this category.</span>
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
"use strict";

function confirmDelete(btn) {
    var id   = btn.getAttribute('data-cat-id');
    var name = btn.getAttribute('data-cat-name');
    document.getElementById('deleteCatName').textContent = name;
    document.getElementById('deleteCatForm').action = '<?= base_url('school/category/remove/') ?>' + id;
    var modal = new bootstrap.Modal(document.getElementById('deleteCatModal'));
    modal.show();
}

if (document.getElementById('cat_table')) {
    const catTable = $('#cat_table').DataTable({
        pageLength:  15,
        lengthMenu:  [[10, 15, 25, 50], [10, 15, 25, 50]],
        order:       [[2, 'asc']],
        dom:
            '<"row align-items-center mb-4"' +
                '<"col-sm-6"l>' +
                '<"col-sm-6 d-flex justify-content-end"p>' +
            '>' +
            't' +
            '<"row align-items-center mt-4"' +
                '<"col-sm-6 text-muted fs-7"i>' +
                '<"col-sm-6 d-flex justify-content-end"p>' +
            '>',
        language: {
            lengthMenu:  'Show _MENU_ categories',
            info:        'Showing _START_ to _END_ of _TOTAL_ categories',
            infoEmpty:   'No categories found',
            emptyTable:  '<div class="text-center text-muted py-10">No school category records found</div>',
            paginate: {
                previous: '<i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i>',
                next:     '<i class="ki-duotone ki-arrow-right fs-4"><span class="path1"></span><span class="path2"></span></i>',
            }
        },
        columnDefs: [
            { targets: 6, orderable: false }
        ],
        drawCallback: function() {
            $('.dataTables_paginate .paginate_button').addClass('btn btn-sm btn-light me-1');
            $('.dataTables_paginate .paginate_button.current').removeClass('btn-light').addClass('btn-primary');
        }
    });

    <?php if ($canExport): ?>
    KTExport.init(catTable, {
        filenamePrefix: 'school_categories',
        title: 'School Categories Report',
        columns: [
            { header: 'Initial', index: 1 },
            { header: 'Category Name', index: 2 },
            { header: 'Terms/Year', index: 3 },
            { header: 'Term Label', index: 4 },
            { header: 'Terms Set', index: 5 },
        ]
    });
    <?php endif; ?>

    // ── Search (real DataTable search now — replaces the old vanilla-JS
    //    keyup filter that had to explicitly skip collapse sibling rows;
    //    that special-casing is no longer needed since child-row detail
    //    content isn't part of DataTables' searchable row data at all) ──
    $('#cat_search').on('keyup', function() {
        catTable.search($(this).val()).draw();
    });

    // ── Expand/collapse term details via DataTables' own child-row API.
    //    The detail HTML lives in the hidden #cat_term_templates container
    //    (rendered once per category, outside <tbody>) and is injected as a
    //    DataTables child row only when toggled open — never as a real
    //    <tbody><tr> that DataTables would try to paginate/sort/export. ──
    $('#cat_table tbody').on('click', '.btn-toggle-terms', function () {
        const btn   = this;
        const tr    = $(btn).closest('tr');
        const row   = catTable.row(tr);
        const catId = $(btn).data('cat-id');

        if (row.child.isShown()) {
            row.child.hide();
            $(btn).find('i').removeClass('rotate-180');
        } else {
            const tpl = document.querySelector('#cat_term_templates [data-cat-id="' + catId + '"]');
            row.child(tpl ? tpl.innerHTML : '<div class="p-4 text-muted">No term details.</div>').show();
            $(btn).find('i').addClass('rotate-180');
        }
    });
}
</script>
