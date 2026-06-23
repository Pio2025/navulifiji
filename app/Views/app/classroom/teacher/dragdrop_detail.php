<?php
$statusColor = $quiz['quizze_status'] === 'Published' ? 'success' : 'warning';
$itemCount   = count($items);
$zoneCount   = count($zones);
$linkedCount = count($answerMap);
$ITEM_BASE   = base_url('classroom/lesson/' . $lesson['lesson_id'] . '/dragdrop/' . $quiz['lesson_quizze_id'] . '/item');
$ZONE_BASE   = base_url('classroom/lesson/' . $lesson['lesson_id'] . '/dragdrop/' . $quiz['lesson_quizze_id'] . '/zone');
$ANSWERS_URL = base_url('classroom/lesson/' . $lesson['lesson_id'] . '/dragdrop/' . $quiz['lesson_quizze_id'] . '/answers/save');
$UPDATE_URL  = base_url('classroom/lesson/' . $lesson['lesson_id'] . '/quiz/' . $quiz['lesson_quizze_id'] . '/update');
$BACK_URL    = base_url('classroom/teacher/' . $schSubId . '/lesson/' . $lesson['lesson_id']);
$IMG_BASE    = base_url('uploads/dragdrop_files/');
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($quiz['quizze_name']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= $BACK_URL ?>" class="text-muted text-hover-primary"><?= esc($lesson['lesson_title']) ?></a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Drag &amp; Drop Builder</li>
            </ul>
        </div>
        <a href="<?= $BACK_URL ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>Back to Lesson
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if ($quiz['quizze_status'] === 'Published'): ?>
    <div class="alert alert-success d-flex align-items-center gap-3 py-3 mb-5">
        <i class="ki-duotone ki-shield-tick fs-2x flex-shrink-0 text-success"><span class="path1"></span><span class="path2"></span></i>
        <div>
            <div class="fw-bold fs-7">This assessment is Published — editing is locked.</div>
            <div class="text-muted fs-8">Students can now take this assessment. To make changes, change the status to <strong>Draft</strong> from the lesson page first.</div>
        </div>
    </div>
    <?php endif; ?>

    <!--begin::Assessment header card-->
    <div class="card border-0 shadow-sm mb-6">
        <div class="card-body p-5">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center justify-content-center bg-light-primary rounded-2 flex-shrink-0" style="width:52px;height:52px;">
                        <i class="ki-duotone ki-abstract-26 fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <div>
                        <div class="fw-bold text-gray-900 fs-5 mb-1" id="dd_title_display"><?= esc($quiz['quizze_name']) ?></div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge badge-light-primary fs-9">Drag &amp; Drop</span>
                            <span class="badge badge-light-<?= $statusColor ?> fs-9" id="dd_status_badge"><?= esc($quiz['quizze_status']) ?></span>
                            <?php if ($quiz['quizze_duration'] > 0): ?>
                            <span class="badge badge-light-secondary fs-9"><?= (int)$quiz['quizze_duration'] ?> min</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!--begin::Stats-->
                    <div class="text-center px-4">
                        <div class="fw-bold text-gray-800 fs-4" id="stat_items"><?= $itemCount ?></div>
                        <div class="text-muted fs-9">Items</div>
                    </div>
                    <div class="text-center px-4" style="border-left:1px solid #f1f1f4;">
                        <div class="fw-bold text-gray-800 fs-4" id="stat_zones"><?= $zoneCount ?></div>
                        <div class="text-muted fs-9">Zones</div>
                    </div>
                    <div class="text-center px-4" style="border-left:1px solid #f1f1f4;">
                        <div class="fw-bold text-<?= $linkedCount === $itemCount && $itemCount > 0 ? 'success' : 'warning' ?> fs-4" id="stat_linked"><?= $linkedCount ?>/<?= $itemCount ?></div>
                        <div class="text-muted fs-9">Linked</div>
                    </div>
                    <!--end::Stats-->
                    <button type="button" class="btn btn-sm btn-light ms-2" id="btn_edit_dd" title="Edit name / status">
                        <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Assessment header card-->

    <div class="row g-6">

        <!--begin::Items panel (col-5)-->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-0 pt-5 pb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ki-duotone ki-cursor fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        <div>
                            <h6 class="fw-bold text-gray-800 mb-0">Drag Items</h6>
                            <span class="text-muted fs-9">Things students will drag</span>
                        </div>
                        <span class="badge badge-light-primary fs-9 ms-2" id="items_count_badge"><?= $itemCount ?></span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">

                    <!--begin::Items list-->
                    <div id="items_list" class="d-flex flex-column gap-2 mb-4">
                    <?php if (empty($items)): ?>
                        <div class="text-center py-6 text-muted" id="items_empty">
                            <i class="ki-duotone ki-cursor fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                            <div class="fs-8">No items yet. Add your first drag item below.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-2 item-row"
                             id="item_row_<?= $item['item_id'] ?>"
                             data-item-text="<?= esc($item['item_text'], 'attr') ?>"
                             data-item-image="<?= esc($item['item_image'] ?? '', 'attr') ?>">
                            <div class="item-thumb-wrap flex-shrink-0" style="width:40px;height:40px;">
                                <?php if (!empty($item['item_image'])): ?>
                                <img src="<?= $IMG_BASE . esc($item['item_image']) ?>" class="rounded-2"
                                     style="width:40px;height:40px;object-fit:cover;" alt="">
                                <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light-primary rounded-2" style="width:40px;height:40px;">
                                    <i class="ki-duotone ki-abstract-26 fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <span class="fw-semibold text-gray-800 fs-7 flex-grow-1 item-view-text"><?= esc($item['item_text']) ?></span>
                            <div class="flex-grow-1 item-edit-form" style="display:none;">
                                <div class="d-flex flex-column gap-2">
                                    <input type="text" class="form-control form-control-sm item-edit-text-input"
                                           value="<?= esc($item['item_text'], 'attr') ?>" maxlength="500">
                                    <input type="file" class="form-control form-control-sm item-edit-image-input"
                                           accept=".jpg,.jpeg,.png,.gif,.webp">
                                </div>
                            </div>
                            <div class="d-flex gap-1 flex-shrink-0 item-view-btns">
                                <button type="button" class="btn btn-icon btn-sm btn-light-primary btn-edit-item"
                                        data-item-id="<?= $item['item_id'] ?>">
                                    <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-del-item"
                                        data-item-id="<?= $item['item_id'] ?>">
                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </button>
                            </div>
                            <div class="d-flex gap-1 flex-shrink-0 item-edit-btns" style="display:none;">
                                <button type="button" class="btn btn-icon btn-sm btn-primary btn-save-item"
                                        data-item-id="<?= $item['item_id'] ?>">
                                    <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm btn-light btn-cancel-item"
                                        data-item-id="<?= $item['item_id'] ?>">
                                    <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
                    <!--end::Items list-->

                    <!--begin::Add item form-->
                    <div class="separator separator-dashed mb-4"></div>
                    <div class="fw-semibold text-gray-700 fs-8 mb-3">Add Item</div>
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-sm" id="new_item_text"
                               placeholder="e.g. Water, Carbon Dioxide, Photosynthesis…" maxlength="500" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-9 text-muted mb-1">Image <span class="fw-normal">(optional)</span></label>
                        <input type="file" class="form-control form-control-sm" id="new_item_image"
                               accept=".jpg,.jpeg,.png,.gif,.webp" />
                    </div>
                    <button type="button" class="btn btn-sm btn-primary w-100" id="btn_add_item">
                        <span class="indicator-label">
                            <i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Add Item
                        </span>
                        <span class="indicator-progress">Adding… <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                    </button>
                    <!--end::Add item form-->

                </div>
            </div>
        </div>
        <!--end::Items panel-->

        <!--begin::Zones panel (col-4)-->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-0 pt-5 pb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ki-duotone ki-drop fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                        <div>
                            <h6 class="fw-bold text-gray-800 mb-0">Drop Zones</h6>
                            <span class="text-muted fs-9">Destinations for the items</span>
                        </div>
                        <span class="badge badge-light-success fs-9 ms-2" id="zones_count_badge"><?= $zoneCount ?></span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4">

                    <!--begin::Zones list-->
                    <div id="zones_list" class="d-flex flex-column gap-2 mb-4">
                    <?php if (empty($zones)): ?>
                        <div class="text-center py-6 text-muted" id="zones_empty">
                            <i class="ki-duotone ki-drop fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                            <div class="fs-8">No zones yet. Add your first drop zone below.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($zones as $zone): ?>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light-success rounded-2 zone-row"
                             id="zone_row_<?= $zone['zone_id'] ?>"
                             data-zone-label="<?= esc($zone['zone_label'], 'attr') ?>">
                            <i class="ki-duotone ki-drop fs-4 text-success flex-shrink-0"><span class="path1"></span><span class="path2"></span></i>
                            <span class="fw-semibold text-gray-800 fs-7 flex-grow-1 zone-view-label"><?= esc($zone['zone_label']) ?></span>
                            <input type="text" class="form-control form-control-sm flex-grow-1 zone-edit-input"
                                   value="<?= esc($zone['zone_label'], 'attr') ?>" maxlength="500" style="display:none;">
                            <div class="d-flex gap-1 flex-shrink-0 zone-view-btns">
                                <button type="button" class="btn btn-icon btn-sm btn-light-success btn-edit-zone"
                                        data-zone-id="<?= $zone['zone_id'] ?>">
                                    <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-del-zone"
                                        data-zone-id="<?= $zone['zone_id'] ?>">
                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </button>
                            </div>
                            <div class="d-flex gap-1 flex-shrink-0 zone-edit-btns" style="display:none;">
                                <button type="button" class="btn btn-icon btn-sm btn-success btn-save-zone"
                                        data-zone-id="<?= $zone['zone_id'] ?>">
                                    <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-sm btn-light btn-cancel-zone"
                                        data-zone-id="<?= $zone['zone_id'] ?>">
                                    <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
                    <!--end::Zones list-->

                    <!--begin::Add zone form-->
                    <div class="separator separator-dashed mb-4"></div>
                    <div class="fw-semibold text-gray-700 fs-8 mb-3">Add Zone</div>
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-sm" id="new_zone_label"
                               placeholder="e.g. Reactants, Products, Living Things…" maxlength="500" />
                    </div>
                    <button type="button" class="btn btn-sm btn-success w-100" id="btn_add_zone">
                        <span class="indicator-label">
                            <i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Add Zone
                        </span>
                        <span class="indicator-progress">Adding… <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                    </button>
                    <!--end::Add zone form-->

                </div>
            </div>
        </div>
        <!--end::Zones panel-->

        <!--begin::Answer key panel (col-3)-->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-light-warning" style="border:1.5px dashed #ffc700 !important;">
                <div class="card-body p-5 d-flex flex-column align-items-center justify-content-center text-center gap-3">
                    <i class="ki-duotone ki-information fs-3x text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div>
                        <div class="fw-bold text-gray-800 fs-6 mb-1">Link items to zones</div>
                        <div class="text-muted fs-8">Use the <strong>Answer Key</strong> panel below to assign each item to its correct drop zone. Students will be scored based on these mappings.</div>
                    </div>
                    <a href="#answer_key_panel" class="btn btn-sm btn-warning">
                        <i class="ki-duotone ki-arrow-down fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Go to Answer Key
                    </a>
                </div>
            </div>
        </div>
        <!--end::Answer key panel-->

    </div>

    <!--begin::Answer key card-->
    <div class="card border-0 shadow-sm mt-6" id="answer_key_panel">
        <div class="card-header border-0 pt-5 pb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-key fs-3 text-warning"><span class="path1"></span><span class="path2"></span></i>
                <div>
                    <h6 class="fw-bold text-gray-800 mb-0">Answer Key</h6>
                    <span class="text-muted fs-9">Assign each item to its correct drop zone</span>
                </div>
                <span class="badge badge-light-warning fs-9 ms-2" id="linked_badge"><?= $linkedCount ?>/<?= $itemCount ?> linked</span>
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-warning" id="btn_save_answers">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Save Answer Key
                    </span>
                    <span class="indicator-progress">Saving… <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
        <div class="card-body pt-3 pb-6">

            <div id="answer_key_empty_state" class="text-center py-10 text-muted" style="<?= $itemCount > 0 ? 'display:none;' : '' ?>">
                <i class="ki-duotone ki-key fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
                <div class="fs-7 fw-semibold mb-1">No items to map</div>
                <div class="fs-8">Add items and zones first, then link them here.</div>
            </div>

            <div id="answer_key_table_wrap" style="<?= $itemCount === 0 ? 'display:none;' : '' ?>">
                <?php if ($zoneCount === 0 && $itemCount > 0): ?>
                <div class="alert alert-warning d-flex align-items-center gap-2 py-3 mb-4">
                    <i class="ki-duotone ki-information-5 fs-4 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <span class="fs-8">Add at least one drop zone before mapping items.</span>
                </div>
                <?php endif; ?>
                <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3 fs-7" id="answer_key_table">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="ps-4 min-w-50px">#</th>
                            <th class="min-w-60px">Image</th>
                            <th class="min-w-200px">Drag Item</th>
                            <th class="min-w-220px">Correct Drop Zone</th>
                            <th class="text-center min-w-80px">Status</th>
                        </tr>
                    </thead>
                    <tbody id="answer_key_tbody">
                    <?php foreach ($items as $idx => $item):
                        $mappedZone = $answerMap[$item['item_id']] ?? 0;
                    ?>
                    <tr id="akrow_<?= $item['item_id'] ?>">
                        <td class="ps-4 text-muted fs-8"><?= $idx + 1 ?></td>
                        <td>
                            <?php if (!empty($item['item_image'])): ?>
                            <img src="<?= $IMG_BASE . esc($item['item_image']) ?>" class="rounded-2"
                                 style="width:36px;height:36px;object-fit:cover;" alt="">
                            <?php else: ?>
                            <span class="text-muted fs-9">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="fw-semibold text-gray-800"><?= esc($item['item_text']) ?></span>
                        </td>
                        <td>
                            <select class="form-select form-select-sm answer-zone-select" data-item-id="<?= $item['item_id'] ?>">
                                <option value="0" <?= $mappedZone === 0 ? 'selected' : '' ?>>— Not linked —</option>
                                <?php foreach ($zones as $zone): ?>
                                <option value="<?= $zone['zone_id'] ?>" <?= $mappedZone === (int)$zone['zone_id'] ? 'selected' : '' ?>>
                                    <?= esc($zone['zone_label']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="text-center">
                            <span class="ak-status-badge">
                            <?php if ($mappedZone > 0): ?>
                                <span class="badge badge-light-success fs-9">Linked</span>
                            <?php else: ?>
                                <span class="badge badge-light-danger fs-9">Unlinked</span>
                            <?php endif; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>

        </div>
    </div>
    <!--end::Answer key card-->

</div>
</div>

<!--begin::Edit Assessment Modal-->
<div class="modal fade" id="modal_edit_dd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Edit Assessment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-5">
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Assessment Name</label>
                    <input type="text" class="form-control form-control-sm" id="dd_edit_name" maxlength="260">
                </div>
                <div class="row g-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-7">Duration <span class="text-muted fw-normal">(minutes)</span></label>
                        <input type="number" class="form-control form-control-sm" id="dd_edit_duration" min="0" max="300" placeholder="0 = no limit">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-7">Status</label>
                        <select class="form-select form-select-sm" id="dd_edit_status">
                            <option value="Draft">Draft</option>
                            <option value="Published">Published</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_dd_edit">
                    <span class="indicator-label"><i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Save</span>
                    <span class="indicator-progress">Saving… <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Edit Assessment Modal-->

<script>
const DD_ITEM_BASE   = '<?= $ITEM_BASE ?>';
const DD_ZONE_BASE   = '<?= $ZONE_BASE ?>';
const DD_ANSWERS_URL = '<?= $ANSWERS_URL ?>';
const DD_UPDATE_URL  = '<?= $UPDATE_URL ?>';
const DD_IMG_BASE    = '<?= $IMG_BASE ?>';
const DD_PUBLISHED   = <?= $quiz['quizze_status'] === 'Published' ? 'true' : 'false' ?>;

// Disable all editing if Published
if (DD_PUBLISHED) {
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#btn_add_item, #btn_add_zone, #btn_save_answers, #btn_edit_dd, .btn-del-item, .btn-del-zone, .btn-edit-item, .btn-save-item, .btn-cancel-item, .btn-edit-zone, .btn-save-zone, .btn-cancel-zone, #new_item_text, #new_item_image, #new_zone_label').forEach(el => {
            el.disabled = true;
            if (el.tagName === 'BUTTON') el.title = 'Locked — assessment is Published';
            if (el.tagName === 'INPUT')  el.readOnly = true;
        });
        document.querySelectorAll('.answer-zone-select').forEach(s => s.disabled = true);
    });
}

// ── Toast helper ──────────────────────────────────────────────────────────────
const DDToast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 2800, timerProgressBar: true,
    didOpen: t => {
        t.addEventListener('mouseenter', Swal.stopTimer);
        t.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

function updateStats() {
    const iCount = document.querySelectorAll('.item-row').length;
    const zCount = document.querySelectorAll('.zone-row').length;
    const lCount = document.querySelectorAll('.answer-zone-select').length
        ? [...document.querySelectorAll('.answer-zone-select')].filter(s => s.value !== '0').length
        : <?= $linkedCount ?>;

    document.getElementById('stat_items').textContent  = iCount;
    document.getElementById('stat_zones').textContent  = zCount;
    document.getElementById('stat_linked').textContent = lCount + '/' + iCount;
    document.getElementById('stat_linked').className   = 'fw-bold fs-4 ' + (lCount === iCount && iCount > 0 ? 'text-success' : 'text-warning');
    document.getElementById('items_count_badge').textContent = iCount;
    document.getElementById('zones_count_badge').textContent = zCount;
    document.getElementById('linked_badge').textContent = lCount + '/' + iCount + ' linked';
}

// ── HTML builders ─────────────────────────────────────────────────────────────
function buildThumbHtml(imageName, size) {
    return imageName
        ? `<img src="${DD_IMG_BASE}${imageName}" class="rounded-2" style="width:${size}px;height:${size}px;object-fit:cover;" alt="">`
        : `<div class="d-flex align-items-center justify-content-center bg-light-primary rounded-2" style="width:${size}px;height:${size}px;"><i class="ki-duotone ki-abstract-26 fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i></div>`;
}

function buildItemRowHtml(item) {
    const thumb = buildThumbHtml(item.item_image, 40);
    return `<div class="d-flex align-items-center gap-3 p-3 bg-light rounded-2 item-row"
             id="item_row_${item.item_id}"
             data-item-text="${escHtml(item.item_text)}"
             data-item-image="${item.item_image || ''}">
        <div class="item-thumb-wrap flex-shrink-0" style="width:40px;height:40px;">${thumb}</div>
        <span class="fw-semibold text-gray-800 fs-7 flex-grow-1 item-view-text">${escHtml(item.item_text)}</span>
        <div class="flex-grow-1 item-edit-form" style="display:none;">
            <div class="d-flex flex-column gap-2">
                <input type="text" class="form-control form-control-sm item-edit-text-input" value="${escHtml(item.item_text)}" maxlength="500">
                <input type="file" class="form-control form-control-sm item-edit-image-input" accept=".jpg,.jpeg,.png,.gif,.webp">
            </div>
        </div>
        <div class="d-flex gap-1 flex-shrink-0 item-view-btns">
            <button type="button" class="btn btn-icon btn-sm btn-light-primary btn-edit-item" data-item-id="${item.item_id}">
                <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-del-item" data-item-id="${item.item_id}">
                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
            </button>
        </div>
        <div class="d-flex gap-1 flex-shrink-0 item-edit-btns" style="display:none;">
            <button type="button" class="btn btn-icon btn-sm btn-primary btn-save-item" data-item-id="${item.item_id}">
                <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-light btn-cancel-item" data-item-id="${item.item_id}">
                <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
            </button>
        </div>
    </div>`;
}

function buildZoneRowHtml(zone) {
    return `<div class="d-flex align-items-center gap-3 p-3 bg-light-success rounded-2 zone-row"
             id="zone_row_${zone.zone_id}"
             data-zone-label="${escHtml(zone.zone_label)}">
        <i class="ki-duotone ki-drop fs-4 text-success flex-shrink-0"><span class="path1"></span><span class="path2"></span></i>
        <span class="fw-semibold text-gray-800 fs-7 flex-grow-1 zone-view-label">${escHtml(zone.zone_label)}</span>
        <input type="text" class="form-control form-control-sm flex-grow-1 zone-edit-input"
               value="${escHtml(zone.zone_label)}" maxlength="500" style="display:none;">
        <div class="d-flex gap-1 flex-shrink-0 zone-view-btns">
            <button type="button" class="btn btn-icon btn-sm btn-light-success btn-edit-zone" data-zone-id="${zone.zone_id}">
                <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-del-zone" data-zone-id="${zone.zone_id}">
                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
            </button>
        </div>
        <div class="d-flex gap-1 flex-shrink-0 zone-edit-btns" style="display:none;">
            <button type="button" class="btn btn-icon btn-sm btn-success btn-save-zone" data-zone-id="${zone.zone_id}">
                <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-light btn-cancel-zone" data-zone-id="${zone.zone_id}">
                <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
            </button>
        </div>
    </div>`;
}

// ── ADD ITEM ──────────────────────────────────────────────────────────────────
document.getElementById('btn_add_item').addEventListener('click', function() {
    const btn  = this;
    const text = document.getElementById('new_item_text').value.trim();
    if (!text) { document.getElementById('new_item_text').classList.add('is-invalid'); return; }
    document.getElementById('new_item_text').classList.remove('is-invalid');

    const fd = new FormData();
    fd.append('item_text', text);
    const imgFile = document.getElementById('new_item_image').files[0];
    if (imgFile) fd.append('item_image', imgFile);

    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;
    $.ajax({
        url: DD_ITEM_BASE + '/store', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (!res.success) { DDToast.fire({ icon: 'error', title: res.message }); return; }
            document.getElementById('items_empty')?.remove();
            document.getElementById('items_list').insertAdjacentHTML('beforeend', buildItemRowHtml(res.item));
            addAnswerKeyRow(res.item);
            document.getElementById('new_item_text').value  = '';
            document.getElementById('new_item_image').value = '';
            updateStats();
            DDToast.fire({ icon: 'success', title: 'Item added' });
        },
        error: function() { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; DDToast.fire({ icon: 'error', title: 'Failed' }); }
    });
});

function addAnswerKeyRow(item) {
    document.getElementById('answer_key_empty_state').style.display = 'none';
    document.getElementById('answer_key_table_wrap').style.display  = '';

    const rowCount = document.querySelectorAll('#answer_key_tbody tr').length + 1;
    const zones    = [...document.querySelectorAll('.zone-row')].map(z => ({
        id:    z.id.replace('zone_row_', ''),
        label: z.querySelector('.zone-view-label').textContent.trim()
    }));
    const zoneOpts = zones.map(z => `<option value="${z.id}">${escHtml(z.label)}</option>`).join('');
    const thumb    = item.item_image
        ? `<img src="${DD_IMG_BASE}${item.item_image}" class="rounded-2" style="width:36px;height:36px;object-fit:cover;" alt="">`
        : `<span class="text-muted fs-9">—</span>`;

    document.getElementById('answer_key_tbody').insertAdjacentHTML('beforeend',
        `<tr id="akrow_${item.item_id}">
            <td class="ps-4 text-muted fs-8">${rowCount}</td>
            <td class="ak-thumb-cell">${thumb}</td>
            <td><span class="fw-semibold text-gray-800 ak-item-name">${escHtml(item.item_text)}</span></td>
            <td><select class="form-select form-select-sm answer-zone-select" data-item-id="${item.item_id}">
                <option value="0" selected>— Not linked —</option>${zoneOpts}
            </select></td>
            <td class="text-center"><span class="ak-status-badge"><span class="badge badge-light-danger fs-9">Unlinked</span></span></td>
        </tr>`
    );
}

// ── EDIT ITEM (inline) ────────────────────────────────────────────────────────
$(document).on('click', '.btn-edit-item', function() {
    const itemId = $(this).data('item-id');
    const row    = $('#item_row_' + itemId);
    row.find('.item-view-text').hide();
    row.find('.item-edit-form').show();
    row.find('.item-view-btns').hide();
    row.find('.item-edit-btns').show();
    row.find('.item-edit-text-input').focus().select();
});

$(document).on('click', '.btn-cancel-item', function() {
    const itemId = $(this).data('item-id');
    const row    = $('#item_row_' + itemId);
    row.find('.item-edit-text-input').val(row.attr('data-item-text')).removeClass('is-invalid');
    row.find('.item-edit-image-input').val('');
    row.find('.item-view-text').show();
    row.find('.item-edit-form').hide();
    row.find('.item-view-btns').show();
    row.find('.item-edit-btns').hide();
});

$(document).on('click', '.btn-save-item', function() {
    const btn    = $(this);
    const itemId = btn.data('item-id');
    const row    = $('#item_row_' + itemId);
    const text   = row.find('.item-edit-text-input').val().trim();

    if (!text) {
        row.find('.item-edit-text-input').addClass('is-invalid');
        return;
    }
    row.find('.item-edit-text-input').removeClass('is-invalid');

    const fd      = new FormData();
    fd.append('item_text', text);
    const imgFile = row.find('.item-edit-image-input')[0].files[0];
    if (imgFile) fd.append('item_image', imgFile);

    btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

    $.ajax({
        url: DD_ITEM_BASE + '/' + itemId + '/update',
        type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.html('<i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>').prop('disabled', false);
            if (!res.success) { DDToast.fire({ icon: 'error', title: res.message }); return; }

            const item = res.item;
            // Update row data attributes
            row.attr('data-item-text',  item.item_text);
            row.attr('data-item-image', item.item_image || '');
            // Update display text
            row.find('.item-view-text').text(item.item_text);
            row.find('.item-edit-text-input').val(item.item_text);
            // Update thumb if image changed
            if (item.item_image) {
                row.find('.item-thumb-wrap').html(buildThumbHtml(item.item_image, 40));
            }
            // Update answer key name + thumb
            const akRow = $('#akrow_' + itemId);
            if (akRow.length) {
                akRow.find('.ak-item-name').text(item.item_text);
                if (item.item_image) {
                    akRow.find('.ak-thumb-cell').html(
                        `<img src="${DD_IMG_BASE}${item.item_image}" class="rounded-2" style="width:36px;height:36px;object-fit:cover;" alt="">`
                    );
                }
            }
            // Return to view mode
            row.find('.item-view-text').show();
            row.find('.item-edit-form').hide();
            row.find('.item-view-btns').show();
            row.find('.item-edit-btns').hide();
            row.find('.item-edit-image-input').val('');
            DDToast.fire({ icon: 'success', title: 'Item updated' });
        },
        error: function() {
            btn.html('<i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>').prop('disabled', false);
            DDToast.fire({ icon: 'error', title: 'Failed to update' });
        }
    });
});

// Allow Enter key in item edit input
$(document).on('keydown', '.item-edit-text-input', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); $(this).closest('.item-row').find('.btn-save-item').click(); }
    if (e.key === 'Escape') { $(this).closest('.item-row').find('.btn-cancel-item').click(); }
});

// ── DELETE ITEM ───────────────────────────────────────────────────────────────
$(document).on('click', '.btn-del-item', function() {
    const itemId = $(this).data('item-id');
    Swal.fire({
        title: 'Remove this item?', text: 'Its answer mapping will also be removed.',
        icon: 'warning', showCancelButton: true, buttonsStyling: false,
        confirmButtonText: 'Remove', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(DD_ITEM_BASE + '/' + itemId + '/delete', function(res) {
            if (res.success) {
                document.getElementById('item_row_' + itemId)?.remove();
                document.getElementById('akrow_' + itemId)?.remove();
                updateStats();
                if (!document.querySelector('.item-row')) {
                    document.getElementById('items_list').innerHTML = `<div class="text-center py-6 text-muted" id="items_empty">
                        <i class="ki-duotone ki-cursor fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fs-8">No items yet. Add your first drag item below.</div></div>`;
                    document.getElementById('answer_key_empty_state').style.display = '';
                    document.getElementById('answer_key_table_wrap').style.display  = 'none';
                }
                DDToast.fire({ icon: 'success', title: 'Item removed' });
            } else {
                DDToast.fire({ icon: 'error', title: res.message });
            }
        }, 'json');
    });
});

// ── ADD ZONE ──────────────────────────────────────────────────────────────────
document.getElementById('btn_add_zone').addEventListener('click', function() {
    const btn   = this;
    const label = document.getElementById('new_zone_label').value.trim();
    if (!label) { document.getElementById('new_zone_label').classList.add('is-invalid'); return; }
    document.getElementById('new_zone_label').classList.remove('is-invalid');

    const fd = new FormData();
    fd.append('zone_label', label);
    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;

    $.ajax({
        url: DD_ZONE_BASE + '/store', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (!res.success) { DDToast.fire({ icon: 'error', title: res.message }); return; }
            document.getElementById('zones_empty')?.remove();
            document.getElementById('zones_list').insertAdjacentHTML('beforeend', buildZoneRowHtml(res.zone));
            // Add to all zone selects in answer key
            const opt = `<option value="${res.zone.zone_id}">${escHtml(res.zone.zone_label)}</option>`;
            document.querySelectorAll('.answer-zone-select').forEach(sel => sel.insertAdjacentHTML('beforeend', opt));
            document.getElementById('new_zone_label').value = '';
            updateStats();
            DDToast.fire({ icon: 'success', title: 'Zone added' });
        },
        error: function() { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; DDToast.fire({ icon: 'error', title: 'Failed' }); }
    });
});

// ── EDIT ZONE (inline) ────────────────────────────────────────────────────────
$(document).on('click', '.btn-edit-zone', function() {
    const zoneId = $(this).data('zone-id');
    const row    = $('#zone_row_' + zoneId);
    row.find('.zone-view-label').hide();
    row.find('.zone-edit-input').show().focus().select();
    row.find('.zone-view-btns').hide();
    row.find('.zone-edit-btns').show();
});

$(document).on('click', '.btn-cancel-zone', function() {
    const zoneId = $(this).data('zone-id');
    const row    = $('#zone_row_' + zoneId);
    row.find('.zone-edit-input').val(row.attr('data-zone-label')).removeClass('is-invalid').hide();
    row.find('.zone-view-label').show();
    row.find('.zone-view-btns').show();
    row.find('.zone-edit-btns').hide();
});

$(document).on('click', '.btn-save-zone', function() {
    const btn    = $(this);
    const zoneId = btn.data('zone-id');
    const row    = $('#zone_row_' + zoneId);
    const label  = row.find('.zone-edit-input').val().trim();

    if (!label) {
        row.find('.zone-edit-input').addClass('is-invalid');
        return;
    }
    row.find('.zone-edit-input').removeClass('is-invalid');

    btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

    const fd = new FormData();
    fd.append('zone_label', label);

    $.ajax({
        url: DD_ZONE_BASE + '/' + zoneId + '/update',
        type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.html('<i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>').prop('disabled', false);
            if (!res.success) { DDToast.fire({ icon: 'error', title: res.message }); return; }

            const zone = res.zone;
            // Update row
            row.attr('data-zone-label', zone.zone_label);
            row.find('.zone-view-label').text(zone.zone_label);
            row.find('.zone-edit-input').val(zone.zone_label);
            // Update all matching options in answer key selects
            document.querySelectorAll(`.answer-zone-select option[value="${zoneId}"]`).forEach(o => {
                o.textContent = zone.zone_label;
            });
            // Return to view mode
            row.find('.zone-edit-input').hide();
            row.find('.zone-view-label').show();
            row.find('.zone-view-btns').show();
            row.find('.zone-edit-btns').hide();
            DDToast.fire({ icon: 'success', title: 'Zone updated' });
        },
        error: function() {
            btn.html('<i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>').prop('disabled', false);
            DDToast.fire({ icon: 'error', title: 'Failed to update' });
        }
    });
});

// Allow Enter/Escape in zone edit input
$(document).on('keydown', '.zone-edit-input', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); $(this).closest('.zone-row').find('.btn-save-zone').click(); }
    if (e.key === 'Escape') { $(this).closest('.zone-row').find('.btn-cancel-zone').click(); }
});

// ── DELETE ZONE ───────────────────────────────────────────────────────────────
$(document).on('click', '.btn-del-zone', function() {
    const zoneId = $(this).data('zone-id');
    Swal.fire({
        title: 'Remove this zone?', text: 'Items mapped to this zone will be unlinked.',
        icon: 'warning', showCancelButton: true, buttonsStyling: false,
        confirmButtonText: 'Remove', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(DD_ZONE_BASE + '/' + zoneId + '/delete', function(res) {
            if (res.success) {
                document.getElementById('zone_row_' + zoneId)?.remove();
                document.querySelectorAll(`.answer-zone-select option[value="${zoneId}"]`).forEach(o => {
                    if (o.selected) {
                        o.closest('select').value = '0';
                        const badge = o.closest('tr')?.querySelector('.ak-status-badge');
                        if (badge) badge.innerHTML = '<span class="badge badge-light-danger fs-9">Unlinked</span>';
                    }
                    o.remove();
                });
                if (!document.querySelector('.zone-row')) {
                    document.getElementById('zones_list').innerHTML = `<div class="text-center py-6 text-muted" id="zones_empty">
                        <i class="ki-duotone ki-drop fs-3x text-gray-200 mb-2"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fs-8">No zones yet. Add your first drop zone below.</div></div>`;
                }
                updateStats();
                DDToast.fire({ icon: 'success', title: 'Zone removed' });
            } else {
                DDToast.fire({ icon: 'error', title: res.message });
            }
        }, 'json');
    });
});

// ── ANSWER KEY — live status update ──────────────────────────────────────────
$(document).on('change', '.answer-zone-select', function() {
    const badge = $(this).closest('tr').find('.ak-status-badge');
    if (this.value !== '0') {
        badge.html('<span class="badge badge-light-warning fs-9">Unsaved</span>');
    } else {
        badge.html('<span class="badge badge-light-danger fs-9">Unlinked</span>');
    }
    updateStats();
});

// ── SAVE ANSWER KEY ───────────────────────────────────────────────────────────
document.getElementById('btn_save_answers').addEventListener('click', function() {
    const btn      = this;
    const mappings = [];
    document.querySelectorAll('.answer-zone-select').forEach(sel => {
        mappings.push({ item_id: sel.dataset.itemId, zone_id: sel.value });
    });

    if (mappings.length === 0) {
        DDToast.fire({ icon: 'warning', title: 'No items to map' });
        return;
    }

    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;
    $.ajax({
        url: DD_ANSWERS_URL, type: 'POST',
        data: { mappings: mappings },
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                // Update all badges
                document.querySelectorAll('.answer-zone-select').forEach(sel => {
                    const badge = sel.closest('tr')?.querySelector('.ak-status-badge');
                    if (!badge) return;
                    if (sel.value !== '0') {
                        badge.innerHTML = '<span class="badge badge-light-success fs-9">Linked</span>';
                    } else {
                        badge.innerHTML = '<span class="badge badge-light-danger fs-9">Unlinked</span>';
                    }
                });
                updateStats();
                DDToast.fire({ icon: 'success', title: res.message });
            } else {
                DDToast.fire({ icon: 'error', title: res.message || 'Failed to save' });
            }
        },
        error: function() { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; DDToast.fire({ icon: 'error', title: 'Failed' }); }
    });
});

// ── EDIT ASSESSMENT ───────────────────────────────────────────────────────────
document.getElementById('btn_edit_dd').addEventListener('click', function() {
    document.getElementById('dd_edit_name').value     = <?= json_encode($quiz['quizze_name']) ?>;
    document.getElementById('dd_edit_duration').value = <?= (int)$quiz['quizze_duration'] ?>;
    document.getElementById('dd_edit_status').value   = <?= json_encode($quiz['quizze_status']) ?>;
    new bootstrap.Modal(document.getElementById('modal_edit_dd')).show();
});

document.getElementById('btn_save_dd_edit').addEventListener('click', function() {
    const btn  = this;
    const name = document.getElementById('dd_edit_name').value.trim();
    if (!name) { document.getElementById('dd_edit_name').classList.add('is-invalid'); return; }
    document.getElementById('dd_edit_name').classList.remove('is-invalid');
    btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true;

    const fd = new FormData();
    fd.append('quizze_name',     name);
    fd.append('quizze_duration', document.getElementById('dd_edit_duration').value);
    fd.append('quizze_status',   document.getElementById('dd_edit_status').value);

    $.ajax({
        url: DD_UPDATE_URL, type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_edit_dd')).hide();
                document.getElementById('dd_title_display').textContent = res.quiz.quizze_name;
                document.querySelector('h1.page-heading').textContent   = res.quiz.quizze_name;
                const sc = res.quiz.quizze_status === 'Published' ? 'success' : 'warning';
                const badge = document.getElementById('dd_status_badge');
                badge.textContent = res.quiz.quizze_status;
                badge.className   = 'badge badge-light-' + sc + ' fs-9';
                DDToast.fire({ icon: 'success', title: 'Assessment updated' });
            } else {
                DDToast.fire({ icon: 'error', title: res.message });
            }
        }
    });
});

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Allow Enter key in add-item / add-zone inputs
document.getElementById('new_item_text').addEventListener('keydown', e => { if (e.key === 'Enter') document.getElementById('btn_add_item').click(); });
document.getElementById('new_zone_label').addEventListener('keydown', e => { if (e.key === 'Enter') document.getElementById('btn_add_zone').click(); });
</script>

<style>
.item-row, .zone-row { transition: box-shadow .15s; }
.item-row:hover, .zone-row:hover { box-shadow: 0 2px 8px rgba(0,0,0,.08); }
</style>
