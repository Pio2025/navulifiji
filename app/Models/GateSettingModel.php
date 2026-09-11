<?php
namespace App\Models;
use CodeIgniter\Model;

class GateSettingModel extends Model
{
    protected $table      = 'gate_setting';
    protected $primaryKey = 'gate_setting_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'pass_prefix',
        'next_pass_seq',
        'notify_on_visitor_checkin',
        'notify_on_pass_request',
        'notify_on_pass_decision',
        'created_at',
        'updated_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `gate_setting` (
            `gate_setting_id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`                 INT UNSIGNED NOT NULL,
            `pass_prefix`               VARCHAR(10) NOT NULL DEFAULT 'GP-',
            `next_pass_seq`             INT UNSIGNED NOT NULL DEFAULT 1,
            `notify_on_visitor_checkin` TINYINT(1) NOT NULL DEFAULT 1,
            `notify_on_pass_request`    TINYINT(1) NOT NULL DEFAULT 1,
            `notify_on_pass_decision`   TINYINT(1) NOT NULL DEFAULT 1,
            `created_at`                DATETIME DEFAULT NULL,
            `updated_at`                DATETIME DEFAULT NULL,
            PRIMARY KEY (`gate_setting_id`),
            UNIQUE KEY `sch_id_fk` (`sch_id_fk`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /** Fetch (creating with defaults if missing) the settings row for a school. */
    public function forSchool(int $schId): array
    {
        $row = $this->where('sch_id_fk', $schId)->first();
        if ($row) {
            return $row;
        }

        $now = date('Y-m-d H:i:s');
        $id  = $this->insert([
            'sch_id_fk'                 => $schId,
            'pass_prefix'               => 'GP-',
            'next_pass_seq'             => 1,
            'notify_on_visitor_checkin' => 1,
            'notify_on_pass_request'    => 1,
            'notify_on_pass_decision'   => 1,
            'created_at'                => $now,
            'updated_at'                => $now,
        ]);

        return $this->find($id);
    }

    /** Atomically reserve and return the next formatted pass number for a school. */
    public function nextPassNumber(int $schId): string
    {
        $settings = $this->forSchool($schId);
        $seq      = (int) $settings['next_pass_seq'];

        $this->update($settings['gate_setting_id'], [
            'next_pass_seq' => $seq + 1,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return $settings['pass_prefix'] . str_pad((string) $seq, 5, '0', STR_PAD_LEFT);
    }
}
