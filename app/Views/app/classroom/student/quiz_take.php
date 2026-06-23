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
                <li class="breadcrumb-item text-muted">Quiz</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<?php
$totalQuestions  = count($quiz['questions'] ?? []);
$hasDuration     = (int) $quiz['quizze_duration'] > 0;
$remainingSecs   = (int) $remainingSeconds;
$savedResponses  = $savedResponses ?? [];
$isResuming      = !empty($savedResponses);
?>

<!--begin::Timer bar (sticky)-->
<?php if ($hasDuration): ?>
<div id="quiz_timer_bar" class="sticky-top" style="top:0;z-index:200;background:#fff;border-bottom:2px solid #f1f1f4;padding:10px 0;">
    <div class="app-container container-xxl d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <i class="ki-duotone ki-time fs-3 text-primary" id="timer_icon">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <span class="fw-semibold text-gray-700 fs-7">Time Remaining:</span>
            <span id="quiz_timer_display" class="fw-bold fs-4 text-primary font-monospace">--:--</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted fs-8"><?= $totalQuestions ?> question<?= $totalQuestions !== 1 ? 's' : '' ?></span>
            <span id="answered_count_badge" class="badge badge-light-primary fs-8">0 / <?= $totalQuestions ?> answered</span>
            <span id="auto_save_indicator" class="badge badge-light-success fs-9 d-none">
                <i class="ki-duotone ki-check fs-9 me-1"><span class="path1"></span><span class="path2"></span></i>Saved
            </span>
        </div>
    </div>
</div>
<?php else: ?>
<div class="sticky-top" style="top:0;z-index:200;background:#fff;border-bottom:2px solid #f1f1f4;padding:10px 0;">
    <div class="app-container container-xxl d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <i class="ki-duotone ki-time fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
            <span class="fw-semibold text-gray-700 fs-7">No Time Limit</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted fs-8"><?= $totalQuestions ?> question<?= $totalQuestions !== 1 ? 's' : '' ?></span>
            <span id="answered_count_badge" class="badge badge-light-primary fs-8">0 / <?= $totalQuestions ?> answered</span>
            <span id="auto_save_indicator" class="badge badge-light-success fs-9 d-none">
                <i class="ki-duotone ki-check fs-9 me-1"><span class="path1"></span><span class="path2"></span></i>Saved
            </span>
        </div>
    </div>
</div>
<?php endif; ?>
<!--end::Timer bar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?php if ($isResuming): ?>
    <div class="alert alert-warning d-flex align-items-center gap-3 mt-4 mb-0 py-3">
        <i class="ki-duotone ki-information-5 fs-3 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <span class="fs-8"><strong>Resuming your previous attempt.</strong> Your saved answers have been restored — continue from where you left off.</span>
    </div>
    <?php endif; ?>

    <?php if (empty($quiz['questions'])): ?>
    <div class="card border-0 shadow-sm mt-5">
        <div class="card-body text-center py-16 text-muted">
            <i class="ki-duotone ki-questionnaire-tablet fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
            <div class="fs-6 fw-semibold">This quiz has no questions yet.</div>
            <a href="<?= $backUrl ?>" class="btn btn-light btn-sm mt-4">Back to Lesson</a>
        </div>
    </div>
    <?php else: ?>

    <div class="row g-6 mt-1">
        <div class="col-12">

            <!--begin::Questions-->
            <?php foreach ($quiz['questions'] as $qi => $q):
                $qNum = $qi + 1;
                $qId  = (int) $q['quizze_quest_id'];
            ?>
            <div class="card border-0 shadow-sm mb-4 question-card" id="qcard_<?= $qNum ?>">
                <div class="card-body p-6">
                    <!--begin::Question header-->
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="symbol symbol-35px flex-shrink-0">
                            <div class="symbol-label bg-light-primary fw-bold text-primary fs-7"><?= $qNum ?></div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-gray-800 fs-6 lh-base"><?= nl2br(esc($q['question'])) ?></div>
                        </div>
                    </div>
                    <!--end::Question header-->

                    <!--begin::Question image-->
                    <?php if (!empty($q['files'])): ?>
                    <div class="row g-3 mb-4 ps-12">
                        <?php foreach ($q['files'] as $f): ?>
                        <div class="col-auto">
                            <img src="<?= base_url('uploads/quiz_files/' . $f['file_src']) ?>"
                                 class="rounded-2 border border-gray-200"
                                 style="max-height:200px;max-width:100%;object-fit:contain;" alt="">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <!--end::Question image-->

                    <!--begin::Answer options-->
                    <div class="ps-12">
                        <?php $letters = ['A', 'B', 'C', 'D', 'E']; ?>
                        <?php foreach ($q['answers'] as $ai => $ans): ?>
                        <label class="answer-option d-flex align-items-center gap-3 p-3 rounded-2 mb-2 cursor-pointer"
                               for="ans_<?= $qId ?>_<?= $ans['lesson_quizze_answer_id'] ?>">
                            <input type="radio"
                                   class="form-check-input quiz-radio mt-0 flex-shrink-0"
                                   name="responses[<?= $qId ?>]"
                                   id="ans_<?= $qId ?>_<?= $ans['lesson_quizze_answer_id'] ?>"
                                   value="<?= (int) $ans['lesson_quizze_answer_id'] ?>"
                                   data-q-num="<?= $qNum ?>">
                            <div class="symbol symbol-25px flex-shrink-0">
                                <div class="symbol-label bg-light-secondary fw-bold text-gray-600 fs-9 answer-letter"><?= $letters[$ai] ?? chr(65 + $ai) ?></div>
                            </div>
                            <span class="text-gray-700 fs-7"><?= esc($ans['answer']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <!--end::Answer options-->
                </div>
            </div>
            <?php endforeach; ?>
            <!--end::Questions-->

            <!--begin::Submit button-->
            <div class="d-flex justify-content-end mt-6 mb-8">
                <button type="button" id="btn_submit_quiz" class="btn btn-primary px-8">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Submit Quiz
                    </span>
                    <span class="indicator-progress">Submitting... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
            <!--end::Submit button-->

        </div>
    </div>

    <?php endif; ?>

</div>
</div>

<script>
const SUBMIT_URL      = '<?= $submitUrl ?>';
const SCORE_URL       = '<?= $scoreUrl ?>';
const TICK_URL        = '<?= $tickUrl ?>';
const SAVE_ANSWER_URL = '<?= $saveAnswerUrl ?>';
const REMAINING_SEC   = <?= $remainingSecs ?>;
const HAS_DURATION    = <?= $hasDuration ? 'true' : 'false' ?>;
const TOTAL_Q         = <?= $totalQuestions ?>;
const SAVED_RESPONSES = <?= json_encode($savedResponses) ?>;
const CSRF_NAME       = '<?= csrf_token() ?>';
const CSRF_HASH       = '<?= csrf_hash() ?>';

let quizSubmitted = false;

// ── Auto-save indicator ───────────────────────────────────────────
let _hideTimer = null;
function showSavedIndicator() {
    const el = document.getElementById('auto_save_indicator');
    if (!el) return;
    el.classList.remove('d-none');
    clearTimeout(_hideTimer);
    _hideTimer = setTimeout(() => el.classList.add('d-none'), 2000);
}

// ── Per-answer save ───────────────────────────────────────────────
function saveAnswer(questionId, answerId) {
    const fd = new FormData();
    fd.append(CSRF_NAME, CSRF_HASH);
    fd.append('question_id', questionId);
    fd.append('answer_id', answerId);
    fetch(SAVE_ANSWER_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { if (d.success) showSavedIndicator(); })
        .catch(() => {});
}

// ── Answered counter ──────────────────────────────────────────────
function updateAnsweredCount() {
    const answered = document.querySelectorAll('.quiz-radio:checked').length;
    document.getElementById('answered_count_badge').textContent = answered + ' / ' + TOTAL_Q + ' answered';
}

$(document).on('change', '.quiz-radio', function() {
    const name = this.name;
    document.querySelectorAll(`input[name="${CSS.escape(name)}"]`).forEach(r => {
        r.closest('.answer-option').classList.remove('selected');
    });
    this.closest('.answer-option').classList.add('selected');
    updateAnsweredCount();
    // Auto-save this answer
    const match = name.match(/responses\[(\d+)\]/);
    if (match) saveAnswer(parseInt(match[1]), parseInt(this.value));
});

// ── Restore saved responses on resume ────────────────────────────
(function restoreSaved() {
    for (const [questionId, answerId] of Object.entries(SAVED_RESPONSES)) {
        const radio = document.getElementById('ans_' + questionId + '_' + answerId);
        if (radio) {
            radio.checked = true;
            const name = radio.name;
            document.querySelectorAll(`input[name="${CSS.escape(name)}"]`).forEach(r => {
                r.closest('.answer-option').classList.remove('selected');
            });
            radio.closest('.answer-option').classList.add('selected');
        }
    }
    updateAnsweredCount();
})();

// ── Timer ─────────────────────────────────────────────────────────
let timerSeconds  = REMAINING_SEC;
let timerInterval = null;
let timerExpired  = false;

function formatTime(s) {
    return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
}
function setTimerColor(s, total) {
    const el   = document.getElementById('quiz_timer_display');
    const icon = document.getElementById('timer_icon');
    if (!el) return;
    const pct = total > 0 ? s / total : 1;
    const color = pct <= 0.1 ? 'text-danger' : (pct <= 0.25 ? 'text-warning' : 'text-primary');
    el.className = 'fw-bold fs-4 font-monospace ' + color;
    if (icon) icon.className = 'ki-duotone ki-time fs-3 ' + color;
}

// ── Timer heartbeat (save remaining time to DB every 30s) ─────────
function sendTick() {
    if (quizSubmitted || timerExpired || !HAS_DURATION) return;
    const fd = new FormData();
    fd.append(CSRF_NAME, CSRF_HASH);
    fd.append('time_remaining', timerSeconds);
    fetch(TICK_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

if (HAS_DURATION) {
    setInterval(sendTick, 30000);
}

// Save on tab hide / page unload via Beacon (works even during unload)
document.addEventListener('visibilitychange', function() {
    if (document.hidden && HAS_DURATION && !quizSubmitted) sendTick();
});
window.addEventListener('pagehide', function() {
    if (!quizSubmitted && HAS_DURATION) {
        const fd = new FormData();
        fd.append(CSRF_NAME, CSRF_HASH);
        fd.append('time_remaining', timerSeconds);
        navigator.sendBeacon(TICK_URL, fd);
    }
});

function submitQuiz(status) {
    const btn = document.getElementById('btn_submit_quiz');
    if (btn) { btn.setAttribute('data-kt-indicator', 'on'); btn.disabled = true; }
    clearInterval(timerInterval);

    const fd = new FormData();
    fd.append('status', status);
    document.querySelectorAll('.quiz-radio:checked').forEach(r => {
        const match = r.name.match(/responses\[(\d+)\]/);
        if (match) fd.append('responses[' + match[1] + ']', r.value);
    });

    $.ajax({
        url: SUBMIT_URL, type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            if (res.success) {
                quizSubmitted = true;
                window.location = SCORE_URL;
            } else {
                Swal.fire({ title: 'Error', text: res.message || 'Submission failed.', icon: 'error',
                    buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
                if (btn) { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; }
            }
        },
        error: function() {
            Swal.fire({ title: 'Network Error', text: 'Please check your connection and try again.', icon: 'error',
                buttonsStyling: false, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            if (btn) { btn.removeAttribute('data-kt-indicator'); btn.disabled = false; }
        }
    });
}

if (HAS_DURATION && timerSeconds > 0) {
    const totalSecs = timerSeconds;
    const display   = document.getElementById('quiz_timer_display');
    display.textContent = formatTime(timerSeconds);
    setTimerColor(timerSeconds, totalSecs);

    timerInterval = setInterval(function() {
        timerSeconds--;
        if (timerSeconds <= 0) {
            timerSeconds = 0; clearInterval(timerInterval); timerExpired = true;
            if (display) { display.textContent = '00:00'; display.className = 'fw-bold fs-4 font-monospace text-danger'; }
            Swal.fire({
                title: 'Time\'s Up!',
                html: '<p>Your time has run out.</p><p class="text-muted fs-8">Your answers will be submitted automatically.</p>',
                icon: 'warning', allowOutsideClick: false, allowEscapeKey: false,
                showConfirmButton: false, timer: 3000, timerProgressBar: true
            }).then(() => submitQuiz('timed_out'));
        } else {
            if (display) display.textContent = formatTime(timerSeconds);
            setTimerColor(timerSeconds, totalSecs);
        }
    }, 1000);
}

// ── Manual submit ─────────────────────────────────────────────────
document.getElementById('btn_submit_quiz')?.addEventListener('click', function() {
    if (timerExpired) return;
    const answered   = document.querySelectorAll('.quiz-radio:checked').length;
    const unanswered = TOTAL_Q - answered;
    Swal.fire({
        title: 'Submit Quiz?',
        html: unanswered > 0
            ? `<p>You have <strong>${unanswered}</strong> unanswered question${unanswered !== 1 ? 's' : ''}.</p><p class="text-muted fs-8">Unanswered questions will be counted as wrong.</p>`
            : '<p>You have answered all questions.</p><p class="text-muted fs-8">This action cannot be undone.</p>',
        icon: unanswered > 0 ? 'warning' : 'question',
        showCancelButton: true, confirmButtonText: 'Submit', cancelButtonText: 'Keep Working',
        buttonsStyling: false,
        customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-light' }
    }).then(r => { if (r.isConfirmed) submitQuiz('submitted'); });
});

window.addEventListener('beforeunload', function(e) {
    if (!timerExpired && !quizSubmitted) { e.preventDefault(); e.returnValue = ''; }
});
</script>

<style>
.answer-option {
    border: 1.5px solid #f1f1f4;
    transition: background .15s, border-color .15s;
    user-select: none;
}
.answer-option:hover {
    background: #f5f8ff;
    border-color: var(--bs-primary);
}
.answer-option.selected {
    background: #eef2ff;
    border-color: var(--bs-primary);
}
.answer-option.selected .answer-letter {
    background: var(--bs-primary) !important;
    color: #fff !important;
}
.question-card { border-left: 3px solid transparent !important; transition: border-color .2s; }
.question-card:has(.quiz-radio:checked) { border-left-color: var(--bs-success) !important; }
.font-monospace { font-family: 'Courier New', monospace; }
</style>
