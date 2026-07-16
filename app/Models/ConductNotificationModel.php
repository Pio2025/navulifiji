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

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `conduct_notifications` (
            `notification_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `incident_id`     INT UNSIGNED DEFAULT NULL,
            `recipient_type`  VARCHAR(20)  DEFAULT NULL,
            `sent_via`        VARCHAR(20)  DEFAULT NULL,
            `sent_timestamp`  TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP,
            `message_preview` VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY (`notification_id`),
            KEY `incident_id` (`incident_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

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
