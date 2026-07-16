<?php
namespace App\Controllers;

class SubjectAttendanceController extends BaseController
{
    private const UNAUTHORIZED_ROLES = [1, 4, 5, 6];
    private const TEACHER_ROLE_CAT   = 3;

    // ================================================================
    // ADD — landing page
    // ================================================================
    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Add Subject Attendance', 'Attendance', 'Add Subject Attendance');

        $roleCatID = (int) $this->session->get('roleCatID');
        $userID    = (int) $this->session->get('userID');
        $schID     = (int) $this->session->get('schID');

        $data['_view']        = 'app/attendance/subject_add';
        $data['error']        = null;
        $data['streams']      = [];
        $data['teacherAdmId'] = null;

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            $data['error'] = 'unauthorised_role';
            return view('app/layouts/main', $data);
        }

        if ($roleCatID === self::TEACHER_ROLE_CAT) {
            $admission = $this->studentAttendanceModel->getTeacherActiveAdmission($userID, $schID);
            if (!$admission) {
                $data['error'] = 'no_admission';
                return view('app/layouts/main', $data);
            }
            $data['teacherAdmId'] = (int) $admission['admission_id'];
            if (!$schID) {
                $schID = (int) $admission['sch_id_fk'];
            }
        }

        $data['streams'] = $this->studentAttendanceModel->getStreamsBySchool($schID);
        $data['schID']   = $schID;
        $data['today']   = date('Y-m-d');

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // VIEW — calendar index
    // ================================================================
    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('View Subject Attendance', 'Attendance', 'View Subject Attendance');

        $roleCatID = (int) $this->session->get('roleCatID');
        $userID    = (int) $this->session->get('userID');
        $schID     = (int) $this->session->get('schID');

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            $data['_view']   = 'app/attendance/subject_view';
            $data['error']   = 'unauthorised_role';
            $data['streams'] = [];
            return view('app/layouts/main', $data);
        }

        if ($roleCatID === self::TEACHER_ROLE_CAT && !$schID) {
            $admission = $this->studentAttendanceModel->getTeacherActiveAdmission($userID, 0);
            if ($admission) {
                $schID = (int) $admission['sch_id_fk'];
            }
        }

        $data['_view']        = 'app/attendance/subject_view';
        $data['error']        = null;
        $data['streams']      = $this->studentAttendanceModel->getStreamsBySchool($schID);
        $data['schID']        = $schID;
        $data['preStreamId']  = (int) $this->request->getGet('stream_id');

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STUDENT SELF-VIEW — my subject attendance
    // ================================================================
    public function mySubjectAttendance()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('My Subject Attendance', 'Attendance', 'My Subject Attendance');

        $userId   = (int) $this->session->get('userID');
        $streamId = (int) $this->request->getGet('stream_id');

        // Allow a parent to view a child's attendance via ?student_id=
        $studentIdParam = (int) $this->request->getGet('student_id');
        if ($studentIdParam > 0 && $studentIdParam !== $userId) {
            $db       = \Config\Database::connect();
            $isParent = $db->table('parent_student')
                ->where('parent_user_id_fk', $userId)
                ->where('student_user_id_fk', $studentIdParam)
                ->countAllResults() > 0;
            if ($isParent) {
                $userId = $studentIdParam;
            }
        }

        $records    = $streamId ? $this->studentAttendanceModel->getStudentSubjectAttendance($userId, $streamId) : [];
        $streamInfo = $streamId ? $this->studentAttendanceModel->getStreamById($streamId) : null;

        $data['_view']      = 'app/attendance/my_subject';
        $data['records']    = $records;
        $data['streamId']   = $streamId;
        $data['streamInfo'] = $streamInfo;

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // AJAX — subjects for a given stream
    // ================================================================
    public function getSubjectsByStream()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false]);
        }

        $streamId = (int) $this->request->getGet('stream_id');

        if (!$streamId) {
            return $this->response->setJSON(['success' => false, 'subjects' => []]);
        }

        $subjects = $this->studentAttendanceModel->getSubjectsByStream($streamId);

        return $this->response->setJSON([
            'success'  => true,
            'subjects' => $subjects,
        ]);
    }

    // ================================================================
    // AJAX — duplicate check
    // ================================================================
    public function checkExists()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false]);
        }

        $streamId  = (int)   $this->request->getGet('stream_id');
        $subjectId = (int)   $this->request->getGet('subject_id');
        $date      = (string) $this->request->getGet('date');

        if (!$streamId || !$subjectId || !$date) {
            return $this->response->setJSON(['exists' => false, 'count' => 0]);
        }

        $count = $this->studentAttendanceModel->checkExists($streamId, $date, 'Subject', $subjectId);

        return $this->response->setJSON([
            'exists' => $count > 0,
            'count'  => $count,
        ]);
    }

    // ================================================================
    // AJAX — load students for a given stream
    // ================================================================
    public function getStudentsByStream()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $streamId = (int) $this->request->getGet('stream_id');

        if (!$streamId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid stream selected.']);
        }

        $year     = (int) date('Y');
        $students = $this->studentAttendanceModel->getEnrolledStudents($streamId, $year);

        if (empty($students)) {
            return $this->response->setJSON([
                'success' => true,
                'count'   => 0,
                'html'    => '<tr><td colspan="5" class="text-center py-8 text-muted">
                                <i class="ki-duotone ki-information-5 fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                No students are enrolled in this stream for ' . $year . '.
                              </td></tr>',
            ]);
        }

        $html = view('app/attendance/partials/student_rows', [
            'students' => $students,
            'year'     => $year,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'count'   => count($students),
            'html'    => $html,
        ]);
    }

    // ================================================================
    // AJAX — FullCalendar event source
    // ================================================================
    public function getCalendarEvents()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([]);
        }

        $streamId  = (int) $this->request->getGet('stream_id');
        $subjectId = (int) $this->request->getGet('subject_id');

        if (!$streamId || !$subjectId) {
            return $this->response->setJSON([]);
        }

        $rows   = $this->studentAttendanceModel->getAttendanceDatesForStream($streamId, 'Subject', $subjectId);
        $events = [];

        foreach ($rows as $row) {
            $total   = (int) $row['student_count'];
            $present = (int) $row['present_count'];
            $absent  = (int) $row['absent_count'];
            $pct     = $total > 0 ? (int) round(($present / $total) * 100) : 0;

            $events[] = [
                'id'              => $streamId . '_' . $subjectId . '_' . $row['attendance_date'],
                'title'           => $total . ' student' . ($total !== 1 ? 's' : ''),
                'start'           => $row['attendance_date'],
                'allDay'          => true,
                'backgroundColor' => '#7239ea',
                'borderColor'     => '#5014d0',
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'stream_id'     => $streamId,
                    'subject_id'    => $subjectId,
                    'att_date'      => $row['attendance_date'],
                    'student_count' => $total,
                    'present_count' => $present,
                    'absent_count'  => $absent,
                    'pct_present'   => $pct,
                ],
            ];
        }

        return $this->response->setJSON($events);
    }

    // ================================================================
    // AJAX — detail modal content
    // ================================================================
    public function getDateDetail()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $streamId  = (int)   $this->request->getGet('stream_id');
        $subjectId = (int)   $this->request->getGet('subject_id');
        $date      = (string) $this->request->getGet('date');

        if (!$streamId || !$subjectId || !$date) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing parameters.']);
        }

        $records = $this->studentAttendanceModel->getAttendanceForStreamDate($streamId, $date, 'Subject', $subjectId);
        $stream  = $this->studentAttendanceModel->getStreamById($streamId);
        $subject = $this->studentAttendanceModel->getSubjectById($subjectId);

        if (empty($records)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No attendance records found for this date.']);
        }

        $attIds   = array_column($records, 'stud_att_id');
        $allFiles = $this->studentAttendanceFileModel->getFilesByAttendanceIds($attIds);

        $filesByAtt = [];
        foreach ($allFiles as $f) {
            $filesByAtt[$f['stud_att_id_fk']][] = $f;
        }
        foreach ($records as &$rec) {
            $rec['files'] = $filesByAtt[$rec['stud_att_id']] ?? [];
        }
        unset($rec);

        $html = view('app/attendance/partials/date_detail', [
            'records' => $records,
            'stream'  => $stream,
            'subject' => $subject,
            'date'    => $date,
        ]);

        return $this->response->setJSON(['success' => true, 'html' => $html]);
    }

    // ================================================================
    // POST — update a single attendance record
    // ================================================================
    public function updateRecord(int $id)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $status = trim((string) $this->request->getPost('attendance_status'));
        $note   = trim((string) $this->request->getPost('attendance_note'));

        if (!$status) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status is required.']);
        }

        $ok = $this->studentAttendanceModel->updateAttendance($id, [
            'attendance_status' => $status,
            'attendance_note'   => $note,
        ]);

        return $this->response->setJSON(['success' => (bool) $ok]);
    }

    // ================================================================
    // POST — delete a single attendance record (and its files)
    // ================================================================
    public function deleteRecord(int $id)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $files = $this->studentAttendanceFileModel->getFilesByAttendance($id);
        $this->removePhysicalFiles($files);
        $this->studentAttendanceFileModel->deleteAllForAttendanceIds([$id]);

        $ok = $this->studentAttendanceModel->deleteAttendance($id);

        return $this->response->setJSON(['success' => (bool) $ok]);
    }

    // ================================================================
    // POST — delete ALL attendance for a stream + subject + date
    // ================================================================
    public function deleteAllForDate()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $streamId  = (int)   $this->request->getPost('stream_id');
        $subjectId = (int)   $this->request->getPost('subject_id');
        $date      = (string) $this->request->getPost('date');

        if (!$streamId || !$subjectId || !$date) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing parameters.']);
        }

        $ids   = $this->studentAttendanceModel->getIdsByStreamDate($streamId, $date, 'Subject', $subjectId);
        $files = $this->studentAttendanceFileModel->getFilesByAttendanceIds($ids);
        $this->removePhysicalFiles($files);
        $this->studentAttendanceFileModel->deleteAllForAttendanceIds($ids);
        $this->studentAttendanceModel->deleteAllForStreamDate($streamId, $date, 'Subject', $subjectId);

        return $this->response->setJSON(['success' => true]);
    }

    // ================================================================
    // POST — upload additional files to an existing attendance record
    // ================================================================
    public function uploadFiles(int $attId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $uploadPath = FCPATH . 'uploads/attendance/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        try {
            $files = $this->request->getFileMultiple('upload_files');
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'No files received.']);
        }

        if (empty($files)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No files received.']);
        }

        $saved = [];

        foreach ($files as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                continue;
            }

            $ext     = strtolower($file->getClientExtension());
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $file->move($uploadPath, $newName);

            $fileId = $this->studentAttendanceFileModel->addFile([
                'stud_att_id_fk'     => $attId,
                'stud_att_file_src'  => $newName,
                'stud_att_file_type' => $ext,
            ]);

            if ($fileId) {
                $saved[] = [
                    'stud_att_file_id'   => $fileId,
                    'stud_att_id_fk'     => $attId,
                    'stud_att_file_src'  => $newName,
                    'stud_att_file_type' => $ext,
                ];
            }
        }

        return $this->response->setJSON(['success' => true, 'files' => $saved]);
    }

    // ================================================================
    // POST — delete a single file
    // ================================================================
    public function deleteFile(int $fileId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $file = $this->studentAttendanceFileModel->deleteFile($fileId);

        if ($file) {
            $physicalPath = FCPATH . 'uploads/attendance/' . $file['stud_att_file_src'];
            if (file_exists($physicalPath)) {
                unlink($physicalPath);
            }
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'File not found.']);
    }

    // ================================================================
    // POST — save attendance (full-page form redirect version)
    // ================================================================
    public function save()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $roleCatID = (int) $this->session->get('roleCatID');
        $userID    = (int) $this->session->get('userID');
        $schID     = (int) $this->session->get('schID');

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            return redirect()->to('attendance/subject/add')->with('error', 'You are not authorised to add student attendance.');
        }

        $streamId   = (int)   $this->request->getPost('stream_id');
        $subjectId  = (int)   $this->request->getPost('subject_id');
        $attDate    = (string) $this->request->getPost('attendance_date');
        $attendance = $this->request->getPost('attendance') ?? [];

        if (!$streamId || !$subjectId || !$attDate || empty($attendance)) {
            return redirect()->to('attendance/subject/add')->with('error', 'Please select a stream, subject and date, then load students before saving.');
        }

        $admissionId = $this->resolveAdmissionId($roleCatID, $userID, $schID);

        if ($admissionId === false) {
            return redirect()->to('attendance/subject/add')->with('error', 'No active admission found. Cannot save attendance.');
        }

        $uploadPath = FCPATH . 'uploads/attendance/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $savedCount = $this->persistAttendance($streamId, $subjectId, $attDate, $attendance, $admissionId, $uploadPath);

        return redirect()->to('attendance/subject/add')->with('success', "Subject attendance saved successfully for {$savedCount} student(s).");
    }

    // ================================================================
    // POST — save attendance via AJAX (calendar add modal)
    // ================================================================
    public function saveAjax()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated.']);
        }

        $roleCatID = (int) $this->session->get('roleCatID');
        $userID    = (int) $this->session->get('userID');
        $schID     = (int) $this->session->get('schID');

        if (in_array($roleCatID, self::UNAUTHORIZED_ROLES)) {
            return $this->response->setJSON(['success' => false, 'message' => 'You are not authorised to add attendance.']);
        }

        $streamId   = (int)   $this->request->getPost('stream_id');
        $subjectId  = (int)   $this->request->getPost('subject_id');
        $attDate    = (string) $this->request->getPost('attendance_date');
        $attendance = $this->request->getPost('attendance') ?? [];

        if (!$streamId || !$subjectId || !$attDate || empty($attendance)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required data. Ensure stream, subject, date and students are provided.']);
        }

        $admissionId = $this->resolveAdmissionId($roleCatID, $userID, $schID);

        if ($admissionId === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'No active admission found. Cannot save attendance.']);
        }

        $uploadPath = FCPATH . 'uploads/attendance/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $savedCount = $this->persistAttendance($streamId, $subjectId, $attDate, $attendance, $admissionId, $uploadPath);

        return $this->response->setJSON([
            'success' => true,
            'count'   => $savedCount,
            'message' => "Subject attendance saved for {$savedCount} student(s).",
        ]);
    }

    // ================================================================
    // PRIVATE — resolve teacher/admin admission ID
    // ================================================================
    private function resolveAdmissionId(int $roleCatID, int $userID, int $schID): int|false
    {
        if ($roleCatID === self::TEACHER_ROLE_CAT) {
            $admission = $this->studentAttendanceModel->getTeacherActiveAdmission($userID, $schID);
            if (!$admission) {
                return false;
            }
            return (int) $admission['admission_id'];
        }

        $admission = $this->studentAttendanceModel->getTeacherActiveAdmission($userID, $schID);
        return $admission ? (int) $admission['admission_id'] : 0;
    }

    // ================================================================
    // PRIVATE — loop and persist attendance rows + files
    // ================================================================
    private function persistAttendance(int $streamId, int $subjectId, string $attDate, array $attendance, int $admissionId, string $uploadPath): int
    {
        $savedCount = 0;

        foreach ($attendance as $rawEnrolId => $attData) {
            $enrolId = (int) $rawEnrolId;
            $status  = trim($attData['status'] ?? 'Present');
            $note    = trim($attData['note']   ?? '');

            $attId = $this->studentAttendanceModel->addAttendance([
                'enrol_id_fk'       => $enrolId,
                'stream_id_fk'      => $streamId,
                'subject_id_fk'     => $subjectId,
                'admission_id_fk'   => $admissionId,
                'attendance_date'   => $attDate,
                'attendance_note'   => $note,
                'attendance_type'   => 'Subject',
                'attendance_status' => $status,
            ]);

            if ($attId) {
                $savedCount++;
                $this->processStudentFiles($attId, $enrolId, $uploadPath);
            }
        }

        return $savedCount;
    }

    // ================================================================
    // PRIVATE — handle file uploads for one student's attendance record
    // ================================================================
    private function processStudentFiles(int $attId, int $enrolId, string $uploadPath): void
    {
        $fieldName = 'files_' . $enrolId;

        try {
            $files = $this->request->getFileMultiple($fieldName);
        } catch (\Throwable $e) {
            return;
        }

        if (empty($files)) {
            return;
        }

        foreach ($files as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                continue;
            }

            $ext     = strtolower($file->getClientExtension());
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $file->move($uploadPath, $newName);

            $this->studentAttendanceFileModel->addFile([
                'stud_att_id_fk'     => $attId,
                'stud_att_file_src'  => $newName,
                'stud_att_file_type' => $ext,
            ]);
        }
    }

    // ================================================================
    // PRIVATE — delete physical files from disk
    // ================================================================
    private function removePhysicalFiles(array $files): void
    {
        foreach ($files as $file) {
            $path = FCPATH . 'uploads/attendance/' . $file['stud_att_file_src'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
