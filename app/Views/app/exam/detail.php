<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                <?= esc($exam['exam_name']) ?>
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('exam') ?>" class="text-muted text-hover-primary">Exams</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Detail</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <?php if ($canEdit): ?>
            <a href="<?= base_url('exam/edit/' . $exam['exam_id']) ?>" class="btn btn-sm btn-light-warning">
                <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i>
                Edit
            </a>
            <?php endif; ?>
            <?php if ($canDelete): ?>
            <button class="btn btn-sm btn-light-danger" onclick="confirmDelete()">
                <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                Delete
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <!--begin::Exam Summary-->
    <div class="card shadow-sm mb-6" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-5">
            <div class="row g-4">
                <div class="col-sm-6 col-md-3">
                    <div class="fw-semibold text-muted fs-7 mb-1">Exam Name</div>
                    <div class="fw-bold text-gray-900 fs-6"><?= esc($exam['exam_name']) ?></div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="fw-semibold text-muted fs-7 mb-1">Level</div>
                    <div class="fw-bold text-gray-900 fs-6"><?= esc($exam['level_name'] ?? '—') ?></div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="fw-semibold text-muted fs-7 mb-1">Status</div>
                    <span class="badge badge-light-<?= $exam['exam_status'] === 'Active' ? 'success' : 'danger' ?> fs-7">
                        <?= esc($exam['exam_status']) ?>
                    </span>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="fw-semibold text-muted fs-7 mb-1">Total Enrolled</div>
                    <div class="fw-bold text-primary fs-4"><?= $totalEnrolled ?></div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Exam Summary-->

    <!--begin::Schools Card-->
    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold fs-5 text-gray-900">
                    <i class="ki-duotone ki-bank fs-3 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    Schools
                </h3>
            </div>
            <div class="card-toolbar">
                <div class="position-relative">
                    <i class="ki-duotone ki-magnifier fs-4 position-absolute top-50 translate-middle-y ms-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" id="school_search"
                           class="form-control form-control-sm form-control-solid ps-10 w-200px"
                           placeholder="Search schools...">
                </div>
            </div>
        </div>
        <div class="card-body py-4">

            <?php if (empty($schools)): ?>
            <div class="text-center text-muted py-8">
                <i class="ki-duotone ki-bank fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                <p class="fs-6">No active schools found.</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="school_table">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">School</th>
                            <th class="min-w-120px text-center">Students Enrolled</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($schools as $sch): ?>
                        <tr data-name="<?= esc(strtolower($sch['sch_name'])) ?>">
                            <td>
                                <span class="fw-bold text-gray-900 fs-6"><?= esc($sch['sch_name']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($sch['enrolled_count'] > 0): ?>
                                <span class="badge badge-light-primary fs-7"><?= $sch['enrolled_count'] ?></span>
                                <?php else: ?>
                                <span class="text-muted fs-7">0</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('exam/detail/' . $exam['exam_id'] . '/school/' . $sch['sch_id']) ?>"
                                   class="btn btn-sm btn-light-primary">
                                    <i class="ki-duotone ki-eye fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    View Students
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

        </div>
    </div>
    <!--end::Schools Card-->

</div>
</div>

<form id="delete_form" method="post" action="<?= base_url('exam/delete/' . $exam['exam_id']) ?>">
    <?= csrf_field() ?>
</form>

<script>
// School search
const schRows = document.querySelectorAll('#school_table tbody tr[data-name]');
document.getElementById('school_search').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    schRows.forEach(r => r.style.display = !q || r.dataset.name.includes(q) ? '' : 'none');
});

function confirmDelete() {
    Swal.fire({
        title: 'Delete this Exam?',
        text: 'All student exam records for this exam will also be deleted. This cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
    }).then(r => { if (r.isConfirmed) document.getElementById('delete_form').submit(); });
}
</script>
