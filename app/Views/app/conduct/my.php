<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                My Conduct
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">My Conduct</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php
    if (!function_exists('conduct_my_table')):
    function conduct_my_table(array $incidents): string {
        if (empty($incidents)) {
            return '<p class="text-muted fs-7 py-4">No conduct incidents on record. Keep up the good work!</p>';
        }
        ob_start();
        ?>
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th class="min-w-150px">Type</th>
                        <th class="min-w-80px">Points</th>
                        <th class="min-w-100px">Severity</th>
                        <th class="min-w-120px">Date</th>
                        <th class="min-w-150px">Location</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-80px text-end"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($incidents as $row): ?>
                    <tr>
                        <td>
                            <span class="badge badge-light-<?= !empty($row['is_positive']) ? 'success' : 'danger' ?>">
                                <?= esc($row['type_name'] ?? '—') ?>
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold <?= (int) $row['points_awarded'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= (int) $row['points_awarded'] >= 0 ? '+' : '' ?><?= $row['points_awarded'] ?>
                            </span>
                        </td>
                        <td><span class="text-muted fs-7"><?= esc($row['severity_level'] ?? '—') ?></span></td>
                        <td><span class="text-muted fs-7"><?= esc(date('d M Y', strtotime($row['incident_date']))) ?></span></td>
                        <td><span class="text-muted fs-7"><?= esc($row['location'] ?: '—') ?></span></td>
                        <td>
                            <span class="badge badge-light-<?= !empty($row['is_resolved']) ? 'success' : 'warning' ?>">
                                <?= !empty($row['is_resolved']) ? 'Resolved' : 'Open' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="<?= base_url('conduct/my/detail/' . $row['incident_id']) ?>"
                               class="btn btn-sm btn-light-primary">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }
    endif;
    ?>

    <?php if ($isStudent): ?>

        <?php if (!$admission): ?>
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body py-10 text-center">
                <i class="ki-duotone ki-shield-tick fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span></i>
                <p class="text-muted fs-6">You are not currently enrolled. Please contact your school administrator.</p>
            </div>
        </div>
        <?php else: ?>
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body py-5">
                <?= conduct_my_table($incidents) ?>
            </div>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Parent view — one section per child -->

        <?php if (empty($children)): ?>
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body py-10 text-center">
                <i class="ki-duotone ki-people fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                <p class="text-muted fs-6">No linked children found. Please contact the school to link your children to your account.</p>
            </div>
        </div>
        <?php else: ?>
            <?php foreach ($children as $cd): ?>
            <?php $child = $cd['child']; ?>
            <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
                <div class="card-header border-0 pt-6">
                    <div class="card-title d-flex align-items-center gap-3">
                        <div class="symbol symbol-40px">
                            <?php if (!empty($child['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profilePhoto/' . $child['profile_photo']) ?>" class="rounded-circle" alt="photo">
                            <?php else: ?>
                            <div class="symbol-label fs-6 fw-bold bg-light-primary text-primary">
                                <?= strtoupper(substr($child['fname'], 0, 1) . substr($child['lname'], 0, 1)) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 class="fw-bold fs-5 mb-0 text-gray-900">
                                <?= esc($child['fname'] . ' ' . $child['lname']) ?>
                            </h3>
                            <?php if (!empty($child['relationship'])): ?>
                            <span class="text-muted fs-8"><?= esc(ucfirst($child['relationship'])) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <?php if (!$cd['admission']): ?>
                        <p class="text-muted fs-7 py-4">Not currently enrolled.</p>
                    <?php else: ?>
                        <?= conduct_my_table($cd['incidents']) ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

</div>
</div>
