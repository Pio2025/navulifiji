<?php
$event     = $event     ?? [];
$canEdit   = $canEdit   ?? false;
$canDelete = $canDelete ?? false;

use App\Models\EventModel;

$typeColor   = EventModel::typeColor($event['event_type']);
$typeBadge   = EventModel::typeBadge($event['event_type']);
$statusBadge = EventModel::statusBadge($event['status']);
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Event Detail</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('event') ?>" class="text-muted text-hover-primary">Events</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= esc($event['title']) ?></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('event') ?>" class="btn btn-sm btn-light fw-bold">
                <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Back
            </a>
            <?php if ($canEdit): ?>
            <a href="<?= base_url('event/edit/' . (int)$event['event_id']) ?>" class="btn btn-sm btn-warning fw-bold">
                <i class="ki-duotone ki-pencil fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Edit
            </a>
            <?php endif; ?>
            <?php if ($canDelete): ?>
            <button class="btn btn-sm btn-danger fw-bold" id="del-btn"
                    data-id="<?= (int)$event['event_id'] ?>"
                    data-title="<?= esc($event['title']) ?>">
                <i class="ki-duotone ki-trash fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                Delete
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <div class="row g-5">

        <!--begin::Main info-->
        <div class="col-xl-8">
            <div class="card card-flush">
                <!--Color stripe-->
                <div style="height:5px;background:<?= esc($event['color'] ?: $typeColor) ?>;border-radius:0.625rem 0.625rem 0 0;"></div>
                <div class="card-body pt-6">

                    <div class="d-flex align-items-start justify-content-between mb-4 gap-3 flex-wrap">
                        <div>
                            <h2 class="fw-bold text-gray-800 fs-3 mb-1"><?= esc($event['title']) ?></h2>
                            <?php if ($event['organizer']): ?>
                            <div class="text-muted fs-7">Organized by <strong><?= esc($event['organizer']) ?></strong></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex gap-2 flex-shrink-0">
                            <span class="badge <?= $typeBadge ?> fs-7"><?= esc($event['event_type']) ?></span>
                            <span class="badge <?= $statusBadge ?> fs-7"><?= esc($event['status']) ?></span>
                        </div>
                    </div>

                    <!--Details grid-->
                    <div class="row g-4 mb-6">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-2">
                                <i class="ki-duotone ki-calendar-tick fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                <div>
                                    <div class="fs-8 text-muted">Start</div>
                                    <div class="fw-semibold fs-7"><?= esc(date('l, d M Y', strtotime($event['start_date']))) ?></div>
                                    <?php if ($event['start_time']): ?>
                                    <div class="text-muted fs-8"><?= esc(date('g:i A', strtotime($event['start_time']))) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($event['end_date']): ?>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-2">
                                <i class="ki-duotone ki-calendar-8 fs-2 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                <div>
                                    <div class="fs-8 text-muted">End</div>
                                    <div class="fw-semibold fs-7"><?= esc(date('l, d M Y', strtotime($event['end_date']))) ?></div>
                                    <?php if ($event['end_time']): ?>
                                    <div class="text-muted fs-8"><?= esc(date('g:i A', strtotime($event['end_time']))) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($event['location']): ?>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-2">
                                <i class="ki-duotone ki-geolocation fs-2 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                <div>
                                    <div class="fs-8 text-muted">Location</div>
                                    <div class="fw-semibold fs-7"><?= esc($event['location']) ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($event['creator_name']): ?>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-2">
                                <i class="ki-duotone ki-profile-user fs-2 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                <div>
                                    <div class="fs-8 text-muted">Created By</div>
                                    <div class="fw-semibold fs-7"><?= esc($event['creator_name']) ?></div>
                                    <div class="text-muted fs-8"><?= esc(date('d M Y', strtotime($event['created_at']))) ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($event['description']): ?>
                    <div class="separator my-4"></div>
                    <h4 class="fw-semibold text-gray-700 fs-7 mb-3">Description</h4>
                    <div class="text-gray-600 fs-7 lh-lg" style="white-space:pre-line;"><?= esc($event['description']) ?></div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <!--end::Main info-->

        <!--begin::Sidebar-->
        <div class="col-xl-4">

            <!--Quick info card-->
            <div class="card card-flush mb-5">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-800 fs-6">Event Summary</h3>
                </div>
                <div class="card-body pt-0">
                    <?php
                    $startDate = new DateTime($event['start_date']);
                    $today     = new DateTime(date('Y-m-d'));
                    $endDate   = $event['end_date'] ? new DateTime($event['end_date']) : $startDate;

                    if ($today < $startDate) {
                        $diff = $today->diff($startDate);
                        $countdown = 'In ' . $diff->days . ' day' . ($diff->days === 1 ? '' : 's');
                        $cdClass   = 'text-primary';
                    } elseif ($today <= $endDate) {
                        $countdown = 'Happening now';
                        $cdClass   = 'text-success';
                    } else {
                        $diff = $endDate->diff($today);
                        $countdown = $diff->days . ' day' . ($diff->days === 1 ? '' : 's') . ' ago';
                        $cdClass   = 'text-muted';
                    }

                    $duration = $startDate->diff($endDate);
                    $durText  = $duration->days === 0 ? 'Single day' : ($duration->days + 1) . ' days';
                    ?>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted fs-7">Countdown</span>
                            <span class="fw-semibold fs-7 <?= $cdClass ?>"><?= $countdown ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted fs-7">Duration</span>
                            <span class="fw-semibold fs-7"><?= $durText ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted fs-7">Type</span>
                            <span class="badge <?= $typeBadge ?>"><?= esc($event['event_type']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted fs-7">Status</span>
                            <span class="badge <?= $statusBadge ?>"><?= esc($event['status']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!--Color swatch-->
            <div class="card card-flush">
                <div class="card-body py-4 px-5 d-flex align-items-center gap-3">
                    <span class="rounded-2 d-inline-block flex-shrink-0"
                          style="width:36px;height:36px;background:<?= esc($event['color'] ?: $typeColor) ?>;"></span>
                    <div>
                        <div class="fw-semibold fs-7">Calendar Color</div>
                        <div class="text-muted fs-8"><?= esc($event['color'] ?: $typeColor . ' (type default)') ?></div>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Sidebar-->

    </div>

</div>
</div>
<!--end::Content-->

<script>
"use strict";
document.getElementById('del-btn') && document.getElementById('del-btn').addEventListener('click', function () {
    const id    = this.dataset.id;
    const title = this.dataset.title;
    Swal.fire({
        icon: 'warning',
        title: 'Delete Event?',
        html: 'This will permanently delete <strong>' + title + '</strong>.',
        showCancelButton: true,
        confirmButtonColor: '#f1416c',
        confirmButtonText: 'Delete',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post('<?= base_url('event/remove/') ?>' + id, { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
            .done(d => {
                if (d.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted', text: 'Event removed.', timer: 1500, showConfirmButton: false });
                    setTimeout(() => window.location.href = '<?= base_url('event') ?>', 1600);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: d.error || 'Failed.' });
                }
            });
    });
});
</script>
