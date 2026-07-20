<?php
/** Expects $panel: one entry from AttendanceController::_buildChildAttendancePanels() */
$v  = $panel['view'];
$ss = $v['summaryStats'] ?? ['present' => 0, 'absent' => 0, 'unmarked' => 0, 'numMarked' => 0, 'pct' => 0];
$pctColor = $ss['pct'] >= 80 ? 'success' : ($ss['pct'] >= 70 ? 'warning' : 'danger');

$streamName = '';
if (!empty($v['streamInfo'])) {
    $streamName = $v['streamInfo']['stream_name'] ?? '';
    if (!empty($v['streamInfo']['level_name'])) $streamName .= ' (' . $v['streamInfo']['level_name'] . ')';
}
?>
<div class="d-flex align-items-center flex-wrap gap-3 mb-5">
    <div class="d-flex align-items-center justify-content-center bg-light-success rounded-2" style="width:44px;height:44px;">
        <i class="ki-duotone ki-calendar-tick fs-2 text-success">
            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            <span class="path4"></span><span class="path5"></span><span class="path6"></span>
        </i>
    </div>
    <div class="me-auto">
        <div class="fw-bold text-gray-900 fs-6"><?= esc($streamName) ?></div>
        <div class="text-muted fs-7"><?= esc($v['termLabel'] ?? 'Term') ?> <?= (int) ($v['termNo'] ?? 0) ?></div>
    </div>
    <a href="<?= base_url('attendance/my/daily?student_id=' . (int) $panel['user_id'] . '&stream_id=' . (int) $v['streamId'] . '&term=' . (int) $v['termNo']) ?>"
       class="btn btn-sm btn-light-primary" target="_blank">
        <i class="ki-duotone ki-eye fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        Full Report
    </a>
</div>

<div class="row g-3 mb-5">
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-light h-100">
            <div class="card-body p-4 text-center">
                <div class="fw-bold fs-3 text-gray-900"><?= $ss['numMarked'] ?></div>
                <div class="text-muted fs-8">Days Marked</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-light h-100">
            <div class="card-body p-4 text-center">
                <div class="fw-bold fs-3 text-success"><?= $ss['present'] ?></div>
                <div class="text-muted fs-8">Present</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-light h-100">
            <div class="card-body p-4 text-center">
                <div class="fw-bold fs-3 text-danger"><?= $ss['absent'] ?></div>
                <div class="text-muted fs-8">Absent</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-light h-100">
            <div class="card-body p-4 text-center">
                <div class="fw-bold fs-3 text-<?= $pctColor ?>"><?= $ss['pct'] ?>%</div>
                <div class="text-muted fs-8">Attendance Rate</div>
            </div>
        </div>
    </div>
</div>

<?php if (empty($v['weeks'])): ?>
<div class="text-center text-muted fs-7 py-8">Term dates not configured. Please ask your administrator.</div>
<?php else: ?>
<div class="my-att-wrapper">
    <table class="my-att-table">
        <thead>
            <tr>
                <th class="my-att-day-head"></th>
                <?php foreach ($v['weeks'] as $wi => $weekDays): ?>
                <th class="my-att-week-head">W<?= $wi + 1 ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($v['dayNames'] as $dk): ?>
            <tr>
                <td class="my-att-day-label"><?= $dk ?></td>
                <?php foreach ($v['weeks'] as $wi => $weekDays):
                    $date      = $weekDays[$dk] ?? '';
                    $isFuture  = $date > $v['today'];
                    $isHoliday = isset($v['holidays'][$date]);
                    $status    = ($isFuture || $isHoliday) ? null : ($v['attendance'][$date] ?? null);
                    $isPresent = $status === 'Present';
                    $isAbsent  = $status === 'Absent';
                ?>
                <td class="my-att-cell <?= $wi % 2 === 0 ? 'col-even' : 'col-odd' ?>">
                    <?php if ($isFuture): ?>
                        <span class="my-att-val future">&mdash;</span>
                    <?php elseif ($isHoliday): ?>
                        <span class="my-att-val holiday" title="<?= esc($v['holidays'][$date]) ?>">H</span>
                    <?php elseif ($isPresent): ?>
                        <span class="my-att-val present" title="Present &middot; <?= esc($date) ?>">&check;</span>
                    <?php elseif ($isAbsent): ?>
                        <span class="my-att-val absent" title="Absent &middot; <?= esc($date) ?>">&cross;</span>
                    <?php else: ?>
                        <span class="my-att-val unmarked" title="Not marked &middot; <?= esc($date) ?>">&mdash;</span>
                    <?php endif; ?>
                </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
