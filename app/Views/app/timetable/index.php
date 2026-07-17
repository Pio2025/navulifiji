<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Timetable Management</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Timetable</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($canAdd): ?>
            <a href="<?= base_url('timetable/add') ?>" class="btn btn-primary btn-sm">
                <i class="ki-duotone ki-plus fs-2"></i> Add Timetable
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php
// Group timetables by stream for cleaner display
$grouped = [];
foreach ($timetables as $tt) {
    $key = $tt['stream_name'] ?? 'Unknown';
    $grouped[$key][] = $tt;
}
?>

<?php if (empty($timetables)): ?>
<div class="card">
    <div class="card-body py-20 text-center">
        <i class="ki-duotone ki-calendar-8 fs-5x text-gray-300 mb-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
        <p class="text-gray-500 fs-5 mb-4">No timetables found.</p>
        <?php if ($canAdd): ?>
        <a href="<?= base_url('timetable/add') ?>" class="btn btn-primary">Create First Timetable</a>
        <?php endif; ?>
    </div>
</div>
<?php else: ?>

<!--begin::Stats row-->
<div class="row g-5 mb-6">
    <?php
    $total    = count($timetables);
    $active   = count(array_filter($timetables, fn($t) => $t['timetable_status'] === 'Active'));
    $draft    = count(array_filter($timetables, fn($t) => $t['timetable_status'] === 'Draft'));
    $archived = count(array_filter($timetables, fn($t) => $t['timetable_status'] === 'Archived'));
    $stats = [
        ['label' => 'Total Timetables', 'value' => $total,    'color' => 'primary',  'icon' => 'ki-calendar-8'],
        ['label' => 'Active',           'value' => $active,   'color' => 'success',  'icon' => 'ki-check-circle'],
        ['label' => 'Draft',            'value' => $draft,    'color' => 'warning',  'icon' => 'ki-pencil'],
        ['label' => 'Archived',         'value' => $archived, 'color' => 'secondary','icon' => 'ki-archive'],
    ];
    foreach ($stats as $s):
    ?>
    <div class="col-6 col-lg-3">
        <div class="card card-flush h-100">
            <div class="card-body py-5 d-flex align-items-center gap-4">
                <div class="bg-light-<?= $s['color'] ?> rounded p-3 flex-shrink-0">
                    <i class="ki-duotone <?= $s['icon'] ?> fs-2x text-<?= $s['color'] ?>">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= $s['value'] ?></div>
                    <div class="text-muted fs-7"><?= $s['label'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<!--end::Stats row-->

<!--begin::Timetables table-->
<div class="card">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800">All Timetables</span>
        </h3>
        <div class="card-toolbar">
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="tt-search" class="form-control form-control-sm w-200px" placeholder="Search stream...">
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="tt-table">
            <thead>
                <tr class="fw-bold text-muted bg-light">
                    <th class="ps-4 rounded-start">Stream</th>
                    <?php if ($isSuperAdmin): ?><th>School</th><?php endif; ?>
                    <th>Level</th>
                    <th>Year</th>
                    <th>Term</th>
                    <th>Template</th>
                    <th>Rotation Start</th>
                    <th class="text-center">Status</th>
                    <th class="pe-4 rounded-end text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($timetables as $tt): ?>
            <tr>
                <td class="ps-4 fw-semibold text-gray-800"><?= esc($tt['stream_name'] ?? '—') ?></td>
                <?php if ($isSuperAdmin): ?>
                <td class="text-muted"><?= esc($tt['sch_name'] ?? '—') ?></td>
                <?php endif; ?>
                <td class="text-muted"><?= esc($tt['level_name'] ?? '—') ?></td>
                <td><?= esc($tt['academic_year']) ?></td>
                <td>Term <?= esc($tt['term']) ?></td>
                <td class="text-muted fs-7"><?= esc($tt['template_name'] ?? '—') ?></td>
                <td class="text-muted fs-7">
                    <?php if ($tt['rotation_start_date']): ?>
                        Day <?= (int)$tt['rotation_start_day'] ?> — <?= date('d M Y', strtotime($tt['rotation_start_date'])) ?>
                    <?php else: ?>
                        <span class="text-gray-400">Not set</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php
                    $badge = match($tt['timetable_status']) {
                        'Active'   => 'success',
                        'Draft'    => 'warning',
                        'Archived' => 'secondary',
                        default    => 'light',
                    };
                    ?>
                    <span class="badge badge-light-<?= $badge ?>"><?= esc($tt['timetable_status']) ?></span>
                </td>
                <td class="pe-4 text-end">
                    <a href="<?= base_url('timetable/detail/' . $tt['timetable_id']) ?>" class="btn btn-icon btn-light-primary btn-sm me-1" title="View">
                        <i class="ki-duotone ki-eye fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </a>
                    <?php if ($canEdit): ?>
                    <a href="<?= base_url('timetable/edit/' . $tt['timetable_id']) ?>" class="btn btn-icon btn-light-warning btn-sm me-1" title="Edit">
                        <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    <?php endif; ?>
                    <a href="<?= base_url('timetable/report/' . $tt['timetable_id']) ?>" class="btn btn-icon btn-light-info btn-sm me-1" title="Print">
                        <i class="ki-duotone ki-printer fs-4"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    <?php if ($canDelete): ?>
                    <form method="POST" action="<?= base_url('timetable/remove/' . $tt['timetable_id']) ?>" class="d-inline" onsubmit="return confirm('Delete this timetable and all its entries?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-icon btn-light-danger btn-sm" title="Delete">
                            <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!--end::Timetables table-->

<?php endif; ?>

</div>
</div>

<script>
document.getElementById('tt-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tt-table tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
