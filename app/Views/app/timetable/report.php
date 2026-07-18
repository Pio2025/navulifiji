<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6" id="toolbar-no-print">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading fw-bold fs-3 my-0">Timetable Report</h1>
        </div>
        <div class="d-flex align-items-center gap-4 toolbar-no-print">
            <div class="d-flex align-items-center gap-4">
                <label class="form-check form-check-custom form-check-sm form-check-solid d-flex align-items-center gap-2 mb-0" for="tt_toggle_room">
                    <input class="form-check-input" type="checkbox" id="tt_toggle_room">
                    <span class="fs-8 text-muted">Show Room</span>
                </label>
                <label class="form-check form-check-custom form-check-sm form-check-solid d-flex align-items-center gap-2 mb-0" for="tt_toggle_cat_initial">
                    <input class="form-check-input" type="checkbox" id="tt_toggle_cat_initial">
                    <span class="fs-8 text-muted">Show Category Initial</span>
                </label>
            </div>
            <div class="d-flex gap-2">
                <a id="tt_pdf_link" href="<?= base_url('timetable/report/' . $tt['timetable_id'] . '/pdf') ?>"
                   data-base="<?= base_url('timetable/report/' . $tt['timetable_id'] . '/pdf') ?>"
                   class="btn btn-danger btn-sm" target="_blank">
                    <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> Download PDF
                </a>
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="ki-duotone ki-printer fs-2"><span class="path1"></span><span class="path2"></span></i> Print
                </button>
                <a href="<?= base_url('timetable/detail/' . $tt['timetable_id']) ?>" class="btn btn-light btn-sm">Back</a>
            </div>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<div class="card" id="print-area">
    <div class="card-body">

        <!--begin::Header (school logo + info — matches PDF style)-->
        <div class="d-flex align-items-center justify-content-center mb-6" style="gap:16px;">
            <!--School logo-->
            <?php
            $logoFile = $school['sch_logo'] ?? '';
            $logoPath = base_url('uploads/school/logo/' . $logoFile);
            ?>
            <div style="width:60px;height:60px;flex-shrink:0;">
                <?php if (!empty($logoFile)): ?>
                <img src="<?= $logoPath ?>" alt="Logo" style="width:60px;height:60px;object-fit:contain;">
                <?php else: ?>
                <div style="width:60px;height:60px;background:#e8f0ff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="ki-duotone ki-building fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <?php endif; ?>
            </div>

            <!--Centre text-->
            <div class="text-center" style="max-width:420px;">
                <h2 class="fw-bold text-primary mb-1" style="font-size:1.3rem;letter-spacing:0.5px;">
                    <?= strtoupper(esc($school['sch_name'] ?? $tt['sch_name'] ?? '')) ?>
                </h2>
                <h5 class="fw-semibold text-gray-700 mb-1" style="font-size:0.95rem;">
                    CLASS TIMETABLE — <?= strtoupper(esc($tt['stream_name'] ?? '')) ?>
                    &nbsp;·&nbsp; <?= esc($tt['academic_year']) ?> TERM <?= esc($tt['term']) ?>
                </h5>
                <?php
                $contact = array_filter([
                    $school['sch_address'] ?? '',
                    !empty($school['sch_phone']) ? 'Ph: ' . $school['sch_phone'] : '',
                    $school['sch_email']   ?? '',
                ]);
                ?>
                <?php if ($contact): ?>
                <div class="text-muted fs-8"><?= esc(implode('  ·  ', $contact)) ?></div>
                <?php endif; ?>
            </div>

            <!--Navuli logo-->
            <div style="width:55px;height:55px;flex-shrink:0;">
                <img src="<?= base_url('icon.png') ?>" alt="Navuli" style="width:55px;height:55px;object-fit:contain;" onerror="this.style.display='none'">
            </div>
        </div>

        <hr style="border-top:2px solid #1a56db;margin:0 0 3px;">
        <hr style="border-top:1px solid #93c5fd;margin:0 0 10px;">

        <!--Rotation info-->
        <?php if (!empty($tt['rotation_start_date'])): ?>
        <p class="text-center text-muted fs-8 mb-4">
            Rotation: Day 1–<?= count($days) ?> cycle
            &nbsp;·&nbsp; Day <?= (int)$tt['rotation_start_day'] ?> began on <?= date('d F Y', strtotime($tt['rotation_start_date'])) ?>
        </p>
        <?php else: ?>
        <div class="mb-4"></div>
        <?php endif; ?>

        <!--begin::Grid-->
        <div class="table-responsive">
        <table id="tt_grid" class="table table-bordered align-middle text-center" style="font-size:0.8rem;">
            <thead>
                <tr style="background:#f0f7ff;">
                    <th class="fw-bold py-3 text-primary" style="min-width:100px;border:1px solid #c8daf0;">Period / Time</th>
                    <?php foreach ($days as $day): ?>
                    <th class="fw-bold py-3 text-primary" style="min-width:130px;border:1px solid #c8daf0;">Day <?= $day ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($slots as $slot): ?>
            <?php $isBreak = !(int)$slot['is_teaching']; ?>
            <tr style="<?= $isBreak ? 'background:#e8edfc;' : '' ?>">
                <td class="py-2 fw-semibold text-nowrap" style="border:1px solid #dce4f0;<?= $isBreak ? 'color:#505a7a;font-style:italic;' : 'color:#374151;' ?>">
                    <div><?= esc($slot['label']) ?></div>
                    <?php if ($slot['start_time'] && !$isBreak): ?>
                    <div class="text-muted" style="font-size:0.72rem;"><?= substr($slot['start_time'],0,5) ?>–<?= substr($slot['end_time'],0,5) ?></div>
                    <?php endif; ?>
                </td>
                <?php foreach ($days as $day): ?>
                <?php $cell = $entryMap[$day][$slot['slot_id']] ?? null; ?>
                <td class="py-2" style="border:1px solid #dce4f0;<?= $isBreak ? 'color:#505a7a;font-style:italic;' : '' ?>">
                    <?php if ($isBreak): ?>
                        <em style="font-size:0.75rem;"><?= esc($slot['label']) ?></em>
                    <?php elseif ($cell && !empty($cell['is_optional'])): ?>
                        <?php foreach ($cell['entries'] as $e): ?>
                        <div style="border-bottom:1px dashed #cbd5e1;padding:2px 0;margin-bottom:2px;">
                            <div class="fw-bold" style="color:#1a37c0;font-size:0.78rem;">
                                <span class="subcat-full"><?= esc($e['sub_cat_name'] ?? '') ?></span>
                                <span class="subcat-initial"><?= esc($e['sub_cat_initial'] ?? '') ?></span>
                            </div>
                            <?php $tch = trim(($e['fname'] ?? '') . ' ' . ($e['lname'] ?? '')); ?>
                            <?php if ($tch): ?><div class="text-muted" style="font-size:0.72rem;"><?= esc($tch) ?></div><?php endif; ?>
                            <?php if (!empty($e['room'])): ?><div class="text-muted tt-room-badge" style="font-size:0.68rem;">[<?= esc($e['room']) ?>]</div><?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php elseif ($cell && ($cell['sch_sub_id_fk'] || $cell['teacher_id_fk'])): ?>
                        <div class="fw-semibold text-gray-900" style="font-size:0.8rem;">
                            <span class="subcat-full"><?= esc($cell['sub_cat_name'] ?? '') ?></span>
                            <span class="subcat-initial"><?= esc($cell['sub_cat_initial'] ?? '') ?></span>
                        </div>
                        <div class="text-muted" style="font-size:0.72rem;"><?= esc(trim(($cell['fname'] ?? '') . ' ' . ($cell['lname'] ?? ''))) ?></div>
                        <?php if (!empty($cell['room'])): ?><div class="text-muted tt-room-badge" style="font-size:0.68rem;">[<?= esc($cell['room']) ?>]</div><?php endif; ?>
                    <?php else: ?>
                        <span style="color:#c8ccd8;">—</span>
                    <?php endif; ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <!--end::Grid-->

        <div class="text-muted fs-9 text-end mt-5">
            <?= esc($tt['template_name'] ?? '') ?> &nbsp;·&nbsp; Generated: <?= date('d M Y, H:i') ?>
            &nbsp;·&nbsp; Navuli Fiji School Management System
        </div>
    </div>
</div>

</div>
</div>

<style>
#tt_grid .tt-room-badge { display: none; }
#tt_grid.tt-show-room .tt-room-badge { display: block; }
#tt_grid .subcat-initial { display: none; }
#tt_grid.tt-cat-initial .subcat-full { display: none; }
#tt_grid.tt-cat-initial .subcat-initial { display: inline; }

@media print {
    #kt_app_toolbar, .app-header, #kt_app_sidebar, .app-footer,
    .btn, [data-kt-scroll], #toolbar-no-print, .toolbar-no-print { display: none !important; }
    body, .app-content { padding: 0 !important; margin: 0 !important; }
    #print-area { box-shadow: none !important; border: none !important; }
    .card-body { padding: 0.5rem !important; }
    @page { size: A4 landscape; margin: 8mm; }
    .table-bordered td, .table-bordered th { border: 1px solid #ccc !important; }
    .table-responsive { overflow: visible !important; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var grid = document.getElementById('tt_grid');
    var roomToggle = document.getElementById('tt_toggle_room');
    var catToggle  = document.getElementById('tt_toggle_cat_initial');
    var pdfLink    = document.getElementById('tt_pdf_link');

    function updatePdfLink() {
        if (!pdfLink) return;
        var params = [];
        if (roomToggle && roomToggle.checked) params.push('room=1');
        if (catToggle && catToggle.checked) params.push('initial=1');
        pdfLink.href = pdfLink.dataset.base + (params.length ? '?' + params.join('&') : '');
    }

    if (roomToggle) {
        roomToggle.addEventListener('change', function () {
            grid.classList.toggle('tt-show-room', this.checked);
            updatePdfLink();
        });
    }
    if (catToggle) {
        catToggle.addEventListener('change', function () {
            grid.classList.toggle('tt-cat-initial', this.checked);
            updatePdfLink();
        });
    }

    updatePdfLink();
});
</script>
