<?php

namespace App\Libraries;

use App\Models\ParentStudentModel;

/**
 * Cross-module personal-document aggregator for Doc Manager. Pulls a user's
 * files from personal uploads plus five other existing source tables
 * (generated references, conduct incident/appeal files, attendance files,
 * medical files, and assignment submissions) into one normalized shape, and
 * resolves a single source_type+source_file_id pair back to its owner and
 * physical location for view/download/share actions.
 *
 * `lesson_assignment_file` is deliberately excluded — it's shared class
 * material owned by a teacher's class, not a single user's personal document.
 *
 * Session-agnostic by design (mirrors App\Libraries\DashboardStats):
 * ownership/ACL decisions belong to the controller, this class only
 * resolves data.
 */
class DocManagerAggregator
{
    public const SOURCE_PERSONAL    = 'personal';
    public const SOURCE_REFERENCE   = 'reference';
    public const SOURCE_APPEAL      = 'conduct_appeal';
    public const SOURCE_INCIDENT    = 'conduct_incident';
    public const SOURCE_ATTENDANCE  = 'attendance';
    public const SOURCE_MEDICAL     = 'medical';
    public const SOURCE_SUBMISSION  = 'assignment_submission';

    private const FOLDERS = [
        self::SOURCE_PERSONAL   => 'doc_manager',
        self::SOURCE_REFERENCE  => 'reference',
        self::SOURCE_APPEAL     => 'conduct_appeals',
        self::SOURCE_INCIDENT   => 'conduct',
        self::SOURCE_ATTENDANCE => 'attendance',
        self::SOURCE_MEDICAL    => 'medical',
        self::SOURCE_SUBMISSION => 'assignment_submissions',
    ];

    private const SOURCE_LABELS = [
        self::SOURCE_PERSONAL   => 'Personal',
        self::SOURCE_REFERENCE  => 'Reference',
        self::SOURCE_APPEAL     => 'Conduct Appeal',
        self::SOURCE_INCIDENT   => 'Conduct Incident',
        self::SOURCE_ATTENDANCE => 'Attendance',
        self::SOURCE_MEDICAL    => 'Medical',
        self::SOURCE_SUBMISSION => 'Assignment',
    ];

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

        usort($rows, fn($a, $b) => strcmp((string) $b['created_at'], (string) $a['created_at']));

        return $rows;
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

            default:
                return null;
        }
    }

    private function normalize(string $sourceType, int $sourceFileId, ?string $fileName, ?string $originalName, string $label, ?string $createdAt, ?int $ownerUserId = null): array
    {
        $fileName     = (string) $fileName;
        $originalName = $originalName ?: $fileName;
        $ext          = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        [$category, $icon, $color] = $this->classify($ext);

        return [
            'source_type'    => $sourceType,
            'source_file_id' => $sourceFileId,
            'owner_user_id'  => $ownerUserId,
            'file_name'      => $fileName,
            'original_name'  => $originalName,
            'label'          => $label,
            'source_label'   => $this->labelFor($sourceType),
            'url'            => base_url('uploads/' . $this->folderFor($sourceType) . '/' . $fileName),
            'created_at'     => $createdAt,
            'extension'      => $ext,
            'category'       => $category,
            'icon'           => $icon,
            'color'          => $color,
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
            in_array($ext, ['zip', 'rar', '7z'], true) => ['Archive', 'ki-file-down', 'dark'],
            $ext === '' => ['Other', 'ki-file', 'secondary'],
            default => ['Other', 'ki-file', 'secondary'],
        };
    }
}
