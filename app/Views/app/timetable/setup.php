<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading fw-bold fs-3 my-0">Timetable Structure Setup</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('timetable') ?>" class="text-muted text-hover-primary">Timetable</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Setup</li>
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

<?php if ($existing): ?>
<div class="alert alert-dismissible bg-light-warning d-flex align-items-center p-5 mb-5">
    <i class="ki-duotone ki-information fs-2x text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
    <div>
        <span class="fw-bold fs-6 d-block">Existing Structure Found</span>
        <span class="fs-7">Your school already has a timetable structure configured. Saving a new one will update the template and rebuild its period slots.
        <strong>Timetables that already reference this template will need to be re-edited.</strong></span>
    </div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
    </button>
</div>
<?php endif; ?>

<div class="row g-5">

    <!--begin::Config form-->
    <div class="col-lg-6">
        <form method="POST" action="<?= base_url('timetable/setup/save') ?>" id="setup-form">
        <?= csrf_field() ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Configure Timetable Structure</h3>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Changes apply to new timetables using this structure</span>
                </div>
            </div>
            <div class="card-body">

                <!--begin::Rotation Days-->
                <div class="mb-7">
                    <label class="form-label fw-semibold">Rotation Days</label>
                    <div class="input-group input-group-sm w-150px">
                        <span class="input-group-text">Days</span>
                        <input type="number" id="num_days" name="num_days"
                               class="form-control setup-input" min="1" max="10"
                               value="<?= $cfg['num_days'] ?>">
                    </div>
                    <div class="form-text">How many days before the cycle repeats (e.g. 6 for Day 1–6)</div>
                </div>

                <div class="separator my-6"></div>

                <!--begin::School Start Time + Period Duration-->
                <div class="row g-5 mb-7">
                    <div class="col-6">
                        <label class="form-label fw-semibold">School Start Time</label>
                        <input type="time" id="start_time" name="start_time"
                               class="form-control form-control-sm setup-input"
                               value="<?= $cfg['start_time'] ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Period Duration</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="period_duration" name="period_duration"
                                   class="form-control setup-input" min="20" max="120"
                                   value="<?= $cfg['period_duration'] ?>">
                            <span class="input-group-text">min</span>
                        </div>
                    </div>
                </div>

                <!--begin::Period blocks-->
                <div class="bg-light-primary rounded p-5 mb-5">
                    <div class="fw-bold text-gray-800 mb-4">Morning Block</div>
                    <div class="row g-4">
                        <div class="col-6">
                            <label class="form-label fs-8 text-muted">Periods before Recess</label>
                            <input type="number" id="morning_periods" name="morning_periods"
                                   class="form-control form-control-sm setup-input" min="1" max="8"
                                   value="<?= $cfg['morning_periods'] ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 text-muted">Recess Duration</label>
                            <div class="input-group input-group-sm">
                                <input type="number" id="recess_duration" name="recess_duration"
                                       class="form-control setup-input" min="5" max="60"
                                       value="<?= $cfg['recess_duration'] ?>">
                                <span class="input-group-text">min</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-light-info rounded p-5 mb-5">
                    <div class="fw-bold text-gray-800 mb-4">Midday Block</div>
                    <div class="row g-4">
                        <div class="col-6">
                            <label class="form-label fs-8 text-muted">Periods after Recess</label>
                            <input type="number" id="midday_periods" name="midday_periods"
                                   class="form-control form-control-sm setup-input" min="0" max="8"
                                   value="<?= $cfg['midday_periods'] ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-8 text-muted">Lunch Duration</label>
                            <div class="input-group input-group-sm">
                                <input type="number" id="lunch_duration" name="lunch_duration"
                                       class="form-control setup-input" min="10" max="120"
                                       value="<?= $cfg['lunch_duration'] ?>">
                                <span class="input-group-text">min</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-light-success rounded p-5 mb-5">
                    <div class="fw-bold text-gray-800 mb-4">Afternoon Block</div>
                    <div class="row g-4">
                        <div class="col-6">
                            <label class="form-label fs-8 text-muted">Periods after Lunch</label>
                            <input type="number" id="afternoon_periods" name="afternoon_periods"
                                   class="form-control form-control-sm setup-input" min="0" max="8"
                                   value="<?= $cfg['afternoon_periods'] ?>">
                            <div class="form-text">Set 0 to skip Lunch block</div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-end py-4">
                <a href="<?= base_url('timetable') ?>" class="btn btn-light me-3">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-2"></i> Save Structure
                </button>
            </div>
        </div>
        </form>
    </div>
    <!--end::Config form-->

    <!--begin::Live preview-->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Preview</h3>
                <div class="card-toolbar">
                    <span class="badge badge-light-primary fs-8" id="preview-total">0 periods</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-bordered mb-0 align-middle" style="font-size:0.85rem;">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-5 py-3 text-muted fw-semibold fs-8">Period / Break</th>
                            <th class="py-3 text-muted fw-semibold fs-8">Start</th>
                            <th class="py-3 text-muted fw-semibold fs-8">End</th>
                            <th class="py-3 text-muted fw-semibold fs-8">Duration</th>
                        </tr>
                    </thead>
                    <tbody id="preview-body">
                        <tr><td colspan="4" class="text-center text-muted py-8">Adjust the settings to preview</td></tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="card-footer text-muted fs-8 text-end" id="preview-end">—</div>
        </div>
    </div>
    <!--end::Live preview-->

</div>

</div>
</div>

<script>
function parseTime(t) {
    const [h, m] = t.split(':').map(Number);
    return h * 60 + (m || 0);
}

function formatTime(totalMin) {
    const h = Math.floor(totalMin / 60) % 24;
    const m = totalMin % 60;
    return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
}

function generatePreview() {
    const startTime    = document.getElementById('start_time').value    || '08:00';
    const pdDur        = parseInt(document.getElementById('period_duration').value)  || 40;
    const morningPds   = parseInt(document.getElementById('morning_periods').value)  || 3;
    const recessDur    = parseInt(document.getElementById('recess_duration').value)  || 20;
    const middayPds    = parseInt(document.getElementById('midday_periods').value)   || 3;
    const lunchDur     = parseInt(document.getElementById('lunch_duration').value)   || 40;
    const afternoonPds = parseInt(document.getElementById('afternoon_periods').value) || 3;

    const slots = [];
    let time = parseTime(startTime);
    let pdNum = 1;

    for (let i = 0; i < morningPds; i++) {
        slots.push({ label: 'Period ' + pdNum++, teaching: true,  duration: pdDur,     start: time });
        time += pdDur;
    }
    slots.push({ label: 'Recess', teaching: false, duration: recessDur, start: time });
    time += recessDur;

    for (let i = 0; i < middayPds; i++) {
        slots.push({ label: 'Period ' + pdNum++, teaching: true,  duration: pdDur,     start: time });
        time += pdDur;
    }

    if (afternoonPds > 0) {
        slots.push({ label: 'Lunch',  teaching: false, duration: lunchDur,  start: time });
        time += lunchDur;
        for (let i = 0; i < afternoonPds; i++) {
            slots.push({ label: 'Period ' + pdNum++, teaching: true, duration: pdDur, start: time });
            time += pdDur;
        }
    }

    const totalPds = morningPds + middayPds + (afternoonPds > 0 ? afternoonPds : 0);
    document.getElementById('preview-total').textContent = totalPds + ' period' + (totalPds !== 1 ? 's' : '');
    document.getElementById('preview-end').textContent   = 'School ends at ' + formatTime(time);

    const body = document.getElementById('preview-body');
    body.innerHTML = '';
    slots.forEach(s => {
        const end = s.start + s.duration;
        const tr  = document.createElement('tr');
        tr.className = s.teaching ? '' : 'table-secondary';
        tr.innerHTML = `
            <td class="ps-5 py-2 fw-${s.teaching ? 'semibold' : 'normal'} ${s.teaching ? 'text-gray-800' : 'text-muted'}">
                ${s.label}
            </td>
            <td class="py-2">${formatTime(s.start)}</td>
            <td class="py-2">${formatTime(end)}</td>
            <td class="py-2 text-muted">${s.duration} min</td>
        `;
        body.appendChild(tr);
    });
}

document.querySelectorAll('.setup-input').forEach(el => {
    el.addEventListener('input', generatePreview);
});

generatePreview();
</script>
