<?php

namespace App\Controllers;

use App\Models\EventModel;

class EventController extends BaseController
{
    // ── index — listing ───────────────────────────────────────────────────────

    public function index()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_event_listing')) {
            return view('app/layouts/main', ['_view' => 'app/auth/access_control']);
        }

        $schId   = $isSuperAdmin ? 0 : (int) $this->session->get('schID');
        $filters = [
            'type'   => $this->request->getGet('type')   ?? '',
            'status' => $this->request->getGet('status') ?? '',
            'from'   => $this->request->getGet('from')   ?? '',
            'to'     => $this->request->getGet('to')     ?? '',
        ];

        $this->setPageData('Events', 'Event', 'Event Listing');
        $data = $this->loadCommonData('app/event/index', [
            'events'      => $this->eventModel->getAll($schId, $filters),
            'stats'       => $this->eventModel->getSummaryStats($schId),
            'filters'     => $filters,
            'canAdd'      => $isSuperAdmin || $this->grant_access('_add_event'),
            'canEdit'     => $isSuperAdmin || $this->grant_access('_edit_event'),
            'canDelete'   => $isSuperAdmin || $this->grant_access('_remove_event'),
            'canDetail'   => $isSuperAdmin || $this->grant_access('_event_detail'),
            'canCalendar' => $isSuperAdmin || $this->grant_access('_event_calendar'),
            'canReport'   => $isSuperAdmin || $this->grant_access('_event_report'),
        ]);
        return view('app/layouts/main', $data);
    }

    // ── add / store ───────────────────────────────────────────────────────────

    public function add()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_add_event')) {
            return view('app/layouts/main', ['_view' => 'app/auth/access_control']);
        }

        $this->setPageData('Add Event', 'Event', 'Add Event');
        $data = $this->loadCommonData('app/event/form', [
            'isEdit' => false,
            'event'  => null,
        ]);
        return view('app/layouts/main', $data);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $schId = (int) $this->session->get('schID');
        $now   = date('Y-m-d H:i:s');

        $title     = trim($this->request->getPost('title') ?? '');
        $startDate = $this->request->getPost('start_date');

        if (!$title || !$startDate) {
            return redirect()->back()->withInput()->with('error', 'Title and start date are required.');
        }

        $row = [
            'sch_id_fk'   => $schId,
            'title'       => $title,
            'description' => trim($this->request->getPost('description') ?? ''),
            'event_type'  => $this->request->getPost('event_type') ?: 'Other',
            'start_date'  => $startDate,
            'end_date'    => $this->request->getPost('end_date')   ?: null,
            'start_time'  => $this->request->getPost('start_time') ?: null,
            'end_time'    => $this->request->getPost('end_time')   ?: null,
            'location'    => trim($this->request->getPost('location')  ?? ''),
            'organizer'   => trim($this->request->getPost('organizer') ?? ''),
            'color'       => $this->request->getPost('color') ?: null,
            'status'      => $this->request->getPost('status') ?: 'Upcoming',
            'created_by'  => (int) $this->session->get('userID'),
            'created_at'  => $now,
            'updated_at'  => $now,
        ];

        $this->eventModel->insert($row);

        $this->userLogModel->insert([
            'user_id_fk'  => $this->session->get('userID'),
            'ip_aadress'  => $this->ipAddress,
            'user_agent'  => $this->userAgent->getAgentString(),
            'user_device' => $this->deviceInfo['device_type'] ?? 'Desktop',
            'log_title'   => 'Event Created',
            'log_desc'    => 'Event "' . $title . '" created for ' . $startDate,
            'log_date'    => date('Y-m-d'),
            'log_time'    => time(),
            'log_icon'    => '<i class="ki-duotone ki-calendar-add fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>',
            'log_theme'   => 'primary',
        ]);

        return redirect()->to('event')->with('success', 'Event "' . $title . '" has been created.');
    }

    // ── edit / update ─────────────────────────────────────────────────────────

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_edit_event')) {
            return view('app/layouts/main', ['_view' => 'app/auth/access_control']);
        }

        $event = $this->eventModel->getOne($id);
        if (!$event) return redirect()->to('event')->with('error', 'Event not found.');

        $this->setPageData('Edit Event', 'Event', 'Edit Event');
        $data = $this->loadCommonData('app/event/form', [
            'isEdit' => true,
            'event'  => $event,
        ]);
        return view('app/layouts/main', $data);
    }

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $event = $this->eventModel->getOne($id);
        if (!$event) return redirect()->to('event')->with('error', 'Event not found.');

        $title     = trim($this->request->getPost('title') ?? '');
        $startDate = $this->request->getPost('start_date');

        if (!$title || !$startDate) {
            return redirect()->back()->withInput()->with('error', 'Title and start date are required.');
        }

        $this->eventModel->update($id, [
            'title'       => $title,
            'description' => trim($this->request->getPost('description') ?? ''),
            'event_type'  => $this->request->getPost('event_type') ?: 'Other',
            'start_date'  => $startDate,
            'end_date'    => $this->request->getPost('end_date')   ?: null,
            'start_time'  => $this->request->getPost('start_time') ?: null,
            'end_time'    => $this->request->getPost('end_time')   ?: null,
            'location'    => trim($this->request->getPost('location')  ?? ''),
            'organizer'   => trim($this->request->getPost('organizer') ?? ''),
            'color'       => $this->request->getPost('color') ?: null,
            'status'      => $this->request->getPost('status'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('event/detail/' . $id)->with('success', 'Event updated successfully.');
    }

    // ── delete (AJAX POST) ────────────────────────────────────────────────────

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['error' => 'Unauthorized']);
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_remove_event')) {
            return $this->response->setJSON(['error' => 'Access denied']);
        }

        $event = $this->eventModel->getOne($id);
        if (!$event) return $this->response->setJSON(['error' => 'Event not found']);

        $this->eventModel->delete($id);
        return $this->response->setJSON(['success' => true]);
    }

    // ── detail ────────────────────────────────────────────────────────────────

    public function detail(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $event = $this->eventModel->getOne($id);
        if (!$event) return redirect()->to('event')->with('error', 'Event not found.');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $this->setPageData('Event Detail', 'Event', esc($event['title']));
        $data = $this->loadCommonData('app/event/detail', [
            'event'     => $event,
            'canEdit'   => $isSuperAdmin || $this->grant_access('_edit_event'),
            'canDelete' => $isSuperAdmin || $this->grant_access('_remove_event'),
        ]);
        return view('app/layouts/main', $data);
    }

    // ── calendar view ─────────────────────────────────────────────────────────

    public function calendar()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_event_calendar')) {
            return view('app/layouts/main', ['_view' => 'app/auth/access_control']);
        }

        $this->setPageData('Event Calendar', 'Event', 'Calendar');
        $data = $this->loadCommonData('app/event/calendar', [
            'canAdd' => $isSuperAdmin || $this->grant_access('_add_event'),
        ]);
        return view('app/layouts/main', $data);
    }

    // ── calendar feed (AJAX) ──────────────────────────────────────────────────

    public function calendarFeed()
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON([]);

        $schId  = (int) $this->session->get('schID');
        $start  = substr($this->request->getGet('start') ?? date('Y-m-01'), 0, 10);
        $end    = substr($this->request->getGet('end')   ?? date('Y-m-t'),  0, 10);

        $events = $this->eventModel->getCalendarEvents($schId, $start, $end);

        $out = array_map(function ($e) {
            $color = $e['color'] ?: EventModel::typeColor($e['event_type']);
            $start = $e['start_date'];
            if ($e['start_time']) $start .= 'T' . $e['start_time'];
            $end   = $e['end_date'] ?: $e['start_date'];
            if ($e['end_time']) $end .= 'T' . $e['end_time'];
            return [
                'id'              => $e['event_id'],
                'title'           => $e['title'],
                'start'           => $start,
                'end'             => $end,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'textColor'       => '#fff',
                'extendedProps'   => [
                    'type'     => $e['event_type'],
                    'location' => $e['location'],
                    'status'   => $e['status'],
                    'detailUrl'=> base_url('event/detail/' . $e['event_id']),
                ],
            ];
        }, $events);

        return $this->response->setJSON($out);
    }

    // ── report ────────────────────────────────────────────────────────────────

    public function report()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_event_report')) {
            return view('app/layouts/main', ['_view' => 'app/auth/access_control']);
        }

        $schId  = $isSuperAdmin ? 0 : (int) $this->session->get('schID');
        $year   = (int) ($this->request->getGet('year') ?? date('Y'));

        $this->setPageData('Event Report', 'Event', 'Report');
        $data = $this->loadCommonData('app/event/report', [
            'events'    => $this->eventModel->getAll($schId, ['from' => "$year-01-01", 'to' => "$year-12-31"]),
            'stats'     => $this->eventModel->getSummaryStats($schId),
            'breakdown' => $this->eventModel->getTypeBreakdown($schId),
            'year'      => $year,
        ]);
        return view('app/layouts/main', $data);
    }
}
