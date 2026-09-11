<?php
namespace App\Models;
use CodeIgniter\Model;

class GateFormOptionModel extends Model
{
    protected $table      = 'gate_form_option';
    protected $primaryKey = 'option_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'sch_id_fk',
        'option_type',
        'option_label',
        'sort_order',
        'option_status',
        'created_at',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();
        $db->query("CREATE TABLE IF NOT EXISTS `gate_form_option` (
            `option_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `sch_id_fk`     INT UNSIGNED NOT NULL,
            `option_type`   VARCHAR(30) NOT NULL,
            `option_label`  VARCHAR(100) NOT NULL,
            `sort_order`    INT NOT NULL DEFAULT 0,
            `option_status` VARCHAR(20) NOT NULL DEFAULT 'Active',
            `created_at`    DATETIME DEFAULT NULL,
            PRIMARY KEY (`option_id`),
            KEY `sch_type` (`sch_id_fk`, `option_type`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");
    }

    /** @return array<string,array> options grouped by type ('visit_purpose'|'pass_reason'), Active only. */
    public function getActiveGrouped(int $schId): array
    {
        $rows = $this->where('sch_id_fk', $schId)
            ->where('option_status', 'Active')
            ->orderBy('option_type', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        $out = ['visit_purpose' => [], 'pass_reason' => []];
        foreach ($rows as $row) {
            $out[$row['option_type']][] = $row;
        }
        return $out;
    }

    public function getAllForSchool(int $schId): array
    {
        return $this->where('sch_id_fk', $schId)
            ->orderBy('option_type', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
