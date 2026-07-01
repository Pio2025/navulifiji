<?php

namespace App\Models;

use CodeIgniter\Model;

class PublicHolidayModel extends Model
{
    protected $table      = 'public_holidays';
    protected $primaryKey = 'holiday_id';
    protected $allowedFields = ['holiday_date', 'holiday_name', 'sch_id_fk'];
    protected $useTimestamps = false;

    public function ensureTable(): void
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('public_holidays')) {
            $db->query("
                CREATE TABLE IF NOT EXISTS `public_holidays` (
                    `holiday_id`   INT NOT NULL AUTO_INCREMENT,
                    `holiday_date` DATE NOT NULL,
                    `holiday_name` VARCHAR(200) NOT NULL,
                    `sch_id_fk`    INT DEFAULT NULL,
                    `created_at`   DATETIME DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`holiday_id`),
                    KEY `idx_holiday_date` (`holiday_date`),
                    KEY `idx_sch_id`       (`sch_id_fk`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
            ");
        }
    }

    // Returns [date => holiday_name] for given dates, scoped to school + global
    public function getByDates(array $dates, int $schId = 0): array
    {
        if (empty($dates)) return [];
        $db = \Config\Database::connect();
        if (!$db->tableExists('public_holidays')) return [];

        $builder = $db->table('public_holidays')->whereIn('holiday_date', $dates);
        if ($schId > 0) {
            $builder->groupStart()
                ->where('sch_id_fk IS NULL', null, false)
                ->orWhere('sch_id_fk', $schId)
                ->groupEnd();
        } else {
            $builder->where('sch_id_fk IS NULL', null, false);
        }

        $indexed = [];
        foreach ($builder->get()->getResultArray() as $row) {
            $indexed[$row['holiday_date']] = $row['holiday_name'];
        }
        return $indexed;
    }

    // All holidays for a school (for listing in management UI)
    public function getForSchool(int $schId = 0): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('public_holidays')) return [];

        $builder = $db->table('public_holidays')->orderBy('holiday_date', 'ASC');
        if ($schId > 0) {
            $builder->groupStart()
                ->where('sch_id_fk IS NULL', null, false)
                ->orWhere('sch_id_fk', $schId)
                ->groupEnd();
        } else {
            $builder->where('sch_id_fk IS NULL', null, false);
        }
        return $builder->get()->getResultArray();
    }
}
