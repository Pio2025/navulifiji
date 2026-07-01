<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Conduct Appeals
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('conduct') ?>" class="text-muted text-hover-primary">Conduct</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Appeals</li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('conduct') ?>" class="btn btn-sm btn-light">Back</a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
        <div class="card-body py-5">

            <!--begin::Filter-->
            <form class="row g-2 align-items-center mb-6">
                <div class="col-auto">
                    <label class="form-label fs-8 mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm form-select-solid" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="Pending"  <?= $statusFilter === 'Pending'  ? 'selected' : '' ?>>Pending</option>
                        <option value="Approved" <?= $statusFilter === 'Approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="Rejected" <?= $statusFilter === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
            </form>
            <!--end::Filter-->

            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-180px">Student</th>
                            <?php if ($isSuperAdmin): ?>
                            <th class="min-w-80px">School</th>
                            <?php endif; ?>
                            <th class="min-w-130px">Incident Type</th>
                            <th class="min-w-120px">Submitted</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-80px text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($appeals)): ?>
                        <tr>
                            <td colspan="<?= $isSuperAdmin ? 6 : 5 ?>" class="text-center text-muted py-8">
                                No appeals found<?= $statusFilter ? ' with status "' . esc($statusFilter) . '"' : '' ?>.
                            </td>
                        </tr>
                    <?php else: foreach ($appeals as $ap): ?>
                        <?php
                        $statusColor = match($ap['appeal_status']) {
                            'Approved' => 'success',
                            'Rejected' => 'danger',
                            default    => 'warning',
                        };
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($ap['student_photo'])): ?>
                                    <div class="symbol symbol-30px">
                                        <img src="<?= base_url('uploads/profilePhoto/' . $ap['student_photo']) ?>" class="rounded-circle" alt="photo">
                                    </div>
                                    <?php else: ?>
                                    <div class="symbol symbol-30px">
                                        <div class="symbol-label fs-8 fw-bold bg-light-primary text-primary">
                                            <?= strtoupper(substr($ap['student_fname'], 0, 1) . substr($ap['student_lname'], 0, 1)) ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="fw-semibold text-gray-900">
                                        <?= esc($ap['student_fname'] . ' ' . $ap['student_lname']) ?>
                                    </div>
                                </div>
                            </td>
                            <?php if ($isSuperAdmin): ?>
                            <td class="text-muted fs-7"><?= esc($ap['sch_id_fk'] ?? '—') ?></td>
                            <?php endif; ?>
                            <td>
                                <span class="badge badge-light-<?= !empty($ap['is_positive']) ? 'success' : 'danger' ?>">
                                    <?= esc($ap['type_name'] ?? '—') ?>
                                </span>
                            </td>
                            <td class="text-muted fs-7">
                                <?= esc(date('d M Y', strtotime($ap['submitted_date']))) ?>
                            </td>
                            <td>
                                <span class="badge badge-light-<?= $statusColor ?>">
                                    <?= esc($ap['appeal_status']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('conduct/detail/' . $ap['incident_id']) ?>"
                                   class="btn btn-sm btn-light-primary">
                                    Review
                                </a>
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
