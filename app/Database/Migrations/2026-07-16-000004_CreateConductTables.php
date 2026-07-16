<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Creates all conduct-module tables if they don't already exist.
 * Safe to re-run: every CREATE uses IF NOT EXISTS.
 */
class CreateConductTables extends Migration
{
    public function up(): void
    {
        $db = \Config\Database::connect();

        // ── conduct_types ────────────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `conduct_types` (
                `type_id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `type_name`      VARCHAR(150) NOT NULL,
                `category`       VARCHAR(100) DEFAULT NULL,
                `is_positive`    TINYINT(1)   NOT NULL DEFAULT 0,
                `default_points` INT          NOT NULL DEFAULT 0,
                `severity_level` ENUM('Positive','Minor','Major','Critical') NOT NULL DEFAULT 'Minor',
                PRIMARY KEY (`type_id`),
                KEY `category`   (`category`),
                KEY `is_positive`(`is_positive`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── conduct_incidents ────────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `conduct_incidents` (
                `incident_id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `student_id`           INT UNSIGNED NOT NULL COMMENT 'admission.admission_id',
                `staff_id`             INT UNSIGNED NOT NULL COMMENT 'users.user_id',
                `type_id_fk`           INT UNSIGNED DEFAULT NULL,
                `points_awarded`       INT          NOT NULL DEFAULT 0,
                `incident_description` TEXT,
                `incident_date`        DATETIME     DEFAULT NULL,
                `location`             VARCHAR(100) DEFAULT NULL,
                `is_resolved`          TINYINT(1)   NOT NULL DEFAULT 0,
                PRIMARY KEY (`incident_id`),
                KEY `student_id` (`student_id`),
                KEY `type_id_fk` (`type_id_fk`),
                KEY `incident_date` (`incident_date`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── conduct_incident_file ────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `conduct_incident_file` (
                `conduct_file_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `incident_id_fk`  INT UNSIGNED NOT NULL,
                `file_src`        VARCHAR(500) NOT NULL,
                `file_type`       VARCHAR(100) DEFAULT NULL,
                `created_at`      DATETIME     DEFAULT NULL,
                PRIMARY KEY (`conduct_file_id`),
                KEY `incident_id_fk` (`incident_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── conduct_actions ──────────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `conduct_actions` (
                `action_id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `incident_id`    INT UNSIGNED NOT NULL,
                `action_type`    VARCHAR(150) DEFAULT NULL,
                `action_date`    DATE         DEFAULT NULL,
                `duration_hours` DECIMAL(6,2) DEFAULT NULL,
                `is_completed`   TINYINT(1)   NOT NULL DEFAULT 0,
                `notes`          TEXT,
                `created_at`     DATETIME     DEFAULT NULL,
                PRIMARY KEY (`action_id`),
                KEY `incident_id` (`incident_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── conduct_notifications ────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `conduct_notifications` (
                `notification_id`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `incident_id`      INT UNSIGNED NOT NULL,
                `recipient_type`   VARCHAR(50)  DEFAULT NULL,
                `sent_via`         VARCHAR(50)  DEFAULT NULL,
                `sent_timestamp`   DATETIME     DEFAULT NULL,
                `message_preview`  TEXT,
                PRIMARY KEY (`notification_id`),
                KEY `incident_id` (`incident_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── conduct_appeals ──────────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `conduct_appeals` (
                `appeal_id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `incident_id_fk`  INT UNSIGNED NOT NULL,
                `submitted_by`    INT UNSIGNED NOT NULL COMMENT 'users.user_id',
                `appeal_reason`   TEXT,
                `appeal_status`   ENUM('Pending','Reviewed','Approved','Rejected') NOT NULL DEFAULT 'Pending',
                `reviewer_id`     INT UNSIGNED DEFAULT NULL,
                `reviewer_notes`  TEXT,
                `created_at`      DATETIME     DEFAULT NULL,
                `updated_at`      DATETIME     DEFAULT NULL,
                PRIMARY KEY (`appeal_id`),
                KEY `incident_id_fk` (`incident_id_fk`),
                KEY `appeal_status`  (`appeal_status`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // ── conduct_appeal_files ─────────────────────────────────────────────────
        $db->query("
            CREATE TABLE IF NOT EXISTS `conduct_appeal_files` (
                `appeal_file_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `appeal_id_fk`   INT UNSIGNED NOT NULL,
                `file_src`       VARCHAR(500) NOT NULL,
                `file_type`      VARCHAR(100) DEFAULT NULL,
                `created_at`     DATETIME     DEFAULT NULL,
                PRIMARY KEY (`appeal_file_id`),
                KEY `appeal_id_fk` (`appeal_id_fk`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");
    }

    public function down(): void
    {
        $db = \Config\Database::connect();
        foreach ([
            'conduct_appeal_files',
            'conduct_appeals',
            'conduct_notifications',
            'conduct_actions',
            'conduct_incident_file',
            'conduct_incidents',
            'conduct_types',
        ] as $table) {
            $db->query("DROP TABLE IF EXISTS `{$table}`");
        }
    }
}
