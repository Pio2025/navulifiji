<?php

namespace App\Libraries;

use App\Models\ParentStudentModel;

/**
 * Cross-module personal-document aggregator for Doc Manager. Pulls a user's
 * files from personal uploads plus existing source tables (generated
 * references, conduct incident/appeal files, attendance files, medical
 * files, assignment submissions, discussion photos, and wall post media)
 * into one normalized shape, and resolves a single source_type+
 * source_file_id pair back to its owner and physical location for
 * view/download/share actions.
 *
 * Lesson videos, lesson resource files, and assignment question files use
 * broad class-membership access instead of single ownership — see
 * CLASS_ACCESS_SOURCES and canUserAccessClassResource().
 *
 * Session-agnostic by design (mirrors App\Libraries\DashboardStats):
 * ownership/ACL decisions belong to the controller, this class only
 * resolves data.
 */
class DocManagerAggregator
{
    public const SOURCE_PERSONAL       = 'personal';
    public const SOURCE_REFERENCE      = 'reference';
    public const SOURCE_APPEAL         = 'conduct_appeal';
    public const SOURCE_INCIDENT       = 'conduct_incident';
    public const SOURCE_ATTENDANCE     = 'attendance';
    public const SOURCE_MEDICAL        = 'medical';
    public const SOURCE_SUBMISSION     = 'assignment_submission';
    public const SOURCE_VIDEO          = 'video';
    public const SOURCE_LESSON_FILE    = 'lesson_file';
    public const SOURCE_ASSIGNMENT_FILE = 'assignment_file';
    public const SOURCE_DISCUSSION     = 'discussion';
    public const SOURCE_WALL           = 'wall';

    /**
     * Source types that use broad class-membership access (lesson creator,
     * an active co-teacher, an active enrolled student, or a parent of one)
     * rather than a single owner — see canUserAccessClassResource().
     */
    public const CLASS_ACCESS_SOURCES = [self::SOURCE_VIDEO, self::SOURCE_LESSON_FILE, self::SOURCE_ASSIGNMENT_FILE];

    private const FOLDERS = [
        self::SOURCE_PERSONAL        => 'doc_manager',
        self::SOURCE_REFERENCE       => 'reference',
        self::SOURCE_APPEAL          => 'conduct_appeals',
        self::SOURCE_INCIDENT        => 'conduct',
        self::SOURCE_ATTENDANCE      => 'attendance',
        self::SOURCE_MEDICAL         => 'medical',
        self::SOURCE_SUBMISSION      => 'assignment_submissions',
        self::SOURCE_VIDEO           => '',
        self::SOURCE_LESSON_FILE     => 'lesson_files',
        self::SOURCE_ASSIGNMENT_FILE => 'assignments',
        self::SOURCE_DISCUSSION      => 'lesson_discussion',
        self::SOURCE_WALL            => 'wall',
    ];

    private const SOURCE_LABELS = [
        self::SOURCE_PERSONAL        => 'Personal',
        self::SOURCE_REFERENCE       => 'Reference',
        self::SOURCE_APPEAL          => 'Conduct Appeal',
        self::SOURCE_INCIDENT        => 'Conduct Incident',
        self::SOURCE_ATTENDANCE      => 'Attendance',
        self::SOURCE_MEDICAL         => 'Medical',
        self::SOURCE_SUBMISSION      => 'Assignment',
        self::SOURCE_VIDEO           => 'Lesson Video',
        self::SOURCE_LESSON_FILE     => 'Lesson File',
        self::SOURCE_ASSIGNMENT_FILE => 'Assignment Question',
        self::SOURCE_DISCUSSION      => 'Discussion',
        self::SOURCE_WALL            => 'Wall Post',
    ];

    /**
     * Shared WHERE fragment for "is $userId allowed to see this class
     * resource" — its creator (given by $creatorColumn, e.g. 'cl.created_by'
     * or 'la.created_by'), an active subject teacher, an active enrolled
     * student, or a parent of an active enrolled student. Requires aliases
     * `cs` (classroom_subject) in scope; takes the same placeholder
     * ($userId) four times.
     */
    private static function classAccessSql(string $creatorColumn): string
    {
        return "(
            {$creatorColumn} = ?
            OR EXISTS (SELECT 1 FROM classroom_subject_teacher cst WHERE cst.class_sub_id_fk = cs.class_sub_id AND cst.user_id_fk = ? AND cst.class_sub_teacher_status = 'Active')
            OR EXISTS (SELECT 1 FROM classroom_student cstu WHERE cstu.class_id_fk = cs.class_id_fk AND cstu.user_id_fk = ? AND cstu.class_stud_status = 'Active')
            OR EXISTS (
                SELECT 1 FROM parent_student ps
                INNER JOIN classroom_student cstu2 ON cstu2.class_id_fk = cs.class_id_fk AND cstu2.user_id_fk = ps.student_user_id_fk AND cstu2.class_stud_status = 'Active'
                WHERE ps.parent_user_id_fk = ?
            )
        )";
    }

    protected ParentStudentModel $parentStudentModel;

    public function __construct()
    {
        $this->parentStudentModel = new ParentStudentModel();
    }

    public function folderFor(string $sourceType): string
    {
        return self::FOLDERS[$sourceType] ?? 'doc_manager';
    }

    public function labelFor(string $sourceType): string
    {
        return self::SOURCE_LABELS[$sourceType] ?? 'Document';
    }

    public function isValidSourceType(string $sourceType): bool
    {
        return isset(self::FOLDERS[$sourceType]);
    }

    public function isParentOfUser(int $parentUserId, int $studentUserId): bool
    {
        $children = $this->parentStudentModel->getChildrenOf($parentUserId);
        foreach ($children as $child) {
            if ((int) $child['user_id'] === $studentUserId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Every document belonging to a user across all included sources,
     * normalized and sorted newest first. Each row: source_type,
     * source_file_id, file_name, original_name, label, url, created_at,
     * category, icon, color, extension.
     */
    public function getDocumentsForUser(int $userId): array
    {
        $db   = \Config\Database::connect();
        $rows = [];

        // ── personal uploads ────────────────────────────────────────────
        $res = $db->query(
            "SELECT doc_id AS source_file_id, file_name, original_name, description AS label, created_at
             FROM doc_manager_file WHERE user_id_fk = ? ORDER BY doc_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_PERSONAL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], $r['label'] ?: 'Personal', $r['created_at'], $userId);
        }

        // ── generated references ────────────────────────────────────────
        $res = $db->query(
            "SELECT gr.gen_ref_id AS source_file_id, gr.gen_ref_file_name AS file_name,
                    rc.ref_cat_name AS label, CONCAT(gr.gen_ref_date, ' ', gr.gen_ref_time) AS created_at
             FROM generated_reference gr
             LEFT JOIN reference_category rc ON rc.ref_cat_id = gr.ref_cat_id_fk
             WHERE gr.user_id_fk = ? AND gr.gen_ref_file_name IS NOT NULL AND gr.gen_ref_file_name <> ''
             ORDER BY gr.gen_ref_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_REFERENCE, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ?: 'Reference', $r['created_at'], $userId);
        }

        // ── conduct appeal files ────────────────────────────────────────
        $res = $db->query(
            "SELECT caf.appeal_file_id AS source_file_id, caf.file_src AS file_name, ca.submitted_date AS created_at
             FROM conduct_appeal_files caf
             INNER JOIN conduct_appeals ca ON ca.appeal_id = caf.appeal_id
             INNER JOIN admission ad ON ad.admission_id = ca.student_id
             WHERE ad.user_id_fk = ? AND caf.file_src IS NOT NULL AND caf.file_src <> ''
             ORDER BY caf.appeal_file_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_APPEAL, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Conduct Appeal', $r['created_at'], $userId);
        }

        // ── conduct incident files ──────────────────────────────────────
        $res = $db->query(
            "SELECT cif.conduct_file_id AS source_file_id, cif.file_src AS file_name, ci.incident_date AS created_at
             FROM conduct_incident_file cif
             INNER JOIN conduct_incidents ci ON ci.incident_id = cif.incident_id_fk
             INNER JOIN admission ad ON ad.admission_id = ci.student_id
             WHERE ad.user_id_fk = ? AND cif.file_src IS NOT NULL AND cif.file_src <> ''
             ORDER BY cif.conduct_file_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_INCIDENT, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Conduct Incident', $r['created_at'], $userId);
        }

        // ── attendance files ─────────────────────────────────────────────
        $res = $db->query(
            "SELECT saf.stud_att_file_id AS source_file_id, saf.stud_att_file_src AS file_name, sa.attendance_date AS created_at
             FROM student_attendance_file saf
             INNER JOIN student_attendance sa ON sa.stud_att_id = saf.stud_att_id_fk
             INNER JOIN admission ad ON ad.admission_id = sa.admission_id_fk
             WHERE ad.user_id_fk = ? AND saf.stud_att_file_src IS NOT NULL AND saf.stud_att_file_src <> ''
             ORDER BY saf.stud_att_file_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_ATTENDANCE, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Attendance', $r['created_at'], $userId);
        }

        // ── medical files ────────────────────────────────────────────────
        $res = $db->query(
            "SELECT umf.file_id AS source_file_id, umf.file_name AS file_name,
                    umf.file_original_name AS original_name, CONCAT(umf.file_date, ' ', umf.file_time) AS created_at
             FROM user_medical_files umf
             INNER JOIN user_medical um ON um.medical_id = umf.medical_id_fk
             WHERE um.user_id_fk = ? AND umf.file_name IS NOT NULL AND umf.file_name <> ''
             ORDER BY umf.file_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_MEDICAL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Medical', $r['created_at'], $userId);
        }

        // ── assignment submissions ──────────────────────────────────────
        $res = $db->query(
            "SELECT sub.submission_id AS source_file_id, sub.submission_file AS file_name,
                    la.assignment_name AS label, sub.submitted_at AS created_at
             FROM assignment_submission sub
             LEFT JOIN lesson_assignment la ON la.assignment_id = sub.assignment_id_fk
             WHERE sub.user_id_fk = ? AND sub.submission_file IS NOT NULL AND sub.submission_file <> ''
             ORDER BY sub.submission_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_SUBMISSION, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ?: 'Assignment', $r['created_at'], $userId);
        }

        // ── lesson videos (visible via teaching, enrolment, or parentage) ──
        $res = $db->query(
            "SELECT lv.video_id AS source_file_id, lv.video_url AS file_name, lv.video_title AS label, cl.created_at AS created_at
             FROM lesson_video lv
             INNER JOIN classroom_lesson cl ON cl.lesson_id = lv.lesson_id_fk
             INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
             WHERE cl.lesson_status = 'Published' AND " . self::classAccessSql('cl.created_by') . "
             ORDER BY cl.created_at DESC",
            [$userId, $userId, $userId, $userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_VIDEO, $r['source_file_id'], $r['file_name'], $r['label'] ?: 'Lesson Video', $r['label'] ?: 'Lesson Video', $r['created_at'], $userId);
        }

        // ── lesson resource files (visible via teaching, enrolment, or parentage) ──
        $res = $db->query(
            "SELECT lf.file_id AS source_file_id, lf.file_path AS file_name, lf.file_name AS original_name, lf.uploaded_at AS created_at
             FROM lesson_file lf
             INNER JOIN classroom_lesson cl ON cl.lesson_id = lf.lesson_id_fk
             INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
             WHERE cl.lesson_status = 'Published' AND " . self::classAccessSql('cl.created_by') . "
             ORDER BY lf.uploaded_at DESC",
            [$userId, $userId, $userId, $userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_LESSON_FILE, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Lesson File', $r['created_at'], $userId);
        }

        // ── assignment question files (visible via teaching, enrolment, or parentage) ──
        $res = $db->query(
            "SELECT laf.assign_file_id AS source_file_id, laf.file_src AS file_name, la.assignment_name AS label, la.created_at AS created_at
             FROM lesson_assignment_file laf
             INNER JOIN lesson_assignment la ON la.assignment_id = laf.assignment_id_fk
             INNER JOIN classroom_subject cs ON cs.class_sub_id = la.class_sub_id_fk
             WHERE la.assignment_status = 'Published' AND " . self::classAccessSql('la.created_by') . "
             ORDER BY la.created_at DESC",
            [$userId, $userId, $userId, $userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_ASSIGNMENT_FILE, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ? ('Assignment: ' . $r['label']) : 'Assignment Question', $r['created_at'], $userId);
        }

        // ── classroom discussion photos I posted ────────────────────────
        $res = $db->query(
            "SELECT ldp.photo_id AS source_file_id, ldp.photo_path AS file_name, ld.created_at AS created_at
             FROM lesson_discussion_photo ldp
             INNER JOIN lesson_discussion ld ON ld.lesson_discussion_id = ldp.ld_id_fk
             WHERE ld.author = ? AND ld.message_status = 1
             ORDER BY ldp.photo_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_DISCUSSION, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Discussion', $r['created_at'], $userId);
        }

        // ── wall post media I posted ────────────────────────────────────
        $res = $db->query(
            "SELECT wm.wall_media_id AS source_file_id, wm.file_src AS file_name, wm.file_name AS original_name, wm.created_at AS created_at
             FROM wall_media wm
             INNER JOIN wall_post wp ON wp.wall_post_id = wm.wall_post_id_fk
             WHERE wp.user_id_fk = ? AND wp.post_status = 'Active' AND wm.media_type <> 'video_url'
             ORDER BY wm.wall_media_id DESC",
            [$userId]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_WALL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Wall Post', $r['created_at'], $userId);
        }

        usort($rows, fn($a, $b) => strcmp((string) $b['created_at'], (string) $a['created_at']));

        return $rows;
    }

    /**
     * Documents across all sources whose file/label name matches $search,
     * scoped to users belonging to school $schId. Each row has the same
     * shape as getDocumentsForUser() plus owner_user_id/owner_name, so
     * results can link straight to view/download or to the owner's full
     * document list. Capped at 200 rows, newest first.
     */
    public function searchDocumentsBySchool(int $schId, string $search): array
    {
        $db   = \Config\Database::connect();
        $like = '%' . $search . '%';
        $rows = [];

        $inSchool = "IN (SELECT user_id_fk FROM admission WHERE sch_id_fk = ? AND admission_status = 'Active')";

        // ── personal uploads ────────────────────────────────────────────
        $res = $db->query(
            "SELECT dmf.doc_id AS source_file_id, dmf.file_name, dmf.original_name, dmf.description AS label,
                    dmf.created_at, dmf.user_id_fk AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM doc_manager_file dmf
             INNER JOIN users u ON u.user_id = dmf.user_id_fk
             WHERE dmf.user_id_fk {$inSchool}
               AND (dmf.original_name LIKE ? OR dmf.file_name LIKE ? OR dmf.description LIKE ?)
             ORDER BY dmf.doc_id DESC",
            [$schId, $like, $like, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_PERSONAL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], $r['label'] ?: 'Personal', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── generated references ────────────────────────────────────────
        $res = $db->query(
            "SELECT gr.gen_ref_id AS source_file_id, gr.gen_ref_file_name AS file_name, rc.ref_cat_name AS label,
                    CONCAT(gr.gen_ref_date, ' ', gr.gen_ref_time) AS created_at, gr.user_id_fk AS owner_user_id,
                    CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM generated_reference gr
             LEFT JOIN reference_category rc ON rc.ref_cat_id = gr.ref_cat_id_fk
             INNER JOIN users u ON u.user_id = gr.user_id_fk
             WHERE gr.user_id_fk {$inSchool}
               AND gr.gen_ref_file_name IS NOT NULL AND gr.gen_ref_file_name <> ''
               AND (gr.gen_ref_file_name LIKE ? OR rc.ref_cat_name LIKE ?)
             ORDER BY gr.gen_ref_id DESC",
            [$schId, $like, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_REFERENCE, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ?: 'Reference', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── conduct appeal files ────────────────────────────────────────
        $res = $db->query(
            "SELECT caf.appeal_file_id AS source_file_id, caf.file_src AS file_name, ca.submitted_date AS created_at,
                    ad.user_id_fk AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM conduct_appeal_files caf
             INNER JOIN conduct_appeals ca ON ca.appeal_id = caf.appeal_id
             INNER JOIN admission ad ON ad.admission_id = ca.student_id
             INNER JOIN users u ON u.user_id = ad.user_id_fk
             WHERE ad.sch_id_fk = ? AND caf.file_src IS NOT NULL AND caf.file_src <> ''
               AND caf.file_src LIKE ?
             ORDER BY caf.appeal_file_id DESC",
            [$schId, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_APPEAL, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Conduct Appeal', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── conduct incident files ──────────────────────────────────────
        $res = $db->query(
            "SELECT cif.conduct_file_id AS source_file_id, cif.file_src AS file_name, ci.incident_date AS created_at,
                    ad.user_id_fk AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM conduct_incident_file cif
             INNER JOIN conduct_incidents ci ON ci.incident_id = cif.incident_id_fk
             INNER JOIN admission ad ON ad.admission_id = ci.student_id
             INNER JOIN users u ON u.user_id = ad.user_id_fk
             WHERE ad.sch_id_fk = ? AND cif.file_src IS NOT NULL AND cif.file_src <> ''
               AND cif.file_src LIKE ?
             ORDER BY cif.conduct_file_id DESC",
            [$schId, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_INCIDENT, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Conduct Incident', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── attendance files ─────────────────────────────────────────────
        $res = $db->query(
            "SELECT saf.stud_att_file_id AS source_file_id, saf.stud_att_file_src AS file_name, sa.attendance_date AS created_at,
                    ad.user_id_fk AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM student_attendance_file saf
             INNER JOIN student_attendance sa ON sa.stud_att_id = saf.stud_att_id_fk
             INNER JOIN admission ad ON ad.admission_id = sa.admission_id_fk
             INNER JOIN users u ON u.user_id = ad.user_id_fk
             WHERE ad.sch_id_fk = ? AND saf.stud_att_file_src IS NOT NULL AND saf.stud_att_file_src <> ''
               AND saf.stud_att_file_src LIKE ?
             ORDER BY saf.stud_att_file_id DESC",
            [$schId, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_ATTENDANCE, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Attendance', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── medical files ────────────────────────────────────────────────
        $res = $db->query(
            "SELECT umf.file_id AS source_file_id, umf.file_name AS file_name, umf.file_original_name AS original_name,
                    CONCAT(umf.file_date, ' ', umf.file_time) AS created_at, um.user_id_fk AS owner_user_id,
                    CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM user_medical_files umf
             INNER JOIN user_medical um ON um.medical_id = umf.medical_id_fk
             INNER JOIN users u ON u.user_id = um.user_id_fk
             WHERE um.user_id_fk {$inSchool}
               AND umf.file_name IS NOT NULL AND umf.file_name <> ''
               AND (umf.file_original_name LIKE ? OR umf.file_name LIKE ?)
             ORDER BY umf.file_id DESC",
            [$schId, $like, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_MEDICAL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Medical', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── assignment submissions ──────────────────────────────────────
        $res = $db->query(
            "SELECT sub.submission_id AS source_file_id, sub.submission_file AS file_name, la.assignment_name AS label,
                    sub.submitted_at AS created_at, sub.user_id_fk AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM assignment_submission sub
             LEFT JOIN lesson_assignment la ON la.assignment_id = sub.assignment_id_fk
             INNER JOIN users u ON u.user_id = sub.user_id_fk
             WHERE sub.user_id_fk {$inSchool}
               AND sub.submission_file IS NOT NULL AND sub.submission_file <> ''
               AND (sub.submission_file LIKE ? OR la.assignment_name LIKE ?)
             ORDER BY sub.submission_id DESC",
            [$schId, $like, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_SUBMISSION, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ?: 'Assignment', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // Class resources (video/lesson_file/assignment_file) belong to a
        // school through the classroom hierarchy (classroom_subject ->
        // classroom -> stream -> sch_level), not through the creator's own
        // admission record — a teacher can create lesson content for a
        // school without necessarily having an 'Active' admission row there,
        // so scoping by admission here would silently drop their files.
        // This mirrors DashboardStats::schoolAdminStats()'s join chain.

        // ── lesson videos ────────────────────────────────────────────────
        $res = $db->query(
            "SELECT lv.video_id AS source_file_id, lv.video_url AS file_name, lv.video_title AS label, cl.created_at AS created_at,
                    cl.created_by AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM lesson_video lv
             INNER JOIN classroom_lesson cl ON cl.lesson_id = lv.lesson_id_fk
             INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
             INNER JOIN classroom c ON c.class_id = cs.class_id_fk
             INNER JOIN stream s ON s.stream_id = c.stream_id_fk
             INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
             INNER JOIN users u ON u.user_id = cl.created_by
             WHERE sl.sch_id_fk = ? AND lv.video_title LIKE ?
             ORDER BY cl.created_at DESC",
            [$schId, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_VIDEO, $r['source_file_id'], $r['file_name'], $r['label'] ?: 'Lesson Video', $r['label'] ?: 'Lesson Video', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── lesson resource files ────────────────────────────────────────
        $res = $db->query(
            "SELECT lf.file_id AS source_file_id, lf.file_path AS file_name, lf.file_name AS original_name, lf.uploaded_at AS created_at,
                    cl.created_by AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM lesson_file lf
             INNER JOIN classroom_lesson cl ON cl.lesson_id = lf.lesson_id_fk
             INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
             INNER JOIN classroom c ON c.class_id = cs.class_id_fk
             INNER JOIN stream s ON s.stream_id = c.stream_id_fk
             INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
             INNER JOIN users u ON u.user_id = cl.created_by
             WHERE sl.sch_id_fk = ? AND lf.file_name LIKE ?
             ORDER BY lf.uploaded_at DESC",
            [$schId, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_LESSON_FILE, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Lesson File', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── assignment question files ────────────────────────────────────
        $res = $db->query(
            "SELECT laf.assign_file_id AS source_file_id, laf.file_src AS file_name, la.assignment_name AS label, la.created_at AS created_at,
                    la.created_by AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM lesson_assignment_file laf
             INNER JOIN lesson_assignment la ON la.assignment_id = laf.assignment_id_fk
             INNER JOIN classroom_subject cs ON cs.class_sub_id = la.class_sub_id_fk
             INNER JOIN classroom c ON c.class_id = cs.class_id_fk
             INNER JOIN stream s ON s.stream_id = c.stream_id_fk
             INNER JOIN sch_level sl ON sl.sch_level_id = s.sch_level_id_fk
             INNER JOIN users u ON u.user_id = la.created_by
             WHERE sl.sch_id_fk = ? AND (laf.file_src LIKE ? OR la.assignment_name LIKE ?)
             ORDER BY la.created_at DESC",
            [$schId, $like, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_ASSIGNMENT_FILE, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ? ('Assignment: ' . $r['label']) : 'Assignment Question', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── classroom discussion photos ──────────────────────────────────
        $res = $db->query(
            "SELECT ldp.photo_id AS source_file_id, ldp.photo_path AS file_name, ld.created_at AS created_at,
                    ld.author AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM lesson_discussion_photo ldp
             INNER JOIN lesson_discussion ld ON ld.lesson_discussion_id = ldp.ld_id_fk
             INNER JOIN users u ON u.user_id = ld.author
             WHERE ld.author {$inSchool} AND ld.message_status = 1 AND ldp.photo_path LIKE ?
             ORDER BY ldp.photo_id DESC",
            [$schId, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_DISCUSSION, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Discussion', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        // ── wall post media ──────────────────────────────────────────────
        $res = $db->query(
            "SELECT wm.wall_media_id AS source_file_id, wm.file_src AS file_name, wm.file_name AS original_name, wm.created_at AS created_at,
                    wp.user_id_fk AS owner_user_id, CONCAT(u.fname, ' ', u.lname) AS owner_name
             FROM wall_media wm
             INNER JOIN wall_post wp ON wp.wall_post_id = wm.wall_post_id_fk
             INNER JOIN users u ON u.user_id = wp.user_id_fk
             WHERE wp.user_id_fk {$inSchool} AND wp.post_status = 'Active' AND wm.media_type <> 'video_url'
               AND (wm.file_name LIKE ? OR wm.file_src LIKE ?)
             ORDER BY wm.wall_media_id DESC",
            [$schId, $like, $like]
        )->getResultArray();
        foreach ($res as $r) {
            $rows[] = $this->normalize(self::SOURCE_WALL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Wall Post', $r['created_at'], (int) $r['owner_user_id'], $r['owner_name']);
        }

        usort($rows, fn($a, $b) => strcmp((string) $b['created_at'], (string) $a['created_at']));

        return array_slice($rows, 0, 200);
    }

    /**
     * Resolve a single source_type + source_file_id back to its owner and
     * physical file, for view/download/share/ownership checks. Returns null
     * if the row doesn't exist or has no file attached.
     */
    public function resolveFile(string $sourceType, int $sourceFileId): ?array
    {
        $db = \Config\Database::connect();

        switch ($sourceType) {
            case self::SOURCE_PERSONAL:
                $r = $db->query('SELECT doc_id AS source_file_id, user_id_fk AS owner_user_id, file_name, original_name, description AS label, created_at FROM doc_manager_file WHERE doc_id = ?', [$sourceFileId])->getRowArray();
                if (!$r) return null;
                return $this->normalize(self::SOURCE_PERSONAL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], $r['label'] ?: 'Personal', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_REFERENCE:
                $r = $db->query(
                    "SELECT gr.gen_ref_id AS source_file_id, gr.user_id_fk AS owner_user_id, gr.gen_ref_file_name AS file_name,
                            rc.ref_cat_name AS label, CONCAT(gr.gen_ref_date, ' ', gr.gen_ref_time) AS created_at
                     FROM generated_reference gr
                     LEFT JOIN reference_category rc ON rc.ref_cat_id = gr.ref_cat_id_fk
                     WHERE gr.gen_ref_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_REFERENCE, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ?: 'Reference', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_APPEAL:
                $r = $db->query(
                    "SELECT caf.appeal_file_id AS source_file_id, caf.file_src AS file_name, ca.submitted_date AS created_at, ad.user_id_fk AS owner_user_id
                     FROM conduct_appeal_files caf
                     INNER JOIN conduct_appeals ca ON ca.appeal_id = caf.appeal_id
                     INNER JOIN admission ad ON ad.admission_id = ca.student_id
                     WHERE caf.appeal_file_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_APPEAL, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Conduct Appeal', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_INCIDENT:
                $r = $db->query(
                    "SELECT cif.conduct_file_id AS source_file_id, cif.file_src AS file_name, ci.incident_date AS created_at, ad.user_id_fk AS owner_user_id
                     FROM conduct_incident_file cif
                     INNER JOIN conduct_incidents ci ON ci.incident_id = cif.incident_id_fk
                     INNER JOIN admission ad ON ad.admission_id = ci.student_id
                     WHERE cif.conduct_file_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_INCIDENT, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Conduct Incident', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_ATTENDANCE:
                $r = $db->query(
                    "SELECT saf.stud_att_file_id AS source_file_id, saf.stud_att_file_src AS file_name, sa.attendance_date AS created_at, ad.user_id_fk AS owner_user_id
                     FROM student_attendance_file saf
                     INNER JOIN student_attendance sa ON sa.stud_att_id = saf.stud_att_id_fk
                     INNER JOIN admission ad ON ad.admission_id = sa.admission_id_fk
                     WHERE saf.stud_att_file_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_ATTENDANCE, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Attendance', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_MEDICAL:
                $r = $db->query(
                    "SELECT umf.file_id AS source_file_id, umf.file_name AS file_name, umf.file_original_name AS original_name,
                            CONCAT(umf.file_date, ' ', umf.file_time) AS created_at, um.user_id_fk AS owner_user_id
                     FROM user_medical_files umf
                     INNER JOIN user_medical um ON um.medical_id = umf.medical_id_fk
                     WHERE umf.file_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_MEDICAL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Medical', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_SUBMISSION:
                $r = $db->query(
                    "SELECT sub.submission_id AS source_file_id, sub.submission_file AS file_name, sub.user_id_fk AS owner_user_id,
                            la.assignment_name AS label, sub.submitted_at AS created_at
                     FROM assignment_submission sub
                     LEFT JOIN lesson_assignment la ON la.assignment_id = sub.assignment_id_fk
                     WHERE sub.submission_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_SUBMISSION, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ?: 'Assignment', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_VIDEO:
                $r = $db->query(
                    "SELECT lv.video_id AS source_file_id, lv.video_url AS file_name, lv.video_title AS label,
                            cl.created_at AS created_at, cl.created_by AS owner_user_id
                     FROM lesson_video lv
                     INNER JOIN classroom_lesson cl ON cl.lesson_id = lv.lesson_id_fk
                     WHERE lv.video_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_VIDEO, $r['source_file_id'], $r['file_name'], $r['label'] ?: 'Lesson Video', $r['label'] ?: 'Lesson Video', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_LESSON_FILE:
                $r = $db->query(
                    "SELECT lf.file_id AS source_file_id, lf.file_path AS file_name, lf.file_name AS original_name,
                            lf.uploaded_at AS created_at, cl.created_by AS owner_user_id
                     FROM lesson_file lf
                     INNER JOIN classroom_lesson cl ON cl.lesson_id = lf.lesson_id_fk
                     WHERE lf.file_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_LESSON_FILE, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Lesson File', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_ASSIGNMENT_FILE:
                $r = $db->query(
                    "SELECT laf.assign_file_id AS source_file_id, laf.file_src AS file_name, la.assignment_name AS label,
                            la.created_at AS created_at, la.created_by AS owner_user_id
                     FROM lesson_assignment_file laf
                     INNER JOIN lesson_assignment la ON la.assignment_id = laf.assignment_id_fk
                     WHERE laf.assign_file_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_ASSIGNMENT_FILE, $r['source_file_id'], $r['file_name'], $r['file_name'], $r['label'] ? ('Assignment: ' . $r['label']) : 'Assignment Question', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_DISCUSSION:
                $r = $db->query(
                    "SELECT ldp.photo_id AS source_file_id, ldp.photo_path AS file_name, ld.created_at AS created_at, ld.author AS owner_user_id
                     FROM lesson_discussion_photo ldp
                     INNER JOIN lesson_discussion ld ON ld.lesson_discussion_id = ldp.ld_id_fk
                     WHERE ldp.photo_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_DISCUSSION, $r['source_file_id'], $r['file_name'], $r['file_name'], 'Discussion', $r['created_at'], (int) $r['owner_user_id']);

            case self::SOURCE_WALL:
                $r = $db->query(
                    "SELECT wm.wall_media_id AS source_file_id, wm.file_src AS file_name, wm.file_name AS original_name,
                            wm.created_at AS created_at, wp.user_id_fk AS owner_user_id
                     FROM wall_media wm
                     INNER JOIN wall_post wp ON wp.wall_post_id = wm.wall_post_id_fk
                     WHERE wm.wall_media_id = ?", [$sourceFileId]
                )->getRowArray();
                if (!$r || empty($r['file_name'])) return null;
                return $this->normalize(self::SOURCE_WALL, $r['source_file_id'], $r['file_name'], $r['original_name'] ?: $r['file_name'], 'Wall Post', $r['created_at'], (int) $r['owner_user_id']);

            default:
                return null;
        }
    }

    /**
     * Whether $userId (a class resource's creator, an active subject
     * teacher, an active enrolled student, or a parent of one) may access
     * the given class resource. These sources have many legitimate viewers,
     * so unlike owner-based sources this is checked directly rather than
     * via a single owner_user_id comparison. $sourceType must be one of
     * CLASS_ACCESS_SOURCES.
     */
    public function canUserAccessClassResource(string $sourceType, int $userId, int $resourceId): bool
    {
        $db = \Config\Database::connect();

        switch ($sourceType) {
            case self::SOURCE_VIDEO:
                $sql = "SELECT 1 FROM lesson_video lv
                        INNER JOIN classroom_lesson cl ON cl.lesson_id = lv.lesson_id_fk
                        INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
                        WHERE lv.video_id = ? AND " . self::classAccessSql('cl.created_by') . " LIMIT 1";
                break;

            case self::SOURCE_LESSON_FILE:
                $sql = "SELECT 1 FROM lesson_file lf
                        INNER JOIN classroom_lesson cl ON cl.lesson_id = lf.lesson_id_fk
                        INNER JOIN classroom_subject cs ON cs.class_sub_id = cl.class_sub_id_fk
                        WHERE lf.file_id = ? AND " . self::classAccessSql('cl.created_by') . " LIMIT 1";
                break;

            case self::SOURCE_ASSIGNMENT_FILE:
                $sql = "SELECT 1 FROM lesson_assignment_file laf
                        INNER JOIN lesson_assignment la ON la.assignment_id = laf.assignment_id_fk
                        INNER JOIN classroom_subject cs ON cs.class_sub_id = la.class_sub_id_fk
                        WHERE laf.assign_file_id = ? AND " . self::classAccessSql('la.created_by') . " LIMIT 1";
                break;

            default:
                return false;
        }

        $row = $db->query($sql, [$resourceId, $userId, $userId, $userId, $userId])->getRowArray();

        return $row !== null;
    }

    private function normalize(string $sourceType, int $sourceFileId, ?string $fileName, ?string $originalName, string $label, ?string $createdAt, ?int $ownerUserId = null, ?string $ownerName = null): array
    {
        $fileName     = (string) $fileName;
        $originalName = $originalName ?: $fileName;
        $isVideo      = $sourceType === self::SOURCE_VIDEO;

        if ($isVideo) {
            $ext = null;
            [$category, $icon, $color] = ['Video', 'ki-video', 'danger'];
            $url = $fileName;
        } else {
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            [$category, $icon, $color] = $this->classify($ext);
            $url = base_url('uploads/' . $this->folderFor($sourceType) . '/' . $fileName);
        }

        return [
            'source_type'    => $sourceType,
            'source_file_id' => $sourceFileId,
            'owner_user_id'  => $ownerUserId,
            'owner_name'     => $ownerName,
            'file_name'      => $fileName,
            'original_name'  => $originalName,
            'label'          => $label,
            'source_label'   => $this->labelFor($sourceType),
            'url'            => $url,
            'created_at'     => $createdAt,
            'extension'      => $ext,
            'category'       => $category,
            'icon'           => $icon,
            'color'          => $color,
            'is_external'    => $isVideo,
        ];
    }

    /**
     * Classify a file extension into a display category + Keenicons duotone
     * icon + color, matching the icon conventions already used elsewhere
     * (e.g. announcement, attendance, wall, medical, conduct views).
     */
    public function classify(string $ext): array
    {
        $ext = strtolower(ltrim($ext, '.'));

        return match (true) {
            $ext === 'pdf' => ['PDF', 'ki-file-pdf', 'danger'],
            in_array($ext, ['doc', 'docx'], true) => ['Word', 'ki-file-doc', 'primary'],
            in_array($ext, ['xls', 'xlsx', 'csv'], true) => ['Excel', 'ki-file-sheet', 'success'],
            in_array($ext, ['ppt', 'pptx'], true) => ['PowerPoint', 'ki-file-up', 'warning'],
            in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'], true) => ['Image', 'ki-picture', 'info'],
            in_array($ext, ['zip', 'rar', '7z'], true) => ['Other', 'ki-file-down', 'dark'],
            $ext === '' => ['Other', 'ki-file', 'secondary'],
            default => ['Other', 'ki-file', 'secondary'],
        };
    }
}
