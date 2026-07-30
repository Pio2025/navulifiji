<?php
// app/Models/AdmissionModel.php

namespace App\Models;

use CodeIgniter\Model;

class AdmissionModel extends Model
{
    protected $table = 'admission';
    protected $primaryKey = 'admission_id';
    
    protected $allowedFields = [
        'user_id_fk',
        'sch_id_fk',
        'admission_date',
        'admission_time',
        'admission_note',
        'admission_status'
    ];
    
    protected $useTimestamps = false;
    protected $returnType = 'array';
    
    /**
     * Get Admission by admission_id
     */
    public function getAdmission($admission_id)
    {
        return $this->find($admission_id);
    }
    
    /**
     * Get all Admissions
     */
    public function getAllAdmission()
    {
        return $this->orderBy('admission_id', 'ASC')->findAll();
    }
    
    /**
     * Add new Admission
     */
    public function addAdmission($data)
    {
        if ($this->insert($data)) {
            return $this->getInsertID();  // ✅ Return the inserted ID, not just true/false
        }
        return false;
    }
    
    /**
     * Update Admission
     */
    public function updateAdmission($admission_id, $data)
    {
        return $this->update($admission_id, $data);
    }
    
    /**
     * Delete Admission
     */
    public function deleteAdmission($admission_id)
    {
        return $this->delete($admission_id);
    }
    
    /**
     * Get admissions by user ID
     */
    public function getAdmissionByUser($userId)
    {
        return $this->where('user_id_fk', $userId)
                    ->where('admission_status', 'Active')
                    ->findAll();
    }
    
    /**
     * Get admissions with school details for a user
     */
    public function getAdmissionWithSchool(int $userId): array
    {
        $db = \Config\Database::connect();
        return $db->table('admission')
            ->select('
                admission.*,
                school.sch_name,
                school.sch_address,
                school.sch_logo,
                school.sch_phone,
                school.sch_email
            ')
            ->join('school', 'school.sch_id = admission.sch_id_fk', 'left')
            ->where('admission.user_id_fk', $userId)
            ->orderBy('admission.admission_id', 'DESC')
            ->get()
            ->getResultArray();
    }
    
    /**
     * Get admission with enrolment details for student
     */
    public function getAdmissionWithEnrolment(int $userId): array
    {
        $db = \Config\Database::connect();
        return $db->table('admission')
            ->select('
                admission.*,
                school.sch_name,
                school.sch_address,
                school.sch_logo,
                enrolment.enrol_id,
                enrolment.enrol_date,
                enrolment.enrol_term,
                enrolment.enrol_year,
                enrolment.enrol_status,
                stream.stream_name,
                level.level_name,
                sch_level.sch_level_id
            ')
            ->join('school',     'school.sch_id             = admission.sch_id_fk',          'left')
            ->join('enrolment',  'enrolment.admission_id_fk = admission.admission_id',        'left')
            ->join('stream',     'stream.stream_id          = enrolment.stream_id_fk',        'left')
            ->join('sch_level',  'sch_level.sch_level_id    = stream.sch_level_id_fk',        'left')
            ->join('level',      'level.level_id            = sch_level.level_id_fk',         'left')
            ->where('admission.user_id_fk', $userId)
            ->orderBy('admission.admission_id', 'DESC')
            ->get()
            ->getResultArray();
    }
    
    /**
     * Get all admissions with user and school details
     */
    public function getAllAdmissionsWithDetails(?int $schId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('admission')
            ->select('
                admission.*,
                users.fname,
                users.lname,
                users.oname,
                users.email,
                users.gender,
                users.profile_photo,
                school.sch_name,
                school.sch_address,
                school.sch_logo,
                role.role_name,
                role_category.role_cat_name,
                role_category.role_cat_id
            ')
            ->join('users',         'users.user_id             = admission.user_id_fk',    'left')
            ->join('user_role',     'user_role.user_id_fk      = users.user_id',           'left')
            ->join('role',          'role.role_id              = user_role.role_id_fk',    'left')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',    'left')
            ->join('school',        'school.sch_id             = admission.sch_id_fk',     'left')
            ->where('user_role.user_role_status', 'Active');

        if ($schId !== null) {
            $builder->where('admission.sch_id_fk', $schId);
        }

        return $builder->orderBy('admission.admission_id', 'DESC')->get()->getResultArray();
    }
    
    /**
     * Get single admission with full details
     */
    public function getAdmissionDetail(int $admissionId): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('admission')
            ->select('
                admission.*,
                users.user_id,
                users.fname,
                users.lname,
                users.oname,
                users.email,
                users.gender,
                users.dob,
                users.phone,
                users.address,
                users.profile_photo,
                school.sch_id,
                school.sch_name,
                school.sch_address,
                school.sch_logo,
                school.sch_phone,
                school.sch_email,
                role.role_name,
                role_category.role_cat_name,
                role_category.role_cat_id
            ')
            ->join('users',         'users.user_id             = admission.user_id_fk',    'left')
            ->join('user_role',     'user_role.user_id_fk      = users.user_id',           'left')
            ->join('role',          'role.role_id              = user_role.role_id_fk',    'left')
            ->join('role_category', 'role_category.role_cat_id = role.role_cat_id_fk',    'left')
            ->join('school',        'school.sch_id             = admission.sch_id_fk',     'left')
            ->where('admission.admission_id', $admissionId)
            ->where('user_role.user_role_status', 'Active')
            ->get()
            ->getRowArray();
    }

    /**
     * Enrolment records for one admission (for the mobile Admission Detail screen).
     */
    public function getEnrolmentsForAdmission(int $admissionId): array
    {
        $db = \Config\Database::connect();
        return $db->table('enrolment')
            ->select('
                enrolment.enrol_id, enrolment.enrol_date, enrolment.enrol_term,
                enrolment.enrol_year, enrolment.enrol_status,
                stream.stream_name, level.level_name
            ')
            ->join('stream',    'stream.stream_id       = enrolment.stream_id_fk', 'left')
            ->join('sch_level', 'sch_level.sch_level_id = stream.sch_level_id_fk',  'left')
            ->join('level',     'level.level_id         = sch_level.level_id_fk',   'left')
            ->where('enrolment.admission_id_fk', $admissionId)
            ->orderBy('enrolment.enrol_id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Current leadership/prefect role for an admission, if any.
     */
    public function getLeadershipRole(int $admissionId): ?string
    {
        $db  = \Config\Database::connect();
        $row = $db->table('admission_student_role')
            ->select('leadership_role')
            ->where('admission_id_fk', $admissionId)
            ->get()->getRowArray();
        return $row['leadership_role'] ?? null;
    }

    /**
     * Save (replace) the leadership/prefect role for an admission. Pass null/empty to clear it.
     */
    public function saveLeadershipRole(int $admissionId, ?string $role): void
    {
        $db = \Config\Database::connect();
        $db->table('admission_student_role')->where('admission_id_fk', $admissionId)->delete();
        if (!empty($role)) {
            $db->table('admission_student_role')->insert([
                'admission_id_fk' => $admissionId,
                'leadership_role' => $role,
                'created_date'    => date('Y-m-d'),
                'created_time'    => time(),
            ]);
        }
    }

    private const API_JOIN = '
        FROM admission
        LEFT JOIN users         ON users.user_id             = admission.user_id_fk
        LEFT JOIN user_role     ON user_role.user_id_fk       = users.user_id
        LEFT JOIN role          ON role.role_id               = user_role.role_id_fk
        LEFT JOIN role_category ON role_category.role_cat_id  = role.role_cat_id_fk
        LEFT JOIN school        ON school.sch_id              = admission.sch_id_fk
    ';

    private const API_SELECT = "
        admission.admission_id, admission.user_id_fk, admission.sch_id_fk,
        admission.admission_date, admission.admission_status, admission.admission_note,
        users.fname, users.lname, users.oname, users.email, users.gender, users.profile_photo,
        school.sch_name, role.role_name, role_category.role_cat_name, role_category.role_cat_id
    ";

    private function apiFilters(?int $schId, ?string $search, ?string $status): array
    {
        $where  = ['user_role.user_role_status = "Active"'];
        $params = [];

        if ($schId) {
            $where[]  = 'admission.sch_id_fk = ?';
            $params[] = $schId;
        }
        if ($search !== null && $search !== '') {
            $where[]  = '(users.fname LIKE ? OR users.lname LIKE ? OR users.email LIKE ?)';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        if ($status !== null && $status !== '') {
            $where[]  = 'admission.admission_status = ?';
            $params[] = $status;
        }

        return [implode(' AND ', $where), $params];
    }

    /**
     * Total admissions matching the given API filters (for pagination).
     */
    public function countForApi(?int $schId, ?string $search, ?string $status): int
    {
        [$where, $params] = $this->apiFilters($schId, $search, $status);
        $db  = \Config\Database::connect();
        $row = $db->query('SELECT COUNT(*) AS cnt ' . self::API_JOIN . " WHERE $where", $params)->getRowArray();

        return (int) ($row['cnt'] ?? 0);
    }

    /**
     * One page of admissions matching the given API filters, newest first.
     */
    public function getPageForApi(?int $schId, ?string $search, ?string $status, int $limit, int $offset): array
    {
        [$where, $params] = $this->apiFilters($schId, $search, $status);
        $db       = \Config\Database::connect();
        $params[] = $limit;
        $params[] = $offset;

        return $db->query(
            'SELECT ' . self::API_SELECT . self::API_JOIN . " WHERE $where ORDER BY admission.admission_id DESC LIMIT ? OFFSET ?",
            $params
        )->getResultArray();
    }

    /**
     * All admissions belonging to the given child user IDs (unpaginated — small result set).
     */
    public function getChildAdmissions(array $childUserIds): array
    {
        if (empty($childUserIds)) {
            return [];
        }

        $db = \Config\Database::connect();
        $in = implode(',', array_fill(0, count($childUserIds), '?'));

        return $db->query(
            'SELECT ' . self::API_SELECT . self::API_JOIN . " WHERE admission.user_id_fk IN ($in) ORDER BY admission.admission_id DESC",
            $childUserIds
        )->getResultArray();
    }
}