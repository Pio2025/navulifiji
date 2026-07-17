<?php if (empty($byStudent)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-16 text-muted">
        <i class="ki-duotone ki-element-7 fs-4x text-gray-200 mb-4"><span class="path1"></span><span class="path2"></span></i>
        <div class="fs-6 fw-semibold text-gray-600 mb-2">No Classrooms Found</div>
        <div class="fs-8">No classrooms found for your children in this year.</div>
    </div>
</div>
<?php else: ?>
<?php foreach ($byStudent as $child): ?>
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
            <div class="fw-bold fs-4 text-gray-800"><?= esc($child['student_fname'] . ' ' . $child['student_lname']) ?></div>
            <div class="text-muted fs-7"><?= esc($child['relationship']) ?></div>
        </div>
    </div>
    <!--end::Child header-->

    <!--begin::Classroom cards-->
    <div class="row g-6">
        <?php foreach ($child['classrooms'] as $c): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <?php $cardViewUrl = 'classroom/child/view/' . $c['class_id']; ?>
            <?php include(APPPATH . 'Views/app/classroom/_my_card.php'); ?>
        </div>
        <?php endforeach; ?>
    </div>
    <!--end::Classroom cards-->
</div>
<?php endforeach; ?>
<?php endif; ?>
