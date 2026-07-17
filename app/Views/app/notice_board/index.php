<?php
$notices        = $notices        ?? [];
$canPost        = $canPost        ?? false;
$canPin         = $canPin         ?? false;
$canManage      = $canManage      ?? false;
$myUserId       = $myUserId       ?? 0;
$parentSchools  = $parentSchools  ?? [];
$activeSchoolId = $activeSchoolId ?? 0;

$now = time();

function noticeAge(string $dateStr): string {
    $diff = time() - strtotime($dateStr);
    if ($diff < 60)      return 'Just now';
    if ($diff < 3600)    return floor($diff / 60) . 'm ago';
    if ($diff < 86400)   return floor($diff / 3600) . 'h ago';
    if ($diff < 604800)  return floor($diff / 86400) . 'd ago';
    return date('d M Y', strtotime($dateStr));
}

function noticeExpiry(string $expiresAt): string {
    $diff = strtotime($expiresAt) - time();
    if ($diff <= 0)      return 'Expired';
    if ($diff < 3600)    return 'Expires in ' . floor($diff / 60) . 'm';
    if ($diff < 86400)   return 'Expires in ' . floor($diff / 3600) . 'h';
    return 'Expires in ' . floor($diff / 86400) . 'd';
}

$priorityConfig = [
    'Urgent'    => ['color' => '#f1416c', 'light' => '#fff5f8', 'badge' => 'badge-light-danger',   'icon' => 'ki-notification-bing'],
    'Important' => ['color' => '#ffc700', 'light' => '#fff8dd', 'badge' => 'badge-light-warning',  'icon' => 'ki-information-5'],
    'Normal'    => ['color' => '#009ef7', 'light' => '#f1faff', 'badge' => 'badge-light-primary',  'icon' => 'ki-notification'],
];
$audienceConfig = [
    'All'      => ['badge' => 'badge-light-success',  'label' => 'Everyone'],
    'Teachers' => ['badge' => 'badge-light-primary',  'label' => 'Teachers'],
    'Students' => ['badge' => 'badge-light-info',     'label' => 'Students'],
    'Parents'  => ['badge' => 'badge-light-warning',  'label' => 'Parents'],
];

// Separate pinned vs regular
$pinned  = array_filter($notices, fn($n) => (int)$n['is_pinned'] === 1);
$regular = array_filter($notices, fn($n) => (int)$n['is_pinned'] === 0);
?>

<style>
.notice-card {
    border-radius: 12px;
    border: 1px solid var(--bs-border-color, #e9edf0);
    transition: box-shadow .18s, transform .18s;
    overflow: hidden;
    background: var(--bs-card-bg, #fff);
    position: relative;
}
.notice-card:hover {
    box-shadow: 0 6px 28px rgba(0,0,0,.10);
    transform: translateY(-2px);
}
.notice-priority-bar {
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    border-radius: 0;
}
.notice-card-body {
    padding: 1.25rem 1.25rem 1rem 1.6rem;
}
.notice-content-text {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    white-space: pre-line;
}
.notice-card.pinned-card {
    border-color: rgba(0,158,247,.3);
    background: linear-gradient(135deg, #f8fcff 0%, #fff 60%);
}
.pinned-ribbon {
    position: absolute;
    top: 0; right: 0;
    background: #009ef7;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px 3px 14px;
    clip-path: polygon(8px 0%, 100% 0%, 100% 100%, 0% 100%);
    letter-spacing: .5px;
}
.notice-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.notice-avatar-placeholder {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #e9edf0;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 13px;
    font-weight: 700;
    color: #5e6278;
}
/* Post form drawer */
#noticeFormDrawer {
    position: fixed; top: 0; right: 0; bottom: 0;
    width: 460px; max-width: 100vw;
    background: var(--bs-card-bg, #fff);
    box-shadow: -6px 0 40px rgba(0,0,0,.13);
    z-index: 1050;
    display: flex; flex-direction: column;
    transform: translateX(100%);
    transition: transform .28s cubic-bezier(.4,0,.2,1);
}
#noticeFormDrawer.open { transform: translateX(0); }
#drawerOverlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.35);
    z-index: 1040;
    display: none;
    backdrop-filter: blur(2px);
}
#drawerOverlay.open { display: block; }
.drawer-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--bs-border-color, #e9edf0);
    display: flex; align-items: center; gap: .75rem;
}
.drawer-body { flex: 1; overflow-y: auto; padding: 1.5rem; }
.drawer-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--bs-border-color, #e9edf0);
    display: flex; gap: .75rem; justify-content: flex-end;
}
/* Priority selector */
.priority-chip {
    border: 2px solid #e9edf0; border-radius: 8px;
    padding: .5rem .9rem; cursor: pointer; text-align: center;
    transition: border-color .15s, background .15s;
    flex: 1; user-select: none;
}
.priority-chip.selected-Normal    { border-color: #009ef7; background: #f1faff; }
.priority-chip.selected-Important { border-color: #ffc700; background: #fff8dd; }
.priority-chip.selected-Urgent    { border-color: #f1416c; background: #fff5f8; }
.empty-board {
    text-align: center; padding: 5rem 2rem;
}
/* Edit modal */
.modal-body textarea { resize: vertical; }
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Notice Board</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Notice Board</li>
            </ul>
        </div>
        <?php if ($canPost): ?>
        <button class="btn btn-primary fw-semibold" onclick="openDrawer()">
            <i class="ki-duotone ki-plus fs-3 me-1"></i>Post Notice
        </button>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php if (!empty($parentSchools)): ?>
<!--begin::School tabs (parent view)-->
<div class="d-flex align-items-center gap-2 flex-wrap mb-3">
    <?php foreach ($parentSchools as $ps): ?>
    <a href="<?= base_url('dashboard/notice?sch_id=' . (int)$ps['sch_id']) ?>"
       class="btn btn-sm d-inline-flex align-items-center gap-2 <?= (int)$ps['sch_id'] === (int)$activeSchoolId ? 'btn-primary' : 'btn-light text-gray-600' ?>">
        <?php if (!empty($ps['sch_logo'])): ?>
        <img src="<?= base_url('uploads/schoolLogo/' . esc($ps['sch_logo'])) ?>"
             alt="" style="height:18px;width:18px;object-fit:contain;flex-shrink:0;">
        <?php else: ?>
        <i class="ki-outline ki-bank fs-6" style="flex-shrink:0;"></i>
        <?php endif; ?>
        <?= esc($ps['sch_name']) ?>
    </a>
    <?php endforeach; ?>
</div>
<hr class="mt-0 mb-5" style="border-color:#c4c8d6;">
<!--end::School tabs-->
<?php endif; ?>

<?php if (empty($notices)): ?>
<!--begin::Empty state-->
<div class="card border-0 shadow-sm">
    <div class="card-body empty-board">
        <div class="symbol symbol-80px mx-auto mb-5">
            <div class="symbol-label bg-light-primary">
                <i class="ki-duotone ki-notification-bing fs-2x text-primary">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
            </div>
        </div>
        <h3 class="fw-bold text-gray-800 mb-2">No Notices Yet</h3>
        <p class="text-muted fs-6 mb-5">The notice board is empty right now.<br>Check back later or be the first to post a notice.</p>
        <?php if ($canPost): ?>
        <button class="btn btn-primary" onclick="openDrawer()">
            <i class="ki-duotone ki-plus fs-3 me-1"></i>Post First Notice
        </button>
        <?php endif; ?>
    </div>
</div>
<!--end::Empty state-->

<?php else: ?>

<!--begin::Pinned section-->
<?php if (!empty($pinned)): ?>
<div class="mb-6">
    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="ki-duotone ki-pin fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
        <span class="fw-bold text-gray-700 fs-6 text-uppercase ls-1" style="letter-spacing:.6px;">Pinned</span>
        <span class="separator flex-grow-1"></span>
    </div>
    <div class="row g-5">
        <?php foreach ($pinned as $n):
            $pc    = $priorityConfig[$n['priority']] ?? $priorityConfig['Normal'];
            $ac    = $audienceConfig[$n['audience']] ?? $audienceConfig['All'];
            $isOwn = (int)$n['posted_by'] === $myUserId;
            $initial = strtoupper(substr($n['fname'] ?? 'U', 0, 1));
        ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="notice-card pinned-card shadow-sm h-100">
                <div class="notice-priority-bar" style="background:<?= $pc['color'] ?>;"></div>
                <div class="pinned-ribbon">PINNED</div>
                <div class="notice-card-body">
                    <!--begin::Header-->
                    <div class="d-flex align-items-start gap-2 mb-3">
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-bold text-gray-800 fs-6 lh-sm mb-1 pe-8"><?= esc($n['title']) ?></div>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge <?= $pc['badge'] ?> fs-9"><?= $n['priority'] ?></span>
                                <span class="badge <?= $ac['badge'] ?> fs-9"><?= $ac['label'] ?></span>
                            </div>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Content-->
                    <div class="text-gray-600 fs-7 notice-content-text mb-4"><?= nl2br(esc($n['content'])) ?></div>
                    <!--end::Content-->
                    <!--begin::Footer-->
                    <div class="d-flex align-items-center gap-2">
                        <?php if (!empty($n['profile_photo'])): ?>
                        <img src="<?= base_url('uploads/profilePhoto/' . $n['profile_photo']) ?>" class="notice-avatar">
                        <?php else: ?>
                        <div class="notice-avatar-placeholder"><?= $initial ?></div>
                        <?php endif; ?>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-gray-700 fs-8 lh-1"><?= esc(trim(($n['fname'] ?? '') . ' ' . ($n['lname'] ?? ''))) ?></div>
                            <div class="text-muted fs-9"><?= noticeAge($n['created_at']) ?></div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="text-muted fs-9"><?= noticeExpiry($n['expires_at']) ?></div>
                            <?php if ($isOwn || $canManage || $canPin): ?>
                            <div class="d-flex gap-1 mt-1 justify-content-end">
                                <?php if ($canPin): ?>
                                <form method="POST" action="<?= base_url('dashboard/notice/' . $n['notice_id'] . '/pin') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-icon btn-xs btn-light-primary" title="Unpin">
                                        <i class="ki-duotone ki-pin fs-5 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if ($isOwn || $canManage): ?>
                                <button class="btn btn-icon btn-xs btn-light-warning"
                                    onclick="openEdit(<?= $n['notice_id'] ?>, <?= htmlspecialchars(json_encode($n), ENT_QUOTES) ?>)"
                                    title="Edit">
                                    <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                <form method="POST" action="<?= base_url('dashboard/notice/' . $n['notice_id'] . '/delete') ?>"
                                    onsubmit="return confirm('Remove this notice?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-icon btn-xs btn-light-danger" title="Delete">
                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!--end::Footer-->
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<!--end::Pinned section-->

<!--begin::All notices-->
<?php if (!empty($regular)): ?>
<div class="mb-2">
    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="ki-duotone ki-notification fs-4 text-gray-500"><span class="path1"></span><span class="path2"></span></i>
        <span class="fw-bold text-gray-600 fs-6 text-uppercase ls-1" style="letter-spacing:.6px;">Notices</span>
        <span class="separator flex-grow-1"></span>
        <span class="badge badge-light text-muted"><?= count($regular) ?></span>
    </div>
    <div class="row g-5">
        <?php foreach ($regular as $n):
            $pc    = $priorityConfig[$n['priority']] ?? $priorityConfig['Normal'];
            $ac    = $audienceConfig[$n['audience']] ?? $audienceConfig['All'];
            $isOwn = (int)$n['posted_by'] === $myUserId;
            $initial = strtoupper(substr($n['fname'] ?? 'U', 0, 1));
        ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="notice-card shadow-sm h-100">
                <div class="notice-priority-bar" style="background:<?= $pc['color'] ?>;"></div>
                <div class="notice-card-body">
                    <!--begin::Header-->
                    <div class="d-flex align-items-start gap-2 mb-3">
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-bold text-gray-800 fs-6 lh-sm mb-1"><?= esc($n['title']) ?></div>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge <?= $pc['badge'] ?> fs-9"><?= $n['priority'] ?></span>
                                <span class="badge <?= $ac['badge'] ?> fs-9"><?= $ac['label'] ?></span>
                            </div>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Content-->
                    <div class="text-gray-600 fs-7 notice-content-text mb-4"><?= nl2br(esc($n['content'])) ?></div>
                    <!--end::Content-->
                    <!--begin::Footer-->
                    <div class="d-flex align-items-center gap-2">
                        <?php if (!empty($n['profile_photo'])): ?>
                        <img src="<?= base_url('uploads/profilePhoto/' . $n['profile_photo']) ?>" class="notice-avatar">
                        <?php else: ?>
                        <div class="notice-avatar-placeholder"><?= $initial ?></div>
                        <?php endif; ?>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-gray-700 fs-8 lh-1"><?= esc(trim(($n['fname'] ?? '') . ' ' . ($n['lname'] ?? ''))) ?></div>
                            <div class="text-muted fs-9"><?= noticeAge($n['created_at']) ?></div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="text-muted fs-9"><?= noticeExpiry($n['expires_at']) ?></div>
                            <?php if ($isOwn || $canManage || $canPin): ?>
                            <div class="d-flex gap-1 mt-1 justify-content-end">
                                <?php if ($canPin): ?>
                                <form method="POST" action="<?= base_url('dashboard/notice/' . $n['notice_id'] . '/pin') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-icon btn-xs btn-light" title="Pin">
                                        <i class="ki-duotone ki-pin fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if ($isOwn || $canManage): ?>
                                <button class="btn btn-icon btn-xs btn-light-warning"
                                    onclick="openEdit(<?= $n['notice_id'] ?>, <?= htmlspecialchars(json_encode($n), ENT_QUOTES) ?>)"
                                    title="Edit">
                                    <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                <form method="POST" action="<?= base_url('dashboard/notice/' . $n['notice_id'] . '/delete') ?>"
                                    onsubmit="return confirm('Remove this notice?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-icon btn-xs btn-light-danger" title="Delete">
                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!--end::Footer-->
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<!--end::All notices-->

<?php endif; ?>

</div>
</div>

<!-- ═══════════════════════════════════════════════════
     POST NOTICE DRAWER (slide-in from right)
════════════════════════════════════════════════════ -->
<div id="drawerOverlay" onclick="closeDrawer()"></div>

<div id="noticeFormDrawer">
    <div class="drawer-header">
        <div class="symbol symbol-40px">
            <div class="symbol-label bg-light-primary">
                <i class="ki-duotone ki-notification-bing fs-2 text-primary">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
            </div>
        </div>
        <div class="flex-grow-1">
            <div class="fw-bold text-gray-800 fs-5">Post a Notice</div>
            <div class="text-muted fs-8">Visible until expiry date</div>
        </div>
        <button class="btn btn-icon btn-sm btn-light" onclick="closeDrawer()">
            <i class="ki-duotone ki-cross fs-3"><span class="path1"></span><span class="path2"></span></i>
        </button>
    </div>

    <div class="drawer-body">
        <form id="postForm" method="POST" action="<?= base_url('dashboard/notice/store') ?>">
            <?= csrf_field() ?>

            <!--begin::Priority-->
            <div class="mb-5">
                <label class="form-label fw-semibold text-gray-700 mb-2 required">Priority</label>
                <input type="hidden" name="priority" id="priorityInput" value="Normal">
                <div class="d-flex gap-3">
                    <div class="priority-chip selected-Normal" data-val="Normal" onclick="selectPriority(this)">
                        <i class="ki-duotone ki-notification fs-3 text-primary d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fw-semibold fs-8 text-gray-700">Normal</div>
                    </div>
                    <div class="priority-chip" data-val="Important" onclick="selectPriority(this)">
                        <i class="ki-duotone ki-information-5 fs-3 text-warning d-block mb-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="fw-semibold fs-8 text-gray-700">Important</div>
                    </div>
                    <div class="priority-chip" data-val="Urgent" onclick="selectPriority(this)">
                        <i class="ki-duotone ki-notification-bing fs-3 text-danger d-block mb-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="fw-semibold fs-8 text-gray-700">Urgent</div>
                    </div>
                </div>
            </div>
            <!--end::Priority-->

            <!--begin::Title-->
            <div class="mb-5">
                <label class="form-label fw-semibold text-gray-700 required" for="newTitle">Title</label>
                <input type="text" class="form-control form-control-solid" id="newTitle" name="title"
                    placeholder="e.g. Staff Meeting — Friday 3pm" maxlength="255" required>
            </div>
            <!--end::Title-->

            <!--begin::Content-->
            <div class="mb-5">
                <label class="form-label fw-semibold text-gray-700 required" for="newContent">Message</label>
                <textarea class="form-control form-control-solid" id="newContent" name="content"
                    rows="5" placeholder="Write the notice details here…" required></textarea>
            </div>
            <!--end::Content-->

            <!--begin::Audience + Expiry-->
            <div class="row g-4 mb-5">
                <div class="col-6">
                    <label class="form-label fw-semibold text-gray-700" for="newAudience">Audience</label>
                    <select class="form-select form-select-solid" id="newAudience" name="audience">
                        <option value="All">Everyone</option>
                        <option value="Teachers">Teachers</option>
                        <option value="Students">Students</option>
                        <option value="Parents">Parents</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold text-gray-700" for="newExpiry">Expires On</label>
                    <input type="date" class="form-control form-control-solid" id="newExpiry" name="expires_at"
                        min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                    <div class="text-muted fs-9 mt-1">Default: 7 days from today</div>
                </div>
            </div>
            <!--end::Audience + Expiry-->

            <?php if ($canPin): ?>
            <!--begin::Pin toggle-->
            <div class="d-flex align-items-center gap-3 p-4 rounded bg-light-primary mb-2">
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="newIsPinned">
                </div>
                <div>
                    <label class="form-check-label fw-semibold text-gray-700 cursor-pointer" for="newIsPinned">
                        Pin to top
                    </label>
                    <div class="text-muted fs-9">Pinned notices appear first for everyone</div>
                </div>
            </div>
            <!--end::Pin toggle-->
            <?php endif; ?>

        </form>
    </div>

    <div class="drawer-footer">
        <button class="btn btn-light" onclick="closeDrawer()">Cancel</button>
        <button class="btn btn-primary fw-semibold" onclick="document.getElementById('postForm').submit()">
            <i class="ki-duotone ki-send fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
            Post Notice
        </button>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     EDIT NOTICE MODAL
════════════════════════════════════════════════════ -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-gray-800">Edit Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">

                    <div class="mb-4">
                        <label class="form-label fw-semibold required">Priority</label>
                        <input type="hidden" name="priority" id="editPriorityInput" value="Normal">
                        <div class="d-flex gap-3">
                            <div class="priority-chip selected-Normal" data-val="Normal" onclick="selectEditPriority(this)">
                                <i class="ki-duotone ki-notification fs-2 text-primary d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                                <div class="fw-semibold fs-8">Normal</div>
                            </div>
                            <div class="priority-chip" data-val="Important" onclick="selectEditPriority(this)">
                                <i class="ki-duotone ki-information-5 fs-2 text-warning d-block mb-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="fw-semibold fs-8">Important</div>
                            </div>
                            <div class="priority-chip" data-val="Urgent" onclick="selectEditPriority(this)">
                                <i class="ki-duotone ki-notification-bing fs-2 text-danger d-block mb-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="fw-semibold fs-8">Urgent</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold required">Title</label>
                        <input type="text" class="form-control form-control-solid" name="title" id="editTitle" maxlength="255" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold required">Message</label>
                        <textarea class="form-control form-control-solid" name="content" id="editContent" rows="4" required></textarea>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Audience</label>
                            <select class="form-select form-select-solid" name="audience" id="editAudience">
                                <option value="All">Everyone</option>
                                <option value="Teachers">Teachers</option>
                                <option value="Students">Students</option>
                                <option value="Parents">Parents</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Expires On</label>
                            <input type="date" class="form-control form-control-solid" name="expires_at" id="editExpiry">
                        </div>
                    </div>

                    <?php if ($canPin): ?>
                    <div class="d-flex align-items-center gap-3 p-3 rounded bg-light-primary">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="editIsPinned">
                        </div>
                        <label class="fw-semibold text-gray-700 cursor-pointer mb-0" for="editIsPinned">Pin to top</label>
                    </div>
                    <?php endif; ?>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ─── Drawer ──────────────────────────────────────────────────
function openDrawer() {
    document.getElementById('noticeFormDrawer').classList.add('open');
    document.getElementById('drawerOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDrawer() {
    document.getElementById('noticeFormDrawer').classList.remove('open');
    document.getElementById('drawerOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

// ─── Priority chips (post form) ──────────────────────────────
function selectPriority(el) {
    document.querySelectorAll('#noticeFormDrawer .priority-chip').forEach(c => {
        c.className = 'priority-chip';
    });
    el.classList.add('selected-' + el.dataset.val);
    document.getElementById('priorityInput').value = el.dataset.val;
}

// ─── Priority chips (edit modal) ─────────────────────────────
function selectEditPriority(el) {
    document.querySelectorAll('#editModal .priority-chip').forEach(c => {
        c.className = 'priority-chip';
    });
    el.classList.add('selected-' + el.dataset.val);
    document.getElementById('editPriorityInput').value = el.dataset.val;
}

// ─── Open edit modal ─────────────────────────────────────────
function openEdit(noticeId, notice) {
    document.getElementById('editForm').action = '<?= base_url('dashboard/notice/') ?>' + noticeId + '/update';
    document.getElementById('editTitle').value   = notice.title;
    document.getElementById('editContent').value = notice.content;
    document.getElementById('editAudience').value = notice.audience;

    var expDate = notice.expires_at ? notice.expires_at.split(' ')[0] : '';
    document.getElementById('editExpiry').value = expDate;

    // Set priority chips
    document.querySelectorAll('#editModal .priority-chip').forEach(c => c.className = 'priority-chip');
    var selChip = document.querySelector('#editModal .priority-chip[data-val="' + notice.priority + '"]');
    if (selChip) selChip.classList.add('selected-' + notice.priority);
    document.getElementById('editPriorityInput').value = notice.priority;

    // Pin checkbox
    var pinCb = document.getElementById('editIsPinned');
    if (pinCb) pinCb.checked = notice.is_pinned == 1;

    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}
</script>
