-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 01, 2026 at 02:57 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(19, 38, 12, '2026-05-28', 1779913023, NULL, 'Completed'),
(20, 39, 12, '2026-05-28', 1779913079, NULL, 'Active'),
(21, 40, 12, '2026-05-28', 1779913118, NULL, 'Active'),
(22, 41, 12, '2026-05-28', 1779913168, NULL, 'Active'),
(23, 42, 12, '2026-05-28', 1779913233, NULL, 'Active'),
(24, 43, 12, '2026-06-01', 1780261886, NULL, 'Active'),
(25, 44, 12, '2026-06-01', 1780264419, NULL, 'Active'),
(26, 45, 12, '2026-06-02', 1780342192, NULL, 'Active'),
(27, 34, 29, '2026-06-02', 1780346508, NULL, 'Active'),
(28, 27, 12, '2026-06-03', 1780440806, NULL, 'Active'),
(29, 51, 30, '2026-06-06', 1780724334, NULL, 'Active'),
(30, 46, 30, '2026-06-06', 1780724358, NULL, 'Active'),
(31, 59, 30, '2026-06-06', 1780724377, NULL, 'Active'),
(32, 54, 30, '2026-06-06', 1780724395, NULL, 'Active'),
(33, 55, 30, '2026-06-06', 1780724408, NULL, 'Active'),
(34, 48, 30, '2026-06-06', 1780724421, NULL, 'Active'),
(35, 60, 30, '2026-06-06', 1780724442, NULL, 'Active'),
(36, 61, 30, '2026-06-06', 1780724454, NULL, 'Active'),
(37, 58, 30, '2026-06-06', 1780724470, NULL, 'Active'),
(38, 49, 30, '2026-06-06', 1780724482, NULL, 'Active'),
(39, 53, 30, '2026-06-06', 1780724494, NULL, 'Active'),
(40, 56, 30, '2026-06-06', 1780724506, NULL, 'Active'),
(41, 57, 30, '2026-06-06', 1780724517, NULL, 'Active'),
(42, 30, 30, '2026-06-06', 1780724531, NULL, 'Active'),
(43, 52, 30, '2026-06-06', 1780724616, NULL, 'Active'),
(44, 20, 30, '2026-06-06', 1780724628, NULL, 'Active'),
(45, 62, 30, '2026-06-06', 1780724642, NULL, 'Active'),
(46, 50, 30, '2026-06-06', 1780724656, NULL, 'Active'),
(47, 47, 30, '2026-06-06', 1780724669, NULL, 'Active'),
(48, 63, 30, '2026-06-06', 1780725345, NULL, 'Active'),
(49, 64, 12, '2026-06-18', 1781731976, NULL, 'Active'),
(50, 65, 12, '2026-06-18', 1781751375, NULL, 'Active'),
(51, 66, 12, '2026-06-18', 1781752541, NULL, 'Active'),
(52, 67, 12, '2026-06-18', 1781753182, NULL, 'Active'),
(53, 68, 30, '2026-06-25', 1782344553, NULL, 'Active'),
(54, 69, 30, '2026-06-25', 1782346401, NULL, 'Active'),
(55, 70, 12, '2026-06-25', 1782356737, NULL, 'Active'),
(56, 71, 30, '2026-06-25', 1782357199, NULL, 'Active'),
(57, 72, 30, '2026-06-25', 1782358108, NULL, 'Active'),
(58, 73, 30, '2026-06-25', 1782358573, NULL, 'Active'),
(59, 74, 30, '2026-06-26', 1782402270, NULL, 'Active');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admission_student_role`
--

INSERT INTO `admission_student_role` (`adm_student_role_id`, `admission_id_fk`, `leadership_role`, `created_date`, `created_time`, `adm_stud_role_status`) VALUES
(3, 19, 'junior_prefect', '2026-06-29', 1782683215, 'Completed');

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
-- Table structure for table `assignment_plagiarism`
--

DROP TABLE IF EXISTS `assignment_plagiarism`;
CREATE TABLE IF NOT EXISTS `assignment_plagiarism` (
  `plagiarism_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `submission_id_fk` int NOT NULL,
  `scan_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `score` decimal(5,2) DEFAULT NULL,
  `identical_pct` decimal(5,2) DEFAULT NULL,
  `minor_changed_pct` decimal(5,2) DEFAULT NULL,
  `paraphrased_pct` decimal(5,2) DEFAULT NULL,
  `sources_json` mediumtext COLLATE utf8mb4_unicode_ci,
  `webhook_raw` mediumtext COLLATE utf8mb4_unicode_ci,
  `error_message` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`plagiarism_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignment_plagiarism`
--

INSERT INTO `assignment_plagiarism` (`plagiarism_id`, `submission_id_fk`, `scan_id`, `status`, `score`, `identical_pct`, `minor_changed_pct`, `paraphrased_pct`, `sources_json`, `webhook_raw`, `error_message`, `submitted_at`, `completed_at`, `created_at`) VALUES
(5, 2, 'sub2xb62247c015b1e05b68', 'completed', 0.20, 0.25, 0.00, 0.00, '[{\"type\":\"internet\",\"url\":\"http:\\/\\/example.com\\/\",\"title\":\"Example Domain\",\"words\":1}]', '{\"scannedDocument\":{\"scanId\":\"sub2xb62247c015b1e05b68\",\"totalWords\":405,\"totalExcluded\":0,\"credits\":0,\"expectedCredits\":2,\"creationTime\":\"2026-07-01T02:32:19.120871Z\",\"metadata\":{\"creationDate\":\"2026-04-15T09:54:59Z\",\"lastModificationDate\":\"2026-04-15T09:54:59Z\",\"author\":\"Emily McCarthy\",\"filename\":\"1782873134_cace0c9f65962dbabb0f.pdf\"},\"enabled\":{\"plagiarismDetection\":true,\"aiDetection\":false,\"explainableAi\":false,\"writingFeedback\":false,\"pdfReport\":false,\"cheatDetection\":false,\"aiSourceMatch\":false,\"internalAiSourceMatch\":false},\"detectedLanguage\":\"en\"},\"results\":{\"score\":{\"identicalWords\":1,\"minorChangedWords\":0,\"relatedMeaningWords\":0,\"aggregatedScore\":0.2},\"internet\":[{\"url\":\"http:\\/\\/example.com\\/\",\"id\":\"2a1b402420\",\"title\":\"Example Domain\",\"introduction\":\"Example Domain This domain is for use in documentation examples without needing permission. Avoid use in operations. Learn more\",\"matchedWords\":1,\"identicalWords\":1,\"similarWords\":0,\"paraphrasedWords\":0,\"totalWords\":19,\"metadata\":{\"authors\":[]},\"tags\":[]}],\"database\":[],\"batch\":[],\"repositories\":[],\"internalAIData\":[]},\"notifications\":{\"alerts\":[]},\"writingFeedback\":{\"textStatistics\":{\"sentenceCount\":5,\"averageWordLength\":4.7,\"averageSentenceLength\":12.8,\"readingTimeSeconds\":21,\"speakingTimeSeconds\":29.5},\"score\":{\"grammarCorrectionsCount\":1,\"grammarCorrectionsScore\":93,\"grammarScoreWeight\":1,\"mechanicsCorrectionsCount\":1,\"mechanicsCorrectionsScore\":93,\"mechanicsScoreWeight\":1,\"sentenceStructureCorrectionsCount\":1,\"sentenceStructureCorrectionsScore\":93,\"sentenceStructureScoreWeight\":1,\"wordChoiceCorrectionsCount\":0,\"wordChoiceCorrectionsScore\":100,\"wordChoiceScoreWeight\":1,\"overallScore\":94},\"readability\":{\"score\":95,\"readabilityLevel\":1,\"readabilityLevelText\":\"5th Grader\",\"readabilityLevelDescription\":\"Very easy to read\"}},\"status\":0,\"developerPayload\":\"\"}', NULL, '2026-07-01 14:32:24', '2026-07-01 14:32:24', '2026-07-01 14:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submission`
--

DROP TABLE IF EXISTS `assignment_submission`;
CREATE TABLE IF NOT EXISTS `assignment_submission` (
  `submission_id` int NOT NULL AUTO_INCREMENT,
  `assignment_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `submission_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submission_file_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submission_note` text COLLATE utf8mb4_unicode_ci,
  `submission_status` enum('Submitted','Late','Graded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Submitted',
  `grade` decimal(5,2) DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `graded_at` datetime DEFAULT NULL,
  `graded_by` int DEFAULT NULL,
  PRIMARY KEY (`submission_id`),
  UNIQUE KEY `unique_submission` (`assignment_id_fk`,`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignment_submission`
--

INSERT INTO `assignment_submission` (`submission_id`, `assignment_id_fk`, `user_id_fk`, `submission_file`, `submission_file_type`, `submission_note`, `submission_status`, `grade`, `feedback`, `submitted_at`, `updated_at`, `graded_at`, `graded_by`) VALUES
(1, 3, 37, '1780527752_675e077a0c1bc1272f6b.pdf', 'pdf', NULL, 'Graded', NULL, NULL, '2026-06-04 11:02:32', '2026-06-04 11:30:10', NULL, NULL),
(2, 7, 48, '1782873134_cace0c9f65962dbabb0f.pdf', 'pdf', NULL, 'Submitted', NULL, NULL, '2026-07-01 14:32:14', '2026-07-01 14:32:14', NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `type`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'direct', NULL, 33, '2026-05-27 15:55:32', '2026-06-06 06:13:19'),
(2, 'direct', NULL, 1, '2026-06-02 12:41:05', '2026-06-02 12:41:05'),
(3, 'direct', NULL, 1, '2026-06-02 12:41:13', '2026-06-02 12:41:13'),
(4, 'direct', NULL, 12, '2026-06-03 08:16:51', '2026-06-05 22:47:54'),
(5, 'direct', NULL, 37, '2026-06-04 07:21:06', '2026-06-04 07:21:06'),
(6, 'direct', NULL, 12, '2026-06-05 22:48:02', '2026-06-06 15:04:14'),
(7, 'direct', NULL, 12, '2026-06-06 06:03:12', '2026-06-06 06:03:12'),
(8, 'direct', NULL, 12, '2026-06-06 06:03:37', '2026-06-06 06:03:37'),
(9, 'direct', NULL, 37, '2026-06-06 06:08:05', '2026-06-06 15:05:21'),
(10, 'direct', NULL, 42, '2026-06-06 06:10:32', '2026-06-06 06:18:41'),
(11, 'direct', NULL, 12, '2026-06-06 06:55:18', '2026-06-06 08:01:31'),
(12, 'direct', NULL, 12, '2026-06-06 06:55:26', '2026-06-06 06:55:26'),
(13, 'direct', NULL, 12, '2026-06-06 06:55:39', '2026-06-06 06:55:39'),
(14, 'direct', NULL, 12, '2026-06-06 06:55:46', '2026-06-06 06:55:46'),
(15, 'direct', NULL, 12, '2026-06-06 06:55:59', '2026-06-06 06:55:59'),
(16, 'direct', NULL, 12, '2026-06-06 06:56:04', '2026-06-06 06:56:04'),
(17, 'direct', NULL, 12, '2026-06-06 07:10:54', '2026-06-06 07:10:54'),
(18, 'direct', NULL, 12, '2026-06-06 07:32:01', '2026-06-06 07:32:01'),
(19, 'direct', NULL, 12, '2026-06-06 07:34:48', '2026-06-06 07:34:48'),
(20, 'direct', NULL, 42, '2026-06-06 08:44:13', '2026-06-06 08:44:13'),
(21, 'direct', NULL, 42, '2026-06-06 08:45:28', '2026-06-06 08:45:28'),
(22, 'direct', NULL, 1, '2026-06-06 17:02:02', '2026-06-06 17:02:09'),
(23, 'direct', NULL, 48, '2026-06-06 19:06:15', '2026-07-01 07:39:54'),
(24, 'direct', NULL, 63, '2026-06-22 16:07:50', '2026-06-22 16:08:01'),
(25, 'direct', NULL, 63, '2026-06-22 16:08:08', '2026-06-22 16:08:11'),
(26, 'direct', NULL, 48, '2026-06-30 19:10:43', '2026-06-30 19:11:22'),
(27, 'direct', NULL, 1, '2026-06-30 19:26:33', '2026-06-30 19:26:37'),
(28, 'direct', NULL, 63, '2026-06-30 19:52:18', '2026-06-30 19:52:18'),
(29, 'direct', NULL, 63, '2026-06-30 19:52:21', '2026-06-30 19:52:21'),
(30, 'direct', NULL, 48, '2026-07-01 07:40:48', '2026-07-01 07:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conversation_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `message_type` enum('text','image','file','call') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_conversation_created` (`conversation_id`,`created_at`),
  KEY `idx_sender_id` (`sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(19, 1, 33, 'text', 'hiu', '2026-05-28 07:05:21', NULL),
(20, 4, 12, 'text', 'hi', '2026-06-05 22:47:54', NULL),
(21, 6, 12, 'text', 'hu', '2026-06-05 22:48:06', NULL),
(22, 6, 12, 'text', 'hi there', '2026-06-05 22:56:41', NULL),
(23, 6, 12, 'text', 'did u see my message', '2026-06-05 22:58:57', NULL),
(24, 6, 42, 'text', 'yes it is coming now instantly', '2026-06-05 22:59:22', NULL),
(25, 6, 12, 'text', 'great thats great news', '2026-06-05 22:59:46', NULL),
(26, 6, 12, 'image', NULL, '2026-06-05 23:00:07', NULL),
(27, 6, 42, 'text', 'this is great', '2026-06-05 23:07:08', NULL),
(28, 6, 12, 'text', 'yes i know', '2026-06-05 23:07:25', NULL),
(29, 6, 42, 'image', NULL, '2026-06-05 23:12:30', NULL),
(30, 6, 12, 'text', 'man this is really awesome', '2026-06-05 23:15:53', NULL),
(31, 6, 12, 'text', 'testing with chat drawer close', '2026-06-05 23:16:18', '2026-06-06 00:07:24'),
(32, 6, 12, 'text', 'Hi there i am typing but it is not showing', '2026-06-05 23:25:25', '2026-06-06 05:53:29'),
(33, 6, 12, 'text', 'delete', '2026-06-06 05:45:35', '2026-06-06 05:52:53'),
(34, 6, 42, 'text', 'set', '2026-06-06 05:45:43', '2026-06-06 05:46:58'),
(35, 6, 12, 'text', 'hi there', '2026-06-06 05:54:11', NULL),
(36, 7, 12, 'text', 'hi there', '2026-06-06 06:03:12', NULL),
(37, 6, 12, 'text', 'hi there', '2026-06-06 06:03:23', NULL),
(38, 8, 12, 'text', 'hi there', '2026-06-06 06:03:37', NULL),
(39, 6, 12, 'text', 'yes it is coming now instantly', '2026-06-06 06:04:33', NULL),
(40, 1, 12, 'text', 'yes it is coming now instantly', '2026-06-06 06:13:19', NULL),
(41, 9, 12, 'text', 'yes it is coming now instantly', '2026-06-06 06:14:01', NULL),
(42, 10, 42, 'text', 'io', '2026-06-06 06:18:41', NULL),
(43, 9, 12, 'text', 'hi', '2026-06-06 06:21:14', NULL),
(44, 9, 37, 'text', 'io', '2026-06-06 06:21:44', NULL),
(45, 9, 37, 'text', 'received', '2026-06-06 06:22:03', NULL),
(46, 6, 12, 'text', 'received', '2026-06-06 06:22:31', NULL),
(47, 9, 12, 'image', NULL, '2026-06-06 06:23:17', NULL),
(48, 6, 12, 'file', NULL, '2026-06-06 06:27:05', NULL),
(49, 6, 12, 'file', NULL, '2026-06-06 06:27:57', NULL),
(50, 6, 12, 'file', NULL, '2026-06-06 06:28:08', NULL),
(51, 9, 12, 'file', NULL, '2026-06-06 06:38:05', NULL),
(52, 11, 12, 'text', 'hi', '2026-06-06 08:01:31', NULL),
(53, 9, 12, 'text', 'hit', '2026-06-06 08:05:48', NULL),
(54, 6, 42, 'text', 'received', '2026-06-06 08:06:19', NULL),
(55, 6, 12, 'text', 'ok', '2026-06-06 08:06:36', NULL),
(56, 6, 42, '', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":15}', '2026-06-06 10:54:15', NULL),
(57, 6, 42, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-06 10:57:15', NULL),
(58, 6, 12, '', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":23}', '2026-06-06 10:57:56', '2026-06-06 14:18:05'),
(59, 6, 12, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-06 11:14:33', NULL),
(60, 6, 12, '', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":6}', '2026-06-06 11:14:50', NULL),
(61, 6, 12, '', '{\"call_type\":\"video\",\"status\":\"ended\",\"duration\":33}', '2026-06-06 11:29:20', NULL),
(62, 6, 42, 'text', 'hi sir', '2026-06-06 14:20:05', NULL),
(63, 6, 12, 'text', 'io', '2026-06-06 14:20:16', NULL),
(64, 6, 42, 'text', 'did u get my message', '2026-06-06 14:20:31', NULL),
(65, 6, 12, 'text', 'yr', '2026-06-06 14:20:38', NULL),
(66, 6, 12, 'text', 'yes but after refresh', '2026-06-06 14:21:09', NULL),
(67, 6, 42, 'text', 'what about now', '2026-06-06 14:21:18', NULL),
(68, 6, 12, 'text', 'ok now i get it', '2026-06-06 14:21:27', NULL),
(69, 6, 12, '', '{\"call_type\":\"video\",\"status\":\"missed\",\"duration\":0}', '2026-06-06 14:32:43', NULL),
(70, 6, 12, '', '{\"call_type\":\"voice\",\"status\":\"missed\",\"duration\":0}', '2026-06-06 14:34:24', NULL),
(71, 6, 12, '', '{\"call_type\":\"video\",\"status\":\"declined\",\"duration\":0}', '2026-06-06 14:55:36', NULL),
(72, 6, 12, '', '{\"call_type\":\"video\",\"status\":\"declined\",\"duration\":0}', '2026-06-06 14:55:52', NULL),
(73, 6, 12, '', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":11}', '2026-06-06 14:56:15', NULL),
(74, 6, 12, '', '{\"call_type\":\"video\",\"status\":\"declined\",\"duration\":0}', '2026-06-06 14:56:23', NULL),
(75, 6, 42, '', '{\"call_type\":\"video\",\"status\":\"ended\",\"duration\":38}', '2026-06-06 14:59:00', NULL),
(76, 6, 12, '', '{\"call_type\":\"video\",\"status\":\"declined\",\"duration\":0}', '2026-06-06 14:59:13', NULL),
(77, 6, 12, '', '{\"call_type\":\"video\",\"status\":\"declined\",\"duration\":0}', '2026-06-06 15:02:58', NULL),
(78, 6, 12, '', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-06 15:04:14', NULL),
(79, 9, 37, 'text', 'hi', '2026-06-06 15:05:02', NULL),
(80, 9, 12, '', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-06 15:05:06', NULL),
(81, 9, 12, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-06 15:05:21', NULL),
(82, 22, 1, '', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-06 17:02:09', NULL),
(83, 23, 63, 'text', 'bula', '2026-06-06 19:06:24', NULL),
(84, 23, 63, '', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-06 19:07:56', NULL),
(85, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-06 19:08:19', NULL),
(86, 23, 48, '', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-08 07:47:09', NULL),
(87, 23, 63, '', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-08 07:48:41', NULL),
(88, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-08 07:48:45', NULL),
(89, 23, 63, '', '{\"call_type\":\"video\",\"status\":\"missed\",\"duration\":0}', '2026-06-08 08:17:46', NULL),
(90, 23, 48, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-18 19:34:25', NULL),
(91, 23, 48, '', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-18 19:34:41', NULL),
(92, 23, 63, 'text', 'hi', '2026-06-22 15:29:44', NULL),
(93, 23, 48, 'text', 'io sir', '2026-06-22 15:29:50', NULL),
(94, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-22 15:30:29', NULL),
(95, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"missed\",\"duration\":0}', '2026-06-22 15:33:40', NULL),
(96, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-22 16:00:45', NULL),
(97, 23, 63, '', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-22 16:00:55', NULL),
(98, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-22 16:07:32', NULL),
(99, 23, 48, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-22 16:07:44', NULL),
(100, 24, 63, 'text', 'vcbvbcvb', '2026-06-22 16:08:01', NULL),
(101, 25, 63, 'text', 'bcvbcvb', '2026-06-22 16:08:11', NULL),
(102, 23, 48, 'text', 'dfgdfg', '2026-06-22 16:28:16', NULL),
(103, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":111}', '2026-06-23 00:08:54', NULL),
(104, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":23}', '2026-06-23 00:14:39', NULL),
(105, 23, 63, '', '{\"call_type\":\"video\",\"status\":\"declined\",\"duration\":0}', '2026-06-23 00:14:53', NULL),
(106, 23, 63, '', '{\"call_type\":\"video\",\"status\":\"declined\",\"duration\":0}', '2026-06-23 00:15:04', NULL),
(107, 23, 63, '', '{\"call_type\":\"video\",\"status\":\"declined\",\"duration\":0}', '2026-06-23 00:27:38', NULL),
(108, 23, 63, '', '{\"call_type\":\"video\",\"status\":\"ended\",\"duration\":51}', '2026-06-23 00:48:19', NULL),
(109, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":9}', '2026-06-23 00:59:16', NULL),
(110, 23, 48, '', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":12}', '2026-06-23 01:00:23', NULL),
(111, 23, 63, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-23 07:16:46', NULL),
(112, 23, 48, '', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-23 16:28:05', NULL),
(113, 23, 63, 'call', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":7}', '2026-06-30 17:15:42', NULL),
(114, 23, 63, 'call', '{\"call_type\":\"video\",\"status\":\"ended\",\"duration\":7}', '2026-06-30 17:16:04', NULL),
(115, 23, 48, 'image', NULL, '2026-06-30 17:17:01', NULL),
(116, 23, 48, 'call', '{\"call_type\":\"video\",\"status\":\"ended\",\"duration\":16}', '2026-06-30 17:44:18', NULL),
(117, 26, 48, 'text', '💘test', '2026-06-30 19:11:22', NULL),
(118, 23, 48, 'text', 'ioooo', '2026-06-30 19:12:46', NULL),
(119, 23, 48, 'call', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-30 19:22:25', NULL),
(120, 27, 1, 'text', 'io', '2026-06-30 19:26:37', NULL),
(121, 23, 48, 'call', '{\"call_type\":\"voice\",\"status\":\"cancelled\",\"duration\":0}', '2026-06-30 19:37:44', NULL),
(122, 23, 63, 'call', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":4}', '2026-06-30 20:16:01', NULL),
(123, 23, 48, 'call', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":5}', '2026-06-30 20:49:18', NULL),
(124, 23, 63, 'call', '{\"call_type\":\"video\",\"status\":\"ended\",\"duration\":18}', '2026-06-30 20:49:47', NULL),
(125, 23, 48, 'call', '{\"call_type\":\"voice\",\"status\":\"ended\",\"duration\":7}', '2026-07-01 07:36:09', NULL),
(126, 23, 48, 'call', '{\"call_type\":\"video\",\"status\":\"ended\",\"duration\":52}', '2026-07-01 07:37:08', NULL),
(127, 23, 63, 'call', '{\"call_type\":\"video\",\"status\":\"ended\",\"duration\":120}', '2026-07-01 07:39:29', NULL),
(128, 23, 63, 'text', 'io', '2026-07-01 07:39:34', NULL),
(129, 23, 48, 'text', 'hghgghghghghgh', '2026-07-01 07:39:41', NULL),
(130, 23, 63, 'text', 'ghghghghghghg', '2026-07-01 07:39:47', NULL),
(131, 23, 48, 'text', 'nml,nm,nm,n mmm', '2026-07-01 07:39:54', NULL),
(132, 30, 48, 'call', '{\"call_type\":\"voice\",\"status\":\"missed\",\"duration\":0}', '2026-07-01 07:41:22', NULL),
(133, 30, 48, 'call', '{\"call_type\":\"video\",\"status\":\"cancelled\",\"duration\":0}', '2026-07-01 07:41:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_deletions`
--

DROP TABLE IF EXISTS `chat_message_deletions`;
CREATE TABLE IF NOT EXISTS `chat_message_deletions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_id_user_id` (`message_id`,`user_id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_message_deletions`
--

INSERT INTO `chat_message_deletions` (`id`, `message_id`, `user_id`, `deleted_at`) VALUES
(1, 30, 12, '2026-06-06 00:07:18'),
(2, 31, 12, '2026-06-06 05:43:38'),
(3, 34, 12, '2026-06-06 05:47:13'),
(4, 33, 42, '2026-06-06 05:53:11'),
(5, 34, 42, '2026-06-06 05:53:17'),
(6, 33, 12, '2026-06-06 05:53:20'),
(7, 31, 42, '2026-06-06 05:53:48'),
(8, 32, 42, '2026-06-06 05:53:56'),
(9, 32, 12, '2026-06-06 05:54:04'),
(10, 115, 48, '2026-06-30 17:29:43'),
(11, 117, 48, '2026-06-30 19:11:45'),
(12, 119, 48, '2026-06-30 19:33:52'),
(14, 120, 48, '2026-06-30 20:09:13');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_message_files`
--

INSERT INTO `chat_message_files` (`id`, `message_id`, `original_name`, `stored_name`, `file_path`, `file_type`, `file_size`, `created_at`) VALUES
(1, 26, '121327289_106514751234980_5375803299248619825_n.png', '93458acb793df1b3761ed3fd47ee4012.png', 'uploads/chat/2026-06/93458acb793df1b3761ed3fd47ee4012.png', 'image/png', 15922, '2026-06-05 23:00:07'),
(2, 29, 'application.png', '2a4380025fa090eef572e6274ac783a9.png', 'uploads/chat/2026-06/2a4380025fa090eef572e6274ac783a9.png', 'image/png', 18745, '2026-06-05 23:12:30'),
(3, 29, 'dashboard.png', '3b496acad0cc3e9889bde3b6236bad16.png', 'uploads/chat/2026-06/3b496acad0cc3e9889bde3b6236bad16.png', 'image/png', 54805, '2026-06-05 23:12:30'),
(4, 29, 'landing.png', '4ddb9bebbc816a9d50bbe47c4b061dd0.png', 'uploads/chat/2026-06/4ddb9bebbc816a9d50bbe47c4b061dd0.png', 'image/png', 122500, '2026-06-05 23:12:30'),
(5, 29, 'login.png', '8d3db484096faf0ff56002076e21ee68.png', 'uploads/chat/2026-06/8d3db484096faf0ff56002076e21ee68.png', 'image/png', 26760, '2026-06-05 23:12:30'),
(6, 29, 'members.png', 'b2c687fd1462b2297d6482be82ab0c7f.png', 'uploads/chat/2026-06/b2c687fd1462b2297d6482be82ab0c7f.png', 'image/png', 132801, '2026-06-05 23:12:30'),
(7, 47, 'application.png', 'e064f1bcd080673678f6cd62ad983074.png', 'uploads/chat/2026-06/e064f1bcd080673678f6cd62ad983074.png', 'image/png', 18745, '2026-06-06 06:23:17'),
(8, 47, 'dashboard.png', '2be0be7b75d0d59eaf55060b462619b1.png', 'uploads/chat/2026-06/2be0be7b75d0d59eaf55060b462619b1.png', 'image/png', 54805, '2026-06-06 06:23:17'),
(9, 47, 'landing.png', 'd902df10e9472fb7502836e84bb86082.png', 'uploads/chat/2026-06/d902df10e9472fb7502836e84bb86082.png', 'image/png', 122500, '2026-06-06 06:23:17'),
(10, 47, 'login.png', 'a6de6b75848978f314366a01733adf39.png', 'uploads/chat/2026-06/a6de6b75848978f314366a01733adf39.png', 'image/png', 26760, '2026-06-06 06:23:17'),
(11, 47, 'members.png', '2dbdf46881b61bfcbba8b5be5e5ad47b.png', 'uploads/chat/2026-06/2dbdf46881b61bfcbba8b5be5e5ad47b.png', 'image/png', 132801, '2026-06-06 06:23:17'),
(12, 48, 'Agent Access Form_020321.pdf', '223c7d3839fe7dbec1e55bc8e1496801.pdf', 'uploads/chat/2026-06/223c7d3839fe7dbec1e55bc8e1496801.pdf', 'application/pdf', 78170, '2026-06-06 06:27:05'),
(13, 49, 'Creditor Vendor Setup Form - EFT.docx', 'ca7a1d96c728512fbc98411fa4ed8931.docx', 'uploads/chat/2026-06/ca7a1d96c728512fbc98411fa4ed8931.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 50094, '2026-06-06 06:27:57'),
(14, 50, 'BBQ Orders 2025.xlsx', '3f7b7ab2312480503bc373e3473ae19e.xlsx', 'uploads/chat/2026-06/3f7b7ab2312480503bc373e3473ae19e.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9973, '2026-06-06 06:28:08'),
(15, 51, 'BBQ Orders 2025.xlsx', '1c7a6eb418df7fd1da68f98ae5245b64.xlsx', 'uploads/chat/2026-06/1c7a6eb418df7fd1da68f98ae5245b64.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 9973, '2026-06-06 06:38:05'),
(16, 115, 'Liga Ni Lawa Cover.jpg', '69aeb012d57625060834bcb775744fc8.jpg', 'uploads/chat/2026-06/69aeb012d57625060834bcb775744fc8.jpg', 'image/jpeg', 2521822, '2026-06-30 17:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_reactions`
--

DROP TABLE IF EXISTS `chat_message_reactions`;
CREATE TABLE IF NOT EXISTS `chat_message_reactions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `emoji` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `message_id_user_id` (`message_id`,`user_id`),
  KEY `message_id` (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_message_reactions`
--

INSERT INTO `chat_message_reactions` (`id`, `message_id`, `user_id`, `emoji`, `created_at`) VALUES
(1, 107, 48, '👍', '2026-06-30 19:07:47'),
(2, 118, 48, '❤️', '2026-06-30 19:34:01'),
(3, 118, 63, '👍', '2026-06-30 20:15:20'),
(6, 130, 63, '❤️', '2026-07-01 09:27:34');

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
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_participants`
--

INSERT INTO `chat_participants` (`id`, `conversation_id`, `user_id`, `joined_at`, `last_read_at`) VALUES
(1, 1, 33, '2026-05-27 15:55:32', '2026-06-01 16:20:24'),
(2, 1, 12, '2026-05-27 15:55:32', '2026-06-06 07:17:42'),
(3, 2, 1, '2026-06-02 12:41:05', '2026-06-02 12:41:06'),
(4, 2, 33, '2026-06-02 12:41:05', NULL),
(5, 3, 1, '2026-06-02 12:41:13', '2026-06-02 12:41:13'),
(6, 3, 12, '2026-06-02 12:41:13', NULL),
(7, 4, 12, '2026-06-03 08:16:51', '2026-06-06 14:41:11'),
(8, 4, 43, '2026-06-03 08:16:51', NULL),
(9, 5, 37, '2026-06-04 07:21:06', '2026-06-04 07:27:37'),
(10, 5, 43, '2026-06-04 07:21:06', NULL),
(11, 6, 12, '2026-06-05 22:48:02', '2026-06-06 15:04:04'),
(12, 6, 42, '2026-06-05 22:48:02', '2026-06-06 15:03:14'),
(13, 7, 12, '2026-06-06 06:03:12', '2026-06-06 07:32:59'),
(14, 7, 27, '2026-06-06 06:03:12', NULL),
(15, 8, 12, '2026-06-06 06:03:37', NULL),
(16, 8, 36, '2026-06-06 06:03:37', NULL),
(17, 9, 37, '2026-06-06 06:08:05', '2026-06-06 15:05:06'),
(18, 9, 12, '2026-06-06 06:08:05', '2026-06-06 15:04:47'),
(19, 10, 42, '2026-06-06 06:10:32', '2026-06-06 06:20:33'),
(20, 10, 37, '2026-06-06 06:10:32', NULL),
(21, 11, 12, '2026-06-06 06:55:18', '2026-06-06 08:01:28'),
(22, 11, 45, '2026-06-06 06:55:18', NULL),
(23, 12, 12, '2026-06-06 06:55:26', '2026-06-06 08:07:04'),
(24, 12, 18, '2026-06-06 06:55:26', NULL),
(25, 13, 12, '2026-06-06 06:55:39', '2026-06-06 08:07:09'),
(26, 13, 35, '2026-06-06 06:55:39', NULL),
(27, 14, 12, '2026-06-06 06:55:46', '2026-06-06 08:06:56'),
(28, 14, 40, '2026-06-06 06:55:46', NULL),
(29, 15, 12, '2026-06-06 06:55:59', '2026-06-06 06:55:59'),
(30, 15, 39, '2026-06-06 06:55:59', NULL),
(31, 16, 12, '2026-06-06 06:56:04', '2026-06-06 06:56:05'),
(32, 16, 44, '2026-06-06 06:56:04', NULL),
(33, 17, 12, '2026-06-06 07:10:54', '2026-06-06 07:33:08'),
(34, 17, 32, '2026-06-06 07:10:54', NULL),
(35, 18, 12, '2026-06-06 07:32:01', '2026-06-06 07:32:01'),
(36, 18, 41, '2026-06-06 07:32:01', NULL),
(37, 19, 12, '2026-06-06 07:34:48', '2026-06-06 08:09:17'),
(38, 19, 38, '2026-06-06 07:34:48', NULL),
(39, 20, 42, '2026-06-06 08:44:13', '2026-06-06 08:44:13'),
(40, 20, 43, '2026-06-06 08:44:13', NULL),
(41, 21, 42, '2026-06-06 08:45:28', '2026-06-06 08:45:39'),
(42, 21, 27, '2026-06-06 08:45:28', NULL),
(43, 22, 1, '2026-06-06 17:02:02', '2026-06-06 17:02:03'),
(44, 22, 37, '2026-06-06 17:02:02', NULL),
(45, 23, 48, '2026-06-06 19:06:15', '2026-07-01 07:39:47'),
(46, 23, 63, '2026-06-06 19:06:15', '2026-07-01 09:29:11'),
(47, 24, 63, '2026-06-22 16:07:50', '2026-06-22 16:07:58'),
(48, 24, 51, '2026-06-22 16:07:50', NULL),
(49, 25, 63, '2026-06-22 16:08:08', '2026-06-22 16:08:08'),
(50, 25, 60, '2026-06-22 16:08:08', NULL),
(51, 26, 48, '2026-06-30 19:10:43', '2026-06-30 19:11:38'),
(52, 26, 49, '2026-06-30 19:10:43', NULL),
(53, 27, 1, '2026-06-30 19:26:33', '2026-06-30 19:26:33'),
(54, 27, 48, '2026-06-30 19:26:33', '2026-06-30 20:09:18'),
(55, 28, 63, '2026-06-30 19:52:18', '2026-06-30 19:52:19'),
(56, 28, 56, '2026-06-30 19:52:18', NULL),
(57, 29, 63, '2026-06-30 19:52:21', '2026-06-30 19:52:21'),
(58, 29, 49, '2026-06-30 19:52:21', NULL),
(59, 30, 48, '2026-07-01 07:40:48', '2026-07-01 07:40:49'),
(60, 30, 51, '2026-07-01 07:40:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_user_blocks`
--

DROP TABLE IF EXISTS `chat_user_blocks`;
CREATE TABLE IF NOT EXISTS `chat_user_blocks` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `blocker_id` int UNSIGNED NOT NULL,
  `blocked_id` int UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blocker_id_blocked_id` (`blocker_id`,`blocked_id`),
  KEY `blocked_id` (`blocked_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`class_id`, `stream_id_fk`, `class_name`, `class_year`, `class_created_at`, `class_updated_at`, `class_created_by`, `class_updated_by`, `class_status`) VALUES
(3, 91, 'Year 9A 2026', 2026, '2026-05-27 08:19:18', '2026-05-27 08:19:18', 1, 1, 'Active'),
(4, 124, 'Year 13A 2026', 2025, '2026-06-06 17:53:48', '2026-06-19 11:11:14', 1, 1, 'Completed'),
(5, 125, 'Year 13B 2026', 2026, '2026-06-19 12:22:38', '2026-06-19 12:22:38', 1, 1, 'Active'),
(6, 123, 'Year 12B 2026', 2026, '2026-06-19 14:47:58', '2026-06-19 14:47:58', 1, 1, 'Active');

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
  `lesson_day` tinyint DEFAULT NULL COMMENT '1=Monday 2=Tuesday 3=Wednesday 4=Thursday 5=Friday',
  `lesson_year` int DEFAULT NULL,
  PRIMARY KEY (`lesson_id`),
  KEY `fk_lesson_class_sub` (`class_sub_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom_lesson`
--

INSERT INTO `classroom_lesson` (`lesson_id`, `class_sub_id_fk`, `lesson_title`, `lesson_desc`, `lesson_term`, `lesson_week`, `lesson_order`, `lesson_duration`, `lesson_status`, `created_by`, `created_at`, `updated_at`, `lesson_day`, `lesson_year`) VALUES
(10, 40, 'Na Vakacacabo', '“Na Vakacacabo” e dua na itovo vakavanua e vakarokorokotaki, e vakayagataki ena soqo ni lewenivanua, ena lotu (me vaka na magiti ni mate, vaka.mau, se veisusu), se ena kerea e dua na kerekere bibi vei turaga i taukei se vei koro.', 2, 4, 1, NULL, 'Published', 63, '2026-06-09 08:25:24', NULL, 2, 2026),
(11, 40, 'Na i tovo', 'nai tovo', 2, 5, 1, NULL, 'Published', 63, '2026-06-19 09:04:21', NULL, 5, 2026);

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom_role`
--

INSERT INTO `classroom_role` (`cs_id`, `class_id_fk`, `user_id_fk`, `cs_role`, `cs_status`, `cs_assigned_at`, `cs_assigned_by`) VALUES
(1, 3, 34, 'Class Teacher', 'Inactive', '2026-05-27', 1),
(2, 3, 33, 'Assistant Class Teacher', 'Active', '2026-05-27', 1),
(3, 3, 34, 'Class Teacher', 'Inactive', '2026-05-27', 1),
(4, 3, 12, 'Class Teacher', 'Active', '2026-05-27', 1),
(5, 3, 37, 'Class Captain', 'Active', '2026-05-27', 1),
(6, 3, 30, 'Assistant Class Captain', 'Active', '2026-05-27', 1),
(7, 4, 63, 'Class Teacher', 'Completed', '2026-06-06', 1),
(8, 4, 49, 'Class Captain', 'Completed', '2026-06-06', 1),
(9, 4, 58, 'Assistant Class Captain', 'Completed', '2026-06-06', 1),
(10, 6, 63, 'Class Teacher', 'Active', '2026-06-19', 1),
(11, 5, 63, 'Class Teacher', 'Active', '2026-06-19', 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(7, 3, 42, '2026-06-03 00:00:00', 12, 'Active'),
(8, 4, 59, '2026-06-06 00:00:00', 1, 'Completed'),
(9, 4, 54, '2026-06-06 00:00:00', 1, 'Completed'),
(10, 4, 60, '2026-06-06 00:00:00', 1, 'Completed'),
(11, 4, 47, '2026-06-06 00:00:00', 1, 'Completed'),
(12, 4, 50, '2026-06-06 00:00:00', 1, 'Completed'),
(13, 4, 48, '2026-06-06 00:00:00', 1, 'Completed'),
(14, 4, 62, '2026-06-06 00:00:00', 1, 'Completed'),
(15, 4, 61, '2026-06-06 00:00:00', 1, 'Completed'),
(16, 4, 58, '2026-06-06 00:00:00', 1, 'Completed'),
(17, 4, 49, '2026-06-06 00:00:00', 1, 'Completed'),
(18, 4, 53, '2026-06-06 00:00:00', 1, 'Completed'),
(19, 4, 56, '2026-06-06 00:00:00', 1, 'Completed'),
(20, 4, 57, '2026-06-06 00:00:00', 1, 'Completed'),
(21, 4, 30, '2026-06-06 00:00:00', 1, 'Completed'),
(22, 4, 52, '2026-06-06 00:00:00', 1, 'Completed'),
(23, 4, 20, '2026-06-06 00:00:00', 1, 'Completed'),
(24, 5, 51, '2026-06-19 00:00:00', 63, 'Active'),
(25, 5, 46, '2026-06-19 00:00:00', 63, 'Active'),
(26, 5, 55, '2026-06-19 00:00:00', 63, 'Active'),
(28, 5, 48, '2026-07-01 00:00:00', 63, 'Active');

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
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(14, 3, 35),
(47, 5, 89),
(46, 5, 25),
(45, 5, 90),
(44, 5, 86),
(43, 5, 87),
(42, 5, 88),
(41, 4, 212),
(40, 4, 208),
(39, 4, 201),
(38, 4, 200),
(37, 4, 195),
(36, 4, 192);

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom_subject_teacher`
--

INSERT INTO `classroom_subject_teacher` (`class_sub_teacher_id`, `class_sub_id_fk`, `user_id_fk`, `class_sub_teacher_status`) VALUES
(1, 11, 12, 'Active'),
(2, 7, 12, 'Inactive'),
(3, 1, 12, 'Active'),
(4, 2, 32, 'Inactive'),
(5, 2, 12, 'Active'),
(6, 3, 12, 'Active'),
(7, 4, 12, 'Active'),
(8, 5, 12, 'Active'),
(9, 7, 12, 'Active'),
(10, 10, 12, 'Active'),
(11, 8, 12, 'Active'),
(12, 9, 12, 'Active'),
(13, 12, 12, 'Active'),
(14, 13, 12, 'Active'),
(15, 14, 12, 'Active'),
(16, 6, 12, 'Active'),
(17, 15, 63, 'Completed'),
(18, 36, 63, 'Completed'),
(19, 38, 63, 'Completed'),
(20, 39, 63, 'Completed'),
(21, 40, 63, 'Completed'),
(22, 41, 63, 'Completed'),
(23, 42, 63, 'Active'),
(24, 43, 63, 'Active'),
(25, 47, 63, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `class_discussion`
--

DROP TABLE IF EXISTS `class_discussion`;
CREATE TABLE IF NOT EXISTS `class_discussion` (
  `cd_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id_fk` int NOT NULL,
  `author` int NOT NULL,
  `message` longtext COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `post_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_discussion`
--

INSERT INTO `class_discussion` (`cd_id`, `class_id_fk`, `author`, `message`, `created_at`, `post_status`) VALUES
(1, 3, 42, 'Test class discussion features', '2026-06-05 09:04:18', 1),
(2, 3, 42, 'test', '2026-06-05 09:16:32', 1),
(3, 4, 63, 'DOu qai irova tu mada yani nai taba ni yaya ni veiqaravi vaka vanua', '2026-06-06 18:29:20', 1),
(4, 5, 63, 'zcvxcvxcvxcvxcvxc', '2026-06-19 15:45:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_discussion_comment`
--

DROP TABLE IF EXISTS `class_discussion_comment`;
CREATE TABLE IF NOT EXISTS `class_discussion_comment` (
  `cdc_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cd_id_fk` int NOT NULL,
  `author` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `comment_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`cdc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_discussion_comment`
--

INSERT INTO `class_discussion_comment` (`cdc_id`, `cd_id_fk`, `author`, `comment`, `created_at`, `comment_status`) VALUES
(1, 1, 12, 'great to have this features here', '2026-06-05 10:32:31', 'Active'),
(2, 1, 12, 'set', '2026-06-05 10:40:48', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `class_discussion_comment_like`
--

DROP TABLE IF EXISTS `class_discussion_comment_like`;
CREATE TABLE IF NOT EXISTS `class_discussion_comment_like` (
  `clike_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cdc_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `like_type` enum('like','dislike') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'like',
  PRIMARY KEY (`clike_id`),
  UNIQUE KEY `cdc_id_fk_user_id_fk` (`cdc_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_discussion_comment_like`
--

INSERT INTO `class_discussion_comment_like` (`clike_id`, `cdc_id_fk`, `user_id_fk`, `like_type`) VALUES
(1, 1, 12, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `class_discussion_comment_reply`
--

DROP TABLE IF EXISTS `class_discussion_comment_reply`;
CREATE TABLE IF NOT EXISTS `class_discussion_comment_reply` (
  `cdcr_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cdc_id_fk` int NOT NULL,
  `author` int NOT NULL,
  `reply` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `reply_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`cdcr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_discussion_comment_reply`
--

INSERT INTO `class_discussion_comment_reply` (`cdcr_id`, `cdc_id_fk`, `author`, `reply`, `created_at`, `reply_status`) VALUES
(1, 1, 12, 'good', '2026-06-05 10:40:23', 'Active'),
(2, 1, 12, 'ok', '2026-06-05 10:40:39', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `class_discussion_comment_reply_like`
--

DROP TABLE IF EXISTS `class_discussion_comment_reply_like`;
CREATE TABLE IF NOT EXISTS `class_discussion_comment_reply_like` (
  `rlike_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cdcr_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `like_type` enum('like','dislike') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'like',
  PRIMARY KEY (`rlike_id`),
  UNIQUE KEY `cdcr_id_fk_user_id_fk` (`cdcr_id_fk`,`user_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_discussion_like`
--

DROP TABLE IF EXISTS `class_discussion_like`;
CREATE TABLE IF NOT EXISTS `class_discussion_like` (
  `like_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cd_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `like_type` enum('like','dislike') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`like_id`),
  UNIQUE KEY `cd_id_fk_user_id_fk` (`cd_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_discussion_like`
--

INSERT INTO `class_discussion_like` (`like_id`, `cd_id_fk`, `user_id_fk`, `like_type`) VALUES
(1, 1, 12, 'like'),
(2, 2, 12, 'dislike'),
(3, 3, 63, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `class_discussion_photo`
--

DROP TABLE IF EXISTS `class_discussion_photo`;
CREATE TABLE IF NOT EXISTS `class_discussion_photo` (
  `photo_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cd_id_fk` int NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `photo_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_discussion_photo`
--

INSERT INTO `class_discussion_photo` (`photo_id`, `cd_id_fk`, `photo_path`, `photo_order`) VALUES
(1, 1, 'cdp_1780607058_7078.png', 0),
(2, 1, 'cdp_1780607058_4294.png', 1),
(3, 1, 'cdp_1780607058_3220.png', 2),
(4, 1, 'cdp_1780607058_1313.png', 3),
(5, 1, 'cdp_1780607058_9139.png', 4),
(6, 3, 'cdp_1780727360_3555.jpg', 0),
(7, 3, 'cdp_1780727360_7059.jpg', 1),
(8, 3, 'cdp_1780727360_1092.jpg', 2),
(9, 3, 'cdp_1780727360_9062.jpg', 3),
(10, 3, 'cdp_1780727360_5480.jpg', 4),
(11, 4, 'cdp_1781840701_3894.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `conduct_actions`
--

DROP TABLE IF EXISTS `conduct_actions`;
CREATE TABLE IF NOT EXISTS `conduct_actions` (
  `action_id` int NOT NULL AUTO_INCREMENT,
  `incident_id` int DEFAULT NULL,
  `action_type` varchar(50) DEFAULT NULL,
  `action_date` date DEFAULT NULL,
  `duration_hours` decimal(5,2) DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `notes` text,
  PRIMARY KEY (`action_id`),
  KEY `incident_id` (`incident_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conduct_appeals`
--

DROP TABLE IF EXISTS `conduct_appeals`;
CREATE TABLE IF NOT EXISTS `conduct_appeals` (
  `appeal_id` int NOT NULL AUTO_INCREMENT,
  `incident_id` int DEFAULT NULL,
  `student_id` int DEFAULT NULL,
  `appeal_reason` text COLLATE utf8mb4_general_ci,
  `appeal_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `submitted_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewed_by` int DEFAULT NULL,
  `reviewed_date` datetime DEFAULT NULL,
  `review_notes` text COLLATE utf8mb4_general_ci,
  `points_restored` int DEFAULT '0',
  PRIMARY KEY (`appeal_id`),
  KEY `incident_id` (`incident_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conduct_appeals`
--

INSERT INTO `conduct_appeals` (`appeal_id`, `incident_id`, `student_id`, `appeal_reason`, `appeal_status`, `submitted_date`, `reviewed_by`, `reviewed_date`, `review_notes`, `points_restored`) VALUES
(1, 2, 34, 'This is a mistake. I did not commit this offence.', 'Pending', '2026-06-30 18:32:04', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `conduct_appeal_files`
--

DROP TABLE IF EXISTS `conduct_appeal_files`;
CREATE TABLE IF NOT EXISTS `conduct_appeal_files` (
  `appeal_file_id` int NOT NULL AUTO_INCREMENT,
  `appeal_id` int DEFAULT NULL,
  `file_src` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_type` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`appeal_file_id`),
  KEY `appeal_id` (`appeal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conduct_incidents`
--

DROP TABLE IF EXISTS `conduct_incidents`;
CREATE TABLE IF NOT EXISTS `conduct_incidents` (
  `incident_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int DEFAULT NULL,
  `staff_id` int DEFAULT NULL,
  `type_id_fk` int DEFAULT NULL,
  `points_awarded` int DEFAULT NULL,
  `incident_description` text,
  `incident_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `location` varchar(100) DEFAULT NULL,
  `is_resolved` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`incident_id`),
  KEY `student_id` (`student_id`),
  KEY `staff_id` (`staff_id`),
  KEY `type_id_fk` (`type_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `conduct_incidents`
--

INSERT INTO `conduct_incidents` (`incident_id`, `student_id`, `staff_id`, `type_id_fk`, `points_awarded`, `incident_description`, `incident_date`, `location`, `is_resolved`) VALUES
(1, 30, 63, 37, -20, 'test description', '2026-06-30 17:45:00', 'Room 5B', 1),
(2, 34, 63, 39, -15, 'Regular exercise provides numerous benefits for both physical and mental health. Physically, frequent movement strengthens the cardiovascular system, increases muscle tone, and helps maintain a healthy weight. Mentally, physical activity triggers the release of endorphins, which are natural chemicals in the brain that actively reduce stress and elevate mood. For instance, a simple thirty-minute daily walk can significantly lower the risk of chronic illnesses while simultaneously sharpening focus and improving sleep quality. Ultimately, integrating consistent exercise into a daily routine serves as an essential foundation for a longer, healthier life.', '2026-06-30 18:12:00', 'Home', 0),
(3, 34, 63, 38, -20, '', '2026-06-30 19:35:00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `conduct_incident_file`
--

DROP TABLE IF EXISTS `conduct_incident_file`;
CREATE TABLE IF NOT EXISTS `conduct_incident_file` (
  `conduct_file_id` int NOT NULL AUTO_INCREMENT,
  `incident_id_fk` int DEFAULT NULL,
  `file_src` varchar(260) DEFAULT NULL,
  `file_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`conduct_file_id`),
  KEY `incident_id_fk` (`incident_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `conduct_incident_file`
--

INSERT INTO `conduct_incident_file` (`conduct_file_id`, `incident_id_fk`, `file_src`, `file_type`) VALUES
(1, 1, 'conduct_1_1782841600_9026.png', 'image/png'),
(2, 1, 'conduct_1_1782841600_4564.jpg', 'image/jpeg'),
(3, 1, 'conduct_1_1782841600_3493.jpg', 'image/jpeg'),
(4, 1, 'conduct_1_1782841600_9396.jpg', 'image/jpeg'),
(5, 2, 'conduct_2_1782843227_6059.png', 'image/png'),
(6, 2, 'conduct_2_1782843227_1588.png', 'image/png'),
(7, 2, 'conduct_2_1782843227_3418.png', 'image/png'),
(8, 2, 'conduct_2_1782843227_2915.png', 'image/png'),
(9, 2, 'conduct_2_1782843227_3856.png', 'image/png'),
(10, 2, 'conduct_2_1782843227_8861.png', 'image/png'),
(11, 2, 'conduct_2_1782843227_9241.png', 'image/png'),
(12, 2, 'conduct_2_1782843227_9268.png', 'image/png'),
(13, 2, 'conduct_2_1782843227_6807.png', 'image/png'),
(14, 2, 'conduct_2_1782843227_8232.png', 'image/png'),
(15, 2, 'conduct_2_1782843227_6930.png', 'image/png'),
(16, 2, 'conduct_2_1782843227_3726.png', 'image/png'),
(17, 2, 'conduct_2_1782843227_6766.png', 'image/png'),
(18, 2, 'conduct_2_1782843227_1600.png', 'image/png'),
(19, 2, 'conduct_2_1782843227_6602.png', 'image/png'),
(20, 2, 'conduct_2_1782843227_2959.png', 'image/png'),
(21, 2, 'conduct_2_1782843227_8021.png', 'image/png'),
(22, 2, 'conduct_2_1782843227_3571.png', 'image/png'),
(23, 2, 'conduct_2_1782843227_6654.png', 'image/png'),
(24, 2, 'conduct_2_1782843227_4605.pdf', 'applicatio'),
(25, 3, 'conduct_3_1782848129_7057.jpg', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `conduct_notifications`
--

DROP TABLE IF EXISTS `conduct_notifications`;
CREATE TABLE IF NOT EXISTS `conduct_notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `incident_id` int DEFAULT NULL,
  `recipient_type` varchar(20) DEFAULT NULL,
  `sent_via` varchar(20) DEFAULT NULL,
  `sent_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `message_preview` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`notification_id`),
  KEY `incident_id` (`incident_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conduct_types`
--

DROP TABLE IF EXISTS `conduct_types`;
CREATE TABLE IF NOT EXISTS `conduct_types` (
  `type_id` int NOT NULL AUTO_INCREMENT,
  `type_name` varchar(100) DEFAULT NULL,
  `category` varchar(60) NOT NULL,
  `is_positive` tinyint(1) DEFAULT '0',
  `default_points` int DEFAULT NULL,
  `severity_level` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `conduct_types`
--

INSERT INTO `conduct_types` (`type_id`, `type_name`, `category`, `is_positive`, `default_points`, `severity_level`) VALUES
(1, 'Excellent Class Participation', 'Academic', 1, 5, 'Positive'),
(2, 'Significant Academic Improvement', 'Academic', 1, 10, 'Positive'),
(3, 'Outstanding Homework/Project Submission', 'Academic', 1, 5, 'Positive'),
(4, 'Perfect Attendance (Weekly/Monthly)', 'Academic', 1, 10, 'Positive'),
(5, 'Academic Excellence (Top Score in Test/Quiz)', 'Academic', 1, 15, 'Positive'),
(6, 'Showing Strong Critical Thinking', 'Academic', 1, 5, 'Positive'),
(7, 'Consistently Prepared for Class', 'Academic', 1, 3, 'Positive'),
(8, 'Helping a Peer with Classwork', 'Social', 1, 5, 'Positive'),
(9, 'Demonstrating Excellent Teamwork/Collaboration', 'Social', 1, 5, 'Positive'),
(10, 'Showing Kindness to a Fellow Student', 'Social', 1, 5, 'Positive'),
(11, 'Mediating a Conflict Peacefully', 'Social', 1, 10, 'Positive'),
(12, 'Including an Excluded Student', 'Social', 1, 10, 'Positive'),
(13, 'Demonstrating Outstanding Leadership', 'Social', 1, 10, 'Positive'),
(14, 'Being a Positive Role Model', 'Social', 1, 5, 'Positive'),
(15, 'Exceptional Honesty/Integrity', 'Personal', 1, 10, 'Positive'),
(16, 'Demonstrating Strong Perseverance/Resilience', 'Personal', 1, 5, 'Positive'),
(17, 'Taking Initiative Without Being Asked', 'Personal', 1, 5, 'Positive'),
(18, 'Showing Outstanding Effort', 'Personal', 1, 5, 'Positive'),
(19, 'Excellent Self-Regulation', 'Personal', 1, 5, 'Positive'),
(20, 'Demonstrating a Growth Mindset', 'Personal', 1, 5, 'Positive'),
(21, 'Outstanding Contribution to a School Event', 'Community', 1, 10, 'Positive'),
(22, 'Excellent Service to the School Community', 'Community', 1, 10, 'Positive'),
(23, 'Representing the School in Sports/Arts/Academics', 'Community', 1, 15, 'Positive'),
(24, 'Excellent Stewardship', 'Community', 1, 5, 'Positive'),
(25, 'Exceptional School Spirit', 'Community', 1, 3, 'Positive'),
(26, 'Tardiness (Unexcused)', 'Attendance', 0, -2, 'Minor'),
(27, 'Truancy/Cutting Class', 'Attendance', 0, -15, 'Major'),
(28, 'Leaving School Without Permission', 'Attendance', 0, -15, 'Major'),
(29, 'Excessive Absenteeism', 'Attendance', 0, -20, 'Major'),
(30, 'Skipping Detention', 'Attendance', 0, -10, 'Major'),
(31, 'Disruptive Classroom Behavior', 'Disrespect', 0, -3, 'Minor'),
(32, 'Insubordination/Defiance', 'Disrespect', 0, -5, 'Minor'),
(33, 'Inappropriate Language/Profanity', 'Disrespect', 0, -5, 'Minor'),
(34, 'Disrespect Toward Staff', 'Disrespect', 0, -10, 'Major'),
(35, 'Disrespect Toward Students', 'Disrespect', 0, -5, 'Minor'),
(36, 'Horseplay/Reckless Behavior', 'Disrespect', 0, -3, 'Minor'),
(37, 'Cheating on Tests/Assignments', 'Academic', 0, -20, 'Major'),
(38, 'Plagiarism', 'Academic', 0, -20, 'Major'),
(39, 'Forgery/Falsifying Documents', 'Academic', 0, -15, 'Major'),
(40, 'Lying to School Personnel', 'Academic', 0, -10, 'Major'),
(41, 'Sharing Homework Inappropriately', 'Academic', 0, -5, 'Minor'),
(42, 'Physical Fighting', 'Conflict', 0, -30, 'Critical'),
(43, 'Verbal Altercation/Threats', 'Conflict', 0, -20, 'Major'),
(44, 'Bullying (Physical, Verbal, Social)', 'Conflict', 0, -30, 'Critical'),
(45, 'Cyberbullying', 'Conflict', 0, -30, 'Critical'),
(46, 'Intimidation/Harassment', 'Conflict', 0, -25, 'Critical'),
(47, 'Throwing Objects in Anger', 'Conflict', 0, -20, 'Major'),
(48, 'Theft', 'Property', 0, -30, 'Critical'),
(49, 'Vandalism/Damaging Property', 'Property', 0, -25, 'Critical'),
(50, 'Graffiti', 'Property', 0, -20, 'Major'),
(51, 'Misuse of School Equipment', 'Property', 0, -10, 'Major'),
(52, 'Unauthorized Use of Personal Devices in Class', 'Property', 0, -5, 'Minor'),
(53, 'Vaping/Smoking on Campus', 'Safety', 0, -25, 'Critical'),
(54, 'Possession of Alcohol/Illegal Substances', 'Safety', 0, -35, 'Critical'),
(55, 'Possession of Weapons/Dangerous Objects', 'Safety', 0, -40, 'Critical'),
(56, 'Violating Fire/Safety Drills', 'Safety', 0, -15, 'Major'),
(57, 'Endangering Others', 'Safety', 0, -25, 'Critical'),
(58, 'Dress Code Violation (Minor)', 'Uniform', 0, -2, 'Minor'),
(59, 'Dress Code Violation (Repeated)', 'Uniform', 0, -5, 'Minor'),
(60, 'Wearing Inappropriate Accessories', 'Uniform', 0, -2, 'Minor'),
(61, 'Unauthorized Recording/Photography', 'Technology', 0, -10, 'Major'),
(62, 'Inappropriate Internet Use', 'Technology', 0, -15, 'Major'),
(63, 'Accessing Prohibited Websites', 'Technology', 0, -10, 'Major'),
(64, 'Sharing Passwords/Account Misuse', 'Technology', 0, -10, 'Major');

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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `enrolment`
--

INSERT INTO `enrolment` (`enrol_id`, `admission_id_fk`, `stream_id_fk`, `enrol_date`, `enrol_time`, `enrol_term`, `enrol_year`, `enrol_note`, `enrol_status`) VALUES
(8, 12, 100, '2026-05-12', NULL, 1, 2026, NULL, 'Completed'),
(9, 10, 91, '2026-05-26', 1779756947, 2, 2026, NULL, 'Completed'),
(10, 17, 91, '2026-05-27', 1779836663, 2, 2026, '', 'Completed'),
(11, 18, 91, '2026-05-27', 1779838403, 2, 2026, '', 'Completed'),
(12, 19, 91, '2026-05-28', 1779913023, 2, 2026, '', 'Completed'),
(13, 20, 91, '2026-05-28', 1779913079, 2, 2026, '', 'Active'),
(14, 21, 91, '2026-05-28', 1779913118, 2, 2026, '', 'Active'),
(15, 22, 91, '2026-05-28', 1779913168, 2, 2026, '', 'Active'),
(16, 23, 91, '2026-05-28', 1779913233, 2, 2026, '', 'Active'),
(17, 16, 95, '2026-06-03', 1780435522, 2, 2026, NULL, 'Active'),
(18, 31, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Completed'),
(19, 32, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(20, 32, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(21, 34, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Completed'),
(22, 35, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(23, 36, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(24, 37, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(25, 38, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(26, 39, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(27, 40, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(28, 41, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(29, 42, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(30, 43, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(31, 44, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(32, 45, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(33, 46, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(34, 47, 124, '2026-06-06', NULL, 2, 2026, NULL, 'Active'),
(35, 49, 91, '2026-06-18', 1781731976, 2, 2026, '', 'Active'),
(36, 50, 91, '2026-06-18', 1781751375, 2, 2026, '', 'Active'),
(37, 51, 91, '2026-06-18', 1781752541, 2, 2026, '', 'Completed'),
(38, 52, 91, '2026-06-18', 1781753182, 2, 2026, '', 'Completed'),
(39, 29, 125, '2026-06-19', 1781830395, 2, 2026, NULL, 'Active'),
(40, 30, 125, '2026-06-19', 1781830420, 2, 2026, NULL, 'Active'),
(41, 33, 125, '2026-06-19', 1781830435, 2, 2026, NULL, 'Active'),
(42, 34, 125, '2026-06-19', 1781832435, 2, 2026, NULL, 'Completed'),
(43, 34, 125, '2026-06-19', 1781836751, 2, 2026, NULL, 'Active'),
(44, 53, 125, '2026-06-25', 1782344553, 2, 2026, '', 'Active'),
(45, 54, 125, '2026-06-25', 1782346401, 2, 2026, '', 'Active'),
(46, 55, 99, '2026-06-25', 1782356737, 2, 2026, '', 'Active'),
(47, 56, 125, '2026-06-25', 1782357199, 1, 2026, '', 'Active'),
(48, 57, 125, '2026-06-25', 1782358108, 1, 2026, '', 'Active'),
(49, 58, 125, '2026-06-25', 1782358573, 1, 2026, '', 'Active'),
(50, 59, 125, '2026-06-26', 1782402270, 2, 2026, '', 'Active'),
(51, 31, 125, '2026-06-26', 1782418149, 2, 2026, NULL, 'Completed'),
(53, 31, 125, '2026-06-26', 1782418554, 2, 2026, NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

DROP TABLE IF EXISTS `exam`;
CREATE TABLE IF NOT EXISTS `exam` (
  `exam_id` int NOT NULL AUTO_INCREMENT,
  `exam_name` varchar(260) NOT NULL,
  `level_id_fk` int NOT NULL,
  `exam_status` varchar(60) NOT NULL,
  PRIMARY KEY (`exam_id`),
  KEY `level_id_fk` (`level_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`exam_id`, `exam_name`, `level_id_fk`, `exam_status`) VALUES
(1, 'Fiji Intermediate Examination', 8, 'Active'),
(2, 'Fiji Eighth Year Examinination', 10, 'Active'),
(3, 'Fiji Junior Examination', 12, 'Active'),
(4, 'Fiji School Leaving Certificate', 14, 'Active'),
(5, 'Fiji Year 13 Certificate Examination', 15, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `exam_mark`
--

DROP TABLE IF EXISTS `exam_mark`;
CREATE TABLE IF NOT EXISTS `exam_mark` (
  `exam_sub_id` int NOT NULL AUTO_INCREMENT,
  `exam_reg_id_fk` int NOT NULL,
  `stud_sub_id_fk` int NOT NULL,
  `exam_mark` int NOT NULL,
  PRIMARY KEY (`exam_sub_id`),
  KEY `fk_exam_reg_id` (`exam_reg_id_fk`),
  KEY `fk_stud_sub_id` (`stud_sub_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exam_mark`
--

INSERT INTO `exam_mark` (`exam_sub_id`, `exam_reg_id_fk`, `stud_sub_id_fk`, `exam_mark`) VALUES
(6, 27, 82, 59),
(7, 27, 83, 66),
(8, 27, 84, 75),
(9, 27, 86, 65),
(10, 27, 85, 70),
(11, 25, 93, 60),
(12, 25, 94, 79),
(13, 25, 95, 69),
(14, 25, 96, 80),
(15, 9, 110, 78),
(16, 9, 111, 65),
(17, 9, 112, 70),
(18, 9, 114, 80),
(19, 9, 113, 68);

-- --------------------------------------------------------

--
-- Table structure for table `exam_registration`
--

DROP TABLE IF EXISTS `exam_registration`;
CREATE TABLE IF NOT EXISTS `exam_registration` (
  `exam_reg_id` int NOT NULL AUTO_INCREMENT,
  `exam_id_fk` int NOT NULL,
  `admission_id_fk` int NOT NULL,
  `exam_year` int NOT NULL,
  `stud_index_num` int NOT NULL,
  PRIMARY KEY (`exam_reg_id`),
  UNIQUE KEY `uk_stud_index_num` (`stud_index_num`),
  KEY `fk_exam_id` (`exam_id_fk`),
  KEY `fk_admission_id` (`admission_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exam_registration`
--

INSERT INTO `exam_registration` (`exam_reg_id`, `exam_id_fk`, `admission_id_fk`, `exam_year`, `stud_index_num`) VALUES
(1, 5, 29, 2026, 49160521),
(2, 5, 30, 2026, 78806318),
(3, 5, 31, 2026, 80120954),
(4, 5, 32, 2026, 20000161),
(5, 5, 33, 2026, 88738429),
(6, 5, 35, 2026, 19342829),
(7, 5, 47, 2026, 98688244),
(8, 5, 46, 2026, 88486279),
(9, 5, 34, 2026, 34040855),
(10, 5, 45, 2026, 70459084),
(11, 5, 36, 2026, 16249148),
(12, 5, 37, 2026, 75682095),
(13, 5, 38, 2026, 76037528),
(14, 5, 39, 2026, 62399170),
(15, 5, 40, 2026, 19910657),
(16, 5, 41, 2026, 51988862),
(17, 5, 42, 2026, 36344153),
(18, 5, 43, 2026, 96838127),
(19, 5, 53, 2026, 64744990),
(20, 5, 44, 2026, 60113315),
(22, 5, 54, 2026, 40349587),
(23, 5, 55, 2026, 28758680),
(24, 5, 56, 2026, 38271454),
(25, 5, 57, 2026, 88951861),
(26, 5, 58, 2026, 47762684),
(27, 5, 59, 2026, 38679208);

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
-- Table structure for table `lesson_assignment`
--

DROP TABLE IF EXISTS `lesson_assignment`;
CREATE TABLE IF NOT EXISTS `lesson_assignment` (
  `assignment_id` int NOT NULL AUTO_INCREMENT,
  `class_sub_id_fk` int NOT NULL,
  `class_id_fk` int DEFAULT NULL,
  `assignment_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assignment_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assignment_due_date` datetime DEFAULT NULL,
  `assignment_total_score` decimal(5,2) NOT NULL DEFAULT '100.00',
  `assignment_status` enum('Draft','Published','Archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`assignment_id`),
  KEY `idx_class_sub` (`class_sub_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lesson_assignment`
--

INSERT INTO `lesson_assignment` (`assignment_id`, `class_sub_id_fk`, `class_id_fk`, `assignment_name`, `assignment_file`, `assignment_due_date`, `assignment_total_score`, `assignment_status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 7, 3, 'Lesson 1 Assignment', '1780524090_d8c25973abec82d749a9.pdf', '2026-06-05 11:00:00', 100.00, 'Draft', '2026-06-04 10:01:30', 12, NULL, NULL),
(2, 7, 3, 'Assignment 2', '1780524214_101a59617563034d0b84.pdf', '2026-06-30 12:00:00', 100.00, 'Draft', '2026-06-04 10:03:34', 12, NULL, NULL),
(3, 7, 3, 'Assignment 3', '1780526161_98db769cb8cd62ff6f2d.pdf', '2026-07-31 12:00:00', 100.00, 'Published', '2026-06-04 10:04:08', 12, '2026-06-04 10:36:27', 12),
(4, 4, 3, 'Assignment 1', '1780653943_4f3a029fde5b58a7257a.pdf', '2026-07-03 12:00:00', 100.00, 'Draft', '2026-06-05 22:05:43', 12, NULL, NULL),
(5, 47, 5, 'Assignment 1', NULL, '2026-08-08 12:00:00', 100.00, 'Published', '2026-07-01 09:44:28', 63, '2026-07-01 09:47:26', 63),
(6, 47, 5, 'Assignment 2', NULL, '2026-08-07 12:00:00', 100.00, 'Published', '2026-07-01 09:51:34', 63, '2026-07-01 09:53:25', 63),
(7, 47, 5, 'Assignment 3', NULL, '2026-08-07 12:00:00', 100.00, 'Published', '2026-07-01 10:24:17', 63, '2026-07-01 10:24:30', 63),
(8, 47, 5, 'Assigment 4', NULL, '2026-08-06 12:00:00', 100.00, 'Draft', '2026-07-01 10:24:57', 63, NULL, NULL),
(9, 47, 5, 'adfadfsdf', NULL, '2026-07-15 12:00:00', 100.00, 'Draft', '2026-07-01 10:55:51', 63, NULL, NULL),
(10, 47, 5, 'fgdfgfg', NULL, '2026-08-06 12:00:00', 100.00, 'Draft', '2026-07-01 10:56:06', 63, NULL, NULL),
(11, 47, 5, 'gfsgsfgsfdg', NULL, '2026-08-06 12:00:00', 100.00, 'Draft', '2026-07-01 10:56:19', 63, NULL, NULL),
(12, 47, 5, 'sfgfgsdgsdg', NULL, '2026-08-07 12:00:00', 100.00, 'Draft', '2026-07-01 10:56:37', 63, NULL, NULL),
(13, 47, 5, 'sfgsgsdg', NULL, '2026-07-30 12:00:00', 100.00, 'Draft', '2026-07-01 10:56:46', 63, NULL, NULL),
(14, 47, 5, 'sdgsdgsdg', NULL, '2026-07-28 12:00:00', 100.00, 'Draft', '2026-07-01 10:56:55', 63, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_assignment_file`
--

DROP TABLE IF EXISTS `lesson_assignment_file`;
CREATE TABLE IF NOT EXISTS `lesson_assignment_file` (
  `assign_file_id` int NOT NULL AUTO_INCREMENT,
  `assignment_id_fk` int NOT NULL,
  `file_src` varchar(260) NOT NULL,
  `file_type` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`assign_file_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_assignment_file`
--

INSERT INTO `lesson_assignment_file` (`assign_file_id`, `assignment_id_fk`, `file_src`, `file_type`) VALUES
(1, 5, '1782855868_de4bfe0b2b4690d0ecdb.png', '.png'),
(2, 5, '1782855868_ce246895e5511e45732b.png', '.png'),
(3, 5, '1782855868_180d488f526dc5608b94.docx', '.docx'),
(4, 5, '1782855868_4948aa90f839e60fed14.pdf', '.pdf'),
(5, 5, '1782855868_f475ae207f1335bd6c24.txt', '.txt'),
(6, 6, '1782856294_92640ca065da7368eba3.pdf', '1'),
(7, 6, '1782856294_17112530db435358fad5.pdf', '1'),
(8, 6, '1782856294_f89039c8cfeda237780b.docx', '1'),
(9, 6, '1782856294_b8af265428fd8241e562.png', '2'),
(10, 6, '1782856294_1bac131e749b6ff0449f.jpg', '2'),
(11, 6, '1782856294_b920878909e07302ce6d.xlsx', '3'),
(12, 7, '1782858257_960e909695afd36c7b27.pdf', 'pdf'),
(13, 7, '1782858257_308c05a17712793b6d23.pdf', 'pdf'),
(14, 7, '1782858257_0e50bcf01860e5b30d31.pdf', 'pdf'),
(15, 8, '1782858297_f2d080e3321246d4c2ad.pdf', 'pdf'),
(16, 9, '1782860151_cf3d2d0fa2559863fe38.pdf', 'pdf'),
(17, 10, '1782860166_4171210d0568d664ef5e.docx', 'docx'),
(18, 11, '1782860179_352c9668bdb48d00d237.pdf', 'pdf'),
(19, 12, '1782860197_29a2c38c02537b4425c2.docx', 'docx'),
(20, 13, '1782860206_a2fde064f176b3ef2263.pdf', 'pdf');

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_discussion`
--

INSERT INTO `lesson_discussion` (`lesson_discussion_id`, `lesson_id_fk`, `author`, `message`, `created_at`, `updated_at`, `created_time`, `message_status`) VALUES
(7, 11, 63, 'dddddd', '2026-06-19 15:24:36', '2026-06-19 15:24:36', 1781839476, 1),
(8, 10, 48, 'test', '2026-07-01 03:53:05', '2026-07-01 03:53:05', 1782834785, 1),
(6, 10, 63, 'Dou bula ragone, welcome to navuli e learning module called My Classroom.', '2026-06-09 09:23:55', '2026-06-09 09:23:55', 1780953835, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_discussion_comment`
--

INSERT INTO `lesson_discussion_comment` (`comment_id`, `discussion_id_fk`, `author`, `comment`, `created_at`, `comment_status`) VALUES
(8, 6, 48, 'ok sir nnnnn', '2026-06-18 19:33:19', 'Active');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_dragdrop_answer`
--

DROP TABLE IF EXISTS `lesson_dragdrop_answer`;
CREATE TABLE IF NOT EXISTS `lesson_dragdrop_answer` (
  `answer_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int UNSIGNED NOT NULL,
  `item_id_fk` int UNSIGNED NOT NULL,
  `zone_id_fk` int UNSIGNED NOT NULL,
  PRIMARY KEY (`answer_id`),
  UNIQUE KEY `item_id_fk` (`item_id_fk`),
  KEY `quizze_id_fk` (`quizze_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_dragdrop_answer`
--

INSERT INTO `lesson_dragdrop_answer` (`answer_id`, `quizze_id_fk`, `item_id_fk`, `zone_id_fk`) VALUES
(15, 11, 15, 15),
(14, 11, 14, 14),
(13, 11, 13, 13);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_dragdrop_attempt`
--

DROP TABLE IF EXISTS `lesson_dragdrop_attempt`;
CREATE TABLE IF NOT EXISTS `lesson_dragdrop_attempt` (
  `attempt_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int UNSIGNED NOT NULL,
  `lesson_id_fk` int UNSIGNED NOT NULL,
  `user_id_fk` int UNSIGNED NOT NULL,
  `started_at` datetime NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'in_progress',
  `score` decimal(5,2) DEFAULT NULL,
  `total_items` int DEFAULT NULL,
  `correct_items` int DEFAULT NULL,
  PRIMARY KEY (`attempt_id`),
  KEY `quizze_id_fk_user_id_fk` (`quizze_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_dragdrop_attempt`
--

INSERT INTO `lesson_dragdrop_attempt` (`attempt_id`, `quizze_id_fk`, `lesson_id_fk`, `user_id_fk`, `started_at`, `submitted_at`, `status`, `score`, `total_items`, `correct_items`) VALUES
(4, 11, 10, 48, '2026-06-19 09:05:00', '2026-06-19 09:05:17', 'submitted', 33.33, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_dragdrop_attempt_item`
--

DROP TABLE IF EXISTS `lesson_dragdrop_attempt_item`;
CREATE TABLE IF NOT EXISTS `lesson_dragdrop_attempt_item` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attempt_id_fk` int UNSIGNED NOT NULL,
  `item_id_fk` int UNSIGNED NOT NULL,
  `zone_id_fk` int UNSIGNED DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `attempt_id_fk` (`attempt_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_dragdrop_attempt_item`
--

INSERT INTO `lesson_dragdrop_attempt_item` (`id`, `attempt_id_fk`, `item_id_fk`, `zone_id_fk`, `is_correct`) VALUES
(15, 4, 15, 14, 0),
(14, 4, 14, 15, 0),
(13, 4, 13, 13, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_dragdrop_item`
--

DROP TABLE IF EXISTS `lesson_dragdrop_item`;
CREATE TABLE IF NOT EXISTS `lesson_dragdrop_item` (
  `item_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int UNSIGNED NOT NULL,
  `item_text` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `item_image` varchar(260) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `item_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`),
  KEY `quizze_id_fk` (`quizze_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_dragdrop_item`
--

INSERT INTO `lesson_dragdrop_item` (`item_id`, `quizze_id_fk`, `item_text`, `item_image`, `item_order`) VALUES
(13, 11, 'Nai yau cava e dau vakayagataki ena i qaloqalovi?', NULL, 1),
(14, 11, 'Nai yau cava e dau vakayagataki e na i sevusevu?', NULL, 2),
(15, 11, 'Na cava e da dau cavuta ni da tama?', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_dragdrop_zone`
--

DROP TABLE IF EXISTS `lesson_dragdrop_zone`;
CREATE TABLE IF NOT EXISTS `lesson_dragdrop_zone` (
  `zone_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int UNSIGNED NOT NULL,
  `zone_label` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `zone_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`zone_id`),
  KEY `quizze_id_fk` (`quizze_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_dragdrop_zone`
--

INSERT INTO `lesson_dragdrop_zone` (`zone_id`, `quizze_id_fk`, `zone_label`, `zone_order`) VALUES
(15, 11, 'Ua...oi..oi..oi', 3),
(14, 11, 'Yaqona', 2),
(13, 11, 'Tabua', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_file`
--

INSERT INTO `lesson_file` (`file_id`, `lesson_id_fk`, `file_path`, `file_name`, `file_type`, `file_size`, `uploaded_at`, `uploaded_by`) VALUES
(20, 10, 'lesson_10_1780952080_4181.xlsx', 'Bbq Orders 2025', 'xlsx', 9973, '2026-06-09 08:54:40', 63),
(21, 10, 'lesson_10_1780952080_1551.docx', 'Betty Kava Barrel Ticket', 'docx', 32199, '2026-06-09 08:54:40', 63),
(22, 10, 'lesson_10_1780952080_1129.pdf', 'Betty Kava Barrel Ticket', 'pdf', 48737, '2026-06-09 08:54:40', 63),
(23, 10, 'lesson_10_1780952090_3043.png', 'Delete May Fb Email', 'png', 34679, '2026-06-09 08:54:50', 63),
(24, 10, 'lesson_10_1780952090_4715.png', 'Dns', 'png', 52650, '2026-06-09 08:54:50', 63),
(25, 10, 'lesson_10_1780952090_5791.png', 'Domain', 'png', 106701, '2026-06-09 08:54:50', 63),
(26, 10, 'lesson_10_1780952090_4940.png', 'Email 1', 'png', 331333, '2026-06-09 08:54:50', 63),
(27, 10, 'lesson_10_1780952090_8108.png', 'Eoi Email', 'png', 53846, '2026-06-09 08:54:50', 63),
(28, 11, 'lesson_11_1781839405_4009.docx', 'Creditor Vendor Setup Form Eft', 'docx', 50094, '2026-06-19 15:23:25', 63);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_label_attempt`
--

DROP TABLE IF EXISTS `lesson_label_attempt`;
CREATE TABLE IF NOT EXISTS `lesson_label_attempt` (
  `attempt_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int UNSIGNED NOT NULL,
  `lesson_id_fk` int UNSIGNED NOT NULL,
  `user_id_fk` int UNSIGNED NOT NULL,
  `started_at` datetime NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'in_progress',
  `score` decimal(5,2) DEFAULT NULL,
  `total_markers` int DEFAULT NULL,
  `correct_markers` int DEFAULT NULL,
  PRIMARY KEY (`attempt_id`),
  KEY `quizze_id_fk_user_id_fk` (`quizze_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_label_attempt_answer`
--

DROP TABLE IF EXISTS `lesson_label_attempt_answer`;
CREATE TABLE IF NOT EXISTS `lesson_label_attempt_answer` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attempt_id_fk` int UNSIGNED NOT NULL,
  `marker_id_fk` int UNSIGNED NOT NULL,
  `student_label` varchar(300) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `attempt_id_fk` (`attempt_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_label_marker`
--

DROP TABLE IF EXISTS `lesson_label_marker`;
CREATE TABLE IF NOT EXISTS `lesson_label_marker` (
  `marker_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `label_question_id_fk` int UNSIGNED NOT NULL,
  `marker_x` decimal(5,2) NOT NULL,
  `marker_y` decimal(5,2) NOT NULL,
  `correct_label` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `marker_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`marker_id`),
  KEY `label_question_id_fk` (`label_question_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_label_marker`
--

INSERT INTO `lesson_label_marker` (`marker_id`, `label_question_id_fk`, `marker_x`, `marker_y`, `correct_label`, `marker_order`) VALUES
(9, 3, 48.79, 86.65, 'Batini tovuto', 2),
(8, 3, 50.50, 20.36, 'Magimagi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_label_question`
--

DROP TABLE IF EXISTS `lesson_label_question`;
CREATE TABLE IF NOT EXISTS `lesson_label_question` (
  `label_question_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int UNSIGNED NOT NULL,
  `question_text` varchar(500) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `bg_image` varchar(260) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `question_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`label_question_id`),
  KEY `quizze_id_fk` (`quizze_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_label_question`
--

INSERT INTO `lesson_label_question` (`label_question_id`, `quizze_id_fk`, `question_text`, `bg_image`, `question_order`) VALUES
(3, 12, 'Volavola', 'lbl_12_q_1780952878_542.jpg', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_link`
--

INSERT INTO `lesson_link` (`link_id`, `lesson_id_fk`, `link_url`, `link_title`, `link_order`) VALUES
(3, 10, 'https://www.vinodpatel.com.fj/', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_quizze`
--

DROP TABLE IF EXISTS `lesson_quizze`;
CREATE TABLE IF NOT EXISTS `lesson_quizze` (
  `lesson_quizze_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id_fk` int NOT NULL,
  `assessment_type` varchar(60) DEFAULT 'quiz',
  `quizze_name` varchar(260) NOT NULL,
  `quizze_duration` int NOT NULL COMMENT 'time in minutes',
  `quizze_status` varchar(60) NOT NULL,
  PRIMARY KEY (`lesson_quizze_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_quizze`
--

INSERT INTO `lesson_quizze` (`lesson_quizze_id`, `lesson_id_fk`, `assessment_type`, `quizze_name`, `quizze_duration`, `quizze_status`) VALUES
(18, 11, 'quiz', 'Test time', 1, 'Published'),
(12, 10, 'labelling', 'Kamunaga', 5, 'Published'),
(11, 10, 'drag_drop', 'Na Vakacacabo', 5, 'Published'),
(10, 10, 'quiz', 'Vakacacabo', 5, 'Published');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_quizze_answer`
--

DROP TABLE IF EXISTS `lesson_quizze_answer`;
CREATE TABLE IF NOT EXISTS `lesson_quizze_answer` (
  `lesson_quizze_answer_id` int NOT NULL AUTO_INCREMENT,
  `quizze_quest_id_fk` int NOT NULL,
  `answer` varchar(260) NOT NULL,
  `is_correct_answer` tinyint(1) NOT NULL,
  PRIMARY KEY (`lesson_quizze_answer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_quizze_answer`
--

INSERT INTO `lesson_quizze_answer` (`lesson_quizze_answer_id`, `quizze_quest_id_fk`, `answer`, `is_correct_answer`) VALUES
(64, 16, 'i don\'t know', 0),
(63, 16, 'Tapa design', 1),
(62, 16, 'Water', 0),
(61, 16, 'Cloth', 0),
(60, 15, 'James', 0),
(59, 15, 'Paul', 0),
(58, 15, 'Peter', 0),
(57, 15, 'John', 1),
(56, 14, 'Ua...oi..oi..oi', 1),
(55, 14, 'Bogi', 0),
(54, 14, 'Moce', 0),
(53, 14, 'Bula', 0),
(52, 13, 'Magiti', 0),
(51, 13, 'I lavo', 0),
(50, 13, 'Tabua', 0),
(49, 13, 'Yaqona', 1),
(48, 12, 'I lavo', 0),
(47, 12, 'Tabua', 1),
(46, 12, 'Karasini', 0),
(45, 12, 'Yaqona', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_quizze_attempt`
--

DROP TABLE IF EXISTS `lesson_quizze_attempt`;
CREATE TABLE IF NOT EXISTS `lesson_quizze_attempt` (
  `attempt_id` int NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int NOT NULL,
  `lesson_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `started_at` datetime NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `status` enum('in_progress','submitted','timed_out') NOT NULL DEFAULT 'in_progress',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `total_questions` int NOT NULL DEFAULT '0',
  `correct_answers` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`attempt_id`),
  UNIQUE KEY `unique_student_quiz` (`quizze_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_quizze_attempt`
--

INSERT INTO `lesson_quizze_attempt` (`attempt_id`, `quizze_id_fk`, `lesson_id_fk`, `user_id_fk`, `started_at`, `submitted_at`, `status`, `score`, `total_questions`, `correct_answers`) VALUES
(7, 18, 11, 48, '2026-06-19 09:08:56', '2026-06-19 09:09:59', 'timed_out', 50.00, 2, 1),
(6, 10, 10, 48, '2026-06-18 19:31:51', '2026-06-18 19:32:15', 'submitted', 100.00, 3, 3);

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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_quizze_question`
--

INSERT INTO `lesson_quizze_question` (`quizze_quest_id`, `lesson_quizze_id_fk`, `question`, `status`) VALUES
(16, 18, 'What is the image shown?', 'Active'),
(15, 18, 'What is your name?', 'Active'),
(14, 10, 'Na cava eda dau cavuta ni da dau tama?', 'Active'),
(13, 10, 'Nai yau cava e day vakayagataki ena i sevisevu?', 'Active'),
(12, 10, 'Nai yau cava e dau vakayagataki e na i qaloalovi?', 'Active');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_quizze_question_file`
--

INSERT INTO `lesson_quizze_question_file` (`lesson_quizze_quest_file_id`, `quizze_quest_id_fk`, `file_src`, `status`) VALUES
(6, 16, 'quiz_18_q16_1781816865_332.jpg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_quizze_response`
--

DROP TABLE IF EXISTS `lesson_quizze_response`;
CREATE TABLE IF NOT EXISTS `lesson_quizze_response` (
  `response_id` int NOT NULL AUTO_INCREMENT,
  `attempt_id_fk` int NOT NULL,
  `question_id_fk` int NOT NULL,
  `answer_id_fk` int NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`response_id`),
  UNIQUE KEY `unique_response` (`attempt_id_fk`,`question_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_quizze_response`
--

INSERT INTO `lesson_quizze_response` (`response_id`, `attempt_id_fk`, `question_id_fk`, `answer_id_fk`, `is_correct`) VALUES
(18, 7, 16, 63, 1),
(17, 6, 14, 56, 1),
(16, 6, 13, 49, 1),
(15, 6, 12, 47, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_simulation_attempt`
--

DROP TABLE IF EXISTS `lesson_simulation_attempt`;
CREATE TABLE IF NOT EXISTS `lesson_simulation_attempt` (
  `attempt_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int UNSIGNED NOT NULL,
  `lesson_id_fk` int UNSIGNED NOT NULL,
  `user_id_fk` int UNSIGNED NOT NULL,
  `started_at` datetime NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'in_progress',
  `score` decimal(5,2) DEFAULT NULL,
  `total_steps` int DEFAULT NULL,
  `correct_steps` int DEFAULT NULL,
  PRIMARY KEY (`attempt_id`),
  KEY `quizze_id_fk_user_id_fk` (`quizze_id_fk`,`user_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_simulation_attempt_response`
--

DROP TABLE IF EXISTS `lesson_simulation_attempt_response`;
CREATE TABLE IF NOT EXISTS `lesson_simulation_attempt_response` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `attempt_id_fk` int UNSIGNED NOT NULL,
  `step_id_fk` int UNSIGNED NOT NULL,
  `choice_id_fk` int UNSIGNED DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `attempt_id_fk` (`attempt_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_simulation_choice`
--

DROP TABLE IF EXISTS `lesson_simulation_choice`;
CREATE TABLE IF NOT EXISTS `lesson_simulation_choice` (
  `choice_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `step_id_fk` int UNSIGNED NOT NULL,
  `choice_text` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `feedback_text` varchar(600) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `choice_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`choice_id`),
  KEY `step_id_fk` (`step_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_simulation_scenario`
--

DROP TABLE IF EXISTS `lesson_simulation_scenario`;
CREATE TABLE IF NOT EXISTS `lesson_simulation_scenario` (
  `scenario_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quizze_id_fk` int UNSIGNED NOT NULL,
  `scenario_intro` text COLLATE utf8mb4_general_ci,
  `intro_image` varchar(260) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`scenario_id`),
  UNIQUE KEY `quizze_id_fk` (`quizze_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_simulation_step`
--

DROP TABLE IF EXISTS `lesson_simulation_step`;
CREATE TABLE IF NOT EXISTS `lesson_simulation_step` (
  `step_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `scenario_id_fk` int UNSIGNED NOT NULL,
  `step_number` int NOT NULL DEFAULT '1',
  `situation_text` text COLLATE utf8mb4_general_ci NOT NULL,
  `step_image` varchar(260) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`step_id`),
  KEY `scenario_id_fk` (`scenario_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_video`
--

INSERT INTO `lesson_video` (`video_id`, `lesson_id_fk`, `video_url`, `video_title`, `video_order`) VALUES
(5, 10, 'https://youtu.be/_3pijyWUXYg?si=3k7zDn4gyQstynuX', NULL, 1),
(6, 10, 'https://youtu.be/_3pijyWUXYg?si=t2xvmkYd8T4PuQ2r', 'Inside Taliban', 2);

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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-06-24-000000', 'App\\Database\\Migrations\\CreateChatTables', 'default', 'App', 1782245458, 1),
(2, '2026-06-25-000001', 'App\\Database\\Migrations\\CreateStudentExamTables', 'default', 'App', 1782341788, 2),
(3, '2026-06-25-000002', 'App\\Database\\Migrations\\AddEnrolIdToStudentSubject', 'default', 'App', 1782346228, 3),
(4, '2026-06-25-000003', 'App\\Database\\Migrations\\AddUniqueToExamRegistrationIndexNum', 'default', 'App', 1782349222, 4),
(5, '2026-06-25-000004', 'App\\Database\\Migrations\\CreateExamSubjectTable', 'default', 'App', 1782350520, 5),
(6, '2026-06-25-033028', 'App\\Database\\Migrations\\AddSubTypeToExamSubject', 'default', 'App', 1782358261, 6),
(7, '2026-06-30-000001', 'App\\Database\\Migrations\\CreateChatMessageReactionsTable', 'default', 'App', 1782843648, 7),
(8, '2026-06-30-000002', 'App\\Database\\Migrations\\CreateChatUserBlocksTable', 'default', 'App', 1782843648, 7),
(9, '2026-07-01-000001', 'App\\Database\\Migrations\\CreateConductAppealsTable', 'default', 'App', 1782843690, 8),
(10, '2026-07-01-000002', 'App\\Database\\Migrations\\CreateConductAppealFilesTable', 'default', 'App', 1782844786, 9),
(11, '2026-07-01-000003', 'App\\Database\\Migrations\\CreateLessonAssignmentFileTable', 'default', 'App', 1782854718, 10),
(12, '2026-07-01-000004', 'App\\Database\\Migrations\\AlterLessonAssignmentFileTypeToVarchar', 'default', 'App', 1782857848, 11),
(13, '2026-07-01-000005', 'App\\Database\\Migrations\\CreateAssignmentPlagiarismTable', 'default', 'App', 1782864403, 12);

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
(9, 'External Exam', '<i class=\"ki-duotone ki-chart-pie-3 fs-2\"><span class=\"path1\"/><span class=\"path2\"/><span class=\"path3\"/></i>', ''),
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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(67, 10, 'My Conduct', '', 'conduct/my/', '_my_conduct', 1, 'Active', NULL, NULL),
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
(98, 8, 'View Student Subject', 'View student subject or class reriod attendance', 'attendance/subject', '_view_student_subject', 1, 'Active', '2026-05-28', '2026-05-28'),
(99, 9, 'My Exam', 'This is where student can view their external exam results and also where parents can view their childrens exam results as well', 'exam/my/', '_my_exam', 1, 'Active', '2026-06-25', '2026-06-25'),
(100, 10, 'Process Conduct Appeals', 'Review and process student conduct appeals', 'conduct/appeals', '_process_conduct_appeal', 1, 'Active', NULL, NULL);

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
-- Table structure for table `public_holiday`
--

DROP TABLE IF EXISTS `public_holiday`;
CREATE TABLE IF NOT EXISTS `public_holiday` (
  `holiday_id` int NOT NULL AUTO_INCREMENT,
  `holiday_name` varchar(150) NOT NULL,
  `holiday_date` date NOT NULL,
  `observed_date` date DEFAULT NULL,
  `holiday_year` year NOT NULL,
  PRIMARY KEY (`holiday_id`),
  UNIQUE KEY `uq_holiday_date` (`holiday_date`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `public_holiday`
--

INSERT INTO `public_holiday` (`holiday_id`, `holiday_name`, `holiday_date`, `observed_date`, `holiday_year`) VALUES
(1, 'New Year\'s Day', '2026-01-01', NULL, '2026'),
(2, 'National Youth Day', '2026-03-27', NULL, '2026'),
(3, 'Good Friday', '2026-04-03', NULL, '2026'),
(4, 'Holy Saturday', '2026-04-04', '2026-04-02', '2026'),
(5, 'Easter Monday', '2026-04-06', NULL, '2026'),
(6, 'Ratu Sir Lala Sukuna Day', '2026-05-25', NULL, '2026'),
(7, 'Prophet Mohammed\'s Birthday', '2026-09-17', NULL, '2026'),
(8, 'Fiji Day', '2026-10-10', '2026-10-09', '2026'),
(9, 'Diwali', '2026-11-08', '2026-11-09', '2026'),
(10, 'Christmas Day', '2026-12-25', NULL, '2026'),
(11, 'Boxing Day', '2026-12-26', '2026-12-28', '2026');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_cat_id_fk`, `role_name`, `role_desc`, `role_rank`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super Admin', 'The ultimate authority in the system. The Super Admin possesses full, unrestricted access to configure the platform, manage all user accounts, and oversee every aspect of the system\'s data and functionality.', 1, '2026-01-22', '2026-01-22'),
(2, 2, 'School Admin', 'The School Administrator serves as the primary manager of the institution\'s digital ecosystem, overseeing platform configuration, user access, and subscription services. This role is responsible for maintaining the operational integrity of the school\'s account, ensuring seamless access to tools and resources for faculty, staff, and students.', 3, '2026-01-22', '2026-01-22'),
(3, 3, 'Principal', 'The Principal serves as the chief operational leader of the school, responsible for overseeing day-to-day academic and administrative functions while shaping the institution’s educational environment. This role goes beyond administration to directly impact teaching effectiveness, student development, and operational efficiency.', 3, '2026-01-22', '2026-01-22'),
(4, 3, 'HOD', 'A Head of Department is a specialist leader and middle manager focused on a specific academic area (e.g., Math, Science, Industrial Arts). They are responsible for the quality, consistency, and innovation within their subject domain.', 4, '2026-01-28', '2026-01-28'),
(5, 3, 'Assistant Teacher', 'An Assistant Teacher is a vital support professional within the classroom, working under the guidance of the HOD or Principal to facilitate student learning and well-being. This role focuses on implementing, assisting, and reinforcing the educational environment to ensure all students receive individualized attention and support.', 4, '2026-01-28', '2026-01-29'),
(6, 6, 'Parent', 'The Parent role is a dedicated portal that provides real-time access to their child\'s academic and school life, transforming them from passive observers into active, informed participants in the educational process.', 5, '2026-02-03', '2026-02-03'),
(7, 4, 'Student', 'The Student role is a personalized digital dashboard that centralizes a student\'s academic life, fostering independence, organization, and active engagement with their own learning process.', 6, '2026-02-03', '2026-02-03'),
(8, 5, 'Support Staff', 'Support Staff are frontline operational specialists responsible for facilitating seamless technical assistance, user issue resolution, and day-to-day system support to maintain service excellence and client satisfaction.', 4, '2026-01-23', '2026-01-29'),
(16, 7, 'Admin', 'Navuli Fiji Administrator', 2, '2026-06-26', '2026-06-26');

-- --------------------------------------------------------

--
-- Table structure for table `role_category`
--

DROP TABLE IF EXISTS `role_category`;
CREATE TABLE IF NOT EXISTS `role_category` (
  `role_cat_id` int NOT NULL AUTO_INCREMENT,
  `role_cat_name` varchar(260) NOT NULL,
  PRIMARY KEY (`role_cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role_category`
--

INSERT INTO `role_category` (`role_cat_id`, `role_cat_name`) VALUES
(1, 'System Admin'),
(2, 'School Admin'),
(3, 'Teacher'),
(4, 'Student'),
(5, 'Support Staff'),
(6, 'Parent or Guardian\r\n'),
(7, 'Admin');

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
) ENGINE=InnoDB AUTO_INCREMENT=743 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(492, 1, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(493, 2, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(494, 3, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(495, 14, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(496, 15, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(497, 16, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(498, 18, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(499, 20, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(500, 23, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(501, 24, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(502, 25, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(503, 26, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(504, 27, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(505, 28, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(506, 29, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(507, 30, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(508, 32, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(509, 33, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(510, 34, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(511, 35, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(512, 36, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(513, 37, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(514, 38, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(515, 39, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(516, 40, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(517, 41, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(518, 42, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(519, 43, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(520, 44, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(521, 45, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(522, 46, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(523, 47, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(524, 48, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(525, 49, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(526, 50, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(527, 51, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(528, 52, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(529, 53, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(530, 54, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(531, 55, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(532, 56, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(533, 57, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(534, 58, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(535, 59, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(536, 60, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(537, 61, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(538, 62, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(539, 63, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(540, 64, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(541, 65, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(542, 66, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(543, 67, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(544, 68, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(545, 69, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(546, 70, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(547, 71, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(548, 72, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(549, 73, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(550, 74, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(551, 75, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(552, 76, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(553, 77, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(554, 78, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(555, 79, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(556, 80, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(557, 81, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(558, 83, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(559, 84, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(560, 85, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(561, 87, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(562, 88, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(563, 89, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(564, 95, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(565, 96, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(566, 97, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(567, 98, 3, '2026-06-04 15:32:37', '2026-06-04 15:32:37'),
(576, 99, 6, '2026-06-25 10:14:40', '2026-06-25 10:14:40'),
(654, 1, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(655, 2, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(656, 3, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(657, 14, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(658, 15, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(659, 16, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(660, 18, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(661, 20, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(662, 23, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(663, 24, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(664, 25, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(665, 26, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(666, 27, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(667, 29, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(668, 30, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(669, 32, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(670, 33, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(671, 35, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(672, 37, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(673, 40, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(674, 41, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(675, 42, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(676, 43, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(677, 44, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(678, 45, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(679, 46, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(680, 47, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(681, 48, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(682, 49, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(683, 51, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(684, 52, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(685, 53, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(686, 54, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(687, 55, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(688, 56, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(689, 57, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(690, 58, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(691, 59, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(692, 60, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(693, 61, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(694, 62, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(695, 63, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(696, 65, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(697, 66, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(698, 67, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(699, 69, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(700, 72, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(701, 73, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(702, 74, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(703, 75, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(704, 76, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(705, 77, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(706, 78, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(707, 79, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(708, 80, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(709, 81, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(710, 82, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(711, 83, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(712, 84, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(713, 85, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(714, 86, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(715, 87, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(716, 88, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(717, 89, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(718, 95, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(719, 96, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(720, 97, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(721, 98, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(722, 99, 5, '2026-06-25 10:15:17', '2026-06-25 10:15:17'),
(732, 1, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(733, 2, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(734, 3, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(735, 18, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(736, 41, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(737, 57, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(738, 60, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(739, 65, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(740, 66, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(741, 67, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07'),
(742, 99, 7, '2026-07-01 05:52:07', '2026-07-01 05:52:07');

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`sch_id`, `sch_cat_id_fk`, `district_id_fk`, `sch_name`, `sch_address`, `sch_phone`, `sch_email`, `sch_password`, `sch_x_coord`, `sch_y_coord`, `sch_motto`, `sch_logo`, `sch_primary_color`, `sch_secondary_color`, `sch_created_at`, `sch_status`) VALUES
(12, 4, 89, 'Suva Secondary School', 'Lot 345, Straight Street, Labasa', 9807645, 'piobaleicoqe@yahoo.com', '$2y$10$tJxHFuOF4CLv.JmHZHejfeKiNXq9M1tNsReqwMFpIkwLdKXDQkMSq', '178.440609', '-18.134809', 'Enter To Learn', 'logo_12_893140.png', '', '', '2025-11-05 12:01:19', 'Active'),
(21, 4, 159, 'Lami High School', '6 Miles', 1234567, 'sch@yahoo.com', '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', NULL, NULL, 'Enter to Learn', '', '#3498db', '#ecf0f1', '2026-01-14 16:12:02', 'Step 1 Configured'),
(26, 4, 46, 'Nasinu Secondary School 6', '6 Miles, Tacirua', 9896700, 'piobaleicoqe92@gmail.com', NULL, NULL, NULL, 'Enter to learn', '', '', '', NULL, 'Step 1 Configured'),
(29, 4, 193, 'Rotuma High School', 'Rotuma island', 9987678, 'pio@baleicoqe.com', NULL, '177.081499', '-12.519626', 'Enter to learn', 'logo_29_883917.jpg', '#0080ff', '#ff0000', NULL, 'Active'),
(30, 4, 114, 'William Cross College', 'Vula Street, Nasinu', 2148885, 'uwatevakaloloma1987@gmail.com', NULL, NULL, NULL, 'Go Forth To Learn', 'logo_30_699279.jpg', '#000000', '#000000', NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `school_category_config`
--

DROP TABLE IF EXISTS `school_category_config`;
CREATE TABLE IF NOT EXISTS `school_category_config` (
  `sch_cat_con_id` int NOT NULL AUTO_INCREMENT,
  `sch_cat_id_fk` int NOT NULL,
  `num_of_term_in_year` int NOT NULL,
  `label_for_term` varchar(60) NOT NULL,
  `num_of_week_in_a_term` int NOT NULL,
  PRIMARY KEY (`sch_cat_con_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_config`
--

DROP TABLE IF EXISTS `school_config`;
CREATE TABLE IF NOT EXISTS `school_config` (
  `sch_config_id` int NOT NULL AUTO_INCREMENT,
  `sch_year` int NOT NULL DEFAULT '2026',
  `num_of_term` int NOT NULL,
  `term_1_start_date` date DEFAULT NULL,
  `term_1_end_date` date DEFAULT NULL,
  `term_2_start_date` date DEFAULT NULL,
  `term_2_end_date` date DEFAULT NULL,
  `term_3_start_date` date DEFAULT NULL,
  `term_3_end_date` date DEFAULT NULL,
  PRIMARY KEY (`sch_config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `school_config`
--

INSERT INTO `school_config` (`sch_config_id`, `sch_year`, `num_of_term`, `term_1_start_date`, `term_1_end_date`, `term_2_start_date`, `term_2_end_date`, `term_3_start_date`, `term_3_end_date`) VALUES
(1, 2026, 3, '2026-01-26', '2026-05-01', '2026-05-18', '2026-08-21', '2026-09-07', '2026-12-04');

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
-- Table structure for table `sch_cat_term_entry`
--

DROP TABLE IF EXISTS `sch_cat_term_entry`;
CREATE TABLE IF NOT EXISTS `sch_cat_term_entry` (
  `sch_cat_term_id` int NOT NULL AUTO_INCREMENT,
  `sch_cat_con_id_fk` int NOT NULL,
  `term_num` int NOT NULL,
  `term_start_date` date NOT NULL,
  `term_end_date` int NOT NULL,
  PRIMARY KEY (`sch_cat_term_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(47, 29, 11, NULL, 'Established'),
(48, 30, 1, NULL, 'Established'),
(49, 30, 2, NULL, 'Established'),
(50, 30, 3, NULL, 'Established'),
(51, 30, 4, NULL, 'Established'),
(52, 30, 5, NULL, 'Established'),
(53, 30, 6, NULL, 'Established'),
(54, 30, 7, NULL, 'Established'),
(55, 30, 8, NULL, 'Established'),
(56, 30, 9, NULL, 'Established'),
(57, 30, 10, NULL, 'Established'),
(58, 30, 11, NULL, 'Established');

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(31, 29, 11),
(32, 30, 11),
(33, 30, 12),
(34, 30, 13),
(35, 30, 14),
(36, 30, 15);

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
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(178, 12, 174, 1, 'Active'),
(179, 30, 132, 49, 'Active'),
(180, 30, 141, 52, 'Active'),
(181, 30, 130, 48, 'Active'),
(182, 30, 131, 51, 'Active'),
(183, 30, 146, 50, 'Active'),
(184, 30, 138, 55, 'Active'),
(185, 30, 144, 53, 'Active'),
(186, 30, 133, 48, 'Active'),
(187, 30, 139, 56, 'Active'),
(188, 30, 143, 54, 'Active'),
(189, 30, 137, 57, 'Active'),
(190, 30, 242, 57, 'Active'),
(191, 30, 237, 57, 'Active'),
(192, 30, 206, 48, 'Active'),
(193, 30, 241, 57, 'Active'),
(194, 30, 251, 48, 'Active'),
(195, 30, 207, 51, 'Active'),
(196, 30, 231, 57, 'Active'),
(197, 30, 223, 57, 'Active'),
(198, 30, 236, 58, 'Active'),
(199, 30, 218, 55, 'Active'),
(200, 30, 226, 53, 'Active'),
(201, 30, 211, 50, 'Active'),
(202, 30, 224, 54, 'Active'),
(203, 30, 210, 51, 'Active'),
(204, 30, 209, 49, 'Active'),
(205, 30, 225, 53, 'Active'),
(206, 30, 221, 52, 'Active'),
(207, 30, 219, 56, 'Active'),
(208, 30, 213, 48, 'Active'),
(209, 30, 254, 52, 'Active'),
(210, 30, 220, 56, 'Active'),
(211, 30, 208, 49, 'Active'),
(212, 30, 212, 50, 'Active'),
(213, 30, 222, 52, 'Active'),
(214, 30, 217, 57, 'Active'),
(215, 30, 246, 57, 'Active');

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
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(113, 30, '1301'),
(116, 32, 'Year 9A'),
(117, 32, 'Year 9B'),
(118, 33, 'Year 10A'),
(119, 33, 'Year 10B'),
(120, 34, 'Year 11A'),
(121, 34, 'Year 11B'),
(122, 35, 'Year 12A'),
(123, 35, 'Year 12B'),
(124, 36, 'Year 13A'),
(125, 36, 'Year 13B');

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
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(210, 139, 107),
(224, 179, 116),
(225, 180, 116),
(226, 181, 116),
(227, 182, 116),
(228, 183, 116),
(229, 189, 116),
(230, 190, 116),
(231, 191, 116),
(232, 192, 124),
(233, 193, 124),
(234, 194, 124),
(235, 195, 124),
(236, 196, 124),
(237, 197, 124),
(238, 198, 124),
(239, 192, 125),
(240, 195, 125);

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
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(101, 66, 95, 3),
(102, 184, 116, 1),
(103, 185, 116, 1),
(104, 186, 116, 1),
(105, 187, 116, 2),
(106, 188, 116, 2),
(107, 199, 124, 1),
(108, 200, 124, 1),
(109, 201, 124, 1),
(110, 202, 124, 1),
(111, 203, 124, 1),
(112, 204, 124, 2),
(113, 205, 124, 2),
(114, 206, 124, 2),
(115, 207, 124, 2),
(116, 208, 124, 2),
(117, 209, 124, 3),
(118, 210, 124, 3),
(119, 211, 124, 3),
(120, 212, 124, 3),
(124, 204, 125, 2),
(125, 205, 125, 2),
(126, 206, 125, 2),
(127, 212, 125, 3),
(128, 203, 125, 3),
(129, 207, 125, 3),
(130, 209, 125, 4),
(131, 199, 125, 4),
(132, 211, 125, 4),
(133, 214, 125, 5),
(134, 215, 125, 5),
(135, 193, 125, 5);

-- --------------------------------------------------------

--
-- Table structure for table `student_assignment_score`
--

DROP TABLE IF EXISTS `student_assignment_score`;
CREATE TABLE IF NOT EXISTS `student_assignment_score` (
  `score_id` int NOT NULL AUTO_INCREMENT,
  `assignment_id_fk` int NOT NULL,
  `submission_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `assignment_mark` decimal(5,2) DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `graded_at` datetime DEFAULT NULL,
  `graded_by` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`score_id`),
  UNIQUE KEY `unique_score` (`assignment_id_fk`,`user_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_assignment_score`
--

INSERT INTO `student_assignment_score` (`score_id`, `assignment_id_fk`, `submission_id_fk`, `user_id_fk`, `assignment_mark`, `feedback`, `graded_at`, `graded_by`, `updated_at`) VALUES
(1, 3, 1, 37, 75.00, 'Student can still do better. Need a little more research work.', '2026-06-04 11:30:10', 12, '2026-06-04 11:30:10');

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
) ENGINE=MyISAM AUTO_INCREMENT=264 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(200, 13, 91, 14, 26, '2026-04-30', '', 'Subject', 'Present'),
(201, 10, 91, 13, NULL, '2026-06-05', 'Call in sick', 'Daily', 'Absent'),
(202, 16, 91, 13, NULL, '2026-06-05', '', 'Daily', 'Present'),
(203, 11, 91, 13, NULL, '2026-06-05', '', 'Daily', 'Present'),
(204, 14, 91, 13, NULL, '2026-06-05', '', 'Daily', 'Present'),
(205, 12, 91, 13, NULL, '2026-06-05', '', 'Daily', 'Present'),
(206, 15, 91, 13, NULL, '2026-06-05', '', 'Daily', 'Present'),
(207, 13, 91, 13, NULL, '2026-06-05', '', 'Daily', 'Present'),
(208, 34, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Sick'),
(209, 21, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(210, 25, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(211, 33, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(212, 30, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(213, 26, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(214, 19, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(215, 20, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(216, 27, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(217, 28, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(218, 24, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(219, 18, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(220, 31, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(221, 22, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(222, 29, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(223, 23, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(224, 32, 124, 48, NULL, '2026-06-06', '', 'Daily', 'Present'),
(225, 34, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(226, 21, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(227, 25, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(228, 33, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(229, 30, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(230, 26, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(231, 19, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(232, 20, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(233, 27, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(234, 28, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(235, 24, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(236, 18, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(237, 31, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(238, 22, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(239, 29, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(240, 23, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(241, 32, 124, 48, 192, '2026-06-06', '', 'Subject', 'Present'),
(263, 48, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(262, 47, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(261, 49, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(260, 44, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(259, 53, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(258, 45, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(257, 41, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(256, 39, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(255, 50, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(254, 43, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Present'),
(253, 40, 125, 48, NULL, '2026-07-01', '', 'Daily', 'Absent');

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
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(11, 193, '1779924858_47864eee0f11.png', 'png'),
(12, 201, '1780643170_c964c79ad8df.jpg', 'jpg'),
(13, 201, '1780643170_9d27cd5e5d2d.jpg', 'jpg'),
(14, 201, '1780643170_b28bffbcaf5c.jpg', 'jpg'),
(15, 201, '1780643170_8428b375329e.jpg', 'jpg'),
(16, 208, '1780729099_995b313e5900.pdf', 'pdf'),
(17, 253, '1782872110_cf1062a9adc4.pdf', 'pdf'),
(18, 253, '1782872110_2d3a354cc9e9.pdf', 'pdf');

-- --------------------------------------------------------

--
-- Table structure for table `student_exam`
--

DROP TABLE IF EXISTS `student_exam`;
CREATE TABLE IF NOT EXISTS `student_exam` (
  `student_exam_id` int NOT NULL AUTO_INCREMENT,
  `exam_id_fk` int NOT NULL,
  `enrol_id_fk` int NOT NULL,
  `exam_year` int NOT NULL,
  `exam_term` int NOT NULL DEFAULT '1',
  `student_exam_status` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `created_by_fk` int DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_time` int DEFAULT NULL,
  PRIMARY KEY (`student_exam_id`),
  UNIQUE KEY `uniq_student_exam` (`exam_id_fk`,`enrol_id_fk`,`exam_year`,`exam_term`),
  KEY `exam_id_fk` (`exam_id_fk`),
  KEY `enrol_id_fk` (`enrol_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_exam`
--

INSERT INTO `student_exam` (`student_exam_id`, `exam_id_fk`, `enrol_id_fk`, `exam_year`, `exam_term`, `student_exam_status`, `created_by_fk`, `created_date`, `created_time`) VALUES
(45, 5, 45, 2026, 1, 'Active', 1, '2026-06-25', 1782349474),
(43, 5, 31, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(42, 5, 44, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(41, 5, 30, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(40, 5, 29, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(39, 5, 28, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(38, 5, 27, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(37, 5, 26, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(36, 5, 25, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(35, 5, 24, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(34, 5, 23, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(33, 5, 32, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(32, 5, 43, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(31, 5, 33, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(30, 5, 34, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(29, 5, 22, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(28, 5, 41, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(27, 5, 20, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(26, 5, 19, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(25, 5, 18, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(24, 5, 40, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(23, 5, 39, 2026, 1, 'Active', 1, '2026-06-25', 1782349309),
(46, 5, 49, 2026, 1, 'Active', 63, '2026-06-25', 1782358657),
(47, 5, 47, 2026, 1, 'Active', 63, '2026-06-25', 1782358834),
(48, 5, 48, 2026, 1, 'Active', 63, '2026-06-25', 1782358834),
(49, 5, 50, 2026, 1, 'Active', 1, '2026-06-26', 1782403856);

-- --------------------------------------------------------

--
-- Table structure for table `student_exam_mark`
--

DROP TABLE IF EXISTS `student_exam_mark`;
CREATE TABLE IF NOT EXISTS `student_exam_mark` (
  `mark_id` int NOT NULL AUTO_INCREMENT,
  `student_exam_id_fk` int NOT NULL,
  `stud_sub_id_fk` int NOT NULL,
  `mark` decimal(5,2) DEFAULT NULL,
  `grade` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `teacher_id_fk` int DEFAULT NULL,
  `mark_status` varchar(60) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Draft',
  `created_date` date DEFAULT NULL,
  `created_time` int DEFAULT NULL,
  `updated_time` int DEFAULT NULL,
  PRIMARY KEY (`mark_id`),
  UNIQUE KEY `uniq_mark` (`student_exam_id_fk`,`stud_sub_id_fk`),
  KEY `student_exam_id_fk` (`student_exam_id_fk`),
  KEY `stud_sub_id_fk` (`stud_sub_id_fk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_subject`
--

DROP TABLE IF EXISTS `student_subject`;
CREATE TABLE IF NOT EXISTS `student_subject` (
  `stud_sub_id` int NOT NULL AUTO_INCREMENT,
  `admission_id_fk` int NOT NULL DEFAULT '0',
  `class_id_fk` int NOT NULL,
  `sch_sub_id_fk` int NOT NULL,
  `stud_sub_status` varchar(60) NOT NULL,
  PRIMARY KEY (`stud_sub_id`),
  KEY `fk_student_subject_sch_sub` (`sch_sub_id_fk`),
  KEY `fk_student_classroom_class` (`class_id_fk`),
  KEY `idx_enrol_id_fk` (`admission_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_subject`
--

INSERT INTO `student_subject` (`stud_sub_id`, `admission_id_fk`, `class_id_fk`, `sch_sub_id_fk`, `stud_sub_status`) VALUES
(82, 59, 5, 192, 'Active'),
(83, 59, 5, 195, 'Active'),
(84, 59, 5, 204, 'Active'),
(85, 59, 5, 203, 'Active'),
(86, 59, 5, 211, 'Active'),
(87, 29, 5, 192, 'Active'),
(88, 29, 5, 195, 'Active'),
(89, 29, 5, 204, 'Active'),
(90, 29, 5, 203, 'Active'),
(91, 29, 5, 211, 'Active'),
(92, 29, 5, 193, 'Active'),
(93, 57, 5, 192, 'Active'),
(94, 57, 5, 195, 'Active'),
(95, 57, 5, 204, 'Active'),
(96, 57, 5, 212, 'Active'),
(97, 57, 5, 209, 'Active'),
(98, 57, 5, 214, 'Active'),
(99, 58, 5, 192, 'Active'),
(100, 58, 5, 195, 'Active'),
(101, 58, 5, 204, 'Active'),
(102, 58, 5, 203, 'Active'),
(103, 58, 5, 211, 'Active'),
(104, 58, 5, 215, 'Active'),
(105, 31, 5, 192, 'Active'),
(106, 31, 5, 195, 'Active'),
(107, 31, 5, 204, 'Active'),
(108, 31, 5, 203, 'Active'),
(109, 31, 5, 211, 'Active'),
(110, 34, 5, 192, 'Active'),
(111, 34, 5, 195, 'Active'),
(112, 34, 5, 204, 'Active'),
(113, 34, 5, 203, 'Active'),
(114, 34, 5, 211, 'Active'),
(115, 34, 5, 215, 'Active');

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
  `is_examinable` tinyint(1) NOT NULL,
  PRIMARY KEY (`subject_id`),
  KEY `fk_subject_level_level` (`level_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=258 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `level_id_fk`, `subject_name`, `sub_image`, `is_examinable`) VALUES
(1, 1, 'Language, Literacy and Communication', '', 0),
(2, 1, 'Mathematics and Numeracy', '', 0),
(3, 1, 'Environmental Studies', '', 0),
(4, 1, 'Creative Arts', '', 0),
(5, 1, 'Physical Development', '', 0),
(6, 1, 'Social and Emotional Development', '', 0),
(7, 1, 'Spiritual and Moral Development', '', 0),
(8, 2, 'Language, Literacy and Communication', '', 0),
(9, 2, 'Mathematics and Numeracy', '', 0),
(10, 2, 'Environmental Studies', '', 0),
(11, 2, 'Creative Arts', '', 0),
(12, 2, 'Physical Development', '', 0),
(13, 2, 'Social and Emotional Development', '', 0),
(14, 2, 'Spiritual and Moral Development', '', 0),
(15, 3, 'Year 1 English', '', 0),
(16, 3, 'Year 1 Mathematics', '', 0),
(17, 3, 'Art Is Fun 1', '', 0),
(18, 3, 'Year 1 Vosa VakaViti', '', 0),
(19, 3, 'Year 1 Hindi', '', 0),
(20, 3, 'Year 1 Rotuman', '', 0),
(21, 3, 'Year 1 Urdu', '', 0),
(22, 3, 'Year 1 Performing Arts', '', 0),
(23, 3, 'Year 1 MCE', '', 0),
(24, 3, 'Year 1 Nutrition', '', 0),
(25, 3, 'Year 1 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(26, 4, 'Year 2 English', '', 0),
(27, 4, 'Year 2 Mathematics', '', 0),
(28, 4, 'Art Is Fun 2', '', 0),
(29, 4, 'Year 2 Vosa VakaViti', '', 0),
(30, 4, 'Year 2 Hindi', '', 0),
(31, 4, 'Year 2 Rotuman', '', 0),
(32, 4, 'Year 2 Urdu', '', 0),
(33, 4, 'Year 2 Performing Arts', '', 0),
(34, 4, 'Year 2 MCE', '', 0),
(35, 4, 'Year 2 Nutrition', '', 0),
(36, 4, 'Year 2 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(37, 5, 'Year 3 English', '', 0),
(38, 5, 'Year 3 Mathematics', '', 0),
(39, 5, 'Art Is Fun 3', '', 0),
(40, 5, 'Year 3 Vosa VakaViti', '', 0),
(41, 5, 'Year 3 Hindi', '', 0),
(42, 5, 'Year 3 Rotuman', '', 0),
(43, 5, 'Year 3 Urdu', '', 0),
(44, 5, 'Year 3 Performing Arts', '', 0),
(45, 5, 'Year 3 MCE', '', 0),
(46, 5, 'Year 3 Nutrition', '', 0),
(47, 5, 'Year 3 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(48, 5, 'PE Is Fun 3', '', 0),
(49, 5, 'Year 3 Enterprise Education', '', 0),
(50, 6, 'Year 4 English', '', 0),
(51, 6, 'Year 4 Mathematics', '', 0),
(52, 6, 'Art Is Fun 4', '', 0),
(53, 6, 'Year 4 Vosa VakaViti', '', 0),
(54, 6, 'Year 4 Hindi', '', 0),
(55, 6, 'Year 4 Rotuman', '', 0),
(56, 6, 'Year 4 Urdu', '', 0),
(57, 6, 'Year 4 Performing Arts', '', 0),
(58, 6, 'Year 4 MCE', '', 0),
(59, 6, 'Year 4 Nutrition', '', 0),
(60, 6, 'Year 4 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(61, 6, 'PE Is Fun 4', '', 0),
(62, 6, 'Year 4 Enterprise Education', '', 0),
(63, 6, 'Year 4 Social Studies', '', 0),
(64, 6, 'Year 4 Elementary Science', '', 0),
(65, 6, 'Year 4 Healthy Living', '', 0),
(66, 7, 'Year 5 English', '', 0),
(67, 7, 'Year 5 Mathematics', '', 0),
(68, 7, 'Art Is Fun 5', '', 0),
(69, 7, 'Year 5 Vosa VakaViti', '', 0),
(70, 7, 'Year 5 Hindi', '', 0),
(71, 7, 'Year 5 Rotuman', '', 0),
(72, 7, 'Year 5 Urdu', '', 0),
(73, 7, 'Year 5 Performing Arts', '', 0),
(74, 7, 'Year 5 MCE', '', 0),
(75, 7, 'Year 5 Nutrition', '', 0),
(76, 7, 'Year 5 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(77, 7, 'PE Is Fun 5', '', 0),
(78, 7, 'Year 5 Enterprise Education', '', 0),
(79, 7, 'Year 5 Social Studies', '', 0),
(80, 7, 'Year 5 Elementary Science', '', 0),
(81, 7, 'Year 5 Healthy Living', '', 0),
(82, 8, 'Year 6 English', '', 1),
(83, 8, 'Year 6 Mathematics', '', 1),
(84, 8, 'Art Is Fun 6', '', 0),
(85, 8, 'Year 6 Vosa VakaViti', '', 1),
(86, 8, 'Year 6 Hindi', '', 1),
(87, 8, 'Year 6 Rotuman', '', 1),
(88, 8, 'Year 6 Urdu', '', 1),
(89, 8, 'Year 6 Performing Arts', '', 0),
(90, 8, 'Year 6 MCE', '', 0),
(91, 8, 'Year 6 Nutrition', '', 0),
(92, 8, 'Year 6 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(93, 8, 'PE Is Fun 6', '', 0),
(94, 8, 'Year 6 Enterprise Education', '', 0),
(95, 8, 'Year 6 Social Studies', '', 1),
(96, 8, 'Year 6 Elementary Science', '', 1),
(97, 8, 'Year 6 Healthy Living', '', 1),
(98, 9, 'Year 7 English', '', 0),
(99, 9, 'Year 7 Mathematics', '', 0),
(100, 9, 'Art Is Fun 7', '', 0),
(101, 9, 'Year 7 Vosa VakaViti', '', 0),
(102, 9, 'Year 7 Hindi', '', 0),
(103, 9, 'Year 7 Rotuman', '', 0),
(104, 9, 'Year 7 Urdu', '', 0),
(105, 9, 'Year 7 Performing Arts', '', 0),
(106, 9, 'Year 7 MCE', '', 0),
(107, 9, 'Year 7 Nutrition', '', 0),
(108, 9, 'Year 7 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(109, 9, 'PE Is Fun 7', '', 0),
(110, 9, 'Year 7 Enterprise Education', '', 0),
(111, 9, 'Year 7 Social Science', '', 0),
(112, 9, 'Year 7 Basic Science', '', 0),
(113, 9, 'Year 7 Healthy Living', '', 0),
(114, 10, 'Year 8 English', '', 1),
(115, 10, 'Year 8 Mathematics', '', 1),
(116, 10, 'Art Is Fun 8', '', 0),
(117, 10, 'Year 8 Vosa VakaViti', '', 1),
(118, 10, 'Year 8 Hindi', '', 1),
(119, 10, 'Year 8 Rotuman', '', 1),
(120, 10, 'Year 8 Urdu', '', 0),
(121, 10, 'Year 8 Performing Arts', '', 0),
(122, 10, 'Year 8 MCE', '', 0),
(123, 10, 'Year 8 Nutrition', '', 0),
(124, 10, 'Year 8 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(125, 10, 'PE Is Fun 8', '', 0),
(126, 10, 'Year 8 Enterprise Education', '', 0),
(127, 10, 'Year 8 Social Science', '', 1),
(128, 10, 'Year 8 Basic Science', '', 1),
(129, 10, 'Year 8 Healthy Living', '', 1),
(130, 11, 'Year 9 English', '', 0),
(131, 11, 'Year 9 Mathematics', '', 0),
(132, 11, 'Year 9 Basic Science', '', 0),
(133, 11, 'Year 9 Vosa VakaViti', '', 0),
(134, 11, 'Year 9 Hindi', '', 0),
(135, 11, 'Year 9 Rotuman', '', 0),
(136, 11, 'Year 9 Urdu', '', 0),
(137, 11, 'Year 9 Art & Craft', '', 0),
(138, 11, 'Year 9 Argriculture Science', '', 0),
(139, 11, 'Year 9 Basic Technology', '', 0),
(140, 11, 'Year 9 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(141, 11, 'Year 9 Commercial Studies', '', 0),
(142, 11, 'Year 9 Physical Education', '', 0),
(143, 11, 'Year 9 Home Economics', '', 0),
(144, 11, 'Year 9 Business Administration', '', 0),
(145, 11, 'Year 9 Performing Arts', '', 0),
(146, 11, 'Year 9 Social Science', '', 0),
(147, 12, 'Year 10 English', '', 1),
(148, 12, 'Year 10 Mathematics', '', 1),
(149, 12, 'Year 10 Basic Science', '', 1),
(150, 12, 'Year 10 Vosa VakaViti', '', 1),
(151, 12, 'Year 10 Hindi', '', 1),
(152, 12, 'Year 10 Rotuman', '', 1),
(153, 12, 'Year 10 Urdu', '', 1),
(154, 12, 'Year 10 Art & Craft', '', 0),
(155, 12, 'Year 10 Argriculture Science', '', 1),
(156, 12, 'Year 10 Basic Technology', '', 1),
(157, 12, 'Year 10 Conversational Vosa VakaViti & Fiji Hindi', '', 0),
(158, 12, 'Year 10 Commercial Studies', '', 1),
(159, 12, 'Year 10 Physical Education', '', 0),
(160, 12, 'Year 10 Home Economics', '', 1),
(161, 12, 'Year 10 Business Administration', '', 1),
(162, 12, 'Year 10 Performing Arts', '', 0),
(163, 12, 'Year 10 Social Science', '', 1),
(164, 13, 'Year 11 English', '', 0),
(165, 13, 'Year 11 Mathematics', '', 0),
(166, 13, 'Year 11 Chemistry', '', 0),
(167, 13, 'Year 11 Biology', '', 0),
(168, 13, 'Year 11 Physics', '', 0),
(169, 13, 'Year 11 History', '', 0),
(170, 13, 'Year 11 Geography', '', 0),
(171, 13, 'Year 11 Vosa VakaViti', '', 0),
(172, 13, 'Year 11 Hindi', '', 0),
(173, 13, 'Year 11 Rotuman', '', 0),
(174, 13, 'Year 11 Urdu', '', 0),
(175, 13, 'Year 11 Art & Craft', '', 0),
(176, 13, 'Year 11 Argriculture Science', '', 0),
(177, 13, 'Year 11 Technical Drawing', '', 0),
(178, 13, 'Year 11 Applied Technology', '', 0),
(179, 13, 'Year 11 Economic', '', 0),
(180, 13, 'Year 11 Accounting', '', 0),
(181, 13, 'Year 11 Physical Education', '', 0),
(182, 13, 'Year 11 Home Economics', '', 0),
(183, 13, 'Year 11 Business Administration', '', 0),
(184, 13, 'Year 11 Computer Science', '', 0),
(185, 14, 'Year 12 English', '', 1),
(186, 14, 'Year 12 Mathematics', '', 1),
(187, 14, 'Year 12 Chemistry', '', 1),
(188, 14, 'Year 12 Biology', '', 1),
(189, 14, 'Year 12 Physics', '', 1),
(190, 14, 'Year 12 History', '', 1),
(191, 14, 'Year 12 Geography', '', 1),
(192, 14, 'Year 12 Vosa VakaViti', '', 1),
(193, 14, 'Year 12 Hindi', '', 1),
(194, 14, 'Year 12 Rotuman', '', 1),
(195, 14, 'Year 12 Urdu', '', 1),
(196, 14, 'Year 12 Art & Craft', '', 0),
(197, 14, 'Year 12 Argriculture Science', '', 1),
(198, 14, 'Year 12 Technical Drawing', '', 1),
(199, 14, 'Year 12 Applied Technology', '', 1),
(200, 14, 'Year 12 Economic', '', 1),
(201, 14, 'Year 12 Accounting', '', 1),
(202, 14, 'Year 12 Physical Education', '', 0),
(203, 14, 'Year 12 Home Economics', '', 1),
(204, 14, 'Year 12 Business Administration', '', 1),
(205, 14, 'Year 12 Computer Science', '', 1),
(206, 15, 'Year 13 English', '', 1),
(207, 15, 'Year 13 Mathematics', '', 1),
(208, 15, 'Year 13 Chemistry', '', 1),
(209, 15, 'Year 13 Biology', '', 1),
(210, 15, 'Year 13 Physics', '', 1),
(211, 15, 'Year 13 History', '', 1),
(212, 15, 'Year 13 Geography', '', 1),
(213, 15, 'Year 13 Vosa VakaViti', '', 1),
(214, 15, 'Year 13 Hindi', '', 1),
(215, 15, 'Year 13 Rotuman', '', 1),
(216, 15, 'Year 13 Urdu', '', 1),
(217, 15, 'Year 13 Art & Craft', '', 0),
(218, 15, 'Year 13 Argriculture Science', '', 1),
(219, 15, 'Year 13 Technical Drawing', '', 1),
(220, 15, 'Year 13 Applied Technology', '', 1),
(221, 15, 'Year 13 Economic', '', 1),
(222, 15, 'Year 13 Accounting', '', 1),
(223, 15, 'Year 13 Physical Education', '', 0),
(224, 15, 'Year 13 Home Economics', '', 1),
(225, 15, 'Year 13 Business Administration', '', 1),
(226, 15, 'Year 13 Computer Science', '', 0),
(227, 11, 'Year 9 Music', '', 0),
(228, 12, 'Year 10 Music', '', 0),
(229, 13, 'Year 11 Music', '', 0),
(230, 14, 'Year 12 Music', '', 0),
(231, 15, 'Year 13 Music', '', 0),
(232, 11, 'Year 9 Religious Education', '', 0),
(233, 12, 'Year 10 Religious Education', '', 0),
(234, 13, 'Year 11 Religious Education', '', 0),
(235, 14, 'Year 12 Religious Education', '', 0),
(236, 15, 'Year 13 Religious Education', '', 0),
(237, 11, 'Year 9 Family Life Education', '', 0),
(238, 12, 'Year 10 Family Life Education', '', 0),
(239, 13, 'Year 11 Family Life Education', '', 0),
(240, 14, 'Year 12 Family Life Education', '', 0),
(241, 15, 'Year 13 Family Life Education', '', 0),
(242, 11, 'Year 9 Career Education', '', 0),
(243, 12, 'Year 10 Career Education', '', 0),
(244, 13, 'Year 11 Career Education', '', 0),
(245, 14, 'Year 12 Career Education', '', 0),
(246, 15, 'Year 13 Career Education', '', 0),
(247, 11, 'Year 9 Library', '', 0),
(248, 12, 'Year 10 Library', '', 0),
(249, 13, 'Year 11 Library', '', 0),
(250, 14, 'Year 12 Library', '', 0),
(251, 15, 'Year 13 Library', '', 0),
(252, 13, 'Year 11 Accounting', '', 0),
(253, 14, 'Year 12 Accounting', '', 0),
(254, 15, 'Year 13 Accounting', '', 0),
(255, 13, 'Year 11 Economic', '', 0),
(256, 14, 'Year 12 Economic', '', 0),
(257, 15, 'Year 13 Economic', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subject_discussion`
--

DROP TABLE IF EXISTS `subject_discussion`;
CREATE TABLE IF NOT EXISTS `subject_discussion` (
  `sd_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_sub_id_fk` int NOT NULL,
  `author` int NOT NULL,
  `message` longtext COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `post_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sd_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_discussion`
--

INSERT INTO `subject_discussion` (`sd_id`, `class_sub_id_fk`, `author`, `message`, `created_at`, `post_status`) VALUES
(1, 7, 12, 'Bula vinaka everyone this is my first post. Testing the subject discussion features.', '2026-06-04 13:06:25', 1),
(2, 7, 42, 'Bula My colleagues. Looking forward for a fruitful year.', '2026-06-04 13:16:01', 1),
(3, 7, 42, 'testing 2 images', '2026-06-04 13:28:37', 1),
(4, 7, 42, 'testing 3 images', '2026-06-04 13:29:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject_discussion_comment`
--

DROP TABLE IF EXISTS `subject_discussion_comment`;
CREATE TABLE IF NOT EXISTS `subject_discussion_comment` (
  `sdc_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sd_id_fk` int NOT NULL,
  `author` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `comment_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`sdc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_discussion_comment`
--

INSERT INTO `subject_discussion_comment` (`sdc_id`, `sd_id_fk`, `author`, `comment`, `created_at`, `comment_status`) VALUES
(1, 1, 12, 'Wow it finally works', '2026-06-04 13:07:11', 'Deleted'),
(2, 2, 42, 'good', '2026-06-04 13:30:08', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `subject_discussion_comment_like`
--

DROP TABLE IF EXISTS `subject_discussion_comment_like`;
CREATE TABLE IF NOT EXISTS `subject_discussion_comment_like` (
  `clike_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sdc_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `like_type` enum('like','dislike') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'like',
  PRIMARY KEY (`clike_id`),
  UNIQUE KEY `sdc_id_fk_user_id_fk` (`sdc_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_discussion_comment_like`
--

INSERT INTO `subject_discussion_comment_like` (`clike_id`, `sdc_id_fk`, `user_id_fk`, `like_type`) VALUES
(1, 1, 42, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `subject_discussion_comment_reply`
--

DROP TABLE IF EXISTS `subject_discussion_comment_reply`;
CREATE TABLE IF NOT EXISTS `subject_discussion_comment_reply` (
  `sdcr_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sdc_id_fk` int NOT NULL,
  `author` int NOT NULL,
  `reply` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `reply_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`sdcr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_discussion_comment_reply`
--

INSERT INTO `subject_discussion_comment_reply` (`sdcr_id`, `sdc_id_fk`, `author`, `reply`, `created_at`, `reply_status`) VALUES
(1, 1, 42, 'Thank you sir looking forward for the first class and orientation', '2026-06-04 13:13:44', 'Active'),
(2, 1, 12, '@Sherine Kumar you are correct indeed', '2026-06-04 13:18:42', 'Deleted'),
(3, 1, 42, '@Sam White i know right?', '2026-06-04 13:19:15', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `subject_discussion_comment_reply_like`
--

DROP TABLE IF EXISTS `subject_discussion_comment_reply_like`;
CREATE TABLE IF NOT EXISTS `subject_discussion_comment_reply_like` (
  `rlike_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sdcr_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `like_type` enum('like','dislike') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'like',
  PRIMARY KEY (`rlike_id`),
  UNIQUE KEY `sdcr_id_fk_user_id_fk` (`sdcr_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_discussion_comment_reply_like`
--

INSERT INTO `subject_discussion_comment_reply_like` (`rlike_id`, `sdcr_id_fk`, `user_id_fk`, `like_type`) VALUES
(1, 3, 42, 'like'),
(2, 2, 42, 'like'),
(3, 1, 42, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `subject_discussion_like`
--

DROP TABLE IF EXISTS `subject_discussion_like`;
CREATE TABLE IF NOT EXISTS `subject_discussion_like` (
  `like_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sd_id_fk` int NOT NULL,
  `user_id_fk` int NOT NULL,
  `like_type` enum('like','dislike') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`like_id`),
  UNIQUE KEY `sd_id_fk_user_id_fk` (`sd_id_fk`,`user_id_fk`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_discussion_like`
--

INSERT INTO `subject_discussion_like` (`like_id`, `sd_id_fk`, `user_id_fk`, `like_type`) VALUES
(1, 1, 12, 'like'),
(2, 1, 42, 'like'),
(3, 2, 42, 'like');

-- --------------------------------------------------------

--
-- Table structure for table `subject_discussion_photo`
--

DROP TABLE IF EXISTS `subject_discussion_photo`;
CREATE TABLE IF NOT EXISTS `subject_discussion_photo` (
  `photo_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sd_id_fk` int NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `photo_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_discussion_photo`
--

INSERT INTO `subject_discussion_photo` (`photo_id`, `sd_id_fk`, `photo_path`, `photo_order`) VALUES
(1, 1, 'sdp_1780535185_2126.png', 0),
(2, 1, 'sdp_1780535185_9617.png', 1),
(3, 1, 'sdp_1780535185_8177.png', 2),
(4, 1, 'sdp_1780535185_5098.png', 3),
(5, 1, 'sdp_1780535185_2947.png', 4),
(6, 1, 'sdp_1780535185_3557.png', 5),
(7, 1, 'sdp_1780535185_3413.png', 6),
(8, 1, 'sdp_1780535185_7318.png', 7),
(9, 1, 'sdp_1780535185_5324.png', 8),
(10, 1, 'sdp_1780535185_8200.png', 9),
(11, 2, 'sdp_1780535761_6363.jpg', 0),
(12, 3, 'sdp_1780536517_3787.jpg', 0),
(13, 3, 'sdp_1780536517_4739.jpg', 1),
(14, 4, 'sdp_1780536545_6477.png', 0),
(15, 4, 'sdp_1780536545_9602.png', 1),
(16, 4, 'sdp_1780536545_9213.png', 2);

-- --------------------------------------------------------

--
-- Table structure for table `subject_feedback`
--

DROP TABLE IF EXISTS `subject_feedback`;
CREATE TABLE IF NOT EXISTS `subject_feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `class_sub_id_fk` int NOT NULL,
  `class_id_fk` int NOT NULL,
  `student_id_fk` int NOT NULL,
  `teacher_id_fk` int DEFAULT NULL,
  `sch_sub_id_fk` int DEFAULT NULL,
  `overall_rating` tinyint NOT NULL DEFAULT '0',
  `teaching_rating` tinyint NOT NULL DEFAULT '0',
  `content_rating` tinyint NOT NULL DEFAULT '0',
  `engagement_rating` tinyint NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`feedback_id`),
  UNIQUE KEY `unique_feedback` (`class_sub_id_fk`,`student_id_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subject_feedback`
--

INSERT INTO `subject_feedback` (`feedback_id`, `class_sub_id_fk`, `class_id_fk`, `student_id_fk`, `teacher_id_fk`, `sch_sub_id_fk`, `overall_rating`, `teaching_rating`, `content_rating`, `engagement_rating`, `comment`, `is_anonymous`, `created_at`, `updated_at`) VALUES
(1, 7, 3, 37, 12, 37, 4, 5, 3, 5, 'Too good', 0, '2026-06-04 11:55:16', '2026-06-04 11:56:14'),
(2, 7, 3, 42, 12, 37, 2, 3, 3, 4, NULL, 1, '2026-06-04 12:39:00', '2026-06-04 12:39:00'),
(3, 1, 3, 42, 12, 30, 5, 5, 5, 5, 'Very good experiece', 1, '2026-06-05 10:04:43', '2026-06-05 10:04:43');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`subscription_id`, `plan_id_fk`, `sch_id_fk`, `subscription_start_date`, `subscription_end_date`, `subscription_time`, `subscription_term`, `payment_mode`, `subscription_status`) VALUES
(1, 1, 12, '2025-12-08', '2026-01-31', NULL, 12, 'Cash', 'Active'),
(6, 3, 26, '2026-02-19', '2029-02-19', NULL, 36, 'Cash', 'Pending Payment'),
(9, 1, 29, '2026-05-13', '2026-06-13', NULL, 12, 'Cash', 'Active'),
(10, 3, 30, '2026-06-06', '2029-06-06', NULL, 36, '', 'Pending Verification');

-- --------------------------------------------------------

--
-- Table structure for table `term_exam_mark`
--

DROP TABLE IF EXISTS `term_exam_mark`;
CREATE TABLE IF NOT EXISTS `term_exam_mark` (
  `temark_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_sub_id_fk` int NOT NULL,
  `class_id_fk` int NOT NULL,
  `student_id_fk` int NOT NULL,
  `term` tinyint(1) NOT NULL,
  `mark` decimal(6,2) DEFAULT NULL,
  `total_mark` decimal(6,2) NOT NULL DEFAULT '100.00',
  `is_absent` tinyint(1) NOT NULL DEFAULT '0',
  `teacher_comment` text COLLATE utf8mb4_general_ci,
  `entered_by` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`temark_id`),
  UNIQUE KEY `class_sub_id_fk_student_id_fk_term` (`class_sub_id_fk`,`student_id_fk`,`term`)
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `term_exam_mark`
--

INSERT INTO `term_exam_mark` (`temark_id`, `class_sub_id_fk`, `class_id_fk`, `student_id_fk`, `term`, `mark`, `total_mark`, `is_absent`, `teacher_comment`, `entered_by`, `created_at`, `updated_at`) VALUES
(1, 7, 3, 36, 1, 50.00, 100.00, 0, 'More effort required', 12, '2026-06-04 14:17:17', '2026-06-04 14:58:31'),
(2, 7, 3, 42, 1, 60.00, 100.00, 0, 'Good performance', 12, '2026-06-04 14:18:06', '2026-06-04 15:04:30'),
(3, 7, 3, 37, 1, 70.00, 100.00, 0, 'Hard working child', 12, '2026-06-04 14:42:18', '2026-06-04 15:04:24'),
(4, 7, 3, 40, 1, 75.00, 100.00, 0, 'Very keen learner', 12, '2026-06-04 15:04:46', '2026-06-04 15:04:46'),
(5, 7, 3, 41, 1, 90.00, 100.00, 0, 'Excellent', 12, '2026-06-04 15:05:17', '2026-06-04 15:05:17'),
(6, 7, 3, 39, 1, 95.00, 100.00, 0, 'Outstanding', 12, '2026-06-04 15:05:35', '2026-06-04 15:05:35'),
(7, 7, 3, 38, 1, 80.00, 100.00, 0, 'Impressive performance', 12, '2026-06-04 15:05:45', '2026-06-04 15:05:45'),
(8, 11, 3, 36, 1, 75.00, 100.00, 0, 'Great effort keep it up', 12, '2026-06-04 15:07:59', '2026-06-04 15:07:59'),
(9, 11, 3, 42, 1, 60.00, 100.00, 0, 'Can do better next time', 12, '2026-06-04 15:08:00', '2026-06-04 15:08:00'),
(10, 11, 3, 37, 1, 88.00, 100.00, 0, 'Great work shown', 12, '2026-06-04 15:08:01', '2026-06-04 15:08:01'),
(11, 11, 3, 40, 1, 90.00, 100.00, 0, 'Excellent keep it up', 12, '2026-06-04 15:08:02', '2026-06-04 15:08:02'),
(12, 11, 3, 38, 1, 67.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:08:03', '2026-06-04 15:08:03'),
(13, 11, 3, 41, 1, 52.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:08:04', '2026-06-04 15:08:04'),
(14, 11, 3, 39, 1, 49.00, 100.00, 0, 'Needs to work harder', 12, '2026-06-04 15:08:05', '2026-06-04 15:08:05'),
(15, 2, 3, 36, 1, 65.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:13:29', '2026-06-04 15:13:29'),
(16, 2, 3, 42, 1, 76.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:13:29', '2026-06-04 15:13:29'),
(17, 2, 3, 37, 1, 56.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:13:30', '2026-06-04 15:13:30'),
(18, 2, 3, 40, 1, 59.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:13:31', '2026-06-04 15:13:31'),
(19, 2, 3, 38, 1, 62.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:13:32', '2026-06-04 15:13:32'),
(20, 2, 3, 41, 1, 70.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:13:32', '2026-06-04 15:13:32'),
(21, 2, 3, 39, 1, 58.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:13:33', '2026-06-04 15:13:33'),
(22, 13, 3, 39, 1, 82.00, 100.00, 0, 'Impressive', 12, '2026-06-04 15:14:17', '2026-06-04 15:14:17'),
(23, 13, 3, 41, 1, 75.00, 100.00, 0, 'Impressive', 12, '2026-06-04 15:14:18', '2026-06-04 15:14:18'),
(24, 13, 3, 38, 1, 80.00, 100.00, 0, 'Impressive', 12, '2026-06-04 15:14:19', '2026-06-04 15:14:19'),
(25, 13, 3, 40, 1, 70.00, 100.00, 0, 'Impressive', 12, '2026-06-04 15:14:19', '2026-06-04 15:14:19'),
(26, 13, 3, 37, 1, 59.00, 100.00, 0, 'Impressive', 12, '2026-06-04 15:14:20', '2026-06-04 15:14:20'),
(27, 13, 3, 42, 1, 66.00, 100.00, 0, 'Impressive', 12, '2026-06-04 15:14:21', '2026-06-04 15:14:21'),
(28, 13, 3, 36, 1, 68.00, 100.00, 0, 'Impressive', 12, '2026-06-04 15:14:21', '2026-06-04 15:14:21'),
(29, 3, 3, 36, 1, 69.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:16:23', '2026-06-04 15:16:23'),
(30, 3, 3, 42, 1, 45.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:16:24', '2026-06-04 15:16:24'),
(31, 3, 3, 37, 1, 48.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:16:25', '2026-06-04 15:16:25'),
(32, 3, 3, 40, 1, 55.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:16:26', '2026-06-04 15:16:26'),
(33, 3, 3, 38, 1, 68.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:16:27', '2026-06-04 15:16:27'),
(34, 3, 3, 41, 1, 70.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:16:28', '2026-06-04 15:16:28'),
(35, 3, 3, 39, 1, 53.00, 100.00, 0, 'Can do better', 12, '2026-06-04 15:16:29', '2026-06-04 15:16:29'),
(36, 4, 3, 36, 1, 53.00, 100.00, 0, 'Try harder', 12, '2026-06-04 15:17:53', '2026-06-04 15:17:53'),
(37, 4, 3, 42, 1, 57.00, 100.00, 0, 'Try harder', 12, '2026-06-04 15:17:55', '2026-06-04 15:17:55'),
(38, 4, 3, 37, 1, 68.00, 100.00, 0, 'Try harder', 12, '2026-06-04 15:17:55', '2026-06-04 15:17:55'),
(39, 4, 3, 40, 1, 45.00, 100.00, 0, 'Try harder', 12, '2026-06-04 15:17:56', '2026-06-04 15:17:56'),
(40, 4, 3, 38, 1, 69.00, 100.00, 0, 'Try harder', 12, '2026-06-04 15:17:57', '2026-06-04 15:17:57'),
(41, 4, 3, 41, 1, 53.00, 100.00, 0, 'Try harder', 12, '2026-06-04 15:17:57', '2026-06-04 15:17:57'),
(42, 4, 3, 39, 1, 71.00, 100.00, 0, 'Try harder', 12, '2026-06-04 15:17:58', '2026-06-04 15:17:58'),
(43, 5, 3, 36, 1, 50.00, 100.00, 0, 'Better performance', 12, '2026-06-04 15:19:02', '2026-06-04 15:19:02'),
(44, 5, 3, 42, 1, 60.00, 100.00, 0, 'Better performance', 12, '2026-06-04 15:19:03', '2026-06-04 15:19:03'),
(45, 5, 3, 37, 1, 70.00, 100.00, 0, 'Better performance', 12, '2026-06-04 15:19:04', '2026-06-04 15:19:04'),
(46, 5, 3, 40, 1, 80.00, 100.00, 0, 'Better performance', 12, '2026-06-04 15:19:04', '2026-06-04 15:19:04'),
(47, 5, 3, 38, 1, 68.00, 100.00, 0, 'Better performance', 12, '2026-06-04 15:19:05', '2026-06-04 15:19:05'),
(48, 5, 3, 41, 1, 63.00, 100.00, 0, 'Better performance', 12, '2026-06-04 15:19:06', '2026-06-04 15:19:06'),
(49, 5, 3, 39, 1, 75.00, 100.00, 0, 'Better performance', 12, '2026-06-04 15:19:06', '2026-06-04 15:19:06'),
(50, 38, 4, 60, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:31:16', '2026-06-06 18:31:16'),
(51, 38, 4, 47, 1, 45.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:33:33', '2026-06-06 18:33:33'),
(52, 38, 4, 48, 1, 50.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:33:42', '2026-06-06 18:33:42'),
(53, 38, 4, 49, 1, 55.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:33:46', '2026-06-06 18:33:46'),
(54, 38, 4, 50, 1, 80.00, 100.00, 0, 'Excellent', 63, '2026-06-06 18:33:53', '2026-06-06 18:33:53'),
(55, 38, 4, 52, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:33:55', '2026-06-06 18:33:55'),
(56, 38, 4, 53, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:33:58', '2026-06-06 18:33:58'),
(57, 38, 4, 54, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:34:01', '2026-06-06 18:34:01'),
(58, 38, 4, 62, 1, 85.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:34:48', '2026-06-06 18:34:48'),
(59, 38, 4, 61, 1, 75.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:34:48', '2026-06-06 18:34:48'),
(60, 38, 4, 30, 1, 76.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:34:49', '2026-06-06 18:34:49'),
(61, 38, 4, 20, 1, 81.00, 100.00, 0, 'Outstanding', 63, '2026-06-06 18:34:52', '2026-06-06 18:34:52'),
(62, 38, 4, 59, 1, 54.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:34:53', '2026-06-06 18:34:53'),
(63, 36, 4, 30, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:35:50', '2026-06-06 18:35:50'),
(64, 36, 4, 61, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:35:51', '2026-06-06 18:35:51'),
(65, 36, 4, 62, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:35:52', '2026-06-06 18:35:52'),
(66, 36, 4, 60, 1, 70.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:35:58', '2026-06-06 18:35:58'),
(67, 36, 4, 20, 1, 60.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:00', '2026-06-06 18:36:00'),
(68, 36, 4, 59, 1, 49.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:02', '2026-06-06 18:36:02'),
(69, 36, 4, 58, 1, 51.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:05', '2026-06-06 18:36:05'),
(70, 36, 4, 57, 1, 74.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:07', '2026-06-06 18:36:07'),
(71, 36, 4, 56, 1, 76.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:10', '2026-06-06 18:36:10'),
(72, 36, 4, 54, 1, 90.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:12', '2026-06-06 18:36:12'),
(73, 36, 4, 53, 1, 88.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:16', '2026-06-06 18:36:16'),
(74, 36, 4, 52, 1, 76.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:18', '2026-06-06 18:36:18'),
(75, 36, 4, 50, 1, 47.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:21', '2026-06-06 18:36:21'),
(76, 36, 4, 49, 1, 32.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:24', '2026-06-06 18:36:24'),
(77, 36, 4, 48, 1, 54.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:26', '2026-06-06 18:36:26'),
(78, 36, 4, 47, 1, 56.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:36:28', '2026-06-06 18:36:28'),
(79, 41, 4, 62, 1, 78.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:34', '2026-06-06 18:37:34'),
(80, 41, 4, 61, 1, 68.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:37', '2026-06-06 18:37:37'),
(81, 41, 4, 30, 1, 67.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:40', '2026-06-06 18:37:40'),
(82, 41, 4, 60, 1, 68.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:42', '2026-06-06 18:37:42'),
(83, 41, 4, 20, 1, 59.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:45', '2026-06-06 18:37:45'),
(84, 41, 4, 59, 1, 65.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:48', '2026-06-06 18:37:48'),
(85, 41, 4, 58, 1, 69.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:51', '2026-06-06 18:37:51'),
(86, 41, 4, 57, 1, 64.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:54', '2026-06-06 18:37:54'),
(87, 41, 4, 56, 1, 58.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:56', '2026-06-06 18:37:56'),
(88, 41, 4, 54, 1, 78.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:37:59', '2026-06-06 18:37:59'),
(89, 41, 4, 53, 1, 95.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:38:03', '2026-06-06 18:38:03'),
(90, 41, 4, 52, 1, 50.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:38:05', '2026-06-06 18:38:05'),
(91, 41, 4, 50, 1, 89.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:38:07', '2026-06-06 18:38:07'),
(92, 41, 4, 49, 1, 76.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:38:09', '2026-06-06 18:38:09'),
(93, 41, 4, 48, 1, 69.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:38:12', '2026-06-06 18:38:12'),
(94, 41, 4, 47, 1, 58.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:38:15', '2026-06-06 18:38:15'),
(95, 39, 4, 47, 1, 36.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:38:39', '2026-06-06 18:38:39'),
(96, 39, 4, 48, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:38:41', '2026-06-06 18:38:41'),
(97, 39, 4, 49, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:38:42', '2026-06-06 18:38:42'),
(98, 39, 4, 50, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:38:43', '2026-06-06 18:38:43'),
(99, 39, 4, 52, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:38:45', '2026-06-06 18:38:45'),
(100, 39, 4, 57, 1, 69.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:39:03', '2026-06-06 18:39:03'),
(101, 39, 4, 56, 1, 56.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:39:04', '2026-06-06 18:39:04'),
(102, 39, 4, 54, 1, 68.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:39:05', '2026-06-06 18:39:05'),
(103, 39, 4, 53, 1, 56.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:39:05', '2026-06-06 18:39:05'),
(104, 39, 4, 58, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:39:11', '2026-06-06 18:39:11'),
(105, 39, 4, 59, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:39:12', '2026-06-06 18:39:12'),
(106, 39, 4, 20, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:39:12', '2026-06-06 18:39:12'),
(107, 39, 4, 60, 1, NULL, 100.00, 1, NULL, 63, '2026-06-06 18:39:13', '2026-06-06 18:39:13'),
(108, 39, 4, 30, 1, 68.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:39:20', '2026-06-06 18:39:39'),
(109, 39, 4, 62, 1, 47.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:39:36', '2026-06-06 18:39:36'),
(110, 39, 4, 61, 1, 78.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:39:38', '2026-06-06 18:39:38'),
(111, 40, 4, 62, 1, 87.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:38', '2026-06-06 18:40:38'),
(112, 40, 4, 61, 1, 69.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:40', '2026-06-06 18:40:40'),
(113, 40, 4, 30, 1, 76.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:42', '2026-06-06 18:40:42'),
(114, 40, 4, 60, 1, 69.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:44', '2026-06-06 18:40:44'),
(115, 40, 4, 20, 1, 67.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:46', '2026-06-06 18:40:46'),
(116, 40, 4, 59, 1, 56.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:48', '2026-06-06 18:40:48'),
(117, 40, 4, 58, 1, 70.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:51', '2026-06-06 18:40:51'),
(118, 40, 4, 57, 1, 69.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:54', '2026-06-06 18:40:54'),
(119, 40, 4, 56, 1, 54.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:56', '2026-06-06 18:40:56'),
(120, 40, 4, 54, 1, 58.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:40:59', '2026-06-06 18:40:59'),
(121, 40, 4, 53, 1, 69.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:41:01', '2026-06-06 18:41:01'),
(122, 40, 4, 52, 1, 82.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:41:03', '2026-06-06 18:41:03'),
(123, 40, 4, 50, 1, 90.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:41:06', '2026-06-06 18:41:06'),
(124, 40, 4, 49, 1, 80.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:41:09', '2026-06-06 18:41:09'),
(125, 40, 4, 48, 1, 65.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:41:11', '2026-06-06 18:41:11'),
(126, 40, 4, 47, 1, 75.00, 100.00, 0, 'Can do better', 63, '2026-06-06 18:41:13', '2026-06-06 18:41:13');

-- --------------------------------------------------------

--
-- Table structure for table `term_report_ct_comment`
--

DROP TABLE IF EXISTS `term_report_ct_comment`;
CREATE TABLE IF NOT EXISTS `term_report_ct_comment` (
  `ctc_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id_fk` int NOT NULL,
  `student_id_fk` int NOT NULL,
  `term` tinyint(1) NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `by_user_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ctc_id`),
  UNIQUE KEY `class_id_fk_student_id_fk_term` (`class_id_fk`,`student_id_fk`,`term`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `term_report_ct_comment`
--

INSERT INTO `term_report_ct_comment` (`ctc_id`, `class_id_fk`, `student_id_fk`, `term`, `comment`, `by_user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 36, 1, 'Hardworking student', 12, '2026-06-04 15:23:34', '2026-06-04 15:23:34'),
(2, 3, 42, 1, 'Hardworking student', 12, '2026-06-04 15:23:41', '2026-06-04 15:23:41'),
(3, 3, 37, 1, 'Student needs to focus more on studies', 12, '2026-06-04 15:25:33', '2026-06-04 15:25:33'),
(4, 3, 40, 1, 'Can do better if student can concentrate more on academic activities', 12, '2026-06-04 15:26:20', '2026-06-04 15:26:20'),
(5, 3, 38, 1, 'Impressive performance', 12, '2026-06-04 15:26:33', '2026-06-04 15:26:33'),
(6, 3, 41, 1, 'Impressive performance', 12, '2026-06-04 15:26:42', '2026-06-04 15:26:42'),
(7, 3, 39, 1, 'Impressive performance', 12, '2026-06-04 15:26:51', '2026-06-04 15:26:51'),
(8, 4, 47, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:42:39', '2026-06-06 18:42:39'),
(9, 4, 48, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:42:48', '2026-06-06 18:42:48'),
(10, 4, 49, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:42:53', '2026-06-06 18:42:53'),
(11, 4, 50, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:42:57', '2026-06-06 18:42:57'),
(12, 4, 52, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:02', '2026-06-06 18:43:02'),
(13, 4, 53, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:07', '2026-06-06 18:43:07'),
(14, 4, 54, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:13', '2026-06-06 18:43:13'),
(15, 4, 56, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:18', '2026-06-06 18:43:18'),
(16, 4, 57, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:24', '2026-06-06 18:43:24'),
(17, 4, 58, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:27', '2026-06-06 18:43:27'),
(18, 4, 59, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:31', '2026-06-06 18:43:31'),
(19, 4, 20, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:37', '2026-06-06 18:43:37'),
(20, 4, 60, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:43', '2026-06-06 18:43:43'),
(21, 4, 30, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:47', '2026-06-06 18:43:47'),
(22, 4, 61, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:51', '2026-06-06 18:43:51'),
(23, 4, 62, 1, 'A pleasure to teach — continues to show strong effort, curiosity, and progress.', 63, '2026-06-06 18:43:55', '2026-06-06 18:43:55'),
(24, 5, 46, 1, 'xCxczxc', 63, '2026-06-19 15:47:01', '2026-06-19 15:47:01');

-- --------------------------------------------------------

--
-- Table structure for table `term_report_principal_comment`
--

DROP TABLE IF EXISTS `term_report_principal_comment`;
CREATE TABLE IF NOT EXISTS `term_report_principal_comment` (
  `prc_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id_fk` int NOT NULL,
  `student_id_fk` int NOT NULL,
  `term` tinyint(1) NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `by_user_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`prc_id`),
  UNIQUE KEY `class_id_fk_student_id_fk_term` (`class_id_fk`,`student_id_fk`,`term`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `term_report_principal_comment`
--

INSERT INTO `term_report_principal_comment` (`prc_id`, `class_id_fk`, `student_id_fk`, `term`, `comment`, `by_user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 36, 1, 'Outstanding student that always eager to assist teachers and peer and took part in extra curricular activity', 1, '2026-06-04 15:51:03', '2026-06-04 15:51:03'),
(2, 3, 42, 1, 'Positive learning culture observed; continue reinforcing literacy routines.', 1, '2026-06-04 15:51:11', '2026-06-04 15:51:11'),
(3, 3, 37, 1, 'Notable growth in team-based planning; maintain momentum next term.', 1, '2026-06-04 15:51:34', '2026-06-04 15:51:34'),
(4, 3, 40, 1, 'Attendance and participation up; address late submissions in target subjects.', 1, '2026-06-04 15:51:46', '2026-06-04 15:51:46'),
(5, 3, 38, 1, 'Incidents reduced; ensure consistent follow-through across all grade levels.', 1, '2026-06-04 15:52:03', '2026-06-04 15:52:03'),
(6, 3, 41, 1, 'Formative assessments driving instruction well; sharpen differentiation strategies.', 1, '2026-06-04 15:52:19', '2026-06-04 15:52:19'),
(7, 3, 39, 1, 'Good responsiveness; expand outreach for under-connected families.', 1, '2026-06-04 15:52:33', '2026-06-04 15:52:33'),
(8, 4, 47, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:49:31', '2026-06-06 18:49:31'),
(9, 4, 48, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:49:34', '2026-06-06 18:49:34'),
(10, 4, 49, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:49:38', '2026-06-06 18:49:38'),
(11, 4, 50, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:49:43', '2026-06-06 18:49:43'),
(12, 4, 52, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:49:46', '2026-06-06 18:49:46'),
(13, 4, 53, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:49:51', '2026-06-06 18:49:51'),
(14, 4, 54, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:49:55', '2026-06-06 18:49:55'),
(15, 4, 56, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:00', '2026-06-06 18:50:00'),
(16, 4, 57, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:04', '2026-06-06 18:50:04'),
(17, 4, 58, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:10', '2026-06-06 18:50:10'),
(18, 4, 59, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:15', '2026-06-06 18:50:15'),
(19, 4, 20, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:20', '2026-06-06 18:50:20'),
(20, 4, 60, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:25', '2026-06-06 18:50:25'),
(21, 4, 30, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:29', '2026-06-06 18:50:29'),
(22, 4, 61, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:32', '2026-06-06 18:50:32'),
(23, 4, 62, 1, 'Working steadily and showing good growth. Keep building on this momentum.', 1, '2026-06-06 18:50:36', '2026-06-06 18:50:36');

-- --------------------------------------------------------

--
-- Table structure for table `term_report_status`
--

DROP TABLE IF EXISTS `term_report_status`;
CREATE TABLE IF NOT EXISTS `term_report_status` (
  `trs_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id_fk` int NOT NULL,
  `term` tinyint(1) NOT NULL,
  `status` enum('collecting','ct_submitted','published') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'collecting',
  `ct_submitted_by` int DEFAULT NULL,
  `ct_submitted_at` datetime DEFAULT NULL,
  `published_by` int DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  PRIMARY KEY (`trs_id`),
  UNIQUE KEY `class_id_fk_term` (`class_id_fk`,`term`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `term_report_status`
--

INSERT INTO `term_report_status` (`trs_id`, `class_id_fk`, `term`, `status`, `ct_submitted_by`, `ct_submitted_at`, `published_by`, `published_at`) VALUES
(1, 3, 1, 'published', 12, '2026-06-04 15:27:08', 1, '2026-06-04 15:53:04'),
(2, 3, 2, 'collecting', NULL, NULL, NULL, NULL),
(3, 3, 3, 'collecting', NULL, NULL, NULL, NULL),
(4, 4, 1, 'published', 63, '2026-06-06 18:48:15', 1, '2026-06-06 18:50:56'),
(5, 4, 2, 'collecting', NULL, NULL, NULL, NULL),
(6, 4, 3, 'collecting', NULL, NULL, NULL, NULL),
(7, 5, 1, 'collecting', NULL, NULL, NULL, NULL),
(8, 5, 2, 'collecting', NULL, NULL, NULL, NULL),
(9, 5, 3, 'collecting', NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `district_id_fk`, `password`, `username`, `fname`, `lname`, `oname`, `gender`, `dob`, `address`, `email`, `pending_email_update`, `phone`, `created_date`, `created_time`, `online_status`, `password_reset_code`, `profile_photo`, `is_a_parent`, `updated_date`, `updated_time`, `security_token`, `security_token_expiry`, `account_status`, `user_status`, `two_factor_method`, `two_factor_secret`, `two_factor_enabled`, `otp_code`, `otp_expiry`, `otp_verified`, `reset_token`, `reset_token_expiry`) VALUES
(1, 56, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Pio', 'Baleicoqe', '', 'Male', '1982-01-06', 'Veivauceva 3, 6 Miles, Tacirua', 'piobaleicoqe@yahoo.com', NULL, 9896700, '2025-05-14', 1747194903, 'Online', NULL, 'avatar_1721681108.jpg', 0, '2026-05-11 11:48:11', 0, NULL, NULL, 'Active', 'Active', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(11, 50, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Pio', 'Baleicoqe', '', 'Male', '2026-01-30', '6 Miles', 'piobaleicoqe2@gmail.com', NULL, 1234567, '2026-01-14', 1768363922, 'Offline', '', '1778626718_73cc384e833c05112504.png', 0, NULL, 0, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(12, 16, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Sam', 'White', '', 'Male', '2026-02-02', 'sgsfgsdgdsggd', 'info@baleicoqe.com', NULL, 1234567, '2026-02-02', 1769998699, 'Offline', NULL, '1769998699_c2f5e3c9d0452d1e685c.jpg', 0, NULL, 0, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
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
(37, 84, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', 'Maeri24', 'Maeri', 'Luikali', '', 'Female', '2009-05-29', NULL, '', NULL, NULL, '2026-05-27', 1779838403, 'Online', 'ee6feb9e0b0ecc80426d6402b403cc5c', '1780556693_6e7f5fa5a00eb5aaa8ef.jpg', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(38, 193, '', '', 'Jenifer ', 'Pareti', '', 'Female', '2009-05-05', NULL, '', NULL, NULL, '2026-05-28', 1779913023, 'Offline', '118eafb0ee0c3e7cedd4d57056ee1a92', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(39, 47, '', '', 'Maleli', 'Tora', 'Uluiviti', 'Male', '2009-04-09', NULL, '', NULL, NULL, '2026-05-28', 1779913079, 'Offline', 'ac705d10f98d9a23b7ddfe7936a135f0', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(40, 133, '', '', 'Ilisabeta', 'Matata', '', 'Female', '2009-07-23', NULL, '', NULL, NULL, '2026-05-28', 1779913118, 'Offline', 'e50bfefbd46dcfe403f25fd5b1e23ced', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(41, 111, '', '', 'Edwin', 'Smith', '', 'Male', '2009-02-04', NULL, '', NULL, NULL, '2026-05-28', 1779913168, 'Offline', '2305f438366c4ce6a2bab4b10d8cc14a', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(42, 159, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', 'Sherine', 'Sherine', 'Kumar', '', 'Female', '2009-08-28', NULL, '', NULL, NULL, '2026-05-28', 1779913233, 'Offline', '5035a959b0618c0b4c12050e1288966c', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(43, 42, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Bese', 'Takina', '', 'Male', '2026-06-13', 'Suva City', 'bese@yahoo.com', NULL, 1234567, '2026-06-01', 1780261886, 'Online', '2aa69fe13c95e759ef2b3604b92dfd77', '1780261886_fbd7205fcea30083b90e.jpg', 1, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(44, 87, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Peter', 'Toganivalu', '', 'Male', '1889-06-02', 'Wailevu', 'pitatoga@yahoo.com', NULL, 8796578, '2026-06-01', 1780264419, 'Offline', '5086822e64ed10884c2598263d28be97', '', 1, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(45, 195, '$2y$10$a9UJNgUtjYk57LCNTl6KDueXsUmOUW9WgQavhu8j5hoCzTZIwYrc6', '', 'Mandy', 'Lopez', '', 'Female', '1969-06-04', 'Baw View Heights, Suva', 'mlopez@yahoo.com', NULL, 8769087, '2026-06-02', 1780342192, 'Offline', 'dd16ba51370cc2306d63815342b7c179', '1780342192_beac329b5cfa7ad3c080.jpg', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(46, 34, '', '1011265', ' Esiteri', 'Adrole', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(47, 34, '', '984891', ' Litiana', 'Balawakula', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(48, 34, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '1025950', ' Milika', 'Botiki', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Online', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(49, 34, '', '974385', ' Sereana', 'Buwawa', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(50, 34, '', '1018158', ' Lusiana', 'Finau', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(51, 34, '', '859136', ' Alesi', 'Lomasalato', '', 'Male', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(52, 34, '', '1046028', 'Luke', 'Lomasalato ', '', 'Male', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(53, 34, '', '821234', ' Talica', 'Naikelekelevesi', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(54, 34, '', '963909', ' Kelera', 'Naisaki', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(55, 34, '', '991456', ' Lemeki', 'Naitagotago', '', 'Male', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(56, 34, '', '954210', ' Waisea', 'Nasili', '', 'Male', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(57, 34, '', '980618', 'Akansha', 'Prakash', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(58, 34, '', '850530', ' Sailosi', 'Qiolele', '', 'Male', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(59, 34, '', '1026460', ' Iowani', 'Rasumu', '', 'Male', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(60, 34, '', '1031302', ' Lewai', 'Saluta', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(61, 34, '', '1025924', ' Rosi', 'Tupou', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(62, 34, '', '906778', ' Nanise', 'Waqaitanoa', '', 'Female', '0000-00-00', '', '', '', 0, '0000-00-00', 0, 'Offline', '', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(63, 31, '$2y$10$93eKtPyN0kYpE5BFnr3XhOCBP9ujIccBqNBD.YfhgPdai5JaMZyqK', '', 'Uwate', 'Vakaloloma', '', 'Male', '1987-04-26', 'Lot 2, Naividaliga New Subdivision, Wainibuku', 'uwatevakaloloma1987@gmail.com', NULL, 2148885, '2026-06-06', 1780725345, 'Online', '14463583cda9e1d65a0a9a428bb1a815', '', 0, NULL, NULL, NULL, NULL, 'Active', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(64, 115, '', '', 'James', 'Cartel', '', 'Male', '2026-06-18', 'Suva 123', '', NULL, NULL, '2026-06-18', 1781731975, 'Offline', 'ee6d4d46a279c35b1fa31dbf0d6df3f1', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(65, 193, '', '', 'Petero', 'Simons', '', 'Male', '2026-06-18', 'Suva', '', NULL, NULL, '2026-06-18', 1781751375, 'Offline', '3333b17b3b0c1fdb264a48af087ac8a5', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(66, 28, '', '2606115798', 'Seini', 'Seru', '', 'Female', '2026-06-18', NULL, '', NULL, NULL, '2026-06-18', 1781752541, 'Offline', 'f7ac7e362ca6c9a47cb6fcc00ea5ec9a', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(67, 37, '', '3421345678', 'Jeke', 'Sade', '', 'Male', '2026-06-27', NULL, '', NULL, NULL, '2026-06-18', 1781753182, 'Offline', '5b042e3f8552d2cb767e0e907f43ba35', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(68, 34, '', '2606707539', 'Maikali', 'Rayasi', '', 'Male', '2005-06-25', NULL, '', NULL, NULL, '2026-06-25', 1782344553, 'Offline', 'f81aebabe31701796cd1aeaa96529ce7', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(69, 61, '', '2606635288', 'Venesa', 'Radio', '', 'Female', '2008-06-25', NULL, '', NULL, NULL, '2026-06-25', 1782346401, 'Offline', 'ed20a69c92b3c515381baffc4a4eb89b', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(70, 45, '', '2606985614', 'Mike', 'Test', '', 'Male', '2006-06-25', NULL, '', NULL, NULL, '2026-06-25', 1782356737, 'Offline', '1857a2e831fe19accc8d9b42fa3b45c3', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(71, 29, '', '2606137605', 'Peter', 'Stevens', '', 'Male', '2006-06-25', NULL, '', NULL, NULL, '2026-06-25', 1782357199, 'Offline', '7b7f0888187b8bd19860f7081794fc2b', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(72, 23, '', '2606173080', 'Peter', 'Stevension', '', 'Male', '2006-06-25', NULL, '', NULL, NULL, '2026-06-25', 1782358108, 'Offline', '8fb780d2d421ba6234dfd877ebcaddd9', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(73, 23, '', '2606432679', 'Mick', 'Stevens', '', 'Male', '2006-06-25', NULL, '', NULL, NULL, '2026-06-25', 1782358573, 'Offline', '20f36bab75946a2b5578bb2ab3b4a3ce', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL),
(74, 47, '', '2606977863', 'Samueal', 'Gaumguo', '', 'Male', '2007-06-26', NULL, '', NULL, NULL, '2026-06-26', 1782402270, 'Offline', '141d4596081fbfa7a32876d1b383092f', '', 0, NULL, NULL, NULL, NULL, 'Pending Activation', 'Active', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=1119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(883, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-03', 1780479865, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(884, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780503875, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(885, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780508758, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(886, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780508775, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(887, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780508780, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(888, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-04', 1780508785, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(889, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Update Role Permissions', 'Permissions for role Student have been updated. Total permissions: 10', '2026-06-04', 1780508913, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(890, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780509011, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(891, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780509023, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(892, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780509131, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(893, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780509148, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(894, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780513259, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(895, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780513261, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(896, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780513274, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(897, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780513286, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(898, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780513294, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(899, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780513297, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(900, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780516700, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(901, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780516702, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(902, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780533101, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(903, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-04', 1780533124, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(904, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780533232, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(905, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780533236, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(906, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780533248, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(907, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780533252, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(908, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-04', 1780533386, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(909, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780533460, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(910, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-04', 1780543698, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(911, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780543786, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(912, 44, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780543805, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(913, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780543814, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(914, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780543819, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(915, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-04', 1780543824, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(916, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Update Role Permissions', 'Permissions for role Principal have been updated. Total permissions: 76', '2026-06-04', 1780543957, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(917, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780544023, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(918, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780544028, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(919, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780544156, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(920, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780544966, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(921, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780544970, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(922, 44, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780545259, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(923, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780545278, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(924, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-04', 1780546292, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(925, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780546295, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(926, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-04', 1780546946, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(927, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-04', 1780556593, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(928, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-04', 1780556598, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(929, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Edit User', 'User \"Maeri Luikali\" updated', '2026-06-04', 1780556693, '<i class=\"ki-duotone ki-check-circle\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(930, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-04', 1780556694, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(931, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780600123, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(932, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780602529, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(933, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-05', 1780611725, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(934, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780611730, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(935, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-05', 1780612063, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(936, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-05', 1780612206, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(937, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780612209, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(938, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780626828, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(939, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780626881, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(940, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780641496, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(941, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780642722, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(942, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780652398, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(943, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-05', 1780656949, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(944, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780681093, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(945, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780681103, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(946, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780682880, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(947, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-06', 1780692308, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(948, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780692312, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(949, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780714947, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(950, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-06', 1780714983, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(951, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780715025, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(952, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-06', 1780715030, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(953, 42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-06', 1780715066, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(954, 37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780715079, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(955, 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-06', 1780717999, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(956, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780718117, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(957, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-06', 1780718131, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(958, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-06', 1780718171, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(959, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New School', 'School \"William Cross College\" has been created successfully!', '2026-06-06', 1780718462, '<i class=\"ki-duotone ki-save-2\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(960, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-06', 1780723598, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(961, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-06', 1780724144, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(962, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-06', 1780724179, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(963, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-06', 1780724305, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(964, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Alesi Lomasalato', '2026-06-06', 1780724334, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(965, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Esiteri Adrole', '2026-06-06', 1780724358, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(966, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Iowani Rasumu', '2026-06-06', 1780724377, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(967, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Kelera Naisaki', '2026-06-06', 1780724395, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(968, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Lemeki Naitagotago', '2026-06-06', 1780724408, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(969, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Milika Botiki', '2026-06-06', 1780724421, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(970, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Lewai Saluta', '2026-06-06', 1780724442, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(971, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Rosi Tupou', '2026-06-06', 1780724454, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success');
INSERT INTO `user_log` (`user_log_id`, `user_id_fk`, `ip_aadress`, `user_agent`, `user_device`, `log_title`, `log_desc`, `log_date`, `log_time`, `log_icon`, `log_theme`) VALUES
(972, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Sailosi Qiolele', '2026-06-06', 1780724470, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(973, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Sereana Buwawa', '2026-06-06', 1780724482, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(974, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Talica Naikelekelevesi', '2026-06-06', 1780724494, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(975, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Waisea Nasili', '2026-06-06', 1780724506, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(976, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for Akansha Prakash', '2026-06-06', 1780724517, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(977, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for James Smith', '2026-06-06', 1780724531, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(978, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for Luke Lomasalato ', '2026-06-06', 1780724616, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(979, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for Peni Ravai', '2026-06-06', 1780724628, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(980, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Nanise Waqaitanoa', '2026-06-06', 1780724642, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(981, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Lusiana Finau', '2026-06-06', 1780724656, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(982, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Admission Added', 'New admission for  Litiana Balawakula', '2026-06-06', 1780724669, '<i class=\"ki-duotone ki-element-plus\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'success'),
(983, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Classroom Created', 'Classroom \"Year 13A 2026\" created for year 2026', '2026-06-06', 1780725228, '<i class=\"ki-duotone ki-element-7\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(984, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Add New User', 'User \"Uwate Vakaloloma\" has been created successfully!', '2026-06-06', 1780725345, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(985, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-06', 1780725345, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(986, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"Uwate Vakaloloma\" assigned as Class Teacher for classroom ID 4', '2026-06-06', 1780725365, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(987, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"Sereana Buwawa\" assigned as Class Captain for classroom ID 4', '2026-06-06', 1780725423, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(988, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'Staff Assigned', '\"Sailosi Qiolele\" assigned as Assistant Class Captain for classroom ID 4', '2026-06-06', 1780725432, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(989, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-06', 1780725828, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(990, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780725855, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(991, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780726618, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(992, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-06', 1780728508, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(993, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780728515, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(994, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-06', 1780728830, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(995, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-06', 1780729026, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(996, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-06', 1780729032, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(997, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-06', 1780729037, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(998, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-08', 1780861196, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(999, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-08', 1780861214, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1000, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-09', 1780947151, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1001, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-09', 1780967622, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1002, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-18', 1781731917, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1003, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"James Cartel\" has been created successfully!', '2026-06-18', 1781731976, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(1004, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-18', 1781731976, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1005, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-18', 1781749603, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1006, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-18', 1781751324, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1007, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Petero Simons\" has been created successfully!', '2026-06-18', 1781751375, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(1008, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-18', 1781751375, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1009, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-18', 1781752479, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1010, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Seini Seru\" has been created successfully!', '2026-06-18', 1781752541, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(1011, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-18', 1781752541, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1012, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-18', 1781752608, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1013, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Jeke Sade\" has been created successfully!', '2026-06-18', 1781753182, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(1014, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-18', 1781753182, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1015, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-18', 1781753814, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(1016, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-18', 1781767812, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1017, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-18', 1781768052, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1018, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-19', 1781816169, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1019, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-19', 1781816618, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1020, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-19', 1781820814, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1021, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-19', 1781820830, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1022, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Student have been updated. Total permissions: 8', '2026-06-19', 1781820901, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1023, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Classroom Updated', 'Classroom \"Year 13A 2026\" updated (ID: 4)', '2026-06-19', 1781824274, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1024, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Classroom Created', 'Classroom \"Year 13B 2026\" created for year 2026', '2026-06-19', 1781828558, '<i class=\"ki-duotone ki-element-7\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1025, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Added', 'Enrolment created for  Alesi Lomasalato | Year: 2026 Term: 2', '2026-06-19', 1781830395, '<i class=\"ki-duotone ki-abstract-28\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1026, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Added', 'Enrolment created for  Esiteri Adrole | Year: 2026 Term: 2', '2026-06-19', 1781830420, '<i class=\"ki-duotone ki-abstract-28\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1027, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Added', 'Enrolment created for  Lemeki Naitagotago | Year: 2026 Term: 2', '2026-06-19', 1781830435, '<i class=\"ki-duotone ki-abstract-28\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1028, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Added', 'Enrolment created for  Milika Botiki | Year: 2026 Term: 2', '2026-06-19', 1781832435, '<i class=\"ki-duotone ki-abstract-28\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1029, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Updated', 'Enrolment ID 42 updated.', '2026-06-19', 1781836718, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1030, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Added', 'Enrolment created for  Milika Botiki | Year: 2026 Term: 2', '2026-06-19', 1781836751, '<i class=\"ki-duotone ki-abstract-28\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1031, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Classroom Created', 'Classroom \"Year 12B 2026\" created for year 2026', '2026-06-19', 1781837278, '<i class=\"ki-duotone ki-element-7\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1032, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Staff Assigned', '\"Uwate Vakaloloma\" assigned as Class Teacher for classroom ID 6', '2026-06-19', 1781837315, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(1033, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Staff Assigned', '\"Uwate Vakaloloma\" assigned as Class Teacher for classroom ID 5', '2026-06-19', 1781837401, '<i class=\"ki-duotone ki-people\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'primary'),
(1034, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-20', 1781895889, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1035, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-22', 1782083768, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1036, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-22', 1782083811, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1037, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-22', 1782098953, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1038, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-22', 1782102475, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(1039, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-22', 1782102476, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1040, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-22', 1782128707, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1041, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-23', 1782129686, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1042, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-23', 1782155770, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1043, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-23', 1782155781, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1044, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-23', 1782188839, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1045, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-23', 1782188851, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1046, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-25', 1782337140, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1047, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-25', 1782339146, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1048, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Permission', 'Permission \"My Exam\" has been created successfully!', '2026-06-25', 1782339194, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1049, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-06-25', 1782339194, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1050, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-25', 1782339271, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1051, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Parent have been updated. Total permissions: 1', '2026-06-25', 1782339280, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1052, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-25', 1782339284, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1053, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Student have been updated. Total permissions: 8', '2026-06-25', 1782339304, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1054, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-25', 1782339307, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1055, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Assistant Teacher have been updated. Total permissions: 69', '2026-06-25', 1782339315, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1056, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Assistant Teacher have been updated. Total permissions: 69', '2026-06-25', 1782339317, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1057, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-25', 1782344438, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(1058, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-25', 1782344468, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1059, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Maikali Rayasi\" has been created successfully!', '2026-06-25', 1782344553, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(1060, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-25', 1782344553, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1061, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Venesa Radio\" has been created successfully!', '2026-06-25', 1782346401, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(1062, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-25', 1782346401, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1063, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-25', 1782349519, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1064, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-25', 1782356113, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1065, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Mick Stevens\" has been created successfully!', '2026-06-25', 1782358573, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(1066, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-25', 1782358573, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1067, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-26', 1782400300, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1068, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New User', 'User \"Samueal Gaumguo\" has been created successfully!', '2026-06-26', 1782402270, '<i class=\"ki-duotone ki-user-tick\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'success'),
(1069, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-06-26', 1782402270, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1070, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-26', 1782404457, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1071, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Updated', 'Enrolment ID 18 updated.', '2026-06-26', 1782417523, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1072, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Updated', 'Enrolment ID 51 updated.', '2026-06-26', 1782418321, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1073, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Deleted', 'Enrolment ID 52 deleted.', '2026-06-26', 1782418501, '<i class=\"ki-duotone ki-trash\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span><span class=\"path4\"></span><span class=\"path5\"></span></i>', 'danger'),
(1074, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Enrolment Added', 'Enrolment created for  Iowani Rasumu | Year: 2026 Term: 2', '2026-06-26', 1782418554, '<i class=\"ki-duotone ki-abstract-28\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1075, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-26', 1782418603, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1076, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-26', 1782418603, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1077, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-26', 1782420326, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1078, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Add New Role', 'Role Admin has been created successfully!', '2026-06-26', 1782421546, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1079, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-26', 1782421546, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1080, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-26', 1782421707, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1081, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-26', 1782421734, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1082, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-26', 1782421858, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1083, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-29', 1782683144, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1084, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Admission Updated', 'Admission ID 19 updated for Jenifer  Pareti', '2026-06-29', 1782683215, '<i class=\"ki-duotone ki-pencil\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1085, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-29', 1782687699, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1086, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-30', 1782796407, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1087, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-30', 1782796429, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1088, 63, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-30', 1782796435, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(1089, 48, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-30', 1782796512, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1090, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-30', 1782798420, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1091, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-06-30', 1782798425, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1092, 48, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-06-30', 1782806127, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(1093, 48, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-06-30', 1782806130, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1094, 48, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-07-01', 1782834203, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1095, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-07-01', 1782834260, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1096, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Conduct Incident Logged', 'Conduct incident logged for admission ID 30', '2026-07-01', 1782841600, '<i class=\"ki-duotone ki-shield-cross\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1097, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Conduct Incident Updated', 'Conduct incident ID 1 updated', '2026-07-01', 1782841655, '<i class=\"ki-duotone ki-shield-cross\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1098, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-07-01', 1782841734, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1099, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-07-01', 1782841776, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1100, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"My Conduct\" has been updated successfully!', '2026-07-01', 1782841794, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1101, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-07-01', 1782841794, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1102, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Role Listing', 'User view role listing.', '2026-07-01', 1782841846, '<i class=\"ki-duotone ki-eye\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1103, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Student have been updated. Total permissions: 9', '2026-07-01', 1782841885, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1104, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Update Role Permissions', 'Permissions for role Student have been updated. Total permissions: 11', '2026-07-01', 1782841927, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'success'),
(1105, 48, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-07-01', 1782842020, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(1106, 48, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-07-01', 1782842022, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1107, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-07-01', 1782842042, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1108, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"My Conduct\" has been updated successfully!', '2026-07-01', 1782842089, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1109, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-07-01', 1782842089, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1110, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Edit Permission', 'Permission \"My Conduct\" has been updated successfully!', '2026-07-01', 1782843070, '<i class=\"ki-duotone ki-verify\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1111, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View Permission Listing', 'User viewed permission listing.', '2026-07-01', 1782843071, '<i class=\"ki-duotone ki-shield-tick\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'info'),
(1112, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Conduct Incident Logged', 'Conduct incident logged for admission ID 34', '2026-07-01', 1782843227, '<i class=\"ki-duotone ki-shield-cross\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1113, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'Conduct Incident Logged', 'Conduct incident logged for admission ID 34', '2026-07-01', 1782848129, '<i class=\"ki-duotone ki-shield-cross\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'warning'),
(1114, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-07-01', 1782854911, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1115, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Logout', 'Successfully logged out from Navuli Fiji.', '2026-07-01', 1782854947, '<i class=\"ki-duotone ki-entrance-right\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'danger'),
(1116, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-07-01', 1782854948, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary'),
(1117, 63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'View User Listing', 'User view user listing.', '2026-07-01', 1782855154, '<i class=\"ki-duotone ki-user\"><span class=\"path1\"></span><span class=\"path2\"></span><span class=\"path3\"></span></i>', 'warning'),
(1118, 48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'Desktop', 'User Login', 'Successfully login to Navuli Fiji.', '2026-07-01', 1782859502, '<i class=\"ki-duotone ki-entrance-left\"><span class=\"path1\"></span><span class=\"path2\"></span></i>', 'primary');

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(19, 45, '$2y$10$a9UJNgUtjYk57LCNTl6KDueXsUmOUW9WgQavhu8j5hoCzTZIwYrc6', '2026-06-02', 1780342192, 'Active'),
(20, 63, '$2y$10$1OGxlYprz9aXz1olzIM37eym32NOIo51WRMA.z5gGAAbKqBM9uAZm', '2026-06-06', 1780725345, 'Active');

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
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(46, 38, 7, '2026-05-28 08:17:03', '2026-05-28 08:17:03', 'Active'),
(47, 39, 7, '2026-05-28 08:17:59', '2026-05-28 08:17:59', 'Active'),
(48, 40, 7, '2026-05-28 08:18:38', '2026-05-28 08:18:38', 'Active'),
(50, 42, 7, '2026-05-28 08:20:33', '2026-05-28 08:20:33', 'Active'),
(51, 43, 5, '2026-06-01 09:11:26', '2026-06-01 09:11:26', 'Active'),
(52, 44, 3, '2026-06-01 09:53:39', '2026-06-01 09:53:39', 'Active'),
(53, 41, 7, NULL, '2026-06-01 11:52:05', 'Active'),
(54, 45, 5, '2026-06-02 07:29:52', '2026-06-02 07:29:52', 'Active'),
(56, 27, 7, NULL, '2026-06-02 11:21:55', 'Active'),
(57, 37, 7, NULL, '2026-06-04 19:04:53', 'Active'),
(58, 46, 7, NULL, NULL, 'Active'),
(59, 47, 7, NULL, NULL, 'Active'),
(60, 48, 7, NULL, NULL, 'Active'),
(61, 49, 7, NULL, NULL, 'Active'),
(62, 50, 7, NULL, NULL, 'Active'),
(63, 51, 7, NULL, NULL, 'Active'),
(64, 52, 7, NULL, NULL, 'Active'),
(65, 53, 7, NULL, NULL, 'Active'),
(66, 54, 7, NULL, NULL, 'Active'),
(67, 55, 7, NULL, NULL, 'Active'),
(68, 56, 7, NULL, NULL, 'Active'),
(69, 57, 7, NULL, NULL, 'Active'),
(70, 58, 7, NULL, NULL, 'Active'),
(71, 59, 7, NULL, NULL, 'Active'),
(72, 60, 7, NULL, NULL, 'Active'),
(73, 61, 7, NULL, NULL, 'Active'),
(74, 62, 7, NULL, NULL, 'Active'),
(75, 63, 5, '2026-06-06 17:55:45', '2026-06-06 17:55:45', 'Active'),
(76, 64, 7, '2026-06-18 09:32:56', '2026-06-18 09:32:56', 'Active'),
(77, 65, 7, '2026-06-18 14:56:15', '2026-06-18 14:56:15', 'Active'),
(78, 66, 7, '2026-06-18 15:15:41', '2026-06-18 15:15:41', 'Active'),
(79, 67, 7, '2026-06-18 15:26:22', '2026-06-18 15:26:22', 'Active'),
(80, 68, 7, '2026-06-25 11:42:33', '2026-06-25 11:42:33', 'Active'),
(81, 69, 7, '2026-06-25 12:13:21', '2026-06-25 12:13:21', 'Active'),
(82, 70, 7, '2026-06-25 15:05:37', '2026-06-25 15:05:37', 'Active'),
(83, 71, 7, '2026-06-25 15:13:19', '2026-06-25 15:13:19', 'Active'),
(84, 72, 7, '2026-06-25 15:28:28', '2026-06-25 15:28:28', 'Active'),
(85, 73, 7, '2026-06-25 15:36:13', '2026-06-25 15:36:13', 'Active'),
(86, 74, 7, '2026-06-26 03:44:30', '2026-06-26 03:44:30', 'Active');

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
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(71, 12, 'c1638c059edcc39e5295eec3bb4fd025736d0835780e374d35a55ece4182756b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-03', 1780473325, 1780473325, 'Active'),
(72, 12, 'ef8769385e8f7fdd84fb3e5352f4148d37b94f376c56e30d71bcd7a1a41291fe', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780503875, 1780503875, 'Signed Out'),
(73, 37, '8ff4313923892fb180a101171f7bc240c5c898c350249f81f29a5c8fd2f50e47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780508758, 1780508758, 'Signed Out'),
(74, 1, '99a57e0aa1c5b225fa466a4964fc62c2a126b8b2f2d3d3a6191e621578c8f227', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780508780, 1780508780, 'Signed Out'),
(75, 33, 'a1696dbe42a7d9ada78fae6546d9179ebf61e5de5d74c76a4e3d58722aa88309', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780509023, 1780509023, 'Signed Out'),
(76, 37, '08b06a46c910ed8179ee69d62a076131b1910e75ed39453426341fbef1aab8bd', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780509148, 1780509148, 'Signed Out'),
(77, 37, 'f0b34c8d81655af1ab3d0d0f287b5e41d5c0c919b2033b22ee2f0930dfcb8ce0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780513261, 1780513261, 'Signed Out'),
(78, 37, 'e8f963677e6f37f41ea66031b05d3fc78f3c7445959363303c0a7b47c75dccc8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780513286, 1780513286, 'Signed Out'),
(79, 12, '83fcd447dcaebe9d6f9771b8b753ad920d291c2c5a8dab56622e448aeaa1e24b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780513297, 1780513297, 'Signed Out'),
(80, 37, 'f1711a5dec52175df535959c4339884f59f936deeedf0efad38de06f5090fdee', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780516702, 1780516702, 'Signed Out'),
(81, 37, 'e2a6ceca8e5954afc998bf8b6d227a8a0d8f8ad4c3ea4022ef0e83fc31a8e231', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780533232, 1780533232, 'Signed Out'),
(82, 1, '2a655afa8401e5ca7be9e11adcb11c719713ba36fb165345ba683e5dad1db33f', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780533248, 1780533248, 'Signed Out'),
(83, 42, '59cba4292b9e4e63cbdeca14b773225bf465b805d88586fada65c0330e67537b', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780533460, 1780533460, 'Signed Out'),
(84, 44, '350566c894e982f3d9d89b5a10263532b7e87fe0de4d65f34858c80cf7806241', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780543805, 1780543805, 'Signed Out'),
(85, 1, '9ddb82885d540a8a4b4d5a8ee15a24d762f68c765c8ea13c346a27b5e69c3fa9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780543819, 1780543819, 'Signed Out'),
(86, 12, '8ad1488f56e292063fae71c025c0521f619b30f46c81d33ba31c768f6d29b78b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780544028, 1780544028, 'Signed Out'),
(87, 42, '1be01d5f2d9eaae7b7ae93e2309fb036baf997948179f9d09e6d5f35f48f2845', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-04', 1780544156, 1780544156, 'Active'),
(88, 1, 'e5bb443543e513ea2107c49a0f59bbbc446da735c4a999ae268267d4da333491', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780544970, 1780544970, 'Signed Out'),
(89, 42, '0453c3ce9d3f9655b6cbf17dbd1c32cd18337c7639a15b1650afb776364e909a', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-04', 1780545278, 1780545278, 'Active'),
(90, 12, 'f3058ce6ee72d91ddce0b2ed7b157378ad83bf0ca583096f92088d5309275610', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780546295, 1780546295, 'Active'),
(91, 12, 'e97e15b54a3260f2e48a934dc3fc22eed18dbc48b8d191313edaea8eb1e14d8e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-04', 1780556593, 1780556593, 'Active'),
(92, 12, '9a8b24fef04fc28f17b5f2f30bbe2c94fc282782a24a98bb85ff9ab2810a3f5a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-05', 1780600123, 1780600123, 'Signed Out'),
(93, 42, '1beea5ca13e6e7d6b4e91fc305cfe3dc8d043b12e832183d0774c9920027cf47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-05', 1780602529, 1780602529, 'Active'),
(94, 33, '3607bdbf4db72389597d3954e87385334edaa2f526676000a33afcd482a06370', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-05', 1780611730, 1780611730, 'Signed Out'),
(95, 12, '62237842d1db88ed606eb435466c439e56ac590098af49a016a4a28a929e363d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-05', 1780612209, 1780612209, 'Active'),
(96, 12, '5563b5bbbdb0346e0d5acdc47eb060ab906d698d0c69fabe858e7bee734748f2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-05', 1780626828, 1780626828, 'Active'),
(97, 42, 'dd05d527992605e90baad8b1d5ecd7a8a51626dfe79d4f5a0eefb60c11e65b01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-05', 1780626881, 1780626881, 'Active'),
(98, 12, 'bc6b0ae461fdf1ff4440519828a5ef7d91649eed9ec67909d4f32b1fefbaed99', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-05', 1780641496, 1780641496, 'Active'),
(99, 42, 'a45bea27225fbb0ba63bc5d673ecb0314acf72a285872ed9cfca97e241a4d406', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-05', 1780642722, 1780642722, 'Active'),
(100, 12, '92ed680ee6cdc9bd27c4ba9e999888ab7924819b6427bb1abedbaeab35c63186', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-05', 1780652398, 1780652398, 'Active'),
(101, 42, '45dda6ab6c08c7f2bfdc8c02a837f300bcccbe46456d5fc2a8e8eb5c585803e8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-05', 1780656949, 1780656949, 'Active'),
(102, 12, 'cf17e422a57bef120ea5e4ef754373c2fa1107657449aef2039f295a23beafce', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-06', 1780681093, 1780681093, 'Signed Out'),
(103, 42, '885fd7e7121b766adb1a56dd2187d2fcd9e1f2e8e16e544669f9a55c67ad6e50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-06', 1780681103, 1780681103, 'Signed Out'),
(104, 37, '7110f8e2a743145e9da62b6c9e336311ff5a531014d1b085db29ad26ae7ab8f3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-06', 1780682880, 1780682880, 'Active'),
(105, 42, 'f2ea7fcf73e2a2d4cb1793757df1eba79924ccc93d709de249411cd606dd4681', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-06', 1780692312, 1780692312, 'Signed Out'),
(106, 42, '95ce08122ae5b7318c97b2e7377b1e98fd973fd8b2bb029078c0c75db0e5a4e1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-06', 1780714947, 1780714947, 'Signed Out'),
(107, 42, '5b9727582fb2dc27e6b6601bae6154ddff1e261aba9eaebb86c9a93843478dd2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-06', 1780715025, 1780715025, 'Signed Out'),
(108, 37, '96dd747554a74010a1657efc7fc052206db692d684dd01cfc17cf7751804d9c7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-06', 1780715079, 1780715079, 'Active'),
(109, 1, 'bb86a399290dd867a45feff8e887a4c25c14a5c8fc830897783f6c5238fda90a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-06', 1780718117, 1780718117, 'Signed Out'),
(110, 63, '2e95ebe8339a04887fb433db4ae35fac915a229d258ef2ed43e1cc6be138b272', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-06', 1780725855, 1780725855, 'Signed Out'),
(111, 48, '7b83a30c1e0dcf95c85984d43af4f9686b77aff7013853e207d5f019d862a4c8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-06', 1780726618, 1780726618, 'Active'),
(112, 1, '5814e7949aea630dd7840f613ccd6f541ede28598e9258e60adedab9840a0a8b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-06', 1780728515, 1780728515, 'Signed Out'),
(113, 63, '04c8ab0aec557532a275b16d32086edebed5bbe62db7b4ac6b093316efe5ca75', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-06', 1780729032, 1780729032, 'Active'),
(114, 63, '9397346843028ba550b67f801482c5e1defaf6b1bbf0bed03fdb5a65f25c557e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-08', 1780861196, 1780861196, 'Active'),
(115, 48, '137dd147bfa78d02a02ec690b2d4fea9fe639dd10836cef88f277008e773d281', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-08', 1780861214, 1780861214, 'Active'),
(116, 63, 'b21b7e56a3b1eeff7e91a728f3ae5ddc1c58cd64f521087538f1923fcc1025d6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-09', 1780947151, 1780947151, 'Active'),
(117, 48, 'eea3d44fc87e4700fd3cdb6f80758c2f3a3fd5518ba2517a165df63653b283aa', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-09', 1780967622, 1780967622, 'Active'),
(118, 1, 'd63d22481e8c0b1091dc1a503efce67de66cd9204606af6bf8753c220e45a3fc', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-18', 1781731917, 1781731917, 'Active'),
(119, 63, '8d22031c3edad8c705213bb1714a206858eb646a597888fd323c07221f684f1a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-18', 1781749603, 1781749603, 'Active'),
(120, 1, '4d814b6c1fe0c10df89f8e8c9a08a44003760d17859dac7fdbec638dda605621', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-18', 1781751324, 1781751324, 'Signed Out'),
(121, 48, '4a91ccd02094c7bf7aa765286a759fe3e36deaa53206f31e93a4a9c9beb46386', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-18', 1781767812, 1781767812, 'Active'),
(122, 63, '62377bd7ec9a8aa8de5aaf0d016cecfb66d8717fecb35b54830665fb77382ae3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-18', 1781768052, 1781768052, 'Active'),
(123, 48, 'a23293cc1aedb56b410a5132e430c66bbe775a9d93fbfb0c2657ffed99ee62fe', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-19', 1781816169, 1781816169, 'Active'),
(124, 63, '55bd6bd22e1d8c1257fec03451a3541adbc754cb5b6bbf2a07d52ce49a4fa5e6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-19', 1781816618, 1781816618, 'Active'),
(125, 1, 'e7990e4b86858765ef85f755d0e1f637b811eec761f20fe5564ac68cfb2a0f71', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-19', 1781820814, 1781820814, 'Active'),
(126, 63, '8a2aa7ec2875e5cd71be6261763e18a0792bea86c570a89f03ad9c4100eeab1d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-20', 1781895889, 1781895889, 'Active'),
(127, 1, '23a0a0a2149dcf7240e403fb16a37f7327fd00c5615cd070300860ae3098181b', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-22', 1782083768, 1782083768, 'Active'),
(128, 63, '240770b3b8d10467940a979bb652b62ef43144e351d759df206192bbecfea525', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-22', 1782083811, 1782083811, 'Active'),
(129, 48, 'd06a37546e8faad872bd5a2551d2a6d3ab4d1fd67f3e534d2478219a60fa2ce5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-22', 1782098953, 1782098953, 'Signed Out'),
(130, 48, 'ab66a6ea7b476fea9fc5d9f503ff413166dae36d80b186d569be77bd53b8fca9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-22', 1782102476, 1782102476, 'Active'),
(131, 48, '38d865e80bae23cf717b952cd5567daad3c924d5f50dc51ca74500103740733c', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-22', 1782128707, 1782128707, 'Active'),
(132, 63, '830836140fea0167e93ffffb64f18c9ad9179aa6d01f86b7fd7f14db49f87b2a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-23', 1782129686, 1782129686, 'Active'),
(133, 63, 'b26b090efe0953cdaf9e3568848a218da58718334ec08a9b697c4ffc927ac3d6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-23', 1782155770, 1782155770, 'Active'),
(134, 48, 'a781c887df7e808724bccc657d4bc8bea1e39dfd38c6cc4427ab9af168381564', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-23', 1782155781, 1782155781, 'Active'),
(135, 48, '253e39716299540e498268b9a34384c977ae232467da82db6886128d51722fb0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-23', 1782188839, 1782188839, 'Active'),
(136, 63, 'c2e9c98d21bd172f6f2652eddeaab2b29676db4638321e1c1a2e0c6947b5b7e7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-23', 1782188851, 1782188851, 'Active'),
(137, 48, '6f11306cda1e549a901d6a4941936e56dc7d3cf7288f85c3ed2ed7a222b20bbd', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-25', 1782337140, 1782337140, 'Signed Out'),
(138, 1, '2d0caf6985e770ac2f94f5635af0024e8c4e51d6b1e274b1d2e5dbb026e10164', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-25', 1782339146, 1782339146, 'Active'),
(139, 63, '88dad0c59a152d3ee1c27ba4dbba74f9dfbe23e26f11c8333f29a155040ea92c', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-25', 1782344468, 1782344468, 'Active'),
(140, 63, '53ea79c8c2f51b07b884415c8af99a059dd0360bc10ece871457b8611dc93185', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-25', 1782356113, 1782356113, 'Active'),
(141, 1, 'fef8daea39b36167f134bc276799b25cfe8effcffd5ff9486107d02d570e820a', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-26', 1782400300, 1782400300, 'Active'),
(142, 63, 'bb0f3d0c8a355ef8025b9f3a19a3cff325bd2ed4bade2a14ebaefa85670a56d9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-26', 1782404457, 1782404457, 'Active'),
(143, 1, '208249fb1489e7237b10fecc6e6b2b856e183e71e3b733c310c1ff10440295e4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-26', 1782418603, 1782418603, 'Active'),
(144, 1, 'a58deafc2422a5fc17d920f8762b01a0f7458f4cba8925223cac72efd5a809a6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-29', 1782683144, 1782683144, 'Active'),
(145, 63, '732a6460a59b0461651d61b2ad289da21396cc9341ea6d38deb5b7fdf25ec21f', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-30', 1782796407, 1782796407, 'Signed Out'),
(146, 63, 'a392b8ffff12c85636853d351afb829e2d6cdf77558a78aa554bd51dc4589a84', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-06-30', 1782796429, 1782796429, 'Active'),
(147, 48, '031b7174b80b7c3d2e1b0473055253836681d5f85f004d92e13d87016149b1c4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-30', 1782796512, 1782796512, 'Signed Out'),
(148, 1, '5b69188ea2b2ed7d8ce3be0a838736522d7cd2b52f4c02b7e08e42f467e96c77', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-06-30', 1782798420, 1782798420, 'Active'),
(149, 48, '3b6ab5043268250ae9396283c48a11113ee0e87e377e714b8c03e6cf014f4276', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-06-30', 1782806130, 1782806130, 'Active'),
(150, 48, 'b0ffa4b0c6a9cf3f7bf0e5cda54ac41a0483d729f100aa94d21203f3a51a01b4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-07-01', 1782834203, 1782834203, 'Signed Out'),
(151, 63, '707e89d4ae43e4febe4ea861fac8100a205d0d0fcbe8c6c508aead513cc0b2de', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-07-01', 1782834260, 1782834260, 'Signed Out'),
(152, 1, '94b17042353170d0e602b7fe158e1904014f52eabbae9fba3038f56c7a8c53c8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-07-01', 1782841734, 1782841734, 'Active'),
(153, 48, '81719b5cc248b19f583b14ae63cde1eaafca24c328c988fd313315da66b4ce59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'Desktop', 'Windows', 'Firefox', 'Local', 'Local', '2026-07-01', 1782842022, 1782842022, 'Active'),
(154, 63, 'dbf2bea4512d80e748312d891cb8c5c88d5e994abdc3366f6f73f038860986c9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Desktop', 'Windows', 'Chrome', 'Local', 'Local', '2026-07-01', 1782854948, 1782854948, 'Active'),
(155, 48, '0455baf5b12f27a534cb36b5116c0864945eacea999070e0caf55b91a2fa1ed0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Desktop', 'Windows', 'Edge', 'Local', 'Local', '2026-07-01', 1782859502, 1782859502, 'Active');

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
