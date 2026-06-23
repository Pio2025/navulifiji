<!--begin::Student Card-->
<div class="card shadow-sm mb-5" style="border:1px solid #E4E6EF; border-radius:4px;">
    <div class="card-body text-center p-6">
        <div class="symbol symbol-70px symbol-circle mb-4">
            <?php if (!empty($user['profile_photo'])): ?>
                <img src="<?= base_url('uploads/profilePhoto/' . $user['profile_photo']) ?>"
                     alt="<?= esc($user['fname']) ?>" />
            <?php else: ?>
                <div class="symbol-label fs-2 fw-bold bg-light-primary text-primary">
                    <?= strtoupper(substr($user['fname'], 0, 1) . substr($user['lname'], 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>
        <h5 class="fw-bold text-gray-900 mb-1"><?= esc($user['fname'] . ' ' . $user['lname']) ?></h5>
        <?php if (!empty($user['email'])): ?>
            <span class="text-muted fs-7 d-block"><?= esc($user['email']) ?></span>
        <?php endif; ?>
        <?php if (!empty($user['gender'])): ?>
            <span class="badge badge-light-primary mt-2"><?= esc($user['gender']) ?></span>
        <?php endif; ?>
    </div>
</div>
<!--end::Student Card-->

<?php if (!empty($existing)): ?>
<!--begin::Existing Notice-->
<div class="card shadow-sm mb-5" style="border:1px solid #FFC700; border-radius:4px;">
    <div class="card-body p-5">
        <div class="d-flex align-items-center gap-3 mb-3">
            <i class="ki-duotone ki-information fs-2tx text-warning">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <div>
                <h6 class="fw-bold text-gray-900 mb-0">Existing Reference</h6>
                <span class="text-muted fs-8">
                    Generated on <?= date('d M Y', strtotime($existing['gen_ref_date'])) ?>
                </span>
            </div>
        </div>
        <a href="<?= base_url('reference/view/' . $existing['gen_ref_id']) ?>"
           target="_blank"
           class="btn btn-sm btn-light-warning w-100">
            <i class="ki-duotone ki-eye fs-4 me-1">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            View Current PDF
        </a>
    </div>
</div>
<!--end::Existing Notice-->
<?php endif; ?>

<!--begin::Tips Card-->
<div class="card shadow-sm" style="border:1px solid #E4E6EF; border-radius:4px;">
    <div class="card-header border-0 pt-5 pb-0">
        <h6 class="card-title fw-bold text-gray-800 fs-7 mb-0">
            <i class="ki-duotone ki-information-5 fs-4 text-primary me-1">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            Tips
        </h6>
    </div>
    <div class="card-body pt-3">
        <ul class="text-muted fs-8 ps-4 mb-0">
            <?php foreach ($formConfig['tips'] ?? [] as $tip): ?>
                <li class="mb-1"><?= $tip ?></li>
            <?php endforeach; ?>
            <li class="mb-1">Generating a new document will mark the previous one as <strong>Outdated</strong>.</li>
            <li>All generated documents are saved and viewable from the student's profile.</li>
        </ul>
    </div>
</div>
<!--end::Tips Card-->