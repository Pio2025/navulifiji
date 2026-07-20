<?php
$photoFile = !empty($enrolment['profile_photo']) ? trim($enrolment['profile_photo']) : '';
$photoPath = FCPATH . 'uploads/profilePhoto/' . $photoFile;
$hasPhoto  = $photoFile && file_exists($photoPath);
$photoUrl  = $hasPhoto ? base_url('uploads/profilePhoto/' . $photoFile) : '';
$initials  = strtoupper(substr($enrolment['fname'] ?? '', 0, 1) . substr($enrolment['lname'] ?? '', 0, 1));

$logoFile = !empty($enrolment['sch_logo']) ? trim($enrolment['sch_logo']) : '';
$logoPath = FCPATH . 'uploads/school/logo/' . $logoFile;
$hasLogo  = $logoFile && file_exists($logoPath);
$logoUrl  = $hasLogo ? base_url('uploads/school/logo/' . $logoFile) : '';

$statusRaw = $enrolment['enrol_status'] ?? '';
$statusCls = match (strtolower($statusRaw)) {
    'active'                          => 'success',
    'pending'                         => 'warning',
    'inactive', 'rejected', 'declined' => 'danger',
    default                           => 'secondary',
};
?>
<div class="d-flex align-items-center mb-6">
    <div class="symbol symbol-60px symbol-circle me-4">
        <?php if ($hasPhoto): ?>
        <img src="<?= $photoUrl ?>" alt="<?= esc($enrolment['fname'] ?? '') ?>" />
        <?php else: ?>
        <div class="symbol-label fs-3 fw-bold bg-light-primary text-primary"><?= esc($initials) ?></div>
        <?php endif; ?>
    </div>
    <div>
        <div class="fw-bold fs-4 text-gray-800">
            <?= esc(trim(($enrolment['fname'] ?? '') . ' ' . ($enrolment['oname'] ?? '') . ' ' . ($enrolment['lname'] ?? ''))) ?>
        </div>
        <div class="text-muted fs-7">
            <?= esc($enrolment['role_name'] ?? '') ?>
            <?php if (!empty($enrolment['role_cat_name'])): ?>
                &bull; <?= esc($enrolment['role_cat_name']) ?>
            <?php endif; ?>
        </div>
    </div>
    <span class="badge badge-light-<?= $statusCls ?> fs-7 ms-auto"><?= esc($statusRaw !== '' ? $statusRaw : '—') ?></span>
</div>

<div class="separator separator-dashed mb-6"></div>

<div class="row g-6">
    <div class="col-md-6">
        <h6 class="fw-bold text-gray-700 mb-3">School</h6>
        <div class="d-flex align-items-center mb-3">
            <div class="symbol symbol-40px me-3">
                <?php if ($hasLogo): ?>
                <img src="<?= $logoUrl ?>" alt="<?= esc($enrolment['sch_name'] ?? '') ?>" />
                <?php else: ?>
                <div class="symbol-label bg-light-primary">
                    <i class="ki-duotone ki-home fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <?php endif; ?>
            </div>
            <div class="fw-semibold text-gray-800"><?= esc($enrolment['sch_name'] ?? '—') ?></div>
        </div>
        <div class="text-muted fs-7 mb-1"><?= esc($enrolment['sch_address'] ?? '') ?></div>
        <div class="text-muted fs-7 mb-1"><?= esc($enrolment['sch_phone'] ?? '') ?></div>
        <div class="text-muted fs-7"><?= esc($enrolment['sch_email'] ?? '') ?></div>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-gray-700 mb-3">Enrolment Info</h6>
        <div class="fs-7 mb-1"><span class="text-muted">Stream / Level:</span>
            <?= esc(trim(($enrolment['stream_name'] ?? '—') . (!empty($enrolment['level_name']) ? ' (' . $enrolment['level_name'] . ')' : ''))) ?>
        </div>
        <div class="fs-7 mb-1"><span class="text-muted">Term:</span> <?= esc($enrolment['enrol_term'] ?? '—') ?></div>
        <div class="fs-7 mb-1"><span class="text-muted">Year:</span> <?= esc($enrolment['enrol_year'] ?? '—') ?></div>
        <div class="fs-7 mb-1"><span class="text-muted">Enrol Date:</span>
            <?= !empty($enrolment['enrol_date']) ? date('d M Y', strtotime($enrolment['enrol_date'])) : '—' ?>
        </div>
        <?php if (!empty($enrolment['enrol_note'])): ?>
        <div class="fs-7"><span class="text-muted">Note:</span> <?= esc($enrolment['enrol_note']) ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<div class="row g-6">
    <div class="col-md-6">
        <h6 class="fw-bold text-gray-700 mb-3">Contact</h6>
        <div class="fs-7 mb-1"><span class="text-muted">Email:</span> <?= esc($enrolment['email'] ?? '—') ?></div>
        <div class="fs-7 mb-1"><span class="text-muted">Phone:</span> <?= esc($enrolment['phone'] ?? '—') ?></div>
        <div class="fs-7"><span class="text-muted">Address:</span> <?= esc($enrolment['address'] ?? '—') ?></div>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-gray-700 mb-3">Admission</h6>
        <div class="fs-7 mb-1"><span class="text-muted">Admission Status:</span> <?= esc($enrolment['admission_status'] ?? '—') ?></div>
        <div class="fs-7"><span class="text-muted">Admission Date:</span>
            <?= !empty($enrolment['admission_date']) ? date('d M Y', strtotime($enrolment['admission_date'])) : '—' ?>
        </div>
    </div>
</div>
