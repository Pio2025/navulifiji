<style>
.subject-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.12) !important; transform: translateY(-2px); }
.subject-card:hover img { transform: scale(1.04); }
</style>

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

    <?php if ($mode === 'teacher'): ?>
    <!--begin::Teacher Teaching Subjects-->
    <?php if (!$activeAdmission): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-20">
            <i class="ki-duotone ki-information-5 fs-5x text-warning mb-5">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <h3 class="text-gray-700 fw-bold mb-3">No Active Admission</h3>
            <p class="text-muted fs-6 mb-0">You do not have an active school admission. Please contact your administrator.</p>
        </div>
    </div>
    <?php elseif (empty($teachingSubjects)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-20">
            <i class="ki-duotone ki-book fs-5x text-primary mb-5">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <h3 class="text-gray-700 fw-bold mb-3">No Teaching Subjects</h3>
            <p class="text-muted fs-6 mb-0">No teaching subjects have been assigned to your admission yet.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="mb-6">
        <h4 class="fw-bold text-gray-800 mb-1">My Teaching Subjects</h4>
        <p class="text-muted fs-7 mb-0">
            <?= count($teachingSubjects) ?> subject<?= count($teachingSubjects) !== 1 ? 's' : '' ?> assigned
            &bull; <?= esc($activeAdmission['admission_status']) ?> admission
        </p>
    </div>
    <div class="row g-5">
        <?php foreach ($teachingSubjects as $sub):
            $imgFile   = !empty($sub['sub_image']) ? trim($sub['sub_image']) : '';
            $imgPath   = FCPATH . 'uploads/subject/' . $imgFile;
            $imgUrl    = ($imgFile && file_exists($imgPath))
                         ? base_url('uploads/subject/' . $imgFile)
                         : base_url('uploads/subject/default.png');
        ?>
        <div class="col-md-3">
            <a href="<?= base_url('classroom/my/' . (int)$sub['sch_sub_id']) ?>"
               class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 subject-card" style="border-radius:.75rem;overflow:hidden;transition:box-shadow .2s,transform .2s;">
                    <div style="height:160px;overflow:hidden;background:#f5f8fa;">
                        <img src="<?= $imgUrl ?>"
                             alt="<?= esc($sub['subject_name']) ?>"
                             style="width:100%;height:100%;object-fit:cover;transition:transform .25s;" />
                    </div>
                    <div class="card-body p-4">
                        <div class="fw-bold text-gray-800 fs-6 mb-1"><?= esc($sub['subject_name']) ?></div>
                        <?php if (!empty($sub['dept_name'])): ?>
                        <div class="text-muted fs-8"><?= esc($sub['dept_name']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($sub['level_name'])): ?>
                        <span class="badge badge-light-primary fs-9 mt-2"><?= esc($sub['level_name']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <!--end::Teacher Teaching Subjects-->

    <?php elseif ($mode === 'none'): ?>
    <!--begin::No Access-->
    <div class="card">
        <div class="card-body text-center py-20">
            <i class="ki-duotone ki-shield-slash fs-5x text-danger mb-5">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <h3 class="text-gray-700 fw-bold mb-3">Not Available</h3>
            <p class="text-muted fs-6 mb-6">
                My Classroom is available for students and parents/guardians only.
            </p>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>
    <!--end::No Access-->

    <?php elseif (empty($classrooms)): ?>
    <!--begin::Empty-->
    <div class="card">
        <div class="card-body text-center py-20">
            <i class="ki-duotone ki-element-7 fs-5x text-primary mb-5">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <h3 class="text-gray-700 fw-bold mb-3">No Classrooms Found</h3>
            <p class="text-muted fs-6 mb-0">
                <?php if ($mode === 'student'): ?>
                    You are not enrolled in any classrooms yet.
                <?php else: ?>
                    No classrooms found for your children. Make sure your children are linked to your account and are enrolled.
                <?php endif; ?>
            </p>
        </div>
    </div>
    <!--end::Empty-->

    <?php else: ?>

    <?php if ($mode === 'parent'):
        // Group classrooms by student for parent view
        $byStudent = [];
        foreach ($classrooms as $row) {
            $sid = $row['student_id'];
            if (!isset($byStudent[$sid])) {
                $byStudent[$sid] = [
                    'student_id'    => $row['student_id'],
                    'student_fname' => $row['student_fname'],
                    'student_lname' => $row['student_lname'],
                    'student_photo' => $row['student_photo'],
                    'relationship'  => $row['relationship'],
                    'classrooms'    => [],
                ];
            }
            $byStudent[$sid]['classrooms'][] = $row;
        }
    endif; ?>

    <?php if ($mode === 'student'): ?>
    <!--begin::Student classroom grid-->
    <div class="row g-6">
        <?php foreach ($classrooms as $c): ?>
        <div class="col-md-6 col-xl-4">
            <?php include(APPPATH . 'Views/app/classroom/_my_card.php'); ?>
        </div>
        <?php endforeach; ?>
    </div>
    <!--end::Student classroom grid-->

    <?php else: ?>
    <!--begin::Parent view-->
    <?php foreach ($byStudent as $child): ?>
    <!--begin::Child section-->
    <div class="mb-10">
        <!--begin::Child header-->
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
                <div class="fw-bold fs-4 text-gray-800">
                    <?= esc($child['student_fname'] . ' ' . $child['student_lname']) ?>
                </div>
                <div class="text-muted fs-7"><?= esc($child['relationship']) ?></div>
            </div>
        </div>
        <!--end::Child header-->
        <div class="row g-6">
            <?php foreach ($child['classrooms'] as $c): ?>
            <div class="col-md-6 col-xl-4">
                <?php include(APPPATH . 'Views/app/classroom/_my_card.php'); ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!--end::Child section-->
    <?php endforeach; ?>
    <!--end::Parent view-->
    <?php endif; ?>

    <?php endif; ?>

</div>
</div>
