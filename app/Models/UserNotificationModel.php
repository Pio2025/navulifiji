<?php
namespace App\Models;
use CodeIgniter\Model;

class UserNotificationModel extends Model
{
    protected $table      = 'user_notification';
    protected $primaryKey = 'notification_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id_fk',
        'notif_dashboard',
        'notif_rbac',
        'notif_user',
        'notif_school',
        'notif_admission',
        'notif_enrolment',
        'notif_classroom',
        'notif_exam',
        'notif_conduct',
        'notif_timetable',
        'notif_event',
        'notif_communication',
        'notif_security',
        'notif_medical',
        'notif_reference',
        'updated_date',
        'updated_time',
    ];

    /**
     * Get notification settings for a user
     * Auto-creates default record if not exists
     */
    public function getByUser(int $userId): ?array
    {
        return $this->where('user_id_fk', $userId)->first();
    }

    /**
     * Save notification preferences
     */
    public function saveForUser(int $userId, array $settings): bool
    {
        $existing = $this->where('user_id_fk', $userId)->first();
    
        $data = array_merge($settings, [
            'updated_date' => date('Y-m-d'),
            'updated_time' => time(),
        ]);
    
        if ($existing) {
            return $this->where('user_id_fk', $userId)->set($data)->update();
        }
    
        // First time saving — insert new record
        return (bool) $this->insert(array_merge(['user_id_fk' => $userId], $data));
    }

    /**
     * Check if user has notifications enabled for a specific module
     * Usage: $notifModel->isEnabled($userId, 'medical')
     */
    public function isEnabled(int $userId, string $module): bool
    {
        $record = $this->where('user_id_fk', $userId)->first();
    
        // No record = no preferences saved = treat as disabled
        if (!$record) return false;
    
        $key = 'notif_' . strtolower($module);
        return isset($record[$key]) && (int) $record[$key] === 1;
    }
}