<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Edit Timetable — <?= esc($tt['stream_name'] ?? '') ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('timetable') ?>" class="text-muted text-hover-primary">Timetable</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Edit</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('timetable/detail/' . $tt['timetable_id']) ?>" class="btn btn-light btn-sm">
                <i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> View
            </a>
            <a href="<?= base_url('timetable') ?>" class="btn btn-light btn-sm">
                <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i> Back
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php
// Build lookup maps for JavaScript
$subjectJson    = json_encode(array_values($subjects));
$teacherMapJson = json_encode((object) $teacherMap);
$entryMapJson   = json_encode((object) array_map(fn($s) => (object) $s, $entryMap));
?>

<form method="POST" action="<?= base_url('timetable/update/' . $tt['timetable_id']) ?>" id="tt-form">
<?= csrf_field() ?>

<!--begin::Info + controls bar-->
<div class="card mb-5">
    <div class="card-body py-5">
        <div class="row align-items-center g-4">
            <div class="col-auto">
                <span class="badge badge-light-primary fs-7 px-4 py-2"><?= esc($tt['sch_cat_name'] ?? '') ?></span>
                <span class="badge badge-light-info fs-7 px-4 py-2 ms-2"><?= esc($tt['level_name'] ?? '') ?></span>
                <span class="badge badge-light-dark fs-7 px-4 py-2 ms-2"><?= esc($tt['template_name'] ?? '') ?></span>
            </div>
            <div class="col-md-2">
                <label class="form-label fs-8 mb-1 text-muted">Status</label>
                <select name="timetable_status" class="form-select form-select-sm">
                    <?php foreach (['Draft','Active','Archived'] as $st): ?>
                    <option value="<?= $st ?>" <?= $tt['timetable_status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fs-8 mb-1 text-muted">Rotation Anchor Date</label>
                <input type="date" name="rotation_start_date" class="form-control form-control-sm"
                       value="<?= esc($tt['rotation_start_date'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label fs-8 mb-1 text-muted">Anchor Day #</label>
                <select name="rotation_start_day" class="form-select form-select-sm">
                    <?php for ($d = 1; $d <= 6; $d++): ?>
                    <option value="<?= $d ?>" <?= (int)$tt['rotation_start_day'] === $d ? 'selected' : '' ?>>Day <?= $d ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3 ms-auto text-end">
                <div class="d-flex align-items-center gap-3 justify-content-end">
                    <!--begin::Copy tool-->
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fs-8 mb-0 text-muted text-nowrap">Copy Day</label>
                        <select id="copy-from" class="form-select form-select-sm w-100px">
                            <?php foreach ($days as $d): ?><option value="<?= $d ?>">Day <?= $d ?></option><?php endforeach; ?>
                        </select>
                        <span class="text-muted">→</span>
                        <select id="copy-to" class="form-select form-select-sm w-100px">
                            <?php foreach ($days as $d): ?><option value="<?= $d ?>">Day <?= $d ?></option><?php endforeach; ?>
                        </select>
                        <button type="button" id="btn-copy" class="btn btn-light-primary btn-sm text-nowrap">Copy</button>
                    </div>
                    <!--end::Copy tool-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Info bar-->

<!--begin::Grid-->
<div class="card">
    <div class="card-header border-0 pt-5 pb-0">
        <h3 class="card-title">
            <?= esc($tt['stream_name'] ?? '') ?> — <?= esc($tt['academic_year']) ?> Term <?= esc($tt['term']) ?>
        </h3>
        <div class="card-toolbar">
            <button type="submit" class="btn btn-primary btn-sm px-6">
                <i class="ki-duotone ki-check fs-3"></i> Save Timetable
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-bordered mb-0 align-middle" id="tt-grid" style="min-width:900px;">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-center text-muted fw-semibold fs-8 text-uppercase" style="min-width:110px;">
                        Time
                    </th>
                    <?php foreach ($days as $day): ?>
                    <th class="py-3 text-center fw-bold text-gray-800" style="min-width:155px;">
                        <span class="badge badge-light-primary px-3 py-2 fs-7">Day <?= $day ?></span>
                    </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($slots as $slot): ?>
            <?php $isBreak = !(int)$slot['is_teaching']; ?>
            <tr class="<?= $isBreak ? 'bg-light-secondary' : '' ?>" data-slot="<?= $slot['slot_id'] ?>">
                <td class="ps-4 py-2 text-center border-end">
                    <div class="<?= $isBreak ? 'badge badge-secondary' : 'fw-semibold text-gray-700 fs-8' ?> mb-1">
                        <?= esc($slot['label']) ?>
                    </div>
                    <?php if ($slot['start_time'] && $slot['end_time']): ?>
                    <div class="text-muted fs-9">
                        <?= substr($slot['start_time'],0,5) ?>–<?= substr($slot['end_time'],0,5) ?>
                    </div>
                    <?php endif; ?>
                </td>
                <?php foreach ($days as $day): ?>
                <td class="py-2 px-2 <?= $isBreak ? 'text-center text-muted' : '' ?>">
                <?php if ($isBreak): ?>
                    <span class="fs-8 text-gray-400">— <?= esc($slot['label']) ?> —</span>
                <?php else: ?>
                    <?php
                    $cell     = $entryMap[$day][$slot['slot_id']] ?? [];
                    $selSubId = (int)($cell['sch_sub_id_fk'] ?? 0);
                    $selTchId = (int)($cell['teacher_id_fk']  ?? 0);
                    $selRoom  = esc($cell['room'] ?? '');
                    $cellId   = "d{$day}s{$slot['slot_id']}";
                    ?>
                    <div class="d-flex flex-column gap-1">
                        <!--Subject-->
                        <select name="entries[<?= $day ?>][<?= $slot['slot_id'] ?>][sch_sub_id_fk]"
                                class="form-select form-select-sm subject-select"
                                data-day="<?= $day ?>"
                                data-slot="<?= $slot['slot_id'] ?>"
                                id="sub-<?= $cellId ?>">
                            <option value="">— Subject —</option>
                            <?php
                            $coreSubjs = array_filter($subjects, fn($s) => $s['subject_type'] === 'Core');
                            $optSubjs  = array_filter($subjects, fn($s) => $s['subject_type'] === 'Optional');
                            ?>
                            <?php if (!empty($coreSubjs)): ?>
                            <optgroup label="Core">
                            <?php foreach ($coreSubjs as $sub): ?>
                                <option value="<?= $sub['sch_sub_id'] ?>" <?= $selSubId == $sub['sch_sub_id'] ? 'selected' : '' ?>><?= esc($sub['subject_name']) ?></option>
                            <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($optSubjs)): ?>
                            <optgroup label="Optional">
                            <?php foreach ($optSubjs as $sub): ?>
                                <option value="<?= $sub['sch_sub_id'] ?>" <?= $selSubId == $sub['sch_sub_id'] ? 'selected' : '' ?>><?= esc($sub['subject_name']) ?></option>
                            <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                        </select>
                        <!--Teacher-->
                        <select name="entries[<?= $day ?>][<?= $slot['slot_id'] ?>][teacher_id_fk]"
                                class="form-select form-select-sm teacher-select"
                                data-day="<?= $day ?>"
                                data-slot="<?= $slot['slot_id'] ?>"
                                id="tch-<?= $cellId ?>">
                            <option value="">— Teacher —</option>
                            <?php
                            $tchrs = $teacherMap[$selSubId] ?? [];
                            foreach ($tchrs as $t):
                            ?>
                            <option value="<?= $t['user_id'] ?>" <?= $selTchId == $t['user_id'] ? 'selected' : '' ?>><?= esc($t['fname'] . ' ' . $t['lname']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <!--Room-->
                        <input type="text"
                               name="entries[<?= $day ?>][<?= $slot['slot_id'] ?>][room]"
                               class="form-control form-control-sm room-input"
                               placeholder="Room"
                               value="<?= $selRoom ?>">
                    </div>
                <?php endif; ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end py-4">
        <a href="<?= base_url('timetable') ?>" class="btn btn-light me-3">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="ki-duotone ki-check fs-2"></i> Save Timetable
        </button>
    </div>
</div>
<!--end::Grid-->

</form>
</div>
</div>

<script>
const TEACHER_MAP = <?= $teacherMapJson ?>;

// Subject → update teacher dropdown
document.querySelectorAll('.subject-select').forEach(sel => {
    sel.addEventListener('change', function () {
        const day    = this.dataset.day;
        const slot   = this.dataset.slot;
        const subId  = this.value;
        const tchSel = document.getElementById('tch-d' + day + 's' + slot);
        if (!tchSel) return;

        const teachers = (subId && TEACHER_MAP[subId]) ? TEACHER_MAP[subId] : [];
        tchSel.innerHTML = '<option value="">— Teacher —</option>' +
            teachers.map(t => `<option value="${t.user_id}">${t.fname} ${t.lname}</option>`).join('');
    });
});

// Copy Day tool
document.getElementById('btn-copy').addEventListener('click', function () {
    const from = document.getElementById('copy-from').value;
    const to   = document.getElementById('copy-to').value;
    if (from === to) { alert('Source and destination day are the same.'); return; }

    document.querySelectorAll(`select.subject-select[data-day="${to}"]`).forEach(toSel => {
        const slot   = toSel.dataset.slot;
        const fromSel = document.querySelector(`select.subject-select[data-day="${from}"][data-slot="${slot}"]`);
        if (!fromSel) return;

        toSel.value = fromSel.value;
        toSel.dispatchEvent(new Event('change')); // trigger teacher reload

        // Copy room
        const fromRoom = document.querySelector(`input.room-input[name="entries[${from}][${slot}][room]"]`);
        const toRoom   = document.querySelector(`input.room-input[name="entries[${to}][${slot}][room]"]`);
        if (fromRoom && toRoom) toRoom.value = fromRoom.value;
    });

    // After teacher lists are rebuilt, copy teacher selections
    setTimeout(() => {
        document.querySelectorAll(`select.teacher-select[data-day="${to}"]`).forEach(toTch => {
            const slot    = toTch.dataset.slot;
            const fromTch = document.querySelector(`select.teacher-select[data-day="${from}"][data-slot="${slot}"]`);
            if (fromTch) toTch.value = fromTch.value;
        });
    }, 50);

    // Highlight the copied day column
    document.querySelectorAll(`[data-day="${to}"]`).forEach(el => {
        el.closest('td')?.classList.add('table-primary');
        setTimeout(() => el.closest('td')?.classList.remove('table-primary'), 1200);
    });
});
</script>
