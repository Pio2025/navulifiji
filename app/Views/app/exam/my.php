<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                My Exams
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">My Exams</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <?php if ($isStudent): ?>

        <?php if (!$enrolment): ?>
        <!--begin::No enrolment-->
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body py-10 text-center">
                <i class="ki-duotone ki-book-open fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span></i>
                <p class="text-muted fs-6">You are not currently enrolled. Please contact your school administrator.</p>
            </div>
        </div>
        <!--end::No enrolment-->
        <?php elseif (empty($studentExams)): ?>
        <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
            <div class="card-body py-10 text-center">
                <i class="ki-duotone ki-document fs-3x text-muted mb-4"><span class="path1"></span><span class="path2"></span></i>
                <p class="text-muted fs-6">You have not been entered into any exams yet.</p>
            </div>
        </div>
        <?php else: ?>

        <?= view('app/exam/_exam_table', ['studentExams' => $studentExams, 'title' => 'My Exams']) ?>

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
                    <?php if (!$cd['enrolment']): ?>
                        <p class="text-muted fs-7 py-4">Not currently enrolled.</p>
                    <?php elseif (empty($cd['exams'])): ?>
                        <p class="text-muted fs-7 py-4">Not entered in any exams yet.</p>
                    <?php else: ?>
                        <?= view('app/exam/_exam_table', ['studentExams' => $cd['exams'], 'title' => '']) ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

</div>
</div>
