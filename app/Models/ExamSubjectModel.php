<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamSubjectModel extends Model
{
    protected $table      = 'exam_mark';
    protected $primaryKey = 'exam_sub_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'exam_reg_id_fk',
        'stud_sub_id_fk',
        'exam_mark',
    ];

    /**
     * Load examinable subjects with marks for a given exam registration.
     * Joins student_subject → subject (is_examinable=1) and exam_mark for values.
     */
    public function getByRegistration(int $examRegId): array
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
                COALESCE(em.exam_mark, 0) AS exam_mark,
                em.exam_sub_id
            FROM exam_registration er
            INNER JOIN admission   adm ON adm.admission_id    = er.admission_id_fk
            INNER JOIN student_subject ss
                    ON ss.admission_id_fk = adm.admission_id
                   AND ss.stud_sub_status = 'Active'
            INNER JOIN sch_subject schsub ON schsub.sch_sub_id = ss.sch_sub_id_fk
            INNER JOIN subject     sub    ON sub.subject_id    = schsub.subject_id_fk
                                         AND sub.is_examinable  = 1
            LEFT  JOIN classroom   cl    ON cl.class_id        = ss.class_id_fk
            LEFT  JOIN stream_core_subject scs
                    ON scs.sch_sub_id_fk = ss.sch_sub_id_fk
                   AND scs.stream_id_fk  = cl.stream_id_fk
            LEFT  JOIN stream_optional_subject sos
                    ON sos.sch_sub_id_fk = ss.sch_sub_id_fk
                   AND sos.stream_id_fk  = cl.stream_id_fk
            LEFT  JOIN exam_mark em
                    ON em.stud_sub_id_fk  = ss.stud_sub_id
                   AND em.exam_reg_id_fk  = er.exam_reg_id
            WHERE er.exam_reg_id = ?
            ORDER BY sub_type ASC, sub.subject_name ASC
        ", [$examRegId])->getResultArray();
    }

    public function deleteByRegistration(int $examRegId): void
    {
        $this->where('exam_reg_id_fk', $examRegId)->delete();
    }
}
