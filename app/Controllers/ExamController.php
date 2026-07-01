<?php

namespace App\Controllers;

class ExamController extends BaseController
{
    // ================================================================
    // INDEX — Exam listing
    // ================================================================

    public function index()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $this->setPageData('Exams', 'Exam', 'All Exams');

        if (!$this->require_access('_exam_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        $data['exams']        = $this->examModel->getAllWithLevel();
        $data['canAdd']       = $isSuperAdmin || $this->grant_access('_add_exam');
        $data['canEdit']      = $isSuperAdmin || $this->grant_access('_edit_exam');
        $data['canDelete']    = $isSuperAdmin || $this->grant_access('_delete_exam');
        $data['isSuperAdmin'] = $isSuperAdmin;
        $data['_view']        = 'app/exam/index';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ADD — Show form
    // ================================================================

    public function add()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_add_exam')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $this->setPageData('Add Exam', 'Exam', 'Add');

        $data['levels'] = $this->levelModel->findAll();
        $data['_view']  = 'app/exam/add';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // STORE — Save new exam
    // ================================================================

    public function store()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('exam');
        }

        $rules = [
            'exam_name'   => 'required|max_length[260]',
            'level_id_fk' => 'required|integer',
            'exam_status' => 'required|in_list[Active,Inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('validation', $this->validator)
                ->with('error', 'Please correct the errors below.')
                ->withInput();
        }

        $this->examModel->insert([
            'exam_name'   => trim($this->request->getPost('exam_name')),
            'level_id_fk' => (int) $this->request->getPost('level_id_fk'),
            'exam_status' => $this->request->getPost('exam_status'),
        ]);

        return redirect()->to('exam')->with('success', 'Exam added successfully.');
    }

    // ================================================================
    // DETAIL — Exam info + schools listing with enrolled counts
    // ================================================================

    public function detail(int $examId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        // Teachers go directly to their school's exam detail page
        if ((int) $this->session->get('roleCatID') === 3) {
            $schId = (int) $this->session->get('schID');
            if ($schId) {
                return redirect()->to("exam/detail/{$examId}/school/{$schId}");
            }
        }

        $exam = $this->examModel->getDetail($examId);
        if (!$exam) {
            return redirect()->to('exam')->with('error', 'Exam not found.');
        }

        $this->setPageData('Exam Detail', 'Exam', 'Detail');

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;

        $data['exam']          = $exam;
        $data['schools']       = $this->studentExamModel->getSchoolSummaryForExam($examId);
        $data['totalEnrolled'] = array_sum(array_column($data['schools'], 'enrolled_count'));
        $data['canEdit']       = $isSuperAdmin || $this->grant_access('_edit_exam');
        $data['canDelete']     = $isSuperAdmin || $this->grant_access('_delete_exam');
        $data['_view']         = 'app/exam/detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // SCHOOL DETAIL — Students from one school in this exam
    // ================================================================

    // ================================================================
    // AJAX — students for an exam/school filtered by year
    // GET exam/detail/{examId}/school/{schId}/students/by-year?year=YYYY
    // ================================================================

    public function studentsByYear(int $examId, int $schId)
    {
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $year = (int) $this->request->getGet('year');

        $exam = $this->examModel->getDetail($examId);
        if (!$exam) {
            return $this->response->setJSON(['success' => false, 'message' => 'Exam not found']);
        }

        $db       = \Config\Database::connect();
        $students = $db->query("
            SELECT se.student_exam_id, se.exam_year, se.exam_term, se.student_exam_status,
                   adm.admission_id,
                   u.fname, u.lname, u.oname, u.profile_photo,
                   str.stream_name
            FROM student_exam se
            INNER JOIN enrolment  enr ON enr.enrol_id       = se.enrol_id_fk
            INNER JOIN stream     str ON str.stream_id       = enr.stream_id_fk
            INNER JOIN sch_level  sl  ON sl.sch_level_id    = str.sch_level_id_fk
            INNER JOIN exam       ex  ON ex.exam_id         = se.exam_id_fk
                                     AND ex.level_id_fk     = sl.level_id_fk
            INNER JOIN admission  adm ON adm.admission_id   = enr.admission_id_fk
            INNER JOIN users      u   ON u.user_id          = adm.user_id_fk
            WHERE se.exam_id_fk = ?
              AND adm.sch_id_fk = ?
              AND se.exam_year  = ?
            ORDER BY u.fname, u.lname
        ", [$examId, $schId, $year])->getResultArray();

        // Which admissions already have marks (cannot be dropped)
        $markedRows = $db->query("
            SELECT DISTINCT er.admission_id_fk
            FROM exam_registration er
            INNER JOIN exam_mark em ON em.exam_reg_id_fk = er.exam_reg_id
            WHERE er.exam_id_fk = ? AND er.exam_year = ?
        ", [$examId, $year])->getResultArray();
        $markedAdmissions = array_column($markedRows, 'admission_id_fk');

        // Stats for this exam/school/year only
        $rule              = $this->scoringRule($exam['level_name'] ?? '');
        $studentsWithMarks = $this->studentExamModel->getStudentsWithMarksBySchoolByYear($examId, $schId, $year);
        $stats             = $this->aggregateExamStats($studentsWithMarks, $rule);

        return $this->response->setJSON([
            'success'          => true,
            'students'         => $students,
            'markedAdmissions' => $markedAdmissions,
            'count'            => count($students),
            'stats'            => $stats,
            'scoringRule'      => $rule,
        ]);
    }

    public function schoolDetail(int $examId, int $schId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_exam_listing')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $exam   = $this->examModel->getDetail($examId);
        $school = $this->schoolModel->find($schId);

        if (!$exam || !$school) {
            return redirect()->to('exam/detail/' . $examId)->with('error', 'Record not found.');
        }

        $this->setPageData('Exam — ' . $school['sch_name'], 'Exam', 'School Detail');

        $students     = $this->studentExamModel->getStudentsInExamBySchool($examId, $schId);

        $defaultYear              = (int) date('Y');
        $hasStudentsForDefaultYear = count(array_filter(
            $students,
            static fn($s) => (int) $s['exam_year'] === $defaultYear
        )) > 0;

        // Registering students into the exam is locked to the actual current year — no back-dated entry.
        $currentYear      = (int) date('Y');
        $eligibleStudents = $this->studentExamModel->getEligibleEnrolmentsBySchool($examId, $schId, $currentYear);
        $totalAtLevel     = $this->studentExamModel->countEnrolmentsAtLevel($schId, (int) $exam['level_id_fk'], $currentYear);

        // Compute exam statistics from exam_subject marks
        $rule              = $this->scoringRule($exam['level_name'] ?? '');
        $studentsWithMarks = $this->studentExamModel->getStudentsWithMarksBySchool($examId, $schId);
        $stats             = $this->aggregateExamStats($studentsWithMarks, $rule);

        // Build per-admission mark status for disabling drop buttons
        $markedAdmissions = [];
        foreach ($studentsWithMarks as $sw) {
            foreach ($sw['subjects'] as $sub) {
                if ((int) $sub['exam_mark'] > 0) {
                    $markedAdmissions[$sw['admission_id']] = true;
                    break;
                }
            }
        }
        $anyStudentHasMarks = !empty($markedAdmissions);

        $data['exam']               = $exam;
        $data['school']             = $school;
        $data['students']           = $students;
        $data['eligibleStudents']   = $eligibleStudents;
        $data['totalAtLevel']       = $totalAtLevel;
        $data['currentYear']               = $currentYear;
        $data['defaultYear']               = $defaultYear;
        $data['hasStudentsForDefaultYear'] = $hasStudentsForDefaultYear;
        $data['stats']              = $stats;
        $data['scoringRule']        = $rule;
        $data['markedAdmissions']   = $markedAdmissions;
        $data['anyStudentHasMarks'] = $anyStudentHasMarks;
        $data['canManageStudents']  = $isSuperAdmin || $this->grant_access('_add_exam');
        $data['canDelete']          = $isSuperAdmin || $this->grant_access('_delete_exam');
        $data['_view']              = 'app/exam/school_detail';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // EDIT — Show edit form
    // ================================================================

    public function edit(int $examId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_edit_exam')) {
            $data['_view'] = 'app/auth/access_control';
            return view('app/layouts/main', $data);
        }

        $exam = $this->examModel->getDetail($examId);
        if (!$exam) {
            return redirect()->to('exam')->with('error', 'Exam not found.');
        }

        $this->setPageData('Edit Exam', 'Exam', 'Edit');

        $data['exam']   = $exam;
        $data['levels'] = $this->levelModel->findAll();
        $data['_view']  = 'app/exam/edit';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // UPDATE — Save changes
    // ================================================================

    public function update(int $examId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('exam/edit/' . $examId);
        }

        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('exam')->with('error', 'Exam not found.');
        }

        $rules = [
            'exam_name'   => 'required|max_length[260]',
            'level_id_fk' => 'required|integer',
            'exam_status' => 'required|in_list[Active,Inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('validation', $this->validator)
                ->with('error', 'Please correct the errors below.')
                ->withInput();
        }

        $this->examModel->update($examId, [
            'exam_name'   => trim($this->request->getPost('exam_name')),
            'level_id_fk' => (int) $this->request->getPost('level_id_fk'),
            'exam_status' => $this->request->getPost('exam_status'),
        ]);

        return redirect()->to('exam/detail/' . $examId)->with('success', 'Exam updated successfully.');
    }

    // ================================================================
    // DELETE
    // ================================================================

    public function delete(int $examId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_delete_exam')) {
            return redirect()->to('exam')->with('error', 'You do not have permission to delete exams.');
        }

        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('exam')->with('error', 'Exam not found.');
        }

        $db = \Config\Database::connect();

        // Delete exam_mark entries via exam_registration, then registrations, then student_exam rows
        $examRegIds = array_column(
            $db->table('exam_registration')->select('exam_reg_id')
               ->where('exam_id_fk', $examId)->get()->getResultArray(),
            'exam_reg_id'
        );
        if ($examRegIds) {
            $db->table('exam_mark')->whereIn('exam_reg_id_fk', $examRegIds)->delete();
        }
        $db->table('exam_registration')->where('exam_id_fk', $examId)->delete();
        $db->table('student_exam')->where('exam_id_fk', $examId)->delete();
        $this->examModel->delete($examId);

        return redirect()->to('exam')->with('success', 'Exam deleted successfully.');
    }

    // ================================================================
    // ADD STUDENTS TO EXAM — POST from detail page
    // ================================================================

    public function addStudents(int $examId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $exam = $this->examModel->find($examId);
        if (!$exam) {
            return redirect()->to('exam')->with('error', 'Exam not found.');
        }

        $enrolIds = $this->request->getPost('enrol_ids') ?? [];
        $year     = (int) $this->request->getPost('exam_year');
        $term     = (int) $this->request->getPost('exam_term');
        $schId    = (int) $this->request->getPost('sch_id');
        $userID   = (int) $this->session->get('userID');

        $redirectBase = $schId
            ? 'exam/detail/' . $examId . '/school/' . $schId
            : 'exam/detail/' . $examId;

        if (empty($enrolIds) || !$year || !$term) {
            return redirect()->to($redirectBase)->with('error', 'Please select students and fill in year and term.');
        }

        $db    = \Config\Database::connect();
        $added = 0;

        foreach ($enrolIds as $enrolId) {
            $enrolId = (int) $enrolId;

            $exists = $this->studentExamModel
                ->where('exam_id_fk', $examId)
                ->where('enrol_id_fk', $enrolId)
                ->where('exam_year', $year)
                ->where('exam_term', $term)
                ->first();

            if (!$exists) {
                $this->studentExamModel->insert([
                    'exam_id_fk'          => $examId,
                    'enrol_id_fk'         => $enrolId,
                    'exam_year'           => $year,
                    'exam_term'           => $term,
                    'student_exam_status' => 'Active',
                    'created_by_fk'       => $userID,
                    'created_date'        => date('Y-m-d'),
                    'created_time'        => time(),
                ]);
                $added++;

                // Register student index number in exam_registration
                $enrolRow = $db->table('enrolment')->select('admission_id_fk')->where('enrol_id', $enrolId)->get()->getRowArray();
                if ($enrolRow) {
                    $this->examRegistrationModel->registerStudent(
                        $examId,
                        (int) $enrolRow['admission_id_fk'],
                        $year
                    );
                }
            }
        }

        return redirect()->to($redirectBase)
            ->with('success', "{$added} student(s) added to exam.");
    }

    // ================================================================
    // REMOVE STUDENT FROM EXAM — POST
    // ================================================================

    public function removeStudent(int $studentExamId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $row = $this->studentExamModel->find($studentExamId);
        if (!$row) {
            return redirect()->to('exam')->with('error', 'Record not found.');
        }

        $examId = $row['exam_id_fk'];
        $schId  = (int) $this->request->getPost('sch_id');

        $db = \Config\Database::connect();
        // Delete exam_mark entries via exam_registration, then the registration itself
        $enrolRow = $db->table('enrolment')
            ->select('admission_id_fk')
            ->where('enrol_id', $row['enrol_id_fk'])
            ->get()->getRowArray();
        if ($enrolRow) {
            $examReg = $db->table('exam_registration')
                ->select('exam_reg_id')
                ->where('exam_id_fk',      $examId)
                ->where('admission_id_fk', (int) $enrolRow['admission_id_fk'])
                ->get()->getRowArray();
            if ($examReg) {
                $db->table('exam_mark')->where('exam_reg_id_fk', (int) $examReg['exam_reg_id'])->delete();
            }
            $this->examRegistrationModel->removeByAdmissionAndExam(
                (int) $enrolRow['admission_id_fk'],
                $examId
            );
        }

        $this->studentExamModel->delete($studentExamId);

        $redirect = $schId
            ? 'exam/detail/' . $examId . '/school/' . $schId
            : 'exam/detail/' . $examId;

        return redirect()->to($redirect)->with('success', 'Student dropped from exam.');
    }

    // ================================================================
    // DROP ALL STUDENTS — POST: remove all students from a school in an exam
    // ================================================================

    public function dropAllStudents(int $examId, int $schId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_delete_exam')) {
            return redirect()->to('exam/detail/' . $examId . '/school/' . $schId)
                ->with('error', 'You do not have permission to drop students.');
        }

        $exam   = $this->examModel->find($examId);
        $school = $this->schoolModel->find($schId);
        if (!$exam || !$school) {
            return redirect()->to('exam/detail/' . $examId)->with('error', 'Record not found.');
        }

        $db       = \Config\Database::connect();
        $students = $this->studentExamModel->getStudentsInExamBySchool($examId, $schId);

        if (empty($students)) {
            return redirect()->to('exam/detail/' . $examId . '/school/' . $schId)
                ->with('error', 'No students to drop.');
        }

        $studentExamIds = array_column($students, 'student_exam_id');
        $enrolIds       = array_column($students, 'enrol_id_fk');

        // Delete exam_mark entries via exam_registration, then the registrations
        if ($enrolIds) {
            $admissionRows = $db->table('enrolment')
                ->select('admission_id_fk')
                ->whereIn('enrol_id', $enrolIds)
                ->get()->getResultArray();

            $admissionIds = array_column($admissionRows, 'admission_id_fk');
            if ($admissionIds) {
                $examRegIds = array_column(
                    $db->table('exam_registration')->select('exam_reg_id')
                       ->where('exam_id_fk', $examId)
                       ->whereIn('admission_id_fk', $admissionIds)
                       ->get()->getResultArray(),
                    'exam_reg_id'
                );
                if ($examRegIds) {
                    $db->table('exam_mark')->whereIn('exam_reg_id_fk', $examRegIds)->delete();
                }
                $this->examRegistrationModel->removeByExamAndAdmissions($examId, $admissionIds);
            }
        }

        // Delete student_exam rows
        $db->table('student_exam')->whereIn('student_exam_id', $studentExamIds)->delete();

        $count = count($studentExamIds);

        return redirect()->to('exam/detail/' . $examId . '/school/' . $schId)
            ->with('success', "{$count} student(s) dropped from exam.");
    }

    // ================================================================
    // MARKS — View / enter marks for a student in an exam
    // Subjects come from exam_subject (via exam_registration)
    // ================================================================

    public function marks(int $studentExamId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin  = (int) $this->session->get('roleID') === 1;
        $canEnterMarks = $isSuperAdmin || $this->grant_access('_enter_exam_mark');

        $studentExam = $this->studentExamModel->getStudentExamDetail($studentExamId);
        if (!$studentExam) {
            return redirect()->to('exam')->with('error', 'Record not found.');
        }

        $this->setPageData('Exam Marks', 'Exam', 'Marks Entry');

        $db = \Config\Database::connect();

        // exam_registration needed for index number display and mark linking
        $examReg = $db->table('exam_registration')
            ->where('exam_id_fk',      (int) $studentExam['exam_id_fk'])
            ->where('admission_id_fk', (int) $studentExam['admission_id'])
            ->get()->getRowArray();

        $examRegId = $examReg ? (int) $examReg['exam_reg_id'] : 0;

        // Load examinable subjects from student_subject, with marks from exam_mark
        $subjects = $this->getStudentSubjectsWithMarks(
            (int) $studentExam['admission_id'],
            $examRegId
        );

        // If no subjects yet, load stream subjects so the assign form can be rendered
        $streamSubjects = ['core' => [], 'optional' => []];
        if (empty($subjects) && !empty($studentExam['stream_id_fk'])) {
            $enrolmentModel = new \App\Models\EnrolmentModel();
            $streamSubjects = $enrolmentModel->getSubjectsByStream((int) $studentExam['stream_id_fk']);
        }

        // Compute this student's individual exam score
        $rule         = $this->scoringRule($studentExam['level_name'] ?? '');
        $studentScore = $this->calcStudentScore($subjects, $rule);

        // Rank this student among peers who sat the same exam, at the same school, in the same year
        $position      = null;
        $positionTotal = 0;
        if ($studentScore['sat']) {
            $cohort = $this->studentExamModel->getStudentsWithMarksBySchoolByYear(
                (int) $studentExam['exam_id_fk'],
                (int) $studentExam['sch_id_fk'],
                (int) $studentExam['exam_year']
            );

            $cohortTotals = [];
            foreach ($cohort as $cs) {
                $score = $this->calcStudentScore($cs['subjects'], $rule);
                if ($score['sat']) {
                    $cohortTotals[(int) $cs['student_exam_id']] = $score['total'];
                }
            }
            arsort($cohortTotals);
            $positionTotal = count($cohortTotals);

            $rank      = 0;
            $seen      = 0;
            $prevTotal = null;
            foreach ($cohortTotals as $sid => $total) {
                $seen++;
                if ($prevTotal === null || $total < $prevTotal) {
                    $rank = $seen;
                }
                if ($sid === $studentExamId) {
                    $position = $rank;
                    break;
                }
                $prevTotal = $total;
            }
        }

        $data['studentExam']    = $studentExam;
        $data['examReg']        = $examReg;
        $data['subjects']       = $subjects;
        $data['streamSubjects'] = $streamSubjects;
        $data['studentScore']   = $studentScore;
        $data['scoringRule']    = $rule;
        $data['position']       = $position;
        $data['positionTotal']  = $positionTotal;
        $data['canEnterMarks']  = $canEnterMarks;
        $data['_view']          = 'app/exam/marks';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ASSIGN SUBJECTS FORM — GET: show subject selection form
    // GET exam/student/{id}/subjects/assign
    // ================================================================

    public function showAssignSubjects(int $studentExamId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_enter_exam_mark')) {
            return redirect()->back()->with('error', 'You do not have permission to manage subjects.');
        }

        $studentExam = $this->studentExamModel->getStudentExamDetail($studentExamId);
        if (!$studentExam) {
            return redirect()->to('exam')->with('error', 'Record not found.');
        }

        $this->setPageData('Assign Subjects', 'Exam', 'Assign Subjects');

        $db = \Config\Database::connect();

        // Load current subject assignments from student_subject
        $currentSubjects = $db->table('student_subject')
            ->where('admission_id_fk', (int) $studentExam['admission_id'])
            ->where('stud_sub_status', 'Active')
            ->get()->getResultArray();

        $enrolmentModel = new \App\Models\EnrolmentModel();
        $streamSubjects = !empty($studentExam['stream_id_fk'])
            ? $enrolmentModel->getSubjectsByStream((int) $studentExam['stream_id_fk'])
            : ['core' => [], 'optional' => []];

        // Map assigned sch_sub_ids for pre-selecting checkboxes / radios
        $assignedSchSubIds = array_map('intval', array_column($currentSubjects, 'sch_sub_id_fk'));

        $assignedCore = [];
        foreach ($streamSubjects['core'] as $s) {
            if (in_array((int) $s['sch_sub_id'], $assignedSchSubIds)) {
                $assignedCore[] = (int) $s['sch_sub_id'];
            }
        }

        $assignedOptional = [];
        foreach ($streamSubjects['optional'] as $s) {
            if (in_array((int) $s['sch_sub_id'], $assignedSchSubIds)) {
                $assignedOptional[(int) $s['option_num']] = (int) $s['sch_sub_id'];
            }
        }

        $data['studentExam']          = $studentExam;
        $data['hasExistingSubjects']  = !empty($currentSubjects);
        $data['streamSubjects']       = $streamSubjects;
        $data['assignedCore']         = $assignedCore;
        $data['assignedOptional']     = $assignedOptional;
        $data['_view']                = 'app/exam/subjects_assign';

        return view('app/layouts/main', $data);
    }

    // ================================================================
    // ASSIGN SUBJECTS — POST: save subject selections to exam_subject
    // POST exam/student/{id}/subjects/assign
    // ================================================================

    public function assignSubjects(int $studentExamId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_enter_exam_mark')) {
            return redirect()->back()->with('error', 'You do not have permission to assign subjects.');
        }

        $studentExam = $this->studentExamModel->getStudentExamDetail($studentExamId);
        if (!$studentExam) {
            return redirect()->to('exam')->with('error', 'Record not found.');
        }

        $admissionId = (int) $studentExam['admission_id'];
        $db          = \Config\Database::connect();

        // Find classroom for this stream + year
        $classRow = $db->table('classroom')
            ->where('stream_id_fk', (int) $studentExam['stream_id_fk'])
            ->where('class_year',   (int) $studentExam['enrol_year'])
            ->get()->getRowArray();

        if (!$classRow) {
            return redirect()->back()
                ->with('error', 'No classroom found for this student\'s stream and year. Please create a classroom first.');
        }

        $classId = (int) $classRow['class_id'];

        // Replace all existing entries for this student in this classroom
        $db->table('student_subject')
            ->where('admission_id_fk', $admissionId)
            ->where('class_id_fk', $classId)
            ->delete();

        $coreSubIds = array_filter(array_map('intval', (array) ($this->request->getPost('exam_core_subs') ?? [])));
        foreach ($coreSubIds as $schSubId) {
            $db->table('student_subject')->insert([
                'admission_id_fk' => $admissionId,
                'class_id_fk'     => $classId,
                'sch_sub_id_fk'   => $schSubId,
                'stud_sub_status' => 'Active',
            ]);
        }

        foreach ($this->request->getPost() as $key => $val) {
            if (preg_match('/^exam_opt_group_\d+$/', $key) && !empty($val)) {
                $db->table('student_subject')->insert([
                    'admission_id_fk' => $admissionId,
                    'class_id_fk'     => $classId,
                    'sch_sub_id_fk'   => (int) $val,
                    'stud_sub_status' => 'Active',
                ]);
            }
        }

        return redirect()->to('exam/student/' . $studentExamId . '/marks')
            ->with('success', 'Subjects assigned successfully.');
    }

    // ================================================================
    // SAVE MARKS — POST: validate and persist marks
    // ================================================================

    public function saveMarks(int $studentExamId)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $isSuperAdmin = (int) $this->session->get('roleID') === 1;
        if (!$isSuperAdmin && !$this->grant_access('_enter_exam_mark')) {
            return redirect()->back()->with('error', 'You do not have permission to enter marks.');
        }

        $studentExam = $this->studentExamModel->getStudentExamDetail($studentExamId);
        if (!$studentExam) {
            return redirect()->to('exam')->with('error', 'Record not found.');
        }

        $db = \Config\Database::connect();

        // Ensure exam_registration exists for mark linking
        $examRegId = $this->examRegistrationModel->registerStudentGetRegId(
            (int) $studentExam['exam_id_fk'],
            (int) $studentExam['admission_id'],
            (int) $studentExam['enrol_year']
        );

        $marks  = $this->request->getPost('marks') ?? [];
        $errors = [];
        $saved  = 0;

        foreach ($marks as $studSubId => $markVal) {
            $studSubId = (int) $studSubId;
            $markVal   = trim((string) $markVal);

            if ($markVal === '') {
                // Blank — remove any existing mark record
                $db->table('exam_mark')
                    ->where('exam_reg_id_fk', $examRegId)
                    ->where('stud_sub_id_fk', $studSubId)
                    ->delete();
                continue;
            }

            if (!ctype_digit($markVal) || (int) $markVal < 1 || (int) $markVal > 100) {
                $errors[] = "Invalid mark — must be a whole number between 1 and 100.";
                continue;
            }

            $markInt  = (int) $markVal;
            $existing = $db->table('exam_mark')
                ->where('exam_reg_id_fk', $examRegId)
                ->where('stud_sub_id_fk', $studSubId)
                ->get()->getRowArray();

            if ($existing) {
                $db->table('exam_mark')
                    ->where('exam_sub_id', $existing['exam_sub_id'])
                    ->update(['exam_mark' => $markInt]);
            } else {
                $db->table('exam_mark')->insert([
                    'exam_reg_id_fk' => $examRegId,
                    'stud_sub_id_fk' => $studSubId,
                    'exam_mark'      => $markInt,
                ]);
            }
            $saved++;
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', implode(' ', array_unique($errors)));
        }

        return redirect()->to('exam/student/' . $studentExamId . '/marks')
            ->with('success', "{$saved} mark(s) saved successfully.");
    }

    // ================================================================
    // SUBJECT / MARK HELPERS
    // ================================================================

    /**
     * Load a student's examinable subjects from student_subject, joined with
     * their marks from exam_mark for the given exam_reg_id.
     * Only subjects where subject.is_examinable = 1 are returned.
     * Returns: stud_sub_id, sch_sub_id_fk, subject_name, sub_type, exam_mark.
     */
    private function getStudentSubjectsWithMarks(int $admissionId, int $examRegId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT
                ss.stud_sub_id,
                ss.sch_sub_id_fk,
                sub.subject_name,
                CASE
                    WHEN scs.stream_core_sub_id IS NOT NULL THEN 'Core'
                    WHEN sos.stream_opt_sub_id  IS NOT NULL THEN 'Optional'
                    ELSE 'Core'
                END AS sub_type,
                COALESCE(em.exam_mark, 0) AS exam_mark
            FROM student_subject ss
            INNER JOIN sch_subject schsub ON schsub.sch_sub_id = ss.sch_sub_id_fk
            INNER JOIN subject     sub    ON sub.subject_id    = schsub.subject_id_fk
                                         AND sub.is_examinable  = 1
            LEFT  JOIN classroom   cl     ON cl.class_id       = ss.class_id_fk
            LEFT  JOIN stream_core_subject scs
                    ON scs.sch_sub_id_fk = ss.sch_sub_id_fk
                   AND scs.stream_id_fk  = cl.stream_id_fk
            LEFT  JOIN stream_optional_subject sos
                    ON sos.sch_sub_id_fk = ss.sch_sub_id_fk
                   AND sos.stream_id_fk  = cl.stream_id_fk
            LEFT  JOIN exam_mark em
                    ON em.stud_sub_id_fk = ss.stud_sub_id
                   AND em.exam_reg_id_fk = ?
            WHERE ss.admission_id_fk = ?
              AND ss.stud_sub_status  = 'Active'
            ORDER BY sub_type ASC, sub.subject_name ASC
        ", [$examRegId, $admissionId])->getResultArray();
    }

    // ================================================================
    // PDF REPORT — School summary
    // GET exam/detail/{examId}/school/{schId}/report/pdf
    // ================================================================

    public function schoolReportPdf(int $examId, int $schId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $exam   = $this->examModel->getDetail($examId);
        $school = $this->schoolModel->find($schId);
        if (!$exam || !$school) return redirect()->back()->with('error', 'Record not found.');

        $year = (int) $this->request->getGet('year');

        $rule              = $this->scoringRule($exam['level_name'] ?? '');
        $studentsWithMarks = $year > 0
            ? $this->studentExamModel->getStudentsWithMarksBySchoolByYear($examId, $schId, $year)
            : $this->studentExamModel->getStudentsWithMarksBySchool($examId, $schId);

        $rows = [];
        foreach ($studentsWithMarks as $sw) {
            $score  = $this->calcStudentScore($sw['subjects'], $rule);
            $pct    = ($score['sat'] && $rule['max'] > 0)
                    ? round($score['total'] / $rule['max'] * 100, 1) : null;
            $rows[] = [
                'name'   => trim($sw['fname'] . ' ' . $sw['lname']),
                'total'  => $score['sat'] ? $score['total'] : null,
                'max'    => $rule['max'],
                'pct'    => $pct,
                'grade'  => $pct !== null ? $this->examGrade($pct) : '—',
                'passed' => $score['passed'],
                'sat'    => $score['sat'],
            ];
        }
        usort($rows, fn($a, $b) => strcmp($a['name'], $b['name']));

        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
        set_error_handler(static function(int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP') || str_contains($errstr, 'gd-png') || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetTitle($exam['exam_name'] . ' — ' . $school['sch_name']);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(true, 14);
        $pdf->AddPage();

        $sx = 12; $cw = 186; $y = 12.0;

        $pdf->SetLineStyle(['width' => 1.0, 'color' => [26, 86, 219]]);
        $pdf->Rect(8, 8, 194, 281, 'D');
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Rect(10, 10, 190, 277, 'D');

        $logoPath = FCPATH . 'uploads/school/logo/' . ($school['sch_logo'] ?? '');
        if (!empty($school['sch_logo']) && file_exists($logoPath)) {
            $pdf->Image($logoPath, $sx, $y, 22, 22, '', '', 'T', false, 300);
        }
        $navuliLogo = FCPATH . 'icon.png';
        if (file_exists($navuliLogo)) {
            $pdf->Image($navuliLogo, $sx + $cw - 22, $y, 20, 20, '', '', 'T', false, 300);
        }

        $cx = $sx + 24; $centerW = $cw - 46;

        $pdf->SetXY($cx, $y + 1);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($centerW, 6, strtoupper($school['sch_name']), 0, 1, 'C');

        $pdf->SetXY($cx, $y + 8);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell($centerW, 4, strtoupper($exam['exam_name']) . ' — ' . strtoupper($exam['level_name'] ?? '') . ($year > 0 ? ' (' . $year . ')' : ''), 0, 1, 'C');

        $contactParts = array_filter([
            $school['sch_address'] ?? '',
            !empty($school['sch_phone']) ? 'Ph: ' . $school['sch_phone'] : '',
            $school['sch_email'] ?? '',
        ]);
        $pdf->SetXY($cx, $y + 13);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($centerW, 4, implode('  |  ', $contactParts), 0, 1, 'C');

        $y += 26;
        $pdf->SetLineStyle(['width' => 0.7, 'color' => [26, 86, 219]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [147, 197, 253]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 5;

        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'I', 6.5);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($cw, 4, 'Scoring: English + Best ' . $rule['best_n'] . ' subjects = Max ' . $rule['max'] . ' marks. Pass average ≥ 50.', 0, 1, 'C');
        $y += 6;

        $cName = 72; $cTotal = 26; $cMax = 20; $cPct = 24; $cGrade = 20; $cResult = $cw - $cName - $cTotal - $cMax - $cPct - $cGrade;
        $rh    = 7;

        $pdf->SetXY($sx, $y);
        $pdf->SetFillColor(240, 247, 255);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->SetDrawColor(200, 220, 240);
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [200, 220, 240]]);
        $pdf->Cell($cName,   $rh, 'Student Name', 1, 0, 'L', true);
        $pdf->Cell($cTotal,  $rh, 'Total Score',  1, 0, 'C', true);
        $pdf->Cell($cMax,    $rh, 'Max',          1, 0, 'C', true);
        $pdf->Cell($cPct,    $rh, 'Percentage',   1, 0, 'C', true);
        $pdf->Cell($cGrade,  $rh, 'Grade',        1, 0, 'C', true);
        $pdf->Cell($cResult, $rh, 'Result',       1, 1, 'C', true);
        $y += $rh;

        $i = 0;
        foreach ($rows as $r) {
            $fill = ($i % 2 === 0);
            $pdf->SetFillColor(248, 250, 252);
            $pdf->SetDrawColor(230, 236, 241);
            $pdf->SetFont('helvetica', '', 7.5);
            $pdf->SetTextColor(30, 30, 30);
            $pdf->Cell($cName,  $rh, $r['name'], 1, 0, 'L', $fill);
            $pdf->Cell($cTotal, $rh, $r['sat'] ? (string)$r['total'] : '—', 1, 0, 'C', $fill);
            $pdf->Cell($cMax,   $rh, (string)$r['max'], 1, 0, 'C', $fill);
            $pdf->Cell($cPct,   $rh, $r['pct'] !== null ? $r['pct'] . '%' : '—', 1, 0, 'C', $fill);

            $gRgb = $this->examGradeRgb($r['grade']);
            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->SetTextColor(...$gRgb);
            $pdf->Cell($cGrade, $rh, $r['grade'], 1, 0, 'C', $fill);

            $pdf->SetTextColor(...($r['passed'] ? [21, 128, 61] : [185, 28, 28]));
            $pdf->Cell($cResult, $rh, $r['sat'] ? ($r['passed'] ? 'PASS' : 'FAIL') : 'DNS', 1, 1, 'C', $fill);
            $i++;
        }

        $sat    = count(array_filter($rows, fn($r) => $r['sat']));
        $passed = count(array_filter($rows, fn($r) => $r['passed']));
        $yf = $pdf->GetY() + 5;
        $pdf->SetXY($sx, $yf);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell($cw, 5,
            'Total Enrolled: ' . count($rows) . '   |   Sat: ' . $sat .
            '   |   Passed: ' . $passed . '   |   Failed: ' . ($sat - $passed) .
            '   |   Pass Rate: ' . ($sat > 0 ? round($passed / $sat * 100, 1) : 0) . '%',
            0, 1, 'C'
        );

        restore_error_handler();
        $pdf->Output('exam_report_school_' . $schId . '_' . $examId . ($year > 0 ? '_' . $year : '') . '.pdf', 'I');
        exit;
    }

    // ================================================================
    // PDF REPORT — Individual student marks
    // GET exam/student/{id}/marks/report/pdf
    // ================================================================

    public function studentReportPdf(int $studentExamId)
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $studentExam = $this->studentExamModel->getStudentExamDetail($studentExamId);
        if (!$studentExam) return redirect()->back()->with('error', 'Record not found.');

        $db     = \Config\Database::connect();
        $school = $this->schoolModel->find((int) $studentExam['sch_id_fk']);

        $examReg = $db->table('exam_registration')
            ->where('exam_id_fk',      (int) $studentExam['exam_id_fk'])
            ->where('admission_id_fk', (int) $studentExam['admission_id'])
            ->get()->getRowArray();
        $examRegId = $examReg ? (int) $examReg['exam_reg_id'] : 0;

        $subjects = $this->getStudentSubjectsWithMarks((int) $studentExam['admission_id'], $examRegId);
        $rule     = $this->scoringRule($studentExam['level_name'] ?? '');
        $score    = $this->calcStudentScore($subjects, $rule);
        $pct      = ($score['sat'] && $rule['max'] > 0)
                  ? round($score['total'] / $rule['max'] * 100, 1) : null;
        $grade    = $pct !== null ? $this->examGrade($pct) : null;

        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
        set_error_handler(static function(int $errno, string $errstr): bool {
            return str_contains($errstr, 'iCCP') || str_contains($errstr, 'gd-png') || str_contains($errstr, 'libpng warning');
        }, E_WARNING);

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Navuli Fiji');
        $pdf->SetTitle($studentExam['exam_name'] . ' — ' . $studentExam['fname'] . ' ' . $studentExam['lname']);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();

        $sx = 12; $cw = 186; $y = 12.0;

        $pdf->SetLineStyle(['width' => 1.0, 'color' => [26, 86, 219]]);
        $pdf->Rect(8, 8, 194, 281, 'D');
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [147, 197, 253]]);
        $pdf->Rect(10, 10, 190, 277, 'D');

        $logoPath = FCPATH . 'uploads/school/logo/' . ($school['sch_logo'] ?? '');
        if (!empty($school['sch_logo']) && file_exists($logoPath)) {
            $pdf->Image($logoPath, $sx, $y, 22, 22, '', '', 'T', false, 300);
        }
        $navuliLogo = FCPATH . 'icon.png';
        if (file_exists($navuliLogo)) {
            $pdf->Image($navuliLogo, $sx + $cw - 22, $y, 20, 20, '', '', 'T', false, 300);
        }

        $cx = $sx + 24; $centerW = $cw - 46;

        $pdf->SetXY($cx, $y + 1);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($centerW, 6, strtoupper($school['sch_name'] ?? $studentExam['sch_name']), 0, 1, 'C');

        $pdf->SetXY($cx, $y + 8);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell($centerW, 4, strtoupper($studentExam['exam_name']) . ' EXAMINATION RESULT', 0, 1, 'C');

        $contactParts = array_filter([
            $school['sch_address'] ?? '',
            !empty($school['sch_phone']) ? 'Ph: ' . $school['sch_phone'] : '',
            $school['sch_email'] ?? '',
        ]);
        $pdf->SetXY($cx, $y + 13);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($centerW, 4, implode('  |  ', $contactParts), 0, 1, 'C');

        $y += 26;
        $pdf->SetLineStyle(['width' => 0.7, 'color' => [26, 86, 219]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 1.5;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [147, 197, 253]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 6;

        // Student photo
        if (!empty($studentExam['profile_photo'])) {
            $photoPath = FCPATH . 'uploads/profilePhoto/' . $studentExam['profile_photo'];
            if (file_exists($photoPath)) {
                $pdf->Image($photoPath, $sx, $y, 22, 26, '', '', 'T', false, 300);
            }
        } else {
            $pdf->SetFillColor(219, 234, 254);
            $pdf->SetDrawColor(219, 234, 254);
            $pdf->RoundedRect($sx, $y, 22, 26, 2, '1111', 'DF');
            $pdf->SetXY($sx, $y + 8);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(29, 78, 216);
            $pdf->Cell(22, 10, strtoupper(substr($studentExam['fname'], 0, 1) . substr($studentExam['lname'], 0, 1)), 0, 0, 'C');
        }

        $infoX = $sx + 25; $lw = 28; $vw = 68;
        $infoRows = [
            ['Full Name', $studentExam['fname'] . ' ' . ($studentExam['oname'] ? $studentExam['oname'] . ' ' : '') . $studentExam['lname']],
            ['Stream',    $studentExam['stream_name'] ?? '—'],
            ['Year',      (string) $studentExam['enrol_year']],
            ['Level',     $studentExam['level_name'] ?? '—'],
        ];
        foreach ($infoRows as $fi => $f) {
            $pdf->SetXY($infoX, $y + 1 + $fi * 6.5);
            $pdf->SetFont('helvetica', '', 7.5);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell($lw, 5, $f[0], 0, 0, 'L');
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->Cell($vw, 5, $f[1], 0, 1, 'L');
        }

        // Score box
        if ($grade !== null) {
            $gRgb = $this->examGradeRgb($grade);
            $boxX = $sx + $cw - 62; $boxW = 62; $boxH = 26;
            $pdf->SetFillColor(248, 250, 252);
            $pdf->SetDrawColor(226, 232, 240);
            $pdf->SetLineStyle(['width' => 0.4, 'color' => [226, 232, 240]]);
            $pdf->RoundedRect($boxX, $y, $boxW, $boxH, 2, '1111', 'DF');

            $pdf->SetXY($boxX, $y + 2);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(...$gRgb);
            $pdf->Cell($boxW, 6, $score['total'] . ' / ' . $rule['max'], 0, 1, 'C');

            $pdf->SetLineStyle(['width' => 0.3, 'color' => [221, 228, 237]]);
            $pdf->Line($boxX + 2, $y + 11, $boxX + $boxW - 2, $y + 11);

            $halfW = $boxW / 2;
            $pdf->SetXY($boxX, $y + 13);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetTextColor(...($score['passed'] ? [21, 128, 61] : [185, 28, 28]));
            $pdf->Cell($halfW, 4.5, $score['passed'] ? 'PASS' : 'FAIL', 0, 0, 'C');
            $pdf->SetXY($boxX, $y + 18.5);
            $pdf->SetFont('helvetica', '', 5);
            $pdf->SetTextColor(130, 130, 130);
            $pdf->Cell($halfW, 3, 'RESULT', 0, 1, 'C');

            $pdf->SetLineStyle(['width' => 0.2, 'color' => [221, 228, 237]]);
            $pdf->Line($boxX + $halfW, $y + 12, $boxX + $halfW, $y + $boxH - 1);

            $pdf->SetXY($boxX + $halfW, $y + 13);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetTextColor(...$gRgb);
            $pdf->Cell($halfW, 4.5, $grade, 0, 0, 'C');
            $pdf->SetXY($boxX + $halfW, $y + 18.5);
            $pdf->SetFont('helvetica', '', 5);
            $pdf->SetTextColor(130, 130, 130);
            $pdf->Cell($halfW, 3, 'GRADE', 0, 1, 'C');
        }

        $y += 32;
        $pdf->SetLineStyle(['width' => 0.2, 'color' => [241, 241, 244]]);
        $pdf->Line($sx, $y, $sx + $cw, $y);
        $y += 4;

        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'I', 6.5);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell($cw, 4, 'Score = English + Best ' . $rule['best_n'] . ' subjects (Max ' . $rule['max'] . '). Pass = average per subject ≥ 50.', 0, 1, 'C');
        $y += 7;

        $pdf->SetXY($sx, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell($cw, 5, 'Subject Results', 0, 1, 'L');
        $y += 6;

        $cSub = 88; $cType = 30; $cMark = 30; $cRes = $cw - $cSub - $cType - $cMark;
        $rh   = 7;

        $pdf->SetXY($sx, $y);
        $pdf->SetFillColor(240, 247, 255);
        $pdf->SetDrawColor(200, 220, 240);
        $pdf->SetLineStyle(['width' => 0.3, 'color' => [200, 220, 240]]);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->SetTextColor(26, 86, 219);
        $pdf->Cell($cSub,  $rh, 'Subject',   1, 0, 'L', true);
        $pdf->Cell($cType, $rh, 'Type',      1, 0, 'C', true);
        $pdf->Cell($cMark, $rh, 'Mark /100', 1, 0, 'C', true);
        $pdf->Cell($cRes,  $rh, 'Grade',     1, 1, 'C', true);

        $i = 0;
        foreach ($subjects as $sub) {
            $mark     = (int) $sub['exam_mark'];
            $fill     = ($i % 2 === 0);
            $subGrade = $mark > 0 ? $this->examGrade((float) $mark) : '—';
            $gRgb     = $mark > 0 ? $this->examGradeRgb($subGrade) : [130, 130, 130];

            $pdf->SetFillColor(248, 250, 252);
            $pdf->SetDrawColor(230, 236, 241);
            $pdf->SetFont('helvetica', '', 7.5);
            $pdf->SetTextColor(30, 30, 30);
            $pdf->Cell($cSub, $rh, $sub['subject_name'], 1, 0, 'L', $fill);

            $isCore = $sub['sub_type'] === 'Core';
            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetTextColor($isCore ? 29 : 180, $isCore ? 78 : 83, $isCore ? 216 : 9);
            $pdf->Cell($cType, $rh, $sub['sub_type'], 1, 0, 'C', $fill);

            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->SetTextColor(30, 30, 30);
            $pdf->Cell($cMark, $rh, $mark > 0 ? (string) $mark : '—', 1, 0, 'C', $fill);

            $pdf->SetFont('helvetica', 'B', 7.5);
            $pdf->SetTextColor(...$gRgb);
            $pdf->Cell($cRes, $rh, $subGrade, 1, 1, 'C', $fill);
            $i++;
        }

        restore_error_handler();
        $pdf->Output('exam_student_' . $studentExamId . '_report.pdf', 'I');
        exit;
    }

    // ================================================================
    // GRADE HELPERS
    // ================================================================

    private function examGrade(float $pct): string
    {
        if ($pct >= 80) return 'A';
        if ($pct >= 65) return 'B';
        if ($pct >= 50) return 'C';
        if ($pct >= 40) return 'D';
        return 'F';
    }

    private function examGradeRgb(string $grade): array
    {
        return match($grade) {
            'A'     => [21, 128, 61],
            'B'     => [29, 78, 216],
            'C'     => [7, 89, 133],
            'D'     => [180, 83, 9],
            default => [185, 28, 28],
        };
    }

    // ================================================================
    // STATS HELPERS
    // ================================================================

    /**
     * Determine scoring rule based on the exam's level name.
     * Year 12 & 13 → English + best 3 (max 400).
     * All others   → English + best 5 (max 600).
     */
    private function scoringRule(string $levelName): array
    {
        $bestN = preg_match('/year\s*1[23]/i', $levelName) ? 3 : 5;
        return ['best_n' => $bestN, 'pass_avg' => 50, 'max' => ($bestN + 1) * 100];
    }

    /**
     * Compute an individual student's score from their exam_subject rows.
     * Each row must have 'subject_name' and 'exam_mark'.
     */
    private function calcStudentScore(array $subjects, array $rule): array
    {
        $english = null;
        $others  = [];

        foreach ($subjects as $sub) {
            $mark = (int) $sub['exam_mark'];
            if ($mark <= 0) continue;
            if (stripos($sub['subject_name'], 'english') !== false) {
                $english = $mark;
            } else {
                $others[] = $mark;
            }
        }

        rsort($others);
        $best    = array_slice($others, 0, $rule['best_n']);
        $counted = ($english !== null ? 1 : 0) + count($best);
        $total   = ($english ?? 0) + array_sum($best);
        $avg     = $counted > 0 ? round($total / $counted, 1) : null;

        return [
            'total'   => $counted > 0 ? $total   : null,
            'max'     => $rule['max'],
            'average' => $avg,
            'counted' => $counted,
            'english' => $english,
            'sat'     => $counted > 0,
            'passed'  => $avg !== null && $avg >= $rule['pass_avg'],
        ];
    }

    /**
     * Aggregate stats across all students with their subjects array.
     */
    private function aggregateExamStats(array $studentsData, array $rule): array
    {
        $totals      = [];
        $passed      = 0;
        $subMarks    = [];

        foreach ($studentsData as $sd) {
            $score = $this->calcStudentScore($sd['subjects'], $rule);
            if ($score['sat']) {
                $totals[] = $score['total'];
                if ($score['passed']) $passed++;
            }
            foreach ($sd['subjects'] as $sub) {
                $mark = (int) $sub['exam_mark'];
                if ($mark > 0) {
                    $subMarks[$sub['subject_name']][] = $mark;
                }
            }
        }

        $sat    = count($totals);
        $failed = $sat - $passed;

        // Subject-level stats
        $subjectStats = [];
        foreach ($subMarks as $name => $marks) {
            $subjectStats[] = [
                'subject' => $name,
                'sat'     => count($marks),
                'avg'     => round(array_sum($marks) / count($marks), 1),
                'highest' => max($marks),
                'lowest'  => min($marks),
            ];
        }
        usort($subjectStats, fn($a, $b) => strcmp($a['subject'], $b['subject']));

        // Score distribution in 5 bands
        $bands = ['0–20%' => 0, '21–40%' => 0, '41–60%' => 0, '61–80%' => 0, '81–100%' => 0];
        foreach ($totals as $t) {
            $pct = $rule['max'] > 0 ? ($t / $rule['max']) * 100 : 0;
            if ($pct <= 20)    $bands['0–20%']++;
            elseif ($pct <= 40) $bands['21–40%']++;
            elseif ($pct <= 60) $bands['41–60%']++;
            elseif ($pct <= 80) $bands['61–80%']++;
            else               $bands['81–100%']++;
        }

        return [
            'enrolled'     => count($studentsData),
            'sat'          => $sat,
            'passed'       => $passed,
            'failed'       => $failed,
            'pass_pct'     => $sat > 0 ? round($passed / $sat * 100, 1) : 0,
            'highest'      => $sat > 0 ? max($totals) : null,
            'lowest'       => $sat > 0 ? min($totals) : null,
            'average'      => $sat > 0 ? round(array_sum($totals) / $sat, 1) : null,
            'max_score'    => $rule['max'],
            'best_n'       => $rule['best_n'],
            'subject_stats' => $subjectStats,
            'distribution' => $bands,
        ];
    }

    // ================================================================
    // AJAX — Get active exams for the level that a stream belongs to
    // GET exam/stream/{streamId}/exams
    // ================================================================

    public function getExamsForStream(int $streamId)
    {
        $db = \Config\Database::connect();

        $row = $db->table('stream')
            ->select('sch_level.level_id_fk')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk', 'left')
            ->where('stream.stream_id', $streamId)
            ->get()->getRowArray();

        if (!$row || empty($row['level_id_fk'])) {
            return $this->response->setJSON(['success' => true, 'exams' => []]);
        }

        $exams = $db->table('exam')
            ->select('exam_id, exam_name')
            ->where('level_id_fk', (int) $row['level_id_fk'])
            ->where('exam_status', 'Active')
            ->get()->getResultArray();

        return $this->response->setJSON(['success' => true, 'exams' => $exams]);
    }

    // ================================================================
    // MY EXAMS — Student / Parent / Staff-who-is-parent view
    // ================================================================

    public function my()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('auth/login');
        }

        $userID    = (int) $this->session->get('userID');
        $roleCatID = (int) $this->session->get('roleCatID');

        // roleCatID 4 = Student, 6 = Parent
        $isStudent = ($roleCatID === 4);
        $isParent  = ($roleCatID === 6);

        // Teachers/staff who are also parents get parent view via is_a_parent flag
        $isParentStaff = false;
        if (!$isStudent && !$isParent) {
            if ($this->grant_access('_my_exam')) {
                // role-based permission granted — treat as parent view
                $isParent = true;
            } else {
                $user = $this->userModel->find($userID);
                if (!empty($user['is_a_parent']) && (int) $user['is_a_parent'] === 1) {
                    $isParentStaff = true;
                    $isParent      = true;
                } else {
                    $data['_view'] = 'app/auth/access_control';
                    return view('app/layouts/main', $data);
                }
            }
        }

        $this->setPageData('My Exams', 'Exam', 'My Exams');

        $data['isStudent']     = $isStudent;
        $data['isParent']      = $isParent || $isParentStaff;
        $data['studentExams']  = [];
        $data['enrolment']     = null;
        $data['children']      = [];

        if ($isStudent) {
            $admissions = $this->admissionModel->getAdmissionByUser($userID);
            $admission  = !empty($admissions) ? $admissions[0] : null;
            $enrolment  = $admission
                ? $this->enrolmentModel->where('admission_id_fk', $admission['admission_id'])->where('enrol_status', 'Active')->first()
                : null;

            $data['enrolment']    = $enrolment;
            $data['studentExams'] = $enrolment
                ? $this->studentExamModel->getForEnrolment((int) $enrolment['enrol_id'])
                : [];
        } else {
            // Parent or staff-parent: show all linked children
            $children     = $this->parentStudentModel->getChildrenOf($userID);
            $childrenData = [];

            foreach ($children as $child) {
                $childId    = (int) $child['user_id'];
                $admissions = $this->admissionModel->getAdmissionByUser($childId);
                $admission  = !empty($admissions) ? $admissions[0] : null;
                $enrolment  = $admission
                    ? $this->enrolmentModel->where('admission_id_fk', $admission['admission_id'])->where('enrol_status', 'Active')->first()
                    : null;

                $childrenData[] = [
                    'child'     => $child,
                    'enrolment' => $enrolment,
                    'exams'     => $enrolment
                        ? $this->studentExamModel->getForEnrolment((int) $enrolment['enrol_id'])
                        : [],
                ];
            }

            $data['children'] = $childrenData;
        }

        $data['_view'] = 'app/exam/my';
        return view('app/layouts/main', $data);
    }
}
