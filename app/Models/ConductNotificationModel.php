<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductNotificationModel extends Model
{
    protected $table      = 'conduct_notifications';
    protected $primaryKey = 'notification_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'incident_id',
        'recipient_type',
        'sent_via',
        'sent_timestamp',
        'message_preview',
    ];

    public function getByIncident(int $incidentId): array
    {
        return $this->where('incident_id', $incidentId)
                    ->orderBy('sent_timestamp', 'DESC')
                    ->findAll();
    }

    public function log(int $incidentId, string $recipientType, string $sentVia, string $messagePreview): int
    {
        return (int) $this->insert([
            'incident_id'     => $incidentId,
            'recipient_type'  => $recipientType,
            'sent_via'        => $sentVia,
            'sent_timestamp'  => date('Y-m-d H:i:s'),
            'message_preview' => $messagePreview,
        ]);
    }
}
