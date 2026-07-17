<?php

namespace App\Controllers;

use App\Models\TimetableModel;
use App\Models\TimetableEntryModel;
use App\Models\TimetableTemplateModel;
use App\Models\TimetableTemplateSlotModel;

class TimetableController extends BaseController
{
    private TimetableModel             $ttModel;
    private TimetableEntryModel        $ttEntryModel;
    private TimetableTemplateModel     $ttTemplateModel;
    private TimetableTemplateSlotModel $ttSlotModel;

    private function boot(): void
    {
        $this->ttModel         = new TimetableModel();
        $this->ttEntryModel    = new TimetableEntryModel();
        $this->ttTemplateModel = new TimetableTemplateModel();
        $this->ttSlotModel     = new TimetableTemplateSlotModel();
    }

    // =========================================================================
    // LISTING
    // =========================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Timetable', 'Timetable', 'Timetable List');

        if (!$this->require_access('_timetable_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = (int) $this->session->get('schID');

        $timetables = $isSuperAdmin
            ? $this->ttModel->getAllWithDetails()
            : $this->ttModel->getBySchool($schId);

        $data['timetables']   = $timetables;
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['canAdd']       = $isSuperAdmin || $this->grant_access('_add_timetable');
        $data['canEdit']      = $isSuperAdmin || $this->grant_access('_edit_timetable');
        $data['canDelete']    = $isSuperAdmin || $this->grant_access('_remove_timetable');
        $data['_view']        = 'app/timetable/index';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // ADD (step 1 — header form; edit page is the grid editor)
    // =========================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Add Timetable', 'Timetable', 'Add Timetable');

        if (!$this->require_access('_add_timetable')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = (int) $this->session->get('schID');

        $data['streams']      = $isSuperAdmin
            ? $this->schoolStreamModel->getAllStream()
            : $this->schoolStreamModel->getAllStreamsBySchool($schId);
        $data['templates']    = $this->ttTemplateModel->getAll();
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['currentYear']  = (int) date('Y');
        $data['_view']        = 'app/timetable/add';
        return view('app/layouts/main', $data);
    }

    public function store()
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();

        if (!$this->require_access('_add_timetable')) {
            return redirect()->to('timetable')->with('error', 'Access denied.');
        }

        if (!$this->validate([
            'stream_id_fk'   => 'required|integer',
            'template_id_fk' => 'required|integer',
            'academic_year'  => 'required|integer|min_length[4]|max_length[4]',
            'term'           => 'required|integer|in_list[1,2,3]',
        ])) {
            return redirect()->back()->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        $schId        = (int) $this->session->get('schID');
        $streamId     = (int) $this->request->getPost('stream_id_fk');
        $templateId   = (int) $this->request->getPost('template_id_fk');
        $year         = (int) $this->request->getPost('academic_year');
        $term         = (int) $this->request->getPost('term');
        $startDate    = $this->request->getPost('rotation_start_date') ?: null;
        $startDay     = (int) ($this->request->getPost('rotation_start_day') ?: 1);
        $status       = $this->request->getPost('timetable_status') ?: 'Draft';

        // Derive school from stream when super admin
        if ($isSuperAdmin || $schId === 0) {
            $schId = $this->schIdFromStream($streamId);
        }

        $id = $this->ttModel->insert([
            'sch_id_fk'           => $schId,
            'stream_id_fk'        => $streamId,
            'template_id_fk'      => $templateId,
            'academic_year'       => $year,
            'term'                => $term,
            'rotation_start_date' => $startDate,
            'rotation_start_day'  => $startDay,
            'timetable_status'    => $status,
            'created_by'          => (int) $this->session->get('userID'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        if (!$id) {
            return redirect()->back()->withInput()->with('error', 'Failed to create timetable. A timetable for this stream/year/term may already exist.');
        }

        $this->logAction('Created timetable #' . $id);

        return redirect()->to('timetable/edit/' . $id)
            ->with('success', 'Timetable created — fill in the periods below and save.');
    }

    // =========================================================================
    // EDIT (grid editor)
    // =========================================================================

    public function edit(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Edit Timetable', 'Timetable', 'Edit Timetable');

        if (!$this->require_access('_edit_timetable')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $slots      = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $entryMap   = $this->ttEntryModel->getMappedByTimetable($id);
        $subjects   = $this->streamSubjects((int) $tt['stream_id_fk']);
        $teacherMap = $this->subjectTeacherMap($subjects);

        $data['tt']         = $tt;
        $data['slots']      = $slots;
        $data['entryMap']   = $entryMap;
        $data['subjects']   = $subjects;
        $data['teacherMap'] = $teacherMap;
        $data['days']       = range(1, 6);
        $data['templates']  = $this->ttTemplateModel->getAll();
        $data['_view']      = 'app/timetable/edit';
        return view('app/layouts/main', $data);
    }

    public function update(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();

        if (!$this->require_access('_edit_timetable')) {
            return redirect()->to('timetable')->with('error', 'Access denied.');
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $this->ttModel->update($id, [
            'rotation_start_date' => $this->request->getPost('rotation_start_date') ?: null,
            'rotation_start_day'  => (int) ($this->request->getPost('rotation_start_day') ?: 1),
            'timetable_status'    => $this->request->getPost('timetable_status') ?: 'Draft',
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        // Collect posted entries
        $rawEntries = $this->request->getPost('entries') ?? [];
        $slots      = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $entries    = [];

        foreach (range(1, 6) as $day) {
            foreach ($slots as $slot) {
                if (!(int) $slot['is_teaching']) continue;
                $slotId    = (int) $slot['slot_id'];
                $subId     = (int) ($rawEntries[$day][$slotId]['sch_sub_id_fk'] ?? 0);
                $teacherId = (int) ($rawEntries[$day][$slotId]['teacher_id_fk']  ?? 0);
                $room      = trim($rawEntries[$day][$slotId]['room'] ?? '');

                if ($subId > 0 || $teacherId > 0) {
                    $entries[] = [
                        'day_number'    => $day,
                        'slot_id_fk'    => $slotId,
                        'sch_sub_id_fk' => $subId,
                        'teacher_id_fk' => $teacherId,
                        'room'          => $room,
                        'notes'         => '',
                    ];
                }
            }
        }

        $this->ttEntryModel->replaceEntries($id, $entries);
        $this->logAction('Updated timetable #' . $id);

        return redirect()->to('timetable/detail/' . $id)
            ->with('success', 'Timetable saved successfully.');
    }

    // =========================================================================
    // DETAIL (view-only grid + this-week rotation indicator)
    // =========================================================================

    public function detail(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Timetable', 'Timetable', 'View Timetable');

        if (!$this->require_access('_timetable_detail')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $slots    = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $entryMap = $this->ttEntryModel->getMappedByTimetable($id);
        $weekMap  = [];

        if (!empty($tt['rotation_start_date'])) {
            $weekMap = $this->ttModel->getWeekDayMap(
                $tt['rotation_start_date'],
                (int) $tt['rotation_start_day']
            );
        }

        $data['tt']       = $tt;
        $data['slots']    = $slots;
        $data['entryMap'] = $entryMap;
        $data['weekMap']  = $weekMap;
        $data['days']     = range(1, 6);
        $data['canEdit']  = (int) $this->session->get('roleID') === 1
                            || $this->grant_access('_edit_timetable');
        $data['_view']    = 'app/timetable/detail';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    public function delete(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();

        if (!$this->require_access('_remove_timetable')) {
            return redirect()->to('timetable')->with('error', 'Access denied.');
        }

        $this->ttEntryModel->where('timetable_id_fk', $id)->delete();
        $this->ttModel->delete($id);
        $this->logAction('Deleted timetable #' . $id);

        return redirect()->to('timetable')->with('success', 'Timetable deleted.');
    }

    // =========================================================================
    // REPORT (printable)
    // =========================================================================

    public function report(int $id)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');
        $this->boot();
        $this->setPageData('Timetable Report', 'Timetable', 'Timetable Report');

        if (!$this->require_access('_timetable_report')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $tt = $this->ttModel->getDetail($id);
        if (!$tt) return redirect()->to('timetable')->with('error', 'Timetable not found.');

        $data['tt']       = $tt;
        $data['slots']    = $this->ttSlotModel->getByTemplate((int) $tt['template_id_fk']);
        $data['entryMap'] = $this->ttEntryModel->getMappedByTimetable($id);
        $data['days']     = range(1, 6);
        $data['_view']    = 'app/timetable/report';
        return view('app/layouts/main', $data);
    }

    // =========================================================================
    // AJAX helpers
    // =========================================================================

    /**
     * Returns template + slots + subjects + teacherMap for a stream.
     * Called when the user picks a stream on the add form.
     */
    public function streamInfo(int $streamId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        $this->boot();

        $stream = $this->schoolStreamModel->getStream($streamId);
        if (!$stream) return $this->response->setJSON(['error' => 'Stream not found'])->setStatusCode(404);

        $db     = \Config\Database::connect();
        $level  = $db->table('sch_level')->where('sch_level_id', $stream['sch_level_id_fk'])->get()->getRowArray();
        $schId  = $level ? (int) $level['sch_id_fk'] : 0;
        $school = $schId ? $db->table('school')->where('sch_id', $schId)->get()->getRowArray() : null;
        $catId  = $school ? (int) $school['sch_cat_id_fk'] : 0;

        $template = $this->ttTemplateModel->getDefaultForCategory($catId, $schId);
        $slots    = $template ? $this->ttSlotModel->getByTemplate((int) $template['template_id']) : [];
        $subjects = $this->streamSubjects($streamId);

        return $this->response->setJSON([
            'template'   => $template,
            'slots'      => $slots,
            'subjects'   => $subjects,
            'teacherMap' => $this->subjectTeacherMap($subjects),
        ]);
    }

    /**
     * Returns teachers assigned to a given sch_sub_id.
     */
    public function subjectTeachers(int $schSubId)
    {
        if (!$this->isLoggedIn()) return $this->response->setJSON([])->setStatusCode(401);

        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT u.user_id, u.fname, u.lname
            FROM   admission_teaching_subject ats
            INNER  JOIN admission a ON a.admission_id = ats.admission_id_fk
            INNER  JOIN users u    ON u.user_id = a.user_id_fk
            WHERE  ats.sch_sub_id_fk = ?
              AND  ats.adm_teach_sub_status = 'Active'
            ORDER  BY u.fname, u.lname
        ", [$schSubId])->getResultArray();

        return $this->response->setJSON($rows);
    }

    // =========================================================================
    // Private helpers
    // =========================================================================

    private function streamSubjects(int $streamId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT ss.sch_sub_id, sub.subject_name, 'Core' AS subject_type
            FROM   stream_core_subject scs
            INNER  JOIN sch_subject ss ON ss.sch_sub_id = scs.sch_sub_id_fk
            INNER  JOIN subject sub    ON sub.subject_id = ss.subject_id_fk
            WHERE  scs.stream_id_fk = ?
            UNION
            SELECT ss.sch_sub_id, sub.subject_name, 'Optional' AS subject_type
            FROM   stream_optional_subject sos
            INNER  JOIN sch_subject ss ON ss.sch_sub_id = sos.sch_sub_id_fk
            INNER  JOIN subject sub    ON sub.subject_id = ss.subject_id_fk
            WHERE  sos.stream_id_fk = ?
            ORDER  BY subject_type, subject_name
        ", [$streamId, $streamId])->getResultArray();
    }

    private function subjectTeacherMap(array $subjects): array
    {
        if (empty($subjects)) return [];

        $db  = \Config\Database::connect();
        $ids = implode(',', array_map('intval', array_column($subjects, 'sch_sub_id')));

        $rows = $db->query("
            SELECT ats.sch_sub_id_fk, u.user_id, u.fname, u.lname
            FROM   admission_teaching_subject ats
            INNER  JOIN admission a ON a.admission_id = ats.admission_id_fk
            INNER  JOIN users u    ON u.user_id = a.user_id_fk
            WHERE  ats.sch_sub_id_fk IN ({$ids})
              AND  ats.adm_teach_sub_status = 'Active'
            ORDER  BY u.fname, u.lname
        ")->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['sch_sub_id_fk']][] = $row;
        }
        return $map;
    }

    private function schIdFromStream(int $streamId): int
    {
        $db     = \Config\Database::connect();
        $stream = $this->schoolStreamModel->getStream($streamId);
        if (!$stream) return 0;
        $level  = $db->table('sch_level')->where('sch_level_id', $stream['sch_level_id_fk'])->get()->getRowArray();
        return $level ? (int) $level['sch_id_fk'] : 0;
    }

    private function logAction(string $msg): void
    {
        $this->userLogModel->logAction(
            (int) $this->session->get('userID'),
            $msg,
            'TimetableController'
        );
    }
}
