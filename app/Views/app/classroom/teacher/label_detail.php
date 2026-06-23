<?php
$statusColor  = $quiz['quizze_status'] === 'Published' ? 'success' : 'warning';
$qCount       = count($questions);
$totalMarkers = array_sum(array_map(fn($q) => count($q['markers']), $questions));
$BACK_URL     = base_url('classroom/teacher/' . $schSubId . '/lesson/' . $lesson['lesson_id']);
$Q_BASE       = base_url('classroom/lesson/' . $lesson['lesson_id'] . '/label/' . $quiz['lesson_quizze_id'] . '/question');
$UPDATE_URL   = base_url('classroom/lesson/' . $lesson['lesson_id'] . '/quiz/' . $quiz['lesson_quizze_id'] . '/update');
$IMG_BASE     = base_url('uploads/label_images/');
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
                <li class="breadcrumb-item text-muted">Labelling Builder</li>
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

    <!--begin::Header card-->
    <div class="card border-0 shadow-sm mb-6">
        <div class="card-body p-5">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center justify-content-center bg-light-info rounded-2 flex-shrink-0" style="width:52px;height:52px;">
                        <i class="ki-duotone ki-tag fs-2x text-info"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <div>
                        <div class="fw-bold text-gray-900 fs-5 mb-1" id="lbl_title_display"><?= esc($quiz['quizze_name']) ?></div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-light-info fs-9">Labelling</span>
                            <span class="badge badge-light-<?= $statusColor ?> fs-9" id="lbl_status_badge"><?= esc($quiz['quizze_status']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-center px-4">
                        <div class="fw-bold text-gray-800 fs-4" id="stat_exercises"><?= $qCount ?></div>
                        <div class="text-muted fs-9">Exercises</div>
                    </div>
                    <div class="text-center px-4" style="border-left:1px solid #f1f1f4;">
                        <div class="fw-bold text-gray-800 fs-4" id="stat_markers"><?= $totalMarkers ?></div>
                        <div class="text-muted fs-9">Markers</div>
                    </div>
                    <button type="button" class="btn btn-sm btn-light ms-2" id="btn_edit_lbl" title="Edit name / status">
                        <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Header card-->

    <div class="alert alert-info d-flex align-items-center gap-3 py-3 mb-5">
        <i class="ki-duotone ki-information-5 fs-3 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <span class="fs-8">Add exercises below — each exercise has an image and clickable marker positions. <strong>Click anywhere on the image</strong> to place a numbered marker, then enter its correct label. Students will type the label for each numbered point.</span>
    </div>

    <!--begin::Exercises list-->
    <div id="exercises_list" class="d-flex flex-column gap-5">

    <?php foreach ($questions as $qi => $q): ?>
    <?php
    $qId      = (int) $q['label_question_id'];
    $mCount   = count($q['markers']);
    $MARKER_BASE = $Q_BASE . '/' . $qId . '/marker';
    ?>
    <div class="card border-0 shadow-sm exercise-card" id="exercise_card_<?= $qId ?>">
        <div class="card-header border-0 pt-4 pb-3">
            <div class="d-flex align-items-center gap-3 flex-grow-1">
                <span class="badge badge-light-info fs-9 flex-shrink-0">Exercise <?= $qi + 1 ?></span>
                <span class="fw-bold text-gray-800 fs-7 exercise-title-display"><?= esc($q['question_text'] ?: 'Untitled exercise') ?></span>
                <input type="text" class="form-control form-control-sm exercise-title-input flex-grow-1" style="display:none;max-width:400px;"
                       value="<?= esc($q['question_text'], 'attr') ?>" maxlength="500" data-qid="<?= $qId ?>">
                <div class="d-flex gap-1 exercise-title-view-btns">
                    <button type="button" class="btn btn-icon btn-xs btn-light-primary btn-edit-exercise-title" data-qid="<?= $qId ?>">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
                <div class="d-flex gap-1 exercise-title-edit-btns" style="display:none;">
                    <button type="button" class="btn btn-icon btn-xs btn-primary btn-save-exercise-title" data-qid="<?= $qId ?>">
                        <i class="ki-duotone ki-check fs-6"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-xs btn-light btn-cancel-exercise-title" data-qid="<?= $qId ?>">
                        <i class="ki-duotone ki-cross fs-6"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
                <span class="badge badge-light-secondary fs-9 ms-1 marker-count-badge" id="mcb_<?= $qId ?>"><?= $mCount ?> marker<?= $mCount !== 1 ? 's' : '' ?></span>
            </div>
            <div class="card-toolbar d-flex gap-2">
                <?php if (empty($q['bg_image'])): ?>
                <label class="btn btn-sm btn-light-info mb-0">
                    <i class="ki-duotone ki-picture fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Upload Image
                    <input type="file" class="exercise-img-upload" style="display:none;" accept=".jpg,.jpeg,.png,.gif,.webp" data-qid="<?= $qId ?>">
                </label>
                <?php else: ?>
                <label class="btn btn-sm btn-light mb-0" title="Replace image">
                    <i class="ki-duotone ki-arrows-circle fs-5"><span class="path1"></span><span class="path2"></span></i>
                    <input type="file" class="exercise-img-upload" style="display:none;" accept=".jpg,.jpeg,.png,.gif,.webp" data-qid="<?= $qId ?>">
                </label>
                <?php endif; ?>
                <button type="button" class="btn btn-sm btn-light-danger btn-del-exercise" data-qid="<?= $qId ?>">
                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                </button>
            </div>
        </div>

        <div class="card-body pt-0 pb-5">
            <?php if (!empty($q['bg_image'])): ?>
            <!--begin::Image with markers-->
            <div class="label-img-container position-relative d-inline-block" id="imgwrap_<?= $qId ?>"
                 style="cursor:crosshair;max-width:100%;user-select:none;">
                <img src="<?= $IMG_BASE . esc($q['bg_image']) ?>"
                     id="exercise_img_<?= $qId ?>"
                     class="rounded-2 d-block exercise-bg-img"
                     style="max-width:100%;max-height:500px;object-fit:contain;"
                     data-qid="<?= $qId ?>"
                     alt="">
                <?php foreach ($q['markers'] as $mi => $m): ?>
                <div class="lbl-marker-dot" id="marker_<?= $m['marker_id'] ?>"
                     style="left:<?= $m['marker_x'] ?>%;top:<?= $m['marker_y'] ?>%;"
                     data-marker-id="<?= $m['marker_id'] ?>"
                     data-qid="<?= $qId ?>"
                     data-label="<?= esc($m['correct_label'], 'attr') ?>"
                     title="<?= $mi+1 ?>. <?= esc($m['correct_label']) ?>"
                     onclick="openEditMarker(<?= $m['marker_id'] ?>, <?= $qId ?>, '<?= esc($m['correct_label'], 'attr') ?>')">
                    <?= $mi + 1 ?>
                </div>
                <?php endforeach; ?>
                <div class="position-absolute bottom-0 start-0 m-2">
                    <span class="badge bg-dark bg-opacity-50 text-white fs-9">Click image to add markers</span>
                </div>
            </div>
            <!--end::Image with markers-->

            <!--begin::Marker list-->
            <?php if (!empty($q['markers'])): ?>
            <div class="mt-4" id="marker_list_<?= $qId ?>">
                <div class="fw-semibold text-gray-700 fs-8 mb-3">Markers</div>
                <div class="d-flex flex-column gap-2">
                <?php foreach ($q['markers'] as $mi => $m): ?>
                <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-2" id="mrow_<?= $m['marker_id'] ?>">
                    <div class="lbl-marker-dot-sm flex-shrink-0"><?= $mi + 1 ?></div>
                    <span class="fw-semibold text-gray-800 fs-8 flex-grow-1 marker-label-text"><?= esc($m['correct_label']) ?></span>
                    <span class="text-muted fs-9"><?= $m['marker_x'] ?>%, <?= $m['marker_y'] ?>%</span>
                    <button type="button" class="btn btn-icon btn-xs btn-light-primary btn-edit-marker-row"
                            data-marker-id="<?= $m['marker_id'] ?>" data-qid="<?= $qId ?>"
                            data-label="<?= esc($m['correct_label'], 'attr') ?>">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-xs btn-light-danger btn-del-marker"
                            data-marker-id="<?= $m['marker_id'] ?>" data-qid="<?= $qId ?>">
                        <i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                </div>
                <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <!--end::Marker list-->

            <?php else: ?>
            <!--begin::No image state-->
            <div class="text-center py-10 text-muted border border-dashed border-gray-300 rounded-2">
                <i class="ki-duotone ki-picture fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
                <div class="fs-7 fw-semibold mb-2">No image uploaded yet</div>
                <div class="fs-8 mb-4">Upload an image to place marker labels on it</div>
                <label class="btn btn-sm btn-light-info mb-0">
                    <i class="ki-duotone ki-picture fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Upload Image
                    <input type="file" class="exercise-img-upload" style="display:none;" accept=".jpg,.jpeg,.png,.gif,.webp" data-qid="<?= $qId ?>">
                </label>
            </div>
            <!--end::No image state-->
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($questions)): ?>
    <div class="text-center py-12 text-muted" id="exercises_empty">
        <i class="ki-duotone ki-tag fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-6 fw-semibold mb-1">No exercises yet</div>
        <div class="fs-8">Add your first labelling exercise below.</div>
    </div>
    <?php endif; ?>

    </div>
    <!--end::Exercises list-->

    <!--begin::Add Exercise card-->
    <div class="card border-0 shadow-sm mt-5" style="border:1.5px dashed #b5cce8 !important;">
        <div class="card-header border-0 pt-4 pb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-plus-circle fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                <h6 class="fw-bold text-gray-800 mb-0">Add New Exercise</h6>
            </div>
        </div>
        <div class="card-body pt-0 pb-5">
            <div class="row g-4 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold fs-8 mb-1">Exercise Title <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="text" class="form-control form-control-sm" id="new_exercise_title"
                           placeholder="e.g. Label the parts of a plant cell" maxlength="500">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold fs-8 mb-1">Background Image <span class="text-muted fw-normal">(optional — add later)</span></label>
                    <input type="file" class="form-control form-control-sm" id="new_exercise_image" accept=".jpg,.jpeg,.png,.gif,.webp">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm w-100" id="btn_add_exercise">
                        <span class="indicator-label"><i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Add Exercise</span>
                        <span class="indicator-progress">Adding… <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Add Exercise card-->

</div>
</div>

<!--begin::Edit Assessment Modal-->
<div class="modal fade" id="modal_edit_lbl" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Edit Assessment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-5">
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Assessment Name</label>
                    <input type="text" class="form-control form-control-sm" id="lbl_edit_name" maxlength="260">
                </div>
                <div class="row g-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-7">Duration <span class="text-muted fw-normal">(minutes)</span></label>
                        <input type="number" class="form-control form-control-sm" id="lbl_edit_duration" min="0" max="300" placeholder="0 = no limit">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-7">Status</label>
                        <select class="form-select form-select-sm" id="lbl_edit_status">
                            <option value="Draft">Draft</option>
                            <option value="Published">Published</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_lbl_edit">
                    <span class="indicator-label"><i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Save</span>
                    <span class="indicator-progress">Saving… <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Edit Assessment Modal-->

<!--begin::Add/Edit Marker Modal-->
<div class="modal fade" id="modal_marker" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-6 mb-0" id="marker_modal_title">Add Marker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <input type="hidden" id="marker_modal_qid">
                <input type="hidden" id="marker_modal_x">
                <input type="hidden" id="marker_modal_y">
                <input type="hidden" id="marker_modal_marker_id">
                <div class="mb-3">
                    <label class="form-label fw-semibold fs-7 required">Correct Label</label>
                    <input type="text" class="form-control form-control-sm" id="marker_modal_label"
                           placeholder="e.g. Nucleus, Mitochondria…" maxlength="300">
                    <div class="text-muted fs-9 mt-1" id="marker_modal_pos_hint"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info btn-sm" id="btn_save_marker">
                    <span class="indicator-label"><i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Save</span>
                    <span class="indicator-progress">Saving… <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add/Edit Marker Modal-->

<script>
const Q_BASE        = '<?= $Q_BASE ?>';
const LBL_UPDATE    = '<?= $UPDATE_URL ?>';
const IMG_BASE      = '<?= $IMG_BASE ?>';
const LBL_PUBLISHED = <?= $quiz['quizze_status'] === 'Published' ? 'true' : 'false' ?>;

// Disable all editing controls if Published
if (LBL_PUBLISHED) {
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#btn_add_exercise, #new_exercise_title, #new_exercise_image, #btn_edit_lbl, .btn-del-exercise, .btn-edit-exercise-title, .btn-save-exercise-title, .btn-cancel-exercise-title, .exercise-img-upload, .exercise-title-input').forEach(el => {
            el.disabled = true;
            if (el.tagName === 'BUTTON') el.title = 'Locked — assessment is Published';
            if (el.tagName === 'INPUT')  el.readOnly = true;
        });
        // Prevent image click from opening add-marker modal
        document.querySelectorAll('.exercise-bg-img').forEach(img => {
            img.style.cursor = 'default';
            img.onclick = function(e) { e.stopPropagation(); };
        });
    });
}

// ── Toast ─────────────────────────────────────────────────────────────────────
const LblToast = Swal.mixin({
    toast:true, position:'top-end', showConfirmButton:false, timer:2800, timerProgressBar:true,
    didOpen: t => { t.addEventListener('mouseenter', Swal.stopTimer); t.addEventListener('mouseleave', Swal.resumeTimer); }
});

function escHtml(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

function updateStats() {
    document.getElementById('stat_exercises').textContent = document.querySelectorAll('.exercise-card').length;
    let total = 0;
    document.querySelectorAll('.marker-count-badge').forEach(b => {
        total += parseInt(b.textContent) || 0;
    });
    document.getElementById('stat_markers').textContent = total;
}

// ── IMAGE CLICK → Add Marker ──────────────────────────────────────────────────
$(document).on('click', '.exercise-bg-img', function(e) {
    const rect = this.getBoundingClientRect();
    const x    = ((e.clientX - rect.left) / rect.width  * 100).toFixed(2);
    const y    = ((e.clientY - rect.top)  / rect.height * 100).toFixed(2);
    const qId  = $(this).data('qid');

    document.getElementById('marker_modal_title').textContent   = 'Add Marker';
    document.getElementById('marker_modal_qid').value           = qId;
    document.getElementById('marker_modal_x').value             = x;
    document.getElementById('marker_modal_y').value             = y;
    document.getElementById('marker_modal_marker_id').value     = '';
    document.getElementById('marker_modal_label').value         = '';
    document.getElementById('marker_modal_pos_hint').textContent= 'Position: ' + x + '%, ' + y + '%';
    new bootstrap.Modal(document.getElementById('modal_marker')).show();
    setTimeout(() => document.getElementById('marker_modal_label').focus(), 300);
});

function openEditMarker(markerId, qId, label) {
    event.stopPropagation();
    document.getElementById('marker_modal_title').textContent    = 'Edit Marker';
    document.getElementById('marker_modal_qid').value            = qId;
    document.getElementById('marker_modal_marker_id').value      = markerId;
    document.getElementById('marker_modal_label').value          = label;
    document.getElementById('marker_modal_pos_hint').textContent = '';
    new bootstrap.Modal(document.getElementById('modal_marker')).show();
    setTimeout(() => document.getElementById('marker_modal_label').focus(), 300);
}
$(document).on('click', '.btn-edit-marker-row', function() {
    openEditMarker($(this).data('marker-id'), $(this).data('qid'), $(this).data('label'));
});
document.getElementById('marker_modal_label').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); document.getElementById('btn_save_marker').click(); }
});

// ── Save Marker (add or edit) ─────────────────────────────────────────────────
document.getElementById('btn_save_marker').addEventListener('click', function() {
    const btn      = this;
    const label    = document.getElementById('marker_modal_label').value.trim();
    const qId      = document.getElementById('marker_modal_qid').value;
    const markerId = document.getElementById('marker_modal_marker_id').value;
    const x        = document.getElementById('marker_modal_x').value;
    const y        = document.getElementById('marker_modal_y').value;

    if (!label) { document.getElementById('marker_modal_label').classList.add('is-invalid'); return; }
    document.getElementById('marker_modal_label').classList.remove('is-invalid');
    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;

    const fd = new FormData();
    fd.append('correct_label', label);

    if (markerId) {
        // Update existing
        $.ajax({
            url: Q_BASE + '/' + qId + '/marker/' + markerId + '/update',
            type:'POST', data:fd, processData:false, contentType:false,
            success: function(res) {
                btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
                if (!res.success) { LblToast.fire({icon:'error',title:res.message}); return; }
                bootstrap.Modal.getInstance(document.getElementById('modal_marker')).hide();
                // Update dot tooltip
                const dot = document.getElementById('marker_' + markerId);
                if (dot) { dot.title = dot.textContent.trim() + '. ' + label; dot.dataset.label = label; }
                // Update list row
                const mrow = document.getElementById('mrow_' + markerId);
                if (mrow) {
                    mrow.querySelector('.marker-label-text').textContent = label;
                    mrow.querySelector('.btn-edit-marker-row').dataset.label = label;
                }
                LblToast.fire({icon:'success', title:'Marker updated'});
            }
        });
    } else {
        // Add new
        fd.append('marker_x', x);
        fd.append('marker_y', y);
        $.ajax({
            url: Q_BASE + '/' + qId + '/marker/store',
            type:'POST', data:fd, processData:false, contentType:false,
            success: function(res) {
                btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
                if (!res.success) { LblToast.fire({icon:'error',title:res.message}); return; }
                bootstrap.Modal.getInstance(document.getElementById('modal_marker')).hide();
                const m = res.marker;

                // Add dot to image
                const imgWrap = document.getElementById('imgwrap_' + qId);
                if (imgWrap) {
                    const currentDots = imgWrap.querySelectorAll('.lbl-marker-dot').length + 1;
                    const dot = document.createElement('div');
                    dot.className      = 'lbl-marker-dot';
                    dot.id             = 'marker_' + m.marker_id;
                    dot.style.left     = m.marker_x + '%';
                    dot.style.top      = m.marker_y + '%';
                    dot.dataset.markerId = m.marker_id;
                    dot.dataset.qid    = qId;
                    dot.dataset.label  = m.correct_label;
                    dot.title          = currentDots + '. ' + m.correct_label;
                    dot.textContent    = currentDots;
                    dot.setAttribute('onclick', `openEditMarker(${m.marker_id}, ${qId}, '${escHtml(m.correct_label)}')`);
                    imgWrap.appendChild(dot);
                }

                // Add to marker list
                let listWrap = document.getElementById('marker_list_' + qId);
                if (!listWrap) {
                    const imgWrapEl = document.getElementById('imgwrap_' + qId);
                    listWrap = document.createElement('div');
                    listWrap.className = 'mt-4';
                    listWrap.id = 'marker_list_' + qId;
                    listWrap.innerHTML = '<div class="fw-semibold text-gray-700 fs-8 mb-3">Markers</div><div class="d-flex flex-column gap-2"></div>';
                    imgWrapEl?.parentElement?.insertBefore(listWrap, imgWrapEl.nextSibling?.nextSibling || null);
                    if (imgWrapEl?.parentElement) imgWrapEl.parentElement.appendChild(listWrap);
                }
                const listInner = listWrap.querySelector('.d-flex.flex-column');
                const rowCount  = listInner ? listInner.children.length + 1 : 1;
                if (listInner) {
                    listInner.insertAdjacentHTML('beforeend', `
                        <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-2" id="mrow_${m.marker_id}">
                            <div class="lbl-marker-dot-sm flex-shrink-0">${rowCount}</div>
                            <span class="fw-semibold text-gray-800 fs-8 flex-grow-1 marker-label-text">${escHtml(m.correct_label)}</span>
                            <span class="text-muted fs-9">${m.marker_x}%, ${m.marker_y}%</span>
                            <button type="button" class="btn btn-icon btn-xs btn-light-primary btn-edit-marker-row"
                                data-marker-id="${m.marker_id}" data-qid="${qId}" data-label="${escHtml(m.correct_label)}">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                            <button type="button" class="btn btn-icon btn-xs btn-light-danger btn-del-marker"
                                data-marker-id="${m.marker_id}" data-qid="${qId}">
                                <i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </button>
                        </div>`);
                }

                // Update badge
                const badge = document.getElementById('mcb_' + qId);
                if (badge) { const c = (parseInt(badge.textContent)||0)+1; badge.textContent = c + ' marker' + (c!==1?'s':''); }
                updateStats();
                LblToast.fire({icon:'success', title:'Marker added'});
            }
        });
    }
});

// ── Delete Marker ─────────────────────────────────────────────────────────────
$(document).on('click', '.btn-del-marker', function() {
    const markerId = $(this).data('marker-id');
    const qId      = $(this).data('qid');
    Swal.fire({
        title:'Remove marker?', icon:'warning', showCancelButton:true, buttonsStyling:false,
        confirmButtonText:'Remove', cancelButtonText:'Cancel',
        customClass:{confirmButton:'btn btn-danger me-2', cancelButton:'btn btn-light'}
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(Q_BASE + '/' + qId + '/marker/' + markerId + '/delete', function(res) {
            if (res.success) {
                document.getElementById('marker_' + markerId)?.remove();
                document.getElementById('mrow_' + markerId)?.remove();
                // Re-number remaining dots
                document.querySelectorAll('#imgwrap_' + qId + ' .lbl-marker-dot').forEach((d,i) => { d.textContent = i+1; });
                document.querySelectorAll('#marker_list_' + qId + ' .lbl-marker-dot-sm').forEach((d,i) => { d.textContent = i+1; });
                const badge = document.getElementById('mcb_' + qId);
                if (badge) { const c = Math.max(0,(parseInt(badge.textContent)||0)-1); badge.textContent = c+' marker'+(c!==1?'s':''); }
                updateStats();
                LblToast.fire({icon:'success', title:'Marker removed'});
            }
        }, 'json');
    });
});

// ── Edit exercise title ───────────────────────────────────────────────────────
$(document).on('click', '.btn-edit-exercise-title', function() {
    const card = $(this).closest('.exercise-card');
    card.find('.exercise-title-display').hide();
    card.find('.exercise-title-view-btns').hide();
    card.find('.exercise-title-input').show().focus().select();
    card.find('.exercise-title-edit-btns').show();
});
$(document).on('click', '.btn-cancel-exercise-title', function() {
    const card = $(this).closest('.exercise-card');
    const qId  = card.find('.exercise-title-input').data('qid');
    const orig = card.find('.exercise-title-display').text();
    card.find('.exercise-title-input').val(orig).hide();
    card.find('.exercise-title-display').show();
    card.find('.exercise-title-view-btns').show();
    card.find('.exercise-title-edit-btns').hide();
});
$(document).on('click', '.btn-save-exercise-title', function() {
    const btn  = $(this);
    const card = btn.closest('.exercise-card');
    const qId  = card.find('.exercise-title-input').data('qid');
    const text = card.find('.exercise-title-input').val().trim();
    const fd   = new FormData(); fd.append('question_text', text);
    $.ajax({
        url: Q_BASE + '/' + qId + '/update', type:'POST', data:fd, processData:false, contentType:false,
        success: function(res) {
            if (res.success) {
                card.find('.exercise-title-display').text(text || 'Untitled exercise').show();
                card.find('.exercise-title-input').hide();
                card.find('.exercise-title-view-btns').show();
                card.find('.exercise-title-edit-btns').hide();
                LblToast.fire({icon:'success', title:'Title updated'});
            }
        }
    });
});
$(document).on('keydown', '.exercise-title-input', function(e) {
    if (e.key === 'Enter') $(this).closest('.exercise-card').find('.btn-save-exercise-title').click();
    if (e.key === 'Escape') $(this).closest('.exercise-card').find('.btn-cancel-exercise-title').click();
});

// ── Image upload for existing exercise ───────────────────────────────────────
$(document).on('change', '.exercise-img-upload', function() {
    if (!this.files.length) return;
    const qId  = $(this).data('qid');
    const file = this.files[0];
    const fd   = new FormData(); fd.append('bg_image', file);
    LblToast.fire({icon:'info', title:'Uploading…', timer:99999});
    $.ajax({
        url: Q_BASE + '/' + qId + '/update', type:'POST', data:fd, processData:false, contentType:false,
        success: function(res) {
            Swal.close();
            if (res.success && res.question.bg_image) {
                LblToast.fire({icon:'success', title:'Image uploaded — reloading…'});
                setTimeout(() => location.reload(), 1000);
            } else { LblToast.fire({icon:'error', title:'Upload failed'}); }
        }
    });
    this.value = '';
});

// ── Delete exercise ───────────────────────────────────────────────────────────
$(document).on('click', '.btn-del-exercise', function() {
    const qId = $(this).data('qid');
    Swal.fire({
        title:'Delete this exercise?', text:'All markers will also be removed.', icon:'warning',
        showCancelButton:true, buttonsStyling:false,
        confirmButtonText:'Delete', cancelButtonText:'Cancel',
        customClass:{confirmButton:'btn btn-danger me-2', cancelButton:'btn btn-light'}
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(Q_BASE + '/' + qId + '/delete', function(res) {
            if (res.success) {
                document.getElementById('exercise_card_' + qId)?.remove();
                updateStats();
                if (!document.querySelector('.exercise-card')) {
                    document.getElementById('exercises_list').insertAdjacentHTML('beforeend',
                        '<div class="text-center py-12 text-muted" id="exercises_empty">' +
                        '<i class="ki-duotone ki-tag fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>' +
                        '<div class="fs-6 fw-semibold mb-1">No exercises yet</div>' +
                        '<div class="fs-8">Add your first labelling exercise below.</div></div>');
                }
                LblToast.fire({icon:'success', title:'Exercise removed'});
            }
        }, 'json');
    });
});

// ── Add exercise ──────────────────────────────────────────────────────────────
document.getElementById('btn_add_exercise').addEventListener('click', function() {
    const btn   = this;
    const title = document.getElementById('new_exercise_title').value.trim();
    const file  = document.getElementById('new_exercise_image').files[0];
    const fd    = new FormData();
    fd.append('question_text', title);
    if (file) fd.append('bg_image', file);
    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;
    $.ajax({
        url: Q_BASE + '/store', type:'POST', data:fd, processData:false, contentType:false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (!res.success) { LblToast.fire({icon:'error', title:res.message}); return; }
            document.getElementById('exercises_empty')?.remove();
            document.getElementById('new_exercise_title').value = '';
            document.getElementById('new_exercise_image').value = '';
            LblToast.fire({icon:'success', title:'Exercise added — reloading…'});
            setTimeout(() => location.reload(), 800);
        }
    });
});

// ── Edit assessment header ────────────────────────────────────────────────────
document.getElementById('btn_edit_lbl').addEventListener('click', function() {
    document.getElementById('lbl_edit_name').value     = <?= json_encode($quiz['quizze_name']) ?>;
    document.getElementById('lbl_edit_duration').value = <?= (int)$quiz['quizze_duration'] ?>;
    document.getElementById('lbl_edit_status').value   = <?= json_encode($quiz['quizze_status']) ?>;
    new bootstrap.Modal(document.getElementById('modal_edit_lbl')).show();
});
document.getElementById('btn_save_lbl_edit').addEventListener('click', function() {
    const btn  = this;
    const name = document.getElementById('lbl_edit_name').value.trim();
    if (!name) { document.getElementById('lbl_edit_name').classList.add('is-invalid'); return; }
    document.getElementById('lbl_edit_name').classList.remove('is-invalid');
    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;
    const fd = new FormData();
    fd.append('quizze_name',     name);
    fd.append('quizze_duration', document.getElementById('lbl_edit_duration').value);
    fd.append('quizze_status',   document.getElementById('lbl_edit_status').value);
    $.ajax({
        url: LBL_UPDATE, type:'POST', data:fd, processData:false, contentType:false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_edit_lbl')).hide();
                document.getElementById('lbl_title_display').textContent = res.quiz.quizze_name;
                document.querySelector('h1.page-heading').textContent     = res.quiz.quizze_name;
                const sc = res.quiz.quizze_status === 'Published' ? 'success' : 'warning';
                const b  = document.getElementById('lbl_status_badge');
                b.textContent = res.quiz.quizze_status;
                b.className   = 'badge badge-light-' + sc + ' fs-9';
                LblToast.fire({icon:'success', title:'Assessment updated'});
            }
        }
    });
});
</script>

<style>
.lbl-marker-dot {
    position: absolute;
    width: 26px; height: 26px; border-radius: 50%;
    background: #009ef7; color: #fff;
    font-size: 11px; font-weight: bold;
    display: flex; align-items: center; justify-content: center;
    transform: translate(-50%, -50%);
    cursor: pointer; z-index: 10;
    border: 2.5px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,.35);
    transition: transform .15s;
    user-select: none;
}
.lbl-marker-dot:hover { transform: translate(-50%,-50%) scale(1.2); background:#0078c1; }
.lbl-marker-dot-sm {
    width: 22px; height: 22px; border-radius: 50%;
    background: #009ef7; color: #fff;
    font-size: 10px; font-weight: bold;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2);
    flex-shrink: 0;
}
.label-img-container { display: inline-block; cursor: crosshair; }
.exercise-card { transition: box-shadow .15s; }
</style>
