<?php
$totalItems      = count($items);
$hasDuration     = (int) $quiz['quizze_duration'] > 0;
$remainingSecs   = (int) $remainingSeconds;
$IMG_BASE        = base_url('uploads/dragdrop_files/');
$savedPlacements = $savedPlacements ?? [];
$isResuming      = !empty($savedPlacements);
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
                <li class="breadcrumb-item text-muted"><a href="<?= $backUrl ?>" class="text-muted text-hover-primary"><?= esc($lesson['lesson_title']) ?></a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Drag &amp; Drop</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Timer / progress bar (sticky)-->
<div id="dd_timer_bar" class="sticky-top" style="top:0;z-index:200;background:#fff;border-bottom:2px solid #f1f1f4;padding:10px 0;">
    <div class="app-container container-xxl d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <?php if ($hasDuration): ?>
            <i class="ki-duotone ki-time fs-3 text-primary" id="timer_icon"><span class="path1"></span><span class="path2"></span></i>
            <span class="fw-semibold text-gray-700 fs-7">Time Remaining:</span>
            <span id="dd_timer_display" class="fw-bold fs-4 text-primary font-monospace">--:--</span>
            <?php else: ?>
            <i class="ki-duotone ki-time fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
            <span class="fw-semibold text-gray-700 fs-7">No Time Limit</span>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span id="placed_badge" class="badge badge-light-primary fs-8">0 / <?= $totalItems ?> placed</span>
            <span id="dd_save_indicator" class="badge badge-light-success fs-9 d-none">
                <i class="ki-duotone ki-check fs-9 me-1"><span class="path1"></span><span class="path2"></span></i>Saved
            </span>
        </div>
    </div>
</div>
<!--end::Timer / progress bar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?php if (empty($items) || empty($zones)): ?>
    <div class="card border-0 shadow-sm mt-5">
        <div class="card-body text-center py-16 text-muted">
            <i class="ki-duotone ki-abstract-26 fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
            <div class="fs-6 fw-semibold mb-2">This assessment is not ready yet.</div>
            <div class="fs-8 mb-4">The teacher hasn't added items or zones.</div>
            <a href="<?= $backUrl ?>" class="btn btn-light btn-sm">Back to Lesson</a>
        </div>
    </div>
    <?php else: ?>

    <?php if ($isResuming): ?>
    <div class="alert alert-warning d-flex align-items-center gap-3 mt-4 mb-0 py-3">
        <i class="ki-duotone ki-information-5 fs-3 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <span class="fs-8"><strong>Resuming your previous attempt.</strong> Your saved placements have been restored.</span>
    </div>
    <?php endif; ?>

    <!--begin::Instructions-->
    <div class="alert alert-primary d-flex align-items-center gap-3 mt-5 mb-4 py-3">
        <i class="ki-duotone ki-information-5 fs-3 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <span class="fs-8">Drag each item from the <strong>Items Pool</strong> and drop it into the correct zone. You can move items between zones at any time before submitting.</span>
    </div>
    <!--end::Instructions-->

    <div class="row g-5 mt-1">

        <!--begin::Items Pool (left)-->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ki-duotone ki-cursor fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        <div>
                            <h6 class="fw-bold text-gray-800 mb-0">Items Pool</h6>
                            <span class="text-muted fs-9">Drag items to the correct zone</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2 pb-5">
                    <div id="items_pool"
                         class="dd-pool min-h-100px p-2 rounded-2 d-flex flex-column gap-2"
                         ondragover="event.preventDefault(); this.classList.add('pool-hover');"
                         ondragleave="this.classList.remove('pool-hover');"
                         ondrop="dropToPool(event)">
                        <?php foreach ($items as $item): ?>
                        <div class="dd-item d-flex align-items-center gap-3 p-3 bg-white border border-gray-200 rounded-2 cursor-grab"
                             id="dditem_<?= $item['item_id'] ?>"
                             draggable="true"
                             data-item-id="<?= $item['item_id'] ?>"
                             ondragstart="dragStart(event, <?= $item['item_id'] ?>)">
                            <?php if (!empty($item['item_image'])): ?>
                            <img src="<?= $IMG_BASE . esc($item['item_image']) ?>"
                                 class="rounded-2 flex-shrink-0" style="width:36px;height:36px;object-fit:cover;" alt="">
                            <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light-primary rounded-2 flex-shrink-0" style="width:36px;height:36px;">
                                <i class="ki-duotone ki-abstract-26 fs-5 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                            <?php endif; ?>
                            <span class="fw-semibold text-gray-800 fs-8 lh-sm"><?= esc($item['item_text']) ?></span>
                            <i class="ki-duotone ki-drag fs-5 text-gray-400 ms-auto flex-shrink-0"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Items Pool-->

        <!--begin::Drop Zones (right)-->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ki-duotone ki-drop fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                        <div>
                            <h6 class="fw-bold text-gray-800 mb-0">Drop Zones</h6>
                            <span class="text-muted fs-9">Drop items here</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2 pb-5">
                    <div class="row g-4">
                    <?php foreach ($zones as $zone): ?>
                    <div class="col-sm-6 col-md-<?= count($zones) <= 2 ? '6' : (count($zones) <= 4 ? '6' : '4') ?>">
                        <div class="dd-zone-wrap h-100">
                            <div class="fw-bold text-gray-700 fs-8 mb-2 d-flex align-items-center gap-1">
                                <i class="ki-duotone ki-drop fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
                                <?= esc($zone['zone_label']) ?>
                            </div>
                            <div class="dd-zone rounded-2 p-3 min-h-100px d-flex flex-column gap-2"
                                 id="ddzone_<?= $zone['zone_id'] ?>"
                                 data-zone-id="<?= $zone['zone_id'] ?>"
                                 ondragover="zoneDragOver(event)"
                                 ondragleave="zoneDragLeave(event)"
                                 ondrop="dropToZone(event, <?= $zone['zone_id'] ?>)">
                                <div class="dd-zone-empty text-center text-muted fs-9 py-3" id="zempty_<?= $zone['zone_id'] ?>">
                                    Drop items here
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!--begin::Submit-->
            <div class="d-flex justify-content-end mt-5 mb-8">
                <button type="button" id="btn_submit_dd" class="btn btn-primary px-8">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Submit Assessment
                    </span>
                    <span class="indicator-progress">Submitting... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
            <!--end::Submit-->
        </div>
        <!--end::Drop Zones-->

    </div>

    <?php endif; ?>

</div>
</div>

<script>
const DD_SUBMIT_URL  = '<?= $submitUrl ?>';
const DD_SCORE_URL   = '<?= $scoreUrl ?>';
const DD_TICK_URL    = '<?= $tickUrl ?>';
const DD_SAVE_URL    = '<?= $saveAnswerUrl ?>';
const DD_REMAINING   = <?= $remainingSecs ?>;
const DD_HAS_TIMER   = <?= $hasDuration ? 'true' : 'false' ?>;
const DD_TOTAL_ITEMS = <?= $totalItems ?>;
const DD_SAVED       = <?= json_encode($savedPlacements) ?>;
const CSRF_NAME      = '<?= csrf_token() ?>';
const CSRF_HASH      = '<?= csrf_hash() ?>';

// ── Placement state ───────────────────────────────────────────────────────────
const placements = {};
let ddSubmitted  = false;
let ddRestoring  = false;
<?php foreach ($items as $item): ?>
placements[<?= $item['item_id'] ?>] = null;
<?php endforeach; ?>

let draggedItemId = null;

// ── Auto-save indicator ───────────────────────────────────────────
let _ddHideTimer = null;
function showDdSavedIndicator() {
    const el = document.getElementById('dd_save_indicator');
    if (!el) return;
    el.classList.remove('d-none');
    clearTimeout(_ddHideTimer);
    _ddHideTimer = setTimeout(() => el.classList.add('d-none'), 2000);
}

// ── Save placement to DB ──────────────────────────────────────────
function savePlacement(itemId, zoneId) {
    if (ddRestoring) return;
    const fd = new FormData();
    fd.append(CSRF_NAME, CSRF_HASH);
    fd.append('item_id', itemId);
    fd.append('zone_id', zoneId || '');
    fetch(DD_SAVE_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) showDdSavedIndicator(); })
        .catch(() => {});
}

function updatePlacedBadge() {
    const placed = Object.values(placements).filter(v => v !== null).length;
    document.getElementById('placed_badge').textContent = placed + ' / ' + DD_TOTAL_ITEMS + ' placed';
    document.getElementById('placed_badge').className   = placed === DD_TOTAL_ITEMS
        ? 'badge badge-light-success fs-8'
        : 'badge badge-light-primary fs-8';
}

// ── Drag events ───────────────────────────────────────────────────────────────
function dragStart(event, itemId) {
    draggedItemId = itemId;
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', itemId);
    setTimeout(() => {
        const el = document.getElementById('dditem_' + itemId);
        if (el) el.classList.add('dragging');
    }, 0);
}
document.addEventListener('dragend', function() {
    document.querySelectorAll('.dd-item').forEach(el => el.classList.remove('dragging'));
    document.querySelectorAll('.dd-zone').forEach(el => el.classList.remove('zone-over'));
    document.getElementById('items_pool')?.classList.remove('pool-hover');
    draggedItemId = null;
});

function zoneDragOver(event) {
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
    event.currentTarget.classList.add('zone-over');
}
function zoneDragLeave(event) {
    event.currentTarget.classList.remove('zone-over');
}

function dropToZone(event, zoneId) {
    event.preventDefault();
    event.currentTarget.classList.remove('zone-over');
    const itemId = parseInt(event.dataTransfer.getData('text/plain'));
    if (!itemId) return;
    moveItemToZone(itemId, zoneId);
}

function dropToPool(event) {
    event.preventDefault();
    document.getElementById('items_pool')?.classList.remove('pool-hover');
    const itemId = parseInt(event.dataTransfer.getData('text/plain'));
    if (!itemId) return;
    moveItemToPool(itemId);
}

function moveItemToZone(itemId, zoneId) {
    const el = document.getElementById('dditem_' + itemId);
    if (!el) return;

    // Remove from current location
    el.remove();
    placements[itemId] = zoneId;

    // Add to zone
    const zone = document.getElementById('ddzone_' + zoneId);
    if (zone) {
        document.getElementById('zempty_' + zoneId)?.style.setProperty('display', 'none');
        // Wrap item with remove button
        const wrapper = document.createElement('div');
        wrapper.className = 'dd-zone-item position-relative';
        wrapper.id = 'zwrapper_' + itemId;
        el.setAttribute('ondragstart', `dragStart(event, ${itemId})`);
        el.classList.remove('dragging');
        wrapper.appendChild(el);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.title = 'Return to pool';
        removeBtn.className = 'btn btn-icon btn-xs btn-light-danger dd-remove-btn position-absolute';
        removeBtn.style.cssText = 'top:-6px;right:-6px;width:18px;height:18px;min-width:0;padding:0;border-radius:50%;';
        removeBtn.innerHTML = '<i class="ki-duotone ki-cross fs-9"><span class="path1"></span><span class="path2"></span></i>';
        removeBtn.onclick = function() { moveItemToPool(itemId); };
        wrapper.appendChild(removeBtn);
        zone.appendChild(wrapper);
    }
    updatePlacedBadge();
    savePlacement(itemId, zoneId);
}

function moveItemToPool(itemId) {
    const wrapper = document.getElementById('zwrapper_' + itemId);
    const el      = document.getElementById('dditem_' + itemId);

    // Determine which zone this came from so we can check empty state
    const oldZoneId = placements[itemId];
    placements[itemId] = null;

    if (wrapper) wrapper.remove();
    else if (el) el.remove();

    // Show zone empty placeholder if zone is now empty
    if (oldZoneId) {
        const zone = document.getElementById('ddzone_' + oldZoneId);
        const emptyEl = document.getElementById('zempty_' + oldZoneId);
        if (zone && emptyEl && !zone.querySelector('.dd-zone-item')) {
            emptyEl.style.removeProperty('display');
        }
    }

    // Re-insert into pool (create fresh if needed)
    const pool = document.getElementById('items_pool');
    if (pool && el) {
        el.classList.remove('dragging');
        pool.appendChild(el);
    }
    updatePlacedBadge();
    savePlacement(itemId, null);
}

// ── Restore saved placements on resume ────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    ddRestoring = true;
    for (const [itemId, zoneId] of Object.entries(DD_SAVED)) {
        if (zoneId) moveItemToZone(parseInt(itemId), parseInt(zoneId));
    }
    ddRestoring = false;
});

// ── Timer heartbeat ───────────────────────────────────────────────
function sendDdTick() {
    if (ddSubmitted || !DD_HAS_TIMER) return;
    const fd = new FormData();
    fd.append(CSRF_NAME, CSRF_HASH);
    fd.append('time_remaining', timerSecs);
    fetch(DD_TICK_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}
if (DD_HAS_TIMER) setInterval(sendDdTick, 30000);
document.addEventListener('visibilitychange', function() { if (document.hidden && !ddSubmitted) sendDdTick(); });
window.addEventListener('pagehide', function() {
    if (!ddSubmitted && DD_HAS_TIMER) {
        const fd = new FormData();
        fd.append(CSRF_NAME, CSRF_HASH);
        fd.append('time_remaining', timerSecs);
        navigator.sendBeacon(DD_TICK_URL, fd);
    }
});

// ── Timer ─────────────────────────────────────────────────────────────────────
let timerSecs     = DD_REMAINING;
let timerInterval = null;
let timerExpired  = false;

function formatTime(s) {
    return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
}
function setTimerColor(s, total) {
    const el   = document.getElementById('dd_timer_display');
    const icon = document.getElementById('timer_icon');
    if (!el) return;
    const pct  = total > 0 ? s / total : 1;
    const cls  = pct <= 0.1 ? 'text-danger' : (pct <= 0.25 ? 'text-warning' : 'text-primary');
    el.className   = 'fw-bold fs-4 font-monospace ' + cls;
    if (icon) icon.className = 'ki-duotone ki-time fs-3 ' + cls;
}

if (DD_HAS_TIMER && timerSecs > 0) {
    const totalSecs = timerSecs;
    const display   = document.getElementById('dd_timer_display');
    display.textContent = formatTime(timerSecs);
    setTimerColor(timerSecs, totalSecs);

    timerInterval = setInterval(function() {
        timerSecs--;
        if (timerSecs <= 0) {
            timerSecs = 0; clearInterval(timerInterval); timerExpired = true;
            if (display) { display.textContent = '00:00'; display.className = 'fw-bold fs-4 font-monospace text-danger'; }
            Swal.fire({
                title: 'Time\'s Up!',
                html: '<p>Your time has run out.</p><p class="text-muted fs-8">Your answers will be submitted automatically.</p>',
                icon: 'warning', allowOutsideClick: false, allowEscapeKey: false,
                showConfirmButton: false, timer: 3000, timerProgressBar: true
            }).then(() => submitDD('submitted'));
        } else {
            if (display) display.textContent = formatTime(timerSecs);
            setTimerColor(timerSecs, totalSecs);
        }
    }, 1000);
}

// ── Submit ────────────────────────────────────────────────────────────────────
function submitDD(status) {
    const btn = document.getElementById('btn_submit_dd');
    if (btn) { btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true; }
    clearInterval(timerInterval);

    const fd = new FormData();
    fd.append('status', status);
    Object.entries(placements).forEach(([itemId, zoneId]) => {
        fd.append('placements[' + itemId + ']', zoneId || '0');
    });

    $.ajax({
        url: DD_SUBMIT_URL, type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (res.success) {
                ddSubmitted = true;
                window.location = DD_SCORE_URL;
            } else {
                Swal.fire({ title: 'Error', text: res.message || 'Submission failed.', icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
                if (btn) { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; }
            }
        },
        error: function() {
            Swal.fire({ title: 'Network Error', text: 'Please check your connection.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            if (btn) { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; }
        }
    });
}

document.getElementById('btn_submit_dd')?.addEventListener('click', function() {
    if (timerExpired) return;
    const placed   = Object.values(placements).filter(v => v !== null).length;
    const unplaced = DD_TOTAL_ITEMS - placed;
    Swal.fire({
        title: 'Submit Assessment?',
        html: unplaced > 0
            ? `<p>You have <strong>${unplaced}</strong> unplaced item${unplaced !== 1 ? 's' : ''}.</p><p class="text-muted fs-8">Unplaced items will count as incorrect.</p>`
            : '<p>You have placed all items.</p><p class="text-muted fs-8">This action cannot be undone.</p>',
        icon: unplaced > 0 ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Keep Working',
        buttonsStyling: false,
        customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-light' }
    }).then(r => { if (r.isConfirmed) submitDD('submitted'); });
});

window.addEventListener('beforeunload', function(e) {
    if (!timerExpired && !ddSubmitted) { e.preventDefault(); e.returnValue = ''; }
});
</script>

<style>
.dd-item {
    transition: box-shadow .15s, opacity .15s;
    user-select: none;
}
.dd-item:hover { box-shadow: 0 2px 8px rgba(0,0,0,.12); }
.dd-item.dragging { opacity: .4; }
.dd-zone {
    background: #f8fffe;
    border: 2px dashed #b5e8d8;
    transition: background .15s, border-color .15s;
    min-height: 100px;
}
.dd-zone.zone-over {
    background: #e0f8f0;
    border-color: #50cd89;
}
.dd-pool {
    background: #f8f9ff;
    border: 2px dashed #d0d5e8;
    transition: background .15s, border-color .15s;
}
.dd-pool.pool-hover {
    background: #eef0ff;
    border-color: #7c85d4;
}
.dd-zone-item { position: relative; }
.dd-remove-btn { opacity: 0; transition: opacity .15s; }
.dd-zone-item:hover .dd-remove-btn { opacity: 1; }
.font-monospace { font-family: 'Courier New', monospace; }
</style>
