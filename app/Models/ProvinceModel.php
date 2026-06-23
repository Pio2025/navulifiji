<?php

namespace App\Models;

use CodeIgniter\Model;

class ProvinceModel extends Model
{
    protected $table = 'province';
    protected $primaryKey = 'province_id';
    protected $allowedFields = [/* list your fillable fields here */];
    
    /**
     * Get province by province_id
     */
    public function getProvince($province_id)
    {
        return $this->select('province.*, division.*')
                   ->join('division', 'division.division_id = province.division_id_fk', 'inner')
                   ->where('province.province_id', $province_id)
                   ->first();
    }
    
    public function getProvinceByDistrict($id)
    {
        $db = \Config\Database::connect();
        return $db->table('district')
                 ->select('*')
                 ->join('province', 'province.province_id = district.province_id_fk', 'inner')
                 ->where('district.province_id_fk', $id)
                 ->get()
                 ->getRowArray();
    }
    
    /**
     * Get all provinces
     */
    public function getAllProvince()
    {
        return $this->orderBy('province_id', 'DESC')
                   ->findAll();
    }
    
    /**
     * Add new province
     */
    public function addProvince($data)
    {
        return $this->insert($data);
    }
    
    /**
     * Update province
     */
    public function updateProvince($province_id, $data)
    {
        return $this->update($province_id, $data);
    }
    
    /**
     * Delete province
     */
    public function deleteProvince($province_id)
    {
        return $this->delete($province_id);
    }
}