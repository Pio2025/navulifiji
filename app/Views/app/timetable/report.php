<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading fw-bold fs-3 my-0">Timetable Report</h1>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="ki-duotone ki-printer fs-2"><span class="path1"></span><span class="path2"></span></i> Print
            </button>
            <a href="<?= base_url('timetable/detail/' . $tt['timetable_id']) ?>" class="btn btn-light btn-sm">Back</a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<div class="card" id="print-area">
    <div class="card-body">
        <!--begin::Header-->
        <div class="text-center mb-8">
            <h2 class="fw-bold text-gray-900 mb-1"><?= esc($tt['sch_name'] ?? '') ?></h2>
            <h4 class="text-gray-700 mb-1">Class Timetable — <?= esc($tt['stream_name'] ?? '') ?></h4>
            <div class="text-muted fs-7">
                <?= esc($tt['level_name'] ?? '') ?> · Academic Year <?= esc($tt['academic_year']) ?> · Term <?= esc($tt['term']) ?>
                · <?= esc($tt['sch_cat_name'] ?? '') ?>
            </div>
            <div class="separator my-4"></div>
        </div>
        <!--end::Header-->

        <!--begin::6-day rotation explanation-->
        <div class="d-flex justify-content-center gap-4 mb-6 flex-wrap">
            <?php foreach ($days as $d): ?>
            <div class="text-center px-4 py-2 rounded border">
                <div class="fw-bold text-gray-800 fs-7">Day <?= $d ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="text-center text-muted fs-8 mb-6">
            This timetable follows a <strong>6-day rotation</strong> cycle (Day 1–6) running Monday to Friday.
            <?php if (!empty($tt['rotation_start_date'])): ?>
            Day <?= (int)$tt['rotation_start_day'] ?> began on <?= date('l, d F Y', strtotime($tt['rotation_start_date'])) ?>.
            <?php endif; ?>
        </p>

        <!--begin::Grid-->
        <div class="table-responsive">
        <table class="table table-bordered align-middle text-center" style="font-size:0.8rem;">
            <thead class="bg-light">
                <tr>
                    <th class="fw-bold py-3" style="min-width:100px;">Time</th>
                    <?php foreach ($days as $day): ?>
                    <th class="fw-bold py-3" style="min-width:130px;">Day <?= $day ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($slots as $slot): ?>
            <?php $isBreak = !(int)$slot['is_teaching']; ?>
            <tr class="<?= $isBreak ? 'table-light' : '' ?>">
                <td class="py-2 fw-semibold text-nowrap">
                    <div><?= esc($slot['label']) ?></div>
                    <?php if ($slot['start_time']): ?>
                    <div class="text-muted" style="font-size:0.75rem;"><?= substr($slot['start_time'],0,5) ?>–<?= substr($slot['end_time'],0,5) ?></div>
                    <?php endif; ?>
                </td>
                <?php foreach ($days as $day): ?>
                <?php $cell = $entryMap[$day][$slot['slot_id']] ?? null; ?>
                <td class="py-2 <?= $isBreak ? 'text-muted' : '' ?>">
                    <?php if ($isBreak): ?>
                        <em><?= esc($slot['label']) ?></em>
                    <?php elseif ($cell && $cell['sch_sub_id_fk']): ?>
                        <div class="fw-semibold text-gray-900"><?= esc($cell['subject_name'] ?? '') ?></div>
                        <div class="text-muted"><?= esc(trim(($cell['fname'] ?? '') . ' ' . ($cell['lname'] ?? ''))) ?></div>
                        <?php if (!empty($cell['room'])): ?><div class="text-muted" style="font-size:0.72rem;"><?= esc($cell['room']) ?></div><?php endif; ?>
                    <?php else: ?>
                        <span class="text-gray-300">—</span>
                    <?php endif; ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <!--end::Grid-->

        <div class="text-muted fs-9 text-end mt-6">
            Generated: <?= date('d M Y, H:i') ?>
        </div>
    </div>
</div>

</div>
</div>

<style>
@media print {
    #kt_app_toolbar, .app-header, #kt_app_sidebar, .app-footer,
    .btn, [data-kt-scroll] { display: none !important; }
    body, .app-content { padding: 0 !important; margin: 0 !important; }
    #print-area { box-shadow: none !important; border: none !important; }
    .table-bordered td, .table-bordered th { border: 1px solid #ccc !important; }
}
</style>
