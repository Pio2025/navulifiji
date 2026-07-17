<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($cls['class_name']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('classroom/child/my') ?>" class="text-muted text-hover-primary">Children's Classrooms</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($cls['class_name']) ?></li>
            </ul>
        </div>
        <!--begin::Child info pill-->
        <div class="d-flex align-items-center gap-3">
            <div class="symbol symbol-40px symbol-circle">
                <?php if (!empty($cls['child_photo'])): ?>
                    <img src="<?= base_url('uploads/profilePhoto/' . esc($cls['child_photo'])) ?>" alt="">
                <?php else: ?>
                    <div class="symbol-label fw-bold bg-light-primary text-primary fs-6">
                        <?= strtoupper(substr($cls['child_fname'] ?? '', 0, 1) . substr($cls['child_lname'] ?? '', 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <div class="fw-bold text-gray-800 fs-6 lh-1"><?= esc(($cls['child_fname'] ?? '') . ' ' . ($cls['child_lname'] ?? '')) ?></div>
                <div class="text-muted fs-8"><?= esc($cls['relationship'] ?? '') ?></div>
            </div>
        </div>
        <!--end::Child info pill-->
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?= view('app/classroom/student/_year_classrooms', [
        'classrooms'      => [$cls],
        'sessionFname'    => $sessionFname    ?? '',
        'sessionPhotoUrl' => $sessionPhotoUrl ?? null,
        'sessionUserId'   => $childUserId,   // exam report links use child's userId
        'parentChildId'   => $childUserId,   // attendance links append &student_id=
        'allowPost'       => true,            // parents can post in active classrooms; _year_classrooms gates on $isActive
    ]) ?>

</div>
</div>

<?= view('app/classroom/teacher/_class_discussion_shared') ?>

<script>
function stuMyOpenChat(userId, fname, lname, photo) {
    var photoBase = '<?= base_url("uploads/profilePhoto/") ?>';
    var name      = ((fname || '') + ' ' + (lname || '')).trim();
    var photoUrl  = photo ? photoBase + photo : '';
    if (window.NavuliChat && typeof window.NavuliChat.openForUser === 'function') {
        window.NavuliChat.openForUser(userId, name, photoUrl);
    }
}
</script>
