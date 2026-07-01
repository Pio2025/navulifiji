<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamRegistrationModel extends Model
{
    protected $table      = 'exam_registration';
    protected $primaryKey = 'exam_reg_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'exam_id_fk',
        'admission_id_fk',
        'exam_year',
        'stud_index_num',
    ];

    /**
     * Generate a cryptographically random 8-digit index number
     * (10000000–99999999, first digit never 0) that is not already in use.
     *
     * Uses PHP's random_int() (CSPRNG) for the initial pick, then verifies
     * uniqueness against the DB. The UNIQUE key on stud_index_num acts as
     * the final concurrency safety net.
     */
    public function generateUniqueIndexNum(): int
    {
        $db          = \Config\Database::connect();
        $maxAttempts = 30;

        for ($i = 0; $i < $maxAttempts; $i++) {
            // Range guarantees 8 digits with a non-zero leading digit
            $num = random_int(10_000_000, 99_999_999);

            $exists = $db->table('exam_registration')
                         ->where('stud_index_num', $num)
                         ->countAllResults();

            if ($exists === 0) {
                return $num;
            }
        }

        throw new \RuntimeException(
            "Could not generate a unique student index number after {$maxAttempts} attempts."
        );
    }

    /**
     * Register a student in exam_registration, skipping if they're already registered.
     * Returns the stud_index_num assigned (existing or new).
     */
    public function registerStudent(int $examId, int $admissionId, int $examYear): int
    {
        $existing = $this->where('exam_id_fk', $examId)
                         ->where('admission_id_fk', $admissionId)
                         ->where('exam_year', $examYear)
                         ->first();

        if ($existing) {
            return (int) $existing['stud_index_num'];
        }

        $indexNum = $this->generateUniqueIndexNum();

        $this->insert([
            'exam_id_fk'      => $examId,
            'admission_id_fk' => $admissionId,
            'exam_year'       => $examYear,
            'stud_index_num'  => $indexNum,
        ]);

        return $indexNum;
    }

    /**
     * Like registerStudent() but returns exam_reg_id (PK) instead of stud_index_num.
     * Use this when you need to link child records (e.g. exam_subject) to the registration row.
     */
    public function registerStudentGetRegId(int $examId, int $admissionId, int $examYear): int
    {
        $existing = $this->where('exam_id_fk', $examId)
                         ->where('admission_id_fk', $admissionId)
                         ->where('exam_year', $examYear)
                         ->first();

        if ($existing) {
            return (int) $existing['exam_reg_id'];
        }

        $indexNum = $this->generateUniqueIndexNum();
        $regId    = $this->insert([
            'exam_id_fk'      => $examId,
            'admission_id_fk' => $admissionId,
            'exam_year'       => $examYear,
            'stud_index_num'  => $indexNum,
        ]);

        return (int) $regId;
    }

    /**
     * Remove a student's registration entry for a specific exam.
     */
    public function removeByAdmissionAndExam(int $admissionId, int $examId): void
    {
        $this->where('exam_id_fk', $examId)
             ->where('admission_id_fk', $admissionId)
             ->delete();
    }

    /**
     * Remove all registrations for a given exam and a set of admission IDs.
     */
    public function removeByExamAndAdmissions(int $examId, array $admissionIds): void
    {
        if (empty($admissionIds)) {
            return;
        }

        $this->where('exam_id_fk', $examId)
             ->whereIn('admission_id_fk', $admissionIds)
             ->delete();
    }
}
