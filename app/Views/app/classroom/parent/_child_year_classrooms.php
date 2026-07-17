<?php if (empty($byStudent)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-16 text-muted">
        <i class="ki-duotone ki-element-7 fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-6 fw-semibold text-gray-600 mb-2">No Classrooms Found</div>
        <div class="fs-8">No classrooms found for your children in this year.</div>
    </div>
</div>
<?php else: ?>

<!-- Flat 3-column grid — all children's classrooms together -->
<div class="row g-6">
    <?php foreach ($byStudent as $child):
        $initials   = strtoupper(substr($child['student_fname'], 0, 1) . substr($child['student_lname'], 0, 1));
        $childPhoto = $child['student_photo'] ?? null;
        $childPhotoUrl = ($childPhoto && file_exists(FCPATH . 'uploads/profilePhoto/' . $childPhoto))
                         ? base_url('uploads/profilePhoto/' . $childPhoto)
                         : null;
    ?>
    <?php foreach ($child['classrooms'] as $c): ?>
    <div class="col-12 col-sm-6 col-lg-4">
        <!-- Child name label above each card -->
        <div class="d-flex align-items-center gap-2 mb-2">
            <?php if ($childPhotoUrl): ?>
            <img src="<?= esc($childPhotoUrl) ?>" alt=""
                 style="width:24px;height:24px;border-radius:50%;object-fit:cover;flex-shrink:0;border:1px solid #e9edf0;">
            <?php else: ?>
            <div style="width:24px;height:24px;border-radius:50%;background:#e8f3ff;color:#0095e8;display:flex;align-items:center;justify-content:center;font-size:.62rem;font-weight:700;flex-shrink:0;">
                <?= esc($initials) ?>
            </div>
            <?php endif; ?>
            <span class="text-gray-600 fs-7 fw-semibold"><?= esc($child['student_fname'] . ' ' . $child['student_lname']) ?></span>
            <?php if (!empty($child['relationship'])): ?>
            <span class="text-muted fs-8">&middot; <?= esc($child['relationship']) ?></span>
            <?php endif; ?>
        </div>
        <?php $cardViewUrl = 'classroom/child/view/' . $c['class_id']; ?>
        <?php include(APPPATH . 'Views/app/classroom/_my_card.php'); ?>
    </div>
    <?php endforeach; ?>
    <?php endforeach; ?>
</div>

<?php endif; ?>
