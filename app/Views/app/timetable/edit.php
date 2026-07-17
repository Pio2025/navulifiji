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
$numDays        = count($days);
$teacherMapJson = json_encode((object) $teacherMap);
$optGroupsJson  = json_encode(array_values($subjectGrps['optional_groups']));
$optEntryMapJson = json_encode($optEntryMapJs);
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
                    <?php foreach ($days as $d): ?>
                    <option value="<?= $d ?>" <?= (int)$tt['rotation_start_day'] === $d ? 'selected' : '' ?>>Day <?= $d ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 ms-auto text-end">
                <div class="d-flex align-items-center gap-2 justify-content-end">
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
            </div>
        </div>
    </div>
</div>
<!--end::Info bar-->

<?php if (!empty($subjectGrps['optional_groups'])): ?>
<div class="alert alert-dismissible bg-light-info d-flex align-items-center p-5 mb-5">
    <i class="ki-duotone ki-information fs-2x text-info me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
    <div class="d-flex flex-column">
        <span class="fw-bold fs-6">Optional Subject Blocks</span>
        <span class="fs-7">
            Optional subjects are grouped below. Selecting an optional group assigns all its subjects to that period simultaneously — each with their own teacher.
            <?php foreach ($subjectGrps['optional_groups'] as $grp): ?>
            <span class="badge badge-light-primary ms-2">Opt <?= $grp['option_num'] ?>: <?= esc($grp['label']) ?></span>
            <?php endforeach; ?>
        </span>
    </div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
    </button>
</div>
<?php endif; ?>

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
                    <th class="ps-4 py-3 text-center text-muted fw-semibold fs-8 text-uppercase" style="min-width:110px;">Time</th>
                    <?php foreach ($days as $day): ?>
                    <th class="py-3 text-center fw-bold text-gray-800" style="min-width:170px;">
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
                    $isOpt    = !empty($cell['is_optional']);
                    $selSubId = $isOpt
                        ? 'opt:' . ($cell['option_num'] ?? 1)
                        : (int)($cell['sch_sub_id_fk'] ?? 0);
                    $selTchId = (int)($cell['teacher_id_fk'] ?? 0);
                    $selRoom  = esc($cell['room'] ?? '');
                    $cellId   = "d{$day}s{$slot['slot_id']}";
                    ?>
                    <div class="d-flex flex-column gap-1">
                        <!-- Hidden optional flags -->
                        <input type="hidden" name="entries[<?= $day ?>][<?= $slot['slot_id'] ?>][is_optional]"
                               value="<?= $isOpt ? '1' : '0' ?>" id="iso-<?= $cellId ?>">
                        <input type="hidden" name="entries[<?= $day ?>][<?= $slot['slot_id'] ?>][option_num]"
                               value="<?= $isOpt ? (int)($cell['option_num'] ?? 1) : '' ?>" id="isoN-<?= $cellId ?>">

                        <!-- Subject select -->
                        <select name="entries[<?= $day ?>][<?= $slot['slot_id'] ?>][sch_sub_id_fk]"
                                class="form-select form-select-sm subject-select"
                                data-day="<?= $day ?>"
                                data-slot="<?= $slot['slot_id'] ?>"
                                id="sub-<?= $cellId ?>">
                            <option value="">— Subject —</option>
                            <?php if (!empty($subjectGrps['core'])): ?>
                            <optgroup label="Core">
                            <?php foreach ($subjectGrps['core'] as $sub): ?>
                                <option value="<?= $sub['sch_sub_id'] ?>"
                                    <?= !$isOpt && $selSubId == $sub['sch_sub_id'] ? 'selected' : '' ?>>
                                    <?= esc($sub['subject_name']) ?>
                                </option>
                            <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($subjectGrps['optional_groups'])): ?>
                            <optgroup label="Optional Subjects">
                            <?php foreach ($subjectGrps['optional_groups'] as $grp): ?>
                                <option value="opt:<?= $grp['option_num'] ?>"
                                    <?= $isOpt && $selSubId === 'opt:' . $grp['option_num'] ? 'selected' : '' ?>>
                                    <?= esc($grp['label']) ?>
                                </option>
                            <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                        </select>

                        <!-- Teacher select (hidden when optional group selected) -->
                        <select name="entries[<?= $day ?>][<?= $slot['slot_id'] ?>][teacher_id_fk]"
                                class="form-select form-select-sm teacher-select"
                                data-day="<?= $day ?>"
                                data-slot="<?= $slot['slot_id'] ?>"
                                id="tch-<?= $cellId ?>"
                                <?= $isOpt ? 'disabled style="display:none"' : '' ?>>
                            <option value="">— Teacher —</option>
                            <?php if (!$isOpt): ?>
                            <?php foreach ($teacherMap[$selSubId] ?? [] as $t): ?>
                            <option value="<?= $t['user_id'] ?>" <?= $selTchId == $t['user_id'] ? 'selected' : '' ?>>
                                <?= esc($t['fname'] . ' ' . $t['lname']) ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                        <!-- Room input (hidden when optional group selected) -->
                        <input type="text"
                               name="entries[<?= $day ?>][<?= $slot['slot_id'] ?>][room]"
                               class="form-control form-control-sm room-input"
                               data-cellid="<?= $cellId ?>"
                               placeholder="Room"
                               value="<?= !$isOpt ? $selRoom : '' ?>"
                               <?= $isOpt ? 'disabled style="display:none"' : '' ?>>

                        <!-- Optional subjects panel -->
                        <div id="opt-panel-<?= $cellId ?>" <?= $isOpt ? '' : 'style="display:none"' ?>></div>
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
const TEACHER_MAP   = <?= $teacherMapJson ?>;
const OPT_GROUPS    = <?= $optGroupsJson ?>;
const OPT_ENTRY_MAP = <?= $optEntryMapJson ?>;

// Build lookup: option_num → group
const OPT_GROUP_MAP = {};
OPT_GROUPS.forEach(g => { OPT_GROUP_MAP[g.option_num] = g; });

function showOptionalPanel(subSel, optNum, savedSubjects) {
    const day    = subSel.dataset.day;
    const slot   = subSel.dataset.slot;
    const cellId = 'd' + day + 's' + slot;

    document.getElementById('iso-'  + cellId).value = '1';
    document.getElementById('isoN-' + cellId).value = optNum;

    const tchSel  = document.getElementById('tch-' + cellId);
    const roomInp = document.querySelector(`input.room-input[data-cellid="${cellId}"]`);
    if (tchSel)  { tchSel.disabled  = true;  tchSel.style.display  = 'none'; }
    if (roomInp) { roomInp.disabled = true;  roomInp.style.display = 'none'; }

    const panel = document.getElementById('opt-panel-' + cellId);
    panel.innerHTML = '';
    panel.style.display = '';

    const grp = OPT_GROUP_MAP[optNum];
    if (!grp) return;

    grp.subjects.forEach(sub => {
        const saved    = (savedSubjects && savedSubjects[sub.sch_sub_id]) ? savedSubjects[sub.sch_sub_id] : {};
        const teachers = TEACHER_MAP[sub.sch_sub_id] || [];
        const tchOpts  = teachers.map(t => {
            const sel = String(saved.teacher_id_fk) === String(t.user_id) ? 'selected' : '';
            return `<option value="${t.user_id}" ${sel}>${t.fname} ${t.lname}</option>`;
        }).join('');

        const row = document.createElement('div');
        row.className = 'border-top pt-1 mt-1';
        row.innerHTML = `
            <div class="fs-9 fw-semibold text-primary mb-1">${sub.subject_name}</div>
            <select name="opt_entries[${day}][${slot}][${sub.sch_sub_id}][teacher_id_fk]"
                    class="form-select form-select-sm mb-1">
                <option value="">— Teacher —</option>
                ${tchOpts}
            </select>
            <input type="text"
                   name="opt_entries[${day}][${slot}][${sub.sch_sub_id}][room]"
                   class="form-control form-control-sm"
                   placeholder="Room"
                   value="${saved.room || ''}">
        `;
        panel.appendChild(row);
    });
}

function hideOptionalPanel(subSel) {
    const day    = subSel.dataset.day;
    const slot   = subSel.dataset.slot;
    const cellId = 'd' + day + 's' + slot;

    document.getElementById('iso-'  + cellId).value = '0';
    document.getElementById('isoN-' + cellId).value = '';

    const tchSel  = document.getElementById('tch-' + cellId);
    const roomInp = document.querySelector(`input.room-input[data-cellid="${cellId}"]`);
    if (tchSel)  { tchSel.disabled  = false; tchSel.style.display  = ''; }
    if (roomInp) { roomInp.disabled = false; roomInp.style.display = ''; }

    const panel = document.getElementById('opt-panel-' + cellId);
    panel.innerHTML = '';
    panel.style.display = 'none';
}

// Initialize pre-saved optional cells on page load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.subject-select').forEach(sel => {
        if (sel.value && sel.value.startsWith('opt:')) {
            const optNum = parseInt(sel.value.split(':')[1]);
            const day    = sel.dataset.day;
            const slot   = sel.dataset.slot;
            const saved  = (OPT_ENTRY_MAP[day] && OPT_ENTRY_MAP[day][slot])
                ? OPT_ENTRY_MAP[day][slot].subjects
                : null;
            showOptionalPanel(sel, optNum, saved);
        }
    });
});

// Subject change handler
document.querySelectorAll('.subject-select').forEach(sel => {
    sel.addEventListener('change', function () {
        const val = this.value;
        if (val && val.startsWith('opt:')) {
            const optNum = parseInt(val.split(':')[1]);
            showOptionalPanel(this, optNum, null);
        } else {
            hideOptionalPanel(this);
            // Refresh teacher dropdown for core subject
            const day    = this.dataset.day;
            const slot   = this.dataset.slot;
            const tchSel = document.getElementById('tch-d' + day + 's' + slot);
            if (!tchSel) return;
            const teachers = (val && TEACHER_MAP[val]) ? TEACHER_MAP[val] : [];
            tchSel.innerHTML = '<option value="">— Teacher —</option>' +
                teachers.map(t => `<option value="${t.user_id}">${t.fname} ${t.lname}</option>`).join('');
        }
    });
});

// Copy Day tool
document.getElementById('btn-copy').addEventListener('click', function () {
    const from = document.getElementById('copy-from').value;
    const to   = document.getElementById('copy-to').value;
    if (from === to) { alert('Source and destination day are the same.'); return; }

    document.querySelectorAll(`select.subject-select[data-day="${to}"]`).forEach(toSel => {
        const slot    = toSel.dataset.slot;
        const fromSel = document.querySelector(`select.subject-select[data-day="${from}"][data-slot="${slot}"]`);
        if (!fromSel) return;

        toSel.value = fromSel.value;
        toSel.dispatchEvent(new Event('change'));

        const fromRoom = document.querySelector(`input.room-input[name="entries[${from}][${slot}][room]"]`);
        const toRoom   = document.querySelector(`input.room-input[name="entries[${to}][${slot}][room]"]`);
        if (fromRoom && toRoom) toRoom.value = fromRoom.value;
    });

    // Copy core teacher selections after dropdowns rebuild
    setTimeout(() => {
        document.querySelectorAll(`select.teacher-select[data-day="${to}"]`).forEach(toTch => {
            const slot    = toTch.dataset.slot;
            const fromTch = document.querySelector(`select.teacher-select[data-day="${from}"][data-slot="${slot}"]`);
            if (fromTch && !toTch.disabled) toTch.value = fromTch.value;
        });
    }, 50);

    document.querySelectorAll(`[data-day="${to}"]`).forEach(el => {
        el.closest('td')?.classList.add('table-primary');
        setTimeout(() => el.closest('td')?.classList.remove('table-primary'), 1200);
    });
});
</script>
