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
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/teacher/' . $schSubId . '/lessons') ?>" class="text-muted text-hover-primary">Lessons</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('classroom/teacher/' . $schSubId . '/lesson/' . $lesson['lesson_id']) ?>" class="text-muted text-hover-primary"><?= esc($lesson['lesson_title']) ?></a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($quiz['quizze_name']) ?></li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/teacher/' . $schSubId . '/lesson/' . $lesson['lesson_id']) ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back to Lesson
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php
    $qCount      = count($quiz['questions']);
    $statusColor = $quiz['quizze_status'] === 'Published' ? 'success' : 'warning';
    ?>

    <!--begin::Quiz info card-->
    <div class="card border-0 shadow-sm mb-6">
        <div class="card-body p-5">
            <div class="d-flex align-items-start gap-4">
                <div class="d-flex align-items-center justify-content-center bg-light-<?= $statusColor ?> rounded-2 flex-shrink-0" style="width:56px;height:56px;">
                    <i class="ki-duotone ki-questionnaire-tablet fs-2x text-<?= $statusColor ?>"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold text-gray-900 fs-4 mb-1"><?= esc($quiz['quizze_name']) ?></div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="badge badge-light-<?= $statusColor ?> fs-8"><?= esc($quiz['quizze_status']) ?></span>
                        <?php if ($quiz['quizze_duration'] > 0): ?>
                        <span class="text-muted fs-8">
                            <i class="ki-duotone ki-time fs-7 me-1"><span class="path1"></span><span class="path2"></span></i><?= $quiz['quizze_duration'] ?> min
                        </span>
                        <?php endif; ?>
                        <span class="text-muted fs-8">
                            <i class="ki-duotone ki-question-2 fs-7 me-1"><span class="path1"></span><span class="path2"></span></i><?= $qCount ?> question<?= $qCount !== 1 ? 's' : '' ?>
                        </span>
                        <span class="text-muted fs-8">
                            <i class="ki-duotone ki-people fs-7 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>0 attempts
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Quiz info card-->

    <!--begin::Questions card-->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-5 pb-0 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-question-2 fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                <div>
                    <h6 class="fw-bold text-gray-800 mb-0">Questions</h6>
                    <span class="text-muted fs-9" id="q_count_label"><?= $qCount ?> question<?= $qCount !== 1 ? 's' : '' ?></span>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_question">
                <i class="ki-duotone ki-plus fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                Add Question
            </button>
        </div>
        <div class="card-body pt-5 pb-6">
            <div id="questions_list">
            <?php if (empty($quiz['questions'])): ?>
            <div class="text-center py-10 text-muted" id="q_empty_state">
                <i class="ki-duotone ki-question-2 fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
                <div class="fs-6 fw-semibold mb-1">No questions yet</div>
                <div class="fs-8">Click "Add Question" to build this quiz.</div>
            </div>
            <?php else: ?>
            <?php $letters = ['A','B','C','D']; ?>
            <?php foreach ($quiz['questions'] as $qi => $q): ?>
            <div class="d-flex gap-4 pb-6 mb-6 border-bottom border-dashed border-gray-100<?= $qi === array_key_last($quiz['questions']) ? ' border-0 pb-0 mb-0' : '' ?>"
                 id="quiz_q_<?= $q['quizze_quest_id'] ?>">
                <!--begin::Number badge-->
                <div class="symbol symbol-35px flex-shrink-0">
                    <div class="symbol-label bg-light-primary fw-bold text-primary fs-7"><?= $qi + 1 ?></div>
                </div>
                <!--end::Number badge-->
                <!--begin::Question body-->
                <div class="flex-grow-1">
                    <div class="fw-semibold text-gray-800 fs-5 mb-4 disc-body-text clamped" id="qtext_<?= $q['quizze_quest_id'] ?>"><?= nl2br(esc($q['question'])) ?></div>
                    <button type="button" class="btn btn-link btn-sm p-0 mb-3 fs-8 fw-bold text-primary btn-show-more" data-target="qtext_<?= $q['quizze_quest_id'] ?>" style="display:none;">Show more</button>
                    <?php if (!empty($q['files'])): ?>
                    <div class="row g-2 mb-4">
                        <?php foreach ($q['files'] as $qf): ?>
                        <div class="col-6">
                            <a href="<?= base_url('uploads/quiz_files/' . $qf['file_src']) ?>" target="_blank" class="d-block">
                                <img src="<?= base_url('uploads/quiz_files/' . $qf['file_src']) ?>"
                                     class="rounded-2 border border-gray-100 w-100" style="aspect-ratio:4/3;width:100%;object-fit:cover;display:block;box-shadow:0 2px 8px rgba(0,0,0,.12);" alt="">
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <div class="row g-3">
                        <?php foreach ($q['answers'] as $ai => $ans): ?>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-2 border <?= $ans['is_correct_answer'] ? 'border-success bg-light-success' : 'border-gray-100' ?>">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 <?= $ans['is_correct_answer'] ? 'bg-success' : 'bg-light-secondary' ?>" style="width:28px;height:28px;min-width:28px;">
                                    <?php if ($ans['is_correct_answer']): ?>
                                    <i class="ki-duotone ki-check fs-8 text-white"><span class="path1"></span><span class="path2"></span></i>
                                    <?php else: ?>
                                    <span class="fw-bold text-muted fs-8"><?= $letters[$ai] ?></span>
                                    <?php endif; ?>
                                </div>
                                <span class="fs-6 <?= $ans['is_correct_answer'] ? 'fw-bold text-success' : 'text-gray-700' ?>"><?= esc($ans['answer']) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!--end::Question body-->
                <!--begin::Delete-->
                <button type="button" class="btn btn-icon btn-sm btn-light-danger flex-shrink-0 btn-del-question"
                        data-question-id="<?= $q['quizze_quest_id'] ?>">
                    <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                </button>
                <!--end::Delete-->
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <!--end::Questions card-->

</div>
</div>

<!--begin::Add Question Modal-->
<div class="modal fade" id="modal_add_question" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Add Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-5">
                <div class="mb-5">
                    <label class="form-label fw-semibold fs-7 required">Question</label>
                    <textarea class="form-control" id="aq_question" rows="3" placeholder="Type your question here..." maxlength="2000"></textarea>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-semibold fs-7">
                        Images <span class="text-muted fw-normal">(optional · max 4 · jpg, png, gif, webp)</span>
                    </label>
                    <div class="d-flex align-items-center gap-3 flex-wrap" id="aq_img_previews">
                        <label class="btn btn-light-primary btn-sm mb-0" id="aq_img_pick_label">
                            <i class="ki-duotone ki-picture fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Choose Images
                            <input type="file" id="aq_img_input" multiple accept=".jpg,.jpeg,.png,.gif,.webp" style="display:none;">
                        </label>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold fs-7 required">Answers <span class="text-muted fw-normal">(click a row to mark as correct)</span></label>
                    <div class="d-flex flex-column gap-3" id="aq_answers_area">
                        <?php foreach (['A','B','C','D'] as $ai => $letter): ?>
                        <div class="answer-row d-flex align-items-center gap-3 px-4 py-3 rounded-2 border border-gray-100 cursor-pointer"
                             data-index="<?= $ai ?>" onclick="selectCorrectAnswer(<?= $ai ?>)">
                            <input type="radio" name="aq_correct" class="form-check-input mt-0 flex-shrink-0"
                                   value="<?= $ai ?>" id="aq_radio_<?= $ai ?>" onclick="event.stopPropagation();selectCorrectAnswer(<?= $ai ?>)">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 answer-badge"
                                 id="aq_badge_<?= $ai ?>" style="width:28px;height:28px;min-width:28px;">
                                <span class="fw-bold fs-7"><?= $letter ?></span>
                            </div>
                            <input type="text" class="form-control form-control-sm border-0 bg-transparent p-0 flex-grow-1 aq-answer-input"
                                   id="aq_answer_<?= $ai ?>" placeholder="Answer <?= $letter ?>..." maxlength="260"
                                   onclick="event.stopPropagation()">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn_save_question">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Save Question
                    </span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Add Question Modal-->

<script>
const QUIZ_ID       = <?= (int) $quiz['lesson_quizze_id'] ?>;
const QUIZ_API_BASE = '<?= base_url("classroom/lesson/" . $lesson["lesson_id"] . "/quiz/" . $quiz["lesson_quizze_id"]) ?>';
const QUIZ_IMG_BASE = '<?= base_url("uploads/quiz_files/") ?>';
const QUIZ_LETTERS  = ['A','B','C','D'];

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function renderQuestionHtml(q, idx) {
    let imgHtml = '';
    if (q.files && q.files.length) {
        imgHtml = '<div class="row g-2 mb-4">';
        q.files.forEach(f => {
            imgHtml += `<div class="col-6"><a href="${QUIZ_IMG_BASE}${f.file_src}" target="_blank" class="d-block"><img src="${QUIZ_IMG_BASE}${f.file_src}" class="rounded-2 border border-gray-100 w-100" style="aspect-ratio:4/3;width:100%;object-fit:cover;display:block;box-shadow:0 2px 8px rgba(0,0,0,.12);" alt=""></a></div>`;
        });
        imgHtml += '</div>';
    }
    let ansHtml = '<div class="row g-3">';
    q.answers.forEach((ans, ai) => {
        const ok = parseInt(ans.is_correct_answer) === 1;
        ansHtml += `<div class="col-12 col-md-6"><div class="d-flex align-items-center gap-3 p-3 rounded-2 border ${ok ? 'border-success bg-light-success' : 'border-gray-100'}">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 ${ok ? 'bg-success' : 'bg-light-secondary'}" style="width:28px;height:28px;min-width:28px;">
                ${ok ? '<i class="ki-duotone ki-check fs-8 text-white"><span class="path1"></span><span class="path2"></span></i>' : `<span class="fw-bold text-muted fs-8">${QUIZ_LETTERS[ai]}</span>`}
            </div>
            <span class="fs-6 ${ok ? 'fw-bold text-success' : 'text-gray-700'}">${escHtml(ans.answer)}</span>
        </div></div>`;
    });
    ansHtml += '</div>';
    const isLast = false;
    return `<div class="d-flex gap-4 pb-6 mb-6 border-bottom border-dashed border-gray-100" id="quiz_q_${q.quizze_quest_id}">
        <div class="symbol symbol-35px flex-shrink-0"><div class="symbol-label bg-light-primary fw-bold text-primary fs-7">${idx + 1}</div></div>
        <div class="flex-grow-1">
            <div class="fw-semibold text-gray-800 fs-5 mb-4 disc-body-text clamped" id="qtext_${q.quizze_quest_id}">${escHtml(q.question).replace(/\n/g,'<br>')}</div>
            <button type="button" class="btn btn-link btn-sm p-0 mb-3 fs-8 fw-bold text-primary btn-show-more" data-target="qtext_${q.quizze_quest_id}" style="display:none;">Show more</button>
            ${imgHtml}${ansHtml}
        </div>
        <button type="button" class="btn btn-icon btn-sm btn-light-danger flex-shrink-0 btn-del-question" data-question-id="${q.quizze_quest_id}">
            <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
        </button>
    </div>`;
}

// ── ADD QUESTION ──────────────────────────────────────────────
document.getElementById('btn_add_question').addEventListener('click', function() {
    document.getElementById('aq_question').value = '';
    document.querySelectorAll('.aq-answer-input').forEach(i => i.value = '');
    document.querySelectorAll('.answer-row').forEach(r => {
        r.classList.remove('border-success','bg-light-success'); r.classList.add('border-gray-100');
    });
    document.querySelectorAll('.answer-badge').forEach(b => {
        b.classList.remove('bg-success'); b.classList.add('bg-light-secondary');
        const s = b.querySelector('span'); if (s) s.style.color = '';
    });
    document.querySelectorAll('input[name="aq_correct"]').forEach(r => r.checked = false);
    document.getElementById('aq_img_previews').querySelectorAll('.aq-img-thumb').forEach(e => e.remove());
    document.getElementById('aq_img_pick_label').style.display = '';
    aqSelectedFiles = [];
    new bootstrap.Modal(document.getElementById('modal_add_question')).show();
});

function selectCorrectAnswer(idx) {
    document.querySelectorAll('.answer-row').forEach(function(row, i) {
        const badge = row.querySelector('.answer-badge');
        if (i === idx) {
            row.classList.add('border-success','bg-light-success'); row.classList.remove('border-gray-100');
            badge.classList.add('bg-success'); badge.classList.remove('bg-light-secondary');
            const s = badge.querySelector('span'); if (s) s.style.color = '#fff';
            document.getElementById('aq_radio_' + i).checked = true;
        } else {
            row.classList.remove('border-success','bg-light-success'); row.classList.add('border-gray-100');
            badge.classList.remove('bg-success'); badge.classList.add('bg-light-secondary');
            const s = badge.querySelector('span'); if (s) s.style.color = '';
        }
    });
}

let aqSelectedFiles = [];
document.getElementById('aq_img_input').addEventListener('change', function() {
    const allowed = ['jpg','jpeg','png','gif','webp'];
    aqSelectedFiles = Array.from(this.files).filter(f => allowed.includes(f.name.split('.').pop().toLowerCase())).slice(0, 4);
    this.value = '';
    const area = document.getElementById('aq_img_previews');
    const label = document.getElementById('aq_img_pick_label');
    area.querySelectorAll('.aq-img-thumb').forEach(e => e.remove());
    aqSelectedFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.createElement('div');
            wrap.className = 'aq-img-thumb position-relative'; wrap.style.cssText = 'width:70px;height:70px;';
            wrap.innerHTML = `<img src="${e.target.result}" class="rounded-2 border border-gray-100 w-100 h-100" style="object-fit:cover;">
                <button type="button" class="btn btn-icon btn-danger position-absolute top-0 end-0" style="width:18px;height:18px;min-width:0;padding:0;font-size:9px;" data-idx="${idx}">×</button>`;
            wrap.querySelector('button').addEventListener('click', function() {
                aqSelectedFiles.splice(parseInt(this.dataset.idx), 1); wrap.remove();
                if (aqSelectedFiles.length < 4) label.style.display = '';
            });
            area.insertBefore(wrap, label);
        };
        reader.readAsDataURL(file);
    });
    label.style.display = aqSelectedFiles.length >= 4 ? 'none' : '';
});

document.getElementById('btn_save_question').addEventListener('click', function() {
    const btn      = this;
    const question = document.getElementById('aq_question').value.trim();
    const correct  = document.querySelector('input[name="aq_correct"]:checked');
    if (!question) { document.getElementById('aq_question').classList.add('is-invalid'); return; }
    document.getElementById('aq_question').classList.remove('is-invalid');
    const answers = Array.from(document.querySelectorAll('.aq-answer-input')).map(i => i.value.trim());
    if (answers.filter(a => a !== '').length < 2) {
        Swal.fire({ title: 'Answers required', text: 'Fill in at least 2 answers.', icon: 'warning', buttonsStyling: false,
            confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } }); return;
    }
    if (!correct) {
        Swal.fire({ title: 'Select correct answer', text: 'Click a row to mark the correct answer.', icon: 'warning', buttonsStyling: false,
            confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-warning' } }); return;
    }
    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;
    const fd = new FormData();
    fd.append('question',       question);
    fd.append('correct_answer', correct.value);
    answers.forEach((a, i) => fd.append('answers[' + i + ']', a));
    aqSelectedFiles.forEach(f => fd.append('question_images[]', f));
    $.ajax({
        url: QUIZ_API_BASE + '/question/store', type: 'POST', data: fd, processData: false, contentType: false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('modal_add_question')).hide();
                document.getElementById('q_empty_state')?.remove();
                const list    = document.getElementById('questions_list');
                const current = list.querySelectorAll('[id^="quiz_q_"]').length;
                list.insertAdjacentHTML('beforeend', renderQuestionHtml(res.question, current));
                initShowMore(document.getElementById('qtext_' + res.question.quizze_quest_id));
                const newCnt = current + 1;
                document.getElementById('q_count_label').textContent = newCnt + ' question' + (newCnt !== 1 ? 's' : '');
            } else {
                Swal.fire({ title: 'Failed', text: res.message, icon: 'error', buttonsStyling: false,
                    confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-danger' } });
            }
        }
    });
});

// ── DELETE QUESTION ───────────────────────────────────────────
$(document).on('click', '.btn-del-question', function() {
    const questionId = $(this).data('question-id');
    Swal.fire({ title: 'Remove this question?', icon: 'warning', showCancelButton: true, buttonsStyling: false,
        confirmButtonText: 'Yes, remove', cancelButtonText: 'Cancel',
        customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(QUIZ_API_BASE + '/question/' + questionId + '/delete', function(res) {
            if (res.success) {
                document.getElementById('quiz_q_' + questionId)?.remove();
                const list   = document.getElementById('questions_list');
                const remain = list.querySelectorAll('[id^="quiz_q_"]').length;
                list.querySelectorAll('[id^="quiz_q_"]').forEach((el, i) => {
                    const badge = el.querySelector('.symbol-label');
                    if (badge) badge.textContent = i + 1;
                });
                if (remain === 0) {
                    list.innerHTML = '<div class="text-center py-10 text-muted" id="q_empty_state">' +
                        '<i class="ki-duotone ki-question-2 fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>' +
                        '<div class="fs-6 fw-semibold mb-1">No questions yet</div>' +
                        '<div class="fs-8">Click "Add Question" to build this quiz.</div></div>';
                }
                document.getElementById('q_count_label').textContent = remain + ' question' + (remain !== 1 ? 's' : '');
            }
        }, 'json');
    });
});

// ── SHOW MORE / SHOW LESS ─────────────────────────────────────
function initShowMore(el) {
    if (!el) return;
    const btn = el.nextElementSibling;
    if (!btn || !btn.classList.contains('btn-show-more')) return;
    el.classList.remove('clamped');
    const fullH = el.scrollHeight;
    el.classList.add('clamped');
    if (fullH > el.clientHeight + 2) btn.style.display = '';
}
$(document).on('click', '.btn-show-more', function() {
    const el = document.getElementById(this.dataset.target);
    if (!el) return;
    if (el.classList.contains('clamped')) { el.classList.remove('clamped'); this.textContent = 'Show less'; }
    else { el.classList.add('clamped'); this.textContent = 'Show more'; }
});
document.querySelectorAll('.disc-body-text').forEach(initShowMore);
</script>

<style>
.answer-row { cursor:pointer; transition:background .15s, border-color .15s; }
.answer-row:hover { background:#f9f9fb; }
.answer-badge { transition:background .15s; background:var(--kt-light-secondary,#f1f3f4); }
.aq-answer-input:focus { outline:none; box-shadow:none; }
.disc-body-text.clamped { display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }
.btn-show-more { text-decoration:none; }
.btn-show-more:hover { text-decoration:underline; }
</style>
