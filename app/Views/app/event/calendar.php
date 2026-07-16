<?php
$canAdd = $canAdd ?? false;
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Event Calendar</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('event') ?>" class="text-muted text-hover-primary">Events</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Calendar</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('event') ?>" class="btn btn-sm btn-light fw-bold">
                <i class="ki-duotone ki-abstract-26 fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                List View
            </a>
            <?php if ($canAdd): ?>
            <a href="<?= base_url('event/add') ?>" class="btn btn-sm btn-primary fw-bold">
                <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Add Event
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <div class="row g-5">

        <!--begin::Legend sidebar-->
        <div class="col-xl-3 order-xl-2">
            <div class="card card-flush mb-5">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-800 fs-6">Event Types</h3>
                </div>
                <div class="card-body pt-0">
                    <?php
                    $legendItems = [
                        ['Academic',    '#3788d8'],
                        ['Sports',      '#28a745'],
                        ['Cultural',    '#fd7e14'],
                        ['Meeting',     '#6f42c1'],
                        ['Holiday',     '#dc3545'],
                        ['Examination', '#e7a800'],
                        ['Other',       '#6c757d'],
                    ];
                    ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($legendItems as [$label, $color]): ?>
                        <div class="d-flex align-items-center gap-3">
                            <span class="rounded-1 flex-shrink-0" style="width:14px;height:14px;background:<?= $color ?>;display:inline-block;"></span>
                            <span class="fs-7 text-gray-700"><?= $label ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!--Mini upcoming events-->
            <div class="card card-flush">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-800 fs-6">Upcoming Events</h3>
                </div>
                <div class="card-body pt-0" id="upcoming-sidebar">
                    <div class="text-muted fs-7 text-center py-4">Loading...</div>
                </div>
            </div>
        </div>
        <!--end::Legend sidebar-->

        <!--begin::Calendar-->
        <div class="col-xl-9 order-xl-1">
            <div class="card card-flush">
                <div class="card-body p-5">
                    <div id="event_calendar" style="min-height:620px;"></div>
                </div>
            </div>
        </div>
        <!--end::Calendar-->

    </div>

</div>
</div>
<!--end::Content-->

<!--begin::Event tooltip modal-->
<div class="modal fade" id="event_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-3 px-5" id="event_modal_header" style="border-bottom:3px solid #3788d8;">
                <h5 class="modal-title fw-bold fs-6" id="event_modal_title"></h5>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4 px-5" id="event_modal_body"></div>
            <div class="modal-footer py-2 px-5">
                <a href="#" id="event_modal_link" class="btn btn-sm btn-primary">View Detail</a>
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Event tooltip modal-->

<style>
/* FullCalendar skin to match Metronic */
.fc .fc-toolbar-title       { font-size: 1.1rem; font-weight: 700; color: var(--bs-gray-800, #181c32); }
.fc .fc-button-primary      { background-color: var(--bs-primary, #3E97FF); border-color: var(--bs-primary, #3E97FF); font-size:.8rem; }
.fc .fc-button-primary:hover{ opacity:.9; }
.fc .fc-button-primary:not(:disabled).fc-button-active { background-color:#2884f5; }
.fc .fc-col-header-cell     { font-size:.75rem; font-weight:600; color: var(--bs-gray-600, #7e8299); text-transform:uppercase; padding:.5rem 0; }
.fc .fc-daygrid-day-number  { font-size:.75rem; color: var(--bs-gray-600, #7e8299); padding:.3rem .4rem; }
.fc .fc-day-today .fc-daygrid-day-number { color: var(--bs-primary,#3E97FF); font-weight:700; }
.fc .fc-day-today           { background: rgba(62,151,255,.06) !important; }
.fc .fc-event               { border-radius:4px; border:none; font-size:.72rem; font-weight:600; padding:1px 4px; cursor:pointer; }
.fc .fc-daygrid-event-harness { margin-bottom:1px; }
</style>

<script>
"use strict";
$(function () {
    const calendarEl = document.getElementById('event_calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView:  'dayGridMonth',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listMonth',
        },
        height:       'auto',
        events: {
            url: '<?= base_url('event/calendar/feed') ?>',
            method: 'GET',
            failure: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Could not load events.' });
            },
        },
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            const p = info.event.extendedProps;
            const modal = new bootstrap.Modal(document.getElementById('event_modal'));
            document.getElementById('event_modal_title').textContent = info.event.title;
            document.getElementById('event_modal_header').style.borderBottomColor = info.event.backgroundColor;
            document.getElementById('event_modal_body').innerHTML =
                '<div class="d-flex flex-column gap-2 fs-7">' +
                (p.location ? '<div><i class="ki-duotone ki-geolocation fs-5 me-1 text-warning"><span class="path1"></span><span class="path2"></span></i>' + escHtml(p.location) + '</div>' : '') +
                '<div><i class="ki-duotone ki-badge fs-5 me-1 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' + escHtml(p.type) + '</div>' +
                '<div><i class="ki-duotone ki-check-circle fs-5 me-1 text-success"><span class="path1"></span><span class="path2"></span></i>' + escHtml(p.status) + '</div>' +
                '</div>';
            document.getElementById('event_modal_link').href = p.detailUrl;
            modal.show();
        },
        datesSet: function (info) {
            loadUpcoming(info.start, info.end);
        },
    });

    calendar.render();

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function loadUpcoming(start, end) {
        const s = start.toISOString().substring(0,10);
        const e = end.toISOString().substring(0,10);
        $.get('<?= base_url('event/calendar/feed') ?>', { start: s, end: e }, function (events) {
            const now  = new Date();
            const soon = events
                .filter(ev => new Date(ev.start) >= now)
                .sort((a, b) => new Date(a.start) - new Date(b.start))
                .slice(0, 6);
            const el = document.getElementById('upcoming-sidebar');
            if (!soon.length) {
                el.innerHTML = '<div class="text-muted fs-7 text-center py-2">No upcoming events</div>';
                return;
            }
            el.innerHTML = soon.map(ev => {
                const d = new Date(ev.start);
                const ds = d.toLocaleDateString('en-FJ', {day:'2-digit', month:'short'});
                return '<a href="' + escHtml(ev.extendedProps.detailUrl) + '" class="d-flex align-items-center gap-3 py-2 border-bottom text-decoration-none text-reset">' +
                    '<span class="rounded-2 text-center flex-shrink-0 fw-bold fs-8 text-white" style="width:36px;height:36px;line-height:36px;background:' + escHtml(ev.backgroundColor) + '">' + ds + '</span>' +
                    '<span class="fs-7 text-gray-800 text-hover-primary fw-semibold">' + escHtml(ev.title) + '</span>' +
                    '</a>';
            }).join('');
        });
    }
});
</script>
