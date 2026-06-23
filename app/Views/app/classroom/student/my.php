<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                My Classroom
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">My Classroom</li>
                <?php foreach ($years ?? [] as $_yr): ?>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item">
                    <a href="#" class="year-crumb fw-bold <?= (int)$_yr === (int)($defaultYear ?? 0) ? 'text-primary' : 'text-muted text-hover-primary' ?>"
                       data-year="<?= $_yr ?>"><?= $_yr ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Year classroom content-->
    <div id="stu-year-content">
        <?= view('app/classroom/student/_year_classrooms', [
            'classrooms'      => $classrooms ?? [],
            'sessionFname'    => $sessionFname    ?? '',
            'sessionPhotoUrl' => $sessionPhotoUrl ?? null,
            'sessionUserId'   => $sessionUserId   ?? 0,
        ]) ?>
    </div>
    <!--end::Year classroom content-->

</div>
</div>

<!-- Shared discussion modals/CSS/JS — loaded once, persists across year switches -->
<?= view('app/classroom/teacher/_class_discussion_shared') ?>

<script>
var _stuYearAjaxUrl = '<?= base_url('classroom/my/year/') ?>';

function stuMyOpenChat(userId, fname, lname, photo) {
    var photoBase = '<?= base_url("uploads/profilePhoto/") ?>';
    var name      = ((fname || '') + ' ' + (lname || '')).trim();
    var photoUrl  = photo ? photoBase + photo : '';
    if (window.NavuliChat && typeof window.NavuliChat.openForUser === 'function') {
        window.NavuliChat.openForUser(userId, name, photoUrl);
    }
}

function _stuInjectAndExec(container, html) {
    container.innerHTML = html;
    Array.from(container.querySelectorAll('script')).forEach(function (old) {
        var s = document.createElement('script');
        s.textContent = old.textContent;
        document.head.appendChild(s);
        document.head.removeChild(s);
    });
}

(function () {
    var container  = document.getElementById('stu-year-content');
    var activeYear = '<?= $defaultYear ?? '' ?>';
    var YEAR_KEY   = 'smy_year';
    var TAB_PFX    = 'cmy_tab_';

    // Persist whichever tab the user opens
    document.addEventListener('shown.bs.tab', function (e) {
        var href  = e.target.getAttribute('href') || '';
        var match = href.match(/_(\d+)$/);
        if (match) localStorage.setItem(TAB_PFX + match[1], href);
    });

    // After content is in the DOM, re-open any stored non-default tabs
    function restoreTabs(root) {
        setTimeout(function () {
            root.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (el) {
                var href  = el.getAttribute('href') || '';
                var match = href.match(/_(\d+)$/);
                if (!match) return;
                var stored = localStorage.getItem(TAB_PFX + match[1]);
                if (stored && stored === href) {
                    try { new bootstrap.Tab(el).show(); } catch (err) {}
                }
            });
        }, 50);
    }

    function updateCrumbs(yr) {
        document.querySelectorAll('.year-crumb').forEach(function (l) {
            l.classList.toggle('text-primary',       l.dataset.year === yr);
            l.classList.toggle('text-muted',         l.dataset.year !== yr);
            l.classList.toggle('text-hover-primary', l.dataset.year !== yr);
        });
    }

    function loadYear(yr, callback) {
        updateCrumbs(yr);
        container.innerHTML = '<div class="text-center py-16"><span class="spinner-border text-primary"></span></div>';
        fetch(_stuYearAjaxUrl + yr, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                _stuInjectAndExec(container, data.html || '');
                activeYear = yr;
                localStorage.setItem(YEAR_KEY, yr);
                if (callback) callback();
            })
            .catch(function () {
                container.innerHTML = '<div class="alert alert-danger m-6">Failed to load classrooms. Please try again.</div>';
            });
    }

    document.querySelectorAll('.year-crumb').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var yr = this.dataset.year;
            if (yr === activeYear) return;
            loadYear(yr, function () { restoreTabs(container); });
        });
    });

    // On page load: restore the last selected year (handles browser Back navigation)
    var storedYear = localStorage.getItem(YEAR_KEY);
    if (storedYear && storedYear !== activeYear && document.querySelector('.year-crumb[data-year="' + storedYear + '"]')) {
        loadYear(storedYear, function () { restoreTabs(container); });
    } else {
        restoreTabs(container);
    }
})();
</script>
