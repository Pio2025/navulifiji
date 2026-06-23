<?php
$hasDuration   = (int) $quiz['quizze_duration'] > 0;
$remainingSecs = (int) $remainingSeconds;
$IMG_BASE      = base_url('uploads/label_images/');
$totalMarkers  = array_sum(array_map(fn($q) => count($q['markers']), $questions));
$savedAnswers  = $savedAnswers ?? [];
$isResuming    = !empty($savedAnswers);

// Build shuffled word banks per question
$wordBanks = [];
foreach ($questions as $q) {
    $labels = array_column($q['markers'], 'correct_label');
    shuffle($labels);
    $wordBanks[$q['label_question_id']] = $labels;
}
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
                <li class="breadcrumb-item text-muted"><a href="<?= $backUrl ?>" class="text-muted text-hover-primary"><?= esc($lesson['lesson_title']) ?></a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Labelling</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Timer bar (sticky)-->
<div class="sticky-top" style="top:0;z-index:200;background:#fff;border-bottom:2px solid #f1f1f4;padding:10px 0;">
    <div class="app-container container-xxl d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <?php if ($hasDuration): ?>
            <i class="ki-duotone ki-time fs-3 text-info" id="timer_icon"><span class="path1"></span><span class="path2"></span></i>
            <span class="fw-semibold text-gray-700 fs-7">Time Remaining:</span>
            <span id="lbl_timer_display" class="fw-bold fs-4 text-info font-monospace">--:--</span>
            <?php else: ?>
            <i class="ki-duotone ki-time fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
            <span class="fw-semibold text-gray-700 fs-7">No Time Limit</span>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span id="answered_badge" class="badge badge-light-info fs-8">0 / <?= $totalMarkers ?> labelled</span>
            <span id="lbl_save_indicator" class="badge badge-light-success fs-9 d-none">
                <i class="ki-duotone ki-check fs-9 me-1"><span class="path1"></span><span class="path2"></span></i>Saved
            </span>
        </div>
    </div>
</div>
<!--end::Timer bar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?php if (empty($questions) || $totalMarkers === 0): ?>
    <div class="card border-0 shadow-sm mt-5">
        <div class="card-body text-center py-16 text-muted">
            <i class="ki-duotone ki-tag fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
            <div class="fs-6 fw-semibold mb-2">This assessment has no exercises yet.</div>
            <a href="<?= $backUrl ?>" class="btn btn-light btn-sm mt-3">Back to Lesson</a>
        </div>
    </div>
    <?php else: ?>

    <?php if ($isResuming): ?>
    <div class="alert alert-warning d-flex align-items-center gap-3 mt-4 mb-0 py-3">
        <i class="ki-duotone ki-information-5 fs-3 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <span class="fs-8"><strong>Resuming your previous attempt.</strong> Your saved labels have been restored.</span>
    </div>
    <?php endif; ?>

    <div class="alert alert-info d-flex align-items-center gap-3 mt-5 mb-4 py-3">
        <i class="ki-duotone ki-information-5 fs-3 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <span class="fs-8">For each numbered marker on the image, type its correct label in the input below — or click a word from the <strong>Word Bank</strong> to fill the selected field.</span>
    </div>

    <?php foreach ($questions as $qi => $q):
        $qId      = (int) $q['label_question_id'];
        $markers  = $q['markers'];
        $bankWords = $wordBanks[$qId] ?? [];
    ?>
    <div class="card border-0 shadow-sm mb-6" data-qid="<?= $qId ?>">
        <div class="card-header border-0 pt-4 pb-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-light-info fs-9">Exercise <?= $qi + 1 ?></span>
                <?php if (!empty($q['question_text'])): ?>
                <span class="fw-semibold text-gray-800 fs-7"><?= esc($q['question_text']) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body pt-0 pb-5">

            <?php if (!empty($q['bg_image'])): ?>
            <!--begin::Image with markers-->
            <div class="position-relative d-inline-block mb-4" style="max-width:100%;">
                <img src="<?= $IMG_BASE . esc($q['bg_image']) ?>"
                     class="rounded-2 d-block"
                     style="max-width:100%;max-height:500px;object-fit:contain;"
                     alt="Exercise <?= $qi+1 ?>">
                <?php foreach ($markers as $mi => $m): ?>
                <div class="stu-marker-dot" style="left:<?= $m['marker_x'] ?>%;top:<?= $m['marker_y'] ?>%;"
                     title="Marker <?= $mi+1 ?>"
                     onclick="selectInput(<?= $qId ?>, <?= $m['marker_id'] ?>)">
                    <?= $mi + 1 ?>
                </div>
                <?php endforeach; ?>
            </div>
            <!--end::Image with markers-->
            <?php endif; ?>

            <?php if (!empty($bankWords)): ?>
            <!--begin::Word bank-->
            <div class="mb-4">
                <div class="fw-semibold text-gray-600 fs-8 mb-2">
                    <i class="ki-duotone ki-book fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Word Bank — click a word to fill the selected label field
                </div>
                <div class="d-flex flex-wrap gap-2" id="wordbank_<?= $qId ?>">
                    <?php foreach ($bankWords as $word): ?>
                    <span class="label-chip badge fs-8 fw-semibold cursor-pointer"
                          data-label="<?= esc($word, 'attr') ?>"
                          data-qid="<?= $qId ?>"
                          onclick="useChip(this)">
                        <?= esc($word) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <!--end::Word bank-->
            <?php endif; ?>

            <!--begin::Label inputs-->
            <div class="d-flex flex-column gap-3" id="inputs_<?= $qId ?>">
            <?php foreach ($markers as $mi => $m): ?>
            <div class="d-flex align-items-center gap-3">
                <div class="stu-marker-dot-sm flex-shrink-0"><?= $mi + 1 ?></div>
                <input type="text"
                       class="form-control form-control-sm label-input"
                       id="li_<?= $m['marker_id'] ?>"
                       name="answers[<?= $m['marker_id'] ?>]"
                       data-marker-id="<?= $m['marker_id'] ?>"
                       data-qid="<?= $qId ?>"
                       placeholder="Type label for marker <?= $mi+1 ?>…"
                       maxlength="300"
                       autocomplete="off">
            </div>
            <?php endforeach; ?>
            </div>
            <!--end::Label inputs-->

        </div>
    </div>
    <?php endforeach; ?>

    <!--begin::Submit button-->
    <div class="d-flex justify-content-end mt-2 mb-8">
        <button type="button" id="btn_submit_lbl" class="btn btn-primary px-8">
            <span class="indicator-label">
                <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Submit Assessment
            </span>
            <span class="indicator-progress">Submitting... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
    <!--end::Submit button-->

    <?php endif; ?>

</div>
</div>

<script>
const LBL_SUBMIT_URL = '<?= $submitUrl ?>';
const LBL_SCORE_URL  = '<?= $scoreUrl ?>';
const LBL_TICK_URL   = '<?= $tickUrl ?>';
const LBL_SAVE_URL   = '<?= $saveAnswerUrl ?>';
const LBL_REMAINING  = <?= $remainingSecs ?>;
const LBL_HAS_TIMER  = <?= $hasDuration ? 'true' : 'false' ?>;
const LBL_TOTAL      = <?= $totalMarkers ?>;
const LBL_SAVED      = <?= json_encode($savedAnswers) ?>;
const CSRF_NAME      = '<?= csrf_token() ?>';
const CSRF_HASH      = '<?= csrf_hash() ?>';
let lblSubmitted     = false;

// ── Auto-save indicator ───────────────────────────────────────────
let _lblHideTimer = null;
function showLblSavedIndicator() {
    const el = document.getElementById('lbl_save_indicator');
    if (!el) return;
    el.classList.remove('d-none');
    clearTimeout(_lblHideTimer);
    _lblHideTimer = setTimeout(() => el.classList.add('d-none'), 2000);
}

// ── Per-marker save (debounced 600ms) ─────────────────────────────
const _saveTimers = {};
function saveMarkerAnswer(markerId, value) {
    clearTimeout(_saveTimers[markerId]);
    _saveTimers[markerId] = setTimeout(function() {
        const fd = new FormData();
        fd.append(CSRF_NAME, CSRF_HASH);
        fd.append('marker_id', markerId);
        fd.append('student_label', value);
        fetch(LBL_SAVE_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json()).then(d => { if (d.success) showLblSavedIndicator(); })
            .catch(() => {});
    }, 600);
}

// ── Answered count ────────────────────────────────────────────────
function updateAnsweredCount() {
    const filled = [...document.querySelectorAll('.label-input')].filter(i => i.value.trim() !== '').length;
    const badge  = document.getElementById('answered_badge');
    badge.textContent = filled + ' / ' + LBL_TOTAL + ' labelled';
    badge.className   = filled === LBL_TOTAL ? 'badge badge-light-success fs-8' : 'badge badge-light-info fs-8';
}

// ── Restore saved answers on resume ──────────────────────────────
(function restoreSaved() {
    for (const [markerId, label] of Object.entries(LBL_SAVED)) {
        const inp = document.getElementById('li_' + markerId);
        if (inp && label !== '') inp.value = label;
    }
    // Update chip states and count after fill
    document.querySelectorAll('.label-input').forEach(function(inp) {
        const qId = inp.dataset.qid;
        const usedLabels = [...document.querySelectorAll(`#inputs_${qId} .label-input`)]
            .map(i => i.value.trim().toLowerCase()).filter(v => v);
        document.querySelectorAll(`#wordbank_${qId} .label-chip`).forEach(function(chip) {
            chip.classList.toggle('chip-used', usedLabels.includes(chip.dataset.label.toLowerCase()));
        });
    });
    updateAnsweredCount();
})();

// ── Word bank chip click ──────────────────────────────────────────
let focusedInput = null;
document.addEventListener('focusin', e => {
    if (e.target.classList.contains('label-input')) focusedInput = e.target;
});

function useChip(chipEl) {
    const label = chipEl.dataset.label;
    const qId   = chipEl.dataset.qid;
    let target  = focusedInput;
    if (!target || !target.classList.contains('label-input') || String(target.dataset.qid) !== String(qId)) {
        target = document.querySelector(`#inputs_${qId} .label-input[value=""]`) ||
                 document.querySelector(`#inputs_${qId} .label-input:not([data-filled])`);
    }
    if (!target) target = document.querySelector(`#inputs_${qId} .label-input`);
    if (target) {
        target.value = label;
        target.dispatchEvent(new Event('input'));
        target.focus();
    }
}

// ── Input change: update chips + count + save ─────────────────────
$(document).on('input', '.label-input', function() {
    const qId        = $(this).data('qid');
    const markerId   = $(this).data('marker-id');
    const usedLabels = [...$(`#inputs_${qId} .label-input`)].map(i => i.value.trim().toLowerCase()).filter(v => v);
    $(`#wordbank_${qId} .label-chip`).each(function() {
        $(this).toggleClass('chip-used', usedLabels.includes($(this).data('label').toLowerCase()));
    });
    updateAnsweredCount();
    saveMarkerAnswer(markerId, this.value.trim());
});

// ── Timer heartbeat ───────────────────────────────────────────────
function sendLblTick() {
    if (lblSubmitted || !LBL_HAS_TIMER) return;
    const fd = new FormData();
    fd.append(CSRF_NAME, CSRF_HASH);
    fd.append('time_remaining', timerSecs);
    fetch(LBL_TICK_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}
if (LBL_HAS_TIMER) setInterval(sendLblTick, 30000);
document.addEventListener('visibilitychange', function() { if (document.hidden && !lblSubmitted) sendLblTick(); });
window.addEventListener('pagehide', function() {
    if (!lblSubmitted && LBL_HAS_TIMER) {
        const fd = new FormData();
        fd.append(CSRF_NAME, CSRF_HASH);
        fd.append('time_remaining', timerSecs);
        navigator.sendBeacon(LBL_TICK_URL, fd);
    }
});

function selectInput(qId, markerId) {
    const inp = document.getElementById('li_' + markerId);
    if (inp) { inp.focus(); inp.select(); }
}

// ── Timer ─────────────────────────────────────────────────────────────────────
let timerSecs = LBL_REMAINING, timerInterval = null, timerExpired = false;

function formatTime(s) {
    return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0');
}
function setTimerColor(s, total) {
    const el   = document.getElementById('lbl_timer_display');
    const icon = document.getElementById('timer_icon');
    if (!el) return;
    const pct  = total > 0 ? s/total : 1;
    const cls  = pct <= 0.1 ? 'text-danger' : (pct <= 0.25 ? 'text-warning' : 'text-info');
    el.className = 'fw-bold fs-4 font-monospace ' + cls;
    if (icon) icon.className = 'ki-duotone ki-time fs-3 ' + cls;
}

if (LBL_HAS_TIMER && timerSecs > 0) {
    const totalSecs = timerSecs;
    const display   = document.getElementById('lbl_timer_display');
    display.textContent = formatTime(timerSecs);
    setTimerColor(timerSecs, totalSecs);
    timerInterval = setInterval(function() {
        timerSecs--;
        if (timerSecs <= 0) {
            timerSecs = 0; clearInterval(timerInterval); timerExpired = true;
            if (display) { display.textContent = '00:00'; display.className = 'fw-bold fs-4 font-monospace text-danger'; }
            Swal.fire({
                title:'Time\'s Up!', html:'<p>Your time has run out.</p><p class="text-muted fs-8">Your answers will be submitted automatically.</p>',
                icon:'warning', allowOutsideClick:false, allowEscapeKey:false,
                showConfirmButton:false, timer:3000, timerProgressBar:true
            }).then(() => submitLabel('submitted'));
        } else {
            if (display) display.textContent = formatTime(timerSecs);
            setTimerColor(timerSecs, totalSecs);
        }
    }, 1000);
}

// ── Submit ────────────────────────────────────────────────────────────────────
function submitLabel(status) {
    const btn = document.getElementById('btn_submit_lbl');
    if (btn) { btn.setAttribute('data-kt-indicator','on'); btn.disabled = true; }
    clearInterval(timerInterval);

    const fd = new FormData();
    fd.append('status', status);
    document.querySelectorAll('.label-input').forEach(inp => {
        fd.append('answers[' + inp.dataset.markerId + ']', inp.value.trim());
    });

    $.ajax({
        url: LBL_SUBMIT_URL, type:'POST', data:fd, processData:false, contentType:false,
        success: function(res) {
            if (res.success) {
                lblSubmitted = true;
                window.location = LBL_SCORE_URL;
            } else {
                Swal.fire({title:'Error', text:res.message||'Submission failed.', icon:'error',
                    buttonsStyling:false, confirmButtonText:'OK', customClass:{confirmButton:'btn btn-danger'}});
                if (btn) { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; }
            }
        },
        error: function() {
            Swal.fire({title:'Network Error', text:'Please check your connection.', icon:'error',
                buttonsStyling:false, confirmButtonText:'OK', customClass:{confirmButton:'btn btn-danger'}});
            if (btn) { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; }
        }
    });
}

document.getElementById('btn_submit_lbl')?.addEventListener('click', function() {
    if (timerExpired) return;
    const filled   = [...document.querySelectorAll('.label-input')].filter(i => i.value.trim()!=='').length;
    const unlabelled = LBL_TOTAL - filled;
    Swal.fire({
        title:'Submit Assessment?',
        html: unlabelled > 0
            ? `<p>You have <strong>${unlabelled}</strong> unlabelled marker${unlabelled!==1?'s':''}.</p><p class="text-muted fs-8">Blank fields will count as incorrect.</p>`
            : '<p>All markers have been labelled.</p><p class="text-muted fs-8">This action cannot be undone.</p>',
        icon: unlabelled > 0 ? 'warning' : 'question',
        showCancelButton:true, confirmButtonText:'Submit', cancelButtonText:'Keep Working',
        buttonsStyling:false,
        customClass:{confirmButton:'btn btn-primary me-2', cancelButton:'btn btn-light'}
    }).then(r => { if (r.isConfirmed) submitLabel('submitted'); });
});

window.addEventListener('beforeunload', function(e) {
    if (!timerExpired && !lblSubmitted) { e.preventDefault(); e.returnValue = ''; }
});
</script>

<style>
.stu-marker-dot {
    position: absolute;
    width: 26px; height: 26px; border-radius: 50%;
    background: #009ef7; color: #fff;
    font-size: 11px; font-weight: bold;
    display: flex; align-items: center; justify-content: center;
    transform: translate(-50%, -50%);
    cursor: pointer; z-index: 10;
    border: 2.5px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,.35);
    transition: transform .15s;
    user-select: none;
}
.stu-marker-dot:hover { transform: translate(-50%,-50%) scale(1.15); }
.stu-marker-dot-sm {
    width: 26px; height: 26px; border-radius: 50%;
    background: #009ef7; color: #fff;
    font-size: 11px; font-weight: bold;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2); flex-shrink:0;
}
.label-chip {
    background: #e0f0ff; color: #0078c1;
    border: 1.5px solid #b3d9f8; border-radius: 20px;
    padding: 4px 12px; cursor: pointer;
    transition: background .15s, opacity .15s;
}
.label-chip:hover { background: #b3d9f8; }
.label-chip.chip-used { background: #f1f1f4; color: #aaa; border-color: #e0e0e0; text-decoration: line-through; cursor: default; }
.font-monospace { font-family:'Courier New',monospace; }
</style>
