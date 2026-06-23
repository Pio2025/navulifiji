<!--begin::Pending Exam Reports-->
<div class="mb-10">
    <div class="d-flex align-items-center gap-3 mb-6">
        <div class="symbol symbol-45px">
            <div class="symbol-label bg-light-warning">
                <i class="ki-duotone ki-shield-tick fs-2 text-warning">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>
        </div>
        <div class="flex-grow-1">
            <h4 class="fw-bold text-gray-800 mb-0">Pending Exam Reports</h4>
            <p class="text-muted fs-7 mb-0">Reports submitted by class teachers awaiting your review and publication</p>
        </div>
        <span class="badge badge-light-warning fs-7 px-3 py-2"><?= count($pendingExamReports) ?> pending</span>
    </div>

    <?php if (empty($pendingExamReports)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-12 text-muted">
            <i class="ki-duotone ki-check-circle fs-4x text-gray-200 mb-3">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <div class="fs-6 fw-semibold mb-1">All clear!</div>
            <div class="fs-8">No reports are currently awaiting your review.</div>
        </div>
    </div>
    <?php else: ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-row-bordered align-middle gs-5 gy-3 fs-7 mb-0" id="tbl_pending_reports">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="min-w-150px">Class</th>
                        <th>School</th>
                        <th class="text-center min-w-80px">Term</th>
                        <th class="min-w-130px">Class Teacher</th>
                        <th class="text-center min-w-80px">Students</th>
                        <th class="text-center min-w-110px">CT Comments</th>
                        <th class="text-center min-w-110px">Your Comments</th>
                        <th class="min-w-110px">Submitted</th>
                        <th class="text-center min-w-100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pendingExamReports as $rep):
                    $stuCount  = (int) $rep['student_count'];
                    $ctDone    = (int) $rep['ct_comments'];
                    $prcDone   = (int) $rep['prc_comments'];
                    $allPrcDone= $prcDone >= $stuCount && $stuCount > 0;
                    $ctColor   = $ctDone >= $stuCount  ? 'success' : 'warning';
                    $prcColor  = $allPrcDone           ? 'success' : 'danger';
                ?>
                <tr>
                    <td>
                        <div class="fw-bold text-gray-800"><?= esc($rep['class_name']) ?></div>
                        <div class="text-muted fs-9"><?= esc($rep['stream_name']) ?> · <?= esc($rep['class_year']) ?></div>
                    </td>
                    <td class="text-muted fs-8"><?= esc($rep['sch_name']) ?></td>
                    <td class="text-center">
                        <span class="badge badge-light-primary fs-8">Term <?= $rep['term'] ?></span>
                    </td>
                    <td class="text-muted fs-8"><?= esc($rep['class_teacher'] ?? '—') ?></td>
                    <td class="text-center fw-bold text-gray-800"><?= $stuCount ?></td>
                    <td class="text-center">
                        <span class="badge badge-light-<?= $ctColor ?> fs-8">
                            <?= $ctDone ?> / <?= $stuCount ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-light-<?= $prcColor ?> fs-8">
                            <?= $prcDone ?> / <?= $stuCount ?>
                        </span>
                    </td>
                    <td>
                        <div class="text-gray-700 fs-8">
                            <?= $rep['ct_submitted_at'] ? date('d M Y', strtotime($rep['ct_submitted_at'])) : '—' ?>
                        </div>
                        <?php if ($rep['ct_submitted_at']): ?>
                        <div class="text-muted fs-9">
                            <?php
                            $diff = time() - strtotime($rep['ct_submitted_at']);
                            if      ($diff < 3600)   echo floor($diff/60).' min ago';
                            elseif  ($diff < 86400)  echo floor($diff/3600).' hrs ago';
                            elseif  ($diff < 604800) echo floor($diff/86400).' days ago';
                            else                     echo date('d M Y', strtotime($rep['ct_submitted_at']));
                            ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="<?= base_url('classroom/principal-exam/'.$rep['class_id'].'/term/'.$rep['term']) ?>"
                           class="btn btn-sm btn-warning">
                            <i class="ki-duotone ki-pencil fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Review
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>
<!--end::Pending Exam Reports-->

<script>
// Initialize DataTable on the pending reports table if DataTables is available
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#tbl_pending_reports').DataTable({
            order:       [[7, 'asc']],
            pageLength:  25,
            language:    { search: 'Filter reports:' },
            columnDefs:  [{ targets: [2,4,5,6,8], orderable: false }],
        });
    }
});
</script>
