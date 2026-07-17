<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Children's Classrooms
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('classroom/my') ?>" class="text-muted text-hover-primary">My Classroom</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Children's Classrooms</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if (empty($years)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-20">
            <i class="ki-duotone ki-element-7 fs-5x text-primary mb-5">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <h3 class="text-gray-700 fw-bold mb-3">No Classrooms Found</h3>
            <p class="text-muted fs-6 mb-0">No classrooms found for your children. Make sure your children are linked to your account and are enrolled.</p>
        </div>
    </div>
    <?php else: ?>

    <!--begin::Year tabs-->
    <div class="d-flex align-items-center gap-2 flex-wrap mb-4">
        <?php foreach ($years as $yr): ?>
        <a href="#"
           class="pcmy-year-tab btn btn-sm <?= (int)$yr === (int)$defaultYear ? 'btn-primary' : 'btn-light text-gray-600' ?>"
           data-year="<?= (int)$yr ?>">
            <?= (int)$yr ?>
        </a>
        <?php endforeach; ?>
    </div>
    <hr class="mb-6" style="border-color:#e9edf0;">
    <!--end::Year tabs-->

    <!--begin::Year content-->
    <div id="pcmy-year-content">
        <?= view('app/classroom/parent/_child_year_classrooms', ['byStudent' => $byStudent]) ?>
    </div>
    <!--end::Year content-->

    <?php endif; ?>

</div>
</div>

<script>
(function () {
    var YEAR_URL    = '<?= base_url('classroom/child/my/year/') ?>';
    var activeYear  = '<?= (int)($defaultYear ?? 0) ?>';
    var YEAR_KEY    = 'pcmy_year';
    var container   = document.getElementById('pcmy-year-content');

    function setActiveTab(yr) {
        document.querySelectorAll('.pcmy-year-tab').forEach(function (btn) {
            var isActive = String(btn.dataset.year) === String(yr);
            btn.classList.toggle('btn-primary', isActive);
            btn.classList.toggle('btn-light',   !isActive);
            btn.classList.toggle('text-gray-600', !isActive);
        });
    }

    function loadYear(yr) {
        if (String(yr) === String(activeYear)) return;
        setActiveTab(yr);
        container.innerHTML = '<div class="text-center py-16"><span class="spinner-border text-primary"></span></div>';
        fetch(YEAR_URL + yr, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                container.innerHTML = data.html || '';
                activeYear = String(yr);
                localStorage.setItem(YEAR_KEY, yr);
            })
            .catch(function () {
                container.innerHTML = '<div class="alert alert-danger m-6">Failed to load classrooms. Please try again.</div>';
            });
    }

    document.querySelectorAll('.pcmy-year-tab').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            loadYear(this.dataset.year);
        });
    });

    // Restore last selected year on page load
    var stored = localStorage.getItem(YEAR_KEY);
    if (stored && stored !== activeYear && document.querySelector('.pcmy-year-tab[data-year="' + stored + '"]')) {
        loadYear(stored);
    }
})();
</script>
