<?php

namespace App\Models;

use CodeIgniter\Model;

class DistrictModel extends Model
{
    protected $table = 'district';
    protected $primaryKey = 'district_id';
    protected $allowedFields = ['district_name', 'province_id_fk', 'district_status', 'district_created_at', 'district_updated_at'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    /**
     * Get district by district_id
     */
    public function getDistrict($district_id)
    {
        return $this->find($district_id);
    }

    /**
     * Get district with full relations (province and division)
     */
    public function getDistrictFull($id)
    {
        return $this->select('district.*, province.*, division.*')
                   ->join('province', 'province.province_id = district.province_id_fk', 'inner')
                   ->join('division', 'division.division_id = province.division_id_fk', 'inner')
                   ->where('district.district_id', $id)
                   ->first();
    }

    /**
     * Get all districts
     */
    public function getAllDistrict()
    {
        return $this->orderBy('district_id', 'DESC')
                   ->findAll();
    }

    /**
     * Add new district
     */
    public function addDistrict($data)
    {
        return $this->insert($data);
    }

    /**
     * Update district
     */
    public function updateDistrict($district_id, $data)
    {
        return $this->update($district_id, $data);
    }

    /**
     * Delete district
     */
    public function deleteDistrict($district_id)
    {
        return $this->delete($district_id);
    }

    /**
     * Get districts by province
     */
    public function getDistrictByProvince($provinceID)
    {
        return $this->where('province_id_fk', $provinceID)
               ->orderBy('district_name', 'ASC')
               ->findAll();
    }
}