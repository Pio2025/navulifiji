<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTimetableForOptionals extends Migration
{
    public function up(): void
    {
        $db = \Config\Database::connect();

        // Allow multiple entries per slot (for optional subject blocks)
        $db->query("ALTER TABLE `timetable_entry` DROP INDEX `uq_tt_cell`");
        $db->query("ALTER TABLE `timetable_entry` ADD COLUMN `option_num` TINYINT UNSIGNED DEFAULT NULL AFTER `slot_id_fk`");
        $db->query("ALTER TABLE `timetable_entry` ADD KEY `idx_tt_cell` (`timetable_id_fk`, `day_number`, `slot_id_fk`)");

        // num_days makes the rotation-day count configurable per template
        $db->query("ALTER TABLE `timetable_template` ADD COLUMN `num_days` TINYINT UNSIGNED NOT NULL DEFAULT 6 AFTER `is_default`");
    }

    public function down(): void
    {
        $db = \Config\Database::connect();

        $db->query("ALTER TABLE `timetable_entry` DROP KEY `idx_tt_cell`");
        $db->query("ALTER TABLE `timetable_entry` DROP COLUMN `option_num`");
        $db->query("ALTER TABLE `timetable_entry` ADD UNIQUE KEY `uq_tt_cell` (`timetable_id_fk`, `day_number`, `slot_id_fk`)");
        $db->query("ALTER TABLE `timetable_template` DROP COLUMN `num_days`");
    }
}
