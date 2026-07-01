<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Conduct Report
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
                <li class="breadcrumb-item text-muted">Report</li>
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

            <form class="row g-2 align-items-center mb-5">
                <div class="col-auto">
                    <label class="form-label fs-8 mb-1">Year</label>
                    <select name="year" class="form-select form-select-sm form-select-solid" onchange="this.form.submit()">
                        <?php for ($y = (int) date('Y'); $y >= (int) date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= $y === (int) $year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">Student</th>
                            <?php if ($isSuperAdmin): ?>
                            <th class="min-w-100px">School</th>
                            <?php endif; ?>
                            <th class="min-w-100px">Incidents</th>
                            <th class="min-w-100px">Positive</th>
                            <th class="min-w-100px">Negative</th>
                            <th class="min-w-100px">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($summary)): ?>
                        <tr><td colspan="<?= $isSuperAdmin ? 6 : 5 ?>" class="text-center text-muted py-8">No conduct data for <?= esc($year) ?>.</td></tr>
                    <?php else: foreach ($summary as $row): ?>
                        <tr>
                            <td class="text-gray-900 fw-bold"><?= esc($row['student_fname'] . ' ' . $row['student_lname']) ?></td>
                            <?php if ($isSuperAdmin): ?>
                            <td class="text-muted fs-7"><?= esc($row['sch_id_fk'] ?? '—') ?></td>
                            <?php endif; ?>
                            <td><?= (int) $row['incident_count'] ?></td>
                            <td class="text-success fw-bold">+<?= (int) $row['positive_points'] ?></td>
                            <td class="text-danger fw-bold"><?= (int) $row['negative_points'] ?></td>
                            <td class="fw-bold <?= (int) $row['total_points'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= (int) $row['total_points'] >= 0 ? '+' : '' ?><?= (int) $row['total_points'] ?>
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
