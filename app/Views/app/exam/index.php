<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Exams
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Exams</li>
            </ul>
        </div>
        <?php if ($canAdd): ?>
        <a href="<?= base_url('exam/add') ?>" class="btn btn-sm btn-primary">
            <i class="ki-duotone ki-plus fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
            Add Exam
        </a>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-5">

            <!--begin::Controls-->
            <div class="row g-2 align-items-center mb-5">
                <div class="col-12 col-md">
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input type="text" id="exam_search"
                               class="form-control form-control-solid ps-12 w-100"
                               placeholder="Search exams..." />
                    </div>
                </div>
                <div class="col-6 col-sm-auto">
                    <select id="filter_status" class="form-select form-select-sm form-select-solid">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-6 col-sm-auto">
                    <select id="filter_level" class="form-select form-select-sm form-select-solid">
                        <option value="">All Levels</option>
                        <?php
                        $levels = array_unique(array_filter(array_column($exams, 'level_name')));
                        sort($levels);
                        foreach ($levels as $lv): ?>
                        <option value="<?= esc($lv) ?>"><?= esc($lv) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!--end::Controls-->

            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="exam_table">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">Exam Name</th>
                            <th class="min-w-120px">Level</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-100px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($exams)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-8">No exams found.</td>
                        </tr>
                    <?php else: foreach ($exams as $exam): ?>
                        <tr data-name="<?= esc(strtolower($exam['exam_name'])) ?>"
                            data-status="<?= esc($exam['exam_status']) ?>"
                            data-level="<?= esc($exam['level_name'] ?? '') ?>">
                            <td>
                                <a href="<?= base_url('exam/detail/' . $exam['exam_id']) ?>"
                                   class="text-gray-900 fw-bold text-hover-primary fs-6">
                                    <?= esc($exam['exam_name']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="text-muted fw-semibold fs-7">
                                    <?= esc($exam['level_name'] ?? '—') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-light-<?= $exam['exam_status'] === 'Active' ? 'success' : 'danger' ?>">
                                    <?= esc($exam['exam_status']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('exam/detail/' . $exam['exam_id']) ?>"
                                   class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                   title="View">
                                    <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </a>
                                <?php if ($canEdit): ?>
                                <a href="<?= base_url('exam/edit/' . $exam['exam_id']) ?>"
                                   class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1"
                                   title="Edit">
                                    <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                                <?php endif; ?>
                                <?php if ($canDelete): ?>
                                <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                                        title="Delete"
                                        onclick="confirmDelete(<?= $exam['exam_id'] ?>, '<?= esc($exam['exam_name'], 'js') ?>')">
                                    <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
</div>

<!-- Delete confirm modal -->
<form id="delete_form" method="post" action="">
    <?= csrf_field() ?>
</form>

<script>
// Filtering
const rows = document.querySelectorAll('#exam_table tbody tr[data-name]');

function applyFilters() {
    const q      = document.getElementById('exam_search').value.toLowerCase();
    const status = document.getElementById('filter_status').value;
    const level  = document.getElementById('filter_level').value;
    rows.forEach(r => {
        const matchQ      = !q      || r.dataset.name.includes(q);
        const matchStatus = !status || r.dataset.status === status;
        const matchLevel  = !level  || r.dataset.level === level;
        r.style.display = (matchQ && matchStatus && matchLevel) ? '' : 'none';
    });
}

document.getElementById('exam_search').addEventListener('input', applyFilters);
document.getElementById('filter_status').addEventListener('change', applyFilters);
document.getElementById('filter_level').addEventListener('change', applyFilters);

function confirmDelete(id, name) {
    Swal.fire({
        title: 'Delete Exam?',
        text: `"${name}" and all its student records will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete_form');
            form.action = `<?= base_url('exam/delete/') ?>${id}`;
            form.submit();
        }
    });
}
</script>
