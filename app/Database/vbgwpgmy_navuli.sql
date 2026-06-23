-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 03, 2026 at 10:58 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vbgwpgmy_navuli`
--

-- --------------------------------------------------------

--
-- Table structure for table `admission`
--

DROP TABLE IF EXISTS `admission`;
CREATE TABLE IF NOT EXISTS `admission` (
  `admission_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `sch_id_fk` int NOT NULL,
  `admission_date` date DEFAULT NULL,
  `admission_time` int DEFAULT NULL,
  `admission_note` longtext,
  `admission_status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`admission_id`),
  KEY `fk_admission_school` (`sch_id_fk`),
  KEY `fk_admission_users` (`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admission`
--

INSERT INTO `admission` (`admission_id`, `user_id_fk`, `sch_id_fk`, `admission_date`, `admission_time`, `admission_note`, `admission_status`) VALUES
(10, 30, 12, '2026-03-03', 1772487700, NULL, 'Completed'),
(11, 32, 12, '2026-03-03', 1772488632, NULL, 'Active'),
(12, 18, 12, '2026-05-12', NULL, NULL, 'Active'),
(13, 12, 12, '2026-05-26', 1779754757, NULL, 'Active'),
(14, 33, 12, '2026-05-27', 1779826374, NULL, 'Active'),
(15, 34, 12, '2026-05-27', 1779826463, NULL, 'Completed'),
(16, 35, 12, '2026-05-27', 1779834195, NULL, 'Active'),
(17, 36, 12, '2026-05-27', 1779836663, NULL, 'Active'),
(18, 37, 12, '2026-05-27', 1779838403, NULL, 'Active'),
(19, 38, 12, '2026-05-28', 1779913023, NULL, 'Active'),
(20, 39, 12, '2026-05-28', 1779913079, NULL, 'Active'),
(21, 40, 12, '2026-05-28', 1779913118, NULL, 'Active'),
(22, 41, 12, '2026-05-28', 1779913168, NULL, 'Active'),
(23, 42, 12, '2026-05-28', 1779913233, NULL, 'Active'),
(24, 43, 12, '2026-06-01', 1780261886, NULL, 'Active'),
(25, 44, 12, '2026-06-01', 1780264419, NULL, 'Active'),
(26, 45, 12, '2026-06-02', 1780342192, NULL, 'Active'),
(27, 34, 29, '2026-06-02', 1780346508, NULL, 'Active'),
(28, 27, 12, '2026-06-03', 1780440806, NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `admission_hod`
--

DROP TABLE IF EXISTS `admission_hod`;
CREATE TABLE IF NOT EXISTS `admission_hod` (
  `adm_hod_id` int NOT NULL AUTO_INCREMENT,
  `admission_id_fk` int NOT NULL,
  `sch_dept_id_fk` int NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_time` int DEFAULT NULL,
  PRIMARY KEY (`adm_hod_id`),
  UNIQUE KEY `unique_adm_hod` (`admission_id_fk`),
  KEY `fk_adm_hod_admission` (`admission_id_fk`),
  KEY `fk_adm_hod_dept` (`sch_dept_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admission_hod`
--

INSERT INTO `admission_hod` (`adm_hod_id`, `admission_id_fk`, `sch_dept_id_fk`, `created_date`, `created_time`) VALUES
(2, 11, 1, '2026-05-26', 1779770100),
(3, 25, 8, '2026-06-02', 1780348309);

-- --------------------------------------------------------

--
-- Table structure for table `admission_student_role`
--

DROP TABLE IF EXISTS `admission_student_role`;
CREATE TABLE IF NOT EXISTS `admission_student_role` (
  `adm_student_role_id` int NOT NULL AUTO_INCREMENT,
  `admission_id_fk` int NOT NULL,
  `leadership_role` varchar(60) DEFAULT NULL COMMENT 'school_prefect/hostel_prefect/head_boy/head_girl/deputy_head_boy/deputy_head_girl/junior_prefect/relieving_prefect',
  `created_date` date DEFAULT NULL,
  `created_time` int DEFAULT NULL,
  `adm_stud_role_status` varchar(60) NOT NULL,
  PRIMARY KEY (`adm_student_role_id`),
  UNIQUE KEY `unique_adm_student_role` (`admission_id_fk`),
  KEY `fk_asr_admission` (`admission_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admission_student_role`
--

INSERT INTO `admission_student_role` (`adm_student_role_id`, `admission_id_fk`, `leadership_role`, `created_date`, `created_time`, `adm_stud_role_status`) VALUES
(1, 19, 'junior_prefect', '2026-06-02', 1780347986, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `admission_teaching_subject`
--

DROP TABLE IF EXISTS `admission_teaching_subject`;
CREATE TABLE IF NOT EXISTS `admission_teaching_subject` (
  `adm_teach_sub_id` int NOT NULL AUTO_INCREMENT,
  `admission_id_fk` int NOT NULL,
  `sch_sub_id_fk` int NOT NULL,
  `subject_type` varchar(20) DEFAULT 'Core' COMMENT 'Core / Optional',
  `created_date` date DEFAULT NULL,
  `created_time` int DEFAULT NULL,
  `adm_teach_sub_status` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`adm_teach_sub_id`),
  UNIQUE KEY `unique_adm_sub` (`admission_id_fk`,`sch_sub_id_fk`),
  KEY `fk_ats_admission` (`admission_id_fk`),
  KEY `fk_ats_sch_sub` (`sch_sub_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admission_teaching_subject`
--

INSERT INTO `admission_teaching_subject` (`adm_teach_sub_id`, `admission_id_fk`, `sch_sub_id_fk`, `subject_type`, `created_date`, `created_time`, `adm_teach_sub_status`) VALUES
(2, 11, 50, 'Core', '2026-05-26', 1779770090, 'Active'),
(3, 11, 68, 'Core', '2026-05-26', 1779770090, 'Active'),
(4, 11, 86, 'Core', '2026-05-26', 1779770090, 'Active'),
(5, 13, 43, 'Core', '2026-05-26', 1779780195, 'Active'),
(6, 13, 59, 'Core', '2026-05-26', 1779780195, 'Active'),
(7, 13, 77, 'Core', '2026-05-26', 1779780195, 'Active'),
(8, 13, 95, 'Core', '2026-05-26', 1779780195, 'Active'),
(9, 13, 31, 'Core', '2026-05-26', 1779780195, 'Active'),
(10, 11, 30, 'Core', '2026-05-27', 1779849378, 'Active'),
(11, 14, 27, 'Core', '2026-05-27', 1779849695, 'Active'),
(12, 15, 33, 'Core', '2026-05-27', 1779849776, 'Active'),
(13, 13, 26, 'Core', '2026-05-27', 1779849788, 'Active'),
(22, 26, 67, 'Core', '2026-06-02', 1780343239, 'Active'),
(23, 26, 85, 'Core', '2026-06-02', 1780343239, 'Active'),
(31, 26, 103, 'Core', '2026-06-02', 1780343721, 'Active'),
(33, 27, 153, 'Core', '2026-06-02', 1780346508, 'Active'),
(34, 27, 151, 'Core', '2026-06-02', 1780346508, 'Active'),
(35, 27, 168, 'Core', '2026-06-02', 1780346508, 'Active'),
(36, 27, 120, 'Core', '2026-06-02', 1780346508, 'Active'),
(37, 25, 43, 'Core', '2026-06-02', 1780348248, 'Active'),
(38, 25, 59, 'Core', '2026-06-02', 1780348248, 'Active'),
(39, 25, 77, 'Core', '2026-06-02', 1780348248, 'Active'),
(40, 25, 95, 'Core', '2026-06-02', 1780348248, 'Active'),
(41, 25, 31, 'Core', '2026-06-02', 1780348248, 'Active'),
(42, 24, 38, 'Core', '2026-06-02', 1780361137, 'Active'),
(43, 24, 50, 'Core', '2026-06-02', 1780361137, 'Active'),
(44, 24, 68, 'Core', '2026-06-02', 1780361137, 'Active'),
(45, 24, 86, 'Core', '2026-06-02', 1780361137, 'Active'),
(46, 24, 26, 'Core', '2026-06-02', 1780361137, 'Active'),
(47, 14, 39, 'Core', '2026-06-02', 1780363487, 'Active'),
(48, 14, 52, 'Core', '2026-06-02', 1780363487, 'Active'),
(49, 14, 51, 'Core', '2026-06-02', 1780363487, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversations`
--

DROP TABLE IF EXISTS `chat_conversations`;
CREATE TABLE IF NOT EXISTS `chat_conversations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('direct','group') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'direct',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_updated_at` (`updated_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `type`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'direct', NULL, 33, '2026-05-27 15:55:32', '2026-05-28 07:05:21'),
(2, 'direct', NULL, 1, '2026-06-02 12:41:05', '2026-06-02 12:41:05'),
(3, 'direct', NULL, 1, '2026-06-02 12:41:13', '2026-06-02 12:41:13'),
(4, 'direct', NULL, 12, '2026-06-03 08:16:51', '2026-06-03 08:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conversation_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `message_type` enum('text','image','file') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_conversation_created` (`conversation_id`,`created_at`),
  KEY `idx_sender_id` (`sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_id`, `message_type`, `content`, `created_at`, `deleted_at`) VALUES
(1, 1, 33, 'text', 'bula', '2026-05-27 15:55:42', NULL),
(2, 1, 12, 'text', 'hi', '2026-05-27 15:55:56', NULL),
(3, 1, 33, 'text', 'wow', '2026-05-27 16:03:50', NULL),
(4, 1, 12, 'text', 'instant', '2026-05-27 16:04:19', NULL),
(5, 1, 12, 'text', 'testing', '2026-05-27 18:03:41', NULL),
(6, 1, 12, 'text', 'hihihih', '2026-05-27 18:03:51', NULL),
(7, 1, 12, 'text', 'Hi it is \r\na testing \r\nof many lines\r\nin one message', '2026-05-27 18:04:24', NULL),
(8, 1, 12, 'text', 'testing', '2026-05-27 18:04:50', NULL),
(9, 1, 12, 'text', 'test notification', '2026-05-27 18:21:19', NULL),
(10, 1, 33, 'text', 'ok', '2026-05-27 18:21:37', NULL),
(11, 1, 12, 'text', 'no', '2026-05-27 18:21:44', NULL),
(12, 1, 12, 'text', 'shhhh', '2026-05-27 18:32:50', NULL),
(13, 1, 12, 'text', 'sdgsfgsgd', '2026-05-27 18:33:00', NULL),
(14, 1, 12, 'text', 'hi', '2026-05-27 18:39:54', NULL),
(15, 1, 33, 'text', 'setto', '2026-05-27 18:40:17', NULL),
(16, 1, 12, 'text', 'set', '2026-05-27 19:10:18', NULL),
(17, 1, 12, 'text', 'to do', '2026-05-27 19:10:29', NULL),
(18, 1, 33, 'text', '7.09', '2026-05-28 07:03:09', NULL),
(19, 1, 33, 'text', 'hiu', '2026-05-28 07:05:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_files`
--

DROP TABLE IF EXISTS `chat_message_files`;
CREATE TABLE IF NOT EXISTS `chat_message_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stored_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_message_id` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_participants`
--

DROP TABLE IF EXISTS `chat_participants`;
CREATE TABLE IF NOT EXISTS `chat_participants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conversation_id` int NOT NULL,
  `user_id` int NOT NULL,
  `joined_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_read_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_participant` (`conversation_id`,`user_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_participants`
--

INSERT INTO `chat_participants` (`id`, `conversation_id`, `user_id`, `joined_at`, `last_read_at`) VALUES
(1, 1, 33, '2026-05-27 15:55:32', '2026-06-01 16:20:24'),
(2, 1, 12, '2026-05-27 15:55:32', '2026-05-28 07:03:19'),
(3, 2, 1, '2026-06-02 12:41:05', '2026-06-02 12:41:06'),
(4, 2, 33, '2026-06-02 12:41:05', NULL),
(5, 3, 1, '2026-06-02 12:41:13', '2026-06-02 12:41:13'),
(6, 3, 12, '2026-06-02 12:41:13', NULL),
(7, 4, 12, '2026-06-03 08:16:51', '2026-06-03 08:16:51'),
(8, 4, 43, '2026-06-03 08:16:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

DROP TABLE IF EXISTS `classroom`;
CREATE TABLE IF NOT EXISTS `classroom` (
  `class_id` int NOT NULL AUTO_INCREMENT,
  `stream_id_fk` int NOT NULL,
  `class_name` varchar(260) NOT NULL,
  `class_year` int NOT NULL,
  `class_created_at` datetime DEFAULT NULL,
  `class_updated_at` datetime DEFAULT NULL,
  `class_created_by` int NOT NULL,
  `class_updated_by` int NOT NULL,
  `class_status` varchar(60) NOT NULL,
  PRIMARY KEY (`class_id`),
  KEY `stream_id_fk` (`stream_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`class_id`, `stream_id_fk`, `class_name`, `class_year`, `class_created_at`, `class_updated_at`, `class_created_by`, `class_updated_by`, `class_status`) VALUES
(3, 91, 'Year 9A 2026', 2026, '2026-05-27 08:19:18', '2026-05-27 08:19:18', 1, 1, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `classroom_lesson`
--

DROP TABLE IF EXISTS `classroom_lesson`;
CREATE TABLE IF NOT EXISTS `classroom_lesson` (
  `lesson_id` int NOT NULL AUTO_INCREMENT,
  `class_sub_id_fk` int NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `lesson_desc` text,
  `lesson_term` tinyint NOT NULL DEFAULT '1',
  `lesson_week` tinyint DEFAULT NULL,
  `lesson_order` int NOT NULL DEFAULT '1',
  `lesson_duration` int DEFAULT NULL,
  `lesson_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Published',
  `created_by` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`lesson_id`),
  KEY `fk_lesson_class_sub` (`class_sub_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom_lesson`
--

INSERT INTO `classroom_lesson` (`lesson_id`, `class_sub_id_fk`, `lesson_title`, `lesson_desc`, `lesson_term`, `lesson_week`, `lesson_order`, `lesson_duration`, `lesson_status`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 7, 'Introduction To Social Science', 'This sets the stage for the lesson. It usually includes a welcome message, the learning objectives (what students should know by the end), and any necessary prerequisites or instructions', 1, 1, 1, NULL, 'Published', 12, '2026-06-03 15:33:24', NULL),
(4, 7, 'Test Lesson', 'Test desc', 2, 1, 1, NULL, 'Draft', 12, '2026-06-03 16:11:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classroom_role`
--

DROP TABLE IF EXISTS `classroom_role`;
CREATE TABLE IF NOT EXISTS `classroom_role` (
  `cs_id` int NOT NULL AUTO_INCREMENT,
  `class_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `cs_role` varchar(260) NOT NULL,
  `cs_status` varchar(60) NOT NULL,
  `cs_assigned_at` date NOT NULL,
  `cs_assigned_by` int NOT NULL,
  PRIMARY KEY (`cs_id`),
  KEY `fk_classroom_staff_class` (`class_id_fk`),
  KEY `fk_classroom_staff_user` (`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom_role`
--

INSERT INTO `classroom_role` (`cs_id`, `class_id_fk`, `user_id_fk`, `cs_role`, `cs_status`, `cs_assigned_at`, `cs_assigned_by`) VALUES
(1, 3, 34, 'Class Teacher', 'Inactive', '2026-05-27', 1),
(2, 3, 33, 'Assistant Class Teacher', 'Active', '2026-05-27', 1),
(3, 3, 34, 'Class Teacher', 'Inactive', '2026-05-27', 1),
(4, 3, 12, 'Class Teacher', 'Active', '2026-05-27', 1),
(5, 3, 37, 'Class Captain', 'Active', '2026-05-27', 1),
(6, 3, 30, 'Assistant Class Captain', 'Active', '2026-05-27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `classroom_student`
--

DROP TABLE IF EXISTS `classroom_student`;
CREATE TABLE IF NOT EXISTS `classroom_student` (
  `class_stud_id` int NOT NULL AUTO_INCREMENT,
  `class_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `admitted_at` datetime DEFAULT NULL,
  `admitted_by` int NOT NULL,
  `class_stud_status` varchar(60) NOT NULL,
  PRIMARY KEY (`class_stud_id`),
  KEY `fk_classroom_student_user` (`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom_student`
--

INSERT INTO `classroom_student` (`class_stud_id`, `class_id_fk`, `user_id_fk`, `admitted_at`, `admitted_by`, `class_stud_status`) VALUES
(1, 3, 41, '2026-06-03 00:00:00', 12, 'Active'),
(2, 3, 40, '2026-06-03 00:00:00', 12, 'Active'),
(3, 3, 38, '2026-06-03 00:00:00', 12, 'Active'),
(4, 3, 37, '2026-06-03 00:00:00', 12, 'Active'),
(5, 3, 39, '2026-06-03 00:00:00', 12, 'Active'),
(6, 3, 36, '2026-06-03 00:00:00', 12, 'Active'),
(7, 3, 42, '2026-06-03 00:00:00', 12, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `classroom_subject`
--

DROP TABLE IF EXISTS `classroom_subject`;
CREATE TABLE IF NOT EXISTS `classroom_subject` (
  `class_sub_id` int NOT NULL AUTO_INCREMENT,
  `class_id_fk` int NOT NULL,
  `sub_id_fk` int NOT NULL,
  PRIMARY KEY (`class_sub_id`),
  KEY `fk_classroom_subject_class` (`class_id_fk`),
  KEY `fk_classroom_subject_sub` (`sub_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom_subject`
--

INSERT INTO `classroom_subject` (`class_sub_id`, `class_id_fk`, `sub_id_fk`) VALUES
(1, 3, 30),
(2, 3, 27),
(3, 3, 33),
(4, 3, 26),
(5, 3, 21),
(6, 3, 34),
(7, 3, 37),
(8, 3, 29),
(9, 3, 109),
(10, 3, 28),
(11, 3, 31),
(12, 3, 36),
(13, 3, 32),
(14, 3, 35);

-- --------------------------------------------------------

--
-- Table structure for table `classroom_subject_teacher`
--

DROP TABLE IF EXISTS `classroom_subject_teacher`;
CREATE TABLE IF NOT EXISTS `classroom_subject_teacher` (
  `class_sub_teacher_id` int NOT NULL AUTO_INCREMENT,
  `class_sub_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `class_sub_teacher_status` varchar(60) NOT NULL,
  PRIMARY KEY (`class_sub_teacher_id`),
  KEY `fk_cst_class_sub` (`class_sub_id_fk`),
  KEY `fk_cst_user` (`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom_subject_teacher`
--

INSERT INTO `classroom_subject_teacher` (`class_sub_teacher_id`, `class_sub_id_fk`, `user_id_fk`, `class_sub_teacher_status`) VALUES
(1, 11, 12, 'Active'),
(2, 7, 12, 'Active'),
(3, 1, 12, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `dept_id` int NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(260) DEFAULT NULL,
  `dept_desc` longtext NOT NULL,
  `dept_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dept_theme` varchar(60) NOT NULL,
  `dept_icon` varchar(500) NOT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_id`, `dept_name`, `dept_desc`, `dept_code`, `dept_theme`, `dept_icon`) VALUES
(1, 'Language', '', 'L', 'primary', '<i class=\"ki-duotone ki-category fs-3x text-primary me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>'),
(2, 'Science', '', 'S', 'warning', '<i class=\"ki-duotone ki-bucket fs-3x text-warning me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>'),
(3, 'Social Science', '', 'SS', 'info', '<i class=\"ki-duotone ki-feather fs-3x text-info me-4\"><span class=\"path1\"></span><span class=\"path2\"></span></i>'),
(4, 'Mathematics and Physics', '', 'MP', 'danger', '<i class=\"ki-duotone ki-chart-simple fs-3x text-danger me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>'),
(5, 'Commercial Studies', '', 'COM', 'dark', '<i class=\"ki-duotone ki-dollar fs-3x text-dark me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>'),
(6, 'Computer Science', '', 'CS', 'success', '<i class=\"ki-duotone ki-screen fs-3x text-success me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>'),
(7, 'Home Economic', '', 'HE', 'dark', '<i class=\"ki-duotone ki-home-1 fs-3x text-dark me-4\"><span class=\"path1\"></span><span class=\"path2\"></span></i>'),
(8, 'Agriculture', '', 'AG', 'primary', '<i class=\"ki-duotone ki-technology-3 fs-3x text-primary me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>'),
(9, 'Industrial Arts', '', 'IA', 'danger', '<i class=\"ki-duotone ki-frame fs-3x text-danger me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>'),
(10, 'Physical Education Music and Art & Craft', '', 'PE', 'success', '<i class=\"ki-duotone ki-graph fs-3x text-success me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>'),
(11, 'Religious Education', '', 'RE', 'warning', '<i class=\"ki-duotone ki-book-open fs-3x text-warning me-4\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>');

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

DROP TABLE IF EXISTS `district`;
CREATE TABLE IF NOT EXISTS `district` (
  `district_id` int NOT NULL AUTO_INCREMENT,
  `province_id_fk` int NOT NULL,
  `district_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`district_id`),
  KEY `fk_ditrict_province` (`province_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`district_id`, `province_id_fk`, `district_name`) VALUES
(1, 1, 'Nailaga'),
(2, 1, 'Bulu'),
(3, 1, 'Magadro'),
(4, 1, 'Naloto'),
(5, 1, 'Nalotawa'),
(6, 1, 'Nadi'),
(7, 1, 'Sikituru'),
(8, 1, 'Tavua'),
(9, 1, 'Savatu'),
(10, 1, 'Qaliyalatini'),
(11, 1, 'Vuda'),
(12, 1, 'Sabeto'),
(13, 1, 'Vitogo'),
(14, 1, 'Nacula'),
(15, 1, 'Yasawa'),
(16, 1, 'Naviti'),
(17, 1, 'Waya'),
(18, 1, 'Viwa'),
(19, 1, 'Nawaka'),
(20, 1, 'Vaturu'),
(21, 1, 'Rukuruku'),
(22, 2, 'Bua'),
(23, 2, 'Navakasiga'),
(24, 2, 'Lekutu'),
(25, 2, 'Wainunu'),
(26, 2, 'Solevu'),
(27, 2, 'Nadi'),
(28, 2, 'Vuya'),
(29, 2, 'Dama'),
(30, 2, 'Kubulau'),
(31, 3, 'Cakaudrove'),
(32, 3, 'Vuna'),
(33, 3, 'Laucala'),
(34, 3, 'Wainikeli'),
(35, 3, 'Tunuloa'),
(36, 3, 'Natewa'),
(37, 3, 'Savusavu'),
(38, 3, 'Naweni'),
(39, 3, 'Navatu'),
(40, 3, 'Vaturova'),
(41, 3, 'Koroalau'),
(42, 3, 'Wairiki'),
(43, 3, 'Saqani'),
(44, 3, 'Tawake'),
(45, 3, 'Wailevu West'),
(46, 3, 'Wailevu East'),
(47, 4, 'Tavuki'),
(48, 4, 'Ravitaki'),
(49, 4, 'Sanima'),
(50, 4, 'Nabukelevu'),
(51, 4, 'Yawe'),
(52, 4, 'Naceva'),
(53, 4, 'Yale'),
(54, 4, 'Nakasaleka'),
(55, 4, 'Ono'),
(56, 5, 'Lakeba'),
(57, 5, 'Oneata'),
(58, 5, 'Moce'),
(60, 5, 'Vulaga'),
(61, 5, 'Ono'),
(62, 5, 'Kabara'),
(63, 5, 'Totoya'),
(64, 5, 'Moala'),
(65, 5, 'Matuku'),
(66, 5, 'Nayau'),
(67, 5, 'Lomaloma'),
(68, 5, 'Mualevu'),
(69, 6, 'Levuka'),
(70, 6, 'Nasinu'),
(71, 6, 'Lovoni'),
(72, 6, 'Bureta'),
(73, 6, 'Motoriki'),
(74, 6, 'Batiki'),
(75, 6, 'Mudu'),
(76, 6, 'Cawa'),
(77, 6, 'Nairai'),
(78, 6, 'Sawaieke'),
(79, 6, 'Navukailagi'),
(80, 6, 'Vanuaso'),
(81, 7, 'Macuata'),
(82, 7, 'Mali'),
(83, 7, 'Dreketi'),
(84, 7, 'Cikobia'),
(85, 7, 'Namuka'),
(86, 7, 'Dogotuki'),
(87, 7, 'Udu'),
(88, 7, 'Sasa'),
(89, 7, 'Seaqaqa'),
(90, 7, 'Labasa'),
(91, 7, 'Nadogo'),
(92, 7, 'Wailevu'),
(93, 8, 'Cuvu'),
(94, 8, 'Tuva'),
(95, 8, 'Nasigatoka'),
(96, 8, 'Nokonoko'),
(97, 8, 'Waicoba'),
(98, 8, 'Malomalo'),
(99, 8, 'Raviravi'),
(100, 8, 'Wai'),
(101, 8, 'Conua'),
(102, 8, 'Komave'),
(103, 8, 'Korolevuiwai'),
(104, 8, 'Koroinasau'),
(105, 8, 'Naqalimare'),
(106, 8, 'Bemana'),
(107, 8, 'Nasikawa'),
(108, 8, 'Namataku'),
(109, 8, 'Naikoro'),
(110, 8, 'Nadrau'),
(111, 8, 'Navatusila'),
(112, 8, 'Vatulele'),
(113, 8, 'Malolo'),
(114, 9, 'Naitasiri'),
(115, 9, 'Vuna'),
(116, 9, 'Viria'),
(117, 9, 'Navuakece'),
(118, 9, 'Rara'),
(119, 9, 'Nabaitavo'),
(120, 9, 'Waidina'),
(121, 9, 'Soloira'),
(122, 9, 'Matailobau'),
(123, 9, 'Waimana'),
(124, 9, 'Lutu'),
(125, 9, 'Nagonenicolo'),
(126, 9, 'Noimalu'),
(127, 9, 'Muaira'),
(128, 9, 'Nadaravakawalu'),
(129, 9, 'Nabobuco'),
(130, 10, 'Namosi'),
(131, 10, 'Wainikoroiluva'),
(132, 10, 'Veinuqa'),
(133, 10, 'Naqarawai'),
(134, 10, 'Veivatuloa'),
(135, 11, 'Naroko'),
(136, 11, 'Tokaimalo'),
(137, 11, 'Saivou'),
(138, 11, 'Nailuva'),
(139, 11, 'Nalaba'),
(140, 11, 'Rakiraki'),
(141, 11, 'Raviravi'),
(142, 11, 'Navoloau'),
(143, 11, 'Nakorotubu'),
(144, 11, 'Bureiwai'),
(145, 11, 'Kavula'),
(146, 11, 'Bureivanua'),
(147, 11, 'Nakuilava'),
(148, 11, 'Mataso'),
(149, 11, 'Navitilevu'),
(150, 11, 'Lawaki'),
(151, 11, 'Nasau'),
(152, 11, 'Nalawa'),
(153, 11, 'Nababa'),
(154, 12, 'Rewa'),
(155, 12, 'Vutia'),
(156, 12, 'Toga'),
(157, 12, 'Noco'),
(158, 12, 'Burebasaga'),
(159, 12, 'Dreketi'),
(160, 12, 'Suva'),
(161, 12, 'Sawau'),
(162, 12, 'Raviravi'),
(163, 13, 'Serua'),
(164, 13, 'Deuba'),
(165, 13, 'Nuku'),
(166, 13, 'Batiwai'),
(167, 14, 'Bau'),
(168, 14, 'Namara'),
(169, 14, 'Nausori'),
(170, 14, 'Dravo'),
(171, 14, 'Namata'),
(172, 14, 'Nakelo'),
(173, 14, 'Nuku'),
(174, 14, 'Tokatoka'),
(175, 14, 'Buretu'),
(176, 14, 'Verata'),
(177, 14, 'Namalata'),
(178, 14, 'Tai'),
(179, 14, 'Vugalei'),
(180, 14, 'Taivugalei'),
(181, 14, 'Sawakasa'),
(182, 14, 'Namena'),
(183, 14, 'Dawasamu'),
(184, 14, 'Naloto'),
(185, 14, 'Wailotua'),
(186, 14, 'Nasautoka'),
(187, 14, 'Nayavu'),
(188, 14, 'Nailega'),
(189, 15, 'Noa\'atau'),
(190, 15, 'Oinafa'),
(191, 15, 'Malhaha'),
(192, 15, 'Itu\'ti\'u'),
(193, 15, 'Itu\'muta'),
(194, 15, 'Pepjei Pepjei Juju'),
(195, 16, 'Foreign Citizen');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

DROP TABLE IF EXISTS `division`;
CREATE TABLE IF NOT EXISTS `division` (
  `division_id` int NOT NULL AUTO_INCREMENT,
  `division_name` varchar(260) DEFAULT NULL,
  PRIMARY KEY (`division_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `division`
--

INSERT INTO `division` (`division_id`, `division_name`) VALUES
(1, 'Central'),
(2, 'Eastern'),
(3, 'Western'),
(4, 'Northern'),
(5, 'Foreign Country');

-- --------------------------------------------------------

--
-- Table structure for table `enrolment`
--

DROP TABLE IF EXISTS `enrolment`;
CREATE TABLE IF NOT EXISTS `enrolment` (
  `enrol_id` int NOT NULL AUTO_INCREMENT,
  `admission_id_fk` int NOT NULL,
  `stream_id_fk` int NOT NULL,
  `enrol_date` date DEFAULT NULL,
  `enrol_time` int DEFAULT NULL,
  `enrol_term` int DEFAULT NULL,
  `enrol_year` int DEFAULT NULL,
  `enrol_note` longtext,
  `enrol_status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`enrol_id`),
  KEY `fk_enrolment_admission` (`admission_id_fk`),
  KEY `stream_id_fk` (`stream_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `enrolment`
--

INSERT INTO `enrolment` (`enrol_id`, `admission_id_fk`, `stream_id_fk`, `enrol_date`, `enrol_time`, `enrol_term`, `enrol_year`, `enrol_note`, `enrol_status`) VALUES
(8, 12, 100, '2026-05-12', NULL, 1, 2026, NULL, 'Completed'),
(9, 10, 91, '2026-05-26', 1779756947, 2, 2026, NULL, 'Completed'),
(10, 17, 91, '2026-05-27', 1779836663, 2, 2026, '', 'Active'),
(11, 18, 91, '2026-05-27', 1779838403, 2, 2026, '', 'Active'),
(12, 19, 91, '2026-05-28', 1779913023, 2, 2026, '', 'Active'),
(13, 20, 91, '2026-05-28', 1779913079, 2, 2026, '', 'Active'),
(14, 21, 91, '2026-05-28', 1779913118, 2, 2026, '', 'Active'),
(15, 22, 91, '2026-05-28', 1779913168, 2, 2026, '', 'Active'),
(16, 23, 91, '2026-05-28', 1779913233, 2, 2026, '', 'Active'),
(17, 16, 95, '2026-06-03', 1780435522, 2, 2026, NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `generated_reference`
--

DROP TABLE IF EXISTS `generated_reference`;
CREATE TABLE IF NOT EXISTS `generated_reference` (
  `gen_ref_id` int NOT NULL AUTO_INCREMENT,
  `ref_cat_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `gen_ref_by` int NOT NULL,
  `gen_ref_file_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gen_ref_date` datetime NOT NULL,
  `gen_ref_time` int NOT NULL,
  `gen_ref_status` varchar(60) NOT NULL,
  PRIMARY KEY (`gen_ref_id`),
  KEY `ref_cat_id_fk` (`ref_cat_id_fk`),
  KEY `user_id_fk` (`user_id_fk`),
  KEY `gen_ref_by` (`gen_ref_by`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `generated_reference`
--

INSERT INTO `generated_reference` (`gen_ref_id`, `ref_cat_id_fk`, `user_id_fk`, `gen_ref_by`, `gen_ref_file_name`, `gen_ref_date`, `gen_ref_time`, `gen_ref_status`) VALUES
(1, 2, 18, 1, 'char_ref_18_20260512_161616.pdf', '2026-05-12 16:16:16', 1778559376, 'Outdated'),
(2, 2, 32, 1, 'char_ref_32_20260512_162557.pdf', '2026-05-12 16:25:58', 1778559958, 'Current'),
(4, 3, 18, 1, 'recommendation_18_20260513_083216.pdf', '2026-05-13 08:32:16', 1778617936, 'Outdated'),
(7, 1, 18, 1, 'enrollment_18_20260513_094408.pdf', '2026-05-13 09:44:09', 1778622249, 'Outdated'),
(8, 2, 18, 1, 'char_ref_18_20260513_095325.pdf', '2026-05-13 09:53:26', 1778622806, 'Outdated'),
(9, 3, 18, 1, 'recommendation_18_20260513_095745.pdf', '2026-05-13 09:57:45', 1778623065, 'Current'),
(10, 4, 18, 1, 'transcript_18_20260513_100807.pdf', '2026-05-13 10:08:07', 1778623687, 'Current'),
(11, 5, 18, 1, 'conduct_18_20260513_101415.pdf', '2026-05-13 10:14:16', 1778624056, 'Outdated'),
(12, 6, 18, 1, 'clearance_18_20260513_101648.pdf', '2026-05-13 10:16:49', 1778624209, 'Outdated'),
(13, 1, 18, 1, 'enrollment_18_20260513_104811.pdf', '2026-05-13 10:48:11', 1778626091, 'Current'),
(14, 2, 18, 1, 'char_ref_18_20260513_105143.pdf', '2026-05-13 10:51:43', 1778626303, 'Current'),
(15, 9, 14, 1, 'parent_guardian_14_20260513_105547.pdf', '2026-05-13 10:55:47', 1778626547, 'Current'),
(16, 5, 18, 1, 'conduct_18_20260513_112626.pdf', '2026-05-13 11:26:26', 1778628386, 'Outdated'),
(17, 5, 18, 1, 'conduct_18_20260513_113020.pdf', '2026-05-13 11:30:20', 1778628620, 'Current'),
(18, 6, 18, 1, 'clearance_18_20260513_114518.pdf', '2026-05-13 11:45:18', 1778629518, 'Current');

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

DROP TABLE IF EXISTS `house`;
CREATE TABLE IF NOT EXISTS `house` (
  `house_id` int NOT NULL AUTO_INCREMENT,
  `sch_id_fk` int NOT NULL,
  `house_name` varchar(60) DEFAULT NULL,
  `house_color` varchar(45) DEFAULT NULL,
  `house_moto` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`house_id`),
  KEY `fk_house_school` (`sch_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `launch_notification`
--

DROP TABLE IF EXISTS `launch_notification`;
CREATE TABLE IF NOT EXISTS `launch_notification` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','notified') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  KEY `idx_status` (`status`),
  KEY `idx_date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `launch_notification`
--

INSERT INTO `launch_notification` (`id`, `email`, `date`, `ip_address`, `user_agent`, `status`) VALUES
(1, 'piobaleicoqe2@gmail.com', '2026-03-03 15:13:07', '27.123.137.233', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'pending'),
(2, 'piobaleicoqe@yahoo.com', '2026-03-03 15:14:01', '27.123.137.233', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_discussion`
--

DROP TABLE IF EXISTS `lesson_discussion`;
CREATE TABLE IF NOT EXISTS `lesson_discussion` (
  `lesson_discussion_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id_fk` int NOT NULL,
  `author` int NOT NULL,
  `message` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_time` int NOT NULL,
  `message_status` int NOT NULL,
  PRIMARY KEY (`lesson_discussion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_discussion`
--

INSERT INTO `lesson_discussion` (`lesson_discussion_id`, `lesson_id_fk`, `author`, `message`, `created_at`, `updated_at`, `created_time`, `message_status`) VALUES
(1, 3, 12, 'Hi there everyone, first of all i want to welcome everyone into this class and hope that our journey throughout the duration of the course will be a contructive and a fruitful one.', '2026-06-03 21:43:38', '2026-06-03 21:43:38', 1780479818, 1),
(2, 3, 12, 'Social studies is an interdisciplinary subject that explores the complexities of human society by weaving together elements of history, geography, economics, political science, and sociology to help students understand how the world works and their place within it. Far more than just memorizing dates and names, social studies encourages critical thinking about past events, cultural traditions, governance systems, and economic forces, all while fostering civic awareness and responsibility. Through its lens, students examine how communities form and change, how resources and power are distributed, how conflicts arise and are resolved, and how geography shapes human behavior and development. This subject empowers learners to analyze current issues—from climate change and migration to social justice and global trade—by drawing connections across time and place, recognizing patterns, and appreciating diverse perspectives. Ultimately, social studies aims to cultivate informed, empathetic, and active citizens who can participate thoughtfully in democratic processes, respect cultural differences, and contribute meaningfully to an interconnected and rapidly evolving world.\r\n\r\nOf course. Here is another paragraph that captures a different angle on the social studies subject.\r\n\r\nSocial studies is fundamentally the study of how people live together, and it serves as a vital bridge between the individual and the vast, often overwhelming machinery of society. Rather than isolating facts into separate silos, the subject invites students to investigate real-world issues through an integrated lens, asking questions like: Why do people move from place to place? How do scarcity and choice shape our daily lives? What does it mean to be a member of a community, a nation, or a global population? Through inquiry-based learning, students analyze primary sources, debate historical decisions, interpret economic charts, and map human-environment interactions, all while developing essential skills like evidence-based reasoning, media literacy, and respectful discourse. In doing so, social studies transforms abstract concepts like democracy, culture, or supply and demand into tangible ideas that students can see reflected in their own neighborhoods and news feeds. By nurturing both intellectual curiosity and a sense of ethical responsibility, the subject prepares young people not merely to pass tests, but to navigate complexity, challenge injustice, advocate for change, and ultimately, to become the thoughtful architects of our collective future.', '2026-06-03 22:39:39', '2026-06-03 22:39:39', 1780483179, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_discussion_comment`
--

DROP TABLE IF EXISTS `lesson_discussion_comment`;
CREATE TABLE IF NOT EXISTS `lesson_discussion_comment` (
  `comment_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `discussion_id_fk` int NOT NULL,
  `author` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `comment_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_discussion_comment`
--

INSERT INTO `lesson_discussion_comment` (`comment_id`, `discussion_id_fk`, `author`, `comment`, `created_at`, `comment_status`) VALUES
(1, 1, 12, 'ok this is the first comment', '2026-06-03 21:50:16', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_discussion_comment_like`
--

DROP TABLE IF EXISTS `lesson_discussion_comment_like`;
CREATE TABLE IF NOT EXISTS `lesson_discussion_comment_like` (
  `clike_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `comment_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `like_type` enum('like','dislike') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'like',
  PRIMARY KEY (`clike_id`),
  UNIQUE KEY `comment_id_fk_user_id_fk` (`comment_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_discussion_comment_like`
--

INSERT INTO `lesson_discussion_comment_like` (`clike_id`, `comment_id_fk`, `user_id_fk`, `like_type`) VALUES
(2, 1, 12, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_discussion_like`
--

DROP TABLE IF EXISTS `lesson_discussion_like`;
CREATE TABLE IF NOT EXISTS `lesson_discussion_like` (
  `like_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `discussion_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `like_type` enum('like','dislike') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`like_id`),
  UNIQUE KEY `discussion_id_fk_user_id_fk` (`discussion_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_discussion_like`
--

INSERT INTO `lesson_discussion_like` (`like_id`, `discussion_id_fk`, `user_id_fk`, `like_type`) VALUES
(5, 1, 12, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_file`
--

DROP TABLE IF EXISTS `lesson_file`;
CREATE TABLE IF NOT EXISTS `lesson_file` (
  `file_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id_fk` int NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `uploaded_at` datetime NOT NULL,
  `uploaded_by` int NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `fk_file_lesson` (`lesson_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_file`
--

INSERT INTO `lesson_file` (`file_id`, `lesson_id_fk`, `file_path`, `file_name`, `file_type`, `file_size`, `uploaded_at`, `uploaded_by`) VALUES
(1, 3, 'lesson_3_1780458814_7718.pdf', 'Bluehost Hand Over Minute.pdf', 'pdf', 135821, '2026-06-03 15:53:34', 12),
(2, 3, 'lesson_3_1780458814_6874.pdf', 'Bluehost Memo Signed.pdf', 'pdf', 174855, '2026-06-03 15:53:34', 12),
(3, 3, 'lesson_3_1780458814_8214.docx', 'Chat GPT Plan.docx', 'docx', 17476, '2026-06-03 15:53:34', 12),
(4, 3, 'lesson_3_1780458814_4584.pdf', 'Software and Themes Purchase.pdf', 'pdf', 176841, '2026-06-03 15:53:34', 12),
(5, 3, 'lesson_3_1780459489_9654.png', 'tagimoucia prod logo.png', 'png', 4917, '2026-06-03 16:04:49', 12),
(6, 3, 'lesson_3_1780459489_3722.jpg', 'tapa.jpg', 'jpg', 443488, '2026-06-03 16:04:49', 12),
(7, 3, 'lesson_3_1780459489_7889.jpg', 'UMC.jpg', 'jpg', 97059, '2026-06-03 16:04:49', 12),
(8, 3, 'lesson_3_1780459489_6307.jpg', 'Youtube Banner 3.jpg', 'jpg', 59811, '2026-06-03 16:04:49', 12),
(12, 3, 'lesson_3_1780473365_2083.jpg', 'Gerby Kindy Photos.jpg', 'jpg', 663207, '2026-06-03 19:56:05', 12);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_link`
--

DROP TABLE IF EXISTS `lesson_link`;
CREATE TABLE IF NOT EXISTS `lesson_link` (
  `link_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id_fk` int NOT NULL,
  `link_url` varchar(500) NOT NULL,
  `link_title` varchar(255) DEFAULT NULL,
  `link_order` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`link_id`),
  KEY `fk_link_lesson` (`lesson_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_link`
--

INSERT INTO `lesson_link` (`link_id`, `lesson_id_fk`, `link_url`, `link_title`, `link_order`) VALUES
(1, 3, 'https://www.fiji.travel/', 'Tourism Fiji', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_quizze`
--

DROP TABLE IF EXISTS `lesson_quizze`;
CREATE TABLE IF NOT EXISTS `lesson_quizze` (
  `lesson_quizze_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id_fk` int NOT NULL,
  `quizze_name` varchar(260) NOT NULL,
  `quizze_duration` int NOT NULL COMMENT 'time in minutes',
  `quizze_status` varchar(60) NOT NULL,
  PRIMARY KEY (`lesson_quizze_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_quizze_answer`
--

DROP TABLE IF EXISTS `lesson_quizze_answer`;
CREATE TABLE IF NOT EXISTS `lesson_quizze_answer` (
  `lesson_quizze_answer_id` int DEFAULT NULL,
  `quizze_quest_id_fk` int NOT NULL,
  `answer` varchar(260) NOT NULL,
  `is_correct_answer` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_quizze_question`
--

DROP TABLE IF EXISTS `lesson_quizze_question`;
CREATE TABLE IF NOT EXISTS `lesson_quizze_question` (
  `quizze_quest_id` int NOT NULL AUTO_INCREMENT,
  `lesson_quizze_id_fk` int NOT NULL,
  `question` longtext NOT NULL,
  `status` varchar(60) NOT NULL,
  PRIMARY KEY (`quizze_quest_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_quizze_question_file`
--

DROP TABLE IF EXISTS `lesson_quizze_question_file`;
CREATE TABLE IF NOT EXISTS `lesson_quizze_question_file` (
  `lesson_quizze_quest_file_id` int NOT NULL AUTO_INCREMENT,
  `quizze_quest_id_fk` int NOT NULL,
  `file_src` varchar(260) NOT NULL,
  `status` varchar(60) NOT NULL,
  PRIMARY KEY (`lesson_quizze_quest_file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_video`
--

DROP TABLE IF EXISTS `lesson_video`;
CREATE TABLE IF NOT EXISTS `lesson_video` (
  `video_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id_fk` int NOT NULL,
  `video_url` varchar(500) NOT NULL,
  `video_title` varchar(255) DEFAULT NULL,
  `video_order` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`video_id`),
  KEY `fk_video_lesson` (`lesson_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_video`
--

INSERT INTO `lesson_video` (`video_id`, `lesson_id_fk`, `video_url`, `video_title`, `video_order`) VALUES
(1, 3, 'https://youtu.be/GWhUuGN59Nk?si=1WvIcgRtES-cguh9', 'Gather Song Sample', 1),
(2, 3, 'https://youtu.be/ndDpjT0_IM0?si=u-XtmsEZitQLphC1', 'How Your Brain Works', 2);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
CREATE TABLE IF NOT EXISTS `level` (
  `level_id` int NOT NULL AUTO_INCREMENT,
  `sch_cat_id_fk` int NOT NULL,
  `level_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`level_id`),
  KEY `fk_level_sch_category` (`sch_cat_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`level_id`, `sch_cat_id_fk`, `level_name`) VALUES
(1, 1, 'Pre School'),
(2, 2, 'Kindergarten'),
(3, 3, 'Year 1'),
(4, 3, 'Year 2'),
(5, 3, 'Year 3'),
(6, 3, 'Year 4'),
(7, 3, 'Year 5'),
(8, 3, 'Year 6'),
(9, 3, 'Year 7'),
(10, 3, 'Year 8'),
(11, 4, 'Year 9'),
(12, 4, 'Year 10'),
(13, 4, 'Year 11'),
(14, 4, 'Year 12'),
(15, 4, 'Year 13');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `module_id` int NOT NULL AUTO_INCREMENT,
  `module_name` varchar(60) DEFAULT NULL,
  `module_icon` varchar(260) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `module_svg` varchar(260) DEFAULT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `module_name`, `module_icon`, `module_svg`) VALUES
(1, 'Dashboard', '<i class=\"ki-duotone ki-element-11 fs-2\"><span class=\"path1\"/><span class=\"path2\"/><span class=\"path3\"/><span class=\"path4\"/></i>', ''),
(2, 'RBAC', '<i class=\"ki-duotone ki-abstract-41 fs-2\"><span class=\"path1\"/><span class=\"path2\"/></i>', ''),
(3, 'User', '<i class=\"ki-duotone ki-user fs-2\"><span class=\"path1\"/><span class=\"path2\"/></i>', ''),
(4, 'School', '<i class=\"ki-duotone ki-bank fs-2\"><span class=\"path1\"/><span class=\"path2\"/></i>', ''),
(5, 'Admission', '<i class=\"ki-duotone ki-element-plus fs-2\"><span class=\"path1\"/><span class=\"path2\"/><span class=\"path3\"/><span class=\"path4\"/><span class=\"path5\"/></i>', ''),
(6, 'Enrolment', '<i class=\"ki-duotone ki-abstract-28 fs-2\"><span class=\"path1\"/><span class=\"path2\"/></i>', ''),
(7, 'Classroom', '<i class=\"ki-duotone ki-element-7 fs-2\"><span class=\"path1\"/><span class=\"path2\"/></i>', ''),
(8, 'Attendance', '<i class=\"ki-duotone ki-bookmark\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', NULL),
(9, 'Exam', '<i class=\"ki-duotone ki-chart-pie-3 fs-2\"><span class=\"path1\"/><span class=\"path2\"/><span class=\"path3\"/></i>', ''),
(10, 'Conduct', '<i class=\"ki-duotone ki-bucket fs-2\"><span class=\"path1\"/><span class=\"path2\"/><span class=\"path3\"/><span class=\"path4\"/></i>', ''),
(11, 'Timetable', '<i class=\"ki-duotone ki-calendar-8 fs-2\"><span class=\"path1\"/><span class=\"path2\"/><span class=\"path3\"/><span class=\"path4\"/><span class=\"path5\"/><span class=\"path6\"/></i>', ''),
(12, 'Event', '<i class=\"ki-duotone ki-calendar-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span><span class=\"path6\"></span></i>', ''),
(13, 'Communication', '<i class=\"ki-duotone ki-message-text-2 fs-2\"><span class=\"path1\"/><span class=\"path2\"/><span class=\"path3\"/></i>', '');

-- --------------------------------------------------------

--
-- Table structure for table `next_of_kin`
--

DROP TABLE IF EXISTS `next_of_kin`;
CREATE TABLE IF NOT EXISTS `next_of_kin` (
  `next_of_kin_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `next_of_kin_name` varchar(260) DEFAULT NULL,
  `next_of_kin_relationship` varchar(260) DEFAULT NULL,
  `next_of_kin_address` varchar(260) DEFAULT NULL,
  `next_of_kin_phone` int DEFAULT NULL,
  `next_of_kin_email` varchar(260) DEFAULT NULL,
  `is_primary_contact` tinyint(1) NOT NULL,
  `is_emergency_contact` tinyint(1) NOT NULL,
  `authorized_pickup` tinyint(1) NOT NULL,
  `created_date` date DEFAULT NULL,
  `updated_date` date DEFAULT NULL,
  PRIMARY KEY (`next_of_kin_id`),
  KEY `user_id_fk` (`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `next_of_kin`
--

INSERT INTO `next_of_kin` (`next_of_kin_id`, `user_id_fk`, `next_of_kin_name`, `next_of_kin_relationship`, `next_of_kin_address`, `next_of_kin_phone`, `next_of_kin_email`, `is_primary_contact`, `is_emergency_contact`, `authorized_pickup`, `created_date`, `updated_date`) VALUES
(3, 18, 'Mary Lewis', 'Mother', 'Suva City', 7865432, 'jamescarter@yahoo.com', 1, 1, 1, '2026-02-10', '2026-02-10'),
(7, 18, 'Eseta Delai', 'Sister', 'Suva', 7658907, 'esetadels12@yahoo.com', 0, 1, 0, '2026-02-10', NULL),
(9, 14, 'James Carter', 'Father', '6 Miles\r\nTacirua', 7865432, 'piobaleicoqe2@gmail.com', 1, 1, 1, '2026-02-19', '2026-02-25'),
(10, 27, 'Peter SMith', 'Father', 'Lot 123, Suva', 9807867, 'petersmith@yahoo.com', 0, 1, 1, '2026-05-11', '2026-05-11'),
(11, 27, 'Mary Smith', 'Mother', 'Suva Street 123', 7689056, NULL, 1, 1, 1, '2026-05-11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `parent_student`
--

DROP TABLE IF EXISTS `parent_student`;
CREATE TABLE IF NOT EXISTS `parent_student` (
  `parent_student_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_user_id_fk` int UNSIGNED NOT NULL,
  `student_user_id_fk` int UNSIGNED NOT NULL,
  `relationship` varchar(50) NOT NULL DEFAULT 'Parent',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`parent_student_id`),
  UNIQUE KEY `uq_parent_student` (`parent_user_id_fk`,`student_user_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
CREATE TABLE IF NOT EXISTS `permission` (
  `perm_id` int NOT NULL AUTO_INCREMENT,
  `module_id_fk` int NOT NULL,
  `perm_name` varchar(100) DEFAULT NULL,
  `perm_desc` mediumtext NOT NULL,
  `perm_controller` varchar(60) DEFAULT NULL,
  `perm_code` varchar(45) DEFAULT NULL,
  `show_in_nav` tinyint DEFAULT NULL,
  `perm_status` varchar(60) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`perm_id`),
  KEY `fk_permission_modules` (`module_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`perm_id`, `module_id_fk`, `perm_name`, `perm_desc`, `perm_controller`, `perm_code`, `show_in_nav`, `perm_status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dashboard', '', 'dashboard', '_view_dashboard', 1, 'Active', NULL, NULL),
(2, 1, 'Notice', '', 'dashboard/notice', '_view_notice_board', 1, 'Active', NULL, NULL),
(3, 1, 'Announcement', '', 'dashboard/announcement', '_view_announcement', 1, 'Active', NULL, NULL),
(4, 2, 'Add Role', '', 'role/add', '_add_role', 1, 'Active', NULL, NULL),
(5, 2, 'Role Listing', '', 'role', '_role_listing', 1, 'Active', NULL, NULL),
(6, 2, 'Edit Role', '', 'role/edit/', '_edit_role', 0, 'Active', NULL, NULL),
(7, 2, 'Delete Role', '', 'role/remove', '_delete_role', 0, 'Active', NULL, NULL),
(8, 2, 'Add Permission', '', 'permission/add', '_add_permission', 1, 'Active', NULL, NULL),
(9, 2, 'Permission Listing', '', 'permission', '_permission_listing', 1, 'Active', NULL, NULL),
(10, 2, 'Edit Permission', '', 'permission/edit/', '_edit_permission', 0, 'Active', NULL, NULL),
(11, 2, 'Delete Permission', '', 'permission/delete', '_delete_permission', 0, 'Active', NULL, NULL),
(12, 2, 'Add Role Permission', '', 'rolepermission/add', '_add_role_permission', 0, 'Active', NULL, NULL),
(13, 2, 'Edit Role Permission', '', 'rolepermission/edit/', '_manage_role_permission', 0, 'Active', NULL, NULL),
(14, 3, 'Add User', '', 'user/add', '_add_user', 1, 'Active', NULL, NULL),
(15, 3, 'User Listing', '', 'user', '_user_listing', 1, 'Active', NULL, NULL),
(16, 3, 'Edit User', '', 'user/edit/', '_edit_user', 0, 'Active', NULL, NULL),
(17, 3, 'Delete User', '', 'user/remove', '_remove_user', 0, 'Active', NULL, NULL),
(18, 3, 'View User Detail', '', 'user/profile/', '_user_profile', 0, 'Active', NULL, NULL),
(19, 4, 'Add School', '', 'school/add/', '_add_school', 1, 'Active', NULL, NULL),
(20, 4, 'School Listing', '', 'school', '_school_listing', 1, 'Active', NULL, NULL),
(21, 4, 'Edit School', '', 'school/edit/', '_edit_school', 0, 'Active', NULL, NULL),
(22, 4, 'Remove School', '', 'school/remove', '_remove_school', 0, 'Active', NULL, NULL),
(23, 4, 'View School Detail', '', 'school/profile/', '_school_profile', 0, 'Active', NULL, NULL),
(24, 4, 'School Subscription', '', 'school/subscription/', '_school_subscription', 0, 'Active', NULL, NULL),
(25, 5, 'Add Admission', '', 'admission/add/', '_add_admission', 1, 'Active', NULL, NULL),
(26, 5, 'Admission Listing', '', 'admission', '_admission_listing', 1, 'Active', NULL, NULL),
(27, 5, 'Edit Admission', '', 'admission/edit/', '_edit_admission', 0, 'Active', NULL, NULL),
(28, 5, 'Delete Admission', '', 'admission/remove', '_remove_admission', 0, 'Active', NULL, NULL),
(29, 5, 'View Admission Detail', '', 'admission/detail/', '_admission_detail', 0, 'Active', NULL, NULL),
(30, 6, 'Add Enrolment', '', 'enrolment/add/', '_add_enrolment', 1, 'Active', NULL, NULL),
(32, 6, 'Enrolment Listing', '', 'enrolment', '_enrolment_listing', 1, 'Active', NULL, NULL),
(33, 6, 'Edit Enrolment', '', 'enrolment/edit/', '_edit_enrolment', 0, 'Active', NULL, NULL),
(34, 6, 'Delete Enrolment', '', 'enrolment/remove', '_remove_enrolment', 0, 'Active', NULL, NULL),
(35, 6, 'View Enrolment Detail', '', 'enrolment/detail/', '_enrolment_detail', 0, 'Active', NULL, NULL),
(36, 7, 'Add Classroom', '', 'classroom/add/', '_add_classroom', 1, 'Active', NULL, NULL),
(37, 7, 'Classroom Listing', '', 'classroom', '_classroom_listing', 1, 'Active', NULL, NULL),
(38, 7, 'Edit Classroom', '', 'classroom/edit/', '_edit_classroom', 0, 'Active', NULL, NULL),
(39, 7, 'Delete Classroom', '', 'classroom/remove', '_remove_classroom', 0, 'Active', NULL, NULL),
(40, 7, 'View Classroom Detail', '', 'classroom/detail/', '_classroom_detail', 0, 'Active', NULL, NULL),
(41, 7, 'My Classroom', '', 'classroom/my', '_my_classroom', 1, 'Active', NULL, NULL),
(42, 7, 'Add Lesson', '', 'classroom/addlesson', '_add_classroom_lesson', 0, 'Active', NULL, NULL),
(43, 7, 'Edit Lesson', '', 'classroom/editlesson', '_edit_classroom_lesson', 0, 'Active', NULL, NULL),
(44, 7, 'Delete Lesson', '', 'classroom/deletelesson', '_remove_classroom_lesson', 0, 'Active', NULL, NULL),
(45, 7, 'Add Assignment', '', 'classroom/addassignment', '_add_classroom_assignment', 0, 'Active', NULL, NULL),
(46, 7, 'Edit Assignment', '', 'classroom/editassignment', '_edit_classroom_assignment', 0, 'Active', NULL, NULL),
(47, 7, 'Delete Assignment', '', 'classroom/deleteassignment', '_remove_classroom_assignment', 0, 'Active', NULL, NULL),
(48, 7, 'Add Assessment', '', 'classroom/addassessment', '_add_classroom_assessment', 0, 'Active', NULL, NULL),
(49, 7, 'Edit Assessment', '', 'classroom/editassessment', '_edit_classroom_assessment', 0, 'Active', NULL, NULL),
(50, 7, 'Attempt Assessment', '', 'classroom/deletessessment', '_attempt_classroom_assessment', 0, 'Active', NULL, NULL),
(51, 7, 'Delete Assessment', '', 'classroom/deletessessment', '_remove_classroom_assessment', 0, 'Active', NULL, NULL),
(52, 7, 'Add Assignment Mark', '', 'classroom/addassignmentmark/', '_add_classroom_assignment_mark', 0, 'Active', NULL, NULL),
(53, 9, 'Add Exam', '', 'exam/add/', '_add_exam', 1, 'Active', NULL, NULL),
(54, 9, 'Exam Listing', '', 'exam', '_exam_listing', 1, 'Active', NULL, NULL),
(55, 9, 'Edit Exam', '', 'exam/edit/', '_edit_exam', 0, 'Active', NULL, NULL),
(56, 9, 'Delete Exam', '', 'exam/remove', '_remove_exam', 0, 'Active', NULL, NULL),
(57, 9, 'View Exam Detail', '', 'exam/detail/', '_exam_detail', 0, 'Active', NULL, NULL),
(58, 9, 'Add Exam Mark', '', 'exam/addmark/', '_add_exam_mark', 0, 'Active', NULL, NULL),
(59, 9, 'View Exam Mark', '', 'exam/viewmark/', '_view_exam_mark', 0, 'Active', NULL, NULL),
(60, 9, 'My Exam Result', '', 'exam/result/', '_view_exam_result', 0, 'Active', NULL, NULL),
(61, 10, 'Add Conduct', '', 'conduct/add/', '_add_conduct', 1, 'Active', NULL, NULL),
(62, 10, 'Conduct Listing', '', 'conduct', '_conduct_listing', 1, 'Active', NULL, NULL),
(63, 10, 'Edit Conduct', '', 'conduct/edit/', '_edit_conduct', 0, 'Active', NULL, NULL),
(64, 10, 'Delete Conduct', '', 'conduct/remove', '_remove_conduct', 0, 'Active', NULL, NULL),
(65, 10, 'View Conduct Detail', '', 'conduct/detail/', '_conduct_detail', 0, 'Active', NULL, NULL),
(66, 10, 'Generate Conduct Report', '', 'conduct/report/', '_conduct_report', 0, 'Active', NULL, NULL),
(67, 10, 'My Conduct', '', 'conduct/my/', '_my_conduct', 0, 'Active', NULL, NULL),
(68, 11, 'Add Timetable', '', 'timetable/add/', '_add_timetable', 1, 'Active', NULL, NULL),
(69, 11, 'Timetable Listing', '', 'timetable', '_timetable_listing', 1, 'Active', NULL, NULL),
(70, 11, 'Edit Timetable', '', 'timetable/edit/', '_edit_timetable', 0, 'Active', NULL, NULL),
(71, 11, 'Delete Timetable', '', 'timetable/remove', '_remove_timetable', 0, 'Active', NULL, NULL),
(72, 11, 'View Timetable Detail', '', 'timetable/detail/', '_timetable_detail', 0, 'Active', NULL, NULL),
(73, 11, 'Generate Timetable Report', '', 'timetable/report/', '_timetable_report', 0, 'Active', NULL, NULL),
(74, 12, 'Add Event', '', 'event/add/', '_add_event', 1, 'Active', NULL, NULL),
(75, 12, 'Event Listing', '', 'event', '_event_listing', 1, 'Active', NULL, NULL),
(76, 12, 'Edit Event', '', 'event/edit/', '_edit_event', 0, 'Active', NULL, NULL),
(77, 12, 'Delete Event', '', 'event/remove', '_remove_event', 0, 'Active', NULL, NULL),
(78, 12, 'View Event Detail', '', 'event/detail/', '_event_detail', 0, 'Active', NULL, NULL),
(79, 12, 'Generate Event Report', '', 'event/report/', '_event_report', 0, 'Active', NULL, NULL),
(80, 12, 'Event Calendar', '', 'event/calendar/', '_event_calendar', 0, 'Active', NULL, NULL),
(81, 13, 'School Wall', '', 'wall/', '_school_wall', 1, 'Active', NULL, NULL),
(82, 13, 'Add Post', '', 'wall/post', '_add_wall_post', 0, 'Active', NULL, NULL),
(83, 13, 'Edit Post', '', 'wall/editpost', '_edit_wall_post', 0, 'Active', NULL, NULL),
(84, 13, 'Delete Post', '', 'wall/removepost', '_remove_wall_post', 0, 'Active', NULL, NULL),
(85, 13, 'Post Comment', '', 'wall/postcomment/', '_add_wall_post_comment', 0, 'Active', NULL, NULL),
(86, 13, 'Edit Post Comment', '', 'wall/editpostcomment/', '_edit_wall_post_comment', 0, 'Active', NULL, NULL),
(87, 13, 'Reply Post Comment', '', 'wall/replypostcomment/', '_reply_wall_post_comment', 0, 'Active', NULL, NULL),
(88, 13, 'Remove Post Comment', '', 'wall/removepostcomment/', '_remove_wall_post_comment', 0, 'Active', NULL, NULL),
(89, 13, 'Instant Message', '', 'chat', '_instant_messaging', 0, 'Active', NULL, NULL),
(90, 2, 'View Role Permission', '', 'role/permission', '_view_role_permission', 0, 'Active', NULL, NULL),
(94, 2, 'Update User Role', 'The role update process involves a state change: the current active role is transitioned to a \'Non-Active\' status, and the newly associated role is established as the \'Active\' role for the user.', 'user/updateRole', '_update_user_role', 0, 'Active', '2026-02-11', '2026-02-11'),
(95, 8, 'Add Student Daily', 'Add student daily attendance', 'attendance/add', '_add_student_daily_attendance', 1, 'Active', '2026-05-28', '2026-05-28'),
(96, 8, 'View Student Daily', 'View student daily attendance', 'attendance', '_view_student_daily_attendance', 1, 'Active', '2026-05-28', '2026-05-28'),
(97, 8, 'Add Student Subject', 'Add student subject or class period attendance', 'attendance/subject/add', '_add_student_subject', 1, 'Active', '2026-05-28', '2026-05-28'),
(98, 8, 'View Student Subject', 'View student subject or class reriod attendance', 'attendance/subject', '_view_student_subject', 1, 'Active', '2026-05-28', '2026-05-28');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
CREATE TABLE IF NOT EXISTS `plans` (
  `plan_id` int NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(45) DEFAULT NULL,
  `plan_desc` longtext,
  `plan_monthly_cost` double DEFAULT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`plan_id`, `plan_name`, `plan_desc`, `plan_monthly_cost`) VALUES
(1, 'Starter', 'Start using Navuli for FREE with access to essential features. This plan is ideal for individuals or small school who are just getting started. You\'ll get limited access to core tools, allowing you to explore Navuli’s basic functionality at no cost.', 0),
(2, 'Standard', 'Perfect for growing school. Manage up to 500 users and unlock Navuli\'s essential features. Designed for organizations that are expanding, the Standard plan offers enhanced functionality, better collaboration tools, and access to core integrations to help you manage users and operations more efficiently.', 150),
(3, 'Enterprise', 'Unlimited users. Full power. Everything Navuli has to offer. Built for large organizations and mission-critical operations, the Enterprise plan gives you complete access to all Navuli features, premium support, and unlimited scalability. It’s the best choice for teams that need full control, customization, and performance.', 250);

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

DROP TABLE IF EXISTS `province`;
CREATE TABLE IF NOT EXISTS `province` (
  `province_id` int NOT NULL AUTO_INCREMENT,
  `division_id_fk` int NOT NULL,
  `province_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`province_id`),
  KEY `fk_province_division` (`division_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`province_id`, `division_id_fk`, `province_name`) VALUES
(1, 3, 'Ba'),
(2, 4, 'Bua'),
(3, 4, 'Cakaudrove'),
(4, 2, 'Kadavu'),
(5, 2, 'Lau'),
(6, 2, 'Lomaiviti'),
(7, 4, 'Macuata'),
(8, 3, 'Nadroga-Navosa'),
(9, 1, 'Naitasiri'),
(10, 1, 'Namosi'),
(11, 3, 'Ra'),
(12, 1, 'Rewa'),
(13, 1, 'Serua'),
(14, 1, 'Tailevu'),
(15, 2, 'Rotuma'),
(16, 5, 'Foreign Citizen');

-- --------------------------------------------------------

--
-- Table structure for table `reference_category`
--

DROP TABLE IF EXISTS `reference_category`;
CREATE TABLE IF NOT EXISTS `reference_category` (
  `ref_cat_id` int NOT NULL AUTO_INCREMENT,
  `ref_cat_name` varchar(150) NOT NULL,
  PRIMARY KEY (`ref_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reference_category`
--

INSERT INTO `reference_category` (`ref_cat_id`, `ref_cat_name`) VALUES
(1, 'Certificate of Enrolment'),
(2, 'Character Reference'),
(3, 'Recommendation Letter'),
(4, 'Transcript Request'),
(5, 'Conduct Certificate'),
(6, 'Clearance Certificate'),
(7, 'Certificate of Employment'),
(8, 'Performance Recommendation'),
(9, 'Parent Guardian Certificate'),
(10, 'Parent Involvement Certificate'),
(11, 'Financial Clearance');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_cat_id_fk` int NOT NULL,
  `role_name` varchar(60) DEFAULT NULL,
  `role_desc` longtext,
  `role_rank` int NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  KEY `role_cat_id_fk` (`role_cat_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_cat_id_fk`, `role_name`, `role_desc`, `role_rank`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super Admin', 'The ultimate authority in the system. The Super Admin possesses full, unrestricted access to configure the platform, manage all user accounts, and oversee every aspect of the system\'s data and functionality.', 1, '2026-01-22', '2026-01-22'),
(2, 2, 'School Admin', 'The School Administrator serves as the primary manager of the institution\'s digital ecosystem, overseeing platform configuration, user access, and subscription services. This role is responsible for maintaining the operational integrity of the school\'s account, ensuring seamless access to tools and resources for faculty, staff, and students.', 3, '2026-01-22', '2026-01-22'),
(3, 3, 'Principal', 'The Principal serves as the chief operational leader of the school, responsible for overseeing day-to-day academic and administrative functions while shaping the institution’s educational environment. This role goes beyond administration to directly impact teaching effectiveness, student development, and operational efficiency.', 2, '2026-01-22', '2026-01-22'),
(4, 3, 'HOD', 'A Head of Department is a specialist leader and middle manager focused on a specific academic area (e.g., Math, Science, Industrial Arts). They are responsible for the quality, consistency, and innovation within their subject domain.', 4, '2026-01-28', '2026-01-28'),
(5, 3, 'Assistant Teacher', 'An Assistant Teacher is a vital support professional within the classroom, working under the guidance of the HOD or Principal to facilitate student learning and well-being. This role focuses on implementing, assisting, and reinforcing the educational environment to ensure all students receive individualized attention and support.', 4, '2026-01-28', '2026-01-29'),
(6, 6, 'Parent', 'The Parent role is a dedicated portal that provides real-time access to their child\'s academic and school life, transforming them from passive observers into active, informed participants in the educational process.', 5, '2026-02-03', '2026-02-03'),
(7, 4, 'Student', 'The Student role is a personalized digital dashboard that centralizes a student\'s academic life, fostering independence, organization, and active engagement with their own learning process.', 6, '2026-02-03', '2026-02-03'),
(8, 5, 'Support Staff', 'Support Staff are frontline operational specialists responsible for facilitating seamless technical assistance, user issue resolution, and day-to-day system support to maintain service excellence and client satisfaction.', 4, '2026-01-23', '2026-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `role_category`
--

DROP TABLE IF EXISTS `role_category`;
CREATE TABLE IF NOT EXISTS `role_category` (
  `role_cat_id` int NOT NULL AUTO_INCREMENT,
  `role_cat_name` varchar(260) NOT NULL,
  PRIMARY KEY (`role_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role_category`
--

INSERT INTO `role_category` (`role_cat_id`, `role_cat_name`) VALUES
(1, 'System Admin'),
(2, 'School Admin'),
(3, 'Teacher'),
(4, 'Student'),
(5, 'Support Staff'),
(6, 'Parent or Guardian\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE IF NOT EXISTS `role_permission` (
  `role_perm_id` int NOT NULL AUTO_INCREMENT,
  `perm_id_fk` int NOT NULL,
  `role_id_fk` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_perm_id`),
  KEY `fk_role_perm_permission` (`perm_id_fk`),
  KEY `fk_role_perm_role` (`role_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=482 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`role_perm_id`, `perm_id_fk`, `role_id_fk`, `created_at`, `updated_at`) VALUES
(96, 1, 2, '2026-01-29 10:23:11', '2026-01-29 10:23:11'),
(97, 2, 2, '2026-01-29 10:23:11', '2026-01-29 10:23:11'),
(98, 3, 2, '2026-01-29 10:23:11', '2026-01-29 10:23:11'),
(99, 1, 4, '2026-02-02 08:50:19', '2026-02-02 08:50:19'),
(100, 2, 4, '2026-02-02 08:50:19', '2026-02-02 08:50:19'),
(101, 3, 4, '2026-02-02 08:50:19', '2026-02-02 08:50:19'),
(256, 1, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(257, 2, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(258, 3, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(259, 4, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(260, 5, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(261, 6, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(262, 7, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(263, 8, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(264, 9, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(265, 10, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(266, 11, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(267, 12, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(268, 13, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(269, 14, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(270, 15, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(271, 16, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(272, 17, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(273, 18, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(274, 19, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(275, 20, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(276, 21, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(277, 22, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(278, 23, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(279, 24, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(280, 25, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(281, 26, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(282, 27, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(283, 28, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(284, 29, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(285, 30, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(286, 32, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(287, 33, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(288, 34, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(289, 35, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(290, 36, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(291, 37, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(292, 38, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(293, 39, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(294, 40, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(295, 41, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(296, 42, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(297, 43, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(298, 44, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(299, 45, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(300, 46, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(301, 47, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(302, 48, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(303, 49, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(304, 50, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(305, 51, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(306, 52, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(307, 53, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(308, 54, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(309, 55, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(310, 56, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(311, 57, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(312, 58, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(313, 59, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(314, 60, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(315, 61, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(316, 62, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(317, 63, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(318, 64, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(319, 65, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(320, 66, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(321, 67, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(322, 68, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(323, 69, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(324, 70, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(325, 71, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(326, 72, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(327, 73, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(328, 74, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(329, 75, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(330, 76, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(331, 77, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(332, 78, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(333, 79, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(334, 80, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(335, 81, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(336, 82, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(337, 83, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(338, 84, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(339, 85, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(340, 86, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(341, 87, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(342, 88, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(343, 89, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(344, 90, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(345, 94, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(346, 95, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(347, 96, 1, '2026-05-28 07:45:28', '2026-05-28 07:45:28'),
(414, 1, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(415, 2, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(416, 3, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(417, 14, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(418, 15, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(419, 16, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(420, 18, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(421, 20, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(422, 23, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(423, 24, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(424, 25, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(425, 26, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(426, 27, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(427, 29, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(428, 30, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(429, 32, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(430, 33, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(431, 35, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(432, 37, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(433, 40, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(434, 41, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(435, 42, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(436, 43, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(437, 44, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(438, 45, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(439, 46, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(440, 47, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(441, 48, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(442, 49, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(443, 51, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(444, 52, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(445, 53, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(446, 54, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(447, 55, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(448, 56, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(449, 57, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(450, 58, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(451, 59, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(452, 60, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(453, 61, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(454, 62, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(455, 63, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(456, 65, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(457, 66, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(458, 67, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(459, 69, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(460, 72, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(461, 73, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(462, 74, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(463, 75, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(464, 76, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(465, 77, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(466, 78, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(467, 79, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(468, 80, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(469, 81, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(470, 82, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(471, 83, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(472, 84, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(473, 85, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(474, 86, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(475, 87, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(476, 88, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(477, 89, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(478, 95, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(479, 96, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(480, 97, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22'),
(481, 98, 5, '2026-05-28 10:58:22', '2026-05-28 10:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
CREATE TABLE IF NOT EXISTS `school` (
  `sch_id` int NOT NULL AUTO_INCREMENT,
  `sch_cat_id_fk` int DEFAULT NULL,
  `district_id_fk` int DEFAULT NULL,
  `sch_name` varchar(260) DEFAULT NULL,
  `sch_address` varchar(260) DEFAULT NULL,
  `sch_phone` int DEFAULT NULL,
  `sch_email` varchar(260) DEFAULT NULL,
  `sch_password` varchar(260) DEFAULT NULL,
  `sch_x_coord` varchar(100) DEFAULT NULL,
  `sch_y_coord` varchar(100) DEFAULT NULL,
  `sch_motto` varchar(260) DEFAULT NULL,
  `sch_logo` varchar(250) NOT NULL,
  `sch_primary_color` varchar(60) NOT NULL,
  `sch_secondary_color` varchar(60) NOT NULL,
  `sch_created_at` datetime DEFAULT NULL,
  `sch_status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sch_id`),
  KEY `fk_school_sch_category` (`sch_cat_id_fk`),
  KEY `fk_school_ditrict` (`district_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`sch_id`, `sch_cat_id_fk`, `district_id_fk`, `sch_name`, `sch_address`, `sch_phone`, `sch_email`, `sch_password`, `sch_x_coord`, `sch_y_coord`, `sch_motto`, `sch_logo`, `sch_primary_color`, `sch_secondary_color`, `sch_created_at`, `sch_status`) VALUES
(12, 4, 89, 'Suva Secondary School', 'Lot 345, Straight Street, Labasa', 9807645, 'piobaleicoqe@yahoo.com', '$2y$10$tJxHFuOF4CLv.JmHZHejfeKiNXq9M1tNsReqwMFpIkwLdKXDQkMSq', '178.440609', '-18.134809', 'Enter To Learn', 'logo_12_893140.png', '', '', '2025-11-05 12:01:19', 'Active'),
(21, 4, 159, 'Lami High School', '6 Miles', 1234567, 'sch@yahoo.com', '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', NULL, NULL, 'Enter to Learn', '', '#3498db', '#ecf0f1', '2026-01-14 16:12:02', 'Step 1 Configured'),
(26, 4, 46, 'Nasinu Secondary School 6', '6 Miles, Tacirua', 9896700, 'piobaleicoqe92@gmail.com', NULL, NULL, NULL, 'Enter to learn', '', '', '', NULL, 'Step 1 Configured'),
(29, 4, 193, 'Rotuma High School', 'Rotuma island', 9987678, 'pio@baleicoqe.com', NULL, '177.081499', '-12.519626', 'Enter to learn', 'logo_29_883917.jpg', '#0080ff', '#ff0000', NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `school_config`
--

DROP TABLE IF EXISTS `school_config`;
CREATE TABLE IF NOT EXISTS `school_config` (
  `sch_config_id` int NOT NULL AUTO_INCREMENT,
  `num_of_term` int NOT NULL,
  `sch_start_month` int NOT NULL,
  `start_day` int NOT NULL,
  `num_of_weeks_in_wone_term` int NOT NULL,
  PRIMARY KEY (`sch_config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `school_config`
--

INSERT INTO `school_config` (`sch_config_id`, `num_of_term`, `sch_start_month`, `start_day`, `num_of_weeks_in_wone_term`) VALUES
(1, 3, 26, 1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `sch_category`
--

DROP TABLE IF EXISTS `sch_category`;
CREATE TABLE IF NOT EXISTS `sch_category` (
  `sch_cat_id` int NOT NULL AUTO_INCREMENT,
  `sch_cat_initial` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sch_cat_name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`sch_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sch_category`
--

INSERT INTO `sch_category` (`sch_cat_id`, `sch_cat_initial`, `sch_cat_name`) VALUES
(1, 'Pre School', 'Pre School'),
(2, 'Kindergarten', 'Kindergarten'),
(3, 'Primary', 'Primary School'),
(4, 'Seconday', 'Secondary School'),
(5, 'TVET', 'Technical and Vocational Education and Training');

-- --------------------------------------------------------

--
-- Table structure for table `sch_department`
--

DROP TABLE IF EXISTS `sch_department`;
CREATE TABLE IF NOT EXISTS `sch_department` (
  `sch_dept_id` int NOT NULL AUTO_INCREMENT,
  `sch_id_fk` int NOT NULL,
  `dept_id_fk` int NOT NULL,
  `dept_head` int DEFAULT NULL,
  `dept_status` varchar(60) NOT NULL,
  PRIMARY KEY (`sch_dept_id`),
  KEY `fk_sch_department_school` (`sch_id_fk`),
  KEY `fk_sch_department_department` (`dept_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sch_department`
--

INSERT INTO `sch_department` (`sch_dept_id`, `sch_id_fk`, `dept_id_fk`, `dept_head`, `dept_status`) VALUES
(1, 12, 1, 0, 'Established'),
(2, 12, 2, 0, 'Established'),
(3, 12, 3, 0, 'Established'),
(4, 12, 4, 0, 'Established'),
(5, 12, 5, 0, 'Established'),
(6, 12, 6, 0, 'Established'),
(7, 12, 7, 0, 'Established'),
(8, 12, 8, 0, 'Established'),
(9, 12, 9, 0, 'Established'),
(10, 12, 10, 0, 'Established'),
(11, 12, 11, 0, 'Established'),
(34, 29, 1, NULL, 'Established'),
(35, 29, 2, NULL, 'Established'),
(36, 29, 3, NULL, 'Established'),
(37, 29, 4, NULL, 'Established'),
(38, 29, 5, NULL, 'Established'),
(39, 29, 6, NULL, 'Established'),
(40, 29, 7, NULL, 'Established'),
(41, 29, 8, NULL, 'Established'),
(42, 29, 9, NULL, 'Established'),
(43, 29, 10, NULL, 'Established'),
(47, 29, 11, NULL, 'Established');

-- --------------------------------------------------------

--
-- Table structure for table `sch_level`
--

DROP TABLE IF EXISTS `sch_level`;
CREATE TABLE IF NOT EXISTS `sch_level` (
  `sch_level_id` int NOT NULL AUTO_INCREMENT,
  `sch_id_fk` int NOT NULL,
  `level_id_fk` int NOT NULL,
  PRIMARY KEY (`sch_level_id`),
  KEY `fk_sch_level_school` (`sch_id_fk`),
  KEY `fk_sch_level_level` (`level_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sch_level`
--

INSERT INTO `sch_level` (`sch_level_id`, `sch_id_fk`, `level_id_fk`) VALUES
(6, 12, 11),
(7, 12, 12),
(8, 12, 13),
(9, 12, 14),
(10, 12, 15),
(27, 29, 12),
(28, 29, 13),
(29, 29, 14),
(30, 29, 15),
(31, 29, 11);

-- --------------------------------------------------------

--
-- Table structure for table `sch_subject`
--

DROP TABLE IF EXISTS `sch_subject`;
CREATE TABLE IF NOT EXISTS `sch_subject` (
  `sch_sub_id` int NOT NULL AUTO_INCREMENT,
  `sch_id_fk` int NOT NULL,
  `subject_id_fk` int NOT NULL,
  `sch_dept_id_fk` int NOT NULL,
  `sch_sub_status` varchar(60) NOT NULL,
  PRIMARY KEY (`sch_sub_id`),
  KEY `fk_sch_subject_school` (`sch_id_fk`),
  KEY `fk_sch_subject_subject` (`subject_id_fk`),
  KEY `sch_dept_id_fk` (`sch_dept_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sch_subject`
--

INSERT INTO `sch_subject` (`sch_sub_id`, `sch_id_fk`, `subject_id_fk`, `sch_dept_id_fk`, `sch_sub_status`) VALUES
(21, 12, 131, 4, 'Active'),
(22, 12, 148, 4, 'Active'),
(23, 12, 165, 4, 'Active'),
(24, 12, 186, 4, 'Active'),
(25, 12, 207, 4, 'Active'),
(26, 12, 130, 1, 'Active'),
(27, 12, 132, 2, 'Active'),
(28, 12, 133, 1, 'Active'),
(29, 12, 134, 1, 'Active'),
(30, 12, 137, 10, 'Active'),
(31, 12, 138, 8, 'Active'),
(32, 12, 139, 9, 'Active'),
(33, 12, 141, 5, 'Active'),
(34, 12, 142, 10, 'Active'),
(35, 12, 143, 7, 'Active'),
(36, 12, 144, 6, 'Active'),
(37, 12, 146, 3, 'Active'),
(38, 12, 147, 1, 'Active'),
(39, 12, 149, 2, 'Active'),
(40, 12, 150, 1, 'Active'),
(41, 12, 151, 1, 'Active'),
(42, 12, 154, 10, 'Active'),
(43, 12, 155, 8, 'Active'),
(44, 12, 156, 9, 'Active'),
(46, 12, 159, 10, 'Active'),
(47, 12, 160, 7, 'Active'),
(48, 12, 161, 6, 'Active'),
(49, 12, 163, 3, 'Active'),
(50, 12, 164, 1, 'Active'),
(51, 12, 166, 2, 'Active'),
(52, 12, 167, 2, 'Active'),
(53, 12, 168, 4, 'Active'),
(54, 12, 169, 3, 'Active'),
(55, 12, 170, 3, 'Active'),
(56, 12, 171, 1, 'Active'),
(57, 12, 172, 1, 'Active'),
(58, 12, 175, 10, 'Active'),
(59, 12, 176, 8, 'Active'),
(60, 12, 177, 9, 'Active'),
(61, 12, 178, 9, 'Active'),
(62, 12, 179, 5, 'Active'),
(63, 12, 180, 5, 'Active'),
(64, 12, 181, 10, 'Active'),
(65, 12, 182, 7, 'Active'),
(66, 12, 183, 6, 'Active'),
(67, 12, 184, 6, 'Active'),
(68, 12, 185, 1, 'Active'),
(69, 12, 187, 2, 'Active'),
(70, 12, 188, 2, 'Active'),
(71, 12, 189, 4, 'Active'),
(72, 12, 190, 3, 'Active'),
(73, 12, 191, 3, 'Active'),
(74, 12, 192, 1, 'Active'),
(75, 12, 193, 1, 'Active'),
(76, 12, 196, 10, 'Active'),
(77, 12, 197, 8, 'Active'),
(78, 12, 198, 9, 'Active'),
(79, 12, 199, 9, 'Active'),
(80, 12, 200, 5, 'Active'),
(81, 12, 201, 5, 'Active'),
(82, 12, 202, 10, 'Active'),
(83, 12, 203, 7, 'Active'),
(84, 12, 204, 6, 'Active'),
(85, 12, 205, 6, 'Active'),
(86, 12, 206, 1, 'Active'),
(87, 12, 208, 2, 'Active'),
(88, 12, 209, 2, 'Active'),
(89, 12, 210, 4, 'Active'),
(90, 12, 211, 3, 'Active'),
(91, 12, 212, 3, 'Active'),
(92, 12, 213, 1, 'Active'),
(93, 12, 214, 1, 'Active'),
(94, 12, 217, 10, 'Active'),
(95, 12, 218, 8, 'Active'),
(96, 12, 219, 9, 'Active'),
(97, 12, 220, 9, 'Active'),
(98, 12, 221, 5, 'Active'),
(99, 12, 222, 5, 'Active'),
(100, 12, 223, 10, 'Active'),
(101, 12, 224, 7, 'Active'),
(102, 12, 225, 6, 'Active'),
(103, 12, 226, 6, 'Active'),
(109, 12, 135, 1, 'Active'),
(110, 12, 152, 1, 'Active'),
(111, 12, 173, 1, 'Active'),
(112, 12, 194, 1, 'Active'),
(113, 12, 215, 1, 'Active'),
(114, 29, 132, 35, 'Active'),
(115, 29, 130, 34, 'Active'),
(116, 29, 131, 37, 'Active'),
(117, 29, 142, 43, 'Active'),
(118, 29, 146, 36, 'Active'),
(119, 29, 138, 41, 'Active'),
(120, 29, 144, 39, 'Active'),
(121, 29, 139, 42, 'Active'),
(122, 29, 141, 38, 'Active'),
(123, 29, 137, 43, 'Active'),
(124, 29, 145, 43, 'Active'),
(125, 29, 134, 34, 'Active'),
(126, 29, 135, 34, 'Active'),
(127, 29, 136, 34, 'Active'),
(128, 29, 133, 34, 'Active'),
(129, 29, 149, 35, 'Active'),
(130, 29, 158, 38, 'Active'),
(131, 29, 147, 34, 'Active'),
(132, 29, 148, 43, 'Active'),
(133, 29, 163, 36, 'Active'),
(134, 29, 156, 42, 'Active'),
(135, 29, 161, 38, 'Active'),
(136, 29, 154, 43, 'Active'),
(137, 29, 160, 40, 'Active'),
(138, 29, 162, 43, 'Active'),
(139, 29, 159, 43, 'Active'),
(140, 29, 152, 34, 'Active'),
(141, 29, 150, 34, 'Active'),
(142, 29, 164, 34, 'Active'),
(143, 29, 165, 43, 'Active'),
(144, 29, 180, 38, 'Active'),
(145, 29, 176, 41, 'Active'),
(146, 29, 167, 35, 'Active'),
(147, 29, 178, 42, 'Active'),
(148, 29, 166, 35, 'Active'),
(149, 29, 170, 36, 'Active'),
(150, 29, 169, 36, 'Active'),
(151, 29, 183, 39, 'Active'),
(152, 29, 168, 35, 'Active'),
(153, 29, 184, 39, 'Active'),
(154, 29, 182, 40, 'Active'),
(155, 29, 173, 34, 'Active'),
(156, 29, 177, 43, 'Active'),
(157, 29, 196, 43, 'Active'),
(158, 29, 185, 34, 'Active'),
(159, 29, 186, 43, 'Active'),
(160, 29, 202, 43, 'Active'),
(161, 29, 194, 34, 'Active'),
(162, 29, 201, 38, 'Active'),
(163, 29, 188, 35, 'Active'),
(164, 29, 191, 36, 'Active'),
(165, 29, 187, 35, 'Active'),
(166, 29, 200, 38, 'Active'),
(167, 29, 190, 36, 'Active'),
(168, 29, 205, 39, 'Active'),
(169, 29, 189, 35, 'Active'),
(170, 29, 198, 42, 'Active'),
(171, 29, 217, 43, 'Active'),
(172, 29, 206, 34, 'Active'),
(173, 29, 207, 37, 'Active'),
(174, 29, 223, 43, 'Active'),
(175, 29, 215, 34, 'Active'),
(176, 29, 155, 41, 'Active'),
(177, 29, 157, 34, 'Active'),
(178, 12, 174, 1, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `staff_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `sch_id_fk` int NOT NULL,
  `date_joined` date DEFAULT NULL,
  `date_left` date DEFAULT NULL,
  `staff_status` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`staff_id`),
  KEY `user_id_fk` (`user_id_fk`),
  KEY `sch_id_fk` (`sch_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `user_id_fk`, `sch_id_fk`, `date_joined`, `date_left`, `staff_status`) VALUES
(1, 11, 21, '2026-01-15', NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `stream`
--

DROP TABLE IF EXISTS `stream`;
CREATE TABLE IF NOT EXISTS `stream` (
  `stream_id` int NOT NULL AUTO_INCREMENT,
  `sch_level_id_fk` int NOT NULL,
  `stream_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`stream_id`),
  KEY `fk_stream_level` (`sch_level_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stream`
--

INSERT INTO `stream` (`stream_id`, `sch_level_id_fk`, `stream_name`) VALUES
(91, 6, 'Year 9A'),
(92, 6, 'Year 9B'),
(93, 7, 'Year 10A'),
(94, 7, 'Year 10B'),
(95, 8, 'Year 11A'),
(96, 8, 'Year 11B'),
(97, 9, 'Year 12A'),
(98, 9, 'Year 12B'),
(99, 10, 'Year 13A'),
(100, 10, 'Year 13B'),
(101, 10, 'Year 13C'),
(102, 31, '901'),
(103, 31, '902'),
(106, 27, '1001'),
(107, 27, '1002'),
(110, 27, '1003'),
(111, 28, '1101'),
(112, 29, '1201'),
(113, 30, '1301');

-- --------------------------------------------------------

--
-- Table structure for table `stream_core_subject`
--

DROP TABLE IF EXISTS `stream_core_subject`;
CREATE TABLE IF NOT EXISTS `stream_core_subject` (
  `stream_core_sub_id` int NOT NULL AUTO_INCREMENT,
  `sch_sub_id_fk` int NOT NULL,
  `stream_id_fk` int NOT NULL,
  PRIMARY KEY (`stream_core_sub_id`),
  KEY `fk_stream_core_subject_stream` (`stream_id_fk`),
  KEY `sch_sub_id_fk` (`sch_sub_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stream_core_subject`
--

INSERT INTO `stream_core_subject` (`stream_core_sub_id`, `sch_sub_id_fk`, `stream_id_fk`) VALUES
(107, 26, 91),
(108, 21, 91),
(109, 27, 91),
(110, 33, 91),
(112, 37, 91),
(113, 30, 91),
(114, 34, 91),
(115, 26, 92),
(116, 21, 92),
(117, 27, 92),
(118, 33, 92),
(119, 36, 92),
(120, 37, 92),
(121, 38, 93),
(122, 22, 93),
(123, 39, 93),
(124, 49, 93),
(125, 38, 94),
(126, 22, 94),
(127, 39, 94),
(128, 49, 94),
(129, 50, 95),
(130, 23, 95),
(131, 64, 95),
(132, 51, 95),
(133, 52, 95),
(134, 53, 95),
(135, 50, 96),
(136, 23, 96),
(137, 54, 96),
(138, 55, 96),
(139, 56, 96),
(140, 68, 97),
(141, 24, 97),
(142, 69, 97),
(143, 70, 97),
(144, 71, 97),
(145, 78, 97),
(146, 79, 97),
(147, 68, 98),
(148, 24, 98),
(149, 69, 98),
(150, 70, 98),
(151, 71, 98),
(152, 72, 98),
(153, 73, 98),
(154, 86, 99),
(155, 25, 99),
(156, 87, 99),
(157, 88, 99),
(158, 89, 99),
(159, 90, 99),
(160, 86, 100),
(161, 25, 100),
(162, 87, 100),
(163, 88, 100),
(164, 89, 100),
(165, 90, 100),
(166, 86, 101),
(167, 25, 101),
(168, 87, 101),
(169, 88, 101),
(170, 89, 101),
(171, 90, 101),
(173, 115, 102),
(174, 116, 102),
(175, 117, 102),
(176, 118, 102),
(177, 114, 102),
(178, 114, 103),
(179, 115, 103),
(180, 116, 103),
(181, 117, 103),
(182, 118, 103),
(183, 129, 110),
(184, 130, 110),
(185, 131, 110),
(186, 132, 110),
(187, 133, 110),
(188, 142, 111),
(189, 143, 111),
(190, 157, 112),
(191, 158, 112),
(192, 159, 112),
(193, 160, 112),
(194, 161, 112),
(195, 171, 113),
(196, 172, 113),
(197, 173, 113),
(198, 174, 113),
(199, 175, 113),
(200, 129, 106),
(201, 130, 106),
(202, 131, 106),
(203, 132, 106),
(204, 140, 106),
(205, 133, 106),
(206, 129, 107),
(207, 130, 107),
(208, 131, 107),
(209, 132, 107),
(210, 139, 107);

-- --------------------------------------------------------

--
-- Table structure for table `stream_optional_subject`
--

DROP TABLE IF EXISTS `stream_optional_subject`;
CREATE TABLE IF NOT EXISTS `stream_optional_subject` (
  `stream_opt_sub_id` int NOT NULL AUTO_INCREMENT,
  `sch_sub_id_fk` int NOT NULL,
  `stream_id_fk` int NOT NULL,
  `option_num` int DEFAULT NULL,
  PRIMARY KEY (`stream_opt_sub_id`),
  KEY `fk_stream_optional_subject_stream` (`stream_id_fk`),
  KEY `sch_sub_id_fk` (`sch_sub_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stream_optional_subject`
--

INSERT INTO `stream_optional_subject` (`stream_opt_sub_id`, `sch_sub_id_fk`, `stream_id_fk`, `option_num`) VALUES
(13, 28, 91, 1),
(14, 29, 91, 1),
(15, 109, 91, 1),
(16, 31, 91, 2),
(17, 36, 91, 2),
(18, 32, 91, 3),
(19, 35, 91, 3),
(20, 28, 92, 4),
(21, 29, 92, 4),
(22, 109, 92, 4),
(23, 40, 93, 5),
(24, 41, 93, 5),
(25, 110, 93, 5),
(26, 42, 93, 6),
(27, 46, 93, 6),
(28, 43, 93, 7),
(29, 44, 93, 7),
(30, 47, 93, 8),
(31, 48, 93, 8),
(32, 40, 94, 9),
(33, 41, 94, 9),
(34, 110, 94, 9),
(35, 30, 92, 10),
(36, 34, 92, 10),
(37, 42, 94, 11),
(38, 46, 94, 11),
(39, 47, 94, 12),
(40, 48, 94, 12),
(41, 43, 94, 13),
(42, 44, 94, 13),
(43, 119, 103, 1),
(44, 120, 103, 1),
(45, 121, 103, 2),
(46, 122, 103, 2),
(47, 123, 102, 1),
(48, 124, 102, 1),
(49, 125, 102, 2),
(50, 126, 102, 2),
(51, 127, 102, 2),
(52, 128, 102, 2),
(53, 134, 110, 1),
(54, 135, 110, 1),
(55, 136, 110, 2),
(56, 137, 110, 2),
(57, 138, 110, 2),
(58, 139, 110, 3),
(59, 140, 110, 3),
(60, 141, 110, 3),
(61, 144, 111, 1),
(62, 145, 111, 1),
(63, 146, 111, 1),
(64, 147, 111, 2),
(65, 148, 111, 2),
(66, 149, 111, 2),
(67, 150, 111, 3),
(68, 151, 111, 3),
(69, 152, 111, 3),
(70, 153, 111, 4),
(71, 154, 111, 4),
(72, 155, 111, 4),
(73, 156, 111, 4),
(74, 162, 112, 1),
(75, 163, 112, 1),
(76, 164, 112, 1),
(77, 165, 112, 2),
(78, 166, 112, 2),
(79, 167, 112, 2),
(80, 168, 112, 3),
(81, 169, 112, 3),
(82, 170, 112, 3),
(83, 176, 106, 1),
(84, 135, 106, 1),
(85, 134, 106, 2),
(86, 137, 106, 2),
(87, 138, 106, 2),
(88, 176, 107, 1),
(89, 134, 107, 1),
(90, 177, 107, 1),
(91, 135, 107, 2),
(92, 133, 107, 2),
(93, 63, 95, 1),
(94, 59, 95, 1),
(95, 57, 95, 2),
(96, 111, 95, 2),
(97, 178, 95, 2),
(98, 56, 95, 2),
(99, 67, 95, 3),
(100, 54, 95, 3),
(101, 66, 95, 3);

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

DROP TABLE IF EXISTS `student_attendance`;
CREATE TABLE IF NOT EXISTS `student_attendance` (
  `stud_att_id` int NOT NULL AUTO_INCREMENT,
  `enrol_id_fk` int NOT NULL COMMENT 'student ID',
  `stream_id_fk` int NOT NULL,
  `admission_id_fk` int NOT NULL COMMENT 'teachers ID taking attendance',
  `subject_id_fk` int DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `attendance_note` varchar(500) NOT NULL,
  `attendance_type` varchar(60) NOT NULL,
  `attendance_status` varchar(60) NOT NULL,
  PRIMARY KEY (`stud_att_id`),
  KEY `fk_attendance_stream` (`stream_id_fk`),
  KEY `fk_attendance_enrol` (`enrol_id_fk`),
  KEY `fk_attendance_admission` (`admission_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_attendance`
--

INSERT INTO `student_attendance` (`stud_att_id`, `enrol_id_fk`, `stream_id_fk`, `admission_id_fk`, `subject_id_fk`, `attendance_date`, `attendance_note`, `attendance_type`, `attendance_status`) VALUES
(1, 10, 91, 14, NULL, '2026-05-28', 'Call in Sick with medical record submitted', 'Daily', 'Sick'),
(2, 16, 91, 14, NULL, '2026-05-28', '', 'Daily', 'No Reason'),
(3, 11, 91, 14, NULL, '2026-05-28', '', 'Daily', 'Absent'),
(4, 14, 91, 14, NULL, '2026-05-28', '', 'Daily', 'Present'),
(5, 12, 91, 14, NULL, '2026-05-28', '', 'Daily', 'Present'),
(6, 15, 91, 14, NULL, '2026-05-28', '', 'Daily', 'Present'),
(7, 9, 91, 14, NULL, '2026-05-28', '', 'Daily', 'Present'),
(8, 13, 91, 14, NULL, '2026-05-28', '', 'Daily', 'Present'),
(9, 10, 91, 14, NULL, '2026-05-25', '', 'Daily', 'Present'),
(10, 16, 91, 14, NULL, '2026-05-25', '', 'Daily', 'Present'),
(11, 11, 91, 14, NULL, '2026-05-25', '', 'Daily', 'Present'),
(12, 14, 91, 14, NULL, '2026-05-25', '', 'Daily', 'Present'),
(13, 12, 91, 14, NULL, '2026-05-25', '', 'Daily', 'Present'),
(14, 15, 91, 14, NULL, '2026-05-25', '', 'Daily', 'Present'),
(15, 9, 91, 14, NULL, '2026-05-25', '', 'Daily', 'Present'),
(16, 13, 91, 14, NULL, '2026-05-25', '', 'Daily', 'Present'),
(17, 10, 91, 14, NULL, '2026-05-26', '', 'Daily', 'Present'),
(18, 16, 91, 14, NULL, '2026-05-26', '', 'Daily', 'Present'),
(19, 11, 91, 14, NULL, '2026-05-26', '', 'Daily', 'Present'),
(20, 14, 91, 14, NULL, '2026-05-26', '', 'Daily', 'Present'),
(21, 12, 91, 14, NULL, '2026-05-26', 'Student arrive late but was accepted into class', 'Daily', 'Transportation Issue'),
(22, 15, 91, 14, NULL, '2026-05-26', '', 'Daily', 'Present'),
(23, 9, 91, 14, NULL, '2026-05-26', '', 'Daily', 'Present'),
(24, 13, 91, 14, NULL, '2026-05-26', '', 'Daily', 'Absent'),
(25, 10, 91, 14, NULL, '2026-05-27', '', 'Daily', 'Present'),
(26, 16, 91, 14, NULL, '2026-05-27', '', 'Daily', 'Present'),
(27, 11, 91, 14, NULL, '2026-05-27', '', 'Daily', 'Present'),
(28, 14, 91, 14, NULL, '2026-05-27', '', 'Daily', 'Present'),
(29, 12, 91, 14, NULL, '2026-05-27', '', 'Daily', 'Present'),
(30, 15, 91, 14, NULL, '2026-05-27', '', 'Daily', 'Present'),
(31, 9, 91, 14, NULL, '2026-05-27', '', 'Daily', 'Present'),
(32, 13, 91, 14, NULL, '2026-05-27', '', 'Daily', 'Present'),
(33, 10, 91, 14, NULL, '2026-05-22', '', 'Daily', 'Sick'),
(34, 16, 91, 14, NULL, '2026-05-22', '', 'Daily', 'Sick'),
(35, 11, 91, 14, NULL, '2026-05-22', '', 'Daily', 'Sick'),
(36, 14, 91, 14, NULL, '2026-05-22', '', 'Daily', 'Present'),
(37, 12, 91, 14, NULL, '2026-05-22', '', 'Daily', 'Present'),
(38, 15, 91, 14, NULL, '2026-05-22', '', 'Daily', 'Present'),
(39, 9, 91, 14, NULL, '2026-05-22', '', 'Daily', 'Present'),
(40, 13, 91, 14, NULL, '2026-05-22', '', 'Daily', 'Present'),
(41, 10, 91, 14, NULL, '2026-05-01', '', 'Daily', 'Present'),
(42, 16, 91, 14, NULL, '2026-05-01', '', 'Daily', 'Present'),
(43, 11, 91, 14, NULL, '2026-05-01', '', 'Daily', 'Present'),
(44, 14, 91, 14, NULL, '2026-05-01', '', 'Daily', 'Present'),
(45, 12, 91, 14, NULL, '2026-05-01', '', 'Daily', 'Sick'),
(46, 15, 91, 14, NULL, '2026-05-01', '', 'Daily', 'Present'),
(47, 9, 91, 14, NULL, '2026-05-01', '', 'Daily', 'Sick'),
(48, 13, 91, 14, NULL, '2026-05-01', '', 'Daily', 'Present'),
(57, 10, 91, 14, NULL, '2026-05-04', '', 'Daily', 'Present'),
(58, 16, 91, 14, NULL, '2026-05-04', '', 'Daily', 'Present'),
(59, 11, 91, 14, NULL, '2026-05-04', '', 'Daily', 'Present'),
(60, 14, 91, 14, NULL, '2026-05-04', '', 'Daily', 'Present'),
(61, 12, 91, 14, NULL, '2026-05-04', '', 'Daily', 'Present'),
(62, 15, 91, 14, NULL, '2026-05-04', '', 'Daily', 'Present'),
(63, 9, 91, 14, NULL, '2026-05-04', '', 'Daily', 'Present'),
(64, 13, 91, 14, NULL, '2026-05-04', '', 'Daily', 'Present'),
(65, 10, 91, 14, NULL, '2026-05-05', '', 'Daily', 'Present'),
(66, 16, 91, 14, NULL, '2026-05-05', '', 'Daily', 'Present'),
(67, 11, 91, 14, NULL, '2026-05-05', '', 'Daily', 'Present'),
(68, 14, 91, 14, NULL, '2026-05-05', '', 'Daily', 'Sick'),
(69, 12, 91, 14, NULL, '2026-05-05', '', 'Daily', 'Present'),
(70, 15, 91, 14, NULL, '2026-05-05', '', 'Daily', 'Present'),
(71, 9, 91, 14, NULL, '2026-05-05', '', 'Daily', 'Present'),
(72, 13, 91, 14, NULL, '2026-05-05', '', 'Daily', 'Present'),
(73, 10, 91, 14, NULL, '2026-05-06', '', 'Daily', 'Present'),
(74, 16, 91, 14, NULL, '2026-05-06', '', 'Daily', 'Present'),
(75, 11, 91, 14, NULL, '2026-05-06', '', 'Daily', 'Present'),
(76, 14, 91, 14, NULL, '2026-05-06', '', 'Daily', 'Present'),
(77, 12, 91, 14, NULL, '2026-05-06', '', 'Daily', 'Present'),
(78, 15, 91, 14, NULL, '2026-05-06', '', 'Daily', 'Present'),
(79, 9, 91, 14, NULL, '2026-05-06', '', 'Daily', 'Present'),
(80, 13, 91, 14, NULL, '2026-05-06', '', 'Daily', 'Absent'),
(81, 10, 91, 14, NULL, '2026-05-07', '', 'Daily', 'Transportation Issue'),
(82, 16, 91, 14, NULL, '2026-05-07', '', 'Daily', 'Present'),
(83, 11, 91, 14, NULL, '2026-05-07', '', 'Daily', 'Present'),
(84, 14, 91, 14, NULL, '2026-05-07', '', 'Daily', 'Present'),
(85, 12, 91, 14, NULL, '2026-05-07', '', 'Daily', 'Present'),
(86, 15, 91, 14, NULL, '2026-05-07', '', 'Daily', 'Present'),
(87, 9, 91, 14, NULL, '2026-05-07', '', 'Daily', 'Present'),
(88, 13, 91, 14, NULL, '2026-05-07', '', 'Daily', 'Family Obligation'),
(97, 10, 91, 14, NULL, '2026-05-11', '', 'Daily', 'Present'),
(98, 16, 91, 14, NULL, '2026-05-11', '', 'Daily', 'Present'),
(99, 11, 91, 14, NULL, '2026-05-11', '', 'Daily', 'Present'),
(100, 14, 91, 14, NULL, '2026-05-11', '', 'Daily', 'Present'),
(101, 12, 91, 14, NULL, '2026-05-11', '', 'Daily', 'Present'),
(102, 15, 91, 14, NULL, '2026-05-11', '', 'Daily', 'Present'),
(103, 9, 91, 14, NULL, '2026-05-11', '', 'Daily', 'Present'),
(104, 13, 91, 14, NULL, '2026-05-11', '', 'Daily', 'Present'),
(105, 10, 91, 14, NULL, '2026-05-12', '', 'Daily', 'Present'),
(106, 16, 91, 14, NULL, '2026-05-12', '', 'Daily', 'Present'),
(107, 11, 91, 14, NULL, '2026-05-12', '', 'Daily', 'Present'),
(108, 14, 91, 14, NULL, '2026-05-12', '', 'Daily', 'Present'),
(109, 12, 91, 14, NULL, '2026-05-12', '', 'Daily', 'Present'),
(110, 15, 91, 14, NULL, '2026-05-12', '', 'Daily', 'Present'),
(111, 9, 91, 14, NULL, '2026-05-12', '', 'Daily', 'Present'),
(112, 13, 91, 14, NULL, '2026-05-12', '', 'Daily', 'Present'),
(113, 10, 91, 14, NULL, '2026-05-13', '', 'Daily', 'Present'),
(114, 16, 91, 14, NULL, '2026-05-13', '', 'Daily', 'Present'),
(115, 11, 91, 14, NULL, '2026-05-13', '', 'Daily', 'Sick'),
(116, 14, 91, 14, NULL, '2026-05-13', '', 'Daily', 'Present'),
(117, 12, 91, 14, NULL, '2026-05-13', '', 'Daily', 'Present'),
(118, 15, 91, 14, NULL, '2026-05-13', '', 'Daily', 'Present'),
(119, 9, 91, 14, NULL, '2026-05-13', '', 'Daily', 'Present'),
(120, 13, 91, 14, NULL, '2026-05-13', '', 'Daily', 'Present'),
(121, 10, 91, 14, NULL, '2026-05-14', '', 'Daily', 'Present'),
(122, 16, 91, 14, NULL, '2026-05-14', '', 'Daily', 'Present'),
(123, 11, 91, 14, NULL, '2026-05-14', '', 'Daily', 'Present'),
(124, 14, 91, 14, NULL, '2026-05-14', '', 'Daily', 'Present'),
(125, 12, 91, 14, NULL, '2026-05-14', '', 'Daily', 'Present'),
(126, 15, 91, 14, NULL, '2026-05-14', '', 'Daily', 'Present'),
(127, 9, 91, 14, NULL, '2026-05-14', '', 'Daily', 'Present'),
(128, 13, 91, 14, NULL, '2026-05-14', '', 'Daily', 'Present'),
(129, 10, 91, 14, NULL, '2026-05-15', '', 'Daily', 'Present'),
(130, 16, 91, 14, NULL, '2026-05-15', '', 'Daily', 'Present'),
(131, 11, 91, 14, NULL, '2026-05-15', '', 'Daily', 'Present'),
(132, 14, 91, 14, NULL, '2026-05-15', '', 'Daily', 'Present'),
(133, 12, 91, 14, NULL, '2026-05-15', '', 'Daily', 'Present'),
(134, 15, 91, 14, NULL, '2026-05-15', '', 'Daily', 'Present'),
(135, 9, 91, 14, NULL, '2026-05-15', '', 'Daily', 'Present'),
(136, 13, 91, 14, NULL, '2026-05-15', '', 'Daily', 'Present'),
(137, 10, 91, 14, NULL, '2026-05-18', '', 'Daily', 'Present'),
(138, 16, 91, 14, NULL, '2026-05-18', '', 'Daily', 'Present'),
(139, 11, 91, 14, NULL, '2026-05-18', '', 'Daily', 'Present'),
(140, 14, 91, 14, NULL, '2026-05-18', '', 'Daily', 'Present'),
(141, 12, 91, 14, NULL, '2026-05-18', '', 'Daily', 'Present'),
(142, 15, 91, 14, NULL, '2026-05-18', '', 'Daily', 'Present'),
(143, 9, 91, 14, NULL, '2026-05-18', '', 'Daily', 'Present'),
(144, 13, 91, 14, NULL, '2026-05-18', '', 'Daily', 'Present'),
(145, 10, 91, 14, NULL, '2026-05-19', '', 'Daily', 'Present'),
(146, 16, 91, 14, NULL, '2026-05-19', '', 'Daily', 'Present'),
(147, 11, 91, 14, NULL, '2026-05-19', '', 'Daily', 'Present'),
(148, 14, 91, 14, NULL, '2026-05-19', '', 'Daily', 'Present'),
(149, 12, 91, 14, NULL, '2026-05-19', '', 'Daily', 'Present'),
(150, 15, 91, 14, NULL, '2026-05-19', '', 'Daily', 'Present'),
(151, 9, 91, 14, NULL, '2026-05-19', '', 'Daily', 'Present'),
(152, 13, 91, 14, NULL, '2026-05-19', '', 'Daily', 'Present'),
(153, 10, 91, 14, NULL, '2026-05-20', '', 'Daily', 'Present'),
(154, 16, 91, 14, NULL, '2026-05-20', '', 'Daily', 'Present'),
(155, 11, 91, 14, NULL, '2026-05-20', '', 'Daily', 'Present'),
(156, 14, 91, 14, NULL, '2026-05-20', '', 'Daily', 'Present'),
(157, 12, 91, 14, NULL, '2026-05-20', '', 'Daily', 'Present'),
(158, 15, 91, 14, NULL, '2026-05-20', '', 'Daily', 'Present'),
(159, 9, 91, 14, NULL, '2026-05-20', '', 'Daily', 'Present'),
(160, 13, 91, 14, NULL, '2026-05-20', '', 'Daily', 'Present'),
(161, 10, 91, 14, NULL, '2026-05-21', '', 'Daily', 'Present'),
(162, 16, 91, 14, NULL, '2026-05-21', '', 'Daily', 'Present'),
(163, 11, 91, 14, NULL, '2026-05-21', '', 'Daily', 'Present'),
(164, 14, 91, 14, NULL, '2026-05-21', '', 'Daily', 'Present'),
(165, 12, 91, 14, NULL, '2026-05-21', '', 'Daily', 'Present'),
(166, 15, 91, 14, NULL, '2026-05-21', '', 'Daily', 'Present'),
(167, 9, 91, 14, NULL, '2026-05-21', '', 'Daily', 'Present'),
(168, 13, 91, 14, NULL, '2026-05-21', '', 'Daily', 'Present'),
(184, 13, 91, 14, NULL, '2026-04-30', '', 'Daily', 'Present'),
(183, 9, 91, 14, NULL, '2026-04-30', '', 'Daily', 'Present'),
(182, 15, 91, 14, NULL, '2026-04-30', '', 'Daily', 'Present'),
(181, 12, 91, 14, NULL, '2026-04-30', '', 'Daily', 'Present'),
(180, 14, 91, 14, NULL, '2026-04-30', '', 'Daily', 'Present'),
(179, 11, 91, 14, NULL, '2026-04-30', '', 'Daily', 'Present'),
(178, 16, 91, 14, NULL, '2026-04-30', '', 'Daily', 'Present'),
(177, 10, 91, 14, NULL, '2026-04-30', '', 'Daily', 'Present'),
(185, 10, 91, 14, 26, '2026-05-28', '', 'Subject', 'Present'),
(186, 16, 91, 14, 26, '2026-05-28', '', 'Subject', 'Present'),
(187, 11, 91, 14, 26, '2026-05-28', '', 'Subject', 'Present'),
(188, 14, 91, 14, 26, '2026-05-28', '', 'Subject', 'Present'),
(189, 12, 91, 14, 26, '2026-05-28', '', 'Subject', 'Present'),
(190, 15, 91, 14, 26, '2026-05-28', '', 'Subject', 'Present'),
(191, 9, 91, 14, 26, '2026-05-28', '', 'Subject', 'Present'),
(192, 13, 91, 14, 26, '2026-05-28', '', 'Subject', 'Present'),
(193, 10, 91, 14, 26, '2026-04-30', 'School Bus break down resulting in some of the student missiong the first period', 'Subject', 'Transportation Issue'),
(194, 16, 91, 14, 26, '2026-04-30', '', 'Subject', 'Present'),
(195, 11, 91, 14, 26, '2026-04-30', '', 'Subject', 'Present'),
(196, 14, 91, 14, 26, '2026-04-30', '', 'Subject', 'Present'),
(197, 12, 91, 14, 26, '2026-04-30', '', 'Subject', 'Present'),
(198, 15, 91, 14, 26, '2026-04-30', '', 'Subject', 'Present'),
(199, 9, 91, 14, 26, '2026-04-30', '', 'Subject', 'Present'),
(200, 13, 91, 14, 26, '2026-04-30', '', 'Subject', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance_file`
--

DROP TABLE IF EXISTS `student_attendance_file`;
CREATE TABLE IF NOT EXISTS `student_attendance_file` (
  `stud_att_file_id` int NOT NULL AUTO_INCREMENT,
  `stud_att_id_fk` int NOT NULL,
  `stud_att_file_src` varchar(260) NOT NULL,
  `stud_att_file_type` varchar(10) NOT NULL,
  PRIMARY KEY (`stud_att_file_id`),
  KEY `fk_stud_att_id` (`stud_att_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_attendance_file`
--

INSERT INTO `student_attendance_file` (`stud_att_file_id`, `stud_att_id_fk`, `stud_att_file_src`, `stud_att_file_type`) VALUES
(1, 1, '1779916901_8100ba1d907f.png', 'png'),
(2, 1, '1779916901_1530089cb991.pdf', 'pdf'),
(3, 33, '1779919118_2dd5e38ddd02.jpg', 'jpg'),
(4, 33, '1779919118_4a7bdd1baf34.jpg', 'jpg'),
(5, 34, '1779919118_8b66c0e1357b.png', 'png'),
(6, 34, '1779919118_2ab18508d9d4.png', 'png'),
(7, 35, '1779919118_3d0bc88ba833.png', 'png'),
(8, 35, '1779919118_8b96167195d8.png', 'png'),
(9, 81, '1779920531_3bc8e2bd1680.png', 'png'),
(11, 193, '1779924858_47864eee0f11.png', 'png');

-- --------------------------------------------------------

--
-- Table structure for table `student_subject`
--

DROP TABLE IF EXISTS `student_subject`;
CREATE TABLE IF NOT EXISTS `student_subject` (
  `stud_sub_id` int NOT NULL AUTO_INCREMENT,
  `class_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `sch_sub_id_fk` int NOT NULL,
  `stud_sub_status` varchar(60) NOT NULL,
  PRIMARY KEY (`stud_sub_id`),
  KEY `fk_student_subject_sch_sub` (`sch_sub_id_fk`),
  KEY `fk_student_classroom_class` (`class_id_fk`),
  KEY `fk_student_classroom_user` (`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_subject`
--

INSERT INTO `student_subject` (`stud_sub_id`, `class_id_fk`, `user_id_fk`, `sch_sub_id_fk`, `stud_sub_status`) VALUES
(1, 0, 0, 52, 'Active'),
(2, 0, 0, 51, 'Active'),
(3, 0, 0, 50, 'Active'),
(4, 0, 0, 23, 'Active'),
(5, 0, 0, 64, 'Active'),
(6, 0, 0, 53, 'Active'),
(7, 0, 0, 59, 'Active'),
(8, 0, 0, 56, 'Active'),
(9, 0, 0, 67, 'Active'),
(12, 0, 0, 30, 'Active'),
(11, 0, 0, 109, 'Active'),
(13, 0, 0, 27, 'Active'),
(14, 0, 0, 33, 'Active'),
(15, 0, 0, 26, 'Active'),
(16, 0, 0, 21, 'Active'),
(17, 0, 0, 34, 'Active'),
(18, 0, 0, 37, 'Active'),
(19, 0, 0, 28, 'Active'),
(20, 0, 0, 36, 'Active'),
(21, 0, 0, 32, 'Active'),
(22, 0, 0, 30, 'Active'),
(23, 0, 0, 27, 'Active'),
(24, 0, 0, 33, 'Active'),
(25, 0, 0, 26, 'Active'),
(26, 0, 0, 21, 'Active'),
(27, 0, 0, 34, 'Active'),
(28, 0, 0, 37, 'Active'),
(29, 0, 0, 28, 'Active'),
(30, 0, 0, 31, 'Active'),
(31, 0, 0, 35, 'Active'),
(32, 0, 0, 30, 'Active'),
(33, 0, 0, 27, 'Active'),
(34, 0, 0, 33, 'Active'),
(35, 0, 0, 26, 'Active'),
(36, 0, 0, 21, 'Active'),
(37, 0, 0, 34, 'Active'),
(38, 0, 0, 37, 'Active'),
(39, 0, 0, 28, 'Active'),
(40, 0, 0, 31, 'Active'),
(41, 0, 0, 32, 'Active'),
(42, 0, 0, 30, 'Active'),
(43, 0, 0, 27, 'Active'),
(44, 0, 0, 33, 'Active'),
(45, 0, 0, 26, 'Active'),
(46, 0, 0, 21, 'Active'),
(47, 0, 0, 34, 'Active'),
(48, 0, 0, 37, 'Active'),
(49, 0, 0, 28, 'Active'),
(50, 0, 0, 31, 'Active'),
(51, 0, 0, 32, 'Active'),
(52, 0, 0, 30, 'Active'),
(53, 0, 0, 27, 'Active'),
(54, 0, 0, 33, 'Active'),
(55, 0, 0, 26, 'Active'),
(56, 0, 0, 21, 'Active'),
(57, 0, 0, 34, 'Active'),
(58, 0, 0, 37, 'Active'),
(59, 0, 0, 28, 'Active'),
(60, 0, 0, 31, 'Active'),
(61, 0, 0, 35, 'Active'),
(62, 0, 0, 30, 'Active'),
(63, 0, 0, 27, 'Active'),
(64, 0, 0, 33, 'Active'),
(65, 0, 0, 26, 'Active'),
(66, 0, 0, 21, 'Active'),
(67, 0, 0, 34, 'Active'),
(68, 0, 0, 37, 'Active'),
(69, 0, 0, 28, 'Active'),
(70, 0, 0, 31, 'Active'),
(71, 0, 0, 32, 'Active'),
(72, 0, 0, 30, 'Active'),
(73, 0, 0, 27, 'Active'),
(74, 0, 0, 33, 'Active'),
(75, 0, 0, 26, 'Active'),
(76, 0, 0, 21, 'Active'),
(77, 0, 0, 34, 'Active'),
(78, 0, 0, 37, 'Active'),
(79, 0, 0, 29, 'Active'),
(80, 0, 0, 31, 'Active'),
(81, 0, 0, 35, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
CREATE TABLE IF NOT EXISTS `subject` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `level_id_fk` int NOT NULL,
  `subject_name` varchar(60) DEFAULT NULL,
  `sub_image` varchar(260) NOT NULL,
  PRIMARY KEY (`subject_id`),
  KEY `fk_subject_level_level` (`level_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `level_id_fk`, `subject_name`, `sub_image`) VALUES
(1, 1, 'Language, Literacy and Communication', ''),
(2, 1, 'Mathematics and Numeracy', ''),
(3, 1, 'Environmental Studies', ''),
(4, 1, 'Creative Arts', ''),
(5, 1, 'Physical Development', ''),
(6, 1, 'Social and Emotional Development', ''),
(7, 1, 'Spiritual and Moral Development', ''),
(8, 2, 'Language, Literacy and Communication', ''),
(9, 2, 'Mathematics and Numeracy', ''),
(10, 2, 'Environmental Studies', ''),
(11, 2, 'Creative Arts', ''),
(12, 2, 'Physical Development', ''),
(13, 2, 'Social and Emotional Development', ''),
(14, 2, 'Spiritual and Moral Development', ''),
(15, 3, 'Year 1 English', ''),
(16, 3, 'Year 1 Mathematics', ''),
(17, 3, 'Art Is Fun 1', ''),
(18, 3, 'Year 1 Vosa VakaViti', ''),
(19, 3, 'Year 1 Hindi', ''),
(20, 3, 'Year 1 Rotuman', ''),
(21, 3, 'Year 1 Urdu', ''),
(22, 3, 'Year 1 Performing Arts', ''),
(23, 3, 'Year 1 MCE', ''),
(24, 3, 'Year 1 Nutrition', ''),
(25, 3, 'Year 1 Conversational Vosa VakaViti & Fiji Hindi', ''),
(26, 4, 'Year 2 English', ''),
(27, 4, 'Year 2 Mathematics', ''),
(28, 4, 'Art Is Fun 2', ''),
(29, 4, 'Year 2 Vosa VakaViti', ''),
(30, 4, 'Year 2 Hindi', ''),
(31, 4, 'Year 2 Rotuman', ''),
(32, 4, 'Year 2 Urdu', ''),
(33, 4, 'Year 2 Performing Arts', ''),
(34, 4, 'Year 2 MCE', ''),
(35, 4, 'Year 2 Nutrition', ''),
(36, 4, 'Year 2 Conversational Vosa VakaViti & Fiji Hindi', ''),
(37, 5, 'Year 3 English', ''),
(38, 5, 'Year 3 Mathematics', ''),
(39, 5, 'Art Is Fun 3', ''),
(40, 5, 'Year 3 Vosa VakaViti', ''),
(41, 5, 'Year 3 Hindi', ''),
(42, 5, 'Year 3 Rotuman', ''),
(43, 5, 'Year 3 Urdu', ''),
(44, 5, 'Year 3 Performing Arts', ''),
(45, 5, 'Year 3 MCE', ''),
(46, 5, 'Year 3 Nutrition', ''),
(47, 5, 'Year 3 Conversational Vosa VakaViti & Fiji Hindi', ''),
(48, 5, 'PE Is Fun 3', ''),
(49, 5, 'Year 3 Enterprise Education', ''),
(50, 6, 'Year 4 English', ''),
(51, 6, 'Year 4 Mathematics', ''),
(52, 6, 'Art Is Fun 4', ''),
(53, 6, 'Year 4 Vosa VakaViti', ''),
(54, 6, 'Year 4 Hindi', ''),
(55, 6, 'Year 4 Rotuman', ''),
(56, 6, 'Year 4 Urdu', ''),
(57, 6, 'Year 4 Performing Arts', ''),
(58, 6, 'Year 4 MCE', ''),
(59, 6, 'Year 4 Nutrition', ''),
(60, 6, 'Year 4 Conversational Vosa VakaViti & Fiji Hindi', ''),
(61, 6, 'PE Is Fun 4', ''),
(62, 6, 'Year 4 Enterprise Education', ''),
(63, 6, 'Year 4 Social Studies', ''),
(64, 6, 'Year 4 Elementary Science', ''),
(65, 6, 'Year 4 Healthy Living', ''),
(66, 7, 'Year 5 English', ''),
(67, 7, 'Year 5 Mathematics', ''),
(68, 7, 'Art Is Fun 5', ''),
(69, 7, 'Year 5 Vosa VakaViti', ''),
(70, 7, 'Year 5 Hindi', ''),
(71, 7, 'Year 5 Rotuman', ''),
(72, 7, 'Year 5 Urdu', ''),
(73, 7, 'Year 5 Performing Arts', ''),
(74, 7, 'Year 5 MCE', ''),
(75, 7, 'Year 5 Nutrition', ''),
(76, 7, 'Year 5 Conversational Vosa VakaViti & Fiji Hindi', ''),
(77, 7, 'PE Is Fun 5', ''),
(78, 7, 'Year 5 Enterprise Education', ''),
(79, 7, 'Year 5 Social Studies', ''),
(80, 7, 'Year 5 Elementary Science', ''),
(81, 7, 'Year 5 Healthy Living', ''),
(82, 8, 'Year 6 English', ''),
(83, 8, 'Year 6 Mathematics', ''),
(84, 8, 'Art Is Fun 6', ''),
(85, 8, 'Year 6 Vosa VakaViti', ''),
(86, 8, 'Year 6 Hindi', ''),
(87, 8, 'Year 6 Rotuman', ''),
(88, 8, 'Year 6 Urdu', ''),
(89, 8, 'Year 6 Performing Arts', ''),
(90, 8, 'Year 6 MCE', ''),
(91, 8, 'Year 6 Nutrition', ''),
(92, 8, 'Year 6 Conversational Vosa VakaViti & Fiji Hindi', ''),
(93, 8, 'PE Is Fun 6', ''),
(94, 8, 'Year 6 Enterprise Education', ''),
(95, 8, 'Year 6 Social Studies', ''),
(96, 8, 'Year 6 Elementary Science', ''),
(97, 8, 'Year 6 Healthy Living', ''),
(98, 9, 'Year 7 English', ''),
(99, 9, 'Year 7 Mathematics', ''),
(100, 9, 'Art Is Fun 7', ''),
(101, 9, 'Year 7 Vosa VakaViti', ''),
(102, 9, 'Year 7 Hindi', ''),
(103, 9, 'Year 7 Rotuman', ''),
(104, 9, 'Year 7 Urdu', ''),
(105, 9, 'Year 7 Performing Arts', ''),
(106, 9, 'Year 7 MCE', ''),
(107, 9, 'Year 7 Nutrition', ''),
(108, 9, 'Year 7 Conversational Vosa VakaViti & Fiji Hindi', ''),
(109, 9, 'PE Is Fun 7', ''),
(110, 9, 'Year 7 Enterprise Education', ''),
(111, 9, 'Year 7 Social Science', ''),
(112, 9, 'Year 7 Basic Science', ''),
(113, 9, 'Year 7 Healthy Living', ''),
(114, 10, 'Year 8 English', ''),
(115, 10, 'Year 8 Mathematics', ''),
(116, 10, 'Art Is Fun 8', ''),
(117, 10, 'Year 8 Vosa VakaViti', ''),
(118, 10, 'Year 8 Hindi', ''),
(119, 10, 'Year 8 Rotuman', ''),
(120, 10, 'Year 8 Urdu', ''),
(121, 10, 'Year 8 Performing Arts', ''),
(122, 10, 'Year 8 MCE', ''),
(123, 10, 'Year 8 Nutrition', ''),
(124, 10, 'Year 8 Conversational Vosa VakaViti & Fiji Hindi', ''),
(125, 10, 'PE Is Fun 8', ''),
(126, 10, 'Year 8 Enterprise Education', ''),
(127, 10, 'Year 8 Social Science', ''),
(128, 10, 'Year 8 Basic Science', ''),
(129, 10, 'Year 8 Healthy Living', ''),
(130, 11, 'Year 9 English', ''),
(131, 11, 'Year 9 Mathematics', ''),
(132, 11, 'Year 9 Basic Science', ''),
(133, 11, 'Year 9 Vosa VakaViti', ''),
(134, 11, 'Year 9 Hindi', ''),
(135, 11, 'Year 9 Rotuman', ''),
(136, 11, 'Year 9 Urdu', ''),
(137, 11, 'Year 9 Art & Craft', ''),
(138, 11, 'Year 9 Argriculture Science', ''),
(139, 11, 'Year 9 BT & BGT', ''),
(140, 11, 'Year 9 Conversational Vosa VakaViti & Fiji Hindi', ''),
(141, 11, 'Year 9 Commercial Studies', ''),
(142, 11, 'Year 9 Physical Education', ''),
(143, 11, 'Year 9 Home Economics', ''),
(144, 11, 'Year 9 Office Technology', ''),
(145, 11, 'Year 9 Performing Arts', ''),
(146, 11, 'Year 9 Social Science', ''),
(147, 12, 'Year 10 English', ''),
(148, 12, 'Year 10 Mathematics', ''),
(149, 12, 'Year 10 Basic Science', ''),
(150, 12, 'Year 10 Vosa VakaViti', ''),
(151, 12, 'Year 10 Hindi', ''),
(152, 12, 'Year 10 Rotuman', ''),
(153, 12, 'Year 10 Urdu', ''),
(154, 12, 'Year 10 Art & Craft', ''),
(155, 12, 'Year 10 Argriculture Science', ''),
(156, 12, 'Year 10 BT & BGT', ''),
(157, 12, 'Year 10 Conversational Vosa VakaViti & Fiji Hindi', ''),
(158, 12, 'Year 10 Commercial Studies', ''),
(159, 12, 'Year 10 Physical Education', ''),
(160, 12, 'Year 10 Home Economics', ''),
(161, 12, 'Year 10 Office Technology', ''),
(162, 12, 'Year 10 Performing Arts', ''),
(163, 12, 'Year 10 Social Science', ''),
(164, 13, 'Year 11 English', ''),
(165, 13, 'Year 11 Mathematics', ''),
(166, 13, 'Year 11 Chemistry', ''),
(167, 13, 'Year 11 Biology', ''),
(168, 13, 'Year 11 Physics', ''),
(169, 13, 'Year 11 History', ''),
(170, 13, 'Year 11 Geography', ''),
(171, 13, 'Year 11 Vosa VakaViti', ''),
(172, 13, 'Year 11 Hindi', ''),
(173, 13, 'Year 11 Rotuman', ''),
(174, 13, 'Year 11 Urdu', ''),
(175, 13, 'Year 11 Art & Craft', ''),
(176, 13, 'Year 11 Argriculture Science', ''),
(177, 13, 'Year 11 Technical Drawing', ''),
(178, 13, 'Year 11 Applied Technology', ''),
(179, 13, 'Year 11 Economic', ''),
(180, 13, 'Year 11 Accounting', ''),
(181, 13, 'Year 11 Physical Education', ''),
(182, 13, 'Year 11 Home Economics', ''),
(183, 13, 'Year 11 Office Technology', ''),
(184, 13, 'Year 11 Computer Science', ''),
(185, 14, 'Year 12 English', ''),
(186, 14, 'Year 12 Mathematics', ''),
(187, 14, 'Year 12 Chemistry', ''),
(188, 14, 'Year 12 Biology', ''),
(189, 14, 'Year 12 Physics', ''),
(190, 14, 'Year 12 History', ''),
(191, 14, 'Year 12 Geography', ''),
(192, 14, 'Year 12 Vosa VakaViti', ''),
(193, 14, 'Year 12 Hindi', ''),
(194, 14, 'Year 12 Rotuman', ''),
(195, 14, 'Year 12 Urdu', ''),
(196, 14, 'Year 12 Art & Craft', ''),
(197, 14, 'Year 12 Argriculture Science', ''),
(198, 14, 'Year 12 Technical Drawing', ''),
(199, 14, 'Year 12 Applied Technology', ''),
(200, 14, 'Year 12 Economic', ''),
(201, 14, 'Year 12 Accounting', ''),
(202, 14, 'Year 12 Physical Education', ''),
(203, 14, 'Year 12 Home Economics', ''),
(204, 14, 'Year 12 Office Technology', ''),
(205, 14, 'Year 12 Computer Science', ''),
(206, 15, 'Year 13 English', ''),
(207, 15, 'Year 13 Mathematics', ''),
(208, 15, 'Year 13 Chemistry', ''),
(209, 15, 'Year 13 Biology', ''),
(210, 15, 'Year 13 Physics', ''),
(211, 15, 'Year 13 History', ''),
(212, 15, 'Year 13 Geography', ''),
(213, 15, 'Year 13 Vosa VakaViti', ''),
(214, 15, 'Year 13 Hindi', ''),
(215, 15, 'Year 13 Rotuman', ''),
(216, 15, 'Year 13 Urdu', ''),
(217, 15, 'Year 13 Art & Craft', ''),
(218, 15, 'Year 13 Argriculture Science', ''),
(219, 15, 'Year 13 Technical Drawing', ''),
(220, 15, 'Year 13 Applied Technology', ''),
(221, 15, 'Year 13 Economic', ''),
(222, 15, 'Year 13 Accounting', ''),
(223, 15, 'Year 13 Physical Education', ''),
(224, 15, 'Year 13 Home Economics', ''),
(225, 15, 'Year 13 Office Technology', ''),
(226, 15, 'Year 13 Computer Science', '');

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

DROP TABLE IF EXISTS `subscription`;
CREATE TABLE IF NOT EXISTS `subscription` (
  `subscription_id` int NOT NULL AUTO_INCREMENT,
  `plan_id_fk` int DEFAULT NULL,
  `sch_id_fk` int DEFAULT NULL,
  `subscription_start_date` date DEFAULT NULL,
  `subscription_end_date` date DEFAULT NULL,
  `subscription_time` int DEFAULT NULL,
  `subscription_term` int DEFAULT NULL,
  `payment_mode` varchar(260) NOT NULL,
  `subscription_status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`subscription_id`),
  KEY `fk_subscription_plan` (`plan_id_fk`),
  KEY `fk_subscription_school` (`sch_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`subscription_id`, `plan_id_fk`, `sch_id_fk`, `subscription_start_date`, `subscription_end_date`, `subscription_time`, `subscription_term`, `payment_mode`, `subscription_status`) VALUES
(1, 1, 12, '2025-12-08', '2026-01-31', NULL, 12, 'Cash', 'Active'),
(6, 3, 26, '2026-02-19', '2029-02-19', NULL, 36, 'Cash', 'Pending Payment'),
(9, 1, 29, '2026-05-13', '2026-06-13', NULL, 12, 'Cash', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `district_id_fk` int NOT NULL,
  `password` varchar(260) NOT NULL,
  `username` varchar(60) NOT NULL,
  `fname` varchar(60) DEFAULT NULL,
  `lname` varchar(60) DEFAULT NULL,
  `oname` varchar(60) DEFAULT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pending_email_update` varchar(260) DEFAULT NULL,
  `phone` int DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_time` int DEFAULT NULL,
  `online_status` varchar(45) DEFAULT NULL,
  `password_reset_code` varchar(60) DEFAULT NULL,
  `profile_photo` varchar(250) NOT NULL,
  `is_a_parent` tinyint(1) NOT NULL,
  `updated_date` datetime DEFAULT NULL,
  `updated_time` int DEFAULT NULL,
  `security_token` varchar(260) DEFAULT NULL,
  `security_token_expiry` datetime DEFAULT NULL,
  `account_status` varchar(250) DEFAULT NULL,
  `user_status` varchar(45) DEFAULT NULL,
  `two_factor_method` varchar(20) DEFAULT NULL COMMENT 'authenticator / otp_email / null',
  `two_factor_secret` varchar(64) DEFAULT NULL COMMENT 'TOTP secret key',
  `two_factor_enabled` tinyint(1) DEFAULT '0',
  `otp_code` varchar(10) DEFAULT NULL COMMENT 'Email OTP code',
  `otp_expiry` int DEFAULT NULL COMMENT 'Unix timestamp',
  `otp_verified` tinyint(1) DEFAULT '0',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expiry` int DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_user_ditrict` (`district_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `district_id_fk`, `password`, `username`, `fname`, `lname`, `oname`, `gender`, `dob`, `address`, `email`, `pending_email_update`, `phone`, `created_date`, `created_time`, `online_status`, `password_reset_code`, `profile_photo`, `is_a_parent`, `updated_date`, `updated_time`, `security_token`, `security_token_expiry`, `account_status`, `user_status`, `two_factor_method`, `two_factor_secret`, `two_factor_enabled`, `otp_code`, `otp_expiry`, `otp_verified`, `reset_token`, `reset_token_expiry`) VALUES
(1, 56, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Pio', 'Baleicoqe', '', 'Male', '1982-01-06', 'Veivauceva 3, 6 Miles, Tacirua', 'piobaleicoqe@yahoo.com', NULL, 9896700, '2025-05-14', 1747194903, 'Online', NULL, 'avatar_1721681108.jpg', 0, '2026-05-11 11:48:11', 0, NULL, NULL, 'Active', 'Active', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(11, 50, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Pio', 'Baleicoqe', '', 'Male', '2026-01-30', '6 Miles', 'piobaleicoqe2@gmail.com', NULL, 1234567, '2026-01-14', 1768363922, 'Offline', '', '1778626718_73cc384e833c05112504.png', 0, NULL, 0, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(12, 16, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Sam', 'White', '', 'Male', '2026-02-02', 'sgsfgsdgdsggd', 'info@baleicoqe.com', NULL, 1234567, '2026-02-02', 1769998699, 'Online', NULL, '1769998699_c2f5e3c9d0452d1e685c.jpg', 0, NULL, 0, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(14, 130, '$2y$10$SpAGtSzYl.ILorqp5cYZoey5X2JWka2q0FV34T8Nw.gUkCbfDC.pS', '', 'Daniel', 'Carter', 'Junior', 'Male', '2026-02-03', '6 Miles\r\nVeivauceva 2', 'piobaleicoqe49@gmail.com', NULL, 9896700, '2026-02-03', 1770084104, 'Offline', 'c920491520eb2f1b7d34183f0e2b9f09', '1770235198_2d1110dd204994b25d1a.jpg', 0, NULL, 0, NULL, NULL, NULL, 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(18, 192, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Mary', 'Lou', '', 'Female', '2026-02-03', '6 Miles\r\nTacirua', 'pio@baleicoqe.com', NULL, 1234567, '2026-02-03', 1770093072, 'Offline', 'de1fe291be6bb18f4135b82929838a6f', '1770093072_e7acbdf94d841d01518c.png', 0, '2026-05-12 00:00:00', 0, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(20, 42, '', '', 'Peni', 'Ravai', '', 'Male', '2006-02-09', NULL, '', NULL, NULL, '2026-03-02', 1772407401, 'Offline', '7b888e80c4d02f02079dbefaf1d5978e', '', 0, NULL, 0, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(27, 78, '', '', 'Benjamin', 'Dada', '', 'Male', '2005-03-22', 'Gau Island', '', NULL, NULL, '2026-03-02', 1772420518, 'Offline', 'a8bc49bcfb65fad01b730cbc2bf5d9ec', '1780356115_82ef291cdf64d51ef752.jpg', 0, NULL, 0, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(28, 28, '', '', 'Mary', 'Low', '', 'Male', '1987-03-02', 'Bua', '', NULL, 1234567, '2026-03-02', 1772420990, 'Offline', '0c0c34d8a3362fcffc6222812f92d07d', '', 0, NULL, 0, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(29, 28, '', '', 'Mary', 'Low', '', 'Male', '1987-03-02', 'Bua', '', NULL, 1234567, '2026-03-02', 1772421169, 'Offline', '8ba6e4a897569d3bf05bb17d4426b7f6', '1772421169_3992fd10c5c3c60eee03.jpg', 0, NULL, 0, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(30, 52, '', '', 'James', 'Smith', '', 'Male', '2007-06-06', 'Kadavu', '', NULL, NULL, '2026-03-03', 1772487700, 'Offline', '8cadf6f726987f6ebda6f1157b67eb6a', '1772487700_725e937017d873ea3fb3.jpg', 0, NULL, 0, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(31, 141, '', '', 'Peni', 'Wise', '', 'Male', '2007-03-03', 'Test', '', NULL, 1234567, '2026-03-03', 1772488549, 'Offline', '3d3b532b3f172d595b13b6b9296e6d8d', '', 0, NULL, 0, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(32, 118, '$2y$10$f4Q9ra23X8SpDnD7lB47GurTJJleQH9yrs4Lzgpf5xTYaSsf5LCQK', '', 'James', 'Brown', '', 'Male', '1990-03-29', 'Suva', 'hris.mitt@gmail.com', NULL, 1234567, '2026-03-03', 1772488632, 'Offline', 'a7deb7af6c8b71533406c9fd5fff5349', '1772488632_088ddb903bffab3a82ca.jpg', 1, NULL, 0, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(33, 130, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Mereisi', 'Talei', '', 'Female', '1989-05-27', '71, Nailuva Road, Suva', 'mtalei@yahoo.com', NULL, 7890678, '2026-05-27', 1779826374, 'Offline', '053097c6e609be3d0658afd6f00f9c04', '', 1, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(34, 28, '$2y$10$pinrhpgBGHoNXvsPQ149JOxRe/o/0usx5o.37S5dX480jRR6ov78K', '', 'Peter', 'Guss', '', 'Male', '2005-05-05', 'Sinu ROad, Nabua, Suva', 'pguss@gmail.com', NULL, 9087967, '2026-05-27', 1779826463, 'Offline', 'f1d87835f482cfe096aa3d78f5b3d4a8', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(35, 34, '', '', 'Shane', 'Davis', '', 'Male', '2008-05-27', NULL, '', NULL, NULL, '2026-05-27', 1779834195, 'Offline', 'c34587166878d32f3dd081692fd14917', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(36, 22, '', '', 'Shane', 'Davis', '', 'Male', '2009-05-27', NULL, '', NULL, NULL, '2026-05-27', 1779836663, 'Offline', 'd4de89c4ca62072ebec03f60f8a4cda2', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(37, 84, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', 'Maeri24', 'Maeri', 'Luikali', '', 'Female', '2009-05-29', NULL, '', NULL, NULL, '2026-05-27', 1779838403, 'Offline', 'ee6feb9e0b0ecc80426d6402b403cc5c', '1779840157_8b39d23135066baa1f6a.png', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(38, 193, '', '', 'Jenifer ', 'Pareti', '', 'Female', '2009-05-05', NULL, '', NULL, NULL, '2026-05-28', 1779913023, 'Offline', '118eafb0ee0c3e7cedd4d57056ee1a92', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(39, 47, '', '', 'Maleli', 'Tora', 'Uluiviti', 'Male', '2009-04-09', NULL, '', NULL, NULL, '2026-05-28', 1779913079, 'Offline', 'ac705d10f98d9a23b7ddfe7936a135f0', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(40, 133, '', '', 'Ilisabeta', 'Matata', '', 'Female', '2009-07-23', NULL, '', NULL, NULL, '2026-05-28', 1779913118, 'Offline', 'e50bfefbd46dcfe403f25fd5b1e23ced', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(41, 111, '', '', 'Edwin', 'Smith', '', 'Male', '2009-02-04', NULL, '', NULL, NULL, '2026-05-28', 1779913168, 'Offline', '2305f438366c4ce6a2bab4b10d8cc14a', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(42, 159, '', '', 'Sherine', 'Kumar', '', 'Female', '2009-08-28', NULL, '', NULL, NULL, '2026-05-28', 1779913233, 'Offline', '5035a959b0618c0b4c12050e1288966c', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(43, 42, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Bese', 'Takina', '', 'Male', '2026-06-13', 'Suva City', 'bese@yahoo.com', NULL, 1234567, '2026-06-01', 1780261886, 'Online', '2aa69fe13c95e759ef2b3604b92dfd77', '1780261886_fbd7205fcea30083b90e.jpg', 1, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(44, 87, '$2y$10$B6UiXsh3mj1L2TIYymYkweAikthkKs3g7UpfqlWZXN1kQHw/SHLEq', '', 'Peter', 'Toganivalu', '', 'Male', '1889-06-02', 'Wailevu', 'pitatoga@yahoo.com', NULL, 8796578, '2026-06-01', 1780264419, 'Offline', '5086822e64ed10884c2598263d28be97', '', 1, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(45, 195, '$2y$10$a9UJNgUtjYk57LCNTl6KDueXsUmOUW9WgQavhu8j5hoCzTZIwYrc6', '', 'Mandy', 'Lopez', '', 'Female', '1969-06-04', 'Baw View Heights, Suva', 'mlopez@yahoo.com', NULL, 8769087, '2026-06-02', 1780342192, 'Offline', 'dd16ba51370cc2306d63815342b7c179', '1780342192_beac329b5cfa7ad3c080.jpg', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

DROP TABLE IF EXISTS `user_log`;
CREATE TABLE IF NOT EXISTS `user_log` (
  `user_log_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `ip_aadress` varchar(45) DEFAULT NULL,
  `user_agent` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_device` varchar(60) DEFAULT NULL,
  `log_title` varchar(60) DEFAULT NULL,
  `log_desc` varchar(260) DEFAULT NULL,
  `log_date` date DEFAULT NULL,
  `log_time` int DEFAULT NULL,
  `log_icon` varchar(260) DEFAULT NULL,
  `log_theme` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`user_log_id`),
  KEY `fk_user_log_user` (`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=884 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`user_log_id`, `user_id_fk`, `ip_aadress`, `user_agent`, `user_device`, `log_title`, `log_desc`, `log_date`, `log_time`, `log_icon`, `log_theme`) VALUES
(3, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Register School Account', 'Successfully registered school information.', '2026-01-14', 1768363922, '<i class=\"ki-duotone ki-copy-success\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(4, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20', 'Desktop', 'Activate User Account', 'Successfully verifyed user email address and user account activated.', '2026-01-15', 1768424247, '<i class=\"ki-duotone ki-user-tick\"> <span class=\"path1\"></span> <span class=\"path2\"></span> <span class=\"path3\"></span></i>', 'info'),
(5, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20', 'Desktop', 'Activate User Account', 'Successfully verifyed user email address and user account activated.', '2026-01-15', 1768427888, '<i class=\"ki-duotone ki-user-tick\"> <span class=\"path1\"></span> <span class=\"path2\"></span> <span class=\"path3\"></span></i>', 'info'),
(6, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-20', 1768882091, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(7, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-20', 1768882111, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(8, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-20', 1768882116, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(9, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-20', 1768882231, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(10, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768940255, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(11, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768940346, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(12, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768941416, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(13, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768941790, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(14, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768943434, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(15, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768945309, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(16, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768945818, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(17, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768950533, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(18, 11, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768952048, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(19, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-21', 1768952086, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(20, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-22', 1769027362, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(21, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-22', 1769053795, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(22, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-23', 1769119553, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(23, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-01-23', 1769119576, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(24, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-23', 1769119594, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(25, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-01-23', 1769119599, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(26, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-23', 1769119629, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(27, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-01-23', 1769119633, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(28, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-23', 1769123832, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(29, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-23', 1769123832, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(30, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-23', 1769137710, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(31, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-23', 1769137710, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(33, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Role', 'Role Another New Role has been created successfully!', '2026-01-23', 1769138779, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(34, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-23', 1769138779, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(35, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-23', 1769138979, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(36, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-23', 1769139024, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(37, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-23', 1769139038, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(38, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-23', 1769139091, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(39, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-23', 1769139187, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(40, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-23', 1769139208, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(41, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-23', 1769139623, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(42, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-28', 1769547340, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(43, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769547340, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(44, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769547348, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(45, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769547563, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(46, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769547824, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(47, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769547988, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(48, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Role', 'Role Test Role has been created successfully!', '2026-01-28', 1769548037, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(49, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769548037, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(50, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769548491, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(51, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769553030, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(52, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Role', 'Role HOD has been created successfully!', '2026-01-28', 1769553172, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(53, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769553173, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(54, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Role', 'Role Assistant Teacher has been created successfully!', '2026-01-28', 1769553260, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(55, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769553260, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(56, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769558301, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(57, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769558608, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(58, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769558711, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(59, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769558765, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(60, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769559490, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(61, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769559751, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(62, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769559797, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(63, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Role', 'Role rtertert has been created successfully!', '2026-01-28', 1769559926, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(64, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769559927, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(65, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769562154, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(66, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769562281, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(67, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769562344, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(68, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769562434, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(69, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769562489, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(70, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769562993, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(71, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769563267, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(72, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769563769, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(73, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769563852, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(74, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769563940, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(75, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769563965, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(76, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769564456, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(77, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769564464, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(78, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769564616, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(79, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769564727, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(80, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769564814, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(81, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769564862, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(82, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769564949, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(83, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769564962, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(84, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567454, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(85, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567488, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(86, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567510, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(87, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567540, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(88, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567580, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(89, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567642, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(90, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567672, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(91, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567704, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(92, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567768, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(93, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567791, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(94, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567951, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(95, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769567985, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(96, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769568062, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(97, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-01-28', 1769570144, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(98, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-28', 1769570160, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(99, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769570166, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(100, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769570176, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(101, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-28', 1769571030, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(102, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-01-29', 1769630326, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(103, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769630326, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(104, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769630429, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(105, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769630700, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(106, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769633516, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(107, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role School Admin have been updated. Total permissions: 3', '2026-01-29', 1769638964, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(108, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role School Admin have been updated. Total permissions: 2', '2026-01-29', 1769638984, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(109, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role School Admin have been updated. Total permissions: 3', '2026-01-29', 1769638991, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(110, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769639906, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(111, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View permission Listing', 'User view permission listing.', '2026-01-29', 1769640508, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(112, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View permission Listing', 'User view permission listing.', '2026-01-29', 1769640859, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(113, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View permission Listing', 'User view permission listing.', '2026-01-29', 1769640882, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(114, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View permission Listing', 'User view permission listing.', '2026-01-29', 1769640959, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(115, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View permission Listing', 'User view permission listing.', '2026-01-29', 1769641045, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(116, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769644827, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(117, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769644838, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(118, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769644853, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(119, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769644884, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(120, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769645391, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(121, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769645605, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(122, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769645628, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(123, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769646044, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(124, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769646412, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(125, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769646424, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(126, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769647290, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(127, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769647331, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(128, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769647607, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(129, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769647824, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(130, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769647972, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(131, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769648110, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(132, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Role', 'Role \"Assistant Teacher 123\" has been updated successfully!', '2026-01-29', 1769652771, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(133, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769652771, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(134, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Role', 'Role \"Assistant Teacher 123\" has been updated successfully!', '2026-01-29', 1769653044, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(135, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769653044, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(136, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Role', 'Role \"Assistant Teacher\" has been updated successfully!', '2026-01-29', 1769653058, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(137, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769653058, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(138, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Role', 'Role \"Principal\" has been updated successfully!', '2026-01-29', 1769653064, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(139, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769653064, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(140, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Permission', 'Permission \"Test Dashboard\" has been created successfully!', '2026-01-29', 1769653853, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(141, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769653853, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(142, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769653877, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(143, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769654200, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(144, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete Permission', 'Permission \'Test Dashboard\' has been deleted', '2026-01-29', 1769654212, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(145, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769654266, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(146, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Permission', 'Permission \"Test Dashboard\" has been created successfully!', '2026-01-29', 1769654290, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(147, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769654290, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(148, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard\" has been updated successfully!', '2026-01-29', 1769654786, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(149, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769654786, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(150, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard\" has been updated successfully!', '2026-01-29', 1769654811, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(151, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769654811, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(152, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769654933, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(153, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard\" has been updated successfully!', '2026-01-29', 1769654943, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(154, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769654943, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(155, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard dwfwtewe\" has been updated successfully!', '2026-01-29', 1769655580, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(156, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769655580, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(157, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard\" has been updated successfully!', '2026-01-29', 1769655591, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(158, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769655591, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(159, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard\" has been updated successfully!', '2026-01-29', 1769655603, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(160, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769655603, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(161, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard\" has been updated successfully!', '2026-01-29', 1769655615, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(162, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769655615, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(163, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard Today\" has been updated successfully!', '2026-01-29', 1769655637, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(164, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769655637, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info');
INSERT INTO `user_log` (`user_log_id`, `user_id_fk`, `ip_aadress`, `user_agent`, `user_device`, `log_title`, `log_desc`, `log_date`, `log_time`, `log_icon`, `log_theme`) VALUES
(165, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard\" has been updated successfully!', '2026-01-29', 1769656147, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(166, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769656147, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(167, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard Today\" has been updated successfully!', '2026-01-29', 1769656159, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(168, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769656159, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(169, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-01-29', 1769656369, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(170, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769659004, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(171, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Role', 'Role \"New Role Day In n Out\" has been updated successfully!', '2026-01-29', 1769659028, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(172, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-01-29', 1769659028, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(173, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role HOD have been updated. Total permissions: 0', '2026-01-29', 1769660128, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(174, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-02', 1769979000, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(175, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role HOD have been updated. Total permissions: 3', '2026-02-02', 1769979019, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(176, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-02', 1769979031, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(177, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-02', 1769979034, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(178, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-02', 1769979073, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(179, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-02', 1769979089, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(180, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Permission', 'Permission \"Test Dashboard Today\" has been created successfully!', '2026-02-02', 1769979128, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(181, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-02', 1769979128, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(182, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"Test Dashboard Today\" has been updated successfully!', '2026-02-02', 1769979145, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(183, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-02', 1769979145, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(184, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete Permission', 'Permission \'Test Dashboard\' has been deleted', '2026-02-02', 1769979161, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(185, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-02', 1769980078, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(186, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-02', 1769980168, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(187, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-02', 1769980173, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(188, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769981192, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(189, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769981204, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(190, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769981746, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(191, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769981933, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(192, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769982307, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(193, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769982431, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(194, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769982989, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(195, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769983068, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(196, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769983166, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(197, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-02', 1769998602, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(198, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769998609, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(199, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Sam White\" has been created successfully!', '2026-02-02', 1769998699, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(200, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769998699, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(201, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-02', 1769998732, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(202, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1769999443, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(203, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770001667, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(204, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770001672, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(205, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770002279, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(206, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770002743, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(207, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770002833, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(208, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770002882, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(209, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770002924, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(210, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770003282, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(211, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770003374, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(212, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770003436, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(213, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770003749, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(214, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770003907, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(215, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770004082, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(216, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-02', 1770004951, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(217, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-03', 1770062450, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(218, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770062450, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(219, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Daniel Twilight\' (Role: School Admin) has been deleted successfully', '2026-02-03', 1770062459, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(220, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Role', 'Role Parent has been created successfully!', '2026-02-03', 1770062933, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(221, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-03', 1770062933, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(222, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Role', 'Role Student has been created successfully!', '2026-02-03', 1770062978, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(223, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-03', 1770062978, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(224, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-03', 1770074008, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(225, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770083104, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(226, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770083448, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(227, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770083784, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(228, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Daniel Carter\' (Role: Student) has been deleted successfully', '2026-02-03', 1770084063, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(229, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Daniel Carter\" has been created successfully!', '2026-02-03', 1770084106, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(230, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770084106, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(231, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770085960, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(232, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Mary Lou\" has been created successfully!', '2026-02-03', 1770090248, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(233, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770090248, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(234, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Mary Lou\' (Role: Student) has been deleted successfully', '2026-02-03', 1770090684, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(235, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Mary  Lou\" has been created successfully!', '2026-02-03', 1770090735, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(236, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770090735, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(237, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770090749, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(238, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770091727, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(239, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770092009, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(240, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Mary  Lou\' (Role: Student) has been deleted successfully', '2026-02-03', 1770092013, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(241, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Mary Lou\" has been created successfully!', '2026-02-03', 1770092431, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(242, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770092432, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(243, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Mary Lou\' (Role: Student) has been deleted successfully', '2026-02-03', 1770093022, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(244, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Mary Lou\" has been created successfully!', '2026-02-03', 1770093073, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(245, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-03', 1770093073, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(246, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-04', 1770149239, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(247, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770149239, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(248, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-04', 1770149301, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(249, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770149330, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(250, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770150218, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(251, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770150951, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(252, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770151706, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(253, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770151886, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(254, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770152217, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(255, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770152684, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(256, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770153255, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(257, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770153261, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(258, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770153284, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(259, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770153752, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(260, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770153776, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(261, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770153892, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(262, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770154047, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(263, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-04', 1770154107, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(264, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-04', 1770154127, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(265, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-04', 1770154324, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(266, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770154336, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(267, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-04', 1770162388, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(268, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770162388, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(269, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770163831, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(270, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-04', 1770171479, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(271, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770171551, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(272, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770171628, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(273, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-04', 1770172407, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(274, 1, '27.123.137.85', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML', 'Tablet', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-04', 1770203744, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(275, 1, '27.123.137.85', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML', 'Tablet', 'View User Listing', 'User view user listing.', '2026-02-04', 1770203755, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(276, 1, '27.123.137.85', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML', 'Tablet', 'View User Listing', 'User view user listing.', '2026-02-04', 1770203773, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(277, 1, '27.123.137.85', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML', 'Tablet', 'View User Listing', 'User view user listing.', '2026-02-04', 1770203799, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(278, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-05', 1770233885, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(279, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770233890, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(280, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Daniel Carter\" updated', '2026-02-05', 1770235107, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(281, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770235107, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(282, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Daniel Carter\" updated', '2026-02-05', 1770235198, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(283, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770235198, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(284, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770235276, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(285, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770235285, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(286, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770235689, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(287, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Daniel Carter\" updated', '2026-02-05', 1770235763, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(288, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770235763, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(289, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770237892, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(290, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-05', 1770239791, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(291, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-10', 1770666022, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(292, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-10', 1770666023, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(293, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-10', 1770666030, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(294, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(295, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(296, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(297, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(298, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(299, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(300, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(301, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(302, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(303, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(304, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(305, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(306, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(307, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(308, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(309, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(310, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(311, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(312, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(313, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(314, 1, '45.117.242.226', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-10', 1770689967, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(315, 1, '45.117.242.226', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-10', 1770689991, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(316, 1, '45.117.242.226', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-10', 1770690137, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(317, 1, '45.117.242.226', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-10', 1770693847, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(320, 1, '27.123.136.105', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-10', 1770708101, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(321, 1, '27.123.136.105', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-10', 1770708102, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(322, 1, '27.123.136.105', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe23@gmail.com to piobaleicoqe2@gmail.com', '2026-02-10', 1770709412, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(323, 18, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(324, 1, '27.123.136.105', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-10', 1770712678, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(325, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-11', 1770757186, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(326, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-11', 1770757197, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(327, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-11', 1770757363, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(328, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-11', 1770757717, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(329, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Permission', 'Permission \"Update User Role\" has been created successfully!', '2026-02-11', 1770758117, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(330, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-11', 1770758117, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(331, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-11', 1770758131, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(332, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-11', 1770758145, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(333, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-11', 1770758185, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(334, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-11', 1770758193, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(335, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-11', 1770758204, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(336, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Super Admin have been updated. Total permissions: 90', '2026-02-11', 1770758238, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(337, 1, '45.117.242.230', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-11', 1770759027, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(338, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-12', 1770843009, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary');
INSERT INTO `user_log` (`user_log_id`, `user_id_fk`, `ip_aadress`, `user_agent`, `user_device`, `log_title`, `log_desc`, `log_date`, `log_time`, `log_icon`, `log_theme`) VALUES
(339, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe2@gmail.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-02-12', 1770843047, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(340, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-12', 1770850444, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(341, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-12', 1770850658, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(342, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update User Role', 'Successfully updated user role to \'Support Staff\' for user Daniel Carter', '2026-02-12', 1770858423, '<i class=\"ki-duotone ki-key-square\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(343, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update User Role', 'Successfully updated user role to \'Parent\' for user Daniel Carter', '2026-02-12', 1770859568, '<i class=\"ki-duotone ki-key-square\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(344, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-12', 1770867534, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(345, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-12', 1770867544, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(346, 1, '45.117.243.232', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe49@gmail.com to piobaleicoqe49@gmail.com for user Daniel Carter', '2026-02-12', 1770869587, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(347, 1, '45.117.243.233', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-13', 1770932413, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(348, 1, '45.117.242.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-19', 1771443162, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(349, 1, '45.117.242.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-19', 1771443167, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(350, 14, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(351, 1, '45.117.242.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe49@gmail.com to piobaleicoqe49@gmail.com for user Daniel Carter', '2026-02-19', 1771443233, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(352, 1, '45.117.242.253', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-19', 1771446065, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(353, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New School', 'School \"Nasinu Secondary School\" has been created successfully!', '2026-02-19', 1771457135, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(354, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-19', 1771457602, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(355, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-19', 1771457627, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(356, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New School', 'School \"Nasinu Secondary School 3\" has been created successfully!', '2026-02-19', 1771461291, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(357, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New School', 'School \"Nasinu Secondary School 4\" has been created successfully!', '2026-02-19', 1771462244, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(358, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New School', 'School \"Nasinu Secondary School 5\" has been created successfully!', '2026-02-19', 1771465807, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(359, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New School', 'School \"Nasinu Secondary School 6\" has been created successfully!', '2026-02-19', 1771466283, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(360, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New School', 'School \"Nasinu Secondary School 11\" has been created successfully!', '2026-02-19', 1771469910, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(361, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New School', 'School \"Nasinu Secondary School5\" has been created successfully!', '2026-02-19', 1771470256, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(362, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-19', 1771472114, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(363, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-19', 1771472544, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(364, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-24', 1771872940, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(365, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-02-24', 1771878120, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(366, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-24', 1771878244, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(367, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-24', 1771878754, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(368, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-24', 1771879280, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(369, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-02-24', 1771882513, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(370, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-24', 1771897325, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(371, 1, '27.123.136.85', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML', 'Tablet', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-24', 1771934230, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(372, 1, '27.123.136.85', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML', 'Tablet', 'View User Listing', 'User view user listing.', '2026-02-25', 1771934546, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(373, 14, NULL, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(374, 1, '45.117.242.240', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-02-25', 1771971783, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(375, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-02-25', 1771972663, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(376, 18, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-03-02', 1772397958, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(377, 18, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-03-02', 1772398150, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(378, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-03-02', 1772398169, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(379, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-03-02', 1772417855, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(380, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772417860, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(381, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Peni Ravai\' (Role: Student) has been deleted successfully', '2026-03-02', 1772418525, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(382, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Ben Ravai\' (Role: Student) has been deleted successfully', '2026-03-02', 1772418530, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(383, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Ben Ravai\' (Role: Student) has been deleted successfully', '2026-03-02', 1772418534, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(384, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Peni Ravai\' (Role: Student) has been deleted successfully', '2026-03-02', 1772418545, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(385, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772418562, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(386, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772418569, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(387, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772418574, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(388, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Ben Ravai\' (Role: Student) has been deleted successfully', '2026-03-02', 1772418579, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(389, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Ben Ravai\' (Role: Student) has been deleted successfully', '2026-03-02', 1772418584, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(390, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete User', 'User \'Peni Ravai\' (Role: Student) has been deleted successfully', '2026-03-02', 1772418593, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(391, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772418609, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(392, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772418983, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(393, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772418995, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(394, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772418999, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(395, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772419133, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(396, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772419140, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(397, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772419816, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(398, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772420183, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(399, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772420190, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(400, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Benjamin Dada\" has been created successfully!', '2026-03-02', 1772420518, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(401, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772420518, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(402, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772420861, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(403, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772420901, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(404, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772420913, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(405, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Mary Low\" has been created successfully!', '2026-03-02', 1772421169, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(406, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772421169, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(407, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772421307, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(408, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-02', 1772421400, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(409, 1, '45.117.242.246', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-03-02', 1772423922, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(410, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-03-03', 1772485180, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(411, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-03-03', 1772485180, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(412, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-03', 1772485187, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(413, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"James Smith\" has been created successfully!', '2026-03-03', 1772487700, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(414, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-03', 1772487700, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(415, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Peni Wise\" has been created successfully!', '2026-03-03', 1772488549, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(416, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-03', 1772488549, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(417, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"James Brown\" has been created successfully!', '2026-03-03', 1772488634, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(418, 1, '45.117.243.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-03', 1772488634, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(419, 1, '45.117.242.226', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-03-12', 1773258138, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(420, 1, '45.117.242.226', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-03-12', 1773258154, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(421, 1, '45.117.242.226', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-03-12', 1773258202, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(422, 1, '45.117.242.226', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-12', 1773258209, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(423, 1, '27.123.136.223', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-03-30', 1774842947, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(424, 1, '27.123.136.223', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-30', 1774842959, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(425, 1, '27.123.136.223', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-30', 1774843870, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(426, 1, '27.123.136.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-03-31', 1774943389, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(427, 1, '27.123.136.211', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-03-31', 1774943399, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(428, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778445217, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(429, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-11', 1778445229, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(430, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-11', 1778445245, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(431, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-11', 1778445327, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(432, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-11', 1778445338, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(433, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778445352, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(434, 27, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(435, 27, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(436, 27, NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(437, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Benjamin Dada\" updated', '2026-05-11', 1778445986, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(438, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778445986, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(439, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778449318, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(440, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Pio Baleicoqe\" updated', '2026-05-11', 1778452593, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(441, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778452593, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(442, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Pio Baleicoqe\" updated', '2026-05-11', 1778452624, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(443, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778452624, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(444, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe@yahoo.com to piobaleicoqe@yahoo.com for user Pio Baleicoqe', '2026-05-11', 1778452662, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(445, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe@yahoo.com to piobaleicoqe@yahoo.com for user Pio Baleicoqe', '2026-05-11', 1778452691, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(446, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe@yahoo.com to pio@baleicoqe.com for user Pio Baleicoqe', '2026-05-11', 1778452744, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(447, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from pio@baleicoqe.com to pio@baleicoqe.com for user Pio Baleicoqe', '2026-05-11', 1778454546, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(448, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778455300, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(449, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-11', 1778455336, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(450, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-11', 1778455349, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(451, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-11', 1778455357, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(452, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-11', 1778455360, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(453, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778455368, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(454, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-11', 1778455399, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(455, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778455402, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(456, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-11', 1778455796, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(457, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778455803, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(458, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-11', 1778455881, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(459, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778455889, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(460, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-11', 1778456011, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(461, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778456027, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(462, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778456041, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(463, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe@yahoo.com to pio@baleicoqe.com for user Pio Baleicoqe', '2026-05-11', 1778456069, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(464, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-11', 1778456073, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(465, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778456080, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(466, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778456097, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(467, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from pio@baleicoqe.com to pio@baleicoqe.com for user Pio Baleicoqe', '2026-05-11', 1778456401, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(468, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from pio@baleicoqe.com to pio@baleicoqe.com for user Pio Baleicoqe', '2026-05-11', 1778456450, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(469, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from pio@baleicoqe.com to pio@baleicoqe.com for user Pio Baleicoqe', '2026-05-11', 1778456645, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(470, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from pio@baleicoqe.com to piobaleicoqe@yahoo.com for user Pio Baleicoqe', '2026-05-11', 1778456705, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(471, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778456848, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(472, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Email', 'Successfully updated email from piobaleicoqe@yahoo.com to pio@baleicoqe.com for user Pio Baleicoqe', '2026-05-11', 1778456891, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(473, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-11', 1778472075, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(474, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-11', 1778472075, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(475, 1, '27.123.136.128', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-11', 1778472202, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(476, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-12', 1778535730, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(477, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-12', 1778535730, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(478, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778535730, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(479, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778535730, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(480, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from piobaleicoqe2@gmail.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778535785, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(481, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from piobaleicoqe2@gmail.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778536428, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(482, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from piobaleicoqe2@gmail.com to pio@baleicoqe.com', '2026-05-12', 1778536539, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(483, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778536668, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(484, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from pio@baleicoqe.com to pio@baleicoqe.com', '2026-05-12', 1778536685, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(485, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778536808, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(486, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from pio@baleicoqe.com to pio@baleicoqe.com', '2026-05-12', 1778536825, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(487, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from piobaleicoqe2@gmail.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778537105, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(488, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from piobaleicoqe2@gmail.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778537243, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(489, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from piobaleicoqe2@gmail.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778537326, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(490, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from piobaleicoqe2@gmail.com to pio@baleicoqe.com', '2026-05-12', 1778538073, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(491, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778538300, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(492, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from pio@baleicoqe.com to piobaleicoqe2@gmail.com', '2026-05-12', 1778538328, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(493, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from piobaleicoqe2@gmail.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778538556, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info');
INSERT INTO `user_log` (`user_log_id`, `user_id_fk`, `ip_aadress`, `user_agent`, `user_device`, `log_title`, `log_desc`, `log_date`, `log_time`, `log_icon`, `log_theme`) VALUES
(494, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from piobaleicoqe2@gmail.com to pio@baleicoqe.com', '2026-05-12', 1778538605, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(495, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778541360, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(496, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778541490, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(497, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778541582, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(498, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778542167, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(499, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778542350, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(500, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778542567, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(501, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778542749, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(502, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778542818, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(503, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778543139, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(504, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778543196, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(505, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from pio@baleicoqe.com to piobaleicoqe2@gmail.com', '2026-05-12', 1778545403, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(506, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-12', 1778545738, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(507, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-12', 1778545745, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(508, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from piobaleicoqe2@gmail.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778545774, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(509, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from piobaleicoqe2@gmail.com to pio@baleicoqe.com', '2026-05-12', 1778545840, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(510, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to piobaleicoqe2@gmail.com for user Mary Lou', '2026-05-12', 1778546087, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(511, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from pio@baleicoqe.com to piobaleicoqe2@gmail.com', '2026-05-12', 1778546137, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(512, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from piobaleicoqe2@gmail.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778546678, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(513, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from piobaleicoqe2@gmail.com to pio@baleicoqe.com', '2026-05-12', 1778546692, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(514, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email Change Requested', 'Email change requested from pio@baleicoqe.com to pio@baleicoqe.com for user Mary Lou', '2026-05-12', 1778546779, '<i class=\"ki-duotone ki-directbox-default\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span></i>', 'info'),
(515, 18, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Unknown', 'Email Changed', 'Email successfully changed from pio@baleicoqe.com to pio@baleicoqe.com', '2026-05-12', 1778547315, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(516, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778547386, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(517, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-12', 1778550676, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(518, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-12', 1778550683, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(519, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778550688, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(520, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-12', 1778551836, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(521, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-12', 1778551843, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(522, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778551847, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(523, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Sign Out All Sessions', 'Signed out all sessions for user ID 1', '2026-05-12', 1778552092, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(524, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778553776, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(525, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778554108, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(526, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Character Reference Generated', 'Character reference generated for Mary Lou', '2026-05-12', 1778559376, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(527, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778559790, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(528, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778559806, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(529, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Character Reference Generated', 'Character reference generated for James Brown', '2026-05-12', 1778559958, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(530, 1, '27.123.138.116', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778562105, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(531, 1, '27.123.138.116', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778562128, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(532, 1, '27.123.138.116', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-12', 1778562158, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(533, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778616815, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(534, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778616933, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(535, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Character Reference Generated', 'Character Reference generated for Mary Lou', '2026-05-13', 1778617087, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(536, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Recommendation Letter Generated', 'Recommendation Letter generated for Mary Lou', '2026-05-13', 1778617936, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(537, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Certificate of Enrollment Generated', 'Certificate of Enrollment generated for Mary Lou', '2026-05-13', 1778619365, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(538, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Certificate of Enrollment Generated', 'Certificate of Enrollment generated for Mary Lou', '2026-05-13', 1778619863, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(539, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Reference Deleted', 'Deleted reference: char_ref_18_20260513_081807.pdf (ID: 3)', '2026-05-13', 1778622052, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(540, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Reference Deleted', 'Deleted reference: enrollment_18_20260513_085605.pdf (ID: 5)', '2026-05-13', 1778622067, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(541, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Reference Deleted', 'Deleted reference: enrollment_18_20260513_090423.pdf (ID: 6)', '2026-05-13', 1778622074, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(542, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Certificate of Enrollment Generated', 'Certificate of Enrollment generated for Mary Lou', '2026-05-13', 1778622249, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(543, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Character Reference Generated', 'Character Reference generated for Mary Lou', '2026-05-13', 1778622806, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(544, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Recommendation Letter Generated', 'Recommendation Letter generated for Mary Lou', '2026-05-13', 1778623065, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(545, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Transcript Request Generated', 'Transcript generated for Mary Lou', '2026-05-13', 1778623687, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(546, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Conduct Certificate Generated', 'Conduct Certificate generated for Mary Lou', '2026-05-13', 1778624056, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(547, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Clearance Certificate Generated', 'Clearance Certificate generated for Mary Lou', '2026-05-13', 1778624209, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(548, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Certificate of Enrollment Generated', 'Certificate of Enrollment generated for Mary Lou', '2026-05-13', 1778626091, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(549, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Character Reference Generated', 'Character Reference generated for Mary Lou', '2026-05-13', 1778626303, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(550, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778626412, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(551, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778626425, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(552, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778626446, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(553, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778626461, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(554, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Parent Guardian Certificate Generated', 'Parent Guardian Certificate generated for Daniel Junior Carter', '2026-05-13', 1778626547, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(555, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778626646, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(556, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Pio Baleicoqe\" updated', '2026-05-13', 1778626718, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(557, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778626718, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(558, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778626734, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(559, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778627227, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(560, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778627227, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(561, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778628267, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(562, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Conduct Certificate Generated', 'Conduct Certificate generated for Mary Lou', '2026-05-13', 1778628386, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(563, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Conduct Certificate Generated', 'Conduct Certificate generated for Mary Lou', '2026-05-13', 1778628620, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(564, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Clearance Certificate Generated', 'Clearance Certificate generated for Mary Lou', '2026-05-13', 1778629518, '<i class=\"ki-duotone ki-document\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(565, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778629585, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(566, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778629599, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(567, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778629611, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(568, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Medical Record Added', 'Medical record added for user ID 1', '2026-05-13', 1778632481, '<i class=\"ki-duotone ki-heart-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(569, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Medical Record Deleted', 'Medical record ID 1 deleted for user ID 1', '2026-05-13', 1778632497, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(570, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Medical Record Deleted', 'Medical record ID 2 deleted for user ID 1', '2026-05-13', 1778632502, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(571, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Medical Record Deleted', 'Medical record ID 3 deleted for user ID 1', '2026-05-13', 1778632507, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(572, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Medical Record Added', 'Medical record added for user ID 1', '2026-05-13', 1778632615, '<i class=\"ki-duotone ki-heart-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(573, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Medical Record Updated', 'Medical record ID 4 updated for user ID 1', '2026-05-13', 1778632672, '<i class=\"ki-duotone ki-heart-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(574, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Authenticator App Enabled', '2FA via Authenticator App was enabled.', '2026-05-13', 1778635482, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(575, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778635531, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(576, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778638335, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(577, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778638371, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(578, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778638524, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(579, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778638853, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(580, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778638860, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(581, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778638864, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(582, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778638979, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(583, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login (2FA)', 'Successfully logged in via two-factor authentication (authenticator).', '2026-05-13', 1778640194, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(584, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778640206, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(585, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Email OTP Enabled', '2FA via Email OTP was enabled.', '2026-05-13', 1778640286, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(586, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778640479, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(587, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login (2FA)', 'Successfully logged in via two-factor authentication (otp_email).', '2026-05-13', 1778640584, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(588, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778640642, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(589, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778640644, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(590, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778642558, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(591, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778643106, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(592, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Notification Settings Updated', 'Email notification preferences updated.', '2026-05-13', 1778643141, '<i class=\"ki-duotone ki-notification\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(593, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login (2FA)', 'Successfully logged in via two-factor authentication (otp_email).', '2026-05-13', 1778664518, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(594, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778664547, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(595, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New School', 'School \"Rotuma High School\" has been created successfully!', '2026-05-13', 1778664989, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(596, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-13', 1778665268, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(597, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-13', 1778665272, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(598, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-13', 1778665301, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(599, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778665444, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(600, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778665495, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(601, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Password Reset Requested', 'Password reset link sent to info@baleicoqe.com', '2026-05-13', 1778666900, '<i class=\"ki-duotone ki-lock\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(602, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Password Reset Successful', 'Password was successfully reset via email link.', '2026-05-13', 1778667014, '<i class=\"ki-duotone ki-lock-3\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(603, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778667067, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(604, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778667090, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(605, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login (2FA)', 'Successfully logged in via two-factor authentication (otp_email).', '2026-05-13', 1778667611, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(606, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778667619, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(607, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-13', 1778667631, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(608, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Assistant Teacher have been updated. Total permissions: 64', '2026-05-13', 1778667798, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(609, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778667806, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(610, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778667820, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(611, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778667836, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(612, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778667846, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(613, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Authenticator App Enabled', '2FA via Authenticator App was enabled.', '2026-05-13', 1778668585, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(614, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Notification Settings Updated', 'Email notification preferences updated.', '2026-05-13', 1778669860, '<i class=\"ki-duotone ki-notification\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(615, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Notification Settings Updated', 'Email notification preferences updated.', '2026-05-13', 1778669875, '<i class=\"ki-duotone ki-notification\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(616, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Notification Settings Updated', 'Email notification preferences updated.', '2026-05-13', 1778670142, '<i class=\"ki-duotone ki-notification\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(617, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670176, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(618, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670235, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(619, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Medical Record Added', 'Medical record added for user ID 12', '2026-05-13', 1778670252, '<i class=\"ki-duotone ki-heart-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(620, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670296, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(621, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670361, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(622, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778670416, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(623, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login (2FA)', 'Successfully logged in via two-factor authentication (authenticator).', '2026-05-13', 1778670443, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(624, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670451, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(625, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670456, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(626, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670520, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(627, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670540, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(628, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670648, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(629, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778670683, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(630, 12, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-13', 1778670717, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(631, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-13', 1778671137, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(632, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778671396, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(633, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-13', 1778672705, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(634, 1, '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-14', 1778674057, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(635, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-26', 1779741062, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(636, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-26', 1779741070, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(637, 1, '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-26', 1779741127, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(638, 1, '27.123.136.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-26', 1779748619, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(639, 1, '27.123.136.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-26', 1779752170, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(640, 1, '27.123.136.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 12 updated for Mary Lou', '2026-05-26', 1779752376, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(641, 1, '27.123.136.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Deleted', 'Admission ID 8 deleted for Benjamin Dada', '2026-05-26', 1779754116, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(642, 1, '27.123.136.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Added', 'New admission for Sam White', '2026-05-26', 1779754757, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(643, 1, '27.123.136.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Added', 'Enrolment created for James Smith | Year: 2026 Term: 2', '2026-05-26', 1779756947, '<i class=\"ki-duotone ki-abstract-28\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(644, 1, '27.123.136.202', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Updated', 'Enrolment ID 8 updated.', '2026-05-26', 1779757112, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(645, 1, '27.123.137.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-26', 1779765512, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary');
INSERT INTO `user_log` (`user_log_id`, `user_id_fk`, `ip_aadress`, `user_agent`, `user_device`, `log_title`, `log_desc`, `log_date`, `log_time`, `log_icon`, `log_theme`) VALUES
(646, 1, '27.123.137.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 13 updated for Sam White', '2026-05-26', 1779767211, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(647, 1, '27.123.137.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 11 updated for James Brown', '2026-05-26', 1779768921, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(648, 1, '27.123.137.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Updated', 'Teaching subjects updated for admission ID 11', '2026-05-26', 1779770090, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(649, 1, '27.123.137.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 11 updated for James Brown', '2026-05-26', 1779770100, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(650, 1, '27.123.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-26', 1779780113, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(651, 1, '27.123.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Updated', 'Teaching subjects updated for admission ID 13', '2026-05-26', 1779780195, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(652, 1, '27.123.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Classroom Created', 'Classroom \"Year 9A 2026\" created for year 2026', '2026-05-26', 1779781323, '<i class=\"ki-duotone ki-element-7\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(653, 1, '27.123.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Classroom Created', 'Classroom \"9A 2026\" created for year 2026', '2026-05-26', 1779781420, '<i class=\"ki-duotone ki-element-7\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(654, 1, '27.123.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Classroom Updated', 'Classroom \"Year 9A 2026\" updated (ID: 1)', '2026-05-26', 1779781471, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(655, 1, '27.123.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Classroom Deleted', 'Classroom \"9A 2026\" deleted (ID: 2)', '2026-05-26', 1779781481, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(656, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-27', 1779826131, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(657, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Mereisi Talei\" has been created successfully!', '2026-05-27', 1779826374, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(658, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779826374, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(659, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Peter Guss\" has been created successfully!', '2026-05-27', 1779826463, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(660, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779826463, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(661, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779826580, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(662, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Classroom Created', 'Classroom \"Year 9A 2026\" created for year 2026', '2026-05-27', 1779826758, '<i class=\"ki-duotone ki-element-7\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(663, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779832727, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(664, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779833579, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(665, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779833773, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(666, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"Peter Guss\" assigned as Class Teacher for classroom ID 3', '2026-05-27', 1779833807, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(667, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"Mereisi Talei\" assigned as Assistant Class Teacher for classroom ID 3', '2026-05-27', 1779833818, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(668, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Status Updated', 'Class Teacher (ID: 1) set to Inactive', '2026-05-27', 1779833893, '<i class=\"ki-duotone ki-switch\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(669, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"Peter Guss\" assigned as Class Teacher for classroom ID 3', '2026-05-27', 1779833902, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(670, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"Sam White\" assigned as Class Teacher for classroom ID 3', '2026-05-27', 1779833912, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(671, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779836068, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(672, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Shane Davis\" has been created successfully!', '2026-05-27', 1779836663, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(673, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779836663, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(674, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Classroom Deleted', 'Classroom \"Year 9A 2026\" deleted (ID: 1)', '2026-05-27', 1779836697, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(675, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Maeri Luikali\" has been created successfully!', '2026-05-27', 1779838403, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(676, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779838403, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(677, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"Maeri Luikali\" assigned as Class Captain for classroom ID 3', '2026-05-27', 1779838531, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(678, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"James Smith\" assigned as Assistant Class Captain for classroom ID 3', '2026-05-27', 1779838556, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(679, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779838632, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(680, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Edit User', 'User \"Maeri Luikali\" updated', '2026-05-27', 1779840157, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(681, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779840158, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(682, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779842282, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(683, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779842946, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(684, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779844678, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(685, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Subject Teacher Assigned', '\"James Brown\" assigned to teach \"Year 9A\" subject ID 30', '2026-05-27', 1779849378, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(686, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Subject Teacher Assigned', '\"Mereisi Talei\" assigned to teach \"Year 9A\" subject ID 27', '2026-05-27', 1779849695, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(687, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Subject Teacher Assigned', '\"Peter Guss\" assigned to teach \"Year 9A\" subject ID 33', '2026-05-27', 1779849776, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(688, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Subject Teacher Assigned', '\"Sam White\" assigned to teach \"Year 9A\" subject ID 26', '2026-05-27', 1779849788, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(689, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779851935, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(690, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779851962, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(691, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779852002, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(692, 12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login (2FA)', 'Successfully logged in via two-factor authentication (authenticator).', '2026-05-27', 1779853735, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(693, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-27', 1779853811, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(694, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-27', 1779853898, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(695, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-27', 1779862961, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(696, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-27', 1779862966, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(697, 12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-27', 1779862976, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(698, 12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login (2FA)', 'Successfully logged in via two-factor authentication (authenticator).', '2026-05-27', 1779863408, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(699, 12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-27', 1779863432, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(700, 12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', '2FA Disabled', 'Two-factor authentication was disabled.', '2026-05-27', 1779863446, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(701, 12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-28', 1779908547, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(702, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-28', 1779908560, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(703, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-28', 1779910003, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(704, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-28', 1779910023, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(705, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-28', 1779910858, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(706, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-28', 1779910862, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(707, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779910865, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(708, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New Permission', 'Permission \"Add Student Daily Attendance\" has been created successfully!', '2026-05-28', 1779911057, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(709, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911057, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(710, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New Permission', 'Permission \"View Student Daily Attendance\" has been created successfully!', '2026-05-28', 1779911107, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(711, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911107, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(712, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-28', 1779911113, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(713, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Update Role Permissions', 'Permissions for role Super Admin have been updated. Total permissions: 92', '2026-05-28', 1779911128, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(714, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911245, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(715, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Edit Permission', 'Permission \"Add Student Daily Attendance\" has been updated successfully!', '2026-05-28', 1779911275, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(716, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911275, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(717, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911280, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(718, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911292, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(719, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Edit Permission', 'Permission \"Add Student Daily Attendance\" has been updated successfully!', '2026-05-28', 1779911307, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(720, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911307, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(721, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911339, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(722, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779911993, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(723, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Jenifer  Pareti\" has been created successfully!', '2026-05-28', 1779913023, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(724, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-28', 1779913023, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(725, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Maleli Tora\" has been created successfully!', '2026-05-28', 1779913079, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(726, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-28', 1779913079, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(727, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Ilisabeta Matata\" has been created successfully!', '2026-05-28', 1779913118, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(728, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-28', 1779913119, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(729, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Edwin Smith\" has been created successfully!', '2026-05-28', 1779913168, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(730, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-28', 1779913169, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(731, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Sherine Kumar\" has been created successfully!', '2026-05-28', 1779913233, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(732, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-05-28', 1779913233, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(733, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-28', 1779916396, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(734, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-28', 1779916402, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(735, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-28', 1779916437, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(736, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779916460, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(737, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-28', 1779916462, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(738, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Assistant Teacher have been updated. Total permissions: 66', '2026-05-28', 1779916475, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(739, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779922607, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(740, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Permission', 'Permission \"Add Student Subject\" has been created successfully!', '2026-05-28', 1779922648, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(741, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779922648, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(742, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Permission', 'Permission \"View Student Subject\" has been created successfully!', '2026-05-28', 1779922685, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(743, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779922685, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(744, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-05-28', 1779922690, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(745, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Assistant Teacher have been updated. Total permissions: 68', '2026-05-28', 1779922702, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(746, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-05-28', 1779924554, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(747, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Delete Permission', 'Permission \'Test Dashboard Today\' has been deleted', '2026-05-28', 1779924564, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(748, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-05-28', 1779926526, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(749, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-28', 1779926529, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(750, 33, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-28', 1779939997, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(751, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-29', 1779997518, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(752, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-30', 1780084202, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(753, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-30', 1780084272, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(754, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-30', 1780084272, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(755, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-05-31', 1780173059, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(756, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780256786, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(757, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-01', 1780258188, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(758, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 12 updated for Mary Lou', '2026-06-01', 1780261651, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(759, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Bese Takina\" has been created successfully!', '2026-06-01', 1780261886, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(760, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-01', 1780261886, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(761, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Peter Toganivalu\" has been created successfully!', '2026-06-01', 1780264419, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(762, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-01', 1780264419, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(763, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-01', 1780267459, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(764, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-01', 1780268598, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(765, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-01', 1780271341, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(766, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-01', 1780271509, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(767, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Edwin Smith\" updated', '2026-06-01', 1780271525, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(768, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-01', 1780271525, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(769, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-06-01', 1780274831, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(770, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275793, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(771, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275793, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(772, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275840, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(773, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275842, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(774, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275842, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(775, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275846, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(776, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275846, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(777, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275848, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(778, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275848, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(779, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275849, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(780, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275849, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(781, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275851, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(782, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780275851, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(783, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780277879, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(784, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780277879, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(785, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780277882, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(786, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780277882, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(787, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780277934, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(788, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780277934, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(789, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780278227, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(790, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780278227, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(791, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-01', 1780278615, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(792, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780278618, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(793, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-01', 1780279127, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(794, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-01', 1780279276, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(795, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279279, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(796, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279280, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(797, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279282, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(798, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279282, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(799, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279293, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(800, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279293, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(801, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279295, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(802, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279295, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(803, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279313, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(804, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-01', 1780279678, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(805, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780279681, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(806, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780286555, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary');
INSERT INTO `user_log` (`user_log_id`, `user_id_fk`, `ip_aadress`, `user_agent`, `user_device`, `log_title`, `log_desc`, `log_date`, `log_time`, `log_icon`, `log_theme`) VALUES
(807, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780287002, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(808, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-01', 1780287227, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(809, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780287231, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(810, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-01', 1780287493, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(811, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780287496, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(812, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-01', 1780287508, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(813, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780287603, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(814, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-01', 1780288031, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(815, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-01', 1780288034, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(816, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780333151, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(817, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780333151, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(818, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-02', 1780333156, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(819, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780333334, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(820, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-02', 1780333346, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(821, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780333523, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(822, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-02', 1780333527, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(823, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780333532, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(824, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-02', 1780333539, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(825, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780333542, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(826, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-02', 1780333546, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(827, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780333558, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(828, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-02', 1780333564, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(829, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780333566, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(830, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Mandy Lopez\" has been created successfully!', '2026-06-02', 1780342192, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(831, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780342192, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(832, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780342233, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(833, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Updated', 'Teaching subjects updated for admission ID 26', '2026-06-02', 1780342402, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(834, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Updated', 'Teaching subjects updated for admission ID 26', '2026-06-02', 1780342684, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(835, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Updated', 'Teaching subjects updated for admission ID 26', '2026-06-02', 1780342730, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(836, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '5 subject(s) added for admission ID 26', '2026-06-02', 1780343239, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(837, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '0 subject(s) added for admission ID 26', '2026-06-02', 1780343252, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(838, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '4 subject(s) added for admission ID 26', '2026-06-02', 1780343651, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(839, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '2 subject(s) added for admission ID 26', '2026-06-02', 1780343721, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(840, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '1 subject(s) added for admission ID 26', '2026-06-02', 1780343727, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(841, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 15 updated for Peter Guss', '2026-06-02', 1780344794, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(842, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Added', 'New admission for Peter Guss', '2026-06-02', 1780346508, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(843, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 19 updated for Jenifer  Pareti', '2026-06-02', 1780347986, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(844, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '5 subject(s) added for admission ID 25', '2026-06-02', 1780348248, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(845, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 25 updated for Peter Toganivalu', '2026-06-02', 1780348309, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(846, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 10 updated for James Smith', '2026-06-02', 1780348437, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(847, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 10 updated for James Smith', '2026-06-02', 1780348848, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(848, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 10 updated for James Smith', '2026-06-02', 1780348869, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(849, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780349970, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(850, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780350099, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(851, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780350099, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(852, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Updated', 'Enrolment ID 9 updated.', '2026-06-02', 1780350170, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(853, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780355458, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(854, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780355893, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(855, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780355992, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(856, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780356015, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(857, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Benjamin Dada\" updated', '2026-06-02', 1780356057, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(858, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780356057, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(859, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit User', 'User \"Benjamin Dada\" updated', '2026-06-02', 1780356115, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(860, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780356115, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(861, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780356152, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(862, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780358341, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(863, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '5 subject(s) added for admission ID 24', '2026-06-02', 1780361137, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(864, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '4 subject(s) added for admission ID 14', '2026-06-02', 1780363487, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(865, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Teaching Subjects Added', '2 subject(s) added for admission ID 14', '2026-06-02', 1780364038, '<i class=\"ki-duotone ki-book\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(866, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780372488, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(867, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780372488, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(868, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780372492, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(869, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-02', 1780372606, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(870, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-02', 1780372614, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(871, 43, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-02', 1780372771, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(872, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-03', 1780430234, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(873, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-03', 1780431246, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(874, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-03', 1780431277, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(875, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-03', 1780432714, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(876, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-03', 1780432718, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(877, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-03', 1780432775, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(878, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-03', 1780432778, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(879, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Enrolment Added', 'Enrolment created for Shane Davis | Year: 2026 Term: 2', '2026-06-03', 1780435522, '<i class=\"ki-duotone ki-abstract-28\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(880, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-03', 1780440751, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(881, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for Benjamin Dada', '2026-06-03', 1780440806, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(882, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-03', 1780473325, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(883, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-03', 1780479865, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning');

-- --------------------------------------------------------

--
-- Table structure for table `user_medical`
--

DROP TABLE IF EXISTS `user_medical`;
CREATE TABLE IF NOT EXISTS `user_medical` (
  `medical_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `medical_condition` varchar(500) DEFAULT NULL,
  `allergies` varchar(500) DEFAULT NULL,
  `medications` varchar(500) DEFAULT NULL,
  `emergency_contact_name` varchar(150) DEFAULT NULL,
  `emergency_contact_phone` varchar(60) DEFAULT NULL,
  `emergency_contact_relation` varchar(60) DEFAULT NULL,
  `doctor_name` varchar(150) DEFAULT NULL,
  `doctor_phone` varchar(60) DEFAULT NULL,
  `doctor_address` varchar(300) DEFAULT NULL,
  `notes` text,
  `medical_date` date DEFAULT NULL,
  `medical_time` int DEFAULT NULL,
  `medical_status` varchar(30) DEFAULT 'Active',
  PRIMARY KEY (`medical_id`),
  KEY `fk_user_medical_user` (`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_medical`
--

INSERT INTO `user_medical` (`medical_id`, `user_id_fk`, `blood_type`, `medical_condition`, `allergies`, `medications`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `doctor_name`, `doctor_phone`, `doctor_address`, `notes`, `medical_date`, `medical_time`, `medical_status`) VALUES
(4, 1, 'A+', 'None', 'None', 'None', 'James Sweet', '8907656', 'Friend', 'Mathew Carter', '7890675', 'Bula Medical Clinic', 'Student is just a little overweight and only allowed to carry light duties.', '2026-05-13', 1778632615, 'Active'),
(5, 12, '', '', '', '', '', '', '', '', '', '', '', '2026-05-13', 1778670252, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_medical_files`
--

DROP TABLE IF EXISTS `user_medical_files`;
CREATE TABLE IF NOT EXISTS `user_medical_files` (
  `file_id` int NOT NULL AUTO_INCREMENT,
  `medical_id_fk` int NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_original_name` varchar(255) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `file_date` date DEFAULT NULL,
  `file_time` int DEFAULT NULL,
  PRIMARY KEY (`file_id`),
  KEY `fk_medical_files_medical` (`medical_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_medical_files`
--

INSERT INTO `user_medical_files` (`file_id`, `medical_id_fk`, `file_name`, `file_original_name`, `file_type`, `file_size`, `file_date`, `file_time`) VALUES
(5, 4, 'medical_4_1778632672_3956.pdf', 'Change management.pdf', 'application/pdf', 5004942, '2026-05-13', 1778632672),
(6, 4, 'medical_4_1778632672_9515.docx', 'CONSENT TO PRINT BIRTH CERTIFICATE.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 12559, '2026-05-13', 1778632672),
(7, 4, 'medical_4_1778632672_7021.png', 'coat-of-arms.png', 'image/png', 76487, '2026-05-13', 1778632672);

-- --------------------------------------------------------

--
-- Table structure for table `user_notification`
--

DROP TABLE IF EXISTS `user_notification`;
CREATE TABLE IF NOT EXISTS `user_notification` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `notif_dashboard` tinyint(1) DEFAULT '1',
  `notif_rbac` tinyint(1) DEFAULT '1',
  `notif_user` tinyint(1) DEFAULT '1',
  `notif_school` tinyint(1) DEFAULT '1',
  `notif_admission` tinyint(1) DEFAULT '1',
  `notif_enrolment` tinyint(1) DEFAULT '1',
  `notif_classroom` tinyint(1) DEFAULT '1',
  `notif_exam` tinyint(1) DEFAULT '1',
  `notif_conduct` tinyint(1) DEFAULT '1',
  `notif_timetable` tinyint(1) DEFAULT '1',
  `notif_event` tinyint(1) DEFAULT '1',
  `notif_communication` tinyint(1) DEFAULT '1',
  `notif_security` tinyint(1) DEFAULT '1',
  `notif_medical` tinyint(1) DEFAULT '1',
  `notif_reference` tinyint(1) DEFAULT '1',
  `updated_date` date DEFAULT NULL,
  `updated_time` int DEFAULT NULL,
  PRIMARY KEY (`notification_id`),
  UNIQUE KEY `unique_user_notification` (`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_notification`
--

INSERT INTO `user_notification` (`notification_id`, `user_id_fk`, `notif_dashboard`, `notif_rbac`, `notif_user`, `notif_school`, `notif_admission`, `notif_enrolment`, `notif_classroom`, `notif_exam`, `notif_conduct`, `notif_timetable`, `notif_event`, `notif_communication`, `notif_security`, `notif_medical`, `notif_reference`, `updated_date`, `updated_time`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2026-05-13', 1778643141),
(2, 27, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2026-05-13', 1778642561),
(3, 18, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2026-05-13', 1778664557),
(5, 12, 1, 1, 1, 1, 1, 0, 1, 1, 1, 0, 0, 1, 1, 0, 1, '2026-05-13', 1778670142);

-- --------------------------------------------------------

--
-- Table structure for table `user_password`
--

DROP TABLE IF EXISTS `user_password`;
CREATE TABLE IF NOT EXISTS `user_password` (
  `user_pass_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `password` varchar(500) NOT NULL,
  `date_created` date NOT NULL,
  `time_created` int NOT NULL,
  `password_status` varchar(60) NOT NULL,
  PRIMARY KEY (`user_pass_id`),
  KEY `user_password_ibfk_1` (`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_password`
--

INSERT INTO `user_password` (`user_pass_id`, `user_id_fk`, `password`, `date_created`, `time_created`, `password_status`) VALUES
(6, 11, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '2026-01-14', 1768363922, 'Active'),
(8, 14, '$2y$10$d/aF1jFnG2TRyhWfDQImROWxZrY/DJ/AXt49s3cVaRo0DhP0dG6Le', '2026-02-03', 1770084104, 'Active'),
(12, 18, '$2y$10$hdlefS6oQZ7iDk8twzuYKOxUGFfHieIkx4bPJgtuwg9gFIg4wF29u', '2026-02-03', 1770093072, 'Active'),
(13, 32, '$2y$10$f4Q9ra23X8SpDnD7lB47GurTJJleQH9yrs4Lzgpf5xTYaSsf5LCQK', '2026-03-03', 1772488632, 'Active'),
(14, 12, '', '0000-00-00', 0, ''),
(15, 33, '$2y$10$4/Ysb8wlqON5aM4AhyvJYOxNGokU30.gNFuTbN8E0GukOOSlMXr2m', '2026-05-27', 1779826374, 'Active'),
(16, 34, '$2y$10$pinrhpgBGHoNXvsPQ149JOxRe/o/0usx5o.37S5dX480jRR6ov78K', '2026-05-27', 1779826463, 'Active'),
(17, 43, '$2y$10$vVZ9T3LIm0HshoGWmfCttO25EDuuSrNte36M73v4cs.0tCU4jROb.', '2026-06-01', 1780261886, 'Active'),
(18, 44, '$2y$10$B6UiXsh3mj1L2TIYymYkweAikthkKs3g7UpfqlWZXN1kQHw/SHLEq', '2026-06-01', 1780264419, 'Active'),
(19, 45, '$2y$10$a9UJNgUtjYk57LCNTl6KDueXsUmOUW9WgQavhu8j5hoCzTZIwYrc6', '2026-06-02', 1780342192, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE IF NOT EXISTS `user_role` (
  `user_role_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `role_id_fk` int NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `user_role_status` varchar(60) NOT NULL,
  PRIMARY KEY (`user_role_id`),
  KEY `fk_user_role_users` (`user_id_fk`),
  KEY `role_id_fk` (`role_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_role_id`, `user_id_fk`, `role_id_fk`, `created_date`, `updated_date`, `user_role_status`) VALUES
(10, 12, 5, NULL, NULL, 'Active'),
(16, 18, 7, NULL, NULL, 'Active'),
(19, 14, 7, NULL, '2026-02-12 13:07:03', 'Non Active'),
(20, 14, 8, '2026-02-12 13:07:57', '2026-02-12 13:26:08', 'Non Active'),
(21, 14, 6, NULL, NULL, 'Active'),
(23, 20, 7, '2026-03-10 15:06:29', NULL, 'Active'),
(31, 28, 6, NULL, NULL, 'Active'),
(32, 29, 6, NULL, NULL, 'Active'),
(33, 30, 7, NULL, NULL, 'Active'),
(34, 31, 6, NULL, NULL, 'Active'),
(35, 32, 5, NULL, NULL, 'Active'),
(38, 1, 1, NULL, NULL, 'Active'),
(39, 11, 2, NULL, NULL, 'Active'),
(40, 33, 5, NULL, NULL, 'Active'),
(41, 34, 5, NULL, NULL, 'Active'),
(42, 35, 7, '2026-05-27 10:23:15', '2026-05-27 10:23:15', 'Active'),
(43, 36, 7, '2026-05-27 11:04:23', '2026-05-27 11:04:23', 'Active'),
(45, 37, 7, NULL, '2026-05-27 12:02:37', 'Active'),
(46, 38, 7, '2026-05-28 08:17:03', '2026-05-28 08:17:03', 'Active'),
(47, 39, 7, '2026-05-28 08:17:59', '2026-05-28 08:17:59', 'Active'),
(48, 40, 7, '2026-05-28 08:18:38', '2026-05-28 08:18:38', 'Active'),
(50, 42, 7, '2026-05-28 08:20:33', '2026-05-28 08:20:33', 'Active'),
(51, 43, 5, '2026-06-01 09:11:26', '2026-06-01 09:11:26', 'Active'),
(52, 44, 3, '2026-06-01 09:53:39', '2026-06-01 09:53:39', 'Active'),
(53, 41, 7, NULL, '2026-06-01 11:52:05', 'Active'),
(54, 45, 5, '2026-06-02 07:29:52', '2026-06-02 07:29:52', 'Active'),
(56, 27, 7, NULL, '2026-06-02 11:21:55', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_session`
--

DROP TABLE IF EXISTS `user_session`;
CREATE TABLE IF NOT EXISTS `user_session` (
  `session_id` int NOT NULL AUTO_INCREMENT,
  `user_id_fk` int NOT NULL,
  `session_token` varchar(64) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `device_type` varchar(60) DEFAULT NULL,
  `device_os` varchar(60) DEFAULT NULL,
  `browser` varchar(60) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `login_date` date DEFAULT NULL,
  `login_time` int DEFAULT NULL,
  `last_active` int DEFAULT NULL,
  `session_status` enum('Active','Expired','Signed Out') DEFAULT 'Active',
  PRIMARY KEY (`session_id`),
  KEY `fk_user_session_user` (`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_session`
--

INSERT INTO `user_session` (`session_id`, `user_id_fk`, `session_token`, `ip_address`, `user_agent`, `device_type`, `device_os`, `browser`, `country`, `city`, `login_date`, `login_time`, `last_active`, `session_status`) VALUES
(1, 1, 'be6132e3efb7599f1dbab488af1bf15d249e615a42c8b9b436011725194e9170', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-12', 1778551843, 1778551843, 'Active'),
(2, 1, '00d22170e9acdf59e3550af038495aabe3b269f483deec085fde0c2e096adef2', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778616815, 1778616815, 'Signed Out'),
(3, 1, '2ceca14fef08e8e844a025bf83e8bfe989aebddf656922104d5431d172498faa', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778627228, 1778627228, 'Active'),
(4, 1, 'a6c24d983799ca1b011b121c96079f0b1695b52e6a25f9e3b2e801da96346f6f', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778627228, 1778627228, 'Signed Out'),
(5, 1, 'c0fa9fd7c9bc5608f7933845e07ec3f80cc3d324abb952de924d4699748307cc', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778635880, 1778635880, 'Active'),
(6, 1, 'fc45c3d61e269fe56487e9424a518ddc90e01a95e2174fa7ab54a456345645a3', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778636119, 1778636119, 'Active'),
(7, 1, 'b498169baa749a8a3c6d5f090af2ac51161ab8c5da46cb46a3f07e43b7d3eb2f', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778636150, 1778636150, 'Active'),
(8, 1, 'db7feb86f3aa5ebc51e15ddff6626c47cf9d7146703e97c075208447abf5674f', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778638335, 1778638335, 'Active'),
(9, 1, 'ba6f64e3a3edfea501b728251e6a3783acbcab6a5fd3cf911bec1b0b1f76fa81', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778638371, 1778638371, 'Active'),
(10, 1, '461f7ae3c0c4be97eb36cd64ae672aeee03a7c6148239773744086880fe86da5', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778638524, 1778638524, 'Active'),
(11, 1, '46b65c8fa9d6657f1ef9dc66a69aa742ff5ae64133efb4194692440b5098d38c', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778638853, 1778638853, 'Signed Out'),
(12, 1, 'c7947e373cdd12ba78fd10dc1d4827b1f9a54f4e0ab61e66f7c66c39f80a0796', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778638864, 1778638864, 'Signed Out'),
(13, 1, '9f0b7ea326ae91479c259bcae546461b82126263b13e2c17f07cd01e90fc18f9', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778639023, 1778639023, 'Active'),
(14, 1, '1d26d9aedb4ff3214ef554aa4cccf30f1dbe3ffd1c6525bbe0fccb09a0f08814', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778640194, 1778640194, 'Signed Out'),
(15, 1, '5848c2e9448e408cd07040cea13ed98845328ba7287a63f8e6ac496547d7cda7', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778640584, 1778640584, 'Active'),
(16, 1, '9055ee218d08bb528ce47d9bee693c55ef5b327118ab2bab161aec4f95a3db2d', '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778664518, 1778664518, 'Signed Out'),
(17, 12, '4c2c7dd8e4c9e307a95fd0957289b2002b9dc2ea2199ca99f6c5c5273718ccc7', '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778667067, 1778667067, 'Signed Out'),
(18, 1, '52d8040f09f88b93c4cfcd483d658b353e5ac30365812387fb7161add5ded1f9', '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778667611, 1778667611, 'Signed Out'),
(19, 12, '569718864d4f2e40aa9af1473af6ea208ccec501f4ca47fdbd158caa0e852d64', '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778667820, 1778667820, 'Signed Out'),
(20, 12, '1451240c404ce0dc8aacc661fc48e9dd1356bc152ad6f002305c5874369d4340', '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778670443, 1778670443, 'Signed Out'),
(21, 1, 'f516184cf76c0cc6f83d30ec9852cddf62cfaf3a8dde5ce79ee787d04dc4655c', '27.123.137.23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-13', 1778671137, 1778671137, 'Active'),
(22, 1, '8003c04def4e395b4c1922de3bed9d5361a8b3affba4e6458f7f3a129bd1773d', '103.1.182.214', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-26', 1779741062, 1779741062, 'Active'),
(23, 1, '4ada7f99b0ab0f47724858c72f748cb0be945d2cc6f5894aba78d77487c11e0b', '27.123.137.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-26', 1779765512, 1779765512, 'Active'),
(24, 1, 'b86caadeb4dfa69bff41ae880a9381fa97fc7e146508588a805302d1d3fec18a', '27.123.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Fiji', 'Suva', '2026-05-26', 1779780113, 1779780113, 'Active'),
(25, 1, 'f87b41766860e099a38b5b87e8fb14c30bc4bfc7236895d841a8aae1389df64b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-27', 1779826131, 1779826131, 'Signed Out'),
(26, 12, '2ccbaf2705370a27c587bb17b421a4086129974c2bb32e92cf4be3bf66b12b5d', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-05-27', 1779853735, 1779853735, 'Signed Out'),
(27, 33, 'dbce6f183a33a490fb319a4a3a91adf669363df96148f91378bfcc182aa579d6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-27', 1779853898, 1779853898, 'Signed Out'),
(28, 33, 'cd4e3495b2022c9fee4303528c8647aff6fac6c02fd0b0020dca83b6c596e2b9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-27', 1779862966, 1779862966, 'Active'),
(29, 12, '2b665132c6a5a1542c1bdba6702a09b19355f050c0e7d07363e8a1b89c7635ec', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-05-27', 1779863408, 1779863408, 'Active'),
(30, 12, 'f2ff77029fe2062feb102ae4324ccf51b084fa0362ce5419919eb9a9ec49c348', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-05-28', 1779908547, 1779908547, 'Active'),
(31, 33, '41b3cc2db8df28773e64342deef68070cba67596d21d1331e5e2a1facfd34276', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-28', 1779908560, 1779908560, 'Signed Out'),
(32, 1, '045d39aae761023c832a6278a443ed0cdab95081b51ece25523fa3653de94bad', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-28', 1779910862, 1779910862, 'Signed Out'),
(33, 33, 'a923191504237e2f54b1df52bee5e96232949571c80c8986eabdc8fb0c9714bb', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-28', 1779916402, 1779916402, 'Signed Out'),
(34, 1, '744298df67488154b946e6b8f3c3c1e0497dbcc9691f9fba955f8adde587eb50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-05-28', 1779916437, 1779916437, 'Active'),
(35, 1, '737e8a4b872c022881b012143e1ea8c23047a7b230b95b6f6d26f151811a8d06', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-28', 1779926529, 1779926529, 'Active'),
(36, 33, '464e08252a3fcf4d8456b75b2c696c29d942ac152e2ac72fbdc3da962843b349', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-05-28', 1779939997, 1779939997, 'Active'),
(37, 1, 'e57cea8648bbfae69cf9ef92475806f4daadf16c3a11e03ca1ad14972194e6cd', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-05-29', 1779997518, 1779997518, 'Active'),
(38, 1, '5e1e56c50ee8931c45eb75807e3957e453916e5a2b44d1b9b136d2d62d3f6a86', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-05-30', 1780084202, 1780084202, 'Active'),
(39, 1, 'b25839c4aa555c22662eb67a0f568d79952bbf4a6aa8d2211a6ff8899d748ffe', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-30', 1780084272, 1780084272, 'Active'),
(40, 1, 'c613a31be5a5f08d8111be73dee9c8242a9a25180d55e0c092ac4df0821cadb0', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-30', 1780084272, 1780084272, 'Active'),
(41, 1, 'c1ea99934f9b179640ec82470910499f7ce00a32cf512a2803b22fdd7e4dbc45', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-05-31', 1780173059, 1780173059, 'Active'),
(42, 1, '23ccea7a06f42f6a3945e48facdb66d3435dd6b96f5076fdf0c1d171281312d3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-01', 1780256786, 1780256786, 'Active'),
(43, 33, 'af248b73c3d26d3cdbbe56193f24f00ece0efc21c9c525b11e170adff0540c2f', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780278227, 1780278227, 'Active'),
(44, 33, '9adb00cf813cf38641400e9cca7abb402244a4a2cd0308fb5cd894841d76dcfb', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780278227, 1780278227, 'Signed Out'),
(45, 33, '5cdad9f73cfedd7a2bf42369b680afd5c39ecb4467421c5ff219f3681e6b5ff6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780278618, 1780278618, 'Signed Out'),
(46, 33, '4ed37b5d4cbb0aad613bbad468c4a539ae4abbd4c11bd8dd8b34ea53cbb0f6ae', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780279313, 1780279313, 'Signed Out'),
(47, 33, 'e77eac71c9aeb2331c5fdc53da117d1d4727b407de6b660db49f65cbed91bf69', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780279681, 1780279681, 'Active'),
(48, 1, '60fdf561a9eb6eadb61c39dee85524f74312f59214ff29134244d771a0fbda08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-01', 1780286555, 1780286555, 'Active'),
(49, 33, '9c70f04a1d2e4d968aee5b9dfcb112dc67559983319761cd09b737dd28f65536', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780287002, 1780287002, 'Signed Out'),
(50, 33, 'a39a28e4eabf13a202e7d62de3f9802bede12ce9fc45ecda7e36c121c42c526c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780287231, 1780287231, 'Signed Out'),
(51, 33, 'db91ea003912c60d8b7633741519bbb1a2ec9b956794b15fa58af04550f1a48b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780287496, 1780287496, 'Signed Out'),
(52, 33, '647c63f60a36e4912db2fac07bd92bf8de92ec3375792c0f3cf875db7dab71a9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780287603, 1780287603, 'Signed Out'),
(53, 33, 'f2aabe90044beee202479105dfad6431296fd3a077b7df3c8d7d5518be0acf95', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-01', 1780288034, 1780288034, 'Active'),
(54, 1, 'edd24d9752d291d0e0aa4f486e5b1a446fe14e1f52f61772d55d1fd04b775f6b', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780333151, 1780333151, 'Signed Out'),
(55, 1, '497e55559f06989ce09a2ba86ae45764c584f7a0084536c4deb98612af7683ec', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780333151, 1780333151, 'Active'),
(56, 1, '9229a7d95884f69b473c503c8d678fc8f8e1073c452978350c38fc6395961db0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780333334, 1780333334, 'Signed Out'),
(57, 1, 'f624d2ea3d969a5d67c8390f298cd9b471f9a208808dd97679bc3ef10ad4ea67', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780333523, 1780333523, 'Signed Out'),
(58, 1, 'ba9ea7db776d9a3965125d23b38903e9d16900694402432a48057c58deddb420', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780333532, 1780333532, 'Signed Out'),
(59, 1, '07b27fab2f10bac15cfcaf2d6964e419223a0d1516c8753f863693ce0e70fb4d', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780333542, 1780333542, 'Signed Out'),
(60, 1, 'ce5d709445814993c1bb7a641254229b38d006fb3fe941c43ed0590d712bb371', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780333558, 1780333558, 'Signed Out'),
(61, 1, '4909a55ec2a74f2cd2c3c5d7c3ef819a1df6f42ee4ca4567fdfb17a88b79829d', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780333566, 1780333566, 'Active'),
(62, 33, '8ccc96c8bb843dc5ed8cb6cdb2afafcdac05434fad2c61497fc7e0ee91e3ada9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-02', 1780350099, 1780350099, 'Active'),
(63, 33, 'cd2b3d286d619ed57cd40eb61088b3111afc26a040ae7deebf8f451486643a41', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-02', 1780350099, 1780350099, 'Active'),
(64, 33, '4f9df92e12184a9543eb54833ab73a376593afbdffa8c32dd1bbdc1cdc8ada68', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-02', 1780358341, 1780358341, 'Signed Out'),
(65, 1, '95eb0f1e36c5e0797a68999cdf56b52c986f1067bb81c93e83899166d498b302', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-02', 1780372488, 1780372488, 'Active'),
(66, 43, '295b7d08caa46f03888d819ba9394676ac460bfa4623b6f1b0087458918665bc', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-02', 1780372771, 1780372771, 'Active'),
(67, 33, '2bc96f22e347df3cb18cc9164037525d1ef09235f8d2115dfb293e5801d10465', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-03', 1780430234, 1780430234, 'Signed Out'),
(68, 12, '2e3f1e9ccc9c126c9d3bfeeb1533d343ef01fb1336d3dd86c1348af20f7530cd', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-03', 1780431277, 1780431277, 'Signed Out'),
(69, 33, '0bc32a0048820697b7d21defcd9d7237e9a0a007f2b346562a999bacd1681d9e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-03', 1780432718, 1780432718, 'Signed Out'),
(70, 12, 'f373b696ab86bda41035d84dc5715df37877f93d63be25bc17cb5d5e67e923d3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-03', 1780432778, 1780432778, 'Active'),
(71, 12, 'c1638c059edcc39e5295eec3bb4fd025736d0835780e374d35a55ece4182756b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-03', 1780473325, 1780473325, 'Active');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admission`
--
ALTER TABLE `admission`
  ADD CONSTRAINT `fk_admission_school` FOREIGN KEY (`sch_id_fk`) REFERENCES `school` (`sch_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_admission_users` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admission_hod`
--
ALTER TABLE `admission_hod`
  ADD CONSTRAINT `fk_adm_hod_admission` FOREIGN KEY (`admission_id_fk`) REFERENCES `admission` (`admission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_adm_hod_dept` FOREIGN KEY (`sch_dept_id_fk`) REFERENCES `sch_department` (`sch_dept_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admission_student_role`
--
ALTER TABLE `admission_student_role`
  ADD CONSTRAINT `fk_asr_admission` FOREIGN KEY (`admission_id_fk`) REFERENCES `admission` (`admission_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admission_teaching_subject`
--
ALTER TABLE `admission_teaching_subject`
  ADD CONSTRAINT `fk_ats_admission` FOREIGN KEY (`admission_id_fk`) REFERENCES `admission` (`admission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ats_sch_sub` FOREIGN KEY (`sch_sub_id_fk`) REFERENCES `sch_subject` (`sch_sub_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classroom`
--
ALTER TABLE `classroom`
  ADD CONSTRAINT `classroom_ibfk_1` FOREIGN KEY (`stream_id_fk`) REFERENCES `stream` (`stream_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `fk_ditrict_province` FOREIGN KEY (`province_id_fk`) REFERENCES `province` (`province_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `enrolment`
--
ALTER TABLE `enrolment`
  ADD CONSTRAINT `enrolment_ibfk_1` FOREIGN KEY (`stream_id_fk`) REFERENCES `stream` (`stream_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_enrolment_admission` FOREIGN KEY (`admission_id_fk`) REFERENCES `admission` (`admission_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `generated_reference`
--
ALTER TABLE `generated_reference`
  ADD CONSTRAINT `generated_reference_ibfk_1` FOREIGN KEY (`ref_cat_id_fk`) REFERENCES `reference_category` (`ref_cat_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `generated_reference_ibfk_2` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `generated_reference_ibfk_3` FOREIGN KEY (`gen_ref_by`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `house`
--
ALTER TABLE `house`
  ADD CONSTRAINT `fk_house_school` FOREIGN KEY (`sch_id_fk`) REFERENCES `school` (`sch_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `level`
--
ALTER TABLE `level`
  ADD CONSTRAINT `fk_level_sch_category` FOREIGN KEY (`sch_cat_id_fk`) REFERENCES `sch_category` (`sch_cat_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `next_of_kin`
--
ALTER TABLE `next_of_kin`
  ADD CONSTRAINT `next_of_kin_ibfk_1` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `fk_permission_modules` FOREIGN KEY (`module_id_fk`) REFERENCES `modules` (`module_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `province`
--
ALTER TABLE `province`
  ADD CONSTRAINT `fk_province_division` FOREIGN KEY (`division_id_fk`) REFERENCES `division` (`division_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_ibfk_1` FOREIGN KEY (`role_cat_id_fk`) REFERENCES `role_category` (`role_cat_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `fk_role_perm_permission` FOREIGN KEY (`perm_id_fk`) REFERENCES `permission` (`perm_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_role_perm_role` FOREIGN KEY (`role_id_fk`) REFERENCES `role` (`role_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `school`
--
ALTER TABLE `school`
  ADD CONSTRAINT `fk_school_ditrict` FOREIGN KEY (`district_id_fk`) REFERENCES `district` (`district_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_school_sch_category` FOREIGN KEY (`sch_cat_id_fk`) REFERENCES `sch_category` (`sch_cat_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `sch_department`
--
ALTER TABLE `sch_department`
  ADD CONSTRAINT `fk_sch_department_department` FOREIGN KEY (`dept_id_fk`) REFERENCES `department` (`dept_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sch_department_school` FOREIGN KEY (`sch_id_fk`) REFERENCES `school` (`sch_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `sch_level`
--
ALTER TABLE `sch_level`
  ADD CONSTRAINT `fk_sch_level_level` FOREIGN KEY (`level_id_fk`) REFERENCES `level` (`level_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sch_level_school` FOREIGN KEY (`sch_id_fk`) REFERENCES `school` (`sch_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `sch_subject`
--
ALTER TABLE `sch_subject`
  ADD CONSTRAINT `fk_sch_subject_school` FOREIGN KEY (`sch_id_fk`) REFERENCES `school` (`sch_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sch_subject_subject` FOREIGN KEY (`subject_id_fk`) REFERENCES `subject` (`subject_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `sch_subject_ibfk_1` FOREIGN KEY (`sch_dept_id_fk`) REFERENCES `sch_department` (`sch_dept_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`sch_id_fk`) REFERENCES `school` (`sch_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `stream`
--
ALTER TABLE `stream`
  ADD CONSTRAINT `fk_stream_level` FOREIGN KEY (`sch_level_id_fk`) REFERENCES `sch_level` (`sch_level_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `stream_core_subject`
--
ALTER TABLE `stream_core_subject`
  ADD CONSTRAINT `fk_stream_core_subject_stream` FOREIGN KEY (`stream_id_fk`) REFERENCES `stream` (`stream_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `stream_core_subject_ibfk_1` FOREIGN KEY (`sch_sub_id_fk`) REFERENCES `sch_subject` (`sch_sub_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `stream_optional_subject`
--
ALTER TABLE `stream_optional_subject`
  ADD CONSTRAINT `fk_stream_optional_subject_stream` FOREIGN KEY (`stream_id_fk`) REFERENCES `stream` (`stream_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `stream_optional_subject_ibfk_1` FOREIGN KEY (`sch_sub_id_fk`) REFERENCES `sch_subject` (`sch_sub_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `fk_subject_level_level` FOREIGN KEY (`level_id_fk`) REFERENCES `level` (`level_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `fk_subscription_plan` FOREIGN KEY (`plan_id_fk`) REFERENCES `plans` (`plan_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_subscription_school` FOREIGN KEY (`sch_id_fk`) REFERENCES `school` (`sch_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_ditrict` FOREIGN KEY (`district_id_fk`) REFERENCES `district` (`district_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `fk_user_log_user` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_medical`
--
ALTER TABLE `user_medical`
  ADD CONSTRAINT `fk_user_medical_user` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_medical_files`
--
ALTER TABLE `user_medical_files`
  ADD CONSTRAINT `fk_medical_files_medical` FOREIGN KEY (`medical_id_fk`) REFERENCES `user_medical` (`medical_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_notification`
--
ALTER TABLE `user_notification`
  ADD CONSTRAINT `fk_user_notification_user` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_password`
--
ALTER TABLE `user_password`
  ADD CONSTRAINT `user_password_ibfk_1` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `fk_user_role_users` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`role_id_fk`) REFERENCES `role` (`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `fk_user_session_user` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
