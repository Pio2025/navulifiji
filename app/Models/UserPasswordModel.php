<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPasswordModel extends Model
{
    // Database table
    protected $table = 'user_password';
    
    // Primary key
    protected $primaryKey = 'user_pass_id';
    
    // Allowed fields (for security)
    protected $allowedFields = [
        'user_id_fk',
        'password',
        'date_created',
        'time_created',
        'password_status'
    ];
    
    // Dates configuration
    protected $useTimestamps = false;
    protected $returnType = 'array';
    
    
    public function addPassword($data)
    {
        return $this->insert($data);
    }

}