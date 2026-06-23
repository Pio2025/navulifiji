<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectFeedbackModel extends Model
{
    protected $table      = 'subject_feedback';
    protected $primaryKey = 'feedback_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'class_sub_id_fk', 'class_id_fk', 'student_id_fk', 'teacher_id_fk',
        'sch_sub_id_fk', 'overall_rating', 'teaching_rating', 'content_rating',
        'engagement_rating', 'comment', 'is_anonymous', 'created_at', 'updated_at',
    ];

    public function getStudentFeedback(int $classSubId, int $studentId): ?array
    {
        return $this->where('class_sub_id_fk', $classSubId)
                    ->where('student_id_fk', $studentId)
                    ->first();
    }

    public function upsert(array $data, ?int $existingId = null): bool
    {
        $now = date('Y-m-d H:i:s');
        if ($existingId) {
            $data['updated_at'] = $now;
            return $this->update($existingId, $data);
        }
        $data['created_at'] = $now;
        $data['updated_at'] = $now;
        return $this->insert($data) !== false;
    }

    public function getSubjectAverages(int $classSubId): array
    {
        return $this->db->query("
            SELECT
                ROUND(AVG(overall_rating),    1) AS avg_overall,
                ROUND(AVG(teaching_rating),   1) AS avg_teaching,
                ROUND(AVG(content_rating),    1) AS avg_content,
                ROUND(AVG(engagement_rating), 1) AS avg_engagement,
                COUNT(*) AS total_responses
            FROM subject_feedback
            WHERE class_sub_id_fk = ?
        ", [$classSubId])->getRowArray() ?? [];
    }

    public function getClassFeedbacks(int $classSubId, bool $withStudentNames = false): array
    {
        $select = 'sf.*';
        $join   = '';
        if ($withStudentNames) {
            $select .= ', CASE WHEN sf.is_anonymous=1 THEN "Anonymous" ELSE CONCAT(u.fname," ",u.lname) END AS student_name';
            $join    = 'LEFT JOIN users u ON u.user_id = sf.student_id_fk';
        }
        return $this->db->query("
            SELECT {$select}
            FROM subject_feedback sf
            {$join}
            WHERE sf.class_sub_id_fk = ?
            ORDER BY sf.created_at DESC
        ", [$classSubId])->getResultArray();
    }
}
