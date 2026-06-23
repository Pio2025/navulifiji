<?php
// $c = classroom row array, $mode = 'student' | 'parent'
$statusClass = $c['class_status'] === 'Active' ? 'badge-light-success' : 'badge-light-danger';
$enrolClass  = match($c['enrol_status'] ?? '') {
    'Active'    => 'badge-light-success',
    'Graduated' => 'badge-light-primary',
    'Withdrawn' => 'badge-light-danger',
    default     => 'badge-light-warning',
};
?>
<div class="card card-flush h-100 border border-dashed border-gray-300 hover-elevate-up">
    <div class="card-header pt-5 pb-0">
        <div class="card-title flex-column">
            <span class="fs-5 fw-bold text-gray-800 mb-1"><?= esc($c['class_name']) ?></span>
            <span class="fs-7 text-muted"><?= esc($c['stream_name']) ?> &middot; <?= esc($c['level_name']) ?></span>
        </div>
        <div class="card-toolbar">
            <span class="badge <?= $statusClass ?>"><?= esc($c['class_status']) ?></span>
        </div>
    </div>
    <div class="card-body pt-3">
        <!--begin::School-->
        <div class="d-flex align-items-center mb-4">
            <?php if (!empty($c['sch_logo'])): ?>
                <img src="<?= base_url('uploads/schoolLogo/' . esc($c['sch_logo'])) ?>"
                     alt="logo" class="h-25px me-2 rounded" />
            <?php else: ?>
                <i class="ki-duotone ki-home fs-4 text-primary me-2">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            <?php endif; ?>
            <span class="text-gray-700 fw-semibold fs-7"><?= esc($c['sch_name']) ?></span>
        </div>
        <!--end::School-->

        <!--begin::Stats-->
        <div class="d-flex flex-wrap gap-3 mb-4">
            <!--Students-->
            <div class="d-flex align-items-center gap-1">
                <i class="ki-duotone ki-people fs-5 text-primary">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span>
                    <span class="path5"></span>
                </i>
                <span class="fw-bold text-gray-800"><?= (int)($c['student_count'] ?? 0) ?></span>
                <span class="text-muted fs-8">students</span>
            </div>
            <!--Year-->
            <div class="d-flex align-items-center gap-1">
                <i class="ki-duotone ki-calendar fs-5 text-info">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="fw-bold text-gray-800"><?= esc($c['class_year']) ?></span>
            </div>
        </div>
        <!--end::Stats-->

        <!--begin::Separator-->
        <div class="separator separator-dashed mb-3"></div>
        <!--end::Separator-->

        <!--begin::Personnel-->
        <div class="mb-3">
            <div class="d-flex align-items-center mb-2">
                <i class="ki-duotone ki-teacher fs-5 text-success me-2">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="text-muted fs-8 me-2">Class Teacher:</span>
                <span class="fw-semibold fs-7 text-gray-800">
                    <?= !empty($c['class_teacher']) ? esc($c['class_teacher']) : '<span class="text-muted fs-8">Not assigned</span>' ?>
                </span>
            </div>
            <div class="d-flex align-items-center">
                <i class="ki-duotone ki-award fs-5 text-warning me-2">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <span class="text-muted fs-8 me-2">Class Captain:</span>
                <span class="fw-semibold fs-7 text-gray-800">
                    <?= !empty($c['class_captain']) ? esc($c['class_captain']) : '<span class="text-muted fs-8">Not assigned</span>' ?>
                </span>
            </div>
        </div>
        <!--end::Personnel-->

        <!--begin::Enrolment status-->
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted fs-8 me-1">Enrolment:</span>
                <span class="badge badge-sm <?= $enrolClass ?>"><?= esc($c['enrol_status'] ?? 'Unknown') ?></span>
            </div>
            <a href="<?= base_url('classroom/detail/' . $c['class_id']) ?>"
               class="btn btn-sm btn-light-primary">
                View
                <i class="ki-duotone ki-arrow-right fs-5 ms-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </a>
        </div>
        <!--end::Enrolment status-->
    </div>
</div>
