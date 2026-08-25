<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactMessageModel extends Model
{
    protected $table = 'contact_message';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'name',
        'email',
        'phone',
        'school_name',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'status',
        'date',
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'name'    => 'required|max_length[150]',
        'email'   => 'required|valid_email|max_length[255]',
        'message' => 'required|max_length[5000]',
    ];

    public function registerMessage(array $data): int|bool
    {
        $data['date'] = date('Y-m-d H:i:s');
        $data['status'] = 'new';

        return $this->insert($data);
    }
}
