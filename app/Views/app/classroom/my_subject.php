<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($subject['subject_name']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($subject['subject_name']) ?></li>
            </ul>
        </div>
        <a href="<?= base_url('classroom/my') ?>" class="btn btn-sm btn-light">
            <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Back
        </a>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="row g-6">

        <!--begin::Left panel-->
        <div class="col-lg-3">

            <!--begin::User menu-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Body-->
                <div class="card-body pt-15 px-0">
                    <!--begin::Member-->
                    <div class="d-flex flex-column text-center mb-9 px-9">
                        <!--begin::Photo-->
                        <div class="symbol symbol-80px symbol-lg-150px mb-4">
                            <img src="assets/media/avatars/300-3.jpg" class="" alt="" />
                        </div>
                        <!--end::Photo-->
                        <!--begin::Info-->
                        <div class="text-center">
                            <div class="d-flex justify-content-between py-2 border-dashed-row"></div>
                            <div class="d-flex justify-content-between py-2 border-dashed-row">
                                <span class="text-gray-600 fw-semibold fs-7">Teacher</span>
                                <span class="text-gray-800 fw-bold fs-7"><?= esc(session('fname') . ' ' . session('lname')) ?></span>
                            </div>
                            <?php if (!empty($subject['subject_name'])): ?>
                            <div class="d-flex justify-content-between py-2 border-dashed-row">
                                <span class="text-gray-600 fw-semibold fs-7">Subject</span>
                                <span class="text-gray-800 fw-bold fs-7"><?= esc($subject['subject_name']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($subject['dept_name'])): ?>
                            <div class="d-flex justify-content-between py-2 border-dashed-row">
                                <span class="text-gray-600 fw-semibold fs-7">Department</span>
                                <span class="text-gray-800 fw-bold fs-7"><?= esc($subject['dept_name']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($subject['level_name'])): ?>
                            <div class="d-flex justify-content-between py-2 border-dashed-row">
                                <span class="text-gray-600 fw-semibold fs-7">Year Level</span>
                                <span class="badge badge-light-primary fs-7"><?= esc($subject['level_name']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Member-->
                    <!--begin::Row-->
                    <div class="row px-9 mb-4">
                        <!--begin::Col-->
                        <div class="col-md-4 text-center">
                            <div class="text-gray-800 fw-bold fs-3">
                                <span class="m-0" data-kt-countup="true" data-kt-countup-value="642">0</span>
                            </div>
                            <span class="text-gray-500 fs-8 d-block fw-bold">STUDENT</span>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4 text-center">
                            <div class="text-gray-800 fw-bold fs-3">
                            <span class="m-0" data-kt-countup="true" data-kt-countup-value="24">0</span>K</div>
                            <span class="text-gray-500 fs-8 d-block fw-bold">LESSONS</span>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4 text-center">
                            <div class="text-gray-800 fw-bold fs-3">
                            <span class="m-0" data-kt-countup="true" data-kt-countup-value="12">0</span>K</div>
                            <span class="text-gray-500 fs-8 d-block fw-bold">QUIZZES</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <div class="row px-9 mb-4">
                        <?php
                        $schSubId = (int) $subject['sch_sub_id'];
                        $navLinks = [
                            ['label' => 'Dashboards',   'icon' => 'ki-element-11',  'url' => 'classroom/my/' . $schSubId . '/dashboard'],
                            ['label' => 'Lessons',     'icon' => 'ki-book-open',   'url' => 'classroom/my/' . $schSubId . '/lessons'],
                            ['label' => 'Assignments', 'icon' => 'ki-document',    'url' => 'classroom/my/' . $schSubId . '/assignments'],
                            ['label' => 'Quizzes',     'icon' => 'ki-abstract-26', 'url' => 'classroom/my/' . $schSubId . '/quizzes'],
                            ['label' => 'Grades',      'icon' => 'ki-chart-simple','url' => 'classroom/my/' . $schSubId . '/grades'],
                            ['label' => 'Feedbacks',   'icon' => 'ki-message-text-2','url' => 'classroom/my/' . $schSubId . '/feedbacks'],
                            ['label' => 'Discussions', 'icon' => 'ki-message-edit', 'url' => 'classroom/my/' . $schSubId . '/discussions'],
                        ];
                        $currentUri = uri_string();
                        $anyActive  = array_filter($navLinks, fn($n) => str_ends_with(rtrim($currentUri, '/'), ltrim($n['url'], '/')));
                        foreach ($navLinks as $i => $nav):
                            $isActive = $anyActive
                                ? str_ends_with(rtrim($currentUri, '/'), ltrim($nav['url'], '/'))
                                : $i === 0;
                        ?>
                        <a href="<?= base_url($nav['url']) ?>"
                        class="d-flex align-items-center gap-3 px-4 py-3 rounded-2 text-decoration-none mb-1
                                <?= $isActive ? 'bg-primary text-white' : 'text-gray-700 text-hover-primary nav-link-hover' ?>">
                            <i class="ki-duotone <?= $nav['icon'] ?> fs-4 <?= $isActive ? 'text-white' : 'text-gray-500' ?>">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span><span class="path4"></span>
                            </i>
                            <span class="fw-semibold fs-7"><?= $nav['label'] ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::User menu-->

        </div>
        <!--end::Left panel-->

        <!--begin::Right panel-->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 pt-5 pb-0">
                    <!--begin::Term tabs-->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-semibold" id="subject_term_tabs">
                        <li class="nav-item">
                            <a class="nav-link active text-active-primary pb-4"
                               data-bs-toggle="tab" href="javascript:void(0)"
                               data-bs-target="#tab_term_1">Term 1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4"
                               data-bs-toggle="tab" href="javascript:void(0)"
                               data-bs-target="#tab_term_2">Term 2</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4"
                               data-bs-toggle="tab" href="javascript:void(0)"
                               data-bs-target="#tab_term_3">Term 3</a>
                        </li>
                    </ul>
                    <!--end::Term tabs-->
                </div>
                <div class="card-body pt-6">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab_term_1">
                            <div class="text-center py-12 text-muted">
                                <i class="ki-duotone ki-calendar fs-4x text-gray-300 mb-3">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <div class="fs-6 fw-semibold">Term 1 content coming soon.</div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_term_2">
                            <div class="text-center py-12 text-muted">
                                <i class="ki-duotone ki-calendar fs-4x text-gray-300 mb-3">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <div class="fs-6 fw-semibold">Term 2 content coming soon.</div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_term_3">
                            <div class="text-center py-12 text-muted">
                                <i class="ki-duotone ki-calendar fs-4x text-gray-300 mb-3">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <div class="fs-6 fw-semibold">Term 3 content coming soon.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Right panel-->

    </div>

<style>
.border-dashed-row { border-bottom: 1px dashed #c4c4d4 !important; }
.nav-link-hover:hover { background: #f5f8ff; }
.nav-pills-custom .nav-link:not(.active):hover {
    background: #f5f8ff;
    border-radius: 0.475rem;
    color: var(--bs-primary) !important;
}
.nav-pills-custom .nav-link:not(.active):hover i {
    color: var(--bs-primary) !important;
}
</style>

</div>
</div>

<script>
(function () {
    const BODY     = document.body;
    const ATTR     = 'data-kt-app-sidebar-minimize';
    const SESS_KEY = 'navuli_sidebar_pre_subject';

    // ── On enter: save current state, then force-minimize ────────────
    const alreadyMinimized = BODY.getAttribute(ATTR) === 'on';
    sessionStorage.setItem(SESS_KEY, alreadyMinimized ? '1' : '0');

    if (!alreadyMinimized) {
        BODY.setAttribute(ATTR, 'on');
        window.dispatchEvent(new Event('resize'));
    }

    // ── On exit: restore if we changed it ────────────────────────────
    window.addEventListener('pagehide', function () {
        if (sessionStorage.getItem(SESS_KEY) === '0') {
            sessionStorage.setItem('navuli_restore_sidebar', '1');
        }
        sessionStorage.removeItem(SESS_KEY);
    });
})();
</script>
