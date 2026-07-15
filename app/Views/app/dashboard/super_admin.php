<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                System Dashboard
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Overview</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <span class="text-muted fs-7 fw-semibold">
                <i class="ki-duotone ki-calendar fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
                <?= date('l, d F Y') ?>
            </span>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Welcome banner-->
    <div class="card bgi-no-repeat bgi-size-cover mb-5 mb-xl-10"
         style="background-color:#1a1a2e; background-image:url('<?= base_url('assets/media/misc/bg-2.jpg') ?>')">
        <div class="card-body d-flex align-items-center py-8 px-10">
            <div>
                <h2 class="text-white fw-bold fs-1 mb-1">
                    Welcome back, <?= esc($fname ?? $name ?? 'Admin') ?>
                </h2>
                <p class="text-white opacity-75 fs-6 mb-0">
                    Here is your platform-wide overview for today.
                </p>
            </div>
            <div class="ms-auto d-none d-md-block">
                <i class="ki-duotone ki-element-11 text-white opacity-25" style="font-size:6rem">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span>
                </i>
            </div>
        </div>
    </div>
    <!--end::Welcome banner-->

    <!--begin::KPI row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">

        <!--Total Schools-->
        <div class="col-sm-6 col-xl-2">
            <div class="card h-lg-100">
                <div class="card-body d-flex flex-column justify-content-between pb-1 px-0">
                    <div class="d-flex flex-column px-9 pt-7 pb-0">
                        <span class="fw-semibold text-muted fs-7 mb-4">Total Schools</span>
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                <?= $sa_total_schools ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center pb-7">
                            <span class="badge badge-light-success fs-base fw-bold me-1">
                                <?= $sa_active_schools ?> Active
                            </span>
                            <span class="badge badge-light-danger fs-base fw-bold">
                                <?= $sa_total_schools - $sa_active_schools ?> Other
                            </span>
                        </div>
                    </div>
                    <div id="chart_schools" style="height:80px"></div>
                </div>
            </div>
        </div>

        <!--Total Users-->
        <div class="col-sm-6 col-xl-2">
            <div class="card h-lg-100">
                <div class="card-body d-flex flex-column justify-content-between pb-1 px-0">
                    <div class="d-flex flex-column px-9 pt-7 pb-0">
                        <span class="fw-semibold text-muted fs-7 mb-4">Registered Users</span>
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                <?= number_format($sa_total_users) ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center pb-7">
                            <span class="badge badge-light-primary fs-base fw-bold">
                                +<?= $sa_new_users_month ?> this month
                            </span>
                        </div>
                    </div>
                    <div id="chart_users" style="height:80px"></div>
                </div>
            </div>
        </div>

        <!--Students-->
        <div class="col-sm-6 col-xl-2">
            <div class="card h-lg-100">
                <div class="card-body d-flex flex-column justify-content-between pb-1 px-0">
                    <div class="d-flex flex-column px-9 pt-7 pb-0">
                        <span class="fw-semibold text-muted fs-7 mb-4">Enrolled Students</span>
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                <?= number_format($sa_total_students) ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center pb-7">
                            <span class="badge badge-light-info fs-base fw-bold">Active enrolments</span>
                        </div>
                    </div>
                    <div id="chart_students" style="height:80px"></div>
                </div>
            </div>
        </div>

        <!--Teachers-->
        <div class="col-sm-6 col-xl-2">
            <div class="card h-lg-100">
                <div class="card-body d-flex flex-column justify-content-between pb-1 px-0">
                    <div class="d-flex flex-column px-9 pt-7 pb-0">
                        <span class="fw-semibold text-muted fs-7 mb-4">Active Teachers</span>
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                <?= number_format($sa_total_teachers) ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center pb-7">
                            <span class="badge badge-light-warning fs-base fw-bold">Role: Teacher</span>
                        </div>
                    </div>
                    <div id="chart_teachers" style="height:80px"></div>
                </div>
            </div>
        </div>

        <!--Users by role chart card-->
        <div class="col-xl-4">
            <div class="card h-lg-100">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Users by Role</span>
                        <span class="text-muted fw-semibold fs-7 mt-1">Active role assignments</span>
                    </h3>
                </div>
                <div class="card-body pt-1">
                    <div id="chart_roles" style="height:160px"></div>
                </div>
            </div>
        </div>

    </div>
    <!--end::KPI row-->

    <!--begin::Main content row-->
    <div class="row g-5 g-xl-10">

        <!--begin::Schools table-->
        <div class="col-xl-7">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Registered Schools</span>
                        <span class="text-muted fw-semibold fs-7 mt-1"><?= count($sa_schools_list) ?> schools on the platform</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="<?= base_url('school') ?>" class="btn btn-sm btn-light-primary fw-bold">
                            <i class="ki-duotone ki-eye fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                            <thead>
                                <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                    <th class="p-0 pb-3 min-w-175px text-start">SCHOOL</th>
                                    <th class="p-0 pb-3 min-w-60px text-center">STUDENTS</th>
                                    <th class="p-0 pb-3 min-w-100px text-end pe-3">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($sa_schools_list)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-5">No schools found.</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($sa_schools_list as $sch): ?>
                                <?php
                                    $statusClass = match(true) {
                                        $sch['sch_status'] === 'Active'            => 'badge-light-success',
                                        str_starts_with($sch['sch_status'], 'Step') => 'badge-light-warning',
                                        default                                    => 'badge-light-secondary',
                                    };
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40px me-3">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-duotone ki-teacher text-primary fs-2">
                                                        <span class="path1"></span><span class="path2"></span>
                                                    </i>
                                                </span>
                                            </div>
                                            <div>
                                                <a href="<?= base_url('school/detail/' . $sch['sch_id']) ?>"
                                                   class="text-gray-800 text-hover-primary fw-bold fs-6">
                                                    <?= esc($sch['sch_name']) ?>
                                                </a>
                                                <span class="text-muted fw-semibold d-block fs-7">
                                                    Added <?= date('d M Y', strtotime($sch['sch_created_at'] ?? 'now')) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-gray-800"><?= (int)$sch['student_count'] ?></span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="badge <?= $statusClass ?> fw-bold">
                                            <?= esc($sch['sch_status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Schools table-->

        <!--begin::Recent activity-->
        <div class="col-xl-5">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Recent Activity</span>
                        <span class="text-muted fw-semibold fs-7 mt-1">Latest platform actions</span>
                    </h3>
                </div>
                <div class="card-body pt-4 pb-5">
                    <?php if (empty($sa_recent_activity)): ?>
                    <div class="text-center text-muted py-8">No recent activity.</div>
                    <?php else: ?>
                    <div class="timeline-label">
                        <?php foreach ($sa_recent_activity as $i => $log):
                            $dotColors = ['bg-success','bg-primary','bg-warning','bg-danger','bg-info','bg-secondary'];
                            $dot = $dotColors[$i % count($dotColors)];
                        ?>
                        <div class="timeline-item align-items-start mb-5">
                            <div class="timeline-label fw-bold text-gray-800 fs-7 mt-1">
                                <?= date('H:i', strtotime($log['created_at'])) ?>
                            </div>
                            <div class="timeline-badge">
                                <i class="fa fa-genderless <?= $dot ?> fs-1"></i>
                            </div>
                            <div class="timeline-content fw-semibold text-gray-800 ps-3">
                                <span class="text-primary fw-bold">
                                    <?= esc(trim(($log['fname'] ?? '') . ' ' . ($log['lname'] ?? '')) ?: 'System') ?>
                                </span>
                                <span class="text-muted fw-normal ms-1">
                                    <?= esc($log['log_description'] ?? $log['log_action'] ?? '') ?>
                                </span>
                                <span class="text-muted fw-normal fs-8 d-block">
                                    <?= date('d M Y', strtotime($log['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <!--end::Recent activity-->

    </div>
    <!--end::Main content row-->

</div>
</div>
<!--end::Content-->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.dataset.theme === 'dark'
        || (!document.documentElement.dataset.theme && window.matchMedia('(prefers-color-scheme:dark)').matches);
    const textColor   = isDark ? '#ffffff' : '#181c32';
    const mutedColor  = isDark ? '#7e8299' : '#a1a5b7';
    const gridColor   = isDark ? '#2b2b40' : '#eff2f5';

    // Mini sparkline helper
    function miniSparkline(el, data, color) {
        if (!el || typeof ApexCharts === 'undefined') return;
        new ApexCharts(el, {
            series: [{ data }],
            chart: { type: 'area', height: 80, sparkline: { enabled: true }, toolbar: { show: false } },
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0, stops: [0, 100] } },
            colors: [color],
            tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } },
        }).render();
    }

    miniSparkline(document.getElementById('chart_schools'),  [1,2,2,3,3,4,<?= $sa_total_schools ?>], '#50cd89');
    miniSparkline(document.getElementById('chart_users'),    [10,20,28,35,40,48,<?= $sa_total_users ?>], '#009ef7');
    miniSparkline(document.getElementById('chart_students'), [0,1,1,2,2,3,<?= $sa_total_students ?>], '#7239ea');
    miniSparkline(document.getElementById('chart_teachers'), [2,4,5,6,7,7,<?= $sa_total_teachers ?>], '#f6c000');

    // Users by role donut chart
    const roleEl = document.getElementById('chart_roles');
    if (roleEl && typeof ApexCharts !== 'undefined') {
        const roleLabels = <?= json_encode(array_column($sa_users_by_role, 'role_cat_name')) ?>;
        const roleData   = <?= json_encode(array_map(fn($r) => (int)$r['cnt'], $sa_users_by_role)) ?>;
        new ApexCharts(roleEl, {
            series: roleData,
            labels: roleLabels,
            chart: { type: 'donut', height: 160, toolbar: { show: false } },
            legend: {
                show: true, position: 'right', fontSize: '12px',
                labels: { colors: mutedColor },
            },
            dataLabels: { enabled: false },
            colors: ['#009ef7','#50cd89','#f6c000','#7239ea','#f1416c','#181c32','#e4e6ef'],
            plotOptions: { pie: { donut: { size: '65%' } } },
            stroke: { show: false },
            tooltip: { theme: isDark ? 'dark' : 'light' },
        }).render();
    }
});
</script>
