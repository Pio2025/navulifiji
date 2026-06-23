<?php

namespace App\Models;

use CodeIgniter\Model;

class UserLogModel extends Model
{
    // Database table
    protected $table = 'user_log';
    
    // Primary key
    protected $primaryKey = 'user_log_id';
    
    // Allowed fields (for security)
    protected $allowedFields = [
        'user_id_fk',
        'ip_aadress',
        'user_agent',
        'user_device',
        'log_title',
        'log_desc',
        'log_date',
        'log_time',
        'log_icon',
        'log_theme'
    ];
    
    // Dates configuration
    protected $useTimestamps = false;
    protected $returnType = 'array';
    
    
    public function addUserLog($data)
    {
        return $this->insert($data);
    }
    
    public function getUserLogs($userId, $perPage = 20, $search = '', $dateFrom = '', $dateTo = '', $theme = '')
    {
        $builder = $this->where('user_id_fk', $userId);
    
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('log_title', $search)
                    ->orLike('log_desc', $search)
                    ->orLike('ip_aadress', $search)
                    ->orLike('user_device', $search)
                    ->groupEnd();
        }
    
        if (!empty($dateFrom)) {
            $builder->where('log_date >=', $dateFrom);
        }
    
        if (!empty($dateTo)) {
            $builder->where('log_date <=', $dateTo);
        }
    
        if (!empty($theme)) {
            $builder->where('log_theme', $theme);
        }
    
        return $builder->orderBy('log_time', 'DESC')
                       ->paginate($perPage, 'log');
    }
    
    public function getAllUserLogsForExport($userId, $search = '', $dateFrom = '', $dateTo = '', $theme = '')
    {
        $builder = $this->where('user_id_fk', $userId);
    
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('log_title', $search)
                    ->orLike('log_desc', $search)
                    ->orLike('ip_aadress', $search)
                    ->orLike('user_device', $search)
                    ->groupEnd();
        }
    
        if (!empty($dateFrom)) {
            $builder->where('log_date >=', $dateFrom);
        }
    
        if (!empty($dateTo)) {
            $builder->where('log_date <=', $dateTo);
        }
    
        if (!empty($theme)) {
            $builder->where('log_theme', $theme);
        }
    
        return $builder->orderBy('log_time', 'DESC')->findAll();
    }

}