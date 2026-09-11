<?php
$visitors          = $visitors ?? [];
$entries           = $entries ?? [];
$passes            = $passes ?? [];
$settings          = $settings ?? null;
$formOptions       = $formOptions ?? ['visit_purpose' => [], 'pass_reason' => []];
$passTypes         = $passTypes ?? [];
$canMark           = $canMark ?? false;
$canApprove        = $canApprove ?? false;
$canReports        = $canReports ?? false;
$canManageAll      = $canManageAll ?? false;
$isSuperAdmin      = $isSuperAdmin ?? false;
$needsSchoolSelect = $needsSchoolSelect ?? false;
$allSchools        = $allSchools ?? [];
$currentSchId      = $currentSchId ?? 0;
$myUserId          = $myUserId ?? 0;

$roleCatLabels = [1 => 'System Admin', 2 => 'School Admin', 3 => 'Teacher', 4 => 'Student', 5 => 'Support Staff', 6 => 'Parent/Guardian', 7 => 'Admin'];

function gate_visitor_status_badge(string $status): string {
    return $status === 'Inside'
        ? '<span class="badge badge-light-success">Inside</span>'
        : '<span class="badge badge-light">Checked Out</span>';
}

function gate_pass_status_badge(string $status): string {
    $map = [
        'Pending'   => 'warning',
        'Approved'  => 'success',
        'Rejected'  => 'danger',
        'Used'      => 'info',
        'Cancelled' => 'secondary',
    ];
    $color = $map[$status] ?? 'light';
    return '<span class="badge badge-light-' . $color . '">' . esc($status) . '</span>';
}
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-wrap flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Gate Management</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Gate Management</li>
            </ul>
        </div>
        <?php if ($needsSchoolSelect): ?>
        <div class="d-flex align-items-center">
            <label class="fw-semibold me-2 mb-0">School</label>
            <select id="gate_school_select" class="form-select form-select-sm w-200px">
                <option value="">— Select school —</option>
                <?php foreach ($allSchools as $s): ?>
                <option value="<?= (int) $s['sch_id'] ?>" <?= (int) $s['sch_id'] === $currentSchId ? 'selected' : '' ?>><?= esc($s['sch_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php if ($currentSchId <= 0): ?>
<div class="card">
    <div class="card-body text-center py-16">
        <i class="ki-duotone ki-shield-tick fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-6 fw-semibold text-gray-600">Please select a school to view Gate Management.</div>
    </div>
</div>
<?php else: ?>

<ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" id="gate_tabs">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tab_gate_visitors">Visitor Management</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab_gate_entries">User Entry Management</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab_gate_passes">Gate Pass Management</a>
    </li>
    <?php if ($canReports): ?>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab_gate_reports" id="gate_reports_tab_link">Reports</a>
    </li>
    <?php endif; ?>
    <?php if ($canManageAll): ?>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab_gate_settings">Settings</a>
    </li>
    <?php endif; ?>
</ul>

<div class="tab-content">

    <!--begin::Visitors-->
    <div class="tab-pane fade show active" id="tab_gate_visitors">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">Visitors</div>
                <?php if ($canMark): ?>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#gateVisitorModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Check In Visitor
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>Pass #</th>
                            <th>Visitor</th>
                            <th>Purpose</th>
                            <th>Meeting</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody id="gate_visitors_tbody">
                        <?php foreach ($visitors as $v): ?>
                        <tr data-visitor-id="<?= (int) $v['visitor_id'] ?>">
                            <td><?= esc($v['pass_number']) ?></td>
                            <td>
                                <div class="fw-semibold"><?= esc($v['visitor_name']) ?></div>
                                <?php if (!empty($v['visitor_phone'])): ?><div class="text-muted fs-8"><?= esc($v['visitor_phone']) ?></div><?php endif; ?>
                            </td>
                            <td><?= esc($v['purpose'] ?? '—') ?></td>
                            <td><?= esc(trim(($v['meet_fname'] ?? '') . ' ' . ($v['meet_lname'] ?? '')) ?: ($v['meet_person_name'] ?? '—')) ?></td>
                            <td><?= esc(substr($v['check_in_at'], 0, 16)) ?></td>
                            <td class="gate-checkout-cell"><?= $v['check_out_at'] ? esc(substr($v['check_out_at'], 0, 16)) : '—' ?></td>
                            <td class="gate-status-cell"><?= gate_visitor_status_badge($v['status']) ?></td>
                            <td class="text-end">
                                <?php if ($canMark): ?>
                                <button type="button" class="btn btn-sm btn-light-danger gate-visitor-checkout-btn" data-visitor-id="<?= (int) $v['visitor_id'] ?>" <?= $v['status'] !== 'Inside' ? 'style="display:none;"' : '' ?>>Check Out</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($visitors)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-6">No visitors recorded yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Visitors-->

    <!--begin::User Entry-->
    <div class="tab-pane fade" id="tab_gate_entries">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">User Entry / Exit Log</div>
                <?php if ($canMark): ?>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#gateEntryModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Log Entry / Exit
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>User</th>
                            <th>Role</th>
                            <th>Direction</th>
                            <th>Time</th>
                            <th>Remarks</th>
                            <th>Recorded By</th>
                        </tr>
                    </thead>
                    <tbody id="gate_entries_tbody">
                        <?php foreach ($entries as $en): ?>
                        <tr>
                            <td class="fw-semibold"><?= esc(trim($en['fname'] . ' ' . $en['lname'])) ?></td>
                            <td><?= esc($roleCatLabels[(int) $en['role_cat_id_fk']] ?? '—') ?></td>
                            <td>
                                <?php if ($en['direction'] === 'In'): ?>
                                <span class="badge badge-light-success">In</span>
                                <?php else: ?>
                                <span class="badge badge-light-warning">Out</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc(substr($en['event_at'], 0, 16)) ?></td>
                            <td><?= esc($en['remarks'] ?? '—') ?></td>
                            <td class="text-muted fs-8"><?= esc(trim($en['recorded_by_fname'] . ' ' . $en['recorded_by_lname'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($entries)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-6">No entries recorded yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::User Entry-->

    <!--begin::Gate Pass-->
    <div class="tab-pane fade" id="tab_gate_passes">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">Gate Passes</div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#gatePassModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Request Gate Pass
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>Pass #</th>
                            <th>For</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Requested Time</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody id="gate_passes_tbody">
                        <?php foreach ($passes as $p):
                            $isOwner = (int) $p['requested_by_user_id_fk'] === $myUserId;
                        ?>
                        <tr data-pass-id="<?= (int) $p['pass_id'] ?>">
                            <td><?= esc($p['pass_number']) ?></td>
                            <td class="fw-semibold"><?= esc(trim($p['for_fname'] . ' ' . $p['for_lname'])) ?></td>
                            <td><?= esc($p['pass_type']) ?></td>
                            <td><?= esc($p['reason']) ?></td>
                            <td><?= esc(substr($p['requested_time'], 0, 16)) ?></td>
                            <td class="gate-pass-status-cell"><?= gate_pass_status_badge($p['status']) ?></td>
                            <td class="text-muted fs-8"><?= esc(trim($p['requested_by_fname'] . ' ' . $p['requested_by_lname'])) ?></td>
                            <td class="text-end gate-pass-actions">
                                <?php if ($canApprove && $p['status'] === 'Pending'): ?>
                                <button type="button" class="btn btn-sm btn-light-success gate-pass-decide-btn" data-pass-id="<?= (int) $p['pass_id'] ?>" data-status="Approved">Approve</button>
                                <button type="button" class="btn btn-sm btn-light-danger gate-pass-decide-btn" data-pass-id="<?= (int) $p['pass_id'] ?>" data-status="Rejected">Reject</button>
                                <?php endif; ?>
                                <?php if ($canMark && $p['status'] === 'Approved'): ?>
                                <button type="button" class="btn btn-sm btn-light-info gate-pass-used-btn" data-pass-id="<?= (int) $p['pass_id'] ?>">Mark Used</button>
                                <?php endif; ?>
                                <?php if (($isOwner || $canManageAll) && $p['status'] === 'Pending'): ?>
                                <button type="button" class="btn btn-sm btn-light gate-pass-cancel-btn" data-pass-id="<?= (int) $p['pass_id'] ?>">Cancel</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($passes)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-6">No gate passes yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Gate Pass-->

    <?php if ($canReports): ?>
    <!--begin::Reports-->
    <div class="tab-pane fade" id="tab_gate_reports">
        <div class="d-flex justify-content-end mb-4">
            <select id="gate_reports_days" class="form-select form-select-sm w-150px">
                <option value="7">Last 7 days</option>
                <option value="30" selected>Last 30 days</option>
                <option value="90">Last 90 days</option>
            </select>
        </div>

        <div class="row g-6 mb-6">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold" id="gate_kpi_inside">—</div>
                            <div class="text-muted fs-7">Visitors Inside Now</div>
                        </div>
                        <i class="ki-duotone ki-people fs-3x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold" id="gate_kpi_pending">—</div>
                            <div class="text-muted fs-7">Pending Gate Passes</div>
                        </div>
                        <i class="ki-duotone ki-notepad-edit fs-3x text-warning"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-6 mb-6">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header"><div class="card-title">Visitor Check-ins</div></div>
                    <div class="card-body"><div id="gate_chart_visitors"></div></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header"><div class="card-title">User Entries / Exits</div></div>
                    <div class="card-body"><div id="gate_chart_entries"></div></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header"><div class="card-title">Gate Passes by Status</div></div>
                    <div class="card-body"><div id="gate_chart_passes"></div></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header"><div class="card-title">Gate Passes by Type</div></div>
                    <div class="card-body"><div id="gate_chart_pass_types"></div></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">Daily Summary</div></div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>Day</th>
                            <th>Visitors</th>
                            <th>Entries In</th>
                            <th>Entries Out</th>
                            <th>Passes Requested</th>
                        </tr>
                    </thead>
                    <tbody id="gate_reports_summary_tbody">
                        <tr><td colspan="5" class="text-center text-muted py-6">Loading…</td></tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Reports-->
    <?php endif; ?>

    <?php if ($canManageAll): ?>
    <!--begin::Settings-->
    <div class="tab-pane fade" id="tab_gate_settings">
        <div class="row g-6">
            <div class="col-lg-6">
                <div class="card mb-6">
                    <div class="card-header"><div class="card-title">General Settings</div></div>
                    <div class="card-body">
                        <form id="gate_settings_form">
                            <?= csrf_field() ?>
                            <div class="mb-5">
                                <label class="form-label fw-semibold">Gate Pass Number Prefix</label>
                                <input type="text" name="pass_prefix" class="form-control" maxlength="10" value="<?= esc($settings['pass_prefix'] ?? 'GP-') ?>" />
                                <div class="text-muted fs-8 mt-1">Next pass number: <?= esc(($settings['pass_prefix'] ?? 'GP-') . str_pad((string) ($settings['next_pass_seq'] ?? 1), 5, '0', STR_PAD_LEFT)) ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="notify_on_visitor_checkin" <?= !empty($settings['notify_on_visitor_checkin']) ? 'checked' : '' ?> />
                                    <span class="form-check-label">Notify on visitor check-in</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="notify_on_pass_request" <?= !empty($settings['notify_on_pass_request']) ? 'checked' : '' ?> />
                                    <span class="form-check-label">Notify on gate pass request</span>
                                </label>
                            </div>
                            <div class="mb-5">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="notify_on_pass_decision" <?= !empty($settings['notify_on_pass_decision']) ? 'checked' : '' ?> />
                                    <span class="form-check-label">Notify on gate pass decision</span>
                                </label>
                            </div>
                            <button type="button" id="btn_save_gate_settings" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mb-6">
                    <div class="card-header"><div class="card-title">Visit Purpose Options</div></div>
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-4">
                            <input type="text" id="gate_new_visit_purpose" class="form-control form-control-sm" placeholder="New visit purpose" maxlength="100" />
                            <button type="button" class="btn btn-sm btn-primary gate-add-option-btn" data-option-type="visit_purpose">Add</button>
                        </div>
                        <ul class="list-group" id="gate_visit_purpose_list">
                            <?php foreach ($formOptions['visit_purpose'] as $o): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center" data-option-id="<?= (int) $o['option_id'] ?>">
                                <?= esc($o['option_label']) ?>
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger gate-remove-option-btn" data-option-id="<?= (int) $o['option_id'] ?>">
                                    <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><div class="card-title">Gate Pass Reason Options</div></div>
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-4">
                            <input type="text" id="gate_new_pass_reason" class="form-control form-control-sm" placeholder="New pass reason" maxlength="100" />
                            <button type="button" class="btn btn-sm btn-primary gate-add-option-btn" data-option-type="pass_reason">Add</button>
                        </div>
                        <ul class="list-group" id="gate_pass_reason_list">
                            <?php foreach ($formOptions['pass_reason'] as $o): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center" data-option-id="<?= (int) $o['option_id'] ?>">
                                <?= esc($o['option_label']) ?>
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger gate-remove-option-btn" data-option-id="<?= (int) $o['option_id'] ?>">
                                    <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Settings-->
    <?php endif; ?>

</div>

<?php endif; // $currentSchId > 0 ?>

</div>
</div>

<?php if ($currentSchId > 0): ?>

<!--begin::Visitor modal-->
<?php if ($canMark): ?>
<div class="modal fade" id="gateVisitorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Check In Visitor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="gate_visitor_form">
                    <?= csrf_field() ?>
                    <div class="row g-5">
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Visitor Name</label>
                            <input type="text" name="visitor_name" class="form-control" maxlength="150" required />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="visitor_phone" class="form-control" maxlength="20" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Purpose of Visit</label>
                            <input type="text" name="purpose" class="form-control" maxlength="150" list="gate_visit_purpose_options" />
                            <datalist id="gate_visit_purpose_options">
                                <?php foreach ($formOptions['visit_purpose'] as $o): ?>
                                <option value="<?= esc($o['option_label']) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="col-12 position-relative">
                            <label class="form-label fw-semibold">Person to Meet</label>
                            <input type="text" id="gate_visitor_meet_search" class="form-control" placeholder="Search staff/student…" autocomplete="off" />
                            <input type="hidden" name="meet_user_id" id="gate_visitor_meet_user_id" />
                            <div id="gate_visitor_meet_results" class="list-group position-absolute w-100" style="z-index:10;"></div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">ID Proof</label>
                            <input type="text" name="id_proof" class="form-control" maxlength="100" />
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Items Carried</label>
                            <input type="text" name="items_carried" class="form-control" maxlength="255" />
                        </div>
                        <?php if ($isSuperAdmin): ?>
                        <input type="hidden" name="sch_id" value="<?= $currentSchId ?>" />
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn_gate_visitor_save" class="btn btn-primary">Check In</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!--end::Visitor modal-->

<!--begin::Entry modal-->
<?php if ($canMark): ?>
<div class="modal fade" id="gateEntryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Log Entry / Exit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="gate_entry_form">
                    <?= csrf_field() ?>
                    <div class="row g-5">
                        <div class="col-12 position-relative">
                            <label class="form-label required fw-semibold">User</label>
                            <input type="text" id="gate_entry_user_search" class="form-control" placeholder="Search staff/student…" autocomplete="off" required />
                            <input type="hidden" name="user_id" id="gate_entry_user_id" />
                            <div id="gate_entry_user_results" class="list-group position-absolute w-100" style="z-index:10;"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label required fw-semibold">Direction</label>
                            <div class="d-flex gap-4 mt-1">
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="direction" value="In" checked />
                                    <span class="form-check-label">In</span>
                                </label>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="direction" value="Out" />
                                    <span class="form-check-label">Out</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Remarks</label>
                            <input type="text" name="remarks" class="form-control" maxlength="255" />
                        </div>
                        <?php if ($isSuperAdmin): ?>
                        <input type="hidden" name="sch_id" value="<?= $currentSchId ?>" />
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn_gate_entry_save" class="btn btn-primary">Log Entry</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!--end::Entry modal-->

<!--begin::Gate Pass modal-->
<div class="modal fade" id="gatePassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">Request Gate Pass</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="gate_pass_form">
                    <?= csrf_field() ?>
                    <div class="row g-5">
                        <div class="col-12 position-relative">
                            <label class="form-label fw-semibold">For (leave blank for yourself)</label>
                            <input type="text" id="gate_pass_for_search" class="form-control" placeholder="Search staff/student…" autocomplete="off" />
                            <input type="hidden" name="for_user_id" id="gate_pass_for_user_id" />
                            <div id="gate_pass_for_results" class="list-group position-absolute w-100" style="z-index:10;"></div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Pass Type</label>
                            <select name="pass_type" class="form-select">
                                <?php foreach ($passTypes as $pt): ?>
                                <option value="<?= esc($pt) ?>"><?= esc($pt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Requested Time</label>
                            <input type="datetime-local" name="requested_time" class="form-control" />
                        </div>
                        <div class="col-12">
                            <label class="form-label required fw-semibold">Reason</label>
                            <input type="text" name="reason" class="form-control" maxlength="255" list="gate_pass_reason_options" required />
                            <datalist id="gate_pass_reason_options">
                                <?php foreach ($formOptions['pass_reason'] as $o): ?>
                                <option value="<?= esc($o['option_label']) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <?php if ($isSuperAdmin): ?>
                        <input type="hidden" name="sch_id" value="<?= $currentSchId ?>" />
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn_gate_pass_save" class="btn btn-primary">Request Pass</button>
            </div>
        </div>
    </div>
</div>
<!--end::Gate Pass modal-->

<?php endif; // $currentSchId > 0 ?>

<script>
"use strict";

var GATE_SCH_ID           = <?= (int) $currentSchId ?>;
var GATE_IS_SUPER_ADMIN   = <?= $isSuperAdmin ? 'true' : 'false' ?>;
var GATE_SEARCH_USERS_URL = '<?= base_url('gate/search-users') ?>';
var GATE_VISITOR_STORE_URL   = '<?= base_url('gate/visitor/store') ?>';
var GATE_VISITOR_CHECKOUT_URL = '<?= base_url('gate/visitor/checkout/') ?>';
var GATE_ENTRY_STORE_URL     = '<?= base_url('gate/entry/store') ?>';
var GATE_PASS_STORE_URL      = '<?= base_url('gate/pass/store') ?>';
var GATE_PASS_DECIDE_URL     = '<?= base_url('gate/pass/decide/') ?>';
var GATE_PASS_USED_URL       = '<?= base_url('gate/pass/mark-used/') ?>';
var GATE_PASS_CANCEL_URL     = '<?= base_url('gate/pass/cancel/') ?>';
var GATE_SETTINGS_SAVE_URL   = '<?= base_url('gate/settings/save') ?>';
var GATE_OPTION_STORE_URL    = '<?= base_url('gate/form-option/store') ?>';
var GATE_OPTION_REMOVE_URL   = '<?= base_url('gate/form-option/remove/') ?>';
var GATE_REPORTS_URL         = '<?= base_url('gate/reports') ?>';

function gateEsc(s) {
    var div = document.createElement('div');
    div.textContent = (s === null || s === undefined) ? '' : String(s);
    return div.innerHTML;
}

function gateShowError(msg) {
    Swal.fire({ title: 'Error', text: msg || 'An unexpected error occurred.', icon: 'error' });
}

// ── School selector (super admin) ──────────────────────────────────────
var gateSchoolSelect = document.getElementById('gate_school_select');
if (gateSchoolSelect) {
    gateSchoolSelect.addEventListener('change', function () {
        var url = new URL(window.location.href);
        if (this.value) {
            url.searchParams.set('sch_id', this.value);
        } else {
            url.searchParams.delete('sch_id');
        }
        window.location.href = url.toString();
    });
}

// ── Tab persistence (survive full-page reloads after an action) ─────────
(function () {
    var gateTabs = document.getElementById('gate_tabs');
    if (!gateTabs) return;
    var storageKey = 'gate_active_tab_' + GATE_SCH_ID;

    function showTab(target) {
        var tabEl = gateTabs.querySelector('a[data-bs-toggle="tab"][href="' + target + '"]');
        if (tabEl) { new bootstrap.Tab(tabEl).show(); return true; }
        return false;
    }

    gateTabs.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function (el) {
        el.addEventListener('shown.bs.tab', function (e) {
            var href = e.target.getAttribute('href');
            if (!href) return;
            history.replaceState(null, '', window.location.pathname + window.location.search + href);
            localStorage.setItem(storageKey, href);
        });
    });

    // Restore after the rest of this script runs, so tab-specific
    // 'shown.bs.tab' handlers (e.g. Reports) are already attached.
    document.addEventListener('DOMContentLoaded', function () {
        var target = window.location.hash || localStorage.getItem(storageKey);
        if (target) { showTab(target); }
    });
})();

// ── Typeahead helper ────────────────────────────────────────────────────
function gateSetupTypeahead(searchInputId, resultsBoxId, hiddenInputId) {
    var input   = document.getElementById(searchInputId);
    var box     = document.getElementById(resultsBoxId);
    var hidden  = document.getElementById(hiddenInputId);
    if (!input) return;

    var timer = null;
    input.addEventListener('input', function () {
        hidden.value = '';
        var q = input.value.trim();
        clearTimeout(timer);
        if (q.length < 2) { box.innerHTML = ''; return; }
        timer = setTimeout(function () {
            $.ajax({
                url: GATE_SEARCH_USERS_URL, type: 'GET',
                data: { sch_id: GATE_SCH_ID, q: q },
                success: function (response) {
                    box.innerHTML = '';
                    if (!response.success || !response.users.length) return;
                    response.users.forEach(function (u) {
                        var item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = u.name;
                        item.addEventListener('click', function (e) {
                            e.preventDefault();
                            input.value = u.name;
                            hidden.value = u.userId;
                            box.innerHTML = '';
                        });
                        box.appendChild(item);
                    });
                }
            });
        }, 250);
    });

    document.addEventListener('click', function (e) {
        if (e.target !== input) box.innerHTML = '';
    });
}

gateSetupTypeahead('gate_visitor_meet_search', 'gate_visitor_meet_results', 'gate_visitor_meet_user_id');
gateSetupTypeahead('gate_entry_user_search', 'gate_entry_user_results', 'gate_entry_user_id');
gateSetupTypeahead('gate_pass_for_search', 'gate_pass_for_results', 'gate_pass_for_user_id');

// ── Visitor check-in ────────────────────────────────────────────────────
var btnGateVisitorSave = document.getElementById('btn_gate_visitor_save');
if (btnGateVisitorSave) {
    btnGateVisitorSave.addEventListener('click', function () {
        var btn  = this;
        var form = document.getElementById('gate_visitor_form');
        var name = form.querySelector('[name="visitor_name"]').value.trim();
        if (!name) { Swal.fire({ title: 'Missing information', text: 'Visitor name is required.', icon: 'warning' }); return; }

        btn.disabled = true;
        $.ajax({
            url: GATE_VISITOR_STORE_URL, type: 'POST', data: new FormData(form), processData: false, contentType: false,
            success: function (response) {
                btn.disabled = false;
                if (response.success) {
                    Swal.fire({ title: 'Checked In', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false })
                        .then(function () { window.location.reload(); });
                } else {
                    gateShowError(response.message);
                }
            },
            error: function () { btn.disabled = false; gateShowError(); }
        });
    });
}

document.getElementById('gate_tabs') && document.getElementById('kt_app_content').addEventListener('click', function (e) {
    var btn = e.target.closest('.gate-visitor-checkout-btn');
    if (btn) {
        var visitorId = btn.getAttribute('data-visitor-id');
        Swal.fire({ title: 'Check out this visitor?', icon: 'question', showCancelButton: true, confirmButtonText: 'Check Out' })
            .then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: GATE_VISITOR_CHECKOUT_URL + visitorId, type: 'POST',
                    success: function (response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            gateShowError(response.message);
                        }
                    },
                    error: function () { gateShowError(); }
                });
            });
        return;
    }

    var decideBtn = e.target.closest('.gate-pass-decide-btn');
    if (decideBtn) {
        var passId = decideBtn.getAttribute('data-pass-id');
        var status = decideBtn.getAttribute('data-status');
        Swal.fire({ title: status + ' this gate pass?', icon: 'question', showCancelButton: true, confirmButtonText: status })
            .then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: GATE_PASS_DECIDE_URL + passId, type: 'POST', data: { status: status },
                    success: function (response) {
                        if (response.success) { window.location.reload(); } else { gateShowError(response.message); }
                    },
                    error: function () { gateShowError(); }
                });
            });
        return;
    }

    var usedBtn = e.target.closest('.gate-pass-used-btn');
    if (usedBtn) {
        var passId = usedBtn.getAttribute('data-pass-id');
        $.ajax({
            url: GATE_PASS_USED_URL + passId, type: 'POST',
            success: function (response) {
                if (response.success) { window.location.reload(); } else { gateShowError(response.message); }
            },
            error: function () { gateShowError(); }
        });
        return;
    }

    var cancelBtn = e.target.closest('.gate-pass-cancel-btn');
    if (cancelBtn) {
        var passId = cancelBtn.getAttribute('data-pass-id');
        Swal.fire({ title: 'Cancel this gate pass?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Cancel Pass', confirmButtonColor: '#f1416c' })
            .then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: GATE_PASS_CANCEL_URL + passId, type: 'POST',
                    success: function (response) {
                        if (response.success) { window.location.reload(); } else { gateShowError(response.message); }
                    },
                    error: function () { gateShowError(); }
                });
            });
        return;
    }

    var addOptBtn = e.target.closest('.gate-add-option-btn');
    if (addOptBtn) {
        var type  = addOptBtn.getAttribute('data-option-type');
        var input = document.getElementById(type === 'visit_purpose' ? 'gate_new_visit_purpose' : 'gate_new_pass_reason');
        var label = input.value.trim();
        if (!label) return;
        $.ajax({
            url: GATE_OPTION_STORE_URL, type: 'POST',
            data: { sch_id: GATE_SCH_ID, option_type: type, option_label: label },
            success: function (response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    gateShowError(response.message);
                }
            },
            error: function () { gateShowError(); }
        });
        return;
    }

    var removeOptBtn = e.target.closest('.gate-remove-option-btn');
    if (removeOptBtn) {
        var optionId = removeOptBtn.getAttribute('data-option-id');
        $.ajax({
            url: GATE_OPTION_REMOVE_URL + optionId, type: 'POST',
            success: function (response) {
                if (response.success) {
                    removeOptBtn.closest('li').remove();
                } else {
                    gateShowError(response.message);
                }
            },
            error: function () { gateShowError(); }
        });
        return;
    }
});

// ── Entry log ────────────────────────────────────────────────────────────
var btnGateEntrySave = document.getElementById('btn_gate_entry_save');
if (btnGateEntrySave) {
    btnGateEntrySave.addEventListener('click', function () {
        var btn  = this;
        var form = document.getElementById('gate_entry_form');
        var userId = form.querySelector('[name="user_id"]').value;
        if (!userId) { Swal.fire({ title: 'Missing information', text: 'Please select a user.', icon: 'warning' }); return; }

        btn.disabled = true;
        $.ajax({
            url: GATE_ENTRY_STORE_URL, type: 'POST', data: new FormData(form), processData: false, contentType: false,
            success: function (response) {
                btn.disabled = false;
                if (response.success) {
                    Swal.fire({ title: 'Logged', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false })
                        .then(function () { window.location.reload(); });
                } else {
                    gateShowError(response.message);
                }
            },
            error: function () { btn.disabled = false; gateShowError(); }
        });
    });
}

// ── Gate pass request ──────────────────────────────────────────────────
var btnGatePassSave = document.getElementById('btn_gate_pass_save');
if (btnGatePassSave) {
    btnGatePassSave.addEventListener('click', function () {
        var btn  = this;
        var form = document.getElementById('gate_pass_form');
        var reason = form.querySelector('[name="reason"]').value.trim();
        if (!reason) { Swal.fire({ title: 'Missing information', text: 'Please provide a reason.', icon: 'warning' }); return; }

        btn.disabled = true;
        $.ajax({
            url: GATE_PASS_STORE_URL, type: 'POST', data: new FormData(form), processData: false, contentType: false,
            success: function (response) {
                btn.disabled = false;
                if (response.success) {
                    Swal.fire({ title: 'Requested', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false })
                        .then(function () { window.location.reload(); });
                } else {
                    gateShowError(response.message);
                }
            },
            error: function () { btn.disabled = false; gateShowError(); }
        });
    });
}

// ── Settings ─────────────────────────────────────────────────────────────
var btnSaveGateSettings = document.getElementById('btn_save_gate_settings');
if (btnSaveGateSettings) {
    btnSaveGateSettings.addEventListener('click', function () {
        var btn  = this;
        var form = document.getElementById('gate_settings_form');
        var formData = new FormData(form);
        formData.append('sch_id', GATE_SCH_ID);

        btn.disabled = true;
        $.ajax({
            url: GATE_SETTINGS_SAVE_URL, type: 'POST', data: formData, processData: false, contentType: false,
            success: function (response) {
                btn.disabled = false;
                if (response.success) {
                    Swal.fire({ title: 'Saved', text: response.message, icon: 'success', timer: 1500, showConfirmButton: false });
                } else {
                    gateShowError(response.message);
                }
            },
            error: function () { btn.disabled = false; gateShowError(); }
        });
    });
}

// ── Reports ──────────────────────────────────────────────────────────────
<?php if ($canReports): ?>
var gateReportsLoaded = false;
var gateCharts = {};

function gateRenderBarChart(elId, categories, series, colors) {
    var el = document.getElementById(elId);
    if (!el || typeof ApexCharts === 'undefined') return;
    if (gateCharts[elId]) { gateCharts[elId].destroy(); }
    gateCharts[elId] = new ApexCharts(el, {
        chart: { type: 'bar', height: 260, toolbar: { show: false } },
        series: series,
        xaxis: { categories: categories },
        colors: colors,
        legend: { show: series.length > 1 },
        dataLabels: { enabled: false },
    });
    gateCharts[elId].render();
}

function gateRenderDonutChart(elId, labels, values) {
    var el = document.getElementById(elId);
    if (!el || typeof ApexCharts === 'undefined') return;
    if (gateCharts[elId]) { gateCharts[elId].destroy(); }
    gateCharts[elId] = new ApexCharts(el, {
        chart: { type: 'donut', height: 260 },
        series: values,
        labels: labels,
    });
    gateCharts[elId].render();
}

function gateLoadReports() {
    var days = document.getElementById('gate_reports_days').value;
    $.ajax({
        url: GATE_REPORTS_URL, type: 'GET',
        data: { sch_id: GATE_SCH_ID, days: days },
        success: function (r) {
            if (!r.success) { gateShowError(r.message); return; }

            document.getElementById('gate_kpi_inside').textContent = r.insideNow;
            document.getElementById('gate_kpi_pending').textContent = r.pendingPasses;

            var byDay = {};
            function ensureDay(day) {
                if (!byDay[day]) byDay[day] = { visitors: 0, in: 0, out: 0, passes: 0 };
                return byDay[day];
            }

            var visitorCats = [], visitorVals = [];
            r.visitorDaily.forEach(function (row) {
                visitorCats.push(row.day);
                visitorVals.push(parseInt(row.total, 10));
                ensureDay(row.day).visitors = parseInt(row.total, 10);
            });
            gateRenderBarChart('gate_chart_visitors', visitorCats, [{ name: 'Visitors', data: visitorVals }], ['#009ef7']);

            var entryDaysSet = {};
            r.entryDaily.forEach(function (row) { entryDaysSet[row.day] = true; });
            var entryCats = Object.keys(entryDaysSet).sort();
            var inSeries = [], outSeries = [];
            entryCats.forEach(function (day) {
                var inRow  = r.entryDaily.find(function (x) { return x.day === day && x.direction === 'In'; });
                var outRow = r.entryDaily.find(function (x) { return x.day === day && x.direction === 'Out'; });
                var inVal  = inRow ? parseInt(inRow.total, 10) : 0;
                var outVal = outRow ? parseInt(outRow.total, 10) : 0;
                inSeries.push(inVal);
                outSeries.push(outVal);
                ensureDay(day).in = inVal;
                ensureDay(day).out = outVal;
            });
            gateRenderBarChart('gate_chart_entries', entryCats, [
                { name: 'In', data: inSeries },
                { name: 'Out', data: outSeries },
            ], ['#50cd89', '#ffc700']);

            var passDaysSet = {};
            r.passDaily.forEach(function (row) { passDaysSet[row.day] = true; });
            var passCats = Object.keys(passDaysSet).sort();
            var passStatuses = ['Pending', 'Approved', 'Rejected', 'Used', 'Cancelled'];
            var passSeries = passStatuses.map(function (status) {
                return {
                    name: status,
                    data: passCats.map(function (day) {
                        var row = r.passDaily.find(function (x) { return x.day === day && x.status === status; });
                        var val = row ? parseInt(row.total, 10) : 0;
                        if (status === 'Pending' || val > 0) { /* no-op, keep for readability */ }
                        return val;
                    }),
                };
            });
            passCats.forEach(function (day) {
                var total = r.passDaily.filter(function (x) { return x.day === day; })
                    .reduce(function (sum, x) { return sum + parseInt(x.total, 10); }, 0);
                ensureDay(day).passes = total;
            });
            gateRenderBarChart('gate_chart_passes', passCats, passSeries, ['#ffc700', '#50cd89', '#f1416c', '#7239ea', '#a1a5b7']);

            var typeLabels = r.passByType.map(function (row) { return row.pass_type; });
            var typeValues = r.passByType.map(function (row) { return parseInt(row.total, 10); });
            gateRenderDonutChart('gate_chart_pass_types', typeLabels, typeValues);

            var days2 = Object.keys(byDay).sort();
            var tbody = document.getElementById('gate_reports_summary_tbody');
            if (!days2.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-6">No activity in this period.</td></tr>';
            } else {
                tbody.innerHTML = days2.map(function (day) {
                    var d = byDay[day];
                    return '<tr><td>' + gateEsc(day) + '</td><td>' + d.visitors + '</td><td>' + (d.in || 0) + '</td><td>' + (d.out || 0) + '</td><td>' + d.passes + '</td></tr>';
                }).join('');
            }
        },
        error: function () { gateShowError(); }
    });
}

document.getElementById('gate_reports_tab_link').addEventListener('shown.bs.tab', function () {
    gateLoadReports();
});
document.getElementById('gate_reports_days').addEventListener('change', gateLoadReports);
<?php endif; ?>
</script>
