<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Class Exam Review — <?= esc($classroom['class_name']) ?> (<?= esc($classroom['class_year']) ?>)
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Exam Review</li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/my') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php
$isReadonly    = !($isActive ?? true);
$canEnterMarks = $canEnterMarks ?? false;
$canSubmitReport = $canSubmitReport ?? false;
$markEditable  = $canEnterMarks && !$isReadonly;
?>
<?php if ($isReadonly): ?>
<div class="d-flex align-items-center gap-2 px-6 py-3 mb-6 rounded-2" style="background:#fff8e1;border:1px solid #ffe082;">
    <i class="ki-duotone ki-lock-2 fs-4 text-warning"><span class="path1"></span><span class="path2"></span></i>
    <span class="fs-7 fw-semibold text-warning">Read-only — this classroom is no longer active. No comments or submissions can be made.</span>
</div>
<?php endif; ?>

<!--begin::Term Tabs-->
<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold mb-6 border-0">
    <?php foreach ([1,2,3] as $t): ?>
    <li class="nav-item">
        <a class="nav-link <?= $t==$term?'active':'' ?> text-active-primary pb-4"
           href="<?= base_url('classroom/class-exam/'.$classId.'/term/'.$t) ?>">
            Term <?= $t ?>
            <?php
            $rs = \App\Models\TermExamModel::class;
            // status badge shown via PHP (we only have the current term's status loaded)
            ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>

<?php
$isLocked   = in_array($reportStatus['status'], ['ct_submitted','published']);
$isPublished= $reportStatus['status'] === 'published';
$ctSubmitted= $reportStatus['status'] === 'ct_submitted';
?>

<?php if ($isPublished): ?>
<div class="alert alert-success d-flex align-items-center gap-2 mb-5">
    <i class="ki-duotone ki-check-circle fs-2 text-success"><span class="path1"></span><span class="path2"></span></i>
    <span class="fw-semibold">Term <?= $term ?> report has been published by the Principal.</span>
</div>
<?php elseif ($ctSubmitted): ?>
<div class="alert alert-warning d-flex align-items-center gap-2 mb-5">
    <i class="ki-duotone ki-clock fs-2 text-warning"><span class="path1"></span><span class="path2"></span></i>
    <span class="fw-semibold">Submitted to Principal on <?= $reportStatus['ct_submitted_at'] ? date('d M Y', strtotime($reportStatus['ct_submitted_at'])) : '—' ?>. Waiting for Principal.</span>
</div>
<?php endif; ?>

<!--begin::Student rows-->
<?php if (empty($students)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-16 text-muted">
        <i class="ki-duotone ki-people fs-4x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
        <div class="fs-6 fw-semibold">No students enrolled.</div>
    </div>
</div>
<?php else: ?>

<!-- Summary bar -->
<?php
$totalStudents    = count($students);
$ctCommentsDone   = count(array_filter($students, fn($s) => !empty($s['ct_comment'])));
$allMarksDone     = count(array_filter($students, fn($s) => $s['subjects_entered'] === $s['subjects_count'] && $s['subjects_count'] > 0));
?>
<div class="row g-4 mb-6">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4">
            <div class="fw-bold fs-2x text-primary"><?= $totalStudents ?></div>
            <div class="text-muted fs-8">Students</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4">
            <div class="fw-bold fs-2x text-<?= count($subjects)>0?'success':'warning' ?>"><?= count($subjects) ?></div>
            <div class="text-muted fs-8">Subjects</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4">
            <div class="fw-bold fs-2x text-<?= $ctCommentsDone===$totalStudents?'success':'warning' ?>"><?= $ctCommentsDone ?>/<?= $totalStudents ?></div>
            <div class="text-muted fs-8">CT Comments Done</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-4">
            <?php if ($isReadonly): ?>
            <button class="btn btn-secondary btn-sm w-100" disabled>
                <i class="ki-duotone ki-lock fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                Submit to Principal
            </button>
            <div class="text-warning fw-semibold fs-9 mt-1">Classroom inactive</div>
            <?php elseif (!$isLocked && $canSubmitReport): ?>
                <?php if ($canSubmit): ?>
                <button id="btn_ct_submit" class="btn btn-primary btn-sm w-100"
                        data-class="<?= $classId ?>" data-term="<?= $term ?>">
                    <i class="ki-duotone ki-send fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Submit to Principal
                </button>
                <div class="text-muted fs-9 mt-1">Submit Term <?= $term ?> to Principal</div>
                <?php else: ?>
                <button class="btn btn-secondary btn-sm w-100" disabled title="All core subject marks must be recorded before submitting">
                    <i class="ki-duotone ki-lock fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Submit to Principal
                </button>
                <div class="text-warning fw-semibold fs-9 mt-1">Core marks incomplete</div>
                <?php endif; ?>
            <?php elseif (!$isLocked && !$canSubmitReport): ?>
            <div class="fw-bold fs-7 text-info">Assistant Class Teacher</div>
            <div class="text-muted fs-9 mt-1">Only the Class Teacher can submit to Principal</div>
            <?php elseif ($ctSubmitted): ?>
            <div class="fw-bold fs-7 text-warning">Awaiting Principal</div>
            <a href="<?= base_url('classroom/principal-exam/'.$classId.'/term/'.$term) ?>"
               class="btn btn-sm btn-light-warning mt-2">Principal View</a>
            <?php else: ?>
            <div class="fw-bold fs-7 text-success">Published ✓</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!$isLocked && !$canSubmit && !empty($coreSubjectStatus)): ?>
<div class="alert alert-warning d-flex align-items-start gap-3 mb-5">
    <i class="ki-duotone ki-information-5 fs-2 text-warning mt-1 flex-shrink-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
    <div class="flex-grow-1">
        <div class="fw-bold text-gray-800 fs-7 mb-2">Submit is locked — core subject marks are not yet complete</div>
        <div class="row g-2">
        <?php foreach ($coreSubjectStatus as $cs): ?>
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <?php if ($cs['complete']): ?>
                    <i class="ki-duotone ki-check-circle fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
                    <?php else: ?>
                    <i class="ki-duotone ki-cross-circle fs-5 text-danger"><span class="path1"></span><span class="path2"></span></i>
                    <?php endif; ?>
                    <span class="fs-8 <?= $cs['complete'] ? 'text-muted' : 'fw-semibold text-gray-800' ?>">
                        <?= esc($cs['subject_name']) ?>
                        <span class="badge badge-light-<?= $cs['complete'] ? 'success' : 'danger' ?> ms-1 fs-10">
                            <?= $cs['entered'] ?>/<?= $cs['needed'] ?>
                        </span>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php foreach ($students as $stu):
    $sid      = $stu['user_id'];
    $initials = strtoupper(substr($stu['fname'],0,1).substr($stu['lname'],0,1));
    $pct      = $stu['overall_pct'];
    $grade    = $pct !== null ? \App\Models\TermExamModel::grade($pct) : null;
    $gColor   = $grade ? \App\Models\TermExamModel::gradeColor($grade) : 'secondary';
?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header border-0 pt-4 pb-3 px-5">
        <div class="d-flex align-items-center gap-3">
            <?php if (!empty($stu['profile_photo'])): ?>
            <img src="<?= base_url('uploads/profilePhoto/'.$stu['profile_photo']) ?>"
                 class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" />
            <?php else: ?>
            <div class="symbol symbol-40px"><div class="symbol-label bg-light-primary fw-bold text-primary fs-7"><?= $initials ?></div></div>
            <?php endif; ?>
            <div class="flex-grow-1">
                <div class="fw-bold text-gray-800 fs-6"><?= esc($stu['fname'].' '.$stu['lname']) ?></div>
                <div class="text-muted fs-9">
                    <?= $stu['subjects_entered'] ?>/<?= $stu['subjects_count'] ?> subjects entered
                    <?php if ($pct !== null): ?>
                    · Overall: <span class="fw-semibold badge badge-light-<?= $gColor ?>"><?= $pct ?>% — <?= $grade ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?= base_url('classroom/report/'.$classId.'/student/'.$sid.'/term/'.$term) ?>"
               class="btn btn-sm btn-light-info" target="_blank">
                <i class="ki-duotone ki-eye fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Preview
            </a>
        </div>
    </div>
    <div class="card-body px-5 pb-4 pt-0">
        <!-- Marks table (editable if CT/ACT and not locked) -->
        <div class="table-responsive mb-4">
            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-2 fs-8">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>Subject</th>
                        <th>Teacher</th>
                        <?php if ($markEditable && !$isLocked): ?>
                        <th class="text-center" style="min-width:60px;">Absent</th>
                        <th class="text-center" style="min-width:80px;">Mark</th>
                        <th class="text-center" style="min-width:70px;">Out Of</th>
                        <th class="text-center" style="min-width:55px;">%</th>
                        <th class="text-center" style="min-width:55px;">Grade</th>
                        <th style="min-width:180px;">Teacher Comment</th>
                        <th class="text-center" style="min-width:70px;"></th>
                        <?php else: ?>
                        <th class="text-center">Mark</th>
                        <th class="text-center">Out Of</th>
                        <th class="text-center">%</th>
                        <th class="text-center">Grade</th>
                        <th>Teacher Comment</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($stu['subjects'] as $sub):
                    $absent = !empty($sub['is_absent']);
                    $sm     = $sub['mark'];
                    $st     = $sub['total_mark'];
                    $sp     = (!$absent && $sm !== null && $st > 0) ? round(($sm/$st)*100,1) : null;
                    $sg     = $absent ? 'ABS' : ($sp !== null ? \App\Models\TermExamModel::grade($sp) : '—');
                    $sgc    = $absent ? 'danger' : ($sp !== null ? \App\Models\TermExamModel::gradeColor($sg) : 'secondary');
                    $csid   = $sub['class_sub_id'];
                ?>
                <?php if ($markEditable && !$isLocked): ?>
                <tr class="<?= $absent ? 'bg-light-danger' : '' ?>" id="mark-row-<?= $sid ?>-<?= $csid ?>">
                    <td class="fw-semibold"><?= esc($sub['subject_name']) ?></td>
                    <td class="text-muted fs-9"><?= esc($sub['teacher_name'] ?? '—') ?></td>
                    <td class="text-center">
                        <div class="form-check form-check-sm d-flex justify-content-center">
                            <input type="checkbox" class="form-check-input mark-absent-cb"
                                   id="abs-<?= $sid ?>-<?= $csid ?>"
                                   <?= $absent ? 'checked' : '' ?>
                                   data-sid="<?= $sid ?>" data-csid="<?= $csid ?>">
                        </div>
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.5" min="0"
                               class="form-control form-control-sm text-center mark-val-input"
                               style="width:70px;margin:auto;"
                               id="mark-<?= $sid ?>-<?= $csid ?>"
                               value="<?= $sm !== null ? $sm : '' ?>"
                               placeholder="—"
                               <?= $absent ? 'disabled' : '' ?>
                               data-sid="<?= $sid ?>" data-csid="<?= $csid ?>">
                    </td>
                    <td class="text-center">
                        <input type="number" step="1" min="1"
                               class="form-control form-control-sm text-center total-val-input"
                               style="width:65px;margin:auto;"
                               id="total-<?= $sid ?>-<?= $csid ?>"
                               value="<?= $st ?: 100 ?>"
                               <?= $absent ? 'disabled' : '' ?>
                               data-sid="<?= $sid ?>" data-csid="<?= $csid ?>">
                    </td>
                    <td class="text-center fw-bold fs-9" id="pct-<?= $sid ?>-<?= $csid ?>">
                        <?= $absent ? '<span class="text-danger">ABS</span>' : ($sp !== null ? $sp.'%' : '—') ?>
                    </td>
                    <td class="text-center" id="grade-<?= $sid ?>-<?= $csid ?>">
                        <span class="badge badge-light-<?= $sgc ?> fs-9"><?= $sg ?></span>
                    </td>
                    <td>
                        <input type="text" maxlength="500"
                               class="form-control form-control-sm comment-val-input"
                               id="cmt-<?= $sid ?>-<?= $csid ?>"
                               value="<?= esc($sub['teacher_comment'] ?? '') ?>"
                               placeholder="Teacher comment…"
                               data-sid="<?= $sid ?>" data-csid="<?= $csid ?>">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-light-primary btn-save-mark"
                                style="width:30px;height:30px;"
                                title="Save mark"
                                data-sid="<?= $sid ?>" data-csid="<?= $csid ?>" data-term="<?= $term ?>" data-class="<?= $classId ?>">
                            <i class="ki-duotone ki-check fs-6"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </td>
                </tr>
                <?php else: ?>
                <tr class="<?= $absent ? 'bg-light-danger' : '' ?>">
                    <td class="fw-semibold"><?= esc($sub['subject_name']) ?></td>
                    <td class="text-muted"><?= esc($sub['teacher_name'] ?? '—') ?></td>
                    <td class="text-center fw-bold"><?= $absent ? '<span class="badge badge-light-danger fs-9">ABS</span>' : ($sm !== null ? $sm : '<span class="text-muted">—</span>') ?></td>
                    <td class="text-center text-muted"><?= $absent ? '—' : $st ?></td>
                    <td class="text-center"><?= $absent ? '<span class="text-danger fw-bold">ABS</span>' : ($sp !== null ? $sp.'%' : '—') ?></td>
                    <td class="text-center"><span class="badge badge-light-<?= $sgc ?> fs-9"><?= $sg ?></span></td>
                    <td class="text-muted fst-italic"><?= $sub['teacher_comment'] ? esc($sub['teacher_comment']) : '' ?></td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Class Teacher Comment -->
        <div class="d-flex align-items-start gap-3">
            <div class="flex-grow-1">
                <label class="form-label fw-semibold fs-8 mb-1 required">Class Teacher Comment</label>
                <?php if ($isLocked || $isReadonly): ?>
                <div class="p-3 rounded-2 bg-light-primary fs-8 lh-lg">
                    <?= $stu['ct_comment'] ? nl2br(esc($stu['ct_comment'])) : '<span class="text-muted fst-italic">No comment.</span>' ?>
                </div>
                <?php else: ?>
                <textarea class="form-control form-control-sm ct-comment-area" rows="2" maxlength="1000"
                          data-sid="<?= $sid ?>" data-term="<?= $term ?>"
                          placeholder="Write your comment for this student..."><?= esc($stu['ct_comment'] ?? '') ?></textarea>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <span class="text-muted fs-9">Max 1000 characters</span>
                    <button class="btn btn-sm btn-light-primary btn-save-ct-comment"
                            data-sid="<?= $sid ?>" data-class="<?= $classId ?>" data-term="<?= $term ?>">
                        <i class="ki-duotone ki-check fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Save Comment
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php endif; // empty students ?>

</div>
</div>

<script>
const CT_COMMENT_URL  = '<?= base_url('classroom/class-exam/'.$classId.'/comment') ?>';
const CT_SUBMIT_URL   = '<?= base_url('classroom/class-exam/'.$classId.'/term/'.$term.'/submit') ?>';
const CT_SAVE_MARK_URL = '<?= base_url('classroom/class-exam/'.$classId.'/save-mark') ?>';

const CtToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    didOpen: t => {
        t.addEventListener('mouseenter', Swal.stopTimer);
        t.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

$(document).on('click', '.btn-save-ct-comment', function() {
    const btn     = $(this);
    const sid     = btn.data('sid');
    const term    = btn.data('term');
    const area    = $(`.ct-comment-area[data-sid="${sid}"]`);
    const comment = area.val().trim();

    if (!comment) {
        CtToast.fire({ icon: 'warning', title: 'Comment is required' });
        area.focus();
        return;
    }
    if (comment.length < 4) {
        CtToast.fire({ icon: 'warning', title: 'Comment must be at least 4 characters' });
        area.focus();
        return;
    }

    btn.attr('data-kt-indicator','on').prop('disabled', true);
    $.post(CT_COMMENT_URL, {student_id: sid, term, comment}, res => {
        btn.removeAttr('data-kt-indicator').prop('disabled', false);
        if (res.success) {
            CtToast.fire({ icon: 'success', title: 'Comment saved successfully' });
            btn.html('<i class="ki-duotone ki-check fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Save Comment');
        } else {
            CtToast.fire({ icon: 'error', title: res.message });
        }
    }).fail(() => {
        btn.removeAttr('data-kt-indicator').prop('disabled', false);
        CtToast.fire({ icon: 'error', title: 'Failed to save comment' });
    });
});

document.getElementById('btn_ct_submit')?.addEventListener('click', function() {
    Swal.fire({
        title: 'Submit to Principal?',
        html: '<p class="text-muted fs-7">Once submitted, marks and comments will be locked and sent to the Principal for finalisation.</p>',
        icon: 'question', showCancelButton: true,
        confirmButtonText: 'Yes, Submit', cancelButtonText: 'Cancel',
        buttonsStyling: false,
        customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(CT_SUBMIT_URL, {}, res => {
            if (res.success) {
                Swal.fire({ title: 'Submitted!', text: res.message, icon: 'success', timer: 2000, showConfirmButton: false })
                    .then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
});

// Grade helper (mirrors PHP TermExamModel::grade/gradeColor)
function calcGrade(pct) {
    if (pct === null) return { grade: '—', color: 'secondary' };
    const g = pct >= 90 ? 'A+' : pct >= 80 ? 'A' : pct >= 70 ? 'B' : pct >= 60 ? 'C' : pct >= 50 ? 'D' : 'F';
    const c = pct >= 70 ? 'success' : pct >= 50 ? 'warning' : 'danger';
    return { grade: g, color: c };
}

// Toggle absent: disable mark/total inputs, update % and grade cells
$(document).on('change', '.mark-absent-cb', function() {
    const sid  = $(this).data('sid');
    const csid = $(this).data('csid');
    const isAbsent = this.checked;
    const markInput  = $(`#mark-${sid}-${csid}`);
    const totalInput = $(`#total-${sid}-${csid}`);
    const pctCell    = $(`#pct-${sid}-${csid}`);
    const gradeCell  = $(`#grade-${sid}-${csid}`);
    markInput.prop('disabled', isAbsent);
    totalInput.prop('disabled', isAbsent);
    if (isAbsent) {
        pctCell.html('<span class="text-danger">ABS</span>');
        gradeCell.html('<span class="badge badge-light-danger fs-9">ABS</span>');
    } else {
        pctCell.html('—');
        gradeCell.html('<span class="badge badge-light-secondary fs-9">—</span>');
    }
    $(`#mark-row-${sid}-${csid}`).toggleClass('bg-light-danger', isAbsent);
});

// Real-time % recalculation on mark/total change
$(document).on('input', '.mark-val-input, .total-val-input', function() {
    const sid  = $(this).data('sid');
    const csid = $(this).data('csid');
    const mark  = parseFloat($(`#mark-${sid}-${csid}`).val());
    const total = parseFloat($(`#total-${sid}-${csid}`).val());
    const pctCell   = $(`#pct-${sid}-${csid}`);
    const gradeCell = $(`#grade-${sid}-${csid}`);
    if (!isNaN(mark) && !isNaN(total) && total > 0) {
        const pct = Math.round((mark / total) * 1000) / 10;
        const { grade, color } = calcGrade(pct);
        pctCell.html(pct + '%');
        gradeCell.html(`<span class="badge badge-light-${color} fs-9">${grade}</span>`);
    } else {
        pctCell.html('—');
        gradeCell.html('<span class="badge badge-light-secondary fs-9">—</span>');
    }
});

// Save mark
$(document).on('click', '.btn-save-mark', function() {
    const btn   = $(this);
    const sid   = btn.data('sid');
    const csid  = btn.data('csid');
    const term  = btn.data('term');
    const isAbsent = $(`#abs-${sid}-${csid}`).is(':checked') ? 1 : 0;
    const mark     = $(`#mark-${sid}-${csid}`).val();
    const total    = $(`#total-${sid}-${csid}`).val() || 100;
    const comment  = $(`#cmt-${sid}-${csid}`).val().trim();

    btn.attr('data-kt-indicator', 'on').prop('disabled', true);
    $.post(CT_SAVE_MARK_URL, {
        class_sub_id:    csid,
        student_id:      sid,
        term:            term,
        is_absent:       isAbsent,
        mark:            mark,
        total_mark:      total,
        teacher_comment: comment,
    }, res => {
        btn.removeAttr('data-kt-indicator').prop('disabled', false);
        if (res.success) {
            CtToast.fire({ icon: 'success', title: 'Mark saved' });
        } else {
            CtToast.fire({ icon: 'error', title: res.message || 'Failed to save' });
        }
    }).fail(() => {
        btn.removeAttr('data-kt-indicator').prop('disabled', false);
        CtToast.fire({ icon: 'error', title: 'Request failed' });
    });
});
</script>
