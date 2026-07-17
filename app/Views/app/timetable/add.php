<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Add Timetable</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('timetable') ?>" class="text-muted text-hover-primary">Timetable</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">New</li>
            </ul>
        </div>
        <a href="<?= base_url('timetable') ?>" class="btn btn-light btn-sm">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i> Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<form method="POST" action="<?= base_url('timetable/store') ?>" id="tt-add-form">
<?= csrf_field() ?>

<div class="row g-6">

    <!--begin::Left — main settings-->
    <div class="col-lg-8">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Timetable Settings</h3>
            </div>
            <div class="card-body">

                <!--begin::Stream-->
                <div class="mb-6">
                    <label class="form-label required fw-semibold">Stream / Class</label>
                    <select name="stream_id_fk" id="stream-select" class="form-select" required>
                        <option value="">— Select a stream —</option>
                        <?php foreach ($streams as $s): ?>
                        <option value="<?= $s['stream_id'] ?>"
                            data-level="<?= esc($s['level_name'] ?? '') ?>"
                            <?= (old('stream_id_fk') == $s['stream_id']) ? 'selected' : '' ?>>
                            <?= esc($s['stream_name']) ?>
                            <?php if (!empty($s['level_name'])): ?>(<?= esc($s['level_name']) ?>)<?php endif; ?>
                            <?php if ($isSuperAdmin && !empty($s['sch_name'])): ?> — <?= esc($s['sch_name']) ?><?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">The class/stream this timetable belongs to.</div>
                </div>
                <!--end::Stream-->

                <div class="row">
                    <div class="col-md-4 mb-6">
                        <label class="form-label required fw-semibold">Academic Year</label>
                        <select name="academic_year" class="form-select" required>
                            <?php for ($y = $currentYear + 1; $y >= $currentYear - 3; $y--): ?>
                            <option value="<?= $y ?>" <?= old('academic_year', $currentYear) == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-6">
                        <label class="form-label required fw-semibold">Term</label>
                        <select name="term" class="form-select" required>
                            <?php foreach ([1,2,3] as $t): ?>
                            <option value="<?= $t ?>" <?= old('term', 1) == $t ? 'selected' : '' ?>>Term <?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-6">
                        <label class="form-label required fw-semibold">Status</label>
                        <select name="timetable_status" class="form-select">
                            <option value="Draft"    <?= old('timetable_status', 'Draft') === 'Draft'    ? 'selected' : '' ?>>Draft</option>
                            <option value="Active"   <?= old('timetable_status') === 'Active'   ? 'selected' : '' ?>>Active</option>
                            <option value="Archived" <?= old('timetable_status') === 'Archived' ? 'selected' : '' ?>>Archived</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <!--begin::Day rotation-->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Day Rotation Anchor</h3>
            </div>
            <div class="card-body">
                <div class="notice d-flex bg-light-info rounded border border-dashed border-info px-5 py-4 mb-6">
                    <i class="ki-duotone ki-information-5 fs-2tx text-info me-4 flex-shrink-0 mt-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <div class="text-gray-700 fs-7">
                        The <strong>6-day rotation</strong> cycles Day 1 → Day 2 → … → Day 6 → Day 1, Mon–Fri only.
                        Set the anchor below so the system can calculate which day number falls on any given calendar date.
                        <em>Example: Term started Monday 3 Feb 2025 on Day 1.</em>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Anchor Date</label>
                        <input type="date" name="rotation_start_date" class="form-control"
                               value="<?= old('rotation_start_date') ?>">
                        <div class="form-text">The calendar date your rotation started.</div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Day Number on Anchor Date</label>
                        <select name="rotation_start_day" class="form-select">
                            <?php for ($d = 1; $d <= 6; $d++): ?>
                            <option value="<?= $d ?>" <?= old('rotation_start_day', 1) == $d ? 'selected' : '' ?>>Day <?= $d ?></option>
                            <?php endfor; ?>
                        </select>
                        <div class="form-text">Which day of the cycle the anchor date was.</div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Day rotation-->
    </div>
    <!--end::Left-->

    <!--begin::Right — template-->
    <div class="col-lg-4">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Daily Schedule Template</h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label required fw-semibold">Template</label>
                    <select name="template_id_fk" id="template-select" class="form-select" required>
                        <option value="">— Select —</option>
                        <?php foreach ($templates as $tpl): ?>
                        <option value="<?= $tpl['template_id'] ?>"
                            <?= old('template_id_fk', 1) == $tpl['template_id'] ? 'selected' : '' ?>>
                            <?= esc($tpl['template_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Defines the periods and break times. Auto-selected when you pick a stream.</div>
                </div>

                <!--begin::Template preview-->
                <div id="template-preview" class="mt-4" style="display:none;">
                    <div class="text-muted fs-7 fw-semibold mb-3 text-uppercase ls-1">Preview</div>
                    <div id="slot-preview-list"></div>
                </div>
                <!--end::Template preview-->
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <p class="text-muted fs-7 mb-5">
                    After creating the timetable you'll be taken directly to the <strong>grid editor</strong>
                    where you assign subjects and teachers to each period across all 6 days.
                </p>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ki-duotone ki-check fs-2"></i> Create &amp; Edit Grid
                </button>
                <a href="<?= base_url('timetable') ?>" class="btn btn-light w-100 mt-3">Cancel</a>
            </div>
        </div>
    </div>
    <!--end::Right-->

</div>
</form>
</div>
</div>

<script>
const BASE_URL = '<?= base_url() ?>';

// When stream changes → auto-select template via AJAX
document.getElementById('stream-select').addEventListener('change', function () {
    const sid = this.value;
    if (!sid) return;
    fetch(BASE_URL + 'timetable/stream-info/' + sid)
        .then(r => r.json())
        .then(data => {
            if (data.template) {
                document.getElementById('template-select').value = data.template.template_id;
                renderSlotPreview(data.slots);
            }
        });
});

// When template changes → show preview
document.getElementById('template-select').addEventListener('change', function () {
    const tid = this.value;
    if (!tid) { document.getElementById('template-preview').style.display = 'none'; return; }
    // Find slots from the selected option — fetch via same endpoint if needed
    // For simplicity, store all template slot data server-side in a JS object
});

function renderSlotPreview(slots) {
    if (!slots || !slots.length) return;
    const wrap  = document.getElementById('template-preview');
    const list  = document.getElementById('slot-preview-list');
    list.innerHTML = slots.map(s => {
        const isBreak = s.is_teaching == 0;
        const time    = s.start_time ? s.start_time.substring(0,5) + '–' + s.end_time.substring(0,5) : '';
        return `<div class="d-flex align-items-center mb-2 ${isBreak ? 'opacity-50' : ''}">
            <span class="badge badge-light-${isBreak ? 'secondary' : 'primary'} me-3 w-80px text-center">${s.label}</span>
            <span class="text-muted fs-8">${time}</span>
        </div>`;
    }).join('');
    wrap.style.display = 'block';
}
</script>
