<?php
$announcements     = $announcements     ?? [];
$canPost           = $canPost           ?? false;
$canManage         = $canManage         ?? false;
$myUserId          = $myUserId          ?? 0;
$parentSchools     = $parentSchools     ?? [];
$activeSchoolId    = $activeSchoolId    ?? 0;
$needsSchoolSelect = $needsSchoolSelect ?? false;
$allSchools        = $allSchools        ?? [];

function annAge(string $d): string {
    $s = time() - strtotime($d);
    if ($s < 60)    return 'Just now';
    if ($s < 3600)  return floor($s/60).'m ago';
    if ($s < 86400) return floor($s/3600).'h ago';
    return date('d M Y', strtotime($d));
}
function annExpiry(?string $e): string {
    if (!$e) return 'No expiry';
    $s = strtotime($e) - time();
    if ($s <= 0)       return 'Expired';
    if ($s < 86400)    return 'Expires today';
    return 'Expires ' . date('d M Y', strtotime($e));
}

$priorityCfg = [
    'Critical'  => ['color'=>'#f1416c','light'=>'#fff5f8','text'=>'text-danger',  'badge'=>'badge-danger',   'label'=>'Critical',  'icon'=>'ki-shield-cross'],
    'Important' => ['color'=>'#ff9500','light'=>'#fff8dd','text'=>'text-warning', 'badge'=>'badge-warning',  'label'=>'Important', 'icon'=>'ki-information-5'],
    'Info'      => ['color'=>'#009ef7','light'=>'#f1faff','text'=>'text-primary', 'badge'=>'badge-primary',  'label'=>'Info',      'icon'=>'ki-notification'],
];
?>

<style>
/* ── Announcement timeline ────────────────────────────── */
.ann-timeline {
    position: relative;
    padding-left: 60px;
}
.ann-timeline::before {
    content: '';
    position: absolute;
    left: 22px; top: 8px; bottom: 8px;
    width: 2px;
    background: linear-gradient(to bottom, #e4e6ef 0%, transparent 100%);
}
.ann-entry {
    position: relative;
    margin-bottom: 2rem;
}
.ann-dot {
    position: absolute;
    left: -46px; top: 20px;
    width: 24px; height: 24px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
    display: flex; align-items: center; justify-content: center;
    z-index: 1;
}
.ann-dot i { font-size: 10px; }
/* Card */
.ann-card {
    border-radius: 12px;
    border: 1px solid var(--bs-border-color, #e9edf0);
    overflow: hidden;
    transition: box-shadow .18s, transform .18s;
    background: #fff;
}
.ann-card:hover { box-shadow: 0 4px 24px rgba(0,0,0,.09); transform: translateY(-1px); }
.ann-card-header {
    padding: 1.1rem 1.5rem .9rem;
    border-bottom: 1px solid var(--bs-border-color, #f1f3f5);
    display: flex; align-items: flex-start; gap: 1rem;
}
.ann-card-body { padding: 1.25rem 1.5rem; }
.ann-card-footer {
    padding: .8rem 1.5rem;
    background: var(--bs-light, #f9f9f9);
    border-top: 1px solid var(--bs-border-color, #f1f3f5);
    display: flex; align-items: center; gap: .75rem; flex-wrap: wrap;
}
/* Priority stripe on left */
.priority-stripe {
    width: 5px; border-radius: 0; flex-shrink: 0;
    align-self: stretch; margin: -1.1rem 0 -.9rem -1.5rem;
    margin-right: .75rem;
}
/* Avatar */
.ann-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    object-fit: cover; flex-shrink: 0;
}
.ann-avatar-ph {
    width: 34px; height: 34px; border-radius: 50%;
    background: #e9edf0; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0;
    font-size: 13px; font-weight: 700; color: #5e6278;
}
/* Attachment pill */
.att-pill {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .45rem 1rem; border-radius: 50px;
    background: #f1f3f9; border: 1px solid #e4e6ef;
    font-size: .8rem; font-weight: 600; color: #3f4254;
    text-decoration: none; transition: background .15s;
}
.att-pill:hover { background: #e4e6ef; color: #181c32; }
/* Stamp */
.official-stamp {
    display: inline-flex; align-items: center; gap: .4rem;
    border: 1.5px solid currentColor; border-radius: 6px;
    padding: 2px 8px; font-size: 10px; font-weight: 700;
    letter-spacing: .8px; text-transform: uppercase; opacity: .7;
}
/* Form drawer */
#annDrawer {
    position: fixed; top: 0; right: 0; bottom: 0;
    width: 500px; max-width: 100vw;
    background: var(--bs-card-bg, #fff);
    box-shadow: -6px 0 40px rgba(0,0,0,.14);
    z-index: 9500;
    display: flex; flex-direction: column;
    transform: translateX(100%);
    transition: transform .28s cubic-bezier(.4,0,.2,1);
}
#annDrawer.open { transform: translateX(0); }
#annOverlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.38);
    z-index: 9490; display: none;
    backdrop-filter: blur(2px);
}
#annOverlay.open { display: block; }
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
.priority-chip {
    border: 2px solid #e9edf0; border-radius: 8px;
    padding: .6rem 1rem; cursor: pointer; text-align: center;
    transition: border-color .15s, background .15s;
    flex: 1; user-select: none;
}
.priority-chip.sel-Info      { border-color: #009ef7; background: #f1faff; }
.priority-chip.sel-Important { border-color: #ff9500; background: #fff8dd; }
.priority-chip.sel-Critical  { border-color: #f1416c; background: #fff5f8; }
.empty-ann { text-align: center; padding: 5rem 2rem; }
/* Dropzone */
.file-dropzone {
    border: 2px dashed #d9dee8; border-radius: 10px;
    padding: 1.5rem; text-align: center;
    cursor: pointer; transition: border-color .15s, background .15s;
}
.file-dropzone:hover, .file-dropzone.drag-over {
    border-color: #009ef7; background: #f1faff;
}
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Announcements</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Announcements</li>
            </ul>
        </div>
        <?php if ($canPost): ?>
        <button class="btn btn-primary fw-semibold" onclick="openAnnDrawer()">
            <i class="ki-duotone ki-send fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            New Announcement
        </button>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php if (!empty($parentSchools)): ?>
<div class="d-flex align-items-center gap-2 flex-wrap mb-3">
    <?php foreach ($parentSchools as $ps): ?>
    <a href="<?= base_url('dashboard/announcement?sch_id=' . (int)$ps['sch_id']) ?>"
       class="btn btn-sm d-inline-flex align-items-center gap-2 <?= (int)$ps['sch_id'] === $activeSchoolId ? 'btn-primary' : 'btn-light' ?>">
        <?php if (!empty($ps['sch_logo'])): ?>
        <img src="<?= base_url('uploads/school/logo/' . esc($ps['sch_logo'])) ?>"
             alt="" style="height:18px;width:18px;object-fit:contain;flex-shrink:0;">
        <?php else: ?>
        <i class="ki-outline ki-bank fs-6" style="flex-shrink:0;"></i>
        <?php endif; ?>
        <?= esc($ps['sch_name']) ?>
    </a>
    <?php endforeach; ?>
</div>
<hr class="mt-0 mb-5" style="border-color:#c4c8d6;">
<?php endif; ?>

<?php if (empty($announcements)): ?>
<!--begin::Empty-->
<div class="card border-0 shadow-sm">
    <div class="card-body empty-ann">
        <div class="symbol symbol-80px mx-auto mb-5">
            <div class="symbol-label bg-light-primary">
                <i class="ki-duotone ki-send fs-2x text-primary">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>
        </div>
        <h3 class="fw-bold text-gray-800 mb-2">No Announcements</h3>
        <p class="text-muted fs-6 mb-5">There are no official announcements at this time.<br>Check back later.</p>
        <?php if ($canPost): ?>
        <button class="btn btn-primary" onclick="openAnnDrawer()">
            <i class="ki-duotone ki-send fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Create First Announcement
        </button>
        <?php endif; ?>
    </div>
</div>
<!--end::Empty-->

<?php else: ?>

<div class="ann-timeline">
    <?php foreach ($announcements as $a):
        $pc    = $priorityCfg[$a['priority']] ?? $priorityCfg['Info'];
        $isOwn = (int)$a['posted_by'] === $myUserId;
        $init  = strtoupper(substr($a['fname'] ?? 'A', 0, 1));
        $authorName = esc(trim(($a['fname'] ?? '') . ' ' . ($a['lname'] ?? '')));
    ?>
    <div class="ann-entry">

        <!--begin::Timeline dot-->
        <div class="ann-dot <?= $pc['text'] ?>">
            <i class="ki-duotone <?= $pc['icon'] ?> fs-8 <?= $pc['text'] ?>">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
        </div>
        <!--end::Timeline dot-->

        <div class="ann-card shadow-sm">

            <!--begin::Card header-->
            <div class="ann-card-header" style="border-left: 5px solid <?= $pc['color'] ?>;">
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="fw-bold text-gray-800 fs-5"><?= esc($a['title']) ?></span>
                        <span class="badge <?= $pc['badge'] ?> fs-9"><?= $a['priority'] ?></span>
                        <span class="official-stamp <?= $pc['text'] ?>">
                            <i class="ki-duotone ki-verify fs-8 me-1 <?= $pc['text'] ?>"><span class="path1"></span><span class="path2"></span></i>
                            Official
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <?php if (!empty($a['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $a['profile_photo']) ?>" class="ann-avatar">
                            <?php else: ?>
                            <div class="ann-avatar-ph"><?= $init ?></div>
                            <?php endif; ?>
                            <div>
                                <span class="fw-semibold text-gray-700 fs-8"><?= $authorName ?></span>
                                <?php if (!empty($a['author_role_cat_name'])): ?>
                                <span class="badge badge-light-secondary fs-9 ms-1 py-1 px-2"><?= esc($a['author_role_cat_name']) ?></span>
                                <?php endif; ?>
                                <span class="text-muted fs-9 ms-1">&bull; <?= annAge($a['created_at']) ?></span>
                            </div>
                        </div>
                        <span class="text-muted fs-9"><?= annExpiry($a['expires_at']) ?></span>
                    </div>
                </div>
                <!--begin::Actions-->
                <?php if ($isOwn || $canManage): ?>
                <div class="d-flex gap-2 flex-shrink-0 align-self-start pt-1">
                    <button class="btn btn-icon btn-sm btn-light-warning"
                        onclick="openAnnEdit(<?= $a['announcement_id'] ?>, <?= htmlspecialchars(json_encode($a), ENT_QUOTES) ?>)"
                        title="Edit">
                        <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                    <form method="POST" action="<?= base_url('dashboard/announcement/'.$a['announcement_id'].'/delete') ?>"
                        onsubmit="return confirm('Remove this announcement?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-icon btn-sm btn-light-danger" title="Delete">
                            <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </form>
                </div>
                <?php endif; ?>
                <!--end::Actions-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="ann-card-body">
                <div class="text-gray-700 fs-6 lh-lg" style="white-space:pre-line;"><?= nl2br(esc($a['content'])) ?></div>
            </div>
            <!--end::Card body-->

            <!--begin::Card footer-->
            <?php if (!empty($a['attachment'])): ?>
            <div class="ann-card-footer">
                <span class="text-muted fs-8 fw-semibold me-1">Attachment:</span>
                <a href="<?= base_url('dashboard/announcement/'.$a['announcement_id'].'/download') ?>"
                    class="att-pill" target="_blank">
                    <?php
                    $ext = strtolower($a['attachment_type'] ?? '');
                    $iconClass = match($ext) {
                        'pdf'  => 'ki-file-down text-danger',
                        'jpg','jpeg','png','webp' => 'ki-picture text-success',
                        'doc','docx' => 'ki-file-doc text-primary',
                        default => 'ki-file text-muted',
                    };
                    ?>
                    <i class="ki-duotone <?= $iconClass ?> fs-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <span class="text-truncate" style="max-width:220px;">
                        <?= esc($a['attachment_name'] ?: $a['attachment']) ?>
                    </span>
                    <i class="ki-duotone ki-arrow-down fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                </a>
            </div>
            <?php endif; ?>
            <!--end::Card footer-->

        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>

</div>
</div>

<!-- ═══════════════════════════════════════
     NEW ANNOUNCEMENT DRAWER
══════════════════════════════════════════ -->
<div id="annOverlay" onclick="closeAnnDrawer()"></div>

<div id="annDrawer">
    <div class="drawer-header">
        <div class="symbol symbol-40px">
            <div class="symbol-label bg-light-primary">
                <i class="ki-duotone ki-send fs-2 text-primary">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>
        </div>
        <div class="flex-grow-1">
            <div class="fw-bold text-gray-800 fs-5">New Announcement</div>
            <div class="text-muted fs-8">Official school-wide communication</div>
        </div>
        <button class="btn btn-icon btn-sm btn-light" onclick="closeAnnDrawer()">
            <i class="ki-duotone ki-cross fs-3"><span class="path1"></span><span class="path2"></span></i>
        </button>
    </div>

    <div class="drawer-body">
        <form id="annForm" method="POST" action="<?= base_url('dashboard/announcement/store') ?>"
            enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="priority" id="annPriorityInput" value="Info">

            <?php if ($needsSchoolSelect && !empty($allSchools)): ?>
            <!--School selector-->
            <div class="mb-5">
                <label class="form-label fw-semibold text-gray-700 required" for="annSchool">Post to School</label>
                <select class="form-select form-select-solid" id="annSchool" name="sch_id" required>
                    <option value="">— Select a school —</option>
                    <?php foreach ($allSchools as $s): ?>
                    <option value="<?= (int)$s['sch_id'] ?>"><?= esc($s['sch_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="text-muted fs-9 mt-1">This announcement will only appear for the selected school.</div>
            </div>
            <?php endif; ?>

            <!--Priority-->
            <div class="mb-5">
                <label class="form-label fw-semibold text-gray-700 mb-2 required">Priority</label>
                <div class="d-flex gap-3">
                    <div class="priority-chip sel-Info" data-val="Info" onclick="selAnnPri(this)">
                        <i class="ki-duotone ki-notification fs-3 text-primary d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fw-semibold fs-8 text-gray-700">Info</div>
                    </div>
                    <div class="priority-chip" data-val="Important" onclick="selAnnPri(this)">
                        <i class="ki-duotone ki-information-5 fs-3 text-warning d-block mb-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="fw-semibold fs-8 text-gray-700">Important</div>
                    </div>
                    <div class="priority-chip" data-val="Critical" onclick="selAnnPri(this)">
                        <i class="ki-duotone ki-shield-cross fs-3 text-danger d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fw-semibold fs-8 text-gray-700">Critical</div>
                    </div>
                </div>
            </div>

            <!--Title-->
            <div class="mb-5">
                <label class="form-label fw-semibold text-gray-700 required" for="annTitle">Title</label>
                <input type="text" class="form-control form-control-solid" id="annTitle" name="title"
                    placeholder="e.g. School Closure — Term 2 Holiday" maxlength="255" required>
            </div>

            <!--Content-->
            <div class="mb-5">
                <label class="form-label fw-semibold text-gray-700 required" for="annContent">Message</label>
                <textarea class="form-control form-control-solid" id="annContent" name="content"
                    rows="6" placeholder="Write the full announcement here…" required></textarea>
            </div>

            <!--Expiry-->
            <div class="mb-5">
                <label class="form-label fw-semibold text-gray-700" for="annExpiry">Expiry Date <span class="text-muted fw-normal">(optional — leave blank to never expire)</span></label>
                <input type="date" class="form-control form-control-solid" id="annExpiry" name="expires_at"
                    min="<?= date('Y-m-d') ?>">
            </div>

            <!--File attachment-->
            <div class="mb-4">
                <label class="form-label fw-semibold text-gray-700">Attachment <span class="text-muted fw-normal">(PDF, Word, Image — max 10 MB)</span></label>
                <div class="file-dropzone" id="dropzone" onclick="document.getElementById('annFile').click()">
                    <i class="ki-duotone ki-file-up fs-2x text-primary mb-2"><span class="path1"></span><span class="path2"></span></i>
                    <div class="fw-semibold text-gray-700 fs-7">Click to upload or drag &amp; drop</div>
                    <div class="text-muted fs-9 mt-1" id="dropzoneLabel">PDF, DOCX, or image</div>
                </div>
                <input type="file" id="annFile" name="attachment" class="d-none"
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp"
                    onchange="showFileName(this)">
            </div>
        </form>
    </div>

    <div class="drawer-footer">
        <button class="btn btn-light" onclick="closeAnnDrawer()">Cancel</button>
        <button class="btn btn-primary fw-semibold" onclick="document.getElementById('annForm').submit()">
            <i class="ki-duotone ki-send fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
            Publish
        </button>
    </div>
</div>

<!-- ═══════════════════════════════════════
     EDIT MODAL
══════════════════════════════════════════ -->
<div class="modal fade" id="annEditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="annEditForm" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="priority" id="editAnnPriInput" value="Info">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4">
                        <label class="form-label fw-semibold required">Priority</label>
                        <div class="d-flex gap-3">
                            <div class="priority-chip sel-Info" data-val="Info" onclick="selEditAnnPri(this)">
                                <i class="ki-duotone ki-notification fs-2 text-primary d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                                <div class="fw-semibold fs-8">Info</div>
                            </div>
                            <div class="priority-chip" data-val="Important" onclick="selEditAnnPri(this)">
                                <i class="ki-duotone ki-information-5 fs-2 text-warning d-block mb-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="fw-semibold fs-8">Important</div>
                            </div>
                            <div class="priority-chip" data-val="Critical" onclick="selEditAnnPri(this)">
                                <i class="ki-duotone ki-shield-cross fs-2 text-danger d-block mb-1"><span class="path1"></span><span class="path2"></span></i>
                                <div class="fw-semibold fs-8">Critical</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold required">Title</label>
                        <input type="text" class="form-control form-control-solid" name="title" id="editAnnTitle" maxlength="255" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold required">Message</label>
                        <textarea class="form-control form-control-solid" name="content" id="editAnnContent" rows="5" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Expiry Date</label>
                        <input type="date" class="form-control form-control-solid" name="expires_at" id="editAnnExpiry">
                    </div>

                    <div>
                        <label class="form-label fw-semibold">Replace Attachment <span class="text-muted fw-normal">(leave empty to keep existing)</span></label>
                        <input type="file" class="form-control form-control-solid" name="attachment"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                    </div>
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
// ── Drawer ───────────────────────────────────────────────────
function openAnnDrawer()  {
    document.getElementById('annDrawer').classList.add('open');
    document.getElementById('annOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeAnnDrawer() {
    document.getElementById('annDrawer').classList.remove('open');
    document.getElementById('annOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

// ── Priority chips (new) ─────────────────────────────────────
function selAnnPri(el) {
    document.querySelectorAll('#annDrawer .priority-chip').forEach(c => c.className = 'priority-chip');
    el.classList.add('sel-' + el.dataset.val);
    document.getElementById('annPriorityInput').value = el.dataset.val;
}

// ── Priority chips (edit) ────────────────────────────────────
function selEditAnnPri(el) {
    document.querySelectorAll('#annEditModal .priority-chip').forEach(c => c.className = 'priority-chip');
    el.classList.add('sel-' + el.dataset.val);
    document.getElementById('editAnnPriInput').value = el.dataset.val;
}

// ── Open edit modal ──────────────────────────────────────────
function openAnnEdit(id, ann) {
    document.getElementById('annEditForm').action = '<?= base_url('dashboard/announcement/') ?>' + id + '/update';
    document.getElementById('editAnnTitle').value   = ann.title;
    document.getElementById('editAnnContent').value = ann.content;
    document.getElementById('editAnnExpiry').value  = ann.expires_at ? ann.expires_at.split(' ')[0] : '';
    document.querySelectorAll('#annEditModal .priority-chip').forEach(c => c.className = 'priority-chip');
    var chip = document.querySelector('#annEditModal .priority-chip[data-val="' + ann.priority + '"]');
    if (chip) chip.classList.add('sel-' + ann.priority);
    document.getElementById('editAnnPriInput').value = ann.priority;
    new bootstrap.Modal(document.getElementById('annEditModal')).show();
}

// ── File dropzone ────────────────────────────────────────────
function showFileName(input) {
    var label = document.getElementById('dropzoneLabel');
    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        label.style.color = '#009ef7';
    }
}
var dz = document.getElementById('dropzone');
if (dz) {
    dz.addEventListener('dragover', function(e) { e.preventDefault(); dz.classList.add('drag-over'); });
    dz.addEventListener('dragleave', function()  { dz.classList.remove('drag-over'); });
    dz.addEventListener('drop', function(e) {
        e.preventDefault(); dz.classList.remove('drag-over');
        var f = document.getElementById('annFile');
        f.files = e.dataTransfer.files;
        showFileName(f);
    });
}
</script>
