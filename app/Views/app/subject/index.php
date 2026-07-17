<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Subject Catalogue</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Subjects</li>
            </ul>
        </div>
        <?php if ($canAdd): ?>
        <a href="<?= base_url('subject/add') ?>" class="btn btn-primary btn-sm">
            <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i>
            Add Subject
        </a>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php
$total       = count($subjects);
$examinable  = count(array_filter($subjects, fn($s) => (int)$s['is_examinable'] === 1));
$nonExam     = $total - $examinable;
?>

<!--begin::Stats-->
<div class="row g-5 mb-6">
    <div class="col-sm-4">
        <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center gap-4 py-5">
                <div class="symbol symbol-50px">
                    <span class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-book fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= $total ?></div>
                    <div class="text-muted fs-7">Total Subjects</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center gap-4 py-5">
                <div class="symbol symbol-50px">
                    <span class="symbol-label bg-light-success">
                        <i class="ki-duotone ki-check-circle fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= $examinable ?></div>
                    <div class="text-muted fs-7">Examinable</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card card-flush h-100">
            <div class="card-body d-flex align-items-center gap-4 py-5">
                <div class="symbol symbol-50px">
                    <span class="symbol-label bg-light-warning">
                        <i class="ki-duotone ki-information fs-2x text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </span>
                </div>
                <div>
                    <div class="fs-2 fw-bold text-gray-800"><?= $nonExam ?></div>
                    <div class="text-muted fs-7">Non-Examinable</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Stats-->

<!--begin::Table card-->
<div class="card">
    <div class="card-header border-0 pt-6 pb-0">
        <div class="card-title">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <!--Search-->
                <div class="d-flex align-items-center position-relative">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                    <input type="text" id="search-subject" class="form-control form-control-solid w-200px ps-12" placeholder="Search subjects...">
                </div>
                <!--Level filter-->
                <select id="filter-level" class="form-select form-select-solid w-180px">
                    <option value="">All Levels</option>
                    <?php foreach ($levels as $lvl): ?>
                    <option value="<?= (int)$lvl['level_id'] ?>"><?= esc($lvl['level_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <!--Type filter-->
                <select id="filter-type" class="form-select form-select-solid w-160px">
                    <option value="">All Types</option>
                    <option value="1">Examinable</option>
                    <option value="0">Non-Examinable</option>
                </select>
            </div>
        </div>
        <div class="card-toolbar">
            <span class="badge badge-light-primary fs-7 px-4 py-2" id="visible-count"><?= $total ?> subjects</span>
        </div>
    </div>
    <div class="card-body pt-4">
        <div class="table-responsive">
        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3" id="subject-table">
            <thead>
                <tr class="fw-bold text-muted fs-7 text-uppercase border-bottom border-gray-200">
                    <th class="min-w-250px">Subject Name</th>
                    <th class="min-w-160px">Level</th>
                    <th class="min-w-120px text-center">Type</th>
                    <?php if ($canEdit || $canDelete): ?>
                    <th class="min-w-100px text-end">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="subject-tbody">
            <?php foreach ($subjects as $sub): ?>
            <tr data-name="<?= strtolower(esc($sub['subject_name'])) ?>"
                data-level="<?= (int)$sub['level_id'] ?>"
                data-exam="<?= (int)$sub['is_examinable'] ?>">
                <td>
                    <span class="fw-semibold text-gray-800 fs-7"><?= esc($sub['subject_name']) ?></span>
                </td>
                <td>
                    <span class="text-muted fs-7"><?= esc($sub['level_name'] ?? '—') ?></span>
                </td>
                <td class="text-center">
                    <?php if ((int)$sub['is_examinable']): ?>
                    <span class="badge badge-light-success fs-8">Examinable</span>
                    <?php else: ?>
                    <span class="badge badge-light-warning fs-8">Non-Exam</span>
                    <?php endif; ?>
                </td>
                <?php if ($canEdit || $canDelete): ?>
                <td class="text-end">
                    <?php if ($canEdit): ?>
                    <a href="<?= base_url('subject/edit/' . (int)$sub['subject_id']) ?>" class="btn btn-icon btn-sm btn-light-primary me-1">
                        <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($canDelete): ?>
                    <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-delete-subject"
                        data-id="<?= (int)$sub['subject_id'] ?>"
                        data-name="<?= esc($sub['subject_name']) ?>">
                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </button>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div id="no-results" class="text-center py-10 d-none">
            <i class="ki-duotone ki-search-list fs-3x text-muted mb-3 d-block"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="text-muted fs-6">No subjects match your filters.</div>
        </div>
    </div>
</div>
<!--end::Table card-->

</div>
</div>

<script>
(function () {
    const csrfName = '<?= csrf_token() ?>';
    let csrfHash   = '<?= csrf_hash() ?>';

    // ── Client-side filter ───────────────────────────────────────────────────
    function filterTable() {
        const q     = document.getElementById('search-subject').value.toLowerCase().trim();
        const lvl   = document.getElementById('filter-level').value;
        const type  = document.getElementById('filter-type').value;
        const rows  = document.querySelectorAll('#subject-tbody tr');
        let visible = 0;

        rows.forEach(function (row) {
            const nameMatch  = !q    || row.dataset.name.includes(q);
            const levelMatch = !lvl  || row.dataset.level === lvl;
            const typeMatch  = type === '' || row.dataset.exam === type;
            const show = nameMatch && levelMatch && typeMatch;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('visible-count').textContent = visible + ' subject' + (visible !== 1 ? 's' : '');
        document.getElementById('no-results').classList.toggle('d-none', visible > 0);
    }

    document.getElementById('search-subject').addEventListener('input',  filterTable);
    document.getElementById('filter-level').addEventListener('change',   filterTable);
    document.getElementById('filter-type').addEventListener('change',    filterTable);

    // ── Delete ───────────────────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-subject');
        if (!btn) return;

        const id   = btn.dataset.id;
        const name = btn.dataset.name;

        Swal.fire({
            icon: 'warning',
            title: 'Delete Subject?',
            html: 'Are you sure you want to delete <strong>' + name + '</strong>?<br><small class="text-muted">This will fail if the subject is assigned to any school.</small>',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#F1416C',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            const fd = new FormData();
            fd.append(csrfName, csrfHash);

            fetch('<?= base_url('subject/remove/') ?>' + id, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(function (data) {
                    if (data.csrf_hash) csrfHash = data.csrf_hash;
                    if (data.success) {
                        btn.closest('tr').remove();
                        filterTable();
                        Swal.fire({ icon: 'success', title: 'Deleted', text: data.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Cannot Delete', text: data.message });
                    }
                })
                .catch(function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                });
        });
    });
})();
</script>
