<?php
namespace App\Models;
use CodeIgniter\Model;

class TermExamModel extends Model
{
    protected $table      = 'term_exam_mark';
    protected $primaryKey = 'temark_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'class_sub_id_fk', 'class_id_fk', 'student_id_fk',
        'term', 'term_exam_id_fk', 'mark', 'total_mark', 'teacher_comment',
        'entered_by', 'created_at', 'updated_at',
    ];

    // ── Table creation ────────────────────────────────────────────────

    public function ensureTables(): void
    {
        $db    = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('term_exam_def')) {
            $forge->addField([
                'term_exam_id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'class_id_fk'  => ['type' => 'INT', 'null' => false],
                'term'         => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false],
                'exam_name'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
                'sort_order'   => ['type' => 'TINYINT', 'unsigned' => true, 'default' => 1],
                'created_by'   => ['type' => 'INT', 'null' => false, 'default' => 0],
                'created_at'   => ['type' => 'DATETIME', 'null' => true],
                'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addPrimaryKey('term_exam_id');
            $forge->createTable('term_exam_def', true);
        }

        if (!$db->tableExists('term_exam_mark')) {
            $forge->addField([
                'temark_id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'class_sub_id_fk' => ['type' => 'INT', 'null' => false],
                'class_id_fk'     => ['type' => 'INT', 'null' => false],
                'student_id_fk'   => ['type' => 'INT', 'null' => false],
                'term'            => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false],
                'mark'            => ['type' => 'DECIMAL', 'constraint' => '6,2', 'null' => true],
                'total_mark'      => ['type' => 'DECIMAL', 'constraint' => '6,2', 'default' => '100.00'],
                'is_absent'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'teacher_comment' => ['type' => 'TEXT', 'null' => true],
                'entered_by'      => ['type' => 'INT', 'null' => false],
                'created_at'      => ['type' => 'DATETIME', 'null' => true],
                'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addPrimaryKey('temark_id');
            $forge->addUniqueKey(['class_sub_id_fk', 'student_id_fk', 'term']);
            $forge->createTable('term_exam_mark', true);
        }

        // Migrate: add is_absent if missing
        if ($db->tableExists('term_exam_mark') && !$db->fieldExists('is_absent', 'term_exam_mark')) {
            $db->query("ALTER TABLE `term_exam_mark` ADD COLUMN `is_absent` TINYINT(1) NOT NULL DEFAULT 0 AFTER `total_mark`");
        }

        // Migrate: add term_exam_id_fk and update unique key
        if ($db->tableExists('term_exam_mark') && !$db->fieldExists('term_exam_id_fk', 'term_exam_mark')) {
            $db->query("ALTER TABLE `term_exam_mark` ADD COLUMN `term_exam_id_fk` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `term`");

            // Backfill: create one default exam def per (class_id_fk, term) and link existing marks
            $termNames = [1 => 'Term 1 End Examination', 2 => 'Term 2 End Examination', 3 => 'Term 3 End Examination'];
            $pairs = $db->query("SELECT DISTINCT class_id_fk, term FROM term_exam_mark")->getResultArray();
            $now = date('Y-m-d H:i:s');
            foreach ($pairs as $p) {
                // Check if an exam def already exists for this class+term
                $exists = $db->table('term_exam_def')
                    ->where('class_id_fk', $p['class_id_fk'])->where('term', $p['term'])
                    ->countAllResults();
                if (!$exists) {
                    $db->table('term_exam_def')->insert([
                        'class_id_fk' => $p['class_id_fk'],
                        'term'        => $p['term'],
                        'exam_name'   => $termNames[(int)$p['term']] ?? "Term {$p['term']} End Examination",
                        'sort_order'  => 1,
                        'created_by'  => 0,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]);
                    $newId = $db->insertID();
                    $db->query(
                        "UPDATE term_exam_mark SET term_exam_id_fk = ? WHERE class_id_fk = ? AND term = ? AND term_exam_id_fk = 0",
                        [$newId, $p['class_id_fk'], $p['term']]
                    );
                }
            }

            // Drop old unique key (CI4 forge names it after the first field) and add new one
            try {
                $db->query("ALTER TABLE `term_exam_mark` DROP INDEX `class_sub_id_fk`");
            } catch (\Exception $e) {
                // Key may have a different name — ignore and proceed
            }
            try {
                $db->query("ALTER TABLE `term_exam_mark` ADD UNIQUE KEY `uq_exam_mark` (`class_sub_id_fk`, `student_id_fk`, `term`, `term_exam_id_fk`)");
            } catch (\Exception $e) {
                // May already exist
            }
        }

        if (!$db->tableExists('term_report_ct_comment')) {
            $forge->addField([
                'ctc_id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'class_id_fk'   => ['type' => 'INT', 'null' => false],
                'student_id_fk' => ['type' => 'INT', 'null' => false],
                'term'          => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false],
                'comment'       => ['type' => 'TEXT', 'null' => false],
                'by_user_id'    => ['type' => 'INT', 'null' => false],
                'created_at'    => ['type' => 'DATETIME', 'null' => true],
                'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addPrimaryKey('ctc_id');
            $forge->addUniqueKey(['class_id_fk', 'student_id_fk', 'term']);
            $forge->createTable('term_report_ct_comment', true);
        }

        if (!$db->tableExists('term_report_principal_comment')) {
            $forge->addField([
                'prc_id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'class_id_fk'   => ['type' => 'INT', 'null' => false],
                'student_id_fk' => ['type' => 'INT', 'null' => false],
                'term'          => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false],
                'comment'       => ['type' => 'TEXT', 'null' => false],
                'by_user_id'    => ['type' => 'INT', 'null' => false],
                'created_at'    => ['type' => 'DATETIME', 'null' => true],
                'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addPrimaryKey('prc_id');
            $forge->addUniqueKey(['class_id_fk', 'student_id_fk', 'term']);
            $forge->createTable('term_report_principal_comment', true);
        }

        if (!$db->tableExists('term_report_status')) {
            $forge->addField([
                'trs_id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'class_id_fk'     => ['type' => 'INT', 'null' => false],
                'term'            => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false],
                'status'          => ['type' => 'ENUM', 'constraint' => ['collecting', 'ct_submitted', 'published'], 'default' => 'collecting'],
                'ct_submitted_by' => ['type' => 'INT', 'null' => true],
                'ct_submitted_at' => ['type' => 'DATETIME', 'null' => true],
                'published_by'    => ['type' => 'INT', 'null' => true],
                'published_at'    => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addPrimaryKey('trs_id');
            $forge->addUniqueKey(['class_id_fk', 'term']);
            $forge->createTable('term_report_status', true);
        }
    }

    // ── Report status ─────────────────────────────────────────────────

    public function getReportStatus(int $classId, int $term): array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('term_report_status')
            ->where('class_id_fk', $classId)->where('term', $term)
            ->get()->getRowArray();
        if (!$row) {
            $db->table('term_report_status')->insert([
                'class_id_fk' => $classId,
                'term'        => $term,
                'status'      => 'collecting',
            ]);
            return ['class_id_fk' => $classId, 'term' => $term, 'status' => 'collecting',
                    'ct_submitted_by' => null, 'ct_submitted_at' => null,
                    'published_by' => null, 'published_at' => null];
        }
        return $row;
    }

    // ── Subject teacher: marks per subject/term ───────────────────────

    public function getMarksForSubjectTerm(int $classSubId, int $term, int $termExamId = 0): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT tem.temark_id, tem.student_id_fk, tem.mark, tem.total_mark,
                   tem.is_absent, tem.teacher_comment, tem.entered_by, tem.updated_at,
                   CONCAT(u.fname,' ',u.lname) AS student_name, u.profile_photo
            FROM term_exam_mark tem
            INNER JOIN users u ON u.user_id = tem.student_id_fk
            WHERE tem.class_sub_id_fk = ? AND tem.term = ? AND tem.term_exam_id_fk = ?
            ORDER BY u.lname, u.fname
        ", [$classSubId, $term, $termExamId])->getResultArray();
    }

    // ── Exam definition management ────────────────────────────────

    public function getExamsForClass(int $classId, int $term): array
    {
        $db = \Config\Database::connect();
        return $db->table('term_exam_def')
            ->where('class_id_fk', $classId)
            ->where('term', $term)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('term_exam_id', 'ASC')
            ->get()->getResultArray();
    }

    public function getOrCreateDefaultExam(int $classId, int $term, int $userId): int
    {
        $db = \Config\Database::connect();
        $existing = $db->table('term_exam_def')
            ->where('class_id_fk', $classId)->where('term', $term)
            ->orderBy('sort_order', 'ASC')->limit(1)->get()->getRowArray();
        if ($existing) return (int) $existing['term_exam_id'];

        $termNames = [1 => 'Term 1 End Examination', 2 => 'Term 2 End Examination', 3 => 'Term 3 End Examination'];
        $now = date('Y-m-d H:i:s');
        $db->table('term_exam_def')->insert([
            'class_id_fk' => $classId,
            'term'        => $term,
            'exam_name'   => $termNames[$term] ?? "Term $term End Examination",
            'sort_order'  => 1,
            'created_by'  => $userId,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
        return (int) $db->insertID();
    }

    public function createExam(int $classId, int $term, string $name, int $userId): int
    {
        $db = \Config\Database::connect();
        $maxOrder = (int) ($db->table('term_exam_def')
            ->selectMax('sort_order', 'max_ord')
            ->where('class_id_fk', $classId)->where('term', $term)
            ->get()->getRowArray()['max_ord'] ?? 0);
        $now = date('Y-m-d H:i:s');
        $db->table('term_exam_def')->insert([
            'class_id_fk' => $classId,
            'term'        => $term,
            'exam_name'   => $name,
            'sort_order'  => $maxOrder + 1,
            'created_by'  => $userId,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
        return (int) $db->insertID();
    }

    public function renameExam(int $termExamId, string $name): void
    {
        \Config\Database::connect()->table('term_exam_def')
            ->where('term_exam_id', $termExamId)
            ->update(['exam_name' => $name, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function deleteExam(int $termExamId): bool
    {
        $db = \Config\Database::connect();
        $def = $db->table('term_exam_def')->where('term_exam_id', $termExamId)->get()->getRowArray();
        if (!$def) return false;

        $count = $db->table('term_exam_def')
            ->where('class_id_fk', $def['class_id_fk'])->where('term', $def['term'])
            ->countAllResults();
        if ($count <= 1) return false; // cannot delete the last exam

        $db->table('term_exam_mark')->where('term_exam_id_fk', $termExamId)->delete();
        $db->table('term_exam_def')->where('term_exam_id', $termExamId)->delete();
        return true;
    }

    public function saveExamMark(int $classSubId, int $classId, int $studentId, int $term,
                                  ?float $mark, float $total, ?string $comment, int $userId,
                                  int $isAbsent = 0, int $termExamId = 0): void
    {
        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $existing = $db->table('term_exam_mark')
            ->where('class_sub_id_fk', $classSubId)
            ->where('student_id_fk', $studentId)
            ->where('term', $term)
            ->where('term_exam_id_fk', $termExamId)
            ->get()->getRowArray();

        $row = [
            'mark'            => $isAbsent ? null : $mark,
            'total_mark'      => $total > 0 ? $total : 100,
            'is_absent'       => $isAbsent ? 1 : 0,
            'teacher_comment' => $comment ?: null,
            'entered_by'      => $userId,
            'updated_at'      => $now,
        ];

        if ($existing) {
            $db->table('term_exam_mark')->where('temark_id', $existing['temark_id'])->update($row);
        } else {
            $db->table('term_exam_mark')->insert(array_merge($row, [
                'class_sub_id_fk'  => $classSubId,
                'class_id_fk'      => $classId,
                'student_id_fk'    => $studentId,
                'term'             => $term,
                'term_exam_id_fk'  => $termExamId,
                'created_at'       => $now,
            ]));
        }
    }

    // ── Class teacher review: all marks for all students ─────────────

    public function getAllMarksForClassTerm(int $classId, int $term): array
    {
        $db = \Config\Database::connect();

        // Get all active students
        $students = $db->query("
            SELECT u.user_id, u.fname, u.lname, u.profile_photo
            FROM classroom_student cs
            INNER JOIN users u ON u.user_id = cs.user_id_fk
            WHERE cs.class_id_fk = ? AND cs.class_stud_status = 'Active'
            ORDER BY u.lname, u.fname
        ", [$classId])->getResultArray();

        // Get all subjects for the class
        $subjects = $db->query("
            SELECT cs.class_sub_id, cs.sub_id_fk AS sch_sub_id,
                   sub.subject_name,
                   CONCAT(ut.fname,' ',ut.lname) AS teacher_name
            FROM classroom_subject cs
            INNER JOIN sch_subject ss ON ss.sch_sub_id = cs.sub_id_fk
            INNER JOIN subject sub ON sub.subject_id = ss.subject_id_fk
            LEFT JOIN classroom_subject_teacher cst ON cst.class_sub_id_fk = cs.class_sub_id
                AND cst.class_sub_teacher_status = 'Active'
            LEFT JOIN users ut ON ut.user_id = cst.user_id_fk
            WHERE cs.class_id_fk = ?
            ORDER BY sub.subject_name
        ", [$classId])->getResultArray();

        // Get all marks for this class/term — aggregate across all exams per subject/student
        $allMarks = $db->query("
            SELECT class_sub_id_fk, student_id_fk,
                   CASE WHEN COUNT(CASE WHEN is_absent=0 AND mark IS NOT NULL THEN 1 END) > 0
                        THEN SUM(CASE WHEN is_absent=0 AND mark IS NOT NULL THEN mark ELSE 0 END)
                        ELSE NULL
                   END AS mark,
                   SUM(total_mark) AS total_mark,
                   CASE WHEN COUNT(*) = SUM(is_absent) THEN 1 ELSE 0 END AS is_absent,
                   NULL AS teacher_comment
            FROM term_exam_mark
            WHERE class_id_fk = ? AND term = ?
            GROUP BY class_sub_id_fk, student_id_fk
        ", [$classId, $term])->getResultArray();

        // Index marks by [class_sub_id][student_id]
        $markIndex = [];
        foreach ($allMarks as $m) {
            $markIndex[$m['class_sub_id_fk']][$m['student_id_fk']] = $m;
        }

        // Get CT comments
        $ctComments = $db->query("
            SELECT tcc.student_id_fk, tcc.comment, tcc.ctc_id
            FROM term_report_ct_comment tcc
            WHERE tcc.class_id_fk = ? AND tcc.term = ?
        ", [$classId, $term])->getResultArray();
        $ctIndex = array_column($ctComments, null, 'student_id_fk');

        // Get principal comments
        $pComments = $db->query("
            SELECT tpc.student_id_fk, tpc.comment, tpc.prc_id
            FROM term_report_principal_comment tpc
            WHERE tpc.class_id_fk = ? AND tpc.term = ?
        ", [$classId, $term])->getResultArray();
        $pIndex = array_column($pComments, null, 'student_id_fk');

        // Build per-student subject map from student_subject enrollment
        $ssRows = $db->query("
            SELECT ss.sch_sub_id_fk, a.user_id_fk AS user_id
            FROM student_subject ss
            INNER JOIN admission a ON a.admission_id = ss.admission_id_fk
            WHERE ss.class_id_fk = ? AND ss.stud_sub_status = 'Active'
        ", [$classId])->getResultArray();
        $studentSubjectMap = [];
        foreach ($ssRows as $r) {
            $studentSubjectMap[(int)$r['user_id']][] = (int)$r['sch_sub_id_fk'];
        }

        // Build student rows with their marks (filtered to enrolled subjects)
        foreach ($students as &$stu) {
            $sid = $stu['user_id'];
            $stu['subjects']          = [];
            $stu['total_earned']      = 0;
            $stu['total_possible']    = 0;
            $stu['subjects_entered']  = 0;
            $stu['ct_comment']        = $ctIndex[$sid]['comment'] ?? null;
            $stu['ctc_id']            = $ctIndex[$sid]['ctc_id']  ?? null;
            $stu['principal_comment'] = $pIndex[$sid]['comment']  ?? null;
            $stu['prc_id']            = $pIndex[$sid]['prc_id']   ?? null;

            // Subjects this student is enrolled in (null = no enrollment data → show all)
            $stuSchSubIds = $studentSubjectMap[$sid] ?? null;

            foreach ($subjects as $sub) {
                if ($stuSchSubIds !== null && !in_array((int)$sub['sch_sub_id'], $stuSchSubIds)) {
                    continue;
                }
                $csid   = $sub['class_sub_id'];
                $m      = $markIndex[$csid][$sid] ?? null;
                $absent = $m && (int)($m['is_absent'] ?? 0) === 1;
                $stu['subjects'][] = [
                    'class_sub_id'    => $csid,
                    'subject_name'    => $sub['subject_name'],
                    'teacher_name'    => $sub['teacher_name'],
                    'mark'            => $m ? $m['mark']            : null,
                    'total_mark'      => $m ? $m['total_mark']      : 100,
                    'is_absent'       => $absent,
                    'teacher_comment' => $m ? $m['teacher_comment'] : null,
                ];
                if ($m && ($m['mark'] !== null || $absent)) {
                    if (!$absent) {
                        $stu['total_earned']   += (float) $m['mark'];
                        $stu['total_possible'] += (float) $m['total_mark'];
                    }
                    $stu['subjects_entered']++;
                }
            }

            $stu['overall_pct']    = $stu['total_possible'] > 0
                ? round(($stu['total_earned'] / $stu['total_possible']) * 100, 1) : null;
            $stu['subjects_count'] = count($stu['subjects']);
        }

        return ['students' => $students, 'subjects' => $subjects];
    }

    // ── CT comment ────────────────────────────────────────────────────

    public function saveCtComment(int $classId, int $studentId, int $term, string $comment, int $userId): void
    {
        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $existing = $db->table('term_report_ct_comment')
            ->where('class_id_fk', $classId)->where('student_id_fk', $studentId)->where('term', $term)
            ->get()->getRowArray();
        $row = ['comment' => $comment, 'by_user_id' => $userId, 'updated_at' => $now];
        if ($existing) {
            $db->table('term_report_ct_comment')->where('ctc_id', $existing['ctc_id'])->update($row);
        } else {
            $db->table('term_report_ct_comment')->insert(array_merge($row, [
                'class_id_fk' => $classId, 'student_id_fk' => $studentId,
                'term' => $term, 'created_at' => $now,
            ]));
        }
    }

    public function submitToPrincipal(int $classId, int $term, int $userId): void
    {
        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $existing = $db->table('term_report_status')
            ->where('class_id_fk', $classId)->where('term', $term)->get()->getRowArray();
        $row = ['status' => 'ct_submitted', 'ct_submitted_by' => $userId, 'ct_submitted_at' => $now];
        if ($existing) {
            $db->table('term_report_status')->where('trs_id', $existing['trs_id'])->update($row);
        } else {
            $db->table('term_report_status')->insert(array_merge($row, [
                'class_id_fk' => $classId, 'term' => $term,
            ]));
        }
    }

    // ── Principal comment + publish ───────────────────────────────────

    public function savePrincipalComment(int $classId, int $studentId, int $term, string $comment, int $userId): void
    {
        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $existing = $db->table('term_report_principal_comment')
            ->where('class_id_fk', $classId)->where('student_id_fk', $studentId)->where('term', $term)
            ->get()->getRowArray();
        $row = ['comment' => $comment, 'by_user_id' => $userId, 'updated_at' => $now];
        if ($existing) {
            $db->table('term_report_principal_comment')->where('prc_id', $existing['prc_id'])->update($row);
        } else {
            $db->table('term_report_principal_comment')->insert(array_merge($row, [
                'class_id_fk' => $classId, 'student_id_fk' => $studentId,
                'term' => $term, 'created_at' => $now,
            ]));
        }
    }

    public function publishReport(int $classId, int $term, int $userId): void
    {
        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $existing = $db->table('term_report_status')
            ->where('class_id_fk', $classId)->where('term', $term)->get()->getRowArray();
        $row = ['status' => 'published', 'published_by' => $userId, 'published_at' => $now];
        if ($existing) {
            $db->table('term_report_status')->where('trs_id', $existing['trs_id'])->update($row);
        } else {
            $db->table('term_report_status')->insert(array_merge($row, [
                'class_id_fk' => $classId, 'term' => $term, 'status' => 'published',
            ]));
        }
    }

    // ── Student report card data ──────────────────────────────────────

    public function getStudentReport(int $classId, int $studentId, int $term): array
    {
        $db = \Config\Database::connect();

        $marks = $db->query("
            SELECT sub.subject_name,
                   CONCAT(ut.fname,' ',ut.lname) AS teacher_name,
                   CASE WHEN COUNT(CASE WHEN tem.is_absent=0 AND tem.mark IS NOT NULL THEN 1 END) > 0
                        THEN SUM(CASE WHEN tem.is_absent=0 AND tem.mark IS NOT NULL THEN tem.mark ELSE 0 END)
                        ELSE NULL
                   END AS mark,
                   SUM(tem.total_mark) AS total_mark,
                   CASE WHEN COUNT(*) = SUM(tem.is_absent) THEN 1 ELSE 0 END AS is_absent,
                   NULL AS teacher_comment
            FROM term_exam_mark tem
            INNER JOIN classroom_subject cs ON cs.class_sub_id = tem.class_sub_id_fk
            INNER JOIN sch_subject ss ON ss.sch_sub_id = cs.sub_id_fk
            INNER JOIN subject sub ON sub.subject_id = ss.subject_id_fk
            LEFT JOIN classroom_subject_teacher cst ON cst.class_sub_id_fk = cs.class_sub_id
                AND cst.class_sub_teacher_status = 'Active'
            LEFT JOIN users ut ON ut.user_id = cst.user_id_fk
            WHERE tem.class_id_fk = ? AND tem.student_id_fk = ? AND tem.term = ?
            GROUP BY cs.class_sub_id, sub.subject_name, ut.fname, ut.lname
            ORDER BY sub.subject_name
        ", [$classId, $studentId, $term])->getResultArray();

        $ctComment = $db->table('term_report_ct_comment')
            ->where('class_id_fk', $classId)->where('student_id_fk', $studentId)->where('term', $term)
            ->get()->getRowArray();

        $pComment = $db->table('term_report_principal_comment')
            ->where('class_id_fk', $classId)->where('student_id_fk', $studentId)->where('term', $term)
            ->get()->getRowArray();

        $status = $db->table('term_report_status')
            ->where('class_id_fk', $classId)->where('term', $term)
            ->get()->getRowArray();

        $totalEarned = $totalPossible = 0;
        foreach ($marks as $m) {
            if ($m['mark'] !== null) {
                $totalEarned   += (float) $m['mark'];
                $totalPossible += (float) $m['total_mark'];
            }
        }

        return [
            'marks'             => $marks,
            'ct_comment'        => $ctComment['comment']  ?? null,
            'principal_comment' => $pComment['comment']   ?? null,
            'status'            => $status['status']      ?? 'collecting',
            'published_at'      => $status['published_at'] ?? null,
            'total_earned'      => $totalEarned,
            'total_possible'    => $totalPossible,
            'overall_pct'       => $totalPossible > 0 ? round(($totalEarned / $totalPossible) * 100, 1) : null,
        ];
    }

    // ── Class statistics for report card ─────────────────────────────

    public function getClassStats(int $classId, int $term, int $studentId): array
    {
        $db = \Config\Database::connect();

        // Total enrolled
        $enrolled = (int) $db->query(
            "SELECT COUNT(*) AS c FROM classroom_student WHERE class_id_fk = ? AND class_stud_status = 'Active'",
            [$classId]
        )->getRowArray()['c'];

        // Aggregate per student: earned marks, possible marks, absent subjects
        $rows = $db->query("
            SELECT tem.student_id_fk,
                   SUM(CASE WHEN tem.is_absent = 0 AND tem.mark IS NOT NULL THEN tem.mark      ELSE 0 END) AS earned,
                   SUM(CASE WHEN tem.is_absent = 0 AND tem.mark IS NOT NULL THEN tem.total_mark ELSE 0 END) AS possible,
                   SUM(CASE WHEN tem.is_absent = 1 THEN 1 ELSE 0 END) AS absent_count,
                   COUNT(*) AS entries
            FROM term_exam_mark tem
            WHERE tem.class_id_fk = ? AND tem.term = ?
            GROUP BY tem.student_id_fk
        ", [$classId, $term])->getResultArray();

        $students = [];
        foreach ($rows as $r) {
            $pct = ($r['possible'] > 0) ? round(($r['earned'] / $r['possible']) * 100, 2) : null;
            $students[(int)$r['student_id_fk']] = [
                'pct'     => $pct,
                'absent'  => (int)$r['absent_count'] > 0,
                'entries' => (int)$r['entries'],
            ];
        }

        // Rank by percentage descending (standard competition ranking with ties)
        $ranked = array_filter($students, fn($s) => $s['pct'] !== null);
        arsort($ranked);   // sort by value — but array, so need manual sort
        $sorted = [];
        foreach ($ranked as $sid => $s) {
            $sorted[] = ['sid' => $sid, 'pct' => $s['pct']];
        }
        usort($sorted, fn($a, $b) => $b['pct'] <=> $a['pct']);

        // Assign ranks (ties share same rank)
        $ranks    = [];
        $rank     = 1;
        $prevPct  = null;
        $prevRank = 1;
        foreach ($sorted as $i => $s) {
            if ($prevPct !== null && $s['pct'] < $prevPct) {
                $prevRank = $rank;
            }
            $ranks[$s['sid']] = $prevRank;
            $rank++;
            $prevPct = $s['pct'];
        }

        $position     = $ranks[$studentId] ?? null;
        $totalRanked  = count($sorted);
        $numberSat    = count($rows);
        $numberPass   = count(array_filter($students, fn($s) => $s['pct'] !== null && $s['pct'] >= 50));
        $numberFail   = count(array_filter($students, fn($s) => $s['pct'] !== null && $s['pct'] < 50));
        $numberAbsent = count(array_filter($students, fn($s) => $s['absent']));
        $pctPass      = $numberSat > 0 ? round(($numberPass / $numberSat) * 100, 1) : 0;
        $avgScore     = $totalRanked > 0
            ? round(array_sum(array_column($sorted, 'pct')) / $totalRanked, 1)
            : null;

        return [
            'enrolled'      => $enrolled,
            'number_sat'    => $numberSat,
            'number_pass'   => $numberPass,
            'number_fail'   => $numberFail,
            'number_absent' => $numberAbsent,
            'pct_pass'      => $pctPass,
            'avg_score'     => $avgScore,
            'position'      => $position,
            'total_ranked'  => $totalRanked,
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public static function grade(float $pct): string
    {
        if ($pct >= 90) return 'A+';
        if ($pct >= 80) return 'A';
        if ($pct >= 70) return 'B';
        if ($pct >= 50) return 'C';
        return 'F';
    }

    public static function gradeColor(string $grade): string
    {
        return match(true) {
            str_starts_with($grade, 'A') => 'success',
            $grade === 'B'               => 'primary',
            $grade === 'C'               => 'info',
            default                      => 'danger',
        };
    }
}
