<?php
$photoFile = !empty($admission['profile_photo']) ? trim($admission['profile_photo']) : '';
$photoPath = FCPATH . 'uploads/profilePhoto/' . $photoFile;
$hasPhoto  = $photoFile && file_exists($photoPath);
$photoUrl  = $hasPhoto ? base_url('uploads/profilePhoto/' . $photoFile) : '';
$initials  = strtoupper(substr($admission['fname'] ?? '', 0, 1) . substr($admission['lname'] ?? '', 0, 1));

$logoFile = !empty($admission['sch_logo']) ? trim($admission['sch_logo']) : '';
$logoPath = FCPATH . 'uploads/school/logo/' . $logoFile;
$hasLogo  = $logoFile && file_exists($logoPath);
$logoUrl  = $hasLogo ? base_url('uploads/school/logo/' . $logoFile) : '';

$statusRaw = $admission['admission_status'] ?? '';
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
        <img src="<?= $photoUrl ?>" alt="<?= esc($admission['fname'] ?? '') ?>" />
        <?php else: ?>
        <div class="symbol-label fs-3 fw-bold bg-light-primary text-primary"><?= esc($initials) ?></div>
        <?php endif; ?>
    </div>
    <div>
        <div class="fw-bold fs-4 text-gray-800">
            <?= esc(trim(($admission['fname'] ?? '') . ' ' . ($admission['oname'] ?? '') . ' ' . ($admission['lname'] ?? ''))) ?>
        </div>
        <div class="text-muted fs-7">
            <?= esc($admission['role_name'] ?? '') ?>
            <?php if (!empty($admission['role_cat_name'])): ?>
                &bull; <?= esc($admission['role_cat_name']) ?>
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
                <img src="<?= $logoUrl ?>" alt="<?= esc($admission['sch_name'] ?? '') ?>" />
                <?php else: ?>
                <div class="symbol-label bg-light-primary">
                    <i class="ki-duotone ki-home fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <?php endif; ?>
            </div>
            <div class="fw-semibold text-gray-800"><?= esc($admission['sch_name'] ?? '—') ?></div>
        </div>
        <div class="text-muted fs-7 mb-1"><?= esc($admission['sch_address'] ?? '') ?></div>
        <div class="text-muted fs-7 mb-1"><?= esc($admission['sch_phone'] ?? '') ?></div>
        <div class="text-muted fs-7"><?= esc($admission['sch_email'] ?? '') ?></div>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-gray-700 mb-3">Admission Info</h6>
        <div class="fs-7 mb-1"><span class="text-muted">Date:</span>
            <?= !empty($admission['admission_date']) ? date('d M Y', strtotime($admission['admission_date'])) : '—' ?>
        </div>
        <div class="fs-7 mb-1"><span class="text-muted">Time:</span> <?= esc($admission['admission_time'] ?? '—') ?></div>
        <?php if (!empty($admission['admission_note'])): ?>
        <div class="fs-7"><span class="text-muted">Note:</span> <?= esc($admission['admission_note']) ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="separator separator-dashed my-6"></div>

<div class="row g-6">
    <div class="col-md-6">
        <h6 class="fw-bold text-gray-700 mb-3">Contact</h6>
        <div class="fs-7 mb-1"><span class="text-muted">Email:</span> <?= esc($admission['email'] ?? '—') ?></div>
        <div class="fs-7 mb-1"><span class="text-muted">Phone:</span> <?= esc($admission['phone'] ?? '—') ?></div>
        <div class="fs-7"><span class="text-muted">Address:</span> <?= esc($admission['address'] ?? '—') ?></div>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-gray-700 mb-3">Personal</h6>
        <div class="fs-7 mb-1"><span class="text-muted">Gender:</span> <?= esc($admission['gender'] ?? '—') ?></div>
        <div class="fs-7"><span class="text-muted">Date of Birth:</span>
            <?= !empty($admission['dob']) ? date('d M Y', strtotime($admission['dob'])) : '—' ?>
        </div>
    </div>
</div>
