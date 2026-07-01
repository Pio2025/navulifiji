<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Incident Detail
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('conduct/my') ?>" class="text-muted text-hover-primary">My Conduct</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('conduct/my') ?>" class="btn btn-sm btn-light">Back</a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="row g-5">
        <!--begin::Left col-->
        <div class="col-lg-8">

            <!--begin::Incident card-->
            <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-body py-6">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-5">
                        <div>
                            <h3 class="fw-bold text-gray-900 mb-1">
                                <?= esc($incident['student_fname'] . ' ' . $incident['student_lname']) ?>
                            </h3>
                            <span class="badge badge-light-<?= !empty($incident['is_positive']) ? 'success' : 'danger' ?> me-2">
                                <?= esc($incident['type_name'] ?? '—') ?>
                            </span>
                            <span class="badge badge-light-<?= !empty($incident['is_resolved']) ? 'success' : 'warning' ?>">
                                <?= !empty($incident['is_resolved']) ? 'Resolved' : 'Open' ?>
                            </span>
                        </div>
                        <div class="text-end">
                            <div class="fs-2x fw-bold <?= (int) $incident['points_awarded'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= (int) $incident['points_awarded'] >= 0 ? '+' : '' ?><?= $incident['points_awarded'] ?>
                            </div>
                            <div class="text-muted fs-8">Points</div>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-sm-4">
                            <div class="text-muted fs-8">Category</div>
                            <div class="fw-semibold"><?= esc($incident['category'] ?? '—') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted fs-8">Severity</div>
                            <div class="fw-semibold"><?= esc($incident['severity_level'] ?? '—') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted fs-8">Date</div>
                            <div class="fw-semibold"><?= esc(date('d M Y, h:i A', strtotime($incident['incident_date']))) ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted fs-8">Location</div>
                            <div class="fw-semibold"><?= esc($incident['location'] ?: '—') ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted fs-8">Logged By</div>
                            <div class="fw-semibold">
                                <?= esc(trim(($incident['staff_fname'] ?? '') . ' ' . ($incident['staff_lname'] ?? '')) ?: '—') ?>
                            </div>
                        </div>
                    </div>

                    <div class="text-muted fs-8 mb-1">Description</div>
                    <p class="text-gray-800 mb-0"><?= nl2br(esc($incident['incident_description'] ?: '—')) ?></p>
                </div>
            </div>
            <!--end::Incident card-->

            <!--begin::Files card-->
            <?php if (!empty($files)): ?>
            <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Attached Files</h3>
                </div>
                <div class="card-body py-6">
                    <div class="row g-3">
                    <?php foreach ($files as $file): ?>
                        <div class="col-sm-3 col-6">
                            <a href="<?= base_url('conduct/file/' . $file['conduct_file_id']) ?>" target="_blank"
                               class="d-block text-center p-3 border rounded text-hover-primary">
                                <?php if (strpos($file['file_type'], 'image/') === 0): ?>
                                <i class="ki-duotone ki-picture fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                <?php else: ?>
                                <i class="ki-duotone ki-file fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                <?php endif; ?>
                                <div class="fs-8 text-truncate mt-2"><?= esc($file['file_src']) ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!--end::Files card-->

        </div>
        <!--end::Left col-->

        <!--begin::Right col-->
        <div class="col-lg-4">

            <!--begin::Appeal card-->
            <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Appeal</h3>
                </div>
                <div class="card-body py-6">

                    <?php if ($appeal): ?>
                        <!--begin::Existing appeal status-->
                        <div class="mb-4">
                            <?php
                            $statusColor = match($appeal['appeal_status']) {
                                'Approved' => 'success',
                                'Rejected' => 'danger',
                                default    => 'warning',
                            };
                            ?>
                            <span class="badge badge-light-<?= $statusColor ?> fs-7 px-4 py-2">
                                <?= esc($appeal['appeal_status']) ?>
                            </span>
                        </div>

                        <div class="text-muted fs-8 mb-1">Your Reason</div>
                        <p class="text-gray-800 fs-7 mb-4"><?= nl2br(esc($appeal['appeal_reason'])) ?></p>

                        <?php if (!empty($appealFiles)): ?>
                        <div class="text-muted fs-8 mb-2">Supporting Documents</div>
                        <div class="row g-2 mb-4">
                        <?php foreach ($appealFiles as $af): ?>
                            <div class="col-4">
                                <a href="<?= base_url('conduct/appeal/file/' . $af['appeal_file_id']) ?>" target="_blank"
                                   class="d-block text-center p-2 border rounded text-hover-primary">
                                    <?php if (strpos((string) $af['file_type'], 'image/') === 0): ?>
                                    <i class="ki-duotone ki-picture fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    <?php else: ?>
                                    <i class="ki-duotone ki-file fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    <?php endif; ?>
                                    <div class="fs-9 text-truncate mt-1"><?= esc($af['file_src']) ?></div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <div class="text-muted fs-8 mb-1">Submitted</div>
                        <div class="fw-semibold fs-7 mb-4">
                            <?= esc(date('d M Y, h:i A', strtotime($appeal['submitted_date']))) ?>
                        </div>

                        <?php if ($appeal['appeal_status'] !== 'Pending'): ?>
                        <div class="separator separator-dashed my-4"></div>

                        <div class="text-muted fs-8 mb-1">Decision</div>
                        <div class="fw-bold fs-7 text-<?= $statusColor ?> mb-3">
                            <?= esc($appeal['appeal_status']) ?>
                        </div>

                        <?php if (!empty($appeal['review_notes'])): ?>
                        <div class="text-muted fs-8 mb-1">Staff Notes</div>
                        <p class="text-gray-800 fs-7 mb-3"><?= nl2br(esc($appeal['review_notes'])) ?></p>
                        <?php endif; ?>

                        <?php if ($appeal['appeal_status'] === 'Approved' && (int) $appeal['points_restored'] > 0): ?>
                        <div class="text-muted fs-8 mb-1">Points Restored</div>
                        <div class="fw-bold text-success fs-7 mb-3">+<?= (int) $appeal['points_restored'] ?></div>
                        <?php endif; ?>

                        <div class="text-muted fs-8 mb-1">Reviewed</div>
                        <div class="fw-semibold fs-7">
                            <?= esc(date('d M Y', strtotime($appeal['reviewed_date']))) ?>
                        </div>
                        <?php endif; ?>
                        <!--end::Existing appeal status-->

                    <?php elseif ($canAppeal): ?>
                        <!--begin::Submit appeal form-->
                        <p class="text-muted fs-7 mb-5">
                            If you believe this incident was recorded in error or the circumstances were misunderstood,
                            you may submit an appeal for review by the school.
                        </p>
                        <form id="appeal_form">
                            <div class="mb-4">
                                <label class="form-label fs-8 required">Reason for Appeal</label>
                                <textarea id="appeal_reason" name="appeal_reason" class="form-control form-control-sm" rows="5"
                                          placeholder="Explain why you are appealing this incident..." required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fs-8">Supporting Documents <span class="text-muted">(optional)</span></label>
                                <input type="file" name="appeal_files[]" class="form-control form-control-sm"
                                       multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx">
                                <div class="form-text fs-9">Images or documents (PDF, Word). Max 10 MB each.</div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100" id="appeal_submit_btn">
                                <i class="ki-duotone ki-send fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                                Submit Appeal
                            </button>
                        </form>
                        <!--end::Submit appeal form-->

                    <?php else: ?>
                        <div class="text-muted fs-7">
                            <?php if (!empty($incident['is_resolved'])): ?>
                                This incident is already resolved. No appeal is necessary.
                            <?php else: ?>
                                No appeal has been submitted for this incident.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <!--end::Appeal card-->

        </div>
        <!--end::Right col-->
    </div>

</div>
</div>

<?php if ($canAppeal): ?>
<script>
document.getElementById('appeal_form').addEventListener('submit', function (e) {
    e.preventDefault();
    const btn    = document.getElementById('appeal_submit_btn');
    const reason = document.getElementById('appeal_reason').value.trim();
    if (!reason) return;

    btn.disabled    = true;
    btn.textContent = 'Submitting…';

    fetch('<?= base_url('conduct/appeal/' . (int) $incident['incident_id']) ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            Swal.fire('Submitted', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', res.message || 'Failed to submit appeal.', 'error');
            btn.disabled    = false;
            btn.textContent = 'Submit Appeal';
        }
    })
    .catch(() => {
        Swal.fire('Error', 'A network error occurred.', 'error');
        btn.disabled    = false;
        btn.textContent = 'Submit Appeal';
    });
});
</script>
<?php endif; ?>
