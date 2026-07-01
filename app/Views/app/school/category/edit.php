<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Edit School Category</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('school/category') ?>" class="text-muted text-hover-primary">School Category</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($category['sch_cat_name']) ?></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Edit</li>
            </ul>
        </div>
        <a href="<?= base_url('school/category') ?>" class="btn btn-light">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to List
        </a>
    </div>
</div>
<!--end::Toolbar-->

<?php
$oldInput = session()->getFlashdata('_ci_old_input') ?? [];
$hasOld   = !empty($oldInput);

$valInitial = $hasOld ? ($oldInput['sch_cat_initial'] ?? '') : ($category['sch_cat_initial'] ?? '');
$valName    = $hasOld ? ($oldInput['sch_cat_name']    ?? '') : ($category['sch_cat_name']    ?? '');
$valNumTerm = $hasOld ? ($oldInput['num_of_term_in_year'] ?? '') : count($terms ?? []);
$valLabel   = $hasOld ? ($oldInput['label_for_term']      ?? '') : ($config['label_for_term'] ?? 'Term');

// Build per-term data keyed by term_num for JS
$termData = [];
foreach (($terms ?? []) as $t) {
    $n = (int) $t['term_num'];
    $termData[$n] = [
        'num_of_week'  => $hasOld ? ($oldInput["num_of_week_{$n}"]       ?? $t['num_of_week'])       : $t['num_of_week'],
        'start_day'    => $hasOld ? ($oldInput["term_start_day_{$n}"]    ?? $t['term_start_day'])    : $t['term_start_day'],
        'start_month'  => $hasOld ? ($oldInput["term_start_month_{$n}"]  ?? $t['term_start_month'])  : $t['term_start_month'],
        'end_day'      => $hasOld ? ($oldInput["term_end_day_{$n}"]      ?? $t['term_end_day'])      : $t['term_end_day'],
        'end_month'    => $hasOld ? ($oldInput["term_end_month_{$n}"]    ?? $t['term_end_month'])    : $t['term_end_month'],
    ];
}
if ($hasOld && !empty($oldInput['num_of_term_in_year'])) {
    for ($i = 1; $i <= (int)$oldInput['num_of_term_in_year']; $i++) {
        if (!isset($termData[$i])) {
            $termData[$i] = [
                'num_of_week' => $oldInput["num_of_week_{$i}"]      ?? '',
                'start_day'   => $oldInput["term_start_day_{$i}"]   ?? '',
                'start_month' => $oldInput["term_start_month_{$i}"]  ?? '',
                'end_day'     => $oldInput["term_end_day_{$i}"]      ?? '',
                'end_month'   => $oldInput["term_end_month_{$i}"]    ?? '',
            ];
        }
    }
}
?>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <?= $this->include('templates/flash_messages') ?>

        <form id="school_cat_edit_form"
              action="<?= base_url('school/category/update/' . (int)$category['sch_cat_id']) ?>"
              method="POST">
            <?= csrf_field() ?>

            <!--begin::Section 1 - Category Information-->
            <div class="card mb-6">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center bg-light-primary rounded-2 flex-shrink-0" style="width:40px;height:40px;">
                                <i class="ki-duotone ki-bank fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-gray-800 mb-0 fs-5">Category Information</h3>
                                <span class="text-muted fs-7">Basic details about the school category</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-5">
                        <div class="col-lg-4">
                            <label class="form-label required fw-semibold">Category Initial / Short Name</label>
                            <input type="text" name="sch_cat_initial"
                                class="form-control <?= session('validation')?->hasError('sch_cat_initial') ? 'is-invalid' : '' ?>"
                                placeholder="e.g. Primary, Secondary"
                                value="<?= esc($valInitial) ?>" />
                            <div class="form-text text-muted">A short label used as the category identifier</div>
                            <?php if (session('validation')?->hasError('sch_cat_initial')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('sch_cat_initial') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-8">
                            <label class="form-label required fw-semibold">Category Full Name</label>
                            <input type="text" name="sch_cat_name"
                                class="form-control <?= session('validation')?->hasError('sch_cat_name') ? 'is-invalid' : '' ?>"
                                placeholder="e.g. Primary School, Secondary School"
                                value="<?= esc($valName) ?>" />
                            <?php if (session('validation')?->hasError('sch_cat_name')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('sch_cat_name') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Section 1-->

            <!--begin::Section 2 - Configuration-->
            <div class="card mb-6">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center bg-light-info rounded-2 flex-shrink-0" style="width:40px;height:40px;">
                                <i class="ki-duotone ki-setting-2 fs-3 text-info"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-gray-800 mb-0 fs-5">Academic Year Configuration</h3>
                                <span class="text-muted fs-7">Define the term structure — dates and weeks apply every year</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-5">
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Number of Terms per Year</label>
                            <select name="num_of_term_in_year" id="num_of_term"
                                class="form-select <?= session('validation')?->hasError('num_of_term_in_year') ? 'is-invalid' : '' ?>">
                                <option value="">-- Select --</option>
                                <?php for ($n = 1; $n <= 12; $n++): ?>
                                    <option value="<?= $n ?>" <?= (string)$valNumTerm === (string)$n ? 'selected' : '' ?>><?= $n ?></option>
                                <?php endfor; ?>
                            </select>
                            <div class="form-text text-muted">Changing this regenerates the term cards below</div>
                            <?php if (session('validation')?->hasError('num_of_term_in_year')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('num_of_term_in_year') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label required fw-semibold">Label for Each Term</label>
                            <input type="text" name="label_for_term" id="label_for_term_input"
                                class="form-control <?= session('validation')?->hasError('label_for_term') ? 'is-invalid' : '' ?>"
                                placeholder="e.g. Term, Semester, Quarter"
                                value="<?= esc($valLabel) ?>" />
                            <div class="form-text text-muted">How each period is named (e.g. "Semester 1")</div>
                            <?php if (session('validation')?->hasError('label_for_term')): ?>
                                <div class="invalid-feedback"><?= session('validation')->getError('label_for_term') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Section 2-->

            <!--begin::Section 3 - Term Entries -->
            <div id="term_entries_wrapper" class="card mb-6" style="display:none;">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center bg-light-success rounded-2 flex-shrink-0" style="width:40px;height:40px;">
                                <i class="ki-duotone ki-calendar-tick fs-3 text-success">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                                </i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-gray-800 mb-0 fs-5">Term Details</h3>
                                <span class="text-muted fs-7" id="term_section_subtitle">Dates and weeks per term — applies every year</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-5" id="term_entries_container"></div>
                </div>
            </div>
            <!--end::Section 3-->

            <div class="d-flex justify-content-end gap-3 mb-10">
                <a href="<?= base_url('school/category') ?>" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

<script>
(function () {
    var MONTHS = ['','January','February','March','April','May','June',
                  'July','August','September','October','November','December'];

    var numOfTermSel = document.getElementById('num_of_term');
    var wrapper      = document.getElementById('term_entries_wrapper');
    var container    = document.getElementById('term_entries_container');
    var subtitle     = document.getElementById('term_section_subtitle');
    var labelInput   = document.getElementById('label_for_term_input');

    var existingTerms = <?= json_encode($termData) ?>;

    function getLabel() {
        return labelInput ? (labelInput.value.trim() || 'Term') : 'Term';
    }

    function monthSelect(name, selected) {
        var html = '<select name="' + name + '" class="form-select form-select-sm" required>';
        html += '<option value="">Month</option>';
        for (var m = 1; m <= 12; m++) {
            var sel = (selected && parseInt(selected) === m) ? ' selected' : '';
            html += '<option value="' + m + '"' + sel + '>' + MONTHS[m] + '</option>';
        }
        return html + '</select>';
    }

    function dayInput(name, val) {
        return '<input type="number" name="' + name + '" min="1" max="31" placeholder="Day" ' +
               'class="form-control form-control-sm" style="width:74px;" value="' + (val ? parseInt(val) : '') + '" required />';
    }

    function weeksInput(name, val) {
        return '<input type="number" name="' + name + '" min="1" max="52" placeholder="e.g. 10" ' +
               'class="form-control form-control-sm" value="' + (val ? parseInt(val) : '') + '" required />';
    }

    function buildTermEntries(n) {
        if (!n || n < 1) {
            wrapper.style.display = 'none';
            container.innerHTML = '';
            return;
        }

        var label = getLabel();
        subtitle.textContent = label + ' dates and weeks — applies every year';
        container.innerHTML = '';

        for (var i = 1; i <= n; i++) {
            var t = existingTerms[i] || {};

            container.innerHTML +=
                '<div class="col-md-6 col-lg-4">' +
                '<div class="card border border-dashed border-success-subtle h-100">' +
                '<div class="card-body p-4">' +

                '<div class="d-flex align-items-center gap-2 mb-5">' +
                '<div class="d-flex align-items-center justify-content-center bg-light-success rounded-1 flex-shrink-0" style="width:32px;height:32px;">' +
                '<span class="fw-bold text-success fs-7">' + i + '</span></div>' +
                '<span class="fw-bold text-gray-700 fs-6">' + label + ' ' + i + '</span>' +
                '</div>' +

                '<div class="mb-4">' +
                '<label class="form-label required fw-semibold fs-7 text-gray-600 mb-2">Number of Weeks</label>' +
                weeksInput('num_of_week_' + i, t.num_of_week || '') +
                '</div>' +

                '<div class="mb-4">' +
                '<label class="form-label required fw-semibold fs-7 text-gray-600 mb-2">Start Date</label>' +
                '<div class="d-flex align-items-center gap-2">' +
                dayInput('term_start_day_' + i, t.start_day || '') +
                monthSelect('term_start_month_' + i, t.start_month || '') +
                '</div></div>' +

                '<div>' +
                '<label class="form-label required fw-semibold fs-7 text-gray-600 mb-2">End Date</label>' +
                '<div class="d-flex align-items-center gap-2">' +
                dayInput('term_end_day_' + i, t.end_day || '') +
                monthSelect('term_end_month_' + i, t.end_month || '') +
                '</div></div>' +

                '</div></div></div>';
        }

        wrapper.style.display = '';
    }

    numOfTermSel.addEventListener('change', function () {
        buildTermEntries(parseInt(this.value) || 0);
    });

    if (labelInput) {
        labelInput.addEventListener('input', function () {
            var n = parseInt(numOfTermSel.value) || 0;
            if (n > 0) buildTermEntries(n);
        });
    }

    var initialN = parseInt(numOfTermSel.value) || 0;
    if (initialN > 0) buildTermEntries(initialN);

    document.getElementById('school_cat_edit_form').addEventListener('submit', function (e) {
        var n = parseInt(numOfTermSel.value) || 0;
        if (n === 0) { alert('Please select the number of terms per year.'); e.preventDefault(); return; }
        var label = getLabel();
        for (var i = 1; i <= n; i++) {
            var wk = document.querySelector('[name="num_of_week_' + i + '"]');
            var sd = document.querySelector('[name="term_start_day_' + i + '"]');
            var sm = document.querySelector('[name="term_start_month_' + i + '"]');
            var ed = document.querySelector('[name="term_end_day_' + i + '"]');
            var em = document.querySelector('[name="term_end_month_' + i + '"]');
            if (!wk || !wk.value || parseInt(wk.value) < 1) {
                alert(label + ' ' + i + ': please enter the number of weeks.'); e.preventDefault(); return;
            }
            if (!sd || !sd.value || !sm || !sm.value) {
                alert(label + ' ' + i + ': please enter a start day and select a start month.'); e.preventDefault(); return;
            }
            if (!ed || !ed.value || !em || !em.value) {
                alert(label + ' ' + i + ': please enter an end day and select an end month.'); e.preventDefault(); return;
            }
        }
    });
})();
</script>
