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
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if (empty($classrooms)): ?>
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
    <?php
    $byStudent = [];
    foreach ($classrooms as $row) {
        $sid = $row['student_id'];
        if (!isset($byStudent[$sid])) {
            $byStudent[$sid] = [
                'student_fname' => $row['student_fname'],
                'student_lname' => $row['student_lname'],
                'student_photo' => $row['student_photo'],
                'relationship'  => $row['relationship'],
                'classrooms'    => [],
            ];
        }
        $byStudent[$sid]['classrooms'][] = $row;
    }
    ?>
    <?php foreach ($byStudent as $child): ?>
    <div class="mb-10">
        <div class="d-flex align-items-center mb-5">
            <div class="symbol symbol-50px symbol-circle me-4">
                <?php if (!empty($child['student_photo'])): ?>
                    <img src="<?= base_url('uploads/profilePhoto/' . esc($child['student_photo'])) ?>"
                         alt="<?= esc($child['student_fname']) ?>" />
                <?php else: ?>
                    <div class="symbol-label fs-3 fw-bold bg-light-primary text-primary">
                        <?= strtoupper(substr($child['student_fname'], 0, 1) . substr($child['student_lname'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <div class="fw-bold fs-4 text-gray-800"><?= esc($child['student_fname'] . ' ' . $child['student_lname']) ?></div>
                <div class="text-muted fs-7"><?= esc($child['relationship']) ?></div>
            </div>
        </div>
        <div class="row g-6">
            <?php foreach ($child['classrooms'] as $c): ?>
            <div class="col-md-6 col-xl-4">
                <?php include(APPPATH . 'Views/app/classroom/_my_card.php'); ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

</div>
</div>
