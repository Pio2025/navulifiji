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
}