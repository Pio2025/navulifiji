<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($tt['stream_name'] ?? 'Timetable') ?> — <?= esc($tt['academic_year']) ?> Term <?= esc($tt['term']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('timetable') ?>" class="text-muted text-hover-primary">Timetable</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">View</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('timetable/edit/' . $tt['timetable_id']) ?>" class="btn btn-warning btn-sm">
                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i> Edit
            </a>
            <?php endif; ?>
            <a href="<?= base_url('timetable/report/' . $tt['timetable_id']) ?>" class="btn btn-light-info btn-sm">
                <i class="ki-duotone ki-printer fs-2"><span class="path1"></span><span class="path2"></span></i> Print
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

<!--begin::This-week rotation banner-->
<?php if (!empty($weekMap)): ?>
<div class="card mb-5 border border-dashed border-primary">
    <div class="card-body py-4 px-6">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <i class="ki-duotone ki-calendar-8 fs-2x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                <div>
                    <div class="fw-bold text-gray-800 fs-6">This Week's Rotation</div>
                    <div class="text-muted fs-8">Based on Day <?= (int)$tt['rotation_start_day'] ?> anchor on <?= date('d M Y', strtotime($tt['rotation_start_date'])) ?></div>
                </div>
            </div>
            <div class="d-flex gap-4 flex-wrap">
                <?php foreach ($weekMap as $dayName => $info): ?>
                <?php $todayLabel = date('D') === $dayName; ?>
                <div class="text-center <?= $todayLabel ? 'bg-light-primary rounded px-3 py-2' : '' ?>">
                    <div class="fw-bold text-gray-600 fs-8"><?= $dayName ?></div>
                    <div class="text-muted fs-9 mb-1"><?= $info['date'] ?></div>
                    <?php if ($info['day_number']): ?>
                    <span class="badge badge-<?= $todayLabel ? 'primary' : 'light-primary' ?> fs-8">Day <?= $info['day_number'] ?></span>
                    <?php else: ?>
                    <span class="text-gray-300 fs-9">—</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!--end::This-week rotation banner-->

<!--begin::Timetable grid-->
<div class="card">
    <div class="card-header">
        <h3 class="card-title d-flex align-items-center gap-3">
            <?= esc($tt['stream_name'] ?? '') ?>
            <?php
            $badge = match($tt['timetable_status']) {
                'Active'   => 'success',
                'Draft'    => 'warning',
                'Archived' => 'secondary',
                default    => 'light',
            };
            ?>
            <span class="badge badge-light-<?= $badge ?> fs-7"><?= esc($tt['timetable_status']) ?></span>
        </h3>
        <div class="card-toolbar gap-3">
            <span class="text-muted fs-7"><?= esc($tt['sch_cat_name'] ?? '') ?> · <?= esc($tt['level_name'] ?? '') ?> · <?= esc($tt['template_name'] ?? '') ?></span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-bordered mb-0 align-middle" style="min-width:900px;">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-center text-muted fw-semibold fs-8 text-uppercase" style="min-width:110px;">Time</th>
                    <?php foreach ($days as $day): ?>
                    <?php
                    // Highlight today's day number if rotation is set
                    $isToday = false;
                    if (!empty($weekMap)) {
                        $todayName = date('D');
                        if (isset($weekMap[$todayName]) && $weekMap[$todayName]['day_number'] == $day) {
                            $isToday = true;
                        }
                    }
                    ?>
                    <th class="py-3 text-center <?= $isToday ? 'bg-light-primary' : '' ?>" style="min-width:150px;">
                        <span class="badge badge-<?= $isToday ? 'primary' : 'light-primary' ?> px-3 py-2 fs-7">Day <?= $day ?></span>
                        <?php if ($isToday): ?><div class="text-primary fs-9 fw-semibold mt-1">Today</div><?php endif; ?>
                    </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($slots as $slot): ?>
            <?php $isBreak = !(int)$slot['is_teaching']; ?>
            <tr class="<?= $isBreak ? 'bg-light-secondary' : '' ?>">
                <td class="ps-4 py-3 text-center border-end">
                    <div class="<?= $isBreak ? 'badge badge-secondary' : 'fw-semibold text-gray-700 fs-8' ?> mb-1">
                        <?= esc($slot['label']) ?>
                    </div>
                    <?php if ($slot['start_time'] && $slot['end_time']): ?>
                    <div class="text-muted fs-9"><?= substr($slot['start_time'],0,5) ?>–<?= substr($slot['end_time'],0,5) ?></div>
                    <?php endif; ?>
                </td>
                <?php foreach ($days as $day): ?>
                <?php
                $isToday = false;
                if (!empty($weekMap)) {
                    $todayName = date('D');
                    if (isset($weekMap[$todayName]) && $weekMap[$todayName]['day_number'] == $day) $isToday = true;
                }
                $cell = $entryMap[$day][$slot['slot_id']] ?? null;
                ?>
                <td class="py-2 px-3 text-center <?= $isBreak ? 'text-muted' : '' ?> <?= $isToday ? 'bg-light-primary' : '' ?>">
                    <?php if ($isBreak): ?>
                        <span class="fs-8 text-gray-400">— <?= esc($slot['label']) ?> —</span>
                    <?php elseif ($cell && ($cell['sch_sub_id_fk'] || $cell['teacher_id_fk'])): ?>
                        <div class="fw-bold text-gray-800 fs-8 mb-1"><?= esc($cell['subject_name'] ?? '—') ?></div>
                        <div class="text-muted fs-9"><?= esc(($cell['fname'] ?? '') . ' ' . ($cell['lname'] ?? '')) ?></div>
                        <?php if (!empty($cell['room'])): ?>
                        <span class="badge badge-light fs-9 mt-1"><?= esc($cell['room']) ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-gray-300 fs-8">—</span>
                    <?php endif; ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<!--end::Timetable grid-->

</div>
</div>
