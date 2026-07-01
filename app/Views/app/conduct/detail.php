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
                    <a href="<?= base_url('conduct') ?>" class="text-muted text-hover-primary">Conduct</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('conduct/edit/' . $incident['incident_id']) ?>" class="btn btn-sm btn-light-warning">
                <i class="ki-duotone ki-pencil fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                Edit
            </a>
            <?php endif; ?>
            <a href="<?= base_url('conduct') ?>" class="btn btn-sm btn-light">Back</a>
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
            <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Attached Files</h3>
                </div>
                <div class="card-body py-6">
                    <?php if (empty($files)): ?>
                        <div class="text-muted">No files attached.</div>
                    <?php else: ?>
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
                    <?php endif; ?>
                </div>
            </div>
            <!--end::Files card-->

            <!--begin::Actions card-->
            <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Actions Taken</h3>
                </div>
                <div class="card-body py-6">
                    <div class="table-responsive mb-5">
                        <table class="table table-row-dashed align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Duration (hrs)</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th class="text-end">—</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($actions)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-5">No actions recorded.</td></tr>
                            <?php else: foreach ($actions as $action): ?>
                                <tr>
                                    <td><?= esc($action['action_type']) ?></td>
                                    <td><?= esc($action['action_date'] ? date('d M Y', strtotime($action['action_date'])) : '—') ?></td>
                                    <td><?= esc($action['duration_hours'] ?? '—') ?></td>
                                    <td>
                                        <span class="badge badge-light-<?= !empty($action['is_completed']) ? 'success' : 'warning' ?>">
                                            <?= !empty($action['is_completed']) ? 'Completed' : 'Pending' ?>
                                        </span>
                                    </td>
                                    <td><?= esc($action['notes'] ?: '—') ?></td>
                                    <td class="text-end">
                                        <?php if ($canEdit && empty($action['is_completed'])): ?>
                                        <button class="btn btn-sm btn-light-success complete-action-btn" data-id="<?= $action['action_id'] ?>">
                                            Mark Complete
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($canEdit): ?>
                    <form id="add_action_form" class="row g-2 align-items-end border-top pt-5">
                        <div class="col-sm-3">
                            <label class="form-label fs-8">Action Type</label>
                            <input type="text" name="action_type" class="form-control form-control-sm" required placeholder="e.g. Detention">
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label fs-8">Date</label>
                            <input type="date" name="action_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label fs-8">Duration (hrs)</label>
                            <input type="number" step="0.5" name="duration_hours" class="form-control form-control-sm">
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label fs-8">Notes</label>
                            <input type="text" name="notes" class="form-control form-control-sm">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100">Add Action</button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <!--end::Actions card-->

        </div>
        <!--end::Left col-->

        <!--begin::Right col-->
        <div class="col-lg-4">

            <?php if ($canEdit): ?>
            <!--begin::Notify card-->
            <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Notify Parent</h3>
                </div>
                <div class="card-body py-6">
                    <form id="notify_form">
                        <div class="mb-3">
                            <label class="form-label fs-8">Via</label>
                            <select name="sent_via" class="form-select form-select-sm">
                                <option value="Email">Email</option>
                                <option value="In-App">In-App</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fs-8">Message (optional)</label>
                            <textarea name="message" class="form-control form-control-sm" rows="3" placeholder="Leave blank for default message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="ki-duotone ki-message-notif fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Send Notification
                        </button>
                    </form>
                </div>
            </div>
            <!--end::Notify card-->
            <?php endif; ?>

            <!--begin::Appeal card-->
            <?php if ($appeal || $canProcessAppeal): ?>
            <div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Appeal</h3>
                </div>
                <div class="card-body py-6">
                    <?php if (!$appeal): ?>
                        <div class="text-muted fs-7">No appeal has been submitted for this incident.</div>

                    <?php elseif ($appeal['appeal_status'] !== 'Pending'): ?>
                        <?php
                        $appealColor = match($appeal['appeal_status']) {
                            'Approved' => 'success',
                            'Rejected' => 'danger',
                            default    => 'warning',
                        };
                        ?>
                        <div class="mb-3">
                            <span class="badge badge-light-<?= $appealColor ?> fs-7 px-4 py-2">
                                <?= esc($appeal['appeal_status']) ?>
                            </span>
                        </div>
                        <div class="text-muted fs-8 mb-1">Student's Reason</div>
                        <p class="text-gray-800 fs-7 mb-3"><?= nl2br(esc($appeal['appeal_reason'])) ?></p>
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
                        <?php if (!empty($appeal['review_notes'])): ?>
                        <div class="text-muted fs-8 mb-1">Review Notes</div>
                        <p class="text-gray-800 fs-7 mb-3"><?= nl2br(esc($appeal['review_notes'])) ?></p>
                        <?php endif; ?>
                        <?php if ($appeal['appeal_status'] === 'Approved' && (int) $appeal['points_restored'] > 0): ?>
                        <div class="text-muted fs-8 mb-1">Points Restored</div>
                        <div class="fw-bold text-success fs-7">+<?= (int) $appeal['points_restored'] ?></div>
                        <?php endif; ?>

                    <?php elseif ($canProcessAppeal): ?>
                        <div class="text-muted fs-8 mb-1">Student's Reason</div>
                        <p class="text-gray-800 fs-7 mb-3"><?= nl2br(esc($appeal['appeal_reason'])) ?></p>
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
                        <div class="fw-semibold fs-7 mb-5">
                            <?= esc(date('d M Y, h:i A', strtotime($appeal['submitted_date']))) ?>
                        </div>

                        <form id="process_appeal_form">
                            <div class="mb-3">
                                <label class="form-label fs-8">Decision</label>
                                <select name="decision" id="appeal_decision" class="form-select form-select-sm" required>
                                    <option value="">— Select —</option>
                                    <option value="Approved">Approve</option>
                                    <option value="Rejected">Reject</option>
                                </select>
                            </div>
                            <div class="mb-3" id="points_restored_wrap" style="display:none;">
                                <label class="form-label fs-8">Points to Restore (optional)</label>
                                <input type="number" name="points_restored" class="form-control form-control-sm" min="0" value="0">
                                <div class="form-text fs-9">Enter the points to add back to the student's tally (positive number).</div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fs-8">Review Notes</label>
                                <textarea name="review_notes" class="form-control form-control-sm" rows="3" placeholder="Optional notes to accompany the decision"></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100" id="process_appeal_btn">
                                Submit Decision
                            </button>
                        </form>

                    <?php else: ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge badge-light-warning fs-7 px-4 py-2">Pending Review</span>
                        </div>
                        <div class="text-muted fs-8 mb-1">Student's Reason</div>
                        <p class="text-gray-800 fs-7 mb-0"><?= nl2br(esc($appeal['appeal_reason'])) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <!--end::Appeal card-->

            <!--begin::Notifications log-->
            <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Notification Log</h3>
                </div>
                <div class="card-body py-6">
                    <?php if (empty($notifications)): ?>
                        <div class="text-muted fs-7">No notifications sent yet.</div>
                    <?php else: ?>
                        <div class="timeline">
                        <?php foreach ($notifications as $n): ?>
                            <div class="d-flex flex-column mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold fs-7"><?= esc($n['recipient_type']) ?> — <?= esc($n['sent_via']) ?></span>
                                    <span class="text-muted fs-8"><?= esc(date('d M, h:i A', strtotime($n['sent_timestamp']))) ?></span>
                                </div>
                                <div class="text-muted fs-8 mt-1"><?= esc($n['message_preview']) ?></div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!--end::Notifications log-->

        </div>
        <!--end::Right col-->
    </div>

</div>
</div>

<script>
const incidentId = <?= (int) $incident['incident_id'] ?>;

document.getElementById('add_action_form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`<?= base_url('conduct/') ?>${incidentId}/actions/add`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            location.reload();
        } else {
            Swal.fire('Error', res.message || 'Failed to add action.', 'error');
        }
    });
});

document.querySelectorAll('.complete-action-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const actionId = this.dataset.id;
        fetch(`<?= base_url('conduct/action/') ?>${actionId}/complete`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                location.reload();
            } else {
                Swal.fire('Error', res.message || 'Failed to complete action.', 'error');
            }
        });
    });
});

document.getElementById('notify_form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`<?= base_url('conduct/') ?>${incidentId}/notify`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            Swal.fire('Sent', res.message || 'Notification sent.', 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', res.message || 'Failed to send notification.', 'error');
        }
    });
});

const appealDecisionSel = document.getElementById('appeal_decision');
if (appealDecisionSel) {
    appealDecisionSel.addEventListener('change', function () {
        const wrap = document.getElementById('points_restored_wrap');
        if (wrap) wrap.style.display = this.value === 'Approved' ? '' : 'none';
    });
}

document.getElementById('process_appeal_form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const btn = document.getElementById('process_appeal_btn');
    btn.disabled    = true;
    btn.textContent = 'Saving…';

    <?php if ($appeal): ?>
    const appealId = <?= (int) $appeal['appeal_id'] ?>;
    fetch(`<?= base_url('conduct/appeal/') ?>${appealId}/process`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(this),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            Swal.fire('Done', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', res.message || 'Failed to process appeal.', 'error');
            btn.disabled    = false;
            btn.textContent = 'Submit Decision';
        }
    })
    .catch(() => {
        Swal.fire('Error', 'A network error occurred.', 'error');
        btn.disabled    = false;
        btn.textContent = 'Submit Decision';
    });
    <?php endif; ?>
});
</script>
