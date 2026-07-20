    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Welcome banner-->
    <div class="card bgi-no-repeat bgi-size-cover mb-5 mb-xl-10"
         style="background-color:#1b3a6b; background-image:url('<?= base_url('assets/media/misc/bg-3.jpg') ?>')">
        <div class="card-body d-flex align-items-center py-8 px-10">
            <div>
                <h2 class="text-white fw-bold fs-1 mb-1">
                    Good <?= (date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening')) ?>,
                    <?= esc($fname ?? $name ?? 'Admin') ?>
                </h2>
                <p class="text-white opacity-75 fs-6 mb-0">
                    Here is your school overview for <?= date('l, d F Y') ?>.
                </p>
            </div>
            <div class="ms-auto d-none d-md-block">
                <i class="ki-duotone ki-teacher text-white opacity-15" style="font-size:7rem">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </div>
        </div>
    </div>
    <!--end::Welcome banner-->

    <!--begin::KPI cards-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">

        <!--Students-->
        <div class="col-sm-6 col-xl-4">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                 style="background-color:#f1f8ff">
                <div class="card-header pt-5 mb-3">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                            <?= number_format($ad_total_students) ?>
                        </span>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Students</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pb-0">
                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-7 text-gray-500 w-100 mt-auto mb-2">
                            <span>Active enrolments</span>
                            <span class="text-primary"><?= $ad_total_students ?></span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-light-primary rounded">
                            <div class="bg-primary rounded h-8px" style="width:<?= min(100, $ad_total_students > 0 ? 100 : 0) ?>%"></div>
                        </div>
                    </div>
                </div>
                <div id="chart_ad_students" class="mt-2" style="height:70px"></div>
            </div>
        </div>

        <!--Teachers-->
        <div class="col-sm-6 col-xl-4">
            <div class="card card-flush h-xl-100" style="background-color:#f6fff8">
                <div class="card-header pt-5 mb-3">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                            <?= number_format($ad_total_teachers) ?>
                        </span>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Active Teachers</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pb-0">
                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-7 text-gray-500 w-100 mt-auto mb-2">
                            <span>Assigned to classrooms</span>
                            <span class="text-success"><?= $ad_total_teachers ?></span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-light-success rounded">
                            <div class="bg-success rounded h-8px" style="width:<?= $ad_total_teachers > 0 ? 100 : 0 ?>%"></div>
                        </div>
                    </div>
                </div>
                <div id="chart_ad_teachers" class="mt-2" style="height:70px"></div>
            </div>
        </div>

        <!--Classrooms & Attendance-->
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                            <?= $ad_total_classrooms ?>
                        </span>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Active Classrooms</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                    <div class="d-flex flex-center me-5 pt-2">
                        <div id="chart_ad_attendance" style="width:100px;height:100px"></div>
                    </div>
                    <div class="d-flex flex-column content-justify-center flex-row-fluid">
                        <div class="d-flex fw-semibold align-items-center mb-3">
                            <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                            <div class="text-gray-500 flex-grow-1 me-4">Attendance (30d)</div>
                            <div class="fw-bolder text-gray-700 text-xxl-end"><?= $ad_attendance_pct ?>%</div>
                        </div>
                        <div class="d-flex fw-semibold align-items-center mb-3">
                            <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                            <div class="text-gray-500 flex-grow-1 me-4">Announcements</div>
                            <div class="fw-bolder text-gray-700"><?= $ad_active_announcements ?></div>
                        </div>
                        <div class="d-flex fw-semibold align-items-center">
                            <div class="bullet w-8px h-3px rounded-2 bg-warning me-3"></div>
                            <div class="text-gray-500 flex-grow-1 me-4">Active Notices</div>
                            <div class="fw-bolder text-gray-700"><?= $ad_active_notices ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end::KPI cards-->

    <!--begin::Lower row-->
    <div class="row g-5 g-xl-10">

        <!--begin::Classrooms table-->
        <div class="col-xl-5">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Classrooms</span>
                        <span class="text-muted fw-semibold fs-7 mt-1"><?= count($ad_classrooms_list) ?> classes registered</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="<?= base_url('classroom') ?>" class="btn btn-sm btn-light-primary fw-bold">View All</a>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <?php if (empty($ad_classrooms_list)): ?>
                    <div class="text-center text-muted py-8">No classrooms found.</div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle gy-3">
                            <thead>
                                <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                    <th>CLASS</th>
                                    <th class="text-center">YEAR</th>
                                    <th class="text-center">STUDENTS</th>
                                    <th class="text-end">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ad_classrooms_list as $cls): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-gray-800 fs-6"><?= esc($cls['class_name']) ?></span>
                                    </td>
                                    <td class="text-center text-muted fw-semibold"><?= $cls['class_year'] ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-light-primary fw-bold"><?= (int)$cls['student_count'] ?></span>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge <?= $cls['class_status'] === 'Active' ? 'badge-light-success' : 'badge-light-secondary' ?> fw-bold">
                                            <?= esc($cls['class_status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <!--end::Classrooms table-->

        <!--begin::Right column-->
        <div class="col-xl-7">
            <div class="row g-5 g-xl-10">

                <!--begin::Term report status-->
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-header pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-gray-900">Term Report Status</span>
                                <span class="text-muted fw-semibold fs-7 mt-1">Per classroom per term</span>
                            </h3>
                        </div>
                        <div class="card-body pt-4">
                            <?php if (empty($ad_term_reports)): ?>
                            <div class="text-center text-muted py-5">No term reports configured yet.</div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gy-2">
                                    <thead>
                                        <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                            <th>CLASS</th>
                                            <th class="text-center">TERM 1</th>
                                            <th class="text-center">TERM 2</th>
                                            <th class="text-center">TERM 3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Group by class
                                        $grouped = [];
                                        foreach ($ad_term_reports as $r) {
                                            $grouped[$r['class_name']][$r['term']] = $r['status'];
                                        }
                                        foreach ($grouped as $className => $terms):
                                            $badgeFor = fn($s) => match($s ?? '') {
                                                'published'  => ['badge-light-success', 'Published'],
                                                'collecting' => ['badge-light-warning', 'Collecting'],
                                                default      => ['badge-light-secondary', ucfirst($s ?? '—')],
                                            };
                                        ?>
                                        <tr>
                                            <td class="fw-bold text-gray-800"><?= esc($className) ?></td>
                                            <?php for ($t = 1; $t <= 3; $t++):
                                                [$cls2, $lbl] = $badgeFor($terms[$t] ?? null);
                                            ?>
                                            <td class="text-center">
                                                <span class="badge <?= $cls2 ?> fw-semibold"><?= $lbl ?></span>
                                            </td>
                                            <?php endfor ?>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <!--end::Term report status-->

                <!--begin::Quick stats row-->
                <div class="col-sm-6">
                    <div class="card card-flush bg-light-danger">
                        <div class="card-body d-flex align-items-center py-5 px-6">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label bg-danger">
                                    <i class="ki-duotone ki-shield-tick text-white fs-2x">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bold text-danger lh-1"><?= $ad_conduct_incidents ?></span>
                                <span class="text-gray-700 fw-semibold d-block fs-7">Conduct Incidents<br>
                                    <span class="text-muted fs-8">Last 30 days</span>
                                </span>
                            </div>
                            <a href="<?= base_url('conduct') ?>" class="btn btn-sm btn-danger ms-auto">View</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card card-flush bg-light-primary">
                        <div class="card-body d-flex align-items-center py-5 px-6">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label bg-primary">
                                    <i class="ki-duotone ki-notification-bing text-white fs-2x">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bold text-primary lh-1"><?= $ad_active_announcements ?></span>
                                <span class="text-gray-700 fw-semibold d-block fs-7">Announcements<br>
                                    <span class="text-muted fs-8">Currently active</span>
                                </span>
                            </div>
                            <a href="<?= base_url('dashboard/announcement') ?>" class="btn btn-sm btn-primary ms-auto">View</a>
                        </div>
                    </div>
                </div>
                <!--end::Quick stats row-->

                <!--begin::Recent announcements-->
                <?php if (!empty($ad_recent_announcements)): ?>
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-header pt-6">
                            <h3 class="card-title fw-bold text-gray-900">Recent Announcements</h3>
                        </div>
                        <div class="card-body pt-3">
                            <?php
                            $priBadge = ['Critical' => 'badge-light-danger', 'Important' => 'badge-light-warning', 'Info' => 'badge-light-primary'];
                            foreach ($ad_recent_announcements as $ann):
                                $badgeCls = $priBadge[$ann['priority']] ?? 'badge-light-secondary';
                            ?>
                            <div class="d-flex align-items-center mb-4">
                                <span class="bullet bullet-vertical h-30px w-4px me-3 <?=
                                    $ann['priority'] === 'Critical' ? 'bg-danger' :
                                    ($ann['priority'] === 'Important' ? 'bg-warning' : 'bg-primary')
                                ?>"></span>
                                <div class="flex-grow-1">
                                    <a href="<?= base_url('dashboard/announcement') ?>"
                                       class="text-gray-800 text-hover-primary fw-bold fs-6">
                                        <?= esc($ann['title']) ?>
                                    </a>
                                    <span class="text-muted fw-semibold d-block fs-7">
                                        <?= date('d M Y', strtotime($ann['created_at'])) ?>
                                    </span>
                                </div>
                                <span class="badge <?= $badgeCls ?> fw-bold ms-2"><?= $ann['priority'] ?></span>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
                <?php endif ?>
                <!--end::Recent announcements-->

            </div>
        </div>
        <!--end::Right column-->

    </div>
    <!--end::Lower row-->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.dataset.theme === 'dark'
        || (!document.documentElement.dataset.theme && window.matchMedia('(prefers-color-scheme:dark)').matches);

    function miniSparkline(el, data, color) {
        if (!el || typeof ApexCharts === 'undefined') return;
        new ApexCharts(el, {
            series: [{ data }],
            chart: { type: 'area', height: 70, sparkline: { enabled: true }, toolbar: { show: false } },
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0, stops: [0, 100] } },
            colors: [color],
            tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } },
        }).render();
    }

    miniSparkline(document.getElementById('chart_ad_students'), [2,3,4,5,6,6,<?= $ad_total_students ?>], '#009ef7');
    miniSparkline(document.getElementById('chart_ad_teachers'), [0,1,1,1,1,1,<?= $ad_total_teachers ?>], '#50cd89');

    // Attendance radial bar
    const attEl = document.getElementById('chart_ad_attendance');
    if (attEl && typeof ApexCharts !== 'undefined') {
        new ApexCharts(attEl, {
            series: [<?= $ad_attendance_pct ?>],
            chart: { type: 'radialBar', height: 100, sparkline: { enabled: true } },
            plotOptions: {
                radialBar: {
                    hollow: { size: '50%' },
                    dataLabels: {
                        name: { show: false },
                        value: { fontSize: '14px', fontWeight: 700, color: isDark ? '#fff' : '#181c32', offsetY: 5 },
                    },
                },
            },
            colors: ['#50cd89'],
            labels: ['Attendance'],
        }).render();
    }
});
</script>
