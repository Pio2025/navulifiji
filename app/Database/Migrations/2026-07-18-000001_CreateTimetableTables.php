<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTimetableTables extends Migration
{
    public function up(): void
    {
        $db = \Config\Database::connect();

        $db->query("
            CREATE TABLE IF NOT EXISTS `timetable_template` (
                `template_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `sch_cat_id_fk` INT NOT NULL DEFAULT 0,
                `sch_id_fk`     INT NOT NULL DEFAULT 0,
                `template_name` VARCHAR(120) NOT NULL,
                `is_default`    TINYINT(1) NOT NULL DEFAULT 0,
                `created_at`    DATETIME DEFAULT NULL,
                `updated_at`    DATETIME DEFAULT NULL,
                PRIMARY KEY (`template_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `timetable_template_slot` (
                `slot_id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `template_id_fk` INT UNSIGNED NOT NULL,
                `slot_order`     TINYINT UNSIGNED NOT NULL,
                `slot_type`      ENUM('period','break') NOT NULL DEFAULT 'period',
                `label`          VARCHAR(60) NOT NULL,
                `start_time`     TIME DEFAULT NULL,
                `end_time`       TIME DEFAULT NULL,
                `is_teaching`    TINYINT(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (`slot_id`),
                KEY `idx_template_slot` (`template_id_fk`, `slot_order`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `timetable` (
                `timetable_id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `sch_id_fk`           INT NOT NULL,
                `stream_id_fk`        INT NOT NULL,
                `template_id_fk`      INT UNSIGNED NOT NULL,
                `academic_year`       YEAR NOT NULL,
                `term`                TINYINT UNSIGNED NOT NULL DEFAULT 1,
                `rotation_start_date` DATE DEFAULT NULL,
                `rotation_start_day`  TINYINT UNSIGNED NOT NULL DEFAULT 1,
                `timetable_status`    ENUM('Draft','Active','Archived') NOT NULL DEFAULT 'Draft',
                `created_by`          INT DEFAULT NULL,
                `created_at`          DATETIME DEFAULT NULL,
                `updated_at`          DATETIME DEFAULT NULL,
                PRIMARY KEY (`timetable_id`),
                KEY `idx_tt_stream` (`stream_id_fk`, `academic_year`, `term`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        $db->query("
            CREATE TABLE IF NOT EXISTS `timetable_entry` (
                `entry_id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `timetable_id_fk` INT UNSIGNED NOT NULL,
                `day_number`      TINYINT UNSIGNED NOT NULL,
                `slot_id_fk`      INT UNSIGNED NOT NULL,
                `sch_sub_id_fk`   INT DEFAULT NULL,
                `teacher_id_fk`   INT DEFAULT NULL,
                `room`            VARCHAR(60) DEFAULT NULL,
                `notes`           VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY (`entry_id`),
                UNIQUE KEY `uq_tt_cell` (`timetable_id_fk`, `day_number`, `slot_id_fk`),
                KEY `idx_tt_entry` (`timetable_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── Seed default templates ────────────────────────────────────────────
        $now = date('Y-m-d H:i:s');

        // sch_cat_id 0 = universal, 2 = Kindergarten, 3 = Primary, 4 = Secondary, 5 = TVET
        $db->query("INSERT IGNORE INTO `timetable_template`
            (`template_id`, `sch_cat_id_fk`, `sch_id_fk`, `template_name`, `is_default`, `created_at`) VALUES
            (1, 0, 0, 'Standard — Primary / Secondary',  1, '$now'),
            (2, 2, 0, 'Kindergarten',                    1, '$now'),
            (3, 5, 0, 'TVET',                            1, '$now')
        ");

        // Template 1 — Standard: 3 + Recess + 3 + Lunch + 3 = 9 teaching periods
        $db->query("INSERT IGNORE INTO `timetable_template_slot`
            (`slot_id`, `template_id_fk`, `slot_order`, `slot_type`, `label`, `start_time`, `end_time`, `is_teaching`) VALUES
            ( 1, 1,  1, 'period', 'Period 1', '08:00:00', '08:40:00', 1),
            ( 2, 1,  2, 'period', 'Period 2', '08:40:00', '09:20:00', 1),
            ( 3, 1,  3, 'period', 'Period 3', '09:20:00', '10:00:00', 1),
            ( 4, 1,  4, 'break',  'Recess',   '10:00:00', '10:20:00', 0),
            ( 5, 1,  5, 'period', 'Period 4', '10:20:00', '11:00:00', 1),
            ( 6, 1,  6, 'period', 'Period 5', '11:00:00', '11:40:00', 1),
            ( 7, 1,  7, 'period', 'Period 6', '11:40:00', '12:20:00', 1),
            ( 8, 1,  8, 'break',  'Lunch',    '12:20:00', '13:00:00', 0),
            ( 9, 1,  9, 'period', 'Period 7', '13:00:00', '13:40:00', 1),
            (10, 1, 10, 'period', 'Period 8', '13:40:00', '14:20:00', 1),
            (11, 1, 11, 'period', 'Period 9', '14:20:00', '15:00:00', 1)
        ");

        // Template 2 — Kindergarten: shorter day, 8 teaching periods
        $db->query("INSERT IGNORE INTO `timetable_template_slot`
            (`slot_id`, `template_id_fk`, `slot_order`, `slot_type`, `label`, `start_time`, `end_time`, `is_teaching`) VALUES
            (12, 2,  1, 'period', 'Period 1', '08:00:00', '08:30:00', 1),
            (13, 2,  2, 'period', 'Period 2', '08:30:00', '09:00:00', 1),
            (14, 2,  3, 'period', 'Period 3', '09:00:00', '09:30:00', 1),
            (15, 2,  4, 'break',  'Recess',   '09:30:00', '10:00:00', 0),
            (16, 2,  5, 'period', 'Period 4', '10:00:00', '10:30:00', 1),
            (17, 2,  6, 'period', 'Period 5', '10:30:00', '11:00:00', 1),
            (18, 2,  7, 'period', 'Period 6', '11:00:00', '11:30:00', 1),
            (19, 2,  8, 'break',  'Lunch',    '11:30:00', '12:00:00', 0),
            (20, 2,  9, 'period', 'Period 7', '12:00:00', '12:30:00', 1),
            (21, 2, 10, 'period', 'Period 8', '12:30:00', '13:00:00', 1)
        ");

        // Template 3 — TVET: longer sessions, 7 teaching sessions
        $db->query("INSERT IGNORE INTO `timetable_template_slot`
            (`slot_id`, `template_id_fk`, `slot_order`, `slot_type`, `label`, `start_time`, `end_time`, `is_teaching`) VALUES
            (22, 3, 1, 'period', 'Session 1', '08:00:00', '09:00:00', 1),
            (23, 3, 2, 'period', 'Session 2', '09:00:00', '10:00:00', 1),
            (24, 3, 3, 'period', 'Session 3', '10:00:00', '11:00:00', 1),
            (25, 3, 4, 'break',  'Recess',    '11:00:00', '11:20:00', 0),
            (26, 3, 5, 'period', 'Session 4', '11:20:00', '12:20:00', 1),
            (27, 3, 6, 'period', 'Session 5', '12:20:00', '13:20:00', 1),
            (28, 3, 7, 'break',  'Lunch',     '13:20:00', '14:00:00', 0),
            (29, 3, 8, 'period', 'Session 6', '14:00:00', '15:00:00', 1),
            (30, 3, 9, 'period', 'Session 7', '15:00:00', '16:00:00', 1)
        ");
    }

    public function down(): void
    {
        $db = \Config\Database::connect();
        foreach (['timetable_entry', 'timetable', 'timetable_template_slot', 'timetable_template'] as $t) {
            $db->query("DROP TABLE IF EXISTS `{$t}`");
        }
    }
}
