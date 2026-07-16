<?php
$isEdit = $isEdit ?? false;
$event  = $event  ?? null;

$types    = ['Academic','Sports','Cultural','Meeting','Holiday','Examination','Other'];
$statuses = ['Upcoming','Ongoing','Completed','Cancelled'];

$v = fn(string $k, $default = '') => old($k, $event[$k] ?? $default);
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0"><?= $isEdit ? 'Edit Event' : 'Add Event' ?></h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('event') ?>" class="text-muted text-hover-primary">Events</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><?= $isEdit ? 'Edit' : 'Add' ?></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('event') ?>" class="btn btn-sm btn-light fw-bold">
                <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                Back to Events
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?= $this->include('templates/flash_messages') ?>

    <form method="POST" action="<?= $isEdit ? base_url('event/update/' . (int)$event['event_id']) : base_url('event/store') ?>" id="event-form">
        <?= csrf_field() ?>

        <div class="row g-5">

            <!--begin::Left column-->
            <div class="col-xl-8">
                <div class="card card-flush mb-5">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title fw-bold text-gray-800 fs-6">Event Details</h3>
                    </div>
                    <div class="card-body pt-0">

                        <!--Event Title-->
                        <div class="mb-5">
                            <label class="form-label required fw-semibold fs-7">Event Title</label>
                            <input type="text" name="title" class="form-control form-control-sm"
                                   value="<?= esc($v('title')) ?>" placeholder="Enter event title" required>
                        </div>

                        <!--Description-->
                        <div class="mb-5">
                            <label class="form-label fw-semibold fs-7">Description</label>
                            <textarea name="description" class="form-control form-control-sm" rows="4"
                                      placeholder="Describe the event..."><?= esc($v('description')) ?></textarea>
                        </div>

                        <!--Dates row-->
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold fs-7">Start Date</label>
                                <input type="date" name="start_date" class="form-control form-control-sm"
                                       value="<?= esc($v('start_date')) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-7">End Date</label>
                                <input type="date" name="end_date" class="form-control form-control-sm"
                                       value="<?= esc($v('end_date')) ?>">
                            </div>
                        </div>

                        <!--Times row-->
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-7">Start Time</label>
                                <input type="time" name="start_time" class="form-control form-control-sm"
                                       value="<?= esc($v('start_time')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-7">End Time</label>
                                <input type="time" name="end_time" class="form-control form-control-sm"
                                       value="<?= esc($v('end_time')) ?>">
                            </div>
                        </div>

                        <!--Location-->
                        <div class="mb-5">
                            <label class="form-label fw-semibold fs-7">Location</label>
                            <input type="text" name="location" class="form-control form-control-sm"
                                   value="<?= esc($v('location')) ?>" placeholder="e.g. School Hall, Field 2">
                        </div>

                        <!--Organizer-->
                        <div class="mb-0">
                            <label class="form-label fw-semibold fs-7">Organizer</label>
                            <input type="text" name="organizer" class="form-control form-control-sm"
                                   value="<?= esc($v('organizer')) ?>" placeholder="e.g. Mr. Smith, PE Department">
                        </div>

                    </div>
                </div>
            </div>
            <!--end::Left column-->

            <!--begin::Right column-->
            <div class="col-xl-4">

                <!--Type & Status-->
                <div class="card card-flush mb-5">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title fw-bold text-gray-800 fs-6">Classification</h3>
                    </div>
                    <div class="card-body pt-0">

                        <div class="mb-5">
                            <label class="form-label required fw-semibold fs-7">Event Type</label>
                            <select name="event_type" class="form-select form-select-sm" required>
                                <?php foreach ($types as $t): ?>
                                <option value="<?= $t ?>" <?= $v('event_type', 'Other') === $t ? 'selected' : '' ?>>
                                    <?= $t ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold fs-7">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <?php foreach ($statuses as $s): ?>
                                <option value="<?= $s ?>" <?= $v('status', 'Upcoming') === $s ? 'selected' : '' ?>>
                                    <?= $s ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </div>

                <!--Calendar Colour-->
                <div class="card card-flush mb-5">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title fw-bold text-gray-800 fs-6">Calendar Color</h3>
                    </div>
                    <div class="card-body pt-0">
                        <label class="form-label fw-semibold fs-7">Override default type color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color" id="color_picker"
                                   class="form-control form-control-color form-control-sm"
                                   value="<?= esc($v('color', '#3788d8')) ?>"
                                   style="width:48px;height:36px;padding:2px;">
                            <button type="button" class="btn btn-sm btn-light-danger" id="clear-color">
                                Reset
                            </button>
                        </div>
                        <div class="text-muted fs-8 mt-2">Leave default to use the type's preset color.</div>
                    </div>
                </div>

                <!--Actions-->
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary fw-bold flex-grow-1">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        <?= $isEdit ? 'Update Event' : 'Create Event' ?>
                    </button>
                    <a href="<?= base_url('event') ?>" class="btn btn-light fw-bold">Cancel</a>
                </div>

            </div>
            <!--end::Right column-->

        </div>

    </form>

</div>
</div>
<!--end::Content-->

<script>
"use strict";
// Reset color picker to type default
document.getElementById('clear-color').addEventListener('click', function () {
    const colors = {
        Academic:'#3788d8', Sports:'#28a745', Cultural:'#fd7e14',
        Meeting:'#6f42c1', Holiday:'#dc3545', Examination:'#e7a800', Other:'#6c757d'
    };
    const type = document.querySelector('[name="event_type"]').value;
    document.getElementById('color_picker').value = colors[type] || '#3788d8';
});
document.querySelector('[name="event_type"]').addEventListener('change', function () {
    const colors = {
        Academic:'#3788d8', Sports:'#28a745', Cultural:'#fd7e14',
        Meeting:'#6f42c1', Holiday:'#dc3545', Examination:'#e7a800', Other:'#6c757d'
    };
    document.getElementById('color_picker').value = colors[this.value] || '#3788d8';
});
</script>
