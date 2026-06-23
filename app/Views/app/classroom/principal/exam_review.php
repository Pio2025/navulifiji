<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Principal Review — <?= esc($classroom['class_name']) ?> (<?= esc($classroom['class_year']) ?>)
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom') ?>" class="text-muted text-hover-primary">Classrooms</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Principal Review</li>
            </ul>
        </div>
        <a href="<?= base_url('classroom') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<!--begin::Term Tabs-->
<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold mb-6 border-0">
    <?php foreach ([1,2,3] as $t): ?>
    <li class="nav-item">
        <a class="nav-link <?= $t==$term?'active':'' ?> text-active-primary pb-4"
           href="<?= base_url('classroom/principal-exam/'.$classId.'/term/'.$t) ?>">Term <?= $t ?></a>
    </li>
    <?php endforeach; ?>
</ul>

<?php
$isPublished = $reportStatus['status'] === 'published';
$canAct      = $reportStatus['status'] === 'ct_submitted';
?>

<?php if ($isPublished): ?>
<div class="alert alert-success d-flex align-items-center gap-2 mb-5">
    <i class="ki-duotone ki-check-circle fs-2 text-success"><span class="path1"></span><span class="path2"></span></i>
    <div>
        <div class="fw-semibold">Term <?= $term ?> report is published.</div>
        <div class="text-muted fs-9">Published on <?= $reportStatus['published_at'] ? date('d M Y, g:i A', strtotime($reportStatus['published_at'])) : '—' ?></div>
    </div>
</div>
<?php elseif (!$canAct): ?>
<div class="alert alert-info d-flex align-items-center gap-2 mb-5">
    <i class="ki-duotone ki-information fs-2 text-info"><span class="path1"></span><span class="path2"></span></i>
    <span class="fw-semibold">Waiting for Class Teacher to submit Term <?= $term ?> report.</span>
</div>
<?php endif; ?>

<?php if (empty($students)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-16 text-muted">
        <div class="fs-6 fw-semibold">No students enrolled.</div>
    </div>
</div>
<?php else: ?>

<!-- Summary + Publish button -->
<?php
$totalStudents  = count($students);
$prcDone        = count(array_filter($students, fn($s) => !empty($s['principal_comment'])));
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
            <div class="fw-bold fs-2x text-<?= $prcDone===$totalStudents?'success':'warning' ?>"><?= $prcDone ?>/<?= $totalStudents ?></div>
            <div class="text-muted fs-8">Principal Comments</div>
        </div>
    </div>
    <div class="col-md-3 offset-md-3">
        <?php if (!$isPublished): ?>
        <div class="card border-0 shadow-sm p-4 text-center">
            <button id="btn_publish" class="btn btn-success btn-sm w-100 <?= !$canAct?'disabled':'' ?>"
                    data-class="<?= $classId ?>" data-term="<?= $term ?>"
                    <?= !$canAct?'disabled':'' ?>>
                <i class="ki-duotone ki-rocket fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                Publish Report
            </button>
            <div class="text-muted fs-9 mt-1"><?= $canAct?'Publish Term '.$term.' for all students':'Requires CT submission first' ?></div>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm p-4 text-center">
            <div class="fw-bold text-success fs-7">✓ Published</div>
            <a href="<?= base_url('classroom/class-exam/'.$classId.'/term/'.$term) ?>"
               class="btn btn-sm btn-light-success mt-2">Class Teacher View</a>
        </div>
        <?php endif; ?>
    </div>
</div>

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
                    <?php if ($pct !== null): ?>
                    Overall: <span class="fw-semibold badge badge-light-<?= $gColor ?>"><?= $pct ?>% — <?= $grade ?></span>
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
        <!-- Marks table -->
        <div class="table-responsive mb-3">
            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-2 fs-8">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>Subject</th><th class="text-center">Mark</th><th class="text-center">%</th><th class="text-center">Grade</th><th>Teacher Comment</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($stu['subjects'] as $sub):
                    $absent = !empty($sub['is_absent']);
                    $sm  = $sub['mark'];
                    $st  = $sub['total_mark'];
                    $sp  = (!$absent && $sm !== null && $st > 0) ? round(($sm/$st)*100,1) : null;
                    $sg  = $absent ? 'ABS' : ($sp !== null ? \App\Models\TermExamModel::grade($sp) : '—');
                    $sgc = $absent ? 'danger' : ($sp !== null ? \App\Models\TermExamModel::gradeColor($sg) : 'secondary');
                ?>
                <tr class="<?= $absent?'bg-light-danger':'' ?>">
                    <td class="fw-semibold"><?= esc($sub['subject_name']) ?></td>
                    <td class="text-center fw-bold"><?= $absent ? '<span class="badge badge-light-danger fs-9">ABS</span>' : ($sm !== null ? $sm.'/'.$st : '<span class="text-muted">—</span>') ?></td>
                    <td class="text-center"><?= $absent ? '<span class="text-danger fw-bold">ABS</span>' : ($sp !== null ? $sp.'%' : '—') ?></td>
                    <td class="text-center"><span class="badge badge-light-<?= $sgc ?> fs-9"><?= $sg ?></span></td>
                    <td class="text-muted fst-italic fs-9"><?= $sub['teacher_comment'] ? esc($sub['teacher_comment']) : '' ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- CT Comment (read-only) -->
        <?php if ($stu['ct_comment']): ?>
        <div class="mb-3">
            <div class="fw-semibold text-gray-600 fs-8 mb-1">Class Teacher Comment</div>
            <div class="p-3 rounded-2 bg-light-primary fs-8 lh-lg"><?= nl2br(esc($stu['ct_comment'])) ?></div>
        </div>
        <?php endif; ?>

        <!-- Principal Comment -->
        <div>
            <label class="form-label fw-semibold fs-8 mb-1">Principal Comment</label>
            <?php if ($isPublished): ?>
            <div class="p-3 rounded-2 bg-light-success fs-8 lh-lg">
                <?= $stu['principal_comment'] ? nl2br(esc($stu['principal_comment'])) : '<span class="text-muted fst-italic">No comment.</span>' ?>
            </div>
            <?php else: ?>
            <textarea class="form-control form-control-sm prc-comment-area" rows="2" maxlength="1000"
                      data-sid="<?= $sid ?>"
                      placeholder="Write your principal comment for this student..."
                      <?= !$canAct?'disabled':'' ?>><?= esc($stu['principal_comment'] ?? '') ?></textarea>
            <?php if ($canAct): ?>
            <div class="d-flex justify-content-end mt-1">
                <button class="btn btn-sm btn-light-primary btn-save-prc"
                        data-sid="<?= $sid ?>" data-class="<?= $classId ?>" data-term="<?= $term ?>">
                    <i class="ki-duotone ki-check fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Save
                </button>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php endif; // empty students ?>

</div>
</div>

<script>
const PRC_COMMENT_URL = '<?= base_url('classroom/principal-exam/'.$classId.'/comment') ?>';
const PRC_PUBLISH_URL = '<?= base_url('classroom/principal-exam/'.$classId.'/term/'.$term.'/publish') ?>';

const PrcToast = Swal.mixin({
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

$(document).on('click', '.btn-save-prc', function() {
    const btn     = $(this);
    const sid     = btn.data('sid');
    const term    = btn.data('term');
    const area    = $(`.prc-comment-area[data-sid="${sid}"]`);
    const comment = area.val().trim();

    if (!comment) {
        PrcToast.fire({ icon: 'warning', title: 'Comment is required' });
        area.focus();
        return;
    }
    if (comment.length < 4) {
        PrcToast.fire({ icon: 'warning', title: 'Comment must be at least 4 characters' });
        area.focus();
        return;
    }

    btn.attr('data-kt-indicator','on').prop('disabled', true);
    $.post(PRC_COMMENT_URL, {student_id: sid, term, comment}, res => {
        btn.removeAttr('data-kt-indicator').prop('disabled', false);
        if (res.success) {
            PrcToast.fire({ icon: 'success', title: 'Comment saved successfully' });
            btn.html('<i class="ki-duotone ki-check fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Save');
        } else {
            PrcToast.fire({ icon: 'error', title: res.message });
        }
    }).fail(() => {
        btn.removeAttr('data-kt-indicator').prop('disabled', false);
        PrcToast.fire({ icon: 'error', title: 'Failed to save comment' });
    });
});

document.getElementById('btn_publish')?.addEventListener('click', function() {
    if (this.disabled) return;
    Swal.fire({
        title: 'Publish Report?',
        html: '<p class="text-muted fs-7">This will make all Term <?= $term ?> reports available to students, parents, and teachers. This action cannot be reversed.</p>',
        icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Yes, Publish', cancelButtonText: 'Cancel',
        buttonsStyling: false,
        customClass: { confirmButton: 'btn btn-success me-2', cancelButton: 'btn btn-light' }
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(PRC_PUBLISH_URL, {}, res => {
            if (res.success) {
                Swal.fire({ title: 'Published!', text: res.message, icon: 'success', timer: 2000, showConfirmButton: false })
                    .then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
});
</script>
