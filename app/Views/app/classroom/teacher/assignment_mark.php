<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Mark Assignment</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/teacher/'.$schSubId.'/assignments') ?>" class="text-muted text-hover-primary">Assignments</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Mark</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('classroom/teacher/'.$schSubId.'/assignment/'.$assignment['assignment_id'].'/analysis') ?>" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-chart-simple fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>View Analysis
            </a>
            <a href="<?= base_url('classroom/teacher/'.$schSubId.'/assignments') ?>" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>Back
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$totalScore     = (float)($assignment['assignment_total_score'] ?? 100);
$isPastDue      = !empty($assignment['assignment_due_date']) && strtotime($assignment['assignment_due_date']) < time();
$canMark        = empty($assignment['assignment_due_date']) || $isPastDue;
$totalSub       = count($submissions);
$totalGraded    = count(array_filter($submissions, fn($s) => !empty($s['assignment_mark']) || $s['assignment_mark']==='0'));
?>

<!--begin::Assignment detail card-->
<div class="card border-0 shadow-sm mb-6">
    <div class="card-body px-6 py-5">
        <div class="d-flex align-items-center flex-wrap gap-6">
            <div>
                <div class="fw-bold text-gray-800 fs-5 mb-1"><?= esc($assignment['assignment_name']) ?></div>
                <div class="text-muted fs-8">Posted by <?= esc($assignment['creator_name'] ?? '—') ?></div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-award fs-4 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div><div class="fw-bold text-gray-700 fs-7"><?= $totalScore ?></div><div class="text-muted fs-9">Total Marks</div></div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-calendar fs-4 <?= $isPastDue?'text-danger':'text-warning' ?>"><span class="path1"></span><span class="path2"></span></i>
                <div>
                    <div class="fw-bold text-gray-700 fs-7"><?= !empty($assignment['assignment_due_date'])?date('d M Y, g:i A',strtotime($assignment['assignment_due_date'])):'—' ?></div>
                    <div class="text-muted fs-9">Due Date <?= $isPastDue?'<span class="badge badge-light-danger fs-9 ms-1">Past Due</span>':'' ?></div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-send fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
                <div><div class="fw-bold text-gray-700 fs-7"><?= $totalSub ?></div><div class="text-muted fs-9">Submissions</div></div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="ki-duotone ki-check-circle fs-4 text-success"><span class="path1"></span><span class="path2"></span></i>
                <div><div class="fw-bold text-gray-700 fs-7"><?= $totalGraded ?></div><div class="text-muted fs-9">Graded</div></div>
            </div>
            <?php if (!empty($assignment['assignment_file'])): ?>
            <div class="ms-auto">
                <a href="<?= base_url('uploads/assignments/'.$assignment['assignment_file']) ?>" target="_blank" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-file-down fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Assignment File
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Assignment detail card-->

<!--begin::Marking locked notice-->
<?php if (!$canMark): ?>
<div class="alert d-flex align-items-center gap-4 mb-6 px-5 py-4"
     style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;">
    <i class="ki-duotone ki-information fs-2x text-warning flex-shrink-0">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
    </i>
    <div>
        <div class="fw-bold text-gray-800 fs-7 mb-1">Marking not yet available</div>
        <div class="text-muted fs-8">
            You can only mark submissions after the due date passes.
            Due: <strong><?= date('d M Y, g:i A', strtotime($assignment['assignment_due_date'])) ?></strong>
        </div>
    </div>
</div>
<?php endif; ?>
<!--end::Marking locked notice-->

<!--begin::Submissions table card-->
<div class="card border-0 shadow-sm">
    <div class="card-header border-0 pt-5 pb-3 px-6">
        <div class="d-flex align-items-center gap-2">
            <i class="ki-duotone ki-abstract-26 fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
            <h5 class="fw-bold text-gray-800 fs-5 mb-0">Student Submissions</h5>
        </div>
    </div>
    <div class="card-body px-6 pb-6 pt-2">
    <?php if (empty($submissions)): ?>
    <div class="text-center py-12 text-muted">
        <i class="ki-duotone ki-send fs-3x text-gray-200 mb-3"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-7 fw-semibold">No submissions yet.</div>
    </div>
    <?php else: ?>
    <table id="submissionsTable" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3">
        <thead>
            <tr class="fw-bold text-muted">
                <th class="ps-0">#</th>
                <th>Student</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>File</th>
                <th>Mark / <?= $totalScore ?></th>
                <th class="pe-0 text-end">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($submissions as $i => $sub):
            $isGraded = $sub['submission_status'] === 'Graded';
            $initials = strtoupper(substr($sub['student_name'],0,1));
        ?>
        <tr>
            <td class="ps-0 text-muted fs-8"><?= $i+1 ?></td>
            <td>
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-35px">
                        <?php if (!empty($sub['profile_photo'])): ?>
                        <img src="<?= base_url('uploads/profilePhoto/'.$sub['profile_photo']) ?>" class="rounded-circle" style="width:35px;height:35px;object-fit:cover;" alt="">
                        <?php else: ?>
                        <div class="symbol-label bg-light-primary fw-bold text-primary fs-8"><?= $initials ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="fw-semibold text-gray-800 fs-7"><?= esc($sub['student_name']) ?></span>
                </div>
            </td>
            <td class="fs-8 text-gray-600"><?= date('d M Y, g:i A', strtotime($sub['submitted_at'])) ?></td>
            <td><span class="badge badge-light-<?= $sub['submission_status']==='Graded'?'success':($sub['submission_status']==='Late'?'warning':'primary') ?> fs-9"><?= $sub['submission_status'] ?></span></td>
            <td>
                <a href="<?= base_url('uploads/assignment_submissions/'.$sub['submission_file']) ?>" target="_blank"
                   class="badge badge-light-primary fs-9">
                    <i class="ki-duotone ki-file-down fs-7 me-1"><span class="path1"></span><span class="path2"></span></i>
                    <?= strtoupper($sub['submission_file_type']) ?>
                </a>
            </td>
            <td>
                <?php if ($isGraded && $sub['assignment_mark'] !== null): ?>
                <span class="fw-bold text-gray-800 fs-7"><?= $sub['assignment_mark'] ?></span>
                <span class="text-muted fs-9"> / <?= $totalScore ?></span>
                <span class="badge badge-light-<?= ($sub['assignment_mark']/$totalScore)*100>=70?'success':(($sub['assignment_mark']/$totalScore)*100>=50?'warning':'danger') ?> ms-1 fs-9">
                    <?= round(($sub['assignment_mark']/$totalScore)*100,1) ?>%
                </span>
                <?php else: ?>
                <span class="text-muted fs-8 fst-italic">Not marked</span>
                <?php endif; ?>
            </td>
            <td class="pe-0 text-end">
                <?php if ($canMark): ?>
                <button type="button" class="btn btn-sm btn-<?= $isGraded?'light-warning':'light-primary' ?> btn-mark-sub"
                        style="height:30px;padding:0 10px;font-size:.78rem;"
                        data-submission-id="<?= $sub['submission_id'] ?>"
                        data-user-id="<?= $sub['student_user_id'] ?>"
                        data-student="<?= esc($sub['student_name']) ?>"
                        data-mark="<?= $sub['assignment_mark'] ?? '' ?>"
                        data-feedback="<?= esc($sub['score_feedback'] ?? '') ?>"
                        data-total="<?= $totalScore ?>">
                    <i class="ki-duotone ki-pencil fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                    <?= $isGraded ? 'Edit Mark' : 'Mark' ?>
                </button>
                <?php else: ?>
                <span class="badge badge-light-secondary fs-9">
                    <i class="ki-duotone ki-lock fs-7 me-1"><span class="path1"></span><span class="path2"></span></i>
                    Not yet
                </span>
                <?php endif; ?>


            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    </div>
</div>
<!--end::Submissions table card-->

</div>
</div>

<!--begin::Mark Modal-->
<div class="modal fade" id="markModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-pencil fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    <h5 class="modal-title fw-bold text-gray-800 fs-5 mb-0">Mark Assignment</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-muted fw-semibold fs-7 mb-4" id="markStudentName"></div>
                <input type="hidden" id="markSubmissionId">
                <input type="hidden" id="markUserId">
                <div class="mb-4">
                    <label class="form-label fw-semibold fs-7 required">Mark</label>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" id="markScore" min="0" step="0.5" placeholder="0">
                        <span class="input-group-text fw-bold" id="markTotal">/ 100</span>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold fs-7">Feedback <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea class="form-control form-control-sm" id="markFeedback" rows="3" placeholder="Comments for the student…"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveMark">
                    <span class="indicator-label"><i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Save Mark</span>
                    <span class="indicator-progress">Saving… <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Mark Modal-->

<script>
// DataTable
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#submissionsTable').DataTable({
            pageLength: 25,
            order: [[1,'asc']],
            columnDefs: [{ orderable: false, targets: [4,6] }]
        });
    }
});

// Open mark modal
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-mark-sub');
    if (!btn) return;
    document.getElementById('markStudentName').textContent = btn.dataset.student;
    document.getElementById('markSubmissionId').value      = btn.dataset.submissionId;
    document.getElementById('markUserId').value            = btn.dataset.userId;
    document.getElementById('markScore').value             = btn.dataset.mark || '';
    document.getElementById('markScore').max               = btn.dataset.total;
    document.getElementById('markTotal').textContent       = '/ ' + btn.dataset.total;
    document.getElementById('markFeedback').value          = btn.dataset.feedback || '';
    new bootstrap.Modal(document.getElementById('markModal')).show();
});

// Save mark
document.getElementById('btnSaveMark').addEventListener('click', function() {
    const btn      = this;
    const mark     = document.getElementById('markScore').value;
    const total    = parseFloat(document.getElementById('markScore').max);
    const feedback = document.getElementById('markFeedback').value.trim();
    if (mark === '' || isNaN(parseFloat(mark))) {
        Swal.fire({ title:'Mark required', text:'Please enter a mark.', icon:'warning',
            buttonsStyling:false, confirmButtonText:'OK', customClass:{confirmButton:'btn btn-warning'} });
        return;
    }
    if (parseFloat(mark) < 0 || parseFloat(mark) > total) {
        Swal.fire({ title:'Invalid mark', text:'Mark must be between 0 and ' + total + '.', icon:'warning',
            buttonsStyling:false, confirmButtonText:'OK', customClass:{confirmButton:'btn btn-warning'} });
        return;
    }
    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;
    const fd = new FormData();
    fd.append('submission_id',    document.getElementById('markSubmissionId').value);
    fd.append('user_id',          document.getElementById('markUserId').value);
    fd.append('assignment_mark',  mark);
    fd.append('feedback',         feedback);
    $.ajax({
        url: '<?= base_url('classroom/teacher/'.$schSubId.'/assignment/'.$assignment['assignment_id'].'/mark/save') ?>',
        type:'POST', data:fd, processData:false, contentType:false,
        success: function(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('markModal')).hide();
                Swal.fire({ title:'Saved!', text:'Mark: ' + res.mark + ' / ' + res.total + ' (' + res.pct + '%)', icon:'success',
                    timer:1800, showConfirmButton:false }).then(()=>location.reload());
            } else {
                Swal.fire({ title:'Failed', text:res.message, icon:'error',
                    buttonsStyling:false, confirmButtonText:'Close', customClass:{confirmButton:'btn btn-danger'} });
            }
        },
        error: function() {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title:'Error', text:'An error occurred.', icon:'error',
                buttonsStyling:false, confirmButtonText:'Close', customClass:{confirmButton:'btn btn-danger'} });
        }
    });
});
</script>
