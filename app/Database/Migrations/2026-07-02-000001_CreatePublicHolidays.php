<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePublicHolidays extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `public_holidays` (
                `holiday_id`   INT NOT NULL AUTO_INCREMENT,
                `holiday_date` DATE NOT NULL,
                `holiday_name` VARCHAR(200) NOT NULL,
                `sch_id_fk`    INT DEFAULT NULL COMMENT 'NULL = all schools',
                `created_at`   DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`holiday_id`),
                KEY `idx_holiday_date` (`holiday_date`),
                KEY `idx_sch_id`       (`sch_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `public_holidays`');
    }
}
