<?php
namespace App\Models;
use CodeIgniter\Model;

class UserSessionModel extends Model
{
    protected $table      = 'user_session';
    protected $primaryKey = 'session_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id_fk',
        'session_token',
        'ip_address',
        'user_agent',
        'device_type',
        'device_os',
        'browser',
        'country',
        'city',
        'login_date',
        'login_time',
        'last_active',
        'session_status',
    ];

    public function getActiveSessions($userId)
    {
        return $this->where('user_id_fk', $userId)
                    ->orderBy('login_time', 'DESC')
                    ->findAll();
    }

    public function expireOldSessions($userId, $daysOld = 30)
    {
        $cutoff = strtotime('-' . $daysOld . ' days');
        return $this->where('user_id_fk', $userId)
                    ->where('last_active <', $cutoff)
                    ->where('session_status', 'Active')
                    ->set(['session_status' => 'Expired'])
                    ->update();
    }

    public function signOutAll($userId, $exceptToken = null)
    {
        $builder = $this->where('user_id_fk', $userId)
                        ->where('session_status', 'Active');

        if ($exceptToken) {
            $builder->where('session_token !=', $exceptToken);
        }

        return $builder->set(['session_status' => 'Signed Out'])->update();
    }

    public function signOutOne($sessionId, $userId)
    {
        return $this->where('session_id', $sessionId)
                    ->where('user_id_fk', $userId)
                    ->set(['session_status' => 'Signed Out'])
                    ->update();
    }

    public function updateLastActive($token)
    {
        return $this->where('session_token', $token)
                    ->set(['last_active' => time()])
                    ->update();
    }

    public function findByToken($token)
    {
        return $this->where('session_token', $token)->first();
    }
}